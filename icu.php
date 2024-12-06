<?php 
include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// $serverName = "192.168.10.1"; //serverName\instanceName
// $connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
// $conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglentry		= gmdate("Y-m-d H:i:s", time()+60*60*7);

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

$qu="SELECT * FROM  V_ERM_RI_KEADAAN_UMUM where noreg='$noreg' and tensi is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$kesadaran = $d1u['kesadaran'];
$e = $d1u['e'];
$v = $d1u['v'];
$m = $d1u['m'];
$suhu = $d1u['suhu'];
$tensi = $d1u['tensi'];
$nadi = $d1u['nadi'];
$ket_nadi = $d1u['ket_nadi'];
$nafas = $d1u['nafas'];
$spo = $d1u['spo'];

// $qu="select top(1) gcs  
// from ERM_RI_OBSERVASI_ICU where noreg='$noreg'  order by id desc";
// $h1u  = sqlsrv_query($conn, $qu);        
// $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 

// $gcs = $d1u['gcs'];

//ambil resep
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

	<body onload="document.myForm.sistole.focus();">
		<font size='2px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<a href='observasi_icu.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-danger btn-sm'><i class="bi bi-x-circle"></i> Close</a>
				&nbsp;&nbsp;
				<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
<!-- 				&nbsp;&nbsp;
				<a href='listobservasi_icu.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success btn-sm'><i class="bi bi-x-circle"></i> List</a>
			-->				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
			<!-- <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button> -->


			<br><br>
			<div class="row">
				<div class="col-12">
					<i class="bi bi-window-plus"> &nbsp; <b>INPUT DATA OBSERVASI ICU</b></i>
				</div>
			</div>
			<hr>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">Tgl/Jam Input</label>
					<input class="form-control-sm" name="tglinput" value="<?php echo $tglentry;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
				</div>
				<div class="col-6">
					<!-- Ket : Pengisian tanda , diganti dengan tanda .<br><br> -->
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">Tensi, Sistole</label>
					<input class="form-control-sm" name="sistole" value="<?php echo $sistole;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<!-- <select name='sistole' style='min-width:180px; min-height:30px;'>
						<option value='0'>0</option>
						<option value='20'>20</option>
						<option value='30'>30</option>
						<option value='40'>40</option>
						<option value='50'>50</option>
						<option value='60'>60</option>
						<option value='70'>70</option>
						<option value='80'>80</option>
						<option value='90'>90</option>
						<option value='100'>100</option>
						<option value='110'>110</option>
						<option value='120'>120</option>
						<option value='130'>130</option>
						<option value='140'>140</option>
						<option value='150'>150</option>
						<option value='160'>160</option>
						<option value='170'>170</option>
						<option value='180'>180</option>
						<option value='190'>190</option>
						<option value='200'>200</option>
						<option value='210'>210</option>
						<option value='220'>220</option>
						<option value='230'>230</option>
					</select> -->
					mmHg
				</div>
				<div class="col-6">
					<label for="" class="col-sm-3">Diastole</label>
					<input class="form-control-sm" name="diastole" value="<?php echo $diastole;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<!-- <select name='diastole' style='min-width:180px; min-height:30px;'>
						<option value='0'>0</option>
						<option value='20'>20</option>
						<option value='30'>30</option>
						<option value='40'>40</option>
						<option value='50'>50</option>
						<option value='60'>60</option>
						<option value='70'>70</option>
						<option value='80'>80</option>
						<option value='90'>90</option>
						<option value='100'>100</option>
						<option value='110'>110</option>
						<option value='120'>120</option>
						<option value='130'>130</option>
						<option value='140'>140</option>
						<option value='150'>150</option>
						<option value='160'>160</option>
						<option value='170'>170</option>
						<option value='180'>180</option>
						<option value='190'>190</option>
						<option value='200'>200</option>
						<option value='210'>210</option>
						<option value='220'>220</option>
						<option value='230'>230</option>
					</select> -->
					mmHg
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">Nadi</label>
					<input class="form-control-sm" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
