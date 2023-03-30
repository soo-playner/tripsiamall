<?php
include_once('./_common.php');

$auth_pw = $_POST['auth_pw'];
$mb_email = $_POST['mb_email'];
$mb_id = $_POST['mb_id'];

$sql = "SELECT * FROM g5_member WHERE mb_id = '{$mb_id}' AND mb_email = '{$mb_email}'";
$result = sql_query($sql);
$cnt = sql_num_rows($result);
if ($cnt > 0) {

    $password = get_encrypt_string($auth_pw);
    $sql = "UPDATE g5_member SET mb_password = '{$password}' WHERE mb_id = '{$mb_id}' AND mb_email = '{$mb_email}'";
    $result = sql_query($sql);

    if ($result) {
        echo json_encode(array("code" => "00001"));
    }
}

