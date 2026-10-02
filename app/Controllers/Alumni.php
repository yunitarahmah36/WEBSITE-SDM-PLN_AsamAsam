<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Alumni extends BaseController
{
    protected $siswaModel;
    protected $applicationModel;

    public function __construct()
    {
        $this->siswaModel = new \App\Models\SiswaModel();
        $this->applicationModel = new \App\Models\ApplicationModel();
    }

    public function index()
    {
        if (!in_groups('admin')) {
            return redirect()->to('/')->with('error', 'Akses ditolak.');
        }

        $alumni = $this->applicationModel->select('siswa.id as id_siswa, siswa.nama, siswa.instansi, siswa.foto, siswa.alumni_foto, siswa.alumni_testimoni, siswa.alumni_is_tampil, tempat_magang.nama as divisi, siswa.tgl_selesai')
            ->join('siswa', 'siswa.id = lamaran.id_siswa')
            ->join('tempat_magang', 'tempat_magang.id = lamaran.id_tempat', 'left')
            ->where('lamaran.status', 'selesai')
            ->orderBy('siswa.tgl_selesai', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Alumni',
            'page_title' => 'Kelola Galeri & Testimoni Alumni',
            'segment' => ['alumni'],
            'breadcrumb' => ['Dashboard' => base_url(), 'Kelola Alumni' => '#'],
            'alumni' => $alumni
        ];

        return view('alumni/index', $data);
    }

    public function update($id_siswa)
    {
        if (!in_groups('admin')) {
            return $this->response->setJSON(['status' => 403, 'message' => 'Akses ditolak.']);
        }

        $rules = [
            'alumni_foto' => 'max_size[alumni_foto,2048]|is_image[alumni_foto]|mime_in[alumni_foto,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['status' => 400, 'message' => $this->validator->getError('alumni_foto')]);
        }

        $dataUpdate = [
            'alumni_testimoni' => $this->request->getPost('alumni_testimoni'),
            'alumni_is_tampil' => $this->request->getPost('alumni_is_tampil') !== null ? 1 : 0
        ];

        $file = $this->request->getFile('alumni_foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'assets/alumni/', $newName);
            $dataUpdate['alumni_foto'] = $newName;
        }

        $this->siswaModel->update($id_siswa, $dataUpdate);

        return $this->response->setJSON(['status' => 200, 'message' => 'Data alumni berhasil diperbarui!']);
    }

    public function reset($id_siswa)
    {
        if (!in_groups('admin')) {
            return $this->response->setJSON(['status' => 403, 'message' => 'Akses ditolak.']);
        }

        $dataUpdate = [
            'alumni_foto' => null,
            'alumni_testimoni' => null,
            'alumni_is_tampil' => 0
        ];

        $this->siswaModel->update($id_siswa, $dataUpdate);

        return $this->response->setJSON(['status' => 200, 'message' => 'Data galeri & testimoni alumni berhasil dihapus dan disembunyikan.']);
    }
}
