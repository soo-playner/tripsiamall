<?php
$sub_menu = "600200";
include_once('./_common.php');

// $debug=1;
include_once('./bonus_inc.php');
auth_check($auth[$sub_menu], 'r');

$category = 'mining';
$binary_location = 'g5_member_binary';

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


//회원 리스트를 읽어 온다.
$sql_common = " FROM g5_member";
$sql_search=" WHERE rank > 0 AND mb_level < 7";
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

// 추천트리 하부 
/* 
function return_down_manager($mb_id,$cnt=0){
	global $config,$g5,$mem_list;

	$mb_result = sql_fetch("SELECT mb_id,mb_rate from g5_member WHERE mb_id = '{$mb_id}' ");
	$result = recommend_downtree($mb_result['mb_id'],0,$cnt);
	return $result;
}


function recommend_downtree($mb_id,$count=0,$cnt = 0){
	global $mem_list;

	if($cnt == 0 || ($cnt !=0 && $count < $cnt)){
		
		$recommend_tree_result = sql_query("SELECT mb_id,mb_rate from g5_member WHERE mb_recommend = '{$mb_id}' ");
		$recommend_tree_cnt = sql_num_rows($recommend_tree_result);

		if($recommend_tree_cnt > 0 ){
			++$count;
			while($row = sql_fetch_array($recommend_tree_result)){
				$list['mb_id'] = $row['mb_id'];
                $list['mb_rate'] = $row['mb_rate'];
				$list['depth'] = $count;
                
				array_push($mem_list,$list);
				recommend_downtree($row['mb_id'],$count,$cnt);
			}
		}
	}
	return $mem_list;
} */

$brcomm_arr = array();
// $test_array = brecommend_array('test6',0,3);
// $mining_matching_sum = array_sum(array_column($test_array, 'mb_rate'));

// print_R($mining_matching_sum);

function brecom_grade($mb_id,$limited =0)
{
    global $config, $brcomm_arr, $debug;
    $origin = $mb_id;

    // 후원 하부 L,R 구분
    list($leg_list, $cnt) = brecommend_direct($mb_id);

    if ($cnt > 1) {

        $L_member = $leg_list[0]['mb_id'];
        $R_member = $leg_list[1]['mb_id'];

        $brcomm_arr = [];
        array_push($brcomm_arr, $leg_list[0]);
        $manager_list_L = brecommend_array($L_member, 0);

        /* echo "<br><br> L ::<br>";
        print_R($manager_list_L); */

        $brcomm_arr = [];
        array_push($brcomm_arr, $leg_list[1]);
        $manager_list_R = brecommend_array($R_member, 0);


        /* echo "<br><br> R ::<br>";
        print_R($manager_list_R); */
    }else{
        return 0;
    } 
}

// 후원트리 하부
function brecommend_array($brecom_id, $count, $limit=0)
{
    global $mem_list,$binary_location;

    // $new_arr = array();
    $b_recom_sql = "SELECT B.mb_id,B.mb_brecommend,B.mb_brecommend_type,(SELECT mb_rate FROM g5_member A WHERE A.mb_id = B.mb_id) AS mb_rate  from {$binary_location} B WHERE mb_brecommend='{$brecom_id}' ";
    $b_recom_result = sql_query($b_recom_sql);
    $cnt = sql_num_rows($b_recom_result);

    if ($cnt < 1) {
        // 마지막
    } else {
        ++$count;
        while ($row = sql_fetch_array($b_recom_result)) {
            brecommend_array($row['mb_id'], $count,$limit);

            // print_R($count.' :: '.$row['mb_id']."<br>");
            // $mem_list[$count]['id'] = $brecom_id;
            if($limit != 0 && $count <= $limit){
                array_push($mem_list, $row);
            }
            
        }
    }
    return $mem_list;
} 

function brecommend_direct($mb_id)
{
    $down_leg = array();
    $sql = "SELECT mb_id,mb_rate,mb_save_point,mb_brecommend_type FROM g5_member where mb_brecommend = '{$mb_id}' AND mb_brecommend != '' ORDER BY mb_brecommend_type ASC ";
    $sql_result = sql_query($sql);
    $cnt = sql_num_rows($sql_result);

    while ($result = sql_fetch_array($sql_result)) {
        array_push($down_leg, $result);
    }
    return array($down_leg, $cnt);
}


