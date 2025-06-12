<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);

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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

</head> 

<div class="container-fluid">
	<div class="col-sm-12">
		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='soap_dokter.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'>Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
					<br>
					<br>
					<div class="row">
						<div class="col-6"><b>RUMAH SAKIT PETROKIMIA GRESIK</b><br><b>CATATAN PERKEMBANGAN PASIEN TERINTEGRASI</b></div>
						<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
					</div>
					<hr>
					<table class="table table-bordered">
						<?php
						$ql="SELECT TOP(100)*,CONVERT(VARCHAR, tanggal, 101) as tgl2,CONVERT(VARCHAR, tglentry, 8) as tgl3,CONVERT(VARCHAR, tglentry, 20) as tgl4  FROM ERM_SOAP WHERE noreg='$noreg' ORDER BY id desc";
						$hl  = sqlsrv_query($conn, $ql);
						$no=1;
						echo 
						"<tr bgcolor='#1E90FF'>
						<td><font color='white'>no</td>
						<td><font color='white'>tanggal-jam-shift</td>
						<td><font color='white'>profesi pemberi asuhan</td>
						<td><font color='white'>hasil assesment</td>
						<td><font color='white'>instruksi PPA</td>
						<td><font color='white'>verif DPJP</td>						
						<td><font color='white'>Hapus / Edit</td>
						</tr>";
						while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){
							
							$keterangan=""; 
							$kodedokter = trim($dl[kodedokter]);
							$noreg = trim($dl[noreg]);
							$userid = trim($dl[userid]);
							$dpjp = trim($dl[dpjp]);
							$periode = trim($dl[tgl2]);
							$instruksi = nl2br($dl[instruksi]);

							$q2		= "select nama from afarm_dokter where kodedokter='$kodedokter'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];
							$profesi = 'DOKTER';

							if(empty($namadokter)){
								$q2		= "select nama from afarm_paramedis where kode='$kodedokter'";
								$hasil2  = sqlsrv_query($conn, $q2);			  					
								$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
								$namadokter	= $data2[nama];
								$profesi = 'PERAWAT';

								if(trim($dl[kodeunit]) =='R02' OR trim($dl[kodeunit]) =='G19' ){
									$profesi = 'BIDAN';									
								}
							}


							if(empty($namadokter)){
								$q2		= "select nama from master_apoteker where apoteker='$kodedokter'";
								$hasil2  = sqlsrv_query($conn, $q2);			  					
								$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
								$namadokter	= $data2[nama];
								$profesi = 'APOTEKER';
							}

							//cek verif dokter...
							$q3		= "select userverif,CONVERT(VARCHAR, tglverif, 20) as tanggal from  ERM_SOAP_VERIF where noreg='$noreg' and userid='$userid' and userverif like '%$dpjp%' and (CONVERT(DATETIME, CONVERT(VARCHAR, tanggal, 101), 101) BETWEEN '$periode' AND '$periode')";
							$hasil3  = sqlsrv_query($conn, $q3);			  					
							$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
							$userverif	= $data3[userverif];
							$tanggal	= $data3[tanggal];

							$subjektif = nl2br($dl[subjektif]);
							$objektif = nl2br($dl[objektif]);
							$assesment = nl2br($dl[assesment]);
							$planning = nl2br($dl[planning]);

							$hasilassesment = "
							<b>Subject :</b> $subjektif<br>
							<b>Object :</b> $objektif<br>
							<b>Assesment :</b> $assesment<br>
							<b>Plan :</b> $planning
							";

							//cek gizi...
							$q5		= "SELECT    antropometri, biokimia, fisik_klinis, asupan_makan, diagnosa_gizi, intervensi, monitoring
							FROM            ERM_RI_SOAP
							WHERE      id_soap='$dl[id]'";
							$hasil5  = sqlsrv_query($conn, $q5);			  					
							$data5	= sqlsrv_fetch_array($hasil5, SQLSRV_FETCH_ASSOC);				  
							$antropometri	= $data5[antropometri];
							$biokimia	= $data5[biokimia];
							$fisik_klinis	= $data5[fisik_klinis];
							$asupan_makan	= $data5[asupan_makan];
							$diagnosa_gizi	= $data5[diagnosa_gizi];
							$intervensi	= $data5[intervensi];
							$monitoring	= $data5[monitoring];

							if($diagnosa_gizi){

								$subjektif = nl2br($dl[subjektif]);
								$objektif = nl2br($dl[objektif]);
								$assesment = nl2br($dl[assesment]);
								$planning = nl2br($dl[planning]);
								
								$hasilassesment = "
								<b>Assesment gizi :</b> $assesment<br>
								<b>Diagnosa gizi :</b> $subjektif<br>
								<b>Intervensi gizi :</b> $planning<br>
								<b>Monitoring-evaluasi gizi :</b> $objektif<br>
								";
								$profesi = 'GIZI';
							}

						//cek edit...
							$q4		= "select count(noreg) as jumlah from ERM_SOAP_EDIT where noreg='$noreg' and idsoap='$dl[id]'";
							$hasil4  = sqlsrv_query($conn, $q4);			  					
							$data4	= sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC);				  
							$jumlah	= $data4[jumlah];
							if($jumlah){
								$keterangan = 'CPPT Telah diEdit '.$jumlah.' kali';
							}

							$jam1  = strtotime($dl[tgl4]);
							$jam2 = strtotime($tglsekarang);											
							$selisih  = $jam2 - $jam1;

							$jam   = floor($selisih / (60 * 60));

							if($antropometri){
								

								if($jam < 24){
									echo "	<tr>
									<td>$no</td>
									<td>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]</td>
									<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
									<td>$hasilassesment</td>
									<td>$instruksi</td>
									<td>$userverif<br>$tanggal</td>
									<td align='center'>
									$keterangan
									<a href='edit_soap_dokter.php?id=$id|$user|$dl[id]'><font size='5'><i class='bi bi-file-earmark-text'></i></font></a>
									<a href='del_cppt.php?id=$id|$user|$dl[id]'><font size='5'><i class='bi bi-trash'></i></font></a>
									</td>					
									</tr>
									";
								}else{
									echo "	<tr>
									<td>$no</td>
									<td>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]</td>
									<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
									<td>$hasilassesment</td>
									<td>$instruksi</td>
									<td>$userverif<br>$tanggal</td>
									<td align='center'>
									sudah 1x24 jam
									</td>					
									</tr>
									";

								}

							}else{
								// echo  substr($dl[noreg], 0,1);

								if(substr($dl[noreg], 0,1)=="R"){ //jika rawat inap
									if($jam < 24){
										echo "	<tr>
										<td>$no</td>
										<td>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]</td>
										<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
										<td>$hasilassesment</td>
										<td>$instruksi</td>
										<td>$userverif<br>$tanggal</td>
										<td align='center'>
										$keterangan
										<a href='tulbakon_dokter.php?id=$id|$user|$dl[id]'><font size='5'><i class='bi bi-clipboard-check'></i></font></a>
										<br>
										Tulbakon
										<hr>
										<a href='edit_soap_dokter.php?id=$id|$user|$dl[id]'><font size='5'><i class='bi bi-file-earmark-text'></i></font></a>
										<br>
										Edit
										<hr>
										<a href='del_cppt.php?id=$id|$user|$dl[id]'><font size='5'><i class='bi bi-trash'></i></font></a>
										<br>
										Hapus
										</td>			
										</tr>
										";
									}else{
										echo "	<tr>
										<td>$no</td>
										<td>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]</td>
										<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
										<td>$hasilassesment</td>
										<td>$instruksi</td>
										<td>$userverif<br>$tanggal</td>
										<td align='center'>
										sudah 1x24 jam
										</td>			
										</tr>
										";
									}
								}else{

									echo "	<tr>
									<td>$no</td>
									<td>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]</td>
									<td>$profesi - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
									<td>$hasilassesment</td>
									<td>$instruksi</td>
									<td>$userverif<br>$tanggal</td>
									<td align='center'></td>							
									<td align='center'>$keterangan</td>												
									</tr>
									";

								}


							}


							$no += 1;
						}
						?>
					</table>
				</font>
			</form>
		</font>
	</body>
</div>
</div>