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
$user = trim($row[4]); 
// $idencounter = trim($row[6]); 

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
$IDFORMULIR_INAP = $data_res[IDFORMULIR_INAP];
$IDKELUHAN_UTAMA = $data_res[IDKELUHAN_UTAMA];
$IDRIWAYAT_PENYAKIT = $data_res[IDRIWAYAT_PENYAKIT];
$IDRIWAYAT_ALERGI = $data_res[IDRIWAYAT_ALERGI];
$IDRIWAYAT_OBAT = $data_res[IDRIWAYAT_OBAT];
$IDRIWAYAT_OBATSTATEMEN = $data_res[IDRIWAYAT_OBATSTATEMEN];
$IDKESADARAN = $data_res[IDKESADARAN];
$IDVITALSIGN_SUHU = $data_res[IDVITALSIGN_SUHU];
$IDFISIK_KEPALA = $data_res[IDFISIK_KEPALA];
$IDPSIKOLOGI = $data_res[IDPSIKOLOGI];
$IDDIAGNOSA_PRIMARY = $data_res[IDDIAGNOSA_PRIMARY];
$IDDIAGNOSA_SECONDARY = $data_res[IDDIAGNOSA_SECONDARY];
$IDTINDAKAN = $data_res[IDTINDAKAN];
$IDEDUKASI = $data_res[IDEDUKASI];
$IDPROGNOSIS = $data_res[IDPROGNOSIS];
$IDRUJUKAN_FASKES = $data_res[IDRUJUKAN_FASKES];
$IDKONTROL_MINGGU = $data_res[IDKONTROL_MINGGU];
$IDKONDISI_KELUAR = $data_res[IDKONDISI_KELUAR];
$IDRAWAT_PASIEN = $data_res[IDRAWAT_PASIEN];
$IDINSTRUKSI_MEDIK = $data_res[IDINSTRUKSI_MEDIK];
$IDLABSERVICE_REQ = $data_res[IDLABSERVICE_REQ];
$IDLABSPECIMEN = $data_res[IDLABSPECIMEN];
$IDLABOBSERVATION = $data_res[IDLABOBSERVATION];
$IDLABDIAGNOSIC = $data_res[IDLABDIAGNOSIC];
$IDRAD_STATUSKEHAMILAN = $data_res[IDRAD_STATUSKEHAMILAN];
$IDRAD_STATUSALERGI = $data_res[IDRAD_STATUSALERGI];
$IDRAD_SERVICEREQ = $data_res[IDRAD_SERVICEREQ];
$IDRAD_OBSERVATION = $data_res[IDRAD_OBSERVATION];
$IDRAD_DIAGNOSIC = $data_res[IDRAD_DIAGNOSIC];
$IDPERESEPAN_MEDICATION = $data_res[IDPERESEPAN_MEDICATION];
$IDPERESEPAN_MEDICATIONREQUEST = $data_res[IDPERESEPAN_MEDICATIONREQUEST];
$IDQuestionnaireResponse = $data_res[IDQuestionnaireResponse];
$IDPENGELUARANOBAT_MEDICATION = $data_res[IDPENGELUARANOBAT_MEDICATION];
$IDPENGELUARANOBAT_MEDICATIONDISPENCE = $data_res[IDPENGELUARANOBAT_MEDICATIONDISPENCE];
$IDRENCANAPULANG_OBSERVATION = $data_res[IDRENCANAPULANG_OBSERVATION];
$IDRENCANAPULANG_CAREPLAN = $data_res[IDRENCANAPULANG_CAREPLAN];
$IDDIET = $data_res[IDDIET];

