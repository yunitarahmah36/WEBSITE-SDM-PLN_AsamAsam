<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFilesAndDatesToSiswa extends Migration
{
    public function up()
    {
        $fields = [
            'surat_permohonan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'cv'               => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tgl_masuk'        => ['type' => 'DATE', 'null' => true],
            'tgl_selesai'      => ['type' => 'DATE', 'null' => true]
        ];
        $this->forge->addColumn('siswa', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', ['surat_permohonan', 'cv', 'tgl_masuk', 'tgl_selesai']);
    }
}
