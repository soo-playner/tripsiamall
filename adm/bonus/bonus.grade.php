<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');


// $debug = 1;


// $month = date('m', $timestr);

// echo "이번달 : ".$month;



/* 
if($day > 13 && $day <= 20){
    $half = '1/2';
    $half_frdate    = date('Y-m-1', $timestr); // 매월 1 시작일자
    $half_todate    = date('Y-m-15', $timestr); // 매월 15
}else{
    $half = '2/2';
    $half_frdate    = date('Y-m-16', $timestr); // 매월 15
    $half_todate    = date('Y-m-'.$lastday, $timestr); // 매월 말일
} */

$d_1 = mktime(0,0,0, date("m"), 1, date("Y")); // 이번달 1일

// 지난달 1일~말일
$prev_month = strtotime("-1 month", $d_1); // 지난달
$prev_m = date('m', $prev_month);

$day = date('d', $timestr);
$lastday = date('t', $prev_month);

$month_frdate    = date('Y-m-01', $prev_month); // 매월 1 시작일자
$month_todate    = date('Y-m-'.$lastday, $prev_month); // 매월 말일



// 직급 수당
$bonus_row = bonus_pick($code);
$bonus_limit = $bonus_row['limited']/100;

// 직급 사용 단계
$bonus_rate_array_cnt = 7;

// 수당 배열인경우
/* $bonus_rate_array_cnt = mb_substr_count($bonus_row['rate'],',');
if($bonus_rate_array_cnt > 0){
    
}else{
    $bonus_rate = $bonus_row['rate']*0.01;  
} */

$bonus_rate = explode(',',$bonus_row['rate']);

$bonus_condition = $bonus_row['source'];
$bonus_condition_tx = bonus_condition_tx($bonus_condition);
$bonus_layer = $bonus_row['layer'];
$bonus_layer_tx = bonus_layer_tx($bonus_layer);
$bonus_limited = $bonus_row['limited'];

$company_sales = 4;

/* 
//보름간 매출 합계 
$total_order_query = "SELECT SUM(pv) AS hap FROM g5_shop_order WHERE od_date BETWEEN '{$half_frdate}' AND '{$half_todate}' ";
$total_order_reult = sql_fetch($total_order_query);
$total_order = $total_order_reult['hap']; 
*/

//지난달 매출 합계 
$total_order_query = "SELECT SUM(od_cash) AS hap FROM g5_shop_order WHERE od_date BETWEEN '{$month_frdate}' AND '{$month_todate}' ";
$total_order_reult = sql_fetch($total_order_query);
$total_order = $total_order_reult['hap'];

$grade_order = ($total_order * ($company_sales * 0.01));
// $_order = ($total_order * 0.01);


// 디버그 로그 
if($debug){
	echo "매출 합계 - <code>";
    print_r($total_order_query);
	echo "</code><br>";
}


//회원 리스트를 읽어 온다.
$sql_common = " FROM g5_member ";
// $sql_search=" WHERE o.mb_id=m.mb_id AND DATE_FORMAT(o.od_time,'%Y-%m-%d')='".$bonus_day."'";
$sql_search=" WHERE grade > 0 ".$pre_condition.$admin_condition;
$sql_mgroup=" GROUP BY grade ORDER BY grade asc ";

$pre_sql = "select grade, count(*) as cnt
                {$sql_common}
                {$sql_search}
                {$sql_mgroup}";

$pre_result = sql_query($pre_sql);


function rate_txt($val){
    $list = explode(',',$val);
    $i =0;

    while($i < count($list)){
        echo $list[$i]."% ";
        $i++;
    }
}

// 디버그 로그 
if($debug){
	echo "대상 회원 - <code>";
    print_r($pre_sql);
	echo "</code><br>";
}

ob_start();

// 설정로그 
echo "<strong>직급(등급) 지급비율 : ";
print_R(rate_txt($bonus_row['rate']));
echo "   </strong> |    지급조건 :".$pre_condition.' | '.$bonus_condition_tx." | ".$bonus_layer_tx."<br>";
echo "<br><strong> 현재일 : ".$bonus_day." |  ".$half."(매출산정기준) : <span class='red'>".$month_frdate."~".$month_todate."</span> | ".$half." PV 합계 : <span class='blue big'>".Number_format($total_order).' '.$curencys[1]."</span>  </strong><br>";
echo "<br> 직급수당 대상금액 : <span class='blue big'>".$company_sales."% = ".Number_format($grade_order).' '.$curencys[1]."</span>";
echo "<br><br>기준대상자(직급 0 이상) : ";

while( $cnt_row = sql_fetch_array($pre_result) ){
    echo "<br><strong>".$cnt_row['grade']." STAR</strong> : <span class='red'>".$cnt_row['cnt'].'</span> 명';    
}

echo "</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>

<html><body>
<header>정산시작</header>    
<div>

<?
if($grade_order > 0){
    excute();
}else{
    echo "<span class='red'>해당 기간 기준매출 없음</span>";
}

