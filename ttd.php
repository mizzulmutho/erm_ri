<?php
$id = $_GET["id"];

session_start();
$_SESSION["id"] = $id;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Signature with jQuery & Canvas</title>
  <link href="./css/jquery.signaturepad.css" rel="stylesheet">
  <style type="text/css">
    body {
      font-family: monospace;
      text-align: center;
    }

    .signature-area {
      width: 304px;
      margin: 50px auto;
    }

    .signature-container {
      width: 60%;
      margin: auto;
    }

    .signature-list {
      width: 150px;
      height: 50px;
      border: solid 1px #cfcfcf;
      margin: 10px 5px;
    }

    .title-area {
      font-family: cursive;
      font-style: oblique;
      font-size: 12px;
      text-align: left;
    }

    .btn-save {
      color: #fff;
      background: #1c84c6;
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      line-height: 1.5;
      border-radius: 0.2rem;
      border: 1px solid transparent;
    }

    .btn-clear {
      color: #fff;
      background: #f7a54a;
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      line-height: 1.5;
      border-radius: 0.2rem;
      border: 1px solid transparent;
    }
  </style>
</head>

<body>
  <h2>Tanda Tangan Perwakilan Perusahaan</h2>
  <div class="signature-area">
    <h2 class="title-area">Put signature,</h2>
    <div class="sig sigWrapper" style="height:auto;">
      <div class="typed"></div>
      <canvas class="sign-pad" id="sign-pad" width="300" height="200"></canvas>
    </div>
  </div>
  <button class="btn-save">Save</button>
  <button class="btn-clear">Clear</button>
<!--   <div class="signature-container">
    <?php
    $signature_list = glob("./signature/*.png");
    foreach ($signature_list as $s) {
    ?>
      <img src="<?php echo $s; ?>" class="signature-list" />
    <?php
    }
    ?>
  </div> -->
  <script src="./js/jquery.min.js"></script>
  <script src="./js/numeric-1.2.6.min.js"></script>
  <script src="./js/bezier.js"></script>
  <script src="./js/jquery.signaturepad.js"></script>
  <script src="./js/html2canvas.js" type='text/javascript'></script>
  <script src="./js/json2.min.js"></script>
  <script>
    $(document).ready(function() {
      $(".signature-area").signaturePad({
        drawOnly: true,
        drawBezierCurves: true,
        lineTop: 90
      });

      $(".btn-clear").click(function(e) {
        $(".signature-area").signaturePad().clearCanvas();
      });
    });

    $(".btn-save").click(function(e) {
      html2canvas([document.getElementById('sign-pad')], {
        onrendered: function(canvas) {
          var canvas_data = canvas.toDataURL('image/png');
          var img_data = canvas_data.replace(/^data:image\/(png|jpg);base64,/, "");
          $.ajax({
            url: 'save.php',
            data: {
              img_data: img_data
            },
            type: 'post',
            dataType: 'json',
            success: function(response) {
              // window.location.reload();
              history.go(-1);
            }
          });
        }
      });
    });
  </script>
</body>

</html>