<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idasuhan = $row[2];

$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];


$ql1="SELECT  id,rencana,userid from ERM_ASUHAN_KEPERAWATAN where id='$idasuhan' order by id desc";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$rencana = $d11['rencana'];
$rencana = html_entity_decode($rencana);

$userid = $d11['userid'];
$idrasuhan = $d11['id'];


// if (isset($_POST["implementasi_rencana"])) {
// 	$idrasuhan = trim($_POST["idrasuhan"]);

// 	echo "
// 	<script>
// 	top.location='i_asuhankeperawatan.php?id=$id|$user|$idrasuhan';
// 	</script>
// 	";            
// }


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<script language="JavaScript" type="text/javascript">
		nextfield = "box1";
		netscape = "";
		ver = navigator.appVersion; len = ver.length;
		for(iln = 0; iln < len; iln++) if (ver.charAt(iln) == "(") break;
			netscape = (ver.charAt(iln+1).toUpperCase() != "C");

		function keyDown(DnEvents) {
			k = (netscape) ? DnEvents.which : window.event.keyCode;
			if (k == 13) {
				if (nextfield == 'done') return true;
				else {
					eval('document.myForm.' + nextfield + '.focus()');
					return false;
				}
			}
		}
		document.onkeydown = keyDown;
		if (netscape) document.captureEvents(Event.KEYDOWN|Event.KEYUP);
	</script>

	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>

	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

</head> 

<div class="container-fluid">

	<body onload="document.myForm.pasien_mcu.focus();">
		<font size='2px'>	

			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<a href='m_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class=''><i>Master</i></a>
				<br>
				<br>
				<div class="row">
					<div class="col-sm-6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<b><font size="4px">Data Rencana Asuan Keperawatan</font></b>
					</div>
					<div class="col-sm-6">
						
<!-- 						<button type="submit" name="implementasi_rencana" class="btn btn-success btn-smal" onfocus="nextfield ='done';">
							<i class='bi bi-file-earmark-text'></i> Implementasi Keperawatan
						</button> 	
					-->
					<a href='i_asuhankeperawatan.php?id=<?php echo $id.'|'.$user.'|'.$idrasuhan;?>' class='btn btn-success'><i class='bi bi-file-earmark-text'></i>Implementasi</a>


				</div>
			</div>
			<br>
			<div class="card">
				<table class="table">
					<input class="form-control" name="idrasuhan" value="<?php echo $idrasuhan;?>" id="idrasuhan" type="text" size='50' onfocus="nextfield ='';" placeholder="idrasuhan">
					<br>
					<textarea class="ckeditor" id="ckedtor" name="rencana" cols="100%">
						<?php echo $rencana;?>
					</textarea>

				</table>
			</div>
			<br>
			<div class="row">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<div class="col-sm-3">
					<button type="submit" name="edit_rencana" class="btn btn-warning btn-smal" onfocus="nextfield ='done';">
						<i class='bi bi-file-earmark-text'></i> edit deskripsi rencana asuhan
					</button> 
				</div>
			</div>
			<br><br>
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

if (isset($_POST["simpan_rencana"])) {

	$lanjut = 'Y';
	$asuan_diagnosa = trim($_POST["asuan_diagnosa"]);

	$qu="SELECT isian as rencana FROM ERM_MASTER_ASUHANKEPERAWATAN where diagnosa_keperawatan like '%$asuan_diagnosa%'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$rencana = trim($d1u['rencana']);
	$data = stripslashes($rencana);
	$data = htmlspecialchars($data);

	//jika tidak ada insert
	$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana) values 
	('$id','$noreg','$asuan_diagnosa','$tgl','$user','$data')";         
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

}
?>

