<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);
$tglentry		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idtransfer = $row[2];

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);
$noregi = $noreg;
$noreg = substr($noreg, 1,12);

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);
$sbu = $KET1;

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
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];


$qe="
SELECT        TOP (100) ID, NOREG, NORM, 
CONVERT(VARCHAR, TGLENTRY, 25) as TGLENTRY,
USERID, IDHEADER, DARIUNIT, KEUNIT, KODEDOKTER1, DOKTER1, KODEDOKTER2, DOKTER2, KODEDOKTER3, DOKTER3, KODEDOKTER4, DOKTER4, ALASANPINDAH, O2, 
KETO2, DERAJATNYERI, LOKASINYERI, DIET, KETDIET, IV, CVC, AKSESDIALISIS, AKSESLAINNYA, KETAKSESLAINNYA, NGT, URINECONTINENT, URINEINCONTINENT, URINEFOLEYCATH, JAMJUMLAHURINE, DRAIN, KETDRAIN, 
BOWELSCONTINENT, BOWELSINCONTINENT, BOWELSCOLOSTOMY, TOTALINPUT, TOTALOUTPUT, TIDAKDILAKUKAN, MOBILISASI, RJCONTINENT, RJRESTRAIN, RJLOKASILUKA, KETLOKASILUKA, PERALATANKHUSUS, 
KONDISIPASIEN, DOKTERPENDAMPING, PERAWATKLINIS1, PERAWATKLINIS2, PERAWATKLINIS3, PERAWATKLINIS4, OBAT, KETOBAT, LAB, KETLAB, ECG, KETECG, XRAY, KETXRAY, LAINNYA, KETLAINNYA, BARANGPASIEN, 
TTDPX, NAMATTDPX, PERAWATPENERIMA, URLTTD
FROM            ERM_TRANSFER_PASIEN
where NOREG = '$noreg'
";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$tglinput = $de['TGLENTRY'];
$tr1='IGD';
$tr2=$de['KEUNIT'];
$tr3=$de['DOKTER1'];
$tr4=$de['DOKTER2'];
$tr5=$de['DOKTER3'];
$idigd = $de['ID'];


	//diagnosa
$qDx = "SELECT *
FROM ERM_IGD_DIAGNOSA WHERE (NOREG = '$de[NOREG]') order by id desc";
$stmt = sqlsrv_query($conn, $qDx, array(), array("Scrollable" => "buffered"));
$nod=1;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$kodeDx = $row["NAMADIAGNOSA"];
	if($nod==1){
		$tr6=$kodeDx;
	}
	if($nod==2){
		$tr7=$kodeDx;
	}
	if($nod==3){
		$tr8=$kodeDx;
	}
	$nod +=1;
}

$tr9=$de['ALASANPINDAH'];

		//riwayat penyakit
$qriwayat = "SELECT a.ID AS IDHDR, a.IDHEADER, a.JAM, a.AIRWAY, a.E, a.V, a.M, a.N, a.SISTOLE, a.DIASTOLE, a.T, a.PERNAFASAN, a.RITME, a.SPO, a.RENCANA, a.TGLENTRY, a.USERID, a.INDIKASI_PREVENTIF, a.INDIKASI_KURATIF, a.INDIKASI_REHABILITATIF, a.INDIKASI_PALIATIF, b.ID AS IDDETAIL, b.IDHEADER AS Expr1, b.PASIENLANSIA, b.GANGGUANGERAK, b.RAWATBERKELANJUTAN, b.PERLUBANTUAN, b.KRITERIA, b.KONDISIPULANG, b.JAMMENINGGAL, b.JAMPULANG, b.EDUKASIMAKAN, b.EDUKASIJAGALUKA, b.EDUKASIDIET, b.EDUKASILAINLAIN, b.TRANSPORTASIPULANG, b.INSTRUKSIKEPERAWATAN, b.INSTRUKSIMEDIK, b.RENCANARAWAT, b.RUANGRAWATINAP, b.TINDAKANLANJUT, b.RSRUJUKAN, b.ALASANDIRUJUK, b.TGLENTRY AS Expr2, b.USERID AS USERDOKTER, c.kodeunit AS ASALUNIT, unit.NAMAUNIT, ROLE.NamaUser AS NAMADOKTER, RIW.riwayatdulu AS RIWAYATDULU FROM ERM_IGD_RENCANA_HDR AS a INNER JOIN ERM_ASSESMEN_HEADER AS c ON c.id = a.IDHEADER INNER JOIN ERM_IGD_RENCANA_DETAIL AS b ON c.id = b.IDHEADER LEFT OUTER JOIN ROLERSPGENTRY.dbo.TBLuserERM AS ROLE ON b.USERID = ROLE.user1 LEFT OUTER JOIN ERM_RIWAYAT AS RIW ON c.noreg = RIW.NOREG LEFT OUTER JOIN Afarm_Unitlayanan AS unit ON c.kodeunit = unit.KODEUNIT WHERE (c.noreg = '$de[NOREG]') ORDER BY IDDETAIL DESC";
$qRiwayat = sqlsrv_query($conn, $qriwayat);
$dRiwayat = sqlsrv_fetch_array($qRiwayat, SQLSRV_FETCH_ASSOC);
$tr10 = $dRiwayat['RIWAYATDULU'];

		//obat rutin
$qObat = "SELECT A.id, A.idheader, A.riwayatobat, A.riwayatdosisobat, A.riwayatlamaobat, A.tglentry
FROM ERM_RIWAYAT_PENGOBATAN AS A INNER JOIN ERM_ASSESMEN_HEADER AS B ON A.idheader = B.id WHERE (B.norm = '$de[NORM]')
ORDER BY A.id DESC";
$dObat = sqlsrv_query($conn, $qObat, array(), array("Scrollable" => "buffered"));
$tr11='';
while ($rowObat = sqlsrv_fetch_array($dObat, SQLSRV_FETCH_ASSOC)) {
	$tr11 = $tr11. $rowObat["riwayatobat"].' '.$rowObat["riwayatdosisobat"].' ,';
}

		//alergi
$qAlergi = "SELECT KETERANGANALERGIOBAT, KETERANGANALERGIMAKANAN ,  keteranganalergiudara, keteranganalergilain from ERM_RIWAYAT WHERE NOREG='$de[NOREG]'";
$hAlergi = sqlsrv_query($conn, $qAlergi);
$dAlergi = sqlsrv_fetch_array($hAlergi, SQLSRV_FETCH_ASSOC);

