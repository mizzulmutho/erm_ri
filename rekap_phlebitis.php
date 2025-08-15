<?php
include "koneksi.php";

$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 

$qu = "SELECT norm, noreg FROM ERM_ASSESMEN_HEADER WHERE id='$id'";
$h1u = sqlsrv_query($conn, $qu);        
$d1u = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$noreg = trim($d1u['noreg']);

$bulan = isset($_GET['bulan']) ? (int)$_GET['bulan'] : date('n');
$tahun = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

if($bulan < 1 || $bulan > 12) $bulan = date('n');
if($tahun < 2020 || $tahun > date('Y')+1) $tahun = date('Y');

$nama_bulan = date('F', mktime(0, 0, 0, $bulan, 10));
$page_title = "REKAP DATA KEJADIAN PHLEBITIS BULAN ".strtoupper($nama_bulan).' '.$tahun;

$query_total_pemasangan = "SELECT COUNT(*) as total_pemasangan 
FROM ERM_RI_PHLEBITHIS 
WHERE noreg = ? 
AND MONTH(tanggal) = ? 
AND YEAR(tanggal) = ?";
$params_total = array($noreg, $bulan, $tahun);
$stmt_total = sqlsrv_query($conn, $query_total_pemasangan, $params_total);

$total_pemasangan = 0;
if ($stmt_total) {
    $row_total = sqlsrv_fetch_array($stmt_total, SQLSRV_FETCH_ASSOC);
    $total_pemasangan = $row_total ? $row_total['total_pemasangan'] : 0;
}

$query_total_phlebitis = "SELECT COUNT(*) as total_phlebitis 
FROM ERM_RI_PHLEBITHIS 
WHERE noreg = ? 
AND MONTH(tanggal) = ? 
AND YEAR(tanggal) = ?
AND alasan_pelepasan = 'PHLEBITHIS'";
$params_phlebitis = array($noreg, $bulan, $tahun);
$stmt_phlebitis = sqlsrv_query($conn, $query_total_phlebitis, $params_phlebitis);


if ($stmt_phlebitis === false) {
    echo "Error executing query: ";
    print_r(sqlsrv_errors());
    exit();
}

$total_phlebitis = 0;
$row_phlebitis = sqlsrv_fetch_array($stmt_phlebitis, SQLSRV_FETCH_ASSOC);
if ($row_phlebitis) {
    $total_phlebitis = (int)$row_phlebitis['total_phlebitis'];
}

$query_detail = "SELECT 
SUM(CASE WHEN unit = 'igd' THEN 1 ELSE 0 END) as igd,
SUM(CASE WHEN unit = 'rawat_inap' THEN 1 ELSE 0 END) as rawat_inap,
SUM(CASE WHEN jarum = '18' THEN 1 ELSE 0 END) as jarum_18,
SUM(CASE WHEN jarum = '20' THEN 1 ELSE 0 END) as jarum_20,
SUM(CASE WHEN jarum = '22' THEN 1 ELSE 0 END) as jarum_22,
SUM(CASE WHEN jarum = '24' THEN 1 ELSE 0 END) as jarum_24,
SUM(CASE WHEN terapi2 = '24jam' THEN 1 ELSE 0 END) as lama_24,
SUM(CASE WHEN terapi2 = '48jam' THEN 1 ELSE 0 END) as lama_48,
SUM(CASE WHEN terapi2 = '72jam' THEN 1 ELSE 0 END) as lama_72,
SUM(CASE WHEN terapi2 = 'lebihdari72jam' THEN 1 ELSE 0 END) as lama_72plus,
SUM(CASE WHEN skala = '1' THEN 1 ELSE 0 END) as skala_1,
SUM(CASE WHEN skala = '2' THEN 1 ELSE 0 END) as skala_2,
SUM(CASE WHEN skala = '3' THEN 1 ELSE 0 END) as skala_3,
SUM(CASE WHEN skala = '4' THEN 1 ELSE 0 END) as skala_4,
SUM(CASE WHEN skala = '5' THEN 1 ELSE 0 END) as skala_5,
SUM(CASE WHEN terapi3 = 'heuer' THEN 1 ELSE 0 END) as merk_heuer,
SUM(CASE WHEN terapi3 = 'Wellcare' THEN 1 ELSE 0 END) as merk_wellcare,
SUM(CASE WHEN terapi3 = 'Lain' THEN 1 ELSE 0 END) as merk_lain,
SUM(CASE WHEN terapi1 = 'antibiotik' THEN 1 ELSE 0 END) as terapi1_antibiotik,
SUM(CASE WHEN terapi1 = 'hipertonis' THEN 1 ELSE 0 END) as terapi1_hipertonis,
SUM(CASE WHEN terapi1 = 'lain-lain' THEN 1 ELSE 0 END) as terapi1_lain,
SUM(CASE WHEN jenis = 'METACARPAL' THEN 1 ELSE 0 END) as lokasi_metacarpal,
SUM(CASE WHEN jenis = 'CHEPALIK' THEN 1 ELSE 0 END) as lokasi_chepalik,
SUM(CASE WHEN jenis = 'BASILICA' THEN 1 ELSE 0 END) as lokasi_basilica,
SUM(CASE WHEN jenis = 'MEDIAN CUBITI' THEN 1 ELSE 0 END) as lokasi_mediancubiti,
SUM(CASE WHEN jenis = 'MEDIANA ANTEBRACHIAL' THEN 1 ELSE 0 END) as lokasi_medantebrachial,
SUM(CASE WHEN jenis = 'LAIN LAIN' THEN 1 ELSE 0 END) as lokasi_lain 
FROM ERM_RI_PHLEBITHIS
WHERE noreg = ?
AND MONTH(tanggal) = ? 
AND YEAR(tanggal) = ?";
$params_detail = array($noreg, $bulan, $tahun);
$stmt_detail = sqlsrv_query($conn, $query_detail, $params_detail);

