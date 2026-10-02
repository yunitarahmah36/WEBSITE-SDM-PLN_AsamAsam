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
      uploadFile('app/Controllers/AuthController.php', basePath + 'app/Controllers/AuthController.php')
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
