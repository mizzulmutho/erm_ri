<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$date = new DateTime('@'.strtotime('2016-03-22 14:30'), new DateTimeZone('Australia/Sydney'));

$tglsekarang    = gmdate("Y-m-d H:i:s", time()+60*60*7);
$tahun    = gmdate("Y", time()+60*60*7);
$milliseconds = round(microtime(true) * 1000);
$waktu = $milliseconds;

$id = $_GET["id"];
$row = explode('|',$id);

$user = trim($row[0]); 
$KET1 = trim($row[1]); 

$qu="SELECT norm,id FROM ERM_ASSESMEN_HEADER where noreg='$noreg'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$id = trim($d1u['id']);


// $id  = $row[0];
// $user = $row[1]; 


$id = $_GET["id"];
$row = explode('|',$id);
$id  = $row[0];
$user = $row[1]; 
$unit = trim($row[2]); 

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
// $KET1 = trim($d1u['KET1']);
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



$login = $_POST['login'];
if ($login) {
	$unit = $_POST['unit'];
	if ($unit != " ") {
        header('Location: listdata.php?id='.$id.'|'.$user.'|'.$unit);
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

</head>

<body onLoad="document.myForm.namaruang.focus();">
    <form action="<?php echo $halaman;?>" method="post" name="myForm" id="myForm">

        <div class="card">
          <div class="card-header">
            <h3>
                <span class='glyphicon glyphicon-edit'></span> 
                Register Pasien <?php echo $sbu; ?>
            </h3>
        </div>
        <div class="card-body">
            <label> Unit : </label>
            <select name="unit"  required>
             <?php

             if($KET1<>'RSPG'){
                 $q = "
                 SELECT        TOP (200) KODEUNIT, NAMAUNIT, KET, DEPT, ACCOUNT_FAR, ACCOUNT_TDK, REK_FAR, REK_TDK, BY_TDK, KODEREK, UNITKOMPILATOR, Grp, GRAHU, KALIMANTAN, DRIYO, JENIS, ROWID, JENIS2, JENIS3, KET1, 
                 KODEUNITBPJS, ONLINE, DIR_HASIL, ARUANG, AUNIT
                 FROM            Afarm_UnitLayanan
                 WHERE        (KET = '$KET1') AND (JENIS2 = 'RI')
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

                if($unit=='ICU'){
                    echo "<option value='ICU' selected>ICU</option>\n";             
                }else{
                    echo "<option value='ICU' >ICU</option>\n";              
                }

                if($unit=='ICU'){
                    echo "<option value='NICU' selected>NICU</option>\n";             
                }else{
                    echo "<option value='NICU' >NICU</option>\n";              
                }
                
            }

            ?>

        </select> &nbsp;&nbsp;
        <input value="Tampil Data" type="submit" class = "btn-success button4" name="login" >
        &nbsp;&nbsp;
        <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
        &nbsp;&nbsp;
        <br>
						<!-- <br>
						<input value="T a m p i l" type="submit" name="login" style="width:250;height:40px">
						<br> -->

                     <br><br>
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
                          if($KET1<>'RSPG'){
                              $q="
                              SELECT DISTINCT TOP (50) ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                              FROM            ARM_PERIKSA INNER JOIN
                              AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                              WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT = '$unit') and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                              order by NOREG desc
                              ";
                          }else{

                            if($unit=='Rawat Inap Lantai 1'){
                                $kodeunit = "
                                'R01',
                                'R01A',
                                'R02',  
                                'R02A', 
                                'R03', 
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
                                $q="
                                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                                FROM            ARM_PERIKSA INNER JOIN
                                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                                WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                                order by NOREG desc
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
                                $q="
                                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                                FROM            ARM_PERIKSA INNER JOIN
                                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                                WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                                order by NOREG desc
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
                                $q="
                                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                                FROM            ARM_PERIKSA INNER JOIN
                                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                                WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                                order by NOREG desc
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
                                $q="
                                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                                FROM            ARM_PERIKSA INNER JOIN
                                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                                WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                                order by NOREG desc
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
                                $q="
                                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                                FROM            ARM_PERIKSA INNER JOIN
                                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                                WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                                order by NOREG desc
                                ";
                            }
                            if($unit=='ICU'){
                                $kodeunit = "
                                'R10', 
                                'R10A'
                                ";
                                $q="
                                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                                FROM            ARM_PERIKSA INNER JOIN
                                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                                WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
                                order by NOREG desc
                                ";
                            }
                            if($unit=='NICU'){
                                $kodeunit = "
                                'R10', 
                                'R10A'
                                ";
                                $q="
                                SELECT DISTINCT ARM_PERIKSA.NOREG, ARM_PERIKSA.NORM, AFarm_MstPasien.NAMA
                                FROM            ARM_PERIKSA INNER JOIN
                                AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
                                WHERE        (ARM_PERIKSA.TGLKELUAR is null OR ARM_PERIKSA.TGLKELUAR='') AND (ARM_PERIKSA.KODEUNIT in($kodeunit)) and  (YEAR(ARM_PERIKSA.TGLENTRY) = '$tahun')
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

                            $qd="SELECT   NAMAUNIT FROM   Afarm_Unitlayanan WHERE KODEUNIT = '$tujuan'";
                            $hd  = sqlsrv_query($conn, $qd);        
                            $dhd = sqlsrv_fetch_array($hd, SQLSRV_FETCH_ASSOC);         
                            $NAMAUNIT = $dhd[NAMAUNIT];


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

                            echo "
                            <td>$no. </td> 
                            <td>$pasien</td> 
                            <td>$noreg</td> 
                            <td>$norm</td>
                            <td>$namadept</td>
                            <td>$kamar</td>
                            <td>$tglmasuk</td>
                            <td>$tglkeluar</td>
                            <td>$NAMAUNIT</td>                            
                            <td>$status</td>
                            <td align='center'>
                            <a href='cekidheader.php?id=$user|$noreg'>
                            <span class='glyphicon glyphicon-edit'></span>AKSI
                            </a>
                            &nbsp;&nbsp;
                            <a href='#'>
                            <span class='glyphicon glyphicon-edit'></span>KRS
                            </a>
                            &nbsp;&nbsp;
                            <a href='#'>
                            <span class='glyphicon glyphicon-print'></span>
                            </a>
                            </td>

                            </tr>
                            ";



                            $no+=1;
                        }
                        ?>
                    </tbody>
                    

                </table>
                <?php 
                if($KET1<>'RSPG'){
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
   <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
   <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
   <script>
      $(document).ready(function() {
        $('.datatab').DataTable();
    } );
</script>