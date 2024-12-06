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

$qu="select top(50)*, 
CONVERT(VARCHAR, tgl, 23) as tglinput,
CONVERT(VARCHAR, tgl, 24) as jam  
from ERM_RI_OBSERVASI_ICU where noreg='$noreg' and id='$aresep' order by tglinput desc";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 

$sistole = $d1u['sistole'];
$diastole = $d1u['diastole'];
$nadi = $d1u['nadi'];
$suhu = $d1u['suhu'];
$cvp = $d1u['cvp'];
$spo2 = $d1u['spo2'];
$rr = $d1u['rr'];
$gcs = $d1u['gcs'];
$pupil = $d1u['pupil'];
$nyeri = $d1u['nyeri'];
$oksigen = $d1u['oksigen'];
$ventilator = $d1u['ventilator'];
$transfusi = $d1u['transfusi'];
$cairan = $d1u['cairan'];
$obat_oral = $d1u['obat_oral'];
$obat_injeksi = $d1u['obat_injeksi'];
$oral = $d1u['oral'];
$intraveneous = $d1u['intraveneous'];
$icairan = $d1u['icairan'];
$gastric = $d1u['gastric'];
$transfusion = $d1u['transfusion'];
$am = $d1u['am'];
$ilain = $d1u['ilain'];
$urine = $d1u['urine'];
$ngt = $d1u['ngt'];
$drain = $d1u['drain'];
$defecation = $d1u['defecation'];
$iwl = $d1u['iwl'];
$olain = $d1u['olain'];
$bcairan = $d1u['bcairan'];
$suction = $d1u['suction'];
$mobilisasi = $d1u['mobilisasi'];


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
				<!-- &nbsp;&nbsp;
					<a href='listobservasi_icu.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success btn-sm'><i class="bi bi-x-circle"></i> List</a> -->
				<!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
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
						mmHg
					</div>
					<div class="col-6">
						<label for="" class="col-sm-3">Diastole</label>
						<input class="form-control-sm" name="diastole" value="<?php echo $diastole;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						mmHg
					</div>
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">Nadi</label>
						<input class="form-control-sm" name="nadi" value="<?php echo $nadi;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						x/mnt
					</div>
					<div class="col-6">
						<label for="" class="col-sm-3">Suhu</label>
						<input class="form-control-sm" name="suhu" value="<?php echo $suhu;?>" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
						⁰C
					</div>
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">CVP</label>
						<input class="form-control-sm" name="cvp" value="<?php echo $cvp;?>" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
						cmH2O
					</div>
					<div class="col-6">
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">SPO2</label>
						<input class="form-control-sm" name="spo2" value="<?php echo $spo2;?>" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
						%
					</div>
					<div class="col-6">
						<label for="" class="col-sm-3">RR</label>
						<input class="form-control-sm" name="rr" value="<?php echo $rr;?>" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
						x/mnt
					</div>
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">GCS</label>
						<input class="form-control-sm" name="gcs" value="<?php echo $gcs;?>" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
					</div>
					<div class="col-6">
						<label for="" class="col-sm-3">REFLEK PUPIL</label>
						<input class="form-control-sm" name="pupil" value="<?php echo $pupil;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					</div>
				</div>

				<div class="row">
					<div class="col-6">
						<label for="" class="col-sm-3">SKORE NYERI</label>
						<input class="form-control-sm" name="nyeri" value="<?php echo $nyeri;?>" id="" type="number" size='' onfocus="nextfield ='';" placeholder="">
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
						<input class="form-control-sm" name="oral" value="<?php echo $oral;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
						<label for="" class="col-sm-3">INTRAVENEOUS</label>
						<input class="form-control-sm" name="intraveneous" value="<?php echo $intraveneous;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
						<label for="" class="col-sm-3">CAIRAN</label>
						<input class="form-control-sm" name="icairan" value="<?php echo $cairan;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
						<label for="" class="col-sm-3">GASTRIC FEEDING</label>
						<input class="form-control-sm" name="gastric" value="<?php echo $gastric;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
						<label for="" class="col-sm-3">TRANSFUSION</label>
						<input class="form-control-sm" name="transfusion" value="<?php echo $transfusion;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
						<label for="" class="col-sm-3">AM</label>
						<input class="form-control-sm" name="am" value="<?php echo $am;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
						<label for="" class="col-sm-3">LAIN-LAIN</label>
						<input class="form-control-sm" name="ilain" value="<?php echo $ilain;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
<!-- 						<label for="" class="col-sm-3">TOTAL</label>
						<input class="form-control-sm" name="tilain" value="0" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
						<br>
					-->						
				</div>					
				<div class="col-6">
					&nbsp;&nbsp;&nbsp;<i><u>OUTPUT</u></i><br>
					<label for="" class="col-sm-3">URINE</label>
					<input class="form-control-sm" name="urine" value="<?php echo $urine;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">NGT/MUNTAH</label>
					<input class="form-control-sm" name="ngt" value="<?php echo $ngt;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">DRAIN</label>
					<input class="form-control-sm" name="drain" value="<?php echo $drain;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">DEFECATION</label>
					<input class="form-control-sm" name="defecation" value="<?php echo $defecation;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">IWL</label>
					<input class="form-control-sm" name="iwl" value="<?php echo $iwl;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
					<br>
					<label for="" class="col-sm-3">LAIN-LAIN</label>
					<input class="form-control-sm" name="olain" value="<?php echo $olain;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
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
				<input class="form-control-sm" name="bcairan" value="<?php echo $bcairan;?>" id="" type="text" size='' onfocus="nextfield ='';" placeholder="">
<!-- 				<select name='bcairan' style='min-width:180px; min-height:30px;'>
					<option value='-'>-</option>
					<option value='EXCESS'>EXCESS</option>
					<option value='DEFICIT'>DEFICIT</option>
				</select>
			-->			</div>
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

	if(empty($sistole)){
		$eror='Tensi Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if($lanjut == 'Y'){

		$q="
		UPDATE ERM_RI_OBSERVASI_ICU set
		tgl='$tgl',
		sistole='$sistole',
		diastole='$diastole',
		nadi='$nadi',
		suhu='$suhu',
		cvp='$cvp',
		spo2='$spo2',
		rr='$rr',
		gcs='$gcs',
		pupil='$pupil',
		nyeri='$nyeri',
		oksigen='$oksigen',
		ventilator='$ventilator',
		transfusi='$transfusi',
		cairan='$cairan',
		obat_oral='$obat_oral',
		obat_injeksi='$obat_injeksi',
		ngt='$ngt',
		urine='$urine',
		drain='$drain',
		defecation='$defecation',
		suction='$suction',
		mobilisasi='$mobilisasi',
		iwl='$iwl',
		olain='$olain',
		tolain='$tolain',
		oral='$oral',
		intraveneous='$intraveneous',
		icairan='$icairan',
		gastric='$gastric',
		transfusion='$transfusion',
		am='$am',
		ilain='$ilain',
		tilain='$tilain',
		bcairan='$bcairan' where id=$aresep
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

