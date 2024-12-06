<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
// $transfer = $row[2]; 


$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];


$ql1="SELECT  id,rencana,userid from ERM_ASUHAN_KEPERAWATAN where id_assesmen='$id' order by id desc";
$hl1  = sqlsrv_query($conn, $ql1);
$d11  = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC); 
$rencana = $d11['rencana'];
$rencana = html_entity_decode($rencana);

$userid = $d11['userid'];
$idrasuhan = $d11['id'];


if (isset($_POST["implementasi_rencana"])) {
	$idrasuhan = trim($_POST["idrasuhan"]);

	echo "
	<script>
	top.location='i_asuhankeperawatan.php?id=$id|$user|$idrasuhan';
	</script>
	";            
}


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

</head> 

<div class="row">
	<div class="col-12">
		<?php 
		include "header_soap.php";
		?>
	</div>
</div>

<div class="container-fluid">

	<body onload="document.myForm.pasien_mcu.focus();">
		<font size='2px'>	



			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='r_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<a href='form_asuhankeperawatan_baru.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'><i class="bi bi-file-earmark-plus"></i>&nbsp;tambah</a>
				&nbsp;&nbsp;
<!-- 				<a href='askep.php?id=<?php echo $id.'|'.$user.'|transfer';?>' class='btn btn-info'><i class="bi bi-file-earmark-plus"></i>&nbsp;transfer</a>
				&nbsp;&nbsp;
 -->				<!-- <a href='r_asuhankeperawatan.php?id=<?php echo $id.'|'.$user.'|transfer';?>' class='btn btn-info'><i class="bi bi-printer-fill"></i></a>
 	&nbsp;&nbsp; -->
 	<a href='m_asuhankeperawatan.php?id=<?php echo $id.'|'.$user;?>' class=''><i>Master</i></a>
 	<br>
 	<br>
 	<table class="table" border="1">
 		<?php
 		$ql="
 		SELECT        distinct ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan, ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_nama
 		FROM            ERM_ASUHAN_KEPERAWATAN INNER JOIN
 		ERM_MASTER_ASUHANKEPERAWATAN ON ERM_ASUHAN_KEPERAWATAN.diagnosa_keperawatan = ERM_MASTER_ASUHANKEPERAWATAN.diagnosa_keperawatan
 		where noreg='$noreg'
 		";
 		$hl  = sqlsrv_query($conn, $ql);
 		$no=1;
 		echo 
 		"<tr bgcolor='#969392'>
 		<td>no</td><td>diagnosa_keperawatan</td><td>diagnosa_nama</td><td colspan='2'>action</td>
 		</tr>";
 		while   ($dl = sqlsrv_fetch_array($hl, SQLSRV_FETCH_ASSOC)){         
 			$implementasi = $dl[implementasi];
 			$implementasi = html_entity_decode($implementasi);

 			echo "	<tr>
 			<td>$no</td>
 			<td>$dl[diagnosa_keperawatan]</td>
 			<td>$dl[diagnosa_nama]</td>
 			<td><a href='askep_detail.php?id=$id|$user|$noreg|$dl[diagnosa_keperawatan]' class='btn btn-success btn-sm'><i class='bi bi-calendar-plus'></i> INTERVENSI</a></td>
 			<td><a href='implementasi.php?id=$id|$user|$noreg|$dl[diagnosa_keperawatan]' class='btn btn-info btn-sm'><i class='bi bi-calendar-fill'></i> IMPLEMENTASI</a></td>
 			</tr>
 			";
 			$no += 1;
 		}
 		?>
 	</table>
 	<br>
 </form>
</font>
</body>
</div>

<?php

if (isset($_POST["edit_rencana"])) {
	$idrasuhan = trim($_POST["idrasuhan"]);
	$rencana = trim($_POST["rencana"]);

	if(!empty($rencana)){
		$q  = "update ERM_ASUHAN_KEPERAWATAN set rencana='$rencana' where id=$idrasuhan";         
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

	}

}

if (isset($_POST["simpan_rencana"])) {

	$lanjut = 'Y';
	$asuan_diagnosa = trim($_POST["asuan_diagnosa"]);

	$qu="SELECT isian as rencana FROM ERM_MASTER_ASUHANKEPERAWATAN where diagnosa_keperawatan like '%$asuan_diagnosa%'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$rencana = trim($d1u['rencana']);
	$data = stripslashes($rencana);
	$data = htmlspecialchars($data);

	//jika tidak ada insert
	$q  = "insert ERM_ASUHAN_KEPERAWATAN(id_assesmen, noreg, diagnosa_keperawatan, tgl_teratasi, userid, rencana) values 
	('$id','$noreg','$asuan_diagnosa','$tgl','$user','$data')";         
	$hs = sqlsrv_query($conn,$q);


	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";
	header('Location: i_asuhankeperawatan1.php?id=$id|$user|$idrasuhan');

	// echo "
	// <script>
	// top.location='i_asuhankeperawatan1.php?id=$id|$user|$idrasuhan';
	// </script>
	// ";


}
?>

