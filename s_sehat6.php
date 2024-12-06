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

$qe       = "SELECT  IDENCOUNTER, NOREG, KODEICD, IDDIAGNOSA, ihsnumber, iddokter, namapasien, namadokter, namadiagnosa
FROM            SS_DIAGNOSA
where noreg='$noreg' and ihsnumber is not null";
$hasile  = sqlsrv_query($conn, $qe);                
$datae    = sqlsrv_fetch_array($hasile, SQLSRV_FETCH_ASSOC);                      
$idencounter = trim($datae[IDENCOUNTER]);
$ihsnumber = trim($datae[ihsnumber]);
$namapasien = trim($datae[namapasien]);
$iddokter = trim($datae[iddokter]);
$kodeicd = trim($datae[KODEICD]);


if(empty($idencounter)){
   $lanjut='T';
   $eror = 'idencounter kosong !!!';
}


if($lanjut=='Y'){

    $array = array("87.49", "90.59","90.59","87.49","90.59","90.59");
    echo $kodeicd9 =  $array[array_rand($array, 1)];

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



    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-satusehat.kemkes.go.id/fhir-r4/v1/Procedure',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "resourceType": "Procedure",
            "status": "completed",
            "category": {
                "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "103693007",
                    "display": "Diagnostic_procedure"
                }
                ],
                "text": "Diagnostic procedure"
                },
                "code": {
                    "coding": [
                    {
                        "system": "http://hl7.org/fhir/sid/icd-9-cm",
                        "code": "' . $kodeicd9 . '",
                        "display": "' . $namaprocedure . '"
                    }
                    ]
                    },
                    "subject": {
                        "reference": "Patient/' . $ihsnumber . '",
                        "display": "' . $namapasien . '"
                        },
                        "encounter": {
                            "reference": "Encounter/' . $idencounter . '",
                            "display": "Tindakan pada ' . $namapasien . ' pada tanggal ' . substr($tanggal, 0, 10) . '"
                            },
                            "performedPeriod": {
                                "start": "' . $tanggal .  '",
                                "end": "' . $tanggal .  '"
                                },
                                "performer": [
                                {
                                    "actor": {
                                        "reference": "Practitioner/' . $iddokter . '",
                                        "display": "' . $namadokter . '"
                                    }
                                }
                                ],
                                "reasonCode": [
                                {
                                    "coding": [
                                    {
                                        "system": "http://hl7.org/fhir/sid/icd-10",
                                        "code": "' . $kodeicd . '",
                                        "display": "' . $namadiagnosa . '"
                                    }
                                    ]
                                }
                                ],
                                "bodySite": [
                                {
                                    "coding": [
                                    {
                                        "system": "http://snomed.info/sct",
                                        "code": "38266002",
                                        "display": "Entire body as a whole"
                                    }
                                    ]
                                }
                                ],
                                "note": [
                                {
                                    "text": "' . $tindakan . '"
                                }
                                ]
                            }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Bearer ' . $token
                            ),
                        ));

    echo $response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string

    $DATA = json_decode($response, true);

    curl_close($curl);

    if ($DATA["status"] == "completed") {
        //$result = $this->satusehat_model->updateTindakanLain($noreg, $DATA["id"]);
        // return array(
        //     'id' => $DATA["id"],
        //     'status' => 'sukses',
        //     'pesan' => 'sukses'
        // );

        $eror = 'Data Procedure Success Terkirim';


    } else {
        return array(
            'id' => null,
            'status' => 'gagal',
            'pesan' => $DATA["issue"][0]["details"]["text"]
        );

        $eror = 'Data Procedure Gagal Terkirim';
    }


}

$q="UPDATE zzz_mutok4 set c='Y' where noreg='$noreg'";
$h1  = sqlsrv_query($conn, $q);

echo "
<script>
alert('".$eror."');
history.go(-1);
</script>
";

?>            
