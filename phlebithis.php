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

$qu = "SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u = sqlsrv_query($conn, $qu);        
if ($h1u === false) {
    die(print_r(sqlsrv_errors(), true));
}
$d1u = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=chevron_left,chevron_right" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
       body {
            font-family: 'Montserrat', sans-serif;
            background: #f9fafb;
        }
        /* Scrollbar for modal tables */
        .modal-scroll {
            max-height: 400px;
            overflow-y: auto;
        }
        /* Hover dengan warna biru muda */
        .table-hover tbody tr:hover {
            background-color: #00000005 !important;
        }

        /* Border antar baris abu-abu putih */
        .table-bordered tbody tr {
            border-bottom: 1px solid #eef1f3;
        }

        /* Hilangkan border antar kolom */
        .table-bordered td,
        .table-bordered th {
            border-left: none;
            border-right: none;
        }

        /* Tampilan teks */
        table td, table th {
            font-size: 0.9rem;
            vertical-align: middle;
            color: #333;
        }

        /* Container tabel dengan sudut bulat dan bayangan */
        .table-responsive {
            border-radius: 1rem;
            background-color: #f9fafb; /* ganti dari #fff */
            overflow: hidden;
        }

        /* Tabel juga pakai warna bg yang sama */
        .table {
            background-color: #f9fafb !important;
        }

        .table td, .table th {
            padding: 0.75rem 0.4rem; /* Vertical: ~5px, Horizontal: ~6px */
        }


        /* Tombol dengan warna kontras */
        .btn-edit {
            background-color: #eba800ff;
            color: black;
        }
        .btn-danger {
            background-color: #ff2323ff;
            border: none;
        }
        .btn-edit:hover {
            transform: scale(1.08);
            background-color: #ffb803ff;
            box-shadow: 0 8px 12px rgba(255, 139, 7, 0.44);
            color: black;
        }
        .btn-edit:active {
            transform: scale(0.97);
            box-shadow: 0 0 8px #ffb803ff, 0 0 10px #ffb803ff, 0 0 20px #ffb803ff;
            color: #ffb803ff;
        }

        .btn-danger:hover {
            transform: scale(1.08);
            background-color: #ff2323ff;
            box-shadow: 0 8px 12px rgba(255, 7, 7, 0.44);
        }

        thead {
            background-color: #f9fafb !important;
        }
        thead th {
            font-weight: 600;
        }
          .modal-dialog.custom-wide {
            max-width: 95% !important;
        }
        .bg-gradient-green-blue {
            background: linear-gradient(to right, #44da67ff, #3546daff);
            color: white;
        }
        .custom-glow-btn {
            background: linear-gradient(to right, #001aadff, #1e55ecff);
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .custom-glow-btn:hover {
            transform: scale(1.08);
            background-color: #001aadff;
           box-shadow: 0 8px 12px rgba(7, 32, 255, 0.44);
        }

        .custom-glow-btn:active {
            transform: scale(0.97);
            box-shadow: 0 0 8px #001aadff, 0 0 10px #1e55ecff, 0 0 20px #1e55ecff;
        }
</style>

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
                    <a href='rekap_phlebitis.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-success'>
                        <i class="bi bi-clipboard-data"></i> REKAP DATA
                    </a>

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
                   <h1 style="text-align: center; font-size: 32px; font-weight: bold; color: #0012b3ff;">SURVEILANS PENCEGAHAN PHLEBITIS</h1>
               </div>
        <form method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4 border-0 shadow-lg bg-white" style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                        <div class="card-header bg-gradient-green-blue">
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
                                    <select name="unit" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="igd">IGD</option>
                                        <option value="rawat_inap">RAWAT INAP</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nama Pemasang</label>
                                    <input type="text" name="namapetugas" class="form-control">
                                </div>

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
                    <div class="card mb-4 border-0 shadow-lg bg-white" style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                        <div class="card-header bg-gradient-green-blue">
                                <h6 class="mb-0"><b>Monitoring Perawatan Akses Intravena</b></h6>
                            </div>
                             <div class="card-body">
                            

                                 <div class="mb-3">
                                    <label class="form-label">Jenis IV Terapi</label>
                                    <select name="terapi1" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="antibiotik">Antibiotik</option>
                                        <option value="hipertonis">Hipertonis</option>
                                        <option value="lain-lain">Lain-lain</option>
                                    </select>
                                </div>

                                 <div class="mb-3">
                                    <label class="form-label">Lama Pemasangan</label>
                                    <select name="terapi2" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="24jam">0-24 Jam</option>
                                        <option value="48jam">>24-48 Jam</option>
                                        <option value="72jam">>48-72 Jam</option>
                                        <option value="lebihdari72jam">>72 jam</option>
                                    </select>
                                </div>

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

                                <div class="mb-3">
                                    <label class="form-label">Merk IV Canul yg mengalami phlebitis</label>
                                    <select name="terapi3" class="form-select">
                                        <option value="">--pilih--</option>
                                        <option value="heuer">Heuer</option>
                                        <option value="Wellcare">Wellcare</option>
                                        <option value="Lain">Lain-lain</option>
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
                        <button type="submit" name="simpan" class="btn btn-success px-4 custom-glow-btn font-weight-bold">
                                <i class="bi bi-floppy2-fill"></i> Simpan
                        </button>
                </div>
            </form>
        </div>
        <br>
        <hr>
        <div class="table-responsive">
             <font size="2">
                    <?php
                    $rows_per_page = 10;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $offset = ($page - 1) * $rows_per_page;

                    $count_query = "SELECT COUNT(*) AS total FROM ERM_RI_PHLEBITHIS WHERE noreg = '$noreg'";
                    $count_result = sqlsrv_query($conn, $count_query);
                    $total_rows = sqlsrv_fetch_array($count_result, SQLSRV_FETCH_ASSOC)['total'];
                    $total_pages = ceil($total_rows / $rows_per_page);

                    $q2 = "
                        SELECT * FROM (
                            SELECT 
                                ROW_NUMBER() OVER (ORDER BY tglentry DESC) AS row_num,
                                id, noreg, userid, tglentry, tanggal, shift, lokasi, jenis, jarum, alasan, unit, 
                                namapetugas, tanya1, tanya2, tanya3, tanya4, tanya5, tanya6, tanya7, 
                                terapi1, terapi2, terapi3, skala, pelepasan, tanggal_pelepasan, alasan_pelepasan,
                                CONVERT(VARCHAR, tanggal, 103) AS tanggal_format, 
                                CONVERT(VARCHAR, tanggal, 24) AS jam,
                                CONVERT(VARCHAR, tglentry, 103) AS tgl_entry,
                                CONVERT(VARCHAR, tglentry, 24) AS jam_entry
                            FROM ERM_RI_PHLEBITHIS
                            WHERE noreg = '$noreg'
                        ) AS numbered_rows
                        WHERE row_num BETWEEN ".($offset + 1)." AND ".($offset + $rows_per_page);

                    $hasil2 = sqlsrv_query($conn, $q2);

                    if ($hasil2 === false) {
                        die(print_r(sqlsrv_errors(), true));
                    }

                    echo "
                    <div class='table-responsive rounded-4 overflow-hidden bg-white'>
                        <table class='table table-hover table-bordered m-0 align-middle bg-white'>
                            <thead style='background-color: #f9fafb;' class='text-dark fw-semibold border-bottom'>
                                <tr>
                                    <th>No</th>    
                                    <th>User Input</th>
                                    <th>Shift</th>
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

                    $i = ($page - 1) * $rows_per_page + 1;
                    while ($data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC)) {
                        echo "
                        <tr>
                            <td>$i</td>
                            <td>{$data2['userid']}</td>
                            <td>{$data2['shift']}</td>
                            <td>{$data2['tgl_entry']}</td>
                            <td>{$data2['jam_entry']}</td>
                            <td>{$data2['lokasi']}</td>
                            <td>{$data2['jenis']}</td>
                            <td>{$data2['jarum']}</td>
                            <td>{$data2['skala']}</td>
                            <td>".($data2['pelepasan'] == 'YA' ? 'Phlebitis '.$data2['alasan_pelepasan'] : 'Tercabut')."</td>
                            <td>
                                <button type=\"button\" class=\"btn btn-sm btn-edit\" data-bs-toggle=\"modal\" data-bs-target=\"#editModal{$data2['id']}\">
                                    <i class=\"bi bi-pencil\"></i>
                                </button>
                                <button type=\"button\" class=\"btn btn-sm btn-danger\" data-bs-toggle=\"modal\" data-bs-target=\"#deleteModal{$data2['id']}\">
                                    <i class=\"bi bi-x-circle\"></i>
                                </button>
                            </td>
                        </tr>";
                        ?>
                        
                        <div class="modal fade" id="editModal<?php echo $data2['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $data2['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <form method="post" action="<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?php echo $id.'|'.$user; ?>">
                                        <div class="modal-header bg-gradient-green-blue">
                                            <h5 class="modal-title text-white" id="editModalLabel<?php echo $data2['id']; ?>">Edit Data Phlebitis</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body row">
                                            <input type="hidden" name="edit_id" value="<?php echo $data2['id']; ?>">
                                            
                                            <div class="col-md-6">
                                                <div class="card mb-4 border-0 shadow-lg bg-white" style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                                                    <div class="card-header bg-gradient-green-blue">
                                                        <h6 class="mb-0"><b>Monitoring Saat Pemasangan Akses Intravena</b></h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Shift</label>
                                                            <select name="edit_shift" class="form-select">
                                                                <option value="DINAS PAGI" <?php if($data2['shift']=='DINAS PAGI') echo 'selected'; ?>>DINAS PAGI</option>
                                                                <option value="DINAS SIANG" <?php if($data2['shift']=='DINAS SIANG') echo 'selected'; ?>>DINAS SIANG</option>
                                                                <option value="DINAS MALAM" <?php if($data2['shift']=='DINAS MALAM') echo 'selected'; ?>>DINAS MALAM</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Lokasi</label>
                                                            <select name="edit_lokasi" class="form-select">
                                                                <option value="DEXTRA" <?php if($data2['lokasi'] == 'DEXTRA') echo 'selected'; ?>>Dextra</option>
                                                                <option value="SINISTRA" <?php if($data2['lokasi'] == 'SINISTRA') echo 'selected'; ?>>Sinistra</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Jenis Vena</label>
                                                            <select name="edit_jenis" class="form-select">
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

                                                        <div class="mb-3">
                                                            <label class="form-label">No Jarum IV Kateter</label>
                                                            <select name="edit_jarum" class="form-select">
                                                                <option value="18" <?php if($data2['jarum'] == '18') echo 'selected'; ?>>18</option>
                                                                <option value="20" <?php if($data2['jarum'] == '20') echo 'selected'; ?>>20</option>
                                                                <option value="22" <?php if($data2['jarum'] == '22') echo 'selected'; ?>>22</option>
                                                                <option value="24" <?php if($data2['jarum'] == '24') echo 'selected'; ?>>24</option>
                                                                <option value="26" <?php if($data2['jarum'] == '26') echo 'selected'; ?>>26</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Pemasangan</label>
                                                            <select name="edit_alasan" class="form-select">
                                                                <option value="PEMASANGAN BARU" <?php if($data2['alasan'] == 'PEMASANGAN BARU') echo 'selected'; ?>>Pemasangan baru</option>
                                                                <option value="PHLEBITIS" <?php if($data2['alasan'] == 'PHLEBITIS') echo 'selected'; ?>>Phlebitis</option>
                                                                <option value="TERCABUT" <?php if($data2['alasan'] == 'TERCABUT') echo 'selected'; ?>>Tercabut</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Unit Pemasangan</label>
                                                            <select name="edit_unit" class="form-select">
                                                                <option value="igd" <?php if($data2['unit'] == 'igd') echo 'selected'; ?>>IGD</option>
                                                                <option value="rawat_inap" <?php if($data2['unit'] == 'rawat_inap') echo 'selected'; ?>>Rawat Inap</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Pemasang</label>
                                                            <input type="text" name="edit_namapetugas" value="<?php echo $data2['namapetugas']; ?>" class="form-control">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Hand Hygiene</label>
                                                            <select name="edit_tanya1" class="form-select">
                                                                <option value="YA" <?php if($data2['tanya1'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['tanya1'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Tidak dilakukan re-palpasi setelah desinfeksi</label>
                                                            <select name="edit_tanya2" class="form-select">
                                                                <option value="YA" <?php if($data2['tanya2'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['tanya2'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Menggunakan Transparent Dressing</label>
                                                            <select name="edit_tanya3" class="form-select">
                                                                <option value="YA" <?php if($data2['tanya3'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['tanya3'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card mb-4 border-0 shadow-lg bg-white" style="border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem;">
                                                    <div class="card-header bg-gradient-green-blue">
                                                        <h6 class="mb-0"><b>Monitoring Perawatan Akses Intravena</b></h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Jenis IV Terapi</label>
                                                            <select name="edit_terapi1" class="form-select">
                                                                <option value="antibiotik" <?php if($data2['terapi1'] == 'antibiotik') echo 'selected'; ?>>Antibiotik</option>
                                                                <option value="hipertonis" <?php if($data2['terapi1'] == 'hipertonis') echo 'selected'; ?>>Hipertonis</option>
                                                                <option value="lain-lain" <?php if($data2['terapi1'] == 'lain-lain') echo 'selected'; ?>>Lain-lain</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Lama Pemasangan</label>
                                                            <select name="edit_terapi2" class="form-select">
                                                                <option value="24jam" <?php if($data2['terapi2'] == '24jam') echo 'selected'; ?>>24 Jam</option>
                                                                <option value="48jam" <?php if($data2['terapi2'] == '48jam') echo 'selected'; ?>>48 Jam</option>
                                                                <option value="72jam" <?php if($data2['terapi2'] == '72jam') echo 'selected'; ?>>72 Jam</option>
                                                                <option value="lebihdari72jam" <?php if($data2['terapi2'] == 'lebihdari72jam') echo 'selected'; ?>>Lebih dari 72 Jam</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Fiksasi Baik, Bersih, Tidak Basah</label>
                                                            <select name="edit_tanya4" class="form-select">
                                                                <option value="YA" <?php if($data2['tanya4'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['tanya4'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Disinfeksi Sebelum Injeksi</label>
                                                            <select name="edit_tanya5" class="form-select">
                                                                <option value="YA" <?php if($data2['tanya5'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['tanya5'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Tidak Ada Bekuan Darah / Clothing</label>
                                                            <select name="edit_tanya6" class="form-select">
                                                                <option value="YA" <?php if($data2['tanya6'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['tanya6'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Dokumentasi Tanggal, Jam & Nama Pemasang</label>
                                                            <select name="edit_tanya7" class="form-select">
                                                                <option value="YA" <?php if($data2['tanya7'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['tanya7'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Skala Penilaian Phlebitis (VIP SCORE)</label>
                                                            <select name="edit_skala" class="form-select">
                                                                <option value="0" <?php if($data2['skala'] == '0') echo 'selected'; ?>>0 - Tidak tampak tanda radang</option>
                                                                <option value="1" <?php if($data2['skala'] == '1') echo 'selected'; ?>>1 - Terdapat salah satu tanda berikut</option>
                                                                <option value="2" <?php if($data2['skala'] == '2') echo 'selected'; ?>>2 - Terdapat dua tanda</option>
                                                                <option value="3" <?php if($data2['skala'] == '3') echo 'selected'; ?>>3 - Semua tanda</option>
                                                                <option value="4" <?php if($data2['skala'] == '4') echo 'selected'; ?>>4 - Semua tanda + vena mengeras</option>
                                                                <option value="5" <?php if($data2['skala'] == '5') echo 'selected'; ?>>5 - Semua tanda + pus + demam</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Merk IV Canul yg mengalami phlebitis</label>
                                                            <select name="edit_terapi3" class="form-select">
                                                                <option value="heuer" <?php if($data2['terapi3'] == 'heuer') echo 'selected'; ?>>Heuer</option>
                                                                <option value="Wellcare" <?php if($data2['terapi3'] == 'Wellcare') echo 'selected'; ?>>Wellcare</option>
                                                                <option value="Lain" <?php if($data2['terapi3'] == 'Lain') echo 'selected'; ?>>Lain-lain</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Pelepasan Akses Intravena</label>
                                                            <select name="edit_pelepasan" class="form-select">
                                                                <option value="YA" <?php if($data2['pelepasan'] == 'YA') echo 'selected'; ?>>YA</option>
                                                                <option value="TDK" <?php if($data2['pelepasan'] == 'TDK') echo 'selected'; ?>>TIDAK</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Tanggal Pelepasan</label>
                                                            <input type="datetime-local" name="edit_tanggal_pelepasan" value="<?php echo $data2['tanggal_pelepasan'] ? $data2['tanggal_pelepasan']->format('Y-m-d\TH:i') : ''; ?>" class="form-control">
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan Pelepasan</label>  
                                                            <select name="edit_alasan_pelepasan" class="form-select">
                                                                <option value="">--pilih--</option>
                                                                <option value="PHLEBITHIS" <?php if($data2['alasan_pelepasan'] == 'PHLEBITHIS') echo 'selected'; ?>>PHLEBITHIS</option>
                                                                <option value="TERCABUT" <?php if($data2['alasan_pelepasan'] == 'TERCABUT') echo 'selected'; ?>>TERCABUT</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update_phlebitis" class="btn btn-success">Simpan Perubahan</button>
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
                                        <p><strong>Tanggal:</strong> <?php echo $data2['tgl_entry']; ?></p>
                                        <p><strong>Jam:</strong> <?php echo $data2['jam_entry']; ?></p>
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
                    echo "</tbody></table></div>";
                    echo '<div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">';
                    echo '<div style="font-size: 0.875rem; color: #6c757d;">Showing '.($offset + 1).' to '.min($offset + $rows_per_page, $total_rows).' of '.$total_rows.' entries</div>';
                    echo '<nav>';
                    echo '<ul style="list-style: none; display: flex; gap: 0.5rem; padding-left: 0; margin: 0;">';

                    if ($page > 1) {
                        echo '<li><a href="?id='.$id.'|'.$user.'&page='.($page - 1).'" style="text-decoration: none;"><span class="material-symbols-outlined">chevron_left</span></a></li>';
                    } else {
                        echo '<li style="opacity: 0.3;"><span class="material-symbols-outlined">chevron_left</span></li>';
                    }

                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $page + 2);

                    if ($start_page > 1) {
                        echo '<li><a href="?id='.$id.'|'.$user.'&page=1">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li><span>...</span></li>';
                        }
                    }

                    for ($p = $start_page; $p <= $end_page; $p++) {
                        $style = ($p == $page) ? 'font-weight: bold; text-decoration: underline;' : '';
                        echo '<li><a href="?id='.$id.'|'.$user.'&page='.$p.'" style="'.$style.'">'.$p.'</a></li>';
                    }

                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li><span>...</span></li>';
                        }
                        echo '<li><a href="?id='.$id.'|'.$user.'&page='.$total_pages.'">'.$total_pages.'</a></li>';
                    }

                    if ($page < $total_pages) {
                        echo '<li><a href="?id='.$id.'|'.$user.'&page='.($page + 1).'" style="text-decoration: none;"><span class="material-symbols-outlined">chevron_right</span></a></li>';
                    } else {
                        echo '<li style="opacity: 0.3;"><span class="material-symbols-outlined">chevron_right</span></li>';
                    }

                    echo '</ul>';
                    echo '</nav>';
                    echo '</div>';

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
        $terapi1 = $_POST["terapi1"];
        $terapi2 = $_POST["terapi2"];
        $terapi3 = $_POST["terapi3"];
        $skala = $_POST["skala"];
        $pelepasan = $_POST["pelepasan"];
        $tanggal_pelepasan = $_POST["tanggal_pelepasan"];
        $alasan_pelepasan = $_POST["alasan_pelepasan"];

        if (!empty($tanggal_pelepasan)) {
            $tanggal_pelepasan = str_replace("T", " ", $tanggal_pelepasan) . ":00";
        }

        $q_insert = "INSERT INTO ERM_RI_PHLEBITHIS 
        (noreg, userid, tglentry, tanggal, shift, lokasi, jenis, jarum, alasan, unit, namapetugas, 
            tanya1, tanya2, tanya3, tanya4, tanya5, tanya6, tanya7, terapi1, terapi2, terapi3, skala, pelepasan, tanggal_pelepasan, alasan_pelepasan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 

        $params_insert = [
            $noreg, $user, $tgl, $tanggal, $shift, $lokasi, $jenis, $jarum, $alasan, $unit, $namapetugas,
            $tanya1, $tanya2, $tanya3, $tanya4, $tanya5, $tanya6, $tanya7, $terapi1, $terapi2, $terapi3, $skala, $pelepasan,
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
        $terapi1 = $_POST["edit_terapi1"] ?? null;
        $terapi2 = $_POST["edit_terapi2"] ?? null;
        $terapi3 = $_POST["edit_terapi3"] ?? null;
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
        terapi1 = ?,
        terapi2 = ?,
        terapi3 = ?, 
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
            $terapi1,
            $terapi2,
            $terapi3,
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