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

if($jenis==35){ //DIET

    $qu="SELECT diet  FROM ERM_DIET where noreg='$noreg'";
    $h1u  = sqlsrv_query($conn, $qu);        
    $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
    $diet = $d1u['diet'];

    if(empty($diet)){
        $diet="Rekomendasi diet rendah lemak";
    }

    $url =$base_url."/Composition";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "Composition",
            "identifier": {
                "system": "http://sys-ids.kemkes.go.id/composition/10000004",
                "value": "'.$noreg.'"
                },
                "status": "final",
                "type": {
                    "coding": [
                    {
                        "system": "http://loinc.org",
                        "code": "18842-5",
                        "display": "Discharge summary"
                    }
                    ]
                    },
                    "category": [
                    {
                        "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "LP173421-1",
                            "display": "Report"
                        }
                        ]
                    }
                    ],
                    "subject": {
                        "reference": "Patient/'.$ihsnumber.'",
                        "display": "'.$namapasien.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$IDENCOUNTER.'",
                            "display": "Kunjungan '.$namapasien.'"
                            },
                            "date": "'.$tanggal.'",
                            "author": [
                            {
                                "reference": "Practitioner/'.$iddokter.'",
                                "display": "'.$namadokter.'"
                            }
                            ],
                            "title": "Resume Medis Rawat Inap",
                            "custodian": {
                                "reference": "Organization/'.$organisation.'"
                                },
                                "section": [
                                {
                                    "code": {
                                        "coding": [
                                        {
                                            "system": "http://loinc.org",
                                            "code": "42344-2",
                                            "display": "Discharge diet (narrative)"
                                        }
                                        ]
                                        },
                                        "text": {
                                            "status": "additional",
                                            "div": "'.$diet.'"
                                        }
                                    }
                                    ]
                                }
                                ',
                                CURLOPT_HTTPHEADER => array(
                                  'Content-Type: application/json',
                                  'Authorization: Bearer ' . $token
                              ),

                            ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDDIET='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }


}


if($jenis==34){ //IDRENCANAPULANG_CAREPLAN

    $qe="SELECT resume39 FROM ERM_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $ku = $de['resume8'];
    $resume39 = $de['resume39'];


    $url =$base_url."/CarePlan";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "CarePlan",
            "title": "Perencanaan Pemulangan Pasien",
            "status": "active",
            "category": [
            {
                "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "736372004",
                    "display": "Discharge care plan"
                }
                ]
            }
            ],
            "intent": "plan",
            "description": "Jenis aktivitas yang boleh dilakukan Pasien '.$resume39.'",
            "subject": {
                "reference": "Patient/'.$ihsnumber.'",
                "display": "'.$namapasien.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$IDENCOUNTER.'"
                    },
                    "created": "'.$tanggal.'",
                    "author": {
                        "reference": "Practitioner/'.$iddokter.'"
                    }
                }
                ',
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json',
                  'Authorization: Bearer ' . $token
              ),

            ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDRENCANAPULANG_CAREPLAN='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }


}


if($jenis==33){ //IDRENCANAPULANG_OBSERVATION
    $qe="SELECT IDPENGELUARANOBAT_MEDICATION,IDPERESEPAN_MEDICATIONREQUEST FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDPENGELUARANOBAT_MEDICATION = $de['IDPENGELUARANOBAT_MEDICATION'];
    $IDPERESEPAN_MEDICATIONREQUEST = $de['IDPERESEPAN_MEDICATIONREQUEST'];

    $array = array("1", "2","3","4","5");
    $jumlah =  $array[array_rand($array, 1)];

    $url =$base_url."/Observation";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "Observation",
            "status": "final",
            "category": [
            {
                "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                    "code": "survey",
                    "display": "Survey"
                }
                ]
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                    "code": "OC000055",
                    "display": "Kriteria Pasien yang dilakukan Rencana Pemulangan"
                }
                ]
                },
                "subject": {
                    "reference": "Patient/'.$ihsnumber.'"
                    },
                    "performer": [
                    {
                        "reference": "Practitioner/'.$iddokter.'"
                    }
                    ],
                    "encounter": {
                        "reference": "Encounter/'.$IDENCOUNTER.'",
                        "display": "Pemeriksaan Kriteria untuk Rencana Pemulangan '.$namapasien.'"
                        },
                        "effectiveDateTime": "'.$tanggal.'",
                        "issued": "'.$tanggal.'",
                        "valueCodeableConcept": {
                            "coding": [
                            {
                                "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                "code": "OV000072",
                                "display": "Pasien dengan perawatan berkelanjutan atau panjang"
                            }
                            ]
                        }
                    }
                    ',
                    CURLOPT_HTTPHEADER => array(
                      'Content-Type: application/json',
                      'Authorization: Bearer ' . $token
                  ),

                ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDRENCANAPULANG_OBSERVATION='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}

if($jenis==32){ //IDPENGELUARANOBAT_MEDICATIONDISPENCE

    $qe="SELECT IDPENGELUARANOBAT_MEDICATION,IDPERESEPAN_MEDICATIONREQUEST FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDPENGELUARANOBAT_MEDICATION = $de['IDPENGELUARANOBAT_MEDICATION'];
    $IDPERESEPAN_MEDICATIONREQUEST = $de['IDPERESEPAN_MEDICATIONREQUEST'];

    $array = array("1", "2","3","4","5");
    $jumlah =  $array[array_rand($array, 1)];

    $url =$base_url."/MedicationDispense";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "MedicationDispense",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/prescription/'.$organisation.'",
                "use": "official",
                "value": "'.$noreg.'"
                },
                {
                    "system": "http://sys-ids.kemkes.go.id/prescription-item/'.$organisation.'",
                    "use": "official",
                    "value": "'.$noreg.'"
                }
                ],
                "status": "completed",
                "category": {
                    "coding": [
                    {
                        "system": "http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category",
                        "code": "inpatient",
                        "display": "Inpatient"
                    }
                    ]
                    },
                    "medicationReference": {
                        "reference": "Medication/'.$IDPENGELUARANOBAT_MEDICATION.'",
                        "display": "Amoxicillin Trihydrate 500 mg Tablet"
                        },
                        "subject": {
                            "reference": "Patient/'.$ihsnumber.'",
                            "display": "'.$namapasien.'"
                            },
                            "context": {
                                "reference": "Encounter/'.$IDENCOUNTER.'"
                                },
                                "performer": [
                                {
                                    "actor": {
                                        "reference": "Practitioner/'.$iddokter.'",
                                        "display": "'.$namadokter.'"
                                    }
                                }
                                ],
                                "location": {
                                    "reference": "Location/'.$location.'",
                                    "display": "'.$display_ruang.'"
                                    },
                                    "authorizingPrescription": [
                                    {
                                        "reference": "MedicationRequest/'.$IDPERESEPAN_MEDICATIONREQUEST.'"
                                    }
                                    ],
                                    "quantity": {
                                        "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                        "code": "TAB",
                                        "value": 1
                                        },
                                        "daysSupply": {
                                            "value": 1,
                                            "unit": "Day",
                                            "system": "http://unitsofmeasure.org",
                                            "code": "d"
                                            },
                                            "whenPrepared": "'.$tanggal.'",
                                            "whenHandedOver": "'.$tanggal.'",
                                            "dosageInstruction": [
                                            {
                                                "sequence": 3,
                                                "text": "3 tablet per hari",
                                                "additionalInstruction": [
                                                {
                                                    "text": "3 tablet per hari"
                                                }
                                                ],
                                                "patientInstruction": "3 tablet per hari",
                                                "timing": {
                                                    "repeat": {
                                                        "frequency": 3,
                                                        "period": 1,
                                                        "periodUnit": "d"
                                                    }
                                                    },
                                                    "route": {
                                                        "coding": [
                                                        {
                                                            "system": "http://www.whocc.no/atc",
                                                            "code": "O",
                                                            "display": "Oral"
                                                        }
                                                        ]
                                                        },
                                                        "doseAndRate": [
                                                        {
                                                            "type": {
                                                                "coding": [
                                                                {
                                                                    "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                                                    "code": "ordered",
                                                                    "display": "Ordered"
                                                                }
                                                                ]
                                                                },
                                                                "doseQuantity": {
                                                                    "value": 3,
                                                                    "unit": "TAB",
                                                                    "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                                                    "code": "TAB"
                                                                }
                                                            }
                                                            ]
                                                        }
                                                        ]
                                                    }
                                                    ',
                                                    CURLOPT_HTTPHEADER => array(
                                                      'Content-Type: application/json',
                                                      'Authorization: Bearer ' . $token
                                                  ),

                                                ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDPENGELUARANOBAT_MEDICATIONDISPENCE='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}


}

if($jenis==31){ //IDPENGELUARANOBAT_MEDICATION

 $qe="SELECT IDPERESEPAN_MEDICATION FROM SS_RI_RESUME where noreg='$noreg'";
 $he  = sqlsrv_query($conn, $qe);        
 $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
 $IDPERESEPAN_MEDICATION = $de['IDPERESEPAN_MEDICATION'];

 $array = array("1", "2","3","4","5");
 $jumlah =  $array[array_rand($array, 1)];

 $url =$base_url."/Medication";

 $curl = curl_init();
 curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '
    {
        "resourceType": "Medication",
        "meta": {
            "profile": [
            "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
            ]
            },
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/medication/'.$organisation.'",
                "use": "official",
                "value": "'.$noreg.'"
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://sys-ids.kemkes.go.id/kfa",
                    "code": "92000881",
                    "display": "Amoxicillin Trihydrate 500 mg Tablet"
                }
                ]
                },
                "status": "active",
                "manufacturer": {
                    "reference": "Organization/'.$organisation.'"
                    },
                    "form": {
                        "coding": [
                        {
                            "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
                            "code": "BS066",
                            "display": "Tablet"
                        }
                        ]
                        },
                        "ingredient": [
                        {
                            "itemCodeableConcept": {
                                "coding": [
                                {
                                    "system": "http://sys-ids.kemkes.go.id/kfa",
                                    "code": "92000881",
                                    "display": "Amoxicillin Trihydrate 500 mg Tablet"
                                }
                                ]
                                },
                                "isActive": true,
                                "strength": {
                                    "numerator": {
                                        "value": 25,
                                        "system": "http://unitsofmeasure.org",
                                        "code": "mg"
                                        },
                                        "denominator": {
                                            "value": '.$jumlah.',
                                            "unit": "TAB",
                                            "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                            "code": "TAB"
                                        }
                                    }
                                }
                                ],
                                "batch": {
                                    "lotNumber": "1625042A",
                                    "expirationDate": "2025-07-28"
                                    },
                                    "extension": [
                                    {
                                        "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                                        "valueCodeableConcept": {
                                            "coding": [
                                            {
                                                "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                                "code": "NC",
                                                "display": "Non-compound"
                                            }
                                            ]
                                        }
                                    }
                                    ]
                                }
                                ',
                                CURLOPT_HTTPHEADER => array(
                                  'Content-Type: application/json',
                                  'Authorization: Bearer ' . $token
                              ),

                            ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDPENGELUARANOBAT_MEDICATION='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

