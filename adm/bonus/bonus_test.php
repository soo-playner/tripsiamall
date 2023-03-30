<?
include_once('./_common.php');

$today = date('Y-m-d');
$bonus_day = $_REQUEST['to_date'];

$b_date_day = date('Y-m',strtotime($bonus_day));
$b_date_month = date('d',strtotime($bonus_day));

// $daydiff = date_diff(date_create($bonus_day), date_create($today));
// $daycount = $daydiff->d;

$db_table_copy = 'g5_member_'.$bonus_day;

	
	// 백업 DB삭제
	for($i=0; $i<$daycount+1; $i++){
		$calc_day = $b_date_month + $i;
		$calc_date = $b_date_day."-".$calc_day;

		$db_table_copy = 'g5_member_'.$calc_date;
		// $drop_query = "DROP TABLE `{$db_table_copy}` ;";
    }   

        // $drop_result = sql_query($drop_query);
    
    // echo $bonus_day." 수당지급전으로 복원했습니다.";
   
    echo $bonus_day."<br>";

    $droptarget = "FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = 'haz' AND TABLE_NAME LIKE 'g5_member_2020%'
    AND MID(TABLE_NAME, 11, 10) >= '{$bonus_day}' ";

    $drops_query = "SELECT COUNT(*) cnt ". $droptarget;
    // echo "<br>".$drops_query."<br>";
    $drops_result = sql_fetch($drops_query);
    $drops_array = $drops_result['cnt'];

    echo "CNT :: ".$drops_array."<br>";

    $drops_table_query = "SELECT TABLE_NAME ". $droptarget;
    $drops_result = sql_query($drops_table_query);

    while($row = sql_fetch_array($drops_result)){
        $tables = $row["TABLE_NAME"];
        $drop_query = "DROP TABLE `{$tables}` ; ";
        echo $drop_query;
    }

?>
