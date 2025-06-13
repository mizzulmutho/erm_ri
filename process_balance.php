<?php
// session_start();

include "koneksi.php"; // koneksi database
$id = $_POST['id'] ?? '';
$user = $_POST['user'] ?? '';
$noreg = $_POST['noreg'] ?? '';
$tanggal = $_POST['filter_tanggal'] ?? '';
$jam_awal = $_POST['filter_jam_awal'] ?? '';
$jam_akhir = $_POST['filter_jam_akhir'] ?? '';
$tanggal_akhir = $_POST['filter_tanggal_akhir'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';
$dataTerpilih = $_POST['data_terpilih'] ?? [];
// $keterangan = $_POST['keterangan'] ?? '';
$inList = implode(",", array_map("intval", $dataTerpilih));


if (empty($dataTerpilih)) {
	echo "Tidak ada data terpilih.";
	exit;
}

$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);


if (!$noreg || !$tanggal) {
	die("Noreg dan tanggal harus diisi.");
}

$filter = "noreg='$noreg'";

$start = "$tgl_awal " . (!empty($jam_awal) ? $jam_awal : "00:00");
$end = "$tgl_akhir " . (!empty($jam_akhir) ? $jam_akhir : "23:59");

$tgl_awal = $tanggal;
$tgl_akhir = $tanggal_akhir;

$filter .= " AND tglinput >= DATEADD(minute, DATEDIFF(minute, 0, '$tgl_awal $jam_awal'), 0)
AND tglinput <= DATEADD(second, 59, DATEADD(minute, DATEDIFF(minute, 0, '$tgl_akhir $jam_akhir'), 0))";

$filter .= " AND id IN ($inList)";

// Query data
$query = "SELECT total_input, total_output FROM ERM_RI_OBSERVASI_CAIRAN WHERE 1=1 and $filter";
$stmt = sqlsrv_query($conn, $query);
if ($stmt === false) {
	die(print_r(sqlsrv_errors(), true));
}


$total_input_sum = 0;
$total_output_sum = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$total_input_sum += floatval($row['total_input']);
	$total_output_sum += floatval($row['total_output']);
}

$balance = $total_input_sum - $total_output_sum;

// Buat nomor transaksi
$prefix = 'TRX';
$tanggalSekarang = date('dmy'); // Format: 210524
$nomorUrut = 1;

// Cek nomor urut terakhir hari ini
$cekNomorQuery = "
SELECT MAX(RIGHT(nomor_transaksi, 4)) AS max_nomor
FROM ERM_RI_OBSERVASI_CAIRAN
WHERE nomor_transaksi LIKE '$prefix$tanggalSekarang%'
";
$cekNomorStmt = sqlsrv_query($conn, $cekNomorQuery);
if ($cekNomorStmt !== false && $row = sqlsrv_fetch_array($cekNomorStmt, SQLSRV_FETCH_ASSOC)) {
	$nomorUrut = intval($row['max_nomor']) + 1;
}

$nomorTransaksi = $prefix . $tanggalSekarang . str_pad($nomorUrut, 4, '0', STR_PAD_LEFT);


$updateQuery = "UPDATE ERM_RI_OBSERVASI_CAIRAN SET ob27 = '', keterangan = ?, nomor_transaksi = ?  WHERE 1=1 and $filter";
$params = [$keterangan, $nomorTransaksi];
$stmt2 = sqlsrv_query($conn, $updateQuery, $params);


if ($stmt2 === false) {
	die(print_r(sqlsrv_errors(), true));
}


// Waktu awal dan akhir
$waktu_awal  = "$tgl_awal $jam_awal";
$waktu_akhir = "$tgl_akhir $jam_akhir";

// Insert log ke tabel log_balance_cairan
$insertLogQuery = "INSERT INTO ERM_RI_OBSERVASI_CAIRAN (
	noreg, tglinput, ob27,balance,tanggal_awal,tanggal_akhir,userinput,nomor_transaksi
) VALUES (?, ?, ?, ?, ?,?,?,?)";

$paramsLog = [
	$noreg,
	$waktu_akhir,
	$balance,
	$balance,
	$waktu_awal,
	$waktu_akhir,
	$user,
	$nomorTransaksi
];

$stmtLog = sqlsrv_query($conn, $insertLogQuery, $paramsLog);
if ($stmtLog === false) {
	die(print_r(sqlsrv_errors(), true));
}

ob_start();
session_start();

$_SESSION['success'] = "Perhitungan balance cairan berhasil disimpan.";

// header("Location: listobservasi_cairan.php?id=$id|$user&user=$user&filter_tanggal=$tanggal&filter_tanggal_akhir=$tanggal_akhir&filter_jam_awal=$jam_awal&filter_jam_akhir=$jam_akhir&msg=balance_success");
// exit;

$eror = "Perhitungan balance cairan berhasil disimpan.";

echo "
<script>
alert('".$eror."');
window.location.replace('listobservasi_cairan.php?id=$id|$user&user=$user&filter_tanggal=$tanggal&filter_tanggal_akhir=$tanggal_akhir&filter_jam_awal=$jam_awal&filter_jam_akhir=$jam_akhir&msg=balance_success');
</script>
";

?>