if($jenis==30){ //IDPERESEPAN_MEDICATIONREQUEST

    $qe="SELECT IDPERESEPAN_MEDICATION FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDPERESEPAN_MEDICATION = $de['IDPERESEPAN_MEDICATION'];


    $url =$base_url."/QuestionnaireResponse";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "QuestionnaireResponse",
            "questionnaire": "https://fhir.kemkes.go.id/Questionnaire/Q0007",
            "status": "completed",
            "subject": {
                "reference": "Patient/'.$ihsnumber.'",
                "display": "'.$namapasien.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$IDENCOUNTER.'"
                    },
                    "authored": "'.$tanggal.'",
                    "author": {
                        "reference": "Practitioner/'.$idperawat.'",
                        "display": "'.$nmperawat.'"
                        },
                        "source": {
                            "reference": "Patient/'.$ihsnumber.'"
                            },
                            "item": [
                            {
                                "linkId": "1",
                                "text": "Persyaratan Administrasi",
                                "item": [
                                {
                                    "linkId": "1.1",
                                    "text": "Apakah nama, umur, jenis kelamin, berat badan dan tinggi badan pasien sudah sesuai?",
                                    "answer": [
                                    {
                                        "valueCoding": {
                                            "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                            "code": "OV000052",
                                            "display": "Sesuai"
                                        }
                                    }
                                    ]
                                    },
                                    {
                                        "linkId": "1.2",
                                        "text": "Apakah nama, nomor ijin, alamat dan paraf dokter sudah sesuai?",
                                        "answer": [
                                        {
                                            "valueCoding": {
                                                "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                "code": "OV000052",
                                                "display": "Sesuai"
                                            }
                                        }
                                        ]
                                        },
                                        {
                                            "linkId": "1.3",
                                            "text": "Apakah tanggal resep sudah sesuai?",
                                            "answer": [
                                            {
                                                "valueCoding": {
                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                    "code": "OV000052",
                                                    "display": "Sesuai"
                                                }
                                            }
                                            ]
                                            },
                                            {
                                                "linkId": "1.4",
                                                "text": "Apakah ruangan/unit asal resep sudah sesuai?",
                                                "answer": [
                                                {
                                                    "valueCoding": {
                                                        "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                        "code": "OV000052",
                                                        "display": "Sesuai"
                                                    }
                                                }
                                                ]
                                                },
                                                {
                                                    "linkId": "2",
                                                    "text": "Persyaratan Farmasetik",
                                                    "item": [
                                                    {
                                                        "linkId": "2.1",
                                                        "text": "Apakah nama obat, bentuk dan kekuatan sediaan sudah sesuai?",
                                                        "answer": [
                                                        {
                                                            "valueCoding": {
                                                                "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                                "code": "OV000052",
                                                                "display": "Sesuai"
                                                            }
                                                        }
                                                        ]
                                                        },
                                                        {
                                                            "linkId": "2.2",
                                                            "text": "Apakah dosis dan jumlah obat sudah sesuai?",
                                                            "answer": [
                                                            {
                                                                "valueCoding": {
                                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                                    "code": "OV000052",
                                                                    "display": "Sesuai"
                                                                }
                                                            }
                                                            ]
                                                            },
                                                            {
                                                                "linkId": "2.3",
                                                                "text": "Apakah stabilitas obat sudah sesuai?",
                                                                "answer": [
                                                                {
                                                                    "valueCoding": {
                                                                        "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                                        "code": "OV000052",
                                                                        "display": "Sesuai"
                                                                    }
                                                                }
                                                                ]
                                                                },
                                                                {
                                                                    "linkId": "2.4",
                                                                    "text": "Apakah aturan dan cara penggunaan obat sudah sesuai?",
                                                                    "answer": [
                                                                    {
                                                                        "valueCoding": {
                                                                            "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                                            "code": "OV000052",
                                                                            "display": "Sesuai"
                                                                        }
                                                                    }
                                                                    ]
                                                                }
                                                                ]
                                                                },
                                                                {
                                                                    "linkId": "3",
                                                                    "text": "Persyaratan Klinis",
                                                                    "item": [
                                                                    {
                                                                        "linkId": "3.1",
                                                                        "text": "Apakah ketepatan indikasi, dosis, dan waktu penggunaan obat sudah sesuai?",
                                                                        "answer": [
                                                                        {
                                                                            "valueCoding": {
                                                                                "system": "http://terminology.kemkes.go.id/CodeSystem/clinical-term",
                                                                                "code": "OV000052",
                                                                                "display": "Sesuai"
                                                                            }
                                                                        }
                                                                        ]
                                                                        },
                                                                        {
                                                                            "linkId": "3.2",
                                                                            "text": "Apakah terdapat duplikasi pengobatan?",
                                                                            "answer": [
                                                                            {
                                                                                "valueBoolean": false
                                                                            }
                                                                            ]
                                                                            },
                                                                            {
                                                                                "linkId": "3.3",
                                                                                "text": "Apakah terdapat alergi dan reaksi obat yang tidak dikehendaki (ROTD)?",
                                                                                "answer": [
                                                                                {
                                                                                    "valueBoolean": false
                                                                                }
                                                                                ]
                                                                                },
                                                                                {
                                                                                    "linkId": "3.4",
                                                                                    "text": "Apakah terdapat kontraindikasi pengobatan?",
                                                                                    "answer": [
                                                                                    {
                                                                                        "valueBoolean": false
                                                                                    }
                                                                                    ]
                                                                                    },
                                                                                    {
                                                                                        "linkId": "3.5",
                                                                                        "text": "Apakah terdapat dampak interaksi obat?",
                                                                                        "answer": [
                                                                                        {
                                                                                            "valueBoolean": false
                                                                                        }
                                                                                        ]
                                                                                    }
                                                                                    ]
                                                                                }
                                                                                ]
                                                                            }
                                                                            ]
                                                                        }
                                                                        ',
                                                                        CURLOPT_HTTPHEADER => array(
                                                                          'Content-Type: application/json',
                                                                          'Authorization: Bearer ' . $token
                                                                      ),

                                                                    ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDQuestionnaireResponse='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

if($jenis==29){ //IDPERESEPAN_MEDICATIONREQUEST

    $qe="SELECT IDPERESEPAN_MEDICATION FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDPERESEPAN_MEDICATION = $de['IDPERESEPAN_MEDICATION'];


    $url =$base_url."/MedicationRequest";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "MedicationRequest",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/prescription/'.$organisation.'",
                "use": "official",
                "value": "'.$noreg.'"
                },
                {
                    "system": "http://sys-ids.kemkes.go.id/prescription-item/'.$organisation.'",
                    "use": "official",
                    "value": "'.$noreg.'"
                }
                ],
                "status": "completed",
                "intent": "order",
                "category": [
                {
                    "coding": [
                    {
                        "system": "http://terminology.hl7.org/CodeSystem/medicationrequest-category",
                        "code": "inpatient",
                        "display": "Inpatient"
                    }
                    ]
                }
                ],
                "priority": "routine",
                "medicationReference": {
                    "reference": "Medication/'.$IDPERESEPAN_MEDICATION.'",
                    "display": "Amoxicillin Trihydrate 500 mg Tablet"
                    },
                    "subject": {
                        "reference": "Patient/'.$ihsnumber.'",
                        "display": "'.$namapasien.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$IDENCOUNTER.'"
                            },
                            "authoredOn": "'.$tanggal.'",
                            "requester": {
                                "reference": "Practitioner/'.$iddokter.'",
                                "display": "'.$namadokter.'"
                                },
                                "dosageInstruction": [
                                {
                                    "sequence": 1,
                                    "text": "3 tablet per hari",
                                    "additionalInstruction": [
                                    {
                                        "text": "3 tablet per hari"
                                    }
                                    ],
                                    "patientInstruction": "3 tablet per hari",
                                    "timing": {
                                        "repeat": {
                                            "frequency": 3,
                                            "period": 3,
                                            "periodUnit": "d"
                                        }
                                        },
                                        "route": {
                                            "coding": [
                                            {
                                                "system": "http://www.whocc.no/atc",
                                                "code": "O",
                                                "display": "Oral"
                                            }
                                            ]
                                            },
                                            "doseAndRate": [
                                            {
                                                "type": {
                                                    "coding": [
                                                    {
                                                        "system": "http://terminology.hl7.org/CodeSystem/dose-rate-type",
                                                        "code": "ordered",
                                                        "display": "Ordered"
                                                    }
                                                    ]
                                                    },
                                                    "doseQuantity": {
                                                        "value": 3,
                                                        "unit": "TAB",
                                                        "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                                        "code": "TAB"
                                                    }
                                                }
                                                ]
                                            }
                                            ],
                                            "dispenseRequest": {
                                                "dispenseInterval": {
                                                    "value": 1,
                                                    "unit": "days",
                                                    "system": "http://unitsofmeasure.org",
                                                    "code": "d"
                                                    },
                                                    "validityPeriod": {
                                                        "start": "'.$tglperiksa.'",
                                                        "end": "'.$tglkeluar.'"
                                                        },
                                                        "numberOfRepeatsAllowed": 0,
                                                        "quantity": {
                                                            "value": 1,
                                                            "unit": "TAB",
                                                            "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                                            "code": "TAB"
                                                            },
                                                            "expectedSupplyDuration": {
                                                                "value": 1,
                                                                "unit": "days",
                                                                "system": "http://unitsofmeasure.org",
                                                                "code": "d"
                                                                },
                                                                "performer": {
                                                                    "reference": "Organization/'.$organisation.'"
                                                                }
                                                            }
                                                        }
                                                        ',
                                                        CURLOPT_HTTPHEADER => array(
                                                          'Content-Type: application/json',
                                                          'Authorization: Bearer ' . $token
                                                      ),

                                                    ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDPERESEPAN_MEDICATIONREQUEST='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}


}


