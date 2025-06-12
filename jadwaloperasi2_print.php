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


$qi="SELECT noreg FROM ERM_RI_LAPORAN_OK where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_LAPORAN_OK(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_LAPORAN_OK2
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$ok1 = $de['ok1'];
	$ok2= $de['ok2'];
	$ok3= $de['ok3'];
	$ok4= $de['ok4'];
	$ok5= $de['ok5'];
	$ok6= $de['ok6'];
	$ok7= $de['ok7'];
	$ok8= $de['ok8'];
	$ok9= $de['ok9'];
	$ok10= $de['ok10'];
	$ok11= $de['ok11'];
	$ok12= $de['ok12'];
	$ok13= $de['ok13'];
	$ok14= $de['ok14'];
	$ok15= $de['ok15'];
	$ok16= $de['ok16'];
	$ok17= $de['ok17'];
	$ok18= $de['ok18'];
	$ok19= $de['ok19'];
	$ok20= $de['ok20'];
	$ok21= $de['ok21'];
	$ok22= $de['ok22'];
	$ok23= $de['ok23'];
	$ok24= $de['ok24'];
	$ok25= $de['ok25'];
	$ok26= $de['ok26'];
	$ok27= $de['ok27'];
	$ok28= $de['ok28'];
	$ok29= $de['ok29'];
	$ok30= $de['ok30'];
	$ok31= $de['ok31'];
	$ok32= $de['ok32'];
	$ok33= $de['ok33'];
	$ok34= $de['ok34'];
	$ok35= $de['ok35'];
	$ok36= $de['ok36'];
	$ok37= $de['ok37'];
	$ok38= $de['ok38'];
	$ok39= $de['ok39'];
	$ok40= $de['ok40'];
	$ok41= $de['ok41'];
	$ok42= $de['ok42'];
	$ok43= $de['ok43'];
	$ok44= $de['ok44'];
	$ok45= $de['ok45'];
	$ok46= $de['ok46'];
	$ok47= $de['ok47'];
	$ok48= $de['ok48'];
	$ok49= $de['ok49'];
	$ok50= $de['ok50'];
	$ok51= $de['ok51'];
	$ok52= $de['ok52'];
	$ok53= $de['ok53'];
	$ok54= $de['ok54'];
	$ok55= $de['ok55'];
	$ok56= $de['ok56'];
	$ok57= $de['ok57'];
	$ok58= $de['ok58'];
	$ok59= $de['ok59'];
	$ok60= $de['ok60'];
}

