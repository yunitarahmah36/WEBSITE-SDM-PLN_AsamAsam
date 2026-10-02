<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCatatanDosenToLogbooks extends Migration
{
    public function up()
    {
        $fields = [
            'catatan_dosen' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'bukti'
            ],
        ];
        $this->forge->addColumn('logbooks', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('logbooks', 'catatan_dosen');
    }
}