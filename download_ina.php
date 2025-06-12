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
$nosep = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];


if($sbu=='RSPG'){
  include('consid_rspg.php');
  include('rspg_inacbg.php');
  $jenis_tarif = "TARIF RS KELAS C SWASTA";
  $kodetarif ="CS";
}
if($sbu=='DRIYO'){
  include('consid_driyo.php');
  include('driyo_inacbg.php');   
  $jenis_tarif = "TARIF RS KELAS D SWASTA";    
  $kodetarif ="DS";
}
if($sbu=='GRAHU'){
  include('consid_grahu.php');
  include('grahu_inacbg.php');
  $jenis_tarif = "TARIF RS KELAS D SWASTA";    
  $kodetarif ="DS";
}

// include('rspg_inacbg.php');
$no_sep = $nosep;
//Untuk mengirim klaim individual ke data center
$ws_query["metadata"]["method"] = "claim_print";
//$no_sepc='0001R0016120666662';//mencobak...
$ws_query["data"]["nomor_sep"] = $no_sep;
$json_requesti = json_encode($ws_query);

// data yang akan dikirimkan dengan method POST adalah encrypted:
$payloadi = inacbg_encrypt($json_requesti,$key);
// tentukan Content-Type pada http header
$headeri = array("Content-Type: application/x-www-form-urlencoded");
// setup curl
$chi = curl_init();
curl_setopt($chi, CURLOPT_URL, $url_ina);
curl_setopt($chi, CURLOPT_HEADER, 0);
curl_setopt($chi, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chi, CURLOPT_HTTPHEADER,$headeri);
curl_setopt($chi, CURLOPT_POST, 1);
curl_setopt($chi, CURLOPT_POSTFIELDS, $payloadi);
// request dengan curl
$responsei = curl_exec($chi);

// terlebih dahulu hilangkan "----BEGIN ENCRYPTED DATA----\r\n"
// dan hilangkan "----END ENCRYPTED DATA----\r\n" dari response
$first = strpos($responsei, "\n")+1;
$last = strrpos($responsei, "\n")-1;
$responsei = substr($responsei,$first,strlen($responsei) - $first - $last);
$responsei = inacbg_decrypt($responsei,$key);

file_put_contents("claim_print.json", $responsei);

// hasil decrypt adalah format json, ditranslate kedalam array
$msg = json_decode($responsei,true);
// variable data adalah base64 dari file pdf
$pdf = base64_decode($msg["data"]);
$namafile=$no_sep.'klaim.pdf';
// hasilnya adalah berupa binary string $pdf, untuk disimpan:
file_put_contents($namafile,$pdf);
// atau untuk ditampilkan dengan perintah:
header("Content-type:application/pdf");
header("Content-Disposition:attachment;filename=".$namafile.'"');
//echo $pdf;

//memindah file...!!!

rename($namafile , 'pdf_klaim/'.$namafile);

?>