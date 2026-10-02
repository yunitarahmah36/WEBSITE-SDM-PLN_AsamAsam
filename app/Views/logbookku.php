<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Laporan Logbook Anda</h5>
                    <div>
                        <a href="/logbookku/export" class="btn btn-sm btn-success mb-0 me-2"><i class="fa fa-file-excel"></i> Export Excel</a>
                        <a href="/kehadiran" class="btn btn-sm btn-dark mb-0">Isi Kehadiran</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tableLogBook">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan Dosen</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1 ?>
                            <?php foreach ($logbooks as $log) : ?>
                                <tr>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $i++ ?></td>
                                    <td class="text-xs ps-4 font-weight-bold d-flex align-items-center justify-content-start"><?= $log->tanggal ?> <a target="_blank" class="ms-3 text-info"  title="Lihat bukti kegiatan" href="<?= base_url('/assets/img/logbook/' . $log->bukti) ?>"><i class="fa fa-search"></i></a></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $log->keterangan ?></td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <div class="d-flex flex-column gap-1">
                                            <?= genBadgeStatusApplication($log->status) ?>
                                            <?php if ($log->telat) : ?>
                                                <span class="badge badge-danger border border-danger">Telat diisi</span>
                                            <?php endif ?>
                                        </div>
                                    </td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <?php if ($log->catatan_dosen): ?>
                                            <button type="button" class="btn btn-sm btn-outline-info mb-0 btn-lihat-catatan" data-catatan="<?= htmlspecialchars($log->catatan_dosen) ?>"><i class="fa fa-info-circle"></i> Lihat</button>
                                        <?php else: ?>
                                            <span class="text-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <?php if ($log->status == 'rejected') : ?>
                                            <a href="/kehadiran/<?= $log->id ?>" class="badge border border-1 border-dark text-dark btn-edit" title="Edit Data"><i class="fas fa-edit"></i> Edit</a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/datatables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('body').on('click', '.btn-lihat-catatan', function() {
            const catatan = $(this).data('catatan');
            Swal.fire({
                title: 'Catatan Dosen',
                text: catatan,
                icon: 'info',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#3085d6'
            });
        });

        const dataTableBasic = new simpleDatatables.DataTable("#tableLogBook", {
            searchable: false,
            fixedHeight: true
        });
    });
</script>
<?= $this->endSection(); ?>