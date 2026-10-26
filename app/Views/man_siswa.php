<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-body p-3">
            <div class="d-flex justify-content-between align-items-center px-2">
                <h6>Table Siswa</h6>
                <?php if (in_groups('admin')) : ?>
                <div class="d-flex flex-column flex-sm-row gap-2 w-100 w-sm-auto mt-2 mt-sm-0">
                    <a href="/siswa/export/pdf" target="_blank" class="btn btn-icons py-1 px-3 btn-danger w-100 w-sm-auto">
                        <i class="far fa-file-pdf me-1"></i>
                        Export PDF
                    </a>
                    <a href="/siswa/export/excel" target="_blank" class="btn btn-icons py-1 px-3 btn-success w-100 w-sm-auto">
                        <i class="far fa-file-excel me-1"></i>
                        Export Excel
                    </a>
                    <button class="btn btn-icons py-1 px-3 btn-dark w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#mtsiswa">
                        <i class="fas fa-plus me-1"></i>
                        Tambah Siswa
                    </button>
                </div>
                <?php endif ?>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="input-group input-group-outline mb-3">
                        <label for="filsearch" class="form-label">Cari Data (Nama, NIM/NIS, Jurusan)</label>
                        <input type="text" name="filsearch" id="filsearch" class="form-control">
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-stripped" id="tbsiswa">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIM / NIS</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jurusan</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Instansi</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dosen Pembimbing</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tgl Daftar</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Berkas</th>
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
                                        <div class="badge badge-secondary"><?= isSmkSiswa($s) ? 'NIS: ' . $s->nim : 'NIM: ' . $s->nim; ?></div>
                                    </td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->nama; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->kelas; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->jurusan; ?></td>
                                    <td class="text-xs ps-4 font-weight-bold"><?= $s->instansi; ?></td>
                                    
                                    <!-- ==================== KOLOM DOSEN PEMBIMBING (WARNA DISAMAKAN) ==================== -->
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <?php if (!empty($s->dosen_nama)) : ?>
                                            <div class="text-wrap"><?= $s->dosen_nama ?></div>
                                        <?php else : ?>
                                            <span class="badge badge-secondary border border-secondary">Belum ada</span>
                                        <?php endif; ?>
                                    </td>
                                    <!-- ==================== END KOLOM DOSEN ==================== -->
                                    
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <?php 
                                        $isPastTglSelesai = (!empty($s->tgl_selesai) && date('Y-m-d') > $s->tgl_selesai);
                                        $hasLaporan = !empty($s->laporan);
                                        $isSmk = isSmkSiswa($s);

                                        if (($s->lamaran_status == 'selesai' || ($s->lamaran_status == 'accepted' && $isPastTglSelesai)) && ($hasLaporan || $isSmk)) : ?>
                                            <span class="badge badge-info border border-info">Selesai</span>
                                        <?php elseif ($s->lamaran_status == 'accepted' && !$isPastTglSelesai) : ?>
                                            <span class="badge badge-success border border-success">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge badge-secondary border border-secondary">Tidak Aktif</span>
                                            <div class="text-xxs text-secondary mt-1 font-weight-normal" style="font-size: 10px; line-height: 1.2;">Tidak Menyelesaikan Magang</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <?php if ($s->tgl_daftar): ?>
                                            <span class="d-block"><?= date('d/m/Y', strtotime($s->tgl_daftar)) ?></span>
                                            <span class="text-xxs text-secondary"><?= date('H:i', strtotime($s->tgl_daftar)) ?> WIB</span>
                                        <?php else: ?>
                                            <span class="text-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-xs ps-4 font-weight-bold">
                                        <?php if ($s->surat_permohonan) : ?>
                                            <a href="/assets/berkas/<?= $s->surat_permohonan ?>" target="_blank" class="badge badge-info border border-info mb-1" title="Surat Permohonan"><i class="fa fa-file-pdf"></i> Surat</a><br>
                                        <?php endif; ?>
                                        <?php if ($s->cv) : ?>
                                            <a href="/assets/berkas/<?= $s->cv ?>" target="_blank" class="badge badge-info border border-info mb-1" title="CV"><i class="fa fa-file-pdf"></i> CV</a><br>
                                        <?php endif; ?>
                                        <?php if ($s->surat_pernyataan) : ?>
                                            <a href="/assets/berkas/<?= $s->surat_pernyataan ?>" target="_blank" class="badge bg-warning border border-warning text-white mb-1" title="Surat Pernyataan"><i class="fa fa-file-signature"></i> Pernyataan</a><br>
                                        <?php endif; ?>
                                        <?php if (!isSmkSiswa($s) && $s->laporan) : ?>
                                            <a href="/assets/laporan/<?= $s->laporan ?>" target="_blank" class="badge badge-primary border border-primary mb-1" title="Laporan Magang"><i class="fa fa-file-alt"></i> Laporan</a><br>
                                        <?php endif; ?>
                                        <?php if (isCertificateReady($s)) : ?>
                                            <a href="/sertifikat/download/<?= $s->id_siswa_real ?>" target="_blank" class="badge badge-success border border-success mb-1" title="Sertifikat Magang"><i class="fa fa-certificate"></i> Sertifikat</a><br>
                                        <?php endif; ?>
                                        <?php if ($s->tgl_masuk && $s->tgl_selesai) : ?>
                                            <span class="text-secondary text-xxs d-block mt-1">
                                                <i class="fa fa-calendar-alt me-1"></i><?= $s->tgl_masuk ?> s/d <?= $s->tgl_selesai ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
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
                                            data-nik="<?= $s->nik ?? '-' ?>"
                                            data-tempat-lahir="<?= $s->tempat_lahir ?? '-' ?>"
                                            data-tanggal-lahir="<?= $s->tanggal_lahir ?? '-' ?>"
                                            data-jenis-kelamin="<?= $s->jenis_kelamin ?? '-' ?>"
                                            data-no-hp-emergency="<?= $s->no_hp_emergency ?? '-' ?>"
                                            data-foto="<?= $s->foto ?? 'default.jpg' ?>"
                                            data-cv="<?= $s->cv ?? '' ?>"
                                            data-ktp="<?= $s->ktp ?? '' ?>"
                                            data-bpjs="<?= $s->bpjs ?? '' ?>"
                                            data-ktm="<?= $s->ktm ?? '' ?>"
                                            data-takaful="<?= $s->takaful ?? '' ?>"
                                            data-surat-balasan="<?= $s->surat_balasan ?? '' ?>"
                                            data-id-real="<?= $s->id_siswa_real ?? '' ?>"
                                            data-surat-permohonan="<?= $s->surat_permohonan ?? '' ?>"
                                            data-surat-pernyataan="<?= $s->surat_pernyataan ?? '' ?>"
                                            data-tgl-masuk="<?= $s->tgl_masuk ?? '' ?>"
                                            data-tgl-selesai="<?= $s->tgl_selesai ?? '' ?>"
                                            data-tgl-daftar="<?= $s->tgl_daftar ?? '' ?>"
                                            data-bs-toggle="modal" data-bs-target="#mvsiswa"
                                        ><i class="fas fa-eye"></i></button>
                                        <?php if (in_groups('admin')) : ?>
                                        <a href="/siswa/edit/<?= $s->nim ?>" class="badge border border-1 border-dark text-dark"><i class="fas fa-edit"></i></a>
                                        <button class="badge border border-1 border-danger text-danger btn-destroy" data-item="<?= $s->id; ?>"><i class="fas fa-trash"></i></button>
                                        <?php endif ?>
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

