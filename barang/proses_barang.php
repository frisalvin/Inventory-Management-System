<?php
include "../inc/koneksi.php";

/* =========================================
SIMPAN BARANG
========================================= */
if(isset($_POST['simpan'])){

/* VALIDASI ANGKA */
if(!is_numeric($_POST['harga_modal']) || !is_numeric($_POST['stok_awal'])){
echo "<script>alert('Harga Modal dan Stok harus angka!');history.back();</script>";
exit;
}

$harga_modal = (int)$_POST['harga_modal'];
$stok_awal   = (int)$_POST['stok_awal'];

$q = mysqli_query($conn,"SELECT max(id) as id FROM barang");
$d = mysqli_fetch_assoc($q);
$kode = "BRG".str_pad($d['id']+1,3,"0",STR_PAD_LEFT);

mysqli_query($conn,"INSERT INTO barang VALUES(
NULL,
'$kode',
'$_POST[nama_barang]',
'$_POST[kategori]',
'$_POST[satuan]',
'$harga_modal',
'$_POST[keterangan]',
'$stok_awal'
)");

header("location:barang.php");
}


/* =========================================
UPDATE BARANG
========================================= */
if(isset($_POST['update'])){

/* VALIDASI ANGKA */
if(!is_numeric($_POST['harga_modal']) || !is_numeric($_POST['stok_awal'])){
echo "<script>alert('Harga Modal dan Stok harus angka!');history.back();</script>";
exit;
}

$harga_modal = (int)$_POST['harga_modal'];
$stok_awal   = (int)$_POST['stok_awal'];

$id = $_POST['id'];

mysqli_query($conn,"
UPDATE barang SET
nama_barang='$_POST[nama_barang]',
kategori='$_POST[kategori]',
satuan='$_POST[satuan]',
harga_modal='$harga_modal',
keterangan='$_POST[keterangan]',
stok_awal='$stok_awal'
WHERE id='$id'
");

header("location:barang.php");
}


/* =========================================
HAPUS SATU BARANG
========================================= */
if(isset($_GET['hapus'])){

$id = $_GET['hapus'];

$ambil = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT kode_barang FROM barang WHERE id='$id'
"));

$kode = $ambil['kode_barang'];

mysqli_query($conn,"DELETE FROM stok_masuk WHERE kode_barang='$kode'");
mysqli_query($conn,"DELETE FROM stok_keluar WHERE kode_barang='$kode'");
mysqli_query($conn,"DELETE FROM stok_opname WHERE kode_barang='$kode'");

mysqli_query($conn,"DELETE FROM barang WHERE id='$id'");

header("location:barang.php");
}


/* =========================================
HAPUS SEMUA BARANG
========================================= */
if(isset($_GET['hapus_semua'])){

mysqli_query($conn,"TRUNCATE barang");
mysqli_query($conn,"TRUNCATE stok_masuk");
mysqli_query($conn,"TRUNCATE stok_keluar");
mysqli_query($conn,"TRUNCATE stok_opname");

header("location:barang.php");
}
?>