<?php 
include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// $serverName = "192.168.10.1"; //serverName\instanceName
// $connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
// $conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idresep = $row[2]; 


$qu="
SELECT        W_EResep.Noreg, W_EResep_R.Jenis, W_EResep_R.KodeR, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, W_EResep_R.WaktuPakai, W_EResep_Racikan.Nama, W_EResep_R.id as idr,W_EResep_R.Keterangan,W_EResep_R.DeletedBy,
AFarm_MstObat.NAMABARANG, W_EResep.Id, W_EResep_Racikan.Dosis
FROM            AFarm_MstObat INNER JOIN
W_EResep_R ON AFarm_MstObat.KODEBARANG = W_EResep_R.KodeR INNER JOIN
W_EResep ON W_EResep_R.IdResep = W_EResep.Id LEFT OUTER JOIN
W_EResep_Racikan ON W_EResep_R.Id = W_EResep_Racikan.IdR
WHERE        (W_EResep.Noreg = '$noreg') AND W_EResep.Kategori like '%KRS%' and W_EResep_R.Id='$idresep'
";
$h1u  = sqlsrv_query($conn, $qu);        
$datao  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 

if($datao['Jenis']=='1'){
	$nama_obat=$datao['NAMABARANG'];
}else{
	$nama_obat=$datao['Nama'];
}
$jumlah=$datao['Jumlah'];
$aturan_pakai=$datao['AturanPakai'];
$instruksi_khusus=$datao['Keterangan'];
$DeletedBy=$datao['DeletedBy'];

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

<div class="container-fluid">

	<body onload="document.myForm.td_sistolik.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">

				<br>
				<a href='form_discharge.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;

				<br>
				<br>
				Nama Obat
				<input class="form-control" name="nama_obat" value="<?php echo $nama_obat;?>" type="text">
				<br>
				Jumlah
				<input class="form-control" name="jumlah" value="<?php echo $jumlah;?>" type="text">
				<br>
				Aturan Pakai
				<input class="form-control" name="aturan_pakai" value="<?php echo $aturan_pakai;?>" type="text">
				<br>
				Instruksi Khusus
				<input class="form-control" name="instruksi_khusus" value="<?php echo $instruksi_khusus;?>" type="text">
				<br>
				Deleted By
				<input class="form-control" name="DeletedBy" value="<?php echo $DeletedBy;?>" type="text">
				<br>

				<center>
					<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> Ubah</button>
					&nbsp;&nbsp;&nbsp;
					<button type='submit' name='hapus' value='hapus' class="btn btn-danger" type="button" style="height: 60px;width: 150px;"><i class="bi bi-trash-fill"></i> Hapus</button>

				</center>
			</div>
		</div>
	</div>
</form>
</font>
</body>
</div>

<?php
if (isset($_POST["hapus"])) {

	$DeletedBy	= trim($_POST["DeletedBy"]);

	if($DeletedBy=='NULL'){
		$q  = "update  W_EResep_R set DeletedBy = NULL where id='$idresep'";
	}else{
		$q  = "update  W_EResep_R set DeletedBy='$user' where id='$idresep'";
	}
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Delete";

	}

	echo "
	<script>
	alert('".$eror."');
	window.location.replace('obat_resep_edit.php?id=$id|$user|$idresep');
	</script>
	";

}

if (isset($_POST["simpan"])) {

	$aturan_pakai	= trim($_POST["aturan_pakai"]);
	$instruksi_khusus	= trim($_POST["instruksi_khusus"]);

	$q  = "update  W_EResep_R set AturanPakai='$aturan_pakai', Keterangan='$instruksi_khusus' where id='$idresep'";
	$hs = sqlsrv_query($conn,$q);

	if($hs){
		$eror = "Success";
	}else{
		$eror = "Gagal Insert";

	}

	echo "
	<script>
	alert('".$eror."');
	window.location.replace('obat_resep_edit.php?id=$id|$user|$idresep');
	</script>
	";


}


?>

