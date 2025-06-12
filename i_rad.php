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
$idhasil = $row[2]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);
$noreg_igd = substr($noreg, 1,12);

$q = " 
DELETE from ERM_RI_RAD_TEMP             
WHERE idrad='$idhasil'
";
$h1  = sqlsrv_query($conn, $q);        

$q2 = " 
SELECT        HASIL, URAIAN, ID, CONVERT(VARCHAR, TANGGAL, 23) AS TANGGAL
FROM            HASILRAD_PEMERIKSAAN_RAD
WHERE        (NOREG = '$noreg_igd')
ORDER BY TANGGAL
";
$h2  = sqlsrv_query($conn, $q2);
$dh2  = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
$TANGGAL = trim($dh2['TANGGAL']);
$HASIL = trim($dh2['HASIL']);
$URAIAN = trim($dh2['URAIAN']);

echo $qi  = "
insert into ERM_RI_RAD_TEMP
(noreg, userid, tglentry, tgl, idrad, hasil, uraian) 
values 
('$noreg','$user','$tanggal','$TANGGAL','$idhasil', '$HASIL', '$URAIAN')
";
$hs = sqlsrv_query($conn,$qi);



if ($hs){
  header("Location: rad_list_dokter.php?id=$id|$user");
  exit; 
}

?>