function  excute(){

    global $g5,$admin_condition,$pre_condition;
    global $bonus_day, $bonus_condition, $bonus_rate_array_cnt, $code, $bonus_rate,$bonus_limit,$total_order,$Khan_order,$grade_order,$cnt_arr,$cnt_arr2;
    global $debug,$prev_m;

    for ($i=$bonus_rate_array_cnt; $i>0; $i--) {   
        $cnt_sql = "SELECT count(*) as cnt From {$g5['member_table']} WHERE grade = {$i} ".$admin_condition.$pre_condition." ORDER BY mb_no" ;
        $cnt_result = sql_fetch($cnt_sql);
        

        $sql = "SELECT * FROM {$g5['member_table']} WHERE grade = {$i} ".$admin_condition.$pre_condition." ORDER BY mb_no ";
        $result = sql_query($sql);

        // 직급표기예외
        $member_count  = $cnt_result['cnt'];
        $grade_name = $i." STAR";
       

        // 수당예외
        /* if($i == 1 ){
            $star_rate = 500000;
            $star_rate_tx = "500,000 원 고정지급";
        }else{
            $star_rate = $bonus_rate[$i -1]*0.01;
            $star_rate_tx = $bonus_rate[$i -1]."%";
        } */

        $star_rate = $bonus_rate[$i -1]*0.01;
        $star_rate_tx = $bonus_rate[$i -1]."%";


        echo "<br><br><span class='title block'>".$grade_name." (".$member_count.") - ".$star_rate_tx."</span><br>";

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

            // 관리자 제외
            if($mb_id == 'admin' && $mb_level > 9 ){ break;}
            
            if( $member_count != 0 ){ 
                
                // 수당예외
                /* if($i == 1 ){

                    $benefit = $star_rate  ;
                    $benefit = round($benefit);
                    $benefit_tx = ' 500,000  ';
                     
                    // 중복지급 제외
                    $have_sql = "SELECT count(mb_id) as cnt FROM soodang_pay WHERE allowance_name ='grade' AND benefit = 500000 AND mb_id = '{$mb_id}' ";
                    $have_cnt = sql_fetch($have_sql)['cnt'];
                    
                }else if($i == $bonus_rate_array_cnt){

                    $benefit =  ( ($Khan_order*$star_rate) * (1/$member_count) );
                    $benefit = round($benefit);
                    $benefit_tx = ' '.$Khan_order.' * '.$star_rate.' * 1/'.$member_count.'='.$benefit; 
                }else{

                    $benefit = ( ($grade_order*$star_rate) * (1/$member_count) );// 매출자 * 수당비율 * 1/n
                    $benefit = round($benefit);
                    $benefit_tx = ' '.$grade_order.' * '.$star_rate.' * 1/'.$member_count.'='.$benefit; 
                } */

                $benefit = ( ($grade_order*$star_rate) * (1/$member_count) );// 매출자 * 수당비율 * 1/n
                $benefit = shift_auto($benefit,'$');
                $benefit_tx = ' '.$grade_order.' * '.$star_rate.' * 1/'.$member_count.'='.$benefit; 
                $benefit_limit = $benefit;

                
                
                /* list($mb_balance,$balance_limit,$benefit_limit) = bonus_limit_check($mb_id,$benefit);

                echo "<code>";
                echo "현재수당 : ".Number_format($mb_balance)."  | 수당한계 :". Number_format($balance_limit).' | ';
                echo "발생할수당: ".Number_format($benefit)." | 지급할수당 :".Number_format($benefit_limit);
                echo "</code><br>"; */
                
                echo $benefit_tx;
                
                $rec= $code.' Bonus from '.$grade_name;
                $rec_adm= $prev_m."M | ".$grade_name.' : '.$benefit_tx;

                /* if($benefit > $benefit_limit && $balance_limit != 0 ){

                    $rec_adm .= "<span class=red> |  Bonus overflow :: ".Number_format($benefit_limit - $benefit)."</span>";
                    echo "<span class=blue> ▶▶ 수당 지급 : ".Number_format($benefit)."</span>";
                    echo "<span class=red> ▶▶▶ 수당 초과 (한계까지만 지급) : ".Number_format($benefit_limit)." </span><br>";
                }else if($benefit != 0 && $balance_limit == 0 && $benefit_limit == 0){
            
                    $rec_adm .= "<span class=red> | Sales zero :: ".Number_format($benefit_limit - $benefit)."</span>";
                    echo "<span class=blue> ▶▶ 수당 지급 : ".Number_format($benefit)."</span>";
                    echo "<span class=red> ▶▶▶ 수당 초과 (기준매출없음) : ".Number_format($benefit_limit)." </span><br>";
            
                }else if($benefit == 0){
                    echo "<span class=blue> ▶▶ 수당 미발생 </span>";
            
                }else{
                    echo "<span class=blue> ▶▶ 수당 지급 : ".Number_format($benefit)."</span><br>";
                } */

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
    } //for
} //function
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