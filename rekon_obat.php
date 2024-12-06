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
    <title>REKONSILIASI OBAT</title>  
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
            <font size='2px'>
                <form method="POST" name='myForm' action="" enctype="multipart/form-data">
                    <br>
                    <a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
                    &nbsp;&nbsp;
                    <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
                    &nbsp;&nbsp;
                    <!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
                    <a href='rekon_obat_print.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a>                  
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
                    <?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-12 text-center">
                    <b>REKONSILIASI OBAT</b><br>
                </div>
            </div>

            <br>
            <h5>Daftar Riwayat Alergi Obat</h5>
            <table width='100%' border='1'>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Obat yang menimbulkan Alergi
                            </div>
                            <div class="col-8">
                                : <input class="" name="obat_alergi" value="<?php echo $obat_alergi;?>" id="obat" type="text" size='50' onfocus="nextfield ='';" placeholder="">
                            </div>
                        </div>
                    </td>
                </tr>    
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Gejala yang ditimbulkan
                            </div>
                            <div class="col-8">
                                : <input class="" name="gejala" value="<?php echo $gejala;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
                            </div>
                        </div>
                    </td>
                </tr>    
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Tingkat Keparahan
                            </div>
                            <div class="col-8">
                                : 
                                <!-- <input class="" name="tingkat" value="<?php echo $tingkat;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder=""> -->
                                <input type='radio' name='tingkat' value='Ringan' >Ringan&nbsp;&nbsp;&nbsp;
                                <input type='radio' name='tingkat' value='Sedang' >Sedang&nbsp;&nbsp;&nbsp;
                                <input type='radio' name='tingkat' value='Berat' >Berat&nbsp;&nbsp;&nbsp;

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
                                <input type='submit' name='simpan_alergi' value='simpan_alergi' style="color: white;background: #66CDAA;border-color: #66CDAA;">
                            </div>
                        </div>
                    </td>
                </tr> 
            </table>
            <br><br>
            <table width="100%">
                <tr>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Obat Alergi</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Gejala</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Tingkat Keparahan</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Tgl Entry</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Userid</font></td>
                    <td align='center' style="border: 1px solid;" bgcolor='#708090'><font color='white'>delete</font></td>
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
                    <td align='center'><a href='del_alergi.php?id=$id|$user|$data[id]'><font color='red'>[x]</font></a></td>
                    </tr>
                    ";
                    $no += 1;

                }


                ?>
            </table>
            <br>
            <br>
            <h5>Rekonsiliasi Obat Saat Admisi</h5>
            <table width='100%' border='1'>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Nama Obat dan Dosis
                            </div>
                            <div class="col-8">
                                : <input class="" name="obat_admisi" value="" id="obat2" type="text" size='50' onfocus="nextfield ='';" placeholder="">
                            </div>
                        </div>
                    </td>
                </tr>    
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Frekuensi
                            </div>
                            <div class="col-8">
                                : <input class="" name="frekuensi" value="" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
                            </div>
                        </div>
                    </td>
                </tr>    
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Lama Pemberian
                            </div>
                            <div class="col-8">
                                : <input class="" name="lama_pemberian" value="" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
                            </div>
                        </div>
                    </td>
                </tr>    
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Tindak Lanjut Oleh DPJP
                            </div>
                            <div class="col-8">
                                <!-- <input class="" name="tingkat" value="<?php echo $tingkat;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder=""> -->
                                <input type='radio' name='tindak_lanjut_dpjp' value='Lanjut Aturan Pakai Sama' >Lanjut Aturan Pakai Sama<br>
                                <input type='radio' name='tindak_lanjut_dpjp' value='Lanjut Aturan Pakai Berubah' >Lanjut Aturan Pakai Berubah<br>
                                <input type='radio' name='tindak_lanjut_dpjp' value='Stop' >Stop

                            </div>
                        </div>
                    </td>
                </tr>  
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-4">
                                &nbsp;&bull; Perubahan Aturan Pakai
                            </div>
                            <div class="col-8">
                                : <input class="" name="perubahan_aturan_pakai" value="" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
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
                                <input type='submit' name='simpan_obat_admisi' value='simpan_obat_admisi' style="color: white;background: #66CDAA;border-color: #66CDAA;">
                            </div>
                        </div>
                    </td>
                </tr> 
            </table>
            <br><br>
            <table width="100%">
                <tr>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Obat</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Frekuensi</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Lama</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Tindak_lanjut</font></td>
                    <td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Perubahan_aturan_pakai</font></td>
                    <td align='center' style="border: 1px solid;" bgcolor='#708090'><font color='white'>delete</font></td>
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
                    <td align='center'><a href='del_rekon_obat.php?id=$id|$user|$data[id]'><font color='red'>[x]</font></a></td>
                    </tr>
                    ";
                    $no += 1;

                }


                ?>
            </table>
            <br>
            <br>


            <table width='100%' border='1'>

                <tr valign="top">
                    <td>
                        <div class="row">
                            <div class="col-4">
                                Penerima Informasi
                                <textarea name= "rekon1" id="" style="min-width:300px; min-height:10px;"><?php echo $rekon1;?></textarea>
                            </div>
                            <div class="col-4">
                                Pemberi Informasi
                                <textarea name= "rekon2" id="" style="min-width:300px; min-height:10px;"><?php echo $rekon2;?></textarea>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>                      
                        <div class="row">
                         <div class="col-4">&nbsp;</div>
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


if (isset($_POST["simpan_obat_admisi"])) {

    $obat_admisi    = $_POST["obat_admisi"];
    $frekuensi    = $_POST["frekuensi"];
    $lama_pemberian    = $_POST["lama_pemberian"];   
    $tindak_lanjut_dpjp    = $_POST["tindak_lanjut_dpjp"];
    $perubahan_aturan_pakai    = $_POST["perubahan_aturan_pakai"];

    $obat_admisi          = str_replace("'","`",$obat_admisi);

    $q  = "insert into ERM_RI_OBAT_ADMISI(noreg,userid,tglentry,tgl,obat,frekuensi,lama,tindak_lanjut,perubahan_aturan_pakai) 
    values ('$noreg','$user','$tgl','$tgl','$obat_admisi','$frekuensi','$lama_pemberian','$tindak_lanjut_dpjp','$perubahan_aturan_pakai')";
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


if (isset($_POST["simpan_alergi"])) {
    $obat_alergi    = $_POST["obat_alergi"];
    $gejala    = $_POST["gejala"];
    $tingkat    = $_POST["tingkat"];   
    $obat_alergi          = str_replace("'","`",$obat_alergi);


    $q  = "insert into ERM_RI_ALERGI(noreg,userid,tglentry,tgl,obat,gejala,tingkat_keparahan) 
    values ('$noreg','$user','$tgl','$tgl','$obat_alergi','$gejala','$tingkat')";
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

if (isset($_POST["simpan"])) {

    $rekon1    = $_POST["rekon1"];
    $rekon2    = $_POST["rekon2"];
    $rekon3    = $_POST["rekon3"];
    $rekon4    = $_POST["rekon4"];
    $rekon5    = $_POST["rekon5"];
    $rekon6    = $_POST["rekon6"];

    $q  = "update ERM_RI_REKONOBAT set
    rekon1 ='$rekon1',rekon2 ='$rekon2',rekon3='$rekon3', rekon4 = '$rekon4', rekon5 = '$rekon5', rekon6 = '$rekon6'
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