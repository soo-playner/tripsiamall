<?php
error_reporting(E_ALL ^ E_NOTICE);
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

$code = "daily";
$bonus_day = date('Y-m-d');

$host_name = 'localhost';
$user_name = 'root';
$user_pwd = 'willsoft0780!@';
$database = 'hwajo';
$conn = mysqli_connect($host_name,$user_name,$user_pwd,$database);


$dupl_check_sql = "select count(mb_id) as cnt from soodang_pay where day='{$bonus_day}' and allowance_name = '{$code}' ";
$dupl_check_result = mysqli_query($conn, $dupl_check_sql);
$get_today = mysqli_fetch_array($dupl_check_result)['cnt'];
if($get_today > 0){
	echo "{$bonus_day} {$code} 수당은 이미 지급되었습니다.";
	die;
}



// 데일리수당
$bonus_row = bonus_pick($code);

$bonus_rate = explode(",",$bonus_row['layer']);
$daily_bonus_rate = $bonus_row['rate'];

$reset_daily_benefit_sql = "update g5_member set mb_my_sales = 0";
if($debug){
	echo "<code>";
	print_r($reset_daily_benefit_sql);
	echo "</code><br>";
}else{
	$reset_result = mysqli_query($conn, $reset_daily_benefit_sql);
	if(!$reset_result){
        echo "<code>";
        print_r($reset_daily_benefit_sql);
        echo "</code><br>";
        die;
	}
}

$order_list_sql = "select s.*, m.mb_level, m.grade, m.mb_name, m.mb_balance,m.mb_balance_ignore, m.mb_deposit_point, m.mb_index
from g5_shop_order s 
join g5_member m 
on s.mb_id = m.mb_id where m.mb_save_point > 0";

$order_list_result = mysqli_query($conn, $order_list_sql);

if($debug){
	echo "<code>";
	print_r($order_list_sql);
	echo "</code><br>";
}

// 설정로그 
echo "<strong>".strtoupper($code)." 지급비율 : ". $daily_bonus_rate."%   </strong> | 지급한계 : ".$bonus_row['limited']."% <br>";
echo "<strong>".$bonus_day."</strong><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";

?>

<html>
	<body>
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
	
	for($i = 0; $i < $order_list_row = mysqli_fetch_array($order_list_result); $i++){
		$goods_price = $order_list_row['upstair'];
		$mb_balance = $order_list_row['mb_balance'];
		$mb_balance_ignore = $order_list_row['mb_balance_ignore'];
		$mb_index = $order_list_row['mb_index'];
		$benefit = $goods_price *((($order_list_row['pv'] * 0.01)/30) * $daily_bonus_rate);

		$total_benefit = $mb_balance + $benefit + $total_paid_list[$order_list_row['mb_id']]['total_benefit'];

		$clean_number_goods_price = clean_number_format($goods_price);
		$clean_number_mb_balance = clean_number_format($mb_balance - $mb_balance_ignore);
		$clean_number_mb_index = clean_number_format($mb_index);
		
		$total_paid_list[$order_list_row['mb_id']]['total_benefit'] += $benefit;
		$total_paid_list[$order_list_row['mb_id']]['real_benefit'] += $benefit;

		$over_benefit_log  = "";
		
		if( $total_benefit > $mb_index ){
			$remaining_benefit = $total_benefit - $mb_index;
			$cut_benefit = $mb_index - $mb_balance;
			
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
		$rec = "Daily bonus {$order_list_row['pv']}% : {$clean_number_benefit} usdt payment{$over_benefit_log}";
		$benefit_log = "{$clean_number_goods_price}(상품가격) * ( ( {$order_list_row['pv']}% [상품지급률]) / 30 ) * {$daily_bonus_rate} ){$over_benefit_log}";
		
		$total_paid_list[$order_list_row['mb_id']]['log'] .= "<br><span>{$benefit_log} = </span><span class='blue'>{$clean_number_benefit}</span>";
		$total_paid_list[$order_list_row['mb_id']]['sub_log'] = "<span>현재총수당 : {$clean_number_mb_balance}, 수당한계점 : {$clean_number_mb_index} </span>";
	
		$log_values_sql .= "('{$code}','{$bonus_day}','{$order_list_row['mb_id']}',{$order_list_row['mb_no']},{$benefit},{$order_list_row['mb_level']},{$order_list_row['grade']},
							'{$order_list_row['mb_name']}','{$rec}','{$benefit_log}={$clean_number_benefit}',{$mb_balance},{$order_list_row['mb_deposit_point']},now()),";
	
	}
	
	foreach($total_paid_list as $key=>$value){
		if($member_balance_column_sql == "") $member_balance_column_sql = "mb_balance = case mb_id ";
		if($member_my_sales_cloumn_sql == "") $member_my_sales_cloumn_sql = ",mb_my_sales = case mb_id ";

		$member_balance_column_sql .= "when '{$key}' then mb_balance + {$value['real_benefit']} ";
		$member_my_sales_cloumn_sql .= "when '{$key}' then {$value['real_benefit']} ";
		
		$member_where_sql .= "'{$key}',";
		echo "<span class='title'>{$key}</span>{$value['sub_log']}<br>{$value['log']}<div style='color:orange;'>발생 수당 : {$value['total_benefit']}</div><div style='color:red;'>▶ 수당지급 : {$value['real_benefit']}</div><br><br>";
	}
	
	$member_balance_column_sql .= "else mb_balance end ";
	$member_my_sales_cloumn_sql .= "else mb_my_sales end ";

	$member_sql = "";
	$log_sql ="";

	if($member_where_sql != "" && $log_values_sql != ""){
		$member_where_sql = substr($member_where_sql,0,-1).")";
		$log_values_sql = substr($log_values_sql,0,-1);

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
			$result = mysqli_query($conn, $log_sql);
			if($result){
				$result = mysqli_query($conn, $member_sql);
				if(!$result){
					echo "<code>ERROR:: MEMBER SQL -> {$member_sql}</code>";
				}
			}else{
				echo "<code>ERROR:: LOG SQL -> {$log_sql}</code>";
			}
		}
	}else{
		echo "<span style='display: flex;justify-content: center; color:red;'>정산할 회원이 존재하지 않습니다.</span>";
	}
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
