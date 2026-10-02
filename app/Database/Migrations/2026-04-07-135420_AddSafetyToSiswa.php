<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSafetyToSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'is_safety_ready' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'laporan'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'is_safety_ready');
    }
}
