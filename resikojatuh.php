<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);

if ($KET1 == 'RSPG'){
	$nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
	$alamat = "Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik";
};
if ($KET1 == 'GRAHU'){
	$nmrs = "RUMAH SAKIT GRHA HUSADA";
	$alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
};
if ($KET1 == 'DRIYO'){
	$nmrs = "RUMAH SAKIT DRIYOREJO";
	$alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
};

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

//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];

//select data

$qi="SELECT noreg FROM ERM_RI_RJATUH where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(!empty($regcek)){

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 20) as tgl
	FROM ERM_RI_RJATUH
	where noreg='$noreg' order by id desc";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 

	$tgl = $de['tgl'];
	$ob31 = $de['ob31'];
	$ob32 = $de['ob32'];
	$ob33 = $de['ob33'];
	$ob34 = $de['ob34'];
	$ob35 = $de['ob35'];
	$ob36 = $de['ob36'];
	$ob37 = $de['ob37'];
	$ob38 = $de['ob38'];
	$ob39 = $de['ob39'];
	$ob40 = $de['ob40'];
	$ob41 = $de['ob41'];
	$ob42 = $de['ob42'];
	$ob43 = $de['ob43'];
	$ob44 = $de['ob44'];

	$asanak149 = $de['asanak149'];
	$asanak150 = $de['asanak150'];
	$asanak151 = $de['asanak151'];
	$asanak152 = $de['asanak152'];
	$asanak153 = $de['asanak153'];
	$asanak154 = $de['asanak154'];
	$asanak155 = $de['asanak155'];
	$asanak156 = $de['asanak156'];
	$asanak157 = $de['asanak157'];
	$asanak158 = $de['asanak158'];
	$asanak159 = $de['asanak159'];
	$asanak160 = $de['asanak160'];
	$asanak161 = $de['asanak161'];
	$asanak162 = $de['asanak162'];
	$asanak163 = $de['asanak163'];
	$asanak164 = $de['asanak164'];
	$asanak165 = $de['asanak165'];
	$asanak166 = $de['asanak166'];
	$asanak167 = $de['asanak167'];
	$asanak168 = $de['asanak168'];
	$asanak169 = $de['asanak169'];
	$asanak170 = $de['asanak170'];



}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Resume Medis</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

	<!-- Jqueri autocomplete untuk procedure !!! -->
	<link rel="stylesheet" href="jquery-ui.css">
	<script src="jquery-1.10.2.js"></script>
	<script src="jquery-ui.js"></script>

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


	<script>
		$(function() {
			$("#obat").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.KODEBARANG + ' - ' + item.NAMABARANG + ' - ' + item.NAMASATUAN
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
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.nama_obat.focus();">
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
					<br>
					<br>
<!-- 				<div class="row">
					<div class="col-12 text-center bg-success text-white"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
				</div>
			-->				
			<div class="row">
			</div>

			<div class="row">
				<div class="col-6">
					<h5><b><?php echo $nmrs; ?></b></h5>
					<?php echo $alamat; ?>
				</div>
				<div class="col-6">
					<?php echo 'NIK : '.$noktp.'<br>'; ?>					
					<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
					<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col-12 text-center">
					<b>ASSESMEN RESIKO JATUH</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Tanggal Jam
							</div>
							<div class="col-8">
								: <input class="" name="tgl" value="<?php echo $tgl;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							
							<div class="col-12">
								<table border='0' width="100%">
									<tr>
										<td colspan="2"><b>Monitoring Risiko Jatuh</b></td>
									</tr>
								</table>
								<?php if($umur >= 17) { ?>

									<table>
										<tr>
											<td style="border: 1px solid;">Faktor Risiko</td>
											<td style="border: 1px solid;">skala</td>
											<td style="border: 1px solid;">poin</td>
											<td style="border: 1px solid;">Skor pasien</td>
										</tr>

										<tr>
											<td style="border: 1px solid;">Riwayat jatuh</td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob31' value='YA' <?php if ($ob31=="YA"){echo "checked";}?>>Ya</td>
											<td style="border: 1px solid;">25</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob31){
													echo $tjatuh1_skor='25';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob32' value='YA' <?php if ($ob32=="YA"){echo "checked";}?>>Tidak</td>
											<td style="border: 1px solid;">0</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob32){
													echo $tjatuh2_skor='0';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;">Diagnosis skunder (≥diagnosis medis)</td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob33' value='YA' <?php if ($ob33=="YA"){echo "checked";}?>>Ya</td>
											<td style="border: 1px solid;">15</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob33){
													echo $tjatuh3_skor='15';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob34' value='YA' <?php if ($ob34=="YA"){echo "checked";}?>>Tidak</td>
											<td style="border: 1px solid;">0</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob34){
													echo $tjatuh4_skor='0';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;">Alat bantu</td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob35' value='YA' <?php if ($ob35=="YA"){echo "checked";}?>>Berpegangan pada perabot, kursi roda</td>
											<td style="border: 1px solid;">30</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob35){
													echo $tjatuh5_skor='30';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob36' value='YA' <?php if ($ob36=="YA"){echo "checked";}?>>Tongkat/walker</td>
											<td style="border: 1px solid;">15</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob36){
													echo $tjatuh6_skor='15';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob37' value='YA' <?php if ($ob37=="YA"){echo "checked";}?>>Tidak ada/perawat/tirah baring</td>
											<td style="border: 1px solid;">0</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob37){
													echo $tjatuh7_skor='0';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;">Terpasang infus/terapi intravena</td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob38' value='YA' <?php if ($ob38=="YA"){echo "checked";}?>>Ya</td>
											<td style="border: 1px solid;">20</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob38){
													echo $tjatuh8_skor='20';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob39' value='YA' <?php if ($ob39=="YA"){echo "checked";}?>>Tidak</td>
											<td style="border: 1px solid;">0</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob39){
													echo $tjatuh9_skor='0';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;">Gaya berjalan</td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob40' value='YA' <?php if ($ob40=="YA"){echo "checked";}?>>Kerusakan</td>
											<td style="border: 1px solid;">20</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob40){
													echo $tjatuh10_skor='20';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob41' value='YA' <?php if ($ob41=="YA"){echo "checked";}?>>Kelemahan</td>
											<td style="border: 1px solid;">10</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob41){
													echo $tjatuh11_skor='10';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob42' value='YA' <?php if ($ob42=="YA"){echo "checked";}?>>Normal /tirah baring/imobilisasi</td>
											<td style="border: 1px solid;">0</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob42){
													echo $tjatuh12_skor='0';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;">Status mental</td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob43' value='YA' <?php if ($ob43=="YA"){echo "checked";}?>>Tidak konsisten dengan perintah</td>
											<td style="border: 1px solid;">15</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob43){
													echo $tjatuh13_skor='15';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='ob44' value='YA' <?php if ($ob44=="YA"){echo "checked";}?>>Sadar akan kemampuan diri sendiri</td>
											<td style="border: 1px solid;">0</td>
											<td style="border: 1px solid;">
												<?php 
												if($ob44){
													echo $tjatuh14_skor='0';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;" colspan="3">Total Skor</td>
											<td style="border: 1px solid;">
												<?php 
												echo $tjatuh_skor_total=$tjatuh1_skor+$tjatuh2_skor+$tjatuh3_skor+$tjatuh4_skor+$tjatuh5_skor+$tjatuh6_skor+$tjatuh7_skor+$tjatuh8_skor+$tjatuh9_skor+$tjatuh10_skor+$tjatuh11_skor+$tjatuh12_skor+$tjatuh13_skor+$tjatuh14_skor; 
												?>
											</td>
										</tr>

									</table>
									<br>
									[] >45 : Risiko tinggi ;         [] 25-44 : Risiko sedang ;         [] 0 -24 : Risiko rendah
									<br>
									<?php 
									echo "<h5>";
									echo "[".$tjatuh_skor_total."]";

									if($tjatuh_skor_total >= 0 and $tjatuh_skor_total <= 24){echo $tjatuh_skor_total="Risiko rendah";}
									if($tjatuh_skor_total >= 25 and $tjatuh_skor_total <= 44 ){echo $tjatuh_skor_total="Risiko sedang";}
									if($tjatuh_skor_total >= 45){echo $tjatuh_skor_total_ket="Risiko tinggi";}
									echo "</h5>";
									?>

								<?php } ?>

								<?php if($umur<17) {?>
									(dewasa dengan skala humpty dumpty)<br>

									<table>
										<tr>
											<td style="border: 1px solid;">Faktor Risiko</td>
											<td style="border: 1px solid;">skala</td>
											<td style="border: 1px solid;">poin</td>
											<td style="border: 1px solid;">Skor pasien</td>
										</tr>

										<tr>
											<td style="border: 1px solid;">Umur</td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak149' value='YA' <?php if ($asanak149=="YA"){echo "checked";}?>>Kurang dari 3 tahun</td>
											<td style="border: 1px solid;">4</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak149){
													echo $tjatuh1_skor='4';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak150' value='YA' <?php if ($asanak150=="YA"){echo "checked";}?>>3 tahun – 7 tahun</td>
											<td style="border: 1px solid;">3</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak150){
													echo $tjatuh2_skor='3';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak151' value='YA' <?php if ($asanak151=="YA"){echo "checked";}?>>7 tahun – 13 tahun</td>
											<td style="border: 1px solid;">2</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak151){
													echo $tjatuh3_skor='2';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak152' value='YA' <?php if ($asanak152=="YA"){echo "checked";}?>>Lebih dari 13</td>
											<td style="border: 1px solid;">1</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak152){
													echo $tjatuh4_skor='1';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;">Jenis kelamin</td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak153' value='YA' <?php if ($asanak153=="YA"){echo "checked";}?>>Laki – laki</td>
											<td style="border: 1px solid;">2</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak153){
													echo $tjatuh5_skor='2';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak154' value='YA' <?php if ($asanak154=="YA"){echo "checked";}?>>Wanita</td>
											<td style="border: 1px solid;">1</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak154){
													echo $tjatuh6_skor='1';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;">Diagnosa</td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak155' value='YA' <?php if ($asanak155=="YA"){echo "checked";}?>>neorologi</td>
											<td style="border: 1px solid;">4</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak155){
													echo $tjatuh7_skor='4';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak156' value='YA' <?php if ($asanak156=="YA"){echo "checked";}?>>Respiratori, dehidrasi, anemia, anorexia, syncope</td>
											<td style="border: 1px solid;">3</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak156){
													echo $tjatuh8_skor='3';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak157' value='YA' <?php if ($asanak157=="YA"){echo "checked";}?>>Lain-lain</td>
											<td style="border: 1px solid;">1</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak157){
													echo $tjatuh9_skor='1';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;">Gangguan kognitif</td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak158' value='YA' <?php if ($asanak158=="YA"){echo "checked";}?>>Keterbatasan daya pikir</td>
											<td style="border: 1px solid;">3</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak158){
													echo $tjatuh10_skor='3';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak159' value='YA' <?php if ($tjatuh7=="YA"){echo "checked";}?>>Pelupa</td>
											<td style="border: 1px solid;">2</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak159){
													echo $tjatuh11_skor='2';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak160' value='YA' <?php if ($asanak160=="YA"){echo "checked";}?>>Dapat menggunakan daya pikir tanpa hambatan</td>
											<td style="border: 1px solid;">1</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak160){
													echo $tjatuh12_skor='1';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;">Faktor lingkungan</td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak161' value='YA' <?php if ($asanak161=="YA"){echo "checked";}?>>Riwayat jatuh atau bayi / balita yang ditempatkan di tempat tidur</td>
											<td style="border: 1px solid;">4</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak161){
													echo $tjatuh13_skor='4';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak162' value='YA' <?php if ($asanak162=="YA"){echo "checked";}?>>Pasien menggunakan alat bantu/bayi balita dalam ayunan</td>
											<td style="border: 1px solid;">3</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak162){
													echo $tjatuh14_skor='3';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak163' value='YA' <?php if ($asanak163=="YA"){echo "checked";}?>>Pasien ditempat tidur standart</td>
											<td style="border: 1px solid;">2</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak163){
													echo $tjatuh15_skor='2';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak164' value='YA' <?php if ($asanak164=="YA"){echo "checked";}?>>Area pasien rawat jalan</td>
											<td style="border: 1px solid;">1</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak164){
													echo $tjatuh16_skor='1';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;">Respon terhadap pembedahan, sedasi, dan anestesi</td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak165' value='YA' <?php if ($asanak165=="YA"){echo "checked";}?>>Dalam 24 jam</td>
											<td style="border: 1px solid;">3</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak165){
													echo $tjatuh17_skor='3';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak166' value='YA' <?php if ($asanak166=="YA"){echo "checked";}?>>Dalam 48 jam</td>
											<td style="border: 1px solid;">2</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak166){
													echo $tjatuh18_skor='2';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak167' value='YA' <?php if ($asanak167=="YA"){echo "checked";}?>>Lebih dari 48 jam/ tidak ada respon</td>
											<td style="border: 1px solid;">1</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak167){
													echo $tjatuh19_skor='1';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;">Penggunaan obat-obatan</td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak168' value='YA' <?php if ($asanak168=="YA"){echo "checked";}?>>Penggunaan bersamaan sedative, barbiturate, anti depresan, diuretik, narkotik</td>
											<td style="border: 1px solid;">3</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak168){
													echo $tjatuh20_skor='3';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak169' value='YA' <?php if ($asanak169=="YA"){echo "checked";}?>>Salah satu dari obat diatas</td>
											<td style="border: 1px solid;">2</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak169){
													echo $tjatuh21_skor='2';
												}
												?>
											</td>
										</tr>
										<tr>
											<td style="border: 1px solid;"></td>
											<td style="border: 1px solid;"><input type='checkbox' name='asanak170' value='YA' <?php if ($asanak170=="YA"){echo "checked";}?>>Obat-obatan lainnya/tanpa obat</td>
											<td style="border: 1px solid;">1</td>
											<td style="border: 1px solid;">
												<?php 
												if($asanak170){
													echo $tjatuh22_skor='1';
												}
												?>
											</td>
										</tr>

										<tr>
											<td style="border: 1px solid;" colspan="3">Total Skor</td>
											<td style="border: 1px solid;">
												<?php 
												echo $tjatuh_skor_total=$tjatuh1_skor+$tjatuh2_skor+$tjatuh3_skor+$tjatuh4_skor+$tjatuh5_skor+$tjatuh6_skor+$tjatuh7_skor+$tjatuh8_skor+$tjatuh9_skor+$tjatuh10_skor+$tjatuh11_skor+$tjatuh12_skor+$tjatuh13_skor+$tjatuh14_skor+$tjatuh15_skor+$tjatuh16_skor+$tjatuh17_skor+$tjatuh18_skor+$tjatuh19_skor+$tjatuh20_skor+$tjatuh21_skor+$tjatuh22_skor; 
												?>
											</td>
										</tr>

									</table>
									<!-- <input type='submit' name='simpan' value='simpan'>&nbsp; -->

									<br>
									[] >12 : Risiko tinggi ;         [] 7-11 : Risiko sedang ;         [] 0 -6 : Risiko rendah
									<br>
									<?php 
									echo "<h5>";
									echo "[".$tjatuh_skor_total."]";

									if($tjatuh_skor_total >= 0 and $tjatuh_skor_total <= 6){echo $tjatuh_skor_total="Risiko rendah";}
									if($tjatuh_skor_total >= 7 and $tjatuh_skor_total <= 11 ){echo $tjatuh_skor_total="Risiko sedang";}
									if($tjatuh_skor_total >= 12){echo $tjatuh_skor_total_ket="Risiko tinggi";}
									echo "</h5>";

									?>

								<?php } ?>	

							</div>
						</div>

					</div>
				</td>
			</tr>

			<tr>
				<td>
					<div class="row">
						<div class="col-4">
							&nbsp;
						</div>
						<div class="col-8">
							&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' onfocus="nextfield ='done';" style="color: white;background: #66CDAA;border-color: #66CDAA;">
						</div>
					</div>
				</td>
			</tr>	
		</table>
		<br>

		<br>
		<table width="100%">
			<tr>
				<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
				<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>tgl & jam</font></td>
				<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>ket</font></td>
				<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>user - pasien</font></td>				
				<td align='center' style="border: 1px solid;" bgcolor='#708090'><font color='white'>aksi</font></td>
			</tr>
			<?php 
			$q="
			select TOP(100) userid,ket,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
			from ERM_RI_RJATUH
			where noreg='$noreg' order by id desc
			";
			$hasil  = sqlsrv_query($conn, $q);  
			$no=1;
			while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
				echo "
				<tr>
				<td>$no</td>
				<td>$data[tglentry]</td>
				<td>$data[ket]</td>
				<td>$data[userid] - $nama</td>
				<td align='center'><a href='del_rjatuh.php?id=$id|$user|$data[id]'><font color='red'>[x]</font></a></td>
				</tr>
				";
				$no += 1;

			}


			?>
		</table>


		<br>
		<br>
		<br>
		<br>
		<br>
	</form>
</font>
</body>
</div>
</div>

<?php 


if (isset($_POST["simpan"])) {

	$tgl	= $_POST["tgl"];
	$nama_obat	= $_POST["nama_obat"];
	$dosis	= $_POST["dosis"];
	$waktu_penggunaan	= $_POST["waktu_penggunaan"];
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

	$asanak149 = $_POST['asanak149'];
	$asanak150 = $_POST['asanak150'];
	$asanak151 = $_POST['asanak151'];
	$asanak152 = $_POST['asanak152'];
	$asanak153 = $_POST['asanak153'];
	$asanak154 = $_POST['asanak154'];
	$asanak155 = $_POST['asanak155'];
	$asanak156 = $_POST['asanak156'];
	$asanak157 = $_POST['asanak157'];
	$asanak158 = $_POST['asanak158'];
	$asanak159 = $_POST['asanak159'];
	$asanak160 = $_POST['asanak160'];
	$asanak161 = $_POST['asanak161'];
	$asanak162 = $_POST['asanak162'];
	$asanak163 = $_POST['asanak163'];
	$asanak164 = $_POST['asanak164'];
	$asanak165 = $_POST['asanak165'];
	$asanak166 = $_POST['asanak166'];
	$asanak167 = $_POST['asanak167'];
	$asanak168 = $_POST['asanak168'];
	$asanak169 = $_POST['asanak169'];
	$asanak170 = $_POST['asanak170'];

	$q  = "insert into ERM_RI_RJATUH(noreg,userid,tglentry,tgl,ob31,ob32,ob33,ob34,ob35,ob36,ob37,ob38,ob39,ob40,ob41,ob42,ob43,ob44,ket,nilai,
	asanak149,asanak150,asanak151,asanak152,asanak153,asanak154,asanak155,asanak156,asanak157,asanak158,asanak159,asanak160,
	asanak161,asanak162,asanak163,asanak164,asanak165,asanak166,asanak167,asanak168,asanak169,asanak170
	) 
	values ('$noreg','$user','$tgl','$tgl','$ob31','$ob32','$ob33','$ob34','$ob35','$ob36','$ob37','$ob38','$ob39','$ob40','$ob41','$ob42','$ob43','$ob44','$tjatuh_skor_total_ket','$tjatuh_skor_total',
	'$asanak149','$asanak150','$asanak151','$asanak152','$asanak153','$asanak154','$asanak155','$asanak156','$asanak157','$asanak158','$asanak159','$asanak160',
	'$asanak161','$asanak162','$asanak163','$asanak164','$asanak165','$asanak166','$asanak167','$asanak168','$asanak169','$asanak170')";
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
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