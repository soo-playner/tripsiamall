<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

// $debug =1;

// 지난주 날짜 구하기 
/* $today=$bonus_day;
$timestr        = strtotime($today);
$week           = date('w', strtotime($todate));
$weekfr         = $timestr - ($week * 86400);
$weekla         = $weekfr + (6 * 86400);
$week_frdate    = date('Y-m-d', $weekfr - (86400 * 5)); // 지난주 시작일자
$week_todate    = date('Y-m-d', $weekla - (86400 * 5)); // 지난주 종료일자 */

$day = date('d', $timestr);
$lastday = date('t', $timestr);


if($day > 13 && $day <= 20){
    $half = '1/2';
    $half_frdate    = date('Y-m-1', $timestr); // 매월 1 시작일자
    $half_todate    = date('Y-m-15', $timestr); // 매월 15
}else{
    $half = '2/2';
    $half_frdate    = date('Y-m-16', $timestr); // 매월 15
    $half_todate    = date('Y-m-'.$lastday, $timestr); // 매월 말일
}



// $bonus_rate = explode(',',$bonus_row['rate']);

$bonus_condition = $bonus_row['source'];
$bonus_condition_tx = bonus_condition_tx($bonus_condition);
$bonus_layer = $bonus_row['layer'];
$bonus_layer_tx = bonus_layer_tx($bonus_layer);


//보름간 매출 합계 
/* $total_order_query = "SELECT SUM(pv) AS hap FROM g5_shop_order WHERE od_date BETWEEN '{$half_frdate}' AND '{$half_todate}' ";
$total_order_reult = sql_fetch($total_order_query);
$total_order = $total_order_reult['hap'];
$grade_order = ($total_order * 0.02); */


// 디버그 로그 
/* if($debug){
	echo "매출 합계 - <code>";
    print_r($total_order_query);
	echo "</code><br>";
} */


//회원 리스트를 읽어 온다.
$sql_common = " FROM g5_member ";
$sql_search=" WHERE center_use > 0 ".$pre_condition.$admin_condition;
$sql_mgroup=" ORDER BY mb_no asc ";

$pre_sql = "select *
                {$sql_common}
                {$sql_search}
                {$sql_mgroup}";

$pre_result = sql_query($pre_sql);
$result_cnt = sql_num_rows($pre_result);

// 디버그 로그 
if($debug){
	echo "대상회원 - <code>";
    print_r($pre_sql);
	echo "</code><br>";
}


ob_start();

// 설정로그 
echo "<strong>센터 지급비율 : ". $bonus_row['rate']."%   </strong> |    지급조건 :".$pre_condition.' | '.$bonus_condition_tx." | ".$bonus_layer_tx."<br>";
echo "<br><strong> 현재일 : ".$bonus_day." |  ".$half."(지급산정기준) : <span class='red'>".$half_frdate."~".$half_todate."</span><br>";

echo "<br><br>기준대상자(센터회원) : <span class='red'>".$result_cnt."</span>";
echo "</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>

<html><body>
<header>정산시작</header>    
<div>
<?


/*
$sql = "SELECT od_date AS od_time, m.mb_no, m.mb_id, m.mb_name, m.mb_level, m.mb_deposit_point, m.mb_balance, m.grade,SUM(pv) AS hap 
            FROM g5_shop_order AS o, g5_member AS m
            WHERE o.mb_id = m.mb_id AND m.mb_center != '0' AND od_date BETWEEN '{$week_frdate}' AND '{$week_todate}'
            GROUP BY m.mb_id ORDER BY m.mb_no asc";
$result = sql_query($sql);
*/


//회원 리스트를 읽어 온다.
$sql_search=" WHERE center_use = 1 ".$pre_condition.$admin_condition;
$sql_mgroup=" ORDER BY mb_no asc ";
$sql = "select * FROM g5_member
                {$sql_search}
                {$sql_mgroup}";

$result = sql_query($sql);

