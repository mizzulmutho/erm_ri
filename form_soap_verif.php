<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$verif = $row[2]; 

$noreg = $row[3]; 
$tanggal = $row[4]; 
$userid = $row[5]; 
$kodedokter = $row[6]; 
$namadokter = $row[7]; 

$userverif = $kodedokter.'-'.$namadokter;

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];

//ambil resep
?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
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
			$("#unit").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_unit.php?id=$sbu', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.kodeunit + ' - ' + item.nama
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
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
			<br>
			<div class="card">
				<br />
				Verifikasi Data SOAP
				<br>
				<hr>
				<input class="form-control" name="kodedokter" value="<?php echo $kodedokter;?>" id="dokter" type="text" size='50' onfocus="nextfield ='pass';" placeholder="Isikan Nama Dokter atau Perawat">
				<br>
				<input class="form-control" name="pass" value="<?php echo $pass;?>" id="" type="text" size='50' onfocus="nextfield ='kodeunit';" placeholder="Konfirmasi Password !!!">
				<br>
<!-- 				<input class="form-control" name="kodeunit" value="<?php echo $kodeunit;?>" id="unit" type="text" size='50' onfocus="nextfield ='cari';" placeholder="Isikan kode Unit">
-->				<br>
<button type="submit" name="cari" class="btn btn-success" onfocus="nextfield ='done';">cari</button> 
<br>

</form>
</body>
</div>

<?php

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
	$eror='Kodedokter Tidak Boleh Kosong !!!';
	$lanjut='T';
}


if (isset($_POST["cari"])) {

	$query = "select * from ROLERSPGENTRY.dbo.TBLuser where user1 like '%$kodedokter%'";		
	$result = sqlsrv_query($conn, $query);
	$data  = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);          

    // cek pass
	if (trim($pass)<>strtolower(trim($data['PASS2']))) {	
		if (trim($pass)<>strtoupper(trim($data['PASS2']))) {	
			$eror='Password Salah !!!';

			echo "
			<script>
			alert('".$eror."');
			history.go(-1);
			</script>
			";

		}
	}else{

		$ql="SELECT TOP(100)*,CONVERT(VARCHAR, tanggal, 101) as tgl2,CONVERT(VARCHAR, tanggal, 20) as tgl3,CONVERT(VARCHAR, tanggal, 23) as tgl3  
		FROM ERM_SOAP WHERE dpjp='$kodedokter' and kodeunit='$kodeunit'
		ORDER BY id desc";
		$hl  = sqlsrv_query($conn, $ql);
		$no=1;

		echo "
		<table border='1'>
		<tr bgcolor='#969392'>
		<td>no</td><td>noreg</td><td>tanggal</td><td>user</td><td>sbu</td><td>unit</td><td>subjektif</td><td>objektif</td><td>assesment</td><td>plant</td><td>tglentry</td><td>verifikasi</td><td>action</td>
		</tr>";

		while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){    
			$kodedokter = trim($dl[dpjp]);

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

			echo "	<tr>
			<td>$no</td>
			<td>$dl[noreg]</td>
			<td>$dl[tgl2]</td>
			<td>$kodedokter - $namadokter</td>
			<td>$dl[sbu]</td>
			<td>$dl[kodeunit]</td>
			<td>$dl[subjektif]</td>
			<td>$dl[objektif]</td>
			<td>$dl[assesment]</td>
			<td>$dl[planning]</td>
			<td>$dl[tgl3]</td>
			<td></td>
			<td><a href='form_soap_verif.php?id=$id|$user|verif|$noreg|$dl[tgl3]|$dl[userid]|$kodedokter|$namadokter'>verif dokter</a></td>
			</tr>
			";
			$no += 1;
		}

		echo "</table>";

	}


}

if($verif){

	$q  = "insert into ERM_SOAP_VERIF
	(
	noreg,
	tanggal,
	userid,
	tglverif,
	userverif
	) 
	values 
	('$noreg','$tgl','$userid','$tgl','$userverif')";
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

