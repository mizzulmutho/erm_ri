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
$KODEUNIT = $d1u['KODEUNIT'];
$KET1 = $d1u['KET1'];
$NORM = $d1u['NORM'];

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



$q2       = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan,NOKTP, 
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
				<div class="row">
					<div class="col-6">
						<table cellpadding="10px">
							<tr valign="center">
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
						<b><font size='4px'>HISTORY CPPT PASIEN PER PPA</font></b><br>
					</div>
				</div>
				<hr> 


				<table  class="table table-bordered">
					<?php

					$ql="
					SELECT DISTINCT kodedokter
					FROM ERM_SOAP WHERE norm='$norm' and noreg like '%$noreg%'
					";

					$hl  = sqlsrv_query($conn, $ql);
					$no=1;
					echo 
					"<tr bgcolor='#969392'>
					<td width='10%'>No</td>
					<td width='30%'>Profesional Pemberi Asuhan</td>
					<td width='60%'>Keterangan</td>	
					</tr>";

					$tampil_ass="Y";
					$no=1;

					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){ 

						$kodedokter = trim($dl[kodedokter]);
						$noreg = trim($dl[noreg]);
						$dokter_ass	= substr($kodedokter,0,3);

						$q2		= "select nama from afarm_dokter where kodedokter ='$kodedokter'";
						$hasil2  = sqlsrv_query($conn, $q2);			  					
						$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
						$namadokter	= $data2[nama];
						$profesi = 'DOKTER';

						if(empty($namadokter)){
							$q2		= "select nama from  MASTER_APOTEKER where apoteker='$kodedokter'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];
							$profesi = 'APOTEKER';
						}

						if(empty($namadokter)){
							$q2		= "select nama from afarm_paramedis where kode='$kodedokter'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];
							$profesi = 'PERAWAT';
						}

						echo 
						"<tr>
						<td>$no</td>
						<td>$namadokter ($kodedokter)</td>
						<td><a href='r_soap_dokter3.php?id=$id|$user|$kodedokter'>Lihat Rekap CPPT</a></td>	
						</tr>";

						$no += 1;
					}
					

					?>
				</table>
			</font>
		</form>
	</font>
</body>
</div>