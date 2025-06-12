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
// $objective = "Riwayat Alergi Obat :\n";

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

?>

<br>
<!-- Page content-->

<style>
     .table {
        background-color: white;
        width: 100%;
        border-collapse: collapse;
   }
   .table-bordered td {
        border: 1px solid black;
        padding: 8px;
   }
</style>

<div class="container-fluid mt-3"> <!-- ubah container jadi container-fluid -->
   <div class="card shadow-sm">
      <div class="card-header bg-primary text-white text-center">
         <h5 class="mb-0">Informasi Pasien</h5>
    </div>
    <div class="card-body">
         <div class="row mb-2">
            <div class="col-md-6"><i class="fas fa-user"></i> <strong>Nama:</strong> <?php echo $nama;?></div>
            <div class="col-md-6"><i class="fas fa-id-card"></i> <strong>No. RM:</strong> <?php echo $norm;?></div>
       </div>
       <div class="row mb-2">
            <div class="col-md-6"><i class="fas fa-calendar-alt"></i> <strong>Tanggal Lahir:</strong> <?php echo $tgllahir;?></div>
            <div class="col-md-6"><i class="fas fa-id-badge"></i> <strong>NIK:</strong> <?php echo $noktp;?></div>
       </div>
       <div class="row mb-2">
            <div class="col-md-6"><i class="fas fa-hourglass-half"></i> <strong>Umur:</strong> <?php echo $umur;?></div>
            <div class="col-md-6"><i class="fas fa-venus-mars"></i> <strong>Jenis Kelamin:</strong> <?php echo $kelamin;?></div>
       </div>
       <div class="row mb-2">
            <div class="col-md-6"><i class="fas fa-allergies"></i> <strong>Riwayat Alergi:</strong> <?php echo $alergi;?></div>
            <div class="col-md-6"><i class="fas fa-utensils"></i> <strong>Diet:</strong> <?php echo $diet;?></div>
       </div>
  </div>
</div>
</div>
<br>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


