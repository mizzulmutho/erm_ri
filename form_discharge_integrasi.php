<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = strtoupper($row[1]); 
$edit  = $row[2];
$id_discharge  = $row[3];

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);

if ($KET1 == 'RSPG'){
	$nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
	$alamat = "Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik";
};
if ($KET1 == 'GRAHU'){
	$nmrs = "RUMAH SAKIT GRHA HUSADA";
	$alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
};
if ($KET1 == 'DRIYO'){
	$nmrs = "RUMAH SAKIT DRIYOREJO";
	$alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
};

$q2       = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan,NOKTP,NOBPJS, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);                

$data2    = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);                      
$kodedept = $data2[kodedept];

$nama     = $data2[nama];
$kelamin  = $data2[kelamin];
$nik = trim($data2[nik]);
$alamatpasien  = $data2[alamatpasien];
$kota     = $data2[kota];
$kodekel  = $data2[kodekel];
$telp     = $data2[tlp];
$tmptlahir     = $data2[tmptlahir];
$tgllahir = $data2[tgllahir];
$jenispekerjaan     = $data2[jenispekerjaan];
$jabatan  = $data2[jabatan];
$umur =  $data2[UMUR];
$noktp =  $data2[NOKTP];
$nobpjs =  $data2[NOBPJS];

//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar,CONVERT(VARCHAR, tglmasuk, 20) as tglmasuk2
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];
$tglmasuk2 = $data3[tglmasuk2];


$qu="SELECT noreg,ppa_perawat,paraf_perawat,ppa_pasien,paraf_pasien,ppa_dpjp,paraf_dpjp
FROM ERM_RI_DISCHARGE where noreg='$noreg'";
$h1u  = sqlsrv_query($conn, $qu);        
$row  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$regcek = $row['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_DISCHARGE(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	// Misal hasil SELECT
	$nama_perawat = $row['ppa_perawat'] ?? '';
	$paraf_perawat = $row['paraf_perawat'] ?? '';

	$nama_pasien = $row['ppa_pasien'] ?? '';
	$paraf_pasien = $row['paraf_pasien'] ?? '';

	$nama_dpjp = $row['ppa_dpjp'] ?? '';
	$paraf_dpjp = $row['paraf_dpjp'] ?? '';
}



if(empty($nama_perawat)){
	$qu="SELECT NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$user'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$nama_perawat = trim($d1u['NamaUser']);
}

if(empty($nama_pasien)){
	$nama_pasien = $nama;
}

$qu = "SELECT dpjp FROM V_ERM_RI_DPJP_ASESMEN WHERE noreg='$noreg' AND dpjp IS NOT NULL";
$h1u  = sqlsrv_query($conn, $qu);
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC);
$dpjp_full = trim($d1u['dpjp'] ?? '');

// Pisah pakai ' - '
$parts = explode(' - ', $dpjp_full);

// Cek: jika ada minimal 2 bagian
if (count($parts) >= 2) {
    $dpjp = trim($parts[1]);  // Elemen ke-1 = nama dokter
} else {
    $dpjp = $dpjp_full;  // fallback kalau format tidak sesuai
}


