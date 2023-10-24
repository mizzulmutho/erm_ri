<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT * FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];

$q		= "select * from ERM_RI_ASSESMEN_AWAL where noreg='$noreg' and jenis like '%RESIKOJATUH_DEWASA%'";
$hasil  = sqlsrv_query($conn, $q);			  
$data2	= sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);	

$isian = html_entity_decode($data2[ISI]);
$cekreg = $data2['noreg'];
$isian = html_entity_decode($data2[ISI]);


if(empty($cekreg)){
	$q		= "select * from ERM_FORM where FORM='RESIKOJATUH_DEWASA'";
	$hasil  = sqlsrv_query($conn, $q);			  
	$data2	= sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);	

	$isian = html_entity_decode($data2[ISI]);
	$jenis = $data2[FORM];
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
			<br>

			<div class="card">
				<textarea class="ckeditor" id="ckedtor" name="isian" cols="100%">
					<?php echo $isian;?>
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

	if(empty($cekreg)){
		//jika tidak ada insert
		$q  = "insert into ERM_RI_ASSESMEN_AWAL(norm, noreg, ISI, userid, tglupdate,jenis) values ('$norm','$noreg','$data','$user','$tgl','$jenis')";
		$hs = sqlsrv_query($conn,$q);

	}else{
		//jika tidak ada insert
		$q  = "update ERM_RI_ASSESMEN_AWAL set isi='$data',userid='$user',tglupdate='$tgl' where noreg='$noreg'";         
		$hs = sqlsrv_query($conn,$q);
	}


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
	top.location='form_jatuh_dewasa.php?id=$id|$user';
	</script>
	";

}
?>

