<?php

namespace App\Controllers;

use App\Models\PesanModel;
use App\Models\SiswaModel;
use App\Models\PembimbingModel;
use App\Models\ApplicationModel;

class Pesan extends BaseController
{
    protected PesanModel $pesanModel;

    public function __construct()
    {
        $this->pesanModel = new PesanModel();
    }

    /**
     * Main chat page - shows conversation list and optionally opens a chat
     */
    public function index(?int $partnerId = null)
    {
        if (!logged_in()) return redirect()->to('/login');

        $userId    = user_id();
        $userRoles = user()->getRoles();

        // Build list of people current user can chat with
        $contacts  = $this->getContacts($userId, $userRoles);
        $chatList  = $this->pesanModel->getConversationList($userId);
        $unread    = $this->pesanModel->countUnread($userId);

        $conversation = [];
        $partner      = null;

        if ($partnerId) {
            // Mark as read
            $this->pesanModel->markAsRead($partnerId, $userId);
            $conversation = $this->pesanModel->getConversation($userId, $partnerId);
            $partner      = $this->getPartnerInfo($partnerId);
        }

        return view('pesan', [
            'title'        => 'Pesan',
            'page_title'   => 'Pesan',
            'breadcrumb'   => ['Pesan'],
            'segment'      => ['pesan'],
            'contacts'     => $contacts,
            'chatList'     => $chatList,
            'conversation' => $conversation,
            'partner'      => $partner,
            'partner_id'   => $partnerId,
            'unread'       => $unread,
            'user_id'      => $userId,
        ]);
    }

