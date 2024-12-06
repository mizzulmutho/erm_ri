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
	<title>Lembar Transfusi</title>  
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
					<b>MONITORING PEMBERIAN TRANSFUSI DARAH</b><br>
				</div>
			</div>

			<br>

			<?php if (empty($edit)) { ?>
				<table width='100%' border='1'>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									&nbsp;&bull; Tanggal
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
									&nbsp;&bull; Jenis Darah
								</div>
								<div class="col-8">
									: <input class="" name="jenis_darah" value="<?php echo $jenis_darah;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan jenis_darah">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									&nbsp;&bull; Nomor Kantong
								</div>
								<div class="col-8">
									: <input class="" name="nomor_kantong" value="<?php echo $nomor_kantong;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan nomor_kantong">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									&nbsp;&bull; Isi
								</div>
								<div class="col-8">
									: <input class="" name="isi" value="<?php echo $isi;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan isi">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									&nbsp;&bull; Reaksi
								</div>
								<div class="col-8">
									: <input class="" name="reaksi" value="<?php echo $reaksi;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan reaksi">
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="row">
								<div class="col-4">
									&nbsp;&bull; TTV
								</div>
								<div class="col-8">
									: TD <input class="" name="td" value="<?php echo $td;?>" id="" type="text" size='5' onfocus="nextfield ='';" placeholder="td"> - 
									NADI <input class="" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='5' onfocus="nextfield ='';" placeholder="nadi"> - 
									RR <input class="" name="rr" value="<?php echo $rr;?>" id="" type="text" size='5' onfocus="nextfield ='';" placeholder="rr"> - 
									S <input class="" name="s" value="<?php echo $s;?>" id="" type="text" size='5' onfocus="nextfield ='';" placeholder="s">
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
									&nbsp;&nbsp;<input type='submit' name='simpan' value='tambah data' onfocus="nextfield ='done';" style="color: white;background: #66CDAA;border-color: #66CDAA;">
								</div>
							</div>
						</td>
					</tr>	
				</table>
			<?php } ?>


			<br>
			<table width="100%">
				<tr>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>no</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>tgl/jam</font></td>					
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>jenis darah</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>nomor kantong</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>isi</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>TD</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Nadi</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>RR</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>S</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>paraf & <br>nama perawat/bidan</font></td>
					<td style="border: 1px solid;" bgcolor='#708090'><font color='white'>Reaksi (-/+)</font></td>
					<td align='center' style="border: 1px solid;" bgcolor='#708090'><font color='white'>hapus</font></td>
				</tr>
				<?php 
				$q="
				select TOP(100) userid,nama_obat,jumlah,dosis,waktu_penggunaan,CONVERT(VARCHAR, tglentry, 25) as tglentry,id,
				interval, dokter, apoteker, periksa, pemberi, keluarga, jenis_darah, nomor_kantong, isi,
				td,nadi,rr,s,reaksi
				from ERM_RI_LTRANSFUSI
				where noreg='$noreg' order by id desc
				";
				$hasil  = sqlsrv_query($conn, $q);  
				$no=1;
				while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
					echo "
					<tr>
					<td>$no</td>
					<td>$data[tglentry]</td>
					<td>$data[jenis_darah]</td>
					<td>$data[nomor_kantong]</td>
					<td>$data[isi]</td>
					<td>$data[td]</td>
					<td>$data[nadi]</td>
					<td>$data[rr]</td>
					<td>$data[s]</td>
					<td>$data[userid]</td>
					<td>$data[reaksi]</td>
					<td align='center'><a href='del_ltranfusi.php?id=$id|$user|$data[id]'><font color='red'>[x]</font></a></td>
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
	$jenis_darah	= $_POST["jenis_darah"];
	$nomor_kantong	= $_POST["nomor_kantong"];
	$isi	= $_POST["isi"];

	$td	= $_POST["td"];
	$nadi	= $_POST["nadi"];
	$rr	= $_POST["rr"];
	$s	= $_POST["s"];
	$reaksi	= $_POST["reaksi"];

	$q  = "insert into ERM_RI_LTRANSFUSI(noreg,userid,tglentry,tgl,jenis_darah,nomor_kantong,isi,td,nadi,rr,s,reaksi) 
	values ('$noreg','$user','$tgl','$tgl','$jenis_darah','$nomor_kantong','$isi','$td','$nadi','$rr','$s','$reaksi')";
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