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
$IdTemplate = $row[2]; 
$IdResep = $row[3]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qr2="
SELECT        TOP (200) Jenis, KodeR, Jumlah, AturanPakai, CaraPakai, WaktuPakai, Keterangan, Bentuk, Satuan, TotalPerkiraan, PerkiraanHarga, 
Paket, Retriksi
FROM            W_EResep_R where Id=$IdResep
";
$hr2  = sqlsrv_query($conn, $qr2);        
$dr2  = sqlsrv_fetch_array($hr2, SQLSRV_FETCH_ASSOC); 

$jenis = trim($dr2['Jenis']);
$koder = trim($dr2['KodeR']);
$jumlah = trim($dr2['Jumlah']);
$aturanpakai = trim($dr2['AturanPakai']);
$carapakai = trim($dr2['CaraPakai']);
$waktupakai = trim($dr2['WaktuPakai']);
$keterangan = trim($dr2['Keterangan']);
$bentuk = trim($dr2['Bentuk']);
$satuan = trim($dr2['Satuan']);
$totalperkiraan = trim($dr2['TotalPerkiraan']);
if(empty($totalperkiraan)){
  $totalperkiraan='0';
}
$perkiraanharga = trim($dr2['PerkiraanHarga']);
if(empty($perkiraanharga)){
  $perkiraanharga='0';
}
$paket = trim($dr2['Paket']);
if(empty($paket)){
  $paket='0';
}
$retriksi = trim($dr2['Retriksi']);
if(empty($retriksi)){
  $retriksi='0';
}


$q  = "
insert into   W_Tmp_EResep_R(
IdTemplate, TglEntry, Jenis, KodeR, Jumlah, AturanPakai, CaraPakai, WaktuPakai, Keterangan, UserId, Bentuk, Satuan
) 
values (
$IdTemplate, '$tgl', '$jenis', '$koder', $jumlah, '$aturanpakai', '$carapakai', '$waktupakai', '$keterangan', '$user', '$bentuk', '$satuan'
)
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
window.location.replace('history_cppt.php?id=$id|$user|rencana_terapi_eresep');
</script>
";


?>