if(empty($nama_dpjp)){
	$nama_dpjp = $dpjp;
}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>FORM DISCHARGE</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

	<!-- Jqueri autocomplete untuk procedure !!! -->
	<link rel="stylesheet" href="jquery-ui.css">
	<script src="jquery-1.10.2.js"></script>
	<script src="jquery-ui.js"></script>

	<script>
		$(function() {
			$("#icd101").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_icd10.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.nodaftar + ' - ' + item.keterangan
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script> 
	
	<style>
		@media print {
			.hide-on-print {
				display: none !important;
			}
		}
	</style>

</head> 

<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
					&nbsp;&nbsp;
					<br>
					<br>
					<div class="row">
					</div>

					<div class="row">
						<div class="col-6">
							<h5><b><?php echo $nmrs; ?></b></h5>
							<?php echo $alamat; ?>
						</div>
						<div class="col-6">
							<?php echo 'NIK : '.$noktp.'<br>'; ?>					
							<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
							<?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-12 text-center">
							<b>LEMBAR DISCHARGE PLANNING TERINTEGRASI</b><br>
						</div>
					</div>
					<hr> 
					


					<?php 

					$qe="
					SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume
					FROM ERM_RI_RESUME
					where noreg='$noreg'";
					$he  = sqlsrv_query($conn, $qe);        
					$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
					$tglresume = $de['tglresume'];
					$resume20= $de['resume20'];
					$resume21= $de['resume21'];
					$resume22= $de['resume22'];
					$resume2= $de['resume2'];
					$resume4= $de['resume4'];
					$resume8= $de['resume8'];

					?>

					<div class="row">
						<div class="col-6">
							Tgl Masuk rumah sakit : 						
							<input class="form-control-sm" name="tglmasuk_rs" value="<?php echo $resume2;?>" id="" type="text" onfocus="nextfield ='';" placeholder="">
						</div>
						<div class="col-6">
							Tgl Rencana pulang : 						
							<input class="form-control-sm" name="tglpulang_rs" value="<?php echo $resume4;?>" id="" type="text" onfocus="nextfield ='';" placeholder="">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-12">
							Alasan Masuk RS : 						
							<input class="form-control" name="alasanmasuk_rs" value="<?php echo $resume8;?>" id="" type="text" onfocus="nextfield ='';" placeholder="">					
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-4">
							&bull; Diagnosis Awal / Masuk
						</div>
						<div class="col-8">
							<input class="" name="resume20" value="<?php echo $resume20;?>" id="icd101" type="text" size='80' onfocus="nextfield ='';" placeholder="ICD 10, ICD 9 CM">
						</div>

						<div class="col-4">
							&bull; Diagnosis Akhir (Primer)
						</div>
						<div class="col-8">
							<input class="" name="resume21" value="<?php echo $resume21;?>" id="icd102" type="text" size='80' onfocus="nextfield ='';" placeholder="ICD 10, ICD 9 CM">
						</div>
						<div class="col-4">
							&bull; Diagnosis Akhir (Sekunder)
						</div>
						<div class="col-8">
							<input class="" name="resume22" value="<?php echo $resume22;?>" id="icd103" type="text" size='80' onfocus="nextfield ='periode';" placeholder="ICD 10, ICD 9 CM">
						</div>
					</div>
					<br><br>
					<div class="row">
						<table border='1'>
							<tbody>
								<tr style="background-color: #e0e0e0;">
									<td rowspan="2">
										Fase<br>
									</td>
									<td rowspan="2">
										No<br>
									</td>
									<td rowspan="2" >
										Kegiatan<br>
									</td>
									<td colspan="3" >
										Dilakukan<br>
									</td>
									<td colspan="2">
										PPA<br>
									</td>
									<td rowspan="2" class="hide-on-print">
										Update<br>
									</td>
								</tr>
								<tr style="background-color: #e0e0e0;">
									<td>Tanggal<br></td>
									<td>Jam<br></td>
									<td>
										Evaluasi<br>
									</td>
									<td >
										Nama<br>
									</td>
									<td >
										Paraf<br>
									</td>
								</tr>
								<tr>
									<td rowspan="10">
										Tahap I<br>
										Pasien Masuk
									</td>
									<td>
										1<br>
									</td>
									<td>
										Pengkajian fisik<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pengkajian fisik'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|1';?>'>Update</a></td>
								</tr>

								<tr>
									<td>
										2<br>
									</td>
									<td>
										Pengkajian status psikologis dan ekonomi<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pengkajian status psikologis dan ekonomi'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|2';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td>
										3<br>
									</td>
									<td colspan="7">
										Pengkajian kebutuhan pendidikan kesehatan<br>
									</td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										a.Proses penyakit
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Proses penyakit'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|3';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										b.Pencegahan faktor resiko
									</td>
									
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pencegahan faktor resiko'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|4';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										c.Obat obatan									
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Obat obatan'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|5';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										d.Prosedur, cara perawatan
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Prosedur, cara perawatan'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|6';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										e.Lingkungan yang disiapkan
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Lingkungan yang disiapkan'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|7';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										f.Rencana tindak lanjut
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Rencana tindak lanjut'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|8';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										g.Pendamping di rumah
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pendamping di rumah'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|9';?>'>Update</a></td>
								</tr>
								<tr>
									<td rowspan="9">
										Tahap II<br>
										Fase Diagnostik<br>
									</td>
									<td>
										4<br>
									</td>
									<td colspan="7">
										Pendidikan kesehatan tentang proses penyakit<br>
									</td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										a.Pengertian, penyebab, tanda dan gejala
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pengertian, penyebab, tanda dan gejala'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|10';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										b.Faktor resiko
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Faktor resiko'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|11';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td><br></td>
									<td>
										c.Komplikasi
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Komplikasi'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|12';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td>
										5<br>
									</td>
									<td>
										Pendidikan tentang obat obatan<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pendidikan tentang obat obatan'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|13';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td>
										6<br>
									</td>
									<td>
										Pendidikan tentang penatalaksanaan<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pendidikan tentang penatalaksanaan'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|14';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td>
										7<br>
									</td>
									<td>
										Pendidikan tentang pemeriksaan diagnostik<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pendidikan tentang pemeriksaan diagnostik'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|15';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td>
										8<br>
									</td>
									<td>
										Pendidikan tentang rehabilitasi<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pendidikan tentang rehabilitasi'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|16';?>'>Update</a></td>
								</tr>
								<tr>
									
									<td>
										9<br>
									</td>
									<td>
										Pendidikan tentang perawatan dalam hygiene personal, perubahan posisi, pencegahan jatuh, pencegahan aspirasi, latihan ROM, dan teknik manajemen nyeri<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pendidikan tentang perawatan'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|17';?>'>Update</a></td>
								</tr>
								<tr>
									<td rowspan="3">
										Tahap III<br>
										Fase Stabilisasi<br>
									</td>
									<td>
										10<br>
									</td>
									<td>
										Pendidikan tentang modifikasi gaya hidup<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Pendidikan tentang modifikasi gaya hidup'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|18';?>'>Update</a></td>
								</tr>
								<tr>
									<td>
										11<br>
									</td>
									<td>
										Diskusi tentang modifikasi lingkungan pasien setelah pulang dari rumah sakit<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Diskusi tentang modifikasi lingkungan pasien setelah pulang dari rumah sakit'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|19';?>'>Update</a></td>
								</tr>
								<tr>
									<td>
										12<br>
									</td>
									<td>
										Diskusi tentang rencana perawatan lanjutan pasien<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Diskusi tentang rencana perawatan lanjutan pasien'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|20';?>'>Update</a></td>
								</tr>
								<tr>
									<td>
										Tahap IV<br>
										Fase Discharge Planning<br>
									</td>
									<td>
										13<br>
									</td>
									<td>
										Diskusi tentang pengawasan pada pasien setelah pulang tentang obat, diet, aktivitas, dan peningkatan status fungsional<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Diskusi tentang pengawasan pada pasien setelah pulang'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|21';?>'>Update</a></td>
								</tr>
								<tr>
									<td><br></td>
									<td>
										14<br>
									</td>
									<td>
										Diskusi tentang support sistem keluarga, finansial, dan alat transportasi yang akan digunakan pasien<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Diskusi tentang support sistem keluarga, finansial, dan alat transportasi yang akan digunakan pasien'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $evaluasi;?></td>
									<td><?php echo $ppa;?></td>
									<td> 
										<?php if (!empty($paraf)) { ?>
											<img src="<?php echo $paraf; ?>" alt="Tanda Tangan" style="max-height: 50px;">
										<?php } else { ?>
											-
										<?php } ?>
									</td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|22';?>'>Update</a></td>
								</tr>
								<tr>
									<td colspan="9">
										<hr>
									</td>
								</tr>
								<tr style="background-color: #e0e0e0;">
									<td rowspan="2">
										No<br>
									</td>
									<td colspan="2"  rowspan="2">
										Catatan pulang<br>
									</td>
									<td colspan="2" >
										Dilakukan<br>
									</td>
									<td colspan="2">
										Tidak dilakukan<br>
									</td>
									<td colspan="2" rowspan="2">
										Keterangan<br>
									</td>
								</tr>
								<tr style="background-color: #e0e0e0;">
									<td >
										Tgl<br>
									</td>
									<td >
										Jam<br>
									</td>
									<td colspan="2">
										Alasan<br>
									</td>
								</tr>
								<tr>
									<td>
										1<br>
									</td>
									<td colspan="2">
										Resep / obat obatan pulang<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf,alasan,keterangan
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Resep / obat obatan pulang'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									$alasan = trim($d1u['alasan']);
									$keterangan = trim($d1u['keterangan']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $alasan;?></td>
									<td><?php echo $ppa;?></td>
									<td><?php echo $keterangan;?></td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|23';?>'>Update</a></td>
								</tr>
								<tr>
									<td>
										2<br>
									</td>
									<td colspan="2">
										Surat control<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf,alasan,keterangan
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Surat control'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									$alasan = trim($d1u['alasan']);
									$keterangan = trim($d1u['keterangan']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $alasan;?></td>
									<td><?php echo $ppa;?></td>
									<td><?php echo $keterangan;?></td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|24';?>'>Update</a></td>
								</tr>
								<tr>
									<td>
										3<br>
									</td>
									<td colspan="2">
										Rujukan rehabilitasi<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf,alasan,keterangan
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Rujukan rehabilitasi'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									$alasan = trim($d1u['alasan']);
									$keterangan = trim($d1u['keterangan']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $alasan;?></td>
									<td><?php echo $ppa;?></td>
									<td><?php echo $keterangan;?></td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|25';?>'>Update</a></td>
								</tr>
								<tr>
									<td>
										4<br>
									</td>
									<td colspan="2">
										Leaflet / informasi kesehatan<br>
									</td>
									<?php
									$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf,alasan,keterangan
									FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='Leaflet / informasi kesehatan'";
									$h1u  = sqlsrv_query($conn, $qu);        
									$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
									//$tgl = trim($d1u['tgl']);
									if (!empty($d1u['tgl'])) {
										list($y, $m, $d) = explode('-', trim($d1u['tgl']));
										$tgl = "$d/$m/$y";
									}else{
										$tgl='';
									}
									$jam = trim($d1u['jam']);
									$evaluasi = trim($d1u['evaluasi']);
									$ppa = trim($d1u['ppa']);
									$paraf = trim($d1u['paraf']);
									$alasan = trim($d1u['alasan']);
									$keterangan = trim($d1u['keterangan']);
									?>
									<td><?php echo $tgl;?></td>
									<td><?php echo $jam;?></td>
									<td><?php echo $alasan;?></td>
									<td><?php echo $ppa;?></td>
									<td><?php echo $keterangan;?></td>
									<td class="hide-on-print"><a href='u_discharge.php?id=<?php echo $id.'|'.$user.'|26';?>'>Update</a></td>
								</tr>


							</tbody>
						</table>

					</div>

					<div class="row">

						<div class="row text-center">
							<!-- Perawat -->
							<div class="col-md-4 mb-4">
								<h6>Perawat</h6>
								<canvas id="ttd-perawat" class="border rounded w-100" style="touch-action: none; height: 150px;"></canvas>
								<button type="button" class="btn btn-sm btn-secondary mt-2 clear-btn hide-on-print" data-canvas="ttd-perawat" data-input="paraf_perawat">Hapus Tanda Tangan</button>
								<input type="text" name="nama_perawat" class="form-control mt-2" placeholder="Nama Perawat"  value="<?php echo htmlspecialchars($nama_perawat); ?>">
							</div>

							<!-- Pasien / Keluarga -->
							<div class="col-md-4 mb-4">
								<h6>Pasien / Keluarga</h6>
								<canvas id="ttd-pasien" class="border rounded w-100" style="touch-action: none; height: 150px;"></canvas>
								<button type="button" class="btn btn-sm btn-secondary mt-2 clear-btn hide-on-print" data-canvas="ttd-pasien" data-input="paraf_pasien">Hapus Tanda Tangan</button>
								<input type="text" name="nama_pasien" class="form-control mt-2" placeholder="Nama Pasien/Keluarga"  value="<?php echo htmlspecialchars($nama_pasien); ?>">
							</div>

							<!-- DPJP -->
							<div class="col-md-4 mb-4">
								<h6>DPJP</h6>

<!-- 								<canvas id="ttd-dpjp" class="border rounded w-100" style="touch-action: none; height: 150px;"></canvas>
								<button type="button" class="btn btn-sm btn-secondary mt-2 clear-btn hide-on-print" data-canvas="ttd-dpjp" data-input="paraf_dpjp">Hapus Tanda Tangan</button>
							-->

							<?php
							$pernyataan = 'Lembar Rujuk Pasien ini telah ditandatangani oleh DPJP: '.$nama_dpjp.' pada tanggal: '.$tglinput;
							ob_start();
							QRcode::png($pernyataan, null, 'L', 4, 2);
							$imageString = base64_encode(ob_get_contents());
							ob_end_clean();

							echo '<img src="data:image/png;base64,' . $imageString . '" />';	
							?>

							<input type="text" name="nama_dpjp" class="form-control mt-2" placeholder="Nama DPJP"  value="<?php echo htmlspecialchars($nama_dpjp); ?>">

						</div>
					</div>

					<!-- Hidden input untuk menyimpan data tanda tangan -->
					<input type="hidden" name="paraf_perawat" id="paraf_perawat" value="<?php echo htmlspecialchars($paraf_perawat); ?>">
					<input type="hidden" name="paraf_pasien" id="paraf_pasien" value="<?php echo htmlspecialchars($paraf_pasien); ?>">
					<input type="hidden" name="paraf_dpjp" id="paraf_dpjp" value="<?php echo htmlspecialchars($paraf_dpjp); ?>">


					<script>
						function loadSignature(canvasId, hiddenInputId) {
							const canvas = document.getElementById(canvasId);
							const ctx = canvas.getContext('2d');
							const dataURL = document.getElementById(hiddenInputId).value;

							if (dataURL) {
								const img = new Image();
								img.onload = function() {
									ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
								};
								img.src = dataURL;
							}
						}

						loadSignature('ttd-perawat', 'paraf_perawat');
						loadSignature('ttd-pasien', 'paraf_pasien');
						loadSignature('ttd-dpjp', 'paraf_dpjp');
					</script>


				</div>

				<br><br>

				<div class="row">
					<div class="col-12 text-center hide-on-print">
						<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
					</div>
				</div>

				<br><br><br>
			</font>
		</form>
	</font>


	<script>
		function initSignaturePad(canvasId, hiddenInputId) {
			const canvas = document.getElementById(canvasId);
			const ctx = canvas.getContext('2d');

			function resizeCanvas() {
				const rect = canvas.getBoundingClientRect();
				canvas.width = rect.width;
				canvas.height = 150;
			}

			resizeCanvas();
			window.addEventListener('resize', resizeCanvas);

			let drawing = false;

			function getPosition(e) {
				const rect = canvas.getBoundingClientRect();
				return {
					x: e.clientX - rect.left,
					y: e.clientY - rect.top
				};
			}

			canvas.addEventListener('mousedown', (e) => {
				drawing = true;
				const pos = getPosition(e);
				ctx.beginPath();
				ctx.moveTo(pos.x, pos.y);
			});

			canvas.addEventListener('mousemove', (e) => {
				if (drawing) {
					const pos = getPosition(e);
					ctx.lineTo(pos.x, pos.y);
					ctx.stroke();
				}
			});

			window.addEventListener('mouseup', () => {
				if (drawing) {
					drawing = false;
					document.getElementById(hiddenInputId).value = canvas.toDataURL();
				}
			});

    // Return context & hidden input for clear button use
    return { ctx, hiddenInputId, canvas };
}

  // Init semua canvas
  const perawat = initSignaturePad('ttd-perawat', 'paraf_perawat');
  const pasien = initSignaturePad('ttd-pasien', 'paraf_pasien');
  const dpjp = initSignaturePad('ttd-dpjp', 'paraf_dpjp');

  // Tombol hapus
  document.querySelectorAll('.clear-btn').forEach(btn => {
  	btn.addEventListener('click', () => {
  		const canvasId = btn.getAttribute('data-canvas');
  		const inputId = btn.getAttribute('data-input');
  		const canvas = document.getElementById(canvasId);
  		const ctx = canvas.getContext('2d');
  		ctx.clearRect(0, 0, canvas.width, canvas.height);
  		document.getElementById(inputId).value = '';
  	});
  });
</script>

</body>
</div>
</div>

<?php 


if (isset($_POST["simpan"])) {

	$nama_perawat = trim($_POST['nama_perawat'] ?? '');
	$nama_pasien  = trim($_POST['nama_pasien'] ?? '');
	$nama_dpjp    = trim($_POST['nama_dpjp'] ?? '');

	$paraf_perawat = trim($_POST['paraf_perawat'] ?? '');
	$paraf_pasien  = trim($_POST['paraf_pasien'] ?? '');
	$paraf_dpjp    = trim($_POST['paraf_dpjp'] ?? '');


	$sql = "UPDATE ERM_RI_DISCHARGE
	SET 
	ppa_perawat = ?,
	paraf_perawat = ?,
	ppa_pasien = ?,
	paraf_pasien = ?,
	ppa_dpjp = ?,
	paraf_dpjp = ?
	WHERE noreg = ?";

	$params = [
		$nama_perawat,
		$paraf_perawat,
		$nama_pasien,
		$paraf_pasien,
		$nama_dpjp,
		$paraf_dpjp,
		$noreg
	];

	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt === false) {
		die(print_r(sqlsrv_errors(), true));
	} else {
		echo "<script>
		alert('Data berhasil disimpan!');
		</script>";
	}



}


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>
