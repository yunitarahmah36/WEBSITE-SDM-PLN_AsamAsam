<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'alamat'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'foto');
    }
}
