<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = trim($row[1]); 

$qu="SELECT noreg,norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];

$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  

$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
$kodedept	= $data2[kodedept];

$nama	= $data2[nama];
$kelamin	= $data2[kelamin];
$nik	= trim($data2[nik]);
$alamatpasien	= $data2[alamatpasien];
$kota	= $data2[kota];
$kodekel	= $data2[kodekel];
$telp	= $data2[tlp];
$tmptlahir	= $data2[tmptlahir];
$tgllahir	= $data2[tgllahir];
$jenispekerjaan	= $data2[jenispekerjaan];
$jabatan	= $data2[jabatan];
$umur =  $data2[UMUR];


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

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

<div class="container">

	<body onload="document.myForm.kodedokter.focus();">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<br>
				<br>
				<div class="row">
					<div class="col-6"><b>RUMAH SAKIT PETROKIMIA GRESIK</b><br><b>CATATAN PERKEMBANGAN PASIEN TERINTEGRASI</b><br>
						<b><u>OPERAN SHIFT</u></b>
					</div>
					<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
				</div>
				<div class="row">
					<div class="col-12">
						<input class="form-control" name="kodedokter" value="<?php echo $kodedokter;?>" id="dokter" type="text" size='50' onfocus="nextfield ='pass';" placeholder="Isikan Nama atau Kode Perawat">
						<input class="form-control" name="pass" value="<?php echo $pass;?>" id="" type="text" size='50' onfocus="nextfield ='verif';" placeholder="Konfirmasi Password !!!">
						<button type="submit" name="verif" class="btn btn-success" onfocus="nextfield ='done';">verif</button> 
					</div>
				</div>

				<hr>
				<table border="1" cellpadding="5px">
					<?php
					$ql="SELECT TOP(100)*,CONVERT(VARCHAR, tanggal, 101) as tgl2,CONVERT(VARCHAR, tglentry, 8) as tgl3  
					FROM ERM_SOAP WHERE norm='$norm' and userid='$user' ORDER BY id desc";
					$hl  = sqlsrv_query($conn, $ql);
					$no=1;
					echo 
					"<tr bgcolor='#969392'>
					<td>no</td>
					<td>tanggal-jam-shift</td>
					<td>profesi pemberi asuhan</td>
					<td>hasil assesment</td>
					<td>instruksi PPA</td>
					<td>verif DPJP</td>
					<td>user pemberi</td>
					<td>user penerima</td>
					</tr>";
					while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){    
						$kodedokter = trim($dl[kodedokter]);
						$noreg = trim($dl[noreg]);
						$userid = trim($dl[userid]);
						$dpjp = trim($dl[dpjp]);
						$periode = trim($dl[tgl2]);

						$q2		= "select nama from afarm_dokter where kodedokter='$kodedokter'";
						$hasil2  = sqlsrv_query($conn, $q2);			  					
						$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
						$namadokter	= $data2[nama];

						if(empty($namadokter)){
							$q2		= "select nama from afarm_paramedis where kode='$kodedokter'";
							$hasil2  = sqlsrv_query($conn, $q2);			  					
							$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
							$namadokter	= $data2[nama];
						}

						//cek verif dokter...
						$q3		= "select userverif,useroper,CONVERT(VARCHAR, tgloper, 20) as tgloper2 from  ERM_SOAP_VERIF where noreg='$noreg' and userid='$userid'  and (CONVERT(DATETIME, CONVERT(VARCHAR, tanggal, 101), 101) BETWEEN '$periode' AND '$periode') and useroper is not null";
						$hasil3  = sqlsrv_query($conn, $q3);			  					
						$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
						$userverif	= $data3[userverif];
						$useroper	= $data3[useroper];
						$tgloper2	= $data3[tgloper2];

						$hasilassesment = "
						<b>Subject :</b> $dl[subjektif]<br>
						<b>Object :</b> $dl[objektif]<br>
						<b>Vital Sign :</b> $dl[vital]<br>
						<b>Assesment :</b> $dl[assesment]<br>
						<b>Plant :</b> $dl[planning]
						";

						echo "	<tr>
						<td>$no</td>
						<td>$dl[noreg]<br>$dl[tgl2]<br>$dl[tgl3]</td>
						<td>$kodedokter - $namadokter<br>$dl[kodeunit] - $dl[sbu]</td>
						<td>$hasilassesment</td>
						<td>$dl[instruksi]</td>
						<td>$userverif</td>
						<td>$dl[userid]</td>						
						<td>$useroper<br>Tgl Terima Operan : $tgloper2</td>
						</tr>
						";
						$no += 1;
					}
					?>
				</table>
			</font>
		</form>
	</font>
</body>
</div>

<?php

if (isset($_POST["verif"])) {

	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);

	$pass = trim($_POST["pass"]);
	$subjektif = trim($_POST["subjektif"]);
	$objektif = trim($_POST["objektif"]);
	$assesment = trim($_POST["assesment"]);
	$planning = trim($_POST["planning"]);

	$lanjut="Y";


	if(empty($pass)){
		$eror='Password Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($kodedokter)){
		$eror='Kodeperawat Tidak Boleh Kosong !!!';
		$lanjut='T';
	}


	if($lanjut=='Y'){

		$q  = "insert into ERM_SOAP_VERIF
		(
		noreg,
		tanggal,
		userid,
		tgloper,
		useroper
		) 
		values 
		('$noreg','$tgl','$userid','$tgl','$kodedokter')";
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
	}else{
		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";
	}
}
