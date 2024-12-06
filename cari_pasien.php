<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

// $noreg = 'R202312290003';
// $user = 'nino';
$tgl = gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);

$user = trim($row[0]); 
$noreg = trim($row[1]); 

$qu="SELECT norm,id FROM ERM_ASSESMEN_HEADER where noreg='$noreg'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$id = trim($d1u['id']);

$kodedokter = substr($user, 0,3);

$qu2="SELECT NAMA FROM AFARM_DOKTER where KODEDOKTER='$kodedokter'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$nama_dokter = trim($d1u2['NAMA']);


$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>SIRS - Sistem Informasi Rumah Sakit</title>  
	<link rel="icon" href="favicon.ico">  
	<!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="app/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bootstrap 4 -->
	<link rel="stylesheet" href="app/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="app/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="app/plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="app/dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="app/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="app/plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="app/plugins/summernote/summernote-bs4.min.css">


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

<body onload="document.myForm.td_sistolik.focus();">
	<form method="POST" name='myForm' action="" enctype="multipart/form-data">

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header"><i class="bi bi-window-plus" style="font-size: 30px;"></i>
							&nbsp;&nbsp;&nbsp;<b>FORM E-RM RAWAT INAP</b>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">

				<?php 
				if (empty($norm)){
					?>
					<!-- <input class="form-control" name="kodedokter" value="<?php echo $kodedokter;?>" id="dokter" type="text" size='50' onfocus="nextfield ='periode';" placeholder="Isikan Nama Dokter atau Kode Dokter" required> -->

					<?php 
					if($role=='DOKTER'){
						$kodedokter = substr($user,0,3).' - '.$nama_dokter;
						echo "<input class='form-control' name='kodedokter' value='$kodedokter' id='dokter' type='text' size='50' placeholder='Isikan Nama Dokter atau Kode Dokter' required>";
					}else{
						echo "<input class='form-control' name='kodedokter' value='$kodedokter' id='dokter' type='text' size='50' placeholder='Isikan Nama Dokter atau Kode Dokter' required>";
					}
					?>
					<button type="submit" name="create_assesmen" class="btn btn-success btn-sm" onfocus="nextfield ='done';">Buat Assesmen Baru</button>
					<?php
				}else{

					// echo "
					// <script>
					// top.location='index.php?id='$id|$user';
					// header('Location: index.php?id=$id|$user');
					// </script>
					// ";



					if($role=='DOKTER'){
						header("Location: index.php?id=$id|$user");
					}else{
						header("Location: index.php?id=$id|$user");						
					}



				}
				?>

			</div>
		</div>
	</form>
</body>

<?php

if (isset($_POST["create_assesmen"])) {

	$lanjut = 'Y';
	$kodedokter = $_POST["kodedokter"];
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);

	if(empty($kodedokter)){
		$eror='DOKTER TIDAK BOLEH KOSONG';
		$lanjut='T';
	}

	if($lanjut=="Y"){
		$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
		FROM            ARM_REGISTER INNER JOIN
		Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
		WHERE        (ARM_REGISTER.NOREG = '$noreg')";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$KODEUNIT = $d1u['KODEUNIT'];
		$KET1 = $d1u['KET1'];
		$NORM = $d1u['NORM'];

		$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where noreg='$noreg'";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$norm = trim($d1u['norm']);
		$id = trim($d1u['id']);

		if(empty($id)){
			$q  = "insert  ERM_ASSESMEN_HEADER
			( noreg, norm, sbu, kodeunit, kodedokter, tanggal, tglentry, userid) 
			values 
			('$noreg','$NORM','$KET1','$KODEUNIT','$kodedokter','$tgl','$tglinput','$user')";         
			$hs = sqlsrv_query($conn,$q);

			$qu="SELECT norm,id FROM ERM_ASSESMEN_HEADER where noreg='$noreg'";
			$h1u  = sqlsrv_query($conn, $qu);        
			$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
			$id = trim($d1u['id']);
		}
		
		if($hs){

			header("Location: index.php?id=$id|$user");


		}else{

		}

	}else{

		echo "
		<div class='alert alert-danger' role='alert'>
		".$eror."
		</div>
		";
	}
}


?>