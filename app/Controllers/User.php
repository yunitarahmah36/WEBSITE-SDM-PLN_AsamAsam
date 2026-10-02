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

        $user_detail = [
            "nama"  => $this->request->getPost("nama"),
            "no_hp" => $this->request->getPost("hp"),
            "email" => $this->request->getPost("email"),
        ];

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
            // Explicit validation for common fields
            if (!$this->validate([
                'nama'     => 'required|min_length[3]',
                'username' => 'required|min_length[3]|max_length[30]|is_unique[users.username]',
                'email'    => 'required|valid_email|is_unique[users.email]',
                'hp'       => 'required|numeric|min_length[10]',
                'password' => 'permit_empty|min_length[8]',
            ])) {
                throw new \Exception(implode(", ", $this->validator->getErrors()));
            }

            if($this->user->withGroup($role)->save($user)) {
                $user_detail['user_id'] = $this->user->getInsertID();

                if ($role === 'dosen_pembimbing') {
                    $user_detail['nidn'] = $this->request->getPost('nidn') ?? '';
                    $model = new \App\Models\DosenPembimbingModel();
                } else {
                    $model = $this->pembimbing;
                }

                if (!$model->save($user_detail)){
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

        $user_detail = [
            "nama"  => $this->request->getPost("nama"),
            "no_hp" => $this->request->getPost("hp"),
        ];

        if ($this->request->getPost("email") != $old_email) {
            $user['email'] = $this->request->getPost("email");
            $user_detail['email'] = $this->request->getPost("email");
        }

        if (!empty($this->request->getPost("password"))) {
            $user['password'] = $this->request->getPost("password");
        }

        if ($role === 'dosen_pembimbing') {
            $model = new \App\Models\DosenPembimbingModel();
            $pid = $model->select('id, foto')->where('user_id', $uid)->first();
            $user_detail['nidn'] = $this->request->getPost('nidn') ?? '';
        } else {
            $model = $this->pembimbing;
            $pid = $model->select('id, foto')->where('user_id', $uid)->first();
        }

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'assets/img/foto_pembimbing/', $namaFoto);
            $user_detail['foto'] = $namaFoto;
            
            // Delete old photo
            if ($pid && $pid->foto && file_exists(FCPATH . 'assets/img/foto_pembimbing/' . $pid->foto)) {
                @unlink(FCPATH . 'assets/img/foto_pembimbing/' . $pid->foto);
            }
        }

        if(!empty($user)){
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
                    'message'    => $errorMessage
                ]);
            }
        }
    }

    public function profile_update()
    {
        $data = $this->request->getPost();
        $data['id'] = user()->id;

        if (!$this->validate([
            'nama' => 'required|min_length[3]',
            'no_hp' => 'required|numeric|min_length[10]',
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
        $is_dosen = $db->table('auth_groups_users')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->where(['user_id' => user()->id, 'name' => 'dosen_pembimbing'])
            ->countAllResults() > 0;

        if ($is_dosen) {
            $model = new \App\Models\DosenPembimbingModel();
        } else {
            $model = $this->pembimbing;
        }

        $data_pembimbing = $data;
        $profile = $model->where('user_id', user()->id)->first();
        $data_pembimbing['id'] = $profile ? $profile->id : null;

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'assets/img/foto_pembimbing/', $namaFoto);
            $data_pembimbing['foto'] = $namaFoto;

            // Delete old photo
            if ($profile && $profile->foto && file_exists(FCPATH . 'assets/img/foto_pembimbing/' . $profile->foto)) {
                @unlink(FCPATH . 'assets/img/foto_pembimbing/' . $profile->foto);
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
        // Force delete from child tables manually to avoid any DB constraint lockups
        $this->db = \Config\Database::connect();
        
        // Remove from auth groups mapping
        $this->db->table('auth_groups_users')->where('user_id', $id)->delete();
        
        // Remove from profile tables
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

        
        // Success! Save the new password, and cleanup the reset hash.
        $user->password         = $this->request->getPost('password');
        $user->reset_hash       = null;
        $user->reset_at         = date('Y-m-d H:i:s');
        $user->reset_expires    = null;
        $user->force_pass_reset = false;
        $users->save($user);

        return redirect()->back()->with('message', "Your password has been successfully changed");
    }
}