//get data dari resume...
$qe="
SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume,CONVERT(VARCHAR, tglentry, 8) as jamentry
FROM ERM_RI_RESUME
where noreg='$noreg'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$jamentry = $de['jamentry'];
$resume2= $de['resume2'];
$resume1 = $de['resume1'];
$resume2= $de['resume2'];
$resume3= $de['resume3'];
$resume4= $de['resume4'];
$resume5= $de['resume5'];
$resume6= $de['resume6'];
$resume7= $de['resume7'];
$resume8= $de['resume8'];
$resume9= $de['resume9'];
$resume10= $de['resume10'];
$resume11= $de['resume11'];
$resume12= $de['resume12'];
$resume13= $de['resume13'];
$resume14= $de['resume14'];
$resume15= $de['resume15'];
$resume16= $de['resume16'];
$resume17= $de['resume17'];
$resume18= $de['resume18'];
$resume19= $de['resume19'];
$resume20= $de['resume20'];
$resume21= $de['resume21'];
$resume22= $de['resume22'];
$resume23= $de['resume23'];
$resume24= $de['resume24'];
$resume25= $de['resume25'];
$resume26= $de['resume26'];
$resume27= $de['resume27'];
$resume28= $de['resume28'];
$resume29= $de['resume29'];
$resume30= $de['resume30'];
$resume31= $de['resume31'];
$resume32= $de['resume32'];
$resume33= $de['resume33'];
$resume34= $de['resume34'];
$resume35= $de['resume35'];
$resume36= $de['resume36'];
$resume37= $de['resume37'];
$resume38= $de['resume38'];
$resume39= $de['resume39'];
$resume40= $de['resume40'];
$resume41= $de['resume41'];
$resume42= $de['resume42'];
$resume43= $de['resume43'];
$resume44= $de['resume44'];
$resume45= $de['resume45'];
$resume46= $de['resume46'];
$resume47= $de['resume47'];
$resume48= $de['resume48'];
$resume49= $de['resume49'];
$resume50= $de['resume50'];
$resume51= $de['resume51'];
$resume52= $de['resume52'];
$resume53= $de['resume53'];
$resume54= $de['resume54'];
$resume55= $de['resume55'];
$resume56= $de['resume56'];
$resume57= $de['resume57'];
$resume58= $de['resume58'];
$resume59= $de['resume59'];
$resume60= $de['resume60'];
$resume61= $de['resume61'];
$resume62= $de['resume62'];
$resume63= $de['resume63'];
$resume64= $de['resume64'];
$resume65= $de['resume65'];
$resume66= $de['resume66'];
$resume67= $de['resume67'];
$resume68= $de['resume68'];
$resume69= $de['resume69'];
$resume70= $de['resume70'];
$resume71= $de['resume71'];
$resume72= $de['resume72'];
$resume73= $de['resume73'];
$resume74= $de['resume74'];
$resume75= $de['resume75'];
$resume76= $de['resume76'];
$resume77= $de['resume77'];
$resume78= $de['resume78'];
$resume79= $de['resume79'];
$resume80= $de['resume80'];
$resume81= $de['resume81'];
$resume82= $de['resume82'];
$resume83= $de['resume83'];
$resume84= $de['resume84'];
$resume85= $de['resume85'];
$resume86= $de['resume86'];
$resume87= $de['resume87'];
$resume88= $de['resume88'];
$resume89= $de['resume89'];
$resume90= $de['resume90'];
$resume91= $de['resume91'];
$resume92= $de['resume92'];
$resume93= $de['resume93'];
$resume94= $de['resume94'];
$resume95= $de['resume95'];
$resume96= $de['resume96'];
$resume97= $de['resume97'];
$resume98= $de['resume98'];
$resume99= $de['resume99'];
$resume100= $de['resume100'];
$resume101= $de['resume101'];
$resume102= $de['resume102'];
$resume103= $de['resume103'];
$resume104= $de['resume104'];
$resume105= $de['resume105'];
$resume106= $de['resume106'];
$resume107= $de['resume107'];
$resume108= $de['resume108'];
$resume109= $de['resume109'];
$resume110= $de['resume110'];
$resume111= $de['resume111'];
$resume112= $de['resume112'];
$resume113= $de['resume113'];
$resume114= $de['resume114'];
$resume115= $de['resume115'];
$resume116= $de['resume116'];
$resume117= $de['resume117'];
$resume118= $de['resume118'];
$resume119= $de['resume119'];
$resume120= $de['resume120'];
$resume121= $de['resume121'];
$resume122= $de['resume122'];
$resume123= $de['resume123'];
$resume124= $de['resume124'];
$resume125= $de['resume125'];
$resume126= $de['resume126'];
$resume127= $de['resume127'];
$resume128= $de['resume128'];
$resume129= $de['resume129'];
$resume130= $de['resume130'];
$resume131= $de['resume131'];
$resume132= $de['resume132'];
$resume133= $de['resume133'];
$resume134= $de['resume134'];
$resume135= $de['resume135'];
$resume136= $de['resume136'];
$resume137= $de['resume137'];
$resume138= $de['resume138'];
$resume139= $de['resume139'];
$resume140= $de['resume140'];
$resume141= $de['resume141'];
$resume142= $de['resume142'];
$resume143= $de['resume143'];
$resume144= $de['resume144'];
$resume145= $de['resume145'];
$resume146= $de['resume146'];
$resume147= $de['resume147'];
$resume148= $de['resume148'];
$resume149= $de['resume149'];
$resume150= $de['resume150'];



