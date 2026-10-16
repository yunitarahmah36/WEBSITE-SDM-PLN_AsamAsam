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
        'dosen_id',   // <-- TAMBAHAN BARU: relasi ke dosen_pembimbing
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
     * Ambil siswa beserta data dosen pembimbing (JOIN)
     */
    public function withDosen($siswaId = null)
    {
        $builder = $this->db->table('siswa')
            ->select('siswa.*, dosen_pembimbing.nama as dosen_nama, dosen_pembimbing.nip as dosen_nip, dosen_pembimbing.nidn as dosen_nidn, dosen_pembimbing.no_hp as dosen_hp, dosen_pembimbing.email as dosen_email')
            ->join('dosen_pembimbing', 'dosen_pembimbing.id = siswa.dosen_id', 'left');

        if ($siswaId) {
            return $builder->where('siswa.id', $siswaId)->get()->getRow();
        }

        return $builder->get()->getResult();
    }

    /**
     * Ambil siswa berdasarkan user_id (untuk dosen lihat mahasiswa bimbingannya)
     */
    public function getByDosen($dosenId)
    {
        return $this->where('dosen_id', $dosenId)->findAll();
    }
}