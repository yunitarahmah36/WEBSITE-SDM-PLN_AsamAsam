<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfileFieldsToSiswa extends Migration
{
    public function up()
    {
        $fields = [
            'nik'             => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'tempat_lahir'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'tanggal_lahir'   => ['type' => 'DATE', 'null' => true],
            'jenis_kelamin'   => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'no_hp_emergency' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'ktp'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'bpjs'            => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'ktm'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ];
        $this->forge->addColumn('siswa', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', [
            'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'no_hp_emergency', 'ktp', 'bpjs', 'ktm'
        ]);
    }
}
