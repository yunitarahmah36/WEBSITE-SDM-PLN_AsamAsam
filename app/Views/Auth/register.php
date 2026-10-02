<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

<main class="main-content  mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('/assets/img/login-bg.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container my-auto">
            <div class="row">
                <div class="col-lg-4 col-md-8 col-12 mx-auto">
                    <div class="card z-index-0 fadeIn3 fadeInBottom">
                        <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                            <div class="bg-gradient-dark shadow-dark border-radius-lg py-3 pe-1">
                                <div class="text-center">
                                    <img src="/assets/logo-pln.png" class="navbar-brand-img" style="width: 100px;" alt="main_logo">
                                </div>
                                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Registrasi Mahasiswa</h4>
                                <div class="row mt-0">
                                    <div class="col-12 text-center px-1">
                                        <a class="btn btn-link px-5" href="javascript:;">
                                            <span class="text-white font-weight-bold" style="font-style: normal;">Sistem Informasi<br>Magang Mahasiswa</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <?= view('App\Views\Auth\_message_block') ?>
                            <form role="form" action="<?= url_to('register') ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0">
                                            <label class="form-label" for="nim">NIM/NIS</label>
                                            <input type="text" class="form-control <?php if (session('errors.nim')) : ?>is-invalid<?php endif ?>" name="nim" <?= old('nim') ? 'value="' . old("nim") . '"' : '' ?>>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.nim')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.nim') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0">
                                            <label class="form-label" for="nama">Nama Lengkap</label>
                                            <input type="text" class="form-control <?php if (session('errors.nama')) : ?>is-invalid<?php endif ?>" name="nama" <?= old('nama') ? 'value="' . old("nama") . '"' : '' ?>>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.nama')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.nama') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0">
                                            <label class="form-label" for="email"><?= lang('Auth.email') ?></label>
                                            <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>" name="email" aria-describedby="emailHelp" <?= old('email') ? 'value="' . old("email") . '"' : '' ?>>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.email')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.email') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0">
                                            <label class="form-label" for="username"><?= lang('Auth.username') ?></label>
                                            <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" <?= old('username') ? 'value="' . old("username") . '"' : '' ?>>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.username')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.username') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0 is-filled">
                                            <label class="form-label" for="kelas">Jumlah Pendaftar</label>
                                            <select class="form-control form-select <?php if (session('errors.kelas')) : ?>is-invalid<?php endif ?>" name="kelas" required>
                                                <option value="" disabled <?= !old('kelas') ? 'selected' : '' ?>>Pilih jumlah</option>
                                                <option value="1 Orang" <?= old('kelas') == '1 Orang' ? 'selected' : '' ?>>1 Orang</option>
                                                <option value="2 Orang" <?= old('kelas') == '2 Orang' ? 'selected' : '' ?>>2 Orang</option>
                                                <option value="3 Orang" <?= old('kelas') == '3 Orang' ? 'selected' : '' ?>>3 Orang</option>
                                                <option value="4 Orang" <?= old('kelas') == '4 Orang' ? 'selected' : '' ?>>4 Orang</option>
                                                <option value="5 Orang" <?= old('kelas') == '5 Orang' ? 'selected' : '' ?>>5 Orang</option>
                                                <option value="Lebih dari 5 Orang" <?= old('kelas') == 'Lebih dari 5 Orang' ? 'selected' : '' ?>>Lebih dari 5 Orang</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.kelas')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.kelas') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0">
                                            <label class="form-label" for="no_hp">Nomor WhatsApp</label>
                                            <input type="text" class="form-control <?php if (session('errors.no_hp')) : ?>is-invalid<?php endif ?>" name="no_hp" oninput="this.value = this.value.replace(/[^0-9]/g, '')" <?= old('no_hp') ? 'value="' . old("no_hp") . '"' : '' ?>>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.no_hp')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.no_hp') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="input-group input-group-outline mb-0">
                                    <label class="form-label" for="alamat">Alamat Lengkap</label>
                                    <input type="text" class="form-control <?php if (session('errors.alamat')) : ?>is-invalid<?php endif ?>" name="alamat" <?= old('alamat') ? 'value="' . old("alamat") . '"' : '' ?>>
                                </div>
                                <div class="mb-3">
                                    <?php if (session('errors.alamat')) : ?>
                                        <small class="text-danger text-xxs ms-1"><?= session('errors.alamat') ?></small>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0">
                                            <label class="form-label" for="jurusan">Jurusan</label>
                                            <input type="text" class="form-control <?php if (session('errors.jurusan')) : ?>is-invalid<?php endif ?>" name="jurusan" <?= old('jurusan') ? 'value="' . old("jurusan") . '"' : '' ?>>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.jurusan')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.jurusan') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0">
                                            <label class="form-label" for="instansi">Nama Instansi </label>
                                            <input type="text" class="form-control <?php if (session('errors.instansi')) : ?>is-invalid<?php endif ?>" name="instansi" <?= old('instansi') ? 'value="' . old("instansi") . '"' : '' ?>>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.instansi')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.instansi') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0 is-filled">
                                            <label class="form-label" for="tgl_masuk">Tanggal Masuk</label>
                                            <input type="date" class="form-control <?php if (session('errors.tgl_masuk')) : ?>is-invalid<?php endif ?>" name="tgl_masuk" <?= old('tgl_masuk') ? 'value="' . old("tgl_masuk") . '"' : '' ?> required>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.tgl_masuk')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.tgl_masuk') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-0 is-filled">
                                            <label class="form-label" for="tgl_selesai">Tanggal Selesai</label>
                                            <input type="date" class="form-control <?php if (session('errors.tgl_selesai')) : ?>is-invalid<?php endif ?>" name="tgl_selesai" <?= old('tgl_selesai') ? 'value="' . old("tgl_selesai") . '"' : '' ?> required>
                                        </div>
                                        <div class="mb-3">
                                            <?php if (session('errors.tgl_selesai')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.tgl_selesai') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 text-sm" for="surat_permohonan">Surat Permohonan Magang (PDF)</label>
                                        <input type="file" accept=".pdf" class="form-control border px-2 <?php if (session('errors.surat_permohonan')) : ?>is-invalid<?php endif ?>" name="surat_permohonan" required>
                                        <div class="mb-3">
                                            <?php if (session('errors.surat_permohonan')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.surat_permohonan') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0 text-sm" for="cv">Curriculum Vitae (PDF)</label>
                                        <input type="file" accept=".pdf" class="form-control border px-2 <?php if (session('errors.cv')) : ?>is-invalid<?php endif ?>" name="cv" required>
                                        <div class="mb-3">
                                            <?php if (session('errors.cv')) : ?>
                                                <small class="text-danger text-xxs ms-1"><?= session('errors.cv') ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label" for="password"><?= lang('Auth.password') ?></label>
                                            <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" autocomplete="off">
                                            <span class="password-toggle-icon"><i class="fa fa-eye-slash font-weight-bold"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label" for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                                            <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" autocomplete="off">
                                            <span class="password-toggle-icon"><i class="fa fa-eye-slash font-weight-bold"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2"><?= lang('Auth.register') ?></button>
                                </div>
                            </form>
                            <hr class="horizontal dark m-0 mb-2">
                            <div class="text-center">
                                <div class="text-sm mb-0 pb-0"><?= lang('Auth.alreadyRegistered') ?></div>
                                <a class="text-sm text-dark text-gradient font-weight-bold" href="<?= url_to('login') ?>"><?= lang('Auth.signIn') ?></a>
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
    $(document).ready(function() {
        function checkInstansi() {
            let val = $('input[name="instansi"]').val().toLowerCase();
            if (val.indexOf('smk') !== -1 || val.indexOf('sekolah') !== -1) {
                $('label[for="nim"]').text('NIS');
            } else {
                $('label[for="nim"]').text('NIM/NIS');
            }
        }
        $('input[name="instansi"]').on('input change', checkInstansi);
        checkInstansi(); // check initial
    });
</script>

<?= $this->endSection() ?>