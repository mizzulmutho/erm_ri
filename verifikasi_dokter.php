<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl        = gmdate("Y-m-d", time()+60*60*7);
$tglinput       = gmdate("Y-m-d H:i:s", time()+60*60*7);

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
                            <?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
                        </div>
                    </div>

                    <div class="row">
                        <?php //include('menu_dokter.php');?>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-12 text-center">
                            <b>VERIFIKASI DOKTER</b><br>
                            <br>
                            <?php

                            $kodedokter  = substr($user,0,3);

                            $qd="SELECT    NAMA  FROM            Afarm_DOKTER
                            WHERE        kodedokter='$kodedokter'";
                            $hd  = sqlsrv_query($conn, $qd);        
                            $dd  = sqlsrv_fetch_array($hd, SQLSRV_FETCH_ASSOC); 
                            $namadokter = trim($dd['NAMA']);

                            ?>
                            Mohon bagi DPDP : <b><?php echo $namadokter; ?></b> untuk terlebih dahulu melakukan <br>verifikasi entryan <b>CPPT / TULBAKON / ASSESMEN AWAL KEPERAWATAN</b> <br >oleh perawat/dokter Jaga untuk kelengkapan informasi di ERM
                        </div>
                    </div>
                    <hr> 
                    <div class="row">
                        <div class="col-12 text-center">
                            <?php
                            $kodedokter  = substr($user,0,3);

                            $qv="
                            SELECT        TOP (1) dpjp as dokter
                            FROM            ERM_SOAP
                            WHERE        verif_dpjp is null AND (dpjp = '$kodedokter') AND (tanggal > '2025-03-04')
                            union
                            SELECT        TOP (1) dokter
                            FROM            ERM_TULBAKON
                            WHERE        verif_dpjp is null AND (dokter = '$kodedokter') AND (tglentry > '2025-03-04')
                            union
                            SELECT TOP (1) dpjp as dokter
                            FROM ERM_RI_ASSESMEN_AWAL_DEWASA
                            WHERE tglverif IS NULL 
                            AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$kodedokter'
                            AND tglentry > '2025-03-04'
                            union
                            SELECT TOP (1) dpjp as dokter
                            FROM ERM_RI_ASSESMEN_AWAL_ANAK
                            WHERE tglverif IS NULL 
                            AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$kodedokter'
                            AND tglentry > '2025-03-04'
                            union
                            SELECT TOP (1) dpjp as dokter
                            FROM ERM_RI_ASSESMEN_AWAL_NEONATUS
                            WHERE tglverif IS NULL 
                            AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$kodedokter'
                            AND tglentry > '2025-03-04'
                            ";
                            $hv  = sqlsrv_query($conn, $qv);        
                            $dv  = sqlsrv_fetch_array($hv, SQLSRV_FETCH_ASSOC); 
                            $cekverif = trim($dv['dokter']);

                            if($cekverif){
                                echo "
                                <button type='submit' name='verifikasi' class='btn btn-danger btn-md'> <i class='bi bi-check-circle-fill'></i> Verifikasi Berkas ERM</button>
                                ";
                            }


                            ?>
                        </div>
                        <br><br>
                    </div>
                    <br><br><br>
                </font>
            </form>
        </font>
    </body>
</div>
</div>

<?php 

if (isset($_POST["verifikasi"])) {

    //verifikasi assesment awal
    $q  = "update ERM_RI_ASSESMEN_AWAL_DEWASA set
    tglverif    ='$tglinput'
    where tglentry > '2025-03-04' AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$kodedokter' and tglverif is null
    ";
    $hs = sqlsrv_query($conn,$q);

    $q  = "update ERM_RI_ASSESMEN_AWAL_ANAK set
    tglverif    ='$tglinput'
    where tglentry > '2025-03-04' AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$kodedokter' and tglverif is null
    ";
    $hs = sqlsrv_query($conn,$q);

    $q  = "update ERM_RI_ASSESMEN_AWAL_NEONATUS set
    tglverif    ='$tglinput'
    where tglentry > '2025-03-04' AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$kodedokter' and tglverif is null
    ";
    $hs = sqlsrv_query($conn,$q);

    //verifikasi tulbakon
    $q  = "update ERM_TULBAKON set
    verif_dpjp    ='$tglinput'
    where ERM_TULBAKON.tglentry > '2025-03-04' and dokter='$kodedokter' and verif_dpjp is null
    ";
    $hs = sqlsrv_query($conn,$q);

    //verifikasi cppt
    $q  = "update ERM_SOAP set
    verif_dpjp    ='$tglinput'
    where ERM_SOAP.tanggal > '2025-03-04' and dpjp='$kodedokter' and verif_dpjp is null
    ";
    $hs = sqlsrv_query($conn,$q);

    $q  = "insert into ERM_SOAP_VERIF
    (noreg, tanggal, userid, tglverif, userverif) 
    values 
    ('$noreg','$tglinput','$kodedokter','$tglinput','$user')";
    $hs = sqlsrv_query($conn,$q);

    if($hs){
        $eror = "Success - Terima kasih telah memverifikasi berkas ERM";
    }else{
      $eror = "Gagal Insert";

  }

  echo "
  <script>
  window.location.replace('index.php?id=$id|$user');
  </script>
  ";

}



?>
