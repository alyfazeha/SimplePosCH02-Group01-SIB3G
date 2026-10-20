Anggota Kelompok : 
Alyfa Zahra Qurrota Aini	NIM. 244107060057
Ganang Andika Kurnia P. 	NIM. 244107060008
M. Miftahul Akmal 		NIM. 244107060048
Nafisa Chiquita Finandra P.	NIM. 244107060020

### Analisis Keamanan: Mencegah Manipulasi Total Transaksi

**Pertanyaan:** *Kalau field total tetap dikirim dari form dan divalidasi dengan aturan `numeric`, apakah itu cukup mencegah manipulasi total? Kenapa atau kenapa tidak?*

**Jawaban:**
**Tidak Cukup** Memvalidasi field `total` hanya dengan aturan `numeric` sama sekali tidak mencegah manipulasi data. 

**Alasannya:**
1. **Validasi `numeric` hanya mengecek tipe data, bukan kebenaran data.** Aturan ini hanya peduli apakah input yang masuk adalah sebuah angka. Ia tidak bisa memastikan apakah angka tersebut merupakan hasil perkalian aktual antara jumlah barang (`qty`) dengan harga barang yang sah.
2. **Klien (Browser) sepenuhnya ada di bawah kendali *user*.** Meskipun di frontend (HTML/JavaScript) kita sudah mengunci field total agar tidak bisa diedit secara visual, seorang *user* yang nakal dapat dengan mudah memanipulasinya menggunakan:
   * **Inspect Element (Developer Tools):** Mengubah *value* pada elemen `<input type="hidden" name="total" value="50000">` menjadi `value="10"`.
   * **Tools API (Postman / cURL / Burp Suite):** Mengirim *request* POST langsung ke server dengan menyisipkan parameter `total=10`.
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
