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

// uuid
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);
    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    $data1 = $data1 ?? random_bytes(16);assert(strlen($data1) == 16);$data1[6] = chr(ord($data1[6]) & 0x0f | 0x40);$data1[8] = chr(ord($data1[8]) & 0x3f | 0x80);
    $data2 = $data2 ?? random_bytes(16);assert(strlen($data2) == 16);$data2[6] = chr(ord($data2[6]) & 0x0f | 0x40);$data2[8] = chr(ord($data2[8]) & 0x3f | 0x80);
    $data3 = $data3 ?? random_bytes(16);assert(strlen($data3) == 16);$data3[6] = chr(ord($data3[6]) & 0x0f | 0x40);$data3[8] = chr(ord($data3[8]) & 0x3f | 0x80);
    $data4 = $data4 ?? random_bytes(16);assert(strlen($data4) == 16);$data4[6] = chr(ord($data4[6]) & 0x0f | 0x40);$data4[8] = chr(ord($data4[8]) & 0x3f | 0x80);
    $data5 = $data5 ?? random_bytes(16);assert(strlen($data5) == 16);$data5[6] = chr(ord($data5[6]) & 0x0f | 0x40);$data5[8] = chr(ord($data5[8]) & 0x3f | 0x80);
    $data6 = $data6 ?? random_bytes(16);assert(strlen($data6) == 16);$data6[6] = chr(ord($data6[6]) & 0x0f | 0x40);$data6[8] = chr(ord($data6[8]) & 0x3f | 0x80);
    $data7 = $data7 ?? random_bytes(16);assert(strlen($data6) == 16);$data6[6] = chr(ord($data6[6]) & 0x0f | 0x40);$data6[8] = chr(ord($data6[8]) & 0x3f | 0x80);
    $data8 = $data8 ?? random_bytes(16);assert(strlen($data6) == 16);$data6[6] = chr(ord($data6[6]) & 0x0f | 0x40);$data6[8] = chr(ord($data6[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    $uuid1 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data1), 4));echo "<br>";
    $uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data2), 4));echo "<br>";
    $uuid3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data3), 4));echo "<br>";
    $uuid4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data4), 4));echo "<br>";
    $uuid5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data5), 4));echo "<br>";
    $uuid6 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data6), 4));echo "<br>";
    $uuid7 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data7), 4));echo "<br>";
    $uuid8 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data8), 4));echo "<br>";

    //post encontered---
    // $u_encontered=$base_url."/Condition";

    $array = array("A90", "A09", "B34", "I12", "J06", "K30");
    $kodeicd =  $array[array_rand($array, 1)];

    $q       = "
    SELECT      TOP(1)Afarm_ICD.KETERANGAN
    FROM            Afarm_ICD WHERE        (Afarm_ICD.NODAFTAR ='$kodeicd')
    ";
    $hasil  = sqlsrv_query($conn, $q);                

    $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
    $namadiagnosa = $data[KETERANGAN];

    $array = array("87.49", "90.59","90.59","87.49","90.59","90.59");
    $kodeicd9 =  $array[array_rand($array, 1)];

    $q       = "
    SELECT      TOP(1) KETERANGAN
    FROM             AFARM_ICD9 WHERE        (NODAFTAR ='$kodeicd9')
    ";
    $hasil  = sqlsrv_query($conn, $q);                

    $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
    $namaprocedure = $data[KETERANGAN];

    if($kodeicd9=='90.59'){
        $tindakan = "LAB, TERAPI";
    }else{
        $tindakan = "FOTO THORAX, TERAPI";
    }

    include('json_data.php');

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $base_url,
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

    file_put_contents("test.json", $json_data);

    $response = curl_exec($curl);
    $DATA = json_decode($response, true);

    $jumlah = count($DATA[entry]);

    if ($jumlah>0){
        for($i=0;$i<$jumlah;$i++){

            $idencounter = $DATA["entry"][$i]["response"]["resourceID"];

            $namapasien          = str_replace("'","`",$namapasien);
            $namadokter          = str_replace("'","`",$namadokter);

            // if($DATA["entry"][$i]["response"]["resourceType"] == "Encounter") {
            //     $namapasien          = str_replace("'","`",$namapasien);
            //     $namadokter          = str_replace("'","`",$namadokter);
            //     $q="
            //     INSERT INTO SS_RESUME_RI
            //     (NOREG, ihsnumber, namapasien, iddokter, namadokter, IDENCOUNTER) 
            //     VALUES
            //     ('$noreg','$ihsnumber','$namapasien','$iddokter','$namadokter','$idencounter')";
            //     $h1  = sqlsrv_query($conn, $q);
            // }

            if($i==0){
                $q="
                INSERT INTO SS_RESUME_RI
                (NOREG, ihsnumber, namapasien, iddokter, namadokter, IDENCOUNTER) 
                VALUES
                ('$noreg','$ihsnumber','$namapasien','$iddokter','$namadokter','$idencounter')";
            }
            if($i==1){
                $q="UPDATE SS_RESUME_RI SET IDCONDITION1='$idencounter' where NOREG='$noreg'";
            }
            if($i==2){
                $q="UPDATE SS_RESUME_RI SET IDCONDITION2='$idencounter' where NOREG='$noreg'";
            }
            if($i==3){
                $q="UPDATE SS_RESUME_RI SET IDPROCEDURE='$idencounter' where NOREG='$noreg'";
            }
            if($i==4){
                $q="UPDATE SS_RESUME_RI SET IDOBSERVATION1='$idencounter' where NOREG='$noreg'";
            }
            if($i==5){
                $q="UPDATE SS_RESUME_RI SET IDOBSERVATION2='$idencounter' where NOREG='$noreg'";
            }
            if($i==6){
                $q="UPDATE SS_RESUME_RI SET IDOBSERVATION3='$idencounter' where NOREG='$noreg'";
            }

            $h1  = sqlsrv_query($conn, $q);

        }


        $eror = 'Data Success Terkirim';
        // include('s_sehat_resume2.php');

    }else{
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}else{

}

echo "<hr>";

echo $eror ;

// echo "
// <script>
// alert('".$eror."');
// history.go(-1);
// </script>
// ";
echo "
<script>
alert('".$eror."');
window.location.replace('s_resume.php?id=$noreg|$kodedokter|$sbu|$tglawal|$id|$user');
</script>
";


?>            
