<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<?php 
$isSmk = false;
if (isset($siswa)) {
    $instansiLower = strtolower($siswa->instansi ?? '');
    $kelasLower = strtolower($siswa->kelas ?? '');
    if (strpos($instansiLower, 'smk') !== false || strpos($instansiLower, 'sekolah') !== false || in_array($kelasLower, ['x', 'xi', 'xii', '10', '11', '12'])) {
        $isSmk = true;
    }
}
$isEdit = !empty($nilai);
$hasUploadedFile = !empty($nilai->file_penilaian);
$isModeFile = ($isEdit && ($nilai->metode_penilaian ?? '') === 'file' && !empty($nilai->file_penilaian));

$initialItems = [];
if (!empty($nilaiDetails)) {
    foreach ($nilaiDetails as $dt) {
        if ($dt->nama_atribut === 'Berkas Lembar Penilaian Resmi Pembimbing Lapangan') {
            continue;
        }
        $initialItems[] = [
            'id_atribut' => $dt->id_atribut,
            'nama'       => $dt->nama_atribut,
            'bobot'      => $dt->bobot,
            'nilai'      => $dt->nilai
        ];
    }
}
if (empty($initialItems)) {
    if (!empty($konfigPenilaian->atribut)) {
    foreach ($konfigPenilaian->atribut as $at) {
        $initialItems[] = [
            'id_atribut' => $at->id,
            'nama'       => $at->nama_atribut,
            'bobot'      => $at->bobot,
            'nilai'      => ''
        ];
    }
} elseif (!empty($nilai) && isset($nilai->n_disiplin) && ($nilai->n_disiplin > 0 || $nilai->n_motivasi > 0 || $nilai->n_kehadiran > 0)) {
    $initialItems = [
        ['id_atribut' => null, 'nama' => 'Kedisiplinan', 'bobot' => null, 'nilai' => $nilai->n_disiplin],
        ['id_atribut' => null, 'nama' => 'Kemauan Kerja / Motivasi', 'bobot' => null, 'nilai' => $nilai->n_motivasi],
        ['id_atribut' => null, 'nama' => 'Kehadiran', 'bobot' => null, 'nilai' => $nilai->n_kehadiran],
        ['id_atribut' => null, 'nama' => 'Inisiatif dan kreatifitas', 'bobot' => null, 'nilai' => $nilai->n_kreatifitas],
        ['id_atribut' => null, 'nama' => 'Kejujuran dan Tanggung Jawab', 'bobot' => null, 'nilai' => $nilai->n_kejujuran],
        ['id_atribut' => null, 'nama' => 'Kesopanan dan Personalia', 'bobot' => null, 'nilai' => $nilai->n_kesopanan],
        ['id_atribut' => null, 'nama' => 'Kerjasama', 'bobot' => null, 'nilai' => $nilai->n_kerjasama],
    ];
    if (!$isSmk || !empty($siswa->laporan)) {
        $initialItems[] = ['id_atribut' => null, 'nama' => 'Nilai Laporan', 'bobot' => null, 'nilai' => $nilai->n_laporan];
    }
} else {
    // Default Kriteria Standar PLN
    $initialItems = [
        ['id_atribut' => null, 'nama' => 'Kedisiplinan & Kepatuhan Tata Tertib', 'bobot' => 15, 'nilai' => ''],
        ['id_atribut' => null, 'nama' => 'Kemampuan Teknis & Kualitas Kerja', 'bobot' => 25, 'nilai' => ''],
        ['id_atribut' => null, 'nama' => 'Tanggung Jawab & Kejujuran', 'bobot' => 20, 'nilai' => ''],
        ['id_atribut' => null, 'nama' => 'Inisiatif, Kreativitas & Penerapan K3', 'bobot' => 15, 'nilai' => ''],
        ['id_atribut' => null, 'nama' => 'Kerjasama Tim & Komunikasi', 'bobot' => 15, 'nilai' => ''],
    ];
    if (!$isSmk || !empty($siswa->laporan)) {
        $initialItems[] = ['id_atribut' => null, 'nama' => 'Laporan Akhir Magang', 'bobot' => 10, 'nilai' => ''];
    }
}
}
?>

