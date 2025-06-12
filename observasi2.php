<?php 
include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// $serverName = "192.168.10.1"; //serverName\instanceName
// $connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
// $conn = sqlsrv_connect( $serverName, $connectionInfo);

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

$qu="SELECT * FROM  V_ERM_RI_KEADAAN_UMUM where noreg='$noreg' and tensi is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$kesadaran = $d1u['kesadaran'];
$e = $d1u['e'];
$v = $d1u['v'];
$m = $d1u['m'];
// $suhu = $d1u['suhu'];
// $suhu 			= str_replace(",",".",$suhu);

$tensi = $d1u['tensi'];
// $nadi = $d1u['nadi'];
$ket_nadi = $d1u['ket_nadi'];
$nafas = $d1u['nafas'];
$spo = $d1u['spo'];
$bb = $d1u['bb'];
$bb = str_replace("kg","",$bb);

$tb = $d1u['tb'];
$tb = str_replace("cm","",$tb);

if($bb=='-'){
	$bb=0;
}
if($tb=='-'){
	$tb=0;
}

//ambil resep



//SIMPAN
if (isset($_POST["simpan"])) {

	$td_sistolik	= trim($_POST["td_sistolik"]);	
	$td_diastolik	= trim($_POST["td_diastolik"]);
	$nadi	= trim($_POST["nadi"]);
	$nadi = str_replace(",",".",$nadi);

	$suhu	= trim($_POST["suhu"]);
	$suhu = str_replace(",",".",$suhu);

	$pernafasan	= trim($_POST["pernafasan"]);
	$pernafasan = str_replace(",",".",$pernafasan);

	$spo2	= trim($_POST["spo2"]);
	$spo2 = str_replace(",",".",$spo2);

	$tglinput	= trim($_POST["tglinput"]);

	$jam1a= $_POST["jam1a"];
	$jam1b= $_POST["jam1b"];
	$jam2a= $_POST["jam2a"];
	$jam2b= $_POST["jam2b"];

	$ob14	= $jam1a.' '.$jam1b;
	$ob17	= $jam2a.' '.$jam2b;

	$ob1	= $_POST["ob1"];
	$ob2	= $_POST["ob2"];
	$ob3	= $_POST["ob3"];
	$ob4	= $_POST["ob4"];
	$ob5	= $_POST["ob5"];
	$ob6	= $_POST["ob6"];
	$ob7	= $_POST["ob7"];
	$ob8	= $_POST["ob8"];
	$ob9	= $_POST["ob9"];
	$ob10	= $_POST["ob10"];
	$ob11	= $_POST["ob11"];
	$ob12	= $_POST["ob12"];
	$ob13	= $_POST["ob13"];
	
	$ob15	= $_POST["ob15"];
	$ob16	= $_POST["ob16"];
	
	$ob18	= $_POST["ob18"];
	$ob19	= $_POST["ob19"];
	$ob20	= $_POST["ob20"];
	$ob21	= $_POST["ob21"];
	$ob22	= $_POST["ob22"];
	$ob23	= $_POST["ob23"];
	$ob24	= $_POST["ob24"];
	$ob25	= $_POST["ob25"];
	$ob26	= $_POST["ob26"];
	$ob27	= $_POST["ob27"];
	$ob28	= $_POST["ob28"];
	$ob29	= $_POST["ob29"];
	$ob30	= $_POST["ob30"];
	$ob31	= $_POST["ob31"];
	$ob32	= $_POST["ob32"];
	$ob33	= $_POST["ob33"];
	$ob34	= $_POST["ob34"];
	$ob35	= $_POST["ob35"];
	$ob36	= $_POST["ob36"];
	$ob37	= $_POST["ob37"];
	$ob38	= $_POST["ob38"];
	$ob39	= $_POST["ob39"];
	$ob40	= $_POST["ob40"];
	$ob41	= $_POST["ob41"];
	$ob42	= $_POST["ob42"];
	$ob43	= $_POST["ob43"];
	$ob44	= $_POST["ob44"];
	$ob45	= $_POST["ob45"];
	$ob46	= $_POST["ob46"];
	$ob47	= $_POST["ob47"];
	$ob48	= $_POST["ob48"];
	$ob49	= $_POST["ob49"];
	$ob50	= $_POST["ob50"];

	$lanjut="Y";


	if(empty($user)){
		$eror='User Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($td_sistolik)){
		// $eror='Tensi Tidak Boleh Kosong !!!';
		// $lanjut='T';
	}

	if($lanjut == 'Y'){

		$ket =
		'tensi :'.$data1[td_sistolik].'/'.$data1[td_diastolik].' ,'.
		'nadi :'.$data1[nadi].' ,'.
		'suhu :'.$data1[suhu].' ,'.
		'pernafasan :'.$data1[pernafasan].' ,'.
		'spo2 :'.$data1[spo2]
		;

		$rr = $pernafasan; 
		if($rr >=25){ $s_rr=3;}
		if($rr >=21 && $rr <=24 ){ $s_rr=2;}
		if($rr >=12 && $rr <=20 ){ $s_rr=0;}
		if($rr >=9 && $rr <=11 ){ $s_rr=1;}
		if($rr <=8){ $s_rr=3;}

		$oksigen_tambahan =$ob7; 
		if($oksigen_tambahan=='ya'){
			$s_oksigen_tambahan=2;
		}else{
			$s_oksigen_tambahan=0;
		}

		$spo2 = $spo2; 
		if($spo2 >=25 && $spo2 <=100 ){ $s_spo2=0;}
		if($spo2 >=94 && $spo2 <=95 ){ $s_spo2=1;}
		if($spo2 >=92 && $spo2 <=93 ){ $s_spo2=2;}
		if($spo2 >=91 && $spo2 <=92 ){ $s_spo2=3;}

		$suhu = $suhu; 
		if($suhu >=39.1){ $s_suhu=2;}
		if($suhu >=38.1 && $suhu <=39 ){ $s_suhu=1;}
		if($suhu >=36.1 && $suhu <=38 ){ $s_suhu=0;}
		if($suhu >=35.1 && $suhu <=36 ){ $s_suhu=1;}
		if($suhu >=9 && $suhu <=11 ){ $s_suhu=1;}
		if($suhu <=3 ){ $s_suhu=3;}

		$sistole = $td_sistolik;
		if($sistole >=220){ $s_sistole=3;}
		if($sistole >=180 && $sistole <=219 ){ $s_sistole=2;}
		if($sistole >=150 && $sistole <=179 ){ $s_sistole=1;}
		if($sistole >=111 && $sistole <=149 ){ $s_sistole=0;}
		if($sistole >=101 && $sistole <=110 ){ $s_sistole=1;}
		if($sistole >=91 && $sistole <=100 ){ $s_sistole=2;}
		if($sistole <=90 ){ $s_sistole=3;}						

		$nadi = $nadi;
		if($nadi >=131){ $s_nadi=3;}
		if($nadi >=111 && $nadi <=130 ){ $s_nadi=2;}
		if($nadi >=91 && $nadi <=110 ){ $s_nadi=1;}
		if($nadi >=51 && $nadi <=90 ){ $s_nadi=0;}
		if($nadi >=41 && $nadi <=50 ){ $s_nadi=1;}
		if($nadi <=40 ){ $s_nadi=3;}	

		$tingkat_kesadaran =$ob1; 
		if($tingkat_kesadaran=='Tidak'){
			$s_tingkat_kesadaran=3;
		}else{
			$s_tingkat_kesadaran=0;
		}


		$total_score = $s_rr+$s_spo2+$s_oksigen_tambahan+$s_suhu+$s_sistole+$s_nadi+$s_tingkat_kesadaran;
		$score = intval($total_score);
		$total_ews = $score;

		if(intval($total_score) == 0){
			$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			$bgcolor='';
			$ket_ews='Sangat rendah<hr>Perawat jaga melakukan monitor setiap shift';
		}else if (intval($total_score) >= 1 and intval($total_score) <= 4 ){
			$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			$bgcolor='#90EE90';
			$ket_ews='Rendah<hr>Perawat jaga melakukan monitor setiap 4-6 jam dan menilai apakah perlu untuk meningkatkan frekuensi monitoring';
		}else if(intval($total_score) > 2 and intval($total_score) < 5){
			$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			$bgcolor='#FAFAD2';		
			$ket_ews='Sedang<hr>Perawat jaga melakukan monitor tiap 1 jam dan melaporkan ke dr jaga dan mempersiapkan jika mengalami perburukan kondisi pasien';
		}else{
			$score = "<font size='5px' color='black'><b>$total_score</b></font>";	
			$bgcolor='#FF6347';
			$ket_ews='Tinggi<hr>Perawat, tim emergency, DPJP melakukan tatalaksana kegawatan, observasi tiap 30 menit/ setiap saat. Aktifkan tim code blue bila terjadi cardiac arrest, transfer ke ruang ICU';
		}


		$t_input = $ob12+$ob13+$ob14+$ob15+$ob18+$ob19;
		$t_output= $ob20+$ob21+$ob22+$ob23+$ob24+$ob25+$ob26;
		$balance_cairan = $t_input - $t_output;
		$ob27 = $balance_cairan;


		echo $q  = "insert into ERM_RI_OBSERVASI
		(
		norm,noreg,
		td_sistolik,
		td_diastolik,
		nadi,
		suhu,
		pernafasan,
		spo2,
		bb,
		tb,
		tglinput,
		userinput,
		total_ews
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
		'$ob9',
		'$ob10',
		'$tglinput',
		'$user',
		'$total_ews'
	)";
	$hs1 = sqlsrv_query($conn,$q);

	if($hs1){
		$qu="SELECT TOP(1)id FROM ERM_RI_OBSERVASI where noreg='$noreg' order by id desc";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$id_observasi = trim($d1u['id']);

		$q  = "update ERM_RI_OBSERVASI set
		ob1	='$ob1',
		ob2	='$ob2',
		ob3	='$ob3',
		ob4	='$ob4',
		ob5	='$ob5',
		ob6	='$ob6',
		ob7	='$ob7',
		ob8	='$ob8',
		ob9	='$ob9',
		ob10	='$ob10',
		ob11	='$ob11',
		ob12	='$ob12',
		ob13	='$ob13',
		ob14	='$ob14',
		ob15	='$ob15',
		ob16	='$ob16',
		ob17	='$ob17',
		ob18	='$ob18',
		ob19	='$ob19',
		ob20	='$ob20',
		ob21	='$ob21',
		ob22	='$ob22',
		ob23	='$ob23',
		ob24	='$ob24',
		ob25	='$ob25',
		ob26	='$ob26',
		ob27	='$ob27',
		ob28	='$ob28',
		ob29	='$ob29',
		ob30	='$ob30',
		ob31	='$ob31',
		ob32	='$ob32',
		ob33	='$ob33',
		ob34	='$ob34',
		ob35	='$ob35',
		ob36	='$ob36',
		ob37	='$ob37',
		ob38	='$ob38',
		ob39	='$ob39',
		ob40	='$ob40',
		ob41	='$ob41',
		ob42	='$ob42',
		ob43	='$ob43',
		ob44	='$ob44',
		ob45	='$ob45',
		ob46	='$ob46',
		ob47	='$ob47',
		ob48	='$ob48',
		ob49	='$ob49',
		ob50	='$ob50'
		where id='$id_observasi'
		";
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			$eror = "Success";

			echo "
			<script>
			alert('".$eror."');
			window.location.replace('listobservasi.php?id=$id|$user');
			</script>
			";



		}else{
			$eror = "Gagal Insert";

		}

		// $notif=$eror.'<br>'.$score.'<br>'.$score;

		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";

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

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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

	<style>
		.card-custom {
			padding: 20px;
			border-radius: 15px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}
		.form-control, .form-check-input {
			border-radius: 10px;
		}
	</style>

</head> 

<div class="container-fluid">

	<body onload="document.myForm.td_sistolik.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">

				<div class="row">
					<div class="col-12">
						<?php 
						include "header_soap.php";
						?>
					</div>
				</div>

				<br>

				<div class="d-flex gap-2 justify-content-center">
					<a href="index.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-danger btn-md px-3" data-bs-toggle="tooltip" title="Tutup">
						<i class="bi bi-x-circle-fill me-1"></i> Close
					</a>
					<a href="" class="btn btn-primary btn-md px-3" data-bs-toggle="tooltip" title="Refresh">
						<i class="bi bi-arrow-clockwise"></i>
					</a>
					<a href="listobservasi.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-success btn-md px-3" data-bs-toggle="tooltip" title="Lihat Daftar">
						<i class="bi bi-list-ul me-1"></i> List
					</a>
				</div>

				<script>
					document.addEventListener("DOMContentLoaded", function() {
						var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
						tooltipTriggerList.forEach(function (tooltipTriggerEl) {
							new bootstrap.Tooltip(tooltipTriggerEl);
						});
					});
				</script>

				<div class="container mt-4">
					<div class="card card-custom">
						<div class="card-header bg-primary text-white text-center">
							<h5><i class="bi bi-window-plus"></i> INPUT DATA MONITORING</h5>
						</div>
						<div class="card-body">
							<form action="#" method="post">
								<div class="row g-3">
									<div class="col-md-6">
										<label class="form-label">Tgl/Jam Input</label>
										<input type="text" class="form-control" name="tglinput" value="<?php echo $tglinput; ?>">
									</div>

									<div class="col-12">
										<h6 class="mt-3">Monitoring EWS</h6>
										<hr>
									</div>

									<div class="col-md-6">
										<label class="form-label">Kesadaran</label>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="ob1" value="Sadar" <?php if ($ob1=="Sadar"){echo "checked";}?>>
											<label class="form-check-label">Sadar</label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="ob1" value="Tidak" <?php if ($ob1=="Tidak"){echo "checked";}?>>
											<label class="form-check-label">Tidak</label>
										</div>
										<input type="text" class="form-control mt-2" name="ob2" value="<?php echo $ob2; ?>" placeholder="Isikan jika tidak">
									</div>

									<div class="col-md-6">
										<label class="form-label">GCS</label>
										<div class="input-group">
											<span class="input-group-text">E</span>
											<input type="text" class="form-control" name="ob3" id="ob3" value="<?php echo $ob3; ?>">
											<span class="input-group-text">V</span>
											<input type="text" class="form-control" name="ob4" id="ob4" value="<?php echo $ob4; ?>">
											<span class="input-group-text">M</span>
											<input type="text" class="form-control" name="ob5" id="ob5" value="<?php echo $ob5; ?>">
											<span class="input-group-text">Total</span>
											<input type="text" class="form-control" name="ob6" id="ob6" value="<?php echo $ob6; ?>" readonly>
										</div>
									</div>

									<script>
										document.querySelectorAll('#ob3, #ob4, #ob5').forEach(input => {
											input.addEventListener("input", function() {
												let e = parseInt(document.getElementById("ob3").value) || 0;
												let v = parseInt(document.getElementById("ob4").value) || 0;
												let m = parseInt(document.getElementById("ob5").value) || 0;
												document.getElementById("ob6").value = e + v + m;
											});
										});
									</script>

									<div class="col-md-6">
										<label class="form-label">TD Sistole (mmHg)</label>
										<input type="text" class="form-control" name="td_sistolik" value="<?php echo $td_sistolik; ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label">TD Diastole (mmHg)</label>
										<input type="text" class="form-control" name="td_diastolik" value="<?php echo $td_diastolik; ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label">Suhu (&deg;C)</label>
										<input type="text" class="form-control" name="suhu" value="<?php echo $suhu; ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label">Nadi (x/menit)</label>
										<input type="text" class="form-control" name="nadi" value="<?php echo $nadi; ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label">Pernapasan (x/menit)</label>
										<input type="text" class="form-control" name="pernafasan" value="<?php echo $pernafasan; ?>">
									</div>
									<div class="col-md-6">
										<label class="form-label">SpO2 (%)</label>
										<input type="text" class="form-control" name="spo2" value="<?php echo $spo2; ?>">
									</div>

									<div class="col-12 text-center mt-3">
										<button type="submit" class="btn btn-primary btn-lg">
											<i class="bi bi-save-fill"></i> Simpan Monitoring EWS
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>


				<hr>

				<div class="container mt-4">
					<h4 class="text-center">Monitoring Cairan</h4>
					<form method="POST" action="">
						<!-- Input Section -->
						<div class="card mb-3">
							<div class="card-header bg-primary text-white">Input</div>
							<div class="card-body">
								<div class="row mb-3">
									<div class="col-md-4">
										<label class="form-label">Infus (cc)</label>
										<input type="number" class="form-control" name="ob12" placeholder="Masukkan volume">
									</div>
									<div class="col-md-4">
										<label class="form-label">Tetesan (/menit)</label>
										<input type="number" class="form-control" name="ob13" placeholder="Masukkan tetesan">
									</div>
									<div class="col-md-4">
										<label class="form-label">Jam</label>
										<input type="date" class="form-control" name="jam1a">
										<input type="time" class="form-control mt-2" name="jam1b">
									</div>
								</div>
								<div class="mb-3">
									<label class="form-label">Nama Infus</label>
									<input type="text" class="form-control" name="ob29" placeholder="Isikan nama infus">
								</div>
							</div>
						</div>

						<!-- Output Section -->
						<div class="card mb-3">
							<div class="card-header bg-danger text-white">Output</div>
							<div class="card-body">
								<div class="row mb-3">
									<div class="col-md-4">
										<label class="form-label">Muntah (cc)</label>
										<input type="number" class="form-control" name="ob20" placeholder="Masukkan volume">
									</div>
									<div class="col-md-4">
										<label class="form-label">BAB (cc)</label>
										<input type="number" class="form-control" name="ob21" placeholder="Masukkan volume">
									</div>
									<div class="col-md-4">
										<label class="form-label">Urine (cc)</label>
										<input type="number" class="form-control" name="ob22" placeholder="Masukkan volume">
									</div>
								</div>
							</div>
						</div>

						<!-- Balance Cairan -->
						<div class="mb-3">
							<label class="form-label"><b>Balance Cairan (total input - total output)</b></label>
							<input type="number" class="form-control" name="ob27" placeholder="Hasil balance cairan" readonly>
						</div>

						<!-- GDA -->
						<div class="mb-3">
							<label class="form-label"><b>GDA</b></label>
							<input type="text" class="form-control" name="ob28" placeholder="Masukkan GDA">
						</div>

						<!-- Submit Button -->
						<div class="text-center">
							<button type="submit" name="simpan" class="btn btn-success btn-lg">
								<i class="bi bi-save-fill"></i> Simpan Monitoring Cairan
							</button>
						</div>
					</form>
				</div>

				<br><br><br>
			</form>
		</font>
	</body>
</div>
