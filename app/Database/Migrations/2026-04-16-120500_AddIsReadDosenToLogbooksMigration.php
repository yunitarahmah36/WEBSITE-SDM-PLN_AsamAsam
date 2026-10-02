<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsReadDosenToLogbooksMigration extends Migration
{
    public function up()
    {
        $this->forge->addColumn('logbooks', [
            'is_read_dosen' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'catatan_dosen'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('logbooks', 'is_read_dosen');
    }
}
