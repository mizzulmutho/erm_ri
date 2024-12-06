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
$aresep = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2       = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan,NOKTP,NOBPJS, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);                

$data2    = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);                      
$kodedept = $data2[kodedept];

$nama     = $data2[nama];
$kelamin  = $data2[kelamin];
$nik = trim($data2[nik]);
$alamatpasien  = $data2[alamatpasien];
$kota     = $data2[kota];
$kodekel  = $data2[kodekel];
$telp     = $data2[tlp];
$tmptlahir     = $data2[tmptlahir];
$tgllahir = $data2[tgllahir];
$jenispekerjaan     = $data2[jenispekerjaan];
$jabatan  = $data2[jabatan];
$umur =  $data2[UMUR];
$noktp =  $data2[NOKTP];
$nobpjs =  $data2[NOBPJS];

$row = explode(',',$umur);
$tahun_u  = $row[0];
$tahun_u = intval(substr($tahun_u, 0,3));

//$tahun_u = 61;

$bulan_u = $row[1]; 
$bulan_u = intval(substr($bulan_u, 0,3));

$hari_u = $row[2]; 
$hari_u = intval(substr($hari_u, 0,3));


$qu="
SELECT        TOP (200) NORM,  ALERGI
FROM            Y_ALERGI 
where norm='$norm'

union 
SELECT        ARM_REGISTER.NORM, V_ERM_RI_KEADAAN_UMUM.alergi as ALERGI
FROM            V_ERM_RI_KEADAAN_UMUM INNER JOIN
ARM_REGISTER ON V_ERM_RI_KEADAAN_UMUM.noreg = ARM_REGISTER.NOREG
where ARM_REGISTER.NORM='$norm' and V_ERM_RI_KEADAAN_UMUM.alergi <> '' 

union
SELECT        ARM_REGISTER.NORM, ERM_RI_ALERGI.obat as ALERGI
FROM            ERM_RI_ALERGI INNER JOIN
ARM_REGISTER ON ERM_RI_ALERGI.noreg = ARM_REGISTER.NOREG

";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

$qu2="SELECT ket as notif  FROM ERM_NOTIF where noreg='$noreg'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$notif = $d1u2['notif'];

$qu3="SELECT diet as diet  FROM ERM_DIET where noreg='$noreg'";
$h1u3  = sqlsrv_query($conn, $qu3);        
$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
$diet = $d1u3['diet'];



$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];

$noreg = trim($noreg);

//cek kodeperawat
$qu="SELECT top(1)kodedokter FROM ERM_SOAP where userid='$user' order by id desc";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$kodedokter = $d1u['kodedokter'];
$kodedokter = trim($kodedokter);


$qu="SELECT dpjp FROM  V_ERM_RI_DPJP_ASESMEN where noreg='$noreg' and dpjp is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$dpjp = $d1u['dpjp'];

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
$bb = $d1u['bb'];
$tb = $d1u['tb'];