if($jenis==28){ //IDPERESEPAN_MEDICATION

    $array = array("1", "2","3","4","5");
    $jumlah =  $array[array_rand($array, 1)];


    $url =$base_url."/Medication";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "Medication",
            "meta": {
                "profile": [
                "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                ]
                },
                "identifier": [
                {
                    "system": "http://sys-ids.kemkes.go.id/medication/'.$organisation.'",
                    "use": "official",
                    "value": "'.$noreg.'"
                }
                ],
                "code": {
                    "coding": [
                    {
                        "system": "http://sys-ids.kemkes.go.id/kfa",
                        "code": "92000881",
                        "display": "Amoxicillin Trihydrate 500 mg Tablet"
                    }
                    ]
                    },
                    "status": "active",
                    "manufacturer": {
                        "reference": "Organization/'.$organisation.'"
                        },
                        "form": {
                            "coding": [
                            {
                                "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
                                "code": "BS066",
                                "display": "Tablet"
                            }
                            ]
                            },
                            "ingredient": [
                            {
                                "itemCodeableConcept": {
                                    "coding": [
                                    {
                                        "system": "http://sys-ids.kemkes.go.id/kfa",
                                        "code": "92000881",
                                        "display": "Amoxicillin Trihydrate 500 mg Tablet"
                                    }
                                    ]
                                    },
                                    "isActive": true,
                                    "strength": {
                                        "numerator": {
                                            "value": 40,
                                            "system": "http://unitsofmeasure.org",
                                            "code": "mg"
                                            },
                                            "denominator": {
                                                "value": '.$jumlah.',
                                                "unit": "TAB",
                                                "system": "http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm",
                                                "code": "TAB"
                                            }
                                        }
                                    }
                                    ],
                                    "extension": [
                                    {
                                        "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                                        "valueCodeableConcept": {
                                            "coding": [
                                            {
                                                "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                                "code": "NC",
                                                "display": "Non-compound"
                                            }
                                            ]
                                        }
                                    }
                                    ]
                                }
                                ',
                                CURLOPT_HTTPHEADER => array(
                                  'Content-Type: application/json',
                                  'Authorization: Bearer ' . $token
                              ),

                            ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDPERESEPAN_MEDICATION='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

if($jenis==27){ //IDRAD_DIAGNOSIC

    $array = array("240", "245","250","255","260");
    $hasil =  $array[array_rand($array, 1)];

    $qe="SELECT IDRAD_SERVICEREQ,IDRAD_OBSERVATION FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDRAD_SERVICEREQ = $de['IDRAD_SERVICEREQ'];
    $IDRAD_OBSERVATION = $de['IDRAD_OBSERVATION'];

    $url =$base_url."/DiagnosticReport";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "DiagnosticReport",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/diagnostic/'.$organisation.'/rad",
                "use": "official",
                "value": "'.$noreg.'"
            }
            ],
            "status": "final",
            "category": [
            {
                "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                    "code": "RAD",
                    "display": "Radiology"
                }
                ]
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "87847-0",
                    "display": "CT Chest WO and CT angiogram Coronary arteries W contrast IV"
                }
                ]
                },
                "subject": {
                    "reference": "Patient/'.$ihsnumber.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$IDENCOUNTER.'"
                        },
                        "effectiveDateTime": "'.$tanggal.'",
                        "issued": "'.$tanggal.'",
                        "performer": [
                        {
                            "reference": "Practitioner/'.$iddokter.'"
                            },
                            {
                                "reference": "Organization/'.$organisation.'"
                            }
                            ],
                            "imagingStudy": [
                            
                            ],
                            "result": [
                            {
                                "reference": "Observation/'.$IDRAD_OBSERVATION.'"
                            }
                            ],
                            "basedOn": [
                            {
                                "reference": "ServiceRequest/'.$IDRAD_SERVICEREQ.'"
                            }
                            ],
                            "conclusion": "Normal"
                        }

                        ',
                        CURLOPT_HTTPHEADER => array(
                          'Content-Type: application/json',
                          'Authorization: Bearer ' . $token
                      ),

                    ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDRAD_DIAGNOSIC='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}

if($jenis==26){ //IDRAD_OBSERVATION

    $array = array("240", "245","250","255","260");
    $hasil =  $array[array_rand($array, 1)];

    $qe="SELECT IDDIAGNOSA_PRIMARY,IDTINDAKAN,IDLABSERVICE_REQ,IDLABSPECIMEN,IDLABOBSERVATION,IDRAD_SERVICEREQ FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDDIAGNOSA_PRIMARY = $de['IDDIAGNOSA_PRIMARY'];
    $IDTINDAKAN = $de['IDTINDAKAN'];
    $IDLABSERVICE_REQ = $de['IDLABSERVICE_REQ'];
    $IDLABSPECIMEN = $de['IDLABSPECIMEN'];
    $IDLABOBSERVATION = $de['IDLABOBSERVATION'];
    $IDRAD_SERVICEREQ = $de['IDRAD_SERVICEREQ'];

    $url =$base_url."/Observation";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "Observation",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/observation/'.$organisation.'",
                "value": "'.$noreg.'"
            }
            ],
            "status": "final",
            "category": [
            {
                "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                    "code": "imaging",
                    "display": "Imaging"
                }
                ]
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "46322-4",
                    "display": "CT Kidney W contrast IV"
                }
                ]
                },
                "subject": {
                    "reference": "Patient/'.$ihsnumber.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$IDENCOUNTER.'"
                        },
                        "effectiveDateTime": "'.$tanggal.'",
                        "issued": "'.$tanggal.'",
                        "performer": [
                        {
                            "reference": "Practitioner/'.$idperawat.'"
                            },
                            {
                                "reference": "Organization/'.$organisation.'"
                            }
                            ],
                            "basedOn": [
                            {
                                "reference": "ServiceRequest/'.$IDRAD_SERVICEREQ.'"
                            }
                            ],
                            "bodySite": {
                                "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "64033007",
                                    "display": "Kidney structure"
                                }
                                ]
                                },
                                "derivedFrom": [

                                ],
                                "valueString": "Tidak ditemukan kelainan dalam CT Kidney"
                            }
                            ',
                            CURLOPT_HTTPHEADER => array(
                              'Content-Type: application/json',
                              'Authorization: Bearer ' . $token
                          ),

                        ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDRAD_OBSERVATION='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}

if($jenis==25){ //IDRAD_SERVICEREQ

    $array = array("240", "245","250","255","260");
    $hasil =  $array[array_rand($array, 1)];

    $qe="SELECT IDDIAGNOSA_PRIMARY,IDTINDAKAN,IDLABSERVICE_REQ,IDLABSPECIMEN,IDLABOBSERVATION FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDDIAGNOSA_PRIMARY = $de['IDDIAGNOSA_PRIMARY'];
    $IDTINDAKAN = $de['IDTINDAKAN'];
    $IDLABSERVICE_REQ = $de['IDLABSERVICE_REQ'];
    $IDLABSPECIMEN = $de['IDLABSPECIMEN'];
    $IDLABOBSERVATION = $de['IDLABOBSERVATION'];

    $url =$base_url."/ServiceRequest";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "ServiceRequest",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$organisation.'",
                "value": "'.$noreg.'"
                },
                {
                    "use": "usual",
                    "type": {
                        "coding": [
                        {
                            "system": "http://terminology.hl7.org/CodeSystem/v2-0203",
                            "code": "ACSN"
                        }
                        ]
                        },
                        "system": "http://sys-ids.kemkes.go.id/acsn/'.$organisation.'",
                        "value": "'.$noreg.'"
                    }
                    ],
                    "status": "active",
                    "intent": "original-order",
                    "priority": "routine",
                    "category": [
                    {
                        "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "363679005",
                            "display": "Imaging"
                        }
                        ]
                    }
                    ],
                    "code": {
                        "coding": [
                        {
                            "system": "http://loinc.org",
                            "code": "46322-4",
                            "display": "CT Kidney W contrast IV"
                        }
                        ],
                        "text": "Pemeriksaan CT Scan"
                        },
                        "subject": {
                            "reference": "Patient/'.$ihsnumber.'"
                            },
                            "encounter": {
                                "reference": "Encounter/'.$IDENCOUNTER.'"
                                },
                                "occurrenceDateTime": "'.$tanggal.'",
                                "authoredOn": "'.$tanggal.'",
                                "requester": {
                                    "reference": "Practitioner/'.$iddokter.'",
                                    "display": "'.$namadokter.'"
                                    },
                                    "performer": [
                                    {
                                        "reference": "Practitioner/'.$idperawat.'",
                                        "display": "'.$nmperawat.'"
                                    }
                                    ],
                                    "bodySite": [
                                    {
                                        "coding": [
                                        {
                                            "system": "http://snomed.info/sct",
                                            "code": "64033007",
                                            "display": "Kidney structure"
                                        }
                                        ]
                                    }
                                    ],
                                    "reasonCode": [
                                    {
                                        "text": "Pemeriksaan CT Scan untuk Pelayanan Rawat Inap Pasien a.n '.$namapasien.'"
                                    }
                                    ],
                                    "reasonReference": [
                                    {
                                        "Reference":"Condition/'.$IDDIAGNOSA_PRIMARY.'"
                                    }
                                    ],
                                    "note": [
                                    {
                                        "text": "Pemeriksaan CT Scan"
                                    }
                                    ],
                                    "supportingInfo": [

                                    ]
                                }
                                ',
                                CURLOPT_HTTPHEADER => array(
                                  'Content-Type: application/json',
                                  'Authorization: Bearer ' . $token
                              ),

                            ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDRAD_SERVICEREQ='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}


}

if($jenis==24){ //IDRAD_STATUSKEHAMILAN

    $url =$base_url."/Observation";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "Observation",
            "status": "final",
            "category": [
            {
                "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                    "code": "survey",
                    "display": "Survey"
                }
                ]
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "82810-3",
                    "display": "Pregnancy status"
                }
                ]
                },
                "subject": {
                    "reference": "Patient/'.$ihsnumber.'"
                    },
                    "performer": [
                    {
                        "reference": "Practitioner/'.$iddokter.'"
                    }
                    ],
                    "encounter": {
                        "reference": "Encounter/'.$IDENCOUNTER.'",
                        "display": "Kunjungan '.$namapasien.' "
                        },
                        "effectiveDateTime": "'.$tanggal.'",
                        "issued": "'.$tanggal.'",
                        "valueCodeableConcept": {
                            "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "60001007",
                                "display": "Not pregnant"
                            }
                            ]
                        }
                    }
                    ',
                    CURLOPT_HTTPHEADER => array(
                      'Content-Type: application/json',
                      'Authorization: Bearer ' . $token
                  ),

                ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDRAD_STATUSKEHAMILAN='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}

