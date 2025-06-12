<?php 
// include ("koneksi.php");
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idsoap = $row[2]; 

//norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry, instruksi, dpjp

$qu="SELECT *,CONVERT(VARCHAR, tanggal, 103) as tanggal
FROM ERM_SOAP where id='$idsoap'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 

$norm = $d1u['norm'];
$noreg = $d1u['noreg'];
$tanggal = $d1u['tanggal']; 
$sbu = $d1u['sbu'];
$kodeunit = $d1u['kodeunit'];
$kodedokter = $d1u['kodedokter']; 
$subjektif = $d1u['subjektif']; 
$objektif = $d1u['objektif'];
$assesment = $d1u['assesment']; 
$planning = $d1u['planning']; 
$userid = $d1u['userid'];
$instruksi = $d1u['instruksi']; 
$dpjp = $d1u['dpjp'];

$qg="SELECT antropometri,biokimia, fisik_klinis, asupan_makan, diagnosa_gizi, intervensi, monitoring
FROM ERM_RI_SOAP where id_soap='$idsoap'";
$h1g  = sqlsrv_query($conn, $qg);        
$d1g  = sqlsrv_fetch_array($h1g, SQLSRV_FETCH_ASSOC); 

$antropometri = $d1g['antropometri'];
$biokimia = $d1g['biokimia'];
$fisik_klinis = $d1g['fisik_klinis'];
$asupan_makan = $d1g['asupan_makan'];
$diagnosa_gizi = $d1g['diagnosa_gizi'];
$intervensi = $d1g['intervensi'];
$monitoring = $d1g['monitoring'];

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

<div class="container">

	<body onload="document.myForm.kodedokter.focus();">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<br>
			<div class="row">
				<br />
				<b>Edit Data CPPT</b>
				&nbsp;
				<a href='' class='btn btn-success'>refresh</a>
				<br>
				<hr>
				<?php 
				if (empty($antropometri)){

					?>
					
					<div class="col-12">
						Tgl Input : 
						<input class="form-control" name="tglinput" value="<?php echo $tglinput;?>" type="text" >
					</div>

					<div class="col-12">
						Kode PPA : <input class="form-control" name="kodedokter" value="<?php echo $kodedokter;?>" id="dokter" type="text" size='50' placeholder="Isikan Nama Dokter atau Perawat">
					</div>
					<div class="col-6">
						Subjektif : 
						<textarea class="form-control" name="subjektif" cols="100%" style="min-height:200px;"><?php echo $subjektif;?></textarea>
					</div>
					<div class="col-6">
						Objektif : 
						<textarea class="form-control" name="objektif" cols="100%" style="min-height:200px;"><?php echo $objektif;?></textarea>				
					</div>
					<div class="col-6">
						Assesment : 
						<textarea class="form-control" name="assesment" cols="100%" style="min-height:200px;"><?php echo $assesment;?></textarea>								
					</div>
					<div class="col-6">
						Planning : 
						<textarea class="form-control" name="planning" cols="100%" style="min-height:200px;"><?php echo $planning;?></textarea>
					</div>
					<div class="col-6">
						Instruksi PPA : 
						<textarea class="form-control" name="instruksi" cols="100%" style="min-height:200px;"><?php echo $instruksi;?></textarea>
					</div>
					<div class="col-12">
						DPJP:
						<input class="form-control" name="dpjp" value="<?php echo $dpjp;?>" id="dpjp" type="text" size='50' onfocus="nextfield ='simpan';" placeholder="Isikan Nama Dokter DPJP">
					</div>			
					<?php 
				}else{

					?>

					<div class="row">
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<b>A (ASSESMEN)</b>
									<br>
									<label for="" class="">Antropometri : </label>
									<textarea class="form-control" name="antropometri" cols="100%" onfocus="nextfield ='';" style="min-height:100px;"><?php echo $antropometri;?></textarea>																
									<label for="" class="">Biokimia : </label>
									<textarea class="form-control" name="biokimia" cols="100%" onfocus="nextfield ='';"><?php echo $biokimia;?></textarea>																
									<label for="" class="">Fisik/Klinis : </label>
									<textarea class="form-control" name="fisik_klinis" cols="100%" onfocus="nextfield ='';"><?php echo $fisik_klinis;?></textarea>	
									<label for="" class="">Recall Asupan Makan : </label>
									<textarea class="form-control" name="asupan_makan" cols="100%" onfocus="nextfield ='';"><?php echo $asupan_makan;?></textarea>	
								</div>


								<div class="col-12">
									<b>I (INTERVENSI)</b>
									<br>
									<label for="" class="">Intervensi : </label>
									<?php 
									if(empty($intervensi)){
										$intervensi = "Kebutuhan Energi : …… kkal \nKebutuhan Protein : ….. gram \nKebutuhan Lemak : ……. gram \nKebutuhan KH : ….. gram \nDiet : ………………………………….. \n
										";
									}
									?>
									<textarea class="form-control" name="intervensi" cols="100%" onfocus="nextfield ='';" style="min-height:150px;"><?php echo $intervensi;?></textarea>
									<br>
								</div>
							</div>
						</div>
						<div class="col-6">
							<div class="row">
								<div class="col-12">
									<b>D (DIAGNOSA GIZI)</b>
									<br>
									<textarea class="form-control" name="diagnosa_gizi" cols="100%" onfocus="nextfield ='';" style="min-height:150px;"><?php echo $diagnosa_gizi;?></textarea>
									<br>
								</div>

								<div class="col-12">
									<b>M-E (MONITORING-EVALUASI)</b>
									<br>
									<label for="" class="">monitoring : </label>
									<textarea class="form-control" name="monitoring" cols="100%" onfocus="nextfield ='';"><?php echo $monitoring;?></textarea>	
									<br>					
								</div>	

							</div>						
						</div>

						<div class="col-6">
							<i>DPJP</i></br>
							<input class="form-control" name="dpjp" value="<?php echo $dpjp;?>" id="dpjp" type="text" size='50' onfocus="nextfield ='simpan';" placeholder="Isikan Nama Dokter DPJP">
						</div>
						<div class="col-6">
							<i>Petugas Entry SOAP</i></br>
							<input class="form-control" name="kodedokter" value="<?php echo $kodedokter;?>" id="dokter" type="text" size='50' onfocus="nextfield ='pass';" placeholder="Isikan Nama Dokter atau Perawat yang Entry SOAP">
						</div>



					</div>

					<?php 
				}
				?>
			</div>
			<br>
			<div class="row">
				&nbsp;&nbsp;&nbsp;
				<button type="submit" name="simpan" class="btn btn-success" onfocus="nextfield ='done';">simpan</button> 
				&nbsp;
				<a href='list_soap.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'>close</a>				
			</div>
			<br>

		</form>
	</body>
