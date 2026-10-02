<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= csrf_meta() ?>
    <link rel="icon" href="/assets/logo-pln.png">

    <title><?= isset($title) && $title != null ? $title : 'title belum diset' ?></title>

    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Font Awesome Icons -->
    <script src="/assets/js/fontawesome-kit.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="/assets/css/material-dashboard.css?v=3.0.5" rel="stylesheet" />

    <?= $this->renderSection('topsc') ?>

    <style>
        .dataTables_info {
            font-size: 14px;
            color: #6c757d;
        }

        .page-item .page-link,
        .page-item span {
            width: 30px;
            height: 30px;
        }
        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
            color: #7b809a;
        }
        .input-group.input-group-outline .form-control {
            padding-right: 40px !important;
        }

        /* Prevent Google Translate from breaking icons */
        .notranslate, [translate="no"] {
            unicode-bidi: isolate !important;
        }

        /* Mobile Adjustments */
        @media (max-width: 575.98px) {
            body {
                overflow-x: hidden !important;
            }
            .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            .card .card-body {
                padding: 1rem 0.75rem !important;
            }
            .navbar-main {
                margin-left: 5px !important;
                margin-right: 5px !important;
            }
            .table thead th {
                padding: 0.5rem 0.3rem !important;
                font-size: 0.6rem !important;
            }
            .table tbody td {
                padding: 0.5rem 0.3rem !important;
                font-size: 0.7rem !important;
            }
            .btn {
                padding: 0.4rem 0.8rem !important;
                font-size: 0.7rem !important;
            }
            .sidenav {
                z-index: 1050 !important;
                width: 250px !important;
            }
            .sidenav .navbar-brand {
                padding: 1.5rem 1rem 1rem 1rem !important;
                width: calc(100% - 40px);
            }
            .sidenav .nav-link {
                margin-left: 0.5rem !important;
                margin-right: 0.5rem !important;
                padding: 0.75rem 1rem !important;
                display: flex !important;
                align-items: center !important;
            }
            .sidenav .nav-link div {
                width: 32px !important;
                height: 32px !important;
                min-width: 32px !important;
                flex-shrink: 0 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .sidenav .nav-link-text {
                margin-left: 0.25rem !important;
                font-size: 0.85rem !important;
                white-space: normal !important;
                line-height: 1.2 !important;
            }
            .sidenav-header {
                height: auto !important;
                min-height: 80px !important;
                position: relative;
            }
            #iconSidenav {
                z-index: 1060;
                top: 1rem !important;
                right: 1rem !important;
                font-size: 1.5rem !important;
                opacity: 0.6 !important;
                padding: 10px !important;
            }
            .navbar-main {
                z-index: 1040 !important;
                margin-top: 0.5rem !important;
                margin-left: 0.5rem !important;
                margin-right: 0.5rem !important;
            }
            .breadcrumb {
                font-size: 0.75rem !important;
            }
            /* Table Adjustments */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .table td, .table th {
                white-space: nowrap;
            }
            .table .badge, .table .btn {
                margin-bottom: 2px;
            }
        }
    </style>
</head>

<?php 
    if (in_groups('siswa')) {
        $bgColor = 'bg-success';
    } elseif (in_groups('pembimbing')) {
        $bgColor = 'bg-info';
    } elseif (in_groups('admin')) {
        $bgColor = 'bg-dark';
    } else {
        $bgColor = 'bg-danger';
    }
?>

