<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NilaiModel;
use App\Models\NilaiDetailModel;
use App\Models\PenilaianKonfigurasiModel;
use App\Models\PenilaianAtributModel;
use App\Models\SiswaModel;

class Nilai extends BaseController
{
    protected $nilai;
    protected $nilaiDetail;
    protected $konfigModel;
    protected $atributModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->nilai        = new NilaiModel();
        $this->nilaiDetail  = new NilaiDetailModel();
        $this->konfigModel  = new PenilaianKonfigurasiModel();
        $this->atributModel = new PenilaianAtributModel();
        $this->siswaModel   = new SiswaModel();
    }

    public function store()
    {
        $data = $this->request->getPost();
        $ids = (int) ($data['ids'] ?? 0);
        $idt = (int) ($data['idt'] ?? 0);
        $modeInput = $data['mode_input'] ?? '';
        $metodePenilaian = $data['metode_penilaian'] ?? '';
        $kriteriaInput = $this->request->getPost('kriteria');
        $nilaiAtribut = $this->request->getPost('nilai_atribut');
        $catatan = trim($data['catatan'] ?? '');

        $siswa = $this->siswaModel->find($ids);
        if (!$siswa) {
            return $this->response->setJSON(['status' => 400, 'success' => false, 'message' => 'Data siswa tidak ditemukan.']);
        }

        $id_konfigurasi = (int) ($data['id_konfigurasi'] ?? 0);
        if ($id_konfigurasi <= 0) {
            $konfigAktif = getKonfigurasiPenilaianSiswa($siswa);
            if ($konfigAktif) {
                $id_konfigurasi = (int) $konfigAktif->id;
            }
        }

        $konfig = $id_konfigurasi > 0 ? $this->konfigModel->find($id_konfigurasi) : null;
        $existing = $this->nilai->where('ids', $ids)->first();

        // ----------------------------------------------------
        // Handle Berkas Penilaian (Upload / Replace / Retain)
        // ----------------------------------------------------
        $fileNilai = $this->request->getFile('file_penilaian');
        if (!$fileNilai || !$fileNilai->isValid()) {
            $fileNilai = $this->request->getFile('file_penilaian_direct');
        }
        $uploadedFileName = null;

        if ($fileNilai && $fileNilai->isValid() && !$fileNilai->hasMoved()) {
            $ext = strtolower($fileNilai->getClientExtension());
            $allowedExts = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'];
            if (!in_array($ext, $allowedExts)) {
                return $this->response->setJSON([
                    'status'  => 400,
                    'success' => false,
                    'message' => 'Format berkas penilaian tidak didukung. Harap upload berkas PDF, JPG, PNG, DOC, DOCX, atau Excel.'
                ]);
            }

            if ($fileNilai->getSizeByUnit('mb') > 20) {
                return $this->response->setJSON([
                    'status'  => 400,
                    'success' => false,
                    'message' => 'Ukuran berkas penilaian terlalu besar. Maksimal 20 MB.'
                ]);
            }

            $uploadDir = FCPATH . 'assets/file_penilaian';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }

            $newName = time() . '_' . $fileNilai->getRandomName();
            if ($fileNilai->move($uploadDir, $newName)) {
                $uploadedFileName = $newName;

                // Hapus berkas lama jika sebelumnya ada
                if ($existing && !empty($existing->file_penilaian)) {
                    $oldPath = $uploadDir . '/' . $existing->file_penilaian;
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
            } else {
                return $this->response->setJSON([
                    'status'  => 500,
                    'success' => false,
                    'message' => 'Gagal mengunggah berkas penilaian.'
                ]);
            }
        } else {
            // Jika tidak ada upload file baru, pertahankan berkas lama jika ada
            if ($existing && !empty($existing->file_penilaian)) {
                $uploadedFileName = $existing->file_penilaian;
            }
        }

        // Cek apakah ada skor kriteria yang diisi
        $hasFilledCriteria = false;
        if (!empty($kriteriaInput) && is_array($kriteriaInput)) {
            foreach ($kriteriaInput as $k) {
                if (isset($k['nilai']) && $k['nilai'] !== '' && is_numeric($k['nilai'])) {
                    $hasFilledCriteria = true;
                    break;
                }
            }
        }
        if (!empty($nilaiAtribut) && is_array($nilaiAtribut)) {
            foreach ($nilaiAtribut as $val) {
                if ($val !== '' && is_numeric($val)) {
                    $hasFilledCriteria = true;
                    break;
                }
            }
        }

        if (empty($catatan) && !empty($data['catatan_file'])) {
            $catatan = trim($data['catatan_file']);
        }

        // ----------------------------------------------------
        // KASUS 1: Mode Upload Berkas Penilaian Langsung
        // ----------------------------------------------------
        $isModeFile = ($modeInput === 'file' || $metodePenilaian === 'file' || (!empty($data['is_direct_file']) && $data['is_direct_file'] == '1') || (!$hasFilledCriteria && !empty($uploadedFileName)));

        if ($isModeFile) {
            if (empty($uploadedFileName)) {
                return $this->response->setJSON([
                    'status'  => 400,
                    'success' => false,
                    'message' => 'Harap pilih dan unggah berkas lembar penilaian (PDF / Gambar / Dokumen).'
                ]);
            }

            $nilaiAkhirRaw = $data['nilai_akhir'] ?? $data['total_nilai'] ?? null;
            $totalNilai = null;
            if ($nilaiAkhirRaw !== null && $nilaiAkhirRaw !== '' && is_numeric($nilaiAkhirRaw)) {
                $totalNilai = (float) $nilaiAkhirRaw;
                if ($totalNilai < 0 || $totalNilai > 100) {
                    return $this->response->setJSON([
                        'status'  => 400,
                        'success' => false,
                        'message' => 'Nilai akhir harus berada di antara 0 dan 100.'
                    ]);
                }
            }

            $nilaiData = [
                'ids'              => $ids,
                'idt'              => $idt,
                'id_konfigurasi'   => $konfig ? $konfig->id : ($id_konfigurasi > 0 ? $id_konfigurasi : null),
                'metode_penilaian' => 'file',
                'total_nilai'      => ($totalNilai !== null) ? round($totalNilai, 2) : null,
                'file_penilaian'   => $uploadedFileName,
                'catatan'          => $catatan
            ];

            if ($existing) {
                $this->nilai->update($existing->id, $nilaiData);
                $idNilai = $existing->id;
                $this->nilaiDetail->where('id_nilai', $idNilai)->delete();
            } else {
                $idNilai = $this->nilai->insert($nilaiData);
            }

            // Simpan detail penilaian mewakili berkas lembar penilaian resmi
            $this->nilaiDetail->insert([
                'id_nilai'     => $idNilai,
                'nama_atribut' => 'Berkas Lembar Penilaian Resmi Pembimbing Lapangan',
                'bobot'        => null,
                'urutan'       => 1,
                'nilai'        => 0
            ]);

            $msg = ($totalNilai !== null)
                ? 'Berkas penilaian langsung untuk ' . esc($siswa->nama) . ' berhasil disimpan dengan nilai akhir ' . round($totalNilai, 2)
                : 'Berkas lembar penilaian untuk ' . esc($siswa->nama) . ' berhasil disimpan.';

            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => $msg,
                'redirect'  => base_url('/logbook/' . $idt . '/' . ($siswa->nim ?? ''))
            ]);
        }

        // ----------------------------------------------------
        // KASUS 2: Mode Input Kriteria & Bobot (Online)
        // ----------------------------------------------------
        if ((!empty($kriteriaInput) && is_array($kriteriaInput)) || (!empty($nilaiAtribut) && is_array($nilaiAtribut))) {
            if (empty($metodePenilaian) || $metodePenilaian === 'file') {
                $metodePenilaian = ($konfig && !empty($konfig->metode_penilaian)) ? $konfig->metode_penilaian : 'bobot';
            }

            $items = [];
            $sumWeighted = 0;
            $sumBobot = 0;
            $sumSkor = 0;
            $urutan = 1;

            // 1. Jika ada kriteria dinamis yang diinputkan pembimbing (dari format lembar file instansi)
            if (!empty($kriteriaInput) && is_array($kriteriaInput)) {
                foreach ($kriteriaInput as $k) {
                    $nama = trim($k['nama'] ?? '');
                    if (empty($nama)) continue;

                    if (!isset($k['nilai']) || $k['nilai'] === '') {
                        return $this->response->setJSON(['status' => 400, 'success' => false, 'message' => 'Nilai untuk kriteria "' . $nama . '" wajib diisi.']);
                    }

                    $skor = (float) $k['nilai'];
                    if ($skor < 0 || $skor > 100) {
                        return $this->response->setJSON(['status' => 400, 'success' => false, 'message' => 'Skor untuk kriteria "' . $nama . '" harus berada di antara 0 dan 100.']);
                    }

                    $bobot = isset($k['bobot']) && $k['bobot'] !== '' ? (float) $k['bobot'] : null;

                    $items[] = [
                        'id_atribut'   => isset($k['id_atribut']) ? (int) $k['id_atribut'] : null,
                        'nama_atribut' => $nama,
                        'bobot'        => $bobot,
                        'urutan'       => $urutan++,
                        'nilai'        => $skor
                    ];

                    $sumSkor += $skor;
                    if ($bobot !== null && $bobot > 0) {
                        $sumWeighted += ($skor * $bobot);
                        $sumBobot += $bobot;
                    }
                }
            } 
            // 2. Fallback jika dikirim via nilai_atribut[...]
            else if (!empty($nilaiAtribut) && is_array($nilaiAtribut)) {
                $atributs = $this->atributModel->getByKonfigurasi($konfig->id);
                foreach ($atributs as $attr) {
                    if (!isset($nilaiAtribut[$attr->id]) || $nilaiAtribut[$attr->id] === '') {
                        return $this->response->setJSON(['status' => 400, 'success' => false, 'message' => 'Nilai untuk kriteria "' . $attr->nama_atribut . '" wajib diisi.']);
                    }
                    $skor = (float) $nilaiAtribut[$attr->id];
                    if ($skor < 0 || $skor > 100) {
                        return $this->response->setJSON(['status' => 400, 'success' => false, 'message' => 'Skor untuk kriteria "' . $attr->nama_atribut . '" harus berada di antara 0 dan 100.']);
                    }
                    $items[] = [
                        'id_atribut'   => $attr->id,
                        'nama_atribut' => $attr->nama_atribut,
                        'bobot'        => $attr->bobot,
                        'urutan'       => $attr->urutan,
                        'nilai'        => $skor
                    ];
                    $sumSkor += $skor;
                    if (!empty($attr->bobot) && $attr->bobot > 0) {
                        $sumWeighted += ($skor * $attr->bobot);
                        $sumBobot += $attr->bobot;
                    }
                }
            }

            if (empty($items)) {
                return $this->response->setJSON(['status' => 400, 'success' => false, 'message' => 'Harap isi minimal 1 kriteria penilaian.']);
            }

            if ($sumBobot > 0) {
                $totalNilai = $sumWeighted / $sumBobot;
            } else {
                $totalNilai = count($items) > 0 ? ($sumSkor / count($items)) : 0;
            }

            $nilaiData = [
                'ids'              => $ids,
                'idt'              => $idt,
                'id_konfigurasi'   => $konfig ? $konfig->id : ($id_konfigurasi > 0 ? $id_konfigurasi : null),
                'metode_penilaian' => $metodePenilaian,
                'total_nilai'      => round($totalNilai, 2),
                'file_penilaian'   => $uploadedFileName,
                'catatan'          => $catatan
            ];

            // Cek apakah sudah pernah ada record nilai untuk siswa ini
            if ($existing) {
                $this->nilai->update($existing->id, $nilaiData);
                $idNilai = $existing->id;
                $this->nilaiDetail->where('id_nilai', $idNilai)->delete();
            } else {
                $idNilai = $this->nilai->insert($nilaiData);
            }

            // Simpan detail penilaian per atribut
            foreach ($items as $it) {
                $it['id_nilai'] = $idNilai;
                $this->nilaiDetail->insert($it);
            }

            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Nilai siswa berhasil disimpan dengan nilai akhir ' . round($totalNilai, 2),
                'redirect'  => base_url('/logbook/' . $idt . '/' . ($siswa->nim ?? ''))
            ]);
        }

        // Fallback simpan legacy
        if (!empty($uploadedFileName)) {
            $data['file_penilaian'] = $uploadedFileName;
        }
        if (!empty($catatan)) {
            $data['catatan'] = $catatan;
        }

        if ($this->nilai->save($data)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Nilai siswa berhasil ditambahkan'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Nilai siswa gagal ditambahkan'
            ]);
        }
    }

    public function delete_file($id)
    {
        $id = (int) $id;
        $nilai = $this->nilai->find($id);
        if ($nilai && !empty($nilai->file_penilaian)) {
            $path = FCPATH . 'assets/file_penilaian/' . $nilai->file_penilaian;
            if (file_exists($path)) {
                @unlink($path);
            }
            $this->nilai->update($id, ['file_penilaian' => null]);
            return $this->response->setJSON(['status' => 200, 'success' => true, 'message' => 'Berkas lembar penilaian berhasil dihapus.']);
        }
        return $this->response->setJSON(['status' => 400, 'success' => false, 'message' => 'Berkas penilaian tidak ditemukan.']);
    }

    public function update()
    {
        $data = $this->request->getPost();
        $kriteriaInput = $this->request->getPost('kriteria');
        $nilaiAtribut = $this->request->getPost('nilai_atribut');

        if ((!empty($kriteriaInput) && is_array($kriteriaInput)) || (!empty($nilaiAtribut) && is_array($nilaiAtribut))) {
            return $this->store();
        }

        if ($this->nilai->update($data['id'], $data)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Nilai siswa berhasil diubah'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Nilai siswa gagal diubah'
            ]);
        }
    }

    public function destroy($id)
    {
        $id = (int) $id;
        $this->nilaiDetail->where('id_nilai', $id)->delete();

        if ($this->nilai->delete($id)) {
            return $this->response->setJSON([
                'status'    => 200,
                'success'   => true,
                'message'   => 'Nilai siswa berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 500,
                'success'   => false,
                'message'   => 'Nilai siswa gagal dihapus'
            ]);
        }
    }
}
