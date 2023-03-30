<?php
include_once("../common.php");

  $get_hash = $_GET['hash'];

  $dateTime = new DateTime("now", new DateTimeZone("Asia/Seoul"));
  $date_time = $dateTime->format("Y-m-d H:i:s");

  $sql_1st = "SELECT * FROM auth_email WHERE auth_md5 = '$get_hash' ORDER BY auth_start_date DESC LIMIT 0,1";

  $result_1st = sql_query($sql_1st);

  $count_1st =sql_num_rows($result_1st);

  if($count_1st > 0){
    $row_1st = sql_fetch_array($result_1st);
    $after_ten_min = date("Y-m-d H:i:s", strtotime("+24 hours", strtotime($row_1st['auth_start_date'])));

    if($after_ten_min >= $date_time){

      if($row_1st['auth_check'] == '0'){
        $sql_2nd = "UPDATE auth_email SET auth_end_date = '$date_time', auth_check = '1' WHERE auth_md5='$get_hash'";
        $result_2nd = sql_query($sql_2nd);
  
        if($result_2nd){
          echo "<script>alert('인증완료되었습니다. 회원가입을 진행해주세요.');
                self.close(); </script>";
        }else{
          echo "<script>alert('죄송합니다. 다시 시도해주세요.');
                self.close(); </script>";
        }
  
  
      }else{

        $sql_3rd = "UPDATE auth_email SET auth_check = '2' WHERE auth_md5='$get_hash'";
	      $result_3rd = sql_query($sql_3rd);
        echo "<script>alert('만료된 URL 입니다. 다시 시도해주세요.');
              self.close(); </script>";
      }
  
  
    }else{
      echo "<script>alert('만료된 URL 입니다. 다시 시도해주세요.');
            self.close(); </script>";
    }

  }else{
    echo "<script>alert('유효하지않은 인증 코드입니다. 다시 인증하기를 눌러주세요.');
    self.close(); </script>";
  }

  

  






 ?>
