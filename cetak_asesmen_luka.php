<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$serverName = "192.168.10.1";
$connectionInfo = array("Database" => "RSPGENTRY", "UID" => "sa", "PWD" => "p@ssw0rd");
$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

$id = $_GET['id'];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$idluka = $row[2]; 

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

$sql = "SELECT * FROM AsesmenLuka WHERE ID = $idluka";
$stmt = sqlsrv_query($conn, $sql, [$id]);
$data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);


// Fungsi helper untuk checkbox
function renderCheckboxes($items, $selectedString) {
    $selected = array_map('trim', explode(',', $selectedString ?? ''));
    $html = '';
    foreach ($items as $item) {
        $checked = in_array($item, $selected) ? '☑' : '☐';
        $html .= "<span style='display:inline-block; margin-right:10px;'>$checked $item</span>";
    }
    return $html;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cetak Asesmen Luka</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        .header, .section {
            border: 1px solid #000;
            margin-bottom: 8px;
            padding: 8px;
        }

        .form-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 4px;
        }

        .print-btn {
            text-align: right;
            margin-bottom: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print print-btn">
        <button onclick="window.print()">🖨️ Cetak</button>
    </div>

    <div class="form-title">ASESMEN RAWAT LUKA</div>

    <div class="section">
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
    </div>

    <div class="section">
        <p><strong>Tipe Luka:</strong><br>
            <?= renderCheckboxes(["Operasi", "Trauma", "Bakar", "Ulkus", "Dekubitus"], $data['Tipe_Luka'] ?? '') ?>
        </p>

        <table>
            <tr>
                <td><strong>Luas Luka:</strong> <?= $data['Luas_Luka'] ?? '' ?></td>
                <td><strong>Balutan Luka:</strong> <?= $data['Balutan_Luka'] ?? '' ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Lokasi Luka:</strong><br><?= nl2br($data['Lokasi_Luka'] ?? '') ?></td>
            </tr>
        </table>

        <p><strong>Tipe Eksudat:</strong><br>
            <?= renderCheckboxes(["Serous", "Bloody", "Serosanguineous", "Purulent", "Foul Purulent", "Tidak ada"], $data['Tipe_Eksudat'] ?? '') ?>
        </p>

        <p><strong>Jumlah Eksudat:</strong><br>
            <?= renderCheckboxes(["< 25%", "25%", "25-75%", "> 75%", "Infeksi kritis"], $data['Jumlah_Eksudat'] ?? '') ?>
        </p>

        <p><strong>Bau Luka:</strong><br>
            <?= renderCheckboxes(["Tidak ada bau", "Saat buka balutan", "Rembesan keluar", "Dekat pasien", "Masuk kamar", "Masuk ruangan lain"], $data['Odour'] ?? '') ?>
        </p>

        <p><strong>Warna Dasar Luka:</strong><br>
            <?= renderCheckboxes(["Merah", "Kuning", "Hitam"], $data['Warna_Dasar'] ?? '') ?>
        </p>

        <p><strong>Tepi Luka:</strong><br>
            <?= renderCheckboxes(["Halus", "Kasar", "Tipis", "Tebal", "Bersih", "Kotor", "Lunak", "Keras"], $data['Tepi_Luka'] ?? '') ?>
        </p>

        <p><strong>Kulit Sekitar Luka:</strong><br>
            <?= renderCheckboxes(["Utuh", "Bengkak", "Kemerahan", "Nyeri", "Keras", "Sianosis"], $data['Kulit_Sekitar'] ?? '') ?>
        </p>

        <table>
            <tr>
                <td><strong>Tanggal Kaji:</strong> <?= isset($data['Tgl_Kaji']) ? $data['Tgl_Kaji']->format('Y-m-d') : '' ?></td>
                <td><strong>Jam Kaji:</strong> <?= isset($data['Jam_Kaji']) ? $data['Jam_Kaji']->format('H:i') : '' ?></td>
            </tr>
            <tr>
                <td colspan="2"><strong>Nama Perawat:</strong> <?= $data['Nama_Perawat'] ?? '' ?></td>
            </tr>
        </table>
    </div>

</body>
</html>
