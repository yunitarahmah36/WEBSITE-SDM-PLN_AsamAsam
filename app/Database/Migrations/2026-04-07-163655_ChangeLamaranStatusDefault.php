<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeLamaranStatusDefault extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('lamaran', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'accepted', 'rejected', 'selesai', 'unfinish', 'reject by system'],
                'default'    => 'pending',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('lamaran', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'accepted', 'rejected', 'selesai', 'unfinish', 'reject by system'],
                'default'    => 'accepted',
            ],
        ]);
    }
}