</div>

<?php

if (isset($_POST["simpan"])) {
	$tglinput = trim($_POST["tglinput"]);
	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);

	$pass = trim($_POST["pass"]);
	$subjektif = trim($_POST["subjektif"]);
	$objektif = trim($_POST["objektif"]);
	$assesment = trim($_POST["assesment"]);
	$planning = trim($_POST["planning"]);
	$instruksi = trim($_POST["instruksi"]);

	$dpjp = trim($_POST["dpjp"]);
	$row2 = explode('-',$dpjp);
	$dpjp  = trim($row2[0]);


	$lanjut="Y";


	// if(empty($pass)){
	// 	$eror='Password Tidak Boleh Kosong !!!';
	// 	$lanjut='T';
	// }

	if(empty($kodedokter)){
		$eror='Kodedokter Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($dpjp)){
		$eror='DPJP Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	//cek user
	$query = "select * from ROLERSPGENTRY.dbo.TBLuser where user1 = '$user'";		
	$result = sqlsrv_query($conn, $query);
	$data  = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);          

	if($lanjut == 'Y'){

		if(empty($antropometri)){

			// $q  = "insert into ERM_SOAP
			// (norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry,instruksi,dpjp) 
			// values 
			// ('$norm','$noreg','$tgl','$sbu','$kodeunit','$kodedokter','$subjektif','$objektif','$assesment','$planning','$user','$tgl','$instruksi','$dpjp')";
			// $hs = sqlsrv_query($conn,$q);

			// $q  = "insert into ERM_SOAP_EDIT
			// (idsoap, noreg, tanggal, userid, tgledit) 
			// values 
			// ('$idsoap','$noreg','$tgl','$user','$tgl')";
			// $hs = sqlsrv_query($conn,$q);


			echo $q  = "update ERM_SOAP set subjektif='$subjektif', objektif='$objektif', assesment='$assesment', planning='$planning',tanggal='$tglinput',tglentry='$tglinput',instruksi='$instruksi' where id=$idsoap";
			$hs = sqlsrv_query($conn,$q);


		}else{

			$antropometri	= trim($_POST["antropometri"]);
			$biokimia	= trim($_POST["biokimia"]);
			$fisik_klinis	= trim($_POST["fisik_klinis"]);
			$asupan_makan	= trim($_POST["asupan_makan"]);
			$intervensi	= trim($_POST["intervensi"]);
			$monitoring	= trim($_POST["monitoring"]);
			$diagnosa_gizi	= trim($_POST["diagnosa_gizi"]);
			$dsubjektif = $diagnosa_gizi;

			//assemen
			$dassesment = "antropometri : ".$antropometri.", biokimia : ".$biokimia.", fisik_klinis : ".$fisik_klinis.", recall_asupan_makan : ".$asupan_makan;

			//plaing
			$dplanning = $intervensi;

			//object
			$dobjektif=$monitoring;

			$q  = "update ERM_SOAP set subjektif='$dsubjektif', objektif='$dobjektif', assesment='$dassesment', planning='$dplanning' where id=$idsoap";
			$hs = sqlsrv_query($conn,$q);

			$q  = "update ERM_RI_SOAP set antropometri='$antropometri', biokimia='$biokimia', fisik_klinis='$fisik_klinis', asupan_makan='$asupan_makan', diagnosa_gizi='$diagnosa_gizi', intervensi='$intervensi', monitoring='$monitoring' where id_soap=$idsoap";
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

