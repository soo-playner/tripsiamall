<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');

$to_date_str = date("mdhis");
$to_date = date("Y-m-d");
$now_datetime = date('Y-m-d H:i:s');
$function  = $_POST['func'];

$mb_id = $member['mb_id'];


if($_POST['func'] == 'swap' || !$mb_id != ''){
    print_R($_POST);

    $origin_coin_id = $_POST['origin_coin_id'];;
    $origin_coin_price = $_POST['origin_coin_price'];

    $change_coin_id = $_POST['change_coin_id'];
    $change_coin_price = $_POST['change_coin_price'];

    $change_coin_val = $_POST['change_val'];

    $inhave_sql = "SELECT * FROM soodang_mining WHERE allowance_name = 'coin swap' AND mb_id = '{$mb_id}' ";
    $inhave_result = sql_num_rows(sql_query($inhave_sql));

    if($inhave_result > 0){
        ob_clean();
        echo (json_encode(array("result" => "failed",  "code" => "0005", "sql" => '이미처리된 요청입니다.'), JSON_UNESCAPED_UNICODE));
    }else{

        echo "<br>회원 마이닝 잔고 변환 ............. ";
        $member_sql = "SELECT *, ($before_mining_target - $before_mining_amt_target) as mining_have FROM g5_member WHERE mb_id = '{$mb_id}' ";
        $member_list = sql_fetch($member_sql);

        $shift_coin_value = calculate_math($member_list['mining_have'] * $change_coin_val,4);

        /* echo "<br>";
        echo $member_list['mb_id'];
        echo " | ";
        echo $member_list['mining_have'];
        echo " ===>";
        echo "<span class='blue'>".calculate_math($member_list['mining_have'] * $change_coin_val,4)."</span>"; 
        echo "<br>"; */
        
        $rec = "Coin Swap :: ".calculate_math($member_list['mining_have'],4).$origin_coin_id." >> ".$shift_coin_value.$change_coin_id;
        $rec_adm = "1 ".$origin_coin_id."(￦".(shift_kor($origin_coin_price)).") / ".$change_coin_val." ".$change_coin_id." RATES";

        $insert_change_log = "INSERT INTO soodang_mining SET day='{$to_date}',
        allowance_name = 'coin swap',
        mb_id = '{$member_list['mb_id']}',
        mining = '{$shift_coin_value}',
        currency = '{$change_coin_id}',
        rate = '{$change_coin_val}',
        rec = '{$rec}', rec_adm = '{$rec_adm}',
        datetime = '{$now_datetime}'";

            /* echo $insert_change_log."<br>";
            $insert_log_result =true; */
        $insert_log_result = sql_query($insert_change_log);

        if($insert_log_result){
            $update_member_sql = "UPDATE g5_member SET swaped = 1, swap_date = '{$to_date}', $mining_target = ($mining_target + $shift_coin_value) WHERE mb_id = '{$member_list['mb_id']}' ";
            // echo $update_member_sql."<br><br>";
            // $update_result = true;
            $update_result = sql_query($update_member_sql);
            

            if($update_result){
                ob_clean();
                echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => 'Swap success')));
            }else{
                echo (json_encode(array("result" => "failed",  "code" => "0003", "sql" => 'Update failed')));
            }
        }else{
            echo (json_encode(array("result" => "failed",  "code" => "0004", "sql" => 'Create swap log failed')));
        }
    }


}else{
    echo (json_encode(array("result" => "failed",  "code" => "0002", "sql" => 'Swap Failed for does not match Value')));
}

function echo_result($sql_result){
    global $processing;
    if($sql_result){
        $processing += 10; 
        echo "<span class='sys_log blue'>OK</span>";
    }else{
        echo "<span class='sys_log red'>FAIL</span>";
        return false; 
    }
}

?>

