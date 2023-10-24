<?php
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$q    = "select ttd from ERM_TTD where id='$id'";
$hasil  = mysqli_query($koneksi, $q);  
$data  = mysqli_fetch_array($hasil);

// $ttd = str_replace("./signature/","",$data2[ttd]);
$ttd = $data[ttd];
unlink($ttd);


$result     = array();
$imagedata  = base64_decode($_POST['img_data']);
$filename   = md5(date("dmYhisA"));
$file_name  = './signature/' . $filename . '.png';
file_put_contents($file_name, $imagedata);
$result['status']     = 1;
$result['file_name']  = $file_name;
echo json_encode($result);

// $ttd = json_encode($result);

$q = "update notulen set ttd='$file_name' where id='$id'";
$hs = mysqli_query($koneksi,$q);	