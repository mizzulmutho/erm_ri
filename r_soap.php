<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}

$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$qu="SELECT noreg,norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];


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
	$alamat = "
	Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik
	<br>
	IGD : 031-99100118 Telp : 031-3978658<br>
	Email : sbu.rspg@gmail.com
	";
	$logo = "logo/rspg.png";
};
if ($KET1 == 'GRAHU'){
	$nmrs = "RUMAH SAKIT GRHA HUSADA";
	$alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
	$logo = "logo/grahu.png";
};
if ($KET1 == 'DRIYO'){
	$nmrs = "RUMAH SAKIT DRIYOREJO";
	$alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
	$logo = "logo/driyo.png";
};



$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, noktp,
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  

$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
$kodedept	= $data2[kodedept];

$nama	= $data2[nama];
$kelamin	= $data2[kelamin];
$nik	= trim($data2[nik]);
$alamatpasien	= $data2[alamatpasien];
$kota	= $data2[kota];
$kodekel	= $data2[kodekel];
$telp	= $data2[tlp];
$tmptlahir	= $data2[tmptlahir];
$tgllahir	= $data2[tgllahir];
$jenispekerjaan	= $data2[jenispekerjaan];
$jabatan	= $data2[jabatan];
$umur =  $data2[UMUR];
$noktp =  $data2[noktp];

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

</head> 

