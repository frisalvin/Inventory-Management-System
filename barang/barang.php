<?php
session_start();

if(!isset($_SESSION['login'])){
header("Location:../login.php");
exit;
}

include "../inc/koneksi.php";

$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
$data = mysqli_query($conn,"SELECT * FROM barang 
WHERE nama_barang LIKE '%$cari%' OR kode_barang LIKE '%$cari%' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Master Barang</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f1f2f6;
font-family:Arial;
}

/* ===== SIDEBAR ===== */
.sidebar{
width:230px;
height:100vh;
position:fixed;
background:#2f3542;
color:white;
left:0;
top:0;
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

/* ===== CONTENT ===== */
.content{
margin-left:240px;
padding:25px;
}

/* ===== TOGGLE BUTTON ===== */
.toggle-btn{
display:none;
position:fixed;
top:10px;
left:10px;
background:#2f3542;
color:white;
border:none;
padding:8px 12px;
border-radius:5px;
z-index:9999;
}

/* ===== RESPONSIVE ===== */
@media(max-width:768px){

.sidebar{
left:-240px;
transition:0.3s;
z-index:999;
}

.sidebar.active{
left:0;
}

.content{
margin-left:0;
padding-top:60px;
}

.toggle-btn{
display:block;
}

}

</style>
</head>

<body>

<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<div class="sidebar">

<h4>Inventory</h4>

<a href="../index.php">Dashboard</a>
<a href="barang.php">Master Barang</a>
<a href="../stok_masuk/stok_masuk.php">Stok Masuk</a>
<a href="../stok_keluar/stok_keluar.php">Stok Keluar</a>
<a href="../rekap/rekap_stok.php">Rekap Stok</a>
<a href="../stok_opname/stok_opname.php">Stok Opname</a>

<hr>
<a href="../logout.php" style="background:#dc3545;">Logout</a>

</div>

<div class="content">

<h3>Master Barang</h3>

<div class="mb-3 d-flex flex-wrap gap-2">

<a href="#" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">Import Excel</a>

<a href="template_import_barang.php" class="btn btn-secondary btn-sm">Download Template</a>

<a href="export_excel.php" class="btn btn-info btn-sm">Export Excel</a>
<a href="export_pdf.php" class="btn btn-success btn-sm">Export PDF</a>

<a href="proses_barang.php?hapus_semua=1"
class="btn btn-danger btn-sm"
onclick="return confirm('Apakah yakin ingin menghapus semua data barang?')">
Hapus Semua Data
</a>

<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Barang</button>

</div>

<form>
<input name="cari" class="form-control" placeholder="Cari barang..." value="<?= $cari ?>">
</form>

<br>

<div class="table-responsive">
<table class="table table-bordered table-striped">
<tr>
<th>Kode</th>
<th>Nama</th>
<th>Kategori</th>
<th>Satuan</th>
<th>Harga Modal</th>
<th>Keterangan</th>
<th>Stok</th>
<th>Aksi</th>
</tr>

<?php while($d=mysqli_fetch_assoc($data)){ ?>

<tr>
<td><?= $d['kode_barang']?></td>
<td><?= $d['nama_barang']?></td>
<td><?= $d['kategori']?></td>
<td><?= $d['satuan']?></td>
<td><?= number_format($d['harga_modal'])?></td>
<td><?= $d['keterangan']?></td>
<td><?= $d['stok_awal']?></td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit<?= $d['id']?>">✏</button>

<a href="proses_barang.php?hapus=<?= $d['id']?>" class="btn btn-danger btn-sm">🗑</a>

</td>
</tr>

<div class="modal fade" id="edit<?= $d['id']?>">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST" action="proses_barang.php">

<div class="modal-header">
<h5>Edit Barang</h5>
</div>

<div class="modal-body">

<input type="hidden" name="id" value="<?= $d['id']?>">

<input name="nama_barang" class="form-control mb-2" placeholder="Nama Barang" value="<?= $d['nama_barang']?>" required>
<input name="kategori" class="form-control mb-2" placeholder="Kategori" value="<?= $d['kategori']?>">
<input name="satuan" class="form-control mb-2" placeholder="Satuan" value="<?= $d['satuan']?>">
<input type="number" name="harga_modal" min="0" step="1" class="form-control mb-2" placeholder="Harga Modal" value="<?= $d['harga_modal']?>">
<input name="keterangan" class="form-control mb-2" placeholder="Keterangan" value="<?= $d['keterangan']?>">
<input type="number" name="stok_awal" min="0" step="1" class="form-control mb-2" placeholder="Stok" value="<?= $d['stok_awal']?>">

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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahModal">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST" action="proses_barang.php">

<div class="modal-header">
<h5>Tambah Barang</h5>
</div>

<div class="modal-body">

<input name="nama_barang" class="form-control mb-2" placeholder="Nama Barang" required>
<input name="kategori" class="form-control mb-2" placeholder="Kategori">
<input name="satuan" class="form-control mb-2" placeholder="Satuan">
<input type="number" name="harga_modal" min="0" step="1" class="form-control mb-2" placeholder="Harga Modal">
<input name="keterangan" class="form-control mb-2" placeholder="Keterangan">
<input type="number" name="stok_awal" min="0" step="1" class="form-control mb-2" placeholder="Stok">

</div>

<div class="modal-footer">
<button name="simpan" class="btn btn-success">Simpan</button>
</div>

</form>
</div>
</div>
</div>

<!-- MODAL IMPORT -->
<div class="modal fade" id="importModal">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST" action="import_excel.php" enctype="multipart/form-data">

<div class="modal-header">
<h5>Import Excel</h5>
</div>

<div class="modal-body">

<input type="file" name="file" required>

<hr>

<b>Format Template:</b><br>
nama_barang | kategori | satuan | harga_modal | keterangan | stok_awal

</div>

<div class="modal-footer">
<button class="btn btn-primary">Upload</button>
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