<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LogBook extends BaseController
{
    protected $logbooks;
    protected $validation;
    protected $rules;

    public function __construct()
    {
        $this->logbooks     = new \App\Models\LogBookModel();
        $this->validation   = \Config\Services::validation();
    }

    public function store()
    {
        if (in_groups('siswa') && !isSafetyCompleted(user_id())) {
            return $this->response->setJSON([
                'success' => false,
                'status' => 500,
                'message' => 'Anda wajib mengonfirmasi telah mempelajari Materi Safety & K3 di Dashboard sebelum dapat mengisi logbook/presensi.'
            ]);
        }

        $bukti      = $this->request->getFile('bukti');
        $filename   = $bukti->getRandomName();

        $data = [
            'id_siswa'      => $this->request->getPost('sid'),
            'id_tempat'     => $this->request->getPost('tid'),
            'id_pembimbing' => $this->request->getPost('pid'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'tanggal'       => $this->request->getPost('tanggal'),
        ];

        $file_rules = 'required|uploaded[bukti]|max_size[bukti,10240]|ext_in[bukti,png,jpg,jpeg]';
        if (!$this->validation->check($bukti, $file_rules)) {
            return $this->response->setJSON([
                'success'   => false,
                'status'    => 500,
                'message'   => "Bukti tidak valid, pastikan ukuran file tidak lebih dari 10MB dan format file adalah png, jpg, atau jpeg"
            ]);
        }

        $data['bukti'] = $filename;
        
        // rule for all data without bukti
        $rules = [
            'id_siswa'      => 'required|numeric',
            'id_tempat'     => 'required|numeric',
            'id_pembimbing' => 'required|numeric',
            'keterangan'    => 'required|in_list[hadir,izin,sakit,alfa]',
            'tanggal'       => 'required|valid_date[Y-m-d]',
        ];
        
        // if keterangan is = hadir
        if ($data['keterangan'] == 'hadir') {
            $data['jam_masuk']  = $this->request->getPost('jam_masuk');
            $data['jam_keluar'] = $this->request->getPost('jam_keluar');
            $data['kegiatan']   = $this->request->getPost('kegiatan');

            $this->rules['jam_masuk']   = 'required|valid_time';
            $this->rules['jam_keluar']  = 'required|valid_time';
            $this->rules['kegiatan']    = 'required|alpha_numeric_punct|min_length[20]';
        }

        if ($this->request->getPost('lid') !== null) {
            $data['id'] = $this->request->getPost('lid');
            $data['status'] = 'pending';
        } else {
            if ($this->logbooks->where([
                'tanggal'       => $data['tanggal'],
                'id_siswa'      => $data['id_siswa'],
                'id_tempat'     => $data['id_tempat'],
                'id_pembimbing' => $data['id_pembimbing'],
            ])->first() !== null) {
                return $this->response->setJSON([
                    'success'   => false,
                    'status'    => 500,
                    'message'   => 'Logbook pada tanggal tersebut sudah ada',
                ]);
            }
        }

        // validate all data without bukti
        $this->validation->setRules($rules);
        if (!$this->validation->run($data)) {
            return $this->response->setJSON([
                'success'   => false,
                'status'    => 500,
                'message'   => "Data tidak valid, pastikan semua data terisi dengan benar"
            ]);
        }

        if ($this->logbooks->save($data)) {
            $bukti->move('assets/img/logbook', $filename);
            
            // Send email notification to Admin, Pembimbing Lapangan, and Dosen Magang
            $email_status = $this->sendLogbookNotification($data);
            $msg_append = ($email_status === true) ? '' : ' (Namun notifikasi email gagal: ' . $email_status . ')';

            $response = [
                'success'   => true,
                'status'    => 200,
                'message'   => 'Data berhasil disimpan.' . $msg_append,
            ];
        } else {
            $response = [
                'success'   => false,
                'status'    => 500,
                'message'   => 'Data gagal disimpan',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function status_update()
    {
        $id = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        $data = [
            'status' => $status,
        ];

        if ($this->logbooks->update($id, $data)) {
            if ($status == 'accepted') {
                $this->sendEmailNotifikasiLogbookSiswa($id, 'status_acc');
            } elseif ($status == 'rejected') {
                $this->sendEmailNotifikasiLogbookSiswa($id, 'status_reject');
            }
            
            $response = [
                'success'   => true,
                'status'    => 200,
                'message'   => 'Status berhasil diubah',
            ];
        } else {
            $response = [
                'success'   => false,
                'status'    => 500,
                'message'   => 'Status gagal diubah',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function add_catatan_dosen()
    {
        $id = $this->request->getPost('id');
        $catatan = $this->request->getPost('catatan_dosen');

        if ($this->logbooks->update($id, ['catatan_dosen' => $catatan, 'is_read_dosen' => 0])) {
            $this->sendEmailNotifikasiLogbookSiswa($id, 'catatan_dosen');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Catatan bimbingan berhasil disimpan'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Catatan bimbingan gagal disimpan'
        ]);
    }

    private function sendEmailNotifikasiLogbookSiswa($id_logbook, $type)
    {
        try {
            $db = \Config\Database::connect();
            $logbook = $db->table('logbook')->where('id', $id_logbook)->get()->getRow();
            if (!$logbook) return false;

            $siswa = $db->table('siswa')->where('id', $logbook->id_siswa)->get()->getRow();
            if (!$siswa) return false;

            $user = $db->table('users')->where('id', $siswa->user_id)->get()->getRow();
            if (!$user || empty($user->email)) return false;

            $email = \Config\Services::email();
            $email->clear();
            $email->setFrom(
                env('SMTP_USER', 'siamam@maxinide.com'),
                'Sistem Informasi Magang'
            );
            $email->setTo($user->email);
            
            $tanggal_logbook = date('d F Y', strtotime($logbook->tanggal));

            if ($type == 'status_acc') {
                if ($logbook->status != 'accepted') return true;
                $email->setSubject('Status Logbook Diterima');
                $email->setMessage("Halo {$siswa->nama},<br><br>Logbook/absensi magang Anda untuk tanggal <b>{$tanggal_logbook}</b> telah <b>DISETUJUI (ACC)</b> oleh Pembimbing Lapangan.<br>Silakan periksa secara berkala status logbook Anda yang lain.<br><br>Terima kasih.");
            } else if ($type == 'status_reject') {
                if ($logbook->status != 'rejected') return true;
                $email->setSubject('Status Logbook Ditolak');
                $email->setMessage("Halo {$siswa->nama},<br><br>Logbook/absensi magang Anda untuk tanggal <b>{$tanggal_logbook}</b> telah <b>DITOLAK</b> oleh Pembimbing Lapangan.<br>Silakan periksa kembali dan ajukan ulang jika diperlukan.<br><br>Terima kasih.");
            } else if ($type == 'catatan_dosen') {
                $email->setSubject('Catatan Baru dari Dosen Pembimbing');
                $email->setMessage("Halo {$siswa->nama},<br><br>Dosen Pembimbing Magang Anda telah memberikan catatan/bimbingan baru pada logbook tanggal <b>{$tanggal_logbook}</b>.<br><br><b>Catatan:</b><br>\"" . nl2br(htmlspecialchars($logbook->catatan_dosen)) . "\"<br><br>Silakan login ke sistem untuk melihat detailnya.<br><br>Terima kasih.");
            } else {
                return false;
            }

            $email->setMailType('html');
            $email->send(false);
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function sendLogbookNotification($data)
    {
        try {
            $db = \Config\Database::connect();
            
            // Get Siswa info
            $siswa = $db->table('siswa')->where('id', $data['id_siswa'])->get()->getRow();
            if (!$siswa) return false;

            // Get Tempat info
            $tempat = $db->table('tempat_magang')->where('id', $data['id_tempat'])->get()->getRow();
            $tempat_name = $tempat ? $tempat->nama : 'Tidak diketahui';

            // Find Application to get Dosen Pembimbing
            $app = $db->table('lamaran')
                      ->where('id_siswa', $data['id_siswa'])
                      ->where('id_tempat', $data['id_tempat'])
                      ->whereIn('status', ['accepted', 'selesai'])
                      ->get()->getRow();
            
            $emails = [];

            // 1. Get Admins emails
            $admins = $db->table('users')
                         ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                         ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                         ->where('auth_groups.name', 'admin')
                         ->get()->getResult();
            foreach ($admins as $a) {
                if (!empty($a->email)) $emails[] = $a->email;
            }

            // 2. Get Pembimbing Lapangan email
            $pembimbing = $db->table('pembimbing')->where('id', $data['id_pembimbing'])->get()->getRow();
            if ($pembimbing) {
                $user_p = $db->table('users')->where('id', $pembimbing->user_id)->get()->getRow();
                if ($user_p && !empty($user_p->email)) $emails[] = $user_p->email;
            }

            // 3. Get Dosen Magang email
            if ($app && !empty($app->id_dosen_pembimbing)) {
                $dosen = $db->table('dosen_pembimbing')->where('id', $app->id_dosen_pembimbing)->get()->getRow();
                if ($dosen) {
                    $user_d = $db->table('users')->where('id', $dosen->user_id)->get()->getRow();
                    if ($user_d && !empty($user_d->email)) $emails[] = $user_d->email;
                }
            }

            // Unique emails
            $emails = array_unique($emails);

            if (empty($emails)) {
                return 'Daftar tujuan email (Admin/Pembimbing) kosong atau tidak valid.';
            }

            $status_kehadiran = strtoupper($data['keterangan']);
            $tanggal = $data['tanggal'];

            $email = \Config\Services::email();
            
            $msg = "Halo,<br><br>";
            $msg .= "Menginformasikan bahwa siswa magang berikut telah mengisi absensi/logbook:<br><br>";
            $msg .= "<b>Nama:</b> {$siswa->nama}<br>";
            $msg .= "<b>NIM:</b> {$siswa->nim}<br>";
            $msg .= "<b>Divisi:</b> {$tempat_name}<br>";
            $msg .= "<b>Tanggal:</b> {$tanggal}<br>";
            $msg .= "<b>Status Kehadiran:</b> {$status_kehadiran}<br>";
            if ($data['keterangan'] == 'hadir') {
                $msg .= "<b>Jam Masuk:</b> {$data['jam_masuk']}<br>";
                $msg .= "<b>Jam Keluar:</b> {$data['jam_keluar']}<br>";
                $msg .= "<b>Kegiatan:</b> " . nl2br(htmlspecialchars($data['kegiatan'])) . "<br>";
            }
            $msg .= "<br>Silakan periksa sistem untuk melihat detail logbook/presensi selengkapnya.<br><br>Terima kasih.";

            $errs = [];
            foreach ($emails as $recipient) {
                $email->clear();
                $email->setFrom(
                    env('SMTP_USER', 'siamam@maxinide.com'),
                    'SIAMAM PLN ASAME'
                );
                $email->setTo($recipient);
                $email->setSubject("Notifikasi Absensi Logbook: {$siswa->nama} ({$status_kehadiran})");
                $email->setMailType('html');
                $email->setMessage($msg);
                
                if (! $email->send(false)) {
                    $errs[] = "Ditolak saat mengirim ke $recipient";
                }
            }

            if (!empty($errs)) {
                return implode(', ', $errs);
            }

            return true;
        } catch (\Throwable $e) {
            return 'Sistem Error: ' . $e->getMessage() . ' (Baris ' . $e->getLine() . ')';
        }
    }
}
