<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

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
			<br>

			<div class="card">
				<select name="jenis" class="form-control">
					<option value=''>Pilih Jenis Document !</option>
					<option value='ASSESMEN_AWAL_DEWASA'>ASSESMEN_AWAL_DEWASA</option>
					<option value='ASSESMEN_AWAL_ANAK'>ASSESMEN_AWAL_ANAK</option>	
					<option value='ASSESMEN_AWAL_NEONATUS'>ASSESMEN_AWAL_NEONATUS</option>
					<option value='ASSESMEN_AWAL_GERIATRI'>ASSESMEN_AWAL_GERIATRI</option>
					<option value='ASSESMEN_AWAL_BERSALIN'>ASSESMEN_AWAL_BERSALIN</option>
				</select>				
				<br />
				<br>
				<textarea class="ckeditor" id="ckedtor" name="isian" cols="100%">
					<?php echo $isian;?>
				</textarea>
				<br />
				<button type="submit" name="simpan" class="btn btn-warning" onfocus="nextfield ='done';">simpan</button>  	

			</div>

			<div class="card">
				<div class="card-header bg-primary text-white">
					MASTER FORM
				</div>
			</div>

			<div class="card">
				<table class="table">
					<tr>
						<td>id</td><td>form</td><td>isi</td><td>action</td>
					</tr>
					<?php 

					$ql="SELECT  id,form,isi FROM ERM_FORM";
					$hl  = sqlsrv_query($conn, $ql);
					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){     
						echo "	<tr bgcolor=$color>
						<td>$dl[id]</td>
						<td>$dl[form]</td>
						<td>
						<a href='mformedit.php?id=$dl[id]'>edit</a>
						</td>					
						</tr>
						";
					}

					?>
				</table>
			</div>

		</form>
	</body>
</div>

<?php
if (isset($_POST["simpan"])) {

	$lanjut = 'Y';
	$jenis = $_POST["jenis"];
	$isian = $_POST["isian"];
	$data = trim($isian);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);

		//jika tidak ada insert
	echo	$q  = "insert ERM_FORM(form,isi) values 
	('$jenis','$data')";         
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

