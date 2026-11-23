<?php
require __DIR__ . '/config/koneksi.php';
$produk = $pdo->query("SELECT * FROM produk WHERE aktif=1 ORDER BY nama")->fetchAll();
$hariIni = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) jumlah, COALESCE(SUM(total),0) omzet FROM transaksi WHERE DATE(dibuat_pada)=?");
$stmt->execute([$hariIni]);
$ringkasan = $stmt->fetch();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kasir Bu Rina</title><link rel="stylesheet" href="style.css"></head><body>
<header class="top"><div><span class="eyebrow">SISTEM PENJUALAN KANTIN</span><h1>Kasir Bu Rina</h1><p>Catat transaksi dengan cepat dan rapi.</p></div><nav><a class="active" href="index.php">Kasir</a><a href="produk.php">Produk</a><a href="riwayat.php">Riwayat</a></nav></header>
<main class="wrap">
<section class="stats"><article><small>Transaksi hari ini</small><strong><?= (int)$ringkasan['jumlah'] ?></strong></article><article><small>Omzet hari ini</small><strong>Rp <?= number_format((float)$ringkasan['omzet'],0,',','.') ?></strong></article></section>
<section class="panel"><h2>Transaksi Baru</h2>
<?php if (!$produk): ?><p>Belum ada produk aktif. Tambahkan produk terlebih dahulu.</p><?php else: ?>
<form action="simpan_transaksi.php" method="post" class="form-grid">
<label>Produk<select name="produk_id" required><?php foreach($produk as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nama']) ?> — Rp <?= number_format($p['harga'],0,',','.') ?> (stok <?= $p['stok'] ?>)</option><?php endforeach; ?></select></label>
<label>Jumlah<input type="number" name="jumlah" min="1" value="1" required></label>
<label>Uang pembayaran (Rp)<input type="number" name="bayar" min="0" step="1" placeholder="Masukkan uang diterima" required></label>
<div class="full"><button type="submit">Simpan Transaksi</button></div>
</form><?php endif; ?></section>
<section class="panel"><div class="panel-head"><h2>Daftar Menu</h2><a href="produk.php">Kelola produk →</a></div>
<div class="table-scroll"><table><thead><tr><th>Nama produk</th><th>Harga</th><th>Stok</th></tr></thead><tbody>
<?php foreach($produk as $p): ?><tr><td><?= htmlspecialchars($p['nama']) ?></td><td>Rp <?= number_format($p['harga'],0,',','.') ?></td><td><?= (int)$p['stok'] ?></td></tr><?php endforeach; ?>
</tbody></table></div></section></main><footer>Kasir Bu Rina · Aplikasi Kantin Sekolah</footer></body></html>
