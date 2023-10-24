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
					<div class="col-6"><b>RUMAH SAKIT PETROKIMIA GRESIK</b><br><b>DETAIL OBSERVASI HARIAN PASIEN</b></div>
					<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
				</div>
				<hr>
				<table border="1" cellpadding="5px">
					<tr>
						<td rowspan='2'>no</td>
						<td rowspan='2'>tgl</td>
						<td rowspan='2'>jam</td>
						<td rowspan='2'>tensi</td>
						<td rowspan='2'>nadi</td>
						<td rowspan='2'>suhu</td>
						<td rowspan='2'>rr</td>
						<td rowspan='2'>skala nyeri</td>
						<td rowspan='2'>spo2</td>
						<td rowspan='2'>bb</td>						
						<td colspan='3'>kesadaran</td>
						<td colspan='4'>intake</td>
						<td colspan='7'>output</td>
						<td rowspan='2'>balance</td>						
						<td rowspan='2'>sisa cairan infus</td>						
						<td rowspan='2'>user</td>
					</tr>
					<tr>
						<td>GCS</td>
						<td>Pupil</td>
						<td>VIP Score</td>
						<td>Cairan(Infus/GC)</td>
						<td>Makan</td>
						<td>Minum</td>
						<td>Total</td>
						<td>Muntah</td>
						<td>Pendarahan</td>
						<td>Cairan</td>
						<td>Urine</td>
						<td>BAB</td>
						<td>IWL</td>
						<td>Total</td>
					</tr>

					<?php 
					$q1		= "select top(50)*, 
					CONVERT(VARCHAR, tglinput, 23) as tglinput,
					CONVERT(VARCHAR, tglinput, 24) as jam  
					from ERM_RI_OBSERVASI where noreg='$noreg' order by id desc";
					$hasil1  = sqlsrv_query($conn, $q1);
					$nox=1;           
					while   ($data1 = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   
						$ket =
						'tensi :'.$data1[td_sistolik].'/'.$data1[td_diastolik].' ,'.
						'nadi :'.$data1[nadi].' ,'.
						'suhu :'.$data1[suhu].' ,'.
						'pernafasan :'.$data1[pernafasan].' ,'.
						'spo2 :'.$data1[spo2]
						;
						echo "
						<tr>
						<td>$nox</td>
						<td>$data1[tglinput]</td>
						<td>$data1[jam]</td>
						<td>$data1[td_sistolik]/$data1[td_diastolik]</td>
						<td>$data1[nadi]</td>
						<td>$data1[suhu]</td>
						<td>$data1[pernafasan]</td>
						<td>$data1[skala_nyeri]</td>
						<td>$data1[spo2]</td>
						<td>$data1[bb]</td>
						<td>$data1[gcs]</td>
						<td>$data1[pupil]</td>
						<td>$data1[vip_score]</td>
						<td>$data1[cairan]</td>
						<td>$data1[makan]</td>
						<td>$data1[minum]</td>
						<td>$data1[total_intake]</td>
						<td>$data1[muntah]</td>
						<td>$data1[pendarahan]</td>
						<td>$data1[cairan]</td>
						<td>$data1[urine]</td>
						<td>$data1[bab]</td>
						<td>$data1[iwl]</td>
						<td>$data1[total_output]</td>
						<td>$data1[balance]</td>
						<td>$data1[sisa_cairan_infus]</td>
						<td>$data1[userinput]</td>
						</tr>


						";

						$nox+=1;
					}
					?>
				</table>
			</font>
		</form>
	</font>
</body>
</div>