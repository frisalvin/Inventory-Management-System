<?php
session_start();

if(!isset($_SESSION['login'])){
header("Location:../login.php");
exit;
}

include "../inc/koneksi.php";

/*AMBIL VALUE SEARCH*/

$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

$where="";
if($cari!=""){
$where="WHERE barang.nama_barang LIKE '%$cari%' 
OR barang.kode_barang LIKE '%$cari%'";
}

/*QUERY REKAP STOK FINAL*/

$data = mysqli_query($conn,"

SELECT 
barang.kode_barang,
barang.nama_barang,
barang.kategori,
barang.satuan,
barang.keterangan,
barang.harga_modal,
barang.stok_awal,

IFNULL((
    SELECT SUM(jumlah) 
    FROM stok_masuk 
    WHERE stok_masuk.kode_barang = barang.kode_barang
),0) AS stok_masuk,

IFNULL((
    SELECT SUM(jumlah) 
    FROM stok_keluar 
    WHERE stok_keluar.kode_barang = barang.kode_barang
),0) AS stok_keluar

FROM barang
$where
ORDER BY barang.kode_barang ASC

");
?>

<!DOCTYPE html>
<html>
<head>
<title>Rekap Stok</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#f1f2f6;
font-family:Arial;
margin:0;
}
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
.sidebar h4{padding:15px;}
.sidebar a{
display:block;
padding:12px 18px;
color:white;
text-decoration:none;
}
.sidebar a:hover{background:#1e90ff;}
.content{
margin-left:240px;
padding:25px;
}
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
.table-responsive{overflow-x:auto;}
@media(max-width:768px){
.toggle-btn{display:block;}
.sidebar{left:-240px;}
.sidebar.active{left:0;}
.content{
margin-left:0;
padding:60px 15px 15px 15px;
}
}
</style>

</head>

<body>

<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<div class="sidebar">
<h4 class="p-3">Inventory</h4>
<a href="../index.php">Dashboard</a>
<a href="../barang/barang.php">Master Barang</a>
<a href="../stok_masuk/stok_masuk.php">Stok Masuk</a>
<a href="../stok_keluar/stok_keluar.php">Stok Keluar</a>
<a href="rekap_stok.php">Rekap Stok</a>
<a href="../stok_opname/stok_opname.php">Stok Opname</a>

<hr>
<a href="../logout.php" style="background:#dc3545;">Logout</a>
</div>

<div class="content">

<h3>Rekap Stok</h3>

<a href="export_rekap_excel.php" class="btn btn-info btn-sm">Export Excel</a>
<a href="export_rekap_pdf.php" class="btn btn-warning btn-sm">Export PDF</a>

<br><br>

<form>
<input name="cari" class="form-control"
placeholder="Cari barang..."
value="<?= $cari ?>">
</form>

<br>

<?php 
/* hitung total modal keseluruhan */
$total_modal_keseluruhan = 0;
mysqli_data_seek($data,0);
while($x=mysqli_fetch_assoc($data)){
    $stok_akhir_temp = $x['stok_awal'] + $x['stok_masuk'] - $x['stok_keluar'];
    $total_modal_keseluruhan += $stok_akhir_temp * $x['harga_modal'];
}
mysqli_data_seek($data,0);
?>

<div class="alert alert-info">
<b>Total Modal Keseluruhan :</b>
Rp <?= number_format($total_modal_keseluruhan) ?>
</div>

<div class="table-responsive">
<table class="table table-bordered table-striped">

<tr>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Satuan</th>
<th>Keterangan</th>
<th>Stok Awal</th>
<th>Stok Masuk</th>
<th>Stok Keluar</th>
<th>Stok Akhir</th>
<th>Harga Modal</th>
<th>Total Modal</th>
</tr>

<?php while($d=mysqli_fetch_assoc($data)){ 

$stok_akhir = $d['stok_awal'] + $d['stok_masuk'] - $d['stok_keluar'];
$total_modal = $stok_akhir * $d['harga_modal'];

?>

<tr>

<td><?= $d['kode_barang']?></td>
<td><?= $d['nama_barang']?></td>
<td><?= $d['kategori']?></td>
<td><?= $d['satuan']?></td>
<td><?= $d['keterangan']?></td>
<td><?= $d['stok_awal']?></td>
<td><?= $d['stok_masuk']?></td>
<td><?= $d['stok_keluar']?></td>

<td><b><?= $stok_akhir?></b></td>

<td><?= number_format($d['harga_modal']) ?></td>

<td><b><?= number_format($total_modal) ?></b></td>

</tr>

<?php } ?>

</table>
</div>

</div>

<script>
function toggleSidebar(){
document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>