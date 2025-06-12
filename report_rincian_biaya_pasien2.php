<?php
INCLUDE_ONCE("koneksi.php");

session_start();
$username = $_SESSION['username'];

$noreg	= $_POST ['noreg'];
$jenis	= $_POST ['jenis'];
?>

<html>
<head>
	<title>Detail Biaya Tindkan dan Farmasi RS GRHA HUSADA</title>
	<link rel="shortcut icon" href="rspg.ico" />
</head>

<body>

	<table width="100%" cellspacing="0" border="0" >		
		<tr><td colspan="10" align='center'>Rincian Biaya Tindakan & Farmasi <br>RS GRHA HUSADA
		</td></tr>
	</table>  

	<?php// Kolom Induk ...?>
	
	<table border=0 align='center' style="border-collapse:collapse;" width='80%'>
		<tr>
			<td align='center'>No</td>
			<td align='center'>NOREG</td>
			<td align='center'>TGL DAFTAR</td>
			<td align='center'>NORM</td>
			<td align='center'>NIK</td>
			<td align='center'>NAMA PASIEN</td>
			<td align='center'>JK</td>
		</tr>
		<?php
//CARI REGISTER PASIEN
		if ($jenis=='RJ'){
			$q = " 
			SELECT     DISTINCT ARM_REGISTER.NOREG,ARM_REGISTER.NORM, CONVERT(VARCHAR, TANGGAL, 103) as TANGGAL 
			FROM         ARM_REGISTER
			WHERE     
			norm = '$noreg'
			";		
		}
		if($jenis=='RI'){
			$q = " 
			SELECT     DISTINCT ARM_REGISTER.NOREG,ARM_REGISTER.NORM, CONVERT(VARCHAR, TANGGAL, 103) as TANGGAL 
			FROM         ARM_REGISTER
			WHERE     
			norm = '$noreg'
			";		
		}

		$hasil = sqlsrv_query($conn, $q);
		$no=1;
		$totalx=0;

		while 	($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)){
			$grand_total=0;
			?>
			<tr>
				<td align='center' bgcolor='FFFFCC'><?php echo $no; ?></td>
				<td align='center' bgcolor='FFFFCC'><?php echo $data[NOREG]; ?></td>
				<td align='center' bgcolor='FFFFCC'><?php echo $data[TANGGAL]; ?></td>

				<?php
				$q2		= "select norm,kodedept,nik,nama,kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
				jabatan from Afarm_MstPasien where norm='$data[NORM]'";
				$hasil2  = sqlsrv_query($conn, $q2);			  

				$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
				$kodedept	= $data2[kodedept];
				$nama	= $data2[nama];
				$tlp	= $data2[tlp];
				$nama = str_replace("'","",$nama);
				?>

				<td align='center' bgcolor='FFFFCC'><?php echo $data[NORM]; ?></td>
				<td align='center' bgcolor='FFFFCC'><?php echo $data2[nik]; ?></td>
				<td align='center' bgcolor='FFFFCC'><?php echo $nama; ?><br><?php echo $tlp; ?></td>
				<td align='center' bgcolor='FFFFCC'><?php echo $data2[kelamin]; ?></td>	
			</tr>

			<tr>
				<td colspan='4'>
					<?php
					$q2x		= 
					"
					SELECT DISTINCT ARM_PERIKSA_DETIL.KODEDOKTER, Afarm_DOKTER.NAMA
					FROM         ARM_PERIKSA_DETIL INNER JOIN
					Afarm_DOKTER ON ARM_PERIKSA_DETIL.KODEDOKTER = Afarm_DOKTER.KODEDOKTER
					WHERE     (ARM_PERIKSA_DETIL.NOREG = '$noreg') AND (ARM_PERIKSA_DETIL.KODEDOKTER <> '')
					";
					$hasil2x  = sqlsrv_query($conn, $q2x);			  

					$data2x	= sqlsrv_fetch_array($hasil2x, SQLSRV_FETCH_ASSOC);				  
					$namadokterx	= $data2x[NAMA];
					$namadokterx = str_replace("'","",$namadokterx);
					?>
					Dokter :&nbsp;<?php echo $namadokterx;?>
				</td>
			</tr>
			
			<tr><td colspan=7>
				<table border=1 style="border-collapse:collapse;" width=100%>
					<tr>
						<td align='center' width='10%'>Tanggal</td>
						<td align='center'width='40%'>Nama Tindakan</td>
						<td align='center' width=''>Jumlah</td>
						<td align='center' width=''>Harga</td>
						<td align='center' width=''>Total</td>
						<td align='center' width=''>Unit Layanan</td>
						<td align='center' width=''>SBU</td>
					</tr>
					<?php
//CARI TINDAKAN
//UNTUK RAWAT JALAN...
					if ($jenis=='RJ'){
						$q2 = "
						SELECT     CONVERT(VARCHAR, ARM_PERIKSA_DETIL.TANGGAL, 103) AS TANGGAL, ARM_PERIKSA_DETIL.JUMLAH, ARM_PERIKSA_DETIL.TARIP, 
						ARM_PERIKSA_DETIL.JUMLAH * ARM_PERIKSA_DETIL.TARIP AS TOTAL_HARGA, ARM_PERIKSA_DETIL.KODE, Afarm_UnitLayanan.NAMAUNIT, 
						Afarm_UnitLayanan.KET
						FROM         ARM_PERIKSA_DETIL INNER JOIN
						Afarm_UnitLayanan ON ARM_PERIKSA_DETIL.UNITLAYANAN = Afarm_UnitLayanan.KODEUNIT
						WHERE     NOREG='$data[NOREG]' and KET='GRAHU' and ARM_PERIKSA_DETIL.JENISBAYAR='1'
						";		
					}
//UNTUK RAWAT INAP...
					if ($jenis=='RI'){
						$q2 = "
						SELECT     CONVERT(VARCHAR, ARM_PERIKSA_DETIL.TANGGAL, 103) AS TANGGAL, ARM_PERIKSA_DETIL.JUMLAH, ARM_PERIKSA_DETIL.TARIP, 
						ARM_PERIKSA_DETIL.JUMLAH * ARM_PERIKSA_DETIL.TARIP AS TOTAL_HARGA, ARM_PERIKSA_DETIL.KODE, Afarm_UnitLayanan.NAMAUNIT, 
						Afarm_UnitLayanan.KET
						FROM         ARM_PERIKSA_DETIL INNER JOIN
						Afarm_UnitLayanan ON ARM_PERIKSA_DETIL.UNITLAYANAN = Afarm_UnitLayanan.KODEUNIT
						WHERE     NOREG='$data[NOREG]'
						";		
					}
					$hasil2 = sqlsrv_query($conn, $q2);
					$no2=1;

					while 	($data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC)){
						?>
						<tr>
							<td width='10%'><?php echo $data2[TANGGAL]; ?></td>

							<?php
							$q3		= "select NAMA from AFARM_TINDAKAN WHERE KODE='$data2[KODE]'";
							$hasil3  = sqlsrv_query($conn, $q3);			  

							$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
							$nama_tindakan	= $data3[NAMA];
							?>	
							<td align='left'width='40%'><?php echo $nama_tindakan; ?></td>
							<td align='right' width=''>
								<?php echo number_format($data2[JUMLAH], 0, ",", ".");?></td>
								<td align='right' width=''>
									<?php echo number_format($data2[TARIP], 0, ",", ".");?></td>
									<td align='right' width=''>
										<?php echo number_format($data2[TOTAL_HARGA], 0, ",", ".");?></td>
										<td align='center' width=''><?php echo $data2[NAMAUNIT];?></td>	
										<td align='center' width=''><?php echo $data2[KET];?></td>		
									</tr>
									<?php
									$grand_total += $data2[TOTAL_HARGA];
									$totalx += $data2[TOTAL_HARGA];

								}
								?>
								<tr>
									<td colspan='4' align="center">Sub Total Layanan</td>
									<td align='right' ><?php echo number_format($grand_total, 0, ",", ".");?></td>
								</tr>
							</table>
						</td></tr>

						<tr><td colspan=7>
							<table border=1 style="border-collapse:collapse;" width=100%>

								<?php
								if ($jenis=='RI'){
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
								}
								if ($jenis=='RJ'){
									$q2 = "
									SELECT     CONVERT(VARCHAR, AFARM_PENJUALAN_DETAIL_1.TANGGAL, 103) AS TANGGAL, AFARM_PENJUALAN_DETAIL_1.JUMLAH, 
									AFARM_PENJUALAN_DETAIL_1.HRGJUAL, AFARM_PENJUALAN_DETAIL_1.JUMLAH * AFARM_PENJUALAN_DETAIL_1.HRGJUAL AS TOTAL_HARGA, 
									AFARM_PENJUALAN_DETAIL_1.KODEBARANG, Afarm_UnitLayanan.KET, AFarm_Penjualan.UNITLAYANAN, Afarm_UnitLayanan.NAMAUNIT
									FROM         AFarm_Penjualan_Detail AS AFARM_PENJUALAN_DETAIL_1 INNER JOIN
									AFarm_Penjualan ON AFARM_PENJUALAN_DETAIL_1.NOREG = AFarm_Penjualan.NOREG AND 
									AFARM_PENJUALAN_DETAIL_1.NORESEP = AFarm_Penjualan.NORESEP INNER JOIN
									Afarm_UnitLayanan ON AFarm_Penjualan.UNITLAYANAN = Afarm_UnitLayanan.KODEUNIT
									WHERE     (Afarm_UnitLayanan.KET = 'GRAHU') AND (AFARM_PENJUALAN_DETAIL_1.NOREG = '$data[NOREG]') AND (AFARM_PENJUALAN_DETAIL_1.JENISBAYAR='1')
									";		
								}
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


											<tr>
												<td colspan='4' align="center">Sub Total Farmasi</td>
												<td align='right' ><?php echo number_format($grand_totalx, 0, ",", ".");?></td>
											</tr>

										</table>
									</td></tr>

									<?php //untuk OBAT UNIT?>
									<tr><td colspan=7>
										<table border=1 style="border-collapse:collapse;" width=100%>

											<?php
											if ($jenis=='RI'){
												$q4 = "
												SELECT     AFarm_PenjualanUnit.NOREG, AFarm_PenjualanUnit.UNITLAYANAN, AFarm_PenjualanUnit_Detail.JUMLAH, AFarm_PenjualanUnit_Detail.HRGJUAL, 
												AFarm_PenjualanUnit_Detail.JUMLAH * AFarm_PenjualanUnit_Detail.HRGJUAL AS TOTAL_HARGA, AFarm_PenjualanUnit_Detail.KODEBARANG, CONVERT(VARCHAR, 
													AFarm_PenjualanUnit_Detail.TANGGAL, 103) AS TANGGAL, AFarm_PenjualanUnit.CUSTNO, AFarm_PenjualanUnit.DEPT, Afarm_UnitLayanan_2.NAMAUNIT
												FROM         AFarm_PenjualanUnit INNER JOIN
												AFarm_PenjualanUnit_Detail ON AFarm_PenjualanUnit.NOREG = AFarm_PenjualanUnit_Detail.NOREG INNER JOIN
												Afarm_UnitLayanan AS Afarm_UnitLayanan_2 ON AFarm_PenjualanUnit.UNITLAYANAN = Afarm_UnitLayanan_2.KODEUNIT
												WHERE     AND (AFarm_PenjualanUnit_Detail.NOREG = '$data[NOREG]')
												";		
											}
											if ($jenis=='RJ'){
												$q4 = "
												SELECT     AFarm_PenjualanUnit.NOREG, AFarm_PenjualanUnit.UNITLAYANAN, AFarm_PenjualanUnit_Detail.JUMLAH, AFarm_PenjualanUnit_Detail.HRGJUAL, 
												AFarm_PenjualanUnit_Detail.JUMLAH * AFarm_PenjualanUnit_Detail.HRGJUAL AS TOTAL_HARGA, AFarm_PenjualanUnit_Detail.KODEBARANG, CONVERT(VARCHAR, 
													AFarm_PenjualanUnit_Detail.TANGGAL, 103) AS TANGGAL, AFarm_PenjualanUnit.CUSTNO, AFarm_PenjualanUnit.DEPT, Afarm_UnitLayanan_2.NAMAUNIT
												FROM         AFarm_PenjualanUnit INNER JOIN
												AFarm_PenjualanUnit_Detail ON AFarm_PenjualanUnit.NOREG = AFarm_PenjualanUnit_Detail.NOREG INNER JOIN
												Afarm_UnitLayanan AS Afarm_UnitLayanan_2 ON AFarm_PenjualanUnit.UNITLAYANAN = Afarm_UnitLayanan_2.KODEUNIT
												WHERE     (Afarm_UnitLayanan_2.KET = 'GRAHU') AND (AFarm_PenjualanUnit_Detail.NOREG = '$data[NOREG]') AND (AFarm_PenjualanUnit_Detail.JENISBAYAR = '1')
												";		
											}
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


														<tr>
															<td colspan='4' align="center">Sub Total Obat Unit</td>
															<td align='right' ><?php echo number_format($grand_total4x, 0, ",", ".");?></td>
														</tr>

														<tr>
															<td colspan='4' align="center">Grand Total</td>
															<td align='right' ><?php echo number_format($totalx, 0, ",", ".");?></td>
														</tr>

													</table>
												</td></tr>


												<?php
												$no=$no+1;
} //END WHILE

?>


</table>

</body>
</html>