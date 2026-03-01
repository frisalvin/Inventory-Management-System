<?php
include "../inc/koneksi.php";

$data=mysqli_query($conn,"
SELECT stok_keluar.*, barang.nama_barang 
FROM stok_keluar
JOIN barang ON stok_keluar.kode_barang=barang.kode_barang
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Export PDF Stok Keluar</title>

<style>

body{
font-family:Arial, Helvetica, sans-serif;
font-size:12px;
color:#000;
}

.header{
text-align:center;
margin-bottom:15px;
}

.header h2{
margin:0;
}

.header p{
margin:3px;
font-size:12px;
}

table{
border-collapse:collapse;
width:100%;
margin-top:10px;
}

th{
background:#f2f2f2;
border:1px solid #000;
padding:7px;
text-align:center;
}

td{
border:1px solid #000;
padding:6px;
}

.text-center{text-align:center;}
.text-right{text-align:right;}

</style>

</head>
<body>

<div class="header">
<h2>LAPORAN DATA STOK KELUAR</h2>
<p>Tanggal Export : <?= date('d-m-Y H:i') ?></p>
</div>

<table>
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Kode</th>
<th>Nama Barang</th>
<th>Jumlah</th>
<th>Penerima</th>
<th>Keterangan</th>
</tr>

<?php 
$no=1; 
while($d=mysqli_fetch_assoc($data)){ 
?>

<tr>
<td class="text-center"><?= $no++ ?></td>
<td class="text-center"><?= date('d-m-Y', strtotime($d['tanggal'])) ?></td>
<td class="text-center"><?= $d['kode_barang']?></td>
<td><?= $d['nama_barang']?></td>
<td class="text-center"><b><?= $d['jumlah']?></b></td>
<td><?= $d['penerima']?></td>
<td><?= $d['keterangan']?></td>
</tr>

<?php } ?>

</table>

<script>
window.print();
</script>

</body>
</html>