<?php
require_once "../config/koneksi.php";
$produk = $pdo->query("SELECT * FROM produk ORDER BY id_produk DESC")->fetchAll();
function rp($n){return "Rp ".number_format((float)$n,0,',','.');}
?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Data Produk - Kasir Bu Rina</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
<div class="app-shell"><aside class="sidebar"><a class="brand" href="../index.php"><span class="brand-icon">BR</span><span><b>Kasir Bu Rina</b><small>Warung & Toko</small></span></a><nav><a href="../index.php">▦ <span>Dashboard</span></a><a class="active" href="index.php">▣ <span>Data Produk</span></a><a href="../transaksi/index.php">＋ <span>Transaksi Kasir</span></a><a href="../riwayat/index.php">◷ <span>Riwayat Transaksi</span></a></nav></aside>
<main class="main"><header class="topbar"><div><p class="eyebrow">INVENTARIS TOKO</p><h1>Data Produk</h1></div><a class="btn btn-primary" href="tambah.php">＋ Tambah Produk</a></header>
<?php if(isset($_GET['sukses'])): ?><div class="alert success">Data produk berhasil diperbarui.</div><?php endif; ?>
<section class="panel"><div class="panel-heading"><div><h2>Daftar Produk</h2><p><?= count($produk) ?> produk tersedia</p></div></div><div class="table-wrap"><table><thead><tr><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody>
<?php if(!$produk): ?><tr><td colspan="5" class="empty">Belum ada produk. Tambahkan produk pertama.</td></tr><?php else: foreach($produk as $p): ?><tr><td><b><?= htmlspecialchars($p['nama_produk']) ?></b></td><td><?= htmlspecialchars($p['kategori'] ?: '-') ?></td><td class="money"><?= rp($p['harga']) ?></td><td><span class="pill <?= $p['stok']<=5?'pill-warning':'' ?>"><?= (int)$p['stok'] ?></span></td><td class="actions"><a href="edit.php?id=<?= $p['id_produk'] ?>">Edit</a><a class="danger-link" href="hapus.php?id=<?= $p['id_produk'] ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a></td></tr><?php endforeach; endif; ?>
</tbody></table></div></section><footer>© <?= date('Y') ?> Kasir Bu Rina</footer></main></div></body></html>
