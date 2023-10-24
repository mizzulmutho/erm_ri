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
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

</head> 

<div class="container">

	<body onload="document.myForm.pasien_mcu.focus();">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<br>
				<br>
				<div class="row">
					<div class="col-4"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
					<div class="col-4"><b>REKAM MEDIK PASIEN</b></div>
					<div class="col-4"><b><font color='red'>ALERGI : </font></b></div>
				</div>

				<div class="row">
					<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
					<div class="col-6"><?php echo 'L/P : '.$kelamin.'<br> UMUR : '.$umur.'<br> ALAMAT : '.$alamatpasien; ?></div>
				</div>
				<hr>
				<table border="1" cellpadding="5px">
					<?php
					$ql="SELECT TOP(100)*,CONVERT(VARCHAR, tanggal, 101) as tgl2,CONVERT(VARCHAR, tglentry, 8) as tgl3  FROM ERM_SOAP WHERE norm='$norm' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$no=1;
					echo 
					"<tr bgcolor='#969392'>
					<td>No</td>
					<td>Nama Unit</td>
					<td>S.O.A.P</td>
					<td>Pelayanan Farmasi</td>
					<td>Pemeriksaan Lab</td>
					<td>Pemeriksaan Rad</td>
					<td>File Berkas</td>
					</tr>";
					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){    
						$kodedokter = trim($dl[kodedokter]);
						$noreg = trim($dl[noreg]);
						$userid = trim($dl[userid]);
						$dpjp = trim($dl[dpjp]);
						$periode = trim($dl[tgl2]);

						$q2		= "select nama from afarm_dokter where kodedokter='$kodedokter'";
						$hasil2  = sqlsrv_query($conn, $q2);			  					
						$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
						$namadokter	= $data2[nama];

						if(empty($namadokter)){
							$q2		= "select nama from afarm_paramedis where kode='$kodedokter'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];
						}

						//cek verif dokter...
						$q3		= "select userverif from  ERM_SOAP_VERIF where noreg='$noreg' and userid='$userid' and userverif like '%$dpjp%' and (CONVERT(DATETIME, CONVERT(VARCHAR, tanggal, 101), 101) BETWEEN '$periode' AND '$periode')";
						$hasil3  = sqlsrv_query($conn, $q3);			  					
						$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
						$userverif	= $data3[userverif];

						$hasilassesment = "
						<b>Subject :</b> $dl[subjektif]<br>
						<b>Object :</b> $dl[objektif]<br>
						<b>Vital Sign :</b> $dl[vital]<br>
						<b>Assesment :</b> $dl[assesment]<br>
						<b>Plant :</b> $dl[planning]
						";

						echo "	<tr>
						<td>$no</td>
						<td>$dl[kodeunit] - $dl[sbu]<br>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]</td>
						<td>$kodedokter - $namadokter<br>$hasilassesment</td>
						<td>$farmasi</td>
						<td>$lab</td>
						<td>$rad</td>
						<td>
						ERM<br>
						FOTO
						</td>
						</tr>
						";
						$no += 1;
					}
					?>
				</table>
			</font>
		</form>
	</font>
</body>
</div>