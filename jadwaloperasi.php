<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$user2 = substr($user, 0,3);

$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);


$idjadwal = $row[2]; 


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

//ambil diregtujuan
$qt="SELECT        TOP (1) NOREG, URUT, REGTUJUAN
FROM            ARM_RegisterTujuan
WHERE        (NOREG = '$noreg')
ORDER BY TANGGAL DESC";
$hqt  = sqlsrv_query($conn, $qt);        
$dhqt  = sqlsrv_fetch_array($hqt, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($dhqt['REGTUJUAN']);


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


$qi="SELECT noreg FROM ERM_RI_LAPORAN_OK where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_LAPORAN_OK(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	if($idjadwal){
		$qe="
		SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
		FROM ERM_RI_LAPORAN_OK
		where id='$idjadwal'";
	}else{
		$qe="
		SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
		FROM ERM_RI_LAPORAN_OK
		where noreg='$noreg'";
	}
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
	$ok61= $de['ok61'];
	$ok62= $de['ok62'];
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
			$("#dokter3").autocomplete({
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
			$("#icd104").autocomplete({
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
			$("#icd103").autocomplete({
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

<!-- Link ke Bootstrap 5 CSS dan Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<style>
	body {
		background-color: #f4f6f9;
	}
	.form-container {
		background-color: #fff;
		padding: 30px;
		border-radius: 10px;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
	}
	.form-heading {
		font-size: 1.5rem;
		margin-bottom: 20px;
		text-align: center;
	}
	.form-label {
		font-weight: bold;
	}
	.form-control {
		border-radius: 8px;
		padding: 10px;
	}
	.form-group {
		margin-bottom: 20px;
	}
	.btn-custom {
		background-color: #66CDAA;
		color: white;
		border: none;
		padding: 10px 20px;
		border-radius: 8px;
	}
	.btn-custom:hover {
		background-color: #4cae91;
	}
</style>


<div class="container mt-5">
	<div class="form-container">

		<!-- Buttons Section -->
		<div class="d-flex justify-content-start mb-3">
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning me-2'><i class="bi bi-x-circle"></i> Close</a>
			<a href='' class='btn btn-success me-2'><i class="bi bi-arrow-clockwise"></i></a>
			<a href='jadwal_operasi_list.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-success me-2'><i class="bi bi-box-arrow-in-right"></i> Template</a>
			<a href='jadwaloperasi_print.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info me-2' target='_blank'><i class="bi bi-printer-fill"></i></a>
			<a href='' class='btn btn-light me-2'>Hal.1</a>
			<a href='jadwaloperasi2.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-success me-2'>Hal.2</a>
			<a href='jadwaloperasi3.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-success'>Hal.3</a>
		</div>

		<!-- Patient Information Card -->
		<div class="card mb-3">
			<div class="card-header bg-primary text-white">
				<h5><b><?php echo $nmrs; ?></b></h5>
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-6">
						<p><?php echo $alamat; ?></p>
					</div>
					<div class="col-6">
						<p>
							<?php echo 'NIK : '.$noktp.'<br>'; ?>
							<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
							<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien; ?>							
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Doctor's Menu Section -->
		<div class="row">
			<?php 
			if($role=='DOKTER'){
				include('menu_dokter.php');
			}
			?>
		</div>

		<div class="form-heading">
			<b>LAPORAN OPERASI</b>
		</div>
		<form method="POST" name="myForm" action="" enctype="multipart/form-data">
			<div class="form-group">
				<label class="form-label">Diagnosa pra pembedahan <i class="fas fa-notes-medical"></i></label>
				<div class="d-flex justify-content-between">
					<input class="form-control" name="ok1" value="<?php echo $ok1;?>" id="icd101" type="text" placeholder="Isikan ICD10" required>
					<input class="form-control w-25" name="ok2" value="<?php echo $ok2;?>" type="number" step="0.01" placeholder="Berat Badan" > kg
				</div>
			</div>

			<div class="form-group">
				<label class="form-label">Tindakan yang dilakukan <i class="fas fa-procedures"></i></label>
				<div class="d-flex flex-column">
					<input class="form-control mb-2" name="ok3" value="<?php echo $ok3;?>" id="icd91" type="text" placeholder="Isikan ICD9" >
					<input class="form-control mb-2" name="ok4" value="<?php echo $ok4;?>" id="icd92" type="text" placeholder="Isikan ICD9" >
					<input class="form-control mb-2" name="ok5" value="<?php echo $ok5;?>" id="icd93" type="text" placeholder="Isikan ICD9" >
					<input class="form-control mb-2" name="ok6" value="<?php echo $ok6;?>" id="icd94" type="text" placeholder="Isikan ICD9" >
					<input class="form-control" name="ok7" value="<?php echo $ok7;?>" type="text" placeholder="ICD Free Text" >
				</div>
			</div>

			<div class="form-group">
				<label class="form-label">Diagnosa pasca pembedahan <i class="fas fa-stethoscope"></i></label>
				<input class="form-control" name="ok8" value="<?php echo $ok8;?>" id="icd102" type="text" placeholder="Isikan ICD10" >
			</div>

			<div class="form-group">
				<label class="form-label">Diagnosa pasca pembedahan(2) <i class="fas fa-stethoscope"></i></label>
				<input class="form-control" name="ok61" value="<?php echo $ok61;?>" id="icd103" type="text" placeholder="Isikan ICD10" >
			</div>

			<div class="form-group">
				<label class="form-label">Diagnosa pasca pembedahan(3) <i class="fas fa-stethoscope"></i></label>
				<input class="form-control" name="ok62" value="<?php echo $ok62;?>" id="icd104" type="text" placeholder="Isikan ICD10" >
			</div>

<!-- 			<div class="form-group text-center">
				<button type="submit" name="simpan" class="btn btn-custom">
					<i class="fas fa-save"></i> Simpan
				</button>
			</div>
		-->
		<hr>

		<!-- Tanggal dan Lama Operasi -->
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Tgl Operasi <i class="fas fa-calendar-alt"></i></label>
			<div class="col-sm-3">
				<input type="text" name="ok9" value="<?php echo $ok9;?>" class="form-control" placeholder="Tanggal Operasi">
			</div>
			<label class="col-sm-3 col-form-label">Lama Operasi <i class="fas fa-clock"></i></label>
			<div class="col-sm-3">
				<input type="text" id="lama_operasi" name="ok10" value="<?php echo $ok10;?>" class="form-control" placeholder="Lama Operasi">
			</div>
		</div>

		<!-- Mulai dan Selesai Jam -->
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Mulai Jam <i class="fas fa-clock"></i></label>
			<div class="col-sm-3">
				<?php if(empty($ok1)){ ?>
					<input type="time" id="mulai_jam" name="ok11" value="<?php echo $ok11;?>" class="form-control" placeholder="Mulai Jam">
				<?php } else { ?>
					<input type="text" id="mulai_jam" name="ok11" value="<?php echo $ok11;?>" class="form-control" placeholder="Mulai Jam">
				<?php }?>
			</div>
			<label class="col-sm-3 col-form-label">Selesai Jam <i class="fas fa-clock"></i></label>
			<div class="col-sm-3">
				<?php if(empty($ok2)){ ?>
					<input type="time" id="selesai_jam" name="ok12" value="<?php echo $ok12;?>" class="form-control" placeholder="Selesai Jam">
				<?php } else { ?>
					<input type="text" id="selesai_jam" name="ok12" value="<?php echo $ok12;?>" class="form-control" placeholder="Selesai Jam">
				<?php }?>
			</div>
		</div>

		<script>
			function hitungLamaOperasi() {
				const mulai = document.getElementById('mulai_jam').value;
				const selesai = document.getElementById('selesai_jam').value;

				if (mulai && selesai) {
					const [jmMulai, mnMulai] = mulai.split(':').map(Number);
					const [jmSelesai, mnSelesai] = selesai.split(':').map(Number);

					const mulaiMenit = jmMulai * 60 + mnMulai;
					const selesaiMenit = jmSelesai * 60 + mnSelesai;

					let selisih = selesaiMenit - mulaiMenit;

        // Jika selesai lebih kecil dari mulai, dianggap lewat tengah malam
        if (selisih < 0) {
        	selisih += 24 * 60;
        }

        const jam = Math.floor(selisih / 60);
        const menit = selisih % 60;

        document.getElementById('lama_operasi').value = `${jam} jam ${menit} menit`;
    }
}

document.getElementById('mulai_jam').addEventListener('change', hitungLamaOperasi);
document.getElementById('selesai_jam').addEventListener('change', hitungLamaOperasi);
</script>


<!-- Dokter Operator, Asisten, Instrumen, Petugas on Steril -->
<div class="form-group row">
	<label class="col-sm-3 col-form-label">Dokter Operator <i class="fas fa-user-md"></i></label>
	<div class="col-sm-3">
		<input type="text" name="ok42" value="<?php echo $ok42;?>" id="dokter2" class="form-control" placeholder="Nama Dokter" >
	</div>
	<label class="col-sm-3 col-form-label">Asisten <i class="fas fa-user-nurse"></i></label>
	<div class="col-sm-3">
		<input type="text" name="ok43" value="<?php echo $ok43;?>" id="karyawan1" class="form-control" placeholder="Nama Asisten" >
	</div>
</div>

<div class="form-group row">
	<label class="col-sm-3 col-form-label">Instrumen <i class="fas fa-tools"></i></label>
	<div class="col-sm-3">
		<input type="text" name="ok44" value="<?php echo $ok44;?>" id="karyawan2" class="form-control" placeholder="Nama Instrumen" >
	</div>
	<label class="col-sm-3 col-form-label">Petugas on Steril <i class="fas fa-user-shield"></i></label>
	<div class="col-sm-3">
		<input type="text" name="ok45" value="<?php echo $ok45;?>" id="karyawan3" class="form-control" placeholder="Nama Petugas" >
	</div>
</div>

<!-- Tombol Simpan -->
<!-- 			<div class="form-group text-center">
				<button type="submit" name="simpan" class="btn btn-custom">
					<i class="fas fa-save"></i> Simpan
				</button>
			</div>
		-->
		<hr>
		<!-- Jumlah Perdarahan dan Transfusi -->
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Jumlah Perdarahan <i class="fas fa-tint"></i></label>
			<div class="col-sm-9">
				<input type="text" name="ok13" value="<?php echo $ok13;?>" class="form-control" placeholder="Jumlah Perdarahan" >
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Transfusi selama operasi</label>
			<div class="col-sm-9">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="ok46" value="TIDAK" <?php if ($ok46=="TIDAK"){echo "checked";}?>>
					<label class="form-check-label">TIDAK</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="ok46" value="YA" <?php if ($ok46=="YA"){echo "checked";}?>>
					<label class="form-check-label">YA</label>
				</div>
				<input type="text" name="ok48" value="<?php echo $ok48;?>" class="form-control d-inline-block" style="width: 80px;" placeholder="CC">
			</div>
		</div>

		<!-- Pemeriksaan Jaringan -->
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Pemeriksaan jaringan <i class="fas fa-cogs"></i></label>
			<div class="col-sm-9">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="ok47" value="TIDAK" <?php if ($ok47=="TIDAK"){echo "checked";}?>>
					<label class="form-check-label">TIDAK</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="ok47" value="YA" <?php if ($ok47=="YA"){echo "checked";}?>>
					<label class="form-check-label">YA</label>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Macam jaringan</label>
			<div class="col-sm-9">
				<input type="text" name="ok14" value="<?php echo $ok14;?>" class="form-control" placeholder="Macam Jaringan" >
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Jenis pemeriksaan</label>
			<div class="col-sm-9">
				<?php 
				if($ok15){
					echo "<input type='text' name='ok15' value='$ok15' class='form-control' placeholder='Jenis Pemeriksaan'>";
				}else{
					echo "
					<select name='ok15' class='form-control' style='min-height:50px;''>
					<option value=''>--pilih--</option>
					<option value='PA'>PA</option>
					<option value='Kultur Jaringan'>Kultur Jaringan</option>
					<option value='Kultur Pus'>Kultur Pus</option>
					<option value='Vries Coup'>Vries Coup</option>
					</select>
					";
				}
				?>
			</div>
		</div>

		<!-- Jenis Anestesi dan Waktu -->
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Jenis Anestesi <i class="fas fa-bed"></i></label>
			<div class="col-sm-9">
				<?php 
				if($ok37){
					echo "<input type='text' name='ok37' value='$ok37' class='form-control' placeholder='Jenis Anestesi'>";
				}else{
					echo "
					<select name='ok37' class='form-control'>
					<option value=''>--pilih--</option>
					<option value='General Anastesi'>General Anastesi</option>
					<option value='GA - Intubasi'>GA - Intubasi</option>
					<option value='GA - LMA'>GA - LMA</option>
					<option value='GA - Masker'>GA - Masker</option>
					<option value='GA - TIVA'>GA - TIVA</option>
					<option value='Regional Anastesi'>Regional Anastesi</option>
					<option value='RA - Spinal'>RA - Spinal</option>
					<option value='RA - Epidural'>RA - Epidural</option>
					<option value='RA - Blok Perifer'>RA - Blok Perifer</option>
					<option value='Lokal Anastesi'>Lokal Anastesi</option>
					</select>
					";
				}
				?>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Mulai Jam</label>
			<div class="col-sm-3">
				<input type="text" name="ok38" value="<?php echo $ok38;?>" class="form-control" placeholder="Mulai Jam">
			</div>
			<label class="col-sm-3 col-form-label">Selesai Jam</label>
			<div class="col-sm-3">
				<input type="text" name="ok39" value="<?php echo $ok39;?>" class="form-control" placeholder="Selesai Jam">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Dokter Anastesi <i class="fas fa-user-md"></i></label>
			<div class="col-sm-9">
				<input type="text" name="ok50" value="<?php echo $ok50;?>" id="dokter3" class="form-control" placeholder="Nama Dokter Anastesi">
			</div>
		</div>

		<!-- Obat Anestesi -->
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">Obat Anestesi <i class="fas fa-pills"></i></label>
			<div class="col-sm-9">
				<input type="text" name="ok40" value="<?php echo $ok40;?>" class="form-control" placeholder="Nama Obat Anestesi" style="width: 100%;">
			</div>
		</div>

		<div class="card mb-3">
			<div class="card-header">
				<strong>Golongan Operasi, Macam Operasi, Urgensi Operasi</strong>
			</div>
			<div class="card-body">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label">Golongan Operasi</label>
					<label class="col-sm-4 col-form-label">Macam Operasi</label>
					<label class="col-sm-4 col-form-label">Urgensi Operasi</label>
				</div>
				<div class="form-group row">
					<div class="col-sm-4">
						<input type="checkbox" name="ok16" value="YA" <?php if ($ok16=="YA"){echo "checked";}?> > Poliklinik<br>
						<input type="checkbox" name="ok17" value="YA" <?php if ($ok17=="YA"){echo "checked";}?> > Kecil<br>
						<input type="checkbox" name="ok18" value="YA" <?php if ($ok18=="YA"){echo "checked";}?> > Sedang<br>
						<input type="checkbox" name="ok19" value="YA" <?php if ($ok19=="YA"){echo "checked";}?> > Besar<br>
						<input type="checkbox" name="ok20" value="YA" <?php if ($ok20=="YA"){echo "checked";}?> > Besar Khusus<br>
						<input type="checkbox" name="ok49" value="YA" <?php if ($ok49=="YA"){echo "checked";}?> > Khusus<br>
					</div>
					<div class="col-sm-4">
						<input type="checkbox" name="ok21" value="YA" <?php if ($ok21=="YA"){echo "checked";}?> > Bersih<br>
						<input type="checkbox" name="ok22" value="YA" <?php if ($ok22=="YA"){echo "checked";}?> > Bersih Terkontaminasi<br>
						<input type="checkbox" name="ok23" value="YA" <?php if ($ok23=="YA"){echo "checked";}?> > Kontaminasi<br>
						<input type="checkbox" name="ok24" value="YA" <?php if ($ok24=="YA"){echo "checked";}?> > Kotor<br>
					</div>
					<div class="col-sm-4">
						<input type="checkbox" name="ok25" value="YA" <?php if ($ok25=="YA"){echo "checked";}?> > Darurat<br>
						<input type="checkbox" name="ok26" value="YA" <?php if ($ok26=="YA"){echo "checked";}?> > Urgen<br>
						<input type="checkbox" name="ok27" value="YA" <?php if ($ok27=="YA"){echo "checked";}?> > Elektif<br>
					</div>
				</div>
			</div>
		</div>

		<!-- Golongan Operasi, Macam Operasi, Urgensi Operasi -->
		<div class="form-group row">
			<div class="col-sm-4">
				<label class="col-form-label"><i class="fas fa-hospital"></i> Kamar Operasi No :</label>
				<input type="text" name="ok28" value="<?php echo $ok28;?>" size="20" class="form-control">
			</div>
			<div class="col-sm-4">
				<label class="col-form-label"><i class="fas fa-sync-alt"></i> Ronde Ke :</label>
				<input type="text" name="ok29" value="<?php echo $ok29;?>" size="20" class="form-control">
			</div>
			<div class="col-sm-4">
				<label class="col-form-label"><i class="fas fa-calculator"></i> Hasil Perhitungan Kasa :</label>
				<input type="text" name="ok30" value="<?php echo $ok30;?>" size="20" class="form-control">
			</div>
		</div>

		<!-- Penjelasan Teknik Operasi dan Rincian Temuan -->
		<div class="form-group row">
			<div class="col-sm-12">
				<label class="col-form-label"><i class="fas fa-file-alt"></i> Penjelasan Teknik Operasi dan Rincian Temuan:</label>
				<textarea name="ok31" class="form-control" style="min-width:950px; min-height:300px;"><?php echo $ok31;?></textarea>
			</div>
		</div>

		<!-- Komplikasi -->
		<div class="form-group row">
			<div class="col-sm-12">
				<label class="col-form-label"><i class="fas fa-exclamation-triangle"></i> Komplikasi :</label>
				<input type="text" name="ok32" value="<?php echo $ok32;?>" size="100" class="form-control">
			</div>
		</div>

		<!-- Implan -->
		<div class="form-group row">
			<div class="col-sm-12">
				<label class="col-form-label"><i class="fas fa-implant"></i> Implan :</label>
				<div>
					<input type="checkbox" name="ok33" value="YA" <?php if ($ok33=="YA"){echo "checked";}?> > Tidak 
					<input type="checkbox" name="ok34" value="YA" <?php if ($ok34=="YA"){echo "checked";}?> > Ya, sebutkan:
				</div>
				<textarea name="ok35" class="form-control" style="min-width:950px; min-height:100px;"><?php echo $ok35;?></textarea>
			</div>
		</div>

		<!-- Verifikasi Petugas Kamar Operasi -->
		<div class="form-group row">
			<div class="col-sm-4 bg-primary text-white p-2">
				<i class="bi bi-person-check"></i> Verifikasi Petugas Kamar Operasi
			</div>
			<div class="col-sm-8">
				<input class="form-control" name="ok36" value="<?php echo $ok36;?>" id="dokter" type="text" size="50" placeholder="Isikan Nama Dokter atau Kode Dokter">
			</div>
		</div>

		<!-- Tombol Simpan -->
		<div class="form-group text-center">
			<button type="submit" name="simpan" class="btn btn-info">
				<i class="fas fa-save"></i> Simpan
			</button>
		</div>

		<!-- Link ke Anatomi Tubuh -->
		<div class="form-group row">
			<div class="col-sm-12">
				<a href="http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/<?php echo $KODEUNIT.'/'.$noreg.'/'.$norm.'/'.$user2 ?>" class="btn btn-success" target="_blank">
					<i class="bi bi-arrow-right-circle"></i> &nbsp; Anatomi Tubuh
				</a>
			</div>
		</div>


		<!-- Card untuk Upload Gambar -->
		<div class="card">
			<div class="card-header bg-primary text-white">
				<i class="fas fa-upload"></i> Upload Gambar
			</div>
			<div class="card-body">
				<div class="form-group row">
					<div class="col-sm-12">
						<label class="col-form-label">Upload Gambar :</label>
						<?php 
						$q = "select doc as doc from ERM_RI_LAPORAN_OK where noreg = '$noreg'";
						$h = sqlsrv_query($conn, $q);              
						$d = sqlsrv_fetch_array($h, SQLSRV_FETCH_ASSOC); 
						$doc = trim($d['doc']);
						echo "Gambar terupload : <a href='$doc' target='_blank'>$doc</a>";
						?>
						<input type="file" name="doc" class="form-control">
						<small class="form-text text-muted">Ekstensi .jpeg | .jpg</small>
					</div>
				</div>
			</div>
			<div class="card-footer text-center">
				<input type="submit" name="upload" value="Upload" class="btn btn-success">
			</div>
		</div>

	</form>
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

	$ok1 = str_replace("'","`",$ok1);
	$ok8 = str_replace("'","`",$ok8);

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
	$ok31 = str_replace("'","`",$ok31);
	
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
	$ok61	= $_POST["ok61"];
	$ok62	= $_POST["ok62"];

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
	ok60	='$ok60',ok61	='$ok61',ok62	='$ok62'
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
	alert('".$eror."');
	history.go(-1);
	</script>
	";


}

?>