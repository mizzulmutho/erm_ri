<?php 

echo "72c99acb-f8af-4c1d-bd30-912e0ebb8cce";
echo "<br>";

// echo $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));


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

    // Output the 36 character UUID.
echo "uuid1 : ".$uuid1 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data1), 4));echo "<br>";
echo "uuid2 : ".$uuid2 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data2), 4));echo "<br>";
echo "uuid3 : ".$uuid3 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data3), 4));echo "<br>";
echo "uuid4 : ".$uuid4 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data4), 4));echo "<br>";
echo "uuid5 : ".$uuid5 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data5), 4));echo "<br>";
echo "uuid6 : ".$uuid6 = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data6), 4));echo "<br>";

//Encounter - Condition - Procedure - Observation

$ws_query["resourceType"] = "Bundle";
$ws_query["type"] = "transaction";

$ws_query["entry"]["0"]["fullUrl"] = 'urn:uuid:72c99acb-f8af-4c1d-bd30-912e0ebb8cce' ;
$ws_query["entry"]["0"]["resource"]["resourceType"]  = 'Encounter' ;
$ws_query["entry"]["0"]["resource"]["identifier"]["0"]["system"]  = 'http://sys-ids.kemkes.go.id/encounter/10085103' ;
$ws_query["entry"]["0"]["resource"]["identifier"]["0"]["value"]  = '10085103' ;
$ws_query["entry"]["0"]["resource"]["status"] = 'finished' ;
$ws_query["entry"]["0"]["resource"]["class"]["system"] = 'http://terminology.hl7.org/CodeSystem/v3-ActCode' ;
$ws_query["entry"]["0"]["resource"]["class"]["code"] = 'IMP' ;
$ws_query["entry"]["0"]["resource"]["class"]["display"] = 'inpatient encounter' ;
$ws_query["entry"]["0"]["resource"]["subject"]["reference"] = 'Patient/100000030015' ;
$ws_query["entry"]["0"]["resource"]["subject"]["display"] = 'Diana Smith' ;
$ws_query["entry"]["0"]["resource"]["participant"]["0"]["type"]["0"]["coding"]["0"]["system"]  = 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType' ;
$ws_query["entry"]["0"]["resource"]["participant"]["0"]["type"]["0"]["coding"]["0"]["code"]  = 'ATND' ;
$ws_query["entry"]["0"]["resource"]["participant"]["0"]["type"]["0"]["coding"]["0"]["display"]  = 'attender' ;
$ws_query["entry"]["0"]["resource"]["participant"]["0"]["individual"]["reference"]  = 'Practitioner/N10000001' ;
$ws_query["entry"]["0"]["resource"]["participant"]["0"]["individual"]["display"]  = 'Dokter Bronsig' ;
$ws_query["entry"]["0"]["resource"]["participant"]["0"]["period"]["start"]  = '2021-09-10T08:00:00+00:00' ;
$ws_query["entry"]["0"]["resource"]["participant"]["0"]["period"]["end"]  = '2021-09-12T10:00:00+00:00' ;
$ws_query["entry"]["0"]["resource"]["participant"]["1"]["type"]["0"]["coding"]["0"]["system"]  = 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType' ;
$ws_query["entry"]["0"]["resource"]["participant"]["1"]["type"]["0"]["coding"]["0"]["code"]  = 'ATND' ;
$ws_query["entry"]["0"]["resource"]["participant"]["1"]["type"]["0"]["coding"]["0"]["display"]  = 'attender' ;
$ws_query["entry"]["0"]["resource"]["participant"]["1"]["individual"]["reference"]  = 'Practitioner/N10000002' ;
$ws_query["entry"]["0"]["resource"]["participant"]["1"]["individual"]["period"]["start"]  = '2021-09-12T10:00:00+00:00' ;
$ws_query["entry"]["0"]["resource"]["participant"]["1"]["individual"]["period"]["end"]   = '2021-09-15T09:30:27+07:00' ;

$ws_query["entry"]["0"]["resource"]["period"]["start"]  = '2021-09-10T08:00:00+00:00' ;
$ws_query["entry"]["0"]["resource"]["period"]["end"]  = '2021-09-15T09:30:27+07:00' ;

