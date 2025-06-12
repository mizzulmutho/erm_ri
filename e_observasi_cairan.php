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
FROM ERM_RI_OBSERVASI_CAIRAN
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
$total_ews = $de['total_ews'];

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
				<a href='listobservasi_cairan.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
				&nbsp;&nbsp;
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
				<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->

				<br>

				<div class="row">
					<div class="col-12">
						<?php 
						include "header_soap.php";
						?>
					</div>
				</div>

				<br>

				<div class="row">
					<div class="col-12">
						<center><i class="bi bi-window-plus"> <b>EDIT DATA MONITORING</b></i></center>
					</div>
				</div>
				<hr>

				
				<div class="row">
					<div class="col-12">
						<table border='0' width="100%">
							<tr><td>Tgl/Jam Input </td>
								<td>: &nbsp;&nbsp; 
									<input class="" name="tglinput" value="<?php echo $tglinput;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
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
								<td>Nama Infus </td>
								<td>
									:<input class="" name="ob29" value="<?php echo $ob29;?>" id="" type="text" size='50' placeholder="isikan nama infus"> 
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
									<?php 
									$ob27 = $balance_cairan;
									?>
									:<input class="" name="ob27" value="<?php echo $ob27;?>" id="" type="text" size='20' placeholder=""> (total input - total output)

								</td>
							</tr>

						</table>
						<br>
						<table border='0' width="100%">

							<tr>
								<td><b>GDA</b>  </td>
								<td>
									: <input class="" name="ob28" value="<?php echo $ob28;?>" id="" type="text" size='20' placeholder=""> 

								</td>
							</tr>


						</table>
					</div>
				</div>
				<br>

				<!-- <input type='submit' name='simpan' value='simpan'>&nbsp; -->
				<center>
					<button type='submit' name='simpan' value='simpan' class="btn btn-info" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
				</center>
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
	$nadi = str_replace(",",".",$nadi);

	$suhu	= trim($_POST["suhu"]);
	$suhu = str_replace(",",".",$suhu);

	$pernafasan	= trim($_POST["pernafasan"]);
	$pernafasan = str_replace(",",".",$pernafasan);

	$spo2	= trim($_POST["spo2"]);
	$spo2 = str_replace(",",".",$spo2);

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


	if($lanjut == 'Y'){
		echo $q  = "update ERM_RI_OBSERVASI_CAIRAN set
		muntah	='$muntah',
		muntah_ket	='$muntah_ket',
		cairan	='$cairan',
		cairan_ket	='$cairan_ket',
		bab	='$bab',
		bab_ket	='$bab_ket',
		makan	='$makan',
		makan_ket	='$makan_ket',
		urine	='$urine',
		urine_ket	='$urine_ket',
		minum	='$minum',
		drain	='$drain',
		drain_ket	='$drain_ket',
		pendarahan	='$pendarahan',
		total_intake	='$total_intake',
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

