<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class TempatMagang extends BaseController
{
    protected $tempat;

    public function __construct() {
        $this->tempat = new \App\Models\TempatModel();
    }

    public function store()
    {
        if (!$this->validate([
            'nama' => [
                'rules' => 'required|is_unique[tempat_magang.nama]',
                'errors' => ['is_unique' => 'Nama Divisi Magang ini sudah ada, mohon gunakan nama lain.']
            ]
        ])) {
            return $this->response->setJSON([
                'success' => false,
                'status' => 400,
                'message' => implode(', ', $this->validator->getErrors())
            ]);
        }

        $file = $this->request->getFile('foto');
        $file_name = null;
        if ($file && $file->getError() == 0) {
            $file_name = $file->getRandomName();
        }

        $data = [
            'status'        => $this->request->getPost('status'),
            'pid'           => $this->request->getPost('pembimbing'),
            'kuota'         => $this->request->getPost('kuota'),
            'nama'          => $this->request->getPost('nama'),
            'hp'            => $this->request->getPost('kontak'),
            'email'         => $this->request->getPost('email'),
            'alamat'        => $this->request->getPost('alamat'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
            'foto'          => $file_name
        ];

        if ($this->tempat->insert($data)) {
            if ($file && $file->getError() == 0) {
                $file->move('assets/img/tempat_magang', $file_name);
            }
            return $this->response->setJSON([
                'success' => true,
                'status' => 200,
                'message' => 'Berhasil menambahkan tempat magang'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'status' => 500,
                'message' => is_array($this->tempat->errors()) ? implode(', ', $this->tempat->errors()) : $this->tempat->errors()
            ]);
        }
    }

    public function status_update()
    {
        if ($this->tempat->save([
            'id' => $this->request->getPost('item'),
            'status' => $this->request->getPost('status')
        ])) {
            return $this->response->setJSON([
                'success' => true,
                'status' => 200,
                'message' => 'Berhasil mengubah status tempat magang'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'status' => 500,
                'message' => is_array($this->tempat->errors()) ? implode(', ', $this->tempat->errors()) : $this->tempat->errors()
            ]);
        }
    }

    public function update()
    {
        $id = $this->request->getPost('item');
        
        if (!$this->validate([
            'nama' => [
                'rules' => "required|is_unique[tempat_magang.nama,id,{$id}]",
                'errors' => ['is_unique' => 'Nama Divisi Magang ini sudah ada, mohon gunakan nama lain.']
            ]
        ])) {
            return $this->response->setJSON([
                'success' => false,
                'status' => 400,
                'message' => implode(', ', $this->validator->getErrors())
            ]);
        }

        $file = $this->request->getFile('foto');
        if ($file->getError() !== 0 || $file->getError() == 4) {
            $data = [
                'id'            => $this->request->getPost('item'),
                'pid'           => $this->request->getPost('pembimbing'),
                'nama'          => $this->request->getPost('nama'),
                'hp'            => $this->request->getPost('kontak'),
                'email'         => $this->request->getPost('email'),
                'kuota'         => $this->request->getPost('kuota'),
                'status'        => $this->request->getPost('status'),
                'alamat'        => $this->request->getPost('alamat'),
                'deskripsi'     => $this->request->getPost('deskripsi')
            ];
        } else {
            $file_name = $file->getRandomName();
            $data = [
                'id'            => $this->request->getPost('item'),
                'pid'           => $this->request->getPost('pembimbing'),
                'status'        => $this->request->getPost('status'),
                'kuota'         => $this->request->getPost('kuota'),
                'nama'          => $this->request->getPost('nama'),
                'hp'            => $this->request->getPost('kontak'),
                'email'         => $this->request->getPost('email'),
                'alamat'        => $this->request->getPost('alamat'),
                'deskripsi'     => $this->request->getPost('deskripsi'),
                'foto'          => $file_name
            ];
        } 

        if ($this->tempat->save($data)) {
            if ($file->getError() == 0) {
                $file->move('assets/img/tempat_magang', $file_name);
            }
            return $this->response->setJSON([
                'success' => true,
                'status' => 200,
                'message' => 'Berhasil mengubah tempat magang'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'status' => 500,
                'message' => is_array($this->tempat->errors()) ? implode(', ', $this->tempat->errors()) : $this->tempat->errors()
            ]);
        }

        return $this->response->setJSON($this->request->getPost());
    }

    public function destroy($id)
    {
        $data = $this->tempat->find($id);
        
        if (!$data) {
            return $this->response->setJSON([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data tempat magang tidak ditemukan'
            ]);
        }

        $foto = $data->foto;
        
        if ($this->tempat->delete($id)) {
            // Only try to delete if it's NOT a URL and the file exists
            if (!filter_var($foto, FILTER_VALIDATE_URL) && $foto) {
                $path = 'assets/img/tempat_magang/' . $foto;
                if (is_file($path)) {
                    @unlink($path);
                }
            }

            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Tempat magang berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => is_array($this->tempat->errors()) ? implode(', ', $this->tempat->errors()) : $this->tempat->errors()
            ]);
        }
    }
}
