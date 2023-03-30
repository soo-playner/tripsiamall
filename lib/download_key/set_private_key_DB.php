<?php
define("_GNUBOARD_", TRUE);
include_once('../../data/dbconfig.php');
$conn = mysqli_connect(G5_MYSQL_HOST,G5_MYSQL_USER,G5_MYSQL_PASSWORD,G5_MYSQL_DB);

if(isset($_POST['func']) && $_POST['func'] == "push"){
    $private_sql = "UPDATE g5_member SET eth_download = '1' WHERE mb_id = '{$_POST['mb_id']}'";
    mysqli_query($conn,$private_sql);
}

?>