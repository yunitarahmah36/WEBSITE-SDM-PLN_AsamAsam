<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// MythAuth Routes
$routes->group('', ['namespace' => 'App\Controllers'], static function ($routes) {
    $config         = config(Auth::class);
    $reservedRoutes = $config->reservedRoutes;

    // Login/out
    $routes->get($reservedRoutes['login'], 'AuthController::login', ['as' => $reservedRoutes['login']]);
    $routes->post($reservedRoutes['login'], 'AuthController::attemptLogin');
    $routes->get($reservedRoutes['logout'], 'AuthController::logout');

    // Registration
    $routes->get($reservedRoutes['register'], 'AuthController::register', ['as' => $reservedRoutes['register']]);
    $routes->post($reservedRoutes['register'], 'AuthController::attemptRegister');

    // Activation
    $routes->get($reservedRoutes['activate-account'], 'AuthController::activateAccount', ['as' => $reservedRoutes['activate-account']]);
    $routes->get($reservedRoutes['resend-activate-account'], 'AuthController::resendActivateAccount', ['as' => $reservedRoutes['resend-activate-account']]);

    // Forgot/Resets
    $routes->get($reservedRoutes['forgot'], 'AuthController::forgotPassword', ['as' => $reservedRoutes['forgot']]);
    $routes->post($reservedRoutes['forgot'], 'AuthController::attemptForgot');
    $routes->get($reservedRoutes['reset-password'], 'AuthController::resetPassword', ['as' => $reservedRoutes['reset-password']]);
    $routes->post($reservedRoutes['reset-password'], 'AuthController::attemptReset');
});

// Custom Placeholder
$routes->addPlaceholder('nis', '[0-9]{8}');
$routes->addPlaceholder('username', '[0-9]{8}');
$routes->addPlaceholder('dotnis', '[0-9]{2}\.[0-9]{6}');

// Routes Utama
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Home::index');
$routes->get('/profile', 'Home::profile');

$routes->get('/application', 'Home::application', ['filter' => 'role:siswa,admin,pembimbing']);
$routes->get('/status-lamaran', 'Home::status_lamaran', ['filter' => 'role:siswa']);

$routes->get('/kehadiran', 'Home::daftarHadir', ['filter' => 'role:siswa']);
$routes->get('/kehadiran/(:num)', 'Home::daftarHadir/$1', ['filter' => 'role:siswa']);
$routes->post('/kehadiran/store', 'LogBook::store', ['filter' => 'role:siswa']);
$routes->get('/logbookku', 'Home::logbookku', ['filter' => 'role:siswa']);
$routes->get('/logbookku/export', 'Home::exportExcelLogbook', ['filter' => 'role:siswa']);

$routes->get('/tempat', 'Home::tempat', ['filter' => 'role:siswa']);
$routes->post('/tempat/daftar', 'Application::daftar', ['filter' => 'role:siswa']);
$routes->get('/laporan-akhir', 'Home::laporan_akhir', ['filter' => 'role:siswa']);

$routes->get('/logbook/(:num)', 'Home::logbooks/$1', ['filter' => 'role:pembimbing,admin']);
$routes->get('/logbook/(:num)/(:segment)', 'Home::logbook_siswa/$1/$2', ['filter' => 'role:pembimbing,admin']);
$routes->post('/logbook/status/update', 'LogBook::status_update', ['filter' => 'role:pembimbing,admin']);

// =========================================================
// MANAJEMEN (ADMIN)
// =========================================================
$routes->get('/man/tempat', 'Home::man_tempat', ['filter' => 'role:admin']);
$routes->get('/man/pembimbing', 'Home::man_pembimbing', ['filter' => 'role:admin']);
$routes->get('/man/instansi', 'Home::man_instansi', ['filter' => 'role:admin']);  // <-- DIUBAH
$routes->get('/man/siswa', 'Home::man_siswa', ['filter' => 'role:admin,pembimbing']);
$routes->get('/settings', 'Home::settings', ['filter' => 'role:admin']);

// =========================================================
// BIMBINGAN INSTANSI (DULU DOSEN)  <-- DIUBAH
// =========================================================
$routes->get('/bimbingan-instansi/logbook/(:segment)', 'Home::logbook_bimbingan_instansi/$1', ['filter' => 'role:instansi,admin']);
$routes->get('/bimbingan-instansi', 'Home::bimbingan_instansi', ['filter' => 'role:instansi,admin']);

// Pengumuman
$routes->group('pengumuman', function($p) {
    $p->get('/', 'Pengumuman::Index');
    $p->get('get/(:num)', 'Pengumuman::get/$1');
    $p->post('store', 'Pengumuman::store');
    $p->delete('destroy/(:num)', 'Pengumuman::destroy/$1');
});

// Jurusan
$routes->group('jurusan', function ($j) {
    $j->post('store', 'Jurusan::store');
    $j->post('update', 'Jurusan::update');
    $j->delete('destroy/(:num)', 'Jurusan::destroy/$1');
});

// Angkatan
$routes->group('angkatan', function ($a) {
    $a->post('store', 'Angkatan::store');
    $a->delete('destroy/(:num)', 'Angkatan::destroy/$1');
});

// Nilai
$routes->group('nilai', function ($n) {
    $n->get('/', 'Home::nilai', ['filter' => 'role:siswa']);
    $n->post('store', 'Nilai::store');
    $n->post('update', 'Nilai::update');
    $n->delete('destroy/(:num)', 'Nilai::destroy/$1');
});

