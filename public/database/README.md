
# Panduan Penggunaan Database Sistem Keuangan Sekolah

## Prasyarat
- XAMPP (versi 7.4 atau lebih baru)
- Browser modern (Chrome, Firefox, Safari, Edge)

## Langkah-langkah Instalasi Database

1. **Instalasi XAMPP**
   - Unduh XAMPP dari [situs resmi](https://www.apachefriends.org/download.html)
   - Instal XAMPP dengan mengikuti petunjuk instalasi

2. **Menjalankan Server**
   - Buka XAMPP Control Panel
   - Start modul Apache dan MySQL
   - Pastikan kedua modul berjalan dengan status berwarna hijau

3. **Mengimpor Database**
   - Buka browser dan akses http://localhost/phpmyadmin/
   - Buat database baru dengan nama `keuangan_sekolah`
   - Pilih tab "Import"
   - Klik "Choose File" dan pilih file `db_schema.sql` yang ada di folder ini
   - Klik "Go" atau "Import" untuk menjalankan impor

4. **Konfigurasi Koneksi Aplikasi**
   - Buka file `.env` pada aplikasi web (jika ada)
   - Sesuaikan konfigurasi database dengan pengaturan berikut:
     ```
     DB_HOST=localhost
     DB_USER=root
     DB_PASS=
     DB_NAME=keuangan_sekolah
     ```

## Struktur Database

Database `keuangan_sekolah` terdiri dari tabel-tabel berikut:

1. **users** - Menyimpan data pengguna sistem
2. **academic_years** - Data tahun ajaran
3. **students** - Data siswa
4. **payment_types** - Jenis-jenis pembayaran
5. **student_fees** - Biaya per siswa (termasuk diskon)
6. **payments** - Riwayat pembayaran
7. **committee_funds** - Dana komite sekolah

## Akses Default

Sistem memiliki tiga pengguna default:

1. **Administrator**
   - Username: admin
   - Password: admin123
   - Role: admin

2. **Komite Sekolah**
   - Username: komite
   - Password: komite123
   - Role: committee

3. **Pengamat**
   - Username: pengamat
   - Password: pengamat123
   - Role: observer

## Backup Database

Untuk melakukan backup database, ikuti langkah-langkah berikut:

1. Buka http://localhost/phpmyadmin/
2. Pilih database `keuangan_sekolah`
3. Klik tab "Export"
4. Pilih format "SQL"
5. Klik "Go" untuk mengunduh file backup

## Restore Database

Untuk mengembalikan database dari backup:

1. Buka http://localhost/phpmyadmin/
2. Buat database baru (jika belum ada) dengan nama `keuangan_sekolah`
3. Pilih database tersebut
4. Klik tab "Import"
5. Pilih file backup SQL yang ingin direstore
6. Klik "Go" untuk memulai proses restore
