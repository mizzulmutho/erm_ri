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

$qu="SELECT noreg,norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];

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
</head>
<body onload="document.myForm.pasien_mcu?.focus();">

	<div class="container-fluid">

		<form method="POST" name='myForm' enctype="multipart/form-data">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h4><i class="bi bi-clipboard-pulse"></i> Detail Observasi Harian Pasien</h4>
				<div>
					<a href='observasi.php?id=<?php echo $id."|".$user; ?>' class='btn btn-warning btn-sm'>
						<i class="bi bi-x-circle"></i> Close
					</a>
					<a href='' class='btn btn-success btn-sm'>
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>

			<?php include "header_soap.php"; ?>

			<?php
			// if(intval($total_score) == 0){
			// 	$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			// 	$bgcolor='';
			// 	$ket_ews='Sangat rendah<br>Perawat jaga melakukan monitor setiap shift';
			// }else if (intval($total_score) >= 1 and intval($total_score) <= 4 ){
			// 	$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			// 	$bgcolor='#90EE90';
			// 	$ket_ews='Rendah<br>Perawat jaga melakukan monitor setiap 4-6 jam <br> dan menilai apakah perlu untuk meningkatkan frekuensi monitoring';
			// }else if(intval($total_score) > 2 and intval($total_score) < 5){
			// 	$score = "<font size='5px' color='black'><b>$total_score</b></font>";
			// 	$bgcolor='#FAFAD2';		
			// 	$ket_ews='Sedang<br>Perawat jaga melakukan monitor tiap 1 jam <br> dan melaporkan ke dr jaga dan mempersiapkan jika mengalami perburukan kondisi pasien';
			// }else{
			// 	$score = "<font size='5px' color='black'><b>$total_score</b></font>";	
			// 	$bgcolor='#FF6347';
			// 	$ket_ews='Tinggi<br>Perawat, tim emergency, DPJP melakukan tatalaksana kegawatan, observasi tiap 30 menit/ setiap saat. <br> Aktifkan tim code blue bila terjadi cardiac arrest, transfer ke ruang ICU';
			// }
			?>

			<div class="table-responsive">
				<table class="table table-sm table-bordered text-center align-middle">
					<thead class="table-dark">
						<tr>
							<th>Kategori</th>
							<th>Nilai</th>
							<th>Tindakan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="bg-white">Sangat Rendah</td>
							<td>0</td>
							<td>Perawat jaga melakukan monitor setiap shift</td>
						</tr>
						<tr>
							<td class="bg-success text-white">Rendah</td>
							<td>&ge; 1 &amp; &le; 4</td>
							<td>
								Perawat jaga melakukan monitor setiap 4-6 jam 
								dan menilai apakah perlu untuk meningkatkan frekuensi monitoring
							</td>
						</tr>
						<tr>
							<td class="bg-warning">Sedang</td>
							<td>&ge; 5 &amp; &le; 6</td>
							<td>
								Perawat jaga melakukan monitor tiap 1 jam
								dan melaporkan ke dr jaga dan mempersiapkan jika terjadi perburukan kondisi pasien
							</td>
						</tr>
						<tr>
							<td class="bg-danger text-white">Tinggi</td>
							<td>&ge; 7</td>
							<td>
								Perawat, tim emergency, DPJP melakukan tatalaksana kegawatan, observasi tiap 30 menit / setiap saat.<br>
								Aktifkan tim code blue bila terjadi cardiac arrest, transfer ke ruang ICU.
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="table-responsive">
				<table class="table table-sm table-bordered table-sm align-middle">
					<thead class="table-light">
						<tr>
							<th rowspan="3">No</th>
							<th rowspan="3">Edit</th>
							<th rowspan="3">Hapus</th>
							<th rowspan="3">Tgl</th>
							<th rowspan="3">Jam</th>
							<th colspan="8">EWS</th>
							<th rowspan="3">Total EWS</th>
							<th rowspan="3">Petugas</th>
						</tr>
						<tr>
							<th rowspan="2">Kesadaran</th>
							<th rowspan="2">GCS</th>
							<th rowspan="2">Tensi</th>
							<th rowspan="2">Suhu</th>
							<th rowspan="2">Nadi</th>
							<th rowspan="2">RR</th>
							<th rowspan="2">SpO2</th>
							<th rowspan="2">Oksigen</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$q1 = "SELECT TOP(50)*, 
						CONVERT(VARCHAR, tglinput, 23) as tglinput,
						CONVERT(VARCHAR, tglinput, 24) as jam  
						FROM ERM_RI_OBSERVASI 
						WHERE noreg='$noreg' and ob1 <> ''
						ORDER BY id DESC";
						$hasil1  = sqlsrv_query($conn, $q1);
						$nox=1;           
						while ($data1 = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   
							$total_score = $data1['total_ews'];
							if(intval($total_score) == 0){
								$score = "<font size='4px' color=''><b>$total_score</b></font>";
								$bgcolor='';
								$ket_ews='Sangat rendah';
							}else if (intval($total_score) >= 1 and intval($total_score) <= 4 ){
								$score = "<font size='4px' color=''><b>$total_score</b></font>";
								$bgcolor='#90EE90';
								$ket_ews='Rendah';
							}else if(intval($total_score) >= 5 and intval($total_score) <= 6){
								$score = "<font size='4px' color=''><b>$total_score</b></font>";
								$bgcolor='#FAFAD2';		
								$ket_ews='Sedang';
							}else{
								$score = "<font size='4px' color=''><b>$total_score</b></font>";	
								$bgcolor='#FF6347';
								$ket_ews='Tinggi';
							}

							echo "<tr>
							<td>$nox</td>
							<td><a href='e_observasi.php?id=$id|$user|{$data1['id']}' class='text-primary'><i class='bi bi-pencil-square'></i></a></td>
							<td><a href='d_observasi.php?id=$id|$user|{$data1['id']}' class='text-danger'><i class='bi bi-trash'></i></a></td>
							<td>{$data1['tglinput']}</td>
							<td>{$data1['jam']}</td>
							<td>{$data1['ob1']}</td>
							<td>{$data1['ob6']}</td>
							<td>{$data1['td_sistolik']}/{$data1['td_diastolik']}</td>
							<td>{$data1['suhu']}</td>
							<td>{$data1['nadi']}</td>
							<td>{$data1['pernafasan']}</td>
							<td>{$data1['spo2']}</td>
							<td>{$data1['ob7']}</td>
							<td align='center' style='background-color:{$bgcolor};'><span>{$score}<br><b>{$ket_ews}</b></span></td>
							<td>{$data1['userinput']}</td>
							</tr>";
							$nox++;
						}
						?>
					</tbody>
				</table>
			</div>
		</form>

	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>