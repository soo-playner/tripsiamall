<?php
include_once "./php/config.php";

//checksum
$temp = $serviceId.$_POST['od_id'].$_POST['amount'];
$cmd = sprintf("%s \"%s\" \"%s\"", $COM_CHECK_SUM, "GEN", $temp);

$checkSum = exec($cmd);

if (!$checkSum || $checkSum == '8001'||$checkSum == '8003'||$checkSum == '8009') {
    $result = 'error';
} else {
    $result = $checkSum;
}
?>
<?=$result;?>