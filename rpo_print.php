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
$idresep = $row[2]; 
$edit = $row[2]; 

$rand = rand(1, 9);
// $rand2 = rand(1, 9);
$nomor = trim($rand.'-'.$tgl);

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qu="
SELECT        TOP (200) NORM,  ALERGI
FROM            Y_ALERGI 
where norm='$norm'

union 
SELECT        ARM_REGISTER.NORM, V_ERM_RI_KEADAAN_UMUM.alergi as ALERGI
FROM            V_ERM_RI_KEADAAN_UMUM INNER JOIN
ARM_REGISTER ON V_ERM_RI_KEADAAN_UMUM.noreg = ARM_REGISTER.NOREG
where ARM_REGISTER.NORM='$norm' and V_ERM_RI_KEADAAN_UMUM.alergi <> '' 

union
SELECT        ARM_REGISTER.NORM, ERM_RI_ALERGI.obat as ALERGI
FROM            ERM_RI_ALERGI INNER JOIN
ARM_REGISTER ON ERM_RI_ALERGI.noreg = ARM_REGISTER.NOREG

";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

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

//resep
$q="
SELECT        TOP (200) CONVERT(VARCHAR, W_EResep_R.TglEntry, 25) as tglentry , 
W_EResep_R.KodeR, AFarm_MstObat.NAMABARANG, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, W_EResep_R.WaktuPakai, W_EResep_R.Keterangan, 
W_EResep_R.Satuan, Afarm_MstSatuan.NAMASATUAN
FROM            W_EResep_R INNER JOIN
AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG INNER JOIN
Afarm_MstSatuan ON W_EResep_R.Satuan = Afarm_MstSatuan.KODESATUAN
WHERE        (W_EResep_R.IdResep = $idresep)
";
$hasil  = sqlsrv_query($conn, $q);  
$no=1;
while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

	$tgl = $data[tglentry];
	$nama_obat = $data[NAMABARANG];
	$dosis = $data[AturanPakai];
	$jumlah = $data[Jumlah];
	$waktu_penggunaan = $data[WaktuPakai];

	$q  = "insert into ERM_RI_RPO(noreg,userid,tglentry,tgl,nama_obat,dosis,jumlah,waktu_penggunaan) 
	values ('$noreg','$user','$tgl','$tgl','$nama_obat','$dosis','$jumlah','$waktu_penggunaan')";
	$hs = sqlsrv_query($conn,$q);

	$no += 1;

}

