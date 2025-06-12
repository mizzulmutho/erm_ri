<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idsoap = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = trim($d1u['noreg']);
$sbu = $d1u['sbu'];
$norm = trim($d1u['norm']);
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

$nama	= $data2[nama];
$alamat	= $data2[alamatpasien];
$tgllahir	= $data2[tgllahir];


//get data dari resume...
$qe="
SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume,CONVERT(VARCHAR, tglentry, 8) as jamentry
FROM ERM_RI_RESUME
where noreg='$noreg'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$jamentry = $de['jamentry'];
$resume2= $de['resume2'];
$resume1 = $de['resume1'];
$resume2= $de['resume2'];
$resume3= $de['resume3'];
$resume4= $de['resume4'];
$resume5= $de['resume5'];
$resume6= $de['resume6'];
$resume7= $de['resume7'];
$resume8= $de['resume8'];
$resume9= $de['resume9'];
$resume10= $de['resume10'];
$resume11= $de['resume11'];
$resume12= $de['resume12'];
$resume13= $de['resume13'];
$resume14= $de['resume14'];
$resume15= $de['resume15'];
$resume16= $de['resume16'];
$resume17= $de['resume17'];
$resume18= $de['resume18'];
$resume19= $de['resume19'];
$resume20= $de['resume20'];
$resume21= $de['resume21'];
$resume22= $de['resume22'];
$resume23= $de['resume23'];
$resume24= $de['resume24'];
$resume25= $de['resume25'];
$resume26= $de['resume26'];
$resume27= $de['resume27'];
$resume28= $de['resume28'];
$resume29= $de['resume29'];
$resume30= $de['resume30'];
$resume31= $de['resume31'];
$resume32= $de['resume32'];
$resume33= $de['resume33'];
$resume34= $de['resume34'];
$resume35= $de['resume35'];
$resume36= $de['resume36'];
$resume37= $de['resume37'];
$resume38= $de['resume38'];
$resume39= $de['resume39'];
$resume40= $de['resume40'];
$resume41= $de['resume41'];
$resume42= $de['resume42'];
$resume43= $de['resume43'];
$resume44= $de['resume44'];
$resume45= $de['resume45'];
$resume46= $de['resume46'];
$resume47= $de['resume47'];
$resume48= $de['resume48'];
$resume49= $de['resume49'];
$resume50= $de['resume50'];
$resume51= $de['resume51'];
$resume52= $de['resume52'];
$resume53= $de['resume53'];
$resume54= $de['resume54'];
$resume55= $de['resume55'];
$resume56= $de['resume56'];
$resume57= $de['resume57'];
$resume58= $de['resume58'];
$resume59= $de['resume59'];
$resume60= $de['resume60'];
$resume61= $de['resume61'];
$resume62= $de['resume62'];
$resume63= $de['resume63'];
$resume64= $de['resume64'];
$resume65= $de['resume65'];
$resume66= $de['resume66'];
$resume67= $de['resume67'];
$resume68= $de['resume68'];
$resume69= $de['resume69'];
$resume70= $de['resume70'];
$resume71= $de['resume71'];
$resume72= $de['resume72'];
$resume73= $de['resume73'];
$resume74= $de['resume74'];
$resume75= $de['resume75'];
$resume76= $de['resume76'];
$resume77= $de['resume77'];
$resume78= $de['resume78'];
$resume79= $de['resume79'];
$resume80= $de['resume80'];
$resume81= $de['resume81'];
$resume82= $de['resume82'];
$resume83= $de['resume83'];
$resume84= $de['resume84'];
$resume85= $de['resume85'];
$resume86= $de['resume86'];
$resume87= $de['resume87'];
$resume88= $de['resume88'];
$resume89= $de['resume89'];
$resume90= $de['resume90'];
$resume91= $de['resume91'];
$resume92= $de['resume92'];
$resume93= $de['resume93'];
$resume94= $de['resume94'];
$resume95= $de['resume95'];
$resume96= $de['resume96'];
$resume97= $de['resume97'];
$resume98= $de['resume98'];
$resume99= $de['resume99'];
$resume100= $de['resume100'];
$resume101= $de['resume101'];
$resume102= $de['resume102'];
$resume103= $de['resume103'];
$resume104= $de['resume104'];
$resume105= $de['resume105'];
$resume106= $de['resume106'];
$resume107= $de['resume107'];
$resume108= $de['resume108'];
$resume109= $de['resume109'];
$resume110= $de['resume110'];
$resume111= $de['resume111'];
$resume112= $de['resume112'];
$resume113= $de['resume113'];
$resume114= $de['resume114'];
$resume115= $de['resume115'];
$resume116= $de['resume116'];
$resume117= $de['resume117'];
$resume118= $de['resume118'];
$resume119= $de['resume119'];
$resume120= $de['resume120'];
$resume121= $de['resume121'];
$resume122= $de['resume122'];
$resume123= $de['resume123'];
$resume124= $de['resume124'];
$resume125= $de['resume125'];
$resume126= $de['resume126'];
$resume127= $de['resume127'];
$resume128= $de['resume128'];
$resume129= $de['resume129'];
$resume130= $de['resume130'];
$resume131= $de['resume131'];
$resume132= $de['resume132'];
$resume133= $de['resume133'];
$resume134= $de['resume134'];
$resume135= $de['resume135'];
$resume136= $de['resume136'];
$resume137= $de['resume137'];
$resume138= $de['resume138'];
$resume139= $de['resume139'];
$resume140= $de['resume140'];
$resume141= $de['resume141'];
$resume142= $de['resume142'];
$resume143= $de['resume143'];
$resume144= $de['resume144'];
$resume145= $de['resume145'];
$resume146= $de['resume146'];
$resume147= $de['resume147'];
$resume148= $de['resume148'];
$resume149= $de['resume149'];
$resume150= $de['resume150'];


