const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.fastPut('debug_bob.php', 'domains/maxinide.com/public_html/siamam/debug_bob.php', (err) => {
      if (err) throw err;
      console.log('Uploaded debug_bob.php');
      conn.exec('cd domains/maxinide.com/public_html/siamam && php debug_bob.php', (err, stream) => {
        if (err) throw err;
        stream.on('close', (code, signal) => {
          conn.end();
        }).on('data', (data) => {
          console.log('STDOUT: ' + data);
        }).stderr.on('data', (data) => {
          console.log('STDERR: ' + data);
        });
      });
    });
  });
}).connect({
  host: '156.67.222.86',
  port: 65002,
  username: 'u535103328',
  password: 'g0Ms?uAE274hsd##'
});
