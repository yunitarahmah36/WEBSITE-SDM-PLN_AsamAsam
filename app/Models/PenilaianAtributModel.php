<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianAtributModel extends Model
{
    protected $table            = 'penilaian_atribut';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_konfigurasi',
        'id_instansi',
        'nama_atribut',
        'bobot',
        'urutan'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan daftar atribut berdasarkan ID konfigurasi.
     */
    public function getByKonfigurasi($id_konfigurasi)
    {
        return $this->where('id_konfigurasi', $id_konfigurasi)
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Cek apakah atribut sudah pernah digunakan dalam penilaian peserta (nilai_detail).
     */
    public function isUsedInAssessment($id_atribut)
    {
        $db = \Config\Database::connect();
        return $db->table('nilai_detail')
                  ->where('id_atribut', $id_atribut)
                  ->countAllResults() > 0;
    }

    /**
     * Hitung total bobot dari suatu konfigurasi.
     */
    public function getTotalBobot($id_konfigurasi)
    {
        $row = $this->selectSum('bobot', 'total')
                    ->where('id_konfigurasi', $id_konfigurasi)
                    ->first();
        return (float) ($row->total ?? 0);
    }
}
