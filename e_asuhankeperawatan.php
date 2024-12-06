<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idimplementasi = $row[2]; 

$ql1="SELECT  noreg,sift,implementasi from ERM_IMPLEMENTASI_ASUHAN where id='$idimplementasi'";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$implementasi = $d11['implementasi'];
$implementasi = html_entity_decode($implementasi);
$sift = $d11['sift'];
$noreg = $d11['noreg'];


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
			<a href='askep.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;
			<br>
			<br>
			<div class="card">
				<b>Edit Implementasi Keperawatan</b>
			</div>

			<div class="card">

				<input class="form-control" name="noreg" value="<?php echo $noreg;?>" id="noreg" type="text" size='50' onfocus="nextfield ='';" placeholder="noreg" readonly>


				<input class="form-control" name="sift" value="<?php echo $sift;?>" id="sift" type="text" size='50' onfocus="nextfield ='';" placeholder="sift" readonly>
				<br>
				<textarea class="ckeditor" id="ckedtor" name="implementasi" cols="100%">
					<?php echo $implementasi;?>
				</textarea>
			</div>

			<br>
			<div class="row">
				&nbsp;&nbsp;&nbsp;<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';">simpan implementasi</button> 
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
		$q  = "update ERM_IMPLEMENTASI_ASUHAN set implementasi='$data' where id='$idimplementasi'";         
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

