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

$qu="SELECT noreg,norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];


//select assesment
//geriatri & dewasa
$qu="SELECT noreg,umur,dpjp FROM  ERM_RI_ASSESMEN_AWAL_DEWASA where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$cnoreg1 = $d1u['noreg'];
$cumur = intval(substr($d1u['umur'],0,2));

if($cnoreg1){
	$jenis='DEWASA';
	if($cumur>60){
		$jenis='GERIATRI';
	}else if($cumur>17 and $cumur<=60){
		$jenis='DEWASA';							
	}
}

//anak
$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_ANAK where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$cnoreg2 = $d1u['noreg'];

if($cnoreg2){
	$jenis='ANAK';

}

//neonatus
$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_NEONATUS where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$cnoreg3 = $d1u['noreg'];

if($cnoreg){
	$jenis3='NEONATUS';

}

//bersalin
$qu="SELECT noreg,dpjp FROM  ERM_RI_ASSESMEN_AWAL_BERSALIN where noreg='$noreg' and (dpjp <> '' or dpjp is not null)";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$cnoreg4 = $d1u['noreg'];

if($cnoreg4){
	$jenis='BERSALIN';
}


if($jenis=='ANAK'){
	echo "
	<script>
	window.location.replace('r_ews18.php?id=$id|$user');
	</script>
	";
}
if($jenis=='NEONATUS'){
	echo "
	<script>
	window.location.replace('r_ews1.php?id=$id|$user');
	</script>
	";
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
	$alamat = "
	Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik
	<br>
	IGD : 031-99100118 Telp : 031-3978658<br>
	Email : sbu.rspg@gmail.com
	";
	$logo = "logo/rspg.png";
};
if ($KET1 == 'GRAHU'){
	$nmrs = "RUMAH SAKIT GRHA HUSADA";
	$alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
	$logo = "logo/grahu.png";
};
if ($KET1 == 'DRIYO'){
	$nmrs = "RUMAH SAKIT DRIYOREJO";
	$alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
	$logo = "logo/driyo.png";
};


$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, noktp,
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  

$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  
$kodedept	= $data2[kodedept];

$nama	= $data2[nama];
$kelamin	= $data2[kelamin];
$nik	= trim($data2[nik]);
$alamatpasien	= $data2[alamatpasien];
$kota	= $data2[kota];
$kodekel	= $data2[kodekel];
$telp	= $data2[tlp];
$tmptlahir	= $data2[tmptlahir];
$tgllahir	= $data2[tgllahir];
$jenispekerjaan	= $data2[jenispekerjaan];
$jabatan	= $data2[jabatan];
$umur =  $data2[UMUR];
$noktp =  $data2[noktp];


?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>

</head> 

