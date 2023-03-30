<?php
$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

// $debug=1;

//회원 리스트를 읽어 온다.
$sql_common = " FROM {$g5['bonus']} ";
$sql_search=" WHERE allowance_name = 'direct' AND day = '{$bonus_day}' ";
$sql_mgroup=' GROUP BY mb_id ORDER BY mb_no asc';

$pre_sql = "select count(*) 
                {$sql_common}
                {$sql_search}
                {$sql_mgroup}";
$pre_result = sql_query($pre_sql);
$result_cnt = sql_num_rows($pre_result);

ob_start();

// 설정로그 
echo "<strong>".strtoupper($code)." 지급비율 : ". $bonus_row['rate']."%   </strong> |    지급조건 :".$pre_condition.' | '.$bonus_condition_tx." | ".$bonus_layer_tx."<br>";
echo "<strong>".$bonus_day."</strong><br>";
echo "<br><span class='red'> 기준대상자(매출발생자) : ".$result_cnt."</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";

?>

<html><body>
<header>정산시작</header>    
<div>
<?

$price_cond=", SUM(benefit) AS hap";

$sql = "SELECT mb_id,SUM(benefit) as benefit
            {$sql_common}
            {$sql_search}
            {$sql_mgroup}";
$result = sql_query($sql);

// 디버그 로그 
if($debug){
	echo "<code>";
    print_r($sql);
	echo "</code><br>";
}

$levelup_result = bonus_pick($code);

// 직추천 회원수 
// $lvlimit_cnt = explode(',',$levelup_result['bonus_condition']);

$history_cnt=0;
$rec='';

if($result_cnt > 0){
    excute();
}else{
    echo "<span class='red'>정산대상자가 없습니다.</span>";
}

