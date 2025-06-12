<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl = gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT noreg,norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];

$q2 = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);            
$data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);

$nama = $data2['nama'];
$kelamin = $data2['kelamin'];
$nik = trim($data2['nik']);
$alamatpasien = $data2['alamatpasien'];
$kota = $data2['kota'];
$kodekel = $data2['kodekel'];
$telp = $data2['tlp'];
$tmptlahir = $data2['tmptlahir'];
$tgllahir = $data2['tgllahir'];
$jenispekerjaan = $data2['jenispekerjaan'];
$jabatan = $data2['jabatan'];
$umur =  $data2['UMUR'];
?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">	
	<!-- <script src="linechartjs/js/Chart.js"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

	<style type="text/css">
		.container {
			margin: 15px auto;
		}
	</style>
</head> 
<body>
	<div class="container">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;
			<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
			<br>
			<br>
			<div class="row">
				<div class="col-4"><b>DASHBOARD VITAL SIGN</b></div>
				<div class="col-8">
					<b><font color=''>ALERGI : </font></b>
					&nbsp;&nbsp;&nbsp;
					<b><font color=''>SKALA JATUH : </font></b><br>                      
				</div>
			</div>              
			<hr>
			<div class="row">
				<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
				<div class="col-6"><?php echo 'L/P : '.$kelamin.'<br> UMUR : '.$umur.'<br> ALAMAT : '.$alamatpasien; ?></div>
			</div>
			<hr>
			<canvas id="vitalsChart" width="400" height="200"></canvas>
		</form>
	</div>

	<?php 
//rr
	$q="
	SELECT     TOP (200) id, norm, noreg, 
	substring(CONVERT(VARCHAR, tglinput, 103),1,2) as tgl,
	substring(CONVERT(VARCHAR, tglinput, 8),1,2) as jam,
	td_sistolik, td_diastolik, nadi, suhu, pernafasan, spo2,total_ews
	FROM         ERM_RI_OBSERVASI
	where noreg='$noreg' 
	order by id ASC";
	$h  = sqlsrv_query($conn, $q);
	$i=1;

	while   ($data = sqlsrv_fetch_array($h, SQLSRV_FETCH_ASSOC)){         
		$ket = $ket."'"."$data[tgl]"."("."$data[jam]".")"."'".",";
		$rr  = $rr."'"."$data[pernafasan]"."',";
		$td_sistolik  = $td_sistolik."'"."$data[td_sistolik]"."',";
		$td_diastolik  = $td_diastolik."'"."$data[td_diastolik]"."',";
		$nadi  = $nadi."'"."$data[nadi]"."',";
		$suhu  = $suhu."'"."$data[suhu]"."',";
		$spo2  = $spo2."'"."$data[spo2]"."',";
		$ews  = $ews."'"."$data[total_ews]"."',";

		$i+=1;

	}

	?>

	<script>
		document.addEventListener("DOMContentLoaded", function () {
			var ctx = document.getElementById("vitalsChart").getContext("2d");

    // Daftarkan plugin ChartDataLabels
    Chart.register(ChartDataLabels);

    var data = {
    	labels: [<?php echo $ket; ?>],
    	datasets: [
    	{
    		label: "Respiratory Rate",
    		borderColor: "blue",
    		data: [<?php echo $rr; ?>],
    		fill: false,
    		pointBackgroundColor: "blue",
    		pointRadius: 5,
                tension: 0.4 // Membuat garis lebih melengkung
            },
            {
            	label: "SPO2",
            	borderColor: "green",
            	data: [<?php echo $spo2; ?>],
            	fill: false,
            	pointBackgroundColor: "green",
            	pointRadius: 5,
            	tension: 0.4
            },
            {
            	label: "Temperature",
            	borderColor: "red",
            	data: [<?php echo $suhu; ?>],
            	fill: false,
            	pointBackgroundColor: "red",
            	pointRadius: 5,
            	tension: 0.4
            },
            {
            	label: "Systolic BP",
            	borderColor: "purple",
            	data: [<?php echo $td_sistolik; ?>],
            	fill: false,
            	pointBackgroundColor: "purple",
            	pointRadius: 5,
            	tension: 0.4
            },
            {
            	label: "Diastolic BP",
            	borderColor: "orange",
            	data: [<?php echo $td_diastolik; ?>],
            	fill: false,
            	pointBackgroundColor: "orange",
            	pointRadius: 5,
            	tension: 0.4
            },
            {
            	label: "Heart Rate",
            	borderColor: "brown",
            	data: [<?php echo $nadi; ?>],
            	fill: false,
            	pointBackgroundColor: "brown",
            	pointRadius: 5,
            	tension: 0.4
            },
            {
            	label: "EWS",
            	borderColor: "black",
            	data: [<?php echo $ews; ?>],
            	fill: false,
            	pointBackgroundColor: "black",
            	pointRadius: 5,
            	tension: 0.4
            }
            ]
        };

        var vitalsChart = new Chart(ctx, {
        	type: 'line',
        	data: data,
        	options: {
        		responsive: true,
        		plugins: {
        			legend: { display: true },
        			tooltip: { enabled: true },
        			datalabels: {
        				align: 'top',
        				anchor: 'end',
        				color: '#000',
        				font: { weight: 'bold' },
                    formatter: (value) => value // Menampilkan angka di atas titik
                }
            },
            scales: {
            	y: { beginAtZero: true },
            	x: { grid: { display: false } }
            }
        }
    });
    });
</script>


</body>
</html>
