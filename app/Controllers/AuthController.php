<?php

namespace App\Controllers;

// use CodeIgniter\Controller;
use CodeIgniter\Session\Session;
use Myth\Auth\Config\Auth as AuthConfig;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

class AuthController extends BaseController
{
    protected $auth;

    /**
     * @var AuthConfig
     */
    protected $config;

    /**
     * @var Session
     */
    protected $session;

    public function __construct()
    {
        // Most services in this controller require
        // the session to be started - so fire it up!
        $this->session = service('session');

        $this->config = config('Auth');
        $this->auth   = service('authentication');
    }

    //--------------------------------------------------------------------
    // Login/out
    //--------------------------------------------------------------------

    /**
     * Displays the login form, or redirects
     * the user to their destination/home if
     * they are already logged in.
     */
    public function login()
    {
        // No need to show a login form if the user
        // is already logged in.
        if ($this->auth->check()) {
            $redirectURL = session('redirect_url') ?? site_url('/');
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirectURL);
        }

        // Set a return URL if none is specified
        $_SESSION['redirect_url'] = session('redirect_url') ?? previous_url() ?? site_url('/');

        return $this->_render($this->config->views['login'], ['config' => $this->config]);
    }

    /**
     * Attempts to verify the user's credentials
     * through a POST request.
     */
    public function attemptLogin()
    {
        $rules = [
            'login'    => 'required',
            'password' => 'required',
        ];
        if ($this->config->validFields === ['email']) {
            $rules['login'] .= '|valid_email';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $login    = $this->request->getPost('login');
        $password = $this->request->getPost('password');
        $remember = (bool) $this->request->getPost('remember');

        // Determine credential type
        $type = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Try to log them in...
        if (! $this->auth->attempt([$type => $login, 'password' => $password], $remember)) {
            return redirect()->back()->withInput()->with('error', $this->auth->error() ?? lang('Auth.badAttempt'));
        }

        // Is the user being forced to reset their password?
        if ($this->auth->user()->force_pass_reset === true) {
            return redirect()->to(route_to('reset-password') . '?token=' . $this->auth->user()->reset_hash)->withCookies();
        }

        $redirectURL = session('redirect_url') ?? site_url('/');
        unset($_SESSION['redirect_url']);

        return redirect()->to($redirectURL)->withCookies()->with('message', lang('Auth.loginSuccess'))->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        if ($this->auth->check()) {
            $this->auth->logout();
        }

        return redirect()->to(site_url('/'));
    }

    //--------------------------------------------------------------------
    // Register
    //--------------------------------------------------------------------

    /**
     * Displays the user registration page.
     */
    public function register()
    {
        // check if already logged in.
        if ($this->auth->check()) {
            return redirect()->back();
        }

        // Check if registration is allowed
        if (! $this->config->allowRegistration) {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }

        // =====================================================
        // AMBIL DATA INSTANSI UNTUK DROPDOWN
        // =====================================================
        $db = \Config\Database::connect();
        
        // Sesuaikan nama tabel 'instansi' dengan nama tabel asli Anda
        // Jika tabel Anda bernama 'manajemen_instansi' atau lainnya, ubah di sini
        $instansi = $db->table('instansi')
                       ->select('id, nama_instansi') // Sesuaikan nama kolomnya
                       ->orderBy('nama_instansi', 'ASC')
                       ->get()
                       ->getResultArray();

        return $this->_render($this->config->views['register'], [
            'config'   => $this->config,
            'instansi' => $instansi,
        ]);
    }

    /**
     * Attempt to register a new user.
     */
    public function attemptRegister()
    {
        // Check if registration is allowed
        if (! $this->config->allowRegistration) {
            return redirect()->back()->withInput()->with('error', lang('Auth.registerDisabled'));
        }

        $users    = model(UserModel::class);
        $siswa    = new \App\Models\SiswaModel();
        $angkatan = new \App\Models\AngkatanModel();

        // Validate basics first since some password rules rely on these fields
        $rules = [
            'username'  => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'nim'       => 'required|alpha_numeric|min_length[5]|max_length[15]|is_unique[siswa.nim]',
            'nama'      => 'required|min_length[3]|max_length[100]',
            'kelas'     => 'required|min_length[2]|max_length[50]',
            'no_hp'     => 'required|min_length[10]|max_length[15]',
            'alamat'    => 'required|min_length[5]|max_length[255]',
            'jurusan'   => 'required|min_length[2]|max_length[100]',
            'instansi'  => 'required|min_length[2]|max_length[100]',
            'tgl_masuk' => 'required|valid_date',
            'tgl_selesai' => 'required|valid_date',
            // Hanya Nama Dosen yang wajib
            'dosen_nama' => 'required|min_length[3]|max_length[150]',
            // File uploads
            'surat_permohonan' => 'uploaded[surat_permohonan]|max_size[surat_permohonan,2048]|ext_in[surat_permohonan,pdf]',
            'cv'        => 'uploaded[cv]|max_size[cv,2048]|ext_in[cv,pdf]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate passwords since they can only be validated properly here
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save the user
        $allowedPostFields = array_merge(['password'], $this->config->validFields, $this->config->personalFields);
        $user              = new User($this->request->getPost($allowedPostFields));

        // Set user to active directly, no admin approval required anymore
        $user->active = 1;
        $user->activate_hash = null;

        // Ensure default group gets assigned
        $groupName = $this->config->defaultUserGroup ?? 'siswa';
        $users = $users->withGroup($groupName);

        if (! $users->save($user)) {
            return redirect()->back()->withInput()->with('errors', $users->errors());
        }

        // Save student detail with manual NIM
        $nim = $this->request->getPost('nim');

        // Handle File Uploads
        $fileSurat = $this->request->getFile('surat_permohonan');
        $fileCv    = $this->request->getFile('cv');

        $suratName = $fileSurat->getRandomName();
        $cvName    = $fileCv->getRandomName();

        $fileSurat->move('assets/berkas/', $suratName);
        $fileCv->move('assets/berkas/', $cvName);

        // =====================================================
        // SIMPAN DATA SISWA
        // Dosen sekarang hanya berupa nama (string), tidak ada relasi
        // =====================================================
        if (! $siswa->insert([
            'nim'          => $nim,
            'user_id'      => $users->getInsertID(),
            'nama'         => $this->request->getPost('nama'),
            'kelas'        => $this->request->getPost('kelas'),
            'no_hp'        => $this->request->getPost('no_hp'),
            'alamat'       => $this->request->getPost('alamat'),
            'jurusan'      => $this->request->getPost('jurusan'),
            'instansi'     => $this->request->getPost('instansi'),
            'dosen_nama'   => $this->request->getPost('dosen_nama'), // <-- Simpan nama dosen langsung
            'tgl_masuk'    => $this->request->getPost('tgl_masuk'),
            'tgl_selesai'  => $this->request->getPost('tgl_selesai'),
            'surat_permohonan' => $suratName,
            'cv'           => $cvName,
        ])) {
            return redirect()->back()->withInput()->with('errors', $siswa->errors());
        }

        if ($this->config->requireActivation !== null) {
            $activator = service('activator');
            $sent      = $activator->send($user);

            if (! $sent) {
                return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
            }

            // Success!
            return redirect()->route('login')->with('message', lang('Auth.activationSuccess'));
        }

        // =====================================================
        // Send email to all admins
        // =====================================================
        try {
            $db = \Config\Database::connect();
            $admins = $db->table('users')
                         ->select('users.email')
                         ->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
                         ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                         ->where('auth_groups.name', 'admin')
                         ->where('users.active', 1)
                         ->get()
                         ->getResultArray();

            if (!empty($admins)) {
                $email = \Config\Services::email();
                $email->setFrom('admin@' . $this->request->getServer('HTTP_HOST'), 'Sistem SIAMAM');

                $adminEmails = array_column($admins, 'email');
                $email->setTo($adminEmails);

                $email->setSubject('Notifikasi: Pendaftaran Mahasiswa Baru');

                $msg  = 'Halo Admin,<br><br>';
                $msg .= 'Ada pendaftar mahasiswa/siswa baru di Sistem Magang dengan rincian:<br>';
                $msg .= '<ul>';
                $msg .= '<li><b>Nama:</b> ' . esc($this->request->getPost('nama')) . '</li>';
                $msg .= '<li><b>NIM:</b> ' . esc($nim) . '</li>';
                $msg .= '<li><b>Institusi:</b> ' . esc($this->request->getPost('instansi')) . '</li>';
                $msg .= '<li><b>Jurusan:</b> ' . esc($this->request->getPost('jurusan')) . '</li>';
                $msg .= '<li><b>Dosen Pembimbing:</b> ' . esc($this->request->getPost('dosen_nama')) . '</li>';
                $msg .= '</ul>';
                $msg .= 'Akun ini telah otomatis aktif. Silakan login ke sistem pada menu <b>Manajemen Data -> Manajemen Siswa</b> untuk meninjau berkas pendaftaran jika diperlukan.<br><br>';
                $msg .= 'Terima kasih.';

                $email->setMessage($msg);
                $email->send();
            }
        } catch (\Exception $e) {
            // Log error or ignore so registration doesn't fail if email fails
            log_message('error', 'Failed to send registration email to admins: ' . $e->getMessage());
        }

        // Success! Redirect with message that registration is successful.
        return redirect()->route('login')->with('message', 'Pendaftaran berhasil. Akun Anda telah aktif, silakan login.');
    }

    //--------------------------------------------------------------------
    // Forgot Password
    //--------------------------------------------------------------------

    public function forgotPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        return $this->_render($this->config->views['forgot'], ['config' => $this->config]);
    }

    public function attemptForgot()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        $rules = [
            'email' => [
                'label' => lang('Auth.emailAddress'),
                'rules' => 'required|valid_email',
            ],
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $users = model(UserModel::class);

        $user = $users->where('email', $this->request->getPost('email'))->first();

        if (null === $user) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        // Save the reset hash /
        $user->generateResetHash();
        $users->save($user);

        $resetter = service('resetter');
        $sent     = $resetter->send($user);

        if (! $sent) {
            return redirect()->back()->withInput()->with('error', $resetter->error() ?? lang('Auth.unknownError'));
        }

        return redirect()->route('reset-password')->with('message', lang('Auth.forgotEmailSent'));
    }

    public function resetPassword()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        $token = $this->request->getGet('token');

        return $this->_render($this->config->views['reset'], [
            'config' => $this->config,
            'token'  => $token,
        ]);
    }

    public function attemptReset()
    {
        if ($this->config->activeResetter === null) {
            return redirect()->route('login')->with('error', lang('Auth.forgotDisabled'));
        }

        $users = model(UserModel::class);

        // First things first - log the reset attempt.
        $users->logResetAttempt(
            $this->request->getPost('email'),
            $this->request->getPost('token'),
            $this->request->getIPAddress(),
            (string) $this->request->getUserAgent()
        );

        $rules = [
            'token'        => 'required',
            'email'        => 'required|valid_email',
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $users->where('email', $this->request->getPost('email'))
            ->where('reset_hash', $this->request->getPost('token'))
            ->first();

        if (null === $user) {
            return redirect()->back()->with('error', lang('Auth.forgotNoUser'));
        }

        // Reset token still valid?
        if (! empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
            return redirect()->back()->withInput()->with('error', lang('Auth.resetTokenExpired'));
        }

        // Success! Save the new password, and cleanup the reset hash.
        $user->password         = $this->request->getPost('password');
        $user->reset_hash       = null;
        $user->reset_at         = date('Y-m-d H:i:s');
        $user->reset_expires    = null;
        $user->force_pass_reset = false;
        $users->save($user);

        return redirect()->route('login')->with('message', lang('Auth.resetSuccess'));
    }

    public function activateAccount()
    {
        $users = model(UserModel::class);

        // First things first - log the activation attempt.
        $users->logActivationAttempt(
            $this->request->getGet('token'),
            $this->request->getIPAddress(),
            (string) $this->request->getUserAgent()
        );

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $user = $users->where('activate_hash', $this->request->getGet('token'))
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()->route('login')->with('error', lang('Auth.activationNoUser'));
        }

        $user->activate();

        $users->save($user);

        return redirect()->route('login')->with('message', lang('Auth.registerSuccess'));
    }

    public function resendActivateAccount()
    {
        if ($this->config->requireActivation === null) {
            return redirect()->route('login');
        }

        $throttler = service('throttler');

        if ($throttler->check(md5($this->request->getIPAddress()), 2, MINUTE) === false) {
            return service('response')->setStatusCode(429)->setBody(lang('Auth.tooManyRequests', [$throttler->getTokentime()]));
        }

        $login = urldecode($this->request->getGet('login'));
        $type  = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $users = model(UserModel::class);

        $user = $users->where($type, $login)
            ->where('active', 0)
            ->first();

        if (null === $user) {
            return redirect()->route('login')->with('error', lang('Auth.activationNoUser'));
        }

        $activator = service('activator');
        $sent      = $activator->send($user);

        if (! $sent) {
            return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
        }

        // Success!
        return redirect()->route('login')->with('message', lang('Auth.activationSuccess'));
    }

    protected function _render(string $view, array $data = [])
    {
        return view($view, $data);
    }
}