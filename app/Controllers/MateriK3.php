<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;

class MateriK3 extends BaseController
{
    protected $materiK3;

    public function __construct()
    {
        $this->materiK3 = new \App\Models\MateriK3Model();
    }

    public function index()
    {
        $data = [
            'title'      => 'Magang | Materi Safety & K3',
            'page_title' => 'Daftar Materi Safety & K3',
            'segment'    => $this->request->getUri()->getSegments(),
            'breadcrumb' => ['Materi K3'],
            'materi'     => $this->materiK3->findAll(),
            'is_safety_ready' => false
        ];

        if (in_groups('siswa')) {
            $siswaModel = new \App\Models\SiswaModel();
            $siswa = $siswaModel->where('user_id', user_id())->first();
            if ($siswa) {
                $data['is_safety_ready'] = (bool)$siswa->is_safety_ready;
            }
        }

        return view('materi_k3/index', $data);
    }

    public function store()
    {
        $validationRule = [
            'judul' => 'required',
            'file_materi' => [
                'label' => 'File Materi',
                'rules' => 'uploaded[file_materi]|max_size[file_materi,5120]|ext_in[file_materi,pdf,ppt,pptx,doc,docx,zip,rar]'
            ],
        ];

        if (! $this->validate($validationRule)) {
            return redirect()->back()->with('error', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_materi');
        if (! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/materi_k3', $newName);
            
            $this->materiK3->save([
                'judul'       => $this->request->getPost('judul'),
                'deskripsi'   => $this->request->getPost('deskripsi'),
                'file_materi' => $newName
            ]);
            
            return redirect()->back()->with('success', 'Materi berhasil diunggah.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }

    public function update($id)
    {
        $validationRule = [
            'judul' => 'required',
        ];

        // Only validate file if it's uploaded
        $file = $this->request->getFile('file_materi');
        if ($file && $file->isValid()) {
            $validationRule['file_materi'] = [
                'label' => 'File Materi',
                'rules' => 'uploaded[file_materi]|max_size[file_materi,5120]|ext_in[file_materi,pdf,ppt,pptx,doc,docx,zip,rar]'
            ];
        }

        if (! $this->validate($validationRule)) {
            return redirect()->back()->with('error', $this->validator->getErrors());
        }

        $materi = $this->materiK3->find($id);
        if (!$materi) {
            return redirect()->back()->with('error', 'Materi tidak ditemukan.');
        }

        $data = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/materi_k3', $newName);
            $data['file_materi'] = $newName;

            // Delete old file
            $oldPath = FCPATH . 'uploads/materi_k3/' . $materi->file_materi;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $this->materiK3->update($id, $data);
        return redirect()->back()->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $materi = $this->materiK3->find($id);
        if ($materi) {
            $filePath = FCPATH . 'uploads/materi_k3/' . $materi->file_materi;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->materiK3->delete($id);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Materi berhasil dihapus.'
            ]);
        }
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Materi tidak ditemukan.'
        ]);
    }
    public function complete()
    {
        $siswaModel = new \App\Models\SiswaModel();
        $siswa = $siswaModel->where('user_id', user_id())->first();

        if ($siswa) {
            $siswaModel->update($siswa->id, ['is_safety_ready' => 1]);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Materi Safety & K3 berhasil dikonfirmasi. Anda sekarang dapat mengisi presensi dan logbook.'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal mengonfirmasi materi.'
        ]);
    }
}
