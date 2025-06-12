<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl        = gmdate("Y-m-d", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);



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

$qu="SELECT dpjp FROM  V_ERM_RI_DPJP_ASESMEN where noreg='$noreg' and dpjp is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$dpjp = $d1u['dpjp'];
$row = explode('-',$dpjp);
$kodedokter  = trim($row[0]);

if(empty($dpjp)){
	$qu="SELECT top(1)nama_dokter as dpjp FROM   ERM_RI_RABER where noreg='$noreg' order by id";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$dpjp = $d1u['dpjp'];
	$row = explode('-',$dpjp);
	$kodedokter  = trim($row[0]);

}

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

//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];



?>

<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Riwayat Pemeriksaan</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
	<!-- DataTables Buttons -->
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	
	<style>
		body {
			padding: 20px;
		}
	</style>
</head>
<body>

	<div class="container">
		<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
		&nbsp;&nbsp;
		<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
		&nbsp;&nbsp;
		<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
		<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
		&nbsp;&nbsp;
		<br>
		<br>
		<div class="row">
		</div>

		<div class="row">
			<div class="col-6">
				<h5><b><?php echo $nmrs; ?></b></h5>
				<?php echo $alamat; ?>
			</div>
			<div class="col-6">
				<?php echo 'NIK : '.$noktp.'<br>'; ?>                   
				<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
				<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
			</div>
		</div>
	</div>

	<div class="row">
		<?php 
		if($role=='DOKTER'){
			include('menu_dokter.php');
		}
		?>
	</div>

	<div class="container">
		<a href="http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/<?php echo $KODEUNIT.'/'.$noreg.'/'.$norm.'/'.$kodedokter;?>" class='btn btn-success' target='_blank' ><i class='bi bi-prescription2'></i> Order Permintaan Laborat & Radiologi</a>		
		
		<h2 class="mb-4">Riwayat Pemeriksaan Radiologi dan Laboratorium</h2>
		<div class="mb-3 row">
			<label class="col-sm-2 col-form-label">Nomor RM</label>
			<div class="col-sm-4">
				<input type="text" id="no_rm" class="form-control" value="<?php echo $norm;?>" placeholder="Contoh: 123456" readonly>
			</div>
			<div class="col-sm-2">
				<button class="btn btn-primary" id="btnCari">Cari Riwayat</button>
			</div>
		</div>

		<hr>
		<div id="hasil_riwayat"></div>
	</div>

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- DataTables JS -->
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<!-- DataTables Buttons -->
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

	<script>
		$(document).ready(function () {
			$('#btnCari').click(function () {
				var no_rm = $('#no_rm').val();
				if (no_rm === '') {
					alert('Silakan masukkan nomor RM.');
					return;
				}

				$('#hasil_riwayat').html('<div class="alert alert-info">Memuat data...</div>');

				$.ajax({
					url: 'riwayat_pemeriksaan.php',
					type: 'POST',
					data: { no_rm: no_rm },
					success: function (res) {
						$('#hasil_riwayat').html(res);

						$('table').DataTable({
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
		});
	</script>

</body>
</html>