if (isset($_POST["Print"])) {

	echo "
	<script>
	window.print();
	</script>
	";



}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Resume Medis</title>  
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

	<!-- Link ke Bootstrap 5 CSS dan Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='4px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<input type='submit' name='Print' value='Print' class='btn btn-info'>
					<div class="row">
					</div>

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
							<b>LAPORAN OPERASI  </b><br>
						</div>
					</div>

					<div class="row">
						<div class="col-12 text-left">
							<b>Diisi Oleh Petugas Kamar Operasi</b><br>
						</div>
					</div>

					<table width='100%' border='1'>
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-4">
										Diagnosa pra pembedahan  <i class="fas fa-notes-medical"></i>
									</div>
									<div class="col-6">
										: <?php 
										// $row = explode('-',$ok1);$ok1  = $row[1];
										echo $ok1;
										?>
										
									</div>
									<div class="col-2">
										<table border='1'>
											<tr><td>
												&nbsp;BB : <?php echo $ok2;?> kg&nbsp;											
											</td></tr>
										</table>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-4">
										Tindakan yang dilakukan <i class="fas fa-procedures"></i>
									</div>
									<div class="col-8">
										: 
										<!-- <input type='text' name='' value='' size='50'> -->
										<?php //$row = explode('-',$ok3);$ok3  = $row[1]; echo $ok3;?>
										<?php //$row = explode('-',$ok4);$ok4  = $row[1]; echo $ok4;?>
										<?php //$row = explode('-',$ok5);$ok5  = $row[1]; echo $ok5;?>
										<?php //$row = explode('-',$ok6);$ok6  = $row[1]; echo $ok6;?>
										<?php //$row = explode('-',$ok7);$ok7  = $row[1]; echo $ok7;?>
										<?php 
										echo $ok3.'<br>'.$ok4.'<br>'.$ok5.'<br>'.$ok6.'<br>'.$ok7;
										?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-4">
										Diagnosa pasca pembedahan <i class="fas fa-stethoscope"></i>
									</div>
									<div class="col-8">
										:<?php //$row = explode('-',$ok8);$ok8  = $row[1]; echo $ok8;?>
										<?php echo $ok8;?>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-3">
										Tgl Operasi <i class="fas fa-calendar-alt"></i>
									</div>
									<div class="col-3">
										<?php 
										if (empty($ok9)){
											$ok9 = $tgl2;
											$ok10 = '1 jam';
											$ok11 = '07:00';
											$ok12 = '08:00';
											$ok38 = '07:00';
											$ok39 = '08:00';
										}
										?>
										: <?php echo $ok9;?>
									</div>
									<div class="col-3">
										Lama Operasi <i class="fas fa-clock"></i>
									</div>
									<div class="col-3">
										: <?php echo $ok10;?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-3">
										Mulai Jam <i class="fas fa-clock"></i>
									</div>
									<div class="col-3">
										: <?php echo $ok11;?>
									</div>
									<div class="col-3">
										Selesai jam <i class="fas fa-clock"></i>
									</div>
									<div class="col-3">
										: <?php echo $ok12;?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-6">
										Dokter Operator <i class="fas fa-user-md"></i> : <?php $row = explode('-',$ok42);$ok42  = $row[1]; echo $ok42;?>
									</div>
									<div class="col-6">
										Asisten <i class="fas fa-user-nurse"></i> : <?php $row = explode('-',$ok43);$ok43  = $row[1]; echo $ok43;?>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										Instrumen <i class="fas fa-tools"></i> : <?php $row = explode('-',$ok44);$ok44  = $row[1]; echo $ok44;?>
									</div>
									<div class="col-6">
										Petugas on Steril <i class="fas fa-user-shield"></i> : <?php $row = explode('-',$ok45);$ok45  = $row[1]; echo $ok45;?>
									</div>
								</div>

							</td>
						</tr>		
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-12">
										Jumlah Perdarahan <i class="fas fa-tint"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $ok13;?><br>
										Tranfusi durante operasi : 
										<?php if ($ok46=="TIDAK"){echo "TIDAK";}?>
										<?php if ($ok46=="YA"){echo "YA : ";}?>
										<?php echo $ok48;?> CC
									</div>

									<div class="col-12">
										Pemeriksaan jaringan <i class="fas fa-cogs"></i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 
										<!-- <input type='text' name='ok47' value='<?php echo $ok47;?>' size='30'><br> -->
										<?php if ($ok47=="TIDAK"){echo "TIDAK";}?>
										<?php if ($ok47=="YA"){echo "YA : ";}?>
										<br>
										Macam jaringan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $ok14;?><br>
										Jenis pemeriksaan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $ok15;?> 								
									</div>
								</div>
							</td>
						</tr>						

						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-3">
										Jenis Anestesi <i class="fas fa-bed"></i> : <?php echo $ok37;?>
									</div>
									<div class="col-3">
										Mulai Jam : <?php echo $ok38;?>
									</div>
									<div class="col-3">
										Selesai Jam : <?php echo $ok39;?>
									</div>
									<div class="col-3">
										Dokter Anastesi : <?php echo $ok50;?>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-12">
										<div class="col-12">
											Obat Anestesi : <?php echo $ok40;?>
											<br><br>
										</div>
									</div>
									<br>
								</div>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-4">
										Golongan Operasi
									</div>
									<div class="col-4">
										Macam Operasi
									</div>
									<div class="col-4">
										Urgensi Operasi
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-4">
										<?php if ($ok16=="YA"){echo "[&radic;]";}else{echo "[]";}?> Poliklinik<br>
										<?php if ($ok17=="YA"){echo "[&radic;]";}else{echo "[]";}?> Kecil<br>
										<?php if ($ok18=="YA"){echo "[&radic;]";}else{echo "[]";}?> Sedang<br>
										<?php if ($ok19=="YA"){echo "[&radic;]";}else{echo "[]";}?> Besar<br>
										<?php if ($ok20=="YA"){echo "[&radic;]";}else{echo "[]";}?> Besar Khusus<br>
										<?php if ($ok49=="YA"){echo "[&radic;]";}else{echo "[]";}?> Khusus<br>
									</div>
									<div class="col-4">
										<?php if ($ok21=="YA"){echo "[&radic;]";}else{echo "[]";}?>Bersih<br>
										<?php if ($ok22=="YA"){echo "[&radic;]";}else{echo "[]";}?>Bersih Terkontaminasi<br>
										<?php if ($ok23=="YA"){echo "[&radic;]";}else{echo "[]";}?>Kontaminasi<br>
										<?php if ($ok24=="YA"){echo "[&radic;]";}else{echo "[]";}?>kotor<br>
									</div>
									<div class="col-4">
										<?php if ($ok25=="YA"){echo "[&radic;]";}else{echo "[]";}?>Darurat<br>
										<?php if ($ok26=="YA"){echo "[&radic;]";}else{echo "[]";}?>Urgen<br>
										<?php if ($ok27=="YA"){echo "[&radic;]";}else{echo "[]";}?>Elektif<br>
									</div>
								</div>
							</td>
						</tr>	

						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-4">
										Kamar Operasi No <i class="fas fa-hospital"></i> : <?php echo $ok28;?>
									</div>
									<div class="col-4">
										Ronde Ke <i class="fas fa-sync-alt"></i> : <?php echo $ok29;?>
									</div>
									<div class="col-4">
										Hasil Perhitungan Kasa <i class="fas fa-calculator"></i> : <?php echo $ok30;?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td colspan="2" align="left">
								<div class="row">
									<div class="col-12">
										Penjelasan Teknik Operasi dan Rincian Temuan <i class="fas fa-file-alt"></i>
										<br>
										<?php echo nl2br($ok31);?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-12">
										Komplikasi <i class="fas fa-exclamation-triangle"></i> : <?php echo $ok32;?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td colspan="2">
								<div class="row">
									<div class="col-12">
										Implan <i class="fas fa-implant"></i> : 
										<?php if ($ok33=="YA"){echo "[&radic;]";}else{echo "[]";}?> Tidak 
										<?php if ($ok34=="YA"){echo "[&radic;]";}else{echo "[]";}?> Ya, 
										sebutkan: <?php echo nl2br($ok35);?>
									</div>
								</div>
							</td>
						</tr>	

						<tr>
							<td colspan="2" align='center'>
								<div class="row">
									<div class="col-12">
										<?php 
										$q		= "select doc as doc from ERM_RI_LAPORAN_OK where noreg = '$noreg'";
										$h  = sqlsrv_query($conn, $q);			  
										$d  = sqlsrv_fetch_array($h, SQLSRV_FETCH_ASSOC); 
										$doc = trim($d['doc']);

										// if(!empty($doc)){
										// 	echo "
										// 	Gambar Upload<br>
										// 	<img src=$doc width='300px' height='200px'>
										// 	";
										// }

										?>						

										<br>			
									</div>
								</div>

							</td>
						</tr>

						<tr>
							<td colspan="2">

								<div class="row">
									<div class="col-4">
										Verifikasi Petugas Kamar Operasi
									</div>
									<div class="col-8">
										<?php echo $ok36." Tanggal : ".$ok9; ?>

									</div>
								</div>

								<div class="row">
									<div class="col-12">
										<?php 
										// if($ok36){
										// 	$verif_dokter="Laporan Operasi ini telah diVerifikasi Oleh : ".$ok36."Pada Tanggal : ".$ok9; 
										// 	echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_dokter&choe=UTF-8'/></center>";
										// }

										if($ok36){
											$verif_dokter="Laporan Operasi ini telah diVerifikasi Oleh : ".$ok36."Pada Tanggal : ".$ok9; 

											QRcode::png($verif_dokter, "image.png", "L", 2, 2);   
											echo "<center><img src='image.png'></center>";


										}

										?>
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