$qres       = "
SELECT      TOP(1)*
FROM            SS_RI_RESUME
WHERE NOREG='$noreg' and IDENCOUNTER <> '' 
";
$hres  = sqlsrv_query($conn, $qres);                

$data_res    = sqlsrv_fetch_array($hres, SQLSRV_FETCH_ASSOC);  
$IDENCOUNTER = $data_res[IDENCOUNTER];

$cekreg = $data_res[NOREG];
$ihsnumber = $data_res[ihsnumber];
$namapasien = $data_res[namapasien];
$iddokter = $data_res[iddokter];
$namadokter = $data_res[namadokter];
$IDFORMULIR_INAP = $data_res[IDFORMULIR_INAP];
$IDKELUHAN_UTAMA = $data_res[IDKELUHAN_UTAMA];
$IDRIWAYAT_PENYAKIT = $data_res[IDRIWAYAT_PENYAKIT];
$IDRIWAYAT_ALERGI = $data_res[IDRIWAYAT_ALERGI];
$IDRIWAYAT_OBAT = $data_res[IDRIWAYAT_OBAT];
$IDRIWAYAT_OBATSTATEMEN = $data_res[IDRIWAYAT_OBATSTATEMEN];
$IDKESADARAN = $data_res[IDKESADARAN];
$IDVITALSIGN_SUHU = $data_res[IDVITALSIGN_SUHU];
$IDFISIK_KEPALA = $data_res[IDFISIK_KEPALA];
$IDPSIKOLOGI = $data_res[IDPSIKOLOGI];
$IDDIAGNOSA_PRIMARY = $data_res[IDDIAGNOSA_PRIMARY];
$IDDIAGNOSA_SECONDARY = $data_res[IDDIAGNOSA_SECONDARY];
$IDTINDAKAN = $data_res[IDTINDAKAN];
$IDEDUKASI = $data_res[IDEDUKASI];
$IDPROGNOSIS = $data_res[IDPROGNOSIS];
$IDRUJUKAN_FASKES = $data_res[IDRUJUKAN_FASKES];
$IDKONTROL_MINGGU = $data_res[IDKONTROL_MINGGU];
$IDKONDISI_KELUAR = $data_res[IDKONDISI_KELUAR];
$IDRAWAT_PASIEN = $data_res[IDRAWAT_PASIEN];
$IDINSTRUKSI_MEDIK = $data_res[IDINSTRUKSI_MEDIK];
$IDLABSERVICE_REQ = $data_res[IDLABSERVICE_REQ];
$IDLABSPECIMEN = $data_res[IDLABSPECIMEN];
$IDLABOBSERVATION = $data_res[IDLABOBSERVATION];
$IDLABDIAGNOSIC = $data_res[IDLABDIAGNOSIC];
$IDRAD_STATUSKEHAMILAN = $data_res[IDRAD_STATUSKEHAMILAN];
$IDRAD_STATUSALERGI = $data_res[IDRAD_STATUSALERGI];
$IDRAD_SERVICEREQ = $data_res[IDRAD_SERVICEREQ];
$IDRAD_OBSERVATION = $data_res[IDRAD_OBSERVATION];
$IDRAD_DIAGNOSIC = $data_res[IDRAD_DIAGNOSIC];
$IDPERESEPAN_MEDICATION = $data_res[IDPERESEPAN_MEDICATION];
$IDPERESEPAN_MEDICATIONREQUEST = $data_res[IDPERESEPAN_MEDICATIONREQUEST];
$IDQuestionnaireResponse = $data_res[IDQuestionnaireResponse];
$IDPENGELUARANOBAT_MEDICATION = $data_res[IDPENGELUARANOBAT_MEDICATION];
$IDPENGELUARANOBAT_MEDICATIONDISPENCE = $data_res[IDPENGELUARANOBAT_MEDICATIONDISPENCE];
$IDRENCANAPULANG_OBSERVATION = $data_res[IDRENCANAPULANG_OBSERVATION];
$IDRENCANAPULANG_CAREPLAN = $data_res[IDRENCANAPULANG_CAREPLAN];
$IDDIET = $data_res[IDDIET];

