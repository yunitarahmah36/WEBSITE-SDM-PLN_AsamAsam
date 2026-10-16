<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User as UserEntity;

class User extends BaseController
{
    protected $user;
    protected $pembimbing;

    public function __construct() {
        $this->user         = new \App\Models\UserModel();
        $this->pembimbing   = new \App\Models\PembimbingModel();
    }

    public function store()
    {
        $password = $this->request->getPost("password");
        if (empty($password)) {
            $password = $this->request->getPost("username");
        }

        $user = new UserEntity([
            "email" => $this->request->getPost("email"),
            "username" => $this->request->getPost("username"),
            "password" => $password,
            "active" => 1,
        ]);

        // Data dasar pembimbing
        $user_detail = [
            "nama"  => $this->request->getPost("nama"),
            "no_hp" => $this->request->getPost("hp"),
            "email" => $this->request->getPost("email"),
        ];

        // Upload foto
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'assets/img/foto_pembimbing/', $namaFoto);
            $user_detail['foto'] = $namaFoto;
        }

        $role = $this->request->getPost("role") ?? 'pembimbing';

        // Start Transaction
        $this->user->db->transStart();

        try {
            // Validasi
            if (!$this->validate([
                'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'hp'       => 'required|numeric|min_length[10]',
                'password' => 'permit_empty|min_length[8]',
            ])) {
                throw new \Exception(implode(", ", $this->validator->getErrors()));
            }

            // Validasi khusus untuk role instansi
            if ($role === 'instansi') {
                if (!$this->validate([
                    'nama_instansi'  => 'required|min_length[3]|max_length[150]',
                    'jenis_instansi' => 'required|in_list[Perguruan tinggi,Sekolah,Lainnya]',
                    'alamat'         => 'required',
                    'provinsi'       => 'required|max_length[100]',
                    'kabupaten_kota' => 'required|max_length[100]',
                    'kecamatan'      => 'required|max_length[100]',
                ])) {
                    throw new \Exception(implode(", ", $this->validator->getErrors()));
                }
            } else {
                // Validasi khusus role pembimbing
                if (!$this->validate([
                    'nama' => 'required|min_length[3]',
                ])) {
                    throw new \Exception(implode(", ", $this->validator->getErrors()));
                }
            }

            if ($this->user->withGroup($role)->save($user)) {
                $user_detail['user_id'] = $this->user->getInsertID();

                if ($role === 'instansi') {
                    // Field khusus instansi
                    $user_detail['nama_instansi']  = trim((string) $this->request->getPost('nama_instansi'));
                    $user_detail['jenis_instansi'] = $this->request->getPost('jenis_instansi');
                    $user_detail['alamat']         = trim((string) $this->request->getPost('alamat'));
                    $user_detail['provinsi']       = trim((string) $this->request->getPost('provinsi'));
                    $user_detail['kabupaten_kota'] = trim((string) $this->request->getPost('kabupaten_kota'));
                    $user_detail['kecamatan']      = trim((string) $this->request->getPost('kecamatan'));
                    $user_detail['no_hp']          = $this->request->getPost('hp');

                    // Hapus field yang tidak dipakai di tabel instansi
                    unset($user_detail['nama'], $user_detail['foto']);

                    $model = new \App\Models\DosenPembimbingModel();
                } else {
                    $model = $this->pembimbing;
                }

                if (!$model->save($user_detail)) {
                    throw new \Exception("Gagal menyimpan detail profil: " . implode(", ", $model->errors()));
                }
            } else {
                throw new \Exception("Gagal menyimpan user: " . implode(", ", $this->user->errors()));
            }

            $this->user->db->transComplete();

            if ($this->user->db->transStatus() === FALSE) {
                throw new \Exception("Transaksi database gagal.");
            }

            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'User berhasil ditambahkan',
            ]);

        } catch (\Exception $e) {
            $this->user->db->transRollback();
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function update()
    {
        $old_email = $this->request->getPost("old_email");
        $user = [];

        $uid = $this->request->getPost("item");

        $role = $this->request->getPost("role") ?? 'pembimbing';

        // Data dasar
        $user_detail = [
            "no_hp" => $this->request->getPost("hp"),
        ];

        if ($this->request->getPost("email") != $old_email) {
            $user['email'] = $this->request->getPost("email");
            $user_detail['email'] = $this->request->getPost("email");
        }

        if (!empty($this->request->getPost("password"))) {
            $user['password'] = $this->request->getPost("password");
        }

        if ($role === 'instansi') {
            $model = new \App\Models\DosenPembimbingModel();
            $pid = $model->select('id')->where('user_id', $uid)->first();

            $user_detail['nama_instansi']  = trim((string) $this->request->getPost('nama_instansi'));
            $user_detail['jenis_instansi'] = $this->request->getPost('jenis_instansi');
            $user_detail['alamat']         = trim((string) $this->request->getPost('alamat'));
            $user_detail['provinsi']       = trim((string) $this->request->getPost('provinsi'));
            $user_detail['kabupaten_kota'] = trim((string) $this->request->getPost('kabupaten_kota'));
            $user_detail['kecamatan']      = trim((string) $this->request->getPost('kecamatan'));
        } else {
            $model = $this->pembimbing;
            $pid = $model->select('id, foto')->where('user_id', $uid)->first();
            $user_detail['nama'] = $this->request->getPost("nama");
        }

        // Upload foto (hanya untuk pembimbing)
        if ($role !== 'instansi') {
            $fileFoto = $this->request->getFile('foto');
            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                $namaFoto = $fileFoto->getRandomName();
                $fileFoto->move(FCPATH . 'assets/img/foto_pembimbing/', $namaFoto);
                $user_detail['foto'] = $namaFoto;

                if ($pid && $pid->foto && file_exists(FCPATH . 'assets/img/foto_pembimbing/' . $pid->foto)) {
                    @unlink(FCPATH . 'assets/img/foto_pembimbing/' . $pid->foto);
                }
            }
        }

        if (!empty($user)) {
            $user['id'] = $uid;
            $user = new UserEntity($user);

            if ($this->user->save($user)) {
                if ($pid && $model->update($pid->id, $user_detail)) {
                    return $this->response->setJSON([
                        'status'    => 200,
                        'success'   => true,
                        'message'   => 'User berhasil diubah',
                    ]);
                } else {
                    $errorMessage = $model->errors() ? implode(", ", $model->errors()) : "Gagal memperbarui profil pendukung. Pastikan data sudah benar.";
                    return $this->response->setJSON([
                        'status'    => 500,
                        'success'   => false,
                        'message'   => $errorMessage
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => "Gagal menyimpan user: " . implode(", ", $this->user->errors())
                ]);
            }
        } else {
            if ($pid && $model->update($pid->id, $user_detail)) {
                return $this->response->setJSON([
                    'status'    => 200,
                    'success'   => true,
                    'message'   => 'User berhasil diubah',
                ]);
            } else {
                $errorMessage = $model->errors() ? implode(", ", $model->errors()) : "Gagal memperbarui profil pendukung. Pastikan data sudah benar.";
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => $errorMessage
                ]);
            }
        }
    }

    public function profile_update()
    {
        $data = $this->request->getPost();
        $data['id'] = user()->id;

        if (!$this->validate([
            'email' => 'required|valid_email',
        ])) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => implode(", ", $this->validator->getErrors())
            ]);
        }

        $user = new UserEntity($data);

        $db = \Config\Database::connect();
        $is_instansi = $db->table('auth_groups_users')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where(['user_id' => user()->id, 'name' => 'instansi'])
            ->countAllResults() > 0;

        if ($is_instansi) {
            $model = new \App\Models\DosenPembimbingModel();
            $profile = $model->where('user_id', user()->id)->first();
            $data_pembimbing = [
                'id'             => $profile ? $profile->id : null,
                'user_id'        => user()->id,
                'nama_instansi'  => $data['nama_instansi'] ?? ($profile->nama_instansi ?? ''),
                'jenis_instansi' => $data['jenis_instansi'] ?? ($profile->jenis_instansi ?? ''),
                'alamat'         => $data['alamat'] ?? ($profile->alamat ?? ''),
                'provinsi'       => $data['provinsi'] ?? ($profile->provinsi ?? ''),
                'kabupaten_kota' => $data['kabupaten_kota'] ?? ($profile->kabupaten_kota ?? ''),
                'kecamatan'      => $data['kecamatan'] ?? ($profile->kecamatan ?? ''),
                'email'          => $data['email'],
                'no_hp'          => $data['no_hp'] ?? ($profile->no_hp ?? ''),
            ];
        } else {
            $model = $this->pembimbing;
            $profile = $model->where('user_id', user()->id)->first();
            $data_pembimbing = [
                'id'     => $profile ? $profile->id : null,
                'user_id' => user()->id,
                'nama'   => $data['nama'] ?? ($profile->nama ?? ''),
                'no_hp'  => $data['no_hp'] ?? ($profile->no_hp ?? ''),
                'email'  => $data['email'],
            ];

            $fileFoto = $this->request->getFile('foto');
            if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
                $namaFoto = $fileFoto->getRandomName();
                $fileFoto->move(FCPATH . 'assets/img/foto_pembimbing/', $namaFoto);
                $data_pembimbing['foto'] = $namaFoto;

                if ($profile && $profile->foto && file_exists(FCPATH . 'assets/img/foto_pembimbing/' . $profile->foto)) {
                    @unlink(FCPATH . 'assets/img/foto_pembimbing/' . $profile->foto);
                }
            }
        }

        if ($this->user->save($user) && $model->save($data_pembimbing)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Profile berhasil diubah',
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => implode(", ", $this->user->errors())
            ]);
        }
    }

    public function destroy($id)
    {
        $this->db = \Config\Database::connect();

        $this->db->table('auth_groups_users')->where('user_id', $id)->delete();
        $this->pembimbing->where('user_id', $id)->delete();

        $dosenModel = new \App\Models\DosenPembimbingModel();
        $dosenModel->where('user_id', $id)->delete();

        if ($this->user->delete($id)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'User berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'User gagal dihapus. Error: ' . json_encode($this->user->db->error())
            ]);
        }
    }

    public function reset_pass()
    {
        $users = new \App\Models\UserModel();

        $rules = [
            'email'        => 'required|valid_email',
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $users->where('email', $this->request->getPost('email'))
            ->where('reset_hash', $this->request->getPost('token'))
            ->first();

        if (null === $user) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        $user->password         = $this->request->getPost('password');
        $user->reset_hash       = null;
        $user->reset_at         = date('Y-m-d H:i:s');
        $user->reset_expires    = null;
        $user->force_pass_reset = false;
        $users->save($user);

        return redirect()->back()->with('message', "Your password has been successfully changed");
    }
}