<?
include_once('./_common.php');

$today = date('Y-m-d');
$bonus_day = $_REQUEST['to_date'];


// 데이터 리셋
$sql_member_reset = "UPDATE g5_member SET mb_balance = 0
, mb_deposit_point = 0
, mb_deposit_calc = 0
, mb_level = 0
, mb_point = 0
, sales_day = ''
, rank_note = ''
, rank = 0 ";

// echo $sql_member_reset;
$sql_member_result = sql_query($sql_member_reset);
$sql_member_reset2 = sql_query(" UPDATE g5_member set grade = 0 WHERE mb_no > 1 ");

if($sql_member_result){
    
    $sql_sales_del = " TRUNCATE table soodang_pay ; ";
    $result1 =sql_query($sql_sales_del); 
}

if($result1){
    
    $sql_sales_del = " TRUNCATE table g5_shop_order ; ";
    sql_query($sql_sales_del);
    $sql_sales_del = " TRUNCATE table package_log ; ";
    $result2 = sql_query($sql_sales_del); 
}
if($result2){
$sql_sales_del = " TRUNCATE table package_r0 ; ";
    sql_query($sql_sales_del);
$sql_sales_del = " TRUNCATE table package_r1 ; ";
    sql_query($sql_sales_del);
$sql_sales_del = " TRUNCATE table package_r2 ; ";
    sql_query($sql_sales_del); 
$sql_sales_del = " TRUNCATE table package_r3 ; ";
    sql_query($sql_sales_del);   
$sql_sales_del = " TRUNCATE table package_r4 ; ";
    sql_query($sql_sales_del); 
}

if($result2){
    echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => '초기화완료')));
}
    
?>
