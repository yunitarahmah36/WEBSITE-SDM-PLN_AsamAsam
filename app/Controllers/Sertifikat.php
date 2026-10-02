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

        $lamaran = $lamaranModel->select('lamaran.*, tempat_magang.nama as tempat, pembimbing.nama as nama_pembimbing')
                                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                                ->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')
                                ->where('id_siswa', $sid)
                                ->where('lamaran.status', 'selesai')
                                ->first();
        
        // Wait, if it's finished, maybe it's still 'accepted' and just the date has passed?
        if (!$lamaran) {
            $lamaran = $lamaranModel->select('lamaran.*, tempat_magang.nama as tempat, pembimbing.nama as nama_pembimbing')
                                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                                ->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')
                                ->where('id_siswa', $sid)
                                ->where('lamaran.status', 'accepted')
                                ->first();
        }

        if (!$lamaran) {
            return redirect()->back()->with('error', 'Siswa belum memiliki divisi magang atau status magang belum selesai.');
        }

        // Check if certificate is manual and uploaded
        if ($siswa->sertifikat_mode === 'manual') {
            if (!empty($siswa->sertifikat_file)) {
                $filePath = FCPATH . 'assets/sertifikat/' . $siswa->sertifikat_file;
                if (file_exists($filePath)) {
                    return $this->response->download($filePath, null)->setFileName("Sertifikat_Magang_{$siswa->nim}_{$siswa->nama}." . pathinfo($siswa->sertifikat_file, PATHINFO_EXTENSION));
                } else {
                    return redirect()->back()->with('error', 'File sertifikat manual tidak ditemukan di server.');
                }
            } else {
                return redirect()->back()->with('error', 'Sertifikat manual belum diupload oleh Admin/Pembimbing.');
            }
        }

        // NEW: Workflow Checklist
        $instansiLower = strtolower($siswa->instansi ?? '');
        $kelasLower = strtolower($siswa->kelas ?? '');
        $isSmk = (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));

        if (!$siswa->is_safety_ready) {
            return redirect()->back()->with('error', 'Sertifikat tidak dapat diterbitkan: Siswa belum mengonfirmasi Materi Safety & K3.');
        }

        if (!$siswa->is_laporan_approved && !($isSmk && empty($siswa->laporan))) {
            return redirect()->back()->with('error', 'Sertifikat tidak dapat diterbitkan: Laporan akhir belum disetujui oleh pembimbing.');
        }

        $nilai = $nilaiModel->where('ids', $sid)->first();

        if (!$nilai) {
            return redirect()->back()->with('error', 'Nilai belum diinput lengkap.');
        }

        // Hitung rata-rata: Normalisasi nilai pembimbing (skala 10) ke skala 100
        $items_pembimbing = [
            $nilai->n_disiplin, $nilai->n_motivasi, $nilai->n_kehadiran, 
            $nilai->n_kreatifitas, $nilai->n_kejujuran, $nilai->n_kesopanan, 
            $nilai->n_kerjasama
        ];
        $includeLaporan = !$isSmk || !empty($siswa->laporan);
        if ($includeLaporan) {
            $items_pembimbing[] = $nilai->n_laporan;
        }
        
        $total_pembimbing = 0;
        foreach ($items_pembimbing as $ni) {
            // Jika nilai <= 10, asumsikan skala 1-10 dan konversi ke skala 100
            $total_pembimbing += ($ni <= 10) ? ($ni * 10) : $ni;
        }

        $total_nilai = $total_pembimbing;
        $pembagi = $includeLaporan ? 8 : 7;

        if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) {
            $total_nilai += $nilai->n_dosen;
            $pembagi++;
        }
        $rata = round($total_nilai / $pembagi, 2);

        $grade = "Sangat Baik";
        if ($rata < 85 && $rata >= 75) $grade = "Baik";
        elseif ($rata < 75 && $rata >= 60) $grade = "Cukup";
        elseif ($rata < 60) $grade = "Kurang";

        $data = [
            'siswa' => $siswa,
            'lamaran' => $lamaran,
            'nilai' => $nilai,
            'rata' => $rata,
            'grade' => $grade,
            'isSmk' => $isSmk
        ];

        try {
            $html = view('sertifikat/template', $data);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);

            $dompdf = new Dompdf($options);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->loadHtml($html);
            $dompdf->render();

            $dompdf->stream("Sertifikat_Magang_{$siswa->nim}_{$siswa->nama}.pdf", ["Attachment" => true]);
            exit;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    public function kelola()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('siswa')
            ->select('siswa.id, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.sertifikat_mode, siswa.sertifikat_file, tempat_magang.nama as tempat, lamaran.status as status_lamaran, siswa.is_safety_ready, siswa.is_laporan_approved')
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
        $sid = $this->request->getPost('id_siswa');
        $file = $this->request->getFile('sertifikat_file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        if ($file->getExtension() !== 'pdf') {
            return redirect()->back()->with('error', 'Sertifikat harus berupa file PDF.');
        }

        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->find($sid);
        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
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
            return redirect()->back()->with('success', 'Sertifikat manual berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah sertifikat.');
    }
}
