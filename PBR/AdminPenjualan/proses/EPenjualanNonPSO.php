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
$tanggal_awal = htmlspecialchars($_POST['tanggal1']);
$tanggal_akhir = htmlspecialchars($_POST['tanggal2']);
$no_penjualan = htmlspecialchars($_POST['no_penjualan']);
$tanggal = htmlspecialchars($_POST['tanggal']);
$nama_akun = htmlspecialchars($_POST['nama_akun']);


if (!isset($_POST['nama_customer'])) {
    $nama_customer = "";
} else {
    $nama_customer = htmlspecialchars($_POST['nama_customer']);
}


if ($nama_customer == "") {
    echo "<script>alert('Nama Customer Tidak Boleh Kosong'); window.location='../view/VPenjualan?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir';</script>";
    exit;
}
$pembayaran = htmlspecialchars($_POST['pembayaran']);

//akses data pangkalan
$sql_customer = mysqli_query($koneksi, "SELECT no_registrasi, harga_3kg FROM customer WHERE nama_customer = '$nama_customer'");
$data_customer = mysqli_fetch_array($sql_customer);
$no_registrasi = $data_customer['no_registrasi'];




$qty_55kg = htmlspecialchars($_POST['qty_55kg']);
$harga_55kg = htmlspecialchars($_POST['harga_55kg']);
$jumlah_55kg = $qty_55kg * $harga_55kg;

$qty_12kg = htmlspecialchars($_POST['qty_12kg']);
$harga_12kg = htmlspecialchars($_POST['harga_12kg']);
$jumlah_12kg = $qty_12kg * $harga_12kg;

$jumlah = $jumlah_12kg + $jumlah_55kg;

$status_penjualan = htmlspecialchars($_POST['status_penjualan']);
$keterangan = htmlspecialchars($_POST['keterangan']);
$nama_file = $_FILES['file']['name'];

if ($nama_file == "") {
    $file = "";
} else if ($nama_file != "") {

    function upload()
    {
        $nama_file = $_FILES['file']['name'];
        $ukuran_file = $_FILES['file']['size'];
        $error = $_FILES['file']['error'];
        $tmp_name = $_FILES['file']['tmp_name'];

        $ekstensi_valid = ['jpg', 'jpeg', 'pdf', 'doc', 'docs', 'xls', 'xlsx', 'docx', 'txt', 'png'];
        $ekstensi_file = explode(".", $nama_file);
        $ekstensi_file = strtolower(end($ekstensi_file));


        $nama_file_baru = uniqid();
        $nama_file_baru .= ".";
        $nama_file_baru .= $ekstensi_file;

        move_uploaded_file($tmp_name, '../file_admin_pso/' . $nama_file_baru);

        return $nama_file_baru;
    }

    $file = upload();
    if (!$file) {
        return false;
    }
}




if ($status_penjualan == 'Lunas') {
    $referensi_debit = $pembayaran;
    if ($nama_akun == 'Penjualan Refill' || $nama_akun == 'Penjualan Tabung Isi') {

        $referensi_kredit_55 = 'Persediaan Tabung isi 5,5 kg';
        $referensi_kredit_12 = 'Persediaan Tabung Isi 12 kg';
    } else {

        $referensi_kredit_55 = 'Persediaan Tabung Kosong 5,5 kg';
        $referensi_kredit_12 = 'Persediaan Tabung Kosong 12 kg';
    }
} else {
    $referensi_debit = 'Piutang TF';
    if ($nama_akun == 'Penjualan Refill' || $nama_akun == 'Penjualan Tabung Isi') {

        $referensi_kredit_55 = 'Persediaan Tabung isi 5,5 kg';
        $referensi_kredit_12 = 'Persediaan Tabung Isi 12 kg';
    } else {

        $referensi_kredit_55 = 'Persediaan Tabung Kosong 5,5 kg';
        $referensi_kredit_12 = 'Persediaan Tabung Kosong 12 kg';
    }
}


$nama_transaksi = 'Penjualan Non PSO';


//data transaksi
$sql_penjualan = mysqli_query($koneksi, "SELECT * FROM penjualan_non_pso WHERE no_penjualan = '$no_penjualan'");
$data_penjualan = mysqli_fetch_array($sql_penjualan);
$qty_awal_55kg = $data_penjualan['qty_55kg'];
$qty_awal_12kg = $data_penjualan['qty_12kg'];


