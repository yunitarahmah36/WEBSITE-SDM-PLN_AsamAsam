<?= $this->extend('main'); ?>

<?= $this->section('content'); ?>

<!-- =========================================================
     FILTER DIVISI MAGANG
     ========================================================= -->

<div class="filter-card">

    <div class="filter-title">
        Filter Divisi Magang
    </div>

    <div class="row align-items-end">

        <!-- FILTER STATUS -->
        <div class="col-12 col-md-4 mb-3 mb-md-0">

            <label for="filterStatus" class="filter-label">
                Filter Status
            </label>

            <div class="filter-select-wrapper">

                <span class="material-icons filter-icon">
                    filter_list
                </span>

                <select id="filterStatus" class="filter-select">

                    <option value="semua">
                        Semua Divisi
                    </option>

                    <option value="buka">
                        Divisi Buka
                    </option>

                    <option value="tutup">
                        Divisi Tutup
                    </option>

                </select>

            </div>

        </div>


        <!-- SEARCH -->
        <div class="col-12 col-md-8">

            <label for="search" class="filter-label">
                Cari Divisi
            </label>

            <div class="search-wrapper">

                <span class="material-icons search-icon">
                    search
                </span>

                <input
                    type="text"
                    id="search"
                    class="search-input"
                    placeholder="Cari divisi magang..."
                    autocomplete="off">

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     ALERT PROFIL BELUM LENGKAP
     ========================================================= -->
<?php if (in_groups('siswa') && (!isset($is_profile_complete) || !$is_profile_complete)) : ?>
    <div class="alert alert-warning text-white border-radius-xl p-4 mb-4 shadow-lg animate__animated animate__pulse">
        <div class="d-flex align-items-center">
            <i class="material-icons me-3 fs-1">account_box</i>
            <div>
                <h5 class="text-white mb-1">Perhatian: Profil Anda Belum Lengkap!</h5>
                <p class="text-sm mb-0 opacity-9">
                    Anda belum dapat mendaftar divisi magang sebelum melengkapi data profil diri (seperti NIK, Tempat & Tanggal Lahir, Jenis Kelamin, dan No HP Emergency).
                </p>
                <a href="/profile" class="btn btn-sm btn-white text-warning font-weight-bold mb-0 mt-3">Lengkapi Profil Sekarang <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- =========================================================
     DAFTAR DIVISI
     ========================================================= -->

