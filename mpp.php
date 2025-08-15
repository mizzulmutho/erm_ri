<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgli		= gmdate("Y-m-d  H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 


$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

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
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];

$data = [
	'tgl_mrs' => '',
	'diagnosis' => '',
	'dpjp' => '',
	'indikator_1' => '',
	'indikator_2' => '',
	'indikator_3' => '',
	'indikator_4' => '',
	'indikator_5' => '',
	'indikator_6' => '',
	'indikator_7' => '',
	'tanggal_jam_lanjut' => '',
	'ringkasan_asesmen' => '',
	'identifikasi_masalah' => '',
	'rencana_tindak_lanjut' => '',
	'cppt' => '',
	'signature_base64' => '',
	'userid' => ''
];

if (!empty($noreg)) {
	$query = sqlsrv_query($conn, "SELECT * FROM ERM_RI_MPP WHERE noreg = ?", [$noreg]);
	if ($query === false) {
		die(print_r(sqlsrv_errors(), true));
	} else {
		// echo "Query berhasil dijalankan.<br>";
	}

	if (sqlsrv_has_rows($query)) {
		// echo "Data ditemukan untuk noreg $noreg.<br>";
	} else {
		// echo "Tidak ada data untuk noreg $noreg.<br>";
	}

	if ($query && sqlsrv_has_rows($query)) {
		$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
		foreach ($data as $key => $val) {
			if (isset($row[$key])) {
				if ($row[$key] instanceof DateTime) {
					$data[$key] = $row[$key]->format('Y-m-d');
				} else {
					$data[$key] = $row[$key];
				}
			} else {
				$data[$key] = '';
			}
		}

		// Inisialisasi nilai indikator
		$indikator = [
			1 => $row['indikator_1'] ?? '',
			2 => $row['indikator_2'] ?? '',
			3 => $row['indikator_3'] ?? '',
			4 => $row['indikator_4'] ?? '',
			5 => $row['indikator_5'] ?? '',
			6 => $row['indikator_6'] ?? '',
			7 => $row['indikator_7'] ?? ''
		];

	}
}

