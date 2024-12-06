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


$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$q2       = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
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

$row = explode(',',$umur);
$tahun_u  = $row[0];
$tahun_u = intval(substr($tahun_u, 0,3));

//$tahun_u = 61;

$bulan_u = $row[1]; 
$bulan_u = intval(substr($bulan_u, 0,3));

$hari_u = $row[2]; 
$hari_u = intval(substr($hari_u, 0,3));

$qu="SELECT ALERGI  FROM Y_alergi where norm='$norm'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

$qu2="SELECT ket as notif  FROM ERM_NOTIF where noreg='$noreg'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$notif = $d1u2['notif'];

$qu3="SELECT diet as diet  FROM ERM_DIET where noreg='$noreg'";
$h1u3  = sqlsrv_query($conn, $qu3);        
$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
$diet = $d1u3['diet'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
     <meta name="description" content="" />
     <meta name="author" content="" />
     <title>SIRS-Sistem Informasi Rumah Sakit</title>
     <!-- Favicon-->
     <link rel="icon" href="P-2.ico">  
     <!-- Core theme CSS (includes Bootstrap)-->
     <link href="css/styles.css" rel="stylesheet" />


</head>
<body>
     <div class="d-flex" id="wrapper">
          <!-- Sidebar-->
          <div class="border-end bg-white" id="sidebar-wrapper">
               <div class="sidebar-heading border-bottom bg-light"><font color='green'><b>FORM E-RM<br>RAWAT INAP</b></font></div>

               <div class="list-group list-group-flush">
                    <div class="dropdown">
                         <a class="list-group-item list-group-item-action list-group-item-light p-3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Report</a>
                         <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="r_rekammedik.php?id=<?php echo $id.'|'.$user;?>">Rekam Medik Pasien</a></li>
                              <li><a class="dropdown-item" href="r_soap.php?id=<?php echo $id.'|'.$user;?>">CPPT</a></li>
                              <li><a class="dropdown-item" href="r_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>">Asuhan Keperawatan</a></li>
                              <li><a class="dropdown-item" href="r_asuhankebidanan.php?id=<?php echo $id.'|'.$user;?>">Asuhan Kebidanan</a></li>
                              <li><a class="dropdown-item" href="r_observasi.php?id=<?php echo $id.'|'.$user;?>">Detail Observasi</a></li>
                              <li><a class="dropdown-item" href="r_ews.php?id=<?php echo $id.'|'.$user;?>">Monitoring EWS</a></li>
                              <li><a class="dropdown-item" href="r_vitalsign.php?id=<?php echo $id.'|'.$user;?>">Dashboard Vital Sign</a></li>
                              <!-- <li><a class="dropdown-item" href="r_resume.php?id=<?php echo $id.'|'.$user;?>">Resume Pasien</a></li> -->
                         </ul>
                    </div>

               </div>

          </div>
          <!-- Page content wrapper-->
          <div id="page-content-wrapper">
               <!-- Top navigation-->
<!--           <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
               <div class="container-fluid">
                    <button class="btn btn-light" id="sidebarToggle"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                         <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                              <li class="nav-item active"><a class="nav-link" href="#!">Home</a></li>
                              <li class="nav-item"><a class="nav-link" href="#!">Link</a></li>
                              <li class="nav-item dropdown">
                                   <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                                   <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="#!">Action</a>
                                        <a class="dropdown-item" href="#!">Another action</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#!">Something else here</a>
                                   </div>
                              </li>
                         </ul>
                    </div>
               </div>
          </nav> -->

          <!-- Page content-->
          <div class="container-fluid">

               <!-- <h3 class="mt-4">Dashboard</h3> -->

               <div class="row">
                    <div class="col-sm-12">
                         <div class="card">
                              <table cellpadding="5">
                                   <tr>
                                        <td>
                                             <font size='2px'>
                                                  Norm : <?php echo $norm;?>
                                             </font>                                        
                                        </td>
                                        <td>
                                             <font size='2px'>
                                                  Tgl Lahir : <?php echo $tgllahir;?>
                                             </font>
                                        </td>
                                        <td>
                                             <font size='2px'>
                                                  Umur : <?php echo $umur;?>
                                             </font>
                                        </td>
                                   </tr>     
                                   <tr>
                                        <td>
                                             <font size='2px'>
                                                  Nama : <?php echo $nama;?>
                                             </font>                                        
                                        </td>
                                        <td colspan="2">
                                             <font size='2px'>
                                                  Alamat : <?php echo $alamatpasien;?>
                                             </font>
                                        </td>
                                   </tr> 
                                   <tr>
                                        <td>       
                                             <font size='2px'>
                                                  ALERGI : <?php echo $alergi; ?>
                                             </font>
                                        </td>
                                        <td>       
                                             <font size='2px'>
                                                  NOTIF : <?php echo $notif; ?>
                                             </font>
                                        </td>
                                        <td>       
                                             <font size='2px'>
                                                  DIET : <?php echo $diet; ?>
                                             </font>
                                        </td>
                                   </tr>
                              </table>

                         </div>
                    </div>
               </div>

          </div>

          <!-- Bootstrap core JS-->
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
          <!-- Core theme JS-->
          <script src="js/scripts.js"></script>