$detail_data = array();
if ($stmt_detail) {
    $detail_data = sqlsrv_fetch_array($stmt_detail, SQLSRV_FETCH_ASSOC);
}

$percentage_phlebitis = ($total_pemasangan > 0) ? round(($total_phlebitis / $total_pemasangan) * 100, 1) : 0;

function hitungPersentase($nilai, $total) {
    return ($total > 0) ? round(($nilai / $total) * 100, 1) : 0;
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page_title; ?></title>
    <link rel="icon" href="favicon.ico">  
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f9fafb;
            padding: 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        table {
            font-size: 12px;
        }
        th {
            background-color: #f8f9fa !important;
            position: sticky;
            top: 0;
        }
        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
            margin-bottom: 30px;
        }
        .percentage-row {
            background-color: #fff3cd;
            font-weight: bold;
        }
        .print-button {
            margin-bottom: 20px;
        }
        .btn-kembali {
            background-color: #f9fafb;
            color: #fc212cff;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-kembali:hover {
            transform: scale(1.08);
            background-color: #fc212cff;
            color: #ffffffff;
            box-shadow: 0 8px 12px rgba(255, 7, 7, 0.44);
        }
        .btn-kembali:active {
            background-color: #fc212cff;
            border: 2px solid #fc212cff;
            color: #ffffffff;
            transform: scale(0.97);
        }
        .alert-no-data {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-biru{
           background: linear-gradient(to right, #9e11fcff, #9e11fcff);
           color: white;
           border: none;
           padding: 10px 20px;
           font-size: 16px;
           font-weight: bold;
           border-radius: 5px;
           cursor: pointer;
           transition: all 0.2s ease;
       }
       .btn-biru:hover {
        transform: scale(1.08);
        background-color: #9e11fcff;
        box-shadow: 0 8px 12px rgba(176, 7, 255, 0.44);
        color: white;
    }
    .btn-biru:active {
        transform: scale(0.97);
        box-shadow: 0 0 8px #9e11fcff, 0 0 10px #9e11fcff, 0 0 20px #9e11fcff;
        color: white;
    }
</style>
</head>
<body>
    <div class="container-fluid">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p><?php echo "Rumah Sakit Petrokimia Gresik"; ?></p>
        </div>

        <a href='phlebithis.php?id=<?php echo $id.'|'.$user;?>' class='btn btn-kembali'>
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="filter-section">
            <form method="get" action="rekap_phlebitis.php" class="row g-3">
                <input type="hidden" name="id" value="<?php echo $id.'|'.$user; ?>">
                <div class="col-md-4">
                    <label class="form-label">Pilih Bulan</label>
                    <select class="form-select" name="bulan">
                        <?php
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        
                        foreach ($months as $key => $month) {
                            $selected = ($key == $bulan) ? 'selected' : '';
                            echo "<option value='$key' $selected>$month</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pilih Tahun</label>
                    <select class="form-select" name="tahun">
                        <?php
                        $current_year = date('Y');
                        for ($year = $current_year; $year >= $current_year - 5; $year--) {
                            $selected = ($year == $tahun) ? 'selected' : '';
                            echo "<option value='$year' $selected>$year</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-biru">
                        <i class="bi bi-funnel"></i> Filter Data
                    </button>
                </div>
            </form>
        </div>

        <?php if($total_phlebitis == 0): ?>
            <div class="alert-no-data">
                <i class="bi bi-exclamation-triangle-fill"></i> Tidak ada data phlebitis yang ditemukan untuk periode yang dipilih.
            </div>
        <?php endif; ?>

        <div class="text-end print-button">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i> Cetak Laporan
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 12px;">
                <thead class="table-warning sticky-top">
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
                        <td class="text-center"><?php echo $total_pemasangan; ?></td>
                        <td class="text-center"><?php echo $total_phlebitis; ?></td>
                        <td class="text-center"><?php echo $detail_data['igd'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['rawat_inap'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['jarum_18'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['jarum_20'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['jarum_22'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['jarum_24'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lama_24'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lama_48'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lama_72'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lama_72plus'] ?? 0; ?></td>
                        <td class="text-center">0</td> <!-- Neo (belum ada data) -->  
                        <td class="text-center">0</td> <!-- Anak (belum ada data) -->
                        <td class="text-center">0</td> <!-- Dewasa (belum ada data) -->
                        <td class="text-center">0</td> <!-- Lansia (belum ada data) -->
                    </tr>
                    <tr class="table-light">
                        <td class="text-start fw-bold"></td>
                        <td class="text-center fw-bold"></td>
                        <td class="text-center fw-bold"><?php echo $percentage_phlebitis; ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['igd'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['rawat_inap'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['jarum_18'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['jarum_20'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['jarum_22'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['jarum_24'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lama_24'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lama_48'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lama_72'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lama_72plus'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold">%</td>
                        <td class="text-center fw-bold">%</td>
                        <td class="text-center fw-bold">%</td>
                        <td class="text-center fw-bold">%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-bordered table-sm" style="font-size: 12px;">
                <thead class="table-warning sticky-top">
                    <tr>
                        <th rowspan="2" class="align-middle text-center" style="min-width: 120px;">Unit</th>
                        <th colspan="3" class="text-center">Jenis IV Terapi</th>
                        <th colspan="6" class="text-center">Lokasi IV Line</th>
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
                        <td class="text-center"><?php echo $detail_data['terapi1_antibiotik'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['terapi1_hipertonis'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['terapi1_lain'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lokasi_metacarpal'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lokasi_chepalik'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lokasi_basilica'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lokasi_mediancubiti'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lokasi_medantebrachial'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['lokasi_lain'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['skala_1'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['skala_2'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['skala_3'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['skala_4'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['skala_5'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['merk_heuer'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['merk_wellcare'] ?? 0; ?></td>
                        <td class="text-center"><?php echo $detail_data['merk_lain'] ?? 0; ?></td>
                    </tr>
                    <tr class="table-light">
                        <td class="text-start fw-bold"></td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['terapi1_antibiotik'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['terapi1_hipertonis'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['terapi1_lain'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lokasi_metacarpal'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lokasi_chepalik'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lokasi_basilica'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lokasi_mediancubiti'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lokasi_medantebrachial'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['lokasi_lain'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['skala_1'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['skala_2'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['skala_3'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['skala_4'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['skala_5'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['merk_heuer'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['merk_wellcare'] ?? 0, $total_pemasangan); ?>%</td>
                        <td class="text-center fw-bold"><?php echo hitungPersentase($detail_data['merk_lain'] ?? 0, $total_pemasangan); ?>%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printReport() {
            window.print();
        }
    </script>
</body>