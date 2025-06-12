<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grafik Aktivitas Dokter</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    /* Spinner Style */
    #loading {
      position: fixed;
      width: 100%;
      height: 100%;
      background: white;
      z-index: 9999;
      top: 0;
      left: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .spinner {
      width: 60px;
      height: 60px;
      border: 8px solid #f3f3f3;
      border-top: 8px solid #3498db;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="container">

  <!-- Spinner -->
  <div id="loading">
    <div class="spinner"></div>
  </div>

  <?php
  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  $serverName = "192.168.10.1";
  $connectionInfo = array("Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
  $conn = sqlsrv_connect($serverName, $connectionInfo);

  $tglsekarang = gmdate("Y-m-d H:i:s", time()+60*60*7);
  $tahun = gmdate("Y", time()+60*60*7);
  $bulan = gmdate("m", time()+60*60*7);

  $tgl1 = gmdate("m/", time()+60*60*7).'01'.gmdate("/Y", time()+60*60*7);
  $tgl2 = gmdate("m/d/Y", time()+60*60*7);

  $periode1=date("d-m-Y",strtotime($tgl1));
  $periode2=date("d-m-Y",strtotime($tgl2));

  $namaBulan = [
    "01" => "Januari", "02" => "Februari", "03" => "Maret",
    "04" => "April",   "05" => "Mei",      "06" => "Juni",
    "07" => "Juli",    "08" => "Agustus",  "09" => "September",
    "10" => "Oktober", "11" => "November", "12" => "Desember"
  ];
  $labelBulan = $namaBulan[$bulan];

  $id = $_GET["id"];
  $row = explode('|', $id);
  $user = trim($row[0]); 
  $sbu = trim($row[1]); 
  $unit = trim($row[2]); 

  if ($sbu == 'RSPG'){
    $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
    $alamat = "Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik";
    $sub_unitlayanan='R';
  } elseif ($sbu == 'GRAHU'){
    $nmrs = "RUMAH SAKIT GRHA HUSADA";
    $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
    $sub_unitlayanan='G';
  } elseif ($sbu == 'DRIYO'){
    $nmrs = "RUMAH SAKIT DRIYOREJO";
    $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
    $sub_unitlayanan='C';
  }


  $q="
  SELECT C.KODEDOKTER, D.NAMA, YEAR(A.TGLMASUK) AS TAHUN, MONTH(A.TGLMASUK) AS BULAN, COUNT(*) AS JUMLAH
  FROM ARM_PERIKSA AS A
  INNER JOIN Afarm_Unitlayanan AS B ON A.KODEUNIT = B.KODEUNIT
  INNER JOIN (
    SELECT DISTINCT NOREG, UNITLAYANAN, KODEDOKTER
    FROM ARM_PERIKSA_DETIL
    WHERE CONVERT(datetime, CONVERT(varchar, TANGGAL, 101), 101) BETWEEN '$tgl1' AND '$tgl2'
    AND JUMLAH <> 0
    AND SUBSTRING(UNITLAYANAN, 1, 1) IN ('$sub_unitlayanan')
    ) AS C ON A.NOREG = C.NOREG AND A.KODEUNIT = C.UNITLAYANAN
  INNER JOIN Afarm_DOKTER AS D ON C.KODEDOKTER = D.KODEDOKTER
  WHERE CONVERT(datetime, CONVERT(varchar, A.TGLMASUK, 101), 101) BETWEEN '$tgl1' AND '$tgl2'
  AND B.JENIS2 LIKE 'RI%'
  AND B.KET1 = '$sbu'
  AND D.KETERANGAN LIKE '%SPESIALIS%'
  GROUP BY YEAR(A.TGLMASUK), MONTH(A.TGLMASUK), C.KODEDOKTER, D.NAMA
  ORDER BY JUMLAH DESC
  ";

  $hq = sqlsrv_query($conn, $q); 

  $dokterLabels = [];
  $jumlahData = [];
  $iermData = [];
  $cermData = [];

  while ($d0 = sqlsrv_fetch_array($hq, SQLSRV_FETCH_ASSOC)) {
    $kodedokter = trim($d0['KODEDOKTER']);

    $qe = "
    SELECT count(noreg) as ierm
    FROM ERM_RI_ANAMNESIS_MEDIS
    WHERE MONTH(tgl) = '$bulan'
    AND YEAR(tgl) = '$tahun'
    AND userid LIKE '%$kodedokter%'
    AND am1 IS NOT NULL";

    $he = sqlsrv_query($conn, $qe);        
    $de = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 

    $qe2 = "
    SELECT COUNT(DISTINCT ERM_SOAP.noreg) AS ierm
    FROM ERM_SOAP
    INNER JOIN ARM_REGISTER ON ERM_SOAP.noreg = ARM_REGISTER.NOREG
    INNER JOIN Afarm_Unitlayanan ON ERM_SOAP.kodeunit = Afarm_Unitlayanan.KODEUNIT
    WHERE MONTH(ERM_SOAP.tanggal) = '$bulan'
    AND YEAR(ERM_SOAP.tanggal) = '$tahun'
    AND ERM_SOAP.userid LIKE '%$kodedokter%'
    AND Afarm_Unitlayanan.KET1 = '$sbu'
    AND Afarm_Unitlayanan.JENIS2 = 'RI'
    ";

    $he2 = sqlsrv_query($conn, $qe2);        
    $de2 = sqlsrv_fetch_array($he2, SQLSRV_FETCH_ASSOC); 

    $qe3 = "
    SELECT        COUNT(DISTINCT ERM_RI_RESUME.noreg) AS iresume
    FROM            ERM_RI_RESUME INNER JOIN
    ARM_REGISTER ON ERM_RI_RESUME.noreg = ARM_REGISTER.NOREG INNER JOIN
    ERM_RI_RESUME_APPROVEL ON ERM_RI_RESUME.noreg = ERM_RI_RESUME_APPROVEL.noreg
    WHERE        (MONTH(ERM_RI_RESUME.tglentry) = '$bulan') AND (YEAR(ERM_RI_RESUME.tglentry) = '$tahun') AND (ERM_RI_RESUME.resume38 LIKE '%$kodedokter%')
    ";

    $he3 = sqlsrv_query($conn, $qe3);        
    $de3 = sqlsrv_fetch_array($he3, SQLSRV_FETCH_ASSOC); 

    $dokterLabels[] = $d0['NAMA'];
    $jumlahData[] = $d0['JUMLAH'];
    $iermData[] = $de['ierm'];
    $cermData[] = $de2['ierm'];
    $rermData[] = $de3['iresume'];

  }
  ?>

  <br><br>
  <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
  &nbsp;&nbsp;
  <a href="listdata.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>" class='btn btn-warning'><i class="bi bi-x-circle-fill"></i></a>
  &nbsp;&nbsp;

  <h2 class="text-center">Grafik Aktivitas Dokter - <?= htmlspecialchars($nmrs) ?></h2>
  <h4 class="text-center">Periode: <?= $periode1 . ' s/d ' . $periode2 ?></h4>

  <div style="height: 450px;">
    <canvas id="chartDokter"></canvas>
  </div>

  <script>
    const ctx = document.getElementById('chartDokter').getContext('2d');
    const chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: <?php echo json_encode($dokterLabels); ?>,
        datasets: [
        {
          label: 'Jumlah Pasien',
          data: <?php echo json_encode($jumlahData); ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        },
        {
          label: 'Input ERM Assesment',
          data: <?php echo json_encode($iermData); ?>,
          backgroundColor: 'rgba(255, 99, 132, 0.7)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1
        },
        {
          label: 'Input ERM CPPT',
          data: <?php echo json_encode($cermData); ?>,
          backgroundColor: 'rgba(144, 238, 144, 0.7)',
          borderColor: 'rgba(144, 238, 144, 1)',
          borderWidth: 1
        },
        {
          label: 'Input ERM Resume Medis',
          data: <?php echo json_encode($rermData); ?>,
          backgroundColor: 'rgba(255, 215, 0, 0.7)',
          borderColor: 'rgba(255, 215, 0, 1)', 
          borderWidth: 1
        }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { position: 'top' },
          title: {
            display: true,
            text: 'Jumlah Pasien & Input ERM per Dokter'
          }
        },
        scales: {
          x: {
            ticks: {
              font: { size: 9 },
              maxRotation: 45,
              minRotation: 45
            }
          },
          y: {
            beginAtZero: true,
            ticks: { font: { size: 12 } }
          }
        }
      }
    });

    // Hide spinner after content load
    window.onload = function () {
      document.getElementById("loading").style.display = "none";
    };
  </script>

</body>
</html>
