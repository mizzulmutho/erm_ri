<?php 
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tglinput   = gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);

$user = trim($row[0]); 
$noreg = trim($row[1]); 
$sbu = trim($row[2]); 

$qur="SELECT noreg FROM ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
$h1ur  = sqlsrv_query($conn, $qur);        
$d1ur  = sqlsrv_fetch_array($h1ur, SQLSRV_FETCH_ASSOC); 
$ceknoreg = trim($d1ur['noreg']);

if(empty($ceknoreg)){

  $q  = "insert into ERM_RI_RESUME_APPROVEL
  (noreg, userid, tglentry, tgl_approve) 
  values 
  ('$noreg','$user','$tglinput','$tglinput')";
  $hs = sqlsrv_query($conn,$q);

}else{

  $q  = "update ERM_RI_RESUME_APPROVEL set userid='$user' where noreg='$noreg'";
  $hs = sqlsrv_query($conn,$q);

}



if ($hs){
  $eror = "Simpan data berhasil.";
  echo "
  <script>
  alert('".$eror."');
  window.location.replace('listdata.php?id=$user|$sbu');
  </script>
  ";
}

?>