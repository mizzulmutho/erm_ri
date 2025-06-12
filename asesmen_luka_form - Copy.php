<?php
// Koneksi ke SQL Server
$serverName = "192.168.10.1";
$connectionOptions = [
	"Database" => "RSPGENTRY",
	"Uid" => "sa",
	"PWD" => "p@ssw0rd"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
	die(print_r(sqlsrv_errors(), true));
}

// Proses submit form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$pasien_id = $_POST["pasien_id"];
	$tanggal = $_POST["tanggal"];
	$lokasi = $_POST["lokasi"];
	$panjang = $_POST["panjang"];
	$lebar = $_POST["lebar"];
	$kedalaman = $_POST["kedalaman"];
	$tipe = $_POST["tipe"];
	$deskripsi = $_POST["deskripsi"];

	$sql = "INSERT INTO AsesmenLuka (pasien_id, tanggal, lokasi_luka, panjang_cm, lebar_cm, kedalaman_cm, tipe_luka, deskripsi)
	VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

	$params = [$pasien_id, $tanggal, $lokasi, $panjang, $lebar, $kedalaman, $tipe, $deskripsi];

	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt) {
		echo "<p>Data asesmen berhasil disimpan.</p>";
	} else {
		echo "<p>Gagal menyimpan data:</p>";
		print_r(sqlsrv_errors());
	}
}

// Cek jika dalam mode edit
$edit_id = null;
$edit_data = null;

if (isset($_GET['edit'])) {
	$edit_id = $_GET['edit'];
	$sql = "SELECT * FROM AsesmenLuka WHERE id = ?";
	$stmt = sqlsrv_query($conn, $sql, [$edit_id]);
	$edit_data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

// Proses update jika ada id tersembunyi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$id = $_POST["id"] ?? null;
	$params = [
		$_POST["pasien_id"],
		$_POST["tanggal"],
		$_POST["lokasi"],
		$_POST["panjang"],
		$_POST["lebar"],
		$_POST["kedalaman"],
		$_POST["tipe"],
		$_POST["deskripsi"]
	];

	if ($id) {
        // Update
		$sql = "UPDATE AsesmenLuka SET pasien_id=?, tanggal=?, lokasi_luka=?, panjang_cm=?, lebar_cm=?, kedalaman_cm=?, tipe_luka=?, deskripsi=? WHERE id=?";
		$params[] = $id;
		$stmt = sqlsrv_query($conn, $sql, $params);
		echo $stmt ? "<p>Data berhasil diperbarui.</p>" : print_r(sqlsrv_errors(), true);
	} else {
        // Insert
		$sql = "INSERT INTO AsesmenLuka (pasien_id, tanggal, lokasi_luka, panjang_cm, lebar_cm, kedalaman_cm, tipe_luka, deskripsi)
		VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = sqlsrv_query($conn, $sql, $params);
		echo $stmt ? "<p>Data berhasil disimpan.</p>" : print_r(sqlsrv_errors(), true);
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Form Asesmen Luka</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
	<div class="container mt-5">
		<div class="card shadow">
			<div class="card-header bg-primary text-white">
				<h4 class="mb-0"><?= $edit_data ? 'Edit Asesmen Luka' : 'Tambah Asesmen Luka' ?></h4>
			</div>
			<div class="card-body">

				<form method="post">
					<input type="hidden" name="id" value="<?= $edit_data['id'] ?? '' ?>">

					<div class="mb-3">
						<label class="form-label">ID Pasien</label>
						<input type="text" name="pasien_id" class="form-control" value="<?= $edit_data['pasien_id'] ?? '' ?>" required>
					</div>

					<div class="mb-3">
						<label class="form-label">Tanggal</label>
						<input type="date" name="tanggal" class="form-control" value="<?= isset($edit_data['tanggal']) ? $edit_data['tanggal']->format('Y-m-d') : '' ?>" required>
					</div>

					<div class="mb-3">
						<label class="form-label">Lokasi Luka</label>
						<input type="text" name="lokasi" class="form-control" value="<?= $edit_data['lokasi_luka'] ?? '' ?>" required>
					</div>

					<div class="row">
						<div class="col-md-4 mb-3">
							<label class="form-label">Panjang (cm)</label>
							<input type="number" step="0.01" name="panjang" class="form-control" value="<?= $edit_data['panjang_cm'] ?? '' ?>">
						</div>
						<div class="col-md-4 mb-3">
							<label class="form-label">Lebar (cm)</label>
							<input type="number" step="0.01" name="lebar" class="form-control" value="<?= $edit_data['lebar_cm'] ?? '' ?>">
						</div>
						<div class="col-md-4 mb-3">
							<label class="form-label">Kedalaman (cm)</label>
							<input type="number" step="0.01" name="kedalaman" class="form-control" value="<?= $edit_data['kedalaman_cm'] ?? '' ?>">
						</div>
					</div>

					<div class="mb-3">
						<label class="form-label">Tipe Luka</label>
						<select name="tipe" class="form-select">
							<?php
							$tipe_options = ["Luka Bakar", "Luka Sayat", "Ulkus Dekubitus", "Lainnya"];
							foreach ($tipe_options as $tipe) {
								$selected = (isset($edit_data['tipe_luka']) && $edit_data['tipe_luka'] == $tipe) ? "selected" : "";
								echo "<option value='$tipe' $selected>$tipe</option>";
							}
							?>
						</select>
					</div>

					<div class="mb-3">
						<label class="form-label">Deskripsi</label>
						<textarea name="deskripsi" rows="4" class="form-control"><?= $edit_data['deskripsi'] ?? '' ?></textarea>
					</div>

					<button type="submit" class="btn btn-success"><?= $edit_data ? 'Update' : 'Simpan' ?></button>
					<a href="asesmen_luka.php" class="btn btn-secondary">Kembali</a>
				</form>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
