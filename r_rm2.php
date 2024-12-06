<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$user = $row[0]; 
$sbu = $row[1]; 
$norm = $row[2]; 

$qu="SELECT top(1)norm,noreg FROM ERM_ASSESMEN_HEADER where norm='$norm'";
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
			<a href='listdata.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
			<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
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
				<div class="col-12 text-center">
					<b>REKAM MEDIS</b>
					<br>
					<b>RAHASIA</b>
					<br>					
				</div>
			</div>

			<br>
			<table  class="table table-bordered">

				<?php
				$ql="
				SELECT DISTINCT ERM_ASSESMEN_HEADER.noreg, CONVERT(VARCHAR, ARM_REGISTER.TANGGAL, 120) AS tglmasuk
				FROM            ERM_ASSESMEN_HEADER INNER JOIN
				ARM_REGISTER ON ERM_ASSESMEN_HEADER.noreg = ARM_REGISTER.NOREG
				WHERE        (ERM_ASSESMEN_HEADER.norm = '$norm')
				ORDER BY tglmasuk DESC
				";
				$hl  = sqlsrv_query($conn, $ql);
				$no=1;
				echo 
				"<tr bgcolor='#969392'>
				<td>No</td>
				<td>Nomor Registrasi</td>
				<td>Unit / Poli</td>
				<td>Dokter</td>
				<td>Rekam Medik</td>
				</tr>";

				while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){    
					$hasilassesment='';
					//cari assesmen header
					$qui       = "
					SELECT TOP(1) ERM_ASSESMEN_HEADER.id,
					ERM_ASSESMEN_HEADER.noreg, CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 103) AS tglmasuk, Afarm_Unitlayanan.NAMAUNIT, Afarm_DOKTER.NAMA, Afarm_Unitlayanan.JENIS2
					FROM            ERM_ASSESMEN_HEADER INNER JOIN
					Afarm_Unitlayanan ON ERM_ASSESMEN_HEADER.kodeunit = Afarm_Unitlayanan.KODEUNIT LEFT OUTER JOIN
					Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER
					WHERE        (ERM_ASSESMEN_HEADER.noreg = '$dl[noreg]')
					ORDER BY noreg DESC
					";
					$h1ui  = sqlsrv_query($conn, $qui);  
					$d1ui    = sqlsrv_fetch_array($h1ui, SQLSRV_FETCH_ASSOC);                      
					$tglmasuk = $d1ui[tglmasuk];
					$NAMAUNIT = $d1ui[NAMAUNIT];
					$JENIS2 = $d1ui[JENIS2];
					$cid = $d1ui['id'];

					//geriatri & dewasa
					$qu="SELECT noreg,umur,dpjp FROM  ERM_RI_ASSESMEN_AWAL_DEWASA where noreg='$dl[noreg]' and (dpjp <> '' or dpjp is not null)";
					$h1u  = sqlsrv_query($conn, $qu);        
					$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
					$cnoreg = $d1u['noreg'];
					$cumur = intval(substr($d1u['umur'],0,2));

					if($cnoreg){
						$jenis='DEWASA';
						if($cumur>60){
							$jenis='GERIATRI';
						}else if($cumur>17 and $cumur<=60){
							$jenis='DEWASA';							
						}
					}

					//anak
					$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_ANAK where noreg='$dl[noreg]' and (dpjp <> '' or dpjp is not null)";
					$h1u  = sqlsrv_query($conn, $qu);        
					$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
					$cnoreg = $d1u['noreg'];

					if($cnoreg){
						$jenis='ANAK';
					}

					//neonatus
					$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_NEONATUS where noreg='$dl[noreg]' and (dpjp <> '' or dpjp is not null)";
					$h1u  = sqlsrv_query($conn, $qu);        
					$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
					$cnoreg = $d1u['noreg'];

					if($cnoreg){
						$jenis='NEONATUS';
					}


					//neonatus
					$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_BERSALIN where noreg='$dl[noreg]' and (dpjp <> '' or dpjp is not null)";
					$h1u  = sqlsrv_query($conn, $qu);        
					$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
					$cnoreg = $d1u['noreg'];

					if($cnoreg){
						$jenis='BERSALIN';
					}

					$namadokter = $d1ui[NAMA];

					if(empty($namadokter)){
						$kodedokter  = $dl['noreg'];
						$q2		= "select nama from afarm_dokter where kodedokter='$kodedokter'";
						$hasil2  = sqlsrv_query($conn, $q2);			  					
						$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
						$namadokter	= $data2[nama];
						if(empty($namadokter)){
							$namadokter = 'Dokter tidak ditemukan';
						}
					}




					if($JENIS2=='RI'){
						echo 
						"<tr>
						<td>$no</td>
						<td>$dl[noreg]<br>$tglmasuk</td>
						<td>$NAMAUNIT</td>
						<td>$namadokter</td>
						<td>
						<a href='rm1.php?id=$cid|$user' class='btn btn-primary btn-sm'><i class='bi bi-x-circle'></i> REKAM MEDIK UTAMA ($jenis)</a>
						<br>
						<a href='rm2.php?id=$cid|$user' class='btn btn-success btn-sm'><i class='bi bi-x-circle'></i> ASESMEN TERPADU</a>
						<a href='#' class='btn btn-warning btn-sm''><i class='bi bi-x-circle'></i> PERSETUJUAN & KONSULTASI</a>
						<a href='#' class='btn btn-danger btn-sm''><i class='bi bi-x-circle'></i> TINDAKAN & OPERASI</a>
						<a href='#' class='btn btn-info btn-sm''><i class='bi bi-x-circle'></i> PERMINTAAN KHUSUS</a>
						<a href='rm3.php?id=$cid|$user' class='btn btn-light btn-sm''><i class='bi bi-x-circle'></i> RINGKASAN PASIEN PULANG</a>
						<a href='#' class='btn btn-secondary btn-sm''><i class='bi bi-x-circle'></i> PENUNJANG MEDIK</a>
						</td>
						</tr>";
					}else{

						if($JENIS2=='RJS' OR $JENIS2=='RJU' ){
							$ql22="
							SELECT TOP(100) id,kodedokter,noreg,userid,dpjp,subjektif,objektif,assesment,planning,instruksi,sbu,
							CONVERT(VARCHAR, tanggal, 101) as tgl2,
							CONVERT(VARCHAR, tglentry, 8) as tgl3, 
							CONVERT(VARCHAR, tglentry, 20) as tgl4,'SOAP' as jenis  
							FROM ERM_SOAP WHERE noreg='$dl[noreg]'
							";
							$hql22 = sqlsrv_query($conn, $ql22);
							while   ($dhql22 = sqlsrv_fetch_array($hql22, SQLSRV_FETCH_ASSOC)){   
								$jam_ccpt = substr($dhql22[tgl3],0,5);

								$subjektif = nl2br($dhql22[subjektif]);
								$objektif = nl2br($dhql22[objektif]);
								$assesment = nl2br($dhql22[assesment]);
								$planning = nl2br($dhql22[planning]);

								$hasilassesment = "
								<b>Subject :</b> $subjektif<br>
								<b>Object :</b> $objektif<br>
								<b>Assesment :</b> $assesment<br>
								<b>Plan :</b> $planning
								";

							}

							echo 
							"<tr>
							<td>$no</td>
							<td>$dl[noreg]<br>$dl[tglmasuk]</td>
							<td>$NAMAUNIT</td>
							<td>$namadokter</td>
							<td>
							$hasilassesment 
							</td>
							</tr>";
						}else{
							$namadokter = "DOKTER IGD";

							$qPeriksaUmum = "SELECT a.KESADARAN, b.ID, b.IDHEADER, b.JAM, b.AIRWAY, b.E, b.V, b.M, b.N, b.SISTOLE, b.T, b.PERNAFASAN, b.SPO, b.RENCANA, b.USERID, b.INDIKASI_PREVENTIF, b.INDIKASI_KURATIF, b.INDIKASI_REHABILITATIF, b.INDIKASI_PALIATIF, b.DIASTOLE, b.RITME FROM ERM_PERIKSA_UMUM AS a LEFT OUTER JOIN
							ERM_IGD_RENCANA_HDR AS b ON b.IDHEADER = a.IDHEADER WHERE (a.NOREG = '$dl[noreg]')";
							$hPeriksaUmum = sqlsrv_query($conn, $qPeriksaUmum);
							$dPeriksaUmum = sqlsrv_fetch_array($hPeriksaUmum, SQLSRV_FETCH_ASSOC);

							$objektif =
							"Kesadaran :".$dPeriksaUmum['KESADARAN']."\n".
							"GCS - E : ".$dPeriksaUmum['E']." V :".$dPeriksaUmum['V']." M :".$dPeriksaUmum['M']."\n".
							"Tensi :".$dPeriksaUmum['SISTOLE'].' / '.$dPeriksaUmum['DIASTOLE']."\n".
							"Nadi : ".$dPeriksaUmum['N']."Ritme : ".$dPeriksaUmum['RITME']."Nyeri : ".$de['DERAJATNYERI']."Lokasi Nyeri : ".$de['LOKASINYERI']."\n".
							"Ket Diet : ".$de['KETDIET']."\n";


							$subjektif = nl2br($dhql22[subjektif]);
							$objektif = nl2br($objektif);
							$assesment = nl2br($dhql22[assesment]);
							$planning = nl2br($dhql22[planning]);

							$hasilassesment = "
							<b>Subject :</b> $subjektif<br>
							<b>Object :</b> $objektif<br>
							<b>Assesment :</b> $assesment<br>
							<b>Plan :</b> $planning
							";


							echo 
							"<tr>
							<td>$no</td>
							<td>$dl[noreg]<br>$dl[tglmasuk]</td>
							<td>$NAMAUNIT</td>
							<td>$namadokter</td>
							<td>
							$hasilassesment 
							</td>
							</tr>";
						}
					}

					$no += 1;
				}

				?>

			</table>

		</form>

		<font size='2px'>
		</font>

	</body>
</div>