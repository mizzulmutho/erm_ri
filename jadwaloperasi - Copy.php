<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$kodedept	= '9100';
$nama	= 'Pasien 1';
$kelamin	= 'Lak-laki';
$nik	= 'PGM11111';
$alamatpasien	= 'Gresik';
$kota	= 'Gresik';
$kodekel	= '20';
$telp	= '081....';
$tmptlahir	= 'Gresik';
$tgllahir	= '01/01/1988';
$jenispekerjaan	= 'Swasta';
$jabatan	= '';
$umur =  '30';
$norm='260587';

$tglmasuk	= '29/12/2023';
$kamar	= 'Kamar Kelas 1';

$keluhan_utama = "Nyeri Perut";
$lama_keluhan = "3 Hari";
$keluhan_lain = "Demam";
$riwayat_penyakit_nama = "Mag";
$riwayat_penyakit_lama = "sejak kecil";
$riwayat_keluarga = "hipertesi";
$riwayat_alergi = "";
$riwayat_pengobatan = "sanmol";
//$fisik = "";
$lab ="GDA : \n Gula Darah Puasa : \n Creatinin:\n Trigliserida: \n LDL:\n HDL:\n";
$radiologi = "Thorax : Normal \n ";
$lainlain = "";
$diagnosis_awal = "A01";
$diagnosis_akhir_primer = "A02";
$diagnosis_akhir_sekunder = "A03";
$masalah_utama = "";
$konsultasi = "Konsultasi ke Spesialist Penyakit Dalam";
$pengobatan = "";
$prognosis = "";
$keluarrs = "Sembuh";
$sebab_meninggal = "";
$tindaklanjut_aktifitas = "Normal";
$tindaklanjut_prosedur = "Normal";
$tindaklanjut_alatbantu = "Normal";
$edukasi_laborat = "Normal";
$edukasi_pencegahan = "Normal";
$edukasi_jadwalkontrol = "Kontrol Kembali 7/1/2024";
$edukasi_lain = "";
$perawatan1 = "";
$perawatan2 = "";
$perawatan3 = "";
$diet1 = "";
$diet2 = "";
$namaobat1 = "Sanmol";
$jumlah1 = "10";
$namaobat2 = "Amoxilin";
$jumlah2 = "10";
$namaobat3 = "Promag`";
$jumlah3 = "10";
$namaobat4 = "Vitamin C";
$jumlah4 = "10";
$namaobat5 = "Paracetamol";
$jumlah5 = "10";


if (isset($_POST["Print"])) {

	echo "
	<script>
	top.location='jadwaloperasi_print.php?id=$id|$user|$idrasuhan';
	</script>
	";    


}

if (isset($_POST["Pdf"])) {

	echo "
	<script>
	downloadPDF();
	</script>
	";

}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Resume Medis</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

	<script type="text/javascript">
		var doc = new jsPDF(); 
		var specialElementHandlers = { 
			'#editor': function (element, renderer) { 
				return true; 
			} 
		};
		$('#submit').click(function () { 
			doc.fromHTML($('#content').html(), 15, 15, { 
				'width': 190, 
				'elementHandlers': specialElementHandlers 
			}); 
			doc.save('sample-page.pdf'); 
		});
	</script>

