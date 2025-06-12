<?php 
include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// $serverName = "192.168.10.1"; //serverName\instanceName
// $connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
// $conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$id_observasi = $row[2]; 

$q  = "delete from ERM_RI_OBSERVASI_CAIRAN where id='$id_observasi'
";
$hs = sqlsrv_query($conn,$q);

if($hs){
	$eror = "Success";
}else{
	$eror = "Gagal Hapus";

}

echo "
<script>
alert('".$eror."');
history.go(-1);
</script>
";



?>
