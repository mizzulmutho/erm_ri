<?php 
//include ("koneksi.php");

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

$qi="SELECT noreg FROM ERM_RI_ASSESMEN_AWAL_BERSALIN where noreg='$noreg'";
$hi  = sqlsrv_query($conn, $qi);        
$di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
$regcek = $di['noreg'];

if(empty($regcek)){
	$q  = "insert into ERM_RI_ASSESMEN_AWAL_BERSALIN(noreg,userid,tglentry) values ('$noreg','$user','$tglentry')";
	$hs = sqlsrv_query($conn,$q);
}else{

	$qe="
	SELECT *,CONVERT(VARCHAR, tglrawat, 23) as tglrawat,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ASSESMEN_AWAL_BERSALIN
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$userid = $de['userid'];

	$tglrawat = $de['tglrawat'];
	$tgl_assesment = $de['tgl_assesment'];
	$jam_assesment = $de['jam_assesment'];
	$dpjp = $de['dpjp'];
	$userid = $de['userid'];
	
	$abidan1 = $de['abidan1'];
	$abidan2 = $de['abidan2'];
	$abidan3 = $de['abidan3'];
	$abidan4 = $de['abidan4'];
	$abidan5 = $de['abidan5'];
	$abidan6 = $de['abidan6'];
	$abidan7 = $de['abidan7'];
	$abidan8 = $de['abidan8'];
	$abidan9 = $de['abidan9'];
	$abidan10 = $de['abidan10'];
	$abidan11 = $de['abidan11'];
	$abidan12 = $de['abidan12'];
	$abidan13 = $de['abidan13'];
	$abidan14 = $de['abidan14'];
	$abidan15 = $de['abidan15'];
	$abidan16 = $de['abidan16'];
	$abidan17 = $de['abidan17'];
	$abidan18 = $de['abidan18'];
	$abidan19 = $de['abidan19'];
	$abidan20 = $de['abidan20'];
	$abidan21 = $de['abidan21'];
	$abidan22 = $de['abidan22'];
	$abidan23 = $de['abidan23'];
	$abidan24 = $de['abidan24'];
	$abidan25 = $de['abidan25'];
	$abidan26 = $de['abidan26'];
	$abidan27 = $de['abidan27'];
	$abidan28 = $de['abidan28'];
	$abidan29 = $de['abidan29'];
	$abidan30 = $de['abidan30'];
	$abidan31 = $de['abidan31'];
	$abidan32 = $de['abidan32'];
	$abidan33 = $de['abidan33'];
	$abidan34 = $de['abidan34'];
	$abidan35 = $de['abidan35'];
	$abidan36 = $de['abidan36'];
	$abidan37 = $de['abidan37'];
	$abidan38 = $de['abidan38'];
	$abidan39 = $de['abidan39'];
	$abidan40 = $de['abidan40'];
	$abidan41 = $de['abidan41'];
	$abidan42 = $de['abidan42'];
	$abidan43 = $de['abidan43'];
	$abidan44 = $de['abidan44'];
	$abidan45 = $de['abidan45'];
	$abidan46 = $de['abidan46'];
	$abidan47 = $de['abidan47'];
	$abidan48 = $de['abidan48'];
	$abidan49 = $de['abidan49'];
	$abidan50 = $de['abidan50'];
	$abidan51 = $de['abidan51'];
	$abidan52 = $de['abidan52'];
	$abidan53 = $de['abidan53'];
	$abidan54 = $de['abidan54'];
	$abidan55 = $de['abidan55'];
	$abidan56 = $de['abidan56'];
	$abidan57 = $de['abidan57'];
	$abidan58 = $de['abidan58'];
	$abidan59 = $de['abidan59'];
	$abidan60 = $de['abidan60'];
	$abidan61 = $de['abidan61'];
	$abidan62 = $de['abidan62'];
	$abidan63 = $de['abidan63'];
	$abidan64 = $de['abidan64'];
	$abidan65 = $de['abidan65'];
	$abidan66 = $de['abidan66'];
	$abidan67 = $de['abidan67'];
	$abidan68 = $de['abidan68'];
	$abidan69 = $de['abidan69'];
	$abidan70 = $de['abidan70'];
	$abidan71 = $de['abidan71'];
	$abidan72 = $de['abidan72'];
	$abidan73 = $de['abidan73'];
	$abidan74 = $de['abidan74'];
	$abidan75 = $de['abidan75'];
	$abidan76 = $de['abidan76'];
	$abidan77 = $de['abidan77'];
	$abidan78 = $de['abidan78'];
	$abidan79 = $de['abidan79'];
	$abidan80 = $de['abidan80'];
	$abidan81 = $de['abidan81'];
	$abidan82 = $de['abidan82'];
	$abidan83 = $de['abidan83'];
	$abidan84 = $de['abidan84'];
	$abidan85 = $de['abidan85'];
	$abidan86 = $de['abidan86'];
	$abidan87 = $de['abidan87'];
	$abidan88 = $de['abidan88'];
	$abidan89 = $de['abidan89'];
	$abidan90 = $de['abidan90'];
	$abidan91 = $de['abidan91'];
	$abidan92 = $de['abidan92'];
	$abidan93 = $de['abidan93'];
	$abidan94 = $de['abidan94'];
	$abidan95 = $de['abidan95'];
	$abidan96 = $de['abidan96'];
	$abidan97 = $de['abidan97'];
	$abidan98 = $de['abidan98'];
	$abidan99 = $de['abidan99'];
	$abidan100 = $de['abidan100'];
	$abidan101 = $de['abidan101'];
	$abidan102 = $de['abidan102'];
	$abidan103 = $de['abidan103'];
	$abidan104 = $de['abidan104'];
	$abidan105 = $de['abidan105'];
	$abidan106 = $de['abidan106'];
	$abidan107 = $de['abidan107'];
	$abidan108 = $de['abidan108'];
	$abidan109 = $de['abidan109'];
	$abidan110 = $de['abidan110'];
	$abidan111 = $de['abidan111'];
	$abidan112 = $de['abidan112'];
	$abidan113 = $de['abidan113'];
	$abidan114 = $de['abidan114'];
	$abidan115 = $de['abidan115'];
	$abidan116 = $de['abidan116'];
	$abidan117 = $de['abidan117'];
	$abidan118 = $de['abidan118'];
	$abidan119 = $de['abidan119'];
	$abidan120 = $de['abidan120'];
	$abidan121 = $de['abidan121'];
	$abidan122 = $de['abidan122'];
	$abidan123 = $de['abidan123'];
	$abidan124 = $de['abidan124'];
	$abidan125 = $de['abidan125'];
	$abidan126 = $de['abidan126'];
	$abidan127 = $de['abidan127'];
	$abidan128 = $de['abidan128'];
	$abidan129 = $de['abidan129'];
	$abidan130 = $de['abidan130'];
	$abidan131 = $de['abidan131'];
	$abidan132 = $de['abidan132'];
	$abidan133 = $de['abidan133'];
	$abidan134 = $de['abidan134'];
	$abidan135 = $de['abidan135'];
	$abidan136 = $de['abidan136'];
	$abidan137 = $de['abidan137'];
	$abidan138 = $de['abidan138'];
	$abidan139 = $de['abidan139'];
	$abidan140 = $de['abidan140'];
	$abidan141 = $de['abidan141'];
	$abidan142 = $de['abidan142'];
	$abidan143 = $de['abidan143'];
	$abidan144 = $de['abidan144'];
	$abidan145 = $de['abidan145'];
	$abidan146 = $de['abidan146'];
	$abidan147 = $de['abidan147'];
	$abidan148 = $de['abidan148'];
	$abidan149 = $de['abidan149'];
	$abidan150 = $de['abidan150'];
	$abidan151 = $de['abidan151'];
	$abidan152 = $de['abidan152'];
	$abidan153 = $de['abidan153'];
	$abidan154 = $de['abidan154'];
	$abidan155 = $de['abidan155'];
	$abidan156 = $de['abidan156'];
	$abidan157 = $de['abidan157'];
	$abidan158 = $de['abidan158'];
	$abidan159 = $de['abidan159'];
	$abidan160 = $de['abidan160'];
	$abidan161 = $de['abidan161'];
	$abidan162 = $de['abidan162'];
	$abidan163 = $de['abidan163'];
	$abidan164 = $de['abidan164'];
	$abidan165 = $de['abidan165'];
	$abidan166 = $de['abidan166'];
	$abidan167 = $de['abidan167'];
	$abidan168 = $de['abidan168'];
	$abidan169 = $de['abidan169'];
	$abidan170 = $de['abidan170'];
	$abidan171 = $de['abidan171'];
	$abidan172 = $de['abidan172'];
	$abidan173 = $de['abidan173'];
	$abidan174 = $de['abidan174'];
	$abidan175 = $de['abidan175'];
	$abidan176 = $de['abidan176'];
	$abidan177 = $de['abidan177'];
	$abidan178 = $de['abidan178'];
	$abidan179 = $de['abidan179'];
	$abidan180 = $de['abidan180'];
	$abidan181 = $de['abidan181'];
	$abidan182 = $de['abidan182'];
	$abidan183 = $de['abidan183'];
	$abidan184 = $de['abidan184'];
	$abidan185 = $de['abidan185'];
	$abidan186 = $de['abidan186'];
	$abidan187 = $de['abidan187'];
	$abidan188 = $de['abidan188'];
	$abidan189 = $de['abidan189'];
	$abidan190 = $de['abidan190'];
	$abidan191 = $de['abidan191'];
	$abidan192 = $de['abidan192'];
	$abidan193 = $de['abidan193'];
	$abidan194 = $de['abidan194'];
	$abidan195 = $de['abidan195'];
	$abidan196 = $de['abidan196'];
	$abidan197 = $de['abidan197'];
	$abidan198 = $de['abidan198'];
	$abidan199 = $de['abidan199'];
	$abidan200 = $de['abidan200'];
	$abidan201 = $de['abidan201'];
	$abidan202 = $de['abidan202'];
	$abidan203 = $de['abidan203'];
	$abidan204 = $de['abidan204'];
	$abidan205 = $de['abidan205'];
	$abidan206 = $de['abidan206'];
	$abidan207 = $de['abidan207'];
	$abidan208 = $de['abidan208'];
	$abidan209 = $de['abidan209'];
	$abidan210 = $de['abidan210'];
	$abidan211 = $de['abidan211'];
	$abidan212 = $de['abidan212'];
	$abidan213 = $de['abidan213'];
	$abidan214 = $de['abidan214'];
	$abidan215 = $de['abidan215'];
	$abidan216 = $de['abidan216'];
	$abidan217 = $de['abidan217'];
	$abidan218 = $de['abidan218'];
	$abidan219 = $de['abidan219'];
	$abidan220 = $de['abidan220'];
	$abidan221 = $de['abidan221'];
	$abidan222 = $de['abidan222'];
	$abidan223 = $de['abidan223'];
	$abidan224 = $de['abidan224'];
	$abidan225 = $de['abidan225'];
	$abidan226 = $de['abidan226'];
	$abidan227 = $de['abidan227'];
	$abidan228 = $de['abidan228'];
	$abidan229 = $de['abidan229'];
	$abidan230 = $de['abidan230'];
	$abidan231 = $de['abidan231'];
	$abidan232 = $de['abidan232'];
	$abidan233 = $de['abidan233'];
	$abidan234 = $de['abidan234'];
	$abidan235 = $de['abidan235'];
	$abidan236 = $de['abidan236'];
	$abidan237 = $de['abidan237'];
	$abidan238 = $de['abidan238'];
	$abidan239 = $de['abidan239'];
	$abidan240 = $de['abidan240'];
	$abidan241 = $de['abidan241'];
	$abidan242 = $de['abidan242'];
	$abidan243 = $de['abidan243'];
	$abidan244 = $de['abidan244'];
	$abidan245 = $de['abidan245'];
	$abidan246 = $de['abidan246'];
	$abidan247 = $de['abidan247'];
	$abidan248 = $de['abidan248'];
	$abidan249 = $de['abidan249'];
	$abidan250 = $de['abidan250'];
	$abidan251 = $de['abidan251'];
	$abidan252 = $de['abidan252'];
	$abidan253 = $de['abidan253'];
	$abidan254 = $de['abidan254'];
	$abidan255 = $de['abidan255'];
	$abidan256 = $de['abidan256'];
	$abidan257 = $de['abidan257'];
	$abidan258 = $de['abidan258'];
	$abidan259 = $de['abidan259'];
	$abidan260 = $de['abidan260'];
	$abidan261 = $de['abidan261'];
	$abidan262 = $de['abidan262'];
	$abidan263 = $de['abidan263'];
	$abidan264 = $de['abidan264'];
	$abidan265 = $de['abidan265'];
	$abidan266 = $de['abidan266'];
	$abidan267 = $de['abidan267'];
	$abidan268 = $de['abidan268'];
	$abidan269 = $de['abidan269'];
	$abidan270 = $de['abidan270'];
	$abidan271 = $de['abidan271'];
	$abidan272 = $de['abidan272'];
	$abidan273 = $de['abidan273'];
	$abidan274 = $de['abidan274'];
	$abidan275 = $de['abidan275'];
	$abidan276 = $de['abidan276'];
	$abidan277 = $de['abidan277'];
	$abidan278 = $de['abidan278'];
	$abidan279 = $de['abidan279'];
	$abidan280 = $de['abidan280'];
	$abidan281 = $de['abidan281'];
	$abidan282 = $de['abidan282'];
	$abidan283 = $de['abidan283'];
	$abidan284 = $de['abidan284'];
	$abidan285 = $de['abidan285'];
	$abidan286 = $de['abidan286'];
	$abidan287 = $de['abidan287'];
	$abidan288 = $de['abidan288'];
	$abidan289 = $de['abidan289'];
	$abidan290 = $de['abidan290'];
	$abidan291 = $de['abidan291'];
	$abidan292 = $de['abidan292'];
	$abidan293 = $de['abidan293'];
	$abidan294 = $de['abidan294'];
	$abidan295 = $de['abidan295'];
	$abidan296 = $de['abidan296'];
	$abidan297 = $de['abidan297'];
	$abidan298 = $de['abidan298'];
	$abidan299 = $de['abidan299'];
	$abidan300 = $de['abidan300'];
	$abidan301 = $de['abidan301'];
	$abidan302 = $de['abidan302'];
	$abidan303 = $de['abidan303'];
	$abidan304 = $de['abidan304'];
	$abidan305 = $de['abidan305'];
	$abidan306 = $de['abidan306'];
	$abidan307 = $de['abidan307'];
	$abidan308 = $de['abidan308'];
	$abidan309 = $de['abidan309'];
	$abidan310 = $de['abidan310'];
	$abidan311 = $de['abidan311'];
	$abidan312 = $de['abidan312'];
	$abidan313 = $de['abidan313'];
	$abidan314 = $de['abidan314'];
	$abidan315 = $de['abidan315'];
	$abidan316 = $de['abidan316'];
	$abidan317 = $de['abidan317'];
	$abidan318 = $de['abidan318'];
	$abidan319 = $de['abidan319'];
	$abidan320 = $de['abidan320'];
	$ku_tinggibadan= $de['ku_tinggibadan'];
	$ku_beratbadan= $de['ku_beratbadan'];
	$jam= $de['jam'];
}

