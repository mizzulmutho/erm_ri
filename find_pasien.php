<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$postcode = $_POST['postcode'];

$sql="
SELECT    TOP(10) ARM_REGISTER.NOREG as noreg, ARM_REGISTER.NORM as norm, AFarm_MstPasien.NAMA as nama_pasien, CONVERT(VARCHAR, ARM_REGISTER.TANGGAL, 103) as tgl_periksa
FROM         ARM_REGISTER INNER JOIN
AFarm_MstPasien ON ARM_REGISTER.NORM = AFarm_MstPasien.NORM
WHERE     (ARM_REGISTER.NOREG like 'R%') and (ARM_REGISTER.NOREG like '%$postcode%' OR ARM_REGISTER.NORM like '%$postcode%' OR AFarm_MstPasien.NAMA like '%$postcode%' ) order by ARM_REGISTER.TANGGAL desc
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