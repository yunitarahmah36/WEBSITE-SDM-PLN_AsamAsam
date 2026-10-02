<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Application extends BaseController
{

    protected $app;

    public function __construct()
    {
        $this->app = new \App\Models\ApplicationModel();
    }

    public function daftar()
    {
        $id_siswa = getSidByUid(user_id());
        $id_tempat = $this->request->getPost('tid');

        if (!$id_siswa) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Sesi pendaftaran tidak valid: Data Siswa Anda tidak ditemukan. Harap pastikan Anda login sebagai siswa.'
            ]);
        }

        // Prevent registering to the same division if already rejected
        $this_app = $this->app->where(['id_siswa' => $id_siswa, 'id_tempat' => $id_tempat])->first();
        if ($this_app && in_array($this_app->status, ['rejected', 'reject by system'])) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Anda sudah pernah mendaftar di divisi ini dan telah ditolak. Silakan mendaftar di divisi lain.'
            ]);
        }

        $data = [
            'id_siswa' => $id_siswa,
            'id_tempat' => $id_tempat
        ];

        // Check if student already has an active application (pending/accepted/selesai)
        $existing_apps = getApplicationSiswa($id_siswa);

        $accepted = 0;
        $pending = 0;
        $already_applied = false;

        foreach ($existing_apps as $ap) {
            if (in_array($ap->status, ['accepted', 'selesai'])) {
                $accepted++;
            }
            if ($ap->status == 'pending') {
                $pending++;
                if ($ap->id_tempat == $id_tempat) {
                    $already_applied = true;
                }
            }
        }

        if ($accepted > 0) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Anda sudah diterima di tempat magang lain'
            ]);
        }

        if ($already_applied) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Anda sudah mendaftar di divisi ini'
            ]);
        }

        if ($pending >= 2) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Anda hanya dapat mendaftar maksimal 2 divisi magang sekaligus. Tunggu keputusan admin.'
            ]);
        }

        // Check slot availability
        if (getSlotAvailable($id_tempat) <= 0) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Kuota tempat magang sudah penuh'
            ]);
        } else {
            if ($this->app->insert($data)) {
                $this->sendEmailNotifPendaftaranBaru($id_siswa, $id_tempat);
                return $this->response->setJSON([
                    'status'    => 200,
                    'success'   => true,
                    'message'   => 'Permintaan magang berhasil diajukan'
                ]);
            } else {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => $this->app->errors()
                ]);
            }
        }
    }

    public function destroy($id)
    {
        if ($this->app->delete($id)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Permintaan magang berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => $this->app->errors()
            ]);
        }
    }

    public function update()
    {
        $data = $this->request->getPost();
        $id = $data['item'];
        unset($data['item']);

        if (isset($data['id_dosen_pembimbing']) && empty($data['id_dosen_pembimbing'])) {
            $data['id_dosen_pembimbing'] = null;
        }

        $id_siswa = $this->app->select('id_siswa')->where('id', $id)->first()->id_siswa;
        $id_tempat = $this->app->select('id_tempat')->where('id', $id)->first()->id_tempat;

        $accepted = $this->app->where('id_tempat', $id_tempat)->where('status', 'accepted')->findAll();

        if ($this->app->update($id, $data)) {
            
            $this->sendEmailNotifPendaftaran($id_siswa, $data['status'], $id_tempat);
            
            // Sync main student active status
            $this->syncUserActiveStatus($id_siswa, $data['status']);

            if ($data['status'] == 'accepted') {
                $other_lamaran = $this->app->where('id_siswa', $id_siswa)->where('id !=', $id)->findAll();
                $this->app->where('id_siswa', $id_siswa)->where('id !=', $id)->set(['status' => 'reject by system'])->update();
                foreach($other_lamaran as $ol) {
                    $this->sendEmailNotifPendaftaran($id_siswa, 'reject by system', $ol->id_tempat);
                    $this->syncUserActiveStatus($id_siswa, 'reject by system');
                }

                $tempat = new \App\Models\TempatModel();
                $tempat = $tempat->find($id_tempat);

                // Add 1 to accepted count since we just accepted one
                if ((count($accepted) + 1) >= $tempat->kuota) {
                    $lam = $this->app->where('id_tempat', $id_tempat)->where('status', 'pending')->findAll();
                    foreach ($lam as $a) {
                        $this->app->update($a->id, ['status' => 'reject by system']);
                        $this->sendEmailNotifPendaftaran($a->id_siswa, 'reject by system', $id_tempat);
                        $this->syncUserActiveStatus($a->id_siswa, 'reject by system');
                    }
                }
            }
            
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Permintaan magang berhasil diubah'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => $this->app->errors()
            ]);
        }
    }

    private function syncUserActiveStatus($id_siswa, $status)
    {
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->find($id_siswa);
        if ($siswa) {
            $userModel = new \Myth\Auth\Models\UserModel();
            if ($status == 'accepted' || $status == 'selesai') {
                $userModel->update($siswa->user_id, ['active' => 1]);
            }
        }
    }

    private function sendEmailNotifPendaftaran($id_siswa, $status, $id_tempat)
    {
        try {
            $db = \Config\Database::connect();
            $siswa = $db->table('siswa')->where('id', $id_siswa)->get()->getRow();
            if (!$siswa) return 'Data siswa tidak ditemukan';

            $user = $db->table('users')->where('id', $siswa->user_id)->get()->getRow();
            if (!$user || empty($user->email)) return 'Email siswa tidak valid/kosong';

            $tempat = $db->table('tempat_magang')->where('id', $id_tempat)->get()->getRow();
            $nama_tempat = $tempat && isset($tempat->nama) ? $tempat->nama : 'Instansi Tujuan';

            $email = \Config\Services::email();
            $email->clear();
            $email->setFrom(
                env('SMTP_USER', 'siamam@maxinide.com'),
                'Sistem Informasi Magang'
            );
            $email->setTo($user->email);
            
            if ($status == 'accepted') {
                $email->setSubject('Selamat! Lamaran Magang Anda Diterima');
                $email->setMessage("Halo {$siswa->nama},<br><br>Selamat! Pendaftaran magang Anda di <b>{$nama_tempat}</b> telah <b>DITERIMA</b>.<br>Silakan login ke sistem untuk memeriksa dan mengunduh Surat Pengantar Magang Anda.<br><br>Terima kasih.");
            } else if ($status == 'rejected') {
                $email->setSubject('Informasi Pendaftaran Magang');
                $email->setMessage("Halo {$siswa->nama},<br><br>Mohon maaf, pendaftaran magang Anda di <b>{$nama_tempat}</b> saat ini <b>DITOLAK</b> oleh Admin karena berkas belum sesuai atau kuota penuh.<br><br>Terima kasih.");
            } else if ($status == 'selesai') {
                $email->setSubject('Informasi Magang Selesai');
                $email->setMessage("Halo {$siswa->nama},<br><br>Selamat! Status magang Anda di <b>{$nama_tempat}</b> telah diperbarui menjadi <b>SELESAI</b>.<br>Silakan login ke sistem untuk mengunggah Laporan Akhir magang Anda jika belum.<br><br>Terima kasih.");
            } else if ($status == 'reject by system') {
                $email->setSubject('Informasi Pendaftaran Magang (Dibatalkan Sistem)');
                $email->setMessage("Halo {$siswa->nama},<br><br>Pendaftaran magang Anda di <b>{$nama_tempat}</b> telah <b>DIBATALKAN OLEH SISTEM</b> karena Anda telah diterima di instansi lain, atau kuota instansi tujuan sudah penuh.<br><br>Terima kasih.");
            } else {
                return true;
            }

            $email->setMailType('html');
            $email->send(false);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function sendEmailNotifPendaftaranBaru($id_siswa, $id_tempat)
    {
        try {
            $db = \Config\Database::connect();
            
            // 1. Get Student details
            $siswa = $db->table('siswa')->where('id', $id_siswa)->get()->getRow();
            if (!$siswa) return false;
            
            // 2. Get Division details
            $tempat = $db->table('tempat_magang')->where('id', $id_tempat)->get()->getRow();
            if (!$tempat) return false;
            
            $nama_tempat = $tempat->nama ?? 'Instansi Tujuan';
            $pembimbing_id = $tempat->pid;
            
            // 3. Get Pembimbing Lapangan email
            $pembimbing_email = null;
            if ($pembimbing_id) {
                $pembimbing = $db->table('pembimbing')->where('id', $pembimbing_id)->get()->getRow();
                if ($pembimbing && !empty($pembimbing->email)) {
                    $pembimbing_email = $pembimbing->email;
                }
            }
            
            // 4. Get Admin emails
            $admins = $db->table('users')
                ->select('users.email')
                ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                ->where('auth_groups.name', 'admin')
                ->get()->getResultArray();
            $admin_emails = array_filter(array_column($admins, 'email'));
            
            // Collect all recipient emails
            $recipients = [];
            
            // Add division email
            if (!empty($tempat->email)) {
                $recipients[] = $tempat->email;
            }
            
            if ($pembimbing_email && !in_array($pembimbing_email, $recipients)) {
                $recipients[] = $pembimbing_email;
            }
            foreach ($admin_emails as $admin_email) {
                if (!in_array($admin_email, $recipients)) {
                    $recipients[] = $admin_email;
                }
            }
            
            if (empty($recipients)) {
                return false;
            }
            
            // Format current date and time
            $tanggal = date('d-m-Y');
            $jam = date('H:i:s');
            
            // 5. Send Email
            $email = \Config\Services::email();
            
            foreach ($recipients as $recipient) {
                $email->clear();
                $email->setFrom(
                    env('SMTP_USER', 'siamam@maxinide.com'),
                    'Sistem Informasi Magang'
                );
                $email->setTo($recipient);
                $email->setSubject('Notifikasi Pendaftaran Magang Baru - ' . $siswa->nama);
                
                $message = "Halo Admin / Pembimbing / Divisi,<br><br>
                Mahasiswa/siswa ini telah mendaftar ke divisi ini. Berikut rincian data pendaftaran:<br><br>
                <table>
                    <tr>
                        <td style='width: 150px; font-weight: bold;'>Nama Siswa</td>
                        <td>: {$siswa->nama}</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>NIM/NIS</td>
                        <td>: {$siswa->nim}</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Divisi/Instansi</td>
                        <td>: {$nama_tempat}</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Tanggal Daftar</td>
                        <td>: {$tanggal}</td>
                    </tr>
                    <tr>
                        <td style='font-weight: bold;'>Jam Daftar</td>
                        <td>: {$jam}</td>
                    </tr>
                </table>
                <br>
                Silakan login ke sistem untuk memproses pendaftaran siswa tersebut.<br><br>
                Terima kasih.";
                
                $email->setMessage($message);
                $email->setMailType('html');
                $email->send(false);
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function upload_template()
    {
        $file = $this->request->getFile('template_surat');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $dir = FCPATH . 'assets/berkas/';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            if (file_exists($dir . 'template_surat_magang.pdf')) {
                @unlink($dir . 'template_surat_magang.pdf');
            }
            if (file_exists($dir . 'template_surat_magang.docx')) {
                @unlink($dir . 'template_surat_magang.docx');
            }
            if (file_exists($dir . 'template_surat_magang.doc')) {
                @unlink($dir . 'template_surat_magang.doc');
            }
            
            $ext = $file->getClientExtension();
            $file->move($dir, 'template_surat_magang.' . $ext);
            
            return redirect()->back()->with('success', 'Template Surat Magang berhasil diupload');
        }
        
        return redirect()->back()->with('error', 'Gagal mengupload template. Silakan coba lagi.');
    }
}
