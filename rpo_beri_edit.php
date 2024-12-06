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
$idrpo = $row[2]; 
$idrpo_beri = $row[3];

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

$q3       = "select nomor,eresep from ERM_RI_RPO_header where id='$idrpo'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$nomor = trim($data3[nomor]);
$cek_eresep = $data3[eresep];


$q3b       = "select CONVERT(VARCHAR, tgl, 25) as tglrpo, nama_obat, jumlah, dokter, apoteker, periksa, pemberi, keluarga
from ERM_RI_RPO_BERI
where id='$idrpo_beri'";
$hasil3b  = sqlsrv_query($conn, $q3b);  
$data3b    = sqlsrv_fetch_array($hasil3b, SQLSRV_FETCH_ASSOC);                      

$nama_obat = $data3b[nama_obat];
$tglrpo = $data3b[tglrpo];
$jumlah = $data3b[jumlah];
$dokter = $data3b[dokter];
$apoteker = $data3b[apoteker];
$periksa = $data3b[periksa];
$pemberi = $data3b[pemberi];
$keluarga = $data3b[keluarga];


if(empty($tglrpo)){
	$tgli = date('Y-m-d\TH:i:s', strtotime($tgl)); 
}else{
	$tgli = date('Y-m-d\TH:i:s', strtotime($tglrpo)); 
}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>RPO DETAIL</title>  
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


	<script>
		$(function() {
			$("#obat").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.KODEBARANG + ' - ' + item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#apoteker").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_apoteker.php', //your                         
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
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.nama_obat.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='rpo2.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
<!-- 					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
-->					&nbsp;&nbsp;
<!-- <a href="http://192.168.10.4:1234/rekam_medik/entry_tindakan/rawat_inap/<?php echo $KODEUNIT; ?>/<?php echo $noreg; ?>/<?php echo $norm; ?>/resep/" target="_blank" class='btn btn-success'><i class="bi bi-box-arrow-in-right"></i> Buat E-Resep</a> -->
&nbsp;&nbsp;
<!-- <a href='eresep_list.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-success'><i class="bi bi-box-arrow-in-right"></i> Ambil dari E-Resep</a> -->
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
			<hr>

			<div class="row">
				<div class="col-12 text-center">
					<b>REKAM PEMBERIAN OBAT</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Nama obat
							</div>
							<div class="col-8">
								: <input class="" name="nama_obat" value="<?php echo $nama_obat;?>" id="obat" type="text" size='50' onfocus="nextfield ='dosis';" placeholder="Isikan Nama Obat">
							</div>
						</div>
<!-- 						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Dosis
							</div>
							<div class="col-8">
								: <input class="" name="dosis" value="<?php echo $dosis;?>" id="obat" type="text" size='50' onfocus="nextfield ='jumlah';" placeholder="Isikan Dosis">
							</div>
						</div>
					-->						
					<div class="row">
						<div class="col-4">
							&nbsp;&bull; Jumlah
						</div>
						<div class="col-8">
							: <input class="" name="jumlah" value="<?php echo $jumlah;?>" id="obat" type="text" size='50' onfocus="nextfield ='interval';" placeholder="Isikan Jumlah">
						</div>
					</div>
				</td>
			</tr>


			<tr>
				<td>
					<div class="row">
						<div class="col-4">
							&nbsp;&bull; Tanggal / Jam
						</div>
						<div class="col-8">
							: <input class="" name="tgl" value="<?php echo $tgli;?>" id="" type="datetime-local" size='50' onfocus="nextfield ='';" >
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
							<?php if(empty($cek_eresep)){ ?>
								<br>
								<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
								<br><br>
							<?php } ?>
						</div>
					</div>
				</td>
			</tr>	



			<tr>
				<td>

					<table width="100%">
						<tr>
							<td>
								Dokter<br>
								<input class="" name="dokter" value="<?php echo $dokter;?>" id="dokter" type="text" size='' onfocus="nextfield ='';" placeholder="">
							</td>
							<td>
								Apoteker<br>
								<input class="" name="apoteker" value="<?php echo $apoteker;?>" id="apoteker" type="text" size='' onfocus="nextfield ='';" placeholder="">
							</td>
							<td>
								Keluarga<br>
								<input class="" name="keluarga" value="<?php echo $nama;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
							</td>
							<td width="25%">
								Pemberi<br>
								<input class="" name="pemberi" value="<?php echo $user;?>" id="" type="text" size='30' onfocus="nextfield ='';" placeholder="">
							</td>
							<td width="25%">
								Pemeriksa<br>
								<input class="" name="periksa" value="<?php echo $periksa;?>" id="" type="text" size='30' onfocus="nextfield ='';" placeholder="">
							</td>							
						</tr>
					</table>

				</td>
			</tr>

		</table>


		<br>
	</form>
</font>
</body>
</div>
</div>

<?php 

if (isset($_POST["simpan"])) {
	// echo "edit_data";
	$tgl		= $_POST["tgl"];
	$tgl3 = date('Y-m-d H:i:s', strtotime($tgl)); 

	$jumlah		= $_POST["jumlah"];
	$dokter		= $_POST["dokter"];
	$apoteker	= $_POST["apoteker"];
	$periksa	= $_POST["periksa"];
	$pemberi	= $_POST["pemberi"];
	$keluarga	= $_POST["keluarga"];

	$q  = "update ERM_RI_RPO_BERI set jumlah='$jumlah',dokter='$dokter',apoteker='$apoteker',periksa='$periksa',pemberi='$pemberi',keluarga='$keluarga',tgl='$tgl3' where id=$idrpo_beri";
	$hs = sqlsrv_query($conn,$q);		

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";


}




?>