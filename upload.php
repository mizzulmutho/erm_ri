<?php
// include 'koneksi.php';

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$con = sqlsrv_connect( $serverName, $connectionInfo);

$id = $_GET["id"];
$row = explode('|',$id);
$jenis  = trim($row[0]);
$noreg = trim($row[1]); 

// echo $nama = $_POST['namapasien'], "<br>";
// echo $noreg = $_POST['noreg'], "<br>";
// echo $norm = $_POST['norm'], "<br>";
// echo $idheader = $_POST['idheader'], "<br>";

$folderPath = "upload/";
if(empty($_POST['signed'])){
    echo "Kosong";
}else{
    $image_parts = explode(";base64,", $_POST['signed']); 
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . uniqid() . '.'.$image_type;
    file_put_contents($file, $image_base64);
    echo "Tanda Tangan Sukses Diupload ";

    if($jenis=='informconsent')
        $qInsert = "UPDATE ERM_RI_INFORMCONSENT SET ic24 = '$file' WHERE NOREG='$noreg'";
    echo $qInsert;
// $params = array($nama, $folderPath);
    $result = sqlsrv_query($con, $qInsert);

// sqlsrv_close($con);
}