if($jenis==23){ //IDLABDIAGNOSIC

    $array = array("240", "245","250","255","260");
    $hasil =  $array[array_rand($array, 1)];

    $qe="SELECT IDDIAGNOSA_PRIMARY,IDTINDAKAN,IDLABSERVICE_REQ,IDLABSPECIMEN,IDLABOBSERVATION FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDDIAGNOSA_PRIMARY = $de['IDDIAGNOSA_PRIMARY'];
    $IDTINDAKAN = $de['IDTINDAKAN'];
    $IDLABSERVICE_REQ = $de['IDLABSERVICE_REQ'];
    $IDLABSPECIMEN = $de['IDLABSPECIMEN'];
    $IDLABOBSERVATION = $de['IDLABOBSERVATION'];

    $url =$base_url."/DiagnosticReport";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "DiagnosticReport",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/diagnostic/'.$organisation.'/lab",
                "use": "official",
                "value": "'.$noreg.'"
            }
            ],
            "status": "final",
            "category": [
            {
                "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                    "code": "CH",
                    "display": "Chemistry"
                }
                ]
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "2093-3",
                    "display": "Cholesterol [Mass/volume] in Serum or Plasma"
                }
                ]
                },
                "subject": {
                    "reference": "Patient/'.$ihsnumber.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$IDENCOUNTER.'"
                        },
                        "effectiveDateTime": "'.$tanggal.'",
                        "issued": "'.$tanggal.'",
                        "performer": [
                        {
                            "reference": "Practitioner/'.$idperawat.'"
                            },
                            {
                                "reference": "Organization/'.$organisation.'"
                            }
                            ],
                            "result": [
                            {
                                "reference": "Observation/'.$IDLABOBSERVATION.'"
                            }
                            ],
                            "specimen": [
                            {
                                "reference": "Specimen/'.$IDLABSPECIMEN.'"
                            }
                            ],
                            "basedOn": [
                            {
                                "reference": "ServiceRequest/'.$IDLABSERVICE_REQ.'"
                            }
                            ],
                            "conclusionCode": [
                            {
                                "coding": [
                                {
                                    "system": "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                                    "code": "H",
                                    "display": "High"
                                }
                                ]
                            }
                            ]
                        }
                        ',
                        CURLOPT_HTTPHEADER => array(
                          'Content-Type: application/json',
                          'Authorization: Bearer ' . $token
                      ),

                    ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDLABDIAGNOSIC='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}

if($jenis==22){ //IDLABOBSERVATION

    $array = array("240", "245","250","255","260");
    $hasil =  $array[array_rand($array, 1)];

    $qe="SELECT IDDIAGNOSA_PRIMARY,IDTINDAKAN,IDLABSERVICE_REQ,IDLABSPECIMEN FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDDIAGNOSA_PRIMARY = $de['IDDIAGNOSA_PRIMARY'];
    $IDTINDAKAN = $de['IDTINDAKAN'];
    $IDLABSERVICE_REQ = $de['IDLABSERVICE_REQ'];
    $IDLABSPECIMEN = $de['IDLABSPECIMEN'];

    $url =$base_url."/Observation";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "Observation",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/observation/'.$organisation.'",
                "value": "'.$noreg.'"
            }
            ],
            "status": "final",
            "category": [
            {
                "coding": [
                {
                    "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                    "code": "laboratory",
                    "display": "Laboratory"
                }
                ]
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://loinc.org",
                    "code": "2093-3",
                    "display": "Cholesterol [Mass/volume] in Serum or Plasma"
                }
                ]
                },
                "subject": {
                    "reference": "Patient/'.$ihsnumber.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$IDENCOUNTER.'"
                        },
                        "effectiveDateTime": "'.$tanggal.'",
                        "issued": "'.$tanggal.'",
                        "performer": [
                        {
                            "reference": "Practitioner/'.$idperawat.'"
                            },
                            {
                                "reference": "Organization/'.$organisation.'"
                            }
                            ],
                            "specimen": {
                                "reference": "Specimen/'.$IDLABSPECIMEN.'"
                                },
                                "basedOn": [
                                {
                                    "reference": "ServiceRequest/'.$IDLABSERVICE_REQ.'"
                                }
                                ],
                                "valueQuantity": {
                                    "value": '.$hasil.',
                                    "unit": "mg/dL",
                                    "system": "http://unitsofmeasure.org",
                                    "code": "mg/dL"
                                    },
                                    "interpretation": [
                                    {
                                        "coding": [
                                        {
                                            "system": "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                                            "code": "H",
                                            "display": "High"
                                        }
                                        ]
                                    }
                                    ],
                                    "referenceRange": [
                                    {
                                        "high": {
                                            "value": 200,
                                            "unit": "mg/dL",
                                            "system": "http://unitsofmeasure.org",
                                            "code": "mg/dL"
                                            },
                                            "text": "Normal"
                                            },
                                            {
                                                "low": {
                                                    "value": 201,
                                                    "unit": "mg/dL",
                                                    "system": "http://unitsofmeasure.org",
                                                    "code": "mg/dL"
                                                    },
                                                    "high": {
                                                        "value": 239,
                                                        "unit": "mg/dL",
                                                        "system": "http://unitsofmeasure.org",
                                                        "code": "mg/dL"
                                                        },
                                                        "text": "Borderline high"
                                                        },
                                                        {
                                                            "low": {
                                                                "value": 240,
                                                                "unit": "mg/dL",
                                                                "system": "http://unitsofmeasure.org",
                                                                "code": "mg/dL"
                                                                },
                                                                "text": "High"
                                                            }
                                                            ]
                                                        }
                                                        ',
                                                        CURLOPT_HTTPHEADER => array(
                                                          'Content-Type: application/json',
                                                          'Authorization: Bearer ' . $token
                                                      ),

                                                    ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDLABOBSERVATION='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}


}

if($jenis==21){ //IDLABSPECIMEN

    $array = array("5", "6","7");
    $jumlah_specimen =  $array[array_rand($array, 1)];

    $qe="SELECT IDDIAGNOSA_PRIMARY,IDTINDAKAN,IDLABSERVICE_REQ FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDDIAGNOSA_PRIMARY = $de['IDDIAGNOSA_PRIMARY'];
    $IDTINDAKAN = $de['IDTINDAKAN'];
    $IDLABSERVICE_REQ = $de['IDLABSERVICE_REQ'];

    $url =$base_url."/Specimen";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "Specimen",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/specimen/'.$organisation.'",
                "value": "'.$noreg.'",
                "assigner": {
                    "reference": "Organization/'.$organisation.'"
                }
            }
            ],
            "status": "available",
            "type": {
                "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "119297000",
                    "display": "Blood specimen"
                }
                ]
                },
                "condition": [
                {
                    "text": "Kondisi Spesimen Baik"
                }
                ],
                "collection": {
                    "method": {
                        "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "82078001",
                            "display": "Collection of blood specimen for laboratory"
                        }
                        ]
                        },
                        "collectedDateTime": "'.$tanggal.'",
                        "quantity": {
                            "value": '.$jumlah_specimen.',
                            "unit": "mL"
                            },
                            "collector": {
                                "reference": "Practitioner/'.$iddokter.'",
                                "display": "'.$namadokter.'"
                                },
                                "fastingStatusCodeableConcept": {
                                    "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v2-0916",
                                        "code": "F",
                                        "display": "Patient was fasting prior to the procedure."
                                    }
                                    ]
                                }
                                },
                                "processing": [
                                {
                                    "timeDateTime": "'.$tanggal.'"
                                }
                                ],
                                "subject": {
                                    "reference": "Patient/'.$ihsnumber.'",
                                    "display": "'.$namapasien.'"
                                    },
                                    "request": [
                                    {
                                        "reference": "ServiceRequest/'.$IDLABSERVICE_REQ.'"
                                    }
                                    ],
                                    "receivedTime": "'.$tanggal.'",
                                    "extension": [
                                    ]
                                }
                                ',
                                CURLOPT_HTTPHEADER => array(
                                  'Content-Type: application/json',
                                  'Authorization: Bearer ' . $token
                              ),

                            ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDLABSPECIMEN='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}

if($jenis==20){ //IDLABSERVICE_REQ

   $qe="SELECT IDDIAGNOSA_PRIMARY,IDTINDAKAN FROM SS_RI_RESUME where noreg='$noreg'";
   $he  = sqlsrv_query($conn, $qe);        
   $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
   $IDDIAGNOSA_PRIMARY = $de['IDDIAGNOSA_PRIMARY'];
   $IDTINDAKAN = $de['IDTINDAKAN'];

   $url =$base_url."/ServiceRequest";

   $curl = curl_init();
   curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => '
    {
        "resourceType": "ServiceRequest",
        "identifier": [
        {
            "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$organisation.'",
            "value": "'.$noreg.'"
        }
        ],
        "status": "active",
        "intent": "original-order",
        "priority": "routine",
        "category": [
        {
            "coding": [
            {
                "system": "http://snomed.info/sct",
                "code": "108252007",
                "display": "Laboratory procedure"
            }
            ]
        }
        ],
        "code": {
            "coding": [
            {
                "system": "http://loinc.org",
                "code": "2093-3",
                "display": "Cholesterol [Mass/volume] in Serum or Plasma"
            }
            ],
            "text": "Kolesterol Total"
            },
            "subject": {
                "reference": "Patient/'.$ihsnumber.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$IDENCOUNTER.'",
                    "display": "Permintaan Pemeriksaan Kolesterol Total"
                    },
                    "occurrenceDateTime": "'.$tanggal.'",
                    "authoredOn": "'.$tanggal.'",
                    "requester": {
                        "reference": "Practitioner/'.$iddokter.'",
                        "display": "'.$namadokter.'"
                        },
                        "performer": [
                        {
                            "reference": "Practitioner/'.$idperawat.'",
                            "display": "'.$nmperawat.'"
                        }
                        ],
                        "reasonCode": [
                        {
                            "text": "Periksa Kolesterol Darah untuk Pelayanan Rawat Inap Pasien a.n '.$namapasien.'"
                        }
                        ],
                        "reasonReference": [
                        {
                            "Reference":"Condition/'.$IDDIAGNOSA_PRIMARY.'"
                        }
                        ],
                        "note": [
                        {
                          "text": "Pasien diminta untuk berpuasa terlebih dahulu"
                      }
                      ]
                  }
                  ',
                  CURLOPT_HTTPHEADER => array(
                      'Content-Type: application/json',
                      'Authorization: Bearer ' . $token
                  ),

              ));

   echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
   $DATA = json_decode($response, true);
   $idproses = $DATA["id"];

   if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDLABSERVICE_REQ='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

                if($jenis==19){ //IDINSTRUKSI_MEDIK

                    $qe="SELECT        TOP (1) instruksi
                    FROM            ERM_SOAP
                    WHERE        (noreg = '$noreg')
                    ORDER BY id DESC";
                    $he  = sqlsrv_query($conn, $qe);        
                    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                    $instruksi = $de['instruksi'];

                    if(empty($instruksi)){
                        $instruksi="Penanganan Pasien dilakukan dengan pemberian Obat dan Terapi Dokter Spesialist.";
                    }

                    $url =$base_url."/CarePlan";

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => '
                        {
                            "resourceType": "CarePlan",
                            "title": "Instruksi Medik dan Keperawatan Pasien",
                            "status": "active",
                            "category": [
                            {
                                "coding": [
                                {
                                    "system": "http://snomed.info/sct",
                                    "code": "736353004",
                                    "display": " Inpatient care plan"
                                }
                                ]
                            }
                            ],
                            "intent": "plan",
                            "description": "'.$instruksi.'",
                            "subject": {
                                "reference": "Patient/'.$ihsnumber.'",
                                "display": "'.$namapasien.'"
                                },
                                "encounter": {
                                    "reference": "Encounter/'.$IDENCOUNTER.'"
                                    },
                                    "created": "'.$tanggal.'",
                                    "author": {
                                        "reference": "Practitioner/'.$iddokter.'"
                                    }
                                }
                                ',
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json',
                                    'Authorization: Bearer ' . $token
                                ),

                            ));

                    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
                    $DATA = json_decode($response, true);
                    $idproses = $DATA["id"];

                    if ($DATA["resourceType"] !== "OperationOutcome") {
                        $q="UPDATE SS_RI_RESUME SET  IDINSTRUKSI_MEDIK='$idproses' where noreg='$noreg'";
                        $h1  = sqlsrv_query($conn, $q);

                        $eror = 'Data Success Terkirim';
                    }else{
        // $eror = 'Data Sudah Terkirim ';
                        $eror = $DATA["issue"][0]["details"]["text"];
                    }

                }


