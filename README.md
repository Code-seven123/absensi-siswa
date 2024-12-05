# Tutorial Instalasi Proyek

Panduan ini menjelaskan langkah-langkah untuk menginstal dan mengonfigurasi proyek ini pada sistem Anda. Ikuti langkah-langkah di bawah ini untuk melakukan instalasi dengan benar.

## Prasyarat
Sebelum memulai, pastikan Anda memiliki hal berikut:
1. **PHP** (versi 7.x atau lebih baru).
2. **Composer** (untuk mengelola dependensi PHP).
3. **MySQL** (atau MariaDB).
4. **phpMyAdmin** (untuk mengelola database MySQL).
5. **XAMPP/WAMP/MAMP** atau server lokal lainnya yang mendukung PHP dan MySQL.

---

## Langkah-langkah Instalasi

### 1. **Impor File `backup_database.sql` ke phpMyAdmin**
   Langkah pertama adalah mengimpor database yang sudah tersedia dalam file SQL ke MySQL menggunakan phpMyAdmin:
   
   - **Buka phpMyAdmin**: Akses phpMyAdmin melalui browser, biasanya `http://localhost/phpmyadmin`.
   - **Pilih Database**: Jika Anda belum memiliki database, buat database baru dengan memilih "New" dan beri nama database yang sesuai.
   - **Impor File SQL**: Pilih tab **Import** pada database yang baru dibuat, kemudian pilih file `backup_database.sql` dari komputer Anda dan klik tombol **Go** untuk mengimpor database tersebut.

### 2. **Replace `config.sample.json` ke `config.json`**
   - Temukan file `config.sample.json` di folder proyek Anda.
   - Salin file tersebut dan beri nama salinan tersebut menjadi `config.json`.
   - Edit file `config.json` sesuai dengan pengaturan yang diperlukan (misalnya, sesuaikan informasi database dan pengaturan lainnya sesuai dengan konfigurasi server Anda).

### 3. **Install Dependencies dengan Composer**
   Setelah menyiapkan konfigurasi, Anda perlu menginstal semua dependensi yang dibutuhkan proyek ini menggunakan **Composer**:
   
   - **Instal Composer**: Jika belum menginstal Composer, Anda dapat mengunduh dan menginstalnya dari [situs resmi Composer](https://getcomposer.org/).
   - **Install Dependencies**:
     - Buka terminal atau command prompt di direktori proyek.
     - Jalankan perintah berikut untuk menginstal semua dependensi yang diperlukan:
       ```bash
       composer install
       ```
   - Perintah ini akan mengunduh dan menginstal semua paket PHP yang tercantum di dalam file `composer.json` ke dalam folder `vendor`.

### 4. **Aktifkan Ekstensi `gd` dan `zip` di `my.ini`**
   Beberapa ekstensi PHP seperti **gd** dan **zip** diperlukan oleh proyek ini. Anda perlu memastikan bahwa ekstensi tersebut sudah diaktifkan di konfigurasi PHP Anda:

   - **Buka File `my.ini`**: Cari dan buka file `my.ini` (file konfigurasi MySQL), yang biasanya terletak di folder instalasi XAMPP/WAMP/MAMP Anda.
   
   - **Aktifkan Ekstensi `gd` dan `zip`**:
     - Temukan baris yang berisi `extension=gd` dan `extension=zip`. Jika baris tersebut diawali dengan tanda titik koma (`;`), hapus tanda titik koma tersebut untuk mengaktifkannya.
     - Jika baris tersebut tidak ada, tambahkan baris berikut di dalam bagian `php.ini`:
       ```
       extension=gd
       extension=zip
       ```
   
   - **Restart Server**: Setelah melakukan perubahan pada `my.ini`, restart server Apache dan MySQL Anda (jika menggunakan XAMPP/WAMP/MAMP).

---

## Selesai!

Sekarang proyek ini sudah siap digunakan. Anda dapat mengaksesnya melalui browser dengan membuka `http://localhost/[nama_proyek]`. Pastikan bahwa konfigurasi database dan dependensi telah terpasang dengan benar.

Jika Anda mengalami kesulitan atau masalah saat instalasi, periksa kembali langkah-langkah di atas atau periksa file log untuk informasi lebih lanjut.

---

## Troubleshooting

- **Masalah phpMyAdmin**: Jika Anda tidak dapat mengakses phpMyAdmin, pastikan MySQL sudah berjalan dengan baik dan akses phpMyAdmin di `http://localhost/phpmyadmin` berfungsi.
- **Dependensi Composer Tidak Terpasang**: Pastikan Anda menjalankan perintah `composer install` di direktori yang benar, dan Composer sudah terinstal dengan benar.

Jika ada masalah lain, silakan periksa dokumentasi atau buka issue di repositori proyek ini.
