<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuratBalasanToSiswa extends Migration
{
    public function up()
    {
        $fields = [
            'surat_balasan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ];
        $this->forge->addColumn('siswa', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', ['surat_balasan']);
    }
}
