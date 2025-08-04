<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);
$tglentry		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idtransfer = $row[2];

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
$sbu = $KET1;

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


$qe="
SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
FROM ERM_RI_TRANSFER
where id='$idtransfer'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$tgl = $de['tgl'];
$tr1 = $de['tr1'];
$tr2= $de['tr2'];
$tr3= $de['tr3'];
$tr4= $de['tr4'];
$tr5= $de['tr5'];
$tr6= $de['tr6'];
$tr7= $de['tr7'];
$tr8= $de['tr8'];
$tr9= $de['tr9'];
$tr10= $de['tr10'];
$tr11= $de['tr11'];
$tr12= $de['tr12'];
$tr13= $de['tr13'];
$tr14= $de['tr14'];
$tr15= $de['tr15'];
$tr16= $de['tr16'];
$tr17= $de['tr17'];
$tr18= $de['tr18'];
$tr19= $de['tr19'];
$tr20= $de['tr20'];
$tr21= $de['tr21'];
$tr22= $de['tr22'];
$tr23= $de['tr23'];
$tr24= $de['tr24'];
$tr25= $de['tr25'];
$tr26= $de['tr26'];
$tr27= $de['tr27'];
$tr28= $de['tr28'];
$tr29= $de['tr29'];
$tr30= $de['tr30'];
$tr31= $de['tr31'];
$tr32= $de['tr32'];
$tr33= $de['tr33'];
$tr34= $de['tr34'];
$tr35= $de['tr35'];
$tr36= $de['tr36'];
$tr37= $de['tr37'];
$tr38= $de['tr38'];
$tr39= $de['tr39'];
$tr40= $de['tr40'];
$tr41= $de['tr41'];
$tr42= $de['tr42'];
$tr43= $de['tr43'];
$tr44= $de['tr44'];
$tr45= $de['tr45'];
$tr46= $de['tr46'];
$tr47= $de['tr47'];
$tr48= $de['tr48'];
$tr49= $de['tr49'];
$tr50= $de['tr50'];
$tr51= $de['tr51'];
$tr52= $de['tr52'];
$tr53= $de['tr53'];
$tr54= $de['tr54'];
$tr55= $de['tr55'];
$tr56= $de['tr56'];
$tr57= $de['tr57'];
$tr58= $de['tr58'];
$tr59= $de['tr59'];
$tr60= $de['tr60'];
$tr61= $de['tr61'];
$tr62= $de['tr62'];
$tr63= $de['tr63'];
$tr64= $de['tr64'];
$tr65= $de['tr65'];
$tr66= $de['tr66'];
$tr67= $de['tr67'];
$tr68= $de['tr68'];
$tr69= $de['tr69'];
$tr70= $de['tr70'];
$tr71= $de['tr71'];
$tr72= $de['tr72'];
$tr73= $de['tr73'];
$tr74= $de['tr74'];
$tr75= $de['tr75'];
$tr76= $de['tr76'];
$tr77= $de['tr77'];
$tr78= $de['tr78'];
$tr79= $de['tr79'];
$tr80= $de['tr80'];
$tr81= $de['tr81'];
$tr82= $de['tr82'];
$tr83= $de['tr83'];
$tr84= $de['tr84'];
$tr85= $de['tr85'];
$tr86= $de['tr86'];
$tr87= $de['tr87'];
$tr88= $de['tr88'];
$tr89= $de['tr89'];
$tr90= $de['tr90'];
$tr91= $de['tr91'];
$tr92= $de['tr92'];
$tr93= $de['tr93'];
$tr94= $de['tr94'];
$tr95= $de['tr95'];
$tr96= $de['tr96'];
$tr97= $de['tr97'];
$tr98= $de['tr98'];
$tr99= $de['tr99'];
$tr100= $de['tr100'];
$tr101= $de['tr101'];
$tr102= $de['tr102'];
$tr103= $de['tr103'];
$tr104= $de['tr104'];
$tr105= $de['tr105'];
$tr106= $de['tr106'];
$tr107= $de['tr107'];
$tr108= $de['tr108'];
$tr109= $de['tr109'];
$tr110= $de['tr110'];
$tr111= $de['tr111'];
$tr112= $de['tr112'];
$tr113= $de['tr113'];
$tr114= $de['tr114'];
$tr115= $de['tr115'];
$tr116= $de['tr116'];
$tr117= $de['tr117'];
$tr118= $de['tr118'];
$tr119= $de['tr119'];
$tr120= $de['tr120'];
$tr121= $de['tr121'];
$tr122= $de['tr122'];
$tr123= $de['tr123'];
$tr124= $de['tr124'];
$tr125= $de['tr125'];
$tr126= $de['tr126'];
$tr127= $de['tr127'];
$tr128= $de['tr128'];
$tr129= $de['tr129'];
$tr130= $de['tr130'];
$tr131= $de['tr131'];
$tr132= $de['tr132'];
$tr133= $de['tr133'];
$tr134= $de['tr134'];
$tr135= $de['tr135'];
$tr136= $de['tr136'];
$tr137= $de['tr137'];
$tr138= $de['tr138'];
$tr139= $de['tr139'];
$tr140= $de['tr140'];
$tr141= $de['tr141'];
$tr142= $de['tr142'];
$tr143= $de['tr143'];
$tr144= $de['tr144'];
$tr145= $de['tr145'];
$tr146= $de['tr146'];
$tr147= $de['tr147'];
$tr148= $de['tr148'];
$tr149= $de['tr149'];
$tr150= $de['tr150'];


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Transfer Pasien</title>  
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
			$("#dokter3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter1.php', //your                         
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
			$("#dokter4").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter1.php', //your                         
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
			$("#dokter5").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter1.php', //your                         
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
			$("#diag_keperawatan1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan4").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan5").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#dariunit").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_unit.php?id=<?php echo $sbu; ?>', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.namaunit + ' - ' + item.kodeunit
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
			$("#keunit").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_unit.php?id=<?php echo $sbu; ?>', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.namaunit + ' - ' + item.kodeunit
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
					<a href='transfer_pasien.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
                    <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
                    &nbsp;&nbsp;


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
					<?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col-12 text-center">
					<b>DETAIL TRANSFER PASIEN ANTAR UNIT PELAYANAN</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='0'>
				
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Tanggal
							</div>
							<div class="col-8">
								<!-- <input type='date' name='tgl' value='<?php echo $tgl;?>'> -->
								<input class="" name="tgl" value="<?php echo $tglinput;?>" type="text" >
							</div>
						</div>
					</td>
				</tr>	
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Dari Unit
							</div>
							<div class="col-8">							
								<input class="" name="tr1" value="<?php echo $tr1;?>" id="dariunit" type="text" size='80' placeholder="" required>
							</div>
						</div>
					</td>
				</tr>	
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Ke Unit
							</div>
							<div class="col-8">							
								<input class="" name="tr2" value="<?php echo $tr2;?>" id="keunit" type="text" size='80' placeholder="" required>
							</div>
						</div>
					</td>
				</tr>	
				<tr>
					<td><hr></td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Nama Dokter
							</div>
							<div class="col-8">
								<input class="" name="tr3" value="<?php echo $tr3;?>" id="dokter3" type="text" size='30' placeholder="Nama Dokter 1">
								<input class="" name="tr4" value="<?php echo $tr4;?>" id="dokter4" type="text" size='30' placeholder="Nama Dokter 2">
								<input class="" name="tr5" value="<?php echo $tr5;?>" id="dokter5" type="text" size='30' placeholder="Nama Dokter 3">
							</div>
						</div>
					</td>
				</tr>	
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Diagnosis Medik
							</div>
							<div class="col-8">
								<input class="" name="tr6" value="<?php echo $tr6;?>" id="icd101" type="text" size='80' placeholder="Diagnosa 1">
								<input class="" name="tr7" value="<?php echo $tr7;?>" id="icd102" type="text" size='80' placeholder="Diagnosa 2">
								<input class="" name="tr8" value="<?php echo $tr8;?>" id="icd103" type="text" size='80' placeholder="Diagnosa 3">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Alasan Pemindahan
							</div>
							<div class="col-8"> 
								<textarea name= "tr9" id="" style="min-width:630px; min-height:70px;"><?php echo $tr9;?></textarea>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Riwayat Penyakit Dahulu
							</div>
							<div class="col-8">
								<!-- <input class="" name="tr10" value="<?php echo $tr10;?>" id="" type="text" size='90' placeholder=""> -->
								<textarea name= "tr10" id="" style="min-width:630px; min-height:70px;"><?php echo $tr10;?></textarea>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Obat-obatan rutin
							</div>
							<div class="col-8">
								<!-- <input class="" name="tr11" value="<?php echo $tr11;?>" id="" type="text" size='90' placeholder=""> -->
								<textarea name= "tr11" id="" style="min-width:630px; min-height:70px;"><?php echo $tr11;?></textarea>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Alergi
							</div>
							<div class="col-8">
								<input class="" name="tr12" value="<?php echo $tr12;?>" id="" type="text" size='90' placeholder="">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Observasi terakhir
							</div>
							<div class="col-8">
								<table width="100%" border='0'>
									<tr>
										<td>Jam :</td>
										<td><input class="" name="tglinput" value="<?php echo $tglinput;?>" type="text" ></td>
									</tr>
									<tr>
										<td>Kesadaran/ Orientasi</td>
										<td>
											<?php 
											if($tr13){
												echo "<input type='text' name='tr13' value='$tr13' size='30'>";
											}else{
												if($idtransfer){
													echo "<input type='text' name='tr13' value='$tr13' size='30'>";
												}else{
													echo "
													<select name='tr13' style='min-width:250px; min-height:30px;''>
													<option value=''>--pilih--</option>
													<option value='Composmentis'>Composmentis</option>
													<option value='Apatis'>Apatis</option>
													<option value='Somnolent'>Somnolent</option>
													<option value='Sopor'>Sopor</option>
													<option value='Coma'>Coma</option>
													</select>
													";
												}
											}
											?>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											Suhu
											<input class="" name="tr20" value="<?php echo $tr20;?>" id="" type="text" size='10' placeholder=""> 0C

											<tr>
												<td>GCS</td>
												<td>
													E <input class="" name="tr14" value="<?php echo $tr14;?>" id="" type="text" size='10' placeholder=""> 
													V <input class="" name="tr15" value="<?php echo $tr15;?>" id="" type="text" size='10' placeholder=""> 
													M <input class="" name="tr16" value="<?php echo $tr16;?>" id="" type="text" size='10' placeholder="">
													Total <input class="" name="total" value="<?php echo $total;?>" id="" type="text" size='10' placeholder="">
												</td>
											</tr>
											<tr>
												<td>Tekanan Darah</td>
												<td>
													<input class="" name="tr17" value="<?php echo $tr17;?>" id="" type="text" size='10' placeholder=""> mmHg
												</td>
											</tr>
											<tr>
												<td>Nadi</td>
												<td>
													<input class="" name="tr18" value="<?php echo $tr18;?>" id="" type="text" size='10' placeholder=""> x/mnt
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													Ritme :
													<?php 
													if($tr19){
														echo "<input type='text' name='tr19' value='$tr19' size='30'>";
													}else{
														echo "
														<select name='tr19' style='min-width:250px; min-height:30px;''>
														<option value=''>--pilih--</option>
														<option value='Teratur'>Teratur</option>
														<option value='Tidak Teratur'>Tidak Teratur</option>
														</select>
														";
													}
													?>
												</td>
											</tr>	

											<tr>
												<td>Pernafasan</td>
												<td>
													<input class="" name="tr21" value="<?php echo $tr21;?>" id="" type="text" size='10' placeholder=""> x/mnt 
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													SpO2 :
													<input class="" name="tr22" value="<?php echo $tr22;?>" id="" type="text" size='10' placeholder=""> %
													O2 :
													<input class="" name="tr23" value="<?php echo $tr23;?>" id="" type="text" size='10' placeholder=""> ℓ/mnt
												</td>
											</tr>

											<tr>
												<td>Derajat nyeri (0-10)</td>
												<td>
													<input class="" name="tr24" value="<?php echo $tr24;?>" id="" type="text" size='10' placeholder="">
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													Lokasi nyeri
													<input class="" name="tr25" value="<?php echo $tr25;?>" id="" type="text" size='30' placeholder="">
												</td>
											</tr>

											<tr>
												<td>Diet</td>
												<td>
													<?php 
													if($tr26){
														echo "<input type='text' name='tr26' value='$tr26' size='30'>";
													}else{
														if($idtransfer){
															echo "<input type='text' name='tr26' value='$tr26' size='30'>";
														}else{
															echo "
															<select name='tr26' style='min-width:250px; min-height:30px;''>
															<option value=''>--pilih--</option>
															<option value='normal'>normal</option>
															<option value='lunak'>lunak</option>
															<option value='khusus'>khusus</option>
															</select>
															";
														}
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
									<table width='100%' border="1">
										<tr valign="top">
											<td align='center'>Diberikan Sejak</td>
											<td align='center'>Cairan & Obat</td>
											<td align='center'>Dosis / Rate</td>
											<td align='center'>Askes</td>
										</tr>
										<tr>
											<td><input class="" name="tr27" value="<?php echo $tr27;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr28" value="<?php echo $tr28;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr29" value="<?php echo $tr29;?>" id="" type="text" size='30' placeholder=""></td>
											<td rowspan="10">
												<input type='checkbox' name='tr57' value='YA' <?php if ($tr57=="YA"){echo "checked";}?> >IV
												<input type='checkbox' name='tr58' value='YA' <?php if ($tr58=="YA"){echo "checked";}?> >CVC<br>
												<input type='checkbox' name='tr59' value='YA' <?php if ($tr59=="YA"){echo "checked";}?> >Akses dialisis<br>
												<input type='checkbox' name='tr60' value='YA' <?php if ($tr60=="YA"){echo "checked";}?> >Lainnya
												<input class="" name="tr63" value="<?php echo $tr63;?>" id="" type="text" size='30' placeholder=""><br>
												NGT
												<input type='checkbox' name='tr61' value='YA' <?php if ($tr61=="YA"){echo "checked";}?> >Ya
												<input type='checkbox' name='tr62' value='YA' <?php if ($tr62=="YA"){echo "checked";}?> >Tidak
											</td>
										</tr>
										<tr>
											<td><input class="" name="tr30" value="<?php echo $tr30;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr31" value="<?php echo $tr31;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr32" value="<?php echo $tr32;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr33" value="<?php echo $tr33;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr34" value="<?php echo $tr34;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr35" value="<?php echo $tr35;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr36" value="<?php echo $tr36;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr37" value="<?php echo $tr37;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr38" value="<?php echo $tr38;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr39" value="<?php echo $tr39;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr40" value="<?php echo $tr40;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr41" value="<?php echo $tr41;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr42" value="<?php echo $tr42;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr43" value="<?php echo $tr43;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr44" value="<?php echo $tr44;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr45" value="<?php echo $tr45;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr46" value="<?php echo $tr46;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr47" value="<?php echo $tr47;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr48" value="<?php echo $tr48;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr49" value="<?php echo $tr49;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr50" value="<?php echo $tr50;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr51" value="<?php echo $tr51;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr52" value="<?php echo $tr52;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr53" value="<?php echo $tr53;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>
										<tr>
											<td><input class="" name="tr54" value="<?php echo $tr54;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr55" value="<?php echo $tr55;?>" id="" type="text" size='30' placeholder=""></td>
											<td><input class="" name="tr56" value="<?php echo $tr56;?>" id="" type="text" size='30' placeholder=""></td>
										</tr>

									</table>
								</div>
							</td>
						</tr>


						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Urine
									</div>
									<div class="col-8">
										<input type='checkbox' name='tr63' value='YA' <?php if ($tr63=="YA"){echo "checked";}?> >Continent
										<input type='checkbox' name='tr64' value='YA' <?php if ($tr64=="YA"){echo "checked";}?> >Incontinent
										<input type='checkbox' name='tr65' value='YA' <?php if ($tr65=="YA"){echo "checked";}?> >Foley Cath
										Jumlah/jam <input class="" name="tr66" value="<?php echo $tr66;?>" id="" type="text" size='5' placeholder="">ml 
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Drain
									</div>
									<div class="col-8">
										<input type='checkbox' name='tr67' value='YA' <?php if ($tr67=="YA"){echo "checked";}?> >Drain bag
										Lain-lain <input class="" name="tr68" value="<?php echo $tr68;?>" id="" type="text" size='25' placeholder=""> 
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Bowels 
									</div>
									<div class="col-8">
										<input type='checkbox' name='tr69' value='YA' <?php if ($tr69=="YA"){echo "checked";}?> >Continent
										<input type='checkbox' name='tr70' value='YA' <?php if ($tr70=="YA"){echo "checked";}?> >Incontinent      
										<input type='checkbox' name='tr71' value='YA' <?php if ($tr71=="YA"){echo "checked";}?> >Colostomy bag  
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Total Input 
									</div>
									<div class="col-8">
										<input class="" name="tr72" value="<?php echo $tr72;?>" id="" type="text" size='5' placeholder=""> 
										Total Output <input class="" name="tr73" value="<?php echo $tr73;?>" id="" type="text" size='5' placeholder=""> 
										<input type='checkbox' name='tr74' value='YA' <?php if ($tr74=="YA"){echo "checked";}?> >Tidak dilakukan    
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Mobilisasi 
									</div>
									<div class="col-8">                
										<input type='checkbox' name='tr75' value='YA' <?php if ($tr75=="YA"){echo "checked";}?> >Jalan
										<input type='checkbox' name='tr76' value='YA' <?php if ($tr76=="YA"){echo "checked";}?> >Tirah baring      
										<input type='checkbox' name='tr77' value='YA' <?php if ($tr77=="YA"){echo "checked";}?> >Duduk 
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Kebutuhan khusus pasien 
									</div>
									<div class="col-8">
										<input type='checkbox' name='tr78' value='YA' <?php if ($tr78=="YA"){echo "checked";}?> >Risiko jatuh
										<input type='checkbox' name='tr79' value='YA' <?php if ($tr79=="YA"){echo "checked";}?> >Restrain     
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
									</div>
									<div class="col-8">
										Lokasi luka <input class="" name="tr80" value="<?php echo $tr80;?>" id="" type="text" size='30' placeholder=""> 
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Peralatan khusus yang dibutuhkan  
									</div>
									<div class="col-8">
										<input class="" name="tr81" value="<?php echo $tr81;?>" id="" type="text" size='30' placeholder=""> 
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Kondisi pasien yang perlu diperhatikan   
									</div>
									<div class="col-8">
										<input class="" name="tr82" value="<?php echo $tr82;?>" id="" type="text" size='30' placeholder=""> 
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Diagnosis keperawatan   
									</div>
									<div class="col-8">
										<input class="" name="tr85" value="<?php echo $tr85;?>" id="diag_keperawatan1" type="text" size='40' placeholder="diagnosa_keperawatan1">
										<input type='checkbox' name='tr83' value='YA' <?php if ($tr83=="YA"){echo "checked";}?> >Sudah Teratasi
										<input type='checkbox' name='tr84' value='YA' <?php if ($tr84=="YA"){echo "checked";}?> >Belum Teratasi
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										&nbsp;
									</div>
									<div class="col-8">
										<input class="" name="tr110" value="<?php echo $tr110;?>" id="diag_keperawatan2" type="text" size='40' placeholder="diagnosa_keperawatan2">
										<input type='checkbox' name='tr111' value='YA' <?php if ($tr111=="YA"){echo "checked";}?> >Sudah Teratasi
										<input type='checkbox' name='tr111' value='YA' <?php if ($tr111=="YA"){echo "checked";}?> >Belum Teratasi
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										&nbsp;
									</div>
									<div class="col-8">
										<input class="" name="tr103" value="<?php echo $tr103;?>" id="diag_keperawatan3" type="text" size='40' placeholder="diagnosa_keperawatan3">
										<input type='checkbox' name='tr104' value='YA' <?php if ($tr104=="YA"){echo "checked";}?> >Sudah Teratasi
										<input type='checkbox' name='tr104' value='YA' <?php if ($tr104=="YA"){echo "checked";}?> >Belum Teratasi
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										&nbsp;
									</div>
									<div class="col-8">
										<input class="" name="tr105" value="<?php echo $tr105;?>" id="diag_keperawatan4" type="text" size='40' placeholder="diagnosa_keperawatan4">
										<input type='checkbox' name='tr106' value='YA' <?php if ($tr106=="YA"){echo "checked";}?> >Sudah Teratasi
										<input type='checkbox' name='tr106' value='YA' <?php if ($tr106=="YA"){echo "checked";}?> >Belum Teratasi
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										&nbsp;
									</div>
									<div class="col-8">
										<input class="" name="tr107" value="<?php echo $tr107;?>" id="diag_keperawatan5" type="text" size='40' placeholder="diagnosa_keperawatan5">
										<input type='checkbox' name='tr108' value='YA' <?php if ($tr108=="YA"){echo "checked";}?> >Sudah Teratasi
										<input type='checkbox' name='tr108' value='YA' <?php if ($tr108=="YA"){echo "checked";}?> >Belum Teratasi
									</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Pendamping pasien transfer    
								</div>
								<div class="col-8">
									<input type='checkbox' name='tr109' value='YA' <?php if ($tr109=="YA"){echo "checked";}?> >Dokter
									- Perawat Klinis 
									<input type='checkbox' name='tr115' value='YA' <?php if ($tr115=="YA"){echo "checked";}?> >1 / 
									<input type='checkbox' name='tr112' value='YA' <?php if ($tr112=="YA"){echo "checked";}?> >2 / 
									<input type='checkbox' name='tr113' value='YA' <?php if ($tr113=="YA"){echo "checked";}?> >3 / 
									<input type='checkbox' name='tr114' value='YA' <?php if ($tr114=="YA"){echo "checked";}?> >4
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Konsultasi    
								</div>
								<div class="col-8">
									<textarea name= "tr86" id="" style="min-width:630px; min-height:150px;"><?php echo $tr86;?></textarea>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Rencana Asuhan/tindakan, Rencana Terapi    
								</div>
								<div class="col-8">
									<textarea name= "tr87" id="" style="min-width:630px; min-height:70px;"><?php echo $tr87;?></textarea>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Obat dan dokumen yang disertakan :    
								</div>
								<div class="col-8">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Obat    
								</div>
								<div class="col-8">
									<input type='checkbox' name='tr88' value='Tidak' <?php if ($tr88=="Tidak"){echo "checked";}?> >Tidak
									<input type='checkbox' name='tr88' value='Ya' <?php if ($tr88=="Ya"){echo "checked";}?> >Ya
									<input class="" name="tr89" value="<?php echo $tr89;?>" id="" type="text" size='30' placeholder=""> 
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Lab    
								</div>
								<div class="col-8">
									<input type='checkbox' name='tr90' value='Tidak' <?php if ($tr90=="Tidak"){echo "checked";}?> >Tidak
									<input type='checkbox' name='tr90' value='Ya' <?php if ($tr90=="Ya"){echo "checked";}?> >Ya
									<input class="" name="tr91" value="<?php echo $tr91;?>" id="" type="text" size='30' placeholder=""> 
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									X Ray    
								</div>
								<div class="col-8">
									<input type='checkbox' name='tr92' value='Tidak' <?php if ($tr92=="Tidak"){echo "checked";}?> >Tidak
									<input type='checkbox' name='tr92' value='Ya' <?php if ($tr92=="Ya"){echo "checked";}?> >Ya
									<input class="" name="tr93" value="<?php echo $tr93;?>" id="" type="text" size='30' placeholder=""> 
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									ECG  
								</div>
								<div class="col-8">
									<input type='checkbox' name='tr94' value='Tidak' <?php if ($tr94=="Tidak"){echo "checked";}?> >Tidak
									<input type='checkbox' name='tr94' value='Ya' <?php if ($tr94=="Ya"){echo "checked";}?> >Ya
									<input class="" name="tr95" value="<?php echo $tr95;?>" id="" type="text" size='30' placeholder=""> 
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Lainnya  
								</div>
								<div class="col-8">
									<input type='checkbox' name='tr96' value='Tidak' <?php if ($tr96=="Tidak"){echo "checked";}?> >Tidak
									<input type='checkbox' name='tr96' value='Ya' <?php if ($tr96=="Ya"){echo "checked";}?> >Ya
									<input class="" name="tr97" value="<?php echo $tr97;?>" id="" type="text" size='30' placeholder=""> 
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									Barang kepunyaan pasien    
								</div>
								<div class="col-8">
									<textarea name= "tr98" id="" style="min-width:630px; min-height:70px;"><?php echo $tr98;?></textarea>
								</div>
							</div>
						</td>
					</tr>



					<tr>
						<td>
							<table width="100%">
								<tr>
									<td>
										Persetujuan Pasien/ keluarga
										<input class="" name="tr99" value="<?php echo $tr99;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Pasien/Keluarga">
										<?php 
										if($tr99){
											$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh Keluarga Pasien atas nama:'.$tr99.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

											QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
											echo "<center><img src='image.png'></center>";

									// echo $tr99;												
										}
										?>
									</td>
									<td>
										Tanggung jawab DPJP / Dokter Jaga
										<input class="" name="tr100" value="<?php echo $tr100;?>" id="dokter" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Dokter atau Kode Dokter">
										<?php 
										if($tr100){
											$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh DPJP / Dokter Jaga atas nama:'.$tr100.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

											QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
											echo "<center><img src='image.png'></center>";

									// echo $tr100;												
										}
										?>
									</td>
								</tr>
								<tr>
									<td>
										Perawat yang memindahkan,&nbsp;&nbsp;&nbsp;
										<input class="" name="tr101" value="<?php echo $tr101;?>" id="karyawan1" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Petugas">
										<?php 
										if($tr101){
											$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh Perawat atas nama:'.$tr101.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

											QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
											echo "<center><img src='image.png'></center>";

									// echo $tr101;												
										}
										?>
									</td>
									<td>
										Perawat penerima pindahan,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input class="" name="tr102" value="<?php echo $tr102;?>" id="karyawan2" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Petugas">
										<?php 
										if($tr102){
											$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh Perawat atas nama:'.$tr102.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

											QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
											echo "<center><img src='image.png'></center>";
											
									// echo $tr102;												
										}
										?>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									&nbsp;
								</div>
								<div class="col-8">
									<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 50px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
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

    $lanjut='Y';

    $tr1	= $_POST["tr1"];
    $tr2	= $_POST["tr2"];

    $row = explode('-',$tr2);
    $ctr2  = trim($row[1]);

    if(empty($ctr2)){
        $eror='Unit Tujuan Tidak Valid, Gunakan Kode Unit !!!';
        $lanjut='T';
    }

    if($lanjut=='Y'){

        $tr3	= $_POST["tr3"];
        $tr4	= $_POST["tr4"];
        $tr5	= $_POST["tr5"];
        $tr6	= $_POST["tr6"];
        $tr7	= $_POST["tr7"];
        $tr8	= $_POST["tr8"];
        $tr9	= $_POST["tr9"];
        $tr10	= $_POST["tr10"];
        $tr11	= $_POST["tr11"];
        $tr12	= $_POST["tr12"];
        $tr13	= $_POST["tr13"];
        $tr14	= $_POST["tr14"];
        $tr15	= $_POST["tr15"];
        $tr16	= $_POST["tr16"];
        $tr17	= $_POST["tr17"];
        $tr18	= $_POST["tr18"];
        $tr19	= $_POST["tr19"];
        $tr20	= $_POST["tr20"];
        $tr21	= $_POST["tr21"];
        $tr22	= $_POST["tr22"];
        $tr23	= $_POST["tr23"];
        $tr24	= $_POST["tr24"];
        $tr25	= $_POST["tr25"];
        $tr26	= $_POST["tr26"];
        $tr27	= $_POST["tr27"];
        $tr28	= $_POST["tr28"];
        $tr29	= $_POST["tr29"];
        $tr30	= $_POST["tr30"];
        $tr31	= $_POST["tr31"];
        $tr32	= $_POST["tr32"];
        $tr33	= $_POST["tr33"];
        $tr34	= $_POST["tr34"];
        $tr35	= $_POST["tr35"];
        $tr36	= $_POST["tr36"];
        $tr37	= $_POST["tr37"];
        $tr38	= $_POST["tr38"];
        $tr39	= $_POST["tr39"];
        $tr40	= $_POST["tr40"];
        $tr41	= $_POST["tr41"];
        $tr42	= $_POST["tr42"];
        $tr43	= $_POST["tr43"];
        $tr44	= $_POST["tr44"];
        $tr45	= $_POST["tr45"];
        $tr46	= $_POST["tr46"];
        $tr47	= $_POST["tr47"];
        $tr48	= $_POST["tr48"];
        $tr49	= $_POST["tr49"];
        $tr50	= $_POST["tr50"];
        $tr51	= $_POST["tr51"];
        $tr52	= $_POST["tr52"];
        $tr53	= $_POST["tr53"];
        $tr54	= $_POST["tr54"];
        $tr55	= $_POST["tr55"];
        $tr56	= $_POST["tr56"];
        $tr57	= $_POST["tr57"];
        $tr58	= $_POST["tr58"];
        $tr59	= $_POST["tr59"];
        $tr60	= $_POST["tr60"];
        $tr61	= $_POST["tr61"];
        $tr62	= $_POST["tr62"];
        $tr63	= $_POST["tr63"];
        $tr64	= $_POST["tr64"];
        $tr65	= $_POST["tr65"];
        $tr66	= $_POST["tr66"];
        $tr67	= $_POST["tr67"];
        $tr68	= $_POST["tr68"];
        $tr69	= $_POST["tr69"];
        $tr70	= $_POST["tr70"];
        $tr71	= $_POST["tr71"];
        $tr72	= $_POST["tr72"];
        $tr73	= $_POST["tr73"];
        $tr74	= $_POST["tr74"];
        $tr75	= $_POST["tr75"];
        $tr76	= $_POST["tr76"];
        $tr77	= $_POST["tr77"];
        $tr78	= $_POST["tr78"];
        $tr79	= $_POST["tr79"];
        $tr80	= $_POST["tr80"];
        $tr81	= $_POST["tr81"];
        $tr82	= $_POST["tr82"];
        $tr83	= $_POST["tr83"];
        $tr84	= $_POST["tr84"];
        $tr85	= $_POST["tr85"];
        $tr86	= $_POST["tr86"];
        $tr87	= $_POST["tr87"];
        $tr88	= $_POST["tr88"];
        $tr89	= $_POST["tr89"];
        $tr90	= $_POST["tr90"];
        $tr91	= $_POST["tr91"];
        $tr92	= $_POST["tr92"];
        $tr93	= $_POST["tr93"];
        $tr94	= $_POST["tr94"];
        $tr95	= $_POST["tr95"];
        $tr96	= $_POST["tr96"];
        $tr97	= $_POST["tr97"];
        $tr98	= $_POST["tr98"];
        $tr99	= $_POST["tr99"];
        $tr100	= $_POST["tr100"];
        $tr101	= $_POST["tr101"];
        $tr102	= $_POST["tr102"];
        $tr103	= $_POST["tr103"];
        $tr104	= $_POST["tr104"];
        $tr105	= $_POST["tr105"];
        $tr106	= $_POST["tr106"];
        $tr107	= $_POST["tr107"];
        $tr108	= $_POST["tr108"];
        $tr109	= $_POST["tr109"];
        $tr110	= $_POST["tr110"];
        $tr111	= $_POST["tr111"];
        $tr112	= $_POST["tr112"];
        $tr113	= $_POST["tr113"];
        $tr114	= $_POST["tr114"];
        $tr115	= $_POST["tr115"];
        $tr116	= $_POST["tr116"];
        $tr117	= $_POST["tr117"];
        $tr118	= $_POST["tr118"];
        $tr119	= $_POST["tr119"];
        $tr120	= $_POST["tr120"];
        $tr121	= $_POST["tr121"];
        $tr122	= $_POST["tr122"];
        $tr123	= $_POST["tr123"];
        $tr124	= $_POST["tr124"];
        $tr125	= $_POST["tr125"];
        $tr126	= $_POST["tr126"];
        $tr127	= $_POST["tr127"];
        $tr128	= $_POST["tr128"];
        $tr129	= $_POST["tr129"];
        $tr130	= $_POST["tr130"];
        $tr131	= $_POST["tr131"];
        $tr132	= $_POST["tr132"];
        $tr133	= $_POST["tr133"];
        $tr134	= $_POST["tr134"];
        $tr135	= $_POST["tr135"];
        $tr136	= $_POST["tr136"];
        $tr137	= $_POST["tr137"];
        $tr138	= $_POST["tr138"];
        $tr139	= $_POST["tr139"];
        $tr140	= $_POST["tr140"];
        $tr141	= $_POST["tr141"];
        $tr142	= $_POST["tr142"];
        $tr143	= $_POST["tr143"];
        $tr144	= $_POST["tr144"];
        $tr145	= $_POST["tr145"];
        $tr146	= $_POST["tr146"];
        $tr147	= $_POST["tr147"];
        $tr148	= $_POST["tr148"];
        $tr149	= $_POST["tr149"];
        $tr150	= $_POST["tr150"];


        echo $q  = "update ERM_RI_TRANSFER set
        userid = '$user',tglentry='$tglentry',
        tr1	='$tr1',
        tr2	='$tr2',
        tr3	='$tr3',
        tr4	='$tr4',
        tr5	='$tr5',
        tr6	='$tr6',
        tr7	='$tr7',
        tr8	='$tr8',
        tr9	='$tr9',
        tr10	='$tr10',
        tr11	='$tr11',
        tr12	='$tr12',
        tr13	='$tr13',
        tr14	='$tr14',
        tr15	='$tr15',
        tr16	='$tr16',
        tr17	='$tr17',
        tr18	='$tr18',
        tr19	='$tr19',
        tr20	='$tr20',
        tr21	='$tr21',
        tr22	='$tr22',
        tr23	='$tr23',
        tr24	='$tr24',
        tr25	='$tr25',
        tr26	='$tr26',
        tr27	='$tr27',
        tr28	='$tr28',
        tr29	='$tr29',
        tr30	='$tr30',
        tr31	='$tr31',
        tr32	='$tr32',
        tr33	='$tr33',
        tr34	='$tr34',
        tr35	='$tr35',
        tr36	='$tr36',
        tr37	='$tr37',
        tr38	='$tr38',
        tr39	='$tr39',
        tr40	='$tr40',
        tr41	='$tr41',
        tr42	='$tr42',
        tr43	='$tr43',
        tr44	='$tr44',
        tr45	='$tr45',
        tr46	='$tr46',
        tr47	='$tr47',
        tr48	='$tr48',
        tr49	='$tr49',
        tr50	='$tr50',
        tr51	='$tr51',
        tr52	='$tr52',
        tr53	='$tr53',
        tr54	='$tr54',
        tr55	='$tr55',
        tr56	='$tr56',
        tr57	='$tr57',
        tr58	='$tr58',
        tr59	='$tr59',
        tr60	='$tr60',
        tr61	='$tr61',
        tr62	='$tr62',
        tr63	='$tr63',
        tr64	='$tr64',
        tr65	='$tr65',
        tr66	='$tr66',
        tr67	='$tr67',
        tr68	='$tr68',
        tr69	='$tr69',
        tr70	='$tr70',
        tr71	='$tr71',
        tr72	='$tr72',
        tr73	='$tr73',
        tr74	='$tr74',
        tr75	='$tr75',
        tr76	='$tr76',
        tr77	='$tr77',
        tr78	='$tr78',
        tr79	='$tr79',
        tr80	='$tr80',
        tr81	='$tr81',
        tr82	='$tr82',
        tr83	='$tr83',
        tr84	='$tr84',
        tr85	='$tr85',
        tr86	='$tr86',
        tr87	='$tr87',
        tr88	='$tr88',
        tr89	='$tr89',
        tr90	='$tr90',
        tr91	='$tr91',
        tr92	='$tr92',
        tr93	='$tr93',
        tr94	='$tr94',
        tr95	='$tr95',
        tr96	='$tr96',
        tr97	='$tr97',
        tr98	='$tr98',
        tr99	='$tr99',
        tr100	='$tr100',
        tr101	='$tr101',
        tr102	='$tr102',
        tr103	='$tr103',
        tr104	='$tr104',
        tr105	='$tr105',
        tr106	='$tr106',
        tr107	='$tr107',
        tr108	='$tr108',
        tr109	='$tr109',
        tr110	='$tr110',
        tr111	='$tr111',
        tr112	='$tr112',
        tr113	='$tr113',
        tr114	='$tr114',
        tr115	='$tr115',
        tr116	='$tr116',
        tr117	='$tr117',
        tr118	='$tr118',
        tr119	='$tr119',
        tr120	='$tr120',
        tr121	='$tr121',
        tr122	='$tr122',
        tr123	='$tr123',
        tr124	='$tr124',
        tr125	='$tr125',
        tr126	='$tr126',
        tr127	='$tr127',
        tr128	='$tr128',
        tr129	='$tr129',
        tr130	='$tr130',
        tr131	='$tr131',
        tr132	='$tr132',
        tr133	='$tr133',
        tr134	='$tr134',
        tr135	='$tr135',
        tr136	='$tr136',
        tr137	='$tr137',
        tr138	='$tr138',
        tr139	='$tr139',
        tr140	='$tr140',
        tr141	='$tr141',
        tr142	='$tr142',
        tr143	='$tr143',
        tr144	='$tr144',
        tr145	='$tr145',
        tr146	='$tr146',
        tr147	='$tr147',
        tr148	='$tr148',
        tr149	='$tr149',
        tr150	='$tr150'
        where id='$idtransfer'
        ";

        $hs = sqlsrv_query($conn,$q);


        $q  = "update ERM_ASSESMEN_HEADER set kodeunit='$ctr2' where noreg='$noreg'";
        $hs = sqlsrv_query($conn,$q);

        if($hs){
            $eror = 'Success';            
        }else{
            $eror = 'Gagal';            
        }
    }else{

    }

    echo "
    <script>
    alert('".$eror."');
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