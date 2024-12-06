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
						<b>INPUT SOAP </b><br>
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
					<b><font size='5' color='green'>(S)</font>ubject</b>
					<br>
					<label for="" class="">Kondisi Umum : </label>
					<input class="form-control form-control-sm" name="ku" value="<?php echo $ku;?>" id="" type="text" size='' onfocus="nextfield ='rps';" placeholder="">
					<label for="" class="">Riwayat Pasien Sekarang : </label>
					<input class="form-control form-control-sm" name="rps" value="<?php echo $rps;?>" id="" type="text" size='' onfocus="nextfield ='rpd';" placeholder="">
					<br>
					<div class="card">
						<div class="card-header">
							Anamnesa Psikologi/Sosial/Ekonomi : 
						</div>
						<div class="card-body">
							<label for="" class="">Kondisi Kejiwaan : </label>												
							<!-- 								<input class="form-control form-control-sm" name="anamnesa" value="<?php echo $anamnesa;?>" id="" type="text" size='' onfocus="nextfield ='rpd';" placeholder="Isikan Tenang/Gelisah/Takut/Bingung/Stres">  -->
							<input class="" name="anamnesa" value="Tenang" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Tenang
							<input class="" name="anamnesa" value="Gelisah" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Gelisah/Takut
							<input class="" name="anamnesa" value="Bingung" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Bingung
							<input class="" name="anamnesa" value="Stres" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Stres
						</div>
					</div>

					<div class="card">
						<div class="card-header">
							Data Riwayat Pasien Dahulu :
						</div>
						<div class="card-body">
							<input class="form-control form-control-sm" name="rpd" value="<?php echo $rpd;?>" id="" type="text" size='' onfocus="nextfield ='alergi';" placeholder="">
						</div>
					</div>

					<div class="card">
						<div class="card-header">
							Data Alergi :
						</div>
						<div class="card-body">
							<input class="form-control form-control-sm" name="alergi" value="<?php echo $alergi;?>" id="" type="text" size='' onfocus="nextfield ='assesmen';" placeholder="">
						</div>
					</div>

					<br>
					<b><font size='5' color='green'>(A)</font>ssesmen</b><br>
					<!-- <input class="form-control form-control-sm" name="assesmen" value="<?php echo $assesmen;?>" id="" type="text" size='' onfocus="nextfield ='aplan';" placeholder="input diagnosa"> -->
					<textarea name= "assesmen" id="" style="min-width:650px; min-height:50px;"><?php echo $assesmen;?></textarea>
					<hr>
					<b><font size='5' color='green'>(P)</font>lan</b></br>
					<!-- <input class="form-control form-control-sm" name="aplan" value="<?php echo $aplan;?>" id="" type="text" size='' onfocus="nextfield ='eye';" placeholder="input plan"> -->
					<!-- <textarea class="form-control" name="aplan" cols="100%" onfocus="nextfield ='';"><?php echo $aplan;?></textarea> -->
					<textarea name= "aplan" id="" style="min-width:650px; min-height:200px;"><?php echo $aplan;?></textarea>
					<hr>


					<div class="row">
						<div class="col-12 align-self-center">
							&nbsp;&nbsp;&nbsp;
							<button type="submit" name="simpan" class="btn btn-danger btn-sm" onfocus="nextfield ='done';">simpan</button> 
						</div>
					</div>

				</div>
				<div class="col-6">
					<b><font size='5' color='green'>(O)</font>bject</b>
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
									<input class="form-control-sm" name="eye" value="<?php echo $eye;?>" id="" type="text" size='' onfocus="nextfield ='verbal';" placeholder="">
									<br>
									<label for="" class="col-3">Verbal : </label>
									<input class="form-control-sm" name="verbal" value="<?php echo $verbal;?>" id="" type="text" size='' onfocus="nextfield ='movement';" placeholder="">
									<br>
									<label for="" class="col-3">Movement : </label>
									<input class="form-control-sm" name="movement" value="<?php echo $movement;?>" id="" type="text" size='' onfocus="nextfield ='tekanan_darah';" placeholder="">
								</div>
							</div>
							<br>
							<label for="" class="col-3">Tekanan Darah : </label>
							<input class="form-control-sm" name="tekanan_darah" value="<?php echo $tekanan_darah;?>" id="" type="text" size='' onfocus="nextfield ='nadi';" placeholder="">mmHg<br>
							<label for="" class="col-3">Nadi : </label>
							<input class="form-control-sm" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='' onfocus="nextfield ='suhu';" placeholder="">x/menit<br>
							<label for="" class="col-3">&nbsp;</label>
							<input class="" name="ket_nadi" value="teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" checked>Teratur
							<input class="" name="ket_nadi" value="tidak teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="">Tidak Teratur
							<br>
							<label for="" class="col-3">Suhu : </label>
							<input class="form-control-sm" name="suhu" value="<?php echo $suhu;?>" id="" type="text" size='' onfocus="nextfield ='frekuansi_pernafasan';" placeholder="">C<br>								
							<label for="" class="col-3">Frekuensi Pernafasan : </label>
							<input class="form-control-sm" name="frekuansi_pernafasan" value="<?php echo $frekuansi_pernafasan;?>" id="" type="text" size='' onfocus="nextfield ='skala_nyeri';" placeholder="">x/menit<br>
							<label for="" class="col-3">Skala Nyeri : </label>
							<input class="form-control-sm" name="skala_nyeri" value="<?php echo $skala_nyeri;?>" id="" type="text" size='' onfocus="nextfield ='berat_badan';" placeholder=""><br>
							<label for="" class="col-3">Berat Badan : </label>
							<input class="form-control-sm" name="berat_badan" value="<?php echo $berat_badan;?>" id="" type="text" size='' onfocus="nextfield ='status_lokalis';" placeholder="">Kg<br>
							<div class="card">
								<div class="card-header">
									Pemeriksaan Fisik
								</div>
								<div class="card-body">
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

								</div>

								<label for="" class="col-6">Status Lokalis : </label>
								<input class="form-control-sm" name="status_lokalis" value="<?php echo $status_lokalis;?>" id="" type="text" size='' onfocus="nextfield ='pemeriksaan_penunjang';" placeholder=""><br>															
								<label for="" class="col-6">Pemeriksaan Penunjang : </label>
								<input class="form-control-sm" name="pemeriksaan_penunjang" value="<?php echo $pemeriksaan_penunjang;?>" id="" type="text" size='' onfocus="nextfield ='simpan';" placeholder=""><br>											

							</div>

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

	$ku	= trim($_POST["ku"]);
	$rps	= trim($_POST["rps"]);
	$anamnesa	= trim($_POST["anamnesa"]);
	$rpd	= trim($_POST["rpd"]);
	$alergi	= trim($_POST["alergi"]);
	$assesmen	= trim($_POST["assesmen"]);
	$aplan	= trim($_POST["aplan"]);
	$eye	= trim($_POST["eye"]);
	$verbal	= trim($_POST["verbal"]);
	$movement	= trim($_POST["movement"]);
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
	$dsubjektif = 
	"Kondisi Umum : ".$ku.", Riwayat Pasien Sekarang : ".$rps.", Kondisi Kejiwaan : ".$anamnesa.", Data Riwayat Pasien Dahulu : ".$rpd.", Alergi : ".$alergi;

	//assemen
	$dassesment = $assesmen;

	//plaing
	$dplanning = $aplan;

	//object
	$dobjektif=
	"GCS - Eye : ".$eye.", Verbal : ".$verbal.", Movement : ".$movement.",".
	"Tensi : ".$tekanan_darah.", Nadi : ".$nadi.", Suhu : ".$suhu.", Frekuensi Pernafasan : ".$frekuansi_pernafasan.", Skala Nyeri : ".$skala_nyeri.", Berat Badan : ".$berat_badan.
	"<br>Pemeriksaan Fisik : Kepala : ".$fisik_kepala.", Mata : ".$fisik_mata.", THT : ".$fisik_tht.", Leher : ".$fisik_leher.", Paru : ".$fisik_paru.", Jantung : ".$fisik_jantung.", Abdomen :".$fisik_abdomen.", Extrimitas : ".$fisik_ekstermitas.", Urogential : ".$fisik_urogenital.", Status Lokalis : ".$status_lokalis.", Pemeriksaan Penunjang : ".$pemeriksaan_penunjang;

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

	$kodedokter  = substr($user,0,3);


	$pass = trim($_POST["pass"]);
	// $subjektif = trim($_POST["subjektif"]);
	// $objektif = trim($_POST["objektif"]);
	// $assesment = trim($_POST["assesment"]);
	// $planning = trim($_POST["planning"]);
	$instruksi = trim($_POST["instruksi"]);

	$subjektif = $dsubjektif;
	$objektif = $dobjektif;
	$assesment = $dassesment;
	$planning = $dplanning;
	$instruksi = trim($_POST["instruksi"]);


	// $dpjp = trim($_POST["dpjp"]);
	// $row2 = explode('-',$dpjp);
	// $dpjp  = trim($row2[0]);


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
		$q  = "insert into ERM_SOAP
		(norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry,instruksi,dpjp) 
		values 
		('$norm','$noreg','$tglinput','$sbu','$kodeunit','$kodedokter','$subjektif','$objektif','$assesment','$planning','$user','$tglinput','$instruksi','$dpjp')";
		$hs = sqlsrv_query($conn,$q);

		$qu="SELECT top(1)id FROM ERM_SOAP where noreg='$noreg' order by id desc";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$idsoap = $d1u['id'];

		echo	$q  = "insert into ERM_RI_SOAP
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