?>            

<!DOCTYPE html> 
<html> 
<head>  
    <title>s_resume Resume Medis</title>  
    <link rel="icon" href="favicon.ico">  
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

</head> 
<div id="content"> 
    <div class="container">

        <?php
        $link = 's_sehat_resume_fase2.php?id='.trim($noreg).'|'.trim($sbu).'|'.trim($IDENCOUNTER).'|';
        $link2 = 's_sehat_resume_rawin.php?id='.trim($noreg).'|'.trim($sbu).'|'.trim($IDENCOUNTER);
        ?>

        <body onload="document.myForm.pasien_mcu.focus();">
            <form method="POST" name='myForm' action="" enctype="multipart/form-data">
                <br><br>
                <div class="row">
                    <a href='rekap.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
                    &nbsp;&nbsp;
                    <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
                    &nbsp;&nbsp;
                </div>
                <div class="row">
                    Pasien A/N NOREG : <?php echo $NOREG;?> NAMA PASIEN : <?php echo $namapasien;?> DOKTER : <?php echo $namadokter;?>
                </div>

                <div class="row">
                    <div class="col-12">
                        <b>Pendaftaran Kunjungan Rawat Inap : </b></td><td><?php echo $IDENCOUNTER;?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        Fase 1
                        <table width="100%">
                            <tr>
                                <td>no</td>
                                <td>kirim</td>
                            </tr>
                            <?php
                            for($i=1;$i<=17;$i++){
                                echo "
                                <tr>
                                <td>$i</td>
                                <td><a href='$link$i' target='_blank'><i class='bi bi-box-arrow-right'></i> Kirim</a></td>
                                </tr>
                                ";
                            }
                            ?>
                        </table>
                    </div>
                    <div class="col-6">
                        Fase 2
                        <table width="100%">
                            <tr>
                                <td></td>
                            </tr>
                            <?php
                            for($x=18;$x<=35;$x++){
                                echo "
                                <tr>
                                <td>$x</td>
                                <td><a href='$link$x'><i class='bi bi-box-arrow-right'></i> Kirim</a></td>
                                </tr>
                                ";
                            }
                            ?>
                        </table>
                    </div>
                </div>


                <hr>

                <div class="row">
                    Fase Pertama
                </div>

                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Data Formulir Rawat Inap                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        01. Anamnesis
                    </div>                    
                    <div class="col-4 border border-info">
                        <b>Keluhan Utama</b> : <?php echo $resume8; ?>&nbsp;<a href='<?php echo $link.'1';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><br>
                        <?php echo $IDKELUHAN_UTAMA;?>
                    </div>
                    <div class="col-4 border border-info">
                        <b>Riwayat Penyakit</b> : <?php echo $resume11; ?>&nbsp;<a href='<?php echo $link.'2';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><br>
                        <?php echo $IDRIWAYAT_PENYAKIT;?>                         
                    </div>
                    <div class="col-4 border border-info">
                        <b>Riwayat Alergi</b> : <?php echo $resume14; ?>&nbsp;<a href='<?php echo $link.'3';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><br>
                        <?php echo $IDRIWAYAT_ALERGI;?>                        
                    </div>
                    <div class="col-12 border border-info">
                        <b>Riwayat Pengobatan</b> : <?php echo $resume15; ?>&nbsp;<a href='<?php echo $link.'4';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a>                        
                        <?php echo $IDRIWAYAT_OBAT;?>
                        Statemen Obat : &nbsp;<a href='<?php echo $link.'5';?>' target='' class='btn btn-warning btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a> <?php echo $IDRIWAYAT_OBATSTATEMEN;?>                      
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        02. Pemeriksaan Fisik
                    </div> 
                    <?php
                    $qe="SELECT        TOP (200) jenis, noreg, kesadaran, e, v, m, suhu, tensi, nadi, ket_nadi, nafas, spo, tb, bb, alergi, skala_nyeri, lokasi_nyeri, keluhan_utama, riwayat_penyakit
                    FROM            V_ERM_RI_KEADAAN_UMUM
                    where noreg='$noreg'";
                    $he  = sqlsrv_query($conn, $qe);        
                    $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                    $kesadaran = $de['kesadaran'];
                    $suhu = $de['suhu'];
                    ?>
                    <div class="col-4 border border-info">
                        <b>Tingkat Kesadaran</b> : <?php echo $kesadaran; ?>&nbsp;<a href='<?php echo $link.'6';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><br>
                        <?php echo $IDKESADARAN;?>
                    </div>
                    <div class="col-4 border border-info">
                        <b>Vital Sign</b> : Suhu : <?php echo $suhu; ?>&nbsp;<a href='<?php echo $link.'7';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><br>
                        <?php echo $IDVITALSIGN_SUHU;?>
                    </div>
                    <?php
                    $c_asawal="
                    SELECT am1 
                    FROM ERM_RI_ANAMNESIS_MEDIS
                    where noreg='$noreg'";
                    $hc_asawal  = sqlsrv_query($conn, $c_asawal);        
                    $dhc_asawal  = sqlsrv_fetch_array($hc_asawal, SQLSRV_FETCH_ASSOC); 

                    $am4= $dhc_asawal['am4'];
                    ?>
                    <div class="col-4 border border-info">
                        <b>Pemeriksaan Fisik</b> : Kepala : <?php echo $am4; ?>&nbsp;<a href='<?php echo $link.'8';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><br>
                        <?php echo $IDFISIK_KEPALA;?>
                    </div>
                    <div class="col-12">
                        03. Pemeriksaan Psikologi
                    </div> 
                    <div class="col-4 border border-info">
                        <b>Pemeriksaan Psikologi</b> : <?php echo $am4; ?>&nbsp;<a href='<?php echo $link.'9';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><br>
                        <?php echo $IDPSIKOLOGI;?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Diagnosis                       
                    </div>
                    <div class="col-12 border border-info">
                        Primary : <?php echo $resume20; ?>&nbsp;<a href='<?php echo $link.'10';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDDIAGNOSA_PRIMARY;?><br>
                        Secondary : <?php echo $resume21; ?>&nbsp;<a href='<?php echo $link.'11';?>' target='' class='btn btn-warning btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDDIAGNOSA_SECONDARY;?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Tindakan                       
                    </div>
                    <div class="col-12 border border-info">
                        <?php $tindakan = $resume25.$resume29.$resume31.$resume33;?>
                        Procedure : <?php echo $tindakan; ?>&nbsp;<a href='<?php echo $link.'12';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDTINDAKAN;?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Tata Laksana (Edukasi)                       
                    </div>
                    <div class="col-12 border border-info">
                        <?php $edukasi = '';?>
                        Edukasi : <?php echo $edukasi; ?>&nbsp;<a href='<?php echo $link.'13';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDEDUKASI;?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Prognosis                      
                    </div>
                    <div class="col-12 border border-info">
                        Prognosis : <?php echo $resume35; ?>&nbsp;<a href='<?php echo $link.'14';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDPROGNOSIS;?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Rencana Tindak Lanjut                      
                    </div>
                    <div class="col-12 border border-info">
                        Rujukan Keluar Faskes : &nbsp;<a href='#' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRUJUKAN_FASKES;?>
                        <br>
                        Kontrol 1 Minggu : &nbsp;<a href='<?php echo $link.'16';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDKONTROL_MINGGU;?>                        
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Kondisi saat Meninggalkan RS                      
                    </div>
                    <div class="col-12 border border-info">
                        <?php echo $resume36; ?>&nbsp;<a href='<?php echo $link.'17';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDKONDISI_KELUAR;?>
                    </div>
                </div>

                <hr>

                <div class="row">
                    Fase Kedua
                </div>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Rencana Rawat Pasien                      
                    </div>
                    <div class="col-12 border border-info">
                        <?php 
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

                        ?>
                        <?php echo substr($rencana_rawat,0,50).'...'; ?>
                        &nbsp;<a href='<?php echo $link.'18';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRAWAT_PASIEN;?>                        
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Instruksi Medik dan Keperawatan                      
                    </div>
                    <div class="col-12 border border-info">
                        <?php 
                        $qe="SELECT        TOP (1) instruksi
                        FROM            ERM_SOAP
                        WHERE        (noreg = '$noreg')
                        ORDER BY id DESC";
                        $he  = sqlsrv_query($conn, $qe);        
                        $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
                        $instruksi = $de['instruksi'];
                        ?>
                        <?php echo substr($instruksi,0,50).'...'; ?>
                        &nbsp;<a href='<?php echo $link.'19';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDINSTRUKSI_MEDIK;?>                        
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Pemeriksaan Penunjang                      
                    </div>

                    <div class="col-6 border border-info">
                        Laborat<br>
                        Service Request&nbsp;<a href='<?php echo $link.'20';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDLABSERVICE_REQ;?> 
                        <br>
                        Specimen&nbsp;<a href='<?php echo $link.'21';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDLABSPECIMEN;?> 
                        <br>
                        Observation&nbsp;<a href='<?php echo $link.'22';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDLABOBSERVATION;?> 
                        <br>
                        DiagnosicReport&nbsp;<a href='<?php echo $link.'23';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDLABDIAGNOSIC;?> 
                        <br>
                    </div>

                    <div class="col-6 border border-info">
                        Radiologi<br>
                        Status Kehamilan Pasien&nbsp;<a href='<?php echo $link.'24';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRAD_STATUSKEHAMILAN;?>
                        <br>
                        Service Request&nbsp;<a href='<?php echo $link.'25';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRAD_SERVICEREQ;?>
                        <br>
                        Observation&nbsp;<a href='<?php echo $link.'26';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRAD_OBSERVATION;?>
                        <br>
                        DiagnosicReport&nbsp;<a href='<?php echo $link.'27';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRAD_DIAGNOSIC;?>
                        <br>

                    </div>

                </div>
                <br>
                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Peresepan Obat                     
                    </div>

                    <div class="col-12 border border-info">
                        Medication&nbsp;<a href='<?php echo $link.'28';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDPERESEPAN_MEDICATION;?>
                        <br>
                        Medication Request&nbsp;<a href='<?php echo $link.'29';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDPERESEPAN_MEDICATIONREQUEST;?>
                        <br>

                    </div>

                </div>
                <br>

                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Pengkajian Resep                     
                    </div>

                    <div class="col-12 border border-info">
                        QuestionnaireResponse&nbsp;<a href='<?php echo $link.'30';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDQuestionnaireResponse;?>

                    </div>

                </div>
                <br>

                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Pengeluaran Obat                     
                    </div>

                    <div class="col-12 border border-info">
                        Medication&nbsp;<a href='<?php echo $link.'31';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDPENGELUARANOBAT_MEDICATION;?>
                        <br>
                        Medication Request&nbsp;<a href='<?php echo $link.'32';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDPENGELUARANOBAT_MEDICATIONDISPENCE;?>
                        <br>

                    </div>

                </div>
                <br>

                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Perencanaan Pemulangan Pasien                
                    </div>

                    <div class="col-12 border border-info">
                        Observation&nbsp;<a href='<?php echo $link.'33';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRENCANAPULANG_OBSERVATION;?>
                        <br>
                        Careplan&nbsp;:
                        <?php echo substr($resume39,0,50).'...'; ?>
                        <a href='<?php echo $link.'34';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDRENCANAPULANG_CAREPLAN;?>
                    </div>

                </div>
                <br>

                <div class="row">
                    <div class="col-12 bg-secondary text-white">
                        Lain2                
                    </div>
                    <div class="col-12 border border-info">
                        Diet&nbsp;:
                        <?php 
                        $qu="SELECT diet  FROM ERM_DIET where noreg='$noreg'";
                        $h1u  = sqlsrv_query($conn, $qu);        
                        $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
                        $diet = $d1u['diet'];
                        ?>
                        <a href='<?php echo $link.'35';?>' target='' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-right"></i> Kirim</a><?php echo $IDDIET;?>
                    </div>
                </div>



            </form>
        </body>
    </div>
</div>
