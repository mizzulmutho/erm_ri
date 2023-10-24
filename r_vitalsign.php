<?php 
include ("koneksi.php");
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
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />

	<script src="linechartjs/js/Chart.js"></script>
	<style type="text/css">
		.container {
			/*width: 40%;*/
			margin: 15px auto;
		}
	</style>
</head> 

<body>
	<div class="container">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
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
				<div class="row">
					<div class="col-4">
						<canvas id="respiratory_rate" width="100" height="100"></canvas>
					</div>
					<div class="col-4">
						<canvas id="spo2" width="100" height="100"></canvas>
					</div>
					<div class="col-4">
						<canvas id="suhu" width="100" height="100"></canvas>
					</div>
				</div>
				<div class="row">
					<div class="col-4">
						<canvas id="td_sistolik" width="100" height="100"></canvas>
					</div>
					<div class="col-4">
						<canvas id="td_diastolik" width="100" height="100"></canvas>
					</div>
					<div class="col-4">
						<canvas id="nadi" width="100" height="100"></canvas>
					</div>
				</div>


			</font>
		</form>
	</font>
</body>
</div>

<?php 
//rr
$q="
SELECT     TOP (200) id, norm, noreg, 
substring(CONVERT(VARCHAR, tglinput, 103),1,2) as tgl,
substring(CONVERT(VARCHAR, tglinput, 8),1,2) as jam,
td_sistolik, td_diastolik, nadi, suhu, pernafasan, spo2
FROM         ERM_RI_OBSERVASI
where noreg='$noreg' 
order by id DESC";
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

	$i+=1;

}

?>

<script  type="text/javascript">
	var ctx = document.getElementById("respiratory_rate").getContext("2d");
	var data = {
		// labels: ["25(06)","25(13)","25(16)","25(21)"],
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK RESPIRATORY RATE",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#29B0D0",
			borderColor: "#29B0D0",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			// data: ['92','98','98','99']
			data: [<?php echo $rr; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>

<script  type="text/javascript">
	var ctx = document.getElementById("spo2").getContext("2d");
	var data = {
		// labels: ["25(06)","25(13)","25(16)","25(21)"],
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK SPO2",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#29B0D0",
			borderColor: "#29B0D0",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $spo2; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>

<script  type="text/javascript">
	var ctx = document.getElementById("suhu").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK TEMPERATUR",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#29B0D0",
			borderColor: "#29B0D0",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $suhu; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>

<script  type="text/javascript">
	var ctx = document.getElementById("td_sistolik").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK TD SISTOLIK",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#29B0D0",
			borderColor: "#29B0D0",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $td_sistolik; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>

<script  type="text/javascript">
	var ctx = document.getElementById("td_diastolik").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK TD DIASTOLIK",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#29B0D0",
			borderColor: "#29B0D0",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $td_diastolik; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>

<script  type="text/javascript">
	var ctx = document.getElementById("nadi").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK HR",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#29B0D0",
			borderColor: "#29B0D0",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $nadi; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>