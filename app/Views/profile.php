<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<style>
    li.errors:not(:last-child) { margin-bottom: 7px; }
    li.errors { text-transform: capitalize; font-size: 14px; }

    /* =========================================
       STEP WIZARD UNTUK MODAL EDIT
       ========================================= */
    .edit-step-content { display: none; }
    .edit-step-content.active { display: block; animation: fadeStepEdit 0.25s ease; }

    @keyframes fadeStepEdit {
        from { opacity: 0; transform: translateX(8px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .edit-step-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        gap: 0;
    }

    .edit-step-item { display: flex; align-items: center; position: relative; }

    .edit-step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e9edf3;
        color: #8392ab;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .edit-step-title {
        font-size: 11px;
        color: #8392ab;
        font-weight: 600;
        margin-left: 6px;
        white-space: nowrap;
    }

    .edit-step-line {
        width: 40px;
        height: 2px;
        background: #e9edf3;
        margin: 0 8px;
        transition: all 0.25s ease;
    }

    .edit-step-item.active .edit-step-circle { background: #344767; color: #fff; }
    .edit-step-item.active .edit-step-title { color: #344767; }
    .edit-step-item.completed .edit-step-circle { background: #4caf50; color: #fff; }
    .edit-step-item.completed .edit-step-title { color: #4caf50; }
    .edit-step-line.active { background: #4caf50; }

    /* =========================================
       MODAL EDIT — TINGGI TERBATAS & LAYOUT FLEX
       ========================================= */
    .modal-edit-scrollable .modal-content {
        max-height: 85vh;
        display: flex;
        flex-direction: column;
    }

    .modal-edit-scrollable .modal-body {
        padding: 0 !important;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        max-height: 85vh;
    }

    .modal-edit-scrollable .card {
        margin-bottom: 0 !important;
        border-radius: 0;
        display: flex;
        flex-direction: column;
        max-height: 85vh;
    }

    .modal-edit-scrollable .card-header {
        flex-shrink: 0;
        background: #fff;
        border-bottom: 1px solid #e9edf3;
        padding: 18px 22px !important;
    }

    .modal-edit-scrollable .card-body {
        padding: 0 !important;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
    }

    .modal-edit-scrollable #fedetail {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-height: 0;
        overflow: hidden;
    }

    .modal-edit-scrollable .edit-step-indicator {
        flex-shrink: 0;
        padding: 16px 22px 0 22px;
        margin-bottom: 10px;
    }

    .modal-edit-scrollable .edit-step-content {
        flex: 1;
        overflow-y: auto;
        padding: 10px 22px 20px 22px;
        min-height: 0;
    }

    .modal-edit-scrollable .edit-step-content::-webkit-scrollbar { width: 6px; }
    .modal-edit-scrollable .edit-step-content::-webkit-scrollbar-thumb {
        background: #c5cdd8;
        border-radius: 3px;
    }

    .edit-actions-sticky {
        flex-shrink: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 14px 22px;
        border-top: 1px solid #e9edf3;
        background: #fff;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.04);
    }

    .edit-actions-sticky.justify-end {
        justify-content: flex-end;
    }

    .edit-actions-sticky .btn {
        margin: 0 !important;
        min-width: 100px;
    }
</style>
<div class="row mb-5">
    <div class="col-lg-3">
        <div class="card position-sticky top-3">
            <ul class="nav flex-column bg-white border-radius-lg p-3">
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex" data-scroll="" href="#profile">
                        <i class="material-icons text-lg me-2">person</i>
                        <span class="text-sm">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark d-flex" data-scroll="" href="#password">
                        <i class="material-icons text-lg me-2">key</i>
                        <span class="text-sm">Password</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php $data = in_groups('admin') || in_groups('pembimbing') || in_groups('dosen_pembimbing') ? getPembimbingData(user_id()) : getSiswaData(user_id()) ?>
    <div class="col-lg-9 mt-lg-0 mt-4">
        <div class="card card-body p-4 mb-4" id="profile">
            <div class="mb-3 d-flex align-items-center gap-3">
                <div class="avatar avatar-xl position-relative cursor-pointer" data-bs-toggle="modal" data-bs-target="#viewPhoto">
                    <img src="<?= !empty($data->foto) ? (in_groups('siswa') ? base_url('assets/img/foto_siswa/') : base_url('assets/img/foto_pembimbing/')) . $data->foto : base_url('assets/img/default-avatar.svg') ?>" alt="profile_image" class="w-100 border-radius-lg shadow-sm" style="object-fit: cover; height: 74px;">
                </div>
                <div>
                    <h4 class="m-0 p-0 d-flex align-items-center gap-2"><?= $data->nama ?> <span class="text-sm" title="Username" data-bs-toggle="tooltip">( <?= $data->username ?> )</span></h4>
                    <div class="text-gray-400 text-xs"><abbr title="waktu akun dibuat"><?= $data->created_at ?></abbr></div>
                </div>
            </div>

            <?php if (in_groups('siswa')) : ?>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-user"></i>
                    <div class="text-gray-800"><?= getIdentityLabel($data) ?>: <?= $data->nim ?></div>
                </div>
            <?php endif ?>

            <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                <i class="fa fa-user-cog"></i>
                <div class="text-gray-800"><?= implode(',', user()->getRoles()) ?></div>
            </div>

            <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                <i class="fa fa-envelope"></i>
                <div class="text-gray-800"><?= $data->uemail ?></div>
            </div>

            <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                <i class="fa fa-phone"></i>
                <div class="text-gray-800"><?= $data->no_hp ?></div>
            </div>

            <?php if (in_groups('siswa')) : ?>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-home"></i>
                    <div class="text-gray-800"><?= $data->alamat ?></div>
                </div>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-school"></i>
                    <div class="text-gray-800"><b>Jurusan: </b> <?= $data->jurusan ?: '-' ?></div>
                </div>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-building"></i>
                    <div class="text-gray-800"><b>Instansi: </b> <?= $data->instansi ?: '-' ?></div>
                </div>

                <!-- ============================================= -->
                <!-- RENCANA MAGANG -->
                <!-- ============================================= -->
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-calendar-alt text-gray-400"></i> 
                    <div class="text-gray-800">
                        <b>Rencana Magang: </b> 
                        <?php 
                            if (isset($siswa) && !empty($siswa->tgl_masuk) && !empty($siswa->tgl_selesai)) {
                                echo date('d/m/Y', strtotime($siswa->tgl_masuk)) . ' s/d ' . date('d/m/Y', strtotime($siswa->tgl_selesai));
                            } else {
                                echo '-';
                            }
                        ?>
                    </div>
                </div>
                <!-- ============================================= -->

                <hr>
                <h6 class="text-sm">Informasi Tambahan</h6>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-id-card"></i>
                    <div class="text-gray-800"><b>NIK: </b> <?= $data->nik ?? '-' ?></div>
                </div>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-map-marker-alt"></i>
                    <div class="text-gray-800"><b>Tempat, Tanggal Lahir: </b> <?= $data->tempat_lahir ?? '-' ?>, <?= $data->tanggal_lahir ?? '-' ?></div>
                </div>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-venus-mars"></i>
                    <div class="text-gray-800"><b>Jenis Kelamin: </b> <?= $data->jenis_kelamin ?? '-' ?></div>
                </div>
                <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                    <i class="fa fa-phone-alt"></i>
                    <div class="text-gray-800"><b>No HP Emergency: </b> <?= $data->no_hp_emergency ?? '-' ?></div>
                </div>

                <!-- SECTION DOSEN PEMBIMBING -->
                <?php if (!empty($data->nama_dosen)) : ?>
                    <hr>
                    <h6 class="text-sm"><i class="fa fa-user-tie me-1"></i> Dosen Pembimbing</h6>

                    <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                        <i class="fa fa-user"></i>
                        <div class="text-gray-800"><b>Nama: </b> <?= esc($data->nama_dosen) ?></div>
                    </div>

                    <?php if (!empty($data->nip_dosen)) : ?>
                        <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                            <i class="fa fa-id-card"></i>
                            <div class="text-gray-800"><b>NIP: </b> <?= esc($data->nip_dosen) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($data->nidn_dosen)) : ?>
                        <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                            <i class="fa fa-id-badge"></i>
                            <div class="text-gray-800"><b>NIDN: </b> <?= esc($data->nidn_dosen) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($data->hp_dosen)) : ?>
                        <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                            <i class="fa fa-phone"></i>
                            <div class="text-gray-800"><b>No HP: </b> <?= esc($data->hp_dosen) ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($data->email_dosen)) : ?>
                        <div class="mb-2 d-flex gap-2 align-items-center text-sm">
                            <i class="fa fa-envelope"></i>
                            <div class="text-gray-800"><b>Email: </b> <a href="mailto:<?= esc($data->email_dosen) ?>" class="text-info"><?= esc($data->email_dosen) ?></a></div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- ============================================= -->
                <!-- SECTION BERKAS (PERBAIKAN SURAT BALASAN)      -->
                <!-- ============================================= -->
                <div class="mt-3">
                    <span class="text-xs font-weight-bold">Berkas:</span><br>
                    <?php $hasBerkas = false; ?>

                    <?php if (!empty($data->ktp)) : $hasBerkas = true; ?>
                        <a href="/assets/berkas/<?= $data->ktp ?>" target="_blank" class="badge badge-info border border-info mb-1 me-1"><i class="fa fa-file-pdf"></i> KTP</a>
                    <?php endif; ?>

                    <?php if (!empty($data->bpjs)) : $hasBerkas = true; ?>
                        <a href="/assets/berkas/<?= $data->bpjs ?>" target="_blank" class="badge badge-info border border-info mb-1 me-1"><i class="fa fa-file-pdf"></i> BPJS</a>
                    <?php endif; ?>

                    <?php if (!empty($data->ktm)) : $hasBerkas = true; ?>
                        <a href="/assets/berkas/<?= $data->ktm ?>" target="_blank" class="badge badge-info border border-info mb-1 me-1"><i class="fa fa-file-pdf"></i> KTM</a>
                    <?php endif; ?>

                    <?php if (!empty($data->takaful)) : $hasBerkas = true; ?>
                        <a href="/assets/berkas/<?= $data->takaful ?>" target="_blank" class="badge badge-info border border-info mb-1 me-1"><i class="fa fa-file-pdf"></i> Takaful</a>
                    <?php endif; ?>

                    <?php if (!empty($data->surat_permohonan)) : $hasBerkas = true; ?>
                        <a href="/assets/berkas/<?= $data->surat_permohonan ?>" target="_blank" class="badge badge-info border border-info mb-1 me-1"><i class="fa fa-file-pdf"></i> Surat Permohonan</a>
                    <?php endif; ?>

                    <?php if (!empty($data->cv)) : $hasBerkas = true; ?>
                        <a href="/assets/berkas/<?= $data->cv ?>" target="_blank" class="badge badge-info border border-info mb-1 me-1"><i class="fa fa-file-pdf"></i> CV</a>
                    <?php endif; ?>

                    <?php if (!empty($data->surat_pernyataan)) : $hasBerkas = true; ?>
                        <a href="/assets/berkas/<?= $data->surat_pernyataan ?>" target="_blank" class="badge badge-info border border-info mb-1 me-1"><i class="fa fa-file-pdf"></i> Surat Pernyataan</a>
                    <?php endif; ?>

                    <?php if (!$hasBerkas) : ?>
                        <span class="text-xs text-secondary fst-italic">Belum ada berkas yang diunggah.</span>
                    <?php endif; ?>
                </div>
                <!-- END SECTION BERKAS -->
            <?php endif ?>

            <div class="mt-4 d-flex justify-content-end mb-0">
                <button class="btn btn-sm bg-gradient-dark m-0" data-bs-toggle="modal" data-bs-target="#userEdit">EDIT</button>
            </div>
        </div>

        <div class="card card-body p-4 mb-4" id="password">
            <div class="mb-4">
                <h4 class="m-0 p-0">Password</h4>
                <div class="text-gray-400 text-sm">Ubah password akun anda</div>
            </div>

            <?php if (session('errors')) : ?>
                <div class="badge border badge-danger border-danger w-100 mb-3" style="text-align: left;">
                    <ul class="list-unstyled mb-0 p-2">
                        <?php foreach (session('errors') as $error) : ?>
                            <li class="errors"><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <?php if (session('error')) : ?>
                <div class="alert alert-danger"><?= session('error') ?></div>
            <?php endif ?>

            <?php if (session('message')) : ?>
                <div class="alert alert-success"><?= session('message') ?></div>
            <?php endif ?>

            <form method="post" action="/user/reset_pass" id="fpass">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="input-group input-group-outline">
                            <label for="email" class="form-label"><?= lang('Auth.email') ?></label>
                            <input type="email" id="email" readonly class="form-control" name="email" value="<?= user()->email ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="input-group input-group-outline">
                            <label for="password" class="form-label"><?= lang('Auth.password') ?></label>
                            <input type="password" class="form-control" id="password" name="password">
                            <span class="password-toggle-icon"><i class="fa fa-eye-slash font-weight-bold"></i></span>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="input-group input-group-outline">
                            <label for="password_confirm" class="form-label"><?= lang('Auth.repeatPassword') ?></label>
                            <input type="password" class="form-control" id="pass_confirm" name="pass_confirm">
                            <span class="password-toggle-icon"><i class="fa fa-eye-slash font-weight-bold"></i></span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-sm bg-gradient-dark m-0">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal userEdit -->
<div class="modal fade modal-edit-scrollable" id="userEdit" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="mejurusan" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain mb-0">
                    <div class="card-header pb-0 text-left">
                        <h5 class="m-0 p-0">Edit Data</h5>
                        <p class="mb-0 p-0 text-xs">Edit detail user</p>
                    </div>
                    <div class="card-body">

                        <form method="post" id="fedetail" enctype="multipart/form-data">

                            <!-- STEP INDICATOR (fixed atas) -->
                            <div class="edit-step-indicator">
                                <div class="edit-step-item active" id="editStepIndicator1">
                                    <div class="edit-step-circle">1</div>
                                    <span class="edit-step-title">Data Akun</span>
                                </div>
                                <div class="edit-step-line" id="editStepLine1"></div>
                                <div class="edit-step-item" id="editStepIndicator2">
                                    <div class="edit-step-circle">2</div>
                                    <span class="edit-step-title">Data Sekolah</span>
                                </div>
                                <div class="edit-step-line" id="editStepLine2"></div>
                                <div class="edit-step-item" id="editStepIndicator3">
                                    <div class="edit-step-circle">3</div>
                                    <span class="edit-step-title">Upload Berkas</span>
                                </div>
                            </div>

                            <!-- TAHAP 1 - DATA AKUN -->
                            <div class="edit-step-content active" id="editStep1">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="input-group input-group-outline">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama" value="<?= $data->nama ?>" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="input-group input-group-outline">
                                            <label for="hp" class="form-label">Nomor HP</label>
                                            <input type="text" class="form-control" id="hp" name="no_hp" value="<?= $data->no_hp ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="input-group input-group-outline">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="<?= $data->uemail ?>" required>
                                        </div>
                                    </div>

                                    <?php if (in_groups('siswa')) : ?>
                                        <div class="col-12 mb-3">
                                            <div class="input-group input-group-outline">
                                                <label for="kelas" class="form-label">Jumlah</label>
                                                <input type="text" class="form-control" id="kelas" name="kelas" value="<?= $data->kelas ?>" required>
                                            </div>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>

                            <!-- TAHAP 2 - DATA SEKOLAH & ALAMAT -->
                            <?php if (in_groups('siswa')) : ?>
                                <div class="edit-step-content" id="editStep2">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group input-group-outline">
                                                <label for="jurusan" class="form-label">Jurusan</label>
                                                <input type="text" class="form-control" id="jurusan" name="jurusan" value="<?= $data->jurusan ?>" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="input-group input-group-outline">
                                                <label for="instansi" class="form-label">Instansi</label>
                                                <input type="text" class="form-control" id="instansi" name="instansi" value="<?= $data->instansi ?>" required>
                                            </div>
                                            <small class="text-xs text-danger font-weight-bold">
                                                *PENTING: Bagi pendaftar berkelompok, penulisan nama Instansi <b>wajib sama persis/serempak</b> antar anggota kelompok.
                                            </small>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <div class="input-group input-group-outline">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <textarea name="alamat" id="alamat" class="form-control" rows="2" required><?= $data->alamat ?></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="input-group input-group-outline">
                                                <label for="nik" class="form-label">NIK</label>
                                                <input type="text" class="form-control" id="nik" name="nik" value="<?= $data->nik ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group input-group-outline">
                                                <label for="no_hp_emergency" class="form-label">No HP Emergency</label>
                                                <input type="text" class="form-control" id="no_hp_emergency" name="no_hp_emergency" value="<?= $data->no_hp_emergency ?? '' ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group input-group-outline">
                                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?= $data->tempat_lahir ?? '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="input-group input-group-outline <?= !empty($data->tanggal_lahir) ? 'is-filled' : '' ?>">
                                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $data->tanggal_lahir ?? '' ?>">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label mb-0 text-sm">Jenis Kelamin</label>
                                            <select class="form-control border px-2" name="jenis_kelamin">
                                                <option value="" <?= empty($data->jenis_kelamin) ? 'selected' : '' ?>>Pilih Jenis Kelamin</option>
                                                <option value="Laki-laki" <?= (isset($data->jenis_kelamin) && $data->jenis_kelamin == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                                                <option value="Perempuan" <?= (isset($data->jenis_kelamin) && $data->jenis_kelamin == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAHAP 3 - UPLOAD BERKAS -->
                                <div class="edit-step-content" id="editStep3">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-sm mb-3"><i class="fa fa-upload me-1"></i> Upload Berkas</h6>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label mb-0 text-sm">Foto Profil</label>
                                            <input type="file" name="foto" class="form-control border px-2" accept="image/*">
                                            <?php if (!empty($data->foto)) : ?>
                                                <small class="text-xs text-success mt-1 d-block">
                                                    Foto saat ini: <a href="<?= base_url('assets/img/foto_siswa/') . $data->foto ?>" target="_blank" class="text-info text-decoration-underline">Lihat Foto</a>
                                                </small>
                                            <?php else: ?>
                                                <small class="text-xs text-secondary mt-1 d-block">Biarkan kosong jika tidak ingin mengubah foto profil.</small>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label mb-0 text-sm">Berkas KTP (PDF)</label>
                                            <input type="file" name="ktp" class="form-control border px-2" accept=".pdf">
                                            <?php if (!empty($data->ktp)) : ?>
                                                <small class="text-xs text-success mt-1 d-block">File saat ini: <a href="/assets/berkas/<?= $data->ktp ?>" target="_blank" class="text-info text-decoration-underline">Lihat File</a></small>
                                            <?php else: ?>
                                                <small class="text-xs text-secondary mt-1 d-block">Belum diunggah.</small>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label mb-0 text-sm">Berkas BPJS (PDF)</label>
                                            <input type="file" name="bpjs" class="form-control border px-2" accept=".pdf">
                                            <?php if (!empty($data->bpjs)) : ?>
                                                <small class="text-xs text-success mt-1 d-block">File saat ini: <a href="/assets/berkas/<?= $data->bpjs ?>" target="_blank" class="text-info text-decoration-underline">Lihat File</a></small>
                                            <?php else: ?>
                                                <small class="text-xs text-secondary mt-1 d-block">Belum diunggah.</small>
                                            <?php endif; ?>
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label class="form-label mb-0 text-sm">Berkas KTM (PDF)</label>
                                            <input type="file" name="ktm" class="form-control border px-2" accept=".pdf">
                                            <?php if (!empty($data->ktm)) : ?>
                                                <small class="text-xs text-success mt-1 d-block">File saat ini: <a href="/assets/berkas/<?= $data->ktm ?>" target="_blank" class="text-info text-decoration-underline">Lihat File</a></small>
                                            <?php else: ?>
                                                <small class="text-xs text-secondary mt-1 d-block">Belum diunggah.</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label mb-0 text-sm">Berkas Takaful (PDF)</label>
                                            <input type="file" name="takaful" class="form-control border px-2" accept=".pdf">
                                            <?php if (!empty($data->takaful)) : ?>
                                                <small class="text-xs text-success mt-1 d-block">File saat ini: <a href="/assets/berkas/<?= $data->takaful ?>" target="_blank" class="text-info text-decoration-underline">Lihat File</a></small>
                                            <?php else: ?>
                                                <small class="text-xs text-secondary mt-1 d-block">Belum diunggah.</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>

                            <!-- TOMBOL AKSI STICKY DI BAWAH -->
                            <div class="edit-actions-sticky">
                                <?php if (in_groups('siswa')) : ?>
                                    <button type="button" class="btn btn-sm bg-gradient-light m-0 d-none" id="editPrevStep1">
                                        <i class="fa fa-arrow-left me-1"></i> Kembali
                                    </button>
                                    <button type="button" class="btn btn-sm bg-gradient-secondary shadow m-0" data-bs-dismiss="modal" id="editCloseBtn">
                                        Tutup
                                    </button>
                                    <button type="button" class="btn btn-sm bg-gradient-dark m-0 ms-auto" id="editNextStep1">
                                        Lanjut <i class="fa fa-arrow-right ms-1"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm bg-gradient-light m-0 d-none" id="editPrevStep2">
                                        <i class="fa fa-arrow-left me-1"></i> Kembali
                                    </button>
                                    <button type="button" class="btn btn-sm bg-gradient-dark m-0 ms-auto d-none" id="editNextStep2">
                                        Lanjut <i class="fa fa-arrow-right ms-1"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm bg-gradient-light m-0 d-none" id="editPrevStep3">
                                        <i class="fa fa-arrow-left me-1"></i> Kembali
                                    </button>
                                    <button type="submit" class="btn btn-sm bg-gradient-dark m-0 ms-auto d-none" id="editSubmitBtn">SIMPAN</button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm bg-gradient-secondary shadow m-0" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-sm bg-gradient-dark m-0">SIMPAN</button>
                                <?php endif ?>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal View Photo -->
<div class="modal fade" id="viewPhoto" tabindex="-1" aria-labelledby="viewPhotoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-transparent shadow-none border-0">
            <div class="modal-body p-0 text-center">
                <img src="<?= !empty($data->foto) ? (in_groups('siswa') ? base_url('assets/img/foto_siswa/') : base_url('assets/img/foto_pembimbing/')) . $data->foto : base_url('assets/img/default-avatar.svg') ?>" class="img-fluid border-radius-lg shadow-lg" alt="Full profile photo">
                <div class="mt-3">
                    <button type="button" class="btn btn-sm btn-white" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/sweetalert.min.js"></script>
<?php if (session('success')) : ?>
    <script>
        Swal.fire({
            title: 'Sukses!',
            icon: 'success',
            text: '<?= session("success") ?>',
            buttons: false,
        });
    </script>
<?php endif ?>

<script>
    $(document).ready(function() {

        function showEditStep(step) {
            $('.edit-step-content').removeClass('active');
            $('#editStep' + step).addClass('active');

            $('#editStepIndicator1, #editStepIndicator2, #editStepIndicator3').removeClass('active completed');
            $('#editStepLine1, #editStepLine2').removeClass('active');

            if (step >= 1) $('#editStepIndicator1').addClass('active');
            if (step >= 2) {
                $('#editStepIndicator1').removeClass('active').addClass('completed');
                $('#editStepLine1').addClass('active');
                $('#editStepIndicator2').addClass('active');
            }
            if (step >= 3) {
                $('#editStepIndicator2').removeClass('active').addClass('completed');
                $('#editStepLine2').addClass('active');
                $('#editStepIndicator3').addClass('active');
            }

            $('#editCloseBtn').toggleClass('d-none', step !== 1);
            $('#editNextStep1').toggleClass('d-none', step !== 1);
            $('#editPrevStep2').toggleClass('d-none', step !== 2);
            $('#editNextStep2').toggleClass('d-none', step !== 2);
            $('#editPrevStep3').toggleClass('d-none', step !== 3);
            $('#editSubmitBtn').toggleClass('d-none', step !== 3);
        }

        $('#editPrevStep1, #editPrevStep2, #editPrevStep3, #editNextStep2, #editSubmitBtn').addClass('d-none');
        $('#editCloseBtn, #editNextStep1').removeClass('d-none');

        $('#editNextStep1').on('click', function() {
            let valid = true;
            ['#nama', '#hp', '#email', '#kelas'].forEach(function(selector) {
                const field = $(selector);
                if (!field.val() || field.val().trim() === '') {
                    field.addClass('is-invalid');
                    valid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            });

            if (valid) showEditStep(2);
            else Swal.fire({ title: 'Data Belum Lengkap!', icon: 'warning', text: 'Mohon isi semua field yang wajib diisi.' });
        });

        $('#editNextStep2').on('click', function() {
            let valid = true;
            ['#jurusan', '#instansi', '#alamat'].forEach(function(selector) {
                const field = $(selector);
                if (!field.val() || field.val().trim() === '') {
                    field.addClass('is-invalid');
                    valid = false;
                } else {
                    field.removeClass('is-invalid');
                }
            });

            if (valid) showEditStep(3);
            else Swal.fire({ title: 'Data Belum Lengkap!', icon: 'warning', text: 'Mohon isi semua field yang wajib diisi.' });
        });

        $('#editPrevStep2').on('click', function() { showEditStep(1); });
        $('#editPrevStep3').on('click', function() { showEditStep(2); });

        $('#userEdit').on('shown.bs.modal', function() { showEditStep(1); });

        $("#fedetail").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "<?= in_groups('admin') || in_groups('pembimbing') || in_groups('dosen_pembimbing') ? '/user/profile/update' : (in_groups('siswa') ? '/siswa/profile/update' : '') ?>",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: "json",
                success: function(s) {
                    if (s.success) {
                        Swal.fire({
                            title: 'Sukses!',
                            icon: 'success',
                            text: s.message,
                            showConfirmButton: false,
                            timer: 1500,
                        }).then(() => { location.reload(); });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            icon: 'error',
                            text: s.message || 'Terjadi kesalahan',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    console.error('Response:', xhr.responseText);
                    
                    let errorMsg = 'Terjadi kesalahan, silahkan coba lagi';
                    try {
                        const res = JSON.parse(xhr.responseText);
                        errorMsg = res.message || res.error || errorMsg;
                    } catch (e) {
                        errorMsg = xhr.responseText.substring(0, 500) || errorMsg;
                    }
                    
                    Swal.fire({ 
                        title: 'Gagal!', 
                        icon: 'error', 
                        text: errorMsg,
                        width: 700,
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        if ($('#kelas').val() != '') $('#kelas').parent().addClass('is-filled');
        $('#kelas').on('change', function() {
            if ($(this).val() != '') $(this).parent().addClass('is-filled');
            else $(this).parent().removeClass('is-filled');
        });

        if ($('#alamat').val() != '') $('#alamat').parent().addClass('is-filled');
        $('#alamat').on('change', function() {
            if ($(this).val() != '') $(this).parent().addClass('is-filled');
            else $(this).parent().removeClass('is-filled');
        });

        if ($('#jurusan').val() != '') $('#jurusan').parent().addClass('is-filled');
        if ($('#instansi').val() != '') $('#instansi').parent().addClass('is-filled');
    });
</script>
<?= $this->endSection(); ?>