const { Client } = require('ssh2');

const conn = new Client();

conn.on('ready', () => {
  conn.sftp((err, sftp) => {
    if (err) throw err;
    const dateStr = '2026-07-07'; // Today's date based on current time
    conn.exec(`tail -n 100 domains/maxinide.com/public_html/siamam/writable/logs/log-${dateStr}.log`, (err, stream) => {
      if (err) throw err;
      let output = '';
      stream.on('close', (code, signal) => {
        if (output) {
            console.log(output);
        } else {
            console.log("No logs for today yet or file not found.");
        }
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
