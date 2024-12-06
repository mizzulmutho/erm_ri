<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl        = gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);

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


$qi="SELECT noreg FROM ERM_RI_REKONOBAT where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
    $q  = "insert into ERM_RI_REKONOBAT(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
    $hs = sqlsrv_query($conn,$q);
}else{

    $qe="
    SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
    FROM ERM_RI_REKONOBAT
    where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    // $lab = $de['lab'];
    // $rad = $de['rad'];
    $rekon1 = $de['rekon1'];
    $rekon2 = $de['rekon2'];
    $rekon3 = $de['rekon3'];
    $rekon4 = $de['rekon4'];
    $rekon5 = $de['rekon5'];
    $rekon6 = $de['rekon6'];
    $rekon7 = $de['rekon7'];
    $rekon8 = $de['rekon8'];
    $rekon9 = $de['rekon9'];
    $rekon10 = $de['rekon10'];
    $rekon11 = $de['rekon11'];
    $rekon12 = $de['rekon12'];
    $rekon13 = $de['rekon13'];
    $rekon14 = $de['rekon14'];
    $rekon15 = $de['rekon15'];
}


?>

<!DOCTYPE html> 
<html> 
<head>  
    <title>LEMBAR REKONSILIASI OBAT</title>  
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
            $("#obat").autocomplete({
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
                                        value: item.KODEBARANG + ' - ' + item.NAMABARANG + ' - ' + item.NAMASATUAN
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
                                        value: item.KODEBARANG + ' - ' + item.NAMABARANG + ' - ' + item.NAMASATUAN
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
            <br><br><br>

            <div class="row">
                <div class="col-6">
                    <table cellpadding="10px">
                        <tr valign="top">
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
                    <h4><center>REKONSILIASI OBAT</center></h4><hr>
                </div>
            </div>

            <br>
            <h5>Daftar Riwayat Alergi Obat</h5>
            <table width="100%">
                <tr>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Obat Alergi</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Gejala</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Tingkat Keparahan</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Tgl Entry</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Userid</font></td>
                </tr>
                <?php 
                $q="
                select TOP(100) userid,obat,gejala,tingkat_keparahan,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
                from ERM_RI_ALERGI
                where noreg='$noreg' order by id desc
                ";
                $hasil  = sqlsrv_query($conn, $q);  
                $no=1;
                while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
                    echo "
                    <tr>
                    <td>$no</td>
                    <td>$data[obat]</td>
                    <td>$data[gejala]</td>
                    <td>$data[tingkat_keparahan]</td>
                    <td>$data[tglentry]</td>
                    <td>$data[userid]</td>
                    </tr>
                    ";
                    $no += 1;

                }


                ?>
            </table>
            <br>
            <br>
            <h5>Rekonsiliasi Obat Saat Admisi</h5>
            <table width="100%">
                <tr>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Obat</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Frekuensi</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Lama</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Tindak_lanjut</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Perubahan_aturan_pakai</font></td>
                </tr>
                <?php 
                $q="
                select TOP(100) userid,obat,frekuensi,lama,tindak_lanjut,perubahan_aturan_pakai,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
                from ERM_RI_OBAT_ADMISI
                where noreg='$noreg' order by id desc
                ";
                $hasil  = sqlsrv_query($conn, $q);  
                $no=1;
                while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
                    echo "
                    <tr>
                    <td>$no</td>
                    <td>$data[obat]</td>
                    <td>$data[frekuensi]</td>
                    <td>$data[lama]</td>
                    <td>$data[tindak_lanjut]</td>
                    <td>$data[perubahan_aturan_pakai]</td>
                    </tr>
                    ";
                    $no += 1;

                }


                ?>
            </table>
            <br><br><br>
            <div class="row">
                <div class="col-6 text-center">
                    <b>Penerima Informasi</b>
                </div>
                <div class="col-6 text-center">
                    <b>Pemberi Informasi</b>
                </div>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-6 text-center">
                    <?php echo $rekon1;?>
                </div>
                <div class="col-6 text-center">
                    <?php echo $rekon2;?>
                </div>
            </div>

            <br>
            <br>
        </form>
    </font>
</body>
</div>
</div>