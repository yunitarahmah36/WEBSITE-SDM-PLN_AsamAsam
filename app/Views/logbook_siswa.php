<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
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
            <div class="card card-body mb-3 bg-info text-white border-info">
                Kegiatan magang siswa ini sudah selesai. diharapkan untuk segera memproses nilai.
                <div class="mt-2 mb-0">
                    <?php if (nilaiAvailable($siswa->id)) : ?>
                        <button class="btn btn-sm bg-gradient-success mb-0" data-bs-toggle="modal" data-bs-target="#mdnilai">DETAIL NILAI</button>
                    <?php else : ?>
                        <button class="btn btn-sm bg-gradient-info mb-0 me-2" data-bs-toggle="modal" data-bs-target="#manilai">INPUT NILAI</button>
                        <?php if ($siswa->laporan) : ?>
                            <a href="/assets/laporan/<?= $siswa->laporan ?>" target="_blank" class="btn btn-sm bg-gradient-success mb-0 me-2">LIHAT LAPORAN</a>
                            <?php if ($siswa->is_laporan_approved) : ?>
                                <button class="btn btn-sm btn-outline-info mb-0 btn-report-approve" data-approved="0" data-id="<?= $siswa->id ?>">BATALKAN PERSETUJUAN LAPORAN</button>
                            <?php else : ?>
                                <button class="btn btn-sm bg-gradient-info mb-0 btn-report-approve" data-approved="1" data-id="<?= $siswa->id ?>">SETUJUI LAPORAN</button>
                            <?php endif; ?>
                        <?php endif ?>
                    <?php endif ?>
                </div>
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
                                                // rata-rata nilai: Normalisasi nilai pembimbing (skala 10) ke skala 100
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

                                                // echo nilai
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
                <?php $clog = count($logbooks);
                $i = 1; ?>
                <div class="accordion accLog" id="logAccordion">
                    <?php foreach ($logbooks as $idx => $log) : ?>
                        <div class="accordion-item mb-2 text-dark">
                            <div class="accordion-header" id="hla<?= $log->id ?>">
                                <div class="card card-body p-2 bg-gray-200 shadow-none rounded px-4 mb-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#la<?= $log->id ?>" aria-expanded="false" aria-controls="la<?= $log->id ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="">
                                            <div class="mb-0"><b><?= $log->tanggal ?></b></div>
                                            <div class="text-xs"><?= genBadgeKehadiran($log->keterangan) ?></div>
                                        </div>
                                        <div class="d-flex flex-column align-items-end">
                                            <div class="text-xs mb-1 d-flex gap-2 flex-row">
                                                <?php if ($log->telat) : ?>
                                                    <span class="badge badge-danger border border-danger">Telat diisi</span>
                                                <?php endif ?>
                                                <?php if ($log->status == 'approved') : ?>
                                                    <span class="badge badge-info border border-info"><i class="fa fa-check-circle me-1"></i> Terverifikasi</span>
                                                <?php endif ?>
                                                <?= genBadgeStatusApplication($log->status) ?>
                                            </div>
                                            <div class="text-sm"><?= $log->jam_masuk ?> - <?= $log->jam_keluar ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="la<?= $log->id ?>" class="accordion-collapse collapse" aria-labelledby="hla<?= $log->id ?>" data-bs-parent="#logAccordion" style="">
                                <?php $i++ ?>
                                <div class="accordion-body card card-body py-3 bg-gray-200 shadow-none rounded px-3 text-sm opacity-8 <?= $i == $clog ? 'mb-3' : '' ?>">
                                    <?= $log->kegiatan ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3 gap-1">
                                        <a target="_blank" href="<?= base_url('/assets/img/logbook/' . ($log->bukti ?? '')) ?>" class="badge badge-info border border-info" title="Lihat bukti kegiatan"><i class="fa fa-search m-0 p-0"></i></a>
                                        <div>
                                            <button class="badge badge-danger border border-danger btn-reject" data-id="<?= $log->id ?>" data-stts="rejected" title="Reject Log Book"><i class="fa fa-times m-0 p-0"></i></button>
                                            <button <?= $log->status == "approved" ? 'disabled style="cursor: not-allowed; pointer-events: all !important;"' : '' ?> class="badge badge-success border border-success text-success btn-approve" data-id="<?= $log->id ?>" data-stts="approved" title="Approve Log Book"><i class="fa fa-check m-0 p-0"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
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

    // Handle focus and blur for floating labels
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
});
</script>

<?php if ($status_siswa_terpilih == 'selesai') : ?>
    <script>
        $(document).ready(function() {
            $('.btn-close-modal').on('click', function() {
                $('#fejurusan').trigger('reset');
            });

            $('.formaddnilai').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData(this);
                $.ajax({
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: "POST",
                    url: '/nilai/store',
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                        console.log(thrownError);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseText,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });
    </script>
<?php endif; ?>

<?php if (nilaiAvailable($siswa->id)) : ?>
    <script>
        $(document).ready(function() {
            $(".btn-edit-nilai").click(function() {
                $("#menilai").modal('show');
            });

            // btn-hapus-nilai on click post data to 
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
                            type: "DELETE",
                            url: "/nilai/destroy/" + id,
                            dataType: "JSON",
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(function() {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                console.log(xhr.responseText);
                                console.log(thrownError);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: xhr.responseText,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            });

            $('.formeditnilai').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var data = new FormData(this);
                $.ajax({
                    cache: false,
                    processData: false,
                    contentType: false,
                    type: "POST",
                    url: '/nilai/update',
                    data: data,
                    dataType: "JSON",
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                        console.log(thrownError);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseText,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            });
        });
    </script>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('.btn-approve').on('click', function() {
            let id = $(this).data('id');
            let stts = $(this).data('stts');
            console.log('Approve clicked for ID:', id);
            Swal.fire({
                title: "Approve Log Book",
                text: "Apakah anda yakin ingin menyetujui log book ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("/logbook/status/update") ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            status: stts
                        },
                        success: function(res) {
                            console.log('Approve success:', res);
                            if (res.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Log Book berhasil disetujui",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false,
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Gagal!", res.message || "Log Book gagal disetujui", "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Approve error:', xhr.responseText);
                            Swal.fire("Gagal!", "Terjadi kesalahan server: " + error, "error");
                        }
                    });
                }
            });
        });

        $('.btn-reject').on('click', function() {
            let id = $(this).data('id');
            let stts = $(this).data('stts');
            console.log('Reject clicked for ID:', id);
            Swal.fire({
                title: "Reject Log Book",
                text: "Apakah anda yakin ingin menolak log book ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url("/logbook/status/update") ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            status: stts
                        },
                        success: function(res) {
                            console.log('Reject success:', res);
                            if (res.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: "Log Book berhasil ditolak",
                                    icon: "success",
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire("Gagal!", res.message || "Log Book gagal ditolak", "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Reject error:', xhr.responseText);
                            Swal.fire("Gagal!", "Terjadi kesalahan server: " + error, "error");
                        }
                    });
                }
            });
        });
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
                        data: {
                            id: id,
                            approved: approved
                        },
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
<?= $this->endSection(); ?>