<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFilePenilaianToNilai extends Migration
{
    public function up()
    {
        $fields = [
            'file_penilaian' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'total_nilai'
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'file_penilaian'
            ],
        ];

        $this->forge->addColumn('nilai', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('nilai', ['file_penilaian', 'catatan']);
    }
}
