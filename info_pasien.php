<?php 
session_start();
include ("koneksi.php");

include ("mode.php");

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
$namaunit = $dhqun['namaunit'];

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
?>

<link rel="icon" href="favicon.ico">  
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f9f9f9;
    }
    .highlight {
        color: #e63946;
        font-weight: bold;
    }
    .container-custom {
        padding: 2rem;
        /*background: #fff;*/
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .btn-custom {
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
    }
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
    }
</style>

<body>

    <div class="container mt-5 container-custom">
        <div class="text-center mb-4">
            <p class="section-title">UNTUK MENCEGAH SALAH INPUT DATA</p>
            <p>Terlebih dahulu tolong <span class="highlight">DICEK APAKAH PASIEN SUDAH BENAR</span></p>
            <p>Jika benar, silakan tekan tombol <strong>LANJUTKAN</strong></p>
        </div>

        <?php 

        include ("header_px.php");

        ?>
        <div class="mb-3">
            <p><strong>Unit : </strong> <span class="text-primary"><?= $namaunit . " - " . $KODEUNIT ?></span></p>
            <p><strong>Dokter DPJP : </strong> <?= $dpjp ?: "<em>Belum ditentukan</em>" ?></p>
        </div>

        <div class="text-center mt-4">
            <a href='index.php?id=<?= $id . '|' . $user ?>' class='btn btn-info btn-custom'><i class="bi bi-info-circle"></i> Lanjutkan</a>
            &nbsp;&nbsp;
            <a href='listdata.php?id=<?= $user . '|' . $sbu ?>' class='btn btn-warning btn-custom'><i class="bi bi-x-circle"></i> Close</a>
            &nbsp;&nbsp;
            <a href='' class='btn btn-success btn-custom'><i class="bi bi-arrow-clockwise"></i> Refresh</a>
        </div>
    </div>

</body>


