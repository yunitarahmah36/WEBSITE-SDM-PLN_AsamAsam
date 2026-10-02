<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAlumniFieldsToSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'alumni_foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'alumni_testimoni' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'alumni_is_tampil' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'alumni_foto');
        $this->forge->dropColumn('siswa', 'alumni_testimoni');
        $this->forge->dropColumn('siswa', 'alumni_is_tampil');
    }
}
