<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJurusanAndInstansiToSiswaMigration extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'jurusan' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'kelas'
            ],
            'instansi' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'jurusan'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', ['jurusan', 'instansi']);
    }
}
