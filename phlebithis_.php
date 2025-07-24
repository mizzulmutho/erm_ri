<?php 
include "koneksi.php";


if(empty($_GET["id"])) {
    die("ID tidak valid");
}

$tgl        = gmdate("Y-m-d H:i:s", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

// Langkah 5: Error Handling untuk query pertama
$qu = "SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u = sqlsrv_query($conn, $qu);        
if ($h1u === false) {
    die(print_r(sqlsrv_errors(), true));
}
$d1u = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

// Query berikutnya dengan error handling
$qu = "SELECT ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE (ARM_REGISTER.NOREG = '$noreg')";
$h1u = sqlsrv_query($conn, $qu);        
if ($h1u === false) {
    die(print_r(sqlsrv_errors(), true));
}
$d1u = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC);

$tgl        = gmdate("Y-m-d H:i:s", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$params = array($id);
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($d1u['KODEUNIT']);
$KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);

if ($KET1 == 'RSPG'){
    $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
    $alamat = "Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik";
};
if ($KET1 == 'GRAHU'){
    $nmrs = "RUMAH SAKIT GRHA HUSADA";
    $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
};
if ($KET1 == 'DRIYO'){
    $nmrs = "RUMAH SAKIT DRIYOREJO";
    $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
};

$q2       = "select norm,kodedept,nik,nama, CASE WHEN kelamin = 'L' THEN 'Laki-laki' ELSE 'Perempuan' END AS kelamin,alamatpasien,kota,kodekel,tlp,tmptlahir, CONVERT(VARCHAR, tgllahir, 103) as tgllahir, jenispekerjaan,NOKTP,NOBPJS, 
jabatan, (select umur from umur where norm=afarm_mstpasien.norm) as UMUR from Afarm_MstPasien where norm='$norm'";
$hasil2  = sqlsrv_query($conn, $q2);                

$data2    = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC);                      
$kodedept = $data2['kodedept'];

$nama     = $data2['nama'];
$kelamin  = $data2['kelamin'];
$nik = trim($data2['nik']);
$alamatpasien  = $data2['alamatpasien'];
$kota     = $data2['kota'];
$kodekel  = $data2['kodekel'];
$telp     = $data2['tlp'];
$tmptlahir     = $data2['tmptlahir'];
$tgllahir = $data2['tgllahir'];
$jenispekerjaan     = $data2['jenispekerjaan'];
$jabatan  = $data2['jabatan'];
$umur =  $data2['UMUR'];
$noktp =  $data2['NOKTP'];
$nobpjs =  $data2['NOBPJS'];

//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3['tglmasuk'];
$tglkeluar = $data3['tglkeluar'];

//select data

// $qi="SELECT noreg FROM ERM_RI_PHLEBITHIS where noreg='$noreg'";
// $hi  = sqlsrv_query($conn, $qi);        
// $di  = sqlsrv_fetch_array($hi, SQLSRV_FETCH_ASSOC); 
// $regcek = $di['noreg'];

// if(empty($regcek)){
//     $q  = "insert into ERM_RI_PHLEBITHIS(noreg,userid,tglentry,tanggal) values ('$noreg','$user','$tgl','$tgl')";
//     $hs = sqlsrv_query($conn,$q);
// }else{

//     $qe="
//     SELECT *,CONVERT(VARCHAR, tanggal, 23) as tanggal,
//     CONVERT(VARCHAR, tglentry, 103) as tgl_assesment,
//     CONVERT(VARCHAR, tglentry, 8) as jam_assesment
//     FROM ERM_RI_PHLEBITHIS
//     where noreg='$noreg'";
//     $he  = sqlsrv_query($conn, $qe);        
//     $de  = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 

