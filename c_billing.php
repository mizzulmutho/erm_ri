<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tanggal    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$getid = $_GET["id"];
$row = explode('|',$getid);
$id = $row[0]; 
$user = $row[1]; 
$nosep = $row[2]; 
$noreg = $row[3]; 
$file_to_display = $row[4]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

if ($sbu == "RSPG") {
  $consID = "30161"; // PROD
  $consSecret = "4uP1D898FE";
  $user_key = "1b2256e07eb21a343f934eb522bb6a59";
  $user_key_antrol= "8a4acfe012329f428ced3f2cc57dd419";
  $ppkPelayanan = "1302R002";
  $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
  $alamat = "
  Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik
  <br>
  IGD : 031-99100118 Telp : 031-3978658<br>
  Email : sbu.rspg@gmail.com
  ";
  $logo = "logo/rspg.png";
} else if ($sbu === "GRAHU") {
  $consID = "9497"; //PROD
  $consSecret = "3aV1C3CB13";
  $user_key = "cb3d247a6b9443d68f9567e0d86fb422";
  $user_key_antrol= "77ce0cdd4d786c2e0029a45f9e97759d";
  $ppkPelayanan = "0205R013";
  $nmrs = "RUMAH SAKIT GRHA HUSADA";
  $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
  $logo = "logo/grahu.png";
} else if ($sbu === "DRIYO") {
  $consID = "3279"; //PROD
  $consSecret = "6uR2F891A4";
  $user_key = "918bda20e3056ae0d4167e698d8adb83";
  $user_key_antrol= "f9b587583c0232c2bd36d27aad8f9856";
  $ppkPelayanan = "0205R014";
  $nmrs = "RUMAH SAKIT DRIYOREJO";
  $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
  $logo = "logo/driyo.png";
} else {
	$consID = "";
	$consSecret = "";
	$user_key = "";
	$ppkPelayanan = "";
}


date_default_timezone_set('UTC');
$timestamp = time();

$data = $consID.'&'.$timestamp;
$key = $consID.$consSecret.$timestamp;

$signature = hash_hmac('sha256', $data, $consSecret, true);
$encodedSignature = base64_encode($signature);

$url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest"; //PROD
$url_antrol = "https://apijkn.bpjs-kesehatan.go.id/antreanrs";

$tglentry = gmdate("d-m-Y H:i:s", time()+60*60*7);

$datetime       = gmdate("Y-m-d H:i:s", time()+60*60*7);
$date       = gmdate("Y-m-d", time()+60*60*7);

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$tglinput=gmdate("Y-m-d", time()+60*60*7);

$bulan  =substr($tglsekarang,5,2);
$tanggal=substr($tglsekarang,8,3);
$tahun  =substr($tglsekarang,0,4);



