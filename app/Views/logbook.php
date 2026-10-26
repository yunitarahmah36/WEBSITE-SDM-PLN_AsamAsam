<?= $this->extend('main'); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card card-body">
            <h6 class="m-0 p-0">Daftar Siswa Magang</h6>

            <div class="mt-3">
                <?php if (empty($siswas)) : ?>
                    <div class="text-center text-muted py-4">
                        <i class="material-icons opacity-6" style="font-size: 2.5rem;">group_off</i>
                        <p class="mb-0 mt-2 text-sm">Belum ada siswa terdaftar pada divisi ini.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($siswas as $s) : ?>
                        <a href="<?= base_url('/logbook/' . $idt . '/' . $s->nim) ?>">
                            <div class="card card-body p-2 bg-gray-200 shadow-none rounded px-4 mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="mb-0 <?= getStatusSiswa($s->sid) == 'selesai' ? 'text-info' : '' ?>"><b><?= esc($s->nama) ?></b></div>
                                        <span class="text-sm <?= getStatusSiswa($s->sid) == 'selesai' ? 'text-info' : '' ?>"><?= esc($s->nim) ?></span>
                                    </div>
                                    <div>
                                        <?php if (getStatusSiswa($s->sid) == 'selesai' || ($s->status ?? '') == 'selesai') : ?>
                                            <span class="badge bg-gradient-success text-xxs px-2 py-1">Selesai</span>
                                        <?php elseif (getStatusSiswa($s->sid) == 'unfinish' || ($s->status ?? '') == 'unfinish') : ?>
                                            <span class="badge bg-gradient-secondary text-xxs px-2 py-1">Tidak Aktif</span>
                                        <?php else : ?>
                                            <span class="badge bg-gradient-info text-xxs px-2 py-1">Aktif</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach ?>
                <?php endif ?>
            </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>