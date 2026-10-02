<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddReportApprovalToSiswa extends Migration
{
    public function up()
    {
        $this->forge->addColumn('siswa', [
            'is_laporan_approved' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'laporan'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('siswa', 'is_laporan_approved');
    }
}
