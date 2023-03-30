<?php
include_once('./_common.php');

$mb_id = $_POST['mb_id'];
$avatar_no = $_POST['avatar_no'];
$avatar_target =$_POST['avatar_target'];
$avatar_rate = $_POST['avatar_rate'];
$mode = $_POST['mode'];

if($_POST['idx']){
    $idx = $_POST['idx'];
}else{
    $avatar_cnt_sql = "select * from avatar_savings where mb_id = '{$mb_id}' order by update_date desc limit 0,1";
    $av_result = sql_fetch($avatar_cnt_sql);
    $idx = $av_result['idx'];
}

if($_GET['debug']){
    $mb_id = 'cwo2535';
    $avatar_no = '1';
    $avatar_target = '4000';
    $avatar_rate= '30';
    $idx = '2';
    $mode ='u';
}

print_r($idx);

$now_date_time = date('Y-m-d H:i:s');
echo "<br>";
print_r($now_date_time);

$now_date = date('Y-m-d');
echo "<br>";
print_r($now_date);
//$avatar_id = $mb_id."_BT".$avatar_no;



$avatar_sql = "select * from avatar_savings where mb_id = '{$mb_id}' and idx = '{$idx}' ";
$av = sql_fetch($avatar_sql);

//print_r($av);
//echo "<br>";


if($idx != '0'){
    if($av['status'] == '1'){
        $avatar_no = $av['avatar_no'] + 1;
        $avatar_id = $mb_id.$av['avatar_character'].$avatar_no;
    }else{
        $mode ='u';
    }
}else{
    $char = generateRandomCharString(2);
    $avatar_id = $mb_id.$char.$avatar_no;
}



// 아바타 정보 신규생성
if($mode == 'w'){
    $status = '0';
    // 아바타 세팅 저장
    $sql = "INSERT avatar_savings set
            mb_id             = '".$mb_id."'
            , avatar_no     = '".$avatar_no."'
            , avatar_id     = '".$avatar_id."'
            , saving_target = '".$avatar_target."'
            , saving_rate           = '".$avatar_rate."'
            , current_saving   = '0'
            , status         = '{$status}'
            , setting_date    = '".$now_date_time."'
            , update_date    = '".$now_date_time."'
            , avatar_character    = '".$char."' ";

    if($_GET['debug']){   
        print_r('신규생성');
        echo "<br>";
        print_R($sql);
        $rst ='1';
    }else{
        $rst = sql_query($sql, false);

        if($rst){
            echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $sql)));
        }
        else{
            echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
        }
    }
}

// 아바타 정보 업데이트
else if($mode == 'u'){
    
    $sql = "UPDATE avatar_savings set
            saving_target = '".$avatar_target."'
            , saving_rate           = '".$avatar_rate."'
            , update_date    = '".$now_date_time."'
            where idx = '{$idx}'";
        
        print_r($sql);

        $rst = sql_query($sql, false);
        $mem_st = avatar_add($idx, $mb_id);

        if(!$mem_st){
            if($rst){
                echo (json_encode(array("result" => "success",  "code" => "0020", "sql" => 'update complete')));
            }
            else{
                echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" =>'에러발생')));
            }
        }else{
            echo (json_encode(array("result" => "success",  "code" => "0030", "sql" => 'avatar create')));
        }
}
else if($mode == 'c'){

    avatar_add($idx, $mb_id);
}



// 아바타 계정 생성
function avatar_add($idx, $mb_id){

    global $now_date_time;
    global $now_date;

    if($_GET['debug']){   
     echo "<br> 멤버추가 </br>";
    }

    $mb_avatar_sql = "select * FROM g5_member AS A INNER JOIN avatar_savings AS B ON A.mb_id = B.mb_id WHERE A.mb_id = '{$mb_id}' AND B.idx = '{$idx}'";
    $result = sql_fetch($mb_avatar_sql);
    $result_memo = "아바타생성 ".$result['avartar_no'];

    //print_r($result['current_saving']."/".$result['saving_target']."/".$result['status']);

    if($result['current_saving'] >= $result['saving_target'] && $result['status'] =='0'){

        $depth_sql = "SELECT mb_no as recom_no, depth+1 as mb_depth FROM g5_member WHERE mb_id ='{$mb_id}'";
        $depth_result = sql_query($depth_sql);
        $depth = $depth_result['mb_depth'];

        $member_add_sql = "insert g5_member set
        mb_id             = '".$result['avatar_id']."'
        , mb_recommend     = '".$mb_id."'
        , mb_recommend_no     = '".$result['mb_no']."'
        , mb_deposit_point = '".$result['current_saving']."'
        , mb_deposit_acc = '".$result['current_saving']."'
        , mb_password = '".$result['mb_password']."'
        , mb_nick_date = '".$now_date."'
        , depth = '".$depth."'
        , mb_email = '".$result['mb_email']."'
        , mb_hp = '".$result['mb_hp']."'
        , mb_datetime = '".$now_date_time."'
        , mb_email_certify = '".$now_date_time."'
        , mb_email_certify2 = '".$result['mb_email_certify2']."'
        , mb_open_date = '".$now_date."'
        , last_name = '".$result['last_name']."'
        , first_name = '".$result['first_name']."'
        , nation_number = '".$result['nation_number']."'
        , mb_memo           = '". $result_memo."'";


        print_R($member_add_sql);
        $mem_create = 1;

        if($mem_create){
            $update_sql = "UPDATE avatar_savings set
            create_date = '".$now_date_time."'
            , status           = '1'
            , update_date    = '".$now_date_time."'
            where idx = '{$idx}'";

            echo "<br>";
            print_R( $update_sql);
            $update_avatar = 1;
            
            if($update_avatar){
                return true;
                //echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $sql)));
            }
        }

    }else{
         return false;
    }
}


function generateRandomCharString($length = 3) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>