$tr12 = 
"Alergi Obat : ".$dAlergi['KETERANGANALERGIOBAT'].
" , Alergi Makanan : ".$dAlergi['KETERANGANALERGIMAKANAN'].
" , Alergi Udara : ".$dAlergi['keteranganalergiudara'] .
" , Alergi Lain-lain : ".$dAlergi['keteranganalergilain'];


		//observasi terakhir..
$qPeriksaUmum = "SELECT a.KESADARAN, b.ID, b.IDHEADER, b.JAM, b.AIRWAY, b.E, b.V, b.M, b.N, b.SISTOLE, b.T, b.PERNAFASAN, b.SPO, b.RENCANA, b.USERID, b.INDIKASI_PREVENTIF, b.INDIKASI_KURATIF, b.INDIKASI_REHABILITATIF, b.INDIKASI_PALIATIF, b.DIASTOLE, b.RITME FROM ERM_PERIKSA_UMUM AS a LEFT OUTER JOIN
ERM_IGD_RENCANA_HDR AS b ON b.IDHEADER = a.IDHEADER WHERE (a.NOREG = '$de[NOREG]')";
$hPeriksaUmum = sqlsrv_query($conn, $qPeriksaUmum);
$dPeriksaUmum = sqlsrv_fetch_array($hPeriksaUmum, SQLSRV_FETCH_ASSOC);

		// $tr13= $dPeriksaUmum['TGLENTRY'];
$tr13= $dPeriksaUmum['KESADARAN'];
$tr20= $dPeriksaUmum['T'];
$tr14= $dPeriksaUmum['E'];
$tr15= $dPeriksaUmum['V'];
$tr16= $dPeriksaUmum['M'];
$tr17=$dPeriksaUmum['SISTOLE'].'/'.$dPeriksaUmum['DIASTOLE'];
$tr18=$dPeriksaUmum['N'];
$tr19=$dPeriksaUmum['RITME'];

$tr24=$de['DERAJATNYERI'];
$tr25=$de['LOKASINYERI'];
$tr26=$de['DIET'].' - '.$de['KETDIET'];

		//pemberian obat

$qPemberianObat = "SELECT ID, IDHEADER, NAMAOBAT, DOSISOBAT, RUTEOBAT, DIPERIKSA, DIBERIKAN, TGLENTRY, USERID, JAMPEMBERIANOBAT, DIKOREKSI, TGLKOREKSI, MINTAAPPROVE, USERAPPROVE, PERAWATBIDAN, GETDATE() AS HARIINI, CASE WHEN CONVERT(varchar, TGLENTRY, 101) = CONVERT(varchar, getdate(), 101) THEN 0 ELSE 1 END AS STATUSEDIT, RATE FROM ERM_IGD_OBAT_INFUS WHERE (IDHEADER = '$de[IDHEADER]') AND PERAWATBIDAN='Perawat' ORDER BY DIKOREKSI, TGLENTRY DESC ";

$dPemberianObat = sqlsrv_query($conn, $qPemberianObat, array(), array("Scrollable" => "buffered"));

$qTotalObat = "SELECT COUNT(IDHEADER) AS TOTAL FROM ERM_IGD_OBAT_INFUS WHERE (IDHEADER = '$id') AND PERAWATBIDAN='Perawat'";
$hTotalObat = sqlsrv_query($con, $qTotalObat);
$dTotalObat = sqlsrv_fetch_array($hTotalObat, SQLSRV_FETCH_ASSOC);
$var1 = (string) $dTotalObat;

$noc = 1;

while ($lPemberianObat = sqlsrv_fetch_array($dPemberianObat, SQLSRV_FETCH_ASSOC)) {
	if($noc==1){
		$tr27 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr28 = $lPemberianObat['NAMAOBAT'];
		$tr29 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==2){
		$tr30 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr31 = $lPemberianObat['NAMAOBAT'];
		$tr32 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==3){
		$tr33 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr34 = $lPemberianObat['NAMAOBAT'];
		$tr35 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==4){
		$tr36 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr37 = $lPemberianObat['NAMAOBAT'];
		$tr38 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==5){
		$tr39 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr40 = $lPemberianObat['NAMAOBAT'];
		$tr41 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==6){
		$tr42 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr43 = $lPemberianObat['NAMAOBAT'];
		$tr44 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==7){
		$tr45 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr46 = $lPemberianObat['NAMAOBAT'];
		$tr47 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==8){
		$tr48 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr49 = $lPemberianObat['NAMAOBAT'];
		$tr50 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==9){
		$tr51 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr52 = $lPemberianObat['NAMAOBAT'];
		$tr53 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}
	if($noc==10){
		$tr51 = $lPemberianObat['JAMPEMBERIANOBAT'];
		$tr52 = $lPemberianObat['NAMAOBAT'];
		$tr53 = $lPemberianObat['DOSISOBAT'].' / '.$lPemberianObat['RATE'];				
	}

	$noc += 1;
}



$qAkses = "SELECT  IV, CVC, AKSESDIALISIS, AKSESLAINNYA, KETAKSESLAINNYA, NGT, 
URINECONTINENT, URINEINCONTINENT, URINEFOLEYCATH, JAMJUMLAHURINE,
DRAIN, KETDRAIN,
BOWELSCONTINENT, BOWELSINCONTINENT, BOWELSCOLOSTOMY,
TOTALINPUT, TOTALOUTPUT, TIDAKDILAKUKAN,
MOBILISASI, RJCONTINENT, RJRESTRAIN, RJLOKASILUKA, KETLOKASILUKA, PERALATANKHUSUS, KONDISIPASIEN,
DOKTERPENDAMPING, PERAWATKLINIS1, PERAWATKLINIS2, PERAWATKLINIS3, PERAWATKLINIS4,
OBAT, KETOBAT, LAB, KETLAB, ECG, KETECG, XRAY, KETXRAY, LAINNYA, KETLAINNYA, BARANGPASIEN
FROM ERM_TRANSFER_PASIEN WHERE NOREG='$de[NOREG]'";
$hAkses = sqlsrv_query($conn, $qAkses);
$dAkses = sqlsrv_fetch_array($hAkses, SQLSRV_FETCH_ASSOC);

if($dAkses['IV']){
	$tr57="YA";
}
if($dAkses['CVC']){
	$tr58="YA";
}
if($dAkses['AKSESDIALISIS']){
	$tr59="YA";
}
if($dAkses['AKSESLAINNYA']){
	$tr60="YA";
			// $tr63=$dAkses['KETAKSESLAINNYA'];
}
if($dAkses['NGT']){
	$tr61="YA";
}

