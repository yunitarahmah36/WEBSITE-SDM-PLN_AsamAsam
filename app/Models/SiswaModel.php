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

    protected $allowedFields = [
        'id',
        'user_id',
        'dosen_nama',   // <-- Ganti dari dosen_id (relasi lama) menjadi dosen_nama (string)
        'nim',
        'nama',
        'kelas',
        'jurusan',
        'instansi',
        'angkatan',
        'no_hp',
        'alamat',
        'foto',
        'laporan',
        'is_safety_ready',
        'is_laporan_approved',
        'surat_permohonan',
        'cv',
        'tgl_masuk',
        'tgl_selesai',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_hp_emergency',
        'ktp',
        'bpjs',
        'ktm',
        'surat_pernyataan',
        'sertifikat_mode',
        'sertifikat_file',
        'surat_balasan',
        'takaful',
        'alumni_foto',
        'alumni_testimoni',
        'alumni_is_tampil',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Ambil siswa beserta nama dosen pembimbing (sekarang langsung dari kolom siswa.dosen_nama).
     * Tidak lagi JOIN ke tabel dosen_pembimbing karena relasi tersebut sudah dihapus.
     */
    public function withDosen($siswaId = null)
    {
        $builder = $this->db->table('siswa')
            ->select('siswa.*');  // dosen_nama sudah termasuk dalam siswa.*

        if ($siswaId) {
            return $builder->where('siswa.id', $siswaId)->get()->getRow();
        }

        return $builder->get()->getResult();
    }

    /**
     * Ambil siswa berdasarkan nama dosen pembimbing (untuk dosen lihat mahasiswa bimbingannya).
     * Diperlukan karena relasi dosen_id sudah tidak ada — pencocokan dilakukan via nama dosen.
     */
    public function getByDosen($dosenName)
    {
        return $this->where('dosen_nama', $dosenName)->findAll();
    }
}