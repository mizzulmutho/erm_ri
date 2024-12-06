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


$qi="SELECT noreg FROM ERM_RI_INFORMCONSENT where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_INFORMCONSENT(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_INFORMCONSENT
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$ic1 = $de['ic1'];
	$ic2= $de['ic2'];
	$ic3= $de['ic3'];
	$ic4= $de['ic4'];
	$ic5= $de['ic5'];
	$ic6= $de['ic6'];
	$ic7= $de['ic7'];
	$ic8= $de['ic8'];
	$ic9= $de['ic9'];
	$ic10= $de['ic10'];
	$ic11= $de['ic11'];
	$ic12= $de['ic12'];
	$ic13= $de['ic13'];
	$ic14= $de['ic14'];
	$ic15= $de['ic15'];
	$ic15a= $ic15;
	$ic16= $de['ic16'];
	$ic17= $de['ic17'];
	$ic18= $de['ic18'];
	$ic19= $de['ic19'];
	$ic20= $de['ic20'];
	$ic21= $de['ic21'];
	$ic22= $de['ic22'];
	$ic23= $de['ic23'];
	$ic24= $de['ic24'];
	$ic25= $de['ic25'];
	$ic26= $de['ic26'];
	$ic27= $de['ic27'];
	$ic28= $de['ic28'];
	$ic29= $de['ic29'];
	$ic30= $de['ic30'];
	$ic31= $de['ic31'];
	$ic32= $de['ic32'];
	$ic33= $de['ic33'];
	$ic34= $de['ic34'];
	$ic35= $de['ic35'];
	$ic36= $de['ic36'];
	$ic37= $de['ic37'];
	$ic38= $de['ic38'];
	$ic39= $de['ic39'];
	$ic40= $de['ic40'];
	$ic41= $de['ic41'];
	$ic42= $de['ic42'];
	$ic43= $de['ic43'];
	$ic44= $de['ic44'];
	$ic45= $de['ic45'];
	$ic46= $de['ic46'];
	$ic47= $de['ic47'];
	$ic48= $de['ic48'];
	$ic49= $de['ic49'];
	$ic50= $de['ic50'];
	$ic51= $de['ic51'];
	$ic52= $de['ic52'];
	$ic53= $de['ic53'];
	$ic54= $de['ic54'];
	$ic55= $de['ic55'];
	$ic56= $de['ic56'];
	$ic57= $de['ic57'];
	$ic58= $de['ic58'];
	$ic59= $de['ic59'];
	$ic60= $de['ic60'];
}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Informed Consent</title>  
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

	<script type="text/javascript" src="js/jquery.signature.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.signature.css">

	<style>
		.kbw-signature {
			width: 300px;
			height: 300px;
		}

		#sig canvas {
			width: 100% !important;
			height: auto;
		}

	</style>

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
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
					<a href='r_informconsent.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a>
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
					<b>
						INFORMED CONSENT<br>
						(PERSETUJUAN TINDAKAN KEDOKTERAN)
					</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>

				<tr>
					<td>
						<div class="row">
							<div class="col-12 text-center">
								PEMBERIAN INFORMASI
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Dokter pelaksana tindakan
							</div>
							<div class="col-8">							
								<input class="form-control form-control-sm" name="ic1" value="<?php echo $ic1;?>" id="dokter" type="text" placeholder="Isikan Nama lengkap sesuai dengan KTP">
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Pemberi Informasi 
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic2" value="<?php echo $ic2;?>" id="karyawan1" type="text" placeholder="Isikan Nama lengkap sesuai dengan KTP">
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								penerimaan informasi/pemberi persetujuan *)
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic3" value="<?php echo $ic3;?>" id="" type="text" placeholder="Isikan Nama lengkap sesuai dengan KTP"> 
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								nama saksi (jika ada)
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic27" value="<?php echo $ic27;?>" id="" type="text" placeholder="Isikan Nama Saksi"> 
							</div>
						</div>
					</td>
				</tr>


				<tr>
					<td>
						<div class="row">
							<div class="col-12">
								<table class="table">
									<tr>
										<td>No</td>
										<td>Jenis Informasi</td>
										<td>Isi Informasi</td>
									</tr>
									<tr>
										<td>1</td>
										<td>Diagnosis</td>
										<td><textarea name= "ic4" id="" style="min-width:650px; min-height:40px;"><?php echo $ic4;?></textarea></td>
									</tr>
									<tr>
										<td>2</td>
										<td>Dasar Diagnosis</td>
										<td><textarea name= "ic5" id="" style="min-width:650px; min-height:40px;"><?php echo $ic5;?></textarea></td>
									</tr>
									<tr>
										<td>3</td>
										<td>Tindakan Kedokteran</td>
										<td><textarea name= "ic6" id="" style="min-width:650px; min-height:40px;"><?php echo $ic6;?></textarea></td>
									</tr>
									<tr>
										<td>4</td>
										<td>Indikasi Tindakan</td>
										<td><textarea name= "ic7" id="" style="min-width:650px; min-height:40px;"><?php echo $ic7;?></textarea></td>
									</tr>
									<tr>
										<td>5</td>
										<td>Tata Cara</td>
										<td><textarea name= "ic8" id="" style="min-width:650px; min-height:40px;"><?php echo $ic8;?></textarea></td>
									</tr>
									<tr>
										<td>6</td>
										<td>Tujuan</td>
										<td><textarea name= "ic9" id="" style="min-width:650px; min-height:40px;"><?php echo $ic9;?></textarea></td>
									</tr>
									<tr>
										<td>7</td>
										<td>Risiko</td>
										<td><textarea name= "ic10" id="" style="min-width:650px; min-height:40px;"><?php echo $ic10;?></textarea></td>
									</tr>
									<tr>
										<td>8</td>
										<td>Komplikasi</td>
										<td><textarea name= "ic11" id="" style="min-width:650px; min-height:40px;"><?php echo $ic11;?></textarea></td>
									</tr>
									<tr>
										<td>9</td>
										<td>Prognosis</td>
										<td><textarea name= "ic12" id="" style="min-width:650px; min-height:40px;"><?php echo $ic12;?></textarea></td>
									</tr>
									<tr>
										<td>10</td>
										<td>Alternatif lain dan Risiko
										</td>
										<td><textarea name= "ic13" id="" style="min-width:650px; min-height:40px;"><?php echo $ic13;?></textarea></td>
									</tr>
									<tr>
										<td>11</td>
										<td>Pembiayaan
										</td>
										<td><textarea name= "ic14" id="" style="min-width:650px; min-height:40px;"><?php echo $ic14;?></textarea></td>
									</tr>
									<tr>
										<td>12</td>
										<td>Lain-lain
										</td>
										<td><textarea name= "ic15" id="" style="min-width:650px; min-height:40px;"><?php echo $ic15;?></textarea></td>
									</tr>
								</table>
							</div>
						</div>
					</td>
				</tr>


				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								penerimaan informasi/pemberi persetujuan *)
							</div>
							<div class="col-8">
								: 
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-6 text-center">
								<br>
								Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/atau berdiskusi.
								<br>
								Tanda tangan,
								dokter
								<br>
								<?php 
								if ($ic1){
									$penanggungjawab='Lembar Inform Consent ini telah ditandatangani oleh dokter :'.$ic1.'pada tanggal:'.$tgl;
												// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

									QRcode::png($penanggungjawab, "image.png", "L", 2, 2);   
									echo "<center><img src='image.png'></center>";

									echo $ic1;
								}

								?>
							</div>

							<div class="col-6 text-center">
								<br>
								Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri tanda/paraf di kolom kanannya, dan telah memahaminya.
								<br>
								Tanda tangan,
								pasien/wali
								<br>
								<br />
								<?php  
								if($ic24){
									echo " <img src='$ic24' height='100' width='100'>";
									echo "<br><br>";
									echo $ic3;

								}
								?>
							</div>

						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-12">
								*) Bila pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah wali atau keluarga terdekat
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Jenis Persetujuan 
							</div>
							<div class="col-8">
								<input type='radio' name='ic26' value='Setuju' <?php if ($ic26=="Setuju"){echo "checked";}?> >PERSETUJUAN TINDAKAN KEDOKTERAN
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type='radio' name='ic26' value='Tidak Setuju' <?php if ($ic26=="Tidak Setuju"){echo "checked";}?> >PENOLAKAN TINDAKAN KEDOKTERAN
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-8">
								Yang bertanda tangan dibawah ini saya,
							</div>
							<div class="col-4">
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Nama
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic16" value="<?php echo $nama;?>" id="" type="text" placeholder=""> 
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Tanggal lahir
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic17" value="<?php echo $tgllahir;?>" id="" type="text" placeholder=""> 
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Alamat
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic18" value="<?php echo $alamat;?>" id="" type="text" placeholder=""> 
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-12">
								Dengan ini menyatakan PERSETUJUAN untuk dilakukan tindakan  
							</div>
							<div class="col-8">
								<textarea name= "ic19" id="" style="min-width:650px; min-height:40px;"><?php echo $ic19;?></textarea>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Terhadap saya
							</div>
							<div class="col-8">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Nama
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic20" value="<?php echo $nama;?>" id="" type="text" placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Tanggal lahir
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic21" value="<?php echo $tgllahir;?>" id="" type="text" placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Alamat
							</div>
							<div class="col-8">
								<input class="form-control form-control-sm" name="ic22" value="<?php echo $alamat;?>" id="" type="text" placeholder="">
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-12">
								Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul apabila tindakan tersebut tidak dilakukannya. 
								Saya bertanggungjawab secara penuh atas segala akibat yang mungkin timbul sebagai akibat tidak dilakukannya tindakan kedokteran tersebut.
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-6">
								Pasien/wali Yang menyatakan<br>
								<input class="form-control form-control-sm" name="ic24" value="<?php echo $ic24;?>" id="" type="text" placeholder="" hidden> 
								<table class="table">
									<tr>
										<td>

											<?php 
											if($ic24){
												echo " <img src='$ic24' height='200' width='200'>";
												echo "<br><br>";
												echo $ic3;
											}
											?>
										</td>
									</tr>
								</table>
							</div>
							<div class="col-6">
								Saksi<br>
								<input class="form-control form-control-sm" name="ic25" value="<?php echo $ic25;?>" id="" type="text" placeholder="" hidden> 
								<table class="table">
									<tr>
										<td>

											<?php 
											if($ic25){
												echo " <img src='$ic25' height='200' width='200'>";
												echo "<br><br>";
												echo $ic27;
											}
											?>


										</td>
									</tr>
								</table>

							</div>
						</div>
					</td>
				</tr>



				<tr>
					<td>
						<div class="row">
							<div class="col-12">
								Tgl Entry : <?php echo $tgl;?>
							</div>
						</div>
					</td>
				</tr>


				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Tanda Tangan : 
								<select name='j_ttd'>
									<option value=''>-- Pilih Jenis TTD --</option>
									<option value='Pasien'>Pasien/Wali</option>
									<option value='Saksi'>Saksi</option>
								</select>
							</div>
							<div class="col-8">
								<div id="sig"></div>
								<br />
								<button id="clear" class="btn btn-warning mt-1">Hapus Tanda Tangan</button>
								<textarea id="signature64" name="signed" style="display: none" class="input-group mb-3"></textarea>
								<br />
								<button class="btn btn-success mt-1" id="" value="ttd" name="ttd">Simpan TTD</button>
								<br>
							</div>
						</div>
					</td>
				</tr>


				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Upload Gambar (Jika ada)
							</div>
							<div class="col-8">
								<input type="file" name="ic23" class="" >Ekstensi .jpeg | .jpg
								<a href='<?php echo $ic15; ?>' target='_blank'><?php echo $ic23; ?></a>
								<input type='submit' name='upload' value='upload' style="color: white;background: #66CDAA;border-color: #66CDAA;">
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-6">
							</div>
							<div class="col-6">
								<br>
								&nbsp;&nbsp;
								<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>

								<br><br><br>
							</div>
						</div>
					</td>
				</tr>	

			</table>

			<br>
		</form>
	</font>

	<script type="text/javascript">
		var sig = $('#sig').signature({
			syncField: '#signature64',
			syncFormat: 'PNG'
		});
		$('#clear').click(function(e) {
			e.preventDefault();
			sig.signature('clear');
			$("#signature64").val('');
		});
	</script>



