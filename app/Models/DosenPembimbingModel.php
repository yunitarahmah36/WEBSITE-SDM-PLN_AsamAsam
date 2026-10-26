<?php

namespace App\Models;

use CodeIgniter\Model;
use Myth\Auth\Entities\User as UserEntity;

class DosenPembimbingModel extends Model
{
    protected $table            = 'instansi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'nama_instansi',
        'jenis_instansi',
        'alamat',
        'provinsi',
        'kabupaten_kota',
        'kecamatan',
        'email',
        'no_hp',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Cari instansi berdasarkan email atau nama_instansi.
     */
    public function findOrCreate(array $data)
    {
        $email = trim($data['email'] ?? '');
        $nama_instansi = trim($data['nama_instansi'] ?? '');

        if (empty($email) && empty($nama_instansi)) {
            return null;
        }

        $existing = null;

        if (!empty($email)) {
            $existing = $this->where('email', $email)->first();
        }
        if (!$existing && !empty($nama_instansi)) {
            $existing = $this->where('nama_instansi', $nama_instansi)->first();
        }

        if ($existing) {
            $updateData = [];

            foreach (['nama_instansi', 'jenis_instansi', 'alamat', 'provinsi', 'kabupaten_kota', 'kecamatan', 'email', 'no_hp'] as $field) {
                if (!empty($data[$field]) && $data[$field] !== $existing->$field) {
                    $updateData[$field] = $data[$field];
                }
            }

            if (!empty($updateData)) {
                $this->update($existing->id, $updateData);
            }

            return $existing->id;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $userModel = new \App\Models\UserModel();

            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $baseUsername = strtolower(explode('@', $email)[0]);
                $baseUsername = preg_replace('/[^a-z0-9]/', '', $baseUsername);
                if (empty($baseUsername)) {
                    $baseUsername = 'instansi' . rand(100, 999);
                }
            } else {
                $namaSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama_instansi ?: 'instansi'));
                $baseUsername = substr($namaSlug, 0, 15) . rand(100, 999);
            }

            $username = $baseUsername;
            $counter  = 1;
            while ($userModel->where('username', $username)->first()) {
                $username = $baseUsername . $counter;
                $counter++;
                if ($counter > 100) {
                    throw new \Exception('Gagal generate username unik untuk: ' . $baseUsername);
                }
            }

            $emailFinal = (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL))
                          ? $email
                          : $username . '@instansi.local';

            if ($userModel->where('email', $emailFinal)->first()) {
                $emailFinal = $username . '.' . time() . '@instansi.local';
            }

            $defaultPassword = ucfirst($username) . 'Instansi123!';

            $userEntity = new UserEntity([
                'email'    => $emailFinal,
                'username' => $username,
                'password' => $defaultPassword,
                'active'   => 1,
            ]);

            $userModel->withGroup('instansi');

            if (!$userModel->save($userEntity)) {
                throw new \Exception('Gagal buat akun user: ' . json_encode($userModel->errors()));
            }

            $userId = $userModel->getInsertID();
            if (empty($userId)) {
                throw new \Exception('User ID kosong setelah insert');
            }

            $insertData = [
                'user_id'        => $userId,
                'nama_instansi'  => !empty($nama_instansi) ? $nama_instansi : 'Instansi ' . $userId,
                'jenis_instansi' => $data['jenis_instansi'] ?? 'Lainnya',
                'alamat'         => $data['alamat'] ?? null,
                'provinsi'       => $data['provinsi'] ?? null,
                'kabupaten_kota' => $data['kabupaten_kota'] ?? null,
                'kecamatan'      => $data['kecamatan'] ?? null,
                'email'          => $emailFinal,
                'no_hp'          => $data['no_hp'] ?? null,
            ];

            if (!$this->insert($insertData)) {
                throw new \Exception('Gagal insert instansi: ' . json_encode($this->errors()));
            }

            $instansiId = $this->getInsertID();

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            log_message('info', '[findOrCreate INSTANSI SUKSES] Instansi ID: ' . $instansiId . ', User ID: ' . $userId . ', Username: ' . $username);

            return $instansiId;

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[findOrCreate INSTANSI GAGAL] ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ambil semua instansi + jumlah mahasiswa bimbingan (via nama instansi).
     * Karena kolom siswa.dosen_id sudah dihapus, penghitungan dilakukan
     * dengan mencocokkan nama instansi.
     */
    public function getAllWithMahasiswaCount()
    {
        return $this->db->table('instansi')
            ->select('instansi.*, 
                      (SELECT COUNT(*) FROM siswa WHERE siswa.instansi = instansi.nama_instansi) AS jumlah_mahasiswa')
            ->orderBy('instansi.nama_instansi', 'ASC')
            ->get()
            ->getResult();
    }

    /**
     * Ambil detail instansi + daftar mahasiswa bimbingan (via nama instansi).
     */
    public function getWithMahasiswa($instansiId)
    {
        $instansi = $this->find($instansiId);
        if (!$instansi) return null;

        $siswaModel = new \App\Models\SiswaModel();
        $instansi->mahasiswa = $siswaModel
            ->where('instansi', $instansi->nama_instansi)
            ->findAll();

        return $instansi;
    }
}