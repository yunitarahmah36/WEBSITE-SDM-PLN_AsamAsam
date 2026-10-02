<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Kelola Galeri & Testimoni Alumni</h6>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="tb-alumni">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Siswa</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Divisi / Tgl Selesai</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Tampil di Landing?</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($alumni as $a) : ?>
                                <tr>
                                    <td class="text-sm ps-4 text-center"><?= $i++ ?></td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <?php
                                                $foto_url = '';
                                                if (!empty($a->alumni_foto)) {
                                                    $foto_url = base_url('assets/alumni/' . $a->alumni_foto);
                                                } elseif (!empty($a->foto)) {
                                                    $foto_url = filter_var($a->foto, FILTER_VALIDATE_URL) ? $a->foto : base_url('assets/img/user_profile/' . $a->foto);
                                                } else {
                                                    $foto_url = base_url('assets/img/default.png');
                                                }
                                                ?>
                                                <img src="<?= $foto_url ?>" class="avatar avatar-sm me-3 border-radius-lg" alt="user1" style="object-fit: cover;">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm"><?= $a->nama ?></h6>
                                                <p class="text-xs text-secondary mb-0"><?= $a->instansi ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0"><?= $a->divisi ?></p>
                                        <p class="text-xs text-secondary mb-0"><?= date('d F Y', strtotime($a->tgl_selesai)) ?></p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <?php if ($a->alumni_is_tampil) : ?>
                                            <span class="badge badge-sm bg-gradient-success">Ya</span>
                                        <?php else : ?>
                                            <span class="badge badge-sm bg-gradient-secondary">Tidak</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-sm btn-info mb-0 py-1 px-2 btn-edit" data-id="<?= $a->id_siswa ?>" data-nama="<?= $a->nama ?>" data-testimoni="<?= esc($a->alumni_testimoni ?? '') ?>" data-tampil="<?= $a->alumni_is_tampil ?>" data-foto="<?= esc($a->alumni_foto ?? '') ?>" title="Edit"><i class="fa fa-edit"></i> Edit</button>
                                        <button class="btn btn-sm btn-danger mb-0 py-1 px-2 btn-hapus" data-id="<?= $a->id_siswa ?>" data-nama="<?= $a->nama ?>" title="Hapus"><i class="fa fa-trash"></i> Hapus</button>
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
                    <h6 id="nama_siswa" class="text-primary mb-3"></h6>
                    
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
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Jika dikosongkan, akan menggunakan foto profil bawaan. Unggah foto kegiatan/bersama untuk ditampilkan di galeri.</small>
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
