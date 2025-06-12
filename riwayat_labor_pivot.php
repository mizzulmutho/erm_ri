<?php
$serverName = "192.168.10.1";
$connectionOptions = [
    "Database" => "RSPGENTRY",
    "Uid" => "sa",
    "PWD" => "p@ssw0rd"
];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) die(print_r(sqlsrv_errors(), true));

$no_rm = trim($_POST['no_rm'] ?? '');
if (!$no_rm) {
    echo "Nomor RM tidak ditemukan."; exit;
}

$sql = "SELECT REG_DATE as tanggal, KEL_PEMERIKSAAN+' - '+PARAMETER_NAME as jenis_pemeriksaan, HASIL as hasil FROM LINKYAN5.SHARELIS.dbo.hasilLIS WHERE NORM = '$no_rm' ORDER BY tanggal DESC";
$stmt = sqlsrv_query($conn, $sql, [$no_rm]);

// Susun array untuk pivot
$pivot = [];
$tanggal_list = [];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $tgl = $row['tanggal']->format('Y-m-d');
    $jenis = $row['JENIS_PEMERIKSAAN'];
    $hasil = $row['HASIL'];

    $pivot[$jenis][$tgl] = $hasil;

    if (!in_array($tgl, $tanggal_list)) {
        $tanggal_list[] = $tgl;
    }
}

sort($tanggal_list); // Urutkan tanggal

// Tampilkan tabel
echo "<h3>Riwayat Laboratorium (Pivot)</h3>";
echo "<table class='table table-bordered table-striped'>";
echo "<thead><tr><th>Jenis Pemeriksaan</th>";

foreach ($tanggal_list as $tgl) {
    echo "<th>$tgl</th>";
}
echo "</tr></thead><tbody>";

foreach ($pivot as $jenis => $data_per_tgl) {
    echo "<tr><td>$jenis</td>";
    foreach ($tanggal_list as $tgl) {
        $isi = $data_per_tgl[$tgl] ?? '-';
        echo "<td>$isi</td>";
    }
    echo "</tr>";
}

echo "</tbody></table>";
?>