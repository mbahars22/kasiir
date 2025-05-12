
# Sistem Keuangan Madrasah

Aplikasi berbasis PHP untuk mengelola keuangan madrasah/sekolah Islam. Aplikasi ini memungkinkan pengguna untuk melacak pembayaran siswa, mengelola dana komite, dan menghasilkan berbagai laporan keuangan.

## Fitur

- **Multi-user dengan peran berbeda**: Admin, Komite, dan Pengamat
- **Data Master**: Siswa, Jenis Pembayaran, Angkatan & Kelas
- **Keuangan**: Catat pembayaran, atur diskon/biaya siswa, kelola dana komite
- **Laporan**: Cetak laporan keuangan siswa, laporan dana komite, statistik pembayaran
- **Multi-tahun ajaran**: Kelola data untuk beberapa tahun ajaran sekaligus
- **Ekspor Data**: Unduh data dalam format Excel

## Prasyarat

- Web server (misal: Apache)
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Browser web modern

## Instalasi

1. **Siapkan web server**:
   - Instal XAMPP atau paket web server lainnya sesuai sistem operasi Anda
   - Pastikan PHP dan MySQL berjalan dengan baik

2. **Ekstrak atau clone aplikasi**:
   - Ekstrak file aplikasi ke folder `htdocs` (untuk XAMPP) atau folder root web server Anda
   - Atau clone dari repository jika menggunakan Git

3. **Buat database**:
   - Buka browser dan akses PHPMyAdmin (http://localhost/phpmyadmin)
   - Buat database baru dengan nama `keuangan_madrasah`

4. **Jalankan setup**:
   - Akses URL setup: `http://localhost/madrasah-finance/setup.php`
   - Ikuti instruksi untuk mengatur database dan akun awal

5. **Akses aplikasi**:
   - Buka browser dan akses `http://localhost/madrasah-finance/`
   - Gunakan kredensial default untuk login:
     - Admin: admin@madrasah.com / password123
     - Komite: committee@madrasah.com / password123
     - Pengamat: observer@madrasah.com / password123

## Struktur Direktori

```
/madrasah-finance/
├── assets/             # Aset statis (CSS, JS, gambar)
│   ├── css/            # File CSS
│   ├── js/             # File JavaScript
│   └── images/         # Gambar
├── config/             # File konfigurasi
├── controllers/        # Controller aplikasi
├── helpers/            # Helper functions
├── models/             # Model data
├── views/              # Template tampilan
│   ├── auth/           # Tampilan autentikasi
│   ├── dashboard/      # Tampilan dashboard
│   ├── errors/         # Tampilan error
│   ├── layouts/        # Layout utama
│   └── ...             # Tampilan lainnya
├── .htaccess           # Konfigurasi Apache
├── index.php           # Entry point aplikasi
├── setup.php           # Script instalasi
└── README.md           # Dokumentasi
```

## Login Default

1. **Administrator**
   - Username: admin@madrasah.com
   - Password: password123

2. **Komite Sekolah**
   - Username: committee@madrasah.com
   - Password: password123

3. **Pengamat**
   - Username: observer@madrasah.com
   - Password: password123

## Pengembangan Selanjutnya

- Integrasi dengan sistem SMS/WhatsApp untuk notifikasi pembayaran
- Fitur pembayaran online
- Dashboard yang lebih interaktif dengan grafik dan chart
- Aplikasi mobile untuk akses cepat
- Ekspor laporan dalam format PDF

## Kontribusi

Kontribusi untuk perbaikan dan pengembangan aplikasi sangat diterima. Silakan fork repository, lakukan perubahan, dan kirim pull request.

## Lisensi

Aplikasi ini dilisensikan di bawah [MIT License](LICENSE).
