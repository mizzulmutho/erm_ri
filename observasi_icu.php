<?php 
// include ("koneksi.php");

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

$qu="SELECT noreg,norm FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$norm = $d1u['norm'];

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);

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

$q="
SELECT     TOP (200) id, noreg, userid,
substring(CONVERT(VARCHAR, tgl, 103),1,2) as tgl,tgl as tgl2,
substring(CONVERT(VARCHAR, tgl, 8),1,2) as jam,
sistole, diastole, nadi, suhu, cvp,
spo2,rr,gcs,pupil,nyeri,oksigen,ventilator,transfusi,cairan,obat_oral,obat_injeksi,ngt,urine,drain,suction,mobilisasi, '' as jumlah_cairan,
iwl,olain,tolain,
oral,intraveneous,icairan,gastric,transfusion,am,ilain,tilain,bcairan
FROM         ERM_RI_OBSERVASI_ICU
where noreg='$noreg' 
order by tgl2 asc";
$h  = sqlsrv_query($conn, $q);
$i=1;

while   ($data = sqlsrv_fetch_array($h, SQLSRV_FETCH_ASSOC)){         
	$ket = $ket."'"."$data[tgl]"."("."$data[jam]".")"."'".",";
	$sistole  = $sistole."'"."$data[sistole]"."',";
	$diastole  = $diastole."'"."$data[diastole]"."',";
	$suhu  = $suhu."'"."$data[suhu]"."',";
	$nadi  = $nadi."'"."$data[nadi]"."',";
	$cvp  = $cvp."'"."$data[cvp]"."',";
	// echo $data[spo2].'-';
	if(!empty($data[spo2])){
		// $id_obs  = $id_obs."<td>"."$data[id]"."</td>";
		$id_obs  = $id_obs."
		<td>
		<a href='icu_e.php?id=$id|$user|$data[id]' class='btn btn-success btn-sm'><i class='bi bi-file-earmark-minus' ></i></a>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<a href='icu_d.php?id=$id|$user|$data[id]' class='btn btn-danger btn-sm'><i class='bi bi-x-circle' ></i></a>
		</td>";		
		$spo2  = $spo2."<td>"."$data[spo2]"."</td>";
		$rr  = $rr."<td>"."$data[rr]"."</td>";
		$gcs  = $gcs."<td>"."$data[gcs]"."</td>";
		$pupil  = $pupil."<td>"."$data[pupil]"."</td>";
		$nyeri  = $nyeri."<td>"."$data[nyeri]"."</td>";
		$useri  = $useri."<td align='center'><font color='black'><b>"."$data[userid]"."</b><br>"."$data[tgl]"."-"."$data[jam]"."</font></td>";
		$oksigen = $oksigen."<td>"."$data[oksigen]"."</td>";
		$ventilator = $ventilator."<td>"."$data[ventilator]"."</td>";
		$transfusi = $transfusi."<td>"."$data[transfusi]"."</td>";
		$cairan = $cairan."<td>"."$data[cairan]"."</td>";
		$obat_oral = $obat_oral."<td>"."$data[obat_oral]"."</td>";
		$obat_injeksi = $obat_injeksi."<td>"."$data[obat_injeksi]"."</td>";

		$oral = $oral."<td>"."$data[oral]"."</td>";
		$intraveneous = $intraveneous."<td>"."$data[intraveneous]"."</td>";
		$icairan = $icairan."<td>"."$data[icairan]"."</td>";
		$gastric = $gastric."<td>"."$data[gastric]"."</td>";
		$transfusion = $transfusion."<td>"."$data[transfusion]"."</td>";
		$am = $am."<td>"."$data[am]"."</td>";
		$ilain = $ilain."<td>"."$data[ilain]"."</td>";
		$tilain = $tilain."<td>"."$data[tilain]"."</td>";


		$ngt = $ngt."<td>"."$data[ngt]"."</td>";
		$urine = $urine."<td>"."$data[urine]"."</td>";
		$drain = $drain."<td>"."$data[drain]"."</td>";
		$defecation = $defecation."<td>"."$data[defecation]"."</td>";
		$iwl = $iwl."<td>"."$data[iwl]"."</td>";
		$olain = $olain."<td>"."$data[olain]"."</td>";
		$tolain = $tolain."<td>"."$data[tolain]"."</td>";

		$suction = $suction."<td>"."$data[suction]"."</td>";
		$mobilisasi = $mobilisasi."<td>"."$data[mobilisasi]"."</td>";
		$bcairan = $bcairan."<td>"."$data[bcairan]"."</td>";
		
		$oral2 = $data[oral];
		$intraveneous2 = $data[intraveneous];
		$icairan2 = $data[icairan];
		$gastric2 = $data[gastric];
		$transfusion2 = $data[transfusion];
		$am2 = $data[am];
		$ilain2 = $data[ilain];
		// $tilain2 += $data[tilain];
		$tilain2 = $oral2+$intraveneous2+$icairan2+$gastric2+$transfusion2+$am2+$ilain2;

		$ngt2 = $data[ngt];
		$urine2 = $data[urine];
		$drain2 = $data[drain];
		$defecation2 = $data[defecation];
		$iwl2 = $data[iwl];
		$olain2 = $data[olain];
		// $tolain2 += $data[tolain];
		$tolain2  = $ngt2+$urine2+$drain2+$defecation2+$iwl2+$olain2;

		$jumlah_cairan = $jumlah_cairan."
		<td>
		<table>
		<tr>
		<td>INPUT
		<table>
		<tr>
		<td>ORAL</td>
		<td>$oral2</td>
		</tr>
		<tr>
		<td>INTRAVENEOUS</td>
		<td>$intraveneous2</td>
		</tr>
		<tr>
		<td>CAIRAN</td>
		<td>$icairan2</td>
		</tr>
		<tr>
		<td>GASTRIC FEEDING</td>
		<td>$gastric2</td>
		</tr>
		<tr>
		<td>TRANSFUSION</td>
		<td>$transfusion2</td>
		</tr>
		<tr>
		<td>AM</td>
		<td>$am2</td>
		</tr>
		<tr>
		<td>LAIN-LAIN</td>
		<td>$ilain2</td>
		</tr>
		<tr>
		<td>TOTAL</td>
		<td>$tilain2</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td>
		OUTPUT
		<table>
		<tr>
		<td>URINE</td>
		<td>$urine2</td>
		</tr>
		<tr>
		<td>NGT/MUNTAH</td>
		<td>$ngt2</td>
		</tr>
		<tr>
		<td>DRAIN</td>
		<td>$drain2</td>
		</tr>	
		<tr>
		<td>DEFECATION</td>
		<td>$defecation2</td>
		</tr>
		<tr>
		<td>IWL</td>
		<td>$iwl2</td>
		</tr>
		<tr>
		<td>LAIN-LAIN</td>
		<td>$olain2</td>
		</tr>
		<tr>
		<td>TOTAL</td>
		<td>$tolain2</td>
		</tr>

		</table>
		</td>
		</tr>
		</table>
		</td>
		";
	}
	$i+=1;

}

