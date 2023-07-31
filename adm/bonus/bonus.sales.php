<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');


$debug = false;

$yesterday = date('Y-m-d', $timestr);

// 직급 수당
$bonus_row = bonus_pick($code);
$bonus_limit = $bonus_row['limited']/100;

// 직급 사용 단계
$bonus_rate_array_cnt = 7;


// $bonus_rate = explode(',',$bonus_row['rate']);

$bonus_condition = $bonus_row['bonus_condition'];
$bonus_condition_tx = bonus_condition_tx($bonus_condition);


$bonus_layer = $bonus_row['layer'];
$bonus_rate = $bonus_layer;
$bonus_layer_tx = bonus_layer_tx($bonus_layer);
$bonus_limited = $bonus_row['limited'];

$company_sales = $bonus_row['layer'];

//어제 매출 합계 
$total_order_query = "SELECT SUM(od_cash) AS hap FROM g5_shop_order WHERE od_date = '{$yesterday}'";
$total_order_reult = sql_fetch($total_order_query);
$total_order = $total_order_reult['hap'];

$sales_order = ($total_order * ($company_sales * 0.01));


// 디버그 로그 
if($debug){
	echo "매출 합계 - <code>";
    print_r($total_order_query);
	echo "</code><br>";
}


function rate_txt($val){
    $list = explode(',',$val);
    $i =0;

    while($i < count($list)){
        echo $list[$i]."% ";
        $i++;
    }
}

ob_start();

// 설정로그 
echo "<strong>세일즈 수당 지급비율 : ";
print_R(rate_txt($company_sales));
echo "   </strong> |    지급조건 : <span class='blue big'>".$bonus_condition."</span><br>";
echo "<br><strong> 현재일 : ".$bonus_day." |  ".$half."(매출산정기준) : <span class='red'>".$yesterday."</span> | ".$half." PV 합계 : <span class='blue big'>".Number_format($total_order).' '.$curencys[1]."</span>  </strong><br>";
echo "<br> 세일즈수당 대상금액 : <span class='blue big'>".$company_sales."% = ".Number_format($sales_order).' '.$curencys[1]."</span>";
echo "<br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>

<html><body>
<header>정산시작</header>    
<div>

<?
if($sales_order > 0){
    excute();
}else{
    echo "<span class='red'>해당 기간 기준매출 없음</span>";
}

function  excute(){

    global $g5,$admin_condition,$pre_condition;
    global $bonus_day, $bonus_condition, $bonus_rate_array_cnt, $code, $bonus_rate,$bonus_limit,$total_order,$Khan_order,$sales_order,$cnt_arr,$cnt_arr2;
    global $debug,$prev_m,$yesterday;

    
        $sql = "SELECT * FROM {$g5['member_table']} WHERE mb_id in ('{$bonus_condition}') " ;
        $result = sql_query($sql);

        $star_rate = $bonus_rate*0.01;
        $star_rate_tx = $bonus_rate."%";


        // echo "<br><br><span class='title block'>".$grade_name." (".$member_count.") - ".$star_rate_tx."</span><br>";

        // 디버그 로그 
        if($debug){
            echo "<code>";
            echo($sql);
            echo "</code><br>";
        }
       
        while($row = sql_fetch_array($result)){
        
            $mb_no=$row['mb_no'];
            $mb_id=$row['mb_id'];
            $mb_name=$row['mb_name'];
            $mb_level=$row['mb_level'];
            $mb_deposit=$row['mb_deposit_point'];
            $mb_balance=$row['mb_balance'];
            $grade=$row['grade'];
            

            echo "<br><br><span class='title' >".$row['mb_id']."</span> <br>";

                
                $benefit = ( ($total_order*$star_rate) );
                
                $benefit_tx = ' '.$total_order.' * '.$star_rate.' = '.$benefit; 
                $benefit_limit = $benefit;

                
                echo $benefit_tx;
                
                $rec= $code.' Bonus from '.$yesterday .' PV';
                $rec_adm= $yesterday." | ".$benefit_tx;
                
                $benefit = shift_auto($benefit,'usdt');

                echo "<span class=blue> ▶▶ 수당 지급 : ".$benefit."</span><br>";
        

                if($benefit > 0 && $benefit_limit > 0){

                    $record_result = soodang_record($mb_id, $code, $benefit_limit,$rec,$rec_adm,$bonus_day);
    
                    if($record_result){
                        
                        $balance_up = "update g5_member set mb_balance = mb_balance + {$benefit_limit}, mb_balance_ignore = mb_balance_ignore + {$benefit_limit}  where mb_id = '".$mb_id."'";

                        // 디버그 로그
                        if($debug){
                            echo "<code>";
                            print_R($balance_up);
                            echo "</code>";
                        }else{
                            sql_query($balance_up);
                        }
                    }
                    
                }

            
 
            } // if else
        } //while
        $rec='';
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