<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

$debug = 1;

if(!$debug){
    $dupl_check_sql = "select mb_id from rank where rank_day='".$bonus_day."'";
	$get_today = sql_fetch( $dupl_check_sql);

	if($get_today['mb_id']){
		alert($bonus_day." 해당일 승급이 이미 완료 되었습니다.");
		die;
	}
}

// 직급 승급
$grade_cnt = 6;
$levelup_result = bonus_pick($code);

// 직추천 회원수 
$lvlimit_cnt = explode(',',$levelup_result['limited']);

// 본인 매출 * 1000
$lvlimit_sales = explode(',',$levelup_result['rate']);
$lvlimit_sales_val = 1000;

// 하부 매출  * 10000
$lvlimit_recom = explode(',',$levelup_result['layer']);
$lvlimit_recom_val = 10000;

//회원 리스트를 읽어 온다.
$sql_common = " FROM g5_member ";
// $sql_search=" WHERE o.mb_id=m.mb_id AND DATE_FORMAT(o.od_time,'%Y-%m-%d')='".$bonus_day."'";
$sql_search=" WHERE grade < 7 ".$pre_condition.$admin_condition;
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

echo "<strong> 현재일 : ".$bonus_day." |  지난주(week) : <span class='red'>".$week_frdate."~".$week_todate."</span></strong> <br>";

if($debug){
    echo "<br><code>회원직급 승급 조건   |   기준조건 :".$pre_condition."<br>";
    for($i=0; $i< $grade_cnt; $i++){
        echo  " <br>    -  [ 승급기준]  추천인". $lvlimit_cnt[$i]."명 이상 |  본인매출 : ". ($lvlimit_sales[$i]*$lvlimit_sales_val)." 이상  |  하부매출 : ".($lvlimit_recom[$i]*$lvlimit_recom_val)."이상 " ;   
    }echo "</code><br>";
}

echo "<strong>현재 직급 기준 대상자</strong> : ";
while( $cnt_row = sql_fetch_array($pre_result) ){
    echo "<br><strong>".$cnt_row['grade']." STAR : <span class='red'>".$cnt_row['cnt'].'</span> 명</strong>';
}

echo "</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>

<html><body>
<header>승급시작</header>    
<div>

<?
excute();

function recom_sales($mb_id){
    $mem_recom_sql = "SELECT * FROM g5_member where mb_recommend = '{$mb_id}' ";
    $mem_recom_result = sql_query($mem_recom_sql);
    $recom_sales = [];
    
    while($row = sql_fetch_array($mem_recom_result)){
        $recom = $row['mb_id'];
        $sql = "SELECT * FROM recom_bonus_week where mb_id ='{$recom}' ";
        $result = sql_fetch($sql);

        if($result){
            $recom_sale = $result['week'];
            if(!$recom_sale){
                $recom_sale = 0;
            }
            array_push($recom_sales,$recom_sale);
        }else{
            array_push($recom_sales,0);
        }
    }
    return $recom_sales;
}

