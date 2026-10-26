<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>

<?php $isInstansi = ($role_type === 'instansi'); ?>

<div class="row mb-5">
    <div class="col-12">
        <div class="card card-body p-3">
            <div class="d-flex justify-content-between align-items-center px-2">
                <h6>Table Pengelola</h6>
                <button class="btn btn-sm btn-dark" id="btnAddPengelola"
                    data-bs-toggle="modal" data-bs-target="#mtPengelola">
                    <i class="fas fa-plus me-1"></i> Pengelola
                </button>
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
                <table class="table table-hover table-stripped" id="tbPengelola">
                    <thead>
                        <tr>
                            <th>No</th>
                            <?php if ($isInstansi): ?>
                                <th>Nama Instansi</th>
                                <th>Jenis</th>
                                <th>Alamat</th>
                            <?php else: ?>
                                <th>Nama Pembimbing</th>
                            <?php endif; ?>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No. HP</th>
                            <th>Group</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($user as $u): ?>
                            <tr>
                                <td class="text-xs ps-4 font-weight-bold"><?= $i++ ?></td>

                                <?php if ($isInstansi): ?>
                                    <td class="text-xs ps-4 font-weight-bold"><?= esc($u->nama_instansi ?? '-') ?></td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <span class="badge bg-info"><?= esc($u->jenis_instansi ?? '-') ?></span>
                                    </td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <div><?= esc($u->alamat ?? '-') ?></div>
                                        <div class="text-secondary" style="font-size: 10px;">
                                            <?= esc($u->kecamatan ?? '') ?>
                                            <?= !empty($u->kabupaten_kota) ? ', ' . esc($u->kabupaten_kota) : '' ?>
                                            <?= !empty($u->provinsi) ? ', ' . esc($u->provinsi) : '' ?>
                                        </div>
                                    </td>
                                <?php else: ?>
                                    <td class="text-xs ps-4 font-weight-bold"><?= esc($u->nama ?? '-') ?></td>
                                <?php endif; ?>

                                <td class="text-xs ps-4 font-weight-bold"><?= esc($u->username) ?></td>
                                <td class="text-xs ps-4 font-weight-bold">
                                    <a class="opacity-7" href="mailto:<?= esc($u->email, 'attr') ?>">
                                        <?= esc($u->email) ?>
                                        <i class="ps-1 far fa-envelope"></i>
                                    </a>
                                </td>
                                <td class="text-xs ps-4 font-weight-bold"><?= esc($u->no_hp ?? '-') ?></td>
                                <td class="text-xs ps-4 font-weight-bold"><?= esc($role_type) ?></td>
                                <td class="text-xs ps-4 font-weight-bold">
                                    <button type="button"
                                        class="badge border border-1 border-danger text-danger btn-destroy"
                                        data-item="<?= esc($u->id, 'attr') ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <button type="button"
                                        class="badge border border-1 border-dark text-dark btn-edit"
                                        data-item="<?= esc($u->id, 'attr') ?>"
                                        data-nama="<?= esc($u->nama ?? '', 'attr') ?>"
                                        data-nama-instansi="<?= esc($u->nama_instansi ?? '', 'attr') ?>"
                                        data-jenis-instansi="<?= esc($u->jenis_instansi ?? '', 'attr') ?>"
                                        data-alamat="<?= esc($u->alamat ?? '', 'attr') ?>"
                                        data-provinsi="<?= esc($u->provinsi ?? '', 'attr') ?>"
                                        data-kabupaten="<?= esc($u->kabupaten_kota ?? '', 'attr') ?>"
                                        data-kecamatan="<?= esc($u->kecamatan ?? '', 'attr') ?>"
                                        data-username="<?= esc($u->username ?? '', 'attr') ?>"
                                        data-email="<?= esc($u->email ?? '', 'attr') ?>"
                                        data-hp="<?= esc($u->no_hp ?? '', 'attr') ?>"
                                        data-role="<?= esc($role_type, 'attr') ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Pengelola -->
