<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);


$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tgl2		= gmdate("Y-m-d", time()+60*60*7);
$jam		= gmdate("H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$noreg = $row[2];
$diagnosa_keperawatan =$row[3]; 
$idresiko_jatuh =$row[4]; 


$qer="SELECT ket FROM ERM_RI_RJATUH where id='$idresiko_jatuh'";
$her  = sqlsrv_query($conn, $qer);        
$der  = sqlsrv_fetch_array($her, SQLSRV_FETCH_ASSOC); 
$ket = $der['ket'];


$userid=$user;

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">


</head> 

<div class="container-fluid py-4">

	<body onload="document.myForm.pasien_mcu.focus();">
		<div class="row mb-3">
			<div class="col-12">
				<?php include "header_soap.php"; ?>
			</div>
		</div>

		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<div class="mb-4 d-flex align-items-center gap-3">
				<a href='resikojatuh.php?id=<?php echo $id."|".$user."|".$idrasuhan;?>' class='btn btn-warning'>
					<i class="bi bi-x-circle-fill"></i> Close
				</a>
				<a href='' class='btn btn-success'>
					<i class="bi bi-arrow-clockwise"></i> Refresh
				</a>
			</div>
			<div class="mb-4 d-flex align-items-center gap-3">
				<div class="w-100 text-center mb-3">
					<h3 class="text-primary m-0">
						<u>IMPLEMENTASI KEPERAWATAN RESIKO JATUH</u>
					</h3>
				</div>

			</div>


			<div class="row mb-4">
				<div class="col-md-3 mb-2">
					<label for="noreg" class="form-label fw-bold">NOREG</label>
					<input name="noreg" value="<?php echo $noreg;?>" id="noreg" type="text" class="form-control" readonly>
				</div>
				<div class="col-md-3 mb-2">
					<label for="diagnosa_keperawatan" class="form-label fw-bold">Diagnosa Keperawatan</label>
					<input name="diagnosa_keperawatan" value="<?php echo $diagnosa_keperawatan;?>" type="text" class="form-control">
				</div>
				<div class="col-md-3 mb-2">
					<label for="sift" class="form-label fw-bold">Sift</label>
					<select name="sift" class="form-select" required>
						<option value=''>--Pilih Sift--</option>
						<option value='DINAS PAGI'>DINAS PAGI</option>
						<option value='DINAS SIANG'>DINAS SIANG</option>
						<option value='DINAS MALAM'>DINAS MALAM</option>
					</select>
				</div>
				<div class="col-md-3 d-flex align-items-end">
					<button type='submit' name='simpan' value='simpan' class="btn btn-info w-100">
						<i class="bi bi-save-fill"></i> Simpan
					</button>
				</div>
			</div>

			<input type="hidden" name="idrasuhan" value="<?php echo $idrasuhan;?>">

			<table class='table table-bordered table-striped'>
				<thead class="table-primary text-center">
					<tr>
						<th>No</th>
						<th>Implementasi</th>
						<th>Tanggal / Jam</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					if($ket==''){
						$q = "
						SELECT DISTINCT rencana
						FROM            ERM_ASUHAN_KEPERAWATAN2
						WHERE        (diagnosa_keperawatan = 'D.0143') AND (jenis IN ('master6', 'master7', 'master8', 'master9'))";

						$hasil  = sqlsrv_query($conn, $q);
						$i = 1;

						while ($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)) {
							$i_tgl = 'tgl'.$i;
							$i_jam = 'jam'.$i;
							$i_rencana = 'rencana'.$i;

							$rencana = str_replace("Berikan","Memberikan",$data['rencana']);
							$rencana = str_replace("Kolaborasi","Mengkolaborasi",$data['rencana']);
							$rencana = str_replace("Anjurkan","Menganjurkan",$data['rencana']);
							$rencana = str_replace("Monitor","Memonitor",$data['rencana']);

							echo "
							<tr>
							<td class='text-center'>$i</td>
							<td><input type='text' name='$i_rencana' value='{$rencana}' class='form-control'></td>
							<td class='d-flex gap-2'>
							<input type='date' name='$i_tgl' class='form-control'>
							<input type='time' name='$i_jam' class='form-control'>
							</td>
							</tr>
							";
							$i++;
						}
					}
					if($ket=='Risiko tinggi'){
						$i=6;
						echo "
						<tr>
						<td class='text-center'>1</td>
						<td><input type='text' name='rencana1' value='Memastikan tempat tidur / brankard dalam posisi rendah dan roda terkunci' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl1' class='form-control'>
						<input type='time' name='jam1' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>2</td>
						<td><input type='text' name='rencana2' value='Menutup pagar tempat tidur / brankard dalam posisi berdiri / terpasang' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl2' class='form-control'>
						<input type='time' name='jam2' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>3</td>
						<td><input type='text' name='rencana3' value='Lakukan orientasi kamar / ruangan sekitar kepada pasien dan keluarga' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl3' class='form-control'>
						<input type='time' name='jam3' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>4</td>
						<td><input type='text' name='rencana4' value='Pastikan pasien memiliki kancing warna kuning pada gelang identifikasi dan tanda segitiga kuning pada tempat tidur pasien' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl4' class='form-control'>
						<input type='time' name='jam4' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>5</td>
						<td><input type='text' name='rencana5' value='Beri penjelasan kepada pasien / keluarga tentang pencegahan jatuh dan menyarankan keluarga / penunggu selalu berada di sekitar pasien' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl5' class='form-control'>
						<input type='time' name='jam5' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>6</td>
						<td><input type='text' name='rencana6' value='Lakukan asesmen ulang risiko jatuh setiap shift' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl6' class='form-control'>
						<input type='time' name='jam6' class='form-control'>
						</td>
						</tr>
						";

					}
					if($ket=='Risiko sedang'){
						$i=4;
						echo "
						<tr>
						<td class='text-center'>1</td>
						<td><input type='text' name='rencana1' value='Memastikan tempat tidur / brankard dalam posisi rendah dan roda terkunci' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl1' class='form-control'>
						<input type='time' name='jam1' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>2</td>
						<td><input type='text' name='rencana2' value='Menutup pagar tempat tidur / brankard dalam posisi berdiri / terpasang' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl2' class='form-control'>
						<input type='time' name='jam2' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>3</td>
						<td><input type='text' name='rencana3' value='Lakukan orientasi kamar / ruangan sekitar kepada pasien dan keluarga' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl3' class='form-control'>
						<input type='time' name='jam3' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>4</td>
						<td><input type='text' name='rencana4' value='Lakukan asesmen ulang risiko jatuh setiap 12 jam' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl4' class='form-control'>
						<input type='time' name='jam4' class='form-control'>
						</td>
						</tr>
						";
					}
					if($ket=='Risiko rendah'){
						$i=4;
						echo "
						<tr>
						<td class='text-center'>1</td>
						<td><input type='text' name='rencana1' value='Memastikan tempat tidur / brankard dalam posisi rendah dan roda terkunci' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl1' class='form-control'>
						<input type='time' name='jam1' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>2</td>
						<td><input type='text' name='rencana2' value='Menutup pagar tempat tidur / brankard dalam posisi berdiri / terpasang' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl2' class='form-control'>
						<input type='time' name='jam2' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>3</td>
						<td><input type='text' name='rencana3' value='Lakukan orientasi kamar / ruangan sekitar kepada pasien dan keluarga' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl3' class='form-control'>
						<input type='time' name='jam3' class='form-control'>
						</td>
						</tr>
						<tr>
						<td class='text-center'>4</td>
						<td><input type='text' name='rencana4' value='Lakukan asesmen ulang risiko jatuh setiap 24 jam' class='form-control'></td>
						<td class='d-flex gap-2'>
						<input type='date' name='tgl4' class='form-control'>
						<input type='time' name='jam4' class='form-control'>
						</td>
						</tr>
						";
					}

					?>
					<tr>
						<td class='text-center'>-</td>
						<td>
							<textarea name="rencanatext" class="form-control" rows="3"><?php echo $rencanatext;?></textarea>
						</td>
						<td class='d-flex gap-2'>
							<input type='date' name='tglket' class='form-control'>
							<input type='time' name='timeket' class='form-control'>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</body>
