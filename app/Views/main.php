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
                align-items: flex-start !important;
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
                font-size: 0.8rem !important;
                white-space: normal !important;
                line-height: 1.3 !important;
                word-break: break-word !important;
                flex: 1 !important;
                min-width: 0 !important;
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

    <!-- Performance & Scroll Optimizations -->
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        .main-content, .sidenav {
            -webkit-overflow-scrolling: touch;
        }

        .navbar-blur {
            backdrop-filter: blur(5px) saturate(150%) !important;
            -webkit-backdrop-filter: blur(5px) saturate(150%) !important;
        }

        [data-scrollbar] {
            overscroll-behavior: contain;
        }
    </style>

    <!-- ============================================= -->
    <!-- PERBAIKAN SIDEBAR: TEKS PANJANG TIDAK TERPOTONG -->
    <!-- ============================================= -->
    <style>
        .sidenav .nav-link {
            align-items: flex-start !important;
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
        }

        .sidenav .nav-link-text {
            white-space: normal !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
            line-height: 1.3 !important;
            flex: 1 !important;
            min-width: 0 !important;
        }

        .sidenav .nav-link-text.text-wrap {
            white-space: normal !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        @media (min-width: 1200px) {
            .sidenav {
                width: 260px !important;
            }
            .sidenav .nav-link-text {
                font-size: 0.85rem !important;
            }
        }

        @media (min-width: 576px) and (max-width: 1199.98px) {
            .sidenav {
                width: 260px !important;
            }
            .sidenav .nav-link-text {
                font-size: 0.85rem !important;
                line-height: 1.3 !important;
            }
        }

        /* ========================================================
           TEMA DASHBOARD PESERTA (WARNA PASTEL SAGE / MINT GREEN)
           ======================================================== */
        body.theme-siswa {
            background-color: #f6faf7 !important;
        }

        body.theme-siswa .sidenav-siswa {
            background-color: #b2e5c7 !important; /* Hijau lebih hidup & segar, tetap soft */
            border: none !important; /* Tanpa garis tepi sesuai permintaan */
            box-shadow: 0 10px 30px rgba(9, 64, 33, 0.10), 0 3px 10px rgba(0, 0, 0, 0.04) !important;
        }

        body.theme-siswa .sidenav-siswa .brand-title {
            color: #043319 !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px;
        }

        body.theme-siswa .sidenav-siswa .brand-sub {
            color: #0a4524 !important;
            font-weight: 700 !important;
        }

        body.theme-siswa .sidenav-siswa .section-title {
            color: #0d4a29 !important;
            font-size: 0.72rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        body.theme-siswa .sidenav-siswa hr.horizontal {
            background-color: transparent !important;
            background-image: linear-gradient(90deg, transparent, rgba(9, 64, 33, 0.22), transparent) !important;
            height: 1px !important;
            opacity: 1 !important;
            border: 0 !important;
            margin: 0.6rem 0.5rem !important;
        }

        body.theme-siswa .sidenav-siswa .nav-link,
        body.theme-siswa .sidenav-siswa .nav-link.text-white {
            color: #0d381f !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            margin: 3px 10px !important;
            padding: 9px 14px !important;
            transition: all 0.2s ease !important;
        }

        body.theme-siswa .sidenav-siswa .nav-link div,
        body.theme-siswa .sidenav-siswa .nav-link i,
        body.theme-siswa .sidenav-siswa .nav-link .material-icons,
        body.theme-siswa .sidenav-siswa .nav-link .nav-link-text {
            color: #0d381f !important;
        }

        body.theme-siswa .sidenav-siswa .nav-link div i,
        body.theme-siswa .sidenav-siswa .nav-link i,
        body.theme-siswa .sidenav-siswa .nav-link .material-icons {
            color: #0d4525 !important;
            font-size: 1.25rem !important;
        }

        body.theme-siswa .sidenav-siswa .nav-link:hover {
            background-color: #9cdbb6 !important;
            color: #033318 !important;
        }

        body.theme-siswa .sidenav-siswa .nav-link:hover *,
        body.theme-siswa .sidenav-siswa .nav-link:hover div,
        body.theme-siswa .sidenav-siswa .nav-link:hover i,
        body.theme-siswa .sidenav-siswa .nav-link:hover .material-icons,
        body.theme-siswa .sidenav-siswa .nav-link:hover .nav-link-text {
            color: #033318 !important;
        }

        /* Active Nav Link in Sidenav */
        body.theme-siswa .sidenav-siswa .nav-link.active,
        body.theme-siswa .sidenav-siswa .nav-link.bg-gradient-primary {
            background-color: #7ece9d !important; /* Warna pill aktif segar & soft */
            border: none !important;
            background-image: none !important;
            color: #033318 !important;
            font-weight: 700 !important;
            box-shadow: 0 3px 8px rgba(9, 64, 33, 0.15) !important;
        }

        body.theme-siswa .sidenav-siswa .nav-link.active *,
        body.theme-siswa .sidenav-siswa .nav-link.active div,
        body.theme-siswa .sidenav-siswa .nav-link.active i,
        body.theme-siswa .sidenav-siswa .nav-link.active .material-icons,
        body.theme-siswa .sidenav-siswa .nav-link.active .nav-link-text,
        body.theme-siswa .sidenav-siswa .nav-link.bg-gradient-primary *,
        body.theme-siswa .sidenav-siswa .nav-link.bg-gradient-primary div,
        body.theme-siswa .sidenav-siswa .nav-link.bg-gradient-primary i,
        body.theme-siswa .sidenav-siswa .nav-link.bg-gradient-primary .material-icons,
        body.theme-siswa .sidenav-siswa .nav-link.bg-gradient-primary .nav-link-text {
            color: #033318 !important;
        }

        /* Breadcrumb & Title in theme-siswa */
        body.theme-siswa .navbar-main .breadcrumb-item a,
        body.theme-siswa .navbar-main .breadcrumb-item {
            color: #64748b !important;
        }
        body.theme-siswa .navbar-main .breadcrumb-item.active {
            color: #0f172a !important;
            font-weight: 600 !important;
        }
        body.theme-siswa .navbar-main nav[aria-label="breadcrumb"] h6 {
            color: #0f172a !important;
            font-size: 1.25rem !important;
        }

        /* User Account Dropdown Menu */
        .navbar-main .dropdown-menu {
            min-width: 185px !important;
            border-radius: 12px !important;
            border: 1px solid rgba(0, 0, 0, 0.08) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
            padding: 8px !important;
        }
        .navbar-main .dropdown-menu .dropdown-header {
            padding: 4px 8px 6px !important;
        }
        .navbar-main .dropdown-menu .dropdown-item {
            padding: 7px 10px !important;
            border-radius: 8px !important;
            font-size: 0.825rem !important;
            font-weight: 500 !important;
            color: #334155 !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.15s ease !important;
        }
        .navbar-main .dropdown-menu .dropdown-item h6 {
            font-size: 0.825rem !important;
            margin: 0 !important;
            font-weight: 500 !important;
            line-height: 1.2 !important;
        }
        .navbar-main .dropdown-menu .dropdown-item .material-icons {
            font-size: 18px !important;
            color: #64748b !important;
            margin-right: 8px !important;
        }
        .navbar-main .dropdown-menu .dropdown-item:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
        .navbar-main .dropdown-menu .dropdown-item:hover .material-icons {
            color: #15803d !important;
        }
        .navbar-main .dropdown-menu .dropdown-item.text-danger:hover,
        .navbar-main .dropdown-menu .dropdown-item:hover .text-danger {
            color: #dc2626 !important;
        }
        .navbar-main .dropdown-menu .dropdown-item:hover .material-icons.text-danger {
            color: #dc2626 !important;
        }

        /* ========================================================
           TEMA DASHBOARD PEMBIMBING (ROYAL BLUE / ELECTRIC BLUE)
           ======================================================== */
        body.theme-pembimbing {
            background-color: #f8fafc !important;
        }

        body.theme-pembimbing .sidenav-pembimbing {
            background: #1865cf !important; /* Royal Blue */
            border: none !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(24, 101, 207, 0.20), 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .brand-title {
            color: #ffffff !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px;
        }

        body.theme-pembimbing .sidenav-pembimbing .brand-sub {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 700 !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .section-title {
            color: rgba(255, 255, 255, 0.72) !important;
            font-size: 0.72rem !important;
            font-weight: 800 !important;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        body.theme-pembimbing .sidenav-pembimbing hr.horizontal {
            background-color: transparent !important;
            background-image: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.28), transparent) !important;
            height: 1px !important;
            opacity: 1 !important;
            border: 0 !important;
            margin: 0.6rem 0.5rem !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .nav-link,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.text-white {
            color: #ffffff !important;
            font-weight: 600 !important;
            border-radius: 10px !important;
            margin: 3px 10px !important;
            padding: 9px 14px !important;
            transition: all 0.2s ease !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .nav-link div,
        body.theme-pembimbing .sidenav-pembimbing .nav-link i,
        body.theme-pembimbing .sidenav-pembimbing .nav-link .material-icons,
        body.theme-pembimbing .sidenav-pembimbing .nav-link .nav-link-text {
            color: #ffffff !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .nav-link div i,
        body.theme-pembimbing .sidenav-pembimbing .nav-link i,
        body.theme-pembimbing .sidenav-pembimbing .nav-link .material-icons {
            color: #ffffff !important;
            font-size: 1.25rem !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .nav-link:hover *,
        body.theme-pembimbing .sidenav-pembimbing .nav-link:hover div,
        body.theme-pembimbing .sidenav-pembimbing .nav-link:hover i,
        body.theme-pembimbing .sidenav-pembimbing .nav-link:hover .material-icons,
        body.theme-pembimbing .sidenav-pembimbing .nav-link:hover .nav-link-text {
            color: #ffffff !important;
        }

        /* Active Nav Link in Sidenav Pembimbing */
        body.theme-pembimbing .sidenav-pembimbing .nav-link.active,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.bg-gradient-primary {
            background-color: #4fa3fb !important; /* Soft bright sky blue pill */
            border: none !important;
            background-image: none !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14) !important;
        }

        body.theme-pembimbing .sidenav-pembimbing .nav-link.active *,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.active div,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.active i,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.active .material-icons,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.active .nav-link-text,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.bg-gradient-primary *,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.bg-gradient-primary div,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.bg-gradient-primary i,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.bg-gradient-primary .material-icons,
        body.theme-pembimbing .sidenav-pembimbing .nav-link.bg-gradient-primary .nav-link-text {
            color: #ffffff !important;
        }

        /* Breadcrumb & Title in theme-pembimbing */
        body.theme-pembimbing .navbar-main .breadcrumb-item a,
        body.theme-pembimbing .navbar-main .breadcrumb-item {
            color: #64748b !important;
        }
        body.theme-pembimbing .navbar-main .breadcrumb-item.active {
            color: #0f172a !important;
            font-weight: 600 !important;
        }
        body.theme-pembimbing .navbar-main nav[aria-label="breadcrumb"] h6 {
            color: #0f172a !important;
            font-size: 1.25rem !important;
            font-weight: 700 !important;
        }

        /* Profile Avatar & Dropdown in theme-pembimbing */
        body.theme-pembimbing .profile-avatar-badge {
            background-color: #1865cf !important;
        }
        body.theme-pembimbing .navbar-main .dropdown-menu .dropdown-item:hover .material-icons {
            color: #1865cf !important;
        }
        body.theme-pembimbing #navbar-unread-badge {
            background-color: #ef4444 !important;
        }

        /* Profile Avatar in theme-siswa */
        body.theme-siswa .profile-avatar-badge {
            background-color: #15803d !important;
        }

        /* ========================================================
           TEMA DASHBOARD ADMIN (DARK SLATE SIDEBAR & CLEAN CONTRAST)
           ======================================================== */
        body.theme-admin {
            background-color: #f1f5f9 !important; /* Netral, tidak terlalu biru agar tidak menyatu */
        }

        body.theme-admin .sidenav-admin,
        .sidenav.sidenav-admin {
            background: #1e293b !important; /* Dark Slate Charcoal, tegas & elegan, kontras dengan konten depan */
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.25) !important;
        }

        body.theme-admin .sidenav-admin .brand-title {
            color: #ffffff !important;
            font-weight: 800 !important;
            letter-spacing: 0.5px;
        }

        body.theme-admin .sidenav-admin .brand-sub {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 700 !important;
        }

        body.theme-admin .navbar-main .breadcrumb-item a,
        body.theme-admin .navbar-main h6,
        body.theme-admin .navbar-main .nav-link {
            color: #1e293b !important;
        }

        /* Profile Avatar & Dropdown in theme-admin */
        body.theme-admin .profile-avatar-badge {
            background-color: #1e293b !important;
        }
        body.theme-admin .navbar-main .dropdown-menu .dropdown-item:hover .material-icons {
            color: #1e293b !important;
        }
        body.theme-admin #navbar-unread-badge {
            background-color: #ef4444 !important;
        }

        /* Teks bagian bawah dibuat hitam */
        body.theme-admin .footer .copyright,
        body.theme-admin .footer .copyright * {
            color: #000000 !important;
            font-weight: 600 !important;
        }

        body.theme-admin .dataTable-info,
        body.theme-admin .dataTable-bottom {
            color: #000000 !important;
            font-weight: 600 !important;
        }
    </style>
</head>

<?php 
    $bgColor = 'bg-danger';
    if (logged_in()) {
        if (in_groups('siswa')) {
            $bgColor = 'sidenav-siswa';
        } elseif (in_groups('pembimbing')) {
            $bgColor = 'sidenav-pembimbing';
        } elseif (in_groups('admin')) {
            $bgColor = 'sidenav-admin';
        } elseif (in_groups('instansi')) { // <-- DIUBAH
            $bgColor = 'bg-primary'; // <-- DIUBAH (warna biru untuk instansi)
        }
    }
?>

<body class="<?= logged_in() ? 'g-sidenav-show ' . (in_groups('siswa') ? 'theme-siswa' : (in_groups('pembimbing') ? 'theme-pembimbing' : (in_groups('admin') ? 'theme-admin' : 'bg-gray-200'))) : 'guest-landing' ?>">
<?php if (!logged_in()) : ?>
<style>
    body.guest-landing {
        background-image: url('/assets/img/login-bg-new.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }
    body.guest-landing::before {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 0;
        pointer-events: none;
    }
    body.guest-landing .main-content {
        position: relative;
        z-index: 1;
    }
    body.guest-landing .navbar-main {
        background: rgba(255,255,255,0.12) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2) !important;
    }
    body.guest-landing .navbar-main .breadcrumb-item a,
    body.guest-landing .navbar-main h6,
    body.guest-landing .navbar-main .nav-link {
        color: #fff !important;
    }
    body.guest-landing .card.card-body,
    body.guest-landing .card {
        background: rgba(255,255,255,0.92) !important;
        backdrop-filter: blur(6px);
    }
    body.guest-landing .container-fluid.py-2 {
        padding-top: 0.5rem !important;
    }
</style>
<?php endif; ?>
    <?php if (logged_in()) : ?>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 <?= $bgColor ?>" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer <?= in_groups('siswa') ? 'text-dark' : 'text-white' ?> opacity-5 position-absolute end-0 top-0 d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex align-items-center" href="/">
                <img src="/assets/logo-pln.png" class="navbar-brand-img" style="height: 40px;" alt="main_logo">
                <div class="ms-2">
                    <span class="font-weight-bold <?= in_groups('siswa') ? 'brand-title' : 'text-white' ?> text-xs d-block" style="line-height: 1.2;">SIAMAM</span>
                    <span class="font-weight-bold <?= in_groups('siswa') ? 'brand-sub' : 'text-white' ?> text-xxs d-block" style="line-height: 1.2;">PLN KALSEL</span>
                </div>
            </a>
        </div>
        <hr class="horizontal <?= in_groups('siswa') ? 'dark' : 'light' ?> mt-0 mb-2">
        <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs <?= (in_groups('siswa') || in_groups('pembimbing')) ? 'section-title' : 'text-white font-weight-bolder opacity-8' ?>">Main</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= (empty($segment) || (isset($segment[0]) && $segment[0] === '')) ? 'active bg-gradient-primary' : '' ?>" href="/">
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
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'tempat') ? 'active bg-gradient-primary' : '' ?>" href="/tempat">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">apartment</i>
                            </div>
                            <span class="nav-link-text ms-1">Divisi Magang</span>
                        </a>
                    </li>
                    
                    <!-- BAGIAN BADGE DITERIMA TELAH DIHAPUS DARI SINI -->
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'status-lamaran') ? 'active bg-gradient-primary' : '' ?>" href="/status-lamaran">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10" translate="no">description</i>
                            </div>
                            <span class="nav-link-text ms-1">Status Lamaran</span>
                        </a>
                    </li>
                <?php endif ?>

                <?php if (in_groups('admin') || in_groups('pembimbing')) : ?>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'application') ? 'active bg-gradient-primary' : '' ?>" href="/application">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">note_add</i>
                            </div>
                            <?php $pendingApps = countPendingApplications(); ?>
                            <span class="nav-link-text ms-1">
                                Pendaftaran Peserta
                                <?= $pendingApps > 0 ? '<span class="badge bg-danger badge-sm ms-2" id="sidebar-pending-apps">' . $pendingApps . '</span>' : '' ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && isset($segment[1]) && $segment[0] === 'man' && $segment[1] === 'siswa') ? 'active bg-gradient-primary' : '' ?>" href="/man/siswa">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">person</i>
                            </div>
                            <?php $needNilai = countNeedsNilai(); ?>
                            <span class="nav-link-text ms-1">
                                Manajemen Peserta
                                <?= $needNilai > 0 ? '<span class="badge bg-danger badge-sm ms-2">'.$needNilai.'</span>' : '' ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'sertifikat-kelola') ? 'active bg-gradient-primary' : '' ?>" href="/sertifikat-kelola">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10" translate="no">card_membership</i>
                            </div>
                            <?php $needCert = countNeedsSertifikat(); ?>
                            <span class="nav-link-text ms-1">
                                Kelola Sertifikat
                                <?= $needCert > 0 ? '<span class="badge bg-danger badge-sm ms-2">'.$needCert.'</span>' : '' ?>
                            </span>
                        </a>
                    </li>
                    <?php if (in_groups('admin')) : ?>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'alumni') ? 'active bg-gradient-primary' : '' ?>" href="/alumni">
                                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10" translate="no">collections</i>
                                </div>
                                <span class="nav-link-text ms-1">Kelola Alumni</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif ?>

                <?php if (in_groups('siswa')) : ?>
                    <hr class="horizontal <?= in_groups('siswa') ? 'dark' : 'light' ?>">
                    <li class="nav-item">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs <?= in_groups('siswa') ? 'section-title' : 'text-white font-weight-bolder opacity-8' ?>">Kegiatanku</h6>
                    </li>
                    <?php if (!empty(isAccepted(getSidByUid(user_id()))) || isEventFinished(user_id())) : ?>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'nilai') ? 'active bg-gradient-primary' : '' ?>" href="/nilai">
                                <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                    <i class="material-icons opacity-10" translate="no">confirmation_number</i>
                                </div>
                                <span class="nav-link-text ms-1">Nilai</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'kehadiran') ? 'active bg-gradient-primary' : '' ?>" href="/kehadiran">
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
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'application') ? 'active bg-gradient-primary' : '' ?>" href="/application">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10" translate="no">description</i>
                            </div>
                            <span class="nav-link-text ms-1">Surat Magang</span>
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

                <?php if (in_groups('admin') || in_groups('pembimbing') || in_groups('instansi')) : ?>
                <hr class="horizontal light">
                <li class="nav-item">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs <?= (in_groups('siswa') || in_groups('pembimbing')) ? 'section-title' : 'text-white font-weight-bolder opacity-8' ?>">
                        <?php if (in_groups('admin')) : ?>
                            Manajemen Data
                        <?php elseif (in_groups('pembimbing') || in_groups('instansi')) : ?>
                            Bimbingan Siswa
                        <?php endif ?>
                    </h6>
                </li>
                <?php endif; ?>

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
                        <a class="nav-link text-white <?= (isset($segment[0]) && isset($segment[1]) && $segment[0] === 'man' && $segment[1] === 'instansi') ? 'active bg-gradient-primary' : '' ?>" href="/man/instansi"> <!-- DIUBAH: dari 'dosen' ke 'instansi' dan href dari /man/dosen ke /man/instansi -->
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">school</i>
                            </div>
                            <span class="nav-link-text ms-1 text-wrap">Manajemen Instansi Pendidikan</span>
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

                <?php if (in_groups('instansi')) : ?> <!-- DIUBAH: dari dosen_pembimbing ke instansi -->
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && $segment[0] === 'bimbingan-instansi') ? 'active bg-gradient-primary' : '' ?>" href="<?= base_url('/bimbingan-instansi') ?>"> <!-- DIUBAH: dari bimbingan-dosen ke bimbingan-instansi -->
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">group</i>
                            </div>
                            <span class="nav-link-text ms-1">Mahasiswa Bimbingan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white <?= (isset($segment[0]) && isset($segment[1]) && $segment[0] === 'instansi' && $segment[1] === 'atribut-penilaian') ? 'active bg-gradient-primary' : '' ?>" href="<?= base_url('/instansi/atribut-penilaian') ?>">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10" translate="no">grading</i>
                            </div>
                            <span class="nav-link-text ms-1">Atribut Penilaian</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </aside>
    <?php endif; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg" style="min-height: 100vh;">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg position-sticky mt-4 top-1 px-0 mx-4 shadow-none border-radius-xl z-index-sticky" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <div class="d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0 me-3 d-flex align-items-center" id="iconNavbarSidenav">
                        <i class="fas fa-bars fs-5 text-dark cursor-pointer"></i>
                    </a>
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
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-5 right" id="navbar">
                    <div class="ms-auto pe-md-3 d-flex align-items-center">
                        <ul class="navbar-nav justify-content-end align-items-center">
                            <?php if (logged_in()) : ?>
                                <!-- Pesan / Notifikasi Notification Dropdown -->
                                <?php
                                $pesanModel2 = new \App\Models\PesanModel();
                                $unreadNav   = $pesanModel2->countUnread(user_id());
                                $isAdminOrPembimbing = in_groups('admin') || in_groups('pembimbing');
                                $pendingAppsCount = $isAdminOrPembimbing ? countPendingApplications() : 0;
                                $pendingList = ($isAdminOrPembimbing && $pendingAppsCount > 0) ? getPendingApplicationsList(5) : [];
                                $totalNotifNav = $unreadNav + $pendingAppsCount;
                                ?>
                                <li class="nav-item dropdown ps-2 me-2">
                                    <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="dropdownNotifications" data-bs-toggle="dropdown" aria-expanded="false" title="Pemberitahuan & Notifikasi">
                                        <i class="far fa-bell fs-5 cursor-pointer text-dark" style="color: #334155 !important;"></i>
                                        <?php if ($totalNotifNav > 0) : ?>
                                            <span class="position-absolute badge rounded-pill bg-danger border border-white shadow-sm"
                                                  style="top: -6px; right: -8px; font-size: 0.62rem; padding: 2px 5px; min-width: 17px; line-height: 1;">
                                                <?= $totalNotifNav > 99 ? '99+' : $totalNotifNav ?>
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4 shadow-lg border-0 border-radius-xl" aria-labelledby="dropdownNotifications" style="min-width: 320px; max-width: 380px;">
                                        <li class="px-2 pb-2 d-flex align-items-center justify-content-between border-bottom">
                                            <h6 class="text-sm font-weight-bold mb-0 text-dark">
                                                <i class="far fa-bell me-1 text-primary"></i> Pemberitahuan
                                            </h6>
                                            <?php if ($totalNotifNav > 0) : ?>
                                                <span class="badge bg-gradient-danger text-xxs"><?= $totalNotifNav ?> Baru</span>
                                            <?php endif; ?>
                                        </li>

                                        <?php if ($totalNotifNav == 0) : ?>
                                            <li class="p-3 text-center text-muted">
                                                <i class="far fa-bell-slash fs-4 d-block mb-1 opacity-6"></i>
                                                <span class="text-xs">Tidak ada pemberitahuan baru</span>
                                            </li>
                                        <?php else : ?>
                                            <?php if ($isAdminOrPembimbing && !empty($pendingList)) : ?>
                                                <li class="mt-2 px-2">
                                                    <span class="text-xxs font-weight-bolder text-uppercase text-muted">Pendaftaran Peserta Baru</span>
                                                </li>
                                                <?php foreach ($pendingList as $papp) : ?>
                                                    <li class="mb-1">
                                                        <a class="dropdown-item border-radius-md py-2 px-2 d-flex align-items-start" href="/application">
                                                            <div class="avatar avatar-sm bg-gradient-warning me-2 my-auto d-flex align-items-center justify-content-center text-white border-radius-md" style="min-width: 32px; height: 32px;">
                                                                <i class="material-icons text-white" style="font-size: 16px;">note_add</i>
                                                            </div>
                                                            <div class="d-flex flex-column justify-content-center overflow-hidden">
                                                                <h6 class="text-xs font-weight-bold mb-0 text-truncate text-dark">
                                                                    <?= esc($papp->nama_siswa) ?>
                                                                </h6>
                                                                <p class="text-xxs text-secondary mb-0 text-truncate">
                                                                    Daftar di <strong><?= esc($papp->nama_tempat ?? 'Divisi Magang') ?></strong>
                                                                </p>
                                                                <span class="text-xxs text-muted" style="font-size: 10px;">
                                                                    <i class="far fa-clock me-1"></i><?= date('d M Y, H:i', strtotime($papp->created_at ?? date('Y-m-d H:i:s'))) ?>
                                                                </span>
                                                            </div>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                                <?php if ($pendingAppsCount > count($pendingList)) : ?>
                                                    <li class="text-center py-1">
                                                        <a href="/application" class="text-xs text-primary font-weight-bold">
                                                            +<?= $pendingAppsCount - count($pendingList) ?> pendaftaran lainnya &raquo;
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if ($unreadNav > 0) : ?>
                                                <li class="mt-2 px-2 border-top pt-2">
                                                    <a class="dropdown-item border-radius-md py-2 px-2 d-flex align-items-center" href="/pesan">
                                                        <div class="avatar avatar-sm bg-gradient-info me-2 d-flex align-items-center justify-content-center text-white border-radius-md" style="min-width: 32px; height: 32px;">
                                                            <i class="material-icons text-white" style="font-size: 16px;">chat</i>
                                                        </div>
                                                        <div>
                                                            <h6 class="text-xs font-weight-bold mb-0 text-dark">Pesan Baru</h6>
                                                            <p class="text-xxs text-secondary mb-0">Ada <?= $unreadNav ?> pesan belum dibaca</p>
                                                        </div>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if ($isAdminOrPembimbing) : ?>
                                            <li class="border-top pt-2 mt-2 text-center">
                                                <a href="/application" class="btn btn-xs btn-outline-primary mb-0 w-100 py-1">
                                                    Buka Pendaftaran Peserta <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                                <!-- User Profile Dropdown Pill -->
                                <li class="nav-item dropdown ps-2">
                                    <a href="javascript:;" class="nav-link text-body p-0 d-flex align-items-center" id="accountMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php
                                        $userAvatarBg = '#15803d';
                                        if (in_groups('admin')) {
                                            $userAvatarBg = '#1e293b';
                                        } elseif (in_groups('pembimbing')) {
                                            $userAvatarBg = '#1865cf';
                                        } elseif (in_groups('instansi')) {
                                            $userAvatarBg = '#0284c7';
                                        }
                                        ?>
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2 text-white shadow-sm profile-avatar-badge" style="width: 32px; height: 32px; background-color: <?= $userAvatarBg ?>;">
                                            <i class="fas fa-user" style="font-size: 13px;"></i>
                                        </div>
                                        <span class="text-sm font-weight-bold d-none d-sm-inline me-1" style="color: #1e293b;"><?= user()->username ?></span>
                                        <i class="fas fa-chevron-down text-xxs text-secondary ms-1"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end p-2 me-sm-n4 shadow-sm" aria-labelledby="accountMenu">
                                        <li>
                                            <div class="dropdown-header text-muted mb-0 px-2 py-1">
                                                <div class="text-capitalize fw-bold text-dark" style="font-size: 0.85rem;">Hello, <?= user()->username ?></div>
                                                <?php if (in_groups('siswa')) : ?>
                                                    <div class="text-xs" style="font-size: 0.75rem; color: #64748b;"><?= getIdentityLabelByUid() ?> : <?= getNimByUid() ?></div>
                                                <?php endif ?>
                                            </div>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="/profile">
                                                <span class="material-icons me-2" style="font-size: 18px;">person</span>
                                                <span style="font-size: 0.825rem; font-weight: 500;">Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="/profile#password">
                                                <span class="material-icons me-2" style="font-size: 18px;">key</span>
                                                <span style="font-size: 0.825rem; font-weight: 500;">Password</span>
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center text-danger" href="/logout">
                                                <span class="material-icons text-danger me-2" style="font-size: 18px;">logout</span>
                                                <span class="text-danger" style="font-size: 0.825rem; font-weight: 600;">Log Out</span>
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
                            <?php else : ?>
                                <li class="nav-item d-none d-lg-flex align-items-center gap-2">
                                    <a href="/login" class="btn btn-sm btn-outline-primary mb-0">Login</a>
                                    <a href="<?= url_to('register') ?>" class="btn btn-sm bg-gradient-primary mb-0">Daftar</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <?php if (!logged_in()) : ?>
                <div class="ms-auto d-flex d-lg-none align-items-center gap-2 ps-2">
                    <a href="/login" class="btn btn-sm bg-gradient-primary mb-0 py-1 px-3" style="font-size:0.75rem;">Login</a>
                    <a href="<?= url_to('register') ?>" class="btn btn-sm bg-gradient-dark mb-0 py-1 px-3" style="font-size:0.75rem;">Daftar</a>
                </div>
                <?php endif; ?>
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