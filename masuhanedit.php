<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$q		= "select * from ERM_MASTER_ASUHANKEPERAWATAN where id=$id";
$hasil  = sqlsrv_query($conn, $q);			  
$data2	= sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);	

$isian = html_entity_decode($data2[isian]);
$jenis = $data2[jenis];
$diagnosa_nama = $data2[diagnosa_nama];
$diagnosa_keperawatan = $data2[diagnosa_keperawatan];
$implementasi = $data2[implementasi];

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
			<a href='m_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			<br>
			<br>

			<div class="card">
				<input class="form-control" name="jenis" value="<?php echo $jenis;?>" id="jenis" type="text" size='50' onfocus="nextfield ='';" placeholder="jenis">
				<br>
				<input class="form-control" name="diagnosa_keperawatan" value="<?php echo $diagnosa_keperawatan;?>" id="diagnosa_keperawatan" type="text" size='50' onfocus="nextfield ='';" placeholder="diagnosa_keperawatan">
				<br>
				<input class="form-control" name="diagnosa_nama" value="<?php echo $diagnosa_nama;?>" id="diagnosa_nama" type="text" size='50' onfocus="nextfield ='';" placeholder="diagnosa_nama">
				<br>				

				<br />
				Rencana Asuhan<br>
				<textarea class="ckeditor" id="ckedtor" name="isian" cols="100%">
					<?php echo $isian;?>
				</textarea>
				<br />

				<br />
				Implementasi Rencana Asuhan<br>
				<textarea class="ckeditor" id="ckedtor" name="implementasi" cols="100%">
					<?php echo $implementasi;?>
				</textarea>
				<br />

				<button type="submit" name="simpan" class="btn btn-warning" onfocus="nextfield ='done';">simpan</button>  	

			</div>

		</form>
	</body>
</div>

<?php
if (isset($_POST["simpan"])) {

	$lanjut = 'Y';
	$isian = $_POST["isian"];
	$data = trim($isian);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);

	$implementasi = $_POST["implementasi"];
	$data2 = trim($implementasi);
	$data2 = stripslashes($data2);
	$data2 = htmlspecialchars($data2);

		//jika tidak ada insert
	$q  = "update ERM_MASTER_ASUHANKEPERAWATAN set isian='$data',implementasi='$data2' where id=$id";         
	$hs = sqlsrv_query($conn,$q);


	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	alert('".$eror."');
	</script>
	";

	echo "
	<script>
	top.location='m_asuhankeperawatan.php?id=$id';
	</script>
	";

}
?>

