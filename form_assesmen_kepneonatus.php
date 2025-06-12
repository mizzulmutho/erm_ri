<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

include "phpqrcode/qrlib.php";
include "header_px.php";

$tgl		= gmdate("Y-m-d", time()+60*60*7);
$tglentry		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$tglinput		= gmdate("Y-m-d H:i:s", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$aresep = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = trim($d1u['noreg']);
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

$nama     = $data2[nama];
$kelamin  = $data2[kelamin];
$nik = trim($data2[nik]);
$alamatpasien  = $data2[alamatpasien];
$kota     = $data2[kota];
$kodekel  = $data2[kodekel];
$telp     = $data2[tlp];
$tmptlahir     = $data2[tmptlahir];
$tgllahir = $data2[tgllahir];
$jenispekerjaan     = $data2[jenispekerjaan];
$jabatan  = $data2[jabatan];
$umur =  $data2[UMUR];
$noktp =  $data2[NOKTP];
$nobpjs =  $data2[NOBPJS];


$qi="SELECT noreg FROM ERM_RI_ASSESMEN_AWAL_NEONATUS_KEP where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_ASSESMEN_AWAL_NEONATUS_KEP(noreg,userid,tglentry) values ('$noreg','$user','$tglentry')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tglrawat, 23) as tglrawat,CONVERT(VARCHAR, tglrawat, 103) as tglrawat2,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ASSESMEN_AWAL_NEONATUS_KEP
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$userid = $de['userid'];

	$tglrawat = $de['tglrawat'];
	$tglrawat2 = $de['tglrawat2'];
	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment'];
	$dpjp = $de['dpjp'];
	$userid = $de['userid'];
	
	$neo1 = $de['neo1'];
	$neo2 = $de['neo2'];
	$neo3 = $de['neo3'];
	$neo4 = $de['neo4'];
	$neo5 = $de['neo5'];
	$neo6 = $de['neo6'];
	$neo7 = $de['neo7'];
	$neo8 = $de['neo8'];
	$neo9 = $de['neo9'];
	$neo10 = $de['neo10'];
	$neo11 = $de['neo11'];
	$neo12 = $de['neo12'];
	$neo13 = $de['neo13'];
	$neo14 = $de['neo14'];
	$neo15 = $de['neo15'];
	$neo16 = $de['neo16'];
	$neo17 = $de['neo17'];
	$neo18 = $de['neo18'];
	$neo19 = $de['neo19'];
	$neo20 = $de['neo20'];
	$neo21 = $de['neo21'];
	$neo22 = $de['neo22'];
	$neo23 = $de['neo23'];
	$neo24 = $de['neo24'];
	$neo25 = $de['neo25'];
	$neo26 = $de['neo26'];
	$neo27 = $de['neo27'];
	$neo28 = $de['neo28'];
	$neo29 = $de['neo29'];
	$neo30 = $de['neo30'];
	$neo31 = $de['neo31'];
	$neo32 = $de['neo32'];
	$neo33 = $de['neo33'];
	$neo34 = $de['neo34'];
	$neo35 = $de['neo35'];
	$neo36 = $de['neo36'];
	$neo37 = $de['neo37'];
	$neo38 = $de['neo38'];
	$neo39 = $de['neo39'];
	$neo40 = $de['neo40'];
	$neo41 = $de['neo41'];
	$neo42 = $de['neo42'];
	$neo43 = $de['neo43'];
	$neo44 = $de['neo44'];
	$neo45 = $de['neo45'];
	$neo46 = $de['neo46'];
	$neo47 = $de['neo47'];
	$neo48 = $de['neo48'];
	$neo49 = $de['neo49'];
	$neo50 = $de['neo50'];
	$neo51 = $de['neo51'];
	$neo52 = $de['neo52'];
	$neo53 = $de['neo53'];
	$neo54 = $de['neo54'];
	$neo55 = $de['neo55'];
	$neo56 = $de['neo56'];
	$neo57 = $de['neo57'];
	$neo58 = $de['neo58'];
	$neo59 = $de['neo59'];
	$neo60 = $de['neo60'];
	$neo61 = $de['neo61'];
	$neo62 = $de['neo62'];
	$neo63 = $de['neo63'];
	$neo64 = $de['neo64'];
	$neo65 = $de['neo65'];
	$neo66 = $de['neo66'];
	$neo67 = $de['neo67'];
	$neo68 = $de['neo68'];
	$neo69 = $de['neo69'];
	$neo70 = $de['neo70'];
	$neo71 = $de['neo71'];
	$neo72 = $de['neo72'];
	$neo73 = $de['neo73'];
	$neo74 = $de['neo74'];
	$neo75 = $de['neo75'];
	$neo76 = $de['neo76'];
	$neo77 = $de['neo77'];
	$neo78 = $de['neo78'];
	$neo79 = $de['neo79'];
	$neo80 = $de['neo80'];
	$neo81 = $de['neo81'];
	$neo82 = $de['neo82'];
	$neo83 = $de['neo83'];
	$neo84 = $de['neo84'];
	$neo85 = $de['neo85'];
	$neo86 = $de['neo86'];
	$neo87 = $de['neo87'];
	$neo88 = $de['neo88'];
	$neo89 = $de['neo89'];
	$neo90 = $de['neo90'];
	$neo91 = $de['neo91'];
	$neo92 = $de['neo92'];
	$neo93 = $de['neo93'];
	$neo94 = $de['neo94'];
	$neo95 = $de['neo95'];
	$neo96 = $de['neo96'];
	$neo97 = $de['neo97'];
	$neo98 = $de['neo98'];
	$neo99 = $de['neo99'];
	$neo100 = $de['neo100'];
	$neo101 = $de['neo101'];
	$neo102 = $de['neo102'];
	$neo103 = $de['neo103'];
	$neo104 = $de['neo104'];
	$neo105 = $de['neo105'];
	$neo106 = $de['neo106'];
	$neo107 = $de['neo107'];
	$neo108 = $de['neo108'];
	$neo109 = $de['neo109'];
	$neo110 = $de['neo110'];
	$neo111 = $de['neo111'];
	$neo112 = $de['neo112'];
	$neo113 = $de['neo113'];
	$neo114 = $de['neo114'];
	$neo115 = $de['neo115'];
	$neo116 = $de['neo116'];
	$neo117 = $de['neo117'];
	$neo118 = $de['neo118'];
	$neo119 = $de['neo119'];
	$neo120 = $de['neo120'];
	$neo121 = $de['neo121'];
	$neo122 = $de['neo122'];
	$neo123 = $de['neo123'];
	$neo124 = $de['neo124'];
	$neo125 = $de['neo125'];
	$neo126 = $de['neo126'];
	$neo127 = $de['neo127'];
	$neo128 = $de['neo128'];
	$neo129 = $de['neo129'];
	$neo130 = $de['neo130'];
	$neo131 = $de['neo131'];
	$neo132 = $de['neo132'];
	$neo133 = $de['neo133'];
	$neo134 = $de['neo134'];
	$neo135 = $de['neo135'];
	$neo136 = $de['neo136'];
	$neo137 = $de['neo137'];
	$neo138 = $de['neo138'];
	$neo139 = $de['neo139'];
	$neo140 = $de['neo140'];
	$neo141 = $de['neo141'];
	$neo142 = $de['neo142'];
	$neo143 = $de['neo143'];
	$neo144 = $de['neo144'];
	$neo145 = $de['neo145'];
	$neo146 = $de['neo146'];
	$neo147 = $de['neo147'];
	$neo148 = $de['neo148'];
	$neo149 = $de['neo149'];
	$neo150 = $de['neo150'];
	$neo151 = $de['neo151'];
	$neo152 = $de['neo152'];
	$neo153 = $de['neo153'];
	$neo154 = $de['neo154'];
	$neo155 = $de['neo155'];
	$neo156 = $de['neo156'];
	$neo157 = $de['neo157'];
	$neo158 = $de['neo158'];
	$neo159 = $de['neo159'];
	$neo160 = $de['neo160'];
	$neo161 = $de['neo161'];
	$neo162 = $de['neo162'];
	$neo163 = $de['neo163'];
	$neo164 = $de['neo164'];
	$neo165 = $de['neo165'];
	$neo166 = $de['neo166'];
	$neo167 = $de['neo167'];
	$neo168 = $de['neo168'];
	$neo169 = $de['neo169'];
	$neo170 = $de['neo170'];
	$neo171 = $de['neo171'];
	$neo172 = $de['neo172'];
	$neo173 = $de['neo173'];
	$neo174 = $de['neo174'];
	$neo175 = $de['neo175'];
	$neo176 = $de['neo176'];
	$neo177 = $de['neo177'];
	$neo178 = $de['neo178'];
	$neo179 = $de['neo179'];
	$neo180 = $de['neo180'];
	$neo181 = $de['neo181'];
	$neo182 = $de['neo182'];
	$neo183 = $de['neo183'];
	$neo184 = $de['neo184'];
	$neo185 = $de['neo185'];
	$neo186 = $de['neo186'];
	$neo187 = $de['neo187'];
	$neo188 = $de['neo188'];
	$neo189 = $de['neo189'];
	$neo190 = $de['neo190'];
	$neo191 = $de['neo191'];
	$neo192 = $de['neo192'];
	$neo193 = $de['neo193'];
	$neo194 = $de['neo194'];
	$neo195 = $de['neo195'];
	$neo196 = $de['neo196'];
	$neo197 = $de['neo197'];
	$neo198 = $de['neo198'];
	$neo199 = $de['neo199'];
	$neo200 = $de['neo200'];
	$neo201 = $de['neo201'];
	$neo202 = $de['neo202'];
	$neo203 = $de['neo203'];
	$neo204 = $de['neo204'];
	$neo205 = $de['neo205'];
	$neo206 = $de['neo206'];
	$neo207 = $de['neo207'];
	$neo208 = $de['neo208'];
	$neo209 = $de['neo209'];
	$neo210 = $de['neo210'];
	$neo211 = $de['neo211'];
	$neo212 = $de['neo212'];
	$neo213 = $de['neo213'];
	$neo214 = $de['neo214'];
	$neo215 = $de['neo215'];
	$neo216 = $de['neo216'];
	$neo217 = $de['neo217'];
	$neo218 = $de['neo218'];
	$neo219 = $de['neo219'];
	$neo220 = $de['neo220'];
	$neo221 = $de['neo221'];
	$neo222 = $de['neo222'];
	$neo223 = $de['neo223'];
	$neo224 = $de['neo224'];
	$neo225 = $de['neo225'];
	$neo226 = $de['neo226'];
	$neo227 = $de['neo227'];
	$neo228 = $de['neo228'];
	$neo229 = $de['neo229'];
	$neo230 = $de['neo230'];
	$neo231 = $de['neo231'];
	$neo232 = $de['neo232'];
	$neo233 = $de['neo233'];
	$neo234 = $de['neo234'];
	$neo235 = $de['neo235'];
	$neo236 = $de['neo236'];
	$neo237 = $de['neo237'];
	$neo238 = $de['neo238'];
	$neo239 = $de['neo239'];
	$neo240 = $de['neo240'];
	$neo241 = $de['neo241'];
	$neo242 = $de['neo242'];
	$neo243 = $de['neo243'];
	$neo244 = $de['neo244'];
	$neo245 = $de['neo245'];
	$neo246 = $de['neo246'];
	$neo247 = $de['neo247'];
	$neo248 = $de['neo248'];
	$neo249 = $de['neo249'];
	$neo250 = $de['neo250'];
	$neo251 = $de['neo251'];
	$neo252 = $de['neo252'];
	$neo253 = $de['neo253'];
	$neo254 = $de['neo254'];
	$neo255 = $de['neo255'];
	$neo256 = $de['neo256'];
	$neo257 = $de['neo257'];
	$neo258 = $de['neo258'];
	$neo259 = $de['neo259'];
	$neo260 = $de['neo260'];
	$neo261 = $de['neo261'];
	$neo262 = $de['neo262'];
	$neo263 = $de['neo263'];
	$neo264 = $de['neo264'];
	$neo265 = $de['neo265'];
	$neo266 = $de['neo266'];
	$neo267 = $de['neo267'];
	$neo268 = $de['neo268'];
	$neo269 = $de['neo269'];
	$neo270 = $de['neo270'];
	$neo271 = $de['neo271'];
	$neo272 = $de['neo272'];
	$neo273 = $de['neo273'];
	$neo274 = $de['neo274'];
	$neo275 = $de['neo275'];
	$neo276 = $de['neo276'];
	$neo277 = $de['neo277'];
	$neo278 = $de['neo278'];
	$neo279 = $de['neo279'];
	$neo280 = $de['neo280'];
	$neo281 = $de['neo281'];
	$neo282 = $de['neo282'];
	$neo283 = $de['neo283'];
	$neo284 = $de['neo284'];
	$neo285 = $de['neo285'];
	$neo286 = $de['neo286'];
	$neo287 = $de['neo287'];
	$neo288 = $de['neo288'];
	$neo289 = $de['neo289'];
	$neo290 = $de['neo290'];
	$neo291 = $de['neo291'];
	$neo292 = $de['neo292'];
	$neo293 = $de['neo293'];
	$neo294 = $de['neo294'];
	$neo295 = $de['neo295'];
	$neo296 = $de['neo296'];
	$neo297 = $de['neo297'];
	$neo298 = $de['neo298'];
	$neo299 = $de['neo299'];
	$neo300 = $de['neo300'];
	$neo301 = $de['neo301'];
	$neo302 = $de['neo302'];
	$neo303 = $de['neo303'];
	$neo304 = $de['neo304'];
	$neo305 = $de['neo305'];
	$neo306 = $de['neo306'];
	$neo307 = $de['neo307'];
	$neo308 = $de['neo308'];
	$neo309 = $de['neo309'];
	$neo310 = $de['neo310'];
	$neo311 = $de['neo311'];
	$neo312 = $de['neo312'];
	$neo313 = $de['neo313'];
	$neo314 = $de['neo314'];
	$neo315 = $de['neo315'];
	$neo316 = $de['neo316'];
	$neo317 = $de['neo317'];
	$neo318 = $de['neo318'];
	$neo319 = $de['neo319'];
	$neo320 = $de['neo320'];

	$neo321 = $de['neo321'];
	$neo322 = $de['neo322'];
	$neo323 = $de['neo323'];
	$neo324 = $de['neo324'];
	$neo325 = $de['neo325'];
	$neo326 = $de['neo326'];
	$neo327 = $de['neo327'];
	$neo328 = $de['neo328'];
	$neo329 = $de['neo329'];
	$neo330 = $de['neo330'];
	$neo331 = $de['neo331'];
	$neo332 = $de['neo332'];
	$neo333 = $de['neo333'];
	$neo334 = $de['neo334'];
	$neo335 = $de['neo335'];
	$neo336 = $de['neo336'];
	$neo337 = $de['neo337'];
	$neo338 = $de['neo338'];
	$neo339 = $de['neo339'];
	$neo340 = $de['neo340'];
	$neo341 = $de['neo341'];
	$neo342 = $de['neo342'];
	$neo343 = $de['neo343'];
	$neo344 = $de['neo344'];
	$doc = $de['doc'];
	$ttd_pasien = $de['ttd_pasien'];
}