//     $tanggal = $de['tanggal'];
// }
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

    <script>
        CKEDITOR.replace('editor1');
        CKEDITOR.config.width="100%";
        CKEDITOR.config.height="500px"
    </script>

    <link rel="stylesheet" href="jquery-ui.css">
    <script src="jquery-1.10.2.js"></script>
    <script src="jquery-ui.js"></script>

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
    <script>
        $(function() {
            $("#obat").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                    $.ajax({
                        type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_obat.php', //your                         
                        dataType: 'json',
                        data: {
                            postcode: request.term
                        },
                        success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                                response ($.map(data.response, function (item) {
                                    return {
                                        value: item.KODEBARANG + ' - ' + item.NAMABARANG + ' - ' + item.NAMASATUAN
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
<div id="content"> 
    <div class="container">

        <body onload="document.myForm.nama_obat.focus();">
            <font size='2px'>
                <form method="POST" name='myForm' action="" enctype="multipart/form-data">
                    <br>
                    <a href='index.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
                    &nbsp;&nbsp;
                    <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
                    &nbsp;&nbsp;
                    <!-- <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a> -->
                    <button type='submit' name='print' value='print' class="btn btn-info" type="button"><i class="bi bi-printer-fill"></i></button>
                    &nbsp;&nbsp;
                    <a href='#' class='btn btn-success' data-bs-toggle="modal" data-bs-target="#rekapModal"><i class="bi bi-clipboard-data"></i> REKAP DATA</a>
                    <br>
                    <br>
<!--                 <div class="row">
                    <div class="col-12 text-center bg-success text-white"><b>RUMAH SAKIT PETROKIMIA GRESIK</b></div>
                </div>
            -->                
            <div class="row">
            </div>

            <div class="row">
                <div class="col-6">
                    <h5><b><?php echo $nmrs; ?></b></h5>
                    <?php echo $alamat; ?>
                </div>
                <div class="col-6">
                    <?php echo 'NIK : '.$noktp.'<br>'; ?>                    
                    <?php echo 'NAMA LENGKAP : '.$nama.' , NORM :'.$norm.'<br> TGL LAHIR : '.$tgllahir.' UMUR : '.$umur.'<br>'; ?>
                    <?php echo 'L/P : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
                </div>
            </div>
            <hr>

            <div class="container mt-4">
                <div class="text-center mb-4">
                   <h1 style="text-align: center; font-size: 32px; font-weight: bold; color: #0e0718ff;">SURVEILANS PENCEGAHAN PHLEBITIS</h1>
               </div>
               <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><b>Monitoring Saat Pemasangan Akses Intravena</b></h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Jam</label>
                                    <input type="text" name="tgl" value="<?php echo $tgl;?>" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Shift</label>
                                    <select name="shift" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="DINAS PAGI">Satu</option>
                                        <option value="DINAS SIANG">Dua</option>
                                        <option value="DINAS MALAM">Tiga</option>
                                    </select>
                                </div>

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label class="form-label">Lokasi Pemasangan Akses Intravena</label>
                                    <select name="lokasi" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="DEXTRA">Dextra</option>
                                        <option value="SINISTRA">Sinistra</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jenis Vena</label>
                                    <select name="jenis" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="METACARPAL">Metacarpal</option>
                                        <option value="CHEPALIK">Chepalik</option>
                                        <option value="BASILICA">Basilica</option>
                                        <option value="DIGITALIS">Digitalis</option>
                                        <option value="MEDIAN CUBITI">Median Cubiti</option>
                                        <option value="MEDIANA ANTEBRACHIAL">Mediana Antebrachial</option>
                                        <option value="DORSALIS PEDIS">Dorsalis Pedis</option>
                                        <option value="LAIN LAIN">Lain-lain...</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">No Jarum IV Kateter</label>
                                    <select name="jarum" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="18">18</option>
                                        <option value="20">20</option>
                                        <option value="22">22</option>
                                        <option value="24">24</option>
                                        <option value="26">26</option>
                                    </select>
                                </div>

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label class="form-label">Alasan Pemasangan</label>
                                    <select name="alasan" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="PEMASANGAN BARU">Pemasangan Baru</option>
                                        <option value="PHLEBITIS">Phlebitis</option>
                                        <option value="TERCABUT">Tercabut</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Unit Pemasangan</label>
                                    <input type="text" name="unit" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Pemasang</label>
                                    <input type="text" name="namapetugas" class="form-control">
                                </div>

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label class="form-label">Hand Hygiene</label>
                                    <select name="tanya1" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tidak dilakukan re-palpasi setelah desinfeksi</label>
                                    <select name="tanya2" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Menggunakan Transparent Dressing</label>
                                    <select name="tanya3" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><b>Monitoring Perawatan Akses Intravena</b></h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Fiksasi Baik, Bersih, Tidak Basah</label>
                                    <select name="tanya4" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Disinfeksi Sebelum Injeksi</label>
                                    <select name="tanya5" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tidak Ada Bekuan Darah / Clothing</label>
                                    <select name="tanya6" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dokumentasi Tanggal, Jam & Nama Pemasang</label>
                                    <select name="tanya7" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label class="form-label">Skala Penilaian Phlebitis (VIP SCORE)</label>
                                    <select name="skala" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="0">0 - Tidak tampak tanda radang (nyeri, kemerahan, bengkak) pada daerah insersi</option>
                                        <option value="1">1 - Terdapat salah satu tanda berikut: nyeri, kemerahan bengkak</option>
                                        <option value="2">2 - Terdapat dua tanda berikut: nyeri, kemerahan, bengkak</option>
                                        <option value="3">3 - Terdapat semua tanda berikut: nyeri, kemerahan, bengkak</option>
                                        <option value="4">4 - Terdapat semua tanda berikut dan luas: nyeri sepanjang tempat insersi, kemerahan, bengkak, vena teraba mengeras</option>
                                        <option value="5">5 - Terdapat semua tanda berikut dan luas: nyeri sepanjang tempat insersi, kemerahan, bengkak, vena teraba mengeras, keluar pus dan demam</option>
                                    </select>
                                </div>

                                <hr class="my-3">

                                <div class="mb-3">
                                    <label class="form-label">Pelepasan Akses Intravena</label>
                                    <select name="pelepasan" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="YA">YA</option>
                                        <option value="TDK">TIDAK</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Tanggal Pelepasan</label>
                                    <input type="datetime-local" name="tanggal_pelepasan" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Alasan Pelepasan</label>
                                    <select name="alasan_pelepasan" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="PHLEBITHIS">Phlebitis</option>
                                        <option value="TERCABUT">Tercabut</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <input type="submit" name="simpan" value="Simpan" class="btn btn-success px-4">
                </div>
            </form>
        </div>
        <br>
        <hr>
        <div class="table-responsive">
            <font size="2">
                <?php
                $q2 = "
                SELECT *, 
                CONVERT(VARCHAR, tanggal, 103) AS tanggal, 
                CONVERT(VARCHAR, tanggal, 24) AS jam,
                CONVERT(VARCHAR, tglentry, 103) AS tgl_entry,
                CONVERT(VARCHAR, tglentry, 24) AS jam_entry
                FROM ERM_RI_PHLEBITHIS
                WHERE noreg = '$noreg' 
                ORDER BY id DESC";

                $hasil2 = sqlsrv_query($conn, $q2);


                $grouped_data = [];

                while ($data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC)) {
                        $tanggal = $data2['tanggal']; // format dd/mm/yyyy
                        $shift = trim($data2['shift'] ?? ''); 
                        $grouped_data[$tanggal][$shift][] = $data2;
                    }

                    krsort($grouped_data);

                    $shift_order = ['DINAS PAGI', 'DINAS SIANG', 'DINAS MALAM'];

                    foreach ($grouped_data as $tanggal => $shift_group) {
                        echo "<h6 class='mt-4'><i class='bi bi-calendar-event'></i> Tanggal: <strong>$tanggal</strong></h6>";


                        foreach ($shift_order as $shift) {
                            if (!isset($shift_group[$shift])) continue;

                            if ($shift == 'DINAS PAGI') {
                                $bgCard = 'bg-info text-white';
                                $warna = '';
                            } elseif ($shift == 'DINAS SIANG') {
                                $bgCard = 'bg-warning text-dark';
                                $warna = '#F5F7F8';
                            } elseif ($shift == 'DINAS MALAM') {
                                $bgCard = 'bg-dark text-white';
                                $warna = '#F1F8E8';
                            } else {
                                $bgCard = 'bg-light';
                                $warna = '';
                            }

                            echo "
                            <div class='card mb-3'>
                            <div class='card-header $bgCard'>
                            <strong>Shift:</strong> $shift
                            </div>
                            <div class='card-body'>
                            <div class='table-responsive'>
                            <table class='table table-bordered table-striped'>
                            <thead class='table-light'>
                            <tr>
                            <th>No</th>    
                            <th>User Input</th>
                            <th>Tanggal Input</th>
                            <th>Jam Input</th>
                            <th>Lokasi</th>
                            <th>Jenis Vena</th>
                            <th>No Jarum</th>
                            <th>Skala Phlebitis</th>
                            <th>Pelepasan</th>
                            <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>";

                            $i = 1;
                            foreach ($shift_group[$shift] as $data2) {
                                echo "
                                <tr style='background-color:$warna'>
                                <td>$i</td>
                                <td>{$data2['userid']}</td>
                                <td>{$data2['tgl_entry']}</td>
                                <td>{$data2['jam_entry']}</td>
                                <td>{$data2['lokasi']}</td>
                                <td>{$data2['jenis']}</td>
                                <td>{$data2['jarum']}</td>
                                <td>{$data2['skala']}</td>
                                <td>".($data2['pelepasan'] == 'YA' ? 'Ya ('.$data2['alasan_pelepasan'].')' : 'Tidak')."</td>
                                <td>
                                <button type=\"button\" class=\"btn btn-sm btn-warning\" data-bs-toggle=\"modal\" data-bs-target=\"#editModal{$data2['id']}\">
                                <i class=\"bi bi-pencil\"></i>
                                </button>
                                <button type=\"button\" class=\"btn btn-sm btn-danger\" data-bs-toggle=\"modal\" data-bs-target=\"#deleteModal{$data2['id']}\">
                                <i class=\"bi bi-trash\"></i>
                                </button>
                                </td>
                                </tr>
                                ";
                                ?>

                                <div class="modal fade" id="editModal<?php echo $data2['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $data2['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <form method="post" action="<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?php echo $id.'|'.$user; ?>">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel<?php echo $data2['id']; ?>">Edit Data Phlebitis</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body row">                                
                                                    <input type="hidden" name="edit_id" value="<?php echo $data2['id']; ?>">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Shift</label>
                                                        <select name="edit_shift" class="form-select">
                                                            <option value="DINAS PAGI" <?php if($data2['shift']=='DINAS PAGI') echo 'selected'; ?>>DINAS PAGI</option>
                                                            <option value="DINAS SIANG" <?php if($data2['shift']=='DINAS SIANG') echo 'selected'; ?>>DINAS SIANG</option>
                                                            <option value="DINAS MALAM" <?php if($data2['shift']=='DINAS MALAM') echo 'selected'; ?>>DINAS MALAM</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Lokasi</label>
                                                        <select name="edit_lokasi" class="form-select">
                                                            <option value="">--pilih--</option>
                                                            <option value="DEXTRA" <?php if($data2['lokasi'] == 'DEXTRA') echo 'selected'; ?>>Dextra</option>
                                                            <option value="SINISTRA" <?php if($data2['lokasi'] == 'SINISTRA') echo 'selected'; ?>>Sinistra</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Jenis Vena</label>
                                                        <select name="edit_jenis" class="form-select">
                                                            <option value="">--pilih--</option>
                                                            <option value="METACARPAL" <?php if($data2['jenis'] == 'METACARPAL') echo 'selected'; ?>>Metacarpal</option>
                                                            <option value="CHEPALIK" <?php if($data2['jenis'] == 'CHEPALIK') echo 'selected'; ?>>Chepalik</option>
                                                            <option value="BASILICA" <?php if($data2['jenis'] == 'BASILICA') echo 'selected'; ?>>Basilica</option>
                                                            <option value="DIGITALIS" <?php if($data2['jenis'] == 'DIGITALIS') echo 'selected'; ?>>Digitalis</option>
                                                            <option value="MEDIAN CUBITI" <?php if($data2['jenis'] == 'MEDIAN CUBITI') echo 'selected'; ?>>Median Cubiti</option>
                                                            <option value="MEDIANA ANTEBRACHIAL" <?php if($data2['jenis'] == 'MEDIANA ANTEBRACHIAL') echo 'selected'; ?>>Mediana Antebrachial</option>
                                                            <option value="DORSALIS PEDIS" <?php if($data2['jenis'] == 'DORSALIS PEDIS') echo 'selected'; ?>>Dorsalis Pedis</option>
                                                            <option value="LAIN LAIN" <?php if($data2['jenis'] == 'LAIN LAIN') echo 'selected'; ?>>Lain-lain...</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">No Jarum IV Kateter</label>
                                                        <select name="edit_jarum" class="form-select">

                                                            <option value="18" <?php if($data2['jarum'] == '18') echo 'selected'; ?>>18</option>
                                                            <option value="20" <?php if($data2['jarum'] == '20') echo 'selected'; ?>>20</option>
                                                            <option value="22" <?php if($data2['jarum'] == '22') echo 'selected'; ?>>22</option>
                                                            <option value="24" <?php if($data2['jarum'] == '24') echo 'selected'; ?>>24</option>
                                                            <option value="26" <?php if($data2['jarum'] == '26') echo 'selected'; ?>>26</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Alasan Pemasangan</label>
                                                        <select name="edit_alasan" class="form-select">
                                                            <option value="PEMASANGAN BARU" <?php if($data2['alasan'] == 'PEMASANGAN BARU') echo 'selected'; ?>>Pemasangan baru</option>
                                                            <option value="PHLEBITIS" <?php if($data2['alasan'] == 'PHLEBITIS') echo 'selected'; ?>>Phlebitis</option>
                                                            <option value="TERCABUT" <?php if($data2['alasan'] == 'TERCABUT') echo 'selected'; ?>>Tercabut</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Unit Pemasangan</label>
                                                        <input type="text" name="edit_unit" value="<?php echo $data2['unit']; ?>" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Nama Pemasang</label>
                                                        <input type="text" name="edit_namapetugas" value="<?php echo $data2['namapetugas']; ?>" class="form-control">
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Hand Hygiene</label>
                                                        <select name="edit_tanya1" class="form-select">
                                                            <option value="YA" <?php if($data2['tanya1'] == 'YA') echo 'selected'; ?>>YA</option>
                                                            <option value="TDK" <?php if($data2['tanya1'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Tidak dilakukan re-palpasi setelah desinfeksi</label>
                                                        <select name="edit_tanya2" class="form-select">
                                                            <option value="YA" <?php if($data2['tanya2'] == 'YA') echo 'selected'; ?>>YA</option>
                                                            <option value="TDK" <?php if($data2['tanya2'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Menggunakan Transparent Dressing</label>
                                                        <select name="edit_tanya3" class="form-select">
                                                            <option value="YA" <?php if($data2['tanya3'] == 'YA') echo 'selected'; ?>>YA</option>
                                                            <option value="TDK" <?php if($data2['tanya3'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Fiksasi Baik, Bersih, Tidak Basah</label>
                                                        <select name="edit_tanya4" class="form-select">
                                                            <option value="YA" <?php if($data2['tanya4'] == 'YA') echo 'selected'; ?>>YA</option>
                                                            <option value="TDK" <?php if($data2['tanya4'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Disinfeksi Sebelum Injeksi</label>
                                                        <select name="edit_tanya5" class="form-select">
                                                            <option value="YA" <?php if($data2['tanya5'] == 'YA') echo 'selected'; ?>>YA</option>
                                                            <option value="TDK" <?php if($data2['tanya5'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Tidak Ada Bekuan Darah / Clothing</label>
                                                        <select name="edit_tanya6" class="form-select">
                                                            <option value="YA" <?php if($data2['tanya6'] == 'YA') echo 'selected'; ?>>YA</option>
                                                            <option value="TDK" <?php if($data2['tanya6'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <label class="form-label">Skala Penilaian Phlebitis (VIP SCORE)</label>
                                                        <select name="edit_skala" class="form-select">
                                                            <option value="">--pilih--</option>
                                                            <option value="0" <?php if($data2['skala'] == '0') echo 'selected'; ?>>0 - Tidak tampak tanda radang (nyeri, kemerahan, bengkak) pada daerah insersi</option>
                                                            <option value="1" <?php if($data2['skala'] == '1') echo 'selected'; ?>>1 - Terdapat salah satu tanda berikut: nyeri, kemerahan bengkak</option>
                                                            <option value="2" <?php if($data2['skala'] == '2') echo 'selected'; ?>>2 - Terdapat dua tanda berikut: nyeri, kemerahan, bengkak</option>
                                                            <option value="3" <?php if($data2['skala'] == '3') echo 'selected'; ?>>3 - Terdapat semua tanda berikut: nyeri, kemerahan, bengkak</option>
                                                            <option value="4" <?php if($data2['skala'] == '4') echo 'selected'; ?>>4 - Terdapat semua tanda berikut dan luas: nyeri sepanjang tempat insersi, kemerahan, bengkak, vena teraba mengeras</option>
                                                            <option value="5" <?php if($data2['skala'] == '5') echo 'selected'; ?>>5 - Terdapat semua tanda berikut dan luas: nyeri sepanjang tempat insersi, kemerahan, bengkak, vena teraba mengeras, keluar pus dan demam</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="update_phlebitis" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal fade" id="deleteModal<?php echo $data2['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $data2['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel<?php echo $data2['id']; ?>">Konfirmasi Hapus Data</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus data phlebitis ini?</p>
                                                <p><strong>Tanggal:</strong> <?php echo $data2['tanggal']; ?></p>
                                                <p><strong>Shift:</strong> <?php echo $data2['shift']; ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form method="post" action="">
                                                    <input type="hidden" name="delete_id" value="<?php echo $data2['id']; ?>">
                                                    <button type="submit" name="delete_phlebitis" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                            echo "
                            </tbody>
                            </table>
                            </div>
                            </div>
                            </div>";
                        }
                    }
                    ?>
                </font>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
        </form>
    </font>
</body>
</div>
</div>

<div class="modal fade" id="rekapModal" tabindex="-1" aria-labelledby="rekapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xxl"> 
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="rekapModalLabel">REKAP DATA KEJADIAN PHLEBITIS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="text-center mb-3">
                        <h4 class="fw-bold">REKAP DATA KEJADIAN PHLEBITIS BULAN JANUARI 2025</h4>
                    </div>
                    

                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-bordered table-sm" style="font-size: 12px;">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th rowspan="2" class="align-middle text-center" style="min-width: 120px;">UNIT</th>
                                    <th rowspan="2" class="align-middle text-center" style="min-width: 110px;">Jumlah kejadian pemasangan infus</th>
                                    <th rowspan="2" class="align-middle text-center" style="min-width: 70px;">jumlah phlebitis</th>
                                    <th colspan="2" class="text-center">Unit pemasang IV line</th>
                                    <th colspan="4" class="text-center">No jarum IV line</th>
                                    <th colspan="4" class="text-center">Lama Pemasangan IV line</th>
                                    <th colspan="4" class="text-center">USIA</th>
                                </tr>
                                <tr>
                                    <th class="text-center" style="min-width: 60px;">IGD</th>
                                    <th class="text-center" style="min-width: 80px;">Rawat inap</th>
                                    <th class="text-center" style="min-width: 50px;">18</th>
                                    <th class="text-center" style="min-width: 50px;">20</th>
                                    <th class="text-center" style="min-width: 50px;">22</th>
                                    <th class="text-center" style="min-width: 50px;">24</th>
                                    <th class="text-center" style="min-width: 80px;">0-24 Jam</th>
                                    <th class="text-center" style="min-width: 80px;">>24-48 jam</th>
                                    <th class="text-center" style="min-width: 80px;">>48-72 jam</th>
                                    <th class="text-center" style="min-width: 80px;">>72 jam</th>
                                    <th class="text-center" style="min-width: 80px;">Neo (<1 Bln) </th>
                                        <th class="text-center" style="min-width: 100px;">Anak (1 bln-18 th)</th>
                                        <th class="text-center" style="min-width: 100px;">Dewasa (19-60)</th>
                                        <th class="text-center" style="min-width: 80px;">Lansia (>60 Thn)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">Rawin Lt 1</td>
                                        <td class="text-center">269</td>
                                        <td class="text-center">73</td>
                                        <td class="text-center">52</td>
                                        <td class="text-center">21</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">21</td>
                                        <td class="text-center">29</td>
                                        <td class="text-center">19</td>
                                        <td class="text-center">18</td>
                                        <td class="text-center">40</td>
                                        <td class="text-center">11</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">12</td>
                                        <td class="text-center">43</td>
                                        <td class="text-center">18</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td class="text-start fw-bold"></td>
                                        <td class="text-center fw-bold">27%</td>
                                        <td class="text-center"></td>
                                        <td class="text-center fw-bold">71.2%</td>
                                        <td class="text-center fw-bold">28.8%</td>
                                        <td class="text-center fw-bold">5%</td>
                                        <td class="text-center fw-bold">29%</td>
                                        <td class="text-center fw-bold">40%</td>
                                        <td class="text-center fw-bold">26%</td>
                                        <td class="text-center fw-bold">25%</td>
                                        <td class="text-center fw-bold">55%</td>
                                        <td class="text-center fw-bold">15%</td>
                                        <td class="text-center fw-bold">5%</td>
                                        <td class="text-center fw-bold">0%</td>
                                        <td class="text-center fw-bold">16%</td>
                                        <td class="text-center fw-bold">59%</td>
                                        <td class="text-center fw-bold">25%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-bordered table-sm" style="font-size: 12px;">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th rowspan="2" class="align-middle text-center" style="min-width: 120px;">Unit</th>
                                        <th colspan="4" class="text-center">Jenis IV Terapi</th>
                                        <th colspan="5" class="text-center">Lokasi IV Line</th>
                                        <th colspan="5" class="text-center">Skala Phlebitis</th>
                                        <th colspan="3" class="text-center">Merk IV Canul yg mengalami phlebitis</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="min-width: 70px;">Antibiotik</th>
                                        <th class="text-center" style="min-width: 70px;">Hipertonis</th>
                                        <th class="text-center" style="min-width: 70px;">Lain-lain</th>
                                        <th class="text-center" style="min-width: 80px;">Metacharpal</th>
                                        <th class="text-center" style="min-width: 80px;">Chepalika</th>
                                        <th class="text-center" style="min-width: 70px;">Basilika</th>
                                        <th class="text-center" style="min-width: 80px;">Medcubiti</th>
                                        <th class="text-center" style="min-width: 100px;">Med. Antebrachial</th>
                                        <th class="text-center" style="min-width: 70px;">Lain-lain</th>
                                        <th class="text-center" style="min-width: 50px;">1</th>
                                        <th class="text-center" style="min-width: 50px;">2</th>
                                        <th class="text-center" style="min-width: 50px;">3</th>
                                        <th class="text-center" style="min-width: 50px;">4</th>
                                        <th class="text-center" style="min-width: 50px;">5</th>
                                        <th class="text-center" style="min-width: 70px;">Heuer</th>
                                        <th class="text-center" style="min-width: 70px;">Wellcare</th>
                                        <th class="text-center" style="min-width: 70px;">Lain2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">Rawin Lt. 1</td>
                                        <td class="text-center">25</td>
                                        <td class="text-center">12</td>
                                        <td class="text-center">53</td>
                                        <td class="text-center">66</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">15</td>
                                        <td class="text-center">56</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">70</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">0</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td class="text-start fw-bold"></td>
                                        <td class="text-center fw-bold">34%</td>
                                        <td class="text-center fw-bold">16%</td>
                                        <td class="text-center fw-bold">73%</td>
                                        <td class="text-center fw-bold">90%</td>
                                        <td class="text-center fw-bold">3%</td>
                                        <td class="text-center fw-bold">0%</td>
                                        <td class="text-center fw-bold">1%</td>
                                        <td class="text-center fw-bold">3%</td>
                                        <td class="text-center fw-bold">3%</td>
                                        <td class="text-center fw-bold">21%</td>
                                        <td class="text-center fw-bold">77%</td>
                                        <td class="text-center fw-bold">3%</td>
                                        <td class="text-center fw-bold">0%</td>
                                        <td class="text-center fw-bold">0%</td>
                                        <td class="text-center fw-bold">90%</td>
                                        <td class="text-center fw-bold">4%</td>
                                        <td class="text-center fw-bold">0%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $errors = [];
    if (isset($_POST["simpan"])) {
        $tanggal = $_POST["tgl"];
        $shift = $_POST["shift"];
        $lokasi = $_POST["lokasi"];
        $jenis = $_POST["jenis"];
        $jarum = $_POST["jarum"];
        $alasan = $_POST["alasan"];
        $unit = $_POST["unit"];
        $namapetugas = $_POST["namapetugas"];
        $tanya1 = $_POST["tanya1"];
        $tanya2 = $_POST["tanya2"];
        $tanya3 = $_POST["tanya3"];
        $tanya4 = $_POST["tanya4"];
        $tanya5 = $_POST["tanya5"];
        $tanya6 = $_POST["tanya6"];
        $tanya7 = $_POST["tanya7"];
        $skala = $_POST["skala"];
        $pelepasan = $_POST["pelepasan"];
        $tanggal_pelepasan = $_POST["tanggal_pelepasan"];
        $alasan_pelepasan = $_POST["alasan_pelepasan"];

        if (!empty($tanggal_pelepasan)) {
            $tanggal_pelepasan = str_replace("T", " ", $tanggal_pelepasan) . ":00";
        }

        $q_insert = "INSERT INTO ERM_RI_PHLEBITHIS 
        (noreg, userid, tglentry, tanggal, shift, lokasi, jenis, jarum, alasan, unit, namapetugas, 
            tanya1, tanya2, tanya3, tanya4, tanya5, tanya6, tanya7, skala, pelepasan, tanggal_pelepasan, alasan_pelepasan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params_insert = [
            $noreg, $user, $tgl, $tanggal, $shift, $lokasi, $jenis, $jarum, $alasan, $unit, $namapetugas,
            $tanya1, $tanya2, $tanya3, $tanya4, $tanya5, $tanya6, $tanya7, $skala, $pelepasan,
            $tanggal_pelepasan, $alasan_pelepasan
        ];

        $hs = sqlsrv_query($conn, $q_insert, $params_insert);

        if ($hs === false) {
            echo "<pre>"; print_r(sqlsrv_errors()); echo "</pre>";
            exit();
        }

        // header("Location: ".$_SERVER['PHP_SELF']."?id=$id|$user");
        // exit();


        $eror = "Berhasil Simpan";

        // header("Location: raber.php?id=$id|$user");
        // exit; 

        echo "
        <script>
        alert('".$eror."');
        window.location.replace('phlebithis.php?id=$id|$user');
        </script>
        ";
    }

    elseif (isset($_POST["update_phlebitis"])) {
        $edit_id = $_POST["edit_id"];
        $shift = $_POST["edit_shift"];
        $lokasi = $_POST["edit_lokasi"];
        $jenis = $_POST["edit_jenis"];
        $jarum = $_POST["edit_jarum"];
        $alasan = $_POST["edit_alasan"] ?? null; 
        $unit = $_POST["edit_unit"] ?? null;
        $namapetugas = $_POST["edit_namapetugas"] ?? null;
        $tanya1 = $_POST["edit_tanya1"] ?? null;
        $tanya2 = $_POST["edit_tanya2"] ?? null;
        $tanya3 = $_POST["edit_tanya3"] ?? null;
        $tanya4 = $_POST["edit_tanya4"] ?? null;
        $tanya5 = $_POST["edit_tanya5"] ?? null;
        $tanya6 = $_POST["edit_tanya6"] ?? null;
        $tanya7 = $_POST["edit_tanya7"] ?? null;
        $skala = $_POST["edit_skala"] ?? null;
        $pelepasan = $_POST["edit_pelepasan"] ?? null;
        $tanggal_pelepasan = $_POST["edit_tanggal_pelepasan"] ?? null;
        $alasan_pelepasan = $_POST["edit_alasan_pelepasan"] ?? null;

        if (!empty($tanggal_pelepasan)) {
            $tanggal_pelepasan = str_replace("T", " ", $tanggal_pelepasan) . ":00";
        }

        $q_update = "UPDATE ERM_RI_PHLEBITHIS SET 
        shift = ?, 
        lokasi = ?, 
        jenis = ?, 
        jarum = ?, 
        alasan = ?, 
        unit = ?, 
        namapetugas = ?, 
        tanya1 = ?, 
        tanya2 = ?, 
        tanya3 = ?, 
        tanya4 = ?, 
        tanya5 = ?, 
        tanya6 = ?, 
        tanya7 = ?, 
        skala = ?, 
        pelepasan = ?, 
        tanggal_pelepasan = ?, 
        alasan_pelepasan = ?,
        tglentry = ?
        WHERE id = ?";

        $current_timestamp = gmdate("Y-m-d H:i:s", time()+60*60*7);

        $params_update = array(
            $shift, 
            $lokasi, 
            $jenis, 
            $jarum, 
            $alasan, 
            $unit, 
            $namapetugas,
            $tanya1, 
            $tanya2, 
            $tanya3, 
            $tanya4, 
            $tanya5, 
            $tanya6, 
            $tanya7,
            $skala, 
            $pelepasan, 
            $tanggal_pelepasan, 
            $alasan_pelepasan,
            $current_timestamp,
            $edit_id
        );

        $stmt = sqlsrv_query($conn, $q_update, $params_update);

        if ($stmt === false) {
            echo "<div class='alert alert-danger'>";
            echo "Error updating record: ";
            print_r(sqlsrv_errors());
            echo "</div>";
        } else {
            $redirect_url = basename($_SERVER['PHP_SELF']) . "?id=" . $id . "|" . $user;
            echo "<script>window.location.href='$redirect_url';</script>";
            exit();
        }
    }

    elseif (isset($_POST["delete_phlebitis"])) {
        $delete_id = $_POST["delete_id"];

        $q_delete = "DELETE FROM ERM_RI_PHLEBITHIS WHERE id = ?";
        $params_delete = array($delete_id);

        $stmt = sqlsrv_query($conn, $q_delete, $params_delete);

        if ($stmt === false) {
            echo "<div class='alert alert-danger'>";
            echo "Error deleting record: ";
            print_r(sqlsrv_errors());
            echo "</div>";
        } else {
            $redirect_url = basename($_SERVER['PHP_SELF']) . "?id=" . $id . "|" . $user;
            echo "<script>window.location.href='$redirect_url';</script>";
            exit();
        }
    }
?>