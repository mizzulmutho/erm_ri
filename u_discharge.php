<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = strtoupper($row[1]); 
$id_discharge  = $row[2];

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

if($id_discharge=='1'){
	$kegiatan='Pengkajian fisik';
}

if($id_discharge=='2'){
	$kegiatan='Pengkajian status psikologis dan ekonomi';
}

if($id_discharge=='3'){
	$kegiatan='Proses penyakit';
}

if($id_discharge=='4'){
	$kegiatan='Pencegahan faktor resiko';
}

if($id_discharge=='5'){
	$kegiatan='Obat obatan';
}
if($id_discharge=='6'){
	$kegiatan='Prosedur, cara perawatan';
}
if($id_discharge=='7'){
	$kegiatan='Lingkungan yang disiapkan';
}
if($id_discharge=='8'){
	$kegiatan='Rencana tindak lanjut';
}
if($id_discharge=='9'){
	$kegiatan='Pendamping di rumah';
}

if($id_discharge=='10'){
	$kegiatan='Pengertian, penyebab, tanda dan gejala';
}
if($id_discharge=='11'){
	$kegiatan='Faktor resiko';
}
if($id_discharge=='12'){
	$kegiatan='Komplikasi';
}
if($id_discharge=='13'){
	$kegiatan='Pendidikan tentang obat obatan';
}
if($id_discharge=='14'){
	$kegiatan='Pendidikan tentang penatalaksanaan';
}
if($id_discharge=='15'){
	$kegiatan='Pendidikan tentang pemeriksaan diagnostik';
}
if($id_discharge=='16'){
	$kegiatan='Pendidikan tentang rehabilitasi';
}
if($id_discharge=='17'){
	$kegiatan='Pendidikan tentang perawatan';
}


if($id_discharge=='18'){
	$kegiatan='Pendidikan tentang modifikasi gaya hidup';
}
if($id_discharge=='19'){
	$kegiatan='Diskusi tentang modifikasi lingkungan pasien setelah pulang dari rumah sakit';
}
if($id_discharge=='20'){
	$kegiatan='Diskusi tentang rencana perawatan lanjutan pasien';
}


if($id_discharge=='21'){
	$kegiatan='Diskusi tentang pengawasan pada pasien setelah pulang';
}
if($id_discharge=='22'){
	$kegiatan='Diskusi tentang support sistem keluarga, finansial, dan alat transportasi yang akan digunakan pasien';
}

if($id_discharge=='23'){
	$kegiatan='Resep / obat obatan pulang';
}
if($id_discharge=='24'){
	$kegiatan='Surat control';
}
if($id_discharge=='25'){
	$kegiatan='Rujukan rehabilitasi';
}
if($id_discharge=='26'){
	$kegiatan='Leaflet / informasi kesehatan';
}

$qu="SELECT noreg,CONVERT(VARCHAR, tgl, 23) as tgl,jam,evaluasi,ppa,paraf,alasan,keterangan
FROM ERM_RI_DISCHARGE_DETAIL where noreg='$noreg' and kegiatan='$kegiatan'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$cnoreg = trim($d1u['noreg']);

if($cnoreg){
	$tgl = trim($d1u['tgl']);
	$jam = trim($d1u['jam']);
	$evaluasi = trim($d1u['evaluasi']);
	$ppa_nama = trim($d1u['ppa']);
	$ppa_paraf = trim($d1u['paraf']);
	$alasan = trim($d1u['alasan']);
	$keterangan = trim($d1u['keterangan']);
}else{
	date_default_timezone_set('Asia/Jakarta'); 
	$tgl = date('Y-m-d'); 
	$jam = date('H:i');   
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
			$("#obat").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.NAMABARANG + ' - ' + item.NAMASATUAN
                            		}
                            	}));
                            //if a single result is returned
                        }           
                    });
                }
            });
		});
	</script>  


	
</head> 

