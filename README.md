### Penjelasan CRUD

1.  **Manajemen Pegawai (`Employees`)**:
    * Menambah data pegawai baru (nama, email, telepon, tgl lahir, alamat, tgl masuk).
    * Menampilkan daftar semua pegawai dengan informasi ringkas (nama, email, telepon, tgl masuk, departemen, jabatan, status).
    * Melihat detail lengkap seorang pegawai, termasuk data gaji terakhir.
    * Mengedit data pegawai yang sudah ada.
    * Menghapus data pegawai (akan menghapus juga data gaji dan absensi terkait).
    * **Otomatisasi Gaji**: Saat pegawai baru ditambahkan atau jabatannya diubah, data gaji (pokok, tunjangan 10%, potongan 4%, total) untuk bulan berjalan akan otomatis dibuat atau diperbarui berdasarkan gaji pokok posisi jabatan.

2.  **Manajemen Departemen (`Departments`)**:
    * Menambah departemen baru.
    * Menampilkan daftar semua departemen.
    * Mengedit nama departemen.
    * Menghapus departemen (hanya jika tidak ada pegawai yang terhubung).

3.  **Manajemen Posisi / Jabatan (`Positions`)**:
    * Menambah posisi jabatan baru beserta gaji pokoknya.
    * Menampilkan daftar semua posisi jabatan dan gaji pokoknya.
    * Mengedit nama posisi dan gaji pokok.
    * Menghapus posisi (hanya jika tidak ada pegawai yang memegang posisi tersebut).

4.  **Manajemen Absensi (`Attendances`)**:
    * Menambah data absensi harian untuk karyawan.
    * **Logika Status Otomatis**:
        * Jika **jam masuk** diisi antara 07:00 - 09:00, status otomatis menjadi **H (Hadir)**, jam keluar otomatis 16:00.
        * Jika **jam masuk** diisi setelah 09:00, status otomatis menjadi **HT (Hadir Terlambat)**, jam keluar otomatis 16:00.
        * Jika **status** dipilih **I (Izin)** atau **S (Sakit)**, jam masuk/keluar dikosongkan.
        * Jika **tidak mengisi jam masuk maupun status I/S**, status otomatis menjadi **A (Alpha)**.
    * Menampilkan daftar absensi yang dikelompokkan per tanggal.
    * Hanya absensi dengan status **A (Alpha)** dan **HT (Hadir Terlambat)** yang bisa diubah.
    * Menghapus data absensi.

### Fitur & Tabel Baru
1.  **Fitur Dashboard Interaktif**
    * **Filter Periode:** Lihat data berdasarkan Bulan dan Tahun tertentu.
    * **Kartu Ringkasan:** Total Kehadiran, Sakit, Izin, dan Alpha dalam satu periode.
    * **Rekap Absensi:** Tabel grid yang menampilkan status kehadiran seluruh pegawai untuk setiap hari dalam satu bulan penuh (H=Hadir, S=Sakit, I=Izin, A=Alpha).
2.  **Fitur Pencarian Data**
    * Menampilkan data yang dicari.
    * Filter tanggal pada *Attendance* untuk memudahkan melihat absensi per hari. 
3.  **Performance Review**
    * Input penilaian dengan skor skala 1-10.
    * Catatan *feedback* detail untuk setiap sesi *review*.
    * Riwayat penilaian yang terhubung langsung ke data karyawan.

### Struktur & Tampilan

* Menggunakan layout master (`master.blade.php`) untuk tampilan yang konsisten di semua halaman (header, navigasi, footer).
* Styling menggunakan file CSS terpisah untuk setiap bagian (master, index, form, detail).
* Form menggunakan tabel untuk layout input.
* Menampilkan pesan sukses atau error setelah melakukan aksi (tambah, edit, hapus).
* Menggunakan *seeder* untuk mengisi data awal (departments, positions, employees, attendances, salaries).

### Cara Menjalankan Aplikasi

1.  **Ambil Kode Proyek:**
    * **Kalau ini pertama kali:** Buka Terminal atau Command Prompt, arahin ke folder tempat kamu mau nyimpen proyek, terus *clone* repositorinya:
        ```bash
        git clone https://github.com/marell4n/app-pegawai.git app-pegawai
        cd app-pegawai
        ```
    * **Kalau udah punya proyeknya & mau update:** Buka Terminal, masuk ke folder proyekmu, terus *pull* perubahan terbaru dari branch default (misalnya `main` atau `master`):
        ```bash
        git pull origin main
        ```
        *(Ganti `main` kalau nama branch default-mu beda)*

2.  **Install Dependensi PHP (Composer):**
    Jalanin perintah ini buat ngunduh semua library PHP yang dibutuhin.

    ```bash
    composer install
    ```

3.  **Bikin File Environment (`.env`):**
    Salin file `.env.example` jadi `.env`. File ini buat nyimpen settingan khusus laptopmu (kayak info database).

    ```bash
    cp .env.example .env
    ```

4.  **Generate Kunci Aplikasi:**
    Laravel butuh kunci enkripsi unik biar aman. Perintah ini bakal bikinin kuncinya dan nyimpen di `.env`.

    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi Database:**
    * Buka file `.env`.
    * Edit settingan database menjadi:
        ```bash
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=db_pegawai
        DB_USERNAME=root
        DB_PASSWORD=
        ```
    * **Penting:** Pastiin database dengan nama yang kamu tulis di `DB_DATABASE` udah kamu buat di sistem database-mu (misalnya lewat phpMyAdmin, MySQL Workbench, dll.).

6.  **Jalanin Migrasi Database:**
    Perintah ini bakal bikin semua tabel database yang dibutuhin, sesuai file-file di `database/migrations/`.

    ```bash
    php artisan migrate
    ```

7.  **Jalanin Seeder (Opsional tapi Oke Banget):**
    Perintah ini bakal ngisi tabel database pake data awal (data bohongan) biar aplikasinya nggak kosong pas pertama dibuka.

    ```bash
    php artisan db:seed
    ```

8.  **Jalanin Server Laravel:**
        ```bash
        php artisan serve
        ```

9. **Buka Aplikasi:**
    Buka browser dan ketik alamat yang muncul dari `php artisan serve` (biasanya `http://127.0.0.1:8000`).

*Catatan: php 8.4.14, apache 2.4.54, mysql 3.0.30*