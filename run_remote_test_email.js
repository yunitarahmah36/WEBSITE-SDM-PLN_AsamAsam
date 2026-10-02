const { Client } = require('ssh2');
const fs = require('fs');

const conn = new Client();

conn.on('ready', () => {
  console.log('Client :: ready');
  conn.sftp((err, sftp) => {
    if (err) throw err;
    
    const remotePath = 'domains/maxinide.com/public_html/siamam/test_email.php';
    sftp.fastPut('test_email.php', remotePath, (err) => {
      if (err) throw err;
      console.log('Uploaded test_email.php');
      
      // Execute php test_email.php
      conn.exec('php domains/maxinide.com/public_html/siamam/test_email.php', (err, stream) => {
        if (err) throw err;
        
        let output = '';
        stream.on('close', (code, signal) => {
          console.log(`Command closed with code ${code}`);
          console.log('--- PHP Output ---');
          console.log(output);
          console.log('------------------');
          
          // Delete remote test_email.php
          sftp.unlink(remotePath, (err) => {
            if (err) console.error('Failed to delete remote test_email.php:', err);
            else console.log('Deleted remote test_email.php');
            conn.end();
          });
        }).on('data', (data) => {
          output += data;
        }).stderr.on('data', (data) => {
          console.error('PHP STDERR:', data.toString());
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
