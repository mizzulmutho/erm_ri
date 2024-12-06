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


$qi="SELECT noreg FROM ERM_RI_PARTOGRAF where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_PARTOGRAF(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);

	$q  = "insert into ERM_RI_PARTOGRAF1(noreg,userid,tglentry,tglrawat) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);

	$q  = "insert into ERM_RI_PARTOGRAF2(noreg,userid,tglentry,tglrawat) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);

	$q  = "insert into ERM_RI_PARTOGRAF3(noreg,userid,tglentry,tglrawat) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);

	$q  = "insert into ERM_RI_PARTOGRAF4(noreg,userid,tglentry,tglrawat) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);

}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_PARTOGRAF
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$par1 = $de['par1'];
	$par2= $de['par2'];
	$par3= $de['par3'];
	$par4= $de['par4'];
	$par5= $de['par5'];
	$par6= $de['par6'];
	$par7= $de['par7'];
	$par8= $de['par8'];
	$par9= $de['par9'];
	$par10= $de['par10'];
	$par11= $de['par11'];
	$par12= $de['par12'];
	$par13= $de['par13'];
	$par14= $de['par14'];
	$par15= $de['par15'];
	$par16= $de['par16'];
	$par17= $de['par17'];
	$par18= $de['par18'];
	$par19= $de['par19'];
	$par20= $de['par20'];
	$par21= $de['par21'];
	$par22= $de['par22'];
	$par23= $de['par23'];
	$par24= $de['par24'];
	$par25= $de['par25'];
	$par26= $de['par26'];
	$par27= $de['par27'];
	$par28= $de['par28'];
	$par29= $de['par29'];
	$par30= $de['par30'];
	$par31= $de['par31'];
	$par32= $de['par32'];
	$par33= $de['par33'];
	$par34= $de['par34'];
	$par35= $de['par35'];
	$par36= $de['par36'];
	$par37= $de['par37'];
	$par38= $de['par38'];
	$par39= $de['par39'];
	$par40= $de['par40'];
	$par41= $de['par41'];
	$par42= $de['par42'];
	$par43= $de['par43'];
	$par44= $de['par44'];
	$par45= $de['par45'];
	$par46= $de['par46'];
	$par47= $de['par47'];
	$par48= $de['par48'];
	$par49= $de['par49'];
	$par50= $de['par50'];
	$par51= $de['par51'];
	$par52= $de['par52'];
	$par53= $de['par53'];
	$par54= $de['par54'];
	$par55= $de['par55'];
	$par56= $de['par56'];
	$par57= $de['par57'];
	$par58= $de['par58'];
	$par59= $de['par59'];
	$par60= $de['par60'];

	//parto1
	$qu="SELECT *  FROM ERM_RI_PARTOGRAF1 where noreg='$noreg'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC);

	for ($x = 1; $x <= 416; $x++) {
		$parto1[$x] = $d1u[parto.$x];
	}

	//parto2
	$qu="SELECT *  FROM ERM_RI_PARTOGRAF2 where noreg='$noreg'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC);

	for ($x = 1; $x <= 400; $x++) {
		$parto2[$x] = $d1u[parto.$x];
	}


	//parto3
	$qu="SELECT *  FROM ERM_RI_PARTOGRAF3 where noreg='$noreg'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC);

	for ($x = 1; $x <= 400; $x++) {
		$parto3[$x] = $d1u[parto.$x];
	}


	//parto4
	$qu="SELECT *  FROM ERM_RI_PARTOGRAF4 where noreg='$noreg'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC);

	for ($x = 1; $x <= 400; $x++) {
		$parto4[$x] = $d1u[parto.$x];
	}

}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Resume Medis</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<!-- <script type="text/javascript" src="ckeditor/ckeditor.js"></script> -->
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
	<div class="container-fluid">

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
					<a href='catatan_persalinan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-folder-symlink"></i> Catatan Persalinan</a>
					&nbsp;&nbsp;
					<a href='observasi_persalinan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-folder-symlink"></i> Observasi Persalinan</a>
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
					<b>LEMBAR PARTOGRAF</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Tanggal
							</div>
							<div class="col-8">
								: <input type='date' name='tgl' value='<?php echo $tgl;?>'>
							</div>
						</div>
					</td>
				</tr>	
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Ketuban Pecah Sejak Jam
							</div>
							<div class="col-8">
								: <input type='text' name='par1' value='<?php echo $par1;?>'>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Mules Sejak Jam
							</div>
							<div class="col-8">
								: <input type='text' name='par2' value='<?php echo $par2;?>'>
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
								&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
							</div>
						</div>
					</td>
				</tr>	
			</table>

			<br>
			<table width='100%' border='1'>
				<tr>
					<td width='10%'>
						Denyut Jantung Janin
					</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td>200</td>
								<?php 
								for ($i = 1; $i <= 32; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>190</td>
								<?php 
								for ($i = 33; $i <= 64; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>180</td>
								<?php 
								for ($i = 65; $i <= 96; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>170</td>
								<?php 
								for ($i = 97; $i <= 128; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>160</td>
								<?php 
								for ($i = 129; $i <= 160; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>150</td>
								<?php 
								for ($i = 161; $i <= 192; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>140</td>
								<?php 
								for ($i = 193; $i <= 224; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>130</td>
								<?php 
								for ($i = 225; $i <= 256; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>120</td>
								<?php 
								for ($i = 257; $i <= 288; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>110</td>
								<?php 
								for ($i = 289; $i <= 320; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>100</td>
								<?php 
								for ($i = 321; $i <= 352; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>90</td>
								<?php 
								for ($i = 353; $i <= 384; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td>80</td>
								<?php 
								for ($i = 385; $i <= 416; $i++) {
									$nmparto = $parto1[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto1$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>							
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan1' value='simpan1' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>
				<tr>
					<td>Air Ketuban</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 1; $i <= 32; $i++) {
									$nmparto = $parto2[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>


						</table>
					</td>
				</tr>

				<tr>
					<td>Penyusupan</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 33; $i <= 64; $i++) {
									$nmparto = $parto2[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>


						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan2' value='simpan2' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>

				<tr>
					<td>
						Pembukaan Serviks (cm) beri tanda x<hr><br>
						Turunya kepala beri tanda O
					</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width='5%'>10&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 1; $i <= 32; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>9&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 33; $i <= 64; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>8&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 65; $i <= 96; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>7&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 97; $i <= 128; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>6&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 129; $i <= 160; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>6&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 161; $i <= 192; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>5&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 193; $i <= 224; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>4&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 225; $i <= 256; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>3&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 257; $i <= 288; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>2&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 289; $i <= 320; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>1&nbsp;&nbsp;&nbsp;</td>
								<?php 
								for ($i = 321; $i <= 352; $i++) {
									$nmparto = $parto3[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto3$i' value='$parto3[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width='5%'>0&nbsp;&nbsp;&nbsp;</td>
								<td colspan="2" align="center">1</td>
								<td colspan="2" align="center">2</td>
								<td colspan="2" align="center">3</td>
								<td colspan="2" align="center">4</td>
								<td colspan="2" align="center">5</td>
								<td colspan="2" align="center">6</td>
								<td colspan="2" align="center">7</td>
								<td colspan="2" align="center">8</td>
								<td colspan="2" align="center">9</td>
								<td colspan="2" align="center">10</td>
								<td colspan="2" align="center">11</td>
								<td colspan="2" align="center">12</td>
								<td colspan="2" align="center">13</td>
								<td colspan="2" align="center">14</td>
								<td colspan="2" align="center">15</td>
								<td colspan="2" align="center">16</td>
							</tr>
							<tr>
								<td width='5%'>0&nbsp;&nbsp;&nbsp;</td>
								<td colspan="2"><input type='text' name='parto3353' value='<?php echo $parto3[353] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3354' value='<?php echo $parto3[354] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3355' value='<?php echo $parto3[355] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3356' value='<?php echo $parto3[356] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3357' value='<?php echo $parto3[357] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3358' value='<?php echo $parto3[358] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3359' value='<?php echo $parto3[359] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3360' value='<?php echo $parto3[360] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3361' value='<?php echo $parto3[361] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3362' value='<?php echo $parto3[362] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3363' value='<?php echo $parto3[363] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3364' value='<?php echo $parto3[364] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3365' value='<?php echo $parto3[365] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3366' value='<?php echo $parto3[366] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3367' value='<?php echo $parto3[367] ?>' size='8'></td>
								<td colspan="2"><input type='text' name='parto3368' value='<?php echo $parto3[368] ?>' size='8'></td>
							</tr>

						</table>
					</td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan3' value='simpan3' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>

				<tr>
					<td>
						Kontraksi tiap 10 menit<br>
						a = < 20 (detik)<br>
						b = 20 - 40 (detik)<br>
						c = > 40 (detik)<br>
					</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">5</td>
								<?php 
								for ($i = 65; $i <= 96; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width="5%">4</td>
								<?php 
								for ($i = 97; $i <= 128; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width="5%">3</td>
								<?php 
								for ($i = 129; $i <= 160; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width="5%">2</td>
								<?php 
								for ($i = 161; $i <= 192; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width="5%">1</td>
								<?php 
								for ($i = 193; $i <= 224; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>


						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan4' value='simpan4' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>

				<tr>
					<td>Oksitosin U/L tetes/menit</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 225; $i <= 256; $i++) {
									$nmparto = $parto2[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 257; $i <= 288; $i++) {
									$nmparto = $parto2[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>

						</table>
					</td>
				</tr>

				<tr>
					<td>Obat dan Cairan IV</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 289; $i <= 320; $i++) {
									$nmparto = $parto2[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto2$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>

						</table>
					</td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan5' value='simpan5' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>

				<tr>
					<td>Nadi / Tekanan Darah</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">180&nbsp;</td>
								<?php 
								for ($i = 1; $i <= 32; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">170&nbsp;</td>
								<?php 
								for ($i = 33; $i <= 64; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">160&nbsp;</td>
								<?php 
								for ($i = 65; $i <= 96; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">150&nbsp;</td>
								<?php 
								for ($i = 97; $i <= 128; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">140&nbsp;</td>
								<?php 
								for ($i = 129; $i <= 160; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">130&nbsp;</td>
								<?php 
								for ($i = 161; $i <= 192; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">120&nbsp;</td>
								<?php 
								for ($i = 193; $i <= 224; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">110&nbsp;</td>
								<?php 
								for ($i = 225; $i <= 256; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">100&nbsp;</td>
								<?php 
								for ($i = 257; $i <= 288; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">90&nbsp;</td>
								<?php 
								for ($i = 289; $i <= 320; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">80&nbsp;</td>
								<?php 
								for ($i = 321; $i <= 352; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">70&nbsp;</td>
								<?php 
								for ($i = 353; $i <= 384; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>
							<tr>
								<td width="5%">60&nbsp;</td>
								<?php 
								for ($i = 385; $i <= 416; $i++) {
									$nmparto = $parto4[$i];
									if($nmparto=='Ya'){
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya' checked>
										</td>
										";
									}else{
										echo "
										<td align='center'>
										<input type='checkbox' name='parto4$i' value='Ya'>
										</td>
										";
									}
								}
								?>
							</tr>

						</table>
					</td>
				</tr>

				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan6' value='simpan6' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>

				<tr>
					<td>Suhu</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 289; $i <= 320; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>


						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan7' value='simpan7' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>

				<tr>
					<td>
						<b>Urine</b><br>
						Protein<br>
						Aseton<br>
						Volume<br>
					</td>
					<td>
						<table border='1' width="100%">
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 321; $i <= 352; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 353; $i <= 384; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>
							<tr>
								<td width="5%">&nbsp;</td>
								<?php 
								for ($i = 385; $i <= 416; $i++) {
									$nmparto = $parto2[$i];
									echo "
									<td align='center'>
									<input type='text' name='parto2$i' value='$parto2[$i]' size='1'>
									</td>
									";
								}
								?>
							</tr>


						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;&nbsp;<input type='submit' name='simpan8' value='simpan8' style="color: white;background: #66CDAA;border-color: #66CDAA;"></td>
				</tr>

			</table>

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
	$tgl	= $_POST["tgl"];
	$par1	= $_POST["par1"];
	$par2	= $_POST["par2"];
	$par3	= $_POST["par3"];
	$par4	= $_POST["par4"];
	$par5	= $_POST["par5"];
	$par6	= $_POST["par6"];
	$par7	= $_POST["par7"];
	$par8	= $_POST["par8"];
	$par9	= $_POST["par9"];
	$par10	= $_POST["par10"];
	$par11	= $_POST["par11"];
	$par12	= $_POST["par12"];
	$par13	= $_POST["par13"];
	$par14	= $_POST["par14"];
	$par15	= $_POST["par15"];
	$par16	= $_POST["par16"];
	$par17	= $_POST["par17"];
	$par18	= $_POST["par18"];
	$par19	= $_POST["par19"];
	$par20	= $_POST["par20"];
	$par21	= $_POST["par21"];
	$par22	= $_POST["par22"];
	$par23	= $_POST["par23"];
	$par24	= $_POST["par24"];
	$par25	= $_POST["par25"];
	$par26	= $_POST["par26"];
	$par27	= $_POST["par27"];
	$par28	= $_POST["par28"];
	$par29	= $_POST["par29"];
	$par30	= $_POST["par30"];
	$par31	= $_POST["par31"];
	$par32	= $_POST["par32"];
	$par33	= $_POST["par33"];
	$par34	= $_POST["par34"];
	$par35	= $_POST["par35"];
	$par36	= $_POST["par36"];
	$par37	= $_POST["par37"];
	$par38	= $_POST["par38"];
	$par39	= $_POST["par39"];
	$par40	= $_POST["par40"];
	$par41	= $_POST["par41"];
	$par42	= $_POST["par42"];
	$par43	= $_POST["par43"];
	$par44	= $_POST["par44"];
	$par45	= $_POST["par45"];
	$par46	= $_POST["par46"];
	$par47	= $_POST["par47"];
	$par48	= $_POST["par48"];
	$par49	= $_POST["par49"];
	$par50	= $_POST["par50"];
	$par51	= $_POST["par51"];
	$par52	= $_POST["par52"];
	$par53	= $_POST["par53"];
	$par54	= $_POST["par54"];
	$par55	= $_POST["par55"];
	$par56	= $_POST["par56"];
	$par57	= $_POST["par57"];
	$par58	= $_POST["par58"];
	$par59	= $_POST["par59"];
	$par60	= $_POST["ok60"];

	$q  = "update ERM_RI_PARTOGRAF set
	tgl 	='$tgl',
	par1	='$par1',
	par2	='$par2',
	par3	='$par3',
	par4	='$par4',
	par5	='$par5',
	par6	='$par6',
	par7	='$par7',
	par8	='$par8',
	par9	='$par9',
	par10	='$par10',
	par11	='$par11',
	par12	='$par12',
	par13	='$par13',
	par14	='$par14',
	par15	='$par15',
	par16	='$par16',
	par17	='$par17',
	par18	='$par18',
	par19	='$par19',
	par20	='$par20',
	par21	='$par21',
	par22	='$par22',
	par23	='$par23',
	par24	='$par24',
	par25	='$par25',
	par26	='$par26',
	par27	='$par27',
	par28	='$par28',
	par29	='$par29',
	par30	='$par30',
	par31	='$par31',
	par32	='$par32',
	par33	='$par33',
	par34	='$par34',
	par35	='$par35',
	par36	='$par36',
	par37	='$par37',
	par38	='$par38',
	par39	='$par39',
	par40	='$par40',
	par41	='$par41',
	par42	='$par42',
	par43	='$par43',
	par44	='$par44',
	par45	='$par45',
	par46	='$par46',
	par47	='$par47',
	par48	='$par48',
	par49	='$par49',
	par50	='$par50',
	par51	='$par51',
	par52	='$par52',
	par53	='$par53',
	par54	='$par54',
	par55	='$par55',
	par56	='$par56',
	par57	='$par57',
	par58	='$par58',
	par59	='$par59',
	par60	='$par60'
	where noreg='$noreg'
	";
	$hs = sqlsrv_query($conn,$q);
}


if (isset($_POST["simpan1"])) {
	//parto1
	for ($i = 1; $i <= 416; $i++) {
		$parto = 'parto1'.$i;
		$parto	= trim($_POST[$parto]);
		$q  = "update ERM_RI_PARTOGRAF1 set parto$i = '$parto' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);
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


if (isset($_POST["simpan2"])) {
	//parto1
	for ($i = 1; $i <= 64; $i++) {
		$parto = 'parto2'.$i;
		$parto	= trim($_POST[$parto]);
		$q  = "update ERM_RI_PARTOGRAF2 set parto$i = '$parto' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);
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

if (isset($_POST["simpan3"])) {
	//parto1
	for ($i = 1; $i <= 400; $i++) {
		$parto = 'parto3'.$i;
		$parto	= trim($_POST[$parto]);
		$q  = "update ERM_RI_PARTOGRAF3 set parto$i = '$parto' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);
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


if (isset($_POST["simpan4"])) {
	//parto1
	for ($i = 65; $i <= 224; $i++) {
		$parto = 'parto2'.$i;
		$parto	= trim($_POST[$parto]);
		$q  = "update ERM_RI_PARTOGRAF2 set parto$i = '$parto' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);
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


if (isset($_POST["simpan5"])) {
	//parto1
	for ($i = 225; $i <= 288; $i++) {
		$parto = 'parto2'.$i;
		$parto	= trim($_POST[$parto]);
		$q  = "update ERM_RI_PARTOGRAF2 set parto$i = '$parto' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);
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

if (isset($_POST["simpan6"])) {
	//parto1
	for ($i = 1; $i <= 400; $i++) {
		$parto = 'parto4'.$i;
		$parto	= trim($_POST[$parto]);
		$q  = "update ERM_RI_PARTOGRAF4 set parto$i = '$parto' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);
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


if (isset($_POST["simpan7"])) {
	//parto1
	for ($i = 289; $i <= 320; $i++) {
		$parto = 'parto2'.$i;
		$parto	= trim($_POST[$parto]);
		$q  = "update ERM_RI_PARTOGRAF2 set parto$i = '$parto' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);
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


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>
