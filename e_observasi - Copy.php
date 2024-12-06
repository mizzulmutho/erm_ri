<?php 
// include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$id_observasi = $row[2]; 

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


$qe="
SELECT *,CONVERT(VARCHAR, tglinput, 20) as tglinput
FROM ERM_RI_OBSERVASI
where id='$id_observasi'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$tglinput = $de['tglinput'];

$td_sistolik= $de['td_sistolik'];
$td_diastolik= $de['td_diastolik'];
$suhu= $de['suhu'];
$nadi= $de['nadi'];
$spo2= $de['spo2'];
$pernafasan= $de['pernafasan'];

$ob1 = $de['ob1'];
$ob2= $de['ob2'];
$ob3= $de['ob3'];
$ob4= $de['ob4'];
$ob5= $de['ob5'];
$ob6= $de['ob6'];
$ob7= $de['ob7'];
$ob8= $de['ob8'];
$ob9= $de['ob9'];
$ob10= $de['ob10'];
$ob11= $de['ob11'];
$ob12= $de['ob12'];
$ob13= $de['ob13'];
$ob14= $de['ob14'];
$ob15= $de['ob15'];
$ob16= $de['ob16'];
$ob17= $de['ob17'];
$ob18= $de['ob18'];
$ob19= $de['ob19'];
$ob20= $de['ob20'];
$ob21= $de['ob21'];
$ob22= $de['ob22'];
$ob23= $de['ob23'];
$ob24= $de['ob24'];
$ob25= $de['ob25'];
$ob26= $de['ob26'];
$ob27= $de['ob27'];
$ob28= $de['ob28'];
$ob29= $de['ob29'];
$ob30= $de['ob30'];
$ob31= $de['ob31'];
$ob32= $de['ob32'];
$ob33= $de['ob33'];
$ob34= $de['ob34'];
$ob35= $de['ob35'];
$ob36= $de['ob36'];
$ob37= $de['ob37'];
$ob38= $de['ob38'];
$ob39= $de['ob39'];
$ob40= $de['ob40'];
$ob41= $de['ob41'];
$ob42= $de['ob42'];
$ob43= $de['ob43'];
$ob44= $de['ob44'];
$ob45= $de['ob45'];
$ob46= $de['ob46'];
$ob47= $de['ob47'];
$ob48= $de['ob48'];
$ob49= $de['ob49'];
$ob50= $de['ob50'];
$ob51= $de['ob51'];
$ob52= $de['ob52'];
$ob53= $de['ob53'];
$ob54= $de['ob54'];
$ob55= $de['ob55'];
$ob56= $de['ob56'];
$ob57= $de['ob57'];
$ob58= $de['ob58'];
$ob59= $de['ob59'];
$ob60= $de['ob60'];


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
				<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->
				<button type="submit" name="simpan" class="btn btn-info btn-sm" onfocus="nextfield ='done';"><i class="bi bi-save"></i>
				simpan</button> 
				&nbsp;&nbsp;
				<a href='listobservasi.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success btn-sm'><i class="bi bi-x-circle"></i> List</a>

				<br><br>
				<div class="row">
					<div class="col-12">
						<i class="bi bi-window-plus"> &nbsp; <b>INPUT DATA MONITORING</b></i>
					</div>
				</div>
				<hr>

				
				<div class="row">
					<div class="col-12">
						<table border='0' width="100%">
							<tr><td>Tgl/Jam Input </td>
								<td>: 
									<input class="" name="tglinput" value="<?php echo $tglinput;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
								</td>
							</tr>

							<tr>
								<td colspan="2"><b>Monitoring EWS</b></td>
							</tr>

							<tr><td>Kesadaran </td>
								<td>: &nbsp;&nbsp; 
									<input type='checkbox' name='ob1' value='Sadar' <?php if ($ob1=="Sadar"){echo "checked";}?> > Sadar
									<input type='checkbox' name='ob1' value='Tidak' <?php if ($ob1=="Tidak"){echo "checked";}?> > Tidak
									<input class="" name="ob2" value="<?php echo $ob2;?>" id="" type="text" size='30' placeholder="isikan jika tidak">
								</td>
							</tr>
							<tr><td>GCS </td>
								<td>: &nbsp;&nbsp; 
									E <input class="" name="ob3" value="<?php echo $ob3;?>" id="" type="text" size='2' placeholder=""> 
									V <input class="" name="ob4" value="<?php echo $ob4;?>" id="" type="text" size='2' placeholder="">
									M <input class="" name="ob5" value="<?php echo $ob5;?>" id="" type="text" size='2' placeholder="">
									Total <input class="" name="ob6" value="<?php echo $ob6;?>" id="" type="text" size='2' placeholder="">
								</td>
							</tr>
							<tr><td>TD Sistole </td>
								<td>: &nbsp;&nbsp; <input class="" name="td_sistolik" value="<?php echo $td_sistolik;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;mmHg</td>
							</tr>
							<tr><td>TD Diastole </td>
								<td>: &nbsp;&nbsp; <input class="" name="td_diastolik" value="<?php echo $td_diastolik;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;mmHg</td>
							</tr>
							<tr><td>Suhu </td><td>: &nbsp;&nbsp; <input class="" name="suhu" value="<?php echo $suhu;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;0C</td></tr>
							<tr><td>Nadi </td><td>: &nbsp;&nbsp; <input class="" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; x/menit</td></tr>
							<tr><td>Pernapasan </td><td>: &nbsp;&nbsp; <input class="" name="pernafasan" value="<?php echo $pernafasan;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp;  x/menit</td></tr>
							<tr><td>SpO2 </td><td>: &nbsp;&nbsp; <input class="" name="spo2" value="<?php echo $spo2;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; %</td></tr>
							<tr><td>Oksigen </td><td>: 
								<input type='checkbox' name='ob7' value='Ya' <?php if ($ob7=="Ya"){echo "checked";}?> > Ya
								<input type='checkbox' name='ob7' value='Tidak' <?php if ($ob7=="Tidak"){echo "checked";}?> > Tidak
								<input class="" name="ob8" value="<?php echo $ob8;?>" id="" type="text" size='30' placeholder="isikan jika tidak">
							</td>
						</tr>
						<tr><td>BB </td><td>: &nbsp;&nbsp; <input class="" name="ob9" value="<?php echo $ob9;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; kg</td></tr>
						<tr><td>TB </td><td>: &nbsp;&nbsp; <input class="" name="ob10" value="<?php echo $ob10;?>" id="" type="text" size='20' placeholder="">&nbsp;&nbsp; cm</td></tr>
						<tr><td>Skor EWS </td><td>: &nbsp;&nbsp; <input class="" name="ob11" value="<?php echo $ob11;?>" id="" type="text" size='20' placeholder="">&nbsp;>muncul otomatis sesuai skor&nbsp;</td></tr>



					</table>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12">
					<table border='0' width="100%">
						<tr>
							<td colspan="6"><b>Monitoring Cairan</b></td>
						</tr>
						<tr>
							<td colspan="6"><b>Input</b></td>
						</tr>
						<tr>
							<td>Infus </td>
							<td>
								:<input class="" name="ob12" value="<?php echo $ob12;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
							<td>Tetesan </td>
							<td>
								:<input class="" name="ob13" value="<?php echo $ob13;?>" id="" type="text" size='20' placeholder=""> /menit 
							</td>
							<td>Jam </td>
							<td>
								:<input class="" name="ob14" value="<?php echo $ob14;?>" id="" type="text" size='20' placeholder=""> (free text)
							</td>
						</tr>
						<tr>
							<td>Transfusi </td>
							<td>
								:<input class="" name="ob15" value="<?php echo $ob15;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
							<td>Tetesan </td>
							<td>
								:<input class="" name="ob16" value="<?php echo $ob16;?>" id="" type="text" size='20' placeholder=""> /menit 
							</td>
							<td>Jam </td>
							<td>
								:<input class="" name="ob17" value="<?php echo $ob17;?>" id="" type="text" size='20' placeholder=""> (free text)
							</td>
						</tr>
						<tr>
							<td>Makan </td>
							<td>
								:<input class="" name="ob18" value="<?php echo $ob18;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
							<td>Minum </td>
							<td>
								:<input class="" name="ob19" value="<?php echo $ob19;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
						</tr>
						<tr>
							<td colspan="6"><b>Output</b></td>
						</tr>
						<tr>
							<td>Muntah </td>
							<td>
								:<input class="" name="ob20" value="<?php echo $ob20;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
							<td>BAB </td>
							<td>
								:<input class="" name="ob21" value="<?php echo $ob21;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
							<td>Urine </td>
							<td>
								:<input class="" name="ob22" value="<?php echo $ob22;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
						</tr>
						<tr>
							<td>IWL </td>
							<td>
								:<input class="" name="ob23" value="<?php echo $ob23;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
							<td>NGT </td>
							<td>
								:<input class="" name="ob24" value="<?php echo $ob24;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
							<td>Drain </td>
							<td>
								:<input class="" name="ob25" value="<?php echo $ob25;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
						</tr>
						<tr>
							<td>Perdarahan </td>
							<td>
								:<input class="" name="ob26" value="<?php echo $ob26;?>" id="" type="text" size='20' placeholder=""> cc
							</td>
						</tr>
						<tr>
							<td><b>Balance Cairan</b>  </td>
							<td colspan="6">
								:<input class="" name="ob27" value="<?php echo $ob27;?>" id="" type="text" size='20' placeholder=""> (total input - total output)

							</td>
						</tr>
						<br>
						<tr>
							<td>GDA  </td>
							<td colspan="6">
								:<input class="" name="ob28" value="<?php echo $ob28;?>" id="" type="text" size='20' placeholder=""> 

							</td>
						</tr>


					</table>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12">
					<table border='0' width="100%">
						<tr>
							<td colspan="2"><b>Monitoring Nyeri</b></td>
						</tr>
						<tr><td>Skala Nyeri  </td>
							<td>: 
								<input type='checkbox' name='ob29' value='Tidak' <?php if ($ob29=="Tidak"){echo "checked";}?> > Tidak
								<input type='checkbox' name='ob29' value='Ya' <?php if ($ob29=="Ya"){echo "checked";}?> > Ya
								<input class="" name="ob30" value="<?php echo $ob30;?>" id="" type="text" size='30' placeholder="Ringan, Sedang, Berat">
							</td>
						</tr>
					</table>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12">
					<table border='0' width="100%">
						<tr>
							<td colspan="2"><b>Monitoring Risiko Jatuh</b></td>
						</tr>
					</table>

					<table>
						<tr>
							<td style="border: 1px solid;">Faktor Risiko</td>
							<td style="border: 1px solid;">skala</td>
							<td style="border: 1px solid;">poin</td>
							<td style="border: 1px solid;">Skor pasien</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Riwayat jatuh</td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob31' value='YA' <?php if ($ob31=="YA"){echo "checked";}?>>Ya</td>
							<td style="border: 1px solid;">25</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob31){
									echo $tjatuh1_skor='25';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob32' value='YA' <?php if ($ob32=="YA"){echo "checked";}?>>Tidak</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob32){
									echo $tjatuh2_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Diagnosis skunder (≥diagnosis medis)</td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob33' value='YA' <?php if ($ob33=="YA"){echo "checked";}?>>Ya</td>
							<td style="border: 1px solid;">15</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob33){
									echo $tjatuh3_skor='15';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob34' value='YA' <?php if ($ob34=="YA"){echo "checked";}?>>Tidak</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob34){
									echo $tjatuh4_skor='0';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Alat bantu</td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob35' value='YA' <?php if ($ob35=="YA"){echo "checked";}?>>Berpegangan pada perabot, kursi roda</td>
							<td style="border: 1px solid;">30</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob35){
									echo $tjatuh5_skor='30';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob36' value='YA' <?php if ($ob36=="YA"){echo "checked";}?>>Tongkat/walker</td>
							<td style="border: 1px solid;">15</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob36){
									echo $tjatuh6_skor='15';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob37' value='YA' <?php if ($ob37=="YA"){echo "checked";}?>>Tidak ada/perawat/tirah baring</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob37){
									echo $tjatuh7_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Terpasang infus/terapi intravena</td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob38' value='YA' <?php if ($ob38=="YA"){echo "checked";}?>>Ya</td>
							<td style="border: 1px solid;">20</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob38){
									echo $tjatuh8_skor='20';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob39' value='YA' <?php if ($ob39=="YA"){echo "checked";}?>>Tidak</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob39){
									echo $tjatuh9_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Gaya berjalan</td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob40' value='YA' <?php if ($ob40=="YA"){echo "checked";}?>>Kerusakan</td>
							<td style="border: 1px solid;">20</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob40){
									echo $tjatuh10_skor='20';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob41' value='YA' <?php if ($ob41=="YA"){echo "checked";}?>>Kelemahan</td>
							<td style="border: 1px solid;">10</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob41){
									echo $tjatuh11_skor='10';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob42' value='YA' <?php if ($ob42=="YA"){echo "checked";}?>>Normal /tirah baring/imobilisasi</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob42){
									echo $tjatuh12_skor='0';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Status mental</td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob43' value='YA' <?php if ($ob43=="YA"){echo "checked";}?>>Tidak konsisten dengan perintah</td>
							<td style="border: 1px solid;">15</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob43){
									echo $tjatuh13_skor='15';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='ob44' value='YA' <?php if ($ob44=="YA"){echo "checked";}?>>Sadar akan kemampuan diri sendiri</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($ob44){
									echo $tjatuh14_skor='0';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;" colspan="3">Total Skor</td>
							<td style="border: 1px solid;">
								<?php 
								echo $tjatuh_skor_total=$tjatuh1_skor+$tjatuh2_skor+$tjatuh3_skor+$tjatuh4_skor+$tjatuh5_skor+$tjatuh6_skor+$tjatuh7_skor+$tjatuh8_skor+$tjatuh9_skor+$tjatuh10_skor+$tjatuh11_skor+$tjatuh12_skor+$tjatuh13_skor+$tjatuh14_skor; 
								?>
							</td>
						</tr>

					</table>
					<br>

					[] >45 : Risiko tinggi ;         [] 25-44 : Risiko sedang ;         [] 0 -24 : Risiko rendah
					<br>
					<?php 
					echo "<h5>";
					echo "[".$tjatuh_skor_total."]";

					if($tjatuh_skor_total >= 0 and $tjatuh_skor_total <= 24){echo $tjatuh_skor_total="Risiko rendah";}
					if($tjatuh_skor_total >= 25 and $tjatuh_skor_total <= 44 ){echo $tjatuh_skor_total="Risiko sedang";}
					if($tjatuh_skor_total >= 45){echo $tjatuh_skor_total="Risiko tinggi";}
					echo "</h5>";
					?>

					<input class="" name="ob45" value="<?php echo $tjatuh_skor_total;?>" id="" type="text" size='30' placeholder="Ringan, Sedang, Berat">
					<br>
					
					<input type='submit' name='simpan' value='simpan'>&nbsp;


				</div>
			</div>





		</div>
		<br><br><br>
	</form>
</font>
</body>
</div>

<?php

if (isset($_POST["simpan"])) {

	$td_sistolik	= trim($_POST["td_sistolik"]);
	$td_diastolik	= trim($_POST["td_diastolik"]);
	$nadi	= trim($_POST["nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$pernafasan	= trim($_POST["pernafasan"]);
	$spo2	= trim($_POST["spo2"]);

	$tglinput	= trim($_POST["tglinput"]);
	$userinput	= trim($_POST["userinput"]);

	$ob1	= $_POST["ob1"];
	$ob2	= $_POST["ob2"];
	$ob3	= $_POST["ob3"];
	$ob4	= $_POST["ob4"];
	$ob5	= $_POST["ob5"];
	$ob6	= $_POST["ob6"];
	$ob7	= $_POST["ob7"];
	$ob8	= $_POST["ob8"];
	$ob9	= $_POST["ob9"];
	$ob10	= $_POST["ob10"];
	$ob11	= $_POST["ob11"];
	$ob12	= $_POST["ob12"];
	$ob13	= $_POST["ob13"];
	$ob14	= $_POST["ob14"];
	$ob15	= $_POST["ob15"];
	$ob16	= $_POST["ob16"];
	$ob17	= $_POST["ob17"];
	$ob18	= $_POST["ob18"];
	$ob19	= $_POST["ob19"];
	$ob20	= $_POST["ob20"];
	$ob21	= $_POST["ob21"];
	$ob22	= $_POST["ob22"];
	$ob23	= $_POST["ob23"];
	$ob24	= $_POST["ob24"];
	$ob25	= $_POST["ob25"];
	$ob26	= $_POST["ob26"];
	$ob27	= $_POST["ob27"];
	$ob28	= $_POST["ob28"];
	$ob29	= $_POST["ob29"];
	$ob30	= $_POST["ob30"];
	$ob31	= $_POST["ob31"];
	$ob32	= $_POST["ob32"];
	$ob33	= $_POST["ob33"];
	$ob34	= $_POST["ob34"];
	$ob35	= $_POST["ob35"];
	$ob36	= $_POST["ob36"];
	$ob37	= $_POST["ob37"];
	$ob38	= $_POST["ob38"];
	$ob39	= $_POST["ob39"];
	$ob40	= $_POST["ob40"];
	$ob41	= $_POST["ob41"];
	$ob42	= $_POST["ob42"];
	$ob43	= $_POST["ob43"];
	$ob44	= $_POST["ob44"];
	$ob45	= $_POST["ob45"];
	$ob46	= $_POST["ob46"];
	$ob47	= $_POST["ob47"];
	$ob48	= $_POST["ob48"];
	$ob49	= $_POST["ob49"];
	$ob50	= $_POST["ob50"];

	$lanjut="Y";


	if(empty($user)){
		$eror='User Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($td_sistolik)){
		$eror='Tensi Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if($lanjut == 'Y'){
		$q  = "update ERM_RI_OBSERVASI set
		td_sistolik	='$td_sistolik',
		td_diastolik	='$td_diastolik',
		nadi	='$nadi',
		suhu	='$suhu',
		pernafasan	='$pernafasan',
		spo2	='$spo2',
		skala_nyeri	='$skala_nyeri',
		bb	='$bb',
		tb	='$tb',
		muntah	='$muntah',
		muntah_ket	='$muntah_ket',
		cairan	='$cairan',
		cairan_ket	='$cairan_ket',
		bab	='$bab',
		bab_ket	='$bab_ket',
		GCS	='$GCS',
		makan	='$makan',
		makan_ket	='$makan_ket',
		urine	='$urine',
		urine_ket	='$urine_ket',
		minum	='$minum',
		drain	='$drain',
		drain_ket	='$drain_ket',
		oksigen_tambahan	='$oksigen_tambahan',
		oksigen_ket	='$oksigen_ket',
		iwl	='$iwl',
		pupil	='$pupil',
		tingkat_kesadaran	='$tingkat_kesadaran',
		pendarahan	='$pendarahan',
		vip_score	='$vip_score',
		total_intake	='$total_intake',
		total_output	='$total_output',
		sisa_cairan_infus	='$sisa_cairan_infus',
		balance	='$balance',
		ob1	='$ob1',
		ob2	='$ob2',
		ob3	='$ob3',
		ob4	='$ob4',
		ob5	='$ob5',
		ob6	='$ob6',
		ob7	='$ob7',
		ob8	='$ob8',
		ob9	='$ob9',
		ob10	='$ob10',
		ob11	='$ob11',
		ob12	='$ob12',
		ob13	='$ob13',
		ob14	='$ob14',
		ob15	='$ob15',
		ob16	='$ob16',
		ob17	='$ob17',
		ob18	='$ob18',
		ob19	='$ob19',
		ob20	='$ob20',
		ob21	='$ob21',
		ob22	='$ob22',
		ob23	='$ob23',
		ob24	='$ob24',
		ob25	='$ob25',
		ob26	='$ob26',
		ob27	='$ob27',
		ob28	='$ob28',
		ob29	='$ob29',
		ob30	='$ob30',
		ob31	='$ob31',
		ob32	='$ob32',
		ob33	='$ob33',
		ob34	='$ob34',
		ob35	='$ob35',
		ob36	='$ob36',
		ob37	='$ob37',
		ob38	='$ob38',
		ob39	='$ob39',
		ob40	='$ob40',
		ob41	='$ob41',
		ob42	='$ob42',
		ob43	='$ob43',
		ob44	='$ob44',
		ob45	='$ob45',
		ob46	='$ob46',
		ob47	='$ob47',
		ob48	='$ob48',
		ob49	='$ob49',
		ob50	='$ob50'
		where id='$id_observasi'
		";
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

