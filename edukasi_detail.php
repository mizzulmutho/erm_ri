<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

if($tgli){
	$tgli = date('Y-m-d\TH:i:s', strtotime($tgl)); 
}else{
	$tgli = date('Y-m-d\TH:i:s', strtotime($tglinput)); 
}


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


$qi="SELECT noreg FROM ERM_RI_EDUKASI_HEADER where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_EDUKASI_HEADER(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_EDUKASI_HEADER
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$ed1 = $de['ed1'];
	$ed2= $de['ed2'];
	$ed3= $de['ed3'];
	$ed4= $de['ed4'];
	$ed5= $de['ed5'];
	$ed6= $de['ed6'];
	$ed7= $de['ed7'];
	$ed8= $de['ed8'];
	$ed9= $de['ed9'];
	$ed10= $de['ed10'];
	$ed11= $de['ed11'];
	$ed12= $de['ed12'];
	$ed13= $de['ed13'];
	$ed14= $de['ed14'];
	$ed15= $de['ed15'];
	$ed16= $de['ed16'];
	$ed17= $de['ed17'];
	$ed18= $de['ed18'];
	$ed19= $de['ed19'];
	$ed20= $de['ed20'];
	$ed21= $de['ed21'];
	$ed22= $de['ed22'];
	$ed23= $de['ed23'];
	$ed24= $de['ed24'];
	$ed25= $de['ed25'];
	$ed26= $de['ed26'];
	$ed27= $de['ed27'];
	$ed28= $de['ed28'];
	$ed29= $de['ed29'];
	$ed30= $de['ed30'];
	// $ed31= $de['ed31'];
	// $ed32= $de['ed32'];
	// $ed33= $de['ed33'];
	// $ed34= $de['ed34'];
	// $ed35= $de['ed35'];
	// $ed36= $de['ed36'];
	// $ed37= $de['ed37'];
	// $ed38= $de['ed38'];
	// $ed39= $de['ed39'];
	// $ed40= $de['ed40'];
	// $ed41= $de['ed41'];
	// $ed42= $de['ed42'];
	// $ed43= $de['ed43'];
	// $ed44= $de['ed44'];
	// $ed45= $de['ed45'];
	// $ed46= $de['ed46'];
	// $ed47= $de['ed47'];
	// $ed48= $de['ed48'];
	// $ed49= $de['ed49'];
	// $ed50= $de['ed50'];
	// $ed51= $de['ed51'];
	// $ed52= $de['ed52'];
	// $ed53= $de['ed53'];
	// $ed54= $de['ed54'];
	// $ed55= $de['ed55'];
	// $ed56= $de['ed56'];
	// $ed57= $de['ed57'];
	// $ed58= $de['ed58'];
	// $ed59= $de['ed59'];
	// $ed60= $de['ed60'];



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
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='edukasi.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
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
					<b>FORMULIR EDUKASI PASIEN DAN KELUARGA TERINTEGRASI</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; PPA
							</div>
							<div class="col-8">
								: 
								<input type='radio' name='ppa' value='dokter'>Dokter
								<input type='radio' name='ppa' value='perawat'>Perawat
								<input type='radio' name='ppa' value='bidan'>Bidan
								<input type='radio' name='ppa' value='apoteker'>Apoteker
								<input type='radio' name='ppa' value='gizi'>Gizi
								<input type='radio' name='ppa' value='tppri'>TPPRI
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Tanggal
							</div>
							<div class="col-8">
								: <input class="" name="tglinput" value="<?php echo $tgl;?>" id="" type="datetime-local" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Materi
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="materi" value="<?php echo $materi;?>" id="obat" type="text" size='50' onfocus="nextfield ='';" placeholder=""> -->
								<textarea name= "materi" id="" style="min-width:650px; min-height:80px;"><?php echo $materi;?></textarea>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Durasi
							</div>
							<div class="col-8">
								: <input class="" name="durasi" value="<?php echo $durasi;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Metode
							</div>
							<div class="col-8">
								: <input class="" name="metode" value="<?php echo $metode;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Evaluasi
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="evaluasi" value="<?php echo $evaluasi;?>" id="" type="text" size='50' onfocus="nextfield ='simpan';" placeholder=""> -->
								<textarea name= "evaluasi" id="" style="min-width:650px; min-height:80px;"><?php echo $evaluasi;?></textarea>
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
								&nbsp;&nbsp;<input type='submit' name='simpan_detail' value='simpan_detail' onfocus="nextfield ='done';" style="color: white;background: #66CDAA;border-color: #66CDAA;">
							</div>
						</div>
					</td>
				</tr>	
			</table>
			<br>				
			<table width="100%">
				<tr>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>ppa</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>materi</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>durasi</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>metode</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>evaluasi</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>tgl input</font></td>
					<td style="border: 1px solid;" bgcolor='#708090' align='center'><font color='white'>edukator</font></td>
					<td style="border: 1px solid;" bgcolor='#708090' align='center'><font color='white'>del</font></td>
				</tr>
				<?php 
				$q="
				select TOP(100) userid,materi,durasi,metode,evaluasi,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
				from ERM_RI_EDUKASI_DETAIL
				where noreg='$noreg' order by id desc
				";
				$hasil  = sqlsrv_query($conn, $q);  
				$no=1;
				while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
					echo "
					<tr>
					<td>$no</td>
					<td>$data[ppa]</td>
					<td>$data[materi]</td>
					<td>$data[durasi]</td>
					<td>$data[metode]</td>
					<td>$data[evaluasi]</td>
					<td>$data[tglentry]</td>
					<td>$data[userid] - $nama</td>
					<td align='center'><a href='del_edukasi.php?id=$id|$user|$data[id]'><font color='red'>[x]</font></a></td>
					</tr>
					";
					$no += 1;

				}


				?>
			</table>


			<br>
			<br>
			<br>
		</form>
	</font>
