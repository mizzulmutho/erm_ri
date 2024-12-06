<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$noreg = $row[2];
$diagnosa_keperawatan =trim($row[3]); 

// $qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
// $h1u  = sqlsrv_query($conn, $qu);        
// $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
// $noreg = $d1u['noreg'];



$ql1="SELECT        diagnosa_nama
FROM            ERM_MASTER_ASUHANKEPERAWATAN
WHERE        (diagnosa_keperawatan = '$diagnosa_keperawatan')";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$diagnosa_nama = $d11['diagnosa_nama'];


$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='ket1'";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$ket1 = $d11['rencana'];

$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='ket2'";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$ket2 = $d11['rencana'];

$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='ket3'";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$ket3 = $d11['rencana'];


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

	<div class="row">
		<div class="col-12">
			<?php 
			include "header_soap.php";
			?>
		</div>
	</div>

	<div class="row">
		<div class="col-12">

			<body onload="document.myForm.pasien_mcu.focus();">
				<font size='2px'>	



					<form method="POST" name='myForm' action="" enctype="multipart/form-data">
						<br>
						<a href='askep.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
						&nbsp;&nbsp;
						<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
						&nbsp;&nbsp;
						<a href='m_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class=''><i>Master</i></a>
						<br>
						<br>
						<div class="row">
							<div class="col-sm-12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<b><font size="4px"><center>Rencana Asuan Keperawatan</center></font></b>
								<br>
							</div>
						</div>
					</div>
					<table width="90%" class="" align='center'>
						<input class="" name="idrasuhan" value="<?php echo $idrasuhan;?>" id="idrasuhan" type="text" size='50' onfocus="nextfield ='';" placeholder="idrasuhan" hidden>
						<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;diagosa keperawatan : <input class="" name="diagnosa_keperawatan" value="<?php echo $diagnosa_keperawatan;?>" id="" type="text" size='10' onfocus="nextfield ='';" placeholder="">
					</table>
					<table width="90%" border='0' align='center'>
						<tr valign="top">
							<td width='33%'><font color='blue'><b>Diagnosa Keperawatan</b></font>
								<br>
								<b><font size='3px'><?php echo $diagnosa_nama; ?></font></b> <br>
								<?php 
								$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN1 where diagnosa ='$diagnosa_keperawatan' order by kode";
								$hasil  = sqlsrv_query($conn, $q);		
								$i=1;	  
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){	
									$data[ket] = trim($data[ket]);
							//cari inputan...
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master1' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

									if($rencana){
										echo "<input type='checkbox' name='master1$i' value='$data[ket]' checked>$data[ket]";
									}else{
										echo "<input type='checkbox' name='master1$i' value='$data[ket]'>$data[ket]";
									}

									echo "<br>";
									$i=$i+1;
								}
								?>
								<br>
								Dibuktikan dengan
								<br>
								<b>DS:</b><br>
								<?php 
								$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN2 where diagnosa ='$diagnosa_keperawatan' order by kode";
								$hasil  = sqlsrv_query($conn, $q);		
								$i=1;		  
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  
									$data[ket] = trim($data[ket]);
							//cari inputan...
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master2' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

									if($rencana){
										echo "<input type='checkbox' name='master2$i' value='$data[ket]' checked>$data[ket]";
									}else{
										echo "<input type='checkbox' name='master2$i' value='$data[ket]'>$data[ket]";
									}

									echo "<br>";
									$i=$i+1;
								}
								?>
								<?php 
								if(empty($ket1)){
									$qu="SELECT * FROM  V_ERM_RI_KEADAAN_UMUM where noreg='$noreg' and tensi is not null";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$kesadaran = $d1u['kesadaran'];
									$e = $d1u['e'];
									$v = $d1u['v'];
									$m = $d1u['m'];
									$suhu = $d1u['suhu'];
									$tensi = $d1u['tensi'];
									$nadi = $d1u['nadi'];
									$ket_nadi = $d1u['ket_nadi'];
									$nafas = $d1u['nafas'];
									$spo = $d1u['spo'];
									$skala_nyeri = $d1u['skala_nyeri'];
									$lokasi_nyeri = $d1u['lokasi_nyeri'];
									// $ket1= 'Skala Nyeri : '.$skala_nyeri.' , Lokasi Nyeri : '.$lokasi_nyeri; 
								}else{
									$ket1=$ket1 ; 
								}
								?>
								<textarea name= "ket1" id="" style="min-width:330px; min-height:100px;"><?php echo $ket1;?></textarea>

								<br><br>
								<b>DO:</b>
								<br>
								Tanda Mayor
								<br>
								<?php 
								$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN3 where diagnosa ='$diagnosa_keperawatan' order by kode";
								$hasil  = sqlsrv_query($conn, $q);	
								$i=1;			  
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  
									$data[ket] = trim($data[ket]);
							//cari inputan...
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master3' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

									if($rencana){
										echo "<input type='checkbox' name='master3$i' value='$data[ket]' checked>$data[ket]";
									}else{
										echo "<input type='checkbox' name='master3$i' value='$data[ket]'>$data[ket]";
									}


									echo "<br>";
									$i=$i+1;
								}
								?>
								Tanda Minor
								<br>
								<?php 
								$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN4 where diagnosa ='$diagnosa_keperawatan' and ket not like '%TD%' order by kode";
								$hasil  = sqlsrv_query($conn, $q);	
								$i=1;			  
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  
									$data[ket] = trim($data[ket]);
							//cari inputan...
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master4' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

									if($rencana){
										echo "<input type='checkbox' name='master4$i' value='$data[ket]' checked>$data[ket]";
									}else{
										echo "<input type='checkbox' name='master4$i' value='$data[ket]'>$data[ket]";
									}


									echo "<br>";
									$i=$i+1;
								}
								?>
								<?php 
								if(empty($ket2)){

							//select
									$qu="SELECT * FROM  V_ERM_RI_KEADAAN_UMUM where noreg='$noreg' and tensi is not null";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$kesadaran = $d1u['kesadaran'];
									$e = $d1u['e'];
									$v = $d1u['v'];
									$m = $d1u['m'];
									$suhu = $d1u['suhu'];
									$tensi = $d1u['tensi'];
									$nadi = $d1u['nadi'];
									$ket_nadi = $d1u['ket_nadi'];
									$nafas = $d1u['nafas'];
									$spo = $d1u['spo'];
									$skala_nyeri = $d1u['skala_nyeri'];
									$lokasi_nyeri = $d1u['lokasi_nyeri'];

									if($tensi){
										$ket2="kesadaran:$kesadaran, tensi:$tensi, nadi:$nadi-$ket_nadi, suhu:$suhu, nafas:$nafas, spo:$spo, e:$e, v:$v, m:$m";
									}else{
										$ket2="... "; 
									}
								}else{
									$ket2=$ket2 ; 
								}
								?>
								<textarea name= "ket2" id="" style="min-width:330px; min-height:100px;"><?php echo $ket2;?></textarea>
								<br>

							</td>
							<td width='33%'>
								<font color='blue'><b>Tujuan dan Kriteria Hasil</b></font><br>
								<?php 
								if(empty($ket3)){
									$ket3="Setelah dilakukan tindakan keperawatan selama \n ....... x ....... jam \n $diagnosa_nama menurun dengan kriteria hasil: "; 
								}else{
									$ket3=$ket3 ; 
								}
								?>
								<textarea name= "ket3" id="" style="min-width:330px; min-height:100px;"><?php echo $ket3;?></textarea>
								<br>
								<?php 
								$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN5 where diagnosa ='$diagnosa_keperawatan' order by kode";
								$hasil  = sqlsrv_query($conn, $q);		
								$i=1;		  
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  
									$data[ket] = trim($data[ket]);
							//cari inputan...
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master5' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

									if($rencana){
										echo "<input type='checkbox' name='master5$i' value='$data[ket]' checked>$data[ket]";
									}else{
										echo "<input type='checkbox' name='master5$i' value='$data[ket]'>$data[ket]";
									}

									echo "<br>";
									$i=$i+1;
								}
								?>


							</td>
							<td>

								<font color='blue'><b>Intervensi Keperawatan</b></font><br>
								<!-- <b>KRITERIA HASIL</b><br>
								<?php 
								$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN5 where diagnosa ='$diagnosa_keperawatan' order by kode";
								$hasil  = sqlsrv_query($conn, $q);	
								$i=1;			  
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

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
								<br> -->
								<b>OBSERVASI</b><br>
								<?php 
								$q="SELECT distinct kode,ket FROM ERM_RI_MASTER_ASUHAN6 where diagnosa ='$diagnosa_keperawatan' order by kode";
								$hasil  = sqlsrv_query($conn, $q);			
								$i=1;  
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){				  

							//cari inputan...																		
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master6' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

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
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master7' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

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
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master8' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

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
									$ql1="SELECT  rencana from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis='master9' and rencana='$data[ket]'";
									$hl1  = sqlsrv_query($conn, $ql1);
									$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
									$rencana = $d11['rencana'];

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
						</tr>
					</table>
					<br>
					<div class="row">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<div class="col-sm-12">
							<center>
								<button type="submit" name="simpan" class="btn btn-success btn-smal" onfocus="nextfield ='done';">
									<i class='bi bi-file-earmark-text'></i> simpan
								</button>
							</center> 
						</div>
					</div>
					<br><br>
				</form>
			</font>
		</body>
	</div>
</div>

</div>

<?php

if (isset($_POST["simpan"])) {

	$ket1 = trim($_POST["ket1"]);
	$ket2 = trim($_POST["ket2"]);
	$ket3 = trim($_POST["ket3"]);

	$q  = "delete from ERM_ASUHAN_KEPERAWATAN2 where noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and jenis <> ''";         
	$hs = sqlsrv_query($conn,$q);

	if($ket1){
		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
		('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$ket1','ket1')";         
		$hs = sqlsrv_query($conn,$q);
	}
	if($ket2){
		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
		('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$ket2','ket2')";         
		$hs = sqlsrv_query($conn,$q);
	}
	if($ket3){
		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
		('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$ket3','ket3')";         
		$hs = sqlsrv_query($conn,$q);
	}

	//while
	for ($i=1 ; $i <= 20 ; $i++){
		$masterx = 'master1'.$i;
		$master1 = trim($_POST[$masterx]);

		if(!empty($master1)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master1','master1')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke2
		$masterb = 'master2'.$i;
		$master2 = trim($_POST[$masterb]);
		if(!empty($master2)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master2','master2')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke3
		$masterc = 'master3'.$i;
		$master3 = trim($_POST[$masterc]);
		if(!empty($master3)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master3','master3')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke4
		$masterd = 'master4'.$i;
		$master4 = trim($_POST[$masterd]);
		if(!empty($master4)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master4','master4')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke5
		$mastere = 'master5'.$i;
		$master5 = trim($_POST[$mastere]);
		if(!empty($master5)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master5','master5')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke6
		$masterf = 'master6'.$i;
		$master6 = trim($_POST[$masterf]);
		if(!empty($master6)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master6','master6')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke7
		$masterg = 'master7'.$i;
		$master7 = trim($_POST[$masterg]);
		if(!empty($master7)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master7','master7')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke8
		$masterh = 'master8'.$i;
		$master8 = trim($_POST[$masterh]);
		if(!empty($master8)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master8','master8')";         
			$hs = sqlsrv_query($conn,$q);
		}

		//ke9
		$masteri = 'master9'.$i;
		$master9 = trim($_POST[$masteri]);
		if(!empty($master9)){
			echo		$q  = "insert ERM_ASUHAN_KEPERAWATAN2(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana, jenis) values 
			('$id','$noreg','$diagnosa_keperawatan','$tgl','$user','$master9','master9')";         
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

