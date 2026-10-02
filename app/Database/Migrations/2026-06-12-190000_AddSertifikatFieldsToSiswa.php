<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSertifikatFieldsToSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'sertifikat_mode' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'otomatis',
                'null' => false
            ],
            'sertifikat_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'sertifikat_mode');
        $this->forge->dropColumn('siswa', 'sertifikat_file');
    }
}
