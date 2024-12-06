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
SELECT        ARM_REGISTER.TUJUAN, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.JENIS2
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')
";
$hasil  = sqlsrv_query($conn, $q);                
$data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
$namaunit = $data[NAMAUNIT];
$jenis2 = $data[JENIS2];

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


if($lanjut=='Y'){


    if($jenis2=='RJU' OR $jenis2=='RJS'){
        $jenis = 'AMB';
        $namajenis = 'Ambulatory';
    }
    if($jenis2=='IGD'){
        $jenis = 'EMER';
        $namajenis = 'Emergency';
    }
    if($jenis2=='RI'){
        $jenis = 'IMP';
        $namajenis = 'inpatient encounter';
    }


    //post encontered---
    $u_encontered=$base_url."/Encounter";

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $u_encontered,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "resourceType": "Encounter",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/encounter/' . $organisation . '",
                "value": "' . $noreg . '"
            }
            ],
            "status": "arrived",
            "class": {
                "system": "http://terminology.hl7.org/CodeSystem/v3-ActCode",
                "code": "' . $jenis . '",
                "display": "' . $namajenis . '"
                },
                "subject": {
                    "reference": "Patient/' . $ihsnumber . '",
                    "display": "' . $namapasien . '"
                    },
                    "participant": [
                    {
                        "type": [
                        {
                            "coding": [
                            {
                                "system": "http://terminology.hl7.org/CodeSystem/v3-ParticipationType",
                                "code": "ATND",
                                "display": "attender"
                            }
                            ]
                        }
                        ],
                        "individual": {
                            "reference": "Practitioner/' . $iddokter . '",
                            "display": "' . $namadokter . '"
                        }
                    }
                    ],
                    "period": {
                        "start": "' . $tanggal . '"
                        },
                        "location": [
                        {
                            "location": {
                                "reference": "Location/' . $location . '",
                                "display": "' . $display_ruang . '"
                            }
                        }
                        ],
                        "statusHistory": [
                        {
                            "status": "arrived",
                            "period": {
                                "start": "' . $tanggal . '",
                                "end": "' . $tanggal . '"
                            }
                        }
                        ],
                        "serviceProvider": {
                            "reference": "Organization/' . $organisation . '"
                        }
                    }',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ),
                ));


    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idencounter = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q = "UPDATE arm_register SET IDENCOUNTERSS = '$idencounter' WHERE NOREG = '$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Success';
    }else{
        $eror = $response;
    }

}else{

}



$kcondition='T';
if($eror=='Success'){
    $kcondition='Y';
}

if($kcondition=='Y'){

    $qe       = "SELECT      IDENCOUNTERSS from  ARM_REGISTER where noreg='$noreg'";
    $hasile  = sqlsrv_query($conn, $qe);                
    $datae    = sqlsrv_fetch_array($hasile, SQLSRV_FETCH_ASSOC);                      
    $idencounter = trim($datae[IDENCOUNTERSS]);


    if($jenis2=='RJU' OR $jenis2=='RJS' OR $jenis2=='IGD' ){
        $array = array("Z50.1", "Z49.1", "Z09.8", "E11.9", "I25.1", "Z50.1","Z49.1");
        $kodeicd =  $array[array_rand($array, 1)];
    }
    if($jenis2=='RI'){
        $array = array("A90", "A09", "B34", "I12", "J06", "K30");
        $kodeicd =  $array[array_rand($array, 1)];
    }


    $q       = "
    SELECT      TOP(1)Afarm_ICD.KETERANGAN
    FROM            Afarm_ICD WHERE        (Afarm_ICD.NODAFTAR ='$kodeicd')
    ";
    $hasil  = sqlsrv_query($conn, $q);                

    $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
    $namadiagnosa = $data[KETERANGAN];



    //post encontered---
    $u_condition=$base_url."/Condition";

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $u_condition,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "resourceType": "Condition",
            "clinicalStatus": {
             "coding": [
             {
               "system": "http://terminology.hl7.org/CodeSystem/condition-clinical",
               "code": "active",
               "display": "Active"
           }
           ]
           },
           "category": [
           {
              "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/condition-category",
                "code": "encounter-diagnosis",
                "display": "Encounter Diagnosis"
            }
            ]
        }
        ],
        "code": {
         "coding": [
         {
           "system": "http://hl7.org/fhir/sid/icd-10",
           "code": "' . $kodeicd . '",
           "display": "' . $namadiagnosa . '"
       }
       ]
       },
       "subject": {
         "reference": "Patient/' . $ihsnumber . '",
         "display": "' . $namapasien . '"
         },
         "encounter": {
             "reference": "Encounter/' . $idencounter . '"
             },
             "onsetDateTime": "' . $tanggal . '",
             "recordedDate" : "' . $tanggal . '"
         }',
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ),

     ));


    $response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idencounter_icd = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="INSERT INTO SS_DIAGNOSA(IDENCOUNTER,NOREG,KODEICD,IDDIAGNOSA,ihsnumber,iddokter,namapasien,namadokter,namadiagnosa) 
        VALUES('$idencounter','$noreg','$kodeicd','$idencounter_icd','$ihsnumber','$iddokter','$namapasien','$namadokter','$namadiagnosa')";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}


$q="INSERT INTO zzz_mutok4(noreg,a) 
VALUES('$noreg','Y')";
$h1  = sqlsrv_query($conn, $q);

echo "
<script>
alert('".$eror."');
window.location.replace('encountered_ss.php?id=$tgl1|$tgl2');
</script>
";

?>            
