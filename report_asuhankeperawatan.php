<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];


$ql1="SELECT  id,rencana,userid from ERM_ASUHAN_KEPERAWATAN where id_assesmen='$id' order by id desc";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$rencana = $d11['rencana'];
$rencana = html_entity_decode($rencana);

$userid = $d11['userid'];
$idrasuhan = $d11['id'];


if (isset($_POST["implementasi_rencana"])) {
	$idrasuhan = trim($_POST["idrasuhan"]);

	echo "
	<script>
	top.location='i_asuhankeperawatan.php?id=$id|$user|$idrasuhan';
	</script>
	";            
}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head> 

<div class="container-fluid">

	<body onload="document.myForm.pasien_mcu.focus();">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">

			<br>
			<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i> </a>
			&nbsp;&nbsp;&nbsp;

			<div class="row">

				<div class="col-12">

					<?php 
					include "header_soap.php";
					?>

				</div>

			</div>

			<br>
			<div class="row">
				<div class="col-12 text-center">
					<font size='4'>
						<b><u>REPORT ASUHAN KEPERAWATAN</u></b><br>
					</font>
				</div>
			</div>

			<font size='2px'>
				<?php
				// $ql="SELECT DISTINCT *,CONVERT(VARCHAR, tgl_teratasi, 20) as tglteratasi  FROM ERM_ASUHAN_KEPERAWATAN WHERE noreg='$noreg' ORDER BY id desc";

				$ql="SELECT DISTINCT noreg,diagnosa_keperawatan from ERM_ASUHAN_KEPERAWATAN WHERE noreg='$noreg' ORDER BY diagnosa_keperawatan asc";

				$hl1  = sqlsrv_query($conn, $ql);
				while   ($dl1 = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC)){   
					$diagnosa_keperawatan = $dl1['diagnosa_keperawatan'];

					$q2="SELECT diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN WHERE diagnosa_keperawatan like '%$diagnosa_keperawatan%' ORDER BY id desc";
					$h2  = sqlsrv_query($conn, $q2);
					$d2  = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
					$diagnosa_nama = $d2['diagnosa_nama'];

					echo "diagnosa keperawatan : ".$diagnosa_nama;
					echo "<br>";

					echo "
					<table class='table'>
					<tr>
					<td>no</td>	
					<td>sift</td>
					<td>user input</td>
					<td>tgl / jam</td>									
					<td width='50%'>implementasi</td>
					</tr>
					";

					//implementasi..
					$q2="
					SELECT      id,sift,userid,implementasi,CONVERT(VARCHAR, tanggal, 103) AS tanggal, CONVERT(VARCHAR, jam, 24) AS jam
					FROM         ERM_IMPLEMENTASI_ASUHAN
					WHERE        (noreg = '$noreg') and diagnosa_keperawatan='$diagnosa_keperawatan'
					order by id desc
					";
					$hasil2  = sqlsrv_query($conn, $q2);
					$i=1;				  

					while 	($data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC)){				  

						if(trim($data2[sift])=='DINAS PAGI'){
							$warna = '';
						}
						if(trim($data2[sift])=='DINAS SIANG'){
							$warna = 'F5F7F8';
						}
						if(trim($data2[sift])=='DINAS MALAM'){
							$warna = 'F1F8E8';
						}

						echo "
						<tr>
						<td bgcolor='$warna'>$i</td>
						<td bgcolor='$warna'>$data2[sift]</td>
						<td bgcolor='$warna'>$data2[userid]</td>
						<td bgcolor='$warna'>$data2[tanggal] - $data2[jam]</td>
						<td bgcolor='$warna'>$data2[implementasi]</td>
						</tr>
						";

						$csift = $data2[sift];

						$i=$i+1;

					}

					echo "</table>";

				}

				?>
			</table>
			<br>

			<!-- <h5>Implementasi Asuhan Keperawatan</h5> -->
		</font>
	</form>
</body>
</div>