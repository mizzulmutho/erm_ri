<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<title>SIRS-Sistem Informasi Rumah Sakit</title>
	<link rel="icon" href="P-2.ico">  
	<link href="css/styles.css" rel="stylesheet" />

	<!-- Jqueri autocomplete untuk procedure !!! -->
	<link rel="stylesheet" href="jquery-ui.css">
	<script src="jquery-1.10.2.js"></script>
	<script src="jquery-ui.js"></script>

	<style>
		body.dark-mode {
			background: #121212 !important;
			color: #e0e0e0 !important;
		}

		.dark-mode .card {
			background-color: #1e1e1e !important;
			border-color: #333 !important;
		}

		.dark-mode .card-header {
			background-color: #2c2c2c !important;
			color: #fff !important;
		}

		.dark-mode input,
		.dark-mode select,
		.dark-mode textarea {
			background-color: #2a2a2a !important;
			color: #fff !important;
			border-color: #555 !important;
		}

		.dark-mode .table {
			color: #fff !important;
			background-color: #1e1e1e !important;
		}

		.dark-mode .table td,
		.dark-mode .table th {
			border-color: #444 !important;
		}

		.dark-mode .btn-dark {
			background-color: #444 !important;
			border-color: #666 !important;
			width: auto !important;
			display: inline-block !important;
		}
	</style>

	<style>
/* Untuk keseluruhan tabel saat dark mode aktif */
body.dark-mode .table {
	background-color: #1e1e1e !important;
	color: #f1f1f1 !important;
	border-color: #444 !important;
}

/* Untuk sel data (td) dan header (th) */
body.dark-mode .table td,
body.dark-mode .table th {
	background-color: #1e1e1e !important;
	color: #f1f1f1 !important;
	border-color: #444 !important;
}

/* Efek hover di dark mode */
body.dark-mode .table-hover tbody tr:hover {
	background-color: #2a2a2a !important;
}

/* Alternatif baris untuk striped table */
body.dark-mode .table-striped tbody tr:nth-of-type(odd) {
	background-color: #2a2a2a !important;
}
</style>

</head>

<div class="container-fluid mt-2 text-start">
	<button type="button" id="toggleDarkMode" class="btn btn-sm btn-dark">
		<i class="bi bi-moon-fill"></i> Mode Gelap
	</button>
</div>


<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const toggleBtn = document.getElementById('toggleDarkMode');
		toggleBtn.addEventListener('click', function () {
			document.body.classList.toggle('dark-mode');

      // Simpan preferensi ke localStorage
      if (document.body.classList.contains('dark-mode')) {
      	localStorage.setItem('darkMode', 'enabled');
      	toggleBtn.innerHTML = '<i class="bi bi-sun-fill"></i> Mode Terang';
      	toggleBtn.classList.remove('btn-dark');
      	toggleBtn.classList.add('btn-light');
      } else {
      	localStorage.setItem('darkMode', 'disabled');
      	toggleBtn.innerHTML = '<i class="bi bi-moon-fill"></i> Mode Gelap';
      	toggleBtn.classList.remove('btn-light');
      	toggleBtn.classList.add('btn-dark');
      }
    });

    // Cek preferensi
    if (localStorage.getItem('darkMode') === 'enabled') {
    	document.body.classList.add('dark-mode');
    	toggleBtn.innerHTML = '<i class="bi bi-sun-fill"></i> Mode Terang';
    	toggleBtn.classList.remove('btn-dark');
    	toggleBtn.classList.add('btn-light');
    }
  });
</script>

