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


// Query data lama
$id_observasi = $id_observasi  ?? '';
$query = "SELECT * FROM ERM_RI_OBSERVASI_CAIRAN WHERE id = '$id_observasi'";
$result = sqlsrv_query($conn, $query);
$data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);


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

		<form method="POST" action="" enctype="multipart/form-data">
			<input type="hidden" name="id_observasi" value="<?php echo $id_observasi; ?>">
			<br><br>
			<div class="mb-3">
				<a href='listobservasi_cairan.php?id=<?php echo $id . "|" . $user; ?>' class='btn btn-warning btn-sm'>
					<i class="bi bi-x-circle"></i> Close
				</a>
				<a href='' class='btn btn-success btn-sm'>
					<i class="bi bi-arrow-clockwise"></i> Refresh
				</a>
			</div>

			<h4><i class="bi bi-droplet-half text-primary"></i> Edit Monitoring Cairan</h4>

			<div class="form-floating mb-3">
				<?php
				$tglinput = !empty($data['tglinput']) ? $data['tglinput']->format('Y-m-d H:i:s') : '';
				?>
				<input type="text" class="form-control" name="tglinput" value="<?php echo $tglinput; ?>">
				<label><i class="bi bi-calendar-event"></i> Tanggal/Jam Input</label>
			</div>

			<!-- Petugas -->
			<div class="mb-3">
				<label class="form-label">Petugas</label>
				<input type="text" class="form-control" name="userinput" value="<?php echo $data['userinput']; ?>">
			</div>

			<!-- Input -->
			<h5>Input</h5>
			<div class="row g-3 mb-3">
				<div class="col-md-4">
					<label>Nama Cairan Infus</label>
					<input type="text" class="form-control" name="ob29" value="<?php echo $data['ob29']; ?>">
				</div>
				<div class="col-md-4">
					<label>Jumlah Cairan (Infus cc)</label>
					<input type="number" class="form-control" name="ob12" value="<?php echo $data['ob12']; ?>">
				</div>
				<div class="col-md-4">
					<label>Tetesan Infus (/menit)</label>
					<input type="text" class="form-control" name="ob13" value="<?php echo $data['ob13']; ?>">
				</div>
				<div class="col-md-4">
					<label>Jenis Transfusi</label>
					<input type="text" class="form-control" name="jtranfusi" value="<?php echo $data['jtranfusi']; ?>">
				</div>
				<div class="col-md-4">
					<label>Jumlah Transfusi (cc)</label>
					<input type="number" class="form-control" name="ob15" value="<?php echo $data['ob15']; ?>">
				</div>
				<div class="col-md-4">
					<label>Tetesan Transfusi (/menit)</label>
					<input type="text" class="form-control" name="ob16" value="<?php echo $data['ob16']; ?>">
				</div>
				<div class="col-md-4">
					<label>Minum (cc)</label>
					<input type="number" class="form-control" name="ob19" value="<?php echo $data['ob19']; ?>">
				</div>
			</div>

			<!-- Output -->
			<h5>Output</h5>
			<div class="row g-3 mb-3">
				<div class="col-md-4">
					<label>Muntah (cc)</label>
					<input type="number" class="form-control" name="ob20" value="<?php echo $data['ob20']; ?>">
				</div>
				<div class="col-md-4">
					<label>Feses (cc)</label>
					<input type="number" class="form-control" name="ob21" value="<?php echo $data['ob21']; ?>">
				</div>
				<div class="col-md-4">
					<label>Urine (cc)</label>
					<input type="number" class="form-control" name="ob22" value="<?php echo $data['ob22']; ?>">
				</div>
				<div class="col-md-4">
					<label>IWL (cc)</label>
					<input type="number" class="form-control" name="ob23" value="<?php echo $data['ob23']; ?>">
				</div>
				<div class="col-md-4">
					<label>NGT (cc)</label>
					<input type="number" class="form-control" name="ob24" value="<?php echo $data['ob24']; ?>">
				</div>
				<div class="col-md-4">
					<label>Drain (cc)</label>
					<input type="number" class="form-control" name="ob25" value="<?php echo $data['ob25']; ?>">
				</div>
				<div class="col-md-4">
					<label>Pendarahan (cc)</label>
					<input type="number" class="form-control" name="ob26" value="<?php echo $data['ob26']; ?>">
				</div>
			</div>

			<!-- Total -->
			<h5>Balance & Total</h5>
			<div class="row g-3 mb-3">
				<div class="col-md-4">
					<label>Total Input (cc)</label>
					<input type="number" class="form-control" name="total_input" value="<?php echo $data['total_input']; ?>">
				</div>
				<div class="col-md-4">
					<label>Total Output (cc)</label>
					<input type="number" class="form-control" name="total_output" value="<?php echo $data['total_output']; ?>">
				</div>
				<div class="col-md-4">
					<label>Balance (cc)</label>
					<input type="number" class="form-control" name="balance" value="<?php echo $data['ob27']; ?>">
				</div>
			</div>

			<!-- Keterangan -->
			<div class="mb-3">
				<label>Keterangan</label>
				<input type="text" class="form-control" name="keterangan" value="<?php echo $data['keterangan']; ?>">
			</div>


			<!-- Kolom Tambahan -->
			<div class="card mb-4">
				<div class="card-header bg-dark text-white">
					<i class="bi bi-clipboard-data"></i> Kolom Tambahan
				</div>
				<div class="card-body row g-3">
					<div class="col-md-4">
						<label class="form-label">Total Input (cc)</label>
						<input type="number" class="form-control" name="total_input" value="<?php echo $data['total_input']; ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label">Total Output (cc)</label>
						<input type="number" class="form-control" name="total_output" value="<?php echo $data['total_output']; ?>">
					</div>
					<div class="col-md-4">
						<label class="form-label">Balance (cc)</label>
						<input type="number" class="form-control" name="balance" value="<?php echo $data['ob27']; ?>">
					</div>

					<div class="col-md-12">
						<label class="form-label">Terapi</label>
						<textarea class="form-control" name="terapi"><?php echo $data['terapi']; ?></textarea>
					</div>
				</div>
			</div>

			<div class="card-footer text-center mt-4">
				<button type="submit" name="simpan" class="btn btn-info btn-lg px-5">
					<i class="bi bi-save-fill"></i> Update Data
				</button>
			</div>
		</form>

	</body>
</div>

<?php

if (isset($_POST["simpan"])) {

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
	
	$ob15	= $_POST["ob15"];
	$ob16	= $_POST["ob16"];
	
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
	$total_input= $_POST["total_input"];
	$total_output= $_POST["total_output"];
	$jtranfusi= $_POST["jtranfusi"];
	$keterangan= $_POST["keterangan"];

	$q  = "update ERM_RI_OBSERVASI_CAIRAN set
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
	ob50	='$ob50',
	total_input = $total_input,
	total_output = $total_output,
	jtranfusi = '$jtranfusi',keterangan='$keterangan'
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

}


?>

