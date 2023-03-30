<?php
$sub_menu = "600600";
include_once('./_common.php');


echo "//**************************************************//<br>";
echo "//<br>";
echo "//			내 하부의 누적, 30일, 하루 매출을 구하는 함수		<br>";
echo "//			하부만 가능하므로 내 매출은 별도 합산해야 함          <br>";
echo "//**************************************************//<br>";

$noo=0;
$mon=0;
$today=0;


echo "<table border='1'>";
function habu_sales_calc($recom, $deep){

	$deep++; // 대수

	$start_day='2018-01-01';
	$day=date('Y-m-d');
	$yy= strtotime($day);

	$min30=date("Y-m-d", strtotime("-30 day", $yy));
	
    $res= sql_query("select * from g5_member where mb_brecommend='".$recom."' ");

	$nooyn=0;
	$monyn=0;
	$todayyn=0;
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			
			  $org_recom = $recom;

			  $recom=$rrr['mb_id'];  
			 
			 
			  
			  $noo_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$start_day' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			  $sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $noo_search");
			  $noo+=$sql['hap'];
			  if ($sql['hap']){
				echo "<tr><td>$deep.</td><td>$org_recom</td><td>".$rrr['mb_id']."</td><td>".$sql['hap']."</td></tr>";
			  }			

			  $mon_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$min30' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			  $sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $mon_search");
			  $mon+=$sql['hap'];

			  $day_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')=='$day'";
			  $sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search");
			  $today+=$sql['hap'];


			list($noo_r,$mon_r,$today_r)=habu_sales_calc($recom, $deep);	 
			$noo+=$noo_r;  	
			$mon+=$mon_r;  
			$today+=$today_r; 
			

					if( ($noo>0) && ($nooyn==0)) {
						$nooyn=1;
						sql_query("insert noo SET noo=".$noo." ,mb_id='".$recom."'");	
						//echo("insert noo SET noo=".$noo." ,mb_id='".$recom."'<br>");
						//echo 'noo:'.$recom.'  '.$deep.'<br>';
					}
					
					if(($mon>0)&& ($monyn==0) ) {
						$monyn=1;
						sql_query("insert thirty SET thirty=".$mon." ,mb_id='".$recom."'");
						//echo("insert thirty SET thirty=".$mon." ,mb_id='".$recom."'<br>");
						//echo 'thirty:'.$recom.'  '.$deep.'-'.$olddeep.'<br>';
					}
					
					if(($today>0)&& ($todayyn==0)) {
						$todayyn=1;
						sql_query("insert today SET today=".$today." ,mb_id='".$recom."'");

					}

					

			


	} // for j	
	 return array($noo,$mon,$today);
}  


$sql= " delete from noo"; // 
sql_query($sql);

$sql= " delete from thirty"; // 
sql_query($sql);

$sql= " delete from today"; //
sql_query($sql);


habu_sales_calc('kking8004',0,0); 
echo "admin 하위의 모든 구성원의 각각 누적, 한30일, 하루 매출 구함 완료";


?>
</table>