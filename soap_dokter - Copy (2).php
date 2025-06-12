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
$aresep = $row[2]; 


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

	header("Location: lab_list_dokter.php?id=$id|$user");

}
if (isset($_POST["ambil_rad"])) {
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
	header("Location: soap_dokter.php?id=$id|$user");
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
td_sistolik,td_diastolik,nadi,suhu,pernafasan,spo2,bb,tb,total_ews
FROM  ERM_RI_OBSERVASI where noreg='$noreg' and suhu > 0";
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

$qn="select TOP(1) skala from ERM_RI_NYERI where noreg='$noreg' order by id desc";
$hn  = sqlsrv_query($conn, $qn);        
$dhn  = sqlsrv_fetch_array($hn, SQLSRV_FETCH_ASSOC); 
$skala_nyeri = $dhn['skala'];
if(empty($skala_nyeri)){
	$skala_nyeri='0';
}

//$planning = $am77;


$noreg_igd = substr($noreg, 1,12);

$qd="
SELECT       'IGD' as unit, AFarm_MstObat.NAMABARANG as nama_obat,convert(varchar(10),W_EResep_R.Jumlah)  as jumlah,W_EResep_R.AturanPakai as aturan_pakai, W_EResep_R.CaraPakai,W_EResep_R.WaktuPakai
FROM            W_EResep INNER JOIN
W_EResep_R ON W_EResep.Id = W_EResep_R.IdResep LEFT OUTER JOIN
W_EResep_Racikan ON W_EResep_R.Id = W_EResep_Racikan.IdR LEFT OUTER JOIN
AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
WHERE        (W_EResep.Noreg = '$noreg_igd')
union
SELECT 'IGD' as unit, nama_obat, jumlah, aturan_pakai,'' as CaraPakai,'' as WaktuPakai
FROM ERM_RI_DISCHARGE where noreg='$noreg_igd'
order by nama_obat
";
$hasild  = sqlsrv_query($conn, $qd);  
$no=1;
while   ($datad = sqlsrv_fetch_array($hasild,SQLSRV_FETCH_ASSOC)){ 

	$unit = trim($datad[unit]);
	$nama_obat = trim($datad[nama_obat]);
	$jumlah = $datad[jumlah];
	$aturan_pakai = $datad[aturan_pakai];
	$CaraPakai = $datad[CaraPakai];
	$WaktuPakai = $datad[WaktuPakai];

	$detail = $no.'. '.$unit.'. '.$nama_obat.' : '.$jumlah.' - '.$aturan_pakai.' - '.$CaraPakai.' - '.$WaktuPakai."\n";

	$planning = $planning.$detail;

	$no += 1;

}


if(!empty($am77)){
	$planning = $planning."\n\n".$am77;
}else{
	$planning = $planning;
}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
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

	<body onload="document.myForm.ku.focus();">
		
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
			&nbsp;&nbsp;
			<a href='soap_dokter.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
			&nbsp;&nbsp;
			<a href='soap_dokter_list.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-info-circle"></i> Edit</a>
			&nbsp;&nbsp;
			<a href='r_soap_dokter.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info' target='_blank'><i class="bi bi-list-ol"></i> History</a>
			<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
			&nbsp;&nbsp;
			<br><br>

			<div class="row">

				<div class="col-12">
					<?php 
					include "header_soap.php";
					?>
				</div>

			</div>

			<div class="row">
				<div class="col-12 text-left">
					&nbsp;<b>Assesment Awal Medik</b><br>
					<table width="100%">
						<tr>
							<td width="50%">
								<table width="100%">
									<tr>
										<td>Diagnosa</td>
										<td>: <?php echo $diagnosa;?></td>
									</tr>
									<tr>
										<td>Keluhan Utama</td>
										<td>: <?php echo $am1;?></td>
									</tr>
									<tr>
										<td>Nama Penyakit</td>
										<td>: <?php echo $am2;?></td>
									</tr>
									<tr>
										<td>Penunjang</td>
										<td>: <?php echo $am76;?></td>
									</tr>

								</table>
							</td>
							<td>
								<table width="100%">
									<tr>
										<td>Rencana Terapi</td>
										<td>
											<textarea class="form-control" name="am77" cols="100%" onfocus="nextfield ='';" style="min-height:100px;" disabled><?php echo $am77;?></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</div>


			<div class="row">
				<div class="col-12 text-center">
					<b>INPUT SOAP </b><br>
				</div>
			</div>

			<hr>

<!-- 				<div class="row">
					<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
					<div class="col-6"><?php echo 'L/P : '.$kelamin.'<br> UMUR : '.$umur.'<br> ALAMAT : '.$alamatpasien; ?></div>
				</div>
			-->

			<font size='4px'>	
				<div class="row">
					<div class="col-6">
						&nbsp;&nbsp;&nbsp;<button type="submit" name="kosongkan_isian" class="btn btn-warning btn-sm" onfocus="nextfield ='done';">Kosongkan isian</button>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-6">
						<div class="col-12">Subjektif<br>
							<textarea name= "subjektif" id="" style="min-width:650px; min-height:150px;" required><?php echo $subjektif;?></textarea>
						</div>
						<div class="col-6">Assesment<br>
							<textarea name= "assesment" id="" style="min-width:650px; min-height:150px;"><?php echo $assesment;?></textarea>
						</div>
						<div class="col-6">Planning -  
							<!-- <a href='eresep_list_dokter.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info btn-sm'><i class="bi bi-box-arrow-in-right"></i> Ambil data e-Resep</a> -->

							<button type="submit" name="ambil_eresep" class="btn btn-info btn-sm" onfocus="nextfield ='done';">Ambil data e-Resep</button> 

							<br><br>
							<textarea name= "planning" id="" style="min-width:650px; min-height:250px;"><?php echo $planning;?></textarea>
						</div>
						<div class="col-6">Instruksi<br>
							<textarea name= "instruksi" id="" style="min-width:650px; min-height:150px;"><?php echo $instruksi;?></textarea>
						</div>	
					</div>
					<div class="col-6">
						<div class="col-12">
							<div class="row">
								<div class="col-12">
									Objektif<br>
									<textarea name= "objektif" id="" style="min-width:650px; min-height:150px;"><?php echo $objektif;?></textarea>
<!-- 									<hr>
									<textarea name= "ket_object" id="" style="min-width:650px; min-height:50px;"><?php echo $ket_object;?></textarea>
								-->								
							</div>
						</div>
						<div class="col-12">
							<div class="row">
								<div class="col-12">							
									Glassow Comma Scale (GCS)
								</div>
								<div class="col-12">
									Eye &nbsp;&nbsp;: 
									<input class="form-control-sm" name="eye" value="<?php echo $eye;?>" id="" type="text" size='5' onfocus="nextfield ='verbal';" placeholder="" style="min-height:50px;">
									Verbal : 
									<input class="form-control-sm" name="verbal" value="<?php echo $verbal;?>" id="" type="text" size='5' onfocus="nextfield ='movement';" placeholder="" style="min-height:50px;">
									Movement : 
									<input class="form-control-sm" name="movement" value="<?php echo $movement;?>" id="" type="text" size='5' onfocus="nextfield ='tekanan_darah';" placeholder="" style="min-height:50px;">
								</div>
								<br>
								<div class="col-12">
									Vital Sign
								</div>
								<div class="col-12">
									Tensi : 
									<input class="form-control-sm" name="tekanan_darah" value="<?php echo $tekanan_darah;?>" id="" type="text" size='5' onfocus="nextfield ='nadi';" placeholder="" style="min-width:50px; min-height:50px;">mmHg
								</div> 
								<br><br>
								<div class="col-12">
									Nadi : 
									<input class="form-control-sm" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='5' onfocus="nextfield ='suhu';" placeholder="" style="min-width:50px; min-height:50px;">x/menit 
									<input class="" name="ket_nadi" value="teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" <?php if ($ket_nadi=="teratur"){echo "checked";}?>>Teratur
									<input class="" name="ket_nadi" value="tidak teratur" id="" type="radio" size='' onfocus="nextfield ='';" placeholder="" <?php if ($ket_nadi=="tidak teratur"){echo "checked";}?>>Tidak Teratur
								</div>
								<br><br>
								<div class="col-12">
									Suhu : 
									<input class="form-control-sm" name="suhu" value="<?php echo $suhu;?>" id="" type="text" size='5' onfocus="nextfield ='frekuansi_pernafasan';" placeholder="" style="min-width:50px; min-height:50px;">C
									-
									Frekuensi Pernafasan : 
									<input class="form-control-sm" name="frekuansi_pernafasan" value="<?php echo $frekuansi_pernafasan;?>" id="" type="text" size='5' onfocus="nextfield ='skala_nyeri';" placeholder="" style="min-width:50px; min-height:50px;">x/menit
									-
									Skala Nyeri : 
									<input class="form-control-sm" name="skala_nyeri" value="<?php echo $skala_nyeri;?>" id="" type="text" size='3' onfocus="nextfield ='berat_badan';" placeholder="" style="min-width:50px; min-height:50px;">						
								</div>
								<br><br>
								<div class="col-12">
									Berat : 
									<input class="form-control-sm" name="berat_badan" value="<?php echo $berat_badan;?>" id="" type="text" size='5' onfocus="nextfield ='status_lokalis';" placeholder="" style="min-width:50px; min-height:50px;">Kg	
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-12">
								Laborat :
								<button type="submit" name="ambil_lab" class="btn btn-info btn-sm" onfocus="nextfield ='done';">Ambil data hasil Laborat</button> 
								<br>
								<textarea name= "plab" id="" style="min-width:650px; min-height:150px;"><?php echo $plab;?></textarea>
								<br>
								Radiologi :
								<button type="submit" name="ambil_rad" class="btn btn-info btn-sm" onfocus="nextfield ='done';">Ambil data hasil Radiologi</button> 
								<br>
								<textarea name= "prad" id="" style="min-width:650px; min-height:150px;"><?php echo $prad;?></textarea>

							</div>
						</div>



					</div>
				</div>

				<div class="col-12 align-self-center">
					<center>
						<br>
						<br>
						<button type='submit' name='simpan' value='simpan' class="btn btn-success" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
					</center>
				</div>


<!-- 				<div class="row">
					<div class="col-6">Penunjang<br>
						<textarea name= "penunjang" id="" style="min-width:650px; min-height:50px;"><?php echo $penunjang;?></textarea>
					</div>
				</div>
			-->				

		</div>
	</div>
</div>				
</div>

<br><br><br>
</form>
</font>
</body>
</div>

<?php



if (isset($_POST["simpan"])) {

	// $subjektif','$objektif','$assesment','$planning'

	$subjektif	= trim($_POST["subjektif"]);
	$objektif	= trim($_POST["objektif"]);
	$assesment	= trim($_POST["assesment"]);
	$planning	= trim($_POST["planning"]);


	$instruksi	= trim($_POST["instruksi"]);
	$plab	= trim($_POST["plab"]);
	$prad	= trim($_POST["prad"]);

	$penunjang	= 
	"Laborat : ".$plab.", ".
	"Radiologi : ".$prad;

	$assesmen	= trim($_POST["assesmen"]);

	$eye	= trim($_POST["eye"]);
	$verbal	= trim($_POST["verbal"]);
	$movement	= trim($_POST["movement"]);
	$tekanan_darah	= trim($_POST["tekanan_darah"]);
	$nadi	= trim($_POST["nadi"]);
	$ket_nadi	= trim($_POST["ket_nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$frekuansi_pernafasan	= trim($_POST["frekuansi_pernafasan"]);
	$skala_nyeri	= trim($_POST["skala_nyeri"]);
	$berat_badan	= trim($_POST["berat_badan"]);

	$objektif=$objektif.
	" GCS - Eye : ".$eye.", Verbal : ".$verbal.", Movement : ".$movement.",".
	"Tensi : ".$tekanan_darah.", Nadi : ".$nadi.", Suhu : ".$suhu.", Frekuensi Pernafasan : ".$frekuansi_pernafasan.", Skala Nyeri : ".$skala_nyeri.", Berat Badan : ".$berat_badan.", Penunjang : ".$penunjang;


	$subjektif = str_replace("'","`",$subjektif);
	$assesment = str_replace("'","`",$assesment);
	$planning = str_replace("'","`",$planning);
	$objektif = str_replace("'","`",$objektif);

	$userinput	= trim($_POST["userinput"]);

	$lanjut="Y";

	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);
	$kodedokter  = substr($user,0,3);
	$pass = trim($_POST["pass"]);
	$instruksi = trim($_POST["instruksi"]);

	$lanjut="Y";

	if($lanjut == 'Y'){
		$q  = "insert into ERM_SOAP
		(norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry,instruksi,dpjp) 
		values 
		('$norm','$noreg','$tglinput','$sbu2','$kodeunit','$kodedokter','$subjektif','$objektif','$assesment','$planning','$user','$tglinput','$instruksi','$dpjp')";
		$hs = sqlsrv_query($conn,$q);

		$qu="SELECT top(1)id FROM ERM_SOAP where noreg='$noreg' order by id desc";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$idsoap = $d1u['id'];

		if($hs){
			$q  = "insert into ERM_RI_SOAP
			(
			norm,noreg,
			tglupdate,
			userid,
			ku,
			rps,
			anamnesa,
			rpd,
			alergi,
			assesmen,
			aplan,
			eye,
			verbal,
			movement,
			tekanan_darah,
			nadi,
			suhu,
			frekuansi_pernafasan,
			skala_nyeri,
			berat_badan,
			fisik_kepala,
			fisik_mata,
			fisik_tht,
			fisik_leher,
			fisik_paru,
			fisik_jantung,
			fisik_abdomen,
			fisik_ekstermitas,
			fisik_urogenital,
			status_lokalis,
			pemeriksaan_penunjang,
			ket_nadi,id_soap, instruksi
			) 
			values 
			(
			'$norm','$noreg',
			'$tglinput',
			'$user',
			'$ku',
			'$rps',
			'$anamnesa',
			'$rpd',
			'$alergi',
			'$assesmen',
			'$aplan',
			'$eye',
			'$verbal',
			'$movement',
			'$tekanan_darah',
			'$nadi',
			'$suhu',
			'$frekuansi_pernafasan',
			'$skala_nyeri',
			'$berat_badan',
			'$fisik_kepala',
			'$fisik_mata',
			'$fisik_tht',
			'$fisik_leher',
			'$fisik_paru',
			'$fisik_jantung',
			'$fisik_abdomen',
			'$fisik_ekstermitas',
			'$fisik_urogenital',
			'$status_lokalis',
			'$penunjang',
			'$ket_nadi','$idsoap','$instruksi'
		)";
		$hs = sqlsrv_query($conn,$q);
	}

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert - Hubungi IT";

	}

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";

		// echo "
		// <script>
		// top.location='index.php?id='$id|$user';
		// </script>
		// ";            

}else{
	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";

}

}



?>

