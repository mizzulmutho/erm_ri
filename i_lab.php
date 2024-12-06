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
$tgllab = $row[3]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);


$q = " 
DELETE from ERM_RI_LAB_TEMP             
WHERE idlab='$idhasil'
";
$h1  = sqlsrv_query($conn, $q);        

$q2 = " 
SELECT 
CONVERT(VARCHAR, REG_DATE, 25) as REG_DATE,KEL_PEMERIKSAAN,PARAMETER_NAME,HASIL,SATUAN,NILAI_RUJUKAN,FLAG,ID_HASIL
FROM        LINKYAN5.SHARELIS.dbo.hasilLIS
WHERE        (ID_HASIL = '$idhasil')";
$h2  = sqlsrv_query($conn, $q2);
$dh2  = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
$REG_DATE = trim($dh2['REG_DATE']);
$KEL_PEMERIKSAAN = trim($dh2['KEL_PEMERIKSAAN']);
$PARAMETER_NAME = trim($dh2['PARAMETER_NAME']);
$HASIL = trim($dh2['HASIL']);
$SATUAN = trim($dh2['SATUAN']);
$NILAI_RUJUKAN = trim($dh2['NILAI_RUJUKAN']);
$FLAG = trim($dh2['FLAG']);

$qi  = "
insert into ERM_RI_LAB_TEMP
(noreg, userid, tglentry, tgl, idlab, pemeriksaan, parameter, nilai_normal, flag, hasil) 
values 
('$noreg','$user','$tanggal','$REG_DATE',
'$idhasil', '$KEL_PEMERIKSAAN', '$PARAMETER_NAME', '$NILAI_RUJUKAN', '$FLAG', '$HASIL'
)
";
$hs = sqlsrv_query($conn,$qi);



if ($hs){
  header("Location: lab_list_dokter_detail.php?id=$id|$user|$tgllab");
  exit; 
}

?>