<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>

<!-- filter -->
<div class="mb-4 card card-body ">
    <h6>Filter Divisi Magang</h6>
    <div class="row">
        <div class="col-12 col-sm-5 col-md-4">
            <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1" role="tablist" id="filterStatus">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 text-dark" data-bs-toggle="tab" href="javascript:void(0);" data-status="semua" role="tab" aria-controls="all" aria-selected="false">
                            <span class="material-icons align-middle mb-1 me-1">
                                list
                            </span>
                            Semua
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 text-success active" data-bs-toggle="tab" href="javascript:void(0);" data-status="buka" role="tab" aria-controls="preview" aria-selected="true">
                            <span class="material-icons align-middle mb-1 me-1">
                                done
                            </span>
                            Buka
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 text-danger" data-bs-toggle="tab" href="javascript:void(0);" data-status="tutup" role="tab" aria-controls="code" aria-selected="false">
                            <span class="material-icons align-middle mb-1 me-1">
                                close
                            </span>
                            Tutup
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-12 col-sm-7 col-md-8">
            <div class="input-group input-group-outline">
                <input type="text" class="form-control" placeholder="Cari divisi magang" aria-label="Cari divisi magang" aria-describedby="button-addon2" id="search">
            </div>
        </div>
    </div>
</div>

<div class="row mb-5 row-instansi" data-masonry='{"percentPosition": true }'>
    <?php foreach ($tempat as $t) : ?>
        <?php
        if (filter_var($t->foto, FILTER_VALIDATE_URL)) {
            $foto = $t->foto;
        } else {
            $foto = '/assets/img/tempat_magang/' . $t->foto;
        }
        ?>
