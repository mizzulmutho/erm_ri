<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

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
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];


$qi="SELECT noreg FROM ERM_RI_INFORMCONSENT where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_INFORMCONSENT(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_INFORMCONSENT
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$ic1 = $de['ic1'];
	$ic2= $de['ic2'];
	$ic3= $de['ic3'];
	$ic4= $de['ic4'];
	$ic5= $de['ic5'];
	$ic6= $de['ic6'];
	$ic7= $de['ic7'];
	$ic8= $de['ic8'];
	$ic9= $de['ic9'];
	$ic10= $de['ic10'];
	$ic11= $de['ic11'];
	$ic12= $de['ic12'];
	$ic13= $de['ic13'];
	$ic14= $de['ic14'];
	$ic15= $de['ic15'];
	$ic15a= $ic15;
	$ic16= $de['ic16'];
	$ic17= $de['ic17'];
	$ic18= $de['ic18'];
	$ic19= $de['ic19'];
	$ic20= $de['ic20'];
	$ic21= $de['ic21'];
	$ic22= $de['ic22'];
	$ic23= $de['ic23'];
	$ic24= $de['ic24'];
	$ic25= $de['ic25'];
	$ic26= $de['ic26'];
	$ic27= $de['ic27'];
	$ic28= $de['ic28'];
	$ic29= $de['ic29'];
	$ic30= $de['ic30'];
	$ic31= $de['ic31'];
	$ic32= $de['ic32'];
	$ic33= $de['ic33'];
	$ic34= $de['ic34'];
	$ic35= $de['ic35'];
	$ic36= $de['ic36'];
	$ic37= $de['ic37'];
	$ic38= $de['ic38'];
	$ic39= $de['ic39'];
	$ic40= $de['ic40'];
	$ic41= $de['ic41'];
	$ic42= $de['ic42'];
	$ic43= $de['ic43'];
	$ic44= $de['ic44'];
	$ic45= $de['ic45'];
	$ic46= $de['ic46'];
	$ic47= $de['ic47'];
	$ic48= $de['ic48'];
	$ic49= $de['ic49'];
	$ic50= $de['ic50'];
	$ic51= $de['ic51'];
	$ic52= $de['ic52'];
	$ic53= $de['ic53'];
	$ic54= $de['ic54'];
	$ic55= $de['ic55'];
	$ic56= $de['ic56'];
	$ic57= $de['ic57'];
	$ic58= $de['ic58'];
	$ic59= $de['ic59'];
	$ic60= $de['ic60'];
}


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Informed Consent</title>  
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

	<style>
		.page-break {
			page-break-after: always;
		}
	</style>

	
