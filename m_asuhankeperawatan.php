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
			<a href='form_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			<br>
			<br>

			<div class="card">
				<select name="jenis" class="form-control">
					<option value=''>Pilih Jenis Document !</option>
					<option value='RENCANA ASUHAN KEPERAWATAN'>RENCANA ASUHAN KEPERAWATAN</option>
					<option value='RENCANA ASUHAN KEBIDANAN'>RENCANA ASUHAN KEBIDANAN</option>
				</select>				
				<br />
				<input class="form-control" name="diagnosa_keperawatan" value="<?php echo $diagnosa_keperawatan;?>" id="diagnosa_keperawatan" type="text" size='50' onfocus="nextfield ='';" placeholder="diagnosa_keperawatan">
				<br>
				<input class="form-control" name="diagnosa_nama" value="<?php echo $diagnosa_nama;?>" id="diagnosa_nama" type="text" size='50' onfocus="nextfield ='';" placeholder="diagnosa_nama">
				<br>				
				<br>
				<textarea class="ckeditor" id="ckedtor" name="isian" cols="100%">
					<?php echo $isian;?>
				</textarea>
				<br />
				<button type="submit" name="simpan" class="btn btn-warning" onfocus="nextfield ='done';">simpan</button>  	

			</div>

			<div class="card">
				<div class="card-header bg-primary text-white">
					MASTER DATA
				</div>
			</div>

			<div class="card">
				<table class="table">
					<tr>
						<td>id</td><td>sbu</td><td>nama</td><td>action</td>
					</tr>
					<?php 

					$ql="SELECT  id,jenis,diagnosa_keperawatan,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN";
					$hl  = sqlsrv_query($conn, $ql);
					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){     
						echo "	<tr bgcolor=$color>
						<td>$dl[id]</td>
						<td>$dl[jenis]</td>
						<td>$dl[diagnosa_keperawatan]</td>
						<td>$dl[diagnosa_nama]</td>
						<td>
						<a href='masuhanedit.php?id=$dl[id]'>edit</a>
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
	$diagnosa_keperawatan = $_POST["diagnosa_keperawatan"];
	$diagnosa_nama = $_POST["diagnosa_nama"];
	$isian = $_POST["isian"];
	$data = trim($isian);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);

		//jika tidak ada insert
	echo	$q  = "insert ERM_MASTER_ASUHANKEPERAWATAN(jenis,diagnosa_keperawatan,diagnosa_nama,isian) values 
	('$jenis','$diagnosa_keperawatan','$diagnosa_nama','$data')";         
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

