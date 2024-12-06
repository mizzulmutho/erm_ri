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

$qu="SELECT *,CONVERT(VARCHAR, tanggal, 103) as tanggal
FROM ERM_SOAP where id='$idsoap'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 

$norm = $d1u['norm'];
$noreg = $d1u['noreg'];
$tanggal = $d1u['tanggal']; 
$sbu = $d1u['sbu'];
$kodeunit = $d1u['kodeunit'];
$kodedokter = $d1u['kodedokter']; 
$subjektif = $d1u['subjektif']; 
$objektif = $d1u['objektif'];
$assesment = $d1u['assesment']; 
$planning = $d1u['planning']; 
$userid = $d1u['userid'];
$instruksi = $d1u['instruksi']; 
$dpjp = $d1u['dpjp'];

$qg="SELECT antropometri,biokimia, fisik_klinis, asupan_makan, diagnosa_gizi, intervensi, monitoring
FROM ERM_RI_SOAP where id_soap='$idsoap'";
$h1g  = sqlsrv_query($conn, $qg);        
$d1g  = sqlsrv_fetch_array($h1g, SQLSRV_FETCH_ASSOC); 

$antropometri = $d1g['antropometri'];
$biokimia = $d1g['biokimia'];
$fisik_klinis = $d1g['fisik_klinis'];
$asupan_makan = $d1g['asupan_makan'];
$diagnosa_gizi = $d1g['diagnosa_gizi'];
$intervensi = $d1g['intervensi'];
$monitoring = $d1g['monitoring'];

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
		
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
			&nbsp;&nbsp;
			<a href='soap_dokter.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
			&nbsp;&nbsp;
			<a href='soap_dokter_list.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-info-circle"></i> Edit</a>
			&nbsp;&nbsp;
			<a href='r_soap_dokter.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info' target='_blank'><i class="bi bi-list-ol"></i> History</a>
			<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
			&nbsp;&nbsp;
			<br><br>

			<div class="row">

				<div class="col-12">
					<?php 
					include "header_soap.php";
					?>
				</div>

			</div>

			<div class="row">
				<div class="col-12 text-left">
					&nbsp;<b>Assesment Awal Medik</b><br>
					<table width="100%">
						<tr>
							<td width="50%">
								<table width="100%">
									<tr>
										<td>Diagnosa</td>
										<td>: <?php echo $diagnosa;?></td>
									</tr>
									<tr>
										<td>Keluhan Utama</td>
										<td>: <?php echo $am1;?></td>
									</tr>
									<tr>
										<td>Nama Penyakit</td>
										<td>: <?php echo $am2;?></td>
									</tr>
									<tr>
										<td>Penunjang</td>
										<td>: <?php echo $am76;?></td>
									</tr>

								</table>
							</td>
							<td>
								<table width="100%">
									<tr>
										<td>Rencana Terapi</td>
										<td>
											<textarea class="form-control" name="am77" cols="100%" onfocus="nextfield ='';" style="min-height:100px;" disabled><?php echo $am77;?></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</div>


			<div class="row">
				<div class="col-12 text-center">
					<b>INPUT SOAP </b><br>
				</div>
			</div>

			<hr>