<div class="container-fluid">

	<body onload="document.myForm.pasien_mcu.focus();">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<br>
				<br>

				<div class="row">
					<div class="col-6">
						<table cellpadding="10px">
							<tr valign="top">
								<td>
									<img src='<?php echo $logo; ?>' width='150px'></img>								
								</td>
								<td>
									<h5><b><?php echo $nmrs; ?></b></h5>
									<?php echo $alamat; ?>								
								</td>
							</tr>
						</table>
					</div>
					<div class="col-6">
						<?php echo 'NIK : '.$noktp.'<br>'; ?>					
						<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
						<?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>

					</div>
				</div>
				<hr>


				<div class="row">
					<div class="col-12"><center><b>LEMBAR OBSERVASI NATIONAL EARLY WARNING SYSTEM (NEWS)
						UNTUK PASIEN USIA > 18 TAHUN
					</b></center></div>
				</div>
				<hr>
				<table class="table table-bordered">
					<tr>
						<td rowspan='2'>No</td>
						<td rowspan='2'>Tgl & Jam</td>
						<td rowspan='2'>Total Score</td>						
						<td rowspan=''>Parameter Respirasi</td>
						<td rowspan=''>Saturasi Oksigen</td>
						<td rowspan=''>Oksigen Tambahan</td>
						<td rowspan=''>Suhu</td>
						<td rowspan=''>Sistole</td>
						<td rowspan=''>Nadi</td>
						<td rowspan=''>Tingkat Kesadaran</td>
						<td rowspan='2'>Petugas</td>
					</tr>
					<tr>
						<td rowspan=''>
							<table  class="table table-bordered">
								<tr>
									<td>RANGE</td>
									<td>SCORE</td>
								</tr>
								<tr>
									<td>&ge; 25</td>
									<td>3</td>
								</tr>
								<tr>
									<td>21-24</td>
									<td>2</td>
								</tr>
								<tr>
									<td>12-20</td>
									<td>0</td>
								</tr>
								<tr>
									<td>9-11</td>
									<td>1</td>
								</tr>
								<tr>
									<td>&le; 8</td>
									<td>3</td>
								</tr>
							</table>							
						</td>

						<td rowspan=''>
							<table  class="table table-bordered">
								<tr>
									<td>RANGE</td>
									<td>SCORE</td>
								</tr>
								<tr>
									<td>&ge; 91</td>
									<td>3</td>
								</tr>
								<tr>
									<td>92-93</td>
									<td>2</td>
								</tr>
								<tr>
									<td>94-95</td>
									<td>1</td>
								</tr>
								<tr>
									<td>96-100</td>
									<td>0</td>
								</tr>
							</table>							
						</td>

						<td rowspan=''>
							<table  class="table table-bordered">
								<tr>
									<td>RANGE</td>
									<td>SCORE</td>
								</tr>
								<tr>
									<td>YA</td>
									<td>2</td>
								</tr>
								<tr>
									<td>TIDAK</td>
									<td>0</td>
								</tr>
							</table>							
						</td>

						<td rowspan=''>
							<table  class="table table-bordered">
								<tr>
									<td>RANGE</td>
									<td>SCORE</td>
								</tr>
								<tr>
									<td>&ge; 39.1</td>
									<td>2</td>
								</tr>
								<tr>
									<td>38.1-39</td>
									<td>1</td>
								</tr>
								<tr>
									<td>36.1-38</td>
									<td>0</td>
								</tr>
								<tr>
									<td>35.1-36</td>
									<td>1</td>
								</tr>
							</table>							
						</td>	

						<td rowspan=''>
							<table  class="table table-bordered">
								<tr>
									<td>RANGE</td>
									<td>SCORE</td>
								</tr>
								<tr>
									<td>&ge; 220</td>
									<td>3</td>
								</tr>
								<tr>
									<td>180-219</td>
									<td>2</td>
								</tr>
								<tr>
									<td>150-179</td>
									<td>1</td>
								</tr>
								<tr>
									<td>111-149</td>
									<td>0</td>
								</tr>
								<tr>
									<td>101-110</td>
									<td>1</td>
								</tr>
								<tr>
									<td>91-100</td>
									<td>2</td>
								</tr>
								<tr>
									<td>&le; 90</td>
									<td>3</td>
								</tr>
							</table>							
						</td>

						<td rowspan=''>
							<table  class="table table-bordered">
								<tr>
									<td>RANGE</td>
									<td>SCORE</td>
								</tr>
								<tr>
									<td>&ge; 131</td>
									<td>3</td>
								</tr>
								<tr>
									<td>111-130</td>
									<td>2</td>
								</tr>
								<tr>
									<td>91-110</td>
									<td>1</td>
								</tr>
								<tr>
									<td>51-90</td>
									<td>0</td>
								</tr>
								<tr>
									<td>41-50</td>
									<td>1</td>
								</tr>
								<tr>
									<td>&le; 40</td>
									<td>3</td>
								</tr>
							</table>							
						</td>

						<td rowspan=''>
							<table  class="table table-bordered">
								<tr>
									<td>RANGE</td>
									<td>SCORE</td>
								</tr>
								<tr>
									<td>ALERT</td>
									<td>0</td>
								</tr>
								<tr>
									<td>VPU</td>
									<td>3</td>
								</tr>
							</table>							
						</td>

					</tr>
					<?php 
					$q1		= "select top(50)*, 
					CONVERT(VARCHAR, tglinput, 23) as tglinput,
					CONVERT(VARCHAR, tglinput, 24) as jam  
					from ERM_RI_OBSERVASI where noreg='$noreg' and suhu > 0 order by id desc";
					$hasil1  = sqlsrv_query($conn, $q1);
					$nox=1;           
					while   ($data1 = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   
						$ket =
						'tensi :'.$data1[td_sistolik].'/'.$data1[td_diastolik].' ,'.
						'nadi :'.$data1[nadi].' ,'.
						'suhu :'.$data1[suhu].' ,'.
						'pernafasan :'.$data1[pernafasan].' ,'.
						'spo2 :'.$data1[spo2]
						;

						$rr = $data1[pernafasan]; 
						if($rr >=25){ $s_rr=3;}
						if($rr >=21 && $rr <=24 ){ $s_rr=2;}
						if($rr >=12 && $rr <=20 ){ $s_rr=0;}
						if($rr >=9 && $rr <=11 ){ $s_rr=1;}
						if($rr <=8){ $s_rr=3;}

						$oksigen_tambahan =$data1[oksigen_tambahan]; 
						if($oksigen_tambahan=='ya'){
							$s_oksigen_tambahan=2;
						}else{
							$s_oksigen_tambahan=0;
						}

						$spo2 = $data1[spo2]; 
						if($spo2 >=25 && $spo2 <=100 ){ $s_spo2=0;}
						if($spo2 >=94 && $spo2 <=95 ){ $s_spo2=1;}
						if($spo2 >=92 && $spo2 <=93 ){ $s_spo2=2;}
						if($spo2 >=91 && $spo2 <=92 ){ $s_spo2=3;}

						$suhu = $data1[suhu]; 
						if($suhu >=39.1){ $s_suhu=2;}
						if($suhu >=38.1 && $suhu <=39 ){ $s_suhu=1;}
						if($suhu >=36.1 && $suhu <=38 ){ $s_suhu=0;}
						if($suhu >=35.1 && $suhu <=36 ){ $s_suhu=1;}
						if($suhu >=9 && $suhu <=11 ){ $s_suhu=1;}
						if($suhu <=3 ){ $s_suhu=3;}

						$suhu = $data1[suhu]; 
						if($suhu >=39.1){ $s_suhu=2;}
						if($suhu >=38.1 && $suhu <=39 ){ $s_suhu=1;}
						if($suhu >=36.1 && $suhu <=38 ){ $s_suhu=0;}
						if($suhu >=35.1 && $suhu <=36 ){ $s_suhu=1;}
						if($suhu >=9 && $suhu <=11 ){ $s_suhu=1;}
						if($suhu <=3 ){ $s_suhu=3;}

						$sistole = $data1[td_sistolik];
						if($sistole >=220){ $s_sistole=3;}
						if($sistole >=180 && $sistole <=219 ){ $s_sistole=2;}
						if($sistole >=150 && $sistole <=179 ){ $s_sistole=1;}
						if($sistole >=111 && $sistole <=149 ){ $s_sistole=0;}
						if($sistole >=101 && $sistole <=110 ){ $s_sistole=1;}
						if($sistole >=91 && $sistole <=100 ){ $s_sistole=2;}
						if($sistole <=90 ){ $s_sistole=3;}						

						$nadi = $data1[nadi];
						if($nadi >=131){ $s_nadi=3;}
						if($nadi >=111 && $nadi <=130 ){ $s_nadi=2;}
						if($nadi >=91 && $nadi <=110 ){ $s_nadi=1;}
						if($nadi >=51 && $nadi <=90 ){ $s_nadi=0;}
						if($nadi >=41 && $nadi <=50 ){ $s_nadi=1;}
						if($nadi <=40 ){ $s_nadi=3;}	

						$tingkat_kesadaran =$data1[tingkat_kesadaran]; 
						if($tingkat_kesadaran=='vpu'){
							$s_tingkat_kesadaran=3;
						}else{
							$s_tingkat_kesadaran=0;
						}


						$total_score = $s_rr+$s_spo2+$s_oksigen_tambahan+$s_suhu+$s_sistole+$s_nadi+$s_tingkat_kesadaran;
						$score = intval($total_score);

						if(intval($total_score) == 0){
							$score = "<font size='5px' color='black'><b>$total_score</b></font>";
							$bgcolor='';
							$ket_ews='Sangat rendah<br>Perawat jaga melakukan monitor setiap shift';
						}else if (intval($total_score) >= 1 and intval($total_score) <= 4 ){
							$score = "<font size='5px' color='black'><b>$total_score</b></font>";
							$bgcolor='#90EE90';
							$ket_ews='Rendah<br>Perawat jaga melakukan monitor setiap 4-6 jam <br> dan menilai apakah perlu untuk meningkatkan frekuensi monitoring';
						}else if(intval($total_score) > 2 and intval($total_score) < 5){
							$score = "<font size='5px' color='black'><b>$total_score</b></font>";
							$bgcolor='#FAFAD2';		
							$ket_ews='Sedang<br>Perawat jaga melakukan monitor tiap 1 jam <br> dan melaporkan ke dr jaga dan mempersiapkan jika mengalami perburukan kondisi pasien';
						}else{
							$score = "<font size='5px' color='black'><b>$total_score</b></font>";	
							$bgcolor='#FF6347';
							$ket_ews='Tinggi<br>Perawat, tim emergency, DPJP melakukan tatalaksana kegawatan, observasi tiap 30 menit/ setiap saat. <br> Aktifkan tim code blue bila terjadi cardiac arrest, transfer ke ruang ICU';
						}

						echo "
						<tr>
						<td>$nox</td>
						<td>$data1[tglinput]<br>$data1[jam]</td>
						<td align='center' bgcolor='$bgcolor'> $score<br>$ket_ews </td>
						<td>Nilai : $rr<br>Score : $s_rr</td>
						<td>Nilai : $spo2<br>Score : $s_spo2</td>
						<td>Nilai : $oksigen_tambahan<br>Score : $s_oksigen_tambahan</td>
						<td>Nilai : $suhu<br>Score : $s_suhu</td>
						<td>Nilai : $sistole<br>Score : $s_sistole</td>
						<td>Nilai : $nadi<br>Score : $s_nadi</td>
						<td>Nilai : $tingkat_kesadaran<br>Score : $s_tingkat_kesadaran</td>
						<td>$data1[userinput]</td>
						</tr>
						";

						$nox+=1;
					}
					?>
				</table>
			</font>
		</form>
	</font>
</body>
</div>