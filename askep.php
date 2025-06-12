<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
// $transfer = $row[2]; 


$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];


$ql1="SELECT  id,rencana,userid from ERM_ASUHAN_KEPERAWATAN where id_assesmen='$id' order by id desc";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$rencana = $d11['rencana'];
$rencana = html_entity_decode($rencana);

$userid = $d11['userid'];
$idrasuhan = $d11['id'];


if (isset($_POST["implementasi_rencana"])) {
	$idrasuhan = trim($_POST["idrasuhan"]);

	echo "
	<script>
	top.location='i_asuhankeperawatan.php?id=$id|$user|$idrasuhan';
	</script>
	";            
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>eRM-RI - Asuhan Keperawatan</title>
	<link rel="icon" href="favicon.ico">
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<script src="ckeditor/ckeditor.js"></script>
	<style>
		body {
			background-color: #f8f9fa;
			font-size: 14px;
		}
		.table thead {
			background-color: #343a40;
			color: white;
		}
		.card {
			margin-top: 20px;
		}
		.btn i {
			margin-right: 5px;
		}
	</style>
</head>
<body onload="document.myForm.pasien_mcu?.focus();">

	<div class="container-fluid mt-4">
		<!-- Header -->
		<?php include "header_soap.php"; ?>

		<!-- Form Card -->
		<div class="card shadow-sm">
			<div class="card-body">
				<form method="POST" name="myForm" action="" enctype="multipart/form-data">
					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="mb-0"><i class="bi bi-clipboard-heart"></i> Asuhan Keperawatan</h5>
						<div>
							<a href='r_asuhankeperawatan.php?id=<?php echo $id . "|" . $user; ?>' class='btn btn-warning btn-sm'>
								<i class="bi bi-x-circle"></i> Close
							</a>
							<a href='' class='btn btn-success btn-sm'>
								<i class="bi bi-arrow-clockwise"></i> Refresh
							</a>
							<a href='form_asuhankeperawatan_baru.php?id=<?php echo $id . "|" . $user; ?>' class='btn btn-primary btn-sm'>
								<i class="bi bi-file-earmark-plus"></i> Tambah
							</a>
							<a href='m_asuhankeperawatan.php?id=<?php echo $id . "|" . $user; ?>' class='btn btn-secondary btn-sm'>
								<i class="bi bi-gear"></i> Master
							</a>
						</div>
					</div>

					<!-- Table -->
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead class="table-dark">
								<tr>
									<th>No</th>
									<th>Kode Diagnosa</th>
									<th>Nama Diagnosa</th>
									<th colspan="2" class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$ql = "
								SELECT DISTINCT ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan, ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_nama
								FROM ERM_ASUHAN_KEPERAWATAN
								INNER JOIN ERM_MASTER_ASUHANKEPERAWATAN
								ON ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan = ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_keperawatan
								WHERE noreg = '$noreg'
								";
								$hl = sqlsrv_query($conn, $ql);
								$no = 1;
								while ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)) {
									echo "
									<tr>
									<td>{$no}</td>
									<td>{$dl['diagnosa_keperawatan']}</td>
									<td>{$dl['diagnosa_nama']}</td>
									<td class='text-center'>
									<a href='askep_detail.php?id=$id|$user|$noreg|{$dl['diagnosa_keperawatan']}' class='btn btn-outline-success btn-sm'>
									<i class='bi bi-calendar-plus'></i> Intervensi
									</a>
									</td>
									<td class='text-center'>
									<a href='implementasi.php?id=$id|$user|$noreg|{$dl['diagnosa_keperawatan']}' class='btn btn-outline-info btn-sm'>
									<i class='bi bi-calendar-check-fill'></i> Implementasi
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
			</div>
		</div>
	</div>

	<script>
		CKEDITOR.replace('editor1', {
			width: "100%",
			height: "500px"
		});
	</script>
</body>
</html>


<?php

if (isset($_POST["edit_rencana"])) {
	$idrasuhan = trim($_POST["idrasuhan"]);
	$rencana = trim($_POST["rencana"]);

	if(!empty($rencana)){
		$q  = "update ERM_ASUHAN_KEPERAWATAN set rencana='$rencana' where id=$idrasuhan";         
		$hs = sqlsrv_query($conn,$q);

		if($hs){
			$eror = "Success";
		}else{
			$eror = "Gagal Insert";

		}

		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";

		// echo "
		// <script>
		// top.location='index.php?id='$id|$user';
		// </script>
		// ";            

	}

}

if (isset($_POST["simpan_rencana"])) {

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

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";
	header('Location: i_asuhankeperawatan1.php?id=$id|$user|$idrasuhan');

	// echo "
	// <script>
	// top.location='i_asuhankeperawatan1.php?id=$id|$user|$idrasuhan';
	// </script>
	// ";


}
?>

