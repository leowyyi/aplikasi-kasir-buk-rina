<?php
require_once "../config/koneksi.php";
$id=(int)($_GET['id']??0);
try{$s=$pdo->prepare("DELETE FROM produk WHERE id_produk=?");$s->execute([$id]);header("Location: index.php?sukses=1");exit;}catch(PDOException $e){die("Produk tidak dapat dihapus karena sudah digunakan dalam transaksi.");}
