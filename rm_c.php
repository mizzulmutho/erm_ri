<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
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

$bulan_u = $row[1]; 
$bulan_u = intval(substr($bulan_u, 0,3));

$hari_u = $row[2]; 
$hari_u = intval(substr($hari_u, 0,3));



//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];



?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>REKAM MEDIK PX</title>  
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
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">

			<br>
			<a href='r_rm.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
			<!-- <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a> -->
			<br>
			<br>


			<div class="row">
				<div class="col-12">
					<center>
						<h3>ASESMEN TERPADU</h3>						
					</center>
				</div>
			</div>

			<hr>

			<div class="row">
				
				<div class="col-12">
					<b>RM 08a1 - ASESMEN AWAL MEDIS RAWAT INAP</b>
					<hr>
					<?php
					include('r_anamnesis_medis.php');
					?>
				</div>
			</div>


			<div class="row">
				
				<div class="col-12">
					<?php 

							if($hari_u < 28 and $tahun_u <= 0){ //neonatus
								$jenis = 'NEONATUS';
								$nodoc = '08f1';
							}else if($tahun_u >=0 and $tahun_u <=17){ //anak
								$jenis = 'ANAK';
								$nodoc = '08f1';
							}else if($tahun_u >17 and $tahun_u <=60){ //dewasa

								if(trim($KODEUNIT) =='R02'){
									$jenis = 'BERSALIN';
									$nodoc = '08f1';
								}else{
									$jenis = 'DEWASA';
									$nodoc = '08f1';
								}
							}else if($tahun_u >60){//geriatri
								$jenis = 'GERIATRI';
								$nodoc = '08f1';
							}


							$qu="SELECT noreg,umur,dpjp FROM  ERM_RI_ASSESMEN_AWAL_DEWASA where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
							$h1u  = sqlsrv_query($conn, $qu);        
							$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
							$cnoreg = $d1u['noreg'];
							$cumur = intval(substr($d1u['umur'],0,2));

							if($cnoreg){
								$jenis='DEWASA';
								if($cumur>60){
									$jenis='GERIATRI';
									$nodoc = '08f1';
								}else if($cumur>17 and $cumur<=60){
									$jenis='DEWASA';
									$nodoc = '08f1';							
								}
							}

							//anak
							$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_ANAK where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
							$h1u  = sqlsrv_query($conn, $qu);        
							$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
							$cnoreg = $d1u['noreg'];

							if($cnoreg){
								$jenis='ANAK';
								$nodoc = '08f1';
							}

							//neonatus
							$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_NEONATUS where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
							$h1u  = sqlsrv_query($conn, $qu);        
							$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
							$cnoreg = $d1u['noreg'];

							if($cnoreg){
								$jenis='NEONATUS';
								$nodoc = '08f1';
							}


							//neonatus
							$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_BERSALIN where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
							$h1u  = sqlsrv_query($conn, $qu);        
							$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
							$cnoreg = $d1u['noreg'];

							if($cnoreg){
								$jenis='BERSALIN';
								$nodoc = '08f1';
							}

							?>
							<b>RM <?php echo $nodoc; ?> - ASESMEN AWAL MEDIS RAWAT INAP <?php echo $jenis;?></b>
							<hr>
							<?php					
							// include('r_anamnesis_medis.php');

							if($jenis=='NEONATUS'){
								include('form_assesmen_neonatus_print.php');
							}
							if($jenis=='BERSALIN'){
								include('form_assesmen_bersalin_print.php');
							}
							if($jenis=='ANAK'){
								include('form_assesmen_anak_print.php');
							}
							if($jenis=='DEWASA'){
								include('form_assesmen_dewasa_print.php');
							}
							if($jenis=='GERIATRI'){
								include('form_assesmen_geriatri_print.php');
							}

							?>
						</div>
					</div>

					<div class="row">

						<div class="col-12">
							<b>RM 09.1 - CATATAN PERKEMBANGAN PASIEN TERINTEGRASI</b>
							<hr>
							<?php
							include('r_soap.php');
							?>
						</div>
					</div>

					<div class="row">

						<div class="col-12">
							<b>RM 10.c2 - LEMBAR OBSERVASI EARLY WARNING SYSTEM (EWS)</b>
							<hr>
							<?php
							include('r_ews.php');
							?>
						</div>
					</div>

					<div class="row">

						<div class="col-12">
							<b>RM 12a.1 - TRANSFER PASIEN ANTAR UNIT PELAYANAN</b>
							<hr>
							<?php
							include('r_transfer_pasien.php');
							?>
						</div>
					</div>

					<div class="row">

						<div class="col-12">
							<b>RM 13a.1 - REKAM PEMBERIAN OBAT (RPO)</b>
							<hr>
							<?php
							include('rpo_print.php');
							?>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<b>RM 13c.1 - LEMBAR BALANCE CAIRAN 24 JAM</b>
							<hr>
							<?php
							include('r_balance_cairan.php');
							?>
						</div>
					</div>


					<div class="row">
						<div class="col-12">
							<b>RM 13d.1 - REKONSILIASI OBAT</b>
							<hr>
							<?php
							include('rekon_obat_print.php');
							?>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<b>RM 17.1 - FORMULIR EDUKASI PASIEN DAN KELUARGA TERINTEGRASI</b>
							<hr>
							<?php
							include('edukasi_print.php');
							?>
						</div>
					</div>



<!-- 			<div class="row">
				
				<div class="col-12">
					<hr>
					<b>RM ... - .... </b>
					<hr>

				</div>
			</div>
		-->

	</form>
	
	<font size='2px'>
	</font>

</body>
</div>