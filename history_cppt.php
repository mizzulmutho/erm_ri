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
$user = $row[1]; 
$afilter = $row[2]; 


if (isset($_POST["rencana_terapi_eresep"])) {

	$afilter='rencana_terapi_eresep';
	echo "
	<script>
	window.location.replace('history_cppt.php?id=$id|$user|$afilter');
	</script>
	";

}

if (isset($_POST["assesment_awal"])) {

	$afilter='assesment_awal';
	echo "
	<script>
	window.location.replace('history_cppt.php?id=$id|$user|$afilter');
	</script>
	";

}

if (isset($_POST["cppt"])) {

	$afilter='cppt';
	echo "
	<script>
	window.location.replace('history_cppt.php?id=$id|$user|$afilter');
	</script>
	";

}

if (isset($_POST["rencana_terapi"])) {

	$afilter='rencana_terapi';
	echo "
	<script>
	window.location.replace('history_cppt.php?id=$id|$user|$afilter');
	</script>
	";

}

if (isset($_POST["advis_igd"])) {

	$afilter='advis_igd';
	echo "
	<script>
	window.location.replace('history_cppt.php?id=$id|$user|$afilter');
	</script>
	";

}

if (isset($_POST["hasil_lab"])) {

	$afilter='hasil_lab';
	echo "
	<script>
	window.location.replace('history_cppt.php?id=$id|$user|$afilter');
	</script>
	";

}

if (isset($_POST["hasil_rad"])) {

	$afilter='hasil_rad';
	echo "
	<script>
	window.location.replace('history_cppt.php?id=$id|$user|$afilter');
	</script>
	";

}


$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];
$sbu2 = $sbu;

