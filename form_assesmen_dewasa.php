<?php 
// include ("koneksi.php");
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include ("mode.php");

include "phpqrcode/qrlib.php";

include "header_px.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglentry		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);


$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$cek_masalah = $row[2]; 


$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = trim($d1u['noreg']);
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

// if($cek_masalah){
// 	echo "
// 	<script>
// 	top.location='form_assesmen_dewasa.php?id=$id|$user.php';
// 	</script>
// 	";

// }

//select master pasien...
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


$qu="
SELECT        TOP (200) NORM,  ALERGI
FROM            Y_ALERGI 
where norm='$norm'

union 
SELECT        ARM_REGISTER.NORM, V_ERM_RI_KEADAAN_UMUM.alergi as ALERGI
FROM            V_ERM_RI_KEADAAN_UMUM INNER JOIN
ARM_REGISTER ON V_ERM_RI_KEADAAN_UMUM.noreg = ARM_REGISTER.NOREG
where ARM_REGISTER.NORM='$norm'
";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

$qu2="SELECT ket as notif  FROM ERM_NOTIF where noreg='$noreg'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$notif = $d1u2['notif'];

$qu3="SELECT diet as diet  FROM ERM_DIET where noreg='$noreg'";
$h1u3  = sqlsrv_query($conn, $qu3);        
$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
$diet = $d1u3['diet'];

//ambil resep

$qi="SELECT noreg FROM ERM_RI_ASSESMEN_AWAL_DEWASA where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_ASSESMEN_AWAL_DEWASA(noreg,userid,tglentry) values ('$noreg','$user','$tglentry')";
	$hs = sqlsrv_query($conn,$q);

	$q  = "update ERM_RI_ASSESMEN_AWAL_DEWASA set m1='Y',m2='Y',m3='Y',m4='Y',m5='Y',m6='Y',m7='Y',m8='Y',m9='Y',m10='Y',m11='Y',m12='Y',m13='Y' where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);

}else{
	$qe="
	SELECT *,
	CONVERT(VARCHAR, tglrawat, 23) as tglrawat,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment,
	CONVERT(VARCHAR, tglentry, 20) as tgl4,
	CONVERT(VARCHAR, tglverif, 103) as tgl_verif,
	CONVERT(VARCHAR, tglverif, 8) as jam_verif

	FROM ERM_RI_ASSESMEN_AWAL_DEWASA 
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tglrawat = $de['tglrawat'];
	$jamrawat = $de['jamrawat'];

	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment'];

	$tgl_verif = $de['tgl_verif'];
	$jam_verif = $de['jam_verif'];

	$dpjp = $de['dpjp'];
	$userid = $de['userid'];
	$keluhan_pasien = $de['keluhan_pasien'];

	$sumber_data_sendiri = trim($de['sumber_data_sendiri']);
	$sumber_data_keluarga = $de['sumber_data_keluarga'];
	$sumber_data_keluarga_nama = $de['sumber_data_keluarga_nama'];
	$sumber_data_keluarga_hub = $de['sumber_data_keluarga_hub'];
	$sumber_data_orang_lain = $de['sumber_data_orang_lain'];
	$sumber_data_orang_lain_nama = $de['sumber_data_orang_lain_nama'];
	$sumber_data_orang_lain_hub = $de['sumber_data_orang_lain_hub'];
	$perlu_interpreter_bahasa = $de['perlu_interpreter_bahasa'];
	$perlu_interpreter_bahasa_bahasa = $de['perlu_interpreter_bahasa_bahasa'];
	$asal_masuk = trim($de['asal_masuk']);
	$masuk_rs = $de['masuk_rs'];
	$rujukan_dari = $de['rujukan_dari'];
	$alatbantu = $de['alatbantu'];
	$prothesis = $de['prothesis'];
	$cacat_tubuh = $de['cacat_tubuh'];
	$riwayat_penyakit_sekarang = $de['riwayat_penyakit_sekarang'];
	$riwayat_penyakit_dahulu = $de['riwayat_penyakit_dahulu'];
	$riwayat_penyakit_keluarga = $de['riwayat_penyakit_keluarga'];
	$obat_sedang_dikonsumsi = $de['obat_sedang_dikonsumsi'];
	$riwayat_alergi = $de['riwayat_alergi'];
	$riwayat_alergi_alergi = $de['riwayat_alergi_alergi'];
	$riwayat_tranfusi_darah = $de['riwayat_tranfusi_darah'];
	$reaksi_alergi = $de['reaksi_alergi'];
	$ket_reaksi_alergi = $de['ket_reaksi_alergi'];
	$riwayat_merokok = $de['riwayat_merokok'];
	$riwayat_keluar_negri = $de['riwayat_keluar_negri'];
	$riwayat_minuman_keras = $de['riwayat_minuman_keras'];

	$ku_kesadaran= $de['ku_kesadaran'];
	$ku_gcs_e= $de['ku_gcs_e'];
	$ku_gcs_v= $de['ku_gcs_v'];
	$ku_gcs_m= $de['ku_gcs_m'];
	$total_gcs = $ku_gcs_e+$ku_gcs_v+$ku_gcs_m;

	$ku_beratbadan= $de['ku_beratbadan'];
	$ku_tinggibadan= $de['ku_tinggibadan'];

	$ku_suhu= $de['ku_suhu'];
	$ku_tensi= $de['ku_tensi'];
	$ku_nadi= $de['ku_nadi'];
	$ku_nadi_ket= $de['ku_nadi_ket'];
	$ku_nafas= $de['ku_nafas'];
	$ku_spo= $de['ku_spo'];

	$ku_respirasi_dada= $de['ku_respirasi_dada'];
	$ku_respirasi_nafas= $de['ku_respirasi_nafas'];
	$ku_suara_nafas= $de['ku_suara_nafas'];
	$ku_sesak_nafas= $de['ku_sesak_nafas'];
	$ku_sesak_nafas_ada= $de['ku_sesak_nafas_ada'];
	$ku_otot_bantu_nafas= $de['ku_otot_bantu_nafas'];
	$ku_batuk= $de['ku_batuk'];
	$ku_sputum= $de['ku_sputum'];
	$ku_batuk_warna= $de['ku_batuk_warna'];
	$ku_alat_medis= $de['ku_alat_medis'];
	$ku_alat_medis_ket= $de['ku_alat_medis_ket'];

	$ku_irkulasi_jantung= $de['ku_irkulasi_jantung'];
	$ku_suara_jantung= $de['ku_suara_jantung'];
	$ku_kelainan_jantung_akral= $de['ku_kelainan_jantung_akral'];
	$ku_kelainan_jantung_crt= $de['ku_kelainan_jantung_crt'];
	$ku_irkulasi_anemis= $de['ku_irkulasi_anemis'];
	$ku_irkulasi_anemis_hb= $de['ku_irkulasi_anemis_hb'];
	$ku_irkulasi_vena= $de['ku_irkulasi_vena'];
	$ku_irkulasi_alat_medis= $de['ku_irkulasi_alat_medis'];
	$ku_irkulasi_alat_medis_ket= $de['ku_irkulasi_alat_medis_ket'];

	$ku_persepsi_fisiologis= $de['ku_persepsi_fisiologis'];
	$ku_persepsi_patologis= $de['ku_persepsi_patologis'];
	$ku_persepsi_mata= $de['ku_persepsi_mata'];
	$ku_persepsi_pupil= $de['ku_persepsi_pupil'];
	$ku_persepsi_telinga= $de['ku_persepsi_telinga'];
	$ku_persepsi_hidung= $de['ku_persepsi_hidung'];
	$ku_persepsi_sensibilitas= $de['ku_persepsi_sensibilitas'];
	$ku_persepsi_bicara= $de['ku_persepsi_bicara'];
	$ku_persepsi_kaku_duduk= $de['ku_persepsi_kaku_duduk'];
	$ku_persepsi_alat_bantu= $de['ku_persepsi_alat_bantu'];
	$ku_persepsi_alat_bantu_ket= $de['ku_persepsi_alat_bantu_ket'];

	$ku_hipermi= $de['ku_hipermi'];
	$ku_hipotermia= $de['ku_hipotermia'];
	$ku_masalah1= $de['ku_masalah1'];
	$ku_masalah2= $de['ku_masalah2'];
	$ku_masalah3= $de['ku_masalah3'];
	$ku_masalah4= $de['ku_masalah4'];
	$ku_masalah5= $de['ku_masalah5'];
	$ku_masalah6= $de['ku_masalah6'];
	$ku_masalah7= $de['ku_masalah7'];
	$ku_masalah8= $de['ku_masalah8'];
	$ku_masalah_lain= $de['ku_masalah_lain'];

	$ku_nyeri= $de['ku_nyeri'];
	$ku_nyeri_skala= $de['ku_nyeri_skala'];
	$ku_nyeri_lokasi= $de['ku_nyeri_lokasi'];
	$ku_tanya1= $de['ku_tanya1'];
	$ku_tanya2= $de['ku_tanya2'];
	$ku_tanya3= $de['ku_tanya3'];
	$ku_tanya4= $de['ku_tanya4'];
	$ku_tanya5= $de['ku_tanya5'];

	$ku_tnyeri1= $de['ku_tnyeri1'];
	$ku_tnyeri2= $de['ku_tnyeri2'];
	$ku_tnyeri3= $de['ku_tnyeri3'];
	$ku_tnyeri4= $de['ku_tnyeri4'];
	$ku_tnyeri5= $de['ku_tnyeri5'];

	$ku_eliminasi1= $de['ku_eliminasi1'];
	$ku_eliminasi2= $de['ku_eliminasi2'];
	$ku_eliminasi3= $de['ku_eliminasi3'];
	$ku_eliminasi4= $de['ku_eliminasi4'];
	$ku_eliminasi5= $de['ku_eliminasi5'];
	$ku_eliminasi6= $de['ku_eliminasi6'];
	$ku_eliminasi7= $de['ku_eliminasi7'];
	$ku_eliminasi8= $de['ku_eliminasi8'];
	$ku_eliminasi9= $de['ku_eliminasi9'];
	$ku_eliminasi10= $de['ku_eliminasi10'];

	$ku_nutrisi1= $de['ku_nutrisi1'];
	$ku_nutrisi2= $de['ku_nutrisi2'];
	$ku_nutrisi3= $de['ku_nutrisi3'];
	$ku_nutrisi4= $de['ku_nutrisi4'];
	$ku_nutrisi5= $de['ku_nutrisi5'];
	$ku_nutrisi6= $de['ku_nutrisi6'];
	$ku_nutrisi7= $de['ku_nutrisi7'];
	$ku_nutrisi8= $de['ku_nutrisi8'];

	$ku_tnutrisi1= $de['ku_tnutrisi1'];
	$ku_tnutrisi2= $de['ku_tnutrisi2'];
	$ku_tnutrisi3= $de['ku_tnutrisi3'];
	$ku_tnutrisi4= $de['ku_tnutrisi4'];
	$ku_tnutrisi5= $de['ku_tnutrisi5'];
	$ku_tnutrisi6= $de['ku_tnutrisi6'];
	$ku_tnutrisi7= $de['ku_tnutrisi7'];
	$ku_tnutrisi8= $de['ku_tnutrisi8'];
	$ku_tnutrisi9= $de['ku_tnutrisi9'];
	$ku_tnutrisi10= $de['ku_tnutrisi10'];

	$ku_nyerimasalah1= $de['ku_nyerimasalah1'];
	$ku_nyerimasalah2= $de['ku_nyerimasalah2'];
	$ku_nyerimasalah3= $de['ku_nyerimasalah3'];
	$ku_nyerimasalah4= $de['ku_nyerimasalah4'];
	$ku_nyerimasalah5= $de['ku_nyerimasalah5'];
	$ku_nyerimasalah6= $de['ku_nyerimasalah6'];
	$ku_nyerimasalah7= $de['ku_nyerimasalah7'];
	$ku_nyerimasalah8= $de['ku_nyerimasalah8'];
	$ku_nyerimasalah9= $de['ku_nyerimasalah9'];
	$ku_nyerimasalah10= $de['ku_nyerimasalah10'];
	$ku_nyerimasalah11= $de['ku_nyerimasalah11'];
	$ku_nyerimasalah12= $de['ku_nyerimasalah12'];

	$aktivitas1= $de['aktivitas1'];
	$aktivitas2= $de['aktivitas2'];

	$taktivitas1= $de['taktivitas1'];
	$taktivitas2= $de['taktivitas2'];
	$taktivitas3= $de['taktivitas3'];
	$taktivitas4= $de['taktivitas4'];
	$taktivitas5= $de['taktivitas5'];
	$taktivitas6= $de['taktivitas6'];
	$taktivitas7= $de['taktivitas7'];
	$taktivitas8= $de['taktivitas8'];
	$taktivitas9= $de['taktivitas9'];
	$taktivitas10= $de['taktivitas10'];
	$taktivitas11= $de['taktivitas11'];
	$taktivitas12= $de['taktivitas12'];
	$taktivitas13= $de['taktivitas13'];
	$taktivitas14= $de['taktivitas14'];
	$taktivitas15= $de['taktivitas15'];
	$taktivitas16= $de['taktivitas16'];
	$taktivitas17= $de['taktivitas17'];
	$taktivitas18= $de['taktivitas18'];
	$taktivitas19= $de['taktivitas19'];
	$taktivitas20= $de['taktivitas20'];
	$taktivitas21= $de['taktivitas21'];
	$taktivitas22= $de['taktivitas22'];
	$taktivitas23= $de['taktivitas23'];
	$taktivitas24= $de['taktivitas24'];
	$taktivitas25= $de['taktivitas25'];
	$taktivitas26= $de['taktivitas26'];
	$taktivitas27= $de['taktivitas27'];
	$taktivitas28= $de['taktivitas28'];
	$taktivitas29= $de['taktivitas29'];
	$taktivitas30= $de['taktivitas30'];

	$kulit1= $de['kulit1'];
	$kulit2= $de['kulit2'];
	$kulit3= $de['kulit3'];
	$kulit4= $de['kulit4'];
	$kulit5= $de['kulit5'];
	$kulit6= $de['kulit6'];

	$tkulit1= $de['tkulit1'];
	$tkulit2= $de['tkulit2'];
	$tkulit3= $de['tkulit3'];
	$tkulit4= $de['tkulit4'];
	$tkulit5= $de['tkulit5'];
	$tkulit6= $de['tkulit6'];
	$tkulit7= $de['tkulit7'];
	$tkulit8= $de['tkulit8'];
	$tkulit9= $de['tkulit9'];
	$tkulit10= $de['tkulit10'];
	$tkulit11= $de['tkulit11'];
	$tkulit12= $de['tkulit12'];
	$tkulit13= $de['tkulit13'];
	$tkulit14= $de['tkulit14'];
	$tkulit15= $de['tkulit15'];
	$tkulit16= $de['tkulit16'];
	$tkulit17= $de['tkulit17'];
	$tkulit18= $de['tkulit18'];
	$tkulit19= $de['tkulit19'];
	$tkulit20= $de['tkulit20'];
	$endokrin1=$de['endokrin1'];
	$endokrin2=$de['endokrin2'];
	$endokrin3=$de['endokrin3'];
	$endokrin4=$de['endokrin4'];
	$reproduksi1=$de['reproduksi1'];
	$reproduksi2=$de['reproduksi2'];
	$reproduksi3=$de['reproduksi3'];
	$maktifitas1=$de['maktifitas1'];
	$maktifitas2=$de['maktifitas2'];
	$maktifitas3=$de['maktifitas3'];
	$maktifitas4=$de['maktifitas4'];
	$maktifitas5=$de['maktifitas5'];
	$maktifitas6=$de['maktifitas6'];
	$maktifitas7=$de['maktifitas7'];
	$maktifitas8=$de['maktifitas8'];

	$tjatuh1=$de['tjatuh1'];
	$tjatuh2=$de['tjatuh2'];
	$tjatuh3=$de['tjatuh3'];
	$tjatuh4=$de['tjatuh4'];
	$tjatuh5=$de['tjatuh5'];
	$tjatuh6=$de['tjatuh6'];
	$tjatuh7=$de['tjatuh7'];
	$tjatuh8=$de['tjatuh8'];
	$tjatuh9=$de['tjatuh9'];
	$tjatuh10=$de['tjatuh10'];
	$tjatuh11=$de['tjatuh11'];
	$tjatuh12=$de['tjatuh12'];
	$tjatuh13=$de['tjatuh13'];
	$tjatuh14=$de['tjatuh14'];

	$asdewasa1=$de['asdewasa1'];
	$asdewasa2=$de['asdewasa2'];
	$asdewasa3=$de['asdewasa3'];
	$asdewasa4=$de['asdewasa4'];
	$asdewasa5=$de['asdewasa5'];
	$asdewasa6=$de['asdewasa6'];
	$asdewasa7=$de['asdewasa7'];
	$asdewasa8=$de['asdewasa8'];
	$asdewasa9=$de['asdewasa9'];
	$asdewasa10=$de['asdewasa10'];
	$asdewasa11=$de['asdewasa11'];
	$asdewasa12=$de['asdewasa12'];
	$asdewasa13=$de['asdewasa13'];
	$asdewasa14=$de['asdewasa14'];
	$asdewasa15=$de['asdewasa15'];
	$asdewasa16=$de['asdewasa16'];
	$asdewasa17=$de['asdewasa17'];
	$asdewasa18=$de['asdewasa18'];
	$asdewasa19=$de['asdewasa19'];
	$asdewasa20=$de['asdewasa20'];
	$asdewasa21=$de['asdewasa21'];
	$asdewasa22=$de['asdewasa22'];
	$asdewasa23=$de['asdewasa23'];
	$asdewasa24=$de['asdewasa24'];
	$asdewasa25=$de['asdewasa25'];
	$asdewasa26=$de['asdewasa26'];
	$asdewasa27=$de['asdewasa27'];
	$asdewasa28=$de['asdewasa28'];
	$asdewasa29=$de['asdewasa29'];
	$asdewasa30=$de['asdewasa30'];
	$asdewasa31=$de['asdewasa31'];
	$asdewasa32=$de['asdewasa32'];
	$asdewasa33=$de['asdewasa33'];
	$asdewasa34=$de['asdewasa34'];
	$asdewasa35=$de['asdewasa35'];
	$asdewasa36=$de['asdewasa36'];
	$asdewasa37=$de['asdewasa37'];
	$asdewasa38=$de['asdewasa38'];
	$asdewasa39=$de['asdewasa39'];
	$asdewasa40=$de['asdewasa40'];
	$asdewasa41=$de['asdewasa41'];
	$asdewasa42=$de['asdewasa42'];
	$asdewasa43=$de['asdewasa43'];
	$asdewasa44=$de['asdewasa44'];
	$asdewasa45=$de['asdewasa45'];
	$asdewasa46=$de['asdewasa46'];
	$asdewasa47=$de['asdewasa47'];
	$asdewasa48=$de['asdewasa48'];
	$asdewasa49=$de['asdewasa49'];
	$asdewasa50=$de['asdewasa50'];
	$asdewasa51=$de['asdewasa51'];
	$asdewasa52=$de['asdewasa52'];
	$asdewasa53=$de['asdewasa53'];
	$asdewasa54=$de['asdewasa54'];
	$asdewasa55=$de['asdewasa55'];
	$asdewasa56=$de['asdewasa56'];
	$asdewasa57=$de['asdewasa57'];
	$asdewasa58=$de['asdewasa58'];
	$asdewasa59=$de['asdewasa59'];
	$asdewasa60=$de['asdewasa60'];
	$asdewasa61=$de['asdewasa61'];
	$ket_1=$de['ket_1'];
	$ket_2=$de['ket_2'];
	$ket_3=$de['ket_3'];

	$m1=$de['m1'];$m2=$de['m2'];$m3=$de['m3'];$m4=$de['m4'];$m5=$de['m5'];$m6=$de['m6'];$m7=$de['m7'];$m8=$de['m8'];$m9=$de['m9'];
	$m10=$de['m10'];$m11=$de['m11'];$m12=$de['m12'];$m13=$de['m13'];
}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
	<link href="css/styles.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<script language="JavaScript" type="text/javascript">
		nextfield = "box1";
		netscape = "";
		ver = navigator.appVersion; len = ver.length;
		for(iln = 0; iln < len; iln++) if (ver.charAt(iln) == "(") break;
			netscape = (ver.charAt(iln+1).toUpperCase() != "C");

		function keyDown(DnEvents) {
			k = (netscape) ? DnEvents.which : window.event.keyCode;
			if (k == 13) {
				if (nextfield == 'done') return true;
				else {
					eval('document.myForm.' + nextfield + '.focus()');
					return false;
				}
			}
		}
		document.onkeydown = keyDown;
		if (netscape) document.captureEvents(Event.KEYDOWN|Event.KEYUP);
	</script>

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

	<style>
		.mst-table {
			width: 100%;
			border-collapse: collapse;
			background-color: #f9f9f9;
			font-family: Arial, sans-serif;
		}
		.mst-table th, .mst-table td {
			border: 1px solid #ccc;
			padding: 8px 12px;
			text-align: left;
		}
		.mst-table th {
			background-color: #66CDAA;
			color: white;
		}
		.mst-table tr:nth-child(even) {
			background-color: #f2f2f2;
		}
		.mst-table input[type="checkbox"] {
			margin-left: 10px;
		}
		.mst-table h1 {
			font-size: 24px;
			color: #2E8B57;
			margin: 0;
		}
		.submit-btn {
			padding: 8px 12px;
			background-color: #66CDAA;
			border: none;
			color: white;
			font-size: 16px;
			cursor: pointer;
			border-radius: 5px;
		}
	</style>


