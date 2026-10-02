const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  
  // Start SFTP to upload files
  conn.sftp((err, sftp) => {
    if (err) throw err;
    
    const uploadFile = (localPath, remotePath) => {
      return new Promise((resolve, reject) => {
        sftp.fastPut(localPath, remotePath, (err) => {
          if (err) {
            console.error(`Failed to upload ${localPath}: ${err.message}`);
            return resolve(); // Resolve anyway to let other files upload
          }
          console.log(`Uploaded ${localPath} to ${remotePath}`);
          resolve();
        });
      });
    };
    
    const basePath = 'domains/maxinide.com/public_html/siamam/';
    
    Promise.all([
      uploadFile('app/Views/logbook_siswa.php', basePath + 'app/Views/logbook_siswa.php'),
      uploadFile('app/Views/logbook.php', basePath + 'app/Views/logbook.php'),
      uploadFile('app/Views/nilai.php', basePath + 'app/Views/nilai.php'),
      uploadFile('app/Controllers/Sertifikat.php', basePath + 'app/Controllers/Sertifikat.php'),
      uploadFile('app/Views/sertifikat/kelola.php', basePath + 'app/Views/sertifikat/kelola.php'),
      uploadFile('app/Helpers/helperku_helper.php', basePath + 'app/Helpers/helperku_helper.php'),
      uploadFile('app/Views/Auth/register.php', basePath + 'app/Views/Auth/register.php'),
      uploadFile('app/Views/man_siswa.php', basePath + 'app/Views/man_siswa.php'),
      uploadFile('app/Views/profile.php', basePath + 'app/Views/profile.php'),
      uploadFile('app/Views/main.php', basePath + 'app/Views/main.php'),
      uploadFile('app/Views/sertifikat/template.php', basePath + 'app/Views/sertifikat/template.php'),
      uploadFile('app/Views/laporan_show.php', basePath + 'app/Views/laporan_show.php'),
      uploadFile('app/Controllers/Siswa.php', basePath + 'app/Controllers/Siswa.php'),
      uploadFile('app/Views/laporan_akhir.php', basePath + 'app/Views/laporan_akhir.php'),
      uploadFile('app/Config/Routes.php', basePath + 'app/Config/Routes.php'),
      uploadFile('app/Views/application.php', basePath + 'app/Views/application.php'),
      uploadFile('app/Views/siswa_edit.php', basePath + 'app/Views/siswa_edit.php'),
      uploadFile('app/Views/tempat.php', basePath + 'app/Views/tempat.php'),
      uploadFile('app/Controllers/Home.php', basePath + 'app/Controllers/Home.php'),
      uploadFile('app/Models/SiswaModel.php', basePath + 'app/Models/SiswaModel.php'),
      uploadFile('app/Controllers/Application.php', basePath + 'app/Controllers/Application.php'),
      uploadFile('app/Controllers/MateriK3.php', basePath + 'app/Controllers/MateriK3.php'),
      uploadFile('app/Views/materi_k3/index.php', basePath + 'app/Views/materi_k3/index.php'),
      uploadFile('app/Views/man_user.php', basePath + 'app/Views/man_user.php'),
      uploadFile('app/Views/man_tempat.php', basePath + 'app/Views/man_tempat.php'),
      uploadFile('app/Views/kehadiran.php', basePath + 'app/Views/kehadiran.php'),
      uploadFile('app/Controllers/Pesan.php', basePath + 'app/Controllers/Pesan.php'),
      uploadFile('app/Views/pesan.php', basePath + 'app/Views/pesan.php'),
      uploadFile('app/Views/landing.php', basePath + 'app/Views/landing.php'),
      uploadFile('app/Controllers/Alumni.php', basePath + 'app/Controllers/Alumni.php'),
      // Also deploy the views directory and its contents for alumni
      new Promise((resolve) => sftp.mkdir(basePath + 'app/Views/alumni', () => {
          uploadFile('app/Views/alumni/index.php', basePath + 'app/Views/alumni/index.php').then(resolve);
      })),
      // upload the migration file
      uploadFile('app/Database/Migrations/2026-07-03-155112_AddAlumniFieldsToSiswa.php', basePath + 'app/Database/Migrations/2026-07-03-155112_AddAlumniFieldsToSiswa.php')
    ]).then(() => {
      console.log('All files processed!');
      conn.end();
    }).catch(err => {
      console.error('Error uploading files:', err);
      conn.end();
    });
  });
}).connect({
  host: '156.67.222.86',
  port: 65002,
  username: 'u535103328',
  password: 'g0Ms?uAE274hsd##'
});
