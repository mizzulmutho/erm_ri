<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = strtoupper($row[1]); 
$jenis = $row[2]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qur="SELECT noreg FROM ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
$h1ur  = sqlsrv_query($conn, $qur);        
$d1ur  = sqlsrv_fetch_array($h1ur, SQLSRV_FETCH_ASSOC); 
$ceknoreg = trim($d1ur['noreg']);

if(!empty($ceknoreg)){
	echo "
	<script>
	window.location.replace('resume_print.php?id=$id|$user');
	</script>
	";
}


$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);

if ($KET1 == 'RSPG'){
	$nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
	$alamat = "Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik";
};
if ($KET1 == 'GRAHU'){
	$nmrs = "RUMAH SAKIT GRHA HUSADA";
	$alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
};
if ($KET1 == 'DRIYO'){
	$nmrs = "RUMAH SAKIT DRIYOREJO";
	$alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
};

$q2       = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan,NOKTP,NOBPJS, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);                

$data2    = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);                      
$kodedept = $data2[kodedept];

$nama     = $data2[nama];
$kelamin  = $data2[kelamin];
$nik = trim($data2[nik]);
$alamatpasien  = $data2[alamatpasien];
$kota     = $data2[kota];
$kodekel  = $data2[kodekel];
$telp     = $data2[tlp];
$tmptlahir     = $data2[tmptlahir];
$tgllahir = $data2[tgllahir];
$jenispekerjaan     = $data2[jenispekerjaan];
$jabatan  = $data2[jabatan];
$umur =  $data2[UMUR];
$noktp =  $data2[NOKTP];
$nobpjs =  $data2[NOBPJS];

//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar,CONVERT(VARCHAR, tglmasuk, 20) as tglmasuk2
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];
$tglmasuk2 = $data3[tglmasuk2];

//kamar
$qc2="SELECT KAMAR FROM ARM_PERIKSA WHERE (NOREG = '$noreg') and kamar <> ''";
$hdc2  = sqlsrv_query($conn, $qc2);        
$dhdc2 = sqlsrv_fetch_array($hdc2, SQLSRV_FETCH_ASSOC);         
$kamar = $dhdc2[KAMAR];

//lama perawatan
$q4       = "
SELECT DATEDIFF(day, '$tglmasuk2', '$tglinput') AS 'Duration'  
FROM dbo.ARM_PERIKSA 
where noreg='$noreg' and tglkeluar is not null";
$hasil4  = sqlsrv_query($conn, $q4);  
$data4    = sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC);                      
$lamaperawatan = $data4[Duration];


//lab 17
// $qlab="SELECT lab,rad FROM ERM_RI_PENUNJANG where noreg='$noreg'";
// $hqlab  = sqlsrv_query($conn, $qlab);        
// $dhqlab  = sqlsrv_fetch_array($hqlab, SQLSRV_FETCH_ASSOC); 
// $lab = $dhqlab['lab'];
// $rad = $dhqlab['rad'];


//laborat
$qlab="
SELECT 
CONVERT(VARCHAR, REG_DATE, 103) as REG_DATE,KEL_PEMERIKSAAN,PARAMETER_NAME,HASIL,SATUAN,NILAI_RUJUKAN,FLAG
FROM        LINKYAN5.SHARELIS.dbo.hasilLIS
WHERE        (NOLAB_RS = '$noreg') order by REG_DATE desc, KEL_PEMERIKSAAN,PARAMETER_NAME
";
$hqlab  = sqlsrv_query($conn, $qlab);

$labh = "no | tgl          | pemeriksaan | hasil | nilai normal";
$labh2 = "====================================";

$i=1;
while   ($dhqlab = sqlsrv_fetch_array($hqlab, SQLSRV_FETCH_ASSOC)){     
    // $lab0 = " | no | tgl | pemeriksaan | hasil | nilai normal | ";
	$lab0 = $i.'|'.$dhqlab[REG_DATE].'|'.$dhqlab[KEL_PEMERIKSAAN].'-'.trim($dhqlab[PARAMETER_NAME]).' : '.    $dhqlab[HASIL].' | '.trim($dhqlab[NILAI_RUJUKAN]).' ('.trim($dhqlab[FLAG]).')';

	if($i==1){
		$lab = $labh.'&#13;&#10;'.$labh2.'&#13;&#10;'.$lab0;        
	}else{
		$lab = $lab.'&#13;&#10;'.$lab0;                
	}

	$i=$i+1;
}

$qrad="
SELECT HASILRAD_PEMERIKSAAN_RAD.HASIL, HASILRAD_PEMERIKSAAN_RAD.URAIAN, 
CONVERT(VARCHAR, HASILRAD_PEMERIKSAAN_RAD.TANGGAL, 103) as TANGGAL
FROM            ERM_RI_ASSESMEN_AWAL_DEWASA INNER JOIN
HASILRAD_PEMERIKSAAN_RAD ON ERM_RI_ASSESMEN_AWAL_DEWASA.noreg = HASILRAD_PEMERIKSAAN_RAD.NOREG
where HASILRAD_PEMERIKSAAN_RAD.noreg='$noreg'
ORDER BY ERM_RI_ASSESMEN_AWAL_DEWASA.noreg, HASILRAD_PEMERIKSAAN_RAD.TANGGAL
";
$hqrad  = sqlsrv_query($conn, $qrad);

$i=1;
while   ($dhqrad = sqlsrv_fetch_array($hqrad, SQLSRV_FETCH_ASSOC)){     
	$rad0 = $dhqrad[TANGGAL].'-'.$dhqrad[HASIL].':'.$dhqrad[URAIAN];
	$rad = $rad.'&#13;&#10;'.$rad0;
}




$qi="SELECT noreg FROM ERM_RI_RESUME where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_RESUME(noreg,userid,tglentry) values ('$noreg','$user','$tglinput')";
	$hs = sqlsrv_query($conn,$q);

	$q  = "insert into ERM_RI_RESUME_PERAWAT(noreg,userid,tglentry) values ('$noreg','$user','$tglinput')";
	$hs = sqlsrv_query($conn,$q);

}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume
	FROM ERM_RI_RESUME
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tglresume = $de['tglresume'];
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


	//select dari assesmen_medis
	$qe2="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ANAMNESIS_MEDIS
	where noreg='$noreg'";
	$he2  = sqlsrv_query($conn, $qe2);        
	$de2  = sqlsrv_fetch_array($he2, SQLSRV_FETCH_ASSOC); 

	$am1 = $de2['am1'];
	$am2= $de2['am2'];
	$am3= $de2['am3'];
	$am4= $de2['am4'];
	$am5= $de2['am5'];
	$am6= $de2['am6'];
	$am7= $de2['am7'];
	$am8= $de2['am8'];
	$am9= $de2['am9'];
	$am10= $de2['am10'];
	$am11= $de2['am11'];
	$am12= $de2['am12'];
	$am13= $de2['am13'];
	$am14= $de2['am14'];
	$am15= $de2['am15'];
	$am16= $de2['am16'];
	$am17= $de2['am17'];
	$am18= $de2['am18'];
	$am19= $de2['am19'];
	$am20= $de2['am20'];
	$am21= $de2['am21'];
	$am22= $de2['am22'];
	$am23= $de2['am23'];
	$am24= $de2['am24'];
	$am25= $de2['am25'];
	$am26= $de2['am26'];
	$am27= $de2['am27'];
	$am28= $de2['am28'];
	$am29= $de2['am29'];
	$am30= $de2['am30'];
	$am31= $de2['am31'];
	$am32= $de2['am32'];
	$am33= $de2['am33'];
	$am34= $de2['am34'];
	$am35= $de2['am35'];
	$am36= $de2['am36'];
	$am37= $de2['am37'];
	$am38= $de2['am38'];
	$am39= $de2['am39'];
	$am40= $de2['am40'];
	$am41= $de2['am41'];
	$am42= $de2['am42'];
	$am43= $de2['am43'];
	$am44= $de2['am44'];
	$am45= $de2['am45'];
	$am46= $de2['am46'];
	$am47= $de2['am47'];
	$am48= $de2['am48'];
	$am49= $de2['am49'];
	$am50= $de2['am50'];
	$am51= $de2['am51'];
	$am52= $de2['am52'];
	$am53= $de2['am53'];
	$am54= $de2['am54'];
	$am55= $de2['am55'];
	$am56= $de2['am56'];
	$am57= $de2['am57'];
	$am58= $de2['am58'];
	$am59= $de2['am59'];
	$am60= $de2['am60'];
	$am61= $de2['am61'];
	$am62= $de2['am62'];
	$am63= $de2['am63'];
	$am64= $de2['am64'];
	$am65= $de2['am65'];
	$am66= $de2['am66'];
	$am67= $de2['am67'];
	$am68= $de2['am68'];
	$am69= $de2['am69'];
	$am70= $de2['am70'];
	$am71= $de2['am71'];
	$am72= $de2['am72'];
	$am73= $de2['am73'];
	$am74= $de2['am74'];
	$am75= $de2['am75'];
	$am76= $de2['am76'];
	$am77= $de2['am77'];
	$am78= $de2['am78'];
	$am79= $de2['am79'];
	$am80= $de2['am80'];

}

// if (isset($_POST["Print"])) {
// 	echo "
// 	<script>
// 	top.location='resume_print.php?id=$id|$user|$idrasuhan','_blank';
// 	</script>
// 	";    
// }

if (isset($_POST["Pdf"])) {

	echo "
	<script>
	downloadPDF();
	</script>
	";

}


if($jenis=='add_diagnosa_sekunder'){
	$q  = "insert into  ERM_RI_DIAGNOSA_SEKUNDER(noreg) values ('$noreg')";
	$hs = sqlsrv_query($conn,$q);

	echo "
	<script>
	window.location.replace('resume.php?id=$id|$user');
	</script>
	";
}