if($jenis==18){ //Rencana Rawat

    $q="
    SELECT DISTINCT ERM_IMPLEMENTASI_ASUHAN.implementasi
    FROM            ERM_ASUHAN_KEPERAWATAN INNER JOIN
    ERM_IMPLEMENTASI_ASUHAN ON ERM_ASUHAN_KEPERAWATAN.noreg = ERM_IMPLEMENTASI_ASUHAN.noreg
    WHERE        (ERM_ASUHAN_KEPERAWATAN.noreg = '$noreg')
    ";   
    $hasil1  = sqlsrv_query($conn, $q);

    while    ($data = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){  
        $rencana_rawat = $rencana_rawat.$data[implementasi].',';
    }

    if(empty($rencana_rawat)){
        $rencana_rawat="Pasien akan melakukan Pengecekan Lab dan Radiologi di ".$display_ruang." selama 3-4 Hari";
    }

    $url =$base_url."/CarePlan";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
        {
            "resourceType": "CarePlan",
            "title": "Rencana Rawat Pasien",
            "status": "active",
            "category": [
            {
                "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "736353004",
                    "display": " Inpatient care plan"
                }
                ]
            }
            ],
            "intent": "plan",
            "description": "'.$rencana_rawat.'",
            "subject": {
                "reference": "Patient/'.$ihsnumber.'",
                "display": "'.$namapasien.'"
                },
                "encounter": {
                    "reference": "Encounter/'.$IDENCOUNTER.'"
                    },
                    "created": "'.$tanggal.'",
                    "author": {
                        "reference": "Practitioner/'.$iddokter.'"
                    }
                }
                ',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $token
                ),

            ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDRAWAT_PASIEN='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}


    if($jenis==17){ //Kondisi saat Meninggalkan RS

     $url =$base_url."/Condition";

     $qe="SELECT resume35,resume8,resume20 FROM ERM_RI_RESUME where noreg='$noreg'";
     $he  = sqlsrv_query($conn, $qe);        
     $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
     $ku = $de['resume8'];
     $diagnosa = $de['resume20'];

     $row = explode('-',$diagnosa);
     $kode_diagnosa = trim($row[0]); 
     $nama_diagnosa = trim($row[1]); 

     $curl = curl_init();
     curl_setopt_array($curl, array(
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => '
         {
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
                        "code": "problem-list-item",
                        "display": "Problem List Item"
                    }
                    ]
                }
                ],
                "code": {
                    "coding": [
                    {
                        "system": "http://snomed.info/sct",
                        "code": "359746009",
                        "display": "Patient`s condition stable"
                    }
                    ]
                    },
                    "subject": {
                        "reference": "Patient/'.$ihsnumber.'",
                        "display": "'.$namapasien.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$IDENCOUNTER.'",
                            "display": "Kunjungan Rawat Inap '.$namapasien.' "
                        }
                    }
                    ',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ),

                ));

     echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
     $DATA = json_decode($response, true);
     $idproses = $DATA["id"];

     if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDKONDISI_KELUAR='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}


    if($jenis==16){ //kontrol 1 minggu
     $url =$base_url."/ServiceRequest";

     $qe="SELECT resume35,resume8,resume20 FROM ERM_RI_RESUME where noreg='$noreg'";
     $he  = sqlsrv_query($conn, $qe);        
     $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
     $ku = $de['resume8'];
     $diagnosa = $de['resume20'];

     $row = explode('-',$diagnosa);
     $diagnosa_kode = trim($row[0]); 
     $diagnosa_nama = trim($row[1]); 

     if(empty($diagnosa)){
        $diagnosa = array("A90", "A09", "B34", "I12", "J06", "K30");
        $diagnosa_kode =  $diagnosa[array_rand($diagnosa, 1)];

        $q       = "
        SELECT      TOP(1)Afarm_ICD.KETERANGAN
        FROM            Afarm_ICD WHERE        (Afarm_ICD.NODAFTAR ='$diagnosa')
        ";
        $hasil  = sqlsrv_query($conn, $q);                

        $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
        $diagnosa_nama = $data[KETERANGAN];
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
     CURLOPT_URL => $url,
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS => '
     {
        "resourceType": "ServiceRequest",
        "identifier": [
        {
            "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$organisation.'",
            "value": "'.$noreg.'"
        }
        ],
        "status": "active",
        "intent": "original-order",
        "priority": "routine",
        "category": [
        {
            "coding": [
            {
                "system": "http://snomed.info/sct",
                "code": "306098008",
                "display": "Self-referral"
            }
            ]
            },
            {
                "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "11429006",
                    "display": "Consultation"
                }
                ]
            }
            ],
            "code": {
                "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "185389009",
                    "display": "Follow-up visit"
                }
                ],
                "text": "Kontrol 1 minggu Pasca Rawat Inap"
                },
                "subject": {
                    "reference": "Patient/'.$ihsnumber.'"
                    },
                    "encounter": {
                        "reference": "Encounter/'.$IDENCOUNTER.'",
                        "display": "Kunjungan '.$namapasien.'"
                        },
                        "occurrenceDateTime": "'.$tanggal.'",
                        "authoredOn": "'.$tanggal.'",
                        "requester": {
                            "reference": "Practitioner/'.$iddokter.'",
                            "display": "'.$namadokter.'"
                            },
                            "performer": [
                            {
                                "reference": "Practitioner/'.$idperawat.'",
                                "display": "'.$nmperawat.'"
                            }
                            ],
                            "reasonCode": [
                            {
                                "coding": [
                                {
                                    "system": "http://hl7.org/fhir/sid/icd-10",
                                    "code": "'.$diagnosa_kode.'",
                                    "display": "'.$diagnosa_nama.'"
                                }
                                ],
                                "text": "Kontrol rutin 1 minggu pertama"
                            }
                            ],
                            "locationCode": [
                            {
                                "coding": [
                                {
                                    "system": "http://terminology.hl7.org/CodeSystem/v3-RoleCode",
                                    "code": "OF",
                                    "display": "Outpatient Facility"
                                }
                                ]
                            }
                            ],
                            "patientInstruction": "Kontrol rutin 1 minggu pasca Rawat Inap."
                        }
                        ',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer ' . $token
                        ),

                    ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDKONTROL_MINGGU='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }
}

    if($jenis==15){ //rujukan keluar faskes
     $url =$base_url."/ServiceRequest";

     $qe="SELECT resume35,resume8,resume20 FROM ERM_RI_RESUME where noreg='$noreg'";
     $he  = sqlsrv_query($conn, $qe);        
     $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
     $ku = $de['resume8'];
     $diagnosa = $de['resume20'];

     $row = explode('-',$diagnosa);
     $kode_diagnosa = trim($row[0]); 
     $nama_diagnosa = trim($row[1]); 

     $curl = curl_init();
     curl_setopt_array($curl, array(
         CURLOPT_URL => $url,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => '
         {
            "resourceType": "ServiceRequest",
            "identifier": [
            {
                "system": "http://sys-ids.kemkes.go.id/servicerequest/'.$organisation.'",
                "value": "'.$noreg.'"
            }
            ],
            "status": "active",
            "intent": "original-order",
            "priority": "routine",
            "category": [
            {
                "coding": [
                {
                    "system": "http://snomed.info/sct",
                    "code": "3457005",
                    "display": "Patient referral"
                }
                ]
                },
                {
                    "coding": [
                    {
                        "system": "http://snomed.info/sct",
                        "code": "11429006",
                        "display": "Consultation"
                    }
                    ]
                }
                ],
                "code": {
                    "coding": [
                    {
                        "system": "http://snomed.info/sct",
                        "code": "3457005",
                        "display": "Patient referral"
                    }
                    ],
                    "text": "Rujukan Kasus '.$ku.'"
                    },
                    "subject": {
                        "reference": "Patient/'.$ihsnumber.'"
                        },
                        "encounter": {
                            "reference": "Encounter/'.$IDENCOUNTER.'",
                            "display": "Kunjungan '.$namapasien.' "
                            },
                            "occurrenceDateTime": "'.$tanggal.'",
                            "requester": {
                                "reference": "Practitioner/'.$iddokter.'",
                                "display": "'.$namadokter.'"
                                },
                                "performer": [
                                {
                                    "reference": "Practitioner/'.$idperawat.'",
                                    "display": "'.$nmperawat.'"
                                }
                                ],
                                "reasonCode": [
                                {
                                    "coding": [
                                    {
                                        "system": "http://hl7.org/fhir/sid/icd-10",
                                        "code": "'.$kode_diagnosa.'",
                                        "display": "'.$nama_diagnosa.'"
                                    }
                                    ],
                                    "text": "'.$nama_diagnosa.'"
                                }
                                ],
                                "locationCode": [
                                {
                                    "coding": [
                                    {
                                        "system": "http://terminology.hl7.org/CodeSystem/v3-RoleCode",
                                        "code": "ER",
                                        "display": "Emergency room"
                                    }
                                    ]
                                }
                                ],
                                "patientInstruction": "Rujukan dari '.$display_ruang.'"
                            }
                            ',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Bearer ' . $token
                            ),

                        ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDRUJUKAN_FASKES='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

    if($jenis==14){ //prognosis

     $url =$base_url."/ClinicalImpression";

     $qe="SELECT resume35,resume8,resume20 FROM ERM_RI_RESUME where noreg='$noreg'";
     $he  = sqlsrv_query($conn, $qe);        
     $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
     $text = $de['resume35'];
     $text2 = $de['resume8'];
     $text3 = $de['resume20'];

     $row = explode('-',$text3);
     $diagnosa_kode = trim($row[0]); 
     $diagnosa_nama = trim($row[1]); 

     if(empty($text3)){
        $diagnosa = array("A90", "A09", "B34", "I12", "J06", "K30");
        $diagnosa_kode =  $diagnosa[array_rand($diagnosa, 1)];

        $q       = "
        SELECT      TOP(1)Afarm_ICD.KETERANGAN
        FROM            Afarm_ICD WHERE        (Afarm_ICD.NODAFTAR ='$diagnosa')
        ";
        $hasil  = sqlsrv_query($conn, $q);                

        $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
        $diagnosa_nama = $data[KETERANGAN];
    }

    $qe="SELECT IDDIAGNOSA_PRIMARY,IDTINDAKAN FROM SS_RI_RESUME where noreg='$noreg'";
    $he  = sqlsrv_query($conn, $qe);        
    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $IDDIAGNOSA_PRIMARY = $de['IDDIAGNOSA_PRIMARY'];
    $IDTINDAKAN = $de['IDTINDAKAN'];


    $curl = curl_init();
    curl_setopt_array($curl, array(
     CURLOPT_URL => $url,
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'POST',
     CURLOPT_POSTFIELDS => '
     {
        "resourceType": "ClinicalImpression",
        "identifier": [
        {
            "system": "http://sys-ids.kemkes.go.id/clinicalimpression/'.$organisation.'",
            "use": "official",
            "value": "Prognosis_'.$organisation.'"
        }
        ],
        "status": "completed",
        "description": "Pasien an. Mengalami '.$text2.'",
        "subject": {
            "reference": "Patient/'.$ihsnumber.'",
            "display": "'.$namapasien.'"
            },
            "encounter": {
                "reference": "Encounter/'.$IDENCOUNTER.'",
                "display": "Kunjungan '.$namapasien.'"
                },
                "effectiveDateTime": "'.$tanggal.'",
                "date": "'.$tanggal.'",
                "assessor": {
                    "reference": "Practitioner/'.$iddokter.'"
                    },
                    "problem": [
                    {
                        "reference": "Condition/'.$IDDIAGNOSA_PRIMARY.'"
                    }
                    ],
                    "summary": "Prognosis '.$text2.'",
                    "finding": [
                    {
                        "itemCodeableConcept": {
                            "coding": [
                            {
                                "system": "http://hl7.org/fhir/sid/icd-10",
                                "code": "'.$diagnosa_kode.'",
                                "display": "'.$diagnosa_nama.'"
                            }
                            ]
                            },
                            "itemReference": {
                                "reference": "Condition/'.$IDDIAGNOSA_PRIMARY.'"
                            }
                        }
                        ],
                        "prognosisCodeableConcept": [
                        {
                            "coding": [
                            {
                                "system": "http://snomed.info/sct",
                                "code": "65872000",
                                "display": "Fair prognosis"
                            }
                            ]
                        }
                        ]
                    }
                    ',
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token
                    ),

                ));

    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
    $DATA = json_decode($response, true);
    $idproses = $DATA["id"];

    if ($DATA["resourceType"] !== "OperationOutcome") {
        $q="UPDATE SS_RI_RESUME SET  IDPROGNOSIS='$idproses' where noreg='$noreg'";
        $h1  = sqlsrv_query($conn, $q);

        $eror = 'Data Success Terkirim';
    }else{
        // $eror = 'Data Sudah Terkirim ';
        $eror = $DATA["issue"][0]["details"]["text"];
    }

}


    if($jenis==13){ //edukasi
        $url =$base_url."/Procedure";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '
            {
                "resourceType": "Procedure",
                "status": "completed",
                "category": {
                    "coding": [
                    {
                        "system": "http://snomed.info/sct",
                        "code": "409073007",
                        "display": "Education"
                    }
                    ],
                    "text": "Education"
                    },
                    "code": {
                        "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "84635008",
                            "display": "Disease process or condition education "
                        }
                        ]
                        },
                        "subject": {
                            "reference": "Patient/'.$ihsnumber.'",
                            "display": "'.$namapasien.'"
                            },
                            "encounter": {
                                "reference": "Encounter/'.$IDENCOUNTER.'",
                                "display": "Edukasi Proses Penyakit, Diagnosis, dan Rencana Asuhan kepada '.$namapasien.' "
                                },
                                "performedPeriod": {
                                    "start": "'.$tanggal.'",
                                    "end": "'.$tanggal.'"
                                    },
                                    "performer": [
                                    {
                                        "actor": {
                                            "reference": "Practitioner/'.$iddokter.'",
                                            "display": "'.$namadokter.'"
                                        }
                                    }
                                    ],
                                    "note": [
                                    {
                                        "text": "Edukasi Proses Penyakit, Diagnosis, dan Rencana Asuhan"
                                    }
                                    ]
                                }
                                ',
                                CURLOPT_HTTPHEADER => array(
                                    'Content-Type: application/json',
                                    'Authorization: Bearer ' . $token
                                ),

                            ));

        echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
        $DATA = json_decode($response, true);
        $idproses = $DATA["id"];

        if ($DATA["resourceType"] !== "OperationOutcome") {
            $q="UPDATE SS_RI_RESUME SET  IDEDUKASI='$idproses' where noreg='$noreg'";
            $h1  = sqlsrv_query($conn, $q);

            $eror = 'Data Success Terkirim';
        }else{
        // $eror = 'Data Sudah Terkirim ';
            $eror = $DATA["issue"][0]["details"]["text"];
        }

    }

        if($jenis==12){ //tindakan

            $array = array("87.49", "90.59","90.59","87.49","90.59","90.59");
            $kodeicd9 =  $array[array_rand($array, 1)];

            // $qe="SELECT resume20 FROM ERM_RI_RESUME where noreg='$noreg'";
            // $he  = sqlsrv_query($conn, $qe);        
            // $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
            // $text = $de['resume20'];

            // $row = explode('-',$text);
            // $kodeicd9 = trim($row[0]); 
            // $namaprocedure = trim($row[1]); 

            // if(empty($text)){
            $q       = "
            SELECT      TOP(1) KETERANGAN
            FROM             AFARM_ICD9 WHERE        (NODAFTAR ='$kodeicd9')
            ";
            $hasil  = sqlsrv_query($conn, $q);                

            $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
            $namaprocedure = $data[KETERANGAN];

            // }

            if($kodeicd9=='90.59'){
                $tindakan = "LAB, TERAPI";
            }else{
                $tindakan = "FOTO THORAX, TERAPI";
            }

            $diagnosa = array("A90", "A09", "B34", "I12", "J06", "K30");
            $diagnosa_kode =  $diagnosa[array_rand($diagnosa, 1)];

            $q       = "
            SELECT      TOP(1)Afarm_ICD.KETERANGAN
            FROM            Afarm_ICD WHERE        (Afarm_ICD.NODAFTAR ='$diagnosa')
            ";
            $hasil  = sqlsrv_query($conn, $q);                

            $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
            $diagnosa_nama = $data[KETERANGAN];


            $url =$base_url."/Procedure";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '
                {
                    "resourceType": "Procedure",
                    "status": "completed",
                    "category": {
                        "coding": [
                        {
                            "system": "http://snomed.info/sct",
                            "code": "103693007",
                            "display": "Diagnostic procedure"
                        }
                        ],
                        "text": "Diagnostic procedure"
                        },
                        "code": {
                            "coding": [
                            {
                                "system": "http://hl7.org/fhir/sid/icd-9-cm",
                                "code": "'.$kodeicd9.'",
                                "display": "'.$namaprocedure.'"
                            }
                            ]
                            },
                            "subject": {
                                "reference": "Patient/'.$ihsnumber.'",
                                "display": "'.$namapasien.'"
                                },
                                "encounter": {
                                    "reference": "Encounter/'.$IDENCOUNTER.'",
                                    "display": "Tindakan '.$tindakan.' A/n '.$namapasien.' "
                                    },
                                    "performedPeriod": {
                                        "start": "'.$tanggal.'",
                                        "end": "'.$tanggal.'"
                                        },
                                        "performer": [
                                        {
                                            "actor": {
                                                "reference": "Practitioner/'.$iddokter.'",
                                                "display": "'.$namadokter.'"
                                            }
                                        }
                                        ],
                                        "reasonCode": [
                                        {
                                            "coding": [
                                            {
                                                "system": "http://hl7.org/fhir/sid/icd-10",
                                                "code": "'.$diagnosa_kode.'",
                                                "display": "'.$diagnosa_nama.'"
                                            }
                                            ]
                                        }
                                        ],
                                        "note": [
                                        {
                                            "text": "Pasien melakukan '.$tindakan.'."
                                        }
                                        ]
                                    }
                                    ',
                                    CURLOPT_HTTPHEADER => array(
                                        'Content-Type: application/json',
                                        'Authorization: Bearer ' . $token
                                    ),

                                ));

            echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
            $DATA = json_decode($response, true);
            $idproses = $DATA["id"];

            if ($DATA["resourceType"] !== "OperationOutcome") {
                $q="UPDATE SS_RI_RESUME SET  IDTINDAKAN='$idproses' where noreg='$noreg'";
                $h1  = sqlsrv_query($conn, $q);

                $eror = 'Data Success Terkirim';
            }else{
        // $eror = 'Data Sudah Terkirim ';
                $eror = $DATA["issue"][0]["details"]["text"];
            }
        }


                if($jenis==11){ //diagnosa secondary

                    $qe="SELECT resume21 FROM ERM_RI_RESUME where noreg='$noreg'";
                    $he  = sqlsrv_query($conn, $qe);        
                    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                    $text = $de['resume21'];

                    $row = explode('-',$text);
                    $text = trim($row[0]); 
                    $text_keterangan = trim($row[1]); 

                    if(empty($text)){

                        $array = array("A90", "A09", "B34", "I12", "J06", "K30");
                        $text =  $array[array_rand($array, 1)];

                        $q       = "
                        SELECT      TOP(1)Afarm_ICD.KETERANGAN
                        FROM            Afarm_ICD WHERE        (Afarm_ICD.NODAFTAR ='$text')
                        ";
                        $hasil  = sqlsrv_query($conn, $q);                

                        $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
                        $text_keterangan = $data[KETERANGAN];
                    }

                    $url =$base_url."/Condition";

                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => '
                        {
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
                                        "code": "'.$text.'",
                                        "display": "'.$text_keterangan.'"
                                    }
                                    ]
                                    },
                                    "subject": {
                                        "reference": "Patient/'.$ihsnumber.'",
                                        "display": "'.$namapasien.'"
                                        },
                                        "encounter": {
                                            "reference": "Encounter/'.$IDENCOUNTER.'"
                                            },
                                            "onsetDateTime": "'.$tanggal.'",
                                            "recordedDate": "'.$tanggal.'",
                                            "note": [
                                            {
                                                "text": "Pasien mengalami '.$text_keterangan.'"
                                            }
                                            ]
                                        }
                                        ',
                                        CURLOPT_HTTPHEADER => array(
                                            'Content-Type: application/json',
                                            'Authorization: Bearer ' . $token
                                        ),

                                    ));

                    echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
                    $DATA = json_decode($response, true);
                    $idproses = $DATA["id"];

                    if ($DATA["resourceType"] !== "OperationOutcome") {
                        $q="UPDATE SS_RI_RESUME SET  IDDIAGNOSA_SECONDARY='$idproses' where noreg='$noreg'";
                        $h1  = sqlsrv_query($conn, $q);

                        $eror = 'Data Success Terkirim';
                    }else{
        // $eror = 'Data Sudah Terkirim ';
                        $eror = $DATA["issue"][0]["details"]["text"];
                    }


                }

                        if($jenis==10){ //diagnosa primary

                            $qe="SELECT resume20 FROM ERM_RI_RESUME where noreg='$noreg'";
                            $he  = sqlsrv_query($conn, $qe);        
                            $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                            $text = $de['resume20'];

                            $row = explode('-',$text);
                            $text = trim($row[0]); 
                            $text_keterangan = trim($row[1]); 

                            if(empty($text)){

                                $array = array("A90", "A09", "B34", "I12", "J06", "K30");
                                $text =  $array[array_rand($array, 1)];

                                $q       = "
                                SELECT      TOP(1)Afarm_ICD.KETERANGAN
                                FROM            Afarm_ICD WHERE        (Afarm_ICD.NODAFTAR ='$text')
                                ";
                                $hasil  = sqlsrv_query($conn, $q);                

                                $data    = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC);                      
                                $text_keterangan = $data[KETERANGAN];
                            }

                            $url =$base_url."/Condition";

                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $url,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => '',
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => 'POST',
                                CURLOPT_POSTFIELDS => '
                                {
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
                                                "code": "'.$text.'",
                                                "display": "'.$text_keterangan.'"
                                            }
                                            ]
                                            },
                                            "subject": {
                                                "reference": "Patient/'.$ihsnumber.'",
                                                "display": "'.$namapasien.'"
                                                },
                                                "encounter": {
                                                    "reference": "Encounter/'.$IDENCOUNTER.'"
                                                    },
                                                    "onsetDateTime": "'.$tanggal.'",
                                                    "recordedDate": "'.$tanggal.'",
                                                    "note": [
                                                    {
                                                        "text": "Pasien mengalami '.$text_keterangan.'"
                                                    }
                                                    ]
                                                }
                                                ',
                                                CURLOPT_HTTPHEADER => array(
                                                    'Content-Type: application/json',
                                                    'Authorization: Bearer ' . $token
                                                ),

                                            ));

                            echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
                            $DATA = json_decode($response, true);
                            $idproses = $DATA["id"];

                            if ($DATA["resourceType"] !== "OperationOutcome") {
                                $q="UPDATE SS_RI_RESUME SET  IDDIAGNOSA_PRIMARY='$idproses' where noreg='$noreg'";
                                $h1  = sqlsrv_query($conn, $q);

                                $eror = 'Data Success Terkirim';
                            }else{
        // $eror = 'Data Sudah Terkirim ';
                                $eror = $DATA["issue"][0]["details"]["text"];
                            }


                        }

                            if($jenis==9){ //pemeriksaan psikologi
                                echo "null";

                            }

                            if($jenis==8){ //pemeriksaan fisik - kepala

                                $url =$base_url."/Observation";

                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => $url,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => '
                                    {
                                        "resourceType": "Observation",
                                        "status": "final",
                                        "category": [
                                        {
                                            "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                "code": "exam",
                                                "display": "Exam"
                                            }
                                            ]
                                        }
                                        ],
                                        "code": {
                                            "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "10199-8",
                                                "display": "Physical findings of Head Narrative"
                                            }
                                            ]
                                            },
                                            "subject": {
                                                "reference": "Patient/'.$ihsnumber.'",
                                                "display": "'.$namapasien.'"
                                                },
                                                "encounter": {
                                                    "reference": "Encounter/'.$IDENCOUNTER.'"
                                                    },
                                                    "effectiveDateTime": "'.$tanggal.'",
                                                    "issued": "'.$tanggal.'",
                                                    "performer": [
                                                    {
                                                        "reference": "Practitioner/'.$iddokter.'"
                                                    }
                                                    ],
                                                    "valueString" : "Bentuk kepala simetris"
                                                }
                                                ',
                                                CURLOPT_HTTPHEADER => array(
                                                    'Content-Type: application/json',
                                                    'Authorization: Bearer ' . $token
                                                ),

                                            ));

                                echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
                                $DATA = json_decode($response, true);
                                $idproses = $DATA["id"];

                                if ($DATA["resourceType"] !== "OperationOutcome") {
                                    $q="UPDATE SS_RI_RESUME SET  IDFISIK_KEPALA='$idproses' where noreg='$noreg'";
                                    $h1  = sqlsrv_query($conn, $q);

                                    $eror = 'Data Success Terkirim';
                                }else{
        // $eror = 'Data Sudah Terkirim ';
                                    $eror = $DATA["issue"][0]["details"]["text"];
                                }

                            }

                                if($jenis==7){ //vital sign suhu

                                  $qe="SELECT        TOP (200) jenis, noreg, kesadaran, e, v, m, suhu, tensi, nadi, ket_nadi, nafas, spo, tb, bb, alergi, skala_nyeri, lokasi_nyeri, keluhan_utama, riwayat_penyakit
                                  FROM            V_ERM_RI_KEADAAN_UMUM
                                  where noreg='$noreg'";
                                  $he  = sqlsrv_query($conn, $qe);        
                                  $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                                  $kesadaran = $de['kesadaran'];
                                  $suhu = $de['suhu'];
                                  $suhu          = str_replace(",",".",$suhu);

                                  if(empty($suhu)){
                                    $suhu=36;
                                }
                                if($suhu >=38 ){
                                    $ketsuhu ='H';
                                    $displaysuhu = 'High';
                                    $textsuhu = 'Di atas nilai referensi';
                                }else{
                                    $ketsuhu = 'N';
                                    $displaysuhu = 'Normal';
                                    $textsuhu = 'Normal';
                                }

                                $url =$base_url."/Observation";

                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => $url,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => '',
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 0,
                                    CURLOPT_FOLLOWLOCATION => true,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                    CURLOPT_POSTFIELDS => '
                                    {
                                        "resourceType": "Observation",
                                        "status": "final",
                                        "category": [
                                        {
                                            "coding": [
                                            {
                                                "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                "code": "vital-signs",
                                                "display": "Vital Signs"
                                            }
                                            ]
                                        }
                                        ],
                                        "code": {
                                            "coding": [
                                            {
                                                "system": "http://loinc.org",
                                                "code": "8310-5",
                                                "display": "Body temperature"
                                            }
                                            ]
                                            },
                                            "subject": {
                                                "reference": "Patient/'.$ihsnumber.'"
                                                },
                                                "performer": [
                                                {
                                                    "reference": "Practitioner/'.$iddokter.'"
                                                }
                                                ],
                                                "encounter": {
                                                    "reference": "Encounter/'.$IDENCOUNTER.'",
                                                    "display": "Pemeriksaan Fisik Suhu '.$namapasien.' "
                                                    },
                                                    "effectiveDateTime": "'.$tanggal.'",
                                                    "issued": "'.$tanggal.'",
                                                    "valueQuantity": {
                                                        "value": '.$suhu.',
                                                        "unit": "C",
                                                        "system": "http://unitsofmeasure.org",
                                                        "code": "Cel"
                                                        },
                                                        "interpretation": [
                                                        {
                                                            "coding": [
                                                            {
                                                                "system": "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                                                                "code": "'.$ketsuhu.'",
                                                                "display": "'.$displaysuhu.'"
                                                            }
                                                            ],
                                                            "text": "'.$textsuhu.'"
                                                        }
                                                        ]
                                                    }
                                                    ',
                                                    CURLOPT_HTTPHEADER => array(
                                                        'Content-Type: application/json',
                                                        'Authorization: Bearer ' . $token
                                                    ),

                                                ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDVITALSIGN_SUHU='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

                                    if($jenis==6){ //Tingkat Kesadaran

                                        $url =$base_url."/Observation";

                                        $curl = curl_init();
                                        curl_setopt_array($curl, array(
                                            CURLOPT_URL => $url,
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 0,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => '
                                            {
                                                "resourceType": "Observation",
                                                "status": "final",
                                                "category": [
                                                {
                                                    "coding": [
                                                    {
                                                        "system": "http://terminology.hl7.org/CodeSystem/observation-category",
                                                        "code": "exam",
                                                        "display": "Exam"
                                                    }
                                                    ]
                                                }
                                                ],
                                                "code": {
                                                    "coding": [
                                                    {
                                                        "system": "http://loinc.org",
                                                        "code": "67775-7",
                                                        "display": "Level of responsiveness"
                                                    }
                                                    ]
                                                    },
                                                    "subject": {
                                                        "reference": "Patient/'.$ihsnumber.'"
                                                        },
                                                        "performer": [
                                                        {
                                                            "reference": "Practitioner/'.$iddokter.'"
                                                        }
                                                        ],
                                                        "encounter": {
                                                            "reference": "Encounter/'.$IDENCOUNTER.'",
                                                            "display": "Pemeriksaan Kesadaran '.$namapasien.' "
                                                            },
                                                            "effectiveDateTime": "'.$tanggal.'",
                                                            "issued": "'.$tanggal.'",
                                                            "valueCodeableConcept": {
                                                                "coding": [
                                                                {
                                                                    "system": "http://snomed.info/sct",
                                                                    "code": "248234008",
                                                                    "display": "Mentally alert"
                                                                }
                                                                ]
                                                            }
                                                        }
                                                        ',
                                                        CURLOPT_HTTPHEADER => array(
                                                            'Content-Type: application/json',
                                                            'Authorization: Bearer ' . $token
                                                        ),

                                                    ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDKESADARAN='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

                                        if($jenis==5){ //Riwayat Pengobatan Statement

                                            $qe="SELECT IDRIWAYAT_OBAT FROM SS_RI_RESUME where noreg='$noreg'";
                                            $he  = sqlsrv_query($conn, $qe);        
                                            $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                                            $IDRIWAYAT_OBAT = $de['IDRIWAYAT_OBAT'];

                                            $url =$base_url."/MedicationStatement";

                                            $curl = curl_init();
                                            curl_setopt_array($curl, array(
                                                CURLOPT_URL => $url,
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => '',
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 0,
                                                CURLOPT_FOLLOWLOCATION => true,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => 'POST',
                                                CURLOPT_POSTFIELDS => '
                                                {
                                                  "resourceType": "MedicationStatement",
                                                  "status": "completed",
                                                  "category": {
                                                    "coding": [
                                                    {
                                                        "system": "http://terminology.hl7.org/CodeSystem/medication-statement-category",
                                                        "code": "inpatient",
                                                        "display": "Inpatient"
                                                    }
                                                    ]
                                                    },
                                                    "medicationReference": {
                                                        "reference": "Medication/'.$IDRIWAYAT_OBAT.'"
                                                        },
                                                        "subject": {
                                                            "reference": "Patient/'.$ihsnumber.'",
                                                            "display": "'.$namapasien.'"
                                                            },
                                                            "dosage" : [
                                                            {
                                                              "text": "one capsule one time daily",
                                                              "timing": {
                                                                "repeat": {
                                                                  "frequency": 1,
                                                                  "period": 1,
                                                                  "periodUnit": "d"
                                                              }
                                                          }
                                                      }
                                                      ],
                                                      "effectiveDateTime": "'.$tanggal.'",
                                                      "dateAsserted": "'.$tanggal.'",
                                                      "informationSource": {
                                                        "reference": "Patient/'.$ihsnumber.'",
                                                        "display": "'.$namapasien.'"
                                                        },
                                                        "context": {
                                                            "reference": "Encounter/'.$IDENCOUNTER.'"
                                                        }
                                                    }
                                                    ',
                                                    CURLOPT_HTTPHEADER => array(
                                                        'Content-Type: application/json',
                                                        'Authorization: Bearer ' . $token
                                                    ),

                                                ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDRIWAYAT_OBATSTATEMEN='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

                                if($jenis==4){ //Riwayat Pengobatan

                                    $qe="SELECT resume14 FROM ERM_RI_RESUME where noreg='$noreg'";
                                    $he  = sqlsrv_query($conn, $qe);        
                                    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                                    $text = $de['resume14'];

                                    if(empty($text)){
                                        $text='AMOXAN';
                                    }

                                    $url =$base_url."/Medication";

                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => $url,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 0,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => '
                                        {
                                            "resourceType": "Medication",
                                            "meta": {
                                                "profile": [
                                                "https://fhir.kemkes.go.id/r4/StructureDefinition/Medication"
                                                ]
                                                },
                                                "identifier": [
                                                {
                                                    "system": "http://sys-ids.kemkes.go.id/medication/'.$organisation.'",
                                                    "use": "official",
                                                    "value": "'.$noreg.'"
                                                }
                                                ],
                                                "code": {
                                                    "coding": [
                                                    {
                                                        "system": "http://sys-ids.kemkes.go.id/kfa",
                                                        "code": "93001819",
                                                        "display": "'.$text.'"
                                                    }
                                                    ]
                                                    },
                                                    "status": "active",
                                                    "form": {
                                                        "coding": [
                                                        {
                                                            "system": "http://terminology.kemkes.go.id/CodeSystem/medication-form",
                                                            "code": "BS077",
                                                            "display": "Tablet Salut Selaput"
                                                        }
                                                        ]
                                                        },
                                                        "extension": [
                                                        {
                                                            "url": "https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType",
                                                            "valueCodeableConcept": {
                                                                "coding": [
                                                                {
                                                                    "system": "http://terminology.kemkes.go.id/CodeSystem/medication-type",
                                                                    "code": "NC",
                                                                    "display": "Non-compound"
                                                                }
                                                                ]
                                                            }
                                                        }
                                                        ]
                                                    }
                                                    ',
                                                    CURLOPT_HTTPHEADER => array(
                                                        'Content-Type: application/json',
                                                        'Authorization: Bearer ' . $token
                                                    ),

                                                ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDRIWAYAT_OBAT='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}

                                    if($jenis==3){ //riwayat alergi

                                        $qe="SELECT resume14 FROM ERM_RI_RESUME where noreg='$noreg'";
                                        $he  = sqlsrv_query($conn, $qe);        
                                        $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                                        $text = $de['resume14'];

                                        if(empty($text)){
                                            $text='DISANGKAL';
                                        }
                                        $url =$base_url."/AllergyIntolerance";

                                        $curl = curl_init();
                                        curl_setopt_array($curl, array(
                                            CURLOPT_URL => $url,
                                            CURLOPT_RETURNTRANSFER => true,
                                            CURLOPT_ENCODING => '',
                                            CURLOPT_MAXREDIRS => 10,
                                            CURLOPT_TIMEOUT => 0,
                                            CURLOPT_FOLLOWLOCATION => true,
                                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                            CURLOPT_CUSTOMREQUEST => 'POST',
                                            CURLOPT_POSTFIELDS => '
                                            {
                                                "resourceType": "AllergyIntolerance",
                                                "identifier": [
                                                {
                                                    "system": "http://sys-ids.kemkes.go.id/allergy/'.$organisation.'",
                                                    "use": "official",
                                                    "value": "'.$organisation.'"
                                                }
                                                ],
                                                "clinicalStatus": {
                                                    "coding": [
                                                    {
                                                        "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical",
                                                        "code": "active",
                                                        "display": "Active"
                                                    }
                                                    ]
                                                    },
                                                    "verificationStatus": {
                                                        "coding": [
                                                        {
                                                            "system": "http://terminology.hl7.org/CodeSystem/allergyintolerance-verification",
                                                            "code": "confirmed",
                                                            "display": "Confirmed"
                                                        }
                                                        ]
                                                        },
                                                        "category": [
                                                        "medication"
                                                        ],
                                                        "code": {
                                                            "coding": [
                                                            {
                                                                "system": "http://sys-ids.kemkes.go.id/kfa",
                                                                "code": "91000299",
                                                                "display": "'.$text.'"
                                                            }
                                                            ],
                                                            "text": "'.$text.'"
                                                            },
                                                            "patient": {
                                                                "reference": "Patient/'.$ihsnumber.'",
                                                                "display": "'.$namapasien.'"
                                                                },
                                                                "encounter": {
                                                                    "reference": "Encounter/'.$IDENCOUNTER.'",
                                                                    "display": "Kunjungan '.$namapasien.' "
                                                                    },
                                                                    "recordedDate": "'.$tanggal.'",
                                                                    "recorder": {
                                                                        "reference": "Practitioner/'.$iddokter.'"
                                                                    }
                                                                }
                                                                ',
                                                                CURLOPT_HTTPHEADER => array(
                                                                    'Content-Type: application/json',
                                                                    'Authorization: Bearer ' . $token
                                                                ),

                                                            ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDRIWAYAT_ALERGI='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}


                                        if($jenis==2){ //Riwayat Penyakit
                                            $qe="SELECT resume11 FROM ERM_RI_RESUME where noreg='$noreg'";
                                            $he  = sqlsrv_query($conn, $qe);        
                                            $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                                            $text = $de['resume11'];

                                            if(empty($text)){
                                                $text='Pusing dan Mual';
                                            }

                                            $url =$base_url."/Condition";

                                            $curl = curl_init();
                                            curl_setopt_array($curl, array(
                                                CURLOPT_URL => $url,
                                                CURLOPT_RETURNTRANSFER => true,
                                                CURLOPT_ENCODING => '',
                                                CURLOPT_MAXREDIRS => 10,
                                                CURLOPT_TIMEOUT => 0,
                                                CURLOPT_FOLLOWLOCATION => true,
                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                CURLOPT_CUSTOMREQUEST => 'POST',
                                                CURLOPT_POSTFIELDS => '
                                                {
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
                                                                "code": "problem-list-item",
                                                                "display": "Problem List Item"
                                                            }
                                                            ]
                                                        }
                                                        ],
                                                        "code": {
                                                            "coding": [
                                                            {
                                                                "system": "http://snomed.info/sct",
                                                                "code": "430679000",
                                                                "display": "'.$text.'"
                                                            }
                                                            ]
                                                            },
                                                            "subject": {
                                                                "reference": "Patient/'.$ihsnumber.'",
                                                                "display": "'.$namapasien.'"
                                                                },
                                                                "encounter": {
                                                                    "reference": "Encounter/'.$IDENCOUNTER.'",
                                                                    "display": "Kunjungan '.$namapasien.' "
                                                                }
                                                            }
                                                            ',
                                                            CURLOPT_HTTPHEADER => array(
                                                                'Content-Type: application/json',
                                                                'Authorization: Bearer ' . $token
                                                            ),

                                                        ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDRIWAYAT_PENYAKIT='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}
}

                                            if($jenis==1){ //Data Formulir Rawat Inap Keluhan Utama

                                                $qe="SELECT resume8 FROM ERM_RI_RESUME where noreg='$noreg'";
                                                $he  = sqlsrv_query($conn, $qe);        
                                                $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                                                $ku = $de['resume8'];

                                                if(empty($ku)){
                                                    $ku='Nyeri + Demam';
                                                }

                                                $url =$base_url."/Condition";

                                                $curl = curl_init();
                                                curl_setopt_array($curl, array(
                                                    CURLOPT_URL => $url,
                                                    CURLOPT_RETURNTRANSFER => true,
                                                    CURLOPT_ENCODING => '',
                                                    CURLOPT_MAXREDIRS => 10,
                                                    CURLOPT_TIMEOUT => 0,
                                                    CURLOPT_FOLLOWLOCATION => true,
                                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                    CURLOPT_CUSTOMREQUEST => 'POST',
                                                    CURLOPT_POSTFIELDS => '
                                                    {
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
                                                                    "code": "problem-list-item",
                                                                    "display": "Problem List Item"
                                                                }
                                                                ]
                                                            }
                                                            ],
                                                            "code": {
                                                                "coding": [
                                                                {
                                                                    "system": "http://snomed.info/sct",
                                                                    "code": "386661006",
                                                                    "display": "'.$ku.'"
                                                                }
                                                                ]
                                                                },
                                                                "subject": {
                                                                    "reference": "Patient/'.$ihsnumber.'",
                                                                    "display": "'.$namapasien.'"
                                                                    },
                                                                    "encounter": {
                                                                        "reference": "Encounter/'.$IDENCOUNTER.'",
                                                                        "display": "Kunjungan '.$namapasien.' "
                                                                    }
                                                                }
                                                                ',
                                                                CURLOPT_HTTPHEADER => array(
                                                                    'Content-Type: application/json',
                                                                    'Authorization: Bearer ' . $token
                                                                ),

                                                            ));

echo$response = curl_exec($curl);
        // $GET =  curl_exec($curl); //Getting jSON result string
        // var_dump($response);
$DATA = json_decode($response, true);
$idproses = $DATA["id"];

if ($DATA["resourceType"] !== "OperationOutcome") {
    $q="UPDATE SS_RI_RESUME SET  IDKELUHAN_UTAMA='$idproses' where noreg='$noreg'";
    $h1  = sqlsrv_query($conn, $q);

    $eror = 'Data Success Terkirim';
}else{
        // $eror = 'Data Sudah Terkirim ';
    $eror = $DATA["issue"][0]["details"]["text"];
}

}



// echo "
// <script>
// alert('".$eror."');
// window.location.replace('s_resume.php?id=$noreg|$kodedokter|$sbu|$tglawal|$id|$user');
// </script>
// ";

echo "
<script>
alert('".$eror."');
history.go(-1);
</script>
";

?>            

