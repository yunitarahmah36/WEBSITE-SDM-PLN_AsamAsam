<?php
if (!function_exists('isProfileComplete')) {
    function isProfileComplete($uid_or_sid, $by_sid = false)
    {
        if (!$uid_or_sid) {
            return false;
        }
        $siswaModel = new \App\Models\SiswaModel();
        if ($by_sid) {
            $siswa = $siswaModel->find($uid_or_sid);
        } else {
            $siswa = $siswaModel->where('user_id', $uid_or_sid)->first();
        }

        if (!$siswa) {
            return false;
        }

        return !empty(trim((string)($siswa->nik ?? '')))
            && !empty(trim((string)($siswa->tempat_lahir ?? '')))
            && !empty($siswa->tanggal_lahir)
            && $siswa->tanggal_lahir !== '0000-00-00'
            && !empty(trim((string)($siswa->jenis_kelamin ?? '')))
            && !empty(trim((string)($siswa->no_hp_emergency ?? '')));
    }
}

if (!function_exists('initCheck')) {
    function initCheck($uid, $tid)
    {
        $sid = getSidByUid($uid);
        
        // Robust fallback: if sid not found by uid, try finding from session or active user object
        if (!$sid && function_exists('user')) {
             $siswaModel = new \App\Models\SiswaModel();
             $sdata = $siswaModel->where('user_id', $uid)->first();
             $sid = $sdata ? $sdata->id : null;
        }
        
        if (!$sid) {
            return false;
        }

        // Profile must be complete before registration
        if (!isProfileComplete($uid)) {
            return false;
        }

        // Prevent registering to the same division if already rejected
        $appModel = new \App\Models\ApplicationModel();
        $this_app = $appModel->where(['id_siswa' => $sid, 'id_tempat' => $tid])->first();
        if ($this_app && in_array($this_app->status, ['rejected', 'reject by system'])) {
            return false;
        }

        $existing_apps = getApplicationSiswa($sid);
        
        $accepted = 0;
        $pending = 0;
        $already_applied = false;

        foreach ($existing_apps as $ap) {
            if (in_array($ap->status, ['accepted', 'selesai'])) {
                $accepted++;
            }
            if ($ap->status == 'pending') {
                $pending++;
                if ($ap->id_tempat == $tid) {
                    $already_applied = true;
                }
            }
        }

        $slotAvailable = getSlotAvailable($tid);
        $siswaActive = isSiswaActive($sid);
        
        $now = date('Y-m-d');
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->select('siswa.*, angkatan.tgl_mulai as angkatan_tgl_mulai, angkatan.tgl_selesai as angkatan_tgl_selesai')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->find($sid);

        $target_selesai = ($siswa && !empty($siswa->tgl_selesai)) ? $siswa->tgl_selesai : ($siswa->angkatan_tgl_selesai ?? null);
        
        if ($target_selesai) {
            // If student has a defined end date, check if we're still within that period
            $applyAllowed = ($now <= $target_selesai);
        } else {
            // No end date defined — don't restrict new students from registering
            // (they may not have angkatan or dates set yet)
            $applyAllowed = true;
        }

        if ($accepted == 0 && $pending < 2 && !$already_applied && $slotAvailable > 0 && $applyAllowed) {
            $ready = true;
        } else {
            $ready = false;
        }

        return $ready;
    }
}

if (!function_exists('isEventFinished')) {
    function isEventFinished($uid)
    {
        $sid = getSidByUid($uid);
        if (!$sid) return false;
        return getStatusSiswa($sid) == 'selesai';
    }
}

