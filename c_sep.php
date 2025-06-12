<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tanggal    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$getid = $_GET["id"];
$row = explode('|',$getid);
$id = $row[0]; 
$user = $row[1]; 
$nosep = $row[2]; 
$noreg = $row[3]; 
$file_to_display = $row[4]; 

$qu="SELECT noreg,norm,sbu,kodeunit FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = $d1u['noreg'];
$sbu = $d1u['sbu'];
$norm = $d1u['norm'];
$kodeunit = $d1u['kodeunit'];

if ($sbu == "RSPG") {
  $consID = "30161"; // PROD
  $consSecret = "4uP1D898FE";
  $user_key = "1b2256e07eb21a343f934eb522bb6a59";
  $user_key_antrol= "8a4acfe012329f428ced3f2cc57dd419";
  $ppkPelayanan = "1302R002";
  $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
  $alamat = "
  Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik
  <br>
  IGD : 031-99100118 Telp : 031-3978658<br>
  Email : sbu.rspg@gmail.com
  ";
  $logo = "logo/rspg.png";
} else if ($sbu === "GRAHU") {
  $consID = "9497"; //PROD
  $consSecret = "3aV1C3CB13";
  $user_key = "cb3d247a6b9443d68f9567e0d86fb422";
  $user_key_antrol= "77ce0cdd4d786c2e0029a45f9e97759d";
  $ppkPelayanan = "0205R013";
  $nmrs = "RUMAH SAKIT GRHA HUSADA";
  $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
  $logo = "logo/grahu.png";
} else if ($sbu === "DRIYO") {
  $consID = "3279"; //PROD
  $consSecret = "6uR2F891A4";
  $user_key = "918bda20e3056ae0d4167e698d8adb83";
  $user_key_antrol= "f9b587583c0232c2bd36d27aad8f9856";
  $ppkPelayanan = "0205R014";
  $nmrs = "RUMAH SAKIT DRIYOREJO";
  $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
  $logo = "logo/driyo.png";
} else {
	$consID = "";
	$consSecret = "";
	$user_key = "";
	$ppkPelayanan = "";
}


date_default_timezone_set('UTC');
$timestamp = time();

$data = $consID.'&'.$timestamp;
$key = $consID.$consSecret.$timestamp;

$signature = hash_hmac('sha256', $data, $consSecret, true);
$encodedSignature = base64_encode($signature);

$url = "https://apijkn.bpjs-kesehatan.go.id/vclaim-rest"; //PROD
$url_antrol = "https://apijkn.bpjs-kesehatan.go.id/antreanrs";

$tglentry = gmdate("d-m-Y H:i:s", time()+60*60*7);

$datetime       = gmdate("Y-m-d H:i:s", time()+60*60*7);
$date       = gmdate("Y-m-d", time()+60*60*7);

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$tglinput=gmdate("Y-m-d", time()+60*60*7);

$bulan  =substr($tglsekarang,5,2);
$tanggal=substr($tglsekarang,8,3);
$tahun  =substr($tglsekarang,0,4);


$link_tampil="/SEP/".$nosep;
$url_tampil=$url.$link_tampil;

$ch = curl_init( $url_tampil);

$options = array(
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => array('Content-type: application/json','X-Cons-id: '.$consID,'X-Timestamp: '.$timestamp,'X-Signature: '.$encodedSignature,'user_key: '.$user_key)
);
curl_setopt_array( $ch, $options ); //Setting curl options
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);

$result =  curl_exec($ch); //Getting jSON result string

$arr = json_decode($result,true);

$string = $arr['response'];
require_once 'vendor/autoload.php';      
$encrypt_method = 'AES-256-CBC';
$key_hash = hex2bin(hash('sha256', $key));
$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);
$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
$output =  \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
$arr = json_decode($output,true);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>SEP</title>
	<style>
		body{
			width : 98%;
			font-size : 10px;
			font-family: arial;
		}
		table{
			width: 100%
		}
		table, tr, td{
			border : none;
			border-collapse: collapse;
		}
		td{
			padding: 2px;
			/* height : 16px; */
			vertical-align: top
		}
		.bordered_table, .bordered_table tr, .bordered_table td{
			border : 1px solid black;
			border-collapse : collapse;
		}
		@media print {
			.header, .hide {
				visibility: hidden
			}
			.tanggal-cetak{
				visibility: hidden;
			}
			@page {
				size: A4;
				margin: 0.8cm;
				margin-top: 0.5cm;
			}
		}
	</style>
