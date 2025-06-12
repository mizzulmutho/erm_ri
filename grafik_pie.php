<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Data Dokter</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
  <style>
    .panel-custom {
      margin-top: 30px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      border-radius: 10px;
    }
    .doctor-list {
      list-style-type: none;
      padding: 0;
    }
    .doctor-list li {
      font-size: 16px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body class="container">
  <h2 class="text-center">5 Dokter dengan Pemeriksaan Terbanyak</h2>

  <div class="row panel-custom">
    <?php
    error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
    $serverName = "192.168.10.1";
    $connectionInfo = array("Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
    $conn = sqlsrv_connect($serverName, $connectionInfo);

    $tgl1 = gmdate("m/", time()+60*60*7).'01'.gmdate("/Y", time()+60*60*7);
    $tgl2 = gmdate("m/d/Y", time()+60*60*7);
    $bulan = gmdate("m", time()+60*60*7);
    $tahun = gmdate("Y", time()+60*60*7);

    $q = "
    SELECT TOP 4 C.KODEDOKTER, D.NAMA, COUNT(*) AS JUMLAH
    FROM ARM_PERIKSA AS A 
    INNER JOIN Afarm_Unitlayanan AS B ON A.KODEUNIT = B.KODEUNIT 
    INNER JOIN (
      SELECT DISTINCT NOREG, UNITLAYANAN, KODEDOKTER
      FROM ARM_PERIKSA_DETIL
      WHERE (CONVERT(datetime, CONVERT(varchar, TANGGAL, 101), 101) BETWEEN '$tgl1' AND '$tgl2') 
      AND (JUMLAH <> 0) 
      AND (SUBSTRING(UNITLAYANAN, 1, 1) IN ('R'))
      ) AS C ON A.NOREG = C.NOREG AND A.KODEUNIT = C.UNITLAYANAN 
    INNER JOIN Afarm_DOKTER AS D ON C.KODEDOKTER = D.KODEDOKTER
    WHERE (CONVERT(datetime, CONVERT(varchar, A.TGLMASUK, 101), 101) BETWEEN '$tgl1' AND '$tgl2') 
    AND (B.JENIS2 LIKE 'RI%') AND (B.KET1 = 'RSPG') AND (D.KETERANGAN LIKE '%SPESIALIS%')
    GROUP BY C.KODEDOKTER, D.NAMA
    ORDER BY JUMLAH DESC
    ";

    $hq = sqlsrv_query($conn, $q); 
    $labels = [];
    $data = [];

    while ($d0 = sqlsrv_fetch_array($hq, SQLSRV_FETCH_ASSOC)) {
      $labels[] = $d0['NAMA'];
      $data[] = $d0['JUMLAH'];
    }
    ?>


    <div class="col-md-6">
      <canvas id="pieChart" width="300" height="300"></canvas>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
          label: 'Jumlah Pemeriksaan',
          data: <?php echo json_encode($data); ?>,
          backgroundColor: [
          '#007bff', '#28a745', '#dc3545', '#ffc107', '#17a2b8'
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
            display: false // ← Hapus legend di bawah grafik
          },
          datalabels: {
            color: '#fff',
            font: {
              weight: 'bold'
            },
            formatter: function(value, context) {
              return context.chart.data.labels[context.dataIndex];
            }
          },
          title: {
            display: true,
            text: 'Distribusi Pemeriksaan oleh 5 Dokter Teratas'
          }
        }
      },
      plugins: [ChartDataLabels]
    });
  </script>
</body>
</html>
