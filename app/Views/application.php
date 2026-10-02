<?= $this->extend('main'); ?>
<?= $this->section('topsc'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }

        .select2-container {
            width: 100% !important;
            z-index: 9999 !important;
        }
    </style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>



<div class="row">
    <div class="col-12">
        <div class="card card-body py-3 px-4">
            <?php if (in_groups('admin') || in_groups('pembimbing')) : ?>
                <div class="d-flex justify-content-between align-items-center px-2">
                    <div>
                        <span class="h5 mb-0 pb-0">Surat Magang & Daftar Pendaftaran Siswa</span> <br>
                        Daftar berikut merupakan Lamaran siswa yang telah mendaftar di tempat magang.
                    </div>
                    <div>
                        <button class="btn btn-sm btn-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalTemplateSurat">
                            <i class="fas fa-upload me-1"></i> Upload Template Surat
                        </button>
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
                    <table class="table table-hover table-stripped" id="tbapplications">
                        <thead>
                            <tr>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">NIM / NIS</th>
                                <th class="ps-3 text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Orang</th>
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
                                    <td class="ps-3 text-xs font-weight-bold"><?= $no++ ?></td>
                                    <td class="ps-3 text-xs font-weight-bold">
                                        <?php if ($application->laporan == null && (date('Y-m-d') > $application->tgl_selesai)) : ?>
                                            <span class="badge badge-warning border border-warning">unfinish</span>
                                        <?php else : ?>
                                            <?= genBadgeStatusApplication($application->status) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold">
                                        <div class="badge badge-secondary"><?= $application->nim ?></div>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold"><?= $application->kelas ?></td>
                                    <td class="ps-3 text-xs font-weight-bold"><?= $application->nama ?></td>
                                    <td class="ps-3 text-xs font-weight-bold"><?= isVerifiedInstansi($application->id_tempat) ?><span class="iname"><?= $instansi ?></span></td>
                                    <td class="ps-3 text-xs font-weight-bold"><?= $application->nama_dosen ?: '<span class="text-secondary opacity-6">Belum ada</span>' ?></td>
                                    <td class="ps-3 text-xs font-weight-bold">
                                        <?php if (isset($application->surat_pernyataan) && $application->surat_pernyataan) : ?>
                                            <a href="/assets/berkas/<?= $application->surat_pernyataan ?>" target="_blank" class="badge badge-success border border-success">UNDUH</a>
                                        <?php else : ?>
                                            <span class="badge badge-light text-secondary border">BELUM ADA</span>
                                        <?php endif ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold">
                                        <?php if ($application->laporan) : ?>
                                            <a href="/assets/laporan/<?= $application->laporan ?>" target="_blank" class="badge badge-info border border-info">LIHAT</a>
                                        <?php else : ?>
                                            <a href="#" class="badge badge-dark border border-dark" style="cursor: not-allowed !important; pointer-events: all !important;">BELUM</a>
                                        <?php endif ?>
                                    </td>
                                    <td class="ps-3 text-xs font-weight-bold">
                                        <button class="badge border border-1 border-info text-info btn-view-profile" title="Lihat Profil" data-nama="<?= htmlspecialchars($application->nama) ?>" data-nim="<?= $application->nim ?>" data-kelas="<?= $application->kelas ?>" data-jurusan="<?= $application->jurusan ?? '-' ?>" data-instansi="<?= htmlspecialchars($application->instansi ?? '-') ?>" data-tgl_masuk="<?= $application->tgl_masuk ?>" data-tgl_selesai="<?= $application->tgl_selesai ?>" data-nik="<?= $application->nik ?>" data-tempat_lahir="<?= htmlspecialchars($application->tempat_lahir ?? '-') ?>" data-tanggal_lahir="<?= $application->tanggal_lahir ?>" data-jenis_kelamin="<?= $application->jenis_kelamin ?>" data-nohp_emergency="<?= $application->no_hp_emergency ?>" data-surat_permohonan="<?= $application->surat_permohonan ?>" data-cv="<?= $application->cv ?>" data-ktp="<?= $application->ktp ?>" data-bpjs="<?= $application->bpjs ?>" data-ktm="<?= $application->ktm ?>" data-surat_balasan="<?= $application->surat_balasan ?? '' ?>" data-nohp="<?= $application->no_hp ?>" data-alamat="<?= htmlspecialchars($application->alamat) ?>" data-foto="<?= $application->foto ? '/assets/img/foto_siswa/'.$application->foto : '/assets/img/default.jpg' ?>" data-issmk="<?= isSmkSiswa($application) ? 'true' : 'false' ?>"><i class="fas fa-eye"></i></button>
                                        <button <?= $application->status != 'pending' ? 'disabled style="opacity: 0.6;"' : '' ?> class="badge border border-1 border-danger text-danger btn-destroy" title="Hapus data" data-item="<?= $application->id; ?>"><i class="fas fa-trash"></i></button>
                                        <button <?= $application->status == 'reject by system' ? 'disabled style="opacity: 0.6;"' : '' ?> class="badge border border-1 border-warning text-warning btn-edit-status" title="Update Status" data-stts="<?= $application->status ?>" data-item="<?= $application->id; ?>" data-dosen="<?= $application->id_dosen_pembimbing ?>" data-issmk="<?= isSmkSiswa($application) ? '1' : '0' ?>"><i class="fas fa-tag"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Profile Siswa -->
                <div class="modal fade" id="modalProfileSiswa" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
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

                <!-- Modal Edit Pendaftaran -->
                <div class="modal fade" id="modalEditApplication" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Pendaftaran</h5>
                            </div>
                            <form id="formEditApplication">
                                <?= csrf_field() ?>
                                <div class="modal-body">
                                    <input type="hidden" name="item" id="edit_app_id">
                                    
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label for="edit_status" class="form-label">Keputusan Seleksi</label>
                                        <select name="status" id="edit_status" class="form-control">
                                            <option value="pending">Menunggu</option>
                                            <option value="accepted">Terima (Lolos Seleksi)</option>
                                            <option value="rejected">Tolak</option>
                                        </select>
                                    </div>
                                    
                                    <div id="edit_dosen_wrapper" class="input-group input-group-outline mb-3 is-filled">
                                        <label for="edit_dosen" class="form-label">Dosen Pembimbing</label>
                                        <select name="id_dosen_pembimbing" id="edit_dosen" class="form-control">
                                            <option value="">-- Belum Ditentukan --</option>
                                            <?php foreach ($dosen as $d) : ?>
                                                <option value="<?= $d->id ?>"><?= $d->nama ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Modal Upload Template -->
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
            <?php else : ?>
                <div class="d-flex justify-content-between align-items-center px-2 mb-4">
                    <div>
                        <span class="h5 mb-0 pb-0">Surat Magang</span> <br>
                        Silakan unduh dan unggah surat magang anda di sini.
                    </div>
                </div>

                <div>
                    <div class="card card-body rounded bg-gradient-light shadow-sm p-3 mb-4 border border-info">
                        <h6 class="text-info mb-2"><i class="fas fa-file-signature me-1"></i> Surat Pernyataan Magang</h6>
                        <p class="text-sm mb-3">Silakan unduh template surat magang berikut, lengkapi, lalu unggah kembali dalam format PDF/Word.</p>
                        
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <?php 
                                $fileUrl = false;
                                if (file_exists(FCPATH . 'assets/berkas/template_surat_magang.docx')) $fileUrl = '/assets/berkas/template_surat_magang.docx';
                                elseif (file_exists(FCPATH . 'assets/berkas/template_surat_magang.pdf')) $fileUrl = '/assets/berkas/template_surat_magang.pdf';
                                elseif (file_exists(FCPATH . 'assets/berkas/template_surat_magang.doc')) $fileUrl = '/assets/berkas/template_surat_magang.doc';
                            ?>
                            <?php if ($fileUrl) : ?>
                                <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-sm btn-info mb-0">
                                    <i class="fas fa-download me-1"></i> Unduh Template
                                </a>
                            <?php else : ?>
                                <span class="badge badge-secondary mb-0">Template belum tersedia</span>
                            <?php endif; ?>
                            
                            <form action="/siswa/upload_pernyataan" method="post" enctype="multipart/form-data" class="d-flex align-items-center mb-0 m-0">
                                <?= csrf_field() ?>
                                <input type="file" name="surat_pernyataan" class="form-control form-control-sm border px-2 py-1 me-2" style="max-width: 200px;" accept=".pdf,.doc,.docx" required>
                                <button type="submit" class="btn btn-sm btn-success mb-0">
                                    <i class="fas fa-upload me-1"></i> Unggah
                                </button>
                            </form>
                            
                            <?php if (isset($siswa_data) && isset($siswa_data->surat_pernyataan) && $siswa_data->surat_pernyataan) : ?>
                                <a href="/assets/berkas/<?= $siswa_data->surat_pernyataan ?>" target="_blank" class="text-success text-sm font-weight-bold ms-2">
                                    <i class="fas fa-check-circle me-1"></i> Sudah Diunggah
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
                $('#edit_dosen').select2({
                    dropdownParent: $('#modalEditApplication')
                });

                var tbApplication = $("#tbapplications").DataTable({
                    dom: '<"row"<"col-12"tr>><"row mt-2 px-3"<"col-12 col-md-6"i><"col-12 col-md-6"p>>',
                    pageLength: 10,
                    language: {
                        paginate: {
                            next: '<i class="fas fa-angle-right"></i>',
                            previous: '<i class="fas fa-angle-left"></i>'
                        }
                    },
                });
                


                // fil search
                $('#filsearch').on('keyup change input', function() {
                    $(this).val() == "" ? $(this).parent().removeClass('is-filled') : $(this).parent().addClass('is-filled');
                    tbApplication.search(this.value).draw();
                });

                $("#tbapplications tbody").on('click', '.btn-destroy', function(s) {
                    const t = $(this).data('item');
                    const nama = $(this).parent().parent().find('td:eq(4)').text();
                    const instansi = $(this).parent().parent().find('td:eq(5) .iname').text();

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

                $("#tbapplications tbody").on('click', '.btn-edit-status', function(s) {
                    const id = $(this).data('item');
                    const status = $(this).data('stts');
                    const id_dosen = $(this).data('dosen');
                    const is_smk = $(this).data('issmk');
                    const nama = $(this).parent().parent().find('td:eq(4)').text();
                    const instansi = $(this).parent().parent().find('td:eq(5) .iname').text();

                    $('#edit_app_id').val(id);
                    $('#edit_status').val(status);
                    $('#edit_dosen').val(id_dosen).trigger('change');
                    
                    if (is_smk == '1') {
                        $('#edit_dosen_wrapper').hide();
                        $('#edit_dosen').val('');
                    } else {
                        $('#edit_dosen_wrapper').show();
                    }
                    
                    $('#modalEditApplication .modal-title').html(`Update Pendaftaran: <b>${nama}</b>`);
                    $('#modalEditApplication').modal('show');
                });

                $('#formEditApplication').on('submit', function(e) {
                    e.preventDefault();
                    const data = $(this).serialize();

                    $.ajax({
                        url: "/application/status/update",
                        type: "post",
                        dataType: "json",
                        data: data,
                        beforeSend: function() {
                            $('#formEditApplication button[type=submit]').attr('disabled', true).html('Simpan...');
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
                                    $('#formEditApplication button[type=submit]').attr('disabled', false).html('Simpan');
                                });
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "Terjadi kesalahan pada server", "error");
                            $('#formEditApplication button[type=submit]').attr('disabled', false).html('Simpan');
                        }
                    });
                });

                $("#tbapplications tbody").on('click', '.btn-view-profile', function() {
                    const d = $(this).data();
                    const label = (d.issmk === true || d.issmk === 'true') ? 'NIS' : 'NIM';

                    $('#prof_nama').text(d.nama || '-');
                    $('#prof_nim').text(label + ': ' + (d.nim || '-'));
                    $('#prof_kelas').text(d.kelas || '-');
                    $('#prof_jurusan').text(d.jurusan || '-');
                    $('#prof_instansi').text(d.instansi || '-');
                    
                    // Format dates
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
                    
                    // Build documents
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