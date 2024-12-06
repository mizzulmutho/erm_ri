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


$qi="SELECT noreg FROM ERM_RI_CATATAN_PERSALINAN where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_CATATAN_PERSALINAN(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_CATATAN_PERSALINAN
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
	$par61 = $de['par61'];
	$par62 = $de['par62'];
	$par63 = $de['par63'];
	$par64 = $de['par64'];
	$par65 = $de['par65'];
	$par66 = $de['par66'];
	$par67 = $de['par67'];
	$par68 = $de['par68'];
	$par69 = $de['par69'];
	$par70 = $de['par70'];
	$par71 = $de['par71'];
	$par72 = $de['par72'];
	$par73 = $de['par73'];
	$par74 = $de['par74'];
	$par75 = $de['par75'];
	$par76 = $de['par76'];
	$par77 = $de['par77'];
	$par78 = $de['par78'];
	$par79 = $de['par79'];
	$par80 = $de['par80'];
	$par81 = $de['par81'];
	$par82 = $de['par82'];
	$par83 = $de['par83'];
	$par84 = $de['par84'];
	$par85 = $de['par85'];
	$par86 = $de['par86'];
	$par87 = $de['par87'];
	$par88 = $de['par88'];
	$par89 = $de['par89'];
	$par90 = $de['par90'];
	$par91 = $de['par91'];
	$par92 = $de['par92'];
	$par93 = $de['par93'];
	$par94 = $de['par94'];
	$par95 = $de['par95'];
	$par96 = $de['par96'];
	$par97 = $de['par97'];
	$par98 = $de['par98'];
	$par99 = $de['par99'];
	$par100 = $de['par100'];
	$par101 = $de['par101'];
	$par102 = $de['par102'];
	$par103 = $de['par103'];
	$par104 = $de['par104'];
	$par105 = $de['par105'];
	$par106 = $de['par106'];
	$par107 = $de['par107'];
	$par108 = $de['par108'];
	$par109 = $de['par109'];
	$par110 = $de['par110'];

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
</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='partograf_a.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
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
					<b>CATATAN PERSALINAN</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>
				<tr>
					<td>
						<div class="row">
							<div class="col-3">
								Tanggal
							</div>
							<div class="col-3">
								<input type='date' name='tgl' value='<?php echo $tgl;?>'>
							</div>
							<div class="col-3">
								Penolong Persalinan
							</div>
							<div class="col-3">
								Persalinan Kala Satu
							</div>
						</div>
					</td>
				</tr>	

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Tempat Persalinan
							</div>
							<div class="col-8">
								: 
								<input type='checkbox' name='par1' value='Rumah Ibu' <?php if ($par1=="Rumah Ibu"){echo "checked";}?> >Rumah Ibu
								<input type='checkbox' name='par1' value='Polindes' <?php if ($par1=="Polindes"){echo "checked";}?> >Polindes
								<input type='checkbox' name='par1' value='Klinik Swasta' <?php if ($par1=="Klinik Swasta"){echo "checked";}?> >Klinik Swasta
								<input type='checkbox' name='par1' value='Puskesmas' <?php if ($par1=="Puskesmas"){echo "checked";}?> >Puskesmas
								<input type='checkbox' name='par1' value='Lainnya' <?php if ($par1=="Lainnya"){echo "checked";}?> >Lainnya
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Alamat Persalinan
							</div>
							<div class="col-8">
								:
								<?php 
								if(empty($par2)){
									$par2="Rumah Sakit ...";
								}else{
									$par2=$par2;
								}
								?> 
								<!-- <textarea name= "par2" id="" style="min-width:650px; min-height:60px;"><?php echo $par2;?></textarea> -->
								<input class="" name="par2" value="<?php echo $par2;?>" id="" type="text" size='50' placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								KALA I
							</div>
							<div class="col-8">
								: <input type='checkbox' name='par3' value='Partograf melewati garis waspada' <?php if ($par3=="Partograf melewati garis waspada"){echo "checked";}?> >Partograf melewati garis waspada
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
								: <input type='checkbox' name='par3' value='Lain-lain' <?php if ($par3=="Lain-lain"){echo "checked";}?> >Lain-lain
								Sebutkan,
								<input class="" name="par4" value="<?php echo $par4;?>" id="" type="text" size='50' placeholder="Lain-lain">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Penatalaksanaan yang dilakukan untuk pasien tersebut
							</div>
							<div class="col-8">
								: <input class="" name="par5" value="<?php echo $par5;?>" id="" type="text" size='80' placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Bagaimana hasilnya
							</div>
							<div class="col-8">
								: <input class="" name="par6" value="<?php echo $par6;?>" id="" type="text" size='80' placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<hr>
				<tr>
					<td>
						<div class="row">
							<div class="col-12">
								KALA II<br>
								<table>
									<tr>
										<td>
											Lama kala II : <input class="" name="par7" value="<?php echo $par7;?>" id="" type="text" size='5' placeholder=""> menit
										</td>
										<td>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Episiotomi : 
											<input type='checkbox' name='par8' value='Tidak' <?php if ($par8=="Tidak"){echo "checked";}?> >Tidak 
											<input type='checkbox' name='par8' value='Ya' <?php if ($par8=="Ya"){echo "checked";}?> >Ya
										</td>
										<td>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Indikasi : 
											<input class="" name="par9" value="<?php echo $par9;?>" id="" type="text" size='50' placeholder="">
										</td>
									</tr>
									<tr>
										<td colspan='3'>
											Pendamping pada saat bersalin : 
											<input type='checkbox' name='par10' value='Suami' <?php if ($par10=="Suami"){echo "checked";}?> >Suami 
											<input type='checkbox' name='par10' value='Keluarga' <?php if ($par10=="Keluarga"){echo "checked";}?> >Keluarga 
											<input type='checkbox' name='par10' value='Teman' <?php if ($par10=="Teman"){echo "checked";}?> >Teman
											<input type='checkbox' name='par10' value='Dukun' <?php if ($par10=="Dukun"){echo "checked";}?> >Dukun 
											<input type='checkbox' name='par10' value='Tidak Ada' <?php if ($par10=="Tidak Ada"){echo "checked";}?> >Tidak Ada 

										</td>
									</tr>
									<tr>
										<td colspan='3'>
											Gawat Janin : 
											<input type='checkbox' name='par11' value='1' <?php if ($par11=="1"){echo "checked";}?> >Memiringkan ibu ke sisi kiri 
											<input type='checkbox' name='par11' value='2' <?php if ($par11=="2"){echo "checked";}?> >Minta ibu menarik nafas
											<input type='checkbox' name='par11' value='3' <?php if ($par11=="3"){echo "checked";}?> >Episiotomi

										</td>
									</tr>
									<tr>
										<td colspan='3'>
											Distosia Bahu : 
											<input type='checkbox' name='par12' value='1' <?php if ($par12=="1"){echo "checked";}?> >Manuver Mac Robert 
											<input type='checkbox' name='par12' value='2' <?php if ($par12=="2"){echo "checked";}?> >Ibu Merangkak
											<input type='checkbox' name='par12' value='3' <?php if ($par12=="3"){echo "checked";}?> >Lainnya
											<input class="" name="par13" value="<?php echo $par13;?>" id="" type="text" size='50' placeholder="Lain-lain">

										</td>
									</tr>
									<tr>
										<td colspan='3'>
											Lain-lain sebutkan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<?php 
											if(empty($par14)){
												$par14="...";
											}else{
												$par14=$par14;
											}
											?> 
											<textarea name= "par14" id="" style="min-width:650px; min-height:60px;"><?php echo $par14;?></textarea>

										</td>
									</tr>
									<tr>
										<td colspan='3'>
											Penatalaksanaan yang digunakan untuk masalah tersebut
											<?php 
											if(empty($par15)){
												$par15="...";
											}else{
												$par15=$par15;
											}
											?> 
											<textarea name= "par15" id="" style="min-width:650px; min-height:60px;"><?php echo $par15;?></textarea>

										</td>
									</tr>
									<tr>
										<td colspan='3'>
											Bagaimana hasilnya &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<?php 
											if(empty($par16)){
												$par16="...";
											}else{
												$par16=$par16;
											}
											?> 
											<textarea name= "par16" id="" style="min-width:650px; min-height:60px;"><?php echo $par16;?></textarea>

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
								KALA III<br>
								<table>
									<tr>
										<td>
											Lama kala III : <input class="" name="par17" value="<?php echo $par17;?>" id="" type="text" size='5' placeholder=""> menit
										</td>
										<td>&nbsp;
										</td>
										<td>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jumlah Pendarahan : 
											<input class="" name="par18" value="<?php echo $par18;?>" id="" type="text" size='10' placeholder="">ml
										</td>
									</tr>
									<tr>
										<td colspan='3'>
											a. Pemberian Oksitosin 10 U im < 2 menit ?  
											<input type='checkbox' name='par19' value='Ya' <?php if ($par19=="Ya"){echo "checked";}?> >Ya 
											<input type='checkbox' name='par19' value='Tidak' <?php if ($par19=="Tidak"){echo "checked";}?> >Tidak 
											<input class="" name="par20" value="<?php echo $par20;?>" id="" type="text" size='50' placeholder="Alasan">
										</td>
									</tr>
									<tr>
										<td colspan='3'>
											&nbsp;&nbsp;&nbsp;Pemberian ulang Oksitosin (2x) ?  
											<input type='checkbox' name='par21' value='Ya' <?php if ($par21=="Ya"){echo "checked";}?> >Ya 
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input class="" name="par22" value="<?php echo $par22;?>" id="" type="text" size='50' placeholder="Alasan">
										</td>
									</tr>
									<tr>
										<td colspan='3'>
											b. Penegangan tali pusat terkendali ?  
											<input type='checkbox' name='par23' value='Ya' <?php if ($par23=="Ya"){echo "checked";}?> >Ya 
											<input type='checkbox' name='par23' value='Tidak' <?php if ($par23=="Tidak"){echo "checked";}?> >Tidak 
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input class="" name="par24" value="<?php echo $par24;?>" id="" type="text" size='50' placeholder="Alasan">
										</td>
									</tr>
									<tr>
										<td colspan='3'>
											c. Masase fundus uteri? 
											<input type='checkbox' name='par25' value='Ya' <?php if ($par25=="Ya"){echo "checked";}?> >Ya 
											<input type='checkbox' name='par25' value='Tidak' <?php if ($par25=="Tidak"){echo "checked";}?> >Tidak 
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input class="" name="par26" value="<?php echo $par26;?>" id="" type="text" size='50' placeholder="Alasan">
										</td>
									</tr>
									<tr>
										<td colspan='3'>
											Laseasi Perineum, 
											derajat <input class="" name="par27" value="<?php echo $par27;?>" id="" type="text" size='3' placeholder=""> 
											tindakan : <input type='checkbox' name='par28' value='Ya' <?php if ($par28=="Ya"){echo "checked";}?> >penjahitan dengan/tanpa(*)anastesi<br>
											Plasenta tidak lahir ? 30 menit 
											<input type='checkbox' name='par29' value='Ya' <?php if ($par29=="Ya"){echo "checked";}?> >mengeluarkan secara manual 
											<input type='checkbox' name='par29' value='Tidak' <?php if ($par29=="Tidak"){echo "checked";}?> >merujuk<br>
											<input type='checkbox' name='par29' value='Lain' <?php if ($par29=="Lain"){echo "checked";}?> >tindakan lain :
											<input class="" name="par30" value="<?php echo $par30;?>" id="" type="text" size='5' placeholder=""><br>
											Atoni Uteri : 
											<input type='checkbox' name='par31' value='1' <?php if ($par31=="1"){echo "checked";}?> >kompresi bimanual internal<br>
											<input type='checkbox' name='par31' value='2' <?php if ($par31=="2"){echo "checked";}?> >metil ergometrin 0,2 mg im 
											<input type='checkbox' name='par31' value='3' <?php if ($par31=="3"){echo "checked";}?> >oksitosin drip<br>
											<input type='checkbox' name='par31' value='4' <?php if ($par31=="4"){echo "checked";}?> >Lain-lain, 
											sebutkan : <input class="" name="par32" value="<?php echo $par32;?>" id="" type="text" size='10' placeholder=""> <br>
											Penatalaksanaan yang dilakukan untuk masalah tersebut 
											<input class="" name="par33" value="<?php echo $par33;?>" id="" type="text" size='50' placeholder=""><br>
											Bagaimana hasilnya?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input class="" name="par34" value="<?php echo $par34;?>" id="" type="text" size='50' placeholder="">
											<br>
											BAYU BARU LAHIR<br>
											Berat badan : <input class="" name="par35" value="<?php echo $par35;?>" id="" type="text" size='5' placeholder=""> gram, 
											panjang : <input class="" name="par36" value="<?php echo $par36;?>" id="" type="text" size='5' placeholder=""> cm, 
											jenis kelamin : <input class="" name="par37" value="<?php echo $par37;?>" id="" type="text" size='1' placeholder=""> L/P(*) nilai apgar 
											<input class="" name="par38" value="<?php echo $par38;?>" id="" type="text" size='50' placeholder=""><br>
											Pemberian Asi < 1 jam 
											<input type='checkbox' name='par39' value='1' <?php if ($par39=="1"){echo "checked";}?> >ya 
											<input type='checkbox' name='par39' value='2' <?php if ($par39=="2"){echo "checked";}?> >tidak,
											alasan <input class="" name="par40" value="<?php echo $par40;?>" id="" type="text" size='50' placeholder=""> <br>
											Bayi lahir pucat/biru/lemas:<br>
											<input type='checkbox' name='par41' value='1' <?php if ($par41=="1"){echo "checked";}?> >mengeringkan 
											<input type='checkbox' name='par42' value='2' <?php if ($par42=="2"){echo "checked";}?> >menghangatkan 
											<input type='checkbox' name='par43' value='3' <?php if ($par43=="3"){echo "checked";}?> >bebaskan jalan nafas 
											<input type='checkbox' name='par44' value='4' <?php if ($par44=="4"){echo "checked";}?> >stimulasi/rangsang takti<br>
											<input type='checkbox' name='par45' value='5' <?php if ($par45=="5"){echo "checked";}?> >lain-lain, sebutkan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input class="" name="par46" value="<?php echo $par46;?>" id="" type="text" size='50' placeholder=""><br>

											<input type='checkbox' name='par47' value='1' <?php if ($par47=="1"){echo "checked";}?> >Cacat bawaan, 
											sebutkan 
											<input class="" name="par48" value="<?php echo $par48;?>" id="" type="text" size='50' placeholder=""> <br>
											<input type='checkbox' name='par49' value='1' <?php if ($par49=="1"){echo "checked";}?> >Lain-lain,sebutkan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
											<input class="" name="par50" value="<?php echo $par50;?>" id="" type="text" size='50' placeholder=""><br>
											Penatalaksanaan yang dilakukan untuk masalah tersebut
											<input class="" name="par51" value="<?php echo $par51;?>" id="" type="text" size='50' placeholder=""><br>
											Bagaimana hasilnya?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input class="" name="par52" value="<?php echo $par52;?>" id="" type="text" size='50' placeholder=""><br>

										</td>
									</tr>

								</table>
							</div>
							<div class="col-12">
								<hr>
								PEMANTAUAN PERSALINAN KALA IV<br>
								<table width="100%" border='1'>
									<tr>
										<td>Jam ke</td>
										<td>Waktu</td>
										<td>Tekanan darah mmHg</td>
										<td>Nadi per Menit</td>
										<td>Suhu C</td>
										<td>Fungsi fundus uteri</td>
										<td>Konteraksi Uterus</td>
										<td>Kandung Kemih</td>
										<td>Perdarahan</td>
									</tr>
									<tr>
										<td><input class="" name="par53" value="<?php echo $par53;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par54" value="<?php echo $par54;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par55" value="<?php echo $par55;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par56" value="<?php echo $par56;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par57" value="<?php echo $par57;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par58" value="<?php echo $par58;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par59" value="<?php echo $par59;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par60" value="<?php echo $par60;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par61" value="<?php echo $par61;?>" id="" type="text" size='10' placeholder=""></td>
									</tr>
									<tr>
										<td><input class="" name="par62" value="<?php echo $par62;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par63" value="<?php echo $par63;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par64" value="<?php echo $par64;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par65" value="<?php echo $par65;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par66" value="<?php echo $par66;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par67" value="<?php echo $par67;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par68" value="<?php echo $par68;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par69" value="<?php echo $par69;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par70" value="<?php echo $par70;?>" id="" type="text" size='10' placeholder=""></td>
									</tr>
									<tr>
										<td><input class="" name="par71" value="<?php echo $par71;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par72" value="<?php echo $par72;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par73" value="<?php echo $par73;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par74" value="<?php echo $par74;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par75" value="<?php echo $par75;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par76" value="<?php echo $par76;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par77" value="<?php echo $par77;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par78" value="<?php echo $par78;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par79" value="<?php echo $par79;?>" id="" type="text" size='10' placeholder=""></td>
									</tr>
									<tr>
										<td><input class="" name="par80" value="<?php echo $par80;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par81" value="<?php echo $par81;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par82" value="<?php echo $par82;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par83" value="<?php echo $par83;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par84" value="<?php echo $par84;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par85" value="<?php echo $par85;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par86" value="<?php echo $par86;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par87" value="<?php echo $par87;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par88" value="<?php echo $par88;?>" id="" type="text" size='10' placeholder=""></td>
									</tr>
									<tr>
										<td><input class="" name="par89" value="<?php echo $par89;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par90" value="<?php echo $par90;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par91" value="<?php echo $par91;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par92" value="<?php echo $par92;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par93" value="<?php echo $par93;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par94" value="<?php echo $par94;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par95" value="<?php echo $par95;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par96" value="<?php echo $par96;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par97" value="<?php echo $par97;?>" id="" type="text" size='10' placeholder=""></td>
									</tr>
									<tr>
										<td><input class="" name="par98" value="<?php echo $par98;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par99" value="<?php echo $par99;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par100" value="<?php echo $par100;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par101" value="<?php echo $par101;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par102" value="<?php echo $par102;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par103" value="<?php echo $par103;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par104" value="<?php echo $par104;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par105" value="<?php echo $par105;?>" id="" type="text" size='10' placeholder=""></td>
										<td><input class="" name="par106" value="<?php echo $par106;?>" id="" type="text" size='10' placeholder=""></td>
									</tr>
								</table>
								<br>
								<table>
									<tr>
										<td>
											Masalah Kala IV :<br>
											Penatalaksanaan yang dilakukan untuk masalah tersebut
											<input class="" name="par107" value="<?php echo $par107;?>" id="" type="text" size='50' placeholder=""><br>
											Bagaimana hasilnya?&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<input class="" name="par108" value="<?php echo $par108;?>" id="" type="text" size='50' placeholder=""><br>
										</td>
									</tr>
									<tr><td>
										Petugas pembuat catatan persalian &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
										<input class="" name="par109" value="<?php echo $par109;?>" id="karyawan1" type="text" size='50' placeholder="Isikan Nama Petugas">
										<br>
									</td></tr>
								</table>
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
	$par60	= $_POST["par60"];
	$par61 = $_POST['par61'];
	$par62 = $_POST['par62'];
	$par63 = $_POST['par63'];
	$par64 = $_POST['par64'];
	$par65 = $_POST['par65'];
	$par66 = $_POST['par66'];
	$par67 = $_POST['par67'];
	$par68 = $_POST['par68'];
	$par69 = $_POST['par69'];
	$par70 = $_POST['par70'];
	$par71 = $_POST['par71'];
	$par72 = $_POST['par72'];
	$par73 = $_POST['par73'];
	$par74 = $_POST['par74'];
	$par75 = $_POST['par75'];
	$par76 = $_POST['par76'];
	$par77 = $_POST['par77'];
	$par78 = $_POST['par78'];
	$par79 = $_POST['par79'];
	$par80 = $_POST['par80'];
	$par81 = $_POST['par81'];
	$par82 = $_POST['par82'];
	$par83 = $_POST['par83'];
	$par84 = $_POST['par84'];
	$par85 = $_POST['par85'];
	$par86 = $_POST['par86'];
	$par87 = $_POST['par87'];
	$par88 = $_POST['par88'];
	$par89 = $_POST['par89'];
	$par90 = $_POST['par90'];
	$par91 = $_POST['par91'];
	$par92 = $_POST['par92'];
	$par93 = $_POST['par93'];
	$par94 = $_POST['par94'];
	$par95 = $_POST['par95'];
	$par96 = $_POST['par96'];
	$par97 = $_POST['par97'];
	$par98 = $_POST['par98'];
	$par99 = $_POST['par99'];
	$par100 = $_POST['par100'];
	$par101 = $_POST['par101'];
	$par102 = $_POST['par102'];
	$par103 = $_POST['par103'];
	$par104 = $_POST['par104'];
	$par105 = $_POST['par105'];
	$par106 = $_POST['par106'];
	$par107 = $_POST['par107'];
	$par108 = $_POST['par108'];
	$par109 = $_POST['par109'];
	$par110 = $_POST['par110'];

	$q  = "update ERM_RI_CATATAN_PERSALINAN set
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
	par60	='$par60',
	par61 = '$par61',
	par62 = '$par62',
	par63 = '$par63',
	par64 = '$par64',
	par65 = '$par65',
	par66 = '$par66',
	par67 = '$par67',
	par68 = '$par68',
	par69 = '$par69',
	par70 = '$par70',
	par71 = '$par71',
	par72 = '$par72',
	par73 = '$par73',
	par74 = '$par74',
	par75 = '$par75',
	par76 = '$par76',
	par77 = '$par77',
	par78 = '$par78',
	par79 = '$par79',
	par80 = '$par80',
	par81 = '$par81',
	par82 = '$par82',
	par83 = '$par83',
	par84 = '$par84',
	par85 = '$par85',
	par86 = '$par86',
	par87 = '$par87',
	par88 = '$par88',
	par89 = '$par89',
	par90 = '$par90',
	par91 = '$par91',
	par92 = '$par92',
	par93 = '$par93',
	par94 = '$par94',
	par95 = '$par95',
	par96 = '$par96',
	par97 = '$par97',
	par98 = '$par98',
	par99 = '$par99',
	par100 = '$par100',
	par101 = '$par101',
	par102 = '$par102',
	par103 = '$par103',
	par104 = '$par104',
	par105 = '$par105',
	par106 = '$par106',
	par107 = '$par107',
	par108 = '$par108',
	par109 = '$par109',
	par110 = '$par110'

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

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>