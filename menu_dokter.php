     <style type="text/css">
          .new-link {
               background-color: #b6f8b6; /* Hijau muda */
               color: #333; /* Warna teks */
               /*padding: 10px;*/
               border-radius: 5px;
               display: inline-flex;
               align-items: center;
               text-decoration: none;
               position: relative;
               transition: background-color 0.3s ease;
          }

          .new-link:hover {
               background-color: #a3f0a3; /* Efek hover sedikit lebih gelap */
          }

          .new-link .fa-bell {
               margin-right: 8px; /* Jarak antara ikon dan teks */
          }

          .new-link::after {
               content: " New";
               position: absolute;
               top: -5px;
               right: -10px;
               background-color: red;
               color: white;
               padding: 2px 6px;
               border-radius: 10px;
               font-size: 12px;
          }
     </style>  

     <?php
     $c_asawal="
     SELECT am1 
     FROM ERM_RI_ANAMNESIS_MEDIS
     where noreg='$noreg'";
     $hc_asawal  = sqlsrv_query($conn, $c_asawal);        
     $dhc_asawal  = sqlsrv_fetch_array($hc_asawal, SQLSRV_FETCH_ASSOC); 

     $am1= $dhc_asawal['am1'];
     $user2 = substr($user, 0,3);

     $qun="  
     SELECT        TOP (200) user1, pass, PASS2, indek, NamaUser, grpuser, unit, unitpenerima, SBU, nik, statusaktif, noktp
     FROM            ROLERSPGENTRY.dbo.TBLuserERM
     where user1 like '%$user%'";
     $h1un  = sqlsrv_query($conn, $qun);        
     $d1un  = sqlsrv_fetch_array($h1un, SQLSRV_FETCH_ASSOC); 
     $cekunit = trim($d1un['unit']);


     $qu="SELECT        ARM_REGISTER.NOREG, ARM_REGISTER.NORM, Afarm_Unitlayanan.KODEUNIT, Afarm_Unitlayanan.NAMAUNIT, Afarm_Unitlayanan.KET1,ARM_REGISTER.TUJUAN
     FROM            ARM_REGISTER INNER JOIN
     Afarm_Unitlayanan ON ARM_REGISTER.TUJUAN = Afarm_Unitlayanan.KODEUNIT
     WHERE        (ARM_REGISTER.NOREG = '$noreg')";
     $h1u  = sqlsrv_query($conn, $qu);        
     $d1u  = sqlsrv_fetch_array($h1u, SQLSRV_FETCH_ASSOC); 
