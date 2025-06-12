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
	SELECT *,CONVERT(VARCHAR, tglrawat, 23) as tglrawat,
	CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
	CONVERT(VARCHAR, tglentry, 8) as jam_assesment
	FROM ERM_RI_ASSESMEN_AWAL_NEONATUS
	where noreg='$noreg'";
	$he  = sqlsrv_query($conn, $qe);        
	$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
	$userid = $de['userid'];

	$tglrawat = $de['tglrawat'];
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

}

?>



<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  
	<!-- <link rel="stylesheet" href="css/bootstrap.min.css" /> -->
	<link href="css/styles.css" rel="stylesheet" />
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
		<font size='4px'>	
			<form method="POST" name='myForm' action="" enctype="multipart/form-data">
				<br>
				<div class="row">
					<div class="col-12">
						<a href='form_assesmen_neonatus.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'>[ x ]</a>
						&nbsp;&nbsp;
						<a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
						&nbsp;&nbsp;
					</div>
				</div>

				<br>
				<div class="col-12 text-center">
					<i class="bi bi-window-plus"> &nbsp; <b>ASESMEN AWAL KEBIDANAN NEONATUS</b></i>
				</div>
				<br>

				<div class="col-12">
					<table>
						<tr>
							<td colspan='2'><b>DIISI OLEH BIDAN / PERAWAT</b> &nbsp;&nbsp;&nbsp;<input class="" name="tglinput" value="<?php echo $tglinput;?>" type="text" >
								&nbsp;&nbsp;&nbsp;
								<b>DPJP</b> : <input class="" name="dpjp" value="<?php echo $dpjp;?>" id="dokter" type="text" size='50' onfocus="nextfield ='';" placeholder="Isikan Nama Dokter atau Kode Dokter" required>&nbsp;&nbsp;&nbsp;
							</td>

						</tr>
						
					</table>

					<table border='0' width="100%">
						<tr>
							<td><h6>B. Riwayat Penyakit Ibu dan pengobatannya</h6></td>
						</tr>
						<tr>
							<td>
								Sebelum Hamil ini : <br>      
								<input type='checkbox' name='neo41' value='HT' <?php if ($neo41=="HT"){echo "checked";}?> >HT        
								<input type='checkbox' name='neo41' value='DM' <?php if ($neo41=="DM"){echo "checked";}?> >DM        
								<input type='checkbox' name='neo41' value='Hepatitis' <?php if ($neo41=="Hepatitis"){echo "checked";}?> >Hepatitis        
								<input type='checkbox' name='neo41' value='Lain-lain' <?php if ($neo41=="Lain-lain"){echo "checked";}?> >Lain-lain
								<input type='text' name='neo42' value='<?php echo $neo42;?>'>
							</td>
						</tr>
						<tr>
							<td>
								Selama Hamil ini   : <br>
								<input type='checkbox' name='neo43' value='HT' <?php if ($neo43=="HT"){echo "checked";}?> >HT        
								<input type='checkbox' name='neo43' value='DM' <?php if ($neo43=="DM"){echo "checked";}?> >DM        
								<input type='checkbox' name='neo43' value='Hepatitis' <?php if ($neo43=="Hepatitis"){echo "checked";}?> >Hepatitis        
								<input type='checkbox' name='neo43' value='Lain-lain' <?php if ($neo43=="Lain-lain"){echo "checked";}?> >Lain-lain
								<input type='text' name='neo44' value='<?php echo $neo44;?>'>
							</td>
						</tr>
						<tr>
							<td><br>
								<table border='1' width="100%">
									<tr>
										<td></td>
										<td>Trimester 1</td>
										<td>Trimester 2</td>
										<td>Trimester 3</td>
									</tr>
									<tr>
										<td>Jumlah ANC</td>
										<td><input type='text' name='neo45' value='<?php echo $neo45;?>'></td>
										<td><input type='text' name='neo46' value='<?php echo $neo46;?>'></td>
										<td><input type='text' name='neo47' value='<?php echo $neo47;?>'></td>
									</tr>
									<tr>
										<td>Obat yang dikonsumsi</td>
										<td><input type='text' name='neo48' value='<?php echo $neo48;?>'></td>
										<td><input type='text' name='neo49' value='<?php echo $neo49;?>'></td>
										<td><input type='text' name='neo50' value='<?php echo $neo50;?>'></td>
									</tr>
									<tr>
										<td>Vac TT</td>
										<td><input type='text' name='neo51' value='<?php echo $neo51;?>'></td>
										<td><input type='text' name='neo52' value='<?php echo $neo52;?>'></td>
										<td><input type='text' name='neo53' value='<?php echo $neo53;?>'></td>
									</tr>
								</table>
							</td>
						</tr>

					</table>


					<br>
					
					<table border='0' width="100%">
						<tr><td align="center">
							<button type='submit' name='simpan' value='simpan' class="btn btn-success" type="button" style="height: 60px;width: 150px;"><i class="bi bi-save-fill"></i> simpan</button>
						</td></tr>
					</table>


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


		echo $q  = "update ERM_RI_ASSESMEN_AWAL_NEONATUS set		
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
		userid='$user'

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