</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.pasien_mcu.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
					&nbsp;&nbsp;
					<a href='#' class='btn btn-success'>Import</a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-danger'>Pdf</a> -->
					<input type='submit' name='Pdf' value='Pdf' class='btn btn-danger'>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info'>Print</a> -->
					<input type='submit' name='Print' value='Print' class='btn btn-info'>

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
					<h5><b>RUMAH SAKIT PETROKIMIA GRESIK</b></h5>
					Gresik
				</div>
				<div class="col-6">
					<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur; ?>
					<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien; ?>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-12 text-center">
					<b>PERSIAPAN PASIEN OPERASI</b><br>
				</div>
			</div>

			<table width='100%' border='0'>
				<tr>
					<td>
						Tinggi badan : <input type='text' name='jo1' value='170'> Cm <br>
						Berat badan  &nbsp;&nbsp;: <input type='text' name='jo2' value='70'> Kg
					</td>
					<td>
						Tanggal Operasi : <input type='text' name='jo3' value='01/01/2024'> <br>
						Riwayat Alergi  &nbsp;&nbsp; : <input type='text' name='jo4' value='-'>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table border='1' width='100%' >
							<tr>								
								<td rowspan="2" width="40%" align="center" bgcolor="#696969"><font color='white'><b>Nama</b></font></td>
								<td colspan="2" align="center" bgcolor="#696969"><font color='white'><b>Rawat Inap</b></font></td>
								<td colspan="2" align="center" bgcolor="#696969"><font color='white'><b>IBS</b></font></td>
								<td rowspan="2" align="center" bgcolor="#696969"><font color='white'><b>Keterangan</b></font></td>
							</tr>
							<tr>
								<td width="5%" align="center" bgcolor="#696969"><font color='white'><b>Ya</b></font></td>
								<td width="5%" align="center" bgcolor="#696969"><font color='white'><b>Tidak</b></font></td>
								<td width="5%" align="center" bgcolor="#696969"><font color='white'><b>Ya</b></font></td>
								<td width="5%" align="center" bgcolor="#696969"><font color='white'><b>Tidak</b></font></td>
							</tr>
							<tr>
								<td><b>1. Persiapan Administrasi</b></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>&bull; Formulir Persetujuan Operasi Lengkap & Terisi
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td>&bull; Formulir Laporan Operasi
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td>&bull; Formulir Laporan Anestesi
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td>&bull; Hasil Laboratorium
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td>&bull; Hasil Radiologi
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td>&bull; Hasil EKG
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td>&bull; Hasil lain-lain
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td><b>2. Persiapan Fisik</b></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70'></td>
							</tr>
							<tr>
								<td>&bull; Puasa
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder="jam mulai"></td>
							</tr>
							<tr>
								<td>&bull; Persiapan usus
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder="jenis"></td>
							</tr>
							<tr>
								<td>&bull; Pasang DC
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder="No"></td>
							</tr>
							<tr>
								<td>&bull; Pengosongan Kandung Kencing sebelum operasi
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
							</tr>
							<tr>
								<td>&bull; Pasang infus
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td>
									IV Cath No : <input type='text' name='jo' value='<?php echo $jo;?>' size='10' placeholder="">
									Cairan infus : <input type='text' name='jo' value='<?php echo $jo;?>' size='10' placeholder="">/tpm
								</td>
							</tr>
							<tr>
								<td>&bull; Pasang NGT
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
							</tr>
							<tr>
								<td>&bull; Pencukuran daerah operasi
								</td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
								<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
								<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>							</tr>
								<tr>
									<td>&bull; Rambut palsu, gigi palsu, contact lens,sudah dilepas
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Cat kuku dan make up muka sudah dibersihkan
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Perhiasan dan arloji dll, sudah dilepas
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Mandi 1 jam sebelum operasi
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Mengganti pakaian operasi
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Persiapan darah untuk tranfusi
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Obat yang dibawa ke IBS
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Premedikasi
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Antibiotik pre – op
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td>
										Diberikan jam : <input type='text' name='jo' value='<?php echo $jo;?>' size='30' placeholder=""><br>
										Jenis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='jo' value='<?php echo $jo;?>' size='30' placeholder=""><br>
										Dosis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='jo' value='<?php echo $jo;?>' size='30' placeholder=""><br>
									</td>
								</tr>
								<tr>
									<td><b>3. Persiapan Khusus</b></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>&bull; DM – Insulin Pre Op
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Hipertensi-obat anti hipertensi Pre Op
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Asma-Obat Asma/corticosteroid Pre Op
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; Lain-lain : ………………………….
									</td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo<>"YA"){echo "checked";}?>></td>
									<td align="center"><input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>></td>
									<td align="left"><input type='text' name='jo' value='<?php echo $jo;?>' size='70' placeholder=""></td>
								</tr>
								<tr>
									<td>&bull; TTV
									</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td>
										TD : Nadi :
										Suhu : RR :
									</td>
								</tr>
								<tr>
									<td><b>4. Visite dokter pra bedah</b></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>1) Dokter Operator
									</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>2) Dokter Anestes
									</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="3">Perawat</td>
									<td colspan="3">Perawat IBS</td>
								</tr>
								<tr>
									<td colspan="3">
										<div class="col-8">
											<select id="perawat" name="perawat">
												<option value="">---Verifikasi Perawat Pemberi Resume---</option>
												<option value="R68.83">Joni</option>
												<option value="R56.0">Sobirin</option>
												<option value="O75.2">Syihab</option>
											</select>
										</div>
									</td>
									<td colspan="3">
										<div class="col-8">
											<select id="perawat" name="perawat">
												<option value="">---Verifikasi Perawat IBS Pemberi Resume---</option>
												<option value="R68.83">Joni</option>
												<option value="R56.0">Sobirin</option>
												<option value="O75.2">Syihab</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3">Tanda Tangan dan Nama Terang</td>
									<td colspan="3">Tanda Tangan dan Nama Terang</td>
								</tr>
								<tr>
									<td colspan="6"><b>*Beri tanda (v) pada kolom yang dipilih</b></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<input type='submit' name='simpan' value='simpan'>

				<div class="row">
					<div class="col-12 text-center">
						<b>LAPORAN OPERASI  </b><br>
					</div>
				</div>

				<div class="row">
					<div class="col-12 text-left">
						<b>Diisi Oleh Petugas Kamar Operasi</b><br>
					</div>
				</div>

				<table width='100%' border='1'>
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-4">
									Diagnosa pra pembedahan
								</div>
								<div class="col-8">
									: 
									<!-- <input type='text' name='' value='' size='50'> -->
									<select id="diagnosis" name="diagnosis">
										<option value=""></option>
										<option value="R68.83">R68.83 - chills without feve</option>
										<option value="R56.0">R56.0 - febrile convulsions</option>
										<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
										<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
										<option value="R68.0">R68.0 - hypothermia due to illness</option>
										<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
										<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
									</select>


									BB : <input type='text' name='' value='' size='20'> kg
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-4">
									Tindakan yang dilakukan
								</div>
								<div class="col-8">
									: 
									<!-- <input type='text' name='' value='' size='50'> -->
									<select id="diagnosis" name="diagnosis">
										<option value=""></option>
										<option value="R68.83">R68.83 - chills without feve</option>
										<option value="R56.0">R56.0 - febrile convulsions</option>
										<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
										<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
										<option value="R68.0">R68.0 - hypothermia due to illness</option>
										<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
										<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
									</select>
									<select id="diagnosis" name="diagnosis">
										<option value=""></option>
										<option value="R68.83">R68.83 - chills without feve</option>
										<option value="R56.0">R56.0 - febrile convulsions</option>
										<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
										<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
										<option value="R68.0">R68.0 - hypothermia due to illness</option>
										<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
										<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
									</select>
									<br>
									: <select id="diagnosis" name="diagnosis">
										<option value=""></option>
										<option value="R68.83">R68.83 - chills without feve</option>
										<option value="R56.0">R56.0 - febrile convulsions</option>
										<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
										<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
										<option value="R68.0">R68.0 - hypothermia due to illness</option>
										<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
										<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
									</select>
									<select id="diagnosis" name="diagnosis">
										<option value=""></option>
										<option value="R68.83">R68.83 - chills without feve</option>
										<option value="R56.0">R56.0 - febrile convulsions</option>
										<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
										<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
										<option value="R68.0">R68.0 - hypothermia due to illness</option>
										<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
										<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
									</select><br>
									: <input type='text' name='' value='' size='100'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-4">
									Diagnosa pasca pembedahan
								</div>
								<div class="col-8">
									: 
									<!-- <input type='text' name='' value='' size='50'> -->
									<select id="diagnosis_awal" name="diagnosis_awal">
										<option value=""></option>
										<option value="R68.83">R68.83 - chills without feve</option>
										<option value="R56.0">R56.0 - febrile convulsions</option>
										<option value="O75.2">O75.2 - fever of unknown origin during labor</option>
										<option value="P81.9">P81.9 - fever of unknown origin in newborn</option>
										<option value="R68.0">R68.0 - hypothermia due to illness</option>
										<option value="T88.3">T88.3 - malignant hyperthermia due to anesthesia</option>
										<option value="O86.4">O86.4 - puerperal pyrexia NOS</option>
									</select>

								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type='submit' name='simpan' value='simpan'>
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-3">
									Operator 
									<!-- <input type='text' name='' value='' size='30'> -->
									<div class="col-8">
										<select id="dpjp" name="dpjp">
											<option value="">---Verifikasi Operator Operasi---</option>
											<option value="R68.83">Joni Setiawan</option>
											<option value="R56.0">Sobirin</option>
											<option value="O75.2">Syihab</option>
										</select>
									</div>
								</div>
								<div class="col-3">
									Asisten 
									<!-- <input type='text' name='' value='' size='30'> -->
									<div class="col-8">
										<select id="dpjp" name="dpjp">
											<option value="">---Verifikasi Operator Operasi---</option>
											<option value="R68.83">Joni Setiawan</option>
											<option value="R56.0">Sobirin</option>
											<option value="O75.2">Syihab</option>
										</select>
									</div>
								</div>
								<div class="col-3">
									Instrumen 
									<!-- <input type='text' name='' value='' size='30'> -->
									<div class="col-8">
										<select id="dpjp" name="dpjp">
											<option value="">---Verifikasi Operator Operasi---</option>
											<option value="R68.83">Joni Setiawan</option>
											<option value="R56.0">Sobirin</option>
											<option value="O75.2">Syihab</option>
										</select>
									</div>
								</div>
								<div class="col-3">
									Petugas on Steril 
									<!-- <input type='text' name='' value='' size='30'> -->
									<div class="col-8">
										<select id="dpjp" name="dpjp">
											<option value="">---Verifikasi Operator Operasi---</option>
											<option value="R68.83">Joni Setiawan</option>
											<option value="R56.0">Sobirin</option>
											<option value="O75.2">Syihab</option>
										</select>
									</div>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<input type='submit' name='simpan' value='simpan'>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-3">
									Tgl Operasi
								</div>
								<div class="col-3">
									: <input type='text' name='' value='01/01/2024' size='30'>
								</div>
								<div class="col-3">
									Lama Operasi
								</div>
								<div class="col-3">
									: <input type='text' name='' value='1 jam' size='30'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-3">
									Mulai Jam
								</div>
								<div class="col-3">
									: <input type='text' name='' value='07:00:00' size='30'>
								</div>
								<div class="col-6">
									Jumlah Perdarahan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='' value='' size='30'><br>
									Tranfusi durante operasi : <input type='text' name='' value='' size='30'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-3">
									Selesai jam
								</div>
								<div class="col-3">
									: <input type='text' name='' value='08:00:00' size='30'>
								</div>
								<div class="col-6">
									Pemeriksaan jaringan : <input type='text' name='' value='' size='30'><br>
									Macam jaringan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='' value='' size='30'><br>
									Jenis pemeriksaan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='' value='' size='30'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<input type='submit' name='simpan' value='simpan'>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-3">
									Jenis Anestesi <input type='text' name='' value='' size='30'>
								</div>
								<div class="col-3">
									Mulai Jam <input type='text' name='' value='' size='30'>
								</div>
								<div class="col-3">
									Selesai Jam <input type='text' name='' value='' size='30'>
								</div>
								<div class="col-3">
									Obat Anestesi <input type='text' name='' value='' size='30'>
								</div>
								<div class="col-3">
									Anestesi <input type='text' name='' value='' size='30'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-4">
									Golongan Operasi
								</div>
								<div class="col-4">
									Macam operasi
								</div>
								<div class="col-4">
									Selesai Jam
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-4">
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Poliklinik<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Kecil<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Sedang<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>sar<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Sar Khusus<br>
								</div>
								<div class="col-4">
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Bersih<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Bersih Terkontaminasi<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Kontaminasi<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>kotor<br>
								</div>
								<div class="col-4">
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Darurat<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Urgen<br>
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Elektif<br>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-4">
									Kamar Operasi No : <input type='text' name='' value='' size='20'>
								</div>
								<div class="col-4">
									Ronde Ke : <input type='text' name='' value='' size='20'>
								</div>
								<div class="col-4">
									Hasil Perhitungan Kasa : <input type='text' name='' value='' size='20'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center">
							<div class="row">
								<div class="col-12">
									Penjelasan Teknik Operasi dan Rincian Temuan
									<br>
									<textarea name= "fisik" id="fisik" style="min-width:950px; min-height:150px;"><?php echo $fisik;?></textarea>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-12">
									Komplikasi : <input type='text' name='' value='' size='100'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-12">
									Implan : 
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Tidak 
									<input type='checkbox' name='jo' value='YA' <?php if ($jo=="YA"){echo "checked";}?>>Ya, 
									sebutkan: <input type='text' name='riwayat_pengobatan' value='<?php echo $riwayat_pengobatan; ?>' size='100'>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center">
							<div class="row">
								<div class="col-12">
									<select id="dpjp" name="dpjp">
										<option value="">---Verifikasi Operator Operasi---</option>
										<option value="R68.83">Joni Setiawan</option>
										<option value="R56.0">Sobirin</option>
										<option value="O75.2">Syihab</option>
									</select>
								</div>
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center">
							<input type='submit' name='simpan' value='simpan' style="height: 90px;width: 300px;color: white;background: green">
						</td>
					</tr>
				</table>

				<br>
				<br>
				<br>
				<br>
				<br>
			</form>
		</font>
	</body>
</div>
</div>

<?php 

if (isset($_POST["simpan"])) {

	$eror ="Simpan Success";

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";

}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#diagnosis').select2();
	});
</script>


<script type="text/javascript">
	$(document).ready(function() {
		$('#diagnosis_awal').select2();
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#diagnosis_akhir_primer').select2();
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#diagnosis_akhir_sekunder').select2();
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#dpjp').select2();
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#perawat').select2();
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#apoteker').select2();
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#ahligizi').select2();
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#keluarrs').select2();
	});
</script>

