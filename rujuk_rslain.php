<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

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


$qi="SELECT noreg FROM  ERM_RI_RUJUK_RSLAIN where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into  ERM_RI_RUJUK_RSLAIN(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM  ERM_RI_RUJUK_RSLAIN
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];

	$surat1 = $de['surat1'];
	$surat2= $de['surat2'];
	$surat3= $de['surat3'];
	$surat4= $de['surat4'];
	$surat5= $de['surat5'];
	$surat6= $de['surat6'];
	$surat7= $de['surat7'];
	$surat8= $de['surat8'];
	$surat9= $de['surat9'];
	$surat10= $de['surat10'];
	$surat11= $de['surat11'];
	$surat12= $de['surat12'];
	$surat13= $de['surat13'];
	$surat14= $de['surat14'];
	$surat15= $de['surat15'];
	$surat16= $de['surat16'];
	$surat17= $de['surat17'];
	$surat18= $de['surat18'];
	$surat19= $de['surat19'];
	$surat20= $de['surat20'];
	$surat21= $de['surat21'];
	$surat22= $de['surat22'];
	$surat23= $de['surat23'];
	$surat24= $de['surat24'];
	$surat25= $de['surat25'];
	$surat26= $de['surat26'];
	$surat27= $de['surat27'];
	$surat28= $de['surat28'];
	$surat29= $de['surat29'];
	$surat30= $de['surat30'];
	$surat31= $de['surat31'];
	$surat32= $de['surat32'];
	$surat33= $de['surat33'];
	$surat34= $de['surat34'];
	$surat35= $de['surat35'];
	$surat36= $de['surat36'];
	$surat37= $de['surat37'];
	$surat38= $de['surat38'];
	$surat39= $de['surat39'];
	$surat40= $de['surat40'];
	$surat41= $de['surat41'];
	$surat42= $de['surat42'];
	$surat43= $de['surat43'];
	$surat44= $de['surat44'];
	$surat45= $de['surat45'];
	$surat46= $de['surat46'];
	$surat47= $de['surat47'];
	$surat48= $de['surat48'];
	$surat49= $de['surat49'];
	$surat50= $de['surat50'];
	$surat51= $de['surat51'];
	$surat52= $de['surat52'];
	$surat53= $de['surat53'];
	$surat54= $de['surat54'];
	$surat55= $de['surat55'];
	$surat56= $de['surat56'];
	$surat57= $de['surat57'];
	$surat58= $de['surat58'];
	$surat59= $de['surat59'];
	$surat60= $de['surat60'];
	$surat61= $de['surat61'];
	$surat62= $de['surat62'];
	$surat63= $de['surat63'];
	$surat64= $de['surat64'];
	$surat65= $de['surat65'];
	$surat66= $de['surat66'];
	$surat67= $de['surat67'];
	$surat68= $de['surat68'];
	$surat69= $de['surat69'];
	$surat70= $de['surat70'];

}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Surat Keterangan Kematian</title>  
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

	<style>
		@media print {
			input, textarea {
				border: none !important;
				background: none !important;
				box-shadow: none !important;
				outline: none !important;
				color: black !important;
				font: inherit !important;
				padding: 0 !important;
				margin: 0 !important;
			}

			input, textarea {
				pointer-events: none;
			}

			input::placeholder,
			textarea::placeholder {
				color: transparent !important;
			}

			input, textarea {
				-webkit-appearance: none;
			}

			/* Jika ingin input tampil full width sebagai teks */
			input, textarea {
				width: auto !important;
			}

			button {
				display: none; /* Sembunyikan tombol print saat mencetak */
			}

			.hide-on-print {
				display: none !important;
			}
		}
	</style>

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
					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
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
							<?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
						</div>
					</div>
					<hr>

					<div class="row">
						<div class="col-12 text-center">
							<b>TRANSFER / RUJUK KE RUMAH SAKIT LAIN</b><br>
						</div>
					</div>

					<br>

					<div class="row">
						<div class="col-12 text-center">
							RINGKASAN PASIEN YANG DILAKUKAN TRANSFER / RUJUKAN KE RUMAH SAKIT LAIN
						</div>
					</div>


					<div class="row">
						<div class="col-4">
							Dari ruangan
						</div>
						<div class="col-8">
							: <input type='text' name='surat1' value='<?php echo $surat1; ?>' size='30'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Ke Rumah Sakit : <input type='text' name='surat2' value='<?php echo $surat2; ?>' size='23'>
						</div>
						<div class="col-4">
							Staff yang Kontak
						</div>
						<div class="col-8">
							: <input type='text' name='surat3' value='<?php echo $surat3; ?>' size='30'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Tanggal dan pukul : <input type='text' name='surat4' value='<?php echo $surat4; ?>' size='20'>
						</div>
						<div class="col-4">
							Staff yang menerima Kontak
						</div>
						<div class="col-8">
							: <input type='text' name='surat5' value='<?php echo $surat5; ?>' size='30'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							No. Telp : <input type='text' name='surat6' value='<?php echo $surat6; ?>' size='30'>
						</div>
						<div class="col-4">
							Ambulance Berangkat
						</div>
						<div class="col-8">
							: <input type='text' name='surat7' value='<?php echo $surat7; ?>' size='30'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							Tiba ditempat : <input type='text' name='surat8' value='<?php echo $surat8; ?>' size='25'>
						</div>
						<div class="col-4">
							Alasan Merujuk
						</div>
						<div class="col-8">
							: <input type='text' name='surat9' value='<?php echo $surat9; ?>' size='80'>
						</div>
						<div class="col-4">
							Diagnosa Medik
						</div>
						<div class="col-8">
							: <input type='text' name='surat10' value='<?php echo $surat10; ?>' size='80'>
						</div>
						<div class="col-4">
							Dokter yang merujuk
						</div>
						<div class="col-8">
							: <input type='text' name='surat11' value='<?php echo $surat11; ?>' size='80'>
						</div>


					</div>
					<br>

					<div class="row">
						<div class="col-12 bg-success text-white">
							<b>Catatan Klinis</b><br>
						</div>
					</div>

					<div class="row">
						<div class="col-4">
							1. Alergi
						</div>
						<div class="col-8">
							: <input type='text' name='surat12' value='<?php echo $surat12; ?>' size='80'>
						</div>	
						<div class="col-4">
							2. Pengobatan & Tindakan yang dilakukan
						</div>
						<div class="col-8">
							: <textarea name= "surat13" id="" style="min-width:630px; min-height:150px;"><?php echo $surat13;?></textarea>
						</div>	
						<div class="col-4">
							3. Riwayat Penyakit
						</div>
						<div class="col-8">
							: <input type='text' name='surat14' value='<?php echo $surat14; ?>' size='80'>
						</div>	
						<div class="col-4">
							4. Kapan Intake Oral terakhir
						</div>
						<div class="col-8">
							: <input type='text' name='surat15' value='<?php echo $surat15; ?>' size='80'>
						</div>	
						<div class="col-4">
							5. Tindakan yang telah dilakukan
						</div>
						<div class="col-8">
							: <input type='text' name='surat16' value='<?php echo $surat16; ?>' size='80'>
						</div>	

					</div>

					<div class="row">
						<div class="col-12 bg-success text-white">
							<b>Kondisi pasien saat ini</b><br>
						</div>
					</div>

					<div class="row">
						<div class="col-4">
							1. Kesadaran
						</div>
						<div class="col-8">
							: 
							<?php 
							if(empty($surat17)){
								$surat17="Kesadaran : GCS : E ... V ... M ... \nPupil : ... \nReflek Cahaya : ... \nTD : ... \nNadi : ... \nSuhu : ... \nRR : ... \nSPO2 : ...";
							}else{
								$surat17=$surat17;
							}
							?> 
							<textarea name= "surat17" id="" style="min-width:630px; min-height:220px;"><?php echo $surat17;?></textarea>
						</div>	
						<div class="col-4">
							2. Pasien memakai peralatan medis
						</div>
						<div class="col-8">
							: <textarea name= "surat18" id="" style="min-width:630px; min-height:50px;"><?php echo $surat18;?></textarea>
						</div>	

					</div>


					<div class="row">
						Perawatan pasien yang dibutuhkan :<br>
						<textarea name= "surat19" id="" style="min-width:980px; min-height:50px;"><?php echo $surat19;?></textarea>
					</div>

					<div class="row">
						Kejadian klinis selama dilakukan transfer :<br>
						<textarea name= "surat20" id="" style="min-width:980px; min-height:50px;"><?php echo $surat20;?></textarea>
					</div>

					<div class="row">
						Tanggal dan pukul serah terima pasien :<br>
						<textarea name= "surat21" id="" style="min-width:980px; min-height:50px;"><?php echo $surat21;?></textarea>
					</div>


					<hr>

					<div class="row">					
						<div class="col-8">
							<table width="100%">
								<tr valign="top">
									<td align="center">
										Dokter yang merujuk<br>	
										<input class="" name="surat23" value="<?php echo $surat23;?>" id="dokter" type="text" size='50' placeholder="Isikan nama Dokter perujuk">
										<?php 
										if($surat23){
											$pernyataan = 'Lembar Rujuk Pasien ini telah ditandatangani oleh Petugas: '.$surat23.' pada tanggal: '.$tglinput;
											ob_start();
											QRcode::png($pernyataan, null, 'L', 2, 1);
											$imageString = base64_encode(ob_get_contents());
											ob_end_clean();

											echo '<img src="data:image/png;base64,' . $imageString . '" />';											
										}
										?>
									</td>

									<td align="center">Staf yang melakukan rujukan
										<br>	
										<input class="" name="surat24" value="<?php echo $surat24;?>" id="karyawan2" type="text" size='50' placeholder="Isikan nama petugas">				
										<?php 
										if($surat24){
											$pernyataan2='Lembar Rujuk Pasien ini telah ditandatangani oleh Petugas:'.$surat24.'pada tanggal:'.$tglinput;
											ob_start();
											QRcode::png($pernyataan2, null, 'L', 2, 1);
											$imageString2 = base64_encode(ob_get_contents());
											ob_end_clean();

											echo '<img src="data:image/png;base64,' . $imageString2 . '" />';												
										}
										?>							
									</td>
									<td align="center">Staf yang menerima pasien
										<br>	
										<input class="" name="surat25" value="<?php echo $surat25;?>" id="" type="text" size='50' placeholder="Isikan nama petugas">						<?php 
										if($surat25){
											$pernyataan3='Lembar Rujuk Pasien ini telah ditandatangani oleh Petugas:'.$surat25.'pada tanggal:'.$tglinput;
											ob_start();
											QRcode::png($pernyataan3, null, 'L', 2, 1);
											$imageString3 = base64_encode(ob_get_contents());
											ob_end_clean();

											echo '<img src="data:image/png;base64,' . $imageString3 . '" />';												
										}
										?>					
									</td>
								</tr>
							</table>
						</div>
					</div>

					<div class="row">				
						<div class="col-8 hide-on-print">
							<br>
							<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
							<br><br>
						</div>
					</div>

				</div>


				<br>
			</form>
		</font>
	</body>
