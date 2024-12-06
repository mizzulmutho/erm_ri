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

<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">

				<br>
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

					<div class="col-12">
						<center>
							<b>SKRINING MANAJER PELAYANAN PASIEN (MPP)</b>
						</center>
						<hr>
						<?php
						$q2       = "
						SELECT NORM, KODEDEPT, NIK, NAMA, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS KELAMIN,
						ALAMATPASIEN, KOTA, KODEKEL, TLP, TMPTLAHIR, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR, 
						CONVERT(VARCHAR, tgllahir, 103) as TGLLAHIR, 
						JENISPEKERJAAN, PENDIDIKAN, JABATAN, GOLDARAH, KELAS, AGAMA,
						KELAS_PLAFON, PERUSH_ASAL, NOBPJS, ALERGI, NOKTP,NOMOR_PASPOR, 
						NAMA_IBU_KANDUNG, NOKTP_IBU_KANDUNG, JENISKELAMIN2, AGAMA2, SUKU, BAHASA, RT, RW, KELURAHAN, KECAMATAN, KABUPATEN, KODEPOS, PROVINSI, NEGARA, ALAMAT_DOMISILI, RT_DOMISILI, 
						RW_DOMISILI, KELURAHAN_DOMISILI, KECAMATAN_DOMISILI, KABUPATEN_DOMISILI, KODEPOS_DOMISILI, PROVINSI_DOMISILI, NEGARA_DOMISILI, HP, STATUS_PERNIKAHAN, X_PERKIRAAN_UMUR, 
						X_LOKASI_DITEMUKAN, X_TGL_DITEMUKAN, X_NAMA_TANGGUNG_JAWAB, X_HP_TANGGUNG_JAWAB, X_HUBUNGAN_PASIEN, X_NAMA_PENGANTAR, X_HP_PENGANTAR, PASPOR, JAM_LAHIR
						from Afarm_MstPasien where norm='$norm'";
						$hasil2  = sqlsrv_query($conn, $q2);                

						$data2    = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);             

						$kodedept = $data2[KODEDEPT];

						$nama     = $data2[NAMA];
						$kelamin  = $data2[KELAMIN];
						$nik = trim($data2[NIK]);
						$alamatpasien  = $data2[ALAMATPASIEN];
						$kota     = $data2[KOTA];
						$kodekel  = $data2[KODEKEL];
						$telp     = $data2[TLP];
						$tmptlahir     = $data2[TMPTLAHIR];
						$tgllahir = $data2[TGLLAHIR];
						$jenispekerjaan     = $data2[JENISPEKERJAAN];
						$jabatan  = $data2[JABATAN];
						$umur =  $data2[UMUR];
						$noktp =  $data2[NOKTP];
						$nobpjs =  $data2[NOBPJS];

						echo "<br>";
						echo "Tgl MRS : ";echo "<br>";
						echo "Diagnosa : ";echo "<br>";
						echo "DPJP : ";echo "<br>";

						echo '<table>';
						echo '<tr>';
						echo '<td>A. Skrining Pasien</td><td>: </td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>B. Ringkasan Asesmen</td><td>: </td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>C. Identifikasi Masalah</td><td>: </td>';
						echo '</tr>';
						echo '<tr>';
						echo '<td>D. Perencanaan Tindak Lanjut</td><td>: </td>';
						echo '</tr>';
						echo '</table>';
						?>
					</div>
				</div>

			</div>
		</form>
		
		<font size='2px'>
		</font>

	</body>
</div>