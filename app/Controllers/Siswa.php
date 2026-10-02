<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User as UserEntity;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            ->select('users.id, users.username, users.email, siswa.nim as nis, siswa.nama, siswa.kelas, siswa.no_hp, siswa.alamat, angkatan.tahun as angkatan')
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

    public function ceknis()
    {
        $nis = $this->request->getPost("nis") ?: $this->request->getPost("nim");
        
        // validate alphanumeric
        if (!preg_match('/^[a-zA-Z0-9]+$/', $nis)) {
            return $this->response->setJSON(['assigned' => false]);
        }

        $cek = $this->siswa->where("nim", $nis);
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
        $nis = $this->request->getPost("nim") ?: $this->request->getPost("nis");
        $siswa_user = new UserEntity([
            "email" => $this->request->getPost("email"),
            "username" => $nis,
            "password" => $nis,
            "active" => 1
        ]);

        $siswa_detail = [
            "nim" => $nis,
            "nama" => $this->request->getPost("nama"),
            "kelas" => $this->request->getPost("kelas"),
            "angkatan" => $this->request->getPost("angkatan"),
            "no_hp" => $this->request->getPost("hp"),
            "alamat" => $this->request->getPost("alamat"),
            "tgl_masuk" => $this->request->getPost("tgl_masuk"),
            "tgl_selesai" => $this->request->getPost("tgl_selesai"),
            "jurusan" => $this->request->getPost("jurusan"),
            "instansi" => $this->request->getPost("instansi")
        ];

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
        $nis = $this->request->getPost("nim") ?: $this->request->getPost("nis");

        $siswa_detail = [
            "nim" => $nis,
            "nama" => $this->request->getPost("nama"),
            "kelas" => $this->request->getPost("kelas"),
            "no_hp" => $this->request->getPost("hp"),
            "alamat" => $this->request->getPost("alamat")
        ];

        if ($this->request->getPost("angkatan") !== null) {
            $siswa_detail["angkatan"] = $this->request->getPost("angkatan");
        }
        if ($this->request->getPost("tgl_masuk") !== null) {
            $siswa_detail["tgl_masuk"] = $this->request->getPost("tgl_masuk");
        }
        if ($this->request->getPost("tgl_selesai") !== null) {
            $siswa_detail["tgl_selesai"] = $this->request->getPost("tgl_selesai");
        }
        if ($this->request->getPost("jurusan") !== null) {
            $siswa_detail["jurusan"] = $this->request->getPost("jurusan");
        }
        if ($this->request->getPost("instansi") !== null) {
            $siswa_detail["instansi"] = $this->request->getPost("instansi");
        }

        // Handle profile photo update if uploaded
        $fileFoto = $this->request->getFile("foto");
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $fotoName = $fileFoto->getRandomName();
            $fileFoto->move("assets/img/", $fotoName);
            $siswa_detail["foto"] = "/assets/img/" . $fotoName;
        }

        // Handle surat_balasan upload if uploaded
        $fileBalasan = $this->request->getFile("surat_balasan");
        $uploaded_balasan = false;
        if ($fileBalasan && $fileBalasan->isValid() && !$fileBalasan->hasMoved()) {
            if (in_array(strtolower($fileBalasan->getClientExtension()), ['pdf'])) {
                $siswa_rec = $this->siswa->where("nim", $nis)->first();
                if ($siswa_rec && !empty($siswa_rec->surat_balasan) && file_exists('assets/berkas/' . $siswa_rec->surat_balasan)) {
                    @unlink('assets/berkas/' . $siswa_rec->surat_balasan);
                }
                $balasanName = $fileBalasan->getRandomName();
                $fileBalasan->move('assets/berkas', $balasanName);
                $siswa_detail["surat_balasan"] = $balasanName;
                $uploaded_balasan = true;
            }
        }

        if ($this->request->getPost("email") != $old_email) {
            $siswa_user["email"] = $this->request->getPost("email");
        }

        if (!empty($this->request->getPost("password"))) {
            $siswa_user["password"] = $this->request->getPost("password");
        }

        // Handle Dosen Pembimbing update if provided
        $id_dosen = $this->request->getPost("id_dosen_pembimbing");
        if ($id_dosen !== null) {
            $siswa_rec = $this->siswa->where("nim", $nis)->first();
            if ($siswa_rec) {
                $appModel = new \App\Models\ApplicationModel();
                $appModel->where("id_siswa", $siswa_rec->id)
                         ->groupStart()
                            ->where("status", "accepted")
                            ->orWhere("status", "selesai")
                         ->groupEnd()
                         ->set(["id_dosen_pembimbing" => $id_dosen ?: null])
                         ->update();
            }
        }

        if (!empty($siswa_user)) {
            $siswa_user = new UserEntity($siswa_user);
            if ($this->user->update($this->request->getPost("items"), $siswa_user)) {
                $id = $this->siswa->where("nim", $nis)->first()->id;

                if ($this->siswa->update($id, $siswa_detail)) {
                    $msg_append = '';
                    if ($uploaded_balasan) {
                        $email_status = $this->sendEmailNotifSuratBalasan($id);
                        if ($email_status !== true) {
                            $msg_append = ' (Namun notifikasi email gagal: ' . $email_status . ')';
                        }
                    }
                    return $this->response->setJSON([
                        'status'    => 200,
                        'success'   => true,
                        'message'   => 'Siswa berhasil diperbarui' . $msg_append,
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
            $id = $this->siswa->where("nim", $nis)->first()->id;
            
            if ($this->siswa->update($id, $siswa_detail)) {
                $msg_append = '';
                if ($uploaded_balasan) {
                    $email_status = $this->sendEmailNotifSuratBalasan($id);
                    if ($email_status !== true) {
                        $msg_append = ' (Namun notifikasi email gagal: ' . $email_status . ')';
                    }
                }
                return $this->response->setJSON([
                    'status'    => 200,
                    'success'   => true,
                    'message'   => 'Siswa berhasil diperbarui' . $msg_append,
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
            'nama' => ['rules' => 'required'],
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

        $siswa = $this->siswa->find($data['id']);
        if (!$siswa) {
            return $this->response->setJSON([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Siswa tidak ditemukan'
            ]);
        }

        // Handle File KTP
        $ktpFile = $this->request->getFile('ktp');
        if ($ktpFile && $ktpFile->isValid() && !$ktpFile->hasMoved()) {
            if (!in_array(strtolower($ktpFile->getClientExtension()), ['pdf'])) {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => 'Berkas KTP harus berformat PDF'
                ]);
            }
            if (!empty($siswa->ktp) && file_exists('assets/berkas/' . $siswa->ktp)) {
                @unlink('assets/berkas/' . $siswa->ktp);
            }
            $ktpName = $ktpFile->getRandomName();
            $ktpFile->move('assets/berkas', $ktpName);
            $data['ktp'] = $ktpName;
        }

        // Handle File BPJS
        $bpjsFile = $this->request->getFile('bpjs');
        if ($bpjsFile && $bpjsFile->isValid() && !$bpjsFile->hasMoved()) {
            if (!in_array(strtolower($bpjsFile->getClientExtension()), ['pdf'])) {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => 'Berkas BPJS harus berformat PDF'
                ]);
            }
            if (!empty($siswa->bpjs) && file_exists('assets/berkas/' . $siswa->bpjs)) {
                @unlink('assets/berkas/' . $siswa->bpjs);
            }
            $bpjsName = $bpjsFile->getRandomName();
            $bpjsFile->move('assets/berkas', $bpjsName);
            $data['bpjs'] = $bpjsName;
        }

        // Handle File KTM
        $ktmFile = $this->request->getFile('ktm');
        if ($ktmFile && $ktmFile->isValid() && !$ktmFile->hasMoved()) {
            if (!in_array(strtolower($ktmFile->getClientExtension()), ['pdf'])) {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => 'Berkas KTM harus berformat PDF'
                ]);
            }
            if (!empty($siswa->ktm) && file_exists('assets/berkas/' . $siswa->ktm)) {
                @unlink('assets/berkas/' . $siswa->ktm);
            }
            $ktmName = $ktmFile->getRandomName();
            $ktmFile->move('assets/berkas', $ktmName);
            $data['ktm'] = $ktmName;
        }

        // Handle File Surat Balasan
        $balasanFile = $this->request->getFile('surat_balasan');
        if ($balasanFile && $balasanFile->isValid() && !$balasanFile->hasMoved()) {
            if (!in_array(strtolower($balasanFile->getClientExtension()), ['pdf'])) {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => 'Berkas Surat Balasan harus berformat PDF'
                ]);
            }
            if (!empty($siswa->surat_balasan) && file_exists('assets/berkas/' . $siswa->surat_balasan)) {
                @unlink('assets/berkas/' . $siswa->surat_balasan);
            }
            $balasanName = $balasanFile->getRandomName();
            $balasanFile->move('assets/berkas', $balasanName);
            $data['surat_balasan'] = $balasanName;
        }

        // Handle Foto
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            if (!empty($siswa->foto) && $siswa->foto != 'default.jpg' && file_exists('assets/img/foto_siswa/' . $siswa->foto)) {
                @unlink('assets/img/foto_siswa/' . $siswa->foto);
            }
            $fotoName = $fotoFile->getRandomName();
            $fotoFile->move('assets/img/foto_siswa', $fotoName);
            $data['foto'] = $fotoName;
        }

        $siswa_data = [
            'email' => $data['email'],
            'id' => user()->id,
        ];

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

    public function upload_surat_balasan()
    {
        $id = $this->request->getPost('id');
        $siswa = $this->siswa->find($id);

        if (!$siswa) {
            return $this->response->setJSON([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Siswa tidak ditemukan',
                'csrf_hash' => csrf_hash()
            ]);
        }

        $balasanFile = $this->request->getFile('surat_balasan');
        if ($balasanFile && $balasanFile->isValid() && !$balasanFile->hasMoved()) {
            if (!in_array(strtolower($balasanFile->getClientExtension()), ['pdf'])) {
                return $this->response->setJSON([
                    'status'    => 500,
                    'success'   => false,
                    'message'   => 'Berkas Surat Balasan harus berformat PDF',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            if (!empty($siswa->surat_balasan) && file_exists('assets/berkas/' . $siswa->surat_balasan)) {
                @unlink('assets/berkas/' . $siswa->surat_balasan);
            }
            $balasanName = $balasanFile->getRandomName();
            $balasanFile->move('assets/berkas', $balasanName);

            $this->siswa->update($id, ['surat_balasan' => $balasanName]);

            // Send Gmail notification to student
            $email_status = $this->sendEmailNotifSuratBalasan($id);
            $msg_append = ($email_status === true) ? '' : ' (Namun notifikasi email gagal: ' . $email_status . ')';

            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Surat Balasan berhasil diunggah.' . $msg_append,
                'surat_balasan' => $balasanName,
                'csrf_hash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON([
            'status'    => 400,
            'success'   => false,
            'message'   => 'Tidak ada berkas yang diunggah atau berkas tidak valid',
            'csrf_hash' => csrf_hash()
        ]);
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
            $file->move('assets/laporan', $filename);
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

    public function finish_without_report()
    {
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->where('id', getSidByUid(user_id()))->first();
        if (!$siswa) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ]);
        }

        $instansiLower = strtolower($siswa->instansi ?? '');
        $kelasLower = strtolower($siswa->kelas ?? '');
        $isSmk = (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12']));

        if (!$isSmk) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hanya siswa SMK yang dapat menyelesaikan magang tanpa laporan.'
            ]);
        }

        $application = $this->application->where('id_siswa', $siswa->id)->first();
        if ($application) {
            $this->application->update($application->id, ['status' => 'selesai']);
        }

        // Clear laporan fields to ensure they are empty/null
        $siswaModel->update($siswa->id, [
            'laporan' => null, 
            'is_laporan_approved' => 0
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status magang berhasil diselesaikan'
        ]);
    }

    public function upload_pernyataan()
    {
        $file = $this->request->getFile('surat_pernyataan');
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $validationRules = [
                'surat_pernyataan' => [
                    'rules' => 'uploaded[surat_pernyataan]|max_size[surat_pernyataan,5120]|ext_in[surat_pernyataan,pdf,doc,docx]',
                    'errors' => [
                        'uploaded' => 'Silakan pilih file terlebih dahulu.',
                        'max_size' => 'Ukuran file maksimal 5MB.',
                        'ext_in'   => 'Format file harus PDF, DOC, atau DOCX.'
                    ]
                ]
            ];

            if (!$this->validate($validationRules)) {
                return redirect()->back()->with('error', $this->validator->getError('surat_pernyataan'));
            }

            $filename = $file->getRandomName();
            $sid = getSidByUid(user_id());

            if (!$sid) {
                return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
            }

            $currentSiswa = $this->siswa->find($sid);
            if ($currentSiswa && !empty($currentSiswa->surat_pernyataan)) {
                $oldFile = FCPATH . 'assets/berkas/' . $currentSiswa->surat_pernyataan;
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            if ($this->siswa->update($sid, ['surat_pernyataan' => $filename])) {
                $file->move('assets/berkas', $filename);
                return redirect()->back()->with('success', 'Surat pernyataan berhasil diunggah.');
            } else {
                return redirect()->back()->with('error', 'Gagal memperbarui data surat pernyataan.');
            }
        }

        return redirect()->back()->with('error', 'File tidak valid atau tidak ditemukan.');
    }

    private function sendEmailNotifSuratBalasan($id_siswa)
    {
        try {
            $db = \Config\Database::connect();
            $siswa = $db->table('siswa')->where('id', $id_siswa)->get()->getRow();
            if (!$siswa) return 'Siswa tidak ditemukan';

            $user = $db->table('users')->where('id', $siswa->user_id)->get()->getRow();
            if (!$user || empty($user->email)) return 'Email siswa tidak ditemukan atau kosong';

            $email = \Config\Services::email();
            $email->clear();
            $email->setFrom(
                env('SMTP_USER', 'siamam@maxinide.com'),
                'Sistem Informasi Magang'
            );
            $email->setTo($user->email);
            $email->setSubject('Surat Balasan Magang Telah Diunggah');
            
            $message = "Halo {$siswa->nama},<br><br>
            Surat balasan magang Anda telah diunggah oleh pihak admin / pembimbing lapangan.<br>
            Silakan login ke sistem untuk memeriksa dan mengunduh berkas surat balasan magang tersebut.<br><br>
            Terima kasih.";

            $email->setMessage($message);
            $email->setMailType('html');
            if ($email->send(false)) {
                return true;
            } else {
                return $email->printDebugger(['headers']);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function exportExcel()
    {
        $query = $this->user
            ->select('users.id, users.username, users.email, users.active, siswa.id as id_siswa_real, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.no_hp, siswa.alamat, siswa.foto, siswa.surat_permohonan, siswa.surat_pernyataan, siswa.cv, siswa.laporan, siswa.sertifikat_mode, siswa.sertifikat_file, siswa.is_safety_ready, siswa.is_laporan_approved, siswa.tgl_masuk, siswa.tgl_selesai, siswa.nik, siswa.tempat_lahir, siswa.tanggal_lahir, siswa.jenis_kelamin, siswa.no_hp_emergency, siswa.ktp, siswa.bpjs, siswa.ktm, siswa.surat_balasan, siswa.created_at as tgl_daftar, angkatan.nama as angkatan, angkatan.tahun, COALESCE((SELECT status FROM lamaran WHERE lamaran.id_siswa = siswa.id AND status IN (\'accepted\', \'selesai\') LIMIT 1), (SELECT status FROM lamaran WHERE lamaran.id_siswa = siswa.id ORDER BY created_at DESC LIMIT 1)) as lamaran_status')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('siswa', 'siswa.user_id = users.id', 'left')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->where('auth_groups.name', 'siswa');

        $query->groupStart()
            ->where("NOT EXISTS (SELECT 1 FROM lamaran WHERE lamaran.id_siswa = siswa.id AND lamaran.status = 'pending')")
        ->groupEnd();
        
        $dsiswa = $query->findAll();
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'DATA SISWA MAGANG');
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'NIM/NIS');
        $sheet->setCellValue('C3', 'Nama');
        $sheet->setCellValue('D3', 'Angkatan');
        $sheet->setCellValue('E3', 'Jurusan');
        $sheet->setCellValue('F3', 'Instansi (Asal Sekolah/Kampus)');
        $sheet->setCellValue('G3', 'No HP');
        $sheet->setCellValue('H3', 'Status Lamaran');
        $sheet->setCellValue('I3', 'Tanggal Mulai');
        $sheet->setCellValue('J3', 'Tanggal Selesai');

        $row = 4;
        $no = 1;
        foreach ($dsiswa as $s) {
            if (in_array('siswa', $s->getRoles())) {
                $status = '';
                if ($s->lamaran_status == 'selesai') $status = 'Selesai';
                elseif ($s->lamaran_status == 'accepted') $status = 'Aktif';
                else $status = 'Tidak Aktif';

                $sheet->setCellValue('A' . $row, $no++);
                $sheet->setCellValue('B' . $row, $s->nim);
                $sheet->setCellValue('C' . $row, $s->nama);
                $sheet->setCellValue('D' . $row, $s->angkatan . ' - ' . $s->tahun);
                $sheet->setCellValue('E' . $row, $s->jurusan);
                $sheet->setCellValue('F' . $row, $s->instansi);
                $sheet->setCellValue('G' . $row, $s->no_hp);
                $sheet->setCellValue('H' . $row, $status);
                $sheet->setCellValue('I' . $row, $s->tgl_masuk ? date('d/m/Y', strtotime($s->tgl_masuk)) : '-');
                $sheet->setCellValue('J' . $row, $s->tgl_selesai ? date('d/m/Y', strtotime($s->tgl_selesai)) : '-');
                $row++;
            }
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Siswa_Magang_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $query = $this->user
            ->select('users.id, users.username, users.email, users.active, siswa.id as id_siswa_real, siswa.nim, siswa.nama, siswa.kelas, siswa.jurusan, siswa.instansi, siswa.no_hp, siswa.alamat, siswa.foto, siswa.surat_permohonan, siswa.surat_pernyataan, siswa.cv, siswa.laporan, siswa.sertifikat_mode, siswa.sertifikat_file, siswa.is_safety_ready, siswa.is_laporan_approved, siswa.tgl_masuk, siswa.tgl_selesai, siswa.nik, siswa.tempat_lahir, siswa.tanggal_lahir, siswa.jenis_kelamin, siswa.no_hp_emergency, siswa.ktp, siswa.bpjs, siswa.ktm, siswa.surat_balasan, siswa.created_at as tgl_daftar, angkatan.nama as angkatan, angkatan.tahun, COALESCE((SELECT status FROM lamaran WHERE lamaran.id_siswa = siswa.id AND status IN (\'accepted\', \'selesai\') LIMIT 1), (SELECT status FROM lamaran WHERE lamaran.id_siswa = siswa.id ORDER BY created_at DESC LIMIT 1)) as lamaran_status')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
            ->join('siswa', 'siswa.user_id = users.id', 'left')
            ->join('angkatan', 'angkatan.id = siswa.angkatan', 'left')
            ->where('auth_groups.name', 'siswa');

        $query->groupStart()
            ->where("NOT EXISTS (SELECT 1 FROM lamaran WHERE lamaran.id_siswa = siswa.id AND lamaran.status = 'pending')")
        ->groupEnd();
        
        $dsiswa = $query->findAll();
        
        $html = '<h2 style="text-align: center;">Data Siswa Magang</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse; font-size: 11px;">';
        $html .= '<thead><tr>';
        $html .= '<th>No</th>';
        $html .= '<th>NIM/NIS</th>';
        $html .= '<th>Nama</th>';
        $html .= '<th>Jurusan</th>';
        $html .= '<th>Instansi</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Masa Magang</th>';
        $html .= '</tr></thead><tbody>';

        $no = 1;
        foreach ($dsiswa as $s) {
            if (in_array('siswa', $s->getRoles())) {
                $status = '';
                if ($s->lamaran_status == 'selesai') $status = 'Selesai';
                elseif ($s->lamaran_status == 'accepted') $status = 'Aktif';
                else $status = 'Tidak Aktif';

                $masa_magang = '-';
                if ($s->tgl_masuk && $s->tgl_selesai) {
                    $masa_magang = date('d/m/Y', strtotime($s->tgl_masuk)) . ' - ' . date('d/m/Y', strtotime($s->tgl_selesai));
                }

                $html .= '<tr>';
                $html .= '<td style="text-align: center;">' . $no++ . '</td>';
                $html .= '<td>' . $s->nim . '</td>';
                $html .= '<td>' . $s->nama . '</td>';
                $html .= '<td>' . $s->jurusan . '</td>';
                $html .= '<td>' . $s->instansi . '</td>';
                $html .= '<td>' . $status . '</td>';
                $html .= '<td>' . $masa_magang . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody></table>';

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("Data_Siswa_Magang_" . date('Ymd_His') . ".pdf", ["Attachment" => true]);
        exit;
    }
}
