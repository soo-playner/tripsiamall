<?php
include_once('./_common.php');

// print_R($_POST);

$function  = $_POST['func'];
$cnt = count($_POST['chk']);


if($function == '저장하기'){
   
    for($i = 0 ; $i < $cnt; $i ++){
        $k = $chk[$i];
        $idx = $_POST['no'][$k];
        if($_POST['used'][$k] == 'on'){
            $check_used = 1;
        }

        $update_msg_templete = 
        "update `app_msg` set 
        name = '{$_POST['name'][$k]}',
        title = '{$_POST['title'][$k]}',
        contents = '{$_POST['contents'][$k]}',
        images = '{$_POST['image'][$k]}',
        used = '{$check_used}'
        where no = $idx ;";
        // print_R($update_msg_templete);

        sql_query($update_msg_templete);
    }

    alert('변경되었습니다.');
    goto_url('./fcm_msg.php');

}else if($function == '삭제하기'){

    for($i = 0 ; $i < $cnt  ; $i ++){
        $k = $chk[$i];
        $idx = $_POST['no'][$k];
       
        $delete_row = "DELETE FROM `app_msg` WHERE no = {$idx} ";
        // print_R($delete_row);

        sql_query($delete_row);
    }

    alert('변경되었습니다.');
    goto_url('./fcm_msg.php');
    

}else if($_POST['func'] == 'w'){

    $insert_sql = "INSERT INTO `app_msg` (name,title,contents,variable,images,used) VALUE ('','','','','',0)";
    
    $result = sql_query($insert_sql);

    if($result){
        echo json_encode(array("result" => "success"));
    }
}
?>