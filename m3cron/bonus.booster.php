<?php
ob_start();
$debug = false;

function bonus_pick($val){    
    global $conn;
    $pick_sql = "select * from wallet_bonus_config where code = '{$val}' ";
    $pick_result = mysqli_query($conn, $pick_sql);
    $list = mysqli_fetch_array($pick_result);
    return $list;
}

function clean_coin_format($val, $decimal = 8){
	$_num = (int)str_pad("1",$decimal+1,"0",STR_PAD_RIGHT);
	return floor($val*$_num)/$_num;
}

function clean_number_format($val, $decimal = 2){
	$_decimal = $decimal <= 0 ? 1 : $decimal;
	$_num = number_format(clean_coin_format($val,$decimal), $_decimal);
    $_num = rtrim($_num, 0);
    $_num= rtrim($_num, '.');

    return $_num;
}

$code = "booster";
$bonus_day = date('Y-m-d');

$host_name = 'localhost';
$user_name = 'root';
$user_pwd = 'willsoft0780!@';
$database = 'hwajo';
$conn = mysqli_connect($host_name,$user_name,$user_pwd,$database);

$check_bonus_day_sql = "select count(day) as cnt from soodang_pay where day = '{$bonus_day}' and allowance_name = 'daily'";
$check_bonus_day_result  = mysqli_query($conn,$check_bonus_day_sql);
$check_bonus_day = mysqli_fetch_array($check_bonus_day_result)['cnt'];

if($check_bonus_day <= 0){
    echo "<code>{$check_bonus_day_sql}</code><br>";
    echo "{$bonus_day} DAILY 보너스 기록이 없습니다.";
    die;
}

$check_booster_today_sql = "select count(day) as cnt from soodang_pay where day = '{$bonus_day}' and allowance_name = '{$code}'";
$check_booster_today_result  = mysqli_query($conn,$check_booster_today_sql);
$check_booster_today = mysqli_fetch_array($check_booster_today_result)['cnt'];

if($check_booster_today > 0){
    echo "<code>{$check_booster_today_sql}</code><br>";
    echo "{$bonus_day} {$code} 는 이미 지급되었습니다.";
    die;
}


// php 버전 대응 패치
if( !function_exists( 'array_column' ) ):
    
    function array_column( array $input, $column_key, $index_key = null ) {
    
        $result = array();
        foreach( $input as $k => $v )
            $result[ $index_key ? $v[ $index_key ] : $k ] = $v[ $column_key ];
        
        return $result;
    }
endif;



// 데일리수당
$bonus_row = bonus_pick($code);

$bonus_rate = explode(",",$bonus_row['layer']);
$booster_bonus_rate = explode(",",$bonus_row['rate']);

$rate_text = "";
for($i = 0; $i < count($bonus_rate); $i++){
    if($i%2 == 0){
        $rate_text .= "<br>|";
    }
    $rate_text .= " 지급대수 : {$bonus_rate[$i]} 대 ({$booster_bonus_rate[$i]}%) | ";
}

if($debug){
    echo "<code>";
	print_r($check_bonus_day_sql);
	echo "</code><br>";
}

// 설정로그 
echo "<strong>".strtoupper($code)." 지급비율 : ". $rate_text."   </strong> | 지급한계 : ".$bonus_row['limited']."% <br>";
echo "<strong>".$bonus_day."</strong><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>


<html>
    <body>
        <header>정산시작</header>    
        <div>
        
        <?php

$member_for_paying_sql = "select mb_id as id, mb_name, mb_no, mb_level, grade, mb_balance, mb_index,mb_balance_ignore, mb_deposit_point, (select count(*) from g5_member where mb_recommend = id) as cnt from g5_member where mb_save_point >= 0";

if($debug){echo "<code>{$member_for_paying_sql}</code>";}

$member_for_paying_result = mysqli_query($conn, $member_for_paying_sql);

$mem_list = array();

$start_member_update_sql = "update g5_member set ";
$update_mb_balance_sql = "";
$update_recom_sales ='';
$update_where_sql = " where mb_id in(";

$log_start_sql = "insert into soodang_pay(`allowance_name`,`day`,`mb_id`,`mb_no`,`benefit`,`mb_level`,`grade`,`mb_name`,`rec`,`rec_adm`,`origin_balance`,`origin_deposit`,`datetime`) values";
$log_values_sql = "";

