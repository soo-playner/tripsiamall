<?php
$sub_menu = "600200";
include_once('./_common.php');
// $debug=1;
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');
$category = 'mining';

// $debug =1;

if(!$debug){
    $dupl_check_sql = "select mb_id from {$g5['mining']} where day='".$bonus_day."' and allowance_name = '{$code}' ";
    $get_today = sql_fetch( $dupl_check_sql);

    if($get_today['mb_id']){
        alert($bonus_day.' '.$code." 수당은 이미 지급되었습니다.");
        die;
    }
}

// 마이닝 컬럼 확인 
$pre_sql = sql_fetch("SHOW COLUMNS FROM g5_member WHERE `Field`= '{$mining_target}' ");

if(!$pre_sql){
    $sql = "ALTER TABLE g5_member ADD COLUMN `{$mining_target}` DOUBLE NULL DEFAULT '0' AFTER `mb_shift_amt`,
    ADD COLUMN `{$mining_amt_target}` DOUBLE NULL DEFAULT '0'  AFTER `{$mining_target}` ";
    echo $sql;
    sql_query($sql);
}

//회원 리스트를 읽어 온다.
$sql_common = " FROM g5_member";
$sql_search=" WHERE mb_rate > 0 AND mb_level < 9";
$sql_mgroup=" ORDER BY mb_no asc";

$pre_sql = "select * 
            {$sql_common}
            {$sql_search}
            {$sql_mgroup}";


if($debug){
    echo "<code>";
    print_r($pre_sql);
    echo "</code><br>";
}

$pre_result = sql_query($pre_sql);
$result = $pre_result;
$result_cnt = sql_num_rows($pre_result);

$tp_list = get_shop_item();
$tp = array();
for($i=0; $i < count($tp_list); $i++){
    array_push($tp,$tp_list[$i]['it_supply_point']);
}

ob_start();

// 설정로그 
echo "<span class ='title' style='font-size:20px;'>".$bonus_row['name']." 수당 정산</span><br>";
echo "<strong>".strtoupper($code)." 마이닝 지급비율 :  <span class='red'>". $bonus_row['rate']."</span>  </strong> |    지급조건 -".$pre_condition.' | '.$bonus_condition_tx." | ".$bonus_layer_tx." | ".$bonus_limit_tx."<br>";
echo "<strong>".$bonus_day."</strong><br>";
echo "<br><span class='red'> 기준대상자(매출발생자) : ".$result_cnt."</span><br><br>";
echo "<div class='btn' onclick=bonus_url('".$category."')>돌아가기</div>";

?>

<html><body>
<header>정산시작</header>    
<div>
<?

excute();


function  excute(){

    global $result;
    global $g5, $bonus_day, $bonus_condition, $code, $bonus_rates, $bonus_rate,$pre_condition_in,$bonus_limit;
    global $tp,$minings,$mining_target,$mining_amt_target,$now_mining_coin ;
    global $debug;

    

    for ($i=0; $row=sql_fetch_array($result); $i++) {   

        $mb_id=$row['mb_id'];
        $mb_rate = $row['mb_rate'];
        $mb_balance = $row['mb_balance'];
        $bonus_rates = $bonus_rate*100;
        
        // $hash_power = Number_format(($mb_rate/$tp[0]),2);
        $hash_power = $mb_rate;

        $benefit = $hash_power*$bonus_rates;

        echo "<br><br><span class='title block gold' style='font-size:30px;'>".$mb_id."</span><br>";

        // 계산식
        echo "보유해쉬파워 : <span class='red'>".$hash_power." mh/s</span>";
        echo "<br><br>";
        echo "마이닝수당 : <span class='blue'>".$benefit."</span>";
        

        if($benefit > 0){
            $rec=$code.' Bonus By '.$hash_power.' mh/s :: '.$benefit.' '.$minings[$now_mining_coin];
            $rec_adm = $rec;
            echo "<span class=blue> ▶▶ 마이닝 지급 : ".$benefit.' '.$minings[$now_mining_coin]."</span><br>";

            $record_result = mining_record($mb_id, $code, $benefit,$bonus_rates,$minings[$now_mining_coin], $rec, $rec_adm, $bonus_day);

            
            if($record_result){
                $balance_up = "update g5_member set {$mining_target} = {$mining_target} + {$benefit}  where mb_id = '{$mb_id}' ";

                // 디버그 로그
                if($debug){
                    echo "<code>";
                    print_R($balance_up);
                    echo "</code>";
                }else{
                    sql_query($balance_up);
                }
            }
        }else{
            echo "<span class=blue> ▶▶ 수당 지급 : ".$benefit.' '.$minings[$now_mining_coin]."</span><br>";
        }

    } // for
}
?>

<?include_once('./bonus_footer.php');?>

<?
if($debug){}else{
    $html = ob_get_contents();
    //ob_end_flush();
    $logfile = G5_PATH.'/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
    file_put_contents($logfile, ob_get_contents());
}
?>