<div class="row row-instansi mb-5">

    <?php foreach ($tempat as $t) : ?>

        <?php

        if (filter_var($t->foto, FILTER_VALIDATE_URL)) {

            $foto = $t->foto;

        } else {

            $foto = '/assets/img/tempat_magang/' . $t->foto;

        }

        ?>

        <div
            class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3 mb-4 cardInstansi <?= strtolower($t->status) ?>"
            data-status="<?= strtolower($t->status) ?>"
        >

            <div
                class="card compact-card"
                data-animation="<?= initCheck(user_id(), $t->id) && $t->status == 'buka' ? 'true' : 'false' ?>"
            >

                <div class="d-flex align-items-stretch card-content">


                    <!-- =================================================
                         FOTO
                         ================================================= -->

                    <div class="card-image-wrapper">

                        <img
                            src="<?= $foto ?>"
                            alt="<?= esc($t->nama) ?>"
                            class="card-image"
                        >

                    </div>


                    <!-- =================================================
                         INFORMASI
                         ================================================= -->

                    <div class="card-info">

                        <div>

                            <!-- NAMA + STATUS -->
                            <div class="card-header-info">

                                <span class="company-name">

                                    <?= esc($t->nama) ?>

                                </span>


                                <span
                                    class="status-badge
                                    <?= getSlotAvailable($t->id) == 0
                                        ? 'status-full'
                                        : ($t->status == 'buka'
                                            ? 'status-open'
                                            : 'status-closed') ?>"
                                >

                                    <?= getSlotAvailable($t->id) == 0
                                        ? 'FULL'
                                        : strtoupper($t->status) ?>

                                </span>

                            </div>


                            <!-- DESKRIPSI -->
                            <div class="full-desc">

                                <?= $t->deskripsi ?>

                            </div>

                        </div>


                        <!-- =================================================
                             DETAIL
                             ================================================= -->

                        <div
                            class="detail-panel"
                            id="detail-<?= $t->id ?>"
                        >

                            <?php if (!empty($t->email)) : ?>

                                <div class="detail-item">

                                    <i class="fa fa-envelope text-primary"></i>

                                    <span>
                                        <?= esc($t->email) ?>
                                    </span>

                                </div>

                            <?php endif; ?>


                            <?php if (!empty($t->hp)) : ?>

                                <div class="detail-item">

                                    <i class="fa fa-phone text-success"></i>

                                    <span>
                                        <?= esc($t->hp) ?>
                                    </span>

                                </div>

                            <?php endif; ?>


                            <?php if (!empty($t->alamat)) : ?>

                                <div class="detail-item">

                                    <i class="fa fa-map-marker-alt text-danger"></i>

                                    <span>
                                        <?= esc($t->alamat) ?>
                                    </span>

                                </div>

                            <?php endif; ?>


                            <div class="detail-item">

                                <i class="fa fa-users text-info"></i>

                                <span>

                                    Kuota tersisa:
                                    <strong>
                                        <?= getSlotAvailable($t->id) ?>
                                    </strong>

                                </span>

                            </div>

                        </div>


                        <!-- =================================================
                             FOOTER CARD
                             ================================================= -->

                        <div class="card-footer-info">


                            <!-- PEMBIMBING + DETAIL -->

                            <div class="supervisor-wrapper">

                                <span
                                    class="supervisor-name"
                                    title="<?= esc($t->pembimbing) ?>"
                                >

                                    <i class="fa fa-user-tie"></i>

                                    <?= esc($t->pembimbing) ?>

                                </span>


                                <button
                                    type="button"
                                    class="btn-toggle-detail"
                                    data-tid="<?= $t->id ?>"
                                >

                                    <i class="fa fa-chevron-down icon-toggle"></i>

                                    Detail

                                </button>

                            </div>


                            <!-- =================================================
                                 STATUS PENDAFTARAN
                                 ================================================= -->

                            <div class="registration-wrapper">

                                <?php

                                $sid = getSidByUid(user_id());

                                $appModel = new \App\Models\ApplicationModel();


                                /*
                                 * Ambil lamaran siswa untuk divisi
                                 * yang sedang ditampilkan.
                                 */

                                $thisApp = $appModel
                                    ->where([
                                        'id_siswa' => $sid,
                                        'id_tempat' => $t->id
                                    ])
                                    ->first();


                                /*
                                 * Hitung jumlah lamaran pending.
                                 */

                                $pendingApps = $appModel
                                    ->where([
                                        'id_siswa' => $sid,
                                        'status' => 'pending'
                                    ])
                                    ->findAll();

                                $pendingCount = count($pendingApps);


                                /*
                                 * Hanya divisi ini yang dianggap
                                 * Terdaftar apabila status diterima.
                                 */

                                $acceptedThis = $thisApp
                                    && in_array(
                                        $thisApp->status,
                                        ['accepted', 'selesai']
                                    );

                                ?>


                                <?php if (!logged_in()) : ?>

                                    <button
                                        type="button"
                                        class="btn-daftar btn-login-first"
                                        data-instansi="<?= esc($t->nama) ?>"
                                        <?= $t->status == 'tutup' || getSlotAvailable($t->id) == 0
                                            ? 'disabled'
                                            : '' ?>
                                    >
                                        <i class="fa fa-paper-plane me-1"></i> Daftar
                                    </button>

                                <?php elseif ($acceptedThis) : ?>

                                    <span class="registration-badge badge-accepted">
                                        <i class="fa fa-check-circle me-1"></i> Terdaftar
                                    </span>

                                <?php elseif ($thisApp && $thisApp->status == 'pending') : ?>

                                    <span class="registration-badge badge-pending">
                                        Pending
                                    </span>

                                <?php elseif (
                                    $thisApp &&
                                    in_array(
                                        $thisApp->status,
                                        ['rejected', 'reject by system']
                                    )
                                ) : ?>

                                    <span class="registration-badge badge-rejected">
                                        Ditolak
                                    </span>

                                <?php elseif ($pendingCount >= 2) : ?>

                                    <span class="registration-badge badge-limit">
                                        Maks 2
                                    </span>

                                <?php elseif ($t->status == 'tutup' || getSlotAvailable($t->id) == 0) : ?>

                                    <span class="registration-badge badge-full">
                                        <?= $t->status == 'tutup' ? 'Tutup' : 'Penuh' ?>
                                    </span>

                                <?php elseif (in_groups('siswa') && (!isset($is_profile_complete) || !$is_profile_complete)) : ?>

                                    <button
                                        type="button"
                                        class="btn-daftar btn-incomplete-profile"
                                        data-instansi="<?= esc($t->nama) ?>"
                                        title="Lengkapi profil diri terlebih dahulu untuk mendaftar"
                                    >
                                        <i class="fa fa-lock me-1"></i> Daftar
                                    </button>

                                <?php else : ?>

                                    <button
                                        type="button"
                                        class="btn-daftar"
                                        data-instansi="<?= esc($t->nama) ?>"
                                        data-tid="<?= $t->id ?>"
                                    >
                                        <i class="fa fa-paper-plane me-1"></i> Daftar
                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