</head>
<body onload="">
	<table  border="1">
		<tr>
			<td style="border:1px solid #000;" colspan="4" align="center">
				<table>
					<tr>
						<td width="90%">
							<table id="kopjudul" width="150%">
								<tbody>
									<tr>
										<td width="10%" align="right">
											<?php
											$image='logo_bpjs.JPG';
											?>    
											<img src="<?php echo $logo; ?>" alt="" width="100"> 
										</td>
										<td colspan="3">
											<table>
												<tbody><tr>
													<td align="center">
														<font size="3">
															<b style="">SURAT ELEGIBILITAS PESERTA</b><br>
															<?php echo $nmRS; ?>
														</font></td>
													</tr>
												</tbody></table>
											</td>
										</tr>
									</tbody>
								</table>            
							</td>
							<td style="text-align: center; font-size:55px">
								<b>
								</b>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border:1px solid #000;" colspan="4">
					<table>
						<tr>
							<td width="50%">
								<table>
									<tr>
										<td width="32%">No.SEP</td>
										<td width="3%">:</td>
										<td><?php echo $arr[noSep]; ?></td>
									</tr>
									<tr>
										<td width="32%">Tgl.SEP</td>
										<td width="3%">:</td>
										<td><?php echo $arr[tglSep]; ?></td>
									</tr>
									<tr>
										<td width="32%">No Kartu</td>
										<td width="3%">:</td>
										<td><?php echo $arr[peserta][noKartu]; ?></td>
									</tr>
									<tr>
										<td width="32%">Nama Peserta</td>
										<td width="3%">:</td>
										<td><?php echo $arr[peserta][nama]; ?></td>
									</tr>
									<tr>
										<td width="32%">Tgl Lahir</td>
										<td width="3%">:</td>
										<td><?php echo $arr[peserta][tglLahir]; ?></td>
									</tr>
									<tr>
										<td width="32%">No. Telepon</td>
										<td width="3%">:</td>
										<td><?php echo $arr_peserta[peserta][mr][noTelepon]; ?></td>
									</tr>
									<tr>
										<td width="32%">Sub/Spesialis</td>
										<td width="3%">:</td>
										<td><?php echo $arr[poli]; ?></td>
									</tr>
									<tr>
										<td width="32%">Dokter</td>
										<td width="3%">:</td>
										<td><?php echo $arr[dpjp][nmDPJP]; ?></td>
									</tr>
									<tr>
										<td width="32%">Faskes Rujukan</td>
										<td width="3%">:</td>
										<td><?php echo $faskesperujuk; ?></td>
									</tr>
									<tr>
										<td width="32%">Diagnosa Awal</td>
										<td width="3%">:</td>
										<td><?php echo $arr[diagnosa]; ?></td>
									</tr>
									<tr>
										<td width="32%">Catatan</td>
										<td width="3%">:</td>
										<td><?php echo $arr[catatan]; ?></td>
									</tr>                
								</table>
							</td>
							<td>
								<table>
									<tr><td>&nbsp;</td></tr>
									<tr>
										<td width="25%">Peserta</td>
										<td width="3%">:</td>
										<td><?php echo $arr[peserta][jnsPeserta]; ?></td>
									</tr>

									<tr>
										<td width="32%">Jns. Rawat</td>
										<td width="3%">:</td>
										<td><?php echo $arr[jnsPelayanan]; ?></td>
									</tr>
									<tr>
										<td width="32%">Jns. Kunjungan</td>
										<td width="3%">:</td>
										<td><?php echo $jns_kunjungan; ?></td>
									</tr>
									<tr>
										<td width="32%">Poli Perujuk</td>
										<td width="3%">:</td>
										<td><?php echo $arr2[rujukan][poliRujukan][nama]; ?></td>
									</tr>
									<tr>
										<td width="32%">Kls. Hak</td>
										<td width="3%">:</td>
										<td><?php echo $kls_hak; ?></td>
									</tr>
									<tr>
										<td width="32%">Kls. Rawat</td>
										<td width="3%">:</td>
										<td><?php echo $arr[klsRawat][klsRawatHak]; ?></td>
									</tr>
									<tr>
										<td width="32%">Penjamin</td>
										<td width="3%">:</td>
										<td><?php echo $arr[penjamin]; ?></td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>

							<td><small><br><?php echo "*Saya menyetujui BPJS Kesehatan menggunakan infomasi medis pasien jika diperlukan."; ?></small>
								<br><small><?php echo "*SEP Bukan sebagai bukti penjaminan peserta."; ?></small>
								<br><small>Cetakan ke 1 <?php echo $tglentry; ?> wib</small>
							</td>
							<td>
								<small>Pasien/Keluarga Pasien</small>
								<br>
								<br>
								<?php
								QRcode::png($arr[peserta][noKartu], "sep_nobpjs.png", "L", 2, 2);   
								echo "<left><img src='sep_nobpjs.png'></left>";
								?>
							</td>
						</tr>

					</table>
				</td>
			</tr>
		</table>
	</table>



</body>
</html>