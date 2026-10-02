<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToDosenPembimbing extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dosen_pembimbing', [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('dosen_pembimbing', 'foto');
    }
}
