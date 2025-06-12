<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$transfer = $row[2]; 


$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<script language="JavaScript" type="text/javascript">
		nextfield = "box1";
		netscape = "";
		ver = navigator.appVersion; len = ver.length;
		for(iln = 0; iln < len; iln++) if (ver.charAt(iln) == "(") break;
			netscape = (ver.charAt(iln+1).toUpperCase() != "C");

		function keyDown(DnEvents) {
			k = (netscape) ? DnEvents.which : window.event.keyCode;
			if (k == 13) {
				if (nextfield == 'done') return true;
				else {
					eval('document.myForm.' + nextfield + '.focus()');
					return false;
				}
			}
		}
		document.onkeydown = keyDown;
		if (netscape) document.captureEvents(Event.KEYDOWN|Event.KEYUP);
	</script>

	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>

	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

</head> 

<div class="container py-4">
	<body onload="document.myForm.pasien_mcu?.focus();">
		<form method="POST" name="myForm" action="" enctype="multipart/form-data" class="p-4 bg-light shadow rounded">
			
			<!-- Header Actions -->
			<div class="d-flex justify-content-start mb-3">
				<a href="index.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-warning btn-sm me-2">
					<i class="bi bi-x-circle-fill me-1"></i> Close
				</a>
				<a href="" class="btn btn-success btn-sm">
					<i class="bi bi-arrow-clockwise me-1"></i> Refresh
				</a>
			</div>

			<!-- Diagnosa Keperawatan Input -->
			<div class="mb-4">
				<label for="asuan_diagnosa" class="form-label fw-bold">
					<i class="bi bi-file-earmark-medical-fill me-2"></i>Input Diagnosa Keperawatan
				</label>
				<div class="input-group" style="max-width: 600px;">
					<select name="asuan_diagnosa" class="form-select">
						<option value="">-- Pilih Kode Diagnosis --</option>
						<?php
						$q = "SELECT DISTINCT diagnosa_keperawatan, diagnosa_nama
						FROM ERM_MASTER_ASUHANKEPERAWATAN
						WHERE JENIS IN('RENCANA ASUHAN KEPERAWATAN','RENCANA ASUHAN NEONATUS')  
						AND implementasi IS NOT NULL";
						$hasil = sqlsrv_query($conn, $q);			  
						while ($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)) {				  
							$selected = ($data['diagnosa_keperawatan'] == $diagnosa_keperawatan) ? 'selected' : '';
							echo "<option value='{$data['diagnosa_keperawatan']}' $selected>
							{$data['diagnosa_keperawatan']} - {$data['diagnosa_nama']}
							</option>";
						}
						?>
					</select>
					<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';">
						<i class="bi bi-save2-fill me-1"></i> Simpan
					</button>
				</div>
			</div>

			<!-- Tabel Diagnosa -->
			<div class="table-responsive">
				<table class="table table-bordered align-middle text-center">
					<thead class="table-secondary">
						<tr>
							<th>No</th>
							<th>Kode Diagnosa</th>
							<th>Nama Diagnosa</th>
							<th>Status Teratasi</th>
							<th>Aksi</th>
							<th>Ubah Status Teratasi</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$ql = "SELECT DISTINCT ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan, ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_nama,teratasi
						FROM ERM_ASUHAN_KEPERAWATAN
						INNER JOIN ERM_MASTER_ASUHANKEPERAWATAN
						ON ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan = ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_keperawatan
						WHERE noreg = '$noreg'";
						$hl = sqlsrv_query($conn, $ql);
						$no = 1;
						while ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)) {
							echo "<tr>
							<td>{$no}</td>
							<td>{$dl['diagnosa_keperawatan']}</td>
							<td>{$dl['diagnosa_nama']}</td>
							<td>{$dl['teratasi']}</td>
							<td>
							<a href='del_diagnosa_keperawatan.php?id={$id}|{$user}|{$noreg}|{$dl['diagnosa_keperawatan']}'
							class='btn btn-danger btn-sm'>
							<i class='bi bi-trash-fill'></i> Hapus
							</a>
							</td>
							<td>
							<a href='teratasi_diagnosa_keperawatan.php?id={$id}|{$user}|{$noreg}|{$dl['diagnosa_keperawatan']}'
							class='btn btn-warning btn-sm'>
							<i class='bi bi-journal-check'></i> Teratasi
							</a>
							<a href='bteratasi_diagnosa_keperawatan.php?id={$id}|{$user}|{$noreg}|{$dl['diagnosa_keperawatan']}'
							class='btn btn-warning btn-sm'>
							<i class='bi bi-journal-x'></i> Batalkan
							</a>

							</td>
							</tr>";
							$no++;
						}
						?>
					</tbody>
				</table>
			</div>

		</form>
	</body>
</div>

<?php


if (isset($_POST["simpan"])) {

	$lanjut = 'Y';
	$asuan_diagnosa = trim($_POST["asuan_diagnosa"]);

	$qu="SELECT isian as rencana FROM ERM_MASTER_ASUHANKEPERAWATAN where diagnosa_keperawatan like '%$asuan_diagnosa%'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$rencana = trim($d1u['rencana']);
	$data = stripslashes($rencana);
	$data = htmlspecialchars($data);

	//jika tidak ada insert
	$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana) values 
	('$id','$noreg','$asuan_diagnosa','$tgl','$user','$data')";         
	$hs = sqlsrv_query($conn,$q);


	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "  sukses !!!!<a href='diagnosa_keperawatan.php?id=$id|$user|$idrasuhan' class='btn btn-danger'> - Click to Refresh - </a>";

	// echo "
	// <script>
	// alert('".$eror."');
	// </script>
	// ";

	// header('Location: diagnosa_keperawatan.php?id=$id|$user|$idrasuhan');

	// echo "
	// <script>
	// top.location='i_asuhankeperawatan1.php?id=$id|$user|$idrasuhan';
	// </script>
	// ";


}
?>

