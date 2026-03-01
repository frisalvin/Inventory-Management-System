<?php
include "../inc/koneksi.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=data_barang.xls");

echo "Kode\tNama\tKategori\tSatuan\tHarga Modal\tKeterangan\tStok\n";

$q=mysqli_query($conn,"SELECT * FROM barang");
while($d=mysqli_fetch_assoc($q)){
echo "$d[kode_barang]\t$d[nama_barang]\t$d[kategori]\t$d[satuan]\t$d[harga_modal]\t$d[keterangan]\t$d[stok_awal]\n";
}
?>