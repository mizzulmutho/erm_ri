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


$qi="SELECT noreg FROM ERM_RI_ROBSON where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_ROBSON(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_ROBSON
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$rob1 = $de['rob1'];
	$rob2= $de['rob2'];
	$rob3= $de['rob3'];
	$rob4= $de['rob4'];
	$rob5= $de['rob5'];
	$rob6= $de['rob6'];
	$rob7= $de['rob7'];
	$rob8= $de['rob8'];
	$rob9= $de['rob9'];
	$rob10= $de['rob10'];
	$rob11= $de['rob11'];
	$rob12= $de['rob12'];
	$rob13= $de['rob13'];
	$rob14= $de['rob14'];
	$rob15= $de['rob15'];
	$rob16= $de['rob16'];
	$rob17= $de['rob17'];
	$rob18= $de['rob18'];
	$rob19= $de['rob19'];
	$rob20= $de['rob20'];
	$rob21= $de['rob21'];
	$rob22= $de['rob22'];
	$rob23= $de['rob23'];
	$rob24= $de['rob24'];
	$rob25= $de['rob25'];
	$rob26= $de['rob26'];
	$rob27= $de['rob27'];
	$rob28= $de['rob28'];
	$rob29= $de['rob29'];
	$rob30= $de['rob30'];
	$rob31= $de['rob31'];
	$rob32= $de['rob32'];
	$rob33= $de['rob33'];
	$rob34= $de['rob34'];
	$rob35= $de['rob35'];
	$rob36= $de['rob36'];
	$rob37= $de['rob37'];
	$rob38= $de['rob38'];
	$rob39= $de['rob39'];
	$rob40= $de['rob40'];
	$rob41= $de['rob41'];
	$rob42= $de['rob42'];
	$rob43= $de['rob43'];
	$rob44= $de['rob44'];
	$rob45= $de['rob45'];
	$rob46= $de['rob46'];
	$rob47= $de['rob47'];
	$rob48= $de['rob48'];
	$rob49= $de['rob49'];
	$rob50= $de['rob50'];
	$rob51= $de['rob51'];
	$rob52= $de['rob52'];
	$rob53= $de['rob53'];
	$rob54= $de['rob54'];
	$rob55= $de['rob55'];
	$rob56= $de['rob56'];
	$rob57= $de['rob57'];
	$rob58= $de['rob58'];
	$rob59= $de['rob59'];
	$rob60= $de['rob60'];
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
					<b>KLASIFIKASI ROBSON  </b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>
				<tr>
					<td colspan="2" align="center" bgcolor="#FFE4C4"><font size='3px'><b>VARIABLE OBSTETRI</b></font></td>
					<td align="center" bgcolor="#FFE4C4"><font size='3px'><b>KETERANGAN</b></font></td>
				</tr>

				<tr>
					<td rowspan="2" align="center">Paritas</td>
					<td>Nullipara</td>
					<td>
						<input type='checkbox' name='rob1' value='Ya' <?php if ($rob1=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Multipara</td>
					<td>
						<input type='checkbox' name='rob2' value='Ya' <?php if ($rob2=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>

				<tr>
					<td rowspan="2" align="center">Riyawat SC</td>
					<td>Ya (1 atau lebih)</td>
					<td>
						<input type='checkbox' name='rob3' value='Ya' <?php if ($rob3=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Tidak</td>
					<td>
						<input type='checkbox' name='rob4' value='Ya' <?php if ($rob4=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>

				<tr>
					<td rowspan="3" align="center">Rencana Persalinan</td>
					<td>Spontan</td>
					<td>
						<input type='checkbox' name='rob5' value='Ya' <?php if ($rob5=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>induksi</td>
					<td>
						<input type='checkbox' name='rob6' value='Ya' <?php if ($rob6=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Elektif SC</td>
					<td>
						<input type='checkbox' name='rob7' value='Ya' <?php if ($rob7=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>


				<tr>
					<td rowspan="2" align="center">Riyawat SC</td>
					<td>Ya (1 atau lebih)</td>
					<td>
						<input type='checkbox' name='rob3' value='Ya' <?php if ($rob3=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Tidak</td>
					<td>
						<input type='checkbox' name='rob4' value='Ya' <?php if ($rob4=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>

				<tr>
					<td rowspan="2" align="center">Jumlah Janin</td>
					<td>Tunggal</td>
					<td>
						<input type='checkbox' name='rob5' value='Ya' <?php if ($rob5=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Kembar</td>
					<td>
						<input type='checkbox' name='rob6' value='Ya' <?php if ($rob6=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>

				<tr>
					<td rowspan="2" align="center">Umur Kehamilan</td>
					<td>Preterm (< 37 mgg)</td>
					<td>
						<input type='checkbox' name='rob7' value='Ya' <?php if ($rob7=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Aterm (37 mgg atau lebih)</td>
					<td>
						<input type='checkbox' name='rob8' value='Ya' <?php if ($rob8=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>

				<tr>
					<td rowspan="3" align="center">Pretensi Janin</td>
					<td>Letak Kepala</td>
					<td>
						<input type='checkbox' name='rob9' value='Ya' <?php if ($rob9=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Letak Sungsang</td>
					<td>
						<input type='checkbox' name='rob10' value='Ya' <?php if ($rob10=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>
				<tr>
					<td>Letak Lintang</td>
					<td>
						<input type='checkbox' name='rob11' value='Ya' <?php if ($rob11=="Ya"){echo "checked";}?> >Ya
					</td>
				</tr>

				<tr>
					<td colspan="2" align="center" bgcolor="#FFE4C4">Kategori Group Robson</td>
					<td align="left" bgcolor="#FFE4C4">
						<input class="" name="rob12" value="<?php echo $rob12;?>" id="" type="text" size='50' placeholder="">
					</td>
				</tr>

				<tr>
					<td colspan="2" align="center" bgcolor="#FFE4C4">Tindakan Persalinan</td>
					<td align="left" bgcolor="#FFE4C4">
						<input class="" name="rob13" value="<?php echo $rob13;?>" id="" type="text" size='50' placeholder="">
					</td>
				</tr>


				<tr>
					<td colspan="3" align="center">
						<br>
						<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
						<br>
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

	$rob1	= $_POST["rob1"];
	$rob2	= $_POST["rob2"];
	$rob3	= $_POST["rob3"];
	$rob4	= $_POST["rob4"];
	$rob5	= $_POST["rob5"];
	$rob6	= $_POST["rob6"];
	$rob7	= $_POST["rob7"];
	$rob8	= $_POST["rob8"];
	$rob9	= $_POST["rob9"];
	$rob10	= $_POST["rob10"];
	$rob11	= $_POST["rob11"];
	$rob12	= $_POST["rob12"];
	$rob13	= $_POST["rob13"];
	$rob14	= $_POST["rob14"];
	$rob15	= $_POST["rob15"];
	$rob16	= $_POST["rob16"];
	$rob17	= $_POST["rob17"];
	$rob18	= $_POST["rob18"];
	$rob19	= $_POST["rob19"];
	$rob20	= $_POST["rob20"];
	$rob21	= $_POST["rob21"];
	$rob22	= $_POST["rob22"];
	$rob23	= $_POST["rob23"];
	$rob24	= $_POST["rob24"];
	$rob25	= $_POST["rob25"];
	$rob26	= $_POST["rob26"];
	$rob27	= $_POST["rob27"];
	$rob28	= $_POST["rob28"];
	$rob29	= $_POST["rob29"];
	$rob30	= $_POST["rob30"];
	$rob31	= $_POST["rob31"];
	$rob32	= $_POST["rob32"];
	$rob33	= $_POST["rob33"];
	$rob34	= $_POST["rob34"];
	$rob35	= $_POST["rob35"];
	$rob36	= $_POST["rob36"];
	$rob37	= $_POST["rob37"];
	$rob38	= $_POST["rob38"];
	$rob39	= $_POST["rob39"];
	$rob40	= $_POST["rob40"];
	$rob41	= $_POST["rob41"];
	$rob42	= $_POST["rob42"];
	$rob43	= $_POST["rob43"];
	$rob44	= $_POST["rob44"];
	$rob45	= $_POST["rob45"];
	$rob46	= $_POST["rob46"];
	$rob47	= $_POST["rob47"];
	$rob48	= $_POST["rob48"];
	$rob49	= $_POST["rob49"];
	$rob50	= $_POST["rob50"];
	$rob51	= $_POST["rob51"];
	$rob52	= $_POST["rob52"];
	$rob53	= $_POST["rob53"];
	$rob54	= $_POST["rob54"];
	$rob55	= $_POST["rob55"];
	$rob56	= $_POST["rob56"];
	$rob57	= $_POST["rob57"];
	$rob58	= $_POST["rob58"];
	$rob59	= $_POST["rob59"];
	$rob60	= $_POST["ok60"];

	$q  = "update ERM_RI_ROBSON set
	rob1	='$rob1',
	rob2	='$rob2',
	rob3	='$rob3',
	rob4	='$rob4',
	rob5	='$rob5',
	rob6	='$rob6',
	rob7	='$rob7',
	rob8	='$rob8',
	rob9	='$rob9',
	rob10	='$rob10',
	rob11	='$rob11',
	rob12	='$rob12',
	rob13	='$rob13',
	rob14	='$rob14',
	rob15	='$rob15',
	rob16	='$rob16',
	rob17	='$rob17',
	rob18	='$rob18',
	rob19	='$rob19',
	rob20	='$rob20',
	rob21	='$rob21',
	rob22	='$rob22',
	rob23	='$rob23',
	rob24	='$rob24',
	rob25	='$rob25',
	rob26	='$rob26',
	rob27	='$rob27',
	rob28	='$rob28',
	rob29	='$rob29',
	rob30	='$rob30',
	rob31	='$rob31',
	rob32	='$rob32',
	rob33	='$rob33',
	rob34	='$rob34',
	rob35	='$rob35',
	rob36	='$rob36',
	rob37	='$rob37',
	rob38	='$rob38',
	rob39	='$rob39',
	rob40	='$rob40',
	rob41	='$rob41',
	rob42	='$rob42',
	rob43	='$rob43',
	rob44	='$rob44',
	rob45	='$rob45',
	rob46	='$rob46',
	rob47	='$rob47',
	rob48	='$rob48',
	rob49	='$rob49',
	rob50	='$rob50',
	rob51	='$rob51',
	rob52	='$rob52',
	rob53	='$rob53',
	rob54	='$rob54',
	rob55	='$rob55',
	rob56	='$rob56',
	rob57	='$rob57',
	rob58	='$rob58',
	rob59	='$rob59',
	rob60	='$rob60'
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