if(empty($IDENCOUNTER)){

	$q  = "update ARM_REGISTER set IDENCOUNTERSS='' where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);

}


$link = 's_sehat_resume_verifikasi.php?id='.trim($noreg).'|'.trim($sbu).'|'.trim($IDENCOUNTER).'|';

$lanjut='Y';

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

	<!-- Jqueri autocomplete untuk procedure !!! -->
	<link rel="stylesheet" href="jquery-ui.css">
	<script src="jquery-1.10.2.js"></script>
	<script src="jquery-ui.js"></script>

	<script>
		$(function() {
			$("#dokter").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.kodedokter + ' - ' + item.nama + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  
</head> 

<div class="container">

	<body onload="document.myForm.kodedokter.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">

				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
				<br><br>

				<br>
				<div class="row">
					<div class="col-12">
						<i class="bi bi-window-plus"> &nbsp; <b>VERFIFIKASI DATA RESUME MEDIS</b></i>
					</div>
				</div>
				<hr>
				<?php if($cekreg){ ?>
					<div class="row">

						<table width='90%'>
							<tr>
								<td colspan='2' align='center'>
									Mohon terlebih dahulu untuk mengirim data Resume Medis ke <b>SATU SEHAT</b> kemenkes<br><br>
								</td>
							</tr>
							<tr>
								<td>
									<table width='100%'>
										<tr>
											<td>Resource</td><td>kirim</td>
										</tr>
										<tr>
											<td>Data Formulir Rawat Inap</td>
											<td>
												<?php 
												if(empty($IDKELUHAN_UTAMA)){
													$link1=$link.'formulir|'.$id.'|'.$user;
													echo "<a href='$link1'>click</a>";
													$lanjut='T';
												}else{
													echo "[ &radic;	]";
												}
												?>									
											</td>
										</tr>
										<tr>
											<td>Data Diagnosis</td>
											<td>
												<?php 
												if(empty($IDDIAGNOSA_PRIMARY)){
													$link2=$link.'diagnosis|'.$id.'|'.$user;
													echo "<a href='$link2'>click</a>";
													$lanjut='T';
												}else{
													echo "[ &radic;	]";
												}
												?>
											</td>
										</tr>
									</table>
								</td>
								<td>
									<table width='100%'>
										<tr>
											<td>Resource</td><td>kirim</td>
										</tr>
										<tr>
											<td>Data Rencana Rawat Pasien</td>
											<td>
												<?php 
												if(empty($IDRAWAT_PASIEN)){
													$link3=$link.'rawat|'.$id.'|'.$user;
													echo "<a href='$link3'>click</a>";
													$lanjut='T';
												}else{
													echo "[ &radic;	]";
												}
												?>
											</td>
										</tr>
										<tr>
											<td>Data Pemeriksaan Penunjang</td>
											<td>
												<?php 
												if(empty($IDLABSERVICE_REQ)){
													$link4=$link.'penunjang|'.$id.'|'.$user;
													echo "<a href='$link4'>click</a>";
													$lanjut='T';
												}else{
													echo "[ &radic;	]";
												}
												?>
											</td>
										</tr>
										<tr>
											<td>Data Peresepan Obat</td>
											<td>
												<?php 
												if(empty($IDPERESEPAN_MEDICATION)){
													$link5=$link.'resep|'.$id.'|'.$user;
													echo "<a href='$link5'>click</a>";
													$lanjut='T';
												}else{
													echo "[ &radic;	]";
												}
												?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					<hr>
				<?php } ?>
				<?php if(empty($cekreg)){$lanjut='Y';} ?>
				<div class="row">
					<div class="col-3">
						<label for="" class="">Tgl Approval</label>
					</div>
					<div class="col-6">
						<input class="form-control" name="tglinput" value="<?php echo $tglinput;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="" disabled>
					</div>
				</div>

				<div class="row">
					<div class="col-3">
						<label for="" class="">Password Petugas</label>
					</div>					
					<div class="col-6">
						<input class="form-control" name="pass" value="<?php echo $pass;?>" id="" type="text" size='50' onfocus="nextfield ='simpan';" placeholder="Isikan Password ERM">
					</div>										
				</div>
				<div class="row">
					<div class="col-3">
						<label for="" class="">Status Verifikasi</label>
					</div>					
					<div class="col-6">
						<?php 
						$qur="SELECT noreg FROM ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
						$h1ur  = sqlsrv_query($conn, $qur);        
						$d1ur  = sqlsrv_fetch_array($h1ur, SQLSRV_FETCH_ASSOC); 
						$ceknoreg = trim($d1ur['noreg']);

						if($ceknoreg){
							echo "<font color='blue'>&nbsp;Resume Medik Sudah Terverifikasi</font>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "<a href='resume_print.php?id=$id|$user' target='_blank'>Cetak Resume</a>";
						}
						?>
					</div>										
				</div>
				<br><br>
				<div class="row">
					<div class="col-4">
						&nbsp;
					</div>
					<div class="col-6 align-self-center">
						<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" <?php if($lanjut=='T'){ echo 'disabled'; }?> style="height: 60px;width: 150px;"><i class="bi bi-save-fill" ></i> VERIF </button>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<button type='submit' name='btl_verif' value='btl_verif' class="btn btn-warning" type="button" <?php if($lanjut=='T'){ echo 'disabled'; }?> style="height: 60px;width: 150px;"><i class="bi bi-x-circle"></i> BATAL VERIF </button>
					</div>
				</div>
				<br><br>

			</div>
			<br><br><br>
		</form>
	</font>
</body>
</div>

<?php

if (isset($_POST["btl_verif"])) {


	$pass = trim($_POST["pass"]);
	$lanjut="Y";

	if(empty($pass)){
		$eror='Password Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	// cek user
	$query = "select * from ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$user'";		
	$result = sqlsrv_query($conn, $query);
	$data  = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);          

	// cek pass
	if (trim($pass)<>strtolower(trim($data['PASS2']))) {	
		if (trim($pass)<>strtoupper(trim($data['PASS2']))) {	
			$eror='Password Salah !!!';
			$lanjut = 'T';
		}
	}


	if($lanjut == 'Y'){

		$qur="SELECT noreg FROM ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
		$h1ur  = sqlsrv_query($conn, $qur);        
		$d1ur  = sqlsrv_fetch_array($h1ur, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1ur['noreg']);


		$q  = "delete ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
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
		

	}else{
		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";

	}



}

if (isset($_POST["simpan"])) {

	$pass = trim($_POST["pass"]);

	$lanjut="Y";


	if(empty($pass)){
		$eror='Password Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	// cek user
	$query = "select * from ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$user'";		
	$result = sqlsrv_query($conn, $query);
	$data  = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);          

	// cek pass
	if (trim($pass)<>strtolower(trim($data['PASS2']))) {	
		if (trim($pass)<>strtoupper(trim($data['PASS2']))) {	
			$eror='Password Salah !!!';
			$lanjut = 'T';
		}
	}

	if(empty($resume8) or ($resume8=='')){
		$eror='Keluhan Utama masih kosong !!!';
		$lanjut = 'T';
	}

	if(empty($resume134) or ($resume134=='')){
		$eror='Tanda Tangan Pasien masih kosong !!!';
		$lanjut = 'T';
	}

	if(empty($resume4) or ($resume4=='')){
		$eror='Tgl KRS masih kosong !!!';
		$lanjut = 'T';
	}

	if(empty($resume52) or ($resume52=='')){
		$eror='Data Ahli Gizi belum ada !!!';
		$lanjut = 'T';
	}

	if(empty($resume133) or ($resume133=='')){
		$eror='Data Apoteker belum ada !!!';
		$lanjut = 'T';
	}

	if($lanjut == 'Y'){

		$qur="SELECT noreg FROM ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
		$h1ur  = sqlsrv_query($conn, $qur);        
		$d1ur  = sqlsrv_fetch_array($h1ur, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1ur['noreg']);

		if(empty($ceknoreg)){

			$q  = "insert into ERM_RI_RESUME_APPROVEL
			(noreg, userid, tglentry, tgl_approve) 
			values 
			('$noreg','$user','$tglinput','$tglinput')";
			$hs = sqlsrv_query($conn,$q);

		}else{

			$q  = "update ERM_RI_RESUME_APPROVEL set userid='$user' where noreg='$noreg'";
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
		

	}else{
		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";

	}


}


?>

