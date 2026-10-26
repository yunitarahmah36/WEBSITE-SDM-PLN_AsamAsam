<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<style>
    /* =========================================
       TABEL LOGBOOK - TAMPILAN BERSIH
       ========================================= */
    
    /* Sembunyikan ikon sorting di header tabel */
    .dataTable-table thead th button.datatable-sorter::before,
    .dataTable-table thead th button.datatable-sorter::after {
        display: none !important;
        content: none !important;
    }
    
    .dataTable-table thead th button.datatable-sorter {
        cursor: default !important;
        pointer-events: none !important;
    }

    /* Rapikan header tabel */
    #tableLogBook thead th {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: #8392ab !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: 1px solid #e9edf3 !important;
        padding: 12px 16px !important;
        background: #fbfcfe !important;
        vertical-align: middle !important;
    }

    /* Rapikan body tabel */
    #tableLogBook tbody td {
        font-size: 13px !important;
        color: #344767 !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid #f0f2f5 !important;
        vertical-align: middle !important;
    }

    #tableLogBook tbody tr:hover {
        background: #f8fafc !important;
    }

    /* Tombol aksi */
    .btn-edit-logbook {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        background: #fff;
        color: #344767;
        border: 1px solid #344767;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        transition: all 0.2s ease;
    }

    .btn-edit-logbook:hover {
        background: #344767;
        color: #fff;
        text-decoration: none;
    }

    .btn-lihat-catatan {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        background: #e8f5e9; /* Hijau muda */
        color: #2e7d32; /* Teks hijau gelap */
        border: 1px solid #a5d6a7;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-lihat-catatan:hover {
        background: #2e7d32; /* Hijau gelap */
        color: #fff;
        border-color: #2e7d32;
    }

    /* Rapikan input search dan page length */
    .dataTable-input,
    .dataTable-selector {
        padding: 6px 10px !important;
        border-radius: 6px !important;
        border: 1px solid #d2d6da !important;
        font-size: 13px !important;
        outline: none !important;
    }

    .dataTable-input:focus,
    .dataTable-selector:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15) !important;
    }

    .dataTable-info {
        font-size: 13px !important;
        color: #8392ab !important;
    }

    .dataTable-pagination a {
        padding: 6px 12px !important;
        border-radius: 6px !important;
        font-size: 13px !important;
        color: #344767 !important;
        border: 1px solid #e9edf3 !important;
        margin: 0 2px !important;
        text-decoration: none !important;
    }

    .dataTable-pagination a:hover {
        background: #f5f7fa !important;
    }

    .dataTable-pagination .active a {
        background: #3b82f6 !important;
        color: #fff !important;
        border-color: #3b82f6 !important;
    }

    /* =========================================
       MODAL CATATAN DOSEN - TAMPILAN HIJAU
       ========================================= */
    .modal-catatan-dosen .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    }

    .modal-catatan-dosen .modal-header {
        background: linear-gradient(135deg, #10b981, #059669); /* Gradasi Hijau */
        color: #fff;
        border-bottom: none;
        padding: 18px 22px;
    }

    .modal-catatan-dosen .modal-header .modal-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modal-catatan-dosen .modal-header .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }

    .modal-catatan-dosen .modal-body {
        padding: 22px;
        background: #fff;
    }

    .catatan-info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5; /* Hijau sangat muda */
        color: #047857; /* Teks hijau */
        border: 1px solid #a7f3d0;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 14px;
    }

    .catatan-content {
        background: #f8fafc;
        border-left: 4px solid #10b981; /* Garis kiri hijau */
        border-radius: 8px;
        padding: 16px 18px;
        font-size: 14px;
        color: #1e293b;
        line-height: 1.7;
        white-space: pre-wrap;
        word-break: break-word;
        max-height: 400px;
        overflow-y: auto;
        text-align: left !important;
    }

    .catatan-content::-webkit-scrollbar {
        width: 6px;
    }

    .catatan-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .catatan-content::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .catatan-content::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .modal-catatan-dosen .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 14px 22px;
        background: #fbfcfe;
    }

    .btn-tutup-catatan {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        background: #10b981; /* Tombol Hijau */
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-tutup-catatan:hover {
        background: #059669; /* Hijau lebih gelap saat di-hover */
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0">Daftar Laporan Logbook Anda</h5>
                    <div>
                        <a href="/logbookku/export" class="btn btn-sm btn-success mb-0 me-2"><i class="fa fa-file-excel"></i> Export Excel</a>
                        <a href="/kehadiran" class="btn btn-sm btn-dark mb-0">Isi Kehadiran</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="tableLogBook">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Keterangan</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Catatan Dosen</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1 ?>
                            <?php foreach ($logbooks as $log) : ?>
                                <tr>
                                    <td class="text-xs font-weight-bold"><?= $i++ ?></td>
                                    <td class="text-xs font-weight-bold">
                                        <div class="d-flex align-items-center gap-2">
                                            <span><?= $log->tanggal ?></span>
                                            <a target="_blank" class="text-info" title="Lihat bukti kegiatan" href="<?= base_url('/assets/img/logbook/' . $log->bukti) ?>">
                                                <i class="fa fa-search"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-xs font-weight-bold"><?= ucfirst($log->keterangan) ?></td>
                                    <td class="text-xs font-weight-bold">
                                        <div class="d-flex flex-column gap-1">
                                            <?= genBadgeStatusApplication($log->status) ?>
                                            <?php if ($log->telat) : ?>
                                                <span class="badge badge-danger border border-danger">Telat diisi</span>
                                            <?php endif ?>
                                        </div>
                                    </td>
                                    <td class="text-xs font-weight-bold">
                                        <?php if ($log->catatan_dosen): ?>
                                            <button type="button" class="btn-lihat-catatan" 
                                                data-catatan="<?= htmlspecialchars($log->catatan_dosen) ?>"
                                                data-tanggal="<?= $log->tanggal ?>">
                                                <i class="fa fa-info-circle"></i> Lihat
                                            </button>
                                        <?php else: ?>
                                            <span class="text-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-xs font-weight-bold">
                                        <?php if ($log->status == 'rejected') : ?>
                                            <a href="/kehadiran/<?= $log->id ?>" class="btn-edit-logbook" title="Edit Data">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        <?php else : ?>
                                            <span class="text-secondary">-</span>
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

<!-- =========================================
     MODAL CATATAN DOSEN
     ========================================= -->
<div class="modal fade modal-catatan-dosen" id="modalCatatanDosen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-comment-dots"></i> Catatan Dosen Pembimbing
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="catatan-info-badge">
                    <i class="fa fa-calendar"></i>
                    <span id="catatanTanggal">-</span>
                </div>
                <div class="catatan-content" id="catatanIsi">-</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-tutup-catatan" data-bs-dismiss="modal">
                    <i class="fa fa-check"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/datatables.js"></script>
<script>
    $(document).ready(function() {
        // Handler untuk buka modal catatan dosen
        $('body').on('click', '.btn-lihat-catatan', function() {
            const catatan = $(this).data('catatan');
            const tanggal = $(this).data('tanggal');
            
            $('#catatanIsi').text(catatan);
            $('#catatanTanggal').text('Tanggal: ' + tanggal);
            $('#modalCatatanDosen').modal('show');
        });

        // Inisialisasi simpleDatatables TANPA SORTING
        const dataTableBasic = new simpleDatatables.DataTable("#tableLogBook", {
            searchable: true,
            fixedHeight: true,
            perPage: 10,
            perPageSelect: [10, 25, 50, 100],
            columns: [
                { select: 0, sortable: false }, // #
                { select: 1, sortable: false }, // Tanggal
                { select: 2, sortable: false }, // Keterangan
                { select: 3, sortable: false }, // Status
                { select: 4, sortable: false }, // Catatan Dosen
                { select: 5, sortable: false }  // Aksi
            ],
            labels: {
                placeholder: "Cari...",
                perPage: "{select} data per halaman",
                noRows: "Tidak ada data yang ditemukan",
                info: "Menampilkan {start} sampai {end} dari {rows} data",
                noResults: "Tidak ada hasil yang cocok dengan pencarian Anda",
            }
        });
    });
</script>
<?= $this->endSection(); ?>