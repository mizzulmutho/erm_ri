<?php    
include "phpqrcode/qrlib.php";    

QRcode::png("test", "image.png", "L", 4, 4);   

echo "<image src='image.png'>";

?>