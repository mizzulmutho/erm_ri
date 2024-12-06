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

$noreg = trim($row[0]); 
$kodedokter = trim($row[1]); 
$sbu = trim($row[2]); 
$tglmasuk = trim($row[3]); 
$idh = trim($row[4]); 
$user = trim($row[5]); 
// $idencounter = trim($row[6]); 

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


if ($sbu == "RSPG") {
    $organisation = "100027935";
    $secretKey = "I24XcouWUQUshPEbGW2lpiqnAGlx7FC7cpk7m68mKu8UenB51MTEjHD15ACSjT4N";
    $clientKey = "FZ4GFR6UiiNNxvGCAvI19ekgjPl4HGcHAlQprSxHSO8F3TYW";
    $location = "7ac7c1fd-c926-4ae9-92e2-85a2770af7e2";
    $display_ruang = $namaunit.", RS.PETROKIMIA GRESIK";
} else if ($sbu == "GRAHU") {
    $organisation = "100027085";
    $secretKey = "zd1UpHFOP7hXS3MSIDRHWj5moeF7A0vfqUMxSDMuCMYG2bkmRUPTGbVSnYJLTipO";
    $clientKey = "9QV9qwumO4G2tX9PGgAlGUzxNlu1eyLsuAKJEQqUk3thWiGv";
    $location = "67339f5f-6962-4539-9f4a-a20061a6dd29";
    $display_ruang = $namaunit.", RS.GRHA HUSADA";
} else if ($sbu == "DRIYO") {
    $organisation = "100027782";
    $secretKey = "ossKNGLH6ckYTJ6ZC7XysxLsEI6UxuRddzHEBoLP5udE6f8BRBCA555TGfGELU7f";
    $clientKey = "DPddzovRuGnPAgyKnAPsnq6j87PI4AfVWL1WUVkWDCYiNQbU";
    $location = "1d1de621-1c89-4be6-816a-9f8bc9331a98";
    $display_ruang = $namaunit.", RS.PETROKIMIA DRIYOREJO";
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


//cari nik pasien
$q       = "
SELECT        AFarm_MstPasien.NOKTP
FROM            ARM_REGISTER INNER JOIN
AFarm_MstPasien ON ARM_REGISTER.NORM = AFarm_MstPasien.NORM
WHERE        (ARM_REGISTER.NOREG = '$noreg')
";
$hasil  = sqlsrv_query($conn, $q);                

$data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
$nik_pasien = trim($data[NOKTP]);

//cari nik dokter
$q       = "
SELECT        TOP (200) NOKTP
FROM            Afarm_DOKTER
WHERE KODEDOKTER = '$kodedokter'
";
$hasil  = sqlsrv_query($conn, $q);                

$data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
$nik_dokter = trim($data[NOKTP]);

//cari diagnosa
$q       = "
SELECT      TOP(1)ARM_PERIKSA.NOREG, ARM_PERIKSA.KODEICD, Afarm_ICD.KETERANGAN
FROM            ARM_PERIKSA INNER JOIN
Afarm_ICD ON ARM_PERIKSA.KODEICD = Afarm_ICD.NODAFTAR
WHERE        (ARM_PERIKSA.KODEICD <> '') and noreg='$noreg'
ORDER BY ARM_PERIKSA.NOREG DESC
";
$hasil  = sqlsrv_query($conn, $q);                

$data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
$kodeicd = $data[KODEICD];
$namadiagnosa = $data[KETERANGAN];


// IDENCOUNTER, IDCONDITION1, IDCONDITION2, IDPROCEDURE, IDOBSERVATION1, IDOBSERVATION2, IDOBSERVATION3

//cari encontered;
$qres       = "
SELECT      ID, NOREG, ihsnumber, namapasien, iddokter, namadokter, IDENCOUNTER, IDCONDITION1, IDCONDITION2, IDPROCEDURE, IDOBSERVATION1, IDOBSERVATION2, IDOBSERVATION3
FROM            SS_RESUME_RI
WHERE NOREG='$noreg'
";
$hres  = sqlsrv_query($conn, $qres);                

$data_res    = sqlsrv_fetch_array($hres, SQLSRV_FETCH_ASSOC);                      
$IDENCOUNTER = $data_res[IDENCOUNTER];
$IDCONDITION1 = $data_res[IDCONDITION1];
$IDCONDITION2 = $data_res[IDCONDITION2];
$IDPROCEDURE = $data_res[IDPROCEDURE];
$IDOBSERVATION1 = $data_res[IDOBSERVATION1];
$IDOBSERVATION2 = $data_res[IDOBSERVATION2];
$IDOBSERVATION3 = $data_res[IDOBSERVATION3];


$lanjut = 'Y';

if(empty($nik_pasien)){
    $lanjut='T';
    $eror = 'nik pasien kosong !!!';
}else{
    $u_pasien=$base_url."/Patient?identifier=https://fhir.kemkes.go.id/id/nik|$nik_pasien";
    $ch = curl_init( $u_pasien);
    $options = array(CURLOPT_RETURNTRANSFER => true,CURLOPT_HTTPHEADER => array('Content-type: application/json','Authorization: Bearer '.$token));
    curl_setopt_array( $ch, $options ); //Setting curl options
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    $r_pasien =  curl_exec($ch); //Getting jSON result string
    curl_close($ch);
    $data_pasien = json_decode($r_pasien,true);
    echo $ihsnumber =$data_pasien['entry']['0']['resource']['id'];
    echo $namapasien =$data_pasien['entry']['0']['resource']['name']['0']['text'];
}

echo "<hr>";

if(empty($nik_dokter)){
    $lanjut='T';
    $eror = 'nik dokter kosong !!!';
}else{
    $u_dokter=$base_url."/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|$nik_dokter";
    $ch = curl_init( $u_dokter);
    $options = array(CURLOPT_RETURNTRANSFER => true,CURLOPT_HTTPHEADER => array('Content-type: application/json','Authorization: Bearer '.$token));
    curl_setopt_array( $ch, $options ); //Setting curl options
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
    $r_dokter =  curl_exec($ch); //Getting jSON result string
    curl_close($ch);
    $data_dokter = json_decode($r_dokter,true);
    echo $iddokter =$data_dokter['entry']['0']['resource']['id'];
    echo $namadokter =$data_dokter['entry']['0']['resource']['name']['0']['text'];
}


// if(empty($kodeicd) OR empty($namadiagnosa) ){
//     $lanjut='T';
//     $eror = 'Kode ICD kosong !!!';
// }


if($ihsnumber == '' OR $iddokter == ''){
    $lanjut ='T';
    $eror = 'ihsnumber / iddokter kosong !!!';
}

if($lanjut=='Y'){

    $jenis = 'IMP';
    $namajenis = 'inpatient encounter';

    $datab1 = $datab1 ?? random_bytes(16);assert(strlen($datab1) == 16);$datab1[6] = chr(ord($datab1[6]) & 0x0f | 0x40);$datab1[8] = chr(ord($datab1[8]) & 0x3f | 0x80);
    $datab2 = $datab2 ?? random_bytes(16);assert(strlen($datab2) == 16);$datab2[6] = chr(ord($datab2[6]) & 0x0f | 0x40);$datab2[8] = chr(ord($datab2[8]) & 0x3f | 0x80);
    $datab3 = $datab3 ?? random_bytes(16);assert(strlen($datab3) == 16);$datab3[6] = chr(ord($datab3[6]) & 0x0f | 0x40);$datab3[8] = chr(ord($datab3[8]) & 0x3f | 0x80);
    $datab4 = $datab4 ?? random_bytes(16);assert(strlen($datab4) == 16);$datab4[6] = chr(ord($datab4[6]) & 0x0f | 0x40);$datab4[8] = chr(ord($datab4[8]) & 0x3f | 0x80);
    $datab5 = $datab5 ?? random_bytes(16);assert(strlen($datab5) == 16);$datab5[6] = chr(ord($datab5[6]) & 0x0f | 0x40);$datab5[8] = chr(ord($datab5[8]) & 0x3f | 0x80);
    $datab6 = $datab6 ?? random_bytes(16);assert(strlen($datab6) == 16);$datab6[6] = chr(ord($datab6[6]) & 0x0f | 0x40);$datab6[8] = chr(ord($datab6[8]) & 0x3f | 0x80);
    $datab7 = $datab7 ?? random_bytes(16);assert(strlen($datab7) == 16);$datab7[6] = chr(ord($datab7[6]) & 0x0f | 0x40);$datab7[8] = chr(ord($datab7[8]) & 0x3f | 0x80);
    $datab8 = $datab8 ?? random_bytes(16);assert(strlen($datab8) == 16);$datab8[6] = chr(ord($datab8[6]) & 0x0f | 0x40);$datab8[8] = chr(ord($datab8[8]) & 0x3f | 0x80);
    $datab9 = $datab9 ?? random_bytes(16);assert(strlen($datab9) == 16);$datab9[6] = chr(ord($datab9[6]) & 0x0f | 0x40);$datab9[8] = chr(ord($datab9[8]) & 0x3f | 0x80);
    $datab10 = $datab10 ?? random_bytes(16);assert(strlen($datab10) == 16);$datab10[6] = chr(ord($datab10[6]) & 0x0f | 0x40);$datab10[8] = chr(ord($datab10[8]) & 0x3f | 0x80);
    $datab11 = $datab11 ?? random_bytes(16);assert(strlen($datab11) == 16);$datab11[6] = chr(ord($datab11[6]) & 0x0f | 0x40);$datab11[8] = chr(ord($datab11[8]) & 0x3f | 0x80);
    $datab12 = $datab12 ?? random_bytes(16);assert(strlen($datab12) == 16);$datab12[6] = chr(ord($datab12[6]) & 0x0f | 0x40);$datab12[8] = chr(ord($datab12[8]) & 0x3f | 0x80);
    $datab13 = $datab13 ?? random_bytes(16);assert(strlen($datab13) == 16);$datab13[6] = chr(ord($datab13[6]) & 0x0f | 0x40);$datab13[8] = chr(ord($datab13[8]) & 0x3f | 0x80);
    $datab14 = $datab14 ?? random_bytes(16);assert(strlen($datab14) == 16);$datab14[6] = chr(ord($datab14[6]) & 0x0f | 0x40);$datab14[8] = chr(ord($datab14[8]) & 0x3f | 0x80);
    $datab15 = $datab15 ?? random_bytes(16);assert(strlen($datab15) == 16);$datab15[6] = chr(ord($datab15[6]) & 0x0f | 0x40);$datab15[8] = chr(ord($datab15[8]) & 0x3f | 0x80);
    $datab16 = $datab16 ?? random_bytes(16);assert(strlen($datab16) == 16);$datab16[6] = chr(ord($datab16[6]) & 0x0f | 0x40);$datab16[8] = chr(ord($datab16[8]) & 0x3f | 0x80);
    $datab17 = $datab17 ?? random_bytes(16);assert(strlen($datab17) == 16);$datab17[6] = chr(ord($datab17[6]) & 0x0f | 0x40);$datab17[8] = chr(ord($datab17[8]) & 0x3f | 0x80);
    $datab18 = $datab18 ?? random_bytes(16);assert(strlen($datab18) == 16);$datab18[6] = chr(ord($datab18[6]) & 0x0f | 0x40);$datab18[8] = chr(ord($datab18[8]) & 0x3f | 0x80);
    $datab19 = $datab19 ?? random_bytes(16);assert(strlen($datab19) == 16);$datab19[6] = chr(ord($datab19[6]) & 0x0f | 0x40);$datab19[8] = chr(ord($datab19[8]) & 0x3f | 0x80);
    $datab20 = $datab20 ?? random_bytes(16);assert(strlen($datab20) == 16);$datab20[6] = chr(ord($datab20[6]) & 0x0f | 0x40);$datab20[8] = chr(ord($datab20[8]) & 0x3f | 0x80);
    $datab21 = $datab21 ?? random_bytes(16);assert(strlen($datab21) == 16);$datab21[6] = chr(ord($datab21[6]) & 0x0f | 0x40);$datab21[8] = chr(ord($datab21[8]) & 0x3f | 0x80);
    $datab22 = $datab22 ?? random_bytes(16);assert(strlen($datab22) == 16);$datab22[6] = chr(ord($datab22[6]) & 0x0f | 0x40);$datab22[8] = chr(ord($datab22[8]) & 0x3f | 0x80);
    $datab23 = $datab23 ?? random_bytes(16);assert(strlen($datab23) == 16);$datab23[6] = chr(ord($datab23[6]) & 0x0f | 0x40);$datab23[8] = chr(ord($datab23[8]) & 0x3f | 0x80);
    $datab24 = $datab24 ?? random_bytes(16);assert(strlen($datab24) == 16);$datab24[6] = chr(ord($datab24[6]) & 0x0f | 0x40);$datab24[8] = chr(ord($datab24[8]) & 0x3f | 0x80);
    $datab25 = $datab25 ?? random_bytes(16);assert(strlen($datab25) == 16);$datab25[6] = chr(ord($datab25[6]) & 0x0f | 0x40);$datab25[8] = chr(ord($datab25[8]) & 0x3f | 0x80);
    $datab26 = $datab26 ?? random_bytes(16);assert(strlen($datab26) == 16);$datab26[6] = chr(ord($datab26[6]) & 0x0f | 0x40);$datab26[8] = chr(ord($datab26[8]) & 0x3f | 0x80);
    $datab27 = $datab27 ?? random_bytes(16);assert(strlen($datab27) == 16);$datab27[6] = chr(ord($datab27[6]) & 0x0f | 0x40);$datab27[8] = chr(ord($datab27[8]) & 0x3f | 0x80);
    $datab28 = $datab28 ?? random_bytes(16);assert(strlen($datab28) == 16);$datab28[6] = chr(ord($datab28[6]) & 0x0f | 0x40);$datab28[8] = chr(ord($datab28[8]) & 0x3f | 0x80);
    $datab29 = $datab29 ?? random_bytes(16);assert(strlen($datab29) == 16);$datab29[6] = chr(ord($datab29[6]) & 0x0f | 0x40);$datab29[8] = chr(ord($datab29[8]) & 0x3f | 0x80);
    $datab30 = $datab30 ?? random_bytes(16);assert(strlen($datab30) == 16);$datab30[6] = chr(ord($datab30[6]) & 0x0f | 0x40);$datab30[8] = chr(ord($datab30[8]) & 0x3f | 0x80);
    $datab31 = $datab31 ?? random_bytes(16);assert(strlen($datab31) == 16);$datab31[6] = chr(ord($datab31[6]) & 0x0f | 0x40);$datab31[8] = chr(ord($datab31[8]) & 0x3f | 0x80);
    $datab32 = $datab32 ?? random_bytes(16);assert(strlen($datab32) == 16);$datab32[6] = chr(ord($datab32[6]) & 0x0f | 0x40);$datab32[8] = chr(ord($datab32[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    $uuidb1 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab1), 4));echo "<br>";
    $uuidb2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab2), 4));echo "<br>";
    $uuidb3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab3), 4));echo "<br>";
    $uuidb4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab4), 4));echo "<br>";
    $uuidb5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab5), 4));echo "<br>";
    $uuidb6 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab6), 4));echo "<br>";
    $uuidb7 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab7), 4));echo "<br>";
    $uuidb8 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab8), 4));echo "<br>";
    $uuidb9 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab9), 4));echo "<br>";
    $uuidb10 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab10), 4));echo "<br>";
    $uuidb11 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab11), 4));echo "<br>";
    $uuidb12 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab12), 4));echo "<br>";
    $uuidb13 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab13), 4));echo "<br>";
    $uuidb14 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab14), 4));echo "<br>";
    $uuidb15 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab15), 4));echo "<br>";
    $uuidb16 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab16), 4));echo "<br>";
    $uuidb17 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab17), 4));echo "<br>";
    $uuidb18 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab18), 4));echo "<br>";
    $uuidb19 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab19), 4));echo "<br>";
    $uuidb20 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab20), 4));echo "<br>";
    $uuidb21 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab21), 4));echo "<br>";
    $uuidb22 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab22), 4));echo "<br>";
    $uuidb23 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab23), 4));echo "<br>";
    $uuidb24 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab24), 4));echo "<br>";
    $uuidb25 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab25), 4));echo "<br>";
    $uuidb26 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab26), 4));echo "<br>";
    $uuidb27 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab27), 4));echo "<br>";
    $uuidb28 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab28), 4));echo "<br>";
    $uuidb29 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab29), 4));echo "<br>";
    $uuidb30 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab30), 4));echo "<br>";
    $uuidb31 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab31), 4));echo "<br>";
    $uuidb32 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($datab32), 4));echo "<br>";

    include('json_data2.php');

    // $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $base_urle,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $json_data,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ),

    ));

    echo "JSON DATA : ".$json_data;

    file_put_contents("test.json", $json_data);

    // $response = curl_exec($curl);
    // $DATA = json_decode($response, true);
    // $idencounter = $DATA["entry"][0]["response"]["resourceID"];

    if ($DATA["entry"][0]["response"]["resourceType"] == "Encounter") {
        $namapasien          = str_replace("'","`",$namapasien);
        $namadokter          = str_replace("'","`",$namadokter);

        $q="
        INSERT INTO SS_RESUME_RI
        (NOREG, ihsnumber, namapasien, iddokter, namadokter, IDENCOUNTER) 
        VALUES
        ('$noreg','$ihsnumber','$namapasien','$iddokter','$namadokter','$idencounter')";
        $h1  = sqlsrv_query($conn, $q);
        $eror = 'Data Success Terkirim';
    }else{
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}else{

}

echo $eror ;


?>            