<!-- 				<div class="row">
					<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
					<div class="col-6"><?php echo 'L/P : '.$kelamin.'<br> UMUR : '.$umur.'<br> ALAMAT : '.$alamatpasien; ?></div>
				</div>
			-->

			<font size='4px'>	
				<div class="row">
					<div class="col-6">
						&nbsp;&nbsp;&nbsp;<button type="submit" name="kosongkan_isian" class="btn btn-warning btn-sm" onfocus="nextfield ='done';">Kosongkan isian</button>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-6">
						<div class="col-12">Subjektif<br>
							<textarea name= "subjektif" id="" style="min-width:650px; min-height:150px;" required><?php echo $subjektif;?></textarea>
						</div>
						<div class="col-6">Assesment<br>
							<textarea name= "assesment" id="" style="min-width:650px; min-height:150px;"><?php echo $assesment;?></textarea>
						</div>
						<div class="col-6">Planning -  
							<!-- <a href='eresep_list_dokter.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-in-right"></i> Ambil data e-Resep</a> -->

							<button type="submit" name="ambil_eresep" class="btn btn-info btn-sm" onfocus="nextfield ='done';">Ambil data e-Resep</button> 

							<br><br>
							<textarea name= "planning" id="" style="min-width:650px; min-height:250px;"><?php echo $planning;?></textarea>
						</div>
						<div class="col-6">Instruksi<br>
							<textarea name= "instruksi" id="" style="min-width:650px; min-height:150px;"><?php echo $instruksi;?></textarea>
						</div>	
					</div>
					<div class="col-6">
						<div class="col-12">
							<div class="row">
								<div class="col-12">
									Objektif<br>
									<textarea name= "objektif" id="" style="min-width:650px; min-height:150px;"><?php echo $objektif;?></textarea>
<!-- 									<hr>
									<textarea name= "ket_object" id="" style="min-width:650px; min-height:50px;"><?php echo $ket_object;?></textarea>
								-->								
							</div>
						</div>
						<div class="col-12">
							<div class="row">
								<div class="col-12">							
									Glassow Comma Scale (GCS)
								</div>
								<div class="col-12">
									Eye &nbsp;&nbsp;: 
									<input class="form-control-sm" name="eye" value="<?php echo $eye;?>" id="" type="text" size='5' onfocus="nextfield ='verbal';" placeholder="" style="min-height:50px;">
									Verbal : 
									<input class="form-control-sm" name="verbal" value="<?php echo $verbal;?>" id="" type="text" size='5' onfocus="nextfield ='movement';" placeholder="" style="min-height:50px;">
									Movement : 
									<input class="form-control-sm" name="movement" value="<?php echo $movement;?>" id="" type="text" size='5' onfocus="nextfield ='tekanan_darah';" placeholder="" style="min-height:50px;">
								</div>
								<br>
								<div class="col-12">
									Vital Sign
								</div>
								<div class="col-12">
									Tensi : 
									<input class="form-control-sm" name="tekanan_darah" value="<?php echo $tekanan_darah;?>" id="" type="text" size='5' onfocus="nextfield ='nadi';" placeholder="" style="min-width:50px; min-height:50px;">mmHg
								</div> 
								<br><br>
								<div class="col-12">
									Nadi : 
									<input class="form-control-sm" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='5' onfocus="nextfield ='suhu';" placeholder="" style="min-width:50px; min-height:50px;">x/menit 
									<input class="" name="ket_nadi" value="teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" <?php if ($ket_nadi=="teratur"){echo "checked";}?>>Teratur
									<input class="" name="ket_nadi" value="tidak teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" <?php if ($ket_nadi=="tidak teratur"){echo "checked";}?>>Tidak Teratur
								</div>
								<br><br>
								<div class="col-12">
									Suhu : 
									<input class="form-control-sm" name="suhu" value="<?php echo $suhu;?>" id="" type="text" size='5' onfocus="nextfield ='frekuansi_pernafasan';" placeholder="" style="min-width:50px; min-height:50px;">C
									-
									Frekuensi Pernafasan : 
									<input class="form-control-sm" name="frekuansi_pernafasan" value="<?php echo $frekuansi_pernafasan;?>" id="" type="text" size='5' onfocus="nextfield ='skala_nyeri';" placeholder="" style="min-width:50px; min-height:50px;">x/menit
									-
									Skala Nyeri : 
									<input class="form-control-sm" name="skala_nyeri" value="<?php echo $skala_nyeri;?>" id="" type="text" size='3' onfocus="nextfield ='berat_badan';" placeholder="" style="min-width:50px; min-height:50px;">						
								</div>
								<br><br>
								<div class="col-12">
									Berat : 
									<input class="form-control-sm" name="berat_badan" value="<?php echo $berat_badan;?>" id="" type="text" size='5' onfocus="nextfield ='status_lokalis';" placeholder="" style="min-width:50px; min-height:50px;">Kg	
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-12">
								Laborat :
								<button type="submit" name="ambil_lab" class="btn btn-info btn-sm" onfocus="nextfield ='done';">Ambil data hasil Laborat</button> 
								<br>
								<textarea name= "plab" id="" style="min-width:650px; min-height:150px;"><?php echo $plab;?></textarea>
								<br>
								Radiologi :
								<button type="submit" name="ambil_rad" class="btn btn-info btn-sm" onfocus="nextfield ='done';">Ambil data hasil Radiologi</button> 
								<br>
								<textarea name= "prad" id="" style="min-width:650px; min-height:150px;"><?php echo $prad;?></textarea>

							</div>
						</div>



					</div>
				</div>

				<div class="col-12 align-self-center">
					<center>
						<br>
						<br>
						<button type='submit' name='simpan' value='simpan' class="btn btn-success" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
					</center>
				</div>


