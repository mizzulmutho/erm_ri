<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$aresep = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

$nama	= $data2[nama];
$alamat	= $data2[alamatpasien];
$tgllahir	= $data2[tgllahir];


$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];

$noreg = trim($noreg);


//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

$nama	= $data2[nama];
$alamat	= $data2[alamatpasien];
$tgllahir	= $data2[tgllahir];


//ambil resep
$qi="SELECT noreg FROM ERM_RI_ANAMNESIS_MEDIS where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

$kodedokter = substr($user, 0,3);
$qu2="SELECT NAMA FROM AFARM_DOKTER where KODEDOKTER='$kodedokter'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$nama_dokter = trim($d1u2['NAMA']);

$am75 = $kodedokter.' - '.$nama_dokter;

if(empty($regcek)){
	$q  = "insert into ERM_RI_ANAMNESIS_MEDIS(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tglinput','$tglinput')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ANAMNESIS_MEDIS
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$userid = $de['userid'];

	$tgl = $de['tgl'];
	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment'];
	$dpjp = $de['dpjp'];
	$userid = $de['userid'];
	
	$am1 = $de['am1'];
	$am2= $de['am2'];
	$am3= $de['am3'];
	$am4= $de['am4'];
	$am5= $de['am5'];
	$am6= $de['am6'];
	$am7= $de['am7'];
	$am8= $de['am8'];
	$am9= $de['am9'];
	$am10= $de['am10'];
	$am11= $de['am11'];
	$am12= $de['am12'];
	$am13= $de['am13'];
	$am14= $de['am14'];
	$am15= $de['am15'];
	$am16= $de['am16'];
	$am17= $de['am17'];
	$am18= $de['am18'];
	$am19= $de['am19'];
	$am20= $de['am20'];
	$am21= $de['am21'];
	$am22= $de['am22'];
	$am23= $de['am23'];
	$am24= $de['am24'];
	$am25= $de['am25'];
	$am26= $de['am26'];
	$am27= $de['am27'];
	$am28= $de['am28'];
	$am29= $de['am29'];
	$am30= $de['am30'];
	$am31= $de['am31'];
	$am32= $de['am32'];
	$am33= $de['am33'];
	$am34= $de['am34'];
	$am35= $de['am35'];
	$am36= $de['am36'];
	$am37= $de['am37'];
	$am38= $de['am38'];
	$am39= $de['am39'];
	$am40= $de['am40'];
	$am41= $de['am41'];
	$am42= $de['am42'];
	$am43= $de['am43'];
	$am44= $de['am44'];
	$am45= $de['am45'];
	$am46= $de['am46'];
	$am47= $de['am47'];
	$am48= $de['am48'];
	$am49= $de['am49'];
	$am50= $de['am50'];
	$am51= $de['am51'];
	$am52= $de['am52'];
	$am53= $de['am53'];
	$am54= $de['am54'];
	$am55= $de['am55'];
	$am56= $de['am56'];
	$am57= $de['am57'];
	$am58= $de['am58'];
	$am59= $de['am59'];
	$am60= $de['am60'];
	$am61= $de['am61'];
	$am62= $de['am62'];
	$am63= $de['am63'];
	$am64= $de['am64'];
	$am65= $de['am65'];
	$am66= $de['am66'];
	$am67= $de['am67'];
	$am68= $de['am68'];
	$am69= $de['am69'];
	$am70= $de['am70'];
	$am71= $de['am71'];
	$am72= $de['am72'];
	$am73= $de['am73'];
	$am74= $de['am74'];
	$am75= $de['am75'];

	if(empty($am75)){
		$am75 = $kodedokter.' - '.$nama_dokter;
	}

	$am76= $de['am76'];
	$am77= $de['am77'];
	$am78= $de['am78'];
	$am79= $de['am79'];
	$am80= $de['am80'];

	$qe="
	SELECT resume20,resume21,resume22
	FROM ERM_RI_RESUME
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tglresume = $de['tglresume'];
	
	$resume20= $de['resume20'];
	$resume21= $de['resume21'];
	$resume22= $de['resume22'];

	if(!empty($resume20)){
		$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20;		
	}
	if(!empty($resume21)){
		$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20.'<br>Diagnosis Akhir (Primer) : '.$resume21;		
	}
	if(!empty($resume22)){
		$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20.'<br>Diagnosis Akhir (Primer) : '.$resume21.'<br>Diagnosis Akhir (Sekunder) : '.$resume22;		
	}
}

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

	<script>
		$(function() {
			$("#dpjp").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dpjp.php', //your                         
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

<div class="container-fluid">

	<body onload="document.myForm.ku.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
				&nbsp;&nbsp;

				<div class="row">
					<div class="col-12 text-center">
						<b>INPUT ANAMNESIS MEDIS </b><br>
					</div>
				</div>

				<hr>

<!-- 				<div class="row">
					<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
					<div class="col-6"><?php echo 'L/P : '.$kelamin.'<br> UMUR : '.$umur.'<br> ALAMAT : '.$alamatpasien; ?></div>
				</div>
			-->

			<div class="row">
				<div class="col-6">
					<b>Diagnosa</b>
					<textarea class="form-control" name="diagnosa" cols="100%" onfocus="nextfield ='';" style="min-height:100px;"><?php echo $diagnosa;?></textarea>
					<b>Anamnesis</b>
					
					<br>
					<!-- <input class="form-control form-control-sm" name="am1" value="<?php echo $am1;?>" id="" type="text" size='' onfocus="nextfield ='rps';" placeholder=""> -->
					<textarea class="form-control" name="am1" cols="100%" onfocus="nextfield ='';" style="min-height:100px;"><?php echo $am1;?></textarea>

					<b>Riwayat Penyakit : </b>
					<!-- <input class="form-control form-control-sm" name="am2" value="<?php echo $am2;?>" id="" type="text" size='' onfocus="nextfield ='rpd';" placeholder=""> -->
					<textarea class="form-control" name="am2" cols="100%" onfocus="nextfield ='';" style="min-height:100px;"><?php echo $am2;?></textarea>

					<label for="" class="">Riwayat Alergi : </label>
					<!-- <input class="form-control form-control-sm" name="am3" value="<?php echo $am3;?>" id="" type="text" size='' onfocus="nextfield ='rpd';" placeholder=""> -->
					<textarea class="form-control" name="am3" cols="100%" onfocus="nextfield ='';" style="min-height:100px;"><?php echo $am3;?></textarea>

					<label for="" class="">Riwayat Pengobatan : </label>
					<!-- <input class="form-control form-control-sm" name="am4" value="<?php echo $am4;?>" id="" type="text" size='' onfocus="nextfield ='rpd';" placeholder=""> -->
					<textarea class="form-control" name="am4" cols="100%" onfocus="nextfield ='';" style="min-height:100px;"><?php echo $am4;?></textarea>

					<hr>
					<b>Keadaan Umum</b><br>
					<label for="" class="">Tingkat Kesadaran : </label>
					<!-- <input class="form-control form-control-sm" name="am5" value="<?php echo $am5;?>" id="" type="text" size='' onfocus="nextfield ='kesadaran';" placeholder=""> -->
					<textarea class="form-control" name="am5" cols="100%" onfocus="nextfield ='';" style="min-height:30px;"><?php echo $am5;?></textarea>

					<br>

					<div class="card">
						<div class="card-header">
							Vital Sign
						</div>
						<div class="card-body">
							<div class="card">
								<div class="card-header">
									Glassow Comma Scale (GCS)
								</div>
								<div class="card-body">
									<label for="" class="col-3">Eye : </label>
									<input class="form-control-sm" name="am6" value="<?php echo $am6;?>" id="" type="text" size='' onfocus="nextfield ='verbal';" placeholder="">
									<br>
									<label for="" class="col-3">Verbal : </label>
									<input class="form-control-sm" name="am7" value="<?php echo $am7;?>" id="" type="text" size='' onfocus="nextfield ='movement';" placeholder="">
									<br>
									<label for="" class="col-3">Movement : </label>
									<input class="form-control-sm" name="am8" value="<?php echo $am8;?>" id="" type="text" size='' onfocus="nextfield ='tekanan_darah';" placeholder="">
								</div>
							</div>
							<br>
							<label for="" class="col-3">Tekanan Darah : </label>
							<input class="form-control-sm" name="am9" value="<?php echo $am9;?>" id="" type="text" size='' onfocus="nextfield ='nadi';" placeholder="">mmHg<br>
							<label for="" class="col-3">Nadi : </label>
							<input class="form-control-sm" name="am10" value="<?php echo $am10;?>" id="" type="text" size='' onfocus="nextfield ='suhu';" placeholder="">x/menit<br>
							<label for="" class="col-3">&nbsp;</label>
							<input type='checkbox' name='am11' value='Teratur' <?php if ($am11=="Teratur"){echo "checked";}?>>Teratur
							<input type='checkbox' name='am11' value='Tidak Teratur' <?php if ($am11=="Abnormal"){echo "checked";}?>>Tidak Teratur
							<br>
							<label for="" class="col-3">Suhu : </label>
							<input class="form-control-sm" name="am12" value="<?php echo $am12;?>" id="" type="text" size='' onfocus="nextfield ='frekuansi_pernafasan';" placeholder="">C<br>								
							<label for="" class="col-3">Frekuensi Pernafasan : </label>
							<input class="form-control-sm" name="am13" value="<?php echo $am13;?>" id="" type="text" size='' onfocus="nextfield ='skala_nyeri';" placeholder="">x/menit<br>
							<label for="" class="col-3">Skala Nyeri : </label>
							<input class="form-control-sm" name="am14" value="<?php echo $am14;?>" id="" type="text" size='' onfocus="nextfield ='berat_badan';" placeholder=""><br>
							<label for="" class="col-3">Berat Badan : </label>
							<input class="form-control-sm" name="am15" value="<?php echo $am15;?>" id="" type="text" size='' onfocus="nextfield ='status_lokalis';" placeholder="">Kg<br>							

						</div>
					</div>

					<br>
					<div class="card">
						<div class="card-header">
							Anamnesa Psikologi/Sosial/Ekonomi : 
						</div>
						<div class="card-body">
							<label for="" class="col-3">Kondisi Kejiwaan : </label>												
							<!-- 								<input class="form-control form-control-sm" name="anamnesa" value="<?php echo $anamnesa;?>" id="" type="text" size='' onfocus="nextfield ='rpd';" placeholder="Isikan Tenang/Gelisah/Takut/Bingung/Stres">  -->
							<input type='checkbox' name='am16' value='Tenang' <?php if ($am16=="Tenang"){echo "checked";}?>>Tenang
							<input type='checkbox' name='am16' value='Gelisah/Takut' <?php if ($am16=="Gelisah/Takut"){echo "checked";}?>>Gelisah/Takut
							<input type='checkbox' name='am16' value='Bingung' <?php if ($am16=="Bingung"){echo "checked";}?>>Bingung
							<input type='checkbox' name='am16' value='Stres' <?php if ($am16=="Stres"){echo "checked";}?>>Stres
							<br>
							<label for="" class="col-3">Sosial Ekonomi : </label>
							<input class="form-control-sm" name="am17" value="<?php echo $am17 ;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder=""> <br>
							<label for="" class="col-3">Spiritual : </label>
							<input class="form-control-sm" name="am18" value="<?php echo $am18 ;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder=""> <br>
						</div>
					</div>

					<div class="card">
						<div class="card-body">						

							<label for="" class="col-3">Verifikasi Dokter Pemeriksa : </label>							
							<input class="form-control-sm" name="am75" value="<?php echo $am75;?>" id="dokter" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Dokter atau Kode Dokter">

							<br>

							<?php 
							if($am75){
								$verif_dokter="Resume medis ini telah diVerifikasi Oleh : ".$am75."Pada Tanggal : ".$tgl; 
								// echo "<center><img alt='ttd' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_dokter&choe=UTF-8'/></center>";
								// echo "<br>";

								QRcode::png($verif_dokter, "image.png", "L", 2, 2);   
								echo "<center><img src='image.png'></center>";


								$verif_dokter;
							}
							?>

							<br>

							<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';"><i class="bi bi-save"></i> simpan</button> 
							<br><br>
						</div>
					</div>

				</div>
				<div class="col-6">					
					<div class="card">
						<div class="card-header">
							Pemeriksaan Fisik
						</div>
						<div class="card-body">						
							<div class="card">
								<div class="card-body">
									<label for="" class="col-3">Kepala : </label>
									<input type='checkbox' name='am19' value='Normal' <?php if ($am19=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am19' value='Abnormal' <?php if ($am19=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am20" value="<?php $am20;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>
									<label for="" class="col-3">Mata : </label>
									<input type='checkbox' name='am21' value='Normal' <?php if ($am21=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am21' value='Abnormal' <?php if ($am21=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am22" value="<?php $am22;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Telinga : </label>
									<input type='checkbox' name='am23' value='Normal' <?php if ($am23=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am23' value='Abnormal' <?php if ($am23=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am24" value="<?php $am24;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>								
									<label for="" class="col-3">Hidung : </label>
									<input type='checkbox' name='am25' value='Normal' <?php if ($am25=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am25' value='Abnormal' <?php if ($am25=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am26" value="<?php $am26;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>							
									<label for="" class="col-3">Rambut : </label>
									<input type='checkbox' name='am27' value='Normal' <?php if ($am27=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am27' value='Abnormal' <?php if ($am27=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am28" value="<?php $am28;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Bibir : </label>
									<input type='checkbox' name='am29' value='Normal' <?php if ($am29=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am29' value='Abnormal' <?php if ($am29=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am30" value="<?php $am30;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Gigi Geligi : </label>
									<input type='checkbox' name='am31' value='Normal' <?php if ($am31=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am31' value='Abnormal' <?php if ($am31=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am32" value="<?php $am32;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Lidah : </label>
									<input type='checkbox' name='am33' value='Normal' <?php if ($am33=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am33' value='Abnormal' <?php if ($am33=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am34" value="<?php $am34;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Langit-langit : </label>
									<input type='checkbox' name='am35' value='Normal' <?php if ($am35=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am35' value='Abnormal' <?php if ($am35=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am36" value="<?php $am36;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Leher : </label>
									<input type='checkbox' name='am37' value='Normal' <?php if ($am37=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am37' value='Abnormal' <?php if ($am37=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am38" value="<?php $am38;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Tenggorokan : </label>
									<input type='checkbox' name='am39' value='Normal' <?php if ($am39=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am39' value='Abnormal' <?php if ($am39=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am40" value="<?php $am40;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Tonsil : </label>
									<input type='checkbox' name='am41' value='Normal' <?php if ($am41=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am41' value='Abnormal' <?php if ($am41=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am42" value="<?php $am42;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Dada : </label>
									<input type='checkbox' name='am43' value='Normal' <?php if ($am43=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am43' value='Abnormal' <?php if ($am43=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am44" value="<?php $am44;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Payudara : </label>
									<input type='checkbox' name='am45' value='Normal' <?php if ($am45=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am45' value='Abnormal' <?php if ($am45=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am46" value="<?php $am46;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Punggung : </label>
									<input type='checkbox' name='am47' value='Normal' <?php if ($am47=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am47' value='Abnormal' <?php if ($am47=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am48" value="<?php $am48;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Perut : </label>
									<input type='checkbox' name='am49' value='Normal' <?php if ($am49=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am49' value='Abnormal' <?php if ($am49=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am50" value="<?php $am50;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Genital : </label>
									<input type='checkbox' name='am51' value='Normal' <?php if ($am51=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am51' value='Abnormal' <?php if ($am51=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am52" value="<?php $am52;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Anus/Dubur : </label>
									<input type='checkbox' name='am53' value='Normal' <?php if ($am53=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am53' value='Abnormal' <?php if ($am53=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am54" value="<?php $am54;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Lengan Atas : </label>
									<input type='checkbox' name='am55' value='Normal' <?php if ($am55=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am55' value='Abnormal' <?php if ($am55=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am56" value="<?php $am56;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Lengan Bawah : </label>
									<input type='checkbox' name='am57' value='Normal' <?php if ($am57=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am57' value='Abnormal' <?php if ($am57=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am58" value="<?php $am58;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Jari Tangan : </label>
									<input type='checkbox' name='am59' value='Normal' <?php if ($am59=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am59' value='Abnormal' <?php if ($am59=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am60" value="<?php $am60;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Kuku Tangan : </label>
									<input type='checkbox' name='am61' value='Normal' <?php if ($am61=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am61' value='Abnormal' <?php if ($am61=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am62" value="<?php $am62;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Persendian Tangan : </label>
									<input type='checkbox' name='am63' value='Normal' <?php if ($am63=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am63' value='Abnormal' <?php if ($am63=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am64" value="<?php $am64;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Tungkai Atas : </label>
									<input type='checkbox' name='am65' value='Normal' <?php if ($am65=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am65' value='Abnormal' <?php if ($am65=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am66" value="<?php $am66;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Tungkai Bawah : </label>
									<input type='checkbox' name='am67' value='Normal' <?php if ($am67=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am67' value='Abnormal' <?php if ($am67=="fisik49"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am68" value="<?php $am68;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Jari Kaki : </label>
									<input type='checkbox' name='am69' value='Normal' <?php if ($am69=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am69' value='Abnormal' <?php if ($am69=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am70" value="<?php $am70;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Kuku Kaki : </label>
									<input type='checkbox' name='am71' value='Normal' <?php if ($am71=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am71' value='Abnormal' <?php if ($am71=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am72" value="<?php $am72;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>	
									<label for="" class="col-3">Persendian Kaki : </label>
									<input type='checkbox' name='am73' value='Normal' <?php if ($am73=="Normal"){echo "checked";}?>>Normal
									<input type='checkbox' name='am73' value='Abnormal' <?php if ($am73=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am74" value="<?php $am74;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan">
									<br>

									<!-- <label for="" class="col-3">THT : </label>
									<input class="" name="fisik_tht" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_tht" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>										
									<label for="" class="col-3">Leher : </label>
									<input class="" name="fisik_leher" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_leher" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>
									<label for="" class="col-3">Paru : </label>
									<input class="" name="fisik_paru" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_paru" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>
									<label for="" class="col-3">Jantung : </label>
									<input class="" name="fisik_jantung" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_jantung" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>

									<label for="" class="col-3">Abomen : </label>
									<input class="" name="fisik_abdomen" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_abdomen" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>

									<label for="" class="col-3">Extrimitas : </label>
									<input class="" name="fisik_ekstermitas" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_ekstermitas" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>

									<label for="" class="col-3">Uro Gental : </label>
									<input class="" name="fisik_urogenital" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_urogenital" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br> -->

								</div>

							</div>
							<br>
							<div class="card">
								<div class="card-body">
									<label for="" class="col-3">Penunjang : </label>							
									<!-- <input class="form-control-sm" name="am76" value="<?php echo $am76;?>" id="dokter" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Pemeriksaan Peunjang"> -->
									<textarea class="form-control" name="am76" cols="100%" onfocus="nextfield ='';" style="min-height:30px;"><?php echo $am76;?></textarea>
									<br>
									<label for="" class="col-3">Rencana Terapi : </label>							
									<!-- <input class="form-control-sm" name="am77" value="<?php echo $am77;?>" id="dokter" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Rencana Terapi"> -->
									<textarea class="form-control" name="am77" cols="100%" onfocus="nextfield ='';" style="min-height:30px;"><?php echo $am77;?></textarea>
									<br>

								</div>
							</div>


						</div>

					</div>
				</div>				
			</div>
			<hr>

			<br><br><br>
		</form>
	</font>
</body>
</div>

<?php
if (isset($_POST["simpan"])) {

	// $tgl	= $_POST["tgl"];
	$am1 = $_POST["am1"];
	$am2 = $_POST['am2'];
	$am3 = $_POST['am3'];
	$am4 = $_POST['am4'];
	$am5 = $_POST['am5'];
	$am6 = $_POST['am6'];
	$am7 = $_POST['am7'];
	$am8 = $_POST['am8'];
	$am9 = $_POST['am9'];
	$am10 = $_POST['am10'];
	$am11 = $_POST['am11'];
	$am12 = $_POST['am12'];
	$am13 = $_POST['am13'];
	$am14 = $_POST['am14'];
	$am15 = $_POST['am15'];
	$am16 = $_POST['am16'];
	$am17 = $_POST['am17'];
	$am18 = $_POST['am18'];
	$am19 = $_POST['am19'];
	$am20 = $_POST['am20'];
	$am21 = $_POST['am21'];
	$am22 = $_POST['am22'];
	$am23 = $_POST['am23'];
	$am24 = $_POST['am24'];
	$am25 = $_POST['am25'];
	$am26 = $_POST['am26'];
	$am27 = $_POST['am27'];
	$am28 = $_POST['am28'];
	$am29 = $_POST['am29'];
	$am30 = $_POST['am30'];
	$am31 = $_POST['am31'];
	$am32 = $_POST['am32'];
	$am33 = $_POST['am33'];
	$am34 = $_POST['am34'];
	$am35 = $_POST['am35'];
	$am36 = $_POST['am36'];
	$am37 = $_POST['am37'];
	$am38 = $_POST['am38'];
	$am39 = $_POST['am39'];
	$am40 = $_POST['am40'];
	$am41 = $_POST['am41'];
	$am42 = $_POST['am42'];
	$am43 = $_POST['am43'];
	$am44 = $_POST['am44'];
	$am45 = $_POST['am45'];
	$am46 = $_POST['am46'];
	$am47 = $_POST['am47'];
	$am48 = $_POST['am48'];
	$am49 = $_POST['am49'];
	$am50 = $_POST['am50'];
	$am51 = $_POST['am51'];
	$am52 = $_POST['am52'];
	$am53 = $_POST['am53'];
	$am54 = $_POST['am54'];
	$am55 = $_POST['am55'];
	$am56 = $_POST['am56'];
	$am57 = $_POST['am57'];
	$am58 = $_POST['am58'];
	$am59 = $_POST['am59'];
	$am60 = $_POST['am60'];
	$am61 = $_POST['am61'];
	$am62 = $_POST['am62'];
	$am63 = $_POST['am63'];
	$am64 = $_POST['am64'];
	$am65 = $_POST['am65'];
	$am66 = $_POST['am66'];
	$am67 = $_POST['am67'];
	$am68 = $_POST['am68'];
	$am69 = $_POST['am69'];
	$am70 = $_POST['am70'];
	$am71 = $_POST['am71'];
	$am72 = $_POST['am72'];
	$am73 = $_POST['am73'];
	$am74 = $_POST['am74'];
	$am75 = $_POST['am75'];
	$am76 = $_POST['am76'];
	$am77 = $_POST['am77'];
	$am78 = $_POST['am78'];
	$am79 = $_POST['am79'];
	$am80 = $_POST['am80'];


	$q  = "update ERM_RI_ANAMNESIS_MEDIS set
	tgl='$tgl',
	am1 = '$am1',
	am2 = '$am2',
	am3 = '$am3',
	am4 = '$am4',
	am5 = '$am5',
	am6 = '$am6',
	am7 = '$am7',
	am8 = '$am8',
	am9 = '$am9',
	am10 = '$am10',
	am11 = '$am11',
	am12 = '$am12',
	am13 = '$am13',
	am14 = '$am14',
	am15 = '$am15',
	am16 = '$am16',
	am17 = '$am17',
	am18 = '$am18',
	am19 = '$am19',
	am20 = '$am20',
	am21 = '$am21',
	am22 = '$am22',
	am23 = '$am23',
	am24 = '$am24',
	am25 = '$am25',
	am26 = '$am26',
	am27 = '$am27',
	am28 = '$am28',
	am29 = '$am29',
	am30 = '$am30',
	am31 = '$am31',
	am32 = '$am32',
	am33 = '$am33',
	am34 = '$am34',
	am35 = '$am35',
	am36 = '$am36',
	am37 = '$am37',
	am38 = '$am38',
	am39 = '$am39',
	am40 = '$am40',
	am41 = '$am41',
	am42 = '$am42',
	am43 = '$am43',
	am44 = '$am44',
	am45 = '$am45',
	am46 = '$am46',
	am47 = '$am47',
	am48 = '$am48',
	am49 = '$am49',
	am50 = '$am50',
	am51 = '$am51',
	am52 = '$am52',
	am53 = '$am53',
	am54 = '$am54',
	am55 = '$am55',
	am56 = '$am56',
	am57 = '$am57',
	am58 = '$am58',
	am59 = '$am59',
	am60 = '$am60',
	am61 = '$am61',
	am62 = '$am62',
	am63 = '$am63',
	am64 = '$am64',
	am65 = '$am65',
	am66 = '$am66',
	am67 = '$am67',
	am68 = '$am68',
	am69 = '$am69',
	am70 = '$am70',
	am71 = '$am71',
	am72 = '$am72',
	am73 = '$am73',
	am74 = '$am74',
	am75 = '$am75',
	am76 = '$am76',
	am77 = '$am77',
	am78 = '$am78',
	am79 = '$am79',
	am80 = '$am80'
	where noreg='$noreg'
	";
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


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>
