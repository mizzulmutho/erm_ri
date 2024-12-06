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
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;&nbsp;
			<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i> </a>

			<div class="row">
				<div class="col-12 text-center">
					<font size='5'>
						<b><u>REPORT ASUHAN KEBIDANAN</u></b><br>
					</font>
				</div>
			</div>

			<font size='3px'>
				<table class="table" border="1" width="100%">
					<?php
					$ql="SELECT *,CONVERT(VARCHAR, tgl_teratasi, 101) as tglteratasi  FROM ERM_ASUHAN_KEPERAWATAN WHERE noreg='$noreg' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$d1  = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC); 

					$rencana = $d1['rencana'];
					$diagnosa_keperawatan = $d1['diagnosa_keperawatan'];
					$diagnosa_nama = $d1['diagnosa_nama'];
					$rencana = html_entity_decode($rencana);

					$q  = "update ERM_ASUHAN_KEPERAWATAN set rencana='$rencana',diagnosa_nama='$diagnosa_nama' where noreg='$noreg'";         
					$hs = sqlsrv_query($conn,$q);

					$ql="SELECT *,CONVERT(VARCHAR, tgl_teratasi, 101) as tglteratasi  FROM ERM_ASUHAN_KEPERAWATAN WHERE noreg='$noreg' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$d1  = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC); 

					$rencana = $d1['rencana'];
					$diagnosa_keperawatan = $d1['diagnosa_keperawatan'];
					$diagnosa_nama = $d1['diagnosa_nama'];
					$rencana = html_entity_decode($rencana);


					echo "Diagnosa Keperawatan: ".$d1['diagnosa_keperawatan'];
					echo " - ";
					echo $diagnosa_nama;
					echo "<br>";
					echo "Tgl Teratasi: ".$d1['tglteratasi'];
					echo " - ";
					echo "User Entry: ".$d1['userid'];
					echo $rencana;
					echo "<br>";

					?>
				</table>
				<br>

			</font>
		</form>
	</body>
</div>