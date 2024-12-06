<?php 
session_start();
include ("koneksi.php");

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

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

$row = explode(',',$umur);
$tahun_u  = $row[0];
$tahun_u = intval(substr($tahun_u, 0,3));

//$tahun_u = 61;

$bulan_u = $row[1]; 
$bulan_u = intval(substr($bulan_u, 0,3));

$hari_u = $row[2]; 
$hari_u = intval(substr($hari_u, 0,3));

$qu="SELECT ALERGI  FROM Y_alergi where norm='$norm'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

$qu2="SELECT ket as notif  FROM ERM_NOTIF where noreg='$noreg'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$notif = $d1u2['notif'];

$qu3="SELECT diet as diet  FROM ERM_DIET where noreg='$noreg'";
$h1u3  = sqlsrv_query($conn, $qu3);        
$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
$diet = $d1u3['diet'];

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


	<div class="container-fluid">
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

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3">
				<div class="info-box">
					<span class="info-box-icon bg-info"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Master</span>
						<span class="info-box-number">
							<a href="m_form.php?id=<?php echo $id.'|'.$user;?>">Form</a> - 
							<a href="m_alergi.php?id=<?php echo $id.'|'.$user;?>">Alergi</a> - 
							<a href="m_notif.php?id=<?php echo $id.'|'.$user;?>">Notif</a> - 
							<a href="m_diet.php?id=<?php echo $id.'|'.$user;?>">Diet</a>
						</span>
					</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="info-box">
					<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Anamnesis Awal</span>
						<span class="info-box-number">
							<?php 
					if($hari_u < 28 and $tahun_u <= 0){ //neonatus
						$fe_anamnesis = "<a href='form_assesmen_neonatus.php?id=$id|$user' >Neonatus</a>";
					}else if($tahun_u >0 and $tahun_u <=17){ //anak
						$fe_anamnesis = "<a href='form_assesmen_anak.php?id=$id|$user' >Anak</a>";
					}else if($tahun_u >17 and $tahun_u <=60){ //dewasa
						$fe_anamnesis = 
						"<a href='form_assesmen_dewasa.php?id=$id|$user' >Dewasa</a> -						
						<a href='form_assesmen_bersalin.php?id=$id|$user' >Bersalin</a>";
					}else if($tahun_u >60){//geriatri
						$fe_anamnesis = "<a href='form_assesmen_geriatri.php?id=$id|$user' >Geriatri</a>";
					}
					echo $fe_anamnesis;
					?>
					<!-- - <a href="erm.php?id=<?php echo $id.'|'.$user;?>" >Medis</a> -->
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="info-box">
			<span class="info-box-icon bg-warning"><i class="bi bi-file-earmark-text"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Rencana Asuhan & Implementasi</span>
				<span class="info-box-number">
					<a href="form_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>">keperawatan</a> - 
					<a href="form_asuhankebidanan.php?id=<?php echo $id.'|'.$user;?>">kebidanan</a>
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="info-box">
			<span class="info-box-icon bg-danger"><i class="bi bi-file-earmark-text"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">CPPT</span>
				<span class="info-box-number">
					<a href="soap.php?id=<?php echo $id.'|'.$user;?>">soap</a> - 
					<!-- <a href="form_soap.php?id=<?php echo $id.'|'.$user;?>">new</a> -  -->
					<a href="list_soap.php?id=<?php echo $id.'|'.$user;?>">edit</a> -
					<a href="form_soap_verif.php?id=<?php echo $id.'|'.$user;?>">verifikasi dokter</a>
				</span>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="info-box">
			<span class="info-box-icon bg-info"><i class="bi bi-file-earmark-text"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Observasi Rawat Inap</span>
				<span class="info-box-number">
					<a href="observasi.php?id=<?php echo $id.'|'.$user;?>">new</a> - 
					<a href="observasi_list.php?id=<?php echo $id.'|'.$user;?>">list</a>
				</span>
			</div>
		</div>
	</div>		
	<div class="col-sm-3">
		<div class="info-box">
			<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Resume Medis</span>
				<span class="info-box-number">
					<a href="resume.php?id=<?php echo $id.'|'.$user;?>">edit</a>
				</span>
			</div>
		</div>

	</div>
</div>