</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">

			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">


					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
					<!-- <a href='r_informconsent.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
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
							<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
							<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
						</div>
					</div>
					<hr>

					<div class="row">
						<div class="col-12 text-center">
							<b>
								INFORMED CONSENT<br>
								(PERSETUJUAN TINDAKAN KEDOKTERAN)
							</b><br>
						</div>
					</div>

					<br>

					<table width='100%' border='0'>

						<tr>
							<td>
								<div class="row">
									<div class="col-12 text-center">
										PEMBERIAN INFORMASI
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Dokter pelaksana tindakan
									</div>
									<div class="col-8">: <?php echo $ic1; ?></div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Pemberi Informasi 
									</div>
									<div class="col-8">: <?php echo $ic2; ?></div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										penerimaan informasi/pemberi persetujuan *)
									</div>
									<div class="col-8">: <?php echo $ic3; ?></div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										nama saksi (jika ada)
									</div>
									<div class="col-8">: <?php echo $ic27; ?></div>
								</div>
							</td>
						</tr>


						<tr>
							<td>
								<div class="row">
									<div class="col-12">
										<table class="table" border="1">
											<tr>
												<td bgcolor='LightGray'>No</td>
												<td bgcolor='LightGray'>Jenis Informasi</td>
												<td bgcolor='LightGray'>Isi Informasi</td>
											</tr>
											<tr>
												<td>1</td>
												<td>Diagnosis</td>
												<td><?php echo $ic4; ?></td>
											</tr>
											<tr>
												<td>2</td>
												<td>Dasar Diagnosis</td>
												<td><?php echo $ic5; ?></td>
											</tr>
											<tr>
												<td>3</td>
												<td>Tindakan Kedokteran</td>
												<td><?php echo $ic6; ?></td>
											</tr>
											<tr>
												<td>4</td>
												<td>Indikasi Tindakan</td>
												<td><?php echo $ic7; ?></td>
											</tr>
											<tr>
												<td>5</td>
												<td>Tata Cara</td>
												<td><?php echo $ic8; ?></td>
											</tr>
											<tr>
												<td>6</td>
												<td>Tujuan</td>
												<td><?php echo $ic9; ?></td>
											</tr>
											<tr>
												<td>7</td>
												<td>Risiko</td>
												<td><?php echo $ic10; ?></td>
											</tr>
											<tr>
												<td>8</td>
												<td>Komplikasi</td>
												<td><?php echo $ic11; ?></td>
											</tr>
											<tr>
												<td>9</td>
												<td>Prognosis</td>
												<td><?php echo $ic12; ?></td>
											</tr>
											<tr>
												<td>10</td>
												<td>Alternatif lain dan Risiko
												</td>
												<td><?php echo $ic13; ?></td>
											</tr>
											<tr>
												<td>11</td>
												<td>Pembiayaan
												</td>
												<td><?php echo $ic14; ?></td>
											</tr>
											<tr>
												<td>12</td>
												<td>Lain-lain
												</td>
												<td><?php echo $ic15; ?></td>
											</tr>
										</table>
									</div>
								</div>
							</td>
						</tr>


						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										penerimaan informasi/pemberi persetujuan *)
									</div>
									<div class="col-8">
										: 
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-6 text-center">
										<br>
										Dengan ini menyatakan bahwa saya telah menerangkan hal-hal di atas secara benar dan jelas dan memberikan kesempatan untuk bertanya dan/atau berdiskusi.
										<br>
										Tanda tangan,
										dokter
										<br>
										<?php 
										if ($ic1){
											$penanggungjawab='Lembar Inform Consent ini telah ditandatangani oleh dokter :'.$ic1.'pada tanggal:'.$tgl;
												// echo "<center><img alt='testing' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$pernyataanpasien&choe=UTF-8'/></center>";

											QRcode::png($penanggungjawab, "image.png", "L", 2, 2);   
											echo "<center><img src='image.png'></center>";

											echo $ic1;
										}

										?>
									</div>

									<div class="col-6 text-center">
										<br>
										Dengan ini menyatakan bahwa saya telah menerima informasi sebagaimana di atas yang saya beri tanda/paraf di kolom kanannya, dan telah memahaminya.
										<br>
										Tanda tangan,
										pasien/wali
										<br>
										<br />
										<?php  
										if($ic24){
											echo " <img src='$ic24' height='100' width='100'>";
											echo "<br><br>";
											echo $ic3;

										}
										?>
									</div>

								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-12">
										*) Bila pasien tidak kompeten atau tidak mau menerima informasi, maka penerima informasi adalah wali atau keluarga terdekat
										<br><br>
									</div>
								</div>
							</td>
						</tr>
					</table>



					<div class="page-break"></div>


					<table width='100%' border='0'>

						<tr>
							<td>
								<div class="row">
									<div class="col-12 text-center">
										<b><?php if ($ic26=="Setuju"){echo "PERSETUJUAN TINDAKAN KEDOKTERAN";}else{ echo "PENOLAKAN TINDAKAN KEDOKTERAN"; }?></b>
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-8">
										Yang bertanda tangan dibawah ini saya,
									</div>
									<div class="col-4">
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Nama
									</div>
									<div class="col-8">: <?php echo $ic16; ?></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Tanggal lahir
									</div>
									<div class="col-8">: <?php echo $ic17; ?></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Alamat
									</div>
									<div class="col-8">: <?php echo $ic18; ?></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-12">
										Dengan ini menyatakan PERSETUJUAN untuk dilakukan tindakan  
									</div>
									<div class="col-8">: <?php echo $ic19; ?></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Terhadap saya
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
										Nama
									</div>
									<div class="col-8">: <?php echo $ic20; ?></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Tanggal lahir
									</div>
									<div class="col-8">: <?php echo $ic21; ?></div>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="row">
									<div class="col-4">
										Alamat
									</div>
									<div class="col-8">: <?php echo $ic22; ?></div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-12">
										Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya, termasuk risiko dan komplikasi yang mungkin timbul apabila tindakan tersebut tidak dilakukannya. 
										Saya bertanggungjawab secara penuh atas segala akibat yang mungkin timbul sebagai akibat tidak dilakukannya tindakan kedokteran tersebut.
									</div>
								</div>
							</td>
						</tr>

						<tr>
							<td>
								<div class="row">
									<div class="col-6">
										Pasien/wali Yang menyatakan<br>
										<input class="form-control form-control-sm" name="ic24" value="<?php echo $ic24;?>" id="" type="text" placeholder="" hidden> 
										<table class="table">
											<tr>
												<td>

													<?php 
													if($ic24){
														echo " <img src='$ic24' height='200' width='200'>";
														echo "<br><br>";
														echo $ic3;
													}
													?>
												</td>
											</tr>
										</table>
									</div>
									<div class="col-6">
										Saksi<br>
										<input class="form-control form-control-sm" name="ic25" value="<?php echo $ic25;?>" id="" type="text" placeholder="" hidden> 
										<table class="table">
											<tr>
												<td>

													<?php 
													if($ic25){
														echo " <img src='$ic25' height='200' width='200'>";
														echo "<br><br>";
														echo $ic27;
													}
													?>


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
									<div class="col-8">Tanggal Entry : <?php echo $tgl; ?></div>
								</div>
							</td>
						</tr>


						<tr>
							<td>
								<div class="row">
									<div class="col-12">
										<?php 
										if($ic23){
											echo " <img src='$ic23' height='200' width='200'>";
											echo "<br><br>";
										}
										?>
									</div>
								</div>
							</td>
						</tr>

					</table>

					<br>
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

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>