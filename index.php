<?php 
session_start();
include ("koneksi.php");

include ("header_px2.php");

include ("mode.php");

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$sbu = $row[2]; 

$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1,ARM_REGISTER.TUJUAN
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
// $KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);
$KODEUNIT = trim($d1u['TUJUAN']);
$sbu = trim($d1u['KET1']);


$qt="SELECT tr1,tr2 FROM   ERM_RI_TRANSFER where noreg='$noreg' order by id desc";
$hqt  = sqlsrv_query($conn, $qt);        
$dhqt  = sqlsrv_fetch_array($hqt, SQLSRV_FETCH_ASSOC); 
$tr1 = $dhqt['tr1'];
$tr2 = $dhqt['tr2'];
$row = explode('-',$tr2);
$tr2  = $row[1];

if($tr2){
	$KODEUNIT=trim($tr2);
}

$qun="SELECT namaunit FROM   Afarm_Unitlayanan where kodeunit='$KODEUNIT'";
$hqun  = sqlsrv_query($conn, $qun);        
$dhqun  = sqlsrv_fetch_array($hqun, SQLSRV_FETCH_ASSOC); 
echo "&nbsp;&nbsp;&nbsp;<font size='4' color='#F93827'><b>Unit : ".$namaunit = $dhqun['namaunit'] .' - '. $KODEUNIT."</b></font>";


$qu="SELECT dpjp FROM  V_ERM_RI_DPJP_ASESMEN where noreg='$noreg' and dpjp is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$dpjp = $d1u['dpjp'];
$row = explode('-',$dpjp);
$kodedokter  = trim($row[0]);

if(empty($dpjp)){
	$qu="SELECT top(1)nama_dokter as dpjp FROM   ERM_RI_RABER where noreg='$noreg' order by id";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$dpjp = $d1u['dpjp'];
	$row = explode('-',$dpjp);
	$kodedokter  = trim($row[0]);

}

echo '&nbsp;&nbsp;&nbsp;Dokter : '.$dpjp;
echo "<br>";
echo "&nbsp;&nbsp;&nbsp;<font size='3' color='#F93827'>Untuk mengganti unit isikan di Form Transfer Unit !</font>";
echo "<br>";echo "<hr>";


 //get trasnfer pasien igd..
$qd="
SELECT        TOP (100) ID, NOREG, IDHEADER, NORM
FROM            ERM_TRANSFER_PASIEN
WHERE NORM like '%$norm%'
order by ID DESC
";
$hqd  = sqlsrv_query($conn, $qd);        
$dhqd  = sqlsrv_fetch_array($hqd, SQLSRV_FETCH_ASSOC); 
$id_trs = trim($dhqd['ID']);
$id_norm = trim($dhqd['NORM']);
$id_hdr_trs = trim($dhqd['IDHEADER']);
$noreg_trs = trim($dhqd['NOREG']);