<div class="col-12 col-sm-6 col-md-4 col-xxl-3 mb-2 cardInstansi <?= strtolower($t->status) ?>">
            <div class="card mt-2 compact-card" data-animation="<?= initCheck(user_id(), $t->id) && $t->status == 'buka' ? 'true' : 'false' ?>">
                <div class="d-flex align-items-stretch">
                    <!-- Thumbnail kiri -->
                    <div class="flex-shrink-0" style="width:120px; min-height:120px;">
                        <img src="<?= $foto ?>" alt="<?= $t->nama ?>"
                            style="width:120px; height:100%; min-height:120px; object-fit:cover; border-radius:0.5rem 0 0 0.5rem;">
                    </div>
                    <!-- Info kanan -->
                    <div class="flex-grow-1 px-3 py-2 d-flex flex-column justify-content-between" style="min-width:0;">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-weight-bold text-dark" style="font-size:0.9rem; line-height:1.2;"><?= $t->nama ?></span>
                                <span class="badge badge-sm ms-1 flex-shrink-0 <?= getSlotAvailable($t->id) == 0 ? 'badge-warning' : ($t->status == 'buka' ? 'badge-success' : 'badge-danger') ?>" style="font-size:0.65rem;"><?= getSlotAvailable($t->id) == 0 ? 'FULL' : strtoupper($t->status) ?></span>
                            </div>
                            <div class="full-desc mt-1 text-muted" style="border-bottom: 1px dashed #e2e8f0; padding-bottom: 6px; margin-bottom: 6px;">
                                <?= $t->deskripsi ?>
                            </div>
                        </div>

                        <!-- Detail collapsible -->
                        <div class="detail-panel" id="detail-<?= $t->id ?>" style="display:none;">
                            <?php if (!empty($t->email)) : ?>
                            <div class="d-flex align-items-center gap-1 mt-2">
                                <i class="fa fa-envelope text-primary" style="font-size:0.75rem; min-width:14px;"></i>
                                <span style="font-size:0.75rem;"><?= $t->email ?></span>        
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($t->hp)) : ?>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <i class="fa fa-phone text-success" style="font-size:0.75rem; min-width:14px;"></i>
                                <span style="font-size:0.75rem;"><?= $t->hp ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($t->alamat)) : ?>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <i class="fa fa-map-marker-alt text-danger" style="font-size:0.75rem; min-width:14px;"></i>
                                <span style="font-size:0.75rem;"><?= $t->alamat ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                <i class="fa fa-users text-info" style="font-size:0.75rem; min-width:14px;"></i>
                                <span style="font-size:0.75rem;">Kuota tersisa: <strong><?= getSlotAvailable($t->id) ?></strong></span>
                            </div>
                        </div>

                        <!-- Footer: pembimbing + tombol -->
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <div style="min-width:0;">
                                <span class="text-muted d-block text-truncate" style="font-size:0.75rem;" title="<?= $t->pembimbing ?>">
                                    <i class="fa fa-user-tie me-1"></i><?= $t->pembimbing ?>
                                </span>
                                <button type="button" class="btn btn-link p-0 btn-toggle-detail" data-tid="<?= $t->id ?>"
                                    style="font-size:0.75rem; color:#aaa; line-height:1; margin-top:2px;">
                                    <i class="fa fa-chevron-down icon-toggle"></i> Detail
                                </button>
                            </div>
                            <div class="flex-shrink-0 ms-1">
                                <?php
                                $sid = getSidByUid(user_id());
                                $appModel = new \App\Models\ApplicationModel();
                                $thisApp = $appModel->where(['id_siswa' => $sid, 'id_tempat' => $t->id])->first();
                                
                                $pendingApps = $appModel->where(['id_siswa' => $sid, 'status' => 'pending'])->findAll();
                                $pendingCount = count($pendingApps);
                                
                                $acceptedAny = $appModel->where('id_siswa', $sid)->whereIn('status', ['accepted', 'selesai'])->countAllResults() > 0;
                                ?>
                                <?php if (!logged_in()) : ?>
                                    <button type="button" class="btn bg-gradient-primary btn-sm py-1 px-3 mb-0 btn-login-first"
                                        data-instansi="<?= $t->nama ?>"
                                        style="font-size:0.75rem; line-height:1.2;"
                                        <?= $t->status == 'tutup' || getSlotAvailable($t->id) == 0 ? 'disabled' : '' ?>>Daftar</button>
                                <?php elseif (initCheck(user_id(), $t->id)) : ?>
                                    <button type="button" class="btn bg-gradient-primary btn-sm py-1 px-3 mb-0 btn-daftar"
                                        data-instansi="<?= $t->nama ?>" data-tid="<?= $t->id ?>"
                                        style="font-size:0.75rem; line-height:1.2;"
                                        <?= $t->status == 'tutup' || getSlotAvailable($t->id) == 0 ? 'disabled' : '' ?>>Daftar</button>
                                <?php else : ?>
                                    <?php if ($acceptedAny) : ?>
                                        <span class="badge badge-success" style="font-size:0.65rem;">Terdaftar</span>
                                    <?php elseif ($thisApp && $thisApp->status == 'pending') : ?>
                                        <span class="badge bg-gradient-info" style="font-size:0.65rem;">Pending</span>
                                    <?php elseif ($thisApp && in_array($thisApp->status, ['rejected', 'reject by system'])) : ?>
                                        <span class="badge bg-gradient-danger" style="font-size:0.65rem;">Ditolak</span>
                                    <?php elseif ($pendingCount >= 2) : ?>
                                        <span class="badge badge-secondary" style="font-size:0.65rem;">Maks 2</span>
                                    <?php elseif (getSlotAvailable($t->id) == 0) : ?>
                                        <span class="badge badge-warning" style="font-size:0.65rem;">Penuh</span>
                                    <?php elseif ($t->status != 'tutup' && getSlotAvailable($t->id) > 0) : ?>
                                        <!-- Fallback: show Daftar button if nothing else matched and slot is available -->
                                        <button type="button" class="btn bg-gradient-primary btn-sm py-1 px-3 mb-0 btn-daftar"
                                            data-instansi="<?= $t->nama ?>" data-tid="<?= $t->id ?>"
                                            style="font-size:0.75rem; line-height:1.2;">Daftar</button>
                                    <?php endif ?>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<style>
    .compact-card {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .compact-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.18);
        transform: translateY(-2px);
    }
    .detail-panel {
        overflow: hidden;
        transition: max-height 0.35s ease, opacity 0.35s ease;
        max-height: 0;
        opacity: 0;
    }
    .detail-panel.open {
        max-height: 2000px;
        opacity: 1;
    }
    .btn-toggle-detail {
        text-decoration: none !important;
        transition: color 0.2s;
    }
    .btn-toggle-detail:hover { color: #344767 !important; }
    .icon-toggle { transition: transform 0.3s ease; display: inline-block; }
    .icon-toggle.rotated { transform: rotate(180deg); }

    /* Rich text styling in collapsible card details */
    .full-desc {
        max-height: 110px;
        overflow-y: auto;
        padding-right: 6px;
        font-size: 0.9rem !important;
    }
    .full-desc::-webkit-scrollbar {
        width: 4px;
    }
    .full-desc::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.03);
        border-radius: 4px;
    }
    .full-desc::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.15);
        border-radius: 4px;
    }
    .full-desc::-webkit-scrollbar-thumb:hover {
        background: rgba(0,0,0,0.3);
    }
    
    .full-desc h1, .full-desc h2, .full-desc h3 {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        margin-top: 10px !important;
        margin-bottom: 5px !important;
        color: #1a1a2e !important;
    }
    .full-desc h4, .full-desc h5, .full-desc h6 {
        font-size: 1.0rem !important;
        font-weight: 600 !important;
        margin-top: 8px !important;
        margin-bottom: 4px !important;
        color: #344767 !important;
    }
    .full-desc p {
        font-size: 0.9rem !important;
        line-height: 1.5 !important;
        margin-bottom: 6px !important;
        color: #4a5568 !important;
    }
    .full-desc ul, .full-desc ol {
        padding-left: 1.2rem !important;
        margin-bottom: 8px !important;
        font-size: 0.9rem !important;
        color: #4a5568 !important;
    }
    .full-desc li {
        margin-bottom: 3px !important;
        line-height: 1.5 !important;
        font-size: 0.9rem !important;
    }
