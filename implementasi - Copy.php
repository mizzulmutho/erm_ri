<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);

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
			<br>
			<br>
			<div class="card">
				<b>Input Implementasi Keperawatan</b>
			</div>

			<input class="form-control" name="noreg" value="<?php echo $noreg;?>" id="noreg" type="text" size='50' onfocus="nextfield ='';" placeholder="noreg" readonly>
			<select name="sift" style="width:500px;height:30px" required>
				<option value=''>--Pilih Sift --</option>
				<option value='DINAS PAGI'>DINAS PAGI</option>
				<option value='DINAS SIANG'>DINAS SIANG</option>
				<option value='DINAS MALAM'>DINAS MALAM</option>
			</select>
			<br>
			<table class="">
				<input class="" name="idrasuhan" value="<?php echo $idrasuhan;?>" id="idrasuhan" type="text" size='50' onfocus="nextfield ='';" placeholder="idrasuhan" hidden>
				<br>
				diagosa keperawatan : <input class="" name="diagnosa_keperawatan" value="<?php echo $diagnosa_keperawatan;?>" id="" type="text" size='10' onfocus="nextfield ='';" placeholder="">
			</table>
			<table width="100%" border='0'>
				<tr valign="top">

					<td>
						<font color='blue'><b>Intervensi Keperawatan</b></font><br>
						<b>KRITERIA HASIL</b><br>
						<?php 
						$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN5 where diagnosa ='$diagnosa_keperawatan' order by kode";
						$hasil  = sqlsrv_query($conn, $q);	
						$i=1;			  
						while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

							//cari inputan...
							$ql1="SELECT  implementasi from ERM_IMPLEMENTASI_ASUHAN where noreg='$noreg' and jenis='master5' and implementasi='$data[ket]'";
							$hl1  = sqlsrv_query($conn, $ql1);
							$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
							$rencana = $d11['implementasi'];

							if($rencana){
								echo "<input type='checkbox' name='master5$i' value='$data[ket]' checked>$data[ket]";
							}else{
								echo "<input type='checkbox' name='master5$i' value='$data[ket]'>$data[ket]";
							}

							echo "<br>";
							$i=$i+1;
						}
						?>
						<br>
						<b>OBSERVASI</b><br>
						<?php 
						$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN6 where diagnosa ='$diagnosa_keperawatan' order by kode";
						$hasil  = sqlsrv_query($conn, $q);			
						$i=1;  
						while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

							//cari inputan...
							$ql1="SELECT  implementasi from ERM_IMPLEMENTASI_ASUHAN where noreg='$noreg' and jenis='master6' and implementasi='$data[ket]'";
							$hl1  = sqlsrv_query($conn, $ql1);
							$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
							$rencana = $d11['implementasi'];

							if($rencana){
								echo "<input type='checkbox' name='master6$i' value='$data[ket]' checked>$data[ket]";
							}else{
								echo "<input type='checkbox' name='master6$i' value='$data[ket]'>$data[ket]";
							}


							echo "<br>";
							$i=$i+1;
						}
						?>
						<br>
						<b>TERAPEUTIK</b><br>
						<?php 
						$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN7 where diagnosa ='$diagnosa_keperawatan' order by kode";
						$hasil  = sqlsrv_query($conn, $q);
						$i=1;				  
						while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

							//cari inputan...
							$ql1="SELECT  implementasi from ERM_IMPLEMENTASI_ASUHAN where noreg='$noreg' and jenis='master7' and implementasi='$data[ket]'";
							$hl1  = sqlsrv_query($conn, $ql1);
							$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
							$rencana = $d11['implementasi'];

							if($rencana){
								echo "<input type='checkbox' name='master7$i' value='$data[ket]' checked>$data[ket]";
							}else{
								echo "<input type='checkbox' name='master7$i' value='$data[ket]'>$data[ket]";
							}


							echo "<br>";
							$i=$i+1;
						}
						?>
						<br>
						<b>EDUKASI</b><br>
						<?php 
						$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN8 where diagnosa ='$diagnosa_keperawatan' order by kode";
						$hasil  = sqlsrv_query($conn, $q);
						$i=1;				  
						while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

							//cari inputan...
							$ql1="SELECT  implementasi from ERM_IMPLEMENTASI_ASUHAN where noreg='$noreg' and jenis='master8' and implementasi='$data[ket]'";
							$hl1  = sqlsrv_query($conn, $ql1);
							$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
							$rencana = $d11['implementasi'];

							if($rencana){
								echo "<input type='checkbox' name='master8$i' value='$data[ket]' checked>$data[ket]";
							}else{
								echo "<input type='checkbox' name='master8$i' value='$data[ket]'>$data[ket]";
							}

							echo "<br>";
							$i=$i+1;
						}
						?>
						<br>
						<b>KOLABORASI</b><br>
						<?php 
						$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN9 where diagnosa ='$diagnosa_keperawatan' order by kode";
						$hasil  = sqlsrv_query($conn, $q);
						$i=1;				  
						while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

							//cari inputan...
							$ql1="SELECT  implementasi from ERM_IMPLEMENTASI_ASUHAN where noreg='$noreg' and jenis='master8' and implementasi='$data[ket]'";
							$hl1  = sqlsrv_query($conn, $ql1);
							$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
							$rencana = $d11['implementasi'];

							if($rencana){
								echo "<input type='checkbox' name='master9$i' value='$data[ket]' checked>$data[ket]";
							}else{
								echo "<input type='checkbox' name='master9$i' value='$data[ket]'>$data[ket]";
							}

							echo "<br>";
							$i=$i+1;
						}
						?>

					</td>
					<td>
						<!-- <input class="" name="ket1" value="<?php echo $ket1;?>" id="" type="text" size='30' onfocus="nextfield ='';" placeholder="Ket lain"> -->
						Implementasi <br>
						<textarea name= "ket2" id="" style="min-width:630px; min-height:250px;" placeholder="Isikan Implementasi dg Jam" required><?php echo $ket2;?></textarea>
						<br>
						Tindakan Kolaborasi <br>
						<textarea name= "ket1" id="" style="min-width:630px; min-height:70px;" placeholder="Isikan Tindakan Kolaborasi"><?php echo $ket1;?></textarea>

						<br>
						<div class="row">
							<div class="col-sm-3">
								&nbsp;&nbsp;&nbsp;<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';">simpan</button> 
							</div>
						</div>

					</td>
				</tr>
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
	$ket1 = trim($_POST["ket1"]);
	$ket2 = trim($_POST["ket2"]);

	// $q  = "delete from  ERM_IMPLEMENTASI_ASUHAN where jenis <> ''";         
	// $hs = sqlsrv_query($conn,$q);

	for ($i=1 ; $i <= 20 ; $i++){
		//ke5
		$mastere = 'master5'.$i;
		$master5 = trim($_POST[$mastere]);
		if(!empty($master5)){
			echo		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan,jenis,diagnosa_keperawatan) values 
			('$noreg','$sift','$master5','$userid','$tgl','$idrasuhan','master5','$diagnosa_keperawatan')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke6
		$masterf = 'master6'.$i;
		$master6 = trim($_POST[$masterf]);
		if(!empty($master6)){
			echo		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan,jenis,diagnosa_keperawatan) values 
			('$noreg','$sift','$master6','$userid','$tgl','$idrasuhan','master6','$diagnosa_keperawatan')";     
			$hs = sqlsrv_query($conn,$q);
		}

		//ke7
		$masterg = 'master7'.$i;
		$master7 = trim($_POST[$masterg]);
		if(!empty($master7)){
			echo		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan,jenis,diagnosa_keperawatan) values 
			('$noreg','$sift','$master7','$userid','$tgl','$idrasuhan','master7','$diagnosa_keperawatan')"; 
			$hs = sqlsrv_query($conn,$q);
		}

		//ke8
		$masterh = 'master8'.$i;
		$master8 = trim($_POST[$masterh]);
		if(!empty($master8)){
			echo		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan,jenis,diagnosa_keperawatan) values 
			('$noreg','$sift','$master8','$userid','$tgl','$idrasuhan','master8','$diagnosa_keperawatan')"; 
			$hs = sqlsrv_query($conn,$q);
		}

		//ke9
		$masteri = 'master9'.$i;
		$master9 = trim($_POST[$masteri]);
		if(!empty($master9)){
			echo		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan,jenis,diagnosa_keperawatan) values 
			('$noreg','$sift','$master9','$userid','$tgl','$idrasuhan','master9','$diagnosa_keperawatan')";         
			$hs = sqlsrv_query($conn,$q);
		}

	}

	if($ket1){
		echo		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan,jenis,diagnosa_keperawatan) values 
		('$noreg','$sift','$ket1','$userid','$tgl','$idrasuhan','keterangan','$diagnosa_keperawatan')";         
		$hs = sqlsrv_query($conn,$q);

	}

	if($ket2){
		echo		$q  = "insert ERM_IMPLEMENTASI_ASUHAN(noreg, sift, implementasi, userid, tgl,idasuhan,jenis,diagnosa_keperawatan) values 
		('$noreg','$sift','$ket2','$userid','$tgl','$idrasuhan','keterangan','$diagnosa_keperawatan')";         
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
	history.go(-1);
	</script>
	";


}


?>