    /**
     * Send a message via AJAX
     */
    public function send()
    {
        if (!logged_in()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $senderId   = user_id();
        $receiverId = (int) $this->request->getPost('receiver_id');
        $pesan      = trim($this->request->getPost('pesan'));

        if (!$receiverId || empty($pesan)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        // Validate that sender is allowed to message receiver
        if (!$this->canMessage($senderId, $receiverId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak diizinkan mengirim pesan ke pengguna ini']);
        }

        $saved = $this->pesanModel->insert([
            'sender_id'   => $senderId,
            'receiver_id' => $receiverId,
            'pesan'       => $pesan,
            'is_read'     => 0,
        ]);

        if ($saved) {
            // Send email notification async (fire and forget)
            $this->sendEmailNotification($senderId, $receiverId, $pesan);

            $msg = $this->pesanModel->find($this->pesanModel->getInsertID());
            return $this->response->setJSON([
                'success'    => true,
                'message'    => 'Pesan terkirim',
                'id'         => $msg->id,
                'pesan'      => $pesan,
                'created_at' => $msg->created_at,
                'sender_id'  => $senderId,
                'csrf_hash'  => csrf_hash(), // Return new hash
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengirim pesan']);
    }

    /**
     * Update a message via AJAX
     */
    public function updateMsg()
    {
        if (!logged_in()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $msgId = (int) $this->request->getPost('msg_id');
        $pesan = trim($this->request->getPost('pesan'));
        $userId = user_id();

        if (!$msgId || empty($pesan)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        $msg = $this->pesanModel->find($msgId);
        if (!$msg || $msg->sender_id != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak diizinkan mengubah pesan ini']);
        }

        $this->pesanModel->update($msgId, ['pesan' => $pesan]);

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Pesan diperbarui',
            'pesan'     => $pesan,
            'csrf_hash' => csrf_hash(),
        ]);
    }

    /**
     * Delete a message via AJAX
     */
    public function deleteMsg()
    {
        if (!logged_in()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $msgId = (int) $this->request->getPost('msg_id');
        $userId = user_id();

        if (!$msgId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap']);
        }

        $msg = $this->pesanModel->find($msgId);
        if (!$msg || $msg->sender_id != $userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Tidak diizinkan menghapus pesan ini']);
        }

        $this->pesanModel->delete($msgId);

        return $this->response->setJSON([
            'success'   => true,
            'message'   => 'Pesan dihapus',
            'csrf_hash' => csrf_hash(),
        ]);
    }

    /**
     * Poll for new messages (AJAX long polling / short poll)
     */
    public function poll(int $partnerId)
    {
        if (!logged_in()) {
            return $this->response->setJSON([]);
        }

        $userId    = user_id();
        $lastId    = (int) $this->request->getGet('last_id');

        // Mark as read first
        $this->pesanModel->markAsRead($partnerId, $userId);

        $newMessages = $this->pesanModel->db->table('pesan')
            ->select('pesan.*, su.username as sender_username')
            ->join('users su', 'su.id = pesan.sender_id', 'left')
            ->groupStart()
                ->where('pesan.sender_id', $partnerId)
                ->where('pesan.receiver_id', $userId)
            ->groupEnd()
            ->orGroupStart()
                ->where('pesan.sender_id', $userId)
                ->where('pesan.receiver_id', $partnerId)
            ->groupEnd()
            ->where('pesan.id >', $lastId)
            ->orderBy('pesan.created_at', 'ASC')
            ->get()->getResult();

        $unread = $this->pesanModel->countUnread($userId);

        return $this->response->setJSON([
            'messages' => $newMessages,
            'unread'   => $unread,
        ]);
    }

    /**
     * Get unread count (for navbar badge update)
     */
    public function unread()
    {
        if (!logged_in()) return $this->response->setJSON(['count' => 0]);
        return $this->response->setJSON(['count' => $this->pesanModel->countUnread(user_id())]);
    }

    // ----------------------------------------------------------------
    // Private helpers
    // ----------------------------------------------------------------

    private function getContacts(int $userId, array $roles): array
    {
        $contacts = [];
        $db       = \Config\Database::connect();

        $getStudentRole = function ($instansi, $kelas) {
            $instansiLower = strtolower($instansi ?? '');
            $kelasLower = strtolower($kelas ?? '');
            $isSmk = (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));
            return $isSmk ? 'Siswa' : 'Mahasiswa';
        };

        // Admin is always available for everyone to message
        if (!in_array('admin', $roles)) {
            $admins = $db->table('users')
                ->select('users.id as user_id, users.username as nama, "Admin" as role')
                ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                ->where('auth_groups.name', 'admin')
                ->get()->getResult();
            
            foreach ($admins as $admin) {
                $contacts[] = [
                    'user_id' => $admin->user_id,
                    'nama'    => 'Administrator (' . $admin->nama . ')',
                    'role'    => 'Admin',
                    'foto'    => null,
                ];
            }
        }

        if (in_array('siswa', $roles)) {
            // Student can message their assigned pembimbing lapangan only
            // (dosen pembimbing sudah tidak punya akun user, jadi tidak bisa dihubungi via pesan)
            $appModel  = new ApplicationModel();
            $accepted  = $appModel->where('id_siswa', (new SiswaModel())->where('user_id', $userId)->first()?->id ?? 0)
                                  ->whereIn('status', ['accepted', 'selesai'])
                                  ->first();

            if ($accepted) {
                // Pembimbing lapangan
                $tempat = $db->table('tempat_magang')->where('id', $accepted->id_tempat)->get()->getRow();
                if ($tempat && $tempat->pid) {
                    $p = $db->table('pembimbing')
                            ->join('users', 'users.id = pembimbing.user_id')
                            ->where('pembimbing.id', $tempat->pid)
                            ->get()->getRow();
                    if ($p) {
                        $contacts[] = [
                            'user_id' => $p->user_id,
                            'nama'    => $p->nama,
                            'role'    => 'Pembimbing Lapangan',
                            'foto'    => $p->foto ?? null,
                        ];
                    }
                }
            }
        }

        if (in_array('admin', $roles)) {
            // Admin can message any student or mentor
            $students = $db->table('siswa')->select('user_id, nama, foto, instansi, kelas')->get()->getResult();
            foreach ($students as $s) {
                $contacts[] = ['user_id' => $s->user_id, 'nama' => $s->nama, 'role' => $getStudentRole($s->instansi, $s->kelas), 'foto' => $s->foto];
            }
            $pembimbing = $db->table('pembimbing')->select('user_id, nama, foto')->get()->getResult();
            foreach ($pembimbing as $p) {
                $contacts[] = ['user_id' => $p->user_id, 'nama' => $p->nama, 'role' => 'Pembimbing Lapangan', 'foto' => $p->foto];
            }
            // Dosen pembimbing sudah tidak punya akun user, jadi tidak bisa dimasukkan ke daftar kontak
        }

        if (in_array('pembimbing', $roles)) {
            // Field supervisor: students in their division
            $pembimbing = $db->table('pembimbing')->where('user_id', $userId)->get()->getRow();
            if ($pembimbing) {
                // Students
                $students = $db->table('lamaran')
                    ->select('lamaran.id_siswa, siswa.user_id as s_uid, siswa.nama, siswa.foto, siswa.instansi, siswa.kelas')
                    ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                    ->join('siswa', 'siswa.id = lamaran.id_siswa')
                    ->where('tempat_magang.pid', $pembimbing->id)
                    ->whereIn('lamaran.status', ['accepted', 'selesai'])
                    ->get()->getResult();

                foreach ($students as $s) {
                    $contacts[] = [
                        'user_id' => $s->s_uid,
                        'nama'    => $s->nama,
                        'role'    => $getStudentRole($s->instansi, $s->kelas),
                        'foto'    => $s->foto ?? null,
                    ];
                }
            }
        }

        if (in_array('dosen_pembimbing', $roles) || in_array('instansi', $roles)) {
            // Academic supervisor: their students only
            $dosen = $db->table('instansi')->where('user_id', $userId)->get()->getRow();
            if ($dosen) {
                $lamarans = $db->table('lamaran')
                    ->select('lamaran.*, siswa.user_id as s_uid, siswa.nama as s_nama, siswa.foto as s_foto, siswa.instansi, siswa.kelas, 
                              tempat_magang.pid,
                              pembimbing.user_id as p_uid, pembimbing.nama as p_nama, pembimbing.foto as p_foto')
                    ->join('siswa', 'siswa.id = lamaran.id_siswa')
                    ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
                    ->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')
                    ->where('lamaran.id_dosen_pembimbing', $dosen->id)
                    ->whereIn('lamaran.status', ['accepted', 'selesai'])
                    ->get()->getResult();

                foreach ($lamarans as $l) {
                    // Student
                    $exists = array_filter($contacts, fn($c) => $c['user_id'] === (int)$l->s_uid);
                    if (empty($exists)) {
                        $contacts[] = [
                            'user_id' => (int)$l->s_uid,
                            'nama'    => $l->s_nama,
                            'role'    => $getStudentRole($l->instansi, $l->kelas),
                            'foto'    => $l->s_foto ?? null,
                        ];
                    }
                    // Field supervisor
                    if ($l->p_uid) {
                        $pExists = array_filter($contacts, fn($c) => $c['user_id'] === (int)$l->p_uid);
                        if (empty($pExists)) {
                            $contacts[] = [
                                'user_id' => (int)$l->p_uid,
                                'nama'    => $l->p_nama,
                                'role'    => 'Pembimbing Lapangan',
                                'foto'    => $l->p_foto ?? null,
                            ];
                        }
                    }
                }
            }
        }

        return $contacts;
    }

    /**
     * Verify that sender is allowed to message receiver
     */
    private function canMessage(int $senderId, int $receiverId): bool
    {
        $senderRoles   = user()->getRoles();
        $db            = \Config\Database::connect();

        // Get receiver roles
        $receiverRoles = $db->table('auth_groups_users')
            ->select('auth_groups.name')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where('user_id', $receiverId)
            ->get()->getResult();
        $receiverRoles = array_map(fn($r) => $r->name, $receiverRoles);

        // Admin can message anyone
        if (in_array('admin', $senderRoles)) return true;

        // Anyone can message an admin
        if (in_array('admin', $receiverRoles)) return true;

        // Siswa -> pembimbing (dosen tidak lagi punya akun user)
        if (in_array('siswa', $senderRoles) && in_array('pembimbing', $receiverRoles)) {
            $contacts = $this->getContacts($senderId, $senderRoles);
            return !empty(array_filter($contacts, fn($c) => (int)$c['user_id'] === (int)$receiverId));
        }

        // Pembimbing -> siswa
        if (in_array('pembimbing', $senderRoles) && in_array('siswa', $receiverRoles)) {
            $contacts = $this->getContacts($senderId, $senderRoles);
            return !empty(array_filter($contacts, fn($c) => (int)$c['user_id'] === (int)$receiverId));
        }

        // Dosen pembimbing / Instansi -> siswa atau pembimbing
        if ((in_array('dosen_pembimbing', $senderRoles) || in_array('instansi', $senderRoles)) && (in_array('siswa', $receiverRoles) || in_array('pembimbing', $receiverRoles))) {
            $contacts = $this->getContacts($senderId, $senderRoles);
            return !empty(array_filter($contacts, fn($c) => (int)$c['user_id'] === (int)$receiverId));
        }

        return false;
    }

    private function getPartnerInfo(int $partnerId): ?object
    {
        $db = \Config\Database::connect();

        $user = $db->table('users')
            ->select('users.id, users.username, users.email,
                COALESCE(s.nama, p.nama, i.nama_instansi) as nama,
                COALESCE(s.foto, p.foto) as foto,
                s.instansi as s_instansi, s.kelas as s_kelas,
                (SELECT GROUP_CONCAT(ag.name SEPARATOR ",") 
                 FROM auth_groups_users agu JOIN auth_groups ag ON ag.id = agu.group_id 
                 WHERE agu.user_id = users.id) as roles')
            ->join('siswa s', 's.user_id = users.id', 'left')
            ->join('pembimbing p', 'p.user_id = users.id', 'left')
            ->join('instansi i', 'i.user_id = users.id', 'left')
            ->where('users.id', $partnerId)
            ->get()->getRow();

        if ($user) {
            $roles = explode(',', $user->roles ?? '');
            $formattedRoles = [];
            foreach ($roles as $r) {
                if ($r === 'admin') {
                    $formattedRoles[] = 'Admin';
                } elseif ($r === 'pembimbing') {
                    $formattedRoles[] = 'Pembimbing Lapangan';
                } elseif ($r === 'dosen_pembimbing' || $r === 'instansi') {
                    $formattedRoles[] = 'Instansi Pendidikan';
                } elseif ($r === 'siswa') {
                    $instansiLower = strtolower($user->s_instansi ?? '');
                    $kelasLower = strtolower($user->s_kelas ?? '');
                    $isSmk = (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));
                    $formattedRoles[] = $isSmk ? 'Siswa' : 'Mahasiswa';
                }
            }
            $user->roles = implode(', ', $formattedRoles);
        }

        return $user;
    }

    /**
     * Send Gmail notification to receiver
     */
    private function sendEmailNotification(int $senderId, int $receiverId, string $pesan): void
    {
        try {
            $db           = \Config\Database::connect();
            $senderUser   = $db->table('users')
                ->select('users.email, COALESCE(s.nama, p.nama) as nama')
                ->join('siswa s', 's.user_id = users.id', 'left')
                ->join('pembimbing p', 'p.user_id = users.id', 'left')
                ->where('users.id', $senderId)
                ->get()->getRow();

            $receiverUser = $db->table('users')
                ->where('id', $receiverId)
                ->get()->getRow();

            if (!$receiverUser || empty($receiverUser->email)) return;

            $senderNama  = $senderUser->nama ?? 'Pengguna';
            $appUrl      = base_url('/pesan/' . $senderId);
            $shortPesan  = mb_strlen($pesan) > 100 ? mb_substr($pesan, 0, 100) . '...' : $pesan;

            $emailBody = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px;'>
                <div style='background: #1a1a2e; padding: 20px; border-radius: 8px 8px 0 0; text-align: center;'>
                    <h2 style='color: #fff; margin: 0;'>SIAMAM PLN ASAME</h2>
                    <p style='color: #aaa; margin: 5px 0 0;'>Sistem Informasi Magang</p>
                </div>
                <div style='background: #fff; padding: 30px; border-radius: 0 0 8px 8px;'>
                    <h3 style='color: #333;'>Pesan Baru dari {$senderNama}</h3>
                    <div style='background: #f0f4ff; border-left: 4px solid #3b82f6; padding: 15px; border-radius: 4px; margin: 20px 0;'>
                        <p style='margin: 0; color: #555; font-style: italic;'>\"{$shortPesan}\"</p>
                    </div>
                    <p style='color: #666;'>Klik tombol di bawah untuk membaca dan membalas pesan.</p>
                    <div style='text-align: center; margin: 25px 0;'>
                        <a href='{$appUrl}' 
                           style='background: #3b82f6; color: white; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: bold; display: inline-block;'>
                           Buka Pesan
                        </a>
                    </div>
                    <p style='color: #999; font-size: 12px; text-align: center; margin-top: 20px;'>
                        Email ini dikirim otomatis oleh Sistem SIAMAM. Jangan balas email ini.
                    </p>
                </div>
            </div>";

            $emailService = \Config\Services::email();
            $emailService->setFrom(
                env('SMTP_USER', 'siamam@maxinide.com'),
                'SIAMAM PLN ASAME'
            );
            $emailService->setTo($receiverUser->email);
            $emailService->setSubject("Pesan Baru dari {$senderNama} - SIAMAM");
            $emailService->setMailType('html');
            $emailService->setMessage($emailBody);
            $emailService->send(false);
        } catch (\Throwable $e) {
            // Silent fail - don't break the send flow
            log_message('error', 'Email notification failed: ' . $e->getMessage());
        }
    }
}