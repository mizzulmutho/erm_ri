<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include ("mode.php");

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

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


$qi="SELECT noreg FROM ERM_SURAT_SAKIT where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_SURAT_SAKIT(noreg,userid,tglentry) values ('$noreg','$user','$tglinput')";
	$hs = sqlsrv_query($conn,$q);
}else{

// NOREG
// HARI
// TANGGAL1
// TANGGAL2
// ALAMAT
// PEKERJAAN
// USERID
// TGLENTRY
// NOMOR
// DIAGNOSA

	$qe="
	SELECT *,CONVERT(VARCHAR, TANGGAL1, 23) as TANGGAL1,CONVERT(VARCHAR, TANGGAL2, 23) as TANGGAL2
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
	$keterangan = $de['KETERANGAN'];
}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>SURAT SAKIT</title>  
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
			$("#icd10").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd10.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
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
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
					&nbsp;&nbsp;
					<br>
					<br>
<!-- 				<div class="row">
					<div class="col-12 text-center bg-success text-white"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
				</div>
			-->				
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


			<div class="row">
				<?php include('menu_dokter.php');?>
			</div>
			
			<hr>

			<div class="row">
				<div class="col-12 text-center">
					<b>SURAT KETERANGAN SAKIT</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='0'>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Nomor
							</div>
							<div class="col-8">
								: 
								<input class="" name="nomor" value="<?php echo $nomor;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Nama
							</div>
							<div class="col-8">
								: 
								<input class="" name="nama" value="<?php echo $nama;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>


						<div class="row">
							<div class="col-4">
								Pekerjaan
							</div>
							<div class="col-8">
								: 
								<input class="" name="pekerjaan" value="<?php echo $pekerjaan;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Alamat
							</div>
							<div class="col-8">
								: 
								<input class="" name="alamatpasien" value="<?php echo $alamatpasien;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Umur
							</div>
							<div class="col-8">
								: 
								<input class="" name="umur" value="<?php echo $umur;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Terhitung Tanggal
							</div>
							<div class="col-8">
								: 
								<input class="" name="tgl1" value="<?php echo $tgl;?>" id="" type="date" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Sampai Dengan
							</div>
							<div class="col-8">
								: 
								<input class="" name="tgl2" value="<?php echo $tgl;?>" id="" type="date" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Selama
							</div>
							<div class="col-8">
								: 
								<input class="" name="hari" value="<?php echo $hari;?>" id="" type="number" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Diagnosa
							</div>
							<div class="col-8">
								: 
								<input class="" name="diagnosa" value="<?php echo $diagnosa;?>" id="icd10" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>

						<div class="row">
							<div class="col-4">
								Keterangan
							</div>
							<div class="col-8">
								: 
								<input class="" name="keterangan" value="<?php echo $keterangan;?>" id="keterangan" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>



					</td>
				</tr>	

				
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;
							</div>
							<div class="col-8">
								<br>

								<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>

								<?php if($diagnosa){?>
									&nbsp;&nbsp;
									<a href='surat_sakit_print.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a>					
									&nbsp;&nbsp;
								<?php } ?>

							</div>
						</div>
					</td>
				</tr>	
			</table>

		</form>
	</font>
</body>
</div>
</div>

<?php 
if (isset($_POST["simpan"])) {

	$nomor	= $_POST["nomor"];
	$nama	= $_POST["nama"];
	$tgl1	= $_POST["tgl1"];
	$tgl2	= $_POST["tgl2"];
	$pekerjaan	= $_POST["pekerjaan"];
	$alamatpasien	= $_POST["alamatpasien"];
	$diagnosa	= $_POST["diagnosa"];
	$hari	= $_POST["hari"];
	$keterangan	= $_POST["keterangan"];

	echo 	$q  = "update ERM_SURAT_SAKIT set NOREG='$noreg',HARI='$hari',TANGGAL1='$tgl1',TANGGAL2='$tgl2',ALAMAT='$alamatpasien',PEKERJAAN='$pekerjaan',USERID='$user',TGLENTRY='$tglinput',NOMOR='$nomor',DIAGNOSA='$diagnosa',NAMA_PASIEN='$nama',KETERANGAN='$keterangan' where noreg='$noreg'";
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




?>