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

$no_registrasi = htmlspecialchars($_POST['no_registrasi']);

$sql_cek = mysqli_query($koneksi, "SELECT * FROM customer WHERE no_registrasi = '$no_registrasi' ");
       
if(mysqli_num_rows($sql_cek) === 1 ){
    echo "<script>alert('No Registrasi Sudah Terdaftar'); window.location='../view/VListCustomer';</script>";exit;
}

$agen = htmlspecialchars($_POST['agen']);
$nama_customer = htmlspecialchars($_POST['nama_customer']);
$pemilik = htmlspecialchars($_POST['pemilik']);
$jenis_pembayaran = htmlspecialchars($_POST['jenis_pembayaran']);
$tipe = htmlspecialchars($_POST['tipe']);
$alamat = htmlspecialchars($_POST['alamat']);
$no_hp = htmlspecialchars($_POST['no_hp']);
$no_ktp = htmlspecialchars($_POST['no_ktp']);
$harga_3kg = htmlspecialchars($_POST['harga_3kg']);
$harga_55kg = htmlspecialchars($_POST['harga_55kg']);
$harga_12kg = htmlspecialchars($_POST['harga_12kg']);
$qty_kontrak = htmlspecialchars($_POST['qty_kontrak']);
$status = 'Aktif';



              
                $query = mysqli_query($koneksi,"INSERT INTO customer VALUES('$no_registrasi','$agen','$nama_customer','$pemilik','$jenis_pembayaran','$tipe','$alamat','$no_hp','$no_ktp','$harga_3kg','$harga_55kg','$harga_12kg','$qty_kontrak','$status')");
           
                   echo "<script>alert('Data Customer Berhasil di input'); window.location='../view/VListCustomer';</script>";exit;


    

