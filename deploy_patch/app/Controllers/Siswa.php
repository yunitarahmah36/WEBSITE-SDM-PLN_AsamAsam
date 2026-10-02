<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User as UserEntity;

class Siswa extends BaseController
{
    protected $user;
    protected $siswa;
    protected $angkatan;
    protected $jurusan;
    protected $application;

    public function __construct()
    {
        $this->user = new \App\Models\UserModel();
        $this->siswa = new \App\Models\SiswaModel();
        $this->angkatan = new \App\Models\AngkatanModel();
        $this->jurusan = new \App\Models\JurusanModel();
        $this->application = new \App\Models\ApplicationModel();
    }

    public function index()
    {
        $dsiswa = $this->user
            ->select('users.id, users.username, users.email, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.no_hp, siswa.alamat, angkatan.tahun as angkatan')
            ->join('siswa', 'siswa.user_id = users.id', 'inner')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', "inner")
            ->findAll();

        return view('siswa_add', [
            "title"         => "Magang | Manajemen Siswa",
            "page_title"    => "Manajemen Data Siswa",
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => ['Manajemen', 'Siswa'],
            "angkatan"      => $this->angkatan->findAll(),
            "jurusan"       => $this->jurusan->findAll(),
            "siswa"         => $dsiswa
        ]);
    }

    public function resetPassword()
    {
        $rules = [
            'email' => [
                'label' => lang('Auth.emailAddress'),
                'rules' => 'required|valid_email',
            ],
            'password' => [
                'label' => lang('Auth.password'),
                'rules' => 'required|strong_password',
            ],
            'pass_confirm' => [
                'label' => lang('Auth.repeatPassword'),
                'rules' => 'required|matches[password]',
            ]
        ];

        // validate post data
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // get user by email
        $user = $this->user->where('email', $this->request->getPost('email'))->first();

        if (null === $user) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        // generae token reset password
        $user = $user->generateResetHash();
        $this->user->save($user);

        $user = $this->user->where('email', $this->request->getPost('email'))
            ->where('reset_hash', $user->reset_hash)
            ->first();

        if (null === $user) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        if (!empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
            return redirect()->back()->withInput()->with('error', lang('Auth.resetTokenExpired'));
        }

        // Success! Save the new password, and cleanup the reset hash.
        $user->password         = $this->request->getPost('password');
        $user->reset_hash       = null;
        $user->reset_at         = date('Y-m-d H:i:s');
        $user->reset_expires    = null;
        $user->force_pass_reset = false;
        $this->user->save($user);

        return redirect()->to('/profile#password')->with('success', lang('Auth.resetSuccess'));
    }

    public function ceknim()
    {
        $nim = $this->request->getPost("nim");
        
        // validate alphanumeric
        if (!preg_match('/^[a-zA-Z0-9]+$/', $nim)) {
            return $this->response->setJSON(['assigned' => false]);
        }

        $cek = $this->siswa->where("nim", $nim);
        if ($cek->countAllResults() > 0) {
            return $this->response->setJSON([
                'assigned' => true
            ]);
        } else {
            return $this->response->setJSON([
                'assigned' => false
            ]);
        }
    }

