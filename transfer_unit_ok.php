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

//resep
$q="
SELECT        TOP (200) CONVERT(VARCHAR, W_EResep_R.TglEntry, 25) as tglentry , 
W_EResep_R.KodeR, AFarm_MstObat.NAMABARANG, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, W_EResep_R.WaktuPakai, W_EResep_R.Keterangan, 
W_EResep_R.Satuan, Afarm_MstSatuan.NAMASATUAN
FROM            W_EResep_R INNER JOIN
AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG INNER JOIN
Afarm_MstSatuan ON W_EResep_R.Satuan = Afarm_MstSatuan.KODESATUAN
WHERE        (W_EResep_R.IdResep = $idresep)
";
$hasil  = sqlsrv_query($conn, $q);  
$no=1;
while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

	$tgl = $data[tglentry];
	$nama_obat = $data[NAMABARANG];
	$dosis = $data[AturanPakai];
	$jumlah = $data[Jumlah];
	$waktu_penggunaan = $data[WaktuPakai];

	$q  = "insert into ERM_RI_RPO(noreg,userid,tglentry,tgl,nama_obat,dosis,jumlah,waktu_penggunaan) 
	values ('$noreg','$user','$tgl','$tgl','$nama_obat','$dosis','$jumlah','$waktu_penggunaan')";
	$hs = sqlsrv_query($conn,$q);

	$no += 1;

}

if($edit){
	$q3       = "select CONVERT(VARCHAR, tgl, 103) as tgl, nama_obat, jumlah, dosis, waktu_penggunaan,
	interval, dokter, apoteker, periksa, pemberi, keluarga
	from ERM_RI_RPO
	where id='$idresep'";
	$hasil3  = sqlsrv_query($conn, $q3);  
	$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
	$nama_obat = $data3[nama_obat];
	$interval = $data3[interval];
	$dokter = $data3[dokter];
	$apoteker = $data3[apoteker];
	$periksa = $data3[periksa];
	$pemberi = $data3[pemberi];
	$keluarga = $data3[keluarga];

}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>RPO</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

</head> 
<div id="content"> 

	<body onload="document.myForm.nama_obat.focus();">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<!-- <a href='rpo.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a> -->
				<br><br>
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

					<div class="col-8">
						<iframe src="http://192.168.10.194:3010/rekam_medik/entry_erm/rawat_ok/<?php echo $KODEUNIT;?>/<?php echo $noreg; ?>/<?php echo $norm; ?>/resep/" width="100%" height="800" scrolling="yes" style="overflow:hidden; margin-top:-4px; margin-left:-4px; border:none;"></iframe>						
					</div>
				</div>




			</form>
		</font>
	</body>
</div>
