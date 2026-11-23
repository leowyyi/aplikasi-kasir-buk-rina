<?php
require __DIR__ . '/config/koneksi.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$produkId = filter_input(INPUT_POST, 'produk_id', FILTER_VALIDATE_INT);
$jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);
$bayar = filter_input(INPUT_POST, 'bayar', FILTER_VALIDATE_FLOAT);
if (!$produkId || !$jumlah || $jumlah < 1 || $bayar === false || $bayar < 0) {
    exit('Input transaksi tidak valid. <a href="index.php">Kembali</a>');
}
$stmt = $pdo->prepare("SELECT * FROM produk WHERE id=? AND aktif=1");
$stmt->execute([$produkId]); $produk = $stmt->fetch();
if (!$produk) exit('Produk tidak ditemukan. <a href="index.php">Kembali</a>');
if ($jumlah > (int)$produk['stok']) exit('Stok tidak mencukupi. <a href="index.php">Kembali</a>');
$total = (float)$produk['harga'] * $jumlah;
if ($bayar < $total) exit('Uang pembayaran kurang. <a href="index.php">Kembali</a>');
$kode = 'TRX' . date('ymdHis') . random_int(10,99);
try {
    $pdo->beginTransaction();
    $ins = $pdo->prepare("INSERT INTO transaksi(kode_transaksi,total,bayar,kembalian) VALUES(?,?,?,?)");
    $ins->execute([$kode,$total,$bayar,$bayar-$total]);
    $transaksiId = $pdo->lastInsertId();
    $det = $pdo->prepare("INSERT INTO detail_transaksi(transaksi_id,produk_id,nama_produk,harga,jumlah,subtotal) VALUES(?,?,?,?,?,?)");
    $det->execute([$transaksiId,$produkId,$produk['nama'],$produk['harga'],$jumlah,$total]);
    $upd = $pdo->prepare("UPDATE produk SET stok=stok-? WHERE id=? AND stok>=?");
    $upd->execute([$jumlah,$produkId,$jumlah]);
    if ($upd->rowCount() !== 1) throw new Exception('Stok berubah, silakan ulangi transaksi.');
    $pdo->commit();
} catch(Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    exit('Transaksi gagal: '.htmlspecialchars($e->getMessage()).' <a href="index.php">Kembali</a>');
}
header('Location: struk.php?id=' . $transaksiId); exit;
