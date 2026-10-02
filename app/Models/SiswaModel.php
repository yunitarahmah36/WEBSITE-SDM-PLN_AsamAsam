<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'user_id', 'nim', 'nama', 'kelas', 'jurusan', 'instansi', 'angkatan', 'no_hp', 'alamat', 'foto', 'laporan', 'is_safety_ready', 'is_laporan_approved', 'surat_permohonan', 'cv', 'tgl_masuk', 'tgl_selesai',
        'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'no_hp_emergency', 'ktp', 'bpjs', 'ktm', 'surat_pernyataan', 'sertifikat_mode', 'sertifikat_file', 'surat_balasan',
        'alumni_foto', 'alumni_testimoni', 'alumni_is_tampil'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
