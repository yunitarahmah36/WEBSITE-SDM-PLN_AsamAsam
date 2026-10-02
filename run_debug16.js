const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.readFile('domains/maxinide.com/public_html/siamam/app/Config/Routes.php', 'utf8', (err, data) => {
      if (err) throw err;
      const lines = data.split('\n').filter(l => l.includes('/man/siswa') || l.includes('/application'));
      console.log(lines.join('\n'));
      conn.end();
    });
  });
}).connect({
  host: '156.67.222.86',
  port: 65002,
  username: 'u535103328',
  password: 'g0Ms?uAE274hsd##'
});