$link_transfer_igd="http://192.168.10.245/transferpx/indexrspg.php?id=$id_norm&noreg=$noreg_trs&idheader=$id_hdr_trs";

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
	<!-- Bootstrap 5 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

	<style type="text/css">
		.new-link {
			background-color: #b6f8b6; /* Hijau muda */
			color: #333; /* Warna teks */
			/*padding: 10px;*/
			border-radius: 5px;
			display: inline-flex;
			align-items: center;
			text-decoration: none;
			position: relative;
			transition: background-color 0.3s ease;
		}

		.new-link:hover {
			background-color: #a3f0a3; /* Efek hover sedikit lebih gelap */
		}

		.new-link .fa-bell {
			margin-right: 8px; /* Jarak antara ikon dan teks */
		}

		.new-link::after {
			content: " New";
			position: absolute;
			top: -5px;
			right: -10px;
			background-color: red;
			color: white;
			padding: 2px 6px;
			border-radius: 10px;
			font-size: 12px;
		}
	</style>		

	<div class="row">

		<?php 
		if($role=='DOKTER'){
		//cek assesment awal dokter
			$c_asawal="
			SELECT am1 
			FROM ERM_RI_ANAMNESIS_MEDIS
			where noreg='$noreg'";
			$hc_asawal  = sqlsrv_query($conn, $c_asawal);        
			$dhc_asawal  = sqlsrv_fetch_array($hc_asawal, SQLSRV_FETCH_ASSOC); 

			$am1= $dhc_asawal['am1'];
			$user2 = substr($user, 0,3);

			//cek verifikasi...
			$qv="
			SELECT        TOP (1) dpjp
			FROM            ERM_SOAP
			WHERE        verif_dpjp is null AND (dpjp = '$user2') AND (tanggal > '2025-03-04')
			union
			SELECT        TOP (1) dokter as dpjp
			FROM            ERM_TULBAKON
			WHERE        verif_dpjp is null AND (dokter = '$user2') AND (tglentry > '2025-03-04')
			union
			SELECT TOP (1) dpjp as dokter
			FROM ERM_RI_ASSESMEN_AWAL_DEWASA
			WHERE tglverif IS NULL 
			AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$user2'
			AND tglentry > '2025-03-04'
			union
			SELECT TOP (1) dpjp as dokter
			FROM ERM_RI_ASSESMEN_AWAL_ANAK
			WHERE tglverif IS NULL 
			AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$user2'
			AND tglentry > '2025-03-04'
			union
			SELECT TOP (1) dpjp as dokter
			FROM ERM_RI_ASSESMEN_AWAL_NEONATUS
			WHERE tglverif IS NULL 
			AND LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) = '$user2'
			AND tglentry > '2025-03-04'
			";
			$hv  = sqlsrv_query($conn, $qv);        
			$dv  = sqlsrv_fetch_array($hv, SQLSRV_FETCH_ASSOC); 
			$cekverif = trim($dv['dpjp']);

			if($cekverif){
				echo "
				<script>
				window.location.replace('verifikasi_dokter.php?id=$id|$user');
				</script>
				";
			}

			if(empty($am1)){
				$tampil="
				<div class='col-sm-12'>
				<div class='btn-group'>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
				<a href='listdata.php?id=$user|$sbu|$unit|$noreg' class='btn btn-info'><i class='bi bi-hospital'></i> Home</a>
				&nbsp;
				<a href='' class='btn btn-success'><i class='bi bi-arrow-clockwise'></i> </a>
				&nbsp;
				<div class='dropdown'>
				<a class='btn btn-success dropdown-toggle' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>Report</a>
				<ul class='dropdown-menu'>
				<li><a class='dropdown-item' href='r_rm.php?id=$id|$user'>Rekam Medik Pasien</a></li>
				<li><a class='dropdown-item' href='r_soap_dokter.php?id=$id|$user' target='_blank'>CPPT</a></li>
				<li><a class='dropdown-item' href='resume_print.php?id=$id|$user' target='_blank'>Resume Medis</a></li>
				<li><a class='dropdown-item' href='r_ews.php?id=$id|$user' target='_blank'>Monitoring EWS</a></li>
				<li><a class='dropdown-item' href='r_vitalsign.php?id=$id|$user'>Dashboard Vital Sign</a></li>
				<li><a class='dropdown-item' href='$link_transfer_igd' target='_blank'>Pemeriksaan dari IGD</a></li>
				</ul>
				</div>
				</div>
				<hr>
				<div class='col-sm-12'>
				<div class='info-box-content'>
				&nbsp;&nbsp;&nbsp;
				<a href='anamnesis_medis.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 1. Asesmen Awal</a>
				<a href='soap_dokter.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 2. CPPT</a>
				<a href='diagnosis.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 3. Diagnosa</a>
				<a href='http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/$KODEUNIT/$noreg/$norm/$user2' class='btn btn-success' target='_blank' ><i class='bi bi-arrow-right-circle'></i> 4. Penunjang : EResep - ELaborat - ERadiologi</a>
				<br><br>
				&nbsp;&nbsp; Informasi : Data Assesment Awal masih kosong untuk membuka menu resume medis simpan data Assesment Awal terlebih dahulu ,
				</div>
				</div>
				";
			}else{

				$c_diagnosa="
				SELECT resume20,resume21,resume22
				FROM ERM_RI_RESUME
				where noreg='$noreg'";
				$hc_diagnosa  = sqlsrv_query($conn, $c_diagnosa);        
				$dc_diagnosa  = sqlsrv_fetch_array($hc_diagnosa, SQLSRV_FETCH_ASSOC); 

				$resume20= $dc_diagnosa['resume20'];
				$resume21= $dc_diagnosa['resume21'];
				$resume22= $dc_diagnosa['resume22'];

				if(empty($resume20)){
					$tampil="
					<div class='col-sm-12'>
					<div class='btn-group'>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
					<a href='listdata.php?id=$user|$sbu|$unit|$noreg' class='btn btn-info'><i class='bi bi-hospital'></i> Home</a>
					&nbsp;
					<a href='' class='btn btn-success'><i class='bi bi-arrow-clockwise'></i> </a>
					&nbsp;
					<div class='dropdown'>
					<a class='btn btn-success dropdown-toggle' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>Report</a>
					<ul class='dropdown-menu'>
					<li><a class='dropdown-item' href='r_rm.php?id=$id|$user'>Rekam Medik Pasien</a></li>
					<li><a class='dropdown-item' href='r_soap_dokter.php?id=$id|$user' target='_blank'>CPPT</a></li>
					<li><a class='dropdown-item' href='resume_print.php?id=$id|$user' target='_blank'>Resume Medis</a></li>
					<li><a class='dropdown-item' href='r_ews.php?id=$id|$user'>Monitoring EWS</a></li>
					<li><a class='dropdown-item' href='r_vitalsign.php?id=$id|$user'>Dashboard Vital Sign</a></li>
					<li><a class='dropdown-item' href='$link_transfer_igd' target='_blank'>Pemeriksaan dari IGD</a></li>
					</ul>
					</div>
					</div>
					<hr>
					<div class='col-sm-12'>
					<div class='info-box-content'>
					&nbsp;&nbsp;&nbsp;
					<a href='anamnesis_medis.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 1. Asesmen Awal</a>
					<a href='diagnosis.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 2. Diagnosa</a>
					<a href='soap_dokter.php?id=$id|$user' class='btn btn-info'><i class='bi bi-arrow-right-circle'></i> 3. CPPT</a>
					<a href='http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/$KODEUNIT/$noreg/$norm/$user2' class='btn btn-success' target='_blank' ><i class='bi bi-arrow-right-circle'></i> 4. Penunjang : EResep - ELaborat - ERadiologi</a>
					<br><br>
					&nbsp;&nbsp; Informasi : Data Diagnosa masih kosong untuk membuka menu resume medis simpan data Diagnosa terlebih dahulu ,
					</div>
					</div>
					";

				}else{

					$tampil="
					<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
					<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css'>

					<div class='col-sm-12'>
					<div class='btn-group'>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;					
					<a href='listdata.php?id=$user|$sbu|$unit|$noreg' class='btn btn-info'><i class='bi bi-hospital'></i> Home</a>
					&nbsp;
					<a href='' class='btn btn-success'><i class='bi bi-arrow-clockwise'></i> </a>
					&nbsp;
					<div class='dropdown'>
					<a class='btn btn-success dropdown-toggle' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'>Report</a>
					<ul class='dropdown-menu'>
					<li><a class='dropdown-item' href='r_rm.php?id=$id|$user'>Rekam Medik Pasien</a></li>
					<li><a class='dropdown-item' href='r_soap_dokter.php?id=$id|$user' target='_blank'>CPPT</a></li>
					<li><a class='dropdown-item' href='resume_print.php?id=$id|$user' target='_blank'>Resume Medis</a></li>
					<li><a class='dropdown-item' href='r_ews.php?id=$id|$user'>Monitoring EWS</a></li>
					<li><a class='dropdown-item' href='r_vitalsign.php?id=$id|$user'>Dashboard Vital Sign</a></li>
					<li><a class='dropdown-item' href='$link_transfer_igd' target='_blank'>Pemeriksaan dari IGD</a></li>
					</ul>
					</div>
					</div>
					<hr>
					<div class='row g-3'>
					<div class='col-6 col-md-4'>
					<a href='anamnesis_medis.php?id=$id|$user' class='btn btn-primary w-100'><i class='bi bi-list-check'></i> Asesmen Awal</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='diagnosis.php?id=$id|$user' class='btn btn-primary w-100'><i class='bi bi-clipboard-heart'></i> Diagnosa</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='soap_dokter.php?id=$id|$user' class='btn btn-primary w-100'><i class='bi bi-journal-medical'></i> CPPT</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='verifikasi_dokter.php?id=$id|$user' class='btn btn-primary w-100'><i class='bi bi-check-circle'></i> Verifikasi</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='resume_dokter.php?id=$id|$user' class='btn btn-primary w-100'><i class='bi bi-file-earmark-medical'></i> Resume Medis</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='jadwaloperasi.php?id=$id|$user' class='btn btn-primary w-100'><i class='bi bi-calendar-check'></i> Laporan Operasi</a>
					</div>
					</div>
					<hr>
					<div class='row g-3'>
					<div class='col-6 col-md-4'>
					<a href='edukasi.php?id=$id|$user' class='btn btn-success w-100'><i class='bi bi-book-half'></i> Edukasi</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='r_vitalsign.php?id=$id|$user' class='btn btn-success w-100'><i class='bi bi-graph-up'></i> Grafik TTV</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/$KODEUNIT/$noreg/$norm/$user2' class='btn btn-success w-100' target='_blank'>
					<i class='bi bi-prescription'></i> Penunjang: EResep - ELaborat - ERadiologi
					</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='riwayat_pasien.php?id=$id|$user' class='btn btn-success'>
					<i class='fa fa-bell'></i> Riwayat Hasil Laborat & Radiologi
					</a>
					</div>
					</div>
					<hr>
					<div class='row g-3'>
					<div class='col-6 col-md-4'>
					<a href='laborat_dokter.php?id=$id|$user' class='btn btn-warning w-100'><i class='bi bi-droplet'></i> Hasil Laborat</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='radiologi_dokter.php?id=$id|$user' class='btn btn-warning w-100'><i class='bi bi-image'></i> Hasil Radiologi</a>
					</div>
					<div class='col-6 col-md-4'>
					<a href='surat_sakit.php?id=$id|$user' class='btn btn-warning w-100'><i class='bi bi-file-earmark-text'></i> Surat Sakit</a>
					</div>
					</div>
					</div>
					
					";

				}
			}

			echo $tampil;

			exit();
		}
		?>		

		<row>
			<div class="col-sm-12">
				<div class="btn-group">
					<a href='listdata.php?id=<?php echo $user.'|'.$sbu.'|'.$unit.'|'.$noreg;?>' class='btn btn-info'><i class="bi bi-hospital"></i> Home</a>
					&nbsp;
					<div class="dropdown">
						<a class="btn btn-info dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Report</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="r_rm.php?id=<?php echo $id.'|'.$user;?>">Rekam Medik Pasien</a></li>
							<li><a class="dropdown-item" href="r_anamnesis_medis.php?id=<?php echo $id.'|'.$user;?>" target="_blank">Assesmen Medik</a></li>
							<li><a class="dropdown-item" href="resume_print.php?id=<?php echo $id.'|'.$user;?>" target="_blank">Resume Medis</a></li>
							<li><a class="dropdown-item" href="r_soap.php?id=<?php echo $id.'|'.$user;?>">CPPT</a></li>
							<li><a class="dropdown-item" href="r_ews.php?id=<?php echo $id.'|'.$user;?>" target='_blank'>Monitoring EWS</a></li>
							<li><a class="dropdown-item" href="r_vitalsign.php?id=<?php echo $id.'|'.$user;?>">Dashboard Vital Sign</a></li>
							<li><a class="dropdown-item" href="r_observasi.php?id=<?php echo $id.'|'.$user;?>">Detail Observasi</a></li>
							<li><a class="dropdown-item" href="<?php echo $link_transfer_igd?>" target='_blank'>Pemeriksaan dari IGD</a></li>
							<li><a class="dropdown-item" href="r_penunjang.php?id=<?php echo $id.'|'.$user;?>">Penunjang</a></li>
							<li><a class="dropdown-item" href="r_obat.php?id=<?php echo $id.'|'.$user;?>">Riwayat Penggunaan Obat</a></li>
							<li><a class="dropdown-item" href="report_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>" target="_blank">Asuhan Keperawatan</a></li>
							<li><a class="dropdown-item" href="report_asuhankebidanan.php?id=<?php echo $id.'|'.$user;?>">Asuhan Kebidanan</a></li>
						</ul>
					</div>
					&nbsp;
					<div class="dropdown">
						<a class="btn btn-info dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Form ERM</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="identitas_pasien.php?id=<?php echo $id.'|'.$user;?>">Identitas Pasien</a></li>
							<li><a class="dropdown-item" href="list_soap.php?id=<?php echo $id.'|'.$user;?>">CPPT</a></li>
							<li><a class="dropdown-item" href="observasi.php?id=<?php echo $id.'|'.$user;?>">Monitoring</a></li>
							<li><a class="dropdown-item" href="resume.php?id=<?php echo $id.'|'.$user;?>">Resume Medik</a></li>
							<hr>
							&nbsp; - Assesmen Awal
							<li><a class="dropdown-item" href="p_anamnesis_medis.php?id=<?php echo $id.'|'.$user;?>">Asesmen Medik</a></li>
							<li><a class="dropdown-item" href="form_assesmen_kepneonatus.php?id=<?php echo $id.'|'.$user;?>">Neonatus(Keperawatan)</a></li>
							<li><a class="dropdown-item" href="form_assesmen_neonatus.php?id=<?php echo $id.'|'.$user;?>">Neonatus</a></li>
							<li><a class="dropdown-item" href="form_assesmen_anak.php?id=<?php echo $id.'|'.$user;?>">Anak</a></li>
							<li><a class="dropdown-item" href="form_assesmen_dewasa.php?id=<?php echo $id.'|'.$user;?>">Dewasa</a></li>
							<li><a class="dropdown-item" href="form_assesmen_geriatri.php?id=<?php echo $id.'|'.$user;?>">Geriatri</a></li>
							<li><a class="dropdown-item" href="form_assesmen_bersalin.php?id=<?php echo $id.'|'.$user;?>">Bersalin</a></li>
							&nbsp; - Rencana Asuhan
							<li><a class="dropdown-item" href="askep.php?id=<?php echo $id.'|'.$user;?>">Keperawatan</a></li>
							<li><a class="dropdown-item" href="form_asuhankebidanan1.php?id=<?php echo $id.'|'.$user;?>">Kebidanan</a></li>
							<li><a class="dropdown-item" href="form_asuhan_neonatus.php?id=<?php echo $id.'|'.$user;?>">Neonatus</a></li>
						</ul>

					</div>
					&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i> </a>
				</div>
			</div>
		</row>
		<hr>

		<style>
			.info-box:hover {
				transform: translateY(-5px);
			}
			.info-box-content a:hover {
				/*color: #0056b3;*/
				text-decoration: underline;
			}
		</style>
		
		<div class="col-sm-6">

			<!-- <style>
				.info-box {
					background: linear-gradient(135deg, #6a11cb, #2575fc);
					color: white;
					border-radius: 10px;
					display: flex;
					align-items: center;
					box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
				}
				.info-box-text {
					font-size: 18px;
					font-weight: bold;
				}

			</style> -->

			<div class="info-box">
				<span class="info-box-icon bg-info"><i class="bi bi-person-plus"></i> </span>
				<div class="info-box-content">
					<span class="info-box-text">Penerimaan Pasien</span>
					<span class="info-box-number">
						<a href="generalconsent.php?id=<?php echo $id.'|'.$user;?>">General Consent / Persetujuan Umum</a><br>
						<a href="transfer_pasien.php?id=<?php echo $id.'|'.$user;?>">Form Transfer Antar Unit Pelayanan</a><br>									<a href="raber.php?id=<?php echo $id.'|'.$user;?>">Daftar DPJP Pasien</a><br>											
					</span>
				</div>
			</div>

			<div class="info-box">
				<span class="info-box-icon bg-info"><i class="bi bi-clipboard-check"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Asesmen Awal Keperawatan</span>
					<span class="info-box-number">
						<?php 

							if($hari_u < 28 and $bulan_u < 9 and $tahun_u <= 0){ //neonatus
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
							<br>
							<a href='diagnosa_keperawatan.php?id=<?php echo $id.'|'.$user;?>'>Diagnosa Keperawatan</a>	
						</span>
					</div>
				</div>

				<?php if(empty($dpjp)){ ?>

					<div class="info-box">
						<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Pemeriksaan Penunjang (Laborat & Radiologi)</span>
							<span class="info-box-number">
								<a href="penunjang.php?id=<?php echo $id.'|'.$user;?>">Preview Hasil Laborat & Radiologi</a> <br> 
							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-success"><i class="bi bi-file-earmark-text"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Monitoring Pasien</span>
							<span class="info-box-number">
								<a href="observasi_icu.php?id=<?php echo $id.'|'.$user;?>">Observasi ICU</a> <br>
								<a href="rekon_obat.php?id=<?php echo $id.'|'.$user;?>">Rekonsiliasi Obat</a> <br>
								<a href="rpo2.php?id=<?php echo $id.'|'.$user;?>">Rekam Pemberian Obat</a> <br>
								<a href="form_discharge.php?id=<?php echo $id.'|'.$user;?>">Form Discharge</a>
							</span>
						</div>
					</div>

				<?php } ?>

				<?php if($dpjp){ ?>
					<div class="info-box">
						<span class="info-box-icon bg-info"><i class="bi bi-journal-check"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Rencana Asuhan Keperawatan</span>
							<span class="info-box-number">
								<!-- <span class="info-box-text"><a href="askep.php?id=<?php echo $id.'|'.$user;?>"><b>Asuhan Keperawatan</b></a></span> -->
								<!-- <span class="info-box-text"><a href="form_asuhankeperawatan1.php?id=<?php echo $id.'|'.$user;?>"><b>b. Asuhan Kebidanan</b></a></span> -->

								<?php 
								if($role=='BIDAN'){
									$asuhan = 
									// "<a href='form_asuhankebidanan1.php?id=$id|$user' >Asuhan Kebidanan</a>";
									"<a href='r_asuhankebidanan.php?id=$id|$user' >Rencana Asuhan Kebidanan</a>";
								}else{
									$asuhan = 
									// "<a href='askep.php?id=$id|$user' >Asuhan Keperawatan</a>";
									"<a href='r_asuhankeperawatan.php?id=$id|$user' >Rencana Asuhan Keperawatan</a>";
								}

								echo $asuhan;

								?>

							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-info"><i class="bi bi-clipboard-data"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Asesmen Lanjutan</span>
							<span class="info-box-number">
								<a href="list_soap.php?id=<?php echo $id.'|'.$user;?>">CPPT</a> <br>
								<a href="nyeri.php?id=<?php echo $id.'|'.$user;?>">Asesmen Ulang Nyeri</a> <br>
								<a href="resikojatuh.php?id=<?php echo $id.'|'.$user;?>">Asesmen Ulang Risiko Jatuh</a> <br>
								<a href="luka.php?id=<?php echo $id.'|'.$user;?>">Asesmen Luka</a> <br>
								<a href="informconsent.php?id=<?php echo $id.'|'.$user;?>">Persetujuan / Penolakan Tindakan (Informed Consent)</a> <br>
								<a href="phlebithis.php?id=<?php echo $id.'|'.$user;?>">Surveilans PPI</a> <br>		
								<a href="mpp.php?id=<?php echo $id.'|'.$user;?>">Skrining Manajer Pelayanan Pasien (MPP)</a> <br>								
							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-info"><i class="bi bi-heart-pulse"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Monitoring Pasien</span>
							<span class="info-box-number">
								<a href="observasi.php?id=<?php echo $id.'|'.$user;?>">Monitoring Harian</a> <br>
								<!-- <a href="r_ews.php?id=<?php echo $id.'|'.$user;?>">Report Monitoring</a> <br> -->
								<a href="r_vitalsign.php?id=<?php echo $id.'|'.$user;?>">Grafik EWS</a> <br>
								<a href="observasi_icu.php?id=<?php echo $id.'|'.$user;?>">Observasi ICU</a> <br>
							</span>
						</div>
					</div>


					<div class="info-box">
						<span class="info-box-icon bg-info"><i class="bi bi-capsule"></i></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Pemberian Obat</span>
							<span class="info-box-number">
								<a href="rpo2.php?id=<?php echo $id.'|'.$user;?>">Rekam Pemberian Obat</a>
								<br>
								<!-- <a href="http://192.168.10.4:1234/rekam_medik/entry_tindakan/rawat_inap/<?php echo $KODEUNIT; ?>/<?php echo $noreg; ?>/<?php echo $norm; ?>/resep/" target="_blank">Tulis e-Resep</a> <br> -->
								<a href="rekon_obat.php?id=<?php echo $id.'|'.$user;?>">Rekonsiliasi Obat</a> <br>
								<a href="form_discharge.php?id=<?php echo $id.'|'.$user;?>">Form Discharge</a> <br>
								<a href="form_discharge_integrasi.php?id=<?php echo $id.'|'.$user;?>">Discharge Planning Terintegrasi</a>
							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-info"><i class="bi bi-mortarboard"></i></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Edukasi Terintegrasi</span>
							<span class="info-box-number">
								<a href="edukasi.php?id=<?php echo $id.'|'.$user;?>">Edukasi Terintegrasi
								</a> <br>
							</span>
						</div>
					</div>

				</div>

				<div class="col-sm-6">
					<div class="info-box">
						<div class="info-box-content">
							<span class="info-box-text">Order Lab/Radiologi/Resep
							</span>
							<span class="info-box-number">
								<!-- 								<a href="http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/<?php echo $KODEUNIT.'/'.$noreg.'/'.$norm.'/'.$kodedokter;?>" class='btn btn-success' target='_blank' ><i class='bi bi-prescription2'></i> Penunjang : EResep - ELaborat - ERadiologi</a> -->	
								<a href="riwayat_pasien.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-success">
									<i class="fa fa-bell"></i> Riwayat Hasil Laborat & Radiologi
								</a>
							</span>
						</div>
					</div>

					<div class="info-box">
						<div class="info-box-content">
							<span class="info-box-text">Verifikasi Data
							</span>
							<span class="info-box-number">
								<a href="resume_approval.php?id=<?php echo $id.'|'.$user;?>" class='btn btn-success'><i class='bi bi-file-earmark-check'></i> Verifikasi Resume Medis</a>
								<a href="upload_document.php?id=<?php echo $id.'|'.$user;?>" class='btn btn-success'><i class='bi bi-file-earmark-check'></i> Upload Document</a>

							</span>
						</div>
					</div>

					<div class="info-box">
						<div class="info-box-content">
							<span class="info-box-text">Persiapan Operasi
							</span>
							<span class="info-box-number">
								<a href="http://192.168.10.194:3010/rekam_medik/entry_erm/rawat_ok/<?php echo $KODEUNIT.'/'.$noreg.'/'.$norm;?>/bedah/" class='btn btn-success' target='_blank' ><i class="bi bi-clipboard-check"></i> Lembar Persiapan Operasi</a>
							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-success"><i class="bi bi-person-walking"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Perencanaan Pemulangan Pasien
							</span>
							<span class="info-box-number">

								<?php 
								$qur="SELECT noreg FROM ERM_RI_RESUME_APPROVEL where noreg='$noreg'";
								$h1ur  = sqlsrv_query($conn, $qur);        
								$d1ur  = sqlsrv_fetch_array($h1ur, SQLSRV_FETCH_ASSOC); 
								$ceknoreg = trim($d1ur['noreg']);

								if(empty($ceknoreg)){
									?>

									<a href="resume.php?id=<?php echo $id.'|'.$user;?>">1.Pasien Sembuh / Resume Medis</a> - 
									<a href="resume_print.php?id=<?php echo $id.'|'.$user;?>" target="_blank" class='btn btn-warning'><i class="bi bi-info-circle"> print preview</i></a><br> 
									<?php 
								}else{
									?>
									<a href="resume_print.php?id=<?php echo $id.'|'.$user;?>" target="_blank">1.Pasien Sembuh / Resume Medis</a><br> 

									<?php
								} 
								?>

								<a href="surat_kematian.php?id=<?php echo $id.'|'.$user;?>">2.Pasien Meninggal / Form Surat Ket Kematian</a> <br> 
								<a href="rujuk_rslain.php?id=<?php echo $id.'|'.$user;?>">3.Rujuk ke RS Lain / Form Rujuk</a> <br>
								<a href="jadwaloperasi.php?id=<?php echo $id.'|'.$user;?>">4.Laporan Operasi</a> <br>
								<a href="partograf_a.php?id=<?php echo $id.'|'.$user;?>">5.Laporan Partograf</a> <br>
								<a href="robson.php?id=<?php echo $id.'|'.$user;?>">6.Lembar Robson</a> <br>
								<a href="lembar_transfusi.php?id=<?php echo $id.'|'.$user;?>">7.Lembar Tranfusi</a> <br>
							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-success"><i class="bi bi-file-medical"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Pemeriksaan Penunjang (Laborat & Radiologi)</span>
							<span class="info-box-number">
								<!-- <a href="http://139.255.11.205:8080//printResult/samples/ResultHisto.php?params=0&trans_code=202410301022279478665323553C20&no_rm=671602&no_lis=2410300177&no_order=<?php echo $noreg;?>" target="_blank"><i>Hasil Laborat</i></a>  -->
								<a href="laborat.php?id=<?php echo $id.'|'.$user;?>"><i>Hasil Laborat</i></a> 								
								<br> 									
								<a href="radiologi.php?id=<?php echo $id.'|'.$user;?>"><i>Hasil Radiologi</i></a>
								<br> 															
							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-warning"><i class="bi bi-file-earmark-text"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Master</span>
							<span class="info-box-number">
								<a href="m_form.php?id=<?php echo $id.'|'.$user;?>">Form</a> - 
								<a href="m_alergi.php?id=<?php echo $id.'|'.$user;?>">Alergi</a> - 
								<a href="m_notif.php?id=<?php echo $id.'|'.$user;?>">Notif</a> - 
								<a href="m_diet.php?id=<?php echo $id.'|'.$user;?>">Diet</a> -
								<a href="askep.php?id=<?php echo $id.'|'.$user;?>">Askep</a> - 
								<a href="resume_approval2.php?id=<?php echo $id.'|'.$user;?>">Approval</a>
							</span>
						</div>
					</div>

					<div class="info-box">
						<span class="info-box-icon bg-warning"><i class="bi bi-file-earmark-text"></i></span>
						<div class="info-box-content">
							<span class="info-box-text">Form Satu Sehat</span>
							<span class="info-box-number">
								<!-- <a href="penunjang.php?id=<?php echo $id.'|'.$user;?>">5.Pemeriksaan Penunjang (Laborat & Radiologi)</a> <br>  -->
								<a href="diagnosis.php?id=<?php echo $id.'|'.$user;?>">Diagnosis</a> <br>
								<!-- <a href="informconsent.php?id=<?php echo $id.'|'.$user;?>">Persetujuan / Penolakan Tindakan (Informed Consent)</a> <br> -->
								<!-- <a href="terapi.php?id=<?php echo $id.'|'.$user;?>">8.Terapi (Tindakan & Obat)</a> <br> -->
								<a href="rencana_pulang.php?id=<?php echo $id.'|'.$user;?>">Perencanaan Pemulangan Pasien</a> <br>
								<a href="rencana_rawat.php?id=<?php echo $id.'|'.$user;?>">Rencana Rawat</a> <br>
								<a href="instruksi_medik.php?id=<?php echo $id.'|'.$user;?>">Instruksi Medik dan Keperawatan</a> <br> 
							</span>
						</div>
					</div>

				</div>
			<?php }else{
				?>
				&nbsp;Untuk menampilkan menu secara detail, masukkan dulu DPJP di menu assesment awal !

				<?php

			} ?>
			<hr>

