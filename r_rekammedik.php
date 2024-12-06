<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}

$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT noreg,norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];

$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, noktp,
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  

$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
$kodedept	= $data2[kodedept];

$nama	= $data2[nama];
$kelamin	= $data2[kelamin];
$nik	= trim($data2[nik]);
$alamatpasien	= $data2[alamatpasien];
$kota	= $data2[kota];
$kodekel	= $data2[kodekel];
$telp	= $data2[tlp];
$tmptlahir	= $data2[tmptlahir];
$tgllahir	= $data2[tgllahir];
$jenispekerjaan	= $data2[jenispekerjaan];
$jabatan	= $data2[jabatan];
$umur =  $data2[UMUR];
$noktp =  $data2[noktp];

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">	
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

	<style> 
		#loader { 
			border: 12px solid #f3f3f3; 
			border-radius: 50%; 
			border-top: 12px solid #444444; 
			width: 70px; 
			height: 70px; 
			animation: spin 1s linear infinite; 
		} 

		@keyframes spin { 
			100% { 
				transform: rotate(360deg); 
			} 
		} 

		.center { 
			position: absolute; 
			top: 0; 
			bottom: 0; 
			left: 0; 
			right: 0; 
			margin: auto; 
		} 
	</style> 

</head> 

<div class="container-fluid">

	<body onload="document.myForm.pasien_mcu.focus();">
		<div id="loader" class="center"></div> 

		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
				&nbsp;&nbsp;
				<br>
				<br>
				&nbsp;&nbsp;
				<br>
				<br>
				<div class="row">
					<div class="col-4"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
					<div class="col-4"><b>REKAM MEDIK PASIEN</b></div>
					<div class="col-4"><b><font color='red'>ALERGI : </font></b></div>
				</div>

				<div class="row">
					<div class="col-12">
						<?php echo 'NIK : '.$noktp.'<br>'; ?>                   
						<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
						<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
					</div>
				</div>
				<hr>
				<table  class="table table-bordered">
					<?php
					$ql="SELECT TOP(100)*,CONVERT(VARCHAR, tanggal, 101) as tgl2,CONVERT(VARCHAR, tglentry, 8) as tgl3  FROM ERM_SOAP WHERE norm='$norm' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$no=1;
					echo 
					"<tr bgcolor='#969392'>
					<td>No</td>
					<td>Nama Unit</td>
					<td>S.O.A.P</td>
					</tr>";


					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){    
						$obat = '';
						$lab = '';
						$rad = '';

						$hasilassesment = "";
						$kodedokter = trim($dl[kodedokter]);
						$noreg = trim($dl[noreg]);
						$userid = trim($dl[userid]);
						$dpjp = trim($dl[dpjp]);
						$periode = trim($dl[tgl2]);

						$q2		= "select nama from afarm_dokter where kodedokter='$kodedokter'";
						$hasil2  = sqlsrv_query($conn, $q2);			  					
						$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
						$namadokter	= $data2[nama];

						if(empty($namadokter)){
							$q2		= "select nama from afarm_paramedis where kode='$kodedokter'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];
						}

						//cek verif dokter...
						$q3		= "select userverif from  ERM_SOAP_VERIF where noreg='$noreg' and userid='$userid' and userverif like '%$dpjp%' and (CONVERT(DATETIME, CONVERT(VARCHAR, tanggal, 101), 101) BETWEEN '$periode' AND '$periode')";
						$hasil3  = sqlsrv_query($conn, $q3);			  					
						$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
						$userverif	= $data3[userverif];

						$q4		= "
						SELECT id, norm, noreg, ISI, userid, jenis, ku, rps, anamnesa, rpd, alergi, assesmen, aplan, eye, verbal, movement, tekanan_darah, nadi, suhu, frekuansi_pernafasan, skala_nyeri, berat_badan, fisik_kepala, fisik_mata, fisik_tht, fisik_leher, fisik_paru, fisik_jantung, fisik_abdomen, fisik_ekstermitas, fisik_urogenital, status_lokalis, pemeriksaan_penunjang, ket_nadi, diagnosa_gizi
						FROM            ERM_RI_SOAP
						where id_soap=$dl[id]";
						$hasil4  = sqlsrv_query($conn, $q4);			  					
						$data4	= sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC);		

						if($data4 and empty($data4[diagnosa_gizi])){
							$hasilassesment = "
							<table width='100%''>
							<tr>
							<td>
							<div class='col-12'>
							<b>Subject</b>
							<br>
							<label for='' class=''>Keluhan Utama : </label>
							<label for='' class=''>$data4[ku]</label>
							<br>
							<label for='' class=''>Riwayat Pasien Sekarang : </label>
							<label for='' class=''>$data4[rps]</label>
							<br>
							<br>
							<div class='card'>
							<div class='card-header'>
							Anamnesa Psikologi/Sosial/Ekonomi : 
							</div>
							<div class='card-body'>
							<label for='' class=''>Kondisi Kejiwaan : </label>												
							<label for='' class=''>$data4[anamnesa]</label>
							</div>
							</div>
							<div class='card'>
							<div class='card-header'>
							Data Riwayat Pasien Dahulu :
							</div>
							<div class='card-body'>
							<label for='' class=''>$data4[rpd]</label>
							</div>
							</div>
							<div class='card'>
							<div class='card-header'>
							Data Alergi :
							</div>
							<div class='card-body'>
							<label for='' class=''>$data4[alergi]</label>
							</div>
							</div>
							<br>
							<b>Assesmen</b><br>
							<label for='' class=''>$data4[assesmen]</label>
							<hr>
							<b>Plan :</b><br>
							<label for='' class=''>$data4[aplan]</label>
							</div>		
							</td>
							<td>
							<div class='col-12'>
							<b>Object</b>
							<div class='card'>
							<div class='card-header'>
							Vital Sign
							</div>
							<div class='card-body'>
							<div class='card'>
							<div class='card-header'>
							Glassow Comma Scale (GCS)
							</div>
							<div class='card-body'>
							<label for='' class='col-6'>Eye : </label>
							<label for='' class=''>$data4[eye]</label>
							<br>
							<label for='' class='col-6'>Verbal : </label>
							<label for='' class=''>$data4[verbal]</label>
							<br>
							<label for='' class='col-6'>Movement : </label>
							<label for='' class=''>$data4[movement]</label>
							</div>
							</div>
							<br>
							<label for='' class='col-6'>Tekanan Darah : </label>
							<label for='' class=''>$data4[tekanan_darah] mmHg</label><br>
							<label for='' class='col-6'>Nadi : </label>
							<label for='' class=''>$data4[nadi] x/menit, $data4[ket_nadi]</label>
							<br>
							<label for='' class='col-6'>Suhu : </label>
							<label for='' class=''>$data4[suhu] C</label>
							<label for='' class='col-6'>Frekuensi Pernafasan :</label>
							<label for='' class=''>$data4[frekuansi_pernafasan] x/menit</label>
							<br>
							<label for='' class='col-6'>Skala Nyeri : </label>
							<label for='' class=''>$data4[skala_nyeri]</label>
							<label for='' class='col-6'>Berat Badan : </label>
							<label for='' class=''>$data4[berat_badan]</label>
							<div class='card'>
							<div class='card-header'>
							Pemeriksaan Fisik
							</div>
							<div class='card-body'>
							<label for='' class='col-6'>Kepala : </label>
							<label for='' class=''>$data4[fisik_kepala]</label>
							<br>
							<label for='' class='col-6'>Mata : </label>
							<label for='' class=''>$data4[fisik_mata]</label>
							<br>										
							<label for='' class='col-6'>THT : </label>
							<label for='' class=''>$data4[fisik_tht]</label>
							<br>										
							<label for='' class='col-6'>Leher : </label>
							<label for='' class=''>$data4[fisik_leher]</label>
							<br>
							<label for='' class='col-6'>Paru : </label>
							<label for='' class=''>$data4[fisik_paru]</label>
							<br>
							<label for='' class='col-6'>Jantung : </label>
							<label for='' class=''>$data4[fisik_jantung]</label>
							<br>
							<label for='' class='col-6'>Abdomen : </label>
							<label for='' class=''>$data4[fisik_abdomen]</label>
							<br>
							<label for='' class='col-6'>Extrimitas : </label>
							<label for='' class=''>$data4[fisik_ekstermitas]</label>
							<br>
							<label for='' class='col-6'>Uro Gental : </label>
							<label for='' class=''>$data4[fisik_urogenital]</label>
							<br>
							</div>
							<label for='' class='col-12'>Status Lokalis : $data4[status_lokalis]</label>
							<label for='' class='col-12'>Pemeriksaan Penunjang : $data4[pemeriksaan_penunjang]</label>
							</div>
							</div>
							</div>
							</div>
							</td>
							</tr>
							</table>
							<br>
							";
						}else{

							$hasilassesment = $hasilassesment."
							<b>Subject :</b> $dl[subjektif]<br>
							<b>Object :</b> $dl[objektif]<br>
							<b>Vital Sign :</b> $dl[vital]<br>
							<b>Assesment :</b> $dl[assesment]<br>
							<b>Plant :</b> $dl[planning]
							";	

							//cek gizi...
							$q5		= "SELECT    antropometri, biokimia, fisik_klinis, asupan_makan, diagnosa_gizi, intervensi, monitoring
							FROM            ERM_RI_SOAP
							WHERE      id_soap='$dl[id]'";
							$hasil5  = sqlsrv_query($conn, $q5);			  					
							$data5	= sqlsrv_fetch_array($hasil5, SQLSRV_FETCH_ASSOC);				  
							$antropometri	= $data5[antropometri];
							$biokimia	= $data5[biokimia];
							$fisik_klinis	= $data5[fisik_klinis];
							$asupan_makan	= $data5[asupan_makan];
							$diagnosa_gizi	= $data5[diagnosa_gizi];
							$intervensi	= $data5[intervensi];
							$monitoring	= $data5[monitoring];

							if($diagnosa_gizi){
								$hasilassesment = "
								<b>Assesment gizi :</b> $dl[assesment]<br>
								<b>Diagnosa gizi :</b> $dl[subjektif]<br>
								<b>Intervensi gizi :</b> $dl[planning]<br>
								<b>Monitoring-evaluasi gizi :</b> $dl[objektif]<br>
								";
							}

						}


						if($noreg1 <> $noreg){
							$foto='';
						//foto
							$ql2="							
							SELECT   distinct document_rad.doc
							FROM            document_rad 
							WHERE        (document_rad.noreg LIKE '%$noreg%')
							";
							$hl2  = sqlsrv_query($conn, $ql2);
							while   ($dl2 = sqlsrv_fetch_array($hl2, SQLSRV_FETCH_ASSOC)){         

								$foto0 = "<a href='http://192.168.10.162/dok_radiologi/$dl2[doc]' target='_blank'>radiologi</a>";
								$foto = $foto.'<br>'.$foto0;
							}

						//farmasi..
							$q2="
							SELECT CONVERT(VARCHAR, TGLENTRY, 103) as TGLENTRY,
							NAMABARANG, JUMLAH, HRGJUAL
							FROM            AVIEW_PENJ_OBAT_PASIEN
							WHERE        (NOREG = '$noreg')
							ORDER BY TGLENTRY DESC
							";
							$hq2  = sqlsrv_query($conn, $q2);

						// $obath = "no | tgl          | nama obat | jumlah ";
						// $obath2 = "<br>";

							$i=1;
							while   ($dhq2 = sqlsrv_fetch_array($hq2, SQLSRV_FETCH_ASSOC)){     
								$obat0 = $i.'|'.$dhq2[TGLENTRY].'|'.trim($dhq2[NAMABARANG]).' : '.$dhq2[JUMLAH].'<br>';

								if($i==1){
									$obat = $obath.'&#13;&#10;'.$obath2.'&#13;&#10;'.$obat0;        
								}else{
									$obat = $obat.'&#13;&#10;'.$obat0;                
								}

								$i=$i+1;
							}


						//laborat
							$qlab="
							SELECT 
							CONVERT(VARCHAR, REG_DATE, 103) as REG_DATE,KEL_PEMERIKSAAN,PARAMETER_NAME,HASIL,SATUAN,NILAI_RUJUKAN
							FROM        LINKYAN5.SHARELIS.dbo.hasilLIS
							WHERE        (NOLAB_RS = '$noreg') order by REG_DATE desc, KEL_PEMERIKSAAN,PARAMETER_NAME
							";
							$hqlab  = sqlsrv_query($conn, $qlab);

						// $labh = "no | tgl          | pemeriksaan | hasil ";
						// $labh2 = "<br>";

							$i=1;
							while   ($dhqlab = sqlsrv_fetch_array($hqlab, SQLSRV_FETCH_ASSOC)){     
								$lab0 = $i.'|'.$dhqlab[REG_DATE].'|'.$dhqlab[KEL_PEMERIKSAAN].'-'.trim($dhqlab[PARAMETER_NAME]).' : '.    $dhqlab[HASIL].'<br>';

								if($i==1){
									$lab = $labh.'&#13;&#10;'.$labh2.'&#13;&#10;'.$lab0;        
								}else{
									$lab = $lab.'&#13;&#10;'.$lab0;                
								}
								$i=$i+1;
							}



						//radiologi
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
								$rad0 = $dhqrad[TANGGAL].'-'.$dhqrad[HASIL].':'.$dhqrad[URAIAN].'<br>';
								$rad = $rad.'&#13;&#10;'.$rad0;
								$rad = nl2br($rad);
							}

						}
						
						echo "	<tr>
						<td>$no</td>
						<td>
						$dl[kodeunit] - $dl[sbu]<br>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]
						<hr>
						ERM<br>
						FOTO : $foto
						</td>
						<td>
						$hasilassesment<hr>$kodedokter - $namadokter
						<table>
						<tr>
						<td>Farmasi :<br> $obat</tr>
						<td>Lab :<br> $lab</tr>
						<td>Rad :<br> $rad</tr>
						</tr>
						</table>
						</td>
						</tr>
						";
						$no += 1;
						$noreg1 = $noreg;
					}
					?>
				</table>
			</font>
		</form>
	</font>

	<script> 
		document.onreadystatechange = function() { 
			if (document.readyState !== "complete") { 
				document.querySelector( 
					"body").style.visibility = "hidden"; 
				document.querySelector( 
					"#loader").style.visibility = "visible"; 
			} else { 
				document.querySelector( 
					"#loader").style.display = "none"; 
				document.querySelector( 
					"body").style.visibility = "visible"; 
			} 
		}; 
	</script> 
	
</body>
</div>