for($i = 0; $i < $row = mysqli_fetch_array($member_for_paying_result); $i++){
    
    $mb_id = $row['id'];
    $recommended_cnt = $row['cnt'];

    if($recommended_cnt >= 12){$recommended_cnt = $bonus_rate[11];}
    if($recommended_cnt == 11){$recommended_cnt = $bonus_rate[$recommended_cnt-1];}

    $booster_member = return_down_manager($row['mb_no'],$recommended_cnt);

    $recom_member = return_down_manager($row['mb_no'],20);
    

    $recom_sales = array_int_sum($recom_member, 'mb_save_point', 'int');
    
    
    if (!$recom_sales) {
        $recom_sales = 0;
    }
    
    if($debug){
        echo "<code>";
        echo "하부매출 : ".$recom_sales;
        echo "</code>";
    }
    

    $sort_arr = array();
    foreach($booster_member as $key => $value){$sort_arr[$key] = $value['depth'];}
    array_multisort($sort_arr,SORT_ASC,$booster_member);

    $add_benefit = 0;

    $mb_index = $row['mb_index'];
    $mb_balance = $row['mb_balance'];
	$mb_balance_ignore = $row['mb_balance_ignore'];

    $total_left_benefit = $mb_index - $mb_balance;

    $clean_total_left_benefit = clean_number_format($total_left_benefit);
    $clean_number_mb_balance = clean_number_format($mb_balance - $mb_balance_ignore);
    
    $clean_number_mb_index = clean_number_format($mb_index);
    
    echo "<div><span class='title'>{$mb_id} ( 추천인수 : {$row['cnt']}명 [{$recommended_cnt}대] )</span> 현재총수당 : {$clean_number_mb_balance}, 수당한계점 : {$clean_number_mb_index} => 총 수용가능 수당 : {$clean_total_left_benefit}</div><br>";

    foreach($booster_member as $key => $value){

        if($value['mb_id'] == $mb_id){continue;}

        $depth = $value['depth'];

        $bonus_benefit_rate = get_bonus_rate($depth);
        
        $booster_benefit = $value['mb_my_sales'] * ($bonus_benefit_rate * 0.01) * 0.5;
        $add_benefit += $booster_benefit;
        echo "<span>{$value['mb_id']} ( {$depth} 대 ) => {$value['mb_my_sales']} (DAILY 수당) * ( {$bonus_benefit_rate} (보너스 비율) * 0.01 ) * 0.5) = </span><span class='blue'>{$booster_benefit}</span><br>";
    }
    
    $origin_benefit = $add_benefit;
    $over_benefit_log = "";
    if($total_left_benefit < $add_benefit){
        $add_benefit = $total_left_benefit;
        $over_benefit = $origin_benefit - $total_left_benefit;
        $clean_over_benefit = clean_number_format($over_benefit);
        $over_benefit_log = " (over benefit : {$clean_over_benefit} / {$clean_number_mb_index})";
    }

    if($add_benefit < 0){
        $add_benefit = 0;
    }

    echo "<div style='color:orange;'>발생 수당 : {$origin_benefit}</div><div style='color:red;'>▶ 수당 지급: {$add_benefit}</div><br><br>";

    if($update_mb_balance_sql == "") {$update_mb_balance_sql .= "mb_balance = case mb_id ";}
    if($update_recom_sales == "") {$update_recom_sales .= ", recom_sales = case mb_id ";}

    $update_mb_balance_sql .= "when '{$mb_id}' then mb_balance + {$add_benefit} ";
    $update_recom_sales .= "WHEN '{$mb_id}' then {$recom_sales} ";
    $update_where_sql .= "'{$mb_id}',";

    $rec = "Booster bonus by step {$recommended_cnt} :: {$add_benefit} usdt payment{$over_benefit_log}";
    $rec_adm = "{$rec} (expected : {$origin_benefit})";

    $log_values_sql .= "('{$code}','{$bonus_day}','{$mb_id}',{$row['mb_no']},{$add_benefit},{$row['mb_level']},{$row['grade']},
    '{$row['mb_name']}','{$rec}','{$rec_adm}',{$mb_balance},{$row['mb_deposit_point']},now()),";
}
    $update_mb_balance_sql .= " else mb_balance end ";
    $update_recom_sales .= " ELSE recom_sales END ";



    $update_where_sql = substr($update_where_sql,0,-1).")";
    $log_values_sql = substr($log_values_sql,0,-1);

    $update_sql = $start_member_update_sql.$update_mb_balance_sql.$update_recom_sales.$update_where_sql;
    $log_sql = $log_start_sql.$log_values_sql;

    if($debug){
        echo "<code>{$update_sql}</code>";
        echo "<code>{$log_sql}</code>";
    }else{
        
        $result = mysqli_query($conn, $log_sql);
        if($result){
            $result = mysqli_query($conn, $update_sql);
            if(!$result){
                echo "<code>ERROR:: MEMBER SQL -> {$update_sql}</code>";
            }
        }else{
            echo "<code>ERROR:: LOG SQL -> {$log_sql}</code>";
        }
    }


