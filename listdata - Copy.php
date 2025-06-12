<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$date = new DateTime('@'.strtotime('2016-03-22 14:30'), new DateTimeZone('Australia/Sydney'));

// $textcari = 'R202402121064';

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);
$tahun    = gmdate("Y", time()+60*60*7);
$milliseconds = round(microtime(true) * 1000);
$waktu = $milliseconds;

$id = $_GET["id"];
$row = explode('|',$id);

$user = trim($row[0]); 
$sbu = trim($row[1]); 
$unit = trim($row[2]); 
$noreg = trim($row[3]); 
$tgl1 = trim($row[4]); 
$tgl2 = trim($row[5]); 
$cekkrs = trim($row[6]); 
$transfer_igd = trim($row[7]); 
$cektgl = trim($row[8]); 

$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);


$qu="SELECT norm,id FROM ERM_ASSESMEN_HEADER where noreg='$noreg'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$id = trim($d1u['id']);

$qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1
FROM            ARM_REGISTER INNER JOIN
Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
WHERE        (ARM_REGISTER.NOREG = '$noreg')";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$KODEUNIT = trim($d1u['KODEUNIT']);
// $KET1 = trim($d1u['KET1']);
$NORM = trim($d1u['NORM']);

if ($sbu == 'RSPG'){
    $nmrs = "RUMAH SAKIT PETROKIMIA GRESIK";
    $alamat = "Jl. Jend. A. Yani No. 69 Kel. Ngipik, Kec. Ngipik, Kab. Gresik";
};
if ($sbu == 'GRAHU'){
    $nmrs = "RUMAH SAKIT GRHA HUSADA";
    $alamat = "Komplek Perum PT Petrokimia Gresik, Jalan Padi No.3, Tlogopojok, Kroman, Kec. Gresik";
};
if ($sbu == 'DRIYO'){
    $nmrs = "RUMAH SAKIT DRIYOREJO";
    $alamat = "Jalan Raya Legundi KM 0.5Driyorejo, Gresik";
};



$login = $_POST['login'];

if (isset($_POST["transfer_igd"])) {
    $transfer_igd = $_POST ['transfer_igd'];
}else{
    $transfer_igd = trim($row[7]); 
}

if (isset($_POST["cekkrs"])) {
    $cekkrs = $_POST ['cekkrs'];
}else{
    $cekkrs = trim($row[6]); 
}

if (isset($_POST["cektgl"])) {
    $cektgl = $_POST ['cektgl'];
}else{
    $cektgl = trim($row[8]); 
}


if (isset($_POST["tgl1"])) {
    $tgl1 = $_POST ['tgl1'];
}else{
    $tgl1 = trim($row[4]); 

}

if (isset($_POST["tgl2"])) {
    $tgl2 = $_POST ['tgl2'];
}else{
    $tgl2 = trim($row[5]); 
}


if(empty($tgl1)){
 $tgl1=gmdate("Y-m-d", time()+60*60*7);
}

if(empty($tgl2)){
    $tgl2=gmdate("Y-m-d", time()+60*60*7);
}


if ($login) {
    $unit = $_POST['unit'];

    if ($unit != " ") {
        header('Location: listdata.php?id='.$user.'|'.$sbu.'|'.$unit.'|'.$noreg.'|'.$tgl1.'|'.$tgl2.'|'.$cekkrs.'|'.$transfer_igd.'|'.$cektgl);
    }
}

if (isset($_POST["cari"])) {
 $textcari = $_POST["textcari"];

 $row = explode('-',$textcari);
 $noreg  = trim($row[0]);
 $nama  = trim($row[1]);

 if($nama){
     if($noreg){
      header("Location: cekidheader.php?id=$user|$noreg");
  }
}else{
    $eror = "Format pencarian salah, tuliskan lagi data pasien !!!";
    echo "
    <script>
    alert('".$eror."');
    history.go(-1);
    </script>
    ";
}

}


if (isset($_POST["carirm"])) {
    echo $textcari2 = $_POST["textcari2"];

    $row = explode('-',$textcari2);
    $norm  = trim($row[0]);


    if($norm){
        echo "
        <script>
        window.location.replace('r_rm2.php?id=$user|$sbu|$norm');
        </script>
        ";
    }


}

?>

