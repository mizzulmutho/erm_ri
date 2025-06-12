<?php
// sambungkan koneksi
include 'koneksi.php';
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_POST['id2'] ?? '';
$user = $_POST['user2'] ?? '';
$noreg = $_POST['noreg2'] ?? '';
$terapi_infus = $_POST['terapi'] ?? '';

// Validasi sederhana
if ($terapi_infus == '') {
	echo "Terapi cairan infus tidak boleh kosong.";
	exit;
}

// SQL Server pakai parameter dengan tanda ? dan sqlsrv_prepare + sqlsrv_execute
$sql = "INSERT INTO ERM_RI_OBSERVASI_CAIRAN (noreg, tglinput, terapi, userinput) VALUES (?, ?, ?, ?)";
$params = array($noreg, $tglinput, $terapi_infus, $user);

$stmt = sqlsrv_prepare($conn, $sql, $params);

if (!$stmt) {
	die(print_r(sqlsrv_errors(), true));
}

if (sqlsrv_execute($stmt)) {
	echo "<script>alert('Terapi cairan infus berhasil disimpan'); window.history.back();</script>";
} else {
	echo "Gagal menyimpan data: ";
	print_r(sqlsrv_errors());
}


?>
