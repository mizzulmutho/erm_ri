<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl2		= gmdate("d/m/Y", time()+60*60*7);
$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);

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

$qss="
SELECT        TOP (100) CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 25) AS tglmasuk, ERM_ASSESMEN_HEADER.norm, AFarm_MstPasien.NAMA, AFarm_MstPasien.NOKTP, ERM_ASSESMEN_HEADER.kodedokter, ERM_ASSESMEN_HEADER.noreg,ERM_ASSESMEN_HEADER.userid,
Afarm_DOKTER_1.NAMA AS NAMADOKTER
FROM            ERM_ASSESMEN_HEADER INNER JOIN
AFarm_MstPasien ON ERM_ASSESMEN_HEADER.norm = AFarm_MstPasien.NORM INNER JOIN
Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER INNER JOIN
Afarm_DOKTER AS Afarm_DOKTER_1 ON Afarm_DOKTER.KODEDOKTER = Afarm_DOKTER_1.KODEDOKTER
WHERE  ERM_ASSESMEN_HEADER.NOREG='$noreg'
";
$hqss  = sqlsrv_query($conn, $qss);        
$dhqss  = sqlsrv_fetch_array($hqss, SQLSRV_FETCH_ASSOC); 
$kodedokter = trim($dhqss['kodedokter']);
$tglawal = trim($dhqss['tglmasuk']);


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
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<a href='generalconsent2.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info'><i class="bi bi-info-circle"></i> From HPK</a>
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
							<b>General Consent / Persetujuan Umum  </b><br>
							<?php 
							$qe       = "SELECT      IDENCOUNTERSS from  ARM_REGISTER where noreg='$noreg'";
							$hasile  = sqlsrv_query($conn, $qe);                
							$datae    = sqlsrv_fetch_array($hasile, SQLSRV_FETCH_ASSOC);  
							$c_encontered = $datae[IDENCOUNTERSS];  

							if(empty($c_encontered)){
								$encontered = 'Kunjungan Rawat Inap Belum dikirimkan ke Satu Sehat';
							}else{
								$encontered = 'Kunjungan Rawat Inap Success dikirimkan ke Satu Sehat - '.$datae[IDENCOUNTERSS];
							}

							echo $encontered; 

							?>
						</div>
					</div>

					<br>

					<?php 
					if(empty($kodedokter)){
						echo "DPJP masih Kosong isi terlebih dahulu DPJP !!!";
						echo "<br>";
						echo "<input name='dpjp' value='' id='dokter' type='text' size='50' placeholder='Isikan Nama Dokter atau Kode Dokter'>";
						echo "<input type='submit' name='simpan_dpjp' value='simpan_dpjp' style='color: white;background: #66CDAA;border-color: #66CDAA;'>";
				// exit();
					}else{
						?>
						<table width='100%' border='0'>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											<font size='3'><b>Persetujuan Pasien</b></font>
										</div>
										<div class="col-8">
											: <input type='radio' name='gc1' value='Ya' <?php if ($gc1=="Ya"){echo "checked";}?> >Ya
											<input type='radio' name='gc1' value='Tidak' <?php if ($gc1=="Tidak"){echo "checked";}?> >Tidak								
										</div>
									</div>
								</td>
							</tr>	
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Informasi Ketentuan Pembayaran
										</div>
										<div class="col-8">
											: <input type='radio' name='gc2' value='Setuju' <?php if ($gc2=="Setuju"){echo "checked";}?> >Setuju
											<input type='radio' name='gc2' value='Tidak Setuju' <?php if ($gc2=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Informasi Jenis Pembayaran
										</div>
										<div class="col-8">
											: <input type='radio' name='gc18' value='umum' <?php if ($gc18=="umum"){echo "checked";}?> >umum
											<input type='radio' name='gc18' value='bpjs' <?php if ($gc18=="bpjs"){echo "checked";}?> >bpjs
											<input type='radio' name='gc18' value='asuransi lain' <?php if ($gc18=="asuransi lain"){echo "checked";}?> >asuransi lain
											keterangan : 
											<input class="" name="gc19" value="<?php echo $gc19;?>" id="" type="text" size='50' placeholder="Isikan asuransi lain">
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Informasi tentang Hak dan Kewajiban Pasien
										</div>
										<div class="col-8">
											: <input type='radio' name='gc3' value='Setuju' <?php if ($gc3=="Setuju"){echo "checked";}?> >Setuju
											<input type='radio' name='gc3' value='Tidak Setuju' <?php if ($gc3=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
										</div>
									</div>
								</td>
							</tr>	
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Informasi tentang Tata Tertib RS
										</div>
										<div class="col-8">
											: <input type='radio' name='gc4' value='Setuju' <?php if ($gc4=="Setuju"){echo "checked";}?> >Setuju
											<input type='radio' name='gc4' value='Tidak Setuju' <?php if ($gc4=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Kebutuhan Penterjemah Bahasa
										</div>
										<div class="col-8">
											: <input type='radio' name='gc5' value='Ya' <?php if ($gc5=="Ya"){echo "checked";}?> >Ya
											<input type='radio' name='gc5' value='Tidak' <?php if ($gc5=="Tidak"){echo "checked";}?> >Tidak
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Kebutuhan Rohaniawan
										</div>
										<div class="col-8">
											: <input type='radio' name='gc6' value='Ya' <?php if ($gc6=="Ya"){echo "checked";}?> >Ya
											<input type='radio' name='gc6' value='Tidak' <?php if ($gc6=="Tidak"){echo "checked";}?> >Tidak
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Pelepasan Informasi / Kerahasiaan Informasi
										</div>
										<div class="col-8">
											: <input type='radio' name='gc7' value='Setuju' <?php if ($gc7=="Setuju"){echo "checked";}?> >Setuju
											<input type='radio' name='gc7' value='Tidak Setuju' <?php if ($gc7=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											&nbsp;&bull; Hasil Pemeriksaan Penunjang dapat Diberikan kepada Pihak Penjamin
										</div>
										<div class="col-8">
											: <input type='radio' name='gc8' value='Setuju' <?php if ($gc8=="Setuju"){echo "checked";}?> >Setuju
											<input type='radio' name='gc8' value='Tidak Setuju' <?php if ($gc8=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
										</div>
									</div>
								</td>
							</tr>
				<!-- <tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Hasil Pemeriksaan Penunjang dapat Diakses oleh Peserta Didik
							</div>
							<div class="col-8">
								: <input type='radio' name='gc9' value='Setuju' <?php if ($gc9=="Setuju"){echo "checked";}?> >Setuju
								<input type='radio' name='gc9' value='Tidak Setuju' <?php if ($gc9=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
							</div>
						</div>
					</td>
				</tr> -->

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Anggota Keluarga Lain yang dapat Diberikan Informasi Data data Pasien
							</div>
							<div class="col-8">
								:
								<?php 
								if(empty($gc10)){
									$gc10="1... \n2... \n3...";
								}else{
									$gc10=$gc10;
								}
								?> 
								<textarea name= "gc10" id="" style="min-width:650px; min-height:100px;"><?php echo $gc10;?></textarea>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Fasyankes tertentu dalam rangka rujukan
							</div>
							<div class="col-8">
								: <input type='radio' name='gc11' value='Setuju' <?php if ($gc11=="Setuju"){echo "checked";}?> >Setuju
								<input type='radio' name='gc11' value='Tidak Setuju' <?php if ($gc11=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Yang Membuat Pernyataan (Tanda Tangan)<br>
								Menyetujui Lembar General Consent<br>
								<input type='checkbox' name='gc12' value='Pasien' <?php if ($gc12=="Pasien"){echo "checked";}?> > Pasien <br>
								<input type='checkbox' name='gc17' value='Bukan Pasien' <?php if ($gc17=="Bukan Pasien"){echo "checked";}?> > Keluarga Pasien / Bukan Pasien<br>
								<input type='checkbox' name='gc15' value='Suami' <?php if ($gc15=="Suami"){echo "checked";}?> > Suami
								<input type='checkbox' name='gc15' value='Istri' <?php if ($gc15=="Istri"){echo "checked";}?> > Istri
								<input type='checkbox' name='gc15' value='Ayah' <?php if ($gc15=="Ayah"){echo "checked";}?> > Ayah
								<input type='checkbox' name='gc15' value='Ibu' <?php if ($gc15=="Ibu"){echo "checked";}?> > Ibu
								<input type='checkbox' name='gc15' value='Saudara' <?php if ($gc15=="Saudara"){echo "checked";}?> > Saudara
								<input type='checkbox' name='gc15' value='Orang Lain' <?php if ($gc15=="Orang Lain"){echo "checked";}?> > Orang Lain

								<br>
								<input class="" name="gc16" value="<?php echo $gc16;?>" id="" type="text" size='50' placeholder="Isikan dg Nama Keluarga Pasien" required>
							</div>
							<div class="col-8">
								<table width="100%" border='1'>
									<tr>
										<td>
											Petugas yang Memberi Penjelasan
											<br>	
											<input class="" name="gc14" value="<?php echo $gc14;?>" id="karyawan2" type="text" size='50' placeholder="Isikan Nama Petugas yang Memberi Penjelasan" required>
										</td>
										<td>Saksi
											<br>	
											<input class="" name="gc13" value="<?php echo $gc13;?>" id="karyawan1" type="text" size='50' placeholder="Isikan Nama lengkap sesuai dengan KTP">										
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
										<td align="center" >
											Yang Membuat Pernyataan<br>
											<?php 
											if($gc16){
												$pernyataanpasien='Lembar General Consent ini telah disetujui oleh Keluarga Pasien dg nama:'.$gc16.' Hubungan dg pasien : '.$gc15.' - pada tanggal:'.$tgl;
												// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

												QRcode::png($pernyataanpasien, "image_pernyataanpasien.png", "L", 2, 2);   
												echo "<center><img src='image_pernyataanpasien.png'></center>";

												echo $gc16;												
											}
											?>
										</td>
										<td align="center">
											Tgl Input :<?php echo $tgl_assesment; ?> , Jam : <?php echo $jam_assesment; ?>
											<?php 
											if ($gc14){
												$penanggungjawab='Lembar General Consent ini telah ditandatangani oleh Petugas :'.$gc14.'pada tanggal:'.$tglinput;
												// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

												QRcode::png($penanggungjawab, "image.png", "L", 2, 2);   
												echo "<center><img src='image.png'></center>";

												echo 'Petugas : '.$gc14;
											}

											?>									
										</td>
										<td align="center">
											Saksi<br>
											<?php 
											if ($gc13){
												$saksi='Lembar General Consent ini telah ditandatangani oleh Saksi :'.$gc13.' - pada tanggal:'.$tgl;
												// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

												QRcode::png($saksi, "image_saksi.png", "L", 2, 2);   
												echo "<center><img src='image_saksi.png'></center>";

												echo $gc13;
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
								&bull; Ket : Tidak akan mengganti status penjamin pasien selama dirawat di rumah sakit
							</div>
						</div>
					</td>
				</tr>	

				<tr>
					<td>
						<div class="row">
							<div class="col-9">
								&bull; Saya mengetahui dan menyetujui bahwa berdasarkan Peraturan Menteri Kesehtan Nomor 24 Tahun 2022 tentang Rekam Medis, fasilitas pelayanan kesehatan wajib membuka akses dan mengirim data rekam medis kepada Kementrian Kesehatan melalui Platform <font size='5'><b>SATUSEHAT</b></font>.
							</div>
							<div class="col-3">
								<input type='radio' name='gc21' value='Setuju' <?php if ($gc21=="Setuju"){echo "checked";}?> >Setuju
								<input type='radio' name='gc21' value='Tidak Setuju' <?php if ($gc21=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-9">
								&bull; Menyetujui untuk menerima dan membuka data Pasien dari Fasilitas Pelayanan Kesehatan lainnya melalui <font size='5'><b>SATUSEHAT</b></font> untuk kepentingan pelayanan kesehatan dan atau rujukan.
							</div>
							<div class="col-3">
								<input type='radio' name='gc20' value='Setuju' <?php if ($gc20=="Setuju"){echo "checked";}?> >Setuju
								<input type='radio' name='gc20' value='Tidak Setuju' <?php if ($gc20=="Tidak Setuju"){echo "checked";}?> >Tidak Setuju
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
								<br><br>
								<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->

								&nbsp;&nbsp;
								<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
								<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;height: 60px;width: 80px;"> -->
								&nbsp;&nbsp;&nbsp;
							</div>
						</div>
					</td>
				</tr>	
			</table>
		<?php  } ?>
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

if (isset($_POST["simpan_dpjp"])) {

	$lanjut = 'Y';
	$kodedokter	= $_POST["dpjp"];
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);
	$namadokter  = trim($row[1]);

	if(empty($kodedokter)){
		$eror='DOKTER TIDAK BOLEH KOSONG';
		$lanjut='T';
	}

	if(empty($namadokter)){
		$eror='NAMA DOKTER TIDAK VALID';
		$lanjut='T';
	}

	if($lanjut=="Y"){

		$qu="SELECT id,noreg FROM ERM_ASSESMEN_HEADER where noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$norm = trim($d1u['norm']);
		$id = trim($d1u['id']);

		if($id){
			$q  = "update  ERM_ASSESMEN_HEADER set kodedokter='$kodedokter' where noreg='$noreg'";         
			$hs = sqlsrv_query($conn,$q);
		}
		
		if($hs){
			echo "<div class='container-fluid'>";
			echo "Data Berhasil diupdate !!!!";
			echo "<br>";
			echo "<a href='generalconsent.php?id=$id|$user' class='btn btn-warning'><i class='bi bi-x-circle'></i> Close</a>";
			echo "</div>";

			$eror='Data Berhasil diupdate !!!!';
			echo "
			<script>
			alert('".$eror."');
			window.location.replace('generalconsent.php?id=$id|$user');
			</script>
			";


		}

	}else{

		echo "<br>";
		echo "<br>";

		echo "<div class='container-fluid'>";

		echo "
		<div class='alert alert-danger' role='alert'>
		".$eror."
		</div>
		";

		echo "</div>";
	}

}

if (isset($_POST["simpan"])) {

	$gc1	= $_POST["gc1"];
	$gc2	= $_POST["gc2"];
	$gc3	= $_POST["gc3"];
	$gc4	= $_POST["gc4"];
	$gc5	= $_POST["gc5"];
	$gc6	= $_POST["gc6"];
	$gc7	= $_POST["gc7"];
	$gc8	= $_POST["gc8"];
	$gc9	= $_POST["gc9"];
	$gc10	= $_POST["gc10"];
	$gc11	= $_POST["gc11"];
	$gc12	= $_POST["gc12"];
	$gc13	= $_POST["gc13"];
	$gc14	= $_POST["gc14"];
	$gc15	= $_POST["gc15"];
	$gc16	= $_POST["gc16"];
	$gc17	= $_POST["gc17"];
	$gc18	= $_POST["gc18"];
	$gc19	= $_POST["gc19"];
	$gc20	= $_POST["gc20"];
	$gc21	= $_POST["gc21"];
	$gc22	= $_POST["gc22"];
	$gc23	= $_POST["gc23"];
	$gc24	= $_POST["gc24"];
	$gc25	= $_POST["gc25"];
	$gc26	= $_POST["gc26"];
	$gc27	= $_POST["gc27"];
	$gc28	= $_POST["gc28"];
	$gc29	= $_POST["gc29"];
	$gc30	= $_POST["gc30"];
	$gc31	= $_POST["gc31"];
	$gc32	= $_POST["gc32"];
	$gc33	= $_POST["gc33"];
	$gc34	= $_POST["gc34"];
	$gc35	= $_POST["gc35"];
	$gc36	= $_POST["gc36"];
	$gc37	= $_POST["gc37"];
	$gc38	= $_POST["gc38"];
	$gc39	= $_POST["gc39"];
	$gc40	= $_POST["gc40"];
	$gc41	= $_POST["gc41"];
	$gc42	= $_POST["gc42"];
	$gc43	= $_POST["gc43"];
	$gc44	= $_POST["gc44"];
	$gc45	= $_POST["gc45"];
	$gc46	= $_POST["gc46"];
	$gc47	= $_POST["gc47"];
	$gc48	= $_POST["gc48"];
	$gc49	= $_POST["gc49"];
	$gc50	= $_POST["gc50"];
	$gc51	= $_POST["gc51"];
	$gc52	= $_POST["gc52"];
	$gc53	= $_POST["gc53"];
	$gc54	= $_POST["gc54"];
	$gc55	= $_POST["gc55"];
	$gc56	= $_POST["gc56"];
	$gc57	= $_POST["gc57"];
	$gc58	= $_POST["gc58"];
	$gc59	= $_POST["gc59"];
	$gc60	= $_POST["ok60"];

	$q  = "update ERM_RI_GENERALCONSENT set
	gc1	='$gc1',
	gc2	='$gc2',
	gc3	='$gc3',
	gc4	='$gc4',
	gc5	='$gc5',
	gc6	='$gc6',
	gc7	='$gc7',
	gc8	='$gc8',
	gc9	='$gc9',
	gc10	='$gc10',
	gc11	='$gc11',
	gc12	='$gc12',
	gc13	='$gc13',
	gc14	='$gc14',
	gc15	='$gc15',
	gc16	='$gc16',
	gc17	='$gc17',
	gc18	='$gc18',
	gc19	='$gc19',
	gc20	='$gc20',
	gc21	='$gc21',
	gc22	='$gc22',
	gc23	='$gc23',
	gc24	='$gc24',
	gc25	='$gc25',
	gc26	='$gc26',
	gc27	='$gc27',
	gc28	='$gc28',
	gc29	='$gc29',
	gc30	='$gc30',
	gc31	='$gc31',
	gc32	='$gc32',
	gc33	='$gc33',
	gc34	='$gc34',
	gc35	='$gc35',
	gc36	='$gc36',
	gc37	='$gc37',
	gc38	='$gc38',
	gc39	='$gc39',
	gc40	='$gc40',
	gc41	='$gc41',
	gc42	='$gc42',
	gc43	='$gc43',
	gc44	='$gc44',
	gc45	='$gc45',
	gc46	='$gc46',
	gc47	='$gc47',
	gc48	='$gc48',
	gc49	='$gc49',
	gc50	='$gc50',
	gc51	='$gc51',
	gc52	='$gc52',
	gc53	='$gc53',
	gc54	='$gc54',
	gc55	='$gc55',
	gc56	='$gc56',
	gc57	='$gc57',
	gc58	='$gc58',
	gc59	='$gc59',
	gc60	='$gc60'
	where noreg='$noreg'
	";

	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Data akan dikirim ke Satu Sehat tunggu beberapa saat";

		// header("Location: raber.php?id=$id|$user");
		// exit; 

		echo "
		<script>
		alert('".$eror."');
		window.location.replace('s_sehat2.php?id=$noreg|$kodedokter|$KET1|$tglawal|$id|$user');
		</script>
		";

	}else{
		$eror = "Gagal Insert";

	}


	echo "
	<script>
	alert('".$eror."');
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