//ambil resep
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

	<script>
		$(function() {
			$("#diag_keperawatan1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan4").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
				<a href='list_soap.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
				<!-- &nbsp;&nbsp; -->				
				<a href='r_soap.php?id=<?php echo $id.'|'.$user.'|transfer';?>' class='btn btn-info btn-sm'><i class="bi bi-printer-fill"></i></a>
				&nbsp;&nbsp;
				<a href='soap_bidan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info btn-sm'><i class="bi bi-info-circle"></i> CPPT Bidan</a>
				&nbsp;&nbsp;
				<a href='soap_gizi.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info btn-sm'><i class="bi bi-info-circle"></i> CPPT Gizi</a>
				&nbsp;&nbsp;
				<a href='soap_apoteker.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info btn-sm'><i class="bi bi-info-circle"></i> CPPT Apoteker</a>
				&nbsp;&nbsp;

				<br><br>
				<div class="row">

					<div class="col-12">
						<table class="table table-bordered">
							<tr>
								<td>Nama</td><td>: <?php echo $nama;?></td><td>No. RM</td><td>: <?php echo $norm;?></td>
							</tr>
							<tr>
								<td>Tanggal Lahir</td><td>: <?php echo $tgllahir;?></td><td>NIK</td><td>: <?php echo $noktp;?></td>
							</tr>
							<tr>
								<td>Umur</td><td>: <?php echo $umur;?></td><td>Jenis Kelamin</td><td>: <?php echo $kelamin;?></td>
							</tr>
							<tr>
								<td>Riwayat Alergi</td><td>: <?php echo $alergi;?></td><td>Diet</td><td>: <?php echo $diet;?></td>
							</tr>
						</table>
					</div>

				</div>


				<div class="row">
					<div class="col-12 text-center">
						<b>INPUT SOAP </b><br>
					</div>					
				</div>
				<br><br>

				<div class="row">
					<div class="col-6">
						<input class="form-control" name="tglinput" value="<?php echo $tglinput;?>" type="text" >
					</div>
					<div class="col-6">
						<input class="form-control" name="kodedokter" value="" id="dokter" type="text" size='50' onfocus="nextfield ='pass';" placeholder="Isikan Perawat yang Entry SOAP" required>
					</div>

				</div>
				<div class="row">
					<div class="col-6">
						<b><font size='5' color='green'>(S)</font>ubject</b>
						<br>
						<label for="" class="">Keluhan Sekarang : </label>
						<input class="form-control form-control-sm" name="ku" value="<?php echo $ku;?>" id="" type="text" size='' onfocus="nextfield ='rps';" placeholder="">
						<?php 

						$ql="
						SELECT        distinct ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan, ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_nama
						FROM            ERM_ASUHAN_KEPERAWATAN INNER JOIN
						ERM_MASTER_ASUHANKEPERAWATAN ON ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan = ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_keperawatan
						where noreg='$noreg'
						";
						$hl  = sqlsrv_query($conn, $ql);
						$no=1;
						while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){         

							if($no==1){
								$assesmen=$dl[diagnosa_keperawatan].'-'.$dl[diagnosa_nama];
							}
							if($no==2){
								$assesmen2=$dl[diagnosa_keperawatan].'-'.$dl[diagnosa_nama];
							}
							if($no==3){
								$assesmen3=$dl[diagnosa_keperawatan].'-'.$dl[diagnosa_nama];
							}
							if($no==4){
								$assesmen4=$dl[diagnosa_keperawatan].'-'.$dl[diagnosa_nama];
							}
							if($no==5){
								$assesmen5=$dl[diagnosa_keperawatan].'-'.$dl[diagnosa_nama];
							}

							$no += 1;
						}
						?>
						<br>
						<b><font size='5' color='green'>(A)</font>ssesmen</b><br>
						<!-- <input class="form-control form-control-sm" name="assesmen" value="<?php echo $assesmen;?>" id="" type="text" size='' onfocus="nextfield ='aplan';" placeholder="input diagnosa"> -->
						<input class="form-control form-control-sm" name="assesmen" value="<?php echo $assesmen;?>" id="diag_keperawatan1" type="text" size='' onfocus="nextfield ='aplan';" placeholder="isi kode / nama diagnosa keperawatan">
						<input class="form-control form-control-sm" name="assesmen2" value="<?php echo $assesmen2;?>" id="diag_keperawatan2" type="text" size='' onfocus="nextfield ='aplan';" placeholder="isi kode / nama diagnosa keperawatan">
						<input class="form-control form-control-sm" name="assesmen3" value="<?php echo $assesmen3;?>" id="diag_keperawatan3" type="text" size='' onfocus="nextfield ='aplan';" placeholder="isi kode / nama diagnosa keperawatan">
						<input class="form-control form-control-sm" name="assesmen4" value="<?php echo $assesmen4;?>" id="diag_keperawatan4" type="text" size='' onfocus="nextfield ='aplan';" placeholder="isi kode / nama diagnosa keperawatan">
						<input class="form-control form-control-sm" name="assesmen5" value="<?php echo $assesmen5;?>" id="diag_keperawatan5" type="text" size='' onfocus="nextfield ='aplan';" placeholder="isi kode / nama diagnosa keperawatan">

						<hr>
						<b><font size='5' color='green'>(P)</font>lan</b></br>
						<?php 
						$qus="SELECT distinct implementasi FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' ";
						$h1us  = sqlsrv_query($conn, $qus);
						$noe=1;        
						while   ($d1us = sqlsrv_fetch_array($h1us, SQLSRV_FETCH_ASSOC)){
							if($noe==1){
								$aplan = $d1us[implementasi]."\n";
							}else{
								$aplan = $aplan.$d1us[implementasi]."\n";
							}
							$noe=$noe+1;

						}

						?>
						<textarea name= "aplan" id="" style="min-width:600px; min-height:200px;"><?php echo $aplan;?></textarea>
						<hr>

						<i>Instruksi </i>
						<textarea class="form-control" name="instruksi" style="min-width:600px; min-height:200px;" onfocus="nextfield ='';"><?php echo $instruksi;?></textarea>


<!-- 					<i>Penunjang </i></br>			
					<textarea class="form-control" name="penunjang" cols="100%" onfocus="nextfield ='Instruksi';">
						<?php echo $penunjang;?>
					</textarea>																
				-->					
				
<!-- 				<input class="form-control" name="pass" value="<?php echo $pass;?>" id="" type="text" size='50' onfocus="nextfield ='subjektif';" placeholder="Masukkan Lagi Password Sirs!">
-->			

</div>


<div class="col-6">
	<b><font size='5' color='green'>(O)</font>bject</b>
	<br>
	<label for="" class="">Keterangan Object Lainnya : </label>
	<input class="form-control form-control-sm" name="ket_object" value="<?php echo $ket_object;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
	<br>

	<div class="card">
		<div class="card-header">
			Glassow Comma Scale (GCS)
		</div>
		<div class="card-body">
			<label for="" class="col-3">Eye : </label>
			<input class="form-control-sm" name="eye" value="<?php echo $e;?>" id="" type="text" size='' onfocus="nextfield ='verbal';" placeholder="">
			<br>
			<label for="" class="col-3">Verbal : </label>
			<input class="form-control-sm" name="verbal" value="<?php echo $v;?>" id="" type="text" size='' onfocus="nextfield ='movement';" placeholder="">
			<br>
			<label for="" class="col-3">Movement : </label>
			<input class="form-control-sm" name="movement" value="<?php echo $m;?>" id="" type="text" size='' onfocus="nextfield ='tekanan_darah';" placeholder="">
			<br>
			<label for="" class="col-3">Total GCS : </label>
			<input class="form-control-sm" name="total_gcs" value="<?php echo $total_gcs;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">

		</div>
	</div>
	<br>
	<div class="card">
		<div class="card-header">
			Vital Sign
		</div>
		<div class="card-body">
			
			<br>
			<label for="" class="col-3">Tekanan Darah : </label>
			<input class="form-control-sm" name="tekanan_darah" value="<?php echo $tensi;?>" id="" type="text" size='' onfocus="nextfield ='nadi';" placeholder="">mmHg<br>
			<label for="" class="col-3">Nadi : </label>
			<input class="form-control-sm" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='' onfocus="nextfield ='suhu';" placeholder="">x/menit<br>
			<label for="" class="col-3">&nbsp;</label>
			<input class="" name="ket_nadi" value="teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Teratur
			<input class="" name="ket_nadi" value="tidak teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Tidak Teratur
			<br>
			<label for="" class="col-3">Suhu : </label>
			<input class="form-control-sm" name="suhu" value="<?php echo $suhu;?>" id="" type="text" size='' onfocus="nextfield ='frekuansi_pernafasan';" placeholder="">C<br>								
			<label for="" class="col-3">Frekuensi Pernafasan : </label>
			<input class="form-control-sm" name="frekuansi_pernafasan" value="<?php echo $nafas;?>" id="" type="text" size='' onfocus="nextfield ='skala_nyeri';" placeholder="">x/menit<br>
			<label for="" class="col-3">Skala Nyeri : </label>
			<input class="form-control-sm" name="skala_nyeri" value="<?php echo $skala_nyeri;?>" id="" type="text" size='' onfocus="nextfield ='berat_badan';" placeholder=""><br>
			<label for="" class="col-3">Berat Badan : </label>
			<input class="form-control-sm" name="berat_badan" value="<?php echo $bb;?>" id="" type="text" size='' onfocus="nextfield ='status_lokalis';" placeholder="">Kg<br>
			<div class="card">
				<div class="card-header">
					Pemeriksaan Fisik
				</div>
<!-- 								<div class="card-body">
									<label for="" class="col-3">Kepala : </label>
									<input class="" name="fisik_kepala" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_kepala" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>
									<label for="" class="col-3">Mata : </label>
									<input class="" name="fisik_mata" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Normal
									<input class="" name="fisik_mata" value="normal" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Abnormal
									<br>										
									<label for="" class="col-3">THT : </label>
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
									<br>

								</div> -->

								<label for="" class="col-6">Status Lokalis : </label>
								<input class="form-control-sm" name="status_lokalis" value="<?php echo $status_lokalis;?>" id="" type="text" size='' onfocus="nextfield ='pemeriksaan_penunjang';" placeholder=""><br>															
								<label for="" class="col-6">Pemeriksaan Penunjang : </label>
								<input class="form-control-sm" name="pemeriksaan_penunjang" value="<?php echo $pemeriksaan_penunjang;?>" id="" type="text" size='' onfocus="nextfield ='simpan';" placeholder=""><br>											

							</div>

						</div>
					</div>
				</div>				
			</div>

			<div class="row">
				<div class="col-12">

					<i>DPJP</i></br>
					<input class="form-control" name="dpjp" value="<?php echo $dpjp;?>" id="dpjp" type="text" size='50' onfocus="nextfield ='simpan';" placeholder="Isikan Nama Dokter DPJP">
				</div>
			</div>
			<br><br>
			<div class="row">
				<div class="col-12 align-self-center">
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'>Batal</a>
					&nbsp;&nbsp;&nbsp;
					<button type="submit" name="simpan" class="btn btn-danger btn-sm" onfocus="nextfield ='done';">simpan</button> 
				</div>
			</div>
			<br><br><br>
		</form>
	</font>
</body>
</div>

<?php

if (isset($_POST["simpan"])) {


	$ket_object	= trim($_POST["ket_object"]);
	$ku	= trim($_POST["ku"]);
	$rps	= trim($_POST["rps"]);
	$anamnesa	= trim($_POST["anamnesa"]);
	$rpd	= trim($_POST["rpd"]);
	$alergi	= trim($_POST["alergi"]);
	$assesmen	= trim($_POST["assesmen"]);
	$assesmen2	= trim($_POST["assesmen2"]);
	if($assesmen2){
		$assesmen=$assesmen.','.$assesmen2;
	}
	$assesmen3	= trim($_POST["assesmen3"]);
	if($assesmen3){
		$assesmen=$assesmen.','.$assesmen3;
	}
	$assesmen4	= trim($_POST["assesmen4"]);
	if($assesmen4){
		$assesmen=$assesmen.','.$assesmen4;
	}
	$assesmen5	= trim($_POST["assesmen5"]);
	if($assesmen5){
		$assesmen=$assesmen.','.$assesmen5;
	}

	$aplan	= trim($_POST["aplan"]);
	$eye	= trim($_POST["eye"]);
	$verbal	= trim($_POST["verbal"]);
	$movement	= trim($_POST["movement"]);
	$total_gcs = trim($_POST["total_gcs"]);
	$tekanan_darah	= trim($_POST["tekanan_darah"]);
	$nadi	= trim($_POST["nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$frekuansi_pernafasan	= trim($_POST["frekuansi_pernafasan"]);
	$skala_nyeri	= trim($_POST["skala_nyeri"]);
	$berat_badan	= trim($_POST["berat_badan"]);
	$fisik_kepala	= trim($_POST["fisik_kepala"]);
	$fisik_mata	= trim($_POST["fisik_mata"]);
	$fisik_tht	= trim($_POST["fisik_tht"]);
	$fisik_leher	= trim($_POST["fisik_leher"]);
	$fisik_paru	= trim($_POST["fisik_paru"]);
	$fisik_jantung	= trim($_POST["fisik_jantung"]);
	$fisik_abdomen	= trim($_POST["fisik_abdomen"]);
	$fisik_ekstermitas	= trim($_POST["fisik_ekstermitas"]);
	$fisik_urogenital	= trim($_POST["fisik_urogenital"]);
	$status_lokalis	= trim($_POST["status_lokalis"]);
	$pemeriksaan_penunjang	= trim($_POST["pemeriksaan_penunjang"]);
	$ket_nadi	= trim($_POST["ket_nadi"]);

	//subyek
	// $dsubjektif = 
	// "Kondisi Umum : ".$ku.", Riwayat Pasien Sekarang : ".$rps.", Kondisi Kejiwaan : ".$anamnesa.", Data Riwayat Pasien Dahulu : ".$rpd.", Alergi : ".$alergi;

	$dsubjektif = 
	"Keluhan : ".$ku ;

	//assemen
	$dassesment = $assesmen;

	//plaing
	$dplanning = $aplan;

	//object
	$dobjektif=
	"GCS - Eye : ".$eye.", Verbal : ".$verbal.", Movement : ".$movement.", Total GCS : ".$total_gcs.
	"Tensi : ".$tekanan_darah.", Nadi : ".$nadi.", Suhu : ".$suhu.", Frekuensi Pernafasan : ".$frekuansi_pernafasan.", Skala Nyeri : ".$skala_nyeri.", Berat Badan : ".$berat_badan.
	"<br>Status Lokalis : ".$status_lokalis.", Pemeriksaan Penunjang : ".$pemeriksaan_penunjang;

	// $tglinput	= trim($_POST["tglinput"]);
	$userinput	= trim($_POST["userinput"]);

	$lanjut="Y";


	if(empty($user)){
		$eror='User Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($tekanan_darah)){
		$eror='tekanan_darah Tidak Boleh Kosong !!!';
		$lanjut='T';
	}


//--
	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);

	$pass = trim($_POST["pass"]);
	// $subjektif = trim($_POST["subjektif"]);
	// $objektif = trim($_POST["objektif"]);
	// $assesment = trim($_POST["assesment"]);
	// $planning = trim($_POST["planning"]);
	$instruksi = trim($_POST["instruksi"]);

	$subjektif = $dsubjektif;
	$objektif = $dobjektif;
	if($ket_object){
		$objektif=$objektif.$ket_object;
	}
	$assesment = $dassesment;
	$planning = $dplanning;
	$instruksi = trim($_POST["instruksi"]);


	$dpjp = trim($_POST["dpjp"]);
	$row2 = explode('-',$dpjp);
	$dpjp  = trim($row2[0]);


	$lanjut="Y";


	// if(empty($pass)){
	// 	$eror='Password Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	if(empty($kodedokter)){
		$eror='Kodedokter Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($dpjp)){
		$eror='DPJP Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	//cek user
	$query = "select * from ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$user'";		
	$result = sqlsrv_query($conn, $query);
	$data  = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);          

    // cek pass
	// if (trim($pass)<>strtolower(trim($data['PASS2']))) {	
	// 	if (trim($pass)<>strtoupper(trim($data['PASS2']))) {	
	// 		$eror='Password Salah !!!';
	// 		$lanjut = 'T';
	// 	}
	// }

	if($lanjut == 'Y'){
		$q  = "insert into ERM_SOAP
		(norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry,instruksi,dpjp) 
		values 
		('$norm','$noreg','$tglinput','$sbu','$kodeunit','$kodedokter','$subjektif','$objektif','$assesment','$planning','$user','$tglinput','$instruksi','$dpjp')";
		$hs1 = sqlsrv_query($conn,$q);

		if($hs1){
			$qu="SELECT top(1)id FROM ERM_SOAP where noreg='$noreg' order by id desc";
			$h1u  = sqlsrv_query($conn, $qu);        
			$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
			$idsoap = $d1u['id'];

			$q  = "insert into ERM_RI_SOAP
			(
			norm,noreg,
			tglupdate,
			userid,
			ku,
			rps,
			anamnesa,
			rpd,
			alergi,
			assesmen,
			aplan,
			eye,
			verbal,
			movement,
			tekanan_darah,
			nadi,
			suhu,
			frekuansi_pernafasan,
			skala_nyeri,
			berat_badan,
			fisik_kepala,
			fisik_mata,
			fisik_tht,
			fisik_leher,
			fisik_paru,
			fisik_jantung,
			fisik_abdomen,
			fisik_ekstermitas,
			fisik_urogenital,
			status_lokalis,
			pemeriksaan_penunjang,
			ket_nadi,id_soap
			) 
			values 
			(
			'$norm','$noreg',
			'$tglinput',
			'$user',
			'$ku',
			'$rps',
			'$anamnesa',
			'$rpd',
			'$alergi',
			'$assesmen',
			'$aplan',
			'$eye',
			'$verbal',
			'$movement',
			'$tekanan_darah',
			'$nadi',
			'$suhu',
			'$frekuansi_pernafasan',
			'$skala_nyeri',
			'$berat_badan',
			'$fisik_kepala',
			'$fisik_mata',
			'$fisik_tht',
			'$fisik_leher',
			'$fisik_paru',
			'$fisik_jantung',
			'$fisik_abdomen',
			'$fisik_ekstermitas',
			'$fisik_urogenital',
			'$status_lokalis',
			'$pemeriksaan_penunjang',
			'$ket_nadi','$idsoap'
		)";
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

		$eror = "Gagal insert SOAP !!!!";

		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";


	}
		// echo "
		// <script>
		// top.location='index.php?id='$id|$user';
		// </script>
		// ";            

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

