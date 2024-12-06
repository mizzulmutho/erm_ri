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
$tgl1 = trim($row[4]);
$tgl2 = trim($row[5]);

// $tgl1       = gmdate("Y-m-d H:i:s", time()+60*60*7);
$datetime = new DateTime($tglmasuk, new DateTimeZone('Asia/Jakarta'));
$tanggal = $datetime->format('c'); // output: '2021-01-03T02:30:00+01:00'

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

$sbu='RSPG';

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

$lanjut = 'Y';

if($lanjut=='Y'){


    $q="select noktp from afarm_dokter where noktp is not null union all select noktp from GPMENTRY.dbo.afarm_dokter where noktp is not null";
    $hq  = sqlsrv_query($conn, $q);

    $i=1;
    echo "<table border='1' style='border-collapse:collapse;'>";

    while   ($dhq = sqlsrv_fetch_array($hq, SQLSRV_FETCH_ASSOC)){     

        $ktpdokter = $dhq[noktp];


        $u_dokter=$base_url."/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|$ktpdokter";
        $ch = curl_init( $u_dokter);
        $options = array(CURLOPT_RETURNTRANSFER => true,CURLOPT_HTTPHEADER => array('Content-type: application/json','Authorization: Bearer '.$token));
        curl_setopt_array( $ch, $options ); //Setting curl options
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        $r_dokter =  curl_exec($ch); //Getting jSON result string
        curl_close($ch);
        $data_dokter = json_decode($r_dokter,true);
        $iddokter =$data_dokter['entry']['0']['resource']['id'];
        $namadokter =$data_dokter['entry']['0']['resource']['name']['0']['text'];

        echo "
        <tr>
        <td>$ktpdokter</td><td>$iddokter</td>
        </tr>
        ";
    }

    echo "</table>";

}


?>            
