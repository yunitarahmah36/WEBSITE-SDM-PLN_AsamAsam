<?= $this->extend('main'); ?>
<?= $this->section('topsc'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* =====================================================
           SELECT2 DEFAULT STYLE
           ===================================================== */
        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }

        .select2-container {
            width: 100% !important;
            z-index: 9999 !important;
        }
        
        .select2-container--open {
            z-index: 99999 !important;
        }

        /* =====================================================
           MODAL EDIT PENDAFTARAN - DARK NAVY
           ===================================================== */
        #modalEditApplication .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        #modalEditApplication .modal-header {
            background: #2b3654; /* Warna Biru Gelap / Navy */
            padding: 16px 22px;
            border: none;
        }

        #modalEditApplication .modal-header .modal-title {
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        /* PERBAIKAN TOMBOL X */
        #modalEditApplication .modal-header .btn-close {
            filter: none; /* Hapus filter */
            color: #ffffff; /* Warna putih */
            opacity: 0.8;
            box-shadow: none;
            background: none; /* Hilangkan background image bawaan bootstrap */
        }

        #modalEditApplication .modal-header .btn-close:hover {
            opacity: 1;
        }

        #modalEditApplication .modal-body {
            padding: 22px;
        }

        #modalEditApplication .modal-footer {
            padding: 14px 22px 18px;
            border-top: 1px solid #e5e7eb;
        }

        #modalEditApplication .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
            display: block;
        }

        #modalEditApplication .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            background: #ffffff !important;
        }

        #modalEditApplication .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px !important;
            padding-left: 12px !important;
            color: #1e293b !important;
            font-size: 14px;
        }

        #modalEditApplication .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }

        #modalEditApplication .select2-container--default.select2-container--focus .select2-selection--single,
        #modalEditApplication .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #2b3654 !important;
            box-shadow: 0 0 0 3px rgba(43, 54, 84, 0.1) !important;
        }

        .select2-results__option .badge-kuota {
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
            margin-left: 8px;
            color: #ffffff;
            white-space: nowrap;
        }

        /* =====================================================
           RESPONSIVE - TABEL JADI CARD DI HP
           ===================================================== */
        @media (max-width: 767px) {
            .page-header-responsive {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 12px;
            }

            .page-header-responsive .btn {
                width: 100%;
            }

            #tbapplications thead {
                display: none;
            }

            #tbapplications,
            #tbapplications tbody,
            #tbapplications tr,
            #tbapplications td {
                display: block;
                width: 100%;
            }

            #tbapplications tr {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                margin-bottom: 14px;
                padding: 12px 14px;
                box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
            }

            #tbapplications td {
                border: none !important;
                padding: 6px 0 !important;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
                font-size: 13px;
                text-align: right;
                word-break: break-word;
            }

            #tbapplications td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #64748b;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                flex-shrink: 0;
                text-align: left;
                padding-right: 8px;
                min-width: 90px;
            }

            #tbapplications td[data-label="No"] {
                display: none;
            }

            #tbapplications td[data-label="Aksi"] {
                display: block;
                text-align: left;
                padding-top: 10px !important;
                border-top: 1px dashed #e2e8f0 !important;
                margin-top: 6px;
            }

            #tbapplications td[data-label="Aksi"]::before {
                display: block;
                margin-bottom: 8px;
            }

            #tbapplications td[data-label="Nama"] {
                font-size: 15px;
                font-weight: 700;
                color: #1e293b;
                padding-bottom: 10px !important;
                border-bottom: 1px solid #f1f5f9 !important;
                margin-bottom: 6px;
                display: block;
                text-align: left;
            }

            #tbapplications td[data-label="Nama"]::before {
                display: none;
            }

            #tbapplications td[data-label="Aksi"] .badge {
                margin: 2px;
                padding: 6px 10px;
                font-size: 11px;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                text-align: center !important;
                width: 100%;
                margin-top: 10px;
            }

            .dataTables_wrapper .dataTables_paginate .pagination {
                justify-content: center;
            }

            #modalEditApplication .modal-dialog {
                margin: 8px;
                max-width: calc(100% - 16px) !important;
            }

            #modalEditApplication .modal-body {
                padding: 16px;
            }

            #modalEditApplication .modal-footer {
                padding: 12px 16px 16px;
            }

            #modalEditApplication .modal-footer .btn {
                flex: 1;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            #tbapplications {
                font-size: 12px;
            }

            #tbapplications th,
            #tbapplications td {
                padding-left: 6px !important;
                padding-right: 6px !important;
            }
        }

        /* =====================================================
           TEMA SURAT MAGANG PESERTA (SOFT GREEN SAGE)
           ===================================================== */
        .card-surat-magang {
            border: 1px solid #d2edd7 !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(15, 81, 50, 0.05) !important;
        }
        .card-pernyataan-inner {
            background: linear-gradient(145deg, #f0fdf4, #eaf8ee) !important;
            border: 1.5px solid #bbf7d0 !important;
            border-radius: 14px !important;
            box-shadow: 0 2px 10px rgba(15, 81, 50, 0.04) !important;
        }
        .btn-unduh-template {
            background: linear-gradient(135deg, #15803d, #16a34a) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            letter-spacing: 0.5px;
            border: none !important;
            border-radius: 8px !important;
            padding: 8px 18px !important;
            box-shadow: 0 4px 10px rgba(21, 128, 61, 0.22) !important;
            transition: all 0.2s ease;
        }
        .btn-unduh-template:hover {
            background: linear-gradient(135deg, #166534, #15803d) !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(21, 128, 61, 0.3) !important;
        }
        .btn-unggah-pernyataan {
            background-color: #ecfdf5 !important;
            color: #15803d !important;
            border: 1.5px solid #86efac !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            letter-spacing: 0.5px;
            border-radius: 8px !important;
            padding: 7px 16px !important;
            transition: all 0.2s ease;
        }
        .btn-unggah-pernyataan:hover {
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border-color: #4ade80 !important;
        }
        .input-file-pernyataan {
            border: 1px solid #a7f3d0 !important;
            border-radius: 8px !important;
            background-color: #ffffff !important;
            font-size: 12px !important;
            color: #064e28 !important;
        }
        .badge-sudah-diunggah {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #d1fae5 !important;
            color: #065f46 !important;
            border: 1px solid #86efac !important;
            border-radius: 8px !important;
            padding: 6px 14px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            transition: all 0.2s ease;
        }
        .badge-sudah-diunggah:hover {
            background-color: #a7f3d0 !important;
            color: #044e31 !important;
        }
    </style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-body py-4 px-4 <?= in_groups('siswa') ? 'card-surat-magang' : '' ?>">
            <?php if (in_groups('admin') || in_groups('pembimbing')) : ?>
                <div class="d-flex justify-content-between align-items-center px-2 page-header-responsive">
                    <div>
                        <span class="h5 mb-0 pb-0">Surat Magang & Daftar Pendaftaran Siswa</span> <br>
                        <small class="text-secondary">Daftar berikut merupakan Lamaran siswa yang telah mendaftar di tempat magang.</small>
                    </div>
                    <div>
                        <?php if (in_groups('admin')) : ?>
                            <button class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalTemplateSurat">
                                <i class="fas fa-upload me-1"></i> Upload Template Surat
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-md-4">
                        <div class="input-group input-group-outline mb-3">
                            <label for="filsearch" class="form-label">Cari Data</label>
                            <input type="text" name="filsearch" id="filsearch" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-stripped" id="tbapplications" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIM / NIS</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Orang</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rencana Magang</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Instansi</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dosen Pembimbing</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Surat Pernyataan</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Laporan</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1 ?>
                            <?php foreach ($applications as $application) : ?>
                                <?php $instansi = $application->id_tempat == null ? $application->custom_tempat : getNamaInstansi($application->id_tempat) ?>
                                <tr>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="No"><?= $no++ ?></td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Status">
                                        <?php if ($application->laporan == null && (date('Y-m-d') > $application->tgl_selesai)) : ?>
                                            <span class="badge badge-warning border border-warning">unfinish</span>
                                        <?php else : ?>
                                            <?= genBadgeStatusApplication($application->status) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="NIM / NIS">
                                        <span class="badge badge-secondary"><?= $application->nim ?></span>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Jumlah Orang"><?= $application->kelas ?></td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Rencana Magang">
                                        <?= ($application->tgl_masuk && $application->tgl_selesai) 
                                            ? date('d/m/Y', strtotime($application->tgl_masuk)) . ' - ' . date('d/m/Y', strtotime($application->tgl_selesai)) 
                                            : '-' ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Nama"><?= $application->nama ?></td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Instansi">
                                        <?= isVerifiedInstansi($application->id_tempat) ?><span class="iname"><?= $instansi ?></span>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Dosen Pembimbing">
                                        <?= !empty($application->dosen_nama) ? esc($application->dosen_nama) : '<span class="text-secondary opacity-6">Belum ada</span>' ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Surat Pernyataan">
                                        <?php if (isset($application->surat_pernyataan) && $application->surat_pernyataan) : ?>
                                            <a href="/assets/berkas/<?= $application->surat_pernyataan ?>" target="_blank" class="badge badge-success border border-success">UNDUH</a>
                                        <?php else : ?>
                                            <span class="badge badge-light text-secondary border">BELUM ADA</span>
                                        <?php endif ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Laporan">
                                        <?php if ($application->laporan) : ?>
                                            <a href="/assets/laporan/<?= $application->laporan ?>" target="_blank" class="badge badge-info border border-info">LIHAT</a>
                                        <?php else : ?>
                                            <a href="#" class="badge badge-dark border border-dark" style="cursor: not-allowed !important; pointer-events: all !important;">BELUM</a>
                                        <?php endif ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold" data-label="Aksi">
                                        <button class="badge border border-1 border-info text-info btn-view-profile" 
                                            title="Lihat Profil" 
                                            data-nama="<?= htmlspecialchars($application->nama) ?>" 
                                            data-nim="<?= $application->nim ?>" 
                                            data-kelas="<?= $application->kelas ?>" 
                                            data-jurusan="<?= $application->jurusan ?? '-' ?>" 
                                            data-instansi="<?= htmlspecialchars($application->instansi ?? '-') ?>" 
                                            data-tgl_masuk="<?= $application->tgl_masuk ?>" 
                                            data-tgl_selesai="<?= $application->tgl_selesai ?>" 
                                            data-nik="<?= $application->nik ?>" 
                                            data-tempat_lahir="<?= htmlspecialchars($application->tempat_lahir ?? '-') ?>" 
                                            data-tanggal_lahir="<?= $application->tanggal_lahir ?>" 
                                            data-jenis_kelamin="<?= $application->jenis_kelamin ?>" 
                                            data-nohp_emergency="<?= $application->no_hp_emergency ?>" 
                                            data-surat_permohonan="<?= $application->surat_permohonan ?>" 
                                            data-cv="<?= $application->cv ?>" 
                                            data-ktp="<?= $application->ktp ?>" 
                                            data-bpjs="<?= $application->bpjs ?>" 
                                            data-ktm="<?= $application->ktm ?>" 
                                            data-takaful="<?= $application->takaful ?? '' ?>" 
                                            data-surat_balasan="<?= $application->surat_balasan ?? '' ?>" 
                                            data-nohp="<?= $application->no_hp ?>" 
                                            data-alamat="<?= htmlspecialchars($application->alamat) ?>" 
                                            data-foto="<?= $application->foto ? '/assets/img/foto_siswa/'.$application->foto : '/assets/img/default.jpg' ?>" 
                                            data-dosen_nama="<?= htmlspecialchars($application->dosen_nama ?? '') ?>"
                                            data-issmk="<?= isSmkSiswa($application) ? 'true' : 'false' ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <?php if (in_groups('admin')) : ?>
                                            <button <?= $application->status != 'pending' ? 'disabled style="opacity: 0.6;"' : '' ?> 
                                                    class="badge border border-1 border-danger text-danger btn-destroy" 
                                                    title="Hapus data" 
                                                    data-item="<?= $application->id; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                            <button <?= $application->status == 'reject by system' ? 'disabled style="opacity: 0.6;"' : '' ?> 
                                                    class="badge border border-1 border-warning text-warning btn-edit-status" 
                                                    title="Update Status / Pindah Divisi" 
                                                    data-stts="<?= $application->status ?>" 
                                                    data-tempat="<?= $application->id_tempat ?>"
                                                    data-item="<?= $application->id; ?>">
                                                <i class="fas fa-tag"></i>
                                            </button>
                                        <?php else : ?>
                                            <span class="badge border border-1 border-secondary text-secondary" 
                                                  title="Hanya Admin yang dapat mengubah status pendaftaran">
                                                <i class="fas fa-lock"></i> Read Only
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Profile Siswa -->
                <div class="modal fade" id="modalProfileSiswa" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Profil Siswa</h5>
                                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center pt-4">
                                <img src="" id="prof_foto" class="img-fluid rounded-circle mb-3 shadow" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #e0e0e0;">
                                <h5 class="mb-1" id="prof_nama">Nama Siswa</h5>
                                <p class="text-sm text-secondary mb-3"><span id="prof_nim" class="badge badge-secondary">NIM</span></p>
                                
                                <div class="row text-start mt-4 px-2" style="max-height: 50vh; overflow-y: auto;">
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Jumlah Orang</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_kelas">-</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Jurusan</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_jurusan">-</div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Instansi Asal</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_instansi">-</div>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Dosen/Guru Pembimbing</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_dosen_nama">-</div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Tanggal Masuk</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_tgl_masuk">-</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Tanggal Selesai</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_tgl_selesai">-</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Tempat, Tgl Lahir</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_ttl">-</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Jenis Kelamin</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_jk">-</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">NIK</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_nik">-</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">No. HP Emergency</div>
                                        <div class="text-sm text-dark font-weight-bold"><a href="#" target="_blank" id="prof_nohp_emergency" class="text-danger"><i class="fa fa-phone me-1"></i> <span>-</span></a></div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">No. HP / WhatsApp</div>
                                        <div class="text-sm text-dark font-weight-bold"><a href="#" target="_blank" id="prof_nohp" class="text-success"><i class="fab fa-whatsapp me-1"></i> <span>-</span></a></div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="text-xs text-secondary font-weight-bold">Alamat</div>
                                        <div class="text-sm text-dark font-weight-bold" id="prof_alamat">-</div>
                                    </div>
                                    
                                    <div class="col-12 mt-2 mb-2">
                                        <div class="text-xs text-secondary font-weight-bold mb-2">Dokumen Pendukung</div>
                                        <div class="d-flex flex-wrap gap-2" id="prof_dokumen">
                                            <!-- Dokumen akan diisi via JS -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Pendaftaran (HANYA ADMIN) -->
                <?php if (in_groups('admin')) : ?>
                    <div class="modal fade" id="modalEditApplication" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
                            <div class="modal-content">
                                
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit me-2"></i> Update Pendaftaran
                                    </h5>
                                    <!-- TOMBOL X DIGANTI DENGAN IKON FONT AWESOME -->
                                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close" style="background: none; opacity: 1;">
                                        <i class="fas fa-times" style="font-size: 20px;"></i>
                                    </button>
                                </div>

                                <form id="formEditApplication" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="item" id="edit_app_id">
                                    
                                    <div class="modal-body">
                                        <p class="text-sm text-secondary mb-3" style="font-size: 13px;">
                                            <i class="fas fa-user me-1"></i> 
                                            Siswa: <strong id="edit_app_subtitle">-</strong>
                                        </p>

                                        <div class="mb-3">
                                            <label for="edit_id_tempat" class="form-label">
                                                Divisi Magang
                                            </label>
                                            <select name="id_tempat" id="edit_id_tempat" style="width: 100%;">
                                                <option value="">-- Pilih Divisi --</option>
                                                <?php if (isset($tempats) && !empty($tempats)) : ?>
                                                    <?php foreach ($tempats as $t) : ?>
                                                        <option 
                                                            value="<?= $t->id ?>"
                                                            data-nama="<?= esc($t->nama) ?>"
                                                            data-kuota="<?= getSlotAvailable($t->id) ?>"
                                                            data-status="<?= $t->status ?>">
                                                            <?= esc($t->nama) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_status" class="form-label">
                                                Keputusan Seleksi
                                            </label>
                                            <select name="status" id="edit_status" class="form-control" style="height: 42px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 14px; padding: 0 12px;">
                                                <option value="pending">Menunggu</option>
                                                <option value="accepted">Terima (Lolos Seleksi)</option>
                                                <option value="rejected">Tolak</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit_surat_balasan" class="form-label">
                                                Surat Balasan Magang (PDF)
                                            </label>
                                            <input type="file" name="surat_balasan" id="edit_surat_balasan" class="form-control" accept=".pdf" style="height: auto; padding: 8px; border-radius: 8px; border: 1px solid #cbd5e1;">
                                            <small class="text-xs text-secondary mt-1 d-block">*Unggah surat balasan jika status diubah menjadi "Terima".</small>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-light border" data-bs-dismiss="modal">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-sm" style="background: #2b3654; color: white; font-weight: 600;">
                                            <i class="fas fa-save me-1"></i> Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Modal Upload Template (HANYA ADMIN) -->
                <?php if (in_groups('admin')) : ?>
                    <div class="modal fade" id="modalTemplateSurat" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Upload Template Surat Magang</h5>
                                </div>
                                <form action="/application/upload_template" method="post" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <div class="modal-body">
                                        <div class="alert alert-info text-white text-sm">
                                            Unggah template surat (Word/PDF) yang akan diunduh oleh siswa. File sebelumnya akan ditimpa.
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <input type="file" name="template_surat" class="form-control p-2 border" accept=".doc,.docx,.pdf" required>
                                        </div>
                                        <?php if (file_exists(FCPATH . 'assets/berkas/template_surat_magang.pdf') || file_exists(FCPATH . 'assets/berkas/template_surat_magang.docx') || file_exists(FCPATH . 'assets/berkas/template_surat_magang.doc')) : ?>
                                            <?php 
                                                $fileUrl = file_exists(FCPATH . 'assets/berkas/template_surat_magang.docx') ? '/assets/berkas/template_surat_magang.docx' : (file_exists(FCPATH . 'assets/berkas/template_surat_magang.pdf') ? '/assets/berkas/template_surat_magang.pdf' : '/assets/berkas/template_surat_magang.doc');
                                            ?>
                                            <div class="mt-2 text-sm">
                                                <strong>File saat ini:</strong> <a href="<?= $fileUrl ?>" target="_blank" class="text-info text-decoration-underline">Unduh Template Aktif</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-sm btn-dark">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="d-flex justify-content-between align-items-center px-1 mb-4">
                    <div>
                        <h4 class="m-0 p-0 font-weight-bold" style="color: #0f4423;">
                            <span class="material-icons align-middle me-1" style="color: #15803d; font-size: 1.6rem;">description</span>
                            Surat Magang
                        </h4>
                        <p class="text-sm text-secondary mt-1 mb-0">Silakan unduh dan unggah surat magang Anda di sini.</p>
                    </div>
                </div>

                <div>
                    <div class="card card-body card-pernyataan-inner p-4 mb-3">
                        <h6 class="mb-2 font-weight-bold" style="color: #064e28;">
                            <i class="fas fa-file-signature me-1" style="color: #15803d;"></i> Surat Pernyataan Magang
                        </h6>
                        <p class="text-sm mb-3" style="color: #2d5a3e;">Silakan unduh template surat magang berikut, lengkapi, lalu unggah kembali dalam format PDF/Word.</p>
                        
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <?php 
                                $fileUrl = false;
                                if (file_exists(FCPATH . 'assets/berkas/template_surat_magang.docx')) $fileUrl = '/assets/berkas/template_surat_magang.docx';
                                elseif (file_exists(FCPATH . 'assets/berkas/template_surat_magang.pdf')) $fileUrl = '/assets/berkas/template_surat_magang.pdf';
                                elseif (file_exists(FCPATH . 'assets/berkas/template_surat_magang.doc')) $fileUrl = '/assets/berkas/template_surat_magang.doc';
                            ?>
                            <?php if ($fileUrl) : ?>
                                <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-unduh-template mb-0">
                                    <i class="fas fa-download me-1"></i> Unduh Template
                                </a>
                            <?php else : ?>
                                <span class="badge badge-secondary mb-0">Template belum tersedia</span>
                            <?php endif; ?>
                            
                            <form action="/siswa/upload_pernyataan" method="post" enctype="multipart/form-data" class="d-flex align-items-center mb-0 m-0 flex-wrap gap-2">
                                <?= csrf_field() ?>
                                <input type="file" name="surat_pernyataan" class="form-control form-control-sm input-file-pernyataan px-2 py-1" style="max-width: 220px;" accept=".pdf,.doc,.docx" required>
                                <button type="submit" class="btn btn-sm btn-unggah-pernyataan mb-0">
                                    <i class="fas fa-upload me-1"></i> Unggah
                                </button>
                            </form>
                            
                            <?php if (isset($siswa_data) && isset($siswa_data->surat_pernyataan) && $siswa_data->surat_pernyataan) : ?>
                                <a href="/assets/berkas/<?= $siswa_data->surat_pernyataan ?>" target="_blank" class="badge-sudah-diunggah ms-lg-2">
                                    <i class="fas fa-check-circle" style="color: #10b981;"></i> Sudah Diunggah
                                    <i class="fas fa-external-link-alt ms-1 opacity-7" style="font-size: 10px;"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
    <script src="/assets/js/plugins/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <?php if (in_groups('admin') || in_groups('pembimbing')) : ?>
        <script src="/assets/js/plugins/jquery.dataTables.min.js"></script>
        <script src="/assets/js/plugins/dataTables.bootstrap5.min.js"></script>
        <script>
            $(document).ready(function() {
                var tbApplication = $("#tbapplications").DataTable({
                    dom: '<"row"<"col-12"tr>><"row mt-2 px-3"<"col-12 col-md-6"i><"col-12 col-md-6"p>>',
                    pageLength: 10,
                    language: {
                        paginate: {
                            next: '<i class="fas fa-angle-right"></i>',
                            previous: '<i class="fas fa-angle-left"></i>'
                        }
                    },
                    responsive: false,
                    autoWidth: false
                });

                // =====================================================
                // INIT SELECT2 UNTUK DROPDOWN DIVISI
                // =====================================================
                $('#edit_id_tempat').select2({
                    dropdownParent: $('#modalEditApplication'),
                    placeholder: '-- Cari Divisi Magang --',
                    allowClear: true,
                    width: '100%',
                    templateResult: function(state) {
                        if (!state.id) return state.text;

                        var $option = $(state.element);
                        var nama   = $option.data('nama') || state.text;
                        var kuota  = $option.data('kuota');
                        var status = $option.data('status');

                        if (kuota === undefined || kuota === null) return state.text;

                        var badgeColor = '';
                        var badgeText  = '';

                        if (status === 'tutup') {
                            badgeColor = '#ef4444';
                            badgeText  = 'TUTUP';
                        } else if (parseInt(kuota) <= 0) {
                            badgeColor = '#f59e0b';
                            badgeText  = 'PENUH';
                        } else {
                            badgeColor = '#10b981';
                            badgeText  = 'Sisa: ' + kuota;
                        }

                        return $(
                            '<div style="display: flex; justify-content: space-between; align-items: center; gap: 8px;">' +
                                '<span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">' + nama + '</span>' +
                                '<span class="badge-kuota" style="background: ' + badgeColor + ';">' + badgeText + '</span>' +
                            '</div>'
                        );
                    },
                    templateSelection: function(state) {
                        if (!state.id) return state.text;
                        return state.text;
                    }
                });

                // fil search
                $('#filsearch').on('keyup change input', function() {
                    $(this).val() == "" ? $(this).parent().removeClass('is-filled') : $(this).parent().addClass('is-filled');
                    tbApplication.search(this.value).draw();
                });

                // =====================================================
                // Hapus data
                // =====================================================
                $("#tbapplications tbody").on('click', '.btn-destroy', function(s) {
                    const t = $(this).data('item');
                    const nama = $(this).parent().parent().find('td[data-label="Nama"]').text() || $(this).parent().parent().find('td:eq(5)').text();
                    const instansi = $(this).parent().parent().find('td[data-label="Instansi"] .iname').text() || $(this).parent().parent().find('td:eq(6) .iname').text();

                    Swal.fire({
                        icon: "warning",
                        title: "Hapus Data",
                        html: `Apakah anda yakin ingin menghapus data pendaftaran siswa <b>${nama}</b> di <b><i>${instansi}</i></b> ?`,
                        showCancelButton: !0,
                        confirmButtonText: "Ya, Hapus"
                    }).then(e => {
                        e.isConfirmed && $.ajax({
                            url: "/application/destroy/" + t,
                            type: "delete",
                            dataType: "json",
                            success: function(t) {
                                t.success ? Swal.fire({
                                    icon: "success",
                                    title: "Berhasil",
                                    text: t.message,
                                    showConfirmButton: !1,
                                    timer: 1500
                                }).then(t => {
                                    location.reload()
                                }) : Swal.fire({
                                    icon: "error",
                                    title: "Gagal",
                                    text: t.message,
                                    showConfirmButton: !0
                                }).then(t => {
                                    t.isConfirmed && location.reload()
                                })
                            }
                        })
                    })
                })

                // =====================================================
                // HANDLER btn-edit-status
                // =====================================================
                $("#tbapplications tbody").on('click', '.btn-edit-status', function(s) {
                    const id = $(this).data('item');
                    const status = $(this).data('stts');
                    const tempat = $(this).data('tempat');
                    const nama = $(this).parent().parent().find('td[data-label="Nama"]').text() || $(this).parent().parent().find('td:eq(5)').text();
                    
                    $('#edit_app_id').val(id);
                    $('#edit_status').val(status);
                    $('#edit_id_tempat').val(tempat).trigger('change');
                    $('#edit_app_subtitle').text(nama);
                    $('#modalEditApplication').modal('show');
                });

                // =====================================================
                // SUBMIT form update pendaftaran (PERBAIKAN AJAX)
                // =====================================================
                $('#formEditApplication').on('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const btn = $('#formEditApplication button[type=submit]');

                    $.ajax({
                        url: "/application/status/update",
                        type: "post",
                        dataType: "json",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function() {
                            btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
                        },
                        success: function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Berhasil",
                                    text: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal",
                                    text: res.message,
                                    showConfirmButton: true
                                }).then(() => {
                                    btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                                });
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "Terjadi kesalahan pada server", "error");
                            btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan');
                        }
                    });
                });

                // =====================================================
                // HANDLER btn-view-profile
                // =====================================================
                $("#tbapplications tbody").on('click', '.btn-view-profile', function() {
                    const d = $(this).data();
                    const label = (d.issmk === true || d.issmk === 'true') ? 'NIS' : 'NIM';

                    $('#prof_nama').text(d.nama || '-');
                    $('#prof_nim').text(label + ': ' + (d.nim || '-'));
                    $('#prof_kelas').text(d.kelas || '-');
                    $('#prof_jurusan').text(d.jurusan || '-');
                    $('#prof_instansi').text(d.instansi || '-');

                    $('#prof_dosen_nama').text(d.dosen_nama || 'Belum ada');
                    
                    const formatDate = (dateStr) => {
                        if (!dateStr || dateStr === '0000-00-00') return '-';
                        const options = { day: 'numeric', month: 'long', year: 'numeric' };
                        return new Date(dateStr).toLocaleDateString('id-ID', options);
                    };
                    $('#prof_tgl_masuk').text(formatDate(d.tgl_masuk));
                    $('#prof_tgl_selesai').text(formatDate(d.tgl_selesai));
                    
                    $('#prof_nik').text(d.nik || '-');
                    $('#prof_jk').text(d.jenis_kelamin || '-');
                    
                    const t_lahir = d.tempat_lahir || '-';
                    const tgl_lahir = formatDate(d.tanggal_lahir);
                    $('#prof_ttl').text((t_lahir === '-' && tgl_lahir === '-') ? '-' : `${t_lahir}, ${tgl_lahir}`);
                    
                    if (d.nohp && d.nohp !== '-') {
                        const cleanHp = d.nohp.toString().replace(/[^0-9]/g, '');
                        $('#prof_nohp').attr('href', 'https://wa.me/' + cleanHp).find('span').text(d.nohp);
                    } else {
                        $('#prof_nohp').attr('href', '#').find('span').text('Belum diisi');
                    }
                    
                    if (d.nohp_emergency && d.nohp_emergency !== '-') {
                        const cleanHp = d.nohp_emergency.toString().replace(/[^0-9]/g, '');
                        $('#prof_nohp_emergency').attr('href', 'tel:' + cleanHp).find('span').text(d.nohp_emergency);
                    } else {
                        $('#prof_nohp_emergency').attr('href', '#').find('span').text('-');
                    }
                    
                    $('#prof_alamat').text(d.alamat || 'Belum diisi');
                    $('#prof_foto').attr('src', d.foto);
                    
                    let docsHtml = '';
                    const createDocBtn = (title, file, color, icon) => {
                        if (file) {
                            return `<a href="/assets/berkas/${file}" target="_blank" class="badge bg-${color} mb-1" style="font-size:10px;"><i class="${icon} me-1"></i> ${title}</a> `;
                        }
                        return '';
                    };
                    
                    docsHtml += createDocBtn('Surat Permohonan', d.surat_permohonan, 'primary', 'fas fa-file-pdf');
                    docsHtml += createDocBtn('Surat Pernyataan', d.surat_pernyataan, 'success', 'fas fa-file-signature');
                    docsHtml += createDocBtn('CV', d.cv, 'info', 'fas fa-id-card');
                    docsHtml += createDocBtn('KTP/KK', d.ktp, 'secondary', 'fas fa-address-card');
                    docsHtml += createDocBtn('BPJS', d.bpjs, 'warning', 'fas fa-heartbeat');
                    docsHtml += createDocBtn('KTM', d.ktm, 'dark', 'fas fa-id-badge');
                    docsHtml += createDocBtn('Takaful', d.takaful, 'info', 'fas fa-shield-alt');
                    docsHtml += createDocBtn('Surat Balasan', d.surat_balasan, 'success', 'fas fa-file-invoice');
                    
                    if (docsHtml === '') {
                        docsHtml = '<span class="text-sm text-secondary">Belum ada dokumen yang diunggah.</span>';
                    }
                    $('#prof_dokumen').html(docsHtml);

                    $('#modalProfileSiswa').modal('show');
                });
            });
        </script>
    <?php endif ?>
<?= $this->endSection(); ?>