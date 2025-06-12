<?php 
//include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// include "phpqrcode/qrlib.php";
include "header_px.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglentry		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$aresep = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = trim($d1u['noreg']);
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, NOKTP,
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

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
$qi="SELECT noreg FROM ERM_RI_ASSESMEN_AWAL_ANAK where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_ASSESMEN_AWAL_ANAK(noreg,userid,tglentry) values ('$noreg','$user','$tglentry')";
	$hs = sqlsrv_query($conn,$q);

	$q  = "update ERM_RI_ASSESMEN_AWAL_DEWASA set m1='Y',m2='Y',m3='Y',m4='Y',m5='Y',m6='Y',m7='Y',m8='Y',m9='Y',m10='Y',m11='Y',m12='Y',m13='Y' where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);

}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tglrawat, 23) as tglrawat,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ASSESMEN_AWAL_ANAK
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$userid = $de['userid'];
	$keluhan_pasien = $de['keluhan_pasien'];
	$riwayat_penyakit_sekarang = $de['riwayat_penyakit_sekarang'];

	$tglrawat = $de['tglrawat'];
	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment'];
	$dpjp = $de['dpjp'];
	$userid = $de['userid'];
	
	$asanak1= $de['asanak1'];
	$asanak2= $de['asanak2'];
	$asanak3= $de['asanak3'];
	$asanak4= $de['asanak4'];
	$asanak5= $de['asanak5'];
	$asanak6= $de['asanak6'];
	$asanak7= $de['asanak7'];
	$asanak8= $de['asanak8'];
	$asanak9= $de['asanak9'];
	$asanak10= $de['asanak10'];
	$asanak11= $de['asanak11'];
	$asanak12= $de['asanak12'];
	$asanak13= $de['asanak13'];
	$asanak14= $de['asanak14'];
	$asanak15= $de['asanak15'];
	$asanak16= $de['asanak16'];
	$asanak17= $de['asanak17'];
	$asanak18= $de['asanak18'];
	$asanak19= $de['asanak19'];
	$asanak20= $de['asanak20'];
	$asanak21= $de['asanak21'];
	$asanak22= $de['asanak22'];
	$asanak23= $de['asanak23'];
	$asanak24= $de['asanak24'];
	$asanak25= $de['asanak25'];
	$asanak26= $de['asanak26'];
	$asanak27= $de['asanak27'];
	$asanak28= $de['asanak28'];
	$asanak29= $de['asanak29'];
	$asanak30= $de['asanak30'];
	$asanak31= $de['asanak31'];
	$asanak32= $de['asanak32'];
	$asanak33= $de['asanak33'];
	$asanak34= $de['asanak34'];
	$asanak35= $de['asanak35'];
	$asanak36= $de['asanak36'];
	$asanak37= $de['asanak37'];
	$asanak38= $de['asanak38'];
	$asanak39= $de['asanak39'];
	$asanak40= $de['asanak40'];
	$asanak41= $de['asanak41'];
	$asanak42= $de['asanak42'];
	$asanak43= $de['asanak43'];
	$asanak44= $de['asanak44'];
	$asanak45= $de['asanak45'];
	$asanak46= $de['asanak46'];
	$asanak47= $de['asanak47'];
	$asanak48= $de['asanak48'];
	$asanak49= $de['asanak49'];
	$asanak50= $de['asanak50'];
	$asanak51= $de['asanak51'];
	$asanak52= $de['asanak52'];
	$asanak53= $de['asanak53'];
	$asanak54= $de['asanak54'];
	$asanak55= $de['asanak55'];
	$asanak56= $de['asanak56'];
	$asanak57= $de['asanak57'];
	$asanak58= $de['asanak58'];
	$asanak59= $de['asanak59'];
	$asanak60= $de['asanak60'];
	$asanak61= $de['asanak61'];
	$asanak62= $de['asanak62'];
	$asanak63= $de['asanak63'];
	$asanak64= $de['asanak64'];
	$asanak65= $de['asanak65'];
	$asanak66= $de['asanak66'];
	$asanak67= $de['asanak67'];
	$asanak68= $de['asanak68'];
	$asanak69= $de['asanak69'];
	$asanak70= $de['asanak70'];
	$asanak71= $de['asanak71'];
	$asanak72= $de['asanak72'];
	$asanak73= $de['asanak73'];
	$asanak74= $de['asanak74'];
	$asanak75= $de['asanak75'];
	$asanak76= $de['asanak76'];
	$asanak77= $de['asanak77'];
	$asanak78= $de['asanak78'];
	$asanak79= $de['asanak79'];
	$asanak80= $de['asanak80'];
	$asanak81= $de['asanak81'];
	$asanak82= $de['asanak82'];
	$asanak83= $de['asanak83'];
	$asanak84= $de['asanak84'];
	$asanak85= $de['asanak85'];
	$asanak86= $de['asanak86'];
	$asanak87= $de['asanak87'];
	$asanak88= $de['asanak88'];
	$asanak89= $de['asanak89'];
	$asanak90= $de['asanak90'];
	$asanak91= $de['asanak91'];
	$asanak92= $de['asanak92'];
	$asanak93= $de['asanak93'];
	$asanak94= $de['asanak94'];
	$asanak95= $de['asanak95'];
	$asanak96= $de['asanak96'];
	$asanak97= $de['asanak97'];
	$asanak98= $de['asanak98'];
	$asanak99= $de['asanak99'];
	$asanak100= $de['asanak100'];
	$asanak101= $de['asanak101'];
	$asanak102= $de['asanak102'];
	$asanak103= $de['asanak103'];
	$asanak104= $de['asanak104'];
	$asanak105= $de['asanak105'];
	$asanak106= $de['asanak106'];
	$asanak107= $de['asanak107'];
	$asanak108= $de['asanak108'];
	$asanak109= $de['asanak109'];
	$asanak110= $de['asanak110'];
	$asanak111= $de['asanak111'];
	$asanak112= $de['asanak112'];
	$asanak113= $de['asanak113'];
	$asanak114= $de['asanak114'];
	$asanak115= $de['asanak115'];
	$asanak116= $de['asanak116'];
	$asanak117= $de['asanak117'];
	$asanak118= $de['asanak118'];
	$asanak119= $de['asanak119'];
	$asanak120= $de['asanak120'];
	$asanak121= $de['asanak121'];
	$asanak122= $de['asanak122'];
	$asanak123= $de['asanak123'];
	$asanak124= $de['asanak124'];
	$asanak125= $de['asanak125'];
	$asanak126= $de['asanak126'];
	$asanak127= $de['asanak127'];
	$asanak128= $de['asanak128'];
	$asanak129= $de['asanak129'];
	$asanak130= $de['asanak130'];
	$asanak131= $de['asanak131'];
	$asanak132= $de['asanak132'];
	$asanak133= $de['asanak133'];
	$asanak134= $de['asanak134'];
	$asanak135= $de['asanak135'];
	$asanak136= $de['asanak136'];
	$asanak137= $de['asanak137'];
	$asanak138= $de['asanak138'];
	$asanak139= $de['asanak139'];
	$asanak140= $de['asanak140'];
	$asanak141= $de['asanak141'];
	$asanak142= $de['asanak142'];
	$asanak143= $de['asanak143'];
	$asanak144= $de['asanak144'];
	$asanak145= $de['asanak145'];
	$asanak146= $de['asanak146'];
	$asanak147= $de['asanak147'];
	$asanak148= $de['asanak148'];
	$asanak149= $de['asanak149'];
	$asanak150= $de['asanak150'];
	$asanak151= $de['asanak151'];
	$asanak152= $de['asanak152'];
	$asanak153= $de['asanak153'];
	$asanak154= $de['asanak154'];
	$asanak155= $de['asanak155'];
	$asanak156= $de['asanak156'];
	$asanak157= $de['asanak157'];
	$asanak158= $de['asanak158'];
	$asanak159= $de['asanak159'];
	$asanak160= $de['asanak160'];
	$asanak161= $de['asanak161'];
	$asanak162= $de['asanak162'];
	$asanak163= $de['asanak163'];
	$asanak164= $de['asanak164'];
	$asanak165= $de['asanak165'];
	$asanak166= $de['asanak166'];
	$asanak167= $de['asanak167'];
	$asanak168= $de['asanak168'];
	$asanak169= $de['asanak169'];
	$asanak170= $de['asanak170'];
	$asanak171= $de['asanak171'];
	$asanak172= $de['asanak172'];
	$asanak173= $de['asanak173'];
	$asanak174= $de['asanak174'];
	$asanak175= $de['asanak175'];
	$asanak176= $de['asanak176'];
	$asanak177= $de['asanak177'];
	$asanak178= $de['asanak178'];
	$asanak179= $de['asanak179'];
	$asanak180= $de['asanak180'];
	$asanak181= $de['asanak181'];
	$asanak182= $de['asanak182'];
	$asanak183= $de['asanak183'];
	$asanak184= $de['asanak184'];
	$asanak185= $de['asanak185'];
	$asanak186= $de['asanak186'];
	$asanak187= $de['asanak187'];
	$asanak188= $de['asanak188'];
	$asanak189= $de['asanak189'];
	$asanak190= $de['asanak190'];
	$asanak191= $de['asanak191'];
	$asanak192= $de['asanak192'];
	$asanak193= $de['asanak193'];
	$asanak194= $de['asanak194'];
	$asanak195= $de['asanak195'];
	$asanak196= $de['asanak196'];
	$asanak197= $de['asanak197'];
	$asanak198= $de['asanak198'];
	$asanak199= $de['asanak199'];
	$asanak200= $de['asanak200'];
	$asanak201= $de['asanak201'];
	$asanak202= $de['asanak202'];
	$asanak203= $de['asanak203'];
	$asanak204= $de['asanak204'];
	$asanak205= $de['asanak205'];
	$asanak206= $de['asanak206'];
	$asanak207= $de['asanak207'];
	$asanak208= $de['asanak208'];
	$asanak209= $de['asanak209'];
	$asanak210= $de['asanak210'];
	$ku_tinggibadan= $de['ku_tinggibadan'];
	$ku_beratbadan= $de['ku_beratbadan'];

	$type_persalinan1= $de['type_persalinan1'];
	$type_persalinan2= $de['type_persalinan2'];
	$type_persalinan3= $de['type_persalinan3'];
	$type_persalinan4= $de['type_persalinan4'];
	$type_persalinan5= $de['type_persalinan5'];
	$r_tumbuhkembang1= $de['r_tumbuhkembang1'];
	$r_tumbuhkembang2= $de['r_tumbuhkembang2'];
	$r_tumbuhkembang3= $de['r_tumbuhkembang3'];
	$r_tumbuhkembang4= $de['r_tumbuhkembang4'];
	$r_tumbuhkembang5= $de['r_tumbuhkembang5'];
	$r_tumbuhkembang6= $de['r_tumbuhkembang6'];
	$r_tumbuhkembang7= $de['r_tumbuhkembang7'];
	$r_tumbuhkembang8= $de['r_tumbuhkembang8'];
	$r_tumbuhkembang9= $de['r_tumbuhkembang9'];
	$r_tumbuhkembang10= $de['r_tumbuhkembang10'];
	$r_tumbuhkembang11= $de['r_tumbuhkembang11'];
	$r_tumbuhkembang12= $de['r_tumbuhkembang12'];

	$r_hamil1= $de['r_hamil1'];
	$r_hamil2= $de['r_hamil2'];
	$r_hamil3= $de['r_hamil3'];
	$r_hamil4= $de['r_hamil4'];
	$r_hamil5= $de['r_hamil5'];
	$r_hamil6= $de['r_hamil6'];
	$r_hamil7= $de['r_hamil7'];
	$r_hamil8= $de['r_hamil8'];
	$r_hamil9= $de['r_hamil9'];

	$nutrisi1= $de['nutrisi1'];
	$nutrisi2= $de['nutrisi2'];
	$nutrisi3= $de['nutrisi3'];
	$nutrisi4= $de['nutrisi4'];
	$nutrisi5= $de['nutrisi5'];
	$nutrisi6= $de['nutrisi6'];

	$m1=$de['m1'];$m2=$de['m2'];$m3=$de['m3'];$m4=$de['m4'];$m5=$de['m5'];$m6=$de['m6'];$m7=$de['m7'];$m8=$de['m8'];$m9=$de['m9'];
	$m10=$de['m10'];$m11=$de['m11'];$m12=$de['m12'];$m13=$de['m13'];

	$ku_gcs_e= $de['ku_gcs_e'];
	$ku_gcs_v= $de['ku_gcs_v'];
	$ku_gcs_m= $de['ku_gcs_m'];
	$ku_suhu= $de['ku_suhu'];
	$ku_tensi= $de['ku_tensi'];
	$ku_nadi_ket= $de['ku_nadi_ket'];
	$ku_nafas= $de['ku_nafas'];
	$ku_spo= $de['ku_spo'];
	$ku_kesadaran= $de['ku_kesadaran'];
	$total_gcs= $de['total_gcs'];
	$ku_nadi= $de['ku_nadi'];
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

	
</head> 

<div class="container-fluid">

	<body onload="document.myForm.td_sistolik.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<div class="row">
					<div class="col-12">
						<i class="bi bi-window-plus"> &nbsp; <b>ASESMEN AWAL KEPERAWATAN ANAK</b></i>

					</div>
				</div>

				<br>
