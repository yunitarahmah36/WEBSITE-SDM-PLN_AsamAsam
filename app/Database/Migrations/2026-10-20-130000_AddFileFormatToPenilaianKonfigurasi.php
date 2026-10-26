<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFileFormatToPenilaianKonfigurasi extends Migration
{
    public function up()
    {
        $fields = [
            'file_format' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'id_instansi'
            ],
            'nama_file_asli' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'file_format'
            ],
        ];

        $this->forge->addColumn('penilaian_konfigurasi', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('penilaian_konfigurasi', ['file_format', 'nama_file_asli']);
    }
}
