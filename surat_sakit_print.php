<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idresep = $row[2]; 
$edit = $row[2]; 

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

// $nama     = $data2[nama];
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
// $umur = '33 Tahun';
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

$qe="
SELECT *,CONVERT(VARCHAR, TANGGAL1, 103) as TANGGAL1,CONVERT(VARCHAR, TANGGAL2, 103) as TANGGAL2,CONVERT(VARCHAR, TGLENTRY, 103) as TGLENTRY
FROM  ERM_SURAT_SAKIT
where noreg='$noreg'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$hari = $de['HARI'];
$tgl1 = $de['TANGGAL1'];
$tgl2 = $de['TANGGAL2'];
$alamatpasien = $de['ALAMAT'];
if(empty($alamatpasien)){
	$alamat = $alamatpasien;
}
$pekerjaan = $de['PEKERJAAN'];
$userid = $de['USERID'];
$nomor = $de['NOMOR'];
$diagnosa = $de['DIAGNOSA'];
$tglentry = $de['TGLENTRY'];
$nama = $de['NAMA_PASIEN'];
$keterangan = $de['KETERANGAN'];

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>SURAT KETERANGAN SAKIT</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.nama_obat.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<!-- <a href='rpo.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a> -->
					<br><br>
					<div class="row">
						<div class="col-4">
							<img src='<?php echo $logo; ?>' width='150px'></img>
						</div>
						<div class="col-8">
							<h5><b><?php echo $nmrs; ?></b></h5>
							<?php echo $alamat; ?>
						</div>
					</div>
					<hr>

					<div class="row">
						<div class="col-12 text-center">
							<h5>
								<u>SURAT KETERANGAN SAKIT</u>
							</h5>
							No : <?php echo $nomor;?> <br><br>
						</div>
					</div>

					<div class="row">

						<table width='100%' border='0'>
							<tr>
								<td>
									<div class="row">
										<div class="col-12">
											Yang bertanda tangan di bawah ini, menerangkan bahwa : 
										</div>
									</div>

									<div class="row">
										<div class="col-4">
											Nama
										</div>
										<div class="col-8">
											: 
											<?php echo $nama;?>
										</div>
									</div>


									<div class="row">
										<div class="col-4">
											Pekerjaan
										</div>
										<div class="col-8">
											: 
											<?php echo $pekerjaan;?>
										</div>
									</div>

									<div class="row">
										<div class="col-4">
											Alamat
										</div>
										<div class="col-8">
											: 
											<?php echo $alamatpasien;?>
										</div>
									</div>

									<div class="row">
										<div class="col-4">
											Umur
										</div>
										<div class="col-8">
											: 
											<?php echo $umur;?>
										</div>
									</div>

									<div class="row">
										<div class="col-12">
											Perlu beristirahat / membatasi aktivitas fisik, karena sakit selama <font size='4'><?php echo $hari;?></font> Hari, terhitung tanggal : <?php echo $tgl1;?> sampai dengan : <?php echo $tgl2;?>
										</div>
									</div>

									<div class="row">
										<div class="col-4">
											Keterangan
										</div>
										<div class="col-8">
											: 
											<?php echo $keterangan;?>
										</div>
									</div>

									<div class="row">
										<div class="col-12">
											Harap yang berkepentingan maklum.
										</div>
									</div>

<!-- 									<div class="row">
										<div class="col-4">
											Diagnosa
										</div>
										<div class="col-8">
											: 
											<?php echo $diagnosa;?>
										</div>
									</div>
								-->
								<br><br><br>

								<div class="row">
									<div class="col-4">
										
									</div>
									<div class="col-8">
										Gresik, <?php echo $tglentry; ?>
										<br>Dokter yang memeriksa<br>

										<?php 
										if($userid){

											$kodedokter = substr($userid, 0,3);
											$qu2="SELECT NAMA FROM AFARM_DOKTER where KODEDOKTER='$kodedokter'";
											$h1u2  = sqlsrv_query($conn, $qu2);        
											$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
											$nama_dokter = trim($d1u2['NAMA']);

											if(empty($nama_dokter)){
												$nama_dokter = $userid;
											}

											echo "<br>";

											$verif_dokter="Surat Keterangan Sakit ini telah diVerifikasi Oleh : ".$nama_dokter." Pada Tanggal : ".$tglentry; 

											QRcode::png($verif_dokter, "image.png", "L", 2, 2);   
											echo "<left><img src='image.png'></left>";
											echo "<br>";
											echo $nama_dokter;

												// echo $userid;

										}
										?>
									</div>
								</div>



							</td>
						</tr>	



					</table>


				</div>


			</form>
		</font>
	</body>
</div>
</div>