<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION["login"])) {
    header("Location: logout.php");
    exit;
}
$username = $_COOKIE['username'];
$result1 = mysqli_query($koneksi, "SELECT * FROM account WHERE username = '$username'");
$data1 = mysqli_fetch_array($result1);
$jabatan_valid = $data1['jabatan'];
if ($jabatan_valid == 'Admin Penjualan') {
} else {
    header("Location: logout.php");
    exit;
}
$tanggal_awal = $_POST['tanggal1'];
$tanggal_akhir = $_POST['tanggal2'];
$no_laporan = $_POST['no_laporan'];

$query = mysqli_query($koneksi,"DELETE FROM laporan_inventory WHERE no_laporan = '$no_laporan'");

$table = mysqli_query($koneksi, "SELECT * FROM laporan_inventory ORDER BY no_laporan DESC LIMIT 1 ");
$data = mysqli_fetch_array($table);


if (!isset($data['L03K01'])) {
	$L03K01 = 0;
} else {

	$L03K01 = $data['L03K01'];
}

if (!isset($data['L03K10'])) {
	$L03K10 = 0;
} else {

	$L03K10 = $data['L03K10'];
}

if (!isset($data['L03K00'])) {
	$L03K00 = 0;
} else {

	$L03K00 = $data['L03K00'];
}

if (!isset($data['B05K01'])) {
	$B05K01 = 0;
} else {

	$B05K01 = $data['B05K01'];
}

if (!isset($data['B05K10'])) {
	$B05K10 = 0;
} else {

	$B05K10 = $data['B05K10'];
}

if (!isset($data['B05K00'])) {
	$B05K00 = 0;
} else {

	$B05K00 = $data['B05K00'];
}

if (!isset($data['B12K01'])) {
	$B12K01 = 0;
} else {

	$B12K01 = $data['B12K01'];
}

if (!isset($data['B12K10'])) {
	$B12K10 = 0;
} else {

	$B12K10 = $data['B12K10'];
}

if (!isset($data['B12K00'])) {
	$B12K00 = 0;
} else {

	$B12K00 = $data['B12K00'];
}



            mysqli_query($koneksi,"UPDATE laporan_inventory SET L03K01 = '$L03K01', L03K11 = '$L03K01', L03K10 = '$L03K10', L03K00 = '$L03K00', B05K01 = '$B05K01', B05K11 = '$B05K01', B05K10 = '$B05K10', B05K00 = '$B05K00', B12K01 = '$B12K01', B12K11 = '$B12K01', B12K10 = '$B12K10', B12K00 = '$B12K00' WHERE no_laporan = 'no_laporan' ");

            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$L03K01' WHERE kode_tabung = 'L03K01' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$L03K01' WHERE kode_tabung = 'L03K11' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$L03K10' WHERE kode_tabung = 'L03K10' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$L03K00' WHERE kode_tabung = 'L03K00' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B05K01' WHERE kode_tabung = 'B05K01' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B05K01' WHERE kode_tabung = 'B05K11' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B05K10' WHERE kode_tabung = 'B05K10' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B05K00' WHERE kode_tabung = 'B05K00' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B12K01' WHERE kode_tabung = 'B12K01' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B12K01' WHERE kode_tabung = 'B12K11' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B12K10' WHERE kode_tabung = 'B12K10' ");
            mysqli_query($koneksi,"UPDATE inventory SET jumlah_tabung = '$B12K00' WHERE kode_tabung = 'B12K00' ");


            echo "<script>alert('Data Laporan Inventory Berhasil di Input'); window.location='../view/VLaporanInventory?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir';</script>";exit;
     

     
        

       


  ?>