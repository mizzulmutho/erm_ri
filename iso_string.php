<?php

echo '2021-09-10T08:00:00+00:00';
echo '<br>';

$date = new DateTime();
echo $date->format('Y-m-d\TH:i:sO');

echo "<hr>";

$datetime = new DateTime('2021-01-03 02:30:00', new DateTimeZone('Europe/Berlin'));

echo $datetime->format(DateTime::ATOM); // output: '2021-01-03T02:30:00+01:00'


echo "<hr>";
$tgl1		= gmdate("Y-m-d H:i:s", time()+60*60*7);
$datetime = new DateTime($tgl1, new DateTimeZone('Asia/Jakarta'));

echo $datetime->format('c'); // output: '2021-01-03T02:30:00+01:00'

?>