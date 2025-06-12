<?php
$serverName = "192.168.10.1"; // atau IP server SQL
$connectionOptions = [
	"Database" => "RSPGENTRY",
	"Uid" => "sa",
	"PWD" => "p@ssw0rd"
];

// Koneksi ke SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
	die(print_r(sqlsrv_errors(), true));
}

$no_rm = $_POST['no_rm'] ?? '';
if (!$no_rm) {
	echo "Nomor RM tidak ditemukan.";
	exit;
}

$qu="SELECT top(1)noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where norm='$no_rm' order by id desc";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];

$noreg_igd = substr($noreg, 1,12);

$html = "";

//// ----------------------------- ///
//// 🔹 1. RADIODIAGNOSIS SECTION ///
//// ----------------------------- ///

$sql_radiologi = "SELECT top(5)CONVERT(date, TANGGAL) as tanggal, HASIL as jenis_pemeriksaan, URAIAN as hasil 
FROM HASILRAD_PEMERIKSAAN_RAD
WHERE NOREG like '%$noreg_igd%' 
ORDER BY tanggal ASC";
$stmt_radiologi = sqlsrv_query($conn, $sql_radiologi, [$no_rm]);

$html .= "<h3>Riwayat Radiologi</h3>";
if ($stmt_radiologi && sqlsrv_has_rows($stmt_radiologi)) {
	$html .= "<table class='table table-bordered table-striped'><thead><tr>
	<th>Tanggal</th><th>Jenis Pemeriksaan</th><th>Hasil</th>
	</tr></thead><tbody>";
	while ($row = sqlsrv_fetch_array($stmt_radiologi, SQLSRV_FETCH_ASSOC)) {
		$tgl = $row['tanggal']->format('Y-m-d');
		$html .= "<tr>
		<td>{$tgl}</td>
		<td>{$row['jenis_pemeriksaan']}</td>
		<td>{$row['hasil']}</td>
		</tr>";
	}
	$html .= "</tbody></table>";
} else {
	$html .= "<p><em>Tidak ada data radiologi.</em></p>";
}

//// -------------------------- ///
//// 🔹 2. LABORATORIUM PIVOT ///
//// -------------------------- ///

function formatTanggalIndonesia($tanggal) {
	$hari = [
		'Sunday' => 'Minggu',
		'Monday' => 'Senin',
		'Tuesday' => 'Selasa',
		'Wednesday' => 'Rabu',
		'Thursday' => 'Kamis',
		'Friday' => 'Jumat',
		'Saturday' => 'Sabtu'
	];

	$bulan = [
		1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
		4 => 'April', 5 => 'Mei', 6 => 'Juni',
		7 => 'Juli', 8 => 'Agustus', 9 => 'September',
		10 => 'Oktober', 11 => 'November', 12 => 'Desember'
	];

	$dt = new DateTime($tanggal);
	$namaHari = $hari[$dt->format('l')];
	$tgl = $dt->format('j');
	$bln = $bulan[(int)$dt->format('n')];
	$thn = $dt->format('Y');
	$jam = $dt->format('H:i');

	return "$namaHari, $tgl $bln $thn $jam WIB";
}

$sql_labor = "SELECT top(100) REG_DATE AS tanggal, TARIF_NAME+'<br>'+KEL_PEMERIKSAAN+'-'+PARAMETER_NAME as jenis_pemeriksaan, HASIL as hasil,FLAG
FROM LINKYAN5.SHARELIS.dbo.hasilLIS WHERE NOLAB_RS like '%$noreg_igd%'
ORDER BY tanggal DESC";
$stmt_labor = sqlsrv_query($conn, $sql_labor, [$no_rm]);

$pivot = [];
$tanggal_list = [];

while ($row = sqlsrv_fetch_array($stmt_labor, SQLSRV_FETCH_ASSOC)) {
	$tgl = $row['tanggal']->format('Y-m-d H:i:s');
	$jenis = $row['jenis_pemeriksaan'];
	$hasil = $row['hasil'];
	$flag = $row['FLAG'];

//	$pivot[$jenis][$tgl] = $hasil;
// Simpan hasil dan flag
	$pivot[$jenis][$tgl] = ['hasil' => $hasil, 'flag' => $flag];


	if (!in_array($tgl, $tanggal_list)) {
		$tanggal_list[] = $tgl;
	}
}

// Urutkan tanggal secara DESC agar tanggal terbaru ada di kiri
usort($tanggal_list, function($a, $b) {
    return strtotime($b) - strtotime($a); // Urutkan dari yang terbaru
});


$html .= "<h3>Riwayat Laboratorium</h3>";
$html .= "
<div style='margin-bottom: 10px;'>
<strong>Keterangan:</strong>
<span style='color: red; font-weight: bold;'> 🔺 Nilai Tinggi (H)</span> |
<span style='color: orange; font-weight: bold;'> 🔻 Nilai Rendah (L)</span>
</div>
";

if (count($pivot) > 0) {
	$html .= "<table class='table table-bordered table-striped'>";
	$html .= "<thead><tr><th>Jenis Pemeriksaan</th>";
	foreach ($tanggal_list as $tgl) {
		// $html .= "<th>$tgl</th>";
		$tgl_indo = formatTanggalIndonesia($tgl);
		$html .= "<th>$tgl_indo</th>";
	}
	$html .= "</tr></thead><tbody>";

	foreach ($pivot as $jenis => $per_tgl) {
		$html .= "<tr><td>$jenis</td>";
		foreach ($tanggal_list as $tgl) {
			if (isset($per_tgl[$tgl])) {
				$hasil = $per_tgl[$tgl]['hasil'];
				$flag = $per_tgl[$tgl]['flag'];

				// Tentukan style berdasarkan FLAG
				$style = '';
				if ($flag === 'H') {
					$style = " style='color: red; font-weight: bold;'";
				} elseif ($flag === 'L') {
					$style = " style='color: orange; font-weight: bold;'";
				}

				$html .= "<td$style>$hasil</td>";
			} else {
				$html .= "<td>-</td>";
			}
		}
		$html .= "</tr>";
	}

	$html .= "</tbody></table>";
} else {
	$html .= "<p><em>Tidak ada data laboratorium.</em></p>";
}

echo $html;

?>