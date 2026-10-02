<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameNisToNimInSiswa extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('siswa', [
            'nis' => [
                'name'       => 'nim',
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('siswa', [
            'nim' => [
                'name'       => 'nis',
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);
    }
}
