<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTakafulToSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'takaful' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'KTM',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'takaful');
    }
}