<body class="g-sidenav-show bg-gray-200">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 <?= $bgColor ?>" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex align-items-center" href="/">
                <img src="/assets/logo-pln.png" class="navbar-brand-img" style="height: 40px;" alt="main_logo">
                <div class="ms-2">
                    <span class="font-weight-bold text-white text-xs d-block" style="line-height: 1.2;">SIMAGANG</span>
                    <span class="font-weight-bold text-white text-xxs d-block" style="line-height: 1.2;">PLN ASAME</span>
                </div>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Main</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="/">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10" translate="no">dashboard</i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'pesan') ? 'active bg-gradient-primary' : '' ?>" href="/pesan">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10" translate="no">forum</i>
                        </div>
                        <span class="nav-link-text ms-1">Pesan</span>
                        <?php
                        $pesanModel = new \App\Models\PesanModel();
                        $unreadCount = $pesanModel->countUnread(user_id());
                        if ($unreadCount > 0): ?>
                            <span class="badge badge-sm bg-danger ms-auto" id="sidebar-unread-badge"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if (in_groups('siswa')) : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/tempat">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">apartment</i>
                            </div>
                            <span class="nav-link-text ms-1">Divisi Magang</span>
                        </a>
                    </li>
                <?php endif ?>

                <?php if (in_groups('admin')) : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/application">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">note_add</i>
                            </div>
                            <span class="nav-link-text ms-1">Pendaftaran Siswa</span>
                        </a>
                    </li>
                <?php endif ?>

                <?php if (in_groups('siswa')) : ?>
                    <hr class="horizontal light">
                    <li class="nav-item">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Kegiatanku</h6>
                    </li>
                    <?php if (!empty(isAccepted(getSidByUid(user_id()))) || isEventFinished(user_id())) : ?>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/nilai">
                                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10" translate="no">confirmation_number</i>
                                </div>
                                <span class="nav-link-text ms-1">Nilai</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="/kehadiran">
                                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10" translate="no">format_list_numbered</i>
                                </div>
                                <span class="nav-link-text ms-1">Daftar Hadir</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'logbookku') ? 'active bg-gradient-primary' : '' ?>" href="/logbookku">
                                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10" translate="no">book</i>
                                </div>
                                <span class="nav-link-text ms-1">Laporan Logbook</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'laporan-akhir') ? 'active bg-gradient-primary' : '' ?>" href="/laporan-akhir">
                                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10" translate="no">assignment_turned_in</i>
                                </div>
                                <span class="nav-link-text ms-1">Laporan Akhir</span>
                            </a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link text-white" href="/application">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10" translate="no">fact_check</i>
                            </div>
                            <span class="nav-link-text ms-1">Status Pengajuan</span>
                        </a>
                    </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'materi-k3') ? 'active bg-gradient-primary' : '' ?>" href="/materi-k3">
                                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10" translate="no">health_and_safety</i>
                                </div>
                                <span class="nav-link-text ms-1">Materi Safety & K3</span>
                            </a>
                        </li>
                    <?php endif ?>
                <?php endif ?>

                <hr class="horizontal light">
                <li class="nav-item">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">
                        <?php if (in_groups('admin')) : ?>
                            Manajemen Data
                        <?php elseif (in_groups('pembimbing') || in_groups('dosen_pembimbing')) : ?>
                            Bimbingan Siswa
                        <?php endif ?>
                    </h6>
                </li>

                <?php if (in_groups('admin')) : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/man/tempat">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">corporate_fare</i>
                            </div>
                            <span class="nav-link-text ms-1">Manajemen Divisi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/man/pembimbing">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">supervisor_account</i>
                            </div>
                            <span class="nav-link-text ms-1">Manajemen Pemb. Lapangan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/man/dosen">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">school</i>
                            </div>
                            <span class="nav-link-text ms-1">Manajemen Dosen Pemb.</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/man/siswa">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <span class="nav-link-text ms-1">Manajemen Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'materi-k3') ? 'active bg-gradient-primary' : '' ?>" href="/materi-k3">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10" translate="no">health_and_safety</i>
                            </div>
                            <span class="nav-link-text ms-1">Manajemen Materi K3</span>
                        </a>
                    </li>

                    <hr class="horizontal light">
                    <li class="nav-item">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Laporan</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/laporan">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">description</i>
                            </div>
                            <span class="nav-link-text ms-1">Laporan</span>
                        </a>
                    </li>

                    <hr class="horizontal light">
                    <li class="nav-item">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Pengaturan</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/settings">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">settings</i>
                            </div>
                            <span class="nav-link-text ms-1">Pengaturan</span>
                        </a>
                    </li>
                <?php endif ?>

                <?php if (in_groups('pembimbing')) : ?>
                    <?php if (empty(getInstansiByPid(user_id()))) : ?>
                        <div class="card card-body mx-4 mt-2">
                            <div class="text-center text-sm">
                                Siswa belum terdaftar
                            </div>
                        </div>
                    <?php else : ?>
                        <?php $i = 1 ?>
                        <?php foreach (getInstansiByPid(user_id()) as $ins) : ?>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="/logbook/<?= $ins->id ?>">
                                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                        <i class="material-icons opacity-10" translate="no">business</i>
                                    </div>
                                    <span class="nav-link-text text-wrap ms-1"><?= $ins->nama ?></span>
                                </a>
                            </li>
                        <?php endforeach ?>
                    <?php endif ?>
                <?php endif ?>

                <?php if (in_groups('dosen_pembimbing')) : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'bimbingan-dosen') ? 'active bg-gradient-primary' : '' ?>" href="<?= base_url('/bimbingan-dosen') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">group</i>
                            </div>
                            <span class="nav-link-text ms-1">Mahasiswa Bimbingan</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb" class="d-none d-sm-block">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm">
                            <a class="opacity-5 text-dark" href="<?= base_url() ?>">Home</a>
                        </li>
                        <?php $ak = array_keys($breadcrumb);
                        $last_key = end($ak); ?>
                        <?php foreach ($breadcrumb as $k => $b) : ?>
                            <li class="breadcrumb-item text-sm <?= $k == $last_key ? 'text-dark active' : '' ?>" aria-current="page">
                                <a class="<?= $k == $last_key ? '' : 'opacity-5 text-dark' ?>"><?= $b ?></a>
                            </li>
                        <?php endforeach ?>
                    </ol>
                    <h6 class="font-weight-bolder mb-0"><?= isset($page_title) && $page_title != null ? $page_title : 'Page title belum di setting' ?></h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-5 right" id="navbar">
                    <div class="ms-auto pe-md-3 d-flex align-items-center">
                        <ul class="navbar-nav  justify-content-end">
                            <!-- Pesan Notification Badge -->
                            <li class="nav-item mt-1 ps-2">
                                <a href="/pesan" class="nav-link text-body p-0 position-relative" title="Pesan">
                                    <i class="material-icons cursor-pointer" translate="no">forum</i>
                                    <?php
                                    $pesanModel2 = new \App\Models\PesanModel();
                                    $unreadNav   = $pesanModel2->countUnread(user_id());
                                    ?>
                                    <span id="navbar-unread-badge" 
                                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                          style="font-size: 0.6rem; padding: 2px 5px; <?= $unreadNav == 0 ? 'display:none;' : '' ?>">
                                        <?= $unreadNav ?: '' ?>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item dropdown mt-1 ps-4">
                                <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="accountMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-icons cursor-pointer">
                                        account_circle
                                    </i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n4" aria-labelledby="accountMenu">
                                    <li>
                                        <div class="dropdown-header text-sm text-muted mb-0">
                                            <div class="text-capitalize fw-bold">Hello, <?= user()->username ?></div>
                                            <?php if (in_groups('siswa')) : ?>
                                                <div class="text-xs">NIM : <?= getNimByUid() ?></div>
                                            <?php endif ?>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="/profile">
                                            <div class="d-flex align-items-center py-1">
                                                <span class="material-icons">person</span>
                                                <div class="ms-2">
                                                    <h6 class="text-sm font-weight-normal my-auto">
                                                        Profile
                                                    </h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="/profile#password">
                                            <div class="d-flex align-items-center py-1">
                                                <span class="material-icons">key</span>
                                                <div class="ms-2">
                                                    <h6 class="text-sm font-weight-normal my-auto">
                                                        Password
                                                    </h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="/logout">
                                            <div class="d-flex align-items-center py-1">
                                                <span class="material-icons text-danger">logout</span>
                                                <div class="ms-2">
                                                    <h6 class="text-sm text-danger font-weight-normal my-auto">
                                                        Log Out
                                                    </h6>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <!-- Page-Content -->
        <div class="container-fluid py-2">
            <div class="card card-body mb-4 d-block d-sm-none">
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                            <li class="breadcrumb-item text-sm">
                                <a class="opacity-5 text-dark" href="<?= base_url() ?>">Home</a>
                            </li>
                            <?php $ak = array_keys($breadcrumb);
                            $last_key = end($ak); ?>
                            <?php foreach ($breadcrumb as $k => $b) : ?>
                                <li class="breadcrumb-item text-sm <?= $k == $last_key ? 'text-dark active' : '' ?>" aria-current="page">
                                    <a class="<?= $k == $last_key ? '' : 'opacity-5 text-dark' ?>"><?= $b ?></a>
                                </li>
                            <?php endforeach ?>
                        </ol>
                        <h6 class="font-weight-bolder mb-0"><?= isset($page_title) && $page_title != null ? $page_title : 'Page title belum di setting' ?></h6>
                    </nav>
                </div>
            </div>



            <?= $this->renderSection('content'); ?>
        </div>

        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-4 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                                document.write(new Date().getFullYear())
                            </script> -
                            Sistem Informasi Siswa PKL
                        </div>
                    </div>
                </div>
        </footer>
    </main>

    <!--   Core JS Files   -->
    <script src="/assets/js/jquery-3.6.3.min.js"></script>
    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/js/material-dashboard.min.js?v=3.0.5"></script>
    <script>
        $(document).ready(function() {
            // Setup AJAX globally to send CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="<?= csrf_header() ?>"]').attr('content')
                }
            });

            // Global fix for Google Translate breaking icons
            $('.material-icons, .material-icons-round, .material-icons-outlined, .material-icons-sharp, .fas, .fab, .far').attr('translate', 'no').addClass('notranslate');
            
            $(document).on('click', '.password-toggle-icon', function() {
                const icon = $(this).find('i');
                const input = $(this).siblings('input');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
        });

        // Click outside to close sidebar on mobile
        document.addEventListener('click', function(event) {
            const body = document.getElementsByTagName('body')[0];
            const sidenav = document.getElementById('sidenav-main');
            const iconSidenav = document.getElementById('iconSidenav');
            const isClickInsideSidenav = sidenav.contains(event.target);
            const isClickInsideNavbarToggler = document.getElementById('iconNavbarSidenav')?.contains(event.target);

            if (body.classList.contains('g-sidenav-pinned') && !isClickInsideSidenav && !isClickInsideNavbarToggler) {
                if (window.innerWidth < 1200) {
                    iconSidenav.click(); // Trigger the close action
                }
            }
        });
    </script>

    <?= $this->renderSection('bottomsc'); ?>
</body>

</html>