<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianKonfigurasiModel extends Model
{
    protected $table            = 'penilaian_konfigurasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'id_instansi',
        'file_format',
        'nama_file_asli',
        'metode_penilaian',
        'status',
        'tgl_kirim',
        'catatan'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Dapatkan atau inisialisasi konfigurasi untuk suatu instansi.
     */
    public function getOrInit($id_instansi)
    {
        $konfig = $this->where('id_instansi', $id_instansi)->first();
        if (!$konfig) {
            $id = $this->insert([
                'id_instansi'      => $id_instansi,
                'metode_penilaian' => 'rata_rata',
                'status'           => 'draft',
                'tgl_kirim'        => null,
                'catatan'          => null,
            ]);
            $konfig = $this->find($id);
        }
        return $konfig;
    }

    /**
     * Dapatkan konfigurasi yang sudah berstatus 'terkirim' untuk instansi tertentu.
     */
    public function getTerkirim($id_instansi)
    {
        return $this->where([
            'id_instansi' => $id_instansi,
            'status'      => 'terkirim'
        ])->first();
    }
}
