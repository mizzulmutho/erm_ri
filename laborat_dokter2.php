<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl        = gmdate("Y-m-d", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);

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


$qi="SELECT noreg FROM ERM_RI_PENUNJANG where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];





$qi="SELECT noreg FROM ERM_RI_PENUNJANG where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];


if(empty($regcek)){
    $q  = "insert into ERM_RI_PENUNJANG(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
    $hs = sqlsrv_query($conn,$q);
}else{

    $qe="
    SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
    FROM ERM_RI_PENUNJANG
    where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    // $lab = $de['lab'];
    // $rad = $de['rad'];
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
<!--                <div class="row">
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
                    <b>Pemeriksaan Penunjang (LABORAT)</b><br>
                </div>
            </div>

            <br>

            <table width='100%' border='1'>

                <tr>
                    <td>
                        <div class="row">
                            <div class="col-12">

                                <?php 
                                $noreg_igd = substr($noreg, 1,12);

                                //jumlah
                                $qu="SELECT        TOP (1) DAY(REG_DATE) AS hari
                                FROM            LINKYAN5.SHARELIS.dbo.hasilLIS
                                WHERE        (NOLAB_RS like '%$noreg_igd%')
                                ORDER BY REG_DATE DESC";
                                $h1u  = sqlsrv_query($conn, $qu);        
                                $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
                                $harix = intval($d1u['hari']);
                                $hari = $harix;

                                $hari2 = $hari+1;
                                $hari3 = $hari2+1;
                                $hari4 = $hari3+1;
                                $hari5 = $hari4+1;
                                $hari6 = $hari5+1;
                                $hari7 = $hari6+1;
                                $hari8 = $hari7+1;
                                $hari9 = $hari8+1;
                                $hari10 = $hari9+1;

                                $qlab="
                                SELECT * 
                                FROM 
                                (
                                    SELECT 
                                    DAY(REG_DATE) AS day, 
                                    TARIF_NAME,
                                    KEL_PEMERIKSAAN,
                                    PARAMETER_NAME, 
                                    CASE 
                                    WHEN LEFT(NOLAB_RS, 1) <> 'R' THEN HASIL + FLAG + '(igd)'
                                    ELSE HASIL + FLAG 
                                    END AS HASIL
                                    FROM LINKYAN5.SHARELIS.dbo.hasilLIS
                                    WHERE 
                                    NOLAB_RS like '%$noreg_igd%'
                                    AND DAY(REG_DATE) BETWEEN $hari AND $hari10 
                                    ) AS SourceTable
                                PIVOT
                                (
                                    MAX(HASIL) 
                                    FOR day IN ([$hari], [$hari2], [$hari3], [$hari4], [$hari5], [$hari6], [$hari7], [$hari8], [$hari9], [$hari10])  
                                    ) AS PivotTable;
                                ";
                                $hqlab  = sqlsrv_query($conn, $qlab);

                                echo "<table class='table'>
                                <tr>
                                <td width='1%' bgcolor='#'>no</td>
                                <td>tarif name</td>
                                <td>kel pemeriksaan</td>
                                <td>parameter name</td>
                                <td>tgl:$hari</td>
                                <td>tgl:$hari2</td>
                                <td>tgl:$hari3</td>
                                <td>tgl:$hari4</td>
                                <td>tgl:$hari5</td>
                                <td>tgl:$hari6</td>
                                <td>tgl:$hari7</td>
                                <td>tgl:$hari8</td>
                                <td>tgl:$hari9</td>
                                <td>tgl:$hari10</td>
                                </tr>
                                ";
                                $i=1;
                                while   ($dhqlab = sqlsrv_fetch_array($hqlab, SQLSRV_FETCH_ASSOC)){     
                                    $dhqlab[REG_DATE];
                                    $noreglab = $dhqlab[NOLAB_RS];
                                    // if(substr($noreglab,0,1)<>'R'){
                                    //     $labu='IGD';
                                    // }else{
                                    //     $labu='';
                                    // }
                                    if(substr($dhqlab[PARAMETER_NAME],0,1)=='-'){
                                        $kethlab=' <i>HITUNG JENIS (DIFF) :<i>';
                                    }else if(substr($dhqlab[PARAMETER_NAME],0,1)==' '){
                                        $kethlab=' <i>INDEX ERITROSIT :<i>';
                                    }else{
                                        $kethlab='';
                                    }
                                    echo "
                                    <tr>
                                    <td>$i</td>
                                    <td>$dhqlab[TARIF_NAME]</td>
                                    <td>$dhqlab[KEL_PEMERIKSAAN]</td>
                                    <td>$kethlab $dhqlab[PARAMETER_NAME]</td>
                                    <td>$dhqlab[$hari]</td>
                                    <td>$dhqlab[$hari2]</td>
                                    <td>$dhqlab[$hari3]</td>
                                    <td>$dhqlab[$hari4]</td>
                                    <td>$dhqlab[$hari5]</td>
                                    <td>$dhqlab[$hari6]</td>
                                    <td>$dhqlab[$hari7]</td>
                                    <td>$dhqlab[$hari8]</td>
                                    <td>$dhqlab[$hari9]</td>
                                    <td>$dhqlab[$hari10]</td>
                                    </tr>
                                    ";


                                    $i=$i+1;
                                }


                                ?>

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
                    &nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
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

    $lab    = $_POST["lab"];
    $rad    = $_POST["rad"];

    $q  = "update ERM_RI_PENUNJANG set
    lab ='$lab',rad ='$rad'
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