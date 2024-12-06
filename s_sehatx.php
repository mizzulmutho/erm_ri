<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$auth_url = "https://api-satusehat.kemkes.go.id/oauth2/v1";
$base_url = "https://api-satusehat.kemkes.go.id/fhir-r4/v1";
$consent_url = "https://api-satusehat.dto.kemkes.go.id/consent/v1";


$id = $_GET["id"];
$row = explode('|',$id);

$noreg = trim($row[0]); 
$tgl1 = trim($row[1]); 
$tgl2 = trim($row[2]); 

$q = "insert into zzz_mutok3(noreg) values('$noreg')";
$h1  = sqlsrv_query($conn, $q);        

if ($h1){
  header("Location: encountered_ss.php?id=$tgl1|$tgl2");
  exit; 
}




?>            
