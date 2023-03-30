<?php
$sub_menu = "600200";
include_once('./_common.php');

// $debug=1;
include_once('./bonus_inc.php');
auth_check($auth[$sub_menu], 'r');

$category = 'mining';

if(!$debug){
    $dupl_check_sql = "select mb_id from {$g5['mining']} where day='".$bonus_day."' and allowance_name = '{$code}' ";
    $get_today = sql_fetch( $dupl_check_sql);

    if($get_today['mb_id']){
        alert($bonus_day.' '.$code." 수당은 이미 지급되었습니다.");
        die;
    }
}

if( !function_exists( 'array_column' ) ):
    
    function array_column( array $input, $column_key, $index_key = null ) {
    
        $result = array();
        foreach( $input as $k => $v )
            $result[ $index_key ? $v[ $index_key ] : $k ] = $v[ $column_key ];
        
        return $result;
    }
endif;

//슈퍼마이닝 초기화 
$super_mining_reset = sql_query("UPDATE g5_member set super_mining = 0;");

//회원 리스트를 읽어 온다.
$sql_common = " FROM soodang_mining";
$sql_search=" WHERE DAY = '{$bonus_day}' AND allowance_name != 'mining'";
$sql_mgroup=" GROUP BY mb_id ORDER BY mb_id asc";

$pre_sql = "select mb_id, SUM(hash) as hash_rate, ROUND(SUM(mining),8) AS total
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


// 마이닝지급량
$mining = bonus_pick('mining');
$mining_rate = $mining['rate'];

ob_start();


// 설정로그 
echo "<span class ='title' style='font-size:20px;'>".$bonus_row['name']." 수당 정산</span><br>";
echo "<span style='border:1px solid black;padding:10px 20px;display:block;font-weight:600;'> MINING 지급비율 : ". $mining_rate." mh/s  </span><br>";
echo "<strong>".strtoupper($code)." 수당 지급비율 : <span class='red'>". $bonus_row['rate']."% </span> </strong> |    지급조건 -".$pre_condition_in.' | '.$bonus_condition_tx." | ".$bonus_layer_tx." | ".$bonus_limit_tx."<br>";
echo "<strong>".$bonus_day."</strong><br>";
echo "<br><span class='red'> 기준대상자(매출발생자) : ".$result_cnt."</span><br><br>";
echo "<div class='btn' onclick=bonus_url('".$category."')>돌아가기</div>";
?>

<html><body>
<header>정산시작</header>    
<div>

<?
$mem_list = array();

if($result_cnt > 0){
    excute();
}

