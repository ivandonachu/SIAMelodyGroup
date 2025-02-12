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

$harga_3kg = htmlspecialchars($_POST['harga_3kg']);
$harga_55kg = htmlspecialchars($_POST['harga_55kg']);
$harga_12kg = htmlspecialchars($_POST['harga_12kg']);


$table = mysqli_query($koneksi, "SELECT harga_3kg, harga_55kg, harga_12kg FROM customer");
$data = mysqli_fetch_array($table);

if ($harga_3kg == '') {

    $harga_3kg =  $data['harga_3kg'];
}
if ($harga_55kg == '') {

    $harga_55kg = $data['harga_55kg'];
}
if ($harga_12kg == '') {

    $harga_12kg = $data['harga_12kg'];
}


mysqli_query($koneksi, "UPDATE customer SET  harga_3kg = '$harga_3kg' , harga_55kg = '$harga_55kg' , harga_12kg = '$harga_12kg'");
echo "<script>alert('Data Harga Berhasil di Ubah'); window.location='../view/VListCustomer';</script>";exit;
