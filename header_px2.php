<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


if( $conn ) {
     //echo "Connection established.<br />";
}else{
     echo "Connection could not be established.<br />";
     die( print_r( sqlsrv_errors(), true));
}


$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$sbu = $row[2]; 


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
$unit = trim($d1u['KODEUNIT']);

if (empty($sbu)){
     $sbu = trim($d1u['KET1']);
}

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

$row = explode(',',$umur);
$tahun_u  = $row[0];
$tahun_u = intval(substr($tahun_u, 0,3));

//$tahun_u = 61;

$bulan_u = $row[1]; 
$bulan_u = intval(substr($bulan_u, 0,3));

$hari_u = $row[2]; 
$hari_u = intval(substr($hari_u, 0,3));

$qu="
SELECT        TOP (200) NORM,  ALERGI
FROM            Y_ALERGI 
where norm='$norm'

union 

SELECT        ARM_REGISTER.NORM, V_ERM_RI_KEADAAN_UMUM.alergi as ALERGI
FROM            V_ERM_RI_KEADAAN_UMUM INNER JOIN
ARM_REGISTER ON V_ERM_RI_KEADAAN_UMUM.noreg = ARM_REGISTER.NOREG
where ARM_REGISTER.NORM='$norm' and V_ERM_RI_KEADAAN_UMUM.alergi <> '' 

";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

//alergi dari rekon obat...
$qalergi="
select TOP(100) userid,obat,gejala,tingkat_keparahan,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
from ERM_RI_ALERGI
where noreg='$noreg' order by id desc
";
$hasil_alergi  = sqlsrv_query($conn, $qalergi);  
$objective = "Riwayat Alergi Obat :\n";

while   ($data_alergi = sqlsrv_fetch_array($hasil_alergi,SQLSRV_FETCH_ASSOC)){ 
     $lalergi=$data_alergi['obat'];
     $gejala = '('.$data_alergi['gejala'].')';
     $row2 = explode('-',$lalergi);

     $oalergi  = trim($row2[1]);
     if(empty($oalergi)){
          $oalergi  = trim($row2[0]);          
     }
     $alergi = $alergi.$oalergi." ,";
}



$qu2="SELECT ket as notif  FROM ERM_NOTIF where noreg='$noreg'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$notif = $d1u2['notif'];

$qu3="SELECT diet as diet  FROM ERM_DIET where noreg='$noreg'";
$h1u3  = sqlsrv_query($conn, $qu3);        
$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
$diet = $d1u3['diet'];

if (isset($_POST["cari"])) {
     $textcari = $_POST["textcari"];

     $row = explode('-',$textcari);
     $noreg  = trim($row[0]);


     if($noreg){
          header("Location: cekidheader.php?id=$user|$noreg");
     }

}


//cek KRS
$qu5="SELECT noreg,CONVERT(VARCHAR, tglkeluar, 105) AS tglkeluar  FROM ARM_PERIKSA where noreg='$noreg' and (year(ARM_PERIKSA.tglkeluar)>=2020)";
$h1u5  = sqlsrv_query($conn, $qu5);        
$h1u5  = sqlsrv_fetch_array($h1u5, SQLSRV_FETCH_ASSOC); 
$cekkrs = $h1u5['noreg'];

if($cekkrs){
     $ketkrs=' - Pasien Sudah KRS';
     $tglkrs = $h1u5['tglkeluar'];
     $qu6 = "SELECT top(1)
     noreg,
     CONVERT(VARCHAR, tglmasuk, 105) AS tglmasuk,
     DATEDIFF(DAY, tglmasuk, tglkeluar)+1 AS lama_dirawat
     FROM ARM_PERIKSA 
     WHERE noreg = '$noreg' 
     AND tglmasuk >  2020";

     $h1u6  = sqlsrv_query($conn, $qu6);        
     $dh1u6  = sqlsrv_fetch_array($h1u6, SQLSRV_FETCH_ASSOC); 

     $tglmasuk = $dh1u6['tglmasuk'];
     $lamadirawat = $dh1u6['lama_dirawat'];
}else{
     $qu6 = "SELECT top(1)
     noreg,
     CONVERT(VARCHAR, tglmasuk, 105) AS tglmasuk,
     DATEDIFF(DAY, tglmasuk, GETDATE())+1 AS lama_dirawat
     FROM ARM_PERIKSA 
     WHERE noreg = '$noreg' 
     AND tglmasuk >  2020";

     $h1u6  = sqlsrv_query($conn, $qu6);        
     $dh1u6  = sqlsrv_fetch_array($h1u6, SQLSRV_FETCH_ASSOC); 

     $tglmasuk = $dh1u6['tglmasuk'];
     $lamadirawat = $dh1u6['lama_dirawat'];
}


?>

<!-- Page content-->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pasien</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .highlight {
            background-color: #FFD95F;
            font-size: 1.2rem;
            font-weight: bold;
       }
  </style>
</head>
<body>
     <div class="container-fluid mt-4">
         <div class="card shadow-lg w-100">
             <div class="card-header bg-primary text-white">
                 <h4><i class="fas fa-user-injured"></i> Informasi Pasien</h4>
            </div>
            <div class="card-body">
                 <table class="table table-bordered table-striped table-hover">
                     <tr>
                         <td><i class="fas fa-user"></i> Nama</td>
                         <td>: <b><?php echo $nama.$ketkrs; ?><b> <?php if($ketkrs<>''){echo "(Tgl Krs : $tglkrs)";}else{}?></td>
                              <td><i class="fas fa-file-medical"></i> No. RM</td>
                              <td class="highlight">: <?php echo $norm; ?></td>
                         </tr>
                         <tr>
                              <td><i class="fas fa-calendar-alt"></i> Tanggal Lahir</td>
                              <td>: <?php echo $tgllahir; ?></td>
                              <td><i class="fas fa-id-card"></i> NIK</td>
                              <td>: <?php echo $noktp; ?></td>
                         </tr>
                         <tr>
                              <td><i class="fas fa-hourglass-half"></i> Umur</td>
                              <td>: <?php echo $umur; ?></td>
                              <td><i class="fas fa-venus-mars"></i> Jenis Kelamin</td>
                              <td>: <?php echo $kelamin; ?></td>
                         </tr>
                         <tr>
                              <td><i class="fas fa-exclamation-triangle"></i> Riwayat Alergi</td>
                              <td>: <?php echo nl2br($alergi); ?></td>
                              <td><i class="fas fa-utensils"></i> Diet</td>
                              <td>: <?php echo $diet; ?></td>
                         </tr>
                         <tr>
                              <td colspan="2">
                                  <i class="fas fa-bell"></i> Notifikasi / Pengingat Pasien
                                  <a href="m_notif.php?id=<?php echo $id.'|'.$user; ?>" class="btn btn-warning btn-sm">
                                      <i class="fas fa-exclamation-circle"></i> Beri Tanda Pasien dengan Nama Sama
                                 </a>
                                 <?php if($notif) { ?>
                                   <i class="fas fa-info-circle"></i> <?php echo $notif; ?>
                              <?php } ?>

                         </td>

                         <td colspan="2">
                              <i class="fas fa-procedures"></i> Lama Dirawat (Tgl Masuk : <?php echo $tglmasuk; ?>)
                              <a href="#" class="btn btn-warning btn-sm"><?php echo $lamadirawat; ?> hari </a>
                         </td>
                    </tr>
               </table>
          </div>
     </div>
</div>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->
</body>
</html>

