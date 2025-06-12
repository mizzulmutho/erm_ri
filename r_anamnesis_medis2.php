<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


// include "phpqrcode/qrlib.php";

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

if(empty($regcek)){
	// $q  = "insert into ERM_RI_ANAMNESIS_MEDIS(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tglinput','$tglinput')";
	// $hs = sqlsrv_query($conn,$q);
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

</head> 

<div class="container-fluid">

	<body onload="document.myForm.ku.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
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
					<div class="col-12 text-center">
						<b>ASESMEN AWAL MEDIS RAWAT INAP</b><br>
					</div>
				</div>

				<hr>

				<?php 
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

				?>
				<div class="row">
					<div class="col-6">
						<b>Diagnosa</b>
						<br>
						<?php echo $diagnosa;?>
						<br>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-6">
						<b>Anamnesis</b>
						<br>
						Keluhan Utama :<br>
						<?php echo nl2br($am1);?>
						<br><br>
						Riwayat Penyakit : <br>
						<?php echo nl2br($am2);?>
						<br><br>
						Riwayat Alergi : <br>
						<?php echo nl2br($am3);?>
						<br><br>
						Riwayat Pengobatan : <br>
						<?php echo nl2br($am4);?>
						<br><br>

						<hr>
						<b>Keadaan Umum</b><br>
						Tingkat Kesadaran : <br>
						<?php 
						$keadaan_umum="
						Kesadaran : $am5<br>
						GCS (Eye : $am6 - Verbal : $am7 - Movement : $am8) <br>
						Tekanan Darah : $am9 - Nadi : $am10 / $am11 - Suhu : $am12 <br>
						Pernafasan : $am13 - Nyeri : $am14 - Berat Badan : $am15 <br>
						";
						echo nl2br($keadaan_umum);
						?>
						<br><br>

						<hr>
						<b>Anamnesa Psikologi/Sosial/Ekonomi</b><br>
						<label for="" class="col-3">Kondisi Kejiwaan : </label>												
						<?php echo nl2br($am16);?> 
						<br>
						<label for="" class="col-3">Sosial Ekonomi : </label>
						<?php echo nl2br($am17);?> 
						<br>
						<label for="" class="col-3">Spiritual : </label>
						<?php echo nl2br($am18);?> <br>
						<br>


					</div>
					<div class="col-6">					
						<div class="card">
							<div class="card-header">
								Pemeriksaan Fisik
							</div>
							<div class="card-body">						
								<div class="card">
									<div class="card-body">
										<?php 

										$keadaan_umum="
										Kesadaran : $am5<br>
										GCS (Eye : $am6 - Verbal : $am7 - Movement : $am8) <br>
										Tekanan Darah : $am9 - Nadi : $am10 / $am11 - Suhu : $am12 <br>
										Pernafasan : $am13 - Nyeri : $am14 - Berat Badan : $am15 <br>
										";

										$penunjang=nl2br($am76);
										$rencana_terapi=nl2br($am77);


										$kondisi_kejiwaan = $am16;

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


										?>
										<?php echo$fisik; ?>

									</div>

								</div>
								<br>
								<div class="card">
									<div class="card-body">
										<b>Penunjang : </b><br>							
										<?php echo nl2br($am76); ?>
										<hr>
										<b>Rencana Terapi : </b><br>							
										<?php echo nl2br($am77); ?>
										<br>

									</div>
								</div>


							</div>

						</div>
					</div>				
				</div>
				<hr>
				<div class="row">
					<div class="col-12 text-center">
						<div class="card">
							<div class="card-body">						

								<label for="" class="col-3">Verifikasi Dokter Pemeriksa : </label><br>	
								<?php echo nl2br($am75);?> 						
								<br>

								<?php 
								if($am75){
									$verif_dokter="Resume medis ini telah diVerifikasi Oleh : ".$am75."Pada Tanggal : ".$tgl; 

									QRcode::png($verif_dokter, "image.png", "L", 2, 2);   
									echo "<center><img src='image.png'></center>";


									$verif_dokter;
									echo $tgl_assesment.' '.$jam_assesment;
								}
								?>
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

	// $tgl	= $_POST["tgl"];
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
	$am78 = $_POST['am78'];
	$am79 = $_POST['am79'];
	$am80 = $_POST['am80'];


	$q  = "update ERM_RI_ANAMNESIS_MEDIS set
	tgl='$tgl',
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
	am80 = '$am80'
	where noreg='$noreg'
	";
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
