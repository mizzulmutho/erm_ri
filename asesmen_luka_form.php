<?php

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qrPerawat = "SELECT NamaUser FROM ROLERSPGENTRY.dbo.tblusererm WHERE user1 = '$user'";
$hqrPerawat = sqlsrv_query($conn, $qrPerawat);
$dhqrPerawat = sqlsrv_fetch_array($hqrPerawat, SQLSRV_FETCH_ASSOC);
$namaPerawat = $dhqrPerawat['NamaUser'];

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

?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Form Asesmen Luka</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
	<div class="container my-4">
		<br>
		<a href='asesmen_luka.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
		&nbsp;&nbsp;
		<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
		<br><br>
		<h4 class="mb-3"><i class="bi bi-clipboard-heart"></i> Asesmen Luka</h4>
		<form method="POST" action="" class="bg-white p-3 rounded shadow-sm">
			<div class="mb-3 row">

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

			</div>

			<hr>

			<div class="mb-3">
				<label class="form-label">Tipe Luka</label><br>
				<?php
				$tipeLuka = ["Operasi", "Trauma", "Bakar", "Ulkus", "Dekubitus"];
				foreach ($tipeLuka as $tipe) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='tipe_luka[]' value='$tipe' class='form-check-input'><label class='form-check-label'>$tipe</label></div>";
				}
				?>
			</div>

			<div class="mb-3 row">
				<div class="col-sm-6">
					<label class="form-label">Luas Luka</label>
					<input type="text" name="luas_luka" class="form-control form-control-sm">
				</div>
				<div class="col-sm-6">
					<label class="form-label">Balutan Luka</label>
					<input type="text" name="balutan_luka" class="form-control form-control-sm">
				</div>
			</div>

			<div class="mb-3">
				<label class="form-label">Lokasi Luka</label>
				<textarea name="lokasi_luka" rows="2" class="form-control form-control-sm"></textarea>
			</div>

			<div class="mb-3">
				<label class="form-label">Tipe Eksudat</label><br>
				<?php
				$eksudat = ["Serous", "Bloody", "Serosanguineous", "Purulent", "Foul Purulent", "Tidak ada"];
				foreach ($eksudat as $item) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='tipe_eksudat[]' value='$item' class='form-check-input'><label class='form-check-label'>$item</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label class="form-label">Jumlah Eksudat</label><br>
				<?php
				$jumlah = ["< 25%", "25%", "25-75%", "> 75%", "Infeksi kritis"];
				foreach ($jumlah as $item) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='jumlah_eksudat[]' value='$item' class='form-check-input'><label class='form-check-label'>$item</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label class="form-label">Bau Luka</label><br>
				<?php
				$bau = ["Tidak ada bau", "Saat buka balutan", "Rembesan keluar", "Dekat pasien", "Masuk kamar", "Masuk ruangan lain"];
				foreach ($bau as $item) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='odour[]' value='$item' class='form-check-input'><label class='form-check-label'>$item</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label class="form-label">Warna Dasar Luka</label><br>
				<?php
				foreach (["Merah", "Kuning", "Hitam"] as $w) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='warna_dasar[]' value='$w' class='form-check-input'><label class='form-check-label'>$w</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label class="form-label">Tepi Luka</label><br>
				<?php
				$tepi = ["Halus", "Kasar", "Tipis", "Tebal", "Bersih", "Kotor", "Lunak", "Keras"];
				foreach ($tepi as $t) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='tepi_luka[]' value='$t' class='form-check-input'><label class='form-check-label'>$t</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label class="form-label">Kulit Sekitar Luka</label><br>
				<?php
				$kulit = ["Utuh", "Bengkak", "Kemerahan", "Nyeri", "Keras", "Sianosis"];
				foreach ($kulit as $k) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='kulit_sekitar[]' value='$k' class='form-check-input'><label class='form-check-label'>$k</label></div>";
				}
				?>
			</div>

			<div class="mb-3 row">
				<div class="col-sm-6">
					<label class="form-label">Tanggal Kaji</label>
					<input type="date" name="tgl_kaji" class="form-control form-control-sm">
				</div>
				<div class="col-sm-6">
					<label class="form-label">Jam Kaji</label>
					<input type="time" name="jam_kaji" class="form-control form-control-sm">
				</div>
			</div>
			<div class="mb-3">
				<label class="form-label">Nama Perawat</label>
				<input type="text" name="nama_perawat" value='<?php echo $namaPerawat;?>' class="form-control form-control-sm">
			</div>

			<div class="text-end">
				<button type="submit" name="simpan" class="btn btn-sm btn-primary"><i class="bi bi-save"></i> Simpan</button>
			</div>
		</form>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// proses_asesmen.php

if (!$conn) {
	die(print_r(sqlsrv_errors(), true));
}

if (isset($_POST["simpan"])) {
// Ambil data dari form
	$nama = $_POST['nama'];
	$jk = $_POST['jk'];
	$tgl_lahir = $_POST['tgl_lahir'];
	$nik = $_POST['nik'];
	$no_rm = $_POST['no_rm'];
	$tipe_luka = implode(", ", $_POST['tipe_luka'] ?? []);
	$luas_luka = $_POST['luas_luka'];
	$balutan_luka = $_POST['balutan_luka'];
	$lokasi_luka = $_POST['lokasi_luka'];
	$tipe_eksudat = implode(", ", $_POST['tipe_eksudat'] ?? []);
	$jumlah_eksudat = implode(", ", $_POST['jumlah_eksudat'] ?? []);
	$odour = implode(", ", $_POST['odour'] ?? []);
	$warna_dasar = implode(", ", $_POST['warna_dasar'] ?? []);
	$tepi_luka = implode(", ", $_POST['tepi_luka'] ?? []);
	$kulit_sekitar = implode(", ", $_POST['kulit_sekitar'] ?? []);
	$tgl_kaji = $_POST['tgl_kaji'];
	$jam_kaji = $_POST['jam_kaji'];
	$nama_perawat = $_POST['nama_perawat'];

// Simpan ke database
	$sql = "INSERT INTO AsesmenLuka
	(Nama, JK, Tgl_Lahir, NIK, No_RM, Tipe_Luka, Luas_Luka, Balutan_Luka, Lokasi_Luka,
		Tipe_Eksudat, Jumlah_Eksudat, Odour, Warna_Dasar, Tepi_Luka, Kulit_Sekitar,
		Tgl_Kaji, Jam_Kaji, Nama_Perawat,noreg)
	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,'$noreg')";

	$params = [
		$nama, $jk, $tgl_lahir, $nik, $no_rm, $tipe_luka, $luas_luka, $balutan_luka, $lokasi_luka,
		$tipe_eksudat, $jumlah_eksudat, $odour, $warna_dasar, $tepi_luka, $kulit_sekitar,
		$tgl_kaji, $jam_kaji, $nama_perawat
	];

	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt) {
		echo "<div style='padding: 20px;'>Data berhasil disimpan. <a href='asesmen_luka.php?id=$id|$user'>Kembali</a></div>";
	} else {
		die(print_r(sqlsrv_errors(), true));
	}


}

?>