$userinput = $data['userid'];
$ringkasan_asesmen = $data['ringkasan_asesmen'];
$identifikasi_masalah = $data['identifikasi_masalah'];
$rencana_tindak_lanjut = $data['rencana_tindak_lanjut'];
$cppt = $data['cppt'];

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>mpp</title>  
	<link rel="icon" href="favicon.ico">  

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Bootstrap Icons -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

	<!-- Bootstrap Bundle JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	<!-- CKEditor atau pustaka lain jika diperlukan -->
	<script src="ckeditor/ckeditor.js"></script>

	<!-- Jqueri autocomplete untuk procedure !!! -->
	<link rel="stylesheet" href="jquery-ui.css">
	<script src="jquery-1.10.2.js"></script>
	<script src="jquery-ui.js"></script>

	<script language="JavaScript" type="text/javascript">
		nextfield = "box1";
		netscape = "";
		ver = navigator.appVersion; len = ver.length;
		for(iln = 0; iln < len; iln++) if (ver.charAt(iln) == "(") break;
			netscape = (ver.charAt(iln+1).toUpperCase() != "C");

		function keyDown(DnEvents) {
			k = (netscape) ? DnEvents.which : window.event.keyCode;
			if (k == 13) {
				if (nextfield == 'done') return true;
				else {
					eval('document.myForm.' + nextfield + '.focus()');
					return false;
				}
			}
		}
		document.onkeydown = keyDown;
		if (netscape) document.captureEvents(Event.KEYDOWN|Event.KEYUP);
	</script>


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
                            			value: item.KODEBARANG + ' - ' + item.NAMABARANG + ' - ' + item.NAMASATUAN
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

		<body onload="document.myForm.nama_obat.focus();">

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
						<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
					</div>
				</div>
				<hr>

				<div class="row">
					<div class="col-12 text-center">
						<b>SKRINING MANAJER PELAYANAN PASIEN (MPP)</b><br>
					</div>
				</div>
				<hr>

				<div class="row mb-3">
					<div class="col-md-4">
						<?php

						// $qe="
						// SELECT resume2 as tgl_mrs, resume4 as tgl_krs FROM ERM_RI_RESUME where noreg='$noreg'";
						// $he  = sqlsrv_query($conn, $qe);        
						// $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
						// $tgl_krs = $de['tgl_mrs'];
						// if ($tgl_mrs){ 
						// 	echo $tgl_krs; 
						// }else{
						// 	echo 'Tgl MRS diresume belum diisi!';
						// }

						$dt = '';
						if (!empty($data['tanggal_jam_lanjut'])) {
							$dt = date('Y-m-d\TH:i', strtotime($data['tanggal_jam_lanjut']));
						}
						?>

						<label for="tgl_mrs" class="form-label"><b>Tgl MRS</b></label>
						<br>
						<input type="date" name="tgl_mrs" value="<?= $data['tgl_mrs'] ? date('Y-m-d', strtotime($data['tgl_mrs'])) : '' ?>" id="tgl_mrs" class="form-control">

					</div>
					<div class="col-md-8">
						<label for="diagnosis" class="form-label"><b>Diagnosis</b></label>
						<br>
						<!-- <input type="text" name="diagnosis" id="diagnosis" class="form-control" value="<?= htmlspecialchars($data['diagnosis'] ?? '') ?>"> -->
						<?php 
						$qe="
						SELECT resume20,resume21,resume22
						FROM ERM_RI_RESUME
						where noreg='$noreg'";
						$he  = sqlsrv_query($conn, $qe);        
						$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
						$tglresume = $de['tglresume'];

						$resume20= $de['resume20'];
						$resume21= $de['resume21'];
						$resume22= $de['resume22'];

						if(!empty($resume20)){
							$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20;		
						}
						if(!empty($resume21)){
							$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20.'<br>Diagnosis Akhir (Primer) : '.$resume21;		
						}
						if(!empty($resume22)){
							$diagnosa = 'Diagnosis Awal / Masuk : '.$resume20.'<br>Diagnosis Akhir (Primer) : '.$resume21.'<br>Diagnosis Akhir (Sekunder) : '.$resume22;		
						}
						echo $diagnosa;
						?>
					</div>
				</div>

				<div class="mb-3">
					<label for="dpjp" class="form-label"><b>DPJP</b></label>
					<br>
					<!-- <input type="text" name="dpjp" class="form-control"  value="<?= htmlspecialchars($data['dpjp'] ?? '') ?>"> -->
					<?php 
					$q="
					SELECT        0 as id,SUBSTRING(dpjp, 0, 4) AS kodedokter, noreg, 'DPJP UTAMA' AS keterangan
					FROM            dbo.V_ERM_RI_DPJP_ASESMEN
					WHERE        (noreg = '$noreg')
					UNION
					SELECT        id,kode_dokter as kodedokter, SUBSTRING(noreg, 0, 13) AS noreg, keterangan
					FROM            ERM_RI_RABER
					WHERE        (noreg = '$noreg')
					";
					$hasil  = sqlsrv_query($conn, $q);  
					$no=1;
					while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

						$q2		= "select nama from afarm_dokter where kodedokter like '%$data[kodedokter]%'";
						$hasil2  = sqlsrv_query($conn, $q2);			  					
						$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
						$namadokter	= $data2[nama];


						if($data[keterangan]=='DPJP UTAMA'){
							echo "
							<tr>
							<td>$no.</td>
							<td>$data[kodedokter] - $namadokter</td>
							<td>$data[keterangan]</td>
							</tr>
							";
						}else{
							echo "
							<tr>
							<td>$no.</td>
							<td>$data[kodedokter] - $namadokter</td>
							<td>$data[keterangan]</td>
							</tr>
							";
						}
						$no += 1;

					}


					?>
				</div>

				<?php
				$indikator_list = [
					"Pasien dengan resiko tinggi/kronis/terminal",
					"Pasien dgn potensi komplain tinggi (Pasien istimewa)",
					"Pasien dgn kemungkinan pembiayaan tinggi dan komplek/ALOS tinggi",
					"Pasien dgn riwayat gangguan mental, upaya bunuh diri, perilaku kekerasan dll",
					"Pasien sering masuk IGD dan readmisi Rumah Sakit",
					"Pasien dgn status fungsional rendah, resiko tinggi jatuh, kebutuhan bantuan ADL tinggi",
					"Pasien dengan Kasus yang penting sehingga membutuhkan rencana pemulangan khusus"
				];

				echo '<h5 class="mt-4"><strong>A. Skrining Pasien</strong></h5>
				<div class="table-responsive">
				<table class="table table-bordered">
				<thead class="table-light">
				<tr>
				<th>No</th>
				<th>Indikator</th>
				<th class="text-center">Ya</th>
				<th class="text-center">Tidak</th>
				</tr>
				</thead>
				<tbody>';

				for ($i = 0; $i < count($indikator_list); $i++) {
					$no = $i + 1;
					$val = $indikator_list[$i];

					$val_indikator = $indikator[$no] ?? ''; 

					echo "<tr>
					<td>$no</td>
					<td>$val</td>
					<td class='text-center'><input class='form-check-input' type='radio' name='indikator_$no' value='ya' " . ($val_indikator == 'ya' ? 'checked' : '') . "></td>
					<td class='text-center'><input class='form-check-input' type='radio' name='indikator_$no' value='tidak' " . ($val_indikator == 'tidak' ? 'checked' : '') . "></td>
					</tr>";
				}

				echo '</tbody></table></div>';
				?>


				<div class="mb-3">
					<label for="tanggal_jam_lanjut" class="form-label">Tanggal/Jam</label>					
					<input type="datetime-local" name="tanggal_jam_lanjut" id="tanggal_jam_lanjut" value="<?= $dt ?>" class="form-control">

				</div>

				<label class="form-label">Tanda Tangan Digital</label><br>

				<?php
				

				include "phpqrcode/qrlib.php";

				if($userinput){
					$user = $userinput;
				}else{
					$user = $user;
				}

				$qr_text = "NIP Petugas: " . $user;

				$tempDir = "temp/";
				if (!file_exists($tempDir)) {
					mkdir($tempDir);
				}
				$filename = $tempDir . 'qr_petugas_' . md5($qr_text) . '.png';
				QRcode::png($qr_text, $filename, QR_ECLEVEL_L, 4);

				$userinput = trim($user);
				$qu="SELECT NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$user'";
				$h1u  = sqlsrv_query($conn, $qu);        
				$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
				$nmuserid = trim($d1u['NamaUser']);

				?>

				<div class="card shadow-sm mb-4">
					<div class="card-header bg-primary text-white">
						<b>🧾 QR Petugas Pemeriksa</b>
					</div>
					<div class="card-body text-center">
						<label class="form-label">Petugas:</label>
						<h5><strong><?= htmlspecialchars($nmuserid) ?></strong></h5>

						<img src="<?= $filename ?>" 
						alt="QR Petugas" 
						class="img-thumbnail mt-2" 
						style="background: #fff; border: 1px solid #ccc; max-width: 80%; height:auto;">

						<p class="mt-2 text-muted"><i>QR code ini merepresentasikan identitas petugas.</i></p>
					</div>
				</div>

				<button type="submit" class="btn btn-primary" name="simpan" value="simpan">
					<i class="bi bi-save"></i> Simpan Data
				</button>


				<hr>
				<div class="mb-3">
					<label for="ringkasan_asesmen" class="form-label">B. Ringkasan Asesmen</label>
					<textarea name="ringkasan_asesmen" id="ringkasan_asesmen" class="form-control" rows="8"><?php echo htmlspecialchars($ringkasan_asesmen ?? 'Silakan isi ringkasan asesmen di sini...', ENT_NOQUOTES); ?></textarea>

				</div>

				<div class="mb-3">
					<label for="identifikasi_masalah" class="form-label">C. Identifikasi Masalah</label>
					<textarea name="identifikasi_masalah" id="identifikasi_masalah" class="form-control" rows="8"><?php echo htmlspecialchars($identifikasi_masalah ?? 'Silakan isi identifikasi_masalah di sini...', ENT_NOQUOTES); ?></textarea>
				</div>

				<div class="mb-3">
					<label for="rencana_tindak_lanjut" class="form-label">D. Perencanaan Tindak Lanjut</label>
					<textarea name="rencana_tindak_lanjut" id="rencana_tindak_lanjut" class="form-control" rows="8"><?php echo htmlspecialchars($rencana_tindak_lanjut ?? 'Silakan isi rencana_tindak_lanjut di sini...', ENT_NOQUOTES); ?></textarea>
				</div>

				<div class="mb-3">
					<label for="cppt" class="form-label">E. Catatan Implementasi MPP</label>
					<textarea name="cppt" id="cppt" class="form-control" rows="8" placeholder="Pelaksanaan rencana, monitoring, fasilitas, koordinasi, komunikasi, kolaborasi, advokasi, hasil pelayanan, dan terminasi"><?php echo htmlspecialchars($cppt ?? 'Silakan isi CPPT di sini...', ENT_NOQUOTES); ?></textarea>
					<?php 
					include('r_soap_mpp.php');
					?>
				</div>
			</form>


		</body>
		</html>


	</div>
