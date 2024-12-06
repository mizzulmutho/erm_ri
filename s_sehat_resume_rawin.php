<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$auth_url = "https://api-satusehat.kemkes.go.id/oauth2/v1";
$base_url = "https://api-satusehat.kemkes.go.id/fhir-r4/v1";
$consent_url = "https://api-satusehat.dto.kemkes.go.id/consent/v1";


$id = $_GET["id"];
$row = explode('|',$id);

// $sbu = trim($row[0]);
// $jenis = trim($row[1]);
// $noreg = trim($row[2]); 
// $IDENCOUNTER = trim($row[3]); 

$noreg = trim($row[0]);
$sbu = trim($row[1]); 
$IDENCOUNTER = trim($row[2]);
$jenis = trim($row[3]); 


//tglkeluar
$q="
SELECT 
CONVERT(VARCHAR, ARM_PERIKSA.TGLKELUAR, 25) AS tglkeluar,
CONVERT(VARCHAR, ARM_PERIKSA.TGLMASUK, 23) AS tglmasuk
from ARM_PERIKSA where NOREG = '$noreg' and TGLKELUAR <> ''";
$hasil  = sqlsrv_query($conn, $q);                
$data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
$tglkeluar = $data[tglkeluar];
$tglperiksa = $data[tglmasuk];

// $tgl1       = gmdate("Y-m-d H:i:s", time()+60*60*7);
$datetime = new DateTime($tglmasuk, new DateTimeZone('Asia/Jakarta'));
$tanggal = $datetime->format('c'); // output: '2021-01-03T02:30:00+01:00'

$datetime = new DateTime($tglkeluar, new DateTimeZone('Asia/Jakarta'));
$tglkeluar = $datetime->format('c'); // output: '2021-01-03T02:30:00+01:00'

$datetime = new DateTime($tglperiksa, new DateTimeZone('Asia/Jakarta'));
$tglperiksa = $datetime->format('c'); // output: '2021-01-03T02:30:00+01:00'


//nama ruang
$q       = "
SELECT        ARM_REGISTER.TUJUAN, Afarm_Unitlayanan.NAMAUNIT
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')
";
$hasil  = sqlsrv_query($conn, $q);                
$data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
$namaunit = $data[NAMAUNIT];


$qres       = "
SELECT      *
FROM            SS_RI_RESUME
WHERE NOREG='$noreg'
";
$hres  = sqlsrv_query($conn, $qres);                

$data_res    = sqlsrv_fetch_array($hres, SQLSRV_FETCH_ASSOC);  

$NOREG = $data_res[NOREG];
$ihsnumber = $data_res[ihsnumber];
$namapasien = $data_res[namapasien];
$iddokter = $data_res[iddokter];
$namadokter = $data_res[namadokter];
$IDENCOUNTER = $data_res[IDENCOUNTER];
$IDRENCANA_RAWAT = $data_res[IDRENCANA_RAWAT];


if ($sbu == "RSPG") {
    $organisation = "100027935";
    $secretKey = "I24XcouWUQUshPEbGW2lpiqnAGlx7FC7cpk7m68mKu8UenB51MTEjHD15ACSjT4N";
    $clientKey = "FZ4GFR6UiiNNxvGCAvI19ekgjPl4HGcHAlQprSxHSO8F3TYW";
    $location = "7ac7c1fd-c926-4ae9-92e2-85a2770af7e2";
    $display_ruang = $namaunit.", RS.PETROKIMIA GRESIK";
    $idperawat = '10003177250';
    $nmperawat = 'DERINO PRATAMA';
} else if ($sbu == "GRAHU") {
    $organisation = "100027085";
    $secretKey = "zd1UpHFOP7hXS3MSIDRHWj5moeF7A0vfqUMxSDMuCMYG2bkmRUPTGbVSnYJLTipO";
    $clientKey = "9QV9qwumO4G2tX9PGgAlGUzxNlu1eyLsuAKJEQqUk3thWiGv";
    $location = "67339f5f-6962-4539-9f4a-a20061a6dd29";
    $display_ruang = $namaunit.", RS.GRHA HUSADA";
    $idperawat = '10018495421';
    $nmperawat = 'GUSTI ANDITA SISWIDYANTI';
} else if ($sbu == "DRIYO") {
    $organisation = "100027782";
    $secretKey = "ossKNGLH6ckYTJ6ZC7XysxLsEI6UxuRddzHEBoLP5udE6f8BRBCA555TGfGELU7f";
    $clientKey = "DPddzovRuGnPAgyKnAPsnq6j87PI4AfVWL1WUVkWDCYiNQbU";
    $location = "1d1de621-1c89-4be6-816a-9f8bc9331a98";
    $display_ruang = $namaunit.", RS.PETROKIMIA DRIYOREJO";
    $idperawat = '10004372707';
    $nmperawat = 'MUSTIKA SYARIL KHAFIDHOH';
} else {
    $consID = "";
    $consSecret = "";
    $userKey = "";
    $ppkPelayanan = "";
}


//token.
$post_data="client_id=$clientKey&client_secret=$secretKey";
$url=$auth_url."/accesstoken?grant_type=client_credentials";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));   
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result, true);

$token = $data['access_token'];

$link = 's_sehat_resume_fase2.php?id='.trim($noreg).'|'.trim($sbu).'|'.trim($IDENCOUNTER).'|';

for($i=1;$i<=34;$i++){

    // echo "
    // <script>
    // window.open('$link$i', '_blank');
    // </script>
    // ";
    echo $i;
    echo "
    <iframe src='$link$i' width='100%' scrolling='yes' style='overflow:hidden; margin-top:-4px; margin-left:-4px; border:none;'></iframe>
    ";
    echo "<br>";

}


?>            

