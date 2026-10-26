<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Pengumuman extends BaseController
{
    protected $pengumuman;

    public function __construct()
    {
        $this->pengumuman = new \App\Models\PengumumanModel();
    }

    public function index()
    {
        $db = $this->pengumuman->select("pengumuman.*, CASE WHEN auth_groups.name = 'admin' THEN 'Admin' ELSE COALESCE(pembimbing.nama, instansi.nama_instansi, users.username) END as pembimbing")
            ->join('users', 'users.id = pengumuman.oleh')
            ->join('pembimbing', 'pembimbing.user_id = users.id', 'left')
            ->join('instansi', 'instansi.user_id = users.id', 'left')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->orderBy('pengumuman.created_at', "DESC")
            ->groupBy('pengumuman.id');

        // Filter by target_role — admin sees all, others see only their group or 'semua'
        if (!in_groups('admin')) {
            if (in_groups('pembimbing')) {
                $db->groupStart()
                   ->where('pengumuman.target_role', 'semua')
                   ->orWhere('pengumuman.target_role', 'pembimbing')
                   ->orWhere('pengumuman.target_role', null)
                   ->orWhere('pengumuman.target_role', '')
                   ->groupEnd();
            } elseif (in_groups('instansi')) {
                $db->groupStart()
                   ->where('pengumuman.target_role', 'semua')
                   ->orWhere('pengumuman.target_role', 'instansi')
                   ->orWhere('pengumuman.target_role', null)
                   ->orWhere('pengumuman.target_role', '')
                   ->groupEnd();
            } else {
                // siswa
                $db->groupStart()
                   ->where('pengumuman.target_role', 'semua')
                   ->orWhere('pengumuman.target_role', 'siswa')
                   ->orWhere('pengumuman.target_role', null)
                   ->orWhere('pengumuman.target_role', '')
                   ->groupEnd();
            }
        }

        $pengumuman = $db->findAll();

        return view('pengumuman', [
            "title"         => "Magang | Dashboard",
            "page_title"    => in_groups('admin') ? 'Dashboard Admin' : (in_groups('pembimbing') ? 'Dashboard Pembimbing' : (in_groups('instansi') ? 'Dashboard Instansi' : 'Dashboard Siswa')),
            "segment"       => $this->request->getUri()->getSegments(),
            "breadcrumb"    => in_groups('admin') ? ['Dashboard', 'Admin'] : (in_groups('pembimbing') ? ['Dashboard', 'Pembimbing'] : (in_groups('instansi') ? ['Dashboard', 'Instansi'] : ['Dashboard', user()->username])),
            "pengumuman"    => $pengumuman
        ]);
    }

    public function get($id)
    {
        $pengumuman = $this->pengumuman->select("pengumuman.*, CASE WHEN auth_groups.name = 'admin' THEN 'Admin' ELSE COALESCE(pembimbing.nama, instansi.nama_instansi, users.username) END as pembimbing")
            ->join('users', 'users.id = pengumuman.oleh')
            ->join('pembimbing', 'pembimbing.user_id = users.id', 'left')
            ->join('instansi', 'instansi.user_id = users.id', 'left')
            ->join('auth_groups_users', 'auth_groups_users.user_id = users.id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->where('pengumuman.id', $id)
            ->groupBy('pengumuman.id')
            ->first();

        return $this->response->setJSON($pengumuman);
    }

    public function store()
    {
        $lampiran = $this->request->getFile('file');
        
        $data = [
            'judul'       => $this->request->getPost('judul'),
            'isi'         => $this->request->getPost('keterangan'),
            'oleh'        => $this->request->getPost('oid'),
            'target_role' => $this->request->getPost('target_role') ?? 'semua',
        ];

        if ($lampiran !== null) {
            if($lampiran->isValid()){
                $lampiran_name = $lampiran->getRandomName();
                $data['lampiran'] = $lampiran_name;
            }
        }
        
        if ($this->pengumuman->save($data)) {
            if ($lampiran !== null) {
                if($lampiran->isValid()){
                    $lampiran->move(FCPATH . 'assets/lampiran', $lampiran_name);
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'status' => 200,
                'message' => 'Berhasil menambahkan pengumuman'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'status' => 500,
                'message' => $this->pengumuman->errors()
            ]);
        }
    }

    public function destroy($id = null)
    {
        // if id not null
        if ($id !== null) {
            $pengumuman = $this->pengumuman->find($id);

            if ($pengumuman) {
                if ($this->pengumuman->delete($id)) {
                    return $this->response->setJSON([
                        'success' => true,
                        'status' => 200,
                        'message' => 'Berhasil menghapus pengumuman'
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'status' => 500,
                        'message' => $this->pengumuman->errors()
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'status' => 404,
                    'message' => 'Pengumuman tidak ditemukan'
                ]);
            }
        }
    }

    public function update($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON([
                'success' => false,
                'status' => 400,
                'message' => 'ID tidak valid'
            ]);
        }

        $pengumuman = $this->pengumuman->find($id);
        if (!$pengumuman) {
            return $this->response->setJSON([
                'success' => false,
                'status' => 404,
                'message' => 'Pengumuman tidak ditemukan'
            ]);
        }

        $lampiran = $this->request->getFile('file');
        
        $data = [
            'id'          => $id,
            'judul'       => $this->request->getPost('judul'),
            'isi'         => $this->request->getPost('keterangan'),
            'target_role' => $this->request->getPost('target_role') ?? $pengumuman->target_role,
        ];

        if ($lampiran !== null && $lampiran->isValid()) {
            $lampiran_name = $lampiran->getRandomName();
            $data['lampiran'] = $lampiran_name;
        }
        
        if ($this->pengumuman->save($data)) {
            if ($lampiran !== null && $lampiran->isValid()) {
                $lampiran->move(FCPATH . 'assets/lampiran', $lampiran_name);
            }

            return $this->response->setJSON([
                'success' => true,
                'status' => 200,
                'message' => 'Berhasil memperbarui pengumuman'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'status' => 500,
                'message' => $this->pengumuman->errors()
            ]);
        }
    }
}
