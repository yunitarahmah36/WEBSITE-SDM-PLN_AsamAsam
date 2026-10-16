<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<style>
    /* =========================================
       LOGBOOK SISWA - TAMPILAN FINAL
       ========================================= */
    .logbook-item {
        background: #fff;
        border: 1px solid #e9edf3;
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        overflow: hidden;
    }

    .logbook-item:hover {
        border-color: #c5cdd8;
        box-shadow: 0 2px 8px rgba(52, 71, 103, 0.06);
    }

    .logbook-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 14px 16px;
        cursor: pointer;
        background: #fbfcfe;
        border-bottom: 1px solid transparent;
        transition: background 0.2s ease;
    }

    .logbook-header:hover {
        background: #f5f7fa;
    }

    .logbook-item.open .logbook-header {
        background: #f5f7fa;
        border-bottom-color: #e9edf3;
    }

    .logbook-header-left {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
        flex: 1;
    }

    .logbook-tanggal {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .logbook-jam {
        font-size: 12px;
        color: #8392ab;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .logbook-header-right {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .logbook-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .badge-logbook {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 1px solid transparent;
    }

    .badge-hadir { background: #e8f5e9; color: #2e7d32; border-color: #a5d6a7; }
    .badge-izin { background: #fff3e0; color: #e65100; border-color: #ffcc80; }
    .badge-sakit { background: #fce4ec; color: #ad1457; border-color: #f48fb1; }
    .badge-alfa { background: #ffebee; color: #c62828; border-color: #ef9a9a; }
    .badge-pending { background: #fff8e1; color: #b28704; border-color: #ffe082; }
    .badge-approved { background: #e8f5e9; color: #2e7d32; border-color: #a5d6a7; }
    .badge-rejected { background: #ffebee; color: #c62828; border-color: #ef9a9a; }
    .badge-telat { background: #ffebee; color: #c62828; border-color: #ef9a9a; }

    .logbook-body {
        padding: 16px;
        display: none;
        background: #fff;
    }

    .logbook-item.open .logbook-body {
        display: block;
    }

    /* --- DESKRIPSI KEGIATAN --- */
    .kegiatan-table {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e9edf3;
        border-radius: 8px;
        margin-bottom: 14px;
        padding: 14px 16px;
        box-sizing: border-box;
    }

    .kegiatan-deskripsi {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        text-align: left !important;
    }

    .kegiatan-label {
        font-size: 11px;
        font-weight: 700;
        color: #8392ab;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 8px;
        text-align: left !important;
        padding: 0 !important;
        margin-left: 0 !important;
    }

    /* PERBAIKAN: GUNAKAN INLINE-BLOCK AGAR GARIS BIRU RATA DENGAN TEKS */
    .kegiatan-deskripsi-text {
        display: inline-block !important;
        width: auto !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 0 0 12px !important;
        border-left: 3px solid #3b82f6 !important;
        box-sizing: border-box !important;
        font-size: 13px !important;
        line-height: 1.7 !important;
        color: #344767 !important;
        text-align: left !important;
        white-space: pre-wrap !important;
        word-break: break-word !important;
    }
    /* --- END DESKRIPSI KEGIATAN --- */

    .logbook-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 12px;
        border-top: 1px dashed #e9edf3;
    }

    .btn-bukti {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: #e3f2fd;
        color: #1565c0;
        border: 1px solid #90caf9;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-bukti:hover {
        background: #1565c0;
        color: #fff;
        border-color: #1565c0;
        text-decoration: none;
    }

    .btn-action-group {
        display: flex;
        gap: 8px;
    }

    .btn-acc {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: #2e7d32;
        color: #fff;
        border: 1px solid #2e7d32;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .btn-acc:hover:not(:disabled) {
        background: #1b5e20;
        border-color: #1b5e20;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(46, 125, 50, 0.3);
    }

    .btn-acc:disabled {
        background: #c5cdd8;
        border-color: #c5cdd8;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: #fff;
        color: #c62828;
        border: 1px solid #c62828;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .btn-reject:hover {
        background: #c62828;
        color: #fff;
        border-color: #c62828;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(198, 40, 40, 0.3);
    }

    .logbook-empty {
        text-align: center;
        padding: 40px 20px;
        color: #8392ab;
    }

    .logbook-empty i {
        font-size: 48px;
        color: #c5cdd8;
        display: block;
        margin-bottom: 12px;
    }

    @media (max-width: 575px) {
        .logbook-header { padding: 12px; }
        .logbook-actions { flex-direction: column; align-items: stretch; }
        .btn-bukti, .btn-acc, .btn-reject { width: 100%; justify-content: center; }
        .btn-action-group { width: 100%; }
        .btn-action-group > * { flex: 1; }
    }
</style>

<div class="row">
    <div class="col-12 col-md-4 mb-3">
        <div class="card card-body pb-1">
            <a href="<?= base_url('/logbook/' . $idt) ?>" class="d-flex gap-3">
                <i class="fa fa-arrow-left" style="margin-top: 4px;"></i>
                <div><strong>Daftar Siswa Magang</strong></div>
            </a>
            <div class="mt-3">
                <?php foreach ($siswas as $s) : ?>
                    <a href="<?= base_url('/logbook/' . $idt . '/' . $s->nim) ?>" <?= $s->status == 'selesai' ? 'data-bs-toggle="tooltip" title="selesai"' : '' ?>>
                        <div class="card card-body p-2 bg-gray-200 shadow-none rounded px-4 mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="">
                                    <div class="mb-0 <?= $s->status == 'selesai' ? 'text-info' : '' ?>"><b><?= $s->nama ?></b></div>
                                    <span class="text-sm <?= $s->status == 'selesai' ? 'text-info' : '' ?>"><?= $s->nim ?></span>
                                </div>
                                <div>
                                    <div class="text-sm <?= $s->status == 'selesai' ? 'text-info' : '' ?>"><?= $s->kelas ?></div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-8 mb-3">
        <?php
        $status_siswa_terpilih = getStatusSiswa($siswa->id);
        $isSmk = false;
        if (isset($siswa)) {
            $instansiLower = strtolower($siswa->instansi ?? '');
            $kelasLower = strtolower($siswa->kelas ?? '');
            if (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12'])) {
                $isSmk = true;
            }
        }
        ?>
        <?php if ($status_siswa_terpilih == 'selesai' && ($isSmk || $siswa->laporan)) : ?>
            <!-- ============================================== -->
            <!-- LOGIKA BARU: NILAI HANYA BISA DIINPUT SETELAH LAPORAN DISETUJUI -->
            <!-- ============================================== -->
            <div class="card card-body mb-3 bg-info text-white border-info">
                <?php if ($siswa->laporan && !$siswa->is_laporan_approved) : ?>
                    <!-- KONDISI 1: Laporan sudah diunggah TAPI belum disetujui -->
                    Kegiatan magang siswa ini sudah selesai. Silakan periksa laporan akhir terlebih dahulu, kemudian setujui laporan untuk dapat memberikan nilai.
                    <div class="mt-2 mb-0">
                        <a href="/assets/laporan/<?= $siswa->laporan ?>" target="_blank" class="btn btn-sm bg-gradient-success mb-0 me-2">
                            <i class="fa fa-file-pdf"></i> LIHAT LAPORAN
                        </a>
                        <button class="btn btn-sm bg-gradient-info mb-0 btn-report-approve" data-approved="1" data-id="<?= $siswa->id ?>">
                            <i class="fa fa-check-circle"></i> SETUJUI LAPORAN
                        </button>
                    </div>
                    <div class="mt-2 text-sm">
                        <i class="fa fa-info-circle"></i> Tombol <strong>INPUT NILAI</strong> akan muncul setelah laporan disetujui.
                    </div>
                <?php elseif ($siswa->is_laporan_approved) : ?>
                    <!-- KONDISI 2: Laporan sudah disetujui -> boleh input nilai -->
                    Kegiatan magang siswa ini sudah selesai dan laporan telah disetujui. Silakan input nilai.
                    <div class="mt-2 mb-0">
                        <?php if (nilaiAvailable($siswa->id)) : ?>
                            <button class="btn btn-sm bg-gradient-success mb-0 me-2" data-bs-toggle="modal" data-bs-target="#mdnilai">DETAIL NILAI</button>
                            <a href="/assets/laporan/<?= $siswa->laporan ?>" target="_blank" class="btn btn-sm bg-gradient-success mb-0 me-2">LIHAT LAPORAN</a>
                            <button class="btn btn-sm btn-outline-info mb-0 btn-report-approve" data-approved="0" data-id="<?= $siswa->id ?>">BATALKAN PERSETUJUAN LAPORAN</button>
                        <?php else : ?>
                            <button class="btn btn-sm bg-gradient-info mb-0 me-2" data-bs-toggle="modal" data-bs-target="#manilai">INPUT NILAI</button>
                            <a href="/assets/laporan/<?= $siswa->laporan ?>" target="_blank" class="btn btn-sm bg-gradient-success mb-0 me-2">LIHAT LAPORAN</a>
                            <button class="btn btn-sm btn-outline-info mb-0 btn-report-approve" data-approved="0" data-id="<?= $siswa->id ?>">BATALKAN PERSETUJUAN LAPORAN</button>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>

            <?php if (nilaiAvailable($siswa->id)) : ?>
                <?php $nilai = getNilaiSiswa($siswa->id) ?>
                <!-- modal detail nilai -->
                <div class="modal fade" id="mdnilai" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="mdnilai" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="m-0">Capaian Hasil Kegiatan Magang</h6>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Kedisiplinan</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_disiplin ?></h6>
                                    </div>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Kemauan Kerja / Motivasi</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_motivasi ?></h6>
                                    </div>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Kehadiran</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kehadiran ?></h6>
                                    </div>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Inisiatif dan kreatifitas</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kreatifitas ?></h6>
                                    </div>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Kejujuran dan Tanggung Jawab</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kejujuran ?></h6>
                                    </div>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Kesopanan dan Personalia</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kesopanan ?></h6>
                                    </div>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Kerjasama</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kerjasama ?></h6>
                                    </div>
                                    <?php
                                    $includeLaporan = !$isSmk || !empty($siswa->laporan);
                                    if ($includeLaporan) :
                                    ?>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0">Laporan</h6>
                                        <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_laporan ?></h6>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) : ?>
                                    <hr class="dark horizontal my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-normal text-sm mb-0 pb-0 text-info">Nilai Akademik (Dosen)</h6>
                                        <h6 class="mb-0 pb-0 text-sm text-info"><?= $nilai->n_dosen ?></h6>
                                    </div>
                                    <?php endif; ?>

                                    <hr class="dark horizontal mt-3 my-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="font-weight-bold text-lg mb-0 pb-0">Rata-rata</h6>
                                        <h6 class="mb-0 pb-0 text-lg">
                                            <?php
                                                $items_pembimbing = [
                                                    $nilai->n_disiplin, $nilai->n_motivasi, $nilai->n_kehadiran,
                                                    $nilai->n_kreatifitas, $nilai->n_kejujuran, $nilai->n_kesopanan,
                                                    $nilai->n_kerjasama
                                                ];
                                                if ($includeLaporan) {
                                                    $items_pembimbing[] = $nilai->n_laporan;
                                                }

                                                $total_pembimbing = 0;
                                                foreach ($items_pembimbing as $ni) {
                                                    $total_pembimbing += ($ni <= 10) ? ($ni * 10) : $ni;
                                                }

                                                $total_nilai = $total_pembimbing;
                                                $pembagi = $includeLaporan ? 8 : 7;

                                                if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) {
                                                    $total_nilai += $nilai->n_dosen;
                                                    $pembagi++;
                                                }
                                                $rata = $total_nilai / $pembagi;

                                                echo round($rata, 2);
                                            ?>
                                        </h6>
                                    </div>

                                    <div class="mt-4 d-flex gap-2 justify-content-between items-center">
                                        <div>
                                            <button class="badge badge-danger border border-danger btn-hapus-nilai" data-id="<?= $nilai->id ?>"><i class="fa fa-trash text-xxs"></i></button>
                                            <button class="badge badge-info border border-info btn-edit-nilai" data-bs-toggle="modal" data-bs-target="#menilai">EDIT NILAI</button>
                                        </div>
                                        <button type="button" class="badge badge-secondary border border-secondary btn-close-modal" data-bs-dismiss="modal">CLOSE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal detail nilai end -->
                <!-- modal edit nilai -->
                <div class="modal fade" id="menilai" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="menilai" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card card-plain">
                                    <div class="card-header pb-0 text-left">
                                        <h5 class="m-0 p-0">Edit Nilai siswa</h5>
                                        <small class="m-0 p-0">Form ini dipergunakan untuk mengganti nilai siswa yang masih belum sesuai</small>
                                    </div>
                                    <div class="card-body">
                                        <form class="formeditnilai">
                                            <input type="hidden" name="id" value="<?= $nilai->id ?>">
                                            <input type="hidden" name="idt" value="<?= $idt ?>">
                                            <input type="hidden" name="ids" value="<?= $siswa->id ?>">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_disiplin" class="form-label">Kedisiplinan</label>
                                                        <input type="number" class="form-control" name="n_disiplin" value="<?= $nilai->n_disiplin ?>" id="n_disiplin" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_motivasi" class="form-label">Kemauan Kerja / Motivasi</label>
                                                        <input type="number" class="form-control" name="n_motivasi" value="<?= $nilai->n_motivasi ?>" id="n_motivasi" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kehadiran" class="form-label">Kehadiran</label>
                                                        <input type="number" class="form-control" name="n_kehadiran" value="<?= $nilai->n_kehadiran ?>" id="n_kehadiran" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kreatifitas" class="form-label">Inisiatif dan kreatifitas</label>
                                                        <input type="number" class="form-control" name="n_kreatifitas" value="<?= $nilai->n_kreatifitas ?>" id="n_kreatifitas" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kejujuran" class="form-label">Kejujuran dan Tanggung Jawab</label>
                                                        <input type="number" class="form-control" name="n_kejujuran" value="<?= $nilai->n_kejujuran ?>" id="n_kejujuran" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kesopanan" class="form-label">Kesopanan dan Personalia</label>
                                                        <input type="number" class="form-control" name="n_kesopanan" value="<?= $nilai->n_kesopanan ?>" id="n_kesopanan" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kerjasama" class="form-label">Kerjasama</label>
                                                        <input type="number" class="form-control" name="n_kerjasama" value="<?= $nilai->n_kerjasama ?>" id="n_kerjasama" min="0" max="100">
                                                    </div>
                                                </div>
                                                <?php
                                                 $includeLaporan = !$isSmk || !empty($siswa->laporan);
                                                 if ($includeLaporan) :
                                                 ?>
                                                 <div class="col-12 col-md-6">
                                                     <div class="input-group input-group-outline mb-3">
                                                         <label for="n_laporan" class="form-label">Nilai Laporan</label>
                                                         <input type="number" class="form-control" name="n_laporan" value="<?= $nilai->n_laporan ?>" id="n_laporan" min="0" max="100">
                                                     </div>
                                                 </div>
                                                 <?php else : ?>
                                                     <input type="hidden" name="n_laporan" value="0">
                                                 <?php endif; ?>
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-sm bg-gradient-secondary shadow mb-0 btn-close-modal" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-sm bg-gradient-info shadow mb-0">SIMPAN</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal edit nilai end -->
            <?php else : ?>
                <!-- modal tambah nilai -->
                <div class="modal fade" id="manilai" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="manilai" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card card-plain">
                                    <div class="card-header pb-0 text-left">
                                        <h5 class="m-0 p-0">Tambah Nilai siswa</h5>
                                        <small class="m-0 p-0">Nilai yang ditambahkan sesuai dengan hasil yang diberikan oleh perusahaan / instansi tempat siswa magang</small>
                                    </div>
                                    <div class="card-body">
                                        <form class="formaddnilai">
                                            <input type="hidden" name="idt" value="<?= $idt ?>">
                                            <input type="hidden" name="ids" value="<?= $siswa->id ?>">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_disiplin" class="form-label">Kedisiplinan</label>
                                                        <input type="number" class="form-control" name="n_disiplin" id="n_disiplin" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_motivasi" class="form-label">Kemauan Kerja / Motivasi</label>
                                                        <input type="number" class="form-control" name="n_motivasi" id="n_motivasi" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kehadiran" class="form-label">Kehadiran</label>
                                                        <input type="number" class="form-control" name="n_kehadiran" id="n_kehadiran" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kreatifitas" class="form-label">Inisiatif dan kreatifitas</label>
                                                        <input type="number" class="form-control" name="n_kreatifitas" id="n_kreatifitas" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kejujuran" class="form-label">Kejujuran dan Tanggung Jawab</label>
                                                        <input type="number" class="form-control" name="n_kejujuran" id="n_kejujuran" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kesopanan" class="form-label">Kesopanan dan Personalia</label>
                                                        <input type="number" class="form-control" name="n_kesopanan" id="n_kesopanan" min="0" max="100">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_kerjasama" class="form-label">Kerjasama</label>
                                                        <input type="number" class="form-control" name="n_kerjasama" id="n_kerjasama" min="0" max="100">
                                                    </div>
                                                </div>
                                                <?php
                                                $includeLaporan = !$isSmk || !empty($siswa->laporan);
                                                if ($includeLaporan) :
                                                ?>
                                                <div class="col-12 col-md-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label for="n_laporan" class="form-label">Nilai Laporan</label>
                                                        <input type="number" class="form-control" name="n_laporan" id="n_laporan" min="0" max="100">
                                                    </div>
                                                </div>
                                                <?php else : ?>
                                                    <input type="hidden" name="n_laporan" value="0">
                                                <?php endif; ?>
                                            </div>

                                            <div class="mt-3">
                                                <button type="button" class="btn btn-sm bg-gradient-secondary shadow mb-0 btn-close-modal" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-sm bg-gradient-info shadow mb-0">SIMPAN</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal tambah nilai end -->
            <?php endif ?>
        <?php else : ?>
            <?php if (!$isSmk && ($status_siswa_terpilih == 'selesai' || date("Y-m-d") > $siswa->tgl_selesai)) : ?>
                <div class="card card-body mb-3 bg-warning text-dark border-warning">
                    Siswa ini belum mengunggah laporan akhir magang. nilai tidak dapat diinputkan sebelum siswa mengunggah laporan akhir magang.
                </div>
            <?php endif ?>
        <?php endif ?>

        <div class="card card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-dark"><strong>Daftar Kegiatan Siswa</strong></div>
                <button type="button" class="btn btn-sm btn-outline-info mb-0" data-bs-toggle="modal" data-bs-target="#mvsiswa">LIHAT PROFIL</button>
            </div>
            <div class="text-dark">
                <?php if (count($logbooks) > 0) : ?>
                    <?php foreach ($logbooks as $idx => $log) : ?>
                        <?php
                        // Tentukan class badge keterangan
                        $keteranganClass = 'badge-hadir';
                        $keteranganLower = strtolower($log->keterangan ?? '');
                        if ($keteranganLower == 'izin') $keteranganClass = 'badge-izin';
                        elseif ($keteranganLower == 'sakit') $keteranganClass = 'badge-sakit';
                        elseif ($keteranganLower == 'alfa') $keteranganClass = 'badge-alfa';
                        ?>
                        <div class="logbook-item" data-id="<?= $log->id ?>">
                            <!-- HEADER -->
                            <div class="logbook-header" data-toggle-logbook="<?= $log->id ?>">
                                <div class="logbook-header-left">
                                    <div class="logbook-tanggal">
                                        <i class="material-icons" style="font-size: 16px; color: #8392ab;">event</i>
                                        <?= date('d M Y', strtotime($log->tanggal)) ?>
                                        <?php if ($log->telat) : ?>
                                            <span class="badge-logbook badge-telat"><i class="fa fa-clock"></i> Telat Diisi</span>
                                        <?php endif ?>
                                    </div>
                                    <div class="logbook-jam">
                                        <i class="material-icons" style="font-size: 14px;">schedule</i>
                                        <?= $log->jam_masuk ?> - <?= $log->jam_keluar ?>
                                    </div>
                                </div>

                                <div class="logbook-header-right">
                                    <div class="logbook-badges">
                                        <span class="badge-logbook <?= $keteranganClass ?>"><?= strtoupper($log->keterangan) ?></span>
                                        <?php if ($log->status == 'approved') : ?>
                                            <span class="badge-logbook badge-approved"><i class="fa fa-check-circle"></i> Approved</span>
                                        <?php elseif ($log->status == 'rejected') : ?>
                                            <span class="badge-logbook badge-rejected"><i class="fa fa-times-circle"></i> Rejected</span>
                                        <?php else : ?>
                                            <span class="badge-logbook badge-pending"><i class="fa fa-hourglass-half"></i> Pending</span>
                                        <?php endif ?>
                                    </div>
                                    <i class="material-icons expand-icon" style="color: #8392ab; transition: transform 0.2s;">expand_more</i>
                                </div>
                            </div>

                            <!-- BODY -->
                            <div class="logbook-body">
                                <!-- DESKRIPSI KEGIATAN -->
                                <div class="kegiatan-table">
                                    <div class="kegiatan-deskripsi">
                                        <div class="kegiatan-label">DESKRIPSI KEGIATAN:</div>
                                        <div class="kegiatan-deskripsi-text"><?= nl2br(htmlspecialchars($log->kegiatan ?? '-')) ?></div>
                                    </div>
                                </div>
                                <!-- END DESKRIPSI KEGIATAN -->

                                <div class="logbook-actions">
                                    <!-- Lihat Bukti -->
                                    <?php if (!empty($log->bukti)) : ?>
                                        <a href="<?= base_url('/assets/img/logbook/' . $log->bukti) ?>" target="_blank" class="btn-bukti">
                                            <i class="material-icons" style="font-size: 16px;">image</i>
                                            Lihat Bukti Kegiatan
                                        </a>
                                    <?php else : ?>
                                        <span style="font-size: 12px; color: #8392ab;">
                                            <i class="material-icons" style="font-size: 14px; vertical-align: middle;">image_not_supported</i>
                                            Tidak ada bukti
                                        </span>
                                    <?php endif ?>

                                    <!-- Tombol ACC & Tolak -->
                                    <div class="btn-action-group">
                                        <button class="btn-reject btn-reject-logbook" data-id="<?= $log->id ?>">
                                            <i class="material-icons" style="font-size: 16px;">close</i>
                                            Tolak
                                        </button>
                                        <button class="btn-acc btn-approve-logbook"
                                            data-id="<?= $log->id ?>"
                                            <?= $log->status == 'approved' ? 'disabled' : '' ?>>
                                            <i class="material-icons" style="font-size: 16px;">check</i>
                                            <?= $log->status == 'approved' ? 'Terverifikasi' : 'ACC' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else : ?>
                    <div class="logbook-empty">
                        <i class="material-icons">event_busy</i>
                        <div style="font-weight: 600; margin-bottom: 4px;">Belum Ada Logbook</div>
                        <div style="font-size: 13px;">Siswa ini belum mengisi logbook kegiatan apapun.</div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal View Siswa -->
<div class="modal fade" id="mvsiswa" tabindex="-1" aria-labelledby="mvsiswa" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profil Mahasiswa</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php if ($siswa->foto && $siswa->foto != 'default.jpg' && $siswa->foto != '') : ?>
                    <div class="col-12 text-center mb-3">
                        <div class="avatar avatar-xxl position-relative">
                            <img src="<?= base_url('assets/img/foto_siswa/' . $siswa->foto) ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm" style="object-fit: cover; height: 120px;">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-12">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-sm font-weight-bold" width="30%"><?= getIdentityLabel($siswa) ?></td>
                                <td class="text-sm">: <?= $siswa->nim ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Nama</td>
                                <td class="text-sm">: <?= $siswa->nama ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Email</td>
                                <td class="text-sm">: <?= $siswa->email ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">No. HP</td>
                                <td class="text-sm">: <?= $siswa->no_hp ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jumlah</td>
                                <td class="text-sm">: <?= $siswa->kelas ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jurusan</td>
                                <td class="text-sm">: <?= $siswa->jurusan ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Instansi</td>
                                <td class="text-sm">: <?= $breadcrumb[2] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Angkatan</td>
                                <td class="text-sm">: <?= $siswa->angkatan ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Alamat</td>
                                <td class="text-sm" style="white-space: normal;">: <?= $siswa->alamat ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">NIK</td>
                                <td class="text-sm">: <?= $siswa->nik ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Tempat, Tanggal Lahir</td>
                                <td class="text-sm">: <?= $siswa->tempat_lahir ?? '-' ?>, <?= $siswa->tanggal_lahir ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jenis Kelamin</td>
                                <td class="text-sm">: <?= $siswa->jenis_kelamin ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">No. HP Emergency</td>
                                <td class="text-sm">: <?= $siswa->no_hp_emergency ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr class="my-2"></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File KTP</td>
                                <td class="text-sm">: <?= $siswa->ktp ? '<a href="'.base_url('assets/berkas/'.$siswa->ktp).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File CV</td>
                                <td class="text-sm">: <?= $siswa->cv ? '<a href="'.base_url('assets/berkas/'.$siswa->cv).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Transkrip Nilai / Lainnya</td>
                                <td class="text-sm">: <?= $siswa->bpjs ? '<a href="'.base_url('assets/berkas/'.$siswa->bpjs).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File KTM</td>
                                <td class="text-sm">: <?= $siswa->ktm ? '<a href="'.base_url('assets/berkas/'.$siswa->ktm).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File Takaful</td>
                                <td class="text-sm">: <?= $siswa->takaful ? '<a href="'.base_url('assets/berkas/'.$siswa->takaful).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Permohonan</td>
                                <td class="text-sm">: <?= $siswa->surat_permohonan ? '<a href="'.base_url('assets/berkas/'.$siswa->surat_permohonan).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Pernyataan Magang</td>
                                <td class="text-sm">: <?= $siswa->surat_pernyataan ? '<a href="'.base_url('assets/berkas/'.$siswa->surat_pernyataan).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Balasan Magang</td>
                                <td class="text-sm">: <?= $siswa->surat_balasan ? '<a href="'.base_url('assets/berkas/'.$siswa->surat_balasan).'" target="_blank" class="badge bg-gradient-success text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Add is-filled to all inputs that have a value
    $('.form-control').each(function() {
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        }
    });

    $('.form-control').on('focus', function() {
        $(this).parent().addClass('is-focused');
    }).on('blur', function() {
        $(this).parent().removeClass('is-focused');
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        } else {
            $(this).parent().removeClass('is-filled');
        }
    }).on('change keyup input', function() {
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        } else {
            $(this).parent().removeClass('is-filled');
        }
    });

    // =========================================
    // BUKA/TUTUP LOGBOOK
    // =========================================
    $('.logbook-header').on('click', function() {
        const item = $(this).closest('.logbook-item');
        const isOpen = item.hasClass('open');

        // Tutup semua
        $('.logbook-item').removeClass('open');
        $('.logbook-header .expand-icon').css('transform', 'rotate(0deg)');

        // Buka yang diklik (kalau sebelumnya tertutup)
        if (!isOpen) {
            item.addClass('open');
            $(this).find('.expand-icon').css('transform', 'rotate(180deg)');
        }
    });

    // =========================================
    // ACC LOGBOOK
    // =========================================
    $('.btn-approve-logbook').on('click', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');

        Swal.fire({
            title: "Approve Log Book",
            text: "Apakah anda yakin ingin menyetujui log book ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("/logbook/status/update") ?>',
                    type: 'POST',
                    data: { id: id, status: 'approved' },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Log Book berhasil disetujui",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => { location.reload(); });
                        } else {
                            Swal.fire("Gagal!", res.message || "Log Book gagal disetujui", "error");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire("Gagal!", "Terjadi kesalahan server", "error");
                    }
                });
            }
        });
    });

    // =========================================
    // TOLAK LOGBOOK
    // =========================================
    $('.btn-reject-logbook').on('click', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');

        Swal.fire({
            title: "Reject Log Book",
            text: "Apakah anda yakin ingin menolak log book ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("/logbook/status/update") ?>',
                    type: 'POST',
                    data: { id: id, status: 'rejected' },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Log Book berhasil ditolak",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => { location.reload(); });
                        } else {
                            Swal.fire("Gagal!", res.message || "Log Book gagal ditolak", "error");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire("Gagal!", "Terjadi kesalahan server", "error");
                    }
                });
            }
        });
    });

    // =========================================
    // APPROVE LAPORAN
    // =========================================
    $('.btn-report-approve').on('click', function() {
        const id = $(this).data('id');
        const approved = $(this).data('approved');
        const title = approved == '1' ? 'Setujui Laporan?' : 'Batalkan Persetujuan?';
        const text = approved == '1' ? 'Laporan ini akan ditandai sebagai disetujui.' : 'Status persetujuan laporan ini akan dihapus.';

        Swal.fire({
            title: title,
            text: text,
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Lanjutkan"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/siswa/approve_laporan",
                    type: "POST",
                    data: { id: id, approved: approved },
                    dataType: "JSON",
                    success: function(res) {
                        if (res.success) {
                            Swal.fire("Berhasil!", res.message, "success").then(() => location.reload());
                        } else {
                            Swal.fire("Gagal!", res.message, "error");
                        }
                    }
                });
            }
        });
    });
});
</script>

