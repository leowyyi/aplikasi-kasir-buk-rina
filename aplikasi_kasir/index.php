<?php
require_once __DIR__ . "/config/koneksi.php";
$produkCount = (int)$pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
$transaksiCount = (int)$pdo->query("SELECT COUNT(*) FROM transaksi")->fetchColumn();
$totalHariIni = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM transaksi WHERE DATE(tanggal) = CURDATE()")->fetchColumn();
$stokMenipis = (int)$pdo->query("SELECT COUNT(*) FROM produk WHERE stok <= 5")->fetchColumn();
$transaksiTerbaru = $pdo->query("SELECT id_transaksi, tanggal, total, metode_bayar FROM transaksi ORDER BY id_transaksi DESC LIMIT 6")->fetchAll();
function rupiah($angka) { return "Rp " . number_format((float)$angka, 0, ',', '.'); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kasir Bu Rina</title><link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
<aside class="sidebar">
  <a class="brand" href="index.php"><span class="brand-icon">BR</span><span><b>Kasir Bu Rina</b><small>Warung & Toko</small></span></a>
  <nav>
    <a class="active" href="index.php">▦ <span>Dashboard</span></a>
    <a href="produk/index.php">▣ <span>Data Produk</span></a>
    <a href="transaksi/index.php">＋ <span>Transaksi Kasir</span></a>
    <a href="riwayat/index.php">◷ <span>Riwayat Transaksi</span></a>
  </nav>
  <div class="sidebar-note"><b>Kasir siap digunakan</b><p>Kelola produk dan catat penjualan dengan mudah.</p></div>
</aside>
<main class="main">
  <header class="topbar"><div><p class="eyebrow">RINGKASAN TOKO</p><h1>Dashboard</h1></div><div class="date-chip">● Sistem Kasir Aktif</div></header>
  <section class="welcome"><div><p class="eyebrow light">SELAMAT DATANG</p><h2>Halo, Bu Rina! 👋</h2><p>Pantau penjualan dan kelola toko dari satu tempat.</p></div><div class="welcome-art">🛍️</div></section>
  <section class="stats">
    <article class="stat-card"><div class="stat-icon green">▣</div><div><p>Total Produk</p><h3><?= $produkCount ?></h3><small>Produk tersimpan</small></div></article>
    <article class="stat-card"><div class="stat-icon blue">▤</div><div><p>Jumlah Transaksi</p><h3><?= $transaksiCount ?></h3><small>Semua transaksi</small></div></article>
    <article class="stat-card"><div class="stat-icon orange">Rp</div><div><p>Penjualan Hari Ini</p><h3><?= rupiah($totalHariIni) ?></h3><small>Total pemasukan hari ini</small></div></article>
    <article class="stat-card"><div class="stat-icon red">!</div><div><p>Stok Menipis</p><h3><?= $stokMenipis ?></h3><small>Produk stok ≤ 5</small></div></article>
  </section>
  <section class="panel">
    <div class="panel-heading"><div><h2>Transaksi Terbaru</h2><p>Enam transaksi terakhir</p></div><a class="text-link" href="riwayat/index.php">Lihat semua →</a></div>
    <div class="table-wrap"><table><thead><tr><th>ID</th><th>Tanggal</th><th>Metode Bayar</th><th>Total</th></tr></thead><tbody>
    <?php if (!$transaksiTerbaru): ?><tr><td colspan="4" class="empty">Belum ada transaksi. Mulai penjualan pertamamu!</td></tr>
    <?php else: foreach ($transaksiTerbaru as $t): ?><tr><td>#<?= (int)$t['id_transaksi'] ?></td><td><?= date('d/m/Y H:i', strtotime($t['tanggal'])) ?></td><td><span class="pill"><?= htmlspecialchars(ucfirst($t['metode_bayar'])) ?></span></td><td class="money"><?= rupiah($t['total']) ?></td></tr><?php endforeach; endif; ?>
    </tbody></table></div>
  </section>
  <div class="quick-actions"><a class="quick-card" href="transaksi/index.php"><span>＋</span><div><b>Mulai Transaksi</b><small>Catat penjualan baru</small></div><strong>→</strong></a><a class="quick-card" href="produk/tambah.php"><span>▣</span><div><b>Tambah Produk</b><small>Masukkan produk toko</small></div><strong>→</strong></a></div>
  <footer>© <?= date('Y') ?> Kasir Bu Rina · Dibuat untuk membantu kegiatan toko</footer>
</main></div>
</body></html>
