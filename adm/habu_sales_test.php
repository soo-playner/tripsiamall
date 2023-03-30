<?php
$sub_menu = "600600";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'r');






$onestar=0;
$twostar=0;
$threestar=0;
$fourstar=0;
$fivestar=0;
$sixstar=0;

function habu_rank($recom){

	
    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' ");

    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  	
 			  $odsql= sql_fetch("select mb_level from g5_member where mb_id='".$recom."'");
			  
 			  switch($odsql['mb_level']){
				case 3:
					$onestar++;
					break;
				case 4:
					$twostar++;
					break;
				case 5:
					$threestar++;
					break;
				case 6:
					$fourstar++;
					break;
				case 6:
					$fivestar++;
					break;
				case 6:
					$sixstar++;
					break;
			  }

			list($one,$two,$three,$four,$five,$six)=habu_rank($recom);	 
			$onestar+=$one;   		
			$twostar+=$two; 
			$threestar+=$three; 
			$fourstar+=$four; 
			$fivestar+=$five; 
			$sixstar+=$six;

	} // for j	
	  return array($onestar,$twostar,$threestar,$fourstar,$fivestar,$sixstar);    
}  


//$yy= strtotime($day);
//$min30=date("Y-m-d", strtotime("-30 day", $yy));

$noo=0;
$mon=0;
$today=0;

function make_habu($gubun){
	$noo=0;
	$mon=0;
	$today=0;
	$gubun = strtolower($gubun);

	$sql= " delete from ".$gubun."noo"; // 
	sql_query($sql);

	$sql= " delete from ".$gubun."thirty"; // 
	sql_query($sql);

	$sql= " delete from ".$gubun."today"; //
	sql_query($sql);

	habu_sales_calc($gubun,'coolrunning',0); 
}

function habu_sales_calc($gubun, $recom, $deep){

	global $fr_date, $to_date;
	$deep++; // 대수

	$start_day = '2017-07-01';

	if ($to_date){
		$day       = $to_date;
	}else{
		$day    = '2018-07-01'; //  = date('Y-m-d');
	}
	$yy= strtotime($day);

	$min30=date("Y-m-d", strtotime("-30 day", $yy));
	
    $res= sql_query("select * from g5_member where mb_".$gubun."recommend='".$recom."' ");

    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		
			$recom=$rrr['mb_id'];  
			echo $recom.'<br>'; 

			$noo_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$start_day' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $noo_search");
			$noo+=$sql['hap'];

			echo "select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $noo_search".'<br>';
			

			$mon_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$min30' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $mon_search");
			$mon+=$sql['hap'];
			//echo $sql.'<br>';

			$day_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search");
			$today+=$sql['hap'];
			echo "select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search".'<br>';

			if($sql['hap']>0){echo "<br>".$sql['hap']."---select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search".'<br>';}
			  
			
			$mysql=sql_fetch("select (pv)as hap from g5_shop_order as o where o.mb_id='".$mbid."'");
			$mysales=$mysql['hap'];


			list($noo_r,$mon_r,$today_r)=habu_sales_calc($gubun, $recom, $deep);	 
				
				$noo_r+=$mysales;
				$mon_r+=$mysales;

				$noo+=$noo_r;
				$mon+=$mon_r;  
				$today+=$today_r; 

			if( ($noo>0) && ($noo_r>0)) {
				if($j==0){
					$rec=$noo;
				}else{
					$rec=$noo_r;	
				}
				//sql_query("insert ".$gubun."noo SET noo=".$rec." ,mb_id='".$recom."'");	
				echo "insert ".$gubun."noo SET noo=".$rec." ,mb_id='".$recom."'";
			}
			
			if(($mon>0) && ($mon_r>0) ) {
				if($j==0){
					$rec=$mon;
				}else{
					$rec=$mon_r;	
				}
				//sql_query("insert ".$gubun."thirty SET thirty=".$rec." ,mb_id='".$recom."'");
				echo "insert ".$gubun."thirty SET thirty=".$rec." ,mb_id='".$recom."'";
			}
			
			if(($today>0)&& ($todayyn>0)) {
				if($j==0){
					$rec=$today;
				}else{
					$rec=$today_r;	
				}
				//sql_query("insert ".$gubun."today SET today=".$rec." ,mb_id='".$recom."'");
				echo "insert ".$gubun."today SET today=".$rec." ,mb_id='".$recom."'";
			}



	} // for j	
	 return array($noo,$mon,$today);
}  

make_habu('b');


echo " 완료<br>";



?>

