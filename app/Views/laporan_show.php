<!-- DEBUG-VIEW START 1 APPPATH/Views/surat.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    <title>Laporan</title>

    <style>
        @media print {
            .pagebreak {
                clear: both !important;
                page-break-after: always !important;
            }
        }

        .logo-kab {
            width: 70px;
            left: 0;
        }

        .judul_surat {
            text-transform: uppercase;
        }

        #kop_surat {
            padding: 0.5em 1em;
            margin-bottom: 1em;
        }

        .paragraph {
            padding-bottom: 10px;
        }

        .ttd {
            margin-top: 5em;
            margin-right: 2.5em;
        }
    </style>
</head>

<body onload="print()">
    <section class="sheet padding-10mm">
        <div id="kop_surat" class="border-bottom border-4 border-dark pb-2 mb-4">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 100px; vertical-align: middle; text-align: left;">
                        <img src="/assets/logo-pln.png" style="width: 80px;" alt="main_logo">
                    </td>
                    <td style="text-align: center; vertical-align: middle; padding-right: 80px;">
                        <h4 class="mb-1" style="font-weight: 700; letter-spacing: 1px; font-size: 14pt;">LAPORAN KEGIATAN PRAKERIN</h4>
                        <h3 class="mb-1" style="font-weight: 800; text-transform: uppercase; font-size: 16pt;"><?= $data[0]->instansi ?? 'INSTANSI BELUM DISET' ?></h3>
                        <p class="mb-0" style="line-height: normal; font-size: 10pt;"><?= $data[0]->alamat_siswa ?? 'ALAMAT BELUM DISET' ?></p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="text-center mb-4">
            <h5 class="judul_surat m-0">
                LAPORAN KEGIATAN PRAKERIN SISWA / MAHASISWA
            </h5>
        </div>
        <div class="isi_surat">
            <p class="mb-3" style="text-align: justify;">
                Berdasarkan hasil dari kegiatan prakerin yang dilakukan oleh siswa / mahasiswa <b><?= $data[0]->instansi ?? 'INSTANSI BELUM DISET' ?></b>, yang dilakukan pada tahun <b><?= $tahun_selected ?></b> dengan rincian sebagai berikut :
            </p>

            <p class="mb-3" style="text-align: justify;">
                Jumlah siswa / mahasiswa yang mengikuti kegiatan prakerin sebanyak <b><?= COUNT($data) ?></b> orang, dengan rincian per jumlah orang seperti pada tabel dibawah ini.
            </p>

            <table class="table table-bordered table-sm table-striped text-center">
                <tbody>
                    <tr style="border-bottom: 1px solid #333;">
                        <th style="width:40%;">JUMLAH ORANG</th>
                        <th>JUMLAH SISWA / MAHASISWA</th>
                    </tr>
                    <?php foreach ($kelas as $k => $v) : ?>
                        <tr>
                            <th><?= $k ?></th>
                            <td><?= $v ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <p class="mb-3" style="text-align: justify;">
                Dari hasil kegiatan prakerin tersebut, didapati bahwa status siswa / mahasiswa yang mengikuti kegiatan prakerin adalah sebagai berikut.
            </p>

            <table class="table table-bordered table-sm table-striped text-center">
                <tbody>
                    <tr style="border-bottom: 1px solid #333;">
                        <th style="width:40%;">STATUS</th>
                        <th>JUMLAH SISWA / MAHASISWA</th>
                    </tr>
                    <?php foreach ($status as $k => $v) : ?>
                        <tr>
                            <th><?= $k ?></th>
                            <td><?= $v ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <p class="mb-3" style="text-align: justify;">
                Adapun nama-nama siswa / mahasiswa yang mengikuti kegiatan prakerin terlampir pada lampiran surat ini. <br>
                Demikian surat keterangan ini kami buat untuk dapat dipergunakan sebagaimana mestinya.
            </p>


            <div class="ttd">
                <p class="text-center mb-3">
                    banjarmasin, <?= date("d F Y") ?> <br>
                    <b>Mengetahui :</b>
                </p>
                <div class="d-flex flex-row align-items-center justify-content-between">
                    <div class="text-center">
                        <p>Ketua Kegiatan Magang</p>


                        <!-- Nama Kepala Desa -->
                        <p style="margin-top: 100px;"><strong>----------------------</strong></p>
                    </div>

                    <div class="text-center">
                        <p>Kepala Sekolah</p>


                        <!-- Nama Kepala Desa -->
                        <p style="margin-top: 100px;"><strong>Nur Afni Indasari, S.Pd</strong></p>
                    </div>
                </div>
            </div>

            <div class="pagebreak"> </div>

            <div class="mt-5">
                <p>
                    Lampiran 1
                </p>
                 <h5 class="mb-0 text-center">DAFTAR NAMA SISWA / MAHASISWA PRAKERIN</h5>
                <h6 class="mb-3 text-center"><b>Tahun</b> : <?= $tahun_selected ?> | <b>Jurusan</b> : <?= $jurusan_selected == "" ? "Semua" : $jurusan_selected ?></h6>

                <table class="table table-bordered table-sm table-striped">
                    <tbody>
                        <tr style="border-bottom: 1px solid #333;">
                            <th class="text-center">No</th>
                            <th>NIM / NIS</th>
                            <th>Nama</th>
                            <th>Pembimbing</th>
                            <th>Jumlah Orang</th>
                            <th>Tanggal Magang</th>
                            <th>Status</th>
                        </tr>
                        <?php $no = 1; ?>
                        <?php foreach ($data as $d) : ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= getIdentityLabel($d) ?>: <?= $d->nim ?></td>
                                <td><?= $d->nama ?></td>
                                <td><?= $d->nama_pembimbing ?></td>
                                <td><?= $d->kelas ?></td>
                                <td>
                                    <?= $d->tgl_masuk ? date('d/m/Y', strtotime($d->tgl_masuk)) : '-' ?>
                                    s/d
                                    <?= $d->tgl_selesai ? date('d/m/Y', strtotime($d->tgl_selesai)) : '-' ?>
                                </td>
                                <td>
                                    <?php if ($d->laporan == null && date("Y-m-d") > $d->tgl_selesai) : ?>
                                        unfinished
                                    <?php else : ?>
                                        <?= strtolower($d->status) == 'selesai' ? 'finished' : strtolower($d->status) ?>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

</html>