<!-- 					<select name='nadi' style='min-width:180px; min-height:30px;'>
						<option value='0'>0</option>
						<option value='20'>20</option>
						<option value='30'>30</option>
						<option value='40'>40</option>
						<option value='50'>50</option>
						<option value='60'>60</option>
						<option value='70'>70</option>
						<option value='80'>80</option>
						<option value='90'>90</option>
						<option value='100'>100</option>
						<option value='110'>110</option>
						<option value='120'>120</option>
						<option value='130'>130</option>
						<option value='140'>140</option>
						<option value='150'>150</option>
						<option value='160'>160</option>
						<option value='170'>170</option>
						<option value='180'>180</option>
						<option value='190'>190</option>
						<option value='200'>200</option>
						<option value='210'>210</option>
						<option value='220'>220</option>
						<option value='230'>230</option>
					</select> -->
					x/mnt
				</div>
				<div class="col-6">
					<label for="" class="col-sm-3">Suhu</label>
					<input class="form-control-sm" name="suhu" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					⁰C
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">CVP</label>
					<input class="form-control-sm" name="cvp" value="0" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
					cmH2O
				</div>
				<div class="col-6">
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">SPO2</label>
					<input class="form-control-sm" name="spo2" value="0" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
					%
				</div>
				<div class="col-6">
					<label for="" class="col-sm-3">RR</label>
					<input class="form-control-sm" name="rr" value="0" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
					x/mnt
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">GCS</label>
					<input class="form-control-sm" name="gcs_e" value="0" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
					E
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">&nbsp;</label>
					<input class="form-control-sm" name="gcs_v" value="0" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
					V
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">&nbsp;</label>
					<input class="form-control-sm" name="gcs_m" value="0" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
					M	
					<!-- <button type="submit" name="simpan" class="btn btn-info btn-sm" onfocus="nextfield ='done';">total_gcs</button> 					 -->
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">Total GCS</label>
					<input class="form-control-sm" name="gcs" value="<?php echo $gcs; ?>" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
				</div>
				<div class="col-6">
					<label for="" class="col-sm-3">REFLEK PUPIL</label>
					<input class="form-control-sm" name="pupil" value="-" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">SKORE NYERI</label>
					<input class="form-control-sm" name="nyeri" value="0" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
				</div>
				<div class="col-6">
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">OKSIGEN</label>
					<input class="form-control-sm" name="oksigen" value="<?php echo $oksigen;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
				</div>
				<div class="col-6">
					<label for="" class="col-sm-3">VENTILATOR</label>
					<input class="form-control-sm" name="ventilator" value="<?php echo $ventilator;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">TRANSFUSI</label>
					<input class="form-control-sm" name="transfusi" value="<?php echo $transfusi;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
				</div>
				<div class="col-6">
					<label for="" class="col-sm-3">CAIRAN</label>
					<input class="form-control-sm" name="cairan" value="<?php echo $cairan;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<label for="" class="col-sm-3">OBAT ORAL<br><br><br><br><br></label>
					<!-- <input class="form-control-sm" name="obat_oral" value="<?php echo $obat_oral;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder=""> -->
					<textarea name= "obat_oral" id="" style="min-width:350px; min-height:130px;"><?php echo $obat_oral;?></textarea>
				</div>
				<div class="col-6">
					<label for="" class="col-sm-3">OBAT INJEKSI<br><br><br><br><br></label>
					<!-- <input class="form-control-sm" name="obat_injeksi" value="<?php echo $obat_injeksi;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder=""> -->
					<textarea name= "obat_injeksi" id="" style="min-width:350px; min-height:130px;"><?php echo $obat_injeksi;?></textarea>
				</div>
			</div>

			<hr>
			<div class="row">
				<div class="col-12">
					<label for="" class="col-sm-12"><b>JUMLAH CAIRAN</b></label>
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					&nbsp;&nbsp;&nbsp;<i><u>INPUT</u></i><br>
					<label for="" class="col-sm-3">ORAL</label>
					<input class="form-control-sm" name="oral" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">INTRAVENEOUS</label>
					<input class="form-control-sm" name="intraveneous" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">CAIRAN</label>
					<input class="form-control-sm" name="icairan" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">GASTRIC FEEDING</label>
					<input class="form-control-sm" name="gastric" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">TRANSFUSION</label>
					<input class="form-control-sm" name="transfusion" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">AM</label>
					<input class="form-control-sm" name="am" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">LAIN-LAIN</label>
					<input class="form-control-sm" name="ilain" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
<!-- 						<label for="" class="col-sm-3">TOTAL</label>
						<input class="form-control-sm" name="tilain" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
					-->						
				</div>					
				<div class="col-6">
					&nbsp;&nbsp;&nbsp;<i><u>OUTPUT</u></i><br>
					<label for="" class="col-sm-3">URINE</label>
					<input class="form-control-sm" name="urine" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">NGT/MUNTAH</label>
					<input class="form-control-sm" name="ngt" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">DRAIN</label>
					<input class="form-control-sm" name="drain" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">DEFECATION</label>
					<input class="form-control-sm" name="defecation" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">IWL</label>
					<input class="form-control-sm" name="iwl" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">LAIN-LAIN</label>
					<input class="form-control-sm" name="olain" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