function  excute(){

    global $result;
    global $g5, $bonus_day, $bonus_condition, $code, $bonus_rates, $bonus_rate,$pre_condition_in,$bonus_limit,$bonus_layer;
    global $minings,$mining_target,$mining_amt_target,$mem_list,$mining_rate,$mining,$now_mining_coin;
    global $debug;
    
    for ($i=0; $row=sql_fetch_array($result); $i++) {   

        $mb_id=$row['mb_id'];
        $mb_rate = $row['mb_rate'];
        $mb_balance = $row['mb_balance'];
        $item_rank = $row['rank'];
        $bonus_rates = $bonus_rate;

        $matching_lvl = $bonus_layer[$item_rank-1];

        echo "<br><br><span class='title block gold' style='font-size:30px;'>".$mb_id."</span><br>";
        
       
        
        $mining_down_brecom_result = brecommend_array($mb_id,0,$matching_lvl);
        $mining_matching_sum = array_sum(array_column($mining_down_brecom_result, 'mb_rate'));
        $mining_matching_hash = $mining_matching_sum;
        

        echo "<br>";
        echo "▶ 보유상품등급: <strong>".$item_rank."</strong> | 매칭레벨 : <span class='blue'>".$matching_lvl."</span><br> ";
        echo "▶▶후원라인 하부 <span class='blue'>".$matching_lvl."대</span> 해쉬파워/채굴량 :: ";
        if($mining_matching_hash == 0){
            $hash_color = "red";
        }else{
            $hash_color = "blue";
        }
        echo "<span class='".$hash_color."'>".$mining_matching_hash." MH/s </span>";
        echo "<br>";

        // $hash_power = shift_auto(($mb_rate/$tp[0]),2);
        
        // 계산식
        echo "▶▶▶데일리 마이닝지급량  :  <span class='blue'>".$mining_rate." eth</span> / 1mh<br>";
        echo "▶▶▶▶매칭수당지급량 : ".$bonus_rates.' '.$minings[$now_mining_coin]."<br>";

        
        // 직추천자수 
        $mem_cnt_sql = "SELECT count(*) as cnt,(SELECT mb_index from g5_member WHERE mb_id = '{$mb_id}' ) as mb_index FROM g5_member where mb_recommend = '{$mb_id}' AND mb_level > 0 AND rank > 1 ";
        $mem_cnt_result = sql_fetch($mem_cnt_sql);
        $mem_cnt = $mem_cnt_result['cnt'];
        $mem_index = $mem_cnt_result['mb_index'];
        
        if($mem_index == 2 && $mem_cnt < $mem_index){
            $mem_cnt = $mem_index;
        }

        $benefit = ($mining_matching_hash*$mining_rate)*$bonus_rates;

        if($mem_cnt < 2){
            $mem_cnt_color = "red";
        }else{
            $mem_cnt_color = "blue";
        }

        echo "▶▶▶▶▶ LV1 직추천인 : <span class='".$mem_cnt_color."'>".$mem_cnt."</span>";
        echo "<br><br>";

        echo "<code>";
        echo "수당계산 : ".$mining_matching_hash.' * '.$mining_rate.' * '.$bonus_rates.' = '.$benefit;
        echo "</code>";

        list($mb_balance,$balance_limit,$benefit_limit) = mining_limit_check($mb_id,$benefit,$bonus_limit,$code);

        $benefit_limit_point = shift_auto($benefit_limit,COIN_NUMBER_POINT);
        $benefit_point = shift_auto($benefit,COIN_NUMBER_POINT);

        echo "<code>";
        echo "현재수당 : ".shift_auto($mb_balance,COIN_NUMBER_POINT)."  | 수당한계 :". shift_auto($balance_limit,COIN_NUMBER_POINT).' | ';
        echo "발생할수당: ".$benefit_point." | 지급할수당 :".$benefit_limit_point;
        echo "</code><br>";

        if($mem_cnt < 2){
            $benefit = 0;
        }
        $rec_adm = '';
        if($benefit > $benefit_limit && $balance_limit != 0 ){

            $rec_adm .= "<span class=red> |  Bonus overflow :: ".shift_auto($benefit_limit - $benefit)."</span>";
            echo "<span class=blue> ▶▶ 수당 지급 : ".$benefit_point."</span>";
            echo "<span class=red> ▶▶▶ 수당 초과 (한계까지만 지급) : ".$benefit_limit_point." </span><br>";
        }else if($benefit != 0 && $balance_limit == 0 && $benefit_limit == 0){

            $rec_adm .= "<span class=red> | Sales zero :: ".shift_auto(($benefit_limit - $benefit),COIN_NUMBER_POINT)."</span>";
            echo "<span class=blue> ▶▶ 수당 지급 : ".shift_auto($benefit)."</span>";
            echo "<span class=red> ▶▶▶ 수당 초과 (기준매출없음) : ".$benefit_limit_point." </span><br>";
        }else if($benefit == 0){
            echo "<span class=blue> ▶▶ 수당 미발생 </span>";
        }else{
            echo "<span class=blue> ▶▶ 수당 지급 : ".$benefit_point."</span><br>";
        }


        if($benefit > 0 ){
            $rec=$code.' Bonus By '.$matching_lvl.' step | '.$mining_matching_hash.' MH :: '.$benefit_limit_point.' '.$minings[$now_mining_coin];
            

            if($benefit_limit < $benefit){
                $rec_adm =  $mining_matching_hash.' * '.$mining_rate.' * '.$bonus_rates.' = '.$benefit_limit_point." (".$benefit_point.")";
            }else{
                $rec_adm =  $mining_matching_hash.' * '.$mining_rate.' * '.$bonus_rates.' = '.$benefit_limit_point;
            }

            $record_result = mining_record($mb_id, $code, $benefit_limit_point,$bonus_rates,$minings[$now_mining_coin], $rec, $rec_adm, $bonus_day,$mining_matching_hash,$benefit_point);

            
            if($record_result){
                $balance_up = "update g5_member set {$mining_target} = {$mining_target} + {$benefit_limit}, brecom2_mining = {$mining_matching_hash}  where mb_id = '{$mb_id}' ";

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

        $mem_list = array();

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