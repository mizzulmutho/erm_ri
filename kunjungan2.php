<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$date = new DateTime('@'.strtotime('2016-03-22 14:30'), new DateTimeZone('Australia/Sydney'));

// $textcari = 'R202402121064';

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);
$tahun    = gmdate("Y", time()+60*60*7);
$milliseconds = round(microtime(true) * 1000);
$waktu = $milliseconds;
$tgl1 = gmdate("m/", time()+60*60*7).'01'.gmdate("/Y", time()+60*60*7);
$tgl2 = gmdate("m/d/Y", time()+60*60*7);

$bulan = gmdate("m", time()+60*60*7);
$tahun = gmdate("Y", time()+60*60*7);

$id = $_GET["id"];
$id='mamik|RSPG|Rawat Inap Lantai 1';

$row = explode('|',$id);

$user = trim($row[0]); 
$sbu = trim($row[1]); 
$unit = trim($row[2]); 

if ($sbu == 'RSPG'){
  $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
  $alamat = "Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik";
};
if ($sbu == 'GRAHU'){
  $nmrs = "RUMAH SAKIT GRHA HUSADA";
  $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
};
if ($sbu == 'DRIYO'){
  $nmrs = "RUMAH SAKIT DRIYOREJO";
  $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
};


$q="
SELECT        C.KODEDOKTER, D.NAMA, YEAR(A.TGLMASUK) AS TAHUN, MONTH(A.TGLMASUK) AS BULAN, COUNT(*) AS JUMLAH
FROM            ARM_PERIKSA AS A INNER JOIN
Afarm_Unitlayanan AS B ON A.KODEUNIT = B.KODEUNIT INNER JOIN
(SELECT DISTINCT NOREG, UNITLAYANAN, KODEDOKTER
 FROM            ARM_PERIKSA_DETIL
 WHERE        (CONVERT(datetime, CONVERT(varchar, TANGGAL, 101), 101) BETWEEN '$tgl1' AND '$tgl2') AND (JUMLAH <> 0) AND (SUBSTRING(UNITLAYANAN, 1, 1) IN ('R'))) AS C ON A.NOREG = C.NOREG AND 
A.KODEUNIT = C.UNITLAYANAN INNER JOIN
Afarm_DOKTER AS D ON C.KODEDOKTER = D.KODEDOKTER
WHERE        (CONVERT(datetime, CONVERT(varchar, A.TGLMASUK, 101), 101) BETWEEN '$tgl1' AND '$tgl2') AND (B.JENIS2 LIKE 'RI%') AND (B.KET1 = 'RSPG') AND (D.KETERANGAN LIKE '%SPESIALIS%')
GROUP BY YEAR(A.TGLMASUK), MONTH(A.TGLMASUK), C.KODEDOKTER, D.NAMA
ORDER BY jumlah DESC
";
$hq  = sqlsrv_query($conn, $q); 
$no=1;

echo "<table class='table'>
<tr>
<td>no</td>
<td>dokter</td>
<td>tahun</td>
<td>bulan</td>
<td>jumlah</td>
<td>input erm</td>
</tr>";
while   ($d0 = sqlsrv_fetch_array($hq,SQLSRV_FETCH_ASSOC)){ 

  $kodedokter = trim($d0[KODEDOKTER]);

    //select di assesment_medis
  $qe="
  SELECT count(noreg) as ierm
  FROM ERM_RI_ANAMNESIS_MEDIS
  where month(tgl)='$bulan' and year(tgl)='$tahun' and userid like '%$kodedokter%' and am1 is not null";
  $he  = sqlsrv_query($conn, $qe);        
  $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 

  $ierm = $de['ierm'];

  echo "
  <tr>
  <td>$no</td>
  <td>$d0[NAMA]</td>
  <td>$d0[TAHUN]</td>
  <td>$d0[BULAN]</td>
  <td>$d0[JUMLAH]</td>
  <td>$ierm</td>
  </tr>
  ";

  $no += 1;
}

echo "</table>";
?>