</head> 

<div class="container-fluid">

	<body onload="document.myForm.td_sistolik.focus();">
		<font size='3px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				

				<div class="row">
					<div class="col-12">
						<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'>[ x ]</a>
						&nbsp;&nbsp;
						<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
						&nbsp;&nbsp;
						<!-- <a href='#' class='btn btn-info btn-sm'><i class="bi bi-printer-fill"></i></a> -->
						<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
						<a href='form_assesmen_dewasa_print.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a>		
					</div>
				</div>

				<!-- Title -->
				<div class="text-center mb-4">
					<h5><i class="bi bi-window-plus"></i> ASESMEN AWAL KEPERAWATAN DEWASA</h5>
				</div>

				<!-- Input DPJP -->
				<div class="card shadow-sm mb-4">
					<div class="card-body">
						<h6 class="card-title text-primary"><i class="bi bi-person-lines-fill"></i>DIISI OLEH PERAWAT</h6>
						<div class="row g-3">
							<div class="col-md-4">
								<label for="tglinput" class="form-label">Tanggal Input</label>
								<input type="text" class="form-control" name="tglinput" value="<?php echo $tglinput; ?>">
							</div>
							<div class="col-md-8">
								<label for="dpjp" class="form-label">DPJP (Dokter Penanggung Jawab)</label>
								<input type="text" class="form-control" id="dokter" name="dpjp" value="<?php echo $dpjp;?>" placeholder="Nama atau Kode Dokter" required>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<table  class="table table-bordered">
						<tr>
							<td  colspan='2'>
								Masuk di Ruang Rawat Tanggal : <input type='date' name='tglrawat' value='<?php echo $tglrawat;?>'>
								<?php 
								$waktusekarang		= gmdate("H:i:s", time()+60*60*7);
								if(empty($jamrawat)){
									$jamrawat=$waktusekarang;
								}
								?>
								Jam : <input type='text' name='jamrawat' value='<?php echo $jamrawat;?>'>
								<input class="submit-btn" type='submit' name='simpan' value='simpan'>
							</td>

						</tr>
						<tr>
							<td colspan='2'>
								<table>
									<tr>
										<td>Sumber Data :</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><input type='checkbox' name='sumber_data_sendiri' value='YA' <?php if ($sumber_data_sendiri=="YA"){echo "checked";}?> >Pasien Sendiri</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><input type='checkbox' name='sumber_data_keluarga' value='YA' <?php if ($sumber_data_keluarga=="YA"){echo "checked";}?> >Keluarga</td>
										<td>Nama : <input type='text' name='sumber_data_keluarga_nama' value='<?php echo $sumber_data_keluarga_nama;?>'></td>
										<td>Hubungan : <input type='text' name='sumber_data_keluarga_hub' value='<?php echo $sumber_data_keluarga_hub;?>'></td>
									</tr>
									<tr>
										<td><input type='checkbox' name='sumber_data_orang_lain' value='YA' <?php if ($sumber_data_orang_lain=="YA"){echo "checked";}?> >Orang Lain</td>
										<td>Nama :  <input type='text' name='sumber_data_orang_lain_nama' value='<?php echo $sumber_data_orang_lain_nama;?>'></td>
										<td>Hubungan : <input type='text' name='sumber_data_orang_lain_hub' value='<?php echo $sumber_data_orang_lain_hub;?>'></td>
									</tr>
									<tr>
										<td>Perlu interpreter bahasa :</td>
										<td colspan="2">
											<input type='checkbox' name='perlu_interpreter_bahasa' value='Tidak' <?php if ($perlu_interpreter_bahasa=="Tidak"){echo "checked";}?>> 
											Tidak
											<input type='checkbox' name='perlu_interpreter_bahasa' value='Ya' <?php if ($perlu_interpreter_bahasa=="Ya"){echo "checked";}?>> 
											Ya,
											bahasa
											<input type='text' name='perlu_interpreter_bahasa_bahasa' value='<?php echo $perlu_interpreter_bahasa_bahasa;?>'>
										</td>
									</tr>
									<tr>
										<td>Asal Masuk  :</td>
										<td colspan="2">
											<input type='checkbox' name='asal_masuk' value='Instalasi Gawat Darurat (IGD)' <?php if ($asal_masuk=="Instalasi Gawat Darurat (IGD)"){echo "checked";}?>>Instalasi Gawat Darurat (IGD)      
											<input type='checkbox' name='asal_masuk' value='Instalasi Rawat Jalan(Rajal)' <?php if ($asal_masuk=="Instalasi Rawat Jalan(Rajal)"){echo "checked";}?>>Instalasi Rawat Jalan(Rajal)
										</td>
									</tr>
									<tr>
										<td>Masuk Ke RS :</td>
										<td colspan="2">
											<input type='checkbox' name='masuk_rs' value='Datang sendiri' <?php if ($masuk_rs=="Datang sendiri"){echo "checked";}?>>Datang sendiri  
											<input type='checkbox' name='masuk_rs' value='Diantar keluarga' <?php if ($masuk_rs=="Diantar keluarga"){echo "checked";}?>>        
											Diantar keluarga      
										</td>
									</tr>
									<tr>
										<td>Rujukan dari :</td>
										<td colspan="2">
											<input type='text' name='rujukan_dari' value='<?php echo $rujukan_dari;?>'>
										</td>
									</tr>
								</table>
								<input type='submit' name='simpan' value='simpan'>								
							</td>
						</tr>	
						<tr>
							<td colspan='2'>RIWAYAT KESEHATAN</td>
						</tr>
						<tr>
							<td colspan='2'>
								<table border="0">
									<tr>
										<td>Keluhan pasien (alasan masuk rumah sakit) </td>
										<td>: 
											<!-- <input type='text' name='keluhan_pasien' value='<?php echo $keluhan_pasien;?>'> -->
											<textarea name= "keluhan_pasien" id="fisik" style="min-width:630px; min-height:80px;"><?php echo $keluhan_pasien;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Riwayat penyakit sekarang</td>
										<td>: 
											<textarea name= "riwayat_penyakit_sekarang" id="fisik" style="min-width:630px; min-height:150px;"><?php echo $riwayat_penyakit_sekarang;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Riwayat penyakit dahulu</td>
										<td>: 
											<textarea name= "riwayat_penyakit_dahulu" id="fisik" style="min-width:630px; min-height:80px;"><?php echo $riwayat_penyakit_dahulu;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Riwayat penyakit keluarga</td>
										<td>: 
											<textarea name= "riwayat_penyakit_keluarga" id="fisik" style="min-width:630px; min-height:80px;"><?php echo $riwayat_penyakit_keluarga;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Obat yang sedang dikonsumsi</td>
										<td>: <input type='radio' name='obat_sedang_dikonsumsi' value='Tidak ada' <?php if ($obat_sedang_dikonsumsi=="Tidak ada"){echo "checked";}?>>Tidak ada <input type='radio' name='obat_sedang_dikonsumsi' value='Ada' <?php if ($obat_sedang_dikonsumsi=="Ada"){echo "checked";}?>>Ada, tulis di form, <a href='rekon_obat.php?id=<?php echo $id.'|'.$user;?>'>Rekonsiliasi Obat</a></td>
									</tr>
									<tr>
										<td>Riwayat alergi </td>
										<td>: <input type='radio' name='riwayat_alergi' value='Tidak' <?php if ($riwayat_alergi=="Tidak"){echo "checked";}?>>Tidak
											<input type='radio' name='riwayat_alergi' value='Ya' <?php if ($riwayat_alergi=="Ya"){echo "checked";}?>>Ya, 
											Keterangan Alergi : <input type='text' name='riwayat_alergi_alergi' value='<?php echo $riwayat_alergi_alergi;?>'>
										</td>
									</tr>
									<tr>
										<td>Riwayat transfusi darah</td>
										<td>: <input type='radio' name='riwayat_tranfusi_darah' value='Tidak' <?php if ($riwayat_tranfusi_darah=="Tidak"){echo "checked";}?>>Tidak
											<input type='radio' name='riwayat_tranfusi_darah' value='Ya' <?php if ($riwayat_tranfusi_darah=="Ya"){echo "checked";}?>>Ya
										</td>
									</tr>
									<tr>
										<td>Riwayat merokok</td>
										<td>: <input type='radio' name='riwayat_merokok' value='Tidak' <?php if ($riwayat_merokok=="Tidak"){echo "checked";}?>>Tidak 
											<input type='radio' name='riwayat_merokok' value='Ya' <?php if ($riwayat_merokok=="Ya"){echo "checked";}?>>Ya 
											<!-- <input type='text' name='ket_1' value='<?php echo $ket_1;?>'> -->
										</td>
									</tr>
									<tr>
										<td>Riwayat minum minuman keras</td>
										<td>: <input type='radio' name='riwayat_minuman_keras' value='Tidak' <?php if ($riwayat_minuman_keras=="Tidak"){echo "checked";}?>>Tidak 
											<input type='radio' name='riwayat_minuman_keras' value='Ya' <?php if ($riwayat_minuman_keras=="Ya"){echo "checked";}?>>Ya 
											<!-- <input type='text' name='ket_2' value='<?php echo $ket_2;?>'> -->
										</td>
									</tr>
									<tr>
										<td>Riwayat pergi keluar negeri</td>
										<td>: <input type='radio' name='riwayat_keluar_negri' value='Tidak' <?php if ($riwayat_keluar_negri=="Tidak"){echo "checked";}?>>Tidak 
											<input type='radio' name='riwayat_keluar_negri' value='Ya' <?php if ($riwayat_keluar_negri=="Ya"){echo "checked";}?>>Ya 
											<!-- <input type='text' name='ket_3' value='<?php echo $ket_3;?>'> -->
										</td>
									</tr>
								</table>							
								<input type='submit' name='simpan' value='simpan'>			
							</td>
						</tr>
						<tr>
							<td>
								DATA
							</td>
							<td>
								MASALAH
							</td>
						</tr>	
						<tr>
							<td>
								A. KEADAAN UMUM<br>

								<table border="0">
									<tr>
										<td>Kesadaran :</td>
										<td coslpan='2'><input type='checkbox' name='ku_kesadaran' value='Composmentis' <?php if ($ku_kesadaran=="Composmentis"){echo "checked";}?>>Composmentis 
											<input type='checkbox' name='ku_kesadaran' value='Apatis' <?php if ($ku_kesadaran=="Apatis"){echo "checked";}?>>Apatis
											<input type='checkbox' name='ku_kesadaran' value='Somnolent' <?php if ($ku_kesadaran=="Somnolent"){echo "checked";}?>>Somnolent 
											<input type='checkbox' name='ku_kesadaran' value='Sopor' <?php if ($ku_kesadaran=="Sopor"){echo "checked";}?>>Sopor
											<input type='checkbox' name='ku_kesadaran' value='Coma' <?php if ($ku_kesadaran=="Coma"){echo "checked";}?>>Coma
										</td>
									</tr>
									<tr>
										<td><b>GCS</b></td>
										<td coslpan='2'> 
											E &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='ku_gcs_e' value='<?php echo $ku_gcs_e;?>' size='5'>
											V : <input type='text' name='ku_gcs_v' value='<?php echo $ku_gcs_v;?>' size='5'>
											M : <input type='text' name='ku_gcs_m' value='<?php echo $ku_gcs_m;?>' size='5'>
											<input type='submit' name='simpan' value='hitung total'>
											<br>
											Total : <input type='text' name='total_gcs' value='<?php echo $total_gcs;?>' size='5'>
										</td>
									</tr>
									<tr><td>Berat Badan</td>
										<td colspan='2'>
											<input type='text' name='ku_beratbadan' value='<?php echo $ku_beratbadan;?>' size='5'> Kg,
											&nbsp;Tinggi Badan &nbsp;&nbsp;&nbsp;: 
											<input type='text' name='ku_tinggibadan' value='<?php echo $ku_tinggibadan;?>' size='5'> Cm
										</td>
									</tr>
									<tr><td>Suhu</td>
										<td colspan='2'>
											<input type='text' name='ku_suhu' value='<?php echo $ku_suhu;?>' size='5'> &#8451;
											Tekanan Darah : <input type='text' name='ku_tensi' value='<?php echo $ku_tensi;?>' size='5'> /mmHg
										</td>
									</tr>
									<tr><td>Nadi</td>
										<td colspan='2'>
											<input type='text' name='ku_nadi' value='<?php echo $ku_nadi;?>'> x/mnt 
											<input type='checkbox' name='ku_nadi_ket' value='Teratur' <?php if ($ku_nadi_ket=="Teratur"){echo "checked";}?>>Teratur    
											<input type='checkbox' name='ku_nadi_ket' value='Tidak Teratur' <?php if ($ku_nadi_ket=="Tidak Teratur"){echo "checked";}?>>Tidak Teratur
										</td>
									</tr>
									<tr>
										<td>Nafas</td>
										<td coslpan='2'> 
											<input type='text' name='ku_nafas' value='<?php echo $ku_nafas;?>'> x/mnt
										</td>
									</tr>
									<tr>
										<td>SpO2</td>
										<td coslpan='2'> 
											<input type='text' name='ku_spo' value='<?php echo $ku_spo;?>'> %
										</td>
									</tr>
								</table>

								<input type='submit' name='simpan' value='simpan'>	
							</td>
							<td>
								<br>
								<input type='checkbox' name='m1' value='Y' <?php if ($m1=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
								<?php
								$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
								WHERE     kolom='KEADAAN UMUM'";
								$hasil  = sqlsrv_query($conn, $q);	
								$no=1;
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

									$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

									$nm_field = 'masalah_ku'.$no;	
									if($cek_kode_diagnosa){
										$checked = 'checked';
									}else{
										$checked='';
									}

									echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
									$no+=1;
								}
								?>
								<br>
								<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>
							</td>
						</tr>	

						<tr>
							<td>
								B. RESPIRASI<br>
								<table border="0">
									<tr>
										<td>Pergerakan dada</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_respirasi_dada' value='Asimetris' <?php if ($ku_respirasi_dada=="Asimetris"){echo "checked";}?>>Asimetris  
											<input type='radio' name='ku_respirasi_dada' value='Simetris' <?php if ($ku_respirasi_dada=="Simetris"){echo "checked";}?>>Simetris
										</td>
									</tr>
									<tr>
										<td>Pola nafas</td>
										<td coslpan='2'>:
											<!-- <input type='checkbox' name='ku_respirasi_nafas' value='Irama' <?php if ($ku_respirasi_nafas=="Irama"){echo "checked";}?>> -->
											Irama    
											<input type='radio' name='ku_respirasi_nafas' value='Teratur' <?php if ($ku_respirasi_nafas=="Teratur"){echo "checked";}?>>Teratur 
											<input type='radio' name='ku_respirasi_nafas' value='Tidak Teratur' <?php if ($ku_respirasi_nafas=="Tidak Teratur"){echo "checked";}?>>Tidak Teratur, 
											Jenis :    
											<input type='checkbox' name='ku_respirasi_nafas' value='Biot' <?php if ($ku_respirasi_nafas=="Biot"){echo "checked";}?>>Biot  
											<input type='checkbox' name='ku_respirasi_nafas' value='Cheyne Stokes' <?php if ($ku_respirasi_nafas=="Cheyne Stokes"){echo "checked";}?>>Cheyne Stokes <input type='checkbox' name='ku_respirasi_nafas' value='Khusmaul' <?php if ($ku_respirasi_nafas=="Khusmaul"){echo "checked";}?>>Khusmaul
										</td>
									</tr>
									<tr>
										<td>Suara nafas</td>
										<td coslpan='2'>:
											<input type='checkbox' name='ku_suara_nafas' value='Vesikuler' <?php if ($ku_suara_nafas=="Vesikuler"){echo "checked";}?>>
											Vesikuler  
											<input type='checkbox' name='ku_suara_nafas' value='Ronchi' <?php if ($ku_suara_nafas=="Ronchi"){echo "checked";}?>>Ronchi
											<input type='checkbox' name='ku_suara_nafas' value='Wheezing' <?php if ($ku_suara_nafas=="Wheezing"){echo "checked";}?>>Wheezing
										</td>
									</tr>
									<tr>
										<td>Sesak nafas</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_sesak_nafas' value='Tidak ada' <?php if ($ku_sesak_nafas=="Tidak ada"){echo "checked";}?>>Tidak
											<input type='radio' name='ku_sesak_nafas' value='Ya, Pada saat' <?php if ($ku_sesak_nafas=="Ya, Pada saat"){echo "checked";}?>>Ya, Pada saat :    <input type='checkbox' name='ku_sesak_nafas_ada' value='Inspirasi' <?php if ($ku_sesak_nafas_ada=="Inspirasi"){echo "checked";}?>>Inspirasi
											<input type='checkbox' name='ku_sesak_nafas_ada' value='Ekspirasi' <?php if ($ku_sesak_nafas_ada=="Ekspirasi"){echo "checked";}?>>Ekspirasi
											<input type='checkbox' name='ku_sesak_nafas_ada' value='Istirahat' <?php if ($ku_sesak_nafas_ada=="Istirahat"){echo "checked";}?>>Istirahat
											<input type='checkbox' name='ku_sesak_nafas_ada' value='Aktifitas' <?php if ($ku_sesak_nafas_ada=="Aktifitas"){echo "checked";}?>>Aktifitas
										</td>
									</tr>
									<tr>
										<td>Otot bantu nafas</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_otot_bantu_nafas' value='Tidak' <?php if ($ku_otot_bantu_nafas=="Tidak"){echo "checked";}?>>Tidak
											<input type='radio' name='ku_otot_bantu_nafas' value='Ya' <?php if ($ku_otot_bantu_nafas=="Ya"){echo "checked";}?>>Ya
										</td>
									</tr>
									<tr>
										<td>Batuk</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_batuk' value='Tidak' <?php if ($ku_batuk=="Tidak"){echo "checked";}?>>Tidak
											<input type='radio' name='ku_batuk' value='Ya' <?php if ($ku_batuk=="Ya"){echo "checked";}?>>Ya,
										</td>
									</tr>
									<tr>
										<td>Produksi sputum</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_sputum' value='Tidak' <?php if ($ku_sputum=="Tidak"){echo "checked";}?>>Tidak  
											<input type='radio' name='ku_sputum' value='Ya' <?php if ($ku_sputum=="Ya"){echo "checked";}?>>Ya, 
											Warna : <input type='text' name='ku_batuk_warna' value='<?php echo $ku_batuk_warna;?>'>
										</td>
									</tr>
									<tr>
										<td>Penggunaan alat medis</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_alat_medis' value='Tidak' <?php if ($ku_alat_medis=="Tidak"){echo "checked";}?>>Tidak 
											<input type='radio' name='ku_alat_medis' value='Ya' <?php if ($ku_alat_medis=="Ya"){echo "checked";}?>>Ya, 
											sebutkan : <input type='text' name='ku_alat_medis_ket' value='<?php echo $ku_alat_medis_ket;?>'>
										</td>
									</tr>
								</table>
								<input type='submit' name='simpan' value='simpan'>
							</td>
							<td>
								<br>
								<input type='checkbox' name='m2' value='Y' <?php if ($m2=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>

								<?php
								$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
								WHERE     kolom='RESPIRASI'";
								$hasil  = sqlsrv_query($conn, $q);	
								$no=1;
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

									$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

									$nm_field = 'masalah_respirasi'.$no;	
									if($cek_kode_diagnosa){
										$checked = 'checked';
									}else{
										$checked='';
									}

									echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
									$no+=1;
								}
								?>
								<br>
								<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>


							</td>
						</tr>

						<tr>
							<td>
								C. SIRKULASI<br>
								<table border="0">
									<tr>
										<td>Irama Jantung</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_irkulasi_jantung' value='Reguler' <?php if ($ku_irkulasi_jantung=="Reguler"){echo "checked";}?>>Reguler 
											<input type='radio' name='ku_irkulasi_jantung' value='Ireguler' <?php if ($ku_irkulasi_jantung=="Ireguler"){echo "checked";}?>>Ireguler
										</td>
									</tr>
									<tr>
										<td>Suara Jantung</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_suara_jantung' value='Tidak Ada Kelainan' <?php if ($ku_suara_jantung=="Tidak Ada Kelainan"){echo "checked";}?>>Tidak Ada Kelainan 
											<input type='radio' name='ku_suara_jantung' value='Mur Mur' <?php if ($ku_suara_jantung=="Mur Mur"){echo "checked";}?>>Mur Mur 
											<input type='radio' name='ku_suara_jantung' value='Gallop' <?php if ($ku_suara_jantung=="Gallop"){echo "checked";}?>>Gallop
										</td>
									</tr>
									<tr>
										<td>Akral</td>
										<td coslpan='2'>:
											<!-- <input type='text' name='ku_kelainan_jantung_akral' value='<?php echo $ku_kelainan_jantung_akral;?>'> -->

											<?php 
											if($ku_kelainan_jantung_akral){
												echo "<input type='text' name='ku_kelainan_jantung_akral' value='$ku_kelainan_jantung_akral' size='30'>";
											}else{
												echo "
												<select name='ku_kelainan_jantung_akral'>
												<option value=''>--pilih--</option>
												<option value='hangat'>hangat</option>
												<option value='dingin'>dingin</option>
												<option value='kering'>kering</option>
												<option value='basah'>basah</option>
												<option value='merah'>merah</option>
												<option value='pucat'>pucat</option>
												</select>
												";
											}
											?>

											&nbsp;&nbsp;
											CRT: 
											<input type='radio' name='ctr' value='kurangdari' <?php if ($ctr=="kurangdari"){echo "checked";}?>><
											&nbsp;
											<input type='text' name='ku_kelainan_jantung_crt' value='<?php echo $ku_kelainan_jantung_crt;?>' size='3'> detik
											&nbsp;
											<input type='radio' name='ctr' value='lebihdari' <?php if ($ctr=="lebihdari"){echo "checked";}?>>>

										</td>
									</tr>
									<tr>
										<td>Anemis</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_irkulasi_anemis' value='Tidak' <?php if ($ku_irkulasi_anemis=="Tidak"){echo "checked";}?>>Tidak 
											<input type='radio' name='ku_irkulasi_anemis' value='Ya' <?php if ($ku_irkulasi_anemis=="Ya"){echo "checked";}?>>Ya, 
											Hb : <input type='text' name='ku_irkulasi_anemis_hb' value='<?php echo $ku_irkulasi_anemis_hb;?>'>mg/dl
										</td>
									</tr>
									<tr>
										<td>Distensi vena jugular</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_irkulasi_vena' value='Tidak' <?php if ($ku_irkulasi_vena=="Tidak"){echo "checked";}?>> Tidak
											<input type='radio' name='ku_irkulasi_vena' value='Ya' <?php if ($ku_irkulasi_vena=="Ya"){echo "checked";}?>> Ya
										</td>
									</tr>
									<tr>
										<td>Penggunaan alat medis</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_irkulasi_alat_medis' value='Tidak' <?php if ($ku_irkulasi_alat_medis=="Tidak"){echo "checked";}?>>Tidak
											<input type='radio' name='ku_irkulasi_alat_medis' value='Ya' <?php if ($ku_irkulasi_alat_medis=="Ya"){echo "checked";}?>>Ya, 
											sebutkan : <input type='text' name='ku_irkulasi_alat_medis_ket' value='<?php echo $ku_irkulasi_alat_medis_ket;?>'> Ya
										</td>
									</tr>
								</table>
								<input type='submit' name='simpan' value='simpan'>
							</td>
							<td>

								<input type='checkbox' name='m3' value='Y' <?php if ($m3=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
								<?php
								$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
								WHERE     kolom='SIRKULASI'";
								$hasil  = sqlsrv_query($conn, $q);	
								$no=1;
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

									$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

									$nm_field = 'masalah_sirkulasi'.$no;	
									if($cek_kode_diagnosa){
										$checked = 'checked';
									}else{
										$checked='';
									}

									echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
									$no+=1;
								}
								?>
								<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>
								<br>
								masalah lain : <br><input type='text' name='ku_masalah_lain' value='<?php echo $ku_masalah_lain;?>'><br>	


							</td>
						</tr>

						<tr>
							<td>
								D. PERSEPSI DAN SENSORI<br>
								<table border="0">
									<tr>
										<td>Reflek fisiologis</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_fisiologis' value='Normal' <?php if ($ku_persepsi_fisiologis=="Normal"){echo "checked";}?>>Normal
											<input type='radio' name='ku_persepsi_fisiologis' value='Patela' <?php if ($ku_persepsi_fisiologis=="Patela"){echo "checked";}?>>Patela
											<input type='radio' name='ku_persepsi_fisiologis' value='Triceps' <?php if ($ku_persepsi_fisiologis=="Triceps"){echo "checked";}?>>Triceps
											<input type='radio' name='ku_persepsi_fisiologis' value='Biseps' <?php if ($ku_persepsi_fisiologis=="Biseps"){echo "checked";}?>>Biseps
										</td>
									</tr>
									<tr>
										<td>Reflek Patologis</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_patologis' value='Normal' <?php if ($ku_persepsi_patologis=="Normal"){echo "checked";}?>>Normal
											<input type='radio' name='ku_persepsi_patologis' value='Babinsky' <?php if ($ku_persepsi_patologis=="Babinsky"){echo "checked";}?>>Babinsky
											<input type='radio' name='ku_persepsi_patologis' value='Brudzinsky' <?php if ($ku_persepsi_patologis=="Brudzinsky"){echo "checked";}?>>Brudzinsky
											<input type='radio' name='ku_persepsi_patologis' value='Kerning' <?php if ($ku_persepsi_patologis=="Kerning"){echo "checked";}?>>Kerning
										</td>
									</tr>
									<tr>
										<td>Mata</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_mata' value='Normal' <?php if ($ku_persepsi_mata=="Normal"){echo "checked";}?>>Normal
											<input type='radio' name='ku_persepsi_mata' value='Kabur' <?php if ($ku_persepsi_mata=="Kabur"){echo "checked";}?>>Kabur     
											<input type='radio' name='ku_persepsi_mata' value='Diplopia' <?php if ($ku_persepsi_mata=="Diplopia"){echo "checked";}?>>Diplopia    
											<input type='radio' name='ku_persepsi_mata' value='Buta' <?php if ($ku_persepsi_mata=="Buta"){echo "checked";}?>>Buta
											<input type='radio' name='ku_persepsi_mata' value='Strabismus' <?php if ($ku_persepsi_mata=="Strabismus"){echo "checked";}?>>Strabismus
										</td>
									</tr>
									<tr>
										<td>Pupil</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_pupil' value='Isokor' <?php if ($ku_persepsi_pupil=="Isokor"){echo "checked";}?>>Isokor 
											<input type='radio' name='ku_persepsi_pupil' value='Anisokor' <?php if ($ku_persepsi_pupil=="Anisokor"){echo "checked";}?>>Anisokor
											<input type='radio' name='ku_persepsi_pupil' value='Reaksi Cahaya' <?php if ($ku_persepsi_pupil=="Reaksi Cahaya"){echo "checked";}?>>Reaksi Cahaya
										</td>
									</tr>
									<tr>
										<td>Telinga</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_telinga' value='Normal' <?php if ($ku_persepsi_telinga=="Normal"){echo "checked";}?>>Normal
											<input type='radio' name='ku_persepsi_telinga' value='Berkurang' <?php if ($ku_persepsi_telinga=="Berkurang"){echo "checked";}?>>Berkurang 
											<input type='radio' name='ku_persepsi_telinga' value='Serumen' <?php if ($ku_persepsi_telinga=="Serumen"){echo "checked";}?>>Serumen
											<input type='radio' name='ku_persepsi_telinga' value='Tuli' <?php if ($ku_persepsi_telinga=="Tuli"){echo "checked";}?>>Tuli
											<input type='radio' name='ku_persepsi_telinga' value='Tinitus' <?php if ($ku_persepsi_telinga=="Tinitus"){echo "checked";}?>>Tinitus
										</td>
									</tr>
									<tr>
										<td>Hidung</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_hidung' value='Normal' <?php if ($ku_persepsi_hidung=="Normal"){echo "checked";}?>>Normal 
											<input type='radio' name='ku_persepsi_hidung' value='Berkurang' <?php if ($ku_persepsi_hidung=="Berkurang"){echo "checked";}?>>Berkurang
											<input type='radio' name='ku_persepsi_hidung' value='Epistaksis' <?php if ($ku_persepsi_hidung=="Epistaksis"){echo "checked";}?>>Epistaksis
											<input type='radio' name='ku_persepsi_hidung' value='Sekret' <?php if ($ku_persepsi_hidung=="Sekret"){echo "checked";}?>>Sekret 
											<input type='radio' name='ku_persepsi_hidung' value='Tersumbat' <?php if ($ku_persepsi_hidung=="Tersumbat"){echo "checked";}?>>Tersumbat
										</td>
									</tr>
									<tr>
										<td>Sensibilitas</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_sensibilitas' value='Normal' <?php if ($ku_persepsi_sensibilitas=="Normal"){echo "checked";}?>>Normal
											<input type='radio' name='ku_persepsi_sensibilitas' value='Kesemutan' <?php if ($ku_persepsi_sensibilitas=="Kesemutan"){echo "checked";}?>>Kesemutan 
											<input type='radio' name='ku_persepsi_sensibilitas' value='Baal' <?php if ($ku_persepsi_sensibilitas=="Baal"){echo "checked";}?>>Baal     
											<input type='radio' name='ku_persepsi_sensibilitas' value='Hiperestesi' <?php if ($ku_persepsi_sensibilitas=="Hiperestesi"){echo "checked";}?>>Hiperestesi, 
											<input type='radio' name='ku_persepsi_sensibilitas' value='Lokasi' <?php if ($ku_persepsi_sensibilitas=="Lokasi"){echo "checked";}?>>Lokasi : Bicara :
											<input type='radio' name='ku_persepsi_bicara' value='Normal' <?php if ($ku_persepsi_bicara=="Normal"){echo "checked";}?>>Normal
											<input type='radio' name='ku_persepsi_bicara' value='Afasia' <?php if ($ku_persepsi_bicara=="Afasia"){echo "checked";}?>>Afasia
										</td>
									</tr>
									<tr>
										<td>Kaku kuduk</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_kaku_duduk' value='Tidak' <?php if ($ku_persepsi_kaku_duduk=="Tidak"){echo "checked";}?>>Tidak  
											<input type='radio' name='ku_persepsi_kaku_duduk' value='Ya' <?php if ($ku_persepsi_kaku_duduk=="Ya"){echo "checked";}?>>Ya
										</td>
									</tr>
									<tr>
										<td>Penggunaan alat bantu</td>
										<td coslpan='2'>:
											<input type='radio' name='ku_persepsi_alat_bantu' value='Tidak' <?php if ($ku_persepsi_alat_bantu=="Tidak"){echo "checked";}?>>Tidak
											<input type='radio' name='ku_persepsi_alat_bantu' value='Ya' <?php if ($ku_persepsi_alat_bantu=="Ya"){echo "checked";}?>>Ya, 
											sebutkan : <input type='text' name='ku_persepsi_alat_bantu_ket' value='<?php echo $ku_persepsi_alat_bantu_ket;?>'>
										</td>
									</tr>
								</table>
								<input type='submit' name='simpan' value='simpan'>
							</td>
							<td>
								&nbsp;
							</td>
						</tr>

						<tr>
							<td>
								Nyeri<br>
								Keluhan nyeri : 
								<input type='radio' name='ku_nyeri' value='Tidak' <?php if ($ku_nyeri=="Tidak"){echo "checked";}?>>Tidak
								<input type='radio' name='ku_nyeri' value='Ya' <?php if ($ku_nyeri=="Ya"){echo "checked";}?>>Ya, 
								Skala : <input type='text' name='ku_nyeri_skala' value='<?php echo $ku_nyeri_skala;?>' size='3'>
								Lokasi Nyeri <input type='text' name='ku_nyeri_lokasi' value='<?php echo $ku_nyeri_lokasi;?>'><br>
								1. Apakah nyeri berpindah dari tempat satu ke tempat lain? 
								<input type='radio' name='ku_tanya1' value='Tidak' <?php if ($ku_tanya1=="Tidak"){echo "checked";}?>>Tidak  
								<input type='radio' name='ku_tanya1' value='Ya' <?php if ($ku_tanya1=="Ya"){echo "checked";}?>>Ya<br>
								2. Berapa lama Nyeri ?  
								<input type='radio' name='ku_tanya2' value='Akut : < 3 bulan' <?php if ($ku_tanya2=="Akut : < 3 bulan"){echo "checked";}?>>Akut : < 3 bulan   
								<input type='radio' name='ku_tanya2' value='Kronik : > 3 bulan' <?php if ($ku_tanya2=="Kronik : > 3 bulan"){echo "checked";}?>>Kronik : > 3 bulan<br>
								3. Gambaran rasa nyeri :
								<input type='checkbox' name='ku_tanya3' value='Nyeri tumpul' <?php if ($ku_tanya3=="Nyeri tumpul"){echo "checked";}?>>  Nyeri tumpul
								<input type='checkbox' name='ku_tanya3' value='Seperti ditarik' <?php if ($ku_tanya3=="Seperti ditarik"){echo "checked";}?>>Seperti ditarik  
								<input type='checkbox' name='ku_tanya3' value='Seperti ditusuk' <?php if ($ku_tanya3=="Seperti ditusuk"){echo "checked";}?>>Seperti ditusuk
								<br>
								&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' name='ku_tanya3' value='Seperti dipukul' <?php if ($ku_tanya3=="Seperti dipukul"){echo "checked";}?>>Seperti dipukul  
								<input type='checkbox' name='ku_tanya3' value='Seperti dibakar' <?php if ($ku_tanya3=="Seperti dibakar"){echo "checked";}?>>Seperti dibakar 
								<input type='checkbox' name='ku_tanya3' value='Seperti berdenyut' <?php if ($ku_tanya3=="Seperti berdenyut"){echo "checked";}?>>Seperti berdenyut 
								<input type='checkbox' name='ku_tanya3' value='Seperti ditikam' <?php if ($ku_tanya3=="Seperti ditikam"){echo "checked";}?>>Seperti ditikam 
								<input type='checkbox' name='ku_tanya3' value='Seperti kram' <?php if ($ku_tanya3=="Seperti kram"){echo "checked";}?>>Seperti kram <br>
								4. Seberapa sering anda mengalami nyeri ? 
								<input type='checkbox' name='ku_tanya4' value='1―2 jam' <?php if ($ku_tanya4=="1―2 jam"){echo "checked";}?>>1―2 jam  
								<input type='checkbox' name='ku_tanya4' value='3―4 jam' <?php if ($ku_tanya4=="3―4 jam"){echo "checked";}?>>3―4 jam  
								<input type='checkbox' name='ku_tanya4' value='>4 jam' <?php if ($ku_tanya4==">4 jam"){echo "checked";}?>>>4 jam 
								<br>&nbsp;&nbsp;&nbsp;&nbsp;setiap  : 
								<input type='checkbox' name='ku_tanya4' value='< 30 menit' <?php if ($ku_tanya4=="< 30 menit"){echo "checked";}?>>< 30 menit 
								<input type='checkbox' name='ku_tanya4' value='> 30 menit' <?php if ($ku_tanya4=="> 30 menit"){echo "checked";}?>>> 30 menit <br>
								5. Apa yang membuat nyeri berkurang ? 
								<input type='checkbox' name='ku_tanya5' value='Kompres hangat / dingin' <?php if ($ku_tanya5=="Kompres hangat / dingin"){echo "checked";}?>>Kompres hangat / dingin 
								<input type='checkbox' name='ku_tanya5' value='Istirahat' <?php if ($ku_tanya5=="Istirahat"){echo "checked";}?>>Istirahat 
								<input type='checkbox' name='ku_tanya5' value='Minum Obat' <?php if ($ku_tanya5=="Minum Obat"){echo "checked";}?>>Minum Obat 
								<input type='checkbox' name='ku_tanya5' value='Berubah posisi' <?php if ($ku_tanya5=="Berubah posisi"){echo "checked";}?>>Berubah posisi<br>
								<!-- (pemeriksaan ini digunakan untuk anak-anak usia >3 tahun, untuk bayi gunakan NIPS, untuk anak dan pasien tidak sadar gunakan FLACC) -->
								<br>
								<input type='submit' name='simpan' value='simpan'><br>
								Pemeriksaan Nyeri – FLACC PAIN SCALE / DIGUNAKAN UNTUK PASIEN TIDAK SADAR<br>
								<table class="mst-table">
									<tr>
										<th rowspan='2'>Katagori</th>
										<th colspan='3'>Skor</th>
										<th rowspan='2'>Total</th>
									</tr>
									<tr>
										<th>0</th>
										<th>1</th>
										<th>2</th>
									</tr>
									<tr>
										<td style="border: 1px solid;">FACE  (WAJAH)</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri1' value='Tak ada ekspresi' <?php if ($ku_tnyeri1=="Tak ada ekspresi"){echo "checked";}?>>Tak ada ekspresi</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri1' value='Menyeringai' <?php if ($ku_tnyeri1=="Menyeringai"){echo "checked";}?>>Menyeringai</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri1' value='Dagu gemetar & rahang dikatup erat' <?php if ($ku_tnyeri1=="Dagu gemetar & rahang dikatup erat"){echo "checked";}?>>Dagu gemetar & rahang dikatup erat</td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnyeri1=='Tak ada ekspresi')
												{$ku_tnyeri1_skor=0;}
											if($ku_tnyeri1=='Menyeringai')
												{$ku_tnyeri1_skor=1;}
											if($ku_tnyeri1=='Dagu gemetar & rahang dikatup erat')
												{$ku_tnyeri1_skor=2;}
											echo $ku_tnyeri1_skor;
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;">LEG  (KAKI)</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri2' value='Normal' <?php if ($ku_tnyeri2=="Normal"){echo "checked";}?>>Normal</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri2' value='Gelisah tenang' <?php if ($ku_tnyeri2=="Gelisah tenang"){echo "checked";}?>>Gelisah tenang</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri2' value='Menendang/ melawan' <?php if ($ku_tnyeri2=="Menendang/ melawan"){echo "checked";}?>>Menendang/ melawan</td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnyeri2=='Normal')
												{$ku_tnyeri2_skor=0;}
											if($ku_tnyeri2=='Gelisah tenang')
												{$ku_tnyeri2_skor=1;}
											if($ku_tnyeri2=='Menendang/ melawan')
												{$ku_tnyeri2_skor=2;}
											echo $ku_tnyeri2_skor;
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;">ACTIVITY (AKTIFITAS)</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri3' value='Tidur tenang' <?php if ($ku_tnyeri3=="Tidur tenang"){echo "checked";}?>>Tidur tenang</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri3' value='Menggeliat tenang' <?php if ($ku_tnyeri3=="Menggeliat tenang"){echo "checked";}?>>Menggeliat tenang</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri3' value='Menekuk, kaku' <?php if ($ku_tnyeri3=="Menekuk, kaku"){echo "checked";}?>>Menekuk, kaku</td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnyeri3=='Tidur tenang')
												{$ku_tnyeri3_skor=0;}
											if($ku_tnyeri3=='Menggeliat tenang')
												{$ku_tnyeri3_skor=1;}
											if($ku_tnyeri3=='Menekuk, kaku')
												{$ku_tnyeri3_skor=2;}
											echo $ku_tnyeri3_skor;
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;">CRY (MENANGIS)</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri4' value='Tidur , tak ada tangisan' <?php if ($ku_tnyeri4=="Tidur , tak ada tangisan"){echo "checked";}?>>Tidur , tak ada tangisan</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri4' value='Mengerang, merengek' <?php if ($ku_tnyeri4=="Mengerang, merengek"){echo "checked";}?>>Mengerang, merengek</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri4' value='Menangis, menjerit' <?php if ($ku_tnyeri4=="Menangis, menjerit"){echo "checked";}?>>Menangis, menjerit</td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnyeri4=='Tidur , tak ada tangisan')
												{$ku_tnyeri4_skor=0;}
											if($ku_tnyeri4=='Mengerang, merengek')
												{$ku_tnyeri4_skor=1;}
											if($ku_tnyeri4=='Menangis, menjerit')
												{$ku_tnyeri4_skor=2;}
											echo $ku_tnyeri4_skor;
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;">CONSOLABILITY</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri5' value='Santai, nyaman' <?php if ($ku_tnyeri5=="Santai, nyaman"){echo "checked";}?>>Santai, nyaman</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri5' value='Dipastikan dengan sentuhan' <?php if ($ku_tnyeri5=="Dipastikan dengan sentuhan"){echo "checked";}?>>Dipastikan dengan sentuhan</td>
										<td style="border: 1px solid;"><input type='radio' name='ku_tnyeri5' value='Sulit konsul / tidak nyaman' <?php if ($ku_tnyeri5=="Sulit konsul / tidak nyaman"){echo "checked";}?>>Sulit konsul / tidak nyaman</td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnyeri5=='Santai, nyaman')
												{$ku_tnyeri5_skor=0;}
											if($ku_tnyeri5=='Dipastikan dengan sentuhan')
												{$ku_tnyeri5_skor=1;}
											if($ku_tnyeri5=='Sulit konsul / tidak nyaman')
												{$ku_tnyeri5_skor=2;}
											echo $ku_tnyeri5_skor;
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;" colspan="4">SKOR TOTAL</td>
										<td style="border: 1px solid;">
											<?php 
											echo $ku_tnyeri1_skor+$ku_tnyeri2_skor+$ku_tnyeri3_skor+$ku_tnyeri4_skor+$ku_tnyeri5_skor;
											?>
										</td>
									</tr>
								</table>
								<input type='submit' name='simpan' class='submit-btn' value='simpan'>
								&nbsp;&nbsp;&nbsp;
								<input type='submit' name='edit' value='edit'>
								<br>
								<br>
							</td>
							<td>
								<br>
								<input type='checkbox' name='m4' value='Y' <?php if ($m4=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
								<?php
								$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
								WHERE     kolom='NYERI'";
								$hasil  = sqlsrv_query($conn, $q);	
								$no=1;
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

									$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

									$nm_field = 'masalah_nyeri'.$no;	
									if($cek_kode_diagnosa){
										$checked = 'checked';
									}else{
										$checked='';
									}

									echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
									$no+=1;
								}
								?>
								<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>
								<br>

							</td>
						</tr>

						<tr>
							<td>
								E. ELIMINASI<br>
								BAK : 
								Frekuensi <input type='text' name='ku_eliminasi1' value='<?php echo $ku_eliminasi1;?>' size='2'> x/hari  
								Warna : <input type='text' name='ku_eliminasi2' value='<?php echo $ku_eliminasi2;?>'> <br>
								Gangguan : 
								<input type='checkbox' name='ku_eliminasi3' value='Tidak' <?php if ($ku_eliminasi3=="Tidak"){echo "checked";}?>>Tidak
								<input type='checkbox' name='ku_eliminasi3' value='Retensi' <?php if ($ku_eliminasi3=="Retensi"){echo "checked";}?>>Retensi
								<input type='checkbox' name='ku_eliminasi3' value='Inkontinensia' <?php if ($ku_eliminasi3=="Inkontinensia"){echo "checked";}?>>Inkontinensia
								<input type='checkbox' name='ku_eliminasi3' value='Anuri' <?php if ($ku_eliminasi3=="Anuri"){echo "checked";}?>>Anuri
								<input type='checkbox' name='ku_eliminasi3' value='Oliguri' <?php if ($ku_eliminasi3=="Oliguri"){echo "checked";}?>>Oliguri
								<input type='checkbox' name='ku_eliminasi3' value='Hematuri' <?php if ($ku_eliminasi3=="Hematuri"){echo "checked";}?>>Hematuri
								<input type='checkbox' name='ku_eliminasi3' value='Lain-lain' <?php if ($ku_eliminasi3=="Lain-lain"){echo "checked";}?>>Lain-lain<br>
								Penggunaan alat medis : 
								<input type='checkbox' name='ku_eliminasi4' value='Tidak' <?php if ($ku_eliminasi4=="Tidak"){echo "checked";}?>>Tidak   
								<input type='checkbox' name='ku_eliminasi4' value='kateter' <?php if ($ku_eliminasi4=="kateter"){echo "checked";}?>>kateter  
								<input type='checkbox' name='ku_eliminasi4' value='Lain-lain' <?php if ($ku_eliminasi4=="Lain-lain"){echo "checked";}?>>Lain-lain :   
								Tanggal pemasangan : <input type='text' name='ku_eliminasi5' value='<?php echo $ku_eliminasi5;?>'><br>
								BAB : 
								<input type='checkbox' name='ku_eliminasi6' value='Normal' <?php if ($ku_eliminasi6=="Normal"){echo "checked";}?>>Normal
								<input type='checkbox' name='ku_eliminasi6' value='Konstipasi' <?php if ($ku_eliminasi6=="Konstipasi"){echo "checked";}?>>Konstipasi 
								<input type='checkbox' name='ku_eliminasi6' value='Diare' <?php if ($ku_eliminasi6=="Diare"){echo "checked";}?>>Diare : 
								Frekuensi  : <input type='text' name='ku_eliminasi7' value='<?php echo $ku_eliminasi7;?>' size='2'>x/hari,  
								Konsistensi : <input type='text' name='ku_eliminasi8' value='<?php echo $ku_eliminasi8;?>'>
								Warna :<input type='text' name='ku_eliminasi9' value='<?php echo $ku_eliminasi9;?>'><br>