if($dAkses['URINECONTINENT']){
	$tr63="YA";
}
if($dAkses['URINEINCONTINENT']){
	$tr64="YA";
}
if($dAkses['URINEFOLEYCATH']){
	$tr65="YA";
}
if($dAkses['JAMJUMLAHURINE']){
	$tr66=$dAkses['JAMJUMLAHURINE'];
}
if($dAkses['DRAIN']){
	$tr67="YA";
}
if($dAkses['KETDRAIN']){
	$tr68=$dAkses['KETDRAIN'];
}
if($dAkses['BOWELSCONTINENT']){
	$tr69="YA";
}
if($dAkses['BOWELSINCONTINENT']){
	$tr70="YA";
}
if($dAkses['BOWELSCOLOSTOMY']){
	$tr71="YA";
}
if($dAkses['TOTALINPUT']){
	$tr72=$dAkses['TOTALINPUT'];
}
if($dAkses['TOTALOUTPUT']){
	$tr73=$dAkses['TOTALOUTPUT'];
}
if($dAkses['TIDAKDILAKUKAN']){
	$tr74="YA";
}

if($dAkses['MOBILISASI']=='Jalan'){
	$tr75="YA";
}
if($dAkses['MOBILISASI']=='Tirah Baring'){
	$tr76="YA";
}
if($dAkses['MOBILISASI']=='Duduk'){
	$tr77="YA";
}
if($dAkses['RJCONTINENT']){
	$tr78="YA";
}
if($dAkses['RJRESTRAIN']){
	$tr79="YA";
}
if($dAkses['RJLOKASILUKA']){
			// $tr80="YA";
	$tr80=$dAkses['KETLOKASILUKA'];
}
if($dAkses['PERALATANKHUSUS']){
	$tr81=$dAkses['PERALATANKHUSUS'];
}
if($dAkses['KONDISIPASIEN']){
	$tr82=$dAkses['KONDISIPASIEN'];
}

		//diagnosis keperawatan,
$qDx = "SELECT ID, IDHEADER, TGLENTRY, USERID, NORM, NOREG, KODEDIAGNOSA, NAMADIAGNOSA, TGLKOREKSI, USERKOREKSI, KODEUNIK, STATUSTERATASI FROM ERM_IGD_TRANS_DX_KEPERAWATAN WHERE        (IDHEADER = '$de[IDHEADER]')";
$hDx = sqlsrv_query($conn, $qDx);
$nodk=1;
while ($dDx = sqlsrv_fetch_array($hDx, SQLSRV_FETCH_ASSOC)) {
	if($nodk==1){
		$tr85 = $dDx['KODEDIAGNOSA'].' - '.$dDx['NAMADIAGNOSA'];
		if ($dDx['STATUSTERATASI']){
			$tr83='YA';					
		}else{
			$tr84='YA';										
		}
	}
	if($nodk==2){
		$tr110= $dDx['KODEDIAGNOSA'].' - '.$dDx['NAMADIAGNOSA'];
		if ($dDx['STATUSTERATASI']){
			$tr111='YA';	
		}else{
			$tr111='TIDAK';
		}
	}

	if($nodk==3){
		$tr103= $dDx['KODEDIAGNOSA'].' - '.$dDx['NAMADIAGNOSA'];
		if ($dDx['STATUSTERATASI']){
			$tr104='YA';	
		}else{
			$tr104='TIDAK';
		}
	}

	if($nodk==4){
		$tr105= $dDx['KODEDIAGNOSA'].' - '.$dDx['NAMADIAGNOSA'];
		if ($dDx['STATUSTERATASI']){
			$tr106='YA';	
		}else{
			$tr106='TIDAK';
		}
	}

	if($nodk==5){
		$tr107= $dDx['KODEDIAGNOSA'].' - '.$dDx['NAMADIAGNOSA'];
		if ($dDx['STATUSTERATASI']){
			$tr108='YA';	
		}else{
			$tr108='TIDAK';
		}
	}

	$nodk+=1;
}

		// DOKTERPENDAMPING, PERAWATKLINIS1, , , 
if ($dAkses['DOKTERPENDAMPING']){
	$tr109='YA';
}
if ($dAkses['PERAWATKLINIS1']){
	$tr112='YA';
}
if ($dAkses['PERAWATKLINIS2']){
	$tr113='YA';
}
if ($dAkses['PERAWATKLINIS3']){
	$tr114='YA';
}
if ($dAkses['PERAWATKLINIS4']){
	$tr115='YA';
}

		//konsultasi
$qAdvis = "SELECT a.ID, a.IDHEADER, a.KODEDOKTER, b.NAMA AS NAMADOKTER, a.KONSULVIA, a.KONSULVIALAIN, a.TGLENTRY, a.USERID, a.ADVIS FROM ERM_IGD_ADVIS AS a LEFT OUTER JOIN Afarm_DOKTER AS b ON a.KODEDOKTER = b.KODEDOKTER WHERE A.IDHEADER='$de[IDHEADER]'";
$hAdvis = sqlsrv_query($conn, $qAdvis);
                // $dAdvis = sqlsrv_fetch_array($hAdvis, SQLSRV_FETCH_ASSOC);
$tr86='';
while ($dAdvis = sqlsrv_fetch_array($hAdvis, SQLSRV_FETCH_ASSOC)) {
	$tr86 = $tr86.$dAdvis['NAMADOKTER'].' - '.$dAdvis['KONSULVIA'].' - '.$dAdvis['ADVIS']."<br>";

}

		//OBAT, KETOBAT, LAB, KETLAB, ECG, KETECG, XRAY, KETXRAY, LAINNYA, KETLAINNYA, BARANGPASIEN
if ($dAkses['OBAT']){
	$tr88='Ya';
	$tr89=$dAkses['KETOBAT'];
}
if ($dAkses['LAB']){
	$tr90='Ya';
	$tr91=$dAkses['KETLAB'];
}
if ($dAkses['ECG']){
	$tr92='Ya';
	$tr93=$dAkses['KETECG'];
}
if ($dAkses['XRAY']){
	$tr94='Ya';
	$tr95=$dAkses['KETXRAY'];
}
if ($dAkses['LAINNYA']){
	$tr96='Ya';
	$tr97=$dAkses['KETLAINNYA'];
}
if ($dAkses['BARANGPASIEN']){
	$tr98=$dAkses['BARANGPASIEN'];
}