?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Resume Medis</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

	<!-- Jqueri autocomplete untuk procedure !!! -->
	<link rel="stylesheet" href="jquery-ui.css">
	<script src="jquery-1.10.2.js"></script>
	<script src="jquery-ui.js"></script>

	<!-- Tanda Tangan -->
	<script type="text/javascript" src="js/jquery.signature.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.signature.css">
	<style>
		.kbw-signature {
			width: 300px;
			height: 300px;
		}
		#sig canvas {
			width: 100% !important;
			height: auto;
		}
	</style>

	<style type="text/css">
		@media print{
			body {display:none;}
		}
	</style>
	
	<script>
		$(function() {
			$("#apoteker").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_apoteker.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.kodedokter + ' - ' + item.nama + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  


	<script>
		$(function() {
			$("#obat1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  

	<script>
		$(function() {
			$("#obat2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat4").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat5").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat6").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat7").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat8").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat9").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat10").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat11").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat12").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat13").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat14").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat15").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat16").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat17").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat18").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat19").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#obat20").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

	<script>
		$(function() {
			$("#dokter").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.kodedokter + ' - ' + item.nama + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  


	<script>
		$(function() {
			$("#karyawan1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_karyawan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nik + ' - ' + item.nama
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  

	<script>
		$(function() {
			$("#karyawan2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_karyawan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nik + ' - ' + item.nama
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  

	<script>
		$(function() {
			$("#karyawan3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_karyawan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nik + ' - ' + item.nama
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  


	<script>
		$(function() {
			$("#icd101").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd10.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd102").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd10.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd103").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd10.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd104").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd10.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd91").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd92").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd93").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd94").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd95").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd96").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd97").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	<script>
		$(function() {
			$("#icd98").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd9.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 

</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='resume.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<button type='submit' name='update_perawat' value='update_perawat' class="btn btn-info" type="button"><i class="bi bi-transparency"></i> Update</button>

					&nbsp;&nbsp;

					<!-- <a href='#' class='btn btn-danger'>Pdf</a> -->
					<!-- <input type='submit' name='Pdf' value='Pdf' class='btn btn-danger'> -->
					&nbsp;&nbsp;
					<!-- <a href='resume_print.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<!-- <input type='submit' name='Print' value='Print' class='btn btn-info'> -->

					&nbsp;&nbsp;
					<br>
					Keterangan : Tekan <b>update</b> apabila data resume perawat/bidan/gizi/apoteker <b>tidak muncul</b> setelah dokter mengisi resume
					<br>
					<br>
<!-- 				<div class="row">
					<div class="col-12 text-center bg-success text-white"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
				</div>
			-->				
			<div class="row">
			</div>

			<div class="row">
				<div class="col-6">
					<h5><b><?php echo $nmrs; ?></b></h5>
					<?php echo $alamat; ?>
				</div>
				<div class="col-6">
					<?php echo 'NIK : '.$noktp.'<br>'; ?>					
					<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
					<?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-12 text-center">
					<b>FORM INPUT RESUME MEDIS RINGKASAN PASIEN PULANG RAWAT INAP</b><br>
					INPATIENT DISCHARGE SUMMARY (MEDICAL RESUME)
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-3">
					Ruang Perawatan
				</div>
				<div class="col-3">
					<?php 
					if(empty($resume1)){
						$resume1 = 'Kamar : '.$kamar;
					}else{
						$resume1 = $resume1;
					}

					?>
					: <input type='text' name='resume1' value='<?php echo $resume1; ?>'>
				</div>
				<div class="col-3">
					Tgl. MRS
				</div>
				<div class="col-3">
					<?php 
					if(empty($resume2)){
						$resume2 = $tglmasuk;
					}
					?>
					: <input type='text' name='resume2' value='<?php echo $resume2; ?>'>
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					<!-- Jumlah Hari Perawatan -->
				</div>
				<div class="col-3">
					<?php 
					if(empty($resume3)){
						$resume3 = $lamaperawatan.' hari';
					}else{
						$resume3 = $resume3;
					}

					?>

					<!-- : <input type='text' name='resume3' value='<?php echo $resume3; ?>'> -->
				</div>
				<div class="col-3">
					Tgl. KRS
				</div>
				<div class="col-3">
					<?php 
					if(empty($resume4)){
						$resume4 = $tglkeluar;
						if($tglkeluar=='01/01/1900'){
							$resume4='';
						}
					}
					?>

					: <input type='text' name='resume4' value='<?php echo $resume4; ?>'>
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					<!-- No. SEP -->
				</div>
				<div class="col-3">
					<!-- : <input type='text' name='resume5' value='<?php echo $resume5; ?>'> -->
				</div>
				<div class="col-3">
					Tgl. Meninggal
				</div>
				<div class="col-3">
					: <input type='text' name='resume6' value='<?php echo $resume6; ?>'>
				</div>
			</div>

<!-- 			<div class="row">
				<div class="col-3">
					No. Asuransi
				</div>
				<div class="col-3">
					<?php 
					if(empty($resume7)){
						$resume7 = $nobpjs;
					}
					?>
					: <input type='text' name='resume7' value='<?php echo $resume7; ?>'>
				</div>
				<div class="col-3">
					&nbsp;
				</div>
				<div class="col-3">
					&nbsp;
				</div>
			</div>			
 --><!-- 			<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
			<br>
		-->			<br>

		<div class="row">
			<div class="col-12 bg-success text-white">
				<b>Diisi Oleh Dokter</b><br>
			</div>
		</div>

		<div class="row">
			<div class="col-4">
				<b>1. Anamnese</b>
			</div>
			<div class="col-8">

			</div>
			<div class="col-4">
				&bull; Keluhan Utama
			</div>
			<div class="col-8">
				<?php 
				if(empty($resume8)){
					$resume8 = $am1;
				}else{
					$resume8 = $resume8;
				}
				?>
				: <input type='text' name='resume8' value='<?php echo $resume8; ?>' size='80'>
			</div>
			<div class="col-4">
				&bull; Lama Keluhan
			</div>
			<div class="col-8">
				<?php 
				if(empty($resume9)){
					$resume9 = $am78;
				}else{
					$resume9 = $resume9;
				}
				?>
				: <input type='text' name='resume9' value='<?php echo $resume9; ?>' size='80'>
			</div>
			<div class="col-4">
				&bull; Keluhan Lain
			</div>
			<div class="col-8">
				<?php 
				if(empty($resume10)){
					$resume10 = $am79;
				}else{
					$resume10 = $resume10;
				}
				?>
				: <input type='text' name='resume10' value='<?php echo $resume10; ?>' size='80'>
			</div>
		</div>
		<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
		<hr> 

		<div class="row">
			<div class="col-4">
				<b>2. Riwayat Penyakit Dahulu</b>
			</div>
			<div class="col-8">

			</div>
			<div class="col-4">
				&bull; Nama Penyakit
			</div>
			<div class="col-8">
				<?php 
				if(empty($resume11)){
					$resume11 = $am2;
				}else{
					$resume11 = $resume11;
				}
				?>
				: <input type='text' name='resume11' value='<?php echo $resume11; ?>' size='80'>
			</div>
			<div class="col-4">
				&bull; Lama Penyakit
			</div>
			<div class="col-8">
				: <input type='text' name='resume12' value='<?php echo $resume12; ?>' size='80'>
			</div>
			<div class="col-4">
				&bull; Riwayat Keluarga
			</div>
			<div class="col-8">
				: <input type='text' name='resume13' value='<?php echo $resume13; ?>' size='80'>
			</div>
			<div class="col-4">
				&bull; Riwayat Alergi
			</div>
			<div class="col-8">
				<?php 
				if(empty($resume14)){
					$resume14 = $am3;
				}else{
					$resume14 = $resume14;
				}
				?>
				: <input type='text' name='resume14' value='<?php echo $resume14; ?>' size='80'>
			</div>
			<div class="col-4">
				&bull; Riwayat Pengobatan
			</div>
			<div class="col-8">
				<?php 
				if(empty($resume15)){
					$resume15 = $am4;
				}else{
					$resume15 = $resume15;
				}
				?>
				: <input type='text' name='resume15' value='<?php echo $resume15; ?>' size='80'>
			</div>
		</div>
		<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
		<hr> 


		<div class="row">
			<div class="col-4">
				<b>3. Pemeriksaan Saat MRS</b>
			</div>
			<div class="col-8">

			</div>
			<div class="col-4">
				&bull; Fisik <br>
				&nbsp;&nbsp; 
				<a href='anamnesis_medis.php?id=<?php echo $id.'|'.$user;?>' target='_blank' class='btn btn-warning'><i class="bi bi-x-circle"></i> Edit Pemeriksaan Fisik</a>
			</div>
			<div class="col-8">
				<?php 

				$total_gcs=$am6+$am7+$am8;
				$row2 = explode('/',$am9);
				$sistole  = $row2[0];
				$diastole = $row2[1]; 

				$periksa_fisik="
				<table border='0'>
				<tr>
				<td colspan='2'>Keadaan umum</td>
				</tr>
				<tr>
				<td colspan='2'>1. Tingkat kesadaran : $am5</td>
				</tr>
				<tr>
				<td colspan='2'>2. Vital sign: GCS : $am6 - $am7 - $am8 ($total_gcs)</td>
				</tr>
				<tr>
				<td>Tekanan darah: $sistole / $diastole mmHg</td><td>|</td><td>Nadi: $am10 x/menit - $am11</td>
				</tr>
				<tr>
				<td>Suhu tubuh:$am12 C</td><td>|</td><td>Frekuensi Pernafasan:$am13</td>
				</tr>
				<tr>
				<td>Skala Nyeri:$am14</td><td>|</td><td>Berat Badan:$am15</td>
				</tr>
				</table>
				Pemeriksaan untuk mengetahui kondisi fisik di tubuh pasien : <br>";

				if(trim($am19)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Kepala : ".$am19.",ket : ".$am20."<br>";
				}
				if(trim($am21)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Mata : ".$am21.",ket : ".$am22."<br>";
				}
				if(trim($am23)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Telinga : ".$am23.",ket : ".$am24."<br>";
				}
				if(trim($am25)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Hidung : ".$am25.",ket : ".$am26."<br>";
				}
				if(trim($am27)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Rambut : ".$am27.",ket : ".$am28."<br>";
				}
				if(trim($am29)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Bibir : ".$am29.",ket : ".$am30."<br>";
				}
				if(trim($am31)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Gigi Gligi : ".$am31.",ket : ".$am32."<br>";
				}
				if(trim($am33)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Lidah : ".$am33.",ket : ".$am34."<br>";
				}
				if(trim($am35)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Langit-langit : ".$am35.",ket : ".$am36."<br>";
				}
				if(trim($am37)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Leher : ".$am37.",ket : ".$am38."<br>";
				}
				if(trim($am39)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Tenggorokan : ".$am39.",ket : ".$am40."<br>";
				}
				if(trim($am41)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Tonsil : ".$am41.",ket : ".$am42."<br>";
				}
				if(trim($am43)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Dada : ".$am43.",ket : ".$am44."<br>";
				}
				if(trim($am45)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Payudara : ".$am45.",ket : ".$am46."<br>";
				}
				if(trim($am47)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Punggung : ".$am47.",ket : ".$am48."<br>";
				}
				if(trim($am49)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Perut : ".$am49.",ket : ".$am50."<br>";
				}
				if(trim($am51)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Genital : ".$am51.",ket : ".$am52."<br>";
				}
				if(trim($am53)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Anus/Dubur : ".$am53.",ket : ".$am54."<br>";
				}
				if(trim($am55)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Lengan Atas : ".$am55.",ket : ".$am56."<br>";
				}
				if(trim($am57)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Lengan Bawah : ".$am57.",ket : ".$am58."<br>";
				}
				if(trim($am59)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Jari Tangan : ".$am59.",ket : ".$am60."<br>";
				}
				if(trim($am61)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Kuku Tangan : ".$am61.",ket : ".$am62."<br>";
				}
				if(trim($am63)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Persendian Tangan : ".$am63.",ket : ".$am64."<br>";
				}
				if(trim($am65)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Tungkai Atas : ".$am65.",ket : ".$am66."<br>";
				}
				if(trim($am67)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Tungkai Bawah : ".$am67.",ket : ".$am68."<br>";
				}
				if(trim($am69)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Jari Kaki : ".$am69.",ket : ".$am70."<br>";
				}
				if(trim($am71)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Kuku Kaki : ".$am71.",ket : ".$am72."<br>";
				}
				if(trim($am73)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Persendian Kaki : ".$am73.",ket : ".$am74."<br>";
				}
				if(trim($am16)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Status psikologis : ".$am16."<br>";
				}
				if(trim($am17)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Sosial Ekonomi : ".$am17."<br>";
				}
				if(trim($am18)=="Abnormal"){
					$periksa_fisik = $periksa_fisik."Spiritual : ".$am18;
				}

				echo ($periksa_fisik);
				?>
				<br>
			</div>
			<div class="col-4">
				&bull; Pemeriksaan Fisik Lain 
			</div>
			<div class="col-8">
				<textarea name= "resume16" id="fisik" style="min-width:630px; min-height:50px;"><?php echo $resume16;?></textarea>
			</div>
			<div class="col-4">
				&bull; Laboratorium 
			</div>
			<div class="col-8">
				<textarea name= "resume17" id="fisik" style="min-width:630px; min-height:150px;"><?php echo $resume17;?></textarea>
			</div>
			<div class="col-4">
				&bull; Radiologi
			</div>
			<div class="col-8">				 
				<!-- <input type='text' name='resume18' value='<?php echo $resume18; ?>' size='80'> -->
				<?php 
				if(empty($resume18)){
					$resume18=$rad;
				}
				?>
				<textarea name= "resume18" id="fisik" style="min-width:630px; min-height:150px;"><?php echo $resume18;?></textarea>
			</div>
			<div class="col-4">
				&bull; Lain-lain
			</div>
			<div class="col-8">
				: <input type='text' name='resume19' value='<?php echo $resume19; ?>' size='80'>
			</div>
		</div>
		<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
		<hr>

		<div class="row">				

			<div class="col-4">
				<b>4. Diagnosis</b>
			</div>
			<div class="col-8">

			</div>
			<div class="col-4">
				&bull; Diagnosis Awal / Masuk
			</div>
			<div class="col-8">
				: 
				<!-- <input type='text' name='diagnosis_awal' value='<?php echo $diagnosis_awal; ?>' size='80'> -->

<!-- 					<select id="resume20" name="resume20">
						<option value=""></option>
						<option value="R68.83">R68.83 - chills without feve</option>
						<option value="R56.0">R56.0 - febrile convulsions</option>
						<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
						<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
						<option value="R68.0">R68.0 - hypothermia due to illness</option>
						<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
						<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
					</select>
				-->
				<input class="" name="resume20" value="<?php echo $resume20;?>" id="icd101" type="text" size='80' onfocus="nextfield ='';" placeholder="Isikan ICD10">

			</div>

			<div class="col-4">
				&bull; Diagnosis Akhir (Primer)
			</div>
			<div class="col-8">
				: 
				<!-- <input type='text' name='diagnosis_akhir_primer' value='<?php echo $diagnosis_akhir_primer; ?>' size='80'> -->
<!-- 				<select id="resume21" name="resume21">
					<option value=""></option>
					<option value="R68.83">R68.83 - chills without feve</option>
					<option value="R56.0">R56.0 - febrile convulsions</option>
					<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
					<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
					<option value="R68.0">R68.0 - hypothermia due to illness</option>
					<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
					<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
				</select>
			-->				
			<input class="" name="resume21" value="<?php echo $resume21;?>" id="icd102" type="text" size='80' onfocus="nextfield ='';" placeholder="Isikan ICD10">
		</div>
		<div class="col-4">
			&bull; Diagnosis Akhir (Sekunder)
		</div>
		<div class="col-8">
			: 
			<!-- <input type='text' name='diagnosis_akhir_sekunder' value='<?php echo $diagnosis_akhir_sekunder; ?>' size='80'> -->
<!-- 			<select id="resume22" name="resume22">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume22" value="<?php echo $resume22;?>" id="icd103" type="text" size='80' onfocus="nextfield ='periode';" placeholder="Isikan ICD10">
			<a href='diagnosis_sekunder.php?id=<?php echo $id.'|'.$user;?>'>(+)</a><br>
			<?php
			$qds="SELECT * FROM  ERM_RI_DIAGNOSA_SEKUNDER where noreg='$noreg'";
			$hqds  = sqlsrv_query($conn, $qds);        
			$dhqds  = sqlsrv_fetch_array($hqds, SQLSRV_FETCH_ASSOC); 			
			$diagnosa1  = $dhqds['diagnosa_sekunder1'];
			$diagnosa2  = $dhqds['diagnosa_sekunder2'];
			$diagnosa3  = $dhqds['diagnosa_sekunder3'];
			$diagnosa4   = $dhqds['diagnosa_sekunder4'];
			$diagnosa5   = $dhqds['diagnosa_sekunder5'];
			$diagnosa6   = $dhqds['diagnosa_sekunder6'];
			$diagnosa7   = $dhqds['diagnosa_sekunder7'];
			$diagnosa8   = $dhqds['diagnosa_sekunder8'];
			$diagnosa9   = $dhqds['diagnosa_sekunder9'];
			$diagnosa10   = $dhqds['diagnosa_sekunder10'];
			if($diagnosa1){
				echo $diagnosa1;echo "<br>";
			}
			if($diagnosa2){
				echo $diagnosa2;echo "<br>";
			}
			if($diagnosa3){
				echo $diagnosa3;echo "<br>";
			}
			if($diagnosa4){
				echo $diagnosa4;echo "<br>";
			}
			if($diagnosa5){
				echo $diagnosa5;echo "<br>";
			}
			if($diagnosa6){
				echo $diagnosa6;echo "<br>";
			}
			if($diagnosa7){
				echo $diagnosa7;echo "<br>";
			}
			if($diagnosa8){
				echo $diagnosa8;echo "<br>";
			}
			if($diagnosa9){
				echo $diagnosa9;echo "<br>";
			}
			if($diagnosa10){
				echo $diagnosa10;echo "<br>";
			}

			?>
		</div>
	</div>
	<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->

	<hr> 
	<div class="row">
		<div class="col-4">
			<b>5. Masalah Utama</b>
		</div>
		<div class="col-8">
			: <input type='text' name='resume23' value='<?php echo $resume23; ?>' size='80'>
		</div>
	</div>
	<hr> 
<!-- 	<div class="row">
		<div class="col-4">
			<b>6. Konsultasi</b>
		</div>
		<div class="col-8">
			: 
			<?php 
			if(empty($resume24)){
				$resume24="Konsultasi 1 : \n\nKonsultasi 2 : \n\nKonsultasi 3 : \n\nKonsultasi 4 : \n\nKonsultasi 5 : ";				
			}
			?>
			<textarea name= "resume24" id="konsultasi" style="min-width:630px; min-height:150px;"><?php echo $resume24;?></textarea>
		</div>
	</div>
	<hr>  -->
	<div class="row">
		<div class="col-4">
			<b>6. Pengobatan/Tindakan selama di RS</b>
		</div>
		<div class="col-8">
			<!-- : <input type='text' name='pengobatan' value='<?php echo $pengobatan; ?>' size='80'> -->
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			<b>&nbsp;&nbsp;&nbsp;Tindakan (ICD-9CM)</b>
		</div>
		<div class="col-8">					 
			: 
			<!-- <select id="resume25" name="resume25">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume25" value="<?php echo $resume25;?>" id="icd91" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<!-- <select id="resume26" name="resume26">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume26" value="<?php echo $resume26;?>" id="icd92" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<br>
			: 
			<!-- <select id="resume27" name="resume27">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume27" value="<?php echo $resume27;?>" id="icd93" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<!-- <select id="resume28" name="resume28">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume28" value="<?php echo $resume28;?>" id="icd94" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<br>
			: 
			<!-- <select id="resume29" name="resume29">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume29" value="<?php echo $resume29;?>" id="icd95" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<!-- <select id="resume30" name="resume30">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume30" value="<?php echo $resume30;?>" id="icd96" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<br>
			: 
			<!-- <select id="resume31" name="resume31">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume31" value="<?php echo $resume31;?>" id="icd97" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<!-- <select id="resume32" name="resume32">
				<option value=""></option>
				<option value="R68.83">R68.83 - chills without feve</option>
				<option value="R56.0">R56.0 - febrile convulsions</option>
				<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
				<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
				<option value="R68.0">R68.0 - hypothermia due to illness</option>
				<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
				<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
			</select> -->
			<input class="" name="resume32" value="<?php echo $resume32;?>" id="icd98" type="text" size='30' onfocus="nextfield ='periode';" placeholder="Isikan ICD9">
			<br>
			: 
			<!-- <input type='text' name='resume33' value='<?php echo $resume33; ?>' size='80' placeholder="ICD Free Text" style="min-width:630px; min-height:150px;"> -->
			<textarea name= "resume33" id="" style="min-width:630px; min-height:150px;"><?php echo $resume33;?></textarea>
		</div>
	</div>
<!-- 	<div class="row">
		<div class="col-4">
			<b>&nbsp;&nbsp;&nbsp;b. Pengobatan</b>
		</div>
		<div class="col-8">
			: 
			<input type='text' name='resume34' value='<?php echo $resume34; ?>' size='80'>
			<textarea name= "resume34" id="resume34" style="min-width:630px; min-height:150px;" placeholder="Isikan Pengobatan Saat Rawat Inap"><?php echo $resume34;?></textarea>
		</div>
	</div> -->

	<hr> 
	<div class="row">
		<div class="col-4">
			<b>7. Prognosis</b>
		</div>
		<div class="col-8">
			: <input type='text' name='resume35' value='<?php echo $resume35; ?>' size='80'>
		</div>
	</div>
	<hr> 
	<div class="row">
		<div class="col-4">
			<b>8. Keadaan waktu keluar RS (Status pasien)</b>
		</div>
		<div class="col-8">
			: 
			<!-- <input type='text' name='keadaan_keluar_rs' value='<?php echo $keadaan_keluar_rs; ?>' size='80'> -->
			<!-- <select id="resume36" name="resume36">
				<option value=""></option>
				<option value="Sembuh">Sembuh</option>
				<option value="Dirujuk">Dirujuk</option>
				<option value="Atas Permintaan Sendiri">Atas Permintaan Sendiri</option>
			</select> -->
			<input type='text' name='resume36' value='<?php echo $resume36; ?>' size='80'>
		</div>
	</div>
	<hr> 
	<div class="row">
		<div class="col-4">
			<b>9. Sebab meninggal</b>
		</div>
		<div class="col-8">
			: <input type='text' name='resume37' value='<?php echo $resume37; ?>' size='80'>
		</div>
	</div>
	
	<div class="row">
		<div class="col-4 bg-primary text-white">
			Verifikasi Dokter Pemeriksa
		</div>
		<div class="col-8 bg-primary text-white">:
			<input class="" name="resume38" value="<?php echo $resume38;?>" id="dokter" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Dokter atau Kode Dokter">

		</div>
	</div>
	<div class="row">
		<div class="col-4">
			&nbsp;
		</div>
		<div class="col-8">
			<!-- <input type='text' name='pass_dokter' value='' size='10' placeholder="password"> -->
			<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
			<br>
			&nbsp;<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>

		</div>
	</div>

	<hr>
	<div class="row">
		<div class="col-4">
			<b>11. Tindak Lanjut (Diisi Oleh Bidan / Perawat)</b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			a. Aktifitas
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Jenis aktivitas yang boleh dilakukan
		</div>
		<div class="col-8">
			: <input type='text' name='resume39' value='<?php echo $resume39; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull; Prosedur
		</div>
		<div class="col-8">
			: <input type='text' name='resume40' value='<?php echo $resume40; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull; Alat Bantu
		</div>
		<div class="col-8">
			: <input type='text' name='resume41' value='<?php echo $resume41; ?>' size='80'>
		</div>

		<div class="col-4">
			<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
		</div>
		<div class="col-8">
		</div>

		<div class="col-4">
			b. Edukasi Kesehatan
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Pemeriksaan Laboratorium Lanjutan
		</div>
		<div class="col-8">
			: <input type='text' name='resume42' value='<?php echo $resume42; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull; Pencegahan Terhadap ke Kambuhan
		</div>
		<div class="col-8">
			: <input type='text' name='resume43' value='<?php echo $resume43; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull; Jadwal Kontrol
		</div>
		<div class="col-8">
			: <input type='text' name='resume44' value='<?php echo $resume44; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull; Lain nya
		</div>
		<div class="col-8">
			: <input type='text' name='resume45' value='<?php echo $resume45; ?>' size='80'>
		</div>
		<div class="col-4">
			<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
		</div>
		<div class="col-8">
		</div>
		<div class="col-4">
			c. Perawatan Saat dirumah
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Kenali tanda dan gejala yang perlu  dilaporkan
		</div>
		<div class="col-8">
			: <input type='text' name='resume46' value='<?php echo $resume46; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull;  Pengobatan yangdapat dilakukan   dirumah sebelum ke rumah Sakit
		</div>
		<div class="col-8">
			: <input type='text' name='resume47' value='<?php echo $resume47; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull; Dalam keadaan darurat, segera bawa pasien ke IGD/dokter terdekat
		</div>
		<div class="col-8">
			: <input type='text' name='resume48' value='<?php echo $resume48; ?>' size='80'>
		</div>
		<div class="col-4">
			<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
		</div>
		<div class="col-8">
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-4 bg-primary text-white">
			Verifikasi Perawat / Bidan Pemberi Resume
		</div>
		<div class="col-8 bg-primary text-white">
			<input class="" name="resume49" value="<?php echo $resume49;?>" id="karyawan1" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Perawat atau Bidan">

		</div>
	</div>
	<div class="row">
		<div class="col-4">
			&nbsp;
		</div>
		<div class="col-8">
			<!-- <input type='text' name='pass_gizi' value='' size='10' placeholder="password"> -->
			<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
			<br>
			&nbsp;<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-4">
			<b>Diisi Oleh Ahli Gizi</b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			d. Diet
		</div>
		<div class="col-8">

		</div>
		<div class="col-4">
			&bull; Ajuran Pola Makan
		</div>
		<div class="col-8">
			: <input type='text' name='resume50' value='<?php echo $resume50; ?>' size='80'>
		</div>
		<div class="col-4">
			&bull; Batasan makanan
		</div>
		<div class="col-8">
			: <input type='text' name='resume51' value='<?php echo $resume51; ?>' size='80'>
		</div>
		<div class="col-4">
			<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
		</div>
		<div class="col-8">
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-4 bg-primary text-white">
			Verifikasi Ahli Gizi Pemberi Resume
		</div>
		<div class="col-8 bg-primary text-white">			
			<input class="" name="resume52" value="<?php echo $resume52;?>" id="karyawan2" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Perawat atau Bidan">

		</div>
	</div>
	<div class="row">
		<div class="col-4">
			&nbsp;
		</div>
		<div class="col-8">
			<!-- <input type='text' name='pass_gizi' value='' size='10' placeholder="password"> -->
			<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
			<br>
			&nbsp;<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-12">
			<b>Daftar Obat</b>
		</div>
	</div>

	<div class="row">
		<div class="col-4">
			<b>Obat yang diberikan Saat Perawatan</b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-12">
			<?php 
			$q="
			select distinct nama_obat
			from ERM_RI_RPO
			where noreg='$noreg' order by nama_obat asc
			";
			$hasil  = sqlsrv_query($conn, $q);  
			$no=1;
			while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
				echo $no.' - '.$data[nama_obat];echo "<br>";
				$no+=1;
			}

			?>

		</div>
	</div>

	<div class="row">
		<div class="col-4">
			<b>Obat yang dibawa Pulang</b>
		</div>
		<div class="col-8">

		</div>
		<div class="col-12">
			<table border='1'>
				<tr>
					<td>no</td><td>nama obat</td><td>jumlah</td><td>aturan pakai</td><td>instruksi khusus</td>
				</tr>
				<?php 
				// $q="
				// select distinct nama_obat,jumlah,aturan_pakai,instruksi_khusus
				// from ERM_RI_DISCHARGE
				// where noreg='$noreg' order by nama_obat asc
				// ";
				$q="
				SELECT        W_EResep.Noreg, W_EResep_R.Jenis, W_EResep_R.KodeR, convert(varchar(10),W_EResep_R.Jumlah) +'S' as jumlah, W_EResep_R.AturanPakai as aturan_pakai, W_EResep_R.CaraPakai,
				W_EResep_R.WaktuPakai, 
				CASE WHEN W_EResep_Racikan.Nama ='' THEN AFarm_MstObat.NAMABARANG ELSE W_EResep_Racikan.Nama END AS Nama,
				W_EResep.Kategori as instruksi_khusus,W_EResep_R.Keterangan, AFarm_MstObat.NAMABARANG as nama_obat, W_EResep.Id, W_EResep_Racikan.Dosis
				FROM            W_EResep INNER JOIN
				W_EResep_R ON W_EResep.Id = W_EResep_R.IdResep LEFT OUTER JOIN
				W_EResep_Racikan ON W_EResep_R.Id = W_EResep_Racikan.IdR LEFT OUTER JOIN
				AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
				WHERE        (W_EResep.Noreg = '$noreg') AND (W_EResep.Kategori LIKE '%KRS%') and W_EResep_R.DeletedBy is NULL
				union
				SELECT Noreg,'' as Jenis,'' as KodeR, jumlah,aturan_pakai,'' as CaraPakai,'' as WaktuPakai, '' as Nama, instruksi_khusus, '' as Keterangan, nama_obat, Id, '' as Dosis
				FROM ERM_RI_DISCHARGE where noreg='$noreg'
				order by nama_obat
				";
				$hasil  = sqlsrv_query($conn, $q);  
				$no=1;
				while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
					// echo $no.' - '.$data[nama_obat].' '.$data[jumlah].' '.$data[aturan_pakai].' '.$data[instruksi_khusus];echo "<br>";
					
					if($data['nama_obat'] == ''){
						$nama_obat=$data['Nama'];
					}else{
						$nama_obat=$data['nama_obat'];
					}

					echo "
					<tr>
					<td>$no</td>
					<td>$nama_obat</td>
					<td>$data[jumlah]</td>
					<td>$data[aturan_pakai]</td>
					<td>$data[instruksi_khusus]</td>
					</tr>
					";
					$no+=1;
				}

				?>
			</table>
		</div>
	</div>

	<hr>

	<div class="row">
		<div class="col-12">
			<b>Keterangan : <br>
				Jml : Jumlah, P : Pagi, Si : Siang, So : Sore, M : malam
			</b>
		</div>
	</div>
	<br>

	<div class="row">
		<div class="col-4 bg-primary text-white">
			Verifikasi Apoteker Pemberi Resume
		</div>
		<div class="col-8 bg-primary text-white">	
			<input class="" name="resume133" value="<?php echo $resume133;?>" id="apoteker" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Apoteker">
		</div>
	</div>
	<div class="row">
		<div class="col-4">
			&nbsp;
		</div>
		<div class="col-8">
			<input type='text' name='user_sirs_apoteker' value='' size='20' placeholder="user sirs apoteker">			
			<input type='text' name='pass_sirs_apoteker' value='' size='20' placeholder="password sirs apoteker">
			<br>
			&nbsp;<button type='submit' name='simpan_verif_apoteker' value='simpan_verif_apoteker' class="btn btn-warning" type="button" style="height: 40px;width: 250px;"><i class="bi bi-save-fill"></i> simpan verifikasi apoteker</button>
		</div>
	</div>

	<br>

<!-- 	<tr>
		<td colspan="2">
			<div class="row">
				<div class="col-12">
					Upload Gambar : 
					<?php 
					$q		= "select doc as doc from ERM_RI_RESUME where noreg = '$noreg'";
					$h  = sqlsrv_query($conn, $q);			  
					$d  = sqlsrv_fetch_array($h, SQLSRV_FETCH_ASSOC); 
					$doc = trim($d['doc']);
					echo "Gambar terupload : <a href='$doc' target='_blank'>$doc</a>";
					?>
					<input type="file" name="doc" class="form-control" >Ekstensi .jpeg | .jpg
				</div>
			</div>

			<div class="row">
				<div class="col-4">
					&nbsp;
				</div>
				<div class="col-8">
					<input type='submit' name='upload' value='upload' style="color: white;background: #66CDAA;border-color: #66CDAA;">
					<br><br>
				</div>
			</div>

		</td>
	</tr> -->

	<hr>

	<div class="row">
<!-- 		<div class="col-12">
			<table border='0' bordercolor='green'>
				<tr>
					<td>Telah membaca dan mengerti,</td>
				</tr>
				<tr>
					<td>Nama & Tanda Tangan 
						Pasien/keluarga,
					</td>
				</tr>
				<tr>
					<td>
						<input type='text' name='keluarga' value='<?php echo $keluarga; ?>' size='50' placeholder='keluarga'>
					</td>
				</tr>
			</table>			
			<input type='submit' name='simpan' value='Konfirmasi Persetujuan Pasien' style="height: 90px;width: 300px;color: white;background: green">
			<input type='checkbox' name='resume134' value='YA' <?php if ($resume134=="YA"){echo "checked";}?> >
			Check Jika Pasien / Keluarga Bersedia
		</div>
	-->
	<div class="col-12 text-center">
		<br>
		Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri tanda/paraf di kolom kanannya, dan telah memahaminya.
		<br>
		Tanda tangan,
		pasien/wali
		<br>
		<br />
		<?php  
		if($resume134){
			echo " <img src='$resume134' height='200' width='200'>";
			echo "<br><br>";
			echo "<input type='text' name='resume134' value='$resume134' size='50' hidden>";
			// echo $resume134;

		}
		?>
	</div>

</div>


<div class="row">
	<div class="col-12">
		<tr>
			<td>
				<div class="row">
					<div class="col-4">
						Tanda Tangan : 
						<select name='j_ttd'>
							<option value=''>-- Pilih Jenis TTD --</option>
							<option value='Pasien' selected>Pasien/Wali</option>
							<option value='Saksi'>Saksi</option>
						</select>
					</div>
					<div class="col-8">
						<div id="sig"></div>
						<br />
						<button id="clear" class="btn btn-warning mt-1">Hapus Tanda Tangan</button>
						<textarea id="signature64" name="signed" style="display: none" class="input-group mb-3"></textarea>
						<br />
						<button class="btn btn-success mt-1" id="" value="ttd" name="ttd">Simpan TTD</button>
						<br>
					</div>
				</div>
			</td>
		</tr>
	</div>
</div>


		<!-- 	<div class="row">
		<div class="col-12">
		Rangkap 4<br>
		Asli 		: Status RM<br>
		Rangkap 1	: Pasien<br>
		Rangkap 2	: Asuransi (bila ada)<br>
		Rangkap 3	: Dokter Keluarga/Faskes Tk 1(bila ada)<br>
		</div>
	</div> -->
	<br><br><br>
</font>
</form>
</font>

<script type="text/javascript">
	var sig = $('#sig').signature({
		syncField: '#signature64',
		syncFormat: 'PNG'
	});
	$('#clear').click(function(e) {
		e.preventDefault();
		sig.signature('clear');
		$("#signature64").val('');
	});
</script>

</body>
</div>
</div>

<?php 

if (isset($_POST["upload"])) {
	// Ambil Gambar yang Dikirim dari Form
	$doc_file = $_FILES['doc']['name'];
  	// Set path folder tempat menyimpan gambarnya
	$path = "upload/".$doc_file;

	$q  = "update ERM_RI_RESUME set doc='$path' where noreg='$noreg'";         
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		if($doc_file){
			unlink($foto_image);
			// move_uploaded_file($nama_file, $path);
			move_uploaded_file($_FILES['doc']['tmp_name'],$path);
		}
		$eror = "Success";
		echo "
		<div class='alert alert-success' role='alert'>
		".$eror."
		</div>
		";

	}else{
		$eror = "Gagal Update";
		echo "
		<div class='alert alert-danger' role='alert'>
		".$eror."
		</div>
		";

	}
}


if (isset($_POST["ttd"])) {
	$folderPath = "upload/";
	$j_ttd = $_POST['j_ttd'];
	$resume134 = $_POST['resume134'];

	// $row1 = explode('/',$resume134);
	// $ttd  = $row1[1];
	unlink($resume134);

	if(empty($_POST['signed']) and empty($j_ttd)){
		echo "Kosong";
		$eror = "Tanda Tangan Kosong";
	}else{
		$image_parts = explode(";base64,", $_POST['signed']); 
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = $image_type_aux[1];
		$image_base64 = base64_decode($image_parts[1]);
		$file = $folderPath . uniqid() . '.'.$image_type;
		file_put_contents($file, $image_base64);
		echo "Tanda Tangan Sukses Diupload ";

		if($j_ttd=="Pasien"){
			$qInsert = "UPDATE ERM_RI_RESUME SET resume134 = '$file' WHERE NOREG='$noreg'";
		}
		if($j_ttd=="Saksi"){
			$qInsert = "UPDATE ERM_RI_RESUME SET resume134 = '$file' WHERE NOREG='$noreg'";
		}

		$qInsert;
		$result = sqlsrv_query($conn, $qInsert);

		$eror = 'Berhasil';

	}

	echo "
	<script>
	alert('".$eror."');
	window.location.replace('resume.php?id=$id|$user');
	</script>
	";

}



if (isset($_POST["update_perawat"])) {
	echo "update_perawat";

	$qep="
	SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume
	FROM ERM_RI_RESUME_PERAWAT
	where noreg='$noreg'";
	$hep  = sqlsrv_query($conn, $qep);        
	$dep  = sqlsrv_fetch_array($hep, SQLSRV_FETCH_ASSOC); 
	$tglresume = $dep['tglresume'];
	$resume1 = $dep['resume1'];
	$resume2= $dep['resume2'];
	$resume3= $dep['resume3'];
	$resume4= $dep['resume4'];
	$resume5= $dep['resume5'];
	$resume6= $dep['resume6'];
	$resume7= $dep['resume7'];
	$resume8= $dep['resume8'];
	$resume9= $dep['resume9'];
	$resume10= $dep['resume10'];
	$resume11= $dep['resume11'];
	$resume12= $dep['resume12'];
	$resume13= $dep['resume13'];
	$resume14= $dep['resume14'];
	$resume15= $dep['resume15'];
	$resume16= $dep['resume16'];
	$resume17= $dep['resume17'];
	$resume18= $dep['resume18'];
	$resume19= $dep['resume19'];
	$resume20= $dep['resume20'];
	$resume21= $dep['resume21'];
	$resume22= $dep['resume22'];
	$resume23= $dep['resume23'];
	$resume24= $dep['resume24'];
	$resume25= $dep['resume25'];
	$resume26= $dep['resume26'];
	$resume27= $dep['resume27'];
	$resume28= $dep['resume28'];
	$resume29= $dep['resume29'];
	$resume30= $dep['resume30'];
	$resume31= $dep['resume31'];
	$resume32= $dep['resume32'];
	$resume33= $dep['resume33'];
	$resume34= $dep['resume34'];
	$resume35= $dep['resume35'];
	$resume36= $dep['resume36'];
	$resume37= $dep['resume37'];
	$resume38= $dep['resume38'];
	$resume39= $dep['resume39'];
	$resume40= $dep['resume40'];
	$resume41= $dep['resume41'];
	$resume42= $dep['resume42'];
	$resume43= $dep['resume43'];
	$resume44= $dep['resume44'];
	$resume45= $dep['resume45'];
	$resume46= $dep['resume46'];
	$resume47= $dep['resume47'];
	$resume48= $dep['resume48'];
	$resume49= $dep['resume49'];
	$resume50= $dep['resume50'];
	$resume51= $dep['resume51'];
	$resume52= $dep['resume52'];
	$resume53= $dep['resume53'];
	$resume54= $dep['resume54'];
	$resume55= $dep['resume55'];
	$resume56= $dep['resume56'];
	$resume57= $dep['resume57'];
	$resume58= $dep['resume58'];
	$resume59= $dep['resume59'];
	$resume60= $dep['resume60'];
	$resume61= $dep['resume61'];
	$resume62= $dep['resume62'];
	$resume63= $dep['resume63'];
	$resume64= $dep['resume64'];
	$resume65= $dep['resume65'];
	$resume66= $dep['resume66'];
	$resume67= $dep['resume67'];
	$resume68= $dep['resume68'];
	$resume69= $dep['resume69'];
	$resume70= $dep['resume70'];
	$resume71= $dep['resume71'];
	$resume72= $dep['resume72'];
	$resume73= $dep['resume73'];
	$resume74= $dep['resume74'];
	$resume75= $dep['resume75'];
	$resume76= $dep['resume76'];
	$resume77= $dep['resume77'];
	$resume78= $dep['resume78'];
	$resume79= $dep['resume79'];
	$resume80= $dep['resume80'];
	$resume81= $dep['resume81'];
	$resume82= $dep['resume82'];
	$resume83= $dep['resume83'];
	$resume84= $dep['resume84'];
	$resume85= $dep['resume85'];
	$resume86= $dep['resume86'];
	$resume87= $dep['resume87'];
	$resume88= $dep['resume88'];
	$resume89= $dep['resume89'];
	$resume90= $dep['resume90'];
	$resume91= $dep['resume91'];
	$resume92= $dep['resume92'];
	$resume93= $dep['resume93'];
	$resume94= $dep['resume94'];
	$resume95= $dep['resume95'];
	$resume96= $dep['resume96'];
	$resume97= $dep['resume97'];
	$resume98= $dep['resume98'];
	$resume99= $dep['resume99'];
	$resume100= $dep['resume100'];
	$resume101= $dep['resume101'];
	$resume102= $dep['resume102'];
	$resume103= $dep['resume103'];
	$resume104= $dep['resume104'];
	$resume105= $dep['resume105'];
	$resume106= $dep['resume106'];
	$resume107= $dep['resume107'];
	$resume108= $dep['resume108'];
	$resume109= $dep['resume109'];
	$resume110= $dep['resume110'];
	$resume111= $dep['resume111'];
	$resume112= $dep['resume112'];
	$resume113= $dep['resume113'];
	$resume114= $dep['resume114'];
	$resume115= $dep['resume115'];
	$resume116= $dep['resume116'];
	$resume117= $dep['resume117'];
	$resume118= $dep['resume118'];
	$resume119= $dep['resume119'];
	$resume120= $dep['resume120'];
	$resume121= $dep['resume121'];
	$resume122= $dep['resume122'];
	$resume123= $dep['resume123'];
	$resume124= $dep['resume124'];
	$resume125= $dep['resume125'];
	$resume126= $dep['resume126'];
	$resume127= $dep['resume127'];
	$resume128= $dep['resume128'];
	$resume129= $dep['resume129'];
	$resume130= $dep['resume130'];
	$resume131= $dep['resume131'];
	$resume132= $dep['resume132'];
	$resume133= $dep['resume133'];
	$resume134= $dep['resume134'];
	$resume135= $dep['resume135'];
	$resume136= $dep['resume136'];
	$resume137= $dep['resume137'];
	$resume138= $dep['resume138'];
	$resume139= $dep['resume139'];
	$resume140= $dep['resume140'];
	$resume141= $dep['resume141'];
	$resume142= $dep['resume142'];
	$resume143= $dep['resume143'];
	$resume144= $dep['resume144'];
	$resume145= $dep['resume145'];
	$resume146= $dep['resume146'];
	$resume147= $dep['resume147'];
	$resume148= $dep['resume148'];
	$resume149= $dep['resume149'];
	$resume150= $dep['resume150'];

	if($resume1){
		$q  = "update ERM_RI_RESUME set
		resume1	='$resume1',
		resume2	='$resume2',
		resume3	='$resume3',
		resume4	='$resume4',
		resume5	='$resume5',
		resume6	='$resume6',
		resume7	='$resume7',	
		resume24	='$resume24',
		resume25	='$resume25',
		resume26	='$resume26',
		resume27	='$resume27',
		resume28	='$resume28',
		resume29	='$resume29',
		resume30	='$resume30',
		resume31	='$resume31',
		resume32	='$resume32',
		resume33	='$resume33',
		resume34	='$resume34',
		resume39	='$resume39',
		resume40	='$resume40',
		resume41	='$resume41',
		resume42	='$resume42',
		resume43	='$resume43',
		resume44	='$resume44',
		resume45	='$resume45',
		resume46	='$resume46',
		resume47	='$resume47',
		resume48	='$resume48',
		resume49	='$resume49',
		resume50	='$resume50',
		resume51	='$resume51',
		resume52	='$resume52',
		resume53	='$resume53',
		resume54	='$resume54',
		resume55	='$resume55',
		resume56	='$resume56',
		resume57	='$resume57',
		resume58	='$resume58',
		resume59	='$resume59',
		resume60	='$resume60',
		resume61	='$resume61',
		resume62	='$resume62',
		resume63	='$resume63',
		resume64	='$resume64',
		resume65	='$resume65',
		resume66	='$resume66',
		resume67	='$resume67',
		resume68	='$resume68',
		resume69	='$resume69',
		resume70	='$resume70',
		resume71	='$resume71',
		resume72	='$resume72',
		resume73	='$resume73',
		resume74	='$resume74',
		resume75	='$resume75',
		resume76	='$resume76',
		resume77	='$resume77',
		resume78	='$resume78',
		resume79	='$resume79',
		resume80	='$resume80',
		resume81	='$resume81',
		resume82	='$resume82',
		resume83	='$resume83',
		resume84	='$resume84',
		resume85	='$resume85',
		resume86	='$resume86',
		resume87	='$resume87',
		resume88	='$resume88',
		resume89	='$resume89',
		resume90	='$resume90',
		resume91	='$resume91',
		resume92	='$resume92',
		resume93	='$resume93',
		resume94	='$resume94',
		resume95	='$resume95',
		resume96	='$resume96',
		resume97	='$resume97',
		resume98	='$resume98',
		resume99	='$resume99',
		resume100	='$resume100',
		resume101	='$resume101',
		resume102	='$resume102',
		resume103	='$resume103',
		resume104	='$resume104',
		resume105	='$resume105',
		resume106	='$resume106',
		resume107	='$resume107',
		resume108	='$resume108',
		resume109	='$resume109',
		resume110	='$resume110',
		resume111	='$resume111',
		resume112	='$resume112',
		resume113	='$resume113',
		resume114	='$resume114',
		resume115	='$resume115',
		resume116	='$resume116',
		resume117	='$resume117',
		resume118	='$resume118',
		resume119	='$resume119',
		resume120	='$resume120',
		resume121	='$resume121',
		resume122	='$resume122',
		resume123	='$resume123',
		resume124	='$resume124',
		resume125	='$resume125',
		resume126	='$resume126',
		resume127	='$resume127',
		resume128	='$resume128',
		resume129	='$resume129',
		resume130	='$resume130',
		resume131	='$resume131',
		resume132	='$resume132',
		resume135	='$resume135',
		resume136	='$resume136',
		resume137	='$resume137',
		resume138	='$resume138',
		resume139	='$resume139',
		resume140	='$resume140',
		resume141	='$resume141',
		resume142	='$resume142',
		resume143	='$resume143',
		resume144	='$resume144',
		resume145	='$resume145',
		resume146	='$resume146',
		resume147	='$resume147',
		resume148	='$resume148',
		resume149	='$resume149',
		resume150	='$resume150'
		where noreg='$noreg'
		";
		$hs = sqlsrv_query($conn,$q);
	}

	if($hs){
		$eror = "Update Success";
	}else{
		$eror = "Update Gagal";
	}

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";



}

if (isset($_POST["simpan_verif_apoteker"])) {

	$lanjut='Y';

	$resume133	= $_POST["resume133"];
	$user_sirs_apoteker 	= $_POST["user_sirs_apoteker"];
	$pass_sirs_apoteker 	= $_POST["pass_sirs_apoteker"];

	//cek user
	$query = "select * from ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$user_sirs_apoteker'";		
	$result = sqlsrv_query($conn, $query);
	$data  = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);          

	$grpuser = $data['grpuser'];
    // cek pass
	if (trim($pass_sirs_apoteker)<>strtolower(trim($data['PASS2']))) {	
		if (trim($pass_sirs_apoteker)<>strtoupper(trim($data['PASS2']))) {	
			$eror='Password Salah !!!';
			$lanjut = 'T';
		}
	}

	$grpuser = trim($grpuser);

	if(preg_match("/FARMASI/i", $grpuser))
	{
		$lanjut = 'Y';
	}else{
		$eror='Akses Failed !!!';
		$lanjut = 'T';		 	
	}


	if($lanjut=='Y'){
		$q  = "update ERM_RI_RESUME set resume133='$resume133' where noreg='$noreg'";
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			$eror='Verifikasi Berhasil';
		}else{
			$eror='Verifikasi Gagal';
		}
	}

	echo "
	<script>
	alert('".$eror."');
	window.location.replace('resume.php?id=$id|$user');
	</script>
	";


}

if (isset($_POST["simpan"])) {

	$resume1	= $_POST["resume1"];
	$resume2	= $_POST["resume2"];
	$resume3	= $_POST["resume3"];
	$resume4	= $_POST["resume4"];
	$resume5	= $_POST["resume5"];
	$resume6	= $_POST["resume6"];
	$resume7	= $_POST["resume7"];
	$resume8	= $_POST["resume8"];
	$resume9	= $_POST["resume9"];
	$resume10	= $_POST["resume10"];
	$resume11	= $_POST["resume11"];
	$resume12	= $_POST["resume12"];
	$resume13	= $_POST["resume13"];
	$resume14	= $_POST["resume14"];
	$resume15	= $_POST["resume15"];
	$resume16	= $_POST["resume16"];
	$resume17	= $_POST["resume17"];
	$resume18	= $_POST["resume18"];
	$resume19	= $_POST["resume19"];
	$resume20	= $_POST["resume20"];
	$resume21	= $_POST["resume21"];
	$resume22	= $_POST["resume22"];
	$resume23	= $_POST["resume23"];
	$resume24	= $_POST["resume24"];
	$resume25	= $_POST["resume25"];
	$resume26	= $_POST["resume26"];
	$resume27	= $_POST["resume27"];
	$resume28	= $_POST["resume28"];
	$resume29	= $_POST["resume29"];
	$resume30	= $_POST["resume30"];
	$resume31	= $_POST["resume31"];
	$resume32	= $_POST["resume32"];
	$resume33	= $_POST["resume33"];
	$resume34	= $_POST["resume34"];
	$resume35	= $_POST["resume35"];
	$resume36	= $_POST["resume36"];
	$resume37	= $_POST["resume37"];
	$resume38	= $_POST["resume38"];
	$resume39	= $_POST["resume39"];
	$resume40	= $_POST["resume40"];
	$resume41	= $_POST["resume41"];
	$resume42	= $_POST["resume42"];
	$resume43	= $_POST["resume43"];
	$resume44	= $_POST["resume44"];
	$resume45	= $_POST["resume45"];
	$resume46	= $_POST["resume46"];
	$resume47	= $_POST["resume47"];
	$resume48	= $_POST["resume48"];
	$resume49	= $_POST["resume49"];
	$resume50	= $_POST["resume50"];
	$resume51	= $_POST["resume51"];
	$resume52	= $_POST["resume52"];
	$resume53	= $_POST["resume53"];
	$resume54	= $_POST["resume54"];
	$resume55	= $_POST["resume55"];
	$resume56	= $_POST["resume56"];
	$resume57	= $_POST["resume57"];
	$resume58	= $_POST["resume58"];
	$resume59	= $_POST["resume59"];
	$resume60	= $_POST["resume60"];
	$resume61	= $_POST["resume61"];
	$resume62	= $_POST["resume62"];
	$resume63	= $_POST["resume63"];
	$resume64	= $_POST["resume64"];
	$resume65	= $_POST["resume65"];
	$resume66	= $_POST["resume66"];
	$resume67	= $_POST["resume67"];
	$resume68	= $_POST["resume68"];
	$resume69	= $_POST["resume69"];
	$resume70	= $_POST["resume70"];
	$resume71	= $_POST["resume71"];
	$resume72	= $_POST["resume72"];
	$resume73	= $_POST["resume73"];
	$resume74	= $_POST["resume74"];
	$resume75	= $_POST["resume75"];
	$resume76	= $_POST["resume76"];
	$resume77	= $_POST["resume77"];
	$resume78	= $_POST["resume78"];
	$resume79	= $_POST["resume79"];
	$resume80	= $_POST["resume80"];
	$resume81	= $_POST["resume81"];
	$resume82	= $_POST["resume82"];
	$resume83	= $_POST["resume83"];
	$resume84	= $_POST["resume84"];
	$resume85	= $_POST["resume85"];
	$resume86	= $_POST["resume86"];
	$resume87	= $_POST["resume87"];
	$resume88	= $_POST["resume88"];
	$resume89	= $_POST["resume89"];
	$resume90	= $_POST["resume90"];
	$resume91	= $_POST["resume91"];
	$resume92	= $_POST["resume92"];
	$resume93	= $_POST["resume93"];
	$resume94	= $_POST["resume94"];
	$resume95	= $_POST["resume95"];
	$resume96	= $_POST["resume96"];
	$resume97	= $_POST["resume97"];
	$resume98	= $_POST["resume98"];
	$resume99	= $_POST["resume99"];
	$resume100	= $_POST["resume100"];
	$resume101	= $_POST["resume101"];
	$resume102	= $_POST["resume102"];
	$resume103	= $_POST["resume103"];
	$resume104	= $_POST["resume104"];
	$resume105	= $_POST["resume105"];
	$resume106	= $_POST["resume106"];
	$resume107	= $_POST["resume107"];
	$resume108	= $_POST["resume108"];
	$resume109	= $_POST["resume109"];
	$resume110	= $_POST["resume110"];
	$resume111	= $_POST["resume111"];
	$resume112	= $_POST["resume112"];
	$resume113	= $_POST["resume113"];
	$resume114	= $_POST["resume114"];
	$resume115	= $_POST["resume115"];
	$resume116	= $_POST["resume116"];
	$resume117	= $_POST["resume117"];
	$resume118	= $_POST["resume118"];
	$resume119	= $_POST["resume119"];
	$resume120	= $_POST["resume120"];
	$resume121	= $_POST["resume121"];
	$resume122	= $_POST["resume122"];
	$resume123	= $_POST["resume123"];
	$resume124	= $_POST["resume124"];
	$resume125	= $_POST["resume125"];
	$resume126	= $_POST["resume126"];
	$resume127	= $_POST["resume127"];
	$resume128	= $_POST["resume128"];
	$resume129	= $_POST["resume129"];
	$resume130	= $_POST["resume130"];
	$resume131	= $_POST["resume131"];
	$resume132	= $_POST["resume132"];
	// $resume133	= $_POST["resume133"];
	// $resume134	= $_POST["resume134"];
	$resume135	= $_POST["resume135"];
	$resume136	= $_POST["resume136"];
	$resume137	= $_POST["resume137"];
	$resume138	= $_POST["resume138"];
	$resume139	= $_POST["resume139"];
	$resume140	= $_POST["resume140"];
	$resume141	= $_POST["resume141"];
	$resume142	= $_POST["resume142"];
	$resume143	= $_POST["resume143"];
	$resume144	= $_POST["resume144"];
	$resume145	= $_POST["resume145"];
	$resume146	= $_POST["resume146"];
	$resume147	= $_POST["resume147"];
	$resume148	= $_POST["resume148"];
	$resume149	= $_POST["resume149"];
	$resume150	= $_POST["resume150"];

	$resume39 = str_replace("'","`",$resume39);
	$resume40 = str_replace("'","`",$resume40);
	$resume41 = str_replace("'","`",$resume41);
	$resume42 = str_replace("'","`",$resume42);
	$resume43 = str_replace("'","`",$resume43);
	$resume44 = str_replace("'","`",$resume44);
	$resume45 = str_replace("'","`",$resume45);
	$resume46 = str_replace("'","`",$resume46);
	$resume47 = str_replace("'","`",$resume47);
	$resume48 = str_replace("'","`",$resume48);


	$q  = "update ERM_RI_RESUME set
	userid = '$user',tglentry='$tglinput',
	resume1	='$resume1',
	resume2	='$resume2',
	resume3	='$resume3',
	resume4	='$resume4',
	resume5	='$resume5',
	resume6	='$resume6',
	resume7	='$resume7',
	resume8	='$resume8',
	resume9	='$resume9',
	resume10	='$resume10',
	resume11	='$resume11',
	resume12	='$resume12',
	resume13	='$resume13',
	resume14	='$resume14',
	resume15	='$resume15',
	resume16	='$resume16',
	resume17	='$resume17',
	resume18	='$resume18',
	resume19	='$resume19',
	resume20	='$resume20',
	resume21	='$resume21',
	resume22	='$resume22',
	resume23	='$resume23',
	resume24	='$resume24',
	resume25	='$resume25',
	resume26	='$resume26',
	resume27	='$resume27',
	resume28	='$resume28',
	resume29	='$resume29',
	resume30	='$resume30',
	resume31	='$resume31',
	resume32	='$resume32',
	resume33	='$resume33',
	resume34	='$resume34',
	resume35	='$resume35',
	resume36	='$resume36',
	resume37	='$resume37',
	resume38	='$resume38',
	resume39	='$resume39',
	resume40	='$resume40',
	resume41	='$resume41',
	resume42	='$resume42',
	resume43	='$resume43',
	resume44	='$resume44',
	resume45	='$resume45',
	resume46	='$resume46',
	resume47	='$resume47',
	resume48	='$resume48',
	resume49	='$resume49',
	resume50	='$resume50',
	resume51	='$resume51',
	resume52	='$resume52',
	resume53	='$resume53',
	resume54	='$resume54',
	resume55	='$resume55',
	resume56	='$resume56',
	resume57	='$resume57',
	resume58	='$resume58',
	resume59	='$resume59',
	resume60	='$resume60',
	resume61	='$resume61',
	resume62	='$resume62',
	resume63	='$resume63',
	resume64	='$resume64',
	resume65	='$resume65',
	resume66	='$resume66',
	resume67	='$resume67',
	resume68	='$resume68',
	resume69	='$resume69',
	resume70	='$resume70',
	resume71	='$resume71',
	resume72	='$resume72',
	resume73	='$resume73',
	resume74	='$resume74',
	resume75	='$resume75',
	resume76	='$resume76',
	resume77	='$resume77',
	resume78	='$resume78',
	resume79	='$resume79',
	resume80	='$resume80',
	resume81	='$resume81',
	resume82	='$resume82',
	resume83	='$resume83',
	resume84	='$resume84',
	resume85	='$resume85',
	resume86	='$resume86',
	resume87	='$resume87',
	resume88	='$resume88',
	resume89	='$resume89',
	resume90	='$resume90',
	resume91	='$resume91',
	resume92	='$resume92',
	resume93	='$resume93',
	resume94	='$resume94',
	resume95	='$resume95',
	resume96	='$resume96',
	resume97	='$resume97',
	resume98	='$resume98',
	resume99	='$resume99',
	resume100	='$resume100',
	resume101	='$resume101',
	resume102	='$resume102',
	resume103	='$resume103',
	resume104	='$resume104',
	resume105	='$resume105',
	resume106	='$resume106',
	resume107	='$resume107',
	resume108	='$resume108',
	resume109	='$resume109',
	resume110	='$resume110',
	resume111	='$resume111',
	resume112	='$resume112',
	resume113	='$resume113',
	resume114	='$resume114',
	resume115	='$resume115',
	resume116	='$resume116',
	resume117	='$resume117',
	resume118	='$resume118',
	resume119	='$resume119',
	resume120	='$resume120',
	resume121	='$resume121',
	resume122	='$resume122',
	resume123	='$resume123',
	resume124	='$resume124',
	resume125	='$resume125',
	resume126	='$resume126',
	resume127	='$resume127',
	resume128	='$resume128',
	resume129	='$resume129',
	resume130	='$resume130',
	resume131	='$resume131',
	resume132	='$resume132',
	resume135	='$resume135',
	resume136	='$resume136',
	resume137	='$resume137',
	resume138	='$resume138',
	resume139	='$resume139',
	resume140	='$resume140',
	resume141	='$resume141',
	resume142	='$resume142',
	resume143	='$resume143',
	resume144	='$resume144',
	resume145	='$resume145',
	resume146	='$resume146',
	resume147	='$resume147',
	resume148	='$resume148',
	resume149	='$resume149',
	resume150	='$resume150'
	where noreg='$noreg'
	";

	// $q  = "update ERM_RI_RESUME set
	// userid = '$user',tglentry='$tglinput',
	// resume1	='$resume1',
	// resume2	='$resume2',
	// resume3	='$resume3',
	// resume4	='$resume4',
	// resume5	='$resume5',
	// resume6	='$resume6',
	// resume7	='$resume7',	
	// resume24	='$resume24',
	// resume25	='$resume25',
	// resume26	='$resume26',
	// resume27	='$resume27',
	// resume28	='$resume28',
	// resume29	='$resume29',
	// resume30	='$resume30',
	// resume31	='$resume31',
	// resume32	='$resume32',
	// resume33	='$resume33',
	// resume34	='$resume34',
	// resume39	='$resume39',
	// resume40	='$resume40',
	// resume41	='$resume41',
	// resume42	='$resume42',
	// resume43	='$resume43',
	// resume44	='$resume44',
	// resume45	='$resume45',
	// resume46	='$resume46',
	// resume47	='$resume47',
	// resume48	='$resume48',
	// resume49	='$resume49',
	// resume50	='$resume50',
	// resume51	='$resume51',
	// resume52	='$resume52',
	// resume53	='$resume53',
	// resume54	='$resume54',
	// resume55	='$resume55',
	// resume56	='$resume56',
	// resume57	='$resume57',
	// resume58	='$resume58',
	// resume59	='$resume59',
	// resume60	='$resume60',
	// resume61	='$resume61',
	// resume62	='$resume62',
	// resume63	='$resume63',
	// resume64	='$resume64',
	// resume65	='$resume65',
	// resume66	='$resume66',
	// resume67	='$resume67',
	// resume68	='$resume68',
	// resume69	='$resume69',
	// resume70	='$resume70',
	// resume71	='$resume71',
	// resume72	='$resume72',
	// resume73	='$resume73',
	// resume74	='$resume74',
	// resume75	='$resume75',
	// resume76	='$resume76',
	// resume77	='$resume77',
	// resume78	='$resume78',
	// resume79	='$resume79',
	// resume80	='$resume80',
	// resume81	='$resume81',
	// resume82	='$resume82',
	// resume83	='$resume83',
	// resume84	='$resume84',
	// resume85	='$resume85',
	// resume86	='$resume86',
	// resume87	='$resume87',
	// resume88	='$resume88',
	// resume89	='$resume89',
	// resume90	='$resume90',
	// resume91	='$resume91',
	// resume92	='$resume92',
	// resume93	='$resume93',
	// resume94	='$resume94',
	// resume95	='$resume95',
	// resume96	='$resume96',
	// resume97	='$resume97',
	// resume98	='$resume98',
	// resume99	='$resume99',
	// resume100	='$resume100',
	// resume101	='$resume101',
	// resume102	='$resume102',
	// resume103	='$resume103',
	// resume104	='$resume104',
	// resume105	='$resume105',
	// resume106	='$resume106',
	// resume107	='$resume107',
	// resume108	='$resume108',
	// resume109	='$resume109',
	// resume110	='$resume110',
	// resume111	='$resume111',
	// resume112	='$resume112',
	// resume113	='$resume113',
	// resume114	='$resume114',
	// resume115	='$resume115',
	// resume116	='$resume116',
	// resume117	='$resume117',
	// resume118	='$resume118',
	// resume119	='$resume119',
	// resume120	='$resume120',
	// resume121	='$resume121',
	// resume122	='$resume122',
	// resume123	='$resume123',
	// resume124	='$resume124',
	// resume125	='$resume125',
	// resume126	='$resume126',
	// resume127	='$resume127',
	// resume128	='$resume128',
	// resume129	='$resume129',
	// resume130	='$resume130',
	// resume131	='$resume131',
	// resume132	='$resume132',
	// resume135	='$resume135',
	// resume136	='$resume136',
	// resume137	='$resume137',
	// resume138	='$resume138',
	// resume139	='$resume139',
	// resume140	='$resume140',
	// resume141	='$resume141',
	// resume142	='$resume142',
	// resume143	='$resume143',
	// resume144	='$resume144',
	// resume145	='$resume145',
	// resume146	='$resume146',
	// resume147	='$resume147',
	// resume148	='$resume148',
	// resume149	='$resume149',
	// resume150	='$resume150'
	// where noreg='$noreg'
	// ";
	$hs = sqlsrv_query($conn,$q);


	$q2  = "update ERM_RI_RESUME_PERAWAT set
	userid = '$user',tglentry='$tglinput',
	resume1	='$resume1',
	resume2	='$resume2',
	resume3	='$resume3',
	resume4	='$resume4',
	resume5	='$resume5',
	resume6	='$resume6',
	resume7	='$resume7',	
	resume24	='$resume24',
	resume25	='$resume25',
	resume26	='$resume26',
	resume27	='$resume27',
	resume28	='$resume28',
	resume29	='$resume29',
	resume30	='$resume30',
	resume31	='$resume31',
	resume32	='$resume32',
	resume33	='$resume33',
	resume34	='$resume34',
	resume39	='$resume39',
	resume40	='$resume40',
	resume41	='$resume41',
	resume42	='$resume42',
	resume43	='$resume43',
	resume44	='$resume44',
	resume45	='$resume45',
	resume46	='$resume46',
	resume47	='$resume47',
	resume48	='$resume48',
	resume49	='$resume49',
	resume50	='$resume50',
	resume51	='$resume51',
	resume52	='$resume52',
	resume53	='$resume53',
	resume54	='$resume54',
	resume55	='$resume55',
	resume56	='$resume56',
	resume57	='$resume57',
	resume58	='$resume58',
	resume59	='$resume59',
	resume60	='$resume60',
	resume61	='$resume61',
	resume62	='$resume62',
	resume63	='$resume63',
	resume64	='$resume64',
	resume65	='$resume65',
	resume66	='$resume66',
	resume67	='$resume67',
	resume68	='$resume68',
	resume69	='$resume69',
	resume70	='$resume70',
	resume71	='$resume71',
	resume72	='$resume72',
	resume73	='$resume73',
	resume74	='$resume74',
	resume75	='$resume75',
	resume76	='$resume76',
	resume77	='$resume77',
	resume78	='$resume78',
	resume79	='$resume79',
	resume80	='$resume80',
	resume81	='$resume81',
	resume82	='$resume82',
	resume83	='$resume83',
	resume84	='$resume84',
	resume85	='$resume85',
	resume86	='$resume86',
	resume87	='$resume87',
	resume88	='$resume88',
	resume89	='$resume89',
	resume90	='$resume90',
	resume91	='$resume91',
	resume92	='$resume92',
	resume93	='$resume93',
	resume94	='$resume94',
	resume95	='$resume95',
	resume96	='$resume96',
	resume97	='$resume97',
	resume98	='$resume98',
	resume99	='$resume99',
	resume100	='$resume100',
	resume101	='$resume101',
	resume102	='$resume102',
	resume103	='$resume103',
	resume104	='$resume104',
	resume105	='$resume105',
	resume106	='$resume106',
	resume107	='$resume107',
	resume108	='$resume108',
	resume109	='$resume109',
	resume110	='$resume110',
	resume111	='$resume111',
	resume112	='$resume112',
	resume113	='$resume113',
	resume114	='$resume114',
	resume115	='$resume115',
	resume116	='$resume116',
	resume117	='$resume117',
	resume118	='$resume118',
	resume119	='$resume119',
	resume120	='$resume120',
	resume121	='$resume121',
	resume122	='$resume122',
	resume123	='$resume123',
	resume124	='$resume124',
	resume125	='$resume125',
	resume126	='$resume126',
	resume127	='$resume127',
	resume128	='$resume128',
	resume129	='$resume129',
	resume130	='$resume130',
	resume131	='$resume131',
	resume132	='$resume132',
	resume135	='$resume135',
	resume136	='$resume136',
	resume137	='$resume137',
	resume138	='$resume138',
	resume139	='$resume139',
	resume140	='$resume140',
	resume141	='$resume141',
	resume142	='$resume142',
	resume143	='$resume143',
	resume144	='$resume144',
	resume145	='$resume145',
	resume146	='$resume146',
	resume147	='$resume147',
	resume148	='$resume148',
	resume149	='$resume149',
	resume150	='$resume150'
	where noreg='$noreg'
	";
	$hs2 = sqlsrv_query($conn,$q2);


	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";


}

?>
