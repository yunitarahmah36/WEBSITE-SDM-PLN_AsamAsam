$password = "g0Ms?uAE274hsd##"
$command = "cat domains/siamam.maxinide.com/public_html/writable/logs/log-2026-05-22.log | tail -n 50"
$ssh = New-Object System.Diagnostics.ProcessStartInfo
$ssh.FileName = "ssh"
$ssh.Arguments = "-p 65002 -o StrictHostKeyChecking=no -o PreferredAuthentications=password -o PubkeyAuthentication=no u535103328@156.67.222.86 `"$command`""
$ssh.RedirectStandardInput = $true
$ssh.RedirectStandardOutput = $true
$ssh.RedirectStandardError = $true
$ssh.UseShellExecute = $false
$ssh.CreateNoWindow = $true

Write-Host "Starting SSH process..."
$p = [System.Diagnostics.Process]::Start($ssh)
Write-Host "Waiting for password prompt..."
Start-Sleep -Seconds 5
$p.StandardInput.WriteLine($password)
Write-Host "Password sent."
$p.StandardInput.Close()

Write-Host "Reading output..."
$stdout = $p.StandardOutput.ReadToEnd()
$stderr = $p.StandardError.ReadToEnd()
$p.WaitForExit()

Write-Host "STDOUT: $stdout"
Write-Host "STDERR: $stderr"
Write-Host "Exit Code: $($p.ExitCode)"

