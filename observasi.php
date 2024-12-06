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
$tb = $d1u['tb'];

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
	$suhu	= trim($_POST["suhu"]);
	$pernafasan	= trim($_POST["pernafasan"]);
	$spo2	= trim($_POST["spo2"]);

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

				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
<!-- 				<button type="submit" name="simpan" class="btn btn-info btn-sm" onfocus="nextfield ='done';"><i class="bi bi-save"></i>
				simpan</button> 
			-->				
			<a href='listobservasi.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success btn-sm'><i class="bi bi-list"></i> List</a>

			<br><br>

			<div class="row">
				<div class="col-12">
					<i class="bi bi-window-plus"> &nbsp; <b>INPUT DATA MONITORING</b></i>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col-6">
					<table border='0' width="100%">
						<tr>
							<td>Tgl/Jam Input</td>
							<td>: &nbsp;&nbsp; <input class="" name="tglinput" value="<?php echo $tglinput;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder=""></td>
						</tr>

						<tr>
							<td colspan="2"><b>Monitoring EWS</b></td>
						</tr>

						<tr><td>Kesadaran </td>
							<td>: 
								<?php 
								if(empty($ob1)){
									// if (strtoupper(trim($kesadaran))=='COMPOSMENTIS'){
									// 	$ob1="Sadar";
									// }else{
									// 	$ob1="Tidak";
									// 	$ob2=$kesadaran;
									// }
								}
								?>
								<input type='checkbox' name='ob1' value='Sadar' <?php if ($ob1=="Sadar"){echo "checked";}?> > Sadar
								<input type='checkbox' name='ob1' value='Tidak' <?php if ($ob1=="Tidak"){echo "checked";}?> > Tidak
								<input class="" name="ob2" value="<?php echo $ob2;?>" id="" type="text" size='30' placeholder="isikan jika tidak">
							</td>
						</tr>
						<tr><td>GCS </td>
							<td>: 
								<?php 
								if(empty($ob3)){
									// $ob3=$e;
									// $ob4=$v;
									// $ob5=$m;
									// $ob6=$e+$v+$m;

									// 	//tekanan darah
									// $row2 = explode('/',$tensi);
									// $sistole  = $row2[0];
									// $diastole = $row2[1]; 

									// $td_sistolik = $sistole;
									// $td_diastolik = $diastole;

									// $suhu = $suhu;
									// $nadi = $nadi;
									// $pernafasan = $nafas;
									// $spo2=$spo;

								}
								?>
								&nbsp;&nbsp; 
								E <input class="" name="ob3" value="<?php echo $ob3;?>" id="" type="text" size='2' placeholder=""> 
								V <input class="" name="ob4" value="<?php echo $ob4;?>" id="" type="text" size='2' placeholder="">
								M <input class="" name="ob5" value="<?php echo $ob5;?>" id="" type="text" size='2' placeholder="">
								Total <input class="" name="ob6" value="<?php echo $ob6;?>" id="" type="text" size='2' placeholder="">
							</td>
						</tr>
						<tr><td>TD Sistole </td>
							<td>: &nbsp;&nbsp; <input class="" name="td_sistolik" value="<?php echo $td_sistolik;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;mmHg</td>
						</tr>
						<tr><td>TD Diastole </td>
							<td>: &nbsp;&nbsp; <input class="" name="td_diastolik" value="<?php echo $td_diastolik;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;mmHg</td>
						</tr>
						<tr><td>Suhu </td><td>: &nbsp;&nbsp; <input class="" name="suhu" value="<?php echo $suhu;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;0C</td></tr>
						<tr><td>Nadi </td><td>: &nbsp;&nbsp; <input class="" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; x/menit</td></tr>
						<tr><td>Pernapasan </td><td>: &nbsp;&nbsp; <input class="" name="pernafasan" value="<?php echo $pernafasan;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;  x/menit</td></tr>
						<tr><td>SpO2 </td><td>: &nbsp;&nbsp; <input class="" name="spo2" value="<?php echo $spo2;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; %</td></tr>
						<tr><td>Oksigen </td><td>: 
							<input type='checkbox' name='ob7' value='Ya' <?php if ($ob7=="Ya"){echo "checked";}?> > Ya
							<input type='checkbox' name='ob7' value='Tidak' <?php if ($ob7=="Tidak"){echo "checked";}?> > Tidak
							<input class="" name="ob8" value="<?php echo $ob8;?>" id="" type="text" size='30' placeholder="isikan jika tidak">
						</td>
					</tr>
					<tr><td>BB </td><td>: &nbsp;&nbsp; <input class="" name="ob9" value="<?php echo $bb;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; kg</td></tr>
					<tr><td>TB </td><td>: &nbsp;&nbsp; <input class="" name="ob10" value="<?php echo $tb;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; cm</td></tr>

				</table>
			</div>
			<div class="col-6">
				<?php  

				if($hs){
					echo "
					<table>
					<tr>
					<td align='center' bgcolor='$bgcolor'>
					SCORE EWS :<br>
					$score<br>$ket_ews </td>
					</tr>
					</table>
					";
				}


				?>
			</div>

		</div>
		<br>
		<div class="row">
			<div class="col-12">
				<center>
					<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
				</center>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-12">
				<table border='0' width="100%">
					<tr>
						<td colspan="6"><b>Monitoring Cairan</b></td>
					</tr>
					<tr>
						<td colspan="6"><b>Input</b></td>
					</tr>
					<tr>
						<td>Infus </td>
						<td>
							:<input class="" name="ob12" value="<?php echo $ob12;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
						<td>Tetesan </td>
						<td>
							:<input class="" name="ob13" value="<?php echo $ob13;?>" id="" type="text" size='20' placeholder=""> /menit 
						</td>
						<td>Jam </td>
						<td>
							:
							<!-- <input class="" name="ob14" value="<?php echo $ob14;?>" id="" type="text" size='20' placeholder=""> (free text) -->
							<input type='date' name='jam1a' value=''>
							&nbsp;
							<input type='time' name='jam1b' value=''>
						</td>
					</tr>
					<tr>
						<td>Nama Infus </td>
						<td>
							:<input class="" name="ob29" value="<?php echo $ob29;?>" id="" type="text" size='50' placeholder="isikan nama infus"> 
						</td>
					</tr>
					<tr>
						<td>Transfusi </td>
						<td>
							:<input class="" name="ob15" value="<?php echo $ob15;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
						<td>Tetesan </td>
						<td>
							:<input class="" name="ob16" value="<?php echo $ob16;?>" id="" type="text" size='20' placeholder=""> /menit 
						</td>
						<td>Jam </td>
						<td>
							:
							<!-- <input class="" name="ob17" value="<?php echo $ob17;?>" id="" type="text" size='20' placeholder=""> (free text) -->
							<input type='date' name='jam2a' value=''>
							&nbsp;
							<input type='time' name='jam2b' value=''>
						</td>
					</tr>
					<tr>
						<td>Makan </td>
						<td>
							:<input class="" name="ob18" value="<?php echo $ob18;?>" id="" type="text" size='20' placeholder=""> porsi
						</td>
						<td>Minum </td>
						<td>
							:<input class="" name="ob19" value="<?php echo $ob19;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
					</tr>
					<tr>
						<td colspan="6"><b>Output</b></td>
					</tr>
					<tr>
						<td>Muntah </td>
						<td>
							:<input class="" name="ob20" value="<?php echo $ob20;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
						<td>BAB </td>
						<td>
							:<input class="" name="ob21" value="<?php echo $ob21;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
						<td>Urine </td>
						<td>
							:<input class="" name="ob22" value="<?php echo $ob22;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
					</tr>
					<tr>
						<td>IWL </td>
						<td>
							:<input class="" name="ob23" value="<?php echo $ob23;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
						<td>NGT </td>
						<td>
							:<input class="" name="ob24" value="<?php echo $ob24;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
						<td>Drain </td>
						<td>
							:<input class="" name="ob25" value="<?php echo $ob25;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
					</tr>
					<tr>
						<td>Perdarahan </td>
						<td>
							:<input class="" name="ob26" value="<?php echo $ob26;?>" id="" type="text" size='20' placeholder=""> cc
						</td>
					</tr>
					<tr>
						<td><b>Balance Cairan</b>  </td>
						<td colspan="6">
							:<input class="" name="ob27" value="<?php echo $ob27;?>" id="" type="text" size='20' placeholder=""> (total input - total output)

						</td>
					</tr>
			<!-- <br><br>
			<tr>
				<td><b>GDA</b>  </td>
				<td colspan="6">
					:<input class="" name="ob28" value="<?php echo $ob28;?>" id="" type="text" size='20' placeholder=""> 

				</td>
			</tr> -->


		</table>
	</div>
</div>
<br>
<div class="row">
	<div class="col-12">
		<table border='0' width="100%">
			<tr>
				<td colspan="2"></td>
			</tr>
			<tr><td><b>GDA</b></td>
				<td>: 
					<input class="" name="ob28" value="<?php echo $ob28;?>" id="" type="text" size='20' placeholder=""> 
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<center>
			<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
		</center>
	</div>
</div>


</div>
<br><br><br>
</form>
</font>
</body>
</div>
