<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl2		= gmdate("d/m/Y", time()+60*60*7);
$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qss="
SELECT        TOP (100) CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 25) AS tglmasuk, ERM_ASSESMEN_HEADER.norm, AFarm_MstPasien.NAMA, AFarm_MstPasien.NOKTP, ERM_ASSESMEN_HEADER.kodedokter, ERM_ASSESMEN_HEADER.noreg,ERM_ASSESMEN_HEADER.userid,
Afarm_DOKTER_1.NAMA AS NAMADOKTER
FROM            ERM_ASSESMEN_HEADER INNER JOIN
AFarm_MstPasien ON ERM_ASSESMEN_HEADER.norm = AFarm_MstPasien.NORM INNER JOIN
Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER INNER JOIN
Afarm_DOKTER AS Afarm_DOKTER_1 ON Afarm_DOKTER.KODEDOKTER = Afarm_DOKTER_1.KODEDOKTER
WHERE  ERM_ASSESMEN_HEADER.NOREG='$noreg'
";
$hqss  = sqlsrv_query($conn, $qss);        
$dhqss  = sqlsrv_fetch_array($hqss, SQLSRV_FETCH_ASSOC); 
$kodedokter = trim($dhqss['kodedokter']);
$tglawal = trim($dhqss['tglmasuk']);


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
	$alamat = "
	Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik
	<br>
	IGD : 031-99100118 Telp : 031-3978658<br>
	Email : sbu.rspg@gmail.com
	";
	$logo = "logo/rspg.png";
};
if ($KET1 == 'GRAHU'){
	$nmrs = "RUMAH SAKIT GRHA HUSADA";
	$alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
	$logo = "logo/grahu.png";
};
if ($KET1 == 'DRIYO'){
	$nmrs = "RUMAH SAKIT DRIYOREJO";
	$alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
	$logo = "logo/driyo.png";
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


$qi="SELECT noreg FROM ERM_RI_GENERALCONSENT where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_GENERALCONSENT(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_GENERALCONSENT
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment']; 
	$tgl = $de['tgl'];
	$gc1 = $de['gc1'];
	$gc2= $de['gc2'];
	$gc3= $de['gc3'];
	$gc4= $de['gc4'];
	$gc5= $de['gc5'];
	$gc6= $de['gc6'];
	$gc7= $de['gc7'];
	$gc8= $de['gc8'];
	$gc9= $de['gc9'];
	$gc10= $de['gc10'];
	$gc11= $de['gc11'];
	$gc12= $de['gc12'];
	$gc13= $de['gc13'];
	$gc14= $de['gc14'];
	$gc15= $de['gc15'];
	$gc16= $de['gc16'];
	$gc17= $de['gc17'];
	$gc18= $de['gc18'];
	$gc19= $de['gc19'];
	$gc20= $de['gc20'];
	$gc21= $de['gc21'];
	$gc22= $de['gc22'];
	$gc23= $de['gc23'];
	$gc24= $de['gc24'];
	$gc25= $de['gc25'];
	$gc26= $de['gc26'];
	$gc27= $de['gc27'];
	$gc28= $de['gc28'];
	$gc29= $de['gc29'];
	$gc30= $de['gc30'];
	$gc31= $de['gc31'];
	$gc32= $de['gc32'];
	$gc33= $de['gc33'];
	$gc34= $de['gc34'];
	$gc35= $de['gc35'];
	$gc36= $de['gc36'];
	$gc37= $de['gc37'];
	$gc38= $de['gc38'];
	$gc39= $de['gc39'];
	$gc40= $de['gc40'];
	$gc41= $de['gc41'];
	$gc42= $de['gc42'];
	$gc43= $de['gc43'];
	$gc44= $de['gc44'];
	$gc45= $de['gc45'];
	$gc46= $de['gc46'];
	$gc47= $de['gc47'];
	$gc48= $de['gc48'];
	$gc49= $de['gc49'];
	$gc50= $de['gc50'];
	$gc51= $de['gc51'];
	$gc52= $de['gc52'];
	$gc53= $de['gc53'];
	$gc54= $de['gc54'];
	$gc55= $de['gc55'];
	$gc56= $de['gc56'];
	$gc57= $de['gc57'];
	$gc58= $de['gc58'];
	$gc59= $de['gc59'];
	$gc60= $de['gc60'];

	$gc61= $de['gc61']; 
	$gc62= $de['gc62']; 
	$gc63= $de['gc63'];
	$gc64= $de['gc64'];
	$gc64a= $de['gc64a'];
	$gc64b= $de['gc64b'];
	$gc64c= $de['gc64c'];
	$gc64d= $de['gc64d'];
	$gc64e= $de['gc64e'];
	$gc65= $de['gc65'];
	$gc66= $de['gc66'];
	$gc66a= $de['gc66a'];
	$gc67= $de['gc67'];
	$gc68= $de['gc68'];
	$gc69= $de['gc69'];
	$gc70= $de['gc70'];
	$gedung= $de['gedung'];
	$ketlain= $de['ketlain'];
	$gc71= $de['gc71'];
	$darikelas= $de['darikelas'];
	$kekelas= $de['kekelas'];
	$asuransilain= $de['asuransilain'];
	$penanggung= $de['penanggung'];
	$plafon= $de['plafon'];
	$hakkelas= $de['hakkelas'];
	$permintaankelas= $de['permintaankelas'];
	$sementarakelas= $de['sementarakelas'];
	$nama2= $de['nama2'];
	$tgllahir2= $de['tgllahir2'];
	$kelamin2= $de['kelamin2'];
	$alamat2= $de['alamat2'];
	$telp2= $de['telp2'];
	$adalah= $de['adalah'];

	if(empty($asuransilain)){
		$asuransilain = $gc19;
	}

	if(empty($gc66a)){
		$gc66a = $gc10;
	}



}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>General Consent</title>  
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
</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='generalconsent.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
					<a href='r_generalconsent2.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a>

					&nbsp;&nbsp;
					<br>
					<br>

					<div class="row">
					</div>

					<div class="row">
						<div class="col-6">
							<h5><b><?php echo $nmrs; ?></b></h5>
							<?php echo $alamat; ?>
						</div>
						<div class="col-6">
							<?php echo 'NIK : '.$noktp.'<br>'; ?>					
							<?php echo 'NAMA LENGKAP : '.$nama.'<br>NORM : '.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
							<?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
						</div>
					</div>
					<hr>

					<div class="row">
						<div class="col-12">
							<p style="text-align: center; font-weight: bold;">PERSETUJUAN UMUM ( GENERAL CONSENT )</p>
							<table>
								<tr>
									<td>
										<b>Identitas Penanggung Jawab</b>
										<table width="100%" border="0" valign="top">
											<tr>
												<td width="20%">Nama</td>
												<td><input class="form-control form-control-sm" type="text" name="nama2" value='<?php echo $nama2;?>'></td>
											</tr>
											<tr>
												<td>Tanggal Lahir</td>
												<td><input class="form-control form-control-sm" type="date" name="tgllahir2" value='<?php echo $tgllahir2;?>'></td>
											</tr>
											<tr>
												<td>Jenis Kelamin</td>
												<td>
													<select class="form-control form-control-sm" name="kelamin2">
														<option value="">--Pilih Data--</option>
														<option value="Laki-laki" <?php if($kelamin2=='Laki-laki'){echo "selected";}?>>Laki-laki</option>
														<option value="Perempuan" <?php if($kelamin2=='Perempuan'){echo "selected";}?>>Perempuan</option>
													</select>
												</td>
											</tr>
											<tr>
												<td>Alamat</td>
												<td><input type="text" class="form-control form-control-sm" name="alamat2" value='<?php echo $alamat2;?>'></td>
											</tr>
											<tr>
												<td>No Telp</td>
												<td><input type="tel" class="form-control form-control-sm" name="telp2" value='<?php echo $telp2;?>'></td>
											</tr>
											<tr>
												<td>Hubungan dengan Pasien</td>
												<td>
													<select class="form-control form-control-sm" name="adalah">
														<option value="">--Pilih Data--</option>
														<option value="Diri sendiri" <?php if($adalah=='Diri sendiri'){echo "selected";}?>>Diri sendiri</option>
														<option value="Istri" <?php if($adalah=='Istri'){echo "selected";}?>>Istri</option>
														<option value="Suami" <?php if($adalah=='Suami'){echo "selected";}?>>Suami</option>
														<option value="Anak" <?php if($adalah=='Anak'){echo "selected";}?>>Anak</option>
														<option value="Ayah" <?php if($adalah=='Ayah'){echo "selected";}?>>Ayah</option>
														<option value="Ibu" <?php if($adalah=='Ibu'){echo "selected";}?>>Ibu</option>
													</select>
												</td>
											</tr>
										</table>
										<p style="text-align: center; font-weight: bold;">
											<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> update</button>
										</p>
										<br>
										<b>Data Pasien</b>
										<table width="100%" border="0">
											<tr>
												<td width="20%">Nama Pasien</td>
												<td><?php echo $nama?></td>
											</tr>
											<tr>
												<td>Nomor Rekam Medis</td>
												<td><?php echo $norm?></td>
											</tr>
											<tr>
												<td>Tanggal Lahir Pasien</td>
												<td><?php echo $tgllahir?></td>
											</tr>
											<tr>
												<td>Alamat</td>
												<td><?php echo $alamatpasien?></td>
											</tr>
											<tr>
												<td>No Telp</td>
												<td><?php echo $telp?></td>
											</tr>
										</table>

										<br><br><br>
									</td>
								</tr>
								<tr>
									<td>
										<p style="text-align: center; font-weight: bold;">
											PASIEN DAN/ ATAU WALI HUKUM HARUS MEMBACA, MEMAHAMI 
											DAN MENGISI INFORMASI BERIKUT
										</p>
									</td>
								</tr>
								<tr>
									<td>
										<table border='1'>
											<tr valign="top">
												<td>1</td>
												<td align="left">HAK DAN KEWAJIBAN SEBAGAI PASIEN. Saya mengakui bahwa pada proses pendaftaran untuk mendapatkan perawatan di RS Petrokimia Gresik dan penandatanganan dokumen ini, saya telah menerima Leaflet dan mendapat informasi tentang hak-hak dan kewajiban saya sebagai pasien.</td>
												<td width="10%">
													<input type='radio' name='gc61' value='Ya' <?php if ($gc61=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc61' value='Tidak' <?php if ($gc61=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>2</td>
												<td align="left">PERSETUJUAN PELAYANAN KESEHATAN. Saya menyetujui dan memberikan persetujuan untuk mendapat pelayanan kesehatan di RS Petrokimia Gresik dan dengan ini saya meminta dan memberikan kuasa kepada RS Petrokimia Gresik, dokter dan perawat, dan tenaga kesehatan lainnya untuk memberikan asuhan perawatan, pemeriksaan fisik yang dilakukan oleh dokter dan perawat dan melakukan prosedur diagnostik, radiologi dan/ atau terapi dan tatalaksana sesuai pertimbangan dokter yang diperlukan atau disarankan pada perawatan saya. Hal ini mencakup seluruh pemeriksaan dan prosedur diagnostik rutin, termasuk x-ray, pemberian dan/ atau tindakan medis serta penyuntikan (intramuskular, intravena) produk farmasi dan obat-obatan, pemasangan alat kesehatan termasuk pemasangan infuspemasangankateter,pemeriksaan/perawatangigi dan pengambilan darah untuk pemeriksaan laboratorium atau pemeriksaan patologiyang dibutukan untuk pengobatan dan tindakan yang aman. Tindakan medis lain khusus yang tercakup dalam persetujuan umum ini, akan dimintakan persetujuan secara terpisah.</td>
												<td width="10%">
													<input type='radio' name='gc62' value='Ya' <?php if ($gc62=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc62' value='Tidak' <?php if ($gc62=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>3</td>
												<td align="left">PEMILIHAN DPJP. Saya telah menerima dan memahami informasi mengenai dokter penanggung jawab pasien selama dirawat Rumah Sakit, maka saya memilih Dokter 
													<b>
														<?php
														$qu="
														SELECT        TOP (200) ERM_ASSESMEN_HEADER.id, ERM_ASSESMEN_HEADER.noreg, Afarm_DOKTER.NAMA,Afarm_DOKTER.KODEDOKTER
														FROM            ERM_ASSESMEN_HEADER INNER JOIN
														Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER
														where ERM_ASSESMEN_HEADER.noreg='$noreg'";
														$h1u  = sqlsrv_query($conn, $qu);        
														$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
														$NAMA = trim($d1u['NAMA']);
														$KODEDOKTER = trim($d1u['KODEDOKTER']);

														echo $NAMA.'('.$KODEDOKTER.')';
														?>
													</b>
												Sebagai dokter penanggung jawab.</td>
												<td width="10%">
													<input type='radio' name='gc63' value='Ya' <?php if ($gc63=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc63' value='Tidak' <?php if ($gc63=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>4</td>
												<td align="left">
													Saya memberi kuasa kepada RS Petrokimia Gresik untuk menjaga privasi dan kerahasian penyakit saya selama dalam perawatan(beri tanda (v) pada kotak) :
													<br>
													<input type='radio' name='gc64a' value='1' <?php if ($gc64a=="1"){echo "checked";}?> >
													Saya mengijinkan semua orang menjenguk saya
													<br>
													<input type='radio' name='gc64a' value='2' <?php if ($gc64a=="2"){echo "checked";}?> >
													Saya mengijinkan semua orang menjenguk saya, kecuali,
													<input class="" name="gc64d" value="<?php echo $gc64d;?>" id="" type="text" size='50' placeholder="" >
													<br>
													<input type='radio' name='gc64a' value='3' <?php if ($gc64a=="3"){echo "checked";}?> >
													Saya tidak mengijinkansemua orang menjenguk saya, kecuali,
													<input class="" name="gc64e" value="<?php echo $gc64e;?>" id="" type="text" size='50' placeholder="" >
												</td>
												<td width="10%">
													<input type='radio' name='gc64' value='Ya' <?php if ($gc64=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc64' value='Tidak' <?php if ($gc64=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>5</td>
												<td align="left">
													RAHASIA KEDOKTERAN. Saya setuju RS Petrokimia Gresik wajib menjamin rahasia kedokteran saya baik untuk kepentingan perawatan atau pengobatan, pendidikan maupun penelitian kecuali saya mengungkapkan sendiri atau orang lain yang saya beri kuasa sebagai Penjamin. 
												</td>
												<td width="10%">
													<input type='radio' name='gc65' value='Ya' <?php if ($gc65=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc65' value='Tidak' <?php if ($gc65=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>6</td>
												<td align="left">
													MEMBUKA RAHASIA KEDOKTERAN.Saya setuju untuk membuka rahasia kedokteran terkait dengan kondisi kesehatan, asuhan dan pengobatan yang saya terima kepada: 
													<br>a.	Dokter dan tenaga kesehatan lain yang memberikan asuhan kepada saya 
													<br>b.	Perusahaan asuransi kesehatan atau perusahaan lainnya atau pihak lain yang menjamin pembiayaan saya.
													<br>c.	Anggota keluarga saya :    
													<br><input class="" name="gc66a" value="<?php echo $gc66a;?>" id="" type="text" size='50' placeholder="" >

												</td>
												<td width="10%">
													<input type='radio' name='gc66' value='Ya' <?php if ($gc66=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc66' value='Tidak' <?php if ($gc66=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>7</td>
												<td align="left">
													INFORMASI RAWAT INAP
													<br>a.	Saya tidak diperkenankan membawa barang-barang berharga yang tidak diperlukan (seperti: perhiasan, elektronik, dll) selama dalam perawatan di RS Petrokimia Gresik. Saya memahami dan menyetujui bahwa apabila saya membawanya, maka RS Petrokimia Gresik tidak bertanggung jawab terhadap kehilangan, kerusakan atau pencurian. 
													<br>b.	Bila tidak ada anggota keluarga yang bisa membawa pulang barang berharga tersebut, RS mengambil alih dan menyediakan tempat penitipan barang milik pasien di tempat penitipan barang milik pasien di tempat resmi yang telah disediakan RS.
													<br>c.	Saya telah menerima informasi tentang peraturan yang diberlakukan oleh rumah sakit dan saya beserta keluarga bersedia untuk mematuhinya, termasuk akan mematuhi jam berkunjung pasien sesuai dengan aturan di rumah sakit
													<br>d.	Anggota keluarga saya yang menunggu saya, bersedia untuk selalu memakai tanda pengenal khusus yang diberikan oleh RS, dan demi keamanan seluruh pasien setiap keluarga dan siapapun yang akan mengunjungi saya diluar jam berkunjung, bersedia untuk diminta/ diperiksa identitasnya dan memakai identitas yang diberikan oleh RS.
												</td>
												<td width="10%">
													<input type='radio' name='gc67' value='Ya' <?php if ($gc67=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc67' value='Tidak' <?php if ($gc67=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>8</td>
												<td align="left">
													PENGAJUAN KELUHAN
													Saya menyatakan bahwa saya telah menerima informasi tentang adanya tatacara mengajukan dan mengatasi keluhan terkait pelayanan medik yang diberikan terhadap diri saya. Saya setuju untuk mengikuti tatacara mengajukan keluhan sesuai prosedur yang ada.  Penanganan komplain nomor 081331706002. 
												</td>
												<td width="10%">
													<input type='radio' name='gc68' value='Ya' <?php if ($gc68=="Ya"){echo "checked";}?> >Ya
													<input type='radio' name='gc68' value='Tidak' <?php if ($gc68=="Tidak"){echo "checked";}?> >Tidak
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>9</td>
												<td align="left" colspan="2">
													KEWAJIBAN PEMBAYARAN. 
													<br>a.	Menjadi pasien dengan status sebagai : (lingkari salah satu 1/2/3 dibawah ini)
													<?php 
													if($gc18=='umum'){
														?>
														<br>1)	PASIEN UMUM (beri tanda (&radic;) pada kotak) :
														<br>
														<input type='radio' name='gc69' value='UMUM' <?php if ($gc69=="UMUM"){echo "checked";}?> >
														Pasien umum, karena tidak mempunyai asuransi
														<br>
														<input type='radio' name='gc69' value='ASURANSI' <?php if ($gc69=="ASURANSI"){echo "checked";}?> >
														Pasien umum, karena dengan sadar tidak mau menggunakan Asuransi
														<br>Permintaan kelas : 
														<br><input type='radio' name='gc70' value='1' <?php if ($gc70=="1"){echo "checked";}?> >
														Kelas 1 : Rp. 510.500/hari.	
														<br><input type='radio' name='gc70' value='2' <?php if ($gc70=="2"){echo "checked";}?> >
														Kelas 2 : Rp. 481.500/hari.
														<br><input type='radio' name='gc70' value='3' <?php if ($gc70=="3"){echo "checked";}?> >
														Kelas 3 : Rp. 219.000/hari.
														<br><input type='radio' name='gc70' value='4' <?php if ($gc70=="4"){echo "checked";}?> >
														Kelas VIP 1 : Rp. 936.000/hari.  	
														<br><input type='radio' name='gc70' value='5' <?php if ($gc70=="5"){echo "checked";}?> >
														Kelas VIP 2	: Rp. 802.000/hari.
														<br><input type='radio' name='gc70' value='6' <?php if ($gc70=="6"){echo "checked";}?> >
														Kelas VVIP  : Rp. 1.605.000/hari.  	
														<br><input type='radio' name='gc70' value='7' <?php if ($gc70=="7"){echo "checked";}?> >
														Kelas PRESIDEN SUITE : Rp. 2.228.500/hari.
														<br><input type='radio' name='gc70' value='8' <?php if ($gc70=="8"){echo "checked";}?> >
														Kamar Isolasi : Rp. /hari.  	
														<br><input type='radio' name='gc70' value='9' <?php if ($gc70=="9"){echo "checked";}?> >
														Kamar ICU : Rp. /hari.
														<br>Gedung
														<input class="" name="gedung" value="<?php echo $gedung;?>" id="" type="text" size='50' placeholder="" >
														<br>Keterangan Lain
														<input class="" name="ketlain" value="<?php echo $ketlain;?>" id="" type="text" size='50' placeholder="" >

														<br>&radic;	Harga diatas belum termasuk, jasa pelayanan, visite dokter, sewa alat kedokteran, tindakan medis yang dilakukan, obat dan pemeriksaan penunjang lainnya.
														<br>&radic;	Saya bertanggungjawab untuk membayar semua biaya yang timbul selama perawatan pasiendan dibayar lunas pada saat pasien selesai perawatan (pada saat pulang).
														<hr>
													<?php } if($gc18=='bpjs'){ ?>
														<br>
														2)	PASIEN BPJS KESEHATANdengan nomor identitas BPJS KESEHATAN/JKN :
														(beri tanda (&radic;) pada kotak)
														<br>
														<input type='radio' name='gc71' value='1' <?php if ($gc71=="1"){echo "checked";}?> >
														2.1 TIDAK NAIK KELAS *)
														<br>
														<input type='radio' name='gc71' value='2' <?php if ($gc71=="2"){echo "checked";}?> >
														2.2 PASIEN BPJS KESEHATAN NAIK KELAS PERAWATAN 
														<br>*) Naik 1 tingkat (non VIP) bersedia membayar selisih biaya berdasarkan selisih tarif INACBGS
														<br>*) tidak mendapatkan print rincian biaya, dikarenakan untuk klaim ke BPJS 
														<br>
														<input type='radio' name='gc71' value='3' <?php if ($gc71=="3"){echo "checked";}?> >
														2.3 PASIEN BPJS KESEHATAN NAIK 2 TINGKAT / UMUM
														<br>Naik 2 tingkat ataulebih, 
														darikelas 
														<input class="" name="darikelas" value="<?php echo $darikelas;?>" id="" type="text" size='50' placeholder="" >
														ke 
														<input class="" name="kekelas" value="<?php echo $kekelas;?>" id="" type="text" size='50' placeholder="" >
														makasecaraotomatiskepesertaan BPJS dianggapgugurdandinyatakansebagaipasienumumdanbersedia membayarbiayaperawatan.
														<br>-	Saya berkewajiban menunjukkan kartu identitas peserta JKN dan persyaratan administrasi lainnya selambat-lambatnya 3 hari sejak dirawat atau sebelum pulang apabila dirawat kurang dari 3 hari.
														<br>-	Apabila sampai waktu yang telah ditentukan, saya tidak dapat menunjukkan kartu identitas peserta JKN dan atau SEP tidak bisa dicetak karena kartu tidak aktif maka pasien dinyatakan sebagai PASIEN UMUM dan tidak dapat dijamin BPJS kesehatan (kecuali2.3).
														<hr>
													<?php } if($gc18=='asuransi lain'){?>
														<br>
														3)	PASIEN ASURANSI LAIN (sebutkan) : 
														<input class="" name="asuransilain" value="<?php echo $asuransilain;?>" id="" type="text" size='50' placeholder="" >
														<br>-	Apabila pasien/keluarga memilih kelas yang lebih tinggi dari haknya, maka selisih dari seluruh biaya perawatan, obat, pengunjung medis, jasa dokter, ditanggung oleh pasien/keluarga pasien dan dibayar lunas pada saat pasien selesai perawatan (pada saat pulang)
														<br>-	Instansi/asuransi yang menanggung : 
														<input class="" name="penanggung" value="<?php echo $penanggung;?>" id="" type="text" size='50' placeholder="" >
														<br>plafon : Rp. 
														<input class="" name="plafon" value="<?php echo $plafon;?>" id="" type="text" size='50' placeholder="" >
														<br>-	Hak kelas perawatan : 
														<input class="" name="hakkelas" value="<?php echo $hakkelas;?>" id="" type="text" size='50' placeholder="" >
														<br>permintaan kelas : 
														<input class="" name="permintaankelas" value="<?php echo $permintaankelas;?>" id="" type="text" size='50' placeholder="" >
														sementara kelas : 
														<input class="" name="sementarakelas" value="<?php echo $sementarakelas;?>" id="" type="text" size='50' placeholder="" >
														<br>-	Khusus untuk asuransi JASARAHARJA wajib menunjukkan surat keterangan kecelakaan dari kepolisian maksimal 1 x 24 jam sejak kejadian. Jika tidak, maka dianggap pasien umum. 
														<br>-	Apabila dalam proses perawatan, ternyata instansi/asuransi tidak menanggung pembiayaan maka seluruh biaya perawatan pasien ditanggung oleh pasien/keluarga.
														<br>
														<br>b.	Tidak akan mengganti status penjamin pasien selama dirawat di Rumah Sakit.
														<br>c.	Menyetujui peraturan tentang perpindahan pasien pasca tindakan/operasi ke kelas yang lebih tinggi
														<br>•	Apabila perpindahan kelas dilakukan sebelum 3 x 24 jam pasca operasi, maka perhitungan seluruh biaya akan dikenakan tarif kelas tertinggi yang ditempati.
														<br>•	Apabila perpindahan kelas diajukan setelah 3 x 24 jam pasca operasi, maka biaya operasi dan biaya tindakan yang terjadi sebelum perpindahan kelas akan dihitung sesuai dengan kelas yang ditempati saat dilakukan tindakan/operasi.
														<br>d.	Waktu pulang (check out) 
														Pulang diatas pukul 19.00 WIB     : dikenakan tambahan biaya kamar 100% (satu hari).
													<?php } ?>
												</td>
											</tr>
										</table>
										<table border='1'>
											<tr valign="top">
												<td>10</td>
												<td align="left" colspan="2">
													SAYA JUGA MENYADARI DAN MEMAHAMI BAHWA
													<br>a.	Apabila saya tidak memberikan persetujuan, atau dikemudian hari mencabut persetujuan saya untuk melepaskan rahasia kedokteran saya kepada perusahaan asuransi yang saya tentukan, maka saya pribadi bertanggung jawab untuk membayar semua pelayanan dan tindakan medis dari RS Petrokimia Gresik. 
													<br>b.	Apabila rumah sakit membutuhkan proses hukum untuk menagih biaya pelayanan rumah sakit dari saya, saya memahami bahwa saya bertanggung jawab untuk membayar semua biaya yang disebabkan dari proses hukum tersebut. 
													<br><br>
													SAYA TELAH MEMBACA dan SEPENUHNYA SETUJU dengan setiap pernyataan yang terdapat pada formulir ini dan menandatangani tanpa paksaan dan dengan kesadaran penuh. 
													<br><br><br>
													Gresik,
													Tgl Input :<?php echo $tgl_assesment; ?> , Jam : <?php echo $jam_assesment; ?>
													<table width="100%">
														<tr>
															<td align="center">
																Pasien/Keluarga/Penanggungjawab,<br>
																<?php 
																$pernyataanpasien='Lembar General Consent ini telah disetujui oleh Keluarga Pasien dg nama:'.$gc16.' Hubungan dg pasien : '.$gc15.' - pada tanggal:'.$tgl;
																QRcode::png($pernyataanpasien, "image_pernyataanpasien.png", "L", 2, 2);
																echo "<center><img src='image_pernyataanpasien.png'></center>";
																echo $gc16;			
																?>
															</td>
															<td align="center">
																Pemberi Informasi,<br>
																<?php 
																QRcode::png($penanggungjawab, "image.png", "L", 2, 2);   
																echo "<center><img src='image.png'></center>";
																echo 'Petugas : '.$gc14;
																?>

															</td>
														</tr>
													</table>
													<br><br><br>
												</td>
											</tr>
										</table>

										<p style="text-align: center; font-weight: bold;">
											<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> update</button>
										</p>

										<br>
										*) Coret yang tidak perlu
									</td>
								</tr>
							</table>
						</div>
					</div>

				</form>
			</font>
		</body>
	</div>
