<?php
require_once "../config/koneksi.php";
$produk=$pdo->query("SELECT * FROM produk WHERE stok>0 ORDER BY nama_produk")->fetchAll();
$pesan="";
if($_SERVER['REQUEST_METHOD']==='POST'){
 $items=$_POST['jumlah']??[];$metode=$_POST['metode_bayar']??'tunai';$valid=['tunai','transfer','qris'];
 if(!in_array($metode,$valid,true))$metode='tunai';
 $pilihan=[];foreach($items as $id=>$jumlah){$id=(int)$id;$jumlah=(int)$jumlah;if($id>0&&$jumlah>0)$pilihan[$id]=$jumlah;}
 if(!$pilihan){$pesan="Pilih minimal satu produk dan isi jumlahnya.";}
 else{
  try{$pdo->beginTransaction();$total=0;$detail=[];
   $q=$pdo->prepare("SELECT * FROM produk WHERE id_produk=? FOR UPDATE");
   foreach($pilihan as $id=>$jumlah){$q->execute([$id]);$p=$q->fetch();if(!$p)throw new Exception("Produk tidak ditemukan.");if($jumlah>$p['stok'])throw new Exception("Stok ".$p['nama_produk']." tidak mencukupi.");$subtotal=$jumlah*$p['harga'];$total+=$subtotal;$detail[]=[$id,$p['nama_produk'],$jumlah,$p['harga'],$subtotal];}
   $ins=$pdo->prepare("INSERT INTO transaksi(total,metode_bayar) VALUES(?,?)");$ins->execute([$total,$metode]);$transaksiId=(int)$pdo->lastInsertId();
   $insD=$pdo->prepare("INSERT INTO detail_transaksi(id_transaksi,id_produk,nama_produk,jumlah,harga,subtotal) VALUES(?,?,?,?,?,?)");
   $upd=$pdo->prepare("UPDATE produk SET stok=stok-? WHERE id_produk=?");
   foreach($detail as $d){$insD->execute([$transaksiId,$d[0],$d[1],$d[2],$d[3],$d[4]]);$upd->execute([$d[2],$d[0]]);}
   $pdo->commit();header("Location: struk.php?id=".$transaksiId);exit;
  }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$pesan=$e->getMessage();}
 }
}
function rp($n){return "Rp ".number_format((float)$n,0,',','.');}
?><!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Transaksi Kasir</title><link rel="stylesheet" href="../assets/css/style.css"></head><body><div class="app-shell"><aside class="sidebar"><a class="brand" href="../index.php"><span class="brand-icon">BR</span><span><b>Kasir Bu Rina</b><small>Warung & Toko</small></span></a><nav><a href="../index.php">▦ <span>Dashboard</span></a><a href="../produk/index.php">▣ <span>Data Produk</span></a><a class="active" href="index.php">＋ <span>Transaksi Kasir</span></a><a href="../riwayat/index.php">◷ <span>Riwayat Transaksi</span></a></nav></aside><main class="main"><header class="topbar"><div><p class="eyebrow">PENJUALAN</p><h1>Transaksi Kasir</h1></div><a class="btn btn-soft" href="../index.php">← Dashboard</a></header>
<?php if($pesan): ?><div class="alert error"><?= htmlspecialchars($pesan) ?></div><?php endif; ?>
<form method="post" id="formKasir"><section class="panel"><div class="panel-heading"><div><h2>Pilih Produk</h2><p>Masukkan jumlah barang yang dibeli</p></div></div><div class="product-grid">
<?php if(!$produk): ?><p class="empty">Belum ada produk dengan stok tersedia. Tambahkan produk terlebih dahulu.</p><?php else: foreach($produk as $p): ?><article class="product-card"><div class="product-emoji">🛒</div><div class="product-info"><b><?= htmlspecialchars($p['nama_produk']) ?></b><small><?= htmlspecialchars($p['kategori']?:'Produk') ?> · Stok <?= (int)$p['stok'] ?></small><strong><?= rp($p['harga']) ?></strong></div><label class="qty-label">Jumlah<input class="qty" type="number" name="jumlah[<?= $p['id_produk'] ?>]" min="0" max="<?= (int)$p['stok'] ?>" value="0" data-price="<?= (float)$p['harga'] ?>"></label></article><?php endforeach; endif; ?>
</div></section><section class="panel checkout"><div><h2>Pembayaran</h2><label>Metode Pembayaran<select name="metode_bayar"><option value="tunai">Tunai</option><option value="transfer">Transfer</option><option value="qris">QRIS</option></select></label></div><div class="checkout-total"><small>Perkiraan Total</small><h2 id="total">Rp 0</h2><button class="btn btn-primary" type="submit" <?= !$produk?'disabled':'' ?>>Selesaikan Transaksi →</button></div></section></form><footer>Pastikan jumlah barang dan pembayaran sudah benar.</footer></main></div>
<script>
const inputs=document.querySelectorAll('.qty');const totalEl=document.getElementById('total');const format=n=>'Rp '+new Intl.NumberFormat('id-ID').format(n);
function hitung(){let total=0;inputs.forEach(i=>{let qty=Math.max(0,Math.min(Number(i.value)||0,Number(i.max)));i.value=qty;total+=qty*Number(i.dataset.price);});totalEl.textContent=format(total);}
inputs.forEach(i=>i.addEventListener('input',hitung));document.getElementById('formKasir').addEventListener('submit',e=>{if(![...inputs].some(i=>Number(i.value)>0)){e.preventDefault();alert('Pilih minimal satu produk.');}});
</script></body></html>
