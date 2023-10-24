<?php 
session_start();
include ("koneksi.php");

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$qu="SELECT norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
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
	<title>SIRS - Sistem Informasi Rumah Sakit</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

	<br>
	<div class="container">

		<div class="row">

			<div class="col-sm-6">
				<div class="card text-white bg-info mb-3">
					<div class="card-body">
						<p class="card-text">
							<i class="bi bi-hospital" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
							<b>E-REKAM MEDIK RAWAT INAP</b>&nbsp;&nbsp;&nbsp;<i><u>Assesmen Keperawatan</u></i> 
						</p>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="">
					<div class="card-body">
						<p class="card-text">
							<font size='2px'>
								Norm : <?php echo $norm;?><br>						
								Nama : <?php echo $nama;?>
							</font>
						</p>
					</div>
				</div>
			</div>

			<div class="col-sm-3">
				<div class="">
					<div class="card-body">
						<p class="card-text">
							<font size='2px'>
								Tgl Lahir : <?php echo $tgllahir;?><br>
								Alamat : <?php echo $alamatpasien;?>
							</font>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-body">
						<i class="bi bi-printer-fill" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
						<a href="r_rekammedik.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Rekam Medik Pasien</a>
						<a href="r_soap.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">CPPT</a>
						<a href="r_vitalsign.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Dashboard Vital Sign</a>
						<a href="r_observasi.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Detail Observasi</a>
						<a href="r_ews.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Monitoring EWS</a>
						<a href="r_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Asuhan Keperawatan</a>
						<br>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Form Anamnesis Awal</i>
						<a href="r_formdewasa.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-light btn-sm">Dewasa</a>
						<a href="r_formanak.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-light btn-sm">Anak</a>
						<a href="r_formneonatus.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-light btn-sm">Neonatus</a>
						<a href="r_formgeriatri.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-light btn-sm">Geriatri</a>
						<a href="r_formbersalin.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-light btn-sm">Bersalin</a>
						<a href="i_upload.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-warning btn-sm">Upload pdf</a>
					</div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-body">
						<i class="bi bi-window-plus" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
						<b>Anamnesis Awal</b>
						<a href="erm.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">form Medis</a>
						<a href="m_form.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-warning btn-sm">Master</a>	
						<br><br>				
						<a href="form_assesmen_dewasa.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">Dewasa</a>
						<a href="form_assesmen_anak.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">Anak</a>
						<a href="form_assesmen_neonatus.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">Neonatus</a>	
						<a href="form_assesmen_geriatri.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">Geriatri</a>
						<a href="form_assesmen_bersalin.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">Bersalin</a>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="card">
					<div class="card-body">
						<i class="bi bi-window-plus" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
						<b>Rencana Asuhan & Implementasi</b><br>
						<a href="form_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">Keperawatan</a>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="card">
					<div class="card-body">
						<i class="bi bi-window-plus" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
						<b>C P P T</b><br>
						<a href="form_soap.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">form input</a>
						<a href="form_soap_verif.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">verifikasi dokter</a>
						<a href="form_soap_operan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">operan sift</a>		
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="card">
					<div class="card-body">
						<i class="bi bi-window-plus" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
						<b>OBSERVASI RAWAT INAP</b><br>
						<a href="observasi.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">form input</a>
						<a href="observasi_list.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">list</a>
					</div>
				</div>
			</div>

		</div>


	</div>