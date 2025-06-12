<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tanggal    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$getid = $_GET["id"];
$row = explode('|',$getid);
$id = $row[0]; 
$user = $row[1]; 
$nosep = $row[2]; 
$noreg = $row[3]; 
$file_to_display = $row[4]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

if ($sbu == "RSPG") {
  $consID = "30161"; // PROD
  $consSecret = "4uP1D898FE";
  $user_key = "1b2256e07eb21a343f934eb522bb6a59";
  $user_key_antrol= "8a4acfe012329f428ced3f2cc57dd419";
  $ppkPelayanan = "1302R002";
  $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
  $alamat = "
  Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik
  <br>
  IGD : 031-99100118 Telp : 031-3978658<br>
  Email : sbu.rspg@gmail.com
  ";
  $logo = "logo/rspg.png";
} else if ($sbu === "GRAHU") {
  $consID = "9497"; //PROD
  $consSecret = "3aV1C3CB13";
  $user_key = "cb3d247a6b9443d68f9567e0d86fb422";
  $user_key_antrol= "77ce0cdd4d786c2e0029a45f9e97759d";
  $ppkPelayanan = "0205R013";
  $nmrs = "RUMAH SAKIT GRHA HUSADA";
  $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
  $logo = "logo/grahu.png";
} else if ($sbu === "DRIYO") {
  $consID = "3279"; //PROD
  $consSecret = "6uR2F891A4";
  $user_key = "918bda20e3056ae0d4167e698d8adb83";
  $user_key_antrol= "f9b587583c0232c2bd36d27aad8f9856";
  $ppkPelayanan = "0205R014";
  $nmrs = "RUMAH SAKIT DRIYOREJO";
  $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
  $logo = "logo/driyo.png";
} else {
	$consID = "";
	$consSecret = "";
	$user_key = "";
	$ppkPelayanan = "";
}


date_default_timezone_set('UTC');
$timestamp = time();

$data = $consID.'&'.$timestamp;
$key = $consID.$consSecret.$timestamp;

$signature = hash_hmac('sha256', $data, $consSecret, true);
$encodedSignature = base64_encode($signature);

$url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest"; //PROD
$url_antrol = "https://apijkn.bpjs-kesehatan.go.id/antreanrs";

$tglentry = gmdate("d-m-Y H:i:s", time()+60*60*7);

$datetime       = gmdate("Y-m-d H:i:s", time()+60*60*7);
$date       = gmdate("Y-m-d", time()+60*60*7);

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$tglinput=gmdate("Y-m-d", time()+60*60*7);

$bulan  =substr($tglsekarang,5,2);
$tanggal=substr($tglsekarang,8,3);
$tahun  =substr($tglsekarang,0,4);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8">
	<title>Hasil Laborat</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
	<!-- DataTables Buttons -->
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
	<table border="1" width="100%">
		<tr>
			<td width="10%" align="right">
				<img src="<?php echo $logo; ?>" alt="" width="100"> 
			</td>
			<td colspan="3">
				<table width="100%">
					<tr>
						<td align="center">
							<font size="3">
								<b>HASIL LABORATORIUM & RADIOLOGI</b><br>
								<?php echo $nmrs; ?>
							</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<div class="container">
		<!-- <h2 class="mb-4">Riwayat Pemeriksaan Radiologi dan Laboratorium</h2> -->
		<div class="mb-3 row">
			<label class="col-sm-2 col-form-label">Nomor RM</label>
			<div class="col-sm-4">
				<input type="text" id="no_rm" class="form-control" value="<?php echo $norm;?>" readonly>
			</div>
		</div>

		<hr>
		<div id="hasil_riwayat"></div>
	</div>

	<!-- jQuery dan JS -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

	<script>
		$(document).ready(function () {
			var no_rm = $('#no_rm').val();
			if (no_rm === '') {
				$('#hasil_riwayat').html('<div class="alert alert-warning">Nomor RM tidak tersedia.</div>');
				return;
			}

			$('#hasil_riwayat').html('<div class="alert alert-info">Memuat data...</div>');

			$.ajax({
				url: 'riwayat_pemeriksaan2.php',
				type: 'POST',
				data: { no_rm: no_rm },
				success: function (res) {
					$('#hasil_riwayat').html(res);

					$('table.dataTable').DataTable({
						dom: 'Bfrtip',
						buttons: ['copy', 'excel', 'pdf', 'print'],
						pageLength: 20,
						lengthMenu: [[10, 20, 50, -1], [10, 20, 50, 'Semua']]
					});
				},
				error: function () {
					$('#hasil_riwayat').html('<div class="alert alert-danger">Gagal memuat data.</div>');
				}
			});
		});
	</script>
</body>
</html>

