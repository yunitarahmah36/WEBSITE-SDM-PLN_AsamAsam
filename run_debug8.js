const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.fastPut('debug_bob8.php', 'domains/maxinide.com/public_html/siamam/debug_bob8.php', (err) => {
      if (err) throw err;
      console.log('Uploaded debug_bob8.php');
      conn.exec('cd domains/maxinide.com/public_html/siamam && php debug_bob8.php', (err, stream) => {
        if (err) throw err;
        let output = '';
        stream.on('close', (code, signal) => {
          conn.end();
          console.log(output);
        }).on('data', (data) => {
          output += data.toString();
        }).stderr.on('data', (data) => {
          console.error(data.toString());
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
