<?php 
include_once('./_common.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');

$od_id = intval(trim($_POST['od_id']));

$prev_package = "SELECT od_cart_price, od_tno, COUNT(*) AS cnt FROM {$g5['g5_shop_order_table']} WHERE od_id = '{$od_id}'";
$pp_result = sql_fetch($prev_package);

$np_num = intval($pp_result['od_tno']) + 1;
$next_package = "SELECT it_cust_price, COUNT(*) AS cnt FROM {$g5['g5_shop_item_table']} WHERE it_id = '{$np_num}'";

$np_result = sql_fetch($next_package);

$diff_price = $np_result['it_cust_price'] - $pp_result['od_cart_price'];

if($pp_result['cnt'] > 0 && $np_result['cnt'] > 0) {
  echo json_encode(array(
    "result" => "success", 
    "it_cust_price" => "{$np_result['it_cust_price']}",
    "diff_price" => "$diff_price"
  ));
} else {
  echo json_encode(array("result" => "failed"));
}