</body>
</div>
</div>

<?php 


if (isset($_POST["ttd"])) {
	$folderPath = "upload/";
	$j_ttd = $_POST['j_ttd'];


	if(empty($_POST['signed']) and empty($j_ttd)){
		echo "Kosong";
		$eror = "Tanda Tangan Kosong";
	}else{
		$image_parts = explode(";base64,", $_POST['signed']); 
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];
		$image_base64 = base64_decode($image_parts[1]);
		$file = $folderPath . uniqid() . '.'.$image_type;
		file_put_contents($file, $image_base64);
		echo "Tanda Tangan Sukses Diupload ";

		if($j_ttd=="Pasien"){
			$qInsert = "UPDATE ERM_RI_INFORMCONSENT SET ic24 = '$file' WHERE NOREG='$noreg'";
		}
		if($j_ttd=="Saksi"){
			$qInsert = "UPDATE ERM_RI_INFORMCONSENT SET ic25 = '$file' WHERE NOREG='$noreg'";
		}

		$qInsert;
		$result = sqlsrv_query($conn, $qInsert);

		$eror = 'Berhasil';
	}

	echo "
	<script>
	alert('".$eror."');
	window.location.replace('informconsent.php?id=$id|$user');
	</script>
	";

}

if (isset($_POST["simpan"])) {

	$ic1	= $_POST["ic1"];
	$ic2	= $_POST["ic2"];
	$ic3	= $_POST["ic3"];
	$ic4	= $_POST["ic4"];
	$ic5	= $_POST["ic5"];
	$ic6	= $_POST["ic6"];
	$ic7	= $_POST["ic7"];
	$ic8	= $_POST["ic8"];
	$ic9	= $_POST["ic9"];
	$ic10	= $_POST["ic10"];
	$ic11	= $_POST["ic11"];
	$ic12	= $_POST["ic12"];
	$ic13	= $_POST["ic13"];
	$ic14	= $_POST["ic14"];
	$ic15	= $_POST["ic15"];
	$ic16	= $_POST["ic16"];
	$ic17	= $_POST["ic17"];
	$ic18	= $_POST["ic18"];
	$ic19	= $_POST["ic19"];
	$ic20	= $_POST["ic20"];
	$ic21	= $_POST["ic21"];
	$ic22	= $_POST["ic22"];
	$ic23	= $_POST["ic23"];
	$ic24	= $_POST["ic24"];
	$ic25	= $_POST["ic25"];
	$ic26	= $_POST["ic26"];
	$ic27	= $_POST["ic27"];
	$ic28	= $_POST["ic28"];
	$ic29	= $_POST["ic29"];
	$ic30	= $_POST["ic30"];
	$ic31	= $_POST["ic31"];
	$ic32	= $_POST["ic32"];
	$ic33	= $_POST["ic33"];
	$ic34	= $_POST["ic34"];
	$ic35	= $_POST["ic35"];
	$ic36	= $_POST["ic36"];
	$ic37	= $_POST["ic37"];
	$ic38	= $_POST["ic38"];
	$ic39	= $_POST["ic39"];
	$ic40	= $_POST["ic40"];
	$ic41	= $_POST["ic41"];
	$ic42	= $_POST["ic42"];
	$ic43	= $_POST["ic43"];
	$ic44	= $_POST["ic44"];
	$ic45	= $_POST["ic45"];
	$ic46	= $_POST["ic46"];
	$ic47	= $_POST["ic47"];
	$ic48	= $_POST["ic48"];
	$ic49	= $_POST["ic49"];
	$ic50	= $_POST["ic50"];
	$ic51	= $_POST["ic51"];
	$ic52	= $_POST["ic52"];
	$ic53	= $_POST["ic53"];
	$ic54	= $_POST["ic54"];
	$ic55	= $_POST["ic55"];
	$ic56	= $_POST["ic56"];
	$ic57	= $_POST["ic57"];
	$ic58	= $_POST["ic58"];
	$ic59	= $_POST["ic59"];
	$ic60	= $_POST["ok60"];

	$q  = "update ERM_RI_INFORMCONSENT set
	ic1	='$ic1',
	ic2	='$ic2',
	ic3	='$ic3',
	ic4	='$ic4',
	ic5	='$ic5',
	ic6	='$ic6',
	ic7	='$ic7',
	ic8	='$ic8',
	ic9	='$ic9',
	ic10	='$ic10',
	ic11	='$ic11',
	ic12	='$ic12',
	ic13	='$ic13',
	ic14	='$ic14',
	ic15	='$ic15',
	ic16	='$ic16',
	ic17	='$ic17',
	ic18	='$ic18',
	ic19	='$ic19',
	ic20	='$ic20',
	ic21	='$ic21',
	ic22	='$ic22',
	ic23	='$ic23',
	ic24	='$ic24',
	ic25	='$ic25',
	ic26	='$ic26',
	ic27	='$ic27',
	ic28	='$ic28',
	ic29	='$ic29',
	ic30	='$ic30',
	ic31	='$ic31',
	ic32	='$ic32',
	ic33	='$ic33',
	ic34	='$ic34',
	ic35	='$ic35',
	ic36	='$ic36',
	ic37	='$ic37',
	ic38	='$ic38',
	ic39	='$ic39',
	ic40	='$ic40',
	ic41	='$ic41',
	ic42	='$ic42',
	ic43	='$ic43',
	ic44	='$ic44',
	ic45	='$ic45',
	ic46	='$ic46',
	ic47	='$ic47',
	ic48	='$ic48',
	ic49	='$ic49',
	ic50	='$ic50',
	ic51	='$ic51',
	ic52	='$ic52',
	ic53	='$ic53',
	ic54	='$ic54',
	ic55	='$ic55',
	ic56	='$ic56',
	ic57	='$ic57',
	ic58	='$ic58',
	ic59	='$ic59',
	ic60	='$ic60'
	where noreg='$noreg'
	";

	$hs = sqlsrv_query($conn,$q);

	$ic15 = $_FILES['ic15']['name'];

	if ($ic15) {
		$path = "upload/".$ic15;

		$q  = "update ERM_RI_INFORMCONSENT set ic15='$path' where noreg='$noreg'";         
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			if($ic15){
				unlink($ic15a);
			// move_uploaded_file($nama_file, $path);
				move_uploaded_file($_FILES['ic15']['tmp_name'],$path);
			}

		}

	}


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

if (isset($_POST["upload"])) {
	// Ambil Gambar yang Dikirim dari Form
	$doc_file = $_FILES['ic15']['name'];
  	// Set path folder tempat menyimpan gambarnya
	$path = "upload/".$doc_file;

	$q  = "update ERM_RI_INFORMCONSENT set ic15='$path' where noreg='$noreg'";         
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		if($doc_file){
			unlink($foto_image);
			// move_uploaded_file($nama_file, $path);
			move_uploaded_file($_FILES['ic15']['tmp_name'],$path);
		}
		$eror = "Success";
		echo "
		<div class='alert alert-success' role='alert'>
		".$eror."
		</div>
		";

	}else{
		$eror = "Gagal Update";
		echo "
		<div class='alert alert-danger' role='alert'>
		".$eror."
		</div>
		";

	}

	echo "
	<script>
	history.go(-1);
	</script>
	";

}

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>