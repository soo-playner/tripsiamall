<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');


// $debug = 1;


$day = date('d', $timestr);
$lastday = date('t', $timestr);

if($day < 20){
    $half = '1/2';
    $half_frdate    = date('Y-m-1', $timestr); // 매월 1 시작일자
    $half_todate    = date('Y-m-15', $timestr); // 매월 15
}else{
    $half = '2/2';
    $half_frdate    = date('Y-m-15', $timestr); // 매월 15
    $half_todate    = date('Y-m-'.$lastday, $timestr); // 매월 말일
}


// 레벨 수당
$bonus_row = bonus_pick($code);
$bonus_limit = $bonus_row['limited']/100;

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


//보름간 매출 합계 
$total_order_query = "SELECT SUM(od_cart_price) AS hap FROM g5_shop_order WHERE od_date BETWEEN '{$half_frdate}' AND '{$half_todate}' "; 
$total_order_reult = sql_fetch($total_order_query);
$total_order = $total_order_reult['hap'];

// 디버그 로그 
if($debug){
	echo "지난주합계 - <code>";
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


// 디버그 로그 
if($debug){
	echo "대상회원 - <code>";
    print_r($pre_sql);
	echo "</code><br>";
}

ob_start();

// 설정로그 
echo "<strong>".strtoupper($code)." 지급비율 : ". $bonus_row['rate']."%   </strong> |    지급조건 :".$pre_condition.' | '.$bonus_condition_tx." | ".$bonus_layer_tx."<br>";
echo "<br><strong> 현재일 : ".$bonus_day." |  ".$half."(지급산정기준) : <span class='red'>".$half_frdate."~".$half_todate."</span> | 지난주 매출 합계 : <span class='blue big'>".$total_order."</span></strong><br>";
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
excute();
function  excute(){

    global $g5,$admin_condition,$pre_condition;
    global $bonus_day, $bonus_condition, $bonus_rate_array_cnt, $code, $bonus_rate,$bonus_limit,$total_order,$cnt_arr,$cnt_arr2;
    global $debug;

    for ($i=$bonus_rate_array_cnt+1; $i>0; $i--) {   
        $cnt_sql = "SELECT count(*) as cnt From {$g5['member_table']} WHERE grade >= {$i} ".$admin_condition.$pre_condition." ORDER BY mb_no" ;
        $cnt_result = sql_fetch($cnt_sql);
        

        $sql = "SELECT * FROM {$g5['member_table']} WHERE grade >= {$i} ".$admin_condition.$pre_condition." ORDER BY mb_no ";
        $result = sql_query($sql);

        $member_count  = $cnt_result['cnt'];

        echo "<br><br><span class='title block'>".$i." STAR (".$member_count.")</span><br>";
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
            $star_rate = $bonus_rate[$i-1]*0.01;

            echo "<br><span class='title indent' >".$row['mb_id']."</span><br>";

            // 관리자 제외
            if($mb_id == 'admin' && $mb_level > 9 ){ break;}
            
            if( $member_count != 0 ){ 
                
                $benefit=( ($total_order*$star_rate) * (1/$member_count) );// 매출자 * 수당비율 * 1/n
                $balance_limit = $bonus_limit * $mb_deposit; // 수당한계선
                $benefit_limit = $mb_balance + $benefit; // 수당합계
                
                $rec= $code.' Bonus from '.$i.' STAR';
                $rec_adm= $i.'STAR :'.$total_order.'*'.$star_rate.'*1/'.$member_count.'='.$benefit;


                    // 디버그 로그
                    if($debug){
                        echo "<code>";
                        echo "현재수당 : ".$mb_balance."  | 수당한계 :". $balance_limit;
                        echo "</code><br>";
                    }
                
                    
                if($benefit_limit > $balance_limit){
                    $benefit_limit = $balance_limit;
                    $rec_adm = "benefit overflow";
                    echo " <span class=red> ▶▶ 수당 초과 (한계까지만 지급)".$benefit_limit." </span><br>";
                    
                }else{

                    // 수당 로그
                    echo "<span class=indent>".$i." STAR :: ".$total_order.'*'.$star_rate.'*1/'.$member_count."</span>";
                    echo "<span class=blue> ▶▶▶ 수당 지급 : ".$benefit."</span><br>";
                }

                
                if($benefit > 0){
                    //**** 수당이 있다면 함께 DB에 저장 한다.
                    $bonus_sql = " insert `{$g5['bonus']}` set day='".$bonus_day."'";
                    $bonus_sql .= " ,mb_no			= ".$mb_no;
                    $bonus_sql .= " ,mb_id			= '".$mb_id."'";
                    $bonus_sql .= " ,mb_name		= '".$mb_name."'";
                    $bonus_sql .= " ,mb_level      = ".$mb_level;
                    $bonus_sql .= " ,grade      = ".$grade;
                    $bonus_sql .= " ,allowance_name	= '".$code."'";
                    $bonus_sql .= " ,benefit		=  ".$benefit;	
                    $bonus_sql .= " ,rec			= '".$rec."'";
                    $bonus_sql .= " ,rec_adm		= '".$rec_adm."'";
                    $bonus_sql .= " ,origin_balance	= '".$mb_balance."'";
                    $bonus_sql .= " ,origin_deposit	= '".$mb_deposit."'";
                    $bonus_sql .= " ,datetime		= '".date("Y-m-d H:i:s")."'";


                    // 디버그 로그
                    if($debug){
                        echo "<br><code>";
                        print_R($bonus_sql);
                        echo "</code>";
                    }else{
                        sql_query($bonus_sql);
                    }


                    $balance_up = "update g5_member set mb_balance = ".$benefit_limit."  where mb_id = '".$mb_id."'";

                    // 디버그 로그
                    if($debug){
                        echo "<code>";
                        print_R($balance_up);
                        echo "</code>";
                    }else{
                        sql_query($balance_up);
                    }

                } // if benefit
 
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