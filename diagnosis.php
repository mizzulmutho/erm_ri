<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
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


$qi="SELECT noreg FROM ERM_RI_RESUME where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_RESUME(noreg,userid,tglentry) values ('$noreg','$user','$tglinput')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume
	FROM ERM_RI_RESUME
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tglresume = $de['tglresume'];
	
	$resume20= $de['resume20'];
	$resume21= $de['resume21'];
	$resume22= $de['resume22'];
	
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
			$("#obat1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat4").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat5").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat6").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat7").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat8").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat9").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat10").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat11").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat12").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat13").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat14").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat15").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat16").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat17").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat18").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat19").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			$("#obat20").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
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
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-danger'>Pdf</a> -->
					<!-- <input type='submit' name='Pdf' value='Pdf' class='btn btn-danger'> -->
					&nbsp;&nbsp;
					<a href='resume_print.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a>
					<!-- <input type='submit' name='Print' value='Print' class='btn btn-info'> -->

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

<div class="row">
<?php include('menu_dokter.php');?>
</div>
<hr>

			<div class="row">
				<div class="col-12 text-center">
					<b>Diagnosis</b><br>
				</div>
			</div>
			<hr> 
			<div class="row">				

				<div class="col-12">
					Penentuan jenis penyakit
					berdasarkan tanda dan gejala, bisa
					dibantu dengan pemeriksaan
					penunjang / Identifikasi penyakit
					berdasarkan hasil pemeriksaan
					terhadap pasien
				</div>
				<br><br>
				<div class="col-4">
					&bull; Diagnosis Awal / Masuk
				</div>
				<div class="col-8">
					: 
					<input class="" name="resume20" value="<?php echo $resume20;?>" id="icd101" type="text" size='80' onfocus="nextfield ='';" placeholder="ICD 10, ICD 9 CM" style="min-width:150px; min-height:50px;">
                    <br>
                    <font size='4px'>
                        &nbsp;<a href='diagnosis_awal_dokter2.php?id=<?php echo $id.'|'.$user;?>'>(+) Tambah Diagnosa Awal/Masuk</a><br>
                    </font>

                    <?php
                    $qds="SELECT * FROM  ERM_RI_DIAGNOSA_AWAL where noreg='$noreg'";
                    $hqds  = sqlsrv_query($conn, $qds);        
                    $dhqds  = sqlsrv_fetch_array($hqds, SQLSRV_FETCH_ASSOC);            
                    $diagnosa1  = $dhqds['diagnosa_sekunder1'];
                    $diagnosa2  = $dhqds['diagnosa_sekunder2'];
                    $diagnosa3  = $dhqds['diagnosa_sekunder3'];
                    $diagnosa4   = $dhqds['diagnosa_sekunder4'];
                    $diagnosa5   = $dhqds['diagnosa_sekunder5'];
                    $diagnosa6   = $dhqds['diagnosa_sekunder6'];
                    $diagnosa7   = $dhqds['diagnosa_sekunder7'];
                    $diagnosa8   = $dhqds['diagnosa_sekunder8'];
                    $diagnosa9   = $dhqds['diagnosa_sekunder9'];
                    $diagnosa10   = $dhqds['diagnosa_sekunder10'];
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
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-4">
                 &bull; Diagnosis Akhir (Primer)
             </div>
             <div class="col-8">
                 : <input class="" name="resume21" value="<?php echo $resume21;?>" id="icd102" type="text" size='80' onfocus="nextfield ='';" placeholder="ICD 10, ICD 9 CM" style="min-width:150px; min-height:50px;">
             </div>
             <div class="col-4">
                 &bull; Diagnosis Akhir (Sekunder)
             </div>
             <div class="col-8">
                 : <input class="" name="resume22" value="<?php echo $resume22;?>" id="icd103" type="text" size='80' onfocus="nextfield ='periode';" placeholder="ICD 10, ICD 9 CM" style="min-width:150px; min-height:50px;">
                 <br>
                 <font size='4px'>
                    &nbsp;<a href='diagnosis_sekunder_dokter2.php?id=<?php echo $id.'|'.$user;?>'>(+) Tambah Diagnosa Sekunder</a><br>
                </font>

                <?php
                $qds="SELECT * FROM  ERM_RI_DIAGNOSA_SEKUNDER where noreg='$noreg'";
                $hqds  = sqlsrv_query($conn, $qds);        
                $dhqds  = sqlsrv_fetch_array($hqds, SQLSRV_FETCH_ASSOC);            
                $diagnosa1  = $dhqds['diagnosa_sekunder1'];
                $diagnosa2  = $dhqds['diagnosa_sekunder2'];
                $diagnosa3  = $dhqds['diagnosa_sekunder3'];
                $diagnosa4   = $dhqds['diagnosa_sekunder4'];
                $diagnosa5   = $dhqds['diagnosa_sekunder5'];
                $diagnosa6   = $dhqds['diagnosa_sekunder6'];
                $diagnosa7   = $dhqds['diagnosa_sekunder7'];
                $diagnosa8   = $dhqds['diagnosa_sekunder8'];
                $diagnosa9   = $dhqds['diagnosa_sekunder9'];
                $diagnosa10   = $dhqds['diagnosa_sekunder10'];
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
        <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;min-width:100px; min-height:50px;">


        <br><br><br>
    </font>
</form>
</font>
</body>
</div>
</div>

<?php 

if (isset($_POST["simpan"])) {

	$resume20	= $_POST["resume20"];
	$resume21	= $_POST["resume21"];
	$resume22	= $_POST["resume22"];

	$q  = "update ERM_RI_RESUME set
	resume20	='$resume20',
	resume21	='$resume21',
	resume22	='$resume22'
	where noreg='$noreg'
	";
	$hs = sqlsrv_query($conn,$q);

    $rowb = explode('-',$resume20);
    $resume20b  = trim($rowb[0]);
    $rowc = explode('-',$resume21);
    $resume21b  = trim($rowc[0]);
    $rowd = explode('-',$resume22);
    $resume22b  = trim($rowd[0]);

    $q  = "update ARM_PERIKSA set
    KODEICD    ='$resume20b',
    KODEICD2    ='$resume21c',
    KODEICD3    ='$resume22d'
    where noreg='$noreg' and kodeunit='$KODEUNIT'
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
