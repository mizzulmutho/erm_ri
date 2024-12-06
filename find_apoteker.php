<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$postcode = $_POST['postcode'];

// header('Content-Type: application/json; charset=utf8');

$sql="
SELECT        TOP (200) APOTEKER as kodedokter, NAMA as nama, KET as keterangan
FROM            MASTER_APOTEKER
where (APOTEKER like'%$postcode%' OR NAMA like '%$postcode%')
";
$hasil  = sqlsrv_query($conn, $sql); 

//data array
$array=array();
while($data=sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)) $array[]=$data; 

$datax =  json_encode(array(
   'response' => $array,
));

$response=$datax;

$response=json_decode($response);

header('Content-Type: application/json'); 
echo json_encode($response);

?>