<div class="modal fade" id="mtPengelola" tabindex="-1"
    data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="mtPengelola" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="font-weight-bolder mb-0" id="modalTitle">Tambah Pengelola</h5>
                                <p class="text-sm text-secondary mb-0" id="modalSubTitle">Tambah data Pengelola magang</p>
                            </div>
                            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form id="fauser" data-action="" enctype="multipart/form-data">
                            <input type="hidden" name="role" id="formRole" value="<?= esc($role_type ?? 'instansi') ?>">
                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-12 col-md-6">
                                    <!-- Field yang HANYA muncul untuk INSTANSI -->
                                    <?php if ($isInstansi): ?>
                                        <div class="input-group input-group-outline mb-3">
                                            <label for="nama_instansi" class="form-label">Nama Instansi</label>
                                            <input type="text" name="nama_instansi" id="nama_instansi" class="form-control">
                                        </div>

                                        <div class="input-group input-group-outline mb-3 is-filled">
                                            <label for="jenis_instansi" class="form-label">Jenis Instansi</label>
                                            <select name="jenis_instansi" id="jenis_instansi" class="form-control">
                                                <option value="">-- Pilih Jenis --</option>
                                                <option value="Perguruan tinggi">Perguruan Tinggi</option>
                                                <option value="Sekolah">Sekolah</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Field yang MUNCUL untuk KEDUA role -->
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" name="username" id="username" class="form-control">
                                    </div>

                                    <div class="input-group input-group-outline mb-3" id="container-password">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password"
                                            class="form-control" autocomplete="new-password">
                                        <span class="password-toggle-icon">
                                            <i class="fa fa-eye-slash font-weight-bold"></i>
                                        </span>
                                    </div>

                                    <div class="input-group input-group-outline mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control">
                                    </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-12 col-md-6">
                                    <!-- Field yang HANYA muncul untuk PEMBIMBING LAPANGAN -->
                                    <?php if (!$isInstansi): ?>
                                        <div class="input-group input-group-outline mb-3">
                                            <label for="nama" class="form-label">Nama Pembimbing</label>
                                            <input type="text" name="nama" id="nama" class="form-control">
                                        </div>
                                    <?php endif; ?>

                                    <!-- Field yang HANYA muncul untuk INSTANSI -->
                                    <?php if ($isInstansi): ?>
                                        <div class="input-group input-group-outline mb-3">
                                            <label for="alamat" class="form-label">Alamat Lengkap</label>
                                            <input type="text" name="alamat" id="alamat" class="form-control">
                                        </div>

                                        <div class="input-group input-group-outline mb-3">
                                            <label for="provinsi" class="form-label">Provinsi</label>
                                            <input type="text" name="provinsi" id="provinsi" class="form-control">
                                        </div>

                                        <div class="input-group input-group-outline mb-3">
                                            <label for="kabupaten_kota" class="form-label">Kabupaten/Kota</label>
                                            <input type="text" name="kabupaten_kota" id="kabupaten_kota" class="form-control">
                                        </div>

                                        <div class="input-group input-group-outline mb-3">
                                            <label for="kecamatan" class="form-label">Kecamatan</label>
                                            <input type="text" name="kecamatan" id="kecamatan" class="form-control">
                                        </div>
                                    <?php endif; ?>

                                    <div class="input-group input-group-outline mb-3">
                                        <label for="hp" class="form-label">Nomor Hp/Wa</label>
                                        <input type="text" name="hp" id="hp" class="form-control"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center gap-3 d-flex align-items-center justify-content-end mt-4">
                                <button type="button" class="btn btn-round bg-gradient-light"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-round bg-gradient-info">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('topsc'); ?>
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="/assets/js/plugins/dataTables.bootstrap5.min.js"></script>
<script src="/assets/js/plugins/sweetalert.min.js"></script>

