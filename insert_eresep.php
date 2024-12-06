<?php 
session_start();

$serverName = "192.168.10.1"; //serverName\instanceName
$connectionInfo = array( "Database"=>"RSPGENTRY", "UID"=>"sa", "PWD"=>"p@ssw0rd");
$conn = sqlsrv_connect( $serverName, $connectionInfo);

$tgl    = gmdate("Y-m-d  H:i:s", time()+60*60*7);
$tanggal    = gmdate("Y-m-d H:i:s", time()+60*60*7);

$getid = $_GET["id"];
$row = explode('|',$getid);
$id = $row[0]; 
$user = $row[1]; 
$idrpo = $row[2]; 

$qu="SELECT norm,noreg FROM ERM_ASSESMEN_HEADER where id='$id'";
$h1u  = sqlsrv_query($conn, $qu);        
$d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
$norm = trim($d1u['norm']);
$noreg = trim($d1u['noreg']);


//eresep
$q="
SELECT distinct id_eresep, id_rpo_header
FROM            ERM_RI_RPO
WHERE        (id_rpo_header = $idrpo)
";
$hasil  = sqlsrv_query($conn, $q);  
$no=1;
while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

  $id_eresep = $data['id_eresep'];

  //select W_EResep
  $qr="SELECT KodeUnit,KodeDokter,Custno,JenisBayar,Template,NamaPasien, Kategori, NomerAntrian FROM W_EResep where id='$id_eresep'";
  $hr  = sqlsrv_query($conn, $qr);        
  $dr  = sqlsrv_fetch_array($hr, SQLSRV_FETCH_ASSOC); 
  $kodeunit = trim($dr['KodeUnit']);
  $kodedokter = trim($dr['KodeDokter']);
  $custno = trim($dr['Custno']);
  $jenisbayar = trim($dr['JenisBayar']);
  $template = trim($dr['Template']);
  $namapasien = trim($dr['NamaPasien']);
  $kategori = trim($dr['Kategori']);
  $nomorantrian = trim($dr['NomerAntrian']);

  $q  = "
  insert into   W_EResep(
  Noreg, Norm, TglEntry, Status, KodeUnit, KodeDokter, Custno, JenisBayar, 
  UserId, Template, NamaPasien, Kategori, StatusLayanan, NomerAntrian
  ) 
  values (
  '$noreg', '$norm', '$tgl', '0', '$kodeunit', '$kodedokter', '$custno', '$jenisbayar', 
  '$user', '$template', '$namapasien', '$kategori', 'order', '$nomorantrian'
  )
  ";
  $hs = sqlsrv_query($conn,$q);

  if($hs){
  //select id resep setelah insert,
    $qr0="
    SELECT        TOP (1) Id as IdResep
    FROM            W_EResep where noreg='$noreg'
    ORDER BY TglEntry desc
    ";
    $hr0  = sqlsrv_query($conn, $qr0);        
    $dr0  = sqlsrv_fetch_array($hr0, SQLSRV_FETCH_ASSOC); 
    $IdResep = trim($dr0['IdResep']);
  }

}


