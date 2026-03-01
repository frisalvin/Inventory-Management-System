<?php
include "../inc/koneksi.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=stok_masuk.xls");

echo "Tanggal\tKode\tJumlah\tSupplier\tKeterangan\n";

$q=mysqli_query($conn,"SELECT * FROM stok_masuk");

while($d=mysqli_fetch_assoc($q)){
echo "$d[tanggal]\t$d[kode_barang]\t$d[jumlah]\t$d[supplier]\t$d[keterangan]\n";
}