$qi="SELECT noreg FROM ERM_RI_OBSERVASI_ICU_HEADER where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_OBSERVASI_ICU_HEADER(noreg,userid,tglentry,tgl) values ('$noreg','$user','$tgl','$tgl')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tgl, 23) as tgl
	FROM ERM_RI_OBSERVASI_ICU_HEADER
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$tgl = $de['tgl'];
	$ich1 = $de['ich1'];
	$ich2= $de['ich2'];
	$ich3= $de['ich3'];
	$ich4= $de['ich4'];
	$ich5= $de['ich5'];
	$ich6= $de['ich6'];
	$ich7= $de['ich7'];
	$ich8= $de['ich8'];
	$ich9= $de['ich9'];
	$ich10= $de['ich10'];
	$ich11= $de['ich11'];
	$ich12= $de['ich12'];
	$ich13= $de['ich13'];
	$ich14= $de['ich14'];
	$ich15= $de['ich15'];
	$ich16= $de['ich16'];
	$ich17= $de['ich17'];
	$ich18= $de['ich18'];
	$ich19= $de['ich19'];
	$ich20= $de['ich20'];
}


//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar,CONVERT(VARCHAR, tglmasuk, 20) as tglmasuk2
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];
$tglmasuk2 = $data3[tglmasuk2];

