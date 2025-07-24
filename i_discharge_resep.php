<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tglinput		= gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idresep = $row[2];
$idresep_detail = $row[3];

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$q3       = "select nomor,eresep from ERM_RI_RPO_header where id='$idrpo'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$nomor = trim($data3[nomor]);
$cek_eresep = $data3[eresep];

$q3b       = "select CONVERT(VARCHAR, tgl, 103) as tgl, nama_obat, jumlah, dosis, waktu_penggunaan,
interval, dokter, apoteker, periksa, pemberi, keluarga,nomor
from ERM_RI_RPO
where id_rpo_header='$idrpo'";
$hasil3b  = sqlsrv_query($conn, $q3b);  
$data3b    = sqlsrv_fetch_array($hasil3b, SQLSRV_FETCH_ASSOC);                      
$nama_obat = $data3b[nama_obat];
$cek_nama_obat = $nama_obat;

$interval = $data3[interval];
$dokter = $data3[dokter];
$apoteker = $data3[apoteker];
$periksa = $data3[periksa];
$pemberi = $data3[pemberi];
$keluarga = $data3[keluarga];
$nomor = $data3[nomor];

//resep
$q="
SELECT        TOP (200) W_EResep_R.Id, CONVERT(VARCHAR, W_EResep_R.TglEntry, 25) AS tglentry, W_EResep_R.KodeR, AFarm_MstObat.NAMABARANG, W_EResep_R.Jumlah, W_EResep_R.AturanPakai, W_EResep_R.CaraPakai, 
W_EResep_R.WaktuPakai, W_EResep_R.Keterangan, W_EResep_R.Satuan, Afarm_MstSatuan.NAMASATUAN
FROM            W_EResep_R INNER JOIN
Afarm_MstSatuan ON W_EResep_R.Satuan = Afarm_MstSatuan.KODESATUAN LEFT OUTER JOIN
AFarm_MstObat ON W_EResep_R.KodeR = AFarm_MstObat.KODEBARANG
WHERE        (W_EResep_R.Id = $idresep_detail)
";
$hasil  = sqlsrv_query($conn, $q);  
$no=1;
while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

	$Id = $data[Id];
	$tgl = $data[tglentry];
	$nama_obat = $data[NAMABARANG];
	$dosis = $data[AturanPakai];
	$jumlah = $data[Jumlah];
	$aturan_pakai = $data[WaktuPakai];
	$instruksi_khusus = '';

	$q  = "insert into ERM_RI_DISCHARGE(noreg,userid,tglentry,tgl,nama_obat,jumlah,aturan_pakai,instruksi_khusus,id_eresep,id_eresep_detail) 
	values ('$noreg','$user','$tglinput','$tglinput','$nama_obat','$jumlah','$aturan_pakai','$instruksi_khusus',$idresep,$idresep_detail)";
	$hs = sqlsrv_query($conn,$q);

}

if($hs){
	$eror = 'Ok';

	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";
}

?>