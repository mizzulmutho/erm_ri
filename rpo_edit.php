<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idrpo = $row[2]; 
$idresep = $row[3];
$idrpo_detail = $row[4];

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

$q3       = "select nomor,eresep from ERM_RI_RPO_header where id='$idrpo'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$nomor = trim($data3[nomor]);
$cek_eresep = $data3[eresep];


$q3b       = "select CONVERT(VARCHAR, tgl, 103) as tgl, nama_obat, jumlah, dosis, waktu_penggunaan,
interval, dokter, apoteker, periksa, pemberi, keluarga,nomor
from ERM_RI_RPO
where id_rpo_header='$idrpo'";
$hasil3b  = sqlsrv_query($conn, $q3b);  
$data3b    = sqlsrv_fetch_array($hasil3b, SQLSRV_FETCH_ASSOC);                      
$nama_obat = $data3b[nama_obat];
$cek_nama_obat = $nama_obat;

$interval = $data3[interval];
$dokter = $data3[dokter];
$apoteker = $data3[apoteker];
$periksa = $data3[periksa];
$pemberi = $data3[pemberi];
$keluarga = $data3[keluarga];
$nomor = $data3[nomor];




//resep
$q="
SELECT        TOP (200) W_EResep_R.Id, CONVERT(VARCHAR, W_EResep_R.TglEntry, 25) AS tglentry, W_EResep_R.KodeR, AFarm_MstObat.NAMABARANG, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, 
W_EResep_R.WaktuPakai, W_EResep_R.Keterangan, W_EResep_R.Satuan, Afarm_MstSatuan.NAMASATUAN
FROM            W_EResep_R INNER JOIN
Afarm_MstSatuan ON W_EResep_R.Satuan = Afarm_MstSatuan.KODESATUAN LEFT OUTER JOIN
AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
WHERE        (W_EResep_R.IdResep = $idresep)
";
$hasil  = sqlsrv_query($conn, $q);  
$no=1;
while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

	$Id = $data[Id];
	$tgl = $data[tglentry];
	$nama_obat = $data[NAMABARANG];
	$dosis = $data[AturanPakai];
	$jumlah = $data[Jumlah];
	$waktu_penggunaan = $data[WaktuPakai];

	$q  = "insert into ERM_RI_RPO(noreg,userid,tglentry,tgl,nama_obat,dosis,jumlah,waktu_penggunaan,id_eresep,id_eresep_detail,id_rpo_header,nomor) 
	values ('$noreg','$user','$tgl','$tgl','$nama_obat','$dosis','$jumlah','$waktu_penggunaan',$idresep,$data[Id],$idrpo,'$nomor')";
	$hs = sqlsrv_query($conn,$q);

	$no += 1;

}

