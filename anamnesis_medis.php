<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include ("mode.php");

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
where ARM_REGISTER.NORM='$norm'

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
	$q  = "insert into ERM_RI_ANAMNESIS_MEDIS(noreg,userid,tglentry,tgl,nm_rpenyakit,lm_rpenyakit,kl_rpenyakit,al_rpenyakit,ob_rpenyakit) 
	values ('$noreg','$user','$tglinput','$tglinput','Tidak Ada','Tidak Ada','Tidak Ada','Tidak Ada','Tidak Ada')";
	$hs = sqlsrv_query($conn,$q);

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ANAMNESIS_MEDIS
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 

	$nm_rpenyakit= trim($de['nm_rpenyakit']);
	$lm_rpenyakit= trim($de['lm_rpenyakit']);
	$kl_rpenyakit= trim($de['kl_rpenyakit']);
	$al_rpenyakit= trim($de['al_rpenyakit']);
	$ob_rpenyakit= trim($de['ob_rpenyakit']);
	
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
	$jamrawat = $de['jamrawat'];
	$tglrawat = $de['tglrawat'];

	$tglselesai = $de['tglselesai'];
	$jamselesai = $de['jamselesai'];

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
	$am81= $de['am81'];
	$am82= $de['am82'];
	$am83= $de['am83'];
	$am84= $de['am84'];
	$am85= $de['am85'];
	$am86= $de['am86'];
	$am87= $de['am87'];
	$am88= $de['am88'];
	$am89= $de['am89'];
	$am90= $de['am90'];
	$nm_rpenyakit= trim($de['nm_rpenyakit']);
	$lm_rpenyakit= trim($de['lm_rpenyakit']);
	$kl_rpenyakit= trim($de['kl_rpenyakit']);
	$al_rpenyakit= trim($de['al_rpenyakit']);
	$ob_rpenyakit= trim($de['ob_rpenyakit']);
	$diagnosa_planning= trim($de['diagnosa_planning']);
	$assesment= trim($de['assesment']);
	$spo2= trim($de['spo2']);

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

//ambil dari igd...
$noreg_igd = substr($noreg, 1,12);
$qhi="SELECT id FROM  ERM_ASSESMEN_HEADER where noreg='$noreg_igd'";
$hqhi  = sqlsrv_query($conn, $qhi);        
$dhqhi  = sqlsrv_fetch_array($hqhi, SQLSRV_FETCH_ASSOC); 
$id_header_igd = $dhqhi['id'];

if($id_header_igd){
	$qu="
	SELECT TOP (1) ID, IDHEADER, GCS, TD, NADI, RR, SUHU, SPO2, KELUHANHASIL, TGLENTRY, USERID, TINDAKANOBSERVASILANJUT, PERAWATOBSERVASILANJUT, DOKTEROBSERVASILANJUT, JAMOBSERVASILANJUT, 
	DIKOREKSI, TGLKOREKSI, PERAWATBIDAN, E, V, M, TGLOBSERVASILANJUT
	FROM            ERM_IGD_OBSERVASI_LANJUT
	WHERE        (IDHEADER = '$id_header_igd') order by id desc
	";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$kesadaran = $d1u['KELUHANHASIL'];
	$e = $d1u['E'];
	$v = $d1u['V'];
	$m = $d1u['M'];
	$suhu = $d1u['SUHU'];
	$tensi = $d1u['TD'];
	$nadi = $d1u['NADI'];
	$ket_nadi = '';
	$nafas = $d1u['RR'];
	$spo2 = $d1u['SPO2'];
	$bb = '-';
	$tb = '-';
	
	$qu2="
	SELECT  id, idheader, keluhanutama, riwayatsekarang, tglentry, userid, ALERGIOBATCEK, KETERANGANALERGIOBAT, ALERGIMAKANANCEK, KETERANGANALERGIMAKANAN, ALERGIUDARACEK, KETERANGANALERGIUDARA, 
	ANALERGILAINCEK, KETERANGANALERGILAIN, SUMBERDATA, NOREG, NORM, NAMA, HUBUNGAN, keluhanutamadokter, CATATAN, RIWAYATKEHAMILAN, RIWAYATTERAPI, KODEUNIT, riwayatdahulu, RIWAYATKELUARGA, 
	RIWAYATSOSIAL FROM ERM_KELUHAN WHERE (NOREG = '$noreg_igd') 
	";
	$h1u2  = sqlsrv_query($conn, $qu2);        
	$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
	$keluhan_utama = $d1u2['kesadaran'];
	$riwayat_penyakit = $d1u2['riwayatdahulu'];
}else{
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
	$spo2 = $d1u['spo'];
	$bb = $d1u['bb'];
	$tb = $d1u['tb'];
	$keluhan_utama = $d1u['keluhan_utama'];
	$riwayat_penyakit = $d1u['riwayat_penyakit'];
}


