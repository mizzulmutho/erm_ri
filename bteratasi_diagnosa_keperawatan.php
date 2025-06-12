<?php 
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tanggal    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$getid = $_GET["id"];
$row = explode('|',$getid);
$id = $row[0]; 
$user = $row[1]; 
$noreg = $row[2]; 
$kode_diagnosa = $row[3]; 

$q = " 
update ERM_ASUHAN_KEPERAWATAN set teratasi=''              
WHERE noreg='$noreg' and diagnosa_keperawatan='$kode_diagnosa'
";
$h1  = sqlsrv_query($conn, $q);        

if ($h1){
  header("Location: diagnosa_keperawatan.php?id=$id|$user");
  exit; 
}

?>