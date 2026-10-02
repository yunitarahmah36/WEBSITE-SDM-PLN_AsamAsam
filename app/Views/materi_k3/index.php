<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-body p-3">
            <div class="d-flex justify-content-between align-items-center px-2">
                <h6>
                    Materi Safety & K3
                </h6>
                <?php if(in_array('admin', user()->getRoles())): ?>
                <button class="btn btn-sm btn-dark" id="btnAddMateri" data-bs-toggle="modal" data-bs-target="#mtMateri">
                    <i class="fas fa-plus me-1"></i>
                    Unggah Materi
                </button>
                <?php endif; ?>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success text-white mt-3"><?= session('success') ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger text-white mt-3">
                    <?php if(is_array(session('error'))) : ?>
                        <ul class="mb-0">
                        <?php foreach(session('error') as $e): ?>
                            <li><?= $e ?></li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <?= session('error') ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive mt-3">
                <table class="table table-hover table-stripped" id="tbMateri">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Judul</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deskripsi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Diupload</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($materi as $m) : ?>
                            <tr>
                                <td class="text-xs ps-4 font-weight-bold"><?= $i++ ?></td>
                                <td class="text-xs ps-4 font-weight-bold"><?= $m->judul ?></td>
                                <td class="text-xs ps-4 font-weight-bold" style="white-space: normal;"><?= $m->deskripsi ?></td>
                                <td class="text-xs ps-4 font-weight-bold"><?= date('d M Y', strtotime($m->created_at)) ?></td>
                                <td class="text-xs ps-4 font-weight-bold">
                                    <a href="/uploads/materi_k3/<?= $m->file_materi ?>" target="_blank" class="badge bg-gradient-info text-white"><i class="fas fa-download me-1"></i> Download</a>
                                    <?php if(in_array('admin', user()->getRoles())): ?>
                                    <button class="badge bg-gradient-warning text-white border-0 btn-edit ms-2" data-item="<?= $m->id; ?>" data-judul="<?= $m->judul; ?>" data-deskripsi="<?= htmlspecialchars($m->deskripsi); ?>"><i class="fas fa-edit"></i></button>
                                    <button class="badge bg-gradient-danger text-white border-0 btn-destroy ms-2" data-item="<?= $m->id; ?>"><i class="fas fa-trash"></i></button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

            <?php if (in_groups('siswa') && !empty(getApplicationSiswa(getSidByUid(user_id())))) : ?>
                <div class="mt-4 p-3 border border-1 border-radius-lg bg-gray-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Konfirmasi Pemahaman Materi</h6>
                            <p class="text-xs mb-0">Klik tombol di samping jika Anda sudah mempelajari seluruh materi di atas dan siap untuk memulai magang.</p>
                        </div>
                        <?php if ($is_safety_ready) : ?>
                            <span class="badge bg-gradient-success"><i class="fas fa-check-circle me-1"></i> Sudah Dikonfirmasi</span>
                        <?php else : ?>
                            <button id="btnCompleteSafety" class="btn btn-sm bg-gradient-dark mb-0">Selesaikan Materi & Mulai Magang</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(in_array('admin', user()->getRoles())): ?>
<!-- modal Tambah Materi -->
<div class="modal fade" id="mtMateri" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="mtMateri" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <span class="h5" id="modalTitle">Unggah Materi K3</span><br>
                        <span id="modalSubTitle">Pilih file berformat pdf/ppt/doc/zip.</span>
                    </div>
                    <div class="card-body">
                        <form role="form" method="post" action="/materi-k3/store" id="formMateri" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="input-group input-group-outline mb-3">
                                <label for="judul" class="form-label">Judul Materi</label>
                                <input type="text" name="judul" id="judul" class="form-control" required>
                            </div>
                            <div class="input-group input-group-outline border rounded p-2 mb-3 is-filled">
                                <label for="deskripsi" class="form-label">Deskripsi Singkat</label>
                                <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control w-100"></textarea>
                            </div>
                            <div class="input-group input-group-outline justify-content-center p-3 mb-3 border border-1 rounded is-filled">
                                <label for="file_materi" class="form-label">File Materi (PDF/DOC/PPT/ZIP max 5MB)</label>
                                <input type="file" name="file_materi" id="file_materi" class="form-control w-100 mt-4" required>
                            </div>
                            
                            <div class="text-center gap-3 d-flex align-items-center justify-content-end mt-4">
                                <button type="button" class="btn btn-round bg-gradient-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-round bg-gradient-info">Unggah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection(); ?>

<?= $this->section('topsc') ;?>
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
<?= $this->endSection() ;?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="/assets/js/plugins/dataTables.bootstrap5.min.js"></script>
<script src="/assets/js/plugins/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        var tbMateri = $("#tbMateri").DataTable({
            language: {
                paginate: {
                    next: '<i class="fas fa-angle-right"></i>',
                    previous: '<i class="fas fa-angle-left"></i>'
                }
            },
        });

        $("#btnAddMateri").on('click', function() {
            $("#modalTitle").text("Unggah Materi K3");
            $("#formMateri").attr('action', '/materi-k3/store');
            $("#judul").val("").parent().removeClass('is-filled');
            $("#deskripsi").val("");
            $("#file_materi").attr('required', true).val("");
            $("label[for='file_materi']").text("File Materi (PDF/DOC/PPT/ZIP max 5MB)");
        });

        $("#tbMateri tbody").on("click", ".btn-edit", function() {
            let id = $(this).data("item");
            let judul = $(this).data("judul");
            let deskripsi = $(this).data("deskripsi");

            $("#modalTitle").text("Edit Materi K3");
            $("#formMateri").attr('action', '/materi-k3/update/' + id);
            
            $("#judul").val(judul).parent().addClass('is-filled');
            $("#deskripsi").val(deskripsi);
            
            $("#file_materi").attr('required', false).val("");
            $("label[for='file_materi']").text("File Materi (Biarkan kosong jika tidak diubah)");
            
            $("#mtMateri").modal('show');
        });

        $("#tbMateri tbody").on("click", ".btn-destroy", function() {
            let t = $(this).data("item");
            Swal.fire({
                icon: "warning",
                title: "Hapus File",
                text: "Apakah anda yakin ingin menghapus materi ini?",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus"
            }).then(e => {
                if (e.isConfirmed) {
                    $.ajax({
                        url: "/materi-k3/destroy/" + t,
                        type: "post", // Using post and setting _method in CI or just straight DELETE if supported. Wait, CI routing for delete is better. I'll use delete.
                        type: "delete",
                        dataType: "json",
                        success: function(res) {
                            if (res.success) {
                                Swal.fire("Berhasil", res.message, "success").then(() => location.reload());
                            } else {
                                Swal.fire("Gagal", res.message, "error");
                            }
                        }
                    })
                }
            })
        });
        $("#btnCompleteSafety").on("click", function() {
            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda menyatakan telah mempelajari seluruh materi Safety & K3 di atas?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, Saya Mengerti"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/materi-k3/complete",
                        type: "POST",
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
