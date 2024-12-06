<?php 

$auth_url = "https://api-satusehat.kemkes.go.id/oauth2/v1";
$base_url = "https://api-satusehat.kemkes.go.id/fhir-r4/v1";
$client_id   = "9QV9qwumO4G2tX9PGgAlGUzxNlu1eyLsuAKJEQqUk3thWiGv";
$client_secret  = "zd1UpHFOP7hXS3MSIDRHWj5moeF7A0vfqUMxSDMuCMYG2bkmRUPTGbVSnYJLTipO";
$consent_url = "https://api-satusehat.dto.kemkes.go.id/consent/v1";

//token.
$post_data="client_id=$client_id&client_secret=$client_secret";
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

//pasien
$u_pasien=$base_url."/Patient?identifier=https://fhir.kemkes.go.id/id/nik|1402010912590002";
$ch = curl_init( $u_pasien);
$options = array(
   CURLOPT_RETURNTRANSFER => true,
   CURLOPT_HTTPHEADER => array('Content-type: application/json','Authorization: Bearer '.$token)
);
curl_setopt_array( $ch, $options ); //Setting curl options
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
$r_pasien =  curl_exec($ch); //Getting jSON result string
curl_close($ch);

$data_pasien = json_decode($r_pasien,true);

echo $ihsnumber =$data_pasien['entry']['0']['resource']['id'];

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
                            "display": "Lantai 1, Poliklinik Rawat Jalan"
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


?>            