</body>
</div>
</div>

<?php 
if (isset($_POST["simpan_detail"])) {

	$lanjut = 'Y';
	$tglin	= $_POST["tglinput"];
	$ppa	= $_POST["ppa"];
	$materi	= $_POST["materi"];
	$durasi	= $_POST["durasi"];
	$metode	= $_POST["metode"];
	$evaluasi	= $_POST["evaluasi"];
	$tgl3 = date('Y-m-d H:i:s', strtotime($tglin)); 

	if(empty($ppa)){
		$eror = 'PPA tidak boleh kosong';
		$lanjut = 'T';
	}

	if($lanjut=='Y'){
		echo $q  = "insert into ERM_RI_EDUKASI_DETAIL(noreg,ppa,userid,tglentry,tgl,materi,durasi,metode,evaluasi) 
		values ('$noreg','$ppa','$user','$tglinput','$tgl3','$materi','$durasi','$metode','$evaluasi')";
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			$eror = "Success";
		}else{
			$eror = "Gagal Insert";

		}

	}

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";

}


if (isset($_POST["simpan"])) {

	$ed1	= $_POST["ed1"];
	$ed2	= $_POST["ed2"];
	$ed3	= $_POST["ed3"];
	$ed4	= $_POST["ed4"];
	$ed5	= $_POST["ed5"];
	$ed6	= $_POST["ed6"];
	$ed7	= $_POST["ed7"];
	$ed8	= $_POST["ed8"];
	$ed9	= $_POST["ed9"];
	$ed10	= $_POST["ed10"];
	$ed11	= $_POST["ed11"];
	$ed12	= $_POST["ed12"];
	$ed13	= $_POST["ed13"];
	$ed14	= $_POST["ed14"];
	$ed15	= $_POST["ed15"];
	$ed16	= $_POST["ed16"];
	$ed17	= $_POST["ed17"];
	$ed18	= $_POST["ed18"];
	$ed19	= $_POST["ed19"];
	$ed20	= $_POST["ed20"];
	$ed21	= $_POST["ed21"];
	$ed22	= $_POST["ed22"];
	$ed23	= $_POST["ed23"];
	$ed24	= $_POST["ed24"];
	$ed25	= $_POST["ed25"];
	$ed26	= $_POST["ed26"];
	$ed27	= $_POST["ed27"];
	$ed28	= $_POST["ed28"];
	$ed29	= $_POST["ed29"];
	$ed30	= $_POST["ed30"];

	$q  = "update ERM_RI_EDUKASI_HEADER set
	ed1	='$ed1',
	ed2	='$ed2',
	ed3	='$ed3',
	ed4	='$ed4',
	ed5	='$ed5',
	ed6	='$ed6',
	ed7	='$ed7',
	ed8	='$ed8',
	ed9	='$ed9',
	ed10	='$ed10',
	ed11	='$ed11',
	ed12	='$ed12',
	ed13	='$ed13',
	ed14	='$ed14',
	ed15	='$ed15',
	ed16	='$ed16',
	ed17	='$ed17',
	ed18	='$ed18',
	ed19	='$ed19',
	ed20	='$ed20',
	ed21	='$ed21',
	ed22	='$ed22',
	ed23	='$ed23',
	ed24	='$ed24',
	ed25	='$ed25',
	ed26	='$ed26',
	ed27	='$ed27',
	ed28	='$ed28',
	ed29	='$ed29',
	ed30	='$ed30'
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