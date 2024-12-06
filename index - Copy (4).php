<?php 
session_start();
include ("koneksi.php");

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$sbu = $row[2]; 

$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>SIRS - Sistem Informasi Rumah Sakit</title>  
	<link rel="icon" href="favicon.ico">  
	<!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="app/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="app/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="app/plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="app/dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="app/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="app/plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="app/plugins/summernote/summernote-bs4.min.css">


<!-- 	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header"><i class="bi bi-window-plus" style="font-size: 30px;"></i>
						&nbsp;&nbsp;&nbsp;<b>FORM E-RM RAWAT INAP</b>
					</div>
				</div>
			</div>
		</div>
	</div>
-->

<!-- <div class="container-fluid">
	<div class="row">

		<div class="col-sm-12">
			<div class="card">
				<div class="card-header"><b>IDENTITAS PASIEN</b><br>
					<span class="info-box-number">
						<?php echo 'NIK : '.$noktp.'<br>'; ?>					
						<?php echo 'NAMA LENGKAP : '.$nama.',&nbsp;&nbsp; NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.',&nbsp;&nbsp; UMUR : '.$umur.'<br>'; ?>
						<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
					</span>
				</div>
			</div>
		</div>

	</div>
</div> -->
<div class="row">

	<?php 
	if($role=='DOKTER'){
		echo "
		<div class='col-sm-12'>
		<div class='info-box-content'>
		&nbsp;&nbsp;&nbsp;
		<a href='diagnosis.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 1. Diagnosa</a>
		<a href='anamnesis_medis.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 2. Anamnesis</a>
		<a href='soap_dokter.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 3. CPPT</a>
		<a href='form_soap_verif.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 4. Verifikasi</a>
		<a href='penunjang.php?id=$id|$user' class='btn btn-warning'><i class='bi bi-arrow-right-circle'></i> Ambil Hasil Penunjang</a>
		<a href='resume.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 5. Resume Medis</a>
		<a href='jadwaloperasi.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 6. Laporan Operasi</a>
		</div>
		</div>
		";
		header("Location: indexdokter.php?id=$id|$user");
		exit();
	}
	?>

	<div class="col-sm-4">
		<div class="info-box">
			<span class="info-box-icon bg-info">1&nbsp;<i class="bi bi-card-checklist"></i></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Penerimaan Pasien</span>
				<span class="info-box-number">
					<a href="identitas_pasien.php?id=<?php echo $id.'|'.$user;?>">1.Identitas Pasien</a><br>
					<a href="generalconsent.php?id=<?php echo $id.'|'.$user;?>">2.General Consent / Persetujuan Umum</a><br>
					<a href="transfer_pasien.php?id=<?php echo $id.'|'.$user;?>">3.Form Transfer Antar Unit Pelayanan</a><br>											
					<!-- <a href='#'>3.Assesmen Awal Keperwatan :</a>  -->
				</span>
			</div>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="info-box">
			<span class="info-box-icon bg-info">2&nbsp;<i class="bi bi-card-checklist"></i></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Asesmen Awal Keperawatan</span>
				<span class="info-box-number">
					<?php 
							if($hari_u < 28 and $tahun_u <= 0){ //neonatus
								$fe_anamnesis = "<a href='form_assesmen_neonatus.php?id=$id|$user' >Neonatus</a>";
							}else if($tahun_u >=0 and $tahun_u <=17){ //anak
								$fe_anamnesis = "<a href='form_assesmen_anak.php?id=$id|$user' >Anak</a>";
							}else if($tahun_u >17 and $tahun_u <=60){ //dewasa

								if($role=='BIDAN'){
									$fe_anamnesis = 
									"<a href='form_assesmen_bersalin.php?id=$id|$user' >Bersalin</a>";
								}else{
									$fe_anamnesis = 
									"<a href='form_assesmen_dewasa.php?id=$id|$user' >Dewasa</a>";
								}
							}else if($tahun_u >60){//geriatri
								$fe_anamnesis = "<a href='form_assesmen_geriatri.php?id=$id|$user' >Geriatri</a>";
							}
							echo $fe_anamnesis;
							?>
						</span>
					</div>
				</div>
			</div>


			<div class="col-sm-4">
				<div class="info-box">
					<span class="info-box-icon bg-info">3&nbsp;<i class="bi bi-card-checklist"></i></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Asuhan Keperawatan</span>
						<span class="info-box-number">
							<!-- <span class="info-box-text"><a href="askep.php?id=<?php echo $id.'|'.$user;?>"><b>Asuhan Keperawatan</b></a></span> -->
							<!-- <span class="info-box-text"><a href="form_asuhankeperawatan1.php?id=<?php echo $id.'|'.$user;?>"><b>b. Asuhan Kebidanan</b></a></span> -->

							<?php 
							if($role=='BIDAN'){
								$asuhan = 
								"<a href='form_asuhankebidanan1.php?id=$id|$user' >Asuhan Kebidanan</a>";
							}else{
								$asuhan = 
								"<a href='askep.php?id=$id|$user' >Asuhan Keperawatan</a>";
							}

							echo $asuhan;

							?>

						</span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="info-box">
					<span class="info-box-icon bg-info">4&nbsp;<i class="bi bi-card-checklist"></i></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Asesmen Lanjutan</span>
						<span class="info-box-number">
							<a href="soap.php?id=<?php echo $id.'|'.$user;?>">CPPT</a> <br>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="info-box">
					<span class="info-box-icon bg-info">5&nbsp;<i class="bi bi-card-checklist"></i></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Monitoring Pasien</span>
						<span class="info-box-number">
							<a href="observasi.php?id=<?php echo $id.'|'.$user;?>">Monitoring</a> <br>
							<a href="r_ews.php?id=<?php echo $id.'|'.$user;?>">Report Monitoring</a> <br>
							<a href="r_vitalsign.php?id=<?php echo $id.'|'.$user;?>">Grafik EWS</a> <br>
							<a href="observasi_icu.php?id=<?php echo $id.'|'.$user;?>">Observasi ICU</a> <br>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="info-box">
					<span class="info-box-icon bg-info">6&nbsp;<i class="bi bi-card-checklist"></i></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Pemberian Obat</span>
						<span class="info-box-number">
							<a href="rpo.php?id=<?php echo $id.'|'.$user;?>">Rekam Pemberian Obat</a> <br>
						</span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-4">
				<div class="info-box">
					<span class="info-box-icon bg-info">7&nbsp;<i class="bi bi-card-checklist"></i></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Edukasi Terintegrasi</span>
						<span class="info-box-number">
							<a href="#">Edukasi Terintegrasi
							</a> <br>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-8">
				<div class="info-box">
					<span class="info-box-icon bg-info">8&nbsp;<i class="bi bi-card-checklist"></i></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Perencanaan Pemulangan Pasien
						</span>
						<span class="info-box-number">
							<a href="resume.php?id=<?php echo $id.'|'.$user;?>">1.Pasien Sembuh / Resume Medis</a> <br> 
							<a href="meninggal.php?id=<?php echo $id.'|'.$user;?>">2.Pasien Meninggal / Form Surat Ket Kematian</a> <br> 
							<a href="meninggal.php?id=<?php echo $id.'|'.$user;?>">3.Rujuk ke RS Lain / Form Rujuk</a> <br>
							<a href="jadwaloperasi.php?id=<?php echo $id.'|'.$user;?>">4.Laporan Operasi</a> <br>
							<a href="partograf_a.php?id=<?php echo $id.'|'.$user;?>">5.Laporan Partograf</a> <br>
							<a href="robson.php?id=<?php echo $id.'|'.$user;?>">6.Lembar Robson</a> <br>
						</span>
					</div>
				</div>
			</div>
		</div>


		<hr>

		<div class="row">
			<div class="col-sm-6">
				<div class="info-box">
					<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Perencanaan Pemulangan Pasien</span>
						<span class="info-box-number">
							<a href="resume.php?id=<?php echo $id.'|'.$user;?>">1.Pasien Sembuh / Resume Medis</a> <br> 
							<a href="meninggal.php?id=<?php echo $id.'|'.$user;?>">2.Pasien Meninggal / Form Surat Ket Kematian</a> <br> 
							<a href="meninggal.php?id=<?php echo $id.'|'.$user;?>">3.Rujuk ke RS Lain / Form Rujuk</a> <br> 
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="info-box">
					<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Laporan Lainnya</span>
						<span class="info-box-number">
							<a href="jadwaloperasi.php?id=<?php echo $id.'|'.$user;?>">1.Laporan Operasi</a> <br>
							<a href="partograf_a.php?id=<?php echo $id.'|'.$user;?>">2.Laporan Partograf</a> <br>
							<a href="robson.php?id=<?php echo $id.'|'.$user;?>">3.Lembar Robson</a> <br>
						</span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="info-box">
					<span class="info-box-icon bg-danger"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Master</span>
						<span class="info-box-number">
							<a href="m_form.php?id=<?php echo $id.'|'.$user;?>">Form</a> - 
							<a href="m_alergi.php?id=<?php echo $id.'|'.$user;?>">Alergi</a> - 
							<a href="m_notif.php?id=<?php echo $id.'|'.$user;?>">Notif</a> - 
							<a href="m_diet.php?id=<?php echo $id.'|'.$user;?>">Diet</a> -
							<a href="askep.php?id=<?php echo $id.'|'.$user;?>">Askep</a>
						</span>
					</div>
				</div>
			</div>
		</div>

		<hr>
		&nbsp;<i>form satu sehat</i><br>
		<div class="row">
			<div class="col-sm-6">
				<div class="info-box">
					<span class="info-box-icon bg-warning"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Pemeriksaan Spesialistik</span>
						<span class="info-box-number">
							<a href="rpo.php?id=<?php echo $id.'|'.$user;?>">1.Riwayat Penggunaan Obat</a> <br>
							<a href="rencana_pulang.php?id=<?php echo $id.'|'.$user;?>">2.Perencanaan Pemulangan Pasien</a> <br> 
							<a href="rencana_rawat.php?id=<?php echo $id.'|'.$user;?>">3.Rencana Rawat</a> <br>
							<a href="instruksi_medik.php?id=<?php echo $id.'|'.$user;?>">4.Instruksi Medik dan Keperawatan</a> <br>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="info-box">
					<span class="info-box-icon bg-warning"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Pemeriksaan Spesialistik</span>
						<span class="info-box-number">
							<a href="penunjang.php?id=<?php echo $id.'|'.$user;?>">5.Pemeriksaan Penunjang (Laborat & Radiologi)</a> <br> 
							<a href="diagnosis.php?id=<?php echo $id.'|'.$user;?>">6.Diagnosis</a> <br>
							<a href="informconsent.php?id=<?php echo $id.'|'.$user;?>">7.Persetujuan / Penolakan Tindakan (Informed Consent)</a> <br>
							<a href="terapi.php?id=<?php echo $id.'|'.$user;?>">8.Terapi (Tindakan & Obat)</a> <br>
						</span>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-6">
				<div class="info-box">
					<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Observasi Harian RS</span>
						<span class="info-box-number">
							<a href="form_asuhankeperawatan1.php?id=<?php echo $id.'|'.$user;?>">Asuhan Keperawatan</a> <br>
							<!-- <a href="form_asuhankebidanan1.php?id=<?php echo $id.'|'.$user;?>">Asuhan Kebidanan</a> <br> 													 -->
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="info-box">
					<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Report Rawat Inap</span>
						<span class="info-box-number">
							<a href="resume.php?id=<?php echo $id.'|'.$user;?>">5.Resume Medis</a> <br> 
							<a href="jadwaloperasi.php?id=<?php echo $id.'|'.$user;?>">6.Laporan Operasi</a> <br>
							<a href="partograf_a.php?id=<?php echo $id.'|'.$user;?>">7.Laporan Partograf</a> <br>
						</span>
					</div>
				</div>
			</div>
		</div>