$qCekTtd = "SELECT NAMATTDPX, URLTTD,idheader FROM ERM_TRANSFER_PASIEN WHERE NOREG='$de[NOREG]'";
$hCekTtd = sqlsrv_query($conn, $qCekTtd);
$dCekTtd = sqlsrv_fetch_array($hCekTtd, SQLSRV_FETCH_ASSOC);
$namaPx = $dCekTtd['NAMATTDPX'];
$idheader = $dCekTtd['idheader'];

$tr99 = $namaPx;

$qrDpjp = "SELECT a.*, ROLE.NamaUser FROM ERM_IGD_RENCANA_HDR a
LEFT OUTER JOIN ROLERSPGENTRY.dbo.tblusererm AS ROLE ON a.userid = ROLE.user1 
WHERE a.IDHEADER = '$de[IDHEADER]'";
$hqrDpjp = sqlsrv_query($conn, $qrDpjp);
$dqrDpjp = sqlsrv_fetch_array($hqrDpjp, SQLSRV_FETCH_ASSOC);
$namaDokter = "Dpjp : " . $dqrDpjp['NamaUser'];

$tr100 = $namaDokter;

$qrPerawatPemindah = "SELECT ROLE.NamaUser FROM ERM_TRANSFER_PASIEN a
LEFT OUTER JOIN ROLERSPGENTRY.dbo.tblusererm AS ROLE ON a.userid = ROLE.user1 
WHERE a.IDHEADER = '$idheader'";
$hqrPerawatPemindah = sqlsrv_query($conn, $qrPerawatPemindah);
$dqrPerawatPemindah = sqlsrv_fetch_array($hqrPerawatPemindah, SQLSRV_FETCH_ASSOC);
$namaPerawatPemindah = "Perawat Pemindah : " . $dqrPerawatPemindah['NamaUser'];

$tr101 = $namaPerawatPemindah;

$qrPerawatPenerima = "SELECT a.*, ROLE.NamaUser FROM ERM_TRANSFER_PASIEN a
LEFT OUTER JOIN ROLERSPGENTRY.dbo.tblusererm AS ROLE ON a.userid = ROLE.user1 
WHERE a.IDHEADER = '$idheader'";
$hqrPerawatPenerima = sqlsrv_query($conn, $qrPerawatPenerima);
$dqrPerawatPenerima = sqlsrv_fetch_array($hqrPerawatPenerima, SQLSRV_FETCH_ASSOC);
$namaPerawatPenerima = "Perawat Penerima : " . $dqrPerawatPenerima['PERAWATPENERIMA'];


$tr102 = $namaPerawatPenerima;


//keluhan utama
$qku = "SELECT keluhanutama,riwayatsekarang from ERM_KELUHAN WHERE idheader = '$de[IDHEADER]'";
$hqku = sqlsrv_query($conn, $qku);
$dhqku = sqlsrv_fetch_array($hqku, SQLSRV_FETCH_ASSOC);
$ku = $dhqku['keluhanutama'];
$riwayatsekarang = $dhqku['riwayatsekarang'];

