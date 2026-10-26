<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenilaianAtributTables extends Migration
{
    public function up()
    {
        // 1. Tabel penilaian_konfigurasi (Konfigurasi penilaian per instansi)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_instansi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'metode_penilaian' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'rata_rata', // 'rata_rata' atau 'bobot'
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'draft', // 'draft' atau 'terkirim'
            ],
            'tgl_kirim' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_instansi');
        $this->forge->createTable('penilaian_konfigurasi', true);

        // 2. Tabel penilaian_atribut (Daftar atribut / kriteria penilaian milik instansi)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_konfigurasi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_instansi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_atribut' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'bobot' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'urutan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_konfigurasi');
        $this->forge->addKey('id_instansi');
        $this->forge->addForeignKey('id_konfigurasi', 'penilaian_konfigurasi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penilaian_atribut', true);

        // 3. Tabel nilai_detail (Menyimpan skor per atribut untuk setiap penilaian peserta)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_nilai' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'id_atribut' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'nama_atribut' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'bobot' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'urutan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'nilai' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('id_nilai');
        $this->forge->addKey('id_atribut');
        $this->forge->addForeignKey('id_nilai', 'nilai', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('nilai_detail', true);

        // 4. Tambah kolom pendukung ke tabel nilai
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('nilai');

        $columnsToAdd = [];
        if (!in_array('id_konfigurasi', $fields)) {
            $columnsToAdd['id_konfigurasi'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'ids',
            ];
        }
        if (!in_array('metode_penilaian', $fields)) {
            $columnsToAdd['metode_penilaian'] = [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'rata_rata',
                'null'       => true,
                'after'      => 'ids',
            ];
        }
        if (!in_array('total_nilai', $fields)) {
            $columnsToAdd['total_nilai'] = [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'n_dosen',
            ];
        }

        if (!empty($columnsToAdd)) {
            $this->forge->addColumn('nilai', $columnsToAdd);
        }
    }

    public function down()
    {
        $this->forge->dropTable('nilai_detail', true);
        $this->forge->dropTable('penilaian_atribut', true);
        $this->forge->dropTable('penilaian_konfigurasi', true);

        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('nilai');
        $dropColumns = [];
        foreach (['id_konfigurasi', 'metode_penilaian', 'total_nilai'] as $col) {
            if (in_array($col, $fields)) {
                $dropColumns[] = $col;
            }
        }
        if (!empty($dropColumns)) {
            $this->forge->dropColumn('nilai', $dropColumns);
        }
    }
}
