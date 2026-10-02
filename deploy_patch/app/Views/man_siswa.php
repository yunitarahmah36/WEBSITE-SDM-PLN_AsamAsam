<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-body p-3">
            <div class="d-flex justify-content-between align-items-center px-2">
                <h6>Table Siswa</h6>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto mt-2 mt-sm-0">
                    <button class="btn btn-icons py-1 px-3 btn-success w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#addexcel">
                        <i class="far fa-file-excel me-1"></i>
                        Siswa
                    </button>
                    <button class="btn btn-icons py-1 px-3 btn-dark w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#mtsiswa">
                        <i class="fas fa-plus me-1"></i>
                        Siswa
                    </button>
                </div>
            </div>
            <!-- option fron $angkatan_json -->
            <div class="row mt-3">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-outline mb-3">
                        <label for="filangkatan" class="form-label">Gelombang</label>
                        <select name="filangkatan" id="filangkatan" class="form-control">
                            <option value=""></option>
                            <?php foreach ($angkatan as $a) : ?>
                                <option value="<?= $a->nama ?>"><?= $a->nama ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-outline mb-3">
                        <label for="filjurusan" class="form-label">Jurusan</label>
                        <input type="text" name="filjurusan" id="filjurusan" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-outline mb-3">
                        <label for="filsearch" class="form-label">Cari Data</label>
                        <input type="text" name="filsearch" id="filsearch" class="form-control">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-stripped" id="tbsiswa">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIM</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kelas</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jurusan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Instansi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Angkatan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1 ?>
                        <?php foreach ($siswa as $s) : ?>
                            <?php if (in_array('siswa', $s->getRoles())) : ?>
                                <tr>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $no++; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <div class="badge badge-secondary"><?= $s->nim; ?></div>
                                    </td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->nama; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->kelas; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->jurusan; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->instansi; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->angkatan; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <button class="badge border border-1 border-info text-info btn-view" 
                                            data-nim="<?= $s->nim ?>" 
                                            data-nama="<?= $s->nama ?>" 
                                            data-email="<?= $s->email ?>"
                                            data-hp="<?= $s->no_hp ?>"
                                            data-kelas="<?= $s->kelas ?>" 
                                            data-jurusan="<?= $s->jurusan ?>" 
                                            data-instansi="<?= $s->instansi ?>" 
                                            data-angkatan="<?= $s->angkatan ?>" 
                                            data-alamat="<?= $s->alamat ?>"
                                            data-foto="<?= $s->foto ?? 'default.jpg' ?>"
                                            data-bs-toggle="modal" data-bs-target="#mvsiswa"
                                        ><i class="fas fa-eye"></i></button>
                                        <a href="/siswa/edit/<?= $s->nim ?>" class="badge border border-1 border-dark text-dark"><i class="fas fa-edit"></i></a>
                                        <button class="badge border border-1 border-danger text-danger btn-destroy" data-item="<?= $s->id; ?>"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endif ?>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addexcel" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="addexcel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0">
                        <span class="h5">Tambah Siswa Excel</span><br>
                        Tambah data siswa magang dengan file excel
                    </div>
                    <div class="card-body bg-white">
                        <form method="post" id="formExcel" enctype="multipart/form-data">
                            <div class="alert alert-warning text-white">
                                <span class="text-white"><strong>Perhatian!</strong></span> <br>
                                <span class="text-sm">Pastikan file excel yang anda upload sesuai dengan format yang telah disediakan. <a href="/assets/excel/dumy_siswa.xlsx" class="text-info">Download Format Excel</a></span>
                            </div>

                            <div class="input-group input-group-outline mb-3">
                                <input type="file" name="excel" id="excel" class="form-control" accept=".xlsx,.xls" required>
                            </div>

                            <div class="text-center gap-2 d-flex align-items-center justify-content-end mt-4">
                                <button type="button" class="btn btn-round btn-sm bg-gradient-secondary shadow" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-round btn-sm bg-gradient-dark" required>Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal Tambah Siswa -->