?>



<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


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

	<!-- Tanda Tangan -->
	<script type="text/javascript" src="js/jquery.signature.min.js"></script>
	<script type="text/javascript" src="js/jquery.ui.touch-punch.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jquery.signature.css">
	<style>
		.kbw-signature {
			width: 300px;
			height: 300px;
		}
		#sig canvas {
			width: 100% !important;
			height: auto;
		}
	</style>

	<style type="text/css">
		@media print{
			body {display:none;}
		}
	</style>

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

<div class="container py-4">

	<body onload="document.myForm.dpjp.focus();" class="bg-light">

		<form method="POST" name='myForm' action="" enctype="multipart/form-data">
			<br>
			<!-- Action Buttons -->
			<div class="d-flex justify-content-start gap-2 mb-4">
				<a href='index.php?id=<?php echo $id."|".$user; ?>' class='btn btn-warning btn-sm'>
					<i class="bi bi-x-lg"></i> Tutup
				</a>
				<a href='' class='btn btn-success btn-sm'>
					<i class="bi bi-arrow-clockwise"></i> Refresh
				</a>
				<a href='form_assesmen_neonatuskep_print.php?id=<?php echo $id."|".$user;?>' target='_blank' class='btn btn-info btn-sm'>
					<i class="bi bi-printer-fill"></i> Cetak
				</a>
			</div>


			<!-- Title -->
			<div class="text-center mb-4">
				<h5><i class="bi bi-window-plus"></i> ASESMEN AWAL KEPERAWATAN NEONATUS</h5>
			</div>

			<!-- Input DPJP -->
			<div class="card shadow-sm mb-4">
				<div class="card-body">
					<h6 class="card-title text-primary"><i class="bi bi-person-lines-fill"></i>DIISI OLEH PERAWAT</h6>
					<div class="row g-3">
						<div class="col-md-4">
							<label for="tglinput" class="form-label">Tanggal Input</label>
							<input type="text" class="form-control" name="tglinput" value="<?php echo $tglinput; ?>">
						</div>
						<div class="col-md-8">
							<label for="dpjp" class="form-label">DPJP (Dokter Penanggung Jawab)</label>
							<input type="text" class="form-control" id="dokter" name="dpjp" value="<?php echo $dpjp;?>" placeholder="Nama atau Kode Dokter" required>
						</div>
					</div>
				</div>
			</div>

			<!-- Submit Button -->
			<div class="text-center mb-4">
				<button type="submit" name="simpan" class="btn btn-success px-4">
					<i class="bi bi-save"></i> Simpan
				</button>
			</div>


			<div class="container">
				<div class="card">
					<div class="card-header bg-primary text-white">
						<h4>Form Masuk Ruang Rawat</h4>
					</div>
					<div class="card-body">
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
								<input type="date" name="tglrawat" value="<?php echo $tglrawat;?>" class="form-control">
							</div>
							<div class="col-md-6">
								<label for="jam_masuk" class="form-label">Jam Masuk</label>
								<input type="time" name="neo1" value="<?php echo $neo1;?>" class="form-control">
							</div>
						</div>

						<h5 class="mt-4">Keluarga / Orang Lain</h5>
						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Nama Keluarga</label>
								<input type="text" name="neo2" value="<?php echo $neo2;?>"  class="form-control">
							</div>
							<div class="col-md-6">
								<label class="form-label">Hubungan</label>
								<input type="text" name="neo3" value="<?php echo $neo3;?>" class="form-control">
							</div>
							<div class="col-md-6 mt-2">
								<label class="form-label">Nama Orang Lain</label>
								<input type="text" name="neo4" value="<?php echo $neo4;?>" class="form-control">
							</div>
							<div class="col-md-6 mt-2">
								<label class="form-label">Hubungan</label>
								<input type="text" name="neo5" value="<?php echo $neo5;?>" class="form-control">
							</div>
						</div>

						<h5 class="mt-4">Data Orang Tua</h5>
						<div class="row mb-3">
							<div class="col-md-6">
								<label class="form-label">Nama Ibu</label>
								<input type="text" name="neo6" value="<?php echo $neo6;?>" class="form-control">
							</div>
							<div class="col-md-6">
								<label class="form-label">Usia Ibu</label>
								<input type="number" name="neo7" value="<?php echo $neo7;?>" class="form-control">
							</div>
							<div class="col-md-6 mt-2">
								<label class="form-label">Nama Ayah</label>
								<input type="text" name="neo8" value="<?php echo $neo8;?>" class="form-control">
							</div>
							<div class="col-md-6 mt-2">
								<label class="form-label">Usia Ayah</label>
								<input type="number" name="neo9" value="<?php echo $neo9;?>" class="form-control">
							</div>
						</div>

						<h5 class="mt-4">Asal Masuk</h5>
						<div class="mb-3">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="neo10" value="IGD" id="igd" <?php if($neo10=='IGD'){echo "checked";}?>>
								<label class="form-check-label" for="igd">Instalasi Gawat Darurat (IGD)</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="neo10" value="Rajal" id="rajal" <?php if($neo10=='Rajal'){echo "checked";}?>>
								<label class="form-check-label" for="rajal">Instalasi Rawat Jalan (Rajal)</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="neo10" value="Bersalin" id="bersalin" <?php if($neo10=='Bersalin'){echo "checked";}?>>
								<label class="form-check-label" for="bersalin">Instalasi Bersalin</label>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label">Rujukan Dari</label>
							<input type="text" name="neo11" value="<?php echo $neo11;?>" class="form-control">
						</div>

						<div class="mb-3">
							<label class="form-label">Pasien Rujukan (Ya/Tidak)</label>
							<input type="text" name="neo12" value="<?php echo $neo12;?>" class="form-control">
						</div>

						<button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
					</div>
				</div>

			</div>

			<div class="container">
				<div class="card">
					<div class="card-header bg-info text-white">
						<h4>Form Anamnesis & Riwayat Bayi Baru Lahir</h4>
					</div>
					<div class="card-body">
						
						<!-- Keluhan Utama -->
						<div class="mb-3">
							<label class="form-label">Keluhan Utama Saat Masuk</label>
							<textarea name="neo13" class="form-control" rows="2"><?php echo $neo13;?></textarea>
						</div>

						<!-- Riwayat Pengobatan -->
						<div class="mb-3">
							<label class="form-label">Riwayat Pengobatan / Perawatan Sebelumnya</label>
							<textarea name="neo14" class="form-control" rows="2"><?php echo $neo14;?></textarea>
						</div>

						<!-- Riwayat Penyakit Keluarga & Reproduksi -->
						<div class="mb-3">
							<label class="form-label">Riwayat Penyakit Keluarga & Reproduksi</label>
							<textarea name="neo15" class="form-control" rows="2"><?php echo $neo15;?></textarea>
						</div>

						<!-- Obat yang sedang dikonsumsi -->
						<div class="mb-3">
							<label class="form-label">Obat yang Sedang Dikonsumsi</label><br>
							<div>
								<input type="checkbox" id="tidakAda" name="neo16" value="Tidak ada" <?php if($neo16=='Tidak ada'){echo "checked";}?>>
								<label for="tidakAda">Tidak ada</label>
							</div>
							<div>
								<input type="checkbox" id="ada" name="neo16" value="Ada" <?php if($neo16=='Ada'){echo "checked";}?>>
								<label for="ada">Ada, tulis di form Rekonsiliasi Obat</label>
							</div>
						</div>

						<!-- Alergi -->
						<div class="mb-3">
							<label class="form-label">Riwayat Alergi</label>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="neo17" value="Tidak" id="alergi_tidak" <?php if($neo16=='Tidak'){echo "checked";}?>>
								<label class="form-check-label" for="alergi_tidak">Tidak</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="neo17" value="Ya" id="alergi_ya" <?php if($neo16=='Ya'){echo "checked";}?>>
								<label class="form-check-label" for="alergi_ya">Ya</label>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label">Alergi Obat (Jenis/Nama Obat)</label>
							<input type="text" name="neo18" value="<?php echo $neo18;?>" class="form-control">
						</div>

						<div class="mb-3">
							<label class="form-label">Alergi Makanan / Lainnya</label>
							<input type="text" name="neo19" value="<?php echo $neo19;?>"class="form-control">
						</div>

						<div class="mb-3">
							<label class="form-label">Reaksi Terhadap Alergi</label>
							<textarea name="neo20" value="<?php echo $neo20;?>" class="form-control" rows="2"></textarea>
						</div>

						<!-- Transfusi Darah -->
						<div class="mb-3">
							<label class="form-label">Riwayat Transfusi Darah</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="neo21" value="Tidak" id="transfusi_tidak" <?php if($neo21=='Tidak'){echo "checked";}?>>
								<label class="form-check-label" for="transfusi_tidak">Tidak</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="neo21" value="Ya" id="transfusi_ya" <?php if($neo21=='Ya'){echo "checked";}?>>
								<label class="form-check-label" for="transfusi_ya">Ya</label>
							</div>
						</div>

						<div class="mb-3">
							<label class="form-label">Reaksi Alergi Terhadap Transfusi</label><br>
							<select name="neo22" class="form-select">
								<option value="Tidak" <?php if($neo22=='Tidak'){echo "selected";}?>>Tidak</option>
								<option value="Ya" <?php if($neo22=='Ya'){echo "selected";}?>>Ya</option>
							</select>
							<input type="text" name="neo23" value="<?php echo $neo23;?>" class="form-control mt-2" placeholder="Jika Ya, jelaskan...">
						</div>

						<!-- Tanggal Lahir & Berat -->
						<div class="row mb-3">
							<div class="col-md-4">
								<label class="form-label">Tanggal / Jam Lahir</label>
								<input type="datetime-local" name="neo24" value="<?php echo $neo24;?>"  class="form-control">
							</div>
							<div class="col-md-4">
								<label class="form-label">Berat Badan Lahir (gram)</label>
								<input type="number" name="neo25" value="<?php echo $neo25;?>"  class="form-control">
							</div>
							<div class="col-md-4">
								<label class="form-label">Usia Gestasi (minggu)</label>
								<input type="number" name="neo26" value="<?php echo $neo26;?>"  class="form-control">
							</div>
						</div>

						<!-- Apgar Score & Panjang Badan -->
						<div class="row mb-3">
							<div class="col-md-4">
								<label class="form-label">Apgar Score 1'</label>
								<input type="number" name="neo27" value="<?php echo $neo27;?>" class="form-control">
							</div>
							<div class="col-md-4">
								<label class="form-label">Apgar Score 5'</label>
								<input type="number" name="neo28" value="<?php echo $neo28;?>" class="form-control">
							</div>
							<div class="col-md-4">
								<label class="form-label">Panjang Badan Lahir (cm)</label>
								<input type="number" name="neo29" value="<?php echo $neo29;?>" class="form-control">
							</div>
						</div>

						<!-- Lingkar Kepala -->
						<div class="mb-3">
							<label class="form-label">Lingkar Kepala Lahir (cm)</label>
							<input type="number" name="neo30" value="<?php echo $neo30;?>" class="form-control">
						</div>

						<!-- Imunisasi Dasar -->
						<div class="mb-3">
							<label class="form-label">Riwayat Imunisasi Dasar</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="neo31" value="BCG" <?php if($neo31=='BCG'){echo "checked";}?>>
								<label class="form-check-label">BCG</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="neo32" value="Campak" <?php if($neo32=='Campak'){echo "checked";}?>>
								<label class="form-check-label">Campak</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="neo33" value="DPT" <?php if($neo33=='DPT'){echo "checked";}?>>
								<label class="form-check-label">DPT</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="neo34" value="Hepatitis B" <?php if($neo34=='Hepatitis B'){echo "checked";}?>>
								<label class="form-check-label">Hepatitis B</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="neo35" value="Polio" <?php if($neo35=='Polio'){echo "checked";}?>>
								<label class="form-check-label">Polio</label>
							</div>
						</div>

						<button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
						
					</div>
				</div>

			</div>


			<div class="container">
				<div class="card">
					<div class="card-header bg-secondary text-white">
						<h4>Form Riwayat ANTENATAL</h4>
					</div>
					<div class="card-body">
						
						<!-- Gravida -->
						<div class="mb-3">
							<label class="form-label">Gravida ke</label>
							<input type="number" name="gravida" class="form-control" placeholder="Contoh: 2">
						</div>

						<!-- Gangguan Hamil -->
						<div class="mb-3">
							<label class="form-label">Gangguan Hamil (Trimester 1)</label>
							<textarea name="gangguan_hamil" class="form-control" rows="2"></textarea>
						</div>

						<!-- Jenis Persalinan -->
						<div class="mb-3">
							<label class="form-label">Jenis Persalinan</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="persalinan[]" value="Spontan">
								<label class="form-check-label">Spontan</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="persalinan[]" value="Induksi">
								<label class="form-check-label">Induksi</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="persalinan[]" value="Forcep">
								<label class="form-check-label">Forcep</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="persalinan[]" value="Sectio Caesarea">
								<label class="form-check-label">Sectio Caesarea</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="persalinan[]" value="Vacum">
								<label class="form-check-label">Vacum</label>
							</div>
						</div>

						<!-- Tempat ANC -->
						<div class="mb-3">
							<label class="form-label">Tempat ANC</label>
							<input type="text" name="tempat_anc" class="form-control">
						</div>

						<!-- ANC Teratur -->
						<div class="mb-3">
							<label class="form-label">ANC Teratur</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="anc_teratur" value="Tidak" id="anc_tidak">
								<label class="form-check-label" for="anc_tidak">Tidak</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="anc_teratur" value="Ya" id="anc_ya">
								<label class="form-check-label" for="anc_ya">Ya</label>
							</div>
						</div>

						<!-- Keputihan -->
						<div class="mb-3">
							<label class="form-label">Keputihan</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="keputihan" value="Tidak" id="keputihan_tidak">
								<label class="form-check-label" for="keputihan_tidak">Tidak</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="keputihan" value="Ya" id="keputihan_ya">
								<label class="form-check-label" for="keputihan_ya">Ya</label>
							</div>
						</div>

						<button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
						
					</div>
				</div>

				<?php if (isset($_POST['submit'])): ?>
					<div class="card mt-4">
						<div class="card-header bg-success text-white">
							<h5>Data yang Dimasukkan</h5>
						</div>
						<div class="card-body">
							<?php
							foreach ($_POST as $key => $value) {
								if ($key === 'persalinan') {
									echo "<p><strong>Jenis Persalinan:</strong> " . implode(', ', $value) . "</p>";
								} elseif ($key !== 'submit') {
									echo "<p><strong>" . ucfirst(str_replace("_", " ", $key)) . ":</strong> " . htmlspecialchars($value) . "</p>";
								}
							}
							?>
						</div>
					</div>
				<?php endif; ?>
			</div>


			<div class="container">
				<div class="card">
					<div class="card-header bg-secondary text-white">
						<h4>Form Riwayat INTRANATAL</h4>
					</div>
					<div class="card-body">
						
						<!-- Kehamilan -->
						<div class="mb-3">
							<label class="form-label">Kehamilan</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="kehamilan" value="Tunggal" id="kehamilan_tunggal">
								<label class="form-check-label" for="kehamilan_tunggal">Tunggal</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="kehamilan" value="Gemeli" id="kehamilan_gemeli">
								<label class="form-check-label" for="kehamilan_gemeli">Gemeli</label>
							</div>
						</div>

						<!-- Ketuban Pecah -->
						<div class="mb-3">
							<label class="form-label">Ketuban Pecah</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="ketuban_pecah" value="Ya" id="pecah_ya">
								<label class="form-check-label" for="pecah_ya">Ya</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="ketuban_pecah" value="Tidak" id="pecah_tidak">
								<label class="form-check-label" for="pecah_tidak">Tidak</label>
							</div>
							<div class="mt-2">
								<label class="form-label">Jam Ketuban Pecah</label>
								<input type="time" name="jam_ketuban_pecah" class="form-control">
							</div>
						</div>

						<!-- Warna Ketuban -->
						<div class="mb-3">
							<label class="form-label">Warna Ketuban</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="warna_ketuban" value="Jernih" id="jernih">
								<label class="form-check-label" for="jernih">Jernih</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="warna_ketuban" value="Kehijauan" id="kehijauan">
								<label class="form-check-label" for="kehijauan">Kehijauan</label>
							</div>
						</div>

						<!-- Perdarahan -->
						<div class="mb-3">
							<label class="form-label">Perdarahan</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="perdarahan" value="Ya" id="perdarahan_ya">
								<label class="form-check-label" for="perdarahan_ya">Ya</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="perdarahan" value="Tidak" id="perdarahan_tidak">
								<label class="form-check-label" for="perdarahan_tidak">Tidak</label>
							</div>
						</div>

						<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
						
					</div>
				</div>

				<?php if (isset($_POST['submit'])): ?>
					<div class="card mt-4">
						<div class="card-header bg-success text-white">
							<h5>Data yang Dimasukkan</h5>
						</div>
						<div class="card-body">
							<ul class="list-group">
								<li class="list-group-item"><strong>Kehamilan:</strong> <?= htmlspecialchars($_POST['kehamilan']) ?></li>
								<li class="list-group-item"><strong>Ketuban Pecah:</strong> <?= htmlspecialchars($_POST['ketuban_pecah']) ?></li>
								<li class="list-group-item"><strong>Jam Ketuban Pecah:</strong> <?= htmlspecialchars($_POST['jam_ketuban_pecah']) ?></li>
								<li class="list-group-item"><strong>Warna Ketuban:</strong> <?= htmlspecialchars($_POST['warna_ketuban']) ?></li>
								<li class="list-group-item"><strong>Perdarahan:</strong> <?= htmlspecialchars($_POST['perdarahan']) ?></li>
							</ul>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="container">
				<div class="card">
					<div class="card-header bg-secondary text-white">
						<h4>Form Riwayat Penyakit Ibu</h4>
					</div>
					<div class="card-body">
						
						<label class="form-label">Riwayat Penyakit Ibu (boleh lebih dari satu):</label><br>

						<?php
                // Daftar penyakit
						$penyakit_list = [
							'DM' => 'Diabetes Mellitus',
							'Imunodefisiensi' => 'Imunodefisiensi',
							'Hepatitis B' => 'Hepatitis B',
							'Jantung' => 'Jantung',
							'TB' => 'Tuberkulosis',
							'Asma' => 'Asma',
							'Hipertensi' => 'Hipertensi',
							'ISK' => 'Infeksi Saluran Kemih'
						];

						foreach ($penyakit_list as $key => $label): ?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="penyakit[]" value="<?= $key ?>" id="<?= $key ?>">
								<label class="form-check-label" for="<?= $key ?>"><?= $label ?></label>
							</div>
						<?php endforeach; ?>

						<!-- Penyakit Lainnya -->
						<div class="mb-3 mt-3">
							<label class="form-label">Lainnya (jika ada):</label>
							<input type="text" name="penyakit_lainnya" class="form-control" placeholder="Tulis penyakit lain di sini...">
						</div>

						<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
						
					</div>
				</div>

				<?php if (isset($_POST['submit'])): ?>
					<div class="card mt-4">
						<div class="card-header bg-success text-white">
							<h5>Data Riwayat Penyakit Ibu</h5>
						</div>
						<div class="card-body">
							<ul class="list-group">
								<li class="list-group-item">
									<strong>Penyakit yang dipilih:</strong>
									<?= isset($_POST['penyakit']) ? implode(', ', $_POST['penyakit']) : 'Tidak ada' ?>
								</li>
								<li class="list-group-item">
									<strong>Lainnya:</strong> <?= htmlspecialchars($_POST['penyakit_lainnya']) ?>
								</li>
							</ul>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="container">
				<div class="card">
					<div class="card-header bg-secondary text-white">
						<h4>Form Riwayat Post Natal</h4>
					</div>
					<div class="card-body">
						
						<!-- IMD -->
						<div class="mb-3">
							<label class="form-label">IMD</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="imd" value="Ya" id="imd_ya">
								<label class="form-check-label" for="imd_ya">Ya</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="imd" value="Tidak" id="imd_tidak">
								<label class="form-check-label" for="imd_tidak">Tidak</label>
							</div>
						</div>

						<!-- Riwayat Gabung -->
						<div class="mb-3">
							<label class="form-label">Riwayat Gabung</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="riwayat_gabung" value="Ya" id="gabung_ya">
								<label class="form-check-label" for="gabung_ya">Ya</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="riwayat_gabung" value="Tidak" id="gabung_tidak">
								<label class="form-check-label" for="gabung_tidak">Tidak</label>
							</div>
						</div>

						<!-- ASI -->
						<div class="mb-3">
							<label class="form-label">ASI</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="asi" value="Ya" id="asi_ya">
								<label class="form-check-label" for="asi_ya">Ya</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="asi" value="Tidak" id="asi_tidak">
								<label class="form-check-label" for="asi_tidak">Tidak</label>
							</div>
						</div>

						<!-- Perawatan Model Kangguru -->
						<div class="mb-3">
							<label class="form-label">Perawatan Model Kangguru</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="kangguru" value="Ya" id="kangguru_ya">
								<label class="form-check-label" for="kangguru_ya">Ya</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="kangguru" value="Tidak" id="kangguru_tidak">
								<label class="form-check-label" for="kangguru_tidak">Tidak</label>
							</div>
						</div>

						<!-- Riwayat Resusitasi -->
						<div class="mb-3">
							<label class="form-label">Riwayat Resusitasi</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="resusitasi" value="Ya" id="resusitasi_ya">
								<label class="form-check-label" for="resusitasi_ya">Ya</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="resusitasi" value="Tidak" id="resusitasi_tidak">
								<label class="form-check-label" for="resusitasi_tidak">Tidak</label>
							</div>
						</div>

						<!-- Kelainan Bawaan -->
						<div class="mb-3">
							<label class="form-label">Kelainan Bawaan</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="kelainan" value="Tidak" id="kelainan_tidak" onclick="toggleKelainanInput(false)">
								<label class="form-check-label" for="kelainan_tidak">Tidak</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="kelainan" value="Ya" id="kelainan_ya" onclick="toggleKelainanInput(true)">
								<label class="form-check-label" for="kelainan_ya">Ya</label>
							</div>
							<div class="mt-2">
								<input type="text" name="kelainan_detail" class="form-control" id="kelainan_detail" placeholder="Sebutkan kelainan..." style="display: none;">
							</div>
						</div>

						<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
						
					</div>
				</div>

				<?php if (isset($_POST['submit'])): ?>
					<div class="card mt-4">
						<div class="card-header bg-success text-white">
							<h5>Data Riwayat Post Natal</h5>
						</div>
						<div class="card-body">
							<ul class="list-group">
								<li class="list-group-item"><strong>IMD:</strong> <?= htmlspecialchars($_POST['imd']) ?></li>
								<li class="list-group-item"><strong>Riwayat Gabung:</strong> <?= htmlspecialchars($_POST['riwayat_gabung']) ?></li>
								<li class="list-group-item"><strong>ASI:</strong> <?= htmlspecialchars($_POST['asi']) ?></li>
								<li class="list-group-item"><strong>Perawatan Kangguru:</strong> <?= htmlspecialchars($_POST['kangguru']) ?></li>
								<li class="list-group-item"><strong>Riwayat Resusitasi:</strong> <?= htmlspecialchars($_POST['resusitasi']) ?></li>
								<li class="list-group-item"><strong>Kelainan Bawaan:</strong>
									<?= htmlspecialchars($_POST['kelainan']) ?>
									<?php if ($_POST['kelainan'] === "Ya"): ?>
										- <?= htmlspecialchars($_POST['kelainan_detail']) ?>
									<?php endif; ?>
								</li>
							</ul>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<script>
				function toggleKelainanInput(show) {
					document.getElementById('kelainan_detail').style.display = show ? 'block' : 'none';
				}
			</script>

			<div class="container">
				<div class="card">
					<div class="card-header bg-secondary text-white">
						<h5>PEMERIKSAAN FISIK</h5>
					</div>
					<div class="card-body">
						
						<div class="mb-3">
							<label>Tanggal/Jam Pemeriksaan:</label>
							<input type="datetime-local" name="tanggal_jam" class="form-control">
						</div>

						<div class="mb-3">
							<label>Kesadaran:</label><br>
							<?php
							$kesadaran = ['Compos Mentis', 'Apatis', 'Somnolen', 'Sopor', 'Coma'];
							foreach ($kesadaran as $item): ?>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="kesadaran" value="<?= $item ?>" id="<?= $item ?>">
									<label class="form-check-label" for="<?= $item ?>"><?= $item ?></label>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="mb-3 row">
							<div class="col-md-4">
								<label>GCS - E:</label>
								<input type="text" name="gcs_e" class="form-control">
							</div>
							<div class="col-md-4">
								<label>GCS - V:</label>
								<input type="text" name="gcs_v" class="form-control">
							</div>
							<div class="col-md-4">
								<label>GCS - M:</label>
								<input type="text" name="gcs_m" class="form-control">
							</div>
						</div>

						<div class="row mb-3">
							<div class="col-md-4">
								<label>Berat Badan (gram):</label>
								<input type="text" name="berat_badan" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Panjang Badan (cm):</label>
								<input type="text" name="panjang_badan" class="form-control">
							</div>
							<div class="col-md-4">
								<label>Lingkar Kepala (cm):</label>
								<input type="text" name="lingkar_kepala" class="form-control">
							</div>
							<div class="col-md-4 mt-2">
								<label>Lingkar Lengan (cm):</label>
								<input type="text" name="lingkar_lengan" class="form-control">
							</div>
						</div>

						<hr>

						
					</div>
				</div>


			</div>

			<div class="container">

				<div class="card mb-4">
					<div class="card-header bg-info text-white">
						<h5>A. KULIT</h5>
					</div>
					<div class="card-body">
						<div class="mb-3">
							<label>Suhu Kulit (°C):</label>
							<input type="text" name="suhu_kulit" class="form-control">
						</div>

						<div class="mb-3">
							<label>Warna Kulit:</label><br>
							<?php
							$warna_kulit = ['Pink', 'Pucat', 'Kuning', 'Cutis marmorata'];
							foreach ($warna_kulit as $warna): ?>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="warna_kulit[]" value="<?= $warna ?>" id="<?= $warna ?>">
									<label class="form-check-label" for="<?= $warna ?>"><?= $warna ?></label>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="mb-3">
							<label>Sianosis:</label><br>
							<?php
							$sianosis = ['Sentral', 'Perioral', 'Periorbital'];
							foreach ($sianosis as $s): ?>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="sianosis[]" value="<?= $s ?>" id="<?= $s ?>">
									<label class="form-check-label" for="<?= $s ?>"><?= $s ?></label>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="mb-3">
							<label>Kemerahan (RASH):</label><br>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="rash" value="Ada" id="rash_ada">
								<label class="form-check-label" for="rash_ada">Ada</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="radio" name="rash" value="Tidak" id="rash_tidak">
								<label class="form-check-label" for="rash_tidak">Tidak</label>
							</div>
						</div>

						<hr>
						<h5>MASALAH</h5>
						<?php
						$masalah = [
							'Hipotermi', 'Hipertemi', 'Ikterik Neonatus',
							'Risiko ikterik neonatus', 'Gangguan integritas kulit/jaringan'
						];
						foreach ($masalah as $m): ?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="masalah[]" value="<?= $m ?>" id="<?= $m ?>">
								<label class="form-check-label" for="<?= $m ?>"><?= $m ?></label>
							</div>
						<?php endforeach; ?>
						<div class="mb-3 mt-2">
							<label>Masalah lain:</label>
							<input type="text" name="masalah_lain" class="form-control">
						</div>

						<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
					</div>
				</div>

				<div class="card mb-4">
					<div class="card-header bg-info text-white">
						<h5>B. PEMERIKSAAN KEPALA/LEHER</h5>
					</div>


					<div class="card-body">
						<form method="post">
							<div class="mb-3">
								<label>Tanda Lahir:</label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="tanda_lahir" value="Ada">
									<label class="form-check-label">Ada</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="tanda_lahir" value="Tidak">
									<label class="form-check-label">Tidak</label>
								</div>
							</div>

							<div class="mb-3">
								<label>Turgor Kulit:</label><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="turgor" value="Elastis">
									<label class="form-check-label">Elastis</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="turgor" value="Tidak elastis">
									<label class="form-check-label">Tidak elastis</label>
								</div>
							</div>

							<hr>
							<h6>A. Kepala / Leher</h6>

							<div class="mb-3">
								<label>Frontanela Anterior:</label><br>
								<?php
								$frontanela = ['Lunak', 'Tegas', 'Datar', 'Menonjol', 'Cekung'];
								foreach ($frontanela as $f): ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="frontanela[]" value="<?= $f ?>">
										<label class="form-check-label"><?= $f ?></label>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="mb-3">
								<label>Sutura Sagitalis:</label><br>
								<?php
								$sutura = ['Tepat', 'Terpisah', 'Menjauh', 'Tumpang tindih'];
								foreach ($sutura as $s): ?>
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="sutura[]" value="<?= $s ?>">
										<label class="form-check-label"><?= $s ?></label>
									</div>
								<?php endforeach; ?>
							</div>

							<div class="mb-3">
								<label>Gambaran Wajah:</label><br>
								<input type="radio" name="wajah" value="Simetris"> Simetris
								<input type="radio" name="wajah" value="Asimetris"> Asimetris
							</div>

							<div class="mb-3">
								<label>Caput Succedaneum:</label><br>
								<input type="radio" name="caput" value="Ada"> Ada
								<input type="radio" name="caput" value="Tidak ada"> Tidak ada
							</div>

							<div class="mb-3">
								<label>Cephalhematoma:</label><br>
								<input type="radio" name="cephalhematoma" value="Ada"> Ada
								<input type="radio" name="cephalhematoma" value="Tidak ada"> Tidak ada
							</div>

							<div class="mb-3">
								<label>Telinga:</label><br>
								<input type="radio" name="telinga" value="Normal"> Normal
								<input type="radio" name="telinga" value="Abnormal"> Abnormal
							</div>

							<div class="mb-3">
								<label>Hidung:</label><br>
								<input type="checkbox" name="hidung[]" value="Simetris"> Simetris
								<input type="checkbox" name="hidung[]" value="Asimetris"> Asimetris
								<input type="checkbox" name="hidung[]" value="Napas cuping hidung"> Napas cuping hidung
								<input type="checkbox" name="hidung[]" value="Sekret"> Sekret
							</div>

							<div class="mb-3">
								<label>Mata - Kelainan:</label><br>
								<input type="radio" name="mata_kelainan" value="Tidak"> Tidak
								<input type="radio" name="mata_kelainan" value="Ada"> Ada
							</div>

							<div class="mb-3">
								<label>Sekret Mata:</label><br>
								<input type="radio" name="sekret_mata" value="Ada"> Ada
								<input type="radio" name="sekret_mata" value="Tidak ada"> Tidak ada
							</div>

							<div class="mb-3">
								<label>Sclera Mata:</label><br>
								<input type="checkbox" name="sclera[]" value="Ikterus"> Ikterus
								<input type="checkbox" name="sclera[]" value="Perdarahan"> Perdarahan
							</div>

							<div class="mb-3">
								<label>Kelainan Gigi dan Mulut:</label><br>
								<input type="radio" name="gigi_mulut" value="Ada"> Ada
								<input type="text" name="gigi_ket" class="form-control mb-2" placeholder="Jika ada, sebutkan...">
								<input type="radio" name="gigi_mulut" value="Tidak ada"> Tidak ada
							</div>

							<div class="mb-3">
								<label>Kebersihan Bibir:</label><br>
								<input type="radio" name="bibir" value="Bersih"> Bersih
								<input type="radio" name="bibir" value="Tidak"> Tidak
							</div>

							<div class="mb-3">
								<label>Kebersihan Lidah:</label><br>
								<input type="radio" name="lidah" value="Bersih"> Bersih
								<input type="radio" name="lidah" value="Tidak"> Tidak
							</div>

							<div class="mb-3">
								<label>Perdarahan Gusi:</label><br>
								<input type="radio" name="gusi" value="Ada"> Ada
								<input type="radio" name="gusi" value="Tidak ada"> Tidak ada
							</div>

							<div class="mb-3">
								<label>Reflek Menelan:</label><br>
								<input type="radio" name="reflek_menelan" value="Ada"> Ada
								<input type="radio" name="reflek_menelan" value="Tidak ada"> Tidak ada
							</div>

							<button type="submit" class="btn btn-primary">Simpan</button>

						</div>
					</div>

					<div class="card mb-4">
						<div class="card-header bg-info text-white">
							<h5>C. DADA DAN PARU-PARU</h5>
						</div>
						<div class="card-body">
							<form method="post">
								<div class="mb-3">
									<label>Frekuensi Nafas (x/menit):</label>
									<input type="number" class="form-control" name="frekuensi_nafas">
								</div>
								<div class="mb-3">
									<label>SpO₂ (%):</label>
									<input type="number" class="form-control" name="spo2">
								</div>
								<div class="mb-3">
									<label>Bentuk Dada:</label><br>
									<input type="radio" name="bentuk_dada" value="Simetris"> Simetris
									<input type="radio" name="bentuk_dada" value="Asimetris"> Asimetris
								</div>
								<div class="mb-3">
									<label>Retraksi Dada:</label><br>
									<input type="radio" name="retraksi_dada" value="Ada"> Ada
									<input type="radio" name="retraksi_dada" value="Tidak ada"> Tidak ada
								</div>
								<div class="mb-3">
									<label>Irama Nafas:</label><br>
									<input type="radio" name="irama_nafas" value="Teratur"> Teratur
									<input type="radio" name="irama_nafas" value="Tidak teratur"> Tidak teratur
								</div>

								<hr>
								<h6>Down Score (Pernafasan Bayi Baru Lahir)</h6>
								<div class="table-responsive mb-3">
									<table class="table table-bordered">
										<thead class="table-light">
											<tr><th>No</th><th>Parameter</th><th>Poin</th><th>Skor Pasien</th></tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td><td>Frekuensi Nafas</td>
												<td>
													<select name="ds_frekuensi" class="form-select">
														<option value="0"><60 x/mnt</option>
															<option value="1">60-80 x/mnt</option>
															<option value="2">>80 x/mnt</option>
														</select>
													</td>
													<td><input type="number" name="skor_frekuensi" class="form-control"></td>
												</tr>
												<tr>
													<td>2</td><td>Retraksi</td>
													<td>
														<select name="ds_retraksi" class="form-select">
															<option value="0">Tidak ada</option>
															<option value="1">Ringan</option>
															<option value="2">Berat</option>
														</select>
													</td>
													<td><input type="number" name="skor_retraksi" class="form-control"></td>
												</tr>
												<tr>
													<td>3</td><td>Sianosis</td>
													<td>
														<select name="ds_sianosis" class="form-select">
															<option value="0">Tidak</option>
															<option value="1">Hilang dgn O2</option>
															<option value="2">Menetap dgn O2</option>
														</select>
													</td>
													<td><input type="number" name="skor_sianosis" class="form-control"></td>
												</tr>
												<tr>
													<td>4</td><td>Air Entry</td>
													<td>
														<select name="ds_air_entry" class="form-select">
															<option value="0">Bilateral baik</option>
															<option value="1">Penurunan ringan</option>
															<option value="2">Tidak masuk</option>
														</select>
													</td>
													<td><input type="number" name="skor_air_entry" class="form-control"></td>
												</tr>
												<tr>
													<td>5</td><td>Merintih</td>
													<td>
														<select name="ds_merintih" class="form-select">
															<option value="0">Tidak merintih</option>
															<option value="1">Dgn stetoskop</option>
															<option value="2">Tanpa alat bantu</option>
														</select>
													</td>
													<td><input type="number" name="skor_merintih" class="form-control"></td>
												</tr>
											</tbody>
										</table>
									</div>

									<div class="mb-3">
										<label>Total Skor Down Score:</label>
										<input type="number" class="form-control" name="total_down_score">
									</div>

									<div class="mb-3">
										<label>Suara Nafas:</label><br>
										<input type="checkbox" name="suara_nafas[]" value="Vesikuler"> Vesikuler
										<input type="checkbox" name="suara_nafas[]" value="Ronchi"> Ronchi
										<input type="checkbox" name="suara_nafas[]" value="Wheezing"> Wheezing
										<input type="checkbox" name="suara_nafas[]" value="Crackles"> Crackles
									</div>

									<div class="mb-3">
										<label>Respirasi:</label><br>
										<input type="radio" name="respirasi" value="Spontan tanpa alat bantu"> Spontan tanpa alat bantu
										<input type="radio" name="respirasi" value="Spontan dengan alat bantu"> Spontan dengan alat bantu
									</div>

									<hr>
									<h6>Masalah Keperawatan</h6>
									<div class="mb-3">
										<input type="checkbox" name="masalah[]" value="Gangguan ventilasi spontan"> Gangguan ventilasi spontan<br>
										<input type="checkbox" name="masalah[]" value="Bersihan jalan napas tidak efektif"> Bersihan jalan napas tidak efektif<br>
										<input type="checkbox" name="masalah[]" value="Pola napas tidak efektif"> Pola napas tidak efektif<br>
										<input type="checkbox" name="masalah[]" value="Gangguan pertukaran gas"> Gangguan pertukaran gas<br>
										<input type="text" name="masalah_lain" class="form-control mt-2" placeholder="Lainnya...">
									</div>

									<button type="submit" class="btn btn-primary">Simpan</button>

								</div>
							</div>

							<div class="card mb-4">
								<div class="card-header bg-info text-white">
									<h5>D. JANTUNG</h5>
								</div>
								<div class="card-body">
									<form method="post">
										<div class="mb-3">
											<label>Nadi (x/menit):</label>
											<input type="number" class="form-control" name="nadi">
										</div>

										<div class="mb-3">
											<label>CRT:</label><br>
											<input type="radio" name="crt" value="<2 detik"> <2 detik
												<input type="radio" name="crt" value=">2 detik"> >2 detik
											</div>

											<div class="mb-3">
												<label>Denyut Jantung (x/menit):</label>
												<input type="number" class="form-control" name="frekuensi_denyut">
												<div class="form-check">
													<input class="form-check-input" type="radio" name="kekuatan_denyut" value="Kuat"> Kuat
													<input class="form-check-input" type="radio" name="kekuatan_denyut" value="Lemah"> Lemah
												</div>
												<div class="form-check">
													<input class="form-check-input" type="radio" name="irama_denyut" value="Teratur"> Teratur
													<input class="form-check-input" type="radio" name="irama_denyut" value="Tidak teratur"> Tidak teratur
												</div>
											</div>

											<div class="mb-3">
												<label>Suara Jantung:</label><br>
												<input type="checkbox" name="suara_jantung[]" value="S1/S2 Tunggal"> S1/S2 Tunggal
												<input type="checkbox" name="suara_jantung[]" value="Murmur"> Murmur
												<input type="checkbox" name="suara_jantung[]" value="Friction rub"> Friction rub
												<input type="checkbox" name="suara_jantung[]" value="Gallop"> Gallop
											</div>

											<div class="mb-3">
												<label>Warna Kulit:</label><br>
												<input type="radio" name="warna_kulit" value="Normal"> Normal
												<input type="radio" name="warna_kulit" value="Sianosis"> Sianosis
											</div>

											<div class="mb-3">
												<label>Edema:</label><br>
												<input type="radio" name="edema" value="Ya"> Ya
												<input type="radio" name="edema" value="Tidak"> Tidak
											</div>

											<div class="mb-3">
												<label>Irama Jantung:</label><br>
												<input type="radio" name="irama_jantung" value="Reguler"> Reguler
												<input type="radio" name="irama_jantung" value="Ireguler"> Ireguler
											</div>

											<div class="mb-3">
												<label>Perfusi Perifer:</label><br>
												<input type="checkbox" name="perfusi[]" value="Hangat"> Hangat
												<input type="checkbox" name="perfusi[]" value="Dingin"> Dingin
												<input type="checkbox" name="perfusi[]" value="Kering"> Kering
												<input type="checkbox" name="perfusi[]" value="Basah"> Basah
												<input type="checkbox" name="perfusi[]" value="Merah"> Merah
												<input type="checkbox" name="perfusi[]" value="Pucat"> Pucat
											</div>

											<hr>
											<h6>Masalah Keperawatan</h6>
											<div class="mb-3">
												<input type="checkbox" name="masalah[]" value="Penurunan curah jantung"> Penurunan curah jantung<br>
												<input type="text" name="masalah_lain" class="form-control mt-2" placeholder="Lainnya...">
											</div>

											<button type="submit" class="btn btn-primary">Simpan</button>



										</div>
									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>E. ABDOMEN</h5>
										</div>
										<div class="card-body">
											<label for="lingkar_abdomen">Lingkar abdomen:</label>
											<input type="text" id="lingkar_abdomen" name="lingkar_abdomen" class="form-control" placeholder="……... cm">

											<label>Supel:</label>
											<input type="checkbox" name="supel" value="Supel"> Supel
											<br>
											<label>Distended (Kembung):</label>
											<input type="checkbox" name="distended" value="Distended"> Kembung
											<br>

											<label for="bising_usus">Bising usus:</label>
											<select name="bising_usus" id="bising_usus" class="form-control">
												<option value="Ada">Ada</option>
												<option value="Tidak ada">Tidak ada</option>
											</select>

											<label for="peristaltik_usus">Peristaltik usus:</label>
											<input type="text" id="peristaltik_usus" name="peristaltik_usus" class="form-control" placeholder="……... x/menit">

											<label>Tali pusat:</label>
											<input type="checkbox" name="tali_pusat[]" value="Basah"> Basah
											<input type="checkbox" name="tali_pusat[]" value="Kering"> Kering
											<input type="checkbox" name="tali_pusat[]" value="Layu"> Layu
											<input type="checkbox" name="tali_pusat[]" value="Segar"> Segar
											<input type="checkbox" name="tali_pusat[]" value="Bau"> Bau
											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
										</div>

									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>F. GENITALIA</h5>
										</div>
										<div class="card-body">
											<label for="lubang_anus">Lubang Anus:</label>
											<input type="checkbox" name="lubang_anus" value="Ada"> Ada, ………
											<input type="checkbox" name="lubang_anus" value="Tidak ada"> Tidak ada
											<br>

											<h6>Laki-laki</h6>
											<label for="scrotum">Scrotum:</label>
											<input type="checkbox" name="scrotum" value="Rugae jelas"> Rugae jelas
											<input type="checkbox" name="scrotum" value="Rugae tidak jelas"> Rugae tidak jelas
											<br>

											<label for="testis">Testis:</label>
											<input type="checkbox" name="testis" value="Sudah turun"> Sudah turun
											<input type="checkbox" name="testis" value="Belum turun"> Belum turun
											<br>

											<label for="kelainan_laki_laki">Kelainan:</label>
											<input type="checkbox" name="kelainan_laki_laki" value="Ada"> Ada, ………
											<input type="checkbox" name="kelainan_laki_laki" value="Tidak ada"> Tidak ada
											<br>

											<h6>Perempuan</h6>
											<label for="labia_major">Labia mayor menutup labia minor:</label>
											<input type="checkbox" name="labia_major" value="Menutup"> Menutup
											<input type="checkbox" name="labia_major" value="Belum menutup"> Belum menutup
											<br>

											<label for="kelamin_ambigu">Kelamin ambigu:</label>
											<input type="checkbox" name="kelamin_ambigu" value="Ya"> Ya
											<input type="checkbox" name="kelamin_ambigu" value="Tidak"> Tidak
											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
										</div>
									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>G. EKSTREMITAS</h5>
										</div>
										<div class="card-body">
											<label for="gerakan">Gerakan:</label>
											<input type="checkbox" name="gerakan" value="Aktif"> Aktif
											<input type="checkbox" name="gerakan" value="Kurang Aktif"> Kurang Aktif
											<br>

											<label for="ekstremitas_atas">Ekstremitas atas:</label>
											<input type="checkbox" name="ekstremitas_atas" value="Normal"> Normal
											<input type="checkbox" name="ekstremitas_atas" value="Abnormal"> Abnormal
											<br>

											<label for="ekstremitas_bawah">Ekstremitas bawah:</label>
											<input type="checkbox" name="ekstremitas_bawah" value="Normal"> Normal
											<input type="checkbox" name="ekstremitas_bawah" value="Abnormal"> Abnormal
											<br>

											<label for="kelainan_tulang">Kelainan tulang:</label>
											<input type="checkbox" name="kelainan_tulang" value="Ada"> Ada
											<input type="checkbox" name="kelainan_tulang" value="Tidak ada"> Tidak ada
											<br>

											<label for="tulang_belakang">Tulang belakang:</label>
											<input type="checkbox" name="tulang_belakang" value="Normal"> Normal
											<input type="checkbox" name="tulang_belakang" value="Abnormal"> Abnormal
											<br>

											<label for="kejang">Kejang:</label>
											<input type="checkbox" name="kejang" value="Ya"> Ya
											<input type="checkbox" name="kejang" value="Tidak"> Tidak
											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
										</div>
									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>H. REFLEK</h5>
										</div>
										<div class="card-body">
											<label for="moro">Moro:</label>
											<input type="checkbox" name="moro" value="Kuat"> Kuat
											<input type="checkbox" name="moro" value="Lemah"> Lemah
											<br>

											<label for="babinski">Babinski:</label>
											<input type="checkbox" name="babinski" value="Kuat"> Kuat
											<input type="checkbox" name="babinski" value="Lemah"> Lemah
											<br>

											<label for="tonik_neck">Tonik neck:</label>
											<input type="checkbox" name="tonik_neck" value="Kuat"> Kuat
											<input type="checkbox" name="tonik_neck" value="Lemah"> Lemah
											<br>

											<label for="rooting">Rooting:</label>
											<input type="checkbox" name="rooting" value="Kuat"> Kuat
											<input type="checkbox" name="rooting" value="Lemah"> Lemah
											<br>

											<label for="menggenggam">Menggenggam:</label>
											<input type="checkbox" name="menggenggam" value="Kuat"> Kuat
											<input type="checkbox" name="menggenggam" value="Lemah"> Lemah
											<br>

											<label for="menghisap">Menghisap:</label>
											<input type="checkbox" name="menghisap" value="Kuat"> Kuat
											<input type="checkbox" name="menghisap" value="Lemah"> Lemah
											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
										</div>
									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>I. TONUS/AKTIVITAS</h5>
										</div>
										<div class="card-body">
											<label for="aktivitas">Aktivitas:</label>
											<input type="checkbox" name="aktivitas" value="Aktif"> Aktif
											<input type="checkbox" name="aktivitas" value="Tenang"> Tenang
											<input type="checkbox" name="aktivitas" value="Letargi"> Letargi
											<input type="checkbox" name="aktivitas" value="Kejang"> Kejang
											<br>

											<label for="menangis">Menangis:</label>
											<input type="checkbox" name="menangis" value="Keras"> Keras
											<input type="checkbox" name="menangis" value="Lemah"> Lemah
											<input type="checkbox" name="menangis" value="Melengking"> Melengking
											<input type="checkbox" name="menangis" value="Sulit menangis"> Sulit menangis

											<hr>
											<h5>MASALAH</h5>

											<label for="disorganisasi_perilaku_bayi">Disorganisasi perilaku bayi:</label>
											<input type="checkbox" name="disorganisasi_perilaku_bayi" value="Ada"> Ada
											<input type="checkbox" name="disorganisasi_perilaku_bayi" value="Tidak ada"> Tidak ada
											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
										</div>



									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>J. STATUS FUNGSIONAL</h5>
										</div>
										<div class="card-body">
											<h6>1. Nutrisi dan Hidrasi</h6>
											<label for="keluhan">Keluhan:</label>
											<input type="checkbox" name="keluhan" value="Tidak ada keluhan"> Tidak ada keluhan
											<input type="checkbox" name="keluhan" value="Mual"> Mual								
											<input type="checkbox" name="keluhan[]" value="Muntah" id="muntah">
											<label for="muntah">Muntah</label>
											<div id="muntahDetail" style="display:none;">
												<input type="number" name="muntah_x" placeholder="Jumlah x" class="form-control" style="width: 100px; display: inline-block;">
												<input type="text" name="muntah_warna" placeholder="Warna" class="form-control" style="width: 150px; display: inline-block;">
											</div>

											<script>
												document.getElementById('muntah').addEventListener('change', function () {
													var detailDiv = document.getElementById('muntahDetail');
													if (this.checked) {
														detailDiv.style.display = 'block'; 
													} else {
														detailDiv.style.display = 'none'; 
													}
												});
											</script>

											<br>

											<label for="asi_eksklusif">Asi Eksklusif:</label>
											<input type="checkbox" name="asi_eksklusif" value="Terjadwal"> Terjadwal
											<input type="checkbox" name="asi_eksklusif" value="Bebas"> Bebas
											<br>

											<label for="pasi">Pasi:</label>
											<input type="checkbox" name="pasi" value="Terjadwal"> Terjadwal
											<input type="checkbox" name="pasi" value="Bebas"> Bebas
											<br>

											<label for="alat_bantu">Alat bantu:</label>
											<input type="checkbox" name="alat_bantu" value="Tidak ada"> Tidak ada
											<input type="checkbox" name="alat_bantu" value="OGT"> OGT
											<br>

											<label for="jenis_susu">Jenis susu:</label>
											<input type="text" name="jenis_susu" class="form-control" placeholder="……………………..">
											<br>

											<label for="takaran_susu">Takaran susu:</label>
											<input type="text" name="takaran_susu" class="form-control" placeholder="………………..cc/hari">
											<br>

											<h6>2. Eliminasi dan pelepasan</h6>
											<label for="bak">BAK:</label>
											<input type="checkbox" name="bak" value="Normal"> Normal
											<input type="checkbox" name="bak" value="Abnormal"> Abnormal
											<br>

											<label for="bab">BAB:</label>
											<input type="checkbox" name="bab" value="Normal"> Normal
											<input type="checkbox" name="bab" value="Abnormal"> Abnormal
											<br>

											<label for="mekonium">Mekonium:</label>
											<input type="checkbox" name="mekonium" value="Ada"> Ada
											<input type="checkbox" name="mekonium" value="Tidak"> Tidak
											<br>

											<label for="alat_bantu_berkemih">Alat bantu berkemih:</label>
											<input type="checkbox" name="alat_bantu_berkemih" value="Tidak ada"> Tidak ada
											<input type="checkbox" name="alat_bantu_berkemih" value="Dower kateter"> Dower kateter
											<input type="text" name="dower_kateter" class="form-control" placeholder="……………………….">

											<hr>
											<h5>MASALAH</h5>

											<label for="disorganisasi_perilaku_bayi">Disorganisasi perilaku bayi:</label>
											<input type="checkbox" name="disorganisasi_perilaku_bayi" value="Ada"> Ada
											<input type="checkbox" name="disorganisasi_perilaku_bayi" value="Tidak ada"> Tidak ada
											<br>

											<label for="nausea">Nausea:</label>
											<input type="checkbox" name="nausea" value="Ada"> Ada
											<input type="checkbox" name="nausea" value="Tidak ada"> Tidak ada
											<br>

											<label for="risiko_aspirasi">Risiko aspirasi:</label>
											<input type="checkbox" name="risiko_aspirasi" value="Ada"> Ada
											<input type="checkbox" name="risiko_aspirasi" value="Tidak ada"> Tidak ada
											<br>

											<label for="hipervolemia">Hipervolemia:</label>
											<input type="checkbox" name="hipervolemia" value="Ada"> Ada
											<input type="checkbox" name="hipervolemia" value="Tidak ada"> Tidak ada
											<br>

											<label for="hipovolemia">Hipovolemia:</label>
											<input type="checkbox" name="hipovolemia" value="Ada"> Ada
											<input type="checkbox" name="hipovolemia" value="Tidak ada"> Tidak ada
											<br>

											<label for="risiko_kurang_volume_cairan">Risiko kurang volume cairan:</label>
											<input type="checkbox" name="risiko_kurang_volume_cairan" value="Ada"> Ada
											<input type="checkbox" name="risiko_kurang_volume_cairan" value="Tidak ada"> Tidak ada
											<br>

											<label for="defisit_nutrisi">Defisit Nutrisi:</label>
											<input type="checkbox" name="defisit_nutrisi" value="Ada"> Ada
											<input type="checkbox" name="defisit_nutrisi" value="Tidak ada"> Tidak ada
											<br>

											<label for="gangguan_eliminasi_urin">Gangguan eliminasi urin:</label>
											<input type="checkbox" name="gangguan_eliminasi_urin" value="Ada"> Ada
											<input type="checkbox" name="gangguan_eliminasi_urin" value="Tidak ada"> Tidak ada
											<br>

											<label for="retensi_urin">Retensi urin:</label>
											<input type="checkbox" name="retensi_urin" value="Ada"> Ada
											<input type="checkbox" name="retensi_urin" value="Tidak ada"> Tidak ada
											<br>

											<label for="diare">Diare:</label>
											<input type="checkbox" name="diare" value="Ada"> Ada
											<input type="checkbox" name="diare" value="Tidak ada"> Tidak ada
											<br>

											<label for="konstipasi">Konstipasi:</label>
											<input type="checkbox" name="konstipasi" value="Ada"> Ada
											<input type="checkbox" name="konstipasi" value="Tidak ada"> Tidak ada
											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

										</div>
									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>Pemeriksaan Nyeri – Skala NIPS</h5>
										</div>
										<div class="card-body">
											<table class="table table-bordered">
												<thead>
													<tr>
														<th>No</th>
														<th>Parameter</th>
														<th>Poin</th>
														<th>Skor Pasien</th>
													</tr>
												</thead>
												<tbody>
													<!-- Ekspresi Wajah -->
													<tr>
														<td>1</td>
														<td>Ekspresi Wajah</td>
														<td>
															a. Wajah tenang, ekspresi netral - 0 <br>
															b. Otot wajah tegang, alis berkerut, dagu dan rahang tegang - 1
														</td>
														<td>
															<input type="radio" name="ekspresi_wajah" value="0"> 0
															<input type="radio" name="ekspresi_wajah" value="1"> 1
														</td>
													</tr>
													<!-- Menangis -->
													<tr>
														<td>2</td>
														<td>Menangis</td>
														<td>
															a. Tenang, tidak menangis - 0 <br>
															b. Merengek ringan, kadang-kadang - 1 <br>
															c. Berteriak kencang, menarik, melengking terus-terusan - 2
														</td>
														<td>
															<input type="radio" name="menangis" value="0"> 0
															<input type="radio" name="menangis" value="1"> 1
															<input type="radio" name="menangis" value="2"> 2
														</td>
													</tr>
													<!-- Pola Pernafasan -->
													<tr>
														<td>3</td>
														<td>Pola Pernafasan</td>
														<td>
															a. Pola pernapasan bayi normal - 0 <br>
															b. Tidak teratur, lebih cepat dari biasanya, tersedak, nafas tertahan - 1
														</td>
														<td>
															<input type="radio" name="pola_pernafasan" value="0"> 0
															<input type="radio" name="pola_pernafasan" value="1"> 1
														</td>
													</tr>
													<!-- Lengan -->
													<tr>
														<td>4</td>
														<td>Lengan</td>
														<td>
															a. Tidak ada kekakuan otot, gerakan tangan acak sekali-kali - 0 <br>
															b. Tegang, lengan lurus, kaku dan/atau eksistensi, cepat eksistensi, fleksi - 1
														</td>
														<td>
															<input type="radio" name="lengan" value="0"> 0
															<input type="radio" name="lengan" value="1"> 1
														</td>
													</tr>
													<!-- Kaki -->
													<tr>
														<td>5</td>
														<td>Kaki</td>
														<td>
															a. Tidak ada kekakuan otot, gerakan kaki acak sekali-kali - 0 <br>
															b. Tegang, kaki lurus, kaku, dan/atau eksistensi, eksistensi cepat, fleksi - 1
														</td>
														<td>
															<input type="radio" name="kaki" value="0"> 0
															<input type="radio" name="kaki" value="1"> 1
														</td>
													</tr>
													<!-- Kesadaran -->
													<tr>
														<td>6</td>
														<td>Kesadaran</td>
														<td>
															a. Tenang, tidur damai atau gerakan kaki acak yang terjaga - 0 <br>
															b. Terjaga, gelisah dan meronta-ronta - 1
														</td>
														<td>
															<input type="radio" name="kesadaran" value="0"> 0
															<input type="radio" name="kesadaran" value="1"> 1
														</td>
													</tr>
													<!-- Total Skor -->
													<tr>
														<td colspan="3"><strong>Total Skor</strong></td>
														<td><input type="text" name="total_skor" class="form-control" readonly></td>
													</tr>
												</tbody>
											</table>

											<div class="mt-3">
												<h6>Interpretasi Skor</h6>
												<ul>
													<li>0-2 = Nyeri ringan - Tidak nyeri</li>
													<li>3-4 = Nyeri sedang – Nyeri ringan</li>
													<li>> 4 = Nyeri hebat</li>
												</ul>
											</div>

											<hr>
											<h5>MASALAH</h5>

											<label for="nyeri_akut">Nyeri Akut:</label>
											<input type="checkbox" name="nyeri_akut" value="Ada"> Ada
											<input type="checkbox" name="nyeri_akut" value="Tidak ada"> Tidak ada
											<br>

											<label for="nyeri_kronis">Nyeri Kronis:</label>
											<input type="checkbox" name="nyeri_kronis" value="Ada"> Ada
											<input type="checkbox" name="nyeri_kronis" value="Tidak ada"> Tidak ada

											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

										</div>
									</div>

									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>II. PROTEKSI/KESELAMATAN</h5>
											<h6>Asesmen Lanjutan Risiko Jatuh (Anak dengan Skala Humpty Dumpty)</h6>
										</div>
										<div class="card-body">
											<table class="table table-bordered">
												<thead>
													<tr>
														<th>Faktor Risiko</th>
														<th>Skala</th>
														<th>Poin</th>
														<th>Skor Pasien</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Umur</td>
														<td>
															<ul>
																<li><b>Kurang dari 3 tahun:</b> 4</li>
																<li><b>3 tahun – 7 tahun:</b> 3</li>
																<li><b>7 tahun – 13 tahun:</b> 2</li>
																<li><b>Lebih dari 13 tahun:</b> 1</li>
															</ul>
														</td>
														<td>
															<input type="radio" name="umur" value="4"> Kurang dari 3 tahun<br>
															<input type="radio" name="umur" value="3"> 3 tahun – 7 tahun<br>
															<input type="radio" name="umur" value="2"> 7 tahun – 13 tahun<br>
															<input type="radio" name="umur" value="1"> Lebih dari 13 tahun
														</td>
													</tr>
													<tr>
														<td>Jenis Kelamin</td>
														<td>
															<ul>
																<li><b>Laki-laki:</b> 2</li>
																<li><b>Wanita:</b> 1</li>
															</ul>
														</td>
														<td>
															<input type="radio" name="jenis_kelamin" value="2"> Laki-laki<br>
															<input type="radio" name="jenis_kelamin" value="1"> Wanita
														</td>
													</tr>
													<tr>
														<td>Diagnosa</td>
														<td>
															<ul>
																<li><b>Neurologi:</b> 4</li>
																<li><b>Respiratori, dehidrasi, anemia, anorexia, syncope:</b> 3</li>
																<li><b>Perilaku:</b> 2</li>
																<li><b>Lain-lain:</b> 1</li>
															</ul>
														</td>
														<td>
															<input type="radio" name="diagnosa" value="4"> Neurologi<br>
															<input type="radio" name="diagnosa" value="3"> Respiratori, dehidrasi, anemia, anorexia, syncope<br>
															<input type="radio" name="diagnosa" value="2"> Perilaku<br>
															<input type="radio" name="diagnosa" value="1"> Lain-lain
														</td>
													</tr>
													<tr>
														<td>Gangguan Kognitif</td>
														<td>
															<ul>
																<li><b>Keterbatasan daya pikir:</b> 3</li>
																<li><b>Pelupa:</b> 2</li>
																<li><b>Dapat menggunakan daya pikir tanpa hambatan:</b> 1</li>
															</ul>
														</td>
														<td>
															<input type="radio" name="gangguan_kognitif" value="3"> Keterbatasan daya pikir<br>
															<input type="radio" name="gangguan_kognitif" value="2"> Pelupa<br>
															<input type="radio" name="gangguan_kognitif" value="1"> Dapat menggunakan daya pikir tanpa hambatan
														</td>
													</tr>
													<tr>
														<td>Faktor Lingkungan</td>
														<td>
															<ul>
																<li><b>Riwayat jatuh atau bayi/balita yang ditempatkan di tempat tidur:</b> 4</li>
																<li><b>Pasien menggunakan alat bantu/bayi balita dalam ayunan:</b> 3</li>
																<li><b>Pasien ditempat tidur standar:</b> 2</li>
																<li><b>Area pasien rawat jalan:</b> 1</li>
															</ul>
														</td>
														<td>
															<input type="radio" name="faktor_lingkungan" value="4"> Riwayat jatuh atau bayi/balita yang ditempatkan di tempat tidur<br>
															<input type="radio" name="faktor_lingkungan" value="3"> Pasien menggunakan alat bantu/bayi balita dalam ayunan<br>
															<input type="radio" name="faktor_lingkungan" value="2"> Pasien ditempat tidur standar<br>
															<input type="radio" name="faktor_lingkungan" value="1"> Area pasien rawat jalan
														</td>
													</tr>
													<tr>
														<td>Respon terhadap pembedahan, sedasi, dan anestesi</td>
														<td>
															<ul>
																<li><b>Dalam 24 jam:</b> 3</li>
																<li><b>Dalam 48 jam:</b> 2</li>
																<li><b>Lebih dari 48 jam/tidak ada respon:</b> 1</li>
															</ul>
														</td>
														<td>
															<input type="radio" name="respon_pembedahan" value="3"> Dalam 24 jam<br>
															<input type="radio" name="respon_pembedahan" value="2"> Dalam 48 jam<br>
															<input type="radio" name="respon_pembedahan" value="1"> Lebih dari 48 jam/tidak ada respon
														</td>
													</tr>
													<tr>
														<td>Penggunaan obat-obatan</td>
														<td>
															<ul>
																<li><b>Penggunaan bersamaan sedative, barbiturate, anti depresan, diuretik, narkotik:</b> 3</li>
																<li><b>Salah satu dari obat di atas:</b> 2</li>
																<li><b>Obat-obatan lainnya/tanpa obat:</b> 1</li>
															</ul>
														</td>
														<td>
															<input type="radio" name="penggunaan_obat" value="3"> Penggunaan bersamaan sedative, barbiturate, anti depresan, diuretik, narkotik<br>
															<input type="radio" name="penggunaan_obat" value="2"> Salah satu dari obat di atas<br>
															<input type="radio" name="penggunaan_obat" value="1"> Obat-obatan lainnya/tanpa obat
														</td>
													</tr>
													<tr>
														<td colspan="3"><strong>Total Skor</strong></td>
														<td>
															<input type="number" name="total_score" class="form-control" readonly>
														</td>
													</tr>
												</tbody>
											</table>

											<p><strong>Interpretasi Skor:</strong></p>
											<ul>
												<li>> 12 = Risiko tinggi</li>
												<li>7 - 11 = Risiko sedang</li>
												<li>0 – 7 = Risiko rendah</li>
											</ul>


											<hr>
											<h5>MASALAH</h5>

											<label for="risiko_cedera">Risiko Cedera:</label>
											<input type="checkbox" name="risiko_cedera" value="Ada"> Ada
											<input type="checkbox" name="risiko_cedera" value="Tidak ada"> Tidak ada
											<br>

											<label for="risiko_trauma_jatuh">Risiko Trauma/Jatuh:</label>
											<input type="checkbox" name="risiko_trauma_jatuh" value="Ada"> Ada
											<input type="checkbox" name="risiko_trauma_jatuh" value="Tidak ada"> Tidak ada
											<br>

											<label for="lainnya">Lainnya:</label>
											<input type="text" name="lainnya" class="form-control mt-2" placeholder="Masukkan masalah lainnya...">

											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

										</div>
									</div>


									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>III. KEBUTUHAN BIO, PSIKO, SOSIAL, SPIRITUAL, KULTURAL</h5>
										</div>
										<div class="card-body">
											<label for="reaksi_hospitalisasi">Reaksi Hospitalisasi:</label><br>
											<label for="tinggal_bersama">Tinggal Bersama:</label><br>
											<input type="checkbox" name="tinggal_bersama" value="Ayah"> Ayah
											<input type="checkbox" name="tinggal_bersama" value="Ibu"> Ibu
											<input type="checkbox" name="tinggal_bersama" value="Nenek"> Nenek
											<input type="checkbox" name="tinggal_bersama" value="Orang lain"> Orang lain<br>
											<input type="text" name="reaksi_hospitalisasi_lain" class="form-control mt-2" placeholder="Lainnya..."><br>

											<label for="status_ekonomi">Status Ekonomi:</label><br>
											<input type="radio" name="status_ekonomi" value="Pembayaran Pribadi/Perorangan"> Pembayaran Pribadi/Perorangan<br>
											<input type="text" name="jaminan_kesehatan_asuransi" class="form-control mt-2" placeholder="Jaminan Kesehatan/Asuransi..."><br>

											<label for="dampak_hospitalisasi">Dampak Hospitalisasi bagi Orang Tua:</label><br>
											<input type="text" name="dampak_hospitalisasi" class="form-control mt-2" placeholder="Dampak Hospitalisasi..."><br>

											<label for="pengobatan_budaya">Pengobatan Bertentangan dengan Budaya:</label><br>
											<input type="checkbox" name="pengobatan_budaya" value="Ada"> Ada
											<input type="checkbox" name="pengobatan_budaya" value="Tidak"> Tidak<br>

											<label for="pengobatan_keyakinan">Pengobatan Bertentangan dengan Keyakinan:</label><br>
											<input type="checkbox" name="pengobatan_keyakinan" value="Ada"> Ada
											<input type="checkbox" name="pengobatan_keyakinan" value="Tidak"> Tidak<br>

											<label for="pengobatan_alternatif">Pengobatan Alternatif:</label><br>
											<input type="checkbox" name="pengobatan_alternatif" value="Ada"> Ada
											<input type="checkbox" name="pengobatan_alternatif" value="Tidak"> Tidak<br>
										</div>
									</div>


									<div class="card mb-4">
										<div class="card-header bg-info text-white">
											<h5>IV. Discharge Planning (Rencana Pemulangan)</h5>
										</div>
										<div class="card-body">
											<table class="table table-bordered">
												<thead>
													<tr>
														<th>No</th>
														<th>Keterangan</th>
														<th>Ya</th>
														<th>Tidak</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1</td>
														<td>Kacacatan permanen/kongenital</td>
														<td><input type="checkbox" name="kacacatan_premanent" value="Ya"></td>
														<td><input type="checkbox" name="kacacatan_premanent" value="Tidak"></td>
													</tr>
													<tr>
														<td>2</td>
														<td>Tidak Tinggal bersama orang tua kandung atau keluarga terdekat</td>
														<td><input type="checkbox" name="tidak_tinggal_orang_tua" value="Ya"></td>
														<td><input type="checkbox" name="tidak_tinggal_orang_tua" value="Tidak"></td>
													</tr>
													<tr>
														<td>3</td>
														<td>Kurang perhatian oleh Orang tua dan atau keluarga sekitar</td>
														<td><input type="checkbox" name="kurang_perhatian" value="Ya"></td>
														<td><input type="checkbox" name="kurang_perhatian" value="Tidak"></td>
													</tr>
													<tr>
														<td>4</td>
														<td>Memerlukan Perawatan atau pengobatan berkelanjutan</td>
														<td><input type="checkbox" name="perawatan_berkelanjutan" value="Ya"></td>
														<td><input type="checkbox" name="perawatan_berkelanjutan" value="Tidak"></td>
													</tr>
													<tr>
														<td>5</td>
														<td>Pasien masih membutuhkan peralatan medis saat KRS (OGT, Oksigen, kateter urin, dll)</td>
														<td><input type="checkbox" name="peralatan_medis" value="Ya"></td>
														<td><input type="checkbox" name="peralatan_medis" value="Tidak"></td>
													</tr>
													<tr>
														<td>6</td>
														<td>Memerlukan bantuan dalam aktivitas sehari-hari</td>
														<td><input type="checkbox" name="bantuan_sehari_hari" value="Ya"></td>
														<td><input type="checkbox" name="bantuan_sehari_hari" value="Tidak"></td>
													</tr>
													<tr>
														<td>7</td>
														<td>Suspec. Penyakit jantung bawaan, suspec. Infeksi HIV-AIDS, suspec. Kanker, penyakit hati kronik, penyakit ginjal kronik, TB paru, luka bakar luas, keterlambatan perkembangan</td>
														<td><input type="checkbox" name="suspec_penyakit" value="Ya"></td>
														<td><input type="checkbox" name="suspec_penyakit" value="Tidak"></td>
													</tr>
												</tbody>
											</table>

											<div class="form-group mt-3">
												<label for="rm_18a10">Jika salah satu jawaban adalah "Ya", dilanjutkan pengisian RM 18a.10 Discharge Planning Terintegrasi:</label>
												<input type="text" name="rm_18a10" class="form-control mt-2" placeholder="Isi RM 18a.10...">
											</div>

											<hr>
											<h5>MASALAH</h5>

											<label for="gangguan_citra_tubuh">Gangguan Citra Tubuh:</label><br>
											<input type="checkbox" name="gangguan_citra_tubuh" value="Ada"> Ada
											<input type="checkbox" name="gangguan_citra_tubuh" value="Tidak ada"> Tidak ada
											<br>

											<label for="gangguan_hospitalisasi">Gangguan Hospitalisasi:</label><br>
											<input type="checkbox" name="gangguan_hospitalisasi" value="Ada"> Ada
											<input type="checkbox" name="gangguan_hospitalisasi" value="Tidak ada"> Tidak ada
											<br>

											<label for="lainnya">Lainnya:</label><br>
											<input type="text" name="lainnya" class="form-control mt-2" placeholder="Masukkan masalah lainnya...">

											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

										</div>
									</div>


									<div class="card mb-4">
										<div class="card-header bg-success text-white">
											<h5>ASSESMEN - PENANDATANGANAN</h5>
										</div>
										<div class="card-body">
											<label for="tanggal_asesmen">Selesai Asesmen Tanggal:</label><br>
											<input type="date" name="tanggal_asesmen" class="form-control" placeholder="Tanggal Asesmen"><br>

											<label for="jam_asesmen">Jam:</label><br>
											<input type="time" name="jam_asesmen" class="form-control" placeholder="Jam Asesmen"><br>

											<label for="perawat_asesmen">Perawat yang Melakukan Asesmen:</label><br>
											<input type="text" name="perawat_asesmen" class="form-control" placeholder="Nama Perawat"><br>
											<label for="tanda_tangan_perawat">Tanda Tangan:</label><br>
											<input type="text" name="tanda_tangan_perawat" class="form-control" placeholder="Tanda Tangan Perawat"><br>

											<label for="dpjp">DPJP:</label><br>
											<input type="text" name="nmdpjp" class="form-control" placeholder="Nama DPJP"><br>
											<label for="tanda_tangan_dpjp">Tanda Tangan:</label><br>
											<input type="text" name="tanda_tangan_dpjp" class="form-control" placeholder="Tanda Tangan DPJP"><br>

											<br>
											<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>

										</div>
									</div>

								</div>

							</form>

						</body>
					</div>

					<?php


					if (isset($_POST["simpan"])) {

						$lanjut='Y';

						if(strtoupper($userid)<>strtoupper($user)){
										// $lanjut='T';
							$eror = 'User Entry Tidak Sama dg User Entry Anamnesis Awal !!!';
						}

						$dpjp	= $_POST["dpjp"];
						$row2 = explode('-',$dpjp);

						$dpjp1  = $row2[0];
						$dpjp2 = $row2[1]; 

						if(empty($dpjp2)){
							$lanjut='T';
							$eror = 'DPJP tidak valid !!!';
						}


						if($lanjut=='Y'){

							// $dpjp	= $_POST["dpjp"];
							$tglrawat	= $_POST["tglrawat"];
							$neo1 = $_POST["neo1"];
							$neo2 = $_POST['neo2'];
							$neo3 = $_POST['neo3'];
							$neo4 = $_POST['neo4'];
							$neo5 = $_POST['neo5'];
							$neo6 = $_POST['neo6'];
							$neo7 = $_POST['neo7'];
							$neo8 = $_POST['neo8'];
							$neo9 = $_POST['neo9'];
							$neo10 = $_POST['neo10'];
							$neo11 = $_POST['neo11'];
							$neo12 = $_POST['neo12'];
							$neo13 = $_POST['neo13'];
							$neo14 = $_POST['neo14'];
							$neo15 = $_POST['neo15'];
							$neo16 = $_POST['neo16'];
							$neo17 = $_POST['neo17'];
							$neo18 = $_POST['neo18'];
							$neo19 = $_POST['neo19'];
							$neo20 = $_POST['neo20'];
							$neo21 = $_POST['neo21'];
							$neo22 = $_POST['neo22'];
							$neo23 = $_POST['neo23'];
							$neo24 = $_POST['neo24'];
							$neo25 = $_POST['neo25'];
							$neo26 = $_POST['neo26'];
							$neo27 = $_POST['neo27'];
							$neo28 = $_POST['neo28'];
							$neo29 = $_POST['neo29'];
							$neo30 = $_POST['neo30'];
							$neo31 = $_POST['neo31'];
							$neo32 = $_POST['neo32'];
							$neo33 = $_POST['neo33'];
							$neo34 = $_POST['neo34'];
							$neo35 = $_POST['neo35'];
							$neo36 = $_POST['neo36'];
							$neo37 = $_POST['neo37'];
							$neo38 = $_POST['neo38'];
							$neo39 = $_POST['neo39'];
							$neo40 = $_POST['neo40'];
							$neo41 = $_POST['neo41'];
							$neo42 = $_POST['neo42'];
							$neo43 = $_POST['neo43'];
							$neo44 = $_POST['neo44'];
							$neo45 = $_POST['neo45'];
							$neo46 = $_POST['neo46'];
							$neo47 = $_POST['neo47'];
							$neo48 = $_POST['neo48'];
							$neo49 = $_POST['neo49'];
							$neo50 = $_POST['neo50'];
							$neo51 = $_POST['neo51'];
							$neo52 = $_POST['neo52'];
							$neo53 = $_POST['neo53'];
							$neo54 = $_POST['neo54'];
							$neo55 = $_POST['neo55'];
							$neo56 = $_POST['neo56'];
							$neo57 = $_POST['neo57'];
							$neo58 = $_POST['neo58'];
							$neo59 = $_POST['neo59'];
							$neo60 = $_POST['neo60'];
							$neo61 = $_POST['neo61'];
							$neo62 = $_POST['neo62'];
							$neo63 = $_POST['neo63'];
							$neo64 = $_POST['neo64'];
							$neo65 = $_POST['neo65'];
							$neo66 = $_POST['neo66'];
							$neo67 = $_POST['neo67'];
							$neo68 = $_POST['neo68'];
							$neo69 = $_POST['neo69'];
							$neo70 = $_POST['neo70'];
							$neo71 = $_POST['neo71'];
							$neo72 = $_POST['neo72'];
							$neo73 = $_POST['neo73'];
							$neo74 = $_POST['neo74'];
							$neo75 = $_POST['neo75'];
							$neo76 = $_POST['neo76'];
							$neo77 = $_POST['neo77'];
							$neo78 = $_POST['neo78'];
							$neo79 = $_POST['neo79'];
							$neo80 = $_POST['neo80'];
							$neo81 = $_POST['neo81'];
							$neo82 = $_POST['neo82'];
							$neo83 = $_POST['neo83'];
							$neo84 = $_POST['neo84'];
							$neo85 = $_POST['neo85'];
							$neo86 = $_POST['neo86'];
							$neo87 = $_POST['neo87'];
							$neo88 = $_POST['neo88'];
							$neo89 = $_POST['neo89'];
							$neo90 = $_POST['neo90'];
							$neo91 = $_POST['neo91'];
							$neo92 = $_POST['neo92'];
							$neo93 = $_POST['neo93'];
							$neo94 = $_POST['neo94'];
							$neo95 = $_POST['neo95'];
							$neo96 = $_POST['neo96'];
							$neo97 = $_POST['neo97'];
							$neo98 = $_POST['neo98'];
							$neo99 = $_POST['neo99'];
							$neo100 = $_POST['neo100'];
							$neo101 = $_POST['neo101'];
							$neo102 = $_POST['neo102'];
							$neo103 = $_POST['neo103'];
							$neo104 = $_POST['neo104'];
							$neo105 = $_POST['neo105'];
							$neo106 = $_POST['neo106'];
							$neo107 = $_POST['neo107'];
							$neo108 = $_POST['neo108'];
							$neo109 = $_POST['neo109'];
							$neo110 = $_POST['neo110'];
							$neo111 = $_POST['neo111'];
							$neo112 = $_POST['neo112'];
							$neo113 = $_POST['neo113'];
							$neo114 = $_POST['neo114'];
							$neo115 = $_POST['neo115'];
							$neo116 = $_POST['neo116'];
							$neo117 = $_POST['neo117'];
							$neo118 = $_POST['neo118'];
							$neo119 = $_POST['neo119'];
							$neo120 = $_POST['neo120'];
							$neo121 = $_POST['neo121'];
							$neo122 = $_POST['neo122'];
							$neo123 = $_POST['neo123'];
							$neo124 = $_POST['neo124'];
							$neo125 = $_POST['neo125'];
							$neo126 = $_POST['neo126'];
							$neo127 = $_POST['neo127'];
							$neo128 = $_POST['neo128'];
							$neo129 = $_POST['neo129'];
							$neo130 = $_POST['neo130'];
							$neo131 = $_POST['neo131'];
							$neo132 = $_POST['neo132'];
							$neo133 = $_POST['neo133'];
							$neo134 = $_POST['neo134'];
							$neo135 = $_POST['neo135'];
							$neo136 = $_POST['neo136'];
							$neo137 = $_POST['neo137'];
							$neo138 = $_POST['neo138'];
							$neo139 = $_POST['neo139'];
							$neo140 = $_POST['neo140'];
							$neo141 = $_POST['neo141'];
							$neo142 = $_POST['neo142'];
							$neo143 = $_POST['neo143'];
							$neo144 = $_POST['neo144'];
							$neo145 = $_POST['neo145'];
							$neo146 = $_POST['neo146'];
							$neo147 = $_POST['neo147'];
							$neo148 = $_POST['neo148'];
							$neo149 = $_POST['neo149'];
							$neo150 = $_POST['neo150'];
							$neo151 = $_POST['neo151'];
							$neo152 = $_POST['neo152'];
							$neo153 = $_POST['neo153'];
							$neo154 = $_POST['neo154'];
							$neo155 = $_POST['neo155'];
							$neo156 = $_POST['neo156'];
							$neo157 = $_POST['neo157'];
							$neo158 = $_POST['neo158'];
							$neo159 = $_POST['neo159'];
							$neo160 = $_POST['neo160'];
							$neo161 = $_POST['neo161'];
							$neo162 = $_POST['neo162'];
							$neo163 = $_POST['neo163'];
							$neo164 = $_POST['neo164'];
							$neo165 = $_POST['neo165'];
							$neo166 = $_POST['neo166'];
							$neo167 = $_POST['neo167'];
							$neo168 = $_POST['neo168'];
							$neo169 = $_POST['neo169'];
							$neo170 = $_POST['neo170'];
							$neo171 = $_POST['neo171'];
							$neo172 = $_POST['neo172'];
							$neo173 = $_POST['neo173'];
							$neo174 = $_POST['neo174'];
							$neo175 = $_POST['neo175'];
							$neo176 = $_POST['neo176'];
							$neo177 = $_POST['neo177'];
							$neo178 = $_POST['neo178'];
							$neo179 = $_POST['neo179'];
							$neo180 = $_POST['neo180'];
							$neo181 = $_POST['neo181'];
							$neo182 = $_POST['neo182'];
							$neo183 = $_POST['neo183'];
							$neo184 = $_POST['neo184'];
							$neo185 = $_POST['neo185'];
							$neo186 = $_POST['neo186'];
							$neo187 = $_POST['neo187'];
							$neo188 = $_POST['neo188'];
							$neo189 = $_POST['neo189'];
							$neo190 = $_POST['neo190'];
							$neo191 = $_POST['neo191'];
							$neo192 = $_POST['neo192'];
							$neo193 = $_POST['neo193'];
							$neo194 = $_POST['neo194'];
							$neo195 = $_POST['neo195'];
							$neo196 = $_POST['neo196'];
							$neo197 = $_POST['neo197'];
							$neo198 = $_POST['neo198'];
							$neo199 = $_POST['neo199'];
							$neo200 = $_POST['neo200'];
							$neo201 = $_POST['neo201'];
							$neo202 = $_POST['neo202'];
							$neo203 = $_POST['neo203'];
							$neo204 = $_POST['neo204'];
							$neo205 = $_POST['neo205'];
							$neo206 = $_POST['neo206'];
							$neo207 = $_POST['neo207'];
							$neo208 = $_POST['neo208'];
							$neo209 = $_POST['neo209'];
							$neo210 = $_POST['neo210'];
							$neo211 = $_POST['neo211'];
							$neo212 = $_POST['neo212'];
							$neo213 = $_POST['neo213'];
							$neo214 = $_POST['neo214'];
							$neo215 = $_POST['neo215'];
							$neo216 = $_POST['neo216'];
							$neo217 = $_POST['neo217'];
							$neo218 = $_POST['neo218'];
							$neo219 = $_POST['neo219'];
							$neo220 = $_POST['neo220'];
							$neo221 = $_POST['neo221'];
							$neo222 = $_POST['neo222'];
							$neo223 = $_POST['neo223'];
							$neo224 = $_POST['neo224'];
							$neo225 = $_POST['neo225'];
							$neo226 = $_POST['neo226'];
							$neo227 = $_POST['neo227'];
							$neo228 = $_POST['neo228'];
							$neo229 = $_POST['neo229'];
							$neo230 = $_POST['neo230'];
							$neo231 = $_POST['neo231'];
							$neo232 = $_POST['neo232'];
							$neo233 = $_POST['neo233'];
							$neo234 = $_POST['neo234'];
							$neo235 = $_POST['neo235'];
							$neo236 = $_POST['neo236'];
							$neo237 = $_POST['neo237'];
							$neo238 = $_POST['neo238'];
							$neo239 = $_POST['neo239'];
							$neo240 = $_POST['neo240'];
							$neo241 = $_POST['neo241'];
							$neo242 = $_POST['neo242'];
							$neo243 = $_POST['neo243'];
							$neo244 = $_POST['neo244'];
							$neo245 = $_POST['neo245'];
							$neo246 = $_POST['neo246'];
							$neo247 = $_POST['neo247'];
							$neo248 = $_POST['neo248'];
							$neo249 = $_POST['neo249'];
							$neo250 = $_POST['neo250'];
							$neo251 = $_POST['neo251'];
							$neo252 = $_POST['neo252'];
							$neo253 = $_POST['neo253'];
							$neo254 = $_POST['neo254'];
							$neo255 = $_POST['neo255'];
							$neo256 = $_POST['neo256'];
							$neo257 = $_POST['neo257'];
							$neo258 = $_POST['neo258'];
							$neo259 = $_POST['neo259'];
							$neo260 = $_POST['neo260'];
							$neo261 = $_POST['neo261'];
							$neo262 = $_POST['neo262'];
							$neo263 = $_POST['neo263'];
							$neo264 = $_POST['neo264'];
							$neo265 = $_POST['neo265'];
							$neo266 = $_POST['neo266'];
							$neo267 = $_POST['neo267'];
							$neo268 = $_POST['neo268'];
							$neo269 = $_POST['neo269'];
							$neo270 = $_POST['neo270'];
							$neo271 = $_POST['neo271'];
							$neo272 = $_POST['neo272'];
							$neo273 = $_POST['neo273'];
							$neo274 = $_POST['neo274'];
							$neo275 = $_POST['neo275'];
							$neo276 = $_POST['neo276'];
							$neo277 = $_POST['neo277'];
							$neo278 = $_POST['neo278'];
							$neo279 = $_POST['neo279'];
							$neo280 = $_POST['neo280'];
							$neo281 = $_POST['neo281'];
							$neo282 = $_POST['neo282'];
							$neo283 = $_POST['neo283'];
							$neo284 = $_POST['neo284'];
							$neo285 = $_POST['neo285'];
							$neo286 = $_POST['neo286'];
							$neo287 = $_POST['neo287'];
							$neo288 = $_POST['neo288'];
							$neo289 = $_POST['neo289'];
							$neo290 = $_POST['neo290'];
							$neo291 = $_POST['neo291'];
							$neo292 = $_POST['neo292'];
							$neo293 = $_POST['neo293'];
							$neo294 = $_POST['neo294'];
							$neo295 = $_POST['neo295'];
							$neo296 = $_POST['neo296'];
							$neo297 = $_POST['neo297'];
							$neo298 = $_POST['neo298'];
							$neo299 = $_POST['neo299'];
							$neo300 = $_POST['neo300'];
							$neo301 = $_POST['neo301'];
							$neo302 = $_POST['neo302'];
							$neo303 = $_POST['neo303'];
							$neo304 = $_POST['neo304'];
							$neo305 = $_POST['neo305'];
							$neo306 = $_POST['neo306'];
							$neo307 = $_POST['neo307'];
							$neo308 = $_POST['neo308'];
							$neo309 = $_POST['neo309'];
							$neo310 = $_POST['neo310'];
							$neo311 = $_POST['neo311'];
							$neo312 = $_POST['neo312'];
							$neo313 = $_POST['neo313'];
							$neo314 = $_POST['neo314'];
							$neo315 = $_POST['neo315'];
							$neo316 = $_POST['neo316'];
							$neo317 = $_POST['neo317'];
							$neo318 = $_POST['neo318'];
							$neo319 = $_POST['neo319'];
							$neo320 = $_POST['neo320'];


							$neo321 = $_POST['neo321'];
							$neo322 = $_POST['neo322'];
							$neo323 = $_POST['neo323'];
							$neo324 = $_POST['neo324'];
							$neo325 = $_POST['neo325'];
							$neo326 = $_POST['neo326'];
							$neo327 = $_POST['neo327'];
							$neo328 = $_POST['neo328'];
							$neo329 = $_POST['neo329'];
							$neo330 = $_POST['neo330'];
							$neo331 = $_POST['neo331'];
							$neo332 = $_POST['neo332'];
							$neo333 = $_POST['neo333'];
							$neo334 = $_POST['neo334'];
							$neo335 = $_POST['neo335'];
							$neo336 = $_POST['neo336'];
							$neo337 = $_POST['neo337'];
							$neo338 = $_POST['neo338'];
							$neo339 = $_POST['neo339'];
							$neo340 = $_POST['neo340'];
							$neo341 = $_POST['neo341'];
							$neo342 = $_POST['neo342'];
							$neo343 = $_POST['neo343'];
							$neo344 = $_POST['neo344'];

							$q  = "update ERM_RI_ASSESMEN_AWAL_NEONATUS_KEP set	
							tglrawat = '$tglrawat',	
							neo1 = '$neo1',
							neo2 = '$neo2',
							neo3 = '$neo3',
							neo4 = '$neo4',
							neo5 = '$neo5',
							neo6 = '$neo6',
							neo7 = '$neo7',
							neo8 = '$neo8',
							neo9 = '$neo9',
							neo10 = '$neo10',
							neo11 = '$neo11',
							neo12 = '$neo12',
							neo13 = '$neo13',
							neo14 = '$neo14',
							neo15 = '$neo15',
							neo16 = '$neo16',
							neo17 = '$neo17',
							neo18 = '$neo18',
							neo19 = '$neo19',
							neo20 = '$neo20',
							neo21 = '$neo21',
							neo22 = '$neo22',
							neo23 = '$neo23',
							neo24 = '$neo24',
							neo25 = '$neo25',
							neo26 = '$neo26',
							neo27 = '$neo27',
							neo28 = '$neo28',
							neo29 = '$neo29',
							neo30 = '$neo30',
							neo31 = '$neo31',
							neo32 = '$neo32',
							neo33 = '$neo33',
							neo34 = '$neo34',
							neo35 = '$neo35',
							neo36 = '$neo36',
							neo37 = '$neo37',
							neo38 = '$neo38',
							neo39 = '$neo39',
							neo40 = '$neo40',
							neo41 = '$neo41',
							neo42 = '$neo42',
							neo43 = '$neo43',
							neo44 = '$neo44',
							neo45 = '$neo45',
							neo46 = '$neo46',
							neo47 = '$neo47',
							neo48 = '$neo48',
							neo49 = '$neo49',
							neo50 = '$neo50',
							neo51 = '$neo51',
							neo52 = '$neo52',
							neo53 = '$neo53',
							neo54 = '$neo54',
							neo55 = '$neo55',
							neo56 = '$neo56',
							neo57 = '$neo57',
							neo58 = '$neo58',
							neo59 = '$neo59',
							neo60 = '$neo60',
							neo61 = '$neo61',
							neo62 = '$neo62',
							neo63 = '$neo63',
							neo64 = '$neo64',
							neo65 = '$neo65',
							neo66 = '$neo66',
							neo67 = '$neo67',
							neo68 = '$neo68',
							neo69 = '$neo69',
							neo70 = '$neo70',
							neo71 = '$neo71',
							neo72 = '$neo72',
							neo73 = '$neo73',
							neo74 = '$neo74',
							neo75 = '$neo75',
							neo76 = '$neo76',
							neo77 = '$neo77',
							neo78 = '$neo78',
							neo79 = '$neo79',
							neo80 = '$neo80',
							neo81 = '$neo81',
							neo82 = '$neo82',
							neo83 = '$neo83',
							neo84 = '$neo84',
							neo85 = '$neo85',
							neo86 = '$neo86',
							neo87 = '$neo87',

							neo88 = '$neo88',
							neo89 = '$neo89',
							neo90 = '$neo90',
							neo91 = '$neo91',
							neo92 = '$neo92',
							neo93 = '$neo93',
							neo94 = '$neo94',
							neo95 = '$neo95',
							neo96 = '$neo96',
							neo97 = '$neo97',
							neo98 = '$neo98',
							neo99 = '$neo99',
							neo100 = '$neo100',
							neo101 = '$neo101',
							neo102 = '$neo102',
							neo103 = '$neo103',
							neo104 = '$neo104',
							neo105 = '$neo105',
							neo106 = '$neo106',
							neo107 = '$neo107',
							neo108 = '$neo108',
							neo109 = '$neo109',
							neo110 = '$neo110',
							neo111 = '$neo111',
							neo112 = '$neo112',
							neo113 = '$neo113',
							neo114 = '$neo114',
							neo115 = '$neo115',
							neo116 = '$neo116',
							neo117 = '$neo117',
							neo118 = '$neo118',
							neo119 = '$neo119',
							neo120 = '$neo120',
							neo121 = '$neo121',
							neo122 = '$neo122',
							neo123 = '$neo123',
							neo124 = '$neo124',
							neo125 = '$neo125',
							neo126 = '$neo126',
							neo127 = '$neo127',
							neo128 = '$neo128',
							neo129 = '$neo129',
							neo130 = '$neo130',
							neo131 = '$neo131',
							neo132 = '$neo132',
							neo133 = '$neo133',
							neo134 = '$neo134',
							neo135 = '$neo135',
							neo136 = '$neo136',
							neo137 = '$neo137',
							neo138 = '$neo138',
							neo139 = '$neo139',
							neo140 = '$neo140',
							neo141 = '$neo141',
							neo142 = '$neo142',
							neo143 = '$neo143',
							neo144 = '$neo144',
							neo145 = '$neo145',
							neo146 = '$neo146',
							neo147 = '$neo147',
							neo148 = '$neo148',
							neo149 = '$neo149',
							neo150 = '$neo150',
							neo151 = '$neo151',
							neo152 = '$neo152',
							neo153 = '$neo153',
							neo154 = '$neo154',
							neo155 = '$neo155',
							neo156 = '$neo156',
							neo157 = '$neo157',
							neo158 = '$neo158',
							neo159 = '$neo159',
							neo160 = '$neo160',
							neo161 = '$neo161',
							neo162 = '$neo162',
							neo163 = '$neo163',
							neo164 = '$neo164',
							neo165 = '$neo165',
							neo166 = '$neo166',
							neo167 = '$neo167',
							neo168 = '$neo168',
							neo169 = '$neo169',
							neo170 = '$neo170',
							neo171 = '$neo171',
							neo172 = '$neo172',
							neo173 = '$neo173',
							neo174 = '$neo174',
							neo175 = '$neo175',
							neo176 = '$neo176',
							neo177 = '$neo177',
							neo178 = '$neo178',
							neo179 = '$neo179',
							neo180 = '$neo180',
							neo181 = '$neo181',
							neo182 = '$neo182',
							neo183 = '$neo183',
							neo184 = '$neo184',
							neo185 = '$neo185',
							neo186 = '$neo186',
							neo187 = '$neo187',
							neo188 = '$neo188',
							neo189 = '$neo189',
							neo190 = '$neo190',
							neo191 = '$neo191',
							neo192 = '$neo192',
							neo193 = '$neo193',
							neo194 = '$neo194',
							neo195 = '$neo195',
							neo196 = '$neo196',
							neo197 = '$neo197',
							neo198 = '$neo198',
							neo199 = '$neo199',
							neo200 = '$neo200',
							neo201 = '$neo201',
							neo202 = '$neo202',
							neo203 = '$neo203',
							neo204 = '$neo204',
							neo205 = '$neo205',
							neo206 = '$neo206',
							neo207 = '$neo207',
							neo208 = '$neo208',
							neo209 = '$neo209',
							neo210 = '$neo210',
							neo211 = '$neo211',
							neo212 = '$neo212',
							neo213 = '$neo213',
							neo214 = '$neo214',
							neo215 = '$neo215',
							neo216 = '$neo216',
							neo217 = '$neo217',
							neo218 = '$neo218',
							neo219 = '$neo219',
							neo220 = '$neo220',
							neo221 = '$neo221',
							neo222 = '$neo222',
							neo223 = '$neo223',
							neo224 = '$neo224',
							neo225 = '$neo225',
							neo226 = '$neo226',
							neo227 = '$neo227',
							neo228 = '$neo228',
							neo229 = '$neo229',
							neo230 = '$neo230',
							neo231 = '$neo231',
							neo232 = '$neo232',
							neo233 = '$neo233',
							neo234 = '$neo234',
							neo235 = '$neo235',
							neo236 = '$neo236',
							neo237 = '$neo237',
							neo238 = '$neo238',
							neo239 = '$neo239',
							neo240 = '$neo240',
							neo241 = '$neo241',
							neo242 = '$neo242',
							neo243 = '$neo243',
							neo244 = '$neo244',
							neo245 = '$neo245',
							neo246 = '$neo246',
							neo247 = '$neo247',
							neo248 = '$neo248',
							neo249 = '$neo249',
							neo250 = '$neo250',
							neo251 = '$neo251',
							neo252 = '$neo252',
							neo253 = '$neo253',
							neo254 = '$neo254',
							neo255 = '$neo255',
							neo256 = '$neo256',
							neo257 = '$neo257',
							neo258 = '$neo258',
							neo259 = '$neo259',
							neo260 = '$neo260',
							neo261 = '$neo261',
							neo262 = '$neo262',
							neo263 = '$neo263',
							neo264 = '$neo264',
							neo265 = '$neo265',
							neo266 = '$neo266',
							neo267 = '$neo267',
							neo268 = '$neo268',
							neo269 = '$neo269',
							neo270 = '$neo270',
							neo271 = '$neo271',
							neo272 = '$neo272',
							neo273 = '$neo273',
							neo274 = '$neo274',
							neo275 = '$neo275',
							neo276 = '$neo276',
							neo277 = '$neo277',
							neo278 = '$neo278',
							neo279 = '$neo279',
							neo280 = '$neo280',
							neo281 = '$neo281',
							neo282 = '$neo282',
							neo283 = '$neo283',
							neo284 = '$neo284',
							neo285 = '$neo285',
							neo286 = '$neo286',
							neo287 = '$neo287',
							neo288 = '$neo288',
							neo289 = '$neo289',
							neo290 = '$neo290',
							neo291 = '$neo291',
							neo292 = '$neo292',
							neo293 = '$neo293',
							neo294 = '$neo294',
							neo295 = '$neo295',
							neo296 = '$neo296',
							neo297 = '$neo297',
							neo298 = '$neo298',
							neo299 = '$neo299',
							neo300 = '$neo300',
							neo301 = '$neo301',
							neo302 = '$neo302',
							neo303 = '$neo303',
							neo304 = '$neo304',
							neo305 = '$neo305',
							neo306 = '$neo306',
							neo307 = '$neo307',
							neo308 = '$neo308',
							neo309 = '$neo309',
							neo310 = '$neo310',
							neo311 = '$neo311',
							neo312 = '$neo312',
							neo313 = '$neo313',
							neo314 = '$neo314',
							neo315 = '$neo315',
							neo316 = '$neo316',
							neo317 = '$neo317',
							neo318 = '$neo318',
							neo319 = '$neo319',
							neo320 = '$neo320',
							neo321 = '$neo321',
							neo322 = '$neo322',
							neo323 = '$neo323',
							neo324 = '$neo324',
							neo325 = '$neo325',
							neo326 = '$neo326',
							neo327 = '$neo327',
							neo328 = '$neo328',
							neo329 = '$neo329',
							neo330 = '$neo330',
							neo331 = '$neo331',
							neo332 = '$neo332',
							neo333 = '$neo333',
							neo334 = '$neo334',
							neo335 = '$neo335',
							neo336 = '$neo336',
							neo337 = '$neo337',
							neo338 = '$neo338',
							neo339 = '$neo339',
							neo340 = '$neo340',
							neo341 = '$neo341',
							neo342 = '$neo342',
							neo343 = '$neo343',
							neo344 = '$neo344',
							dpjp='$dpjp',userid='$user'

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
							alert('".$eror."');
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

