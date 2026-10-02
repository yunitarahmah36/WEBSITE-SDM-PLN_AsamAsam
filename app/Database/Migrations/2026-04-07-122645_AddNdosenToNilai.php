<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNdosenToNilai extends Migration
{
    public function up()
    {
        $fields = [
            'n_dosen' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'n_laporan'
            ],
        ];
        $this->forge->addColumn('nilai', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('nilai', 'n_dosen');
    }
}