<?php if ($status_siswa_terpilih == 'selesai') : ?>
    <script>
        $(document).ready(function() {
            $('.formaddnilai').submit(function(e) {
                e.preventDefault();
                var data = new FormData(this);
                $.ajax({
                    cache: false, processData: false, contentType: false,
                    type: "POST", url: '/nilai/store', data: data, dataType: "JSON",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 1500 }).then(function() { window.location.reload(); });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: response.message, showConfirmButton: false, timer: 1500 });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseText, showConfirmButton: false, timer: 1500 });
                    }
                });
            });
        });
    </script>
<?php endif; ?>

<?php if (nilaiAvailable($siswa->id)) : ?>
    <script>
        $(document).ready(function() {
            $(".btn-edit-nilai").click(function() { $("#menilai").modal('show'); });

            $('.btn-hapus-nilai').on('click', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Nilai akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE", url: "/nilai/destroy/" + id, dataType: "JSON",
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 1500 }).then(function() { window.location.reload(); });
                                } else {
                                    Swal.fire({ icon: 'error', title: 'Gagal', text: response.message, showConfirmButton: false, timer: 1500 });
                                }
                            }
                        });
                    }
                });
            });

            $('.formeditnilai').submit(function(e) {
                e.preventDefault();
                var data = new FormData(this);
                $.ajax({
                    cache: false, processData: false, contentType: false,
                    type: "POST", url: '/nilai/update', data: data, dataType: "JSON",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 1500 }).then(function() { window.location.reload(); });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: response.message, showConfirmButton: false, timer: 1500 });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseText, showConfirmButton: false, timer: 1500 });
                    }
                });
            });
        });
    </script>
<?php endif; ?>
<?= $this->endSection(); ?>