//kamar
$qc2="SELECT KAMAR FROM ARM_PERIKSA WHERE (NOREG = '$noreg') and kamar <> ''";
$hdc2  = sqlsrv_query($conn, $qc2);        
$dhdc2 = sqlsrv_fetch_array($hdc2, SQLSRV_FETCH_ASSOC);         
$kamar = $dhdc2[KAMAR];

//lama perawatan
$q4       = "
SELECT DATEDIFF(day, '$tglmasuk2', '$tgl') AS 'Duration'  
FROM dbo.ARM_PERIKSA 
where noreg='$noreg' and tglkeluar is not null";
$hasil4  = sqlsrv_query($conn, $q4);  
$data4    = sqlsrv_fetch_array($hasil4, SQLSRV_FETCH_ASSOC);                      
$lamaperawatan = $data4[Duration];

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<link rel="stylesheet" href="css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script>
		CKEDITOR.replace('editor1');
		CKEDITOR.config.width="100%";
		CKEDITOR.config.height="500px"
	</script>
	<script src="linechartjs/js/Chart.js"></script>
	<style type="text/css">
		.container {
			/*width: 40%;*/
			margin: 15px auto;
		}
	</style>

	<!-- Jqueri autocomplete untuk procedure !!! -->
	<link rel="stylesheet" href="jquery-ui.css">
	<script src="jquery-1.10.2.js"></script>
	<script src="jquery-ui.js"></script>

	<script>
		$(function() {
			$("#diag_keperawatan1").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_diagkeperawatan.php', //your                         
                        dataType: 'json',
                        data: {
                        	postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                            	response ($.map(data.response, function (item) {
                            		return {
                            			value: item.diagnosa_keperawatan + ' - ' + item.diagnosa_nama
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
			$("#dokter1").autocomplete({
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
			$("#dokter2").autocomplete({
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

	<body onload="document.myForm.pasien_mcu.focus();">
		<font size='2px'>
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<br>
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<button type='submit' name='print' value='print' class="btn btn-info btn-sm" type="button"><i class="bi bi-printer-fill"></i></button>
				&nbsp;&nbsp;
				<a href='icu.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success btn-sm'><i class="bi bi-plus-circle "></i> Tambah Data Observasi</a>
				<!-- &nbsp;&nbsp;
				<a href='listobservasi_icu.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success btn-sm'><i class="bi bi-x-circle"></i> Edit Data</a>
			-->				<br>
			<br>
			&nbsp;&nbsp;
			<br>
			<br>
			<div class="row">
				<div class="col-12">
					<?php echo 'NIK : '.$noktp.'<br>'; ?>                   
					<?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
					<?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
				</div>
			</div>

			<hr>

			<div class="row">
				<div class="col-12 center">
					<b center><font size='3px'>&nbsp;UNIT PERAWATAN INTENSIF</font></b>
				</div>
			</div>
			<table  class="table table-bordered">
				<tr>
					<td>
						<table>
							<tr>
								<td>TGL.MRS</td>
								<?php 
								if(empty($ich1)){
									$ich1 = $tglmasuk;
								}
								if(empty($ich2)){
									$ich2 = $tgl2;
								}
								if(empty($ich3)){
									$ich3 = $lamaperawatan.' hari';;
								}
								if(empty($ich4)){
									$ich4 = 'Kamar : '.$kamar;
								}
								?>
								<td><input class="form-control-sm" name="ich1" value="<?php echo $ich1;?>" id="" type="text"></td>
							</tr>
							<tr>
								<td>TANGGAL</td>
								<td><input class="form-control-sm" name="ich2" value="<?php echo $ich2;?>" id="" type="text"></td>
							</tr>
							<tr>
								<td>HARI KE</td>
								<td><input class="form-control-sm" name="ich3" value="<?php echo $ich3;?>" id="" type="text"></td>
							</tr>
							<tr>
								<td>RUANG/KLAS</td>
								<td><input class="form-control-sm" name="ich4" value="<?php echo $ich4;?>" id="" type="text"></td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>DIAGNOSA</td>
								<td>
									<!-- <input class="form-control-sm" name="ich5" value="<?php echo $ich5;?>" id="diag_keperawatan1" type="text"> -->
									<textarea name= "ich5" id="" style="min-width:350px; min-height:130px;"><?php echo $ich5;?></textarea>
								</td>
							</tr>
							<tr>
								<td>BB</td>
								<td><input class="form-control-sm" name="ich6" value="<?php echo $ich6;?>" id="" type="text"></td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>NAMA OPERASI</td>
								<td><input class="form-control-sm" name="ich7" value="<?php echo $ich7;?>" id="" type="text"></td>
							</tr>
							<tr>
								<td>TGL OPERASI</td>
								<td><input class="form-control-sm" name="ich8" value="<?php echo $ich8;?>" id="" type="text"></td>
							</tr>
							<tr>
								<td>HARI KE</td>
								<td><input class="form-control-sm" name="ich9" value="<?php echo $ich9;?>" id="" type="text"></td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>DPJP UTAMA</td>
								<td>
									<!-- <input class="form-control-sm" name="ich10" value="<?php echo $ich10;?>" id="dokter1" type="text"> -->
									<textarea name= "ich10" id="" style="min-width:350px; min-height:130px;"><?php echo $ich10;?></textarea>
								</td>
							</tr>
							<tr>
								<td>DPJP</td>
								<td>
									<!-- <input class="form-control-sm" name="ich11" value="<?php echo $ich11;?>" id="dokter2" type="text"> -->
									<textarea name= "ich11" id="" style="min-width:350px; min-height:130px;"><?php echo $ich11;?></textarea>
								</td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>
									S & I LOSS 70 ML/M2<br>
									1` | -+ 10 %<br>
									WATER METAB 350 ML/M2<br>
								</td>
							</tr>
							<tr>
								<td>
									GOL DARAH<br>
									Rh
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<div class="col-12">
				<button type="submit" name="simpan" class="btn btn-info btn-sm" onfocus="nextfield ='done';"><i class="bi bi-save"></i>
					update
				</button> 
			</div>
			<br>					
			<table  class="table table-bordered">
				<tr>
					<td>
						<div class="row">
							<div class="col-3">
								<canvas id="sistole" width="100" height="100"></canvas>
							</div>
							<div class="col-3">
								<canvas id="diastole" width="100" height="100"></canvas>
							</div>
							<div class="col-3">
								<canvas id="nadi" width="100" height="100"></canvas>
							</div>
						</div>
						<div class="row">
							<div class="col-3">
								<canvas id="suhu" width="100" height="100"></canvas>
							</div>
							<div class="col-3">
								<canvas id="cvp" width="100" height="100"></canvas>
							</div>
						</div>
					</td>
				</tr>
			</table>

			<table  class="table table-bordered">
				<tr>
					<td width='1%'>EDIT / HAPUS</td>
					<?php echo $id_obs;?>
				</tr>
				<tr>
					<td width='1%'>SPO2</td>
					<?php echo $spo2;?>
				</tr>
				<tr>
					<td>RR</td>
					<?php echo $rr;?>
				</tr>
				<tr>
					<td>GCS</td>
					<?php echo $gcs;?>
				</tr>
				<tr>
					<td>REFLEK PUPIL</td>
					<?php echo $pupil;?>
				</tr>
				<tr>
					<td>SKORE NYERI</td>
					<?php echo $nyeri;?>
				</tr>
				<tr>
					<td>PARAF</td>
					<?php echo $useri;?>
				</tr>
				<tr>
					<td>OKSIGEN</td>
					<?php echo $oksigen;?>
				</tr>
				<tr>
					<td>VENTILATOR</td>
					<?php echo $ventilator;?>
				</tr>
				<tr>
					<td>TRANSFUSI</td>
					<?php echo $transfusi;?>
				</tr>
				<tr>
					<td>CAIRAN</td>
					<?php echo $cairan;?>
				</tr>
				<tr>
					<td>OBAT ORAL</td>
					<?php echo $obat_oral;?>
				</tr>
				<tr>
					<td>OBAT INJEKSI</td>
					<?php echo $obat_injeksi;?>
				</tr>

				<tr>
					<td>JUMLAH CAIRAN</td>
					<?php echo $jumlah_cairan;?>
				</tr>

				<tr>
					<td>BALANCE CAIRAN</td>
					<?php echo $bcairan;?>
				</tr>

				<tr>
					<td>SUCTION</td>
					<?php echo $suction;?>
				</tr>
				<tr>
					<td>MOBILISASI</td>
					<?php echo $mobilisasi;?>
				</tr>
			</table>

		</font>
	</form>
</font>
</body>
</div>

<?php 

if (isset($_POST["simpan"])) {

	$ich1	= $_POST["ich1"];
	$ich2	= $_POST["ich2"];
	$ich3	= $_POST["ich3"];
	$ich4	= $_POST["ich4"];
	$ich5	= $_POST["ich5"];
	$ich6	= $_POST["ich6"];
	$ich7	= $_POST["ich7"];
	$ich8	= $_POST["ich8"];
	$ich9	= $_POST["ich9"];
	$ich10	= $_POST["ich10"];
	$ich11	= $_POST["ich11"];
	$ich12	= $_POST["ich12"];
	$ich13	= $_POST["ich13"];
	$ich14	= $_POST["ich14"];
	$ich15	= $_POST["ich15"];
	$ich16	= $_POST["ich16"];
	$ich17	= $_POST["ich17"];
	$ich18	= $_POST["ich18"];
	$ich19	= $_POST["ich19"];
	$ich20	= $_POST["ich20"];

	$q  = "update ERM_RI_OBSERVASI_ICU_HEADER set
	ich1	='$ich1',
	ich2	='$ich2',
	ich3	='$ich3',
	ich4	='$ich4',
	ich5	='$ich5',
	ich6	='$ich6',
	ich7	='$ich7',
	ich8	='$ich8',
	ich9	='$ich9',
	ich10	='$ich10',
	ich11	='$ich11'
	where noreg='$noreg'
	";

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


}

if (isset($_POST["print"])) {
	echo "
	<script>
	window.print();
	</script>
	";
}

?>




<script  type="text/javascript">
	var ctx = document.getElementById("sistole").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK TENSI SISTOLIK",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#808080",
			borderColor: "#808080",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $sistole; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>

<script  type="text/javascript">
	var ctx = document.getElementById("diastole").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK TENSI DIASTOLIK",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#808080",
			borderColor: "#808080",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $diastole; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>

<script  type="text/javascript">
	var ctx = document.getElementById("suhu").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK SUHU",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#29B0D0",
			borderColor: "#29B0D0",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $suhu; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>


<script  type="text/javascript">
	var ctx = document.getElementById("nadi").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK NADI",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#FF0000",
			borderColor: "#FF0000",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $nadi; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>


<script  type="text/javascript">
	var ctx = document.getElementById("cvp").getContext("2d");
	var data = {
		labels: [<?php echo $ket; ?>],
		datasets: [
		{
			label: "GRAFIK CVP",
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#2E8B57",
			borderColor: "#2E8B57",
			pointHoverBackgroundColor: "#29B0D0",
			pointHoverBorderColor: "#29B0D0",
			data: [<?php echo $cvp; ?>],
		}
		]
	};

	var myBarChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			legend: {
				display: true
			},
			barValueSpacing: 20,
			scales: {
				yAxes: [{
					ticks: {
						min: 0,
					}
				}],
				xAxes: [{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					}
				}]
			}
		}
	});
</script>