</div>


<?= $this->endSection(); ?>


<?= $this->section('bottomsc'); ?>


<style>

/* =========================================================
   HEADER
   ========================================================= */

.page-header-section {

    display: flex;
    justify-content: space-between;
    align-items: center;

    margin-bottom: 28px;

}

.breadcrumb-custom {

    display: flex;
    align-items: center;
    gap: 9px;

    font-size: 14px;

    color: #9aa5b5;

    margin-bottom: 5px;

}

.breadcrumb-custom .separator {

    color: #b5bdc9;

}

.breadcrumb-custom .active {

    color: #344767;

    font-weight: 500;

}

.page-title {

    margin: 0;

    color: #344767;

    font-size: 18px;

    font-weight: 700;

}


/* =========================================================
   FILTER CARD
   ========================================================= */

.filter-card {

    background: #ffffff;

    border-radius: 16px;

    padding: 24px 26px;

    margin-bottom: 30px;

    border: 1px solid #edf0f4;

    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);

}

.filter-title {

    color: #344767;

    font-size: 17px;

    font-weight: 700;

    margin-bottom: 22px;

}

.filter-label {

    display: block;

    margin-bottom: 8px;

    color: #344767;

    font-size: 13px;

    font-weight: 600;

}


/* =========================================================
   DROPDOWN
   ========================================================= */

.filter-select-wrapper {

    position: relative;

    width: 100%;

}

.filter-icon {

    position: absolute;

    left: 17px;

    top: 50%;

    transform: translateY(-50%);

    font-size: 19px;

    color: #8795a8;

    pointer-events: none;

    z-index: 2;

}

.filter-select {

    width: 100%;

    height: 48px;

    border: 1px solid #dce3eb;

    border-radius: 10px;

    background-color: #ffffff;

    color: #344767;

    font-size: 14px;

    font-weight: 500;

    padding: 0 42px 0 46px;

    outline: none;

    cursor: pointer;

    appearance: auto;

    transition: all 0.2s ease;

}

.filter-select:hover {

    border-color: #c7d0dc;

}

.filter-select:focus {

    border-color: #5e72e4;

    box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10);

}


/* =========================================================
   SEARCH
   ========================================================= */

.search-wrapper {

    position: relative;

    width: 100%;

}

.search-icon {

    position: absolute;

    left: 17px;

    top: 50%;

    transform: translateY(-50%);

    font-size: 20px;

    color: #8795a8;

    pointer-events: none;

    z-index: 2;

}

.search-input {

    width: 100%;

    height: 48px;

    border: 1px solid #dce3eb;

    border-radius: 10px;

    background: #ffffff;

    color: #344767;

    font-size: 14px;

    padding: 0 16px 0 46px;

    outline: none;

    transition: all 0.2s ease;

}

.search-input::placeholder {

    color: #9aa7b8;

}

.search-input:hover {

    border-color: #c7d0dc;

}

.search-input:focus {

    border-color: #5e72e4;

    box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10);

}


/* =========================================================
   GRID DIVISI
   ========================================================= */

.row-instansi {

    margin-left: -10px !important;

    margin-right: -10px !important;

}

.cardInstansi {

    padding-left: 10px !important;

    padding-right: 10px !important;

}


/* =========================================================
   CARD
   ========================================================= */

.compact-card {

    width: 100%;

    min-height: 235px;

    margin: 0 !important;

    border: 1px solid #edf0f4;

    border-radius: 12px;

    overflow: hidden;

    background: #ffffff;

    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;

}

.compact-card:hover {

    transform: translateY(-3px);

    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.10);

}

.card-content {

    width: 100%;

    min-height: 235px;

}


/* =========================================================
   IMAGE
   ========================================================= */

