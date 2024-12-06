<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

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
								: 
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
								: 
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
										<td></td>
									</tr>
									<tr>
										<td>2</td>
										<td>Dasar Diagnosis</td>
										<td></td>
									</tr>
									<tr>
										<td>3</td>
										<td>Tindakan Kedokteran</td>
										<td></td>
									</tr>
									<tr>
										<td>4</td>
										<td>Indikasi Tindakan</td>
										<td></td>
									</tr>
									<tr>
										<td>5</td>
										<td>Tata Cara</td>
										<td></td>
									</tr>
									<tr>
										<td>6</td>
										<td>Tujuan</td>
										<td></td>
									</tr>
									<tr>
										<td>7</td>
										<td>Risiko</td>
										<td></td>
									</tr>
									<tr>
										<td>8</td>
										<td>Komplikasi</td>
										<td></td>
									</tr>
									<tr>
										<td>9</td>
										<td>Prognosis</td>
										<td></td>
									</tr>
									<tr>
										<td>10</td>
										<td>Alternatif lain dan Risiko
										</td>
										<td></td>
									</tr>
									<tr>
										<td>11</td>
										<td>Pembiayaan
										</td>
										<td></td>
									</tr>
									<tr>
										<td>12</td>
										<td>Lain-lain
										</td>
										<td></td>
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
							<div class="col-8">
								Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/atau berdiskusi.
							</div>
							<div class="col-4">
								Tanda tangan,
								dokter

								( ............................. )
							</div>
						</div>
					</td>
				</tr>
				
				<tr>
					<td>
						<div class="row">
							<div class="col-8">
								Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri tanda/paraf di kolom kanannya, dan telah memahaminya.
							</div>
							<div class="col-4">
								Tanda tangan,
								pasien/wali

								( ............................. )
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
								&nbsp;&bull; Tindakan yang Dilakukan
							</div>
							<div class="col-8">
								:
								<?php 
								if(empty($ic10)){
									$ic10="1... \n2... \n3...";
								}else{
									$ic10=$ic10;
								}
								?> 
								<textarea name= "ic10" id="" style="min-width:650px; min-height:60px;"><?php echo $ic10;?></textarea>
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Konsekuensi dari Tindakan
							</div>
							<div class="col-8">
								:
								<?php 
								if(empty($ic10)){
									$ic10="1... \n2... \n3...";
								}else{
									$ic10=$ic10;
								}
								?> 
								<textarea name= "ic10" id="" style="min-width:650px; min-height:60px;"><?php echo $ic10;?></textarea>
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-12">
								<table width="100%" border='0'>
									<tr>
										<td align="center">
											<?php 
											if ($ic12){
												$pernyataanpasien='Lembar General Consent ini telah disetujui oleh Pasien atas nama:'.$nama.'pada tanggal:'.$tglinput;
												echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";
												echo $nama;
											}
											?>
										</td>
										<td align="center">
											<?php 
											if ($ic13){
												$penanggungjawab='Lembar General Consent ini telah ditandatangani oleh Penanggung Jawab :'.$ic13.'pada tanggal:'.$tglinput;
												echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";
												echo $ic13;
											}
											?>									
										</td>
										<td align="center">
											<?php 
											if ($ic14){
												$penanggungjawab='Lembar General Consent ini telah ditandatangani oleh Petugas :'.$ic14.'pada tanggal:'.$tglinput;
												echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";
												echo $ic14;
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
							<div class="col-4">
								Yang Membuat Pernyataan (Tanda Tangan)<br>
								<input type='checkbox' name='ic12' value='YA' <?php if ($ic12=="YA"){echo "checked";}?> > Pasien Menyetujui Lembar General Consent
							</div>
							<div class="col-8">
								<table width="100%" border='1'>
									<tr>
										<td>Dokter yang Memberi Penjelasan
											<br>	
											<input class="" name="ic13" value="<?php echo $ic13;?>" id="karyawan1" type="text" size='50' placeholder="Isikan Nama lengkap sesuai dengan KTP">										
										</td>
										<td>
											Petugas yang Mendampingi
											<br>	
											<input class="" name="ic14" value="<?php echo $ic14;?>" id="karyawan2" type="text" size='50' placeholder="Isikan Nama Petugas yang Memberi Penjelasan">
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
								<table width="100%" border='1'>
									<tr>
										<td>Saksi 1
											<br>	
											<input class="" name="ic13" value="<?php echo $ic13;?>" id="karyawan1" type="text" size='50' placeholder="Isikan Nama lengkap sesuai dengan KTP">										
										</td>
										<td>
											Saksi 2
											<br>	
											<input class="" name="ic14" value="<?php echo $ic14;?>" id="karyawan2" type="text" size='50' placeholder="Isikan Nama Petugas yang Memberi Penjelasan">
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<input type="file" name="ic15" class="" >Ekstensi .jpeg | .jpg
								<a href='<?php echo $ic15; ?>' target='_blank'><?php echo $ic15; ?></a>
								<input type='submit' name='upload' value='upload' style="color: white;background: #66CDAA;border-color: #66CDAA;">
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
								<br><br><br>
								&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
							</div>
						</div>
					</td>
				</tr>	

			</table>


			<br>
			<br>
			<br>
			<br>
			<br>
		</form>
	</font>
</body>
</div>
</div>

<?php 


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