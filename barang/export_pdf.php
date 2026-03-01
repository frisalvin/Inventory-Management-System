<?php
include "../inc/koneksi.php";

$data = mysqli_query($conn,"
SELECT * FROM barang
ORDER BY kode_barang DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Export Master Barang</title>

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
<h2>LAPORAN MASTER DATA BARANG</h2>
<p>Tanggal Export : <?= date('d-m-Y H:i') ?></p>
</div>

<table>

<tr>
<th>No</th>
<th>Kode</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Satuan</th>
<th>Harga Modal</th>
<th>Keterangan</th>
<th>Stok Awal</th>
</tr>

<?php 
$no=1; 
while($d=mysqli_fetch_assoc($data)){ 
?>

<tr>
<td class="text-center"><?= $no++ ?></td>
<td class="text-center"><?= $d['kode_barang']?></td>
<td><?= $d['nama_barang']?></td>
<td><?= $d['kategori']?></td>
<td class="text-center"><?= $d['satuan']?></td>
<td class="text-right">Rp <?= number_format($d['harga_modal']) ?></td>
<td><?= $d['keterangan']?></td>
<td class="text-center"><?= $d['stok_awal']?></td>
</tr>

<?php } ?>

</table>

<script>
window.print();
</script>

</body>
</html>