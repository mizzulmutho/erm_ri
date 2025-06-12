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


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>SEP ZIP</title>  
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

</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.nama_obat.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href="javascript:window.close();" class='btn btn-warning'><i class="bi bi-x-circle"></i> Close Window</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<br>
					<br>
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
							<b>SEP</b><br>
						</div>
					</div>

					<br>

					<table width='100%' border='1'>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										&nbsp; Tanggal
									</div>
									<div class="col-8">
										: <input class="" name="tgl" value="<?php echo $tgl;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
									</div>
								</div>
							</td>
						</tr>

						<?php
						$qsep="SELECT NOSEP FROM ARM_REGISTER where noreg='$noreg'";
						$hqsep  = sqlsrv_query($conn, $qsep);        
						$dhqsep  = sqlsrv_fetch_array($hqsep, SQLSRV_FETCH_ASSOC); 
						$nosep = trim($dhqsep['NOSEP']);
						?>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										&nbsp; Nomor SEP
									</div>
									<div class="col-8">
										: 
										<input class="" name="nosep" value="<?php echo $nosep;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
										<input type='submit' name='simpan' value='simpan' onfocus="nextfield ='done';" style="color: white;background: #66CDAA;border-color: #66CDAA;">
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										&nbsp; Nomor Grouping
									</div>

									<?php
									$filename = $nosep."klaim".".pdf";
									$filepath = "pdf_klaim/" . $filename;

									if (file_exists($filepath)) {
										$file_to_display = $filename;
									} else {
										$file_to_display = "File tidak ditemukan";
									}
									?>

									<div class="col-8">
										: 
										<!-- <input class="" name="noina" value="<?php echo $noina;?>" id="" type="text" size='50' onfocus="nextfield ='';" > -->
										<input class="" name="noina" value="<?php echo $file_to_display; ?>" id="" type="text" size="50" onfocus="nextfield ='';" readonly>

										<a href='download_ina.php?id=<?php echo $id."|".$user."|".$nosep; ?>' class='btn btn-warning btn-sm'>
											<i class="bi bi-cloud-download-fill"></i> Download
										</a>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										<a href='resume_print_bpjs.php?id=<?php echo $id."|".$user."|".$nosep."|".$noreg."|".$file_to_display; ?>' target='_blank' class='btn btn-success btn-sm'>
											<i class="bi bi-cloud-download-fill"></i> Cetak
										</a>
									</div>
									<div class="col-8">
									</div>
								</div>
							</td>
						</tr>

					</table>
					<br>
					
					<br>
				</form>
			</font>
		</body>
	</div>
</div>

<?php 


if (isset($_POST["simpan"])) {

	$nosep	= $_POST["nosep"];

	$q  = "update ARM_REGISTER set NOSEP='$nosep' where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}


	echo "
	<script>
	alert('".$eror."');
	window.location.replace('sep.php?id=$id|$user');
	</script>
	";

}


?>