if($idresep=='edit'){
	$q3       = "select CONVERT(VARCHAR, tgl, 103) as tgl, nama_obat, jumlah, dosis, waktu_penggunaan,
	interval, dokter, apoteker, periksa, pemberi, keluarga
	from ERM_RI_RPO
	where id='$idrpo_detail'";
	$hasil3  = sqlsrv_query($conn, $q3);  
	$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
	$nama_obat = $data3[nama_obat];
	$dosis = $data3[dosis];
	$jumlah = $data3[jumlah];
	$interval = $data3[interval];
	$dokter = $data3[dokter];
	$apoteker = $data3[apoteker];
	$periksa = $data3[periksa];
	$pemberi = $data3[pemberi];
	$keluarga = $data3[keluarga];

}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>RPO DETAIL</title>  
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


	<script>
		$(function() {
			$("#apoteker").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_apoteker.php', //your                         
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

		<body onload="document.myForm.nama_obat.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='rpo2.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
<!-- 					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
-->					&nbsp;&nbsp;
<!-- <a href="http://192.168.10.4:1234/rekam_medik/entry_tindakan/rawat_inap/<?php echo $KODEUNIT; ?>/<?php echo $noreg; ?>/<?php echo $norm; ?>/resep/" target="_blank" class='btn btn-success'><i class="bi bi-box-arrow-in-right"></i> Buat E-Resep</a> -->
&nbsp;&nbsp;
<!-- <a href='eresep_list.php?id=<?php echo $id.'|'.$user; ?>' class='btn btn-success'><i class="bi bi-box-arrow-in-right"></i> Ambil dari E-Resep</a> -->
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
					<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col-12 text-center">
					<b>REKAM PEMBERIAN OBAT</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Nama obat
							</div>
							<div class="col-8">
								: <input class="" name="nama_obat" value="<?php echo $nama_obat;?>" id="obat" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Obat">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Dosis
							</div>
							<div class="col-8">
								: <input class="" name="dosis" value="<?php echo $dosis;?>" id="obat" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Dosis Obat">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Interval Jadwal Pemberian Obat
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="interval" value="<?php echo $interval;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder=""> -->
								<?php 
								if(empty($interval)){
									?>
									<select id="interval" name="interval">
										<option value=""></option>
										<option value="1x1 - 05.00-07.00, 11.00-12.00, 17.00-18.00, 21.00-23.00">
											1x1 - 05.00-07.00, 11.00-12.00, 17.00-18.00, 21.00-23.00
										</option>
										<option value="2x1 - 05.00 & 17.00 atau 07.00 & 19.00 atau 11.00 & 23.00">
											2x1 - 05.00 & 17.00 atau 07.00 & 19.00 atau 11.00 & 23.00
										</option>
										<option value="3x1 - 05.00-13.00-21.00 atau 06.00-14.00-22.00">
											3x1 - 05.00-13.00-21.00 atau 06.00-14.00-22.00
										</option>
										<option value="4x1 - 05.00-11.00-17.00-23.00 atau 06.00-12.00-18.00-00.00">
											4x1 - 05.00-11.00-17.00-23.00 atau 06.00-12.00-18.00-00.00
										</option>
										<option value="5x1 - 05.00-09.00-13.00-17.00-21.00 atau 06.00-10.00-14.00-18.00-22.00">
											5x1 - 05.00-09.00-13.00-17.00-21.00 atau 06.00-10.00-14.00-18.00-22.00
										</option>
										<option value="6x1 - 05.00-09.00-13.00-17.00-21.00-01.00 atau 06.00-10.00-14.00-18.00-22.00-02.00">
											6x1 - 05.00-09.00-13.00-17.00-21.00-01.00 atau 06.00-10.00-14.00-18.00-22.00-02.00
										</option>
									</select>
								<?php } else{ ?>
									<input class="" name="interval" value="<?php echo $interval;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
								<?php } ?>
							</div>
						</div>
					</td>
				</tr>

				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; Tanggal / Jam
							</div>
							<div class="col-8">
								: <input class="" name="tgl" value="<?php echo $tgl;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
					</td>
				</tr>


				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;
							</div>
							<div class="col-8">
								<br>
								<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
								<br><br>
							</div>
						</div>
					</td>
				</tr>	


			</table>


			<br>
		</form>
	</font>
</body>
</div>
</div>

<?php 

if (isset($_POST["simpan"])) {
	// echo "edit_data";
	$interval	= $_POST["interval"];
	$dosis	= $_POST["dosis"];

	if($idresep=='edit'){
		$q  = "update ERM_RI_RPO set interval='$interval',dosis='$dosis' where id=$idrpo_detail";
		$hs = sqlsrv_query($conn,$q);		
	}else{

		$nama_obat= $_POST["nama_obat"];
		$dosis= $_POST["dosis"];
		$interval= $_POST["interval"];
		$tgl= $_POST["tgl"];

		$q  = "insert into ERM_RI_RPO(noreg,userid,tglentry,tgl,nama_obat,dosis,interval,id_eresep,id_eresep_detail,id_rpo_header,nomor) 
		values ('$noreg','$user','$tgl','$tgl','$nama_obat','$dosis','$interval','0','0','$idrpo','$nomor')";
		$hs = sqlsrv_query($conn,$q);
	}

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	history.go(-1);
	</script>
	";


}


if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>