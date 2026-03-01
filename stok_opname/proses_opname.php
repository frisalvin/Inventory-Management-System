<?php
include "../inc/koneksi.php";

/*
========================================
SIMPAN OPNAME
========================================
*/
if(isset($_POST['simpan'])){

$kode_barang = $_POST['kode_barang'];
$tanggal     = $_POST['tanggal'];

if($_POST['stok_fisik']==""){
echo "<script>alert('Stok fisik wajib diisi!');history.back();</script>";
exit;
}

$stok_fisik  = (int)$_POST['stok_fisik'];

/* HITUNG STOK SISTEM */
$cek = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
barang.stok_awal,

IFNULL((SELECT SUM(jumlah) FROM stok_masuk WHERE kode_barang='$kode_barang'),0) AS masuk,
IFNULL((SELECT SUM(jumlah) FROM stok_keluar WHERE kode_barang='$kode_barang'),0) AS keluar

FROM barang
WHERE kode_barang='$kode_barang'
"));

$stok_awal   = (int)$cek['stok_awal'];
$stok_masuk  = (int)$cek['masuk'];
$stok_keluar = (int)$cek['keluar'];

$stok_sistem = $stok_awal + $stok_masuk - $stok_keluar;
$selisih     = $stok_fisik - $stok_sistem;

/* INSERT */
mysqli_query($conn,"
INSERT INTO stok_opname
(tanggal,kode_barang,stok_sistem,stok_fisik,selisih)
VALUES
('$tanggal','$kode_barang','$stok_sistem','$stok_fisik','$selisih')
");

header("Location: stok_opname.php");
}


/*
========================================
UPDATE OPNAME
========================================
*/
if(isset($_POST['update'])){

$id          = $_POST['id'];
$kode_barang = $_POST['kode_barang'];
$tanggal     = $_POST['tanggal'];

if($_POST['stok_fisik']==""){
echo "<script>alert('Stok fisik wajib diisi!');history.back();</script>";
exit;
}

$stok_fisik  = (int)$_POST['stok_fisik'];

/* HITUNG ULANG STOK SISTEM */
$cek = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT 
barang.stok_awal,

IFNULL((SELECT SUM(jumlah) FROM stok_masuk WHERE kode_barang='$kode_barang'),0) AS masuk,
IFNULL((SELECT SUM(jumlah) FROM stok_keluar WHERE kode_barang='$kode_barang'),0) AS keluar

FROM barang
WHERE kode_barang='$kode_barang'
"));

$stok_awal   = (int)$cek['stok_awal'];
$stok_masuk  = (int)$cek['masuk'];
$stok_keluar = (int)$cek['keluar'];

$stok_sistem = $stok_awal + $stok_masuk - $stok_keluar;
$selisih     = $stok_fisik - $stok_sistem;

mysqli_query($conn,"
UPDATE stok_opname SET
tanggal='$tanggal',
kode_barang='$kode_barang',
stok_sistem='$stok_sistem',
stok_fisik='$stok_fisik',
selisih='$selisih'
WHERE id='$id'
");

header("Location: stok_opname.php");
}


/*
========================================
HAPUS SATU DATA
========================================
*/
if(isset($_GET['hapus'])){

mysqli_query($conn,"
DELETE FROM stok_opname WHERE id='$_GET[hapus]'
");

header("Location: stok_opname.php");
}


/*
========================================
HAPUS SEMUA DATA (TAMBAHAN)
========================================
*/
if(isset($_GET['hapus_semua'])){

mysqli_query($conn,"TRUNCATE stok_opname");

header("Location: stok_opname.php");
}
?>