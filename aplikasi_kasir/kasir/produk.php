<?php
require __DIR__ . '/config/koneksi.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $aksi=$_POST['aksi']??'';
 if($aksi==='hapus'){ $q=$pdo->prepare("UPDATE produk SET aktif=0 WHERE id=?");$q->execute([(int)$_POST['id']]); }
 else {
  $nama=trim($_POST['nama']??'');$harga=(float)($_POST['harga']??0);$stok=(int)($_POST['stok']??0);$id=(int)($_POST['id']??0);
  if($nama===''||$harga<0||$stok<0)$error='Lengkapi data dengan benar.';
  else if($id){$q=$pdo->prepare("UPDATE produk SET nama=?,harga=?,stok=?,aktif=1 WHERE id=?");$q->execute([$nama,$harga,$stok,$id]);}
  else {$q=$pdo->prepare("INSERT INTO produk(nama,harga,stok) VALUES(?,?,?)");$q->execute([$nama,$harga,$stok]);}
 }
 if(!$error){header('Location: produk.php');exit;}
}
$edit=null;if(isset($_GET['edit'])){$q=$pdo->prepare("SELECT * FROM produk WHERE id=?");$q->execute([(int)$_GET['edit']]);$edit=$q->fetch();}
$list=$pdo->query("SELECT * FROM produk WHERE aktif=1 ORDER BY nama")->fetchAll();
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Produk - Kasir Bu Rina</title><link rel="stylesheet" href="style.css"></head><body>
<header class="top"><div><span class="eyebrow">PENGELOLAAN MENU</span><h1>Produk Kantin</h1><p>Tambah dan ubah nama menu, harga, serta stok.</p></div><nav><a href="index.php">Kasir</a><a class="active" href="produk.php">Produk</a><a href="riwayat.php">Riwayat</a></nav></header>
<main class="wrap"><section class="panel"><h2><?= $edit?'Ubah Produk':'Tambah Produk' ?></h2><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<form method="post" class="form-grid"><input type="hidden" name="aksi" value="simpan"><input type="hidden" name="id" value="<?= $edit?(int)$edit['id']:0 ?>">
<label>Nama produk<input name="nama" required value="<?= htmlspecialchars($edit['nama']??'') ?>"></label><label>Harga (Rp)<input type="number" name="harga" min="0" required value="<?= htmlspecialchars($edit['harga']??'') ?>"></label><label>Stok<input type="number" name="stok" min="0" required value="<?= htmlspecialchars($edit['stok']??'0') ?>"></label><div class="full"><button>Simpan Produk</button> <?php if($edit): ?><a class="button secondary" href="produk.php">Batal</a><?php endif; ?></div></form></section>
<section class="panel"><h2>Daftar Produk</h2><div class="table-scroll"><table><thead><tr><th>Nama</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead><tbody><?php foreach($list as $p): ?><tr><td><?= htmlspecialchars($p['nama']) ?></td><td>Rp <?= number_format($p['harga'],0,',','.') ?></td><td><?= $p['stok'] ?></td><td class="actions"><a href="?edit=<?= $p['id'] ?>">Edit</a><form method="post" onsubmit="return confirm('Nonaktifkan produk ini?')"><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= $p['id'] ?>"><button class="danger small">Nonaktifkan</button></form></td></tr><?php endforeach; ?></tbody></table></div></section></main></body></html>
