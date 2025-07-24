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

$sql_labor = "SELECT TOP(100) REG_DATE AS tanggal, 
TARIF_NAME, 
KEL_PEMERIKSAAN, 
PARAMETER_NAME, 
HASIL, 
FLAG
FROM LINKYAN5.SHARELIS.dbo.hasilLIS 
WHERE NOLAB_RS LIKE '%$noreg_igd%'
ORDER BY tanggal DESC";

$stmt_labor = sqlsrv_query($conn, $sql_labor, [$no_rm]);

$pivot = [];
$tanggal_list = [];

while ($row = sqlsrv_fetch_array($stmt_labor, SQLSRV_FETCH_ASSOC)) {
	$tgl = $row['tanggal']->format('Y-m-d H:i:s');
	$tarif = trim($row['TARIF_NAME']);
	$kelompok = trim($row['KEL_PEMERIKSAAN']);
	$kelompok = strtoupper(trim($row['KEL_PEMERIKSAAN']));

	if (!$kelompok) {
		error_log("Kelompok kosong pada parameter: $parameter_raw, tarif: $tarif");
	}

    // Membersihkan nama parameter dari awalan "- " atau spasi
	$parameter_raw = $row['PARAMETER_NAME'];
    $parameter = trim(ltrim($parameter_raw, "- ")); // penting!

    $hasil = $row['HASIL'];
    $flag = $row['FLAG'];

    // Mapping ke HITUNG JENIS (DIFF)
    $diffParams = ['Basofil', 'Neutrofil', 'Monosit', 'Eosinofil', 'Limfosit'];
    if (in_array(ucfirst(strtolower($parameter)), $diffParams)) {
    	$kelompok = "HITUNG JENIS (DIFF)";
    }

	$parameter = preg_replace('/\s+/', '', $parameter_raw); // hapus semua spasi dalam nama
	$indexEritrositParams = ['MCV', 'MCH', 'MCHC', 'RDW', 'MPV', 'PDW', 'P-LCC', 'P-LCR', 'PCT'];
	if (in_array(ucfirst(strtoupper($parameter)), $indexEritrositParams)) {
		$kelompok = "INDEX ERITROSIT";
	}

	$pivot[$tarif][$kelompok][$parameter][$tgl] = ['hasil' => $hasil, 'flag' => $flag];

	if (!in_array($tgl, $tanggal_list)) {
		$tanggal_list[] = $tgl;
	}
}


usort($tanggal_list, function($a, $b) {
	return strtotime($a) - strtotime($b); // ASCENDING (terbaru di kanan)
});


$html .= "<h3>Riwayat Laboratorium</h3>";
$html .= "<div style='margin-bottom: 10px;'>
<strong>Keterangan:</strong>
<span style='color: red; font-weight: bold;'> 🔺 Nilai Tinggi (H)</span> |
<span style='color: orange; font-weight: bold;'> 🔻 Nilai Rendah (L)</span>
</div>";

if (count($pivot) > 0) {
	
	$grup_faal = [];

	foreach ($pivot as $tarif => $kelompok_data) {
		foreach ($kelompok_data as $kelompok => $parameter_data) {
			$grup_faal[$kelompok][$tarif] = $parameter_data;
		}
	}

$urutan_kelompok = ['DARAH LENGKAP', 'GULA DARAH','HITUNG JENIS (DIFF)','INDEX ERITROSIT','FAAL HATI', 'FAAL GINJAL']; // tambahkan jika ada lagi

foreach ($urutan_kelompok as $kelompok) {
	if (!isset($grup_faal[$kelompok])) continue;

	$html .= "<h4 style='margin-top:30px; color:#333;'><strong>$kelompok</strong></h4>";

	foreach ($grup_faal[$kelompok] as $tarif => $parameter_data) {
		$html .= "<h6 style='margin-left:10px;'>🧪 <u>$tarif</u></h6>";
		$html .= "<table class='table table-bordered table-sm' style='margin-left:15px;'>";
		$html .= "<thead><tr><th>Parameter</th>";

		foreach ($tanggal_list as $tgl) {
			$html .= "<th>" . formatTanggalIndonesia($tgl) . "</th>";
		}

		$html .= "</tr></thead><tbody>";

		foreach ($parameter_data as $parameter => $per_tgl) {
			$html .= "<tr><td>$parameter</td>";
			foreach ($tanggal_list as $tgl) {
				if (isset($per_tgl[$tgl])) {
					$hasil = $per_tgl[$tgl]['hasil'];
					$flag = $per_tgl[$tgl]['flag'];

					$style = '';
					if ($flag === 'H') {
						$style = " style='color:red; font-weight:bold;'";
					} elseif ($flag === 'L') {
						$style = " style='color:orange; font-weight:bold;'";
					}

					$html .= "<td$style>$hasil</td>";
				} else {
					$html .= "<td>-</td>";
				}
			}
			$html .= "</tr>";
		}

		$html .= "</tbody></table>";
	}
}

} else {
	$html .= "<p><em>Tidak ada data laboratorium.</em></p>";
}

// Kelompok lain yang belum ditampilkan
$kelompok_lain = array_diff(array_keys($grup_faal), $urutan_kelompok);

if (count($kelompok_lain) > 0) {
	$html .= "<h4 style='margin-top:40px;'>🔍 <strong>Kelompok Lain</strong></h4>";
	foreach ($kelompok_lain as $kelompok) {
		$html .= "<h5 style='margin-top:20px; color:#444;'><strong>$kelompok</strong></h5>";

		foreach ($grup_faal[$kelompok] as $tarif => $parameter_data) {
			$html .= "<h6 style='margin-left:10px;'>🧪 <u>$tarif</u></h6>";
			$html .= "<table class='table table-bordered table-sm' style='margin-left:15px;'>";
			$html .= "<thead><tr><th>Parameter</th>";

			foreach ($tanggal_list as $tgl) {
				$html .= "<th>" . formatTanggalIndonesia($tgl) . "</th>";
			}

			$html .= "</tr></thead><tbody>";

			foreach ($parameter_data as $parameter => $per_tgl) {
				$html .= "<tr><td>$parameter</td>";
				foreach ($tanggal_list as $tgl) {
					if (isset($per_tgl[$tgl])) {
						$hasil = $per_tgl[$tgl]['hasil'];
						$flag = $per_tgl[$tgl]['flag'];

						$style = '';
						if ($flag === 'H') {
							$style = " style='color:red; font-weight:bold;'";
						} elseif ($flag === 'L') {
							$style = " style='color:orange; font-weight:bold;'";
						}

						$html .= "<td$style>$hasil</td>";
					} else {
						$html .= "<td>-</td>";
					}
				}
				$html .= "</tr>";
			}

			$html .= "</tbody></table>";
		}
	}
}

echo $html;

?>