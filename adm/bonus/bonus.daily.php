<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

// 데일리수당
// $debug = 1;
$bonus_row = bonus_pick($code);

$bonus_rate = explode(",",$bonus_row['layer']);
$daily_bonus_rate = $bonus_row['rate'];

$reset_daily_benefit_sql = "update g5_member set mb_my_sales = 0";
if($debug){
	echo "<code>";
	print_r($reset_daily_benefit_sql);
	echo "</code><br>";
}else{
	$reset_result = sql_query($reset_daily_benefit_sql);
	if(!$reset_result){
		echo "<script>alert('DAILY 지급중 문제가 발생하였습니다.');
				history.back();</script>";
	}
}

$order_list_sql = "select s.*, m.mb_level, m.grade, m.mb_name, m.mb_balance, m.mb_deposit_point, m.mb_save_point
from g5_shop_order s 
join g5_member m 
on s.mb_id = m.mb_id where m.mb_balance < m.mb_save_point";

$order_list_result = sql_query($order_list_sql);

if($debug){
	echo "<code>";
	print_r($order_list_sql);
	echo "</code><br>";
}

// 설정로그 
echo "<strong>".strtoupper($code)." 지급비율 : ". $daily_bonus_rate."%   </strong> |    지급조건 :".$pre_condition."  |    지급한계 : ".$bonus_row['limited']."% <br>";
echo "<strong>".$bonus_day."</strong><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";

?>

<html><body>
<header>정산시작</header>    
<div>
	
<?php

if(!$get_today){

	$member_start_sql = "update g5_member set ";
	$member_balance_column_sql = "";
	$member_my_sales_cloumn_sql = "";
	$member_where_sql = " where mb_id in (";
	
	$log_start_sql = "insert into soodang_pay(`allowance_name`,`day`,`mb_id`,`mb_no`,`benefit`,`mb_level`,`grade`,`mb_name`,`rec`,`rec_adm`,`origin_balance`,`origin_deposit`,`datetime`) values";
	$log_values_sql = "";
	
	$total_paid_list = array();
	
	for($i = 0; $i < $order_list_row = sql_fetch_array($order_list_result); $i++){
		$goods_price = $order_list_row['upstair'];
		$mb_balance = $order_list_row['mb_balance'];
		$mb_save_point = $order_list_row['mb_save_point'];
		$benefit = $goods_price *(($order_list_row['pv'] * 0.01) * $daily_bonus_rate);

		$total_benefit = $mb_balance + $benefit + $total_paid_list[$order_list_row['mb_id']]['total_benefit'];

		$clean_number_goods_price = clean_number_format($goods_price);
		$clean_number_mb_balance = clean_number_format($mb_balance);
		$clean_number_mb_save_point = clean_number_format($mb_save_point);
		

		$total_paid_list[$order_list_row['mb_id']]['total_benefit'] += $benefit;
		$total_paid_list[$order_list_row['mb_id']]['real_benefit'] += $benefit;

		$over_benefit_log  = "";
		
		if( $total_benefit > $mb_save_point ){
			$remaining_benefit = $total_benefit - $mb_save_point;
			$cut_benefit = $mb_save_point - $mb_balance;
			
			$origin_benefit = $benefit;
			if($benefit - $remaining_benefit > 0) {
				$benefit -= $remaining_benefit;		
			}else{
				$benefit = 0;
			}
			$over_benefit = $origin_benefit - $benefit;
			$clean_over_benefit = clean_number_format($over_benefit);
			$clean_origin_benefit = clean_number_format($origin_benefit);

			$total_paid_list[$order_list_row['mb_id']]['real_benefit'] = $cut_benefit;
			$over_benefit_log = " (over benefit : {$clean_over_benefit} / {$clean_origin_benefit})";
		}

		$clean_number_benefit = clean_number_format($benefit);
		$rec = "Daily( {$order_list_row['pv']}% ) : {$clean_number_benefit}지급{$over_benefit_log}";
		$benefit_log = "{$clean_number_goods_price}(상품가격) * ( ({$order_list_row['pv']}(상품보너스) * 0.01 ) * {$daily_bonus_rate}(매일지급보너스) ){$over_benefit_log}";

		$total_paid_list[$order_list_row['mb_id']]['log'] .= "<br><span>현재총수당 : {$clean_number_mb_balance}, 수당한계점 : {$clean_number_mb_save_point} | {$benefit_log} = </span><span class='blue'>{$clean_number_benefit}</span>";
	
		$log_values_sql .= "('{$code}','{$now_date}','{$order_list_row['mb_id']}',{$order_list_row['mb_no']},{$benefit},{$order_list_row['mb_level']},{$order_list_row['grade']},
							'{$order_list_row['mb_name']}','{$rec}','{$benefit_log}={$clean_number_benefit}',{$mb_balance},{$order_list_row['mb_deposit_point']},now()),";
	
	}
	
	foreach($total_paid_list as $key=>$value){
		if($member_balance_column_sql == "") $member_balance_column_sql = "mb_balance = case mb_id ";
		if($member_my_sales_cloumn_sql == "") $member_my_sales_cloumn_sql = ",mb_my_sales = case mb_id ";

		$member_balance_column_sql .= "when '{$key}' then mb_balance + {$value['real_benefit']} ";
		$member_my_sales_cloumn_sql .= "when '{$key}' then {$value['real_benefit']} ";
		
		$member_where_sql .= "'{$key}',";
		echo "<span class='title'>{$key}</span> {$value['log']}<div style='color:orange;'>당일 예정 수당 : {$value['total_benefit']}</div><div style='color:red;'>▶ 당일 실제 수당 : {$value['real_benefit']}</div><br><br>";
	}
	
	$member_balance_column_sql .= "else mb_balance end ";
	$member_my_sales_cloumn_sql .= "else mb_my_sales end ";

	$member_sql = "";
	$log_sql ="";

	if($member_where_sql != "" && $log_values_sql != ""){
		$member_where_sql[-1] = ")";
		$log_values_sql[-1] = " ";
		$member_sql = $member_start_sql.$member_balance_column_sql.$member_my_sales_cloumn_sql.$member_where_sql;
		$log_sql = $log_start_sql.$log_values_sql;
	}
	
	if($member_sql != "" && $log_sql != ""){	
		// 디버그 로그
		if($debug){
			echo "<code>";
			print_R($member_sql);
			echo "</code>";
			echo "<br>";
			echo "<code>";
			print_R($log_sql);
			echo "</code>";
		}else{
			$result = sql_query($log_sql);
			if($result){
				$result = sql_query($member_sql);
				if(!$result){
					echo "ERROR:: MEMBER SQL -> {$member_sql}";
				}
			}else{
				echo "ERROR:: LOG SQL -> {$log_sql}";
			}
		}
	}else{
		echo "<span style='display: flex;justify-content: center; color:red;'>정산할 회원이 존재하지 않습니다.</span>";
	}
}
	include_once('./bonus_footer.php');
	
	//로그 기록
	if($debug){}else{
		$html = ob_get_contents();
		//ob_end_flush();
		$logfile = G5_PATH.'/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
		fopen($logfile, "w");
		file_put_contents($logfile, ob_get_contents());
	}
 ?>