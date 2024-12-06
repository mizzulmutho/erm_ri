<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
$id = $_GET["id"];
$sbu = $id;

$postcode = $_POST['postcode'];

$sql="
SELECT        TOP (10) ARM_REGISTER.NOREG AS noreg, ARM_REGISTER.NORM AS norm, AFarm_MstPasien.NAMA AS nama_pasien, CONVERT(VARCHAR, ARM_REGISTER.TANGGAL, 103) AS tgl_periksa
FROM            ARM_REGISTER INNER JOIN
AFarm_MstPasien ON ARM_REGISTER.NORM = AFarm_MstPasien.NORM INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE       (ARM_REGISTER.NOREG LIKE 'R%') AND (ARM_REGISTER.NOREG LIKE 'R%') AND (Afarm_Unitlayanan.KET1 = '$sbu') AND ((ARM_REGISTER.NOREG LIKE '%$postcode%') OR (ARM_REGISTER.NORM LIKE '%$postcode%') OR (AFarm_MstPasien.NAMA LIKE '%$postcode%'))
ORDER BY ARM_REGISTER.TANGGAL DESC
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