</style>
<!-- Load SweetAlert Early -->
<script src="/assets/js/plugins/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>

<script>
    // Toggle detail panel
    $(document).on('click', '.btn-toggle-detail', function() {
        var tid = $(this).data('tid');
        var panel = $('#detail-' + tid);
        var icon = $(this).find('.icon-toggle');
        var btn = $(this);
        if (panel.hasClass('open')) {
            panel.removeClass('open');
            icon.removeClass('rotated');
            btn.html('<i class="fa fa-chevron-down me-1 icon-toggle"></i>Lihat Detail');
        } else {
            panel.css('display', 'block').addClass('open');
            icon.addClass('rotated');
            btn.html('<i class="fa fa-chevron-up me-1 icon-toggle rotated"></i>Sembunyikan');
        }
        setTimeout(() => { $grid.masonry('layout'); }, 380);
    });

    // Daftarkan event click sesegera mungkin menggunakan delegasi
    $(document).on('click', '.btn-daftar', function() {
        var instansi = $(this).data('instansi');
        var tid = $(this).data('tid');
        
        // Ambil token CSRF terbaru langsung dari meta tag saat tombol diklik
        var csrfName = '<?= csrf_token() ?>';
        var csrfHeader = '<?= csrf_header() ?>';
        var csrfHash = $('meta[name="' + csrfHeader + '"]').attr('content');

        Swal.fire({
            title: 'Daftar Magang',
            text: "Apakah anda yakin ingin mendaftar magang di " + instansi + "?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Daftar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/tempat/daftar',
                    type: 'post',
                    headers: { [csrfHeader]: csrfHash },
                    data: { tid: tid },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message || ("Anda berhasil mendaftar magang di " + instansi),
                                icon: 'success'
                            }).then(() => { window.location.href = '/status-lamaran'; });
                        } else {
                            Swal.fire({ title: 'Gagal', text: data.message || "Terjadi kesalahan.", icon: 'error' });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Sesi Berakhir',
                            text: "Sesi Anda mungkin telah kedaluwarsa atau terjadi gangguan koneksi. Silakan reload halaman ini.",
                            icon: 'warning'
                        });
                    }
                })
            }
        })
    });

    $(document).on('click', '.btn-login-first', function() {
        var instansi = $(this).data('instansi');
        Swal.fire({
            title: 'Harus Login',
            text: "Untuk mendaftar magang di " + instansi + ", Anda harus login terlebih dahulu.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Login Sekarang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/login';
            }
        });
    });

    var $grid; // declare in outer scope so toggle handler can access it

    $(document).ready(function() {
        $grid = $('.row-instansi').masonry({
            itemSelector: '.cardInstansi',
            percentPosition: true
        });

        // Tunggu gambar dimuat sebelum memicu layouting
        $grid.imagesLoaded().progress( function() {
            $grid.masonry('layout');
        });

        var status = 'buka';
        sh(status);

        $('#search').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('.cardInstansi').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(value) > -1 && ($(this).hasClass(status) || status == 'semua')) {
                    $(this).show().removeClass('d-none');
                } else {
                    $(this).hide().addClass('d-none');
                }
            });
            $grid.masonry('layout');
        });

        $('#filterStatus a').on('click', function(e) {
            e.preventDefault();
            status = $(this).data('status');
            sh(status);
        });

        function sh(status) {
            $('.cardInstansi').each(function() {
                if ($(this).hasClass(status) || status == 'semua') {
                    $(this).show().removeClass('d-none');
                } else {
                    $(this).hide().addClass('d-none');
                }
            });
            $grid.masonry('layout');
        }
    })
</script>
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>
<?= $this->endSection(); ?>