<!-- 								Penggunaan alat medis : 
								<input type='checkbox' name='ku_eliminasi10' value='Tidak' <?php if ($ku_eliminasi10=="Tidak"){echo "checked";}?>>Tidak
								<input type='checkbox' name='ku_eliminasi10' value='Kolostomi' <?php if ($ku_eliminasi10=="Kolostomi"){echo "checked";}?>>Kolostomi
								<input type='checkbox' name='ku_eliminasi10' value='Lain-lain' <?php if ($ku_eliminasi10=="Lain-lain"){echo "checked";}?>>Lain-lain<br> -->
								<input type='submit' name='simpan' value='simpan'><br>
							</td>
							<td>

								<br>
								<input type='checkbox' name='m5' value='Y' <?php if ($m5=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
								<?php
								$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
								WHERE     kolom='ELIMINASI'";
								$hasil  = sqlsrv_query($conn, $q);	
								$no=1;
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

									$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

									$nm_field = 'masalah_eliminasi'.$no;	
									if($cek_kode_diagnosa){
										$checked = 'checked';
									}else{
										$checked='';
									}

									echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
									$no+=1;
								}
								?>
								<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>
								<br>


							</td>
						</tr>

						<tr>
							<td>
								F. CAIRAN DAN NUTRISI
								<br>
								Keluhan : 
								<input type='checkbox' name='ku_nutrisi1' value='Nafsu makan menurun' <?php if ($ku_nutrisi1=="Nafsu makan menurun"){echo "checked";}?>>Nafsu makan menurun 
								<input type='checkbox' name='ku_nutrisi1' value='Mual' <?php if ($ku_nutrisi1=="Mual"){echo "checked";}?>>Mual 
								<input type='checkbox' name='ku_nutrisi1' value='Muntah' <?php if ($ku_nutrisi1=="Muntah"){echo "checked";}?>>Muntah<br>
								Mulut : 
								<input type='checkbox' name='ku_nutrisi2' value='Bersih' <?php if ($ku_nutrisi2=="Bersih"){echo "checked";}?>>Bersih 
								<input type='checkbox' name='ku_nutrisi2' value='Bau' <?php if ($ku_nutrisi2=="Bau"){echo "checked";}?>>Bau 
								<input type='checkbox' name='ku_nutrisi2' value='Lidah kotor' <?php if ($ku_nutrisi2=="Lidah kotor"){echo "checked";}?>>Lidah kotor<br>
								Mukosa bibir : 
								<input type='checkbox' name='ku_nutrisi3' value='Normal' <?php if ($ku_nutrisi3=="Normal"){echo "checked";}?>>Normal 
								<input type='checkbox' name='ku_nutrisi3' value='Kering/pecah' <?php if ($ku_nutrisi3=="Kering/pecah"){echo "checked";}?>>Kering/pecah<br>
								Gigi : 
								<input type='checkbox' name='ku_nutrisi4' value='Normal' <?php if ($ku_nutrisi4=="Normal"){echo "checked";}?>>Normal 
								<input type='checkbox' name='ku_nutrisi4' value='Karies' <?php if ($ku_nutrisi4=="Karies"){echo "checked";}?>>Karies 
								<input type='checkbox' name='ku_nutrisi4' value='Gigi palsu' <?php if ($ku_nutrisi4=="Gigi palsu"){echo "checked";}?>>Gigi palsu<br>
								Pola minum : 
								Jumlah : <input type='text' name='ku_nutrisi5' value='<?php echo $ku_nutrisi5;?>'> cc  <br>
								<!-- Jenis minuman : <input type='text' name='ku_nutrisi6' value='<?php echo $ku_nutrisi6;?>'> <br> -->
								Penggunaan alat medis : 
								<input type='checkbox' name='ku_nutrisi7' value='Tidak' <?php if ($ku_nutrisi7=="Tidak"){echo "checked";}?>>Tidak   
								<input type='checkbox' name='ku_nutrisi7' value='NGT' <?php if ($ku_nutrisi7=="NGT"){echo "checked";}?>>NGT,
								Ket penggunaan alat medis <input type='text' name='ku_nutrisi8' value='<?php echo $ku_nutrisi8;?>'> <br>
								<input type='submit' name='simpan' value='simpan'><br><br>
								Lakukan skrining nutrisi dengan Malnutrition Screening Tools (MST)<br>
								<table class='mst-table'>
									<tr>
										<th>No</th>
										<th>Uraian</th>
										<th>Skor</th>
									</tr>
									<tr>
										<td style="border: 1px solid;">1</td>
										<td style="border: 1px solid;">Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir?</td>
										<td style="border: 1px solid;"></td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">a. Tidak<input type='checkbox' name='ku_tnutrisi1' value='Tidak' <?php if ($ku_tnutrisi1=="Tidak"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi1=='Tidak'){
												echo $ku_nutrisi1_skor='0';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">b. Tidak yakin<input type='checkbox' name='ku_tnutrisi2' value='Tidak yakin' <?php if ($ku_tnutrisi2=="Tidak yakin"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi2=='Tidak yakin'){
												echo $ku_nutrisi2_skor='2';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">c. Ya, ada penurunan BB sebanyak:</td>
										<td style="border: 1px solid;"></td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">1-5 kg<input type='checkbox' name='ku_tnutrisi3' value='1-5 kg' <?php if ($ku_tnutrisi3=="1-5 kg"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi3){
												echo $ku_nutrisi3_skor='1';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">6-10 kg<input type='checkbox' name='ku_tnutrisi4' value='6-10 kg' <?php if ($ku_tnutrisi4=="6-10 kg"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi4){
												echo $ku_nutrisi4_skor='2';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">11-15 kg<input type='checkbox' name='ku_tnutrisi5' value='11-15 kg' <?php if ($ku_tnutrisi5=="11-15 kg"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi5){
												echo $ku_nutrisi5_skor='3';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">> 15 kg<input type='checkbox' name='ku_tnutrisi6' value='> 15 kg' <?php if ($ku_tnutrisi6=="> 15 kg"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi6){
												echo $ku_nutrisi6_skor='4';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">Tidak tahu berapa kg penurunannya<input type='checkbox' name='ku_tnutrisi7' value='Tidak tahu berapa kg penurunannya' <?php if ($ku_tnutrisi7=="Tidak tahu berapa kg penurunannya"){echo "checked";}?>>
										</td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi7){
												echo $ku_nutrisi7_skor='2';
											}
											?>	
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;">2</td>
										<td style="border: 1px solid;">Apakah asupan makan pasien berkurang karena penurunan nafsu makan/kesulitan menerima makanan?</td>
										<td style="border: 1px solid;"></td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">a. Tidak<input type='checkbox' name='ku_tnutrisi8' value='Tidak' <?php if ($ku_tnutrisi8=="Tidak"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi8){
												echo $ku_nutrisi8_skor='0';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">b. Ya<input type='checkbox' name='ku_tnutrisi9' value='Ya' <?php if ($ku_tnutrisi9=="Ya"){echo "checked";}?>></td>
										<td style="border: 1px solid;">
											<?php 
											if($ku_tnutrisi9){
												echo $ku_nutrisi9_skor='1';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">Total Skor

										</td>
										<td style="border: 1px solid;">
											<h3>
												<?php
												echo $ku_nutrisi9_skor_total = $ku_nutrisi1_skor+$ku_nutrisi2_skor+$ku_nutrisi3_skor+$ku_nutrisi4_skor+$ku_nutrisi5_skor+$ku_nutrisi6_skor+$ku_nutrisi7_skor+$ku_nutrisi8_skor+$ku_nutrisi9_skor;
												?>
											</h3>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;">3</td>
										<td style="border: 1px solid;">Pasien dengan diagnosa khusus : (tulis diagnosa yang sesuai pasien)<br>
										fraktur tulang panggul, sirosis hati, PPOK, Hemodialisis, diabetes, kanker, bedah digestive, stoke, pneumonia berat, cedera kepala, transplantasi, luka bakar, pasien kritis di ICU/HCU, usia lanjut, psikiatri, mendapat kemoterapi, imunitas rendah/HIV-AIDS, penyakit kronis lain.</td>
										<td style="border: 1px solid;"><input type='text' name='ku_tnutrisi10' value='<?php echo $ku_tnutrisi10;?>'> </td>
									</tr>
								</table>
								<br>
								<input class='submit-btn' type='submit' name='simpan' value='simpan'><br>
								Bila skor ≥ 2 dan/atau pasien dengan diagnosis/kondisi khusus dilakukan pengkajian lanjut oleh nutrisionis/dietisien<br>
							</td>
							<td>

								
								<br>
								<input type='checkbox' name='m6' value='Y' <?php if ($m6=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
								<?php
								$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
								WHERE     kolom='CAIRAN'";
								$hasil  = sqlsrv_query($conn, $q);	
								$no=1;
								while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

									$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

									$nm_field = 'masalah_cairan'.$no;	
									if($cek_kode_diagnosa){
										$checked = 'checked';
									}else{
										$checked='';
									}

									echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
									$no+=1;
								}
								?>
								<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>
								<br>


							</td>
						</tr>

						<tr>
							<td>
								G. AKTIVITAS DAN LATIHAN (dengan menggunakan barthel indeks)<br>

								<table class='mst-table'>
									<tr>
										<th>No</th>
										<th>Fungsi</th>
										<th>Nilai</th>
										<th>Keterangan</th>
										<th>Skor</th>
									</tr>

									<tr>
										<td style="border: 1px solid;">1</td>
										<td style="border: 1px solid;">Buang Air Besar</td>
										<td style="border: 1px solid;">0</td>
										<td style="border: 1px solid;"><input type='checkbox' name='taktivitas1' value='Tak Terkendali/Tak Teratur (Perlu Pencahar)' <?php if ($taktivitas1=="Tak Terkendali/Tak Teratur (Perlu Pencahar)"){echo "checked";}?>>
											Tak Terkendali/Tak Teratur (Perlu Pencahar)
										</td>
										<td style="border: 1px solid;">
											<?php 
											if($taktivitas1){
												echo $taktivitas1_skor='0';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">1</td>
										<td style="border: 1px solid;"><input type='checkbox' name='taktivitas2' value='Kadang-Kadang Tak Terkendali (1 X Seminggu)' <?php if ($taktivitas2=="Kadang-Kadang Tak Terkendali (1 X Seminggu)"){echo "checked";}?>>
											Kadang-Kadang Tak Terkendali (1 X Seminggu)
										</td>
										<td style="border: 1px solid;">
											<?php 
											if($taktivitas2){
												echo $taktivitas2_skor='1';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">2</td>
										<td style="border: 1px solid;"><input type='checkbox' name='taktivitas3' value='Terkendali/Teratur' <?php if ($taktivitas3=="Terkendali/Teratur"){echo "checked";}?>>
										Terkendali/Teratur</td>
										<td style="border: 1px solid;">
											<?php 
											if($taktivitas3){
												echo $taktivitas3_skor='2';
											}
											?>
										</td>
									</tr>

									<tr>
										<td style="border: 1px solid;">2</td>
										<td style="border: 1px solid;">Buang Air Kecil</td>
										<td style="border: 1px solid;">0</td>
										<td style="border: 1px solid;"><input type='checkbox' name='taktivitas4' value='Tak Terkendali Atau Pakai Kateter' <?php if ($taktivitas4=="Tak Terkendali Atau Pakai Kateter"){echo "checked";}?>>
										Tak Terkendali Atau Pakai Kateter</td>
										<td style="border: 1px solid;">
											<?php 
											if($taktivitas4){
												echo $taktivitas4_skor='0';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">1</td>
										<td style="border: 1px solid;"><input type='checkbox' name='taktivitas5' value='Kadang-Kadang Tak Terkendali (Hanya 1 X 24 Jam)' <?php if ($taktivitas5=="Kadang-Kadang Tak Terkendali (Hanya 1 X 24 Jam)"){echo "checked";}?>>
										Kadang-Kadang Tak Terkendali (Hanya 1 X 24 Jam)</td>
										<td style="border: 1px solid;"><?php 
										if($taktivitas5){
											echo $taktivitas5_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas6' value='YA' <?php if ($taktivitas6=="YA"){echo "checked";}?>>
									Terkendali/Teratur</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas6){
											echo $taktivitas6_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">Perawatan Diri</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas7' value='YA' <?php if ($taktivitas7=="YA"){echo "checked";}?>>
									Butuh Pertolongan Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas7){
											echo $taktivitas7_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas8' value='YA' <?php if ($taktivitas8=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas8){
											echo $taktivitas8_skor='1';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">4</td>
									<td style="border: 1px solid;">Penggunaan Toilet</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas9' value='YA' <?php if ($taktivitas9=="YA"){echo "checked";}?>>
									Tergantung Pertolongan Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas9){
											echo $taktivitas9_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas10' value='YA' <?php if ($taktivitas10=="YA"){echo "checked";}?>>
									Perlu Pertolongan Pada Beberapa Kegiatan Tetapi Dapat Mengerjakan Sendiri Beberapa Kegiatan Yang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas10){
											echo $taktivitas10_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas11' value='YA' <?php if ($taktivitas11=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas11){
											echo $taktivitas11_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">5</td>
									<td style="border: 1px solid;">Makan</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas12' value='YA' <?php if ($taktivitas12=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas12){
											echo $taktivitas12_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas13' value='YA' <?php if ($taktivitas13=="YA"){echo "checked";}?>>
									Perlu Di Tolong Memotong Makanan</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas13){
											echo $taktivitas13_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas14' value='YA' <?php if ($taktivitas14=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas14){
											echo $taktivitas14_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">6</td>
									<td style="border: 1px solid;">Transfer</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas15' value='YA' <?php if ($taktivitas15=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas15){
											echo $taktivitas15_skor='6';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas16' value='YA' <?php if ($taktivitas16=="YA"){echo "checked";}?>>
									Perlu Banyak Bantuan Untuk Biasa Duduk</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas16){
											echo $taktivitas16_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas17' value='YA' <?php if ($taktivitas17=="YA"){echo "checked";}?>>
									Bantuan Minimal 1 Orang</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas17){
											echo $taktivitas17_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas18' value='YA' <?php if ($taktivitas18=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas18){
											echo $taktivitas18_skor='3';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">7</td>
									<td style="border: 1px solid;">Mobilitas</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas19' value='YA' <?php if ($taktivitas19=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas19){
											echo $taktivitas19_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas20' value='YA' <?php if ($taktivitas20=="YA"){echo "checked";}?>>
									Bisa (Pindah) Dengan Kursi Roda</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas20){
											echo $taktivitas20_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas21' value='YA' <?php if ($taktivitas21=="YA"){echo "checked";}?>>
									Berjalan Dengan Bantuan 1 Orang</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas21){
											echo $taktivitas21_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas22' value='YA' <?php if ($taktivitas22=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas22){
											echo $taktivitas22_skor='3';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">8</td>
									<td style="border: 1px solid;">Berpakaian</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas23' value='YA' <?php if ($taktivitas23=="YA"){echo "checked";}?>>
									Tergantung Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas23){
											echo $taktivitas23_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas24' value='YA' <?php if ($taktivitas24=="YA"){echo "checked";}?>>
									Sebagian Dibantu (Misal : Memakai Baju)</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas24){
											echo $taktivitas24_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas25' value='YA' <?php if ($taktivitas25=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas25){
											echo $taktivitas25_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">9</td>
									<td style="border: 1px solid;">Naik Turun Tangga</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas26' value='YA' <?php if ($taktivitas26=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas26){
											echo $taktivitas26_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas27' value='YA' <?php if ($taktivitas27=="YA"){echo "checked";}?>>
									Butuh Pertolongan</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas27){
											echo $taktivitas27_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas28' value='YA' <?php if ($taktivitas28=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas28){
											echo $taktivitas28_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">10</td>
									<td style="border: 1px solid;">Mandi</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas29' value='YA' <?php if ($taktivitas29=="YA"){echo "checked";}?>>
									Tergantung Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas29){
											echo $taktivitas29_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='checkbox' name='taktivitas30' value='YA' <?php if ($taktivitas30=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($taktivitas30){
											echo $taktivitas30_skor='1';
										}
										?>
									</td>
								</tr>


							</table>
							<br>
							[]12 – 20 : Minimal Care [] 9 – 11 : Partial Care [] 0 – 8 : Total Care
							<input type='submit' class='submit-btn' name='simpan' value='simpan'>&nbsp;
							<?php 
							echo "<h5>";
							echo "[";
							echo $taktivitas_skor_total=$taktivitas1_skor+$taktivitas2_skor+$taktivitas3_skor+$taktivitas4_skor+$taktivitas5_skor+$taktivitas6_skor+$taktivitas7_skor+$taktivitas8_skor+$taktivitas9_skor+$taktivitas10_skor+$taktivitas11_skor+$taktivitas12_skor+$taktivitas13_skor+$taktivitas14_skor+$taktivitas15_skor+$taktivitas16_skor+$taktivitas17_skor+$taktivitas18_skor+$taktivitas19_skor+$taktivitas20_skor+$taktivitas21_skor+$taktivitas22_skor+$taktivitas23_skor+$taktivitas24_skor+$taktivitas25_skor+$taktivitas26_skor+$taktivitas27_skor+$taktivitas28_skor+$taktivitas29_skor+$taktivitas30_skor;
							echo "] - ";

							if($taktivitas_skor_total >= 12 and $taktivitas_skor_total <=20 ){echo $ket_taktivitas_skor="Minimal Care";}
							if($taktivitas_skor_total >= 9 and $taktivitas_skor_total <=11 ){echo $ket_taktivitas_skor="Partial Care";}
							if($taktivitas_skor_total >= 0 and $taktivitas_skor_total <=8 ){echo $ket_taktivitas_skor="Total Care";}
							echo "</h5>";
							?>
							<br>
						</td>
						<td>

							<br>
							<input type='checkbox' name='m7' value='Y' <?php if ($m7=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
							<?php
							$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
							WHERE     kolom='AKTIVITAS'";
							$hasil  = sqlsrv_query($conn, $q);	
							$no=1;
							while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

								$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

								$nm_field = 'masalah_aktivitas'.$no;	
								if($cek_kode_diagnosa){
									$checked = 'checked';
								}else{
									$checked='';
								}

								echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>


						</td>
					</tr>


					<tr>
						<td>
							H. INTEGRITAS KULIT DAN HYGIENE<br>
							Warna :  
							<input type='checkbox' name='kulit1' value='Normal' <?php if ($kulit1=="Normal"){echo "checked";}?>>Normal  
							<input type='checkbox' name='kulit1' value='Ikterik' <?php if ($kulit1=="Ikterik"){echo "checked";}?>>Ikterik    
							<input type='checkbox' name='kulit1' value='Sianosis' <?php if ($kulit1=="Sianosis"){echo "checked";}?>>Sianosis 
							<br> 
							Oedem:    
							<input type='checkbox' name='kulit2' value='Tidak' <?php if ($kulit2=="Tidak"){echo "checked";}?>>Tidak      
							<input type='checkbox' name='kulit2' value='Ya' <?php if ($kulit2=="Ya"){echo "checked";}?>>Ya, 
							lokasi : <input type='text' name='kulit3' value='<?php echo $kulit3;?>'><br>
							Turgor :   
							<input type='checkbox' name='kulit4' value='Tidak ada kelainan' <?php if ($kulit4=="Tidak ada kelainan"){echo "checked";}?>>Tidak ada kelainan 
							<input type='checkbox' name='kulit4' value='Ada kelainan' <?php if ($kulit4=="Ada kelainan"){echo "checked";}?>>Ada kelainan     
							<br>
							Luka :  
							<input type='checkbox' name='kulit5' value='Tidak' <?php if ($kulit5=="Tidak"){echo "checked";}?>>Tidak    
							<input type='checkbox' name='kulit5' value='Ya' <?php if ($kulit6=="Ya"){echo "checked";}?>>Ya, 
							lokasi : (
							lengkapi 
							<a href='asesmen_luka.php?id=<?php echo $id.'|'.$user;?>'>form assesmen luka</a>
							)

							<br>
							<input type='submit' name='simpan' value='simpan'>
							<br>
							Asesmen Risiko Dekubitus (dengan skala norton)<br>

							<table class="mst-table">
								<tr>
									<th>No</th>
									<th>Parameter</th>
									<th>Kondisi</th>
									<th>Skor</th>
								</tr>

								<tr>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">Kondisi Fisik</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit1' value='YA' <?php if ($tkulit1=="YA"){echo "checked";}?>>
									Baik, pasien tidak mengalami cacat atau kelemahan fisik</td>
									<td style="border: 1px solid;">4</td>
									<?php 
									if($tkulit1){
										$tkulit1_skor='1';
									}
									?>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit2' value='YA' <?php if ($tkulit2=="YA"){echo "checked";}?>>
									Cukup</td>
									<td style="border: 1px solid;">3</td>
									<?php 
									if($tkulit2){
										$tkulit2_skor='3';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit3' value='YA' <?php if ($tkulit3=="YA"){echo "checked";}?>>
									Buruk</td>
									<td style="border: 1px solid;">2</td>
									<?php 
									if($tkulit3){
										$tkulit3_skor='2';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit4' value='YA' <?php if ($tkulit4=="YA"){echo "checked";}?>>
									Sangat Buruk</td>
									<td style="border: 1px solid;">1</td>
									<?php 
									if($tkulit4){
										$tkulit4_skor='1';
									}
									?>

								</tr>

								<tr>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">Kondisi mental</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit5' value='YA' <?php if ($tkulit5=="YA"){echo "checked";}?>>
									Composmentis</td>
									<td style="border: 1px solid;">4</td>
									<?php 
									if($tkulit5){
										$tkulit5_skor='4';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit6' value='YA' <?php if ($tkulit6=="YA"){echo "checked";}?>>
									Apatis</td>
									<td style="border: 1px solid;">3</td>
									<?php 
									if($tkulit6){
										$tkulit6_skor='3';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit7' value='YA' <?php if ($tkulit7=="YA"){echo "checked";}?>>
									Gelisah, disorientasi, sopor</td>
									<td style="border: 1px solid;">2</td>
									<?php 
									if($tkulit7){
										$tkulit7_skor='2';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit8' value='YA' <?php if ($tkulit8=="YA"){echo "checked";}?>>
									Koma/stupor</td>
									<td style="border: 1px solid;">1</td>
									<?php 
									if($tkulit8){
										$tkulit8_skor='1';
									}
									?>

								</tr>

								<tr>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">Rentang aktifitas</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit9' value='YA' <?php if ($tkulit9=="YA"){echo "checked";}?>>
									Ambulasi /pasien bisa bergerak bebas</td>
									<td style="border: 1px solid;">4</td>
									<?php 
									if($tkulit9){
										$tkulit9_skor='4';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit10' value='YA' <?php if ($tkulit10=="YA"){echo "checked";}?>>
									Berjalan dengan alat bantu, misal kruk, tripot, dll</td>
									<td style="border: 1px solid;">3</td>
									<?php 
									if($tkulit10){
										$tkulit10_skor='3';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit11' value='YA' <?php if ($tkulit11=="YA"){echo "checked";}?>>
									Hanya bisa beraktifitas duduk</td>
									<td style="border: 1px solid;">2</td>
									<?php 
									if($tkulit11){
										$tkulit11_skor='2';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit12' value='YA' <?php if ($tkulit12=="YA"){echo "checked";}?>>
									Pasien bedrest</td>
									<td style="border: 1px solid;">1</td>
									<?php 
									if($tkulit12){
										$tkulit12_skor='1';
									}
									?>

								</tr>

								<tr>
									<td style="border: 1px solid;">4</td>
									<td style="border: 1px solid;">Mobilitas</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit13' value='YA' <?php if ($tkulit13=="YA"){echo "checked";}?>>
									Masih bergerak bebas</td>
									<td style="border: 1px solid;">4</td>
									<?php 
									if($tkulit13){
										$tkulit13_skor='4';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit14' value='YA' <?php if ($tkulit14=="YA"){echo "checked";}?>>
									Ada keterbatasan gerak tetapi tidak memerlukan bantuan</td>
									<td style="border: 1px solid;">3</td>
									<?php 
									if($tkulit14){
										$tkulit14_skor='3';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit15' value='YA' <?php if ($tkulit15=="YA"){echo "checked";}?>>
									Bergerak sangat terbatas dan memerlukan bantuan minimal</td>
									<td style="border: 1px solid;">2</td>
									<?php 
									if($tkulit15){
										$tkulit15_skor='2';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit16' value='YA' <?php if ($tkulit16=="YA"){echo "checked";}?>>
									Imobilitas (bantuan penuh)</td>
									<td style="border: 1px solid;">1</td>
									<?php 
									if($tkulit16){
										$tkulit16_skor='1';
									}
									?>

								</tr>

								<tr>
									<td style="border: 1px solid;">5</td>
									<td style="border: 1px solid;">Inkotinentia</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit17' value='YA' <?php if ($tkulit17=="YA"){echo "checked";}?>>
									BAB dan BAK normal</td>
									<td style="border: 1px solid;">4</td>
									<?php 
									if($tkulit17){
										$tkulit17_skor='4';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit18' value='YA' <?php if ($tkulit18=="YA"){echo "checked";}?>>
									Kadang kesulitan BAB dan BAK</td>
									<td style="border: 1px solid;">3</td>
									<?php 
									if($tkulit18){
										$tkulit18_skor='3';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit19' value='YA' <?php if ($tkulit19=="YA"){echo "checked";}?>>
									Pasien mengalami inkontinentia berkemih</td>
									<td style="border: 1px solid;">2</td>
									<?php 
									if($tkulit19){
										$tkulit19_skor='2';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tkulit20' value='YA' <?php if ($tkulit20=="YA"){echo "checked";}?>>
									Pasien mengalami inkontinentia BAB</td>
									<td style="border: 1px solid;">1</td>
									<?php 
									if($tkulit20){
										$tkulit20_skor='1';
									}
									?>

								</tr>
								<tr>
									<td style="border: 1px solid;" colspan="3">Total Skor</td>
									<td style="border: 1px solid;">
										<?php
										echo										$tkulit_skor_total=$tkulit1_skor+$tkulit2_skor+$tkulit3_skor+$tkulit4_skor+$tkulit5_skor+$tkulit6_skor+$tkulit7_skor+$tkulit8_skor+$tkulit9_skor+$tkulit10_skor+$tkulit11_skor+$tkulit12_skor+$tkulit13_skor+$tkulit14_skor+$tkulit15_skor+$tkulit16_skor+$tkulit17_skor+$tkulit18_skor+$tkulit19_skor+$tkulit20_skor;
										?>

									</td>
								</tr>

							</table>
							<input type='submit' class='submit-btn' name='simpan' value='simpan'>&nbsp;
							<br>
							Total Skor :   []5 – 10 : Risiko tinggi       []11 – 15 : Risiko sedang      []16 – 20 : Risiko rendah
							<?php 
							echo "<h5>";
							echo "[".$tkulit_skor_total."]";

							if($tkulit_skor_total >= 5 and $tkulit_skor_total <=10 ){echo $tkulit6_skor_ket="Risiko tinggi";}
							if($tkulit_skor_total >= 11 and $tkulit_skor_total <=15 ){echo $tkulit6_skor_ket="Risiko sedang";}
							if($tkulit_skor_total >= 16 and $tkulit_skor_total <=20 ){echo $tkulit6_skor_ket="Risiko rendah";}
							echo "</h5>";
							?>
							<br>
							(jika total skor kurang dari 15 lakukan tindakan pencegahan dekubitus dan jika sudah ada luka maka lanjutkan dengan form asesmen luka).
						</td>
						<td>

							<br>
							<input type='checkbox' name='m8' value='Y' <?php if ($m8=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
							<?php
							$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
							WHERE     kolom='KULIT'";
							$hasil  = sqlsrv_query($conn, $q);	
							$no=1;
							while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

								$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

								$nm_field = 'masalah_kulit'.$no;	
								if($cek_kode_diagnosa){
									$checked = 'checked';
								}else{
									$checked='';
								}

								echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>


						</td>
					</tr>

					<tr>
						<td>

							I. ENDOKRIN<br>

							Pembesaran kel. Thyroid       :   
							<input type='radio' name='endokrin1' value='Tidak' <?php if ($endokrin1=="Tidak"){echo "checked";}?>> Tidak     
							<input type='radio' name='endokrin1' value='Ya' <?php if ($endokrin1=="Ya"){echo "checked";}?>> Ya<br>
							Pembesaran kel. Getah bening :     
							<input type='radio' name='endokrin2' value='Tidak' <?php if ($endokrin2=="Tidak"){echo "checked";}?>>Tidak      
							<input type='radio' name='endokrin2' value='Ya' <?php if ($endokrin2=="Ya"){echo "checked";}?>>Ya<br>
							Hiperglikemi   :  
							<input type='radio' name='endokrin3' value='Tidak' <?php if ($endokrin3=="Tidak"){echo "checked";}?>>Tidak      
							<input type='radio' name='endokrin3' value='Ya' <?php if ($endokrin3=="Ya"){echo "checked";}?>>Ya,
							sebutkan : <input type='text' name='' value='<?php echo $endokrin4;?>'><br>
							Hipoglikemi   :  
							<input type='radio' name='endokrin4' value='Tidak' <?php if ($endokrin4=="Tidak"){echo "checked";}?>>Tidak      
							<input type='radio' name='endokrin4' value='Ya' <?php if ($endokrin4=="Ya"){echo "checked";}?>>Ya,
							sebutkan : <input type='text' name='' value='<?php echo $endokrin4;?>'><br>
							<input type='submit' name='simpan' value='simpan'>&nbsp;

						</td>
						<td>

							<br>
							<input type='checkbox' name='m9' value='Y' <?php if ($m9=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
							<?php
							$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
							WHERE     kolom='ENDOKTRIN'";
							$hasil  = sqlsrv_query($conn, $q);	
							$no=1;
							while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

								$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

								$nm_field = 'masalah_endoktrin'.$no;	
								if($cek_kode_diagnosa){
									$checked = 'checked';
								}else{
									$checked='';
								}

								echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>


						</td>
					</tr>

					<tr>
						<td>
							J. REPRODUKSI<br>
							Wanita    : 
							<input type='radio' name='reproduksi1' value='Tidak ada kelainan' <?php if ($reproduksi1=="Tidak ada kelainan"){echo "checked";}?>>Tidak ada kelainan
							<input type='radio' name='reproduksi1' value='Sedang Hamil' <?php if ($reproduksi1=="Sedang Hamil"){echo "checked";}?>>Sedang Hamil<br>
							Menstruasi pertama :   
							<input type='radio' name='reproduksi2' value='Teratur' <?php if ($reproduksi2=="Teratur"){echo "checked";}?>>Teratur  
							<input type='radio' name='reproduksi2' value='Tidak Teratur' <?php if ($reproduksi2=="Tidak Teratur"){echo "checked";}?>>Tidak Teratur<br>
							Laki – Laki : 
							<input type='radio' name='reproduksi3' value='Tidak ada kelainan' <?php if ($reproduksi3=="Tidak ada kelainan"){echo "checked";}?>>Tidak ada kelainan   
							<input type='radio' name='reproduksi3' value='Ada kelainan' <?php if ($reproduksi3=="Ada kelainan"){echo "checked";}?>>Ada Kelainan<br>
							<input type='submit' name='simpan' value='simpan'>&nbsp;

						</td>
						<td>
							&nbsp;
						</td>
					</tr>


					<tr>
						<td>
							K. KEAMANAN DAN KENYAMANAN<br>

							1. Risiko Jatuh<br>
							(dewasa dengan skala Morse)<br>

							<table class="mst-table">
								<tr>
									<th>Faktor Risiko</th>
									<th>skala</th>
									<th>poin</th>
									<th>Skor pasien</th>
								</tr>

								<tr>
									<td style="border: 1px solid;">Riwayat jatuh</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh1' value='YA' <?php if ($tjatuh1=="YA"){echo "checked";}?>>Ya</td>
									<td style="border: 1px solid;">25</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh1){
											echo $tjatuh1_skor='25';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh2' value='YA' <?php if ($tjatuh2=="YA"){echo "checked";}?>>Tidak</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh2){
											echo $tjatuh2_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;">Diagnosis skunder (≥diagnosis medis)</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh3' value='YA' <?php if ($tjatuh3=="YA"){echo "checked";}?>>Ya</td>
									<td style="border: 1px solid;">15</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh3){
											echo $tjatuh3_skor='15';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh4' value='YA' <?php if ($tjatuh4=="YA"){echo "checked";}?>>Tidak</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh4){
											echo $tjatuh4_skor='0';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">Alat bantu</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh5' value='YA' <?php if ($tjatuh5=="YA"){echo "checked";}?>>Berpegangan pada perabot, kursi roda</td>
									<td style="border: 1px solid;">30</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh5){
											echo $tjatuh5_skor='30';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh6' value='YA' <?php if ($tjatuh6=="YA"){echo "checked";}?>>Tongkat/walker</td>
									<td style="border: 1px solid;">15</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh6){
											echo $tjatuh6_skor='15';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh7' value='YA' <?php if ($tjatuh7=="YA"){echo "checked";}?>>Tidak ada/perawat/tirah baring</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh7){
											echo $tjatuh7_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;">Terpasang infus/terapi intravena </td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh8' value='YA' <?php if ($tjatuh8=="YA"){echo "checked";}?>>Ya</td>
									<td style="border: 1px solid;">20</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh8){
											echo $tjatuh8_skor='20';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh9' value='YA' <?php if ($tjatuh9=="YA"){echo "checked";}?>>Tidak</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh9){
											echo $tjatuh9_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;">Gaya berjalan</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh10' value='YA' <?php if ($tjatuh10=="YA"){echo "checked";}?>>Kerusakan</td>
									<td style="border: 1px solid;">20</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh10){
											echo $tjatuh10_skor='20';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh11' value='YA' <?php if ($tjatuh11=="YA"){echo "checked";}?>>Kelemahan</td>
									<td style="border: 1px solid;">10</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh11){
											echo $tjatuh11_skor='10';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh12' value='YA' <?php if ($tjatuh12=="YA"){echo "checked";}?>>Normal /tirah baring/imobilisasi</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh12){
											echo $tjatuh12_skor='0';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">Status mental</td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh13' value='YA' <?php if ($tjatuh13=="YA"){echo "checked";}?>>Tidak konsisten dengan perintah</td>
									<td style="border: 1px solid;">15</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh13){
											echo $tjatuh13_skor='15';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='tjatuh14' value='YA' <?php if ($tjatuh14=="YA"){echo "checked";}?>>Sadar akan kemampuan diri sendiri</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($tjatuh14){
											echo $tjatuh14_skor='0';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;" colspan="3">Total Skor</td>
									<td style="border: 1px solid;">
										<?php 
										echo $tjatuh_skor_total=$tjatuh1_skor+$tjatuh2_skor+$tjatuh3_skor+$tjatuh4_skor+$tjatuh5_skor+$tjatuh6_skor+$tjatuh7_skor+$tjatuh8_skor+$tjatuh9_skor+$tjatuh10_skor+$tjatuh11_skor+$tjatuh12_skor+$tjatuh13_skor+$tjatuh14_skor; 
										?>
									</td>
								</tr>

							</table>
							<input type='submit' class='submit-btn' name='simpan' value='simpan'>&nbsp;

							<br>
							[] >45 : Risiko tinggi ;         [] 25-44 : Risiko sedang ;         [] 0 -24 : Risiko rendah
							<br>
							<?php 
							echo "<h5>";
							echo "[".$tjatuh_skor_total."]";

							if($tjatuh_skor_total >= 0 and $tjatuh_skor_total <= 24){echo $tjatuh_skor_total="Risiko rendah";}
							if($tjatuh_skor_total >= 25 and $tjatuh_skor_total <= 44 ){echo $tjatuh_skor_total="Risiko sedang";}
							if($tjatuh_skor_total >= 45){echo $tjatuh_skor_total="Risiko tinggi";}
							echo "</h5>";

							?>

							<br>
							2. Restrain :<br>
							Pernah menggunakan restrain sebelumnya : 
							<input type='radio' name='asdewasa1' value='Tidak' <?php if ($asdewasa1=="Tidak"){echo "checked";}?>>Tidak    
							<input type='radio' name='asdewasa1' value='Ya' <?php if ($asdewasa1=="Ya"){echo "checked";}?>>Ya<br>
							Kondisi Pasien  :   
							<input type='checkbox' name='asdewasa2' value='Kooperatif' <?php if ($asdewasa2=="Kooperatif"){echo "checked";}?>>Kooperatif
							<input type='checkbox' name='asdewasa2' value='Gelisah' <?php if ($asdewasa2=="Gelisah"){echo "checked";}?>>Gelisah     
							<input type='checkbox' name='asdewasa2' value='Delirium' <?php if ($asdewasa2=="Delirium"){echo "checked";}?>>Delirium    
							<input type='checkbox' name='asdewasa2' value='Berontak' <?php if ($asdewasa2=="Berontak"){echo "checked";}?>>Berontak<br>
							Kondisi saat ini berisiko :   
							<input type='radio' name='asdewasa4' value='Tidak' <?php if ($asdewasa4=="Tidak"){echo "checked";}?>>Tidak     
							<input type='radio' name='asdewasa4' value='Ya' <?php if ($asdewasa4=="Ya"){echo "checked";}?>>Ya, lanjutan dengan pengkajian restrain.<br>
							<input type='submit' name='simpan' value='simpan'>
						</td>
						<td>

							<br>
							<input type='checkbox' name='m10' value='Y' <?php if ($m10=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
							<?php
							$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
							WHERE     kolom='JATUH'";
							$hasil  = sqlsrv_query($conn, $q);	
							$no=1;
							while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

								$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

								$nm_field = 'masalah_jatuh'.$no;	
								if($cek_kode_diagnosa){
									$checked = 'checked';
								}else{
									$checked='';
								}

								echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>

						</td>
					</tr>

					<tr>
						<td>
							L. ISTIRAHAT DAN TIDUR<br>
							Lama tidur          :    
							<input type='radio' name='asdewasa5' value='Tidak ada kelainan' <?php if ($asdewasa5=="Tidak ada kelainan"){echo "checked";}?>>Tidak ada kelainan     
							<input type='radio' name='asdewasa5' value='Ada kelainan' <?php if ($asdewasa5=="Ada kelainan"){echo "checked";}?>>Ada kelainan :
							<input type='text' name='asdewasa3' value='<?php echo $asdewasa3;?>' placeholder='Kelainan Istirahat / Tidur'>
							<br>
							Gangguan istirahat    :    
							<input type='radio' name='asdewasa6' value='Tidak ada kelainan' <?php if ($asdewasa6=="Tidak ada kelainan"){echo "checked";}?>>Tidak ada kelainan     
							<input type='radio' name='asdewasa6' value='Ada kelainan' <?php if ($asdewasa6=="Ada kelainan"){echo "checked";}?>>Ada kelainan :<br>
							Penggunaan obat tidur  :    
							<input type='radio' name='asdewasa7' value='Tidak ada kelainan' <?php if ($asdewasa7=="Tidak ada kelainan"){echo "checked";}?>>Tidak ada kelainan    
							<input type='radio' name='asdewasa7' value='Ada kelainan' <?php if ($asdewasa7=="Ada kelainan"){echo "checked";}?>>Ada kelainan :<br>
							<input type='submit' name='simpan' value='simpan'>
						</td>
						<td>

							<br>
							<input type='checkbox' name='m11' value='Y' <?php if ($m11=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
							<?php
							$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
							WHERE     kolom='TIDUR'";
							$hasil  = sqlsrv_query($conn, $q);	
							$no=1;
							while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

								$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

								$nm_field = 'masalah_tidur'.$no;	
								if($cek_kode_diagnosa){
									$checked = 'checked';
								}else{
									$checked='';
								}

								echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>

						</td>
					</tr>


					<tr>
						<td>
							M. KEBUTUHAN KOMUNIKASI, KOGNISI DAN EDUKASI<br>
							Bicara :   
							<input type='radio' name='asdewasa8' value='Normal' <?php if ($asdewasa8=="Normal"){echo "checked";}?>>Normal      
							<input type='radio' name='asdewasa8' value='Gangguan Bicara' <?php if ($asdewasa8=="Gangguan Bicara"){echo "checked";}?>>Gangguan Bicara :<br>
							Mulai kapan : <input type='text' name='asdewasa9' value='<?php echo $asdewasa9;?>'><br>
							Memerlukan Bahasa Isyarat :    
							<input type='radio' name='asdewasa10' value='Tidak' <?php if ($asdewasa10=="Tidak"){echo "checked";}?>>Tidak     
							<input type='radio' name='asdewasa10' value='Ya' <?php if ($asdewasa10=="Ya"){echo "checked";}?>>Ya<br>
							Hambatan Belajar : 
							<input type='checkbox' name='asdewasa11' value='Bahasa' <?php if ($asdewasa11=="Bahasa"){echo "checked";}?>>Bahasa     
							<input type='checkbox' name='asdewasa11' value='Pendengaran' <?php if ($asdewasa11=="Pendengaran"){echo "checked";}?>>Pendengaran    
							<input type='checkbox' name='asdewasa11' value='Hilang Memori' <?php if ($asdewasa11=="Hilang Memori"){echo "checked";}?>>Hilang Memori<br>
							<input type='submit' name='simpan' value='simpan'>
						</td>
						<td>

							<br>
							<input type='checkbox' name='m12' value='Y' <?php if ($m12=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
							<?php
							$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
							WHERE     kolom='KOMUNIKASI'";
							$hasil  = sqlsrv_query($conn, $q);	
							$no=1;
							while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

								$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

								$nm_field = 'masalah_komunikasi'.$no;	
								if($cek_kode_diagnosa){
									$checked = 'checked';
								}else{
									$checked='';
								}

								echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>



						</td>
					</tr>

					<tr>
						<td>
							N. PSIKOSOSIAL, SPIRITUAL DAN EKONOMI<br>
							Keadaan Psikologis   :    
							<input type='radio' name='asdewasa12' value='Kooperatif' <?php if ($asdewasa12=="Kooperatif"){echo "checked";}?>>Kooperatif      
							<input type='radio' name='asdewasa12' value='Sedih' <?php if ($asdewasa12=="Sedih"){echo "checked";}?>>Sedih   
							<input type='radio' name='asdewasa12' value='Marah' <?php if ($asdewasa12=="Marah"){echo "checked";}?>>Marah
							<input type='radio' name='asdewasa12' value='Agitasi' <?php if ($asdewasa12=="Agitasi"){echo "checked";}?>>Agitasi        
							<input type='radio' name='asdewasa12' value='Cemas' <?php if ($asdewasa12=="Cemas"){echo "checked";}?>>Cemas   
							<input type='radio' name='asdewasa12' value='Gelisah' <?php if ($asdewasa12=="Gelisah"){echo "checked";}?>>Gelisah
							<input type='radio' name='asdewasa12' value='Disorientasi' <?php if ($asdewasa12=="Disorientasi"){echo "checked";}?>>Disorientasi:    
							<input type='radio' name='asdewasa12' value='Orang' <?php if ($asdewasa12=="Orang"){echo "checked";}?>>Orang   
							<input type='radio' name='asdewasa12' value='Tempat' <?php if ($asdewasa12=="Tempat"){echo "checked";}?>>Tempat   
							<input type='radio' name='asdewasa12' value='Waktu' <?php if ($asdewasa12=="Waktu"){echo "checked";}?>>Waktu<br>
							Tingkat Pendidikan :    
							<input type='radio' name='asdewasa13' value='Belum Sekolah' <?php if ($asdewasa13=="Belum Sekolah"){echo "checked";}?>>Belum Sekolah   
							<input type='radio' name='asdewasa13' value='SD' <?php if ($asdewasa13=="SD"){echo "checked";}?>>SD     
							<input type='radio' name='asdewasa13' value='SMP' <?php if ($asdewasa13=="SMP"){echo "checked";}?>>SMP
							<input type='radio' name='asdewasa13' value='SMA' <?php if ($asdewasa13=="SMA"){echo "checked";}?>>SMA           
							<input type='radio' name='asdewasa13' value='Diploma' <?php if ($asdewasa13=="Diploma"){echo "checked";}?>>Diploma  
							<input type='radio' name='asdewasa13' value='Sarjana' <?php if ($asdewasa13=="Sarjana"){echo "checked";}?>>Sarjana<br>
							Pekerjaan         :    
							<input type='radio' name='asdewasa14' value='Wiraswasta' <?php if ($asdewasa14=="Wiraswasta"){echo "checked";}?>>Wiraswasta      
							<input type='radio' name='asdewasa14' value='Swasta' <?php if ($asdewasa14=="Swasta"){echo "checked";}?>>Swasta   
							<input type='radio' name='asdewasa14' value='Peg.Negeri' <?php if ($asdewasa14=="Peg.Negeri"){echo "checked";}?>>Peg.Negeri<br>
							Tinggal Bersama   :    
							<input type='radio' name='asdewasa15' value='Suami/istri ' <?php if ($asdewasa15=="Suami/istri "){echo "checked";}?>>Suami/istri      
							<input type='radio' name='asdewasa15' value='Anak' <?php if ($asdewasa15=="Anak"){echo "checked";}?>>Anak   
							<input type='radio' name='asdewasa15' value='Teman' <?php if ($asdewasa15=="Teman"){echo "checked";}?>>Teman
							<input type='radio' name='asdewasa15' value='Orang Tua ' <?php if ($asdewasa15=="Orang Tua "){echo "checked";}?>>Orang Tua       
							<input type='radio' name='asdewasa15' value='Sendiri' <?php if ($asdewasa15=="Sendiri"){echo "checked";}?>>Sendiri  
							<input type='radio' name='asdewasa15' value='Caregiver' <?php if ($asdewasa15=="Caregiver"){echo "checked";}?>>Caregiver<br>
							Status Ekonomi    :    
							<input type='radio' name='asdewasa16' value='Pembayaran Pribadi/Perorangan' <?php if ($asdewasa16=="Pembayaran Pribadi/Perorangan"){echo "checked";}?>>Pembayaran Pribadi/Perorangan
							<input type='radio' name='asdewasa16' value='Jaminan kesehatan/Asuransi' <?php if ($asdewasa16=="Jaminan kesehatan/Asuransi"){echo "checked";}?>>Jaminan kesehatan/Asuransi<br>
							Spiritual<br>
							Menjalankan ibadah    :   
							<input type='radio' name='asdewasa17' value='Ada Hambatan' <?php if ($asdewasa17=="Ada Hambatan"){echo "checked";}?>>Ada Hambatan    :   
							<input type='radio' name='asdewasa17' value='Tidak ada hambatan' <?php if ($asdewasa17=="Tidak ada hambatan"){echo "checked";}?>>Tidak ada hambatan<br>
							Persepsi terhadap Sakit :   
							<input type='radio' name='asdewasa18' value='Tidak Ada Keluhan' <?php if ($asdewasa18=="Tidak Ada Keluhan"){echo "checked";}?>>Tidak Ada Keluhan     
							<input type='radio' name='asdewasa18' value='Rasa Bersalah' <?php if ($asdewasa18=="Rasa Bersalah"){echo "checked";}?>>Rasa Bersalah<br>
							Meminta Pelayanan Spiritual :    
							<input type='radio' name='asdewasa19' value='Tidak Ada Keluhan' <?php if ($asdewasa19=="Tidak Ada Keluhan"){echo "checked";}?>>Tidak Ada Keluhan      
							<input type='radio' name='asdewasa19' value='Ya' <?php if ($asdewasa19=="Ya"){echo "checked";}?>>Ya, (lakukan kolaborasi dengan bagian kerohanian)<br>
							<input type='submit' name='simpan' value='simpan'>
						</td>
						<td>

							<br>
							<input type='checkbox' name='m13' value='Y' <?php if ($m13=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
							<?php
							$q="SELECT distinct kode_diagnosa,diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN
							WHERE     kolom='PSIKOLOGI'";
							$hasil  = sqlsrv_query($conn, $q);	
							$no=1;
							while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){

								$qu="SELECT diagnosa_keperawatan FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$data[kode_diagnosa]' and noreg='$noreg' ";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$cek_kode_diagnosa = trim($d1u['diagnosa_keperawatan']);

								$nm_field = 'masalah_psikologi'.$no;	
								if($cek_kode_diagnosa){
									$checked = 'checked';
								}else{
									$checked='';
								}

								echo "<input type='checkbox' name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<button type='submit' name='update_mas' value='update_mas' class="btn btn-info" type="button" style="height: 40px;width: 270px;"><i class="bi bi-save-fill"></i> simpan masalah keperawatan</button>



						</td>
					</tr>

					<tr>
						<td>
							O. DISCHARGE PLANNING<br>

							<table class="mst-table">
								<tr>
									<th>No</th>
									<th>Keterangan</th>
									<th>Ya</th>
									<th>Tidak</th>
								</tr>

								<tr>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">Usia ≥60 tahun</td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa20' value='Ya' <?php if ($asdewasa20=="Ya"){echo "checked";}?>></td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa20' value='Tidak' <?php if ($asdewasa20=="Tidak"){echo "checked";}?>></td>
								</tr>
								<tr>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">Tinggal sendirian tanpa dukungan sosial secara langsung</td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa21' value='Ya' <?php if ($asdewasa21=="Ya"){echo "checked";}?>></td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa21' value='Tidak' <?php if ($asdewasa21=="Tidak"){echo "checked";}?>></td>
								</tr>
								<tr>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">Stroke, serangan Jantung, PPOK, gagal jantung kongestif, empisema demensia, TB, alzeimer, AIDS, atau penyakit dengan potensi mengancam nyawa lainnya</td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa22' value='Ya' <?php if ($asdewasa22=="Ya"){echo "checked";}?>></td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa22' value='Tidak' <?php if ($asdewasa22=="Tidak"){echo "checked";}?>></td>
								</tr>
								<tr>
									<td style="border: 1px solid;">4</td>
									<td style="border: 1px solid;">Korban dari kasus criminal</td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa23' value='Ya' <?php if ($asdewasa23=="Ya"){echo "checked";}?>></td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa23' value='Tidak' <?php if ($asdewasa23=="Tidak"){echo "checked";}?>></td>
								</tr>
								<tr>
									<td style="border: 1px solid;">5</td>
									<td style="border: 1px solid;">Trauma Multiple</td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa24' value='Ya' <?php if ($asdewasa24=="Ya"){echo "checked";}?>></td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa24' value='Tidak' <?php if ($asdewasa24=="Tidak"){echo "checked";}?>></td>
								</tr>
								<tr>
									<td style="border: 1px solid;">6</td>
									<td style="border: 1px solid;">Memerlukan Perawatan atau pengobatan berkelanjutan</td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa25' value='Ya' <?php if ($asdewasa25=="Ya"){echo "checked";}?>></td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa25' value='Tidak' <?php if ($asdewasa25=="Tidak"){echo "checked";}?>></td>
								</tr>
								<tr>
									<td style="border: 1px solid;">7</td>
									<td style="border: 1px solid;">Pasien lansia yang ada dilantai atas (saat dirumah)</td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa26' value='Ya' <?php if ($asdewasa26=="Ya"){echo "checked";}?>></td>
									<td style="border: 1px solid;"><input type='radio' name='asdewasa26' value='Tidak' <?php if ($asdewasa26=="Tidak"){echo "checked";}?>></td>
								</tr>


								<tr>
									<td style="border: 1px solid;" colspan="4">Bila salah satu jawaban adalah ya, dilanjutkan pengisian rm 18a.10 discharge planning terintegrasi</td>
								</tr>

							</table>
							<input type='submit' class='submit-btn' name='simpan' value='simpan'>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>


					<tr>
						<td>
							<br>

							<br>
							<br>
							<br>
							Selesai asesmen tanggal :<?php echo $tgl_assesment; ?> , Jam : <?php echo $jam_assesment; ?>
							<br>
							<table border='0' width="100%">

								<tr>
									<td width='50%' align="center">
										Perawat yang melakukan Asesmen
									</td>
									<td align="center">
										DPJP										
									</td>
								</tr>
								<tr>
									<td align="center">
										<?php 
					// $qu="SELECT  NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where [user1] like '%$userid%'";
										$qu="SELECT  NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where [user1] = '$userid'";
										$h1u  = sqlsrv_query($conn, $qu);        
										$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
										$NamaUser = trim($d1u['NamaUser']);
										?>

										<?php $verif_perawat="Document ini telah diVerifikasi Oleh : ".$NamaUser."Pada Tanggal : ".$tgl_assesment;?>
										<!-- <center><img alt='Verifikasi' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_perawat&choe=UTF-8'/></center> -->

										<?php 
										QRcode::png($verif_perawat, "asdew1.png", "L", 2, 2);   
										echo "<center><img src='asdew1.png'></center>";
										?>
										<br>
										<?php echo $NamaUser;?>
									</td>
									<td align="center">
										<?php
										$qnm="
										SELECT TOP (1)
										LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) AS kode,
										LTRIM(RTRIM(SUBSTRING(dpjp, CHARINDEX('-', dpjp) + 1, LEN(dpjp)))) AS nmdokter
										FROM ERM_RI_ASSESMEN_AWAL_DEWASA
										WHERE noreg='$noreg'
										";
										$hqnm  = sqlsrv_query($conn, $qnm);
										$dhqnm  = sqlsrv_fetch_array($hqnm, SQLSRV_FETCH_ASSOC); 
										$nmdokter = trim($dhqnm['nmdokter']);


										$verif_dpjp="Document ini telah diVerifikasi Oleh : ".$nmdokter."Pada Tanggal : ".$tgl_verif;

										if($tgl_verif){
											QRcode::png($verif_dpjp, "asdew2.png", "L", 2, 2);   
											echo "<center><img src='asdew2.png'></center>";
										}
										echo $dpjp;
										?>

									</td>
								</tr>
							</table>
							<br>
							<br>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>	
				</table>	
			</div>
		</form>
	</font>
</body>
</div>

<?php

if (isset($_POST["update_mas"])) {

	$m1=$_POST['m1'];
	$m2=$_POST['m2'];
	$m3=$_POST['m3'];
	$m4=$_POST['m4'];
	$m5=$_POST['m5'];
	$m6=$_POST['m6'];
	$m7=$_POST['m7'];
	$m8=$_POST['m8'];
	$m9=$_POST['m9'];
	$m10=$_POST['m10'];
	$m11=$_POST['m11'];
	$m12=$_POST['m12'];
	$m13=$_POST['m13'];

	//keadaan umum...
	$masalah_ku1	= $_POST["masalah_ku1"];
	$masalah_ku2	= $_POST["masalah_ku2"];

	if($masalah_ku1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$masalah_ku1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$masalah_ku1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0131' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	if($masalah_ku2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$masalah_ku2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$masalah_ku2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0131' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	//respirasi
	$respirasi1	= $_POST["masalah_respirasi1"];
	$respirasi2	= $_POST["masalah_respirasi2"];
	$respirasi3	= $_POST["masalah_respirasi3"];
	$respirasi4	= $_POST["masalah_respirasi4"];

	if($respirasi1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$respirasi1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$respirasi1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0001' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($respirasi2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$respirasi2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$respirasi2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0003' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($respirasi3){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$respirasi3' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$respirasi3','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0005' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($respirasi4){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$respirasi4' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$respirasi3','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0006' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}


	//sirukulasi
	$sirkulasi1	= $_POST["masalah_sirkulasi1"];
	$sirkulasi2	= $_POST["masalah_sirkulasi2"];
	$sirkulasi3	= $_POST["masalah_sirkulasi3"];

	if($sirkulasi1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$sirkulasi1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$sirkulasi1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0008' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	if($sirkulasi2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$sirkulasi2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$sirkulasi2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0009' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	if($sirkulasi3){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$sirkulasi3' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$sirkulasi3','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0014' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}


	//nyeri
	$nyeri1	= $_POST["masalah_nyeri1"];
	$nyeri2	= $_POST["masalah_nyeri2"];

	if($nyeri1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$nyeri1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$nyeri1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0077' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	if($nyeri2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$nyeri2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$nyeri2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0078' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}


	//eliminasi
	$eliminasi1	= $_POST["masalah_eliminasi1"];
	$eliminasi2	= $_POST["masalah_eliminasi2"];
	$eliminasi3	= $_POST["masalah_eliminasi3"];
	$eliminasi4	= $_POST["masalah_eliminasi4"];

	if($eliminasi1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$eliminasi1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$eliminasi1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0020' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	if($eliminasi2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$eliminasi2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$eliminasi2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0040' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	if($eliminasi3){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$eliminasi3' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$eliminasi3','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0049' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($eliminasi4){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$eliminasi4' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$eliminasi4','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0050' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}



	//cairan
	$cairan1	= $_POST["masalah_cairan1"];
	$cairan2	= $_POST["masalah_cairan2"];
	$cairan3	= $_POST["masalah_cairan3"];
	$cairan4	= $_POST["masalah_cairan4"];
	$cairan5	= $_POST["masalah_cairan5"];

	if($cairan1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$cairan1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$cairan1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0019' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($cairan2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$cairan2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$cairan2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0022' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($cairan3){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$cairan3' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$cairan3','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0023' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($cairan4){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$cairan4' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$cairan4','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0039' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($cairan5){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$cairan5' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$cairan5','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0076' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}


	//aktivitas
	$aktivitas1	= $_POST["masalah_aktivitas1"];
	$aktivitas2	= $_POST["masalah_aktivitas2"];
	$aktivitas3	= $_POST["masalah_aktivitas3"];
	if($aktivitas1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$aktivitas1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$aktivitas1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0054' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($aktivitas2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$aktivitas2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$aktivitas2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0056' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($aktivitas3){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$aktivitas3' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$aktivitas3','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0109' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}


	//kulit
	$kulit1	= $_POST["masalah_kulit1"];
	$kulit2	= $_POST["masalah_kulit2"];
	if($kulit1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$kulit1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$kulit1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0129' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($kulit2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$kulit2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$kulit2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0142' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}



	//endoktrin
	$endoktrin1	= $_POST["masalah_endoktrin1"];
	if($endoktrin1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$endoktrin1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			echo	$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$endoktrin1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0027' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}


	//jatuh
	$jatuh1	= $_POST["masalah_jatuh1"];
	if($jatuh1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$jatuh1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$jatuh1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0143' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}

	//psikologi
	$psikologi1	= $_POST["masalah_psikologi1"];
	$psikologi2	= $_POST["masalah_psikologi2"];

	if($psikologi1){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$psikologi1' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$psikologi1','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.0080' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}
	if($psikologi2){
		$qu="SELECT noreg FROM ERM_ASUHAN_KEPERAWATAN where diagnosa_keperawatan='$psikologi2' and noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$ceknoreg = trim($d1u['noreg']);
		if(empty($ceknoreg)){
			$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid) values 
			('$id','$noreg','$psikologi2','$tglentry','$user')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}else{
		$q = "DELETE from ERM_ASUHAN_KEPERAWATAN WHERE diagnosa_keperawatan='D.8033' and noreg='$noreg'";
		$hs  = sqlsrv_query($conn, $q); 
	}



	$q  = "update ERM_RI_ASSESMEN_AWAL_DEWASA set
	m1='$m1',m2='$m2',m3='$m3',m4='$m4',m5='$m5',m6='$m6',m7='$m7',m8='$m8',m9='$m9',m10='$m10',m11='$m11',m12='$m12',m13='$m13'
	where noreg='$noreg'
	";
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	alert('".$eror."');
	window.location.replace('form_assesmen_dewasa.php?id=$id|$user');
	</script>
	";

}

if (isset($_POST["edit"])) {

	$ku_tnyeri1= '';
	$ku_tnyeri2= '';
	$ku_tnyeri3= '';
	$ku_tnyeri4= '';
	$ku_tnyeri5= '';

	$q  = "update ERM_RI_ASSESMEN_AWAL_DEWASA set
	ku_tnyeri1= '$ku_tnyeri1',
	ku_tnyeri2= '$ku_tnyeri2',
	ku_tnyeri3= '$ku_tnyeri3',
	ku_tnyeri4= '$ku_tnyeri4',
	ku_tnyeri5= '$ku_tnyeri5'
	where noreg='$noreg'
	";
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	alert('".$eror."');
	</script>
	";


}


if (isset($_POST["simpan"])) {
	$lanjut='Y';

	//select user....
	if(strtoupper($userid)<>strtoupper($user)){
			// $lanjut='T';
		$eror = 'User Entry Tidak Sama dg User Entry Anamnesis Awal !!!';
	}

	$dpjp	= $_POST["dpjp"];
	$row2 = explode('-',$dpjp);

	$dpjp1  = $row2[0];
	$dpjp2 = $row2[1]; 

	if(empty($dpjp2)){
			// $lanjut='T';
		$eror = 'DPJP tidak valid !!!';
	}

	$jam1  = strtotime($de[tgl4]);
	$jam2 = strtotime($tglentry);											
	$selisih  = $jam2 - $jam1;
	$jam   = floor($selisih / (60 * 60));

	if($jam > 24){
			// $lanjut='T';
		$eror = 'Sudah lebih dari 24 jam, data tidak bisa dirubah';
		// $lanjut='T';

	}

	if($lanjut=='Y'){
		$keluhan_pasien	= $_POST["keluhan_pasien"];
		$dpjp	= $_POST["dpjp"];
		$dpjp 			= str_replace("'","`",$dpjp);

		$tglrawat	= $_POST["tglrawat"];
		$jamrawat	= $_POST["jamrawat"];
		$sumber_data_sendiri= $_POST["sumber_data_sendiri"];
		$sumber_data_keluarga= $_POST["sumber_data_keluarga"];
		$sumber_data_keluarga_nama= $_POST["sumber_data_keluarga_nama"];
		$sumber_data_keluarga_hub= $_POST["sumber_data_keluarga_hub"];
		$sumber_data_orang_lain= $_POST["sumber_data_orang_lain"];
		$sumber_data_orang_lain_nama= $_POST["sumber_data_orang_lain_nama"];
		$sumber_data_orang_lain_hub= $_POST["sumber_data_orang_lain_hub"];
		$perlu_interpreter_bahasa= $_POST["perlu_interpreter_bahasa"];
		$perlu_interpreter_bahasa_bahasa= $_POST["perlu_interpreter_bahasa_bahasa"];
		$asal_masuk= $_POST["asal_masuk"];
		$masuk_rs= $_POST["masuk_rs"];
		$rujukan_dari= $_POST["rujukan_dari"];
		$alatbantu= $_POST["alatbantu"];
		$prothesis= $_POST["prothesis"];
		$cacat_tubuh= $_POST["cacat_tubuh"];
		$riwayat_penyakit_sekarang= $_POST["riwayat_penyakit_sekarang"];
		$riwayat_penyakit_dahulu= $_POST["riwayat_penyakit_dahulu"];
		$riwayat_penyakit_keluarga= $_POST["riwayat_penyakit_keluarga"];
		$obat_sedang_dikonsumsi= $_POST["obat_sedang_dikonsumsi"];
		$riwayat_alergi= $_POST["riwayat_alergi"];
		$riwayat_alergi_alergi= $_POST["riwayat_alergi_alergi"];
		$riwayat_tranfusi_darah= $_POST["riwayat_tranfusi_darah"];
		$reaksi_alergi= $_POST["reaksi_alergi"];
		$ket_reaksi_alergi= $_POST["ket_reaksi_alergi"];
		$riwayat_merokok= $_POST["riwayat_merokok"];
		$riwayat_minuman_keras= $_POST["riwayat_minuman_keras"];
		$riwayat_keluar_negri= $_POST["riwayat_keluar_negri"];


		$ku_kesadaran= $_POST["ku_kesadaran"];
		$ku_gcs_e= $_POST["ku_gcs_e"];
		$ku_gcs_v= $_POST["ku_gcs_v"];
		$ku_gcs_m= $_POST["ku_gcs_m"];
		$total_gcs = $ku_gcs_e+$ku_gcs_v+$ku_gcs_m;
		$ku_beratbadan= $_POST["ku_beratbadan"];
		$ku_tinggibadan= $_POST["ku_tinggibadan"];

		$ku_suhu= $_POST["ku_suhu"];
		$ku_tensi= $_POST["ku_tensi"];
		$ku_nadi= $_POST["ku_nadi"];
		$ku_nadi_ket= $_POST["ku_nadi_ket"];
		$ku_nafas= $_POST["ku_nafas"];
		$ku_spo= $_POST["ku_spo"];
		$ku_respirasi_dada= $_POST["ku_respirasi_dada"];
		$ku_respirasi_nafas= $_POST["ku_respirasi_nafas"];
		$ku_suara_nafas= $_POST["ku_suara_nafas"];
		$ku_sesak_nafas= $_POST["ku_sesak_nafas"];
		$ku_sesak_nafas_ada= $_POST["ku_sesak_nafas_ada"];
		$ku_otot_bantu_nafas= $_POST["ku_otot_bantu_nafas"];
		$ku_batuk= $_POST["ku_batuk"];
		$ku_sputum= $_POST["ku_sputum"];
		$ku_batuk_warna= $_POST["ku_batuk_warna"];
		$ku_alat_medis= $_POST["ku_alat_medis"];
		$ku_alat_medis_ket= $_POST["ku_alat_medis_ket"];
		$ku_irkulasi_jantung= $_POST["ku_irkulasi_jantung"];
		$ku_suara_jantung= $_POST["ku_suara_jantung"];
		$ku_kelainan_jantung_akral= $_POST["ku_kelainan_jantung_akral"];
		$ku_kelainan_jantung_crt= $_POST["ku_kelainan_jantung_crt"];
		$ku_irkulasi_anemis= $_POST["ku_irkulasi_anemis"];
		$ku_irkulasi_anemis_hb= $_POST["ku_irkulasi_anemis_hb"];
		$ku_irkulasi_vena= $_POST["ku_irkulasi_vena"];
		$ku_irkulasi_alat_medis= $_POST["ku_irkulasi_alat_medis"];
		$ku_irkulasi_alat_medis_ket= $_POST["ku_irkulasi_alat_medis_ket"];
		$ku_persepsi_fisiologis= $_POST["ku_persepsi_fisiologis"];
		$ku_persepsi_patologis= $_POST["ku_persepsi_patologis"];
		$ku_persepsi_mata= $_POST["ku_persepsi_mata"];
		$ku_persepsi_pupil= $_POST["ku_persepsi_pupil"];
		$ku_persepsi_telinga= $_POST["ku_persepsi_telinga"];
		$ku_persepsi_hidung= $_POST["ku_persepsi_hidung"];
		$ku_persepsi_sensibilitas= $_POST["ku_persepsi_sensibilitas"];
		$ku_persepsi_bicara= $_POST["ku_persepsi_bicara"];
		$ku_persepsi_kaku_duduk= $_POST["ku_persepsi_kaku_duduk"];
		$ku_persepsi_alat_bantu= $_POST["ku_persepsi_alat_bantu"];
		$ku_persepsi_alat_bantu_ket= $_POST["ku_persepsi_alat_bantu_ket"];
		$ku_hipermi= $_POST["ku_hipermi"];
		$ku_hipotermia= $_POST["ku_hipotermia"];
		$ku_masalah1= $_POST["ku_masalah1"];
		$ku_masalah2= $_POST["ku_masalah2"];
		$ku_masalah3= $_POST["ku_masalah3"];
		$ku_masalah4= $_POST["ku_masalah4"];
		$ku_masalah5= $_POST["ku_masalah5"];
		$ku_masalah6= $_POST["ku_masalah6"];
		$ku_masalah7= $_POST["ku_masalah7"];
		$ku_masalah8= $_POST["ku_masalah8"];
		$ku_masalah_lain= $_POST["ku_masalah_lain"];
		$ku_nyeri= $_POST["ku_nyeri"];
		$ku_nyeri_skala= $_POST["ku_nyeri_skala"];
		$ku_nyeri_lokasi= $_POST["ku_nyeri_lokasi"];
		$ku_tanya1= $_POST["ku_tanya1"];
		$ku_tanya2= $_POST["ku_tanya2"];
		$ku_tanya3= $_POST["ku_tanya3"];
		$ku_tanya4= $_POST["ku_tanya4"];
		$ku_tanya5= $_POST["ku_tanya5"];

		$ku_tnyeri1= $_POST["ku_tnyeri1"];
		$ku_tnyeri2= $_POST["ku_tnyeri2"];
		$ku_tnyeri3= $_POST["ku_tnyeri3"];
		$ku_tnyeri4= $_POST["ku_tnyeri4"];
		$ku_tnyeri5= $_POST["ku_tnyeri5"];

		$ku_eliminasi1= $_POST["ku_eliminasi1"];
		$ku_eliminasi2= $_POST["ku_eliminasi2"];
		$ku_eliminasi3= $_POST["ku_eliminasi3"];
		$ku_eliminasi4= $_POST["ku_eliminasi4"];
		$ku_eliminasi5= $_POST["ku_eliminasi5"];
		$ku_eliminasi6= $_POST["ku_eliminasi6"];
		$ku_eliminasi7= $_POST["ku_eliminasi7"];
		$ku_eliminasi8= $_POST["ku_eliminasi8"];
		$ku_eliminasi9= $_POST["ku_eliminasi9"];
		$ku_eliminasi10= $_POST["ku_eliminasi10"];

		$ku_nutrisi1= $_POST["ku_nutrisi1"];
		$ku_nutrisi2= $_POST["ku_nutrisi2"];
		$ku_nutrisi3= $_POST["ku_nutrisi3"];
		$ku_nutrisi4= $_POST["ku_nutrisi4"];
		$ku_nutrisi5= $_POST["ku_nutrisi5"];
		$ku_nutrisi6= $_POST["ku_nutrisi6"];
		$ku_nutrisi7= $_POST["ku_nutrisi7"];
		$ku_nutrisi8= $_POST["ku_nutrisi8"];

		$ku_tnutrisi1= $_POST["ku_tnutrisi1"];
		$ku_tnutrisi2= $_POST["ku_tnutrisi2"];
		$ku_tnutrisi3= $_POST["ku_tnutrisi3"];
		$ku_tnutrisi4= $_POST["ku_tnutrisi4"];
		$ku_tnutrisi5= $_POST["ku_tnutrisi5"];
		$ku_tnutrisi6= $_POST["ku_tnutrisi6"];
		$ku_tnutrisi7= $_POST["ku_tnutrisi7"];
		$ku_tnutrisi8= $_POST['ku_tnutrisi8'];
		$ku_tnutrisi9= $_POST['ku_tnutrisi9'];
		$ku_tnutrisi10= $_POST['ku_tnutrisi10'];

		$ku_nyerimasalah1= $_POST['ku_nyerimasalah1'];
		$ku_nyerimasalah2= $_POST['ku_nyerimasalah2'];
		$ku_nyerimasalah3= $_POST['ku_nyerimasalah3'];
		$ku_nyerimasalah4= $_POST['ku_nyerimasalah4'];
		$ku_nyerimasalah5= $_POST['ku_nyerimasalah5'];
		$ku_nyerimasalah6= $_POST['ku_nyerimasalah6'];
		$ku_nyerimasalah7= $_POST['ku_nyerimasalah7'];
		$ku_nyerimasalah8= $_POST['ku_nyerimasalah8'];
		$ku_nyerimasalah9= $_POST['ku_nyerimasalah9'];
		$ku_nyerimasalah10= $_POST['ku_nyerimasalah10'];
		$ku_nyerimasalah11= $_POST['ku_nyerimasalah11'];
		$ku_nyerimasalah12= $_POST['ku_nyerimasalah12'];

		$aktivitas1= $_POST['aktivitas1'];
		$aktivitas2= $_POST['aktivitas2'];

		$taktivitas1= $_POST['taktivitas1'];
		$taktivitas2= $_POST['taktivitas2'];
		$taktivitas3= $_POST['taktivitas3'];
		$taktivitas4= $_POST['taktivitas4'];
		$taktivitas5= $_POST['taktivitas5'];
		$taktivitas6= $_POST['taktivitas6'];
		$taktivitas7= $_POST['taktivitas7'];
		$taktivitas8= $_POST['taktivitas8'];
		$taktivitas9= $_POST['taktivitas9'];
		$taktivitas10= $_POST['taktivitas10'];
		$taktivitas11= $_POST['taktivitas11'];
		$taktivitas12= $_POST['taktivitas12'];
		$taktivitas13= $_POST['taktivitas13'];
		$taktivitas14= $_POST['taktivitas14'];
		$taktivitas15= $_POST['taktivitas15'];
		$taktivitas16= $_POST['taktivitas16'];
		$taktivitas17= $_POST['taktivitas17'];
		$taktivitas18= $_POST['taktivitas18'];
		$taktivitas19= $_POST['taktivitas19'];
		$taktivitas20= $_POST['taktivitas20'];
		$taktivitas21= $_POST['taktivitas21'];
		$taktivitas22= $_POST['taktivitas22'];
		$taktivitas23= $_POST['taktivitas23'];
		$taktivitas24= $_POST['taktivitas24'];
		$taktivitas25= $_POST['taktivitas25'];
		$taktivitas26= $_POST['taktivitas26'];
		$taktivitas27= $_POST['taktivitas27'];
		$taktivitas28= $_POST['taktivitas28'];
		$taktivitas29= $_POST['taktivitas29'];
		$taktivitas30= $_POST['taktivitas30'];

		$kulit1= $_POST['kulit1'];
		$kulit2= $_POST['kulit2'];
		$kulit3= $_POST['kulit3'];
		$kulit4= $_POST['kulit4'];
		$kulit5= $_POST['kulit5'];
		$kulit6= $_POST['kulit6'];


		$tkulit1= $_POST['tkulit1'];
		$tkulit2= $_POST['tkulit2'];
		$tkulit3= $_POST['tkulit3'];
		$tkulit4= $_POST['tkulit4'];
		$tkulit5= $_POST['tkulit5'];
		$tkulit6= $_POST['tkulit6'];
		$tkulit7= $_POST['tkulit7'];
		$tkulit8= $_POST['tkulit8'];
		$tkulit9= $_POST['tkulit9'];
		$tkulit10= $_POST['tkulit10'];
		$tkulit11= $_POST['tkulit11'];
		$tkulit12= $_POST['tkulit12'];
		$tkulit13= $_POST['tkulit13'];
		$tkulit14= $_POST['tkulit14'];
		$tkulit15= $_POST['tkulit15'];
		$tkulit16= $_POST['tkulit16'];
		$tkulit17= $_POST['tkulit17'];
		$tkulit18= $_POST['tkulit18'];
		$tkulit19= $_POST['tkulit19'];
		$tkulit20= $_POST['tkulit20'];
		$endokrin1=$_POST['endokrin1'];
		$endokrin2=$_POST['endokrin2'];
		$endokrin3=$_POST['endokrin3'];
		$endokrin4=$_POST['endokrin4'];
		$reproduksi1=$_POST['reproduksi1'];
		$reproduksi2=$_POST['reproduksi2'];
		$reproduksi3=$_POST['reproduksi3'];
		$maktifitas1=$_POST['maktifitas1'];
		$maktifitas2=$_POST['maktifitas2'];
		$maktifitas3=$_POST['maktifitas3'];
		$maktifitas4=$_POST['maktifitas4'];
		$maktifitas5=$_POST['maktifitas5'];
		$maktifitas6=$_POST['maktifitas6'];
		$maktifitas7=$_POST['maktifitas7'];
		$maktifitas8=$_POST['maktifitas8'];
		$tjatuh1=$_POST['tjatuh1'];
		$tjatuh2=$_POST['tjatuh2'];
		$tjatuh3=$_POST['tjatuh3'];
		$tjatuh4=$_POST['tjatuh4'];
		$tjatuh5=$_POST['tjatuh5'];
		$tjatuh6=$_POST['tjatuh6'];
		$tjatuh7=$_POST['tjatuh7'];
		$tjatuh8=$_POST['tjatuh8'];
		$tjatuh9=$_POST['tjatuh9'];
		$tjatuh10=$_POST['tjatuh10'];
		$tjatuh11=$_POST['tjatuh11'];
		$tjatuh12=$_POST['tjatuh12'];
		$tjatuh13=$_POST['tjatuh13'];
		$tjatuh14=$_POST['tjatuh14'];

		$asdewasa1=$_POST['asdewasa1'];
		$asdewasa2=$_POST['asdewasa2'];
		$asdewasa3=$_POST['asdewasa3'];
		$asdewasa4=$_POST['asdewasa4'];
		$asdewasa5=$_POST['asdewasa5'];
		$asdewasa6=$_POST['asdewasa6'];
		$asdewasa7=$_POST['asdewasa7'];
		$asdewasa8=$_POST['asdewasa8'];
		$asdewasa9=$_POST['asdewasa9'];
		$asdewasa10=$_POST['asdewasa10'];
		$asdewasa11=$_POST['asdewasa11'];
		$asdewasa12=$_POST['asdewasa12'];
		$asdewasa13=$_POST['asdewasa13'];
		$asdewasa14=$_POST['asdewasa14'];
		$asdewasa15=$_POST['asdewasa15'];
		$asdewasa16=$_POST['asdewasa16'];
		$asdewasa17=$_POST['asdewasa17'];
		$asdewasa18=$_POST['asdewasa18'];
		$asdewasa19=$_POST['asdewasa19'];
		$asdewasa20=$_POST['asdewasa20'];
		$asdewasa21=$_POST['asdewasa21'];
		$asdewasa22=$_POST['asdewasa22'];
		$asdewasa23=$_POST['asdewasa23'];
		$asdewasa24=$_POST['asdewasa24'];
		$asdewasa25=$_POST['asdewasa25'];
		$asdewasa26=$_POST['asdewasa26'];
		$asdewasa27=$_POST['asdewasa27'];
		$asdewasa28=$_POST['asdewasa28'];
		$asdewasa29=$_POST['asdewasa29'];
		$asdewasa30=$_POST['asdewasa30'];
		$asdewasa31=$_POST['asdewasa31'];
		$asdewasa32=$_POST['asdewasa32'];
		$asdewasa33=$_POST['asdewasa33'];
		$asdewasa34=$_POST['asdewasa34'];
		$asdewasa35=$_POST['asdewasa35'];
		$asdewasa36=$_POST['asdewasa36'];
		$asdewasa37=$_POST['asdewasa37'];
		$asdewasa38=$_POST['asdewasa38'];
		$asdewasa39=$_POST['asdewasa39'];
		$asdewasa40=$_POST['asdewasa40'];
		$asdewasa41=$_POST['asdewasa41'];
		$asdewasa42=$_POST['asdewasa42'];
		$asdewasa43=$_POST['asdewasa43'];
		$asdewasa44=$_POST['asdewasa44'];
		$asdewasa45=$_POST['asdewasa45'];
		$asdewasa46=$_POST['asdewasa46'];
		$asdewasa47=$_POST['asdewasa47'];
		$asdewasa48=$_POST['asdewasa48'];
		$asdewasa49=$_POST['asdewasa49'];
		$asdewasa50=$_POST['asdewasa50'];
		$asdewasa51=$_POST['asdewasa51'];
		$asdewasa52=$_POST['asdewasa52'];
		$asdewasa53=$_POST['asdewasa53'];
		$asdewasa54=$_POST['asdewasa54'];
		$asdewasa55=$_POST['asdewasa55'];
		$asdewasa56=$_POST['asdewasa56'];
		$asdewasa57=$_POST['asdewasa57'];
		$asdewasa58=$_POST['asdewasa58'];
		$asdewasa59=$_POST['asdewasa59'];
		$asdewasa60=$_POST['asdewasa60'];
		$asdewasa61=$_POST['asdewasa61'];

		$ket_1=$_POST['ket_1'];
		$ket_2=$_POST['ket_2'];
		$ket_3=$_POST['ket_3'];

		$m1=$_POST['m1'];
		$m2=$_POST['m2'];
		$m3=$_POST['m3'];
		$m4=$_POST['m4'];
		$m5=$_POST['m5'];
		$m6=$_POST['m6'];
		$m7=$_POST['m7'];
		$m8=$_POST['m8'];
		$m9=$_POST['m9'];
		$m10=$_POST['m10'];
		$m11=$_POST['m11'];
		$m12=$_POST['m12'];
		$m13=$_POST['m13'];


		$q  = "update ERM_RI_ASSESMEN_AWAL_DEWASA set
		keluhan_pasien='$keluhan_pasien',
		tglrawat='$tglrawat',jamrawat='$jamrawat',
		sumber_data_sendiri='$sumber_data_sendiri',
		sumber_data_keluarga='$sumber_data_keluarga',
		sumber_data_keluarga_nama='$sumber_data_keluarga_nama',
		sumber_data_keluarga_hub='$sumber_data_keluarga_hub',
		sumber_data_orang_lain='$sumber_data_orang_lain',
		sumber_data_orang_lain_hub='$sumber_data_orang_lain_hub',
		sumber_data_orang_lain_nama='$sumber_data_orang_lain_nama',
		perlu_interpreter_bahasa='$perlu_interpreter_bahasa',
		perlu_interpreter_bahasa_bahasa='$perlu_interpreter_bahasa_bahasa',
		asal_masuk='$asal_masuk',
		masuk_rs='$masuk_rs',
		rujukan_dari='$rujukan_dari',
		alatbantu='$alatbantu',
		prothesis='$prothesis',
		cacat_tubuh='$cacat_tubuh',
		riwayat_penyakit_sekarang='$riwayat_penyakit_sekarang',
		riwayat_penyakit_dahulu='$riwayat_penyakit_dahulu',
		riwayat_penyakit_keluarga='$riwayat_penyakit_keluarga',
		obat_sedang_dikonsumsi='$obat_sedang_dikonsumsi',
		riwayat_alergi='$riwayat_alergi',
		riwayat_alergi_alergi='$riwayat_alergi_alergi',
		riwayat_tranfusi_darah='$riwayat_tranfusi_darah',
		reaksi_alergi='$reaksi_alergi',
		ket_reaksi_alergi='$ket_reaksi_alergi',
		riwayat_merokok='$riwayat_merokok',
		riwayat_minuman_keras='$riwayat_minuman_keras',
		riwayat_keluar_negri='$riwayat_keluar_negri',
		ku_kesadaran='$ku_kesadaran',
		ku_gcs_e='$ku_gcs_e',
		ku_gcs_v='$ku_gcs_v',
		ku_gcs_m='$ku_gcs_m',
		total_gcs='$total_gcs',
		ku_tinggibadan='$ku_tinggibadan',
		ku_beratbadan='$ku_beratbadan',
		ku_suhu='$ku_suhu',
		ku_tensi='$ku_tensi',
		ku_nadi='$ku_nadi',
		ku_nadi_ket='$ku_nadi_ket',
		ku_nafas='$ku_nafas',
		ku_spo='$ku_spo',
		ku_respirasi_dada='$ku_respirasi_dada',
		ku_respirasi_nafas='$ku_respirasi_nafas',
		ku_suara_nafas='$ku_suara_nafas',
		ku_sesak_nafas='$ku_sesak_nafas',
		ku_sesak_nafas_ada='$ku_sesak_nafas_ada',
		ku_otot_bantu_nafas='$ku_otot_bantu_nafas',
		ku_batuk='$ku_batuk',
		ku_sputum='$ku_sputum',
		ku_batuk_warna='$ku_batuk_warna',
		ku_alat_medis='$ku_alat_medis',
		ku_alat_medis_ket='$ku_alat_medis_ket',
		ku_irkulasi_jantung='$ku_irkulasi_jantung',
		ku_suara_jantung='$ku_suara_jantung',
		ku_kelainan_jantung_akral='$ku_kelainan_jantung_akral',
		ku_kelainan_jantung_crt='$ku_kelainan_jantung_crt',
		ku_irkulasi_anemis='$ku_irkulasi_anemis',
		ku_irkulasi_anemis_hb='$ku_irkulasi_anemis_hb',
		ku_irkulasi_vena='$ku_irkulasi_vena',
		ku_irkulasi_alat_medis='$ku_irkulasi_alat_medis',
		ku_irkulasi_alat_medis_ket='$ku_irkulasi_alat_medis_ket',
		ku_persepsi_fisiologis='$ku_persepsi_fisiologis',
		ku_persepsi_patologis='$ku_persepsi_patologis',
		ku_persepsi_mata='$ku_persepsi_mata',
		ku_persepsi_pupil='$ku_persepsi_pupil',
		ku_persepsi_telinga='$ku_persepsi_telinga',
		ku_persepsi_hidung='$ku_persepsi_hidung',
		ku_persepsi_sensibilitas='$ku_persepsi_sensibilitas',
		ku_persepsi_bicara='$ku_persepsi_bicara',
		ku_persepsi_kaku_duduk='$ku_persepsi_kaku_duduk',
		ku_persepsi_alat_bantu='$ku_persepsi_alat_bantu',
		ku_persepsi_alat_bantu_ket='$ku_persepsi_alat_bantu_ket',
		ku_hipermi='$ku_hipermi',
		ku_hipotermia='$ku_hipotermia',
		ku_masalah1='$ku_masalah1',
		ku_masalah2='$ku_masalah2',
		ku_masalah3='$ku_masalah3',
		ku_masalah4='$ku_masalah4',
		ku_masalah5='$ku_masalah5',
		ku_masalah6='$ku_masalah6',
		ku_masalah7='$ku_masalah7',
		ku_masalah8='$ku_masalah8',
		ku_masalah_lain='$ku_masalah_lain',
		ku_nyeri='$ku_nyeri',
		ku_nyeri_skala='$ku_nyeri_skala',
		ku_nyeri_lokasi='$ku_nyeri_lokasi',
		ku_tanya1='$ku_tanya1',
		ku_tanya2='$ku_tanya2',
		ku_tanya3='$ku_tanya3',
		ku_tanya4='$ku_tanya4',
		ku_tanya5='$ku_tanya5',
		ku_tnyeri1= '$ku_tnyeri1',
		ku_tnyeri2= '$ku_tnyeri2',
		ku_tnyeri3= '$ku_tnyeri3',
		ku_tnyeri4= '$ku_tnyeri4',
		ku_tnyeri5= '$ku_tnyeri5',
		ku_eliminasi1= '$ku_eliminasi1',
		ku_eliminasi2= '$ku_eliminasi2',
		ku_eliminasi3= '$ku_eliminasi3',
		ku_eliminasi4= '$ku_eliminasi4',
		ku_eliminasi5= '$ku_eliminasi5',
		ku_eliminasi6= '$ku_eliminasi6',
		ku_eliminasi7= '$ku_eliminasi7',
		ku_eliminasi8= '$ku_eliminasi8',
		ku_eliminasi9= '$ku_eliminasi9',
		ku_eliminasi10= '$ku_eliminasi10',
		ku_nutrisi1= '$ku_nutrisi1',
		ku_nutrisi2= '$ku_nutrisi2',
		ku_nutrisi3= '$ku_nutrisi3',
		ku_nutrisi4= '$ku_nutrisi4',
		ku_nutrisi5= '$ku_nutrisi5',
		ku_nutrisi6= '$ku_nutrisi6',
		ku_nutrisi7= '$ku_nutrisi7',
		ku_nutrisi8= '$ku_nutrisi8',
		ku_tnutrisi1= '$ku_tnutrisi1',
		ku_tnutrisi2= '$ku_tnutrisi2',
		ku_tnutrisi3= '$ku_tnutrisi3',
		ku_tnutrisi4= '$ku_tnutrisi4',
		ku_tnutrisi5= '$ku_tnutrisi5',
		ku_tnutrisi6= '$ku_tnutrisi6',
		ku_tnutrisi7= '$ku_tnutrisi7',
		ku_tnutrisi8= '$ku_tnutrisi8',
		ku_tnutrisi9= '$ku_tnutrisi9',
		ku_tnutrisi10= '$ku_tnutrisi10',
		ku_nyerimasalah1= '$ku_nyerimasalah1',
		ku_nyerimasalah2= '$ku_nyerimasalah2',
		ku_nyerimasalah3= '$ku_nyerimasalah3',
		ku_nyerimasalah4= '$ku_nyerimasalah4',
		ku_nyerimasalah5= '$ku_nyerimasalah5',
		ku_nyerimasalah6= '$ku_nyerimasalah6',
		ku_nyerimasalah7= '$ku_nyerimasalah7',
		ku_nyerimasalah8= '$ku_nyerimasalah8',
		ku_nyerimasalah9= '$ku_nyerimasalah9',
		ku_nyerimasalah10= '$ku_nyerimasalah10',
		ku_nyerimasalah11= '$ku_nyerimasalah11',
		ku_nyerimasalah12= '$ku_nyerimasalah12',
		aktivitas1= '$aktivitas1',
		aktivitas2= '$aktivitas2',
		taktivitas1= '$taktivitas1',
		taktivitas2= '$taktivitas2',
		taktivitas3= '$taktivitas3',
		taktivitas4= '$taktivitas4',
		taktivitas5= '$taktivitas5',
		taktivitas6= '$taktivitas6',
		taktivitas7= '$taktivitas7',
		taktivitas8= '$taktivitas8',
		taktivitas9= '$taktivitas9',
		taktivitas10= '$taktivitas10',
		taktivitas11= '$taktivitas11',
		taktivitas12= '$taktivitas12',
		taktivitas13= '$taktivitas13',
		taktivitas14= '$taktivitas14',
		taktivitas15= '$taktivitas15',
		taktivitas16= '$taktivitas16',
		taktivitas17= '$taktivitas17',
		taktivitas18= '$taktivitas18',
		taktivitas19= '$taktivitas19',
		taktivitas20= '$taktivitas20',
		taktivitas21= '$taktivitas21',
		taktivitas22= '$taktivitas22',
		taktivitas23= '$taktivitas23',
		taktivitas24= '$taktivitas24',
		taktivitas25= '$taktivitas25',
		taktivitas26= '$taktivitas26',
		taktivitas27= '$taktivitas27',
		taktivitas28= '$taktivitas28',
		taktivitas29= '$taktivitas29',
		taktivitas30= '$taktivitas30',
		kulit1= '$kulit1',
		kulit2= '$kulit2',
		kulit3= '$kulit3',
		kulit4= '$kulit4',
		kulit5= '$kulit5',
		kulit6= '$kulit6',
		tkulit1	= '$tkulit1',
		tkulit2	= '$tkulit2',
		tkulit3	= '$tkulit3',
		tkulit4	= '$tkulit4',
		tkulit5	= '$tkulit5',
		tkulit6	= '$tkulit6',
		tkulit7	= '$tkulit7',
		tkulit8	= '$tkulit8',
		tkulit9	= '$tkulit9',
		tkulit10 = '$tkulit10',
		tkulit11 = '$tkulit11',
		tkulit12 = '$tkulit12',
		tkulit13 = '$tkulit13',
		tkulit14 = '$tkulit14',
		tkulit15 = '$tkulit15',
		tkulit16 = '$tkulit16',
		tkulit17 = '$tkulit17',
		tkulit18 = '$tkulit18',
		tkulit19 = '$tkulit19',
		tkulit20 = '$tkulit20',
		endokrin1='$endokrin1',
		endokrin2='$endokrin2',
		endokrin3='$endokrin3',
		endokrin4='$endokrin4',
		reproduksi1='$reproduksi1',
		reproduksi2='$reproduksi2',
		reproduksi3='$reproduksi3',
		maktifitas1='$maktifitas1',
		maktifitas2='$maktifitas2',
		maktifitas3='$maktifitas3',
		maktifitas4='$maktifitas4',
		maktifitas5='$maktifitas5',
		maktifitas6='$maktifitas6',
		maktifitas7='$maktifitas7',
		maktifitas8='$maktifitas8',
		tjatuh1='$tjatuh1',
		tjatuh2='$tjatuh2',
		tjatuh3='$tjatuh3',
		tjatuh4='$tjatuh4',
		tjatuh5='$tjatuh5',
		tjatuh6='$tjatuh6',
		tjatuh7='$tjatuh7',
		tjatuh8='$tjatuh8',
		tjatuh9='$tjatuh9',
		tjatuh10='$tjatuh10',
		tjatuh11='$tjatuh11',
		tjatuh12='$tjatuh12',
		tjatuh13='$tjatuh13',
		tjatuh14='$tjatuh14',
		asdewasa1='$asdewasa1',
		asdewasa2='$asdewasa2',
		asdewasa3='$asdewasa3',
		asdewasa4='$asdewasa4',
		asdewasa5='$asdewasa5',
		asdewasa6='$asdewasa6',
		asdewasa7='$asdewasa7',
		asdewasa8='$asdewasa8',
		asdewasa9='$asdewasa9',
		asdewasa10='$asdewasa10',
		asdewasa11='$asdewasa11',
		asdewasa12='$asdewasa12',
		asdewasa13='$asdewasa13',
		asdewasa14='$asdewasa14',
		asdewasa15='$asdewasa15',
		asdewasa16='$asdewasa16',
		asdewasa17='$asdewasa17',
		asdewasa18='$asdewasa18',
		asdewasa19='$asdewasa19',
		asdewasa20='$asdewasa20',
		asdewasa21='$asdewasa21',
		asdewasa22='$asdewasa22',
		asdewasa23='$asdewasa23',
		asdewasa24='$asdewasa24',
		asdewasa25='$asdewasa25',
		asdewasa26='$asdewasa26',
		asdewasa27='$asdewasa27',
		asdewasa28='$asdewasa28',
		asdewasa29='$asdewasa29',
		asdewasa30='$asdewasa30',
		asdewasa31='$asdewasa31',
		asdewasa32='$asdewasa32',
		asdewasa33='$asdewasa33',
		asdewasa34='$asdewasa34',
		asdewasa35='$asdewasa35',
		asdewasa36='$asdewasa36',
		asdewasa37='$asdewasa37',
		asdewasa38='$asdewasa38',
		asdewasa39='$asdewasa39',
		asdewasa40='$asdewasa40',
		asdewasa41='$asdewasa41',
		asdewasa42='$asdewasa42',
		asdewasa43='$asdewasa43',
		asdewasa44='$asdewasa44',
		asdewasa45='$asdewasa45',
		asdewasa46='$asdewasa46',
		asdewasa47='$asdewasa47',
		asdewasa48='$asdewasa48',
		asdewasa49='$asdewasa49',
		asdewasa50='$asdewasa50',
		asdewasa51='$asdewasa51',
		asdewasa52='$asdewasa52',
		asdewasa53='$asdewasa53',
		asdewasa54='$asdewasa54',
		asdewasa55='$asdewasa55',
		asdewasa56='$asdewasa56',
		asdewasa57='$asdewasa57',
		asdewasa58='$asdewasa58',
		asdewasa59='$asdewasa59',
		asdewasa60='$asdewasa60',
		asdewasa61='$asdewasa61',
		ket_1='$ket_1',ket_2='$ket_2',ket_3='$ket_3',
		dpjp='$dpjp',userid='$user',
		m1='$m1',m2='$m2',m3='$m3',m4='$m4',m5='$m5',m6='$m6',m7='$m7',m8='$m8',m9='$m9',m10='$m10',m11='$m11',m12='$m12',m13='$m13',umur='$umur'

		where noreg='$noreg'
		";
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			$eror = "Success";
		}else{
			$eror = "Gagal Insert";

		}

		echo "
		<script>
		alert('".$eror."');
		</script>
		";


	}else{

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";

		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";
	}


	echo "
	<script>
	history.go(-1);
	</script>
	";

}


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}

?>

