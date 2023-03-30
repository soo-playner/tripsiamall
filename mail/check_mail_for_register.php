<?php
include_once("../common.php");

$user_email = $_POST['user_email'];

$sql = "SELECT * FROM auth_email WHERE email='$user_email' ORDER BY id DESC LIMIT 0,1";
$result = sql_query($sql);
$count = sql_num_rows($result);

if($count > 0){

  $row = sql_fetch_array($result);

  if($row['auth_check'] == '1'){

    $dateTime = new DateTime("now", new DateTimeZone("Asia/Seoul"));
    $date_time = $dateTime->format("Y-m-d H:i:s");

    $after_ten_min = date("Y-m-d H:i:s", strtotime("+24 hours", strtotime($row['auth_end_date'])));

    if($after_ten_min >= $date_time){
      echo json_encode(array("result"=>"OK"));
    }else{
      echo json_encode(array("result"=>"FAIL_expired", "res"=>"인증 날짜를 초과하였습니다. 다시 인증해주세요!"));
    }

  
  }else{
    echo json_encode(array("result"=>"FAIL", "res"=>"이메일 인증을 해주세요!"));
  }
  
}else{
   echo json_encode(array("result"=>"FAIL", "res"=>"이메일 인증을 해주세요!"));
}


 ?>
