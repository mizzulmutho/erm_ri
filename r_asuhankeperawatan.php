<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Asuhan Keperawatan</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body onload="document.myForm.pasien_mcu.focus();">

	<div class="container py-4">
		<form method="POST" name="myForm" action="" enctype="multipart/form-data">
			<div class="row mb-3">
				<div class="col-12">
					<?php include "header_soap.php"; ?>
				</div>
			</div>

			<div class="mb-3 d-flex gap-2">
				<a href='index.php?id=<?php echo $id."|".$user;?>' class='btn btn-warning'>
					<i class="bi bi-x-circle-fill"></i> Tutup
				</a>
				<a href='' class='btn btn-success'>
					<i class="bi bi-arrow-clockwise"></i> Refresh
				</a>
				<a href='askep.php?id=<?php echo $id."|".$user;?>' class='btn btn-primary'>
					<i class="bi bi-journal-plus"></i> Tambah Rencana Asuhan
				</a>
			</div>

			<div class="card">
				<div class="card-header bg-primary text-white">
					<i class="bi bi-file-earmark-medical-fill"></i> Report Asuhan Keperawatan
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<font size="2">
							<?php
							$ql = "SELECT DISTINCT noreg, diagnosa_keperawatan 
							FROM ERM_ASUHAN_KEPERAWATAN 
							WHERE noreg = '$noreg' 
							ORDER BY diagnosa_keperawatan ASC";
							$hl1 = sqlsrv_query($conn, $ql);

							while ($dl1 = sqlsrv_fetch_array($hl1, SQLSRV_FETCH_ASSOC)) {
								$diagnosa_keperawatan = $dl1['diagnosa_keperawatan'];

								$q2 = "SELECT diagnosa_nama 
								FROM ERM_MASTER_ASUHANKEPERAWATAN 
								WHERE diagnosa_keperawatan LIKE '%$diagnosa_keperawatan%' 
								ORDER BY id DESC";
								$h2 = sqlsrv_query($conn, $q2);
								$d2 = sqlsrv_fetch_array($h2, SQLSRV_FETCH_ASSOC); 
								$diagnosa_nama = $d2['diagnosa_nama'];

								echo "<h6 class='mt-3'><i class='bi bi-bandaid-fill text-danger'></i> Diagnosa Keperawatan: <strong>$diagnosa_nama</strong></h6>";

                    // Ambil data implementasi
								$q2 = "
								SELECT id, sift, userid, implementasi, 
								CONVERT(VARCHAR, tanggal, 103) AS tanggal, 
								CONVERT(VARCHAR, jam, 24) AS jam
								FROM ERM_IMPLEMENTASI_ASUHAN
								WHERE noreg = '$noreg' AND diagnosa_keperawatan = '$diagnosa_keperawatan'
								ORDER BY tanggal DESC, jam DESC";

								$hasil2 = sqlsrv_query($conn, $q2);

                    // Kelompok data per tanggal > sift
								$grouped_data = [];

								while ($data2 = sqlsrv_fetch_array($hasil2, SQLSRV_FETCH_ASSOC)) {
                        $tanggal = $data2['tanggal']; // format dd/mm/yyyy
                        $sift = trim($data2['sift']);
                        $grouped_data[$tanggal][$sift][] = $data2;
                    }

                    // Urutkan tanggal DESC
                    krsort($grouped_data);

                    // Urutan sift yang diinginkan
                    $sift_order = ['DINAS PAGI', 'DINAS SIANG', 'DINAS MALAM'];

                    foreach ($grouped_data as $tanggal => $sift_group) {
                    	echo "<h6 class='mt-4'><i class='bi bi-calendar-event'></i> Tanggal: <strong>$tanggal</strong></h6>";

                        // Urutkan sift sesuai urutan custom
                    	foreach ($sift_order as $sift) {
                    		if (!isset($sift_group[$sift])) continue;

                            // Set warna card
                    		if ($sift == 'DINAS PAGI') {
                    			$bgCard = 'bg-info text-white';
                    			$warna = '';
                    		} elseif ($sift == 'DINAS SIANG') {
                    			$bgCard = 'bg-warning text-dark';
                    			$warna = '#F5F7F8';
                    		} elseif ($sift == 'DINAS MALAM') {
                    			$bgCard = 'bg-dark text-white';
                    			$warna = '#F1F8E8';
                    		} else {
                    			$bgCard = 'bg-light';
                    			$warna = '';
                    		}

                    		echo "
                    		<div class='card mb-3'>
                    		<div class='card-header $bgCard'>
                    		<strong>Sift:</strong> $sift
                    		</div>
                    		<div class='card-body'>
                    		<div class='table-responsive'>
                    		<table class='table table-bordered table-striped'>
                    		<thead class='table-light'>
                    		<tr>
                    		<th>No</th>    
                    		<th>User Input</th>
                    		<th>Tanggal</th>
                    		<th>Jam</th>                                     
                    		<th>Implementasi</th>
                    		<th>Aksi</th>
                    		</tr>
                    		</thead>
                    		<tbody>";

                    		$i = 1;
                    		foreach ($sift_group[$sift] as $data2) {
                    			echo "
                    			<tr style='background-color:$warna'>
                    			<td>$i</td>
                    			<td>{$data2['userid']}</td>
                    			<td>{$data2['tanggal']}</td>
                    			<td>{$data2['jam']}</td>
                    			<td>{$data2['implementasi']}</td>
                    			<td>
                    			<a href='del_implementasi.php?id={$id}|{$user}|{$data2['id']}' 
                    			class='btn btn-sm btn-outline-danger'>
                    			<i class='bi bi-trash'></i>
                    			</a>
                    			</td>
                    			</tr>";
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
                }
                ?>
            </font>
        </div>
    </div>
</div>


</form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>