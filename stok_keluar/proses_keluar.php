<?php
include "../inc/koneksi.php";

/*
====================================================
SIMPAN DATA STOK KELUAR
====================================================
*/

if(isset($_POST['simpan'])){

    $kode_barang = $_POST['kode_barang'];
    $tanggal     = $_POST['tanggal'];
    $jumlah      = $_POST['jumlah'];
    $penerima    = $_POST['penerima'];

    /* AMBIL KETERANGAN DARI MASTER BARANG */
    $q = mysqli_query($conn,"SELECT keterangan FROM barang WHERE kode_barang='$kode_barang'");
    $d = mysqli_fetch_assoc($q);
    $keterangan = $d['keterangan'];

    mysqli_query($conn,"
        INSERT INTO stok_keluar
        VALUES(NULL,'$tanggal','$kode_barang','$jumlah','$penerima','$keterangan')
    ");

    header("Location: stok_keluar.php");
}


/*
====================================================
UPDATE DATA (EDIT)
====================================================
*/

if(isset($_POST['update'])){

    $id          = $_POST['id'];
    $kode_barang = $_POST['kode_barang'];
    $tanggal     = $_POST['tanggal'];
    $jumlah      = $_POST['jumlah'];
    $penerima    = $_POST['penerima'];

    /* AMBIL KETERANGAN MASTER BARANG */
    $q = mysqli_query($conn,"SELECT keterangan FROM barang WHERE kode_barang='$kode_barang'");
    $d = mysqli_fetch_assoc($q);
    $keterangan = $d['keterangan'];

    mysqli_query($conn,"
        UPDATE stok_keluar SET
        tanggal='$tanggal',
        kode_barang='$kode_barang',
        jumlah='$jumlah',
        penerima='$penerima',
        keterangan='$keterangan'
        WHERE id='$id'
    ");

    header("Location: stok_keluar.php");
}


/*
====================================================
HAPUS SATU DATA
====================================================
*/

if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"
        DELETE FROM stok_keluar WHERE id='$id'
    ");

    header("Location: stok_keluar.php");
}


/*
====================================================
HAPUS SEMUA DATA
====================================================
*/

if(isset($_GET['hapus_semua'])){

    mysqli_query($conn,"TRUNCATE stok_keluar");

    header("Location: stok_keluar.php");
}
?>