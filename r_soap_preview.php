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
				<a href='close.php' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
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
					CONVERT(VARCHAR, tanggal, 101) as tgl2,
					CONVERT(VARCHAR, tglentry, 8) as tgl3, 
					CONVERT(VARCHAR, tglentry, 20) as tgl4,'SOAP' as jenis,instruksi  
					FROM ERM_SOAP WHERE norm='$norm' and noreg='$noreg'
					union
					select top(100) id,'' as kodedokter,noreg,userinput,'' as dpjp, '' as subjektif, '' as objektif, '' as assesment, '' as planning,'' as instruksi,'' as sbu,'' as kodeunit,
					CONVERT(VARCHAR, tglinput, 101) as tgl2, 
					CONVERT(VARCHAR, tglinput, 8) as tgl3, 
					CONVERT(VARCHAR, tglinput, 20) as tgl4, 'MONITORING' as jenis,'' as instruksi
					FROM ERM_RI_OBSERVASI WHERE norm='$norm' and noreg='$noreg'
					UNION
					SELECT        TOP (100) id, '' AS kodedokter, noreg, userid as userinput, '' AS dpjp, '' AS subjektif, '' AS objektif, '' AS assesment, '' AS planning, '' AS instruksi, '' AS sbu, '' AS kodeunit, CONVERT(VARCHAR, tgl, 101) AS tgl2, CONVERT(VARCHAR, 
						tglentry, 8) AS tgl3, CONVERT(VARCHAR, tglentry, 20) AS tgl4, 'NYERI' AS jenis,'' as instruksi
					FROM            ERM_RI_NYERI
					WHERE        (noreg = '$noreg')
					UNION
					SELECT        TOP (100) id, '' AS kodedokter, noreg, userid AS userinput, '' AS dpjp, '' AS subjektif, '' AS objektif, '' AS assesment, '' AS planning, '' AS instruksi, '' AS sbu, '' AS kodeunit, CONVERT(VARCHAR, tgl, 101) AS tgl2, 
					CONVERT(VARCHAR, tglentry, 8) AS tgl3, CONVERT(VARCHAR, tglentry, 20) AS tgl4, 'JATUH' AS jenis,'' as instruksi
					FROM            ERM_RI_RJATUH
					WHERE        (noreg = '$noreg')
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
							$q3		= "select userverif,CONVERT(VARCHAR, tglverif, 20) as tanggal from  ERM_SOAP_VERIF where noreg='$noreg' and userverif like '%$dpjp%'";
							$hasil3  = sqlsrv_query($conn, $q3);			  					
							$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
							$userverif	= $data3[userverif];
							$tanggal	= $data3[tanggal];

							$subjektif = nl2br($dl[subjektif]);
							$subjektif 			= str_replace("Keluhan Pasien :","",$subjektif).'<br>';
							$objektif = nl2br($dl[objektif]).'<br>';
							$assesment = nl2br($dl[assesment]).'<br>';
							$planning = nl2br($dl[planning]).'<br>';
							$instruksi = nl2br($dl[instruksi]).'<br>';

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
								$objektif = nl2br($dl[objektif]).'<br>';
								$assesment = nl2br($dl[assesment]).'<br>';
								$planning = nl2br($dl[planning]).'<br>';
								$instruksi = nl2br($dl[instruksi]).'<br>';								
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
							$q2		= "select namapetugas,CONVERT(VARCHAR, tglentry, 103) as tgl, CONVERT(VARCHAR, tglentry, 8) as tgl3 
							from ERM_OPERSHIFT where noreg='$noreg' and idsoap='$dl[id]'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namapetugas	= $data2[namapetugas];
							$tgl	= $data2[tgl];
							$jam_oper = substr($data2[tgl3],0,5);

							if($namapetugas){
								$gantishift = 'Petugas:'.$namapetugas.'<br>Tgl:'.$tgl.' Jam: '.$jam_oper;
								$petugasoper = 'Petugas:'.$userid.'<br>Tgl:'.$tgl.' Jam: '.$jam_oper;
							}

						//cek edit...
							$q4		= "select count(noreg) as jumlah from ERM_SOAP_EDIT where noreg='$noreg' and idsoap='$dl[id]'";
							$hasil4  = sqlsrv_query($conn, $q4);			  					
							$data4	= sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC);				  
							$jumlah	= $data4[jumlah];
							if($jumlah){
								$keterangan = 'CPPT Telah diEdit '.$jumlah.' kali';
							}

							$q5		= "select namadokter,CONVERT(VARCHAR, tglentry, 103) as tgl 
							from ERM_TULBAKON where noreg='$noreg' and idsoap='$dl[id]'";
							$hasil5  = sqlsrv_query($conn, $q5);			  					
							$data5	= sqlsrv_fetch_array($hasil5, SQLSRV_FETCH_ASSOC);				  
							$namadokter2	= $data5[namadokter];
							$tgl	= $data5[tgl];

							if($namadokter2){
								$tulbakon = 'TULBAKON/SBAR<br>'.$tgl.'<br>'.$namadokter2;
							}

							

							if($tulbakon){
								echo "	<tr>
								<td>$no</td>
								<td>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt</td>
								<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
								<td>
								$hasilassesment
								<table>
								<tr>
								<td>Verif DPJP<br>$userverif<br>$tanggal</td>
								<td>Ganti Shift<br>$gantishift</td>
								<td>$tulbakon</td>
								</tr>
								</table>
								</td>
								<td>$keterangan</td>
								</tr>
								";
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
									<td>Verif DPJP<br>$userverif<br>$tanggal</td>
									<td>Dioperkan Oleh<br>$petugasoper</td>
									<td>Diterima Oleh<br>$gantishift</td>
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