<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tanggal    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$getid = $_GET["id"];
$row = explode('|',$getid);
$id = $row[0]; 
$user = $row[1]; 
$nosep = $row[2]; 
$noreg = $row[3]; 
$file_to_display = $row[4]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

if ($sbu == "RSPG") {
  $consID = "30161"; // PROD
  $consSecret = "4uP1D898FE";
  $user_key = "1b2256e07eb21a343f934eb522bb6a59";
  $user_key_antrol= "8a4acfe012329f428ced3f2cc57dd419";
  $ppkPelayanan = "1302R002";
  $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
  $alamat = "
  Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik
  <br>
  IGD : 031-99100118 Telp : 031-3978658<br>
  Email : sbu.rspg@gmail.com
  ";
  $logo = "logo/rspg.png";
} else if ($sbu === "GRAHU") {
  $consID = "9497"; //PROD
  $consSecret = "3aV1C3CB13";
  $user_key = "cb3d247a6b9443d68f9567e0d86fb422";
  $user_key_antrol= "77ce0cdd4d786c2e0029a45f9e97759d";
  $ppkPelayanan = "0205R013";
  $nmrs = "RUMAH SAKIT GRHA HUSADA";
  $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
  $logo = "logo/grahu.png";
} else if ($sbu === "DRIYO") {
  $consID = "3279"; //PROD
  $consSecret = "6uR2F891A4";
  $user_key = "918bda20e3056ae0d4167e698d8adb83";
  $user_key_antrol= "f9b587583c0232c2bd36d27aad8f9856";
  $ppkPelayanan = "0205R014";
  $nmrs = "RUMAH SAKIT DRIYOREJO";
  $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
  $logo = "logo/driyo.png";
} else {
	$consID = "";
	$consSecret = "";
	$user_key = "";
	$ppkPelayanan = "";
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Print Preview with Page Break</title>
	<style>
		@media print {
			.page-break {
				page-break-before: always;
			}
		}
	</style>
</head>
<body>


	<?php

	ob_start();
	echo "<div class='page-break'></div>";
	include 'resume_print.php';
	echo "<div class='page-break'></div>";
	include 'c_sep.php';
	// echo "<div class='page-break'></div>";
	// include 'c_ina.php';
	echo "<div class='page-break'></div>";
	include 'c_billing.php';
	echo "<div class='page-break'></div>";
	include 'c_lab.php';
	// echo "<div class='page-break'></div>";
	// include 'c_rad.php';
	$htmlPreview = ob_get_clean();

	echo $htmlPreview; // tampilkan di browser


	?>


</body>
</html>


