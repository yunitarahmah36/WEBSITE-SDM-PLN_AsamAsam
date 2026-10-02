# Dokumen Pengujian Black Box - SIAMAM
**Sistem Informasi Administrasi Praktek Magang (PT PLN Indonesia Power UBP Asam-Asam)**

Metode pengujian yang digunakan pada dokumen ini adalah **Equivalence Partitioning** dan **Boundary Value Analysis**, berfokus pada fungsionalitas sistem dari sudut pandang pengguna (Siswa, Pembimbing, Admin) tanpa melihat struktur kode internal (Black Box Testing).

---

## 1. Modul Autentikasi (Login & Register)

| ID | Skenario Pengujian | Prasyarat | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-AUTH-01 | Login dengan kredensial valid | Memiliki akun aktif | 1. Buka halaman login<br>2. Masukkan email & password benar<br>3. Klik 'Masuk' | Sistem mengarahkan pengguna ke halaman Dashboard sesuai Role (Admin/Siswa/Pembimbing). | [ ] |
| TC-AUTH-02 | Login dengan password salah | Memiliki akun aktif | 1. Buka halaman login<br>2. Masukkan email benar & password salah<br>3. Klik 'Masuk' | Sistem menolak akses dan menampilkan pesan error kredensial tidak valid. | [ ] |
| TC-AUTH-03 | Pendaftaran akun siswa baru | Belum punya akun | 1. Buka halaman register<br>2. Isi form pendaftaran lengkap<br>3. Submit | Akun berhasil dibuat, sistem mengarahkan ke halaman login / verifikasi email. | [ ] |

---

## 2. Modul Profil dan Kelengkapan Data (Siswa)

| ID | Skenario Pengujian | Prasyarat | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-PROF-01 | Melengkapi data profil valid | Login sebagai Siswa | 1. Masuk menu Profil<br>2. Isi form biodata<br>3. Upload dokumen (PDF) & foto (JPG/PNG)<br>4. Simpan | Data tersimpan ke database, file berhasil diunggah, tombol pendaftaran divisi magang terbuka. | [ ] |
| TC-PROF-02 | Upload dokumen format tidak sesuai | Login sebagai Siswa | 1. Upload KTP dengan format `.docx` atau file size > batas maksimal<br>2. Simpan | Sistem menolak upload dan menampilkan pesan error validasi format/ukuran file. | [ ] |

---

## 3. Modul Pendaftaran Magang (Siswa & Admin)

| ID | Skenario Pengujian | Prasyarat | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-MAG-01 | Melamar divisi magang | Profil sudah lengkap | 1. Masuk menu Divisi Magang<br>2. Pilih divisi yang berstatus "BUKA"<br>3. Klik Daftar | Lamaran berhasil masuk dengan status "Pending". | [ ] |
| TC-MAG-02 | Melamar saat kuota penuh | Profil lengkap | 1. Buka menu Divisi Magang<br>2. Cari divisi dengan sisa kuota = 0 | Tombol daftar *disabled* (tidak bisa diklik) atau badge berubah menjadi "Penuh". | [ ] |
| TC-MAG-03 | Admin menerima lamaran | Login sebagai Admin | 1. Masuk menu Pendaftaran Siswa<br>2. Pilih lamaran "Pending"<br>3. Klik "Terima", atur pembimbing dan upload surat balasan | Status lamaran berubah menjadi "Accepted", notifikasi email terkirim ke siswa. | [ ] |
| TC-MAG-04 | Notifikasi Email Gagal | Login sebagai Admin | 1. Matikan koneksi SMTP/internet server sementara<br>2. Terima lamaran siswa | Status lamaran tetap berubah (update data berhasil), tetapi UI memunculkan peringatan/error detail bahwa pengiriman email notifikasi gagal. | [ ] |

---

## 4. Modul Materi Keselamatan & K3

