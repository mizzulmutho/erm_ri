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
$idrpo = $row[2]; 
$KODEUNIT = $row[3]; 
$noreg = $row[4]; 
$norm = $row[5]; 

header("Location: http://192.168.10.4:1234/rekam_medik/entry_tindakan/rawat_jalan/$KODEUNIT/$noreg/$norm/resep/");
exit; 

?>