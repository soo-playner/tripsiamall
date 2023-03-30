<?php
$sub_menu = "600900";
include_once('./_common.php');
include_once('./bonus_inc.php');

auth_check($auth[$sub_menu], 'r');

/* 
매출 통계자료 단독 실행파일 
데일리에 포함되어있음
*/

// ob_start();

// 설정로그 
if($debug){
    echo "<br><strong> 현재일 : ".$bonus_day."</strong> |  Week : <span class='red'>".$week_frdate."~".$week_todate."</span>";
    echo "<br>매출기록";
    echo "<br><br>";
    echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";
}
?>

<html><body>
<header>매출 기록 시작</header>    
<div>

<?
delete_sales();
echo "<br>"."매출기록 생성"."<br>";
habu_sales_calc('',$config['cf_admin'],0);

//산하매출기록 초기화

function delete_sales(){
    global $bonus_day;

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

<?
/* if($debug){}else{
    $html = ob_get_contents();
    //ob_end_flush();
    $logfile = G5_PATH.'/data/log/'.$code.'/'.$code.'_'.$bonus_day.'.html';
    fopen($logfile, "w");
    file_put_contents($logfile, ob_get_contents());
} */

?>