<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='form_discharge_integrasi.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
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
							<b>FORM DISCHARGE DETAIL</b><br>
						</div>
					</div>
					<!-- Form yang dirapikan dengan Bootstrap -->
					<div class="container my-4">
						<div class="card shadow-sm">
							<div class="card-body">
								<!-- Bagian Kegiatan -->
								<div class="mb-4">
									<label for="kegiatan" class="form-label">Kegiatan:</label>
									<input type="text" 
									id="kegiatan"
									name="kegiatan" 
									value="<?php echo $kegiatan; ?>" 
									class="form-control" 
									placeholder="Isi kegiatan">
								</div>

								<hr>

								<!-- Pilihan Dilakukan / Tidak Dilakukan -->
								<div class="mb-3">
									<label class="form-label fw-bold">Status Kegiatan:</label><br>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="status_kegiatan" id="status_dilakukan" value="dilakukan">
										<label class="form-check-label" for="status_dilakukan">Dilakukan</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="radio" name="status_kegiatan" id="status_tidak" value="tidak">
										<label class="form-check-label" for="status_tidak">Tidak Dilakukan</label>
									</div>
								</div>


								<div id="form_dilakukan">
									<h6 class="mb-3 fw-bold">Dilakukan</h6>
									<div class="row g-3">
										<div class="col-md-6">
											<label for="tgl" class="form-label">Tanggal:</label>
											<input type="date" id="tgl" name="tgl" value="<?php echo $tgl; ?>" class="form-control">
										</div>
										<div class="col-md-6">
											<label for="jam" class="form-label">Jam:</label>
											<input type="time" id="jam" name="jam" value="<?php echo $jam; ?>" class="form-control">
										</div>
									</div>
									<div class="mt-3">
										<label for="evaluasi" class="form-label">Evaluasi:</label>
										<textarea id="evaluasi" name="evaluasi" class="form-control" rows="3" placeholder="Isi evaluasi"><?php echo $evaluasi; ?></textarea>
									</div>
								</div>

								<div id="form_tidak_dilakukan" style="display: none;">
									<h6 class="mb-3 fw-bold">Tidak Dilakukan</h6>
									<div class="mb-3">
										<label for="alasan" class="form-label">Alasan:</label>
										<input type="text" id="alasan" name="alasan" value="<?php echo $alasan; ?>" class="form-control" placeholder="Alasan tidak dilakukan">
									</div>
									<div class="mb-3">
										<label for="keterangan" class="form-label">Keterangan:</label>
										<textarea id="keterangan" name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan"><?php echo $keterangan; ?></textarea>
									</div>
								</div>

								<hr>

								<!-- Bagian PPA -->
								<h6 class="mb-3 fw-bold">PPA</h6>
								<div class="row g-3">
									<div class="col-md-6">
										<?php

										$qu="SELECT NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$user'";
										$h1u  = sqlsrv_query($conn, $qu);        
										$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
										$nmuserid = trim($d1u['NamaUser']);

										if($ppa_nama){
											$nmuserid = $nmuserid;
										}

										if($ppa_paraf){
											$ppa_paraf = $ppa_paraf;
										}


										?>
										<label for="ppa_nama" class="form-label">Nama:</label>
										<input type="text" 
										id="ppa_nama"
										name="ppa_nama" 
										class="form-control" 
										value="<?php echo $nmuserid; ?>"
										placeholder="Nama PPA">
									</div>
									<div class="col-md-6">
										<label class="form-label">Paraf (Tanda Tangan):</label>
										<canvas id="signature-pad" class="border rounded w-100" style="touch-action: none;"></canvas>
										<button type="button" class="btn btn-sm btn-secondary mt-2" id="clear-signature">Hapus Tanda Tangan</button>
										<input type="hidden" name="ppa_paraf" id="signature-data" value="<?php echo $ppa_paraf; ?>">
									</div>

									<script>
										const canvas = document.getElementById('signature-pad');
										const ctx = canvas.getContext('2d');

										function resizeCanvas() {
											const rect = canvas.getBoundingClientRect();
											canvas.width = rect.width;
											canvas.height = 200; 
										}

										resizeCanvas();
										window.addEventListener('resize', resizeCanvas);

										let drawing = false;

										function getPosition(event) {
											const rect = canvas.getBoundingClientRect();
											return {
												x: (event.clientX - rect.left),
												y: (event.clientY - rect.top)
											};
										}

										canvas.addEventListener('mousedown', e => {
											drawing = true;
											const pos = getPosition(e);
											ctx.beginPath();
											ctx.moveTo(pos.x, pos.y);
										});

										canvas.addEventListener('mousemove', e => {
											if (drawing) {
												const pos = getPosition(e);
												ctx.lineTo(pos.x, pos.y);
												ctx.stroke();
											}
										});

										window.addEventListener('mouseup', () => {
											if (drawing) {
												drawing = false;
												document.getElementById('signature-data').value = canvas.toDataURL();
											}
										});

										document.getElementById('clear-signature').addEventListener('click', () => {
											ctx.clearRect(0, 0, canvas.width, canvas.height);
											document.getElementById('signature-data').value = '';
										});

										 // Tampilkan tanda tangan yang sudah tersimpan
										 window.addEventListener('load', () => {
										 	const savedSignature = document.getElementById('signature-data').value;
										 	if (savedSignature) {
										 		const img = new Image();
										 		img.onload = () => {
										 			ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
										 		};
										 		img.src = savedSignature;
										 	}
										 });

										</script>
									</div>

									<!-- Tombol Simpan -->
									<div class="text-center mt-4">
										<button type="submit" 
										name="simpan" 
										value="simpan" 
										class="btn btn-info btn-lg px-4">
										<i class="bi bi-save-fill"></i> Simpan
									</button>
								</div>
							</div>
						</div>
					</div>


				</font>
			</form>
		</font>

		<script>
			const statusDilakukan = document.getElementById('status_dilakukan');
			const statusTidak = document.getElementById('status_tidak');
			const formDilakukan = document.getElementById('form_dilakukan');
			const formTidakDilakukan = document.getElementById('form_tidak_dilakukan');

			const tgl = document.getElementById('tgl');
			const jam = document.getElementById('jam');

			statusDilakukan.addEventListener('change', () => {
				if (statusDilakukan.checked) {
					formDilakukan.style.display = 'block';
					formTidakDilakukan.style.display = 'none';
				}
			});

			statusTidak.addEventListener('change', () => {
				if (statusTidak.checked) {
					formDilakukan.style.display = 'none';
					formTidakDilakukan.style.display = 'block';

					tgl.value = '';
					jam.value = '';
				}
			});
		</script>

	</body>
