const { Client } = require('ssh2');
const fs = require('fs');

const conn = new Client();
const localFile = 'app/Config/Auth.php';
const remoteFile = 'domains/maxinide.com/public_html/siamam/app/Config/Auth.php';

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.fastPut(localFile, remoteFile, (err) => {
      if (err) throw err;
      console.log('Uploaded Auth.php');
      conn.end();
    });
  });
}).connect({
  host: '156.67.222.86',
  port: 65002,
  username: 'u535103328',
  password: 'g0Ms?uAE274hsd##'
});
