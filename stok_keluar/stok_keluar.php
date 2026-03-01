<?php
session_start();

if(!isset($_SESSION['login'])){
header("Location:../login.php");
exit;
}

include "../inc/koneksi.php";

/*
=============================
TAMBAHAN SEARCH BARANG
=============================
*/

$cari   = isset($_GET['cari']) ? $_GET['cari'] : '';
$dari   = isset($_GET['dari']) ? $_GET['dari'] : '';
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : '';

$where="WHERE 1=1";

if($dari!="" && $sampai!=""){
$where.=" AND tanggal BETWEEN '$dari' AND '$sampai'";
}

if($cari!=""){
$where.=" AND (
barang.nama_barang LIKE '%$cari%' 
OR stok_keluar.kode_barang LIKE '%$cari%'
)";
}

$data = mysqli_query($conn,"
SELECT stok_keluar.*, barang.nama_barang 
FROM stok_keluar
JOIN barang ON stok_keluar.kode_barang=barang.kode_barang
$where
ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Stok Keluar</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#f1f2f6;
font-family:Arial;
margin:0;
}

/* SIDEBAR */
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

label{
font-size:13px;
font-weight:600;
margin-bottom:3px;
}

/* TOGGLE BUTTON */
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

/* RESPONSIVE */
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
<a href="stok_keluar.php">Stok Keluar</a>
<a href="../rekap/rekap_stok.php">Rekap Stok</a>
<a href="../stok_opname/stok_opname.php">Stok Opname</a>

<hr>
<a href="../logout.php" style="background:#dc3545;">Logout</a>
</div>

<div class="content">

<h3>Stok Keluar</h3>

<a href="export_keluar_excel.php" class="btn btn-info btn-sm">Export Excel</a>
<a href="export_keluar_pdf.php" class="btn btn-warning btn-sm">Export PDF</a>
<a href="proses_keluar.php?hapus_semua=1"
    class="btn btn-danger btn-sm"
    onclick="return confirm('Apakah yakin ingin menghapus semua data stok keluar?')">
    Hapus Semua Data
</a>

<button class="btn btn-success btn-sm"
data-bs-toggle="modal"
data-bs-target="#modalKeluar">
Tambah Stok Keluar
</button>

<br><br>

<form class="row mb-3 g-2">

<div class="col-lg-3 col-md-4 col-12">
<label>Dari</label>
<input type="date" name="dari" class="form-control" value="<?= $dari ?>">
</div>

<div class="col-lg-3 col-md-4 col-12">
<label>Sampai</label>
<input type="date" name="sampai" class="form-control" value="<?= $sampai ?>">
</div>

<div class="col-lg-2 col-md-4 col-12 align-self-end">
<button class="btn btn-primary btn-sm">Filter</button>
</div>

<div class="col-lg-3 col-md-4 col-12">
<label>Cari Barang</label>
<input name="cari" class="form-control" placeholder="Nama / Kode..." value="<?= $cari ?>">
</div>

</form>

<div class="table-responsive">
<table class="table table-bordered table-striped">

<tr>
<th>No</th>
<th>Tanggal</th>
<th>Kode</th>
<th>Nama Barang</th>
<th>Jumlah</th>
<th>Penerima</th>
<th>Keterangan</th>
<th>Aksi</th>
</tr>

<?php 
$no=1; 
mysqli_data_seek($data,0);
while($d=mysqli_fetch_assoc($data)){ 
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $d['tanggal']?></td>
<td><?= $d['kode_barang']?></td>
<td><?= $d['nama_barang']?></td>
<td><?= $d['jumlah']?></td>
<td><?= $d['penerima']?></td>
<td><?= $d['keterangan']?></td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit<?= $d['id']?>">✏</button>

<a href="proses_keluar.php?hapus=<?= $d['id']?>"
class="btn btn-danger btn-sm">🗑</a>

</td>
</tr>

<?php } ?>

</table>
</div>

</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalKeluar">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="proses_keluar.php">

<div class="modal-header">
<h5>Tambah Stok Keluar</h5>
</div>

<div class="modal-body">

<select name="kode_barang" class="form-control mb-2">
<?php
$q=mysqli_query($conn,"SELECT * FROM barang");
while($b=mysqli_fetch_assoc($q)){
echo "<option value='$b[kode_barang]'>
$b[kode_barang] - $b[nama_barang]
</option>";
}
?>
</select>

<input type="date" name="tanggal" class="form-control mb-2" required>
<input type="number" name="jumlah" class="form-control mb-2" placeholder="Jumlah">
<input name="penerima" class="form-control mb-2" placeholder="Penerima">

</div>

<div class="modal-footer">
<button name="simpan" class="btn btn-success">Simpan</button>
</div>

</form>

</div>
</div>
</div>

<?php 
mysqli_data_seek($data,0);
while($d=mysqli_fetch_assoc($data)){ 
?>
<div class="modal fade" id="edit<?= $d['id']?>">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="proses_keluar.php">

<input type="hidden" name="id" value="<?= $d['id']?>">

<div class="modal-header">
<h5>Edit Stok Keluar</h5>
</div>

<div class="modal-body">

<select name="kode_barang" class="form-control mb-2">
<?php
$q2=mysqli_query($conn,"SELECT * FROM barang");
while($b2=mysqli_fetch_assoc($q2)){
$sel = ($b2['kode_barang']==$d['kode_barang'])?"selected":"";
echo "<option $sel value='$b2[kode_barang]'>
$b2[kode_barang] - $b2[nama_barang]
</option>";
}
?>
</select>

<input type="date" name="tanggal" value="<?= $d['tanggal']?>" class="form-control mb-2" required>
<input type="number" name="jumlah" value="<?= $d['jumlah']?>" class="form-control mb-2" placeholder="Jumlah">
<input name="penerima" value="<?= $d['penerima']?>" class="form-control mb-2" placeholder="Penerima">

</div>

<div class="modal-footer">
<button name="update" class="btn btn-primary">Update</button>
</div>

</form>

</div>
</div>
</div>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar(){
document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>