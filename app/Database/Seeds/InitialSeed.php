<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeed extends Seeder
{
    public function run()
    {
        /**
         * =============== FAKE DATA ===============
         */
        $fake = \Faker\Factory::create('id_ID');

        /**
         * =============== GROUP USER =============== 
         * Menghapus dosen_pembimbing, fokus ke instansi
         */
        $this->db->table('auth_groups')->insertBatch([
            ['name' => 'admin', 'description' => 'Site Administrator'],
            ['name' => 'pembimbing', 'description' => 'Pembimbing magang'],
            ['name' => 'siswa', 'description' => 'Siswa'],
            ['name' => 'instansi', 'description' => 'Instansi Pendidikan'],
        ]);

        /**
         * =============== JURUSAN ===============
         */
        $jurusan = new \App\Models\JurusanModel();
        $jurusan->insertBatch([
            ['id' => 1, 'nama_jurusan' => 'RPL'],
            ['id' => 2, 'nama_jurusan' => 'TKJ'],
            ['id' => 3, 'nama_jurusan' => 'TKR'],
        ]);

        /**
         * =============== ANGKATAN ===============
         */
        $angkatan = new \App\Models\AngkatanModel();
        $angkatan->insertBatch([
            ['tahun' => 2023, 'nama' => '2023 Gelombang 1', 'tgl_mulai' => '2023-03-03', 'tgl_selesai' => '2023-05-03'],
            ['tahun' => 2023, 'nama' => '2023 Gelombang 2', 'tgl_mulai' => '2023-05-05', 'tgl_selesai' => '2023-07-05'],
        ]);

        /**
         * =============== PEMBIMBING ===============
         */
        $pembimbing = new \App\Models\PembimbingModel();
        $user       = new \App\Models\UserModel();
        $idPembimbing = [];
        for ($i = 0; $i < $fake->numberBetween(10, 15); $i++) {
            $fakeEmail = $fake->freeEmail();
            if ($user->withGroup('pembimbing')->save(new \App\Entities\User([
                "email"     => $fakeEmail,
                "username"  => 'pembimbing' . ($i + 1),
                "password"  => '12345678',
                "active"    => 1,
            ]))) {
                $id = $user->getInsertID();
                $pembimbing->save([
                    'user_id'   => $id,
                    'nama'      => $fake->firstName() . ' ' . $fake->lastName(),
                    'no_hp'     => $fake->phoneNumber(),
                    'email'     => $fakeEmail,
                ]);
                $idPembimbing[] = $pembimbing->getInsertID();
            } else {
                echo implode(", ", $user->errors()) . "\n";
            }
        }

        /**
         * =============== ADMIN ===============
         */
        if ($user->withGroup('admin')->insert(new \App\Entities\User([
            "email"     => 'admin@gmail.com',
            "username"  => 'admin',
            "password"  => 'adminasama123',
            "active"    => 1,
        ]))) {
            $pembimbing->save([
                'user_id'   => $user->getInsertID(),
                'nama'      => 'Administrator',
                'no_hp'     => '08123456789',
                'email'     => 'admin@gmail.com',
            ]);
        }

        /**
         * =============== INSTANSI ===============
         * Menggantikan dosen_pembimbing
         */
        $instansiModel = new \App\Models\DosenPembimbingModel(); 
        
        for ($i = 0; $i < 3; $i++) {
            $fakeEmail = $fake->freeEmail();
            if ($user->withGroup('instansi')->save(new \App\Entities\User([
                "email"     => $fakeEmail,
                "username"  => 'instansi' . ($i + 1),
                "password"  => '12345678',
                "active"    => 1,
            ]))) {
                $instansiModel->save([
                    'user_id'        => $user->getInsertID(),
                    'nama_instansi'  => $fake->company(),
                    'jenis_instansi' => $fake->randomElement(['Perguruan tinggi', 'Sekolah', 'Lainnya']),
                    'alamat'         => $fake->address(),
                    'provinsi'       => $fake->state(),
                    'kabupaten_kota' => $fake->city(),
                    'kecamatan'      => $fake->district(),
                    'no_hp'          => $fake->phoneNumber(),
                    'email'          => $fakeEmail,
                ]);
            }
        }

        /**
         * =============== SISWA ===============
         */
        $idAngkatan = [];
        foreach ($angkatan->select('id')->findAll() as $key => $value) {
            $idAngkatan[] = $value->id;
        }

        $idSiswa = [];
        $siswa = new \App\Models\SiswaModel();
        for ($i = 0; $i < $fake->numberBetween(5, 10); $i++) {
            $fakeEmail = $fake->freeEmail();
            $nim = $fake->randomNumber(7, true);
            if ($user->withGroup('siswa')->save(new \App\Entities\User([
                "email"     => $fakeEmail,
                "username"  => $nim,
                "password"  => $nim,
                "active"    => 1,
            ]))) {
                $siswa->save([
                    "user_id"   => $user->getInsertID(),
                    "nim"       => $nim,
                    "nama"      => $fake->firstName() . ' ' . $fake->lastName(),
                    "kelas"     => $fake->randomElement(['XI TKJ', 'XI RPL', 'XI TKR']),
                    "angkatan"  => $fake->randomElement($idAngkatan),
                    "no_hp"     => $fake->e164PhoneNumber(),
                    "alamat"    => $fake->streetAddress()
                ]);
                $idSiswa[] = $siswa->getInsertID();
            } else {
                echo implode(", ", $user->errors()) . "\n";
            }
        }

        /**
         * =============== TEMPAT MAGANG ===============
         */
        $idTempat = [];
        $tempat = new \App\Models\TempatModel();
        for ($i = 0; $i < $fake->numberBetween(13, 25); $i++) {
            $tempat->save([
                'pid'           => $fake->randomElement($idPembimbing),
                'status'        => $fake->randomElement(['buka', 'tutup']),
                'kuota'         => $fake->randomDigitNot(0),
                'nama'          => $fake->company(),
                'hp'            => $fake->e164PhoneNumber(),
                'email'         => $fake->companyEmail(),
                'alamat'        => $fake->address(),
                'deskripsi'     => $fake->paragraph(2),
                'foto'          => 'https://propertywiselaunceston.com.au/wp-content/themes/property-wise/images/no-image@2x.png',
            ]);
            $idTempat[] = $tempat->getInsertID();
        }

        /**
         * =============== PENGUMUMAN ===============
         */
        $pengumuman = new \App\Models\PengumumanModel();
        for ($i = 0; $i < $fake->numberBetween(5, 10); $i++) {
            $pengumuman->save([
                'judul'        => $fake->sentence(5),
                'isi'          => $fake->paragraph(4),
                'oleh'         => $fake->randomElement($idPembimbing),
                'lampiran'     => 'https://propertywiselaunceston.com.au/wp-content/themes/property-wise/images/no-image@2x.png',
            ]);
        }
    }
}