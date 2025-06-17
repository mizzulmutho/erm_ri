<?php 
include ("koneksi.php");

error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

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

$qu="SELECT * FROM  V_ERM_RI_KEADAAN_UMUM where noreg='$noreg' and tensi is not null";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$kesadaran = $d1u['kesadaran'];
$e = $d1u['e'];
$v = $d1u['v'];
$m = $d1u['m'];
// $suhu = $d1u['suhu'];
// $suhu 			= str_replace(",",".",$suhu);

$tensi = $d1u['tensi'];
// $nadi = $d1u['nadi'];
$ket_nadi = $d1u['ket_nadi'];
$nafas = $d1u['nafas'];
$spo = $d1u['spo'];
$bb = $d1u['bb'];
$bb = str_replace("kg","",$bb);

$tb = $d1u['tb'];
$tb = str_replace("cm","",$tb);

if($bb=='-'){
	$bb=0;
}
if($tb=='-'){
	$tb=0;
}

//ambil resep



//SIMPAN
if (isset($_POST["simpan"])) {

	$tglinput	= trim($_POST["tglinput"]);

	$jam1a= $_POST["jam1a"];
	$jam1b= $_POST["jam1b"];
	$jam2a= $_POST["jam2a"];
	$jam2b= $_POST["jam2b"];

	$ob14	= $jam1a.' '.$jam1b;
	$ob17	= $jam2a.' '.$jam2b;

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

	$lanjut="Y";


	if(empty($user)){
		$eror='User Tidak Boleh Kosong !!!';
		$lanjut='T';
	}

	if($lanjut == 'Y'){


		$q  = "insert into ERM_RI_OBSERVASI_CAIRAN
		(norm,noreg,tglinput,userinput) 
		values 
		('$norm','$noreg','$tglinput','$user')";
		$hs1 = sqlsrv_query($conn,$q);

		if($hs1){
			$qu="SELECT TOP(1)id FROM ERM_RI_OBSERVASI_CAIRAN where noreg='$noreg' order by id desc";
			$h1u  = sqlsrv_query($conn, $qu);        
			$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
			$id_observasi = trim($d1u['id']);

			$ob27=0;//total balance_cairan

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
			jtranfusi = '$jtranfusi'
			where id='$id_observasi'
			";
			$hs = sqlsrv_query($conn,$q);

			if($hs){
				$eror = "Success";

				echo "
				<script>
				alert('".$eror."');
				window.location.replace('listobservasi_cairan.php?id=$id|$user');
				</script>
				";



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

<!DOCTYPE html> 
<html> 
<head>  
	<title>eRM-RI</title>  
	<link rel="icon" href="favicon.ico">  

	<!-- Bootstrap CSS & Icons CDN -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


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

</head> 



<div class="container mt-4">
	<?php include "header_soap.php"; ?>

	<form method="POST" name='myForm' action="" enctype="multipart/form-data">
		<div class="mb-3">
			<a href='index.php?id=<?php echo $id."|".$user;?>' class='btn btn-warning btn-sm'><i class="bi bi-x-circle"></i> Close</a>
			<a href='' class='btn btn-success btn-sm'><i class="bi bi-arrow-clockwise"></i> Refresh</a>
			<a href='observasi.php?id=<?php echo $id."|".$user;?>' class='btn btn-success btn-sm'><i class="bi bi-graph-up"></i> Monitoring EWS</a>

			<a href='observasi_cairan2.php?id=<?php echo $id."|".$user;?>' class='btn btn-success btn-sm'><i class="bi bi-droplet"></i> Monitoring Cairan</a>
		</div>

		<h4><i class="bi bi-droplet-half text-primary"></i> Monitoring Cairan</h4>

		<div class="form-floating mb-3">
			<a href='listobservasi_cairan.php?id=<?php echo $id."|".$user;?>' class='btn btn-success btn-sm'><i class="bi bi-list"></i> List Data</a>
		</div>


		<div class="form-floating mb-3">
			<input type="text" class="form-control" name="tglinput" value="<?php echo $tglinput;?>">
			<label><i class="bi bi-calendar-event"></i> Tanggal/Jam Input</label>
		</div>
		
		<!-- Input Card -->
		<div class="card my-3">
			<div class="card-header bg-primary text-white">
				<i class="bi bi-input-cursor-text me-2"></i> Input
			</div>
			<div class="card-body">
				<div class="row g-3 mb-3">
					<div class="col-md-4">
						<label class="form-label">Infus (cc)</label>
						<input type="number" class="form-control input-cairan" name="ob12" id="infus" placeholder="0" step="any">
					</div>
					<div class="col-md-4">
						<label class="form-label">Tetesan (/menit)</label>
						<input type="text" class="form-control" name="ob13">
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label">Nama Infus</label>
					<input type="text" class="form-control" name="ob29" placeholder="Isikan nama infus">
				</div>

				<div class="row g-3 mb-3">
					<div class="col-md-4">
						<label class="form-label">Transfusi (cc)</label>
						<input type="number" class="form-control input-cairan" name="ob15" id="transfusi" placeholder="0" step="any">
					</div>
					<div class="col-md-4">
						<label class="form-label">Tetesan (/menit)</label>
						<input type="text" class="form-control" name="ob16">
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label">Jenis Transfusi</label>
					<input type="text" class="form-control" name="jtranfusi" list="jenisTransfusiList" placeholder="Pilih atau isikan jenis transfusi">
					<datalist id="jenisTransfusiList">
						<option value="WB"></option>
						<option value="PRC"></option>
						<option value="TC"></option>
						<option value="FFP"></option>
					</datalist>
				</div>


				<div class="row g-3 mb-3">
					<div class="col-md-6">
						<label class="form-label">Minum (cc)</label>
						<input type="number" class="form-control input-cairan" name="ob19" id="minum" placeholder="0" step="any">
					</div>
					<div class="col-md-6">
						<label class="form-label">Keterangan</label>
						<input type="text" class="form-control" name="ob18">
					</div>

				</div>
			</div>
		</div>

		<!-- Output Card -->
		<div class="card mb-3">
			<div class="card-header bg-success text-white">
				<i class="bi bi-box-arrow-up-right me-2"></i> Output
			</div>
			<div class="card-body">
				<div class="row g-3 mb-3">
					<div class="col-md-4">
						<label class="form-label">Muntah (cc)</label>
						<input type="number" class="form-control output-cairan" name="ob20" id="muntah" placeholder="0" step="any">
					</div>
					<div class="col-md-4">
						<label class="form-label">Feses (cc)</label>
						<input type="number" class="form-control output-cairan" name="ob21" id="bab" placeholder="0" step="any">
					</div>
					<div class="col-md-4">
						<label class="form-label">Urine (cc)</label>
						<input type="number" class="form-control output-cairan" name="ob22" id="urine" placeholder="0" step="any">
					</div>
				</div>

				<div class="row g-3 mb-3">
					<div class="col-md-4">
						<label class="form-label">IWL (cc)</label>
						<input type="number" class="form-control output-cairan" name="ob23" id="iwl" placeholder="0" step="any">
					</div>
					<div class="col-md-4">
						<label class="form-label">NGT (cc)</label>
						<input type="number" class="form-control output-cairan" name="ob24" id="ngt" placeholder="0" step="any">
					</div>
					<div class="col-md-4">
						<label class="form-label">Drain (cc)</label>
						<input type="number" class="form-control output-cairan" name="ob25" id="drain" placeholder="0" step="any">
					</div>
				</div>

				<div class="mb-3">
					<label class="form-label">Perdarahan (cc)</label>
					<input type="number" class="form-control output-cairan" name="ob26" id="perdarahan" placeholder="0" step="any">
				</div>
			</div>
		</div>

		<!-- Bagian Balance Cairan -->
		<div class="card mb-4">
			<div class="card-header bg-dark text-white">
				<i class="bi bi-calculator-fill me-2"></i> Total
			</div>
			<div class="card-body row g-3">
				<div class="col-md-4">
					<label class="form-label fw-bold text-primary">Total Input (cc)</label>
					<input type="number" class="form-control" id="total-input" name="total_input" oninput="updateBalance()" placeholder="otomatis atau manual" step="any" readonly>
				</div>
				<div class="col-md-4">
					<label class="form-label fw-bold text-success">Total Output (cc)</label>
					<input type="number" class="form-control" id="total-output" name="total_output" oninput="updateBalance()" placeholder="otomatis atau manual" step="any" readonly>
				</div>
			</div>
		</div>

		<div class="card-footer text-center mt-4">
			<button type="submit" name="simpan" value="simpan" class="btn btn-info btn-lg px-5">
				<i class="bi bi-save-fill"></i> Simpan Monitoring Cairan
			</button>
		</div>
		<br><br>

	</div>
</form>

<!-- Script Otomatis -->
<script>
	let isManualInput = false;
	let isManualOutput = false;

	function sumValues(selector) {
		let total = 0;
		document.querySelectorAll(selector).forEach(field => {
			let value = parseFloat(field.value);
			if (!isNaN(value)) total += value;
		});
		return total;
	}

	function autoUpdateTotals() {
		if (!isManualInput) {
			const totalInput = sumValues('.input-cairan');
			document.getElementById('total-input').value = totalInput.toFixed(2);
		}

		if (!isManualOutput) {
			const totalOutput = sumValues('.output-cairan');
			document.getElementById('total-output').value = totalOutput.toFixed(2);
		}

		updateBalance();
	}

	function updateBalance() {
		const input = parseFloat(document.getElementById('total-input').value) || 0;
		const output = parseFloat(document.getElementById('total-output').value) || 0;
		const balance = input - output;
		document.getElementById('balance').value = balance.toFixed(2);
	}

  // Deteksi jika user edit manual total input/output
  document.getElementById('total-input').addEventListener('input', () => {
  	isManualInput = true;
  	updateBalance();
  });

  document.getElementById('total-output').addEventListener('input', () => {
  	isManualOutput = true;
  	updateBalance();
  });

  // Setiap perubahan pada input/output cairan → auto jumlah
  document.querySelectorAll('.input-cairan, .output-cairan').forEach(field => {
  	field.addEventListener('input', autoUpdateTotals);
  });

  // Jalankan saat halaman pertama kali dibuka
  autoUpdateTotals();
</script>

