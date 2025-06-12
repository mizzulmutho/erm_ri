<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

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

$q2       = "
SELECT NORM, KODEDEPT, NIK, NAMA, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS KELAMIN,
ALAMATPASIEN, KOTA, KODEKEL, TLP, TMPTLAHIR, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR, 
CONVERT(VARCHAR, tgllahir, 103) as TGLLAHIR, 
JENISPEKERJAAN, PENDIDIKAN, JABATAN, GOLDARAH, KELAS, AGAMA,
KELAS_PLAFON, PERUSH_ASAL, NOBPJS, ALERGI, NOKTP,NOMOR_PASPOR, 
NAMA_IBU_KANDUNG, NOKTP_IBU_KANDUNG, JENISKELAMIN2, AGAMA2, SUKU, BAHASA, RT, RW, KELURAHAN, KECAMATAN, KABUPATEN, KODEPOS, PROVINSI, NEGARA, ALAMAT_DOMISILI, RT_DOMISILI, 
RW_DOMISILI, KELURAHAN_DOMISILI, KECAMATAN_DOMISILI, KABUPATEN_DOMISILI, KODEPOS_DOMISILI, PROVINSI_DOMISILI, NEGARA_DOMISILI, HP, STATUS_PERNIKAHAN, X_PERKIRAAN_UMUR, 
X_LOKASI_DITEMUKAN, X_TGL_DITEMUKAN, X_NAMA_TANGGUNG_JAWAB, X_HP_TANGGUNG_JAWAB, X_HUBUNGAN_PASIEN, X_NAMA_PENGANTAR, X_HP_PENGANTAR, PASPOR, JAM_LAHIR
from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);                

$data2    = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);             

$kodedept = $data2[KODEDEPT];

$nama     = $data2[NAMA];
$kelamin  = $data2[KELAMIN];
$nik = trim($data2[NIK]);
$alamatpasien  = $data2[ALAMATPASIEN];
$kota     = $data2[KOTA];
$kodekel  = $data2[KODEKEL];
$telp     = $data2[TLP];
$tmptlahir     = $data2[TMPTLAHIR];
$tgllahir = $data2[TGLLAHIR];
$jenispekerjaan     = $data2[JENISPEKERJAAN];
$jabatan  = $data2[JABATAN];
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



?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Resume Medis</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

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
			$("#dokter").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dokter.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.kodedokter + ' - ' + item.nama + ' - ' + item.keterangan
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
			<font size='4px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
					&nbsp;&nbsp;
					<a href='http://192.168.10.4:1234/rekam_medik/master/pasien' class='btn btn-success' title='Edit' target='_blank'><i class="bi bi-pencil-square"></i></a>
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
					<b>FORMULIR DATA SOSIAL PASIEN - RM01</b><br>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<i>Diisi oleh petugas LOBBY/administrasi IGD/TPPRI</i>
				</div>
			</div>

			<table border='1' width="90%">
				<tr>
					<td width="30%">Nama Lengkap</td>
					<td><?php echo $data2[NAMA]; ?></td>
				</tr>
				<tr>
					<td>Tempat & Tgl. Lahir</td>
					<td><?php echo $data2[TMPTLAHIR].$data2[TGLLAHIR]; ?></td>
				</tr>
				<tr>
					<td>No. Ktp/ Id Card Lain</td>
					<td><?php echo $data2[NOKTP]; ?></td>
				</tr>
				<tr>
					<td>Jenis Kelamin</td>
					<td>
						<?php if ($data2[KELAMIN]=='L'){ echo "Laki-Laki"; }else{ echo "Perempuan"; }; ?>
					</td>
				</tr>
				<tr>
					<td>Status Perkawinan</td>
					<td></td>
				</tr>
				<tr>
					<td>Alamat Lengkap</td>
					<td><?php echo $data2[ALAMATPASIEN]; ?></td>
				</tr>
				<tr>
					<td>No. Telp</td>
					<td><?php echo $data2[TLP]; ?></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><?php echo $data2[EMAIL]; ?></td>
				</tr>
				<tr>
					<td>Agama</td>
					<td><?php echo $data2[AGAMA]; ?></td>
				</tr>
				<tr>
					<td>Bahasa</td>
					<td>
						<?php echo $data2[BAHASA]; ?>
					</td>
				</tr>
				<tr>
					<td>Suku</td>
					<td><?php echo $data2[SUKU]; ?></td>
				</tr>
				<tr>
					<td>Status Kependudukan</td>
					<td></td>
				</tr>
				<tr>
					<td>Pekerjaan</td>
					<td><?php echo $data2[JENISPEKERJAAN]; ?></td>
				</tr>
				<tr>
					<td>Nilai-Nilai Kepercayaan</td>
					<td></td>
				</tr>
				<tr>
					<td>Alamat & No. Telp Tempat Kerja</td>
					<td><?php echo $data2[ALAMAT_DOMISILI]; ?></td>
				</tr>
				<tr>
					<td>Nama Ibu Kandung</td>
					<td><?php echo $data2[NAMA_IBU_KANDUNG]; ?></td>
				</tr>
				<tr>
					<td>Keluarga Terdekat</td>
					<td></td>
				</tr>
				<tr>
					<td>Alamat Pemberitahuan Terdekat</td>
					<td></td>
				</tr>
				<tr>
					<td>No. Telp</td>
					<td></td>
				</tr>
			</table>

			<br>
		</form>
	</font>
</body>
</div>
</div>

<?php 


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>