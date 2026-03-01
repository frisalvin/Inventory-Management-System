<?php
include "../inc/koneksi.php";

if(isset($_FILES['file'])){

$file = $_FILES['file']['tmp_name'];

$handle = fopen($file,"r");

$no = 0;

while(($row = fgets($handle)) !== false){

    // skip header
    if($no==0){
        $no++;
        continue;
    }

    /*
    ==============================
    AUTO SPLIT (KOMA ATAU SPASI)
    ==============================
    */
    if(strpos($row,",") !== false){
        $data = explode(",",$row);
    }else{
        $data = preg_split('/\s+/',$row);
    }

    $nama_barang = trim($data[0]);
    $kategori    = trim($data[1]);
    $satuan      = trim($data[2]);
    $harga_modal    = trim($data[3]);
    $keterangan  = trim($data[4]);
    $stok_awal   = trim($data[5]);

    // kode otomatis
    $q = mysqli_query($conn,"SELECT max(id) as id FROM barang");
    $d = mysqli_fetch_assoc($q);
    $kode = "BRG".str_pad($d['id']+1,3,"0",STR_PAD_LEFT);

    mysqli_query($conn,"
    INSERT INTO barang
    (kode_barang,nama_barang,kategori,satuan,harga_modal,keterangan,stok_awal)
    VALUES(
        '$kode',
        '$nama_barang',
        '$kategori',
        '$satuan',
        '$harga_modal',
        '$keterangan',
        '$stok_awal'
    )");

    $no++;
}

fclose($handle);

header("location:barang.php");

}
?>