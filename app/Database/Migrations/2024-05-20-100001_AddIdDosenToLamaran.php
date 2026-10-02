<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdDosenToLamaran extends Migration
{
    public function up()
    {
        // Menambahkan kolom id_dosen_pembimbing
        $fields = [
            'id_dosen_pembimbing' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id_tempat'
            ],
        ];
        $this->forge->addColumn('lamaran', $fields);

        // Menambahkan Foreign Key secara manual (karena tabel sudah ada)
        $this->db->query('ALTER TABLE `lamaran` ADD CONSTRAINT `lamaran_id_dosen_pembimbing_foreign` FOREIGN KEY (`id_dosen_pembimbing`) REFERENCES `dosen_pembimbing`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->forge->dropForeignKey('lamaran', 'lamaran_id_dosen_pembimbing_foreign');
        $this->forge->dropColumn('lamaran', 'id_dosen_pembimbing');
    }
}