<script>
$(document).ready(function () {
    const form = $('#fauser');
    const isInstansi = <?= $isInstansi ? 'true' : 'false' ?>;
    const roleType = '<?= esc($role_type ?? 'instansi') ?>';

    // Handle floating labels
    form.on('focus', '.form-control', function () {
        $(this).parent().addClass('is-focused');
    }).on('blur', '.form-control', function () {
        $(this).parent().removeClass('is-focused');
        if ($(this).val() !== '') {
            $(this).parent().addClass('is-filled');
        } else {
            $(this).parent().removeClass('is-filled');
        }
    }).on('keyup change input', '.form-control', function () {
        if ($(this).val() !== '') {
            $(this).parent().addClass('is-filled');
        } else if (!$(this).is(':focus')) {
            $(this).parent().removeClass('is-filled');
        }
    });

    const tbPengelola = $('#tbPengelola').DataTable({
        dom: '<"row"<"col-12"tr>><"row mt-2 px-3"<"col-12 col-md-6"i><"col-12 col-md-6"p>>',
        pageLength: 10,
        language: {
            paginate: {
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });

    $('#filsearch').on('keyup change input', function () {
        $(this).val() === ''
            ? $(this).parent().removeClass('is-filled')
            : $(this).parent().addClass('is-filled');

        tbPengelola.search($(this).val()).draw();
    });

    $('#btnAddPengelola').on('click', function () {
        if (isInstansi) {
            $('#modalTitle').text('Tambah Instansi');
            $('#modalSubTitle').text('Tambah data Instansi Pendidikan');
        } else {
            $('#modalTitle').text('Tambah Pembimbing');
            $('#modalSubTitle').text('Tambah data Pembimbing Lapangan');
        }

        form[0].reset();
        form.find('input[name="item"], input[name="old_email"]').remove();
        $('#formRole').val(roleType);

        form.find('.form-control').each(function () {
            $(this).parent().removeClass('is-filled is-focused');
        });

        form.find('select[name="jenis_instansi"]').parent().addClass('is-filled');
        form.find('input[name="username"]').attr('readonly', false).parent().removeClass('bg-gray-300');

        $('#container-password').removeClass('d-none');
        $('#container-password label').text('Password');
        form.data('action', 'store');
    });

    form.on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: form.data('action') === 'store'
                ? '/user/store'
                : form.data('action') === 'update'
                    ? '/user/update'
                    : '',
            type: 'post',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function () {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message,
                        showConfirmButton: true
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan saat menyimpan data.'
                });
            }
        });
    });

    $('#tbPengelola tbody').on('click', '.btn-edit', function () {
        form.data('action', 'update');

        const tombol = $(this);
        const item = tombol.data('item');
        const roleData = tombol.attr('data-role') || '';

        // AMBIL DATA LANGSUNG DARI ATRIBUT DATA-* (TIDAK LAGI MENGHITUNG URUTAN TD)
        const nama = tombol.attr('data-nama') || '';
        const namaInstansi = tombol.attr('data-nama-instansi') || '';
        const jenisInstansi = tombol.attr('data-jenis-instansi') || '';
        const alamat = tombol.attr('data-alamat') || '';
        const provinsi = tombol.attr('data-provinsi') || '';
        const kabupaten = tombol.attr('data-kabupaten') || '';
        const kecamatan = tombol.attr('data-kecamatan') || '';
        const username = tombol.attr('data-username') || '';
        const email = tombol.attr('data-email') || '';
        const hp = tombol.attr('data-hp') || '';

        // Sesuaikan judul modal
        if (isInstansi) {
            $('#modalTitle').text('Edit Instansi');
            $('#modalSubTitle').text('Edit data Instansi Pendidikan');
        } else {
            $('#modalTitle').text('Edit Pembimbing');
            $('#modalSubTitle').text('Edit data Pembimbing Lapangan');
        }

        $('#mtPengelola').modal('show');

        // Isi form sesuai role
        if (isInstansi) {
            form.find('input[name="nama_instansi"]').val(namaInstansi).parent().addClass('is-filled');
            form.find('select[name="jenis_instansi"]').val(jenisInstansi).parent().addClass('is-filled');
            form.find('input[name="alamat"]').val(alamat).parent().addClass('is-filled');
            form.find('input[name="provinsi"]').val(provinsi).parent().addClass('is-filled');
            form.find('input[name="kabupaten_kota"]').val(kabupaten).parent().addClass('is-filled');
            form.find('input[name="kecamatan"]').val(kecamatan).parent().addClass('is-filled');
        } else {
            form.find('input[name="nama"]').val(nama).parent().addClass('is-filled');
        }

        form.find('input[name="username"]').val(username).attr('readonly', true).parent().addClass('is-filled bg-gray-300');
        form.find('input[name="hp"]').val(hp).parent().addClass('is-filled');
        form.find('input[name="email"]').val(email).parent().addClass('is-filled');

        form.find('input[name="password"]').val('');
        $('#container-password').removeClass('d-none');
        $('#container-password label').text('Password (Biarkan kosong jika tidak diubah)');

        form.find('input[name="item"], input[name="old_email"]').remove();
        $('#formRole').val(roleData || roleType);

        $('<input>', {
            type: 'hidden',
            name: 'item',
            value: item
        }).appendTo(form);

        $('<input>', {
            type: 'hidden',
            name: 'old_email',
            value: email
        }).appendTo(form);
    });

    $('#tbPengelola tbody').on('click', '.btn-destroy', function () {
        const item = $(this).data('item');

        Swal.fire({
            icon: 'warning',
            title: 'Hapus Data',
            text: 'Apakah anda yakin ingin menghapus data ini?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus'
        }).then(function (result) {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '/user/destroy/' + item,
                type: 'delete',
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function () {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Tidak dapat menghapus data.'
                    });
                }
            });
        });
    });
});
</script>

<?= $this->endSection(); ?>