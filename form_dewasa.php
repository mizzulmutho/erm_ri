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


$ql1="SELECT  id,form,isi from ERM_FORM where id_assesmen='$id' order by id desc";
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

<div class="container">

	<body onload="document.myForm.pasien_mcu.focus();">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;
			<a href='' class='btn btn-success'>Refresh</a>
			&nbsp;&nbsp;
			<a href='m_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-primary'>Master</a>
			<br>
			<br>
			<div class="card">
				<?php if(empty($idrasuhan)){ ?>
					<br />
					Input Data Asuhan Keperawatan
					<br>
					<select name="asuan_diagnosa" style="width:500px;height:40px">
						<option value=''>--Pilih Diagnosa Keperawatan --</option>

						<?php
						$q="SELECT DISTINCT diagnosa_keperawatan,diagnosa_nama
						FROM         ERM_MASTER_ASUHANKEPERAWATAN
						WHERE     JENIS='RENCANA ASUHAN KEPERAWATAN'";
						$hasil  = sqlsrv_query($conn, $q);			  
						while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  
							if ($data[diagnosa_keperawatan]==$diagnosa_keperawatan){
								echo "<option value='$data[diagnosa_keperawatan]' selected >$data[diagnosa_keperawatan] $data[diagnosa_nama] </option>\n";
							}else{
								echo "<option value='$data[diagnosa_keperawatan]'>$data[diagnosa_keperawatan] $data[diagnosa_nama]</option>\n";
							};
						}
						?>
					</select>

					<br>

					<button type="submit" name="simpan_rencana" class="btn btn-warning" onfocus="nextfield ='done';">simpan diagnosa keperawatan</button> 
				<?php }else{ ?>
					<button type="submit" name="implementasi_rencana" class="btn btn-success" onfocus="nextfield ='done';">implementasi rencana asuhan keperawatan</button> 
				<?php } ?>


			</div>
			<br>
			<div class="card">
				<div class="card-header bg-primary text-white">
					Rencana Asuan Keperawatan
				</div>
			</div>

			<div class="card">
				<table class="table">
					<input class="form-control" name="idrasuhan" value="<?php echo $idrasuhan;?>" id="idrasuhan" type="text" size='50' onfocus="nextfield ='';" placeholder="idrasuhan">
					<br>
					<textarea class="ckeditor" id="ckedtor" name="rencana" cols="100%">
						<?php echo $rencana;?>
					</textarea>

					<br>
					<button type="submit" name="edit_rencana" class="btn btn-warning" onfocus="nextfield ='done';">simpan deskripsi</button> 

				</table>
			</div>

		</form>
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

