<?php
session_start();

if(!isset($_SESSION['login'])){
header("Location:../login.php");
exit;
}

include "../inc/koneksi.php";

/* ===============================
SEARCH BAR
=============================== */
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';

$where="";
if($cari!=""){
$where="WHERE barang.nama_barang LIKE '%$cari%' 
OR barang.kode_barang LIKE '%$cari%'";
}

/*
==================================================
PERBAIKAN:
STOK SISTEM & SELISIH REALTIME
==================================================
*/
$data = mysqli_query($conn,"
SELECT 
stok_opname.id,
stok_opname.tanggal,
stok_opname.kode_barang,
stok_opname.stok_fisik,

barang.nama_barang,
barang.keterangan AS keterangan_barang,

(
barang.stok_awal
+ IFNULL((SELECT SUM(jumlah) FROM stok_masuk WHERE kode_barang=barang.kode_barang),0)
- IFNULL((SELECT SUM(jumlah) FROM stok_keluar WHERE kode_barang=barang.kode_barang),0)
) AS stok_sistem,

(
stok_opname.stok_fisik -
(
barang.stok_awal
+ IFNULL((SELECT SUM(jumlah) FROM stok_masuk WHERE kode_barang=barang.kode_barang),0)
- IFNULL((SELECT SUM(jumlah) FROM stok_keluar WHERE kode_barang=barang.kode_barang),0)
)
) AS selisih

FROM stok_opname
JOIN barang ON barang.kode_barang = stok_opname.kode_barang
$where
ORDER BY stok_opname.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Stok Opname</title>

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

.sidebar h4{ padding:15px; }

.sidebar a{
display:block;
padding:12px 18px;
color:white;
text-decoration:none;
}

.sidebar a:hover{ background:#1e90ff; }

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

.table-responsive{ overflow-x:auto; }

@media(max-width:768px){
.toggle-btn{ display:block; }
.sidebar{ left:-240px; }
.sidebar.active{ left:0; }
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
<a href="../rekap/rekap_stok.php">Rekap Stok</a>
<a href="stok_opname.php">Stok Opname</a>

<hr>
<a href="../logout.php" style="background:#dc3545;">Logout</a>
</div>

<div class="content">

<h3>Stok Opname</h3>

<div class="mb-2">
<button class="btn btn-success btn-sm"
data-bs-toggle="modal"
data-bs-target="#modalOpname">
Tambah Opname
</button>

<a href="proses_opname.php?hapus_semua=1"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus semua data opname?')">
Hapus Semua
</a>
</div>

<br>

<form class="mb-3">
<input name="cari" class="form-control"
placeholder="Cari barang..."
value="<?= $cari ?>">
</form>

<div class="table-responsive">
<table class="table table-bordered table-striped">

<tr>
<th>No</th>
<th>Tanggal</th>
<th>Kode</th>
<th>Nama Barang</th>
<th>Keterangan</th>
<th>Stok Sistem</th>
<th>Stok Fisik</th>
<th>Selisih</th>
<th>Status</th>
<th>Aksi</th>
</tr>

<?php $no=1; while($d=mysqli_fetch_assoc($data)){ ?>

<tr>

<td><?= $no++ ?></td>
<td><?= $d['tanggal']?></td>
<td><?= $d['kode_barang']?></td>
<td><?= $d['nama_barang']?></td>
<td><?= $d['keterangan_barang']?></td>
<td><?= $d['stok_sistem']?></td>
<td><?= $d['stok_fisik']?></td>
<td><?= $d['selisih']?></td>

<td>
<?php
if($d['selisih']==0){
echo "<span class='badge bg-success'>Sesuai</span>";
}else{
echo "<span class='badge bg-danger'>Selisih</span>";
}
?>
</td>

<td>

<button 
class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit<?= $d['id']?>">✏</button>

<a href="proses_opname.php?hapus=<?= $d['id']?>"
class="btn btn-danger btn-sm">🗑</a>

</td>

</tr>

<!-- MODAL EDIT TIDAK DIHILANGKAN -->
<div class="modal fade" id="edit<?= $d['id']?>">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="proses_opname.php">

<input type="hidden" name="id" value="<?= $d['id']?>">
<input type="hidden" name="kode_barang" value="<?= $d['kode_barang']?>">

<div class="modal-header">
<h5>Edit Stok Opname</h5>
</div>

<div class="modal-body">

<input type="date" name="tanggal"
class="form-control mb-2"
value="<?= $d['tanggal']?>" required>

<input type="number" name="stok_fisik"
class="form-control mb-2"
value="<?= $d['stok_fisik']?>" required>

</div>

<div class="modal-footer">
<button name="update" class="btn btn-success">Update</button>
</div>

</form>

</div>
</div>
</div>

<?php } ?>

</table>
</div>

</div>

<div class="modal fade" id="modalOpname">
<div class="modal-dialog">
<div class="modal-content">

<form method="POST" action="proses_opname.php">

<div class="modal-header">
<h5>Tambah Stok Opname</h5>
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

<input type="number" name="stok_fisik"
class="form-control mb-2"
placeholder="Stok Fisik"
required>

</div>

<div class="modal-footer">
<button name="simpan" class="btn btn-success">Simpan</button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function toggleSidebar(){
document.querySelector(".sidebar").classList.toggle("active");
}
</script>

</body>
</html>