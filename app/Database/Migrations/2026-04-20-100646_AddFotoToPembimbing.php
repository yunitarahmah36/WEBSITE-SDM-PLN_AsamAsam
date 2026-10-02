<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToPembimbing extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembimbing', [
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
        $this->forge->dropColumn('pembimbing', 'foto');
    }
}