</div>

<?php

if (isset($_POST["simpan"])) {
	$userid = $user;
	$tglentry = $tgli;
	$tgl_mrs   = $_POST["tgl_mrs"] ?? null;
	$diagnosis = $_POST["diagnosis"] ?? '';
	$dpjp      = $_POST["dpjp"] ?? '';
	$tanggal_jam_lanjut = $_POST["tanggal_jam_lanjut"] ?? null;


	if (!empty($tanggal_jam_lanjut)) {
		$tanggal_jam_lanjut = date('Y-m-d H:i:s', strtotime($tanggal_jam_lanjut));
	} else {
		$tanggal_jam_lanjut = null; 
	}

	$ringkasan = $_POST["ringkasan_asesmen"] ?? '';
	$masalah   = $_POST["identifikasi_masalah"] ?? '';
	$tindak    = $_POST["rencana_tindak_lanjut"] ?? '';
	$cppt      = $_POST["cppt"] ?? '';
	$signature = $_POST["signature"] ?? '';

    // indikator
	$indikator = [];
	for ($i = 1; $i <= 7; $i++) {
		$indikator[$i] = $_POST["indikator_$i"] ?? null;
	}

    // Cek apakah data dengan noreg sudah ada
	$cek = sqlsrv_query($conn, "SELECT noreg FROM ERM_RI_MPP WHERE noreg = ?", [$noreg]);

	if ($cek && sqlsrv_has_rows($cek)) {
        // ✅ UPDATE
		$sql = "UPDATE ERM_RI_MPP SET
		userid=?, tglentry=?, tgl_mrs=?,
		indikator_1=?, indikator_2=?, indikator_3=?, indikator_4=?,
		indikator_5=?, indikator_6=?, indikator_7=?,
		tanggal_jam_lanjut=?, ringkasan_asesmen=?,
		identifikasi_masalah=?, rencana_tindak_lanjut=?, cppt=?, signature_base64=?
		WHERE noreg=?";

		$params = [
			$userid, $tglentry, $tgl_mrs, 
			$indikator[1], $indikator[2], $indikator[3], $indikator[4],
			$indikator[5], $indikator[6], $indikator[7],
			$tanggal_jam_lanjut, $ringkasan, $masalah, $tindak, $cppt, $signature,
			$noreg
		];
	} else {
        // ✅ INSERT	
		$sql = "INSERT INTO ERM_RI_MPP (
			noreg, userid, tglentry, tgl_mrs, indikator_1, indikator_2, indikator_3, indikator_4, indikator_5,
			indikator_6, indikator_7, tanggal_jam_lanjut, ringkasan_asesmen, identifikasi_masalah,
			rencana_tindak_lanjut, cppt, signature_base64
		) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$params = [
			$noreg, $userid, $tglentry, $tgl_mrs,
			$indikator[1], $indikator[2], $indikator[3], $indikator[4],
			$indikator[5], $indikator[6], $indikator[7],
			$tanggal_jam_lanjut, $ringkasan, $masalah, $tindak, $cppt, $signature
		];
	}

	echo $sql;
	$stmt = sqlsrv_query($conn, $sql, $params);

	if ($stmt) {
		$eror="Data berhasil disimpan";

		echo "
		<script>
		alert('".$eror."');
		window.location.replace('mpp.php?id=$id|$user');
		</script>
		";


			// exit;
	} else {
		die(print_r(sqlsrv_errors(), true));
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