<?
include_once('./_common.php');

$today = date('Y-m-d');
$bonus_day = $_REQUEST['to_date'];

$b_date_day = date('Y-m',strtotime($bonus_day));
$b_date_month = date('d',strtotime($bonus_day));
// $daydiff = date_diff(date_create($bonus_day), date_create($today));
// $daycount = $daydiff->d;

$db_table_copy = 'g5_member_'.$bonus_day;

if(!sql_query(" DESC `{$db_table_copy}` ", false)) {
    echo $db_table_copy." 해당일 백업데이터가 없습니다.";
    
}else{
	// 해당일 DB에서 mb_balace 복원
    $copysql = "UPDATE g5_member as A SET mb_balance = (SELECT mb_balance FROM `{$db_table_copy}` as B WHERE A.mb_id = B.mb_id) ";
    sql_query($copysql);

	// 수당 내역 삭제
    $sql_del = " DELETE FROM `{$g5['bonus']}` WHERE day >= '{$bonus_day}' ; ";
		$sql_result = sql_query($sql_del);
	// 삭제후 카운트 조정

	if($sql_result){
		$sql_count_reset = "ALTER TABLE `{$g5['bonus']}` AUTO_INCREMENT=1;"; 
		$sql_count_reset .= "SET @COUNT = 0 ;";
		$sql_count_reset .= "UPDATE `{$g5['bonus']}` SET no = @COUNT:=@COUNT+1;";
		sql_query($sql_count_reset);

		//print_r($sql_count_reset);
		
			$count_sql = "select count(*) as count from `{$g5['bonus']}`";
			$count = sql_fetch($count_sql);
			$auto_count = $count['count'];
			$sql_auto_count = "ALTER TABLE `{$g5['bonus']}` AUTO_INCREMENT={$auto_count};"; 
			
			sql_query($sql_auto_count);
			//print_r($sql_auto_count);
	}

	// 누적 데이터 삭제
	if($sql_result){
		$sql_sales_del = " DELETE FROM brecom_bonus_noo WHERE day >= '{$bonus_day}' ; ";
			sql_query($sql_sales_del);
		$sql_sales_del = " DELETE FROM brecom_bonus_week WHERE day >= '{$bonus_day}' ; ";
			sql_query($sql_sales_del);
		$sql_sales_del = " DELETE FROM brecom_bonus_today WHERE day >= '{$bonus_day}' ; ";
			sql_query($sql_sales_del);

		$sql_sales_del = " DELETE FROM recom_bonus_noo WHERE day >= '{$bonus_day}' ; ";
			sql_query($sql_sales_del);
		$sql_sales_del = " DELETE FROM recom_bonus_week WHERE day >= '{$bonus_day}' ; ";
			sql_query($sql_sales_del);
		$sql_sales_del = " DELETE FROM recom_bonus_today WHERE day >= '{$bonus_day}' ; ";
			sql_query($sql_sales_del);
		$sql_sales_del = " DELETE FROM iwol WHERE iwolday >= '{$bonus_day}' ; ";
			sql_query($sql_sales_del);
	}
		
	
	// 백업 DB삭제

	
    $droptarget = "FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = 'haz' AND TABLE_NAME LIKE 'g5_member_2020%'
    AND MID(TABLE_NAME, 11, 10) >= '{$bonus_day}' ";

    $drops_query = "SELECT COUNT(*) cnt ". $droptarget;
    
    $drops_result = sql_fetch($drops_query);
    $drops_array = $drops_result['cnt'];

	// echo "<br>".$drops_query."<br>";
    // echo "CNT :: ".$drops_array."<br>";

    $drops_table_query = "SELECT TABLE_NAME ". $droptarget;
    $drops_result = sql_query($drops_table_query);

    while($row = sql_fetch_array($drops_result)){
        $tables = $row["TABLE_NAME"];
        $drop_query = "DROP TABLE `{$tables}` ; ";
        sql_query($drop_query);
    }
		   
    
    echo $bonus_day." 수당지급전으로 복원했습니다.";
    
}

/*r
	$sql_clear2 = " TRUNCATE table bnoo2; ";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table bthirty2; ";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table iwol;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table soodang_pay;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table noo2;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = "TRUNCATE table btoday2;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table thirty2";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table rank";
		$sql_result = sql_query($sql_clear2);
	
	echo "result : ".$sql_result;
*/
?>