function get_bonus_rate($depth){
    global $booster_bonus_rate;

    if($depth <= 10){$bonus_benefit_rate = $depth > 0 ? $booster_bonus_rate[$depth-1] : 0;}
    else if($depth >= 11 && $depth <= 15){$bonus_benefit_rate = $booster_bonus_rate[10];}
    else if($depth >= 16){$bonus_benefit_rate = $booster_bonus_rate[11];}

    return $bonus_benefit_rate;
}

/* 추천하부매니저 검색 */
function return_down_manager($mb_no,$cnt=0){
	global $conn, $mem_list;

    $mb_sql = "SELECT mb_id,mb_name,mb_level,grade,mb_rate,mb_save_point,rank,recom_sales,mb_my_sales from g5_member WHERE mb_no = '{$mb_no}' ";
	$mb_result = mysqli_query($conn, $mb_sql);
    $mb_row = mysqli_fetch_array($mb_result);
	$list = [];
	$list['mb_id'] = $mb_row['mb_id'];
	$list['mb_name'] = $mb_row['mb_name'];
	$list['mb_level'] = $mb_row['mb_level'];
    $list['mb_save_point'] = $mb_row['mb_save_point'];
	$list['grade'] = $mb_row['grade'];
	$list['depth'] = 0;
	$list['mb_rate'] = $mb_row['mb_rate'];
	$list['recom_sales'] = $mb_row['recom_sales'];
	$list['rank'] = $mb_row['rank'];
	$list['mb_my_sales'] = $mb_row['mb_my_sales'];

	$mem_list = [$list];
	$result = recommend_downtree($mb_row['mb_id'],0,$cnt);

	return $result;
}


function recommend_downtree($mb_id,$count=0,$cnt = 0){
	global $conn, $mem_list;

	if($cnt == 0 || ($cnt !=0 && $count < $cnt)){
		
		$recommend_tree_result = mysqli_query($conn, "SELECT mb_id,mb_name,mb_level,grade,mb_rate,mb_save_point,rank,recom_sales,mb_my_sales from g5_member WHERE mb_recommend = '{$mb_id}' ");
		$recommend_tree_cnt = mysqli_num_rows($recommend_tree_result);
		if($recommend_tree_cnt > 0 ){
			++$count;

			while($row = mysqli_fetch_array($recommend_tree_result)){
				$list['mb_id'] = $row['mb_id'];
				$list['mb_name'] = $row['mb_name'];
				$list['mb_level'] = $row['mb_level'];
				$list['grade'] = $row['grade'];
				$list['mb_rate'] = $row['mb_rate'];
				$list['recom_sales'] = $row['recom_sales'];
                $list['mb_save_point'] = $row['mb_save_point'];
				$list['rank'] = $row['rank'];
				$list['mb_my_sales'] = $row['mb_my_sales'];
				$list['depth'] = $count;
				array_push($mem_list,$list);
				recommend_downtree($row['mb_id'],$count,$cnt);
			}
		}
	}
	return $mem_list;
}


/* 결과 합계 */
function array_int_sum($list, $key){
	return array_sum(array_column($list, $key));
}
?>


</div>
<footer > 정산 완료</footer>

<div class='btn' onclick="bonus_url('<?=$category?>');">돌아가기</div>

<body>
</html>

<style>
	body{font-size:14px;line-height:18px;letter-spacing:0px;}
	code{color:green;display:block;margin-bottom:5px;font-size:11px;}
    .red{color:red;font-weight:600;}
    .blue{color:blue;font-weight:600;}
	.big {font-size:16px;font-weight:600;}
	.title{font-weight:800;color:black;font-size:16px;display:block;}
	.box{background:ghostwhite;margin-top:30px;border-bottom:1px solid #eee;padding-left:5px;width:100%;display:block;}
	.block{font-size:26px; background: turquoise;display: block;height: 30px;line-height: 30px;}
	.block.coral{background:lightcoral}
	.indent{text-indent:20px;display: inline-block;}
	.btn{background:black; padding:5px 20px; display:inline-block;color:white;font-weight:600;cursor:pointer;margin-bottom:20px;}
	footer,header{margin:20px 0; background:black;color:white;text-align:center}
	.error{display:block;width:100%;text-align:center;height:150px;line-height:150px}
	.hidden{display:none;}
	.desc{font-size:11px;color:#777;}
	.subtitle{font-size:20px;}
	.sys_log{margin-bottom:30px;}
</style>


<script>
 function bonus_url($val){
	 if($val == 'mining'){
		location.href = '/adm/bonus/bonus_mining.php?to_date=<?=$bonus_day?>';
	 }else{
		location.href = '/adm/bonus/bonus_list.php?to_date=<?=$bonus_day?>';
	 }
     
 }
</script>


<?php
//로그 기록
if($debug){}else{
    $html = ob_get_contents();
    //ob_end_flush();
    $logfile = '/var/www/html/hwajo/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
    file_put_contents($logfile, ob_get_contents());
}
?>