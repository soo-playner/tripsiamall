<?php
$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

// $debug=1;


$log_record_arr = [];
$log_cnt = 0;

/* 테이블 없으면 생성 / 있으면 비우기 */
if(!sql_query(" DESC `soodang_pre_record` ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `soodang_pre_record` (
        `no` int(11) NOT NULL AUTO_INCREMENT,
        `mb_id` varchar(255) NOT NULL,
        `code` varchar(255) NOT NULL,
        `benefit_limit` double NOT NULL,
        `rec` varchar(255) NOT NULL,
        `rec_adm` TEXT NOT NULL,
        `bonus_day` DATE  NOT NULL DEFAULT '0000-00-00',
        KEY `no` (`no`)
    )", true);
}else{
    $clear_log_data = sql_query("TRUNCATE TABLE `soodang_pre_record`");
}



//회원 리스트를 읽어 온다.
$sql_common = " FROM g5_member ";
$sql_search=" WHERE mb_week_dividend != 0";
$sql_mgroup=' GROUP BY mb_week_dividend';

$pre_sql = "select mb_id
            {$sql_common}
            {$sql_search}";


if($debug){
    echo "<code>";
    print_r($pre_sql);
    echo "</code><br>";
}

$pre_result = sql_query($pre_sql);
$result_cnt = sql_num_rows($pre_result);

ob_start();

// 설정로그 
echo "<span class ='title' style='font-size:20px;'>".$bonus_row['name']." 수당 정산</span><br>";
echo "<strong>".strtoupper($code)." 수당 지급비율 : ". $bonus_row['rate']."%   </strong> |    지급조건 -".$pre_condition.' | '.$bonus_condition_tx." | ".$bonus_layer_tx." | ".$bonus_limit_tx."<br>";
echo "<strong>".$bonus_day."</strong><br>";
echo "<br><span class='red'> 기준대상자(매출발생자) : ";
echo $result_cnt;
echo "</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";

?>

<html><body>
<header>정산시작</header>    
<div>
<?
// 디버그 로그 
if($debug){
	echo "<code>";
    print_r($sql);
	echo "</code><br>";
}

excute(1);
excute(2);

function  excute($val){

    global $g5, $bonus_day, $bonus_condition, $code, $bonus_rates, $bonus_rate,$pre_condition_in,$bonus_limit ;
    global $debug,$log_sql;

    $log_sql ='INSERT INTO `soodang_pre_record` (mb_id,code,benefit_limit,rec,rec_adm,bonus_day) VALUE ';

    $sql = "SELECT mb_no,mb_id,mb_rate,mb_save_point,mb_balance,rank_note,mb_deposit_point,mb_deposit_calc,mb_week_dividend
            FROM g5_member WHERE mb_week_dividend = {$val}
            ORDER BY mb_no asc ";
    $result = sql_query($sql);

    
    if($val == 1){
        $it_name = 'taked Jewelry';
        echo "<br><br><span class='title block'> 보석수령 회원 : ".$bonus_rates[$val-1]."%</span><br>";
    }else{
        $it_name = 'Selected';
        echo "<br><br><span class='title block coral'> 미수령 회원 : ".$bonus_rates[$val-1]."%</span><br>";
    }
    

    for ($i=0; $row=sql_fetch_array($result); $i++) {   

        $mb_id = $row['mb_id'];
        $mb_balance = $row['mb_balance'];
        
        $pv = $row['mb_rate'];
        
        // 최근구매한 마지막 상품의PV 기준으로 변경 arcthna by 21.07.19
        $rank_item = $row['rank_note'];
        $lateset_supply = it_item_return(strtoupper($rank_item),'supply_point','name');
        $lateset_item_pv = $lateset_supply * 10000;
        
        echo "<br><br><span class='title' style='font-size:30px;'>".$mb_id."</span><br>";
        
        $bonus_rate = $bonus_rates[$val-1]*0.01;
        $benefit=($lateset_item_pv*$bonus_rate); // 매출자 * 수당비율
        
        echo "현재 보유 PV :: <strong>".Number_format($pv)."</strong>";
        echo "<br>최근구매상품 PV :: <strong>".Number_format($lateset_item_pv)."</strong>";

        if($pv > 0){
            list($mb_balance,$balance_limit,$benefit_limit) = bonus_limit_check($mb_id,$benefit);
            
            // 디버그 로그
        
                echo "<code>";
                echo "현재수당 : ".Number_format($mb_balance)."  | 수당한계 :". Number_format($balance_limit).' | ';
                echo "발생할수당: ".Number_format($benefit)." | 지급할수당 :".Number_format($benefit_limit);
                echo "</code><br>";
            

            $rec=$code.' Bonus By  '.$it_name;
            $rec_adm= $it_name.' | '.$pv.'*'.$bonus_rate.'='.$benefit;


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

                /* 산출 및 실행으로 변경*/
                $record_result = log_record($mb_id,$code,$benefit_limit,$rec,$rec_adm,$bonus_day);

                /* 바로 정산실행시
                /* $record_result = soodang_record($mb_id, $code, $benefit_limit,$rec,$rec_adm,$bonus_day);

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
                } */
            }
        }else{
            echo "<br><span class='red'>▶ 인정회원 </span>";
        }

    }
    $bonus_rate = 0; 

    $log_insert_result = substr($log_sql,0,-1);
        if($debug){
            echo "<code>";
            print_R($log_insert_result);
            echo "</code>";
        }else{
            echo "<br><div class= 'hidden' >";
            print_R($log_insert_result);
            echo "</div>";
        }
        
    
    $log_result = sql_query($log_insert_result);

    if($log_result){
        echo "<br><br><div class='blue'>※※ 수당 산출 정상 처리 완료 ※※</div>";
    }else{
        echo "<br><br><div class='red'>※※ 수당 산출 내용 이상 발생 ※※</div>";
    }
    $log_sql = '';
}


function log_record($mb_id,$code,$benefit_limit,$rec,$rec_adm,$bonus_day){
    global $g5,$log_record_arr,$log_sql;
    $log_sql .= "('{$mb_id}','{$code}',{$benefit_limit},'{$rec}','{$rec_adm}','{$bonus_day}'),";
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