</div>
</div>

<?php 


if (isset($_POST["simpan"])) {
	// echo "simpan";

	$surat1	= $_POST["surat1"];
	$surat2	= $_POST["surat2"];
	$surat3	= $_POST["surat3"];
	$surat4	= $_POST["surat4"];
	$surat5	= $_POST["surat5"];
	$surat6	= $_POST["surat6"];
	$surat7	= $_POST["surat7"];
	$surat8	= $_POST["surat8"];
	$surat9	= $_POST["surat9"];
	$surat10	= $_POST["surat10"];
	$surat11	= $_POST["surat11"];
	$surat12	= $_POST["surat12"];
	$surat13	= $_POST["surat13"];
	$surat14	= $_POST["surat14"];
	$surat15	= $_POST["surat15"];
	$surat16	= $_POST["surat16"];
	$surat17	= $_POST["surat17"];
	$surat18	= $_POST["surat18"];
	$surat19	= $_POST["surat19"];
	$surat20	= $_POST["surat20"];
	$surat21	= $_POST["surat21"];
	$surat22	= $_POST["surat22"];
	$surat23	= $_POST["surat23"];
	$surat24	= $_POST["surat24"];
	$surat25	= $_POST["surat25"];
	$surat26	= $_POST["surat26"];
	$surat27	= $_POST["surat27"];
	$surat28	= $_POST["surat28"];
	$surat29	= $_POST["surat29"];
	$surat30	= $_POST["surat30"];
	$surat31	= $_POST["surat31"];
	$surat32	= $_POST["surat32"];
	$surat33	= $_POST["surat33"];
	$surat34	= $_POST["surat34"];
	$surat35	= $_POST["surat35"];
	$surat36	= $_POST["surat36"];
	$surat37	= $_POST["surat37"];
	$surat38	= $_POST["surat38"];
	$surat39	= $_POST["surat39"];
	$surat40	= $_POST["surat40"];
	$surat41	= $_POST["surat41"];
	$surat42	= $_POST["surat42"];
	$surat43	= $_POST["surat43"];
	$surat44	= $_POST["surat44"];
	$surat45	= $_POST["surat45"];
	$surat46	= $_POST["surat46"];
	$surat47	= $_POST["surat47"];
	$surat48	= $_POST["surat48"];
	$surat49	= $_POST["surat49"];
	$surat50	= $_POST["surat50"];
	$surat51	= $_POST["surat51"];
	$surat52	= $_POST["surat52"];
	$surat53	= $_POST["surat53"];
	$surat54	= $_POST["surat54"];
	$surat55	= $_POST["surat55"];
	$surat56	= $_POST["surat56"];
	$surat57	= $_POST["surat57"];
	$surat58	= $_POST["surat58"];
	$surat59	= $_POST["surat59"];
	$surat60	= $_POST["surat60"];
	$surat61	= $_POST["surat61"];
	$surat62	= $_POST["surat62"];
	$surat63	= $_POST["surat63"];
	$surat64	= $_POST["surat64"];
	$surat65	= $_POST["surat65"];
	$surat66	= $_POST["surat66"];
	$surat67	= $_POST["surat67"];
	$surat68	= $_POST["surat68"];
	$surat69	= $_POST["surat69"];
	$surat70	= $_POST["surat70"];

	$q  = "update  ERM_RI_RUJUK_RSLAIN set
	userid = '$user',tglentry='$tglinput',
	surat1	='$surat1',
	surat2	='$surat2',
	surat3	='$surat3',
	surat4	='$surat4',
	surat5	='$surat5',
	surat6	='$surat6',
	surat7	='$surat7',
	surat8	='$surat8',
	surat9	='$surat9',
	surat10	='$surat10',
	surat11	='$surat11',
	surat12	='$surat12',
	surat13	='$surat13',
	surat14	='$surat14',
	surat15	='$surat15',
	surat16	='$surat16',
	surat17	='$surat17',
	surat18	='$surat18',
	surat19	='$surat19',
	surat20	='$surat20',
	surat21	='$surat21',
	surat22	='$surat22',
	surat23	='$surat23',
	surat24	='$surat24',
	surat25	='$surat25',
	surat26	='$surat26',
	surat27	='$surat27',
	surat28	='$surat28',
	surat29	='$surat29',
	surat30	='$surat30',
	surat31	='$surat31',
	surat32	='$surat32',
	surat33	='$surat33',
	surat34	='$surat34',
	surat35	='$surat35',
	surat36	='$surat36',
	surat37	='$surat37',
	surat38	='$surat38',
	surat39	='$surat39',
	surat40	='$surat40',
	surat41	='$surat41',
	surat42	='$surat42',
	surat43	='$surat43',
	surat44	='$surat44',
	surat45	='$surat45',
	surat46	='$surat46',
	surat47	='$surat47',
	surat48	='$surat48',
	surat49	='$surat49',
	surat50	='$surat50',
	surat51	='$surat51',
	surat52	='$surat52',
	surat53	='$surat53',
	surat54	='$surat54',
	surat55	='$surat55',
	surat56	='$surat56',
	surat57	='$surat57',
	surat58	='$surat58',
	surat59	='$surat59',
	surat60	='$surat60',
	surat61	='$surat61',
	surat62	='$surat62',
	surat63	='$surat63',
	surat64	='$surat64',
	surat65	='$surat65',
	surat66	='$surat66',
	surat67	='$surat67',
	surat68	='$surat68',
	surat69	='$surat69',
	surat70	='$surat70'

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