?>

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
<!-- 	<link href="css/styles.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
-->
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

	<body onload="document.myForm.td_sistolik.focus();" class="bg-light">
		<font size='2px'>	
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
					<a href='form_assesmen_bersalin_print.php?id=<?php echo $id."|".$user;?>' target='_blank' class='btn btn-info btn-sm'>
						<i class="bi bi-printer-fill"></i> Cetak
					</a>
				</div>

				<!-- Title -->
				<div class="text-center mb-4">
					<h5><i class="bi bi-window-plus"></i> ASESMEN AWAL KEBIDANAN BERSALIN</h5>
				</div>

				<!-- Input DPJP -->
				<div class="card shadow-sm mb-4">
					<div class="card-body">
						<h6 class="card-title text-primary"><i class="bi bi-person-lines-fill"></i>DIISI OLEH BIDAN</h6>
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

				<style>
					.form-container {
						display: flex;
						flex-wrap: wrap;
						gap: 2rem;
						padding: 1rem;
						background-color: #f9f9f9;
						border-radius: 10px;
						box-shadow: 0 2px 8px rgba(0,0,0,0.1);
					}
					.form-section {
						flex: 1 1 45%;
						background: white;
						padding: 1rem;
						border-radius: 8px;
						border: 1px solid #ddd;
					}
					.form-group {
						margin-bottom: 1rem;
					}
					.form-group label {
						display: block;
						margin-bottom: 0.5rem;
						font-weight: bold;
					}
					.form-group input {
						width: 100%;
						padding: 0.5rem;
						border: 1px solid #ccc;
						border-radius: 4px;
					}
					.submit-button {
						background-color: #66CDAA;
						color: white;
						padding: 0.75rem 1.5rem;
						border: none;
						border-radius: 5px;
						font-size: 1rem;
						cursor: pointer;
						margin-top: 1rem;
					}
					.submit-button:hover {
						background-color: #5ab89c;
					}
				</style>

				<div class="form-container">
					<div class="form-section">
						<div class="form-group">
							<label>Tanggal Input</label>
							<input type="text" name="tglinput" value="<?php echo $tglinput; ?>">
						</div>
						<div class="form-group">
							<label>MRS Tanggal</label>
							<input type="date" name="tglrawat" value="<?php echo $tglrawat; ?>">
						</div>
						<div class="form-group">
							<label>Jam</label>
							<input type="text" name="jam" value="<?php echo $jam; ?>" size="8">
						</div>
						<div class="form-group">
							<label>Nama Pasien</label>
							<input type="text" name="abidan1" value="<?php echo $abidan1; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Umur</label>
							<input type="text" name="abidan2" value="<?php echo $abidan2; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Alamat</label>
							<input type="text" name="abidan3" value="<?php echo $abidan3; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Bahasa</label>
							<input type="text" name="abidan4" value="<?php echo $abidan4; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Suku/Bangsa</label>
							<input type="text" name="abidan5" value="<?php echo $abidan5; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Status Perkawinan</label>
							<input type="text" name="abidan6" value="<?php echo $abidan6; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Berapa kali</label>
							<input type="text" name="abidan7" value="<?php echo $abidan7; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Lama Menikah</label>
							<input type="text" name="abidan8" value="<?php echo $abidan8; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Pekerjaan</label>
							<input type="text" name="abidan9" value="<?php echo $abidan9; ?>" size="50">
						</div>
						<button type="submit" name="simpan" class="submit-button">Simpan</button>
					</div>

					<div class="form-section">
						<div class="form-group">
							<label>No. Reg</label>
							<input type="text" name="abidan10" value="<?php echo $abidan10; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Kelas</label>
							<input type="text" name="abidan11" value="<?php echo $abidan11; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Nama Suami</label>
							<input type="text" name="abidan12" value="<?php echo $abidan12; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Umur Suami</label>
							<input type="text" name="abidan13" value="<?php echo $abidan13; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Alamat</label>
							<input type="text" name="abidan14" value="<?php echo $abidan14; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Bahasa</label>
							<input type="text" name="abidan15" value="<?php echo $abidan15; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Suku/Bangsa</label>
							<input type="text" name="abidan16" value="<?php echo $abidan16; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Status Perkawinan</label>
							<input type="text" name="abidan17" value="<?php echo $abidan17; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Suami Ke</label>
							<input type="text" name="abidan18" value="<?php echo $abidan18; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Lama Menikah</label>
							<input type="text" name="abidan19" value="<?php echo $abidan19; ?>" size="50">
						</div>
						<div class="form-group">
							<label>Pekerjaan Suami</label>
							<input type="text" name="abidan20" value="<?php echo $abidan20; ?>" size="50">
						</div>
					</div>
				</div>

				<div class="card shadow-sm">
					<div class="card-header bg-primary text-white">
						<h5 class="mb-0">Riwayat Kebidanan</h5>
					</div>
					<div class="card-body">
						<h6><b>I. LANGKAH PERTAMA</b></h6>
						<div class="mb-3">
							<label class="form-label">Keluhan Utama</label>
							<input type="text" name="abidan21" value="<?php echo $abidan21; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">Riwayat Kehamilan/Penyakit sekarang</label>
							<input type="text" name="abidan22" value="<?php echo $abidan22; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">Riwayat Penyakit /Keluarga</label>
							<input type="text" name="abidan23" value="<?php echo $abidan23; ?>" class="form-control">
						</div>

						<h6 class="mt-4"><b>Riwayat Menstruasi</b></h6>

						<div class="mb-3">
							<label class="form-label">Menarche/tahun</label>
							<input type="text" name="abidan24" value="<?php echo $abidan24; ?>" class="form-control">
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label">Teratur/tidak teratur</label>
								<input type="text" name="abidan25" value="<?php echo $abidan25; ?>" class="form-control">
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Siklus/hari</label>
								<input type="text" name="abidan26" value="<?php echo $abidan26; ?>" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="form-label">Lamanya</label>
								<input type="text" name="abidan27" value="<?php echo $abidan27; ?>" class="form-control">
							</div>
							<div class="col-md-6 mb-3">
								<label class="form-label">Banyaknya</label>
								<input type="text" name="abidan28" value="<?php echo $abidan28; ?>" class="form-control">
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label">Sifat darah</label>
							<input type="text" name="abidan29" value="<?php echo $abidan29; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">Dismenorrhoe</label>
							<input type="text" name="abidan30" value="<?php echo $abidan30; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">HPHT</label>
							<input type="text" name="abidan31" value="<?php echo $abidan31; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">HPL</label>
							<input type="text" name="abidan32" value="<?php echo $abidan32; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">ANC dimana</label>
							<input type="text" name="abidan33" value="<?php echo $abidan33; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">Berapa kali</label>
							<input type="text" name="abidan34" value="<?php echo $abidan34; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">Status Emosional</label>
							<input type="text" name="abidan35" value="<?php echo $abidan35; ?>" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">Riwayat KB (Jenis & lamanya)</label>
							<input type="text" name="abidan36" value="<?php echo $abidan36; ?>" class="form-control">
						</div>

						<div class="text-end">
							<button type="submit" name="simpan" class="btn btn-success">Simpan</button>
						</div>
					</div>
				</div>

				<div class="card shadow-sm">
					<div class="card-header bg-primary text-white">
						<h5 class="mb-0">Riwayat Kehamilan, Persalinan dan Nifas</h5>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered table-sm align-middle text-center">
								<thead class="table-light">
									<tr>
										<th rowspan="2">No</th>
										<th rowspan="2">Tgl, Bln, Th<br>(Tgl Persalinan)</th>
										<th colspan="3">Umur hamil</th>
										<th rowspan="2">Jenis Persalinan</th>
										<th colspan="4">Penolong</th>
										<th colspan="2">Anak</th>
										<th colspan="3">Keadaan anak sekarang</th>
										<th rowspan="2">Umur anak<br>sekarang</th>
										<th colspan="2">Menyusui</th>
										<th rowspan="2">KB</th>
										<th rowspan="2">Keterangan</th>
									</tr>
									<tr>
										<th>Abortus</th>
										<th>Prematur</th>
										<th>Aterm</th>
										<th>Dokter</th>
										<th>Bidan</th>
										<th>Non Nakes</th>
										<th>Tempat Persalinan</th>
										<th>JK</th>
										<th>BBL</th>
										<th>Normal</th>
										<th>Cacat</th>
										<th>Mati</th>
										<th>Ya</th>
										<th>Tidak</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td><input type="text" name="abidan37" value="<?php echo $abidan37; ?>" class="form-control form-control-sm"></td>
										<td><input type="checkbox" name="abidan38" value="YA" <?php if ($abidan38=="YA"){echo "checked";} ?>></td>
										<td><input type="checkbox" name="abidan39" value="YA" <?php if ($abidan39=="YA"){echo "checked";} ?>></td>
										<td><input type="checkbox" name="abidan40" value="YA" <?php if ($abidan40=="YA"){echo "checked";} ?>></td>
										<td><input type="text" name="abidan41" value="<?php echo $abidan41; ?>" class="form-control form-control-sm"></td>
										<td><input type="checkbox" name="" value="YA"></td>
										<td><input type="checkbox" name="abidan42" value="YA" <?php if ($abidan42=="YA"){echo "checked";} ?>></td>
										<td><input type="checkbox" name="abidan43" value="YA" <?php if ($abidan43=="YA"){echo "checked";} ?>></td>
										<td><input type="text" name="" value="" class="form-control form-control-sm"></td>
										<td><input type="text" name="abidan44" value="<?php echo $abidan44; ?>" class="form-control form-control-sm"></td>
										<td><input type="text" name="abidan45" value="<?php echo $abidan45; ?>" class="form-control form-control-sm"></td>
										<td><input type="checkbox" name="abidan46" value="YA" <?php if ($abidan46=="YA"){echo "checked";} ?>></td>
										<td><input type="checkbox" name="abidan47" value="YA" <?php if ($abidan47=="YA"){echo "checked";} ?>></td>
										<td><input type="checkbox" name="abidan48" value="YA" <?php if ($abidan48=="YA"){echo "checked";} ?>></td>
										<td><input type="text" name="" value="" class="form-control form-control-sm"></td>
										<td><input type="checkbox" name="abidan49" value="YA" <?php if ($abidan49=="YA"){echo "checked";} ?>></td>
										<td><input type="checkbox" name="abidan50" value="YA" <?php if ($abidan51=="YA"){echo "checked";} ?>></td>
										<td><input type="text" name="abidan52" value="<?php echo $abidan52; ?>" class="form-control form-control-sm"></td>
										<td><input type="text" name="" value="" class="form-control form-control-sm"></td>
									</tr>
									<tr>
										<td>2</td>
										<td><input type='text' name='abidan53' value='<?php echo $abidan53;?>'></td>
										<td><input type='checkbox' name='abidan54' value='YA' <?php if ($abidan54=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan55' value='YA' <?php if ($abidan55=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan56' value='YA' <?php if ($abidan56=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan57' value='<?php echo $abidan57;?>'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='abidan58' value='YA' <?php if ($abidan58=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan59' value='YA' <?php if ($abidan59=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='abidan60' value='<?php echo $abidan60;?>'></td>
										<td><input type='text' name='abidan61' value='<?php echo $abidan61;?>'></td>
										<td><input type='checkbox' name='abidan62' value='YA' <?php if ($abidan62=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan63' value='YA' <?php if ($abidan63=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan64' value='YA' <?php if ($abidan64=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='abidan65' value='YA' <?php if ($abidan65=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan66' value='YA' <?php if ($abidan66=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan67' value='<?php echo $abidan67;?>'></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>3</td>
										<td><input type='text' name='abidan68' value='<?php echo $abidan68;?>'></td>
										<td><input type='checkbox' name='abidan69' value='YA' <?php if ($abidan69=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan70' value='YA' <?php if ($abidan70=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan71' value='YA' <?php if ($abidan71=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan72' value='<?php echo $abidan72;?>'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='abidan73' value='YA' <?php if ($abidan73=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan74' value='YA' <?php if ($abidan74=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='abidan75' value='<?php echo $abidan75;?>'></td>
										<td><input type='text' name='abidan76' value='<?php echo $abidan76;?>'></td>
										<td><input type='checkbox' name='abidan77' value='YA' <?php if ($abidan77=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan78' value='YA' <?php if ($abidan78=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan79' value='YA' <?php if ($abidan79=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='abidan80' value='YA' <?php if ($abidan80=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan81' value='YA' <?php if ($abidan81=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan82' value='<?php echo $abidan82;?>'></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>4</td>
										<td><input type='text' name='abidan83' value='<?php echo $abidan83;?>'></td>
										<td><input type='checkbox' name='abidan84' value='YA' <?php if ($abidan84=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan85' value='YA' <?php if ($abidan85=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan86' value='YA' <?php if ($abidan86=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan87' value='<?php echo $abidan87;?>'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='abidan88' value='YA' <?php if ($abidan88=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan89' value='YA' <?php if ($abidan89=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='abidan90' value='<?php echo $abidan90;?>'></td>
										<td><input type='text' name='abidan91' value='<?php echo $abidan91;?>'></td>
										<td><input type='checkbox' name='abidan92' value='YA' <?php if ($abidan92=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan93' value='YA' <?php if ($abidan93=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan94' value='YA' <?php if ($abidan94=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='abidan95' value='YA' <?php if ($abidan95=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan96' value='YA' <?php if ($abidan96=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan97' value='<?php echo $abidan97;?>'></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>5</td>
										<td><input type='text' name='abidan98' value='<?php echo $abidan98;?>'></td>
										<td><input type='checkbox' name='abidan99' value='YA' <?php if ($abidan99=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan100' value='YA' <?php if ($abidan100=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan101' value='YA' <?php if ($abidan101=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan102' value='<?php echo $abidan102;?>'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='abidan103' value='YA' <?php if ($abidan103=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan104' value='YA' <?php if ($abidan104=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='abidan105' value='<?php echo $abidan105;?>'></td>
										<td><input type='text' name='abidan106' value='<?php echo $abidan106;?>'></td>
										<td><input type='checkbox' name='abidan107' value='YA' <?php if ($abidan107=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan108' value='YA' <?php if ($abidan108=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan109' value='YA' <?php if ($abidan109=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='abidan110' value='YA' <?php if ($abidan110=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan111' value='YA' <?php if ($abidan111=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan112' value='<?php echo $abidan112;?>'></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>6</td>
										<td><input type='text' name='abidan113' value='<?php echo $abidan113;?>'></td>
										<td><input type='checkbox' name='abidan114' value='YA' <?php if ($abidan114=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan115' value='YA' <?php if ($abidan115=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan116' value='YA' <?php if ($abidan116=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan117' value='<?php echo $abidan117;?>'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='abidan118' value='YA' <?php if ($abidan118=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan119' value='YA' <?php if ($abidan119=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='abidan120' value='<?php echo $abidan120;?>'></td>
										<td><input type='text' name='abidan121' value='<?php echo $abidan121;?>'></td>
										<td><input type='checkbox' name='abidan122' value='YA' <?php if ($abidan122=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan123' value='YA' <?php if ($abidan123=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan124' value='YA' <?php if ($abidan124=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='abidan125' value='YA' <?php if ($abidan125=="YA"){echo "checked";}?> ></td>
										<td><input type='checkbox' name='abidan126' value='YA' <?php if ($abidan126=="YA"){echo "checked";}?> ></td>
										<td><input type='text' name='abidan127' value='<?php echo $abidan127;?>'></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>7</td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>8</td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>9</td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
									</tr>
									<tr>
										<td>10</td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='checkbox' name='' value='YA'></td>
										<td><input type='text' name='' value=''></td>
										<td><input type='text' name='' value=''></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="text-end">
							<button type="submit" name="simpan" class="btn btn-success">Simpan</button>
						</div>

					</div>

				</div>

				<div class="card shadow-sm mb-3">
					<div class="card-header bg-primary text-white">
						<h5 class="mb-0">Riwayat Ginekologi</h5>
					</div>
					<div class="card-body">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="abidan128" value="Tidak Ada" <?php if ($abidan128=="Tidak Ada"){echo "checked";}?>>
							<label class="form-check-label">Tidak Ada</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="abidan128" value="Ada" <?php if ($abidan128=="YA"){echo "checked";}?>>
							<label class="form-check-label">Ada</label>
						</div>
						<?php 
						$ginekologi = [
							129 => "Infertilitas", 130 => "Infeksi Virus", 131 => "PMS", 
							132 => "Cervisitis Kronis", 133 => "Endometriosis", 134 => "Myoma",
							135 => "Polyp Cervik", 136 => "Kanker", 137 => "Lainnya"
						];
						foreach ($ginekologi as $key => $label) {
							?>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="abidan<?php echo $key;?>" value="YA" <?php if (${"abidan".$key}=="YA"){echo "checked";}?>>
								<label class="form-check-label"><?php echo $label;?></label>
							</div>
						<?php } ?>
					</div>
				</div>

				<table>
					<br><br>
					<table border='1'>
						<tr>
							<td style="border: 1px solid;">Riwayat Ginecologi</td>
							<td style="border: 1px solid;">
								<input type='checkbox' name='abidan128' value='Tidak Ada' <?php if ($abidan128=="Tidak Ada"){echo "checked";}?> >Tidak Ada                
								<input type='checkbox' name='abidan128' value='Ada' <?php if ($abidan128=="YA"){echo "checked";}?> >Ada              
								<input type='checkbox' name='abidan129' value='YA' <?php if ($abidan129=="YA"){echo "checked";}?> >Infertilitas          
								<input type='checkbox' name='abidan130' value='YA' <?php if ($abidan130=="YA"){echo "checked";}?> >Infeksi Virus           
								<input type='checkbox' name='abidan131' value='YA' <?php if ($abidan131=="YA"){echo "checked";}?> >PMS                 
								<input type='checkbox' name='abidan132' value='YA' <?php if ($abidan132=="YA"){echo "checked";}?> >Cervisitis Kronis              
								<input type='checkbox' name='abidan133' value='YA' <?php if ($abidan133=="YA"){echo "checked";}?> >Endometriosis              
								<input type='checkbox' name='abidan134' value='YA' <?php if ($abidan134=="YA"){echo "checked";}?> >Myoma                    
								<input type='checkbox' name='abidan135' value='YA' <?php if ($abidan135=="YA"){echo "checked";}?> >Polyp Cervik                            
								<input type='checkbox' name='abidan136' value='YA' <?php if ($abidan136=="YA"){echo "checked";}?> >Kanker                    
								<input type='checkbox' name='abidan137' value='YA' <?php if ($abidan137=="YA"){echo "checked";}?> >Lainnya 
							</td>
						</tr>	
						<tr>
							<td  style="border: 1px solid;">Riwayat alergi</td>
							<td  style="border: 1px solid;">
								<input type='checkbox' name='abidan138' value='tidak' <?php if ($abidan138=="tidak"){echo "checked";}?> >tidak         
								<input type='checkbox' name='abidan138' value='Ya' <?php if ($abidan138=="Ya"){echo "checked";}?> >Ya, Alergi      
								<br>
								<input type='checkbox' name='abidan139' value='YA' <?php if ($abidan139=="YA"){echo "checked";}?> > 
								Obat : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='abidan140' value='<?php echo $abidan140;?>' placeholder="Jenis/nama Obat"> 
								Reaksi : <input type='text' name='abidan141' value='<?php echo $abidan141;?>'>
								<br>
								<input type='checkbox' name='abidan142' value='YA' <?php if ($abidan142=="YA"){echo "checked";}?> >
								Makanan : &nbsp;&nbsp;<input type='text' name='abidan143' value='<?php echo $abidan143;?>'>
								Reaksi : <input type='text' name='abidan144' value='<?php echo $abidan144;?>'>
								<br>
								<input type='checkbox' name='abidan145' value='YA' <?php if ($abidan145=="YA"){echo "checked";}?> >
								Lain-lain : &nbsp;&nbsp;&nbsp;<input type='text' name='abidan146' value='<?php echo $abidan146;?>'>
								Reaksi : <input type='text' name='abidan147' value='<?php echo $abidan147;?>'>
							</td>
						</tr>
						<tr>
							<td  style="border: 1px solid;">Riwayat transfusi darah</td>
							<td  style="border: 1px solid;">
								<input type='checkbox' name='abidan148' value='Tidak' <?php if ($abidan148=="Tidak"){echo "checked";}?> >Tidak         
								<input type='checkbox' name='abidan148' value='Ya' <?php if ($abidan148=="Ya"){echo "checked";}?> >Ya, reaksi alergi :      
								<input type='checkbox' name='abidan149' value='Tidak' <?php if ($abidan149=="Tidak"){echo "checked";}?> >Tidak         
								<input type='checkbox' name='abidan150' value='Ya' <?php if ($abidan150=="Ya"){echo "checked";}?> >Ya, <input type='text' name='abidan151' value='<?php echo $abidan151;?>'>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Riwayat Merokok</td>
							<td style="border: 1px solid;">
								<input type='checkbox' name='abidan152' value='Tidak' <?php if ($abidan152=="Tidak"){echo "checked";}?> >Tidak         
								<input type='checkbox' name='abidan152' value='Ya' <?php if ($abidan152=="Ya"){echo "checked";}?> >Ya, 
								Jumlah : <input type='text' name='abidan153' value='<?php echo $abidan153;?>'> batang/hari
								<input type='checkbox' name='abidan154' value='YA' <?php if ($abidan154=="YA"){echo "checked";}?> >Lama <input type='text' name='abidan155' value='<?php echo $abidan155;?>'> tahun
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Riwayat minum minuman keras</td>
							<td style="border: 1px solid;">
								<input type='checkbox' name='abidan156' value='Tidak' <?php if ($abidan156=="Tidak"){echo "checked";}?> >Tidak         
								<input type='checkbox' name='abidan156' value='Ya' <?php if ($abidan156=="Ya"){echo "checked";}?> >Ya,
								Jenis     : &nbsp;&nbsp;&nbsp;&nbsp;<input type='text' name='abidan157' value='<?php echo $abidan157;?>'>
								jumlah <input type='text' name='abidan158' value='<?php echo $abidan158;?>'>
							</td>
						</tr>
					</table>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
					<br><br>
				</td>
			</tr>
			<tr valign="top">
				<td>
					<table border='0' width="80%">
						<tr valign="top">
							<td style="border: 0px solid;">
								<b>Penilaian Nyeri</b>
								<br>
								<?php 
								if(empty($abidan158)){
												// $abidan158="Tidak";
												// $abidan161="Tidak";
												// $abidan162="Akut : < 3 bulan";
												// $abidan163="seperti kram";
												// $abidan164="1-2 jam";
												// $abidan165="Kompres hangat/dingin";
								}
								?>

								1. Skala Nyeri <br>
								Nyeri :        
								<input type='radio' name='abidan158' value='Tidak' <?php if ($abidan158=="Tidak"){echo "checked";}?> >Tidak              
								<input type='radio' name='abidan158' value='Ya' <?php if ($abidan158=="Ya"){echo "checked";}?> >Ya,    
								Lokasi :<input type='text' name='abidan159' value='<?php echo $abidan159;?>' size='50'><br>
								Sifat :        
								<input type='radio' name='abidan160' value='Akut' <?php if ($abidan1=="Akut"){echo "checked";}?> >Akut                
								<input type='radio' name='abidan160' value='Kronis' <?php if ($abidan1=="Kronis"){echo "checked";}?> >Kronis     
								<br>
								<br>
								2. Apakah Nyeri Berpindah dari tempat satu ke tempat lain?<br>
								<input type='radio' name='abidan161' value='Tidak' <?php if ($abidan161=="Tidak"){echo "checked";}?> >Tidak          
								<input type='radio' name='abidan161' value='Ya' <?php if ($abidan161=="Ya"){echo "checked";}?> >Ya<br><br>
								3. Berapa lama Nyeri ?<br>
								<input type='radio' name='abidan162' value='Akut : < 3 bulan' <?php if ($abidan162=="Akut : < 3 bulan"){echo "checked";}?> >Akut : < 3 bulan           
								<input type='radio' name='abidan162' value='Kronik : > 3 bulan' <?php if ($abidan162=="Kronik : > 3 bulan"){echo "checked";}?> >Kronik : > 3 bulan<br><br>
								4. Gambaran rasa nyeri : <br>
								<input type='radio' name='abidan163' value='Nyeri Tumpul' <?php if ($abidan163=="Nyeri Tumpul"){echo "checked";}?> >Nyeri Tumpul      
								<input type='radio' name='abidan163' value='Seperti di tarik' <?php if ($abidan163=="Seperti di tarik"){echo "checked";}?> >Seperti di tarik      
								<input type='radio' name='abidan163' value='seperti dibakar' <?php if ($abidan163=="seperti dibakar"){echo "checked";}?> >seperti dibakar
								<input type='radio' name='abidan163' value='seperti kram' <?php if ($abidan163=="seperti kram"){echo "checked";}?> >seperti kram       
								<input type='radio' name='abidan163' value='seperti ditusuk' <?php if ($abidan163=="seperti ditusuk"){echo "checked";}?> >seperti ditusuk     
								<input type='radio' name='abidan163' value='seperti berdenyut' <?php if ($abidan163=="seperti berdenyut"){echo "checked";}?> >seperti berdenyut
								<input type='radio' name='abidan163' value='seperti dipukul' <?php if ($abidan163=="seperti dipukul"){echo "checked";}?> >
								seperti dipukul    
								<input type='radio' name='abidan163' value='YA' <?php if ($abidan163=="YA"){echo "checked";}?> >seperti ditikam  <br><br>  
								5. seberapa sering anda mengalami nyeri ?<br>
								setiap :    <input type='radio' name='abidan164' value='1-2 jam' <?php if ($abidan164=="1-2 jam"){echo "checked";}?> >1-2 jam        
								<input type='radio' name='abidan164' value='3-4 jam' <?php if ($abidan164=="3-4 jam"){echo "checked";}?> >3-4 jam         
								<input type='radio' name='abidan164' value='4 jam' <?php if ($abidan164=="4 jam"){echo "checked";}?> >> 4 jam
								Selama :  <input type='radio' name='abidan164' value='< 30 menit' <?php if ($abidan164=="< 30 menit"){echo "checked";}?> >< 30 menit   
								<input type='radio' name='abidan164' value='> 30 menit' <?php if ($abidan164=="> 30 menit"){echo "checked";}?> >> 30 menit<br><br>
								6.  Apa yang membuat nyeri berkurang atau bertambah parah?<br>
								<input type='checkbox' name='abidan165' value='Kompres hangat/dingin' <?php if ($abidan165=="Kompres hangat/dingin"){echo "checked";}?> >Kompres hangat/dingin      
								<input type='checkbox' name='abidan165' value='Istirahat' <?php if ($abidan165=="Istirahat"){echo "checked";}?> >Istirahat
								<input type='checkbox' name='abidan165' value='Minum Obat' <?php if ($abidan165=="Minum Obat"){echo "checked";}?> >Minum Obat                      
								<input type='checkbox' name='abidan165' value='Berubah Posisi' <?php if ($abidan165=="Berubah Posisi"){echo "checked";}?> >Berubah Posisi


							</td>
							<td style="border: 0px solid;">											
								<b>Kebutuhan Komunikasi dan Pengajaran</b>
								<br><br>
								<?php 
								if(empty($abidan166)){
												// $abidan166="Normal";
												// $abidan168="YA";
												// $abidan171="tidak";
												// $abidan173="Tidak";
												// $abidan175="Tidak ditemukan hambatan belajar";
												// $abidan176="Menulis";
												// $abidan177="Pengobatan/ Tindakan";
								}
								?>
								<table>
									<tr>
										<td>Pemeriksaan fisikbicara</td>
										<td>
											<input type='checkbox' name='abidan166' value='Normal' <?php if ($abidan166=="Normal"){echo "checked";}?> >Normal       
											<input type='checkbox' name='abidan166' value='Serangan awal gangguan bicara' <?php if ($abidan166=="Serangan awal gangguan bicara"){echo "checked";}?> >Serangan awal gangguan bicara, kapan:<input type='text' name='abidan167' value='<?php echo $abidan167;?>'>
										</td>
									</tr>
									<tr>
										<td>Bahasa sehari-hari</td>
										<td>
											<input type='checkbox' name='abidan168' value='YA' <?php if ($abidan168=="YA"){echo "checked";}?> >Indonesia;        
											<input type='checkbox' name='abidan169' value='YA' <?php if ($abidan169=="YA"){echo "checked";}?> >aktif      
											<input type='checkbox' name='abidan169' value='YA' <?php if ($abidan169=="YA"){echo "checked";}?> >pasif
											<input type='checkbox' name='' value='YA'>Bahasa Daerah, jelaskan <input type='text' name='abidan170' value='<?php echo $abidan170;?>'>
										</td>
									</tr>
									<tr>
										<td>Perlu Penerjemah</td>
										<td>
											<input type='checkbox' name='abidan171' value='tidak' <?php if ($abidan171=="tidak"){echo "checked";}?> >tidak       
											<input type='checkbox' name='abidan171' value='YA' <?php if ($abidan171=="YA"){echo "checked";}?> >ya,  Bahasa <input type='text' name='abidan172' value='<?php echo $abidan172;?>'>
										</td>
									</tr>
									<tr>
										<td>Bahasa Isyarat</td>
										<td>
											<input type='checkbox' name='abidan173' value='YA' <?php if ($abidan173=="Tidak"){echo "checked";}?> >Tidak        <input type='checkbox' name='abidan174' value='YA' <?php if ($abidan174=="YA"){echo "checked";}?> >Ya
										</td>
									</tr>
									<tr>
										<td>Hambatan belajar</td>
										<td><input type='checkbox' name='abidan175' value='Tidak ditemukan hambatan belajar' <?php if ($abidan175=="Tidak ditemukan hambatan belajar"){echo "checked";}?> >Tidak ditemukan hambatan belajar
											<br>
											<input type='checkbox' name='abidan175' value='Bahasa' <?php if ($abidan175=="Bahasa"){echo "checked";}?> >Bahasa                    
											<input type='checkbox' name='abidan175' value='Pendengaran' <?php if ($abidan175=="Pendengaran"){echo "checked";}?> >Pendengaran
											<input type='checkbox' name='abidan175' value='Emosi' <?php if ($abidan175=="Emosi"){echo "checked";}?> >Emosi                     
											<input type='checkbox' name='abidan175' value='Masalah penglihatan' <?php if ($abidan175=="Masalah penglihatan"){echo "checked";}?> >Masalah penglihatan          
											<input type='checkbox' name='abidan175' value='Hilang memori' <?php if ($abidan175=="Hilang memori"){echo "checked";}?> >Hilang memori          
											<input type='checkbox' name='abidan175' value='Kesulitan bicara' <?php if ($abidan175=="Kesulitan bicara"){echo "checked";}?> >Kesulitan bicara    
											<br>       
											<input type='checkbox' name='abidan175' value='diskusi' <?php if ($abidan175=="diskusi"){echo "checked";}?> >diskusi                     
											<input type='checkbox' name='abidan1' value='Motivasi burukA' <?php if ($abidan175=="Motivasi buruk"){echo "checked";}?> >Motivasi buruk      
											<input type='checkbox' name='abidan175' value='Cemas' <?php if ($abidan175=="Cemas"){echo "checked";}?> >Cemas                    
											<input type='checkbox' name='abidan175' value='Tidak ada partisipasi dari caregiver' <?php if ($abidan175=="Tidak ada partisipasi dari caregiver"){echo "checked";}?> >
											Tidak ada partisipasi dari caregiver
											<input type='checkbox' name='abidan175' value='Kognitif' <?php if ($abidan1=="Kognitif"){echo "checked";}?> >Kognitif                  
											<input type='checkbox' name='abidan1' value='Secara fisiologi tidak mampu belajar' <?php if ($abidan175=="Secara fisiologi tidak mampu belajar"){echo "checked";}?> >
											Secara fisiologi tidak mampu belajar

										</td>
									</tr>
									<tr>
										<td>Cara belajar yang disukai</td>
										<td>
											<input type='checkbox' name='abidan176' value='Menulis' <?php if ($abidan176=="Menulis"){echo "checked";}?> >Menulis                        
											<input type='checkbox' name='abidan176' value='Audio – Visual / Gambar' <?php if ($abidan1=="Audio – Visual / Gambar"){echo "checked";}?> >Audio – Visual / Gambar
											<input type='checkbox' name='abidan176' value='Membaca' <?php if ($abidan176=="Membaca"){echo "checked";}?> >Membaca                     
											<input type='checkbox' name='abidan176' value='Demonstrasi' <?php if ($abidan176=="Demonstrasi"){echo "checked";}?> >Demonstrasi
											<br>
											<input type='checkbox' name='abidan176' value='Mendengar' <?php if ($abidan176=="Mendengar"){echo "checked";}?> >Mendengar
										</td>
									</tr>
									<tr>
										<td>Potensial  Kebutuhan  Pembelajaran</td>
										<td>														
											<input type='checkbox' name='abidan177' value='Proses penyakit' <?php if ($abidan177=="Proses penyakit"){echo "checked";}?> >
											Proses penyakit             
											<input type='checkbox' name='abidan177' value='Pengobatan/ Tindakan' <?php if ($abidan177=="Pengobatan/ Tindakan"){echo "checked";}?> >
											Pengobatan/ Tindakan         
											<input type='checkbox' name='abidan177' value='Terapi' <?php if ($abidan177=="Terapi"){echo "checked";}?> >Terapi                         
											<input type='checkbox' name='abidan177' value='Nutrisi' <?php if ($abidan177=="Nutrisi"){echo "checked";}?> >
											Nutrisi            
											<input type='checkbox' name='abidan177' value='Lain-lain, Jelaskan' <?php if ($abidan177=="Lain-lain, Jelaskan"){echo "checked";}?> >
											Lain-lain, Jelaskan <input type='text' name='abidan178' value='<?php echo $abidan178;?>'>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
					<br><br>
				</td>
			</tr>
			<tr>
				<td>
					<h5>1. Nutrisi</h5>
					Lakukan skrining nutrisi dengan Malnutrition Screening Tools (MST)<br>
					<table>
						<tr>
							<td style="border: 1px solid;">No</td>
							<td style="border: 1px solid;">Uraian</td>
							<td style="border: 1px solid;">Skor</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir?</td>
							<td style="border: 1px solid;"></td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">a. Tidak<input type='checkbox' name='abidan179' value='Tidak' <?php if ($abidan179=="Tidak"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan179=='Tidak'){
									echo $ku_nutrisi1_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">b. Tidak yakin<input type='checkbox' name='abidan180' value='Tidak yakin' <?php if ($abidan180=="Tidak yakin"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan180=='Tidak yakin'){
									echo $ku_nutrisi2_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">c. Ya, ada penurunan BB sebanyak:</td>
							<td style="border: 1px solid;"></td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">1-5 kg<input type='checkbox' name='abidan181' value='1-5 kg' <?php if ($abidan181=="1-5 kg"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan181){
									echo $ku_nutrisi3_skor='1';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">6-10 kg<input type='checkbox' name='abidan182' value='6-10 kg' <?php if ($abidan182=="6-10 kg"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan182){
									echo $ku_nutrisi4_skor='2';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">11-15 kg<input type='checkbox' name='abidan183' value='11-15 kg' <?php if ($abidan183=="11-15 kg"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan183){
									echo $ku_nutrisi5_skor='3';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">> 15 kg<input type='checkbox' name='abidan184' value='> 15 kg' <?php if ($abidan184=="> 15 kg"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan184){
									echo $ku_nutrisi6_skor='4';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">Tidak tahu berapa kg penurunannya<input type='checkbox' name='abidan185' value='Tidak tahu berapa kg penurunannya' <?php if ($abidan185=="Tidak tahu berapa kg penurunannya"){echo "checked";}?>>
							</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan185){
									echo $ku_nutrisi7_skor='2';
								}
								?>	
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">Apakah asupan makan pasien berkurang karena penurunan nafsu makan/kesulitan menerima makanan?</td>
							<td style="border: 1px solid;"></td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">a. Tidak<input type='checkbox' name='abidan186' value='Tidak' <?php if ($abidan186=="Tidak"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan186){
									echo $ku_nutrisi8_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">b. Ya<input type='checkbox' name='abidan187' value='Ya' <?php if ($abidan187=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan187){
									echo $ku_nutrisi9_skor='1';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;">Total Skor

							</td>
							<td style="border: 1px solid;">
								<h1>
									<?php
									echo $ku_nutrisi9_skor_total = $ku_nutrisi1_skor+$ku_nutrisi2_skor+$ku_nutrisi3_skor+$ku_nutrisi4_skor+$ku_nutrisi5_skor+$ku_nutrisi6_skor+$ku_nutrisi7_skor+$ku_nutrisi8_skor+$ku_nutrisi9_skor;
									?>
								</h1>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">Pasien dengan diagnosa khusus : (lingkari diagnosa yang sesuai pasien)<br>
								fraktur tulang panggul, sirosis hati, PPOK, Hemodialisis, diabetes, kanker, bedah digestive,<br> 
								stoke, pneumonia berat, cedera kepala, transplantasi, luka bakar, pasien kritis di ICU/HCU, usia lanjut, psikiatri, <br>
							mendapat kemoterapi, imunitas rendah/HIV-AIDS, penyakit kronis lain.</td>
							<td style="border: 1px solid;"><input type='text' name='abidan188' value='<?php echo $abidan188;?>'> </td>
						</tr>
					</table>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
					<br><br>
					Bila skor ≥ 2 dan/atau pasien dengan diagnosis/kondisi khusus dilakukan pengkajian lanjut oleh nutrisionis/dietisien<br>
					<br><br><br>
					<h5>2. Eliminasi dan pelepasan</h5>
					<?php 
					if (empty($abidan189)){
								// $abidan189="Tidak";
								// $abidan190="Tidak";
								// $abidan191="Normal";
								// $abidan197="Tidak";
					}

					?>
					BAK : 
					Frekuensi <input type='text' name='asanak93' value='<?php echo $asanak93;?>'> x/hari  
					Warna : <input type='text' name='asanak94' value='<?php echo $asanak94;?>'> <br>
					Gangguan : 
					<input type='radio' name='abidan189' value='Tidak' <?php if ($abidan189=="Tidak"){echo "checked";}?>>Tidak
					<input type='radio' name='abidan189' value='Ya' <?php if ($abidan189=="Ya"){echo "checked";}?>>Ya
					<input type='checkbox' name='abidan189' value='Retensi' <?php if ($abidan189=="Retensi"){echo "checked";}?>>Retensi
					<input type='checkbox' name='abidan189' value='Inkontinensia' <?php if ($abidan189=="Inkontinensia"){echo "checked";}?>>Inkontinensia
					<input type='checkbox' name='abidan189' value='Anuri' <?php if ($abidan189=="Anuri"){echo "checked";}?>>Anuri
					<input type='checkbox' name='abidan189' value='Oliguri' <?php if ($abidan189=="Oliguri"){echo "checked";}?>>Oliguri
					<input type='checkbox' name='abidan189' value='Hematuri' <?php if ($abidan189=="Hematuri"){echo "checked";}?>>Hematuri
					<input type='checkbox' name='abidan189' value='Lain-lain' <?php if ($abidan189=="Lain-lain"){echo "checked";}?>>Lain-lain<br>
					Penggunaan alat medis : 
					<input type='checkbox' name='abidan190' value='Tidak' <?php if ($abidan190=="Tidak"){echo "checked";}?>>Tidak   
					<input type='checkbox' name='abidan190' value='kateter' <?php if ($abidan190=="kateter"){echo "checked";}?>>kateter  
					<input type='checkbox' name='abidan190' value='Lain-lain' <?php if ($abidan190=="Lain-lain"){echo "checked";}?>>Lain-lain :   
					Tanggal pemasangan : <input type='text' name='abidan191' value='<?php echo $abidan191;?>'><br>
					BAB : 
					<input type='checkbox' name='abidan191' value='Normal' <?php if ($abidan191=="Normal"){echo "checked";}?>>Normal
					<input type='checkbox' name='abidan192' value='Konstipasi' <?php if ($abidan192=="Konstipasi"){echo "checked";}?>>Konstipasi 
					<input type='checkbox' name='abidan193' value='Diare' <?php if ($abidan193=="Diare"){echo "checked";}?>>Diare : 
					Frekuensi  : <input type='text' name='abidan194' value='<?php echo $abidan194;?>'>x/hari,  
					Konsistensi : <input type='text' name='abidan195' value='<?php echo $abidan195;?>'>
					Warna :<input type='text' name='abidan196' value='<?php echo $abidan196;?>'><br>
					Penggunaan alat medis : 
					<input type='checkbox' name='abidan197' value='Tidak' <?php if ($abidan197=="Tidak"){echo "checked";}?>>Tidak
					<input type='checkbox' name='abidan197' value='Kolostomi' <?php if ($abidan197=="Kolostomi"){echo "checked";}?>>Kolostomi
					<input type='checkbox' name='abidan197' value='Lain-lain' <?php if ($abidan197=="Lain-lain"){echo "checked";}?>>Lain-lain<br>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"><br><br>

					<?php 
					if (empty($abidan198)){
								// $abidan198="tidak ada keluhan";
								// $abidan200="Mandiri";
					}

					?>
					<br>
					<h5>3 Aktifitas dan Istirahat</h5>
					<input type='radio' name='abidan198' value='Tidur/istirahat' <?php if ($abidan198=="Tidur/istirahat"){echo "checked";}?> >Tidur/istirahat
					<input type='radio' name='abidan198' value='tidak ada keluhan' <?php if ($abidan198=="tidak ada keluhan"){echo "checked";}?> >tidak ada keluhan 
					<input type='text' name='abidan199' value='<?php echo $abidan199;?>'>
					<br>
					Aktivitas/latihan dan perawatan diri :
					<input type='radio' name='abidan200' value='Mandiri' <?php if ($abidan200=="Mandiri"){echo "checked";}?> >Mandiri        
					<input type='radio' name='abidan200' value='Perlu Pengawasan' <?php if ($abidan200=="Perlu Pengawasan"){echo "checked";}?> >Perlu Pengawasan       
					<input type='radio' name='abidan200' value='Bantuan sebagian' <?php if ($abidan200=="Bantuan sebagian"){echo "checked";}?> >Bantuan sebagian        
					<input type='radio' name='abidan200' value='Bantuan Total' <?php if ($abidan200=="Bantuan Total"){echo "checked";}?> >Bantuan Total
					<br>
					Alat Bantu :        
					<input type='radio' name='abidan201' value='Tidak' <?php if ($abidan201=="Tidak"){echo "checked";}?> >Tidak         
					<input type='radio' name='abidan201' value='YA' <?php if ($abidan201=="YA"){echo "checked";}?> >Ya :
					<input type='text' name='abidan202' value='<?php echo $abidan202;?>'>




				</td>
			</tr>	
			<tr>
				<td><br>
					<h5>B. Proteksi/keselamatan</h5>
					1. Risiko Jatuh<br>
					(dewasa dengan skala Morse)<br>
					(implementasikan tindakan pencegahan risiko jatuh sesuai dengan tingkat risiko jatuh pasien)
					<br> 
					<table>
						<tr>
							<td style="border: 1px solid;">Faktor Risiko</td>
							<td style="border: 1px solid;">skala</td>
							<td style="border: 1px solid;">poin</td>
							<td style="border: 1px solid;">Skor pasien</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Riwayat jatuh</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan202' value='YA' <?php if ($abidan202=="YA"){echo "checked";}?>>Ya</td>
							<td style="border: 1px solid;">25</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan202){
									echo $tjatuh1_skor='25';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan203' value='YA' <?php if ($abidan203=="YA"){echo "checked";}?>>Tidak</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan203){
									echo $tjatuh2_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Diagnosis skunder (≥diagnosis medis)</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan204' value='YA' <?php if ($abidan204=="YA"){echo "checked";}?>>Ya</td>
							<td style="border: 1px solid;">15</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan204){
									echo $tjatuh3_skor='15';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan205' value='YA' <?php if ($abidan205=="YA"){echo "checked";}?>>Tidak</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan205){
									echo $tjatuh4_skor='0';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Alat bantu</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan206' value='YA' <?php if ($abidan206=="YA"){echo "checked";}?>>Berpegangan pada perabot, kursi roda</td>
							<td style="border: 1px solid;">30</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan206){
									echo $tjatuh5_skor='30';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan207' value='YA' <?php if ($abidan207=="YA"){echo "checked";}?>>Tongkat/walker</td>
							<td style="border: 1px solid;">15</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan207){
									echo $tjatuh6_skor='15';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan208' value='YA' <?php if ($abidan208=="YA"){echo "checked";}?>>Tidak ada/perawat/tirah baring</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan208){
									echo $tjatuh7_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Terpasang infus/terapi intravena </td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan209' value='YA' <?php if ($abidan209=="YA"){echo "checked";}?>>Ya</td>
							<td style="border: 1px solid;">20</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan209){
									echo $tjatuh8_skor='20';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan210' value='YA' <?php if ($abidan210=="YA"){echo "checked";}?>>Tidak</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan210){
									echo $tjatuh9_skor='0';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Gaya berjalan</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan211' value='YA' <?php if ($abidan211=="YA"){echo "checked";}?>>Kerusakan</td>
							<td style="border: 1px solid;">20</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan211){
									echo $tjatuh10_skor='20';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan212' value='YA' <?php if ($abidan212=="YA"){echo "checked";}?>>Kelemahan</td>
							<td style="border: 1px solid;">10</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan212){
									echo $tjatuh11_skor='10';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan213' value='YA' <?php if ($abidan213=="YA"){echo "checked";}?>>Normal /tirah baring/imobilisasi</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan213){
									echo $tjatuh12_skor='0';
								}
								?>
							</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">Status mental</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan214' value='YA' <?php if ($abidan214=="YA"){echo "checked";}?>>Tidak konsisten dengan perintah</td>
							<td style="border: 1px solid;">15</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan214){
									echo $tjatuh13_skor='15';
								}
								?>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;"></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan215' value='YA' <?php if ($abidan215=="YA"){echo "checked";}?>>Sadar akan kemampuan diri sendiri</td>
							<td style="border: 1px solid;">0</td>
							<td style="border: 1px solid;">
								<?php 
								if($abidan215){
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
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"><br><br>&nbsp;

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
			<tr>
				<td>
					<?php 
					if (empty($abidan216)){
								// $abidan216="Kooperatif";
								// $abidan217="Sarjana";
								// $abidan218="Wiraswasta";
								// $abidan219="suami/istri";
								// $abidan220="Pembayaran Pribadi/Perorangan";
					}

					?>

					C. Psikososial dan Ekonomi<br>
					Keadaan Psikologis         :     
					<input type='checkbox' name='abidan216' value='Kooperatif' <?php if ($abidan216=="Kooperatif"){echo "checked";}?> >Kooperatif         
					<input type='checkbox' name='abidan216' value='Sedih' <?php if ($abidan216=="Sedih"){echo "checked";}?> >Sedih                       
					<input type='checkbox' name='abidan216' value='Marah' <?php if ($abidan216=="Marah"){echo "checked";}?> >Marah               
					<input type='checkbox' name='abidan216' value='Disorientasi' <?php if ($abidan216=="Disorientasi"){echo "checked";}?> >Disorientasi                  
					<input type='checkbox' name='abidan216' value='Orang' <?php if ($abidan216=="Orang"){echo "checked";}?> >Orang                  
					<input type='checkbox' name='abidan216' value='Tempat' <?php if ($abidan216=="Tempat"){echo "checked";}?> >
					Tempat
					<input type='checkbox' name='abidan216' value='Agitasi' <?php if ($abidan216=="Agitasi"){echo "checked";}?> >Agitasi             
					<input type='checkbox' name='abidan216' value='Cemas' <?php if ($abidan216=="Cemas"){echo "checked";}?> >Cemas                     
					<input type='checkbox' name='abidan216' value='Gelisah' <?php if ($abidan216=="Gelisah"){echo "checked";}?> >
					Gelisah         
					<br>
					Tingkat Pendidikan         :      
					<input type='checkbox' name='abidan217' value='Belum Sekolah' <?php if ($abidan217=="Belum Sekolah"){echo "checked";}?> >Belum Sekolah       
					<input type='checkbox' name='abidan217' value='SD' <?php if ($abidan217=="SD"){echo "checked";}?> >SD                      
					<input type='checkbox' name='abidan217' value='SMP' <?php if ($abidan217=="SMP"){echo "checked";}?> >SMP                 
					<input type='checkbox' name='abidan217' value='SMA' <?php if ($abidan217=="SMA"){echo "checked";}?> >SMA                                
					<input type='checkbox' name='abidan217' value='Diploma' <?php if ($abidan217=="Diploma"){echo "checked";}?> >Diploma
					<input type='checkbox' name='abidan217' value='Sarjana' <?php if ($abidan217=="Sarjana"){echo "checked";}?> >Sarjana                  
					<input type='checkbox' name='abidan217' value='Pasca Sarjana' <?php if ($abidan217=="Pasca Sarjana"){echo "checked";}?> >Pasca Sarjana              
					<br>
					Pekerjaan                     :      
					<input type='checkbox' name='abidan218' value='Wiraswasta' <?php if ($abidan218=="Wiraswasta"){echo "checked";}?> >Wiraswasta             
					<input type='checkbox' name='abidan218' value='Swasta' <?php if ($abidan218=="Swasta"){echo "checked";}?> >Swasta              
					<input type='checkbox' name='abidan218' value='Pensiunan' <?php if ($abidan218=="Pensiunan"){echo "checked";}?> >Pensiunan                                                                        
					Tinggal Bersama         :        
					<input type='checkbox' name='abidan219' value='suami/istri' <?php if ($abidan219=="suami/istri"){echo "checked";}?> >suami/istri               
					<input type='checkbox' name='abidan219' value='Anak' <?php if ($abidan219=="Anak"){echo "checked";}?> >Anak                  
					<input type='checkbox' name='abidan219' value='Teman' <?php if ($abidan219=="Teman"){echo "checked";}?> >Teman
					<input type='checkbox' name='abidan219' value='Orang Tua' <?php if ($abidan219=="Orang Tua"){echo "checked";}?> >Orang Tua               
					<input type='checkbox' name='abidan219' value='Sendiri' <?php if ($abidan219=="Sendiri"){echo "checked";}?> >Sendiri                
					<input type='checkbox' name='abidan219' value='Caregiver' <?php if ($abidan219=="Caregiver"){echo "checked";}?> >Caregiver
					<br>         
					Status Ekonomi           :       
					<input type='checkbox' name='abidan220' value='Pembayaran Pribadi/Perorangan' <?php if ($abidan220=="Pembayaran Pribadi/Perorangan"){echo "checked";}?> >Pembayaran Pribadi/Perorangan
					<input type='checkbox' name='abidan220' value='Jaminan kesehatan/Asuransi' <?php if ($abidan220=="Jaminan kesehatan/Asuransi"){echo "checked";}?> >Jaminan kesehatan/Asuransi
					<input type='text' name='abidan221' value='<?php echo $abidan221;?>'>
					<br>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
					<br><br>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
					if (empty($abidan222)){
								// $abidan222="Tidak";
								// $abidan223="Tidak Ada Keluhan";
								// $abidan224="Tidak";
					}

					?>

					D. Spiritual<br>
					Menjalankan ibadah       :<br>     
					Ada Hambatan  :      
					<input type='checkbox' name='abidan222' value='Tidak' <?php if ($abidan222=="Tidak"){echo "checked";}?> >Tidak                    
					<input type='checkbox' name='abidan222' value='YA' <?php if ($abidan222=="YA"){echo "checked";}?> >Ya
					<br>
					Persepsi terhadap Sakit :     
					<input type='checkbox' name='abidan223' value='Tidak Ada Keluhan' <?php if ($abidan223=="Tidak Ada Keluhan"){echo "checked";}?> >Tidak Ada Keluhan        
					<input type='checkbox' name='abidan223' value='Rasa Bersalah' <?php if ($abidan223=="Rasa Bersalah"){echo "checked";}?> >Rasa Bersalah
					<br>
					Meminta Pelayanan Spiritual :     
					<input type='checkbox' name='abidan224' value='Tidak' <?php if ($abidan224=="Tidak"){echo "checked";}?> >Tidak         
					<input type='checkbox' name='abidan224' value='YA' <?php if ($abidan224=="YA"){echo "checked";}?> >Ya, (lakukan kolaborasi dengan bagian kerohanian)
					<br>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;">
					<br><br>
				</td>
			</tr>
			<tr>
				<td>
					<hr>
					<b>DATA OBJEKTIF</b><br>
					1. Pemeriksaan Umum<br>
					<?php 
					if (empty($abidan225)){
								// $abidan225="baik";
								// $abidan226="composmentis";
								// $abidan227="78 kg";
								// $abidan228="148 cm";
								// $abidan229="110/70";
								// $abidan230="20";
								// $abidan231="88";
								// $abidan232="36,8";
								// $abidan233="36,8";
					}

					?>
					<table>
						<tr>
							<td>Keadaan Umum</td>
							<td><input type='text' name='abidan225' value='<?php echo $abidan225;?>'> </td>
						</tr>
						<tr>
							<td>kesadaran</td>
							<td><input type='text' name='abidan226' value='<?php echo $abidan226;?>'> </td>
						</tr>
						<tr>
							<td>BB</td>
							<td><input type='text' name='abidan227' value='<?php echo $abidan227;?>'> </td>
						</tr>
						<tr>
							<td>TB</td>
							<td><input type='text' name='abidan228' value='<?php echo $abidan228;?>'> </td>
						</tr>
						<tr>
							<td>TD</td>
							<td><input type='text' name='abidan229' value='<?php echo $abidan229;?>'> mm/Hg</td>
						</tr>
						<tr>
							<td>RR</td>
							<td><input type='text' name='abidan230' value='<?php echo $abidan230;?>'> x/mnt, Teratur/Tidak  </td>
						</tr>
						<tr>
							<td>Nadi</td>
							<td><input type='text' name='abidan231' value='<?php echo $abidan231;?>'> x/mnt. Reguler/irreguler</td>
						</tr>
						<tr>
							<td>Suhu</td>
							<td>
								<input type='text' name='abidan232' value='<?php echo $abidan232;?>'> °C (aksila)
								<input type='text' name='abidan232' value='<?php echo $abidan232;?>'> °C (rectal)
							</td>
						</tr>
					</table>
					<br>
					<table border='1'>
						<tr>
							<td style="border: 1px solid;">Mata</td>
							<td style="border: 1px solid;">
								<!-- <input type='checkbox' name='abidan233' value='Konjungtiva' <?php if ($abidan233=="Konjungtiva"){echo "checked";}?> > -->
								Konjungtiva     
								<input type='checkbox' name='abidan233' value='pucat' <?php if ($abidan233=="pucat"){echo "checked";}?> >Pucat        
								<input type='checkbox' name='abidan233' value='Normal' <?php if ($abidan233=="Normal"){echo "checked";}?> >Normal,
								Sklera        
								<input type='checkbox' name='abidan234' value='Putih' <?php if ($abidan234=="Putih"){echo "checked";}?> >Putih         
								<input type='checkbox' name='abidan234' value='Kuning' <?php if ($abidan234=="Kuning"){echo "checked";}?> >Kuning        
								<input type='checkbox' name='abidan234' value='Merah' <?php if ($abidan234=="Merah"){echo "checked";}?> >Merah
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Mammae</td>
							<td style="border: 1px solid;">
								bentuk :            
								<input type='checkbox' name='abidan235' value='simetris' <?php if ($abidan235=="simetris"){echo "checked";}?> >Simetris        
								<input type='checkbox' name='abidan235' value='Asimetris' <?php if ($abidan235=="Asimetris"){echo "checked";}?> >Asimetris,  
								<br> 
								Puting susu :         
								<input type='checkbox' name='abidan236' value='Menonjol' <?php if ($abidan236=="Menonjol"){echo "checked";}?> >Menonjol        
								<input type='checkbox' name='abidan236' value='Datar' <?php if ($abidan236=="Datar"){echo "checked";}?> >Datar        
								<input type='checkbox' name='abidan236' value='Masuk' <?php if ($abidan236=="Masuk"){echo "checked";}?> >Masuk  
								<br>
								pengeluaran :     
								<input type='checkbox' name='abidan237' value='Tidak' <?php if ($abidan237=="Tidak"){echo "checked";}?> >tidak ada      
								<input type='checkbox' name='abidan237' value='Ada' <?php if ($abidan237=="Ada"){echo "checked";}?> >
								Ada,
								<br>           
								Colostrum         
								<input type='checkbox' name='abidan238' value='ASI' <?php if ($abidan238=="ASI"){echo "checked";}?> >ASI         
								<input type='checkbox' name='abidan238' value='Nanah' <?php if ($abidan238=="Nanah"){echo "checked";}?> >Nanah         
								<input type='checkbox' name='abidan238' value='Darah' <?php if ($abidan238=="Darah"){echo "checked";}?> >Darah
								<br>
								Kebersihan :      
								<input type='checkbox' name='abidan239' value='Cukup' <?php if ($abidan239=="Cukup"){echo "checked";}?> >Cukup          
								<input type='checkbox' name='abidan239' value='Kurang' <?php if ($abidan239=="Kurang"){echo "checked";}?> >Kurang,       
								<br>
								Kelainan :         
								<input type='checkbox' name='abidan240' value='Lecet' <?php if ($abidan240=="Lecet"){echo "checked";}?> >Lecet       
								<input type='checkbox' name='abidan240' value='Bengkak' <?php if ($abidan240=="Bengkak"){echo "checked";}?> >Bengkak     
								<input type='checkbox' name='abidan240' value='Lainnya' <?php if ($abidan240=="Lainnya"){echo "checked";}?> >Lainnya <input type='text' name='abidan241' value='<?php echo $abidan241;?>'>
							</td>
						</tr>
						<tr>
							<td colspan="2">a. Abdomen </td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Inspeksi</td>
							<td style="border: 1px solid;">Luka Bekas Operasi :      
								<input type='checkbox' name='abidan242' value='Tidak  Ada' <?php if ($abidan242=="Tidak  Ada"){echo "checked";}?> >Tidak  Ada       
								<input type='checkbox' name='abidan242' value='Ada' <?php if ($abidan242=="Ada"){echo "checked";}?> >Ada,        
								<br>
								Linea Nigra :                   
								<input type='checkbox' name='abidan243' value='Ada' <?php if ($abidan243=="Ada"){echo "checked";}?> >Ada                 
								<input type='checkbox' name='abidan243' value='Tidak Ada' <?php if ($abidan243=="Tidak Ada"){echo "checked";}?> >Tidak Ada,
								<br>
								Line Alba:                       
								<input type='checkbox' name='abidan244' value='Ada' <?php if ($abidan244=="Ada"){echo "checked";}?> >Ada                 
								<input type='checkbox' name='abidan244' value='Tidak Ada' <?php if ($abidan244=="Tidak Ada"){echo "checked";}?> >Tidak Ada
								<br>
								Pembesaran     :             
								<input type='checkbox' name='abidan245' value='Ada' <?php if ($abidan245=="Ada"){echo "checked";}?> >Memanjang      
								<input type='checkbox' name='abidan245' value='Tidak Ada' <?php if ($abidan245=="Tidak Ada"){echo "checked";}?> >Melebar
								<br>
								Kelaianan                  :  
								<input type='checkbox' name='abidan246' value='Ada' <?php if ($abidan246=="Ada"){echo "checked";}?> >Tidak ada       
								<input type='checkbox' name='abidan246' value='Tidak Ada' <?php if ($abidan246=="Tidak Ada"){echo "checked";}?> >Bandle 
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Palpasi </td>
							<td style="border: 1px solid;">
								<input type='checkbox' name='abidan247' value='Distensi' <?php if ($abidan247=="Distensi"){echo "checked";}?> >Distensi        
								<input type='checkbox' name='abidan247' value='Lainnya' <?php if ($abidan247=="Lainnya"){echo "checked";}?> >Lainnya <input type='text' name='abidan248' value='<?php echo $abidan248;?>'>
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Auskultasi  </td>
							<td style="border: 1px solid;">   
								leopold I :
								<input type='text' name='abidan297' value='<?php echo $abidan297;?>' size='80'><br>  
								TFU :<input type='text' name='abidan248' value='<?php echo $abidan248;?>'> cm,  
								Taksiran Berat Janin (TBJ) : <input type='text' name='abidan249' value='<?php echo $abidan249;?>'> gram, 
								<br>
								leopold II : <input type='text' name='abidan250' value='<?php echo $abidan250;?>' size='80'><br>
								leopold III  : <input type='text' name='abidan251' value='<?php echo $abidan251;?>' size='80'><br>
								leopold IV   : <input type='text' name='abidan252' value='<?php echo $abidan252;?>' size='80'><br>
								kontaksi uterus :       
								<input type='checkbox' name='abidan253' value='Tidak Ada' <?php if ($abidan253=="Tidak Ada"){echo "checked";}?> >Tidak Ada       
								<input type='checkbox' name='abidan253' value='Ada' <?php if ($abidan253=="Ada"){echo "checked";}?> >Ada;        
								<input type='checkbox' name='abidan253' value='adekuat' <?php if ($abidan253=="adekuat"){echo "checked";}?> >adekuat       
								<input type='checkbox' name='abidan253' value='inadekuat' <?php if ($abidan253=="inadekuat"){echo "checked";}?> >inadekuat,  
								<input type='checkbox' name='abidan254' value='His' <?php if ($abidan254=="His"){echo "checked";}?> >His : <input type='text' name='abidan255' value='<?php echo $abidan255;?>'> x/10 mnt,
								<!-- <input type='checkbox' name='abidan256' value='Lama' <?php if ($abidan256=="Lama"){echo "checked";}?> > -->
								Lama : <input type='text' name='abidan257' value='<?php echo $abidan257;?>'> detik 
								<br>
								kelainan                     :     
								<input type='checkbox' name='abidan258' value='Nyeri Tekan' <?php if ($abidan258=="Nyeri Tekan"){echo "checked";}?> >Nyeri Tekan     
								<input type='checkbox' name='abidan258' value='Cekungan pada perut' <?php if ($abidan258=="Cekungan pada perut"){echo "checked";}?> >Cekungan pada perut       
								<input type='checkbox' name='abidan258' value='Blass Penuh' <?php if ($abidan258=="Blass Penuh"){echo "checked";}?> >Blass Penuh, 
								<br>
								Teraba massa            :      
								<input type='checkbox' name='abidan259' value='Tidak Ada' <?php if ($abidan259=="Tidak Ada"){echo "checked";}?> >Tidak Ada       
								<input type='checkbox' name='abidan259' value='Ada' <?php if ($abidan259=="Ada"){echo "checked";}?> >Ada;  
								Ukuran : <input type='text' name='abidan259' value='<?php echo $abidan259;?>'> x <input type='text' name='abidan260' value='<?php echo $abidan260;?>'> cm, 											
								Mobilitas                    :     
								<input type='checkbox' name='abidan261' value='Bebas' <?php if ($abidan261=="Bebas"){echo "checked";}?> >Bebas             
								<input type='checkbox' name='abidan261' value='Terbatas' <?php if ($abidan261=="Terbatas"){echo "checked";}?> >Terbatas       
								<input type='checkbox' name='abidan261' value='Terfiksir' <?php if ($abidan261=="Terfiksir"){echo "checked";}?> >Terfiksir, 
								<br>
								Konsistensi :      
								<input type='checkbox' name='abidan262' value='Kistik' <?php if ($abidan262=="Kistik"){echo "checked";}?> >Kistik       
								<input type='checkbox' name='abidan262' value='Padat' <?php if ($abidan262=="Padat"){echo "checked";}?> >Padat     
								<input type='checkbox' name='abidan262' value='Campuran' <?php if ($abidan262=="Campuran"){echo "checked";}?> >Campuran
								<br>
								Bising Usus                :    
								<input type='checkbox' name='abidan263' value='Ada' <?php if ($abidan263=="Ada"){echo "checked";}?> >Ada        
								<input type='checkbox' name='abidan263' value='Tidak Ada' <?php if ($abidan263=="Tidak Ada"){echo "checked";}?> >Tidak Ada
								<br>
								Denyut Jantung Janin (DJJ) : <input type='text' name='abidan264' value='<?php echo $abidan264;?>'> x/mnt,         
								<input type='checkbox' name='abidan265' value='Teratur' <?php if ($abidan265=="Teratur"){echo "checked";}?> >Teratur         
								<input type='checkbox' name='abidan265' value='Tidak Teratur' <?php if ($abidan265=="Tidak Teratur"){echo "checked";}?> >Tidak Teratur
							</td>
						</tr>
						<tr>
							<td colspan="2">b. Anogenital</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Inspeksi </td>
							<td style="border: 1px solid;">
								Pengeluaran Vaginal : <input type='text' name='abidan266' value='<?php echo $abidan266;?>'>  										
								Lochea                         : <input type='text' name='abidan267' value='<?php echo $abidan267;?>'> 
								Volume                        : <input type='text' name='abidan268' value='<?php echo $abidan268;?>'> cc,   
								<br>
								Berbau:    
								<input type='checkbox' name='abidan269' value='Tidak' <?php if ($abidan269=="Tidak"){echo "checked";}?> >Tidak          
								<input type='checkbox' name='abidan269' value='Ya' <?php if ($abidan269=="Ya"){echo "checked";}?> >Ya;           
								<input type='checkbox' name='abidan269' value='Amis' <?php if ($abidan269=="Amis"){echo "checked";}?> >Amis          
								<input type='checkbox' name='abidan269' value='Busuk' <?php if ($abidan269=="Busuk"){echo "checked";}?> >Busuk      
								<input type='checkbox' name='abidan269' value='YA' <?php if ($abidan269=="YA"){echo "checked";}?> >Lainnya <input type='text' name='abidan270' value='<?php echo $abidan270;?>'>
								<br>
								Perineum                      :  
								<input type='checkbox' name='abidan271' value='Utuh' <?php if ($abidan271=="Utuh"){echo "checked";}?> >Utuh       
								<input type='checkbox' name='abidan271' value='Laserasi' <?php if ($abidan271=="Laserasi"){echo "checked";}?> >Laserasi :  Derajat <input type='text' name='abidan272' value='<?php echo $abidan272;?>'>     
								<input type='checkbox' name='abidan273' value='Jaringan Parut' <?php if ($abidan273=="Jaringan Parut"){echo "checked";}?> >Jaringan Parut                  
								<input type='checkbox' name='abidan273' value='Lainnya' <?php if ($abidan273=="Lainnya"){echo "checked";}?> >Lainnya <input type='text' name='abidan274' value='<?php echo $abidan274;?>'>
								<br>
								Jahitan                         :  
								<input type='checkbox' name='abidan275' value='Baik' <?php if ($abidan275=="Baik"){echo "checked";}?> >Baik       
								<input type='checkbox' name='abidan275' value='Terlepas' <?php if ($abidan275=="Terlepas"){echo "checked";}?> >Terlepas     
								<input type='checkbox' name='abidan275' value='Hematom' <?php if ($abidan275=="Hematom"){echo "checked";}?> >Hematom      
								<input type='checkbox' name='abidan275' value='Oedem' <?php if ($abidan275=="Oedem"){echo "checked";}?> >Oedem        
								<input type='checkbox' name='abidan275' value='Ekimosis' <?php if ($abidan275=="Ekimosis"){echo "checked";}?> >Ekimosis         
								<input type='checkbox' name='abidan275' value='Kemerahan' <?php if ($abidan275=="Kemerahan"){echo "checked";}?> >Kemerahan	
							</td>
						</tr>
						<tr>
							<td style="border: 1px solid;">Inspekulo Vagina </td>
							<td style="border: 1px solid;">
								Vagina<br>
								Kelainan :      
								<input type='checkbox' name='abidan276' value='YA' <?php if ($abidan276=="YA"){echo "checked";}?> >Tidak Ada       
								<input type='checkbox' name='abidan276' value='YA' <?php if ($abidan276=="YA"){echo "checked";}?> >Ada;       
								<input type='checkbox' name='abidan276' value='YA' <?php if ($abidan276=="YA"){echo "checked";}?> >Fistel       
								<input type='checkbox' name='abidan276' value='YA' <?php if ($abidan276=="YA"){echo "checked";}?> >Condiloma      
								<input type='checkbox' name='abidan276' value='YA' <?php if ($abidan276=="YA"){echo "checked";}?> >Septum       
								<input type='checkbox' name='abidan276' value='YA' <?php if ($abidan276=="YA"){echo "checked";}?> >Varises     
								<input type='checkbox' name='abidan276' value='YA' <?php if ($abidan276=="YA"){echo "checked";}?> >Lainnya 
								<input type='text' name='abidan277' value='<?php echo $abidan277;?>'>
								<br>
								Hymen                         :  
								<input type='checkbox' name='abidan278' value='Utuh' <?php if ($abidan278=="Utuh"){echo "checked";}?> >Utuh            
								<input type='checkbox' name='abidan278' value='Robek' <?php if ($abidan278=="Robek"){echo "checked";}?> >Robek;       
								<input type='checkbox' name='abidan278' value='Sampai Dasar' <?php if ($abidan278=="Sampai Dasar"){echo "checked";}?> >Sampai Dasar ,  
								<input type='checkbox' name='abidan278' value='Arah  Robekan' <?php if ($abidan278=="Arah  Robekan"){echo "checked";}?> >Arah  Robekan (Jam) 
								<input type='text' name='abidan279' value='<?php echo $abidan279;?>'>, Keadaan Sekitar
								Robekan <input type='text' name='abidan280' value='<?php echo $abidan280;?>'>
								<br> 
								Portio                           :  
								<input type='checkbox' name='abidan281' value='Utuh' <?php if ($abidan281=="Utuh"){echo "checked";}?> >Utuh            
								<input type='checkbox' name='abidan281' value='Rapuh' <?php if ($abidan281=="Rapuh"){echo "checked";}?> >Rapuh        
								<input type='checkbox' name='abidan281' value='Lainnya' <?php if ($abidan281=="Lainnya"){echo "checked";}?> >Lainnya 
								<input type='text' name='abidan282' value='<?php echo $abidan282;?>'>
								<br>
								Cavum douglasi          : 
								Menonjol:       
								<input type='checkbox' name='abidan283' value='Tidak' <?php if ($abidan283=="Tidak"){echo "checked";}?> >Tidak     
								<input type='checkbox' name='abidan283' value='Ya' <?php if ($abidan283=="Ya"){echo "checked";}?> >Ya
								<br>
								Vagina toucher (VT) oleh <input type='text' name='abidan284' value='<?php echo $abidan284;?>'> tanggal/jam 
								<input type='text' name='abidan285' value='<?php echo $abidan285;?>'>
								<input type='text' name='abidan286' value='<?php echo $abidan286;?>'>
								<br>
								Hasil Vagina toucher (VT) oleh <input type='text' name='abidan256' value='<?php echo $abidan256;?>'> 
							</td>
						</tr>
						<tr>
							<td colspan="2">
								kesan panggul/ukuran panggul luar : <input type='text' name='abidan287' value='<?php echo $abidan287;?>'>

							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<table width="100%">
						<tr>
							<td colspan="2">3. Kesimpulan pemeriksaan penunjang </td>
						</tr>
						<tr>
							<td>
								a. Darah :<br>
							</td>
							<td>
								HB &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='abidan288' value='<?php echo $abidan288;?>'>
								Golongan Darah : <input type='text' name='abidan289' value='<?php echo $abidan289;?>'>
								Rhesus : <input type='text' name='abidan290' value='<?php echo $abidan290;?>'>
								Toxo : <input type='text' name='abidan291' value='<?php echo $abidan291;?>'>
								<br>
								HbsAg &nbsp;&nbsp;&nbsp;: <input type='text' name='abidan292' value='<?php echo $abidan292;?>'>
								HIV &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <input type='text' name='abidan293' value='<?php echo $abidan293;?>'>
							</td>
						</tr>
						<tr>
							<td>
								b. Urine
							</td>
							<td>
								albumin              : <input type='text' name='abidan294' value='<?php echo $abidan294;?>'>
								Reduksi               : <input type='text' name='abidan295' value='<?php echo $abidan295;?>'><br>
							</td>
						</tr>
						<tr>
							<td>
								c. USG e
							</td>
							<td>
								<input type='text' name='abidan296' value='<?php echo $abidan296;?>' size='80'>
							</td>
						</tr>
						<tr>
							<td>
								d. Lainnya
							</td>
							<td>
								<input type='text' name='abidan297' value='<?php echo $abidan297;?>' size='80'>
							</td>
						</tr>

					</table>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"><br><br>
				</td>
			</tr>
			<tr>
				<td>
					A. Discharge Planning (Rencana Pemulangan)<br>

					<table>
						<tr>
							<td style="border: 1px solid;">No</td>
							<td style="border: 1px solid;">Keterangan</td>
							<td style="border: 1px solid;">Ya</td>
							<td style="border: 1px solid;">Tidak</td>
						</tr>

						<tr>
							<td style="border: 1px solid;">1</td>
							<td style="border: 1px solid;">Usia ≥60 tahun</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan298' value='Ya' <?php if ($abidan298=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan298' value='Tidak' <?php if ($abidan298=="Tidak"){echo "checked";}?>></td>
						</tr>
						<tr>
							<td style="border: 1px solid;">2</td>
							<td style="border: 1px solid;">Tinggal sendirian tanpa dukungan sosial secara langsung</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan299' value='Ya' <?php if ($abidan299=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan299' value='Tidak' <?php if ($abidan299=="Tidak"){echo "checked";}?>></td>
						</tr>
						<tr>
							<td style="border: 1px solid;">3</td>
							<td style="border: 1px solid;">Stroke, serangan Jantung, PPOK, gagal jantung kongestif, <br>empisema demensia, TB, alzeimer, AIDS, atau penyakit <br>dengan potensi mengancam nyawa lainnya</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan300' value='Ya' <?php if ($abidan300=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan300' value='Tidak' <?php if ($abidan300=="Tidak"){echo "checked";}?>></td>
						</tr>
						<tr>
							<td style="border: 1px solid;">4</td>
							<td style="border: 1px solid;">Korban dari kasus criminal</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan301' value='Ya' <?php if ($abidan301=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan301' value='Tidak' <?php if ($abidan301=="Tidak"){echo "checked";}?>></td>
						</tr>
						<tr>
							<td style="border: 1px solid;">5</td>
							<td style="border: 1px solid;">Trauma Multiple</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan302' value='Ya' <?php if ($abidan302=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan302' value='Tidak' <?php if ($abidan302=="Tidak"){echo "checked";}?>></td>
						</tr>
						<tr>
							<td style="border: 1px solid;">6</td>
							<td style="border: 1px solid;">Memerlukan Perawatan atau pengobatan berkelanjutan</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan303' value='Ya' <?php if ($abidan303=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan303' value='Tidak' <?php if ($abidan303=="Tidak"){echo "checked";}?>></td>
						</tr>
						<tr>
							<td style="border: 1px solid;">7</td>
							<td style="border: 1px solid;">Pasien lansia yang ada dilantai atas (saat dirumah)</td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan304' value='Ya' <?php if ($abidan304=="Ya"){echo "checked";}?>></td>
							<td style="border: 1px solid;"><input type='checkbox' name='abidan304' value='Tidak' <?php if ($abidan304=="Tidak"){echo "checked";}?>></td>
						</tr>


						<tr>
							<td style="border: 1px solid;" colspan="4">Bila salah satu jawaban adalah ya, dilanjutkan pengisian rm 18a.10 discharge planning terintegrasi</td>
						</tr>

					</table>
					<input type='submit' name='simpan' value='simpan' style="color: white;background: #66CDAA;border-color: #66CDAA;"><br><br>


				</td>
			</tr>
			<tr>
				<td>
					II. LANGKAH KEDUA<br>
					Identifikasi Diagnosa Masalah dan Kebutuhan <input type='text' name='abidan305' value='<?php echo $abidan305;?>' size='50'><br>
					III. IDENTIFIKASI DIAGNOSA DAN ANTISIPASI MASALAH POTENSIAL <input type='text' name='abidan306' value='<?php echo $abidan306;?>'  size='50'><br>
					IV. KEBUTUHAN AKAN TINDAKAN SEGERA <input type='text' name='abidan307' value='<?php echo $abidan307;?>'  size='50'><br>

				</td>
			</tr>

		</table>


		<table border='0' width="100%">
			<tr>
				<td colspan="2">
					<br>
					<br>
					<br>
					<br>
					Selesai asesmen tanggal :<?php echo $tgl_assesment; ?> , Jam : <?php echo $jam_assesment; ?>
					<br>
				</td>
			</tr>
			<tr>
				<td width='50%' align="center">
					Bidan yang melakukan asesmen
				</td>
				<td align="center">
					DPJP										
				</td>
			</tr>
			<tr>
				<td align="center">
					<?php $verif_perawat="Document ini telah diVerifikasi Oleh : ".$userid."Pada Tanggal : ".$tgl_assesment;?>
					<!-- <center><img alt='Verifikasi' src='https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=$verif_perawat&choe=UTF-8'/></center> -->

					<?php 
					QRcode::png($verif_perawat, "image.png", "L", 2, 2);   
					echo "<center><img src='image.png'></center>";
					?>

					<br>
					<?php echo $user;?>
				</td>
				<td align="center">
					<?php echo $dpjp;?>										
				</td>
			</tr>
		</table>

	</div>
</form>
</font>
</body>
</div>

<?php

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
		
		$tglrawat	= $_POST["tglrawat"];
		$abidan1 = $_POST["abidan1"];
		$abidan2 = $_POST['abidan2'];
		$abidan3 = $_POST['abidan3'];
		$abidan4 = $_POST['abidan4'];
		$abidan5 = $_POST['abidan5'];
		$abidan6 = $_POST['abidan6'];
		$abidan7 = $_POST['abidan7'];
		$abidan8 = $_POST['abidan8'];
		$abidan9 = $_POST['abidan9'];
		$abidan10 = $_POST['abidan10'];
		$abidan11 = $_POST['abidan11'];
		$abidan12 = $_POST['abidan12'];
		$abidan13 = $_POST['abidan13'];
		$abidan14 = $_POST['abidan14'];
		$abidan15 = $_POST['abidan15'];
		$abidan16 = $_POST['abidan16'];
		$abidan17 = $_POST['abidan17'];
		$abidan18 = $_POST['abidan18'];
		$abidan19 = $_POST['abidan19'];
		$abidan20 = $_POST['abidan20'];
		$abidan21 = $_POST['abidan21'];
		$abidan22 = $_POST['abidan22'];
		$abidan23 = $_POST['abidan23'];
		$abidan24 = $_POST['abidan24'];
		$abidan25 = $_POST['abidan25'];
		$abidan26 = $_POST['abidan26'];
		$abidan27 = $_POST['abidan27'];
		$abidan28 = $_POST['abidan28'];
		$abidan29 = $_POST['abidan29'];
		$abidan30 = $_POST['abidan30'];
		$abidan31 = $_POST['abidan31'];
		$abidan32 = $_POST['abidan32'];
		$abidan33 = $_POST['abidan33'];
		$abidan34 = $_POST['abidan34'];
		$abidan35 = $_POST['abidan35'];
		$abidan36 = $_POST['abidan36'];
		$abidan37 = $_POST['abidan37'];
		$abidan38 = $_POST['abidan38'];
		$abidan39 = $_POST['abidan39'];
		$abidan40 = $_POST['abidan40'];
		$abidan41 = $_POST['abidan41'];
		$abidan42 = $_POST['abidan42'];
		$abidan43 = $_POST['abidan43'];
		$abidan44 = $_POST['abidan44'];
		$abidan45 = $_POST['abidan45'];
		$abidan46 = $_POST['abidan46'];
		$abidan47 = $_POST['abidan47'];
		$abidan48 = $_POST['abidan48'];
		$abidan49 = $_POST['abidan49'];
		$abidan50 = $_POST['abidan50'];
		$abidan51 = $_POST['abidan51'];
		$abidan52 = $_POST['abidan52'];
		$abidan53 = $_POST['abidan53'];
		$abidan54 = $_POST['abidan54'];
		$abidan55 = $_POST['abidan55'];
		$abidan56 = $_POST['abidan56'];
		$abidan57 = $_POST['abidan57'];
		$abidan58 = $_POST['abidan58'];
		$abidan59 = $_POST['abidan59'];
		$abidan60 = $_POST['abidan60'];
		$abidan61 = $_POST['abidan61'];
		$abidan62 = $_POST['abidan62'];
		$abidan63 = $_POST['abidan63'];
		$abidan64 = $_POST['abidan64'];
		$abidan65 = $_POST['abidan65'];
		$abidan66 = $_POST['abidan66'];
		$abidan67 = $_POST['abidan67'];
		$abidan68 = $_POST['abidan68'];
		$abidan69 = $_POST['abidan69'];
		$abidan70 = $_POST['abidan70'];
		$abidan71 = $_POST['abidan71'];
		$abidan72 = $_POST['abidan72'];
		$abidan73 = $_POST['abidan73'];
		$abidan74 = $_POST['abidan74'];
		$abidan75 = $_POST['abidan75'];
		$abidan76 = $_POST['abidan76'];
		$abidan77 = $_POST['abidan77'];
		$abidan78 = $_POST['abidan78'];
		$abidan79 = $_POST['abidan79'];
		$abidan80 = $_POST['abidan80'];
		$abidan81 = $_POST['abidan81'];
		$abidan82 = $_POST['abidan82'];
		$abidan83 = $_POST['abidan83'];
		$abidan84 = $_POST['abidan84'];
		$abidan85 = $_POST['abidan85'];
		$abidan86 = $_POST['abidan86'];
		$abidan87 = $_POST['abidan87'];
		$abidan88 = $_POST['abidan88'];
		$abidan89 = $_POST['abidan89'];
		$abidan90 = $_POST['abidan90'];
		$abidan91 = $_POST['abidan91'];
		$abidan92 = $_POST['abidan92'];
		$abidan93 = $_POST['abidan93'];
		$abidan94 = $_POST['abidan94'];
		$abidan95 = $_POST['abidan95'];
		$abidan96 = $_POST['abidan96'];
		$abidan97 = $_POST['abidan97'];
		$abidan98 = $_POST['abidan98'];
		$abidan99 = $_POST['abidan99'];
		$abidan100 = $_POST['abidan100'];
		$abidan101 = $_POST['abidan101'];
		$abidan102 = $_POST['abidan102'];
		$abidan103 = $_POST['abidan103'];
		$abidan104 = $_POST['abidan104'];
		$abidan105 = $_POST['abidan105'];
		$abidan106 = $_POST['abidan106'];
		$abidan107 = $_POST['abidan107'];
		$abidan108 = $_POST['abidan108'];
		$abidan109 = $_POST['abidan109'];
		$abidan110 = $_POST['abidan110'];
		$abidan111 = $_POST['abidan111'];
		$abidan112 = $_POST['abidan112'];
		$abidan113 = $_POST['abidan113'];
		$abidan114 = $_POST['abidan114'];
		$abidan115 = $_POST['abidan115'];
		$abidan116 = $_POST['abidan116'];
		$abidan117 = $_POST['abidan117'];
		$abidan118 = $_POST['abidan118'];
		$abidan119 = $_POST['abidan119'];
		$abidan120 = $_POST['abidan120'];
		$abidan121 = $_POST['abidan121'];
		$abidan122 = $_POST['abidan122'];
		$abidan123 = $_POST['abidan123'];
		$abidan124 = $_POST['abidan124'];
		$abidan125 = $_POST['abidan125'];
		$abidan126 = $_POST['abidan126'];
		$abidan127 = $_POST['abidan127'];
		$abidan128 = $_POST['abidan128'];
		$abidan129 = $_POST['abidan129'];
		$abidan130 = $_POST['abidan130'];
		$abidan131 = $_POST['abidan131'];
		$abidan132 = $_POST['abidan132'];
		$abidan133 = $_POST['abidan133'];
		$abidan134 = $_POST['abidan134'];
		$abidan135 = $_POST['abidan135'];
		$abidan136 = $_POST['abidan136'];
		$abidan137 = $_POST['abidan137'];
		$abidan138 = $_POST['abidan138'];
		$abidan139 = $_POST['abidan139'];
		$abidan140 = $_POST['abidan140'];
		$abidan141 = $_POST['abidan141'];
		$abidan142 = $_POST['abidan142'];
		$abidan143 = $_POST['abidan143'];
		$abidan144 = $_POST['abidan144'];
		$abidan145 = $_POST['abidan145'];
		$abidan146 = $_POST['abidan146'];
		$abidan147 = $_POST['abidan147'];
		$abidan148 = $_POST['abidan148'];
		$abidan149 = $_POST['abidan149'];
		$abidan150 = $_POST['abidan150'];
		$abidan151 = $_POST['abidan151'];
		$abidan152 = $_POST['abidan152'];
		$abidan153 = $_POST['abidan153'];
		$abidan154 = $_POST['abidan154'];
		$abidan155 = $_POST['abidan155'];
		$abidan156 = $_POST['abidan156'];
		$abidan157 = $_POST['abidan157'];
		$abidan158 = $_POST['abidan158'];
		$abidan159 = $_POST['abidan159'];
		$abidan160 = $_POST['abidan160'];
		$abidan161 = $_POST['abidan161'];
		$abidan162 = $_POST['abidan162'];
		$abidan163 = $_POST['abidan163'];
		$abidan164 = $_POST['abidan164'];
		$abidan165 = $_POST['abidan165'];
		$abidan166 = $_POST['abidan166'];
		$abidan167 = $_POST['abidan167'];
		$abidan168 = $_POST['abidan168'];
		$abidan169 = $_POST['abidan169'];
		$abidan170 = $_POST['abidan170'];
		$abidan171 = $_POST['abidan171'];
		$abidan172 = $_POST['abidan172'];
		$abidan173 = $_POST['abidan173'];
		$abidan174 = $_POST['abidan174'];
		$abidan175 = $_POST['abidan175'];
		$abidan176 = $_POST['abidan176'];
		$abidan177 = $_POST['abidan177'];
		$abidan178 = $_POST['abidan178'];
		$abidan179 = $_POST['abidan179'];
		$abidan180 = $_POST['abidan180'];
		$abidan181 = $_POST['abidan181'];
		$abidan182 = $_POST['abidan182'];
		$abidan183 = $_POST['abidan183'];
		$abidan184 = $_POST['abidan184'];
		$abidan185 = $_POST['abidan185'];
		$abidan186 = $_POST['abidan186'];
		$abidan187 = $_POST['abidan187'];
		$abidan188 = $_POST['abidan188'];
		$abidan189 = $_POST['abidan189'];
		$abidan190 = $_POST['abidan190'];
		$abidan191 = $_POST['abidan191'];
		$abidan192 = $_POST['abidan192'];
		$abidan193 = $_POST['abidan193'];
		$abidan194 = $_POST['abidan194'];
		$abidan195 = $_POST['abidan195'];
		$abidan196 = $_POST['abidan196'];
		$abidan197 = $_POST['abidan197'];
		$abidan198 = $_POST['abidan198'];
		$abidan199 = $_POST['abidan199'];
		$abidan200 = $_POST['abidan200'];
		$abidan201 = $_POST['abidan201'];
		$abidan202 = $_POST['abidan202'];
		$abidan203 = $_POST['abidan203'];
		$abidan204 = $_POST['abidan204'];
		$abidan205 = $_POST['abidan205'];
		$abidan206 = $_POST['abidan206'];
		$abidan207 = $_POST['abidan207'];
		$abidan208 = $_POST['abidan208'];
		$abidan209 = $_POST['abidan209'];
		$abidan210 = $_POST['abidan210'];
		$abidan211 = $_POST['abidan211'];
		$abidan212 = $_POST['abidan212'];
		$abidan213 = $_POST['abidan213'];
		$abidan214 = $_POST['abidan214'];
		$abidan215 = $_POST['abidan215'];
		$abidan216 = $_POST['abidan216'];
		$abidan217 = $_POST['abidan217'];
		$abidan218 = $_POST['abidan218'];
		$abidan219 = $_POST['abidan219'];
		$abidan220 = $_POST['abidan220'];
		$abidan221 = $_POST['abidan221'];
		$abidan222 = $_POST['abidan222'];
		$abidan223 = $_POST['abidan223'];
		$abidan224 = $_POST['abidan224'];
		$abidan225 = $_POST['abidan225'];
		$abidan226 = $_POST['abidan226'];
		$abidan227 = $_POST['abidan227'];
		$abidan228 = $_POST['abidan228'];
		$abidan229 = $_POST['abidan229'];
		$abidan230 = $_POST['abidan230'];
		$abidan231 = $_POST['abidan231'];
		$abidan232 = $_POST['abidan232'];
		$abidan233 = $_POST['abidan233'];
		$abidan234 = $_POST['abidan234'];
		$abidan235 = $_POST['abidan235'];
		$abidan236 = $_POST['abidan236'];
		$abidan237 = $_POST['abidan237'];
		$abidan238 = $_POST['abidan238'];
		$abidan239 = $_POST['abidan239'];
		$abidan240 = $_POST['abidan240'];
		$abidan241 = $_POST['abidan241'];
		$abidan242 = $_POST['abidan242'];
		$abidan243 = $_POST['abidan243'];
		$abidan244 = $_POST['abidan244'];
		$abidan245 = $_POST['abidan245'];
		$abidan246 = $_POST['abidan246'];
		$abidan247 = $_POST['abidan247'];
		$abidan248 = $_POST['abidan248'];
		$abidan249 = $_POST['abidan249'];
		$abidan250 = $_POST['abidan250'];
		$abidan251 = $_POST['abidan251'];
		$abidan252 = $_POST['abidan252'];
		$abidan253 = $_POST['abidan253'];
		$abidan254 = $_POST['abidan254'];
		$abidan255 = $_POST['abidan255'];
		$abidan256 = $_POST['abidan256'];
		$abidan257 = $_POST['abidan257'];
		$abidan258 = $_POST['abidan258'];
		$abidan259 = $_POST['abidan259'];
		$abidan260 = $_POST['abidan260'];
		$abidan261 = $_POST['abidan261'];
		$abidan262 = $_POST['abidan262'];
		$abidan263 = $_POST['abidan263'];
		$abidan264 = $_POST['abidan264'];
		$abidan265 = $_POST['abidan265'];
		$abidan266 = $_POST['abidan266'];
		$abidan267 = $_POST['abidan267'];
		$abidan268 = $_POST['abidan268'];
		$abidan269 = $_POST['abidan269'];
		$abidan270 = $_POST['abidan270'];
		$abidan271 = $_POST['abidan271'];
		$abidan272 = $_POST['abidan272'];
		$abidan273 = $_POST['abidan273'];
		$abidan274 = $_POST['abidan274'];
		$abidan275 = $_POST['abidan275'];
		$abidan276 = $_POST['abidan276'];
		$abidan277 = $_POST['abidan277'];
		$abidan278 = $_POST['abidan278'];
		$abidan279 = $_POST['abidan279'];
		$abidan280 = $_POST['abidan280'];
		$abidan281 = $_POST['abidan281'];
		$abidan282 = $_POST['abidan282'];
		$abidan283 = $_POST['abidan283'];
		$abidan284 = $_POST['abidan284'];
		$abidan285 = $_POST['abidan285'];
		$abidan286 = $_POST['abidan286'];
		$abidan287 = $_POST['abidan287'];
		$abidan288 = $_POST['abidan288'];
		$abidan289 = $_POST['abidan289'];
		$abidan290 = $_POST['abidan290'];
		$abidan291 = $_POST['abidan291'];
		$abidan292 = $_POST['abidan292'];
		$abidan293 = $_POST['abidan293'];
		$abidan294 = $_POST['abidan294'];
		$abidan295 = $_POST['abidan295'];
		$abidan296 = $_POST['abidan296'];
		$abidan297 = $_POST['abidan297'];
		$abidan298 = $_POST['abidan298'];
		$abidan299 = $_POST['abidan299'];
		$abidan300 = $_POST['abidan300'];
		$abidan301 = $_POST['abidan301'];
		$abidan302 = $_POST['abidan302'];
		$abidan303 = $_POST['abidan303'];
		$abidan304 = $_POST['abidan304'];
		$abidan305 = $_POST['abidan305'];
		$abidan306 = $_POST['abidan306'];
		$abidan307 = $_POST['abidan307'];
		$abidan308 = $_POST['abidan308'];
		$abidan309 = $_POST['abidan309'];
		$abidan310 = $_POST['abidan310'];
		$abidan311 = $_POST['abidan311'];
		$abidan312 = $_POST['abidan312'];
		$abidan313 = $_POST['abidan313'];
		$abidan314 = $_POST['abidan314'];
		$abidan315 = $_POST['abidan315'];
		$abidan316 = $_POST['abidan316'];
		$abidan317 = $_POST['abidan317'];
		$abidan318 = $_POST['abidan318'];
		$abidan319 = $_POST['abidan319'];
		$abidan320 = $_POST['abidan320'];
		$jam = $_POST['jam'];


		$q  = "update ERM_RI_ASSESMEN_AWAL_BERSALIN set
		tglrawat='$tglrawat',jam='$jam',
		abidan1 = '$abidan1',
		abidan2 = '$abidan2',
		abidan3 = '$abidan3',
		abidan4 = '$abidan4',
		abidan5 = '$abidan5',
		abidan6 = '$abidan6',
		abidan7 = '$abidan7',
		abidan8 = '$abidan8',
		abidan9 = '$abidan9',
		abidan10 = '$abidan10',
		abidan11 = '$abidan11',
		abidan12 = '$abidan12',
		abidan13 = '$abidan13',
		abidan14 = '$abidan14',
		abidan15 = '$abidan15',
		abidan16 = '$abidan16',
		abidan17 = '$abidan17',
		abidan18 = '$abidan18',
		abidan19 = '$abidan19',
		abidan20 = '$abidan20',
		abidan21 = '$abidan21',
		abidan22 = '$abidan22',
		abidan23 = '$abidan23',
		abidan24 = '$abidan24',
		abidan25 = '$abidan25',
		abidan26 = '$abidan26',
		abidan27 = '$abidan27',
		abidan28 = '$abidan28',
		abidan29 = '$abidan29',
		abidan30 = '$abidan30',
		abidan31 = '$abidan31',
		abidan32 = '$abidan32',
		abidan33 = '$abidan33',
		abidan34 = '$abidan34',
		abidan35 = '$abidan35',
		abidan36 = '$abidan36',
		abidan37 = '$abidan37',
		abidan38 = '$abidan38',
		abidan39 = '$abidan39',
		abidan40 = '$abidan40',
		abidan41 = '$abidan41',
		abidan42 = '$abidan42',
		abidan43 = '$abidan43',
		abidan44 = '$abidan44',
		abidan45 = '$abidan45',
		abidan46 = '$abidan46',
		abidan47 = '$abidan47',
		abidan48 = '$abidan48',
		abidan49 = '$abidan49',
		abidan50 = '$abidan50',
		abidan51 = '$abidan51',
		abidan52 = '$abidan52',
		abidan53 = '$abidan53',
		abidan54 = '$abidan54',
		abidan55 = '$abidan55',
		abidan56 = '$abidan56',
		abidan57 = '$abidan57',
		abidan58 = '$abidan58',
		abidan59 = '$abidan59',
		abidan60 = '$abidan60',
		abidan61 = '$abidan61',
		abidan62 = '$abidan62',
		abidan63 = '$abidan63',
		abidan64 = '$abidan64',
		abidan65 = '$abidan65',
		abidan66 = '$abidan66',
		abidan67 = '$abidan67',
		abidan68 = '$abidan68',
		abidan69 = '$abidan69',
		abidan70 = '$abidan70',
		abidan71 = '$abidan71',
		abidan72 = '$abidan72',
		abidan73 = '$abidan73',
		abidan74 = '$abidan74',
		abidan75 = '$abidan75',
		abidan76 = '$abidan76',
		abidan77 = '$abidan77',
		abidan78 = '$abidan78',
		abidan79 = '$abidan79',
		abidan80 = '$abidan80',
		abidan81 = '$abidan81',
		abidan82 = '$abidan82',
		abidan83 = '$abidan83',
		abidan84 = '$abidan84',
		abidan85 = '$abidan85',
		abidan86 = '$abidan86',
		abidan87 = '$abidan87',
		abidan88 = '$abidan88',
		abidan89 = '$abidan89',
		abidan90 = '$abidan90',
		abidan91 = '$abidan91',
		abidan92 = '$abidan92',
		abidan93 = '$abidan93',
		abidan94 = '$abidan94',
		abidan95 = '$abidan95',
		abidan96 = '$abidan96',
		abidan97 = '$abidan97',
		abidan98 = '$abidan98',
		abidan99 = '$abidan99',
		abidan100 = '$abidan100',
		abidan101 = '$abidan101',
		abidan102 = '$abidan102',
		abidan103 = '$abidan103',
		abidan104 = '$abidan104',
		abidan105 = '$abidan105',
		abidan106 = '$abidan106',
		abidan107 = '$abidan107',
		abidan108 = '$abidan108',
		abidan109 = '$abidan109',
		abidan110 = '$abidan110',
		abidan111 = '$abidan111',
		abidan112 = '$abidan112',
		abidan113 = '$abidan113',
		abidan114 = '$abidan114',
		abidan115 = '$abidan115',
		abidan116 = '$abidan116',
		abidan117 = '$abidan117',
		abidan118 = '$abidan118',
		abidan119 = '$abidan119',
		abidan120 = '$abidan120',
		abidan121 = '$abidan121',
		abidan122 = '$abidan122',
		abidan123 = '$abidan123',
		abidan124 = '$abidan124',
		abidan125 = '$abidan125',
		abidan126 = '$abidan126',
		abidan127 = '$abidan127',
		abidan128 = '$abidan128',
		abidan129 = '$abidan129',
		abidan130 = '$abidan130',
		abidan131 = '$abidan131',
		abidan132 = '$abidan132',
		abidan133 = '$abidan133',
		abidan134 = '$abidan134',
		abidan135 = '$abidan135',
		abidan136 = '$abidan136',
		abidan137 = '$abidan137',
		abidan138 = '$abidan138',
		abidan139 = '$abidan139',
		abidan140 = '$abidan140',
		abidan141 = '$abidan141',
		abidan142 = '$abidan142',
		abidan143 = '$abidan143',
		abidan144 = '$abidan144',
		abidan145 = '$abidan145',
		abidan146 = '$abidan146',
		abidan147 = '$abidan147',
		abidan148 = '$abidan148',
		abidan149 = '$abidan149',
		abidan150 = '$abidan150',
		abidan151 = '$abidan151',
		abidan152 = '$abidan152',
		abidan153 = '$abidan153',
		abidan154 = '$abidan154',
		abidan155 = '$abidan155',
		abidan156 = '$abidan156',
		abidan157 = '$abidan157',
		abidan158 = '$abidan158',
		abidan159 = '$abidan159',
		abidan160 = '$abidan160',
		abidan161 = '$abidan161',
		abidan162 = '$abidan162',
		abidan163 = '$abidan163',
		abidan164 = '$abidan164',
		abidan165 = '$abidan165',
		abidan166 = '$abidan166',
		abidan167 = '$abidan167',
		abidan168 = '$abidan168',
		abidan169 = '$abidan169',
		abidan170 = '$abidan170',
		abidan171 = '$abidan171',
		abidan172 = '$abidan172',
		abidan173 = '$abidan173',
		abidan174 = '$abidan174',
		abidan175 = '$abidan175',
		abidan176 = '$abidan176',
		abidan177 = '$abidan177',
		abidan178 = '$abidan178',
		abidan179 = '$abidan179',
		abidan180 = '$abidan180',
		abidan181 = '$abidan181',
		abidan182 = '$abidan182',
		abidan183 = '$abidan183',
		abidan184 = '$abidan184',
		abidan185 = '$abidan185',
		abidan186 = '$abidan186',
		abidan187 = '$abidan187',
		abidan188 = '$abidan188',
		abidan189 = '$abidan189',
		abidan190 = '$abidan190',
		abidan191 = '$abidan191',
		abidan192 = '$abidan192',
		abidan193 = '$abidan193',
		abidan194 = '$abidan194',
		abidan195 = '$abidan195',
		abidan196 = '$abidan196',
		abidan197 = '$abidan197',
		abidan198 = '$abidan198',
		abidan199 = '$abidan199',
		abidan200 = '$abidan200',
		abidan201 = '$abidan201',
		abidan202 = '$abidan202',
		abidan203 = '$abidan203',
		abidan204 = '$abidan204',
		abidan205 = '$abidan205',
		abidan206 = '$abidan206',
		abidan207 = '$abidan207',
		abidan208 = '$abidan208',
		abidan209 = '$abidan209',
		abidan210 = '$abidan210',
		abidan211 = '$abidan211',
		abidan212 = '$abidan212',
		abidan213 = '$abidan213',
		abidan214 = '$abidan214',
		abidan215 = '$abidan215',
		abidan216 = '$abidan216',
		abidan217 = '$abidan217',
		abidan218 = '$abidan218',
		abidan219 = '$abidan219',
		abidan220 = '$abidan220',
		abidan221 = '$abidan221',
		abidan222 = '$abidan222',
		abidan223 = '$abidan223',
		abidan224 = '$abidan224',
		abidan225 = '$abidan225',
		abidan226 = '$abidan226',
		abidan227 = '$abidan227',
		abidan228 = '$abidan228',
		abidan229 = '$abidan229',
		abidan230 = '$abidan230',
		abidan231 = '$abidan231',
		abidan232 = '$abidan232',
		abidan233 = '$abidan233',
		abidan234 = '$abidan234',
		abidan235 = '$abidan235',
		abidan236 = '$abidan236',
		abidan237 = '$abidan237',
		abidan238 = '$abidan238',
		abidan239 = '$abidan239',
		abidan240 = '$abidan240',
		abidan241 = '$abidan241',
		abidan242 = '$abidan242',
		abidan243 = '$abidan243',
		abidan244 = '$abidan244',
		abidan245 = '$abidan245',
		abidan246 = '$abidan246',
		abidan247 = '$abidan247',
		abidan248 = '$abidan248',
		abidan249 = '$abidan249',
		abidan250 = '$abidan250',
		abidan251 = '$abidan251',
		abidan252 = '$abidan252',
		abidan253 = '$abidan253',
		abidan254 = '$abidan254',
		abidan255 = '$abidan255',
		abidan256 = '$abidan256',
		abidan257 = '$abidan257',
		abidan258 = '$abidan258',
		abidan259 = '$abidan259',
		abidan260 = '$abidan260',
		abidan261 = '$abidan261',
		abidan262 = '$abidan262',
		abidan263 = '$abidan263',
		abidan264 = '$abidan264',
		abidan265 = '$abidan265',
		abidan266 = '$abidan266',
		abidan267 = '$abidan267',
		abidan268 = '$abidan268',
		abidan269 = '$abidan269',
		abidan270 = '$abidan270',
		abidan271 = '$abidan271',
		abidan272 = '$abidan272',
		abidan273 = '$abidan273',
		abidan274 = '$abidan274',
		abidan275 = '$abidan275',
		abidan276 = '$abidan276',
		abidan277 = '$abidan277',
		abidan278 = '$abidan278',
		abidan279 = '$abidan279',
		abidan280 = '$abidan280',
		abidan281 = '$abidan281',
		abidan282 = '$abidan282',
		abidan283 = '$abidan283',
		abidan284 = '$abidan284',
		abidan285 = '$abidan285',
		abidan286 = '$abidan286',
		abidan287 = '$abidan287',
		abidan288 = '$abidan288',
		abidan289 = '$abidan289',
		abidan290 = '$abidan290',
		abidan291 = '$abidan291',
		abidan292 = '$abidan292',
		abidan293 = '$abidan293',
		abidan294 = '$abidan294',
		abidan295 = '$abidan295',
		abidan296 = '$abidan296',
		abidan297 = '$abidan297',
		abidan298 = '$abidan298',
		abidan299 = '$abidan299',
		abidan300 = '$abidan300',
		abidan301 = '$abidan301',
		abidan302 = '$abidan302',
		abidan303 = '$abidan303',
		abidan304 = '$abidan304',
		abidan305 = '$abidan305',
		abidan306 = '$abidan306',
		abidan307 = '$abidan307',
		abidan308 = '$abidan308',
		abidan309 = '$abidan309',
		abidan310 = '$abidan310',
		abidan311 = '$abidan311',
		abidan312 = '$abidan312',
		abidan313 = '$abidan313',
		abidan314 = '$abidan314',
		abidan315 = '$abidan315',
		abidan316 = '$abidan316',
		abidan317 = '$abidan317',
		abidan318 = '$abidan318',
		abidan319 = '$abidan319',
		abidan320 = '$abidan320',
		dpjp = '$dpjp'

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

	// echo "
	// <script>
	// alert('".$eror."');
	// history.go(-1);
	// </script>
	// ";

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

