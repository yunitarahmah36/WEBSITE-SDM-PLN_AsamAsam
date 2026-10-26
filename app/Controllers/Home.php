<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Home extends BaseController
{
    protected $angkatan;
    protected $jurusan;
    protected $user;
    protected $siswa;
    protected $tempat;
    protected $application;
    protected $pembimbing;
    protected $logbooks;
    protected $pengumuman;
    protected $nilai;
    protected $instansi;

    public function __construct()
    {
        $this->angkatan    = new \App\Models\AngkatanModel();
        $this->jurusan     = new \App\Models\JurusanModel();
        $this->user        = new \App\Models\UserModel();
        $this->siswa       = new \App\Models\SiswaModel();
        $this->tempat      = new \App\Models\TempatModel();
        $this->application = new \App\Models\ApplicationModel();
        $this->pembimbing  = new \App\Models\PembimbingModel();
        $this->logbooks    = new \App\Models\LogBookModel();
        $this->pengumuman  = new \App\Models\PengumumanModel();
        $this->nilai       = new \App\Models\NilaiModel();
        $this->instansi    = new \App\Models\DosenPembimbingModel();
    }

    public function index()
    {
        if (!logged_in()) {
            $tempat = $this->tempat->select('tempat_magang.*, pembimbing.nama as pembimbing')
                ->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')
                ->orderBy('tempat_magang.status', "ASC")
                ->findAll();

            $alumni = $this->application->select('siswa.nama, siswa.instansi, siswa.foto, siswa.alumni_foto, siswa.alumni_testimoni, siswa.alumni_is_tampil, tempat_magang.nama as divisi, siswa.tgl_masuk, siswa.tgl_selesai, angkatan.tgl_mulai as angkatan_tgl_mulai, angkatan.tgl_selesai as angkatan_tgl_selesai')
                ->join('siswa', 'siswa.id = lamaran.id_siswa')
                ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
                ->where('lamaran.status', 'selesai')
                ->where('siswa.alumni_is_tampil', 1)
                ->where('siswa.alumni_foto IS NOT NULL')
                ->where('siswa.alumni_foto !=', '')
                ->orderBy('siswa.tgl_selesai', 'DESC')
                ->findAll();

            return view('landing', ['tempat' => $tempat, 'alumni' => $alumni]);
        }

        $dbPengumuman = $this->pengumuman->select("pengumuman.*, CASE WHEN auth_groups.name = 'admin' THEN 'Admin' ELSE COALESCE(pembimbing.nama, instansi.nama_instansi, users.username) END as pembimbing")
            ->join('users', 'users.id = pengumuman.oleh')
            ->join('pembimbing', 'pembimbing.user_id = users.id', 'left')
            ->join('instansi', 'instansi.user_id = users.id', 'left')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->orderBy('pengumuman.created_at', "DESC")
            ->groupBy('pengumuman.id');

        if (!in_groups('admin')) {
            if (in_groups('pembimbing')) {
                $dbPengumuman->groupStart()->where('pengumuman.target_role', 'semua')->orWhere('pengumuman.target_role', 'pembimbing')->orWhere('pengumuman.target_role', null)->orWhere('pengumuman.target_role', '')->groupEnd();
            } elseif (in_groups('instansi')) {
                $dbPengumuman->groupStart()->where('pengumuman.target_role', 'semua')->orWhere('pengumuman.target_role', 'instansi')->orWhere('pengumuman.target_role', null)->orWhere('pengumuman.target_role', '')->groupEnd();
            } else {
                $dbPengumuman->groupStart()->where('pengumuman.target_role', 'semua')->orWhere('pengumuman.target_role', 'siswa')->orWhere('pengumuman.target_role', null)->orWhere('pengumuman.target_role', '')->groupEnd();
            }
        }

        $pengumuman = $dbPengumuman->findAll();

        $is_safety_ready = false;
        $my_application = null;
        $catatan_dosen = 0;
        $is_profile_incomplete = false;
        $applications = []; // Inisialisasi awal

        if (in_groups('siswa')) {
            $sid = getSidByUid(user_id());
            if ($sid) {
                $catatan_dosen = $this->logbooks->where('id_siswa', $sid)->where('catatan_dosen !=', null)->where('catatan_dosen !=', '')->where('is_read_dosen', 0)->countAllResults();
            }

            $siswa_data = $this->siswa->where('user_id', user_id())->first();
            if ($siswa_data) {
                $is_safety_ready = (bool)$siswa_data->is_safety_ready;
                $is_profile_incomplete = !isProfileComplete(user_id());
                $my_application = $this->application->select('lamaran.*, tempat_magang.nama as instansi, tempat_magang.alamat')
                    ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                    ->where('lamaran.id_siswa', $siswa_data->id)
                    ->groupStart()->whereIn('lamaran.status', ['accepted', 'selesai'])->groupEnd()
                    ->first();
                if (!$my_application) {
                    $my_application = $this->application->select('lamaran.*, tempat_magang.nama as instansi, tempat_magang.alamat')
                        ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
                        ->where('lamaran.id_siswa', $siswa_data->id)
                        ->orderBy('lamaran.created_at', 'DESC')
                        ->first();
                }
            }
        }

        // === LOGIKA UNTUK INSTANSI: AMBIL DATA MAHASISWA BIMBINGAN ===
        if (in_groups('instansi')) {
            $instansi_data = $this->instansi->where('user_id', user_id())->first();
            if ($instansi_data) {
                $applications = $this->application
                    ->select('lamaran.*, siswa.nama, siswa.nim, siswa.id as sid, tempat_magang.nama as instansi')
                    ->join('siswa', 'siswa.id = lamaran.id_siswa')
                    ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
                    ->where('siswa.instansi', $instansi_data->nama_instansi)
                    ->orderBy('lamaran.created_at', 'DESC')
                    ->findAll();
            }
        }

        return view('dashboard', [
            "title"          => "Magang | Dashboard",
            "page_title"     => in_groups('admin') ? 'Dashboard Admin' : (in_groups('pembimbing') ? 'Dashboard Pembimbing' : (in_groups('instansi') ? 'Dashboard Instansi' : 'Dashboard Siswa')),
            "segment"        => $this->request->getUri()->getSegments(),
            "breadcrumb"     => in_groups('admin') ? ['Dashboard', 'Admin'] : (in_groups('pembimbing') ? ['Dashboard', 'Pembimbing'] : (in_groups('instansi') ? ['Dashboard', 'Instansi Pendidikan'] : ['Dashboard', user()->username])),
            "total_siswa"    => (function() {
                $db = \Config\Database::connect();
                $builder = $db->table('lamaran')->whereIn('status', ['accepted', 'selesai']);
                if (in_groups('pembimbing')) {
                    $pid = getPidByUid(user_id());
                    $tempat_ids_query = $db->table('tempat_magang')->select('id')->where('pid', $pid)->get()->getResultArray();
                    $tempat_ids = array_column($tempat_ids_query, 'id');
                    if (!empty($tempat_ids)) { $builder->whereIn('id_tempat', $tempat_ids); } else { return 0; }
                }
                $row = $builder->select('COUNT(DISTINCT id_siswa) as total')->get()->getRow();
                return $row ? (int)$row->total : 0;
            })(),
            "total_tempat"   => $this->tempat->countAllResults(),
            "tempat_buka"    => $this->tempat->where("status", 'buka')->countAllResults(),
            "tempat_tutup"   => $this->tempat->where("status", 'tutup')->countAllResults(),
            "pengumuman"     => $pengumuman,
            "catatan_dosen"  => $catatan_dosen,
            "is_safety_ready" => $is_safety_ready,
            "my_application" => $my_application,
            "is_profile_incomplete" => $is_profile_incomplete,
            "applications"   => $applications 
        ]);
    }

    public function profile()
    {
        $siswa_data = null;
        if (in_groups('siswa')) {
            $siswa_data = $this->siswa->where('user_id', user_id())->first();
        }

        return view('profile', [
            "title"         => "Magang | Profile",
            "page_title"    => in_groups('admin') ? 'Profile Admin' : (in_groups('pembimbing') ? 'Profile Pembimbing' : (in_groups('instansi') ? 'Profile Instansi Pendidikan' : 'Profile Siswa')),
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => in_groups('admin') ? ['Profile', 'Admin'] : (in_groups('pembimbing') ? ['Profile', 'Pembimbing'] : (in_groups('instansi') ? ['Profile', 'Instansi Pendidikan'] : ['Profile', user()->username])),
            "jurusan"       => $this->jurusan->findAll(),
            "angkatan"      => $this->angkatan->findAll(),
            "siswa"         => $siswa_data,
        ]);
    }

    public function tempat()
    {
        $syncKey = 'synced_' . (user_id() ?? 'guest');
        if (in_groups('siswa') && !session()->get($syncKey)) {
            session()->set($syncKey, true);
            return redirect()->to(current_url());
        }

        $tempat = $this->tempat->select('tempat_magang.*, pembimbing.nama as pembimbing')
            ->join('pembimbing', 'pembimbing.id = tempat_magang.pid')
            ->orderBy('tempat_magang.status', "ASC")
            ->findAll();

        $is_profile_complete = true;
        if (in_groups('siswa')) {
            $is_profile_complete = isProfileComplete(user_id());
        }

        return view('tempat', [
            "title"               => "Magang | Tempat Magang",
            "page_title"          => "Daftar Divisi Magang",
            "segment"             => $this->request->getUri()->getSegments(),
            "breadcrumb"          => ['Divisi Magang'],
            "tempat"              => $tempat,
            "is_profile_complete" => $is_profile_complete
        ]);
    }

    public function nilai()
    {
        $dataSiswa = $this->siswa->select('siswa.*, angkatan.nama as angkatan, angkatan.tahun, angkatan.tgl_selesai')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->where('siswa.id', getSidByUid(user_id()))
            ->first();

        $nilaiObj = $this->nilai->where('ids', getSidByUid(user_id()))->first();
        $nilaiDetails = $nilaiObj ? getDetailNilaiSiswa($nilaiObj->id) : [];

        return view('nilai', [
            "title"         => "Magang | Nilai Siswa",
            "page_title"    => "Nilai Magang Siswa",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Nilai'],
            "nilai"         => $nilaiObj,
            "nilaiDetails"  => $nilaiDetails,
            "siswa"         => $dataSiswa
        ]);
    }

    public function daftarHadir($id = null)
    {
        $tempat = $this->application->select('lamaran.id_siswa as sid, tempat_magang.nama as instansi, tempat_magang.id as tid, tempat_magang.pid, tempat_magang.alamat, tempat_magang.hp, tempat_magang.email')
            ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat')
            ->where('lamaran.id_siswa', getSidByUid(user_id()))
            ->whereIn('lamaran.status', ['accepted', 'selesai'])
            ->orderBy('lamaran.created_at', "DESC")
            ->first();

        $is_safety_ready = false;
        if (in_groups('siswa')) {
            $siswa_data = $this->siswa->where('user_id', user_id())->first();
            if ($siswa_data) {
                $is_safety_ready = (bool)$siswa_data->is_safety_ready;
            }
        }

        $data = [
            "title"         => "Magang | Daftar Hadir Siswa",
            "page_title"    => "Daftar Hadir Siswa Magang",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Daftar Hadir', user()->username],
            "tempat"        => $tempat,
            "logbooks"      => $this->logbooks->select('*')->select("IF(DATE(created_at) > tanggal, true, false) as telat", false)->where('id_siswa', getSidByUid(user_id()))->findAll(),
            "is_safety_ready" => $is_safety_ready
        ];

        if ($id && is_numeric($id)) {
            $edit_logbook = $this->logbooks->select('*')->select("IF(DATE(created_at) > tanggal, true, false) as telat", false)->where('id_siswa', getSidByUid(user_id()))->where('id', $id)->first();

            if ($edit_logbook->status !== 'rejected') {
                return redirect()->to('/kehadiran');
            }

            $data['edit_logbook'] = $edit_logbook;
        }

        return view('kehadiran', $data);
    }

    public function logbookku()
    {
        $sid = getSidByUid(user_id());
        $this->logbooks->where('id_siswa', $sid)->where('catatan_dosen !=', null)->set(['is_read_dosen' => 1])->update();

        $data = [
            "title"         => "Magang | Logbook Saya",
            "page_title"    => "Daftar Kegiatan Logbook",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Laporan Logbook', user()->username],
            "logbooks"      => $this->logbooks->select('*')->select("IF(DATE(created_at) > tanggal, true, false) as telat", false)->where('id_siswa', $sid)->orderBy('tanggal', 'DESC')->findAll()
        ];
        return view('logbookku', $data);
    }

    public function exportExcelLogbook()
    {
        $sid = getSidByUid(user_id());
        if (!$sid) { return redirect()->to('/'); }

        $siswa = $this->siswa->find($sid);
        $logbooks = $this->logbooks->where('id_siswa', $sid)->orderBy('tanggal', 'ASC')->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'LAPORAN KEGIATAN MAGANG / LOGBOOK');
        $sheet->setCellValue('A2', 'Nama: ' . $siswa->nama);
        $sheet->setCellValue('A3', 'NIM: ' . $siswa->nim);

        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'Tanggal');
        $sheet->setCellValue('C5', 'Keterangan');
        $sheet->setCellValue('D5', 'Jam Masuk');
        $sheet->setCellValue('E5', 'Jam Keluar');
        $sheet->setCellValue('F5', 'Kegiatan');
        $sheet->setCellValue('G5', 'Status');
        $sheet->setCellValue('H5', 'Bukti Kegiatan');

        $styleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ];
        $sheet->getStyle('A5:H5')->applyFromArray($styleArray);

        $row = 6;
        $no = 1;
        foreach ($logbooks as $log) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $log->tanggal);
            $sheet->setCellValue('C' . $row, strtoupper($log->keterangan));
            $sheet->setCellValue('D' . $row, $log->jam_masuk);
            $sheet->setCellValue('E' . $row, $log->jam_keluar);
            $sheet->setCellValue('F' . $row, $log->kegiatan);
            $sheet->setCellValue('G' . $row, strtoupper($log->status));

            $sheet->getStyle('A'.$row.':H'.$row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A'.$row.':E'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A'.$row.':H'.$row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F'.$row)->getAlignment()->setWrapText(true);

            if ($log->bukti && file_exists(FCPATH . 'assets/img/logbook/' . $log->bukti)) {
                $drawing = new Drawing();
                $drawing->setName('Bukti');
                $drawing->setDescription('Bukti Kegiatan');
                $drawing->setPath(FCPATH . 'assets/img/logbook/' . $log->bukti);
                $drawing->setCoordinates('H' . $row);
                $drawing->setHeight(90);
                $drawing->setOffsetX(5);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($sheet);
                $sheet->getRowDimension($row)->setRowHeight(80);
            } else {
                $sheet->getRowDimension($row)->setRowHeight(40);
            }

            $row++;
            $no++;
        }

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(50);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(25);

        $filename = 'Logbook_' . str_replace(' ', '_', $siswa->nama) . '_' . date('Ymd');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function application()
    {
        $data = [
            "title"         => "Magang | Application Siswa",
            "page_title"    => "Surat Magang",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Surat Magang', user()->username]
        ];

        if (in_groups('admin') || in_groups('pembimbing')) {
            $query = $this->application->select('lamaran.*, siswa.surat_pernyataan, siswa.nama, siswa.nim, siswa.kelas, siswa.jurusan, siswa.foto, siswa.no_hp, siswa.laporan, siswa.alamat, siswa.instansi, siswa.tgl_masuk, siswa.nik, siswa.tempat_lahir, siswa.tanggal_lahir, siswa.jenis_kelamin, siswa.no_hp_emergency, siswa.surat_permohonan, siswa.cv, siswa.ktp, siswa.bpjs, siswa.ktm, siswa.takaful, siswa.surat_balasan, siswa.dosen_nama, angkatan.tahun, angkatan.nama as angkatan, COALESCE(siswa.tgl_selesai, angkatan.tgl_selesai) as tgl_selesai')
                ->join('siswa', 'siswa.id = lamaran.id_siswa')
                ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
                ->where('lamaran.status', 'pending');

            if (in_groups('pembimbing')) {
                $pid = getPidByUid(user_id());
                $db = \Config\Database::connect();
                $tempat_ids_query = $db->table('tempat_magang')->select('id')->where('pid', $pid)->get()->getResultArray();
                $tempat_ids = array_column($tempat_ids_query, 'id');
                if (!empty($tempat_ids)) { $query->whereIn('lamaran.id_tempat', $tempat_ids); } else { $query->where('lamaran.id_tempat', -1); }
            }

            $data['applications'] = $query->orderBy('lamaran.created_at', "DESC")->findAll();
            $data['kelas'] = $this->siswa->select('kelas')->groupBy('kelas')->orderBy('kelas', 'ASC')->findAll();
            $data['angkatan'] = $this->angkatan->findAll();
            $data['dosen'] = $this->instansi->findAll();
            $data['tempats'] = $this->tempat->orderBy('nama', 'ASC')->findAll();

        } elseif (in_groups('siswa')) {
            $data['siswa_data'] = $this->siswa->find(getSidByUid(user_id()));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('application', $data);
    }

    public function status_lamaran()
    {
        $data = [
            "title"         => "Magang | Status Lamaran",
            "page_title"    => "Status Lamaran",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Status Lamaran', user()->username]
        ];

        if (in_groups('siswa')) {
            $sid = getSidByUid(user_id());
            $data['siswa'] = $this->siswa->find($sid);
            $data['applications'] = $this->application->select('lamaran.*, siswa.surat_pernyataan, siswa.surat_balasan, siswa.nama, siswa.nim, siswa.laporan, siswa.dosen_nama, angkatan.tahun, angkatan.nama as angkatan, COALESCE(siswa.tgl_selesai, angkatan.tgl_selesai) as tgl_selesai, tempat_magang.nama as instansi, tempat_magang.alamat, pembimbing.nama as nama_pembimbing, pembimbing.no_hp as hp_pembimbing, pembimbing.email as email_pembimbing')
                ->join('siswa', 'siswa.id = lamaran.id_siswa')
                ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
                ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
                ->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')
                ->where('lamaran.id_siswa', $sid)
                ->orderBy('lamaran.created_at', "DESC")
                ->findAll();
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('status_lamaran', $data);
    }

    public function logbooks($idt)
    {
        $idt = (int) $idt;
        $tempat = $this->tempat->find($idt);
        if (!$tempat) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tempat magang tidak ditemukan.'); }

        $siswas = $this->application->select('lamaran.*, siswa.id as sid, siswa.nama, siswa.nim, siswa.kelas, siswa.no_hp, siswa.alamat, angkatan.tahun')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->where(['lamaran.id_tempat' => $idt])
            ->whereIn('lamaran.status', ['accepted', 'selesai'])
            ->orderBy('lamaran.created_at', "DESC")
            ->findAll();

        return view('logbook', [
            "title"         => "Magang | logbook Siswa",
            "page_title"    => "logbook Siswa Magang",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['logbook', user()->username, $tempat->nama],
            "siswas"         => $siswas,
            "idt"           => $idt,
        ]);
    }

    public function logbook_siswa($idt, $nim)
    {
        syncStatusSelesai();
        $idt = (int) $idt;
        $tempat = $this->tempat->find($idt);
        if (!$tempat) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tempat magang tidak ditemukan.'); }

        $siswa = $this->siswa
            ->select('siswa.*, angkatan.nama as angkatan, angkatan.tahun, COALESCE(siswa.tgl_selesai, angkatan.tgl_selesai) as tgl_selesai, users.email')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->join('users', 'users.id = siswa.user_id')
            ->where('nim', $nim)
            ->first();

        if (!$siswa) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Siswa tidak ditemukan.'); }

        $siswas = $this->application->select('lamaran.*, siswa.id as sid, siswa.nama, siswa.nim, siswa.kelas, siswa.no_hp, siswa.alamat, siswa.laporan, siswa.foto, siswa.instansi, siswa.jurusan, angkatan.tahun, angkatan.nama as angkatan')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->where(['lamaran.id_tempat' => $idt])
            ->whereIn('lamaran.status', ['accepted', 'selesai'])
            ->orderBy('lamaran.created_at', "DESC")
            ->findAll();

        $log = $this->logbooks->select("*")
            ->select("IF(DATE(created_at) > tanggal, true, false) as telat", false)
            ->where(['id_siswa' => $siswa->id, 'id_pembimbing' => $tempat->pid])
            ->orderBy('tanggal', 'DESC')
            ->findAll();

        $nilaiObj = getNilaiSiswa($siswa->id);
        $konfigPenilaian = getKonfigurasiPenilaianSiswa($siswa);
        $nilaiDetails = $nilaiObj ? getDetailNilaiSiswa($nilaiObj->id) : [];

        return view('logbook_siswa', [
            "title"           => "Magang | Logbook Siswa",
            "page_title"      => "Logbook Siswa Magang",
            "segment"         => $this->request->getUri()->getSegments(),
            "breadcrumb"      => ['logbook', user()->username, $tempat->nama, $nim],
            "selected"        => $siswa,
            "siswas"          => $siswas,
            "siswa"           => $siswa,
            "idt"             => $idt,
            "logbooks"        => $log,
            "nilai"           => $nilaiObj,
            "konfigPenilaian" => $konfigPenilaian,
            "nilaiDetails"    => $nilaiDetails
        ]);
    }

    public function form_penilaian($idt, $nim)
    {
        $idt = (int) $idt;
        $tempat = $this->tempat->find($idt);
        if (!$tempat) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tempat magang tidak ditemukan.'); }

        $siswa = $this->siswa
            ->select('siswa.*, angkatan.nama as angkatan, angkatan.tahun, COALESCE(siswa.tgl_selesai, angkatan.tgl_selesai) as tgl_selesai, users.email')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->join('users', 'users.id = siswa.user_id')
            ->where('nim', $nim)
            ->first();

        if (!$siswa) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Siswa tidak ditemukan.'); }

        $nilaiObj = getNilaiSiswa($siswa->id);
        $konfigPenilaian = getKonfigurasiPenilaianSiswa($siswa);
        $instansiData = getInstansiDataBySiswa($siswa);
        $nilaiDetails = $nilaiObj ? getDetailNilaiSiswa($nilaiObj->id) : [];

        return view('penilaian_siswa', [
            "title"           => "Penilaian Siswa | " . $siswa->nama,
            "page_title"      => "Penilaian Siswa Magang",
            "segment"         => $this->request->getUri()->getSegments(),
            "breadcrumb"      => ['logbook', user()->username, $tempat->nama, $nim, 'Penilaian'],
            "siswa"           => $siswa,
            "tempat"          => $tempat,
            "idt"             => $idt,
            "instansiData"    => $instansiData,
            "nilai"           => $nilaiObj,
            "konfigPenilaian" => $konfigPenilaian,
            "nilaiDetails"    => $nilaiDetails
        ]);
    }

    public function man_tempat()
    {
        $tempat = $this->tempat->select('tempat_magang.*, pembimbing.nama as pembimbing')
            ->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')
            ->findAll();
        $pem = $this->pembimbing->findAll();
        $pem = array_map(function ($p) { return ['value' => $p->nama, 'label' => $p->nama, 'num' => $p->id]; }, $pem);

        return view('man_tempat', [
            "title"             => "Magang | Manajemen Tempat",
            "page_title"        => "Manajemen Divisi Magang",
            "segment"           => $this->request->getUri()->getSegments(),
            "breadcrumb"        => ['Manajemen', 'Tempat'],
            "tempat"            => $tempat,
            "pembimbing"        => $this->pembimbing->findAll(),
            "pembimbing_json"   => json_encode($pem)
        ]);
    }

    public function tempat_edit($id)
    {
        return view('edit_tempat', [
            'title'         => 'Magang | Edit Tempat Magang',
            'page_title'    => 'Edit Tempat Magang',
            'segment'       => $this->request->getUri()->getSegments(),
            'breadcrumb'    => ['Manajemen', 'Tempat', 'Edit'],
            'tempat'        => $this->tempat->find($id),
            'pembimbing'    => $this->pembimbing->findAll()
        ]);
    }

    public function man_pembimbing() { return $this->man_user('pembimbing'); }
    public function man_instansi() { return $this->man_user('instansi'); }

    public function man_user($type = 'pembimbing')
    {
        $db = \Config\Database::connect();

        $builder = $db->table('users')
            ->select('users.id, users.username, users.email')
            ->select('pembimbing.id as pid, pembimbing.nama as p_nama, pembimbing.no_hp as p_hp, pembimbing.foto as p_foto')
            ->select('instansi.id as iid, instansi.nama_instansi, instansi.jenis_instansi, instansi.alamat, instansi.provinsi, instansi.kabupaten_kota, instansi.kecamatan, instansi.no_hp as i_hp, instansi.email as i_email')
            ->select('(SELECT COUNT(*) FROM siswa WHERE siswa.instansi = instansi.nama_instansi) as jumlah_mahasiswa')
            ->join('pembimbing', 'pembimbing.user_id = users.id', 'left')
            ->join('instansi', 'instansi.user_id = users.id', 'left');

        if ($type === 'instansi') {
            $builder->where('instansi.id IS NOT NULL');
            $title = "Magang | Manajemen Instansi Pendidikan";
            $page_title = "Manajemen Data Instansi Pendidikan";
            $breadcrumb = ['Manajemen', 'Instansi Pendidikan'];
            $role_filter = 'instansi';
        } else {
            $builder->where('pembimbing.id IS NOT NULL');
            $title = "Magang | Manajemen Pembimbing";
            $page_title = "Manajemen Data Pembimbing Lapangan & Admin";
            $breadcrumb = ['Manajemen', 'Pembimbing Lapangan'];
            $role_filter = 'pembimbing';
        }

        $duser = $builder->orderBy('users.id', 'DESC')->get()->getResult();

        foreach ($duser as $u) {
            $u->nama  = $u->p_nama ?? $u->nama_instansi;
            $u->no_hp = $u->p_hp ?? $u->i_hp;
            $u->foto  = $u->p_foto;
            $u->jumlah_mahasiswa = isset($u->jumlah_mahasiswa) ? (int)$u->jumlah_mahasiswa : 0;
        }

        return view('man_user', [
            "title"         => $title,
            "page_title"    => $page_title,
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => $breadcrumb,
            "user"          => $duser,
            "role_type"     => $role_filter
        ]);
    }

    public function man_siswa()
    {
        syncStatusSelesai();
        $query = $this->user
            ->select('users.id, users.username, users.email, users.active, siswa.id as id_siswa_real, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.no_hp, siswa.alamat, siswa.foto, siswa.surat_permohonan, siswa.surat_pernyataan, siswa.cv, siswa.laporan, siswa.sertifikat_mode, siswa.sertifikat_file, siswa.is_safety_ready, siswa.is_laporan_approved, siswa.tgl_masuk, siswa.tgl_selesai, siswa.nik, siswa.tempat_lahir, siswa.tanggal_lahir, siswa.jenis_kelamin, siswa.no_hp_emergency, siswa.ktp, siswa.bpjs, siswa.ktm, siswa.takaful, siswa.surat_balasan, siswa.dosen_nama, siswa.created_at as tgl_daftar, angkatan.nama as angkatan, angkatan.tahun, COALESCE((SELECT status FROM lamaran WHERE lamaran.id_siswa = siswa.id AND status IN (\'accepted\', \'selesai\', \'unfinish\') LIMIT 1), (SELECT status FROM lamaran WHERE lamaran.id_siswa = siswa.id ORDER BY created_at DESC LIMIT 1)) as lamaran_status')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('siswa', 'siswa.user_id = users.id', 'left')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->where('auth_groups.name', 'siswa');

        if (in_groups('pembimbing')) {
            $pid = getPidByUid(user_id());
            $tempat_ids_query = $this->tempat->select('id')->where('pid', $pid)->get()->getResultArray();
            $tempat_ids = array_column($tempat_ids_query, 'id');

            if (!empty($tempat_ids)) {
                $db = \Config\Database::connect();
                $siswa_ids_query = $db->table('lamaran')->select('id_siswa')->whereIn('id_tempat', $tempat_ids)->whereIn('status', ['accepted', 'selesai', 'unfinish'])->get()->getResultArray();
                $siswa_ids = array_column($siswa_ids_query, 'id_siswa');
                if (!empty($siswa_ids)) { $query->whereIn('siswa.id', $siswa_ids); } else { $query->where('siswa.id', -1); }
            } else {
                $query->where('siswa.id', -1);
            }
        } else {
            $query->groupStart()->where("NOT EXISTS (SELECT 1 FROM lamaran WHERE lamaran.id_siswa = siswa.id AND lamaran.status = 'pending')")->groupEnd();
        }

        $dsiswa = $query->findAll();
        $angkatan_jeson = $this->angkatan->findAll();
        $angkatan_jeson = array_map(function ($item) { return ['value' => $item->nama . ' - ' . $item->tahun, 'label' => $item->nama . ' - ' . $item->tahun, 'no' => $item->id, 'tahun' => $item->tahun]; }, $angkatan_jeson);

        return view('man_siswa', [
            "title"         => "Magang | Manajemen Siswa",
            "page_title"    => "Manajemen Data Siswa",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Manajemen', 'Siswa'],
            "angkatan"      => $this->angkatan->findAll(),
            "jurusan"       => $this->jurusan->findAll(),
            "siswa"         => $dsiswa,
            "angkatan_json" => json_encode($angkatan_jeson)
        ]);
    }

    public function siswa_edit($nim)
    {
        $dsiswa = $this->user->select('users.id, users.username, users.email, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.no_hp, siswa.alamat, siswa.foto, siswa.tgl_masuk, siswa.tgl_selesai, siswa.dosen_nama, siswa.surat_balasan, angkatan.nama as angkatan, angkatan.tahun, angkatan.id as num')
            ->join('siswa', 'siswa.user_id = users.id', 'left')->join('angkatan', 'angkatan.id = siswa.angkatan', "left")->where('siswa.nim', $nim)->first();

        $angkatan_jeson = $this->angkatan->findAll();
        $angkatan_jeson = array_map(function ($item) { return ['value' => $item->nama . ' - ' . $item->tahun, 'label' => $item->nama . ' - ' . $item->tahun, 'no' => $item->id, 'tahun' => $item->tahun]; }, $angkatan_jeson);

        return view('siswa_edit', [
            "title"         => "Magang | Edit Data siswa",
            "page_title"    => "Edit Data Siswa $nim",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Siswa', 'Edit', $nim],
            "siswa"         => $dsiswa,
            "angkatan"      => $this->angkatan->findAll(),
            "jurusan"       => $this->jurusan->findAll(),
            "dosen"         => $this->instansi->findAll(),
            "angkatan_json" => json_encode($angkatan_jeson)
        ]);
    }

    public function laporan()
    {
        $jurusan = $this->siswa->select('jurusan')->where('jurusan !=', null)->where('jurusan !=', '')->groupBy('jurusan')->orderBy('jurusan', 'ASC')->findAll();

        return view('laporan_all', [
            "title"         => "Magang | Laporan",
            "page_title"    => "Laporan",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Laporan'],
            "jurusan"       => $jurusan,
            "angkatan"      => $this->angkatan->orderBy('tahun', 'DESC')->findAll(),
            "tahun"         => $this->angkatan->orderBy('tahun', 'DESC')->groupBy('tahun')->findAll(),
        ]);
    }

    public function laporan_akhir()
    {
        $uid = user_id();
        $sid = getSidByUid($uid);

        $db = \Config\Database::connect();
        $application = $db->table('lamaran')->select('lamaran.*, tempat_magang.nama as instansi, pembimbing.nama as nama_pembimbing, siswa.dosen_nama')
            ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')->join('pembimbing', 'pembimbing.id = tempat_magang.pid', 'left')->join('siswa', 'siswa.id = lamaran.id_siswa', 'left')
            ->where('lamaran.id_siswa', $sid)->whereIn('lamaran.status', ['pending', 'accepted', 'selesai'])->orderBy('lamaran.id', 'DESC')->get()->getRow();

        return view('laporan_akhir', [
            "title"         => "Magang | Laporan Akhir",
            "page_title"    => "Laporan Akhir Magang",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Kegiatanku', 'Laporan Akhir'],
            "application"   => $application,
            "siswa"         => $this->siswa->find($sid)
        ]);
    }

    public function laporan_all()
    {
        return view('laporan_all', [
            "title"         => "Magang | Site Settings",
            "page_title"    => "Pengaturan",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Settings'],
            "jurusan"       => $this->jurusan->orderBy('nama_jurusan', 'ASC')->findAll(),
            "angkatan"      => $this->angkatan->orderBy('tahun', 'DESC')->findAll(),
            "tahun"         => $this->angkatan->orderBy('tahun', 'DESC')->groupBy('tahun')->findAll(),
        ]);
    }

    public function settings()
    {
        return view('settings', [
            "title"         => "Magang | Site Settings",
            "page_title"    => "Pengaturan",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Settings'],
        ]);
    }

    public function bimbingan_instansi()
    {
        $instansi = $this->instansi->where('user_id', user_id())->first();
        if (!$instansi) { return redirect()->back()->with('error', 'Data Instansi Pendidikan tidak ditemukan.'); }

        $applications = $this->application->select('lamaran.*, siswa.nama, siswa.nim, siswa.kelas, siswa.no_hp, siswa.laporan, siswa.id as sid, siswa.is_laporan_approved, tempat_magang.nama as instansi')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
            ->where('siswa.instansi', $instansi->nama_instansi)->orderBy('lamaran.created_at', 'DESC')->findAll();

        return view('bimbingan_dosen', [
            "title"         => "Magang | Mahasiswa Bimbingan",
            "page_title"    => "Daftar Mahasiswa Bimbingan",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Bimbingan', 'Instansi Pendidikan'],
            "applications"  => $applications
        ]);
    }

    public function logbook_bimbingan_instansi($nim)
    {
        $siswa = $this->siswa->select('siswa.*, angkatan.nama as angkatan, angkatan.tahun, COALESCE(siswa.tgl_selesai, angkatan.tgl_selesai) as tgl_selesai')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')->where('nim', $nim)->first();

        if (!$siswa) { throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(); }

        $log = $this->logbooks->select("*")->select("IF(DATE(created_at) > tanggal, true, false) as telat", false)->where(['id_siswa' => $siswa->id])->findAll();

        return view('logbook_dosen_siswa', [ // <--- DISESUAIKAN DENGAN NAMA FILE KAMU
            "title"         => "Magang | Logbook Siswa",
            "page_title"    => "Logbook Siswa Magang",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Bimbingan', 'Instansi Pendidikan', $nim],
            "siswa"         => $siswa,
            "logbooks"      => $log
        ]);
    }
}