if (!function_exists('isSiswaActive')) {
    function isSiswaActive($sid)
    {
        $siswaModel = new \App\Models\SiswaModel();
        $angkatanModel = new \App\Models\AngkatanModel();
        
        $siswa = $siswaModel->select('siswa.*, angkatan.tgl_mulai as angkatan_tgl_mulai, angkatan.tgl_selesai as angkatan_tgl_selesai')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->find($sid);

        if (!$siswa) {
            return false;
        }

        $now = date('Y-m-d');

        $tgl_mulai = !empty($siswa->tgl_masuk) ? $siswa->tgl_masuk : ($siswa->angkatan_tgl_mulai ?? null);
        $tgl_selesai = !empty($siswa->tgl_selesai) ? $siswa->tgl_selesai : ($siswa->angkatan_tgl_selesai ?? null);

        // If student has dates, check them
        if ($tgl_mulai && $tgl_selesai) {
            return ($now >= $tgl_mulai && $now <= $tgl_selesai);
        }

        // Fallback: If no angkatan assigned, check if there's any active cohort globally
        $activeCohort = $angkatanModel->where('tgl_mulai <=', $now)
            ->where('tgl_selesai >=', $now)
            ->first();

        return ($activeCohort != null);
    }
}

if (!function_exists("checkNowAndPeroid")) {
    function checkNowAndPeroid()
    {
        $now = date('Y-m-d');
        $application = new \App\Models\ApplicationModel();
        $application_data = $application->select('lamaran.*, siswa.angkatan, siswa.laporan, angkatan.tahun, angkatan.nama as angkatan, angkatan.tgl_mulai, COALESCE(siswa.tgl_selesai, angkatan.tgl_selesai) as tgl_selesai')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->findAll();

        foreach ($application_data as $ad) {
            if ($now > $ad->tgl_selesai && $ad->status == 'accepted' && $ad->laporan == null) {
                $application->update($ad->id, ['status' => 'unfinished']);
            }
        }
    }
}

if (!function_exists('getSiswaData')) {
    /**
     * Ambil data siswa + data akun user.
     * Data dosen pembimbing sekarang disimpan langsung di kolom siswa.dosen_nama (string),
     * jadi tidak perlu JOIN ke tabel dosen_pembimbing lagi.
     */
    function getSiswaData($id)
    {
        $db = \Config\Database::connect();

        return $db->table('siswa')
            ->select('siswa.*, users.username, users.email as uemail, users.created_at')
            ->join('users', 'users.id = siswa.user_id', 'left')
            ->where('siswa.user_id', $id)
            ->get()
            ->getRow();
    }
}

if (!function_exists('getPembimbingData')) {
    function getPembimbingData($id)
    {
        $db = \Config\Database::connect();
        $is_dosen = $db->table('auth_groups_users')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where(['user_id' => $id, 'name' => 'dosen_pembimbing'])
            ->countAllResults() > 0;

        if ($is_dosen) {
            // Karena tabel dosen_pembimbing sudah tidak dipakai (field NIP/NIDN dihapus),
            // kita fallback ke tabel users saja agar profil dosen tetap bisa dibuka.
            $db = \Config\Database::connect();
            return $db->table('users')
                ->select('users.*, users.email as uemail, users.username')
                ->where('users.id', $id)
                ->get()
                ->getRow();
        } else {
            $model = new \App\Models\PembimbingModel();
            $table = 'pembimbing';

            return $model->select($table . '.*, users.username, users.email as uemail, users.created_at')
                ->join('users', 'users.id = ' . $table . '.user_id')
                ->where($table . '.user_id', $id)
                ->first();
        }
    }
}


