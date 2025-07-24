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


$qu="SELECT  [user],role FROM ROLERSPGENTRY.dbo.user_roleERM where [user] like '%$user%'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$role = trim($d1u['role']);

if($role=='DOKTER'){
    $kodedokter = substr($user, 0,3);
    $qu2="SELECT NAMA FROM AFARM_DOKTER where KODEDOKTER='$kodedokter'";
    $h1u2  = sqlsrv_query($conn, $qu2);        
    $d1u2  = sqlsrv_fetch_array($h1u2, SQLSRV_FETCH_ASSOC); 
    $nama_dokter = trim($d1u2['NAMA']);
}



$login = $_POST['login'];
if ($login) {
    $unit = $_POST['unit'];
    if ($unit != " ") {
        header('Location: listdata.php?id='.$user.'|'.$sbu.'|'.$unit);
    }
}

if (isset($_POST["cari"])) {
 $textcari = $_POST["textcari"];

 $row = explode('-',$textcari);
 $noreg  = trim($row[0]);


 if($noreg){
  header("Location: cekidheader.php?id=$user|$noreg");
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
                        url: 'find_pasien.php', //your                         
                        dataType: 'json',
                        data: {
                         postcode: request.term
                     },
                     success: function (data) {
                            //if multiple results are returned
                            if(data.response instanceof Array)
                              response ($.map(data.response, function (item) {
                               return {
                                value: item.noreg + ' - ' + item.nama_pasien + ' - ' +  item.tgl_periksa
                            }
                        }));
                            //if a single result is returned
                        }           
                    });
                }
            });
});
</script>    

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    body, table, th, td, button, input, .card, .btn {
        font-family: 'Poppins', Arial, sans-serif;
        font-size: 14px;
    }
    h3 {
        font-family: 'Poppins', Arial, sans-serif;
        font-weight: 600;
    }
</style>

</head>

<body onLoad="document.myForm.namaruang.focus();">
    <form action="<?php echo $halaman;?>" method="post" name="myForm" id="myForm">

        <div class="card">
          <div class="card-header">
            <h3>
                <span class='glyphicon glyphicon-edit'></span> 
                Register Pasien <?php echo $nama_dokter; ?>
            </h3>
        </div>
        <div class="card-body">

            <a href='listdata.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>' class='btn btn-warning'><i class="bi bi-x-circle"></i> Close</a>
            &nbsp;&nbsp;
            <a href="listdokter.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>" class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
            &nbsp;&nbsp;
            <input class="" name="textcari" value="<?php echo $textcari;?>" id="pasien" type="text" placeholder="Isikan Nama Pasien / NORM / NOREG" style="width: 300px;order: 0;">
            
            <button type='submit' name='cari' value='cari' type="button" style="height: 20px;border: 0;"><i class="bi bi-search"></i>
            </button>

        </div>

        <br>
        <table class="table table-stripped table-hover datatab">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pasien</th>
                    <th>Nomor Reg</th>
                    <th>Norm</th>
                    <th>Jaminan</th>
                    <th>No Kamar</th>
                    <th>Tgl Mrs/Jam</th>
                    <th>Tgl Krs/Jam</th>
                    <th>Jenis Penyakit</th>
                    <th>Status</th>
                    <th>Opsi</th>
                </tr>
            </thead>

            <tbody>


                <?php

                $q="
                SELECT DISTINCT TOP (50) ARM_REGISTER.NOREG, AFarm_MstPasien.NORM, AFarm_MstPasien.NAMA, ARM_REGISTER.TUJUAN AS KODEUNIT
                FROM            AFarm_MstPasien INNER JOIN
                ARM_REGISTER ON AFarm_MstPasien.NORM = ARM_REGISTER.NORM INNER JOIN
                V_ERM_RI_DPJP ON ARM_REGISTER.NOREG = V_ERM_RI_DPJP.noreg INNER JOIN
                Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
                WHERE        (YEAR(ARM_REGISTER.TGLENTRY) = '$tahun') AND (V_ERM_RI_DPJP.kodedokter = '$kodedokter') AND (Afarm_Unitlayanan.KET = '$sbu')
                ORDER BY ARM_REGISTER.NOREG DESC
                ";
                $hq  = sqlsrv_query($conn, $q); 
                $no=1;


                while   ($d0 = sqlsrv_fetch_array($hq,SQLSRV_FETCH_ASSOC)){ 

                    $noreg = $d0[NOREG];
                    $pasien = $d0[NAMA];
                    $norm = $d0[NORM];
                    $kode_unit = $d0[KODEUNIT];

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

                    if($cekass){
                        $status = "<a class='btn-warning sm button4'>&nbsp;".'Assesmen Awal'."&nbsp;</a>";
                    }else{
                        $status = "<a class='btn-success sm button4'>&nbsp;".'Belum Entry'."&nbsp;</a>"; 
                    }

                    echo "
                    <td>$no. </td> 
                    <td>$pasien</td> 
                    <td>$noreg</td> 
                    <td>$norm</td>
                    <td>$namadept</td>
                    <td>$kamar</td>
                    <td>$tglmasuk</td>
                    <td>$tglkeluar</td>
                    <td>$NAMAUNIT-$tujuan</td>                            
                    <td>$status</td>
                    <td align='center'>
                    <a href='cekidheader.php?id=$user|$noreg'>
                    <span class='glyphicon glyphicon-edit'></span>INPUT
                    </a>
                    &nbsp;&nbsp;
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