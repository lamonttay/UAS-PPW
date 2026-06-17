# Momentask - Todo List Application

**Mata Kuliah:** Praktikum Pemrograman Web 1 (SVPL214208)  
**Tugas Proyek Ujian Akhir Semester**

Momentask adalah aplikasi berbasis web (PHP & MySQL) untuk memanajemen tugas (To-Do List) dengan antarmuka pengguna yang bersih, responsif, dan interaktif.

## Fitur Utama & Pemenuhan Kriteria
- **Dashboard & Landing Page**: Landing page responsif (`index.php`), dan Dashboard yang menampilkan persentase penyelesaian tugas serta kalender fungsional.
- **Manajemen Tugas (CRUD)**: Create, Read, Update, Delete untuk tiap task. Dilengkapi dengan filter pencarian dan **Pagination**.
- **Kategori & Activity Log**: Penggunaan MySQL `JOIN` dari minimal 3 tabel (`activity_log.php`).
- **Responsive & Modern UI**: Menggunakan komponen Bootstrap Grid dan custom CSS. Mendukung layar Desktop (1440px) hingga Mobile (375px).
- **Keamanan Lanjut**: Registrasi/Login menggunakan `session_start()`, `password_hash()`, verifikasi input via `htmlspecialchars()`, dan pengecualian kredensial via `.gitignore`.
- **Interaksi JavaScript Murni**: Validasi form kustom sisi klien menggunakan Vanilla JS (`addEventListener`), AJAX Task toggling, serta peringatan pop-up DOM `confirm()` saat mengedit/menghapus tugas.

## Persyaratan Sistem
- XAMPP / Laragon (PHP 7.4+ atau 8.x)
- MySQL / MariaDB
- Browser modern (Chrome, Firefox, Safari)

## Cara Instalasi & Menjalankan
1. Clone repositori ini ke dalam folder `htdocs` (XAMPP) atau `www` (Laragon).
   ```bash
   git clone https://github.com/username/momentask.git
   ```
2. Jalankan service Apache dan MySQL melalui Control Panel XAMPP/Laragon.
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`) dan buat sebuah database baru dengan nama `momentum_db`.
4. Lakukan **Import** pada file `database.sql` yang berada di direktori *root* proyek ini ke dalam database tersebut.
5. Buka dan konfigurasi file `includes/config.php` (Jika Anda tidak menemukannya, buatlah file ini dengan meniru contoh kredensial untuk host lokal Anda, karena file ini diabaikan oleh git demi keamanan).
6. Buka *browser* dan akses aplikasi melalui `http://localhost/todo-list-app/`.

## Struktur Direktori
- `assets/` : Menyimpan CSS, JavaScript custom, dan Gambar.
- `includes/` : File komponen (Header, Footer, Auth) dan konfigurasi DB (`config.php`).
- `pages/` : Halaman-halaman fitur utama (dashboard, add_task, edit_task, dll).
- `index.php` : Landing page utama untuk pengunjung yang belum login.
- `database.sql` : Ekspor skema struktur dan sample data awal database.

## Dokumentasi Visual (Screenshot)
*(Screenshot disediakan secara terpisah di folder pelaporan akhir atau lampirkan di sini menggunakan format markdown image `![Deskripsi](path/to/image.png)`)*

- **Halaman Beranda (Landing Page)**: Layout Responsif Bootstrap
- **Dashboard & Daftar Tugas**: Menampilkan list card Pagination, dan AJAX toggle progress bar.
- **Form Tambah & Edit**: Pop-up konfirmasi dan Vanilla JS validation sebelum pengiriman data.
- **Tampilan Mobile View**: Uji responsivitas lebar layar 375px.

---
Dikembangkan untuk memenuhi spesifikasi teknis Ujian Praktikum Pemrograman Web 1.