.card-image-wrapper {

    width: 42%;

    min-width: 42%;

    min-height: 235px;

    background: #f3f4f6;

    overflow: hidden;

}

.card-image {

    display: block;

    width: 100%;

    height: 100%;

    min-height: 235px;

    object-fit: cover;

}


/* =========================================================
   CARD INFO
   ========================================================= */

.card-info {

    width: 58%;

    min-width: 0;

    padding: 14px 12px;

    display: flex;

    flex-direction: column;

    justify-content: space-between;

}

.card-header-info {

    display: flex;

    align-items: flex-start;

    justify-content: space-between;

    gap: 6px;

    margin-bottom: 7px;

}

.company-name {

    display: block;

    min-width: 0;

    color: #344767;

    font-size: 14px;

    font-weight: 700;

    line-height: 1.3;

}


/* =========================================================
   STATUS
   ========================================================= */

.status-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    min-height: 24px;

    padding: 4px 8px;

    border-radius: 7px;

    font-size: 10px;

    font-weight: 700;

    white-space: nowrap;

}

.status-open {

    color: #188038;

    background: #d9f1df;

}

.status-closed {

    color: #e53935;

    background: #ffd9d7;

}

.status-full {

    color: #9a6700;

    background: #fff0c2;

}


/* =========================================================
   DESKRIPSI
   ========================================================= */

.full-desc {

    height: 92px;

    overflow-y: auto;

    padding-right: 5px;

    color: #7783a0;

    font-size: 13px;

    line-height: 1.7;

    border-bottom: 1px dashed #e2e8f0;

}

.full-desc::-webkit-scrollbar {

    width: 4px;

}

.full-desc::-webkit-scrollbar-track {

    background: transparent;

}

.full-desc::-webkit-scrollbar-thumb {

    background: #cfd5dd;

    border-radius: 5px;

}


/* =========================================================
   DETAIL
   ========================================================= */

.detail-panel {

    display: none;

    margin-top: 8px;

    padding-top: 7px;

    border-top: 1px solid #edf0f4;

}

.detail-panel.open {

    display: block;

}

.detail-item {

    display: flex;

    align-items: flex-start;

    gap: 7px;

    margin-bottom: 5px;

    color: #667085;

    font-size: 11px;

    line-height: 1.4;

}

.detail-item i {

    width: 14px;

    flex-shrink: 0;

    margin-top: 2px;

}


/* =========================================================
   FOOTER CARD
   ========================================================= */

.card-footer-info {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    gap: 5px;

    margin-top: 9px;

}

.supervisor-wrapper {

    min-width: 0;

    flex: 1;

}

.supervisor-name {

    display: block;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    color: #7b879d;

    font-size: 11px;

    margin-bottom: 4px;

}

.supervisor-name i {

    margin-right: 4px;

}

.btn-toggle-detail {

    display: inline-flex;

    align-items: center;

    gap: 3px;

    padding: 0;

    border: 0;

    background: transparent;

    color: #9aa1aa;

    font-size: 10px;

    font-weight: 600;

    cursor: pointer;

}

.btn-toggle-detail:hover {

    color: #344767;

}

.icon-toggle {

    transition: transform 0.2s ease;

}

.icon-toggle.rotated {

    transform: rotate(180deg);

}


/* =========================================================
   BUTTON DAFTAR
   ========================================================= */

.registration-wrapper {

    flex-shrink: 0;

}

