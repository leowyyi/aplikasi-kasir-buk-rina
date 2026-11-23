<?php
require __DIR__ . '/config/koneksi.php';
$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);
$s=$pdo->prepare("SELECT * FROM transaksi WHERE id=?");$s->execute([$id]);$trx=$s->fetch();
if(!$trx) exit('Transaksi tidak ditemukan.');
$d=$pdo->prepare("SELECT * FROM detail_transaksi WHERE transaksi_id=?");$d->execute([$id]);$items=$d->fetchAll();
function rupiah($n){return 'Rp '.number_format((float)$n,0,',','.');}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Struk Transaksi</title><link rel="stylesheet" href="style.css"></head><body>
<main class="wrap narrow"><section class="panel receipt"><h1>BU RINA</h1><p>Struk Pembelian Kantin</p><hr><p>Kode: <?= htmlspecialchars($trx['kode_transaksi']) ?><br>Tanggal: <?= htmlspecialchars($trx['dibuat_pada']) ?></p>
<?php foreach($items as $i): ?><div class="receipt-line"><span><?= htmlspecialchars($i['nama_produk']) ?> × <?= $i['jumlah'] ?></span><b><?= rupiah($i['subtotal']) ?></b></div><?php endforeach; ?>
<hr><div class="receipt-line"><span>Total</span><b><?= rupiah($trx['total']) ?></b></div><div class="receipt-line"><span>Bayar</span><b><?= rupiah($trx['bayar']) ?></b></div><div class="receipt-line"><span>Kembalian</span><b><?= rupiah($trx['kembalian']) ?></b></div>
<p class="center">Terima kasih sudah berbelanja!</p><button onclick="window.print()">Cetak Struk</button><a class="button secondary" href="index.php">Transaksi Baru</a></section></main></body></html>
