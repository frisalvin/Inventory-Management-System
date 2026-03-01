<?php
include "../inc/koneksi.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=stok_keluar.xls");

echo "Tanggal\tKode\tJumlah\tPenerima\tKeterangan\n";

$q=mysqli_query($conn,"SELECT * FROM stok_keluar");

while($d=mysqli_fetch_assoc($q)){
echo "$d[tanggal]\t$d[kode_barang]\t$d[jumlah]\t$d[penerima]\t$d[keterangan]\n";
}
?>