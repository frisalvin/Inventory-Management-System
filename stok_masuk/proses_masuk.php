<?php
include "../inc/koneksi.php";

/* =================================================
   SIMPAN DATA STOK MASUK
================================================= */
if(isset($_POST['simpan'])){

/* AMBIL KETERANGAN DARI MASTER BARANG */
$q = mysqli_query($conn,"SELECT keterangan FROM barang WHERE kode_barang='$_POST[kode_barang]'");
$d = mysqli_fetch_assoc($q);
$keterangan = $d['keterangan'];

mysqli_query($conn,"INSERT INTO stok_masuk VALUES(
NULL,
'$_POST[tanggal]',
'$_POST[kode_barang]',
'$_POST[jumlah]',
'$_POST[supplier]',
'$keterangan'
)");

header("location:stok_masuk.php");
}


/* =================================================
   UPDATE DATA (EDIT)
================================================= */
if(isset($_POST['update'])){

$id = $_POST['id'];

/* AMBIL KETERANGAN MASTER BARANG */
$q = mysqli_query($conn,"SELECT keterangan FROM barang WHERE kode_barang='$_POST[kode_barang]'");
$d = mysqli_fetch_assoc($q);
$keterangan = $d['keterangan'];

mysqli_query($conn,"
UPDATE stok_masuk SET
tanggal='$_POST[tanggal]',
kode_barang='$_POST[kode_barang]',
jumlah='$_POST[jumlah]',
supplier='$_POST[supplier]',
keterangan='$keterangan'
WHERE id='$id'
");

header("location:stok_masuk.php");
}


/* =================================================
   HAPUS SATU DATA
================================================= */
if(isset($_GET['hapus'])){

$id=$_GET['hapus'];

mysqli_query($conn,"
DELETE FROM stok_masuk WHERE id='$id'
");

header("location:stok_masuk.php");
}


/* =================================================
   HAPUS SEMUA DATA
================================================= */
if(isset($_GET['hapus_semua'])){

mysqli_query($conn,"TRUNCATE stok_masuk");

header("location:stok_masuk.php");
}
?>