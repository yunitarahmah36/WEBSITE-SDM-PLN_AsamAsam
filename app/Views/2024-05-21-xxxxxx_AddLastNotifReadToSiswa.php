<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLastNotifReadToSiswa extends Migration
{
    public function up()
    {
        $fields = [
            'last_notif_read_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'laporan' // Menempatkan kolom setelah 'laporan'
            ],
        ];
        $this->forge->addColumn('siswa', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'last_notif_read_at');
    }
}