if ($qty_awal_55kg != "0") {
    if ($qty_55kg != "0") {
        mysqli_query($koneksi, "UPDATE buku_besar SET tanggal = '$tanggal' , referensi_debit = '$referensi_debit' , referensi_kredit = '$referensi_kredit_55' , jumlah = '$jumlah_55kg' 
                            WHERE nama_transaksi = '$nama_transaksi' && no_transaksi =  '$no_penjualan' && referensi_kredit = 'Persediaan Tabung isi 5,5 kg' ||  
                                  nama_transaksi = '$nama_transaksi' && no_transaksi =  '$no_penjualan' && referensi_kredit = 'Persediaan Tabung Kosong 5,5 kg'");
    } else if ($qty_55kg == "0") {

        mysqli_query($koneksi, "DELETE FROM buku_besar WHERE nama_transaksi = '$nama_transaksi' && no_transaksi = '$no_penjualan' && referensi_kredit = 'Persediaan Tabung isi 5,5 kg'||  
                                                             nama_transaksi = '$nama_transaksi' && no_transaksi =  '$no_penjualan' && referensi_kredit = 'Persediaan Tabung Kosong 5,5 kg'");
    }
} else if ($qty_awal_55kg == "0") {
    if ($qty_55kg != "0") {
        mysqli_query($koneksi, "INSERT INTO buku_besar VALUES('','$tanggal','$nama_transaksi','$no_penjualan','$referensi_debit','$referensi_kredit_55','$jumlah_55kg')");
    } else  if ($qty_55kg == "0") {
    }
}

if ($qty_awal_12kg != "0") {
    if ($qty_12kg != "0") {
        mysqli_query($koneksi, "UPDATE buku_besar SET tanggal = '$tanggal' , referensi_debit = '$referensi_debit' , referensi_kredit = '$referensi_kredit_12' , jumlah = '$jumlah_12kg' 
                            WHERE nama_transaksi = '$nama_transaksi' && no_transaksi =  '$no_penjualan' && referensi_kredit = 'Persediaan Tabung isi 12 kg' ||  
                                  nama_transaksi = '$nama_transaksi' && no_transaksi =  '$no_penjualan' && referensi_kredit = 'Persediaan Tabung Kosong 12 kg'");
    } else if ($qty_12kg == "0") {
        mysqli_query($koneksi, "DELETE FROM buku_besar WHERE nama_transaksi = '$nama_transaksi' && no_transaksi = '$no_penjualan' && referensi_kredit = 'Persediaan Tabung isi 12 kg'||  
                                                             nama_transaksi = '$nama_transaksi' && no_transaksi =  '$no_penjualan' && referensi_kredit = 'Persediaan Tabung Kosong 12 kg'");
    }
} else if ($qty_awal_12kg == "0") {
    if ($qty_12kg != "0") {
        mysqli_query($koneksi, "INSERT INTO buku_besar VALUES('','$tanggal','$nama_transaksi','$no_penjualan','$referensi_debit','$referensi_kredit_12','$jumlah_12kg')");
    } else if ($qty_12kg == "0") {
    }
}



if ($file == '') {
    mysqli_query($koneksi, "UPDATE penjualan_non_pso SET tanggal = '$tanggal' , no_registrasi = '$no_registrasi' , nama_akun = '$nama_akun' , pembayaran = '$pembayaran' , qty_55kg = '$qty_55kg' , harga_55kg = '$harga_55kg',
                                    qty_12kg = '$qty_12kg' , harga_12kg = '$harga_12kg', jumlah = '$jumlah', status_penjualan = '$status_penjualan', keterangan = '$keterangan' WHERE no_penjualan =  '$no_penjualan'");
} else {

    mysqli_query($koneksi, "UPDATE penjualan_non_pso SET tanggal = '$tanggal' , no_registrasi = '$no_registrasi' , nama_akun = '$nama_akun' , pembayaran = '$pembayaran' , qty_55kg = '$qty_55kg' , harga_55kg = '$harga_55kg',
                                    qty_12kg = '$qty_12kg' , harga_12kg = '$harga_12kg',jumlah = '$jumlah', status_penjualan = '$status_penjualan', keterangan = '$keterangan', file_bukti = '$file'  WHERE no_penjualan =  '$no_penjualan'");
}


echo "<script>alert('Data Penjualan Berhasil di Edit'); window.location='../view/VPenjualanNonPSO?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir';</script>";
exit;