<?php if (in_groups('admin')) : ?>
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
                                    <div class="input-group input-group-outline mb-3" data-bs-toggle="tooltip" title="Bisa diisi manual, atau terisi otomatis saat memilih gelombang" data-container="body" data-animation="true">
                                        <label for="nim" class="form-label">NIM</label>
                                        <input type="text" name="nim" id="nim" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                    <div class="input-group input-group-outline mb-3">
                                        <label for="hp" class="form-label">Nomor Hp/Wa</label>
                                        <input type="text" name="hp" id="hp" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
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
                                        <label for="kelas" class="form-label">Jumlah</label>
                                        <input type="text" name="kelas" id="kelas" class="form-control" required>
                                    </div>

                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label" for="tgl_masuk">Tanggal Masuk</label>
                                        <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control" required>
                                    </div>

                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label" for="tgl_selesai">Tanggal Selesai</label>
                                        <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control" required>
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
                                            <small class="text-xs text-danger font-weight-bold d-block mt-n2 mb-3">
                                                *PENTING: Jika pendaftar berkelompok, pastikan nama Instansi <b>sama persis/serempak</b> antar anggota kelompok.
                                            </small>
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
<?php endif ?>
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
                    <div class="col-12 text-center mb-3" id="v-foto-container">
                        <div class="avatar avatar-xxl position-relative">
                            <img src="" id="v-foto" alt="profile_image" class="w-100 border-radius-lg shadow-sm" style="object-fit: cover; height: 120px;">
                        </div>
                    </div>
                    <div class="col-12">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-sm font-weight-bold" width="30%"><span id="v-nim-label">NIM</span></td>
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
                                <td class="text-sm font-weight-bold">Jumlah</td>
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
                            <tr>
                                <td class="text-sm font-weight-bold">Rencana Magang</td>
                                <td class="text-sm">: <span id="v-tgl-masuk"></span> s/d <span id="v-tgl-selesai"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold text-primary">Tgl & Waktu Daftar</td>
                                <td class="text-sm text-primary font-weight-bold">: <span id="v-tgl-daftar"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">NIK</td>
                                <td class="text-sm">: <span id="v-nik"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Tempat, Tanggal Lahir</td>
                                <td class="text-sm">: <span id="v-tempat-lahir"></span>, <span id="v-tanggal-lahir"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Jenis Kelamin</td>
                                <td class="text-sm">: <span id="v-jenis-kelamin"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">No. HP Emergency</td>
                                <td class="text-sm">: <span id="v-hp-emergency"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2"><hr class="my-2"></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File KTP</td>
                                <td class="text-sm">: <span id="v-ktp"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File CV</td>
                                <td class="text-sm">: <span id="v-cv"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Transkrip Nilai / Lainnya</td>
                                <td class="text-sm">: <span id="v-bpjs"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">File KTM</td>
                                <td class="text-sm">: <span id="v-ktm"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Takaful</td>
                                <td class="text-sm">: <span id="v-takaful"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Permohonan</td>
                                <td class="text-sm">: <span id="v-surat-permohonan"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Pernyataan Magang</td>
                                <td class="text-sm">: <span id="v-surat-pernyataan"></span></td>
                            </tr>
                            <tr>
                                <td class="text-sm font-weight-bold">Surat Balasan Magang</td>
                                <td class="text-sm">: <span id="v-surat-balasan"></span></td>
                            </tr>
                            <tr id="upload-balasan-row">
                                <td class="text-sm font-weight-bold">Upload Surat Balasan</td>
                                <td class="text-sm">
                                    <form id="fupload-balasan" enctype="multipart/form-data" class="d-flex gap-2 align-items-center mt-1">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" id="upload-balasan-id">
                                        <div class="input-group input-group-outline w-70" style="margin-bottom:0px;">
                                            <input type="file" name="surat_balasan" accept=".pdf" class="form-control form-control-sm" style="padding: 4px 8px;" required>
                                        </div>
                                        <button type="submit" class="btn btn-sm bg-gradient-dark mb-0 py-1" style="height:32px;">Unggah</button>
                                    </form>
                                </td>
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



        $('#filsearch').on('keyup change input', function() {
            $(this).val() == "" ? $(this).parent().removeClass('is-filled') : $(this).parent().addClass('is-filled');
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

        <?php if (in_groups('admin')) : ?>
        $('#mtsiswa').on('shown.bs.modal', function () {
            if ($('#fasiswa #nim').val().trim() === '') {
                let randomNim = 'c0303' + Math.floor(10000 + Math.random() * 90000);
                $('#fasiswa #nim').val(randomNim);
                $('#fasiswa #nim').parent().addClass('is-filled');
            }
        });

        $('#fasiswa #instansi').on('input change', function() {
            let val = $(this).val().toLowerCase();
            if (val.indexOf('smk') !== -1 || val.indexOf('sekolah') !== -1) {
                $('#fasiswa label[for="nim"]').text('NIS');
            } else {
                $('#fasiswa label[for="nim"]').text('NIM');
            }
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
                        $("#mtsiswa").modal("hide"), $("#fasiswa")[0].reset(), $("#kelas").siblings("label").removeClass("d-none"), location.reload()
                    }) : Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: s.message,
                        showConfirmButton: !0
                    }).then(s => {
                        s.isConfirmed && ($("#mtsiswa").modal("hide"), $("#fasiswa")[0].reset(), $("#kelas").siblings("label").removeClass("d-none"), location.reload())
                    })
                }
            })
        });

        // btn-activate
        $("#tbsiswa tbody").on("click", ".btn-activate", function() {
            let t = $(this).data("item");
            let nama = $(this).data("nama");
            Swal.fire({
                icon: "question",
                title: "Verifikasi Mahasiswa",
                text: "Apakah anda yakin ingin menerima dan mengaktifkan akun " + nama + "?",
                showCancelButton: !0,
                confirmButtonText: "Ya, Aktifkan",
                cancelButtonText: "Batal"
            }).then(e => {
                e.isConfirmed && $.ajax({
                    url: "/siswa/activate/" + t,
                    type: "post",
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
                        })
                    }
                })
            })
        });

        // btn-reject
        $("#tbsiswa tbody").on("click", ".btn-reject", function() {
            let t = $(this).data("item");
            let nama = $(this).data("nama");
            Swal.fire({
                icon: "warning",
                title: "Tolak Mahasiswa",
                text: "Apakah anda yakin ingin menolak dan menghapus pendaftaran " + nama + "?",
                showCancelButton: !0,
                confirmButtonText: "Ya, Tolak",
                cancelButtonText: "Batal"
            }).then(e => {
                e.isConfirmed && $.ajax({
                    url: "/siswa/destroy/" + t,
                    type: "delete",
                    dataType: "json",
                    success: function(t) {
                        t.success ? Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: "Pendaftaran berhasil ditolak dan dihapus",
                            showConfirmButton: !1,
                            timer: 1500
                        }).then(t => {
                            location.reload()
                        }) : Swal.fire({
                            icon: "error",
                            title: "Gagal",
                            text: t.message,
                            showConfirmButton: !0
                        })
                    }
                })
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
        <?php endif ?>

        // mvsiswa
        $("#tbsiswa tbody").on("click", ".btn-view", function() {
            const d = $(this).data();
            let instansiLower = (d.instansi || '').toLowerCase();
            let kelasLower = (d.kelas || '').toLowerCase();
            let isSmk = instansiLower.includes('smk') || instansiLower.includes('sekolah') || ['x', 'xi', 'xii', '10', '11', '12'].includes(kelasLower);
            $("#v-nim-label").text(isSmk ? 'NIS' : 'NIM');
            $("#v-nim").text(d.nim);
            $("#v-nama").text(d.nama);
            $("#v-email").text(d.email);
            $("#v-hp").text(d.hp);
            $("#v-kelas").text(d.kelas);
            $("#v-jurusan").text(d.jurusan);
            $("#v-instansi").text(d.instansi);
            $("#v-angkatan").text(d.angkatan);
            $("#v-alamat").text(d.alamat);
            $("#v-nik").text(d.nik || '-');
            $("#v-tempat-lahir").text(d.tempatLahir || '-');
            $("#v-tanggal-lahir").text(d.tanggalLahir || '-');
            $("#v-jenis-kelamin").text(d.jenisKelamin || '-');
            $("#v-hp-emergency").text(d.noHpEmergency || '-');
            $("#v-tgl-masuk").text(d.tglMasuk || '-');
            $("#v-tgl-selesai").text(d.tglSelesai || '-');
            // Format tgl_daftar nicely
            if (d.tglDaftar) {
                var dt = new Date(d.tglDaftar);
                var formatted = dt.toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'}) + ', ' +
                                dt.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) + ' WIB';
                $("#v-tgl-daftar").text(formatted);
            } else {
                $("#v-tgl-daftar").text('-');
            }

            const baseUrl = "<?= base_url('assets/berkas') ?>/";
            
            if (d.ktp) $("#v-ktp").html(`<a href="${baseUrl}${d.ktp}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-ktp").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            if (d.cv) $("#v-cv").html(`<a href="${baseUrl}${d.cv}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-cv").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            if (d.bpjs) $("#v-bpjs").html(`<a href="${baseUrl}${d.bpjs}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-bpjs").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            if (d.ktm) $("#v-ktm").html(`<a href="${baseUrl}${d.ktm}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-ktm").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            if (d.takaful) $("#v-takaful").html(`<a href="${baseUrl}${d.takaful}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-takaful").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            if (d.suratPermohonan) $("#v-surat-permohonan").html(`<a href="${baseUrl}${d.suratPermohonan}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-surat-permohonan").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            if (d.suratPernyataan) $("#v-surat-pernyataan").html(`<a href="${baseUrl}${d.suratPernyataan}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-surat-pernyataan").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            if (d.suratBalasan) $("#v-surat-balasan").html(`<a href="${baseUrl}${d.suratBalasan}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
            else $("#v-surat-balasan").html(`<span class="text-secondary text-xs">Belum diunggah</span>`);

            $("#upload-balasan-id").val(d.idReal);
            const foto = $(this).attr("data-foto");
            if (foto && foto != 'default.jpg' && foto != '') {
                $("#v-foto-container").show();
                $("#v-foto").attr("src", "<?= base_url('assets/img/foto_siswa') ?>/" + foto);
            } else {
                $("#v-foto-container").hide();
            }
        });

        $("#fupload-balasan").on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            $.ajax({
                url: "<?= base_url('siswa/upload_surat_balasan') ?>",
                type: "post",
                data: formData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(s) {
                    // Update CSRF token
                    if (s.csrf_hash) {
                        $('meta[name="<?= csrf_header() ?>"]').attr('content', s.csrf_hash);
                        $('input[name="<?= csrf_token() ?>"]').val(s.csrf_hash);
                    }
                    if (s.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: s.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        const baseUrl = "<?= base_url('assets/berkas') ?>/";
                        $("#v-surat-balasan").html(`<a href="${baseUrl}${s.surat_balasan}" target="_blank" class="badge bg-gradient-info text-xxs">Lihat File</a>`);
                        const targetId = $("#upload-balasan-id").val();
                        $(`.btn-view[data-id-real="${targetId}"]`).attr('data-surat-balasan', s.surat_balasan);
                        $(`.btn-view[data-id-real="${targetId}"]`).data('suratBalasan', s.surat_balasan);
                        $("#fupload-balasan")[0].reset();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal",
                            text: s.message,
                            showConfirmButton: true
                        });
                    }
                },
                error: function(xhr) {
                    // Try to fetch new CSRF hash from response headers if present
                    const headerToken = xhr.getResponseHeader('<?= csrf_header() ?>');
                    if (headerToken) {
                        $('meta[name="<?= csrf_header() ?>"]').attr('content', headerToken);
                        $('input[name="<?= csrf_token() ?>"]').val(headerToken);
                    }
                    let errMsg = "Terjadi kesalahan saat mengunggah file.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: errMsg,
                        showConfirmButton: true
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>