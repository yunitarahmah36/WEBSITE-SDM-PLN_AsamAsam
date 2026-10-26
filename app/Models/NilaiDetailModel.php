<?php

namespace App\Models;

use CodeIgniter\Model;

class NilaiDetailModel extends Model
{
    protected $table            = 'nilai_detail';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_nilai',
        'id_atribut',
        'nama_atribut',
        'bobot',
        'urutan',
        'nilai'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan detail nilai untuk suatu ID penilaian.
     */
    public function getByNilaiId($id_nilai)
    {
        return $this->where('id_nilai', $id_nilai)
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }
}
