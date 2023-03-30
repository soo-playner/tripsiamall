<?php
$sub_menu = "200200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

check_token();

$h_id = $_POST['h_id'];
$h_day = $_POST['h_day'];
$h_name = $_POST['h_name'];


   if ($_POST['act_button'] == "수정" ){
    $sql = " update holiday
                set h_day = '$h_day',
                		h_name = '$h_name'
                   
             where h_id='$h_id'";
                  
                   
   }else if ($_POST['act_button'] == "등록") {
    $sql = " insert into holiday
                set h_day = '$h_day',
                		h_name = '$h_name'  ";
                   
   }
   

    sql_query($sql);

    
goto_url('./holiday_list.php?'.$qstr);
?>
