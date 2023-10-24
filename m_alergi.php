<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

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
$norm = trim($d1u['norm']);
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

$nama	= $data2[nama];
$alamat	= $data2[alamatpasien];
$tgllahir	= $data2[tgllahir];


$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];

$qu="SELECT ALERGI  FROM Y_alergi where norm='$norm'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];
$alergi1 = $d1u['ALERGI'];


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
</head> 

<div class="container">

	<body onload="document.myForm.alergi.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<div class="row">
					<div class="col-12">
						<i class="bi bi-window-plus"> &nbsp; <b>INPUT DATA OBSERVASI RAWAT INAP HARIAN</b></i>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">No. RM</label>
						<input class="" name="norm" value="<?php echo $norm;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>
					<div class="col-6">
						<label for="" class="col-sm-3">Tgl Lahir</label>
						<input class="" name="tgllahir" value="<?php echo $tgllahir;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>				
					<div class="col-6">
						<label for="" class="col-sm-3">Nama Pasien</label>
						<input class="" name="nama" value="<?php echo $nama;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>				
					<div class="col-6">
						<label for="" class="col-sm-3">Alamat</label>					
						<input class="" name="alamat" value="<?php echo $alamat;?>" id="" type="text" size='50' onfocus="nextfield ='';" placeholder="">
					</div>				
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">Tgl Input</label>
						<input class="" name="tglinput" value="<?php echo $tglinput;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>
				</div>

				<hr>
				Ket : Pengisian tanda , diganti dengan tanda .<br><br>

				<div class="row">
					<div class="col-2">
						<label for="" class="col-sm-2">Alergi</label>
					</div>					
					<div class="col-10">
						<input class="form-control" name="alergi" value="<?php echo $alergi;?>" id="" type="text" onfocus="nextfield ='simpan';" placeholder="">
					</div>										
				</div>

				<div class="row">
					<div class="col-4">
						&nbsp;
					</div>
					<div class="col-6 align-self-center">
						<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'>Close</a>
						&nbsp;&nbsp;&nbsp;
						<button type="submit" name="simpan" class="btn btn-danger btn-sm" onfocus="nextfield ='done';">simpan</button> 
					</div>
				</div>
			</div>
			<br><br><br>
		</form>
	</font>
</body>
</div>

<?php

if (isset($_POST["simpan"])) {

	$alergi	= trim($_POST["alergi"]);

	$lanjut="Y";


	if(empty($alergi)){
		$eror='Alergi Tidak Boleh Kosong !!!';
		$lanjut='T';
	}


	if($lanjut == 'Y'){

		if(empty($alergi1)){
			$q  = "insert into Y_ALERGI
			(norm,urut,alergi) 
			values 
			('$norm','1','$alergi')";
			$hs = sqlsrv_query($conn,$q);

		}else{
			$q  = "update Y_ALERGI set alergi = '$alergi' where norm='$norm'";
			$hs = sqlsrv_query($conn,$q);

		}

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

