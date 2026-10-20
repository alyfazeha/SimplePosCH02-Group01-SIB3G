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