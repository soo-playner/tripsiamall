<?php
include_once('./_common.php');

$mb_id = $_POST['mb_id'];
$pin = $_POST['pin'];

$sql = "SELECT reg_tr_password FROM g5_member WHERE mb_id = '$mb_id'";

$result = sql_query($sql);

$row = sql_fetch_array($result);

if(check_password($pin,$row['reg_tr_password'])){
  echo json_encode(array("response"=>"OK"));
}else{
  echo json_encode(array("response"=>"FAIL"));
}

 ?>