if (!function_exists('isLaporanUploaded')) {
    function isLaporanUploaded($sid)
    {
        $siswa = new \App\Models\SiswaModel();
        $data = $siswa->where('id', $sid)->first();
        if ($data->laporan == null) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('getNamaInstansi')) {
    function getNamaInstansi($id_tempat)
    {
        $ins = new \App\Models\TempatModel();
        $data = $ins->find($id_tempat);
        return $data->nama;
    }
}

if (!function_exists('isVerifiedInstansi')) {
    function isVerifiedInstansi($id_tempat)
    {
        if ($id_tempat == null) {
            return '<i class="fas text-warning me-2 fa-info-circle" title="Tempat pengajuan sendiri (belum terverifikasi sekolah)" style="cursor:help;"></i>';
        } else {
            return '<i class="far text-success me-2 fa-check-circle" title="Tempat sudah terverifikasi sekolah" style="cursor:help;"></i>';
        }
    }
}


if (!function_exists('genBadgeRoles')) {
    function genBadgeRoles($r)
    {
        switch (strtolower($r)) {
            case 'admin':
                return '<span class="badge badge-success border border-success">Admin</span>';
                break;
            case 'pembimbing':
                return '<span class="badge badge-info border border-info">Pembimbing</span>';
                break;
            case 'siswa':
                return '<span class="badge badge-warning border border-warning">Siswa</span>';
                break;
            default:
                return '<span class="badge badge-secondary border border-secondary">Unknown</span>';
                break;
        }
    }
}


if (!function_exists('genBadgeStatusApplication')) {
    function genBadgeStatusApplication($s)
    {
        switch ($s) {
            case 'pending':
                return '<span class="badge badge-warning border border-warning">Pending</span>';
                break;
            case 'accepted':
                return '<span class="badge badge-success border border-success">Accepted</span>';
                break;
            case 'approved':
                return '<span class="badge badge-success border border-success">Approved</span>';
                break;
            case 'rejected':
                return '<span class="badge badge-danger border border-danger">Rejected</span>';
                break;
            case 'selesai':
                return '<span class="badge badge-info border border-info">Selesai</span>';
                break;
            case 'unfinish':
                return '<span class="badge badge-warning border border-warning">unfinish</span>';
                break;
            case 'reject by system':
                return '<span class="badge badge-danger border border-danger">reject by system</span>';
                break;
            default:
                return '<span class="badge badge-secondary border border-secondary">Unknown</span>';
                break;
        }
    }
}


if (!function_exists('genBadgeKehadiran')) {
    function genBadgeKehadiran($k)
    {
        switch ($k) {
            case 'hadir':
                return '<span class="badge badge-success border border-success">Hadir</span>';
                break;
            case 'izin':
                return '<span class="badge badge-warning border border-warning">Izin</span>';
                break;
            case 'sakit':
                return '<span class="badge badge-info border border-info">Sakit</span>';
                break;
            case 'alfa':
                return '<span class="badge badge-danger border border-danger">Alfa</span>';
                break;
            default:
                return '<span class="badge badge-secondary">Unknown</span>';
                break;
        }
    }
}

if (!function_exists('getSidByUid')) {
    function getSidByUid($uid)
    {
        $siswa = new \App\Models\SiswaModel();
        $data = $siswa->where('user_id', $uid)->first();
        return $data ? $data->id : null;
    }
}

if (!function_exists('getPidByUid')) {
    function getPidByUid($uid)
    {
        $pembimbing = new \App\Models\PembimbingModel();
        $data = $pembimbing->where('user_id', $uid)->first();
        return $data ? $data->id : null;
    }
}

if (!function_exists('getInstansiByPid')) {
    function getInstansiByPid($uid)
    {
        $ins = new \App\Models\TempatModel();
        $idp = getPidByUid($uid);
        if (!$idp) return [];
        return $ins->where(['pid' => $idp])->findAll();
    }
}

if (!function_exists('isAccepted')) {
    function isAccepted($sid)
    {
        $app = new \App\Models\ApplicationModel();
        $data = $app->where(['id_siswa' => $sid, 'status' => 'accepted'])
            ->orderBy('created_at', 'DESC')
            ->findAll();
        return $data;
    }
}

if (!function_exists('getApplicationSiswa')) {
    function getApplicationSiswa($sid)
    {
        $app = new \App\Models\ApplicationModel();
        $data = $app->where('id_siswa', $sid)
            ->whereIn('status', ['pending', 'accepted', 'selesai'])
            ->orderBy('created_at', 'DESC')
            ->findAll();
        return $data;
    }
}

if (!function_exists('getNimByUid')) {
    function getNimByUid()
    {
        $siswa = new \App\Models\SiswaModel();
        return $siswa->where('user_id', user_id())->first()->nim;
    }
}

if (!function_exists('getSlotAvailable')) {
    function getSlotAvailable($tid)
    {
        $app = new \App\Models\ApplicationModel();
        $tempatModel = new \App\Models\TempatModel();

        $tempat = $tempatModel->find($tid);
        if (!$tempat) return 0;

        $count = $app->where('id_tempat', $tid)
                     ->where('status', 'accepted')
                     ->countAllResults();

        return max(0, $tempat->kuota - $count);
    }
}

if (!function_exists('syncStatusSelesai')) {
    /**
     * Otomatis sinkronisasi status lamaran siswa dari 'accepted' menjadi 'selesai'
     * jika tanggal selesai magang sudah terlewati (date('Y-m-d') > tgl_selesai).
     * Berlaku untuk semua siswa (baik SMK maupun Mahasiswa / Umum).
     */
    function syncStatusSelesai()
    {
        try {
            $db = \Config\Database::connect();
            $today = date('Y-m-d');

            $db->query("
                UPDATE lamaran 
                JOIN siswa ON siswa.id = lamaran.id_siswa
                LEFT JOIN angkatan ON angkatan.id = siswa.angkatan
                SET lamaran.status = 'selesai'
                WHERE lamaran.status = 'accepted'
                  AND (
                      (siswa.tgl_selesai IS NOT NULL AND siswa.tgl_selesai != '' AND ? > siswa.tgl_selesai)
                      OR 
                      ((siswa.tgl_selesai IS NULL OR siswa.tgl_selesai = '') AND angkatan.tgl_selesai IS NOT NULL AND ? > angkatan.tgl_selesai)
                  )
            ", [$today, $today]);
        } catch (\Throwable $th) {
            // Abaikan jika database belum siap
        }
    }
}

if (!function_exists('getStatusSiswa')) {
    function getStatusSiswa($sid)
    {
        if (!$sid) return 'not_applied';
        $app = new \App\Models\ApplicationModel();
        $data = $app->where('id_siswa', $sid)
            ->whereIn('status', ['accepted', 'selesai'])
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$data) {
            $data = $app->where('id_siswa', $sid)->orderBy('created_at', 'DESC')->first();
        }

        if ($data && $data->status == 'accepted') {
            $siswaModel = new \App\Models\SiswaModel();
            $siswa = $siswaModel->find($sid);
            if ($siswa) {
                $tgl_selesai = $siswa->tgl_selesai;
                if (empty($tgl_selesai) && !empty($siswa->angkatan)) {
                    $db = \Config\Database::connect();
                    $angkatan = $db->table('angkatan')->where('id', $siswa->angkatan)->get()->getRow();
                    if ($angkatan) {
                        $tgl_selesai = $angkatan->tgl_selesai;
                    }
                }
                if (!empty($tgl_selesai) && date('Y-m-d') > $tgl_selesai) {
                    // Update ke database agar konsisten
                    try {
                        $app->update($data->id, ['status' => 'selesai']);
                    } catch (\Throwable $th) {}
                    return 'selesai';
                }
            }
        }
        return $data ? $data->status : 'not_applied';
    }
}

if (!function_exists('getLamaranSiswa')) {
    function getLamaranSiswa($uid, $status = null)
    {
        $sid = getSidByUid($uid);
        if (!$sid) return [];

        $app = new \App\Models\ApplicationModel();
        $query = $app->where(['id_siswa' => $sid]);
        if ($status) {
            $query->where('status', $status);
        }
        return $query->findAll();
    }
}

if (!function_exists('nilaiAvailable')) {
    function nilaiAvailable($sid)
    {
        $nilai = new \App\Models\NilaiModel();
        $data = $nilai->where(['ids' => $sid])->first();

        if ($data == null) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('getNilaiSiswa')) {
    function getNilaiSiswa($sid)
    {
        $nilai = new \App\Models\NilaiModel();
        $data = $nilai->where(['ids' => $sid])->first();
        return $data;
    }
}
if (!function_exists('isSafetyCompleted')) {
    function isSafetyCompleted($uid)
    {
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->where('user_id', $uid)->first();
        if ($siswa) {
            return (bool)$siswa->is_safety_ready;
        }
        return false;
    }
}

if (!function_exists('isSmkSiswa')) {
    function isSmkSiswa($siswa)
    {
        if (!$siswa) return false;
        if (is_object($siswa)) {
            $instansi = $siswa->instansi ?? '';
            $kelas = $siswa->kelas ?? '';
        } else {
            $instansi = $siswa['instansi'] ?? '';
            $kelas = $siswa['kelas'] ?? '';
        }
        $instansiLower = strtolower($instansi);
        $kelasLower = strtolower($kelas);
        return (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));
    }
}

if (!function_exists('getIdentityLabel')) {
    function getIdentityLabel($siswa)
    {
        return isSmkSiswa($siswa) ? 'NIS' : 'NIM';
    }
}

if (!function_exists('getIdentityLabelByUid')) {
    function getIdentityLabelByUid($uid = null)
    {
        if (!$uid) $uid = user_id();
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->where('user_id', $uid)->first();
        return isSmkSiswa($siswa) ? 'NIS' : 'NIM';
    }
}

if (!function_exists('isCertificateReady')) {
    function isCertificateReady($s)
    {
        if (!$s) return false;
        
        // Must have accepted or selesai status
        if (!isset($s->lamaran_status) || !in_array($s->lamaran_status, ['accepted', 'selesai'])) {
            return false;
        }

        // Sertifikat hanya siap jika file berkas resmi sudah diunggah oleh admin/pembimbing
        if (!empty($s->sertifikat_file)) {
            $filePath = FCPATH . 'assets/sertifikat/' . $s->sertifikat_file;
            return file_exists($filePath);
        }
        return false;
    }
}

if (!function_exists('countNeedsNilai')) {
    function countNeedsNilai($uid = null) {
        if (!$uid) $uid = user_id();
        $db = \Config\Database::connect();
        $now = date('Y-m-d');
        
        $builder = $db->table('lamaran')
            ->select('lamaran.id_siswa, siswa.tgl_selesai, angkatan.tgl_selesai as angkatan_tgl_selesai')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->whereIn('lamaran.status', ['accepted', 'selesai']);
            
        if (in_groups('pembimbing')) {
            $pid = getPidByUid($uid);
            if ($pid) {
                $builder->where('lamaran.id_tempat IN (SELECT id FROM tempat_magang WHERE pid = '.$pid.')');
            } else {
                $builder->where('1=0'); // Force empty result if no pid
            }
        }
        
        $results = $builder->get()->getResult();
        $count = 0;
        foreach($results as $r) {
            $tgl_selesai = !empty($r->tgl_selesai) ? $r->tgl_selesai : $r->angkatan_tgl_selesai;
            if ($tgl_selesai && $tgl_selesai <= $now) {
                if (!nilaiAvailable($r->id_siswa)) {
                    $count++;
                }
            }
        }
        return $count;
    }
}

if (!function_exists('countNeedsSertifikat')) {
    function countNeedsSertifikat($uid = null) {
        if (!$uid) $uid = user_id();
        $db = \Config\Database::connect();
        $now = date('Y-m-d');
        
        $builder = $db->table('lamaran')
            ->select('siswa.*, lamaran.status as lamaran_status, angkatan.tgl_selesai as angkatan_tgl_selesai')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->whereIn('lamaran.status', ['accepted', 'selesai']);
            
        if (in_groups('pembimbing')) {
            $pid = getPidByUid($uid);
            if ($pid) {
                $builder->where('lamaran.id_tempat IN (SELECT id FROM tempat_magang WHERE pid = '.$pid.')');
            } else {
                $builder->where('1=0');
            }
        }
        
        $results = $builder->get()->getResult();
        $count = 0;
        $nilaiModel = new \App\Models\NilaiModel();
        foreach($results as $s) {
            // Syarat sertifikat: syarat selesai & nilai sudah ada, tetapi file sertifikat belum diunggah
            if (empty($s->sertifikat_file)) {
                $nilai = $nilaiModel->where('ids', $s->id)->first();
                if (!empty($nilai) && !empty($s->is_safety_ready)) {
                    $instansiLower = strtolower($s->instansi ?? '');
                    $kelasLower = strtolower($s->kelas ?? '');
                    $isSmk = (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));
                    if (!empty($s->is_laporan_approved) || ($isSmk && empty($s->laporan))) {
                        $count++;
                    }
                }
            }
        }
        return $count;
    }
}