// Siswa
$routes->group('siswa', function ($s) {
    $s->get('add', 'Siswa::index');
    $s->get('edit/(:segment)', 'Home::siswa_edit/$1');
    $s->post('save/excel', 'Siswa::save_excel');
    $s->post('upload/laporan', 'Siswa::upload_laporan');
    $s->post('finish_without_report', 'Siswa::finish_without_report');
    $s->post('ceknis', 'Siswa::ceknis');
    $s->post('resetpass', 'Siswa::resetPassword');
    $s->post('store', 'Siswa::store');
    $s->post('update', 'Siswa::update');
    $s->post('profile/update', 'Siswa::profile_update');
    $s->post('approve_laporan', 'Siswa::approve_laporan', ['filter' => 'role:pembimbing,instansi,admin']);  // <-- DIUBAH
    $s->post('activate/(:num)', 'Siswa::activate/$1', ['filter' => 'role:admin']);
    $s->post('upload_pernyataan', 'Siswa::upload_pernyataan');
    $s->post('upload_surat_balasan', 'Siswa::upload_surat_balasan', ['filter' => 'role:admin,pembimbing']);
    $s->delete('destroy/(:num)', 'Siswa::destroy/$1');
    $s->get('export/excel', 'Siswa::exportExcel', ['filter' => 'role:admin']);
    $s->get('export/pdf', 'Siswa::exportPdf', ['filter' => 'role:admin']);
});

// User (untuk CRUD user/pengelola oleh admin)
$routes->group('user', function ($u) {
    $u->post('store', 'User::store');
    $u->post('profile/update', 'User::profile_update');
    $u->post('update', 'User::update');
    $u->post('reset_pass', 'User::reset_pass');
    $u->delete('destroy/(:num)', 'User::destroy/$1');
});

// Tempat Magang
$routes->group('tempat', function ($t) {
    $t->get('edit/(:num)', 'Home::tempat_edit/$1');
    $t->post('store', 'TempatMagang::store');
    $t->post('update', 'TempatMagang::update');
    $t->post('status/update', 'TempatMagang::status_update');
    $t->delete('destroy/(:num)', 'TempatMagang::destroy/$1');
});

// Materi K3
$routes->group('materi-k3', ['filter' => 'role:siswa,admin,pembimbing,instansi'], function ($mk) {  // <-- DIUBAH
    $mk->get('/', 'MateriK3::index');
    $mk->post('store', 'MateriK3::store', ['filter' => 'role:admin']);
    $mk->post('update/(:num)', 'MateriK3::update/$1', ['filter' => 'role:admin']);
    $mk->post('complete', 'MateriK3::complete');
    $mk->delete('destroy/(:num)', 'MateriK3::destroy/$1', ['filter' => 'role:admin']);
});

// Sertifikat
$routes->get('sertifikat/download/(:num)', 'Sertifikat::download/$1', ['filter' => 'role:siswa,admin,pembimbing']);
$routes->get('sertifikat-kelola', 'Sertifikat::kelola', ['filter' => 'role:admin,pembimbing']);
$routes->post('sertifikat/update-mode', 'Sertifikat::updateMode', ['filter' => 'role:admin,pembimbing']);
$routes->post('sertifikat/upload', 'Sertifikat::upload', ['filter' => 'role:admin,pembimbing']);

// Application / Lamaran
$routes->group('application', ['filter' => 'role:admin,pembimbing,siswa'], function ($app) {
    $app->post('daftar', 'Application::daftar');
    $app->post('status/update', 'Application::update', ['filter' => 'role:admin,pembimbing']);
    $app->put('(:num)', 'Application::update/$1', ['filter' => 'role:admin,pembimbing']);
    $app->delete('destroy/(:num)', 'Application::destroy/$1', ['filter' => 'role:admin,pembimbing']);
    $app->post('upload_template', 'Application::upload_template', ['filter' => 'role:admin,pembimbing']);
});

// Catatan Instansi (dulu dosen)  <-- DIUBAH
$routes->post('/logbook/catatan-instansi/update', 'LogBook::add_catatan_instansi', ['filter' => 'role:instansi,admin']);

// Laporan
$routes->group('laporan', ['filter' => 'role:admin'], function($l) {
    $l->get('/', 'Home::laporan');
    $l->post('show', 'Laporan::index');
});

// Pesan  <-- DIUBAH
$routes->group('pesan', ['filter' => 'role:siswa,admin,pembimbing,instansi'], function($routes) {
    $routes->get('/', 'Pesan::index');
    $routes->get('(:num)', 'Pesan::index/$1');
    $routes->post('send', 'Pesan::send');
    $routes->post('updateMsg', 'Pesan::updateMsg');
    $routes->post('deleteMsg', 'Pesan::deleteMsg');
    $routes->get('poll/(:num)', 'Pesan::poll/$1');
    $routes->get('unread', 'Pesan::unread');
});

// Alumni
$routes->get('/alumni', 'Alumni::index', ['filter' => 'role:admin']);
$routes->post('/alumni/update/(:segment)', 'Alumni::update/$1', ['filter' => 'role:admin']);
$routes->post('/alumni/reset/(:segment)', 'Alumni::reset/$1', ['filter' => 'role:admin']);

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}