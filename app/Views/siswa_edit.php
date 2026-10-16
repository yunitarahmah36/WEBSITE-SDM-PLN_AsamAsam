<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <span class="h5">Edit Data Siswa</span><br>
                Edit data siswa magang
            </div>
            <div class="card-body">
                <form role="form text-left" id="fesiswa" enctype="multipart/form-data">
                    <input type="hidden" name="items" value="<?= $siswa->id ?>">
                    <input type="hidden" name="old_email" value="<?= $siswa->email ?>">

                    <!-- ==================== BARIS 1: NIM & NAMA ==================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3 is-filled" data-bs-toggle="tooltip" title="<?= isSmkSiswa($siswa) ? 'NIS' : 'NIM' ?> Tidak dapat diedit" data-container="body" data-animation="true">
                                <label for="nim" class="form-label"><?= isSmkSiswa($siswa) ? 'NIS' : 'NIM' ?></label>
                                <input type="text" readonly name="nim" id="nim" class="form-control" value="<?= $siswa->nim ?>" readonly required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control" value="<?= $siswa->nama ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== BARIS 2: EMAIL & KELAS ==================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="<?= $siswa->email ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3 is-filled">
                                <label for="kelas" class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="kelas" class="form-control" value="<?= $siswa->kelas ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== BARIS 3: HP & TANGGAL MASUK ==================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label for="hp" class="form-label">Nomor Hp/Wa</label>
                                <input type="text" name="hp" id="hp" class="form-control" value="<?= $siswa->no_hp ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3 is-filled">
                                <label class="form-label" for="tgl_masuk">Tanggal Masuk</label>
                                <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control" value="<?= $siswa->tgl_masuk ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== BARIS 4: PASSWORD & TANGGAL SELESAI ==================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3">
                                <label for="password" class="form-label">Password (Biarkan kosong jika tidak diubah)</label>
                                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3 is-filled">
                                <label class="form-label" for="tgl_selesai">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" value="<?= $siswa->tgl_selesai ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== BARIS 5: JURUSAN & INSTANSI ==================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3 <?= !empty($siswa->jurusan) ? 'is-filled' : '' ?>">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <input type="text" name="jurusan" id="jurusan" class="form-control" value="<?= $siswa->jurusan ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-outline mb-3 <?= !empty($siswa->instansi) ? 'is-filled' : '' ?>">
                                <label for="instansi" class="form-label">Nama Instansi (Asal Sekolah/PT)</label>
                                <input type="text" name="instansi" id="instansi" class="form-control" value="<?= $siswa->instansi ?>" required>
                            </div>
                            <small class="text-xs text-danger font-weight-bold d-block mt-n2 mb-3">
                                *PENTING: Jika siswa berkelompok, pastikan nama Instansi diketik <b>sama persis/serempak</b> (misal: "Kelompok A - Poliban").
                            </small>
                        </div>
                    </div>

                    <!-- ==================== ALAMAT ==================== -->
                    <div class="input-group input-group-outline mb-3 <?= !empty($siswa->alamat) ? 'is-filled' : '' ?>">
                        <textarea name="alamat" id="alamat" rows="2" class="form-control" placeholder="Alamat Lengkap" required><?= $siswa->alamat ?></textarea>
                    </div>

                    <!-- ==================== SURAT BALASAN ==================== -->
                    <div class="mb-3">
                        <label class="form-label mb-0 text-sm" for="surat_balasan">Surat Balasan Magang (PDF)</label>
                        <div class="input-group input-group-outline">
                            <input type="file" name="surat_balasan" id="surat_balasan" class="form-control" accept=".pdf">
                        </div>
                        <?php if (!empty($siswa->surat_balasan)) : ?>
                            <small class="text-xs text-success mt-1 d-block">
                                Surat balasan saat ini: <a href="/assets/berkas/<?= $siswa->surat_balasan ?>" target="_blank" class="text-info text-decoration-underline">Lihat File</a>
                            </small>
                        <?php else: ?>
                            <small class="text-xs text-secondary mt-1 d-block">Belum ada surat balasan yang diunggah.</small>
                        <?php endif; ?>
                    </div>

                    <!-- ==================== TOMBOL ==================== -->
                    <div class="text-center gap-3 d-flex align-items-center justify-content-end mt-4">
                        <a href="/man/siswa" class="btn btn-round bg-gradient-light">Batal</a>
                        <button type="submit" class="btn btn-round bg-gradient-dark">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>


<?= $this->section('topsc'); ?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<?= $this->endSection(); ?>


<?= $this->section('bottomsc'); ?>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="/assets/js/plugins/datatables.js"></script>
<script src="/assets/js/plugins/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        const BASE_URL = "<?= base_url() ?>";
        const angkatan_json = JSON.parse('<?= $angkatan_json ?>');

        $('#fesiswa #kelas').on('change', function() {
            if ($(this).val() != "") {
                $(this).parent().addClass('is-filled');
            } else {
                $(this).parent().removeClass('is-filled');
            }
        });

        $("#fesiswa").on('submit', function(s) {
            s.preventDefault();
            $.ajax({
                url: "/siswa/update",
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
                        showConfirmButton: false,
                        timer: 1500
                    }).then(s => {
                        window.location.href = '/man/siswa'
                    }) : Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: s.message,
                        showConfirmButton: true
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>