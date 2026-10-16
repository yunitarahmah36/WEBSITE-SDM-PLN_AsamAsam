<?= $this->extend($config->viewLayout) ?>

<?= $this->section('main') ?>

<style>
/* =========================================================
   REGISTER CARD
   ========================================================= */
.register-card { max-width: 620px; margin: 40px auto; }
.register-header { border-radius: 14px; padding: 22px 20px !important; }
.register-header img { width: 75px !important; }
.register-header h4 { font-size: 18px; margin-top: 10px !important; }
.register-header .system-title { font-size: 11px; line-height: 1.5; }
.register-body { padding: 22px !important; }

/* =========================================================
   STEP INDICATOR
   ========================================================= */
.register-steps { display: flex; align-items: center; justify-content: center; margin-bottom: 25px; gap: 0; }
.register-step { display: flex; align-items: center; position: relative; }
.step-circle { width: 34px; height: 34px; border-radius: 50%; background: #e9edf3; color: #8392ab; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; transition: all 0.25s ease; }
.step-title { font-size: 11px; color: #8392ab; font-weight: 600; margin-left: 7px; white-space: nowrap; }
.step-line { width: 70px; height: 2px; background: #e9edf3; margin: 0 12px; transition: all 0.25s ease; }
.register-step.active .step-circle { background: #344767; color: #fff; }
.register-step.active .step-title { color: #344767; }
.register-step.completed .step-circle { background: #4caf50; color: #fff; }
.register-step.completed .step-title { color: #4caf50; }
.step-line.active { background: #4caf50; }

/* =========================================================
   STEP CONTENT
   ========================================================= */
.register-step-content { display: none; }
.register-step-content.active { display: block; animation: fadeStep 0.25s ease; }
@keyframes fadeStep { from { opacity: 0; transform: translateX(8px); } to { opacity: 1; transform: translateX(0); } }
.step-heading { margin-bottom: 22px; }
.step-heading h6 { margin-bottom: 3px; color: #344767; font-size: 16px; font-weight: 700; }
.step-heading p { margin: 0; font-size: 12px; color: #8392ab; }

/* =========================================================
   FORM
   ========================================================= */
.register-body .row { margin-bottom: 0; }
.register-body .form-group { margin-bottom: 18px !important; position: relative; }
.register-body .row > [class*="col-"] { margin-bottom: 0; }

/* =========================================================
   INPUT GROUP
   ========================================================= */
.register-body .input-group.input-group-outline { position: relative !important; display: flex !important; flex-wrap: wrap !important; align-items: center !important; width: 100% !important; margin: 0 !important; border-radius: 8px !important; }
.register-body .input-group.input-group-outline .form-control { display: block !important; width: 100% !important; height: 50px !important; min-height: 50px !important; padding: 14px 16px !important; border: 1px solid #d9e0e8 !important; border-radius: 8px !important; background-color: #fff !important; background-image: none !important; color: #344767 !important; font-size: 13px !important; box-shadow: none !important; transition: border-color 0.15s ease, box-shadow 0.15s ease !important; }

/* =========================================================
   FLOATING LABEL
   ========================================================= */
.register-body .input-group.input-group-outline .form-label { position: absolute !important; top: 50% !important; left: 16px !important; transform: translateY(-50%) !important; margin: 0 !important; padding: 0 4px !important; background: transparent !important; color: #8392ab !important; font-size: 13px !important; font-weight: 400 !important; line-height: 1 !important; pointer-events: none !important; transition: all 0.2s ease !important; z-index: 2 !important; width: auto !important; height: auto !important; }
.register-body .input-group.input-group-outline .form-label::before,
.register-body .input-group.input-group-outline .form-label::after { display: none !important; content: none !important; }
.register-body .input-group.input-group-outline:focus-within .form-label { top: 0 !important; left: 12px !important; transform: translateY(-50%) scale(0.85) !important; transform-origin: left center !important; background-color: #fff !important; color: #5e72e4 !important; font-weight: 500 !important; padding: 0 6px !important; }
.register-body .input-group.input-group-outline .form-control.is-filled ~ .form-label,
.register-body .input-group.input-group-outline .form-control:not(:placeholder-shown) ~ .form-label { top: 0 !important; left: 12px !important; transform: translateY(-50%) scale(0.85) !important; transform-origin: left center !important; background-color: #fff !important; color: #8392ab !important; font-weight: 500 !important; padding: 0 6px !important; }

/* =========================================================
   FOCUS
   ========================================================= */
.register-body .input-group.input-group-outline .form-control:focus { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; outline: none !important; background-color: #fff !important; background-image: none !important; }

/* =========================================================
   FIELD TERISI
   ========================================================= */
.register-body .input-group.input-group-outline .form-control.is-filled,
.register-body .input-group.input-group-outline input.is-filled,
.register-body .input-group.input-group-outline select.is-filled { border: 1px solid #d9e0e8 !important; box-shadow: none !important; background-color: #fff !important; background-image: none !important; color: #344767 !important; }
.register-body .input-group.input-group-outline .form-control.is-filled:focus { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; background-color: #fff !important; }

/* =========================================================
   ERROR / INVALID
   ========================================================= */
.register-body .input-group.input-group-outline .form-control.is-invalid,
.register-body .input-group.input-group-outline .is-invalid { border: 1px solid #5e72e4 !important; background-color: #fff !important; background-image: none !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; outline: none !important; }
.register-body .input-group.input-group-outline .form-control.is-invalid:focus { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; background-color: #fff !important; }
.register-body .input-group.input-group-outline:has(.is-invalid) .form-label { color: #5e72e4 !important; }

/* =========================================================
   SELECT JUMLAH PENDAFTAR
   ========================================================= */
.register-body .input-group.input-group-outline:has(#kelas) .form-select { display: block !important; width: 100% !important; height: 50px !important; min-height: 50px !important; padding: 14px 16px !important; border: 1px solid #d9e0e8 !important; border-radius: 8px !important; background-color: #fff !important; background-image: none !important; color: #344767 !important; box-shadow: none !important; font-size: 13px !important; }
.register-body .input-group.input-group-outline:has(#kelas) .form-select:focus { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; outline: none !important; background-color: #fff !important; }
.register-body .input-group.input-group-outline:has(#kelas) .form-select.is-filled { border: 1px solid #d9e0e8 !important; background-color: #fff !important; box-shadow: none !important; }
.register-body .input-group.input-group-outline:has(#kelas) .form-select.is-invalid { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; background-color: #fff !important; }

/* =========================================================
   TANGGAL
   ========================================================= */
.register-body .date-field { display: block; margin: 0; }
.register-body .date-field .form-label { display: block; position: static; font-size: 12px; font-weight: 500; color: #8392ab; margin-bottom: 8px; transform: none; }
.register-body .date-field input[type="date"].form-control { display: block; width: 100%; height: 50px; min-height: 50px; padding: 0 16px; border: 1px solid #d9e0e8 !important; border-radius: 8px !important; background-color: #fff !important; background-image: none !important; color: #344767 !important; font-size: 13px; box-shadow: none !important; }
.register-body .date-field input[type="date"].form-control:focus { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; outline: none !important; background-color: #fff !important; }
.register-body .date-field input[type="date"].form-control.is-filled { border: 1px solid #d9e0e8 !important; background-color: #fff !important; box-shadow: none !important; }
.register-body .date-field input[type="date"].form-control.is-invalid { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; background-color: #fff !important; }

/* =========================================================
   PESAN ERROR
   ========================================================= */
.register-body .text-danger { font-size: 10px; color: #5e72e4 !important; margin-top: 5px; display: block; line-height: 1.3; }

/* =========================================================
   FILE UPLOAD
   ========================================================= */
.file-label { display: block; font-size: 12px; font-weight: 500; color: #8392ab; margin-bottom: 8px; line-height: 1.4; }
.file-input { font-size: 12px !important; padding: 13px 14px !important; height: 50px !important; min-height: 50px !important; cursor: pointer; border: 1px solid #d9e0e8 !important; border-radius: 8px !important; background-color: #fff !important; background-image: none !important; width: 100% !important; color: #344767 !important; box-shadow: none !important; }
.file-input:focus { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; outline: none !important; background-color: #fff !important; }
.file-input.is-invalid { border: 1px solid #5e72e4 !important; box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.10) !important; background-color: #fff !important; }
.register-body .file-label { min-height: 18px; display: flex; align-items: center; }

/* =========================================================
   PASSWORD
   ========================================================= */
.register-body .password-field { position: relative; }
.register-body .password-field .form-control { padding-right: 48px !important; }
.register-body .password-toggle-icon { position: absolute; top: 50%; right: 16px; transform: translateY(-50%); cursor: pointer; color: #8392ab; z-index: 9999; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 14px; transition: color 0.2s ease; pointer-events: auto !important; user-select: none; }
.register-body .password-toggle-icon:hover { color: #5e72e4; }
.register-body .password-toggle-icon:active { transform: translateY(-50%) scale(0.95); }
.register-body .password-toggle-icon i { pointer-events: none !important; }

/* =========================================================
   HINT PASSWORD
   ========================================================= */
.password-hint { font-size: 10px; color: #8392ab; margin-top: 6px; margin-bottom: 0; display: block; line-height: 1.4; }

/* =========================================================
   SECTION HEADER DOSEN
   ========================================================= */
.dosen-section-header { margin-top: 10px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid #e9edf3; }
.dosen-section-header h6 { font-size: 14px; font-weight: 700; color: #344767; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
.dosen-section-header h6 i { color: #5e72e4; font-size: 14px; }
.dosen-section-header p { font-size: 11px; color: #8392ab; margin: 0; line-height: 1.4; }
.dosen-info-badge { display: flex; align-items: center; gap: 6px; background: #f0f4ff; color: #4c5fd5; border: 1px solid #d9e2ff; border-radius: 6px; padding: 8px 12px; font-size: 11px; font-weight: 500; margin-bottom: 18px; line-height: 1.4; }
.dosen-info-badge i { font-size: 12px; flex-shrink: 0; }
.register-body .required-star { color: #5e72e4; font-weight: 700; margin-left: 2px; }

/* =========================================================
   BUTTON
   ========================================================= */
.step-buttons { display: flex; justify-content: space-between; gap: 12px; margin-top: 28px; }
.step-buttons .btn { margin: 0 !important; height: 50px !important; min-height: 50px !important; max-height: 50px !important; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; padding: 0 20px !important; line-height: 1 !important; box-shadow: none !important; }
.btn-back { background: #f1f3f5 !important; color: #344767 !important; width: 130px; flex: 0 0 130px !important; }
.btn-next, .btn-register { flex: 1; background: #344767 !important; color: #fff !important; }
.btn-next:hover, .btn-register:hover { background: #2c3a56 !important; color: #fff !important; }

/* =========================================================
   LOGIN
   ========================================================= */
.login-section { margin-top: 22px; }
.login-section hr { margin-bottom: 15px !important; }
.login-section .text-sm { font-size: 11px !important; }

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 575px) {
    .register-card { margin: 25px 10px; }
    .register-body { padding: 18px !important; }
    .register-steps { justify-content: flex-start; overflow-x: auto; padding-bottom: 5px; }
    .step-line { width: 35px; margin: 0 8px; }
    .step-title { font-size: 10px; }
    .step-circle { width: 30px; height: 30px; }
    .step-buttons { flex-direction: column-reverse; gap: 10px; }
    .btn-back { width: 100%; flex: 1 1 100% !important; }
    .register-body .form-group { margin-bottom: 16px !important; }
}
</style>

<main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('/assets/img/login-bg.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-5 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom register-card">

                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1 register-header">
                                <div class="text-center">
                                    <img src="/assets/logo-pln.png" class="navbar-brand-img" alt="main_logo">
                                </div>
                                <h4 class="text-white font-weight-bolder text-center mb-0">Registrasi Peserta</h4>
                                <div class="text-center mt-2">
                                    <span class="text-white font-weight-bold system-title">SISTEM INFORMASI<br>PESERTA MAGANG</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-body register-body">
                            <?= view('App\Views\Auth\_message_block') ?>

                            <div class="register-steps">
                                <div class="register-step active" id="stepIndicator1">
                                    <div class="step-circle">1</div>
                                    <span class="step-title">Data Diri</span>
                                </div>
                                <div class="step-line" id="stepLine"></div>
                                <div class="register-step" id="stepIndicator2">
                                    <div class="step-circle">2</div>
                                    <span class="step-title">Data Magang</span>
                                </div>
                            </div>

                            <form role="form" action="<?= url_to('register') ?>" method="post" enctype="multipart/form-data" id="registerForm">
                                <?= csrf_field() ?>

                                <!-- TAHAP 1 -->
                                <div class="register-step-content active" id="step1">
                                    <div class="step-heading">
                                        <h6>Data Diri & Akun</h6>
                                        <p>Isi informasi pribadi dan akun Anda.</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control <?= session('errors.nim') ? 'is-invalid' : '' ?>" name="nim" id="nim" placeholder=" " <?= old('nim') ? 'value="' . old('nim') . '"' : '' ?> required>
                                                    <label class="form-label" for="nim">NIM/NIS</label>
                                                </div>
                                                <?php if (session('errors.nim')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.nim') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control <?= session('errors.nama') ? 'is-invalid' : '' ?>" name="nama" id="nama" placeholder=" " <?= old('nama') ? 'value="' . old('nama') . '"' : '' ?> required>
                                                    <label class="form-label" for="nama">Nama Lengkap</label>
                                                </div>
                                                <?php if (session('errors.nama')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.nama') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" name="email" id="email" placeholder=" " <?= old('email') ? 'value="' . old('email') . '"' : '' ?> required>
                                                    <label class="form-label" for="email"><?= lang('Auth.email') ?></label>
                                                </div>
                                                <?php if (session('errors.email')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.email') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" name="username" id="username" placeholder=" " <?= old('username') ? 'value="' . old('username') . '"' : '' ?> required>
                                                    <label class="form-label" for="username"><?= lang('Auth.username') ?></label>
                                                </div>
                                                <?php if (session('errors.username')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.username') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <select class="form-control form-select" name="kelas" id="kelas" required>
                                                        <option value="" disabled <?= !old('kelas') ? 'selected' : '' ?>>Pilih jumlah</option>
                                                        <option value="1 Orang" <?= old('kelas') == '1 Orang' ? 'selected' : '' ?>>1 Orang</option>
                                                        <option value="2 Orang" <?= old('kelas') == '2 Orang' ? 'selected' : '' ?>>2 Orang</option>
                                                        <option value="3 Orang" <?= old('kelas') == '3 Orang' ? 'selected' : '' ?>>3 Orang</option>
                                                        <option value="4 Orang" <?= old('kelas') == '4 Orang' ? 'selected' : '' ?>>4 Orang</option>
                                                        <option value="5 Orang" <?= old('kelas') == '5 Orang' ? 'selected' : '' ?>>5 Orang</option>
                                                        <option value="Lebih dari 5 Orang" <?= old('kelas') == 'Lebih dari 5 Orang' ? 'selected' : '' ?>>Lebih dari 5 Orang</option>
                                                    </select>
                                                    <label class="form-label" for="kelas">Jumlah Pendaftar</label>
                                                </div>
                                                <?php if (session('errors.kelas')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.kelas') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control <?= session('errors.no_hp') ? 'is-invalid' : '' ?>" name="no_hp" id="no_hp" placeholder=" " oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?= old('no_hp') ? 'value="' . old('no_hp') . '"' : '' ?> required>
                                                    <label class="form-label" for="no_hp">Nomor WhatsApp</label>
                                                </div>
                                                <?php if (session('errors.no_hp')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.no_hp') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group input-group-outline">
                                            <input type="text" class="form-control <?= session('errors.alamat') ? 'is-invalid' : '' ?>" name="alamat" id="alamat" placeholder=" " <?= old('alamat') ? 'value="' . old('alamat') . '"' : '' ?> required>
                                            <label class="form-label" for="alamat">Alamat Lengkap</label>
                                        </div>
                                        <?php if (session('errors.alamat')) : ?>
                                            <small class="text-danger text-xxs ms-1"><?= session('errors.alamat') ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control <?= session('errors.jurusan') ? 'is-invalid' : '' ?>" name="jurusan" id="jurusan" placeholder=" " <?= old('jurusan') ? 'value="' . old('jurusan') . '"' : '' ?> required>
                                                    <label class="form-label" for="jurusan">Jurusan</label>
                                                </div>
                                                <?php if (session('errors.jurusan')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.jurusan') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control <?= session('errors.instansi') ? 'is-invalid' : '' ?>" name="instansi" id="instansi" placeholder=" " <?= old('instansi') ? 'value="' . old('instansi') . '"' : '' ?> required>
                                                    <label class="form-label" for="instansi">Nama Instansi</label>
                                                </div>
                                                <?php if (session('errors.instansi')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.instansi') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="step-buttons">
                                        <button type="button" class="btn bg-gradient-dark w-100 btn-next" id="nextStep">
                                            Lanjut <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- TAHAP 2 -->
                                <div class="register-step-content" id="step2">
                                    <div class="step-heading">
                                        <h6>Data Magang & Dokumen</h6>
                                        <p>Lengkapi informasi magang, dosen/guru pembimbing, dan dokumen pendaftaran.</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="date-field">
                                                    <label class="form-label" for="tgl_masuk">Rencana Tanggal Masuk</label>
                                                    <input type="date" class="form-control <?= session('errors.tgl_masuk') ? 'is-invalid' : '' ?>" name="tgl_masuk" id="tgl_masuk" <?= old('tgl_masuk') ? 'value="' . old('tgl_masuk') . '"' : '' ?> required>
                                                </div>
                                                <?php if (session('errors.tgl_masuk')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.tgl_masuk') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="date-field">
                                                    <label class="form-label" for="tgl_selesai">Rencana Tanggal Selesai</label>
                                                    <input type="date" class="form-control <?= session('errors.tgl_selesai') ? 'is-invalid' : '' ?>" name="tgl_selesai" id="tgl_selesai" <?= old('tgl_selesai') ? 'value="' . old('tgl_selesai') . '"' : '' ?> required>
                                                </div>
                                                <?php if (session('errors.tgl_selesai')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.tgl_selesai') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECTION DOSEN -->
                                    <div class="dosen-section-header">
                                        <h6>
                                            <i class="fas fa-user-tie"></i>
                                            Identitas Dosen/guru Pembimbing
                                        </h6>
                                        <p>Isi data dosen/guru pembimbing dari kampus/sekolah Anda</p>
                                    </div>

                                    <div class="dosen-info-badge">
                                        <i class="fas fa-info-circle"></i>
                                        Wajib: isi minimal salah satu — NIP (dosen PNS) atau NIDN (dosen non-PNS)
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group input-group-outline">
                                            <input type="text" class="form-control <?= session('errors.dosen_nama') ? 'is-invalid' : '' ?>" name="dosen_nama" id="dosen_nama" placeholder=" " value="<?= old('dosen_nama') ?>" required>
                                            <label class="form-label" for="dosen_nama">Nama Dosen/Guru Pembimbing<span class="required-star">*</span></label>
                                        </div>
                                        <?php if (session('errors.dosen_nama')) : ?>
                                            <small class="text-danger text-xxs ms-1"><?= session('errors.dosen_nama') ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outli   ne">
                                                    <input type="text" class="form-control <?= session('errors.dosen_nip') ? 'is-invalid' : '' ?>" name="dosen_nip" id="dosen_nip" placeholder=" " value="<?= old('dosen_nip') ?>">
                                                    <label class="form-label" for="dosen_nip">NIP Dosen/Guru (untuk PNS)</label>
                                                </div>
                                                <?php if (session('errors.dosen_nip')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.dosen_nip') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control <?= session('errors.dosen_nidn') ? 'is-invalid' : '' ?>" name="dosen_nidn" id="dosen_nidn" placeholder=" " value="<?= old('dosen_nidn') ?>">
                                                    <label class="form-label" for="dosen_nidn">NIDN Dosen/Guru (untuk Non-PNS)</label>
                                                </div>
                                                <?php if (session('errors.dosen_nidn')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.dosen_nidn') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="text" class="form-control" name="dosen_hp" id="dosen_hp" placeholder=" " value="<?= old('dosen_hp') ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                    <label class="form-label" for="dosen_hp">No. WhatsApp Dosen/Guru (Opsional)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline">
                                                    <input type="email" class="form-control" name="dosen_email" id="dosen_email" placeholder=" " value="<?= old('dosen_email') ?>">
                                                    <label class="form-label" for="dosen_email">Email Dosen/Guru (Opsional)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DOKUMEN -->
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="file-label" for="surat_permohonan">Surat Permohonan Magang (PDF)</label>
                                                <input type="file" accept=".pdf" class="form-control file-input <?= session('errors.surat_permohonan') ? 'is-invalid' : '' ?>" name="surat_permohonan" id="surat_permohonan" required>
                                                <?php if (session('errors.surat_permohonan')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.surat_permohonan') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="file-label" for="cv">Curriculum Vitae (PDF)</label>
                                                <input type="file" accept=".pdf" class="form-control file-input <?= session('errors.cv') ? 'is-invalid' : '' ?>" name="cv" id="cv" required>
                                                <?php if (session('errors.cv')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.cv') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PASSWORD -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline password-field">
                                                    <input type="password" name="password" id="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" placeholder=" " autocomplete="off" required>
                                                    <label class="form-label" for="password"><?= lang('Auth.password') ?></label>
                                                    <span class="password-toggle-icon" data-target="password" role="button" tabindex="0" aria-label="Tampilkan password">
                                                        <i class="fa fa-eye-slash font-weight-bold"></i>
                                                    </span>
                                                </div>
                                                <small class="password-hint">Gunakan kombinasi huruf besar, huruf kecil, dan karakter lain (simbol/angka).</small>
                                                <?php if (session('errors.password')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.password') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="input-group input-group-outline password-field">
                                                    <input type="password" name="pass_confirm" id="pass_confirm" class="form-control <?= session('errors.pass_confirm') ? 'is-invalid' : '' ?>" placeholder=" " autocomplete="off" required>
                                                    <label class="form-label" for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                                                    <span class="password-toggle-icon" data-target="pass_confirm" role="button" tabindex="0" aria-label="Tampilkan ulangi password">
                                                        <i class="fa fa-eye-slash font-weight-bold"></i>
                                                    </span>
                                                </div>
                                                <?php if (session('errors.pass_confirm')) : ?>
                                                    <small class="text-danger text-xxs ms-1"><?= session('errors.pass_confirm') ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="step-buttons">
                                        <button type="button" class="btn btn-back" id="prevStep">
                                            <i class="fas fa-arrow-left me-2"></i>Kembali
                                        </button>
                                        <button type="submit" class="btn bg-gradient-dark btn-register">
                                            <i class="fas fa-user-plus me-2"></i>Daftar
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="text-center login-section">
                                <hr class="horizontal dark">
                                <div class="text-sm mb-1"><?= lang('Auth.alreadyRegistered') ?></div>
                                <a class="text-sm text-dark text-gradient font-weight-bold" href="<?= url_to('login') ?>">
                                    <?= lang('Auth.signIn') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    let currentStep = 1;

    function showStep(step) {
        $('.register-step-content').removeClass('active');
        $('#step' + step).addClass('active');

        if (step === 1) {
            $('#stepIndicator1').addClass('active').removeClass('completed');
            $('#stepIndicator2').removeClass('active completed');
            $('#stepLine').removeClass('active');
        } else {
            $('#stepIndicator1').removeClass('active').addClass('completed');
            $('#stepIndicator2').addClass('active');
            $('#stepLine').addClass('active');
        }

        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStepOne() {
        let valid = true;
        const fields = ['#nim', '#nama', '#email', '#username', '#kelas', '#no_hp', '#alamat', '#jurusan', '#instansi'];

        fields.forEach(function (selector) {
            const field = $(selector);
            const value = field.val();
            if (value === null || value === undefined || String(value).trim() === '') {
                field.addClass('is-invalid');
                valid = false;
            } else {
                field.removeClass('is-invalid');
            }
        });

        if (!valid) {
            const firstInvalid = $('.register-step-content.active .is-invalid').first();
            if (firstInvalid.length) firstInvalid.focus();
        }
        return valid;
    }

    // Validasi tambahan: NIP/NIDN dosen minimal salah satu
    $('#registerForm').on('submit', function(e) {
        const nip = $('#dosen_nip').val().trim();
        const nidn = $('#dosen_nidn').val().trim();
        const namaDosen = $('#dosen_nama').val().trim();

        if (namaDosen === '') {
            e.preventDefault();
            alert('Nama Dosen Pembimbing wajib diisi!');
            $('#dosen_nama').addClass('is-invalid').focus();
            return false;
        }

        if (nip === '' && nidn === '') {
            e.preventDefault();
            alert('Minimal isi NIP atau NIDN dosen pembimbing!');
            $('#dosen_nip').addClass('is-invalid').focus();
            return false;
        }
    });

    $('#nextStep').on('click', function () {
        if (validateStepOne()) showStep(2);
    });

    $('#prevStep').on('click', function () {
        showStep(1);
    });

    function updateFilledState() {
        $('#registerForm input, #registerForm select').each(function () {
            const value = $(this).val();
            if (value !== null && value !== undefined && String(value).trim() !== '') {
                $(this).addClass('is-filled');
            } else {
                $(this).removeClass('is-filled');
            }
        });
    }

    $('#registerForm input, #registerForm select').on('input change', function () {
        updateFilledState();
        const value = $(this).val();
        if (value !== null && value !== undefined && String(value).trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });

    updateFilledState();

    function checkInstansi() {
        let val = $('input[name="instansi"]').val() || '';
        val = val.toLowerCase();
        if (val.indexOf('smk') !== -1 || val.indexOf('sekolah') !== -1) {
            $('label[for="nim"]').text('NIS');
        } else {
            $('label[for="nim"]').text('NIM/NIS');
        }
    }

    $('input[name="instansi"]').on('input change', checkInstansi);
    checkInstansi();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('.password-toggle-icon');

    toggleButtons.forEach(function (toggleButton) {
        toggleButton.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            togglePassword(this);
        });

        toggleButton.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                togglePassword(this);
            }
        });
    });

    function togglePassword(button) {
        const targetId = button.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = button.querySelector('i');

        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            if (icon) { icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
            button.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            input.type = 'password';
            if (icon) { icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
            button.setAttribute('aria-label', 'Tampilkan password');
        }
    }
});
</script>

<?= $this->endSection() ?>