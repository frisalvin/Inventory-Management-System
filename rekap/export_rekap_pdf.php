<?php
include "../inc/koneksi.php";

$data=mysqli_query($conn,"
SELECT 
barang.kode_barang,
barang.nama_barang,
barang.kategori,
barang.satuan,
barang.keterangan,
barang.harga_modal,
barang.stok_awal,

IFNULL((SELECT SUM(jumlah) FROM stok_masuk 
WHERE kode_barang=barang.kode_barang),0) AS stok_masuk,

IFNULL((SELECT SUM(jumlah) FROM stok_keluar 
WHERE kode_barang=barang.kode_barang),0) AS stok_keluar

FROM barang
ORDER BY barang.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Export Rekap Stok</title>

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
margin:2px;
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

.footer{
margin-top:20px;
width:100%;
}

</style>

</head>
<body>

<div class="header">
<h2>LAPORAN REKAP STOK BARANG</h2>
<p>Tanggal Export : <?= date('d-m-Y H:i') ?></p>
</div>

<table>
<tr>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Keterangan</th>
<th>Satuan</th>
<th>Stok Awal</th>
<th>Stok Masuk</th>
<th>Stok Keluar</th>
<th>Stok Akhir</th>
<th>Harga Modal</th>
<th>Total Modal</th>
</tr>

<?php 
$total_modal_keseluruhan = 0;

while($d=mysqli_fetch_assoc($data)){ 

$stok_akhir = $d['stok_awal'] + $d['stok_masuk'] - $d['stok_keluar'];
$total_modal = $stok_akhir * $d['harga_modal'];

$total_modal_keseluruhan += $total_modal;
?>

<tr>
<td class="text-center"><?= $d['kode_barang']?></td>
<td><?= $d['nama_barang']?></td>
<td><?= $d['kategori']?></td>
<td><?= $d['keterangan']?></td>
<td class="text-center"><?= $d['satuan']?></td>
<td class="text-center"><?= $d['stok_awal']?></td>
<td class="text-center"><?= $d['stok_masuk']?></td>
<td class="text-center"><?= $d['stok_keluar']?></td>
<td class="text-center"><b><?= $stok_akhir ?></b></td>
<td class="text-right">Rp <?= number_format($d['harga_modal']) ?></td>
<td class="text-right"><b>Rp <?= number_format($total_modal) ?></b></td>
</tr>

<?php } ?>

<tr>
<td colspan="10" class="text-right"><b>Total Modal Keseluruhan</b></td>
<td class="text-right"><b>Rp <?= number_format($total_modal_keseluruhan) ?></b></td>
</tr>

</table>

<script>
window.print();
</script>

</body>
</html>