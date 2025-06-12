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

			<div class="table-responsive">
				<table class="table table-bordered table-sm align-middle">
					<thead class="table-light">
						<tr>
							<th rowspan="3">No</th>
							<th rowspan="3">Edit</th>
							<th rowspan="3">Hapus</th>
							<th rowspan="3">Tgl</th>
							<th rowspan="3">Jam</th>
							<th colspan="10">EWS</th>
							<th rowspan="3">Total EWS</th>
							<th colspan="14">Cairan</th>
							<th rowspan="3">GDA</th>
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
							<th rowspan="2">BB</th>
							<th rowspan="2">TB</th>
							<th colspan="5">Input</th>
							<th colspan="8">Output</th>
							<th rowspan="2">Balance</th>
						</tr>
						<tr>
							<th>Infus</th>
							<th>Transfusi</th>
							<th>Makan</th>
							<th>Minum</th>
							<th>Total</th>
							<th>Muntah</th>
							<th>Peradangan</th>
							<th>Urine</th>
							<th>BAB</th>
							<th>IWL</th>
							<th>NGT</th>
							<th>Drain</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$q1 = "SELECT TOP(50)*, 
						CONVERT(VARCHAR, tglinput, 23) as tglinput,
						CONVERT(VARCHAR, tglinput, 24) as jam  
						FROM ERM_RI_OBSERVASI 
						WHERE noreg='$noreg' 
						ORDER BY id DESC";
						$hasil1  = sqlsrv_query($conn, $q1);
						$nox=1;           
						while ($data1 = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   
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
							<td>{$data1['ob9']}</td>
							<td>{$data1['ob10']}</td>
							<td>{$data1['ob11']}</td>
							<td>{$data1['ob12']}</td>
							<td>{$data1['ob13']}</td>
							<td>{$data1['ob18']}</td>
							<td>{$data1['ob19']}</td>
							<td>{$data1['total_input']}</td>
							<td>{$data1['ob20']}</td>
							<td>{$data1['ob26']}</td>
							<td>{$data1['ob22']}</td>
							<td>{$data1['ob21']}</td>
							<td>{$data1['ob23']}</td>
							<td>{$data1['ob24']}</td>
							<td>{$data1['ob25']}</td>
							<td>{$data1['total_output']}</td>
							<td>{$data1['ob27']}</td>
							<td>{$data1['ob28']}</td>
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