<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tgl2		= gmdate("Y-m-d", time()+60*60*7);
$jam		= gmdate("H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$noreg = $row[2];
$diagnosa_keperawatan =$row[3]; 

$userid=$user;

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

		<div class="row">
			<div class="col-12">
				<?php 
				include "header_soap.php";
				?>
			</div>
		</div>

		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='askep.php?id=<?php echo $id.'|'.$user.'|'.$idrasuhan;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;
			<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
			&nbsp;&nbsp;
			<!-- <a href='d_asuhankeperawatan.php?id=<?php echo $id.'|'.$user.'|'.$noreg;?>' class='btn btn-warning'>
				<i class="bi bi-list-task"></i> Detail Implementasi
			</a> -->
			&nbsp;&nbsp;
			<b> <font size='5' color='#1E90FF'><u>IMPLEMENTASI KEPERAWATAN</u></font></b>
			<br>
			<br>
			NOREG : 
			<input name="noreg" value="<?php echo $noreg;?>" id="noreg" type="text" size='15' onfocus="nextfield ='';" placeholder="noreg" readonly>
			<input class="" name="idrasuhan" value="<?php echo $idrasuhan;?>" id="idrasuhan" type="text" size='50' onfocus="nextfield ='';" placeholder="idrasuhan" hidden>
			diagosa keperawatan : <input class="" name="diagnosa_keperawatan" value="<?php echo $diagnosa_keperawatan;?>" id="" type="text" size='10' onfocus="nextfield ='';" placeholder="">
			<select name="sift" style="width:200px;height:30px" required>
				<option value=''>--Pilih Sift --</option>
				<option value='DINAS PAGI'>DINAS PAGI</option>
				<option value='DINAS SIANG'>DINAS SIANG</option>
				<option value='DINAS MALAM'>DINAS MALAM</option>
			</select>		
			<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>		
			<br>
			
			<br>
			<table class='table'>
				<tr>
					<td>no</td>				
					<td>implementasi</td>
					<td>tgl / jam</td>
				</tr>

				<?php 
				$q="
				SELECT        TOP (200) id, id_assesmen, noreg, diagnosa_keperawatan, rencana
				FROM            ERM_ASUHAN_KEPERAWATAN2
				WHERE        (noreg = '$noreg') and jenis in('master6','master7','master8','master9') and diagnosa_keperawatan='$diagnosa_keperawatan'
				";
				$hasil  = sqlsrv_query($conn, $q);
				$i=1;				  

				while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

					$i_tgl = 'tgl'.$i;
					$i_jam = 'jam'.$i;
					$i_rencana = 'rencana'.$i;


					echo "
					<tr>
					<td>$i</td>
					<td><input type='text' name=$i_rencana value='$data[rencana]' class='form-control'></td>
					<td>
					<input type='date' name=$i_tgl value=''>
					&nbsp;
					<input type='time' name=$i_jam value=''>
					</td>
					</tr>
					";
					$i=$i+1;
					$jumlah += $i;

				}

				?>

			</table>

			<br>
			<br>

		</form>
	</body>
</div>

<?php

if (isset($_POST["simpan"])) {
	$sift = trim($_POST["sift"]);
	$noreg = trim($_POST["noreg"]);

	echo $i;

	// $q  = "delete from  ERM_IMPLEMENTASI_ASUHAN where jenis <> ''";         
	// $hs = sqlsrv_query($conn,$q);

	for ($u=1 ; $u < $i ; $u++){
		$rencana = trim($_POST[rencana.$u]);
		$tanggal = trim($_POST[tgl.$u]);
		$jam = trim($_POST[jam.$u]);

		if($tanggal){
			$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,tanggal,jam,diagnosa_keperawatan) values 
			('$noreg','$sift','$rencana','$userid','$tgl','$tanggal','$jam','$diagnosa_keperawatan')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}

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

