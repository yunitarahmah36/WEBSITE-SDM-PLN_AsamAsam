<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<style>
    /* =========================================
       LOGBOOK SISWA - MODERN ENTERPRISE UI
       ========================================= */
    :root {
        --pln-blue: #1A73E8;
        --pln-blue-dark: #1256b0;
        --pln-blue-light: #eff6ff;
        --pln-cyan: #00c5ff;
        --pln-emerald: #10b981;
        --pln-amber: #f59e0b;
        --pln-rose: #ef4444;
    }

    /* --- SIDEBAR DAFTAR SISWA --- */
    .student-sidebar-card {
        border: 1px solid #e9edf3;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
    }

    .student-nav-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 14px;
    }

    .btn-back-tempat {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back-tempat:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateX(-2px);
    }

    .student-nav-item {
        display: block;
        padding: 10px 12px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid #edf2f7;
        margin-bottom: 8px;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .student-nav-item:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateX(3px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.04);
    }

    .student-nav-item.active {
        background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%) !important;
        border-color: #93c5fd !important;
        border-left: 4px solid #1A73E8 !important;
        box-shadow: 0 4px 14px rgba(26, 115, 232, 0.12) !important;
    }

    .student-nav-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 2px 5px rgba(0,0,0,0.06);
    }

    .student-nav-initials {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #e0f2fe 0%, #dbeafe 100%);
        color: #0284c7;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid #bfdbfe;
    }

    .student-nav-item.active .student-nav-initials {
        background: linear-gradient(135deg, #1A73E8 0%, #0284c7 100%);
        color: #ffffff;
        border-color: #1A73E8;
    }

    /* Status Badges on Student Sidebar */
    .student-badge-selesai {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
        padding: 3px 8px !important;
        border-radius: 6px !important;
        background-color: #1865cf !important;
        color: #ffffff !important;
        text-transform: uppercase !important;
        letter-spacing: 0.4px !important;
        box-shadow: 0 1px 3px rgba(24, 101, 207, 0.25) !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
        white-space: nowrap !important;
    }

    .btn-pln-blue {
        background: linear-gradient(135deg, #1865cf, #2563eb) !important;
        color: #ffffff !important;
        border: none !important;
    }
    .btn-pln-blue:hover {
        opacity: 0.92;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(24, 101, 207, 0.3) !important;
    }
    .btn-outline-pln-blue {
        border: 1.5px solid #1865cf !important;
        color: #1865cf !important;
        background: transparent !important;
        transition: all 0.2s ease !important;
    }
    .btn-outline-pln-blue:hover {
        background: #1865cf !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(24, 101, 207, 0.25) !important;
    }
    .badge-pln-blue {
        background-color: #1865cf !important;
        color: #ffffff !important;
    }

    .student-badge-aktif {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
        padding: 3px 8px !important;
        border-radius: 6px !important;
        background-color: #e91e63 !important;
        color: #ffffff !important;
        text-transform: uppercase !important;
        letter-spacing: 0.4px !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
        white-space: nowrap !important;
    }

    /* --- HERO SUMMARY CARD --- */
    .student-hero-card {
        border-radius: 14px;
        border: 1px solid #e9edf3;
        background: #ffffff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }

    .student-hero-avatar {
        width: 68px;
        height: 68px;
        border-radius: 14px;
        object-fit: cover;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        border: 2px solid #ffffff;
    }

    .student-hero-initials {
        width: 68px;
        height: 68px;
        border-radius: 14px;
        background: linear-gradient(135deg, #1A73E8 0%, #0ea5e9 100%);
        color: #ffffff;
        font-size: 24px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(26, 115, 232, 0.25);
        border: 2px solid #ffffff;
    }

    .quick-stat-box {
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .quick-stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
    }

    /* --- STATUS BANNER CARDS (LAPORAN & PENILAIAN) --- */
    .banner-status-card {
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
        transition: all 0.2s ease;
    }

    .banner-status-card.card-laporan {
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        border-color: #bfdbfe;
        border-left: 5px solid #1865cf;
    }

    .banner-status-card.card-penilaian {
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
        border-color: #bfdbfe;
        border-left: 5px solid #1865cf;
    }

    .banner-status-card.card-warning-state {
        background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
        border-color: #fde68a;
        border-left: 5px solid #f59e0b;
    }

    .banner-status-card.card-locked-state {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-color: #e2e8f0;
        border-left: 5px solid #94a3b8;
    }

    .banner-icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    /* --- LOGBOOK MAIN CARD & ITEMS --- */
    .logbook-card {
        border-radius: 14px;
        border: 1px solid #e9edf3;
        background: #ffffff;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }

    .logbook-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.25s ease;
        overflow: hidden;
    }

    .logbook-item.status-approved {
        border-left: 4px solid #1865cf;
    }

    .logbook-item.status-pending {
        border-left: 4px solid #f59e0b;
    }

    .logbook-item.status-rejected {
        border-left: 4px solid #ef4444;
    }

    .logbook-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(52, 71, 103, 0.07);
    }

    .logbook-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 18px;
        cursor: pointer;
        background: #fbfcfe;
        border-bottom: 1px solid transparent;
        transition: background 0.2s ease;
    }

    .logbook-header:hover {
        background: #f8fafc;
    }

    .logbook-item.open .logbook-header {
        background: #f1f5f9;
        border-bottom-color: #e2e8f0;
    }

    .logbook-header-left {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
        flex: 1;
    }

    .logbook-tanggal {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .logbook-jam {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .logbook-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .logbook-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: center;
    }

    .badge-logbook {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 1px solid transparent;
    }

    .badge-hadir { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .badge-izin { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .badge-sakit { background: #fdf2f8; color: #be185d; border-color: #fbcfe8; }
    .badge-alfa { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
    .badge-pending { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .badge-approved { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .badge-rejected { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
    .badge-telat { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

    .expand-icon {
        color: #64748b;
        transition: transform 0.25s ease;
        font-size: 20px;
    }

    .logbook-body {
        padding: 16px 18px;
        display: none;
        background: #ffffff;
    }

    .logbook-item.open .logbook-body {
        display: block;
    }

    /* --- DESKRIPSI KEGIATAN --- */
    .kegiatan-table {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 14px;
        padding: 14px 18px;
        box-sizing: border-box;
    }

    .kegiatan-deskripsi {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        text-align: left !important;
    }

    .kegiatan-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 8px;
        text-align: left !important;
    }

    .kegiatan-deskripsi-text {
        display: inline-block !important;
        width: auto !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 0 0 12px !important;
        border-left: 3px solid #1A73E8 !important;
        box-sizing: border-box !important;
        font-size: 13.5px !important;
        line-height: 1.75 !important;
        color: #334155 !important;
        text-align: left !important;
        white-space: pre-wrap !important;
        word-break: break-word !important;
    }

    .logbook-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        padding-top: 14px;
        border-top: 1px dashed #e2e8f0;
    }

    .btn-bukti {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-bukti:hover {
        background: #1d4ed8;
        color: #ffffff;
        border-color: #1d4ed8;
        text-decoration: none;
    }

    .btn-action-group {
        display: flex;
        gap: 8px;
    }

    .btn-acc {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: #059669;
        color: #ffffff;
        border: 1px solid #059669;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .btn-acc:hover:not(:disabled) {
        background: #047857;
        border-color: #047857;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(5, 150, 105, 0.3);
    }

    .btn-acc:disabled {
        background: #e2e8f0;
        border-color: #cbd5e1;
        color: #64748b;
        cursor: not-allowed;
        opacity: 0.85;
    }

    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 18px;
        background: #ffffff;
        color: #dc2626;
        border: 1px solid #f87171;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .btn-reject:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(220, 38, 38, 0.25);
    }

    .logbook-empty {
        text-align: center;
        padding: 50px 20px;
        color: #64748b;
    }

    .logbook-empty i {
        font-size: 54px;
        color: #cbd5e1;
        display: block;
        margin-bottom: 14px;
    }

    /* Filter tabs */
    .filter-tab-btn {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .filter-tab-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }

    .filter-tab-btn.active {
        background: #1A73E8;
        border-color: #1A73E8;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(26, 115, 232, 0.3);
    }

    @media (max-width: 575px) {
        .logbook-header { padding: 12px; }
        .logbook-actions { flex-direction: column; align-items: stretch; }
        .btn-bukti, .btn-acc, .btn-reject { width: 100%; justify-content: center; }
        .btn-action-group { width: 100%; }
        .btn-action-group > * { flex: 1; }
    }

    /* Modal Tambah & Edit Nilai */
    #manilai .modal-dialog,
    #menilai .modal-dialog {
        --bs-modal-width: 680px;
        max-width: 680px;
    }

    #manilai .input-group.input-group-outline .form-label,
    #menilai .input-group.input-group-outline .form-label {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: calc(100% - 12px);
    }

    #manilai .input-group.input-group-outline.is-focused .form-label + .form-control,
    #manilai .input-group.input-group-outline.is-filled .form-label + .form-control,
    #menilai .input-group.input-group-outline.is-focused .form-label + .form-control,
    #menilai .input-group.input-group-outline.is-filled .form-label + .form-control {
        border-color: #1A73E8 !important;
        border-top-color: transparent !important;
        box-shadow: inset 1px 0 #1A73E8, inset -1px 0 #1A73E8, inset 0 -1px #1A73E8 !important;
    }

    #manilai .input-group.input-group-outline.is-focused .form-label,
    #manilai .input-group.input-group-outline.is-filled .form-label,
    #menilai .input-group.input-group-outline.is-focused .form-label,
    #menilai .input-group.input-group-outline.is-filled .form-label {
        color: #1A73E8 !important;
    }

    #manilai .input-group.input-group-outline.is-focused .form-label:before,
    #manilai .input-group.input-group-outline.is-focused .form-label:after,
    #manilai .input-group.input-group-outline.is-filled .form-label:before,
    #manilai .input-group.input-group-outline.is-filled .form-label:after,
    #menilai .input-group.input-group-outline.is-focused .form-label:before,
    #menilai .input-group.input-group-outline.is-focused .form-label:after,
    #menilai .input-group.input-group-outline.is-filled .form-label:before,
    #menilai .input-group.input-group-outline.is-filled .form-label:after {
        border-top-color: #1A73E8 !important;
        box-shadow: inset 0 1px #1A73E8 !important;
    }

    #manilai .input-group.input-group-outline .form-control:focus,
    #menilai .input-group.input-group-outline .form-control:focus {
        border-color: #1A73E8 !important;
    }
</style>

<div class="row">
    <!-- Kolom Kiri: Navigasi Siswa Bimbingan -->
    <div class="col-12 col-lg-4 col-xl-3 mb-4">
        <div class="card student-sidebar-card p-3">
            <div class="student-nav-header">
                <div class="d-flex align-items-center gap-2">
                    <a href="<?= base_url('/logbook/' . $idt) ?>" class="btn-back-tempat" title="Kembali ke Daftar Bimbingan">
                        <i class="fa fa-arrow-left text-xs"></i>
                    </a>
                    <div>
                        <h6 class="mb-0 font-weight-bold text-dark text-sm">Daftar Siswa</h6>
                        <small class="text-xxs text-muted">Peserta di tempat ini</small>
                    </div>
                </div>
                <span class="badge bg-light text-secondary border text-xxs"><?= count($siswas) ?> Siswa</span>
            </div>

            <div class="student-list-container">
                <?php foreach ($siswas as $s) : ?>
                    <?php 
                    $isSelected = ($s->nim == $siswa->nim); 
                    $sStatus = getStatusSiswa($s->sid ?? $s->id_siswa ?? null);
                    $initials = '';
                    $words = explode(' ', trim($s->nama));
                    if (count($words) >= 2) {
                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($s->nama, 0, 2));
                    }
                    ?>
                    <a href="<?= base_url('/logbook/' . $idt . '/' . $s->nim) ?>" class="student-nav-item <?= $isSelected ? 'active' : '' ?>">
                        <div class="d-flex align-items-center gap-2">
                            <?php if (!empty($s->foto) && $s->foto != 'default.jpg' && file_exists(FCPATH . 'assets/img/foto_siswa/' . $s->foto)) : ?>
                                <img src="<?= base_url('assets/img/foto_siswa/' . $s->foto) ?>" alt="<?= esc($s->nama) ?>" class="student-nav-avatar">
                            <?php else : ?>
                                <div class="student-nav-initials">
                                    <?= $initials ?>
                                </div>
                            <?php endif; ?>

                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex align-items-center justify-content-between mb-1 gap-1">
                                    <div class="font-weight-bold text-sm text-truncate" title="<?= esc($s->nama) ?>" style="min-width: 0; color: <?= $isSelected ? '#1865cf' : '#1e293b' ?> !important;">
                                        <?= esc($s->nama) ?>
                                    </div>
                                    <?php if ($s->status == 'selesai' || $sStatus == 'selesai') : ?>
                                        <span class="student-badge-selesai flex-shrink-0">Selesai</span>
                                    <?php elseif ($s->status == 'unfinish' || $sStatus == 'unfinish') : ?>
                                        <span class="badge badge-secondary text-xxs flex-shrink-0" style="font-size: 9px; padding: 2px 6px; background-color: #6c757d; color: #fff;">Tidak Aktif</span>
                                    <?php else : ?>
                                        <span class="badge badge-success text-xxs flex-shrink-0" style="font-size: 9px; padding: 2px 6px; background-color: #2dce89; color: #fff;">Aktif</span>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-xxs text-muted"><?= esc($s->nim) ?></span>
                                </div>
                            </div>

                            <i class="fa fa-chevron-right text-xs flex-shrink-0 ms-1" style="color: <?= $isSelected ? '#1865cf' : '#94a3b8' ?> !important; opacity: <?= $isSelected ? '1' : '0.4' ?>;"></i>
                        </div>
                    </a>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Rincian Siswa, Status Magang & Logbook -->
    <div class="col-12 col-lg-8 col-xl-9 mb-4">
        <?php
        $status_siswa_terpilih = getStatusSiswa($siswa->id);
        $isSmk = false;
        if (isset($siswa)) {
            $instansiLower = strtolower($siswa->instansi ?? '');
            $kelasLower = strtolower($siswa->kelas ?? '');
            if (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12'])) {
                $isSmk = true;
            }
        }

        // Quick attendance statistics
        $totalLogbook = count($logbooks);
        $countHadir = 0;
        $countIzin = 0;
        $countSakit = 0;
        $countAlfa = 0;
        $countPending = 0;
        $countApproved = 0;
        $countRejected = 0;

        foreach ($logbooks as $l) {
            $ket = strtolower($l->keterangan ?? '');
            if ($ket === 'hadir') $countHadir++;
            elseif ($ket === 'izin') $countIzin++;
            elseif ($ket === 'sakit') $countSakit++;
            elseif ($ket === 'alfa') $countAlfa++;

            if ($l->status === 'approved') $countApproved++;
            elseif ($l->status === 'rejected') $countRejected++;
            else $countPending++;
        }

        $heroInitials = '';
        $words = explode(' ', trim($siswa->nama ?? ''));
        if (count($words) >= 2) {
            $heroInitials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else {
            $heroInitials = strtoupper(substr($siswa->nama ?? 'S', 0, 2));
        }
        ?>

        <!-- Student Hero Card -->
        <div class="card student-hero-card mb-3 p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($siswa->foto) && $siswa->foto != 'default.jpg' && file_exists(FCPATH . 'assets/img/foto_siswa/' . $siswa->foto)) : ?>
                        <img src="<?= base_url('assets/img/foto_siswa/' . $siswa->foto) ?>" alt="<?= esc($siswa->nama) ?>" class="student-hero-avatar">
                    <?php else : ?>
                        <div class="student-hero-initials">
                            <?= $heroInitials ?>
                        </div>
                    <?php endif; ?>

                    <div>
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h5 class="mb-0 font-weight-bolder text-dark"><?= esc($siswa->nama) ?></h5>
                            <?php if ($status_siswa_terpilih == 'selesai') : ?>
                                <span class="badge badge-pln-blue text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                    <i class="fa fa-check-circle me-1"></i> Selesai Magang
                                </span>
                            <?php elseif ($status_siswa_terpilih == 'unfinish') : ?>
                                <span class="badge bg-secondary text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                    <i class="fa fa-info-circle me-1"></i> Tidak Aktif (Tidak Menyelesaikan Magang)
                                </span>
                            <?php else : ?>
                                <span class="badge bg-info text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                    <i class="fa fa-clock me-1"></i> Sedang Berjalan
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-wrap align-items-center text-xs text-muted gap-2">
                            <span><i class="fa fa-id-card me-1 text-secondary"></i><strong><?= getIdentityLabel($siswa) ?>:</strong> <?= esc($siswa->nim) ?></span>
                            <span>•</span>
                            <span><i class="fa fa-university me-1 text-secondary"></i><?= esc($siswa->instansi ?? $breadcrumb[2] ?? 'PLN Asam Asam') ?></span>
                            <?php if (!empty($siswa->jurusan)) : ?>
                                <span>•</span>
                                <span><i class="fa fa-graduation-cap me-1 text-secondary"></i><?= esc($siswa->jurusan) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-pln-blue mb-0 shadow-none d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#mvsiswa">
                        <i class="fa fa-user me-1"></i> Lihat Profil Lengkap
                    </button>
                </div>
            </div>

            <!-- Attendance & Progress Quick Stats -->
            <div class="row g-2 mt-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="quick-stat-box p-2 bg-light">
                        <div class="text-xxs text-uppercase text-muted font-weight-bold">Total Logbook</div>
                        <div class="h6 mb-0 font-weight-bolder text-dark"><?= $totalLogbook ?> <span class="text-xxs font-weight-normal text-muted">Hari</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="quick-stat-box p-2" style="background: #eff6ff; border-color: #bfdbfe;">
                        <div class="text-xxs text-uppercase font-weight-bold" style="color: #1e40af;">Hadir</div>
                        <div class="h6 mb-0 font-weight-bolder" style="color: #1d4ed8;"><?= $countHadir ?> <span class="text-xxs font-weight-normal text-muted">Hari</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="quick-stat-box p-2" style="background: #fffbeb; border-color: #fde68a;">
                        <div class="text-xxs text-uppercase text-warning font-weight-bold">Izin / Sakit / Alfa</div>
                        <div class="h6 mb-0 font-weight-bolder text-warning"><?= ($countIzin + $countSakit + $countAlfa) ?> <span class="text-xxs font-weight-normal text-muted">Hari</span></div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="quick-stat-box p-2" style="<?= $countPending > 0 ? 'background: #fff7ed; border-color: #fed7aa;' : 'background: #f8fafc; border-color: #e2e8f0;' ?>">
                        <div class="text-xxs text-uppercase font-weight-bold <?= $countPending > 0 ? 'text-danger' : 'text-secondary' ?>">
                            <?= $countPending > 0 ? 'Perlu ACC' : 'Verifikasi' ?>
                        </div>
                        <div class="h6 mb-0 font-weight-bolder <?= $countPending > 0 ? 'text-danger' : 'text-dark' ?>">
                            <?= $countPending > 0 ? $countPending . ' Pending' : 'Semua Terverifikasi' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================
             STATUS BANNER: LAPORAN AKHIR & PENILAIAN TERPISAH
             ========================================= -->
        <?php if ($status_siswa_terpilih == 'selesai' || (!empty($siswa->tgl_selesai) && date("Y-m-d") > $siswa->tgl_selesai)) : ?>
            <?php 
            $hasFormatPenilaian = !empty($konfigPenilaian) && (!empty($konfigPenilaian->atribut) || !empty($konfigPenilaian->file_format));
            $isReportUploaded = !empty($siswa->laporan);
            $isReportApproved = !empty($siswa->is_laporan_approved);
            $canGrade = $isReportApproved || $isSmk;
            $hasGrade = nilaiAvailable($siswa->id);
            ?>

            <!-- CARD 1: STATUS LAPORAN AKHIR SISWA -->
            <div class="card banner-status-card <?= $isReportApproved ? 'card-laporan' : ($isReportUploaded ? 'card-warning-state' : 'card-locked-state') ?> mb-3 p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="banner-icon-circle" style="background: <?= $isReportApproved ? '#eff6ff' : ($isReportUploaded ? '#fffbeb' : '#f8fafc') ?>; border: 1px solid <?= $isReportApproved ? '#bfdbfe' : ($isReportUploaded ? '#fde68a' : '#e2e8f0') ?>; color: <?= $isReportApproved ? '#1865cf' : ($isReportUploaded ? '#f59e0b' : '#64748b') ?>;">
                            <i class="fa <?= $isReportApproved ? 'fa-file-signature' : ($isReportUploaded ? 'fa-file-pdf' : 'fa-file-alt') ?> fs-4"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h6 class="mb-0 text-dark font-weight-bolder">Laporan Akhir Siswa</h6>
                                <?php if ($isReportApproved) : ?>
                                    <span class="badge badge-pln-blue text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-check-circle me-1"></i> Disetujui
                                    </span>
                                <?php elseif ($isReportUploaded) : ?>
                                    <span class="badge bg-warning text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-clock me-1"></i> Menunggu Verifikasi
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-secondary text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <?= $isSmk ? 'Opsional (SMK)' : 'Belum Diunggah' ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-muted mb-0">
                                <?php if ($isReportApproved) : ?>
                                    Berkas laporan akhir siswa telah diverifikasi dan disetujui oleh pembimbing lapangan.
                                <?php elseif ($isReportUploaded) : ?>
                                    Laporan akhir telah diunggah siswa. Silakan periksa berkas lalu setujui untuk membuka pengisian nilai.
                                <?php else : ?>
                                    <?= $isSmk ? 'Siswa SMK tidak diwajibkan mengunggah berkas laporan akhir magang.' : 'Siswa belum mengunggah berkas laporan akhir magang. Pengisian nilai dibuka setelah laporan diunggah dan disetujui.' ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <?php if ($isReportUploaded) : ?>
                            <a href="/assets/laporan/<?= $siswa->laporan ?>" target="_blank" class="btn btn-sm btn-outline-pln-blue mb-0 d-inline-flex align-items-center gap-1 shadow-none">
                                <i class="fa fa-file-pdf text-danger"></i> LIHAT LAPORAN
                            </a>
                            <?php if ($isReportApproved) : ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-0 d-inline-flex align-items-center gap-1 shadow-none btn-report-approve" data-approved="0" data-id="<?= $siswa->id ?>" title="Batalkan status persetujuan laporan">
                                    <i class="fa fa-undo"></i> BATAL ACC
                                </button>
                            <?php else : ?>
                                <button type="button" class="btn btn-sm btn-pln-blue text-white mb-0 d-inline-flex align-items-center gap-1 shadow-sm btn-report-approve" data-approved="1" data-id="<?= $siswa->id ?>">
                                    <i class="fa fa-check-circle"></i> SETUJUI LAPORAN
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CARD 2: STATUS PENILAIAN CAPAIAN MAGANG -->
            <div class="card banner-status-card <?= $hasGrade ? 'card-penilaian' : ($canGrade ? 'card-warning-state' : 'card-locked-state') ?> mb-3 p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="banner-icon-circle" style="background: <?= $hasGrade ? '#eff6ff' : ($canGrade ? '#fffbeb' : '#f8fafc') ?>; border: 1px solid <?= $hasGrade ? '#bfdbfe' : ($canGrade ? '#fde68a' : '#e2e8f0') ?>; color: <?= $hasGrade ? '#1865cf' : ($canGrade ? '#f59e0b' : '#64748b') ?>;">
                            <i class="fa <?= $hasGrade ? 'fa-graduation-cap' : ($canGrade ? 'fa-award' : 'fa-lock') ?> fs-4"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h6 class="mb-0 text-dark font-weight-bolder">Penilaian Capaian Magang</h6>
                                <?php if ($hasGrade) : ?>
                                    <span class="badge badge-pln-blue text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-check-double me-1"></i> Sudah Dinilai
                                    </span>
                                <?php elseif ($canGrade) : ?>
                                    <span class="badge bg-warning text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-edit me-1"></i> Belum Dinilai
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-secondary text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-lock me-1"></i> Terkunci
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-muted mb-0">
                                <?php if ($hasGrade) : ?>
                                    Nilai capaian kompetensi magang telah terisi. Anda dapat melihat detail nilai atau melakukan pembaruan jika diperlukan.
                                <?php elseif ($canGrade) : ?>
                                    Laporan akhir telah disetujui. Silakan inputkan penilaian capaian kompetensi siswa magang.
                                <?php else : ?>
                                    Penilaian baru dapat diinputkan setelah laporan akhir siswa disetujui oleh pembimbing.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <?php if ($hasGrade) : ?>
                            <button type="button" class="btn btn-sm btn-outline-pln-blue mb-0 d-inline-flex align-items-center gap-1 shadow-none" data-bs-toggle="modal" data-bs-target="#mdnilai">
                                <i class="fa fa-eye"></i> DETAIL NILAI
                            </button>
                            <a href="<?= base_url('/logbook/' . $idt . '/' . $siswa->nim . '/nilai') ?>" class="btn btn-sm btn-pln-blue text-white mb-0 d-inline-flex align-items-center gap-1 shadow-sm">
                                <i class="fa fa-edit"></i> EDIT NILAI
                            </a>
                            <?php if (!empty($konfigPenilaian->file_format)) : ?>
                                <a href="<?= base_url('assets/format_penilaian/' . $konfigPenilaian->file_format) ?>" target="_blank" class="btn btn-sm btn-outline-warning text-dark mb-0 d-inline-flex align-items-center gap-1 shadow-none" title="Unduh Lembar Format Penilaian dari Instansi">
                                    <i class="fa fa-file-pdf text-danger"></i> FORMAT INSTANSI
                                </a>
                            <?php endif; ?>
                        <?php elseif ($canGrade) : ?>
                            <a href="<?= base_url('/logbook/' . $idt . '/' . $siswa->nim . '/nilai') ?>" class="btn btn-sm btn-pln-blue text-white mb-0 d-inline-flex align-items-center gap-1 shadow-sm">
                                <i class="fa fa-pen"></i> INPUT NILAI
                            </a>
                            <?php if (!empty($konfigPenilaian->file_format)) : ?>
                                <a href="<?= base_url('assets/format_penilaian/' . $konfigPenilaian->file_format) ?>" target="_blank" class="btn btn-sm btn-outline-warning text-dark mb-0 d-inline-flex align-items-center gap-1 shadow-none" title="Unduh Lembar Format Penilaian dari Instansi">
                                    <i class="fa fa-file-pdf text-danger"></i> FORMAT INSTANSI
                                </a>
                            <?php endif; ?>
                        <?php else : ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary mb-0 d-inline-flex align-items-center gap-1 shadow-none" disabled title="Perlu persetujuan laporan akhir terlebih dahulu">
                                <i class="fa fa-lock"></i> INPUT NILAI
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($canGrade && !$hasGrade) : ?>
                    <?php if (!$hasFormatPenilaian) : ?>
                        <div class="alert alert-warning text-white mt-3 mb-0 text-xs py-2 px-3" role="alert" style="background: #f59e0b; border: none; border-radius: 8px;">
                            <i class="fa fa-info-circle me-1"></i>
                            <strong>Pemberitahuan:</strong> Instansi asal peserta (<strong><?= esc($konfigPenilaian->instansi->nama_instansi ?? $siswa->instansi ?? 'Belum ditentukan') ?></strong>) belum mengirimkan lembar format penilaian khusus. Pembimbing lapangan dapat tetap melakukan penilaian dengan format standar PLN dengan mengklik tombol <strong>INPUT NILAI</strong>.
                        </div>
                    <?php elseif ($hasFormatPenilaian && !empty($konfigPenilaian->file_format)) : ?>
                        <div class="alert alert-info text-white mt-3 mb-0 text-xs py-2 px-3" role="alert" style="background: #0284c7; border: none; border-radius: 8px;">
                            <i class="fa fa-info-circle me-1"></i>
                            Instansi telah mengirimkan lembar format penilaian (<strong><?= esc($konfigPenilaian->nama_file_asli ?: $konfigPenilaian->file_format) ?></strong>). Silakan unduh format tersebut lalu klik tombol <strong>INPUT NILAI</strong> untuk mengisi nilai sesuai lembar.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- CARD 3: STATUS & UPLOAD SERTIFIKAT MAGANG OLEH PEMBIMBING/ADMIN -->
            <?php 
            $hasCertFile = !empty($siswa->sertifikat_file);
            $isSafetyReady = !empty($siswa->is_safety_ready);
            $isSyaratLengkap = $isSafetyReady && ($isReportApproved || ($isSmk && empty($siswa->laporan))) && $hasGrade;
            ?>
            <div class="card banner-status-card <?= $hasCertFile ? 'card-laporan' : ($isSyaratLengkap ? 'card-warning-state' : 'card-locked-state') ?> mb-3 p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="banner-icon-circle" style="background: <?= $hasCertFile ? '#eff6ff' : ($isSyaratLengkap ? '#fffbeb' : '#f8fafc') ?>; border: 1px solid <?= $hasCertFile ? '#bfdbfe' : ($isSyaratLengkap ? '#fde68a' : '#e2e8f0') ?>; color: <?= $hasCertFile ? '#1865cf' : ($isSyaratLengkap ? '#f59e0b' : '#64748b') ?>;">
                            <i class="fa fa-award fs-4"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h6 class="mb-0 text-dark font-weight-bolder">Sertifikat Magang Siswa</h6>
                                <?php if ($hasCertFile) : ?>
                                    <span class="badge badge-pln-blue text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-check-circle me-1"></i> Sudah Diupload
                                    </span>
                                <?php elseif ($isSyaratLengkap) : ?>
                                    <span class="badge bg-warning text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-clock me-1"></i> Menunggu Upload Berkas
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-secondary text-xxs px-2 py-1 d-inline-flex align-items-center" style="line-height: 1.2;">
                                        <i class="fa fa-lock me-1"></i> Terkunci
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-muted mb-0">
                                <?php if ($hasCertFile) : ?>
                                    Berkas sertifikat resmi yang sudah bernama dan bertanda tangan telah diunggah. Siswa dapat mengunduhnya melalui akun mereka.
                                <?php elseif ($isSyaratLengkap) : ?>
                                    Seluruh persyaratan (Safety K3, Laporan Akhir, dan Nilai) telah lengkap. Silakan unggah sertifikat resmi yang sudah bernama.
                                <?php elseif (!$isSafetyReady) : ?>
                                    Pengunggahan sertifikat terkunci: Siswa belum menyelesaikan Materi Safety & K3.
                                <?php elseif (!$isReportApproved && !($isSmk && empty($siswa->laporan))) : ?>
                                    Pengunggahan sertifikat terkunci: Laporan akhir siswa belum disetujui.
                                <?php else : ?>
                                    Pengunggahan sertifikat terbuka setelah seluruh penilaian capaian magang diinputkan.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <?php if ($hasCertFile) : ?>
                            <a href="/sertifikat/download/<?= $siswa->id ?>" class="btn btn-sm btn-pln-blue mb-0 d-inline-flex align-items-center gap-1 shadow-sm" title="Unduh berkas resmi yang telah diunggah">
                                <i class="fa fa-download"></i> UNDUH RESMI
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-pln-blue mb-0 d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUploadSertifikatLogbook">
                                <i class="fa fa-edit"></i> EDIT SERTIFIKAT
                            </button>
                        <?php elseif ($isSyaratLengkap) : ?>
                            <button type="button" class="btn btn-sm btn-pln-blue text-white mb-0 d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUploadSertifikatLogbook">
                                <i class="fa fa-upload"></i> UPLOAD SERTIFIKAT
                            </button>
                        <?php else : ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary mb-0 d-inline-flex align-items-center gap-1 shadow-none" disabled style="cursor: not-allowed;" title="Sertifikat belum dapat diunggah: Persyaratan (Safety, Laporan, Nilai) belum lengkap">
                                <i class="fa fa-lock"></i> UPLOAD SERTIFIKAT
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modal Upload Sertifikat Logbook -->
            <div class="modal fade" id="modalUploadSertifikatLogbook" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalUploadSertifikatLogbookLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                        <div class="modal-header border-bottom pb-3">
                            <div>
                                <h6 class="modal-title font-weight-bolder text-dark mb-0" id="modalUploadSertifikatLogbookLabel">Upload Sertifikat Resmi Bernama</h6>
                                <small class="text-xxs text-muted">Unggah sertifikat akhir yang sudah bernama dan bertanda tangan</small>
                            </div>
                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="/sertifikat/upload" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_siswa" value="<?= $siswa->id ?>">
                            <div class="modal-body py-3">
                                <div class="alert alert-info text-white text-xs py-2 px-3 mb-3" style="border-radius: 8px;">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Siswa baru dapat mengunduh sertifikat magang setelah Anda mengunggah berkas sertifikat resmi berikut.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-xs text-secondary font-weight-bold mb-1">Nama Mahasiswa / Siswa</label>
                                    <input type="text" class="form-control bg-light" readonly value="<?= esc($siswa->nama) ?> (<?= esc($siswa->nim) ?>)" style="padding: 8px 12px; font-weight: bold; border-radius: 6px;">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-xs text-secondary font-weight-bold mb-1">Pilih Berkas Sertifikat (Format: PDF)</label>
                                    <input type="file" name="sertifikat_file" class="form-control" accept=".pdf" required style="border: 1px solid #d2d6da; padding: 8px 12px; border-radius: 6px;">
                                    <small class="text-muted text-xxs mt-1 d-block">
                                        <i class="fa fa-file-pdf text-danger me-1"></i> Pastikan berkas berformat <strong>PDF</strong> resmi yang telah ditandatangani/distempel.
                                    </small>
                                </div>
                            </div>
                            <div class="modal-footer border-top pt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-0" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary btn-sm mb-0 shadow-sm d-inline-flex align-items-center gap-1">
                                    <i class="fa fa-upload"></i> Unggah Sertifikat
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php if (nilaiAvailable($siswa->id)) : ?>
                <?php 
                $nilai = getNilaiSiswa($siswa->id);
                $nilaiDetails = $nilai ? getDetailNilaiSiswa($nilai->id) : [];
                $isModeBerkas = (!empty($nilai->file_penilaian) && (($nilai->metode_penilaian ?? '') === 'file' || empty($nilai->total_nilai) || (!empty($nilaiDetails) && count($nilaiDetails) === 1 && $nilaiDetails[0]->nama_atribut === 'Berkas Lembar Penilaian Resmi Pembimbing Lapangan')));
                ?>
                <!-- modal detail nilai -->
                <div class="modal fade" id="mdnilai" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="mdnilai" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card card-body">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3 pb-2 border-bottom">
                                        <h6 class="m-0 font-weight-bolder text-dark">
                                            <i class="fa fa-award text-success me-1"></i> Capaian Hasil Kegiatan Magang
                                        </h6>
                                        <div class="d-flex flex-wrap align-items-center gap-1">
                                            <span class="badge bg-gradient-info text-xxs">
                                                <?= esc($konfigPenilaian->instansi->nama_instansi ?? $siswa->instansi) ?>
                                            </span>
                                            <?php if (!empty($konfigPenilaian->file_format)) : ?>
                                                <a href="<?= base_url('assets/format_penilaian/' . $konfigPenilaian->file_format) ?>" target="_blank" class="badge bg-gradient-warning text-xxs text-white" title="Lihat Dokumen Format Asli Instansi">
                                                    <i class="fa fa-file-pdf me-1"></i> Format Asli
                                                </a>
                                            <?php endif; ?>
                                            <?php if (!empty($nilai->file_penilaian)) : ?>
                                                <a href="<?= base_url('assets/file_penilaian/' . $nilai->file_penilaian) ?>" target="_blank" class="badge bg-gradient-success text-xxs text-white" title="Buka Berkas Lembar Penilaian Siswa">
                                                    <i class="fa fa-file-pdf me-1"></i> Berkas Siswa
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn-close text-dark ms-1" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.7rem;"></button>
                                        </div>
                                    </div>

                                    <?php if (!empty($nilaiDetails)) : ?>
                                        <?php foreach ($nilaiDetails as $idx => $dt) : ?>
                                            <?php 
                                            $isItemBerkas = ($isModeBerkas || $dt->nama_atribut === 'Berkas Lembar Penilaian Resmi Pembimbing Lapangan' || !empty($nilai->file_penilaian));
                                            ?>
                                            <?php if ($idx > 0) : ?>
                                                <hr class="dark horizontal my-1">
                                            <?php endif; ?>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div>
                                                    <span class="font-weight-normal text-sm mb-0"><?= esc($dt->nama_atribut) ?></span>
                                                    <?php if (!$isItemBerkas && !empty($dt->bobot) && $dt->bobot > 0 && ($nilai->metode_penilaian ?? '') === 'bobot') : ?>
                                                        <span class="text-xxs text-muted ms-1">(Bobot: <?= $dt->bobot ?>%)</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-end">
                                                    <?php if ($isItemBerkas) : ?>
                                                        <?php if (!empty($nilai->file_penilaian)) : ?>
                                                            <a href="<?= base_url('assets/file_penilaian/' . $nilai->file_penilaian) ?>" target="_blank" class="badge bg-gradient-success text-xxs text-white shadow-sm" title="Lihat Berkas Penilaian">
                                                                <i class="fa fa-file-pdf me-1"></i> Lihat Berkas Penilaian
                                                            </a>
                                                        <?php else : ?>
                                                            <span class="badge bg-gradient-success text-xxs">Lihat Berkas Penilaian</span>
                                                        <?php endif; ?>
                                                    <?php elseif ($dt->nilai > 0) : ?>
                                                        <h6 class="mb-0 pb-0 text-sm font-weight-bold"><?= $dt->nilai ?></h6>
                                                    <?php else : ?>
                                                        <h6 class="mb-0 pb-0 text-sm font-weight-bold"><?= $dt->nilai ?></h6>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <?php if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) : ?>
                                            <hr class="dark horizontal my-1">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="font-weight-normal text-sm mb-0 text-info">Nilai Akademik (Dosen)</span>
                                                <h6 class="mb-0 pb-0 text-sm text-info font-weight-bold"><?= $nilai->n_dosen ?></h6>
                                            </div>
                                        <?php endif; ?>

                                        <hr class="dark horizontal mt-3 my-2">
                                        <div class="d-flex justify-content-between align-items-center py-2 px-3 bg-light rounded">
                                            <div>
                                                <h6 class="font-weight-bold text-sm mb-0">Nilai Akhir</h6>
                                                <span class="text-xxs text-muted">
                                                    Metode: <?= $isModeBerkas ? 'Berkas Lembar Penilaian Fisik' : (($nilai->metode_penilaian ?? '') === 'bobot' ? 'Pembobotan Atribut' : 'Rata-rata Atribut') ?>
                                                </span>
                                            </div>
                                            <h5 class="mb-0 text-success font-weight-bolder">
                                                <?php if ($isModeBerkas) : ?>
                                                    <?php if (!empty($nilai->file_penilaian)) : ?>
                                                        <a href="<?= base_url('assets/file_penilaian/' . $nilai->file_penilaian) ?>" target="_blank" class="badge bg-gradient-success text-xs text-white shadow-sm" title="Lihat Berkas Penilaian">
                                                            <i class="fa fa-file-pdf me-1"></i> Lihat Berkas Penilaian
                                                        </a>
                                                    <?php else : ?>
                                                        <span class="badge bg-gradient-success text-xs">Lihat Berkas Penilaian</span>
                                                    <?php endif; ?>
                                                <?php elseif (($nilai->total_nilai ?? 0) > 0) : ?>
                                                    <?= $nilai->total_nilai ?>
                                                <?php else : ?>
                                                    0
                                                <?php endif; ?>
                                            </h5>
                                        </div>
                                        <?php if (!empty($nilai->catatan)) : ?>
                                            <div class="mt-2 p-2 bg-gray-100 rounded text-xs">
                                                <strong>Catatan Pembimbing:</strong> <?= esc($nilai->catatan) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Kedisiplinan</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_disiplin ?></h6>
                                        </div>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Kemauan Kerja / Motivasi</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_motivasi ?></h6>
                                        </div>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Kehadiran</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kehadiran ?></h6>
                                        </div>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Inisiatif dan kreatifitas</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kreatifitas ?></h6>
                                        </div>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Kejujuran dan Tanggung Jawab</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kejujuran ?></h6>
                                        </div>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Kesopanan dan Personalia</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kesopanan ?></h6>
                                        </div>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Kerjasama</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_kerjasama ?></h6>
                                        </div>
                                        <?php
                                        $includeLaporan = !$isSmk || !empty($siswa->laporan);
                                        if ($includeLaporan) :
                                        ?>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0">Laporan</h6>
                                            <h6 class="mb-0 pb-0 text-sm"><?= $nilai->n_laporan ?></h6>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) : ?>
                                        <hr class="dark horizontal my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-normal text-sm mb-0 pb-0 text-info">Nilai Akademik (Dosen)</h6>
                                            <h6 class="mb-0 pb-0 text-sm text-info"><?= $nilai->n_dosen ?></h6>
                                        </div>
                                        <?php endif; ?>

                                        <hr class="dark horizontal mt-3 my-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="font-weight-bold text-lg mb-0 pb-0">Rata-rata</h6>
                                            <h6 class="mb-0 pb-0 text-lg">
                                                <?php
                                                    $items_pembimbing = [
                                                        $nilai->n_disiplin, $nilai->n_motivasi, $nilai->n_kehadiran,
                                                        $nilai->n_kreatifitas, $nilai->n_kejujuran, $nilai->n_kesopanan,
                                                        $nilai->n_kerjasama
                                                    ];
                                                    if ($includeLaporan) {
                                                        $items_pembimbing[] = $nilai->n_laporan;
                                                    }

                                                    $total_pembimbing = 0;
                                                    foreach ($items_pembimbing as $ni) {
                                                        $total_pembimbing += ($ni <= 10) ? ($ni * 10) : $ni;
                                                    }

                                                    $total_nilai = $total_pembimbing;
                                                    $pembagi = $includeLaporan ? 8 : 7;

                                                    if (isset($nilai->n_dosen) && $nilai->n_dosen !== null) {
                                                        $total_nilai += $nilai->n_dosen;
                                                        $pembagi++;
                                                    }
                                                    $rata = $total_nilai / $pembagi;

                                                    echo round($rata, 2);
                                                ?>
                                            </h6>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-4 d-flex gap-2 justify-content-between items-center">
                                        <div>
                                            <button class="badge badge-danger border border-danger btn-hapus-nilai" data-id="<?= $nilai->id ?>"><i class="fa fa-trash text-xxs"></i> Hapus</button>
                                            <a href="<?= base_url('/logbook/' . $idt . '/' . $siswa->nim . '/nilai') ?>" class="badge badge-info border border-info"><i class="fa fa-edit text-xxs"></i> EDIT NILAI</a>
                                        </div>
                                        <button type="button" class="badge badge-secondary border border-secondary btn-close-modal" data-bs-dismiss="modal">CLOSE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal detail nilai end -->

                <!-- modal edit nilai -->
                <div class="modal fade" id="menilai" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="menilai" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card card-plain">
                                    <div class="card-header pb-0 text-left">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="m-0 p-0 font-weight-bolder">Edit Nilai Siswa Magang</h5>
                                                <small class="text-muted">Perbarui skor kriteria penilaian sesuai format yang dikirimkan oleh instansi.</small>
                                            </div>
                                            <span class="badge bg-gradient-info text-xs">
                                                <?= esc($konfigPenilaian->instansi->nama_instansi ?? $siswa->instansi) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form class="formeditnilai">
                                            <input type="hidden" name="id" value="<?= $nilai->id ?>">
                                            <input type="hidden" name="idt" value="<?= $idt ?>">
                                            <input type="hidden" name="ids" value="<?= $siswa->id ?>">
                                            <input type="hidden" name="id_konfigurasi" value="<?= $nilai->id_konfigurasi ?? ($konfigPenilaian->id ?? '') ?>">

                                            <?php if (!empty($konfigPenilaian->file_format)) : ?>
                                                <div class="p-3 mb-3 rounded bg-light border border-info d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                                                    <div>
                                                        <div class="text-xs text-uppercase font-weight-bold text-info">
                                                            <i class="fa fa-file-pdf me-1"></i> Format Penilaian Dikirim Instansi
                                                        </div>
                                                        <div class="text-sm font-weight-bold text-dark">
                                                            <?= esc($konfigPenilaian->nama_file_asli ?: $konfigPenilaian->file_format) ?>
                                                        </div>
                                                        <div class="text-xxs text-muted">
                                                            Buka dokumen untuk mencocokkan kriteria dan bobot yang ditentukan instansi.
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <a href="<?= base_url('assets/format_penilaian/' . $konfigPenilaian->file_format) ?>" target="_blank" class="btn btn-sm bg-gradient-info mb-0 text-nowrap">
                                                            <i class="fa fa-external-link-alt me-1"></i> Buka File
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Metode Perhitungan Nilai -->
                                            <div class="mb-3 p-3 bg-gray-100 rounded">
                                                <label class="form-label text-xs font-weight-bold text-dark mb-1 d-block">
                                                    Metode Perhitungan Nilai Akhir:
                                                </label>
                                                <div class="d-flex flex-wrap gap-4">
                                                    <div class="form-check ps-0 mb-0">
                                                        <input class="form-check-input radio-metode-edit" type="radio" name="metode_penilaian" id="metode_edit_rata" value="rata_rata" form-context="edit" <?= ($nilai->metode_penilaian ?? 'rata_rata') !== 'bobot' ? 'checked' : '' ?>>
                                                        <label class="form-check-label text-xs text-dark font-weight-bold cursor-pointer" for="metode_edit_rata">
                                                            Rata-rata Standar (Unweighted)
                                                        </label>
                                                    </div>
                                                    <div class="form-check ps-0 mb-0">
                                                        <input class="form-check-input radio-metode-edit" type="radio" name="metode_penilaian" id="metode_edit_bobot" value="bobot" form-context="edit" <?= ($nilai->metode_penilaian ?? '') === 'bobot' ? 'checked' : '' ?>>
                                                        <label class="form-check-label text-xs text-dark font-weight-bold cursor-pointer" for="metode_edit_bobot">
                                                            Sistem Pembobotan (Weighted %)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tabel Kriteria Penilaian -->
                                            <div class="table-responsive mb-2">
                                                <table class="table table-bordered table-sm align-items-center mb-0" id="table-kriteria-edit">
                                                    <thead class="bg-gray-200">
                                                        <tr>
                                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 40px;">#</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Aspek / Kriteria Penilaian</th>
                                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder col-bobot-edit" style="width: 125px; <?= ($nilai->metode_penilaian ?? '') === 'bobot' ? '' : 'display: none;' ?>">Bobot (%)</th>
                                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 130px;">Skor (0-100)</th>
                                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 45px;"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbody-kriteria-edit">
                                                        <?php 
                                                        $editItems = [];
                                                        if (!empty($nilaiDetails)) {
                                                            foreach ($nilaiDetails as $dt) {
                                                                if ($dt->nama_atribut === 'Berkas Lembar Penilaian Resmi Pembimbing Lapangan') {
                                                                    continue;
                                                                }
                                                                $editItems[] = [
                                                                    'id_atribut' => $dt->id_atribut,
                                                                    'nama'       => $dt->nama_atribut,
                                                                    'bobot'      => $dt->bobot,
                                                                    'nilai'      => $dt->nilai
                                                                ];
                                                            }
                                                        } elseif (isset($nilai->n_disiplin)) {
                                                            $editItems = [
                                                                ['id_atribut' => null, 'nama' => 'Kedisiplinan', 'bobot' => null, 'nilai' => $nilai->n_disiplin],
                                                                ['id_atribut' => null, 'nama' => 'Kemauan Kerja / Motivasi', 'bobot' => null, 'nilai' => $nilai->n_motivasi],
                                                                ['id_atribut' => null, 'nama' => 'Kehadiran', 'bobot' => null, 'nilai' => $nilai->n_kehadiran],
                                                                ['id_atribut' => null, 'nama' => 'Inisiatif dan kreatifitas', 'bobot' => null, 'nilai' => $nilai->n_kreatifitas],
                                                                ['id_atribut' => null, 'nama' => 'Kejujuran dan Tanggung Jawab', 'bobot' => null, 'nilai' => $nilai->n_kejujuran],
                                                                ['id_atribut' => null, 'nama' => 'Kesopanan dan Personalia', 'bobot' => null, 'nilai' => $nilai->n_kesopanan],
                                                                ['id_atribut' => null, 'nama' => 'Kerjasama', 'bobot' => null, 'nilai' => $nilai->n_kerjasama],
                                                            ];
                                                            if (!$isSmk || !empty($siswa->laporan)) {
                                                                $editItems[] = ['id_atribut' => null, 'nama' => 'Nilai Laporan', 'bobot' => null, 'nilai' => $nilai->n_laporan];
                                                            }
                                                        }
                                                        ?>
                                                        <?php foreach ($editItems as $idx => $it) : ?>
                                                            <tr class="kriteria-row">
                                                                <td class="text-center align-middle text-xs font-weight-bold row-index"><?= $idx + 1 ?></td>
                                                                <td class="align-middle">
                                                                    <?php if (!empty($it['id_atribut'])) : ?>
                                                                        <input type="hidden" name="kriteria[<?= $idx ?>][id_atribut]" value="<?= $it['id_atribut'] ?>">
                                                                    <?php endif; ?>
                                                                    <input type="text" class="form-control form-control-sm border px-2 input-kriteria-nama" 
                                                                           name="kriteria[<?= $idx ?>][nama]" value="<?= esc($it['nama']) ?>" 
                                                                           placeholder="Nama kriteria penilaian" required>
                                                                </td>
                                                                <td class="align-middle col-bobot" style="<?= ($nilai->metode_penilaian ?? '') === 'bobot' ? '' : 'display: none;' ?>">
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-bobot" 
                                                                               name="kriteria[<?= $idx ?>][bobot]" value="<?= $it['bobot'] ?? '' ?>" 
                                                                               min="0" max="100" step="0.1" placeholder="0">
                                                                        <span class="input-group-text px-1 text-xs">%</span>
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle text-center">
                                                                    <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-nilai" 
                                                                           name="kriteria[<?= $idx ?>][nilai]" value="<?= $it['nilai'] ?>" 
                                                                           min="0" max="100" step="0.01" placeholder="0 - 100" required>
                                                                </td>
                                                                <td class="align-middle text-center">
                                                                    <button type="button" class="btn btn-link text-danger text-gradient p-1 mb-0 btn-remove-row" title="Hapus baris">
                                                                        <i class="fa fa-trash text-xs"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <button type="button" class="btn btn-xs bg-gradient-dark mb-0" id="btn-add-kriteria-edit">
                                                    <i class="fa fa-plus me-1"></i> Tambah Kriteria Penilaian
                                                </button>
                                                <span class="text-xxs text-muted">* Minimal 1 kriteria penilaian diisi</span>
                                            </div>

                                            <!-- Summary Card -->
                                            <div class="p-3 mb-3 rounded bg-light border d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="text-xs text-muted text-uppercase font-weight-bold">Status Perhitungan:</div>
                                                    <div class="text-xs text-dark">
                                                        Total Kriteria: <span class="font-weight-bold" id="count-kriteria-edit"><?= count($editItems) ?></span>
                                                        <span id="wrap-total-bobot-edit" style="<?= ($nilai->metode_penilaian ?? '') === 'bobot' ? '' : 'display: none;' ?>">
                                                            | Total Bobot: <span class="font-weight-bold" id="total-bobot-edit">0%</span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <div class="text-xs text-muted text-uppercase font-weight-bold">Estimasi Nilai Akhir:</div>
                                                    <div class="text-xl font-weight-bolder text-info" id="preview-nilai-edit"><?= $nilai->total_nilai ?? 0 ?></div>
                                                </div>
                                            </div>

                                            <div class="mt-3 d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm bg-gradient-secondary shadow mb-0 btn-close-modal" data-bs-dismiss="modal">BATAL</button>
                                                <button type="submit" class="btn btn-sm bg-gradient-info shadow mb-0"><i class="fa fa-save me-1"></i> SIMPAN PERUBAHAN</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal edit nilai end -->
            <?php else : ?>
                <!-- modal tambah nilai -->
                <div class="modal fade" id="manilai" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="manilai" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="card card-plain">
                                    <div class="card-header pb-0 text-left">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="m-0 p-0 font-weight-bolder">Tambah Nilai Siswa Magang</h5>
                                                <small class="text-muted">Input skor kriteria penilaian sesuai format yang dikirimkan oleh instansi.</small>
                                            </div>
                                            <span class="badge bg-gradient-info text-xs">
                                                <?= esc($konfigPenilaian->instansi->nama_instansi ?? $siswa->instansi) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php 
                                        $hasFormat = !empty($konfigPenilaian) && (!empty($konfigPenilaian->atribut) || !empty($konfigPenilaian->file_format));
                                        ?>
                                        <?php if ($hasFormat) : ?>
                                            <form class="formaddnilai">
                                                <input type="hidden" name="idt" value="<?= $idt ?>">
                                                <input type="hidden" name="ids" value="<?= $siswa->id ?>">
                                                <input type="hidden" name="id_konfigurasi" value="<?= $konfigPenilaian->id ?>">

                                                <?php if (!empty($konfigPenilaian->file_format)) : ?>
                                                    <div class="p-3 mb-3 rounded bg-light border border-info d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                                                        <div>
                                                            <div class="text-xs text-uppercase font-weight-bold text-info">
                                                                <i class="fa fa-file-pdf me-1"></i> Lembar Format Penilaian Dari Instansi
                                                            </div>
                                                            <div class="text-sm font-weight-bold text-dark">
                                                                <?= esc($konfigPenilaian->nama_file_asli ?: $konfigPenilaian->file_format) ?>
                                                            </div>
                                                            <div class="text-xxs text-muted">
                                                                Buka dokumen ini untuk melihat aspek penilaian yang diminta instansi, lalu isikan nilainya di tabel bawah.
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="<?= base_url('assets/format_penilaian/' . $konfigPenilaian->file_format) ?>" target="_blank" class="btn btn-sm bg-gradient-info mb-0 text-nowrap">
                                                                <i class="fa fa-external-link-alt me-1"></i> BUKA / UNDUH FORMAT
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Metode Perhitungan Nilai -->
                                                <div class="mb-3 p-3 bg-gray-100 rounded">
                                                    <label class="form-label text-xs font-weight-bold text-dark mb-1 d-block">
                                                        Metode Perhitungan Nilai Akhir:
                                                    </label>
                                                    <div class="d-flex flex-wrap gap-4">
                                                        <div class="form-check ps-0 mb-0">
                                                            <input class="form-check-input radio-metode-tambah" type="radio" name="metode_penilaian" id="metode_tambah_rata" value="rata_rata" form-context="tambah" <?= ($konfigPenilaian->metode_penilaian ?? 'rata_rata') !== 'bobot' ? 'checked' : '' ?>>
                                                            <label class="form-check-label text-xs text-dark font-weight-bold cursor-pointer" for="metode_tambah_rata">
                                                                Rata-rata Standar (Unweighted)
                                                            </label>
                                                            <div class="text-xxs text-muted">Semua kriteria memiliki bobot sama rata</div>
                                                        </div>
                                                        <div class="form-check ps-0 mb-0">
                                                            <input class="form-check-input radio-metode-tambah" type="radio" name="metode_penilaian" id="metode_tambah_bobot" value="bobot" form-context="tambah" <?= ($konfigPenilaian->metode_penilaian ?? '') === 'bobot' ? 'checked' : '' ?>>
                                                            <label class="form-check-label text-xs text-dark font-weight-bold cursor-pointer" for="metode_tambah_bobot">
                                                                Sistem Pembobotan (Weighted %)
                                                            </label>
                                                            <div class="text-xxs text-muted">Setiap kriteria memiliki % bobot (Total harus 100%)</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tabel Kriteria Penilaian -->
                                                <div class="table-responsive mb-2">
                                                    <table class="table table-bordered table-sm align-items-center mb-0" id="table-kriteria-tambah">
                                                        <thead class="bg-gray-200">
                                                            <tr>
                                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 40px;">#</th>
                                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Aspek / Kriteria Penilaian</th>
                                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder col-bobot-tambah" style="width: 125px; <?= ($konfigPenilaian->metode_penilaian ?? '') === 'bobot' ? '' : 'display: none;' ?>">Bobot (%)</th>
                                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 130px;">Skor (0-100)</th>
                                                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 45px;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbody-kriteria-tambah">
                                                            <?php if (!empty($konfigPenilaian->atribut)) : ?>
                                                                <?php foreach ($konfigPenilaian->atribut as $idx => $attr) : ?>
                                                                    <tr class="kriteria-row">
                                                                        <td class="text-center align-middle text-xs font-weight-bold row-index"><?= $idx + 1 ?></td>
                                                                        <td class="align-middle">
                                                                            <input type="hidden" name="kriteria[<?= $idx ?>][id_atribut]" value="<?= $attr->id ?>">
                                                                            <input type="text" class="form-control form-control-sm border px-2 input-kriteria-nama" 
                                                                                   name="kriteria[<?= $idx ?>][nama]" value="<?= esc($attr->nama_atribut) ?>" 
                                                                                   placeholder="Nama kriteria penilaian" required>
                                                                        </td>
                                                                        <td class="align-middle col-bobot" style="<?= ($konfigPenilaian->metode_penilaian ?? '') === 'bobot' ? '' : 'display: none;' ?>">
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-bobot" 
                                                                                       name="kriteria[<?= $idx ?>][bobot]" value="<?= $attr->bobot ?? 0 ?>" 
                                                                                       min="0" max="100" step="0.1" placeholder="0">
                                                                                <span class="input-group-text px-1 text-xs">%</span>
                                                                            </div>
                                                                        </td>
                                                                        <td class="align-middle text-center">
                                                                            <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-nilai" 
                                                                                   name="kriteria[<?= $idx ?>][nilai]" 
                                                                                   min="0" max="100" step="0.01" placeholder="0 - 100" required>
                                                                        </td>
                                                                        <td class="align-middle text-center">
                                                                            <button type="button" class="btn btn-link text-danger text-gradient p-1 mb-0 btn-remove-row" title="Hapus baris">
                                                                                <i class="fa fa-trash text-xs"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php else : ?>
                                                                <!-- Default 3 empty rows for mentor to fill from the sheet -->
                                                                <?php 
                                                                $defaults = [
                                                                    'Kedisiplinan & Etika Kerja',
                                                                    'Kemampuan Teknis / Kualitas Praktik',
                                                                    'Kerjasama Tim & Tanggung Jawab'
                                                                ];
                                                                ?>
                                                                <?php foreach ($defaults as $idx => $defNama) : ?>
                                                                    <tr class="kriteria-row">
                                                                        <td class="text-center align-middle text-xs font-weight-bold row-index"><?= $idx + 1 ?></td>
                                                                        <td class="align-middle">
                                                                            <input type="text" class="form-control form-control-sm border px-2 input-kriteria-nama" 
                                                                                   name="kriteria[<?= $idx ?>][nama]" value="<?= $defNama ?>" 
                                                                                   placeholder="Nama kriteria penilaian" required>
                                                                        </td>
                                                                        <td class="align-middle col-bobot" style="<?= ($konfigPenilaian->metode_penilaian ?? '') === 'bobot' ? '' : 'display: none;' ?>">
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-bobot" 
                                                                                       name="kriteria[<?= $idx ?>][bobot]" value="" 
                                                                                       min="0" max="100" step="0.1" placeholder="0">
                                                                                <span class="input-group-text px-1 text-xs">%</span>
                                                                            </div>
                                                                        </td>
                                                                        <td class="align-middle text-center">
                                                                            <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-nilai" 
                                                                                   name="kriteria[<?= $idx ?>][nilai]" 
                                                                                   min="0" max="100" step="0.01" placeholder="0 - 100" required>
                                                                        </td>
                                                                        <td class="align-middle text-center">
                                                                            <button type="button" class="btn btn-link text-danger text-gradient p-1 mb-0 btn-remove-row" title="Hapus baris">
                                                                                <i class="fa fa-trash text-xs"></i>
                                                                            </button>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php endif; ?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <button type="button" class="btn btn-xs bg-gradient-dark mb-0" id="btn-add-kriteria-tambah">
                                                        <i class="fa fa-plus me-1"></i> Tambah Kriteria Penilaian
                                                    </button>
                                                    <span class="text-xxs text-muted">* Minimal 1 kriteria penilaian diisi</span>
                                                </div>

                                                <!-- Summary Card -->
                                                <div class="p-3 mb-3 rounded bg-light border d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <div class="text-xs text-muted text-uppercase font-weight-bold">Status Perhitungan:</div>
                                                        <div class="text-xs text-dark">
                                                            Total Kriteria: <span class="font-weight-bold" id="count-kriteria-tambah">0</span>
                                                            <span id="wrap-total-bobot-tambah" style="<?= ($konfigPenilaian->metode_penilaian ?? '') === 'bobot' ? '' : 'display: none;' ?>">
                                                                | Total Bobot: <span class="font-weight-bold" id="total-bobot-tambah">0%</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="text-xs text-muted text-uppercase font-weight-bold">Estimasi Nilai Akhir:</div>
                                                        <div class="text-xl font-weight-bolder text-info" id="preview-nilai-tambah">0.00</div>
                                                    </div>
                                                </div>

                                                <div class="mt-3 d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-sm bg-gradient-secondary shadow mb-0 btn-close-modal" data-bs-dismiss="modal">BATAL</button>
                                                    <button type="submit" class="btn btn-sm bg-gradient-info shadow mb-0"><i class="fa fa-save me-1"></i> SIMPAN NILAI</button>
                                                </div>
                                            </form>
                                        <?php else : ?>
                                            <div class="alert alert-warning text-white my-3 text-sm" role="alert">
                                                <i class="fa fa-exclamation-triangle me-2"></i>
                                                <strong>Perhatian:</strong> Instansi tempat siswa melaksanakan PKL (<strong><?= esc($siswa->instansi ?: 'Belum ditentukan') ?></strong>) belum mengirimkan format lembar penilaian. Pembimbing lapangan belum dapat melakukan penilaian sebelum instansi mengirimkan format penilaian.
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-sm bg-gradient-secondary mb-0" data-bs-dismiss="modal">TUTUP</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal tambah nilai end -->
            <?php endif ?>
        <?php endif ?>

        <!-- Card Daftar Kegiatan Siswa -->
        <div class="card logbook-card p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="banner-icon-circle" style="background: #eff6ff; border: 1px solid #bfdbfe; width: 40px; height: 40px; color: #1865cf;">
                        <i class="fa fa-calendar-check fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 font-weight-bolder text-dark">Daftar Kegiatan Siswa</h6>
                        <small class="text-xxs text-muted">Riwayat presensi & logbook aktivitas harian</small>
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Filter Tabs -->
                    <div class="d-flex gap-1">
                        <button type="button" class="filter-tab-btn active" data-filter="all">Semua (<?= count($logbooks) ?>)</button>
                        <button type="button" class="filter-tab-btn" data-filter="hadir">Hadir (<?= $countHadir ?>)</button>
                        <?php if ($countPending > 0) : ?>
                            <button type="button" class="filter-tab-btn" data-filter="pending" style="color: #d97706; border-color: #fde68a;">Pending (<?= $countPending ?>)</button>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-secondary mb-0 shadow-none" id="btn-toggle-all-logbook">
                        <i class="fa fa-expand-alt me-1"></i> Buka Semua
                    </button>
                </div>
            </div>

            <div class="logbook-items-wrapper">
                <?php if (count($logbooks) > 0) : ?>
                    <?php 
                    $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    $blnIndo = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
                    ?>
                    <?php foreach ($logbooks as $idx => $log) : ?>
                        <?php
                        $keteranganClass = 'badge-hadir';
                        $keteranganLower = strtolower($log->keterangan ?? '');
                        if ($keteranganLower == 'izin') $keteranganClass = 'badge-izin';
                        elseif ($keteranganLower == 'sakit') $keteranganClass = 'badge-sakit';
                        elseif ($keteranganLower == 'alfa') $keteranganClass = 'badge-alfa';

                        $statusClass = 'status-pending';
                        if ($log->status == 'approved') $statusClass = 'status-approved';
                        elseif ($log->status == 'rejected') $statusClass = 'status-rejected';

                        $timeLog = strtotime($log->tanggal);
                        $tglIndoFormatted = $hariIndo[date('w', $timeLog)] . ', ' . date('j', $timeLog) . ' ' . $blnIndo[(int)date('n', $timeLog)] . ' ' . date('Y', $timeLog);
                        ?>
                        <div class="logbook-item <?= $statusClass ?>" data-id="<?= $log->id ?>" data-status="<?= esc($log->status ?? 'pending') ?>" data-keterangan="<?= esc($keteranganLower) ?>">
                            <!-- HEADER -->
                            <div class="logbook-header" data-toggle-logbook="<?= $log->id ?>">
                                <div class="logbook-header-left">
                                    <div class="logbook-tanggal">
                                        <i class="fa fa-calendar-alt" style="font-size: 13px; color: #1865cf;"></i>
                                        <span><?= $tglIndoFormatted ?></span>
                                        <?php if ($log->telat) : ?>
                                            <span class="badge-logbook badge-telat"><i class="fa fa-clock"></i> Telat Diisi</span>
                                        <?php endif ?>
                                    </div>
                                    <div class="logbook-jam">
                                        <i class="fa fa-clock text-muted" style="font-size: 12px;"></i>
                                        <span><?= substr($log->jam_masuk, 0, 5) ?> - <?= substr($log->jam_keluar, 0, 5) ?></span>
                                    </div>
                                </div>

                                <div class="logbook-header-right">
                                    <div class="logbook-badges">
                                        <span class="badge-logbook <?= $keteranganClass ?>"><?= strtoupper($log->keterangan) ?></span>
                                        <?php if ($log->status == 'approved') : ?>
                                            <span class="badge-logbook badge-approved"><i class="fa fa-check-circle"></i> Terverifikasi</span>
                                        <?php elseif ($log->status == 'rejected') : ?>
                                            <span class="badge-logbook badge-rejected"><i class="fa fa-times-circle"></i> Ditolak</span>
                                        <?php else : ?>
                                            <span class="badge-logbook badge-pending"><i class="fa fa-hourglass-half"></i> Menunggu ACC</span>
                                        <?php endif ?>
                                    </div>
                                    <i class="material-icons expand-icon">expand_more</i>
                                </div>
                            </div>

                            <!-- BODY -->
                            <div class="logbook-body">
                                <!-- DESKRIPSI KEGIATAN -->
                                <div class="kegiatan-table">
                                    <div class="kegiatan-deskripsi">
                                        <div class="kegiatan-label"><i class="fa fa-align-left text-primary me-1"></i> Deskripsi Kegiatan Harian:</div>
                                        <div class="kegiatan-deskripsi-text"><?= nl2br(htmlspecialchars($log->kegiatan ?? '-')) ?></div>
                                    </div>
                                </div>
                                <!-- END DESKRIPSI KEGIATAN -->

                                <div class="logbook-actions">
                                    <!-- Lihat Bukti -->
                                    <?php if (!empty($log->bukti)) : ?>
                                        <a href="<?= base_url('/assets/img/logbook/' . $log->bukti) ?>" target="_blank" class="btn-bukti">
                                            <i class="fa fa-image me-1"></i>
                                            Lihat Foto Bukti Kegiatan
                                        </a>
                                    <?php else : ?>
                                        <span class="text-xs text-muted d-flex align-items-center gap-1">
                                            <i class="fa fa-image text-secondary"></i>
                                            Tidak ada lampiran bukti
                                        </span>
                                    <?php endif ?>

                                    <!-- Tombol ACC & Tolak -->
                                    <div class="btn-action-group">
                                        <button class="btn-reject btn-reject-logbook" data-id="<?= $log->id ?>">
                                            <i class="fa fa-times me-1"></i>
                                            Tolak
                                        </button>
                                        <button class="btn-acc btn-approve-logbook"
                                            data-id="<?= $log->id ?>"
                                            <?= $log->status == 'approved' ? 'disabled' : '' ?>>
                                            <i class="fa fa-check me-1"></i>
                                            <?= $log->status == 'approved' ? 'Terverifikasi' : 'Setujui (ACC)' ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                <?php else : ?>
                    <div class="logbook-empty">
                        <i class="fa fa-calendar-times"></i>
                        <div class="fw-bold mb-1 text-dark fs-6">Belum Ada Catatan Logbook</div>
                        <div class="text-xs text-muted">Siswa ini belum mengirimkan catatan kegiatan magang apapun.</div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal View Siswa -->
<div class="modal fade" id="mvsiswa" tabindex="-1" aria-labelledby="mvsiswa" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Profil Mahasiswa</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php if ($siswa->foto && $siswa->foto != 'default.jpg' && $siswa->foto != '') : ?>
                    <div class="col-12 text-center mb-3">
                        <div class="avatar avatar-xxl position-relative">
                            <img src="<?= base_url('assets/img/foto_siswa/' . $siswa->foto) ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm" style="object-fit: cover; height: 120px;">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-12">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-sm font-weight-bold" width="30%"><?= getIdentityLabel($siswa) ?></td>
                                <td class="text-sm">: <?= $siswa->nim ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Nama</td>
                                <td class="text-sm">: <?= $siswa->nama ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Email</td>
                                <td class="text-sm">: <?= $siswa->email ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">No. HP</td>
                                <td class="text-sm">: <?= $siswa->no_hp ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jumlah</td>
                                <td class="text-sm">: <?= $siswa->kelas ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jurusan</td>
                                <td class="text-sm">: <?= $siswa->jurusan ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Instansi</td>
                                <td class="text-sm">: <?= $breadcrumb[2] ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Angkatan</td>
                                <td class="text-sm">: <?= $siswa->angkatan ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Alamat</td>
                                <td class="text-sm" style="white-space: normal;">: <?= $siswa->alamat ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">NIK</td>
                                <td class="text-sm">: <?= $siswa->nik ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Tempat, Tanggal Lahir</td>
                                <td class="text-sm">: <?= $siswa->tempat_lahir ?? '-' ?>, <?= $siswa->tanggal_lahir ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jenis Kelamin</td>
                                <td class="text-sm">: <?= $siswa->jenis_kelamin ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">No. HP Emergency</td>
                                <td class="text-sm">: <?= $siswa->no_hp_emergency ?? '-' ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr class="my-2"></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File KTP</td>
                                <td class="text-sm">: <?= $siswa->ktp ? '<a href="'.base_url('assets/berkas/'.$siswa->ktp).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File CV</td>
                                <td class="text-sm">: <?= $siswa->cv ? '<a href="'.base_url('assets/berkas/'.$siswa->cv).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Transkrip Nilai / Lainnya</td>
                                <td class="text-sm">: <?= $siswa->bpjs ? '<a href="'.base_url('assets/berkas/'.$siswa->bpjs).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File KTM</td>
                                <td class="text-sm">: <?= $siswa->ktm ? '<a href="'.base_url('assets/berkas/'.$siswa->ktm).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File Takaful</td>
                                <td class="text-sm">: <?= $siswa->takaful ? '<a href="'.base_url('assets/berkas/'.$siswa->takaful).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Permohonan</td>
                                <td class="text-sm">: <?= $siswa->surat_permohonan ? '<a href="'.base_url('assets/berkas/'.$siswa->surat_permohonan).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Pernyataan Magang</td>
                                <td class="text-sm">: <?= $siswa->surat_pernyataan ? '<a href="'.base_url('assets/berkas/'.$siswa->surat_pernyataan).'" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Balasan Magang</td>
                                <td class="text-sm">: <?= $siswa->surat_balasan ? '<a href="'.base_url('assets/berkas/'.$siswa->surat_balasan).'" target="_blank" class="badge bg-gradient-success text-xxs">Lihat File</a>' : '<span class="text-secondary text-xs">Belum diunggah</span>' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Add is-filled to all inputs that have a value
    $('.form-control').each(function() {
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        }
    });

    $('.form-control').on('focus', function() {
        $(this).parent().addClass('is-focused');
    }).on('blur', function() {
        $(this).parent().removeClass('is-focused');
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        } else {
            $(this).parent().removeClass('is-filled');
        }
    }).on('change keyup input', function() {
        if ($(this).val() !== "") {
            $(this).parent().addClass('is-filled');
        } else {
            $(this).parent().removeClass('is-filled');
        }
    });

    // =========================================
    // BUKA/TUTUP LOGBOOK
    // =========================================
    $('.logbook-header').on('click', function() {
        const item = $(this).closest('.logbook-item');
        const isOpen = item.hasClass('open');

        // Tutup semua
        $('.logbook-item').removeClass('open');
        $('.logbook-header .expand-icon').css('transform', 'rotate(0deg)');

        // Buka yang diklik (kalau sebelumnya tertutup)
        if (!isOpen) {
            item.addClass('open');
            $(this).find('.expand-icon').css('transform', 'rotate(180deg)');
        }
    });

    // =========================================
    // FILTER LOGBOOK
    // =========================================
    $('.filter-tab-btn').on('click', function() {
        $('.filter-tab-btn').removeClass('active');
        $(this).addClass('active');
        const filter = $(this).data('filter');

        if (filter === 'all') {
            $('.logbook-item').fadeIn(150);
        } else if (filter === 'hadir') {
            $('.logbook-item').hide();
            $('.logbook-item[data-keterangan="hadir"]').fadeIn(150);
        } else if (filter === 'pending') {
            $('.logbook-item').hide();
            $('.logbook-item[data-status="pending"]').fadeIn(150);
        }
    });

    // =========================================
    // BUKA / TUTUP SEMUA LOGBOOK
    // =========================================
    let allLogbooksExpanded = false;
    $('#btn-toggle-all-logbook').on('click', function() {
        allLogbooksExpanded = !allLogbooksExpanded;
        if (allLogbooksExpanded) {
            $('.logbook-item').addClass('open');
            $('.logbook-header .expand-icon').css('transform', 'rotate(180deg)');
            $(this).html('<i class="fa fa-compress-alt me-1"></i> Tutup Semua');
        } else {
            $('.logbook-item').removeClass('open');
            $('.logbook-header .expand-icon').css('transform', 'rotate(0deg)');
            $(this).html('<i class="fa fa-expand-alt me-1"></i> Buka Semua');
        }
    });

    // =========================================
    // ACC LOGBOOK
    // =========================================
    $('.btn-approve-logbook').on('click', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');

        Swal.fire({
            title: "Approve Log Book",
            text: "Apakah anda yakin ingin menyetujui log book ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#2e7d32',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("/logbook/status/update") ?>',
                    type: 'POST',
                    data: { id: id, status: 'approved' },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Log Book berhasil disetujui",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false,
                            }).then(() => { location.reload(); });
                        } else {
                            Swal.fire("Gagal!", res.message || "Log Book gagal disetujui", "error");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire("Gagal!", "Terjadi kesalahan server", "error");
                    }
                });
            }
        });
    });

    // =========================================
    // TOLAK LOGBOOK
    // =========================================
    $('.btn-reject-logbook').on('click', function(e) {
        e.stopPropagation();
        const id = $(this).data('id');

        Swal.fire({
            title: "Reject Log Book",
            text: "Apakah anda yakin ingin menolak log book ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("/logbook/status/update") ?>',
                    type: 'POST',
                    data: { id: id, status: 'rejected' },
                    dataType: 'JSON',
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Log Book berhasil ditolak",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => { location.reload(); });
                        } else {
                            Swal.fire("Gagal!", res.message || "Log Book gagal ditolak", "error");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire("Gagal!", "Terjadi kesalahan server", "error");
                    }
                });
            }
        });
    });

    // =========================================
    // APPROVE LAPORAN
    // =========================================
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
            confirmButtonText: "Ya, Lanjutkan"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/siswa/approve_laporan",
                    type: "POST",
                    data: { id: id, approved: approved },
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