//eresep detail
$q="
SELECT        id, nomor, noreg, userid, '' as tglentry, '' as tgl, nama_obat, jumlah, dosis, waktu_penggunaan, interval, dokter, apoteker, periksa, pemberi, keluarga, id_eresep, id_eresep_detail, id_rpo_header
FROM            ERM_RI_RPO
WHERE        (id_rpo_header = $idrpo) order by id 
";
$hasil  = sqlsrv_query($conn, $q);  
$no=1;
while   ($data = sqlsrv_fetch_array($hasil,SQLSRV_FETCH_ASSOC)){ 

  $id_eresep_detail = $data['id_eresep_detail'];
  $id_eresep = $data['id_eresep'];
  // $tgl = $data['tglentry'];
  $nama_obat = $data['nama_obat'];
  $dosis = $data['dosis'];
  $jumlah = $data['jumlah'];
  $waktu_penggunaan = $data['waktu_penggunaan'];

  //select W_EResep_R
  $qr2="
  SELECT        TOP (200) Jenis, KodeR, Jumlah, AturanPakai, CaraPakai, WaktuPakai, Keterangan, Bentuk, Satuan, TotalPerkiraan, PerkiraanHarga, 
  Paket, Retriksi
  FROM            W_EResep_R where Id=$id_eresep_detail
  ";
  $hr2  = sqlsrv_query($conn, $qr2);        
  $dr2  = sqlsrv_fetch_array($hr2, SQLSRV_FETCH_ASSOC); 
  $jenis = trim($dr2['Jenis']);
  $koder = trim($dr2['KodeR']);
  $jumlah = trim($dr2['Jumlah']);
  $aturanpakai = trim($dr2['AturanPakai']);
  $carapakai = trim($dr2['CaraPakai']);
  $waktupakai = trim($dr2['WaktuPakai']);
  $keterangan = trim($dr2['Keterangan']);
  $bentuk = trim($dr2['Bentuk']);
  $satuan = trim($dr2['Satuan']);
  $totalperkiraan = trim($dr2['TotalPerkiraan']);
  if(empty($totalperkiraan)){
    $totalperkiraan='0';
  }
  $perkiraanharga = trim($dr2['PerkiraanHarga']);
  if(empty($perkiraanharga)){
    $perkiraanharga='0';
  }
  $paket = trim($dr2['Paket']);
  if(empty($paket)){
    $paket='0';
  }
  $retriksi = trim($dr2['Retriksi']);
  if(empty($retriksi)){
    $retriksi='0';
  }


  $q  = "
  insert into   W_EResep_R(
  IdResep, TglEntry, Jenis, KodeR, Jumlah, AturanPakai, CaraPakai, WaktuPakai, Keterangan, UserId, Bentuk, 
  Satuan, TotalPerkiraan, PerkiraanHarga, Paket, Retriksi
  ) 
  values (
  $IdResep, '$tgl', '$jenis', '$koder', '$jumlah', '$aturanpakai', '$carapakai', '$waktupakai', '$keterangan','$user','$bentuk',
  '$satuan', '$totalperkiraan', '$perkiraanharga', '$paket', '$retriksi'
  )
  ";
  $hs = sqlsrv_query($conn,$q);

  if($hs and $jenis=='2'){

//select id resep setelah insert,
    $qr0="
    SELECT        TOP (1) Id as IdR
    FROM             W_EResep_R where IdResep='$IdResep'
    ORDER BY id desc
    ";
    $hr0  = sqlsrv_query($conn, $qr0);        
    $dr0  = sqlsrv_fetch_array($hr0, SQLSRV_FETCH_ASSOC); 
    $IdR = trim($dr0['IdR']);

    $qrac="
    SELECT Nama, Dosis, UserId
    FROM            W_EResep_Racikan
    WHERE        (idResep = $id_eresep) and IdR=$id_eresep_detail order by id 
    ";
    $hasilrec  = sqlsrv_query($conn, $qrac);  
    while   ($drac = sqlsrv_fetch_array($hasilrec,SQLSRV_FETCH_ASSOC)){ 

      $Nama = trim($drac['Nama']);
      $Dosis = trim($drac['Dosis']);
      $q  = "
      insert into  W_EResep_Racikan(
      IdResep, IdR, Nama, Dosis, UserId
      ) 
      values (
      $IdResep, '$IdR', '$Nama', '$Dosis', '$user'
      )
      ";
      $hs = sqlsrv_query($conn,$q);
    }

  }

}



if($hs){
  $eror = "Success";

  $qr = " 
  UPDATE ERM_RI_RPO_HEADER set eresep='YA'              
  WHERE id='$idrpo'
  ";
  $hr  = sqlsrv_query($conn, $qr);  

}else{
  $eror = "Gagal Insert";

}

if($hs){
  echo "
  <div class='container-fluid'>
  <div class='row'>
  ";
  echo "SUCCESS";
  echo "<br>";
  echo "<a href='rpo.php?id=$id|$user' class='btn btn-success'><i class='bi bi-arrow-clockwise'></i> Close</a>";
  echo "<br>";
  echo "
  </div>
  </div>
  "; 
}



?>