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
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

	<br>
	<div class="container">

		<div class="row">

			<div class="col-sm-3">
				<p class="card-text">
					<font size='2px'>
						Norm : <?php echo $norm;?><br>						
						Nama : <?php echo $nama;?>
					</font>
				</p>
			</div>

			<div class="col-sm-3">
				<p class="card-text">
					<font size='2px'>
						Tgl Lahir : <?php echo $tgllahir;?><br>
						Alamat : <?php echo $alamatpasien;?>
					</font>
				</p>
			</div>

			<div class="col-sm-6">
				<p class="card-text">
					<font size='2px'>
						Umur : <?php echo $umur;?><br>
						NOTIF : <font size="3px" color="red"><b><?php echo $notif;?></b></font>
					</font>
				</p>
			</div>

		</div>
		<div class="row">
			<div class="col-sm-6">
				<font size='2px'>
					ALERGI : <?php echo $alergi; ?>
				</font>
			</div>		
			<div class="col-sm-6">
				<font size='2px'>
					DIET : <?php echo $diet; ?>
				</font>
			</div>		

		</div>
		<hr>
		<div class="row">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header"><i class="bi bi-window-plus" style="font-size: 30px;"></i>
						&nbsp;&nbsp;&nbsp;<b>FORM INPUT</b>
					</div>
					<div class="card-body">
						<b>1. Anamnesis Awal </b>
						<?php 
					// dewasa : >18 th s/d 60
					// anak : 28 hari s/d 17 th
					// neonatus :0 hari s/d 28 hari
					// geriatri : >60 th
					// bersalin : ikut dewasa

					if($hari_u < 28 and $tahun_u <= 0){ //neonatus
						$fe_anamnesis = "<a href='form_assesmen_neonatus.php?id=$id|$user' class='btn btn-primary btn-sm'>Neonatus</a>";
					}else if($tahun_u >0 and $tahun_u <=17){ //anak
						$fe_anamnesis = "<a href='form_assesmen_anak.php?id=$id|$user' class='btn btn-primary btn-sm'>Anak</a>";
					}else if($tahun_u >17 and $tahun_u <=60){ //dewasa
						$fe_anamnesis = 
						"<a href='form_assesmen_dewasa.php?id=$id|$user' class='btn btn-primary btn-sm'>Dewasa</a>
						/
						<a href='form_assesmen_bersalin.php?id=$id|$user' class='btn btn-primary btn-sm'>Bersalin</a>";
					}else if($tahun_u >60){//geriatri
						$fe_anamnesis = "<a href='form_assesmen_geriatri.php?id=$id|$user' class='btn btn-primary btn-sm'>Geriatri</a>";
					}

					echo $fe_anamnesis;
					?>
					/
					<a href="erm.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">form Medis</a>
					/
					<a href="m_form.php?id=<?php echo $id.'|'.$user;?>" class="btn btn- btn-sm"><i>Master</i></a>
					<br>	
					<a href="m_alergi.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-success btn-sm"><i>Alergi</i></a>
					<a href="m_notif.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-success btn-sm"><i>Notif</i></a>
					<a href="m_diet.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-success btn-sm"><i>Diet</i></a>
					<hr>

					<b>2. Rencana Asuhan & Implementasi</b> :
					<a href="form_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">keperawatan</a>
					<a href="form_asuhankebidanan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-success btn-sm">kebidanan</a>
					<hr>
					<b>3. C P P T</b> :
					<a href="form_soap.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">new</a>
					<a href="list_soap.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-success btn-sm">edit</a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="form_soap_verif.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">verifikasi dokter</a>
					<!-- <a href="form_soap_operan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">operan sift</a> -->
					<hr>
					<b>4. OBSERVASI RAWAT INAP</b> :
					<a href="observasi.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">new</a>
					<a href="observasi_list.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">list</a>
					<hr>
					<b>5. RESUME MEDIS</b> :
					<a href="resume.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm">edit</a>
				</div>
			</div>

		</div>
		<div class="col-sm-6">
			<div class="card">
				<div class="card-header"><i class="bi bi-printer-fill" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;<b>REPORT</b>
				</div>
				<div class="card-body">
					<a href="r_rekammedik.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Rekam Medik Pasien</a>
					<a href="r_soap.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">CPPT</a>
					<a href="r_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Asuhan Keperawatan</a>
					<a href="r_asuhankebidanan.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Asuhan Kebidanan</a>
					<hr>
					<a href="r_observasi.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Detail Observasi</a>
					<a href="r_ews.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Monitoring EWS</a>
					<a href="r_vitalsign.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Dashboard Vital Sign</a>
					<hr>
					<a href="r_resume.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-secondary btn-sm">Resume Pasien</a>
					<?php 
					// dewasa : >18 th s/d 60
					// anak : 28 hari s/d 17 th
					// neonatus :0 hari s/d 28 hari
					// geriatri : >60 th
					// bersalin : ikut dewasa

					if($hari_u < 28 and $tahun_u <= 0){ //neonatus
						$f_anamnesis = "<a href='r_formneonatus.php?id=$id|$user' class='btn btn-secondary btn-sm'>Anamnesis Awal Neonatus</a>";
					}else if($tahun_u >0 and $tahun_u <=17){ //anak
						$f_anamnesis = "<a href='r_formanak.php?id=$id|$user' class='btn btn-secondary btn-sm'>Anamnesis Awal Anak</a>";
					}else if($tahun_u >17 and $tahun_u <=60){ //dewasa
						$f_anamnesis = 
						"<a href='r_formdewasa.php?id=$id|$user' class='btn btn-secondary btn-sm'>Anamnesis Awal Dewasa</a>
						/
						<a href='r_formbersalin.php?id=$id|$user' class='btn btn-secondary btn-sm'>Anamnesis Awal Bersalin</a>";
					}else if($tahun_u >60){//geriatri
						$f_anamnesis = "<a href='r_formgeriatri.php?id=$id|$user' class='btn btn-secondary btn-sm'>Anamnesis Awal Geriatri</a>";
					}

					echo $f_anamnesis;
					?>			
					<hr>		
					<a href="i_upload.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-warning btn-sm">Upload pdf</a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">

		</div>
	</div>


	<div class="row">
		<div class="col-sm-12">

		</div>
		<div class="col-sm-12">
		</div>
		<div class="col-sm-12">

		</div>
		<div class="col-sm-12">
		</div>

	</div>


</div>