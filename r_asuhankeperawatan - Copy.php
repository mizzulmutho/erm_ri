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
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head> 

<div class="container-fluid">

	<body onload="document.myForm.pasien_mcu.focus();">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			&nbsp;&nbsp;&nbsp;
			<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i> </a>
			&nbsp;&nbsp;&nbsp;
			<a href='askep.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'>+ Rencana Asuhan </a>
			&nbsp;&nbsp;&nbsp;
			<br>
			<!-- <h3>Rencana Asuhan Keperawatan</h3> -->
			<br>
			&nbsp;&nbsp;&nbsp;<b>REPORT ASUHAN KEPERAWATAN</b><br>
			<font size='2px'>
				<?php
				// $ql="SELECT DISTINCT *,CONVERT(VARCHAR, tgl_teratasi, 20) as tglteratasi  FROM ERM_ASUHAN_KEPERAWATAN WHERE noreg='$noreg' ORDER BY id desc";

				$ql="SELECT DISTINCT CONVERT(VARCHAR, tgl_teratasi, 20) as tglteratasi,id_assesmen,noreg,diagnosa_keperawatan,rencana,userid,diagnosa_nama,jenis  FROM ERM_ASUHAN_KEPERAWATAN WHERE noreg='$noreg' ORDER BY diagnosa_keperawatan asc";

				$hl1  = sqlsrv_query($conn, $ql);
				while   ($dl1 = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC)){   
					$jenis = $dl1['jenis'];

					if($jenis=='RENCANA ASUHAN KEPERAWATAN'){

						echo "<table width='100%' border='1'>";

						$diagnosa_keperawatan = $dl1['diagnosa_keperawatan'];

						$q2="SELECT diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN WHERE diagnosa_keperawatan like '%$diagnosa_keperawatan%' ORDER BY id desc";
						$h2  = sqlsrv_query($conn, $q2);
						$d2  = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
						$diagnosa_nama = $d2['diagnosa_nama'];

						echo "
						<tr>
						<td width='20%'>Diagnosa Keperawatan</td>
						<td width='20%'>$dl1[diagnosa_keperawatan]</td>
						<td rowspan='3'>
						<b><u>detail pengkajian</u></b>
						";
						$ql2="SELECT rencana from ERM_ASUHAN_KEPERAWATAN2 WHERE noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and rencana <> '...' ORDER BY id desc";
						$hl12  = sqlsrv_query($conn, $ql2);
						while   ($dl12 = sqlsrv_fetch_array($hl12, SQLSRV_FETCH_ASSOC)){   
							echo "
							<table>
							<tr>
							<td>
							$dl12[rencana]
							</td>
							</tr>
							</table>
							";
						}


						echo "
						</td>
						</tr>
						<tr>
						<td>Diagnosa Nama</td><td>$diagnosa_nama</td>
						</tr>
						<tr>
						<td>User Entry</td><td>$dl1[userid] - $dl1[tglteratasi]</td>
						</tr>
						";

						?>

						<?php

						$qus="SELECT distinct sift FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' ";
						$h1us  = sqlsrv_query($conn, $qus);        
						while   ($d1us = sqlsrv_fetch_array($h1us, SQLSRV_FETCH_ASSOC)){

							echo "<table  width='100%' border='1'>";

							$sift = $d1us[sift];

							$qu="SELECT *,CONVERT(VARCHAR, tgl, 20) as tgl2 FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and sift='$sift'";
							$h1u  = sqlsrv_query($conn, $qu);        
							$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 



							echo 
							"<tr bgcolor='#969392'>
							<td>no</td><td>implementasi/invervensi</td>
							</tr>";

							echo "<tr><td colspan='2'>";
							echo $sift = $d1u['sift'];
							echo ' - ';						
							echo ' user : '.$userid = $d1u['userid'];
							echo ' tgl : '.$tgl2 = $d1u['tgl2'];
							echo "</td></tr>";


							$ql="SELECT *,CONVERT(VARCHAR, tgl, 20) as tgl2  FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and sift='$sift'
							ORDER BY id desc";
							$hl  = sqlsrv_query($conn, $ql);
							$no=1;

							while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){         
								$implementasi = $dl[implementasi];
								$implementasi = html_entity_decode($implementasi);

								echo "	<tr>
								<td>$no</td>
								<td>$implementasi</td>
								</tr>
								";
								$no += 1;
							}

							?>
						</table>
						<?php 
						echo "</table>";
					}// end while
				}else if($jenis=='RENCANA ASUHAN NEONATUS'){

					echo "<table width='100%' border='1'>";

					$diagnosa_keperawatan = $dl1['diagnosa_keperawatan'];

					$q2="SELECT diagnosa_nama FROM ERM_MASTER_ASUHANKEPERAWATAN WHERE diagnosa_keperawatan like '%$diagnosa_keperawatan%' ORDER BY id desc";
					$h2  = sqlsrv_query($conn, $q2);
					$d2  = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
					$diagnosa_nama = $d2['diagnosa_nama'];
					
					echo "
					<tr>
					<td width='20%'>Diagnosa Keperawatan</td>
					<td width='20%'>$dl1[diagnosa_keperawatan]</td>
					<td rowspan='3'>
					<b><u>detail pengkajian</u></b>
					";
					$ql2="SELECT isian from ERM_MASTER_ASUHANKEPERAWATAN WHERE diagnosa_keperawatan='$diagnosa_keperawatan'";
					$hl12  = sqlsrv_query($conn, $ql2);
					while   ($dl12 = sqlsrv_fetch_array($hl12, SQLSRV_FETCH_ASSOC)){   
						$isian = html_entity_decode($dl12[isian]);
						echo "
						<table>
						<tr>
						<td>
						$isian
						</td>
						</tr>
						</table>
						";
					}


					echo "
					</td>
					</tr>
					<tr>
					<td>Diagnosa Nama</td><td>$diagnosa_nama</td>
					</tr>
					<tr>
					<td>User Entry</td><td>$dl1[userid] - $dl1[tglteratasi]</td>
					</tr>
					";

					?>

					<?php

					$qus="SELECT distinct sift FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' ";
					$h1us  = sqlsrv_query($conn, $qus);        
					while   ($d1us = sqlsrv_fetch_array($h1us, SQLSRV_FETCH_ASSOC)){

						echo "<table  width='100%' border='1'>";

						$sift = $d1us[sift];

						$qu="SELECT *,CONVERT(VARCHAR, tgl, 20) as tgl2 FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and sift='$sift'";
						$h1u  = sqlsrv_query($conn, $qu);        
						$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 



						echo 
						"<tr bgcolor='#969392'>
						<td>no</td><td>implementasi/invervensi</td>
						</tr>";

						echo "<tr><td colspan='2'>";
						echo $sift = $d1u['sift'];
						echo ' - ';						
						echo ' user : '.$userid = $d1u['userid'];
						echo ' tgl : '.$tgl2 = $d1u['tgl2'];
						echo "</td></tr>";


						$ql="SELECT *,CONVERT(VARCHAR, tgl, 20) as tgl2  FROM ERM_IMPLEMENTASI_ASUHAN WHERE noreg='$noreg' and diagnosa_keperawatan='$diagnosa_keperawatan' and sift='$sift'
						ORDER BY id desc";
						$hl  = sqlsrv_query($conn, $ql);
						$no=1;

						while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){         
							$implementasi = $dl[implementasi];
							$implementasi = html_entity_decode($implementasi);

							echo "	<tr>
							<td>$no</td>
							<td>$implementasi</td>
							</tr>
							";
							$no += 1;
						}

						?>
					</table>
					<?php 
					echo "</table>";
					}// end while


				}

				}//while shift

				?>
			</table>
			<br>

			<!-- <h5>Implementasi Asuhan Keperawatan</h5> -->
		</font>
	</form>
</body>
</div>