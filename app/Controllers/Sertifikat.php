<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

class Sertifikat extends BaseController
{
    public function download($sid)
    {
        // Must be logged in and either the student themselves, an admin, or a pembimbing
        if (!in_groups('admin') && !in_groups('pembimbing') && getSidByUid(user_id()) != $sid) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke sertifikat ini.');
        }

        $siswaModel = new \App\Models\SiswaModel();
        $nilaiModel = new \App\Models\NilaiModel();
        $lamaranModel = new \App\Models\ApplicationModel();

        $siswa = $siswaModel->select('siswa.*, angkatan.tahun')->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')->where('siswa.id', $sid)->first();
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        // Verification for pembimbing: must be their student
        if (in_groups('pembimbing') && !in_groups('admin')) {
            $idp = getPidByUid(user_id());
            $checkPembimbing = $lamaranModel->select('lamaran.id')
                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                ->where('lamaran.id_siswa', $sid)
                ->where('tempat_magang.pid', $idp)
                ->first();
            if (!$checkPembimbing) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data siswa ini.');
            }
        }

        $lamaran = $lamaranModel->select('lamaran.*, tempat_magang.nama as tempat, pembimbing.nama as nama_pembimbing')
                                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                                ->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')
                                ->where('id_siswa', $sid)
                                ->whereIn('lamaran.status', ['accepted', 'selesai'])
                                ->first();

        if (!$lamaran) {
            return redirect()->back()->with('error', 'Siswa belum memiliki divisi magang atau status magang belum selesai.');
        }

        // Sertifikat resmi WAJIB sudah diunggah oleh admin/pembimbing
        if (empty($siswa->sertifikat_file)) {
            return redirect()->back()->with('error', 'Sertifikat belum dapat diunduh karena belum diunggah oleh Admin atau Pembimbing PKL.');
        }

        $filePath = FCPATH . 'assets/sertifikat/' . $siswa->sertifikat_file;
        if (file_exists($filePath)) {
            return $this->response->download($filePath, null)->setFileName("Sertifikat_Magang_{$siswa->nim}_{$siswa->nama}." . pathinfo($siswa->sertifikat_file, PATHINFO_EXTENSION));
        } else {
            return redirect()->back()->with('error', 'Berkas sertifikat resmi tidak ditemukan di server.');
        }
    }

    public function kelola()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('siswa')
            ->select('siswa.id, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.sertifikat_mode, siswa.sertifikat_file, siswa.laporan, tempat_magang.nama as tempat, lamaran.status as status_lamaran, siswa.is_safety_ready, siswa.is_laporan_approved')
            ->join('lamaran', 'lamaran.id_siswa = siswa.id')
            ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
            ->whereIn('lamaran.status', ['accepted', 'selesai']);

        if (in_groups('pembimbing')) {
            $idp = getPidByUid(user_id());
            $builder->where('tempat_magang.pid', $idp);
        }

        $siswa = $builder->get()->getResult();

        $nilaiModel = new \App\Models\NilaiModel();
        foreach ($siswa as $s) {
            $nilai = $nilaiModel->where('ids', $s->id)->first();
            $s->has_nilai = !empty($nilai);

            $instansiLower = strtolower($s->instansi ?? '');
            $kelasLower = strtolower($s->kelas ?? '');
            $isSmk = (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));
            $s->is_smk = $isSmk;
            $s->is_syarat_lengkap = $s->is_safety_ready && ($s->is_laporan_approved || ($isSmk && empty($s->laporan))) && $s->has_nilai;
        }

        return view('sertifikat/kelola', [
            'title'      => 'Magang | Kelola Sertifikat',
            'page_title' => 'Kelola Sertifikat Siswa',
            'segment'    => $this->request->getUri()->getSegments(),
            'breadcrumb' => ['Sertifikat', 'Kelola'],
            'siswa'      => $siswa
        ]);
    }

    public function updateMode()
    {
        $sid = $this->request->getPost('id_siswa');
        $mode = $this->request->getPost('mode');

        if (!in_array($mode, ['otomatis', 'manual'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Mode tidak valid.'
            ]);
        }

        $siswaModel = new \App\Models\SiswaModel();
        if ($siswaModel->update($sid, ['sertifikat_mode' => $mode])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Mode sertifikat berhasil diubah.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengubah mode sertifikat.'
        ]);
    }

    public function upload()
    {
        if (!in_groups('admin') && !in_groups('pembimbing')) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki hak akses untuk mengunggah sertifikat.');
        }

        $sid = $this->request->getPost('id_siswa');
        $file = $this->request->getFile('sertifikat_file');

        if (!$sid) {
            return redirect()->back()->with('error', 'Data siswa tidak valid.');
        }

        // If pembimbing, verify that this student is assigned to their division
        if (in_groups('pembimbing') && !in_groups('admin')) {
            $idp = getPidByUid(user_id());
            $db = \Config\Database::connect();
            $check = $db->table('lamaran')
                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                ->where('lamaran.id_siswa', $sid)
                ->where('tempat_magang.pid', $idp)
                ->countAllResults();
            if ($check == 0) {
                return redirect()->back()->with('error', 'Anda tidak berhak mengunggah sertifikat untuk siswa ini.');
            }
        }

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau belum dipilih.');
        }

        if (strtolower($file->getExtension()) !== 'pdf') {
            return redirect()->back()->with('error', 'Sertifikat harus berupa file PDF.');
        }

        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->find($sid);
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        // Validasi kelengkapan seluruh persyaratan magang sebelum sertifikat boleh diunggah
        $instansiLower = strtolower($siswa->instansi ?? '');
        $kelasLower = strtolower($siswa->kelas ?? '');
        $isSmk = (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));

        if (!$siswa->is_safety_ready) {
            return redirect()->back()->with('error', 'Sertifikat tidak dapat diunggah: Siswa belum menyelesaikan Materi Safety & K3.');
        }

        if (!$siswa->is_laporan_approved && !($isSmk && empty($siswa->laporan))) {
            return redirect()->back()->with('error', 'Sertifikat tidak dapat diunggah: Laporan akhir siswa belum disetujui.');
        }

        $nilaiModel = new \App\Models\NilaiModel();
        $nilai = $nilaiModel->where('ids', $sid)->first();
        if (!$nilai) {
            return redirect()->back()->with('error', 'Sertifikat tidak dapat diunggah: Nilai capaian magang siswa belum diinputkan.');
        }

        if (!empty($siswa->sertifikat_file)) {
            $oldPath = FCPATH . 'assets/sertifikat/' . $siswa->sertifikat_file;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $newName = time() . '_' . $file->getRandomName();
        $uploadDir = FCPATH . 'assets/sertifikat';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        if ($file->move($uploadDir, $newName)) {
            $siswaModel->update($sid, [
                'sertifikat_file' => $newName,
                'sertifikat_mode' => 'manual'
            ]);
            return redirect()->back()->with('success', 'Sertifikat resmi bernama untuk ' . esc($siswa->nama) . ' berhasil diunggah. Siswa sekarang dapat mengunduh sertifikat.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah sertifikat.');
    }

    public function deleteUpload()
    {
        if (!in_groups('admin') && !in_groups('pembimbing')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        $sid = $this->request->getPost('id_siswa');
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->find($sid);

        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        // If pembimbing, verify permission
        if (in_groups('pembimbing') && !in_groups('admin')) {
            $idp = getPidByUid(user_id());
            $db = \Config\Database::connect();
            $check = $db->table('lamaran')
                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                ->where('lamaran.id_siswa', $sid)
                ->where('tempat_magang.pid', $idp)
                ->countAllResults();
            if ($check == 0) {
                return redirect()->back()->with('error', 'Anda tidak berhak menghapus sertifikat untuk siswa ini.');
            }
        }

        if (!empty($siswa->sertifikat_file)) {
            $oldPath = FCPATH . 'assets/sertifikat/' . $siswa->sertifikat_file;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
            $siswaModel->update($sid, ['sertifikat_file' => null]);
            return redirect()->back()->with('success', 'Berkas sertifikat resmi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Berkas sertifikat tidak ditemukan.');
    }
}