function  excute(){

    global $result;
    global $g5, $bonus_day, $bonus_condition, $code, $bonus_rates, $bonus_rate,$pre_condition_in,$bonus_limit,$bonus_layer;
    global $minings,$mining_target,$mining_amt_target,$mem_list,$mining_rate,$mining,$now_mining_coin;
    global $debug;
    
    for ($i=0; $row=sql_fetch_array($result); $i++) {   

        $mb_origin=$row['mb_id'];
        $super_rate = $row['hash_rate'];
        $bonus_total = $row['total'];
        $bonus_rates = $bonus_rate;

        echo "<br><br><span class='title block gold' style='font-size:30px;'>".$mb_origin."</span><br>";
        
        echo "<br>";
        echo "▶▶금일 슈퍼마이닝 해쉬파워 :: <span class='blue'>".$super_rate." mh/s </span>";
        echo "<br>";

        echo "▶▶▶데일리 마이닝지급량  :  <span class='blue'>".$mining_rate." eth</span> / 1mh";
        // echo "▶▶▶▶ 슈퍼마이닝 지급량 : ".$bonus_rates."<br>";

        // 스폰서(직추천인)
        $mb_id = sql_fetch("SELECT mb_recommend from g5_member WHERE mb_id = '{$mb_origin}' ")['mb_recommend'];
        echo "<div class='box title'> 직추천인(스폰서) : <span class='blue'>".$mb_id."</span></div>";

        // 직추천자수 
        $mem_cnt_sql = "SELECT count(*) as cnt,(SELECT mb_index from g5_member WHERE mb_id = '{$mb_id}' ) as mb_index FROM g5_member where mb_recommend = '{$mb_id}' AND mb_level > 0 AND rank > 1 ";
        $mem_cnt_result = sql_fetch($mem_cnt_sql);
        $mem_cnt = $mem_cnt_result['cnt'];
        $mem_index = $mem_cnt_result['mb_index'];

        if($mem_index == 2 && $mem_cnt < $mem_index){
            $mem_cnt = $mem_index;
        }


        if($mem_cnt < 2){
            $mem_cnt_color = "red";
        }else{
            $mem_cnt_color = "blue";
        }


        echo "▶▶▶▶▶ 직추천인(스폰서)의 LV1 직추천인 : <span class='".$mem_cnt_color."'>".$mem_cnt."</span>";
        echo "<br><br>";

        $benefit = ($super_rate*$mining_rate)*$bonus_rates;

         // 계산식
        
        echo "<code>";
        echo "수당계산 : ".$super_rate.' * '.$mining_rate.' * '.$bonus_rates.' = '.$benefit;
        echo "</code>";

        list($mb_balance,$balance_limit,$benefit_limit) = mining_limit_check($mb_id,$benefit,$bonus_limit,$code);

        $benefit_limit_point = shift_auto($benefit_limit,COIN_NUMBER_POINT);
        $benefit_point = shift_auto($benefit,COIN_NUMBER_POINT);

        echo "<code>";
        echo "현재수당 : ".shift_auto($mb_balance,COIN_NUMBER_POINT)."  | 수당한계 :". shift_auto($balance_limit,COIN_NUMBER_POINT).' | ';
        echo "발생할수당: ".$benefit_point." | 지급할수당 :".$benefit_limit_point;
        echo "</code><br>";

        $rec_adm = '';
        
        if($mem_cnt < 2){
            $benefit = 0;
        }
                
        if($benefit > $benefit_limit && $balance_limit != 0 ){
            $rec_adm .= "<span class=red> |  Bonus overflow :: ".shift_auto($benefit_limit - $benefit)."</span>";
            echo "<span class=blue> ▶▶ 수당 지급 : ".$benefit_point."</span>";
            echo "<span class=red> ▶▶▶ 수당 초과 (한계까지만 지급) : ".$benefit_limit_point." </span><br>";
        }else if($benefit != 0 && $balance_limit == 0 && $benefit_limit == 0){

            $rec_adm .= "<span class=red> | Sales zero :: ".shift_auto(($benefit_limit - $benefit),COIN_NUMBER_POINT)."</span>";
            echo "<span class=blue> ▶▶ 수당 지급 : ".shift_auto($benefit)."</span>";
            echo "<span class=red> ▶▶▶ 수당 초과 : ".$benefit_limit_point." </span><br>";
        }else if($benefit == 0){
            echo "<span class=blue> ▶▶ 수당 미발생 </span>";
        }else{
            echo "<span class=blue> ▶▶ 수당 지급 : ".$benefit_point."</span><br>";
        }


        if($benefit > 0 ){
            $rec=' Bonus By '.$mb_origin.' hash | '.$super_rate.' MH :: '.$benefit_limit_point.' '.$minings[$now_mining_coin];

            if($benefit_limit < $benefit){
                $rec_adm =  $super_rate.' * '.$mining_rate.' * '.$bonus_rates.' = '.$benefit_limit_point." (".$benefit_point.")";
            }else{
                $rec_adm =  $super_rate.' * '.$mining_rate.' * '.$bonus_rates.' = '.$benefit_limit_point;
            }

            $record_result = mining_record($mb_id, $code, $benefit_limit_point,$bonus_rates,$minings[$now_mining_coin], $rec, $rec_adm, $bonus_day,$super_rate,$benefit_point);

            
            if($record_result ){
                $balance_up = "update g5_member set {$mining_target} = {$mining_target} + {$benefit_limit}, super_mining = super_mining + {$super_rate}  where mb_id = '{$mb_id}' ";

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
            echo "<span class=red> ▶▶ 수당 미발생 </span>";
        }

        // $mem_list = array(); 

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