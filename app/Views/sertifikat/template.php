<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Magang - <?= esc($siswa->nama) ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Georgia', 'Times New Roman', Times, serif;
            background-color: #ffffff;
        }
        
        /* ── Symmetrical Borders (Independent of Content) ── */
        .border-outer {
            position: absolute;
            top: 20px;
            bottom: 20px;
            left: 20px;
            right: 20px;
            border: 8px solid #005a9c;
            z-index: -2;
        }
        .border-inner {
            position: absolute;
            top: 32px;
            bottom: 32px;
            left: 32px;
            right: 32px;
            border: 2px solid #005a9c;
            background-color: #fafafa;
            z-index: -1;
        }

        /* ── Content Area ── */
        .cert-content {
            position: absolute;
            top: 34px;
            bottom: 34px;
            left: 34px;
            right: 34px;
            padding: 25px 35px;
            z-index: 1;
        }
        
        /* Top Header */

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 55px;
            height: auto;
        }
        .header-title {
            text-align: right;
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #444;
            line-height: 1.3;
        }
        .header-title strong {
            color: #005a9c;
            font-size: 11px;
        }

        /* Title */
        .title-container {
            text-align: center;
            margin-top: 10px;
        }
        .title {
            font-size: 30px;
            font-weight: bold;
            color: #005a9c;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .subtitle {
            font-size: 13px;
            font-style: italic;
            color: #555;
            margin: 5px 0 15px 0;
        }

        /* Student Name */
        .name-container {
            text-align: center;
            margin-bottom: 12px;
        }
        .name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            border-bottom: 2px solid #005a9c;
            display: inline-block;
            padding: 0 30px 3px 30px;
        }
        
        /* Main Text Details */
        .main-text {
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            text-align: center;
            margin: 0 auto 12px auto;
            max-width: 820px;
        }
        .main-text strong {
            color: #000;
        }

        /* Score Box */
        .score-container {
            text-align: center;
            margin-bottom: 15px;
        }
        .score-box {
            background-color: #eef7fc;
            border: 1px solid #bce1f5;
            padding: 6px 15px;
            display: inline-block;
            border-radius: 4px;
        }
        .score-text {
            font-size: 15px;
            font-weight: bold;
            color: #005a9c;
            font-family: Arial, sans-serif;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        .signature-cell {
            width: 50%;
            font-size: 12px;
            color: #333;
            vertical-align: top;
            font-family: Arial, sans-serif;
        }
        .signature-cell.left {
            text-align: left;
        }
        .signature-cell.right {
            text-align: right;
        }
        .signature-space {
            height: 45px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #666;
            margin-top: 3px;
            display: inline-block;
        }
        .signature-name {
            font-weight: bold;
            color: #111;
        }
        .signature-title {
            font-size: 10px;
            color: #666;
            margin-top: 1px;
        }
    </style>
</head>
<body>
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <div class="cert-content">
        <!-- Top Header -->
        <table class="header-table">
                <tr>
                    <td>
                        <?php 
                        $logoPath = FCPATH . 'assets/logo-pln.png';
                        if (file_exists($logoPath)) {
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoSrc = 'data:image/png;base64,' . $logoData;
                        } else {
                            $logoSrc = '';
                        }
                        
                        if (!empty($logoSrc)) : 
                        ?>
                            <img class="header-logo" src="<?= $logoSrc ?>" alt="Logo PLN">
                        <?php endif; ?>
                    </td>
                    <td class="header-title">
                        <strong>PT PLN (PERSERO)</strong><br>
                        UNIT PELAKSANA PEMBANGKITAN ASAM ASAM<br>
                        <span style="font-size: 9px; color: #777;">Sistem Informasi Aplikasi Magang Mahasiswa (SIAMAM)</span>
                    </td>
                </tr>
            </table>

            <!-- Title -->
            <div class="title-container">
                <div class="title">Sertifikat Kelulusan</div>
                <div class="subtitle">Diberikan Sebagai Penghargaan Dan Bukti Kelulusan Kepada:</div>
            </div>

            <!-- Student Name -->
            <div class="name-container">
                <div class="name"><?= esc(strtoupper($siswa->nama)) ?></div>
            </div>

            <!-- Main Content -->
            <div class="main-text">
                <?= ($isSmk ?? false) ? 'Nomor Induk Siswa (NIS)' : 'Nomor Induk Mahasiswa (NIM)' ?>: <strong><?= esc($siswa->nim) ?></strong>. 
                Telah berhasil menyelesaikan program <strong>Praktek Kerja Lapangan (PKL) / Magang</strong><br>
                pada Divisi <strong><?= esc($lamaran->tempat) ?></strong> PT PLN (Persero) UBP Asam Asam<br>
                periode tanggal <strong><?= date('d/m/Y', strtotime($siswa->tgl_masuk)) ?></strong> s/d <strong><?= date('d/m/Y', strtotime($siswa->tgl_selesai)) ?></strong>.
            </div>

            <!-- Capaian / Predikat -->
            <div class="score-container">
                <div class="score-box">
                    <span class="score-text">Nilai Rata-Rata Akhir: <?= number_format($rata, 2) ?> &nbsp;&bull;&nbsp; Predikat: <?= esc($grade) ?></span>
                </div>
            </div>

            <!-- Signatures -->
            <table class="signature-table">
                <tr>
                    <td class="signature-cell left">
                        Mengetahui,<br>
                        <strong>Pembimbing Lapangan</strong>
                        <div class="signature-space"></div>
                        <div class="signature-name"><?= esc($lamaran->nama_pembimbing ?? 'Pembimbing Lapangan') ?></div>
                        <div class="signature-line"></div>
                        <div class="signature-title">PT PLN (Persero) UBP Asam Asam</div>
                    </td>
                    <td class="signature-cell right">
                        Asam Asam, <?= date('d F Y') ?><br>
                        <strong>Manager / Admin SIAMAM</strong>
                        <div class="signature-space"></div>
                        <div class="signature-name">Admin Sistem Magang</div>
                        <div class="signature-line"></div>
                        <div class="signature-title">PT PLN (Persero) UBP Asam Asam</div>
                    </td>
                </tr>
            </table>
    </div>
</body>
</html>