<!-- 					<br>
					<label for="" class="col-sm-3">TOTAL</label>
					<input class="form-control-sm" name="tolain" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
				-->
			</div>					
		</div>
		<br>
		<div class="row">
			<div class="col-6">
				<label for="" class="col-sm-3">BALANS CAIRAN</label>
				<!-- <input class="form-control-sm" name="bcairan" value="<?php echo $bcairan;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder=""> -->
				<select name='bcairan' style='min-width:180px; min-height:30px;'>
					<option value='-'>-</option>
					<option value='EXCESS'>EXCESS</option>
					<option value='DEFICIT'>DEFICIT</option>
				</select>
			</div>
		</div>

		<hr>

		<div class="row">
			<div class="col-6">
				<label for="" class="col-sm-3">SUCTION</label>
				<input class="form-control-sm" name="suction" value="<?php echo $suction;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
			</div>
			<div class="col-6">
				<label for="" class="col-sm-3">MOBILISASI</label>
				<input class="form-control-sm" name="mobilisasi" value="<?php echo $mobilisasi;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
			</div>
		</div>

		<hr>

		<div class="row-center">
			<div class="col-12">
				<button type="submit" name="simpan" class="btn btn-info btn-sm" onfocus="nextfield ='done';"><i class="bi bi-save"></i>
				simpan</button> 
			</div>					
		</div>
		<br><br>
	</form>
</font>
</body>
</div>

<?php

if (isset($_POST["simpan"])) {

	$tglinput= trim($_POST["tglinput"]);
	$sistole	= trim($_POST["sistole"]);
	$diastole	= trim($_POST["diastole"]);
	$nadi	= trim($_POST["nadi"]);
	$suhu	= trim($_POST["suhu"]);
	$cvp	= trim($_POST["cvp"]);
	if (empty($cvp)){
		$cvp=0;
	}
	$spo2	= trim($_POST["spo2"]);
	$rr	= trim($_POST["rr"]);
	$gcs	= trim($_POST["gcs"]);
	$gcs_e	= trim($_POST["gcs_e"]);
	$gcs_v	= trim($_POST["gcs_v"]);
	$gcs_m	= trim($_POST["gcs_m"]);
	$gcs = $gcs_e+$gcs_v+$gcs_m;


	$pupil	= trim($_POST["pupil"]);
	$nyeri	= trim($_POST["nyeri"]);
	$oksigen	= trim($_POST["oksigen"]);
	$ventilator	= trim($_POST["ventilator"]);
	$transfusi	= trim($_POST["transfusi"]);
	$cairan	= trim($_POST["cairan"]);
	$obat_oral	= trim($_POST["obat_oral"]);
	$obat_injeksi	= trim($_POST["obat_injeksi"]);

	$suction	= trim($_POST["suction"]);
	$mobilisasi	= trim($_POST["mobilisasi"]);

	$ngt	= trim($_POST["ngt"]);
	$urine	= trim($_POST["urine"]);
	$drain	= trim($_POST["drain"]);
	$defecation	= trim($_POST["defecation"]);
	$iwl	= trim($_POST["iwl"]);
	$olain	= trim($_POST["olain"]);
	// $tolain	= trim($_POST["tolain"]);
	$tolain = $ngt+$urine+$drain+$defecation+$iwl+$olain;

	$oral	= trim($_POST["oral"]);
	$intraveneous	= trim($_POST["intraveneous"]);
	$icairan	= trim($_POST["icairan"]);
	$gastric	= trim($_POST["gastric"]);
	$transfusion	= trim($_POST["transfusion"]);
	$am	= trim($_POST["am"]);
	$ilain	= trim($_POST["ilain"]);
	// $tilain	= trim($_POST["tilain"]);
	$tilain = $oral+$intraveneous+$icairan+$gastric+$transfusion+$am+$ilain;

	$bcairan	= trim($_POST["bcairan"]);

	$lanjut="Y";


	if(empty($user)){
		$eror='User Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	// if(empty($sistole)){
	// 	$eror='Tensi Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	if($lanjut == 'Y'){

		echo$q="		
		insert into ERM_RI_OBSERVASI_ICU
		(
		noreg,
		userid,
		tglentry,
		tgl,
		sistole,diastole,nadi,suhu,cvp,spo2,rr,gcs,gcs_e,gcs_v,gcs_m,pupil,nyeri,
		oksigen,ventilator,transfusi,cairan,obat_oral,obat_injeksi,ngt,urine,drain,defecation,suction,mobilisasi,
		iwl,olain,tolain,oral,intraveneous,icairan,gastric,transfusion,am,ilain,tilain,bcairan
		) 
		values 
		(
		'$noreg',
		'$user',
		'$tglentry',
		'$tglinput',
		$sistole,$diastole,$nadi,$suhu,$cvp,$spo2,$rr,$gcs,$gcs_e,$gcs_v,$gcs_m,'$pupil',$nyeri,
		'$oksigen','$ventilator','$transfusi','$cairan','$obat_oral','$obat_injeksi',$ngt,$urine,$drain,$defecation,'$suction','$mobilisasi',
		$iwl,$olain,$tolain,$oral,$intraveneous,$icairan,$gastric,$transfusion,$am,$ilain,$tilain,'$bcairan'
		)
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

