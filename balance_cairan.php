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

$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, NOKTP,
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
		@media print {
			@page {
				size: A4 landscape;
				margin: 0.5cm;
			}
		}

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
<body onload="document.myForm.pasien_mcu?.focus();">

	<div class="container-fluid">

		<form method="POST" name='myForm' enctype="multipart/form-data">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h4><i class="bi bi-clipboard-pulse"></i> Balance Cairan</h4>
				<div>
					<a href='index.php?id=<?php echo $id."|".$user; ?>' class='btn btn-warning btn-sm'>
						<i class="bi bi-x-circle"></i> Close
					</a>
					<a href='' class='btn btn-success btn-sm'>
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>

			<?php include "header_soap.php"; ?>

			<button onclick="printTable()" style="margin-bottom: 10px;">🖨️ Cetak Tabel Observasi Cairan</button>


			<div class="table-responsive">
				<table class="table table-bordered table-sm align-middle" id="tabel-cairan">
					<thead class="table-light">
						<tr>
							<th rowspan="3">No</th>
							<th rowspan="3">Tgl</th>
							<th rowspan="3">Jam</th>
							<th colspan="16">Cairan</th>
							<th rowspan="3">Keterangan</th>
							<th rowspan="3">Petugas</th>
						</tr>
						<tr>
							<th colspan="7">Input</th>
							<th colspan="8">Output</th>
							<th rowspan="3">Balance</th>
						</tr>
						<tr>
							<th>Infus</th>
							<th>Tetesan</th>
							<!-- <th>Jam</th> -->
							<th>Nama Infus</th>
							<th>Tranfusi</th>
							<th>Tetesan</th>
							<!-- <th>Jam</th> -->
							<th>Minum</th>
							<th>Total Input</th>

							<th>Muntah</th>
							<th>BAB</th>
							<th>Urine</th>
							<th>IWL</th>
							<th>NGT</th>
							<th>Drain</th>
							<th>Pendarahan</th>
							<th>Total Output</th>

						</tr>
					</thead>
					<tbody>
						<?php 
						$q1 = "SELECT TOP(50)*, 
						CONVERT(VARCHAR, tglinput, 23) as tglinput,
						CONVERT(VARCHAR, tglinput, 24) as jam  
						FROM ERM_RI_OBSERVASI_CAIRAN 
						WHERE noreg='$noreg'
						ORDER BY id DESC";
						$hasil1  = sqlsrv_query($conn, $q1);
						$nox=1;           
						while ($data1 = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   

							$input = floatval($data1['total_input']);
							$output = floatval($data1['total_output']);
							$selisih = $input - $output;

							if ($selisih > 0) {
								$status = "Excess";
							} elseif ($selisih < 0) {
								$status = "Defisit";
							} else {
								$status = "Seimbang";
							}


							echo "<tr>
							<td>$nox</td>
							<td>{$data1['tglinput']}</td>
							<td>{$data1['jam']}</td>
							<td>{$data1['ob12']}</td>
							<td>{$data1['ob13']}</td>
							<td>{$data1['ob29']}</td>
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
							<td>{$data1['ob27']}</td>
							<td><strong>$status</strong></td>
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