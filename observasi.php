<?php 
include ("koneksi.php");
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
</head> 

<div class="container">

	<body onload="document.myForm.td_sistolik.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<div class="row">
					<div class="col-12">
						<i class="bi bi-window-plus"> &nbsp; <b>INPUT DATA OBSERVASI RAWAT INAP HARIAN</b></i>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">No. RM</label>
						<input class="" name="norm" value="<?php echo $norm;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>
					<div class="col-6">
						<label for="" class="col-sm-3">Tgl Lahir</label>
						<input class="" name="tgllahir" value="<?php echo $tgllahir;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>				
					<div class="col-6">
						<label for="" class="col-sm-3">Nama Pasien</label>
						<input class="" name="nama" value="<?php echo $nama;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>				
					<div class="col-6">
						<label for="" class="col-sm-3">Alamat</label>					
						<input class="" name="alamat" value="<?php echo $alamat;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
					</div>				
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">Tgl Input</label>
						<input class="" name="tglinput" value="<?php echo $tglinput;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>
				</div>

				<hr>
				Ket : Pengisian tanda , diganti dengan tanda .<br><br>

				<div class="row">
					<div class="col-4">
						<label for="" class="col-sm-4">TD Sistolik</label>
						<input class="" name="td_sistolik" value="<?php echo $td_sistolik;?>" id="" type="text" size='10' onfocus="nextfield ='td_diastolik';" placeholder="">
						<label for="" class="col-sm-2">mmHg</label>
					</div>					
					<div class="col-4">
						<label for="" class="col-sm-4">TD Diastolik</label>
						<input class="" name="td_diastolik" value="<?php echo $td_diastolik;?>" id="" type="text" size='10' onfocus="nextfield ='nadi';" placeholder="">
						<label for="" class="col-sm-2">mmHg</label>
					</div>					
					<div class="col-4">
						<label for="" class="col-sm-4">Nadi</label>
						<input class="" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='10' onfocus="nextfield ='suhu';" placeholder="">
						<label for="" class="col-sm-2">x/menit(Teratur)</label>
					</div>					
				</div>

				<div class="row">
					<div class="col-4">
						<label for="" class="col-sm-4">Suhu</label>
						<input class="" name="suhu" value="<?php echo $suhu;?>" id="" type="text" size='10' onfocus="nextfield ='pernafasan';" placeholder="">
						<label for="" class="col-sm-2">&deg;C</label>
					</div>					
					<div class="col-4">
						<label for="" class="col-sm-4">Frekuensi Pernafasan</label>
						<input class="" name="pernafasan" value="<?php echo $pernafasan;?>" id="" type="text" size='10' onfocus="nextfield ='spo2';" placeholder="">
						<label for="" class="col-sm-2">x/menit</label>
					</div>					
					<div class="col-4">
						<label for="" class="col-sm-4">SPO2</label>
						<input class="" name="spo2" value="<?php echo $spo2;?>" id="" type="text" size='10' onfocus="nextfield ='skala_nyeri';" placeholder="">
						<label for="" class="col-sm-2">%</label>
					</div>					
				</div>

				<div class="row">
					<div class="col-4">
						<label for="" class="col-sm-4">Skala Nyeri</label>
						<input class="" name="skala_nyeri" value="<?php echo $skala_nyeri;?>" id="" type="text" size='10' onfocus="nextfield ='bb';" placeholder="">
						<label for="" class="col-sm-4">Isikan 1 s/d 9</label>
					</div>					
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">BB dan TB</label>
						<input class="" name="bb" value="<?php echo $bb;?>" id="" type="text" size='10' onfocus="nextfield ='tb';" placeholder="Isian BB">
						<input class="" name="tb" value="<?php echo $tb;?>" id="" type="text" size='10' onfocus="nextfield ='muntah';" placeholder="Isian TB">
					</div>					
					<div class="col-6">
						<label for="" class="col-sm-4">Muntah</label>
						<input class="" name="muntah" value="<?php echo $muntah;?>" id="" type="text" size='10' onfocus="nextfield ='muntah_ket';" placeholder="Angka">
						<input class="" name="muntah_ket" value="<?php echo $muntah_ket;?>" id="" type="text" size='20' onfocus="nextfield ='cairan';" placeholder="Keterangan">
					</div>					
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">Cairan (Infus/Transfusi)</label>
						<input class="" name="cairan" value="<?php echo $cairan;?>" id="" type="text" size='10' onfocus="nextfield ='cairan_ket';" placeholder="Angka">
						<input class="" name="cairan_ket" value="<?php echo $cairan_ket;?>" id="" type="text" size='20' onfocus="nextfield ='bab';" placeholder="Keterangan">
					</div>					
					<div class="col-6">
						<label for="" class="col-sm-4">BAB</label>
						<input class="" name="bab" value="<?php echo $bab;?>" id="" type="text" size='10' onfocus="nextfield ='bab_ket';" placeholder="Angka">
						<input class="" name="bab_ket" value="<?php echo $bab_ket;?>" id="" type="text" size='20' onfocus="nextfield ='makan';" placeholder="Keterangan">
					</div>										
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4"></label>
					</div>					
					<div class="col-6">
						<label for="" class="col-sm-4">GCS</label>
						<input class="" name="" value="4" id="" type="text" size='10' onfocus="nextfield ='';" placeholder="Angka">
						<input class="" name="" value="5" id="" type="text" size='10' onfocus="nextfield ='';" placeholder="Keterangan">
						<input class="" name="" value="6" id="" type="text" size='10' onfocus="nextfield ='';" placeholder="Keterangan">
					</div>										
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">Makan</label>
						<input class="" name="makan" value="<?php echo $makan;?>" id="" type="text" size='10' onfocus="nextfield ='makan_ket';" placeholder="Angka">
						<input class="" name="makan_ket" value="<?php echo $makan_ket;?>" id="" type="text" size='20' onfocus="nextfield ='urine';" placeholder="Keterangan">
					</div>					
					<div class="col-6">
						<label for="" class="col-sm-4">Urine</label>
						<input class="" name="urine" value="<?php echo $urine;?>" id="" type="text" size='10' onfocus="nextfield ='urine_ket';" placeholder="Angka">
						<input class="" name="urine_ket" value="<?php echo $urine_ket;?>" id="" type="text" size='20' onfocus="nextfield ='minum';" placeholder="Keterangan">
					</div>										
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">Minum</label>
						<input class="" name="minum" value="<?php echo $minum;?>" id="" type="text" size='20' onfocus="nextfield ='drain';" placeholder="Angka">
					</div>					
					<div class="col-6">
						<label for="" class="col-sm-4">Cairan (Drain/GC)</label>
						<input class="" name="drain" value="<?php echo $drain;?>" id="" type="text" size='10' onfocus="nextfield ='drain_ket';" placeholder="Angka">
						<input class="" name="drain_ket" value="<?php echo $drain_ket;?>" id="" type="text" size='20' onfocus="nextfield ='oksigen_tambahan';" placeholder="Keterangan">
					</div>										
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">Oksigen Tambahan</label>
						<!-- <input class="" name="oksigen_tambahan" value="<?php echo $oksigen_tambahan;?>" id="" type="text" size='20' onfocus="nextfield ='iwl';" placeholder="Angka"> -->
						<select name="oksigen_tambahan" onfocus="nextfield ='iwl';">
							<option value="tidak">tidak</option>
							<option value="ya">ya</option>
						</select>
						<label for="" class="col-sm-4">Isikan Tidak / Ya</label>
					</div>					
					<div class="col-3">
						<label for="" class="col-sm-4">IWL</label>
						<input class="" name="iwl" value="<?php echo $iwl;?>" id="" type="text" size='20' onfocus="nextfield ='pupil';" placeholder="">
					</div>										
					<div class="col-3">
						<label for="" class="col-sm-3">Pupil</label>
						<input class="" name="pupil" value="<?php echo $pupil;?>" id="" type="text" size='20' onfocus="nextfield ='tingkat_kesadaran';" placeholder="">
					</div>										
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">Tingkat Kesadaran</label>
						<!-- <input class="" name="tingkat_kesadaran" value="<?php echo $tingkat_kesadaran;?>" id="" type="text" size='20' onfocus="nextfield ='pendarahan';" placeholder="Angka"> -->
						<select name="tingkat_kesadaran" onfocus="nextfield ='pendarahan';">
							<option value="alert">alert</option>
							<option value="vpu">vpu</option>
						</select>
						<label for="" class="col-sm-4">Isikan Salah Satu</label>
					</div>
				</div>	

				<div class="row">			
					<div class="col-6">
						<label for="" class="col-sm-4">Pendarahan</label>
						<input class="" name="pendarahan" value="<?php echo $pendarahan;?>" id="" type="text" size='20' onfocus="nextfield ='vip_score';" placeholder="">
					</div>										
					<div class="col-3">
						<label for="" class="col-sm-4">VIP Score</label>
						<input class="" name="vip_score" value="<?php echo $vip_score;?>" id="" type="text" size='20' onfocus="nextfield ='total_intake';" placeholder="">
					</div>										
				</div>	

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">Total Intake</label>
						<input class="" name="total_intake" value="<?php echo $total_intake;?>" id="" type="text" size='20' onfocus="nextfield ='total_output';" placeholder="">
					</div>					
					<div class="col-3">
						<label for="" class="col-sm-4">Total Output</label>
						<input class="" name="total_output" value="<?php echo $total_output;?>" id="" type="text" size='20' onfocus="nextfield ='sisa_cairan_infus';" placeholder="">
					</div>										
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-4">Sisa Cairan Infus</label>
						<input class="" name="sisa_cairan_infus" value="<?php echo $sisa_cairan_infus;?>" id="" type="text" size='20' onfocus="nextfield ='balance';" placeholder="">
					</div>					
					<div class="col-3">
						<label for="" class="col-sm-4">Balance</label>
						<input class="" name="balance" value="<?php echo $balance;?>" id="" type="text" size='20' onfocus="nextfield ='simpan';" placeholder="">
					</div>										
				</div>


				<div class="row">
					<div class="col-4">
						&nbsp;
					</div>
					<div class="col-6 align-self-center">
						<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'>Close</a>
						&nbsp;&nbsp;&nbsp;
						<button type="submit" name="simpan" class="btn btn-danger btn-sm" onfocus="nextfield ='done';">simpan</button> 
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

	$td_sistolik	= trim($_POST["td_sistolik"]);
	$td_diastolik	= trim($_POST["td_diastolik"]);
	$nadi	= trim($_POST["nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$pernafasan	= trim($_POST["pernafasan"]);
	$spo2	= trim($_POST["spo2"]);
	$skala_nyeri	= trim($_POST["skala_nyeri"]);
	$bb	= trim($_POST["bb"]);
	$tb	= trim($_POST["tb"]);
	$muntah	= trim($_POST["muntah"]);
	$muntah_ket	= trim($_POST["muntah_ket"]);
	$cairan	= trim($_POST["cairan"]);
	$cairan_ket	= trim($_POST["cairan_ket"]);
	$bab	= trim($_POST["bab"]);
	$bab_ket	= trim($_POST["bab_ket"]);
	$GCS	= trim($_POST["GCS"]);
	$makan	= trim($_POST["makan"]);
	$makan_ket	= trim($_POST["makan_ket"]);
	$urine	= trim($_POST["urine"]);
	$urine_ket	= trim($_POST["urine_ket"]);
	$minum	= trim($_POST["minum"]);
	$drain	= trim($_POST["drain"]);
	$drain_ket	= trim($_POST["drain_ket"]);
	$oksigen_tambahan	= trim($_POST["oksigen_tambahan"]);
	$iwl	= trim($_POST["iwl"]);
	$pupil	= trim($_POST["pupil"]);
	$tingkat_kesadaran	= trim($_POST["tingkat_kesadaran"]);
	$pendarahan	= trim($_POST["pendarahan"]);
	$vip_score	= trim($_POST["vip_score"]);
	$total_intake	= trim($_POST["total_intake"]);
	$total_output	= trim($_POST["total_output"]);
	$sisa_cairan_infus	= trim($_POST["sisa_cairan_infus"]);
	$balance	= trim($_POST["balance"]);
	$tglinput	= trim($_POST["tglinput"]);
	$userinput	= trim($_POST["userinput"]);

	$lanjut="Y";


	if(empty($user)){
		$eror='User Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($td_sistolik)){
		$eror='Tensi Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if($lanjut == 'Y'){
		$q  = "insert into ERM_RI_OBSERVASI
		(
		norm,noreg,
		td_sistolik,
		td_diastolik,
		nadi,
		suhu,
		pernafasan,
		spo2,
		skala_nyeri,
		bb,
		tb,
		muntah,
		muntah_ket,
		cairan,
		cairan_ket,
		bab,
		bab_ket,
		GCS,
		makan,
		makan_ket,
		urine,
		urine_ket,
		minum,
		drain,
		drain_ket,
		oksigen_tambahan,
		iwl,
		pupil,
		tingkat_kesadaran,
		pendarahan,
		vip_score,
		total_intake,
		total_output,
		sisa_cairan_infus,
		balance,
		tglinput,
		userinput
		) 
		values 
		(
		'$norm','$noreg',
		'$td_sistolik',
		'$td_diastolik',
		'$nadi',
		'$suhu',
		'$pernafasan',
		'$spo2',
		'$skala_nyeri',
		'$bb',
		'$tb',
		'$muntah',
		'$muntah_ket',
		'$cairan',
		'$cairan_ket',
		'$bab',
		'$bab_ket',
		'$GCS',
		'$makan',
		'$makan_ket',
		'$urine',
		'$urine_ket',
		'$minum',
		'$drain',
		'$drain_ket',
		'$oksigen_tambahan',
		'$iwl',
		'$pupil',
		'$tingkat_kesadaran',
		'$pendarahan',
		'$vip_score',
		'$total_intake',
		'$total_output',
		'$sisa_cairan_infus',
		'$balance',
		'$tglinput',
		'$user'
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

