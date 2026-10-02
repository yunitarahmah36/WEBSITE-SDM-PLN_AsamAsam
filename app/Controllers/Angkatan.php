<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Angkatan extends BaseController
{

    protected $angkatan;

    public function __construct() {
        $this->angkatan = new \App\Models\AngkatanModel();
    }

    public function store()
    {
        if ($this->angkatan->save([
            'tahun' => $this->request->getPost('angkatan'),
            'nama' => $this->request->getPost('nama'),
            'tgl_mulai' => $this->request->getPost('mulai'),
            'tgl_selesai' => $this->request->getPost('selesai'),
        ])) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Tahun angkatan berhasil ditambahkan'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Tahun angkatan gagal ditambahkan. Error: ' . implode(", ", $this->angkatan->errors())
            ]);
        }
    }

    public function destroy($id)
    {
        $siswaModel = new \App\Models\SiswaModel();
        $studentCount = $siswaModel->where('angkatan', $id)->countAllResults();

        if ($studentCount > 0) {
            return $this->response->setJSON([
                'status'    => 400,
                'success'   => false,
                'message'   => "Gagal menghapus. Masih ada $studentCount siswa yang terdaftar di angkatan ini. Harap pindahkan atau hapus siswa tersebut terlebih dahulu."
            ]);
        }

        if ($this->angkatan->delete($id)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Tahun angkatan berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => implode(", ", $this->angkatan->errors())
            ]);
        }
    }
}
