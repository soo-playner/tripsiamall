<?php
include_once('./_common.php');

$userId = $_POST['userId'];
$fcmToken = $_POST['fcmToken'];

if($userId != ''){

  $sql = "UPDATE g5_member SET fcm_token = '{$fcmToken}' WHERE mb_id = '{$userId}' ";
  $result = sql_query($sql);

  if($result){
    $result = "OK";
  }else{
    $result = "FAILED";
  }

  echo json_encode(array("result"=>$result));
}
?>
