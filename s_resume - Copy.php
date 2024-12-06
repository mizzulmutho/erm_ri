<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$auth_url = "https://api-satusehat.kemkes.go.id/oauth2/v1";
$base_url = "https://api-satusehat.kemkes.go.id/fhir-r4/v1";
$consent_url = "https://api-satusehat.dto.kemkes.go.id/consent/v1";


$id = $_GET["id"];
$row = explode('|',$id);

$noreg = trim($row[0]); 
$kodedokter = trim($row[1]); 
$sbu = trim($row[2]); 
$tglmasuk = trim($row[3]); 
$user = trim($row[4]); 
// $idencounter = trim($row[6]); 

$qres       = "
SELECT      *
FROM            SS_RI_RESUME
WHERE NOREG='$noreg'
";
$hres  = sqlsrv_query($conn, $qres);                

$data_res    = sqlsrv_fetch_array($hres, SQLSRV_FETCH_ASSOC);  

$NOREG = $data_res[NOREG];
$ihsnumber = $data_res[ihsnumber];
$namapasien = $data_res[namapasien];
$iddokter = $data_res[iddokter];
$namadokter = $data_res[namadokter];
$IDENCOUNTER = $data_res[IDENCOUNTER];
$IDFORMULIR_INAP = $data_res[IDFORMULIR_INAP];
$IDKELUHAN_UTAMA = $data_res[IDKELUHAN_UTAMA];

?>            

<!DOCTYPE html> 
<html> 
<head>  
    <title>Resume Medis</title>  
    <link rel="icon" href="favicon.ico">  
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

</head> 
<div id="content"> 
    <div class="container">

        <body onload="document.myForm.pasien_mcu.focus();">
            <form method="POST" name='myForm' action="" enctype="multipart/form-data">
                <br><br>
                <div class="row">
                    <a href='rekap.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
                    &nbsp;&nbsp;
                    <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
                    &nbsp;&nbsp;
                </div>
                <div class="row">
                    <table class='table'>
                        <?php 

                        // $link = $noreg.'|'.$kodedokter.'|'.$sbu.'|'.$tglawal.'|'.$id.'|'.$user;

                        ?>
                        <tr>
                            <td rowspan="2">
                                NOREG<br><?php echo $NOREG;?><br>
                                A/N PASIEN<br><?php echo $namapasien;?><br>
                                DOKTER<br><?php echo $namadokter;?><br>
                            </td>
                            <td align="center"><b>Pendaftaran Kunjungan Rawat Inap</b></td><td><?php echo $IDENCOUNTER;?></td>
                        </tr>
                        <tr>
                            <td>
                                <table>
                                    Fase Pertama<br>
                                    <tr>
                                        <td>Data Formulir Rawat Inap</td>
                                        <td>
                                            01. ANAMNESIS
                                            <table>
                                                <tr>
                                                    <td>
                                                        Keluhan Utama<br><?php echo $IDKELUHAN_UTAMA;?><br>
                                                        <a href='s_sehat_resume_fase2.php?id=<?php echo $noreg.'|'.$sbu.'|1|'.$IDENCOUNTER;?>' target='_blank'><i class="bi bi-box-arrow-right"></i> Kirim</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Riwayat Penyakit</td>
                                                </tr>
                                                <tr>
                                                    <td>Riwayat Alergi</td>
                                                </tr>
                                                <tr>
                                                    <td>Riwayat Pengobatan</td>
                                                </tr>

                                            </table>

                                            <br>
                                            
                                            <br>
                                            <br>
                                            02. PEMERIKSAAN FISIK
                                            <table>
                                                <tr>
                                                    <td>Tingkat Kesadaran</td>
                                                </tr>
                                                <tr>
                                                    <td>Vital Sign</td>
                                                </tr>
                                                <tr>
                                                    <td>Pemeriksaan Fisik</td>
                                                </tr>

                                            </table>


                                            <br>
                                            <?php echo $IDPEMERIKSAAN_FISIK;?>
                                            <br>
                                            <a href='s_sehat_resume_fase2.php?id=<?php echo $noreg.'|'.$sbu.'|2|'.$IDENCOUNTER;?>' target='_blank'><i class="bi bi-box-arrow-right"></i> Kirim</a>
                                            <br>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan='3'><hr></td>
                                    </tr>
                                    <tr>
                                        <td>Diagnosis</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Tindakan</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Tata Laksana (Edukasi)</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Prognosis</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Rencana Tindak Lanjut</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Kondisi saat Meninggalkan RS</td><td><?php ?></td><td>Kirim</td>
                                    </tr>

                                </table>
                            </td>
                            <td>
                                <table>
                                    Fase Lanjutan<br>
                                    <tr>
                                        <td>Rencana Rawat Inap</td><td><?php echo $IDRENCANA_RAWAT;?></td>
                                        <td><a href='s_sehat_resume_fase2.php?id=<?php echo $noreg.'|'.$sbu.'|8|'.$IDENCOUNTER;?>'><i class="bi bi-box-arrow-right"></i> Kirim</a></td>
                                    </tr>
                                    <tr>
                                        <td>Instruksi Medik dan Keperawatan Pasien</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Pemeriksaan Penunjang</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Peresepan Obat</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Pengeluaran Obat</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Perencanaan Pemulangan Pasien</td><td><?php ?></td><td>Kirim</td>
                                    </tr>
                                    <tr>
                                        <td>Cara Keluar dari Rumah Sakit</td><td><?php ?></td><td>Kirim</td>
                                    </tr>

                                </table>
                            </td>
                        </tr>


                    </talbe>
                </div>
            </form>
        </body>
    </div>
</div>
