<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

// 데일리수당

$bonus_row = bonus_pick($code);

$bonus_limit = $bonus_row['limited']/100;
$bonus_rate = $bonus_row['rate']*0.01;

$mem_list = "select * from {$g5['member_table']} where (1)".$pre_condition .$admin_condition." order by mb_no asc";
$rst_list = sql_query($mem_list);

if($debug){
	echo "<code>";
	print_r($mem_list);
	echo "</code><br>";
}

// 설정로그 
echo "<strong>".strtoupper($code)." 지급비율 : ". $bonus_row['rate']."%   </strong> |    지급조건 :".$pre_condition."  |    지급한계 : ".$bonus_row['limited']."% <br>";
echo "<strong>".$bonus_day."</strong><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
?>

<html><body>
<header>정산시작</header>    
<div>
<?



if(!$get_today ){

	/*현재수당 테이블 복사*/
	if(!$debug){
		$db_table_copy = 'g5_member_'.$bonus_day;
		if(!sql_query(" DESC `{$db_table_copy}` ", false)) {
			sql_query(" CREATE TABLE IF NOT EXISTS `$db_table_copy` (
						`mb_no` INT(11) DEFAULT NULL ,
						`mb_id` VARCHAR(255) NOT NULL DEFAULT '',
						`mb_balance` DOUBLE NOT NULL DEFAULT '0'
						) ", false);
			
			$copysql = "INSERT INTO `{$db_table_copy}`(mb_no, mb_id, mb_balance) SELECT mb_no, mb_id, mb_balance FROM  g5_member ";
			sql_query($copysql);
		}
	}


	/* 매출통계기록 */
	delete_sales();
		echo "<br>"."매출 통계 기록 생성"."<br><br><br>";
	habu_sales_calc('',$config['cf_admin'],0);


	/* 데일리수당발생 */
	while($mrow = sql_fetch_array($rst_list)){
		
		$grade = $mrow['grade'];
		$mb_level = $mrow['mb_level'];
		$mb_balance = $mrow['mb_balance'];
		$mb_deposit = $mrow['mb_deposit_point'];

		$benefit = round($mb_deposit * $bonus_rate ,2);
		$allowance_name = $code;

		$rec = 'day payout : ('.$mb_deposit.") + ".$benefit; 
		$rec_adm = $mb_deposit." * ".$bonus_rate." = ".$benefit;
		
		
		$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from {$g5['bonus']} where 1=1 and mb_id='".$mrow['mb_id']."'");
		save_benefit( $mrow['mb_id'], $mrow['mb_no'], $mrow['mb_name'], $allowance_name, $benefit, $rec, $rec_adm, $mb_level,$grade,$mb_balance,$mb_deposit);
	}
}

function save_benefit( $mb_id, $mb_no, $mb_name, $allowance_name, $benefit, $rec, $rec_adm, $mb_level,$grade,$mb_balance,$mb_deposit){
	global $g5,$bonus_day, $bonus_limit,$debug,$bonus_rate;

	if($mb_level < 10 ){
	
		//$benefit_limit = "update {$g5['member_table']} set mb_balance = round(mb_balance+ ".$benefit.",3), mb_v7_account = round(mb_v7_account+ ".$benefit."/".$v7_cost.",3) where mb_id = '".$mbid."';";
		echo "<span class='title'>". $mb_id."</span> ";

		$balance_limit = $bonus_limit * $mb_deposit; // 수당한계선
		$benefit_limit = $mb_balance + $benefit; // 수당합계


		if($benefit_limit > $balance_limit){
			$benefit_limit = $balance_limit;
			$rec_adm .= "(benefit overflow)";
		}

		$balance_up = "update {$g5['member_table']} set mb_balance = {$benefit_limit} where mb_id = '".$mb_id."';";
		
		if($debug){
			//print_R($balance_up);
		}else{
			sql_query($balance_up);
		}

		$bonus_sql = " insert `{$g5['bonus']}` set day='".$bonus_day."'";
		$bonus_sql .= " ,mb_no			= ".$mb_no;
		$bonus_sql .= " ,mb_id			= '".$mb_id."'";
		$bonus_sql .= " ,mb_name		= '".$mb_name."'";
		$bonus_sql .= " ,mb_level      = ".$mb_level;
		$bonus_sql .= " ,grade      = ".$grade;
		$bonus_sql .= " ,allowance_name	= '".$allowance_name."'";
		$bonus_sql .= " ,benefit		=  ".$benefit;	
		$bonus_sql .= " ,rec			= '".$rec."'";
		$bonus_sql .= " ,rec_adm		= '".$rec_adm."'";
		$bonus_sql .= " ,origin_balance		= '".$mb_balance."'";
		$bonus_sql .= " ,origin_deposit	= '".$mb_deposit."'";
		$bonus_sql .= " ,datetime		= '".date("Y-m-d H:i:s")."'";
		
		// 디버그 로그
		if($debug){
			echo "<code>";
			print_R($bonus_sql);
			echo "</code>";
		}else{
			sql_query($bonus_sql);
		}

		// 디버그 로그
		if($debug){
			echo "<code>";
			echo "현재수당 : ".$mb_balance."  | 수당한계 :". $balance_limit;
			echo "</code><br>";
		}

		// 수당기록 로그 
		echo "<span>".$mb_deposit."(금일예치금) * ".$bonus_rate. " = </span><span class='blue'>+". $benefit."</span> ";
		if($benefit_limit == $balance_limit){
			echo "<span class='red'>( limit :: ".$benefit_limit.")</span>"; 
		}
		echo "<br>";
		
	}

	//echo $bonus_sql;
	echo "<br>";
}