<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimal-ui">
    <title>listRegister</title>
    <link rel="icon" href="favicon.ico">  
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Plugin untuk DataTables -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">


    <!-- Jqueri autocomplete untuk procedure !!! -->
    <link rel="stylesheet" href="jquery-ui.css">
    <script src="jquery-1.10.2.js"></script>
    <script src="jquery-ui.js"></script>


    <style>
        .button {
          padding: 20px;
          text-align: center;
          text-decoration: none;
          font-size: 12px;
          margin: 4px 2px;
          cursor: pointer;
      }

      .button4 {border-radius: 5px;}
  </style>

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
     $("#pasien").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                    $.ajax({
                       type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_pasien3.php?id=<?php echo $sbu; ?>', //your                         
                        dataType: 'json',
                        data: {
                           postcode: request.term
                       },
                       success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                              response ($.map(data.response, function (item) {
                                 return {
                                    value: item.noreg + ' - ' + item.nama_pasien + ' - Tgl Lahir : ' +  item.tgl_lahir
                                }
                            }));
                            //if a single result is returned
                        }           
                    });
                }
            });
 });
</script>    

<script>
  $(function() {
     $("#pasien2").autocomplete({
                minLength:3, //minimum length of characters for type ahead to begin
                source: function (request, response) {
                    $.ajax({
                       type: 'POST',
                        // url: 'dok.php?id=<?php echo $sbu; ?>', //your server side script
                        url: 'find_pasien2.php', //your                         
                        dataType: 'json',
                        data: {
                           postcode: request.term
                       },
                       success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                              response ($.map(data.response, function (item) {
                                 return {
                                    value: item.norm + ' - ' + item.nama_pasien + ' - ' + item.nik 
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

<body onLoad="document.myForm.textcari.focus();">
    <form action="<?php echo $halaman;?>" method="post" name="myForm" id="myForm">

        <div class="card">
          <div class="card-header">
            <h3>
                <span class='glyphicon glyphicon-edit'></span> 
                Register Pasien <?php echo $sbu; ?>
            </h3>
        </div>

        <?php 

        if($role=='DOKTER'){
            $kodedokter = substr($user, 0,3);
            $qu2="SELECT NAMA FROM AFARM_DOKTER where KODEDOKTER='$kodedokter'";
            $h1u2  = sqlsrv_query($conn, $qu2);        
            $d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
            $nama_dokter = trim($d1u2['NAMA']);

    // header("Location: https://www.google.com/");
            echo "<br>";

            echo "
            <div class='container-fluid'>
            <div class='row'>
            ";
            echo "&nbsp;&nbsp;&nbsp;LIST PASIEN DALAM PERAWATAN DOKTER : ",$nama_dokter;
            echo "&nbsp;&nbsp;&nbsp;<a href='listdokter.php?id=$user|$sbu' class='btn btn-danger'><i class='bi bi-arrow-clockwise'></i> Tampilkan</a>";
            echo "
            </div>
            </div>

            "; 

            // exit();

        }
        ?>

        <div class='row'>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <a href="rekap.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>" class='btn btn-success'><i class="bi bi-file-earmark-text-fill"></i> Rekap </a>
                    &nbsp;&nbsp;
                    <a href="listdata.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>" class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
                    &nbsp;&nbsp;
                    <a href="kunjungan3.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>" target='_blank' class='btn btn-success'><i class="bi bi-bar-chart-line-fill"></i> Grafik</a>
                    <br>
                    <label> Unit : </label>
                    <select name="unit"  required>
                       <?php

                       if($sbu<>'RSPG'){
                           $q = "
                           SELECT        TOP (200) KODEUNIT, NAMAUNIT, KET, DEPT, ACCOUNT_FAR, ACCOUNT_TDK, REK_FAR, REK_TDK, BY_TDK, KODEREK, UNITKOMPILATOR, Grp, GRAHU, KALIMANTAN, DRIYO, JENIS, ROWID, JENIS2, JENIS3, KET1, 
                           KODEUNITBPJS, ONLINE, DIR_HASIL, ARUANG, AUNIT
                           FROM            Afarm_UnitLayanan
                           WHERE        (KET = '$sbu') AND (JENIS2 = 'RI')
                           ";
                           $hasil  = sqlsrv_query($conn, $q);
                           while ($data = sqlsrv_fetch_array($hasil, SQLSRV_FETCH_ASSOC)) {
                            if($unit==trim($data[KODEUNIT])){
                                echo "<option value='$data[KODEUNIT]' selected >$data[KODEUNIT] | $data[NAMAUNIT]</option>\n";
                            }else{
                                echo "<option value='$data[KODEUNIT]' >$data[KODEUNIT] | $data[NAMAUNIT]</option>\n";
                            }
                        }
                    }else{

                        if($unit=='Rawat Inap Lantai 1'){
                            echo "<option value='Rawat Inap Lantai 1' selected>Rawat Inap Lantai 1</option>\n";                
                        }else{
                            echo "<option value='Rawat Inap Lantai 1' >Rawat Inap Lantai 1</option>\n";                
                        }

                        if($unit=='Rawat Inap Lantai 2 Abhipraya'){
                            echo "<option value='Rawat Inap Lantai 2 Abhipraya' selected>Rawat Inap Lantai 2 Abhipraya</option>\n";             
                        }else{
                            echo "<option value='Rawat Inap Lantai 2 Abhipraya' >Rawat Inap Lantai 2 Abhipraya</option>\n";              
                        }

                        if($unit=='Rawat Inap Lantai 3 Abhipraya'){
                            echo "<option value='Rawat Inap Lantai 3 Abhipraya' selected>Rawat Inap Lantai 3 Abhipraya</option>\n";             
                        }else{
                            echo "<option value='Rawat Inap Lantai 3 Abhipraya' >Rawat Inap Lantai 3 Abhipraya</option>\n";              
                        }

                        if($unit=='Rawat Inap Lantai 2 Abhinaya'){
                            echo "<option value='Rawat Inap Lantai 2 Abhinaya' selected>Rawat Inap Lantai 2 Abhinaya</option>\n";             
                        }else{
                            echo "<option value='Rawat Inap Lantai 2 Abhinaya' >Rawat Inap Lantai 2 Abhinaya</option>\n";              
                        }

                        if($unit=='Rawat Inap Lantai 3 Abhinaya'){
                            echo "<option value='Rawat Inap Lantai 3 Abhinaya' selected>Rawat Inap Lantai 3 Abhinaya</option>\n";             
                        }else{
                            echo "<option value='Rawat Inap Lantai 3 Abhinaya' >Rawat Inap Lantai 3 Abhinaya</option>\n";              
                        }

                        if($unit=='Rawat Inap Lantai 4 Abhinaya'){
                            echo "<option value='Rawat Inap Lantai 4 Abhinaya' selected>Rawat Inap Lantai 4 Abhinaya</option>\n";             
                        }else{
                            echo "<option value='Rawat Inap Lantai 4 Abhinaya' >Rawat Inap Lantai 4 Abhinaya</option>\n";              
                        }


                        if($unit=='ICU'){
                            echo "<option value='ICU' selected>ICU</option>\n";             
                        }else{
                            echo "<option value='ICU' >ICU</option>\n";              
                        }

                        if($unit=='NICU'){
                            echo "<option value='NICU' selected>NICU</option>\n";             
                        }else{
                            echo "<option value='NICU' >NICU</option>\n";              
                        }

                    }

                    ?>

                </select> &nbsp;&nbsp;
                <input value="Tampil Data" type="submit" class = "btn-success button4" name="login" >
                &nbsp;&nbsp;
                Cari Pasien : <input class="" name="textcari" value="<?php echo $textcari;?>" id="pasien" type="text" placeholder="Isikan Nama Pasien / NORM / NOREG" style="width: 300px;order: 0;">
                <button type='submit' name='cari' value='cari' type="button" style="height: 30px;border: 0;"><i class="bi bi-search">Cari Data</i>
                </button>
                <br>Tambah Filter Pencarian<br>
                <input type='checkbox' name='cektgl' value='Y' style="min-width:20px; min-height:15px;" <?php if($cektgl=='Y'){ echo 'checked'; } ?> >&nbsp;Filter TGL    
                <input name="tgl1" type="date" size="15" value="<?php echo $tgl1; ?>" style="width:150px;height:30px">
                s/d
                <input name="tgl2" type="date" size="15" value="<?php echo $tgl2; ?>" style="width:150px;height:30px">
                &nbsp;&nbsp;
                <input type='checkbox' name='cekkrs' value='Y' style="min-width:20px; min-height:15px;" <?php if($cekkrs=='Y'){ echo 'checked'; } ?> >&nbsp;PX.KRS
                &nbsp;&nbsp;
                <input type='checkbox' name='transfer_igd' value='Y' style="min-width:20px; min-height:15px;"  <?php if($transfer_igd=='Y'){ echo 'checked'; } ?> >&nbsp;PX.DARI IGD (Transfer Pasien IGD)
            </div>
            <div class="col-4">
                <?php

                $bulan = gmdate("m", time()+60*60*7);
                $tahun = gmdate("Y", time()+60*60*7);

                $q="SELECT D.KODEDOKTER, D.NAMA FROM Afarm_DOKTER AS D WHERE D.KETERANGAN LIKE '%SPESIALIS%'";
                $hq = sqlsrv_query($conn, $q); 
                $chartData = [];

                while ($dokter = sqlsrv_fetch_array($hq, SQLSRV_FETCH_ASSOC)) {
                    $kode = trim($dokter['KODEDOKTER']);
                    $nama = $dokter['NAMA'];

                    $qe="SELECT count(noreg) as ierm FROM ERM_RI_ANAMNESIS_MEDIS WHERE MONTH(tgl) = '$bulan' AND YEAR(tgl) = '$tahun' AND userid LIKE '%$kode%' AND am1 IS NOT NULL";
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

                usort($chartData, function($a, $b) {
                    return $b['value'] - $a['value'];
                });
                $chartData = array_slice($chartData, 0, 5);

                if (empty($chartData)) {
                    echo "<p>No data available for the chart.</p>";
                } else {
                    echo "<table border='1'>
                    <tr>
                    <th>Nama Dokter</th>
                    <th>Jumlah</th>
                    </tr>";
                    foreach ($chartData as $data) {
                        echo "<tr>
                        <td>" . htmlspecialchars($data['label']) . "</td>
                        <td>" . htmlspecialchars($data['value']) . "</td>
                        </tr>";
                    }
                    echo "</table>";
                }

                ?>

            </div>
        </div>
    </div>

    <br>
        <!-- <br>
        <input value="T a m p i l" type="submit" name="login" style="width:250;height:40px">
        <br> -->

        <!-- <br><br> -->
        <table class="table table-stripped table-hover datatab">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pasien</th>
                    <th>Nomor Reg</th>
                    <th>Norm</th>
                    <th>Dokter</th>
                    <th>Jaminan</th>
                    <th>No Kamar</th>
                    <th>Tgl Mrs/Jam</th>
                    <th>Tgl Krs/Jam</th>
                    <th>Jenis Penyakit</th>
                    <th>Status</th>
                    <th>Opsi</th>
                    <th>Cetak</th>
                </tr>
            </thead>

            <tbody>


                <?php

                $bulan1  =intval(substr($tgl1,5,2));
                $tanggal1=intval(substr($tgl1,8,3));
                $tahun1  =intval(substr($tgl1,0,4));

                $bulan2  =intval(substr($tgl2,5,2));
                $tanggal2=intval(substr($tgl2,8,2));
                $tahun2  =intval(substr($tgl2,0,4));

                $periode1=date("m-d-Y",strtotime($tgl1));
                $periode2=date("m-d-Y",strtotime($tgl2));

                if ($cektgl) {
                    if($periode1 or $periode2){
                        $filter = "and (CONVERT(DATETIME, CONVERT(VARCHAR,  ARM_PERIKSA.TGLMASUK, 101), 101) BETWEEN '$periode1' AND '$periode2')";
                    }
                }else{
                    $filter = "";
                }

                if($cekkrs=='Y'){
                    $filter1 = "(year(ARM_PERIKSA.tglkeluar)>=2020)";
                }else{
                    $filter1 = "(ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='')";
                }

                if($transfer_igd=='Y'){

                    // $noreg_igd = substr($noreg, 1,12);
                    $filter2 = "
                    and (SUBSTRING(ARM_PERIKSA.NOREG, 2, 12) IN (SELECT noreg FROM ERM_TRANSFER_PASIEN))
                    ";
                }


                if($sbu<>'RSPG'){
                    if(empty($noreg)){
                      $q="
                      SELECT DISTINCT TOP (50) ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA, ARM_PERIKSA.KODEUNIT
                      FROM            ARM_PERIKSA INNER JOIN
                      AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                      WHERE        $filter1 AND (ARM_PERIKSA.KODEUNIT = '$unit') and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun') $filter $filter2
                      order by NOREG desc
                      ";
                  }else{
                      $q="
                      SELECT DISTINCT TOP (50) ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                      FROM            ARM_PERIKSA INNER JOIN
                      AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                      WHERE        (ARM_PERIKSA.NOREG='$noreg')
                      order by NOREG desc
                      ";
                  }
              }else{
                if($unit=='Rawat Inap Lantai 1'){
                   $kodeunit = "
                   'R01',
                   'R01A',
                   'R02A', 
                   'R03A',
                   'R04',
                   'R04A',
                   'R05',
                   'R05A',
                   'R06',
                   'R06A',
                   'R07',
                   'R07A',
                   'R08',
                   'R08A',
                   'R09',
                   'R09A'
                   ";
               }

               if($unit=='Rawat Inap Lantai 2 Abhipraya'){
                $kodeunit = "
                'R01D', 
                'R04D',
                'R05D', 
                'R06D', 
                'R08D', 
                'R09D' 
                ";
            }
            if($unit=='Rawat Inap Lantai 3 Abhipraya'){
                $kodeunit = "
                'R01E', 
                'R04E', 
                'R05E', 
                'R06E', 
                'R08E', 
                'R09E' 
                ";
            }
            if($unit=='Rawat Inap Lantai 2 Abhinaya'){
                $kodeunit = "
                'R01B', 
                'R04B', 
                'R05B', 
                'R06B', 
                'R08B', 
                'R09B' 
                ";
            }
            if($unit=='Rawat Inap Lantai 3 Abhinaya'){
                $kodeunit = "
                'R01C', 
                'R04C', 
                'R05C', 
                'R06C', 
                'R08C', 
                'R09C' 
                ";
            }
            if($unit=='Rawat Inap Lantai 4 Abhinaya'){
                $kodeunit = "
                'R02',  
                'R03'
                ";
            }
            if($unit=='ICU'){
                $kodeunit = "
                'R10', 
                'R10A'
                ";
            }
            if($unit=='NICU'){
                $kodeunit = "
                'R10', 
                'R10A'
                ";
            }
            if(empty($noreg)){
                if($role<>'DOKTER'){

                //user perawat register tidak diklik.
                    $q="
                    SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA, ARM_PERIKSA.KODEUNIT, ERM_ASSESMEN_HEADER.kodedokter, Afarm_DOKTER.NAMA AS NAMADOKTER
                    FROM            Afarm_DOKTER INNER JOIN
                    ERM_ASSESMEN_HEADER ON Afarm_DOKTER.KODEDOKTER = ERM_ASSESMEN_HEADER.kodedokter RIGHT OUTER JOIN
                    ARM_PERIKSA INNER JOIN
                    AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM ON ERM_ASSESMEN_HEADER.noreg = ARM_PERIKSA.NOREG
                    WHERE        $filter1 AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun') $filter $filter2
                    ORDER BY ARM_PERIKSA.NOREG DESC
                    ";

                }else{

                //cari dokter spesialist/umum.

                    $qu="SELECT KETERANGAN FROM Afarm_DOKTER  where kodedokter='$kodedokter'";
                    $h1u  = sqlsrv_query($conn, $qu);        
                    $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
                    $keterangan = trim($d1u['KETERANGAN']);

                    if($keterangan=='UMUM'){
                      $q="
                      SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA, ARM_PERIKSA.KODEUNIT, ERM_ASSESMEN_HEADER.kodedokter, Afarm_DOKTER.NAMA AS NAMADOKTER
                      FROM            Afarm_DOKTER INNER JOIN
                      ERM_ASSESMEN_HEADER ON Afarm_DOKTER.KODEDOKTER = ERM_ASSESMEN_HEADER.kodedokter RIGHT OUTER JOIN
                      ARM_PERIKSA INNER JOIN
                      AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM ON ERM_ASSESMEN_HEADER.noreg = ARM_PERIKSA.NOREG
                      WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                      ORDER BY ARM_PERIKSA.NOREG DESC
                      ";

                  }else{
                    $q="
                    SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA, ARM_PERIKSA.KODEUNIT, ERM_ASSESMEN_HEADER.kodedokter, Afarm_DOKTER.NAMA AS NAMADOKTER
                    FROM            ARM_PERIKSA INNER JOIN
                    AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM INNER JOIN
                    ERM_ASSESMEN_HEADER ON ARM_PERIKSA.NOREG = ERM_ASSESMEN_HEADER.noreg LEFT OUTER JOIN
                    Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER
                    WHERE        (ARM_PERIKSA.TGLKELUAR IS NULL OR
                        ARM_PERIKSA.TGLKELUAR = '') AND (ARM_PERIKSA.KODEUNIT IN ($kodeunit)) AND (YEAR(ARM_PERIKSA.TGLENTRY) 
                        = '$tahun') AND (ERM_ASSESMEN_HEADER.kodedokter = '$kodedokter')
                        ORDER BY ARM_PERIKSA.NOREG DESC
                        ";
                    }

                }

            }else{

                $q="
                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA, ARM_PERIKSA.KODEUNIT, ERM_ASSESMEN_HEADER.kodedokter, Afarm_DOKTER.NAMA AS NAMADOKTER
                FROM            Afarm_DOKTER INNER JOIN
                ERM_ASSESMEN_HEADER ON Afarm_DOKTER.KODEDOKTER = ERM_ASSESMEN_HEADER.kodedokter RIGHT OUTER JOIN
                ARM_PERIKSA INNER JOIN
                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM ON ERM_ASSESMEN_HEADER.noreg = ARM_PERIKSA.NOREG
                WHERE        (ARM_PERIKSA.NOREG='$noreg')
                order by NOREG desc
                ";

            }
        }
        $hq  = sqlsrv_query($conn, $q); 
        $no=1;


        while   ($d0 = sqlsrv_fetch_array($hq,SQLSRV_FETCH_ASSOC)){ 

            $noreg = $d0[NOREG];
            $pasien = $d0[NAMA];
            $norm = $d0[NORM];
            $kode_unit = $d0[KODEUNIT];
            $namadokter = $d0[NAMADOKTER];

                            //jaminan
            $qc="SELECT   custno,tujuan FROM arm_register WHERE noreg = '$noreg'";
            $hdc  = sqlsrv_query($conn, $qc);        
            $dhdc = sqlsrv_fetch_array($hdc, SQLSRV_FETCH_ASSOC);         
            $custno = trim($dhdc[custno]);
            $tujuan = trim($dhdc[tujuan]);

            $qc2="SELECT   namadept FROM Afarm_Customer_Dept WHERE custno = '$custno'";
            $hdc2  = sqlsrv_query($conn, $qc2);        
            $dhdc2 = sqlsrv_fetch_array($hdc2, SQLSRV_FETCH_ASSOC);         
            $namadept = $dhdc2[namadept];

                            //kamar
            $qc2="SELECT KAMAR FROM ARM_PERIKSA WHERE (NOREG = '$noreg') and kamar <> ''";
            $hdc2  = sqlsrv_query($conn, $qc2);        
            $dhdc2 = sqlsrv_fetch_array($hdc2, SQLSRV_FETCH_ASSOC);         
            $kamar = $dhdc2[KAMAR];

                            //tgl masuk
            $qc2="SELECT CONVERT(VARCHAR, tglmasuk, 120) as tglmasuk FROM ARM_PERIKSA WHERE (NOREG = '$noreg') and tglmasuk <> ''";
            $hdc2  = sqlsrv_query($conn, $qc2);        
            $dhdc2 = sqlsrv_fetch_array($hdc2, SQLSRV_FETCH_ASSOC);         
            $tglmasuk = $dhdc2[tglmasuk];

                            //tgl krs
            $qc2="SELECT CONVERT(VARCHAR, tglkeluar, 120) as tglkeluar FROM ARM_PERIKSA WHERE (NOREG = '$noreg') and tglkeluar <> ''";
            $hdc2  = sqlsrv_query($conn, $qc2);        
            $dhdc2 = sqlsrv_fetch_array($hdc2, SQLSRV_FETCH_ASSOC);         
            $tglkeluar = $dhdc2[tglkeluar];

            $qc2="SELECT CONVERT(VARCHAR, tglkeluar, 120) as tglkeluar FROM ARM_PERIKSA WHERE (NOREG = '$noreg') and tglkeluar <> ''";
            $hdc2  = sqlsrv_query($conn, $qc2);        
            $dhdc2 = sqlsrv_fetch_array($hdc2, SQLSRV_FETCH_ASSOC);         
            $tglkeluar = $dhdc2[tglkeluar];

            $qd="SELECT   NAMAUNIT FROM   Afarm_Unitlayanan WHERE KODEUNIT = '$kode_unit'";
            $hd  = sqlsrv_query($conn, $qd);        
            $dhd = sqlsrv_fetch_array($hd, SQLSRV_FETCH_ASSOC);         
            $NAMAUNIT = $dhd[NAMAUNIT];

            if(empty($NAMAUNIT)){
                $qd="SELECT   NAMAUNIT FROM   Afarm_Unitlayanan WHERE KODEUNIT = '$unit'";
                $hd  = sqlsrv_query($conn, $qd);        
                $dhd = sqlsrv_fetch_array($hd, SQLSRV_FETCH_ASSOC);         
                $NAMAUNIT = $dhd[NAMAUNIT];
            }

            $qd="SELECT   noreg FROM ERM_ASSESMEN_HEADER WHERE noreg = '$noreg'";
            $hd  = sqlsrv_query($conn, $qd);        
            $dhd = sqlsrv_fetch_array($hd, SQLSRV_FETCH_ASSOC);         
            $cekass = $dhd[noreg];

            $font = "<p style='font-size:12px;font-family: Arial,sans-serif;'>";
            $font2 = "<p style='font-size:32px;font-family: Arial,sans-serif;'>";
            $font3 = "<p style='font-size:18px;font-family: Arial,sans-serif;'>";

    // <tr></tr>


            if($cekass){
                $status = "<a class='btn-warning sm button4'>&nbsp;".'Assesmen Awal'."&nbsp;</a>";
            }else{
                $status = "<a class='btn-success sm button4'>&nbsp;".'Belum Entry'."&nbsp;</a>"; 
            }


            //get trasnfer pasien igd..
            $qd="
            SELECT        TOP (100) ID, NOREG, IDHEADER, NORM
            FROM            ERM_TRANSFER_PASIEN
            WHERE NORM like '%$norm%'
            order by ID DESC
            ";
            $hqd  = sqlsrv_query($conn, $qd);        
            $dhqd  = sqlsrv_fetch_array($hqd, SQLSRV_FETCH_ASSOC); 
            $id_trs = trim($dhqd['ID']);
            $id_norm = trim($dhqd['NORM']);
            $id_hdr_trs = trim($dhqd['IDHEADER']);
            $noreg_trs = trim($dhqd['NOREG']);

            if($id_trs){
                $link_lembar_igd="<a href='http://192.168.10.245/transferpx/indexrspg.php?id=$id_norm&noreg=$noreg_trs&idheader=$id_hdr_trs' target='_blank'>
                <span class='glyphicon glyphicon-print'></span> Lembar Transfer Pasien IGD
                </a>";
            }else{
                $link_lembar_igd='-';
            }
            echo "
            <td>$no. </td> 
            <td>$pasien</td> 
            <td>$noreg</td> 
            <td>$norm</td>
            <td>$namadokter</td>
            <td>$namadept</td>
            <td>$kamar</td>
            <td>$tglmasuk</td>
            <td>$tglkeluar</td>
            <td>$NAMAUNIT-$tujuan</td>                            
            <td>$status</td>
            <td align='center'>
            <a href='cekidheader.php?id=$user|$noreg'>
            <span class='glyphicon glyphicon-edit'></span>AKSI
            </a>
            <br>
            <a href='#'>
            <span class='glyphicon glyphicon-remove'></span>KRS
            </a>
            </td>
            <td>
            $link_lembar_igd
            </td>
            </tr>
            ";



            $no+=1;
        }
        ?>
    </tbody>


</table>
<?php 
if($sbu<>'RSPG'){
    $qt = "
    SELECT COUNT(DISTINCT ARM_PERIKSA.NOREG) as total_pasien
    FROM            ARM_PERIKSA INNER JOIN
    AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
    WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT = '$unit') and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
    group by YEAR(ARM_PERIKSA.TGLENTRY)
    ";
}else{
 $qt = "
 SELECT COUNT(DISTINCT ARM_PERIKSA.NOREG) as total_pasien
 FROM            ARM_PERIKSA INNER JOIN
 AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
 WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in ($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
 group by YEAR(ARM_PERIKSA.TGLENTRY)
 ";                    
}

$qth  = sqlsrv_query($conn, $qt);        
$dqth = sqlsrv_fetch_array($qth, SQLSRV_FETCH_ASSOC);         
$total_pasien = $dqth[total_pasien];

?>

Total Pasien yang di rawat : </b><?php echo $total_pasien; ?><b><br>

</div>
</form>
</body>

<!-- <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script> -->
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script>
 $(document).ready(function() {
    $('.datatab').DataTable();
} );
</script>