<?php
include_once('./_common.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');

$now_datetime = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

$od_id = $_POST['od_id'];
$mb_id = $_POST['mb_id'];

// 1. 패키지를 실제 보유하고 있는지 확인
$exist_package = sql_fetch("SELECT *, COUNT('od_id') AS cnt FROM {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id}'");

// 1-1. 패키지 보유하고 있지 않다면 failed
if ($exist_package['cnt'] <= 0) {
	echo json_encode(array("result" => "failed", "code" => "300", "message" => "구매한 패키지가 존재하지 않습니다."));
	return false;
}

// 2. 업그레이드할 패키지 ID 계산(기존 패키지에 + 1)
$up_pack_num = (int)$exist_package['od_tno'] + 1;

// 2-1. 업그레이드할 패키지 존재하는지 조회
$up_pack_info = sql_fetch("SELECT *, COUNT(*) AS cnt FROM {$g5['g5_shop_item_table']} WHERE it_id = '{$up_pack_num}' ");

// 2-2. 업그레이드할 패키지가 없다면(최상위 패키지 이용 중이라면) failed
if ($up_pack_info['cnt'] <= 0) {
	echo json_encode(array("result" => "failed", "code" => "200", "message" => "최상위 패키지에서 더 이상 업그레이드하실 수 없습니다."));
	return false;
}

// 3. 회원 잔고 확인
$mb_info = sql_fetch("SELECT mb_deposit_point + mb_deposit_calc + mb_fee AS sum_deposit, 
mb_balance - mb_shift_amt - mb_fee AS sum_soodang, 
mb_balance + mb_deposit_point + mb_deposit_calc - mb_shift_amt AS balance 
FROM {$g5['member_table']} 
WHERE mb_id = '{$mb_id}'");

// 3-1. 구매 가능 잔고가 부족할 경우 failed 30000 - 10000 > 20000
if(((int)$up_pack_info['it_cust_price'] - (int)$exist_package['od_cart_price']) > floor($mb_info['balance'])) {
	echo json_encode(array("result" => "failed", "code" => "200", "message" => "구매 가능한 잔고가 부족합니다."));
	return false;
}

// 구매가능하다면
// 1. 업그레이드 전 데이터 별도 보관
$up_log_sql = "INSERT INTO g5_shop_order_upgrade (SELECT * from {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id}')";
sql_query($up_log_sql);

// 2. 패키지 업그레이드 진행
$pack_update_sql = "UPDATE {$g5['g5_shop_order_table']} SET
		od_cart_price				= " . $up_pack_info['it_cust_price'] . ",
		od_cash    					= " . $up_pack_info['it_price'] . ",
		upstair    					= " . $up_pack_info['it_point'] . ",
		od_name    					= '" . $up_pack_info['it_maker'] . "',
		od_tno    					= " . $up_pack_info['it_id'] . ",
		pv    							= " . $up_pack_info['it_supply_point'] . ", 
		od_receipt_time   = '" . $now_datetime . "', 
		od_time           = '" . $now_datetime . "', 
		od_date           = '" . $now_date . "',
		od_status					= '패키지업그레이드',
		od_pg							= '" . $exist_package['od_name'] . '->' . $up_pack_info['it_maker'] . "',
		od_app_no							= '" . $exist_package['no'] . "'  WHERE od_id = '" . $od_id . "'";

sql_query($pack_update_sql);

// 3. 상위 패키지 테이블로 이동 및 기존 패키지 테이블에서 삭제
$prev_pack = strtolower($exist_package['od_name']);
$next_pack = strtolower($up_pack_info['it_maker']);
$move_package = "INSERT INTO package_{$next_pack} (idx, mb_id, it_name, nth, cdate, cdatetime, pdate, od_id, promote)
 SELECT idx, mb_id, it_name, nth, cdate, cdatetime, pdate, od_id, promote from package_{$prev_pack} WHERE od_id = '{$od_id}'";
$del_prev_pack = "DELETE FROM package_{$prev_pack} WHERE od_id = '{$od_id}'";
sql_query($move_package);
sql_query($del_prev_pack);

// 4. 회원 지갑에서 업그레이드 금액만큼 차감
$package_price = (int)$up_pack_info['it_price'] - (int)$exist_package['od_cart_price'];
$calc_point = $package_price - floor($mb_info['sum_deposit']);

// 4-1. deposit_point 잔고로 구매 가능할 때
if (floor($mb_info['sum_deposit']) > 0 && floor($mb_info['sum_deposit']) >= $package_price) {
	$update_point = " UPDATE g5_member SET mb_deposit_calc = (mb_deposit_calc - {$package_price}) ";	
} 
// 4-2. deposit_point 잔고가 부족하여 부족한 차액만큼 mb_balance 잔고에서 끌어와 구매할 때
else if ($package_price >= floor($mb_info['sum_deposit']) && floor($mb_info['balance']) >= $calc_point) {
	$update_point = " UPDATE g5_member SET mb_deposit_calc = (mb_deposit_calc - {$package_price}) ";
	$update_point .= ", mb_fee = (mb_fee + {$calc_point}) ";
}

// 해당 패키지로 받을 수 있는 수당 한도()
$sql = "SELECT q_autopack,b_autopack,rank FROM g5_member WHERE mb_id = '{$mb_id}'";
$row = sql_fetch($sql);
if ($row['b_autopack'] > 0) {
	$limited = $row['q_autopack'];
}

$pack_rank_num = substr($up_pack_info['it_maker'], 1, 1);
$update_point .= ", mb_rate = ( mb_rate - {$exist_package['pv']} + {$up_pack_info['it_supply_point']}) ";
$update_point .= ", mb_save_point = ( mb_save_point + {$up_pack_info['it_point']} - {$exist_package['upstair']}) ";
$update_point .= ", mb_index = (SELECT ifnull(sum(od_cart_price),0)*({$limited}/100) FROM {$g5['g5_shop_order_table']} WHERE mb_id = '{$mb_id}')";

if($pack_rank_num >= $row['rank']) {
	$update_point .= ", rank = '{$pack_rank_num}', rank_note = '{$up_pack_info['it_maker']}', sales_day = '{$now_datetime}' ";
}
$update_point .= " WHERE mb_id ='" . $mb_id . "'";
sql_query($update_point);

// 5. promote 컬럼 1로 변경처리
// $promote_update_sql = "UPDATE package_{$next_pack} SET promote = 1 WHERE od_id = '{$od_id}'";
// sql_query($promote_update_sql);

echo json_encode(array("result" => "success", "code" => "0000", "message" => "패키지 업그레이드가 완료되었습니다."));