<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTargetRoleToPengumuman extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengumuman', [
            'target_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'default'    => 'semua',
                'after'      => 'lampiran',
                'comment'    => 'Target penerima: semua, siswa, pembimbing, dosen_pembimbing',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pengumuman', 'target_role');
    }
}
