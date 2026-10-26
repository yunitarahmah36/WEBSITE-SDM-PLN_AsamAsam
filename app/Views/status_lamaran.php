<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <!-- Banner Status Lamaran -->
        <div class="p-3 mb-4 rounded-4 d-flex align-items-center" style="background-color: #ebf7ee; border: 1px solid #d2edd7;">
            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; background-color: #c9ebd4;">
                <i class="fas fa-file-alt fs-5" style="color: #0f5132;"></i>
            </div>
            <div>
                <h6 class="mb-0 font-weight-bold" style="color: #0f5132; font-size: 1.05rem;">Status Lamaran</h6>
                <div class="text-xs" style="color: #52796f;">Daftar berikut merupakan status pendaftaran anda di tempat magang.</div>
            </div>
        </div>

        <!-- Tombol Tambah Magang Baru -->
        <div class="d-flex justify-content-end mb-3">
            <a class="btn btn-sm text-white mb-0" href="/tempat" style="background-color: #156c3f; border-radius: 8px; font-weight: 700; font-size: 11px; padding: 8px 18px; letter-spacing: 0.5px;">
                <i class="fas fa-plus me-1"></i> DAFTAR MAGANG BARU
            </a>
        </div>

        <?php if (empty($applications)) : ?>
            <div class="card card-body rounded-4 bg-white p-5 text-center border" style="border-color: #e2e8f0 !important;">
                <p class="text-secondary text-sm mb-0">Anda belum memiliki riwayat lamaran magang.</p>
                <?php if (!empty($siswa->surat_balasan)) : ?>
                    <div class="mt-3">
                        <span class="text-xs text-muted d-block mb-2">Surat Balasan Magang:</span>
                        <a href="<?= base_url('assets/berkas/' . $siswa->surat_balasan) ?>" target="_blank" class="btn btn-sm text-white me-2 mb-0" style="background-color: #15803d; border-radius: 6px; font-weight: 700; font-size: 11px; padding: 7px 16px;">
                            <i class="fas fa-file-alt me-1"></i> LIHAT SURAT BALASAN
                        </a>
                        <a href="<?= base_url('assets/berkas/' . $siswa->surat_balasan) ?>" download class="btn btn-sm mb-0" style="background-color: #dcfce7; color: #166534; border: none; border-radius: 6px; font-weight: 700; font-size: 11px; padding: 7px 16px;">
                            <i class="fas fa-arrow-down me-1"></i> UNDUH
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php foreach ($applications as $app) : ?>
                <div class="card card-body rounded-4 bg-white p-4 mb-4 border" style="border-color: #e2e8f0 !important; box-shadow: 0 2px 12px rgba(0,0,0,0.03);">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="mb-1 font-weight-bold text-dark" style="font-size: 1.15rem;"><?= $app->instansi ?></h5>
                            <div class="text-xs text-secondary d-flex align-items-center">
                                <i class="fas fa-map-marker-alt me-1 text-muted"></i> <?= $app->alamat ?>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="mb-1">
                                <?php 
                                    $badgeBg = '#d1fae5';
                                    $badgeText = '#065f46';
                                    $statusUpper = strtoupper($app->status);
                                    if ($app->laporan == null && (date('Y-m-d') > $app->tgl_selesai)) {
                                        $badgeBg = '#fef3c7';
                                        $badgeText = '#92400e';
                                        $statusUpper = 'UNFINISH';
                                    } elseif ($app->status == 'pending') {
                                        $badgeBg = '#fef3c7';
                                        $badgeText = '#92400e';
                                    } elseif ($app->status == 'rejected' || $app->status == 'reject by system') {
                                        $badgeBg = '#fee2e2';
                                        $badgeText = '#991b1b';
                                    }
                                ?>
                                <span class="badge" style="background-color: <?= $badgeBg ?>; color: <?= $badgeText ?>; font-weight: 700; font-size: 11px; border-radius: 20px; padding: 5px 14px; letter-spacing: 0.5px;"><?= $statusUpper ?></span>
                            </div>
                            <div class="text-xs text-muted">
                                <i class="far fa-calendar-alt me-1"></i> <?= $app->updated_at ?>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="border-color: #e2e8f0;">
                            <tbody>
                                <tr>
                                    <th class="p-2 text-sm" style="width: 22%; background-color: #f8fafc; color: #475569; font-weight: 600;">Nama Pembimbing</th>
                                    <td class="p-2 text-sm text-dark font-weight-bold"><?= $app->nama_pembimbing ?></td>
                                </tr>
                                <tr>
                                    <th class="p-2 text-sm" style="background-color: #f8fafc; color: #475569; font-weight: 600;">No Pembimbing</th>
                                    <td class="p-2 text-sm">
                                        <?php 
                                            $wa_hp = preg_replace('/[^0-9]/', '', $app->hp_pembimbing);
                                            if (strpos($wa_hp, '0') === 0) {
                                                $wa_hp = '62' . substr($wa_hp, 1);
                                            }
                                        ?>
                                        <a href="https://wa.me/<?= $wa_hp ?>" target="_blank" style="color: #15803d; font-weight: 600;"><i class="fab fa-whatsapp me-1"></i> <?= $app->hp_pembimbing ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="p-2 text-sm" style="background-color: #f8fafc; color: #475569; font-weight: 600;">Email Pembimbing</th>
                                    <td class="p-2 text-sm">
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= $app->email_pembimbing ?>" target="_blank" style="color: #15803d; font-weight: 600;"><i class="far fa-envelope me-1"></i> <?= $app->email_pembimbing ?></a>
                                    </td>
                                </tr>

                                <?php if (!empty($app->nama_dosen)) : ?>
                                <tr>
                                    <th class="p-2 text-sm" style="background-color: #f8fafc; color: #475569; font-weight: 600;">Dosen Pembimbing</th>
                                    <td class="p-2 text-sm text-dark font-weight-bold"><?= $app->nama_dosen ?></td>
                                </tr>
                                <tr>
                                    <th class="p-2 text-sm" style="background-color: #f8fafc; color: #475569; font-weight: 600;">No Dosen (WA)</th>
                                    <td class="p-2 text-sm">
                                        <?php 
                                            $wa_dosen = preg_replace('/[^0-9]/', '', $app->hp_dosen);
                                            if (strpos($wa_dosen, '0') === 0) {
                                                $wa_dosen = '62' . substr($wa_dosen, 1);
                                            }
                                        ?>
                                        <a href="https://wa.me/<?= $wa_dosen ?>" target="_blank" style="color: #15803d; font-weight: 600;"><i class="fab fa-whatsapp me-1"></i> <?= $app->hp_dosen ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="p-2 text-sm" style="background-color: #f8fafc; color: #475569; font-weight: 600;">Email Dosen</th>
                                    <td class="p-2 text-sm">
                                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= $app->email_dosen ?>" target="_blank" style="color: #15803d; font-weight: 600;"><i class="far fa-envelope me-1"></i> <?= $app->email_dosen ?></a>
                                    </td>
                                </tr>
                                <?php endif ?>

                                <tr>
                                    <th class="p-2 text-sm" style="background-color: #f8fafc; color: #475569; font-weight: 600;">Surat Balasan</th>
                                    <td class="p-2 text-sm">
                                        <?php 
                                            $suratBalasan = !empty($app->surat_balasan) ? $app->surat_balasan : (!empty($siswa->surat_balasan) ? $siswa->surat_balasan : null);
                                        ?>
                                        <?php if ($suratBalasan) : ?>
                                            <a href="<?= base_url('assets/berkas/' . $suratBalasan) ?>" target="_blank" class="btn btn-sm text-white mb-0 me-2" style="background-color: #15803d; border-radius: 6px; font-weight: 700; font-size: 11px; padding: 7px 16px; letter-spacing: 0.3px;">
                                                <i class="fas fa-file-alt me-1"></i> LIHAT SURAT BALASAN
                                            </a>
                                            <a href="<?= base_url('assets/berkas/' . $suratBalasan) ?>" download class="btn btn-sm mb-0" style="background-color: #dcfce7; color: #166534; border: none; border-radius: 6px; font-weight: 700; font-size: 11px; padding: 7px 16px; letter-spacing: 0.3px;">
                                                <i class="fas fa-arrow-down me-1"></i> UNDUH
                                            </a>
                                        <?php else : ?>
                                            <span class="text-xs text-secondary fst-italic">Belum ada surat balasan</span>
                                        <?php endif ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="p-2 text-sm" style="background-color: #f8fafc; color: #475569; font-weight: 600;">Laporan Akhir</th>
                                    <td class="p-2 text-sm">
                                        <?php if ($app->laporan) : ?>
                                            <a href="<?= base_url('assets/laporan/' . $app->laporan) ?>" target="_blank" class="btn btn-sm mb-0" style="background-color: #ecfdf5; color: #166534; border: 1px solid #86efac; border-radius: 6px; font-weight: 700; font-size: 11px; padding: 7px 16px; letter-spacing: 0.3px;">
                                                <i class="fas fa-file-alt me-1"></i> LIHAT LAPORAN
                                            </a>
                                        <?php else : ?>
                                            <a href="/laporan-akhir" class="text-xs font-weight-bold text-dark"><i class="fa fa-external-link-alt me-1"></i> Pergi ke Menu Laporan</a>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection(); ?>