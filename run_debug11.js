const { Client } = require('ssh2');
const fs = require('fs');

const conn = new Client();
const remoteFile = 'domains/maxinide.com/public_html/siamam/app/Config/Filters.php';

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.readFile(remoteFile, 'utf8', (err, data) => {
      if (err) throw err;
      console.log('--- REMOTE FILTERS.PHP ---');
      console.log(data);
      conn.end();
    });
  });
}).connect({
  host: '156.67.222.86',
  port: 65002,
  username: 'u535103328',
  password: 'g0Ms?uAE274hsd##'
});