if (!function_exists('getInstansiDataBySiswa')) {
    function getInstansiDataBySiswa($siswa)
    {
        if (!$siswa) return null;
        $namaInstansi = is_object($siswa) ? ($siswa->instansi ?? '') : '';
        if (empty($namaInstansi)) return null;

        $db = \Config\Database::connect();
        $trimmed = trim($namaInstansi);

        // 1. Exact match
        $instansi = $db->table('instansi')->where('nama_instansi', $trimmed)->get()->getRow();
        if ($instansi) return $instansi;

        // 2. Case-insensitive match
        $instansi = $db->table('instansi')->where('LOWER(nama_instansi)', strtolower($trimmed))->get()->getRow();
        if ($instansi) return $instansi;

        // 3. Like match
        $instansi = $db->table('instansi')->like('nama_instansi', $trimmed)->get()->getRow();
        return $instansi;
    }
}

if (!function_exists('getKonfigurasiPenilaianSiswa')) {
    function getKonfigurasiPenilaianSiswa($siswa)
    {
        $instansi = getInstansiDataBySiswa($siswa);
        if (!$instansi) return null;

        $db = \Config\Database::connect();
        $konfig = $db->table('penilaian_konfigurasi')
            ->where('id_instansi', $instansi->id)
            ->where('status', 'terkirim')
            ->orderBy('id', 'DESC')
            ->get()->getRow();

        if (!$konfig) return null;

        $atribut = $db->table('penilaian_atribut')
            ->where('id_konfigurasi', $konfig->id)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()->getResult();

        if (empty($atribut) && empty($konfig->file_format)) return null;

        $konfig->atribut = $atribut ?: [];
        $konfig->instansi = $instansi;
        return $konfig;
    }
}

