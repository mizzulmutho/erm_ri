<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idrasuhan = $row[2]; 

$ql1="SELECT  noreg,diagnosa_keperawatan from ERM_ASUHAN_KEPERAWATAN where id='$idrasuhan'";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$rencana = $d11['rencana'];
$rencana = html_entity_decode($rencana);
$diagnosa_keperawatan = $d11['diagnosa_keperawatan'];
$noreg = $d11['noreg'];

$ql2="SELECT  implementasi from ERM_MASTER_ASUHANKEPERAWATAN where diagnosa_keperawatan like '%$diagnosa_keperawatan%'";
$hl2  = sqlsrv_query($conn, $ql2);
$d12  = sqlsrv_fetch_array($hl2, SQLSRV_FETCH_ASSOC); 
$implementasi = $d12['implementasi'];
$implementasi = html_entity_decode($implementasi);


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
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='form_asuhankeperawatan2.php?id=<?php echo $id.'|'.$user.'|'.$idrasuhan;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;
			<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
			&nbsp;&nbsp;
			<a href='d_asuhankeperawatan.php?id=<?php echo $id.'|'.$user.'|'.$idrasuhan;?>' class='btn btn-warning'>
				<i class="bi bi-list-task"></i> Detail Implementasi
			</a>
			&nbsp;&nbsp;
			<br>
			<br>
			<div class="card">
				<b>Input Implementasi Keperawatan</b>
			</div>

			<div class="card">

				<input class="form-control" name="noreg" value="<?php echo $noreg;?>" id="noreg" type="text" size='50' onfocus="nextfield ='';" placeholder="noreg" readonly>

				<select name="sift" style="width:500px;height:30px" required>
					<option value=''>--Pilih Sift --</option>
					<option value='DINAS PAGI'>DINAS PAGI</option>
					<option value='DINAS SIANG'>DINAS SIANG</option>
					<option value='DINAS MALAM'>DINAS MALAM</option>
				</select>
				<br>
				<textarea class="ckeditor" id="ckedtor" name="implementasi" cols="100%">
					<?php echo $implementasi;?>
				</textarea>
			</div>

			<br>
			<div class="row">
				<div class="col-sm-3">
					&nbsp;&nbsp;&nbsp;<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';">simpan implementasi</button> 
				</div>
			</div>

			<br>
			<br>

		</form>
	</body>
</div>

<?php

if (isset($_POST["simpan"])) {
	$implementasi = trim($_POST["implementasi"]);
	$sift = trim($_POST["sift"]);
	$noreg = trim($_POST["noreg"]);

	$data = stripslashes($implementasi);
	$data = htmlspecialchars($data);

	if(!empty($implementasi)){
		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan)
		values('$noreg','$sift','$data','$user','$tgl','$idrasuhan')";         
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


?>

