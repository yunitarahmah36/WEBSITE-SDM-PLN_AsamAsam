<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card my-4 border">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 px-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white text-capitalize ps-3 mb-0">Status Lamaran</h6>
                        <div class="text-white text-xs ps-3">daftar berikut merupakan status pendaftaran anda di tempat magang.</div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="px-4 pb-4">
                    <div class="d-flex justify-content-end mb-3">
                        <a class="btn btn-sm btn-dark" href="/tempat">
                            <i class="fas fa-plus"></i> Daftar Magang Baru
                        </a>
                    </div>
                    <?php if (empty($applications)) : ?>
                        <div class="text-center py-5">
                            <p class="text-secondary text-sm">Anda belum memiliki riwayat lamaran magang.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($applications as $app) : ?>
                            <div class="card card-body rounded bg-gray-200 p-2 px-4 mb-3 liInstansi">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $app->created_at ?>" data-container="body" data-animation="true">
                                        <div class="h6 mb-0 pb-0"><?= $app->instansi ?></div>
                                        <div class="text-xs"><?= $app->alamat ?></div>
                                    </div>
                                    <div style="text-align: end !important;">
                                        <div class="mb-2">
                                            <?php if ($app->laporan == null && (date('Y-m-d') > $app->tgl_selesai)) : ?>
                                                <span class="badge badge-warning border border-warning">unfinish</span>
                                            <?php else : ?>
                                                <?= genBadgeStatusApplication($app->status) ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-xs"><?= $app->updated_at ?></div>
                                    </div>
                                </div>

                                <table class="mt-2 table-compact table-bordered">
                                    <tr>
                                        <th class="p-2 text-sm" style="width:20%;">Nama Pembimbing</th>
                                        <td class="p-2 text-sm"><a href="#"><?= $app->nama_pembimbing ?></a></td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 text-sm" style="width:20%;">No Pembimbing</th>
                                        <td class="p-2 text-sm">
                                            <?php 
                                                $wa_hp = preg_replace('/[^0-9]/', '', $app->hp_pembimbing);
                                                if (strpos($wa_hp, '0') === 0) {
                                                    $wa_hp = '62' . substr($wa_hp, 1);
                                                }
                                            ?>
                                            <a href="https://wa.me/<?= $wa_hp ?>" target="_blank" class="text-primary font-weight-bold"><i class="fab fa-whatsapp me-1"></i> <?= $app->hp_pembimbing ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 text-sm" style="width:20%;">Email Pembimbing</th>
                                        <td class="p-2 text-sm">
                                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= $app->email_pembimbing ?>" target="_blank" class="text-primary font-weight-bold"><i class="far fa-envelope me-1"></i> <?= $app->email_pembimbing ?></a>
                                        </td>
                                    </tr>
                                    <?php if ($app->nama_dosen) : ?>
                                    <tr>
                                        <th class="p-2 text-sm bg-gray-100" style="width:20%;">Dosen Pembimbing</th>
                                        <td class="p-2 text-sm bg-gray-100"><span class="font-weight-bold"><?= $app->nama_dosen ?></span></td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 text-sm bg-gray-100" style="width:20%;">No Dosen (WA)</th>
                                        <td class="p-2 text-sm bg-gray-100">
                                            <?php 
                                                $wa_dosen = preg_replace('/[^0-9]/', '', $app->hp_dosen);
                                                if (strpos($wa_dosen, '0') === 0) {
                                                    $wa_dosen = '62' . substr($wa_dosen, 1);
                                                }
                                            ?>
                                            <a href="https://wa.me/<?= $wa_dosen ?>" target="_blank" class="text-info font-weight-bold"><i class="fab fa-whatsapp me-1"></i> <?= $app->hp_dosen ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="p-2 text-sm bg-gray-100" style="width:20%;">Email Dosen</th>
                                        <td class="p-2 text-sm bg-gray-100">
                                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?= $app->email_dosen ?>" target="_blank" class="text-info font-weight-bold"><i class="far fa-envelope me-1"></i> <?= $app->email_dosen ?></a>
                                        </td>
                                    </tr>
                                    <?php endif ?>
                                    <tr>
                                        <th class="p-2 text-sm" style="width:20%;">Laporan Akhir</th>
                                        <td class="p-2 text-sm">
                                            <?php if ($app->laporan) : ?>
                                                <a href="<?= base_url('assets/laporan/' . $app->laporan) ?>" target="_blank" class="badge badge-info border border-info px-3"><i class="fa fa-file-pdf me-1"></i> Lihat Laporan</a>
                                            <?php else : ?>
                                                <a href="/laporan-akhir" class="text-xs font-weight-bold text-dark"><i class="fa fa-external-link-alt me-1"></i> Pergi ke Menu Laporan</a>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