if($edit){
	$q3       = "select CONVERT(VARCHAR, tgl, 103) as tgl, nama_obat, jumlah, dosis, waktu_penggunaan,
	interval, dokter, apoteker, periksa, pemberi, keluarga
	from ERM_RI_RPO
	where id='$idresep'";
	$hasil3  = sqlsrv_query($conn, $q3);  
	$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
	$nama_obat = $data3[nama_obat];
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
	<title>RPO</title>  
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
					<input type='submit' name='print' value='print' class='btn btn-info'>
					<br>

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
						<div class="col-12">
							<font size="3"><?php echo 'ALERGI : '.$alergi; ?></font>									
						</div>

					</div>
					<hr>

					<div class="row">
						<div class="col-12 text-center">
							<b>REKAM PEMBERIAN OBAT</b><br>
						</div>
					</div>

					<br>


					<div class="row">

						<table width="100%" border='1'>
							<tr>
								<td align="center" style="border: 1px solid;" bgcolor='#708090'><font color='white'>NO</font></td>
								<td align="center" style="border: 1px solid;" bgcolor='#708090'><font color='white'>NAMA OBAT</font></td>		
								<td align="center" style="border: 1px solid;" bgcolor='#708090'><font color='white'>WAKTU PEMBERIAN</font></td>
							</tr>
							<?php 
							$q="
							select distinct nama_obat
							from ERM_RI_RPO
							where noreg='$noreg' order by nama_obat asc
							";
							$hasil  = sqlsrv_query($conn, $q);  
							$no=1;
							while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
								?>

								<tr>
									<td><?php echo $no; ?></td>
									<td>
										<?php 
										echo $data[nama_obat]; 
										$qo="SELECT top(1)interval,id_rpo_header FROM ERM_RI_RPO where noreg='$noreg' and nama_obat='$data[nama_obat]'";
										$hqo  = sqlsrv_query($conn, $qo);        
										$dhqo  = sqlsrv_fetch_array($hqo, SQLSRV_FETCH_ASSOC); 
										echo $aturanpakai = '<br>Aturan Pakai : '.trim($dhqo['interval']);
										$id_rpo_header = $dhqo['id_rpo_header'];
										?>
									</td>
									<td>									
										<?php 
										$q2="
										select TOP(100) userid,nama_obat,jumlah,
										CONVERT(VARCHAR, tglentry, 25) as tglentry,
										CONVERT(VARCHAR, tgl, 103) as tgl,
										CONVERT(VARCHAR, tgl, 8) as jam,
										id, id_rpo_header,
										dokter, apoteker, periksa, pemberi, keluarga
										from ERM_RI_RPO_BERI
										where noreg='$noreg' and nama_obat ='$data[nama_obat]' order by id desc
										";
										$hasil2  = sqlsrv_query($conn, $q2);  
										$no2=1;
										echo "<table width=100% border=1>";
										echo "
										<tr>
										<td width='3%' align='center' bgcolor='#708090'><font color='white'>no</font></td>
										<td width='3%' align='center' bgcolor='#708090'><font color='white'>jumlah</font></td>								
										<td width='5%' align='center' bgcolor='#708090'><font color='white'>tgl</font></td>
										<td width='5%' align='center' bgcolor='#708090'><font color='white'>jam</font></td>
										<td width='5%' align='center' bgcolor='#708090'><font color='white'>dokter</font></td>
										<td width='5%' align='center' bgcolor='#708090'><font color='white'>apoteker</font></td>
										<td width='17%' align='center' bgcolor='#708090'><font color='white'>periksa</font></td>
										<td width='18%' align='center' bgcolor='#708090'><font color='white'>pemberi</font></td>
										<td width='5%' align='center' bgcolor='#708090'><font color='white'>keluarga</font></td>
										</tr>";

										while   ($data2 = sqlsrv_fetch_array($hasil2,SQLSRV_FETCH_ASSOC)){
											if($data2[keluarga]){ 
										// $keluarga = substr($data2[keluarga], 0,10).'...';
												$keluarga = '&radic;';
											}else{
												$keluarga='';
											}

											if($data2[dokter]){ 
												$dokter = '&radic;';
											}else{
												$dokter='';
											}

											if($data2[apoteker]){ 
												$apoteker = '&radic;';
											}else{
												$apoteker='';
											}


											echo "
											<tr>
											<td align='center'>$no2</td>
											<td align='center'>$data2[jumlah]</td>
											<td align='center'>$data2[tgl]</td>
											<td align='center'>$data2[jam]</td>
											<td align='center'>$dokter</td>
											<td align='center'>$apoteker</td>
											<td align='center'>$data2[periksa]</td>
											<td align='center'>$data2[pemberi]</td>
											<td align='center'>$keluarga</td>
											</tr>
											";
											$no2 += 1;
										}
										echo "</table>";
										?>
									</td>
								</tr>

								<?php
								$no += 1;
							}

							?>
						</table>

					</div>
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

if (isset($_POST["new"])) {

	// $tgl	= $_POST["tgl"];
	// $nama_obat	= $_POST["nama_obat"];
	// $dosis	= $_POST["dosis"];
	// $waktu_penggunaan	= $_POST["waktu_penggunaan"];
	// $jumlah	= $_POST["jumlah"];

	$q  = "insert into ERM_RI_RPO_HEADER(noreg,userid,tglentry,tgl,nomor) 
	values ('$noreg','$user','$tgl','$tgl','$nomor')";
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

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";


}

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>