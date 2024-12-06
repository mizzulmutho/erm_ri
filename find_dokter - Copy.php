<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$postcode = $_POST['postcode'];

// header('Content-Type: application/json; charset=utf8');

$sql="
select top(10) kodedokter,nama,keterangan from afarm_dokter where (kodedokter like'%$postcode%' OR nama like '%$postcode%') and KODEDOKTER <> ''
union
select top(10) KODE as kodedokter,NAMA as nama,KETERANGAN as keterangan from Afarm_PARAMEDIS where (KODE like'%$postcode%' OR NAMA like '%$postcode%')
order by KODEDOKTER
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