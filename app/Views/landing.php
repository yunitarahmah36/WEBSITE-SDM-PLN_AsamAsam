<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <?php helper('url'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAMAM — PLN UBP Asam Asam</title>
    <link rel="icon" href="<?= base_url('assets/logo-pln.png') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; color: #2d3748; background-color: #f7fafc; }

        /* NAVBAR */
        .lp-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 16px 40px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.06);
            transition: all 0.3s;
        }
        .lp-nav .brand {
            display: flex; align-items: center; gap: 14px;
            font-size: 28px; font-weight: 800; color: #1a1a2e; text-decoration: none;
            letter-spacing: 0.5px;
        }
        .lp-nav .brand img { height: 50px; }
        .lp-nav .brand span { color: #0096d6; }
        .lp-nav .nav-links { display: flex; gap: 28px; align-items: center; }
        .lp-nav .nav-links a {
            color: #4a5568; text-decoration: none; font-weight: 500; font-size: 14px;
            transition: color 0.2s;
        }
        .lp-nav .nav-links a:hover { color: #0096d6; }
        .btn-masuk {
            background: #0096d6; color: #fff !important; padding: 9px 24px;
            border-radius: 8px; font-weight: 600; font-size: 14px;
            text-decoration: none; transition: all 0.2s;
            border: none;
        }
        .btn-masuk:hover { background: #007bb0; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 150, 214, 0.3); }

        /* HERO */
        .hero {
            position: relative; min-height: 90vh;
            display: flex; align-items: center;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            overflow: hidden;
            padding-top: 80px;
        }
        .hero::before {
            display: none;
        }
        .hero-slideshow {
            position: absolute;
            inset: 0;
            z-index: 1;
        }
        .hero-slideshow .slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 1.5s ease-in-out, transform 6s ease-out;
            transform: scale(1);
        }
        .hero-slideshow .slide.active {
            opacity: 0.8;
            transform: scale(1.06);
        }
        .hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(90deg, rgba(15, 32, 39, 0.95) 0%, rgba(15, 32, 39, 0.6) 70%, transparent 100%);
            z-index: 2;
        }
        .hero-content {
            position: relative; z-index: 3;
            padding: 80px 40px 60px;
            max-width: 1200px; margin: 0 auto; width: 100%;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(0, 150, 214, 0.15); border: 1px solid rgba(0, 150, 214, 0.4);
            color: #4fc3f7; padding: 6px 16px; border-radius: 20px;
            font-size: 13px; font-weight: 600; margin-bottom: 24px;
            animation: fadeInUp 0.6s ease;
        }
        .hero h1 {
            font-size: clamp(32px, 5vw, 52px);
            font-weight: 800; color: #fff; line-height: 1.25;
            margin-bottom: 20px;
            animation: fadeInUp 0.7s ease;
        }
        .hero h1 span { color: #0096d6; }
        .hero p {
            font-size: 18px; color: rgba(255,255,255,0.8);
            max-width: 650px; line-height: 1.8; margin-bottom: 36px;
            animation: fadeInUp 0.8s ease;
            font-style: italic;
            border-left: 3px solid #0096d6;
            padding-left: 15px;
        }
        .hero-btns { display: flex; gap: 14px; flex-wrap: wrap; animation: fadeInUp 0.9s ease; }
        .btn-hero-primary {
            background: #0096d6; color: #fff; padding: 14px 32px;
            border-radius: 10px; font-weight: 700; font-size: 15px;
            text-decoration: none; transition: all 0.25s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-hero-primary:hover { background: #007bb0; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0, 150, 214, 0.4); color:#fff; }
        .btn-hero-secondary {
            background: rgba(255,255,255,0.08); color: #fff; padding: 14px 32px;
            border-radius: 10px; font-weight: 600; font-size: 15px;
            text-decoration: none; border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.25s; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,0.15); color:#fff; }

        /* TENTANG PROGRAM SECTION */
        .about-section {
            padding: 80px 40px;
            background-color: #fff;
        }
        .about-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        .about-visual {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            height: 400px;
        }
        .about-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .about-visual::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(15,32,39,0.7), transparent);
        }
        .about-text h2 {
            font-size: 30px;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 20px;
            position: relative;
        }
        .about-text h2::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background-color: #0096d6;
            margin-top: 10px;
            border-radius: 2px;
        }
        .about-text p {
            font-size: 15px;
            color: #4a5568;
            line-height: 1.75;
            margin-bottom: 16px;
            text-align: justify;
        }

        /* MENGAPA MAGANG SECTION */
        .why-section {
            padding: 80px 40px;
            background-color: #f7fafc;
        }
        .why-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        .section-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: #1a202c;
            margin-bottom: 12px;
        }
        .section-header p {
            font-size: 16px;
            color: #718096;
            max-width: 600px;
            margin: 0 auto;
        }
        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
        .why-card {
            background: #fff;
            border-radius: 16px;
            padding: 35px 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .why-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
            border-color: #cbd5e0;
        }
        .why-card-icon {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 24px;
        }
        .why-smk .why-card-icon { background: rgba(66, 153, 225, 0.15); color: #3182ce; }
        .why-mhs .why-card-icon { background: rgba(72, 187, 120, 0.15); color: #38a169; }
        .why-keunggulan .why-card-icon { background: rgba(236, 151, 6, 0.15); color: #dd6b20; }

        .why-card h3 {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .why-list {
            list-style: none;
        }
        .why-list li {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.6;
            margin-bottom: 12px;
            position: relative;
            padding-left: 24px;
        }
        .why-list li::before {
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            content: "\f00c";
            position: absolute;
            left: 0;
            top: 2px;
        }
        .why-smk .why-list li::before { color: #3182ce; }
        .why-mhs .why-list li::before { color: #38a169; }
        .why-keunggulan .why-list li::before { color: #dd6b20; }

        /* DIVISI DIBUKA SECTION */
        .divisi-section {
            padding: 80px 40px;
            background-color: #fff;
        }
        .divisi-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .divisi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 28px;
        }
        .divisi-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .divisi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
            border-color: #cbd5e0;
        }
        .divisi-image {
            height: 180px;
            position: relative;
            background-color: #2c5364;
        }
        .divisi-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .divisi-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 5;
        }
        .divisi-badge.buka {
            background-color: #c6f6d5;
            color: #22543d;
        }
        .divisi-badge.tutup {
            background-color: #fed7d7;
            color: #742a2a;
        }
        .divisi-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .divisi-body h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }
        .divisi-desc {
            font-size: 0.9rem !important;
            color: #718096;
            line-height: 1.5;
            margin-bottom: 12px;
            max-height: 100px;
            overflow-y: auto;
            padding-right: 4px;
        }
        .divisi-desc::-webkit-scrollbar {
            width: 4px;
        }
        .divisi-desc::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.03);
            border-radius: 4px;
        }
        .divisi-desc::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.15);
            border-radius: 4px;
        }
        .divisi-desc::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.3);
        }
        /* Rich text nested styles inside landing division description */
        .divisi-desc h1, .divisi-desc h2, .divisi-desc h3 {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            margin-top: 8px !important;
            margin-bottom: 4px !important;
            color: #1a202c !important;
        }
        .divisi-desc h4, .divisi-desc h5, .divisi-desc h6 {
            font-size: 1.0rem !important;
            font-weight: 600 !important;
            margin-top: 6px !important;
            margin-bottom: 3px !important;
            color: #2d3748 !important;
        }
        .divisi-desc p {
            font-size: 0.9rem !important;
            line-height: 1.5 !important;
            margin-bottom: 4px !important;
            color: #4a5568 !important;
        }
        .divisi-desc ul, .divisi-desc ol {
            padding-left: 14px !important;
            margin-bottom: 4px !important;
            font-size: 0.9rem !important;
        }
        .divisi-desc li {
            margin-bottom: 2px !important;
            line-height: 1.5 !important;
            font-size: 0.9rem !important;
        }
        .divisi-meta {
            border-top: 1px solid #f7fafc;
            padding-top: 15px;
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .divisi-meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #4a5568;
        }
        .divisi-meta-item i {
            color: #0096d6;
            width: 16px;
            text-align: center;
        }
        .divisi-footer {
            background-color: #f8fafc;
            padding: 16px 24px;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-daftar-divisi {
            background-color: #0096d6;
            color: #fff !important;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-daftar-divisi:hover {
            background-color: #007bb0;
            transform: translateY(-1px);
        }
        .btn-daftar-divisi.disabled {
            background-color: #cbd5e0;
            color: #718096 !important;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* STEPS */
        .steps-section {
            background: linear-gradient(135deg, #1f2d3d, #0f172a);
            padding: 70px 40px;
        }
        .steps-inner { max-width: 1200px; margin: 0 auto; }
        .steps-title {
            text-align: center; color: #4fc3f7;
            font-size: 13px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; margin-bottom: 12px;
        }
        .steps-subtitle {
            text-align: center; color: #fff;
            font-size: 26px; font-weight: 800; margin-bottom: 45px;
        }
        .steps-row {
            display: flex; align-items: flex-start; gap: 0;
            position: relative;
        }
        .steps-row::before {
            content: ''; position: absolute;
            top: 32px; left: 10%; right: 10%; height: 2px;
            background: linear-gradient(90deg, #0096d6, rgba(255,255,255,0.15));
            z-index: 0;
        }
        .step-item {
            flex: 1; text-align: center; position: relative; z-index: 1;
            padding: 0 10px;
        }
        .step-icon {
            width: 64px; height: 64px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 22px;
            background: rgba(255,255,255,0.08);
            border: 2px solid rgba(255,255,255,0.15);
            color: #fff; transition: all 0.3s;
        }
        .step-item.active .step-icon {
            background: #0096d6; border-color: #0096d6;
            box-shadow: 0 4px 16px rgba(0, 150, 214, 0.4);
        }
        .step-label { color: #fff; font-size: 14px; font-weight: 600; }
        .step-desc { color: rgba(255,255,255,0.5); font-size: 11px; margin-top: 5px; line-height: 1.4; }

        /* FEATURES */
        .features-section { padding: 80px 40px; background: #fff; }
        .features-inner { max-width: 1200px; margin: 0 auto; }
        .features-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; }
        .feature-card {
            background: #f8fafc; border-radius: 16px; padding: 32px 28px;
            box-shadow: none;
            transition: all 0.3s; border: 1px solid #e2e8f0;
        }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.05); border-color: #cbd5e0; }
        .feature-icon {
            width: 50px; height: 50px; border-radius: 12px;
            background: linear-gradient(135deg, #0096d6, #00c6ff);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff; margin-bottom: 20px;
        }
        .feature-card h3 { font-size: 16px; font-weight: 700; color: #1a202c; margin-bottom: 8px; }
        .feature-card p { font-size: 13.5px; color: #4a5568; line-height: 1.7; }

        /* CTA */
        .cta-section {
            padding: 85px 40px;
            background: linear-gradient(135deg, #0f2027, #203a43);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute; inset: 0;
            background: url('<?= base_url('assets/gambar3.jpg') ?>') center/cover no-repeat;
            opacity: 0.05;
        }
        .cta-content {
            position: relative;
            z-index: 1;
        }
        .cta-section h2 { font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 16px; }
        .cta-section p { color: rgba(255,255,255,0.75); font-size: 16px; margin-bottom: 32px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6; }
        .btn-cta-blue {
            background: #0096d6; color: #fff; padding: 14px 38px;
            border-radius: 10px; font-weight: 700; font-size: 16px;
            text-decoration: none; display: inline-block;
            transition: all 0.25s;
        }
        .btn-cta-blue:hover { background: #007bb0; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,150,214,0.3); color:#fff; }

        /* FOOTER */
        .lp-footer {
            background: #0b131a; padding: 40px;
            text-align: center; color: rgba(255,255,255,0.5); font-size: 13.5px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }
        .lp-footer strong { color: #fff; }

        /* ALUMNI SECTION */
        .alumni-section {
            padding: 80px 40px;
            background-color: #f7fafc;
        }
        .alumni-inner {
            max-width: 1200px;
            margin: 0 auto;
        }
        .alumni-intro {
            margin-bottom: 40px;
        }
        .alumni-gallery {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        .alumni-img-wrapper {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .alumni-img-wrapper img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .alumni-img-wrapper img:hover {
            transform: scale(1.03);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 992px) {
            .about-inner { grid-template-columns: 1fr; gap: 30px; }
            .about-visual { height: 300px; }
            .why-grid { grid-template-columns: 1fr; }
        }

        .d-md-none { display: none !important; }

        @media (max-width: 768px) {
            .d-md-none { display: block !important; }
            .lp-nav { padding: 14px 20px; }
            .lp-nav .nav-links { display: none; }
            .hero-content { padding: 100px 20px 40px; }
            .steps-row::before { display: none; }
            .steps-row { flex-wrap: wrap; gap: 20px; }
            .step-item { flex: 1 1 100%; margin-bottom: 10px; }
            .features-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="lp-nav">
    <a href="/" class="brand">
        <img src="<?= base_url('assets/logo-pln.png') ?>" alt="Logo">
        <div style="display: inline-block;"><span>SIA</span>MAM</div>
    </a>
    <div class="nav-links">
        <a href="#tentang">Tentang</a>
        <a href="#mengapa-magang">Manfaat</a>
        <a href="#divisi-dibuka">Divisi Dibuka</a>
        <a href="#alur-pendaftaran">Alur</a>
        <a href="#fitur">Fitur Platform</a>
        <a href="#alumni">Alumni</a>
        <a href="<?= url_to('login') ?>">Masuk</a>
        <a href="<?= url_to('register') ?>" class="btn-masuk">Daftar</a>
    </div>
    <a href="<?= url_to('login') ?>" class="btn-masuk d-md-none">Masuk</a>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-slideshow">
        <div class="slide active" style="background-image: url('<?= base_url('assets/gambar3.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar4.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar5.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar6.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar7.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar8.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar9.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar10.jpg') ?>');"></div>
        <div class="slide" style="background-image: url('<?= base_url('assets/gambar11.jpg') ?>');"></div>
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge">
            <i class="fa fa-info-circle" style="font-size:12px;"></i>
            PLN UBP Asam Asam
        </div>
        <h1>Program Magang Resmi<br><span>PLN UBP Asam Asam</span></h1>
        <p>"Belajar, Berkembang, dan Berkarya Bersama PLN UBP Asam Asam."</p>
        <div class="hero-btns">
            <a href="<?= url_to('register') ?>" class="btn-hero-primary">
                <i class="fa fa-rocket"></i> Daftar Sekarang
            </a>
            <a href="<?= url_to('login') ?>" class="btn-hero-secondary">
                <i class="fa fa-sign-in-alt"></i> Masuk ke Akun
            </a>
        </div>
    </div>
</section>

<!-- TENTANG PROGRAM SECTION -->
<section class="about-section" id="tentang">
    <div class="about-inner">
        <div class="about-visual">
            <img src="<?= base_url('assets/gambar3.jpg') ?>" alt="PLN UBP Asam Asam">
        </div>
        <div class="about-text">
            <h2>Tentang Program Magang</h2>
            <p>Program Magang PLN UBP Asam Asam merupakan program pembelajaran dan pengembangan kompetensi yang ditujukan bagi siswa SMK dan mahasiswa perguruan tinggi. Program ini memberikan kesempatan kepada peserta untuk memperoleh pengalaman kerja nyata di lingkungan industri ketenagalistrikan serta memahami proses operasional perusahaan secara langsung.</p>
            <p>Melalui program magang ini, peserta dapat mengembangkan keterampilan teknis maupun non-teknis, meningkatkan kemampuan kerja sama tim, komunikasi, disiplin, serta memahami budaya kerja profesional yang diterapkan di PLN UBP Asam Asam.</p>
            <p>Bagi siswa SMK, program magang menjadi sarana untuk menerapkan ilmu dan keterampilan yang telah dipelajari di sekolah ke dalam dunia kerja yang sesungguhnya. Sedangkan bagi mahasiswa, program ini menjadi wadah untuk memperluas wawasan, mengasah kemampuan profesional, serta mempersiapkan diri menghadapi tantangan dunia kerja setelah lulus.</p>
            <p>PLN UBP Asam Asam berkomitmen untuk mendukung pengembangan sumber daya manusia yang unggul, kompeten, dan siap bersaing di era industri modern melalui pengalaman belajar yang bermakna dan berorientasi pada kebutuhan dunia kerja.</p>
        </div>
    </div>
</section>

<!-- MENGAPA MAGANG SECTION -->
<section class="why-section" id="mengapa-magang">
    <div class="why-inner">
        <div class="section-header">
            <h2>Mengapa Magang di PLN UBP Asam Asam?</h2>
            <p>Dapatkan pengalaman, pengetahuan, dan bimbingan langsung di lingkungan industri ketenagalistrikan.</p>
        </div>
        <div class="why-grid">
            <!-- SMK -->
            <div class="why-card why-smk">
                <div class="why-card-icon"><i class="fa fa-graduation-cap"></i></div>
                <h3>🎓 Untuk Siswa SMK</h3>
                <ul class="why-list">
                    <li>Menerapkan ilmu yang diperoleh di sekolah.</li>
                    <li>Mengenal budaya kerja di dunia industri.</li>
                    <li>Mengembangkan keterampilan kerja dan kedisiplinan.</li>
                    <li>Menambah pengalaman sebelum memasuki dunia kerja.</li>
                </ul>
            </div>
            <!-- Mahasiswa -->
            <div class="why-card why-mhs">
                <div class="why-card-icon"><i class="fa fa-book"></i></div>
                <h3>📚 Untuk Mahasiswa</h3>
                <ul class="why-list">
                    <li>Memperoleh pengalaman kerja profesional.</li>
                    <li>Mengembangkan kompetensi sesuai bidang studi.</li>
                    <li>Meningkatkan kemampuan problem solving dan kerja tim.</li>
                    <li>Menjadi bekal untuk karier dan tugas akhir.</li>
                </ul>
            </div>
            <!-- Keunggulan -->
            <div class="why-card why-keunggulan">
                <div class="why-card-icon"><i class="fa fa-bolt"></i></div>
                <h3>⚡ Keunggulan Program</h3>
                <ul class="why-list">
                    <li>Lingkungan kerja profesional.</li>
                    <li>Bimbingan dari mentor berpengalaman.</li>
                    <li>Kesempatan belajar langsung di unit pembangkitan listrik.</li>
                    <li>Mendukung pengembangan hard skill dan soft skill.</li>
                    <li>Sistem pendaftaran dan monitoring magang berbasis website.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- DIVISI TERBUKA SECTION -->
<section class="divisi-section" id="divisi-dibuka">
    <div class="divisi-inner">
        <div class="section-header">
            <h2>Divisi Magang Terbuka</h2>
            <p>Pilih divisi magang yang sesuai dengan program studi, keahlian, dan minat karier Anda.</p>
        </div>
        <div class="divisi-grid">
            <?php if (empty($tempat)) : ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #718096;">
                    <i class="fa fa-briefcase" style="font-size: 48px; margin-bottom: 16px; color: #cbd5e0;"></i>
                    <p style="font-size: 16px; font-weight: 500;">Belum ada divisi magang yang dibuka saat ini.</p>
                </div>
            <?php else : ?>
                <?php foreach ($tempat as $t) : ?>
                    <?php
                    if (filter_var($t->foto, FILTER_VALIDATE_URL)) {
                        $foto = $t->foto;
                    } else {
                        $foto = $t->foto ? base_url('assets/img/tempat_magang/' . $t->foto) : base_url('assets/gambar3.jpg');
                    }
                    ?>
                    <div class="divisi-card">
                        <div class="divisi-image">
                            <img src="<?= $foto ?>" alt="<?= $t->nama ?>">
                            <span class="divisi-badge <?= strtolower($t->status) ?>"><?= $t->status ?></span>
                        </div>
                        <div class="divisi-body">
                            <h3><?= $t->nama ?></h3>
                            <div class="divisi-desc">
                                 <?= $t->deskripsi ?>
                            </div>
                            <div class="divisi-meta">
                                <?php if (!empty($t->pembimbing)) : ?>
                                    <div class="divisi-meta-item">
                                        <i class="fa fa-user-tie"></i>
                                        <span>Mentor: <strong><?= $t->pembimbing ?></strong></span>
                                    </div>
                                <?php endif; ?>
                                <div class="divisi-meta-item">
                                    <i class="fa fa-users"></i>
                                    <span>Kuota Tersisa: <strong><?= getSlotAvailable($t->id) == 0 ? 'Full' : getSlotAvailable($t->id) ?></strong></span>
                                </div>
                                <?php if (!empty($t->alamat)) : ?>
                                    <div class="divisi-meta-item">
                                        <i class="fa fa-map-marker-alt"></i>
                                        <span style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;"><?= $t->alamat ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="divisi-footer">
                            <span style="font-size: 12px; color: #a0aec0;">PLN UBP Asam Asam</span>
                            <a href="<?= url_to('register') ?>" class="btn-daftar-divisi <?= strtolower($t->status) == 'tutup' || getSlotAvailable($t->id) == 0 ? 'disabled' : '' ?>">Daftar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ALUR PENDAFTARAN -->
<section class="steps-section" id="alur-pendaftaran">
    <div class="steps-inner">
        <p class="steps-title">Alur Pendaftaran</p>
        <h2 class="steps-subtitle">Cara Mudah Mendaftar Magang</h2>
        <div class="steps-row">
            <div class="step-item">
                <div class="step-icon"><i class="fa fa-user-plus"></i></div>
                <div class="step-label">Daftar Akun</div>
                <div class="step-desc">Buat akun baru dengan email aktif</div>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa fa-file-alt"></i></div>
                <div class="step-label">Lengkapi Data</div>
                <div class="step-desc">Isi profil & berkas dokumen lengkap. <br><strong style="color: #f6ad55; font-size: 11px; display: block; margin-top: 6px;">*Bagi pendaftar berkelompok, nama Instansi wajib sama persis antar anggota kelompok</strong></div>
            </div>
            <div class="step-item active">
                <div class="step-icon"><i class="fa fa-briefcase"></i></div>
                <div class="step-label">Pilih Divisi</div>
                <div class="step-desc">Pilih posisi / divisi magang yang dibuka</div>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa fa-paper-plane"></i></div>
                <div class="step-label">Ajukan Lamaran</div>
                <div class="step-desc">Kirim berkas lamaran Anda secara online</div>
            </div>
            <div class="step-item">
                <div class="step-icon"><i class="fa fa-clock"></i></div>
                <div class="step-label">Tunggu Konfirmasi</div>
                <div class="step-desc">Verifikasi berkas & kelulusan oleh admin</div>
            </div>
        </div>
    </div>
</section>

<!-- FITUR PLATFORM -->
<section class="features-section" id="fitur">
    <div class="features-inner">
        <div class="section-header">
            <h2>Kemudahan Monitoring Digital</h2>
            <p>Sistem pendaftaran dan monitoring praktek magang terintegrasi dalam satu platform.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-book-open"></i></div>
                <h3>Logbook Harian Digital</h3>
                <p>Isi logbook kegiatan harian secara online lengkap dengan bukti kegiatan. Pembimbing lapangan dapat langsung menyetujui secara digital.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-comments"></i></div>
                <h3>Sistem Pesan Internal</h3>
                <p>Fitur chat untuk memudahkan koordinasi dan bimbingan antara mahasiswa, pembimbing lapangan, dan dosen pembimbing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-bullhorn"></i></div>
                <h3>Pengumuman Tertarget</h3>
                <p>Dapatkan update informasi penting yang disesuaikan khusus dengan peran Anda sebagai Mahasiswa atau Pembimbing.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-chart-line"></i></div>
                <h3>Nilai Transparan</h3>
                <p>Pantau hasil penilaian magang baik dari aspek industri maupun akademik secara langsung setelah dikonfirmasi.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-shield-alt"></i></div>
                <h3>Sosialisasi K3</h3>
                <p>Akses materi Keselamatan dan Kesehatan Kerja (K3) industri ketenagalistrikan demi keamanan kerja Anda.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa fa-file-pdf"></i></div>
                <h3>Unggah Laporan Praktis</h3>
                <p>Kumpulkan laporan akhir magang Anda langsung ke dalam sistem untuk diperiksa dan disetujui pembimbing.</p>
            </div>
        </div>
    </div>
</section>

<!-- ALUMNI SECTION -->
<section class="alumni-section" id="alumni">
    <div class="alumni-inner">
        <div class="section-header">
            <h2>ALUMNI MAGANG</h2>
            <p style="font-weight: 600; color: #0096d6; margin-bottom: 24px;">"Jejak Prestasi Alumni Magang, Inspirasi untuk Generasi Berikutnya"</p>
        </div>
        <div class="alumni-intro text-start" style="text-align: left !important; margin-bottom: 40px;">
            <h3 style="font-size: 22px; font-weight: 700; color: #2d3748; margin-bottom: 12px;">Selamat Datang di Halaman Alumni Magang</h3>
            <p style="font-size: 15px; color: #4a5568; line-height: 1.8; margin-bottom: 16px; text-align: justify;">
                Selamat datang di halaman Alumni Magang PT PLN Indonesia Power UBP Asam-Asam.
            </p>
            <p style="font-size: 15px; color: #4a5568; line-height: 1.8; text-align: justify;">
                Halaman ini menjadi wadah untuk mendokumentasikan perjalanan, pengalaman, dan pencapaian para peserta magang yang telah menyelesaikan program magang di PT PLN Indonesia Power UBP Asam-Asam. Program magang dirancang untuk memberikan pengalaman kerja nyata di lingkungan industri pembangkitan listrik sehingga peserta dapat mengembangkan kompetensi teknis, keterampilan profesional, dan budaya kerja yang dibutuhkan di dunia industri. Pengalaman magang di berbagai unit PLN Indonesia Power menunjukkan bahwa peserta memperoleh pembelajaran langsung mengenai operasi dan pemeliharaan pembangkit, penerapan K3, serta kolaborasi dengan mentor di lapangan.
            </p>
        </div>
        <div class="alumni-gallery">
            <?php if (!empty($alumni)): ?>
                <?php $idx = 0; ?>
                <?php foreach ($alumni as $alum): ?>
                    <?php 
                        if (!empty($alum->alumni_foto)) {
                            $fotoPath = FCPATH . 'assets/alumni/' . $alum->alumni_foto;
                            $foto = base_url('assets/alumni/'.$alum->alumni_foto);
                        } else {
                            $fotoPath = FCPATH . 'assets/img/foto_siswa/' . $alum->foto;
                            if (!empty($alum->foto) && file_exists($fotoPath)) {
                                $foto = base_url('assets/img/foto_siswa/'.$alum->foto);
                            } else {
                                $fallbacks = ['picture.jpeg', 'picture (1).jpeg', 'picture (2).jpeg', 'picture (3).jpeg'];
                                $foto = base_url('assets/' . $fallbacks[$idx % 4]);
                            }
                        }
                        $divisi = $alum->divisi ?? 'Divisi Magang';
                        $idx++;
                    ?>
                    <div class="alumni-img-wrapper" style="position: relative;">
                        <img src="<?= $foto ?>" alt="<?= esc($alum->nama) ?>">
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 15px; text-align: left;">
                            <h4 style="margin: 0; font-size: 16px; color: white; font-weight: bold;"><?= esc($alum->nama) ?></h4>
                            <p style="margin: 4px 0 0; font-size: 13px; color: #ddd;"><?= esc($alum->instansi) ?></p>
                            <p style="margin: 2px 0 0; font-size: 12px; color: #aaa;"><i class="fa fa-map-marker-alt me-1"></i> <?= esc($divisi) ?></p>
                            <?php if (!empty($alum->alumni_testimoni)): ?>
                                <p style="margin: 8px 0 0; font-size: 12px; color: #fff; font-style: italic; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 8px;">
                                    "<?= esc($alum->alumni_testimoni) ?>"
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php 
                $count = count($alumni);
                if ($count < 4): 
                    $fallbacks = ['picture.jpeg', 'picture (1).jpeg', 'picture (2).jpeg', 'picture (3).jpeg'];
                    for ($i = $count; $i < 4; $i++):
                ?>
                    <div class="alumni-img-wrapper">
                        <img src="<?= base_url('assets/' . $fallbacks[$i % 4]) ?>" alt="Alumni Magang <?= $i+1 ?>">
                    </div>
                <?php 
                    endfor;
                endif; 
                ?>
            <?php else: ?>
                <div class="alumni-img-wrapper">
                    <img src="<?= base_url('assets/picture.jpeg') ?>" alt="Alumni Magang 1">
                </div>
                <div class="alumni-img-wrapper">
                    <img src="<?= base_url('assets/picture (1).jpeg') ?>" alt="Alumni Magang 2">
                </div>
                <div class="alumni-img-wrapper">
                    <img src="<?= base_url('assets/picture (2).jpeg') ?>" alt="Alumni Magang 3">
                </div>
                <div class="alumni-img-wrapper">
                    <img src="<?= base_url('assets/picture (3).jpeg') ?>" alt="Alumni Magang 4">
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-content">
        <h2>Siap Belajar, Berkembang, dan Berkarya?</h2>
        <p>Bergabunglah dengan Program Magang PLN UBP Asam Asam sekarang dan persiapkan diri Anda untuk menghadapi dunia industri profesional.</p>
        <a href="<?= url_to('register') ?>" class="btn-cta-blue">
            Mulai Pendaftaran &rarr;
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="lp-footer">
    <strong>SIAMAM — PLN UBP Asam Asam</strong><br>
    Sistem Informasi Administrasi Praktek Magang &copy; <?= date('Y') ?>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slideshow .slide');
        let currentSlide = 0;
        const slideInterval = 6000; // 6 seconds

        function nextSlide() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        setInterval(nextSlide, slideInterval);
    });
</script>

</body>
</html>
