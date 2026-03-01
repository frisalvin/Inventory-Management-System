<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=template_barang.xls");

echo "nama_barang\tkategori\tsatuan\tharga_modal\tketerangan\tstok_awal\n";
echo "Handle\tperpintuan\tpcs\t100000\tBarang_baru\t100\n";
echo "Smart_Door\tengsel\tunit\t250000\tWarna_kuning\t50\n";
?>