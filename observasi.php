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

		if($hari_u < 28 and $bulan_u < 9 and $tahun_u <= 0){ //neonatus
			$j_ews ="neonatus";
		}else if($tahun_u >=0 and $tahun_u <=17){ //anak
			$j_ews ="anak";
		}else if($tahun_u >17 and $tahun_u <=60){ //dewasa
			$j_ews ="dewasa";
		}else if($tahun_u >60){//geriatri
			$j_ews ="geriatri";
		}

		$ket =
		'tensi :'.$data1[td_sistolik].'/'.$data1[td_diastolik].' ,'.
		'nadi :'.$data1[nadi].' ,'.
		'suhu :'.$data1[suhu].' ,'.
		'pernafasan :'.$data1[pernafasan].' ,'.
		'spo2 :'.$data1[spo2]
		;

		if($j_ews=='anak'){

			$rr = $pernafasan; 
			if($rr >60 ){ $s_rr=3;}
			if($rr >=51 && $rr <=60 ){ $s_rr=2;}
			if($rr >=31 && $rr <=50 ){ $s_rr=1;}
			if($rr >=20 && $rr <=30 ){ $s_rr=0;}
			if($rr < 20){ $s_rr=3;}

			$spo2 = $spo2; 
			if($spo2 >95){ $s_spo2=0;}
			if($spo2 >=90 && $spo2 <=94 ){ $s_spo2=1;}
			if($spo2 >=86 && $spo2 <=89 ){ $s_spo2=2;}
			if($spo2 <=85){ $s_spo2=3;}

			$oksigen_tambahan = $ob7; 
			if($oksigen_tambahan=='ya'){
				$s_oksigen_tambahan=2;
			}else{
				$s_oksigen_tambahan=0;
			}

			$nadi = $nadi;
			if($nadi > 160){ $s_nadi=3;}
			if($nadi >=141 && $nadi <=160 ){ $s_nadi=2;}
			if($nadi >=121 && $nadi <=140 ){ $s_nadi=1;}
			if($nadi >=80 && $nadi <=120 ){ $s_nadi=0;}
			if($nadi >=70 && $nadi <=79 ){ $s_nadi=1;}
			if($nadi >=60 && $nadi <=69 ){ $s_nadi=2;}
			if($nadi <60 ){ $s_nadi=3;}	


			$suhu = $suhu; 
			if($suhu >=39){ $s_suhu=2;}
			if($suhu ==38){ $s_suhu=1;}
			if($suhu ==37){ $s_suhu=0;}
			if($suhu ==36){ $s_suhu=0;}
			if($suhu ==35){ $s_suhu=1;}
			if($suhu <=34 ){ $s_suhu=3;}


			$sistole = $td_sistolik;
			if($sistole >=150 ){ $s_sistole=3;}
			if($sistole >=140 && $sistole >=149){ $s_sistole=3;}
			if($sistole >=130 && $sistole >=139){ $s_sistole=2;}
			if($sistole >=120 && $sistole >=129){ $s_sistole=1;}
			if($sistole >=90 && $sistole >=119){ $s_sistole=0;}
			if($sistole >=80 && $sistole >=89){ $s_sistole=1;}
			if($sistole <=70 ){ $s_sistole=3;}						

			$tingkat_kesadaran =$ob1; 
			if($tingkat_kesadaran=='Tidak'){
				$s_tingkat_kesadaran=3;
			}else{
				$s_tingkat_kesadaran=0;
			}

			$total_score = $s_rr+$s_spo2+$s_oksigen_tambahan+$s_suhu+$s_nadi+$s_tingkat_kesadaran;
			$score = intval($total_score);
			$total_ews = $score;

		}else{

			$rr = $pernafasan; 
			if($rr >=12 && $rr <=20 ){ $s_rr=0;}
			if($rr >=9 && $rr <=11 ){ $s_rr=1;}
			if($rr >=21 && $rr <=24 ){ $s_rr=2;}
			if($rr <=8){ $s_rr=3;}
			if($rr >=25){ $s_rr=3;}

			$oksigen_tambahan = $ob7; 
			if($oksigen_tambahan=='ya'){
				$s_oksigen_tambahan=2;
			}else{
				$s_oksigen_tambahan=0;
			}

			$spo2 = $spo2; 
			if($spo2 >=96 && $spo2 <=100 ){ $s_spo2=0;}
			if($spo2 >=92 && $spo2 <96 ){ $s_spo2=2;}
			if($spo2 >=0 && $spo2 <92 ){ $s_spo2=3;}

			$suhu = $suhu; 
			if($suhu >=36 && $suhu <=37 ){ $s_suhu=0;}
			if($suhu ==35 OR $suhu ==38 ){ $s_suhu=1;}
			if($suhu >=39){ $s_suhu=2;}
			if($suhu <=34 ){ $s_suhu=3;}

			$sistole = $td_sistolik;
			if($sistole >=100 && $sistole <=160 ){ $s_sistole=0;}
			if($sistole ==90 OR $sistole ==170 OR $sistole ==180 ){ $s_sistole=1;}
			if($sistole >=171 && $sistole <=180 ){ $s_sistole=1;}
			if($sistole >=80 && $sistole <=89 ){ $s_sistole=2;}
			if($sistole >=191 && $sistole <=209 ){ $s_sistole=2;}
			if($sistole >=210){ $s_sistole=3;}
			if($sistole <=79 ){ $s_sistole=3;}						

			$nadi = $nadi;
			if($nadi >=51 && $nadi <=90 ){ $s_nadi=0;}
			if($nadi >=41 && $nadi <=50 ){ $s_nadi=1;}
			if($nadi >=91 && $nadi <=110 ){ $s_nadi=1;}
			if($nadi >=111 && $nadi <=130 ){ $s_nadi=2;}
			if($nadi <=40 ){ $s_nadi=3;}	
			if($nadi >=131){ $s_nadi=3;}

			$tingkat_kesadaran =$ob1; 
			if($tingkat_kesadaran=='Tidak'){
				$s_tingkat_kesadaran=3;
			}else{
				$s_tingkat_kesadaran=0;
			}

			$total_score = $s_rr+$s_spo2+$s_oksigen_tambahan+$s_suhu+$s_sistole+$s_nadi+$s_tingkat_kesadaran;
			$score = intval($total_score);
			$total_ews = $score;
		}
		

		if(intval($total_score) == 0){
			$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			$bgcolor='';
			$ket_ews='Sangat rendah<hr>Perawat jaga melakukan monitor setiap shift';
		}else if (intval($total_score) >= 1 and intval($total_score) <= 4 ){
			$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			$bgcolor='#90EE90';
			$ket_ews='Rendah<hr>Perawat jaga melakukan monitor setiap 4-6 jam dan menilai apakah perlu untuk meningkatkan frekuensi monitoring';
		}else if(intval($total_score) >= 5 and intval($total_score) <= 6){
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
			window.location.replace('listobservasi_ews.php?id=$id|$user');
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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head> 


<div class="container py-4">
	<form method="POST" name='myForm' action="" enctype="multipart/form-data">
		<?php include "header_soap.php"; ?>

		<div class="mb-3">
			<a href='index.php?id=<?php echo $id."|".$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
			<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i> Refresh</a>
			<a href='observasi.php?id=<?php echo $id."|".$user;?>' class='btn btn-success btn-sm'><i class="bi bi-graph-up"></i> Monitoring EWS</a>

			<a href='observasi_cairan2.php?id=<?php echo $id."|".$user;?>' class='btn btn-success btn-sm'><i class="bi bi-droplet"></i> Monitoring Cairan</a>
		</div>

		<div class="card shadow">
			<div class="card-header bg-primary text-white">
				<i class="bi bi-clipboard-plus"></i> Input Data Monitoring EWS
			</div>
			<div class="card-body row g-3">
				<div class="col-md-6">

					<div class="form-floating mb-3">
						<a href='listobservasi_ews.php?id=<?php echo $id."|".$user;?>' class='btn btn-success btn-sm'><i class="bi bi-list"></i> List Data</a>
					</div>

					<div class="form-floating mb-3">
						<input type="text" class="form-control" name="tglinput" value="<?php echo $tglinput;?>">
						<label><i class="bi bi-calendar-event"></i> Tanggal/Jam Input</label>
					</div>

					<h6><i class="bi bi-activity"></i> Monitoring EWS</h6>

					<label class="form-label"><i class="bi bi-person-check"></i> Kesadaran</label>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="ob1" value="Sadar" <?php if ($ob1=="Sadar") echo "checked"; ?>>
						<label class="form-check-label">Sadar</label>
					</div>
					<div class="form-check mb-2">
						<input class="form-check-input" type="radio" name="ob1" value="Tidak" <?php if ($ob1=="Tidak") echo "checked"; ?>>
						<label class="form-check-label">Tidak Sadar</label>
					</div>
					<input class="form-control mb-3" name="ob2" value="<?php echo $ob2;?>" placeholder="Isikan jika tidak sadar">

					<div class="row g-2">
						<label class="form-label"><i class="bi bi-eye"></i> GCS</label>
						<div class="col">
							<input class="form-control" name="ob3" id="ob3" value="<?php echo $ob3;?>" placeholder="E">
						</div>
						<div class="col">
							<input class="form-control" name="ob4" id="ob4" value="<?php echo $ob4;?>" placeholder="V">
						</div>
						<div class="col">
							<input class="form-control" name="ob5" id="ob5" value="<?php echo $ob5;?>" placeholder="M">
						</div>
						<div class="col">
							<input class="form-control" name="ob6" id="ob6" value="<?php echo $ob6;?>" readonly placeholder="Total">
						</div>
					</div>

					<script>
						document.getElementById("ob5").addEventListener("input", function() {
							let e = parseInt(document.getElementById("ob3").value) || 0;
							let v = parseInt(document.getElementById("ob4").value) || 0;
							let m = parseInt(document.getElementById("ob5").value) || 0;
							document.getElementById("ob6").value = e + v + m;
						});
					</script>

					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="td_sistolik" value="<?php echo $td_sistolik;?>">
						<label><i class="bi bi-activity"></i> TD Sistolik (mmHg)</label>
					</div>
					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="td_diastolik" value="<?php echo $td_diastolik;?>">
						<label><i class="bi bi-activity"></i> TD Diastolik (mmHg)</label>
					</div>
					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="suhu" value="<?php echo $suhu;?>">
						<label><i class="bi bi-thermometer-sun"></i> Suhu (°C)</label>
					</div>
					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="nadi" value="<?php echo $nadi;?>">
						<label><i class="bi bi-heart-pulse"></i> Nadi (x/menit)</label>
					</div>
					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="pernafasan" value="<?php echo $pernafasan;?>">
						<label><i class="bi bi-wind"></i> Pernapasan (x/menit)</label>
					</div>
					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="spo2" value="<?php echo $spo2;?>">
						<label><i class="bi bi-droplet-half"></i> SpO2 (%)</label>
					</div>

					<label class="form-label mt-3"><i class="bi bi-capsule"></i> Oksigen</label>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="ob7" value="Ya" <?php if ($ob7=="Ya") echo "checked"; ?>>
						<label class="form-check-label">Ya</label>
					</div>
					<div class="form-check mb-2">
						<input class="form-check-input" type="radio" name="ob7" value="Tidak" <?php if ($ob7=="Tidak") echo "checked"; ?>>
						<label class="form-check-label">Tidak</label>
					</div>
					<input class="form-control mb-3" name="ob8" value="<?php echo $ob8;?>" placeholder="Isikan jika tidak">

<!-- 					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="ob9" value="<?php echo $bb;?>">
						<label><i class="bi bi-person-fill"></i> Berat Badan (kg)</label>
					</div>
					<div class="form-floating mt-3">
						<input type="text" class="form-control" name="ob10" value="<?php echo $tb;?>">
						<label><i class="bi bi-rulers"></i> Tinggi Badan (cm)</label>
					</div> -->
					
				</div>

				<div class="col-md-6">
					<?php if ($hs): ?>
						<div class="card bg-light text-center">
							<div class="card-body">
								<h5><i class="bi bi-graph-up-arrow"></i> SCORE EWS</h5>
								<p class="fs-3"><?php echo $score; ?></p>
								<p><?php echo $ket_ews; ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="card-footer text-center mt-4">
				<button type="submit" name="simpan" value="simpan" class="btn btn-info btn-lg px-5">
					<i class="bi bi-save-fill"></i> Simpan Monitoring EWS
				</button>
			</div>
		</div>
	</form>
</div>

