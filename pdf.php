<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

header('Access-Control-Allow-Origin: http://192.168.10.4:1234/keuangan/tagihan_bpjs');
$user = $_POST['name'];
echo ("Hello from server: $user");

?>