<div class="container-fluid py-2">
    <!-- Top Nav Action Bar -->
    <div class="row mb-3">
        <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <a href="<?= base_url('/logbook/' . $idt . '/' . $siswa->nim) ?>" class="btn btn-sm btn-outline-dark mb-0">
                    <i class="fa fa-arrow-left me-1"></i> Kembali ke Logbook Siswa
                </a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge <?= $isEdit ? 'bg-gradient-warning' : 'bg-gradient-info' ?> text-xs px-3 py-2 shadow-sm">
                    <i class="fa <?= $isEdit ? 'fa-edit' : 'fa-pen-alt' ?> me-1"></i> <?= $isEdit ? 'Mode Edit Nilai' : 'Mode Input Nilai Baru' ?>
                </span>
                <?php if ($isEdit && isset($nilai->total_nilai)) : ?>
                    <span class="badge bg-gradient-success text-xs px-3 py-2 shadow-sm">
                        Nilai Saat Ini: <?= $nilai->total_nilai ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Student Information Hero Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 p-md-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-xl position-relative">
                                <?php if (!empty($siswa->foto)) : ?>
                                    <img src="<?= base_url('assets/img/foto_siswa/' . $siswa->foto) ?>" alt="<?= esc($siswa->nama) ?>" class="w-100 border-radius-lg shadow-sm" style="object-fit: cover; height: 74px; width: 74px;">
                                <?php else : ?>
                                    <div class="border-radius-lg bg-gradient-info d-flex align-items-center justify-content-center shadow-sm" style="height: 74px; width: 74px;">
                                        <i class="fa fa-user text-white text-2xl"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                <div>
                                    <h5 class="mb-1 text-dark font-weight-bolder">
                                        <?= esc($siswa->nama) ?>
                                    </h5>
                                    <p class="mb-0 text-sm text-secondary">
                                        <span class="font-weight-bold text-dark"><?= esc($siswa->nim) ?></span> &bull; <?= esc($siswa->jurusan ?? 'Jurusan belum diisi') ?>
                                    </p>
                                </div>
                                <span class="badge bg-gradient-light text-dark border text-xs">
                                    <i class="fa fa-building me-1 text-info"></i> <?= esc($tempat->nama ?? 'Divisi Magang') ?>
                                </span>
                            </div>
                            <hr class="horizontal dark my-2">
                            <div class="row text-xs text-secondary g-2">
                                <div class="col-12 col-md-4">
                                    <i class="fa fa-university me-1 text-info"></i> Asal Instansi: 
                                    <strong class="text-dark"><?= esc($konfigPenilaian->instansi->nama_instansi ?? $siswa->instansi ?? '-') ?></strong>
                                </div>
                                <div class="col-12 col-md-4">
                                    <i class="fa fa-calendar-alt me-1 text-info"></i> Periode: 
                                    <strong class="text-dark">
                                        <?= !empty($siswa->tgl_masuk) ? date('d M Y', strtotime($siswa->tgl_masuk)) : '-' ?> s/d <?= !empty($siswa->tgl_selesai) ? date('d M Y', strtotime($siswa->tgl_selesai)) : '-' ?>
                                    </strong>
                                </div>
                                <div class="col-12 col-md-4">
                                    <i class="fa fa-file-alt me-1 text-info"></i> Laporan Akhir: 
                                    <?php if (!empty($siswa->laporan)) : ?>
                                        <a href="<?= base_url('assets/laporan/' . $siswa->laporan) ?>" target="_blank" class="badge bg-gradient-success text-xxs px-2 py-1">
                                            <i class="fa fa-eye me-1"></i> Buka Laporan
                                        </a>
                                    <?php else : ?>
                                        <span class="text-muted">Belum ada</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instansi Format Document Notification Card -->
    <?php if (!empty($konfigPenilaian->file_format)) : ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border border-info shadow-sm" style="background: linear-gradient(135deg, #f0f7ff 0%, #e6f2ff 100%);">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-3 bg-gradient-warning text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fa fa-file-pdf text-xl"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-uppercase font-weight-bolder text-info mb-1">
                                        <i class="fa fa-check-circle me-1"></i> Lembar Format Penilaian Resmi Dari Instansi
                                    </div>
                                    <h6 class="mb-1 text-dark font-weight-bold">
                                        <?= esc($konfigPenilaian->nama_file_asli ?: $konfigPenilaian->file_format) ?>
                                    </h6>
                                    <p class="text-xs text-muted mb-0">
                                        Instansi (<strong><?= esc($konfigPenilaian->instansi->nama_instansi ?? $siswa->instansi) ?></strong>) telah mengunggah lembar kriteria nilai. Silakan buka dokumen untuk mencocokkan aspek penilaian dan bobot yang diminta.
                                    </p>
                                </div>
                            </div>
                            <div class="text-nowrap">
                                <a href="<?= base_url('assets/format_penilaian/' . $konfigPenilaian->file_format) ?>" target="_blank" class="btn btn-sm bg-gradient-info mb-0 shadow">
                                    <i class="fa fa-external-link-alt me-1"></i> Buka / Unduh Lembar Format
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Penilaian Form -->
    <div class="row">
        <div class="col-12">
            <form id="formPenilaian" class="formpenilaian" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="ids" value="<?= $siswa->id ?>">
                <input type="hidden" name="idt" value="<?= $idt ?>">
                <input type="hidden" name="id_konfigurasi" value="<?= $konfigPenilaian->id ?? ($nilai->id_konfigurasi ?? '') ?>">
                <?php if ($isEdit) : ?>
                    <input type="hidden" name="id" value="<?= $nilai->id ?>">
                <?php endif; ?>
                <input type="hidden" name="mode_input" id="input-mode-penilaian" value="kriteria">
                <input type="hidden" name="metode_penilaian" id="input-metode-penilaian" value="bobot">

                <!-- Card: Tabel Kriteria Penilaian -->
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                        <div class="card-header pb-0 pt-3 bg-transparent d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-list-check text-info"></i>
                                    <h6 class="mb-0 font-weight-bolder">Daftar Aspek & Kriteria Penilaian</h6>
                                </div>
                                <p class="text-xs text-muted mb-0">Isi aspek kriteria, persentase bobot, dan skor (0 - 100) sesuai format instansi.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-xs btn-outline-secondary mb-0" id="btn-reset-standar">
                                    <i class="fa fa-sync-alt me-1"></i> Gunakan Template Standar PLN
                                </button>
                                <button type="button" class="btn btn-xs bg-gradient-dark mb-0 shadow-sm" id="btn-add-kriteria">
                                    <i class="fa fa-plus me-1"></i> Tambah Kriteria
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0 pt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered align-items-center mb-0" id="table-kriteria">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 50px;">#</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Aspek / Kriteria Penilaian</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 140px;">
                                                Bobot (%)
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 150px;">
                                                Skor (0 - 100)
                                            </th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder" style="width: 50px;">
                                                Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-kriteria">
                                        <?php foreach ($initialItems as $idx => $it) : ?>
                                            <tr class="kriteria-row">
                                                <td class="text-center align-middle text-xs font-weight-bold row-index">
                                                    <?= $idx + 1 ?>
                                                </td>
                                                <td class="align-middle">
                                                    <?php if (!empty($it['id_atribut'])) : ?>
                                                        <input type="hidden" name="kriteria[<?= $idx ?>][id_atribut]" value="<?= $it['id_atribut'] ?>">
                                                    <?php endif; ?>
                                                    <input type="text" class="form-control form-control-sm border px-2 input-kriteria-nama" 
                                                           name="kriteria[<?= $idx ?>][nama]" value="<?= esc($it['nama']) ?>" 
                                                           placeholder="Nama kriteria / aspek penilaian">
                                                </td>
                                                <td class="align-middle col-bobot-cell">
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-bobot" 
                                                               name="kriteria[<?= $idx ?>][bobot]" value="<?= $it['bobot'] ?? '' ?>" 
                                                               min="0" max="100" step="0.1" placeholder="0">
                                                        <span class="input-group-text px-2 text-xs">%</span>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-nilai font-weight-bold" 
                                                           name="kriteria[<?= $idx ?>][nilai]" value="<?= $it['nilai'] ?>" 
                                                           min="0" max="100" step="0.01" placeholder="0 - 100">
                                                </td>
                                                <td class="align-middle text-center">
                                                    <button type="button" class="btn btn-link text-danger text-gradient p-1 mb-0 btn-remove-row" title="Hapus baris kriteria">
                                                        <i class="fa fa-trash text-sm"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="bg-gray-50 border-top">
                                        <tr>
                                            <th colspan="2" class="text-end text-xs font-weight-bolder text-dark py-2">
                                                Total Bobot & Rata-rata Skor:
                                            </th>
                                            <th class="text-center py-2">
                                                <span id="label-total-bobot" class="badge bg-secondary text-xxs">0%</span>
                                            </th>
                                            <th class="text-center py-2">
                                                <span id="label-total-skor" class="text-sm font-weight-bolder text-info">0.00</span>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Lampiran & Catatan Tambahan (Opsional) -->
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                        <div class="card-header pb-0 pt-3 bg-transparent">
                            <h6 class="mb-0 font-weight-bolder text-sm">
                                <i class="fa fa-paperclip text-info me-1"></i> Lampiran Berkas & Catatan Tambahan (Opsional)
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-xs font-weight-bold text-dark mb-1">
                                        Lampirkan Berkas Penilaian Asli (Opsional)
                                    </label>
                                    <?php if (!empty($nilai->file_penilaian)) : ?>
                                        <div class="mb-2 text-xxs">
                                            <span class="text-muted">Berkas saat ini:</span>
                                            <a href="<?= base_url('assets/file_penilaian/' . $nilai->file_penilaian) ?>" target="_blank" class="font-weight-bold text-info ms-1">
                                                <i class="fa fa-file-pdf me-1"></i> <?= esc($nilai->file_penilaian) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="file_penilaian" id="file_penilaian" class="form-control form-control-sm border px-2" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" style="border-radius: 6px;">
                                    <small class="text-xxs text-muted d-block mt-1">Lampirkan berkas bukti fisik jika ada (PDF / Gambar / Dokumen bertanda tangan, maks 20 MB).</small>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-xs font-weight-bold text-dark mb-1">
                                        Catatan & Komentar Evaluasi (Opsional)
                                    </label>
                                    <textarea name="catatan" id="catatan" class="form-control border px-3 py-2 text-sm" rows="3" placeholder="Tuliskan evaluasi capaian kerja, kedisiplinan, maupun saran untuk peserta magang..."><?= esc($nilai->catatan ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Action Button Bar -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <a href="<?= base_url('/logbook/' . $idt . '/' . $siswa->nim) ?>" class="btn btn-secondary mb-0">
                        <i class="fa fa-times me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn bg-gradient-info mb-0 shadow-sm px-4" id="btn-submit-nilai">
                        <i class="fa fa-save me-1"></i> <?= $isEdit ? 'Simpan Perubahan Nilai' : 'Simpan Nilai Peserta' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('bottomsc'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const isSmk = <?= json_encode($isSmk) ?>;
    const hasLaporan = <?= json_encode(!empty($siswa->laporan)) ?>;

    // Helper renumber baris dan perbarui atribut name
    function renumberRows() {
        $('#tbody-kriteria .kriteria-row').each(function(i) {
            $(this).find('.row-index').text(i + 1);
            $(this).find('.input-kriteria-nama').attr('name', `kriteria[${i}][nama]`);
            $(this).find('.input-kriteria-bobot').attr('name', `kriteria[${i}][bobot]`);
            $(this).find('.input-kriteria-nilai').attr('name', `kriteria[${i}][nilai]`);
            let hiddenId = $(this).find('input[name*="[id_atribut]"]');
            if (hiddenId.length > 0) {
                hiddenId.attr('name', `kriteria[${i}][id_atribut]`);
            }
        });
    }



    // HTML Generator untuk baris kriteria baru (tanpa atribut required agar tidak memblokir jika upload file)
    function getRowHtml(index, nama = '', bobot = '', nilai = '', idAtribut = null) {
        let hiddenIdHtml = idAtribut ? `<input type="hidden" name="kriteria[${index}][id_atribut]" value="${idAtribut}">` : '';

        return `
            <tr class="kriteria-row">
                <td class="text-center align-middle text-xs font-weight-bold row-index">${index + 1}</td>
                <td class="align-middle">
                    ${hiddenIdHtml}
                    <input type="text" class="form-control form-control-sm border px-2 input-kriteria-nama" 
                           name="kriteria[${index}][nama]" value="${nama}" 
                           placeholder="Nama kriteria / aspek penilaian">
                </td>
                <td class="align-middle col-bobot-cell">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-bobot" 
                               name="kriteria[${index}][bobot]" value="${bobot}" 
                               min="0" max="100" step="0.1" placeholder="0">
                        <span class="input-group-text px-2 text-xs">%</span>
                    </div>
                </td>
                <td class="align-middle text-center">
                    <input type="number" class="form-control form-control-sm border px-2 text-center input-kriteria-nilai font-weight-bold" 
                           name="kriteria[${index}][nilai]" value="${nilai}" 
                           min="0" max="100" step="0.01" placeholder="0 - 100">
                </td>
                <td class="align-middle text-center">
                    <button type="button" class="btn btn-link text-danger text-gradient p-1 mb-0 btn-remove-row" title="Hapus baris kriteria">
                        <i class="fa fa-trash text-sm"></i>
                    </button>
                </td>
            </tr>
        `;
    }

    // Helper kalkulasi live total bobot dan rata-rata/estimasi nilai akhir
    function calculateTotal() {
        let sumBobot = 0;
        let sumWeighted = 0;
        let sumSkor = 0;
        let count = 0;

        $('#tbody-kriteria .kriteria-row').each(function() {
            let bobotVal = parseFloat($(this).find('.input-kriteria-bobot').val());
            let skorVal = parseFloat($(this).find('.input-kriteria-nilai').val());

            if (!isNaN(bobotVal) && bobotVal > 0) {
                sumBobot += bobotVal;
            }
            if (!isNaN(skorVal)) {
                count++;
                sumSkor += skorVal;
                if (!isNaN(bobotVal) && bobotVal > 0) {
                    sumWeighted += (skorVal * bobotVal);
                }
            }
        });

        // Update badge total bobot
        let roundedBobot = Math.round(sumBobot * 10) / 10;
        $('#label-total-bobot').text(roundedBobot + '%');
        if (roundedBobot === 100) {
            $('#label-total-bobot').removeClass('bg-secondary bg-warning bg-danger').addClass('bg-success');
        } else if (roundedBobot > 100) {
            $('#label-total-bobot').removeClass('bg-secondary bg-success').addClass('bg-danger');
        } else {
            $('#label-total-bobot').removeClass('bg-secondary bg-success bg-danger').addClass('bg-warning');
        }

        // Update preview estimasi nilai akhir
        let totalAkhir = 0;
        if (sumBobot > 0) {
            totalAkhir = sumWeighted / sumBobot;
        } else if (count > 0) {
            totalAkhir = sumSkor / count;
        }
        $('#label-total-skor').text(totalAkhir > 0 ? totalAkhir.toFixed(2) : '0.00');
    }

    $(document).on('input', '.input-kriteria-bobot, .input-kriteria-nilai', function() {
        calculateTotal();
    });

    // Tambah Baris Kriteria
    $('#btn-add-kriteria').on('click', function() {
        let count = $('#tbody-kriteria .kriteria-row').length;
        $('#tbody-kriteria').append(getRowHtml(count));
        renumberRows();
        calculateTotal();
        $('#tbody-kriteria .kriteria-row:last .input-kriteria-nama').focus();
    });

    // Hapus Baris Kriteria
    $(document).on('click', '.btn-remove-row', function() {
        if ($('#tbody-kriteria .kriteria-row').length <= 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Minimal harus ada 1 kriteria penilaian.',
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }
        $(this).closest('.kriteria-row').remove();
        renumberRows();
        calculateTotal();
    });

    // Reset ke Template Standar PLN
    $('#btn-reset-standar').on('click', function() {
        Swal.fire({
            title: 'Gunakan Template Standar PLN?',
            text: 'Daftar kriteria saat ini akan diganti dengan kriteria standar evaluasi magang PLN.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Terapkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let defaults = [
                    { nama: 'Kedisiplinan & Kepatuhan Tata Tertib', bobot: 15 },
                    { nama: 'Kemampuan Teknis & Kualitas Kerja', bobot: 25 },
                    { nama: 'Tanggung Jawab & Kejujuran', bobot: 20 },
                    { nama: 'Inisiatif, Kreativitas & Penerapan K3', bobot: 15 },
                    { nama: 'Kerjasama Tim & Komunikasi', bobot: 15 }
                ];
                if (!isSmk || hasLaporan) {
                    defaults.push({ nama: 'Laporan Akhir Magang', bobot: 10 });
                } else {
                    defaults[1].bobot = 30;
                    defaults[4].bobot = 20;
                }

                $('#tbody-kriteria').empty();
                defaults.forEach((def, i) => {
                    $('#tbody-kriteria').append(getRowHtml(i, def.nama, def.bobot, ''));
                });
                renumberRows();
                calculateTotal();
                Swal.fire({
                    icon: 'success',
                    title: 'Template Diterapkan',
                    text: 'Kriteria standar evaluasi PLN siap diisi nilainya.',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });

    // Submit Form Nilai (Mendukung Isi Kriteria Manual atau Upload Berkas Saja)
    $('#formPenilaian').on('submit', function(e) {
        e.preventDefault();

        let filePenilaian = $('#file_penilaian')[0]?.files[0];
        let hasExistingFile = <?= json_encode(!empty($nilai->file_penilaian)) ?>;
        let isFileProvided = Boolean(filePenilaian || hasExistingFile);

        // Cek berapa skor kriteria yang diisi
        let filledCount = 0;
        let emptyNama = false;
        let invalidNilai = false;
        let rowCount = $('#tbody-kriteria .kriteria-row').length;

        $('#tbody-kriteria .kriteria-row').each(function() {
            let nama = $(this).find('.input-kriteria-nama').val().trim();
            let nilaiStr = $(this).find('.input-kriteria-nilai').val().trim();

            if (nilaiStr !== '' && !isNaN(parseFloat(nilaiStr))) {
                filledCount++;
                let skor = parseFloat(nilaiStr);
                if (skor < 0 || skor > 100) {
                    invalidNilai = true;
                }
            } else if (nama !== '') {
                // Ada nama tapi nilai kosong
                emptyNama = true;
            }
        });

        // 1. KASUS JIKA USER HANYA MELAMPIRKAN BERKAS (Tabel Kriteria Dikosongkan)
        if (filledCount === 0) {
            if (isFileProvided) {
                $('#input-mode-penilaian').val('file');
                $('#input-metode-penilaian').val('file');

                Swal.fire({
                    title: 'Simpan Berkas Lembar Penilaian?',
                    text: 'Penilaian siswa akan disimpan berdasarkan berkas lembar penilaian fisik yang dilampirkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan Berkas',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitNilaiAjax();
                    }
                });
                return;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nilai Belum Diisi',
                    text: 'Harap isi skor kriteria penilaian pada tabel, atau lampirkan berkas fisik lembar penilaian di bagian bawah.'
                });
                return;
            }
        }

        // 2. KASUS JIKA USER MENGISI SKOR KRITERIA MANUAL
        if (invalidNilai) {
            Swal.fire({
                icon: 'warning',
                title: 'Skor Tidak Valid',
                text: 'Semua skor penilaian kriteria harus berupa angka antara 0 hingga 100.'
            });
            return;
        }

        if (emptyNama || filledCount < rowCount) {
            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi Skor Kriteria',
                text: 'Harap lengkapi semua nilai kriteria (angka 0 - 100) pada tabel atau hapus baris yang tidak digunakan.'
            });
            return;
        }

        $('#input-mode-penilaian').val('kriteria');
        $('#input-metode-penilaian').val('bobot');

        Swal.fire({
            title: 'Simpan Penilaian Siswa?',
            text: 'Pastikan seluruh kriteria, bobot, dan skor yang Anda masukkan sudah sesuai.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan Nilai',
            cancelButtonText: 'Periksa Kembali'
        }).then((result) => {
            if (result.isConfirmed) {
                submitNilaiAjax();
            }
        });
    });

    function submitNilaiAjax() {
        let formData = new FormData($('#formPenilaian')[0]);

        Swal.fire({
            title: 'Menyimpan Penilaian...',
            text: 'Mohon tunggu sejenak.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?= base_url("/nilai/store") ?>',
            type: 'POST',
            data: formData,
            dataType: 'JSON',
            processData: false,
            contentType: false,
            success: function(res) {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Disimpan!',
                        text: res.message || 'Penilaian siswa berhasil disimpan.',
                        showConfirmButton: false,
                        timer: 1800
                    }).then(() => {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                        } else {
                            window.location.href = '<?= base_url("/logbook/" . $idt . "/" . $siswa->nim) ?>';
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: res.message || 'Terjadi kesalahan saat menyimpan nilai.'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan Server',
                    text: xhr.responseText || 'Gagal menghubungi server untuk menyimpan nilai.'
                });
            }
        });
    }

    // Inisialisasi awal
    renumberRows();
    calculateTotal();
});
</script>
<?= $this->endSection(); ?>