</div>

<?php 


if (isset($_POST["simpan"])) {

	$gc1	= $_POST["gc1"];
	$gc2	= $_POST["gc2"];
	$gc3	= $_POST["gc3"];
	$gc4	= $_POST["gc4"];
	$gc5	= $_POST["gc5"];
	$gc6	= $_POST["gc6"];
	$gc7	= $_POST["gc7"];
	$gc8	= $_POST["gc8"];
	$gc9	= $_POST["gc9"];
	$gc10	= $_POST["gc10"];
	$gc11	= $_POST["gc11"];
	$gc12	= $_POST["gc12"];
	$gc13	= $_POST["gc13"];
	$gc14	= $_POST["gc14"];
	$gc15	= $_POST["gc15"];
	$gc16	= $_POST["gc16"];
	$gc17	= $_POST["gc17"];
	$gc18	= $_POST["gc18"];
	$gc19	= $_POST["gc19"];
	$gc20	= $_POST["gc20"];
	$gc21	= $_POST["gc21"];
	$gc22	= $_POST["gc22"];
	$gc23	= $_POST["gc23"];
	$gc24	= $_POST["gc24"];
	$gc25	= $_POST["gc25"];
	$gc26	= $_POST["gc26"];
	$gc27	= $_POST["gc27"];
	$gc28	= $_POST["gc28"];
	$gc29	= $_POST["gc29"];
	$gc30	= $_POST["gc30"];
	$gc31	= $_POST["gc31"];
	$gc32	= $_POST["gc32"];
	$gc33	= $_POST["gc33"];
	$gc34	= $_POST["gc34"];
	$gc35	= $_POST["gc35"];
	$gc36	= $_POST["gc36"];
	$gc37	= $_POST["gc37"];
	$gc38	= $_POST["gc38"];
	$gc39	= $_POST["gc39"];
	$gc40	= $_POST["gc40"];
	$gc41	= $_POST["gc41"];
	$gc42	= $_POST["gc42"];
	$gc43	= $_POST["gc43"];
	$gc44	= $_POST["gc44"];
	$gc45	= $_POST["gc45"];
	$gc46	= $_POST["gc46"];
	$gc47	= $_POST["gc47"];
	$gc48	= $_POST["gc48"];
	$gc49	= $_POST["gc49"];
	$gc50	= $_POST["gc50"];
	$gc51	= $_POST["gc51"];
	$gc52	= $_POST["gc52"];
	$gc53	= $_POST["gc53"];
	$gc54	= $_POST["gc54"];
	$gc55	= $_POST["gc55"];
	$gc56	= $_POST["gc56"];
	$gc57	= $_POST["gc57"];
	$gc58	= $_POST["gc58"];
	$gc59	= $_POST["gc59"];
	$gc60	= $_POST["gc60"];

	$gc61= $_POST["gc61"]; 
	$gc62= $_POST["gc62"]; 
	$gc63= $_POST["gc63"];
	$gc64= $_POST["gc64"];
	$gc64a= $_POST["gc64a"];
	$gc64b= $_POST["gc64b"];
	$gc64c= $_POST["gc64c"];
	$gc64d= $_POST["gc64d"];
	$gc64e= $_POST["gc64e"];
	$gc65= $_POST["gc65"];
	$gc66= $_POST["gc66"];
	$gc66a= $_POST["gc66a"];
	$gc67= $_POST["gc67"];
	$gc68= $_POST["gc68"];
	$gc69= $_POST["gc69"];
	$gc70= $_POST["gc70"];
	$gedung= $_POST["gedung"];
	$ketlain= $_POST["ketlain"];
	$gc71= $_POST["gc71"];
	$darikelas= $_POST["darikelas"];
	$kekelas= $_POST["kekelas"];
	$asuransilain= $_POST["asuransilain"];
	$penanggung= $_POST["penanggung"];
	$plafon= $_POST["plafon"];
	$hakkelas= $_POST["hakkelas"];
	$permintaankelas= $_POST["permintaankelas"];
	$sementarakelas= $_POST["sementarakelas"];
	$nama2= $_POST["nama2"];
	$tgllahir2= $_POST["tgllahir2"];
	$kelamin2= $_POST["kelamin2"];
	$alamat2= $_POST["alamat2"];
	$telp2= $_POST["telp2"];
	$adalah= $_POST["adalah"];

	$q  = "update ERM_RI_GENERALCONSENT set
	gc61='$gc61', 
	gc62='$gc62', 
	gc63='$gc63',
	gc64='$gc64',
	gc64a='$gc64a',
	gc64b='$gc64b',
	gc64c='$gc64c',
	gc64d='$gc64d',
	gc64e='$gc64e',
	gc65='$gc65',
	gc66='$gc66',
	gc66a='$gc66a',
	gc67='$gc67',
	gc68='$gc68',
	gc69='$gc69',
	gc70='$gc70',
	gedung='$gedung',
	ketlain='$ketlain',
	gc71='$gc71',
	darikelas='$darikelas',
	kekelas='$kekelas',
	asuransilain='$asuransilain',
	penanggung='$penanggung',
	plafon='$plafon',
	hakkelas='$hakkelas',
	permintaankelas='$permintaankelas',
	sementarakelas='$sementarakelas',
	nama2='$nama2',
	tgllahir2='$tgllahir2',
	kelamin2='$kelamin2',
	alamat2='$alamat2',
	telp2='$telp2',
	adalah='$adalah'
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
	window.location.replace('generalconsent2.php?id=$id|$user');
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