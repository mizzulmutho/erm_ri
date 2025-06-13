<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

session_start();
// echo '<pre>'; print_r($_SESSION); echo '</pre>';

$_SESSION['success'];

// $success_message = '';
// if (isset($_SESSION['success'])) {
// 	$success_message = $_SESSION['success'];
//     unset($_SESSION['success']); // Hapus setelah ditampilkan sekali
// }


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

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1, ARM_REGISTER.CUSTNO
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = $d1u['KODEUNIT'];
$KET1 = $d1u['KET1'];
$NORM = $d1u['NORM'];
$CUSTNO = $d1u['CUSTNO'];

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
	$nmrs = "RUMAH SAKIT PETROKIMIA GRESIK DRIYOREJO";
	$alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
	$logo = "logo/driyo.png";
};

$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
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
$noktp =  $data2[NOKTP];

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Observasi Harian Pasien</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
	<style>
		body {
			padding: 20px;
			background-color: #f8f9fa;
		}
		h4 {
			margin-bottom: 20px;
		}
		.table thead th {
			vertical-align: middle;
			text-align: center;
		}
	</style>

	<script>
		function printTable() {
			const table = document.getElementById('tabel-cairan').outerHTML;

			const headerHTML = `
			<div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
			<div style="width: 50%; display: flex;">
			<div style="margin-right: 15px;">
			<img src='<?php echo $logo; ?>' width='100px'>
			</div>
			<div>
			<h3 style="margin: 0;"><b><?php echo $nmrs; ?></b></h3>
			<small><?php echo $alamat; ?></small>
			</div>
			</div>
			<div style="width: 45%; font-size: 13px;">
			<div>NIK: <?php echo $noktp; ?></div>
			<div>Nama Lengkap: <?php echo $nama; ?>, NORM: <?php echo $norm; ?></div>
			<div>Tgl Lahir: <?php echo $tgllahir; ?>, Umur: <?php echo $umur; ?></div>
			<div>Jenis Kelamin: <?php echo $kelamin; ?></div>
			<div>Alamat: <?php echo $alamatpasien; ?></div>
			</div>
			</div>
			`;

			const newWin = window.open('', '', 'width=900,height=600');
			newWin.document.write(`
				<html>
				<head>
				<title>Cetak Observasi Cairan</title>
				<style>
				body {
					font-family: Arial, sans-serif;
					padding: 20px;
				}
				table {
					width: 100%;
					border-collapse: collapse;
					table-layout: auto;
				}
				th, td {
					border: 1px solid #000;
					padding: 6px;
					text-align: center;
					word-wrap: break-word;
					white-space: pre-wrap;
					font-size: 12px;
				}
				th {
					background-color: #f2f2f2;
				}
				td:nth-child(21), th:nth-child(21) {
					min-width: 150px;
					max-width: 200px;
				}
				</style>
				</head>
				<body>
				${headerHTML}
				<h4 style="text-align:center;">Laporan Observasi Cairan</h4>
				${table}
				</body>
				</html>
				`);
			newWin.document.close();
			newWin.focus();
			newWin.print();
			newWin.close();
		}
	</script>

	<style>
		/* Tambahkan style untuk pembeda kolom input dan output */
		th.input-header {
			background-color: #e0f7fa !important;  /* Biru muda */
			border: 2px solid #007BFF !important;
			font-weight: bold;
		}

		th.output-header {
			background-color: #fbe9e7 !important;  /* Merah muda */
			border: 2px solid #e53935 !important;
			font-weight: bold;
		}

		th, td {
			border: 1px solid #999 !important;
			text-align: center;
			vertical-align: middle;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}
	</style>

	<style>
		th.col-tgl,
		td.col-tgl {
			width: 120px; /* Atau sesuaikan, misalnya 150px */
			min-width: 100px;
			max-width: 200px;
			white-space: nowrap;
		}
	</style>