// 디버그 로그 
if($debug){
	echo "<code>";
    print_r($sql);
	echo "</code><br>";
}


excute();

function  excute(){

    global $result;
    global $g5, $bonus_day, $bonus_condition, $code, $bonus_rate,$pre_condition_in,$bonus_limit,$week_frdate,$week_todate,$half_frdate,$half_todate;
    global $debug,$log_sql;


    for ($i=0; $row=sql_fetch_array($result); $i++) {   
   
        // $center_bonus = $bonus_rate;
        $mb_no=$row['mb_no'];
        $mb_id=$row['mb_id'];
        $mb_name=$row['mb_name'];
        $mb_level=$row['mb_level'];
        $mb_deposit=$row['mb_deposit_point'];
        $mb_balance=$row['mb_balance'];
        $grade=$row['grade'];


        $recom= 'mb_center'; //센터멤버
        $sql = " SELECT mb_no, mb_id, mb_name, grade, mb_level, mb_balance, mb_deposit_point FROM g5_member WHERE {$recom} = '{$mb_id}' ";
        $sql_result = sql_query($sql);
        $sql_result_cnt = sql_num_rows($sql_result);

        
        // $center_bonus = $total_order *($bonus_rate);
        echo "<br><br><span class='title block' style='font-size:30px;'>".$mb_id."</span><br>";
        echo "센터하부회원 : <span class='red'> ".$sql_result_cnt."</span> 명 <br>";
        

        while( $center = sql_fetch_array($sql_result) ){   
            
            $recom_id = $center['mb_id'];
            $half_bonus_sql = "SELECT SUM(pv) AS hap FROM g5_shop_order WHERE od_date BETWEEN '{$half_frdate}' AND '{$half_todate}' AND mb_id = '{$recom_id}' ";
            // if($debug){echo "<code>".$half_bonus_sql."</code>";}
            $half_bonus_result = sql_fetch($half_bonus_sql);

            if($half_bonus_result['hap'] > 0){
                $recom_half_bonus = $half_bonus_result['hap'];
            }else{
                $recom_half_bonus = 0;
            }

            $recom_half_total += $recom_half_bonus;

            echo "<br>".$recom_id;
            echo " | 기간내 매출 : <span class='blue'>".Number_format($recom_half_bonus)."</span>";
        } 

        $benefit = $recom_half_total * $bonus_rate;
        
        echo "<br><br><span class='title box'> ".$mb_id."  - 지난주 하부 총매출 : <span class='blue'>".Number_format($recom_half_total)."</span>";
        echo " | 센터수당 : <span class='blue'>".Number_format($benefit)." (".($bonus_rate*100)."%)</span></span><br>";
        
        list($mb_balance,$balance_limit,$benefit_limit) = bonus_limit_check($recom_id,$benefit);
        $benefit_limit = $benefit;

        // 디버그 로그
        echo "<code>";
        echo "현재수당 : ".Number_format($mb_balance)."  | 수당한계 :". Number_format($balance_limit).' | ';
        echo "발생할수당: ".Number_format($benefit)." | 지급할수당 :".Number_format($benefit_limit);
        echo "</code><br>";
        
        $rec=$code.' Bonus By Center:'.$mb_id;
        $rec_adm= 'CENTER | '.$recom_half_total.'*'.$bonus_rate.'='.$benefit;


        // 수당제한
        echo $mb_id." | ".Number_format($recom_half_total).'*'.$bonus_rate;

        if($benefit > $benefit_limit && $balance_limit != 0 ){

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
        }


        if($benefit > 0 && $benefit_limit > 0){
            
            $record_result = soodang_record($mb_id, $code, $benefit_limit,$rec,$rec_adm,$bonus_day);
            if($record_result){
                $balance_up = "update g5_member set mb_balance = mb_balance + {$benefit_limit}  where mb_id = '".$mb_id."'";

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
        $recom_half_total = 0;
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