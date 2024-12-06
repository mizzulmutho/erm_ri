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
	top.location='i_asuhankebidanan.php?id=$id|$user|$idrasuhan';
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
				<a href='form_asuhankebidanan1.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<a href='m_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class=''><i>Master</i></a>
				<br>
				<br>
				<div class="card">
					<br />
					Input Data Asuhan Kebidanan
					<br>
					<select name="asuan_diagnosa" style="width:500px;height:40px">
						<option value=''>--Pilih Diagnosa Kebidanan --</option>

						<?php
						$q="SELECT DISTINCT diagnosa_keperawatan,diagnosa_nama
						FROM         ERM_MASTER_ASUHANKEPERAWATAN
						WHERE     JENIS='RENCANA ASUHAN KEBIDANAN'";
						$hasil  = sqlsrv_query($conn, $q);			  
						while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  
							if ($data[diagnosa_keperawatan]==$diagnosa_keperawatan){
								echo "<option value='$data[diagnosa_keperawatan]|$data[diagnosa_nama]' selected >$data[diagnosa_nama] </option>\n";
							}else{
								echo "<option value='$data[diagnosa_keperawatan]|$data[diagnosa_nama]'>$data[diagnosa_nama]</option>\n";
							};
						}
						?>
					</select>

					<br>

				</div>
				<div class="row">
					<div class="col-6">
						<button type="submit" name="simpan_rencana" class="btn btn-success" onfocus="nextfield ='done';">simpan</button> 
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
	if(empty($asuan_diagnosa)){
		$lanjut='T';
		$eror="Diagnosa Belum diisi!!!";
	}

	if($lanjut=='Y'){

		$row = explode('|',$asuan_diagnosa);
		$diagnosa_kode  = $row[0];
		$diagnosa_nama = $row[1]; 


		$qu="SELECT isian as rencana FROM ERM_MASTER_ASUHANKEPERAWATAN where diagnosa_keperawatan like '%$diagnosa_kode%'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$rencana = trim($d1u['rencana']);
		$data = stripslashes($rencana);
		$data = htmlspecialchars($data);


	//jika tidak ada insert

		$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, diagnosa_nama) values 
		('$id','$noreg','$diagnosa_kode','$tgl','$user','$data','$diagnosa_nama')";         
		$hs = sqlsrv_query($conn,$q);


		if($hs){
			$eror = "Success";
			// header('Location: form_asuhankebidanan1.php?id=$id|$user|$idrasuhan');
			echo "Simpan Berhasil, Close";
		}else{
			$eror = "Gagal Insert";

		}
	}

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";

	// echo "
	// <script>
	// top.location='form_asuha.php?id=$id|$user|$idrasuhan';
	// </script>
	// ";

	// header('Location: form_asuhankebidanan1.php?id=$id|$user|$idrasuhan');

}
?>

