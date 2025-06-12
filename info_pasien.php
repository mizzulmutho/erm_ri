<?php 
session_start();
include ("koneksi.php");


echo "
<div class='row'>
<div class='col-12 text-center'>
UNTUK MENCEGAH SALAH INPUT DATA<br>
TERLEBIH DAHULU TOLONG <b></u><font color='red'>DICEK APAKAH PASIEN SUDAH BENAR</font></u></b> SEPERTI DATA DIBAWAH ! 
<br>
JIKA BENAR SILAHKAN TEKAN TOMBOL LANJUTKAN
</div>
</div>
";

include ("header_px.php");

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$sbu = $row[2]; 

$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1,ARM_REGISTER.TUJUAN
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
// $KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);
$KODEUNIT = trim($d1u['TUJUAN']);
$sbu = trim($d1u['KET1']);


$qt="SELECT tr1,tr2 FROM   ERM_RI_TRANSFER where noreg='$noreg' order by id desc";
$hqt  = sqlsrv_query($conn, $qt);        
$dhqt  = sqlsrv_fetch_array($hqt, SQLSRV_FETCH_ASSOC); 
$tr1 = $dhqt['tr1'];
$tr2 = $dhqt['tr2'];
$row = explode('-',$tr2);
$tr2  = $row[1];

if($tr2){
    $KODEUNIT=trim($tr2);
}

$qun="SELECT namaunit FROM   Afarm_Unitlayanan where kodeunit='$KODEUNIT'";
$hqun  = sqlsrv_query($conn, $qun);        
$dhqun  = sqlsrv_fetch_array($hqun, SQLSRV_FETCH_ASSOC); 
echo "&nbsp;&nbsp;&nbsp;<font size='4' color='#F93827'><b>Unit : ".$namaunit = $dhqun['namaunit'] .' - '. $KODEUNIT."</b></font>";


$qu="SELECT dpjp FROM  V_ERM_RI_DPJP_ASESMEN where noreg='$noreg' and dpjp is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$dpjp = $d1u['dpjp'];
$row = explode('-',$dpjp);
$kodedokter  = trim($row[0]);

if(empty($dpjp)){
    $qu="SELECT top(1)nama_dokter as dpjp FROM   ERM_RI_RABER where noreg='$noreg' order by id";
    $h1u  = sqlsrv_query($conn, $qu);        
    $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
    $dpjp = $d1u['dpjp'];
    $row = explode('-',$dpjp);
    $kodedokter  = trim($row[0]);

}

echo '&nbsp;&nbsp;&nbsp;Dokter : '.$dpjp;
echo "<br>";echo "<hr>";
?>

<link rel="icon" href="favicon.ico">  
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> 

<!-- $user = trim($row[0]); 
$sbu = trim($row[1]); 
-->
<div class="row">
    <div class="col-12 text-center">
        <a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-info"></i> Lanjutkan</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href='listdata.php?id=<?php echo $user.'|'.$sbu;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
    </div>
</div>

