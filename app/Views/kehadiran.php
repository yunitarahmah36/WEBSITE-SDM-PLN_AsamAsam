<?= $this->extend('main'); ?>
<?= $this->section('topsc'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* =========================================
       KAMERA BUKTI KEGIATAN
       ========================================= */
    .bukti-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .bukti-preview {
        display: none;
        position: relative;
        width: 100%;
        max-width: 400px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #d9e0e8;
        background: #f5f7fa;
    }

    .bukti-preview.active {
        display: block;
    }

    .bukti-preview img {
        width: 100%;
        height: auto;
        display: block;
    }

    .bukti-preview .btn-hapus-preview {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(220, 53, 69, 0.9);
        color: #fff;
        border: 0;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .bukti-preview .btn-hapus-preview:hover {
        background: #dc3545;
    }

    .bukti-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .bukti-buttons .btn {
        flex: 1;
        min-width: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .btn-kamera {
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff;
        border: none;
        box-shadow: 0 4px 10px rgba(21, 128, 61, 0.2);
    }

    .btn-kamera:hover {
        background: linear-gradient(135deg, #166534, #15803d);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-galeri {
        background: #ecfdf5;
        color: #15803d;
        border: 1.5px solid #a7f3d0;
        font-weight: 600;
    }

    .btn-galeri:hover {
        background: #d1fae5;
        color: #065f46;
        border-color: #6ee7b7;
    }

    .btn-absen-submit {
        background: linear-gradient(135deg, #15803d, #16a34a) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 10px !important;
        padding: 10px 24px !important;
        font-weight: 700 !important;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25) !important;
        transition: all 0.2s ease;
    }

    .btn-absen-submit:hover {
        background: linear-gradient(135deg, #166534, #15803d) !important;
        color: #fff !important;
        box-shadow: 0 6px 16px rgba(21, 128, 61, 0.35) !important;
        transform: translateY(-1px);
    }

    .form-absen-card {
        border: 1px solid #d2edd7 !important;
        border-radius: 16px !important;
        box-shadow: 0 6px 24px rgba(15, 81, 50, 0.05) !important;
    }

    .form-absen-card .form-label {
        color: #1e3a2b;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .form-absen-card .form-control:focus,
    .form-absen-card select:focus,
    .form-absen-card textarea:focus {
        border-color: #15803d !important;
        box-shadow: 0 0 0 2px rgba(21, 128, 61, 0.15) !important;
    }

    .form-absen-card .input-group.input-group-outline.is-focused .form-label,
    .form-absen-card .input-group.input-group-outline.is-filled .form-label {
        color: #15803d !important;
    }

    .form-absen-card .input-group.input-group-outline.is-focused .form-control {
        border-color: #15803d !important;
        border-top-color: transparent !important;
        box-shadow: inset 1px 0 #15803d, inset -1px 0 #15803d, inset 0 -1px #15803d !important;
    }

    /* Modal Kamera */
    #modalKamera .modal-content {
        border-radius: 14px;
        overflow: hidden;
    }

    #modalKamera video {
        width: 100%;
        height: auto;
        background: #000;
        display: block;
        border-radius: 8px;
    }

    #modalKamera .modal-footer {
        border-top: 1px solid #e9edf3;
    }

    /* Loading kamera */
    .camera-loading {
        padding: 40px 20px;
        text-align: center;
        color: #8392ab;
    }

    .camera-loading .spinner {
        width: 32px;
        height: 32px;
        border: 3px solid #e9edf3;
        border-top-color: #15803d;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin: 0 auto 12px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .camera-error {
        padding: 30px 20px;
        text-align: center;
        color: #dc3545;
    }

    .camera-error i {
        font-size: 42px;
        display: block;
        margin-bottom: 12px;
    }

    /* Info teks */
    .bukti-info {
        font-size: 11.5px;
        color: #047857;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
    }

    .bukti-info i {
        font-size: 14px;
        color: #10b981;
    }

    /* Flatpickr tema hijau */
    .flatpickr-calendar {
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(15, 81, 50, 0.15) !important;
        border: 1px solid #d2edd7 !important;
    }
    .flatpickr-time .numInput:focus,
    .flatpickr-time .numInput:hover {
        background: #ecfdf5 !important;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row justify-content-center">
    <div class="col-12 col-md-8">
        <?php if (!$is_safety_ready && !empty(getApplicationSiswa(getSidByUid(user_id())))) : ?>
            <div class="card card-body text-center p-5">
                <i class="material-icons text-danger fs-1 mb-3">gpp_maybe</i>
                <h5 class="mb-2">Akses Terkunci: Prasyarat Keselamatan</h5>
                <p class="text-secondary px-lg-5">Anda wajib mempelajari dan mengonfirmasi pemahaman Materi Safety & K3 di dashboard sebelum dapat mengakses fitur presensi dan logbook ini.</p>
                <div class="d-flex justify-content-center gap-2 mt-3">
                    <a href="/dashboard" class="btn btn-sm btn-outline-dark">Kembali ke Dashboard</a>
                    <a href="/materi-k3" class="btn btn-sm bg-gradient-danger">Buka Materi K3</a>
                </div>
            </div>
        <?php elseif ($tempat || (isSiswaActive(getSidByUid(user_id())) && getStatusSiswa(getSidByUid(user_id())) !== 'selesai')) : ?>
            <div class="card form-absen-card">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 style="color: #0f4423; font-weight: 700;">
                            <span class="material-icons align-middle me-1 text-success" style="font-size: 1.4rem;">edit_calendar</span>
                            Form <?= isset($edit_logbook) ? 'Edit ' : ''  ?>Kehadiran
                        </h5>
                    </div>
                    <form class="form-absen" method="post" enctype="multipart/form-data">
                        <?php if (isset($edit_logbook)) : ?>
                            <input type="hidden" name="lid" id="lid" value="<?= $edit_logbook->id ?>">
                            <input type="hidden" name="pid" value="<?= $edit_logbook->id_pembimbing ?>">
                            <input type="hidden" name="tid" value="<?= $edit_logbook->id_tempat ?>">
                        <?php elseif ($tempat) : ?>
                            <input type="hidden" name="pid" value="<?= $tempat->pid ?>">
                            <input type="hidden" name="tid" value="<?= $tempat->tid ?>">
                        <?php endif ?>
                        <input type="hidden" name="sid" value="<?= getSidByUid(user_id()) ?>">

                        <?php if (!$tempat && !isset($edit_logbook)) : ?>
                            <div class="alert alert-warning text-white border-0">
                                <h6 class="text-white">Penempatan Belum Tersedia</h6>
                                <p class="text-sm mb-0">Anda belum memiliki penempatan magang yang aktif. Silakan ajukan lamaran atau hubungi admin jika ini adalah kesalahan.</p>
                            </div>
                        <?php else : ?>
                            <div class="mb-3">
                                <label class="form-label mb-1" for="tanggal">Tanggal Kegiatan</label>
                                <div class="input-group input-group-outline">
                                    <input type="date" class="form-control" name="tanggal" id="tanggal" value="<?= isset($edit_logbook) ? $edit_logbook->tanggal : date('Y-m-d') ?>" max="<?= date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-1" for="keterangan">Keterangan</label>
                                <div class="input-group input-group-outline mb-3 is-filled">
                                    <select name="keterangan" id="keterangan" class="form-control">
                                        <option value="">-- Pilih Keterangan --</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'hadir' ? 'selected' : '') ?> value="hadir">Hadir</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'izin' ? 'selected' : '') ?> value="izin">izin</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'sakit' ? 'selected' : '') ?> value="sakit">sakit</option>
                                        <option <?= !isset($edit_logbook) ? '' : ($edit_logbook->keterangan == 'alfa' ? 'selected' : '') ?> value="alfa">alfa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col">
                                    <label class="form-label mb-1" for="jam_masuk">Jam Masuk</label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control ps-2" name="jam_masuk" id="jam_masuk" value="<?= isset($edit_logbook) ? $edit_logbook->jam_masuk : ''  ?>" placeholder="Pilih jam masuk" readonly="readonly" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="form-label mb-1" for="jam_keluar">Jam Keluar</label>
                                    <div class="input-group input-group-outline">
                                        <input type="text" class="form-control ps-2" name="jam_keluar" id="jam_keluar" value="<?= isset($edit_logbook) ? $edit_logbook->jam_keluar : ''  ?>" placeholder="Pilih jam keluar" readonly="readonly" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mb-1" for="kegiatan">Kegiatan yang dilakukan</label>
                                <div class="input-group input-group-outline mb-3">
                                    <textarea name="kegiatan" id="kegiatan" rows="4" class="form-control" placeholder="kegiatan hari ini"><?= isset($edit_logbook) ? $edit_logbook->kegiatan : ''  ?></textarea>
                                </div>
                            </div>

                            <!-- ============================================= -->
                            <!-- BUKTI KEGIATAN - DENGAN PILIHAN KAMERA/GALERI -->
                            <!-- ============================================= -->
                            <div class="mb-3">
                                <label class="form-label mb-1" for="bukti">Bukti Kegiatan</label>

                                <div class="bukti-container">
                                    <!-- Input file asli (hidden, tapi tetap dipakai untuk submit) -->
                                    <input type="file" name="bukti" id="bukti" accept="image/*" style="display: none;" required>

                                    <!-- Tombol pilih sumber -->
                                    <div class="bukti-buttons" id="buktiButtons">
                                        <button type="button" class="btn btn-kamera" id="btnAmbilKamera">
                                            <i class="material-icons" style="font-size: 18px;">photo_camera</i>
                                            Ambil dari Kamera
                                        </button>
                                        <button type="button" class="btn btn-galeri" id="btnPilihGaleri">
                                            <i class="material-icons" style="font-size: 18px;">photo_library</i>
                                            Pilih dari Galeri
                                        </button>
                                    </div>

                                    <!-- Preview foto yang sudah dipilih/diambil -->
                                    <div class="bukti-preview" id="buktiPreview">
                                        <img src="" alt="Preview Bukti" id="buktiImg">
                                        <button type="button" class="btn-hapus-preview" id="btnHapusPreview" title="Hapus foto">
                                            <i class="material-icons" style="font-size: 18px;">close</i>
                                        </button>
                                    </div>

                                    <!-- Info -->
                                    <div class="bukti-info" id="buktiInfo">
                                        <i class="material-icons">info</i>
                                        <span>Ambil foto langsung dari kamera atau pilih dari galeri. Maksimal 5 MB.</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-absen-submit btn-block"><?= isset($edit_logbook) ? 'Perbarui' : '' ?> Absen</button>
                            <?php if (isset($edit_logbook)) : ?>
                                <button type="button" class="btn btn-outline-success btn-block me-2 btn-back" style="border-radius: 10px; font-weight: 600;">Kembali</button>
                            <?php endif ?>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        <?php endif ?>
    </div>
    <div class="col-12 col-md-8 mt-3">
        <?php if (isset($edit_logbook)) : ?>
            <div class="alert border shadow-sm w-100 text-start py-3 px-3" style="background-color: #ecfdf5 !important; border-color: #a7f3d0 !important; color: #065f46;">
                <h6 style="color: #044e31; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 6px;">
                    <span class="material-icons align-middle me-1" style="font-size: 1.2rem; color: #15803d;">info</span> Perhatian !
                </h6>
                <p class="mb-0" style="font-size: 0.85rem; line-height: 1.5; color: #065f46;">Pastikan detail dari <strong>absensi</strong> dan <strong>logbook</strong> Anda <u>sudah benar</u>, agar tidak terjadi penolakan oleh pembimbing magang.</p>
            </div>
        <?php else : ?>
            <div class="alert border shadow-sm w-100 text-center py-3" style="background-color: #ecfdf5 !important; border-color: #a7f3d0 !important;">
                <span style="color: #065f46;">Untuk melihat daftar logbook Anda, silakan buka menu <a href="/logbookku" style="color: #15803d; font-weight: 700; text-decoration: underline;">Laporan Logbook</a>.</span>
            </div>
        <?php endif ?>
    </div>
</div>

<!-- ============================================= -->
<!-- MODAL KAMERA -->
<!-- ============================================= -->
<div class="modal fade" id="modalKamera" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="modalKameraLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKameraLabel">Ambil Foto Bukti</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <!-- Loading -->
                <div id="kameraLoading" class="camera-loading">
                    <div class="spinner"></div>
                    <div>Menyalakan kamera...</div>
                </div>

                <!-- Error -->
                <div id="kameraError" class="camera-error" style="display:none;">
                    <i class="material-icons">no_photography</i>
                    <div id="kameraErrorMsg">Kamera tidak dapat diakses.</div>
                    <button type="button" class="btn btn-sm btn-galeri mt-3" data-bs-dismiss="modal">Gunakan Galeri Saja</button>
                </div>

                <!-- Video Preview -->
                <video id="kameraVideo" autoplay playsinline style="display:none;"></video>
                <canvas id="kameraCanvas" style="display:none;"></canvas>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="material-icons" style="font-size: 16px; vertical-align: middle;">arrow_back</i> Batal
                </button>
                <button type="button" class="btn btn-sm btn-absen-submit" id="btnCapture" disabled>
                    <i class="material-icons" style="font-size: 16px; vertical-align: middle;">camera</i> Ambil Foto
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="/assets/js/plugins/sweetalert.min.js"></script>

<script>
    $(document).ready(function() {
        $('body').tooltip({ selector: '[data-bs-toggle="tooltip"]' });

        $("#jam_masuk, #jam_keluar").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            disableMobile: true
        });

        // =============================================
        // KAMERA BUKTI KEGIATAN
        // =============================================
        let cameraStream = null;
        const videoEl = document.getElementById('kameraVideo');
        const canvasEl = document.getElementById('kameraCanvas');
        const inputBukti = document.getElementById('bukti');
        const previewImg = document.getElementById('buktiImg');
        const buktiPreview = document.getElementById('buktiPreview');
        const buktiButtons = document.getElementById('buktiButtons');
        const modalKameraEl = document.getElementById('modalKamera');
        const modalKamera = new bootstrap.Modal(modalKameraEl);
        const btnCapture = document.getElementById('btnCapture');
        const kameraLoading = document.getElementById('kameraLoading');
        const kameraError = document.getElementById('kameraError');
        const kameraErrorMsg = document.getElementById('kameraErrorMsg');

        // Cek apakah browser support getUserMedia
        const isCameraSupported = !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);

        // Tampilkan tombol kamera kalau didukung
        if (!isCameraSupported) {
            document.getElementById('btnAmbilKamera').style.display = 'none';
        }

        // =============================================
        // BUKA KAMERA
        // =============================================
        document.getElementById('btnAmbilKamera').addEventListener('click', function() {
            modalKamera.show();

            // Reset UI
            kameraLoading.style.display = 'block';
            kameraError.style.display = 'none';
            videoEl.style.display = 'none';
            btnCapture.disabled = true;
            btnCapture.innerHTML = '<i class="material-icons" style="font-size: 16px; vertical-align: middle;">camera</i> Ambil Foto';

            // Stop stream lama kalau ada
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }

            // Minta akses kamera
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: { ideal: 'environment' }, // kamera belakang (untuk HP)
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                },
                audio: false
            })
            .then(function(stream) {
                cameraStream = stream;
                videoEl.srcObject = stream;
                videoEl.play();

                videoEl.onloadedmetadata = function() {
                    kameraLoading.style.display = 'none';
                    videoEl.style.display = 'block';
                    btnCapture.disabled = false;
                };
            })
            .catch(function(err) {
                console.error('Kamera error:', err);
                kameraLoading.style.display = 'none';
                kameraError.style.display = 'block';

                let msg = 'Kamera tidak dapat diakses. ';
                if (err.name === 'NotAllowedError') {
                    msg += 'Anda menolak izin akses kamera. Silakan izinkan di pengaturan browser.';
                } else if (err.name === 'NotFoundError') {
                    msg += 'Tidak ada kamera yang terdeteksi.';
                } else if (err.name === 'NotReadableError') {
                    msg += 'Kamera sedang dipakai aplikasi lain.';
                } else if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
                    msg += 'Kamera hanya bisa diakses via HTTPS atau localhost. Silakan gunakan galeri saja.';
                } else {
                    msg += err.message;
                }
                kameraErrorMsg.textContent = msg;
            });
        });

        // =============================================
        // AMBIL FOTO
        // =============================================
        btnCapture.addEventListener('click', function() {
            if (!cameraStream) return;

            // Set ukuran canvas sama dengan video
            const w = videoEl.videoWidth;
            const h = videoEl.videoHeight;
            canvasEl.width = w;
            canvasEl.height = h;

            // Gambar frame video ke canvas
            const ctx = canvasEl.getContext('2d');
            ctx.drawImage(videoEl, 0, 0, w, h);

            // Convert canvas ke blob (JPEG, kualitas 0.9)
            canvasEl.toBlob(function(blob) {
                if (!blob) {
                    Swal.fire('Gagal', 'Gagal memproses gambar.', 'error');
                    return;
                }

                // Buat File dari Blob
                const fileName = 'bukti_kamera_' + Date.now() + '.jpg';
                const file = new File([blob], fileName, { type: 'image/jpeg' });

                // Set ke input file (pakai DataTransfer)
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                inputBukti.files = dataTransfer.files;

                // Tampilkan preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    buktiPreview.classList.add('active');
                    buktiButtons.style.display = 'none';
                    document.getElementById('buktiInfo').style.display = 'none';
                };
                reader.readAsDataURL(file);

                // Tutup modal
                modalKamera.hide();
            }, 'image/jpeg', 0.9);
        });

        // =============================================
        // STOP KAMERA SAAT MODAL DITUTUP
        // =============================================
        modalKameraEl.addEventListener('hidden.bs.modal', function() {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
        });

        // =============================================
        // PILIH DARI GALERI
        // =============================================
        document.getElementById('btnPilihGaleri').addEventListener('click', function() {
            inputBukti.click();
        });

        // =============================================
        // SAAT FILE DIPILIH DARI GALERI
        // =============================================
        inputBukti.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                // Validasi ukuran (maks 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire('Gagal', 'Ukuran file maksimal 5 MB.', 'warning');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    buktiPreview.classList.add('active');
                    buktiButtons.style.display = 'none';
                    document.getElementById('buktiInfo').style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // =============================================
        // HAPUS PREVIEW
        // =============================================
        document.getElementById('btnHapusPreview').addEventListener('click', function() {
            previewImg.src = '';
            buktiPreview.classList.remove('active');
            buktiButtons.style.display = 'flex';
            document.getElementById('buktiInfo').style.display = 'flex';
            inputBukti.value = '';
        });

        // =============================================
        // VALIDASI SEBELUM SUBMIT
        // =============================================
        $('.form-absen').on('submit', function(e) {
            e.preventDefault();

            if (!inputBukti.files || inputBukti.files.length === 0) {
                Swal.fire('Gagal', 'Silakan ambil foto bukti kegiatan terlebih dahulu.', 'warning');
                return;
            }

            var data = new FormData(this);
            $.ajax({
                url: '/kehadiran/store',
                type: 'post',
                data: data,
                dataType: 'JSON',
                processData: false,
                contentType: false,
                success: function(data) {
                    console.log(data);
                    data.success ? Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/kehadiran'
                        }
                    }) : Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    })
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan pada server.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // =============================================
        // KETERANGAN - DISABLE FIELD
        // =============================================
        $('#keterangan').change(function() {
            if ($(this).val() == 'alfa') {
                $('#jam_keluar').attr('disabled', true);
                $('#jam_masuk').attr('disabled', true);
                $('#kegiatan').attr('disabled', true);
            } else if ($(this).val() == 'sakit' || $(this).val() == 'izin') {
                $('#jam_keluar').attr('disabled', true);
                $('#jam_masuk').attr('disabled', true);
            } else {
                $('#jam_keluar').attr('disabled', false);
                $('#kegiatan').attr('disabled', false);
                $('#jam_masuk').attr('disabled', false);
            }
        });

        $('.btn-back').on('click', function() {
            return window.history.back();
        });
    });
</script>
<?= $this->endSection(); ?>