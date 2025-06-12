<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tgl2		= gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$edit = $row[2]; 
$nama_obat = $row[3]; 

$rand = rand(1, 9);
// $rand2 = rand(1, 9);
$nomor = trim($rand.'-'.$tgl);

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qu="
SELECT        TOP (200) NORM,  ALERGI
FROM            Y_ALERGI 
where norm='$norm'

union 

SELECT        ARM_REGISTER.NORM, V_ERM_RI_KEADAAN_UMUM.alergi as ALERGI
FROM            V_ERM_RI_KEADAAN_UMUM INNER JOIN
ARM_REGISTER ON V_ERM_RI_KEADAAN_UMUM.noreg = ARM_REGISTER.NOREG
where ARM_REGISTER.NORM='$norm' and V_ERM_RI_KEADAAN_UMUM.alergi <> '' 

";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

//alergi dari rekon obat...
$qalergi="
select TOP(100) userid,obat,gejala,tingkat_keparahan,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
from ERM_RI_ALERGI
where noreg='$noreg' order by id desc
";
$hasil_alergi  = sqlsrv_query($conn, $qalergi);  
$objective = "Riwayat Alergi Obat :\n";

while   ($data_alergi = sqlsrv_fetch_array($hasil_alergi,SQLSRV_FETCH_ASSOC)){ 
	$lalergi=$data_alergi['obat'];
	$gejala = '('.$data_alergi['gejala'].')';
	$row2 = explode('-',$lalergi);

	$oalergi  = trim($row2[1]);
	if(empty($oalergi)){
		$oalergi  = trim($row2[0]);          
	}
	$alergi = $alergi.$oalergi." ,";
}



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

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>RPO</title>  
	<link rel="icon" href="favicon.ico">  
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head> 
<body>
	<div class="container my-4">
		<div class="d-flex justify-content-center align-items-center gap-2 mb-3">
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>
				<i class="bi bi-x-circle"></i> Close
			</a>
			<a href='rpo2.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'>
				<i class="bi bi-arrow-clockwise"></i> Refresh
			</a>
			<a href='rpo_print.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-info' target='_blank'>
				<i class="bi bi-printer-fill"></i> Print
			</a>
			<a href='rpo.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-primary'>
				<i class="bi bi-pencil-square"></i> Input Data
			</a>
		</div>

		<div class="row">
			<div class="col-md-6">
				<h5><b><i class="bi bi-hospital"></i> <?php echo $nmrs; ?></b></h5>
				<p><i class="bi bi-geo-alt"></i> <?php echo $alamat; ?></p>
			</div>
			<div class="col-md-6">
				<p><i class="bi bi-person-badge"></i> <strong>NIK:</strong> <?php echo $noktp; ?></p>
				<p><i class="bi bi-person"></i> <strong>Nama Lengkap:</strong> <?php echo $nama; ?> | <strong>No RM:</strong> <?php echo $norm; ?></p>
				<p><i class="bi bi-calendar"></i> <strong>Tanggal Lahir:</strong> <?php echo $tgllahir; ?> | <strong>Umur:</strong> <?php echo $umur; ?></p>
				<p><i class="bi bi-gender-ambiguous"></i> <strong>Jenis Kelamin:</strong> <?php echo $kelamin; ?></p>
				<p><i class="bi bi-house"></i> <strong>Alamat:</strong> <?php echo $alamatpasien; ?></p>
				<p><i class="bi bi-exclamation-triangle"></i> <strong>Alergi:</strong> <span class="text-danger"><?php echo $alergi; ?></span></p>
			</div>
		</div>
		<hr>
		<h4 class="text-center text-primary"><i class="bi bi-file-medical"></i> Rekam Pemberian Obat</h4>
		<table class="table table-bordered table-hover">
			<thead class="table-dark text-center">
				<tr>
					<th><i class="bi bi-hash"></i> No</th>
					<th><i class="bi bi-capsule"></i> Nama Obat</th>
					<th><i class="bi bi-clipboard-check"></i> Dosis</th>
					<th><i class="bi bi-pencil-square"></i> Aturan Pakai</th>
					<th><i class="bi bi-plus-circle"></i> Tambah Jadwal</th>
					<th><i class='bi bi-stop-circle'></i> Stop Pemberian</th>
					<th><i class="bi bi-clock"></i> Waktu Pemberian</th>
				</tr>
			</thead>
			<?php
			if($edit){
				echo "<b><font color='red'>Tekan Refresh untuk menampilkan semua data !!!!</font></b><br><br>";
				$q="
				select distinct nama_obat
				from ERM_RI_RPO
				where noreg='$noreg' and nama_obat like '%$nama_obat%' order by nama_obat asc
				";				
			}else{
				$q="
				select distinct nama_obat
				from ERM_RI_RPO
				where noreg='$noreg' order by nama_obat asc
				";				
			} 
			$hasil  = sqlsrv_query($conn, $q);  
			$no=1;
			while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 
				?>

				<tr>
					<td><?php echo $no; ?></td>
					<td>
						<?php 
						echo $data[nama_obat]; 
						$qo="SELECT top(1)interval,id_rpo_header,id,dosis,stop,CONVERT(VARCHAR, tglstop, 120) as tglstop
						FROM ERM_RI_RPO where noreg='$noreg' and nama_obat='$data[nama_obat]'";
						$hqo  = sqlsrv_query($conn, $qo);        
						$dhqo  = sqlsrv_fetch_array($hqo, SQLSRV_FETCH_ASSOC); 
						echo $aturanpakai = '<br>Aturan Pakai : '.trim($dhqo['interval']);
						$id_rpo_header = $dhqo['id_rpo_header'];
						$id_rpo = $dhqo['id'];
						$dosis = $dhqo['dosis'];
						$stop = trim($dhqo['stop']);

						$tgl = date_create($dhqo['tglstop']);
						$format_indonesia = date_format($tgl, 'd-m-Y H:i');


						if($stop=='Y'){
							echo '<span style="color:hotpink;"><br>Ket Stop: ' . $dhqo['stop'] . '<br>Tgl: ' . $format_indonesia . '</span>';
						}
						?>
					</td>
					<td align="center"><?php echo $dosis; ?></a>
					</td>
					<td align="center"><a href='rpo_edit.php?id=<?php echo $id.'|'.$user.'|'.$id_rpo_header.'|edit|'.$id_rpo; ?>'>
						<font color='green'>✎</font></a>
					</td>
					<td align="center"><a href='rpo_beri.php?id=<?php echo $id.'|'.$user.'|'.$id_rpo_header.'|'.$data[nama_obat].'|'.$id_rpo; ?>'>
						<font color='green'>➕</font></a>
					</td>
					<td align="center">
						<a href='rpo_stop.php?id=<?php echo $id."|".$user."|".$id_rpo_header."|".$data['nama_obat']."|".$id_rpo; ?>' title='Stop RPO'>
							<i class='bi bi-stop-circle text-danger'></i> Stop
						</a>
					</td>
					<td>									
						<?php 
						$q2="
						select TOP(100) userid,nama_obat,jumlah,
						CONVERT(VARCHAR, tglentry, 25) as tglentry,
						CONVERT(VARCHAR, tgl, 103) as tgl,
						CONVERT(VARCHAR, tgl, 8) as jam,
						id, id_rpo_header,
						dokter, apoteker, periksa, pemberi, keluarga,ttd_keluarga
						from ERM_RI_RPO_BERI
						where noreg='$noreg' and nama_obat ='$data[nama_obat]' order by id desc
						";
						$hasil2  = sqlsrv_query($conn, $q2);  
						$no2=1;
						echo "<table class='table-dark text-center table-bordered table-hover'>";
						echo "
						<tr style='background-color:#708090; color:white; text-align:center;'>
						<th width='3%''>No</th>
						<th width='5%'>Tgl</th>
						<th width='5%'>Jam</th>
						<th width='5%'>Dokter</th>
						<th width='5%'>Apoteker</th>
						<th width='17%'>Periksa</th>
						<th width='18%'>Pemberi</th>
						<th width='5%'>Keluarga</th>
						<th width='2%'>Edit</th>
						<th width='2%'>Hapus</th>
						</tr>";

						while   ($data2 = sqlsrv_fetch_array($hasil2,SQLSRV_FETCH_ASSOC)){

							$ttd_keluarga = $data2[ttd_keluarga];

							if($data2[keluarga]){ 
										// $keluarga = substr($data2[keluarga], 0,10).'...';
								$keluarga = '&radic;';
							}else{
								$keluarga='';
							}

							if($data2[dokter]){ 
								$dokter = '&radic;';
							}else{
								$dokter='';
							}

							if($data2[apoteker]){ 
								$apoteker = '&radic;';
							}else{
								$apoteker='';
							}

							$periksa = trim($data2[periksa]);
							$pemberi = trim($data2[pemberi]);
							$nmperiksa ='';
							$nmpemberi = '';
							if($periksa){
								$qu="SELECT NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$periksa'";
								$h1u  = sqlsrv_query($conn, $qu);        
								$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
								$nmperiksa = trim($d1u['NamaUser']);
							}

							if($pemberi){
								$qu2="SELECT NamaUser FROM ROLERSPGENTRY.dbo.TBLuserERM where user1 = '$pemberi'";
								$h1u2  = sqlsrv_query($conn, $qu2);        
								$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
								$nmpemberi = trim($d1u2['NamaUser']);
							}

							echo "
							<tr style='background-color:; color:black; text-align:center;'>
							<td align='center'>$no2</td>
							<td align='center'>$data2[tgl]</td>
							<td align='center'>$data2[jam]</td>
							<td align='center'>$dokter</td>
							<td align='center'>$apoteker</td>
							<td align='center'>$nmperiksa</td>
							<td align='center'>$nmpemberi</td>
							<td align='center'><img src='$ttd_keluarga' width='30px' alt='TTD Keluarga'></td>
							<td align='center'>
							<a href='rpo_beri_edit.php?id=$id|$user|$data2[id_rpo_header]|$data2[id]' style='color:green; text-decoration:none; font-weight:bold;'>✎</a>
							</td>
							<td align='center'>
							<a href='del_rpo_beri.php?id=$id|$user|$data2[id_rpo_header]|$data2[id]' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?')\" style='color:red; text-decoration:none; font-weight:bold;'>❌</a>
							</td>
							</tr>";

							$no2 += 1;
						}
						echo "</table>";
						?>
					</td>
				</tr>

				<?php
				$no += 1;
			}

			?>
		</tbody>
	</table>
</div>
</body>


<?php 

if (isset($_POST["new"])) {

	// $tgl	= $_POST["tgl"];
	// $nama_obat	= $_POST["nama_obat"];
	// $dosis	= $_POST["dosis"];
	// $waktu_penggunaan	= $_POST["waktu_penggunaan"];
	// $jumlah	= $_POST["jumlah"];

	$q  = "insert into ERM_RI_RPO_HEADER(noreg,userid,tglentry,tgl,nomor) 
	values ('$noreg','$user','$tgl','$tgl','$nomor')";
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