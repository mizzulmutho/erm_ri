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
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
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
					<b>ASSESMEN LUKA</b><br>
				</div>
			</div>

			<br>

			<table width='100%' border='1'>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp; Tanggal
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
								&nbsp; Tipe Luka
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="luka1" value="<?php echo $luka1;?>" id="" type="text" size='50' onfocus="nextfield ='';" > -->
								<select name='luka1' style="min-width:330px; min-height:30px;">
									<option value=''>--pilih--</option>
									<option value='Operasi'>Operasi</option>
									<option value='Trauma'>Trauma</option>
									<option value='Bakar'>Bakar</option>
									<option value='Ulkus/Gangren DM'>Ulkus/Gangren DM</option>
									<option value='Luka tekan/Dekubitus'>Luka tekan/Dekubitus</option>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp; Luas Luka
							</div>
							<div class="col-8">
								: <input class="" name="luka2" value="<?php echo $luka2;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp; Balutan Luka
							</div>
							<div class="col-8">
								: <input class="" name="luka3" value="<?php echo $luka3;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp; Lokasi Luka
							</div>
							<div class="col-8">
								: <input class="" name="luka4" value="<?php echo $luka4;?>" id="" type="text" size='50' onfocus="nextfield ='';" >
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; EKSUDAT/ CAIRAN LUKA
							</div>
							<div class="col-8">
								: tipe &nbsp;&nbsp;&nbsp;&nbsp;
								<!-- <input class="" name="luka5" value="<?php echo $luka5;?>" id="" type="text" size='20' onfocus="nextfield ='';" > -->
								<select name='luka5' style="min-width:20px; min-height:30px;">
									<option value=''>--pilih--</option>
									<option value='Serous/jernih'>Serous/jernih</option>
									<option value='Bloody/merah'>Bloody/merah</option>
									<option value='Sanguineous/darah dan kental'>Sanguineous/darah dan kental</option>
									<option value='Serosanguineous/merah pucat/merah muda'>Serosanguineous/merah pucat/merah muda</option>
									<option value='Purulent/nanah/pus warna kuning'>Purulent/nanah/pus warna kuning</option>
									<option value='Foul Purulent/nanah/pus warna hijau'>Foul Purulent/nanah/pus warna hijau</option>
								</select>
								<br>
								: jumlah 
								<!-- <input class="" name="luka6" value="<?php echo $luka6;?>" id="" type="text" size='12' onfocus="nextfield ='';" > -->
								<select name='luka6' style="min-width:20px; min-height:30px;">
									<option value=''>--pilih--</option>
									<option value='Tidak ada'>Tidak ada</option>
									<option value='Sedikit/ < 25% dari balutan '>Sedikit/ < 25% dari balutan </option>
									<option value='Sedang/ 25% dari balutan'>Sedang/ 25% dari balutan</option>
									<option value='Banyak/ 25-75% dari balutan'>Banyak/ 25-75% dari balutan</option>
									<option value='Sangat Banyak/> 75% balutan sampai keluar'>Sangat Banyak/> 75% balutan sampai keluar</option>
									<option value='Infeksi kritis'>Infeksi kritis</option>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; OUDOR/ BAU
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="luka7" value="<?php echo $luka7;?>" id="" type="text" size='50' onfocus="nextfield ='';" > -->
								<select name='luka7' style="min-width:330px; min-height:30px;">
									<option value=''>--pilih--</option>
									<option value='Tidak ada bau'>Tidak ada bau</option>
									<option value='Tercium saat membuka balutan'>Tercium saat membuka balutan</option>
									<option value='Tercium jarak satu tangan dari pasien'>Tercium jarak satu tangan dari pasien</option>
									<option value='Tercium saat petugas masuk kamar pasien'>Tercium saat petugas masuk kamar pasien</option>
									<option value='Tercium saat petugas masuk beberapa kamar pasien'>Tercium saat petugas masuk beberapa kamar pasien</option>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; WARNA DASAR LUKA
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="luka1" value="<?php echo $luka1;?>" id="" type="text" size='50' onfocus="nextfield ='';" > -->
								<select name='luka8' style="min-width:330px; min-height:30px;">
									<option value=''>--pilih--</option>
									<option value='Merah'>Merah</option>
									<option value='Kuning'>Kuning</option>
									<option value='Hitam'>Hitam</option>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; TIPE LUKA
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="luka9" value="<?php echo $luka9;?>" id="" type="text" size='50' onfocus="nextfield ='';" > -->
								<select name='luka9' style="min-width:330px; min-height:30px;">
									<option value=''>--pilih--</option>
									<option value='Bersih'>Bersih</option>
									<option value='Bersih Terkontaminasi'>Bersih Terkontaminasi</option>
									<option value='Terkontaminasi'>Terkontaminasi</option>
									<option value='Kotor'>Kotor</option>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="row">
							<div class="col-4">
								&nbsp;&bull; KULIT SEKITAR LUKA
							</div>
							<div class="col-8">
								: 
								<!-- <input class="" name="luka10" value="<?php echo $luka10;?>" id="" type="text" size='50' onfocus="nextfield ='';" > -->
								<select name='luka10' style="min-width:330px; min-height:30px;">
									<option value=''>--pilih--</option>
									<option value='Utuh'>Utuh</option>
									<option value='Bengkak'>Bengkak</option>
									<option value='Kemerahan'>Kemerahan</option>
									<option value='Nyeri'>Nyeri</option>
									<option value='Keras'>Keras</option>
									<option value='Sianosis atau pucat'>Sianosis atau pucat</option>
								</select>

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
								&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' onfocus="nextfield ='done';" style="color: white;background: #66CDAA;border-color: #66CDAA;">
							</div>
						</div>
					</td>
				</tr>	
			</table>
			<br>
			<table width="100%">
				<tr>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>tgl & jam</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Tipe Luka</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Luas Luka</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Balutan Luka</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Lokasi Luka</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>EKSUDAT/ CAIRAN LUKA - tipe</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>jumlah</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>OUDOR/ BAU</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>WARNA DASAR LUKA</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>TIPE LUKA</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>KULIT SEKITAR LUKA</font></td>
					<td style="border: 1px solid;" bgcolor='#708090' align='center'><font color='white'>petugas</font></td>
					<td align='center' style="border: 1px solid;" bgcolor='#708090'><font color='white'>aksi</font></td>
				</tr>
				<?php 
				$q="
				select TOP(100) *,CONVERT(VARCHAR, tglentry, 25) as tglentry
				from ERM_RI_RAWATLUKA
				where noreg='$noreg' order by id desc
				";
				$hasil  = sqlsrv_query($conn, $q);  
				$no=1;
				while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
					echo "
					<tr>
					<td>$no</td>
					<td>$data[tglentry]</td>
					<td>$data[luka1]</td>
					<td>$data[luka2]</td>
					<td>$data[luka3]</td>
					<td>$data[luka4]</td>
					<td>$data[luka5]</td>
					<td>$data[luka6]</td>
					<td>$data[luka7]</td>
					<td>$data[luka8]</td>
					<td>$data[luka9]</td>
					<td>$data[luka10]</td>

					<td>$data[userid] - $nama</td>
					<td align='center'><a href='del_luka.php?id=$id|$user|$data[id]'><font color='red'>[x]</font></a></td>
					</tr>
					";
					$no += 1;

				}


				?>
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

	$tgl	= $_POST["tgl"];
	$luka1	= $_POST["luka1"];
	$luka2	= $_POST["luka2"];
	$luka3	= $_POST["luka3"];
	$luka4	= $_POST["luka4"];
	$luka5	= $_POST["luka5"];
	$luka6	= $_POST["luka6"];
	$luka7	= $_POST["luka7"];
	$luka8	= $_POST["luka8"];
	$luka9	= $_POST["luka9"];
	$luka10	= $_POST["luka10"];

	$q  = "insert into ERM_RI_RAWATLUKA(noreg,userid,tglentry,tgl,luka1,luka2,luka3,luka4,luka5,luka6,luka7,luka8,luka9,luka10) 
	values ('$noreg','$user','$tgl','$tgl','$luka1','$luka2','$luka3','$luka4','$luka5','$luka6','$luka7','$luka8','$luka9','$luka10')";
	$hs = sqlsrv_query($conn,$q);

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