<!-- 				<div class="row">
					<div class="col-6">Penunjang<br>
						<textarea name= "penunjang" id="" style="min-width:650px; min-height:50px;"><?php echo $penunjang;?></textarea>
					</div>
				</div>
			-->				

		</div>
	</div>
</div>				
</div>

<br><br><br>
</form>
</font>
</body>
</div>

<?php



if (isset($_POST["simpan"])) {

	// $subjektif','$objektif','$assesment','$planning'

	$subjektif	= trim($_POST["subjektif"]);
	$objektif	= trim($_POST["objektif"]);
	$assesment	= trim($_POST["assesment"]);
	$planning	= trim($_POST["planning"]);
	$instruksi	= trim($_POST["instruksi"]);
	$plab	= trim($_POST["plab"]);
	$prad	= trim($_POST["prad"]);

	$penunjang	= 
	"Laborat : ".$plab.", ".
	"Radiologi : ".$prad;

	$assesmen	= trim($_POST["assesmen"]);

	$eye	= trim($_POST["eye"]);
	$verbal	= trim($_POST["verbal"]);
	$movement	= trim($_POST["movement"]);
	$tekanan_darah	= trim($_POST["tekanan_darah"]);
	$nadi	= trim($_POST["nadi"]);
	$ket_nadi	= trim($_POST["ket_nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$frekuansi_pernafasan	= trim($_POST["frekuansi_pernafasan"]);
	$skala_nyeri	= trim($_POST["skala_nyeri"]);
	$berat_badan	= trim($_POST["berat_badan"]);

	$objektif=$objektif.
	" GCS - Eye : ".$eye.", Verbal : ".$verbal.", Movement : ".$movement.",".
	"Tensi : ".$tekanan_darah.", Nadi : ".$nadi.", Suhu : ".$suhu.", Frekuensi Pernafasan : ".$frekuansi_pernafasan.", Skala Nyeri : ".$skala_nyeri.", Berat Badan : ".$berat_badan.", Penunjang : ".$penunjang;


	//subyek
	$dsubjektif = $subjektif;

	//assemen
	$dassesment = $assesment;

	//plaing
	$dplanning = $planning;

	//object
	$dobjektif= $objektif;

	// $tglinput	= trim($_POST["tglinput"]);
	$userinput	= trim($_POST["userinput"]);

	$lanjut="Y";

	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);
	$kodedokter  = substr($user,0,3);
	$pass = trim($_POST["pass"]);
	$instruksi = trim($_POST["instruksi"]);

	$lanjut="Y";


	// if(empty($pass)){
	// 	$eror='Password Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	// if(empty($kodedokter)){
	// 	$eror='Kodedokter Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	// if(empty($dpjp)){
	// 	$eror='DPJP Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	if($lanjut == 'Y'){
		$q  = "update ERM_SOAP set subjektif='$subjektif',objektif='$objektif',assesment='$assesment',planning='$planning',instruksi='$instruksi' where id='$idsoap'";
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

