<?php 
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl    = gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tanggal    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$getid = $_GET["id"];
$row = explode('|',$getid);
$id = $row[0]; 
$user = $row[1]; 
$IdResep = $row[2]; 

$q  = "
DELETE from W_Tmp_EResep_R where id=$IdResep
";
$hs = sqlsrv_query($conn,$q);


if($hs){
  $eror = "Success";
}else{
  $eror = "Gagal Insert";
}

echo "
<script>
alert('".$eror."');
window.location.replace('soap_dokter_all.php?id=$id|$user||rencana_terapi_eresep');
</script>
";



?>