if (!function_exists('getDetailNilaiSiswa')) {
    function getDetailNilaiSiswa($id_nilai)
    {
        if (!$id_nilai) return [];
        $db = \Config\Database::connect();
        if (!$db->tableExists('nilai_detail')) {
            return [];
        }
        $query = $db->table('nilai_detail')
            ->where('id_nilai', $id_nilai)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();
        return ($query && method_exists($query, 'getResult')) ? $query->getResult() : [];
    }
}

if (!function_exists('countPendingApplications')) {
    function countPendingApplications($uid = null)
    {
        if (!$uid && function_exists('user_id')) {
            $uid = user_id();
        }
        $db = \Config\Database::connect();
        $builder = $db->table('lamaran')->where('lamaran.status', 'pending');

        if (function_exists('in_groups') && in_groups('pembimbing')) {
            $pid = getPidByUid($uid);
            if ($pid) {
                $builder->where('lamaran.id_tempat IN (SELECT id FROM tempat_magang WHERE pid = ' . (int)$pid . ')');
            } else {
                return 0;
            }
        }

        return $builder->countAllResults();
    }
}

if (!function_exists('getPendingApplicationsList')) {
    function getPendingApplicationsList($limit = 5, $uid = null)
    {
        if (!$uid && function_exists('user_id')) {
            $uid = user_id();
        }
        $db = \Config\Database::connect();
        $builder = $db->table('lamaran')
            ->select('lamaran.*, siswa.nama as nama_siswa, siswa.nim, tempat_magang.nama as nama_tempat')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
            ->where('lamaran.status', 'pending')
            ->orderBy('lamaran.created_at', 'DESC');

        if (function_exists('in_groups') && in_groups('pembimbing')) {
            $pid = getPidByUid($uid);
            if ($pid) {
                $builder->where('lamaran.id_tempat IN (SELECT id FROM tempat_magang WHERE pid = ' . (int)$pid . ')');
            } else {
                return [];
            }
        }

        return $builder->limit($limit)->get()->getResult();
    }
}

