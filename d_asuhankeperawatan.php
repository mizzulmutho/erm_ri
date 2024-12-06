<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$noreg = $row[2]; 

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
		<font size='2px>'
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='implementasi.php?id=<?php echo $id.'|'.$user.'|'.$noreg;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<br>
				<table class="table" border="1">

					<?php
					$ql="SELECT *,CONVERT(VARCHAR, tgl, 20) as tgl2  FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$no=1;
					echo 
					"<tr bgcolor='#969392'>
					<td>no</td><td>sift</td><td>userid</td><td>tgl2</td><td>diagnosa</td><td>implementasi</td><td>edit</td>
					</tr>";
					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){         
						$implementasi = $dl[implementasi];
						$implementasi = html_entity_decode($implementasi);

						$ql11="
						SELECT        TOP (200) jenis, diagnosa_keperawatan, diagnosa_nama, isian, id, implementasi, isiantextarea, kode_diagnosa
						FROM            ERM_MASTER_ASUHANKEPERAWATAN
						where diagnosa_keperawatan='$dl[diagnosa_keperawatan]'
						";
						$hl11  = sqlsrv_query($conn, $ql11);
						$d111  = sqlsrv_fetch_array($hl11, SQLSRV_FETCH_ASSOC); 
						$diagnosa_nama = $d111['diagnosa_nama'];

						echo "	<tr>
						<td>$no</td>
						<td>$dl[sift]</td>
						<td>$dl[userid]</td>
						<td>$dl[tgl2]</td>
						<td>$dl[diagnosa_keperawatan]<br>$diagnosa_nama</td>
						<td>$implementasi</td>
						<td><a href='e_asuhankeperawatan.php?id=$id|$user|$dl[id]'>Edit</a></td>
						</tr>
						";
						$no += 1;
					}

					?>
				</table>
			</form>
		</font>
	</body>
</div>

<?php

if (isset($_POST["edit_rencana"])) {
	$idrasuhan = trim($_POST["idrasuhan"]);
	$rencana = trim($_POST["rencana"]);

	if(!empty($rencana)){
		$q  = "update ERM_ASUHAN_KEPERAWATAN set rencana='$rencana' where id=$idrasuhan";         
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			$eror = "Success";
		}else{
			$eror = "Gagal Insert";

		}

		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";

		// echo "
		// <script>
		// top.location='index.php?id='$id|$user';
		// </script>
		// ";            

	}

}
