<?php
session_start();

if(!isset($_SESSION['login'])){
header("Location:login.php");
exit;
}

include "inc/koneksi.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Inventory</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f1f2f6;
font-family:Arial;
margin:0;
}

/* SIDEBAR (TIDAK DIUBAH) */
.sidebar{
width:230px;
height:100vh;
position:fixed;
background:#2f3542;
color:white;
left:0;
top:0;
transition:0.3s;
z-index:999;
}

.sidebar h4{
padding:15px;
}

.sidebar a{
display:block;
padding:12px 18px;
color:white;
text-decoration:none;
}

.sidebar a:hover{
background:#1e90ff;
}

/* CONTENT */
.content{
margin-left:240px;
padding:25px;
}

/* CARD DASHBOARD */
.card-box{
border-radius:10px;
padding:20px;
color:white;
font-weight:bold;
}

/* ===== TOGGLE BUTTON (BARU) ===== */
.toggle-btn{
display:none;
position:fixed;
top:12px;
left:12px;
background:#2f3542;
color:white;
border:none;
padding:8px 12px;
border-radius:6px;
z-index:1000;
}

/* ===========================
RESPONSIVE TAMBAHAN
=========================== */

.table-responsive{
overflow-x:auto;
}

@media(max-width:768px){

.toggle-btn{
display:block;
}

.sidebar{
left:-240px;
}

.sidebar.active{
left:0;
}

.content{
margin-left:0;
padding:60px 15px 15px 15px;
}

.card-box{
padding:15px;
font-size:14px;
}

}

@media(max-width:480px){
.card-box h3{
font-size:18px;
}
}

</style>

</head>
<body>

<!-- TOGGLE BUTTON -->
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<!-- SIDEBAR -->
<div class="sidebar">

<h4>Inventory</h4>

<a href="index.php">Dashboard</a>
<a href="barang/barang.php">Master Barang</a>
<a href="stok_masuk/stok_masuk.php">Stok Masuk</a>
<a href="stok_keluar/stok_keluar.php">Stok Keluar</a>
<a href="rekap/rekap_stok.php">Rekap Stok</a>
<a href="stok_opname/stok_opname.php">Stok Opname</a>

<hr>
<a href="logout.php" style="background:#dc3545;">Logout</a>

</div>

<!-- CONTENT -->
<div class="content">

<h3 class="mb-4">Dashboard</h3>

<?php
$total_barang = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM barang"));

$barang_masuk = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM stok_masuk
WHERE MONTH(tanggal)=MONTH(CURRENT_DATE())
"));

$barang_keluar = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM stok_keluar
WHERE MONTH(tanggal)=MONTH(CURRENT_DATE())
"));

$stok_habis = mysqli_num_rows(mysqli_query($conn,"
SELECT * FROM barang WHERE stok_awal <= 2
"));
?>

<!-- CARD -->
<div class="row g-3 mb-4">

<div class="col-lg-3 col-md-6 col-12">
<div class="card-box bg-primary">
Total Barang
<h3><?= $total_barang ?></h3>
</div>
</div>

<div class="col-lg-3 col-md-6 col-12">
<div class="card-box bg-success">
Barang Masuk Bulan Ini
<h3><?= $barang_masuk ?></h3>
</div>
</div>

<div class="col-lg-3 col-md-6 col-12">
<div class="card-box bg-danger">
Barang Keluar Bulan Ini
<h3><?= $barang_keluar ?></h3>
</div>
</div>

<div class="col-lg-3 col-md-6 col-12">
<div class="card-box bg-warning text-dark">
Stok Hampir Habis
<h3><?= $stok_habis ?></h3>
</div>
</div>

</div>

<!-- TABEL STOK TERBANYAK -->
<div class="card">
<div class="card-header">
<b>Barang dengan Stok Terbanyak</b>
</div>

<div class="card-body">

<div class="table-responsive">
<table class="table table-bordered">

<tr>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Keterangan</th> <!-- TAMBAHAN -->
<th>Stok</th>
</tr>

<?php

$q = mysqli_query($conn,"
SELECT 
barang.kode_barang,
barang.nama_barang,
barang.kategori,
barang.keterangan,

(
barang.stok_awal
+
IFNULL((SELECT SUM(jumlah) FROM stok_masuk WHERE kode_barang=barang.kode_barang),0)
-
IFNULL((SELECT SUM(jumlah) FROM stok_keluar WHERE kode_barang=barang.kode_barang),0)
) AS stok_akhir

FROM barang
ORDER BY stok_akhir DESC
LIMIT 5
");

while($d=mysqli_fetch_assoc($q)){
echo "
<tr>
<td>$d[kode_barang]</td>
<td>$d[nama_barang]</td>
<td>$d[kategori]</td>
<td>$d[keterangan]</td>
<td>$d[stok_akhir]</td>
</tr>
";
}
?>

</table>
</div>

</div>
</div>

</div>

<script>
function toggleSidebar(){
document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>