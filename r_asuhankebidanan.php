<?php 
include ("koneksi.php");
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

</head> 

<div class="container">

	<body onload="document.myForm.pasien_mcu.focus();">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;
			<br>
			<h3>Rencana Asuhan Kebidanan</h3>
			<font size='2px'>
				<table class="table" border="1">
					<?php
					$ql="SELECT *,CONVERT(VARCHAR, tgl_teratasi, 101) as tglteratasi  FROM ERM_ASUHAN_KEPERAWATAN WHERE noreg='$noreg' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$d1  = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC); 
					$rencana = $d1['rencana'];
					$diagnosa_keperawatan = $d1['diagnosa_keperawatan'];
					$rencana = html_entity_decode($rencana);

					$q2="SELECT diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN WHERE diagnosa_keperawatan like '%$diagnosa_keperawatan%' ORDER BY id desc";
					$h2  = sqlsrv_query($conn, $q2);
					$d2  = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
					$diagnosa_nama = $d2['diagnosa_nama'];

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

				<h5>Implementasi Asuhan Kebidanan</h5>
				<table class="table" border="1">
					<?php
					$ql="SELECT *,CONVERT(VARCHAR, tgl, 101) as tgl2  FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$no=1;
					echo 
					"<tr bgcolor='#969392'>
					<td>no</td><td>sift</td><td>userid</td><td>tgl2</td><td>implementasi</td>
					</tr>";
					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){         
						$implementasi = $dl[implementasi];
						$implementasi = html_entity_decode($implementasi);

						echo "	<tr>
						<td>$no</td>
						<td>$dl[sift]</td>
						<td>$dl[userid]</td>
						<td>$dl[tgl2]</td>
						<td>$implementasi</td>
						</tr>
						";
						$no += 1;
					}
					?>
				</table>
			</font>
		</form>
	</body>
</div>