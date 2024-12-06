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


$tgl1 = $_POST ['tgl1'];
$tgl2 = $_POST ['tgl2'];
$jenis = $_POST ['jenis'];

if(empty($tgl1)){
    $tgl1=gmdate("Y-m-d", time()+60*60*7);
}

if(empty($tgl2)){
    $tgl2=gmdate("Y-m-d", time()+60*60*7);
}else{

}


$id = $_GET["id"];
$row = explode('|',$id);

$user = trim($row[0]); 
$sbu = trim($row[1]); 
$unit = trim($row[2]); 
$kirim = trim($row[3]);

if($kirim=='ya'){

    $eror = "Berhasil !!!";

    echo "
    <script>
    alert('".$eror."');
    history.go(-1);
    </script>
    ";
}

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

</head>

<body onLoad="document.myForm.namaruang.focus();">
    <form action="<?php echo $halaman;?>" method="post" name="myForm" id="myForm">

        <div class="card">
          <div class="card-header">
            <h3>
                <span class='glyphicon glyphicon-edit'></span> 
                Rekap Data ERM RI <?php echo $sbu; ?>
            </h3>
        </div>
        <div class="card-body">
            <a href='' class='btn btn-success'><i class="bi bi-arrow-clockwise"></i></a>
            &nbsp;&nbsp;
            <a href="listdata.php?id=<?php echo $user.'|'.$sbu.'|'.$unit; ?>" class='btn btn-warning'><i class="bi bi-x-circle-fill"></i></a>
            &nbsp;&nbsp;
            <br>
            <br>
            <select name="jenis" style="width:150px;height:40px" required>
                <option value='-'>Pilih Jenis</option>
                <option value='REKAP' <?php if($jenis=='REKAP'){ echo "selected";}?> >Rekap ERM RI</option>
                <option value='GENERAL CONSENT' <?php if($jenis=='GENERAL CONSENT'){ echo "selected";}?> >General Consent</option>                                
                <option value='ASSEMEN AWAL' <?php if($jenis=='ASSEMEN AWAL'){ echo "selected";}?> >Assesment Awal</option>
                <option value='GIZI' <?php if($jenis=='GIZI'){ echo "selected";}?> >Gizi</option>
                <!-- <option value='Encontered' <?php if($jenis=='Encontered'){ echo "selected";}?> >Encontered</option> -->
                <option value='Condition' <?php if($jenis=='Condition'){ echo "selected";}?> >Condition</option>
                <option value='Pasien Pulang' <?php if($jenis=='Pasien Pulang'){ echo "selected";}?> >Pasien Pulang</option>
            </select>
            <input name="tgl1" type="date" size="15" value="<?php echo $tgl1; ?>" style="width:150px;height:40px">
            s/d
            <input name="tgl2" type="date" size="15" value="<?php echo $tgl2; ?>" style="width:150px;height:40px">
            &nbsp;&nbsp;
            <button type='submit' name='tampil' value='cari' type="button" class='btn btn-primary'><i class="bi bi-search"></i>
            </button>

        </div>

    </form>
</body>

<?php 

