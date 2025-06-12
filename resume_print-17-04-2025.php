<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);

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


$qur="SELECT noreg FROM ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
$h1ur  = sqlsrv_query($conn, $qur);        
$d1ur  = sqlsrv_fetch_array($h1ur, SQLSRV_FETCH_ASSOC); 
$ceknoreg = trim($d1ur['noreg']);

if(empty($ceknoreg)){
	echo "
	<script>
	window.location.replace('resume_print2.php?id=$id|$user');
	</script>
	";
}

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1, ARM_REGISTER.CUSTNO
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = $d1u['KODEUNIT'];
$KET1 = $d1u['KET1'];
$NORM = $d1u['NORM'];
$CUSTNO = $d1u['CUSTNO'];

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

$q2       = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan,NOKTP, 
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

$qi="SELECT noreg FROM ERM_RI_RESUME where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_RESUME(noreg,userid,tglentry) values ('$noreg','$user','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume,CONVERT(VARCHAR, tglentry, 8) as jamentry
	FROM ERM_RI_RESUME
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$jamentry = $de['jamentry'];
	$resume2= $de['resume2'];
	$resume1 = $de['resume1'];
	$resume2= $de['resume2'];
	$resume3= $de['resume3'];
	$resume4= $de['resume4'];
	$resume5= $de['resume5'];
	$resume6= $de['resume6'];
	$resume7= $de['resume7'];
	$resume8= $de['resume8'];
	$resume9= $de['resume9'];
	$resume10= $de['resume10'];
	$resume11= $de['resume11'];
	$resume12= $de['resume12'];
	$resume13= $de['resume13'];
	$resume14= $de['resume14'];
	$resume15= $de['resume15'];
	$resume16= $de['resume16'];
	$resume17= $de['resume17'];
	$resume18= $de['resume18'];
	$resume19= $de['resume19'];
	$resume20= $de['resume20'];
	$resume21= $de['resume21'];
	$resume22= $de['resume22'];
	$resume23= $de['resume23'];
	$resume24= $de['resume24'];
	$resume25= $de['resume25'];
	$resume26= $de['resume26'];
	$resume27= $de['resume27'];
	$resume28= $de['resume28'];
	$resume29= $de['resume29'];
	$resume30= $de['resume30'];
	$resume31= $de['resume31'];
	$resume32= $de['resume32'];
	$resume33= $de['resume33'];
	$resume34= $de['resume34'];
	$resume35= $de['resume35'];
	$resume36= $de['resume36'];
	$resume37= $de['resume37'];
	$resume38= $de['resume38'];
	$resume39= $de['resume39'];
	$resume40= $de['resume40'];
	$resume41= $de['resume41'];
	$resume42= $de['resume42'];
	$resume43= $de['resume43'];
	$resume44= $de['resume44'];
	$resume45= $de['resume45'];
	$resume46= $de['resume46'];
	$resume47= $de['resume47'];
	$resume48= $de['resume48'];
	$resume49= $de['resume49'];
	$resume50= $de['resume50'];
	$resume51= $de['resume51'];
	$resume52= $de['resume52'];
	$resume53= $de['resume53'];
	$resume54= $de['resume54'];
	$resume55= $de['resume55'];
	$resume56= $de['resume56'];
	$resume57= $de['resume57'];
	$resume58= $de['resume58'];
	$resume59= $de['resume59'];
	$resume60= $de['resume60'];
	$resume61= $de['resume61'];
	$resume62= $de['resume62'];
	$resume63= $de['resume63'];
	$resume64= $de['resume64'];
	$resume65= $de['resume65'];
	$resume66= $de['resume66'];
	$resume67= $de['resume67'];
	$resume68= $de['resume68'];
	$resume69= $de['resume69'];
	$resume70= $de['resume70'];
	$resume71= $de['resume71'];
	$resume72= $de['resume72'];
	$resume73= $de['resume73'];
	$resume74= $de['resume74'];
	$resume75= $de['resume75'];
	$resume76= $de['resume76'];
	$resume77= $de['resume77'];
	$resume78= $de['resume78'];
	$resume79= $de['resume79'];
	$resume80= $de['resume80'];
	$resume81= $de['resume81'];
	$resume82= $de['resume82'];
	$resume83= $de['resume83'];
	$resume84= $de['resume84'];
	$resume85= $de['resume85'];
	$resume86= $de['resume86'];
	$resume87= $de['resume87'];
	$resume88= $de['resume88'];
	$resume89= $de['resume89'];
	$resume90= $de['resume90'];
	$resume91= $de['resume91'];
	$resume92= $de['resume92'];
	$resume93= $de['resume93'];
	$resume94= $de['resume94'];
	$resume95= $de['resume95'];
	$resume96= $de['resume96'];
	$resume97= $de['resume97'];
	$resume98= $de['resume98'];
	$resume99= $de['resume99'];
	$resume100= $de['resume100'];
	$resume101= $de['resume101'];
	$resume102= $de['resume102'];
	$resume103= $de['resume103'];
	$resume104= $de['resume104'];
	$resume105= $de['resume105'];
	$resume106= $de['resume106'];
	$resume107= $de['resume107'];
	$resume108= $de['resume108'];
	$resume109= $de['resume109'];
	$resume110= $de['resume110'];
	$resume111= $de['resume111'];
	$resume112= $de['resume112'];
	$resume113= $de['resume113'];
	$resume114= $de['resume114'];
	$resume115= $de['resume115'];
	$resume116= $de['resume116'];
	$resume117= $de['resume117'];
	$resume118= $de['resume118'];
	$resume119= $de['resume119'];
	$resume120= $de['resume120'];
	$resume121= $de['resume121'];
	$resume122= $de['resume122'];
	$resume123= $de['resume123'];
	$resume124= $de['resume124'];
	$resume125= $de['resume125'];
	$resume126= $de['resume126'];
	$resume127= $de['resume127'];
	$resume128= $de['resume128'];
	$resume129= $de['resume129'];
	$resume130= $de['resume130'];
	$resume131= $de['resume131'];
	$resume132= $de['resume132'];
	$resume133= $de['resume133'];
	$resume134= $de['resume134'];
	$resume135= $de['resume135'];
	$resume136= $de['resume136'];
	$resume137= $de['resume137'];
	$resume138= $de['resume138'];
	$resume139= $de['resume139'];
	$resume140= $de['resume140'];
	$resume141= $de['resume141'];
	$resume142= $de['resume142'];
	$resume143= $de['resume143'];
	$resume144= $de['resume144'];
	$resume145= $de['resume145'];
	$resume146= $de['resume146'];
	$resume147= $de['resume147'];
	$resume148= $de['resume148'];
	$resume149= $de['resume149'];
	$resume150= $de['resume150'];

	//select dari assesmen_medis
	$qe2="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ANAMNESIS_MEDIS
	where noreg='$noreg'";
	$he2  = sqlsrv_query($conn, $qe2);        
	$de2  = sqlsrv_fetch_array($he2, SQLSRV_FETCH_ASSOC); 

	$am1 = $de2['am1'];
	$am2= $de2['am2'];
	$am3= $de2['am3'];
	$am4= $de2['am4'];
	$am5= $de2['am5'];
	$am6= $de2['am6'];
	$am7= $de2['am7'];
	$am8= $de2['am8'];
	$am9= $de2['am9'];
	$am10= $de2['am10'];
	$am11= $de2['am11'];
	$am12= $de2['am12'];
	$am13= $de2['am13'];
	$am14= $de2['am14'];
	$am15= $de2['am15'];
	$am16= $de2['am16'];
	$am17= $de2['am17'];
	$am18= $de2['am18'];
	$am19= $de2['am19'];
	$am20= $de2['am20'];
	$am21= $de2['am21'];
	$am22= $de2['am22'];
	$am23= $de2['am23'];
	$am24= $de2['am24'];
	$am25= $de2['am25'];
	$am26= $de2['am26'];
	$am27= $de2['am27'];
	$am28= $de2['am28'];
	$am29= $de2['am29'];
	$am30= $de2['am30'];
	$am31= $de2['am31'];
	$am32= $de2['am32'];
	$am33= $de2['am33'];
	$am34= $de2['am34'];
	$am35= $de2['am35'];
	$am36= $de2['am36'];
	$am37= $de2['am37'];
	$am38= $de2['am38'];
	$am39= $de2['am39'];
	$am40= $de2['am40'];
	$am41= $de2['am41'];
	$am42= $de2['am42'];
	$am43= $de2['am43'];
	$am44= $de2['am44'];
	$am45= $de2['am45'];
	$am46= $de2['am46'];
	$am47= $de2['am47'];
	$am48= $de2['am48'];
	$am49= $de2['am49'];
	$am50= $de2['am50'];
	$am51= $de2['am51'];
	$am52= $de2['am52'];
	$am53= $de2['am53'];
	$am54= $de2['am54'];
	$am55= $de2['am55'];
	$am56= $de2['am56'];
	$am57= $de2['am57'];
	$am58= $de2['am58'];
	$am59= $de2['am59'];
	$am60= $de2['am60'];
	$am61= $de2['am61'];
	$am62= $de2['am62'];
	$am63= $de2['am63'];
	$am64= $de2['am64'];
	$am65= $de2['am65'];
	$am66= $de2['am66'];
	$am67= $de2['am67'];
	$am68= $de2['am68'];
	$am69= $de2['am69'];
	$am70= $de2['am70'];
	$am71= $de2['am71'];
	$am72= $de2['am72'];
	$am73= $de2['am73'];
	$am74= $de2['am74'];
	$am75= $de2['am75'];
	$am76= $de2['am76'];
	$am77= $de2['am77'];
	$am78= $de2['am78'];
	$am79= $de2['am79'];
	$am80= $de2['am80'];

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
	<script>
		$(function() {
			$("#icd95").autocomplete({
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
			$("#icd96").autocomplete({
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
			$("#icd97").autocomplete({
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
			$("#icd98").autocomplete({
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
			<font size='4px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<input type='submit' name='Print' value='Print' class='btn btn-info'>

					<div class="row">
					</div>
					<?php
					// QRcode::png("test", "image.png", "L", 4, 4);   
					// echo "<image src='image.png'>";
					?>
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
							<b>RINGKASAN PASIEN PULANG RAWAT INAP</b><br>
							INPATIENT DISCHARGE SUMMARY (MEDICAL RESUME)
						</div>
					</div>
					<hr> 
					<div class="row">
						<div class="col-3">
							Ruang Perawatan
						</div>
						<div class="col-3">
							: <?php echo $resume1; ?>
						</div>
						<div class="col-3">
							Tgl. MRS
						</div>
						<div class="col-3">
							: <?php echo $resume2; ?>
						</div>
					</div>

					<div class="row">
						<div class="col-3">
							<!-- Jumlah Hari Perawatan -->
						</div>
						<div class="col-3">
							<!-- : <?php echo $resume3; ?> -->
						</div>
						<div class="col-3">
							Tgl. KRS
						</div>
						<div class="col-3">
							: <?php echo $resume4; ?>
						</div>
					</div>

					<?php if ($CUSTNO){?>
						<div class="row">
							<div class="col-3">
								<!-- No. SEP -->
							</div>
							<div class="col-3">
								<!-- : <?php echo $resume5; ?> -->
							</div>
							<?php if($resume6){ ?>
								<div class="col-3">
									Tgl. Meninggal
								</div>
								<div class="col-3">
									: <?php echo $resume6 ?>
								</div>
							<?php } ?>
						</div>

<!-- 						<div class="row">
							<div class="col-3">
								No. Asuransi
							</div>
							<div class="col-3">
								: <?php echo $resume7; ?>
							</div>
							<div class="col-3">
								&nbsp;
							</div>
							<div class="col-3">
								&nbsp;
							</div>
						</div>	 -->		
					<?php }?>

					<div class="row">
						<div class="col-3">
							<b>DPJP </b>
						</div>
						<div class="col-9">
							<table border='0' width="100%">
								<?php 

								$q="
								SELECT        0 as id,SUBSTRING(dpjp, 0, 4) AS kodedokter, noreg, 'DPJP UTAMA' AS keterangan
								FROM            dbo.V_ERM_RI_DPJP_ASESMEN
								WHERE        (noreg = '$noreg')
								UNION
								SELECT        id,kode_dokter as kodedokter, SUBSTRING(noreg, 0, 13) AS noreg, keterangan
								FROM            ERM_RI_RABER
								WHERE        (noreg = '$noreg')
								";
								$hasil  = sqlsrv_query($conn, $q);  
								$no=1;
								while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
									$q2		= "select nama from afarm_dokter where kodedokter like '%$data[kodedokter]%'";
									$hasil2  = sqlsrv_query($conn, $q2);			  					
									$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
									$namadokter	= $data2[nama];


									if($data[keterangan]=='DPJP UTAMA'){
										echo "
										<tr>
										<td>$no.</td>
										<td>$data[keterangan]</td>
										<td>$namadokter</td>
										</tr>
										";
									}else{
										echo "
										<tr>
										<td>$no.</td>
										<td>$data[keterangan]</td>
										<td>$namadokter</td>
										</tr>
										";
									}
									$no += 1;
								}
								?>
							</table>
						</div>

					</div>

					<hr>

					<div class="row">
						
						<div class="col-4">
							<b>Diisi Oleh Dokter</b>
						</div>
						<div class="col-8">

						</div>

					</div>

					<div class="row">
						<div class="col-4">
							<b>1. Anamnese</b>
						</div>
						<div class="col-8">

						</div>
						<div class="col-4">
							&bull; Keluhan Utama
						</div>
						<div class="col-8">
							: <?php echo $resume8; ?>
						</div>
						<div class="col-4">
							&bull; Lama Keluhan
						</div>
						<div class="col-8">
							: <?php echo $resume9; ?>
						</div>
						<div class="col-4">
							&bull; Keluhan Lain
						</div>
						<div class="col-8">
							: <?php echo $resume10; ?>
						</div>
					</div>
					<hr> 

					<div class="row">
						<div class="col-4">
							<b>2. Riwayat Penyakit Dahulu</b>
						</div>
						<div class="col-8">

						</div>
						<div class="col-4">
							&bull; Nama Penyakit
						</div>
						<div class="col-8">
							: <?php echo $resume11; ?>
						</div>
						<div class="col-4">
							&bull; Lama Penyakit
						</div>
						<div class="col-8">
							: <?php echo $resume12; ?>
						</div>
						<div class="col-4">
							&bull; Riwayat Keluarga
						</div>
						<div class="col-8">
							: <?php echo $resume13; ?>
						</div>
						<div class="col-4">
							&bull; Riwayat Alergi
						</div>
						<div class="col-8">
							: <?php echo $resume14; ?>
						</div>
						<div class="col-4">
							&bull; Riwayat Pengobatan
						</div>
						<div class="col-8">
							: <?php echo $resume15; ?>
						</div>
					</div>
					<hr> 


					<div class="row">
						<div class="col-4">
							<b>3. Pemeriksaan Saat MRS</b>
						</div>
						<div class="col-8">

						</div>
						<div class="col-4">
							&bull; Fisik
						</div>
						<div class="col-8">							
							<!-- <input type='text' name='fisik' value='<?php echo $fisik; ?>' size='100'> -->
							<?php 
							
							$total_gcs=$am6+$am7+$am8;
							$row2 = explode('/',$am9);
							$sistole  = $row2[0];
							$diastole = $row2[1]; 

							$periksa_fisik="
							<table border='0'>
							<tr>
							<td colspan='2'>Keadaan umum</td>
							</tr>
							<tr>
							<td colspan='2'>1. Tingkat kesadaran : $am5</td>
							</tr>
							<tr>
							<td colspan='2'>2. Vital sign: GCS : $am6 - $am7 - $am8 ($total_gcs)</td>
							</tr>
							<tr>
							<td>Tekanan darah: $sistole / $diastole mmHg</td><td>|</td><td>Nadi: $am10 x/menit - $am11</td>
							</tr>
							<tr>
							<td>Suhu tubuh:$am12 C</td><td>|</td><td>Frekuensi Pernafasan:$am13</td>
							</tr>
							<tr>
							<td>Skala Nyeri:$am14</td><td>|</td><td>Berat Badan:$am15</td>
							</tr>
							</table>
							Pemeriksaan untuk mengetahui kondisi fisik di tubuh pasien : <br>";

							if(trim($am19)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Kepala : ".$am19.",ket : ".$am20."<br>";
							}
							if(trim($am21)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Mata : ".$am21.",ket : ".$am22."<br>";
							}
							if(trim($am23)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Telinga : ".$am23.",ket : ".$am24."<br>";
							}
							if(trim($am25)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Hidung : ".$am25.",ket : ".$am26."<br>";
							}
							if(trim($am27)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Rambut : ".$am27.",ket : ".$am28."<br>";
							}
							if(trim($am29)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Bibir : ".$am29.",ket : ".$am30."<br>";
							}
							if(trim($am31)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Gigi Gligi : ".$am31.",ket : ".$am32."<br>";
							}
							if(trim($am33)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Lidah : ".$am33.",ket : ".$am34."<br>";
							}
							if(trim($am35)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Langit-langit : ".$am35.",ket : ".$am36."<br>";
							}
							if(trim($am37)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Leher : ".$am37.",ket : ".$am38."<br>";
							}
							if(trim($am39)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Tenggorokan : ".$am39.",ket : ".$am40."<br>";
							}
							if(trim($am41)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Tonsil : ".$am41.",ket : ".$am42."<br>";
							}
							if(trim($am43)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Dada : ".$am43.",ket : ".$am44."<br>";
							}
							if(trim($am45)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Payudara : ".$am45.",ket : ".$am46."<br>";
							}
							if(trim($am47)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Punggung : ".$am47.",ket : ".$am48."<br>";
							}
							if(trim($am49)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Perut : ".$am49.",ket : ".$am50."<br>";
							}
							if(trim($am51)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Genital : ".$am51.",ket : ".$am52."<br>";
							}
							if(trim($am53)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Anus/Dubur : ".$am53.",ket : ".$am54."<br>";
							}
							if(trim($am55)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Lengan Atas : ".$am55.",ket : ".$am56."<br>";
							}
							if(trim($am57)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Lengan Bawah : ".$am57.",ket : ".$am58."<br>";
							}
							if(trim($am59)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Jari Tangan : ".$am59.",ket : ".$am60."<br>";
							}
							if(trim($am61)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Kuku Tangan : ".$am61.",ket : ".$am62."<br>";
							}
							if(trim($am63)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Persendian Tangan : ".$am63.",ket : ".$am64."<br>";
							}
							if(trim($am65)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Tungkai Atas : ".$am65.",ket : ".$am66."<br>";
							}
							if(trim($am67)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Tungkai Bawah : ".$am67.",ket : ".$am68."<br>";
							}
							if(trim($am69)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Jari Kaki : ".$am69.",ket : ".$am70."<br>";
							}
							if(trim($am71)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Kuku Kaki : ".$am71.",ket : ".$am72."<br>";
							}
							if(trim($am73)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Persendian Kaki : ".$am73.",ket : ".$am74."<br>";
							}
							if(trim($am16)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Status psikologis : ".$am16."<br>";
							}
							if(trim($am17)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Sosial Ekonomi : ".$am17."<br>";
							}
							if(trim($am18)=="Abnormal"){
								$periksa_fisik = $periksa_fisik."Spiritual : ".$am18;
							}

							echo ($periksa_fisik);

							// if(empty($resume16)){
							// 	echo ($periksa_fisik);								
							// }else{
							// 	echo nl2br($resume16);  
							// }

							?>

							<br>
						</div>
						<div class="col-4">
							&bull; Pemeriksaan Fisik Lain 
						</div>
						<div class="col-8">
							<?php echo nl2br($resume16); ?>
						</div>
						<div class="col-4">
							&bull; Laboratorium
						</div>
						<div class="col-8">

							<?php 
							$regigd=substr($noreg,1,12);
							// $qlab="
							// SELECT 
							// CONVERT(VARCHAR, REG_DATE, 103) as REG_DATE,KEL_PEMERIKSAAN,PARAMETER_NAME,HASIL,SATUAN,NILAI_RUJUKAN,FLAG,NOLAB_RS
							// FROM        LINKYAN5.SHARELIS.dbo.hasilLIS
							// WHERE        (NOLAB_RS = '$noreg' or NOLAB_RS = '$regigd' ) AND (PARAMETER_NAME NOT IN ('- Basofil', '- Eosinofil', '- Limfosit', '- Monosit', '- Neutrofil'))
							// order by REG_DATE desc, KEL_PEMERIKSAAN,PARAMETER_NAME
							// ";
							$qlab="
							SELECT 
							CONVERT(VARCHAR, REG_DATE, 103) as REG_DATE,KEL_PEMERIKSAAN,PARAMETER_NAME,HASIL,SATUAN,NILAI_RUJUKAN,FLAG,NOLAB_RS
							FROM        LINKYAN5.SHARELIS.dbo.hasilLIS
							WHERE        (NOLAB_RS = '$noreg' or NOLAB_RS = '$regigd' ) 
							order by NOLAB_RS desc, REG_DATE desc, KEL_PEMERIKSAAN,PARAMETER_NAME
							";
							$hqlab  = sqlsrv_query($conn, $qlab);

							$labh = "no | tgl          | pemeriksaan | hasil | nilai normal";
							$labh2 = "====================================";

							echo "<table class='table'>
							<tr>
							<td>unit</td><td>tgl</td><td>pemeriksaan</td><td>hasil</td><td>nilai normal</td>
							</tr>
							";
							$i=1;
							while   ($dhqlab = sqlsrv_fetch_array($hqlab, SQLSRV_FETCH_ASSOC)){     
								$lab0 = $i.'|'.$dhqlab[REG_DATE].'|'.$dhqlab[KEL_PEMERIKSAAN].'-'.trim($dhqlab[PARAMETER_NAME]).' : '.    $dhqlab[HASIL].' | '.trim($dhqlab[NILAI_RUJUKAN]).' ('.trim($dhqlab[FLAG]).')';
								$nnormal = trim($dhqlab[FLAG]);
								$PARAMETER_NAME          = str_replace("-","",$dhqlab[PARAMETER_NAME]);
								$rawat = trim($dhqlab[NOLAB_RS]);
								$rawatx = substr($rawat,0,1);
								if($rawatx=='R'){
									$jrawat="RI";
								}else{
									$jrawat="IGD";
								}
								if($nnormal<>''){
									echo "
									<tr>
									<td bgcolor=''>$jrawat</td>
									<td bgcolor=''>$dhqlab[REG_DATE]</td>
									<td bgcolor=''>$dhqlab[KEL_PEMERIKSAAN] - $PARAMETER_NAME</td>
									<td bgcolor=''>$dhqlab[HASIL]</td>
									<td bgcolor=''>$dhqlab[NILAI_RUJUKAN] - $dhqlab[FLAG]</td>
									</tr>
									";
								}

								$i=$i+1;
							}

							echo "</table>";
							echo "<br>";
							?>
						</div>
						<div class="col-4">
							&bull; Hasil Lab Lain
						</div>
						<div class="col-8">
							: <?php echo nl2br($resume17); ?>
						</div>

						<div class="col-4">
							&bull; Radiologi
						</div>
						<div class="col-8">
							: <?php 
							$regigd=substr($noreg,1,12);
							$qrad="
							SELECT        HASIL, URAIAN, CONVERT(VARCHAR, TANGGAL, 103) AS TANGGAL,NOREG
							FROM            HASILRAD_PEMERIKSAAN_RAD
							WHERE        (NOREG = '$noreg') OR
							(NOREG = '$regigd')
							ORDER BY TANGGAL
							";
							$hqrad  = sqlsrv_query($conn, $qrad);

							$i=1;
							while   ($dhqrad = sqlsrv_fetch_array($hqrad, SQLSRV_FETCH_ASSOC)){     
								$rad0 = 'TGL : '.$dhqrad[TANGGAL].', REG : '.$dhqrad[NOREG].'<br><u>'.$dhqrad[HASIL].'</u>:'."\n".nl2br($dhqrad[URAIAN])."<hr>";
								$rad = $rad.'&#13;&#10;'.$rad0;
							}

							echo $rad;
							?>
						</div>

						<div class="col-4">
							&bull; Hasil Rad Lain
						</div>
						<div class="col-8">
							: <?php echo nl2br($resume18); ?>
						</div>

						<div class="col-4">
							&bull; Lain-lain
						</div>
						<div class="col-8">
							: <?php echo $resume19; ?>
						</div>
					</div>
					<hr>

					<div class="row">				

						<div class="col-4">
							<b>4. Diagnosis</b>
						</div>
						<div class="col-8">

						</div>
						
						<!-- <div class="col-4">
							&bull; Diagnosis Awal / Masuk
						</div>
						<div class="col-8">
							: <?php 

							if(preg_match("/-/i", $resume20)){
								$row = explode('-',$resume20);
								$resume20  = $row[1]; 
								if(empty($resume20)){
									$resume20  = $row[0];
								}else{
									if(preg_match("/-/i", $resume20)){
										$row = explode('-',$resume20);		
										$resume20  = $row[1]; 		
										if(empty($resume20)){
											$resume20  = $row[0];								
										}else{
											$resume20;	
										}
									}else{

									}
								}
							}
							echo $resume20;?>
						</div> -->
						
						<div class="col-4">
							&bull; Diagnosis Akhir (Primer)
						</div>
						<div class="col-8">
							: 
							<!-- <input type='text' name='diagnosis_akhir_primer' value='<?php echo $diagnosis_akhir_primer; ?>' size='100'> -->
<!-- 				<select id="resume21" name="resume21">
					<option value=""></option>
					<option value="R68.83">R68.83 - chills without feve</option>
					<option value="R56.0">R56.0 - febrile convulsions</option>
					<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
					<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
					<option value="R68.0">R68.0 - hypothermia due to illness</option>
					<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
					<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
				</select>
			-->				
			<?php  
			// if(preg_match("/NON/i", $resume20) or preg_match("/END/i", $resume20)) {
			// 	$resume20 = trim(substr($resume20,7,500));
			// }else{
			// 	$row = explode('-',$resume20);$resume20  = $row[1]; if(empty($resume20)){$resume20  = $row[0];} 
			// }

			// if(preg_match("/NON/i", $resume21) or preg_match("/END/i", $resume21)) {
			// 	$resume21 = trim(substr($resume21,7,500));
			// }else{
			// 	$row = explode('-',$resume21);$resume21  = $row[1]; if(empty($resume21)){$resume21  = $row[0];}
			// }

			if(preg_match("/-/i", $resume20)){
				$row = explode('-',$resume20);
				$resume20  = $row[1]; 
				if(empty($resume20)){
					$resume20  = $row[0];
				}else{
					if(preg_match("/-/i", $resume20)){
						$row = explode('-',$resume20);		
						$resume20  = $row[1]; 		
						if(empty($resume20)){
							$resume20  = $row[0];								
						}else{
							// $resume20  = $row[1];	
							$resume20;	
						}
					}else{

					}
				}
			}


			if(preg_match("/-/i", $resume21)){
				$row = explode('-',$resume21);
				$resume21  = $row[1]; 
				if(empty($resume21)){
					$resume21  = $row[0];
				}else{
					if(preg_match("/-/i", $resume21)){
						$row = explode('-',$resume21);		
						$resume21  = $row[1]; 		
						if(empty($resume21)){
							$resume21  = $row[0];								
						}else{
							// $resume21  = $row[1];	
							$resume21;	
						}
					}else{

					}
				}
			}

			if(empty($resume21)){
				echo $resume20; 
			}else{
				echo $resume21; 

			}
			
			?>
		</div>
		<div class="col-4">
			&bull; Diagnosis Akhir (Sekunder)
		</div>
		<div class="col-8">
			: 
			<?php 
			if(preg_match("/-/i", $resume22)){
				$row = explode('-',$resume22);
				$resume22  = $row[1]; 
				if(empty($resume22)){
					$resume22  = $row[0];
				}else{
					if(preg_match("/-/i", $resume22)){
						$row = explode('-',$resume22);		
						$resume22  = $row[1]; 		
						if(empty($resume22)){
							$resume22  = $row[0];								
						}else{
							// $resume22  = $row[1];	
							$resume22;	
						}
					}else{

					}
				}
			}

			echo $resume22; 
			?>
			<br>
			<?php
			$qds="SELECT * FROM  ERM_RI_DIAGNOSA_SEKUNDER where noreg='$noreg'";
			$hqds  = sqlsrv_query($conn, $qds);        
			$dhqds  = sqlsrv_fetch_array($hqds, SQLSRV_FETCH_ASSOC); 			
			$diagnosa1  = $dhqds['diagnosa_sekunder1'];
			$row = explode('-',$diagnosa1);$diagnosa1  = $row[1];if(empty($diagnosa1)){$diagnosa1  = $row[0];}

			$diagnosa2  = $dhqds['diagnosa_sekunder2'];
			$row = explode('-',$diagnosa2);$diagnosa2  = $row[1];if(empty($diagnosa2)){$diagnosa2  = $row[0];}

			$diagnosa3  = $dhqds['diagnosa_sekunder3'];
			$row = explode('-',$diagnosa3);$diagnosa3  = $row[1];if(empty($diagnosa3)){$diagnosa3  = $row[0];}

			$diagnosa4   = $dhqds['diagnosa_sekunder4'];
			$row = explode('-',$diagnosa4);$diagnosa4  = $row[1];if(empty($diagnosa4)){$diagnosa4  = $row[0];}

			$diagnosa5   = $dhqds['diagnosa_sekunder5'];
			$row = explode('-',$diagnosa5);$diagnosa5  = $row[1];if(empty($diagnosa5)){$diagnosa5  = $row[0];}

			$diagnosa6   = $dhqds['diagnosa_sekunder6'];
			$row = explode('-',$diagnosa6);$diagnosa6  = $row[1];if(empty($diagnosa6)){$diagnosa6  = $row[0];}

			$diagnosa7   = $dhqds['diagnosa_sekunder7'];
			$row = explode('-',$diagnosa7);$diagnosa7  = $row[1];if(empty($diagnosa7)){$diagnosa7  = $row[0];}

			$diagnosa8   = $dhqds['diagnosa_sekunder8'];
			$row = explode('-',$diagnosa8);$diagnosa8  = $row[1];if(empty($diagnosa8)){$diagnosa8  = $row[0];}

			$diagnosa9   = $dhqds['diagnosa_sekunder9'];
			$row = explode('-',$diagnosa9);$diagnosa9  = $row[1];if(empty($diagnosa9)){$diagnosa9  = $row[0];}

			$diagnosa10   = $dhqds['diagnosa_sekunder10'];
			$row = explode('-',$diagnosa10);$diagnosa10  = $row[1];if(empty($diagnosa10)){$diagnosa10  = $row[0];}

			if($diagnosa1){
				echo $diagnosa1;echo "<br>";
			}
			if($diagnosa2){
				echo $diagnosa2;echo "<br>";
			}
			if($diagnosa3){
				echo $diagnosa3;echo "<br>";
			}
			if($diagnosa4){
				echo $diagnosa4;echo "<br>";
			}
			if($diagnosa5){
				echo $diagnosa5;echo "<br>";
			}
			if($diagnosa6){
				echo $diagnosa6;echo "<br>";
			}
			if($diagnosa7){
				echo $diagnosa7;echo "<br>";
			}
			if($diagnosa8){
				echo $diagnosa8;echo "<br>";
			}
			if($diagnosa9){
				echo $diagnosa9;echo "<br>";
			}
			if($diagnosa10){
				echo $diagnosa10;echo "<br>";
			}

			?>
		</div>
	</div>
	<?php 
	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_LAPORAN_OK
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$ok1 = $de['ok1'];
	$ok2 = $de['ok2'];
	$ok3 = $de['ok3'];
	$ok4 = $de['ok4'];
	$ok5 = $de['ok5'];
	$ok6 = $de['ok6'];
	$ok7 = $de['ok7'];
	$ok8 = $de['ok8'];

	if($ok1){
		?>
		<br>
		<b>Tindakan Operasi / OK</b><br>		
		<table width='100%' border='0'>
			<tr>
				<td colspan="2">
					<div class="row">
						<div class="col-4">
							Diagnosa pra pembedahan
						</div>
						<div class="col-6">
							: 
							<?php $row = explode('-',$ok1);$ok1  = $row[1]; //echo $ok3;?>

							<?php 
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
							Tindakan yang dilakukan
						</div>
						<div class="col-8">
							: 
							<!-- <input type='text' name='' value='' size='50'> -->
							<?php $row = explode('-',$ok3);$ok3  = $row[1]; //echo $ok3;?>
							<?php $row = explode('-',$ok4);$ok4  = $row[1]; //echo $ok4;?>
							<?php $row = explode('-',$ok5);$ok5  = $row[1]; //echo $ok5;?>
							<?php $row = explode('-',$ok6);$ok6  = $row[1]; //echo $ok6;?>
							<?php $row = explode('-',$ok7);$ok7  = $row[1]; //echo $ok7;?>
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
							Diagnosa pasca pembedahan
						</div>
						<div class="col-8">
							:<?php $row = explode('-',$ok8);$ok8  = $row[1]; //echo $ok8;?>
							<?php echo $ok8;?>
						</div>
					</div>
				</td>
			</tr>
		</table>
		<?php
	}
	?>
	<hr> 
	<div class="row">
		<div class="col-4">
			<b>5. Masalah Utama</b>
		</div>
		<div class="col-8">
			: <?php echo $resume23; ?>
		</div>
	</div>
	<hr> 
<!-- 	<div class="row">
		<div class="col-4">
			<b>6. Konsultasi</b>
		</div>
		<div class="col-8">
			: 
			<?php echo nl2br($resume24); ?>
		</div>
	</div>
	<hr>  -->
	<div class="row">
		<div class="col-4">
			<b>6. Pengobatan/Tindakan selama di RS</b>
		</div>
		<div class="col-8">
			<!-- : <input type='text' name='pengobatan' value='<?php echo $pengobatan; ?>' size='100'> -->
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<b>&nbsp;&nbsp;&nbsp;Tindakan (ICD-9CM)</b>
		</div>
		<div class="col-8">					 
			<?php if($resume25){ echo ':'.$resume25;}?>
			<?php if($resume26){ echo $resume26;}?>
			<?php if($resume27){ echo $resume27;}?>
			<?php if($resume28){ echo $resume28;echo "<br>";}?>
			<?php if($resume29){ echo ':'.$resume29;}?>
			<?php if($resume30){ echo $resume30;echo "<br>";}?>
			<?php if($resume31){ echo ':'.$resume31;}?>
			<?php if($resume32){ echo $resume32;echo "<br>";}?>
			<?php if($resume33){ echo ':'.$resume33;}?>
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			<!-- <b>&nbsp;&nbsp;&nbsp;b. Pengobatan</b> -->
		</div>
		<div class="col-8">
			: <?php echo nl2br($am77); ?>
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			<b>&nbsp;&nbsp;&nbsp;Terapi Tambahan</b>
		</div>
		<div class="col-8">
			: 
			<?php echo nl2br($resume34); ?>
		</div>
	</div>

	<hr> 
	<div class="row">
		<div class="col-4">
			<b>7. Prognosis</b>
		</div>
		<div class="col-8">
			: <?php echo $resume35; ?>
		</div>
	</div>
	<hr> 
	<div class="row">
		<div class="col-4">
			<b>8. Keadaan waktu keluar RS (Status pasien)</b>
		</div>
		<div class="col-8">
			: 
			<!-- <input type='text' name='keadaan_keluar_rs' value='<?php echo $keadaan_keluar_rs; ?>' size='100'> -->
			<?php echo $resume36; ?>
		</div>
	</div>
	<hr> 
	<?php if($resume37){ ?>
		<div class="row">
			<div class="col-4">
				<b>9. Sebab meninggal</b>
			</div>
			<div class="col-8">
				: <?php echo $resume37; ?>
			</div>
		</div>
	<?php } ?>

	<div class="row">
		<div class="col-4">
			Verifikasi Dokter Pemeriksa
		</div>
		<div class="col-8">
			<?php 
			$row = explode('-',$resume38);$resume38  = $row[1];

			echo $resume38." Tanggal : ".$resume4; ?>

		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<?php 
			if($resume38){
				$verif_dokter="Resume medis ini telah diVerifikasi Oleh Dokter : ".$resume38." Pada Tanggal : ".$resume4.', '.$jamentry; 
			// echo "<center><img alt='ttd' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_dokter&choe=UTF-8'/></center>";

				QRcode::png($verif_dokter, "image_verif_dokter.png", "L", 2, 2);   
				echo "<center><img src='image_verif_dokter.png'></center>";


			}
			?>
		</div>
	</div>


	<hr>
	<div class="row">
		<div class="col-4">
			<b>Tindak Lanjut (Diisi Oleh Bidan / Perawat)</b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			a. Aktifitas
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Jenis aktivitas yang boleh dilakukan
		</div>
		<div class="col-8">
			: <?php echo $resume39; ?>
		</div>
		<div class="col-4">
			&bull; Prosedur
		</div>
		<div class="col-8">
			: <?php echo $resume40; ?>
		</div>
		<div class="col-4">
			&bull; Alat Bantu
		</div>
		<div class="col-8">
			: <?php echo $resume41; ?>
		</div>

		<div class="col-4">
		</div>
		<div class="col-8">
		</div>

		<div class="col-4">
			b. Edukasi Kesehatan
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Pemeriksaan Laboratorium Lanjutan
		</div>
		<div class="col-8">
			: <?php echo $resume42; ?>
		</div>
		<div class="col-4">
			&bull; Pencegahan Terhadap ke Kambuhan
		</div>
		<div class="col-8">
			: <?php echo $resume43; ?>
		</div>
		<div class="col-4">
			&bull; Jadwal Kontrol
		</div>
		<div class="col-8">
			: <?php echo $resume44; ?>
		</div>
		<div class="col-4">
			&bull; Lain nya
		</div>
		<div class="col-8">
			: <?php echo $resume45; ?>
		</div>
		<div class="col-4">
		</div>
		<div class="col-8">
		</div>
		<div class="col-4">
			c. Perawatan Saat dirumah
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Kenali tanda dan gejala yang perlu  dilaporkan
		</div>
		<div class="col-8">
			: <?php echo $resume46; ?>
		</div>
		<div class="col-4">
			&bull;  Pengobatan yangdapat dilakukan   dirumah sebelum ke rumah Sakit
		</div>
		<div class="col-8">
			: <?php echo $resume47; ?>
		</div>
		<div class="col-4">
			&bull; Dalam keadaan darurat, segera bawa pasien ke IGD/dokter terdekat
		</div>
		<div class="col-8">
			: <?php echo $resume48; ?>
		</div>
		<div class="col-4">
		</div>
		<div class="col-8">
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			Verifikasi Perawat / Bidan Pemberi Resume
		</div>
		<div class="col-8">
			<?php 
			$row = explode('-',$resume49);$resume49  = $row[1];
			echo $resume49." Tanggal : ".$resume4; 
			?>

		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<?php 
			if($resume49){
				$verif_perawat="Resume medis ini telah diVerifikasi Oleh Petugas : ".$resume49." Pada Tanggal : ".$resume4.', '.$jamentry; 
			// echo "<center><img alt='ttd' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_perawat&choe=UTF-8'/></center>";

				QRcode::png($verif_perawat, "image_verif_perawat.png", "L", 2, 2);   
				echo "<center><img src='image_verif_perawat.png'></center>";

			}
			?>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-4">
			<b>Diisi Oleh Ahli Gizi</b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			d. Diet
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Ajuran Pola Makan
		</div>
		<div class="col-8">
			: <?php echo $resume50; ?>
		</div>
		<div class="col-4">
			&bull; Batasan makanan
		</div>
		<div class="col-8">
			: <?php echo $resume51; ?>
		</div>
		<div class="col-4">
		</div>
		<div class="col-8">
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			Verifikasi Ahli Gizi Pemberi Resume
		</div>
		<div class="col-8">		
			<?php 
			$row = explode('-',$resume52);$resume52  = $row[1];
			echo $resume52." Tanggal : ".$resume4; ?>

		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<?php 
			if($resume52){
				$verif_gizi="Resume medis ini telah diVerifikasi Oleh : ".$resume52." Pada Tanggal : ".$resume4.', '.$jamentry; 
			// echo "<center><img alt='ttd' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_gizi&choe=UTF-8'/></center>";

				QRcode::png($verif_gizi, "image_gizi.png", "L", 2, 2);   
				echo "<center><img src='image_gizi.png'></center>";

			}
			?>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-12">
			<b>Daftar Obat</b>
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			<b><u>Obat yang diberikan Saat Perawatan</u></b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-12">
			<?php 
			$q="
			select distinct nama_obat
			from ERM_RI_RPO
			where noreg='$noreg' order by nama_obat asc
			";
			$hasil  = sqlsrv_query($conn, $q);  
			$no=1;
			while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
				echo $no.' - '.$data[nama_obat];echo "<br>";
				$no+=1;
			}

			?>

		</div>

	</div>

	<div class="row">
		<div class="col-4">
			<b><u>Obat yang dibawa Pulang</u></b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-12">
			<table border='1'>
				<tr>
					<td>no</td><td>nama obat</td><td>jumlah</td><td>aturan pakai</td><td>instruksi khusus</td>
				</tr>
				<?php 
			// $q="
			// select distinct nama_obat,jumlah,aturan_pakai,instruksi_khusus
			// from ERM_RI_DISCHARGE
			// where noreg='$noreg' order by nama_obat asc
			// ";

			// $q="
			// SELECT        W_EResep.Noreg, W_EResep_R.Jenis, W_EResep_R.KodeR, W_EResep_R.Jumlah as jumlah, W_EResep_R.AturanPakai as aturan_pakai, W_EResep_R.CaraPakai, W_EResep_R.WaktuPakai, W_EResep_Racikan.Nama, W_EResep.Kategori as instruksi_khusus,W_EResep_R.Keterangan,
			// AFarm_MstObat.NAMABARANG as nama_obat, W_EResep.Id, W_EResep_Racikan.Dosis
			// FROM            AFarm_MstObat INNER JOIN
			// W_EResep_R ON AFarm_MstObat.KODEBARANG = W_EResep_R.KodeR INNER JOIN
			// W_EResep ON W_EResep_R.IdResep = W_EResep.Id LEFT OUTER JOIN
			// W_EResep_Racikan ON W_EResep_R.Id = W_EResep_Racikan.IdR
			// WHERE        (W_EResep.Noreg = '$noreg') AND W_EResep.Kategori like '%KRS%'
			// ";

				$q="
				SELECT        W_EResep.Noreg, W_EResep_R.Jenis, W_EResep_R.KodeR, convert(varchar(10),W_EResep_R.Jumlah) +'S' as jumlah, W_EResep_R.AturanPakai as aturan_pakai, W_EResep_R.CaraPakai,
				W_EResep_R.WaktuPakai, 
				CASE WHEN W_EResep_Racikan.Nama ='' THEN AFarm_MstObat.NAMABARANG ELSE W_EResep_Racikan.Nama END AS Nama,
				W_EResep.Kategori as instruksi_khusus,W_EResep_R.Keterangan, AFarm_MstObat.NAMABARANG as nama_obat, W_EResep.Id, W_EResep_Racikan.Dosis
				FROM            W_EResep INNER JOIN
				W_EResep_R ON W_EResep.Id = W_EResep_R.IdResep LEFT OUTER JOIN
				W_EResep_Racikan ON W_EResep_R.Id = W_EResep_Racikan.IdR LEFT OUTER JOIN
				AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
				WHERE        (W_EResep.Noreg = '$noreg') AND (W_EResep.Kategori LIKE '%KRS%') and W_EResep_R.DeletedBy is NULL
				union
				SELECT Noreg,'' as Jenis,'' as KodeR, jumlah,aturan_pakai,'' as CaraPakai,'' as WaktuPakai, '' as Nama, instruksi_khusus, '' as Keterangan, nama_obat, Id, '' as Dosis
				FROM ERM_RI_DISCHARGE where noreg='$noreg'
				order by nama_obat
				";

				$hasil  = sqlsrv_query($conn, $q);  
				$no=1;
				while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
					// echo $no.' - '.$data[nama_obat].' '.$data[jumlah].' '.$data[aturan_pakai].' '.$data[instruksi_khusus];echo "<br>";

					if($data['nama_obat'] == ''){
						$nama_obat=$data['Nama'];
					}else{
						$nama_obat=$data['nama_obat'];
					}

					echo "
					<tr>
					<td>$no</td>
					<td>$nama_obat</td>
					<td>$data[jumlah]</td>
					<td>$data[aturan_pakai]</td>
					<td>$data[instruksi_khusus]</td>
					</tr>
					";
					$no+=1;
				}

				?>
			</table>

		</div>

	</div>

	<hr>

	<div class="row">
		<div class="col-12">
			<b>Keterangan : <br>
				Jml : Jumlah, P : Pagi, Si : Siang, So : Sore, M : malam
			</b>
		</div>
	</div>

	<?php 
	if ($doc){
		?>
		<tr>
			<td colspan="2" align='center'>
				<div class="row">
					<div class="col-12">
						Gambar Upload<br>
						<?php 
						$q		= "select doc as doc from ERM_RI_RESUME where noreg = '$noreg'";
						$h  = sqlsrv_query($conn, $q);			  
						$d  = sqlsrv_fetch_array($h, SQLSRV_FETCH_ASSOC); 
						$doc = trim($d['doc']);
						?>						
						<img src="<?php echo $doc;?>" width='300px'>	
						<br>			
					</div>
				</div>

			</td>
		</tr>
		<?php 
}//end if dok
?>
<div class="row">
	<div class="col-4">
		Verifikasi Apoteker Pemberi Resume
	</div>
	<div class="col-8">
		<?php 
		$row = explode('-',$resume133);$resume133  = $row[1];
		echo $resume133." Tanggal : ".$resume4; ?>

	</div>
</div>

<div class="row">
	<div class="col-12">
		<?php 
		if($resume133){
			$verif_apoteker="Resume medis ini telah diVerifikasi Oleh : ".$resume133." Pada Tanggal : ".$resume4.', '.$jamentry; 
			// echo "<center><img alt='ttd' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_apoteker&choe=UTF-8'/></center>";

			QRcode::png($verif_apoteker, "image_apoteker.png", "L", 2, 2);   
			echo "<center><img src='image_apoteker.png'></center>";

		}
		?>
	</div>
</div>


<hr>

<div class="row">
	<div class="col-12">
		<table border='0' bordercolor='green'>
			<tr>
				<td>Telah membaca dan mengerti,</td>
			</tr>
			<tr>
				<td>Nama & Tanda Tangan 
					Pasien/keluarga,
				</td>
			</tr>
			<tr>
				<td>
					<!-- <input type='text' name='keluarga' value='<?php echo $keluarga; ?>' size='50' placeholder='keluarga'> -->
				</td>
			</tr>
		</table>			
		<!-- <input type='submit' name='simpan' value='Konfirmasi Persetujuan Pasien' style="height: 90px;width: 300px;color: white;background: green"> -->
		
	</div>

</div>

<div class="row">
	<div class="col-4">
		Verifikasi Keluarga Pasien
	</div>
	<div class="col-8">
		<?php echo $nama." / Tanggal : ".$resume4; ?>

	</div>
</div>

<div class="row">
	<div class="col-12 text-center">
		<?php 
		if($resume134){
			echo " <img src='$resume134' height='250' width='250'>";
			echo "<br><br>";
			echo "<input type='text' name='resume134' value='$resume134' size='50' hidden>";
		}
		?>
	</div>
</div>

<?php 

$qdoc="SELECT        TOP (200) noreg, jenis, doc FROM            document_erm where noreg='$noreg'";
$hqdoc  = sqlsrv_query($conn, $qdoc);  
while   ($dhqdoc = sqlsrv_fetch_array($hqdoc,SQLSRV_FETCH_ASSOC)){ 
	$doc	= 'http://192.168.10.109/dok_erm/'.$dhqdoc[doc];
	$jenis = $dhqdoc[jenis];
	$row3 = explode('.',$dhqdoc[doc]);
	$file  = $row3[1];

	if($file=='png' or $file=='PNG' or $file=='jpg' or $file=='JPG' or $file=='jpeg' or $file=='JPEG' ){
		echo "
		<div style='page-break-before: always;'>
		$jenis<br>
		<img src='$doc' width='100%'>
		</div>
		";
	}
}
?>

<!-- <div class="row">
	<div class="col-12">
		Rangkap 4<br>
		Asli 		: Status RM<br>
		Rangkap 1	: Pasien<br>
		Rangkap 2	: Asuransi (bila ada)<br>
		Rangkap 3	: Dokter Keluarga/Faskes Tk 1(bila ada)<br>
	</div>
</div> -->

<br><br><br>
</font>
</form>
</font>
</body>
</div>
</div>
