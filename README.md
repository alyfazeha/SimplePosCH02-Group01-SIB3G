## Skenario Uji Manual (Manual Testing Scenarios)

Berikut adalah daftar skenario pengujian fungsionalitas sistem berdasarkan hak akses (_Role-Based Access Control_ / RBAC) dan pengelolaan halaman tamu:

### 1. Peran: Guest (Tamu / Belum Login)

- **Deskripsi Skenario:** Menguji pengalihan akses (_redirect_) saat pengguna belum melakukan login ke dalam sistem.
- **Langkah-langkah:**
    1. Buka browser dalam mode incognito/private.
    2. Akses halaman utama URL root (`http://127.0.0.1:8000/`).
    3. Coba akses halaman manajemen kasir/transaksi secara langsung (`http://127.0.0.1:8000/pos` atau `/transactions`).
- **Hasil yang Diharapkan:**
    - Saat mengakses root URL (`/`), sistem secara otomatis mengalihkan (_redirect_) ke halaman informasi / _welcome_ (`/welcome`).
    - Saat mengakses halaman terproteksi secara langsung, sistem menolak akses dan mengalihkan pengguna ke halaman login (`/login`).

---

### 2. Peran: Kasir (`kasir@pos.test`)

- **Deskripsi Skenario:** Menguji hak akses pengguna dengan peran kasir pada halaman POS dan pembatasan halaman manajemen data.
- **Langkah-langkah:**
    1. Lakukan login menggunakan akun kasir (`kasir@pos.test` / `password`).
    2. Akses halaman pembuatan transaksi / POS (`/pos`).
    3. Lakukan input transaksi penjualan produk dan simpan.
    4. Coba ketik manual URL untuk mengakses halaman laporan transaksi (`/transactions`) atau manajemen produk (`/products`).
- **Hasil yang Diharapkan:**
    - Kasir berhasil mengakses dan menggunakan halaman POS (`/pos`) serta sukses melakukan penyimpanan transaksi.
    - Kasir ditolak mengakses halaman `/transactions`, `/products`, dan `/categories` dengan kemunculan halaman error **403 Forbidden**.

---

### 3. Peran: Manager (`manager@pos.test`)

- **Deskripsi Skenario:** Menguji hak akses khusus manager yang diizinkan melihat laporan transaksi tetapi dilarang mengakses manajemen produk.
- **Langkah-langkah:**
    1. Lakukan login menggunakan akun manager (`manager@pos.test` / `password`).
    2. Akses halaman laporan transaksi (`/transactions`).
    3. Coba akses halaman manajemen produk (`/products`) atau kategori (`/categories`).
- **Hasil yang Diharapkan:**
    - Manager berhasil membuka dan melihat data pada halaman laporan transaksi (`/transactions`).
    - Manager diblokir saat mencoba membuka halaman `/products` atau `/categories` dan menampilkan pesan error **403 Forbidden** (_Halaman ini hanya untuk peran: admin_).

---

### 4. Peran: Admin (`admin@pos.test`)

- **Deskripsi Skenario:** Menguji hak akses penuh administrator terhadap seluruh modul sistem (POS, Transaksi, Produk, dan Kategori).
- **Langkah-langkah:**
    1. Lakukan login menggunakan akun admin (`admin@pos.test` / `password`).
    2. Akses halaman `/pos` untuk membuat transaksi.
    3. Akses halaman `/transactions` untuk melihat rekap laporan.
    4. Akses halaman `/products` dan `/categories` untuk mengelola data master produk.
- **Hasil yang Diharapkan:**
    - Admin memiliki hak akses penuh ke seluruh menu tanpa mengalami kendala ataupun error 403.