if (isset($_POST["tampil"])) {
    $tgl1 = $_POST ['tgl1'];
    $tgl2 = $_POST ['tgl2'];
    $jenis = $_POST ['jenis'];

    $bulan1  =intval(substr($tgl1,5,2));
    $tanggal1=intval(substr($tgl1,8,3));
    $tahun1  =intval(substr($tgl1,0,4));

    $bulan2  =intval(substr($tgl2,5,2));
    $tanggal2=intval(substr($tgl2,8,2));
    $tahun2  =intval(substr($tgl2,0,4));

    $periode1=date("m-d-Y",strtotime($tgl1));
    $periode2=date("m-d-Y",strtotime($tgl2));


    if($tgl1){
        if($jenis=='REKAP'){
            $q="
            SELECT        dbo.Afarm_Unitlayanan.KET, a.REGTUJUAN, dbo.Afarm_Unitlayanan.NAMAUNIT, COUNT(*) AS jumlah, ISNULL(SUM(b.dokter), 0) AS dokter, ISNULL(SUM(b.perawat), 0) AS perawat,
            (SELECT        COUNT(ERM_RI_RESUME.noreg) AS Expr1
             FROM             ERM_RI_RESUME INNER JOIN
             ARM_RegisterTujuan ON  ERM_RI_RESUME.noreg = ARM_RegisterTujuan.NOREG
             WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR,  ERM_RI_RESUME.tglentry, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (ARM_RegisterTujuan.REGTUJUAN = a.REGTUJUAN) AND (resume38 IS NOT NULL) AND (resume38 <> '')) AS resume_medis,
            (SELECT COUNT(ERM_RI_GENERALCONSENT.noreg) AS Expr1
                FROM            ERM_RI_GENERALCONSENT INNER JOIN
                ARM_RegisterTujuan ON ERM_RI_GENERALCONSENT.noreg = ARM_RegisterTujuan.NOREG
                WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, tgl, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (REGTUJUAN = a.REGTUJUAN)) AS gc_tppri,
            (SELECT        COUNT(noreg) AS Expr1
               FROM            dbo.V_ERM_RI_CPPT AS V_ERM_RI_CPPT_1
               WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, TANGGAL, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (profesi = 'dokter') AND (REGTUJUAN = a.REGTUJUAN)) AS CPPT_DOKTER,
            (SELECT        COUNT(noreg) AS Expr1
               FROM            dbo.V_ERM_RI_CPPT
               WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, TANGGAL, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (profesi = 'perawat') AND (REGTUJUAN = a.REGTUJUAN)) AS CPPT_PERAWAT,
            (SELECT        COUNT(noreg) AS Expr1
               FROM            dbo.V_ERM_RI_CPPT AS V_ERM_RI_CPPT_1
               WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, TANGGAL, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (profesi = 'gizi') AND (REGTUJUAN = a.REGTUJUAN)) AS CPPT_GIZI,
            (SELECT        COUNT(noreg) AS Expr1
               FROM            dbo.V_ERM_RI_CPPT AS V_ERM_RI_CPPT_1
               WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, TANGGAL, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (profesi = 'farmasi') AND (REGTUJUAN = a.REGTUJUAN)) 
            AS CPPT_APOTEKER,
            (SELECT        COUNT(noreg) AS Expr1
               FROM            dbo.V_ERM_RI_OBSERVASI
               WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, TANGGAL, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (REGTUJUAN = a.REGTUJUAN)) AS OBSERVASI
            FROM            dbo.ARM_RegisterTujuan AS a INNER JOIN
            dbo.Afarm_Unitlayanan ON a.REGTUJUAN = dbo.Afarm_Unitlayanan.KODEUNIT LEFT OUTER JOIN
            (SELECT        dbo.V_ERM_RI_HEADER.noreg, SUM(CASE WHEN ERM_RI_ANAMNESIS_MEDIS.noreg IS NULL THEN 0 ELSE 1 END) AS dokter, COUNT(*) AS perawat
               FROM            dbo.ERM_RI_ANAMNESIS_MEDIS RIGHT OUTER JOIN
               dbo.V_ERM_RI_HEADER ON dbo.ERM_RI_ANAMNESIS_MEDIS.noreg = dbo.V_ERM_RI_HEADER.noreg
               WHERE        (dbo.V_ERM_RI_HEADER.userid IS NOT NULL)
               GROUP BY dbo.V_ERM_RI_HEADER.noreg) AS b ON a.NOREG = b.noreg
            WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, a.TANGGAL, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (a.NOREG LIKE 'R%') AND (dbo.Afarm_Unitlayanan.JENIS = 'RI') and ket='$sbu'
            GROUP BY a.REGTUJUAN, dbo.Afarm_Unitlayanan.KET, dbo.Afarm_Unitlayanan.NAMAUNIT
            ";
        }

        if($jenis=='ASSEMEN AWAL'){
            $q="
            SELECT        TOP (100) CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 25) AS tglmasuk, ERM_ASSESMEN_HEADER.norm, AFarm_MstPasien.NAMA, AFarm_MstPasien.NOKTP, ERM_ASSESMEN_HEADER.kodedokter, ERM_ASSESMEN_HEADER.noreg,ERM_ASSESMEN_HEADER.userid,
            Afarm_DOKTER_1.NAMA AS NAMADOKTER
            FROM            ERM_ASSESMEN_HEADER INNER JOIN
            AFarm_MstPasien ON ERM_ASSESMEN_HEADER.norm = AFarm_MstPasien.NORM INNER JOIN
            Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER INNER JOIN
            Afarm_DOKTER AS Afarm_DOKTER_1 ON Afarm_DOKTER.KODEDOKTER = Afarm_DOKTER_1.KODEDOKTER
            WHERE  
            (CONVERT(DATETIME, CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 101), 101) BETWEEN '$periode1' AND '$periode2') and noreg like 'R%' and Afarm_DOKTER.kodedokter like 'S%' and SBU like '%$sbu%'
            ORDER BY tglmasuk desc
            ";
        }
        if($jenis=='GENERAL CONSENT'){
           $q="           
           SELECT        TOP (200) ERM_RI_GENERALCONSENT.noreg, CONVERT(VARCHAR, ERM_RI_GENERALCONSENT.tgl, 25) AS tglmasuk, AFarm_MstPasien.NAMA, ERM_RI_GENERALCONSENT.userid, Afarm_Unitlayanan.KET, 
           ERM_ASSESMEN_HEADER.kodedokter, Afarm_DOKTER.NAMA AS NAMADOKTER
           FROM            Afarm_DOKTER INNER JOIN
           ERM_ASSESMEN_HEADER ON Afarm_DOKTER.KODEDOKTER = ERM_ASSESMEN_HEADER.kodedokter RIGHT OUTER JOIN
           ERM_RI_GENERALCONSENT INNER JOIN
           ARM_REGISTER ON ERM_RI_GENERALCONSENT.noreg = ARM_REGISTER.NOREG INNER JOIN
           AFarm_MstPasien ON ARM_REGISTER.NORM = AFarm_MstPasien.NORM INNER JOIN
           Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT ON ERM_ASSESMEN_HEADER.id = ERM_RI_GENERALCONSENT.id
           WHERE       
           (CONVERT(DATETIME, CONVERT(VARCHAR, ERM_RI_GENERALCONSENT.tgl, 101), 101) BETWEEN '$periode1' AND '$periode2') and ERM_RI_GENERALCONSENT.noreg like 'R%' and 
           KET like '%$sbu%'
           ORDER BY ERM_RI_GENERALCONSENT.id desc
           ";
       }
       if($jenis=='Encontered'){
        $q="
        SELECT     RTRIM(ARM_REGISTER.NOREG) noreg, Afarm_Unitlayanan.NAMAUNIT, AFarm_MstPasien.NAMA, ISNULL(AFarm_MstPasien.NOKTP,'') NOKTP, Afarm_DOKTER.NAMA AS NAMADOKTER, ISNULL(Afarm_DOKTER.NOKTP,'') AS KTPDOKTER, Afarm_Unitlayanan.KET AS SBU, 0 KIRIM,CONVERT(VARCHAR, ARM_REGISTER.tanggal, 25) AS tglmasuk,ARM_REGISTER.USERID as userid
        FROM            ARM_REGISTER INNER JOIN
        ARM_PERIKSA ON ARM_REGISTER.NOREG = ARM_PERIKSA.NOREG INNER JOIN
        Afarm_Unitlayanan ON ARM_PERIKSA.KODEUNIT = Afarm_Unitlayanan.KODEUNIT INNER JOIN
        Afarm_DOKTER ON ARM_PERIKSA.KODEDOKTER = Afarm_DOKTER.KODEDOKTER INNER JOIN
        AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
        WHERE       ARM_REGISTER.IDENCOUNTERSS is null AND  (ARM_PERIKSA.KODEDOKTER <> '') AND Afarm_Unitlayanan.KET = '$sbu' AND (ARM_PERIKSA.NOREG NOT IN
           (SELECT        C.NOREG
               FROM            ARM_REGISTER AS C INNER JOIN
               Afarm_Unitlayanan AS D ON C.TUJUAN = D.KODEUNIT
               WHERE        (D.JENIS3 = 'MCU'))) AND (CONVERT(DATETIME, CONVERT(VARCHAR, ARM_PERIKSA.TGLMASUK, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (ARM_PERIKSA.KODEUNIT NOT IN ('P03', 'D12', 'P48'))
        ";   
    }

    if($jenis=='Condition'){
      // $q="           
      // SELECT        TOP (200) ARM_REGISTER.NOREG as noreg, ARM_REGISTER.IDENCOUNTERSS
      // FROM            ARM_REGISTER INNER JOIN
      // Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
      // WHERE       
      // (CONVERT(DATETIME, CONVERT(VARCHAR, ARM_REGISTER.tanggal, 101), 101) BETWEEN '$periode1' AND '$periode2') and ARM_REGISTER.noreg like 'R%' and 
      // KET like '%$sbu%' AND 
      // (ARM_REGISTER.IDENCOUNTERSS IS NOT NULL)
      // ORDER BY ARM_REGISTER.NOREG DESC
      // ";

        $q="
        SELECT        CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 25) AS tglmasuk, ERM_ASSESMEN_HEADER.norm, AFarm_MstPasien.NAMA, AFarm_MstPasien.NOKTP, ERM_ASSESMEN_HEADER.kodedokter, 
        ERM_ASSESMEN_HEADER.noreg, ERM_ASSESMEN_HEADER.userid, Afarm_DOKTER_1.NAMA AS NAMADOKTER
        FROM            ERM_ASSESMEN_HEADER INNER JOIN
        AFarm_MstPasien ON ERM_ASSESMEN_HEADER.norm = AFarm_MstPasien.NORM INNER JOIN
        Afarm_DOKTER ON ERM_ASSESMEN_HEADER.kodedokter = Afarm_DOKTER.KODEDOKTER INNER JOIN
        Afarm_DOKTER AS Afarm_DOKTER_1 ON Afarm_DOKTER.KODEDOKTER = Afarm_DOKTER_1.KODEDOKTER INNER JOIN
        ARM_REGISTER ON ERM_ASSESMEN_HEADER.noreg = ARM_REGISTER.NOREG
        WHERE        (CONVERT(DATETIME, CONVERT(VARCHAR, ERM_ASSESMEN_HEADER.tglentry, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (Afarm_DOKTER.KODEDOKTER LIKE 'S%') AND 
        (ERM_ASSESMEN_HEADER.sbu LIKE '%$sbu%') AND (ARM_REGISTER.IDENCOUNTERSS <> '')
        ORDER BY tglmasuk DESC
        ";

    }

    if($jenis=='Pasien Pulang'){
     $q="
     SELECT     DISTINCT RTRIM(ARM_REGISTER.NOREG) noreg, Afarm_Unitlayanan.NAMAUNIT, AFarm_MstPasien.NAMA, ISNULL(AFarm_MstPasien.NOKTP,'') NOKTP, Afarm_DOKTER.NAMA AS NAMADOKTER, ISNULL(Afarm_DOKTER.NOKTP,'') AS KTPDOKTER, Afarm_Unitlayanan.KET AS SBU, 0 KIRIM,CONVERT(VARCHAR, ARM_PERIKSA.TGLKELUAR, 25) AS tglmasuk,ARM_REGISTER.USERID as userid, Afarm_DOKTER.kodedokter
     FROM            ARM_REGISTER INNER JOIN
     ARM_PERIKSA ON ARM_REGISTER.NOREG = ARM_PERIKSA.NOREG INNER JOIN
     Afarm_Unitlayanan ON ARM_PERIKSA.KODEUNIT = Afarm_Unitlayanan.KODEUNIT INNER JOIN
     Afarm_DOKTER ON ARM_PERIKSA.KODEDOKTER = Afarm_DOKTER.KODEDOKTER INNER JOIN
     AFarm_MstPasien ON ARM_PERIKSA.NORM = AFarm_MstPasien.NORM
     WHERE      Afarm_Unitlayanan.KET = '$sbu' AND ARM_REGISTER.NOREG like '%R%' AND (CONVERT(DATETIME, CONVERT(VARCHAR, ARM_PERIKSA.TGLMASUK, 101), 101) BETWEEN '$periode1' AND '$periode2') AND (ARM_PERIKSA.TGLKELUAR IS NOT NULL OR ARM_PERIKSA.TGLKELUAR <> '') and year(TGLKELUAR)<>'1900' order by CONVERT(VARCHAR, ARM_PERIKSA.TGLKELUAR, 25) desc
     ";   
 }

 $hasil1  = sqlsrv_query($conn, $q);

 $nox=1;  
 if($jenis=='REKAP'){

   echo "
   <div ='card'>
   <table class='table'>
   <tr valign='middle'>
   <td rowspan='2'>no</td>
   <td rowspan='2'>ket</td>
   <td rowspan='2'>regtujuan</td>
   <td rowspan='2'>namaunit</td>
   <td rowspan='2'>detail</td>
   <td rowspan='2'>jumlah</td>
   <td rowspan='2'>resume medis<br>(dokter)</td>
   <td rowspan='2'>general consent<br> (tppri)</td>
   <td colspan='2'>ASSESMEN AWAL</td>
   <td colspan='4'>ASSESMEN ULANG</td>
   </tr>
   <tr>
   <td>assesmen dokter</td>
   <td>assesmen perawat</td>
   <td>cppt dokter</td>
   <td>cppt perawat</td>
   <td>cppt gizi</td>
   <td>cppt apoteker</td>
   </tr>
   ";      

   while    ($data = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   

    echo "
    <tr>
    <td>$nox</td>
    <td>$data[KET]</td>
    <td>$data[REGTUJUAN]</td>
    <td>$data[NAMAUNIT]</td>
    <td><a href='rekap_detail.php?id=$user|$sbu|$data[REGTUJUAN]|$tgl1|$tgl2|$jenis'><i class='bi bi-arrow-right-square-fill'></i></a></td>
    <td>$data[jumlah]</td>
    <td>$data[resume_medis]</td>
    <td>$data[gc_tppri]</td>
    <td>$data[dokter]</td>
    <td>$data[perawat]</td>
    <td>$data[CPPT_DOKTER]</td>
    <td>$data[CPPT_PERAWAT]</td>
    <td>$data[CPPT_GIZI]</td>
    <td>$data[CPPT_APOTEKER]</td>
    </tr>
    ";
    $nox+=1;
    $t_jumlah += $data[jumlah];
    $t_resume_medis += $data[resume_medis];
    $t_ASSESMEN_AWAL_DOKTER += $data[dokter];
    $t_ASSESMEN_AWAL_PERAWAT += $data[perawat];
    $t_CPPT_DOKTER += $data[CPPT_DOKTER];
    $t_CPPT_PERAWAT += $data[CPPT_PERAWAT];
    $t_CPPT_GIZI += $data[CPPT_GIZI];
    $t_CPPT_APOTEKER += $data[CPPT_APOTEKER];
    $t_gc_tppri += $data[gc_tppri];
}

echo "
<tr>
<td colspan='5' align='center'>TOTAL</td>
<td>$t_jumlah</td>
<td>$t_resume_medis</td>
<td>$t_gc_tppri</td>
<td>$t_ASSESMEN_AWAL_DOKTER</td>
<td>$t_ASSESMEN_AWAL_PERAWAT</td>
<td>$t_CPPT_DOKTER</td>
<td>$t_CPPT_PERAWAT</td>
<td>$t_CPPT_GIZI</td>
<td>$t_CPPT_APOTEKER</td>
</tr>
";

echo "</table>";
echo "</card>";

}  else{


    $kolom = "encontered";

    if($jenis=='ASSEMEN AWAL' OR $jenis=='GENERAL CONSENT'){
        $link = "s_sehat.php";
        $class='btn btn-primary';
    }else if($jenis=='Condition'){
        $link = "s_sehat3.php";
        $kolom = "encontered icd";
        $class='btn btn-primary';
    }else if($jenis=='Pasien Pulang'){
        $link = "s_resume_ri.php";
        $kolom = "encontered";
        $class='btn btn-success';
    }else{

    }


    echo "
    <div ='card'>
    <table class='table'>
    <tr>
    <td>no</td>
    <td>noreg</td>
    <td>tanggal</td>
    <td>pasien</td>
    <td>profesi pemberi asuhan</td>
    <td>$kolom</td>
    <td>userid</td>
    <td>satu sehat</td>
    </tr>"; 


    while    ($data = sqlsrv_fetch_array($hasil1, SQLSRV_FETCH_ASSOC)){   

        $qe       = "SELECT      IDENCOUNTERSS from  ARM_REGISTER where noreg='$data[noreg]'";
        $hasile  = sqlsrv_query($conn, $qe);                
        $datae    = sqlsrv_fetch_array($hasile, SQLSRV_FETCH_ASSOC);                      
        $encontered = trim($datae[IDENCOUNTERSS]);

        if($jenis=='Condition'){
         $qe       = "SELECT      IDDIAGNOSA from   SS_DIAGNOSA where noreg='$data[noreg]'";
         $hasile  = sqlsrv_query($conn, $qe);                
         $datae    = sqlsrv_fetch_array($hasile, SQLSRV_FETCH_ASSOC);                      
         $encontered = trim($datae[IDDIAGNOSA]);
     }

     if($jenis=='Pasien Pulang'){
                   // $qe       = "SELECT      IDENCOUNTER from   SS_RESUME_RI where noreg='$data[noreg]'";
      $qe       = "SELECT      IDENCOUNTERSS from  ARM_REGISTER where noreg='$data[noreg]'";
      $hasile  = sqlsrv_query($conn, $qe);                
      $datae    = sqlsrv_fetch_array($hasile, SQLSRV_FETCH_ASSOC);   

      $qe2       = "SELECT      IDKELUHAN_UTAMA from  SS_RI_RESUME where noreg='$data[noreg]'";
      $hasile2  = sqlsrv_query($conn, $qe2);                
      $datae2    = sqlsrv_fetch_array($hasile2, SQLSRV_FETCH_ASSOC);   

      $encontered = 'Encounter :'.trim($datae[IDENCOUNTERSS]).'<br>Resume :'.trim($datae2[IDKELUHAN_UTAMA]);

  }

  echo "
  <tr>
  <td>$nox</td>
  <td>$data[noreg]</td>
  <td>$data[tglmasuk]</td>
  <td>$data[NAMA]</td>
  <td>$data[NAMADOKTER]</td>
  <td>$encontered</td>
  <td>$data[userid]</td>
  <td><a href='$link?id=$data[noreg]|$data[kodedokter]|$sbu|$data[tglmasuk]|$user' target='_blank' class='$class'><i class='bi bi-arrow-up-right-circle-fill'></i></a></td>
  </tr>
  ";
  $nox+=1;

}

echo "</table>";
echo "</card>";

}    


}


}

?>


<!-- <script src="http://code.jquery.com/jquery-1.12.0.min.js"></script> -->
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('.datatab').DataTable();
    } );
</script>