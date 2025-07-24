<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include ("mode.php");

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$aresep = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

$nama	= $data2[nama];
$alamat	= $data2[alamatpasien];
$tgllahir	= $data2[tgllahir];


$qu="SELECT noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];

$noreg = trim($noreg);

//cek kodeperawat
$qu="SELECT top(1)kodedokter FROM ERM_SOAP where userid='$user' order by id desc";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$kodedokter = $d1u['kodedokter'];
$kodedokter = trim($kodedokter);

$qu="SELECT dpjp FROM  V_ERM_RI_DPJP_ASESMEN where noreg='$noreg' and dpjp is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$dpjp = $d1u['dpjp'];

//ambil resep

$qu3="SELECT diet as diet  FROM ERM_DIET where noreg='$noreg'";
$h1u3  = sqlsrv_query($conn, $qu3);        
$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
$diet = $d1u3['diet'];

//alergi obat
$qalergi="
select TOP(100) userid,obat,gejala,tingkat_keparahan,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
from ERM_RI_ALERGI
where noreg='$noreg' order by id desc
";
$hasil_alergi  = sqlsrv_query($conn, $qalergi);  
$no_alergi=1;
$objective = "Riwayat Alergi Obat :\n";

while   ($data_alergi = sqlsrv_fetch_array($hasil_alergi,SQLSRV_FETCH_ASSOC)){ 
	$no_alergi+=1;

	$row3 = explode('-',$data_alergi['obat']);
	$data_alergi_obat  = trim($row3[1]);

	if(empty($data_alergi_obat)){
		$d_alergi  = trim($row3[0]);
	}else{
		$d_alergi = $data_alergi_obat;
	}

	$lalergi=$d_alergi.' - '.$data_alergi['gejala'];
	$objective = $objective.$lalergi."\n";
}

//rekonsiliasi obat
$qrekon="
select TOP(100) userid,obat,frekuensi,lama,tindak_lanjut,perubahan_aturan_pakai,CONVERT(VARCHAR, tglentry, 25) as tglentry,id
from ERM_RI_OBAT_ADMISI
where noreg='$noreg' order by id desc
";
$hasil_rekon  = sqlsrv_query($conn, $qrekon);  
$no_rekon=1;
$objective = $objective."\nRiwayat penggunaan Obat :\n";

while   ($data_rekon = sqlsrv_fetch_array($hasil_rekon,SQLSRV_FETCH_ASSOC)){ 
	$no_rekon+=1;

	$row4 = explode('-',rtrim($data_rekon['obat']));
	$data_rekon_obat  = trim($row4[1]);

	if(empty($data_rekon_obat)){
		$data_rekons  = trim($row4[0]);
	}else{
		$data_rekons  = $data_rekon_obat;
	}

	$lrekon=$data_rekons.', '.trim($data_rekon['frekuensi']).', '.trim($data_rekon['tindak_lanjut']);
	$objective = $objective.$lrekon."\n";
}

