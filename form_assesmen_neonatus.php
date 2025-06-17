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

$qu="
SELECT        TOP (200) NORM,  ALERGI
FROM            Y_ALERGI 
where norm='$norm'

union 
SELECT        ARM_REGISTER.NORM, V_ERM_RI_KEADAAN_UMUM.alergi as ALERGI
FROM            V_ERM_RI_KEADAAN_UMUM INNER JOIN
ARM_REGISTER ON V_ERM_RI_KEADAAN_UMUM.noreg = ARM_REGISTER.NOREG
where ARM_REGISTER.NORM='$norm'
";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$alergi = $d1u['ALERGI'];

$qu2="SELECT ket as notif  FROM ERM_NOTIF where noreg='$noreg'";
$h1u2  = sqlsrv_query($conn, $qu2);        
$d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
$notif = $d1u2['notif'];

$qu3="SELECT diet as diet  FROM ERM_DIET where noreg='$noreg'";
$h1u3  = sqlsrv_query($conn, $qu3);        
$d1u3  = sqlsrv_fetch_array($h1u3, SQLSRV_FETCH_ASSOC); 
$diet = $d1u3['diet'];

$qi="SELECT noreg FROM ERM_RI_ASSESMEN_AWAL_NEONATUS where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_ASSESMEN_AWAL_NEONATUS(noreg,userid,tglentry) values ('$noreg','$user','$tglentry')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tglrawat, 23) as tglrawat,CONVERT(VARCHAR, tglrawat, 103) as tglrawat2,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment,
	CONVERT(VARCHAR, tglverif, 103) as tgl_verif,
	CONVERT(VARCHAR, tglverif, 8) as jam_verif

	FROM ERM_RI_ASSESMEN_AWAL_NEONATUS
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$userid = $de['userid'];

	$tglrawat = $de['tglrawat'];
	$tglrawat2 = $de['tglrawat2'];
	$jamrawat = $de['jamrawat'];
	
	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment'];

	$tgl_verif = $de['tgl_verif'];
	$jam_verif = $de['jam_verif'];

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
				<a href='form_assesmen_neonatus_print.php?id=<?php echo $id."|".$user;?>' target='_blank' class='btn btn-info btn-sm'>
					<i class="bi bi-printer-fill"></i> Cetak
				</a>
			</div>


			<!-- Title -->
			<div class="text-center mb-4">
				<h5><i class="bi bi-window-plus"></i> ASESMEN AWAL KEBIDANAN NEONATUS</h5>
			</div>

			<!-- Input DPJP -->
			<div class="card shadow-sm mb-4">
				<div class="card-body">
					<h6 class="card-title text-primary"><i class="bi bi-person-lines-fill"></i>DIISI OLEH BIDAN / PERAWAT</h6>
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

			<!-- Informasi Pasien -->
			<div class="row">
				<div class="col-md-6 mb-3">
					<div class="card shadow-sm">
						<div class="card-header bg-primary text-white">
							<i class="bi bi-person-vcard"></i> Data Ibu
						</div>
						<div class="card-body">
							<p><i class="bi bi-calendar-event"></i> MRS Tanggal: <strong><?php echo $tglrawat2;?></strong></p>
							<p><i class="bi bi-person"></i> Nama Ibu: <strong><?php echo $neo1;?></strong></p>
							<p><i class="bi bi-person-badge"></i> Umur Ibu: <strong><?php echo $neo2;?></strong></p>
							<p><i class="bi bi-telephone"></i> No. Telp Ibu: <strong><?php echo $neo3;?></strong></p>
							<p><i class="bi bi-geo-alt"></i> Alamat: <strong><?php echo $neo4;?></strong></p>
							<p><i class="bi bi-heart"></i> Riwayat Pernikahan: <strong><?php echo $neo5;?></strong></p>
						</div>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<div class="card shadow-sm">
						<div class="card-header bg-secondary text-white">
							<i class="bi bi-person-hearts"></i> Data Ayah
						</div>
						<div class="card-body">
							<p><i class="bi bi-person"></i> Nama Ayah: <strong><?php echo $neo6;?></strong></p>
							<p><i class="bi bi-person-badge"></i> Umur Ayah: <strong><?php echo $neo7;?></strong></p>
							<p><i class="bi bi-telephone"></i> No. Telp Ayah: <strong><?php echo $neo8;?></strong></p>
						</div>
					</div>
				</div>
			</div>

			<div class="card shadow-sm border-0" style="max-width: 200px;">
				<div class="card-body text-center">
					<a href="f_neo1.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-2">
						<i class="bi bi-pencil-square"></i> Edit Data
					</a>
				</div>
			</div>

			<hr>

			<div class="card shadow-sm mb-4">
				<div class="card-header bg-primary text-white">
					<i class="bi bi-journal-medical me-2"></i> A. Anamnesa Kehamilan Dahulu
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table table-bordered table-sm mb-0 text-center align-middle">
							<thead class="table-light">
								<tr>
									<th><i class="bi bi-patch-question"></i> Hamil ke</th>
									<th><i class="bi bi-exclamation-triangle"></i> Penyulit</th>
									<th><i class="bi bi-gender-ambiguous"></i> Jenis Persalinan</th>
									<th><i class="bi bi-gender-trans"></i> L/P</th>
									<th><i class="bi bi-heart-pulse"></i> Hidup/Mati</th>
									<th><i class="bi bi-clock-history"></i> Umur Sekarang / Waktu Meninggal</th>
									<th><i class="bi bi-emoji-frown"></i> Sebab Kematian</th>
									<th><i class="bi bi-cup-straw"></i> ASI</th>
									<th><i class="bi bi-calendar-heart"></i> KB (lama)</th>
								</tr>
							</thead>
							<tbody>
								<?php if($neo9){ ?>
									<tr>
										<td>1</td>
										<td><?php echo $neo9;?></td>
										<td><?php echo $neo10;?></td>
										<td><?php echo $neo11;?></td>
										<td><?php echo $neo12;?></td>
										<td><?php echo $neo13;?></td>
										<td><?php echo $neo14;?></td>
										<td><?php echo $neo15;?></td>
										<td><?php echo $neo16;?></td>
										<td><?php echo $neo16;?></td>
									</tr>
									<tr>
										<td>2</td>
										<td><?php echo $neo17;?></td>
										<td><?php echo $neo18;?></td>
										<td><?php echo $neo19;?></td>
										<td><?php echo $neo20;?></td>
										<td><?php echo $neo21;?></td>
										<td><?php echo $neo22;?></td>
										<td><?php echo $neo23;?></td>
										<td><?php echo $neo24;?></td>
										<td><?php echo $neo24;?></td>
									</tr>
									<tr>
										<td>3</td>
										<td><?php echo $neo25;?></td>
										<td><?php echo $neo26;?></td>
										<td><?php echo $neo27;?></td>
										<td><?php echo $neo28;?></td>
										<td><?php echo $neo29;?></td>
										<td><?php echo $neo30;?></td>
										<td><?php echo $neo31;?></td>
										<td><?php echo $neo32;?></td>
										<td><?php echo $neo32;?></td>
									</tr>
									<tr>
										<td>4</td>
										<td><?php echo $neo33;?></td>
										<td><?php echo $neo34;?></td>
										<td><?php echo $neo35;?></td>
										<td><?php echo $neo36;?></td>
										<td><?php echo $neo37;?></td>
										<td><?php echo $neo38;?></td>
										<td><?php echo $neo39;?></td>
										<td><?php echo $neo40;?></td>
										<td><?php echo $neo40;?></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="card shadow-sm border-0" style="max-width: 200px;">
				<div class="card-body text-center">
					<a href="f_neo2.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-2">
						<i class="bi bi-pencil-square"></i> Edit Data
					</a>
				</div>
			</div>

			<hr>

			<div class="card shadow-sm mb-4">
				<div class="card-header bg-success text-white">
					<i class="bi bi-heart-pulse me-2"></i> B. Riwayat Penyakit Ibu dan Pengobatannya
				</div>
				<div class="card-body">
					<?php if($neo45){ ?>
						<p>
							<i class="bi bi-activity text-danger me-1"></i>
							<strong>Sebelum Hamil ini:</strong> <?php echo $neo41;?> <br>
							<i class="bi bi-info-circle me-1"></i><strong>Keterangan:</strong> <?php echo $neo42;?>
						</p>
						<p>
							<i class="bi bi-activity text-warning me-1"></i>
							<strong>Selama Hamil ini:</strong> <?php echo $neo43;?> <br>
							<i class="bi bi-info-circle me-1"></i><strong>Keterangan:</strong> <?php echo $neo44;?>
						</p>

						<div class="table-responsive">
							<table class="table table-bordered text-center align-middle table-sm">
								<thead class="table-light">
									<tr>
										<th><i class="bi bi-hospital"></i> Pemeriksaan</th>
										<th>Trimester 1</th>
										<th>Trimester 2</th>
										<th>Trimester 3</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><i class="bi bi-clipboard-check text-primary"></i> Jumlah ANC</td>
										<td><?php echo $neo45;?></td>
										<td><?php echo $neo46;?></td>
										<td><?php echo $neo47;?></td>
									</tr>
									<tr>
										<td><i class="bi bi-capsule text-info"></i> Obat yang dikonsumsi</td>
										<td><?php echo $neo48;?></td>
										<td><?php echo $neo49;?></td>
										<td><?php echo $neo50;?></td>
									</tr>
									<tr>
										<td><i class="bi bi-shield-check text-success"></i> Vaksin TT</td>
										<td><?php echo $neo51;?></td>
										<td><?php echo $neo52;?></td>
										<td><?php echo $neo53;?></td>
									</tr>
								</tbody>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>

			<div class="card shadow-sm border-0" style="max-width: 200px;">
				<div class="card-body text-center">
					<a href="f_neo3.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-2">
						<i class="bi bi-pencil-square"></i> Edit Data
					</a>
				</div>
			</div>

			<hr>

			<div class="card shadow-sm mb-4">
				<div class="card-header bg-warning text-dark">
					<i class="bi bi-people-fill me-2"></i> C. Riwayat Keluarga
				</div>
				<div class="card-body">
					<ul class="list-group list-group-flush">
						<?php if($neo54){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo54; ?></strong> — <?php echo $neo55; ?>
							</li>
						<?php } ?>
						<?php if($neo56){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo56; ?></strong> — <?php echo $neo57; ?>
							</li>
						<?php } ?>
						<?php if($neo58){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo58; ?></strong> — <?php echo $neo59; ?>
							</li>
						<?php } ?>
						<?php if($neo60){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo60; ?></strong> — <?php echo $neo61; ?>
							</li>
						<?php } ?>
						<?php if($neo62){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo62; ?></strong> — <?php echo $neo63; ?>
							</li>
						<?php } ?>
						<?php if($neo64){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo64; ?></strong> — <?php echo $neo65; ?>
							</li>
						<?php } ?>
						<?php if($neo66){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo66; ?></strong> — <?php echo $neo67; ?>
							</li>
						<?php } ?>
						<?php if($neo68){ ?>
							<li class="list-group-item">
								<i class="bi bi-check-circle-fill text-success me-2"></i>
								<strong><?php echo $neo68; ?></strong> — <?php echo $neo69; ?>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>

			<div class="card shadow-sm border-0" style="max-width: 200px;">
				<div class="card-body text-center">
					<a href="f_neo4.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-2">
						<i class="bi bi-pencil-square"></i> Edit Data
					</a>
				</div>
			</div>

			<hr>

			<div class="card shadow-sm mb-4">
				<div class="card-header bg-danger text-white">
					<i class="bi bi-person-bounding-box me-2"></i> D. Riwayat Persalinan
				</div>
				<div class="card-body">
					<?php if($neo71){ ?>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">
								<i class="bi bi-calendar-week me-2 text-primary"></i>
								<strong>Umur Kehamilan:</strong> <?php echo $neo71; ?> minggu
							</li>
							<li class="list-group-item">
								<i class="bi bi-people me-2 text-success"></i>
								<strong>Kehamilan:</strong> <?php echo $neo72; ?>
							</li>
							<li class="list-group-item">
								<i class="bi bi-hourglass-split me-2 text-warning"></i>
								<strong>Lama Persalinan:</strong><br>
								- Kala I: <?php echo $neo73; ?> jam <?php echo $neo74; ?> menit<br>
								- Kala II: <?php echo $neo75; ?> jam <?php echo $neo76; ?> menit
							</li>
							<li class="list-group-item">
								<i class="bi bi-droplet-half me-2 text-info"></i>
								<strong>Ketuban pecah sebelum lahir:</strong> <?php echo $neo77; ?> jam <?php echo $neo78; ?> menit
							</li>
							<li class="list-group-item">
								<i class="bi bi-water me-2 text-primary"></i>
								<strong>Air Ketuban:</strong> Jumlah <?php echo $neo79; ?> cc, Warna/Bau: <?php echo $neo80; ?>
							</li>
							<li class="list-group-item">
								<i class="bi bi-person-standing me-2 text-dark"></i>
								<strong>Letak Bayi:</strong> <?php echo $neo81; ?>
							</li>
							<li class="list-group-item">
								<i class="bi bi-hospital me-2 text-secondary"></i>
								<strong>Indikasi Persalinan Operatif:</strong> <?php echo $neo82; ?>
							</li>
							<li class="list-group-item">
								<i class="bi bi-capsule-pill me-2 text-danger"></i>
								<strong>Obat Selama Persalinan:</strong> <?php echo $neo83; ?>
							</li>
							<li class="list-group-item">
								<i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>
								<strong>Tanda Gawat Janin:</strong> <?php echo $neo84; ?>
							</li>
							<li class="list-group-item">
								<i class="bi bi-box2-heart me-2 text-success"></i>
								<strong>Plasenta:</strong><br>
								- Berat: <?php echo $neo85; ?> gr<br>
								- Ukuran: <?php echo $neo86; ?><br>
								- Kelainan: <?php echo $neo87; ?>
							</li>
						</ul>
					<?php } ?>
				</div>
			</div>

			<div class="card shadow-sm border-0" style="max-width: 200px;">
				<div class="card-body text-center">
					<a href="f_neo5.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center gap-2">
						<i class="bi bi-pencil-square"></i> Edit Data
					</a>
				</div>
			</div>

			<hr>

			<div class="card shadow-sm mb-4">
				<div class="card-header bg-info text-white">
					<i class="bi bi-baby me-2"></i> E. Keadaan Bayi
				</div>
				<div class="card-body">
					<div class="row g-3">
						<!-- Kolom 1: Ukuran Fisik -->
						<div class="col-md-4">
							<h6><i class="bi bi-rulers me-2 text-primary"></i>Ukuran Fisik</h6>
							<div class="mb-2"><strong>Berat Badan:</strong> <input type="text" class="form-control form-control-sm" name="neo109" value="<?php echo $neo109; ?>"> gram</div>
							<div class="mb-2"><strong>Panjang Badan:</strong> <input type="text" class="form-control form-control-sm" name="neo110" value="<?php echo $neo110; ?>"> cm</div>
							<div class="mb-2"><strong>Lingkar Kepala:</strong> <input type="text" class="form-control form-control-sm" name="neo111" value="<?php echo $neo111; ?>"> cm</div>
							<div class="mb-2"><strong>Lingkar Dada:</strong> <input type="text" class="form-control form-control-sm" name="neo112" value="<?php echo $neo112; ?>"> cm</div>
							<div class="mb-2"><strong>Lingkar Lengan:</strong> <input type="text" class="form-control form-control-sm" name="neo113" value="<?php echo $neo113; ?>"> cm</div>
						</div>

						<!-- Kolom 2: Refleks -->
						<div class="col-md-4">
							<h6><i class="bi bi-hand-index-thumb me-2 text-success"></i>Refleks Bayi</h6>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="neo114" value="YA" <?php if ($neo114=="YA"){echo "checked";} ?>>
								<label class="form-check-label">Moro (Terkejut)</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="neo115" value="YA" <?php if ($neo115=="YA"){echo "checked";} ?>>
								<label class="form-check-label">Rootting (Mencari)</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="neo116" value="YA" <?php if ($neo116=="YA"){echo "checked";} ?>>
								<label class="form-check-label">Sucking (Menghisap)</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="neo117" value="YA" <?php if ($neo117=="YA"){echo "checked";} ?>>
								<label class="form-check-label">Grapping (Menggenggam)</label>
							</div>
						</div>

						<!-- Kolom 3: Perawatan Bayi -->
						<div class="col-md-4">
							<h6><i class="bi bi-clipboard-pulse me-2 text-danger"></i>Perawatan & Pemeriksaan</h6>
							<div class="mb-2"><strong>ASI Eksklusif:</strong><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="neo118" value="YA" <?php if ($neo118=="YA"){echo "checked";} ?>>
									<label class="form-check-label">Ya</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="neo119" value="YA" <?php if ($neo119=="YA"){echo "checked";} ?>>
									<label class="form-check-label">Tidak</label>
								</div>
								<input type="text" class="form-control form-control-sm mt-1" name="neo98" value="<?php echo $neo98; ?>" placeholder="Alasan">
							</div>

							<div class="mb-2"><strong>IMD:</strong><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="neo120" value="YA" <?php if ($neo120=="YA"){echo "checked";} ?>>
									<label class="form-check-label">Ya</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="neo121" value="YA" <?php if ($neo121=="YA"){echo "checked";} ?>>
									<label class="form-check-label">Tidak</label>
								</div>
							</div>

							<div class="row">
								<div class="col">
									<label>Jam Pertama:</label>
									<input type="text" class="form-control form-control-sm" name="neo122" value="<?php echo $neo122; ?>">
								</div>
								<div class="col">
									<label>Jam Kedua:</label>
									<input type="text" class="form-control form-control-sm" name="neo123" value="<?php echo $neo123; ?>">
								</div>
							</div>

							<div class="mt-2"><strong>Rawat Gabung:</strong><br>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="neo124" value="YA" <?php if ($neo124=="YA"){echo "checked";} ?>>
									<label class="form-check-label">Ya</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="checkbox" name="neo125" value="YA" <?php if ($neo125=="YA"){echo "checked";} ?>>
									<label class="form-check-label">Tidak</label>
								</div>
							</div>

							<div class="row mt-2">
								<div class="col">
									<label>HR:</label>
									<input type="text" class="form-control form-control-sm" name="neo126" value="<?php echo $neo126; ?>">
								</div>
								<div class="col">
									<label>RR:</label>
									<input type="text" class="form-control form-control-sm" name="neo127" value="<?php echo $neo127; ?>">
								</div>
							</div>

							<div class="mt-2">
								<label>Suhu:</label>
								<input type="text" class="form-control form-control-sm" name="neo128" value="<?php echo $neo128; ?>"> °C
							</div>

							<div class="mt-2">
								<label>Resusitasi:</label>
								<input type="text" class="form-control form-control-sm" name="neo129" value="<?php echo $neo129; ?>">
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-12">			
				
				<div class="container my-5">
					<div class="card shadow-sm">
						<div class="card-header bg-success text-white">
							<h4><i class="fas fa-heartbeat"></i> <b>APGAR SCORE</b> (Menit 1)</h4>
						</div>
						<div class="card-body">
							<table class="table table-bordered">
								<!-- A (Activity) -->
								<tr>
									<td><b>A (Activity)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo88" value="Tidak ada respon" <?php if ($neo88=="Tidak ada respon"){echo "checked";}?>>
											<label class="form-check-label">Tidak ada respon</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo88" value="Tangan dan kaki fleksi" <?php if ($neo88=="Tangan dan kaki fleksi"){echo "checked";}?>>
											<label class="form-check-label">Tangan dan kaki fleksi</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo88" value="aktif" <?php if ($neo88=="aktif"){echo "checked";}?>>
											<label class="form-check-label">Aktif</label>
										</div>
									</td>
								</tr>
								<!-- P (Pulse/Heart Rate) -->
								<tr>
									<td><b>P (Pulse/HR)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo89" value="k60" <?php if ($neo89=="k60"){echo "checked";}?>>
											<label class="form-check-label"> &lt; 60</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo89" value="l60" <?php if ($neo89=="l60"){echo "checked";}?>>
											<label class="form-check-label">≥60 - &lt; 100</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo89" value="l100" <?php if ($neo89=="l100"){echo "checked";}?>>
											<label class="form-check-label">≥100</label>
										</div>
									</td>
								</tr>
								<!-- G (Grimace/Reflex) -->
								<tr>
									<td><b>G (Grimace/Reflex)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo90" value="Tidak ada respon" <?php if ($neo90=="Tidak ada respon"){echo "checked";}?>>
											<label class="form-check-label">Tidak ada respon</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo90" value="Sedikit respon bila" <?php if ($neo90=="Sedikit respon bila"){echo "checked";}?>>
											<label class="form-check-label">Sedikit respon bila dirangsang</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo90" value="Batuk/bersin/menangis" <?php if ($neo90=="Batuk/bersin/menangis"){echo "checked";}?>>
											<label class="form-check-label">Batuk/Bersin/Menangis bila dirangsang</label>
										</div>
									</td>
								</tr>
								<!-- A (Appearance or Skin Color) -->
								<tr>
									<td><b>A (Appearance or Skin Color)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo91" value="Badan dan ekstremitas" <?php if ($neo91=="Badan dan ekstremitas"){echo "checked";}?>>
											<label class="form-check-label">Badan dan ekstremitas biru</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo91" value="Ekstremitas biru, badan merah" <?php if ($neo91=="Ekstremitas biru, badan merah"){echo "checked";}?>>
											<label class="form-check-label">Ekstremitas biru, badan merah</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo91" value="merah" <?php if ($neo91=="merah"){echo "checked";}?>>
											<label class="form-check-label">Merah</label>
										</div>
									</td>
								</tr>
								<!-- R (Respiratory Rate) -->
								<tr>
									<td><b>R (Respiratory Rate)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo92" value="Tidak ada respon" <?php if ($neo92=="Tidak ada respon"){echo "checked";}?>>
											<label class="form-check-label">Tidak ada respon</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo92" value="Nafas pelan dan tidak teratur" <?php if ($neo92=="Nafas pelan dan tidak teratur"){echo "checked";}?>>
											<label class="form-check-label">Nafas pelan dan tidak teratur</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo92" value="Tangis kuat" <?php if ($neo92=="Tangis kuat"){echo "checked";}?>>
											<label class="form-check-label">Tangis kuat</label>
										</div>
									</td>
								</tr>
							</table>
							<div class="text-center">
								<button type="submit" name="simpan" class="btn btn-lg btn-success"><i class="fas fa-save"></i> Simpan</button>
							</div>

							<?php 
                    // APGAR score calculations
							if($neo88=='Tidak ada respon'){$ap_score1=0;}
							if($neo88=='Tangan dan kaki fleksi'){$ap_score1=1;}
							if($neo88=='aktif'){$ap_score1=2;}

							if($neo89=='k60'){$ap_score2=0;}
							if($neo89=='l60'){$ap_score2=1;}
							if($neo89=='l100'){$ap_score2=2;}

							if($neo90=='Tidak ada respon'){$ap_score3=0;}
							if($neo90=='Sedikit respon bila'){$ap_score3=1;}
							if($neo90=='Batuk/bersin/menangis'){$ap_score3=2;}

							if($neo91=='Badan dan ekstremitas'){$ap_score4=0;}
							if($neo91=='Ekstremitas biru, badan merah'){$ap_score4=1;}
							if($neo91=='merah'){$ap_score4=2;}

							if($neo92=='Tidak ada respon'){$ap_score5=0;}
							if($neo92=='Nafas pelan dan tidak teratur'){$ap_score5=1;}
							if($neo92=='Tangis kuat'){$ap_score5=2;}

							echo "<div class='alert alert-info mt-3 text-center' style='font-size: 1.5em;'>Total APGAR Score: <b>" . $total_apgar_score = $ap_score1+$ap_score2+$ap_score3+$ap_score4+$ap_score5 . "</b></div>";
							?>
						</div>
					</div>
				</div>

				<div class="container my-5">
					<div class="card shadow-sm">
						<div class="card-header bg-warning text-white">
							<h4><i class="fas fa-heartbeat"></i> <b>APGAR SCORE Menit 5</b></h4>
						</div>
						<div class="card-body">
							<table class="table table-bordered">
								<!-- A (Activity) -->
								<tr>
									<td><b>A (Activity)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo93" value="Tidak ada respon" <?php if ($neo93=="Tidak ada respon"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-times-circle"></i> Tidak ada respon</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo93" value="Tangan dan kaki fleksi" <?php if ($neo93=="Tangan dan kaki fleksi"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-hand-paper"></i> Tangan dan kaki fleksi</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo93" value="aktif" <?php if ($neo93=="aktif"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-running"></i> Aktif</label>
										</div>
									</td>
								</tr>
								<!-- P (Pulse/Heart Rate) -->
								<tr>
									<td><b>P (Pulse/HR)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo94" value="k60" <?php if ($neo94=="k60"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-arrow-down"></i> &lt; 60</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo94" value="l60" <?php if ($neo94=="l60"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-arrow-right"></i> ≥60 - &lt; 100</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo94" value="l100" <?php if ($neo94=="l100"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-arrow-up"></i> ≥100</label>
										</div>
									</td>
								</tr>
								<!-- G (Grimace/Reflex) -->
								<tr>
									<td><b>G (Grimace/Reflex)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo95" value="Tidak ada respon" <?php if ($neo95=="Tidak ada respon"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-times-circle"></i> Tidak ada respon</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo95" value="Sedikit respon bila" <?php if ($neo95=="Sedikit respon bila"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-hand-pointer"></i> Sedikit respon bila dirangsang</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo95" value="Batuk/bersin/menangis" <?php if ($neo95=="Batuk/bersin/menangis"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-smile-beam"></i> Batuk/Bersin/Menangis bila dirangsang</label>
										</div>
									</td>
								</tr>
								<!-- A (Appearance or Skin Color) -->
								<tr>
									<td><b>A (Appearance or Skin Color)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo96" value="Badan dan ekstremitas" <?php if ($neo96=="Badan dan ekstremitas"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-tint"></i> Badan dan ekstremitas biru</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo96" value="Ekstremitas biru, badan merah" <?php if ($neo96=="Ekstremitas biru, badan merah"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-cloud-sun"></i> Ekstremitas biru, badan merah</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo96" value="merah" <?php if ($neo96=="merah"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-sun"></i> Merah</label>
										</div>
									</td>
								</tr>
								<!-- R (Respiratory Rate) -->
								<tr>
									<td><b>R (Respiratory Rate)</b></td>
									<td>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo97" value="Tidak ada respon" <?php if ($neo97=="Tidak ada respon"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-times-circle"></i> Tidak ada respon</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo97" value="Nafas pelan dan tidak teratur" <?php if ($neo97=="Nafas pelan dan tidak teratur"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-tachometer-alt"></i> Nafas pelan dan tidak teratur</label>
										</div>
										<div class="form-check">
											<input type="radio" class="form-check-input" name="neo97" value="Tangis kuat" <?php if ($neo97=="Tangis kuat"){echo "checked";}?>>
											<label class="form-check-label"><i class="fas fa-laugh-beam"></i> Tangis kuat</label>
										</div>
									</td>
								</tr>
							</table>
							<div class="text-center">
								<button type="submit" name="simpan" class="btn btn-lg btn-warning"><i class="fas fa-save"></i> Simpan</button>
							</div>

							<?php 
                    // APGAR score calculations for Menit 5
							if($neo93=='Tidak ada respon'){$ap_score1b=0;}
							if($neo93=='Tangan dan kaki fleksi'){$ap_score1b=1;}
							if($neo93=='aktif'){$ap_score1b=2;}

							if($neo94=='k60'){$ap_score2b=0;}
							if($neo94=='l60'){$ap_score2b=1;}
							if($neo94=='l100'){$ap_score2b=2;}

							if($neo95=='Tidak ada respon'){$ap_score3b=0;}
							if($neo95=='Sedikit respon bila'){$ap_score3b=1;}
							if($neo95=='Batuk/bersin/menangis'){$ap_score3b=2;}

							if($neo96=='Badan dan ekstremitas'){$ap_score4b=0;}
							if($neo96=='Ekstremitas biru, badan merah'){$ap_score4b=1;}
							if($neo96=='merah'){$ap_score4b=2;}

							if($neo97=='Tidak ada respon'){$ap_score5b=0;}
							if($neo97=='Nafas pelan dan tidak teratur'){$ap_score5b=1;}
							if($neo97=='Tangis kuat'){$ap_score5b=2;}

							echo "<div class='alert alert-info mt-3 text-center' style='font-size: 1.5em;'>Total APGAR Score: <b>" . $total_apgar_scoreb = $ap_score1b+$ap_score2b+$ap_score3b+$ap_score4b+$ap_score5b . "</b></div>";
							?>
						</div>
					</div>
				</div>

				<br><br>
				<table border='0'>
					<tr>
						<td>
							<h6>F. Pemeriksaan fisik</h6>
							<table>
								<tr>
									<td width="30%">Keadaan umum</td><td></td>
								</tr>
								<tr>
									<td>Kulit</td>
									<td>
										<input type='checkbox' name='neo130' value='YA' <?php if ($neo130=="YA"){echo "checked";}?> >Merah        
										<input type='checkbox' name='neo131' value='YA' <?php if ($neo131=="YA"){echo "checked";}?> >sianosis       
										<input type='checkbox' name='neo132' value='YA' <?php if ($neo132=="YA"){echo "checked";}?> >Pucat
									</td>
								</tr>
								<tr>
									<td>Kepala dan leher</td>
									<td>
										<input type='checkbox' name='neo133' value='YA' <?php if ($neo133=="YA"){echo "checked";}?> >Simetris    
										<input type='checkbox' name='neo134' value='YA' <?php if ($neo134=="YA"){echo "checked";}?> >Asimetris    
										<input type='checkbox' name='neo135' value='YA' <?php if ($neo135=="YA"){echo "checked";}?> >Caput succedaneum   
										<input type='checkbox' name='neo136' value='YA' <?php if ($neo136=="YA"){echo "checked";}?> >Cephal hematoma    
										<input type='checkbox' name='neo137' value='YA' <?php if ($neo137=="YA"){echo "checked";}?> >hydrocephalus       
										<input type='checkbox' name='neo138' value='YA' <?php if ($neo138=="YA"){echo "checked";}?> >Anencephal
										<br>
										Ubun-ubun 
										<input type='checkbox' name='neo139' value='YA' <?php if ($neo139=="YA"){echo "checked";}?> >besar     
										<input type='checkbox' name='neo140' value='YA' <?php if ($neo140=="YA"){echo "checked";}?> >rata       
										<input type='checkbox' name='neo141' value='YA' <?php if ($neo141=="YA"){echo "checked";}?> >menonjol       
										<input type='checkbox' name='neo142' value='YA' <?php if ($neo142=="YA"){echo "checked";}?> >cekung    
									</td>
								</tr>
								<tr>
									<td>Mata</td>
									<td>
										<input type='checkbox' name='neo143' value='YA' <?php if ($neo143=="YA"){echo "checked";}?> >Ikterus        
										<input type='checkbox' name='neo144' value='YA' <?php if ($neo144=="YA"){echo "checked";}?> >katarak      
										<input type='checkbox' name='neo145' value='YA' <?php if ($neo145=="YA"){echo "checked";}?> >Nistagmus
									</td>
								</tr>
								<tr>
									<td>Telinga-Hidung</td>
									<td>
										Pernafasan cuping hidung   
										<input type='checkbox' name='neo146' value='YA' <?php if ($neo146=="YA"){echo "checked";}?> >Ya      
										<input type='checkbox' name='neo147' value='YA' <?php if ($neo147=="YA"){echo "checked";}?> >Tidak,     
										<input type='checkbox' name='neo148' value='YA' <?php if ($neo148=="YA"){echo "checked";}?> >Labiopalatosch     
										<input type='checkbox' name='neo149' value='YA' <?php if ($neo149=="YA"){echo "checked";}?> >Labioschzis
										<input type='checkbox' name='neo150' value='YA' <?php if ($neo150=="YA"){echo "checked";}?> >Lainlain   
									</td>
								</tr>
								<tr>
									<td>Dada</td>
									<td>
										Retraksi dada  
										<input type='checkbox' name='neo151' value='YA' <?php if ($neo151=="YA"){echo "checked";}?> >Tidak ada retraksi Dada    
										<input type='checkbox' name='neo152' value='YA' <?php if ($neo152=="YA"){echo "checked";}?> >simetris   
										<input type='checkbox' name='neo153' value='YA' <?php if ($neo153=="YA"){echo "checked";}?> >…   <br>
										Suara nafas     
										<input type='checkbox' name='neo154' value='YA' <?php if ($neo154=="YA"){echo "checked";}?> >Normal     
										<input type='checkbox' name='neo155' value='YA' <?php if ($neo155=="YA"){echo "checked";}?> >Ronchi     
										<input type='checkbox' name='neo156' value='YA' <?php if ($neo156=="YA"){echo "checked";}?> >Wheezing,
										suara Jantung :    
										<input type='checkbox' name='neo157' value='YA' <?php if ($neo157=="YA"){echo "checked";}?> >Normal       
										<input type='checkbox' name='neo158' value='YA' <?php if ($neo158=="YA"){echo "checked";}?> >Mur mur      
										<input type='checkbox' name='neo159' value='YA' <?php if ($neo159=="YA"){echo "checked";}?> >Gallop 
									</td>
								</tr>
								<tr>
									<td>Perut </td>
									<td>
										Perut bayi :     
										<input type='checkbox' name='neo160' value='YA' <?php if ($neo160=="YA"){echo "checked";}?> >Datar tidak keras      
										<input type='checkbox' name='neo161' value='YA' <?php if ($neo161=="YA"){echo "checked";}?> >teraba Massa
										<br>      
										Umbilikal        
										<input type='checkbox' name='neo162' value='YA' <?php if ($neo162=="YA"){echo "checked";}?> >segar      
										<input type='checkbox' name='neo163' value='YA' <?php if ($neo163=="YA"){echo "checked";}?> >layu
									</td>
								</tr>
								<tr>
									<td>Alat kelamin </td><td>
										<input type='checkbox' name='neo164' value='YA' <?php if ($neo164=="YA"){echo "checked";}?> >Normal      
										<input type='checkbox' name='neo165' value='YA' <?php if ($neo165=="YA"){echo "checked";}?> >Hipospadia      
										<input type='checkbox' name='neo166' value='YA' <?php if ($neo166=="YA"){echo "checked";}?> >Bak spontan
									</td>
								</tr>
								<tr>
									<td>Ruas tulang belakang</td><td>
										<input type='checkbox' name='neo167' value='YA' <?php if ($neo167=="YA"){echo "checked";}?> >Normal      
										<input type='checkbox' name='neo168' value='YA' <?php if ($neo168=="YA"){echo "checked";}?> >Spinabifida 
									</td>
								</tr>
								<tr>
									<td>Ekstremitas</td><td>
										<input type='checkbox' name='neo169' value='YA' <?php if ($neo169=="YA"){echo "checked";}?> >Normal       
										<input type='checkbox' name='neo170' value='YA' <?php if ($neo170=="YA"){echo "checked";}?> >Polidaktili       
										<input type='checkbox' name='neo171' value='YA' <?php if ($neo171=="YA"){echo "checked";}?> >Sindaktili   
										<input type='checkbox' name='neo172' value='YA' <?php if ($neo172=="YA"){echo "checked";}?> >… 
									</td>
								</tr>
								<tr>
									<td>Anus</td><td>
										<input type='checkbox' name='neo173' value='YA' <?php if ($neo173=="YA"){echo "checked";}?> >Lubang     
										<input type='checkbox' name='neo174' value='YA' <?php if ($neo174=="YA"){echo "checked";}?> >Atresiaani 
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
				<br><br>

				<table border='0'>
					<tr>
						<td>
							<h6>G. Proteksi/keselamatan<br>
							Risiko Jatuh (Untuk pasien 0-18 tahun gunakan skala hympty dumpty)</h6>
						</td>
					</tr>
					<tr>
						<td>

							<table>
								<tr>
									<td style="border: 1px solid;">Faktor Risiko</td>
									<td style="border: 1px solid;">skala</td>
									<td style="border: 1px solid;">poin</td>
									<td style="border: 1px solid;">Skor pasien</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">Umur</td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo175' value='YA' <?php if ($neo175=="YA"){echo "checked";}?>>Kurang dari 3 tahun</td>
									<td style="border: 1px solid;">4</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo175){
											echo $tjatuh1_skor='4';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo176' value='YA' <?php if ($neo176=="YA"){echo "checked";}?>>3 tahun – 7 tahun</td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo176){
											echo $tjatuh2_skor='3';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo177' value='YA' <?php if ($neo177=="YA"){echo "checked";}?>>7 tahun – 13 tahun</td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo177){
											echo $tjatuh3_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo178' value='YA' <?php if ($neo178=="YA"){echo "checked";}?>>Lebih dari 13</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo178){
											echo $tjatuh4_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;">Jenis kelamin</td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo179' value='YA' <?php if ($neo179=="YA"){echo "checked";}?>>Laki – laki</td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo179){
											echo $tjatuh5_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo180' value='YA' <?php if ($neo180=="YA"){echo "checked";}?>>Wanita</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo180){
											echo $tjatuh6_skor='1';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">Diagnosa</td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo181' value='YA' <?php if ($neo181=="YA"){echo "checked";}?>>neorologi</td>
									<td style="border: 1px solid;">4</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo181){
											echo $tjatuh7_skor='4';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo182' value='YA' <?php if ($neo182=="YA"){echo "checked";}?>>Respiratori, dehidrasi, anemia, anorexia, syncope</td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo182){
											echo $tjatuh8_skor='3';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo183' value='YA' <?php if ($neo183=="YA"){echo "checked";}?>>Lain-lain</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo183){
											echo $tjatuh9_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;">Gangguan kognitif</td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo184' value='YA' <?php if ($neo184=="YA"){echo "checked";}?>>Keterbatasan daya pikir</td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo184){
											echo $tjatuh10_skor='3';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo185' value='YA' <?php if ($neo185=="YA"){echo "checked";}?>>Pelupa</td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo185){
											echo $tjatuh11_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo186' value='YA' <?php if ($neo186=="YA"){echo "checked";}?>>Dapat menggunakan daya pikir tanpa hambatan</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo186){
											echo $tjatuh12_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;">Faktor lingkungan</td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo187' value='YA' <?php if ($neo187=="YA"){echo "checked";}?>>Riwayat jatuh atau bayi / balita yang ditempatkan di tempat tidur</td>
									<td style="border: 1px solid;">4</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo187){
											echo $tjatuh13_skor='4';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo188' value='YA' <?php if ($neo188=="YA"){echo "checked";}?>>Pasien menggunakan alat bantu/bayi balita dalam ayunan</td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo188){
											echo $tjatuh14_skor='3';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo189' value='YA' <?php if ($neo189=="YA"){echo "checked";}?>>Pasien ditempat tidur standart</td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo189){
											echo $tjatuh15_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo190' value='YA' <?php if ($neo190=="YA"){echo "checked";}?>>Area pasien rawat jalan</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo190){
											echo $tjatuh16_skor='1';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;">Respon terhadap pembedahan, sedasi, dan anestesi</td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo191' value='YA' <?php if ($neo191=="YA"){echo "checked";}?>>Dalam 24 jam</td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo191){
											echo $tjatuh17_skor='3';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo192' value='YA' <?php if ($neo192=="YA"){echo "checked";}?>>Dalam 48 jam</td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo192){
											echo $tjatuh18_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo193' value='YA' <?php if ($neo193=="YA"){echo "checked";}?>>Lebih dari 48 jam/ tidak ada respon</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo193){
											echo $tjatuh19_skor='1';
										}
										?>
									</td>
								</tr>

								<tr>
									<td style="border: 1px solid;">Penggunaan obat-obatan</td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo194' value='YA' <?php if ($neo194=="YA"){echo "checked";}?>>Penggunaan bersamaan sedative, barbiturate, anti depresan, diuretik, narkotik</td>
									<td style="border: 1px solid;">3</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo194){
											echo $tjatuh20_skor='3';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo195' value='YA' <?php if ($neo195=="YA"){echo "checked";}?>>Salah satu dari obat diatas</td>
									<td style="border: 1px solid;">2</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo195){
											echo $tjatuh21_skor='2';
										}
										?>
									</td>
								</tr>
								<tr>
									<td style="border: 1px solid;"></td>
									<td style="border: 1px solid;"><input type='checkbox' name='neo196' value='YA' <?php if ($neo196=="YA"){echo "checked";}?>>Obat-obatan lainnya/tanpa obat</td>
									<td style="border: 1px solid;">1</td>
									<td style="border: 1px solid;">
										<?php 
										if($neo196){
											echo $tjatuh22_skor='1';
										}
										?>
									</td>
								</tr>

								<tr>
									<font size='5px'>
										<td style="border: 1px solid;" colspan="3">Total Skor</td>
										<td style="border: 1px solid;">
											<?php 
											echo $tjatuh_skor_total=$tjatuh1_skor+$tjatuh2_skor+$tjatuh3_skor+$tjatuh4_skor+$tjatuh5_skor+$tjatuh6_skor+$tjatuh7_skor+$tjatuh8_skor+$tjatuh9_skor+$tjatuh10_skor+$tjatuh11_skor+$tjatuh12_skor+$tjatuh13_skor+$tjatuh14_skor+$tjatuh15_skor+$tjatuh16_skor+$tjatuh17_skor+$tjatuh18_skor+$tjatuh19_skor+$tjatuh20_skor+$tjatuh21_skor+$tjatuh22_skor; 
											?>
										</td>
									</font>
								</tr>

							</table>
							&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">

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


						</td>
					</tr>
				</table>
				<br><br>

				<div class="card shadow-sm mb-4">
					<div class="card-header bg-info text-white">
						<i class="bi bi-baby me-2"></i> <h6>LANGKAH KEDUA</h6>
					</div>
					<div class="card-body">
						<div class="row g-3">
							<!-- Langkah Kedua -->
							<div class="col-12">									
								<label for="neo197">Identifikasi Diagnosa Masalah dan Kebutuhan</label>
								<textarea name="neo197" id="neo197" style="min-width:100%; min-height:120px;"><?php echo $neo197;?></textarea>
							</div>

							<!-- Identifikasi Diagnosa dan Antisipasi Masalah Potensial -->
							<div class="col-12">
								<label for="neo198">Identifikasi Diagnosa dan Antisipasi Masalah Potensial</label>
								<textarea name="neo198" id="neo198" style="min-width:100%; min-height:60px;"><?php echo $neo198;?></textarea>
							</div>

							<!-- Kebutuhan Akan Tindakan Segera -->
							<div class="col-12">
								<label for="neo199">Kebutuhan Akan Tindakan Segera</label>
								<textarea name="neo199" id="neo199" style="min-width:100%; min-height:60px;"><?php echo $neo199;?></textarea>
							</div>

							<!-- Tombol Simpan -->
							<div class="col-12">
								<button type="submit" name="simpan" class="btn btn-success">Simpan</button>
							</div>
						</div>
					</div>
				</div>


				<br>
				<br>
				<table border='0'>
					<tr>
						<td>
							<h6>Discharge Planning (Rencana Pemulangan)</h6>
						</td>
					</tr>
					<tr>
						<td>
							1. Setelah pulang pasien akan tinggal dengan siapa ?<br>
							<input type='checkbox' name='neo200' value='YA' <?php if ($neo200=="YA"){echo "checked";}?>>Ibu saja    
							<input type='checkbox' name='neo201' value='YA' <?php if ($neo201=="YA"){echo "checked";}?>>Orang Tua saja  
							<input type='checkbox' name='neo202' value='YA' <?php if ($neo202=="YA"){echo "checked";}?>>Orang Tua dan Nenek   
							<input type='checkbox' name='neo203' value='YA' <?php if ($neo203=="YA"){echo "checked";}?>>Orang Tua dengan pembantu      <br> 
							2. Posisi kamar pasien di Rumah? <br>
							<input type='checkbox' name='neo204' value='YA' <?php if ($neo204=="YA"){echo "checked";}?>>Lantai dasar          
							<input type='checkbox' name='neo205' value='YA' <?php if ($neo205=="YA"){echo "checked";}?>>Lantai Atas    <br>       
							3. Kondis rumah yang akan ditinggali   <br> 
							Penerangan                  
							<input type='checkbox' name='neo206' value='YA' <?php if ($neo206=="YA"){echo "checked";}?>>Terang          
							<input type='checkbox' name='neo207' value='YA' <?php if ($neo207=="YA"){echo "checked";}?>>Cukup         
							<input type='checkbox' name='neo208' value='YA' <?php if ($neo208=="YA"){echo "checked";}?>>Kurang Terang<br>
							Letak kamar mandi       
							<input type='checkbox' name='neo209' value='YA' <?php if ($neo209=="YA"){echo "checked";}?>>Dekat           
							<input type='checkbox' name='neo210' value='YA' <?php if ($neo210=="YA"){echo "checked";}?>>Jauh<br>
							4. Jenis makanan pasien<br>
							<input type='checkbox' name='neo211' value='YA' <?php if ($neo211=="YA"){echo "checked";}?>>ASI Eksklusif            
							<input type='checkbox' name='neo212' value='YA' <?php if ($neo212=="YA"){echo "checked";}?>>Susu formula          
							<input type='checkbox' name='neo213' value='YA' <?php if ($neo213=="YA"){echo "checked";}?>>ASI + Formula<br>

						</td>
					</tr>
				</table>
				&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
				<br><br>
				<table border='0'>
					<tr>
						<td>
							<br>
							<br>
							<br>
							<br>
							Selesai asesmen tanggal :<?php echo $tgl_assesment; ?> , Jam : <?php echo $jam_assesment; ?>
							<br>
							<table border='0' width="100%">

								<tr>
									<td width='50%' align="center">
										Perawat yang melakukan Asesmen
									</td>
									<td align="center">
										DPJP										
									</td>
								</tr>
								<tr>
									<td align="center">
										<?php $verif_perawat="Document ini telah diVerifikasi Oleh : ".$userid."<br>Pada Tanggal : ".$tgl_assesment;?>
										<!-- <center><img alt='Verifikasi' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_perawat&choe=UTF-8'/></center> -->

										<?php 
										QRcode::png($verif_perawat, "asneo1.png", "L", 2, 2);   
										echo "<center><img src='asneo1.png'></center>";
										?>

										<br>
										<?php echo $userid;?>
									</td>
									<td align="center">
										<?php
										$qnm="
										SELECT TOP (1)
										LTRIM(RTRIM(LEFT(dpjp, CHARINDEX('-', dpjp + '-') - 1))) AS kode,
										LTRIM(RTRIM(SUBSTRING(dpjp, CHARINDEX('-', dpjp) + 1, LEN(dpjp)))) AS nmdokter
										FROM ERM_RI_ASSESMEN_AWAL_NEONATUS
										WHERE noreg='$noreg'
										";
										$hqnm  = sqlsrv_query($conn, $qnm);
										$dhqnm  = sqlsrv_fetch_array($hqnm, SQLSRV_FETCH_ASSOC); 
										$nmdokter = trim($dhqnm['nmdokter']);


										$verif_dpjp="Document ini telah diVerifikasi Oleh : ".$nmdokter."Pada Tanggal : ".$tgl_verif;

										if($tgl_verif){
											QRcode::png($verif_dpjp, "asneo2.png", "L", 2, 2);   
											echo "<center><img src='asneo2.png'></center>";
										}
										echo $dpjp;
										?>													
									</td>
								</tr>
							</table>
							<br>
						</td>
					</tr>
				</table>
				<hr>
				<table border='0' width="100%">
					<tr>
						<td align="center">
							<h5>Asesmen Awal Medis Neonatus</h5>
						</td>
					</tr>
					<tr>
						<td>
							<table border="1" width="100%">
								<tr>
									<td>Pemeriksaan Fisik</td><td>Keterangan</td>
								</tr>
								<tr>
									<td>Kepala</td><td><input type='text' name='neo214' value='<?php echo $neo214;?>' size='100'></td>
								</tr>
								<tr>
									<td>Dada</td><td><input type='text' name='neo215' value='<?php echo $neo215;?>' size='100'></td>
								</tr>
								<tr>
									<td>Abdomen</td><td><input type='text' name='neo216' value='<?php echo $neo216;?>' size='100'></td>
								</tr>
								<tr>
									<td>Extrimitas</td><td><input type='text' name='neo217' value='<?php echo $neo217;?>' size='100'></td>
								</tr>
								<tr>
									<td>Genetalia</td><td><input type='text' name='neo218' value='<?php echo $neo218;?>' size='100'></td>
								</tr>


							</table>
						</td>
					</tr>
				</table>
				&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
				<br><br>

				<table border='0' width="100%">
					<tr>
						<td colspan='4' align="center"><h5>Downes Skor</h5></td>
					</tr>
					<tr>
						<td>
							<table border="1" width="100%">
								<tr>
									<td bgcolor='#66CDAA'></td><td bgcolor='#66CDAA'>0</td><td bgcolor='#66CDAA'>1</td><td bgcolor='#66CDAA'>2</td>
								</tr>
								<tr>
									<td>Pernafasan</td>
									<td><input type='radio' name='neo219' value='1' <?php if ($neo219=="1"){echo "checked";}?>><60x/mnt</td>
										<td><input type='radio' name='neo219' value='2' <?php if ($neo219=="2"){echo "checked";}?>>60-80x/mnt</td>
										<td><input type='radio' name='neo219' value='3' <?php if ($neo219=="3"){echo "checked";}?>>>80x/mnt</td>
										<?php 
										if($neo219=='1'){
											$tdow1_skor='0';
										}
										if($neo219=='2'){
											$tdow1_skor='1';
										}
										if($neo219=='3'){
											$tdow1_skor='2';
										}
										?>
									</tr>
									<tr>
										<td>Sianosis</td>
										<td><input type='radio' name='neo220' value='1' <?php if ($neo220=="1"){echo "checked";}?>>Tidak ada sianosis</td>
										<td><input type='radio' name='neo220' value='2' <?php if ($neo220=="2"){echo "checked";}?>>Sianosis hilang dg O2</td>
										<td><input type='radio' name='neo220' value='3' <?php if ($neo220=="3"){echo "checked";}?>>Sianosis menetap walaupun diberikan O2</td>
										<?php 
										if($neo220=='1'){
											$tdow2_skor='0';
										}
										if($neo220=='2'){
											$tdow2_skor='1';
										}
										if($neo220=='3'){
											$tdow2_skor='2';
										}
										?>
									</tr>
									<tr>
										<td>Retraksi</td>
										<td><input type='radio' name='neo221' value='1' <?php if ($neo221=="1"){echo "checked";}?>>Tidak ada Retraksi</td>
										<td><input type='radio' name='neo221' value='2' <?php if ($neo221=="2"){echo "checked";}?>>Retraksi Ringan</td>
										<td><input type='radio' name='neo221' value='3' <?php if ($neo221=="3"){echo "checked";}?>>Retraksi Berat</td>
										<?php 
										if($neo221=='1'){
											$tdow3_skor='0';
										}
										if($neo221=='2'){
											$tdow3_skor='1';
										}
										if($neo221=='3'){
											$tdow3_skor='2';
										}
										?>
									</tr>
									<tr>
										<td>Merintih</td>
										<td><input type='radio' name='neo222' value='1' <?php if ($neo222=="1"){echo "checked";}?>>Tidak merintih</td>
										<td><input type='radio' name='neo222' value='2' <?php if ($neo222=="2"){echo "checked";}?>>Dpt di dengar dg stetoskop</td>
										<td><input type='radio' name='neo222' value='3' <?php if ($neo222=="3"){echo "checked";}?>>Dpt didengar tanpa alat bantu</td>
										<?php 
										if($neo222=='1'){
											$tdow4_skor='0';
										}
										if($neo222=='2'){
											$tdow4_skor='1';
										}
										if($neo222=='3'){
											$tdow4_skor='2';
										}
										?>

									</tr>
									<tr>
										<td>Udara masuk</td>
										<td><input type='radio' name='neo223' value='1' <?php if ($neo223=="1"){echo "checked";}?>>Udara masuk</td>
										<td><input type='radio' name='neo223' value='2' <?php if ($neo223=="2"){echo "checked";}?>>Penurunan ringan udara masuk</td>
										<td><input type='radio' name='neo223' value='3' <?php if ($neo223=="3"){echo "checked";}?>>Tdk ada udara masuk</td>
										<?php 
										if($neo223=='1'){
											$tdow5_skor='0';
										}
										if($neo223=='2'){
											$tdow5_skor='1';
										}
										if($neo223=='3'){
											$tdow5_skor='2';
										}
										?>

									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<table width="100%">
									<tr>
										<td align="center">
											<font size='5px'>
												Total Downes Skor : <?php echo $total_dow=$tdow1_skor+$tdow2_skor+$tdow3_skor+$tdow4_skor+$tdow5_skor; ?>
											</font>
											<br>
											<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
										</td>
										<td>
											Evaluasi :
											<table border="1">
												<tr>
													<td>Total</td><td>Diagnosa</td>
												</tr>
												<tr>
													<td>1-3</td><td>Sesak nafas ringan</td>
												</tr>
												<tr>
													<td>4-5</td><td>Sesak nafas sedang</td>
												</tr>
												<tr>
													<td>≥6</td><td>Sesak nafas berat</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<br>									
							</td>
						</tr>

					</table>

					<br>

					<table border='0' width="100%">
						<tr>
							<td><h6>Ballard Skor</h6></td>
						</tr>
						<tr>
							<td align="center">Maturitas Neuromuscular
								<table border=1  width="100%">
									<tr>
										<td>Score</td>
										<td>-1</td>
										<td>0</td>
										<td>1</td>
										<td>2</td>
										<td>3</td>
										<td>4</td>
										<td>5</td>
									</tr>
									<tr>
										<td>Postur</td>
										<td></td>
										<td><img src='image/1.png' width="50px">
											<input type='checkbox' name='neo224' value='1' <?php if ($neo224=="1"){echo "checked";}?>></td>
											<td><img src='image/2.png' width="50px">
												<input type='checkbox' name='neo225' value='1' <?php if ($neo225=="1"){echo "checked";}?>></td>
												<td><img src='image/3.png' width="50px">
													<input type='checkbox' name='neo226' value='1' <?php if ($neo226=="1"){echo "checked";}?>></td>
													<td><img src='image/4.png' width="50px">
														<input type='checkbox' name='neo227' value='1' <?php if ($neo227=="1"){echo "checked";}?>>
													</td>
													<td></td>
													<td></td>
												</tr>
												<?php 
												if($neo224){
													$dow1=0;
												}
												if($neo225){
													$dow1=1;
												}
												if($neo226){
													$dow1=2;
												}
												if($neo227){
													$dow1=3;
												}
												?>
												<tr>
													<td>sudut pergelangan tangan</td>
													<td><img src='image/6.png' width="50px">
														<input type='checkbox' name='neo228' value='1' <?php if ($neo228=="1"){echo "checked";}?>></td>
														<td><img src='image/7.png' width="50px">
															<input type='checkbox' name='neo229' value='1' <?php if ($neo229=="1"){echo "checked";}?>></td>
															<td><img src='image/8.png' width="50px">
																<input type='checkbox' name='neo230' value='1' <?php if ($neo230=="1"){echo "checked";}?>></td>
																<td><img src='image/9.png' width="50px">
																	<input type='checkbox' name='neo231' value='1' <?php if ($neo231=="1"){echo "checked";}?>>
																</td>
																<td><img src='image/10.png' width="50px">
																	<input type='checkbox' name='neo232' value='1' <?php if ($neo232=="1"){echo "checked";}?>>
																</td>
																<td><img src='image/11.png' width="50px">
																	<input type='checkbox' name='neo233' value='1' <?php if ($neo233=="1"){echo "checked";}?>>
																</td>
																<td></td>
															</tr>
															<?php 
															if($neo228){
																$dow2=-1;
															}
															if($neo229){
																$dow2=0;
															}
															if($neo230){
																$dow2=1;
															}
															if($neo231){
																$dow2=2;
															}
															if($neo232){
																$dow2=3;
															}
															if($neo233){
																$dow2=4;
															}
															?>

															<tr>
																<td>rekoil lengan</td>
																<td></td>
																<td><img src='image/13.png' width="50px"><input type='checkbox' name='neo321' value='1' <?php if ($neo321=="1"){echo "checked";}?>></td>
																<td><img src='image/14.png' width="50px"><input type='checkbox' name='neo322' value='1' <?php if ($neo322=="1"){echo "checked";}?>></td>
																<td><img src='image/15.png' width="50px"><input type='checkbox' name='neo323' value='1' <?php if ($neo323=="1"){echo "checked";}?>></td>
																<td><img src='image/16.png' width="50px"><input type='checkbox' name='neo324' value='1' <?php if ($neo324=="1"){echo "checked";}?>></td>
																<td><img src='image/17.png' width="50px"><input type='checkbox' name='neo325' value='1' <?php if ($neo325=="1"){echo "checked";}?>></td>
																<td></td>
															</tr>
															<?php 
															if($neo321){
																$dow3=0;
															}
															if($neo322){
																$dow3=1;
															}
															if($neo323){
																$dow3=2;
															}
															if($neo324){
																$dow3=3;
															}
															if($neo325){
																$dow3=4;
															}
															?>

															<tr>
																<td>sudut papolitea</td>
																<td><img src='image/18.png' width="50px"><input type='checkbox' name='neo326' value='1' <?php if ($neo326=="1"){echo "checked";}?>></td>
																<td><img src='image/19.png' width="50px"><input type='checkbox' name='neo327' value='1' <?php if ($neo327=="1"){echo "checked";}?>></td>
																<td><img src='image/20.png' width="50px"><input type='checkbox' name='neo328' value='1' <?php if ($neo328=="1"){echo "checked";}?>></td>
																<td><img src='image/21.png' width="50px"><input type='checkbox' name='neo329' value='1' <?php if ($neo329=="1"){echo "checked";}?>></td>
																<td><img src='image/22.png' width="50px"><input type='checkbox' name='neo330' value='1' <?php if ($neo330=="1"){echo "checked";}?>></td>
																<td><img src='image/23.png' width="50px"><input type='checkbox' name='neo331' value='1' <?php if ($neo331=="1"){echo "checked";}?>></td>
																<td><img src='image/24.png' width="50px"><input type='checkbox' name='neo332' value='1' <?php if ($neo332=="1"){echo "checked";}?>></td>
															</tr>
															<?php 
															if($neo326){
																$dow4=-1;
															}
															if($neo327){
																$dow4=0;
															}
															if($neo328){
																$dow4=1;
															}
															if($neo329){
																$dow4=2;
															}
															if($neo330){
																$dow4=3;
															}
															if($neo331){
																$dow4=4;
															}
															if($neo332){
																$dow4=5;
															}

															?>

															<tr>
																<td>tanda selempang</td>
																<td><img src='image/25.png' width="50px"><input type='checkbox' name='neo333' value='1' <?php if ($neo333=="1"){echo "checked";}?>></td>
																<td><img src='image/26.png' width="50px"><input type='checkbox' name='neo334' value='1' <?php if ($neo334=="1"){echo "checked";}?>></td>
																<td><img src='image/27.png' width="50px"><input type='checkbox' name='neo335' value='1' <?php if ($neo335=="1"){echo "checked";}?>></td>
																<td><img src='image/28.png' width="50px"><input type='checkbox' name='neo336' value='1' <?php if ($neo336=="1"){echo "checked";}?>></td>
																<td><img src='image/29.png' width="50px"><input type='checkbox' name='neo337' value='1' <?php if ($neo337=="1"){echo "checked";}?>></td>
																<td><img src='image/30.png' width="50px"><input type='checkbox' name='neo338' value='1' <?php if ($neo338=="1"){echo "checked";}?>></td>
																<td></td>
															</tr>
															<?php 
															if($neo333){
																$dow5=-1;
															}
															if($neo334){
																$dow5=0;
															}
															if($neo335){
																$dow5=1;
															}
															if($neo336){
																$dow5=2;
															}
															if($neo337){
																$dow5=3;
															}
															if($neo338){
																$dow5=4;
															}
															?>

															<tr>
																<td>tumit ke telinga</td>
																<td><img src='image/31.png' width="50px"><input type='checkbox' name='neo339' value='1' <?php if ($neo339=="1"){echo "checked";}?>></td>
																<td><img src='image/32.png' width="50px"><input type='checkbox' name='neo340' value='1' <?php if ($neo340=="1"){echo "checked";}?>></td>
																<td><img src='image/33.png' width="50px"><input type='checkbox' name='neo341' value='1' <?php if ($neo341=="1"){echo "checked";}?>></td>
																<td><img src='image/34.png' width="50px"><input type='checkbox' name='neo342' value='1' <?php if ($neo342=="1"){echo "checked";}?>></td>
																<td><img src='image/35.png' width="50px"><input type='checkbox' name='neo343' value='1' <?php if ($neo343=="1"){echo "checked";}?>></td>
																<td><img src='image/36.png' width="50px"><input type='checkbox' name='neo344' value='1' <?php if ($neo344=="1"){echo "checked";}?>></td>
																<td></td>
															</tr>

															<?php 
															if($neo339){
																$dow6=-1;
															}
															if($neo340){
																$dow6=0;
															}
															if($neo341){
																$dow6=1;
															}
															if($neo342){
																$dow6=2;
															}
															if($neo343){
																$dow6=3;
															}
															if($neo344){
																$dow6=4;
															}
															?>

														</table>
														<br>
														<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
													</td>
													<td>
														Keterangan
														<table border=1 width="100%">
															<tr>
																<td rowspan="12" align="center"><b>Total Skor<br>
																	<font size='6px'>
																		<?php 
																		echo $total_dow = $dow1+$dow2+$dow3+$dow4+$dow5+$dow6;
																		?>
																	</font></b>
																</td><td>Skor</td><td>Week</td>
															</tr>
															<tr>
																<td>0</td><td>24</td>
															</tr>
															<tr>
																<td>5</td><td>26</td>
															</tr>
															<tr>
																<td>10</td><td>28</td>
															</tr>
															<tr>
																<td>15</td><td>30</td>
															</tr>
															<tr>
																<td>20</td><td>32</td>
															</tr>
															<tr>
																<td>25</td><td>34</td>
															</tr>
															<tr>
																<td>30</td><td>36</td>
															</tr>
															<tr>
																<td>35</td><td>38</td>
															</tr>
															<tr>
																<td>40</td><td>40</td>
															</tr>
															<tr>
																<td>45</td><td>42</td>
															</tr>
															<tr>
																<td>50</td><td>44</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
											<br>
											Maturitas Fisik<br>
											<table border="1">
												<tr>
													<td></td>
													<td>0</td>
													<td>1</td>
													<td>2</td>
													<td>3</td>
													<td>4</td>
													<td>5</td>
													<td>Skor</td>
												</tr>	
												<tr>
													<td>Kulit</td>
													<td><input type='checkbox' name='neo248' value='1' <?php if ($neo248=="1"){echo "checked";}?>>Merah seperti agar, transparan</td>
													<td><input type='checkbox' name='neo249' value='1' <?php if ($neo249=="1"){echo "checked";}?>>Merah muda, licin/halus tampak vena</td>
													<td><input type='checkbox' name='neo250' value='1' <?php if ($neo250=="1"){echo "checked";}?>>Permukaan mengelupas dengan/tanpa ruam, sedikit vena</td>
													<td><input type='checkbox' name='neo251' value='1' <?php if ($neo251=="1"){echo "checked";}?>>Daerah pucat retak-retak, vena jarang</td>
													<td><input type='checkbox' name='neo252' value='1' <?php if ($neo252=="1"){echo "checked";}?>>Seperti kertas putih, retak lebih dalam, tidak ada vena</td>
													<td><input type='checkbox' name='neo253' value='1' <?php if ($neo253=="1"){echo "checked";}?>>Seperti kulit retak, mengerut</td>
													<td>
														<?php 
														if($neo248){$ts1=0;}
														if($neo249){$ts1=1;}
														if($neo250){$ts1=2;}
														if($neo251){$ts1=3;}
														if($neo252){$ts1=4;}
														if($neo253){$ts1=5;}
														echo $ts1;
														?>
													</td>
												</tr>
												<tr>
													<td>lanugo</td>
													<td><input type='checkbox' name='neo254' value='1' <?php if ($neo254=="1"){echo "checked";}?>>Tidak ada</td>
													<td><input type='checkbox' name='neo255' value='1' <?php if ($neo255=="1"){echo "checked";}?>>banyak</td>
													<td><input type='checkbox' name='neo256' value='1' <?php if ($neo256=="1"){echo "checked";}?>>menipis</td>
													<td><input type='checkbox' name='neo257' value='1' <?php if ($neo257=="1"){echo "checked";}?>>menghilang</td>
													<td><input type='checkbox' name='neo258' value='1' <?php if ($neo258=="1"){echo "checked";}?>>Umumnya tidak ada</td>
													<td></td>
													<td>
														<?php 
														if($neo254){$ts2=0;}
														if($neo255){$ts2=1;}
														if($neo256){$ts2=2;}
														if($neo257){$ts2=3;}
														if($neo258){$ts2=4;}
														echo $ts2;
														?>
													</td>
												</tr>
												<tr>
													<td>Lipatan piantar</td>
													<td><input type='checkbox' name='neo259' value='1' <?php if ($neo259=="1"){echo "checked";}?>>Tidak ada</td>
													<td><input type='checkbox' name='neo260' value='1' <?php if ($neo260=="1"){echo "checked";}?>>Tanda merah sangat sedikit</td>
													<td><input type='checkbox' name='neo261' value='1' <?php if ($neo261=="1"){echo "checked";}?>>Hanya lipatan anterior yang melintang</td>
													<td><input type='checkbox' name='neo262' value='1' <?php if ($neo262=="1"){echo "checked";}?>>Lipatan 2/3 anterior</td>
													<td><input type='checkbox' name='neo263' value='1' <?php if ($neo263=="1"){echo "checked";}?>>lipatan diseluruh telapak</td>
													<td></td>
													<td>
														<?php 
														if($neo259){$ts3=0;}
														if($neo260){$ts3=1;}
														if($neo261){$ts3=2;}
														if($neo262){$ts3=3;}
														if($neo263){$ts3=4;}
														echo $ts3;
														?>
													</td>
												</tr>
												<tr>
													<td>Payudara</td>
													<td><input type='checkbox' name='neo264' value='1' <?php if ($neo264=="1"){echo "checked";}?>>Hampir tidak ada</td>
													<td><input type='checkbox' name='neo265' value='1' <?php if ($neo265=="1"){echo "checked";}?>>Areola datar, tidak ada tonjolan</td>
													<td><input type='checkbox' name='neo266' value='1' <?php if ($neo266=="1"){echo "checked";}?>>Areola seperti titik,penojolan 1-2 mm</td>
													<td><input type='checkbox' name='neo267' value='1' <?php if ($neo267=="1"){echo "checked";}?>>Areola lebih jelas, tonjolan 3-4mm</td>
													<td><input type='checkbox' name='neo268' value='1' <?php if ($neo268=="1"){echo "checked";}?>>areola penuh, tonjolan 5-10 mm</td>
													<td></td>
													<td>
														<?php 
														if($neo264){$ts4=0;}
														if($neo265){$ts4=1;}
														if($neo266){$ts4=2;}
														if($neo267){$ts4=3;}
														if($neo268){$ts4=4;}
														echo $ts4;
														?>
													</td>
												</tr>
												<tr>
													<td>Daun telinga</td>
													<td><input type='checkbox' name='neo269' value='1' <?php if ($neo269=="1"){echo "checked";}?>>Datar, tetap terlipat</td>
													<td><input type='checkbox' name='neo270' value='1' <?php if ($neo270=="1"){echo "checked";}?>>Sedikit melengkung, lunak, lambat untuk kembali</td>
													<td><input type='checkbox' name='neo271' value='1' <?php if ($neo271=="1"){echo "checked";}?>>Bentuknya lebih baik, lunak, mudah membalik</td>
													<td><input type='checkbox' name='neo272' value='1' <?php if ($neo272=="1"){echo "checked";}?>>Bentuk sempurna, membalik seketika</td>
													<td><input type='checkbox' name='neo273' value='1' <?php if ($neo273=="1"){echo "checked";}?>>Tulang rawan tebal, telinga kaku</td>
													<td></td>
													<td>
														<?php 
														if($neo269){$ts5=0;}
														if($neo270){$ts5=1;}
														if($neo271){$ts5=2;}
														if($neo272){$ts5=3;}
														if($neo273){$ts5=4;}
														echo $ts5;
														?>
													</td>
												</tr>
												<tr>
													<td>Genetalia pria</td>
													<td><input type='checkbox' name='neo274' value='1' <?php if ($neo274=="1"){echo "checked";}?>>Skrotum kosong, tidak ada rugae</td>
													<td><input type='checkbox' name='neo275' value='1' <?php if ($neo275=="1"){echo "checked";}?>>Testis dikanal bagian atas, rugae jarang</td>
													<td><input type='checkbox' name='neo276' value='1' <?php if ($neo276=="1"){echo "checked";}?>>Testis menurun, sedikit rugae</td>
													<td><input type='checkbox' name='neo277' value='1' <?php if ($neo277=="1"){echo "checked";}?>>testis dibawah, rugaenya bagus</td>
													<td><input type='checkbox' name='neo278' value='1' <?php if ($neo278=="1"){echo "checked";}?>>Testis bergantung, rugae dalam</td>
													<td></td>
													<td>
														<?php 
														if($neo274){$ts6=0;}
														if($neo275){$ts6=1;}
														if($neo276){$ts6=2;}
														if($neo277){$ts6=3;}
														if($neo278){$ts6=4;}
														echo $ts6;
														?>
													</td>
												</tr>
												<tr>
													<td>Genetalia wanita</td>
													<td><input type='checkbox' name='neo279' value='1' <?php if ($neo279=="1"){echo "checked";}?>>Klitoris menonjol, labia minora kecil</td>
													<td><input type='checkbox' name='neo280' value='1' <?php if ($neo280=="1"){echo "checked";}?>>Klitoris menonjol, minora membesar</td>
													<td><input type='checkbox' name='neo281' value='1' <?php if ($neo281=="1"){echo "checked";}?>>Labia mayora & minora menonjol</td>
													<td><input type='checkbox' name='neo282' value='1' <?php if ($neo282=="1"){echo "checked";}?>>Labia mayora besar, labia minora kecil</td>
													<td><input type='checkbox' name='neo283' value='1' <?php if ($neo283=="1"){echo "checked";}?>>Labia mayora menutupi klitoris & labia minora</td>
													<td></td>
													<td>
														<?php 
														if($neo279){$ts7=0;}
														if($neo280){$ts7=1;}
														if($neo281){$ts7=2;}
														if($neo282){$ts7=3;}
														if($neo283){$ts7=4;}
														echo $ts6;
														?>
													</td>
												</tr>
												<tr>
													<td colspan="8" align="center">
														<?php 
														$total_skor = $ts1+$ts2+$ts3+$ts4+$ts5+$ts6; 
														?>
														<font size='5px'>
															<b>
																Total Skor : [<?php echo $total_skor; ?>]
															</b>
														</font>
														<br>
														<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
													</td>
												</tr>
											</table>
											<br><br>
											<table border='0'>
												<tr><td>
													<h6>Identifikasi Bayi</h6>
												</td></tr>
												<tr><td>
													<table>
														<tr>
															<td>Nama bayi &nbsp;&nbsp;:  <input type='text' name='neo284' value='<?php echo $neo284;?>' size='50'></td>
															<td>Bayi lahir : <input type='text' name='neo285' value='<?php echo $neo285;?>' placeholder='Hidup/Mati'></td>
														</tr>
														<tr>
															<td>Nama Ibu  &nbsp;&nbsp;&nbsp;:  <input type='text' name='neo286' value='<?php echo $neo286;?>' size='50'></td>
															<td>Hari : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='neo287' value='<?php echo $neo287;?>' placeholder='Hidup/Mati'></td>
														</tr>
														<tr>
															<td>Nama Ayah  :  <input type='text' name='neo288' value='<?php echo $neo288;?>' size='50'></td>
															<td>Tanggal : &nbsp;&nbsp;<input type='text' name='neo289' value='<?php echo $neo289;?>' placeholder='Hidup/Mati'></td>
														</tr>
														<tr>
															<td>No RM ibu  &nbsp;:  <input type='text' name='neo290' value='<?php echo $neo290;?>'></td>
															<td>jenis Kelamin : <input type='text' name='neo291' value='<?php echo $neo291;?>' placeholder='Hidup/Mati'></td>
														</tr>
														<tr>
															<td>Telp  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:  <input type='text' name='neo292' value='<?php echo $neo292;?>'></td>
															<td>Anak Ke : &nbsp;&nbsp;<input type='text' name='neo293' value='<?php echo $neo293;?>' placeholder='Hidup/Mati'></td>
														</tr>
													</table>
													<br>
												</td></tr>
											</table>
											&nbsp;&nbsp;<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
											<br><br>
											<table border='0'>
												<tr>
													<td colspan="3">
														<h6>Tanda Tangan dan nama terang pemberi gelang identitas bayi ( No RM dan Nama Bayi )</h6>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														Upload<br>
														<div class="col-12">
															Upload Gambar : 
															<?php 
															$q		= "select doc as doc from ERM_RI_ASSESMEN_AWAL_NEONATUS where noreg = '$noreg'";
															$h  = sqlsrv_query($conn, $q);			  
															$d  = sqlsrv_fetch_array($h, SQLSRV_FETCH_ASSOC); 
															$doc = trim($d['doc']);
															echo "Gambar terupload : <a href='$doc' target='_blank'>$doc</a>";
															?>
															<input type="file" name="doc" class="form-control" >Ekstensi .jpg | .png
														</div>
													</td>
												</tr>
											</table>
											<input type='submit' name='upload' value='upload' style="color: white;background: #66CDAA;border-color: #66CDAA;">
											<br><br>
											<table border='0' width="100%">
												<tr>
													<td>
														<h6>SERAH TERIMA BAYI</h6>
														Sewaktu pulang<br>
														Saya menyatakan bahwa pada saat pulang telah menerima bayi saya, memeriksanya dan meyakinkan bahwa bayi tersebut dalah betul-betul anak saya<br>
														Saya mengecek nama dan nomor rekam medis pada gelang identitas adalah
														dan berisi keterangan pengenal yang sesuai<br>
														<table border="1" width="100%">
															<tr>
																<td style='border: 1px solid;' align="center">Tanda tangan dan nama terang</td>
																<td style='border: 1px solid;' align="center">Tanda tangan dan nama terang</td>
															</tr>
															<tr>
																<td style='border: 1px solid;' align="center">
																	Saksi : bidan/dokter<br>
																	<!-- <input type='text' name='neo297' value='<?php echo $user;?>'> -->
																	<?php 
																	QRcode::png($verif_perawat, "image.png", "L", 2, 2);   
																	echo "<center><img src='image.png'></center>";
																	echo "<br>";
																	echo $verif_perawat;
																	?>
																</td>
																<td style='border: 1px solid;' align="center">
																	<table>
																		<tr>
																			<td align="center">Ibu - Tanda Tangan </td>
																		</tr>
																		<tr>
																			<td align="center">

																				<?php  
																				if($ttd_pasien){
																					echo " <img src='$ttd_pasien' height='200' width='200'>";
																					echo "<br><br>";
																					echo "<input type='text' name='ttd_pasien' value='$ttd_pasien' size='50' hidden>";
																				}
																				?>
																			</td>
																			<td align="center">
																				Input Tanda Tangan Baru
																				<div class="col-8">
																					<div id="sig"></div>
																					<br />
																					<button id="clear" class="btn btn-warning mt-1">Hapus Tanda Tangan</button>
																					<textarea id="signature64" name="signed" style="display: none" class="input-group mb-3"></textarea>
																					<br />
																					<button class="btn btn-success mt-1" id="" value="ttd" name="ttd">Simpan TTD</button>
																					<br>
																				</div>

																			</td>
																		</tr>
																	</table>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</div>
									</form>

									<script type="text/javascript">
										var sig = $('#sig').signature({
											syncField: '#signature64',
											syncFormat: 'PNG'
										});
										$('#clear').click(function(e) {
											e.preventDefault();
											sig.signature('clear');
											$("#signature64").val('');
										});
									</script>

								</body>
							</div>

							<?php

							if (isset($_POST["ttd"])) {
								$folderPath = "upload/";
								$ttd_pasien = $_POST['ttd_pasien'];
								unlink($resume134);

								if(empty($_POST['signed'])){
									echo "Kosong";
									$eror = "Tanda Tangan Kosong";
								}else{
									$image_parts = explode(";base64,", $_POST['signed']); 
									$image_type_aux = explode("image/", $image_parts[0]);
									$image_type = $image_type_aux[1];
									$image_base64 = base64_decode($image_parts[1]);
									$file = $folderPath . uniqid() . '.'.$image_type;
									file_put_contents($file, $image_base64);
									echo "Tanda Tangan Sukses Diupload ";

									$qInsert = "UPDATE ERM_RI_ASSESMEN_AWAL_NEONATUS SET ttd_pasien = '$file' WHERE NOREG='$noreg'";

									$qInsert;
									$result = sqlsrv_query($conn, $qInsert);

									$eror = 'Berhasil';

								}

								echo "
								<div class='alert alert-success' role='alert'>
								".$eror."
								</div>
								";

								echo "
								<script>
								alert('".$eror."');
								</script>
								";


							}


							if (isset($_POST["upload"])) {
	// Ambil Gambar yang Dikirim dari Form
								$doc_file = $_FILES['doc']['name'];
  	// Set path folder tempat menyimpan gambarnya
								$path = "upload/".$doc_file;

								$q  = "update ERM_RI_ASSESMEN_AWAL_NEONATUS set doc='$path' where noreg='$noreg'";         
								$hs = sqlsrv_query($conn,$q);

								if($hs){
									if($doc_file){
										unlink($foto_image);
			// move_uploaded_file($nama_file, $path);
										move_uploaded_file($_FILES['doc']['tmp_name'],$path);
									}
									$eror = "Success";
									echo "
									<div class='alert alert-success' role='alert'>
									".$eror."
									</div>
									";

								}else{
									$eror = "Gagal Update";
									echo "
									<div class='alert alert-danger' role='alert'>
									".$eror."
									</div>
									";

								}

								echo "
								<script>
								alert('".$eror."');
								</script>
								";

							}

							if (isset($_POST["simpan"])) {

								$lanjut='Y';

	//select user....
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

									echo $q  = "update ERM_RI_ASSESMEN_AWAL_NEONATUS set										
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