<script>
    $(document).ready(function() {
        // Helper untuk menomori ulang baris kriteria dan menyesuaikan indeks name attribute
        function renumberRows(tbodyId) {
            let isTambah = tbodyId.indexOf('tambah') !== -1;
            let prefix = isTambah ? 'tambah' : 'edit';
            $(`#${tbodyId} .kriteria-row`).each(function(i) {
                $(this).find('.row-index').text(i + 1);
                $(this).find('.input-kriteria-nama').attr('name', `kriteria[${i}][nama]`);
                $(this).find('.input-kriteria-bobot').attr('name', `kriteria[${i}][bobot]`);
                $(this).find('.input-kriteria-nilai').attr('name', `kriteria[${i}][nilai]`);
                let hiddenId = $(this).find('input[name*="[id_atribut]"]');
                if (hiddenId.length > 0) {
                    hiddenId.attr('name', `kriteria[${i}][id_atribut]`);
                }
            });
            recalculate(prefix);
        }

        // Fungsi kalkulasi live nilai akhir & total bobot
        function recalculate(prefix) {
            let isBobot = $(`input[name="metode_penilaian"][form-context="${prefix}"]:checked`).val() === 'bobot';
            let totalNilai = 0;
            let totalBobot = 0;
            let count = 0;

            $(`#tbody-kriteria-${prefix} .kriteria-row`).each(function() {
                let nama = $(this).find('.input-kriteria-nama').val().trim();
                let valStr = $(this).find('.input-kriteria-nilai').val();
                let bbtStr = $(this).find('.input-kriteria-bobot').val();

                let val = parseFloat(valStr);
                let bbt = parseFloat(bbtStr) || 0;

                if (nama !== '' || !isNaN(val)) {
                    count++;
                    if (!isNaN(val)) {
                        if (isBobot) {
                            totalNilai += (val * (bbt / 100.0));
                            totalBobot += bbt;
                        } else {
                            totalNilai += val;
                        }
                    }
                }
            });

            if (!isBobot) {
                totalNilai = count > 0 ? (totalNilai / count) : 0;
            }

            $(`#count-kriteria-${prefix}`).text(count);
            $(`#total-bobot-${prefix}`).text(totalBobot.toFixed(1) + '%');

            if (isBobot) {
                if (Math.abs(totalBobot - 100.0) <= 0.1) {
                    $(`#total-bobot-${prefix}`).removeClass('text-danger font-weight-bold').addClass('text-success font-weight-bolder');
                } else {
                    $(`#total-bobot-${prefix}`).removeClass('text-success').addClass('text-danger font-weight-bold');
                }
            }

            $(`#preview-nilai-${prefix}`).text(totalNilai.toFixed(2));
        }

        // Template baris kriteria baru
        function getRowHtml(prefix, index) {
            let isBobot = $(`input[name="metode_penilaian"][form-context="${prefix}"]:checked`).val() === 'bobot';
            let bobotStyle = isBobot ? '' : 'style="display:none;"';
            return `
                <tr class="kriteria-row">
                    <td class="text-center align-middle text-xs font-weight-bold row-index">${index + 1}</td>
                    <td class="align-middle">
                        <input type="text" class="form-control form-control-sm border px-2 input-kriteria-nama" 
                               name="kriteria[${index}][nama]" value="" 
                               placeholder="Nama kriteria penilaian" required>
                    </td>
                    <td class="align-middle col-bobot" ${bobotStyle}>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-bobot" 
                                   name="kriteria[${index}][bobot]" value="" 
                                   min="0" max="100" step="0.1" placeholder="0">
                            <span class="input-group-text px-1 text-xs">%</span>
                        </div>
                    </td>
                    <td class="align-middle text-center">
                        <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-nilai" 
                               name="kriteria[${index}][nilai]" 
                               min="0" max="100" step="0.01" placeholder="0 - 100" required>
                    </td>
                    <td class="align-middle text-center">
                        <button type="button" class="btn btn-link text-danger text-gradient p-1 mb-0 btn-remove-row" title="Hapus baris">
                            <i class="fa fa-trash text-xs"></i>
                        </button>
                    </td>
                </tr>
            `;
        }

        // Tambah baris di modal Tambah
        $('#btn-add-kriteria-tambah').on('click', function() {
            let tbody = $('#tbody-kriteria-tambah');
            let count = tbody.find('.kriteria-row').length;
            tbody.append(getRowHtml('tambah', count));
            renumberRows('tbody-kriteria-tambah');
            tbody.find('.kriteria-row:last .input-kriteria-nama').focus();
        });

        // Tambah baris di modal Edit
        $('#btn-add-kriteria-edit').on('click', function() {
            let tbody = $('#tbody-kriteria-edit');
            let count = tbody.find('.kriteria-row').length;
            tbody.append(getRowHtml('edit', count));
            renumberRows('tbody-kriteria-edit');
            tbody.find('.kriteria-row:last .input-kriteria-nama').focus();
        });

        // Hapus baris kriteria
        $(document).on('click', '.btn-remove-row', function() {
            let tbody = $(this).closest('tbody');
            let tbodyId = tbody.attr('id');
            if (tbody.find('.kriteria-row').length <= 1) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pemberitahuan',
                    text: 'Minimal harus ada 1 kriteria penilaian.',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }
            $(this).closest('.kriteria-row').remove();
            renumberRows(tbodyId);
        });

        // Toggle metode Tambah
        $(document).on('change', '.radio-metode-tambah', function() {
            let isBobot = $(this).val() === 'bobot';
            if (isBobot) {
                $('#table-kriteria-tambah .col-bobot-tambah').show();
                $('#tbody-kriteria-tambah .col-bobot').show();
                $('#wrap-total-bobot-tambah').show();
            } else {
                $('#table-kriteria-tambah .col-bobot-tambah').hide();
                $('#tbody-kriteria-tambah .col-bobot').hide();
                $('#wrap-total-bobot-tambah').hide();
            }
            recalculate('tambah');
        });

        // Toggle metode Edit
        $(document).on('change', '.radio-metode-edit', function() {
            let isBobot = $(this).val() === 'bobot';
            if (isBobot) {
                $('#table-kriteria-edit .col-bobot-edit').show();
                $('#tbody-kriteria-edit .col-bobot').show();
                $('#wrap-total-bobot-edit').show();
            } else {
                $('#table-kriteria-edit .col-bobot-edit').hide();
                $('#tbody-kriteria-edit .col-bobot').hide();
                $('#wrap-total-bobot-edit').hide();
            }
            recalculate('edit');
        });

        // Live input trigger
        $(document).on('input', '#tbody-kriteria-tambah input', function() { recalculate('tambah'); });
        $(document).on('input', '#tbody-kriteria-edit input', function() { recalculate('edit'); });

        // Inisialisasi awal kalkulasi saat modal dibuka
        $('#manilai').on('shown.bs.modal', function() {
            renumberRows('tbody-kriteria-tambah');
            recalculate('tambah');
        });
        $('#menilai').on('shown.bs.modal', function() {
            renumberRows('tbody-kriteria-edit');
            recalculate('edit');
        });

        // Form Submit Tambah Nilai
        $('.formaddnilai').submit(function(e) {
            e.preventDefault();

            let isBobot = $('input[name="metode_penilaian"][form-context="tambah"]:checked').val() === 'bobot';
            if (isBobot) {
                let totalBobot = 0;
                $('#tbody-kriteria-tambah .kriteria-row').each(function() {
                    let bbt = parseFloat($(this).find('.input-kriteria-bobot').val()) || 0;
                    totalBobot += bbt;
                });
                if (Math.abs(totalBobot - 100.0) > 0.1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Total Bobot Tidak 100%',
                        text: 'Total persentase bobot harus tepat 100%. Saat ini total bobot adalah ' + totalBobot.toFixed(1) + '%.'
                    });
                    return;
                }
            }

            var data = new FormData(this);
            $.ajax({
                cache: false, processData: false, contentType: false,
                type: "POST", url: '/nilai/store', data: data, dataType: "JSON",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 1500 }).then(function() { window.location.reload(); });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseText || 'Terjadi kesalahan sistem' });
                }
            });
        });

        $(".btn-edit-nilai").click(function() { $("#menilai").modal('show'); });

        // Hapus nilai
        $('.btn-hapus-nilai').on('click', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Nilai akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE", url: "/nilai/destroy/" + id, dataType: "JSON",
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 1500 }).then(function() { window.location.reload(); });
                            } else {
                                Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                            }
                        }
                    });
                }
            });
        });

        // Form Submit Edit Nilai
        $('.formeditnilai').submit(function(e) {
            e.preventDefault();

            let isBobot = $('input[name="metode_penilaian"][form-context="edit"]:checked').val() === 'bobot';
            if (isBobot) {
                let totalBobot = 0;
                $('#tbody-kriteria-edit .kriteria-row').each(function() {
                    let bbt = parseFloat($(this).find('.input-kriteria-bobot').val()) || 0;
                    totalBobot += bbt;
                });
                if (Math.abs(totalBobot - 100.0) > 0.1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Total Bobot Tidak 100%',
                        text: 'Total persentase bobot harus tepat 100%. Saat ini total bobot adalah ' + totalBobot.toFixed(1) + '%.'
                    });
                    return;
                }
            }

            var data = new FormData(this);
            $.ajax({
                cache: false, processData: false, contentType: false,
                type: "POST", url: '/nilai/update', data: data, dataType: "JSON",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message, showConfirmButton: false, timer: 1500 }).then(function() { window.location.reload(); });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: response.message });
                    }
                },
                error: function(xhr) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseText || 'Terjadi kesalahan sistem' });
                }
            });
        });

        // Inisialisasi awal
        recalculate('tambah');
        recalculate('edit');
    });
</script>
<?= $this->endSection(); ?>