<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$aresep = $row[2]; 

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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
			$("#dpjp").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dpjp.php', //your                         
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

<div class="container-fluid">

	<body onload="document.myForm.kodedokter.focus();">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<br>
			<div class="row">
				<br />
				<i class="bi bi-window-plus" style="font-size: 30px;"></i>&nbsp;&nbsp;&nbsp;
				<b>Input Data CPPT</b>
				<br>
				<hr>
				<div class="col-12">
					<input class="form-control" name="kodedokter" value="<?php echo $kodedokter;?>" id="dokter" type="text" size='50' onfocus="nextfield ='pass';" placeholder="Isikan Nama Dokter atau Perawat">
					<input class="form-control" name="pass" value="<?php echo $pass;?>" id="" type="text" size='50' onfocus="nextfield ='subjektif';" placeholder="Konfirmasi Password !!!">
				</div>
				<div class="col-6">
					Subjektif : 
					<textarea class="form-control" name="subjektif" cols="100%" onfocus="nextfield ='objektif';">
						<?php echo $subjektif;?>						
					</textarea>
				</div>
				<div class="col-6">
					Objektif : 
					<textarea class="form-control" name="objektif" cols="100%" onfocus="nextfield ='assesment';">
						<?php echo $objektif;?>
					</textarea>				
				</div>
				<div class="col-6">
					Assesment : 
					<textarea class="form-control" name="assesment" cols="100%" onfocus="nextfield ='planning';">
						<?php echo $assesment;?>
					</textarea>								
				</div>
				<div class="col-6">
					Planning : 
					<textarea class="form-control" name="planning" cols="100%" onfocus="nextfield ='penunjang';">
						<?php echo $objektif;?>
					</textarea>
				</div>
				<div class="col-6">
					Penunjang : 			
					<textarea class="form-control" name="penunjang" cols="100%" onfocus="nextfield ='Instruksi';">
						<?php echo $penunjang;?>
					</textarea>																
				</div>
				<div class="col-6">
					Instruksi : 			
					<textarea class="form-control" name="instruksi" cols="100%" onfocus="nextfield ='dpjp';">
						<?php echo $instruksi;?>
					</textarea>
				</div>			
				<div class="col-12">
					DPJP:
					<input class="form-control" name="dpjp" value="<?php echo $dpjp;?>" id="dpjp" type="text" size='50' onfocus="nextfield ='simpan';" placeholder="Isikan Nama Dokter DPJP">
				</div>			

			</div>
			<br>
			<div class="row">
				<div class="col-3">
					<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';">simpan</button> 
					<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>close</a>
				</div>
			</div>
			<br>

		</form>
	</body>
</div>

<?php

if (isset($_POST["simpan"])) {
	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);

	$pass = trim($_POST["pass"]);
	$subjektif = trim($_POST["subjektif"]);
	$objektif = trim($_POST["objektif"]);
	$assesment = trim($_POST["assesment"]);
	$planning = trim($_POST["planning"]);
	$instruksi = trim($_POST["instruksi"]);

	$dpjp = trim($_POST["dpjp"]);
	$row2 = explode('-',$dpjp);
	$dpjp  = trim($row2[0]);


	$lanjut="Y";


	if(empty($pass)){
		$eror='Password Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($kodedokter)){
		$eror='Kodedokter Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($dpjp)){
		$eror='DPJP Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	// if(empty($objektif)){
	// 	$eror='objektif Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	// if(empty($assesment)){
	// 	$eror='assesment Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	// if(empty($planning)){
	// 	$eror='planning Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	//cek user
	$query = "select * from ROLERSPGENTRY.dbo.TBLuser where user1 = '$user'";		
	$result = sqlsrv_query($conn, $query);
	$data  = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);          

    // cek pass
	if (trim($pass)<>strtolower(trim($data['PASS2']))) {	
		if (trim($pass)<>strtoupper(trim($data['PASS2']))) {	
			$eror='Password Salah !!!';
			$lanjut = 'T';
		}
	}

	if($lanjut == 'Y'){
		$q  = "insert into ERM_SOAP
		(norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry,instruksi,dpjp) 
		values 
		('$norm','$noreg','$tgl','$sbu','$kodeunit','$kodedokter','$subjektif','$objektif','$assesment','$planning','$user','$tgl','$instruksi','$dpjp')";
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

		// echo "
		// <script>
		// top.location='index.php?id='$id|$user';
		// </script>
		// ";            

	}else{
		echo "
		<script>
		alert('".$eror."');
		history.go(-1);
		</script>
		";

	}

}


?>

