<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idrpo = $row[2]; 
$idresep = $row[3];
$idrpo_detail = $row[4];

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);
$noreg_igd = substr($noreg, 1,12);

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


// $q = " 
// DELETE from ERM_RI_LAB_TEMP             
// WHERE noreg='$noreg'
// ";
// $h1  = sqlsrv_query($conn, $q);        

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>Laborat List</title>  
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



</head> 
<div id="content"> 
	<div class="container">

		<body onload="document.myForm.nama_obat.focus();">
			<font size='2px'>
				<form method="POST" name='myForm' action="" enctype="multipart/form-data">
					<br>
					<a href='soap_dokter.php?id=<?php echo $id.'|'.$user.'|i_rad';?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
					&nbsp;&nbsp;
					<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
					&nbsp;&nbsp;
					<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
					<a href='del_list_rad.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-danger'><i class="bi bi-x-circle"></i> Hapus Pilihan</a>
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
					<b>LIST HASIL RADIOLOGI</b><br>
				</div>
			</div>

			<br>
			<table class='table'>
				<tr>
					<td style="border: 1px solid;" bgcolor='#708090' align="center"><font color='white'>no</font></td>					
					<td style="border: 1px solid;" bgcolor='#708090' align="center"><font color='white'>tgl</font></td>
					<td style="border: 1px solid;" bgcolor='#708090' align="center"><font color='white'>hasil</font></td>
					<td style="border: 1px solid;" bgcolor='#708090' align="center"><font color='white'>uraian</font></td>
					<td style="border: 1px solid;" bgcolor='#708090' align="center"><font color='white'>pilih</font></td>
					<td style="border: 1px solid;" bgcolor='#708090' align="center"><font color='white'>batal</font></td>
				</tr>
				<?php 
				$q="
				SELECT        HASIL, URAIAN, ID, CONVERT(VARCHAR, TANGGAL, 103) AS TANGGAL
				FROM            HASILRAD_PEMERIKSAAN_RAD
				WHERE        (NOREG like '%$noreg_igd%')
				ORDER BY TANGGAL
				";
				$hasil  = sqlsrv_query($conn, $q);  
				$no=1;
				while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

					$q2 = " 
					SELECT idrad from ERM_RI_RAD_TEMP
					WHERE        noreg like '%$noreg_igd%' and (idrad = '$data[ID]')";
					$h2  = sqlsrv_query($conn, $q2);
					$dh2  = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
					$idrad = trim($dh2['idrad']);

					if($idrad){
						$color='#DCDCDC';
					}else{
						$color='';
					}

					echo "
					<tr>
					<td bgcolor='$color'>$no</td>					
					<td bgcolor='$color'>$data[TANGGAL]</td>
					<td bgcolor='$color'>$data[HASIL]</td>
					<td bgcolor='$color'>$data[URAIAN]</td>
					<td bgcolor='$color' align='center'>
					<a href='i_rad.php?id=$id|$user|$data[ID]'>
					<font color='gren'><i class='bi bi-check-all'></i></font>
					</a>
					</td>
					<td bgcolor='$color' align='center'>
					<a href='i_rad_batal.php?id=$id|$user|$data[ID]'>
					<font color='gren'><i class='bi bi-x-circle'></i></font>
					</a>
					</td>
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
	$nama_obat	= $_POST["nama_obat"];
	$dosis	= $_POST["dosis"];
	$waktu_penggunaan	= $_POST["waktu_penggunaan"];

	$q  = "insert into ERM_RI_RPO(noreg,userid,tglentry,tgl,nama_obat,dosis,waktu_penggunaan) 
	values ('$noreg','$user','$tgl','$tgl','$nama_obat','$dosis','$waktu_penggunaan')";
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

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";


}

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}


?>