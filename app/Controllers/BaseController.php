<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['helperku', 'auth'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        // checkNowAndPeroid();

        $session = \Config\Services::session();

        // Auto-delete inactive student accounts after 7 days
        $db = \Config\Database::connect();
        $studentsToDelete = $db->query("
            SELECT s.user_id 
            FROM siswa s 
            WHERE s.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
              AND NOT EXISTS (
                  SELECT 1 FROM lamaran l 
                  WHERE l.id_siswa = s.id 
                    AND l.status IN ('accepted', 'selesai')
              )
              AND NOT EXISTS (
                  SELECT 1 FROM lamaran l 
                  WHERE l.id_siswa = s.id 
                    AND l.updated_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
              )
        ")->getResult();

        if (!empty($studentsToDelete)) {
            $userIds = array_column($studentsToDelete, 'user_id');
            // Delete from users (cascades to siswa and other child tables)
            $db->table('users')->whereIn('id', $userIds)->delete();
        }
        
        // Cek lisensi setiap 1 jam sekali agar aplikasi tidak lambat
        $last_check = $session->get('license_last_check');
        $is_locked = $session->get('license_is_locked');

        if (!$last_check || time() - $last_check > 5) { // Sementara diubah ke 5 detik untuk testing
            // URL API pengecekan di server Anda (tambahkan ?t=time() untuk menembus cache server)
            $url = 'https://maxinide.com/siamam/public/api_lisensi.php?t=' . time(); 
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2); // Timeout 2 detik
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Memperbaiki error cURL SSL di XAMPP Windows
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['status']) && $data['status'] === 'kunci') {
                    $session->set('license_is_locked', true);
                    $is_locked = true;
                } else {
                    $session->set('license_is_locked', false);
                    $is_locked = false;
                }
            }
            $session->set('license_last_check', time());
        }

        // Jika status terkunci, blokir akses
        if ($is_locked) {
            echo "<div style='text-align:center; margin-top:150px; font-family:sans-serif;'>";
            echo "<h2 style='color:red;'>AKSES SISTEM DIBEKUKAN</h2>";
            echo "<p>Selesaikan pembayaran atau hubungi nomor ini <strong>085762190097</strong> untuk membuka kembali akses.</p>";
            echo "</div>";
            exit;
        }
    }
}