//advis
$qku = "SELECT ADVIS from ERM_IGD_ADVIS WHERE noreg = '$de[IDHEADER]'";
$hqku = sqlsrv_query($conn, $qku);
$dhqku = sqlsrv_fetch_array($hqku, SQLSRV_FETCH_ASSOC);
$ku = $dhqku['keluhanutama'];
$riwayatsekarang = $dhqku['riwayatsekarang'];

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Transfer Pasien</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

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
			$("#dokter3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter1.php', //your                         
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
			$("#dokter4").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter1.php', //your                         
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
			$("#dokter5").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter1.php', //your                         
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
			$("#dokter2").autocomplete({
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
			$("#diag_keperawatan1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan3").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan4").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#diag_keperawatan5").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#keunit").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_unit.php?id=<?php echo $sbu; ?>', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.namaunit + ' - ' + item.kodeunit
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
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
					&nbsp;&nbsp;
					<?php 

					if(empty($tr102)){
						echo "
						<button type='submit' name='terima' value='terima' class='btn btn-info' type='button'><i class='bi bi-printer-fill'></i> Terima</button>
						";
					}
					?>
					<br><br>
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
							<b>TRANSFER PASIEN ANTAR UNIT PELAYANAN</b><br>
						</div>
					</div>

					<br>

					<table width='100%' border='0'>
						
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Tanggal
									</div>
									<div class="col-8">
										<!-- <input type='date' name='tgl' value='<?php echo $tgl;?>'> -->
										: <?php echo $tglinput;?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Dari Unit
									</div>
									<div class="col-8">
										: <?php echo $tr1;?> - Ke Unit : <?php echo $tr2;?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Nama Dokter
									</div>
									<div class="col-8">
										: <?php echo $tr3;?>
										<?php echo $tr4;?>
										<?php echo $tr5;?>
									</div>
								</div>
							</td>
						</tr>	
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Diagnosis Medik
									</div>
									<div class="col-8">
										: <?php echo $tr6;?>
										<?php echo $tr7;?>
										<?php echo $tr8;?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										<b>Keluhan Pasien</b>
									</div>
									<div class="col-8">
										<!-- <input class="" name="tr10" value="<?php echo $tr10;?>" id="" type="text" size='90' placeholder=""> -->
										: <?php echo $ku;?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										<b>Riwayat Penyakit Sekarang</b>
									</div>
									<div class="col-8">
										<!-- <input class="" name="tr10" value="<?php echo $tr10;?>" id="" type="text" size='90' placeholder=""> -->
										: <?php echo $riwayatsekarang;?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										<b>Konsultasi</b>    
									</div>
									<div class="col-8">
										: <?php echo $tr86;?>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Alasan Pemindahan
									</div>
									<div class="col-8"> 
										: <?php echo $tr9;?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Riwayat Penyakit Dahulu
									</div>
									<div class="col-8">
										<!-- <input class="" name="tr10" value="<?php echo $tr10;?>" id="" type="text" size='90' placeholder=""> -->
										: <?php echo $tr10;?>
									</div>
								</div>
							</td>
						</tr>						
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Obat-obatan rutin
									</div>
									<div class="col-8">
										<!-- <input class="" name="tr11" value="<?php echo $tr11;?>" id="" type="text" size='90' placeholder=""> -->
										: <?php echo $tr11;?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Alergi
									</div>
									<div class="col-8">
										<!-- <input class="" name="tr12" value="<?php echo $tr12;?>" id="" type="text" size='90' placeholder=""> -->
										: <?php echo $tr12;?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Observasi terakhir
									</div>
									<div class="col-8">
										<table width="100%" border='0'>
											<tr>
												<td>Jam :</td>
												<td>
													<!-- <input class="" name="tglinput" value="<?php echo $tglinput;?>" type="text" > -->
													: <?php echo $tglinput;?>
												</td>
											</tr>
											<tr>
												<td>Kesadaran/ Orientasi</td>
												<td>
													: <?php echo $tr13;?>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
													Suhu
													<!-- <input class="" name="tr20" value="<?php echo $tr20;?>" id="" type="text" size='10' placeholder=""> 0C -->
													: <?php echo $tr20;?>
													<tr>
														<td>GCS</td>
														<td>
															E : <?php echo $tr14;?>
															V : <?php echo $tr15;?>
															M : <?php echo $tr16;?>
															Total : <?php echo $total;?>
														</td>
													</tr>
													<tr>
														<td>Tekanan Darah</td>
														<td>
															: <?php echo $tr17;?> mmHg
														</td>
													</tr>
													<tr>
														<td>Nadi</td>
														<td>
															<!-- <input class="" name="tr18" value="<?php echo $tr18;?>" id="" type="text" size='10' placeholder="">  -->
															: <?php echo $tr18;?> x/mnt
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															Ritme :
															<?php echo $tr19;?> 
														?>
													</td>
												</tr>	

												<tr>
													<td>Pernafasan</td>
													<td>
														: <?php echo $tr21;?>  x/mnt 
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														SpO2 :
														: <?php echo $tr22;?>  %
														O2 :
														: <?php echo $tr23;?>  ℓ/mnt
													</td>
												</tr>

												<tr>
													<td>Derajat nyeri (0-10)</td>
													<td>
														: <?php echo $tr24;?> 
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														Lokasi nyeri
														: <?php echo $tr25;?> 
													</td>
												</tr>

												<tr>
													<td>Diet</td>
													<td>
														: <?php echo $tr26;?> 

													</td>
												</tr>
											</table>
										</div>
									</div>
								</td>
							</tr>

							<tr>
								<td>
									<div class="row">
										<table width='100%' border="1">
											<tr valign="top">
												<td align='center'>Diberikan Sejak</td>
												<td align='center'>Cairan & Obat</td>
												<td align='center'>Dosis / Rate</td>
												<td align='center'>Askes</td>
											</tr>
											<tr>
												<td><?php echo $tr27;?></td>
												<td><?php echo $tr28;?></td>
												<td><?php echo $tr29;?></td>
												<td rowspan="10">
													<?php if ($tr57=="YA"){echo "&check; IV";}?>
													<?php if ($tr58=="YA"){echo "&check; CVC";}?><br>
													<?php if ($tr59=="YA"){echo "&check; Akses dialisis";}?><br>
													<?php if ($tr60=="YA"){echo "&check; Lainnya";}?>
													<?php if ($tr63=="YA"){echo "&check; URINECONTINENT";}?>
													<br>
													NGT
													<?php if ($tr61=="YA"){echo "&check; Ya";}?>
													<?php if ($tr62=="YA"){echo "&check; Tidak";}?>
												</td>
											</tr>
											<?php if($tr30){?>
												<tr>
													<td><?php echo $tr30;?></td>
													<td><?php echo $tr31;?></td>
													<td><?php echo $tr32;?></td>
												</tr>
											<?php } ?>
											<?php if($tr33){?>
												<tr>
													<td><?php echo $tr33;?></td>
													<td><?php echo $tr34;?></td>
													<td><?php echo $tr35;?></td>
												</tr>
											<?php } ?>
											<?php if($tr36){?>
												<tr>
													<td><?php echo $tr36;?></td>
													<td><?php echo $tr37;?></td>
													<td><?php echo $tr38;?></td>
												</tr>
											<?php } ?>
											<?php if($tr39){?>
												<tr>
													<td><?php echo $tr39;?></td>
													<td><?php echo $tr40;?></td>
													<td><?php echo $tr41;?></td>
												</tr>
											<?php } ?>
											<?php if($tr42){?>
												<tr>
													<td><?php echo $tr42;?></td>
													<td><?php echo $tr43;?></td>
													<td><?php echo $tr44;?></td>
												</tr>
											<?php } ?>
											<?php if($tr44){?>
												<tr>
													<td><?php echo $tr45;?></td>
													<td><?php echo $tr46;?></td>
													<td><?php echo $tr47;?></td>
												</tr>
											<?php } ?>
											<?php if($tr48){?>
												<tr>
													<td><?php echo $tr48;?></td>
													<td><?php echo $tr49;?></td>
													<td><?php echo $tr50;?></td>
												</tr>
											<?php } ?>
											<?php if($tr52){?>
												<tr>
													<td><?php echo $tr51;?></td>
													<td><?php echo $tr52;?></td>
													<td><?php echo $tr53;?></td>
												</tr>
											<?php } ?>
											<?php if($tr54){?>
												<tr>
													<td><?php echo $tr54;?></td>
													<td><?php echo $tr55;?></td>
													<td><?php echo $tr56;?></td>
												</tr>
											<?php } ?>
										</table>
									</div>
								</td>
							</tr>


							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Urine
										</div>
										<div class="col-8">
											<?php if ($tr63=="YA"){echo "&check; Continent";}?>
											<?php if ($tr64=="YA"){echo "&check; Incontinent";}?>
											<?php if ($tr65=="YA"){echo "&check; Foley Cath";}?>
											Jumlah/jam : <?php echo $tr66;?> ml 
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Drain
										</div>
										<div class="col-8">
											<?php if ($tr67=="YA"){echo "&check; Drain bag";}?>
											Lain-lain : <?php echo $tr68;?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Bowels 
										</div>
										<div class="col-8">
											<?php if ($tr69=="YA"){echo "&check; Continent";}?>
											<?php if ($tr70=="YA"){echo "&check; Incontinent";}?>
											<?php if ($tr71=="YA"){echo "&check; Colostomy bag";}?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Total Input 
										</div>
										<div class="col-8">
											: <?php echo $tr72;?>
											Total Output : <?php echo $tr73;?> 
											<?php if ($tr74=="YA"){echo "&check; Tidak dilakukan";}?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Mobilisasi 
										</div>
										<div class="col-8">       
											<?php if ($tr75=="YA"){echo "&check; Jalan";}?>
											<?php if ($tr76=="YA"){echo "&check; Tirah baring";}?>
											<?php if ($tr77=="YA"){echo "&check; Duduk";}?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Kebutuhan khusus pasien 
										</div>
										<div class="col-8">
											<?php if ($tr78=="YA"){echo "&check; Risiko jatuh";}?>
											<?php if ($tr79=="YA"){echo "&check; Restrain";}?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
										</div>
										<div class="col-8">
											Lokasi luka : <?php echo $tr80;?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Peralatan khusus yang dibutuhkan  
										</div>
										<div class="col-8">
											: <?php echo $tr81;?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Kondisi pasien yang perlu diperhatikan   
										</div>
										<div class="col-8">
											: <?php echo $tr82;?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="row">
										<div class="col-4">
											Diagnosis keperawatan   
										</div>
										<div class="col-8">
											: <?php echo $tr85;?>
											<?php if ($tr83=="YA"){echo "&check; Sudah Teratasi";}?>
											<?php if ($tr84=="YA"){echo "&check; Belum Teratasi";}?>
										</div>
									</div>
									<div class="row">
										<div class="col-4">
											&nbsp;
										</div>
										<div class="col-8">
											: <?php echo $tr110;?>
											<?php if ($tr111=="YA"){echo "&check; Sudah Teratasi";}?>
											<?php if ($tr111=="YA"){echo "&check; Belum Teratasi";}?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										&nbsp;
									</div>
									<div class="col-8">
										: <?php echo $tr103;?>
										<?php if ($tr104=="YA"){echo "&check; Sudah Teratasi";}?>
										<?php if ($tr104=="YA"){echo "&check; Belum Teratasi";}?>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-4">
									&nbsp;
								</div>
								<div class="col-8">
									: <?php echo $tr105;?>
									<?php if ($tr106=="YA"){echo "&check; Sudah Teratasi";}?>
									<?php if ($tr106=="YA"){echo "&check; Belum Teratasi";}?>
								</div>
							</div>
							<div class="row">
								<div class="col-4">
									&nbsp;
								</div>
								<div class="col-8">
									: <?php echo $tr107;?>
									<?php if ($tr108=="YA"){echo "&check; Sudah Teratasi";}?>
									<?php if ($tr108=="YA"){echo "&check; Belum Teratasi";}?>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Pendamping pasien transfer    
							</div>
							<div class="col-8">
								<?php if ($tr109=="YA"){echo "&check; Dokter";}?>
								- Perawat Klinis 
								<?php if ($tr115=="YA"){echo "&check; 1";}?>
								<?php if ($tr112=="YA"){echo "&check; 2";}?>
								<?php if ($tr113=="YA"){echo "&check; 3";}?>
								<?php if ($tr114=="YA"){echo "&check; 4";}?>
							</div>
						</div>
					</td>
				</tr>				
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Rencana Asuhan/tindakan, Rencana Terapi    
							</div>
							<div class="col-8">
								: <?php echo $tr87;?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Obat dan dokumen yang disertakan :    
							</div>
							<div class="col-8">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Obat    
							</div>
							<div class="col-8">
								<?php if ($tr88=="Tidak"){echo "&check; Tidak";}?>
								<?php if ($tr88=="Ya"){echo "&check; Ya";}?>
								: <?php echo $tr89;?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Lab    
							</div>
							<div class="col-8">
								<?php if ($tr90=="Tidak"){echo "&check; Tidak";}?>
								<?php if ($tr90=="Ya"){echo "&check; Ya";}?>
								: <?php echo $tr91;?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								X Ray    
							</div>
							<div class="col-8">
								<?php if ($tr92=="Tidak"){echo "&check; Tidak";}?>
								<?php if ($tr92=="Ya"){echo "&check; Ya";}?>
								: <?php echo $tr93;?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								ECG  
							</div>
							<div class="col-8">
								<?php if ($tr94=="Tidak"){echo "&check; Tidak";}?>
								<?php if ($tr94=="Ya"){echo "&check; Ya";}?>
								: <?php echo $tr95;?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Lainnya  
							</div>
							<div class="col-8">
								<?php if ($tr96=="Tidak"){echo "&check; Tidak";}?>
								<?php if ($tr96=="Ya"){echo "&check; Ya";}?>
								: <?php echo $tr97;?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								Barang kepunyaan pasien    
							</div>
							<div class="col-8">
								: <?php echo $tr98;?>
							</div>
						</div>
					</td>
				</tr>



				<tr>
					<td>
						<table width="100%" border='1'>
							<tr>
								<td align="center">
									Persetujuan Pasien/ keluarga
									<!-- <input class="" name="tr99" value="<?php echo $tr99;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Pasien/Keluarga"> -->
									<?php 
									if($tr99){
										$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh Keluarga Pasien atas nama:'.$tr99.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

										QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
										echo "<center><img src='image.png'></center>";

									// echo $tr99;												
									}
									?>
									<br>
									<?php echo $tr99;?>
								</td>
								<td align="center">
									Tanggung jawab DPJP / Dokter Jaga
									<!-- <input class="" name="tr100" value="<?php echo $tr100;?>" id="dokter" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Dokter atau Kode Dokter"> -->
									<?php 
									if($tr100){
										$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh DPJP / Dokter Jaga atas nama:'.$tr100.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

										QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
										echo "<center><img src='image.png'></center>";

									// echo $tr100;												
									}
									?>
									<?php echo $tr100;?> 
								</td>
							</tr>
							<tr>
								<td align="center">
									Perawat yang memindahkan,&nbsp;&nbsp;&nbsp;
									<!-- <input class="" name="tr101" value="<?php echo $tr101;?>" id="karyawan1" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Petugas"> -->
									<?php 
									if($tr101){
										$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh Perawat atas nama:'.$tr101.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

										QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
										echo "<center><img src='image.png'></center>";

									// echo $tr101;												
									}
									?>
									<?php echo $tr101;?> 
								</td>
								<td align="center">
									Perawat penerima pindahan,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<?php 
									if($tr102){
										$pernyataanpasien='Lembar Transfer Unit ini telah disetujui oleh Perawat atas nama:'.$tr102.'pada tanggal:'.$tglinput;
											// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

										QRcode::png($pernyataanpasien, "image.png", "L", 2, 2);   
										echo "<center><img src='image.png'></center>";

									// echo $tr102;												
									}
									?>
									<?php echo $tr102;?>
								</td>
							</tr>
						</table>
					</td>
				</tr>

			</table>

			<br>
			<br>
			<br>
			<br>
			<br>
		</form>
	</font>
</body>
</div>
</div>

<?php 


if (isset($_POST["simpan"])) {

	$tr1	= $_POST["tr1"];
	$tr2	= $_POST["tr2"];
	$tr3	= $_POST["tr3"];
	$tr4	= $_POST["tr4"];
	$tr5	= $_POST["tr5"];
	$tr6	= $_POST["tr6"];
	$tr7	= $_POST["tr7"];
	$tr8	= $_POST["tr8"];
	$tr9	= $_POST["tr9"];
	$tr10	= $_POST["tr10"];
	$tr11	= $_POST["tr11"];
	$tr12	= $_POST["tr12"];
	$tr13	= $_POST["tr13"];
	$tr14	= $_POST["tr14"];
	$tr15	= $_POST["tr15"];
	$tr16	= $_POST["tr16"];
	$tr17	= $_POST["tr17"];
	$tr18	= $_POST["tr18"];
	$tr19	= $_POST["tr19"];
	$tr20	= $_POST["tr20"];
	$tr21	= $_POST["tr21"];
	$tr22	= $_POST["tr22"];
	$tr23	= $_POST["tr23"];
	$tr24	= $_POST["tr24"];
	$tr25	= $_POST["tr25"];
	$tr26	= $_POST["tr26"];
	$tr27	= $_POST["tr27"];
	$tr28	= $_POST["tr28"];
	$tr29	= $_POST["tr29"];
	$tr30	= $_POST["tr30"];
	$tr31	= $_POST["tr31"];
	$tr32	= $_POST["tr32"];
	$tr33	= $_POST["tr33"];
	$tr34	= $_POST["tr34"];
	$tr35	= $_POST["tr35"];
	$tr36	= $_POST["tr36"];
	$tr37	= $_POST["tr37"];
	$tr38	= $_POST["tr38"];
	$tr39	= $_POST["tr39"];
	$tr40	= $_POST["tr40"];
	$tr41	= $_POST["tr41"];
	$tr42	= $_POST["tr42"];
	$tr43	= $_POST["tr43"];
	$tr44	= $_POST["tr44"];
	$tr45	= $_POST["tr45"];
	$tr46	= $_POST["tr46"];
	$tr47	= $_POST["tr47"];
	$tr48	= $_POST["tr48"];
	$tr49	= $_POST["tr49"];
	$tr50	= $_POST["tr50"];
	$tr51	= $_POST["tr51"];
	$tr52	= $_POST["tr52"];
	$tr53	= $_POST["tr53"];
	$tr54	= $_POST["tr54"];
	$tr55	= $_POST["tr55"];
	$tr56	= $_POST["tr56"];
	$tr57	= $_POST["tr57"];
	$tr58	= $_POST["tr58"];
	$tr59	= $_POST["tr59"];
	$tr60	= $_POST["tr60"];
	$tr61	= $_POST["tr61"];
	$tr62	= $_POST["tr62"];
	$tr63	= $_POST["tr63"];
	$tr64	= $_POST["tr64"];
	$tr65	= $_POST["tr65"];
	$tr66	= $_POST["tr66"];
	$tr67	= $_POST["tr67"];
	$tr68	= $_POST["tr68"];
	$tr69	= $_POST["tr69"];
	$tr70	= $_POST["tr70"];
	$tr71	= $_POST["tr71"];
	$tr72	= $_POST["tr72"];
	$tr73	= $_POST["tr73"];
	$tr74	= $_POST["tr74"];
	$tr75	= $_POST["tr75"];
	$tr76	= $_POST["tr76"];
	$tr77	= $_POST["tr77"];
	$tr78	= $_POST["tr78"];
	$tr79	= $_POST["tr79"];
	$tr80	= $_POST["tr80"];
	$tr81	= $_POST["tr81"];
	$tr82	= $_POST["tr82"];
	$tr83	= $_POST["tr83"];
	$tr84	= $_POST["tr84"];
	$tr85	= $_POST["tr85"];
	$tr86	= $_POST["tr86"];
	$tr87	= $_POST["tr87"];
	$tr88	= $_POST["tr88"];
	$tr89	= $_POST["tr89"];
	$tr90	= $_POST["tr90"];
	$tr91	= $_POST["tr91"];
	$tr92	= $_POST["tr92"];
	$tr93	= $_POST["tr93"];
	$tr94	= $_POST["tr94"];
	$tr95	= $_POST["tr95"];
	$tr96	= $_POST["tr96"];
	$tr97	= $_POST["tr97"];
	$tr98	= $_POST["tr98"];
	$tr99	= $_POST["tr99"];
	$tr100	= $_POST["tr100"];
	$tr101	= $_POST["tr101"];
	$tr102	= $_POST["tr102"];
	$tr103	= $_POST["tr103"];
	$tr104	= $_POST["tr104"];
	$tr105	= $_POST["tr105"];
	$tr106	= $_POST["tr106"];
	$tr107	= $_POST["tr107"];
	$tr108	= $_POST["tr108"];
	$tr109	= $_POST["tr109"];
	$tr110	= $_POST["tr110"];
	$tr111	= $_POST["tr111"];
	$tr112	= $_POST["tr112"];
	$tr113	= $_POST["tr113"];
	$tr114	= $_POST["tr114"];
	$tr115	= $_POST["tr115"];
	$tr116	= $_POST["tr116"];
	$tr117	= $_POST["tr117"];
	$tr118	= $_POST["tr118"];
	$tr119	= $_POST["tr119"];
	$tr120	= $_POST["tr120"];
	$tr121	= $_POST["tr121"];
	$tr122	= $_POST["tr122"];
	$tr123	= $_POST["tr123"];
	$tr124	= $_POST["tr124"];
	$tr125	= $_POST["tr125"];
	$tr126	= $_POST["tr126"];
	$tr127	= $_POST["tr127"];
	$tr128	= $_POST["tr128"];
	$tr129	= $_POST["tr129"];
	$tr130	= $_POST["tr130"];
	$tr131	= $_POST["tr131"];
	$tr132	= $_POST["tr132"];
	$tr133	= $_POST["tr133"];
	$tr134	= $_POST["tr134"];
	$tr135	= $_POST["tr135"];
	$tr136	= $_POST["tr136"];
	$tr137	= $_POST["tr137"];
	$tr138	= $_POST["tr138"];
	$tr139	= $_POST["tr139"];
	$tr140	= $_POST["tr140"];
	$tr141	= $_POST["tr141"];
	$tr142	= $_POST["tr142"];
	$tr143	= $_POST["tr143"];
	$tr144	= $_POST["tr144"];
	$tr145	= $_POST["tr145"];
	$tr146	= $_POST["tr146"];
	$tr147	= $_POST["tr147"];
	$tr148	= $_POST["tr148"];
	$tr149	= $_POST["tr149"];
	$tr150	= $_POST["tr150"];


	echo	$q  = "update ERM_RI_TRANSFER set
	userid = '$user',tglentry='$tglentry',
	tr1	='$tr1',
	tr2	='$tr2',
	tr3	='$tr3',
	tr4	='$tr4',
	tr5	='$tr5',
	tr6	='$tr6',
	tr7	='$tr7',
	tr8	='$tr8',
	tr9	='$tr9',
	tr10	='$tr10',
	tr11	='$tr11',
	tr12	='$tr12',
	tr13	='$tr13',
	tr14	='$tr14',
	tr15	='$tr15',
	tr16	='$tr16',
	tr17	='$tr17',
	tr18	='$tr18',
	tr19	='$tr19',
	tr20	='$tr20',
	tr21	='$tr21',
	tr22	='$tr22',
	tr23	='$tr23',
	tr24	='$tr24',
	tr25	='$tr25',
	tr26	='$tr26',
	tr27	='$tr27',
	tr28	='$tr28',
	tr29	='$tr29',
	tr30	='$tr30',
	tr31	='$tr31',
	tr32	='$tr32',
	tr33	='$tr33',
	tr34	='$tr34',
	tr35	='$tr35',
	tr36	='$tr36',
	tr37	='$tr37',
	tr38	='$tr38',
	tr39	='$tr39',
	tr40	='$tr40',
	tr41	='$tr41',
	tr42	='$tr42',
	tr43	='$tr43',
	tr44	='$tr44',
	tr45	='$tr45',
	tr46	='$tr46',
	tr47	='$tr47',
	tr48	='$tr48',
	tr49	='$tr49',
	tr50	='$tr50',
	tr51	='$tr51',
	tr52	='$tr52',
	tr53	='$tr53',
	tr54	='$tr54',
	tr55	='$tr55',
	tr56	='$tr56',
	tr57	='$tr57',
	tr58	='$tr58',
	tr59	='$tr59',
	tr60	='$tr60',
	tr61	='$tr61',
	tr62	='$tr62',
	tr63	='$tr63',
	tr64	='$tr64',
	tr65	='$tr65',
	tr66	='$tr66',
	tr67	='$tr67',
	tr68	='$tr68',
	tr69	='$tr69',
	tr70	='$tr70',
	tr71	='$tr71',
	tr72	='$tr72',
	tr73	='$tr73',
	tr74	='$tr74',
	tr75	='$tr75',
	tr76	='$tr76',
	tr77	='$tr77',
	tr78	='$tr78',
	tr79	='$tr79',
	tr80	='$tr80',
	tr81	='$tr81',
	tr82	='$tr82',
	tr83	='$tr83',
	tr84	='$tr84',
	tr85	='$tr85',
	tr86	='$tr86',
	tr87	='$tr87',
	tr88	='$tr88',
	tr89	='$tr89',
	tr90	='$tr90',
	tr91	='$tr91',
	tr92	='$tr92',
	tr93	='$tr93',
	tr94	='$tr94',
	tr95	='$tr95',
	tr96	='$tr96',
	tr97	='$tr97',
	tr98	='$tr98',
	tr99	='$tr99',
	tr100	='$tr100',
	tr101	='$tr101',
	tr102	='$tr102',
	tr103	='$tr103',
	tr104	='$tr104',
	tr105	='$tr105',
	tr106	='$tr106',
	tr107	='$tr107',
	tr108	='$tr108',
	tr109	='$tr109',
	tr110	='$tr110',
	tr111	='$tr111',
	tr112	='$tr112',
	tr113	='$tr113',
	tr114	='$tr114',
	tr115	='$tr115',
	tr116	='$tr116',
	tr117	='$tr117',
	tr118	='$tr118',
	tr119	='$tr119',
	tr120	='$tr120',
	tr121	='$tr121',
	tr122	='$tr122',
	tr123	='$tr123',
	tr124	='$tr124',
	tr125	='$tr125',
	tr126	='$tr126',
	tr127	='$tr127',
	tr128	='$tr128',
	tr129	='$tr129',
	tr130	='$tr130',
	tr131	='$tr131',
	tr132	='$tr132',
	tr133	='$tr133',
	tr134	='$tr134',
	tr135	='$tr135',
	tr136	='$tr136',
	tr137	='$tr137',
	tr138	='$tr138',
	tr139	='$tr139',
	tr140	='$tr140',
	tr141	='$tr141',
	tr142	='$tr142',
	tr143	='$tr143',
	tr144	='$tr144',
	tr145	='$tr145',
	tr146	='$tr146',
	tr147	='$tr147',
	tr148	='$tr148',
	tr149	='$tr149',
	tr150	='$tr150'
	where noreg='$regcek'
	";

	$hs = sqlsrv_query($conn,$q);

	if($hs){

		$eror = "Success";
		$regugd=substr($noreg, 1,12);

		$q  = "update ERM_TRANSFER_PASIEN set PERAWATPENERIMA='$tr102' where noreg='$regugd'";
		$hs = sqlsrv_query($conn,$q);


	}else{
		$eror = "Gagal Insert";

	}

	// echo "
	// <script>
	// history.go(-1);
	// </script>
	// ";


}

if (isset($_POST["terima"])) {

	$q  = "insert into ERM_RI_TRANSFER(noreg,userid,tglentry,tgl,tr1,tr2) values ('$noregi','$user','$tglentry','$tgl','$d_unit','$k_unit')";
	$hs = sqlsrv_query($conn,$q);

	if($hs){


		$qu="SELECT id FROM ERM_RI_TRANSFER where noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$idtransfer = trim($d1u['id']);
		$idigd = $idigd;

		$eror = "Success";
		$regugd=substr($noreg, 1,12);

		// $q  = "update ERM_TRANSFER_PASIEN set PERAWATPENERIMA='$tr102' where noreg='$regugd'";
		// $hs = sqlsrv_query($conn,$q);

		echo "
		<script>
		window.location.replace('transfer_pasien_igd.php?id=$id|$user|$idtransfer|$idigd');
		</script>
		";


	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	window.print();
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