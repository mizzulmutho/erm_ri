<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$date = new DateTime('@'.strtotime('2016-03-22 14:30'), new DateTimeZone('Australia/Sydney'));

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$tanggalperiksa = gmdate("Y-m-d", time()+60*60*7);

$bulan2  =substr($tanggalperiksa,5,2);
$tanggal2=substr($tanggalperiksa,8,3);
$tahun2  =substr($tanggalperiksa,0,4);



$sbu = 'GRAHU';


if(empty($tgl1)){
	$tgl1=gmdate("Y-m-d", time()+60*60*7);
}

if(empty($tgl2)){
	$tgl2=gmdate("Y-m-d", time()+60*60*7);
}


if (isset($_POST["tampil"])) {
	$tgl1 = $_POST ['tgl1'];
	$tgl2 = $_POST ['tgl2'];
	$jenis = $_POST ['jenis'];
}else{
	$id = $_GET["id"];
	$row = explode('|',$id);

	$tgl1 = trim($row[0]); 
	$tgl2 = trim($row[1]);

}


$bulan1  =intval(substr($tgl1,5,2));
$tanggal1=intval(substr($tgl1,8,3));
$tahun1  =intval(substr($tgl1,0,4));

$bulan2  =intval(substr($tgl2,5,2));
$tanggal2=intval(substr($tgl2,8,2));
$tahun2  =intval(substr($tgl2,0,4));

$periode1=date("m-d-Y",strtotime($tgl1));
$periode2=date("m-d-Y",strtotime($tgl2));

?>

<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<title>SIRS - Sistem Informasi Rumah Sakit</title>
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

	<!-- Plugin untuk DataTables -->
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">


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

</head>


<body onLoad="document.myForm.namaruang.focus();">
	<form action="<?php echo $halaman;?>" method="post" name="myForm" id="myForm">
		<br>
		<div class="container-fluid">

			<div class="card-body">
				<input name="tgl1" type="date" size="15" value="<?php echo $tgl1; ?>" style="width:150px;height:40px">
				s/d
				<input name="tgl2" type="date" size="15" value="<?php echo $tgl2; ?>" style="width:150px;height:40px">
				&nbsp;&nbsp;
				<button type='submit' name='tampil' value='cari' type="button" class='btn btn-primary'><i class="bi bi-search"></i>
				</button>

			</div>



			<div class="card">
				<div class="card-header">
					<h3>&nbsp;&nbsp;&nbsp;<i class="bi bi-bar-chart-line"></i>
					</h3>
				</div>
				<div class="card-body">

					<table class="table table-stripped table-hover datatab">
						<thead>
							<tr>
								<th>No</th>
								<th>Noreg</th>
								<th>Tanggal</th>
								<th>Pasien</th>
								<th>Profesi Pemberi Asuhan</th>
								<th>Encontered</th>
								<th>Userid</th>
								<th>Encontered</th>
								<th>Observasi</th>
								<th>Procedure</th>
								<th>Hidden</th>
							</tr>
						</thead>

						<tbody>

							<?php
							if($tgl1){
								$q="

								SELECT        TOP (200) AFarm_MstPasien.NAMA, Afarm_Unitlayanan.KET, ERM_ASSESMEN_HEADER.kodedokter, Afarm_DOKTER.NAMA AS NAMADOKTER, ERM_ASSESMEN_HEADER.noreg, ERM_ASSESMEN_HEADER.userid, AFarm_MstPasien.NOKTP,AFarm_MstPasien.NORM,
								CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 25) AS tglmasuk, ARM_REGISTER.IDENCOUNTERSS
								FROM            Afarm_DOKTER INNER JOIN
								ERM_ASSESMEN_HEADER ON Afarm_DOKTER.KODEDOKTER = ERM_ASSESMEN_HEADER.kodedokter INNER JOIN
								ARM_REGISTER INNER JOIN
								AFarm_MstPasien ON ARM_REGISTER.NORM = AFarm_MstPasien.NORM INNER JOIN
								Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT ON ERM_ASSESMEN_HEADER.noreg = ARM_REGISTER.NOREG
								WHERE   (CONVERT(DATETIME, CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 101), 101) BETWEEN '$periode1' AND '$periode2') and      (Afarm_Unitlayanan.KET LIKE '%GRAHU%') AND
								(ERM_ASSESMEN_HEADER.noreg NOT IN
									(SELECT        noreg
										FROM            zzz_mutok3))
								
								ORDER BY ERM_ASSESMEN_HEADER.id desc

								";
							}

							$hq  = sqlsrv_query($conn, $q); 
							$no=1;

							while   ($data1 = sqlsrv_fetch_array($hq,SQLSRV_FETCH_ASSOC)){ 


								$qe       = "SELECT  * from zzz_mutok4
								where noreg='$data1[noreg]'";
								$hasile  = sqlsrv_query($conn, $qe);                
								$datae    = sqlsrv_fetch_array($hasile, SQLSRV_FETCH_ASSOC);                      
								$a = trim($datae[a]);
								$b = trim($datae[b]);
								$c = trim($datae[c]);

								if($a=='Y'){$a=' &check;';}
								if($b=='Y'){$b=' &check;';}
								if($c=='Y'){$c=' &check;';}

								echo "
								<tr>
								<td>$no</td>
								<td>$data1[noreg]</td>
								<td>$data1[tglmasuk]</td>
								<td>$data1[NAMA] ($data1[NORM])<br>$data1[NOKTP]</td>
								<td>$data1[NAMADOKTER] - $data1[kodedokter]</td>
								<td>$data1[IDENCOUNTERSS]</td>
								<td>$data1[userid]</td>
								<td><a href='s_sehat4.php?id=$data1[noreg]|$data1[kodedokter]|$sbu|$data1[tglmasuk]|$tgl1|$tgl2' class='btn btn-info'><i class='bi bi-arrow-up-right-circle-fill'></i></a> $a</td>
								<td><a href='s_sehat5.php?id=$data1[noreg]|$data1[kodedokter]|$sbu|$data1[tglmasuk]|$tgl1|$tgl2' class='btn btn-primary'><i class='bi bi-arrow-up-right-circle-fill'></i></a> $b</td>
								<td><a href='s_sehat6.php?id=$data1[noreg]|$data1[kodedokter]|$sbu|$data1[tglmasuk]|$tgl1|$tgl2' class='btn btn-primary'><i class='bi bi-arrow-up-right-circle-fill'></i></a> $c</td>
								<td><a href='s_sehatx.php?id=$data1[noreg]|$tgl1|$tgl2' class='btn btn-warning'><i class='bi bi-x-circle'></i></a></td>
								</tr>
								";

								$no+=1;
							}

							?>
						</tbody>

					</table>
				</div>

			</div>
		</form>
	</body>
	<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
	<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready(function() {
			$('.datatab').DataTable();
		} );
	</script>