</div>
</div>

<?php 


if (isset($_POST["simpan"])) {

//	noreg, userid, tglentry, tgl, jam, evaluasi, ppa, paraf

	$tgl	= $_POST["tgl"];
	$jam	= $_POST["jam"];
	$evaluasi	= $_POST["evaluasi"];
	$ppa	= $_POST["ppa_nama"];
	$paraf	= $_POST["ppa_paraf"];
	$kegiatan	= trim($_POST["kegiatan"]);
	$alasan	= trim($_POST["alasan"]);
	$keterangan	= trim($_POST["keterangan"]);

	$status_kegiatan = $_POST["status_kegiatan"];

	if($status_kegiatan=='tidak'){
		$tgl = "NULL";		
	}else{
		$tgl = "'$tgl'";
	}

	if($cnoreg){
		echo $q  = "update ERM_RI_DISCHARGE_DETAIL set tgl=$tgl,jam='$jam',evaluasi='$evaluasi',ppa='$ppa',paraf='$paraf',alasan='$alasan',keterangan='$keterangan'
		where noreg='$noreg' and kegiatan='$kegiatan'
		";

	}else{
		$q  = "insert into ERM_RI_DISCHARGE_DETAIL(noreg,userid,tglentry,tgl,jam,evaluasi,ppa,paraf,kegiatan,alasan,keterangan) 
		values ('$noreg','$user','$tglinput',$tgl,'$jam','$evaluasi','$ppa','$paraf','$kegiatan','$alasan','$keterangan')
		";
	}
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";



}

?>