?>

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Rincian Biaya Perawatan</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>


	<div class="container my-4">

		<table class="table table-bordered table-striped align-middle text-center">
				<!-- <thead class="table-primary">
					<tr>
						<th>No</th>
						<th>No. Reg</th>
						<th>Tgl Daftar</th>
						<th>No. RM</th>
						<th>NIK</th>
						<th>Nama Pasien</th>
						<th>JK</th>
					</tr>
				</thead> -->
				<tbody>

					<?php
					$q = "SELECT DISTINCT ARM_REGISTER.NOREG,ARM_REGISTER.NORM, CONVERT(VARCHAR, TANGGAL, 103) as TANGGAL 
					FROM ARM_REGISTER WHERE noreg like '%$noreg%'";

					$hasil = sqlsrv_query($conn, $q);
					$no = 1;
					$totalx = 0;

					while ($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)) {
						$grand_total = 0;
						$q2 = "SELECT norm,kodedept,nik,nama,kelamin,tlp FROM Afarm_MstPasien WHERE norm='{$data['NORM']}'";
						$hasil2 = sqlsrv_query($conn, $q2);
						$data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);
						$nama = str_replace("'", "", $data2['nama']);
						?>
						

						<!-- Tindakan Table -->
						<tr>
							<td colspan="7">
								<img src="<?php echo $logo; ?>" alt="" width="100">
								<h6 class="text-primary">RINCIAN BIAYA PERAWATAN</h6><?php echo $nmrs; ?>
								<table class="table table-sm table-bordered">
									<thead class="table-secondary text-center">
										<tr>
											<th>Tanggal</th>
											<th>Nama Tindakan</th>
											<th>Jumlah</th>
											<th>Harga</th>
											<th>Total</th>
											<th>Unit Layanan</th>
											<th>SBU</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$q2 = "SELECT CONVERT(VARCHAR, TANGGAL, 103) AS TANGGAL, JUMLAH, TARIP, JUMLAH * TARIP AS TOTAL_HARGA, KODE, NAMAUNIT, KET 
										FROM ARM_PERIKSA_DETIL 
										INNER JOIN Afarm_UnitLayanan ON UNITLAYANAN = KODEUNIT 
										WHERE NOREG='{$data['NOREG']}'";
										$hasil2 = sqlsrv_query($conn, $q2);
										while ($data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC)) {
											$q3 = "SELECT NAMA FROM AFARM_TINDAKAN WHERE KODE='{$data2['KODE']}'";
											$hasil3 = sqlsrv_query($conn, $q3);
											$data3 = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);
											$nama_tindakan = $data3['NAMA'];
											?>
											<tr>
												<td><?= $data2['TANGGAL']; ?></td>
												<td><?= $nama_tindakan; ?></td>
												<td class="text-end"><?= number_format($data2['JUMLAH'], 0, ",", "."); ?></td>
												<td class="text-end"><?= number_format($data2['TARIP'], 0, ",", "."); ?></td>
												<td class="text-end"><?= number_format($data2['TOTAL_HARGA'], 0, ",", "."); ?></td>
												<td><?= $data2['NAMAUNIT']; ?></td>
												<td><?= $data2['KET']; ?></td>
											</tr>
											<?php
											$grand_total += $data2['TOTAL_HARGA'];
											$totalx += $data2['TOTAL_HARGA'];
										}
										?>
										<tr class="table-warning">
											<td colspan="4" class="text-center">Sub Total Layanan</td>
											<td class="text-end"><?= number_format($grand_total, 0, ",", "."); ?></td>
											<td colspan="2"></td>
										</tr>

										<?php
										$q2 = "
										SELECT     CONVERT(VARCHAR, AFARM_PENJUALAN_DETAIL_1.TANGGAL, 103) AS TANGGAL, AFARM_PENJUALAN_DETAIL_1.JUMLAH, 
										AFARM_PENJUALAN_DETAIL_1.HRGJUAL, AFARM_PENJUALAN_DETAIL_1.JUMLAH * AFARM_PENJUALAN_DETAIL_1.HRGJUAL AS TOTAL_HARGA, 
										AFARM_PENJUALAN_DETAIL_1.KODEBARANG, Afarm_UnitLayanan.KET, AFarm_Penjualan.UNITLAYANAN, Afarm_UnitLayanan.NAMAUNIT
										FROM         AFarm_Penjualan_Detail AS AFARM_PENJUALAN_DETAIL_1 INNER JOIN
										AFarm_Penjualan ON AFARM_PENJUALAN_DETAIL_1.NOREG = AFarm_Penjualan.NOREG AND 
										AFARM_PENJUALAN_DETAIL_1.NORESEP = AFarm_Penjualan.NORESEP INNER JOIN
										Afarm_UnitLayanan ON AFarm_Penjualan.UNITLAYANAN = Afarm_UnitLayanan.KODEUNIT
										WHERE    (AFARM_PENJUALAN_DETAIL_1.NOREG = '$data[NOREG]')
										";		
										$hasil2 = sqlsrv_query($conn, $q2);
										$no2=1;

										while 	($data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC)){
											?>
											<tr>
												<td width='10%'><?php echo $data2[TANGGAL]; ?></td>

												<?php
												$q4		= "select NAMABARANG from  AFarm_MstObat WHERE KODEBARANG='$data2[KODEBARANG]'";
												$hasil4  = sqlsrv_query($conn, $q4);			  

												$data4	= sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC);				  
												$nama_barang	= $data4[NAMABARANG];
												?>	
												<td align='left'width='40%'><?php echo $nama_barang; ?></td>
												<td align='right'width='7%'>
													<?php echo number_format($data2[JUMLAH], 0, ",", ".");?></td>
													<td align='right'width='8%'>
														<?php echo number_format($data2[HRGJUAL], 0, ",", ".");?></td>
														<td align='right'width='8%'>
															<?php echo number_format($data2[TOTAL_HARGA], 0, ",", ".");?></td>
															<td colspan=2 align='center'><?php echo $data2[NAMAUNIT];?></td>
														</tr>
														<?php
														$grand_totalx += $data2[TOTAL_HARGA];
														$totalx += $data2[TOTAL_HARGA];
													}
													?>

													<tr class="table-warning">
														<td colspan="4" class="text-center">Sub Total Farmasi</td>
														<td class="text-end"><?= number_format($grand_totalx, 0, ",", "."); ?></td>
														<td colspan="2"></td>
													</tr>

													<?php
													$q4 = "
													SELECT     AFarm_PenjualanUnit.NOREG, AFarm_PenjualanUnit.UNITLAYANAN, AFarm_PenjualanUnit_Detail.JUMLAH, AFarm_PenjualanUnit_Detail.HRGJUAL, 
													AFarm_PenjualanUnit_Detail.JUMLAH * AFarm_PenjualanUnit_Detail.HRGJUAL AS TOTAL_HARGA, AFarm_PenjualanUnit_Detail.KODEBARANG, CONVERT(VARCHAR, 
														AFarm_PenjualanUnit_Detail.TANGGAL, 103) AS TANGGAL, AFarm_PenjualanUnit.CUSTNO, AFarm_PenjualanUnit.DEPT, Afarm_UnitLayanan_2.NAMAUNIT
													FROM         AFarm_PenjualanUnit INNER JOIN
													AFarm_PenjualanUnit_Detail ON AFarm_PenjualanUnit.NOREG = AFarm_PenjualanUnit_Detail.NOREG INNER JOIN
													Afarm_UnitLayanan AS Afarm_UnitLayanan_2 ON AFarm_PenjualanUnit.UNITLAYANAN = Afarm_UnitLayanan_2.KODEUNIT
													WHERE     AND (AFarm_PenjualanUnit_Detail.NOREG = '$data[NOREG]')
													";		
													$hasil4 = sqlsrv_query($conn, $q4);
													$no4=1;

													while 	($data4 = sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC)){
														?>
														<tr>
															<td width='10%'><?php echo $data4[TANGGAL]; ?></td>

															<?php
															$q44		= "select NAMABARANG from  AFarm_MstObat WHERE KODEBARANG='$data4[KODEBARANG]'";
															$hasil44  = sqlsrv_query($conn, $q44);			  

															$data44	= sqlsrv_fetch_array($hasil44, SQLSRV_FETCH_ASSOC);				  
															$nama_barang	= $data44[NAMABARANG];
															?>	
															<td align='left'width='40%'><?php echo $nama_barang; ?></td>
															<td align='right'width='7%'>
																<?php echo number_format($data4[JUMLAH], 0, ",", ".");?></td>
																<td align='right'width='8%'>
																	<?php echo number_format($data4[HRGJUAL], 0, ",", ".");?></td>
																	<td align='right'width='8%'>
																		<?php echo number_format($data4[TOTAL_HARGA], 0, ",", ".");?></td>
																		<td colspan=2 align='center'><?php echo $data4[NAMAUNIT];?></td>
																	</tr>
																	<?php
																	$grand_total4x += $data4[TOTAL_HARGA];
																	$totalx += $data4[TOTAL_HARGA];
																}
																?>

																<tr class="table-warning">
																	<td colspan="4" class="text-center">Sub Total Obat Unit</td>
																	<td class="text-end"><?= number_format($grand_total4x, 0, ",", "."); ?></td>
																	<td colspan="2"></td>
																</tr>

																<tr class="table-warning">
																	<td colspan="4" class="text-center">Grand Total</td>
																	<td class="text-end"><?= number_format($totalx, 0, ",", "."); ?></td>
																	<td colspan="2"></td>
																</tr>


															</tbody>
														</table>
													</td>
												</tr>

												<!-- TODO: Tambahkan bagian Farmasi & Penjualan Unit di sini dengan pola yang sama -->
											<?php } ?>
										</tbody>
									</table>
								</div>


							</body>
							</html>