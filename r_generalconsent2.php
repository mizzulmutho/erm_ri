<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

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


$qi="SELECT noreg FROM ERM_RI_GENERALCONSENT where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_GENERALCONSENT(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_GENERALCONSENT
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC);
	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment']; 
	$tgl = $de['tgl'];
	$gc1 = $de['gc1'];
	$gc2= $de['gc2'];
	$gc3= $de['gc3'];
	$gc4= $de['gc4'];
	$gc5= $de['gc5'];
	$gc6= $de['gc6'];
	$gc7= $de['gc7'];
	$gc8= $de['gc8'];
	$gc9= $de['gc9'];
	$gc10= $de['gc10'];
	$gc11= $de['gc11'];
	$gc12= $de['gc12'];
	$gc13= $de['gc13'];
	$gc14= $de['gc14'];
	$gc15= $de['gc15'];
	$gc16= $de['gc16'];
	$gc17= $de['gc17'];
	$gc18= $de['gc18'];
	$gc19= $de['gc19'];
	$gc20= $de['gc20'];
	$gc21= $de['gc21'];
	$gc22= $de['gc22'];
	$gc23= $de['gc23'];
	$gc24= $de['gc24'];
	$gc25= $de['gc25'];
	$gc26= $de['gc26'];
	$gc27= $de['gc27'];
	$gc28= $de['gc28'];
	$gc29= $de['gc29'];
	$gc30= $de['gc30'];
	$gc31= $de['gc31'];
	$gc32= $de['gc32'];
	$gc33= $de['gc33'];
	$gc34= $de['gc34'];
	$gc35= $de['gc35'];
	$gc36= $de['gc36'];
	$gc37= $de['gc37'];
	$gc38= $de['gc38'];
	$gc39= $de['gc39'];
	$gc40= $de['gc40'];
	$gc41= $de['gc41'];
	$gc42= $de['gc42'];
	$gc43= $de['gc43'];
	$gc44= $de['gc44'];
	$gc45= $de['gc45'];
	$gc46= $de['gc46'];
	$gc47= $de['gc47'];
	$gc48= $de['gc48'];
	$gc49= $de['gc49'];
	$gc50= $de['gc50'];
	$gc51= $de['gc51'];
	$gc52= $de['gc52'];
	$gc53= $de['gc53'];
	$gc54= $de['gc54'];
	$gc55= $de['gc55'];
	$gc56= $de['gc56'];
	$gc57= $de['gc57'];
	$gc58= $de['gc58'];
	$gc59= $de['gc59'];
	$gc60= $de['gc60'];

	$gc61= $de['gc61']; 
	$gc62= $de['gc62']; 
	$gc63= $de['gc63'];
	$gc64= $de['gc64'];
	$gc64a= $de['gc64a'];
	$gc64b= $de['gc64b'];
	$gc64c= $de['gc64c'];
	$gc64d= $de['gc64d'];
	$gc64e= $de['gc64e'];
	$gc65= $de['gc65'];
	$gc66= $de['gc66'];
	$gc66a= $de['gc66a'];
	$gc67= $de['gc67'];
	$gc68= $de['gc68'];
	$gc69= $de['gc69'];
	$gc70= $de['gc70'];
	$gedung= $de['gedung'];
	$ketlain= $de['ketlain'];
	$gc71= $de['gc71'];
	$darikelas= $de['darikelas'];
	$kekelas= $de['kekelas'];
	$asuransilain= $de['asuransilain'];
	$penanggung= $de['penanggung'];
	$plafon= $de['plafon'];
	$hakkelas= $de['hakkelas'];
	$permintaankelas= $de['permintaankelas'];
	$sementarakelas= $de['sementarakelas'];
	$nama2= $de['nama2'];
	$tgllahir2= $de['tgllahir2'];
	$kelamin2= $de['kelamin2'];
	$alamat2= $de['alamat2'];
	$telp2= $de['telp2'];
	$adalah= $de['adalah'];


}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>General Consent</title>  
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
			$("#dokter2").autocomplete({
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
			$("#karyawan1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_karyawan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nik + ' - ' + item.nama
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
			$("#karyawan2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_karyawan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nik + ' - ' + item.nama
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
			$("#karyawan3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_karyawan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nik + ' - ' + item.nama
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
			$("#icd101").autocomplete({
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
	<script>
		$(function() {
			$("#icd102").autocomplete({
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
	<script>
		$(function() {
			$("#icd91").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
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
	<script>
		$(function() {
			$("#icd92").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
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
	<script>
		$(function() {
			$("#icd93").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
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
	<script>
		$(function() {
			$("#icd94").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
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
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
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
					<p style="text-align: center; font-weight: bold;">PERSETUJUAN UMUM ( GENERAL CONSENT )</p>
					<table>
						<tr>
							<td>
								<b>Identitas Penanggung Jawab</b>
								<table width="100%" border="0" valign="top">
									<tr>
										<td width="20%">Nama</td>
										<td>: <?php echo $nama2;?></td>
									</tr>
									<tr>
										<td>Tanggal Lahir</td>
										<td>: <?php echo $tgllahir2;?></td>
									</tr>
									<tr>
										<td>Jenis Kelamin</td>
										<td>: <?php echo $kelamin2;?>
									</td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>: <?php echo $alamat2;?></td>
								</tr>
								<tr>
									<td>No Telp</td>
									<td>: <?php echo $telp2;?></td>
								</tr>
								<tr>
									<td>Hubungan dengan Pasien</td>
									<td>
										: <?php echo $adalah;?>
									</td>
								</tr>
							</table>
							<br>
							<b>Data Pasien</b>
							<table width="100%" border="0">
								<tr>
									<td width="20%">Nama Pasien</td>
									<td>: <?php echo $nama?></td>
								</tr>
								<tr>
									<td>Nomor Rekam Medis</td>
									<td>: <?php echo $norm?></td>
								</tr>
								<tr>
									<td>Tanggal Lahir Pasien</td>
									<td>: <?php echo $tgllahir?></td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>: <?php echo $alamatpasien?></td>
								</tr>
								<tr>
									<td>No Telp</td>
									<td>: <?php echo $telp?></td>
								</tr>
							</table>

							<br><br><br>
						</td>
					</tr>
					<tr>
						<td>
							<p style="text-align: center; font-weight: bold;">
								PASIEN DAN/ ATAU WALI HUKUM HARUS MEMBACA, MEMAHAMI<br> 
								DAN MENGISI INFORMASI BERIKUT
							</p>
						</td>
					</tr>
					<tr>
						<td>
							<table border='0'>
								<tr valign="top">
									<td>1</td>
									<td align="left">HAK DAN KEWAJIBAN SEBAGAI PASIEN. Saya mengakui bahwa pada proses pendaftaran untuk mendapatkan perawatan di <?php echo $nmrs; ?> dan penandatanganan dokumen ini, saya telah menerima Leaflet dan mendapat informasi tentang hak-hak dan kewajiban saya sebagai pasien.</td>
									<td width="10%" align="center">
										<?php if ($gc61=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>2</td>
									<td align="left">PERSETUJUAN PELAYANAN KESEHATAN. Saya menyetujui dan memberikan persetujuan untuk mendapat pelayanan kesehatan di <?php echo $nmrs; ?> dan dengan ini saya meminta dan memberikan kuasa kepada <?php echo $nmrs; ?>, dokter dan perawat, dan tenaga kesehatan lainnya untuk memberikan asuhan perawatan, pemeriksaan fisik yang dilakukan oleh dokter dan perawat dan melakukan prosedur diagnostik, radiologi dan/ atau terapi dan tatalaksana sesuai pertimbangan dokter yang diperlukan atau disarankan pada perawatan saya. Hal ini mencakup seluruh pemeriksaan dan prosedur diagnostik rutin, termasuk x-ray, pemberian dan/ atau tindakan medis serta penyuntikan (intramuskular, intravena) produk farmasi dan obat-obatan, pemasangan alat kesehatan termasuk pemasangan infuspemasangankateter,pemeriksaan/perawatangigi dan pengambilan darah untuk pemeriksaan laboratorium atau pemeriksaan patologiyang dibutukan untuk pengobatan dan tindakan yang aman. Tindakan medis lain khusus yang tercakup dalam persetujuan umum ini, akan dimintakan persetujuan secara terpisah.</td>
									<td width="10%" align="center">
										<?php if ($gc62=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>3</td>
									<td align="left">PEMILIHAN DPJP. Saya telah menerima dan memahami informasi mengenai dokter penanggung jawab pasien selama dirawat Rumah Sakit, maka saya memilih Dokter 
										<b>
											<?php
											$qu="
											SELECT        TOP (200) ERM_ASSESMEN_HEADER.id, ERM_ASSESMEN_HEADER.noreg, Afarm_DOKTER.NAMA,Afarm_DOKTER.KODEDOKTER
											FROM            ERM_ASSESMEN_HEADER INNER JOIN
											Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER
											where ERM_ASSESMEN_HEADER.noreg='$noreg'";
											$h1u  = sqlsrv_query($conn, $qu);        
											$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
											$NAMA = trim($d1u['NAMA']);
											$KODEDOKTER = trim($d1u['KODEDOKTER']);

											echo $NAMA.'('.$KODEDOKTER.')';
											?>
										</b>
									Sebagai dokter penanggung jawab.</td>
									<td width="10%" align="center">
										<?php if ($gc63=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>4</td>
									<td align="left">
										Saya memberi kuasa kepada <?php echo $nmrs; ?> untuk menjaga privasi dan kerahasian penyakit saya selama dalam perawatan(beri tanda (v) pada kotak) :
										<br>
										<?php 
										if ($gc64a=="1"){echo "&radic; Saya mengijinkan semua orang menjenguk saya";}
										if ($gc64a=="2"){echo "&radic; Saya mengijinkan semua orang menjenguk saya, kecuali, ".$gc64d;}
										if ($gc64a=="3"){echo "&radic; Saya tidak mengijinkansemua orang menjenguk saya, kecuali, ".$gc64e;}
										?>										
									</td>
									<td width="10%" align="center">
										<?php if ($gc64=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<div style='page-break-before: always;'></div>
							<table border='0'>
								<tr valign="top">
									<td>5</td>
									<td align="left">
										RAHASIA KEDOKTERAN. Saya setuju <?php echo $nmrs; ?> wajib menjamin rahasia kedokteran saya baik untuk kepentingan perawatan atau pengobatan, pendidikan maupun penelitian kecuali saya mengungkapkan sendiri atau orang lain yang saya beri kuasa sebagai Penjamin. 
									</td>
									<td width="10%" align="center">
										<?php if ($gc65=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>6</td>
									<td align="left">
										MEMBUKA RAHASIA KEDOKTERAN.Saya setuju untuk membuka rahasia kedokteran terkait dengan kondisi kesehatan, asuhan dan pengobatan yang saya terima kepada: 
										<br>a.	Dokter dan tenaga kesehatan lain yang memberikan asuhan kepada saya 
										<br>b.	Perusahaan asuransi kesehatan atau perusahaan lainnya atau pihak lain yang menjamin pembiayaan saya.
										<br>c.	Anggota keluarga saya : <?php echo $gc66a;?> 
									</td>
									<td width="10%" align="center">
										<?php if ($gc66=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>7</td>
									<td align="left">
										INFORMASI RAWAT INAP
										<br>a.	Saya tidak diperkenankan membawa barang-barang berharga yang tidak diperlukan (seperti: perhiasan, elektronik, dll) selama dalam perawatan di <?php echo $nmrs; ?>. Saya memahami dan menyetujui bahwa apabila saya membawanya, maka <?php echo $nmrs; ?> tidak bertanggung jawab terhadap kehilangan, kerusakan atau pencurian. 
										<br>b.	Bila tidak ada anggota keluarga yang bisa membawa pulang barang berharga tersebut, RS mengambil alih dan menyediakan tempat penitipan barang milik pasien di tempat penitipan barang milik pasien di tempat resmi yang telah disediakan RS.
										<br>c.	Saya telah menerima informasi tentang peraturan yang diberlakukan oleh rumah sakit dan saya beserta keluarga bersedia untuk mematuhinya, termasuk akan mematuhi jam berkunjung pasien sesuai dengan aturan di rumah sakit
										<br>d.	Anggota keluarga saya yang menunggu saya, bersedia untuk selalu memakai tanda pengenal khusus yang diberikan oleh RS, dan demi keamanan seluruh pasien setiap keluarga dan siapapun yang akan mengunjungi saya diluar jam berkunjung, bersedia untuk diminta/ diperiksa identitasnya dan memakai identitas yang diberikan oleh RS.
									</td>
									<td width="10%" align="center">
										<?php if ($gc67=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>8</td>
									<td align="left">
										PENGAJUAN KELUHAN
										Saya menyatakan bahwa saya telah menerima informasi tentang adanya tatacara mengajukan dan mengatasi keluhan terkait pelayanan medik yang diberikan terhadap diri saya. Saya setuju untuk mengikuti tatacara mengajukan keluhan sesuai prosedur yang ada.  Penanganan komplain nomor 081331706002. 
									</td>
									<td width="10%" align="center">
										<?php if ($gc68=="Ya"){echo "&radic;";}else{echo "&#x25a2;";}?>
									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>9</td>
									<td align="left" colspan="2">
										KEWAJIBAN PEMBAYARAN. 
										<br>a.	Menjadi pasien dengan status sebagai : (Klik salah satu 1/2/3 dibawah ini)
										<br>1)	PASIEN UMUM (beri tanda (&radic;) pada kotak) :
										<br>
										<?php
										if ($gc69=="UMUM"){echo "&radic; Pasien umum, karena tidak mempunyai asuransi";}
										if ($gc69=="ASURANSI"){echo "&radic; Pasien umum, karena dengan sadar tidak mau menggunakan Asuransi";}
										?>
										<br>Permintaan kelas : 
										<br>
										<?php
										if ($gc70=="1"){echo "&radic; Kelas 1 : Rp. 510.500/hari.";}
										if ($gc70=="2"){echo "&radic; Kelas 2 : Rp. 481.500/hari.";}
										if ($gc70=="3"){echo "&radic; Kelas 3 : Rp. 219.000/hari.";}
										if ($gc70=="4"){echo "&radic; Kelas VIP 1 : Rp. 936.000/hari.";}
										if ($gc70=="5"){echo "&radic; Kelas VIP 2	: Rp. 802.000/hari.";}
										if ($gc70=="6"){echo "&radic; Kelas VVIP  : Rp. 1.605.000/hari.";}
										if ($gc70=="7"){echo "&radic; Kelas PRESIDEN SUITE : Rp. 2.228.500/hari.";}
										if ($gc70=="8"){echo "&radic; Kamar Isolasi : Rp. /hari.";}
										if ($gc70=="9"){echo "&radic; Kamar ICU : Rp. /hari.";}
										?>
										
										<br>Gedung : <?php echo $gedung;?>
										<br>Keterangan Lain : <?php echo $ketlain;?>
										<br>&radic;	Harga diatas belum termasuk, jasa pelayanan, visite dokter, sewa alat kedokteran, tindakan medis yang dilakukan, obat dan pemeriksaan penunjang lainnya.
										<br>&radic;	Saya bertanggungjawab untuk membayar semua biaya yang timbul selama perawatan pasiendan dibayar lunas pada saat pasien selesai perawatan (pada saat pulang).
										<hr>
										2)	PASIEN BPJS KESEHATANdengan nomor identitas BPJS KESEHATAN/JKN :
										(beri tanda (&radic;) pada kotak)
										<br>
										<?php
										if ($gc71=="1"){echo "&radic; 2.1 TIDAK NAIK KELAS.";}
										if ($gc71=="2"){echo "&radic; 2.2 PASIEN BPJS KESEHATAN NAIK KELAS PERAWATAN 
										<br>
										*) Naik 1 tingkat (non VIP) bersedia membayar selisih biaya berdasarkan selisih tarif INACBGS
										<br>
										*) tidak mendapatkan print rincian biaya, dikarenakan untuk klaim ke BPJS 
										";}
										if ($gc71=="3"){echo "&radic; 2.3 PASIEN BPJS KESEHATAN NAIK 2 TINGKAT / UMUM
										<br>
										- Naik 2 tingkat atau lebih, dari kelas : $darikelas ke $kekelas
										maka secara otomatis kepesertaan BPJS dianggap gugur dan dinyatakan sebagai pasien umum dan bersedia membayar biaya perawatan.
										<br>
										- Saya berkewajiban menunjukkan kartu identitas peserta JKN dan persyaratan administrasi lainnya selambat-lambatnya 3 hari sejak dirawat atau sebelum pulang apabila dirawat kurang dari 3 hari.
										<br>
										- Apabila sampai waktu yang telah ditentukan, saya tidak dapat menunjukkan kartu identitas peserta JKN dan atau SEP tidak bisa dicetak karena kartu tidak aktif maka pasien dinyatakan sebagai PASIEN UMUM dan tidak dapat dijamin BPJS kesehatan (kecuali2.3).
										";}
										?>
										<hr>
										3)	PASIEN ASURANSI LAIN (sebutkan) : <?php echo $asuransilain;?>										
										<br>-	Apabila pasien/keluarga memilih kelas yang lebih tinggi dari haknya, maka selisih dari seluruh biaya perawatan, obat, pengunjung medis, jasa dokter, ditanggung oleh pasien/keluarga pasien dan dibayar lunas pada saat pasien selesai perawatan (pada saat pulang)
										<br>-	Instansi/asuransi yang menanggung : <?php echo $penanggung;?>
										<br>plafon : Rp. <?php echo $plafon;?>
										<br>-	Hak kelas perawatan : <?php echo $hakkelas;?>
										<br>permintaan kelas : <?php echo $permintaankelas;?>
										sementara kelas : <?php echo $sementarakelas;?>
										<br>-	Khusus untuk asuransi JASARAHARJA wajib menunjukkan surat keterangan kecelakaan dari kepolisian maksimal 1 x 24 jam sejak kejadian. Jika tidak, maka dianggap pasien umum. 
										<br>-	Apabila dalam proses perawatan, ternyata instansi/asuransi tidak menanggung pembiayaan maka seluruh biaya perawatan pasien ditanggung oleh pasien/keluarga.
										<br>
										<br>b.	Tidak akan mengganti status penjamin pasien selama dirawat di Rumah Sakit.
										<br>c.	Menyetujui peraturan tentang perpindahan pasien pasca tindakan/operasi ke kelas yang lebih tinggi
										<br>•	Apabila perpindahan kelas dilakukan sebelum 3 x 24 jam pasca operasi, maka perhitungan seluruh biaya akan dikenakan tarif kelas tertinggi yang ditempati.
										<br>•	Apabila perpindahan kelas diajukan setelah 3 x 24 jam pasca operasi, maka biaya operasi dan biaya tindakan yang terjadi sebelum perpindahan kelas akan dihitung sesuai dengan kelas yang ditempati saat dilakukan tindakan/operasi.
										<br>d.	Waktu pulang (check out) 
										Pulang diatas pukul 19.00 WIB     : dikenakan tambahan biaya kamar 100% (satu hari).

									</td>
								</tr>
							</table>
							<hr>
							<table border='0'>
								<tr valign="top">
									<td>10</td>
									<td align="left" colspan="2">
										SAYA JUGA MENYADARI DAN MEMAHAMI BAHWA
										<br>a.	Apabila saya tidak memberikan persetujuan, atau dikemudian hari mencabut persetujuan saya untuk melepaskan rahasia kedokteran saya kepada perusahaan asuransi yang saya tentukan, maka saya pribadi bertanggung jawab untuk membayar semua pelayanan dan tindakan medis dari <?php echo $nmrs; ?>. 
										<br>b.	Apabila rumah sakit membutuhkan proses hukum untuk menagih biaya pelayanan rumah sakit dari saya, saya memahami bahwa saya bertanggung jawab untuk membayar semua biaya yang disebabkan dari proses hukum tersebut. 
										<br><br>
										SAYA TELAH MEMBACA dan SEPENUHNYA SETUJU dengan setiap pernyataan yang terdapat pada formulir ini dan menandatangani tanpa paksaan dan dengan kesadaran penuh. 
										<br><br><br>
										Gresik,
										Tgl Input :<?php echo $tgl_assesment; ?> , Jam : <?php echo $jam_assesment; ?>
										<table width="100%">
											<tr>
												<td align="center">
													Pasien/Keluarga/Penanggungjawab,<br>
													<?php 
													$pernyataanpasien='Lembar General Consent ini telah disetujui oleh Keluarga Pasien dg nama:'.$gc16.' Hubungan dg pasien : '.$gc15.' - pada tanggal:'.$tgl;
													QRcode::png($pernyataanpasien, "image_pernyataanpasien.png", "L", 2, 2);
													echo "<center><img src='image_pernyataanpasien.png'></center>";
													echo $gc16;			
													?>
												</td>
												<td align="center">
													Pemberi Informasi,<br>
													<?php 
													QRcode::png($penanggungjawab, "image.png", "L", 2, 2);   
													echo "<center><img src='image.png'></center>";
													echo 'Petugas : '.$gc14;
													?>

												</td>
											</tr>
										</table>
										<br><br><br>
									</td>
								</tr>
							</table>
							<br>
							*) Coret yang tidak perlu
							<br>
							<br>
						</td>
					</tr>
				</table>
			</div>
		</div>


	</form>
</font>
</body>
</div>
</div>

<?php 


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>