<?
include_once('./_common.php');

$today = date('Y-m-d');
$today_time = date('Y-m-d H:i:s');
$order_id = date("YmdHis",time());

$dumy_item_tno = 2020052090;
$dumy_item = 'Package3'; // 상품
$dumy_output_val = 1400; // 상품구매금액 ($)
$dumy_input_val = 6.93749; // 상품구매금액 (코인)
$exchange_rate = 201.802; // ETH 시세


// $b_date_day = date('Y-m',strtotime($bonus_day));
// $b_date_month = date('d',strtotime($bonus_day));
// $daydiff = date_diff(date_create($bonus_day), date_create($today));
// $daycount = $daydiff->d;

// $db_table_copy = 'g5_member_'.$bonus_day;


// 멤버 1~10까지 초기화후 ETH 지급 
$sql_member_reset = "UPDATE g5_member SET 
    mb_eth_account = 10
    WHERE mb_no > 1 AND mb_no < 12 ";
    
    if($debug){
        print($sql_member_reset);
        echo "<br>";
    }else{
        sql_query($sql_member_reset);
    }

// 구매내역 생성
for($i=2;$i < 12; $i++ ){
    $sql = "SELECT * FROM g5_member WHERE mb_no = $i ";
    $sql_result = sql_query($sql);
    $dumy_member = sql_fetch_array($sql_result);
    $d_mb_id = $dumy_member['mb_id'];
    $orderid = $order_id.$dumy_member['mb_no'];

    $sql_order_dumy = "INSERT g5_shop_order set od_id = '{$orderid}',
    mb_no = '{$i}' ,
    mb_id = '{$d_mb_id}',
    od_cart_price = {$dumy_output_val},
    od_cash = {$exchange_rate}, 
    od_receipt_time = '{$today_time}',
    od_time = '{$today_time}', 
    od_name = '{$dumy_item}' , 
    od_tno = '{$dumy_item_tno}' , 
    od_date = '{$today}' , 
    od_settle_case = 'eth' , 
    od_status = '패키지 구매' , 
    upstair = {$dumy_input_val} , 
    pv = {$dumy_input_val} ";
    
    //$order_g_sql = "INSERT package_g1 SET mb_id = 'admin', idx= 0, nth = 0+1, cdate = '2020-05-27', cdatetime = '2020-05-27 16:25:37', od_id = 2020052716253701"

    if($debug){
        echo "<br>";
        print($sql_order_dumy);
        echo "<br>";
    }else{
        sql_query($sql_order_dumy);
    }

    // 상품구매등록
    for($k=1; $k <4; $k++){
        $pack_table = "package_g".$k;

        $count_colum_sql = "SELECT count(*) as cnt FROM {$pack_table}";
        $count_colum = sql_fetch($count_colum_sql);
        $count_colum_cnt = $count_colum['cnt']+1;

        $it_name = $k."_".$d_mb_id."_1";

        $order_pack_sql = "INSERT {$pack_table} SET mb_id = '{$d_mb_id}', 
        idx= {$count_colum_cnt}, 
        nth = 1,
        it_name = '{$it_name}',
        cdate = '{$today}',
        cdatetime = '{$today_time}',
        od_id = '{$orderid}' ";

        if($debug){
            echo "<br>";
            print($order_pack_sql);
            echo "<br>";
        }else{
            sql_query($order_pack_sql);
        }
    }

    // 회원구매반영
    $member_up_sql = "UPDATE g5_member set 
    mb_eth_calc = (mb_eth_calc-{$dumy_input_val}), 
    mb_deposit_point = (mb_deposit_point + {$dumy_output_val}) , 
    sales_day = '{$today}' , 
    rank_note = '{$dumy_item}' 
    where mb_id ='{$d_mb_id}' ";
    if($debug){
        echo "<br>";
        print($member_up_sql);
        echo "<br>";
    }else{
        sql_query($member_up_sql);
    }

    
}


// G0 더미 데이터 생성
for($j=0; $j <5; $j++){

    $count_colum_sql = "SELECT count(*) as cnt FROM package_g0";
    $count_colum = sql_fetch($count_colum_sql);
    $count_colum_cnt = $count_colum['cnt']+1;

    $count_item_sql = "SELECT count(*) as cnt FROM package_g0 where mb_id = 'dumy' ";
    $count_item = sql_fetch($count_item_sql);
    $count_item_cnt = $count_item['cnt']+1;
    
    
    $dumy_pack_sql = "INSERT package_g0 SET mb_id = 'dumy', 
    idx= {$count_colum_cnt}, 
    nth = {$count_item_cnt},
    cdate = '{$today}',
    cdatetime = '{$today_time}',
    od_id = '000' ";

    if($debug){
        echo "<br>";
        print($dumy_pack_sql);
        echo "<br>";
    }else{
        sql_query($dumy_pack_sql);
    }
}

echo $bonus_day." 데이터를 생성 했습니다.";
    


?>
