<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1";
$connectionInfo = array("Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect($serverName, $connectionInfo);
if (!$conn) die(print_r(sqlsrv_errors(), true));

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idluka = $row[2]; 

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


// $id = $_GET['id'];
$sql = "SELECT * FROM AsesmenLuka WHERE ID = $idluka";
$stmt = sqlsrv_query($conn, $sql, [$id]);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);


// Fungsi bantu
function is_checked($value, $arrayStr) {
	$array = explode(', ', $arrayStr ?? '');
	return in_array($value, $array) ? 'checked' : '';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Edit Asesmen Luka</title>
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

		<h4>Edit Asesmen Luka</h4>
		<form method="POST" action="" class="bg-white p-4 rounded shadow-sm">

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
			
			<input type="hidden" name="id" value="<?= $data['ID'] ?>">

			<div class="mb-3">
				<label>Tipe Luka</label><br>
				<?php
				$tipe = ["Operasi", "Trauma", "Bakar", "Ulkus", "Dekubitus"];
				foreach ($tipe as $val) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='tipe_luka[]' value='$val' class='form-check-input' ".is_checked($val, $data['Tipe_Luka'])."><label class='form-check-label'>$val</label></div>";
				}
				?>
			</div>

			<div class="row mb-3">
				<div class="col-sm-6">
					<label>Luas Luka</label>
					<input type="text" name="luas_luka" value="<?= $data['Luas_Luka'] ?>" class="form-control form-control-sm">
				</div>
				<div class="col-sm-6">
					<label>Balutan Luka</label>
					<input type="text" name="balutan_luka" value="<?= $data['Balutan_Luka'] ?>" class="form-control form-control-sm">
				</div>
			</div>

			<div class="mb-3">
				<label>Lokasi Luka</label>
				<textarea name="lokasi_luka" class="form-control form-control-sm"><?= $data['Lokasi_Luka'] ?></textarea>
			</div>

			<div class="mb-3">
				<label>Tipe Eksudat</label><br>
				<?php
				$eksudat = ["Serous", "Bloody", "Serosanguineous", "Purulent", "Foul Purulent", "Tidak ada"];
				foreach ($eksudat as $val) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='tipe_eksudat[]' value='$val' class='form-check-input' ".is_checked($val, $data['Tipe_Eksudat'])."><label class='form-check-label'>$val</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label>Jumlah Eksudat</label><br>
				<?php
				$jumlah = ["< 25%", "25%", "25-75%", "> 75%", "Infeksi kritis"];
				foreach ($jumlah as $val) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='jumlah_eksudat[]' value='$val' class='form-check-input' ".is_checked($val, $data['Jumlah_Eksudat'])."><label class='form-check-label'>$val</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label>Bau Luka</label><br>
				<?php
				$bau = ["Tidak ada bau", "Saat buka balutan", "Rembesan keluar", "Dekat pasien", "Masuk kamar", "Masuk ruangan lain"];
				foreach ($bau as $val) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='odour[]' value='$val' class='form-check-input' ".is_checked($val, $data['Odour'])."><label class='form-check-label'>$val</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label>Warna Dasar Luka</label><br>
				<?php
				$warna = ["Merah", "Kuning", "Hitam"];
				foreach ($warna as $val) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='warna_dasar[]' value='$val' class='form-check-input' ".is_checked($val, $data['Warna_Dasar'])."><label class='form-check-label'>$val</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label>Tepi Luka</label><br>
				<?php
				$tepi = ["Halus", "Kasar", "Tipis", "Tebal", "Bersih", "Kotor", "Lunak", "Keras"];
				foreach ($tepi as $val) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='tepi_luka[]' value='$val' class='form-check-input' ".is_checked($val, $data['Tepi_Luka'])."><label class='form-check-label'>$val</label></div>";
				}
				?>
			</div>

			<div class="mb-3">
				<label>Kulit Sekitar Luka</label><br>
				<?php
				$kulit = ["Utuh", "Bengkak", "Kemerahan", "Nyeri", "Keras", "Sianosis"];
				foreach ($kulit as $val) {
					echo "<div class='form-check form-check-inline'><input type='checkbox' name='kulit_sekitar[]' value='$val' class='form-check-input' ".is_checked($val, $data['Kulit_Sekitar'])."><label class='form-check-label'>$val</label></div>";
				}
				?>
			</div>

			<div class="row mb-3">
				<div class="col-sm-6">
					<label>Tanggal Kaji</label>
					<input type="date" name="tgl_kaji" value="<?= $data['Tgl_Kaji']->format('Y-m-d') ?>" class="form-control form-control-sm">
				</div>
				<div class="col-sm-6">
					<label>Jam Kaji</label>
					<input type="time" name="jam_kaji" value="<?= $data['Jam_Kaji']->format('H:i') ?>" class="form-control form-control-sm">
				</div>
			</div>

			<div class="mb-3">
				<label>Nama Perawat</label>
				<input type="text" name="nama_perawat" value="<?= $data['Nama_Perawat'] ?>" class="form-control form-control-sm">
			</div>

			<div class="text-end">
				<button type="submit" class="btn btn-success btn-sm">Simpan Perubahan</button>
				<a href="list_asesmen_luka.php" class="btn btn-secondary btn-sm">Batal</a>
			</div>
		</form>
	</div>
</body>
</html>

<?php 


$id = $_POST['id'];
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

$sql = "UPDATE AsesmenLuka SET
Nama=?, JK=?, Tgl_Lahir=?, NIK=?, No_RM=?, Tipe_Luka=?, Luas_Luka=?, Balutan_Luka=?, Lokasi_Luka=?,
Tipe_Eksudat=?, Jumlah_Eksudat=?, Odour=?, Warna_Dasar=?, Tepi_Luka=?, Kulit_Sekitar=?,
Tgl_Kaji=?, Jam_Kaji=?, Nama_Perawat=? WHERE ID=?";

$params = [
	$nama, $jk, $tgl_lahir, $nik, $no_rm, $tipe_luka, $luas_luka, $balutan_luka, $lokasi_luka,
	$tipe_eksudat, $jumlah_eksudat, $odour, $warna_dasar, $tepi_luka, $kulit_sekitar,
	$tgl_kaji, $jam_kaji, $nama_perawat,
	$id
];

$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt) {
	echo "<div style='padding:20px;'>Data berhasil diperbarui. <a href='asesmen_luka.php'>Kembali</a></div>";
} else {
	die(print_r(sqlsrv_errors(), true));
}
sqlsrv_close($conn);
?>
