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
$idsoap = $row[2]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = trim($d1u['noreg']);
$sbu = $d1u['sbu'];
$norm = trim($d1u['norm']);
$kodeunit = $d1u['kodeunit'];

//select master pasien...
$q2		= "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);			  
$data2	= sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);				  

$nama	= $data2[nama];
$alamat	= $data2[alamatpasien];
$tgllahir	= $data2[tgllahir];


//get data dari resume...
$qe="
SELECT *,CONVERT(VARCHAR, tglresume, 23) as tglresume,CONVERT(VARCHAR, tglentry, 8) as jamentry
FROM ERM_RI_RESUME
where noreg='$noreg'";
$he  = sqlsrv_query($conn, $qe);        
$de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
$jamentry = $de['jamentry'];
$resume2= $de['resume2'];
$resume1 = $de['resume1'];
$resume2= $de['resume2'];
$resume3= $de['resume3'];
$resume4= $de['resume4'];
$resume5= $de['resume5'];
$resume6= $de['resume6'];
$resume7= $de['resume7'];
$resume8= $de['resume8'];
$resume9= $de['resume9'];
$resume10= $de['resume10'];
$resume11= $de['resume11'];
$resume12= $de['resume12'];
$resume13= $de['resume13'];
$resume14= $de['resume14'];
$resume15= $de['resume15'];
$resume16= $de['resume16'];
$resume17= $de['resume17'];
$resume18= $de['resume18'];
$resume19= $de['resume19'];
$resume20= $de['resume20'];
$resume21= $de['resume21'];
$resume22= $de['resume22'];
$resume23= $de['resume23'];
$resume24= $de['resume24'];
$resume25= $de['resume25'];
$resume26= $de['resume26'];
$resume27= $de['resume27'];
$resume28= $de['resume28'];
$resume29= $de['resume29'];
$resume30= $de['resume30'];
$resume31= $de['resume31'];
$resume32= $de['resume32'];
$resume33= $de['resume33'];
$resume34= $de['resume34'];
$resume35= $de['resume35'];
$resume36= $de['resume36'];
$resume37= $de['resume37'];
$resume38= $de['resume38'];
$resume39= $de['resume39'];
$resume40= $de['resume40'];
$resume41= $de['resume41'];
$resume42= $de['resume42'];
$resume43= $de['resume43'];
$resume44= $de['resume44'];
$resume45= $de['resume45'];
$resume46= $de['resume46'];
$resume47= $de['resume47'];
$resume48= $de['resume48'];
$resume49= $de['resume49'];
$resume50= $de['resume50'];
$resume51= $de['resume51'];
$resume52= $de['resume52'];
$resume53= $de['resume53'];
$resume54= $de['resume54'];
$resume55= $de['resume55'];
$resume56= $de['resume56'];
$resume57= $de['resume57'];
$resume58= $de['resume58'];
$resume59= $de['resume59'];
$resume60= $de['resume60'];
$resume61= $de['resume61'];
$resume62= $de['resume62'];
$resume63= $de['resume63'];
$resume64= $de['resume64'];
$resume65= $de['resume65'];
$resume66= $de['resume66'];
$resume67= $de['resume67'];
$resume68= $de['resume68'];
$resume69= $de['resume69'];
$resume70= $de['resume70'];
$resume71= $de['resume71'];
$resume72= $de['resume72'];
$resume73= $de['resume73'];
$resume74= $de['resume74'];
$resume75= $de['resume75'];
$resume76= $de['resume76'];
$resume77= $de['resume77'];
$resume78= $de['resume78'];
$resume79= $de['resume79'];
$resume80= $de['resume80'];
$resume81= $de['resume81'];
$resume82= $de['resume82'];
$resume83= $de['resume83'];
$resume84= $de['resume84'];
$resume85= $de['resume85'];
$resume86= $de['resume86'];
$resume87= $de['resume87'];
$resume88= $de['resume88'];
$resume89= $de['resume89'];
$resume90= $de['resume90'];
$resume91= $de['resume91'];
$resume92= $de['resume92'];
$resume93= $de['resume93'];
$resume94= $de['resume94'];
$resume95= $de['resume95'];
$resume96= $de['resume96'];
$resume97= $de['resume97'];
$resume98= $de['resume98'];
$resume99= $de['resume99'];
$resume100= $de['resume100'];
$resume101= $de['resume101'];
$resume102= $de['resume102'];
$resume103= $de['resume103'];
$resume104= $de['resume104'];
$resume105= $de['resume105'];
$resume106= $de['resume106'];
$resume107= $de['resume107'];
$resume108= $de['resume108'];
$resume109= $de['resume109'];
$resume110= $de['resume110'];
$resume111= $de['resume111'];
$resume112= $de['resume112'];
$resume113= $de['resume113'];
$resume114= $de['resume114'];
$resume115= $de['resume115'];
$resume116= $de['resume116'];
$resume117= $de['resume117'];
$resume118= $de['resume118'];
$resume119= $de['resume119'];
$resume120= $de['resume120'];
$resume121= $de['resume121'];
$resume122= $de['resume122'];
$resume123= $de['resume123'];
$resume124= $de['resume124'];
$resume125= $de['resume125'];
$resume126= $de['resume126'];
$resume127= $de['resume127'];
$resume128= $de['resume128'];
$resume129= $de['resume129'];
$resume130= $de['resume130'];
$resume131= $de['resume131'];
$resume132= $de['resume132'];
$resume133= $de['resume133'];
$resume134= $de['resume134'];
$resume135= $de['resume135'];
$resume136= $de['resume136'];
$resume137= $de['resume137'];
$resume138= $de['resume138'];
$resume139= $de['resume139'];
$resume140= $de['resume140'];
$resume141= $de['resume141'];
$resume142= $de['resume142'];
$resume143= $de['resume143'];
$resume144= $de['resume144'];
$resume145= $de['resume145'];
$resume146= $de['resume146'];
$resume147= $de['resume147'];
$resume148= $de['resume148'];
$resume149= $de['resume149'];
$resume150= $de['resume150'];


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

<div class="container">

	<body onload="document.myForm.kodedokter.focus();">
		<form method="POST" name='myForm' action="" enctype="multipart/form-data">

			<br>
			<a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
			&nbsp;&nbsp;
			<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i></a>
			<br><br>

			<br>
			<div class="row">
				<div class="col-12">
					<i class="bi bi-window-plus"> &nbsp; <b>UPLOAD BERKAS</b></i>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<iframe src="http://192.168.10.109/dok_erm/index.php?id=<?php echo $noreg;?>" width="100%" height="600" scrolling="yes"></iframe>
				</div>
			</div>
		</div>
		<br><br><br>
	</form>
</body>
</div>