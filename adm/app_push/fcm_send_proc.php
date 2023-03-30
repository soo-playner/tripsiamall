<?php
$sub_menu = "800100";
include_once('./_common.php');
include_once(G5_LIB_PATH.'/fcm_push/push.php');

/* print_R($_POST);
echo "<br><br><br>"; */

/* "제타바이트 📊 내마이닝 해시: 150 mh/s",
"총보너스 해시 : 2176.58 mh/s (📈12.36 mh/s)   금일 마이닝 총수량 : 0.00842682 ETH ",
"fR5YE6VRdf8:APA91bH_aHv1XV2n2xyS9uok8u2tnWoRD4YuQsTsbVCxhIPuPA_lAfspM8I8T7b3sYZjvoOAQTAYitKiHJbRgqaKzcOnx2TYH-0L5_bp9CWjQOk71GQu62sINYk1GMw_IRjKePH-B6Bo"
 */

$now_date_time = date('Y-m-d H:i:s');

$wild_code = "📈,📉";

$contents_code = $_POST['contents_code'];
$contents_sql = "SELECT * from app_msg WHERE no = {$contents_code}";
$get_contents = sql_fetch($contents_sql);

// $msg_title = "제타바이트 📊 내마이닝 해시: {code1} mh/s";
// $msg_contents = "총보너스 해시 : {code2} mh/s {code3} mh/s   금일 마이닝 총수량 : {code4} ETH ";

if($contents_code == 99){
    $msg_title = $_POST['contents_title'];
    $msg_contents =  $_POST['contents_content'];
    $msg_img = G5_URL.$_POST['images'];
}else{
    $msg_title = $get_contents['title'];
    $msg_contents = $get_contents['contents'];
    $msg_img = G5_URL.$get_contents['images'];
}




function replace_code($str,$val,$key = 0,$mb_id){
    
    if(is_array($val)){

        for($i= key($val); $i < (key($val)+count($val)); $i++){
            $str = str_replace('{code'.$i.'}',$val[$i],$str);
        }
        $str = str_replace('{mb_id}',$mb_id,$str);
        $result = $str;

    }else{
        $str = str_replace('{mb_id}',$mb_id,$str);
        $result = str_replace('{code1}',$val,$str);
    }

    return $result;
}


$count = count($_POST['chk']);

if (!$count)
    alert("메세지 보내실 항목을 하나 이상 체크하세요.");

for ($i=0; $i<$count; $i++)
{
    $k = $chk[$i];

    $mb_id = $chk_id[$k];
    $fcm_token = sql_fetch("SELECT fcm_token FROM g5_member WHERE fcm_token !='' AND mb_id  = '{$mb_id}' ")['fcm_token'];
    
    if($contents_code == 1){
        $code_val1 = $mining_total[$k];
        $fcm_title = replace_code($msg_title,$code_val1,1,$mb_id);

        $code_val2[2] = $all_hash[$k];
        $code_val2[3] = $all_diff[$k];
        $code_val2[4] = $mb_rate[$k];

        $fcm_contents = replace_code($msg_contents,$code_val2,2,$mb_id);

    }else{
        $fcm_title = replace_code($msg_title,$code_val1,1,$mb_id);
        $fcm_contents = replace_code($msg_contents,$code_val2,2,$mb_id);
    }

    $msg_img = $msg_img;
    

    /* echo $chk_id[$k];
    echo "<br>";
    print_R($fcm_title);
    echo "<br>";
    print_R($fcm_contents);
    echo "<br>";
    print_R($fcm_token); */


    $send_result = setPushData($fcm_title,$fcm_contents,$fcm_token,$msg_img);

    
    $send_log = "INSERT INTO msg_send_log (mb_id,title,contents,datetime,fcm_token) VALUES 
    ('{$mb_id}','{$fcm_title}','{$fcm_contents}','{$now_date_time}','{$fcm_token}')";

    $log_result = sql_query($send_log);
    
}


if($log_result){
    ob_clean();
    alert('메세지가 전송 되었습니다.',0);
    goto_url("./fcm_memberlist.php");
}

?>