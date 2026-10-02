<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddApprovalToLogbook extends Migration
{
    public function up()
    {
        $this->forge->addColumn('logbook', [
            'is_approved' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'materi'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('logbook', 'is_approved');
    }
}
