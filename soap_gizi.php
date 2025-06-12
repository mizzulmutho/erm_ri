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

<div class="container-fluid">

	<body onload="document.myForm.ku.focus();" style="background-color: #FDFBEE;">
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
						<b>INPUT SOAP / CPPT GIZI</b><br>
					</div>
				</div>

				<hr>

<!-- 				<div class="row">
					<div class="col-6"><?php echo 'NORM : '.$norm.'<br> NAMA : '.$nama.'<br> TGL LAHIR : '.$tgllahir; ?></div>
					<div class="col-6"><?php echo 'L/P : '.$kelamin.'<br> UMUR : '.$umur.'<br> ALAMAT : '.$alamatpasien; ?></div>
				</div>
			-->
			<div class="row">
				<div class="col-6">
					<div class="row">
						<div class="col-12">
							<b>A (ASSESMEN)</b>
							<br>
							<label for="" class="">Antropometri : </label>
							<?php 
							if(empty($antropometri)){
								$antropometri = "UMUR:  Thn \nBB :  Kg \nTB :  Cm \nIMT : \n
								";
							}
							?>
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
								$intervensi = "Kebutuhan Energi : kkal \nKebutuhan Protein : gram \nKebutuhan Lemak : gram \nKebutuhan KH : gram 
								";
							}
							?>
							<textarea class="form-control" name="intervensi" cols="100%" onfocus="nextfield ='';" style="min-height:150px;"><?php echo $intervensi;?></textarea>
							<br>
						</div>

						<div class="col-12">
							<b>Diet</b>
							<input type='text' name='diet' class='form-control' value='<?php echo $diet; ?>' size='100' placeholder="..."><br>
						</div>

					</div>
				</div>
				<div class="col-6">
					<div class="row">
						<div class="col-12">
							<b>D (DIAGNOSA GIZI)</b>
							<br>
							<label for="" class="">Diagnosa : </label><br>
							<input type='checkbox' name='diagnosa_gizi1' value='NI 2.1 - Kekurangan intake makanan dan minuman oral'>
							NI 2.1	Kekurangan intake makanan dan minuman oral<br>
							<input type='text' name='diagnosa_gizi_1ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi2' value='NI 2.1 - Kelebihan makanan dan minuman oral'>
							NI 2.2	Kelebihan makanan dan minuman oral<br>
							<input type='text' name='diagnosa_gizi_2ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi3' value='NI 2.1 - Kekurangan intake cairan'>
							NI 3.1	Kekurangan intake cairan<br>
							<input type='text' name='diagnosa_gizi_3ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi4' value='NI 2.1 - Peningkatan kebutuhan'>
							NI 5.1	Peningkatan kebutuhan<br>
							<input type='text' name='diagnosa_gizi_4ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi5' value='NI 2.1 - Malnutrisi energi dan protein yang nyata'>
							NI 5.2	Malnutrisi energi dan protein yang nyata<br>
							<input type='text' name='diagnosa_gizi_5ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi6' value='NI 2.1 - Penurunan kebutuhan'>
							NI 5.4	Penurunan kebutuhan<br>
							<input type='text' name='diagnosa_gizi_6ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi7' value='NI 2.1 - Pengetahuan yang kurang dikaitkan dengan pangan dan gizi'>
							NI 1.1	Pengetahuan yang kurang dikaitkan dengan pangan dan gizi<br>
							<input type='text' name='diagnosa_gizi_7ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi8' value='NI 2.1 - Belum siap untuk melakukan diet'>
							NI 1.3	Belum siap untuk melakukan diet<br>
							<input type='text' name='diagnosa_gizi_8ket' value='' size='100' placeholder="..."><br>
							<input type='checkbox' name='diagnosa_gizi9' value='NI 2.1 - Kekeliruan pola makan'>
							NI 1.5	Kekeliruan pola makan <br>
							<input type='text' name='diagnosa_gizi_9ket' value='' size='100' placeholder="..."><br>
							<input type='text' name='diagnosa_gizi_ket' value='' size='100' placeholder="diagnosa lainnya">
							<br>
						</div>

					</div>						
				</div>

				<div class="col-12">
					<b>M-E (MONITORING-EVALUASI)</b>
					<br>
					<label for="" class="">monitoring : </label>
					<textarea class="form-control" name="monitoring" cols="100%" onfocus="nextfield ='';"><?php echo $monitoring;?></textarea>	
					<br>					
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

	$antropometri	= trim($_POST["antropometri"]);
	$biokimia	= trim($_POST["biokimia"]);
	$fisik_klinis	= trim($_POST["fisik_klinis"]);
	$asupan_makan	= trim($_POST["asupan_makan"]);
	$intervensi	= trim($_POST["intervensi"]);
	$monitoring	= trim($_POST["monitoring"]);
	$diagnosa_gizi1	= trim($_POST["diagnosa_gizi1"]);
	$diagnosa_gizi2	= trim($_POST["diagnosa_gizi2"]);
	$diagnosa_gizi3	= trim($_POST["diagnosa_gizi3"]);
	$diagnosa_gizi4	= trim($_POST["diagnosa_gizi4"]);
	$diagnosa_gizi5	= trim($_POST["diagnosa_gizi5"]);
	$diagnosa_gizi6	= trim($_POST["diagnosa_gizi6"]);
	$diagnosa_gizi7	= trim($_POST["diagnosa_gizi7"]);
	$diagnosa_gizi8	= trim($_POST["diagnosa_gizi8"]);
	$diagnosa_gizi9	= trim($_POST["diagnosa_gizi9"]);

	$diagnosa_gizi_1ket	= trim($_POST["diagnosa_gizi_1ket"]);
	$diagnosa_gizi_2ket	= trim($_POST["diagnosa_gizi_2ket"]);
	$diagnosa_gizi_3ket	= trim($_POST["diagnosa_gizi_3ket"]);
	$diagnosa_gizi_4ket	= trim($_POST["diagnosa_gizi_4ket"]);
	$diagnosa_gizi_5ket	= trim($_POST["diagnosa_gizi_5ket"]);
	$diagnosa_gizi_6ket	= trim($_POST["diagnosa_gizi_6ket"]);
	$diagnosa_gizi_7ket	= trim($_POST["diagnosa_gizi_7ket"]);
	$diagnosa_gizi_8ket	= trim($_POST["diagnosa_gizi_8ket"]);
	$diagnosa_gizi_9ket	= trim($_POST["diagnosa_gizi_9ket"]);

	$diagnosa_gizi_ket	= trim($_POST["diagnosa_gizi_ket"]);
	$diet	= trim($_POST["diet"]);

	if($diet){
		$intervensi= $intervensi.'Diet : '.$diet;
	}

	// if(empty($diagnosa_gizi)){
	// 	$diagnosa_gizi=$diagnosa_gizi2;
	// }

	$dpjp = trim($_POST["dpjp"]);
	$row2 = explode('-',$dpjp);
	$dpjp  = trim($row2[0]);

	$kodedokter = trim($_POST["kodedokter"]);
	$row = explode('-',$kodedokter);
	$kodedokter  = trim($row[0]);

	//subyek
	$dsubjektif = 
	$diagnosa_gizi1.' '.$diagnosa_gizi_1ket.' '.
	$diagnosa_gizi2.' '.$diagnosa_gizi_2ket.' '.
	$diagnosa_gizi3.' '.$diagnosa_gizi_3ket.' '.
	$diagnosa_gizi4.' '.$diagnosa_gizi_4ket.' '.
	$diagnosa_gizi5.' '.$diagnosa_gizi_5ket.' '.
	$diagnosa_gizi6.' '.$diagnosa_gizi_6ket.' '.
	$diagnosa_gizi7.' '.$diagnosa_gizi_7ket.' '.
	$diagnosa_gizi8.' '.$diagnosa_gizi_8ket.' '.
	$diagnosa_gizi9.' '.$diagnosa_gizi_9ket.' '.
	$diagnosa_gizi_ket
	;

	//assemen
	$dassesment = "antropometri : ".$antropometri.", biokimia : ".$biokimia.", fisik_klinis : ".$fisik_klinis.", recall_asupan_makan : ".$asupan_makan;

	//plaing
	$dplanning = $intervensi;

	//object
	$dobjektif=$monitoring;

	$lanjut="Y";
	
	if(empty($dpjp)){
		$eror='DPJP Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if(empty($kodedokter)){
		$eror='kodedokter Tidak Boleh Kosong !!!';
		$lanjut='T';
	}


	if($lanjut == 'Y'){
		$q  = "insert into ERM_SOAP
		(norm, noreg, tanggal, sbu, kodeunit, kodedokter, subjektif, objektif, assesment, planning, userid, tglentry,instruksi,dpjp) 
		values 
		('$norm','$noreg','$tglinput','$sbu','$kodeunit','$kodedokter','$dsubjektif','$dobjektif','$dassesment','$dplanning','$user','$tglinput','$instruksi','$dpjp')";
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
		'$ku',
		'$rps',
		'$anamnesa',
		'$rpd',
		'$alergi',
		'$assesmen',
		'$aplan',
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

//simpan diet
	$qu="SELECT noreg FROM ERM_DIET where noreg='$noreg'";
	$h1u  = sqlsrv_query($conn, $qu);        
	$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
	$cekreg_diet = $d1u['noreg'];

	if($cekreg_diet){
		$q  = "update ERM_DIET set diet='$diet',userid='$user',tglentry='$tglinput' where noreg='$noreg'";		
	}else{
		$q  = "insert into ERM_DIET
		(noreg,diet,userid,tglentry) 
		values 
		('$noreg','$diet','$user','$tglinput')";		
	}

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

