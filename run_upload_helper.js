const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  conn.sftp((err, sftp) => {
    if (err) throw err;
    sftp.fastPut('app/Helpers/helperku_helper.php', 'domains/maxinide.com/public_html/siamam/app/Helpers/helperku_helper.php', (err) => {
      if (err) throw err;
      console.log("Uploaded helperku_helper.php");
      conn.end();
    });
  });
}).connect({
  host: '156.67.222.86',
  port: 65002,
  username: 'u535103328',
  password: 'g0Ms?uAE274hsd##'
});
