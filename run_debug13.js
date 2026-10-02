const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.fastPut('debug_bob13.php', 'domains/maxinide.com/public_html/siamam/debug_bob13.php', (err) => {
      if (err) throw err;
      conn.exec('cd domains/maxinide.com/public_html/siamam && php debug_bob13.php', (err, stream) => {
        if (err) throw err;
        let output = '';
        stream.on('close', (code, signal) => {
          console.log(output);
          conn.end();
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