<!-- 				<div class="row">
					<div class="col-12">
						<table class="table table-bordered">
							<tr>
								<td>Nama</td><td>: <?php echo $nama;?></td><td>No. RM</td><td>: <?php echo $norm;?></td>
							</tr>
							<tr>
								<td>Tanggal Lahir</td><td>: <?php echo $tgllahir;?></td><td>NIK</td><td>: <?php echo $noktp;?></td>
							</tr>
							<tr>
								<td>Umur</td><td>: <?php echo $umur;?></td><td>Jenis Kelamin</td><td>: <?php echo $kelamin;?></td>
							</tr>
							<tr>
								<td>Riwayat Alergi</td><td>: <?php echo $alergi;?></td><td>Diet</td><td>: <?php echo $diet;?></td>
							</tr>
						</table>
					</div>
				</div> -->

				
				<div class="col-12">
					<table  class="table table-bordered">
						<tr>
							<td colspan='2'>
								<b>DIISI OLEH PERAWAT</b> 
								&nbsp;&nbsp;&nbsp;<input class="" name="tglinput" value="<?php echo $tglinput;?>" type="text" disabled >
								<b>DPJP</b> : <input class="" name="dpjp" value="<?php echo $dpjp;?>" id="dokter" type="text" disabled size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Dokter atau Kode Dokter" required>&nbsp;&nbsp;&nbsp; 
							</td>
						</tr>
						<tr>
							<td width="70%">
								Masuk di Ruang Rawat Tanggal : <input type='date' disabled name='tglrawat' value='<?php echo $tglrawat;?>'>
								
							</td>
							<td>
								Fungsional
							</td>
						</tr>
						<tr>
							<td>
								<table>
									<tr>
										<td>Sumber Data :</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><input type='checkbox' disabled name='asanak1' value='YA' <?php if ($asanak1=="YA"){echo "checked";}?> >Pasien Sendiri</td>
										<td></td>
										<td></td>
									</tr>
									<tr>
										<td><input type='checkbox' disabled name='asanak2' value='YA' <?php if ($asanak2=="YA"){echo "checked";}?> >Keluarga</td>
										<td>Nama : <input  type='text' disabled name='asanak3' value='<?php echo $asanak3;?>'></td>
										<td>Hubungan : <input  type='text' disabled name='asanak4' value='<?php echo $asanak4;?>'></td>
									</tr>
									<tr>
										<td><input type='checkbox' disabled name='asanak5' value='YA' <?php if ($asanak5=="YA"){echo "checked";}?> >Orang Lain</td>
										<td>Nama :  <input  type='text' disabled name='asanak6' value='<?php echo $asanak6;?>'></td>
										<td>Hubungan : <input  type='text' disabled name='asanak7' value='<?php echo $asanak7;?>'></td>
									</tr>
									<tr>
										<td>Perlu interpreter bahasa :</td>
										<td colspan="2">
											<input type='checkbox' disabled name='asanak8' value='Tidak' <?php if ($asanak8=="Tidak"){echo "checked";}?>> 
											Tidak
											<input type='checkbox' disabled name='asanak8' value='Ya' <?php if ($asanak8=="Ya"){echo "checked";}?>> 
											Ya,
											bahasa
											<input  type='text' disabled name='asanak9' value='<?php echo $asanak9;?>'>
										</td>
									</tr>
									<tr>
										<td>Asal Masuk  :</td>
										<td colspan="2">
											<input type='checkbox' disabled name='asanak10' value='Instalasi Gawat Darurat (IGD)' <?php if ($asanak10=="Instalasi Gawat Darurat (IGD)"){echo "checked";}?>>Instalasi Gawat Darurat (IGD)      
											<input type='checkbox' disabled name='asanak10' value='Instalasi Rawat Jalan(Rajal)' <?php if ($asanak10=="Instalasi Rawat Jalan(Rajal)"){echo "checked";}?>>Instalasi Rawat Jalan(Rajal)
										</td>
									</tr>
									<tr>
										<td>Masuk Ke RS :</td>
										<td colspan="2">
											<input type='checkbox' disabled name='asanak11' value='Datang sendiri' <?php if ($asanak11=="Datang sendiri"){echo "checked";}?>>Datang sendiri  
											<input type='checkbox' disabled name='asanak11' value='Diantar keluarga' <?php if ($asanak11=="Diantar keluarga"){echo "checked";}?>>        
											Diantar keluarga      
										</td>
									</tr>
									<tr>
										<td>Rujukan dari :</td>
										<td colspan="2">
											<input  type='text' disabled name='asanak12' value='<?php echo $asanak12;?>'>
										</td>
									</tr>
								</table>

							</td>
							<td>
<!-- 								AlatBantu : &nbsp;&nbsp;&nbsp;<input  type='text' disabled name='asanak13' value='<?php echo $asanak13;?>'><br>
								Prothesis : &nbsp;&nbsp;&nbsp;&nbsp;<input  type='text' disabled name='asanak14' value='<?php echo $asanak14;?>'><br>
								CacatTubuh : <input  type='text' disabled name='asanak15' value='<?php echo $asanak15;?>'><br> -->
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
											<!-- <input  type='text' disabled name='keluhan_pasien' value='<?php echo $keluhan_pasien;?>'> -->
											<textarea disabled name= "keluhan_pasien" id="fisik" style="min-width:630px; min-height:80px;"><?php echo $keluhan_pasien;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Riwayat penyakit sekarang</td>
										<td>: 
											<!-- <input  type='text' disabled name='riwayat_penyakit_sekarang' value='<?php echo $riwayat_penyakit_sekarang;?>' size='80'> -->
											<textarea disabled name= "riwayat_penyakit_sekarang" id="fisik" style="min-width:630px; min-height:80px;"><?php echo $riwayat_penyakit_sekarang;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Riwayat penyakit dahulu</td>
										<td>: 
											<!-- <input  type='text' disabled name='riwayat_penyakit_dahulu' value='<?php echo $riwayat_penyakit_dahulu;?>'> -->
											<textarea disabled name= "riwayat_penyakit_dahulu" id="fisik" style="min-width:630px; min-height:80px;"><?php echo $riwayat_penyakit_dahulu;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Riwayat penyakit keluarga</td>
										<td>: 
											<!-- <input  type='text' disabled name='riwayat_penyakit_keluarga' value='<?php echo $riwayat_penyakit_keluarga;?>'> -->
											<textarea disabled name= "riwayat_penyakit_keluarga" id="fisik" style="min-width:630px; min-height:80px;"><?php echo $riwayat_penyakit_keluarga;?></textarea>
										</td>
									</tr>
									<tr>
										<td>Obat yang sedang dikonsumsi</td>
										<td>: <input type='radio' disabled  name='obat_sedang_dikonsumsi' value='Tidak ada' <?php if ($obat_sedang_dikonsumsi=="Tidak ada"){echo "checked";}?>>Tidak ada <input type='radio' disabled  name='obat_sedang_dikonsumsi' value='Ada' <?php if ($obat_sedang_dikonsumsi=="Ada"){echo "checked";}?>>Ada, tulis di form </td>
									</tr>
									<tr>
										<td>Riwayat alergi </td>
										<td>: <input type='radio' disabled  name='riwayat_alergi' value='Tidak' <?php if ($riwayat_alergi=="Tidak"){echo "checked";}?>>Tidak
											<input type='radio' disabled  name='riwayat_alergi' value='Ya' <?php if ($riwayat_alergi=="Ya"){echo "checked";}?>>Ya, 
											Keterangan Alergi : <input  type='text' disabled name='riwayat_alergi_alergi' value='<?php echo $riwayat_alergi_alergi;?>'>
										</td>
									</tr>
									<tr>
										<td>Riwayat transfusi darah</td>
										<td>: <input type='checkbox' disabled name='asanak22' value='Tidak' <?php if ($asanak22=="Tidak"){echo "checked";}?>>Tidak
											<input type='checkbox' disabled name='asanak22' value='Ya' <?php if ($asanak22=="Ya"){echo "checked";}?>>Ya
										</td>
									</tr>
									<tr>
										<td>Reaksi Alergi</td>
										<td>: <input type='checkbox' disabled name='asanak23' value='Tidak' <?php if ($asanak23=="Tidak"){echo "checked";}?>>Tidak 
											<input type='checkbox' disabled name='asanak23' value='Ya' <?php if ($asanak23=="Ya"){echo "checked";}?>>Ya 
											<input  type='text' disabled name='asanak24' value='<?php echo $asanak24;?>'>
										</td>
									</tr>
									<tr>
										<td>Riwayat merokok</td>
										<td>: <input  type='text' disabled name='asanak25' value='<?php echo $asanak25;?>'>
										</td>
									</tr>
									<tr>
										<td>Riwayat minum minuman keras</td>
										<td>: <input  type='text' disabled name='asanak26' value='<?php echo $asanak26;?>'>
										</td>
									</tr>
									<tr>
										<td>Riwayat pergi keluar negeri</td>
										<td>: <input  type='text' disabled name='asanak27' value='<?php echo $asanak27;?>'>
										</td>
									</tr>
								</table>							

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
										<td coslpan='2'><input type='checkbox' disabled name='ku_kesadaran' value='Composmentis' <?php if ($ku_kesadaran=="Composmentis"){echo "checked";}?>>Composmentis 
											<input type='checkbox' disabled name='ku_kesadaran' value='Apatis' <?php if ($ku_kesadaran=="Apatis"){echo "checked";}?>>Apatis
											<input type='checkbox' disabled name='ku_kesadaran' value='Somnolent' <?php if ($ku_kesadaran=="Somnolent"){echo "checked";}?>>Somnolent 
											<input type='checkbox' disabled name='ku_kesadaran' value='Sopor' <?php if ($ku_kesadaran=="Sopor"){echo "checked";}?>>Sopor
											<input type='checkbox' disabled name='ku_kesadaran' value='Coma' <?php if ($ku_kesadaran=="Coma"){echo "checked";}?>>Coma
										</td>
									</tr>
									<tr>
										<td><b>GCS</b></td>
										<td coslpan='2'> 
											E &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input  type='text' disabled name='ku_gcs_e' value='<?php echo $ku_gcs_e;?>' size='5'>
											V : <input  type='text' disabled name='ku_gcs_v' value='<?php echo $ku_gcs_v;?>' size='5'>
											M : <input  type='text' disabled name='ku_gcs_m' value='<?php echo $ku_gcs_m;?>' size='5'>
											<br>
											Total : <input  type='text' disabled name='total_gcs' value='<?php echo $total_gcs;?>' size='5'>
										</td>
									</tr>
									<tr><td>Berat Badan</td>
										<td colspan='2'>
											<input  type='text' disabled name='ku_beratbadan' value='<?php echo $ku_beratbadan;?>' size='5'> Kg,
											&nbsp;Tinggi Badan &nbsp;&nbsp;&nbsp;: 
											<input  type='text' disabled name='ku_tinggibadan' value='<?php echo $ku_tinggibadan;?>' size='5'> Cm
										</td>
									</tr>
									<tr><td>Suhu</td>
										<td colspan='2'>
											<input  type='text' disabled name='ku_suhu' value='<?php echo $ku_suhu;?>' size='5'> &#8451;
											Tekanan Darah : <input  type='text' disabled name='ku_tensi' value='<?php echo $ku_tensi;?>' size='5'> /mmHg
										</td>
									</tr>
									<tr><td>Nadi</td>
										<td colspan='2'>
											<input  type='text' disabled name='ku_nadi' value='<?php echo $ku_nadi;?>'> x/mnt 
											<input type='checkbox' disabled name='ku_nadi_ket' value='Teratur' <?php if ($ku_nadi_ket=="Teratur"){echo "checked";}?>>Teratur    
											<input type='checkbox' disabled name='ku_nadi_ket' value='Tidak Teratur' <?php if ($ku_nadi_ket=="Tidak Teratur"){echo "checked";}?>>Tidak Teratur
										</td>
									</tr>
									<tr>
										<td>Nafas</td>
										<td coslpan='2'> 
											<input  type='text' disabled name='ku_nafas' value='<?php echo $ku_nafas;?>'> x/mnt
										</td>
									</tr>
									<tr>
										<td>SpO2</td>
										<td coslpan='2'> 
											<input  type='text' disabled name='ku_spo' value='<?php echo $ku_spo;?>'> %
										</td>
									</tr>
								</table>


							</td>
							<td>
<!-- 								<input type='checkbox' disabled name='asanak72' value='Hipertermi' <?php if ($asanak72=="Hipertermi"){echo "checked";}?>>Hipertermi<br>
	<input type='checkbox' disabled name='asanak73' value='Hipotermia' <?php if ($asanak73=="Hipotermia"){echo "checked";}?>>Hipotermia<br> -->

	<br>
	<input type='checkbox' disabled name='m1' value='Y' <?php if ($m1=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
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

		echo "<input type='checkbox' disabled name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
		$no+=1;
	}
	?>
	<br>
	

</td>
</tr>