| ID | Skenario Pengujian | Prasyarat | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-K3-01 | Validasi prasyarat logbook & presensi | Siswa berstatus Accepted, belum lulus K3 | 1. Login sebagai siswa<br>2. Klik menu Daftar Hadir atau Logbook | Sistem memblokir akses dan memunculkan peringatan "Akses Terkunci: Prasyarat Keselamatan", dan memaksa ke menu Materi K3. | [ ] |
| TC-K3-02 | Menyelesaikan modul K3 | Siswa berstatus Accepted | 1. Masuk menu Materi K3<br>2. Scroll & baca dokumen<br>3. Centang persetujuan & klik Selesai | Akses Daftar Hadir dan Laporan Logbook terbuka secara otomatis. | [ ] |

---

## 5. Modul Kegiatan Harian (Absensi & Logbook)

| ID | Skenario Pengujian | Prasyarat | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-LOG-01 | Isi daftar hadir - Hadir | Siswa Aktif, Lulus K3 | 1. Buka form Daftar Hadir<br>2. Pilih "Hadir", isi jam masuk & keluar, isi kegiatan, upload bukti foto<br>3. Submit | Data logbook/absensi tersimpan, muncul di menu Logbookku dengan status Pending. | [ ] |
| TC-LOG-02 | Isi daftar hadir - Alfa/Sakit/Izin | Siswa Aktif, Lulus K3 | 1. Buka form Daftar Hadir<br>2. Pilih "Alfa" atau "Sakit" | Input jam dan kegiatan otomatis *disabled*. Hanya keterangan yang disubmit. | [ ] |
| TC-LOG-03 | Validasi logbook oleh pembimbing | Login Pembimbing | 1. Buka dashboard pembimbing<br>2. Lihat logbook siswa<br>3. Klik validasi/setuju | Status logbook siswa berubah menjadi "Disetujui" (Approved). | [ ] |

---

## 6. Modul Evaluasi, Laporan Akhir & Sertifikat

| ID | Skenario Pengujian | Prasyarat | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-EVAL-01 | Upload laporan akhir | Logbook lengkap | 1. Login Siswa<br>2. Masuk menu Laporan Akhir<br>3. Upload dokumen PDF laporan<br>4. Submit | Dokumen terkirim ke sistem untuk divalidasi oleh pembimbing/admin. | [ ] |
| TC-EVAL-02 | Input Nilai Siswa | Laporan Akhir ada | 1. Login Pembimbing<br>2. Buka menu Nilai Siswa<br>3. Input angka (0-100) pada tiap aspek penilaian<br>4. Simpan | Nilai rata-rata dan predikat dikalkulasi otomatis dan tersimpan di database. | [ ] |
| TC-EVAL-03 | Cetak Sertifikat | Status = Selesai, Nilai sudah ada | 1. Login Siswa / Admin<br>2. Klik Cetak Sertifikat / Unduh | Browser mengunduh file Sertifikat PDF (DomPDF) yang memuat nama, NIM, dan transkrip nilai. | [ ] |

---

## 7. Modul Manajemen Admin (Fitur Export & Import)

| ID | Skenario Pengujian | Prasyarat | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|---|---|
| TC-MGT-01 | Export Data Siswa ke PDF | Login Admin | 1. Buka Manajemen Siswa<br>2. Klik tombol "Export PDF" | Browser mengunduh dokumen `.pdf` berisi tabel daftar siswa magang beserta detail masa magang dan status. | [ ] |
| TC-MGT-02 | Export Data Siswa ke Excel | Login Admin | 1. Buka Manajemen Siswa<br>2. Klik tombol "Export Excel" | Browser mengunduh dokumen `.xlsx` yang memuat data terstruktur seluruh siswa aktif & non-aktif. | [ ] |
| TC-MGT-03 | Import Data via Excel | Login Admin | 1. Buka Manajemen Siswa<br>2. Klik Import Excel<br>3. Upload file sesuai format `dumy_siswa.xlsx`<br>4. Submit | Data berhasil masuk secara bulk ke database sistem tanpa error constraint. | [ ] |

---
**Catatan Pengujian:**
*  *Status* dapat diisi dengan tanda centang (✅) jika **Pass**, atau silang (❌) jika **Fail** setelah dilakukan pengujian langsung terhadap aplikasi di server `siamam.maxinide.com`.
*  *Bug/Defect* yang ditemukan selama pengujian harus dicatat dalam defect tracker (atau catatan terpisah) dengan merujuk pada *Test Case ID* yang relevan.