//산하매출기록 초기화
function delete_sales(){

	$sql_sales_del = " TRUNCATE table recom_bonus_today ";
		sql_query($sql_sales_del);
	
	$sql_sales_del = " TRUNCATE table recom_bonus_week";
		sql_query($sql_sales_del);
	
	$sql_sales_del = " TRUNCATE table recom_bonus_noo";
		sql_query($sql_sales_del);
	
	/* // 바이너리 
	$sql_sales_del = " TRUNCATE table brecom_bonus_bnoo";
		sql_query($sql_sales_del); 
	$sql_sales_del = " TRUNCATE table brecom_bonus_bthirty";
		sql_query($sql_sales_del);
	$sql_sales_del = " TRUNCATE table brecom_bonus_btoday";
		sql_query($sql_sales_del); 
	*/
	echo "<br>"."매출기록 초기화"."<br>";
}            


//산하 매출 기록 
function habu_sales_calc($gubun, $recom, $deep){

	global $bonus_day,$week_frdate,$week_todate,$debug;
	$deep++; // 대수

	//$od_time = "date_format(od_time,'%Y-%m-%d')";
	
	
	$res= sql_query("select * from g5_member where mb_".$gubun."recommend='".$recom."' ");

	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
	
		$recom=$rrr['mb_id'];

		//누적매출
		$noo_search = " and od_date <='{$bonus_day}' ";
		$noo_sql ="select sum(od_cart_price)as hap from g5_shop_order where mb_id='{$recom}'".$noo_search;
		$sql1= sql_fetch($noo_sql);
		$noo+=$sql1['hap'];
		
		//지난주 주간 매출
		$week_search = " and od_date BETWEEN '{$week_frdate}' AND '{$week_todate}'";
		$week_search_sql = "select sum(od_cart_price)as hap from g5_shop_order where mb_id='{$recom}'".$week_search;
		$sql2= sql_fetch($week_search_sql);
		$week+=$sql2['hap'];
		
		//일일매출
		$day_search = " and od_date ='$bonus_day' ";
		$day_search_sql = "select sum(od_cart_price)as hap from g5_shop_order where mb_id='{$recom}' ". $day_search;
		$sql3= sql_fetch($day_search_sql);
		$today+=$sql3['hap'];
		

		// 디버그 로그
		if($debug){
			echo "<span class=red> | noo: ".$noo." | week: ".$week." | today: ".$today."</span><br>" ;
		}

		list($noo_r,$week_r,$today_r)=habu_sales_calc($gubun, $recom, $deep);	 
		
		if($debug) echo "<br>".$recom;

		$noo_r+=$mysales;
		$week_r+=$mysales;
		$today_r+=$mysales;

		$noo+=$noo_r;
		$week+=$week_r;  
		$today+=$today_r; 


			if( ($noo>0) && ($noo_r>0)) {
				if($j==0){
					$rec=$noo;
				}else{
					$rec=$noo_r;	
				}
				
				if($j == count($rrr)) {
					$rec=$rec;
				}else{
					$rec=$noo_r;	
				}
				
				$inbnoo = "insert ".$gubun."recom_bonus_noo SET noo=".$rec.", mb_id='".$recom."',  day = '".$bonus_day."'";
				
				// 디버그 로그
				if($debug){
				echo " | <span class='blue'>noo: ".$rec."</span>";
				}else{
				sql_query($inbnoo);	
				}
			}
			
			if(($week>0) && ($week_r>0) ) {

				if($j==0){
					$rec=$week;
				}else{
					$rec=$week_r;		
				}
				
				if($j == count($rrr)) {
					$rec=$rec;
				}else{
					$rec=$week_r;	
				}
				
				$weekly = "insert ".$gubun."recom_bonus_week SET week=".$rec.", mb_id='".$recom."',  day = '".$bonus_day."'";
				// 디버그 로그
				if($debug){
					echo " | <span class='red'> week: ".$rec."</span>";
				}else{
					sql_query($weekly);
				}
			}
			
			
			if(($today>0) && ($today_r>0)) {
				if($j==0){
					$rec=$today;
				}else{
					$rec=$today_r;
				}
				
				if($j == count($rrr)) {
					$rec=$rec;
				}else{
					$rec=$today_r;
				}

				$intoday = "insert ".$gubun."recom_bonus_today SET today=".$rec.", mb_id='".$recom."',  day = '".$bonus_day."'";
				// 디버그 로그

				if($debug){
					echo " | <span> today: ".$rec."</span>";
				}else{
					sql_query($intoday);
				}
			
			}
		if($debug) echo "</code>";
	}
	return array($noo,$week,$today);
}
?>

<?include_once('./bonus_footer.php');?>

<? //로그 기록
if($debug){}else{
	$html = ob_get_contents();
    //ob_end_flush();
    $logfile = G5_PATH.'/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
	file_put_contents($logfile, ob_get_contents());
}
?>