<?php 
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl        = gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tgl2       = gmdate("d/m/Y", time()+60*60*7);

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
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
$kodedept = $data2[kodedept];

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

//tgl masuk/keluar
$q3       = "select CONVERT(VARCHAR, tglmasuk, 103) as tglmasuk, CONVERT(VARCHAR, tglkeluar, 103) as tglkeluar
from ARM_PERIKSA
where noreg='$noreg' and kodeunit='$KODEUNIT'";
$hasil3  = sqlsrv_query($conn, $q3);  
$data3    = sqlsrv_fetch_array($hasil3, SQLSRV_FETCH_ASSOC);                      
$tglmasuk = $data3[tglmasuk];
$tglkeluar = $data3[tglkeluar];


// Ambil semua data
if (!$conn) die(print_r(sqlsrv_errors(), true));

$query = "SELECT TOP 100 ID, Nama, No_RM, Tgl_Kaji FROM AsesmenLuka ORDER BY ID DESC";
$result = sqlsrv_query($conn, $query);


if (isset($_GET['delete']) && $_GET['delete'] == 'true' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $row = explode('|',$id);
    $id  = $row[0];
    $user = $row[1]; 
    $idluka = $row[2]; 

    // Query untuk menghapus data berdasarkan ID
    $sqlDelete = "DELETE FROM AsesmenLuka WHERE ID = $idluka";
    $stmtDelete = sqlsrv_query($conn, $sqlDelete, [$id]);

    if ($stmtDelete) {
        // echo "<div style='padding: 20px;'>Data berhasil dihapus. <a href='daftar_asesmen_luka.php'>Kembali ke daftar</a></div>";

        echo "
        <script>
        window.location.replace('asesmen_luka.php?id=$id|$user');
        </script>
        ";
    } else {
        die(print_r(sqlsrv_errors(), true));
    }
}


?>

<!DOCTYPE html> 
<html> 
<head>  
    <title>ASESMENT LUKA</title>  
    <link rel="icon" href="favicon.ico">  
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

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
<div id="content"> 
    <div class="container">

        <body onload="document.myForm.pasien_mcu.focus();">
            <form method="POST" name='myForm' action="" enctype="multipart/form-data">
                <br>
                <a href='form_assesmen_dewasa.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
                &nbsp;&nbsp;
                <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
                &nbsp;&nbsp;
                <a href='#' class='btn btn-info' target='_blank'><i class="bi bi-printer-fill"></i></a>                  
                &nbsp;&nbsp;
                <br>
                <br>
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
                        <?php echo 'JENIS KELAMIN : '.$kelamin.'<br> ALAMAT : '.$alamatpasien.'<br>'; ?>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-12 text-center">
                        <b>ASESMENT LUKA</b><br>
                    </div>
                </div>

                <br>

                <div class="container my-4">
                    <h4 class="mb-3">Data Asesmen Luka</h4>
                    <a href="asesmen_luka_form.php?id=<?php echo $id.'|'.$user;?>" class="btn btn-primary btn-sm mb-3">+ Tambah Data</a>
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Kaji</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?= $row['ID'] ?></td>
                                    <td><?= $row['Tgl_Kaji'] ? $row['Tgl_Kaji']->format('Y-m-d') : '' ?></td>
                                    <td>
                                        <!-- Edit Button -->
                                        <a href="edit_asesmen_luka.php?id=<?= $id.'|'.$user.'|'.$row['ID'] ?>" class="btn btn-sm btn-warning">Edit</a>

                                        <!-- Delete Button -->
                                        <a href="asesmen_luka.php?id=<?= $id.'|'.$user.'|'.$row['ID'] ?>&delete=true" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>

                                        <a href="cetak_asesmen_luka.php?id=<?= $id.'|'.$user.'|'.$row['ID'] ?>" class="btn btn-sm btn-secondary" target="_blank">Cetak</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <br>
                <br>
            </form>
        </body>
    </div>
</div>

<?php 



?>