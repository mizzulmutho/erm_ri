<?php 
session_start();
include ("koneksi_dokter.php");

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

// $qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
// $h1u  = sqlsrv_query($conn, $qu);        
// $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
// $role = $d1u['role'];
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
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-6">
			<div class="info-box">
				<span class="info-box-icon bg-info"><i class="bi bi-file-earmark-text"></i></span>
				<div class="info-box-content">
					<span class="info-box-text"><!-- General Consent / Persetujuan Umum --></span>
					<span class="info-box-number">
						<a href="resume.php?id=<?php echo $id.'|'.$user;?>">1.Resume Medis</a> <br>
						<a href="jadwaloperasi.php?id=<?php echo $id.'|'.$user;?>">2.Laporan Operasi</a> <br>
						<a href="assesmen.php?id=<?php echo $id.'|'.$user;?>">3.Assesmen Medis</a> <br>
						<a href="soap.php?id=<?php echo $id.'|'.$user;?>">4.SOAP</a> <br>
					</span>
				</div>
			</div>
		</div>