function  excute(){

    global $result,$history_cnt;
    global $g5, $bonus_day, $bonus_condition, $code, $bonus_rate,$bonus_rates,$pre_condition_in,$bonus_limit,$bonus_layer,$lvlimit_cnt ;
    global $debug,$config;

    for ($i=0; $row=sql_fetch_array($result); $i++) {   


        $today=$row['datetime'];
        $comp=$row['mb_id'];
        
        $firstname='';
        $firstid='';
        
        $today_sales=$row['benefit'];

        echo "<br><br><br><span class='block title' style='font-size:30px;'>".$comp."</span><br>";

        while($history_cnt <= 3){   
            $sql = " SELECT mb_no, mb_id, mb_name,grade,mb_level, mb_balance, mb_recommend, mb_brecommend, mb_deposit_point,rank FROM g5_member WHERE mb_id= '{$comp}' ";
            // if($debug){echo "<code>".$sql."</code>";}
            $recommend = sql_fetch($sql);

            $mb_no=$recommend['mb_no'];
            $mb_id=$recommend['mb_id'];
            $mb_name=$recommend['mb_name'];
            $mb_level=$recommend['mb_level'];
            $mb_deposit=$recommend['mb_deposit_point'];
            $mb_balance=$recommend['mb_balance'];
            $item_rank=$recommend['rank'];
            $grade=$recommend['grade'];

            // 추천, 후원 조건
            if($bonus_condition < 2){
                $recom=$recommend['mb_recommend'];
            }else{
                $recom=$recommend['mb_brecommend'];
            }
    
            if( $history_cnt==0 ){ // 본인
                $firstname=$mb_name;
                $firstid=$mb_id;

            }else{
                $rank_cnt = 0;
                $hist = $history_cnt-1;	
                $bonus_rate = $bonus_rates[$hist];

                $benefit=($today_sales*($bonus_rate * 0.01));// 매출자 * 수당비율

                $benefit_limit = $benefit;
                
                echo "<br><br><span class='box'><strong class='subtitle'>".$mb_id."</strong> | ".$history_cnt." 단계 :: ".Number_format($today_sales).'*'.$bonus_rate.'% = '.Number_format($benefit)."</span>";

                /* list($mb_balance,$balance_limit,$benefit_limit) = bonus_limit_check($mb_id,$benefit);
                // 디버그 로그
                
                echo "<code>";
                echo "현재수당 : ".Number_format($mb_balance)."  | 수당한계 :". Number_format($balance_limit).' | ';
                echo "발생할수당: ".Number_format($benefit)." | 지급할수당 :".Number_format($benefit_limit);
                echo "</code>"; */
                

                $rec=$code.' Bonus from '.$firstid.'('. $firstname.') :: step : '.$history_cnt.')';
                $rec_adm= ''.$firstid.' - '.$history_cnt.'대 :'.$today_sales.'*'.$bonus_rate.'='.$benefit;
                
                    
                /* if($benefit_limit > $balance_limit){
                    $benefit_limit = $balance_limit;
                    $rec_adm = "benefit overflow";
                    echo " <span class=red> ▶▶ 수당 초과 (한계까지만 지급)".$benefit_limit." </span><br>";
                    
                }else{

                    // 수당 로그
                    echo $mb_id." | ".$history_cnt." 단계 :: ".$today_sales.'*'.$bonus_rate.'%';
                    echo "<span class=blue> ▶▶▶ 수당 지급 : ".Number_format($benefit)."</span><br>";
                } */


                // 등급계산
                /* $grade_condition = (($history_cnt*2)-1) ; 
                
                if($grade > 0 && $grade >= $grade_condition){
                    $grade_check = 1;
                    
                }else{
                    $grade_check = 0;
                    echo "<span class='red'>등급기준 미달 :: </span>";
                    
                } 

                echo " 등급기준: ".$grade_condition." | ".$grade."</span>";
                */

                /* $package_condition = (($history_cnt*2)-1) ; 

                $high_item = max_item_level_array($mb_id);
                $high_item_num = substr($high_item,1,1);

                if($high_item_num > 0 && $high_item_num >= $package_condition){
                    $package_check = 1; 
                }else{
                    $package_check = 0;
                    echo "<span class='red'>패키지 구매 등급기준 미달 :: </span>";
                } 

                echo "구매등급 기준 : P".$package_condition."이상 | <span class='blue'>".$high_item."</span> 보유</span>"; */


                

                // 직추천자수 
                /*  $mem_cnt_sql="SELECT count(*) as cnt FROM g5_member where mb_recommend = '{$mb_id}' ";
                $mem_cnt_result = sql_fetch($mem_cnt_sql);
                $mem_cnt = $mem_cnt_result['cnt'];

                echo "직추천인수 : ";

                if($mem_cnt>0 && $mem_cnt >= $lvlimit_cnt[$history_cnt-1]){
                    $rank_cnt += 1; $rank_option1 = 1; 
                    echo "<span class='blue'>".$mem_cnt."</span> / <span class='blue'>".$lvlimit_cnt[$history_cnt-1]."</span>";
                    echo "<span class='red'> == OK </span>";
                }else{
                    echo "<span >".$mem_cnt."</span> / <span class='blue'>".$lvlimit_cnt[$history_cnt-1]."</span>";
                } */

                /* 구매 아이템 등급 조건 */
                $matching_lvl = $bonus_layer[$item_rank-1];
                echo " 보유상품등급: ".$item_rank." | 매칭레벨 : <span class='blue'>".$matching_lvl."</span> | 기준등급: <strong>".$history_cnt."</strong></span> ";

                if($item_rank > 0 && $matching_lvl >= $history_cnt){
                    $matching_lvl = 1;
                    
                }else{
                    $matching_lvl = 0;
                    echo "<span class='red'>:: 상품등급기준 미달 </span>";
                } 
                echo "<br><br>";
                

                if($matching_lvl > 0){
                    if($benefit > $benefit_limit && $balance_limit != 0 ){

                        $rec_adm .= "<span class=red> |  Bonus overflow :: ".Number_format($benefit_limit - $benefit)."</span>";
                        echo "<span class=blue> ▶▶ 수당 지급 : ".Number_format($benefit)."</span>";
                        echo "<span class=red> ▶▶▶ 수당 초과 (한계까지만 지급) : ".Number_format($benefit_limit)." </span><br>";
                    }else if($benefit != 0 && $balance_limit == 0 && $benefit_limit == 0){
                
                        $rec_adm .= "<span class=red> | Sales zero :: ".Number_format($benefit_limit - $benefit)."</span>";
                        echo "<span class=blue> ▶▶ 수당 지급 : ".Number_format($benefit)."</span>";
                        echo "<span class=red> ▶▶▶ 수당 초과 (기준매출없음) : ".Number_format($benefit_limit)." </span><br>";
                
                    }else if($benefit == 0){
                        echo "<span class=blue> ▶▶ 수당 0 </span>";
                
                    }else{
                        echo "<span class=blue> ▶▶ 수당 지급 : ".Number_format($benefit)."</span><br>";
                    }
                }else{
                    $benefit_limit = 0;
                    echo "<span> ▶▶ 수당 미발생 </span>";

                   /*  if(!$debug){
                        soodang_extra($mb_id, $code, $benefit, $rec,$rec_adm,$bonus_day);
                    } */
                    
                }

                if($benefit > 0 && $benefit_limit > 0 && $matching_lvl  > 0){

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
            }

            $rec='';
            $matching_lvl = 0;

            /* admin 제외 */
            if($recom == 'MASTER'){
                $recom = 'admin';
            }

            $comp=$recom;
            $history_cnt++;
        } // while

        $history_cnt=0;
        $today_sales=0;
    }
}

// DB 대체
/* function max_item_level_array($mb_id){
    $oreder_result = array_column(ordered_items($mb_id),'it_name');
    if(count($oreder_result) > 0){
        $key = max($oreder_result);
    }else{
        $key = 0;
    }
    return $key;
} */
/* 
function return_down_manager($mb_id,$cnt=0){
	global $config;
	$origin = $mb_id;
	$manager_list = [];
	$i = 0;
    
    if($mb_id != 'admin' && $mb_id != $config['cf_admin']){
		
		if($cnt == 0){
			do{
				$manager = recommend_uptree($mb_id);
				$mb_id = $manager;
				array_push($manager_list,$manager);
			}while( 
				$manager != 'dfine'
			);
		
			if(count($manager_list) < 2){
				return $origin;
			}else{
				return $manager_list[count($manager_list)-2];
			}
		}else{
			do{
				$i++;
				$manager = recommend_uptree($mb_id);
				$mb_id = $manager;
				array_push($manager_list,$manager);
			}while( $i < $cnt );

			return $manager_list[$cnt-1];
		}
    }else{
        return $mb_id;
    }
}

function recommend_uptree($mb_id){
    $result = sql_fetch("SELECT mb_recommend,mb_level from g5_member WHERE mb_id = '{$mb_id}' ");
    return $result['mb_recommend'];
} */

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