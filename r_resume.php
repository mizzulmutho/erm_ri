<?php 
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$kodedept	= '9100';
$nama	= 'Pasien 1';
$kelamin	= 'Lak-laki';
$nik	= 'PGM11111';
$alamatpasien	= 'Gresik';
$kota	= 'Gresik';
$kodekel	= '20';
$telp	= '081....';
$tmptlahir	= 'Gresik';
$tgllahir	= '01/01/1988';
$jenispekerjaan	= 'Swasta';
$jabatan	= '';
$umur =  '30';
$norm='260587';

$tglmasuk	= '29/12/2023';
$kamar	= 'Kamar Kelas 1';

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

</head> 

<div class="container">

	<body onload="document.myForm.pasien_mcu.focus();">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<br>
				<br>
<!-- 				<div class="row">
					<div class="col-12 text-center bg-success text-white"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
				</div>
			-->				
			<div class="row">
			</div>

			<div class="row">
				<div class="col-6">
					<h5><b>RUMAH SAKIT PETROKIMIA GRESIK</b></h5>
					Gresik
				</div>
				<div class="col-6">
					<?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?>
					<?php echo 'L/P : '.$kelamin.'<br> UMUR : '.$umur.'<br> ALAMAT : '.$alamatpasien; ?>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-12 text-center">
					<b>RINGKASAN PASIEN PULANG RAWAT INAP</b><br>
					INPATIENT DISCHARGE SUMMARY (MEDICAL RESUME)
				</div>
			</div>

			<div class="row">
				<div class="col-12 bg-success text-white">
					<b>Diisi Oleh Dokter</b><br>
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					Ruang Perawatan
				</div>
				<div class="col-3">
					: <?php echo $kamar; ?>
				</div>
				<div class="col-3">
					Tgl. MRS
				</div>
				<div class="col-3">
					: <?php echo $tglmasuk; ?>
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					Jumlah Hari Perawatan
				</div>
				<div class="col-3">
					:
				</div>
				<div class="col-3">
					Tgl. KRS
				</div>
				<div class="col-3">
					:
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					No. SEP
				</div>
				<div class="col-3">
					:
				</div>
				<div class="col-3">
					Tgl. Dijamin/SEP
				</div>
				<div class="col-3">
					:
				</div>
			</div>

			<div class="row">
				<div class="col-3">
					No. Kartu
				</div>
				<div class="col-3">
					:
				</div>
				<div class="col-3">
					&nbsp;
				</div>
				<div class="col-3">
					&nbsp;
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>1. INDIKASI MRS</b>
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>2. ANAMNESE</b>
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Keluhan Keluhan
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Lama Keluhan
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Keluhan Lain
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Riwayat Penyakit / Pengobatan
				</div>
				<div class="col-8">
					:
				</div>

			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>3. HASIL PERIKSA</b>
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-4">
					<b>4. DIAGNOSIS AKHIR</b>
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Diagnosis Utama
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Diagnosis Sekunder
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp; a. Diagnosis Ko Morbiditas
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp; b. Diagnosis Komplikasi
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp; c. Diagnosis Penyebab
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Diagnosis PA
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>5. MASALAH UTAMA YANG DIHADARI & ALERGI</b>
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp; Keterangan
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>6. KONSULTASI (Tulis divisi yang Dikonsulkan)</b>
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp; Keterangan
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>7. TERAPI</b>
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Tindakan Medis Operatif
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&bull; Tindakan Non Operatif
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>8. RIWAYAT OBAT YANG TELAH DIBERIKAN SELAMA PERAWATAN</b>
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>9. TINDAK LANJUT/EDUKASI</b>
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-12">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;a. AKTIFITAS
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Jenis Aktivitas yang boleh dilakukan
				</div>
				<div class="col-8">
					:
				</div>								
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Alat bantu yang dapat digunakan
				</div>
				<div class="col-8">
					:
				</div>								
				<div class="col-12">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;b. EDUKASI PASIEN & KELUARGA
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Pemeriksaan penunjang diagnostik lanjutan
				</div>
				<div class="col-8">
					:
				</div>	
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Pengertian dan pemahaman akan efek samping dan interaksi obat
				</div>
				<div class="col-8">
					:
				</div>			
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Pengobatan yang dapat dilakukan jika timbul nyeri
				</div>
				<div class="col-8">
					:
				</div>	
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Pencegahan terhadap kekambuhan
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Lainnya
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-12">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c. PERAWATAN DI RUMAH
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Pengobatan yang dapat dilakukan
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Tanda dan gejala yang dilaporkan
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Nomor kontak yang harus dihubungi bila terdapat tanda dan gejala yang perlu dilaporkan
					No.Telp/HP
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-12">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d. DIET
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Anjuran pola makan
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Batasan makanan
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-12">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;e. SEGERA KEMBALI KE RUMAH SAKIT LANGSUNG KE GAWAT DARURAT, BILA TERJADI
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Tanda dan gejala
				</div>
				<div class="col-8">
					:
				</div>
				<div class="col-4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Penanganan darurat yang dapat dilakukan di rumah sebelum ke rumah sakit
				</div>
				<div class="col-8">
					:
				</div>	
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>10. DAFTAR OBAT-OBATAN YANG DIBAWAH PULANG</b>
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-4">
					<b>11. JADWAL KONTROL DOKTER</b>
				</div>
				<div class="col-8">
					:
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-12">
					<table border='1' bordercolor='green'>
						<tr>
							<td rowspan='3' width='20%'>Pernyataan Menerima Salinan Ringkasan Pulang (Wajin diisi)</td>
							<td width='20%'>Yang menerima :<br>(Nama Pasien / Wali*)</td>
							<td width='10%'>Tanda Tangan</td>
							<td>Yang menyerahkan :<br>(Nama Perawat)</td>
							<td width='10%'>Tanda Tangan</td>
							<td width='10%'>Tanggal</td>
						</tr>
						<tr height='80px'>
							<td></td>
							<td></td>
							<td rowspan="2"></td>
							<td rowspan="2"></td>
							<td rowspan="2"></td>
						</tr>
						<tr>
							<td colspan='2'>No Kontak</td>
						</tr>

					</table>
				</div>
			</div>
			<br><br><br>
		</font>
	</form>
</font>
</body>
</div>