<div class="container-fluid">

	<body onload="document.myForm.pasien_mcu.focus();">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<br>
				<a href='list_soap.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
				&nbsp;&nbsp;
				<br>
				<br>
				&nbsp;&nbsp;
				<br>
				<br>

				<div class="row">
					<div class="col-6">
						<table cellpadding="10px">
							<tr valign="top">
								<td>
									<img src='<?php echo $logo; ?>' width='150px'></img>								
								</td>
								<td>
									<h5><b><?php echo $nmrs; ?></b></h5>
									<?php echo $alamat; ?>								
								</td>
							</tr>
						</table>
					</div>
					<div class="col-6">
						<?php echo 'NIK : '.$noktp.'<br>'; ?>					
						<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
						<?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>

					</div>
				</div>
				<hr>


				<div class="row">
					<div class="col-12 center">
						<b><center>CATATAN PERKEMBANGAN PASIEN TERINTEGRASI</center></b>
						<br>
						<center>Disi oleh Dokter / Perawat / Fisioterapi / Tenaga Gizi / Apoteker</center>
					</div>
				</div>
				<hr>

				<table  class="table table-bordered">
					<?php
					$ql="
					SELECT TOP(100) id,kodedokter,noreg,userid,dpjp,subjektif,objektif,assesment,planning,instruksi,sbu,kodeunit,
					CONVERT(VARCHAR, tanggal, 103) as tgl2,
					CONVERT(VARCHAR, tglentry, 8) as tgl3, 
					CONVERT(VARCHAR, tglentry, 20) as tgl4,'SOAP' as jenis,instruksi  
					FROM ERM_SOAP WHERE norm='$norm' and noreg='$noreg'
					union
					select top(200) a.id,'' as kodedokter,a.noreg,a.userid as userinput,'' as dpjp, '' as subjektif, '' as objektif, '' as assesment, '' as planning,'' as instruksi,'' as sbu,'' AS kodeunit,
					CONVERT(VARCHAR, a.tgl, 103) as tgl2, 
					CONVERT(VARCHAR, a.tgl, 8) as tgl3, 
					CONVERT(VARCHAR, a.tgl, 20) as tgl4, 'ASSESMEN' as jenis,'' as instruksi
					FROM            ERM_RI_ANAMNESIS_MEDIS as a INNER JOIN
					ARM_REGISTER ON a.noreg = ARM_REGISTER.NOREG
					WHERE ARM_REGISTER.noreg='$noreg' and a.noreg like '%R%'
					ORDER BY tgl4 desc					
					";
					$hl  = sqlsrv_query($conn, $ql);
					$no=1;
					echo 
					"<tr bgcolor='#969392'>
					<td>No</td>
					<td>Tanggal-Jam-Shift</td>
					<td>Profesional Pemberi Asuhan</td>
					<td>Hasil Pemeriksaan, analisis, Rencana, Penatalaksanaan Pasien</td>
					<td>Keterangan</td>	
					</tr>";
					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){   
						
						$jam_ccpt = substr($dl[tgl3],0,5);
						$total_input=0;
						$total_output=0;

						$kodedokter = trim($dl[kodedokter]);
						$noreg = trim($dl[noreg]);
						$jenis = trim($dl[jenis]);

						//cek assesment awal medis.
						$qcea		= "select noreg,userid from ERM_RI_ANAMNESIS_MEDIS where noreg='$noreg'";
						$hqcea  = sqlsrv_query($conn, $qcea);			  					
						$dhqcea	= sqlsrv_fetch_array($hqcea, SQLSRV_FETCH_ASSOC);				  
						$cek_qcea	= $dhqcea[noreg];
						$dokter_ass	= substr($dhqcea[userid],0,3);

						if($cek_qcea and $jenis=='ASSESMEN'){

							$q2		= "select nama from afarm_dokter where kodedokter like '%$dokter_ass%'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];

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
							$diagnosa_planning= $de['diagnosa_planning'];
							$assesment= $de['assesment'];

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

							$anamnesis="
							Keluhan Utama : $am1<br>
							Lama Keluhan : $am78<br>
							Keluhan Lain : $am79<br>
							";

							$riwayat_penyakit="
							Nama Penyakit : $am2<br>
							Lama Penyakit : $am80<br>
							Riwayat Keluarga : $am81<br>
							Riwayat Alergi : $am3<br>
							Riwayat Pengobatan : $am79<br>
							";

							$keadaan_umum="
							Kesadaran : $am5<br>
							GCS (Eye : $am6 - Verbal : $am7 - Movement : $am8) <br>
							Tekanan Darah : $am9 - Nadi : $am10 / $am11 - Suhu : $am12 <br>
							Pernafasan : $am13 - Nyeri : $am14 - Berat Badan : $am15 <br>
							";

							$penunjang=nl2br($am76);
							$rencana_terapi=nl2br($am77);


							$kondisi_kejiwaan = $am16;

							$diagnosa_planning = $diagnosa_planning;
							$assesment = $assesment;

							if($am19){
								if($am19=='Normal'){
									$fisik="Kepala : $am19 , ";																
								}else{
									$fisik="Kepala : $am19 : $am20 , ";																	
								}
							}
							if($am21){
								if($am21=='Normal'){
									$fisik=$fisik."Mata : $am21 , ";																
								}else{
									$fisik=$fisik."Mata : $am21 : $am22 , ";																	
								}
							}
							if($am23){
								if($am23=='Normal'){
									$fisik=$fisik."Telinga : $am23 , ";																
								}else{
									$fisik=$fisik."Telinga : $am23 : $am24 , ";																	
								}

							}
							if($am25){
								if($am25=='Normal'){
									$fisik=$fisik."Hidung : $am25 ,<br> ";																
								}else{
									$fisik=$fisik."Hidung : $am25 : $am26 ,<br> ";																	
								}

							}
							if($am27){
								if($am25=='Normal'){
									$fisik=$fisik."Rambut : $am27 , ";																
								}else{
									$fisik=$fisik."Rambut : $am27 : $am28 , ";																	
								}

							}
							if($am29){
								if($am29=='Normal'){
									$fisik=$fisik."Bibir : $am29 , ";																
								}else{
									$fisik=$fisik."Bibir : $am29 : $am30 , ";																	
								}

							}
							if($am31){
								if($am31=='Normal'){
									$fisik=$fisik."Gigi Geligi : $am31 , ";																
								}else{
									$fisik=$fisik."Gigi Geligi : $am31 : $am32 , ";																	
								}

							}
							if($am33){
								if($am33=='Normal'){
									$fisik=$fisik."Lidah : $am33 , <br>";																
								}else{
									$fisik=$fisik."Lidah : $am33 : $am34 , <br>";																	
								}

							}
							if($am35){
								if($am35=='Normal'){
									$fisik=$fisik."Langit-langit : $am35 , ";																
								}else{
									$fisik=$fisik."Langit-langit : $am35 : $am36 , ";																	
								}

							}
							if($am37){
								if($am37=='Normal'){
									$fisik=$fisik."Leher : $am37 , ";																
								}else{
									$fisik=$fisik."Leher : $am37 : $am38 , ";																	
								}

							}
							if($am39){
								if($am39=='Normal'){
									$fisik=$fisik."Tenggorokan : $am39 , <br>";																
								}else{
									$fisik=$fisik."Tenggorokan : $am39 : $am40 , <br>";																	
								}

							}
							if($am40){
								if($am40=='Normal'){
									$fisik=$fisik."Tonsil : $am40 , ";																
								}else{
									$fisik=$fisik."Tonsil : $am40 : $am41 , ";																	
								}

							}
							if($am42){
								if($am42=='Normal'){
									$fisik=$fisik."Dada : $am42 , ";																
								}else{
									$fisik=$fisik."Dada : $am42 : $am43 , ";																	
								}

							}
							if($am45){
								if($am45=='Normal'){
									$fisik=$fisik."Payudara : $am45 , ";																
								}else{
									$fisik=$fisik."Payudara : $am45 : $am46 , ";																	
								}

							}
							if($am47){
								if($am47=='Normal'){
									$fisik=$fisik."Punggung : $am47 , ";																
								}else{
									$fisik=$fisik."Punggung : $am47 : $am48 , ";																	
								}

							}
							if($am49){
								if($am49=='Normal'){
									$fisik=$fisik."Perut : $am49 , ";																
								}else{
									$fisik=$fisik."Perut : $am49 : $am50 , ";																	
								}

							}
							if($am51){
								if($am51=='Normal'){
									$fisik=$fisik."Genital : $am51 , <br>";																
								}else{
									$fisik=$fisik."Genital : $am51 : $am52 , <br>";																	
								}

							}
							if($am53){
								if($am53=='Normal'){
									$fisik=$fisik."Anus/Dubur : $am53 , ";																
								}else{
									$fisik=$fisik."Anus/Dubur : $am53 : $am54 , ";																	
								}

							}
							if($am55){
								if($am55=='Normal'){
									$fisik=$fisik."Lengan Atas : $am55 , ";																
								}else{
									$fisik=$fisik."Lengan Atas : $am55 : $am56 , ";																	
								}

							}
							if($am57){
								if($am57=='Normal'){
									$fisik=$fisik."Lengan Bawah : $am57 , <br>";																
								}else{
									$fisik=$fisik."Lengan Bawah : $am57 : $am58 , <br>";																	
								}

							}
							if($am59){
								if($am59=='Normal'){
									$fisik=$fisik."Jari Tangan : $am59 , ";																
								}else{
									$fisik=$fisik."Jari Tangan : $am59 : $am60 , ";																	
								}

							}
							if($am61){
								if($am61=='Normal'){
									$fisik=$fisik."Kuku Tangan : $am61 , ";																
								}else{
									$fisik=$fisik."Kuku Tangan : $am61 : $am62 , ";																	
								}

							}
							if($am63){
								if($am63=='Normal'){
									$fisik=$fisik."Persendian Tangan : $am63 , <br>";																
								}else{
									$fisik=$fisik."Persendian Tangan : $am63 : $am64 , <br>";																	
								}

							}
							if($am65){
								if($am65=='Normal'){
									$fisik=$fisik."Tungkai Atas : $am65 , ";																
								}else{
									$fisik=$fisik."Tungkai Atas : $am65 : $am66 , ";																	
								}

							}
							if($am67){
								if($am67=='Normal'){
									$fisik=$fisik."Tungkai Bawah : $am67 , ";																
								}else{
									$fisik=$fisik."Tungkai Bawah : $am67 : $am68 , ";																	
								}

							}
							if($am69){
								if($am69=='Normal'){
									$fisik=$fisik."Jari Kaki : $am69 , <br>";																
								}else{
									$fisik=$fisik."Jari Kaki : $am69 : $am70 , <br>";																	
								}

							}
							if($am71){
								if($am71=='Normal'){
									$fisik=$fisik."Kuku Kaki : $am71 , ";																
								}else{
									$fisik=$fisik."Kuku Kaki : $am71 : $am72 , ";																	
								}

							}
							if($am73){
								if($am73=='Normal'){
									$fisik=$fisik."Persendian Kaki : $am73 , ";																
								}else{
									$fisik=$fisik."Persendian Kaki : $am73 : $am74 , ";																	
								}

							}






							echo "
							<tr>
							<td>$no</td>
							<td colspan='4'>
							<b>Assesment Awal Medis : </b> $namadokter ($dokter_ass) - Tgl : $tgl_assesment Jam : $jam_assesment ($noreg)

							<table width='100%'>
							<tr>
							<td width='50%'>
							<table border='1' width='100%'>
							<tr>
							<td>Diagnosa</td>
							<td>$diagnosa</td>
							</tr>
							<tr>
							<td>Anamnesis</td>
							<td>$anamnesis</td>
							</tr>
							<tr>
							<td>Riwayat Penyakit</td>
							<td>$riwayat_penyakit</td>
							</tr>
							<tr>
							<td>Keadaan Umum</td>
							<td>$keadaan_umum</td>
							</tr>
							</table>
							</td>
							<td width='50%'>

							<table border='1' width='100%'>
							<tr>
							<td>Pemeriksaan Fisik</td>
							<td>$fisik</td>
							</tr>
							<tr>
							<td>Penunjang</td>
							<td>$penunjang</td>
							</tr>
							<tr>
							<td>Assesment</td>
							<td>$assesment</td>
							</tr>
							<tr>
							<td>Diagnosa Planning</td>
							<td>$diagnosa_planning</td>
							</tr>
							<tr>
							<td>Rencana Terapi</td>
							<td>$rencana_terapi</td>
							</tr>
							<tr>
							<td>Kondisi Kejiwaan</td>
							<td>$kondisi_kejiwaan</td>
							</tr>

							</table>


							</td>
							</tr>
							</table>



							</td>
							</tr>
							";
						}

						if($dl[jenis]=='SOAP'){ 
							$keterangan=""; 
							$tulbakon="";
							$gantishift="";
							$kodedokter = trim($dl[kodedokter]);
							$noreg = trim($dl[noreg]);
							$userid = trim($dl[userid]);
							$dpjp = trim($dl[dpjp]);
							$periode = trim($dl[tgl2]);

							$q2		= "select nama from afarm_dokter where kodedokter like '%$kodedokter%'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];
							$profesi = 'DOKTER';

							if(empty($namadokter)){
								$q2		= "select nama from afarm_paramedis where kode='$kodedokter'";
								$hasil2  = sqlsrv_query($conn, $q2);			  					
								$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
								$namadokter	= $data2[nama];
								$profesi = 'PERAWAT';

								if(trim($dl[kodeunit]) =='R02' OR trim($dl[kodeunit]) =='G19' ){
									$profesi = 'BIDAN';									
								}

							}

							if(empty($namadokter)){
								$q2		= "select nama from master_apoteker where apoteker='$kodedokter'";
								$hasil2  = sqlsrv_query($conn, $q2);			  					
								$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
								$namadokter	= $data2[nama];
								$profesi = 'APOTEKER';								
							}
							
						//cek verif dokter...
							// $q3		= "select userverif,CONVERT(VARCHAR, tglverif, 20) as tanggal from  ERM_SOAP_VERIF where noreg='$noreg' and userverif like '%$dpjp%' and (CONVERT(DATETIME, CONVERT(VARCHAR, tanggal, 101), 101) BETWEEN '$periode' AND '$periode')";

							$q3		= "
							SELECT        ERM_SOAP.dpjp, CONVERT(VARCHAR, ERM_SOAP.verif_dpjp, 103) AS tgl, 
							CONVERT(VARCHAR, ERM_SOAP.verif_dpjp, 8) as tgl3, 
							Afarm_DOKTER.NAMA
							FROM            ERM_SOAP LEFT OUTER JOIN
							Afarm_DOKTER ON ERM_SOAP.dpjp = Afarm_DOKTER.KODEDOKTER
							WHERE        (ERM_SOAP.noreg = '$noreg') AND (ERM_SOAP.id = $dl[id]) and ERM_SOAP.verif_dpjp is not null
							";
							$hasil3  = sqlsrv_query($conn, $q3);			  					
							$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
							$userverif	= $data3[NAMA];
							$tanggal	= $data3[tgl];
							$jam_verif = substr($data3[tgl3],0,5);

							$subjektif = nl2br($dl[subjektif]);
							$subjektif 			= str_replace("Keluhan Pasien :","",$subjektif).'<br>';
							$objektif = nl2br($dl[objektif]).'<br>';
							$assesment = nl2br($dl[assesment]).'<br>';
							$planning = nl2br($dl[planning]).'<br>';
							// $instruksi = nl2br($dl[instruksi]).'<br>';
							$instruksi = '<p>' . str_replace("\n", '</p><p>', trim($dl['instruksi'])) . '</p>';

							if($profesi=='APOTEKER'){
								$subjektif = nl2br($dl[subjektif]);
								$subjektif 			= str_replace("Keluhan Pasien :","",$subjektif).'<br>';
								$subjektif = "<table><tr><td>$subjektif</td></tr></table>";
								$objektif = nl2br($dl[objektif]).'<br>';
								$objektif 			= str_replace("Riwayat Alergi Obat","<b>Riwayat Alergi Obat</b>",$objektif);
								$objektif 			= str_replace("Riwayat penggunaan Obat ","<br><b>Riwayat penggunaan Obat </b>",$objektif.'<br>');
								$objektif 			= str_replace("Hasil Lab","<b>Hasil Lab</b>",$objektif);
								$objektif = "<table><tr><td>$objektif</td></tr></table>";
								$assesment = nl2br($dl[assesment]).'<br>';
								$assesment = "<table><tr><td>$assesment</td></tr></table>";
								$planning = nl2br($dl[planning]);
								$planning = "<table><tr><td>$planning</td></tr></table>";							

							}else{
								$subjektif = nl2br($dl[subjektif]);
								$subjektif 			= str_replace("Keluhan Pasien :","",$subjektif).'<br>';
								// $objektif = nl2br($dl[objektif]).'<br>';

								$parts = explode('Status Lokalis :', $objektif, 2);
								if (count($parts) == 2) {
    								// Pisahkan Status Lokalis + sisanya
									$before = $parts[0];
									$after = $parts[1];

    								// Pisahkan lagi jika ada 'Pemeriksaan Penunjang :'
									$subparts = explode('Pemeriksaan Penunjang :', $after, 2);
									if (count($subparts) == 2) {
										$statusLokalis = trim($subparts[0]);
										$pemeriksaan = trim($subparts[1]);

        								// Tambahkan <br> setelah koma di masing-masing
										$statusLokalis = str_replace(', ', ',<br>', $statusLokalis);
										$pemeriksaan = str_replace(', ', ',<br>', $pemeriksaan);

        								// Gabung lagi
										$objektif = $before 
										. 'Status Lokalis :<br>' . $statusLokalis 
										. '<br>Pemeriksaan Penunjang :<br>' . $pemeriksaan;
									} else {
        								// Kalau tidak ada Pemeriksaan Penunjang, hanya Status Lokalis
										$statusLokalis = str_replace(', ', ',<br>', $after);
										$objektif = $before . 'Status Lokalis :<br>' . $statusLokalis;
									}
								}
								// Terakhir: pastikan newline juga diubah jadi <br> jika ada
								$objektif = nl2br($objektif);

								$assesment = nl2br($dl[assesment]).'<br>';
								$planning = nl2br($dl[planning]).'<br>';
								// $instruksi = nl2br($dl[instruksi]).'<br>';	
								$instruksi = '<p>' . str_replace("\n", '</p><p>', trim($dl['instruksi'])) . '</p>';
								
							}

							$hasilassesment = "
							<b>Subject :</b> $subjektif<br>
							<b>Object :</b> $objektif<br>
							<b>Assesment :</b> $assesment<br>
							<b>Plan :</b> $planning<br>
							<b>Instruksi :</b> $instruksi
							";
							

						//cek gizi...
							$q5		= "SELECT    antropometri, biokimia, fisik_klinis, asupan_makan, diagnosa_gizi, intervensi, monitoring
							FROM            ERM_RI_SOAP
							WHERE      id_soap='$dl[id]'";
							$hasil5  = sqlsrv_query($conn, $q5);			  					
							$data5	= sqlsrv_fetch_array($hasil5, SQLSRV_FETCH_ASSOC);				  
							$antropometri	= $data5[antropometri];
							$biokimia	= $data5[biokimia];
							$fisik_klinis	= $data5[fisik_klinis];
							$asupan_makan	= $data5[asupan_makan];
							$diagnosa_gizi	= $data5[diagnosa_gizi];
							$intervensi	= $data5[intervensi];
							$monitoring	= $data5[monitoring];

							if($diagnosa_gizi){
								$subjektif = nl2br($dl[subjektif]);
								$objektif = nl2br($dl[objektif]);
								$assesment = nl2br($dl[assesment]);
								$planning = nl2br($dl[planning]);
								
								$hasilassesment = "
								<b>Assesment gizi :</b> $assesment<br>
								<b>Diagnosa gizi :</b> $subjektif<br>
								<b>Intervensi gizi :</b> $planning<br>
								<b>Monitoring-evaluasi gizi :</b> $objektif<br>
								";
								$profesi = 'GIZI';
							}


						//oper shift
							$q2		= "select userid,namapetugas,CONVERT(VARCHAR, tglentry, 103) as tgl, CONVERT(VARCHAR, tglentry, 8) as tgl3 
							from ERM_OPERSHIFT where noreg='$noreg' and idsoap='$dl[id]'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);		
							$useroper	= $data2[userid];			  
							$namapetugas	= $data2[namapetugas];
							$tgl	= $data2[tgl];
							$jam_oper = substr($data2[tgl3],0,5);

							$qu="SELECT NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where user1 like '%$useroper%'";
							$h1u  = sqlsrv_query($conn, $qu);        
							$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
							$nmuserid = trim($d1u['NamaUser']);
							
							if($namapetugas){
								$gantishift = 'Petugas : <b>'.$namapetugas.'</b><br>Tgl:'.$tgl.' - Jam: '.$jam_oper;
								$petugasoper = 'Petugas : <b>'.$nmuserid.'</b><br>Tgl:'.$tgl.' - Jam: '.$jam_oper;
							}

						//cek edit...
							$q4		= "select count(noreg) as jumlah from ERM_SOAP_EDIT where noreg='$noreg' and idsoap='$dl[id]'";
							$hasil4  = sqlsrv_query($conn, $q4);			  					
							$data4	= sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC);				  
							$jumlah	= $data4[jumlah];
							if($jumlah){
								$keterangan = 'CPPT Telah diEdit '.$jumlah.' kali';
							}

							$q5		= "select namadokter,CONVERT(VARCHAR, tglentry, 103) as tgl,userid, CONVERT(VARCHAR, tglentry, 8) as tgl3,
							CONVERT(VARCHAR, verif_dpjp, 103) as tglt, CONVERT(VARCHAR, verif_dpjp, 8) as tglt2
							from ERM_TULBAKON where noreg='$noreg' and idsoap='$dl[id]'";
							$hasil5  = sqlsrv_query($conn, $q5);			  					
							$data5	= sqlsrv_fetch_array($hasil5, SQLSRV_FETCH_ASSOC);				  
							$namadokter2	= $data5[namadokter];
							$tgl	= $data5[tgl];
							$userid	= $data5[userid];
							$jam_lapor = substr($data5[tgl3],0,5);
							$tgl_vtulbakon	= $data5[tglt];
							$jam_vtulbakon = substr($data5[tglt2],0,5);

							if($namadokter2){

								$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$userid%'";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$role = trim($d1u['role']);


								if($role=='DOKTER'){
									$qrnmUser = "SELECT NamaUser from ROLERSPGENTRY.dbo.tblusererm WHERE user1 ='$userid'";
									$hqrnmUser = sqlsrv_query($conn, $qrnmUser);
									$dhqrnmUser = sqlsrv_fetch_array($hqrnmUser, SQLSRV_FETCH_ASSOC);
									$namaUser = $dhqrnmUser['NamaUser'];
									$userid = $namaUser;
								}

								if($tgl_vtulbakon){
									$tulbakon = 'Petugas : <b>'.$userid.'</b><br>Tgl:'.$tgl.' Jam: '.$jam_lapor;
									$tulbakon_dokter = 'Dokter: <b>'.$namadokter2.'</b><br>Tgl:'.$tgl_vtulbakon.' Jam: '.$jam_vtulbakon;
								}else{
									$tulbakon = 'Petugas : <b>'.$userid.'</b><br>Tgl:'.$tgl.' Jam: '.$jam_lapor;
									$tulbakon_dokter = 'Dokter: <b>'.$namadokter2.'</b><br><i>Belum diverifikasi</i>';
								}
							}

							

							if($tulbakon){
								if($role=='DOKTER'){
									echo "	<tr>
									<td>$no</td>
									<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
									<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
									<td>
									$hasilassesment
									<table>
									<tr>									
									<td>									
									<table>Tulbakon
									<tr>
									<td>Dilaporkan Oleh<br>$tulbakon</td>
									<td>Diverifikasi Oleh<br>$tulbakon_dokter</td>
									</tr>
									</table>
									</td>
									</tr>
									</table>
									</td>
									<td>$keterangan</td>
									</tr>
									";
								}else{	
									echo "	<tr>
									<td>$no</td>
									<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
									<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
									<td>
									$hasilassesment
									<table>
									<tr>
									<td>Verif DPJP<br>
									<table>
									<tr><td>
									Diverifikasi Oleh<br>
									<b>$userverif</b><br>Tgl : $tanggal - Jam : $jam_verif
									</td></tr>
									</table>
									</td>
									<td>
									<table>Oper Shift
									<tr>
									<td>Dioperkan Oleh<br>$petugasoper</td>
									<td>Diterima Oleh<br>$gantishift</td>
									</tr>
									</table>
									</td>
									<td>

									<table>Tulbakon
									<tr>
									<td>Dilaporkan Oleh<br>$tulbakon</td>
									<td>Diverifikasi Oleh<br>$tulbakon_dokter</td>
									</tr>
									</table>
									</td>
									</tr>
									</table>
									</td>
									<td>$keterangan</td>
									</tr>
									";
								}
							}else{

								if($profesi<>'APOTEKER' and $profesi<>'DOKTER' ){
									echo "	<tr>
									<td>$no</td>
									<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
									<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
									<td>
									$hasilassesment
									<table>
									<tr>
									<td>Verif DPJP<br>

									<table>
									<tr><td>
									Diverifikasi Oleh<br>
									<b>$userverif</b><br>Tgl : $tanggal - Jam : $jam_verif
									</td></tr>
									</table>

									</td>
									<td colspan='2'>
									<table>Oper Shift
									<tr>
									<td>Dioperkan Oleh<br>$petugasoper</td>
									<td>Diterima Oleh<br>$gantishift</td>
									</tr>
									</table>
									</td>
									</tr>
									</table>
									</td>
									<td>$keterangan</td>
									</tr>
									";
								}else{
									echo "	<tr>
									<td>$no</td>
									<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
									<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
									<td>
									$hasilassesment
									</td>
									<td>$keterangan</td>
									</tr>
									";
								}

							}

						}else if($dl[jenis]=='MONITORING'){
							// echo "monitoring";

							$qe="
							SELECT *,CONVERT(VARCHAR, tglinput, 20) as tglinput
							FROM ERM_RI_OBSERVASI
							where id='$dl[id]'";
							$he  = sqlsrv_query($conn, $qe);        
							$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
							$tglinput = $de['tglinput'];
							$userinput = $de['userinput'];

							$td_sistolik= $de['td_sistolik'];
							$td_diastolik= $de['td_diastolik'];
							$suhu= $de['suhu'];
							$nadi= $de['nadi'];
							$spo2= $de['spo2'];
							$pernafasan= $de['pernafasan'];
							$total_ews= $de['total_ews'];

							$ob1 = $de['ob1'];
							$ob2= $de['ob2'];
							$ob3= $de['ob3'];
							$ob4= $de['ob4'];
							$ob5= $de['ob5'];
							$ob6= $de['ob6'];
							$ob7= $de['ob7'];
							$ob8= $de['ob8'];
							$ob9= $de['ob9'];
							$ob10= $de['ob10'];
							$ob11= $de['ob11'];
							$ob12= $de['ob12'];
							$ob13= $de['ob13'];
							$ob14= $de['ob14'];
							$ob15= $de['ob15'];
							$ob16= $de['ob16'];
							$ob17= $de['ob17'];
							$ob18= $de['ob18'];
							$ob19= $de['ob19'];
							$ob20= $de['ob20'];
							$ob21= $de['ob21'];
							$ob22= $de['ob22'];
							$ob23= $de['ob23'];
							$ob24= $de['ob24'];
							$ob25= $de['ob25'];
							$ob26= $de['ob26'];
							$ob27= $de['ob27'];
							$ob28= $de['ob28'];
							$ob29= $de['ob29'];
							$ob30= $de['ob30'];
							$ob31= $de['ob31'];
							$ob32= $de['ob32'];
							$ob33= $de['ob33'];
							$ob34= $de['ob34'];
							$ob35= $de['ob35'];
							$ob36= $de['ob36'];
							$ob37= $de['ob37'];
							$ob38= $de['ob38'];
							$ob39= $de['ob39'];
							$ob40= $de['ob40'];
							$ob41= $de['ob41'];
							$ob42= $de['ob42'];
							$ob43= $de['ob43'];
							$ob44= $de['ob44'];
							$ob45= $de['ob45'];
							$ob46= $de['ob46'];
							$ob47= $de['ob47'];
							$ob48= $de['ob48'];
							$ob49= $de['ob49'];
							$ob50= $de['ob50'];
							$ob51= $de['ob51'];
							$ob52= $de['ob52'];
							$ob53= $de['ob53'];
							$ob54= $de['ob54'];
							$ob55= $de['ob55'];
							$ob56= $de['ob56'];
							$ob57= $de['ob57'];
							$ob58= $de['ob58'];
							$ob59= $de['ob59'];
							$ob60= $de['ob60'];

							$total_input=$ob12+$ob13+$ob18+$ob19;
							$total_output=$ob20+$ob26+$ob22+$ob21+$ob23+$ob24+$ob25;
							$balance_cairan = $total_input-$total_output;

							if($suhu){
								$monitoringket="
								<table>
								<tr>
								<td>EWS</td><td>GDA</td>
								</tr>
								<tr>
								<td>
								kesadaran : $ob1 - gcs : $ob6<br>
								tensi : $td_sistolik/$td_diastolik , 
								suhu : $suhu<br>
								nadi : $nadi , 
								rr : $pernafasan , 
								spo2 : $spo2<br> 
								oksigen : $ob7<br>
								bb : $ob9 , 
								tb : $ob10<br>
								<b>total EWS : $total_ews</b>
								</td>
								<td>
								$ob28
								</td>
								</tr>							
								</table>
								";

							}else{

								$monitoringket="
								<table>
								<tr>
								<td>Cairan</td><td>GDA</td>
								</tr>
								<tr>
								<td>
								Infus : $ob12 , 
								Tranfusi : $ob13 , 
								Makan : $ob18 , 
								Minum : $ob19<br>
								Total : $total_input<br>
								Muntah : $ob20 , 
								Peradangan : $ob26 , 
								Urine : $ob22 , 
								BAB : $ob21 , 
								IWL : $ob23 ,
								NGT : $ob24 ,
								Drain : $ob25<br>
								Total : $total_output<br>
								<b>Balance Cairan : $balance_cairan</b>
								</td>
								<td>
								$ob28
								</td>
								</tr>							
								</table>
								";
							}

							echo "	<tr>
							<td>$no</td>
							<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
							<td>Observasi Pasien oleh : $userinput</td>
							<td>$monitoringket<br>
							</td>
							<td></td>
							</tr>
							";

						}else if($dl[jenis]=='NYERI'){
							$qe="
							SELECT *,CONVERT(VARCHAR, tgl, 20) as tglinput
							FROM ERM_RI_NYERI
							where id='$dl[id]'";
							$he  = sqlsrv_query($conn, $qe);        
							$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
							$tglinput = $de['tglinput'];
							$userinput = $de['userid'];

							$jenis= $de['jenis'];
							$evaluasi= $de['evaluasi'];
							$skala= $de['skala'];

							if($jenis){
								$nyeriket="
								<table class='table table-bordered'>
								<tr>
								<td width='20%'>Jenis Nyeri : $jenis</td><td>Skala : $skala</td>
								</tr>
								<tr>
								<td>Evaluasi</td><td>$evaluasi</td>
								</tr>
								</table>
								";

							}

							echo "	<tr>
							<td>$no</td>
							<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
							<td>$userinput</td>
							<td><b>Assesment Ulang Nyeri</b> <br>$nyeriket<br>
							</td>
							<td></td>
							</tr>
							";


						}else if($dl[jenis]=='JATUH'){
							$qe="
							SELECT *,CONVERT(VARCHAR, tgl, 20) as tglinput
							FROM ERM_RI_RJATUH
							where id='$dl[id]'";
							$he  = sqlsrv_query($conn, $qe);        
							$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
							$tglinput = $de['tglinput'];
							$userinput = $de['userid'];

							$ket= $de['ket'];
							$nilai= $de['nilai'];

							if($userinput){
								$jatuhket="
								<table class='table table-bordered'>
								<tr>
								<td  width='20%'>Skore Resiko Jatuh</td><td>$nilai</td><td>$ket</td>
								</tr>
								</table>
								";

							}

							echo "	<tr>
							<td>$no</td>
							<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
							<td>$userinput</td>
							<td><b>Assesment Ulang Resiko jatuh</b> <br>$jatuhket<br>
							</td>
							<td></td>
							</tr>
							";


						}else{

						}
						$no += 1;

					}
					?>
				</table>
			</font>
		</form>
	</font>
</body>
</div>