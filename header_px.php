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

?>

<!-- Tambahkan ini di bagian <head> jika belum -->
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

     <style>
         body {
             font-family: 'Poppins', sans-serif;
        }
        .info-table td {
             vertical-align: middle;
             font-size: 16px;
        }
        .highlight-cell {
          background-color: #FFF3C7 !important;
     }
     .highlight-text {
        color: #1d4ed8;
        font-size: 20px;
        font-weight: bold;
   }
   .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
   }
</style>


<div class="container-fluid">
 <div class="row">
  <div class="col-12">
   <p class="section-title">Informasi Pasien</p>
   <table class="table table-bordered info-table">
    <tr>
     <td>Nama</td>
     <td class="highlight-cell">: <span class="highlight-text"><?php echo $nama; ?></span></td>
     <td>No. RM</td>
     <td>: <?php echo $norm; ?></td>
</tr>
<tr>
     <td>Tanggal Lahir</td>
     <td class="highlight-cell">: <span class="highlight-text"><?php echo $tgllahir; ?></span></td>
     <td>NIK</td>
     <td>: <?php echo $noktp; ?></td>
</tr>
<tr>
     <td>Umur</td>
     <td>: <?php echo $umur; ?></td>
     <td>Jenis Kelamin</td>
     <td>: <?php echo $kelamin; ?></td>
</tr>
<tr>
     <td width="15%">Riwayat Alergi</td>
     <td width="35%">: <?php echo nl2br($alergi); ?></td>
     <td width="15%">Diet</td>
     <td width="35%">: <?php echo $diet; ?></td>
</tr>
<tr>
     <?php
     $qu3r = "SELECT resume4 FROM ERM_RI_RESUME WHERE noreg='$noreg'";
     $h1u3r = sqlsrv_query($conn, $qu3r);        
     $d1u3r = sqlsrv_fetch_array($h1u3r, SQLSRV_FETCH_ASSOC); 
     $tglkrs = $d1u3r['resume4'];
     ?>
     <td>Tgl KRS</td>
     <td colspan="3" class="<?php echo ($tglkrs ? 'highlight-cell' : ''); ?>">
      : <span class="highlight-text"><?php echo nl2br($tglkrs); ?></span>
 </td>
</tr>
</table>
</div>
</div>
</div>