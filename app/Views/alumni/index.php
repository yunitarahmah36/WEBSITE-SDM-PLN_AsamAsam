<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 px-3 d-flex flex-column flex-sm-row justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize mb-2 mb-sm-0 ps-1">Kelola Galeri & Testimoni Alumni</h6>
                    <div class="d-flex align-items-center gap-2">
                        <a href="/alumni/export/excel" target="_blank" class="btn btn-sm btn-white text-success mb-0 font-weight-bold shadow-sm d-inline-flex align-items-center">
                            <i class="far fa-file-excel text-success me-1 fa-lg"></i>
                            Export Excel
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="tb-alumni">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Siswa</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jurusan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Instansi</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dosen Pembimbing</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Divisi</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rencana Magang</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Berkas</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Tampil di Landing?</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($alumni as $a) : ?>
                                <?php
                                $tglMulai = !empty($a->tgl_masuk) ? $a->tgl_masuk : ($a->angkatan_tgl_mulai ?? null);
                                $tglSelesai = !empty($a->tgl_selesai) ? $a->tgl_selesai : ($a->angkatan_tgl_selesai ?? null);
                                $rencanaStr = '-';
                                if ($tglMulai && $tglSelesai) {
                                    $rencanaStr = date('d/m/Y', strtotime($tglMulai)) . ' s/d ' . date('d/m/Y', strtotime($tglSelesai));
                                } elseif ($tglSelesai) {
                                    $rencanaStr = 'Selesai: ' . date('d/m/Y', strtotime($tglSelesai));
                                }
                                ?>
                                <tr>
                                    <td class="text-sm ps-4 text-center"><?= $i++ ?></td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <?php
                                                $foto_url = '';
                                                if (!empty($a->alumni_foto) && file_exists(FCPATH . 'assets/alumni/' . $a->alumni_foto)) {
                                                    $foto_url = base_url('assets/alumni/' . $a->alumni_foto);
                                                } elseif (!empty($a->foto) && file_exists(FCPATH . 'assets/img/foto_siswa/' . $a->foto)) {
                                                    $foto_url = base_url('assets/img/foto_siswa/' . $a->foto);
                                                } elseif (!empty($a->foto) && file_exists(FCPATH . 'assets/img/user_profile/' . $a->foto)) {
                                                    $foto_url = base_url('assets/img/user_profile/' . $a->foto);
                                                } elseif (!empty($a->foto) && filter_var($a->foto, FILTER_VALIDATE_URL)) {
                                                    $foto_url = $a->foto;
                                                } else {
                                                    $foto_url = base_url('assets/img/default.png');
                                                }
                                                ?>
                                                <img src="<?= $foto_url ?>" class="avatar avatar-sm me-3 border-radius-lg" alt="<?= esc($a->nama) ?>" style="object-fit: cover;">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= esc($a->nama) ?></h6>
                                                <?php if (!empty($a->nim)) : ?>
                                                    <p class="text-xxs text-secondary mb-0"><?= esc($a->nim) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold text-dark"><?= esc($a->jurusan ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold text-dark"><?= esc($a->instansi ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($a->dosen_nama)) : ?>
                                            <div class="text-wrap text-xs font-weight-bold text-dark"><?= esc($a->dosen_nama) ?></div>
                                        <?php else : ?>
                                            <span class="badge badge-secondary border border-secondary" style="font-size: 10px;">Belum ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="text-xs font-weight-bold text-dark"><?= esc($a->divisi ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <span class="text-xs text-secondary font-weight-bold">
                                            <i class="fa fa-calendar-alt text-xxs me-1 text-primary"></i><?= $rencanaStr ?>
                                        </span>
                                    </td>
                                    <td class="text-xs font-weight-bold">
                                        <?php if (!empty($a->surat_permohonan)) : ?>
                                            <a href="/assets/berkas/<?= $a->surat_permohonan ?>" target="_blank" class="badge badge-info border border-info mb-1" title="Surat Permohonan"><i class="fa fa-file-pdf"></i> Surat</a><br>
                                        <?php endif; ?>
                                        <?php if (!empty($a->cv)) : ?>
                                            <a href="/assets/berkas/<?= $a->cv ?>" target="_blank" class="badge badge-info border border-info mb-1" title="CV"><i class="fa fa-file-pdf"></i> CV</a><br>
                                        <?php endif; ?>
                                        <?php if (!empty($a->surat_pernyataan)) : ?>
                                            <a href="/assets/berkas/<?= $a->surat_pernyataan ?>" target="_blank" class="badge bg-warning border border-warning text-white mb-1" title="Surat Pernyataan"><i class="fa fa-file-signature"></i> Pernyataan</a><br>
                                        <?php endif; ?>
                                        <?php if (!isSmkSiswa($a) && !empty($a->laporan)) : ?>
                                            <a href="/assets/laporan/<?= $a->laporan ?>" target="_blank" class="badge badge-primary border border-primary mb-1" title="Laporan Magang"><i class="fa fa-file-alt"></i> Laporan</a><br>
                                        <?php endif; ?>
                                        <?php if (isCertificateReady($a)) : ?>
                                            <a href="/sertifikat/download/<?= $a->id_siswa ?>" target="_blank" class="badge badge-success border border-success mb-1" title="Sertifikat Magang"><i class="fa fa-certificate"></i> Sertifikat</a><br>
                                        <?php endif; ?>
                                        <?php if (empty($a->surat_permohonan) && empty($a->cv) && empty($a->surat_pernyataan) && empty($a->laporan) && !isCertificateReady($a)) : ?>
                                            <span class="text-secondary text-xxs">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <?php if ($a->alumni_is_tampil) : ?>
                                            <span class="badge badge-sm bg-gradient-success">Ya</span>
                                        <?php else : ?>
                                            <span class="badge badge-sm bg-gradient-secondary">Tidak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-sm btn-info mb-0 py-1 px-2 btn-edit" data-id="<?= $a->id_siswa ?>" data-nama="<?= esc($a->nama) ?>" data-jurusan="<?= esc($a->jurusan ?? '-') ?>" data-instansi="<?= esc($a->instansi ?? '-') ?>" data-dosen="<?= esc($a->dosen_nama ?? '-') ?>" data-rencana="<?= esc($rencanaStr) ?>" data-divisi="<?= esc($a->divisi ?? '-') ?>" data-testimoni="<?= esc($a->alumni_testimoni ?? '') ?>" data-tampil="<?= $a->alumni_is_tampil ?>" data-foto="<?= esc($a->alumni_foto ?? '') ?>" title="Edit"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-sm btn-danger mb-0 py-1 px-2 btn-hapus" data-id="<?= $a->id_siswa ?>" data-nama="<?= esc($a->nama) ?>" title="Hapus"><i class="fa fa-trash"></i> Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Alumni -->
<div class="modal fade" id="modalEditAlumni" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelola Data Alumni</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditAlumni" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <h6 id="nama_siswa" class="text-primary mb-1"></h6>
                    <p class="text-xs text-secondary mb-3">
                        <span id="jurusan_siswa" class="font-weight-bold text-dark"></span> • <span id="instansi_siswa"></span><br>
                        <span id="divisi_siswa" class="font-weight-bold text-dark"></span> • 
                        <i class="fa fa-calendar-alt text-xxs text-primary me-1"></i><span id="rencana_siswa"></span>
                    </p>
                    
                    <div class="form-group mb-3">
                        <label class="form-label text-xs font-weight-bold">Tampilkan di Landing Page?</label>
                        <div class="form-check form-switch ms-2">
                            <input class="form-check-input" type="checkbox" id="alumni_is_tampil" name="alumni_is_tampil" value="1">
                            <label class="form-check-label" for="alumni_is_tampil">Tampil</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-xs font-weight-bold">Kesan Pesan / Testimoni (Opsional)</label>
                        <textarea name="alumni_testimoni" id="alumni_testimoni" class="form-control border p-2" rows="3" placeholder="Tuliskan testimoni atau kesan pesan magang dari siswa ini..."></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label text-xs font-weight-bold">Foto Galeri Khusus (Opsional)</label>
                        <div id="preview_foto_container" class="mb-2 d-none">
                            <img id="preview_foto" src="" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                            <br>
                            <small class="text-xs text-info">Foto saat ini</small>
                        </div>
                        <input type="file" name="alumni_foto" class="form-control border p-2" accept="image/*">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Unggah foto kegiatan / kenang-kenangan magang untuk ditampilkan di galeri landing page.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn bg-gradient-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/datatables.js"></script>
<script src="/assets/js/plugins/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        const dataTableBasic = new simpleDatatables.DataTable("#tb-alumni", {
            searchable: true,
            fixedHeight: true,
            perPage: 10,
        });

        let currentId = null;

        $('.btn-edit').on('click', function() {
            $('#formEditAlumni').trigger('reset'); // reset file input first
            
            currentId = $(this).data('id');
            $('#nama_siswa').text($(this).data('nama'));
            $('#jurusan_siswa').text($(this).data('jurusan') || '-');
            $('#instansi_siswa').text($(this).data('instansi') || '-');
            $('#divisi_siswa').text($(this).data('divisi') || '-');
            $('#rencana_siswa').text($(this).data('rencana') || '-');
            $('#alumni_testimoni').val($(this).data('testimoni'));
            $('#alumni_is_tampil').prop('checked', $(this).data('tampil') == 1);
            
            let foto = $(this).data('foto');
            if (foto) {
                $('#preview_foto').attr('src', '/assets/alumni/' + foto);
                $('#preview_foto_container').removeClass('d-none');
            } else {
                $('#preview_foto_container').addClass('d-none');
            }
            
            $('#modalEditAlumni').modal('show');
        });

        $('#formEditAlumni').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: '/alumni/update/' + currentId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('#formEditAlumni button[type=submit]').attr('disabled', true).text('Menyimpan...');
                },
                success: function(res) {
                    $('#formEditAlumni button[type=submit]').attr('disabled', false).text('Simpan Perubahan');
                    if (res.status === 200) {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function() {
                    $('#formEditAlumni button[type=submit]').attr('disabled', false).text('Simpan Perubahan');
                    Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                }
            });
        });

        $('.btn-hapus').on('click', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            Swal.fire({
                title: 'Hapus Data Alumni?',
                text: "Data galeri dan testimoni alumni " + nama + " akan dihapus dan disembunyikan dari halaman depan. (Data magang tetap aman)",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/alumni/reset/' + id,
                        type: 'POST',
                        success: function(res) {
                            if (res.status === 200) {
                                Swal.fire('Dihapus!', res.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>
