<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl        = gmdate("Y-m-d", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);
$tglentry       = gmdate("Y-m-d H:i:s", time()+60*60*7);
$tglinput       = gmdate("Y-m-d H:i:s", time()+60*60*7);

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


$noreg_igd = substr($noreg, 1,12);

$qe="
SELECT        TOP (100) ID,idheader,norm
FROM            ERM_TRANSFER_PASIEN
where noreg = '$noreg_igd'
";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$id_trs = $de['ID'];
$id_headerigd = $de['idheader'];
$id_norm = $de['norm'];

$link_transfer_igd = "http://192.168.10.245/transferpx/indexrspg.php?id=$id_norm&noreg=$noreg_igd&idheader=$id_headerigd";
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
            $("#k_unit").autocomplete({
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
            $("#d_unit").autocomplete({
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transfer Pasien</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border-radius: 15px;
        }
        .table thead th {
            background-color: #495057;
            color: white;
        }
    </style>
</head>
<body onload="document.myForm.pasien_mcu.focus();">

    <div class="container mt-5 mb-5">
        <form method="POST" name="myForm" enctype="multipart/form-data">
            <div class="d-flex justify-content-start mb-3">
                <a href="index.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-warning me-2">
                    <i class="bi bi-x-circle-fill"></i> Tutup
                </a>
                <a href="" class="btn btn-success me-2">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
                <button type="submit" name="print" value="print" class="btn btn-info me-2">
                    <i class="bi bi-printer-fill"></i> Cetak
                </button>
            </div>

            <!-- Informasi Pasien -->
            <div class="card shadow-sm p-4 mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="fw-bold"><?php echo $nmrs; ?></h5>
                        <?php echo $alamat; ?>
                    </div>
                    <div class="col-md-6">
                        <p>
                            <strong>NIK:</strong> <?php echo $noktp; ?><br>
                            <strong>Nama Lengkap:</strong> <?php echo $nama; ?> <br>
                            <strong>No RM:</strong> <?php echo $norm; ?><br>
                            <strong>Tgl Lahir:</strong> <?php echo $tgllahir; ?> (<?php echo $umur; ?> tahun)<br>
                            <strong>Jenis Kelamin:</strong> <?php echo $kelamin; ?><br>
                            <strong>Alamat:</strong> <?php echo $alamatpasien; ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Judul -->
            <div class="text-center mb-4">
                <h4 class="fw-bold text-primary">Transfer Pasien Antar Unit Pelayanan</h4>
            </div>

            <!-- Form Transfer -->
            <div class="card shadow-sm p-4 mb-4">
                <div class="row mb-3">
                    <label class="col-md-2 col-form-label">Tanggal</label>
                    <div class="col-md-4">
                        <input class="form-control" name="tgl" type="text" value="<?php echo $tglinput; ?>">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" name="simpan" value="simpan" class="btn btn-primary btn-lg me-3">
                        <i class="bi bi-save2-fill"></i> Buat Form Transfer Baru
                    </button>

                    <?php if($id_trs): ?>
                        <button type="submit" name="terima_transfer" value="terima_transfer" class="btn btn-warning btn-lg me-3">
                            <i class="bi bi-download"></i> Terima Form dari IGD
                        </button>
                        <a href="<?php echo $link_transfer_igd; ?>" target="_blank" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-file-earmark-text"></i> Lembar Pemeriksaan IGD
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Riwayat Transfer -->
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Riwayat Transfer</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Reg</th>
                            <th>Tanggal</th>
                            <th>Dari Unit</th>
                            <th>Ke Unit</th>
                            <th class="text-center">Edit</th>
                            <th class="text-center">Hapus</th>
                            <th class="text-center">Detail IGD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q="
                        select TOP(100) userid,CONVERT(VARCHAR, tglentry, 25) as tglentry,id,noreg,
                        CONVERT(VARCHAR, [tgl], 103) as [tgl],tr1,tr2
                        from ERM_RI_TRANSFER
                        where noreg='$noreg' order by id desc
                        ";
                        $hasil  = sqlsrv_query($conn, $q);  
                        $no = 1;
                        while ($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data['noreg']; ?></td>
                                <td><?php echo $data['tgl']; ?></td>
                                <td><?php echo $data['tr1']; ?></td>
                                <td><?php echo $data['tr2']; ?></td>
                                <td class="text-center">
                                    <a href="e_transfer.php?id=<?php echo $id.'|'.$user.'|'.$data['id']; ?>">
                                        <i class="bi bi-pencil-square text-success"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="del_transfer.php?id=<?php echo $id.'|'.$user.'|'.$data['id']; ?>">
                                        <i class="bi bi-trash-fill text-danger"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="transfer_pasien_list.php?id=<?php echo $id.'|'.$user.'|'.$data['id']; ?>">
                                        <i class="bi bi-arrows-angle-expand text-info"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



<?php 

if (isset($_POST["terima_transfer"])) {

    $regugd=substr($noreg, 1,12);

    $qrPerawatPenerima = "SELECT NamaUser from ROLERSPGENTRY.dbo.tblusererm WHERE user1 ='$user'";
    $hqrPerawatPenerima = sqlsrv_query($conn, $qrPerawatPenerima);
    $dqrPerawatPenerima = sqlsrv_fetch_array($hqrPerawatPenerima, SQLSRV_FETCH_ASSOC);
    $namaPerawatPenerima = $dqrPerawatPenerima['NamaUser'];

    echo    $q  = "update ERM_TRANSFER_PASIEN set PERAWATPENERIMA='$namaPerawatPenerima' where noreg='$regugd'";
    $hs = sqlsrv_query($conn,$q);

    if($hs){
        $eror = "Success";
    }else{
        $eror = "Gagal Insert";
    }

    echo "
    <script>
    alert('".$eror."');
    window.location.replace('transfer_pasien.php?id=$id|$user');
    </script>
    ";

}

if (isset($_POST["simpan"])) {

    $tgl    = $_POST["tgl"];
    $d_unit = $_POST["d_unit"];
    $k_unit = $_POST["k_unit"];

    $q  = "insert into ERM_RI_TRANSFER(noreg,userid,tglentry,tgl,tr1,tr2) values ('$noreg','$user','$tglentry','$tgl','$d_unit','$k_unit')";
    $hs = sqlsrv_query($conn,$q);

    if($hs){

        $eror = "Success";
        $regugd=substr($noreg, 1,12);

        $q  = "update ERM_TRANSFER_PASIEN set PERAWATPENERIMA='$tr102' where noreg='$regugd'";
        $hs = sqlsrv_query($conn,$q);


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

// if (isset($_POST["print"])) {
//  echo "
//  <script>
//  window.print();
//  </script>
//  ";
// }


?>