    public function store()
    {
        $nim = $this->request->getPost("nim");
        $password = $this->request->getPost("password");

        if (empty($password)) {
            $password = $nim;
        }

        $siswa_user = new UserEntity([
            "email" => $this->request->getPost("email"),
            "username" => $nim,
            "password" => $password,
            "active" => 1
        ]);

        $siswa_detail = [
            "nim" => $this->request->getPost("nim"),
            "nama" => $this->request->getPost("nama"),
            "kelas" => $this->request->getPost("kelas"),
            "jurusan" => $this->request->getPost("jurusan"),
            "instansi" => $this->request->getPost("instansi"),
            "angkatan" => $this->request->getPost("angkatan"),
            "no_hp" => $this->request->getPost("hp"),
            "alamat" => $this->request->getPost("alamat")
        ];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'assets/img/foto_siswa/', $namaFoto);
            $siswa_detail['foto'] = $namaFoto;
        }

        // Start Transaction
        $this->user->db->transStart();

        try {
            if ($this->user->withGroup('siswa')->save($siswa_user)) {
                $siswa_detail["user_id"] = $this->user->getInsertID();
                $siswa = new UserEntity($siswa_detail);

                if ($this->siswa->save($siswa)) {
                    $this->user->db->transComplete();
                    
                    if ($this->user->db->transStatus() === FALSE) {
                        throw new \Exception("Transaksi database gagal.");
                    }

                    return $this->response->setJSON([
                        'status'    => 200,
                        'success'   => true,
                        'message'   => 'Siswa berhasil ditambahkan',
                        'data'      => $siswa
                    ]);
                } else {
                    throw new \Exception(implode(", ", $this->siswa->errors()));
                }
            } else {
                throw new \Exception(implode(", ", $this->user->errors()));
            }
        } catch (\Exception $e) {
            $this->user->db->transRollback();
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => $e->getMessage()
            ]);
        }
    }

    public function save_excel()
    {
        $data_excel = $this->request->getFile('excel');
        $ext = $data_excel->getClientExtension();

        if ($ext == "xlsx") {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } else if ($ext == "xls") {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Format file tidak didukung'
            ]);
        }

        $spreadsheet = $reader->load($data_excel);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $this->user->db->transStart();

        try {
            foreach ($data as $x => $row) {
                if ($x == 0) continue;
                $angkatan = explode("/", $row[4]);
                $angkatan_fix = $this->angkatan->where("tahun", $angkatan[0])->findAll();

                if (count($angkatan) == 2) {
                    $user_data = new UserEntity([
                        'email'     => $row[2],
                        'username'  => $row[0],
                        'password'  => $row[0],
                        'active'    => 1
                    ]);

                    if (!$this->user->withGroup('siswa')->save($user_data)) {
                        throw new \Exception("Gagal simpan user di baris " . ($x + 1) . ": " . implode(", ", $this->user->errors()));
                    }

                    $siswa_data = [
                        'user_id' => $this->user->getInsertID(),
                        'nim' => $row[0],
                        'nama' => $row[1],
                        'kelas' => $row[3],
                        'angkatan' => $angkatan_fix[($angkatan[1] - 1)]->id,
                        'no_hp' => $row[5],
                        'alamat' => $row[6],
                        'jurusan' => $row[7] ?? null,
                        'instansi' => $row[8] ?? null,
                    ];

                    if (!$this->siswa->save($siswa_data)) {
                        throw new \Exception("Gagal simpan detail siswa di baris " . ($x + 1) . ": " . implode(", ", $this->siswa->errors()));
                    }
                } else {
                    throw new \Exception("Format tahun angkatan salah di baris " . ($x + 1));
                }
            }

            $this->user->db->transComplete();

            if ($this->user->db->transStatus() === FALSE) {
                throw new \Exception("Transaksi database gagal.");
            }

            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Siswa berhasil ditambahkan'
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
        $siswa_user = [];
        $old_email = $this->request->getPost("old_email");

        $siswa_detail = [
            "nim" => $this->request->getPost("nim"),
            "nama" => $this->request->getPost("nama"),
            "kelas" => $this->request->getPost("kelas"),
            "jurusan" => $this->request->getPost("jurusan"),
            "instansi" => $this->request->getPost("instansi"),
            "angkatan" => $this->request->getPost("angkatan"),
            "no_hp" => $this->request->getPost("hp"),
            "alamat" => $this->request->getPost("alamat")
        ];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'assets/img/foto_siswa/', $namaFoto);
            $siswa_detail['foto'] = $namaFoto;

            // Delete old photo if exists
            $old_data = $this->siswa->where("nim", $this->request->getPost("nim"))->first();
            if ($old_data && $old_data->foto && file_exists(FCPATH . 'assets/img/foto_siswa/' . $old_data->foto)) {
                @unlink(FCPATH . 'assets/img/foto_siswa/' . $old_data->foto);
            }
        }

        if ($this->request->getPost("email") != $old_email) {
            $siswa_user["email"] = $this->request->getPost("email");
        }

        if (!empty($this->request->getPost("password"))) {
            $siswa_user["password"] = $this->request->getPost("password");
            // If password is updated, force session reset or clean reset hashes if needed
            // UserEntity handles password hashing
        }

        if (!empty($siswa_user)) {
            $siswa_user = new UserEntity($siswa_user);
            if ($this->user->update($this->request->getPost("items"), $siswa_user)) {
                $id = $this->siswa->where("nim", $this->request->getPost("nim"))->first()->id;

                if ($this->siswa->update($id, $siswa_detail)) {
                    return $this->response->setJSON([
                        'status'    => 200,
                        'success'   => true,
                        'message'   => 'Siswa berhasil diperbarui',
                        'data'      => $siswa_detail
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status'    => 500,
                        'success'   => false,
                        'message'   => implode(", ", $this->siswa->errors())
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => implode(", ", $this->user->errors())
                ]);
            }
        } else {
            $id = $this->siswa->where("nim", $this->request->getPost("nim"))->first()->id;
            
            if ($this->siswa->update($id, $siswa_detail)) {
                return $this->response->setJSON([
                    'status'    => 200,
                    'success'   => true,
                    'message'   => 'Siswa berhasil diperbarui',
                    'data'      => $siswa_detail
                ]);
            } else {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => implode(", ", $this->siswa->errors())
                ]);
            }
        }
    }

    public function profile_update()
    {
        $data = $this->request->getPost();
        $data['id'] = getSidByUid(user()->id);

        // validate
        if (!$this->validate([
            'email' => ['rules' => 'required|valid_email'],
            'nama' => ['rules' => 'required|min_length[3]'],
            'kelas' => ['rules' => 'required'],
            'no_hp' => ['rules' => 'required|numeric'],
            'alamat' => ['rules' => 'required'],
        ])) {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => implode(", ", $this->validator->getErrors())
            ]);
        }

        $siswa_data = [
            'email' => $data['email'],
            'id' => user()->id,
        ];

        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'assets/img/foto_siswa/', $namaFoto);
            $data['foto'] = $namaFoto;

            // Delete old photo
            $old_data = $this->siswa->find($data['id']);
            if ($old_data && $old_data->foto && file_exists(FCPATH . 'assets/img/foto_siswa/' . $old_data->foto)) {
                @unlink(FCPATH . 'assets/img/foto_siswa/' . $old_data->foto);
            }
        }

        if (isset($data['username'])) {
            $siswa_data['username'] = $data['username'];
        }

        if ($this->siswa->save($data) && $this->user->save($siswa_data)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Profil berhasil diperbarui',
                'data'      => $data
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => implode(", ", $this->siswa->errors())
            ]);
        }
    }

    public function destroy($id)
    {
        if ($this->user->delete($id)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Siswa berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Siswa gagal dihapus'
            ]);
        }
    }

    public function upload_laporan()
    {
        $file = $this->request->getFile('laporan');
        $filename = $file->getRandomName();
        
        $data = [
            'id' => getSidByUid(user_id()),
            'laporan' => $filename,
        ];
        
        $application = $this->application->where('id_siswa', getSidByUid(user_id()))->first();
        
        if ($this->siswa->save($data)) {
            $file->move(FCPATH . 'assets/laporan', $filename);
            $this->application->update($application->id, ['status' => 'selesai']);

            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Laporan berhasil diupload'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => implode(", ", $this->siswa->errors())
            ]);
        }
    }

    public function approve_laporan()
    {
        $id = $this->request->getPost('id');
        $is_approved = $this->request->getPost('approved') == '1';

        if ($this->siswa->update($id, ['is_laporan_approved' => $is_approved])) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => $is_approved ? 'Laporan berhasil disetujui' : 'Persetujuan laporan dibatalkan'
            ]);
        }

        return $this->response->setJSON([
            'status'    => 500,
            'success'   => false,
            'message'   => 'Gagal memperbarui status laporan'
        ]);
    }
}
