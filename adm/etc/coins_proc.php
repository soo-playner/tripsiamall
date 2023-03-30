<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');

// $debug=1;
// include_once('../bonus/bonus_inc.php');

// print_R($_POST);
$to_date_str = date("mdhis");
$to_date = date("Y-m-d");
$now_datetime = date('Y-m-d H:i:s');
$function  = $_POST['func'];

if($function == 'add'){
   
    $update_msg_templete = 
    "INSERT INTO `coins` set 
    id = '{$_POST['id']}', name = '{$_POST['name']}', krname = '{$_POST['krname']}' ";
    
    $result = sql_query($update_msg_templete);

    if($result){
        ob_clean();
        echo json_encode(array("result" => "success"));
    }

}else if($function == 'del'){
    $selected_list = $_POST['selected'];

    for($i = 0 ; $i < count($selected_list); $i ++){
        $idx = $selected_list[$i];
        // print_R($idx);
        if($idx > 1){
            $delete_row = "DELETE FROM `coins` WHERE no = {$idx} ";
            // print_R($delete_row);

            $result = sql_query($delete_row);
        }else{
            ob_clean();
            echo json_encode(array("result" => "failed","sql" => "사용중인 코인은 삭제할수없습니다."));
        }
    }

    if($result){
        ob_clean();
        echo json_encode(array("result" => "success"));
    }
   
    

}else if($_POST['func'] == 'swap'){
    print_R($_POST);

    $origin_coin_id = $_POST['origin_coin_id'];;
    $origin_coin_price = $_POST['origin_coin_price'];

    $change_coin_id = $_POST['change_coin_id'];
    $change_coin_price = $_POST['change_coin_price'];

    $change_coin_val = $_POST['change_val'];

    echo "<br>회원 데이터 테이블 백업 ............. ";
    $copy_member_table_sql = "CREATE table g5_member_{$to_date_str} (select * from g5_member)";
    // $sql_result = true;
    $sql_result = sql_query($copy_member_table_sql);
    $result = echo_result($sql_result);
    
    echo "<br>회원 마이닝 잔고 변환 ............. ";
    $member_sql = "SELECT *, ($mining_target - $mining_amt_target) as mining_have FROM g5_member WHERE $mining_target > 0";
    $member_list = sql_query($member_sql);
    $member_list_cnt = sql_num_rows($member_list);
    
    $i = 0;
    while($row = sql_fetch_array($member_list)){
            /* echo "<br>";
            echo $row['mb_id'];
            echo " | ";
            echo $row['mining_have'];
            echo " ===>";
            echo "<span class='blue'>".calculate_math($row['mining_have'] * $change_coin_val,4)."</span>"; 
            echo "<br>"; */
        $shift_coin_value = calculate_math($row['mining_have'] * $change_coin_val,4);
        
        $rec = "Mining Coin Changed :: ".calculate_math($row['mining_have'],4)." ".$origin_coin_id." shift ".$shift_coin_value." ".$change_coin_id;
        $rec_adm = "1 ".$origin_coin_id."(￦".$origin_coin_price.") / ".$change_coin_val." ".$change_coin_id;

        $insert_change_log = "INSERT INTO soodang_mining SET day='{$to_date}',
        allowance_name = 'system_log',
        mb_id = '{$row['mb_id']}',
        mining = '{$shift_coin_value}',
        currency = '{$change_coin_id}',
        rate = '{$change_coin_val}',
        rec = '{$rec}', rec_adm = '{$rec_adm}',
        datetime = '{$now_datetime}'";

            // echo $insert_change_log."<br>";
            // $insert_log_result =true;
        $insert_log_result = sql_query($insert_change_log);

        if($insert_log_result){
            $update_member_sql = "UPDATE g5_member SET $mining_target = {$shift_coin_value}, $mining_amt_target = 0 WHERE mb_id = '{$row['mb_id']}' ";
            // echo $update_member_sql."<br><br>";
            $update_result = sql_query($update_member_sql);

            if($update_result){
                $i++;
            }
        }
    }
    
    if($i == $member_list_cnt){
        echo_result($update_result);
    }else{
        echo "<span class='sys_log red'>FAIL</span>";
    }

    /*$insert_sql = "INSERT INTO `app_msg` (name,title,contents,variable,images,used) VALUE ('','','','','',0)";
    
    $result = sql_query($insert_sql);

    if($result){
        echo json_encode(array("result" => "success"));
    } */
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

<?
if($func == 'swap'){
    include_once('../bonus/bonus_footer.php');
}
?>

<?
if($debug){}else{
    /* $html = ob_get_contents();
    //ob_end_flush();
    $logfile = G5_PATH.'/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
    file_put_contents($logfile, ob_get_contents()); */
}
?>