function  excute(){

    global $g5,$admin_condition,$pre_condition;
    global $bonus_day,$week_frdate,$week_todate, $grade_cnt, $code, $lvlimit_cnt, $lvlimit_sales, $lvlimit_recom, $lvlimit_sales_val, $lvlimit_recom_val;
    global $debug;

    for ($i=$grade_cnt-1; $i>-1; $i--) {   
        $cnt_sql = "SELECT count(*) as cnt From {$g5['member_table']} WHERE grade = {$i} ".$admin_condition.$pre_condition." ORDER BY mb_no" ;
        $cnt_result = sql_fetch($cnt_sql);

        $sql = "SELECT * FROM {$g5['member_table']} WHERE grade = {$i} ".$admin_condition.$pre_condition." ORDER BY mb_no ";
        $result = sql_query($sql);

        $member_count  = $cnt_result['cnt'];
        
        echo "<br><br><span class='title block'>".$i." STAR (".$member_count.")</span><br>";
        echo  " -  [ 승급기준 ] 직추천 : ". $lvlimit_cnt[$i]."명 이상 |  본인매출 : ". ($lvlimit_sales[$i]*1000)." 이상  |  하부매출 : ".($lvlimit_recom[$i]*10000)."이상 " ;   
        
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
            $rank_cnt =0;
            echo "<br><span class='title' >[ ".$row['mb_id']." ] </span><br>";

            // 관리자 제외
            if($mb_level > 9 ){ break;}
            
            if( $member_count != 0 ){ 
                
                // 직추천자수 
                $mem_cnt_sql="SELECT count(*) as cnt FROM g5_member where mb_recommend = '{$mb_id}' ";
                $mem_cnt_result = sql_fetch($mem_cnt_sql);
                $mem_cnt = $mem_cnt_result['cnt'];
                echo "<br>직추천인수 : <span class='blue'>".$mem_cnt."</span>";
                if($mem_cnt >$lvlimit_cnt[$i]){$rank_cnt += 1; echo "<span class='red'> == OK </span>";}

                // 내 매출 
                $mem_sales_sql ="SELECT SUM(od_cart_price) as hap FROM g5_shop_order WHERE od_date BETWEEN '{$week_frdate}' AND '{$week_todate}' AND mb_id ='{$mb_id}'";
                $mem_sales_result = sql_fetch($mem_sales_sql);
                $mem_sales = $mem_sales_result['hap'];
                echo "<br>지난주 매출 : <span class='blue'>".$mem_sales."</span>";
                if($mem_sales >= $lvlimit_sales[$i]*$lvlimit_sales_val){$rank_cnt += 1; echo "<span class='red'> == OK </span>";}

                // 하부 매출
                $recom_week_sales = recom_sales($mb_id);
                echo "<br>지난주 하부 매출 - ";
                //print_R($recom_week_sales);

                if($recom_week_sales){
                    $sum_sale = array_sum($recom_week_sales);
                    $max_sale = max($recom_week_sales);
                    
                    echo  "하부매출(". $sum_sale .") - 대실적(". $max_sale .") = 계산실적( <span class='blue'>".($sum_sale - $max_sale)."</span> )";
                    // if($mem_sales >= $lvlimit_recom[$i]*$lvlimit_recom_val){$rank_cnt += 1;}
                    if($mem_sales >= $lvlimit_recom[$i]*1){$rank_cnt += 1; echo "<span class='red'> == OK </span>";}
                    if($debug)  echo "<code>"; print_R($recom_week_sales);echo "</code>";
                }

                // 디버그 로그
                if($debug){
                    echo "<code>";
                    echo $rank_cnt;
                    echo "</code>";
                }

                // 승급로그
                if($rank_cnt == 3){
                    $upgrade = ($grade+1);
                    echo "<br><span class='red'> ▶▶ 직급 승급 => ".$upgrade." STAR </span><br> ";
                    $rec= $code.' Update to '.($grade+1).' STAR IN '.$bonus_day;
                

                    //**** 수당이 있다면 함께 DB에 저장 한다.
                    $bonus_sql = " insert rank set rank_day='".$bonus_day."'";
                    $bonus_sql .= " ,mb_id			= '".$mb_id."'";
                    $bonus_sql .= " ,old_level		= '".$grade."'";
                    $bonus_sql .= " ,rank      = ".$upgrade;
                    $bonus_sql .= " ,rank_note	= '".$rec."'";"'";


                    // 디버그 로그
                    if($debug){
                        echo "<br><code>";
                        print_R($bonus_sql);
                        echo "</code>";
                    }else{
                        sql_query($bonus_sql);
                    }


                    $balance_up = "update g5_member set grade = {$upgrade} where mb_id = '".$mb_id."'";

                    // 디버그 로그
                    if($debug){
                        echo "<code>";
                        print_R($balance_up);
                        echo "</code>";
                    }else{
                        sql_query($balance_up);
                    }
                     
                } // if $rank_cnt
            
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