</head>
<body onload="document.myForm.pasien_mcu?.focus();">

	<div class="container-fluid">

		<div class="d-flex justify-content-between align-items-center mb-3">
			<h4><i class="bi bi-clipboard-pulse"></i> Detail Observasi Balance Cairan Pasien </h4>
			<div>
				<a href='observasi_cairan2.php?id=<?php echo $id."|".$user; ?>' class='btn btn-warning btn-sm'>
					<i class="bi bi-x-circle"></i> Close
				</a>
				<a href='' class='btn btn-success btn-sm'>
					<i class="bi bi-arrow-clockwise"></i> Refresh
				</a>
			</div>
		</div>

		<?php include "header_soap.php"; ?>


		<form method="get" class="row align-items-end g-2 mb-3">
			<input type="hidden" name="id" value="<?= $id.'|'.$user ?>">
			<input type="hidden" name="user" value="<?= $user ?>">

			<!-- Input Tanggal & Jam -->
			<div class="col-md-8">
				<div class="d-flex flex-wrap gap-2">

					<div class="d-flex align-items-center gap-2">
						<label for="filter_tanggal" class="form-label mb-0">Tanggal Awal</label>
						<input type="date" name="filter_tanggal" id="filter_tanggal" class="form-control" style="max-width: 140px;" 
						value="<?= $_GET['filter_tanggal'] ?? '' ?>">					
						<label for="filter_tanggal_akhir" class="form-label mb-0">Tanggal Akhir</label>
						<input type="date" name="filter_tanggal_akhir" id="filter_tanggal_akhir" class="form-control" style="max-width: 140px;" 
						value="<?= $_GET['filter_tanggal_akhir'] ?? '' ?>">
					</div>

					<?php
					$filter_jam_awal = $_GET['filter_jam_awal'] ?? '';
					$filter_jam_awal = substr($filter_jam_awal, 0, 5); 

					$filter_jam_akhir = $_GET['filter_jam_akhir'] ?? '';
					$filter_jam_akhir = substr($filter_jam_akhir, 0, 5); 
					?>

					<div class="d-flex align-items-center gap-2">
						<label for="filter_jam_awal" class="form-label mb-0">Jam Awal</label>
						<input type="time" id="filter_jam_awal" name="filter_jam_awal" class="form-control" step="60"
						style="max-width: 120px;" value="<?= $filter_jam_awal ?>">

						<label for="filter_jam_akhir" class="form-label mb-0">Jam Akhir</label>
						<input type="time" id="filter_jam_akhir" name="filter_jam_akhir" class="form-control" step="60"
						style="max-width: 120px;" value="<?= $filter_jam_akhir ?>">
					</div>
				</div>
			</div>


			<div class="col-md-8">
				<button type="submit" class="btn btn-primary" style="min-width: 140px;">Filter</button>
				<a href="listobservasi_cairan.php?id=<?= $id.'|'.$user ?>&user=<?= $user ?>" class="btn btn-secondary" style="min-width: 140px;">Reset</a>
				<!-- Tombol Toggle -->
				<button type="button" class="btn btn-secondary" id="toggleTerapiBtn" onclick="toggleTerapiInfus()" style="min-width: 140px;">+ Tambah Terapi Cairan Infus</button>
				<button class="btn btn-secondary" onclick="printTable()">🖨️ Print</button>			

			</div>

		</form>

		<?php
		$filter_tanggal = $_GET['filter_tanggal'] ?? '';
		$filter_tanggal_akhir = $_GET['filter_tanggal_akhir'] ?? '';
		$filter_jam_awal = $_GET['filter_jam_awal'] ?? '';
		$filter_jam_akhir = $_GET['filter_jam_akhir'] ?? '';



		if (!$filter_tanggal||!$filter_tanggal_akhir||!$filter_jam_awal||!$filter_jam_akhir) {
			echo '<div class="alert alert-info" role="alert">
			Silakan lakukan filter tanggal dan jam (jam awal & jam akhir) terlebih dahulu untuk menampilkan tombol Hitung Balance Cairan.
			</div>';
		} else {
			?>
			<form method="post" action="process_balance.php" id="balanceForm" class="mt-3">
				<input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
				<input type="hidden" name="user" value="<?= htmlspecialchars($user) ?>">
				<input type="hidden" name="noreg" value="<?= htmlspecialchars($noreg) ?>">
				<input type="hidden" name="filter_tanggal" value="<?= htmlspecialchars($filter_tanggal) ?>">
				<input type="hidden" name="filter_jam_awal" value="<?= htmlspecialchars($filter_jam_awal) ?>">
				<input type="hidden" name="filter_jam_akhir" value="<?= htmlspecialchars($filter_jam_akhir) ?>">
				<input type="hidden" name="filter_tanggal_akhir" value="<?= htmlspecialchars($filter_tanggal_akhir) ?>">
				<!-- <button type="submit" class="btn btn-success">Hitung Balance Cairan</button> -->
				<button type="button" onclick="submitBalance()" class="btn btn-success">Hitung Balance Cairan</button>
				<input type="hidden" name="keterangan" id="keterangan">
				<script>
					function submitBalance() {
						const checked = document.querySelectorAll('input[name="data_terpilih[]"]:checked');
						if (checked.length === 0) {
							alert("Pilih data observasi cairan terlebih dahulu.");
							return;
						}

						let ket = prompt("Masukkan keterangan balance cairan:");
						if (ket !== null && ket.trim() !== "") {
							document.getElementById("keterangan").value = ket.trim();

							let form = document.getElementById("balanceForm");

							document.querySelectorAll('#balanceForm input[name="data_terpilih[]"]').forEach(e => e.remove());

							checked.forEach(c => {
								let input = document.createElement("input");
								input.type = "hidden";
								input.name = "data_terpilih[]";
								input.value = c.value;
								form.appendChild(input);
							});
							form.submit();
						} else {
							alert("Keterangan harus diisi untuk menyimpan balance cairan.");
						}
					}
				</script>

			</form>
			<?php
		}

		?>

		<!-- Form Terapi Cairan Infus -->
		<div id="formTerapiInfus" style="display: none;">
			<form method="post" action="simpan_terapi_infus.php">
				<input type="hidden" name="id2" value="<?= htmlspecialchars($id) ?>">
				<input type="hidden" name="user2" value="<?= htmlspecialchars($user) ?>">
				<input type="hidden" name="noreg2" value="<?= htmlspecialchars($noreg) ?>">

				<div class="mb-3">
					<label for="terapi" class="form-label">Terapi Cairan Infus</label>
					<textarea name="terapi" id="terapi" class="form-control" rows="3" required placeholder="Masukkan catatan terapi cairan infus..."></textarea>
				</div>

				<button type="submit" class="btn btn-success">Simpan Terapi</button>
			</form>
		</div>

		<script>
			function toggleTerapiInfus() {
				const form = document.getElementById("formTerapiInfus");
				const button = document.getElementById("toggleTerapiBtn");

				if (form.style.display === "none") {
					form.style.display = "block";
					button.innerText = "- Sembunyikan Form Terapi Cairan Infus";
					button.classList.remove("btn-info");
					button.classList.add("btn-warning");
				} else {
					form.style.display = "none";
					button.innerText = "+ Tambah Terapi Cairan Infus";
					button.classList.remove("btn-warning");
					button.classList.add("btn-info");
				}
			}
		</script>

		<br><br>	

		<div class="table-responsive">
			<table class="table table-bordered table-sm align-middle" id="tabel-cairan">
				<thead>
					<tr>
						<th rowspan="3">Pilih</th> <!-- Kolom baru -->
						<th rowspan="3">No</th>
						<th rowspan="3">Edit</th>
						<th rowspan="3">Hapus</th>
						<th rowspan="3" class="col-tgl">Tgl</th>
						<th rowspan="3">Jam</th>
						<th colspan="8" class="input-header">Input</th>
						<th colspan="8" class="output-header">Output</th>
						<th rowspan="3">Balance</th>
						<th rowspan="3">Petugas</th>
						<th rowspan="3">Keterangan</th>
					</tr>
					<tr>
						<th class="input-header">Nama Cairan Infus</th>
						<th class="input-header">Jumlah Cairan</th>
						<th class="input-header">Tetesan</th>
						<th class="input-header">Jenis Transfusi</th>
						<th class="input-header">Jumlah Transfusi</th>
						<th class="input-header">Tetesan</th>
						<th class="input-header">Minum</th>
						<th class="input-header">Total Input</th>
						<th class="output-header">Muntah</th>
						<th class="output-header">Feses</th>
						<th class="output-header">Urine</th>
						<th class="output-header">IWL</th>
						<th class="output-header">NGT</th>
						<th class="output-header">Drain</th>
						<th class="output-header">Pendarahan</th>
						<th class="output-header">Total Output</th>
					</tr>
				</thead>
				<tbody>
					<?php 

					$filter = "noreg='$noreg'";

					$tgl_awal = $_GET['filter_tanggal'] ?? '';
					$tgl_akhir = $_GET['filter_tanggal_akhir'] ?? '';

					if (!empty($tgl_awal) && !empty($tgl_akhir)) {

						$start = "$tgl_awal " . (!empty($jam_awal) ? $jam_awal : "00:00");
						$end = "$tgl_akhir " . (!empty($jam_akhir) ? $jam_akhir : "23:59");

						$filter .= " AND tglinput >= '$start' AND tglinput <= DATEADD(second, 59, '$end')";

   						 // Filter jam jika tersedia
						if (!empty($_GET['filter_jam_awal']) && !empty($_GET['filter_jam_akhir'])) {
							$jam_awal = $_GET['filter_jam_awal'];
							$jam_akhir = $_GET['filter_jam_akhir'];

    						// Jam awal <= waktu (jam dan menit dan detik)
    						// Jam akhir + 59 detik agar data sampai menit akhir masuk
							$filter .= " AND tglinput >= DATEADD(minute, DATEDIFF(minute, 0, '$tgl_awal $jam_awal'), 0)
							AND tglinput <= DATEADD(second, 59, DATEADD(minute, DATEDIFF(minute, 0, '$tgl_akhir $jam_akhir'), 0))";
						}


					}

					$q1 = "SELECT TOP(50)*, 
					CONVERT(VARCHAR, tglinput, 23) as tglinput,
					CONVERT(VARCHAR, tanggal_awal, 120) as tanggal_awal,
					CONVERT(VARCHAR, tanggal_akhir, 120) as tanggal_akhir,
					CONVERT(VARCHAR, tglinput, 24) as jam  
					FROM ERM_RI_OBSERVASI_CAIRAN 
					WHERE $filter
					ORDER BY ERM_RI_OBSERVASI_CAIRAN.tglinput desc,nomor_transaksi desc";

					$hasil1  = sqlsrv_query($conn, $q1);
					$nox=1;           
					
					while ($data1 = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   

						$tglinput = date('d-m-Y', strtotime($data1['tglinput']));
						$tanggal_awal = date('d-m-Y H:i', strtotime($data1['tanggal_awal']));
						$tanggal_akhir = date('d-m-Y H:i', strtotime($data1['tanggal_akhir']));
						$terapi = trim($data1['terapi'] ?? '');

						$balance = intval(trim($data1['ob27']));
						$balance_table = $data1['balance'];
						$nomor_transaksi = $data1['nomor_transaksi'];
						$rowClass='';

						//$rowClass = (!empty($balance_table) && !is_null($nomor_transaksi)) ? 'table-success' : '';

						// $balance = $_POST['balance'];

						if ($balance < 0) {
							$keterangan = "Defisit";
						} elseif ($balance > 0) {
							$keterangan = "Excess";
						} else {
							$keterangan = "Seimbang";
						}

						if (!is_null($balance_table) && $balance_table != '' && $balance_table != 0 && !is_null($nomor_transaksi)) {
							$editBtn = "<a href='e_observasi_cairan.php?id=$id|$user|{$data1['id']}' class='text-primary'><i class='bi bi-pencil-square'></i></a>";
							$deleteBtn = "<a href='d_observasi_cairan.php?id=$id|$user|{$data1['id']}' class='text-danger'><i class='bi bi-trash'></i></a>";
						} else {
							$editBtn = "<a href='e_observasi_cairan.php?id=$id|$user|{$data1['id']}' class='text-primary'><i class='bi bi-pencil-square'></i></a>";
							$deleteBtn = "<a href='d_observasi_cairan.php?id=$id|$user|{$data1['id']}' class='text-danger'><i class='bi bi-trash'></i></a>";
						}

						$userinput = trim($data1['userinput']);
						$qu="SELECT NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$userinput'";
						$h1u  = sqlsrv_query($conn, $qu);        
						$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
						$nmuserid = trim($d1u['NamaUser']);

						if (!empty($terapi)) {
							$colspan_terapi = 17; 
							echo "<tr class='table-warning'>
							<td><input type='checkbox' name='data_terpilih[]' value='{$data1['id']}'></td>
							<td>$nox</td>
							<td>$editBtn</td>
							<td>$deleteBtn</td>
							<td>{$tglinput}</td>
							<td>{$data1['jam']}</td>
							<td colspan='$colspan_terapi' style='text-align: left;'><strong>Terapi Cairan Infus :</strong> " . htmlspecialchars($terapi) . "</td>
							<td>{$nmuserid}</td>
							<td>{$data1['ob18']}</td>
							</tr>";
						}
						else if (isset($balance_table) and !empty($nomor_transaksi)) {
							$colspan = 18;

							echo "<tr class='table-success'>
							<td><input type='checkbox' name='data_terpilih[]' value='{$data1['id']}'></td>
							<td>$nox</td>
							<td>$editBtn</td>
							<td>$deleteBtn</td>
							<td colspan='$colspan' style='text-align: left;'><strong>Balance Cairan :</strong> $tanggal_awal s/d $tanggal_akhir</td>
							<td>{$balance}</td>
							<td>{$nmuserid}</td>
							<td>$keterangan</td>
							</tr>";
						} else {

							if(is_null($nomor_transaksi)){
								echo "<tr class='$rowClass'>
								<td><input type='checkbox' name='data_terpilih[]' value='{$data1['id']}'></td>
								<td>$nox</td>
								<td>$editBtn</td>
								<td>$deleteBtn</td>
								<td>{$tglinput}</td>
								<td>{$data1['jam']}</td>
								<td>{$data1['ob29']}</td>
								<td>{$data1['ob12']}</td>
								<td>{$data1['ob13']}</td>
								<td>{$data1['jtranfusi']}</td>
								<td>{$data1['ob15']}</td>
								<td>{$data1['ob16']}</td>
								<td>{$data1['ob19']}</td>
								<td>{$data1['total_input']}</td>
								<td>{$data1['ob20']}</td>
								<td>{$data1['ob21']}</td>
								<td>{$data1['ob22']}</td>
								<td>{$data1['ob23']}</td>
								<td>{$data1['ob24']}</td>
								<td>{$data1['ob25']}</td>
								<td>{$data1['ob26']}</td>
								<td>{$data1['total_output']}</td>
								<td>{$balance}</td>
								<td>{$nmuserid}</td>
								<td>{$data1['ob18']}</td>
								</tr>";
							}else{
								$rowClass = 'table-info';
								echo "<tr class='$rowClass'>
								<td><input type='checkbox' name='data_terpilih[]' value='{$data1['id']}'></td>
								<td>$nox</td>
								<td>$editBtn</td>
								<td>$deleteBtn</td>
								<td>{$tglinput}</td>
								<td>{$data1['jam']}</td>
								<td>{$data1['ob29']}</td>
								<td>{$data1['ob12']}</td>
								<td>{$data1['ob13']}</td>
								<td>{$data1['jtranfusi']}</td>
								<td>{$data1['ob15']}</td>
								<td>{$data1['ob16']}</td>
								<td>{$data1['ob19']}</td>
								<td>{$data1['total_input']}</td>
								<td>{$data1['ob20']}</td>
								<td>{$data1['ob21']}</td>
								<td>{$data1['ob22']}</td>
								<td>{$data1['ob23']}</td>
								<td>{$data1['ob24']}</td>
								<td>{$data1['ob25']}</td>
								<td>{$data1['ob26']}</td>
								<td>{$data1['total_output']}</td>
								<td>{$balance}</td>
								<td>{$nmuserid}</td>
								<td>{$data1['ob18']}</td>
								</tr>";
							}
						}

						$nox += 1;
					}
					?>
				</tbody>
			</table>

			<?php if (!empty($success_message)) : ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?= htmlspecialchars($success_message) ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			<?php endif; ?>


		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>