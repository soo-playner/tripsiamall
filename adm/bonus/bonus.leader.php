<?php
$sub_menu = "600900";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

if($_GET['debug']){
	$debug = 1;
}

// 리더 수당

$bonus_row = bonus_pick($code);
$bonus_limit = $bonus_row['limited']/100;
$bonus_rate = $bonus_row['rate'];

// 수당 배열인경우
if(strpos($bonus_rate,',') > 0){
    $bonus_rate_array = explode(',',$bonus_rate);
}

$bonus_condition = $bonus_row['source'];
$bonus_condition_tx = bonus_condition_tx($bonus_condition);

$bonus_layer = $bonus_row['layer'];
$bonus_layer_tx = bonus_layer_tx($bonus_layer);
$min30= date("Y-m-d", strtotime( "-30 day", strtotime($bonus_day)) );


//회원 리스트를 읽어 온다.
if($_GET['test_id']){
    $pre_sql = "select * from g5_member where mb_id = '".$test_id."'";
}else{
    $pre_sql = "select * from {$g5['bonus']} where allowance_name = 'binary' and day = '{$bonus_day}' ". $pre_condition ." ". $admin_condition. " order by mb_no asc";
}

$pre_result = sql_query($pre_sql);
$result_cnt = sql_num_rows($pre_result);


ob_start();


// 설정로그 
echo "<strong>".strtoupper($code)." 지급비율 : ". $bonus_row['rate']."%   </strong> |    지급조건 :".$pre_condition.' | '.$bonus_condition_tx." | ".$bonus_layer_tx."<br>";
echo "<strong>".$bonus_day."</strong><br>";
echo "<br><span class='red'> 기준대상자(매출발생자) : ".$result_cnt." </span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";

if($debug){
	echo "<code>";
    print_r($pre_sql);
	echo "</code><br>";
}
?>

<html><body>
<header>정산시작</header>    
<div>

<?

if($result_cnt < 1){
    echo "<span class='error'>바이너리수당지급 내역이 없거나 대상자가 없습니다.</span>";
}else{

    $history_cnt = 0;

    for ($i=0; $row=sql_fetch_array($pre_result); $i++) {   
        $comp=$row['mb_id'];
        $bonus_origin = $row['benefit'];

        // 4단계까지
        while( $history_cnt <= $bonus_layer ){   
            
            $sql = " SELECT mb_no, mb_id, mb_name,grade,mb_level, mb_balance, mb_recommend, mb_brecommend, mb_deposit_point FROM g5_member WHERE mb_id= '{$comp}' ";
            $recommend = sql_fetch($sql);

            $mb_no=$recommend['mb_no'];
            $mb_id=$recommend['mb_id'];
            $mb_name=$recommend['mb_name'];
            $mb_level=$recommend['mb_level'];
            $mb_deposit=$recommend['mb_deposit_point'];
            $mb_balance=$recommend['mb_balance'];
            $grade=$recommend['grade'];

            // 추천, 후원 조건
            if($bonus_condition < 2){
                $recom=$recommend['mb_recommend'];
            }else{
                $recom=$recommend['mb_brecommend'];
            }

            // 수당비율 배열, 단일값설정
            if($bonus_rate_array){
                $bonus_rate = $bonus_rate_array[$history_cnt-1] * 0.01;
            }else{
                $bonus_rate = $bonus_rate * 0.01;
            }

            // 관리자급 수당정지
            if($mb_level > 9 ){ break;} 


            // 수당 로그
            //echo $row['mb_id']." | ".$history_cnt." 단계 :: ".$today_sales.'*'.$bonus_rate;
            //echo "<span class=blue> ▶▶▶ 수당 지급 : ".$benefit."</span><br>";

            if( $history_cnt==0 ){ // 본인
                $firstname=$mb_name;
                $firstid=$mb_id;

                // 수당 로그
                echo "<span class=title>".$row['mb_id']." | 지급받은 수당 : <span class='red'>".$bonus_origin.'</span> '.ASSETS_CURENCY."</span>";
            }else{
                
                
                $benefit=($bonus_origin*$bonus_rate);// 매출자 * 수당비율

                $balance_limit = $bonus_limit * $mb_deposit; // 수당한계선
                $benefit_limit = $mb_balance + $benefit; // 수당합계

                $rec= $code.' Bonus from '.$firstid.'('. $firstname.') :: step : '.$history_cnt.')';
                $rec_adm= ''.$firstid.' - '.$history_cnt.'대 :'.$bonus_origin.'*'.$bonus_rate.'='.$benefit;

                    // 디버그 로그
                    if($debug){
                        echo "<code>";
                        echo "현재수당 : ".$mb_balance."  | 수당한계 :". $balance_limit;
                        echo "<br>";
                        //echo $rec."<br>";
                        //echo $rec_adm."<br>";
                        echo "</code><br>";
                    }


                // 수당초과여부 확인
                if($benefit_limit > $balance_limit){
                    $benefit_limit = $balance_limit;
                    $rec_adm .= " (benefit overflow)";
                    echo " <span class=red> ▶▶ 수당 초과 (한계까지만 지급)".$benefit_limit." </span><br>";
                    
                }else{
                    // 수당 로그
                    echo '<br> ▷ '.$mb_id." | ".$history_cnt." 단계  | 수당 비율 : ".$bonus_origin.' * '. $bonus_rate. ' = '. $benefit;
                    echo "<span class=blue> ▶▶▶ 수당 지급 : ".$benefit."</span><br>";
                }





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
                    echo "<code>";
                    print_R($bonus_sql);
                    echo "</code>";
                }else{
                    sql_query($bonus_sql);
                }


                $balance_up = "update g5_member set mb_balance = '".$benefit_limit."'  where mb_id = '".$mb_id."'";

                // 디버그 로그
                if($debug){
                    echo "<code>";
                    print_R($balance_up);
                    echo "</code>";
                }else{
                    sql_query($balance_up);
                }

            }


            $comp=$recom;
            $history_cnt++;
        }
        
    }
}
?>

<?include_once('./bonus_footer.php');?>

<?
if($debug){}else{
    $html = ob_get_contents();
    $logfile = G5_PATH.'/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
    file_put_contents($logfile, ob_get_contents());
}
?>