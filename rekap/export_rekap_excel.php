<?php
include "../inc/koneksi.php";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=rekap_stok_barang.xls");

/*
=========================================
QUERY REKAP STOK + HARGA MODAL
=========================================
*/

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

/*
=========================================
HEADER KOLOM EXCEL
=========================================
*/

echo "Kode Barang\tNama Barang\tKategori\tSatuan\tKeterangan\tStok Awal\tStok Masuk\tStok Keluar\tStok Akhir\tHarga Modal\tTotal Modal\n";

/*
=========================================
ISI DATA
=========================================
*/

$total_modal_keseluruhan = 0; // ✅ penampung total

while($d=mysqli_fetch_assoc($data)){

$stok_akhir = $d['stok_awal'] + $d['stok_masuk'] - $d['stok_keluar'];
$total_modal = $stok_akhir * $d['harga_modal'];

// akumulasi total modal keseluruhan
$total_modal_keseluruhan += $total_modal;

echo "$d[kode_barang]\t$d[nama_barang]\t$d[kategori]\t$d[satuan]\t$d[keterangan]\t$d[stok_awal]\t$d[stok_masuk]\t$d[stok_keluar]\t$stok_akhir\t$d[harga_modal]\t$total_modal\n";

}

/*
=========================================
TOTAL MODAL KESELURUHAN DI BAWAH
=========================================
*/

echo "\n";
echo "TOTAL MODAL KESELURUHAN\t\t\t\t\t\t\t\t\t\t$total_modal_keseluruhan\n";

?>