<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
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
$idrpo = $row[2]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$rand = rand(1, 9);
// $rand2 = rand(1, 9);
$nomor = trim($rand.'-'.$tgl);

$q3       = "select distinct top(1)id_rpo_header from ERM_RI_RPO where noreg='$noreg' and id_rpo_header is not null order by id_rpo_header desc";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$id_rpo_header = $data3['id_rpo_header'];

if(empty($id_rpo_header)){
  echo "<br>";echo "<br>";
  echo "Data RPO masih Kosong";
}

$q="
SELECT        id, nomor, noreg, userid, '' as tglentry, '' as tgl, nama_obat, jumlah, dosis, waktu_penggunaan, interval, dokter, apoteker, periksa, pemberi, keluarga, id_eresep, id_eresep_detail, id_rpo_header
FROM            ERM_RI_RPO
WHERE        (id_rpo_header = $id_rpo_header) order by id 
";
$hasil  = sqlsrv_query($conn, $q);  
$no=1;
while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

  $id_eresep_detail = $data['id_eresep_detail'];
  $id_eresep = $data['id_eresep'];
  $tgl = $data['tglentry'];
  $nama_obat = $data['nama_obat'];
  $dosis = $data['dosis'];
  $jumlah = $data['jumlah'];
  $waktu_penggunaan = $data['waktu_penggunaan'];

  $q  = "insert into ERM_RI_RPO(noreg,userid,tglentry,tgl,nama_obat,dosis,jumlah,waktu_penggunaan,id_eresep,id_eresep_detail,id_rpo_header,nomor) 
  values ('$noreg','$user','$tanggal','$tanggal','$nama_obat','$dosis','$jumlah','$waktu_penggunaan',$id_eresep,$id_eresep_detail,$idrpo,'$nomor')";
  $hs = sqlsrv_query($conn,$q);

  $no += 1;

}

if($hs){
  $eror = "Success";
}else{
  $eror = "Gagal Insert";

}

if($hs){
  echo "
  <div class='container-fluid'>
  <div class='row'>
  ";
  echo "SUCCESS";
  echo "<br>";
  echo "<a href='rpo.php?id=$id|$user' class='btn btn-success'><i class='bi bi-arrow-clockwise'></i> Close</a>";
  echo "<br>";
  echo "
  </div>
  </div>
  "; 
}



?>