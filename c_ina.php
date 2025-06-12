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


date_default_timezone_set('UTC');
$timestamp = time();

$data = $consID.'&'.$timestamp;
$key = $consID.$consSecret.$timestamp;

$signature = hash_hmac('sha256', $data, $consSecret, true);
$encodedSignature = base64_encode($signature);

$url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest"; //PROD
$url_antrol = "https://apijkn.bpjs-kesehatan.go.id/antreanrs";

$tglentry = gmdate("d-m-Y H:i:s", time()+60*60*7);

$datetime       = gmdate("Y-m-d H:i:s", time()+60*60*7);
$date       = gmdate("Y-m-d", time()+60*60*7);

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$tglinput=gmdate("Y-m-d", time()+60*60*7);

$bulan  =substr($tglsekarang,5,2);
$tanggal=substr($tglsekarang,8,3);
$tahun  =substr($tglsekarang,0,4);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>INA</title>
	<style>
		body{
			width : 98%;
			font-size : 10px;
			font-family: arial;
		}
		table{
			width: 100%
		}
		table, tr, td{
			border : none;
			border-collapse: collapse;
		}
		td{
			padding: 2px;
			/* height : 16px; */
			vertical-align: top
		}
		.bordered_table, .bordered_table tr, .bordered_table td{
			border : 1px solid black;
			border-collapse : collapse;
		}
		@media print {
			.header, .hide {
				visibility: hidden
			}
			.tanggal-cetak{
				visibility: hidden;
			}
			@page {
				size: A4;
				margin: 0.8cm;
				margin-top: 0.5cm;
			}
		}
	</style>
</head>
<body onload="">

	<?php
	// $file_to_display = "1302R0020425V013258klaim.pdf";
	$pdf_path = "pdf_klaim/" . $file_to_display;
	?>

	<embed src="<?php echo $pdf_path; ?>" type="application/pdf" width="100%" height="1200px" />
	</embed>


	

</body>
</html>