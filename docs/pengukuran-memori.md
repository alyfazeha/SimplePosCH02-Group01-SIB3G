# Pengukuran Memori: get() vs lazyById()

Dataset: sekitar 52.500 row pada tabel `transactions`.

| Cara baca data            | Peak memory (MB)                         |
| ------------------------- | ---------------------------------------- |
| `Transaction::lazyById()` | 69.0 MB                                  |
| `Transaction::get()`      | Error (Memory Limit Exhausted / >134 MB) |

## Kesimpulan Kelompok:

Penggunaan metode `Transaction::lazyById()` terbukti jauh lebih aman dan efisien dalam penggunaan memori RAM (hanya 69.0 MB) karena data diproses secara _chunk_ (bertahap). Sebaliknya, metode `Transaction::get()` menarik seluruh 52.500 data sekaligus ke dalam memori yang menyebabkan aplikasi mengalami _fatal error_ (`Allowed memory size exhausted`). Oleh karena itu, _lazy loading_ wajib digunakan saat menangani dataset dengan skala besar.