</div>

<?php

if (isset($_POST["simpan"])) {
	$sift = trim($_POST["sift"]);
	$noreg = trim($_POST["noreg"]);

	echo $i;

	// $q  = "delete from  ERM_IMPLEMENTASI_ASUHAN where jenis <> ''";         
	// $hs = sqlsrv_query($conn,$q);

	for ($u=1 ; $u < $i ; $u++){
		$rencana = trim($_POST[rencana.$u]);
		$tanggal = trim($_POST[tgl.$u]);
		$jam = trim($_POST[jam.$u]);

		if($tanggal){
			$q  = "insert ERM_IMPLEMENTASI_JATUH(noreg, sift, implementasi, userid, tgl,tanggal,jam,diagnosa_keperawatan) values 
			('$noreg','$sift','$rencana','$userid','$tgl','$tanggal','$jam','$diagnosa_keperawatan')";         
			$hs = sqlsrv_query($conn,$q);
		}
	}

	$rencanatext = trim($_POST["rencanatext"]);

	if($rencanatext){
		$tglket = trim($_POST["tglket"]);
		$timeket = trim($_POST["timeket"]);

		$q  = "insert ERM_IMPLEMENTASI_JATUH(noreg, sift, implementasi, userid, tgl,tanggal,jam,diagnosa_keperawatan) values 
		('$noreg','$sift','$rencanatext','$userid','$tgl','$tglket','$timeket','$diagnosa_keperawatan')";         
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


}


?>

