<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include ("mode.php");

$tgl        = gmdate("Y-m-d", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);

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

$noreg_igd = substr($noreg, 1,12);

//radiologi
$qrad="
SELECT        HASIL, URAIAN, CONVERT(VARCHAR, TANGGAL, 103) AS TANGGAL
FROM            HASILRAD_PEMERIKSAAN_RAD
WHERE        (NOREG LIKE '%$noreg_igd%')
ORDER BY TANGGAL
";
$hqrad  = sqlsrv_query($conn, $qrad);

$i=1;
while   ($dhqrad = sqlsrv_fetch_array($hqrad, SQLSRV_FETCH_ASSOC)){     
    $rad0 = $dhqrad[TANGGAL].'-'.$dhqrad[HASIL].':'.$dhqrad[URAIAN];
    $rad = $rad.'&#13;&#10;'.$rad0;
}


$qi="SELECT noreg FROM ERM_RI_PENUNJANG where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];


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

    <style>
        body.dark-mode .card {
            background-color: #212529; /* Warna gelap */
            color: #ffffff;            /* Teks putih */
        }
        body.dark-mode .diagnosa-text {
            color: #ffffff;
        }

        body:not(.dark-mode) .diagnosa-text {
            color: #000000;
        }
        body.dark-mode .verifikasi-text {
            color: #cccccc;
        }
    </style>
    
</head> 
<div id="content"> 
    <div class="container">

        <body onload="document.myForm.pasien_mcu.focus();">
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
                <?php 
                if($role=='DOKTER'){
                    include('menu_dokter.php');
                }
                ?>
            </div>

            <hr>

        </body>
    </div>

    <div class="container mt-4">
        <div class="text-center mb-4">
            <h5><i class="bi bi-clipboard2-pulse"></i> Pemeriksaan Radiologi</h5>
        </div>

        <div class="row g-4">
            <!-- Card: Hasil Bacaan Radiologi -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-journal-text me-2"></i> Hasil Bacaan Radiologi
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br($rad); ?></p>
                    </div>
                </div>
            </div>

            <!-- Card: Foto Radiologi -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-file-earmark-image me-2"></i> Foto Radiologi
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>No</th>
                                        <th>No. Reg</th>
                                        <th>Poli</th>
                                        <th>Preview</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $ql="
                                    SELECT TOP (200) document_rad.noreg, document_rad.jenis, document_rad.doc, 
                                    ARM_REGISTER.NORM, AFarm_MstPasien.NAMA, ARM_REGISTER.TUJUAN, 
                                    Afarm_Unitlayanan.NAMAUNIT, CONVERT(VARCHAR, ARM_REGISTER.TANGGAL, 103) AS TANGGAL
                                    FROM document_rad 
                                    INNER JOIN ARM_REGISTER ON document_rad.noreg = ARM_REGISTER.NOREG 
                                    INNER JOIN AFarm_MstPasien ON ARM_REGISTER.NORM = AFarm_MstPasien.NORM 
                                    INNER JOIN Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
                                    WHERE (ARM_REGISTER.NORM LIKE '%$noreg_igd%') 
                                    OR (AFarm_MstPasien.NAMA LIKE '%$noreg_igd%') 
                                    OR (document_rad.noreg LIKE '%$noreg_igd%')
                                    ORDER BY document_rad.id DESC
                                    ";
                                    $hl = sqlsrv_query($conn, $ql);
                                    $no = 1;
                                    while ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)) {         
                                        if($sbu=='RSPG'){
                                            $link = 'http://192.168.5.109/dok_radiologi/' . $dl['doc'];
                                        }       
                                        if($sbu=='GRAHU'){
                                            $link = 'http://192.168.30.34/dok_radiologi_gr/' . $dl['doc'];
                                        }

                                        $isImage = preg_match('/\.(jpg|jpeg|png|gif)$/i', $dl['doc']);

                                        echo "
                                        <tr>
                                        <td class='text-center'>$no</td>
                                        <td>{$dl['noreg']}<br>{$dl['TANGGAL']}</td>
                                        <td>{$dl['NAMAUNIT']}<br>{$dl['jenis']}</td>
                                        <td class='text-center'>";
                                        if ($isImage) {
                                            echo "<a href='$link' target='_blank'>
                                            <img src='$link' alt='Foto' style='width: 60px; height: 60px; object-fit: cover; border-radius: 6px;'>
                                            </a>";
                                        } else {
                                            echo "<i class='bi bi-file-earmark'></i>";
                                        }
                                        echo "</td>
                                        <td class='text-center'>
                                        <a href='$link' target='_blank' class='btn btn-sm btn-outline-success'>
                                        <i class='bi bi-eye'></i> Lihat
                                        </a>
                                        </td>
                                        </tr>";
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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