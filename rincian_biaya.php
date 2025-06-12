<?php
INCLUDE_ONCE("koneksi.php");
?>

<html>
<head>
 <script type="text/javascript" src="scw.js"></script>

 <script type="text/javascript">
  function createPopup(targ){
    var win = window.open('', targ, 'width=1000,height=400,scrollbars=1');
  }
</script>

</head>

<body>

  <table Border=0>
    <form name="laporan" action="report_rincian_biaya_pasien.php" method="post"target="zipWindow" onSubmit="createPopup(this.target)">
      <h2>Lihat Rincian Biaya Pasien</h2>
      <table border="0" width="100%" align="left">
        <tr>
          <td width=5%>Jenis</td>
          <td>:</td>
          <td>
            <input type='checkbox' name='jenis' value='RJ' checked>Rawat Jalan
            <input type='checkbox' name='jenis' value='RI'>Rawat Inap
          </td>
        </tr>


        <tr>
          <td width=5%>NOREG</td>
          <td>:</td>
          <td><input name="noreg" id="noreg" type="text" size="20" values=""></td>
        </tr>
        
        <tr>
         <td colspan=2>&nbsp;</td>
         <td><input name="submit" type="submit" value="CETAK"></td>
       </tr>
       <tr>
         <td colspan=3><hr></td>
       </tr>

     </table>

     <!--  PopCalendar(tag name and id must match) Tags should not be enclosed in tags other than the html body tag. -->
     <iframe width=174 height=189 name="gToday:normal:calender/agenda.js" id="gToday:normal:calender/agenda.js" src="calender/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
     </iframe>

   </form>
   
 </body>
 </html>