.btn-daftar {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 74px;

    height: 30px;

    padding: 0 12px;

    border: 0;

    border-radius: 8px;

    background: linear-gradient(135deg, #2dce89 0%, #28a745 100%);

    color: #ffffff;

    font-size: 10px;

    font-weight: 700;

    cursor: pointer;

    box-shadow: 0 3px 8px rgba(40, 167, 69, 0.28);

    transition: all 0.2s ease;

}

.btn-daftar:hover {

    background: linear-gradient(135deg, #28a745 0%, #218838 100%);

    color: #ffffff;

    box-shadow: 0 5px 12px rgba(40, 167, 69, 0.38);

    transform: translateY(-1px);

}

.btn-daftar:disabled {

    opacity: 0.55;

    cursor: not-allowed;

    transform: none;

    box-shadow: none;

}

.btn-daftar.btn-incomplete-profile {

    background: linear-gradient(135deg, #fb8c00 0%, #f57c00 100%) !important;

    border-color: #fb8c00 !important;

    box-shadow: 0 3px 8px rgba(251, 140, 0, 0.32) !important;

    color: #ffffff !important;

    cursor: pointer;

}

.btn-daftar.btn-incomplete-profile:hover {

    background: linear-gradient(135deg, #f57c00 0%, #e65100 100%) !important;

    border-color: #f57c00 !important;

    box-shadow: 0 5px 12px rgba(251, 140, 0, 0.45) !important;

    transform: translateY(-1px);

}


/* =========================================================
   REGISTRATION BADGE
   ========================================================= */

.registration-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 62px;

    height: 28px;

    padding: 0 8px;

    border-radius: 7px;

    font-size: 9px;

    font-weight: 700;

    white-space: nowrap;

}

.badge-accepted {

    color: #0f5132;

    background: #d1e7dd;

    border: 1px solid #a3cfbb;

}

.badge-pending {

    color: #0277bd;

    background: #d9efff;

}

.badge-rejected {

    color: #d93025;

    background: #ffd9d7;

}

.badge-limit {

    color: #667085;

    background: #eaecf0;

}

.badge-full {

    color: #9a6700;

    background: #fff0c2;

}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1199px) {

    .card-image-wrapper {

        width: 40%;

        min-width: 40%;

    }

    .card-info {

        width: 60%;

    }

}

@media (max-width: 767px) {

    .filter-card {

        padding: 20px;

        margin-bottom: 24px;

    }

    .filter-title {

        margin-bottom: 18px;

    }

    .cardInstansi {

        padding-left: 8px !important;

        padding-right: 8px !important;

    }

    .row-instansi {

        margin-left: -8px !important;

        margin-right: -8px !important;

    }

    .compact-card,
    .card-content {

        min-height: 220px;

    }

    .card-image-wrapper {

        min-height: 220px;

    }

    .card-image {

        min-height: 220px;

    }

}

@media (max-width: 575px) {

    .page-header-section {

        margin-bottom: 20px;

    }

    .filter-card {

        padding: 18px;

    }

    .compact-card,
    .card-content {

        min-height: 210px;

    }

    .card-image-wrapper {

        min-height: 210px;

    }

    .card-image {

        min-height: 210px;

    }

}

</style>


<!-- =========================================================
     SWEET ALERT
     ========================================================= -->

<script src="/assets/js/plugins/sweetalert.min.js"></script>


<script>

$(document).ready(function() {


    /* =========================================================
       FILTER STATUS
       ========================================================= */

    var status = 'semua';


    $('#filterStatus').on('change', function() {

        status = $(this).val();

        filterCards();

    });


    /* =========================================================
       SEARCH
       ========================================================= */

    $('#search').on('keyup', function() {

        filterCards();

    });


    /* =========================================================
       FILTER CARD
       ========================================================= */

    function filterCards() {

        var keyword = $('#search')
            .val()
            .toLowerCase()
            .trim();


        $('.cardInstansi').each(function() {

            var card = $(this);

            var cardStatus = card
                .data('status')
                .toString()
                .toLowerCase();


            var cardText = card
                .text()
                .toLowerCase();


            var statusMatch =
                status === 'semua' ||
                cardStatus === status;


            var searchMatch =
                keyword === '' ||
                cardText.indexOf(keyword) !== -1;


            if (statusMatch && searchMatch) {

                card.removeClass('d-none');

            } else {

                card.addClass('d-none');

            }

        });

    }


    /* =========================================================
       DETAIL
       ========================================================= */

    $(document).on(
        'click',
        '.btn-toggle-detail',
        function() {

            var tid = $(this).data('tid');

            var panel = $('#detail-' + tid);

            var icon = $(this).find('.icon-toggle');


            if (panel.hasClass('open')) {

                panel
                    .removeClass('open')
                    .hide();

                icon.removeClass('rotated');

                $(this).html(
                    '<i class="fa fa-chevron-down icon-toggle"></i> Detail'
                );

            } else {

                panel
                    .addClass('open')
                    .show();

                icon.addClass('rotated');

                $(this).html(
                    '<i class="fa fa-chevron-up icon-toggle rotated"></i> Sembunyikan'
                );

            }

        }
    );


    /* =========================================================
       TOMBOL DAFTAR
       ========================================================= */

    $(document).on(
        'click',
        '.btn-incomplete-profile',
        function() {
            var instansi = $(this).data('instansi');
            Swal.fire({
                title: 'Profil Belum Lengkap!',
                html: 'Anda belum dapat mendaftar magang di <b>' + instansi + '</b>.<br><br>Silakan lengkapi data profil diri Anda (seperti NIK, Tempat & Tanggal Lahir, Jenis Kelamin, dan No HP Emergency) terlebih dahulu pada menu Profil.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#344767',
                cancelButtonColor: '#8392ab',
                confirmButtonText: '<i class="fa fa-user-edit me-1"></i> Lengkapi Profil Sekarang',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = '/profile';
                }
            });
        }
    );

    $(document).on(
        'click',
        '.btn-daftar:not(.btn-login-first):not(.btn-incomplete-profile)',
        function() {

            var instansi = $(this).data('instansi');

            var tid = $(this).data('tid');


            var csrfHeader =
                '<?= csrf_header() ?>';

            var csrfHash =
                $('meta[name="' + csrfHeader + '"]')
                .attr('content');


            Swal.fire({

                title: 'Daftar Magang',

                text:
                    'Apakah anda yakin ingin mendaftar magang di '
                    + instansi
                    + '?',

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#188038',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Daftar',

                cancelButtonText: 'Batal'

            }).then(function(result) {

                if (result.isConfirmed) {

                    $.ajax({

                        url: '/tempat/daftar',

                        type: 'post',

                        headers: {

                            [csrfHeader]:
                                csrfHash

                        },

                        data: {

                            tid: tid

                        },

                        success: function(data) {

                            if (data.success) {

                                Swal.fire({

                                    title: 'Berhasil',

                                    text:
                                        data.message ||
                                        'Anda berhasil mendaftar magang di '
                                        + instansi,

                                    icon: 'success'

                                }).then(function() {

                                    window.location.href =
                                        '/status-lamaran';

                                });

                            } else {

                                Swal.fire({

                                    title: 'Gagal',

                                    text:
                                        data.message ||
                                        'Terjadi kesalahan.',

                                    icon: 'error'

                                });

                            }

                        },

                        error: function(xhr) {

                            // ⭐ DEBUG: Tampilkan pesan asli dari server
                            console.log('===== DEBUG ERROR DAFTAR =====');
                            console.log('Status:', xhr.status);
                            console.log('Response:', xhr.responseText);

                            let title = 'Gagal Mendaftar';
                            let msg   = 'Terjadi kesalahan. Buka Console (F12) untuk detail.';

                            // Coba ambil pesan dari JSON response
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            } 
                            // Kalau bukan JSON, ambil dari HTML
                            else if (xhr.responseText) {
                                let stripped = xhr.responseText
                                    .replace(/<[^>]*>/g, ' ')
                                    .replace(/\s+/g, ' ')
                                    .trim();
                                msg = stripped.substring(0, 500);
                            }

                            // Pesan spesifik per status
                            if (xhr.status === 401) {
                                title = 'Belum Login';
                            } else if (xhr.status === 403) {
                                title = 'Akses Ditolak';
                            } else if (xhr.status === 419) {
                                title = 'CSRF Token Expired';
                                msg = 'Sesi telah berakhir. Silakan reload halaman ini.';
                            } else if (xhr.status === 500) {
                                title = 'Server Error (500)';
                            }

                            Swal.fire({
                                title: title,
                                html: '<div style="text-align:left;font-size:13px;word-break:break-word;max-height:300px;overflow:auto;">' + msg + '</div>',
                                icon: 'error',
                                showCancelButton: true,
                                confirmButtonText: 'Reload Halaman',
                                cancelButtonText: 'Tutup'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });

                        }

                    });

                }

            });

        }
    );


    /* =========================================================
       LOGIN SEBELUM DAFTAR
       ========================================================= */

    $(document).on(
        'click',
        '.btn-login-first',
        function() {

            var instansi =
                $(this).data('instansi');


            Swal.fire({

                title: 'Harus Login',

                text:
                    'Untuk mendaftar magang di '
                    + instansi
                    + ', Anda harus login terlebih dahulu.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#3085d6',

                cancelButtonColor: '#d33',

                confirmButtonText: 'Login Sekarang',

                cancelButtonText: 'Batal'

            }).then(function(result) {

                if (result.isConfirmed) {

                    window.location.href =
                        '/login';

                }

            });

        }
    );


    /* =========================================================
       FILTER AWAL
       ========================================================= */

    filterCards();

});

</script>


<?= $this->endSection(); ?>