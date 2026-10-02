<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSuratPernyataanToSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'surat_pernyataan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'surat_pernyataan');
    }
}