<div class="modal fade" id="mtsiswa" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="mtsiswa" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0">
                        <span class="h5">Tambah Siswa</span><br>
                        Tambah data siswa magang
                    </div>
                    <div class="card-body bg-white">
                        <form role="form text-left" id="fasiswa" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group input-group-outline mb-3" data-bs-toggle="tooltip" title="NIM siswa akan terisi otomatis ketika memilih angkatan" data-container="body" data-animation="true">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" name="nim" id="nim" class="form-control" readonly required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="hp" class="form-label">Nomor Hp/Wa</label>
                                        <input type="number" name="hp" id="hp" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                        <span class="password-toggle-icon"><i class="fa fa-eye-slash font-weight-bold"></i></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" name="nama" id="nama" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="kelas" class="form-label">Kelas</label>
                                        <input type="text" name="kelas" id="kelas" class="form-control" required>
                                    </div>

                                    <input type="hidden" name="angkatan" id="angval">

                                    <div class="input-group input-group-outline mb-3">
                                        <label for="angkatan" class="form-label">Gelombang</label>
                                        <input type="text" name="autoang" id="angkatan" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="input-group input-group-outline mb-3">
                                                <label for="jurusan" class="form-label">Jurusan</label>
                                                <input type="text" name="jurusan" id="jurusan" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="input-group input-group-outline mb-3">
                                                <label for="instansi" class="form-label">Nama Instansi (Asal Sekolah/PT)</label>
                                                <input type="text" name="instansi" id="instansi" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <textarea name="alamat" id="alamat" rows="2" class="form-control" placeholder="Alamat Lengkap" required></textarea>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center gap-3 d-flex align-items-center justify-content-end mt-4">
                                <button type="button" class="btn btn-round bg-gradient-secondary shadow" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-round bg-gradient-dark" required>Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
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
                    <div class="col-12 text-center mb-3">
                        <div class="avatar avatar-xxl position-relative">
                            <img src="<?= base_url('assets/img/default-avatar.svg') ?>" id="v-foto" alt="profile_image" class="w-100 border-radius-lg shadow-sm" style="object-fit: cover; height: 120px;">
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-sm font-weight-bold" width="30%">NIM</td>
                                <td class="text-sm">: <span id="v-nim"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Nama</td>
                                <td class="text-sm">: <span id="v-nama" class="text-wrap"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Email</td>
                                <td class="text-sm">: <span id="v-email"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">No. HP</td>
                                <td class="text-sm">: <span id="v-hp"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Kelas</td>
                                <td class="text-sm">: <span id="v-kelas"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jurusan</td>
                                <td class="text-sm">: <span id="v-jurusan" class="text-wrap"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Instansi</td>
                                <td class="text-sm">: <span id="v-instansi" class="text-wrap"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Angkatan</td>
                                <td class="text-sm">: <span id="v-angkatan"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Alamat</td>
                                <td class="text-sm" style="white-space: normal;">: <span id="v-alamat" class="text-wrap"></span></td>
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


<?= $this->section('topsc'); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
<?= $this->endSection(); ?>


