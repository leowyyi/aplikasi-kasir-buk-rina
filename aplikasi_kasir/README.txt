APLIKASI KASIR BU RINA
======================

FITUR
- Dashboard ringkasan produk, transaksi, penjualan hari ini, dan stok menipis.
- Data produk: tambah, edit, hapus.
- Transaksi kasir dengan hitung total otomatis.
- Stok produk otomatis berkurang setelah transaksi berhasil.
- Pilihan pembayaran tunai, transfer, dan QRIS.
- Struk transaksi yang dapat dicetak.
- Riwayat transaksi.
- Tidak ada halaman login dan tidak ada akun/admin.

CARA MENJALANKAN (XAMPP)
1. Ekstrak folder kasir_bu_rina ke C:\xampp\htdocs\
2. Buka XAMPP, jalankan Apache dan MySQL.
3. Buka http://localhost/phpmyadmin
4. Pilih tab Import, pilih file database/db_kasir_bu_rina.sql, lalu klik Import/Go.
5. Buka browser: http://localhost/kasir_bu_rina/

KONFIGURASI DATABASE
File: config/koneksi.php
Default XAMPP:
- Host: localhost
- Database: db_kasir_bu_rina
- Username: root
- Password: (kosong)

CATATAN
- Pastikan ekstensi PDO MySQL aktif pada PHP.
- Untuk penggunaan nyata di internet, aplikasi tanpa login sebaiknya dibatasi aksesnya
  hanya untuk jaringan/perangkat toko karena siapa pun yang dapat membuka aplikasi
  dapat mengubah data produk dan membuat transaksi.