<tr>
	<td>
		B. PEMERIKSAAN FISIK<br>
		<table border="0">
			<tr>
				<td>Kepala</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak38' value='Tidak ada kelainan ' <?php if ($asanak38=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak38' value='Ada kelainan' <?php if ($asanak38=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='' value=''>
				</td>
			</tr>
			<tr>
				<td>Wajah</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak39' value='Tidak ada kelainan ' <?php if ($asanak39=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak39' value='Ada kelainan' <?php if ($asanak39=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='' value=''>
				</td>
			</tr>
			<tr>
				<td>Mata</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak40' value='Tidak ada kelainan ' <?php if ($asanak40=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak40' value='Ada kelainan' <?php if ($asanak40=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak41' value='<?php echo $asanak41;?>'>
				</td>
			</tr>
			<tr>
				<td>Telinga</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak42' value='Tidak ada kelainan ' <?php if ($asanak42=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak42' value='Ada kelainan' <?php if ($asanak42=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak43' value='<?php echo $asanak43;?>'>
				</td>
			</tr>
			<tr>
				<td>Hidung</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak44' value='Tidak ada kelainan ' <?php if ($asanak44=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak44' value='Ada kelainan' <?php if ($asanak44=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak45' value='<?php echo $asanak45;?>'>
				</td>
			</tr>
			<tr>
				<td>Mulut</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak46' value='Tidak ada kelainan ' <?php if ($asanak46=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak46' value='Ada kelainan' <?php if ($asanak46=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak47' value='<?php echo $asanak47;?>'>
				</td>
			</tr>
			<tr>
				<td>Leher</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak48' value='Tidak ada kelainan ' <?php if ($asanak48=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak48' value='Ada kelainan' <?php if ($asanak48=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak49' value='<?php echo $asanak49;?>'>
				</td>
			</tr>
			<tr>
				<td>Dada</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak50' value='Tidak ada kelainan ' <?php if ($asanak50=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak50' value='Ada kelainan' <?php if ($asanak50=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak51' value='<?php echo $asanak51;?>'>
				</td>
			</tr>
			<tr>
				<td>Perut</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak52' value='Tidak ada kelainan ' <?php if ($asanak52=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak52' value='Ada kelainan' <?php if ($asanak52=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak53' value='<?php echo $asanak45;?>'>
				</td>
			</tr>
			<tr>
				<td>Anggota Gerak</td>
				<td coslpan='2'>:
					<input type='radio' disabled  name='asanak54' value='Tidak ada kelainan ' <?php if ($asanak54=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
					<input type='radio' disabled  name='asanak54' value='Ada kelainan' <?php if ($asanak54=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
					<input  type='text' disabled name='asanak55' value='<?php echo $asanak55;?>'>
				</td>
			</tr>
<!-- 									<tr>
										<td>Gerak</td>
										<td coslpan='2'>:
											<input type='checkbox' disabled name='asanak56' value='Tidak ada kelainan ' <?php if ($asanak56=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
											<input type='checkbox' disabled name='asanak56' value='Ada kelainan' <?php if ($asanak56=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
											<input  type='text' disabled name='asanak57' value='<?php echo $asanak57;?>'>
										</td>
									</tr> -->
									<tr>
										<td>Kulit</td>
										<td coslpan='2'>:
											<input type='radio' disabled  name='asanak58' value='Tidak ada kelainan ' <?php if ($asanak58=="Tidak ada kelainan "){echo "checked";}?>>Tidak ada kelainan  
											<input type='radio' disabled  name='asanak58' value='Ada kelainan' <?php if ($asanak58=="Ada kelainan"){echo "checked";}?>>Ada kelainan : 
											<input  type='text' disabled name='asanak59' value='<?php echo $asanak59;?>'>
										</td>
									</tr>

								</table>
								- Jika ada Luka, maka lanjutkan dengan <a href='#'>Form Asesmen Luka</a><br>
								
							</td>
							<td>
<!-- 								<input type='checkbox' disabled name='asanak207' value='Bersihan jalan napas tidak efektif' <?php if ($asanak207=="Bersihan jalan napas tidak efektif"){echo "checked";}?>>Bersihan jalan napas tidak efektif<br>
								<input type='checkbox' disabled name='asanak74' value='Gangguan pertukaran gas' <?php if ($asanak74=="Gangguan pertukaran gas"){echo "checked";}?>>Gangguan pertukaran gas<br>
								<input type='checkbox' disabled name='asanak75' value='Pola napas tidak efektif' <?php if ($asanak75=="Pola napas tidak efektif"){echo "checked";}?>>Pola napas tidak efektif<br>
								<input type='checkbox' disabled name='asanak76' value='Resiko aspirasi' <?php if ($asanak76=="Resiko aspirasi"){echo "checked";}?>>Resiko aspirasi<br>
								<input type='checkbox' disabled name='asanak77' value='Penurunan curah jantung' <?php if ($asanak77=="Penurunan curah jantung"){echo "checked";}?>>Penurunan curah jantung<br>
								<input type='checkbox' disabled name='asanak78' value='Perfusi perifer tidak efektif' <?php if ($asanak78=="Perfusi perifer tidak efektif"){echo "checked";}?>>Perfusi perifer tidak efektif<br>
								<input type='checkbox' disabled name='asanak79' value='Resiko perdarahan' <?php if ($asanak79=="Resiko perdarahan"){echo "checked";}?>>Resiko perdarahan<br>
								<input type='checkbox' disabled name='asanak80' value='Resiko perfusi miokard tidak efektif' <?php if ($asanak80=="Resiko perfusi miokard tidak efektif"){echo "checked";}?>>Resiko perfusi miokard tidak efektif<br>
								<input type='checkbox' disabled name='asanak81' value='Kerusakan integritas jaringan' <?php if ($asanak81=="Kerusakan integritas jaringan"){echo "checked";}?>>Kerusakan integritas jaringan<br>
								<input type='checkbox' disabled name='asanak194' value='Gangguan integritas kulit/jaringan' <?php if ($asanak194=="Gangguan integritas kulit/jaringan"){echo "checked";}?>>Gangguan integritas kulit/jaringan<br>
							-->
							<br>
							<input type='checkbox' disabled name='m2' value='Y' <?php if ($m2=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>

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

								echo "<input type='checkbox' disabled name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							<br>
							


						</td>
					</tr>

					<tr>
						<td>
							C.  RIWAYAT KEHAMILAN/ PERSALINAN, IMUNISASI DAN TUMBUH KEMBANG<br>
							<table border="0">
								<tr>
									<td>Usia ibu saat hamil</td>
									<td coslpan='2'><input  type='text' disabled name='r_hamil1' value='<?php echo $r_hamil1;?>'>
									</td>
								</tr>
								<tr>
									<td>Gravida ke  </td>
									<td coslpan='2'><input  type='text' disabled name='r_hamil2' value='<?php echo $r_hamil2;?>'>
									</td>
								</tr>
								<tr>
									<td>Gangguan Hamil (Trimester 1) </td>
									<td coslpan='2'><input  type='text' disabled name='r_hamil3' value='<?php echo $r_hamil3;?>'>
									</td>
								</tr>
								<tr>
									<td>Type Persalinan</td>
									<td coslpan='2'>
										<input type='radio' disabled  name='type_persalinan1' value='Spontan' <?php if ($type_persalinan1=="Spontan"){echo "checked";}?>>
										Spontan  
										<input type='radio' disabled  name='type_persalinan2' value='Induksi' <?php if ($type_persalinan2=="Induksi"){echo "checked";}?>>
										Induksi
										<input type='radio' disabled  name='type_persalinan3' value='Forcep' <?php if ($type_persalinan3=="Forcep"){echo "checked";}?>>
										Forcep
										<input type='radio' disabled  name='type_persalinan4' value='Sectio Caesarea' <?php if ($type_persalinan4=="Sectio Caesarea"){echo "checked";}?>>
										Sectio Caesarea
										<input type='radio' disabled  name='type_persalinan5' value='Vacum' <?php if ($type_persalinan5=="Vacum"){echo "checked";}?>>
										Vacum
									</td>
								</tr>
								<tr>
									<td>BB lahir </td>
									<td coslpan='2'>
										<input  type='text' disabled name='asanak61' value='<?php echo $asanak61;?>'>gram
										Panjang Badan : <input  type='text' disabled name='asanak62' value='<?php echo $asanak62;?>'>
										cm    
										Lingkar Kepala: <input  type='text' disabled name='asanak63' value='<?php echo $asanak63;?>'>
										m
									</td>
								</tr>
								<tr>
									<td>BB saat dikaji </td>
									<td coslpan='2'>
										<input  type='text' disabled name='asanak64' value='<?php echo $asanak64;?>'>gram
										Tinggi Badan: <input  type='text' disabled name='asanak65' value='<?php echo $asanak65;?>'>
										cm    
									</td>
								</tr>
								<tr>
									<td>Imunisasi dasar </td>
									<td coslpan='2'>
										<input type='radio' disabled  name='asanak66' value='Lengkap' <?php if ($asanak66=="Lengkap"){echo "checked";}?>>
										Lengkap  
										<input type='radio' disabled  name='asanak66' value='Tidak Pernah' <?php if ($asanak66=="Tidak Pernah"){echo "checked";}?>>
										Tidak Pernah
										<input type='radio' disabled  name='asanak66' value='Tidak Lengkap' <?php if ($asanak66=="Tidak Lengkap"){echo "checked";}?>>
										Tidak Lengkap, sebutkan yang belum <input  type='text' disabled name='asanak69' value='<?php echo $asanak69;?>'>

									</td>
								</tr>
								<?php if ($umur <= 3) {?>
									<tr>
										<td>Riwayat tumbuh kembang <br>(dikaji pada pasien usia ≤ 3 tahun)</td>
										<td coslpan='2'>											
											<input type='checkbox' disabled name='r_tumbuhkembang1' value='Tengkurap' <?php if ($r_tumbuhkembang1=="Tengkurap"){echo "checked";}?>>Tengkurap, 
											usia :<input  type='text' disabled name='r_tumbuhkembang2' value='<?php echo $r_tumbuhkembang2;?>'><br>

											<input type='checkbox' disabled name='r_tumbuhkembang3' value='Berjalan' <?php if ($r_tumbuhkembang3=="Berjalan"){echo "checked";}?>>Berjalan, 
											usia &nbsp;&nbsp;&nbsp;&nbsp;:<input  type='text' disabled name='r_tumbuhkembang4' value='<?php echo $r_tumbuhkembang4;?>'><br>

											<input type='checkbox' disabled name='r_tumbuhkembang5' value='Duduk' <?php if ($r_tumbuhkembang5=="Duduk"){echo "checked";}?>>Duduk, 
											usia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<input  type='text' disabled name='r_tumbuhkembang6' value='<?php echo $r_tumbuhkembang6;?>'><br>

											<input type='checkbox' disabled name='r_tumbuhkembang7' value='Bicara' <?php if ($r_tumbuhkembang7=="Bicara"){echo "checked";}?>>Bicara, 
											usia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<input  type='text' disabled name='r_tumbuhkembang8' value='<?php echo $r_tumbuhkembang8;?>'><br>

											<input type='checkbox' disabled name='r_tumbuhkembang9' value='Berdiri' <?php if ($r_tumbuhkembang9=="Berdiri"){echo "checked";}?>>Berdiri, 
											usia &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<input  type='text' disabled name='r_tumbuhkembang10' value='<?php echo $r_tumbuhkembang10;?>'><br>

											<input type='checkbox' disabled name='r_tumbuhkembang11' value='Tumbuh gigi' <?php if ($r_tumbuhkembang11=="Tumbuh gigi"){echo "checked";}?>>Tumbuh gigi, 
											usia :<input  type='text' disabled name='r_tumbuhkembang12' value='<?php echo $r_tumbuhkembang12;?>'><br>

										</td>
									</tr>
								<?php } ?>
							</table>
							
						</td>
						<td>								
							<!-- 								<input type='checkbox' disabled name='asanak82' value='Gangguan Pertumbuhan dan Perkembangan' <?php if ($asanak82=="Gangguan Pertumbuhan dan Perkembangan"){echo "checked";}?>>Gangguan Pertumbuhan dan Perkembangan<br> -->
						</td>
					</tr>


					<tr>
						<td>
							D. STATUS FUNGSIONAL<br>

							1. Nutrisi dan Hidrasi<br>
							<input type='checkbox' disabled name='nutrisi1' value='Tidak ada keluhan' <?php if ($nutrisi1=="Tidak ada keluhan"){echo "checked";}?>>Tidak ada keluhan
							<input type='checkbox' disabled name='nutrisi2' value='Nafsu makan menurun' <?php if ($nutrisi2=="Nafsu makan menurun"){echo "checked";}?>>Nafsu makan menurun
							<input type='checkbox' disabled name='nutrisi3' value='Mual' <?php if ($nutrisi3=="Mual"){echo "checked";}?>>Mual
							<input type='checkbox' disabled name='nutrisi4' value='Muntah' <?php if ($nutrisi4=="Muntah"){echo "checked";}?>>Muntah
							<input type='checkbox' disabled name='nutrisi5' value='Diet' <?php if ($nutrisi5=="Diet"){echo "checked";}?>>Diet
							Lainnya <input  type='text' disabled name='nutrisi6' value='<?php echo $nutrisi6;?>'>
							<br>
							Lakukan skrining nutrisi dengan Strong Kids<br>

							<table>
								<tr>
									<td style="border: 1px solid;">No</td>
									<td style="border: 1px solid;">Pertanyaan</td>
									<td style="border: 1px solid;">Jawaban</td>
									<td style="border: 1px solid;">poin</td>
									<td style="border: 1px solid;">Skor</td>
								</tr>

								<tr>
									<td style="border: 1px solid;" rowspan="2">1</td>
									<td style="border: 1px solid;" rowspan="2">Apakah pasien tampak kurus</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak85' value='YA' <?php if ($asanak85=="YA"){echo "checked";}?>>Ya</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak85){
											echo $asanak85_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak86' value='Tidak' <?php if ($asanak86=="Tidak"){echo "checked";}?>>Tidak</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak86){
											echo $asanak86_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;" rowspan="2">2</td>
									<td style="border: 1px solid;" rowspan="2">Apakah terdapat penurunan BB dalam satu bulan terakhir ? (berdasarkan penilaian obyektif data BB bila ada ATAU penilaian subyektif dari orang tua pasien ATAU untuk bayi < 1 tahun BB tidak naik selama 3 bulan terakhir) </td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak87' value='YA' <?php if ($asanak87=="YA"){echo "checked";}?>>Ya</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak87){
											echo $asanak87_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak88' value='Tidak' <?php if ($asanak88=="Tidak"){echo "checked";}?>>Tidak</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak88){
											echo $asanak88_skor='0';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;" rowspan="2">3</td>
									<td style="border: 1px solid;" rowspan="2">Apakah terdapat SALAH SATU dari kondisi berikut :

										Diare ≥ 5 kali/ hari dan atau muntah > 3 kali / hari dalam seminggu terakhir
									Asupan makanan berkurang selama 1 minggu terakhir</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak89' value='YA' <?php if ($asanak89=="YA"){echo "checked";}?>>Ya</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak89){
											echo $asanak89_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak90' value='Tidak' <?php if ($asanak90=="Tidak"){echo "checked";}?>>Tidak</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak90){
											echo $asanak90_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;" rowspan="2">4</td>
									<td style="border: 1px solid;" rowspan="2">Apakah terdapat penyakit atau keadaan yang mengakibatkan malnutrisi, antara lain :

									Diare kronik (lebih dari 2 minggu), suspec Penyakit jantung bawaan, suspec Infeksi HIV-AIDS, suspec Kanker, penyakit hati kronik, penyakit ginjal kronik, TB paru, luka bakar luas, kelainan anatomi daerah mulut yang menyebabkan kesulitan makan (misal : bibir sumbing), trauma, kelainan metabolic bawaan (inborn error metabolism), retardasi mental, keterlambatan perkembangan, rencana/pasca operasi mayor (misal : laparatomi, torakotomi), lain – lain : (bersadarkan pertimbangan dokter) </td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak91' value='YA' <?php if ($asanak91=="YA"){echo "checked";}?>>Ya</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak91){
											echo $asanak91_skor='1';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak92' value='Tidak' <?php if ($asanak92=="Tidak"){echo "checked";}?>>Tidak</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak92){
											echo $asanak92_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;" colspan="4">Total Skor</td>
									<td style="border: 1px solid;">
										<?php 
										echo $asanak85_skor_total=$asanak85_skor+$asanak86_skor+$asanak87_skor+$asanak88_skor+$asanak89_skor+$asanak90_skor+$asanak91_skor+$asanak92_skor; 
										?>
									</td>
								</tr>

							</table>
							&nbsp;
							<br>
							Keterangan : []Skor 4-5 : Risiko Tinggi, []Skor 1-3 : Risiko Sedang []Skor 0 : Risko rendah<br>

							<?php 
							echo "<h5>";
							echo "[".$asanak85_skor_total."]";

							if($asanak85_skor_total == 0){echo $asanak85_skor_total="Risiko rendah";}
							if($asanak85_skor_total >= 1 and $asanak85_skor_total <= 3 ){echo $asanak85_skor_total="Risiko sedang";}
							if($asanak85_skor_total >= 4){echo $asanak85_skor_total="Risiko tinggi";}
							echo "</h5>";
							?>

							Bila terdapat sedang sampai risiko tinggi, Dilanjutkan dengan asesmen gizi oleh nutrisionis/dietisien
						</td>
						<td>
							<!-- <input type='checkbox' disabled name='asanak195' value='Resiko infeksi' <?php if ($asanak195=="Resiko infeksi"){echo "checked";}?>>Resiko infeksi<br> -->

							<br>
							<input type='checkbox' disabled name='m8' value='Y' <?php if ($m8=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
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

								echo "<input type='checkbox' disabled name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							


						</td>
					</tr>

					<tr>
						<td>
							2. ELIMINASI<br>
							BAK : 
							Frekuensi <input  type='text' disabled name='asanak93' value='<?php echo $asanak93;?>'> x/hari  
							Warna : <input  type='text' disabled name='asanak94' value='<?php echo $asanak94;?>'> <br>
							Gangguan : 
							<input type='checkbox' disabled name='asanak95' value='Tidak' <?php if ($asanak95=="Tidak"){echo "checked";}?>>Tidak
							<input type='checkbox' disabled name='asanak95' value='Retensi' <?php if ($asanak95=="Retensi"){echo "checked";}?>>Retensi
							<input type='checkbox' disabled name='asanak95' value='Inkontinensia' <?php if ($asanak95=="Inkontinensia"){echo "checked";}?>>Inkontinensia
							<input type='checkbox' disabled name='asanak95' value='Anuri' <?php if ($asanak95=="Anuri"){echo "checked";}?>>Anuri
							<input type='checkbox' disabled name='asanak95' value='Oliguri' <?php if ($asanak95=="Oliguri"){echo "checked";}?>>Oliguri
							<input type='checkbox' disabled name='asanak95' value='Hematuri' <?php if ($asanak95=="Hematuri"){echo "checked";}?>>Hematuri
							<input type='checkbox' disabled name='asanak95' value='Lain-lain' <?php if ($asanak95=="Lain-lain"){echo "checked";}?>>Lain-lain<br>
							Penggunaan alat medis : 
							<input type='checkbox' disabled name='asanak96' value='Tidak' <?php if ($asanak96=="Tidak"){echo "checked";}?>>Tidak   
							<input type='checkbox' disabled name='asanak96' value='kateter' <?php if ($asanak96=="kateter"){echo "checked";}?>>kateter  
							<input type='checkbox' disabled name='asanak96' value='Lain-lain' <?php if ($asanak96=="Lain-lain"){echo "checked";}?>>Lain-lain :   
							Tanggal pemasangan : <input  type='text' disabled name='asanak97' value='<?php echo $asanak97;?>'><br>
							BAB : 
							<input type='checkbox' disabled name='asanak98' value='Normal' <?php if ($asanak98=="Normal"){echo "checked";}?>>Normal
							<input type='checkbox' disabled name='asanak98' value='Konstipasi' <?php if ($asanak98=="Konstipasi"){echo "checked";}?>>Konstipasi 
							<input type='checkbox' disabled name='asanak98' value='Diare' <?php if ($asanak98=="Diare"){echo "checked";}?>>Diare : 
							Frekuensi  : <input  type='text' disabled name='asanak99' value='<?php echo $asanak99;?>'>x/hari,  
							Konsistensi : <input  type='text' disabled name='asanak100' value='<?php echo $asanak100;?>'>
							Warna :<input  type='text' disabled name='asanak101' value='<?php echo $asanak101;?>'><br>
							Penggunaan alat medis : 
							<input type='checkbox' disabled name='asanak102' value='Tidak' <?php if ($asanak102=="Tidak"){echo "checked";}?>>Tidak
							<input type='checkbox' disabled name='asanak103' value='Kolostomi' <?php if ($asanak103=="Kolostomi"){echo "checked";}?>>Kolostomi
							<input type='checkbox' disabled name='asanak104' value='Lain-lain' <?php if ($asanak104=="Lain-lain"){echo "checked";}?>>Lain-lain<br>
							<br>
						</td>
						<td>
							<!-- <input type='checkbox' disabled name='asanak206' value='Gangguan eliminasi urin' <?php if ($asanak206=="Gangguan eliminasi urin"){echo "checked";}?>> Gangguan eliminasi urin<br> -->

							<br>
							<input type='checkbox' disabled name='m5' value='Y' <?php if ($m5=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
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

								echo "<input type='checkbox' disabled name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							
							<br>


						</td>
					</tr>

					<?php if($umur>=12) { ?>
						<tr>
							<td>
								3. AKTIVITAS DAN LATIHAN (dengan menggunakan barthel indeks)<br>
								<input type='radio' disabled  name='asanak105' value='Tidur/istirahat' <?php if ($asanak105=="Tidur/istirahat"){echo "checked";}?>>Tidur/istirahat   
								<input type='radio' disabled  name='asanak105' value='Tidak ada keluhan' <?php if ($asanak105=="Tidak ada keluhan"){echo "checked";}?>>Tidak ada keluhan<br> 

								<table>
									<tr>
										<td style="border: 1px solid;">No</td>
										<td style="border: 1px solid;">Fungsi</td>
										<td style="border: 1px solid;">Nilai</td>
										<td style="border: 1px solid;">Keterangan</td>
										<td style="border: 1px solid;">Skor</td>
									</tr>

									<tr>
										<td style="border: 1px solid;">1</td>
										<td style="border: 1px solid;">Buang Air Besar</td>
										<td style="border: 1px solid;">0</td>
										<td style="border: 1px solid;"><input type='radio' disabled  name='asanak106' value='Tak Terkendali/Tak Teratur (Perlu Pencahar)' <?php if ($asanak106=="Tak Terkendali/Tak Teratur (Perlu Pencahar)"){echo "checked";}?>>
											Tak Terkendali/Tak Teratur (Perlu Pencahar)
										</td>
										<td style="border: 1px solid;">
											<?php 
											if($asanak106){
												echo $taktivitas1_skor='0';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">1</td>
										<td style="border: 1px solid;"><input type='radio' disabled  name='asanak107' value='Kadang-Kadang Tak Terkendali (1 X Seminggu)' <?php if ($asanak107=="Kadang-Kadang Tak Terkendali (1 X Seminggu)"){echo "checked";}?>>
											Kadang-Kadang Tak Terkendali (1 X Seminggu)
										</td>
										<td style="border: 1px solid;">
											<?php 
											if($asanak107){
												echo $taktivitas2_skor='1';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">2</td>
										<td style="border: 1px solid;"><input type='radio' disabled  name='asanak108' value='Terkendali/Teratur' <?php if ($asanak108=="Terkendali/Teratur"){echo "checked";}?>>
										Terkendali/Teratur</td>
										<td style="border: 1px solid;">
											<?php 
											if($asanak108){
												echo $taktivitas3_skor='2';
											}
											?>
										</td>
									</tr>

									<tr>
										<td style="border: 1px solid;">2</td>
										<td style="border: 1px solid;">Buang Air Kecil</td>
										<td style="border: 1px solid;">0</td>
										<td style="border: 1px solid;"><input type='radio' disabled  name='asanak109' value='Tak Terkendali Atau Pakai Kateter' <?php if ($asanak109=="Tak Terkendali Atau Pakai Kateter"){echo "checked";}?>>
										Tak Terkendali Atau Pakai Kateter</td>
										<td style="border: 1px solid;">
											<?php 
											if($asanak109){
												echo $taktivitas4_skor='0';
											}
											?>
										</td>
									</tr>
									<tr>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;"></td>
										<td style="border: 1px solid;">1</td>
										<td style="border: 1px solid;"><input type='radio' disabled  name='asanak110' value='Kadang-Kadang Tak Terkendali (Hanya 1 X 24 Jam)' <?php if ($asanak110=="Kadang-Kadang Tak Terkendali (Hanya 1 X 24 Jam)"){echo "checked";}?>>
										Kadang-Kadang Tak Terkendali (Hanya 1 X 24 Jam)</td>
										<td style="border: 1px solid;"><?php 
										if($asanak110){
											echo $taktivitas5_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak111' value='YA' <?php if ($asanak111=="YA"){echo "checked";}?>>
									Terkendali/Teratur</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak111){
											echo $taktivitas6_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">Perawatan Diri</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak112' value='YA' <?php if ($asanak112=="YA"){echo "checked";}?>>
									Butuh Pertolongan Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak112){
											echo $taktivitas7_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak113' value='YA' <?php if ($asanak113=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak113){
											echo $taktivitas8_skor='1';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">4</td>
									<td style="border: 1px solid;">Penggunaan Toilet</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak114' value='YA' <?php if ($asanak114=="YA"){echo "checked";}?>>
									Tergantung Pertolongan Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak114){
											echo $taktivitas9_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak115' value='YA' <?php if ($asanak115=="YA"){echo "checked";}?>>
									Perlu Pertolongan Pada Beberapa Kegiatan Tetapi Dapat Mengerjakan Sendiri Beberapa Kegiatan Yang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak115){
											echo $taktivitas10_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak116' value='YA' <?php if ($asanak116=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak116){
											echo $taktivitas11_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">5</td>
									<td style="border: 1px solid;">Makan</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak117' value='YA' <?php if ($asanak117=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak117){
											echo $taktivitas12_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak118' value='YA' <?php if ($asanak118=="YA"){echo "checked";}?>>
									Perlu Di Tolong Memotong Makanan</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak118){
											echo $taktivitas13_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak119' value='YA' <?php if ($asanak119=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak119){
											echo $taktivitas14_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">6</td>
									<td style="border: 1px solid;">Transfer</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak120' value='YA' <?php if ($asanak120=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak120){
											echo $taktivitas15_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak121' value='YA' <?php if ($asanak121=="YA"){echo "checked";}?>>
									Perlu Banyak Bantuan Untuk Biasa Duduk</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak121){
											echo $taktivitas16_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak122' value='YA' <?php if ($asanak122=="YA"){echo "checked";}?>>
									Bantuan Minimal 1 Orang</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak122){
											echo $taktivitas17_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak123' value='YA' <?php if ($asanak123=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak123){
											echo $taktivitas18_skor='3';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">7</td>
									<td style="border: 1px solid;">Mobilitas</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak124' value='YA' <?php if ($asanak124=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak124){
											echo $taktivitas19_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak125' value='YA' <?php if ($asanak125=="YA"){echo "checked";}?>>
									Bisa (Pindah) Dengan Kursi Roda</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak125){
											echo $taktivitas20_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak126' value='YA' <?php if ($asanak126=="YA"){echo "checked";}?>>
									Berjalan Dengan Bantuan 1 Orang</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak126){
											echo $taktivitas21_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak127' value='YA' <?php if ($asanak127=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak127){
											echo $taktivitas22_skor='3';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">8</td>
									<td style="border: 1px solid;">Berpakaian</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak128' value='YA' <?php if ($asanak128=="YA"){echo "checked";}?>>
									Tergantung Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak128){
											echo $taktivitas23_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak129' value='YA' <?php if ($asanak129=="YA"){echo "checked";}?>>
									Sebagian Dibantu (Misal : Memakai Baju)</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak129){
											echo $taktivitas24_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak130' value='YA' <?php if ($asanak130=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak130){
											echo $taktivitas25_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">9</td>
									<td style="border: 1px solid;">Naik Turun Tangga</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak131' value='YA' <?php if ($asanak131=="YA"){echo "checked";}?>>
									Tidak Mampu</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak131){
											echo $taktivitas26_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak132' value='YA' <?php if ($asanak132=="YA"){echo "checked";}?>>
									Butuh Pertolongan</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak132){
											echo $taktivitas27_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak133' value='YA' <?php if ($asanak133=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak133){
											echo $taktivitas28_skor='2';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">10</td>
									<td style="border: 1px solid;">Mandi</td>
									<td style="border: 1px solid;">0</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak134' value='YA' <?php if ($asanak134=="YA"){echo "checked";}?>>
									Tergantung Orang Lain</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak134){
											echo $taktivitas29_skor='0';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;"><input type='radio' disabled  name='asanak135' value='YA' <?php if ($asanak135=="YA"){echo "checked";}?>>
									Mandiri</td>
									<td style="border: 1px solid;">
										<?php 
										if($asanak135){
											echo $taktivitas30_skor='1';
										}
										?>
									</td>
								</tr>


							</table>
							<br>
							[]12 – 20 : Minimal Care [] 9 – 11 : Partial Care [] 0 – 8 : Total Care
							&nbsp;
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
							<input type='checkbox' disabled name='m7' value='Y' <?php if ($m7=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
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

								echo "<input type='checkbox' disabled name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
								$no+=1;
							}
							?>
							

						</td>
					</tr>

				<?php } ?>

				<tr>
					<td>
						E. NYERI<br>
						Keluhan nyeri : 
						<input type='radio' disabled  name='asanak136' value='Tidak' <?php if ($asanak136=="Tidak"){echo "checked";}?>>Tidak
						<input type='radio' disabled  name='asanak136' value='Ya' <?php if ($asanak136=="Ya"){echo "checked";}?>>Ya, 
						Skala : <input  type='text' disabled name='asanak137' value='<?php echo $asanak137;?>'><br>
						Lokasi Nyeri <input  type='text' disabled name='asanak138' value='<?php echo $asanak138;?>'><br>
						1. Apakah nyeri berpindah dari tempat satu ke tempat lain? 
						<input type='radio' disabled  name='asanak139' value='Tidak' <?php if ($asanak139=="Tidak"){echo "checked";}?>>Tidak  
						<input type='radio' disabled  name='asanak139' value='Ya' <?php if ($asanak139=="Ya"){echo "checked";}?>>Ya<br>
						2. Berapa lama Nyeri ?  
						<input type='radio' disabled  name='asanak140' value='Akut : < 3 bulan' <?php if ($asanak140=="Akut : < 3 bulan"){echo "checked";}?>>Akut : < 3 bulan   
						<input type='radio' disabled  name='asanak140' value='Kronik : > 3 bulan' <?php if ($asanak140=="Kronik : > 3 bulan"){echo "checked";}?>>Kronik : > 3 bulan<br>
						3. Gambaran rasa nyeri :
						<input type='radio' disabled  name='asanak141' value='Nyeri tumpul' <?php if ($asanak141=="Nyeri tumpul"){echo "checked";}?>>  Nyeri tumpul
						<input type='radio' disabled  name='asanak141' value='Seperti ditarik' <?php if ($asanak141=="Seperti ditarik"){echo "checked";}?>>Seperti ditarik  
						<input type='radio' disabled  name='asanak141' value='Seperti ditusuk' <?php if ($asanak141=="Seperti ditusuk"){echo "checked";}?>>Seperti ditusuk
						<input type='radio' disabled  name='asanak141' value='Seperti dipukul' <?php if ($asanak141=="Seperti dipukul"){echo "checked";}?>>Seperti dipukul  
						<input type='radio' disabled  name='asanak141' value='Seperti dibakar' <?php if ($asanak141=="Seperti dibakar"){echo "checked";}?>>Seperti dibakar 
						<input type='radio' disabled  name='asanak141' value='Seperti berdenyut' <?php if ($asanak141=="Seperti berdenyut"){echo "checked";}?>>Seperti berdenyut 
						<input type='radio' disabled  name='asanak141' value='Seperti ditikam' <?php if ($asanak141=="Seperti ditikam"){echo "checked";}?>>Seperti ditikam 
						<input type='radio' disabled  name='asanak141' value='Seperti kram' <?php if ($asanak141=="Seperti kram"){echo "checked";}?>>Seperti kram <br>
						4. Seberapa sering anda mengalami nyeri ? 
						<input type='radio' disabled  name='asanak142' value='1―2 jam' <?php if ($asanak142=="1―2 jam"){echo "checked";}?>>1―2 jam  
						<input type='radio' disabled  name='asanak142' value='3―4 jam' <?php if ($asanak142=="3―4 jam"){echo "checked";}?>>3―4 jam  
						<input type='radio' disabled  name='asanak142' value='>4 jam' <?php if ($asanak142==">4 jam"){echo "checked";}?>>>4 jam 
						setiap  : 
						<input type='radio' disabled  name='asanak142' value='< 30 menit' <?php if ($asanak142=="< 30 menit"){echo "checked";}?>>< 30 menit 
						<input type='radio' disabled  name='asanak142' value='> 30 menit' <?php if ($asanak142=="> 30 menit"){echo "checked";}?>>> 30 menit <br>
						5. Apa yang membuat nyeri berkurang atau bertambah parah ? 
						<input type='radio' disabled  name='asanak143' value='Kompres hangat / dingin' <?php if ($asanak143=="Kompres hangat / dingin"){echo "checked";}?>>Kompres hangat / dingin 
						<input type='radio' disabled  name='asanak143' value='Istirahat' <?php if ($asanak143=="Istirahat"){echo "checked";}?>>Istirahat 
						<input type='radio' disabled  name='asanak143' value='Minum Obat' <?php if ($asanak143=="Minum Obat"){echo "checked";}?>>Minum Obat 
						<input type='radio' disabled  name='asanak143' value='Berubah posisi' <?php if ($asanak143=="Berubah posisi"){echo "checked";}?>>Berubah posisi<br>
						(pemeriksaan ini digunakan untuk anak-anak usia >3 tahun, untuk bayi gunakan NIPS, untuk anak dan pasien tidak sadar gunakan FLACC)
						<br>
						<br>
						Pemeriksaan Nyeri – FLACC PAIN SCALE<br>
						<table class="">
							<tr>
								<td style="border: 1px solid;" rowspan='2'>Katagori</td>
								<td style="border: 1px solid;" colspan='3'>Skor</td>
								<td style="border: 1px solid;" rowspan='2'>Total</td>
							</tr>
							<tr>
								<td style="border: 1px solid;">0</td>
								<td style="border: 1px solid;">1</td>
								<td style="border: 1px solid;">2</td>
							</tr>
							<tr>
								<td style="border: 1px solid;">FACE  (WAJAH)</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak144' value='Tak ada ekspresi' <?php if ($asanak144=="Tak ada ekspresi"){echo "checked";}?>>Tak ada ekspresi</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak144' value='Menyeringai' <?php if ($asanak144=="Menyeringai"){echo "checked";}?>>Menyeringai</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak144' value='Dagu gemetar & rahang dikatup erat' <?php if ($asanak144=="Dagu gemetar & rahang dikatup erat"){echo "checked";}?>>Dagu gemetar & rahang dikatup erat</td>
								<td style="border: 1px solid;">
									<?php 
									if($asanak144=='Tak ada ekspresi')
										{$ku_tnyeri1_skor=0;}
									if($asanak144=='Menyeringai')
										{$ku_tnyeri1_skor=1;}
									if($asanak144=='Dagu gemetar & rahang dikatup erat')
										{$ku_tnyeri1_skor=2;}
									echo $ku_tnyeri1_skor;
									?>
								</td>
							</tr>
							<tr>
								<td style="border: 1px solid;">LEG  (KAKI)</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak145' value='Normal' <?php if ($asanak145=="Normal"){echo "checked";}?>>Normal</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak145' value='Gelisah tenang' <?php if ($asanak145=="Gelisah tenang"){echo "checked";}?>>Gelisah tenang</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak145' value='Menendang/ melawan' <?php if ($asanak145=="Menendang/ melawan"){echo "checked";}?>>Menendang/ melawan</td>
								<td style="border: 1px solid;">
									<?php 
									if($asanak145=='Normal')
										{$ku_tnyeri2_skor=0;}
									if($asanak145=='Gelisah tenang')
										{$ku_tnyeri2_skor=1;}
									if($asanak145=='Menendang/ melawan')
										{$ku_tnyeri2_skor=2;}
									echo $ku_tnyeri2_skor;
									?>
								</td>
							</tr>
							<tr>
								<td style="border: 1px solid;">ACTIVITY (AKTIFITAS)</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak146' value='Tidur tenang' <?php if ($asanak146=="Tidur tenang"){echo "checked";}?>>Tidur tenang</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak146' value='Menggeliat tenang' <?php if ($asanak146=="Menggeliat tenang"){echo "checked";}?>>Menggeliat tenang</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak146' value='Menekuk, kaku' <?php if ($asanak146=="Menekuk, kaku"){echo "checked";}?>>Menekuk, kaku</td>
								<td style="border: 1px solid;">
									<?php 
									if($asanak146=='Tidur tenang')
										{$ku_tnyeri3_skor=0;}
									if($asanak146=='Menggeliat tenang')
										{$ku_tnyeri3_skor=1;}
									if($asanak146=='Menekuk, kaku')
										{$ku_tnyeri3_skor=2;}
									echo $ku_tnyeri3_skor;
									?>
								</td>
							</tr>
							<tr>
								<td style="border: 1px solid;">CRY (MENANGIS)</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak147' value='Tidur , tak ada tangisan' <?php if ($asanak147=="Tidur , tak ada tangisan"){echo "checked";}?>>Tidur , tak ada tangisan</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak147' value='Mengerang, merengek' <?php if ($asanak147=="Mengerang, merengek"){echo "checked";}?>>Mengerang, merengek</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak147' value='Menangis, menjerit' <?php if ($asanak147=="Menangis, menjerit"){echo "checked";}?>>Menangis, menjerit</td>
								<td style="border: 1px solid;">
									<?php 
									if($asanak147=='Tidur , tak ada tangisan')
										{$ku_tnyeri4_skor=0;}
									if($asanak147=='Mengerang, merengek')
										{$ku_tnyeri4_skor=1;}
									if($asanak147=='Menangis, menjerit')
										{$ku_tnyeri4_skor=2;}
									echo $ku_tnyeri4_skor;
									?>
								</td>
							</tr>
							<tr>
								<td style="border: 1px solid;">CONSOLABILITY</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak148' value='Santai, nyaman' <?php if ($asanak148=="Santai, nyaman"){echo "checked";}?>>Santai, nyaman</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak148' value='Dipastikan dengan sentuhan' <?php if ($asanak148=="Dipastikan dengan sentuhan"){echo "checked";}?>>Dipastikan dengan sentuhan</td>
								<td style="border: 1px solid;"><input type='radio' disabled  name='asanak148' value='Sulit konsul / tidak nyaman' <?php if ($asanak148=="Sulit konsul / tidak nyaman"){echo "checked";}?>>Sulit konsul / tidak nyaman</td>
								<td style="border: 1px solid;">
									<?php 
									if($asanak148=='Santai, nyaman')
										{$ku_tnyeri5_skor=0;}
									if($asanak148=='Dipastikan dengan sentuhan')
										{$ku_tnyeri5_skor=1;}
									if($asanak148=='Sulit konsul / tidak nyaman')
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
						<br>
					</td>
					<td>
<!-- 						<input type='checkbox' disabled name='asanak171' value='Nyeri akut' <?php if ($asanak171=="Nyeri akut"){echo "checked";}?>> Nyeri akut<br>
						<input type='checkbox' disabled name='asanak172' value='Nyeri kronis' <?php if ($asanak172=="Nyeri kronis"){echo "checked";}?>> Nyeri kronis<br>
					-->
					<br>
					<input type='checkbox' disabled name='m4' value='Y' <?php if ($m4=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
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

						echo "<input type='checkbox' disabled name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
						$no+=1;
					}
					?>
					
					<br>

				</td>
			</tr>


			<tr>
				<td>
					F. KEAMANAN DAN KENYAMANAN<br>

					1. Risiko Jatuh<br>
					(dewasa dengan skala humpty dumpty)<br>

					<table>
						<tr>
							<td style="border: 1px solid;">Faktor Risiko</td>
							<td style="border: 1px solid;">skala</td>
							<td style="border: 1px solid;">poin</td>
							<td style="border: 1px solid;">Skor pasien</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Umur</td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak149' value='YA' <?php if ($asanak149=="YA"){echo "checked";}?>>Kurang dari 3 tahun</td>
							<td style="border: 1px solid;">4</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak149){
									echo $tjatuh1_skor='4';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak150' value='YA' <?php if ($asanak150=="YA"){echo "checked";}?>>3 tahun – 7 tahun</td>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak150){
									echo $tjatuh2_skor='3';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak151' value='YA' <?php if ($asanak151=="YA"){echo "checked";}?>>7 tahun – 13 tahun</td>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak151){
									echo $tjatuh3_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak152' value='YA' <?php if ($asanak152=="YA"){echo "checked";}?>>Lebih dari 13</td>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak152){
									echo $tjatuh4_skor='1';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Jenis kelamin</td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak153' value='YA' <?php if ($asanak153=="YA"){echo "checked";}?>>Laki – laki</td>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak153){
									echo $tjatuh5_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak154' value='YA' <?php if ($asanak154=="YA"){echo "checked";}?>>Wanita</td>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak154){
									echo $tjatuh6_skor='1';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Diagnosa</td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak155' value='YA' <?php if ($asanak155=="YA"){echo "checked";}?>>neorologi</td>
							<td style="border: 1px solid;">4</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak155){
									echo $tjatuh7_skor='4';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak156' value='YA' <?php if ($asanak156=="YA"){echo "checked";}?>>Respiratori, dehidrasi, anemia, anorexia, syncope</td>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak156){
									echo $tjatuh8_skor='3';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak157' value='YA' <?php if ($asanak157=="YA"){echo "checked";}?>>Lain-lain</td>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak157){
									echo $tjatuh9_skor='1';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Gangguan kognitif</td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak158' value='YA' <?php if ($asanak158=="YA"){echo "checked";}?>>Keterbatasan daya pikir</td>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak158){
									echo $tjatuh10_skor='3';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak159' value='YA' <?php if ($tjatuh7=="YA"){echo "checked";}?>>Pelupa</td>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak159){
									echo $tjatuh11_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak160' value='YA' <?php if ($asanak160=="YA"){echo "checked";}?>>Dapat menggunakan daya pikir tanpa hambatan</td>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak160){
									echo $tjatuh12_skor='1';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Faktor lingkungan</td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak161' value='YA' <?php if ($asanak161=="YA"){echo "checked";}?>>Riwayat jatuh atau bayi / balita yang ditempatkan di tempat tidur</td>
							<td style="border: 1px solid;">4</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak161){
									echo $tjatuh13_skor='4';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak162' value='YA' <?php if ($asanak162=="YA"){echo "checked";}?>>Pasien menggunakan alat bantu/bayi balita dalam ayunan</td>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak162){
									echo $tjatuh14_skor='3';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak163' value='YA' <?php if ($asanak163=="YA"){echo "checked";}?>>Pasien ditempat tidur standart</td>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak163){
									echo $tjatuh15_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak164' value='YA' <?php if ($asanak164=="YA"){echo "checked";}?>>Area pasien rawat jalan</td>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak164){
									echo $tjatuh16_skor='1';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Respon terhadap pembedahan, sedasi, dan anestesi</td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak165' value='YA' <?php if ($asanak165=="YA"){echo "checked";}?>>Dalam 24 jam</td>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak165){
									echo $tjatuh17_skor='3';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak166' value='YA' <?php if ($asanak166=="YA"){echo "checked";}?>>Dalam 48 jam</td>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak166){
									echo $tjatuh18_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak167' value='YA' <?php if ($asanak167=="YA"){echo "checked";}?>>Lebih dari 48 jam/ tidak ada respon</td>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak167){
									echo $tjatuh19_skor='1';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Penggunaan obat-obatan</td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak168' value='YA' <?php if ($asanak168=="YA"){echo "checked";}?>>Penggunaan bersamaan sedative, barbiturate, anti depresan, diuretik, narkotik</td>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak168){
									echo $tjatuh20_skor='3';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak169' value='YA' <?php if ($asanak169=="YA"){echo "checked";}?>>Salah satu dari obat diatas</td>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak169){
									echo $tjatuh21_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak170' value='YA' <?php if ($asanak170=="YA"){echo "checked";}?>>Obat-obatan lainnya/tanpa obat</td>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">
								<?php 
								if($asanak170){
									echo $tjatuh22_skor='1';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;" colspan="3">Total Skor</td>
							<td style="border: 1px solid;">
								<?php 
								echo $tjatuh_skor_total=$tjatuh1_skor+$tjatuh2_skor+$tjatuh3_skor+$tjatuh4_skor+$tjatuh5_skor+$tjatuh6_skor+$tjatuh7_skor+$tjatuh8_skor+$tjatuh9_skor+$tjatuh10_skor+$tjatuh11_skor+$tjatuh12_skor+$tjatuh13_skor+$tjatuh14_skor+$tjatuh15_skor+$tjatuh16_skor+$tjatuh17_skor+$tjatuh18_skor+$tjatuh19_skor+$tjatuh20_skor+$tjatuh21_skor+$tjatuh22_skor; 
								?>
							</td>
						</tr>

					</table>
					&nbsp;

					<br>
					[] >12 : Risiko tinggi ;         [] 7-11 : Risiko sedang ;         [] 0 -6 : Risiko rendah
					<br>
					<?php 
					echo "<h5>";
					echo "[".$tjatuh_skor_total."]";

					if($tjatuh_skor_total >= 0 and $tjatuh_skor_total <= 6){echo $tjatuh_skor_total="Risiko rendah";}
					if($tjatuh_skor_total >= 7 and $tjatuh_skor_total <= 11 ){echo $tjatuh_skor_total="Risiko sedang";}
					if($tjatuh_skor_total >= 12){echo $tjatuh_skor_total="Risiko tinggi";}
					echo "</h5>";

					?>
				</td>
				<td>
<!-- 						<input type='checkbox' disabled name='asanak173' value='Konstipasi' <?php if ($asanak173=="Konstipasi"){echo "checked";}?>> Risiko Cedera<br>
	<input type='checkbox' disabled name='asanak174' value='Risiko Trauma/jatuh' <?php if ($asanak174=="Risiko Trauma/jatuh"){echo "checked";}?>> Risiko Trauma/jatuh<br> -->

	<br>
	<input type='checkbox' disabled name='m10' value='Y' <?php if ($m10=='Y'){echo 'checked';}?>> Tidak Ada Masalah<br>
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

		echo "<input type='checkbox' disabled name='$nm_field' value='$data[kode_diagnosa]' $checked> $data[diagnosa_nama]";echo "<br>";			  
		$no+=1;
	}
	?>
	
	
</td>
</tr>


<tr>
	<td>
		2. Restrain :<br>
		Pernah menggunakan restrain sebelumnya : 
		<input type='checkbox' disabled name='asanak176' value='Tidak' <?php if ($asanak176=="Tidak"){echo "checked";}?>>Tidak    
		<input type='checkbox' disabled name='asanak176' value='Ya' <?php if ($asanak176=="Ya"){echo "checked";}?>>Ya<br>
		Kondisi Pasien  :   
		<input type='checkbox' disabled name='asanak177' value='Gelisah' <?php if ($asanak177=="Gelisah"){echo "checked";}?>>Gelisah     
		<input type='checkbox' disabled name='asanak177' value='Delirium' <?php if ($asanak177=="Delirium"){echo "checked";}?>>Delirium    
		<input type='checkbox' disabled name='asanak177' value='Berontak' <?php if ($asanak177=="Berontak"){echo "checked";}?>>Berontak<br>
		pasien kooperatif :   
		<input type='checkbox' disabled name='asanak178' value='Ya' <?php if ($asanak178=="Ya"){echo "checked";}?>>Ya       
		<input type='checkbox' disabled name='asanak178' value='Tidak' <?php if ($asanak178=="Tidak"){echo "checked";}?>>Tidak<br>
		Kondisi saat ini berisiko :   
		<input type='checkbox' disabled name='asanak179' value='Tidak' <?php if ($asanak179=="Tidak"){echo "checked";}?>>Tidak     
		<input type='checkbox' disabled name='asanak179' value='Ya' <?php if ($asanak179=="Ya"){echo "checked";}?>>Ya, lanjutan dengan pengkajian restrain.<br>
		
	</td>
	<td>
		&nbsp;
	</td>
</tr>


<tr>
	<td>
		G. KEBUTUHAN KOMUNIKASI, KOGNISI DAN EDUKASI<br>
		Bicara :   
		<input type='checkbox' disabled name='asanak180' value='Normal' <?php if ($asanak180=="Normal"){echo "checked";}?>>Normal      
		<input type='checkbox' disabled name='asanak180' value='Gangguan Bicara' <?php if ($asanak180=="Gangguan Bicara"){echo "checked";}?>>Gangguan Bicara :<br>
		Mulai kapan : <input  type='text' disabled name='asdewasa9' value='<?php echo $asdewasa9;?>'><br>
		Memerlukan Bahasa Isyarat :    
		<input type='checkbox' disabled name='asanak181' value='Tidak' <?php if ($asanak181=="Tidak"){echo "checked";}?>>Tidak     
		<input type='checkbox' disabled name='asanak181' value='Ya' <?php if ($asanak181=="Ya"){echo "checked";}?>>Ya<br>
		Hambatan Belajar : 
		<input type='checkbox' disabled name='asanak182' value='Bahasa' <?php if ($asanak182=="Bahasa"){echo "checked";}?>>Bahasa     
		<input type='checkbox' disabled name='asanak182' value='Pendengaran' <?php if ($asanak182=="Pendengaran"){echo "checked";}?>>Pendengaran    
		<input type='checkbox' disabled name='asanak182' value='Hilang Memori' <?php if ($asanak182=="Hilang Memori"){echo "checked";}?>>Hilang Memori<br>
		
	</td>
	<td>
		<!-- <input type='checkbox' disabled name='asanak196' value='asanak198' <?php if ($asanak198=="Hambatan Komunikasi Verbal"){echo "checked";}?>>Hambatan Komunikasi Verbal<br> -->
	</td>
</tr>


<tr>
	<td>
		H. PSIKOSOSIAL, SPIRITUAL DAN EKONOMI<br>
		Keadaan Psikologis   :    
		<input type='checkbox' disabled name='asanak183' value='Kooperatif' <?php if ($asanak183=="Kooperatif"){echo "checked";}?>>Kooperatif      
		<input type='checkbox' disabled name='asanak183' value='Sedih' <?php if ($asanak183=="Sedih"){echo "checked";}?>>Sedih   
		<input type='checkbox' disabled name='asanak183' value='Marah' <?php if ($asanak183=="Marah"){echo "checked";}?>>Marah
		<input type='checkbox' disabled name='asanak183' value='Agitasi' <?php if ($asanak183=="Agitasi"){echo "checked";}?>>Agitasi        
		<input type='checkbox' disabled name='asanak183' value='Cemas' <?php if ($asanak183=="Cemas"){echo "checked";}?>>Cemas   
		<input type='checkbox' disabled name='asanak183' value='Gelisah' <?php if ($asanak183=="Gelisah"){echo "checked";}?>>Gelisah
		<input type='checkbox' disabled name='asanak183' value='Disorientasi' <?php if ($asanak183=="Disorientasi"){echo "checked";}?>>Disorientasi:    
		<input type='checkbox' disabled name='asanak183' value='Orang' <?php if ($asanak183=="Orang"){echo "checked";}?>>Orang   
		<input type='checkbox' disabled name='asanak183' value='Tempat' <?php if ($asanak183=="Tempat"){echo "checked";}?>>Tempat   
		<input type='checkbox' disabled name='asanak183' value='Waktu' <?php if ($asanak183=="Waktu"){echo "checked";}?>>Waktu<br>
		Tingkat Pendidikan :    
		<input type='checkbox' disabled name='asanak184' value='Belum Sekolah' <?php if ($asanak184=="Belum Sekolah"){echo "checked";}?>>Belum Sekolah   
		<input type='checkbox' disabled name='asanak184' value='SD' <?php if ($asanak184=="SD"){echo "checked";}?>>SD     
		<input type='checkbox' disabled name='asanak184' value='SMP' <?php if ($asanak184=="SMP"){echo "checked";}?>>SMP
		<input type='checkbox' disabled name='asanak184' value='SMA' <?php if ($asanak184=="SMA"){echo "checked";}?>>SMA           
		<input type='checkbox' disabled name='asanak184' value='Diploma' <?php if ($asanak184=="Diploma"){echo "checked";}?>>Diploma  
		<input type='checkbox' disabled name='asanak184' value='Sarjana' <?php if ($asanak184=="Sarjana"){echo "checked";}?>>Sarjana<br>
		Pekerjaan         :    
		<input type='checkbox' disabled name='asanak185' value='Wiraswasta' <?php if ($asanak185=="Wiraswasta"){echo "checked";}?>>Wiraswasta      
		<input type='checkbox' disabled name='asanak185' value='Swasta' <?php if ($asanak185=="Swasta"){echo "checked";}?>>Swasta   
		<input type='checkbox' disabled name='asanak185' value='Peg.Negeri' <?php if ($asanak185=="Peg.Negeri"){echo "checked";}?>>Peg.Negeri<br>
		Tinggal Bersama   :    
		<input type='checkbox' disabled name='asanak186' value='Suami/istri ' <?php if ($asanak186=="Suami/istri "){echo "checked";}?>>Suami/istri      
		<input type='checkbox' disabled name='asanak186' value='Anak' <?php if ($asanak186=="Anak"){echo "checked";}?>>Anak   
		<input type='checkbox' disabled name='asanak186' value='Teman' <?php if ($asanak186=="Teman"){echo "checked";}?>>Teman
		<input type='checkbox' disabled name='asanak186' value='Orang Tua ' <?php if ($asanak186=="Orang Tua "){echo "checked";}?>>Orang Tua       
		<input type='checkbox' disabled name='asanak186' value='Sendiri' <?php if ($asdewasa15=="Sendiri"){echo "checked";}?>>Sendiri  
		<input type='checkbox' disabled name='asanak186' value='Caregiver' <?php if ($asanak186=="Caregiver"){echo "checked";}?>>Caregiver<br>
		Status Ekonomi    :    
		<input type='checkbox' disabled name='asanak187' value='Pembayaran Pribadi/Perorangan' <?php if ($asanak187=="Pembayaran Pribadi/Perorangan"){echo "checked";}?>>Pembayaran Pribadi/Perorangan
		<input type='checkbox' disabled name='asanak187' value='Jaminan kesehatan/Asuransi' <?php if ($asanak187=="Jaminan kesehatan/Asuransi"){echo "checked";}?>>Jaminan kesehatan/Asuransi
	</td>
	<td>
		<!-- <input type='checkbox' disabled name='asanak192' value='Defisit perawatan diri' <?php if ($asanak192=="Defisit perawatan diri"){echo "checked";}?>>Defisit perawatan diri<br> -->
	</td>
</tr>


<tr>
	<td>
		I. Spiritual<br>
		Menjalankan ibadah    :   
		<input type='checkbox' disabled name='asanak188' value='Ada Hambatan' <?php if ($asanak188=="Ada Hambatan"){echo "checked";}?>>Ada Hambatan    :   
		<input type='checkbox' disabled name='asanak188' value='Tidak ada hambatan' <?php if ($asanak188=="Tidak ada hambatan"){echo "checked";}?>>Tidak ada hambatan<br>
		Persepsi terhadap Sakit :   
		<input type='checkbox' disabled name='asanak189' value='Tidak Ada Keluhan' <?php if ($asanak189=="Tidak Ada Keluhan"){echo "checked";}?>>Tidak Ada Keluhan     
		<input type='checkbox' disabled name='asanak189' value='Rasa Bersalah' <?php if ($asanak189=="Rasa Bersalah"){echo "checked";}?>>Rasa Bersalah<br>
		Meminta Pelayanan Spiritual :    
		<input type='checkbox' disabled name='asanak190' value='Tidak Ada Keluhan' <?php if ($asanak190=="Tidak Ada Keluhan"){echo "checked";}?>>Tidak Ada Keluhan      
		<input type='checkbox' disabled name='asanak190' value='Ya' <?php if ($asanak190=="Ya"){echo "checked";}?>>Ya, (lakukan kolaborasi dengan bagian kerohanian)<br>
		
	</td>
	<td>
		&nbsp;
	</td>
</tr>

<tr>
	<td>
		J. DISCHARGE PLANNING<br>
		<table>
			<tr>
				<td style="border: 1px solid;">No</td>
				<td style="border: 1px solid;">Keterangan</td>
				<td style="border: 1px solid;">Ya</td>
				<td style="border: 1px solid;">Tidak</td>
			</tr>

			<tr>
				<td style="border: 1px solid;">1</td>
				<td style="border: 1px solid;">Usia ≥60 tahun</td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak199' value='Ya' <?php if ($asanak199=="Ya"){echo "checked";}?>></td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak199' value='Tidak' <?php if ($asanak199=="Tidak"){echo "checked";}?>></td>
			</tr>
			<tr>
				<td style="border: 1px solid;">2</td>
				<td style="border: 1px solid;">Tinggal sendirian tanpa dukungan sosial secara langsung</td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak200' value='Ya' <?php if ($asanak200=="Ya"){echo "checked";}?>></td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak200' value='Tidak' <?php if ($asanak200=="Tidak"){echo "checked";}?>></td>
			</tr>
			<tr>
				<td style="border: 1px solid;">3</td>
				<td style="border: 1px solid;">Stroke, serangan Jantung, PPOK, gagal jantung kongestif, empisema demensia, TB, alzeimer, AIDS, atau penyakit dengan potensi mengancam nyawa lainnya</td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak201' value='Ya' <?php if ($asanak201=="Ya"){echo "checked";}?>></td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak201' value='Tidak' <?php if ($asanak201=="Tidak"){echo "checked";}?>></td>
			</tr>
			<tr>
				<td style="border: 1px solid;">4</td>
				<td style="border: 1px solid;">Korban dari kasus criminal</td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak202' value='Ya' <?php if ($asanak202=="Ya"){echo "checked";}?>></td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak202' value='Tidak' <?php if ($asanak202=="Tidak"){echo "checked";}?>></td>
			</tr>
			<tr>
				<td style="border: 1px solid;">5</td>
				<td style="border: 1px solid;">Trauma Multiple</td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak203' value='Ya' <?php if ($asanak203=="Ya"){echo "checked";}?>></td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak203' value='Tidak' <?php if ($asanak203=="Tidak"){echo "checked";}?>></td>
			</tr>
			<tr>
				<td style="border: 1px solid;">6</td>
				<td style="border: 1px solid;">Memerlukan Perawatan atau pengobatan berkelanjutan</td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak204' value='Ya' <?php if ($asanak204=="Ya"){echo "checked";}?>></td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak204' value='Tidak' <?php if ($asanak204=="Tidak"){echo "checked";}?>></td>
			</tr>
			<tr>
				<td style="border: 1px solid;">7</td>
				<td style="border: 1px solid;">Pasien lansia yang ada dilantai atas (saat dirumah)</td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak205' value='Ya' <?php if ($asanak205=="Ya"){echo "checked";}?>></td>
				<td style="border: 1px solid;"><input type='checkbox' disabled name='asanak205' value='Tidak' <?php if ($asanak205=="Tidak"){echo "checked";}?>></td>
			</tr>


			<tr>
				<td style="border: 1px solid;" colspan="4">Bila salah satu jawaban adalah ya, dilanjutkan pengisian rm 18a.10 discharge planning terintegrasi</td>
			</tr>

		</table>
		
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
					<?php $verif_perawat="Document ini telah diVerifikasi Oleh : ".$userid."Pada Tanggal : ".$tgl_assesment;?>
					<!-- <center><img alt='Verifikasi' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_perawat&choe=UTF-8'/></center> -->

					<?php 
					QRcode::png($verif_perawat, "image.png", "L", 2, 2);   
					echo "<center><img src='image.png'></center>";
					?>

					<br>
					<?php echo $userid;?>
				</td>
				<td align="center">
					<?php echo $dpjp;?>										
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



	$q  = "update ERM_RI_ASSESMEN_AWAL_ANAK set
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
	window.location.replace('form_assesmen_anak.php?id=$id|$user');
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
		$lanjut='T';
		$eror = 'DPJP tidak valid !!!';
	}


	if($lanjut=='Y'){
		$keluhan_pasien	= $_POST["keluhan_pasien"];
		$riwayat_penyakit_sekarang= $_POST["riwayat_penyakit_sekarang"];
		$dpjp	= $_POST["dpjp"];
		$tglrawat	= $_POST["tglrawat"];
		$asanak1= $_POST['asanak1'];
		$asanak2= $_POST['asanak2'];
		$asanak3= $_POST['asanak3'];
		$asanak4= $_POST['asanak4'];
		$asanak5= $_POST['asanak5'];
		$asanak6= $_POST['asanak6'];
		$asanak7= $_POST['asanak7'];
		$asanak8= $_POST['asanak8'];
		$asanak9= $_POST['asanak9'];
		$asanak10= $_POST['asanak10'];
		$asanak11= $_POST['asanak11'];
		$asanak12= $_POST['asanak12'];
		$asanak13= $_POST['asanak13'];
		$asanak14= $_POST['asanak14'];
		$asanak15= $_POST['asanak15'];
		$asanak16= $_POST['asanak16'];
		$asanak17= $_POST['asanak17'];
		$asanak18= $_POST['asanak18'];
		$asanak19= $_POST['asanak19'];
		$asanak20= $_POST['asanak20'];
		$asanak21= $_POST['asanak21'];
		$asanak22= $_POST['asanak22'];
		$asanak23= $_POST['asanak23'];
		$asanak24= $_POST['asanak24'];
		$asanak25= $_POST['asanak25'];
		$asanak26= $_POST['asanak26'];
		$asanak27= $_POST['asanak27'];
		$asanak28= $_POST['asanak28'];
		$asanak29= $_POST['asanak29'];
		$asanak30= $_POST['asanak30'];
		$asanak31= $_POST['asanak31'];
		$asanak32= $_POST['asanak32'];
		$asanak33= $_POST['asanak33'];
		$asanak34= $_POST['asanak34'];
		$asanak35= $_POST['asanak35'];
		$asanak36= $_POST['asanak36'];
		$asanak37= $_POST['asanak37'];
		$asanak38= $_POST['asanak38'];
		$asanak39= $_POST['asanak39'];
		$asanak40= $_POST['asanak40'];
		$asanak41= $_POST['asanak41'];
		$asanak42= $_POST['asanak42'];
		$asanak43= $_POST['asanak43'];
		$asanak44= $_POST['asanak44'];
		$asanak45= $_POST['asanak45'];
		$asanak46= $_POST['asanak46'];
		$asanak47= $_POST['asanak47'];
		$asanak48= $_POST['asanak48'];
		$asanak49= $_POST['asanak49'];
		$asanak50= $_POST['asanak50'];
		$asanak51= $_POST['asanak51'];
		$asanak52= $_POST['asanak52'];
		$asanak53= $_POST['asanak53'];
		$asanak54= $_POST['asanak54'];
		$asanak55= $_POST['asanak55'];
		$asanak56= $_POST['asanak56'];
		$asanak57= $_POST['asanak57'];
		$asanak58= $_POST['asanak58'];
		$asanak59= $_POST['asanak59'];
		$asanak60= $_POST['asanak60'];
		$asanak61= $_POST['asanak61'];
		$asanak62= $_POST['asanak62'];
		$asanak63= $_POST['asanak63'];
		$asanak64= $_POST['asanak64'];
		$asanak65= $_POST['asanak65'];
		$asanak66= $_POST['asanak66'];
		$asanak67= $_POST['asanak67'];
		$asanak68= $_POST['asanak68'];
		$asanak69= $_POST['asanak69'];
		$asanak70= $_POST['asanak70'];
		$asanak71= $_POST['asanak71'];
		$asanak72= $_POST['asanak72'];
		$asanak73= $_POST['asanak73'];
		$asanak74= $_POST['asanak74'];
		$asanak75= $_POST['asanak75'];
		$asanak76= $_POST['asanak76'];
		$asanak77= $_POST['asanak77'];
		$asanak78= $_POST['asanak78'];
		$asanak79= $_POST['asanak79'];
		$asanak80= $_POST['asanak80'];
		$asanak81= $_POST['asanak81'];
		$asanak82= $_POST['asanak82'];
		$asanak83= $_POST['asanak83'];
		$asanak84= $_POST['asanak84'];
		$asanak85= $_POST['asanak85'];
		$asanak86= $_POST['asanak86'];
		$asanak87= $_POST['asanak87'];
		$asanak88= $_POST['asanak88'];
		$asanak89= $_POST['asanak89'];
		$asanak90= $_POST['asanak90'];
		$asanak91= $_POST['asanak91'];
		$asanak92= $_POST['asanak92'];
		$asanak93= $_POST['asanak93'];
		$asanak94= $_POST['asanak94'];
		$asanak95= $_POST['asanak95'];
		$asanak96= $_POST['asanak96'];
		$asanak97= $_POST['asanak97'];
		$asanak98= $_POST['asanak98'];
		$asanak99= $_POST['asanak99'];
		$asanak100= $_POST['asanak100'];
		$asanak101= $_POST['asanak101'];
		$asanak102= $_POST['asanak102'];
		$asanak103= $_POST['asanak103'];
		$asanak104= $_POST['asanak104'];
		$asanak105= $_POST['asanak105'];
		$asanak106= $_POST['asanak106'];
		$asanak107= $_POST['asanak107'];
		$asanak108= $_POST['asanak108'];
		$asanak109= $_POST['asanak109'];
		$asanak110= $_POST['asanak110'];
		$asanak111= $_POST['asanak111'];
		$asanak112= $_POST['asanak112'];
		$asanak113= $_POST['asanak113'];
		$asanak114= $_POST['asanak114'];
		$asanak115= $_POST['asanak115'];
		$asanak116= $_POST['asanak116'];
		$asanak117= $_POST['asanak117'];
		$asanak118= $_POST['asanak118'];
		$asanak119= $_POST['asanak119'];
		$asanak120= $_POST['asanak120'];
		$asanak121= $_POST['asanak121'];
		$asanak122= $_POST['asanak122'];
		$asanak123= $_POST['asanak123'];
		$asanak124= $_POST['asanak124'];
		$asanak125= $_POST['asanak125'];
		$asanak126= $_POST['asanak126'];
		$asanak127= $_POST['asanak127'];
		$asanak128= $_POST['asanak128'];
		$asanak129= $_POST['asanak129'];
		$asanak130= $_POST['asanak130'];
		$asanak131= $_POST['asanak131'];
		$asanak132= $_POST['asanak132'];
		$asanak133= $_POST['asanak133'];
		$asanak134= $_POST['asanak134'];
		$asanak135= $_POST['asanak135'];
		$asanak136= $_POST['asanak136'];
		$asanak137= $_POST['asanak137'];
		$asanak138= $_POST['asanak138'];
		$asanak139= $_POST['asanak139'];
		$asanak140= $_POST['asanak140'];
		$asanak141= $_POST['asanak141'];
		$asanak142= $_POST['asanak142'];
		$asanak143= $_POST['asanak143'];
		$asanak144= $_POST['asanak144'];
		$asanak145= $_POST['asanak145'];
		$asanak146= $_POST['asanak146'];
		$asanak147= $_POST['asanak147'];
		$asanak148= $_POST['asanak148'];
		$asanak149= $_POST['asanak149'];
		$asanak150= $_POST['asanak150'];
		$asanak151= $_POST['asanak151'];
		$asanak152= $_POST['asanak152'];
		$asanak153= $_POST['asanak153'];
		$asanak154= $_POST['asanak154'];
		$asanak155= $_POST['asanak155'];
		$asanak156= $_POST['asanak156'];
		$asanak157= $_POST['asanak157'];
		$asanak158= $_POST['asanak158'];
		$asanak159= $_POST['asanak159'];
		$asanak160= $_POST['asanak160'];
		$asanak161= $_POST['asanak161'];
		$asanak162= $_POST['asanak162'];
		$asanak163= $_POST['asanak163'];
		$asanak164= $_POST['asanak164'];
		$asanak165= $_POST['asanak165'];
		$asanak166= $_POST['asanak166'];
		$asanak167= $_POST['asanak167'];
		$asanak168= $_POST['asanak168'];
		$asanak169= $_POST['asanak169'];
		$asanak170= $_POST['asanak170'];
		$asanak171= $_POST['asanak171'];
		$asanak172= $_POST['asanak172'];
		$asanak173= $_POST['asanak173'];
		$asanak174= $_POST['asanak174'];
		$asanak175= $_POST['asanak175'];
		$asanak176= $_POST['asanak176'];
		$asanak177= $_POST['asanak177'];
		$asanak178= $_POST['asanak178'];
		$asanak179= $_POST['asanak179'];
		$asanak180= $_POST['asanak180'];
		$asanak181= $_POST['asanak181'];
		$asanak182= $_POST['asanak182'];
		$asanak183= $_POST['asanak183'];
		$asanak184= $_POST['asanak184'];
		$asanak185= $_POST['asanak185'];
		$asanak186= $_POST['asanak186'];
		$asanak187= $_POST['asanak187'];
		$asanak188= $_POST['asanak188'];
		$asanak189= $_POST['asanak189'];
		$asanak190= $_POST['asanak190'];
		$asanak191= $_POST['asanak191'];
		$asanak192= $_POST['asanak192'];
		$asanak193= $_POST['asanak193'];
		$asanak194= $_POST['asanak194'];
		$asanak195= $_POST['asanak195'];
		$asanak196= $_POST['asanak196'];
		$asanak197= $_POST['asanak197'];
		$asanak198= $_POST['asanak198'];
		$asanak199= $_POST['asanak199'];
		$asanak200= $_POST['asanak200'];
		$asanak201= $_POST['asanak201'];
		$asanak202= $_POST['asanak202'];
		$asanak203= $_POST['asanak203'];
		$asanak204= $_POST['asanak204'];
		$asanak205= $_POST['asanak205'];
		$asanak206= $_POST['asanak206'];
		$asanak207= $_POST['asanak207'];
		$asanak208= $_POST['asanak208'];
		$asanak209= $_POST['asanak209'];
		$asanak210= $_POST['asanak210'];
		$ku_beratbadan= $_POST['ku_beratbadan'];
		$ku_tinggibadan= $_POST['ku_tinggibadan'];
		$type_persalinan1= $_POST['type_persalinan1'];
		$type_persalinan2= $_POST['type_persalinan2'];
		$type_persalinan3= $_POST['type_persalinan3'];
		$type_persalinan4= $_POST['type_persalinan4'];
		$type_persalinan5= $_POST['type_persalinan5'];
		$r_tumbuhkembang1= $_POST['r_tumbuhkembang1'];
		$r_tumbuhkembang2= $_POST['r_tumbuhkembang2'];
		$r_tumbuhkembang3= $_POST['r_tumbuhkembang3'];
		$r_tumbuhkembang4= $_POST['r_tumbuhkembang4'];
		$r_tumbuhkembang5= $_POST['r_tumbuhkembang5'];
		$r_tumbuhkembang6= $_POST['r_tumbuhkembang6'];
		$r_tumbuhkembang7= $_POST['r_tumbuhkembang7'];
		$r_tumbuhkembang8= $_POST['r_tumbuhkembang8'];
		$r_tumbuhkembang9= $_POST['r_tumbuhkembang9'];
		$r_tumbuhkembang10= $_POST['r_tumbuhkembang10'];
		$r_tumbuhkembang11= $_POST['r_tumbuhkembang11'];
		$r_tumbuhkembang12= $_POST['r_tumbuhkembang12'];

		$r_hamil1= $_POST['r_hamil1'];
		$r_hamil2= $_POST['r_hamil2'];
		$r_hamil3= $_POST['r_hamil3'];
		$r_hamil4= $_POST['r_hamil4'];
		$r_hamil5= $_POST['r_hamil5'];
		$r_hamil6= $_POST['r_hamil6'];
		$r_hamil7= $_POST['r_hamil7'];
		$r_hamil8= $_POST['r_hamil8'];
		$r_hamil9= $_POST['r_hamil9'];

		$nutrisi1= $_POST['nutrisi1'];
		$nutrisi2= $_POST['nutrisi2'];
		$nutrisi3= $_POST['nutrisi3'];
		$nutrisi4= $_POST['nutrisi4'];
		$nutrisi5= $_POST['nutrisi5'];
		$nutrisi6= $_POST['nutrisi6'];

		$ku_gcs_e= $_POST['ku_gcs_e'];
		$ku_gcs_v= $_POST['ku_gcs_v'];
		$ku_gcs_m= $_POST['ku_gcs_m'];
		$total_gcs = $ku_gcs_e+$ku_gcs_v+$ku_gcs_m;
		$ku_suhu= $_POST['ku_suhu'];
		$ku_tensi= $_POST['ku_tensi'];
		$ku_nadi= $_POST['ku_nadi'];
		$ku_nadi_ket= $_POST['ku_nadi_ket'];
		$ku_nafas= $_POST['ku_nafas'];
		$ku_spo= $_POST['ku_spo'];
		$ku_kesadaran= $_POST['ku_kesadaran'];

		echo	$q  = "update ERM_RI_ASSESMEN_AWAL_ANAK set
		keluhan_pasien='$keluhan_pasien',
		riwayat_penyakit_sekarang='$riwayat_penyakit_sekarang',
		tglrawat='$tglrawat',
		asanak1= '$asanak1',
		asanak2= '$asanak2',
		asanak3= '$asanak3',
		asanak4= '$asanak4',
		asanak5= '$asanak5',
		asanak6= '$asanak6',
		asanak7= '$asanak7',
		asanak8= '$asanak8',
		asanak9= '$asanak9',
		asanak10= '$asanak10',
		asanak11= '$asanak11',
		asanak12= '$asanak12',
		asanak13= '$asanak13',
		asanak14= '$asanak14',
		asanak15= '$asanak15',
		asanak16= '$asanak16',
		asanak17= '$asanak17',
		asanak18= '$asanak18',
		asanak19= '$asanak19',
		asanak20= '$asanak20',
		asanak21= '$asanak21',
		asanak22= '$asanak22',
		asanak23= '$asanak23',
		asanak24= '$asanak24',
		asanak25= '$asanak25',
		asanak26= '$asanak26',
		asanak27= '$asanak27',
		asanak28= '$asanak28',
		asanak29= '$asanak29',
		asanak30= '$asanak30',
		asanak31= '$asanak31',
		asanak32= '$asanak32',
		asanak33= '$asanak33',
		asanak34= '$asanak34',
		asanak35= '$asanak35',
		asanak36= '$asanak36',
		asanak37= '$asanak37',
		asanak38= '$asanak38',
		asanak39= '$asanak39',
		asanak40= '$asanak40',
		asanak41= '$asanak41',
		asanak42= '$asanak42',
		asanak43= '$asanak43',
		asanak44= '$asanak44',
		asanak45= '$asanak45',
		asanak46= '$asanak46',
		asanak47= '$asanak47',
		asanak48= '$asanak48',
		asanak49= '$asanak49',
		asanak50= '$asanak50',
		asanak51= '$asanak51',
		asanak52= '$asanak52',
		asanak53= '$asanak53',
		asanak54= '$asanak54',
		asanak55= '$asanak55',
		asanak56= '$asanak56',
		asanak57= '$asanak57',
		asanak58= '$asanak58',
		asanak59= '$asanak59',
		asanak60= '$asanak60',
		asanak61= '$asanak61',
		asanak62= '$asanak62',
		asanak63= '$asanak63',
		asanak64= '$asanak64',
		asanak65= '$asanak65',
		asanak66= '$asanak66',
		asanak67= '$asanak67',
		asanak68= '$asanak68',
		asanak69= '$asanak69',
		asanak70= '$asanak70',
		asanak71= '$asanak71',
		asanak72= '$asanak72',
		asanak73= '$asanak73',
		asanak74= '$asanak74',
		asanak75= '$asanak75',
		asanak76= '$asanak76',
		asanak77= '$asanak77',
		asanak78= '$asanak78',
		asanak79= '$asanak79',
		asanak80= '$asanak80',
		asanak81= '$asanak81',
		asanak82= '$asanak82',
		asanak83= '$asanak83',
		asanak84= '$asanak84',
		asanak85= '$asanak85',
		asanak86= '$asanak86',
		asanak87= '$asanak87',
		asanak88= '$asanak88',
		asanak89= '$asanak89',
		asanak90= '$asanak90',
		asanak91= '$asanak91',
		asanak92= '$asanak92',
		asanak93= '$asanak93',
		asanak94= '$asanak94',
		asanak95= '$asanak95',
		asanak96= '$asanak96',
		asanak97= '$asanak97',
		asanak98= '$asanak98',
		asanak99= '$asanak99',
		asanak100= '$asanak100',
		asanak101= '$asanak101',
		asanak102= '$asanak102',
		asanak103= '$asanak103',
		asanak104= '$asanak104',
		asanak105= '$asanak105',
		asanak106= '$asanak106',
		asanak107= '$asanak107',
		asanak108= '$asanak108',
		asanak109= '$asanak109',
		asanak110= '$asanak110',
		asanak111= '$asanak111',
		asanak112= '$asanak112',
		asanak113= '$asanak113',
		asanak114= '$asanak114',
		asanak115= '$asanak115',
		asanak116= '$asanak116',
		asanak117= '$asanak117',
		asanak118= '$asanak118',
		asanak119= '$asanak119',
		asanak120= '$asanak120',
		asanak121= '$asanak121',
		asanak122= '$asanak122',
		asanak123= '$asanak123',
		asanak124= '$asanak124',
		asanak125= '$asanak125',
		asanak126= '$asanak126',
		asanak127= '$asanak127',
		asanak128= '$asanak128',
		asanak129= '$asanak129',
		asanak130= '$asanak130',
		asanak131= '$asanak131',
		asanak132= '$asanak132',
		asanak133= '$asanak133',
		asanak134= '$asanak134',
		asanak135= '$asanak135',
		asanak136= '$asanak136',
		asanak137= '$asanak137',
		asanak138= '$asanak138',
		asanak139= '$asanak139',
		asanak140= '$asanak140',
		asanak141= '$asanak141',
		asanak142= '$asanak142',
		asanak143= '$asanak143',
		asanak144= '$asanak144',
		asanak145= '$asanak145',
		asanak146= '$asanak146',
		asanak147= '$asanak147',
		asanak148= '$asanak148',
		asanak149= '$asanak149',
		asanak150= '$asanak150',
		asanak151= '$asanak151',
		asanak152= '$asanak152',
		asanak153= '$asanak153',
		asanak154= '$asanak154',
		asanak155= '$asanak155',
		asanak156= '$asanak156',
		asanak157= '$asanak157',
		asanak158= '$asanak158',
		asanak159= '$asanak159',
		asanak160= '$asanak160',
		asanak161= '$asanak161',
		asanak162= '$asanak162',
		asanak163= '$asanak163',
		asanak164= '$asanak164',
		asanak165= '$asanak165',
		asanak166= '$asanak166',
		asanak167= '$asanak167',
		asanak168= '$asanak168',
		asanak169= '$asanak169',
		asanak170= '$asanak170',
		asanak171= '$asanak171',
		asanak172= '$asanak172',
		asanak173= '$asanak173',
		asanak174= '$asanak174',
		asanak175= '$asanak175',
		asanak176= '$asanak176',
		asanak177= '$asanak177',
		asanak178= '$asanak178',
		asanak179= '$asanak179',
		asanak180= '$asanak180',
		asanak181= '$asanak181',
		asanak182= '$asanak182',
		asanak183= '$asanak183',
		asanak184= '$asanak184',
		asanak185= '$asanak185',
		asanak186= '$asanak186',
		asanak187= '$asanak187',
		asanak188= '$asanak188',
		asanak189= '$asanak189',
		asanak190= '$asanak190',
		asanak191= '$asanak191',
		asanak192= '$asanak192',
		asanak193= '$asanak193',
		asanak194= '$asanak194',
		asanak195= '$asanak195',
		asanak196= '$asanak196',
		asanak197= '$asanak197',
		asanak198= '$asanak198',
		asanak199= '$asanak199',
		asanak200= '$asanak200',
		asanak201= '$asanak201',
		asanak202= '$asanak202',
		asanak203= '$asanak203',
		asanak204= '$asanak204',
		asanak205= '$asanak205',
		asanak206= '$asanak206',
		asanak207= '$asanak207',
		asanak208= '$asanak208',
		asanak209= '$asanak209',
		asanak210= '$asanak210',
		ku_beratbadan= '$ku_beratbadan',
		ku_tinggibadan= '$ku_tinggibadan',
		type_persalinan1= '$type_persalinan1',
		type_persalinan2= '$type_persalinan2',
		type_persalinan3= '$type_persalinan3',
		type_persalinan4= '$type_persalinan4',
		type_persalinan5= '$type_persalinan5',
		r_tumbuhkembang1= '$r_tumbuhkembang1',
		r_tumbuhkembang2= '$r_tumbuhkembang2',
		r_tumbuhkembang3= '$r_tumbuhkembang3',
		r_tumbuhkembang4= '$r_tumbuhkembang4',
		r_tumbuhkembang5= '$r_tumbuhkembang5',
		r_tumbuhkembang6= '$r_tumbuhkembang6',
		r_tumbuhkembang7= '$r_tumbuhkembang7',
		r_tumbuhkembang8= '$r_tumbuhkembang8',
		r_tumbuhkembang9= '$r_tumbuhkembang9',
		r_tumbuhkembang10= '$r_tumbuhkembang10',
		r_tumbuhkembang11= '$r_tumbuhkembang11',
		r_tumbuhkembang12= '$r_tumbuhkembang12',
		r_hamil1= '$r_hamil1',
		r_hamil2= '$r_hamil2',
		r_hamil3= '$r_hamil3',
		r_hamil4= '$r_hamil4',
		r_hamil5= '$r_hamil5',
		r_hamil6= '$r_hamil6',
		r_hamil7= '$r_hamil7',
		r_hamil8= '$r_hamil8',
		r_hamil9= '$r_hamil9',
		nutrisi1= '$nutrisi1',
		nutrisi2= '$nutrisi2',
		nutrisi3= '$nutrisi3',
		nutrisi4= '$nutrisi4',
		nutrisi5= '$nutrisi5',
		nutrisi6= '$nutrisi6',
		ku_gcs_e= '$ku_gcs_e',
		ku_gcs_v= '$ku_gcs_v',
		ku_gcs_m= '$ku_gcs_m',
		ku_suhu= '$ku_suhu',
		ku_tensi= '$ku_tensi',
		ku_nadi_ket= '$ku_nadi_ket',
		ku_nafas= '$ku_nafas',
		ku_spo= '$ku_spo',ku_kesadaran='$ku_kesadaran',total_gcs='$total_gcs',ku_nadi='$ku_nadi',
		dpjp='$dpjp'
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

