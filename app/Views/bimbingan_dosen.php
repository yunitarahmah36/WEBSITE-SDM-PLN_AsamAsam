<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card card-body py-3 px-4">
            <div class="d-flex justify-content-between align-items-center px-2 mb-4">
                <div>
                    <span class="h5 mb-0 pb-0">Daftar Mahasiswa Bimbingan</span> <br>
                    Berikut adalah daftar mahasiswa yang berada di bawah bimbingan akademik Anda.
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-stripped" id="tbbimbingan">
                    <thead>
                        <tr>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIM</th>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Mahasiswa</th>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kelas</th>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Divisi Magang</th>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Laporan Akhir</th>
                            <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($applications as $app) : ?>
                            <tr>
                                <td class="ps-3 text-xs font-weight-bold"><?= $no++ ?></td>
                                 <td class="ps-3 text-xs font-weight-bold">
                                    <div class="badge badge-secondary"><?= $app->nim ?></div>
                                </td>
                                <td class="ps-3 text-xs font-weight-bold"><?= $app->nama ?></td>
                                <td class="ps-3 text-xs font-weight-bold"><?= $app->kelas ?></td>
                                <td class="ps-3 text-xs font-weight-bold"><?= $app->instansi ?: '-' ?></td>
                                <td class="ps-3 text-xs font-weight-bold">
                                    <?= genBadgeStatusApplication($app->status) ?>
                                </td>
                                <td class="ps-3 text-xs font-weight-bold">
                                    <?php if ($app->laporan) : ?>
                                        <div class="d-flex flex-column gap-2">
                                            <a href="<?= base_url('assets/laporan/' . $app->laporan) ?>" target="_blank" class="badge badge-info border border-info shadow text-center w-100">LIHAT LAPORAN</a>
                                            
                                            <?php if ($app->is_laporan_approved) : ?>
                                                <button class="badge btn-outline-info border-info text-info btn-report-approve w-100" data-approved="0" data-id="<?= $app->sid ?>">
                                                    <i class="fa fa-times me-1"></i> BATALKAN
                                                </button>
                                            <?php else : ?>
                                                <button class="badge bg-gradient-info text-white border-0 btn-report-approve w-100" data-approved="1" data-id="<?= $app->sid ?>">
                                                    <i class="fa fa-check me-1"></i> SETUJUI
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    <?php else : ?>
                                        <span class="badge badge-dark border border-dark opacity-5">BELUM ADA</span>
                                    <?php endif; ?>
                                </td>
                                <td class="ps-3 text-xs font-weight-bold">
                                    <a href="<?= base_url('/bimbingan-dosen/logbook/' . $app->nim) ?>" class="badge badge-primary border border-primary shadow">LIHAT LOGBOOK</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('topsc'); ?>
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="/assets/js/plugins/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $("#tbbimbingan").DataTable({
            dom: '<"row"<"col-12"tr>><"row mt-2 px-3"<"col-12 col-md-6"i><"col-12 col-md-6"p>>',
            pageLength: 10,
            language: {
                paginate: {
                    next: '<i class="fas fa-angle-right"></i>',
                    previous: '<i class="fas fa-angle-left"></i>'
                }
            },
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
                confirmButtonText: "Ya, Lanjutkan",
                confirmButtonColor: '#17c1e8',
                cancelButtonColor: '#6c757d',
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
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: res.message,
                                    icon: "success",
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire("Gagal!", res.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Gagal!", "Terjadi kesalahan pada server.", "error");
                        }
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>