if($skala_nyeri){
	$skala_nyeri = $d1u['skala_nyeri'].' Lokasi Nyeri : '.$d1u['lokasi_nyeri'];
}else{
	$skala_nyeri='-';
}

$berat_badan = $d1u['bb'];

$noreg_igd = substr($noreg, 1,12);

$qd="
SELECT        'IGD' AS unit, ERM_IGD_ADVIS.ADVIS, Afarm_DOKTER.NAMA, CONVERT(VARCHAR, ERM_IGD_ADVIS.TGLENTRY, 103) AS tgl, CONVERT(VARCHAR, ERM_IGD_ADVIS.TGLENTRY, 8) AS jam
FROM            ERM_IGD_ADVIS INNER JOIN
Afarm_DOKTER ON ERM_IGD_ADVIS.KODEDOKTER = Afarm_DOKTER.KODEDOKTER
WHERE        (ERM_IGD_ADVIS.NOREG = '$noreg_igd')
";
$hasild  = sqlsrv_query($conn, $qd);  
$no=1;
while   ($datad = sqlsrv_fetch_array($hasild,SQLSRV_FETCH_ASSOC)){ 

	$advis = trim($datad[ADVIS]);
	$dokter_advis = trim($datad[NAMA]);
	$tgl_advis = trim($datad[tgl]);
	$jam_advis = trim($datad[jam]);
	$detail = $no.'. '.$dokter_advis.'. '.$tgl_advis.'-'.$jam_advis.'. '."\nAdvis : ".$advis."\n\n";
	$terapi_igd = $terapi_igd.$detail;
	$no += 1;
}

//echo nl2br($terapi_igd);

