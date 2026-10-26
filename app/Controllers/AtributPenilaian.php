<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DosenPembimbingModel;
use App\Models\PenilaianKonfigurasiModel;
use App\Models\PenilaianAtributModel;

class AtributPenilaian extends BaseController
{
    protected $instansiModel;
    protected $konfigModel;
    protected $atributModel;

    public function __construct()
    {
        $this->instansiModel = new DosenPembimbingModel(); // table: instansi
        $this->konfigModel   = new PenilaianKonfigurasiModel();
        $this->atributModel  = new PenilaianAtributModel();
    }

    /**
     * Helper untuk mendapatkan instansi aktif berdasarkan user yang login atau filter admin.
     */
    protected function getActiveInstansi()
    {
        if (in_groups('admin')) {
            $id_instansi = $this->request->getGet('id_instansi') ?: $this->request->getPost('id_instansi');
            if ($id_instansi) {
                return $this->instansiModel->find($id_instansi);
            }
            return $this->instansiModel->orderBy('id', 'ASC')->first();
        }

        // Untuk role instansi
        return $this->instansiModel->where('user_id', user_id())->first();
    }

    public function index()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return redirect()->to('/dashboard')->with('error', 'Profil data instansi tidak ditemukan.');
        }

        $allInstansi = in_groups('admin') ? $this->instansiModel->findAll() : [];

        $konfig = $this->konfigModel->getOrInit($instansi->id);
        $atributs = $this->atributModel->getByKonfigurasi($konfig->id);

        $hasUsedAttribute = false;
        foreach ($atributs as $a) {
            $a->is_used = $this->atributModel->isUsedInAssessment($a->id);
            if ($a->is_used) {
                $hasUsedAttribute = true;
            }
        }

        $totalBobot = $this->atributModel->getTotalBobot($konfig->id);

        return view('instansi/atribut_penilaian', [
            'title'            => 'Magang | Atribut Penilaian Instansi',
            'page_title'       => 'Pengelolaan Atribut Penilaian Peserta PKL',
            'segment'          => $this->request->getUri()->getSegments(),
            'breadcrumb'       => ['Instansi', 'Atribut Penilaian'],
            'instansi'         => $instansi,
            'allInstansi'      => $allInstansi,
            'konfig'           => $konfig,
            'atributs'         => $atributs,
            'totalBobot'       => $totalBobot,
            'hasUsedAttribute' => $hasUsedAttribute
        ]);
    }

    public function simpanMetode()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $konfig = $this->konfigModel->getOrInit($instansi->id);
        $metode = $this->request->getPost('metode_penilaian');
        $catatan = $this->request->getPost('catatan');

        if (!in_array($metode, ['rata_rata', 'bobot'])) {
            $metode = 'rata_rata';
        }

        $this->konfigModel->update($konfig->id, [
            'metode_penilaian' => $metode,
            'catatan'          => $catatan
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pengaturan metode penilaian berhasil disimpan.'
        ]);
    }

    public function tambahAtribut()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $konfig = $this->konfigModel->getOrInit($instansi->id);
        $namaAtribut = trim((string) $this->request->getPost('nama_atribut'));
        $bobot = (float) $this->request->getPost('bobot');
        $urutan = (int) $this->request->getPost('urutan');

        if (empty($namaAtribut)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nama atribut penilaian wajib diisi.']);
        }

        if ($urutan <= 0) {
            $last = $this->atributModel->where('id_konfigurasi', $konfig->id)->orderBy('urutan', 'DESC')->first();
            $urutan = $last ? ($last->urutan + 1) : 1;
        }

        $this->atributModel->insert([
            'id_konfigurasi' => $konfig->id,
            'id_instansi'    => $instansi->id,
            'nama_atribut'   => $namaAtribut,
            'bobot'          => $bobot,
            'urutan'         => $urutan
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Atribut penilaian berhasil ditambahkan.'
        ]);
    }

    public function updateAtribut()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $id = (int) $this->request->getPost('id');
        $atribut = $this->atributModel->find($id);

        if (!$atribut || $atribut->id_instansi != $instansi->id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Atribut tidak ditemukan atau bukan milik instansi Anda.']);
        }

        // Cek jika sudah pernah digunakan dalam penilaian
        $isUsed = $this->atributModel->isUsedInAssessment($id);
        if ($isUsed) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Atribut ini sudah digunakan dalam penilaian peserta PKL dan tidak dapat diubah agar tidak mengubah riwayat penilaian.'
            ]);
        }

        $namaAtribut = trim((string) $this->request->getPost('nama_atribut'));
        $bobot = (float) $this->request->getPost('bobot');
        $urutan = (int) $this->request->getPost('urutan');

        if (empty($namaAtribut)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nama atribut penilaian wajib diisi.']);
        }

        $this->atributModel->update($id, [
            'nama_atribut' => $namaAtribut,
            'bobot'        => $bobot,
            'urutan'       => $urutan
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Atribut penilaian berhasil diperbarui.'
        ]);
    }

    public function hapusAtribut($id)
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $id = (int) $id;
        $atribut = $this->atributModel->find($id);

        if (!$atribut || $atribut->id_instansi != $instansi->id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Atribut tidak ditemukan.']);
        }

        // Cek jika sudah pernah digunakan dalam penilaian
        $isUsed = $this->atributModel->isUsedInAssessment($id);
        if ($isUsed) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Atribut ini sudah digunakan dalam penilaian peserta PKL dan tidak dapat dihapus.'
            ]);
        }

        $this->atributModel->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Atribut penilaian berhasil dihapus.'
        ]);
    }

    public function uploadFileFormat()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $file = $this->request->getFile('file_format');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'File tidak valid atau belum dipilih.']);
        }

        $allowedExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
        $ext = strtolower($file->getExtension());
        if (!in_array($ext, $allowedExt)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Format file tidak didukung. Harap unggah file PDF, Word, Excel, atau Gambar.'
            ]);
        }

        if ($file->getSizeByUnit('mb') > 15) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ukuran file maksimal 15MB.']);
        }

        $konfig = $this->konfigModel->getOrInit($instansi->id);

        if (!empty($konfig->file_format)) {
            $oldPath = FCPATH . 'assets/format_penilaian/' . $konfig->file_format;
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }

        $namaAsli = $file->getClientName();
        $randomName = $file->getRandomName();
        $targetDir = FCPATH . 'assets/format_penilaian';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        $file->move($targetDir, $randomName);

        $updateData = [
            'file_format'    => $randomName,
            'nama_file_asli' => $namaAsli,
        ];

        $langsungKirim = $this->request->getPost('langsung_kirim');
        if ($langsungKirim == '1') {
            $updateData['status'] = 'terkirim';
            $updateData['tgl_kirim'] = date('Y-m-d H:i:s');
        }

        $this->konfigModel->update($konfig->id, $updateData);

        return $this->response->setJSON([
            'success'        => true,
            'message'        => 'File format penilaian berhasil diunggah' . ($langsungKirim == '1' ? ' dan langsung dikirimkan ke pembimbing.' : '.'),
            'file_name'      => $randomName,
            'nama_file_asli' => $namaAsli,
            'url'            => base_url('assets/format_penilaian/' . $randomName)
        ]);
    }

    public function hapusFileFormat()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $konfig = $this->konfigModel->getOrInit($instansi->id);
        if (!empty($konfig->file_format)) {
            $filePath = FCPATH . 'assets/format_penilaian/' . $konfig->file_format;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $this->konfigModel->update($konfig->id, [
            'file_format'    => null,
            'nama_file_asli' => null
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'File format penilaian berhasil dihapus.'
        ]);
    }

    public function kirimAtribut()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $konfig = $this->konfigModel->getOrInit($instansi->id);
        $atributs = $this->atributModel->getByKonfigurasi($konfig->id);

        if (empty($konfig->file_format) && empty($atributs)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Harap unggah file lembar format penilaian terlebih dahulu sebelum mengirimkan ke pembimbing.'
            ]);
        }

        // Jika metode bobot dan ada atribut, validasi total bobot = 100%
        if (!empty($atributs) && $konfig->metode_penilaian === 'bobot') {
            $totalBobot = $this->atributModel->getTotalBobot($konfig->id);
            if (abs($totalBobot - 100.00) > 0.01) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Total bobot semua atribut harus tepat 100% (saat ini: ' . round($totalBobot, 2) . '%). Silakan sesuaikan bobot setiap atribut terlebih dahulu.'
                ]);
            }
        }

        $this->konfigModel->update($konfig->id, [
            'status'    => 'terkirim',
            'tgl_kirim' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Format penilaian berhasil dikirimkan ke pembimbing PKL di lapangan!'
        ]);
    }

    public function batalKirim()
    {
        $instansi = $this->getActiveInstansi();
        if (!$instansi) {
            return $this->response->setJSON(['success' => false, 'message' => 'Instansi tidak ditemukan.']);
        }

        $konfig = $this->konfigModel->getOrInit($instansi->id);

        $this->konfigModel->update($konfig->id, [
            'status' => 'draft'
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status atribut penilaian berhasil dikembalikan menjadi draf.'
        ]);
    }
}
