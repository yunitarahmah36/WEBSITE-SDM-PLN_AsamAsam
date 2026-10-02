const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  conn.sftp((err, sftp) => {
    if (err) throw err;
    const date = new Date();
    // Use the actual date from the logs. Since today is 2026-07-05, we fetch the log for today.
    // However, the timezone is UTC+7. Let's list the directory.
    conn.exec('ls -la domains/maxinide.com/public_html/siamam/writable/logs', (err, stream) => {
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
}).connect({
  host: '156.67.222.86',
  port: 65002,
  username: 'u535103328',
  password: 'g0Ms?uAE274hsd##'
});
