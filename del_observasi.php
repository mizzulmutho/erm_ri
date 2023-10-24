<?php 
include ("koneksi.php");
$tgl		= gmdate("Y-m-d", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idobservasi = $row[2]; 

$ql1 ="delete from ERM_RI_OBSERVASI where id='$idobservasi'";
$hs  = sqlsrv_query($conn,$ql1);

if($hs){
	$eror = "Success";

	echo "
	<script>
	alert('".$eror."');
	history.go(-2);
	</script>
	";

}else{
	$eror = "Gagal Insert";

	echo "
	<script>
	alert('".$eror."');
	</script>
	";

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";          

}


?>