if (isset($_POST["ambil_lab"])) {
	$subjektif	= trim($_POST["subjektif"]);
	//$objektif	= trim($_POST["objektif"]);
	$assesment	= trim($_POST["assesment"]);
	//$planning	= trim($_POST["planning"]);
	$penunjang	= trim($_POST["penunjang"]);
	$assesmen	= trim($_POST["assesmen"]);

	$eye	= trim($_POST["eye"]);
	$verbal	= trim($_POST["verbal"]);
	$movement	= trim($_POST["movement"]);
	$tekanan_darah	= trim($_POST["tekanan_darah"]);
	$nadi	= trim($_POST["nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$frekuansi_pernafasan	= trim($_POST["frekuansi_pernafasan"]);
	$skala_nyeri	= trim($_POST["skala_nyeri"]);
	$berat_badan	= trim($_POST["berat_badan"]);
	$ket_nadi	= trim($_POST["ket_nadi"]);
	$plab	= trim($_POST["plab"]);
	$prad	= trim($_POST["prad"]);

	$q  = "delete from ERM_RI_SOAP_TEMP where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);

	$q  = "
	insert into ERM_RI_SOAP_TEMP
	(noreg,userid,tglentry,tgl,
	subjektif, objektif, assesment, planning, penunjang, assesmen, eye, verbal, movement, tekanan_darah, nadi, suhu, frekuansi_pernafasan, skala_nyeri, berat_badan,ket_nadi,plab,prad) 
	values 
	('$noreg','$user','$tglinput','$tglinput',
	'$subjektif', '$objektif', '$assesment', '$planning', '$penunjang', '$assesmen', '$eye', '$verbal', '$movement', 
	'$tekanan_darah', '$nadi', '$suhu', '$frekuansi_pernafasan', '$skala_nyeri', '$berat_badan','$ket_nadi','$plab','$prad'
	)
	";
	$hs = sqlsrv_query($conn,$q);

	header("Location: lab_list_dokter.php?id=$id|$user");

}
if (isset($_POST["ambil_rad"])) {
	$subjektif	= trim($_POST["subjektif"]);
	//$objektif	= trim($_POST["objektif"]);
	$assesment	= trim($_POST["assesment"]);
	// $planning	= trim($_POST["planning"]);
	$penunjang	= trim($_POST["penunjang"]);
	$assesmen	= trim($_POST["assesmen"]);

	$eye	= trim($_POST["eye"]);
	$verbal	= trim($_POST["verbal"]);
	$movement	= trim($_POST["movement"]);
	$tekanan_darah	= trim($_POST["tekanan_darah"]);
	$nadi	= trim($_POST["nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$frekuansi_pernafasan	= trim($_POST["frekuansi_pernafasan"]);
	$skala_nyeri	= trim($_POST["skala_nyeri"]);
	$berat_badan	= trim($_POST["berat_badan"]);
	$ket_nadi	= trim($_POST["ket_nadi"]);
	$plab	= trim($_POST["plab"]);
	$prad	= trim($_POST["prad"]);

	$q  = "delete from ERM_RI_SOAP_TEMP where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);

	$q  = "
	insert into ERM_RI_SOAP_TEMP
	(noreg,userid,tglentry,tgl,
	subjektif, objektif, assesment, planning, penunjang, assesmen, eye, verbal, movement, tekanan_darah, nadi, suhu, frekuansi_pernafasan, skala_nyeri, berat_badan,ket_nadi,plab,prad) 
	values 
	('$noreg','$user','$tglinput','$tglinput',
	'$subjektif', '$objektif', '$assesment', '$planning', '$penunjang', '$assesmen', '$eye', '$verbal', '$movement', 
	'$tekanan_darah', '$nadi', '$suhu', '$frekuansi_pernafasan', '$skala_nyeri', '$berat_badan','$ket_nadi','$plab','$prad'
	)
	";
	$hs = sqlsrv_query($conn,$q);

	header("Location: rad_list_dokter.php?id=$id|$user");

}

if (isset($_POST["ambil_eresep"])) {

	$subjektif	= trim($_POST["subjektif"]);
	$objektif	= trim($_POST["objektif"]);
	$assesment	= trim($_POST["assesment"]);
	$planning	= trim($_POST["planning"]);
	$penunjang	= trim($_POST["penunjang"]);
	$assesmen	= trim($_POST["assesmen"]);

	$eye	= trim($_POST["eye"]);
	$verbal	= trim($_POST["verbal"]);
	$movement	= trim($_POST["movement"]);
	$tekanan_darah	= trim($_POST["tekanan_darah"]);
	$nadi	= trim($_POST["nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$frekuansi_pernafasan	= trim($_POST["frekuansi_pernafasan"]);
	$skala_nyeri	= trim($_POST["skala_nyeri"]);
	$berat_badan	= trim($_POST["berat_badan"]);
	$ket_nadi	= trim($_POST["ket_nadi"]);
	$plab	= trim($_POST["plab"]);
	$prad	= trim($_POST["prad"]);

	$q  = "delete from ERM_RI_SOAP_TEMP where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);

	$q  = "
	insert into ERM_RI_SOAP_TEMP
	(noreg,userid,tglentry,tgl,
	subjektif, objektif, assesment, planning, penunjang, assesmen, eye, verbal, movement, tekanan_darah, nadi, suhu, frekuansi_pernafasan, skala_nyeri, berat_badan,ket_nadi,plab,prad) 
	values 
	('$noreg','$user','$tglinput','$tglinput',
	'$subjektif', '$objektif', '$assesment', '$planning', '$penunjang', '$assesmen', '$eye', '$verbal', '$movement', 
	'$tekanan_darah', '$nadi', '$suhu', '$frekuansi_pernafasan', '$skala_nyeri', '$berat_badan','$ket_nadi','$plab','$prad'
	)
	";
	$hs = sqlsrv_query($conn,$q);

	header("Location: eresep_list_dokter.php?id=$id|$user");
}


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

$row = explode(',',$umur);
$tahun_u  = $row[0];
$tahun_u = intval(substr($tahun_u, 0,3));

//$tahun_u = 61;

$bulan_u = $row[1]; 
$bulan_u = intval(substr($bulan_u, 0,3));

$hari_u = $row[2]; 
$hari_u = intval(substr($hari_u, 0,3));

$qu="
SELECT        TOP (200) NORM,  ALERGI
FROM            Y_ALERGI 
where norm='$norm'

union 
SELECT        ARM_REGISTER.NORM, V_ERM_RI_KEADAAN_UMUM.alergi as ALERGI
FROM            V_ERM_RI_KEADAAN_UMUM INNER JOIN
ARM_REGISTER ON V_ERM_RI_KEADAAN_UMUM.noreg = ARM_REGISTER.NOREG
where ARM_REGISTER.NORM='$norm' and V_ERM_RI_KEADAAN_UMUM.alergi <> '' 

union
SELECT        ARM_REGISTER.NORM, ERM_RI_ALERGI.obat as ALERGI
FROM            ERM_RI_ALERGI INNER JOIN
ARM_REGISTER ON ERM_RI_ALERGI.noreg = ARM_REGISTER.NOREG

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
if($aresep<>'i_lab' and $aresep<>'i_rad' ){
	$q="
	SELECT        TOP (200) W_EResep_R.Id, CONVERT(VARCHAR, W_EResep_R.TglEntry, 25) AS tglentry, W_EResep_R.KodeR, AFarm_MstObat.NAMABARANG, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, 
	W_EResep_R.WaktuPakai, W_EResep_R.Keterangan, W_EResep_R.Satuan, Afarm_MstSatuan.NAMASATUAN
	FROM            W_EResep_R INNER JOIN
	Afarm_MstSatuan ON W_EResep_R.Satuan = Afarm_MstSatuan.KODESATUAN LEFT OUTER JOIN
	W_EResep_Racikan ON W_EResep_R.IdResep = W_EResep_Racikan.IdResep AND W_EResep_R.Id = W_EResep_Racikan.IdR LEFT OUTER JOIN
	AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
	WHERE        (W_EResep_R.IdResep = $aresep)
	";
	$hasil  = sqlsrv_query($conn, $q);  
	$no=1;
	while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

		$Id = $data[Id];
		$tgl = $data[tglentry];
		$nama_obat = trim($data[NAMABARANG]);
		$dosis = $data[AturanPakai];
		$jumlah = $data[Jumlah];
		$waktu_penggunaan = $data[WaktuPakai];
		$satuan = $data[NAMASATUAN];

		if(empty($nama_obat)){
			$qu3="SELECT Nama,Dosis  FROM  W_EResep_Racikan where idR='$Id'";
			$h1u3  = sqlsrv_query($conn, $qu3);        
			$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
			$nama_obat = trim($d1u3['Nama']);
			$dosis = $d1u3['Dosis'];
		}


		$detail = $no.'. '.$nama_obat.' : '.$jumlah.' - '.$dosis.' '.$satuan."\n";

		$planning = $planning.$detail;

		$no += 1;

	}
}
if($aresep=='i_lab'){
	$qlab="
	SELECT 
	CONVERT(VARCHAR, tgl, 25) as tgl,pemeriksaan,parameter,hasil,nilai_normal,flag,idlab
	FROM        ERM_RI_LAB_TEMP
	WHERE        (noreg = '$noreg')
	";
	$h_qlab  = sqlsrv_query($conn, $qlab);  
	$no=1;
	while   ($d_qlab = sqlsrv_fetch_array($h_qlab,SQLSRV_FETCH_ASSOC)){ 

		$pemeriksaan = $d_qlab[pemeriksaan];
		$parameter = trim($d_qlab[parameter]);
		$hasil = $d_qlab[hasil];
		$satuan = $d_qlab[satuan];
		$nilai_normal = $d_qlab[nilai_normal];
		$flag = $d_qlab[flag];

		$lab_detail = $no.'. '.$parameter.' : '.$hasil.' - '.$flag."\n";

		$plab = $plab.$lab_detail;

		$no += 1;

	}
}


if($aresep=='i_rad'){
	$qrad="
	SELECT 
	CONVERT(VARCHAR, tgl, 25) as tgl,hasil,uraian
	FROM        ERM_RI_RAD_TEMP
	WHERE        (noreg = '$noreg')
	";
	$h_qrad  = sqlsrv_query($conn, $qrad);  
	$no=1;
	while   ($d_qrad = sqlsrv_fetch_array($h_qrad,SQLSRV_FETCH_ASSOC)){ 

		$hasil = $d_qrad[hasil];
		$uraian = trim($d_qrad[uraian]);

		$rad_detail = $no.'. '.$hasil.' : '.$uraian."\n";

		$prad = $prad.$rad_detail;

		$no += 1;

	}
}


if (isset($_POST["kosongkan_isian"])) {
	$q  = "delete from ERM_RI_SOAP_TEMP where noreg='$noreg'";
	$hs = sqlsrv_query($conn,$q);
	header("Location: history_cppt.php?id=$id|$user");
}


$qt       = "
SELECT       subjektif, objektif, assesment, planning, penunjang, assesmen, eye, verbal, movement, tekanan_darah, nadi, suhu, frekuansi_pernafasan, skala_nyeri, berat_badan,ket_nadi,plab,prad
FROM            ERM_RI_SOAP_TEMP where noreg='$noreg'
";
$ht  = sqlsrv_query($conn, $qt);                

$dht    = sqlsrv_fetch_array($ht, SQLSRV_FETCH_ASSOC);                      
$subjektif = $dht[subjektif];
$objektif = $dht[objektif];
$assesment = $dht[assesment];
$planning = $planning.$dht[planning];
$penunjang = $dht[penunjang];
$assesmen = $dht[assesmen];
$eye = $dht[eye];
$verbal = $dht[verbal];
$movement = $dht[movement];
$tekanan_darah = $dht[tekanan_darah];
$nadi = $dht[nadi];
$suhu = $dht[suhu];
$frekuansi_pernafasan = $dht[frekuansi_pernafasan];
$skala_nyeri = $dht[skala_nyeri];
$berat_badan = $dht[berat_badan];
$ket_nadi = $dht[ket_nadi];

if($aresep<>'i_lab'){
	$plab = $dht[plab];
}

if($aresep<>'i_rad'){
	$prad = $dht[prad];
}

$qr="
SELECT count(NOREG) as jreg FROM ERM_SOAP
where noreg='$noreg' group by noreg";
$hr  = sqlsrv_query($conn, $qr);        
$dr  = sqlsrv_fetch_array($hr, SQLSRV_FETCH_ASSOC); 
$jreg = $dr['jreg'];

if(empty($jreg)){
	$qe="
	SELECT resume20,resume21,resume22
	FROM ERM_RI_RESUME
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tglresume = $de['tglresume'];

	$resume20= $de['resume20'];
	$resume21= $de['resume21'];
	$resume22= $de['resume22'];

	if(empty($assesment)){
		$assesment=
		"Diagnosis Awal / Masuk : ". $resume20."\n".
		"Diagnosis Akhir (Primer) : ". $resume21."\n".
		"Diagnosis Akhir (Sekunder) ". $resume22;
	}

	$qu="SELECT * FROM  V_ERM_RI_KEADAAN_UMUM where noreg='$noreg' and tensi is not null";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$kesadaran = $d1u['kesadaran'];
	$e = $d1u['e'];
	$v = $d1u['v'];
	$m = $d1u['m'];
	$suhu = $d1u['suhu'];
	$tensi = $d1u['tensi'];
	$nadi = $d1u['nadi'];
	$ket_nadi = $d1u['ket_nadi'];
	$nafas = $d1u['nafas'];
	$spo = $d1u['spo'];
	$bb = $d1u['bb'];
	$tb = $d1u['tb'];
	$keluhan_utama = $d1u['keluhan_utama'];
	$riwayat_penyakit = $d1u['riwayat_penyakit'];


	if(!empty($skala_nyeri)){
		$skala_nyeri = $d1u['skala_nyeri'].' Lokasi Nyeri : '.$d1u['lokasi_nyeri'];
	}else{
		$skala_nyeri='-';
	}

	$berat_badan = $d1u['bb'];


	if(empty($subjektif)){
		$subjektif=$keluhan_utama;
	}

	if(empty($objektif)){
		$objektif='kesadaran : '.$kesadaran;
	}

	$eye	= $e;
	$verbal	= $v;
	$movement	= $m;
	$tekanan_darah	= $tensi;
	$frekuansi_pernafasan	= $nafas;

}

$qe="
SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl,
CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
CONVERT(VARCHAR, tglentry, 8) as jam_assesment
FROM ERM_RI_ANAMNESIS_MEDIS
where noreg='$noreg'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$userid = $de['userid'];

$tgl = $de['tgl'];
$tgl_assesment = $de['tgl_assesment'];
$jam_assesment = $de['jam_assesment'];
$dpjp = $de['dpjp'];
$userid = $de['userid'];

$am1 = $de['am1'];
$am2= $de['am2'];
$am3= $de['am3'];
$am4= $de['am4'];
$am5= $de['am5'];
$am6= $de['am6'];
$am7= $de['am7'];
$am8= $de['am8'];
$am9= $de['am9'];
$am10= $de['am10'];
$am11= $de['am11'];
$am12= $de['am12'];
$am13= $de['am13'];
$am14= $de['am14'];
$am15= $de['am15'];
$am16= $de['am16'];
$am17= $de['am17'];
$am18= $de['am18'];
$am19= $de['am19'];
$am20= $de['am20'];
$am21= $de['am21'];
$am22= $de['am22'];
$am23= $de['am23'];
$am24= $de['am24'];
$am25= $de['am25'];
$am26= $de['am26'];
$am27= $de['am27'];
$am28= $de['am28'];
$am29= $de['am29'];
$am30= $de['am30'];
$am31= $de['am31'];
$am32= $de['am32'];
$am33= $de['am33'];
$am34= $de['am34'];
$am35= $de['am35'];
$am36= $de['am36'];
$am37= $de['am37'];
$am38= $de['am38'];
$am39= $de['am39'];
$am40= $de['am40'];
$am41= $de['am41'];
$am42= $de['am42'];
$am43= $de['am43'];
$am44= $de['am44'];
$am45= $de['am45'];
$am46= $de['am46'];
$am47= $de['am47'];
$am48= $de['am48'];
$am49= $de['am49'];
$am50= $de['am50'];
$am51= $de['am51'];
$am52= $de['am52'];
$am53= $de['am53'];
$am54= $de['am54'];
$am55= $de['am55'];
$am56= $de['am56'];
$am57= $de['am57'];
$am58= $de['am58'];
$am59= $de['am59'];
$am60= $de['am60'];
$am61= $de['am61'];
$am62= $de['am62'];
$am63= $de['am63'];
$am64= $de['am64'];
$am65= $de['am65'];
$am66= $de['am66'];
$am67= $de['am67'];
$am68= $de['am68'];
$am69= $de['am69'];
$am70= $de['am70'];
$am71= $de['am71'];
$am72= $de['am72'];
$am73= $de['am73'];
$am74= $de['am74'];
$am75= $de['am75'];
$am76= $de['am76'];
$am77= $de['am77'];
$am78= $de['am78'];
$am79= $de['am79'];
$am80= $de['am80'];

$qe="
SELECT resume20,resume21,resume22
FROM ERM_RI_RESUME
where noreg='$noreg'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$tglresume = $de['tglresume'];

$resume20= $de['resume20'];
$resume21= $de['resume21'];
$resume22= $de['resume22'];

if(!empty($resume20)){
	$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20;		
}
if(!empty($resume21)){
	$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20.'<br>Diagnosis Akhir (Primer) : '.$resume21;		
}
if(!empty($resume22)){
	$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20.'<br>Diagnosis Akhir (Primer) : '.$resume21.'<br>Diagnosis Akhir (Sekunder) : '.$resume22;		
}

//ambil dari observasi...
$qo="SELECT TOP(1) ob3 as e, ob4 as v, ob5 as m, ob6 as total, ob9 as bb,
td_sistolik,td_diastolik,nadi,suhu,pernafasan,spo2,bb,tb,total_ews,
CONVERT(VARCHAR, tglinput, 103) as tgl_observasi,
CONVERT(VARCHAR, tglinput, 8) as jam_observasi
FROM  ERM_RI_OBSERVASI where noreg='$noreg' and suhu > 0 order by id desc";
$ho  = sqlsrv_query($conn, $qo);        
$dho  = sqlsrv_fetch_array($ho, SQLSRV_FETCH_ASSOC); 
$kesadaran = $dho['kesadaran'];
$eye = $dho['e'];
$verbal = $dho['v'];
$movement = $dho['m'];
$total_ews = $dho['total_ews'];
$td_sistolik = $dho['td_sistolik'];
$td_diastolik = $dho['td_diastolik'];
$tekanan_darah = $td_sistolik.'/'.$td_diastolik;
$nadi = $dho['nadi'];
$suhu = $dho['suhu'];
$frekuansi_pernafasan = $dho['pernafasan'];
$berat_badan = $dho['bb'];
$skala_nyeri = 0;
$tgl_observasi = $dho['tgl_observasi'];
$jam_observasi = $dho['jam_observasi'];

$qn="select TOP(1) skala from ERM_RI_NYERI where noreg='$noreg' order by id desc";
$hn  = sqlsrv_query($conn, $qn);        
$dhn  = sqlsrv_fetch_array($hn, SQLSRV_FETCH_ASSOC); 
$skala_nyeri = $dhn['skala'];
if(empty($skala_nyeri)){
	$skala_nyeri='0';
}

//$planning = $am77;

$noreg_igd = substr($noreg, 1,12);

$kodedokter  = substr($user,0,3);

//ambil cppt terakhir dokter
$qhiscppt="
SELECT        TOP (1) subjektif, objektif, assesment, planning
FROM            ERM_SOAP INNER JOIN
Afarm_DOKTER ON ERM_SOAP.kodedokter = Afarm_DOKTER.KODEDOKTER
WHERE        (ERM_SOAP.noreg LIKE '%$noreg%') AND (ERM_SOAP.kodedokter = '$kodedokter') order by id desc
";
$hasilhiscppt  = sqlsrv_query($conn, $qhiscppt);  
while   ($datahiscppt = sqlsrv_fetch_array($hasilhiscppt,SQLSRV_FETCH_ASSOC)){ 

	$subjektif3 = trim($datahiscppt[subjektif]);
	$objektif3 = trim($datahiscppt[objektif]);
	$assesment3 = trim($datahiscppt[assesment]);
	$planning3 = trim($datahiscppt[planning]);
}



if(!empty($am77)){
	$planning = $planning."\n\n".$am77;
}else{
	$planning = $planning;
}

if($his_cppt){
	$planning = $his_cppt;
}


if($planning3){
	$planning = $planning3;
}
if($subjektif3){
	$subjektif = $subjektif3;
}
if($subjektif3){
	$objektif = $objektif3;
}
if($assesment3){
	$assesment = $assesment3;
}




?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

	<script>
		$(function() {
			$("#dpjp").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dpjp.php', //your                         
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

	<body onload="document.myForm.ku.focus();" style="background-color: #E8F9FF;">
		
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">

			<div class="row">
				<div class="col-12 text-left">
					<br>
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
				</div>
			</div>

			<div class="row">
				<?php include('menu_dokter.php');?>
			</div>


			<div class="row">
				<div class="col-12 text-left">
					<?php 
					include "header_soap.php";
					?>
				</div>
			</div>


			<div class="row">
				<div class="col-12 text-left">
					History Pemeriksaan Pasien
				</div>
			</div>

			<div class="row">
				<div class="col-12 text-left">
					<table cellpadding="10" bgcolor='white' width="100%">
						<tr valign="top">
							<td width="60%">
								<?php
											// echo $afilter;
								if($afilter==''){
									$kodedokter  = substr($user,0,3);

									echo "
									<font size='2'>
									<b>History CPPT Terakhir</b><br>
									";

									$qlc="
									SELECT TOP(1) id,kodedokter,noreg,userid,dpjp,subjektif,objektif,assesment,planning,instruksi,sbu,kodeunit,
									CONVERT(VARCHAR, tanggal, 103) as tgl2,
									CONVERT(VARCHAR, tglentry, 8) as tgl3, 
									CONVERT(VARCHAR, tglentry, 20) as tgl4,'SOAP' as jenis,instruksi  
									FROM ERM_SOAP WHERE norm='$norm' and noreg = '$noreg' and kodedokter='$kodedokter' order by id desc
									";

									$hlc  = sqlsrv_query($conn, $qlc);
									$noc=1;

									echo 
									"
									<table class='tables'>
									<tr bgcolor='#969392'>
									<td align='center'><font color='white'>No</font></td>
									<td align='center'><font color='white'>PPA</font></td>
									<td align='center'><font color='white'>Hasil Pemeriksaan, analisis, Rencana, Penatalaksanaan Pasien</font></td>
									</tr>";


									while   ($dl = sqlsrv_fetch_array($hlc, SQLSRV_FETCH_ASSOC)){  
										$kodedokter = trim($dl[kodedokter]);
										$noreg = trim($dl[noreg]);
										$jenis = trim($dl[jenis]);
										$jam_ccpt = substr($dl[tgl3],0,5);

										$kodedokter = trim($dl[kodedokter]);
										$noreg = trim($dl[noreg]);
										$userid = trim($dl[userid]);
										$dpjp = trim($dl[dpjp]);
										$periode = trim($dl[tgl2]);

										$q2		= "select nama from afarm_dokter where kodedokter like '%$kodedokter%'";
										$hasil2  = sqlsrv_query($conn, $q2);			  					
										$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
										$namadokter	= $data2[nama];
										$profesi = 'DOKTER';

										$q3		= "
										SELECT        ERM_SOAP.dpjp, CONVERT(VARCHAR, ERM_SOAP.verif_dpjp, 103) AS tgl, 
										CONVERT(VARCHAR, ERM_SOAP.verif_dpjp, 8) as tgl3, 
										Afarm_DOKTER.NAMA
										FROM            ERM_SOAP LEFT OUTER JOIN
										Afarm_DOKTER ON ERM_SOAP.dpjp = Afarm_DOKTER.KODEDOKTER
										WHERE        (ERM_SOAP.noreg = '$noreg') AND (ERM_SOAP.id = $dl[id]) and ERM_SOAP.verif_dpjp is not null
										";
										$hasil3  = sqlsrv_query($conn, $q3);			  					
										$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
										$userverif	= $data3[NAMA];
										$tanggal	= $data3[tgl];
										$jam_verif = substr($data3[tgl3],0,5);

										$subjektif = nl2br($dl[subjektif]);
										$objektif = nl2br($dl[objektif]);
										$assesment = nl2br($dl[assesment]);
										$planning = nl2br($dl[planning]);
										$instruksi = nl2br($dl[instruksi]);

										$hasilassesment = "
										<b>Subject :</b> $subjektif<br>
										<b>Object :</b> $objektif<br>
										<b>Assesment :</b> $assesment<br>
										<b>Plan :</b> $planning<br>
										<b>Instruksi :</b> $instruksi
										";

										echo "	<tr>
										<td>$noc</td>
										<td>$namadokter<br>$dl[noreg]<br>$dl[tgl2]<br>$jam_ccpt<br>$dl[kodeunit] - $dl[sbu]</td>
										<td>
										$hasilassesment
										</td>
										</tr>
										";



										$noc += 1;

									}

									echo "</table> 
									</font>";
								}
								if($afilter=='assesment_awal'){
									echo "
									<font size='2'>
									&nbsp;<b>Assesment Awal Medik</b><br>
									<table width='100%''>
									<tr>
									<td width='50%''>
									<table width='100%''>
									<tr>
									<td>Diagnosa : $diagnosa</td>
									</tr>
									<tr>
									<td>Keluhan Utama : $am1</td>
									</tr>
									<tr>
									<td>Nama Penyakit : $am2</td>
									</tr>
									<tr>
									<td>Penunjang : $am76</td>
									</tr>
									</table>
									</td>
									</tr>
									</table>
									</font>
									";
								}
								if($afilter=='cppt'){
									echo "<font size='2'>";
									$ql="
									SELECT DISTINCT kodedokter
									FROM ERM_SOAP WHERE norm='$norm' and noreg like '%$noreg%' and kodedokter like '%S%'
									";

									$hl  = sqlsrv_query($conn, $ql);
									$no=1;
									echo 
									"<table class='table'>
									<tr bgcolor='#969392'>
									<td width='10%'>No</td>
									<td width='30%'>Profesional Pemberi Asuhan</td>
									<td width='60%'>Keterangan</td>	
									</tr>";

									$tampil_ass="Y";
									$no=1;

									while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){ 

										$kodedokter = trim($dl[kodedokter]);
										$noreg = trim($dl[noreg]);
										$dokter_ass	= substr($kodedokter,0,3);

										$q2		= "select nama from afarm_dokter where kodedokter ='$kodedokter'";
										$hasil2  = sqlsrv_query($conn, $q2);			  					
										$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
										$namadokter	= $data2[nama];
										$profesi = 'DOKTER';

										if(empty($namadokter)){
											$q2		= "select nama from  MASTER_APOTEKER where apoteker='$kodedokter'";
											$hasil2  = sqlsrv_query($conn, $q2);			  					
											$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
											$namadokter	= $data2[nama];
											$profesi = 'APOTEKER';
										}

										if(empty($namadokter)){
											$q2		= "select nama from afarm_paramedis where kode='$kodedokter'";
											$hasil2  = sqlsrv_query($conn, $q2);			  					
											$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
											$namadokter	= $data2[nama];
											$profesi = 'PERAWAT';
										}

										echo 
										"<tr>
										<td>$no</td>
										<td>$namadokter ($kodedokter)</td>
										<td><a href='r_soap_dokter3.php?id=$id|$user|$kodedokter' target='_blank'>Lihat Rekap CPPT</a></td>	
										</tr>";

										$no += 1;
									}
									echo "</table>";
									echo "</font>";
								}

								if($afilter=='rencana_terapi'){

									echo "<u>Rencana Terapi</u>";
									echo "<br>";
									$qe="
									SELECT am77 FROM ERM_RI_ANAMNESIS_MEDIS
									where noreg='$noreg'";
									$he  = sqlsrv_query($conn, $qe);        
									$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
									$userid = $de['userid'];

									echo $am77= nl2br($de['am77']);

								}

								if($afilter=='advis_igd'){
									echo "<u>advis dari IGD</u>";
									echo "<br>";

									$noreg_igd = substr($noreg, 1,12);

									$qd="
									SELECT        'IGD' AS unit, ERM_IGD_ADVIS.ADVIS, Afarm_DOKTER.NAMA, CONVERT(VARCHAR, ERM_IGD_ADVIS.TGLENTRY, 103) AS tgl, CONVERT(VARCHAR, ERM_IGD_ADVIS.TGLENTRY, 8) AS jam
									FROM            ERM_IGD_ADVIS INNER JOIN
									Afarm_DOKTER ON ERM_IGD_ADVIS.KODEDOKTER = Afarm_DOKTER.KODEDOKTER
									WHERE        (ERM_IGD_ADVIS.NOREG = '$noreg_igd')
									";
									$hasild  = sqlsrv_query($conn, $qd);  
									$no=1;
									while   ($datad = sqlsrv_fetch_array($hasild,SQLSRV_FETCH_ASSOC)){ 

										$advis = trim($datad[ADVIS]);
										$dokter_advis = trim($datad[NAMA]);
										$tgl_advis = trim($datad[tgl]);
										$jam_advis = trim($datad[jam]);
										$detail = $no.'. '.$dokter_advis.'. '.$tgl_advis.'-'.$jam_advis.'. '."\nAdvis : ".$advis."\n\n";
										$terapi_igd = $terapi_igd.$detail;
										$no += 1;
									}

									echo nl2br($terapi_igd);
								}

								if($afilter=='hasil_lab'){

									$noreg_igd = substr($noreg, 1,12);

									$qu="SELECT        TOP (1) DAY(REG_DATE) AS hari
									FROM            LINKYAN5.SHARELIS.dbo.hasilLIS
									WHERE        (NOLAB_RS = '$noreg_igd')
									ORDER BY REG_DATE DESC";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$harix = intval($d1u['hari']);
									$hari = $harix;

									$hari2 = $hari+1;
									$hari3 = $hari2+1;
									$hari4 = $hari3+1;
									$hari5 = $hari4+1;
									$hari6 = $hari5+1;
									$hari7 = $hari6+1;
									$hari8 = $hari7+1;
									$hari9 = $hari8+1;
									$hari10 = $hari9+1;

									$qlab="
									SELECT * 
									FROM 
									(
										SELECT 
										DAY(REG_DATE) AS day, 
										TARIF_NAME,
										KEL_PEMERIKSAAN,
										PARAMETER_NAME, 
										CASE 
										WHEN LEFT(NOLAB_RS, 1) <> 'R' THEN 'IGD : ' + HASIL + FLAG 
										ELSE HASIL + FLAG 
										END AS HASIL,
										NOLAB_RS 
										FROM LINKYAN5.SHARELIS.dbo.hasilLIS
										WHERE 
										NOLAB_RS like '%$noreg_igd%'
										AND DAY(REG_DATE) BETWEEN $hari AND $harix 
										) AS SourceTable
									PIVOT
									(
										MAX(HASIL) 
										FOR day IN ([$hari], [$hari2], [$hari3], [$hari4], [$hari5], [$hari6], [$hari7], [$hari8], [$hari9], [$hari10])  
										) AS PivotTable;
									";
									$hqlab  = sqlsrv_query($conn, $qlab);

									echo "<table class='table'>
									<tr>
									<td width='1%'>no</td><td>tarif name</td><td>kel pemeriksaan</td><td>parameter name</td>
									<td>$hari</td>
									<td>$hari2</td>
									<td>$hari3</td>
									<td>$hari4</td>
									<td>$hari5</td>
									<td>$hari6</td>
									<td>$hari7</td>
									<td>$hari8</td>
									<td>$hari9</td>
									<td>$hari10</td>
									</tr>
									";
									$i=1;
									while   ($dhqlab = sqlsrv_fetch_array($hqlab, SQLSRV_FETCH_ASSOC)){     
										$dhqlab[REG_DATE];
										if(substr($dhqlab[PARAMETER_NAME],0,1)=='-'){
											$kethlab=' <i>HITUNG JENIS (DIFF) :<i>';
										}else if(substr($dhqlab[PARAMETER_NAME],0,1)==' '){
											$kethlab=' <i>INDEX ERITROSIT :<i>';
										}else{
											$kethlab='';
										}

										if($kethlab==''){
											echo "
											<tr>
											<td><font size='2'>$i</font></td>
											<td><font size='2'>$dhqlab[TARIF_NAME]</font></td>
											<td><font size='2'>$dhqlab[KEL_PEMERIKSAAN]</font></td>
											<td><font size='2'>$kethlab $dhqlab[PARAMETER_NAME]</font></td>
											<td><font size='2'>$dhqlab[$hari]</font></td>
											<td><font size='2'>$dhqlab[$hari2]</font></td>
											<td><font size='2'>$dhqlab[$hari3]</font></td>
											<td>$dhqlab[$hari4]</td>
											<td>$dhqlab[$hari5]</td>
											<td>$dhqlab[$hari6]</td>
											<td>$dhqlab[$hari7]</td>
											<td>$dhqlab[$hari8]</td>
											<td>$dhqlab[$hari9]</td>
											<td>$dhqlab[$hari10]</td>
											</tr>
											";
										}

										$i=$i+1;
									}

									echo "</table>";
									;

								}

								if($afilter=='hasil_rad'){
									$noreg_igd = substr($noreg, 1,12);
									$qrad="
									SELECT HASILRAD_PEMERIKSAAN_RAD.HASIL, HASILRAD_PEMERIKSAAN_RAD.URAIAN, 
									CONVERT(VARCHAR, HASILRAD_PEMERIKSAAN_RAD.TANGGAL, 103) as TANGGAL
									FROM            ERM_RI_ASSESMEN_AWAL_DEWASA INNER JOIN
									HASILRAD_PEMERIKSAAN_RAD ON ERM_RI_ASSESMEN_AWAL_DEWASA.noreg = HASILRAD_PEMERIKSAAN_RAD.NOREG
									where HASILRAD_PEMERIKSAAN_RAD.noreg='$noreg_igd'
									ORDER BY ERM_RI_ASSESMEN_AWAL_DEWASA.noreg, HASILRAD_PEMERIKSAAN_RAD.TANGGAL
									";
									$hqrad  = sqlsrv_query($conn, $qrad);

									$i=1;
									while   ($dhqrad = sqlsrv_fetch_array($hqrad, SQLSRV_FETCH_ASSOC)){     
										$rad0 = $dhqrad[TANGGAL].'-'.$dhqrad[HASIL].':'.$dhqrad[URAIAN]."\n";
										$rad = $rad.'&#13;&#10;'.$rad0."\n";
									}
									echo nl2br($rad);
								}

								if($afilter=='rencana_terapi_eresep'){
									$noreg_igd = substr($noreg, 1,12);

									$nmtemplate = trim(substr($nama,0,10)).'/RI/'.$kodedokter.'/'.$tgl;

									$qu="SELECT Id,NamaTemplate FROM W_Tmp_EResep where NamaTemplate='$nmtemplate'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$NamaTemplate = $d1u['NamaTemplate'];
									$IdTemplate = $d1u['Id'];


									if(empty($NamaTemplate)){
										$pilih="-";													
									}else{
										$pilih="<a href='insert_eresep_cppt.php?id=$id|$user|$IdTemplate|$IdResep'><font color='gren'><i class='bi bi-check-all'></i> masukkan ke template</font></a>";
									}

									$q="
									SELECT        TOP (200) CONVERT(VARCHAR, W_EResep.TglEntry, 103) AS tgl, W_EResep.Id, W_EResep.Noreg, W_EResep.Norm, W_EResep.KodeDokter, W_EResep.NamaPasien, W_EResep.Kategori, W_EResep.StatusLayanan, 
									W_EResep.NomerAntrian, Afarm_DOKTER.NAMA
									FROM            W_EResep INNER JOIN
									Afarm_DOKTER ON W_EResep.KodeDokter = Afarm_DOKTER.KODEDOKTER
									WHERE        (W_EResep.Noreg = '$noreg')
									ORDER BY W_EResep.Id DESC
									";
									$hasil  = sqlsrv_query($conn, $q);  
									$no=1;
									while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
										if (empty($data[NomerAntrian])){
											$nomorantrian='Racikan';
										}else{
											$nomorantrian=$data[NomerAntrian];						
										}

										echo "
										<table bgcolor='' border='1' width='100%'>
										<tr>
										<td><font size='2' width='2%'>No</font></td>					
										<td><font size='2' width='20%'>Nama Dokter</font></td>
										<td><font size='2' width='10%'>Tgl</font></td>
										<td><font size='2' width='78%'>Detail Resep</font>
										<tr>
										<td><font size='2' width='2%'>$no</font></td>					
										<td><font size='2' width='20%'>$data[NAMA]<br>$data[KodeDokter]</font></td>
										<td><font size='2' width='10%'>$data[tgl]<br>$data[Kategori]</font></td>
										<td width='78%'>
										";

										$qdet="
										SELECT        TOP (200) W_EResep_R.Id, CONVERT(VARCHAR, W_EResep_R.TglEntry, 25) AS tglentry, W_EResep_R.KodeR, AFarm_MstObat.NAMABARANG, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, 
										W_EResep_R.WaktuPakai, W_EResep_R.Keterangan, W_EResep_R.Satuan, Afarm_MstSatuan.NAMASATUAN, W_EResep_R.Jenis
										FROM            W_EResep_R INNER JOIN
										Afarm_MstSatuan ON W_EResep_R.Satuan = Afarm_MstSatuan.KODESATUAN LEFT OUTER JOIN
										AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
										WHERE        (W_EResep_R.IdResep = $data[Id])
										";
										$hqdet  = sqlsrv_query($conn, $qdet);
										echo "
										<font size='2'>
										<table width='100%' border='1'>
										<tr>
										<td>jenis</td><td>kode</td><td>obat</td><td>jumlah</td><td>aturan pakai</td><td>cara pakai</td><td>waktu pakai</td><td>pilih</td>
										</tr>
										</font>
										";
										while   ($dhqdet = sqlsrv_fetch_array($hqdet,SQLSRV_FETCH_ASSOC)){

											$jenis = $dhqdet[Jenis];
											$IdResep = $dhqdet[Id];

											$pilih="<a href='insert_eresep_cppt.php?id=$id|$user|$IdTemplate|$IdResep'><font color='gren'><i class='bi bi-check-all'></i> masukkan ke template</font></a>";

											if($jenis=='1'){
												$jenis='NON RACIKAN';
											}else{
												$jenis='RACIKAN';
											}
											echo "
											<font size='2'>
											<tr>
											<td>$jenis</td>
											<td>$dhqdet[KodeR]</td>
											<td>$dhqdet[NAMABARANG]</td>
											<td>$dhqdet[Jumlah]</td>
											<td>$dhqdet[AturanPakai]</td>
											<td>$dhqdet[CaraPakai]</td>
											<td>$dhqdet[WaktuPakai]</td>
											<td align='center'>$pilih</td>
											</tr>
											</font>
											";
										}

										echo "</table>";													
										$no += 1;
										echo "</table>";
									}
								}

								?>
							</td>
							<td>
								<?php 
								if($afilter=='rencana_terapi_eresep'){
									echo "<font size='2'>";
									echo "Buat Template dari E-Resep<br>";
									$nmtemplate = trim(substr($nama,0,10)).'/RI/'.$kodedokter.'/'.$tgl;

									$qu="SELECT Id,NamaTemplate FROM W_Tmp_EResep where NamaTemplate='$nmtemplate'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									$NamaTemplate = $d1u['NamaTemplate'];

									if($NamaTemplate){
										echo "<b>Template berhasil dibuat</b><br> nama template : $NamaTemplate";
									}else{
										echo "Nama Template : 
										<input class='form-control-sm' name='kategori_resep' value='$nmtemplate' type='text' size='25' style='min-height:20px;'>
										";												
										echo "<br>";
										echo "
										<button type='submit' name='buat_resep' value='buat_resep' class='btn btn-success' type='button' style='height: 35px;width: 250px;'><i class='bi bi-save-fill'></i> Buat Template Resep Baru</button>
										";
									}



									$qlc="
									SELECT        TOP (200) W_Tmp_EResep_R.IdTemplate, W_Tmp_EResep_R.Jenis, W_Tmp_EResep_R.Id, W_Tmp_EResep_R.KodeR, W_Tmp_EResep_R.Jumlah, W_Tmp_EResep_R.AturanPakai, W_Tmp_EResep_R.CaraPakai, 
									W_Tmp_EResep_R.WaktuPakai, W_Tmp_EResep_R.Keterangan, W_Tmp_EResep_R.UserId, W_Tmp_EResep_R.Bentuk, W_Tmp_EResep_R.Satuan, AFarm_MstObat.NAMABARANG,W_Tmp_EResep_R.Id
									FROM            W_Tmp_EResep_R INNER JOIN
									AFarm_MstObat ON W_Tmp_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
									WHERE        (W_Tmp_EResep_R.IdTemplate = $IdTemplate)
									";

									$hlc  = sqlsrv_query($conn, $qlc);
									$noc=1;
									echo "<br><b>Detail Template Obat :</b><br>";
									echo "<table border='1'>";
									echo "
									<tr>
									<td>no</td><td>nama obat</td><td>jumlah</td><td>aturan pakai</td><td>cara pakai</td><td>delete</td>
									</tr>";
									$no1=1;
									while   ($dl = sqlsrv_fetch_array($hlc, SQLSRV_FETCH_ASSOC)){  
										echo "
										<tr>
										<td>$no1</td>
										<td>$dl[NAMABARANG]</td>
										<td>$dl[Jumlah]</td>
										<td>$dl[AturanPakai]</td>
										<td>$dl[CaraPakai]</td>
										<td><a href='delete_eresep_cppt.php?id=$id|$user|$dl[Id]'>delete</a></td>
										</tr>
										";
										$no1 += 1;
									}
									echo "</table>";
									echo "</font>";
								}
								?>
							</div>
						</div>

					</form>
				</body>
			</div>

			<?php

			if (isset($_POST["buat_resep"])) {

				$kategori = $_POST["kategori_resep"];

				$q  = " insert into  W_Tmp_EResep(
				KodeUnit, KodeDokter, UserId, NamaTemplate
				)
				values(
				'$kodeunit', '$kodedokter', '$userid', '$kategori' 
				)
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
				history.go(-1);
				</script>
				";

			}
