<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class Alumni extends BaseController
{
    protected $siswaModel;
    protected $applicationModel;

    public function __construct()
    {
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->applicationModel = new \App\Models\ApplicationModel();
    }

    public function index()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        syncStatusSelesai();

        $alumni = $this->applicationModel->select('siswa.id as id_siswa, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.dosen_nama, siswa.foto, siswa.surat_permohonan, siswa.cv, siswa.surat_pernyataan, siswa.laporan, siswa.sertifikat_file, siswa.sertifikat_mode, siswa.alumni_foto, siswa.alumni_testimoni, siswa.alumni_is_tampil, tempat_magang.nama as divisi, siswa.tgl_masuk, siswa.tgl_selesai, angkatan.tgl_mulai as angkatan_tgl_mulai, angkatan.tgl_selesai as angkatan_tgl_selesai, lamaran.status as lamaran_status')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
            ->where('lamaran.status', 'selesai')
            ->orderBy('siswa.tgl_selesai', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Alumni',
            'page_title' => 'Kelola Galeri & Testimoni Alumni',
            'segment' => ['alumni'],
            'breadcrumb' => ['Dashboard' => base_url(), 'Kelola Alumni' => '#'],
            'alumni' => $alumni
        ];

        return view('alumni/index', $data);
    }

    public function exportExcel()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        syncStatusSelesai();

        $alumni = $this->applicationModel->select('siswa.id as id_siswa, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.dosen_nama, siswa.foto, siswa.surat_permohonan, siswa.cv, siswa.surat_pernyataan, siswa.laporan, siswa.sertifikat_file, siswa.sertifikat_mode, siswa.alumni_foto, siswa.alumni_testimoni, siswa.alumni_is_tampil, tempat_magang.nama as divisi, siswa.tgl_masuk, siswa.tgl_selesai, angkatan.tgl_mulai as angkatan_tgl_mulai, angkatan.tgl_selesai as angkatan_tgl_selesai, lamaran.status as lamaran_status')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
            ->where('lamaran.status', 'selesai')
            ->orderBy('siswa.tgl_selesai', 'DESC')
            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Alumni');

        // Document Title Header
        $sheet->setCellValue('A1', 'DATA ALUMNI MAGANG / PRAKERIN');
        $sheet->setCellValue('A2', 'PLN INDONESIA POWER UBP ASAM ASAM');
        $sheet->setCellValue('A3', 'Tanggal Unduh: ' . date('d/m/Y H:i') . ' WITA');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0096D6'));
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('555555'));

        // Table Column Headers (Row 5)
        $headers = [
            'A5' => 'No',
            'B5' => 'NIM / NIS',
            'C5' => 'Nama Alumni',
            'D5' => 'Jurusan',
            'E5' => 'Instansi (Kampus/Sekolah)',
            'F5' => 'Dosen Pembimbing',
            'G5' => 'Divisi Penempatan',
            'H5' => 'Tanggal Mulai',
            'I5' => 'Tanggal Selesai',
            'J5' => 'Rencana Magang',
            'K5' => 'Berkas Pendaftaran',
            'L5' => 'Laporan PKL',
            'M5' => 'Sertifikat Magang',
            'N5' => 'Tampil di Landing Page',
            'O5' => 'Testimoni Alumni',
        ];

        foreach ($headers as $cell => $val) {
            $sheet->setCellValue($cell, $val);
        }

        // Header Style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0096D6'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D0D0D0'],
                ],
            ],
        ];

        $sheet->getStyle('A5:O5')->applyFromArray($headerStyle);
        $sheet->getRowDimension(5)->setRowHeight(28);

        // Data Rows
        $row = 6;
        $no = 1;
        foreach ($alumni as $a) {
            $tglMulai = !empty($a->tgl_masuk) ? $a->tgl_masuk : ($a->angkatan_tgl_mulai ?? null);
            $tglSelesai = !empty($a->tgl_selesai) ? $a->tgl_selesai : ($a->angkatan_tgl_selesai ?? null);
            
            $rencanaStr = '-';
            if ($tglMulai && $tglSelesai) {
                $rencanaStr = date('d/m/Y', strtotime($tglMulai)) . ' s/d ' . date('d/m/Y', strtotime($tglSelesai));
            } elseif ($tglSelesai) {
                $rencanaStr = 'Selesai: ' . date('d/m/Y', strtotime($tglSelesai));
            }

            // Berkas Kelengkapan
            $berkasList = [];
            if (!empty($a->surat_permohonan)) $berkasList[] = 'Surat';
            if (!empty($a->cv)) $berkasList[] = 'CV';
            if (!empty($a->surat_pernyataan)) $berkasList[] = 'Pernyataan';
            $berkasStr = !empty($berkasList) ? implode(', ', $berkasList) : '-';

            // Laporan Status
            if (isSmkSiswa($a)) {
                $laporanStr = !empty($a->laporan) ? 'Sudah Upload (SMK)' : 'Bebas Laporan (SMK)';
            } else {
                $laporanStr = !empty($a->laporan) ? 'Sudah Upload' : 'Belum Upload';
            }

            // Sertifikat Status
            $sertifikatStr = isCertificateReady($a) ? 'Siap / Tersedia' : 'Belum Tersedia';

            // Tampil di landing
            $tampilStr = $a->alumni_is_tampil ? 'Ya' : 'Tidak';

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValueExplicit('B' . $row, (string)($a->nim ?? '-'), DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $row, $a->nama ?? '-');
            $sheet->setCellValue('D' . $row, $a->jurusan ?? '-');
            $sheet->setCellValue('E' . $row, $a->instansi ?? '-');
            $sheet->setCellValue('F' . $row, $a->dosen_nama ?? '-');
            $sheet->setCellValue('G' . $row, $a->divisi ?? '-');
            $sheet->setCellValue('H' . $row, $tglMulai ? date('d/m/Y', strtotime($tglMulai)) : '-');
            $sheet->setCellValue('I' . $row, $tglSelesai ? date('d/m/Y', strtotime($tglSelesai)) : '-');
            $sheet->setCellValue('J' . $row, $rencanaStr);
            $sheet->setCellValue('K' . $row, $berkasStr);
            $sheet->setCellValue('L' . $row, $laporanStr);
            $sheet->setCellValue('M' . $row, $sertifikatStr);
            $sheet->setCellValue('N' . $row, $tampilStr);
            $sheet->setCellValue('O' . $row, !empty($a->alumni_testimoni) ? $a->alumni_testimoni : '-');

            // Cell Styles per row
            $sheet->getStyle('A' . $row . ':O' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle('A' . $row . ':O' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            
            // Alignments
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $row . ':J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K' . $row . ':N' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Row background zebra striping (subtle)
            if ($no % 2 == 1) {
                $sheet->getStyle('A' . $row . ':O' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $sheet->getRowDimension($row)->setRowHeight(22);
            $row++;
        }

        // Auto-fit column widths
        foreach (range('A', 'O') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Alumni_Magang_PLN_' . date('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function update($id_siswa)
    {
        if (!in_groups('admin')) {
            return $this->response->setJSON(['status' => 403, 'message' => 'Akses ditolak.']);
        }

        $rules = [
            'alumni_foto' => 'max_size[alumni_foto,2048]|is_image[alumni_foto]|mime_in[alumni_foto,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 400, 'message' => $this->validator->getError('alumni_foto')]);
        }

        $dataUpdate = [
            'alumni_testimoni' => $this->request->getPost('alumni_testimoni'),
            'alumni_is_tampil' => $this->request->getPost('alumni_is_tampil') !== null ? 1 : 0
        ];

        $file = $this->request->getFile('alumni_foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'assets/alumni/', $newName);
            $dataUpdate['alumni_foto'] = $newName;
        }

        $this->siswaModel->update($id_siswa, $dataUpdate);

        return $this->response->setJSON(['status' => 200, 'message' => 'Data alumni berhasil diperbarui!']);
    }

    public function reset($id_siswa)
    {
        if (!in_groups('admin')) {
            return $this->response->setJSON(['status' => 403, 'message' => 'Akses ditolak.']);
        }

        $dataUpdate = [
            'alumni_foto' => null,
            'alumni_testimoni' => null,
            'alumni_is_tampil' => 0
        ];

        $this->siswaModel->update($id_siswa, $dataUpdate);

        return $this->response->setJSON(['status' => 200, 'message' => 'Data galeri & testimoni alumni berhasil dihapus dan disembunyikan.']);
    }
}
