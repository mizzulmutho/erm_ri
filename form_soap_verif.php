<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$tgl1 = $_POST ['tgl1'];
if(empty($tgl1)){
	$tgl1=gmdate("Y-m-d", time()+60*60*7);
}



$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$verif = $row[2]; 


$noreg = $row[3]; 
$noreg2 = $noreg; 

$tanggal = $row[4]; 
$userid = $row[5]; 
$kodedokter = $row[6]; 
$namadokter = $row[7]; 

if (isset($_POST["tampil"])) {
	$tgl1 = $_POST ['tgl1'];
	$bulan  =intval(substr($tgl1,5,2));
	$hari =intval(substr($tgl1,8,3));
	$tahun  =intval(substr($tgl1,0,4));



}else{

	if($verif=='verif'){
		if(empty($verif)){
			$bulan  =intval(substr($tgl,5,2));
			$tahun  =intval(substr($tgl,0,4));
			$hari =intval(substr($tgl,8,3));
		}else{
			$tgl1 = $row[6];
			$hari = $row[3]; 
			$bulan = $row[4]; 
			$tahun = $row[5]; 
		}
	}else{
		$tgl1 = $row[2];
	}
}


$kodedokter = substr($user, 0,3);
$qu2="SELECT NAMA FROM AFARM_DOKTER where KODEDOKTER='$kodedokter'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$nama_dokter = trim($d1u2['NAMA']);

$userverif = $kodedokter.' - '.$nama_dokter;

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

<div id="content"> 
	<div class="container">

		<body onload="document.myForm.kodedokter.focus();">
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<a href='form_soap_verif.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				Pilih Tanggal :
				<input name="tgl1" type="date" size="15" value="<?php echo $tgl1; ?>" style="width:150px;height:40px">
				&nbsp;&nbsp;
				<button type='submit' name='tampil' value='cari' type="button" class='btn btn-primary'><i class="bi bi-search"></i>
				</button>	
				&nbsp;&nbsp;
				<a href='form_soap_verif.php?id=<?php echo $id.'|'.$user.'|verif|'.$hari.'|'.$bulan.'|'.$tahun.'|'.$tgl1 ?>' class='btn btn-info'><i class="bi bi-arrow-clockwise"></i>Verifikasi Semua !!!</a>			

				<br><br>
				<center><h5>Verifikasi Data SOAP</h5></center>
				<div class="card">
				</form>
				<?php

				$lanjut="Y";

				$ql="
				SELECT        TOP (100)*,CONVERT(VARCHAR, tglupdate, 101) as tgl2,CONVERT(VARCHAR, tglupdate, 20) as tgl3  
				FROM            ERM_RI_SOAP INNER JOIN
				ERM_SOAP ON ERM_RI_SOAP.noreg = ERM_SOAP.noreg
				WHERE  year(ERM_RI_SOAP.tglupdate)='$tahun' and month(ERM_RI_SOAP.tglupdate)='$bulan' and   day(ERM_RI_SOAP.tglupdate)='$hari' and  (ERM_SOAP.dpjp = '$kodedokter') AND (ERM_RI_SOAP.noreg NOT IN
					(SELECT        noreg
						FROM            ERM_SOAP_VERIF))
				ORDER BY ERM_RI_SOAP.NOREG DESC
				";
				$hl  = sqlsrv_query($conn, $ql);
				$no=1;

				echo "<font size='2px'>";
				echo "
				<table border='1' class='table-striped'>
				<tr>
				<td>no</td>
				<td>pasien a/n</td>
				<td>subjektif</td><td>objektif</td><td>assesment</td><td>plant</td>
				</tr>";

				while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){    
					$kodedokter = trim($dl[dpjp]);
					$norm = trim($dl[norm]);
					$userid = trim($dl[userid]);

					$q3		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
					jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
					$hasil3  = sqlsrv_query($conn, $q3);			  
					$data3	= sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);				  
					$namapasien	= $data3[nama];

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
					<td>
					$dl[noreg]<br>
					$dl[tgl3]<br>
					$namapasien<br>
					User Input :<br>
					$userid
					<br>
					</td>
					<td>$dl[subjektif]</td>
					<td>$dl[objektif]</td>
					<td>$dl[assesment]</td>
					<td></td>
					</tr>
					";
					$no += 1;
				}

				echo "</table>";
				echo "</font>";

				if($verif=='verif'){

					$qw="
					SELECT        TOP (100)*,CONVERT(VARCHAR, tglupdate, 20) as tgl2,CONVERT(VARCHAR, tglupdate, 20) as tgl3  
					FROM            ERM_RI_SOAP INNER JOIN
					ERM_SOAP ON ERM_RI_SOAP.noreg = ERM_SOAP.noreg
					WHERE  year(ERM_RI_SOAP.tglupdate)='$tahun' and month(ERM_RI_SOAP.tglupdate)='$bulan' and  day(ERM_RI_SOAP.tglupdate)='$hari' and    (ERM_SOAP.dpjp = '$kodedokter') AND (ERM_RI_SOAP.noreg NOT IN
						(SELECT        noreg
							FROM            ERM_SOAP_VERIF))
					ORDER BY ERM_RI_SOAP.NOREG DESC
					";
					$hw  = sqlsrv_query($conn, $qw);

					while   ($dw = sqlsrv_fetch_array($hw, SQLSRV_FETCH_ASSOC)){   

						$noreg2 = $dw[noreg];
						$userid = $dw[userid];
						$tgl2 = $dw[tgl2];

						$qu3="SELECT noreg FROM ERM_SOAP_VERIF where noreg='$noreg2'";
						$h1u3 = sqlsrv_query($conn, $qu3);        
						$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
						$noreg3 = $d1u3['noreg'];

						if(empty($noreg3)){
							$q  = "insert into ERM_SOAP_VERIF
							(
							noreg,
							tanggal,
							userid,
							tglverif,
							userverif
							) 
							values 
							('$noreg2','$tgl2','$userid','$tgl','$userverif')";
						}
						$hs = sqlsrv_query($conn,$q);
						$eror = "Success";

					}
					// end while...



					// if($hs){
					// 	$eror = "Success";
					// }else{
					// 	$eror = "Gagal Insert";

					// }

					echo "
					<script>
					alert('".$eror."');
					window.location.replace('form_soap_verif.php?id=$id|$user|$tgl1');
					</script>
					";


				}


				?>

			</body>

		</div>
	</div>