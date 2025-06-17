# LAB_TNU - Sistem Peminjaman Laboratorium & Alat

Aplikasi ini adalah sistem informasi untuk mengelola peminjaman laboratorium dan alat di lingkungan LAB_TNU. Sistem ini berbasis web dan menggunakan Laravel + Filament Admin Panel.

---

## Fitur Utama

-   **Manajemen Laboratorium**: Tambah, edit, hapus data laboratorium.
-   **Manajemen Alat**: Tambah, edit, hapus data alat, serta pengelolaan stok alat.
-   **Peminjaman Lab & Alat**: Pengguna dapat mengajukan peminjaman lab/alat, admin dapat menyetujui/menolak.
-   **Kalender Jadwal**: Melihat jadwal pemakaian lab & alat dalam tampilan kalender.
-   **Upload Bukti Selesai**: Pengguna wajib upload foto bukti selesai peminjaman.
-   **Dashboard Statistik**: Statistik peminjaman, status alat/lab, dan log aktivitas.
-   **Role & Hak Akses**: Superadmin, Admin, Monitor, Pengguna (Mahasiswa).

---

## Cara Instalasi

> Jalankan Semua ini Di Terminal

1. **Install Dependency**

    ```
    composer install
    npm install
    npm run build
    ```

2. **Generate Key**

    ```
    php artisan key:generate
    ```

3. **Migrasi & Seed Database**

    ```
    php artisan migrate
    php artisan migrate --seed
    ```

    - Seeder akan membuat user default:
        - Superadmin: `superadmin@labtnu.test` / `password`
        - Admin: `admin@labtnu.test` / `password`
        - Monitor: `monitor@labtnu.test` / `password`
        - Pengguna: `pengguna@labtnu.test` / `password`

4. **Buat Environtmen**

    ```
    copy .env.example .env
    ```

5. **Jalankan Server**

    ```
    php artisan serve
    ```

6. **Akses Aplikasi**
    - Buka browser ke `http://localhost:8000/Lab-TNU`
    - Login sesuai role.

---

## Penjelasan Role & Hak Akses

-   **Superadmin**: Semua akses, kelola user, data master, monitoring.
-   **Admin**: Kelola data lab, alat, approve/reject peminjaman.
-   **Monitor**: Hanya monitoring, tidak bisa edit data.
-   **Pengguna**: Mahasiswa, hanya bisa mengajukan peminjaman.

---

## Alur Penggunaan

1. **Login** sesuai role.
2. **Pengguna**:
    - Ajukan peminjaman lab/alat melalui menu "Peminjaman".
    - Isi form, pilih tanggal, waktu, jumlah alat.
    - Setelah selesai menggunakan, klik tombol "Selesai" dan upload foto bukti selesai.
3. **Admin/Superadmin**:
    - Melihat dan mengelola data peminjaman.
    - Approve/reject pengajuan.
    - Melihat statistik dan log aktivitas.
4. **Monitor**:
    - Hanya bisa melihat data, tidak bisa mengubah.

---

## Fitur Kalender

-   Menu "Kalender" menampilkan semua jadwal pemakaian lab & alat.
-   Klik tanggal untuk melihat detail jadwal hari itu.

---

## Fitur Statistik & Dashboard

-   Statistik peminjaman lab & alat (harian, bulanan, tahunan).
-   Status alat/lab (kosong/dipinjam).
-   Log aktivitas peminjaman.

---

## Upload Bukti Selesai

-   Setelah peminjaman, pengguna wajib upload foto bukti selesai (max 2MB, jpg/png/webp).
-   Admin dapat melihat bukti pada detail peminjaman.

---

## Pengembangan & Struktur Kode

-   **resources/views/filament/**: Semua tampilan (Blade) untuk panel admin.
-   **app/Filament/Resources/**: Resource CRUD utama (Lab, Tool, Booking).
-   **app/Filament/Widgets/**: Widget dashboard/statistik.
-   **app/Models/**: Model database.
-   **database/migrations/**: Struktur tabel database.
-   **database/seeders/**: Seeder data awal (role, user).
-   **app/Http/Controllers/**: Controller custom (upload bukti selesai).
-   **routes/web.php**: Routing aplikasi.

---

## FAQ

**Q: Bagaimana jika lupa password?**  
A: Hubungi Superadmin untuk reset password.

**Q: Bagaimana menambah user baru?**  
A: Hanya Superadmin yang bisa menambah user melalui menu "User".

**Q: Bagaimana jika stok alat tidak sesuai?**  
A: Admin dapat mengedit jumlah alat pada menu "Alat".

---

## Kontak & Bantuan

Untuk bantuan lebih lanjut, hubungi admin LAB_TNU.

---
