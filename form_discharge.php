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

if($edit){
	$qi="SELECT nama_obat,jumlah,aturan_pakai,instruksi_khusus FROM ERM_RI_DISCHARGE where noreg='$noreg' and id=$id_discharge";
	$hi  = sqlsrv_query($conn, $qi);        
	$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
	$nama_obat = $di['nama_obat'];
	$jumlah = $di['jumlah'];
	$aturan_pakai = $di['aturan_pakai'];
	$instruksi_khusus = $di['instruksi_khusus'];

}

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
	<script>
		$(function() {
			$("#icd102").autocomplete({
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
	<script>
		$(function() {
			$("#icd103").autocomplete({
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

	
</head> 

<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='3px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='form_discharge.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-danger'>Pdf</a> -->
					<!-- <input type='submit' name='Pdf' value='Pdf' class='btn btn-danger'> -->
					&nbsp;&nbsp;
					<br>
					<br>
<!-- 				<div class="row">
					<div class="col-12 text-center bg-success text-white"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
				</div>
			-->				
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
					<b>FORM DISCHARGE</b><br>
				</div>
			</div>
			<hr> 
			<div class="row">
				<div class="col-6">
					Tgl Masuk rumah sakit : 						
					<input class="form-control-sm" name="tglmasuk_rs" value="<?php echo $tglmasuk_rs;?>" id="" type="text" onfocus="nextfield ='';" placeholder="">
				</div>
				<div class="col-6">
					Tgl Rencana pulang : 						
					<input class="form-control-sm" name="tglpulang_rs" value="<?php echo $tglpulang_rs;?>" id="" type="text" onfocus="nextfield ='';" placeholder="">
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					Alasan Masuk RS : 						
					<input class="form-control" name="alasanmasuk_rs" value="<?php echo $alasanmasuk_rs;?>" id="" type="text" onfocus="nextfield ='';" placeholder="">					
				</div>
			</div>
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

			<div class="row">
				<div class="col-12">
					Surat Kontrol : 						
					<input class="form-control" name="surat_kontrol" value="<?php echo $surat_kontrol;?>" id="" type="text" onfocus="nextfield ='';" placeholder="">					
				</div>
			</div>

			<br>
			<div class="col-8 text-center">
				&nbsp;<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 40px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
			</div>

			<hr> 

			<?php if($edit){ ?>
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-4">
								<font size='3'>Nama Obat</font>
							</div>
							<div class="col-8">
								<input class="" name="nama_obat" value="<?php echo $nama_obat;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<font size='3'>Jumlah</font>
							</div>
							<div class="col-8">
								<input class="" name="jumlah" value="<?php echo $jumlah;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<font size='3'>Aturan Pakai</font>
							</div>
							<div class="col-8">
								<input class="" name="aturan_pakai" value="<?php echo $aturan_pakai;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<font size='3'>Instruksi Khusus</font>
							</div>
							<div class="col-8">
								<input class="" name="instruksi_khusus" value="<?php echo $instruksi_khusus;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<font size='3'>Edit Instruksi Khusus <input type='checkbox' name='edit_instruksi' value='Y'></font>
							</div>
							<div class="col-8">
								<input type='checkbox' name='ins1' value='[&check;] diminum'>diminum
								<input type='checkbox' name='ins2' value='[&check;] dimasukkan vagina'>dimasukkan vagina<br>
								<input type='checkbox' name='ins3' value='[&check;] dioleskan pada bagian yang sakit'>dioleskan pada bagian yang sakit
								<input type='checkbox' name='ins4' value='[&check;] dikumur selama 30 detik - 1 menit'>dikumur selama 30 detik - 1 menit<br>
								<input type='checkbox' name='ins5' value='[&check;] diteteskan'>diteteskan
								<input type='checkbox' name='ins6' value='[&check;] disuntikkan'>disuntikkan
								<input type='checkbox' name='ins7' value='[&check;] rendam duduk selama 15 menit'>rendam duduk selama 15 menit<br>
								<input type='checkbox' name='ins8' value='[&check;] pagi'>pagi
								<input type='checkbox' name='ins9' value='[&check;] siang'>siang
								<input type='checkbox' name='ins10' value='[&check;] malam'>malam<br>
								<input type='checkbox' name='ins11' value='[&check;] tiap 6 jam'>tiap 6 jam
								<input type='checkbox' name='ins12' value='[&check;] tiap 8 jam'>tiap 8 jam
								<input type='checkbox' name='ins13' value='[&check;] tiap 12 jam'>tiap 12 jam<br>
								<input type='checkbox' name='ins14' value='[&check;] sebelum makan'>sebelum makan
								<input type='checkbox' name='ins15' value='[&check;] setelah makan'>setelah makan<br>
								<input type='checkbox' name='ins16' value='[&check;] bersama dengan makan setelah 1 suapan pertama'>bersama dengan makan setelah 1 suapan pertama<br>
								<input type='checkbox' name='ins17' value='[&check;] bila perlu'>bila perlu<br>
								<input class="" name="ins18" value="" placeholder="Keterangan Lain" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
						<div class="row">
							<div class="col-4">
								<font size='3'></font>
							</div>
							<div class="col-8">
								<input type='submit' name='edit' value='edit' style="color: white;background: #6495ED;border-color: #1E90FF	;">
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<hr>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>

			<div class="row">
				<div class="col-4">
					<b>Obat yang diberikan Saat Perawatan</b>
				</div>
				<div class="col-8">

				</div>
				<div class="col-12">
					<?php 
					$q="
					select distinct nama_obat
					from ERM_RI_RPO
					where noreg='$noreg' order by nama_obat asc
					";
					$hasil  = sqlsrv_query($conn, $q);  
					$no=1;
					while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
						echo $no.' - '.$data[nama_obat];echo "<br>";
						$no+=1;
					}

					?>

				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-4">
					<b>Obat yang dibawa Pulang : </b>
					<!-- <input type='submit' name='ambil_dari_eresep' value='ambil_dari_eresep' style="color: white;background: #F4A460;border-color: #F4A460;"> -->
				</div>
				<br>

				<?php 

				$q2		= "SELECT        W_EResep.Noreg from W_EResep where W_EResep.Noreg = '$noreg' AND W_EResep.Kategori like '%KRS%'";
				$hasil2  = sqlsrv_query($conn, $q2);			  					
				$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
				$cobat_pulang	= $data2[Noreg];
				?>
				<div class="col-12">
					<?php
					if(empty($cobat_pulang)){
						echo "Obat KRS Belum ada !";						
					}else{

						$qo=
						"
						SELECT        W_EResep.Noreg, W_EResep_R.Jenis, W_EResep_R.KodeR, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, W_EResep_R.WaktuPakai, W_EResep_Racikan.Nama, W_EResep_R.Keterangan, W_EResep_R.DeletedBy,
						AFarm_MstObat.NAMABARANG, W_EResep.Id, W_EResep_Racikan.Dosis,W_EResep_R.Id as idr
						FROM            W_EResep INNER JOIN
						W_EResep_R ON W_EResep.Id = W_EResep_R.IdResep LEFT OUTER JOIN
						W_EResep_Racikan ON W_EResep_R.Id = W_EResep_Racikan.IdR LEFT OUTER JOIN
						AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
						WHERE        (W_EResep.Noreg = '$noreg') AND (W_EResep.Kategori LIKE '%KRS%')
						";
						$hasilo  = sqlsrv_query($conn, $qo);  
						$noo=1;
						echo "<table border='1'>";
						echo "
						<tr><td>no</td><td>nama</td><td>jumlah</td><td>aturan_pakai</td><td>instruksi_khusus</td><td>DeletedBy</td><td>edit</td></tr>
						";

						while   ($datao = sqlsrv_fetch_array($hasilo,SQLSRV_FETCH_ASSOC)){ 
							if($datao['Jenis']=='1'){
								$nama_obat=$datao['NAMABARANG'];
							}else{
								$nama_obat=$datao['Nama'];
							}
							$jumlah=$datao['Jumlah'];
							$aturan_pakai=$datao['AturanPakai'];
							$instruksi_khusus=$datao['Keterangan'];
							$DeletedBy=$datao['DeletedBy'];

							echo "
							<tr>
							<td>$noo</td>
							<td>$nama_obat</td>
							<td>$jumlah</td>
							<td>$aturan_pakai</td>
							<td>$instruksi_khusus</td>
							<td>$DeletedBy</td>
							<td><a href='obat_resep_edit.php?id=$id|$user|$datao[idr]'>edit</a></td>
							</tr>
							";
							$noo+=1;
						}
						echo "</table>";

					}
					?>	
				</div>

				<div class="col-12"><br><br>
					<font color='#D76C82'><b>Tambah Obat saat Pulang</b></font>
					<br>
					<table width="100%">
						<tr>
							<td width="20%">nama_obat</td><td><input class="form-control" name="obatd" value="<?php echo $obatd;?>" type="text"></td>
						</tr>
						<tr>
							<td>jumlah</td><td><input class="form-control" name="jumlahd" value="<?php echo $jumlahd;?>" type="text"></td>
						</tr>
						<tr>
							<td>aturan pakai</td><td><input class="form-control" name="aturan_pakaid" value="<?php echo $aturan_pakaid;?>" type="text"></td>
						</tr>
						<tr>
							<td>instruksi khusus</td><td><input class="form-control" name="instruksi_khususd" value="<?php echo $instruksi_khususd;?>" type="text"></td>
						</tr>
					</table>
					<br>
					&nbsp;<button type='submit' name='simpan_obat' value='simpan_obat' class="btn btn-info" type="button" style="height: 40px;width: 250px;"><i class="bi bi-save-fill"></i> tambah obat pulang</button>
					&nbsp;
					<a href='eresep_list_discharge.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-info'><i class="bi bi-box-arrow-in-right"></i> Ambil Data dari E-Resep</a>

					<br><br>
				</div>

				<div class="col-12">
					<table class='table'>
						<tr>
							<td>no</td>
							<td>nama_obat</td>
							<td>jumlah</td>
							<td>aturan pakai</td>
							<td>instruksi khusus</td>
							<td>edit</td>
							<td>hapus</td>
						</tr>
						<?php 
						$q="
						select id,nama_obat,jumlah,aturan_pakai,instruksi_khusus
						from ERM_RI_DISCHARGE
						where noreg='$noreg' order by nama_obat asc
						";
						$hasil  = sqlsrv_query($conn, $q);  
						$no=1;
						while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
							echo 
							"
							<tr>
							<td>$no</td>
							<td>$data[nama_obat]</td>
							<td>$data[jumlah]</td>
							<td>$data[aturan_pakai]</td>
							<td>$data[instruksi_khusus]</td>
							<td><a href='form_discharge.php?id=$id|$user|edit|$data[id]'>edit</a></td>
							<td><a href='del_obatpulang.php?id=$id|$user|$data[id]'>hapus</a></td>
							</tr>
							";
							$no+=1;
						}
						?>
					</table>
				</div>
			</div>




			<div class="row">
				<div class="col-4">
					&nbsp;
				</div>
				<div class="col-8 text-center">
					<!-- <input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"> -->
				</div>
			</div>

			<br><br><br>
		</font>
	</form>
</font>
</body>
</div>
</div>

<?php 

if (isset($_POST["edit"])) {
	$edit_instruksi = $_POST["edit_instruksi"];
	if($edit_instruksi){
		$ins1 = $_POST["ins1"];
		$ins2 = $_POST["ins2"];
		$ins3 = $_POST["ins3"];
		$ins4 = $_POST["ins4"];
		$ins5 = $_POST["ins5"];
		$ins6 = $_POST["ins6"];
		$ins7 = $_POST["ins7"];
		$ins8 = $_POST["ins8"];
		$ins9 = $_POST["ins9"];
		$ins10 = $_POST["ins10"];
		$ins11 = $_POST["ins11"];
		$ins12 = $_POST["ins12"];
		$ins13 = $_POST["ins13"];
		$ins14 = $_POST["ins14"];
		$ins15 = $_POST["ins15"];
		$ins16 = $_POST["ins16"];
		$ins17 = $_POST["ins17"];
		$ins18 = $_POST["ins18"];

		$instruksi_khusus = $ins1.$ins2.$ins3.$ins4.$ins5.$ins6.$ins7.$ins8.$ins9.$ins10.$ins11.$ins12.$ins13.$ins14.$ins15.$ins16.$ins17.$ins18;

		$qd  = "update ERM_RI_DISCHARGE set  instruksi_khusus = '$instruksi_khusus' where id='$id_discharge'";
		$hsd = sqlsrv_query($conn,$qd);

		if($hsd){
			$eror = "Success";
		}else{
			$eror = "Gagal Insert";

		}

	}

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";

}

if (isset($_POST["ambil_dari_eresep"])) {

	$qd  = "delete from ERM_RI_DISCHARGE where noreg='$noreg'";
	$hsd = sqlsrv_query($conn,$qd);

	$qo=
	"
	SELECT        W_EResep.Noreg, W_EResep_R.Jenis, W_EResep_R.KodeR, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, W_EResep_R.WaktuPakai, W_EResep_Racikan.Nama, 
	AFarm_MstObat.NAMABARANG, W_EResep.Id, W_EResep_Racikan.Dosis
	FROM            AFarm_MstObat INNER JOIN
	W_EResep_R ON AFarm_MstObat.KODEBARANG = W_EResep_R.KodeR INNER JOIN
	W_EResep ON W_EResep_R.IdResep = W_EResep.Id LEFT OUTER JOIN
	W_EResep_Racikan ON W_EResep_R.Id = W_EResep_Racikan.IdR
	WHERE        (W_EResep.Noreg = '$noreg') AND W_EResep.Kategori like '%KRS%'

	";
	$hasilo  = sqlsrv_query($conn, $qo);  
	$noo=1;
	while   ($datao = sqlsrv_fetch_array($hasilo,SQLSRV_FETCH_ASSOC)){ 
		$noo+=1;
		if($datao['Jenis']=='1'){
			$nama_obat=$datao['NAMABARANG'];
		}else{
			$nama_obat=$datao['Nama'];
		}
		$jumlah=$datao['Jumlah'];
		$aturan_pakai=$datao['AturanPakai'];
		$instruksi_khusus=$datao['CaraPakai'];

		$q  = "insert into ERM_RI_DISCHARGE(noreg,userid,tglentry,tgl,nama_obat,jumlah,aturan_pakai,instruksi_khusus) 
		values ('$noreg','$user','$tglinput','$tglinput','$nama_obat','$jumlah','$aturan_pakai','$instruksi_khusus')";
		$hs = sqlsrv_query($conn,$q);


	}

	header("Location: form_discharge.php?id=$id|$user");

}

if (isset($_POST["simpan_obat"])) {
	
	$obatd	= $_POST["obatd"];
	$jumlahd	= $_POST["jumlahd"];
	$aturan_pakaid	= $_POST["aturan_pakaid"];
	$instruksi_khususd	= $_POST["instruksi_khususd"];

	$q  = "insert into ERM_RI_DISCHARGE(noreg,userid,tglentry,tgl,nama_obat,jumlah,aturan_pakai,instruksi_khusus) 
	values ('$noreg','$user','$tglinput','$tglinput','$obatd','$jumlahd','$aturan_pakaid','$instruksi_khususd')";
	$hs = sqlsrv_query($conn,$q);

	$eror="Success";
	echo "
	<script>
	alert('".$eror."');
	window.location.replace('form_discharge.php?id=$id|$user');
	</script>
	";

}

if (isset($_POST["simpan"])) {


	$resume20	= $_POST["resume20"];
	$resume21	= $_POST["resume21"];
	$resume22	= $_POST["resume22"];

	$q  = "update ERM_RI_RESUME set
	resume20	='$resume20',
	resume21	='$resume21',
	resume22	='$resume22'
	where noreg='$noreg'
	";
	$hs = sqlsrv_query($conn,$q);

	$rowb = explode('-',$resume20);
	$resume20b  = trim($rowb[0]);
	$rowc = explode('-',$resume21);
	$resume21b  = trim($rowc[0]);
	$rowd = explode('-',$resume22);
	$resume22b  = trim($rowd[0]);

	$q  = "update ARM_PERIKSA set
	KODEICD    ='$resume20b',
	KODEICD2    ='$resume21c',
	KODEICD3    ='$resume22d'
	where noreg='$noreg' and kodeunit='$KODEUNIT'
	";
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