<?= $this->section('bottomsc'); ?>
<script src="/assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="/assets/js/plugins/dataTables.bootstrap5.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="/assets/js/plugins/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        const BASE_URL = "<?= base_url() ?>";
        const angkatan_json = JSON.parse('<?= $angkatan_json ?>');

        // tbsiswa
        var tbsiswa = $('#tbsiswa').DataTable({
            dom: '<"row"<"col-12"tr>><"row mt-2 px-3"<"col-12 col-md-6"i><"col-12 col-md-6"p>>',
            pageLength: 10,
            language: {
                paginate: {
                    next: '<i class="fas fa-angle-right"></i>',
                    previous: '<i class="fas fa-angle-left"></i>'
                }
            },
        });

        $('#filjurusan').on('keyup', function() {
            $(this).val() == "" ? $(this).parent().removeClass('is-filled') : $(this).parent().addClass('is-filled');
            tbsiswa.columns(4).search($(this).val()).draw();
        });

        $('#filangkatan').on('change', function() {
            $(this).val() == "" ? $(this).parent().removeClass('is-filled') : $(this).parent().addClass('is-filled');
            tbsiswa.columns(6).search($(this).val()).draw();
        });

        $('#filsearch').on('keyup', function() {
            tbsiswa.search($(this).val()).draw();
        });

        function cekNim(nim) {
            $.ajax({
                url: "/siswa/ceknim",
                type: "POST",
                data: {
                    nim: nim
                },
                dataType: "JSON",
                success: function(data) {
                    return data.assigned;
                }
            });
        }

        $("#angkatan").autocomplete({
            appendTo: "#fasiswa",
            source: angkatan_json,
            minLength: 0,
            select: function(event, ui) {
                $('#fasiswa #angval').val(ui.item.no);
                let nim = Math.floor(1000000 + Math.random() * 9999999);
                if (cekNim(nim)) {
                    while (cekNim(nim)) {
                        nim = Math.floor(1000000 + Math.random() * 9999999);
                    }
                    $('#fasiswa #nim').val(nim);
                    $('#fasiswa #nim').parent().addClass('is-filled');
                } else {
                    $('#fasiswa #nim').val(nim);
                    $('#fasiswa #nim').parent().addClass('is-filled');
                }
            },

        });

        $('#fasiswa #kelas').on('change', function() {
            if ($(this).val() != "") {
                $(this).parent().addClass('is-filled');
            } else {
                $(this).parent().removeClass('is-filled');
            }
        });

        $("#formExcel").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "/siswa/save/excel",
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: data.message,
                            showConfirmButton: !1,
                            timer: 1500
                        }).then(s => {
                            $("#addexcel").modal("hide"), $("#formExcel")[0].reset(), location.reload()
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal",
                            text: data.message,
                            showConfirmButton: !0
                        }).then(s => {
                            s.isConfirmed && ($("#addexcel").modal("hide"), $("#formExcel")[0].reset(), location.reload())
                        });
                    }
                }
            });
        });

        // fasiswa submit
        $("#fasiswa").on("submit", function(s) {
            s.preventDefault(), $.ajax({
                url: "/siswa/store",
                type: "post",
                data: new FormData(this),
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(s) {
                    s.success ? Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: s.message,
                        showConfirmButton: !1,
                        timer: 1500
                    }).then(s => {
                        $("#mtsiswa").modal("hide"), $("#fasiswa")[0].reset(), $("#kelas").siblings("label").removeClass("d-none"), $("#angkatan").siblings("label").removeClass("d-none"), location.reload()
                    }) : Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: s.message,
                        showConfirmButton: !0
                    }).then(s => {
                        s.isConfirmed && ($("#mtsiswa").modal("hide"), $("#fasiswa")[0].reset(), $("#kelas").siblings("label").removeClass("d-none"), $("#angkatan").siblings("label").removeClass("d-none"), location.reload())
                    })
                }
            })
        });

        // tbsiswa btn-destroy
        $("#tbsiswa tbody").on("click", ".btn-destroy", function() {
            let t = $(this).data("item");
            Swal.fire({
                icon: "warning",
                title: "Hapus Data",
                text: "Apakah anda yakin ingin menghapus data ini?",
                showCancelButton: !0,
                confirmButtonText: "Ya, Hapus"
            }).then(e => {
                e.isConfirmed && $.ajax({
                    url: "/siswa/destroy/" + t,
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
            });
        });

        // mvsiswa
        $("#tbsiswa tbody").on("click", ".btn-view", function() {
            const d = $(this).data();
            $("#v-nim").text(d.nim);
            $("#v-nama").text(d.nama);
            $("#v-email").text(d.email);
            $("#v-hp").text(d.hp);
            $("#v-kelas").text(d.kelas);
            $("#v-jurusan").text(d.jurusan);
            $("#v-instansi").text(d.instansi);
            $("#v-angkatan").text(d.angkatan);
            $("#v-alamat").text(d.alamat);
            const foto = $(this).attr("data-foto");
            if (foto && foto != 'default.jpg' && foto != '') {
                $("#v-foto").attr("src", "<?= base_url('assets/img/foto_siswa') ?>/" + foto);
            } else {
                $("#v-foto").attr("src", "<?= base_url('assets/img/default-avatar.svg') ?>");
            }
        });
    });
</script>
<?= $this->endSection(); ?>