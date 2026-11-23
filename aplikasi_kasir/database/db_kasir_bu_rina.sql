CREATE DATABASE IF NOT EXISTS db_kasir_bu_rina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_kasir_bu_rina;

CREATE TABLE IF NOT EXISTS produk (
  id_produk INT AUTO_INCREMENT PRIMARY KEY,
  nama_produk VARCHAR(120) NOT NULL,
  kategori VARCHAR(80) DEFAULT NULL,
  harga DECIMAL(12,2) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS transaksi (
  id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(12,2) NOT NULL DEFAULT 0,
  metode_bayar ENUM('tunai','transfer','qris') NOT NULL DEFAULT 'tunai'
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS detail_transaksi (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_transaksi INT NOT NULL,
  id_produk INT NULL,
  nama_produk VARCHAR(120) NOT NULL,
  jumlah INT NOT NULL,
  harga DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(12,2) NOT NULL,
  CONSTRAINT fk_detail_transaksi FOREIGN KEY (id_transaksi)
    REFERENCES transaksi(id_transaksi) ON DELETE CASCADE,
  CONSTRAINT fk_detail_produk FOREIGN KEY (id_produk)
    REFERENCES produk(id_produk) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO produk (nama_produk,kategori,harga,stok) VALUES
('Air Mineral','Minuman',4000,30),
('Teh Botol','Minuman',5000,25),
('Roti Cokelat','Makanan',6000,15),
('Mi Instan','Makanan',3500,40),
('Keripik','Camilan',7000,20);