$objective = $objective."\nHasil Lab : \n";
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
			$("#apoteker").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_apoteker.php', //your                         
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

	<script>
		$(function() {
			$("#dpjp").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                	$.ajax({
                		type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_dpjp.php', //your                         
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

	<body onload="document.myForm.ku.focus();" style="background-color: #F5EFFF;">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='list_soap.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>

				<div class="row">
					<div class="col-12">
						<?php 
						include "header_soap.php";
						?>
					</div>
				</div>


				<div class="row">
					<div class="col-12 text-center">
						<b>INPUT SOAP / CPPT APOTEKER</b><br>
					</div>
				</div>

				<div class="row">
					<div class="col-6">
						<b><font size='5' color='green'>(S)</font>Subject</b>
						<br>
						<?php 
						if(empty($subject)){?>
							<textarea name= "subject0" id="" style="min-width:100%; min-height:50px;" placeholder="Keluhan Pasien"><?php echo $subject0;?></textarea>
							<input type='checkbox' name='subject1' value='DM'>DM&nbsp;&nbsp;
							<input type='checkbox' name='subject2' value='Hipertensi'>Hipertensi&nbsp;&nbsp;
							<input type='checkbox' name='subject3' value='PJK'>PJK&nbsp;&nbsp;
							<input type='checkbox' name='subject4' value='Lainnya'>Lainnya<br>
							<textarea name= "subject5" id="" style="min-width:100%; min-height:50px;" placeholder="Ket Keluhan Penyakit Lainnnya"><?php echo $subject5;?></textarea>
							<?php
						}else{?>
							<textarea name= "subject" id="" style="min-width:100%; min-height:200px;"><?php echo $subject;?></textarea>
						<?php }
						?>


					</div>
					<div class="col-6">
						<b><font size='5' color='green'>(O)</font>Objective</b>
						<br>
						<?php 
						if(empty($objective)){
							$objective = "Riwayat Alergi Obat:\nRiwayat penggunaan Obat (ambil dari form Rekonsiliasi Obat dan Rekonsiliasi transfer)\n1.  Amlodipin 10 mg pagi lanjut aturan pakai sama\n2.  Candesartan 16 mg malam lanjut aturan pakai berubah dosis 8 mg\n3.  Asam Folat stop\nHasil Lab :\n
							";
						}
						?>
						<textarea name= "objective" id="" style="min-width:100%; min-height:200px;"><?php echo $objective;?></textarea>
					</div>
				</div>

				<div class="row">
					<div class="col-6">
						<b><font size='5' color='green'>(A)</font>Assesment</b>
						<br>
						<?php 
						if(empty($assesment)){
							// $assesment = "PCNE (Pharmaceutical Care Network Europe)\nProblems 	: [&check;] P1	: Treatment Effectiveness \n[&check;] P2	: Treatment Safety\n[&check;] P3 : Other\nCause : [&check;] C1 : Drug Selection\n [&check;] C2	: Drug Form\n[&check;] C3	: Dose Selection\n[&check;] C4	: Drug Treatment Duration\n [&check;] C5	: Dispensing\n[&check;] C6	: Drug Use Process\n[&check;] C7	: Patient Related\n[&check;] C8	: Patient Transfer Related\n[&check;] C9	: Other\n[&check;] No Other Drug Related Problem\n";
							?>
							<br>
							Problems :<br>
							<input type='checkbox' name='ass1' value='P1 : Treatment Effectiveness'>P1 : Treatment Effectiveness<br>
							<input type='checkbox' name='ass2' value='P2 : Treatment Safety'>P2 : Treatment Safety<br>
							<input type='checkbox' name='ass3' value='P3 : Other'>P3 : Other<br>
							<br>
							Cause :<br>
							<input type='checkbox' name='ass4' value='C1 : Drug Selection'>C1 : Drug Selection<br>
							<input type='checkbox' name='ass5' value='C2 : Drug Form'>C2 : Drug Form<br>
							<input type='checkbox' name='ass6' value='C3 : Dose Selection'>C3 : Dose Selection<br>
							<input type='checkbox' name='ass7' value='C4 : Drug Treatment Duration'>C4 : Drug Treatment Duration<br>
							<input type='checkbox' name='ass8' value='C5 : Dispensing'>C5 : Dispensing<br>
							<input type='checkbox' name='ass9' value='C6 : Drug Use Process'>C6 : Drug Use Process<br>
							<input type='checkbox' name='ass10' value='C7 : Patient Related'>C7 : Patient Related<br>
							<input type='checkbox' name='ass11' value='C8 : Patient Transfer Related'>C8 : Patient Transfer Related<br>
							<input type='checkbox' name='ass12' value='C9 : Other'>C9 : Other<br>
							<br>
							<input type='checkbox' name='ass13' value='No Other Drug Related Problem'>No Other Drug Related Problem<br>
							<br>
							<textarea name= "ass14" id="" style="min-width:100%; min-height:30px;" placeholder=""></textarea>

							<?php
						}
						?>
						<br>
					</div>
					<div class="col-6">
						<b><font size='5' color='green'>(P)</font>Plan</b>
						<br>
						<?php 
						if(empty($plan)){
							// $plan = "\n[&check;] \n[&check;]  ...............................................\n[&check;] \n[&check;] \n[&check;] \n[&check;] \n[&check;] \n[&check;] \n[&check;]  .................................\n[&check;]  .................................\n[&check;]  .......................................\n: .............................................";
							?>
							<input type='checkbox' name='plan1' value='Tidak ada Intervensi'>Tidak ada Intervensi<br>
							<input type='checkbox' name='plan2' value='Perhatikan Riwayat Alergi Obat pasien, hindari obat yang menimbulkan alergi pada pasien'>Perhatikan Riwayat Alergi Obat pasien, hindari obat yang menimbulkan alergi pada pasien<br>
							<input type='checkbox' name='plan3' value='Lanjutkan Terapi Sesuai Advice Dokter'>Lanjutkan Terapi Sesuai Advice Dokter<br>
							<input type='checkbox' name='plan4' value='Monitoring Efek Samping Obat seperti'>Monitoring Efek Samping Obat seperti : 
							<input type='text' name='plan5' value='' style="min-width:30%; min-height:10px;" placeholder=""><br>
							<br>
							Edukasi tentang :<br>
							<input type='checkbox' name='plan6' value='Cara Penggunaan Obat'>Cara Penggunaan Obat<br>
							<input type='checkbox' name='plan7' value='Reaksi Alergi Obat'>Reaksi Alergi Obat<br>
							<input type='checkbox' name='plan8' value='Obat yang digunakan selama rawat inap'>Obat yang digunakan selama rawat inap<br>
							<input type='checkbox' name='plan9' value='Interaksi antar obat dengan obat atau obat dengan makanan'>Interaksi antar obat dengan obat atau obat dengan makanan<br>
							<input type='checkbox' name='plan10' value='Kepatuhan penggunaan obat kronis'>Kepatuhan penggunaan obat kronis<br>
							<input type='checkbox' name='plan11' value='Lainnya'>Lainnya :
							<input type='text' name='plan12' value='' style="min-width:60%; min-height:10px;" placeholder=""><br>
							<input type='checkbox' name='plan13' value='Lainnya'>Interaksi antar obat<br>
							<textarea name= "plan14" id="" style="min-width:100%; min-height:30px;" placeholder=""></textarea>
							<input type='checkbox' name='plan15' value='Lainnya'>Lapor Dokter:<br>
							<textarea name= "plan16" id="" style="min-width:100%; min-height:30px;" placeholder=""></textarea>
							<input type='checkbox' name='plan17' value='Lainnya'>Advice :<br>
							<textarea name= "plan18" id="" style="min-width:100%; min-height:30px;" placeholder=""></textarea>
							<br>
							<i>Instruksi PPA</i>
							<textarea class="form-control" name="instruksi" style="min-width:100; min-height:30;" onfocus="nextfield ='dpjp';">
								<?php echo $instruksi;?>
							</textarea>
							<?php	
						}
						?>
						<!-- <textarea name= "plan" id="" style="min-width:100%; min-height:300px;"><?php echo $plan;?></textarea> -->
					</div>
				</div>

				<div class="row">
					<div class="col-6">
						<i>DPJP</i></br>
						<input class="form-control" name="dpjp" value="<?php echo $dpjp;?>" id="dpjp" type="text" size='50' onfocus="nextfield ='simpan';" placeholder="Isikan Nama Dokter DPJP">
					</div>
					<div class="col-6">
						<i>Petugas Entry SOAP</i></br>
						<input class="form-control" name="kodedokter" value="<?php echo $kodedokter;?>" id="apoteker" type="text" size='50' onfocus="nextfield ='pass';" placeholder="Isikan Nama Dokter atau Perawat yang Entry SOAP">
					</div>
				</div>

				<hr>
				<div class="row">
					<div class="col-12 align-self-center">
						<button type="submit" name="simpan" class="btn btn-danger btn-sm" onfocus="nextfield ='done';"><i class="bi bi-floppy"></i>
						simpan</button> 
					</div>
				</div>
				<br><br><br>
			</form>
		</font>
	</body>
</div>

<?php

if (isset($_POST["simpan"])) {

	$subject0	= trim($_POST["subject0"]);	
	$subject1	= trim($_POST["subject1"]);
	$subject2	= trim($_POST["subject2"]);
	$subject3	= trim($_POST["subject3"]);
	$subject4	= trim($_POST["subject4"]);
	$subject5	= trim($_POST["subject5"]);

	if(!empty($subject0)){
		$subject0=$subject0.' , ';
	}
	if(!empty($subject2)){
		$subject2=' ,'.$subject2;
	}
	if(!empty($subject3)){
		$subject3=' ,'.$subject3;
	}
	if(!empty($subject4)){
		$subject4=' ,'.$subject4.' : ';
	}

	$subject	= 
	'Keluhan Pasien : '.$subject0."\n".
	'Riwayat Penyakit Pasien : '.$subject1.$subject2.$subject3.$subject4.$subject5;
	;

	$objective	= trim($_POST["objective"]);

	$ass1	= trim($_POST["ass1"]);	
	$ass2	= trim($_POST["ass2"]);	
	$ass3	= trim($_POST["ass3"]);	
	$ass4	= trim($_POST["ass4"]);	
	$ass5	= trim($_POST["ass5"]);	
	$ass6	= trim($_POST["ass6"]);	
	$ass7	= trim($_POST["ass7"]);	
	$ass8	= trim($_POST["ass8"]);	
	$ass9	= trim($_POST["ass9"]);	
	$ass10	= trim($_POST["ass10"]);	
	$ass11	= trim($_POST["ass11"]);	
	$ass12	= trim($_POST["ass12"]);	
	$ass13	= trim($_POST["ass13"]);	
	$ass14	= trim($_POST["ass14"]);	

	if(!empty($ass2)){
		$ass2="\n".$ass2;
	}
	if(!empty($ass3)){
		$ass3="\n".$ass3;
	}
	if(!empty($ass4)){
		$ass4="\n".$ass4;
	}
	if(!empty($ass5)){
		$ass5="\n".$ass5;
	}
	if(!empty($ass6)){
		$ass6="\n".$ass6;
	}
	if(!empty($ass7)){
		$ass7="\n".$ass7;
	}
	if(!empty($ass8)){
		$ass8="\n".$ass8;
	}
	if(!empty($ass9)){
		$ass9="\n".$ass9;
	}
	if(!empty($ass10)){
		$ass10="\n".$ass10;
	}
	if(!empty($ass11)){
		$ass11="\n".$ass11;
	}
	if(!empty($ass12)){
		$ass12="\n".$ass12;
	}
	if(!empty($ass13)){
		$ass13="\n".$ass13;
	}
	if(!empty($ass14)){
		$ass14="\n".$ass14;
	}


	// $assesment	= trim($_POST["assesment"]);



	if($ass13){
		$assesment	= 
		'Cause : '.$ass4.$ass5.$ass6.$ass7.$ass8.$ass9.$ass10.$ass11.$ass12."\n".
		$ass13.$ass14
		;
	}else{
		$assesment	= 
		'Problems : '.$ass1.$ass2.$ass3."\n".
		'Cause : '.$ass4.$ass5.$ass6.$ass7.$ass8.$ass9.$ass10.$ass11.$ass12."\n".
		$ass13.$ass14
		;
	}

	$plan1	= trim($_POST["plan1"]);	
	$plan2	= trim($_POST["plan2"]);	
	$plan3	= trim($_POST["plan3"]);	
	$plan4	= trim($_POST["plan4"]);	
	$plan5	= trim($_POST["plan5"]);	
	$plan6	= trim($_POST["plan6"]);	
	$plan7	= trim($_POST["plan7"]);	
	$plan8	= trim($_POST["plan8"]);	
	$plan9	= trim($_POST["plan9"]);	
	$plan10	= trim($_POST["plan10"]);	
	$plan11	= trim($_POST["plan11"]);	
	$plan12	= trim($_POST["plan12"]);	
	$plan13	= trim($_POST["plan13"]);	
	$plan14	= trim($_POST["plan14"]);	
	$plan15	= trim($_POST["plan15"]);	
	$plan16	= trim($_POST["plan16"]);	
	$plan17	= trim($_POST["plan17"]);	
	$plan18	= trim($_POST["plan18"]);	

	if(!empty($plan2)){
		$plan2="\n".$plan2;
	}
	if(!empty($plan3)){
		$plan3="\n".$plan;
	}
	if(!empty($plan4)){
		$plan4="\n".$plan4;
	}
	if(!empty($plan5)){
		$plan5="\n".$plan5;
	}
	if(!empty($plan6)){
		$plan6=''.$plan6;
	}
	if(!empty($plan7)){
		$plan7="\n".$plan7;
	}
	if(!empty($plan8)){
		$plan8="\n".$plan8;
	}
	if(!empty($plan9)){
		$plan9="\n".$plan9;
	}
	if(!empty($plan10)){
		$plan10="\n".$plan10;
	}
	if(!empty($plan11)){
		$plan11="\n".$plan11;
	}
	if(!empty($plan12)){
		$plan12="\n".$plan12;
	}
	if(!empty($plan13)){
		$plan13="\n".$plan13;
	}
	if(!empty($plan14)){
		$plan14="\n".$plan14;
	}
	if(!empty($plan15)){
		$plan15="\n".$plan15;
	}
	if(!empty($plan16)){
		$plan16="\n".$plan16;
	}
	if(!empty($plan17)){
		$plan17="\n".$plan17;
	}
	if(!empty($plan18)){
		$plan18="\n".$plan18;
	}

	// $planning	= trim($_POST["plan"]);
	$planning	=$plan1.$plan2.$plan3.$plan4.$plan5."\n".
	'Edukasi Tentang : '.$plan6.$plan7.$plan8.$plan9.$plan10.$plan11.$plan12."\n".
	'Interaksi antar obat : '.$plan13.$plan14.$plan15."\n".
	'Lapor Dokter : '.$plan16."\n".
	'Advice : '.$plan17.$plan18;

	$dpjp = trim($_POST["dpjp"]);
	$row2 = explode('-',$dpjp);
	$dpjp  = trim($row2[0]);

	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);

	$instruksi = trim($_POST["instruksi"]);

	$lanjut="Y";

	if(empty($dpjp)){
		$eror='DPJP Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($kodedokter)){
		$eror='apoteker Tidak Boleh Kosong !!!';
		$lanjut='T';
	}


	if($lanjut == 'Y'){
		$q  = "insert into ERM_SOAP
		(norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry,instruksi,dpjp) 
		values 
		('$norm','$noreg','$tglinput','$sbu','$kodeunit','$kodedokter','$subject','$objective','$assesment','$planning','$user','$tglinput','$instruksi','$dpjp')";
		$hs = sqlsrv_query($conn,$q);

		$qu="SELECT top(1)id FROM ERM_SOAP where noreg='$noreg' order by id desc";
		$h1u  = sqlsrv_query($conn, $qu);        
		$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
		$idsoap = $d1u['id'];

		echo	$q  = "insert into ERM_RI_SOAP
		(
		norm,noreg,
		tglupdate,
		userid,
		ku,
		rps,
		anamnesa,
		rpd,
		alergi,
		assesmen,
		aplan,
		eye,
		verbal,
		movement,
		tekanan_darah,
		nadi,
		suhu,
		frekuansi_pernafasan,
		skala_nyeri,
		berat_badan,
		fisik_kepala,
		fisik_mata,
		fisik_tht,
		fisik_leher,
		fisik_paru,
		fisik_jantung,
		fisik_abdomen,
		fisik_ekstermitas,
		fisik_urogenital,
		status_lokalis,
		pemeriksaan_penunjang,
		ket_nadi,id_soap,
		antropometri,
		biokimia,
		fisik_klinis,
		asupan_makan,
		diagnosa_gizi,
		intervensi,
		monitoring
		) 
		values 
		(
		'$norm','$noreg',
		'$tglinput',
		'$user',
		'$subject',
		'$rps',
		'$objective',
		'$rpd',
		'$alergi',
		'$assesment',
		'$planning',
		'$eye',
		'$verbal',
		'$movement',
		'$tekanan_darah',
		'$nadi',
		'$suhu',
		'$frekuansi_pernafasan',
		'$skala_nyeri',
		'$berat_badan',
		'$fisik_kepala',
		'$fisik_mata',
		'$fisik_tht',
		'$fisik_leher',
		'$fisik_paru',
		'$fisik_jantung',
		'$fisik_abdomen',
		'$fisik_ekstermitas',
		'$fisik_urogenital',
		'$status_lokalis',
		'$pemeriksaan_penunjang',
		'$ket_nadi','$idsoap',
		'$antropometri',
		'$biokimia',
		'$fisik_klinis',
		'$asupan_makan',
		'$dsubjektif',
		'$intervensi',
		'$monitoring'
	)";
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

}else{
	echo "
	<script>
	alert('".$eror."');
	history.go(-1);
	</script>
	";

}

}



?>