// $KODEUNIT = trim($d1u['KODEUNIT']);
     $KET1 = trim($d1u['KET1']);
     $NORM = trim($d1u['NORM']);
     $KODEUNIT = trim($d1u['TUJUAN']);
     $sbu = trim($d1u['KET1']);
     $unit = $KODEUNIT;

     if($cekunit=='R07'){
          $KODEUNIT='R07';
     }


     if(empty($am1)){
          $tampil="
          <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
          <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>
          <div class='container mt-3'>
          <div class='btn-group d-flex flex-wrap' role='group'>
          <a href='anamnesis_medis.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-list-check'></i> Asesmen Awal</a>
          <a href='soap_dokter.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-journal-medical'></i> CPPT</a>
          <a href='diagnosis.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-clipboard-heart'></i> Diagnosa</a>
          <a href='http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/$KODEUNIT/$noreg/$norm/$user2' class='btn btn-success border border-white' target='_blank'>
          <i class='bi bi-prescription'></i> EResep - ELaborat - ERadiologi
          </a>
          <a href='laborat_dokter.php?id=$id|$user' class='btn btn-warning border border-white'><i class='bi bi-droplet'></i> Hasil Laborat</a>
          <a href='radiologi_dokter.php?id=$id|$user' class='btn btn-warning border border-white'><i class='bi bi-image'></i> Hasil & Foto Radiologi</a>

          &nbsp;&nbsp; Informasi : Data Assesment Awal masih kosong untuk membuka menu resume medis simpan data Assesment Awal terlebih dahulu ,
          </div>
          </div>


          ";
     }else{

          $c_diagnosa="
          SELECT resume20,resume21,resume22
          FROM ERM_RI_RESUME
          where noreg='$noreg'";
          $hc_diagnosa  = sqlsrv_query($conn, $c_diagnosa);        
          $dc_diagnosa  = sqlsrv_fetch_array($hc_diagnosa, SQLSRV_FETCH_ASSOC); 

          $resume20= $dc_diagnosa['resume20'];
          $resume21= $dc_diagnosa['resume21'];
          $resume22= $dc_diagnosa['resume22'];

          if(empty($resume20)){
               $tampil="
               <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
               <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>

               <div class='container mt-3'>
               <div class='btn-group d-flex flex-wrap' role='group'>
               <a href='anamnesis_medis.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-list-check'></i> Asesmen Awal</a>
               <a href='diagnosis.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-clipboard-heart'></i> Diagnosa</a>
               <a href='soap_dokter.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-journal-medical'></i> CPPT</a>
               <a href='http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/$KODEUNIT/$noreg/$norm/$user2' class='btn btn-success border border-white' target='_blank'>
               <i class='bi bi-prescription'></i> EResep - ELaborat - ERadiologi
               </a>
               <a href='laborat_dokter.php?id=$id|$user' class='btn btn-warning border border-white'><i class='bi bi-droplet'></i> Hasil Laborat</a>
               <a href='radiologi_dokter.php?id=$id|$user' class='btn btn-warning border border-white'><i class='bi bi-image'></i> Hasil & Foto Radiologi</a>

               &nbsp;&nbsp; Informasi : Data Diagnosa masih kosong untuk membuka menu resume medis simpan data Diagnosa terlebih dahulu ,
               </div>
               </div>
               ";

          }else{

               $tampil="
               <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
               <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>

               <div class='container mt-3'>
               <div class='btn-group d-flex flex-wrap' role='group'>
               <a href='listdata.php?id=$user|$sbu|$unit|$noreg' class='btn btn-primary border border-white'><i class='bi bi-hospital'></i> Home</a>
               <a href='anamnesis_medis.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-list-check'></i> Asesmen Awal</a>
               <a href='diagnosis.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-clipboard-heart'></i> Diagnosa</a>
               <a href='soap_dokter.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-journal-medical'></i> CPPT</a>
               <a href='verifikasi_dokter.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-check-circle'></i> Verifikasi</a>
               <a href='resume_dokter.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-file-earmark-medical'></i> Resume Medis</a>
               <a href='jadwaloperasi.php?id=$id|$user' class='btn btn-primary border border-white'><i class='bi bi-calendar-check'></i> Laporan Operasi</a>
               <br>
               <a href='edukasi.php?id=$id|$user' class='btn btn-success border border-white'><i class='bi bi-book-half'></i> Edukasi</a>
               <a href='r_vitalsign_dokter.php?id=$id|$user' class='btn btn-success border border-white'><i class='bi bi-graph-up'></i> Grafik TTV</a>
               <a href='http://192.168.10.194:3010/full_screen/eresepRI/rawat_jalan/$KODEUNIT/$noreg/$norm/$user2' class='btn btn-success border border-white' target='_blank'>
               <i class='bi bi-prescription'></i> EResep - ELaborat - ERadiologi
               </a>

               <a href='laborat_dokter.php?id=$id|$user' class='btn btn-warning border border-white'><i class='bi bi-droplet'></i> Hasil Laborat</a>
               <a href='radiologi_dokter.php?id=$id|$user' class='btn btn-warning border border-white'><i class='bi bi-image'></i> Hasil & Foto Radiologi</a>
               <a href='surat_sakit.php?id=$id|$user' class='btn btn-warning border border-white'><i class='bi bi-file-earmark-text'></i> Surat Sakit</a>

               <a href='history_cppt.php?id=$id|$user|cppt' class='btn btn-info border border-white'><i class='bi bi-clock-history'></i> History CPPT</a>
               <a href='history_cppt.php?id=$id|$user|rencana_terapi' class='btn btn-info border border-white'><i class='bi bi-clock-history'></i> History Rencana Terapi</a>
               <a href='history_cppt.php?id=$id|$user|advis_igd' class='btn btn-info border border-white'><i class='bi bi-clock-history'></i> History Advis IGD</a>
               <a href='history_cppt.php?id=$id|$user|rencana_terapi_eresep' class='btn btn-info border border-white'><i class='bi bi-clock-history'></i> History Rencana Terapi dari ERESEP</a>

               <a href='riwayat_pasien.php?id=$id|$user' class='btn btn-info border border-white'>
               <i class='fa fa-bell'></i> Riwayat Hasil Laborat & Radiologi
               </a>

               <a href='rpo_print.php?id=$id|$user' class='btn btn-info border border-white'>
               <i class='fa fa-bell'></i> Rekam Pemberian Obat (RPO)
               </a>

               </div>
               </div>
               ";

          }
     }

     echo $tampil;

?>