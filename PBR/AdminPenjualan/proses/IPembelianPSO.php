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
$tanggal_awal = $_GET['tanggal1'];
$tanggal_akhir = $_GET['tanggal2'];
$tanggal = htmlspecialchars($_POST['tanggal']);
$nama_akun = htmlspecialchars($_POST['nama_akun']);
$nama_tabung = htmlspecialchars($_POST['nama_tabung']);
$pembayaran = htmlspecialchars($_POST['pembayaran']);
$qty_pembelian = htmlspecialchars($_POST['qty_pembelian']);
$harga_pembelian = htmlspecialchars($_POST['harga_pembelian']);
$pph = htmlspecialchars($_POST['pph']);
$ppn = htmlspecialchars($_POST['ppn']);
$jumlah = ($qty_pembelian * $harga_pembelian) + ($pph + $ppn);
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

		$ekstensi_valid = ['jpg','jpeg','pdf','doc','docs','xls','xlsx','docx','txt','png'];
		$ekstensi_file = explode(".", $nama_file);
		$ekstensi_file = strtolower(end($ekstensi_file));


		$nama_file_baru = uniqid();
		$nama_file_baru .= ".";
		$nama_file_baru .= $ekstensi_file;

		move_uploaded_file($tmp_name, '../file_admin_pso/' . $nama_file_baru   );

		return $nama_file_baru; 
    }

    $file = upload();
    if (!$file) {
        return false;
    }
}

    if($nama_akun == 'Penjualan Refill' || $nama_akun == 'Penjualan Tabung Isi' ){
        $referensi_debit = 'Persediaan Tabung isi 3 kg';
        $referensi_kredit = $pembayaran;
    }
    else{
        $referensi_debit = 'Persediaan Tabung Kosong 3 kg';
        $referensi_kredit = $pembayaran;
    }
    

mysqli_query($koneksi, "INSERT INTO pembelian_pso VALUES('','$tanggal','$nama_akun','$nama_tabung','$pembayaran','$qty_pembelian','$harga_pembelian','$pph','$ppn','$jumlah','$keterangan','$file')");

//data transaksi
$sql_pembelian = mysqli_query($koneksi, "SELECT no_pembelian FROM pembelian_pso ORDER BY no_pembelian DESC LIMIT 1");
$data_pembelian= mysqli_fetch_array($sql_pembelian);
$no_pembelian = $data_pembelian['no_pembelian'];

$nama_transaksi = 'Pembelian PSO'; 
mysqli_query($koneksi, "INSERT INTO buku_besar VALUES('','$tanggal','$nama_transaksi','$no_pembelian','$referensi_debit','$referensi_kredit','$jumlah')");

echo "<script>alert('Data Pembelian Berhasil di Input'); window.location='../view/VPembelianPSO?tanggal1=$tanggal_awal&tanggal2=$tanggal_akhir';</script>";
exit;