$ws_query["entry"]["0"]["resource"]["location"]["0"]["location"]["reference"]  = 'Location/b29038d4-9ef0-4eb3-a2e9-3c02df668b07' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["location"]["display"]  = 'Bed 2, Ruang 210, Bangsal Rawat Inap Kelas 1, Layanan Penyakit Dalam, Lantai 2, Gedung Utama' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["url"]  = 'https://fhir.kemkes.go.id/r4/StructureDefinition/ServiceClass' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["0"]["url"]  = 'value' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["0"]["valueCodeableConcept"]["coding"]["0"]["system"]  = 'http://terminology.kemkes.go.id/CodeSystem/locationServiceClass-Inpatient' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["0"]["valueCodeableConcept"]["coding"]["0"]["code"]  = '1' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["0"]["valueCodeableConcept"]["coding"]["0"]["display"]  = 'Kelas 1' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["1"]["url"] = 'upgradeClassIndicator' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["1"]["valueCodeableConcept"]["coding"]["0"]["system"] = 'http://terminology.kemkes.go.id/CodeSystem/locationUpgradeClass' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["1"]["valueCodeableConcept"]["coding"]["0"]["code"] = 'kelas-tetap' ;
$ws_query["entry"]["0"]["resource"]["location"]["0"]["extension"]["0"]["extension"]["1"]["valueCodeableConcept"]["coding"]["0"]["display"] = 'Kelas Tetap Perawatan' ;

$ws_query["entry"]["0"]["resource"]["diagnosis"]["0"]["condition"]["reference"]  = 'Condition/urn:uuid:a734df17-84ca-4a09-998c-95442eba13d9' ;
$ws_query["entry"]["0"]["resource"]["diagnosis"]["0"]["condition"]["display"]  = 'Chronic kidney disease, stage 5' ;
$ws_query["entry"]["0"]["resource"]["diagnosis"]["0"]["use"]["coding"]["0"]["system"]  = 'http://terminology.hl7.org/CodeSystem/diagnosis-role' ;
$ws_query["entry"]["0"]["resource"]["diagnosis"]["0"]["use"]["coding"]["0"]["code"]  = 'DD' ;
$ws_query["entry"]["0"]["resource"]["diagnosis"]["0"]["use"]["coding"]["0"]["display"]  = 'Discharge diagnosis' ;

$ws_query["entry"]["0"]["resource"]["statusHistory"]["0"]["status"]  = 'in-progress' ;
$ws_query["entry"]["0"]["resource"]["statusHistory"]["0"]["period"]["start"]  = '2021-09-10T08:00:00+00:00' ;
$ws_query["entry"]["0"]["resource"]["statusHistory"]["0"]["period"]["end"]  = '2021-09-15T09:30:27+07:00' ;
$ws_query["entry"]["0"]["resource"]["statusHistory"]["1"]["status"]  = 'finished' ;
$ws_query["entry"]["0"]["resource"]["statusHistory"]["1"]["period"]["start"]  = '2021-09-15T09:30:27+07:00' ;
$ws_query["entry"]["0"]["resource"]["statusHistory"]["1"]["period"]["end"]  = '2021-09-15T09:30:27+07:00' ;

$ws_query["entry"]["0"]["resource"]["hospitalization"]["dischargeDisposition"]["coding"]["0"]["system"] = 'http://terminology.hl7.org/CodeSystem/discharge-disposition' ;
$ws_query["entry"]["0"]["resource"]["hospitalization"]["dischargeDisposition"]["coding"]["0"]["code"] = 'home' ;
$ws_query["entry"]["0"]["resource"]["hospitalization"]["dischargeDisposition"]["coding"]["0"]["display"] = 'Home' ;
$ws_query["entry"]["0"]["resource"]["hospitalization"]["dischargeDisposition"]["text"] = 'Anjuran dokter untuk pulang dan kontrol kembali' ;
$ws_query["entry"]["0"]["resource"]["serviceProvider"]["reference"]  = 'Organization/10085103' ;
$ws_query["entry"]["0"]["resource"]["basedOn"]["0"]["reference"]  = 'ServiceRequest/urn:uuid:1e1a260d-538f-4172-ad68-0aa5f8ccfc4a' ;

$ws_query["entry"]["0"]["request"]["method"]  = 'POST' ;
$ws_query["entry"]["0"]["request"]["url"]  = 'Encounter' ;
$ws_query["entry"]["0"]["resource"]["diagnosis"]["0"]["rank"]  = 1 ;


$ws_query["entry"]["1"]["fullUrl"] = 'urn:uuid:a734df17-84ca-4a09-998c-95442eba13d9' ;
$ws_query["entry"]["1"]["resource"]["category"]["0"]["coding"]["0"]["code"]  = 'Encounter' ;


echo $json_requesti = json_encode($ws_query);





?>            