if (isset($_POST["upload"])) {
	// Ambil Gambar yang Dikirim dari Form
	$doc_file = $_FILES['doc']['name'];
  	// Set path folder tempat menyimpan gambarnya
	$path = "upload/".$doc_file;

	$q  = "update ERM_RI_LAPORAN_OK set doc='$path' where noreg='$noreg'";         
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		if($doc_file){
			unlink($foto_image);
			// move_uploaded_file($nama_file, $path);
			move_uploaded_file($_FILES['doc']['tmp_name'],$path);
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
}


if (isset($_POST["simpan"])) {

	$ok1	= $_POST["ok1"];
	$ok2	= $_POST["ok2"];
	$ok3	= $_POST["ok3"];
	$ok4	= $_POST["ok4"];
	$ok5	= $_POST["ok5"];
	$ok6	= $_POST["ok6"];
	$ok7	= $_POST["ok7"];
	$ok8	= $_POST["ok8"];
	$ok9	= $_POST["ok9"];
	$ok10	= $_POST["ok10"];
	$ok11	= $_POST["ok11"];
	$ok12	= $_POST["ok12"];
	$ok13	= $_POST["ok13"];
	$ok14	= $_POST["ok14"];
	$ok15	= $_POST["ok15"];
	$ok16	= $_POST["ok16"];
	$ok17	= $_POST["ok17"];
	$ok18	= $_POST["ok18"];
	$ok19	= $_POST["ok19"];
	$ok20	= $_POST["ok20"];
	$ok21	= $_POST["ok21"];
	$ok22	= $_POST["ok22"];
	$ok23	= $_POST["ok23"];
	$ok24	= $_POST["ok24"];
	$ok25	= $_POST["ok25"];
	$ok26	= $_POST["ok26"];
	$ok27	= $_POST["ok27"];
	$ok28	= $_POST["ok28"];
	$ok29	= $_POST["ok29"];
	$ok30	= $_POST["ok30"];
	$ok31	= $_POST["ok31"];
	$ok32	= $_POST["ok32"];
	$ok33	= $_POST["ok33"];
	$ok34	= $_POST["ok34"];
	$ok35	= $_POST["ok35"];
	$ok36	= $_POST["ok36"];
	$ok37	= $_POST["ok37"];
	$ok38	= $_POST["ok38"];
	$ok39	= $_POST["ok39"];
	$ok40	= $_POST["ok40"];
	$ok41	= $_POST["ok41"];
	$ok42	= $_POST["ok42"];
	$ok43	= $_POST["ok43"];
	$ok44	= $_POST["ok44"];
	$ok45	= $_POST["ok45"];
	$ok46	= $_POST["ok46"];
	$ok47	= $_POST["ok47"];
	$ok48	= $_POST["ok48"];
	$ok49	= $_POST["ok49"];
	$ok50	= $_POST["ok50"];
	$ok51	= $_POST["ok51"];
	$ok52	= $_POST["ok52"];
	$ok53	= $_POST["ok53"];
	$ok54	= $_POST["ok54"];
	$ok55	= $_POST["ok55"];
	$ok56	= $_POST["ok56"];
	$ok57	= $_POST["ok57"];
	$ok58	= $_POST["ok58"];
	$ok59	= $_POST["ok59"];
	$ok60	= $_POST["ok60"];

	$q  = "update ERM_RI_LAPORAN_OK set
	ok1	='$ok1',
	ok2	='$ok2',
	ok3	='$ok3',
	ok4	='$ok4',
	ok5	='$ok5',
	ok6	='$ok6',
	ok7	='$ok7',
	ok8	='$ok8',
	ok9	='$ok9',
	ok10	='$ok10',
	ok11	='$ok11',
	ok12	='$ok12',
	ok13	='$ok13',
	ok14	='$ok14',
	ok15	='$ok15',
	ok16	='$ok16',
	ok17	='$ok17',
	ok18	='$ok18',
	ok19	='$ok19',
	ok20	='$ok20',
	ok21	='$ok21',
	ok22	='$ok22',
	ok23	='$ok23',
	ok24	='$ok24',
	ok25	='$ok25',
	ok26	='$ok26',
	ok27	='$ok27',
	ok28	='$ok28',
	ok29	='$ok29',
	ok30	='$ok30',
	ok31	='$ok31',
	ok32	='$ok32',
	ok33	='$ok33',
	ok34	='$ok34',
	ok35	='$ok35',
	ok36	='$ok36',
	ok37	='$ok37',
	ok38	='$ok38',
	ok39	='$ok39',
	ok40	='$ok40',
	ok41	='$ok41',
	ok42	='$ok42',
	ok43	='$ok43',
	ok44	='$ok44',
	ok45	='$ok45',
	ok46	='$ok46',
	ok47	='$ok47',
	ok48	='$ok48',
	ok49	='$ok49',
	ok50	='$ok50',
	ok51	='$ok51',
	ok52	='$ok52',
	ok53	='$ok53',
	ok54	='$ok54',
	ok55	='$ok55',
	ok56	='$ok56',
	ok57	='$ok57',
	ok58	='$ok58',
	ok59	='$ok59',
	ok60	='$ok60'
	where noreg='$noreg'
	";

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