if(empty($am77)){
	$am77 = $terapi_igd;
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

	<style>
		@media print {
			.hide-on-print {
				display: none !important;
			}
		}
	</style>

	<style>
		body.dark-mode .card {
			background-color: #212529; /* Warna gelap */
			color: #ffffff;            /* Teks putih */
		}
		body.dark-mode .diagnosa-text {
			color: #ffffff;
		}

		body:not(.dark-mode) .diagnosa-text {
			color: #000000;
		}
		body.dark-mode .verifikasi-text {
			color: #cccccc;
		}
	</style>

</head> 


<div class="container-fluid">

	<body onload="document.myForm.ku.focus();">
		<font size='3px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
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

				<div class="row hide-on-print">
					<?php include('menu_dokter.php');?>
				</div>

				<div class="row">
					<div class="col-12 text-center">
						<b>INPUT ANAMNESIS MEDIS </b><br>
					</div>
				</div>

				<hr>				
				<div class="alert alert-primary text-center" role="alert">
					<strong>Rencana Terapi:</strong> <?php echo $am77; ?>
				</div>
				<br>

				<?php 
				$tglsekarang		= gmdate("Y-m-d", time()+60*60*7);
				$waktusekarang		= gmdate("H:i:s", time()+60*60*7);
				if(empty($jamrawat)){
					$jamrawat=$waktusekarang;
					$tglrawat=$tglsekarang;
				}
				?>
				<div class="card">
					<div class="card-body">
						<div class="row align-items-center g-2">
							<label for="tglrawat" class="col-auto">Tanggal dan Jam Masuk/Mulai Asesmen:</label>
							<div class="col-auto">
								<input type="date" id="tglrawat" name="tglrawat" class="form-control" value="<?php echo $tglrawat; ?>">
							</div>
							<label for="jamrawat" class="col-auto mb-0">Jam:</label>
							<div class="col-auto">
								<input type="text" id="jamrawat" name="jamrawat" class="form-control" value="<?php echo $jamrawat; ?>">
							</div>
							<div class="col-auto hide-on-print">
								<button type="submit" name="simpan1" class="btn btn-primary" onfocus="nextfield ='done';"><i class="bi bi-save"></i> Simpan</button> 
							</div>
						</div>
					</div>
				</div>


				<br>
				<div class="row">

					<style>
						.card {
							font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
							background-color: #f8f9fa;
						}

						.text-primary {
							color: #007bff;
						}

						.text-dark {
							color: #343a40;
						}
					</style>



					<div class="col-6">

						<div class="card shadow-sm p-3 mb-3 bg-white rounded">
							<h5 class="text-primary mb-3"><b>🩺 Diagnosa</b></h5>
							<div class="diagnosa-text">
								<?php echo $diagnosa; ?>
							</div>
						</div>

						<div class="card shadow-sm p-4 mb-4 bg-white rounded">
							<h5 class="text-primary mb-4"><b>📝 Anamnesis</b></h5>

							<!-- Keluhan Utama -->
							<div class="form-group mb-3">
								<label for="am1" class="form-label">
									<b>&bull; Keluhan Utama</b>
								</label>
								<textarea class="form-control" name="am1" id="am1" rows="4" onfocus="nextfield ='';"><?php 
								if(empty($am1)){
									$am1=$keluhan_utama;
								}
								echo $am1;
							?></textarea>
						</div>

						<!-- Lama Keluhan -->
						<div class="form-group mb-3">
							<label for="am78" class="form-label">
								<b>&bull; Lama Keluhan</b>
							</label>
							<input type="text" class="form-control" name="am78" id="am78" value="<?php echo $am78; ?>">
						</div>

						<!-- Keluhan Lain -->
						<div class="form-group">
							<label for="am79" class="form-label">
								<b>&bull; Keluhan Lain</b>
							</label>
							<textarea class="form-control" name="am79" id="am79" rows="4" onfocus="nextfield ='';"><?php echo $am79;?></textarea>
						</div>
					</div>

					<div class="">
						<b>Riwayat Penyakit : </b>
						<br>
						&bull; Nama Penyakit
						&nbsp;
						<input type='radio' name='nm_rpenyakit' value='Tidak Ada' <?php if ($nm_rpenyakit=="Tidak Ada"){echo "checked";}?>>Tidak Ada
						<input type='radio' name='nm_rpenyakit' value='Ada' <?php if ($nm_rpenyakit=="Ada"){echo "checked";}?>>Ada
						<textarea class="form-control" name="am2" cols="100%" onfocus="nextfield ='';" style="min-height:80px;"><?php echo $am2;?></textarea>
						&bull; Lama Penyakit
						&nbsp;
						<input type='radio' name='lm_rpenyakit' value='Tidak Ada' <?php if ($lm_rpenyakit=="Tidak Ada"){echo "checked";}?>>Tidak Ada
						<input type='radio' name='lm_rpenyakit' value='Ada' <?php if ($lm_rpenyakit=="Ada"){echo "checked";}?>>Ada
						<input type='text' class="form-control" name='am80' value='<?php echo $am80; ?>' size='80'>

						&bull; Riwayat Keluarga
						&nbsp;
						<input type='radio' name='kl_rpenyakit' value='Tidak Ada' <?php if ($kl_rpenyakit=="Tidak Ada"){echo "checked";}?>>Tidak Ada
						<input type='radio' name='kl_rpenyakit' value='Ada' <?php if ($kl_rpenyakit=="Ada"){echo "checked";}?>>Ada
						<input type='text' class="form-control" name='am81' value='<?php echo $am81; ?>' size='80'>

						&bull; Riwayat Alergi
						&nbsp;
						<input type='radio' name='al_rpenyakit' value='Tidak Ada' <?php if ($al_rpenyakit=="Tidak Ada"){echo "checked";}?>>Tidak Ada
						<input type='radio' name='al_rpenyakit' value='Ada' <?php if ($al_rpenyakit=="Ada"){echo "checked";}?>>Ada
						<textarea class="form-control" name="am3" cols="100%" onfocus="nextfield ='';" style="min-height:70px;"><?php echo $am3;?></textarea>

						&bull; Riwayat Pengobatan
						&nbsp;
						<input type='radio' name='ob_rpenyakit' value='Tidak Ada' <?php if ($ob_rpenyakit=="Tidak Ada"){echo "checked";}?>>Tidak Ada
						<input type='radio' name='ob_rpenyakit' value='Ada' <?php if ($ob_rpenyakit=="Ada"){echo "checked";}?>>Ada
						<textarea class="form-control" name="am4" cols="100%" onfocus="nextfield ='';" style="min-height:70px;"><?php echo $am4;?></textarea>
					</div>
					<br>
					<div class="card shadow-sm p-4 mb-4 bg-white rounded">
						<h5 class="text-primary mb-3"><b>🧍‍♂️ Keadaan Umum</b></h5>

						<div class="form-group">
							<?php 
							if (empty($am5)) {
								$am5 = 'Kesadaran : ' . $kesadaran;
							}
							?>
							<textarea class="form-control" name="am5" id="am5" rows="2" onfocus="nextfield ='';"><?php echo $am5; ?></textarea>
						</div>
					</div>

					<div class="card shadow-sm mb-4">
						<div class="card-header bg-primary text-white">
							<b>🩺 Vital Sign</b>
						</div>
						<div class="card-body">
							<!-- GCS -->
							<div class="card mb-4">
								<div class="card-header bg-light">
									<b>🧠 Glasgow Coma Scale (GCS)</b>
									<?php 
									if(empty($am6)){
										$am6=$e;$am7=$v;$am8=$m;
										$am9=$tensi;$am10=$nadi;
										$am11=$ket_nadi;
										$am12=$suhu;$am13=$nafas;
										$am14=$skala_nyeri;$am15=$berat_badan;
									}
									?>
								</div>
								<div class="card-body">
									<div class="row g-3">
										<div class="col-md-4">
											<label class="form-label">Eye</label>
											<input class="form-control" name="am6" value="<?php echo $am6;?>" type="text">
										</div>
										<div class="col-md-4">
											<label class="form-label">Verbal</label>
											<input class="form-control" name="am7" value="<?php echo $am7;?>" type="text">
										</div>
										<div class="col-md-4">
											<label class="form-label">Movement</label>
											<input class="form-control" name="am8" value="<?php echo $am8;?>" type="text">
										</div>
									</div>
								</div>
							</div>

							<!-- Vital Sign -->
							<div class="row g-3">
								<div class="col-md-4">
									<label class="form-label">Tekanan Darah (mmHg)</label>
									<input class="form-control" name="am9" value="<?php echo $am9;?>" type="text">
								</div>
								<div class="col-md-4">
									<label class="form-label">Nadi (x/menit)</label>
									<input class="form-control" name="am10" value="<?php echo $am10;?>" type="text">
								</div>
								<div class="col-md-4">
									<label class="form-label d-block">Irama Nadi</label>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="am11" value="Teratur" <?php if ($am11=="Teratur") echo "checked"; ?>>
										<label class="form-check-label">Teratur</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="am11" value="Tidak Teratur" <?php if ($am11=="Abnormal") echo "checked"; ?>>
										<label class="form-check-label">Tidak Teratur</label>
									</div>
								</div>

								<div class="col-md-4">
									<label class="form-label">Suhu (°C)</label>
									<input class="form-control" name="am12" value="<?php echo $am12;?>" type="text">
								</div>
								<div class="col-md-4">
									<label class="form-label">Frekuensi Pernafasan (x/menit)</label>
									<input class="form-control" name="am13" value="<?php echo $am13;?>" type="text">
								</div>
								<div class="col-md-4">
									<label class="form-label">Saturasi O2 (SpO2)</label>
									<input class="form-control" name="spo2" value="<?php echo $spo2;?>" type="text">
								</div>
								<div class="col-md-4">
									<label class="form-label">Skala Nyeri</label>
									<input class="form-control" name="am14" value="<?php echo $am14;?>" type="text">
								</div>
								<div class="col-md-4">
									<label class="form-label">Berat Badan (Kg)</label>
									<input class="form-control" name="am15" value="<?php echo $am15;?>" type="text">
								</div>
							</div>
						</div>
					</div>

					<div class="card shadow-sm mb-4">
						<div class="card-header bg-info text-white">
							<b>🧠 Anamnesa Psikologi / Sosial / Ekonomi</b>
						</div>
						<div class="card-body">
							<!-- Kondisi Kejiwaan -->
							<div class="form-group mb-3">
								<label class="form-label"><b>Kondisi Kejiwaan:</b></label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="am16" value="Tenang" <?php if ($am16=="Tenang") echo "checked"; ?>>
									<label class="form-check-label">Tenang</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="am16" value="Gelisah/Takut" <?php if ($am16=="Gelisah/Takut") echo "checked"; ?>>
									<label class="form-check-label">Gelisah/Takut</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="am16" value="Bingung" <?php if ($am16=="Bingung") echo "checked"; ?>>
									<label class="form-check-label">Bingung</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="am16" value="Stres" <?php if ($am16=="Stres") echo "checked"; ?>>
									<label class="form-check-label">Stres</label>
								</div>
							</div>

							<!-- Sosial Ekonomi -->
							<div class="form-group mb-3">
								<label class="form-label"><b>Sosial Ekonomi:</b></label>
								<input class="form-control" name="am17" value="<?php echo $am17; ?>" type="text" placeholder="Contoh: Cukup / Kurang / Tidak Bekerja">
							</div>

							<!-- Spiritual -->
							<div class="form-group">
								<label class="form-label"><b>Spiritual:</b></label>
								<input class="form-control" name="am18" value="<?php echo $am18; ?>" type="text" placeholder="Contoh: Agamis / Tidak Agamis / Rutin Beribadah">
							</div>
						</div>
					</div>


					<div class="card shadow-sm mb-4">
						<div class="card-header bg-success text-white">
							<b>✅ Verifikasi Dokter Pemeriksa</b>
						</div>
						<div class="card-body">
							<div class="form-group mb-3">
								<label for="dokter" class="form-label"><b>Nama / Kode Dokter:</b></label>
								<input type="text" class="form-control" name="am75" id="dokter" value="<?php echo $am75;?>" placeholder="Isikan Nama Dokter atau Kode Dokter">
							</div>

							<?php 
							if($am75){
								$verif_dokter = "Form Anamnesis Medis ini telah diVerifikasi Oleh: ".$am75." Pada Tanggal: ".$tgl; 
								QRcode::png($verif_dokter, "image.png", "L", 2, 2);   
								echo "<div class='text-center'>";
								echo "<img src='image.png' class='img-thumbnail mt-3' alt='QR Verifikasi Dokter'>";
								echo "<p class='mt-2 verifikasi-text'><small><i>".$verif_dokter."</i></small></p>";
								echo "</div>";
							}
							?>
						</div>
					</div>


				</div>
				<div class="col-6">	

					<div class="card shadow-sm mb-4">
						<div class="card-header bg-success text-white">
							<b>🩻 Pemeriksaan Fisik</b><br>
							<small><i>Isikan pada bagian-bagian yang diperiksa</i></small>
						</div>
						<div class="card-body">

							<div class="card">
								<div class="card-body">
									<label for="" class="col-3">Kepala : </label>
									<input type='radio' name='am19' value='Normal' <?php if ($am19=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am19' value='Abnormal' <?php if ($am19=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am20" value="<?php $am20;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am20"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am19"][value="Abnormal"]').checked = true;
											}
											else { 
												document.querySelector('[name="am19"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>
									<label for="" class="col-3">Mata : </label>
									<input type='radio' name='am21' value='Normal' <?php if ($am21=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am21' value='Abnormal' <?php if ($am21=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am22" value="<?php echo $am22;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am22"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am21"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am21"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>	
									<label for="" class="col-3">Telinga : </label>
									<input type='radio' name='am23' value='Normal' <?php if ($am23=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am23' value='Abnormal' <?php if ($am23=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am24" value="<?php echo $am24;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am24"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am23"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am23"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>								
									<label for="" class="col-3">Hidung : </label>
									<input type='radio' name='am25' value='Normal' <?php if ($am25=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am25' value='Abnormal' <?php if ($am25=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am26" value="<?php echo $am26;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am26"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am25"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am25"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>							
									<label for="" class="col-3">Rambut : </label>
									<input type='radio' name='am27' value='Normal' <?php if ($am27=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am27' value='Abnormal' <?php if ($am27=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am28" value="<?php echo $am28;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am28"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am27"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am27"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>	
									<label for="" class="col-3">Bibir : </label>
									<input type='radio' name='am29' value='Normal' <?php if ($am29=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am29' value='Abnormal' <?php if ($am29=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am30" value="<?php echo $am30;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am30"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am29"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am29"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>	
									<label for="" class="col-3">Gigi Geligi : </label>
									<input type='radio' name='am31' value='Normal' <?php if ($am31=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am31' value='Abnormal' <?php if ($am31=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am32" value="<?php echo $am32;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am32"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am31"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am31"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>	
									<label for="" class="col-3">Lidah : </label>
									<input type='radio' name='am33' value='Normal' <?php if ($am33=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am33' value='Abnormal' <?php if ($am33=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am34" value="<?php echo $am34;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am34"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am33"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am33"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>	
									<label for="" class="col-3">Langit-langit : </label>
									<input type='radio' name='am35' value='Normal' <?php if ($am35=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am35' value='Abnormal' <?php if ($am35=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am36" value="<?php echo $am36;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am36"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am35"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am35"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>	
									<label for="" class="col-3">Leher : </label>
									<input type='radio' name='am37' value='Normal' <?php if ($am37=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am37' value='Abnormal' <?php if ($am37=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am38" value="<?php echo $am38;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am38"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am37"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am37"][value="Abnormal"]').checked = false;
											}
										});
									</script>

									<br>	
									<label for="" class="col-3">Tenggorokan : </label>
									<input type='radio' name='am39' value='Normal' <?php if ($am39=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am39' value='Abnormal' <?php if ($am39=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am40" value="<?php echo $am40;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">

									<script>
										document.querySelector('[name="am40"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am39"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am39"][value="Abnormal"]').checked = false;
											}
										});
									</script>


									<br>	
									<label for="" class="col-3">Tonsil : </label>
									<input type='radio' name='am41' value='Normal' <?php if ($am41=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am41' value='Abnormal' <?php if ($am41=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am42" value="<?php echo $am42;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am42"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am41"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am41"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Dada : </label>
									<input type='radio' name='am43' value='Normal' <?php if ($am43=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am43' value='Abnormal' <?php if ($am43=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am44" value="<?php echo $am44;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am44"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am43"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am43"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Payudara : </label>
									<input type='radio' name='am45' value='Normal' <?php if ($am45=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am45' value='Abnormal' <?php if ($am45=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am46" value="<?php echo $am46;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am46"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am45"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am45"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Punggung : </label>
									<input type='radio' name='am47' value='Normal' <?php if ($am47=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am47' value='Abnormal' <?php if ($am47=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am48" value="<?php echo $am48;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am48"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am47"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am47"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Perut : </label>
									<input type='radio' name='am49' value='Normal' <?php if ($am49=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am49' value='Abnormal' <?php if ($am49=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am50" value="<?php echo $am50;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am50"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am49"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am49"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Genital : </label>
									<input type='radio' name='am51' value='Normal' <?php if ($am51=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am51' value='Abnormal' <?php if ($am51=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am52" value="<?php echo $am52;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am52"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am51"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am51"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Anus/Dubur : </label>
									<input type='radio' name='am53' value='Normal' <?php if ($am53=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am53' value='Abnormal' <?php if ($am53=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am54" value="<?php echo $am54;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am54"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am53"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am53"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Lengan Atas : </label>
									<input type='radio' name='am55' value='Normal' <?php if ($am55=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am55' value='Abnormal' <?php if ($am55=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am56" value="<?php echo $am56;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am56"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am55"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am55"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Lengan Bawah : </label>
									<input type='radio' name='am57' value='Normal' <?php if ($am57=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am57' value='Abnormal' <?php if ($am57=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am58" value="<?php echo $am58;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am58"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am57"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am57"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Jari Tangan : </label>
									<input type='radio' name='am59' value='Normal' <?php if ($am59=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am59' value='Abnormal' <?php if ($am59=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am60" value="<?php echo $am60;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am60"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am59"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am59"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Kuku Tangan : </label>
									<input type='radio' name='am61' value='Normal' <?php if ($am61=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am61' value='Abnormal' <?php if ($am61=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am62" value="<?php echo $am62;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am62"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am61"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am61"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Persendian Tangan : </label>
									<input type='radio' name='am63' value='Normal' <?php if ($am63=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am63' value='Abnormal' <?php if ($am63=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am64" value="<?php echo $am64;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am64"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am63"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am63"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Tungkai Atas : </label>
									<input type='radio' name='am65' value='Normal' <?php if ($am65=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am65' value='Abnormal' <?php if ($am65=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am66" value="<?php echo $am66;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am66"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am65"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am65"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Tungkai Bawah : </label>
									<input type='radio' name='am67' value='Normal' <?php if ($am67=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am67' value='Abnormal' <?php if ($am67=="fisik49"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am68" value="<?php echo $am68;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am68"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am67"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am67"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Jari Kaki : </label>
									<input type='radio' name='am69' value='Normal' <?php if ($am69=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am69' value='Abnormal' <?php if ($am69=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am70" value="<?php echo $am70;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am70"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am69"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am69"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Kuku Kaki : </label>
									<input type='radio' name='am71' value='Normal' <?php if ($am71=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am71' value='Abnormal' <?php if ($am71=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am72" value="<?php echo $am72;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am72"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am71"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am71"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>	
									<label for="" class="col-3">Persendian Kaki : </label>
									<input type='radio' name='am73' value='Normal' <?php if ($am73=="Normal"){echo "checked";}?>>Normal
									<input type='radio' name='am73' value='Abnormal' <?php if ($am73=="Abnormal"){echo "checked";}?>>Abnormal
									<input class="form-control-sm" name="am74" value="<?php echo $am74;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="Keterangan" style="min-width:300px; min-height:50px;">
									<script>
										document.querySelector('[name="am74"]').addEventListener('input', function() {
											if (this.value.trim() !== '') {
												document.querySelector('[name="am73"][value="Abnormal"]').checked = true;
											}else { 
												document.querySelector('[name="am73"][value="Abnormal"]').checked = false;
											}
										});
									</script>
									<br>									
								</div>

							</div>
							<br>
							<div class="card shadow-sm mb-4">
								<div class="card-header bg-primary text-white">
									<b>📝 Assesmen Lanjutan & Terapi</b>
								</div>
								<div class="card-body">

									<!-- Penunjang -->
									<div class="mb-3">
										<label for="am76" class="form-label"><b>🔬 Pemeriksaan Penunjang</b></label>
										<textarea class="form-control" name="am76" id="am76" style="min-height:100px;"><?php echo $am76;?></textarea>
									</div>

									<!-- Assesment -->
									<div class="mb-3">
										<label for="assesment" class="form-label"><b>📋 Assessment</b></label>
										<textarea class="form-control" name="assesment" id="assesment" style="min-height:100px;"><?php echo $assesment;?></textarea>
									</div>

									<!-- Diagnosa & Planning -->
									<div class="mb-3">
										<label for="diagnosa_planning" class="form-label"><b>🧠 Diagnosa Planning</b></label>
										<textarea class="form-control" name="diagnosa_planning" id="diagnosa_planning" style="min-height:100px;"><?php echo $diagnosa_planning;?></textarea>
									</div>

									<!-- Rencana Terapi -->
									<div class="mb-3">
										<label for="am77" class="form-label"><b>💊 Rencana Terapi</b></label>
										<textarea class="form-control" name="am77" id="am77" style="min-height:250px;"><?php echo $am77;?></textarea>
									</div>

								</div>
							</div>


						</div>

					</div>
				</div>				
			</div>

			<?php 
			$tglsekarang2		= gmdate("Y-m-d", time()+60*60*7);
			$waktusekarang2		= gmdate("H:i:s", time()+60*60*7);
			if(empty($jamselesai)){
				$jamselesai=$waktusekarang2;
				$tglselesai=$tglsekarang2;
			}
			?>

			<div class="card">
				<div class="card-body">
					<div class="row align-items-center g-2">
						<label for="tglrawat" class="col-auto">Tanggal dan Jam Selesai Asesmen:</label>
						<div class="col-auto">
							<input type="date" id="tglselesai" name="tglselesai" class="form-control" value="<?php echo $tglselesai; ?>">
						</div>
						<label for="jamrawat" class="col-auto mb-0">Jam:</label>
						<div class="col-auto">
							<input type="text" id="jamselesai" name="jamselesai" class="form-control" value="<?php echo $jamselesai; ?>">
						</div>
						<div class="col-auto hide-on-print">
							<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';"><i class="bi bi-save"></i> Simpan</button> 
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
if (isset($_POST["simpan1"])) {
	$lanjut='Y';
	$jamrawat	= $_POST["jamrawat"];
	$tglrawat	= $_POST["tglrawat"];

	if($lanjut == 'Y'){

		$q  = "update ERM_RI_ANAMNESIS_MEDIS set
		jamrawat='$jamrawat',tglrawat='$tglrawat'
		where noreg='$noreg'
		";
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			$eror = "Berhasil update Mulai Asesmen";
		}else{
			$eror = "Gagal Insert";

		}

	}else{

	}

	echo "
	<script>
	alert('".$eror."');
	window.location.replace('anamnesis_medis.php?id=$id|$user');
	</script>
	";

}

if (isset($_POST["simpan"])) {

	$lanjut='Y';

	// $tgl	= $_POST["tgl"];
	$jamrawat	= $_POST["jamrawat"];
	$tglrawat	= $_POST["tglrawat"];

	$tglselesai	= $_POST["tglselesai"];
	$jamselesai	= $_POST["jamselesai"];

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
	$am77 = str_replace("'","`",$am77);	
	
	$diagnosa_planning = $_POST['diagnosa_planning'];
	$diagnosa_planning = str_replace("'","`",$diagnosa_planning);	

	$assesment = $_POST['assesment'];
	$assesment = str_replace("'","`",$assesment);

	$spo2 = $_POST['spo2'];

	$am78 = $_POST['am78'];
	$am79 = $_POST['am79'];
	$am80 = $_POST['am80'];
	$am81 = $_POST['am81'];
	$am82 = $_POST['am82'];
	$am83 = $_POST['am83'];
	$am84 = $_POST['am84'];
	$am85 = $_POST['am85'];
	$am86 = $_POST['am86'];
	$am87 = $_POST['am87'];
	$am88 = $_POST['am88'];
	$am89 = $_POST['am89'];
	$am90 = $_POST['am90'];
	$nm_rpenyakit = $_POST['nm_rpenyakit'];
	$lm_rpenyakit = $_POST['lm_rpenyakit'];
	$kl_rpenyakit = $_POST['kl_rpenyakit'];
	$al_rpenyakit = $_POST['al_rpenyakit'];
	$ob_rpenyakit = $_POST['ob_rpenyakit'];

	if($nm_rpenyakit=='Tidak Ada'){
		$am2='Tidak Ada';
	}
	if($lm_rpenyakit=='Tidak Ada'){
		$am80='Tidak Ada';
	}
	if($kl_rpenyakit=='Tidak Ada'){
		$am81='Tidak Ada';
	}
	if($al_rpenyakit=='Tidak Ada'){
		$am3='DISANGKAL';
	}
	if($ob_rpenyakit=='Tidak Ada'){
		$am4='Tidak Ada';	
	}

	$am20 = str_replace("'","`",$am20);
	$am22 = str_replace("'","`",$am22);
	$am24 = str_replace("'","`",$am24);
	$am26 = str_replace("'","`",$am26);
	$am28 = str_replace("'","`",$am28);
	$am30 = str_replace("'","`",$am30);
	$am34 = str_replace("'","`",$am34);
	$am36 = str_replace("'","`",$am36);
	$am38 = str_replace("'","`",$am38);
	$am40 = str_replace("'","`",$am40);
	$am42 = str_replace("'","`",$am42);
	$am44 = str_replace("'","`",$am44);
	$am46 = str_replace("'","`",$am46);
	$am48 = str_replace("'","`",$am48);
	$am50 = str_replace("'","`",$am50);
	$am54 = str_replace("'","`",$am54);
	$am56 = str_replace("'","`",$am56);
	$am58 = str_replace("'","`",$am58);
	$am60 = str_replace("'","`",$am60);
	$am62 = str_replace("'","`",$am62);
	$am64 = str_replace("'","`",$am64);
	$am66 = str_replace("'","`",$am66);
	$am68 = str_replace("'","`",$am68);
	$am70 = str_replace("'","`",$am70);
	$am72 = str_replace("'","`",$am72);
	$am74 = str_replace("'","`",$am74);


	if(empty($am77)){
		$eror='Rencana Terapi Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($spo2)){
		// $eror='SPO2 Tidak Boleh Kosong !!!';
		// $lanjut='T';
	}

	if($lanjut == 'Y'){

		$q  = "update ERM_RI_ANAMNESIS_MEDIS set
		tgl='$tgl',
		jamrawat='$jamrawat',tglrawat='$tglrawat',
		tglselesai='$tglselesai',jamselesai='$jamselesai',
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
		am80 = '$am80',
		am81 = '$am81',
		am82 = '$am82',
		am83 = '$am83',
		am84 = '$am84',
		am85 = '$am85',
		am86 = '$am86',
		am87 = '$am87',
		am88 = '$am88',
		am89 = '$am89',
		am90 = '$am90',
		nm_rpenyakit = '$nm_rpenyakit',
		lm_rpenyakit = '$lm_rpenyakit',
		kl_rpenyakit = '$kl_rpenyakit',
		al_rpenyakit = '$al_rpenyakit',
		ob_rpenyakit = '$ob_rpenyakit',
		diagnosa_planning = '$diagnosa_planning',
		assesment = '$assesment',
		spo2 = '$spo2'
		where noreg='$noreg'
		";
		$hs = sqlsrv_query($conn,$q);

// Hasil untuk tes
		$hs = true;
		$eror = $hs ? "Periksa lagi data yang telah dimasukkan!" : "Gagal Insert";

	}else{

	}


	// echo "
	// <script>
	// alert('".$eror."');
	// window.location.replace('anamnesis_medis.php?id=$id|$user');
	// </script>
	// ";

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";


	?>

	<!-- Panggil SweetAlert CDN -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
// Tampilkan SweetAlert lengkap
Swal.fire({
	title: '<?php echo ($hs ? "Berhasil!" : "Gagal!"); ?>',
	html: `
	<?php echo $eror; ?><br><br>
	<b>Waktu Mulai Asesmen :</b> <?php echo $tglrawat . " " . $jamrawat; ?><br>
	<b>Waktu Selesai Asesmen :</b> <?php echo $tglselesai . " " . $jamselesai; ?><br>
	`,
	icon: '<?php echo ($hs ? "success" : "error"); ?>',
	confirmButtonText: 'OK'
}).then((result) => {
	if (result.isConfirmed) {
		window.location.replace('anamnesis_medis.php?id=<?php echo "$id|$user"; ?>');
	}
});
</script>

<?php
}


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>
