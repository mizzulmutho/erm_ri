<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bar Chart Top 5 e-RM</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <style>
    .chart-container {
      width: 100%;
      max-width: 800px;
      margin: 40px auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 20px;
      border-radius: 10px;
      background: #fff;
    }
  </style>

  <style>
    .chart-container {
      width: 100%;
      max-width: 600px; /* Ubah lebar maksimum grafik */
      height: 400px; /* Atur tinggi grafik */
      margin: 40px auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      padding: 20px;
      border-radius: 10px;
      background: #fff;
    }

    canvas {
      width: 100% !important; /* Pastikan canvas menyesuaikan dengan lebar container */
      height: auto !important; /* Sesuaikan tinggi canvas secara proporsional */
    }
  </style>

</head>
<body class="container">
  <h2 class="text-center">Top 5 Dokter - Input e-RM</h2>

  <?php
  error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
  $serverName = "192.168.10.1";
  $connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
  $conn = sqlsrv_connect($serverName, $connectionInfo);

  $bulan = gmdate("m", time()+60*60*7);
  $tahun = gmdate("Y", time()+60*60*7);

  $q="
  SELECT D.KODEDOKTER, D.NAMA
  FROM Afarm_DOKTER AS D
  WHERE D.KETERANGAN LIKE '%SPESIALIS%'" ;

  $hq = sqlsrv_query($conn, $q); 
  $chartData = [];

  while ($dokter = sqlsrv_fetch_array($hq, SQLSRV_FETCH_ASSOC)) {
    $kode = trim($dokter['KODEDOKTER']);
    $nama = $dokter['NAMA'];

    $qe="
    SELECT count(noreg) as ierm
    FROM ERM_RI_ANAMNESIS_MEDIS
    WHERE MONTH(tgl) = '$bulan' AND YEAR(tgl) = '$tahun'
    AND userid LIKE '%$kode%' AND am1 IS NOT NULL";
    $he = sqlsrv_query($conn, $qe);
    $de = sqlsrv_fetch_array($he, SQLSRV_FETCH_ASSOC); 
    $ierm = (int)$de['ierm'];

    if ($ierm > 0) {
      $chartData[] = [
        'label' => $nama,
        'value' => $ierm
      ];
    }
  }

  // Sort dan ambil 5 teratas
  usort($chartData, function($a, $b) {
    return $b['value'] - $a['value'];
  });
  $chartData = array_slice($chartData, 0, 5);

  // Cek jika data ada
  if (empty($chartData)) {
    echo "<p>No data available for the chart.</p>";
  }
  ?>

  <div class="chart-container">
    <canvas id="ermChart"></canvas>
  </div>

  <script>
    const chartData = <?php echo json_encode($chartData); ?>;

    // Jika tidak ada data, jangan coba menampilkan chart
    if (chartData.length === 0) {
      alert('No data available for the chart');
    } else {
      const labels = chartData.map(item => item.label);
      const data = chartData.map(item => item.value);
      const backgroundColors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'];

      const ctx = document.getElementById('ermChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar', // Ubah menjadi grafik batang
        data: {
          labels: labels,
          datasets: [{
            label: 'Jumlah e-RM',
            data: data,
            backgroundColor: backgroundColors
          }]
        },
        options: {
          responsive: true,
          indexAxis: 'y', // Grafik batang horizontal
          plugins: {
            legend: {
              display: false // Hilangkan legend
            },
            tooltip: {
              enabled: false // Matikan tooltip
            },
            datalabels: {
              color: 'white',
              formatter: function(value, ctx) {
                const label = ctx.chart.data.labels[ctx.dataIndex];
                return value; // Hanya menampilkan nilai jumlah e-RM di atas batang
              },
              font: {
                weight: 'bold',
                size: 14
              },
              align: 'end', // Menampilkan datalabel di sebelah kanan batang
              anchor: 'end', // Posisi anchor datalabel
              display: function(context) {
                return context.dataset.data[context.dataIndex] > 0; // Pastikan datalabel hanya muncul jika nilai > 0
              }
            }
          },
          scales: {
            x: {
              beginAtZero: true // Mulai sumbu X dari 0
            }
          }
        }
      });
    }
  </script>
</body>
</html>
