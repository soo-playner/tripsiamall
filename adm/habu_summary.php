<?php



include_once('/home/sdevftv/html/common.php'); //실서버용 경로



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

	habu_sales_calc($gubun,'Coolrunning',0); 
}

function habu_sales_calc($gubun, $recom, $deep){

	global $fr_date, $to_date;
	$deep++; // 대수

	$start_day = '2018-08-23';

	if ($to_date){
		$day       = $to_date;
	}else{
		$day    = '2018-07-06'; //  = date('Y-m-d');
	}
	$yy= strtotime($day);

	$min30=date("Y-m-d", strtotime("-30 day", $yy));
	echo "select * from g5_member where mb_".$gubun."recommend='".$recom."' ";
    $res= sql_query("select * from g5_member where mb_".$gubun."recommend='".$recom."' ");

    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		
			$recom=$rrr['mb_id'];  
			//echo $recom.'<br>'; 

			
			$noo_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$start_day' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $noo_search");
			$noo+=$sql['hap'];

		
			

			$mon_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$min30' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $mon_search");
			$mon+=$sql['hap'];
			
			

			$day_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search");
			$today+=$sql['hap'];
			
			/*
			if($sql['hap']>0){echo "<br>".$sql['hap']."---select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search".'<br>';
			}
			*/
			  
			
			$mysql=sql_fetch("select (pv)as hap from g5_shop_order as o where o.mb_id='".$mbid."'");
			$mysales=$mysql['hap'];
			


			list($noo_r,$mon_r,$today_r)=habu_sales_calc($gubun, $recom, $deep);	 
				
				$noo_r+=$mysales;
				$mon_r+=$mysales;
				$today_r+=$mysales;


				$noo+=$noo_r;
				$mon+=$mon_r;  
				$today+=$today_r; 

					if( ($noo>0) && ($noo_r>0)) {
					if($j==0){
						$rec=$noo;
					}else{
						$rec=$noo_r;	
					}
					$inbnoo = "insert ".$gubun."noo2 SET noo=".$rec.", mb_id='".$recom."',  day = '".$to_date."'";
					sql_query($inbnoo);	
					
				}
				
				if(($mon>0) && ($mon_r>0) ) {
					if($j==0){
						$rec=$mon;
					}else{
						$rec=$mon_r;	
					}
					$inthirty = "insert ".$gubun."thirty2 SET thirty=".$rec.", mb_id='".$recom."',  day = '".$to_date."'";
					sql_query($inthirty);
					
				}
				
				if(($today>0)&& ($today_r>0)) {
					if($j==0){
						$rec=$today;
					}else{
						$rec=$today_r;
					}
					$intoday = "insert ".$gubun."today2 SET today=".$rec.", mb_id='".$recom."',  day = '".$to_date."'";
					sql_query($intoday);
					
				}


	} // for j	
	 return array($noo,$mon,$today);
}  




 
// 새로운 공휴일 처리함수
function plus_day($day,$add)
{


		if($add<7 && $add>0){ $add+=2; }

		else if($add<=14 && $add>7){ $add+=4;}

		else if($add<=21 && $add>14){ $add+=6;  }

		else if($add<=28 && $add>21){ $add+=8;}

		else if($add<=31 && $add>28){ $add+=10;  }




		$year=date(Y);
		$holiday = array(date("Y")."-01-01",);
		$sql = " select * from holiday where YEAR(h_day)=$year";
		$result = sql_query($sql);


		$il= strtotime($day);
		$nal=date("Y-m-d", strtotime("+$add day", $il));

		   for ($i=0; $row=sql_fetch_array($result); $i++) {
				
				
				  if($nal==$row['h_day']){
					$il=strtotime($nal);
					$nal=date("Y-m-d", strtotime("+1 day", $il));
					$add++;
				  }
			}    
			

		 $exdate = explode("-", $day); 

		 $exyear = $exdate[0]; 
		 $exmonth = $exdate[1]; 
		 $exday = $exdate[2]; 
		 
		 $exweek = array(0=>'일',1=>'월',2=>'화',3=>'수',4=>'목',5=>'금',6=>'토'); 

		 



		  $exweek2 = date("w",mktime(0,0,0,(int) $exmonth,(int) $exday+$add,(int) $exyear)); 
		 if ($exweek2 == 0) {
		   $add++;
		 }

		// 토요일 이라면 월요일로...
		if ($exweek2 == 6) { 
		   $add++;
			$add++;
		 }

   return date("Y-m-d", mktime(0,0,0,(int) $exmonth,(int) $exday+$add,(int) $exyear));
 

}




function self_sales($recom){

    $res= sql_fetch("select sum(pv)as hap from g5_shop_order as o where o.mb_id='".$recom."'");
	echo "select sum(pv)as hap from g5_shop_order as o where o.mb_id='".$recom."'". $res['hap'].'<br>';    
	return $res['hap'];    
	
} 



function my_bchild_sub($mb_id){

	$hap2=0;

	//자기매출제외
	//$hap=self_sales($mb_id); //먼저 자기매출

    $res= sql_query("select mb_id from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		$hap2+=self_sales($rrr['mb_id']); // 하부매출을 구한다
		$hap2 = $hap2+ my_bchild_sub($rrr['mb_id']);		
	} 	
	return $hap2;
} 

function my_bchild_hap($mb_id){

	$cnt = 0;
	$hap = 0;
	$bcnt =0;
    $res= sql_query("select count(mb_id) as cnt from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	$ret = sql_fetch_array($res);
	$cnt = $ret['cnt']; 

    $res2= sql_query("select count(mb_id) as bcnt from g5_member where mb_brecommend='".$mb_id."' order by mb_no"); 
	$ret2 = sql_fetch_array($res2);
	$cnt2 = $ret2['bcnt']; // 하부매출을 구한다
		
	if($cnt >= 2 and $cnt2==2){
		$hap = my_bchild_sub($mb_id);
	}
	else{
		return 0;
	}
	return $hap;
} 


function clear_all_benefit_mem(){
	for ($i=0; $row=sql_fetch_array($rrr); $i++) {   
		$cond[$i]['price_cond']='';
		$cond[$i]['source']='';
		$cond[$i]['source_cond1']='';
		$cond[$i]['source_cond2']='';
		$cond[$i]['source_in1']='';
		$cond[$i]['source_in2']='';
		$cond[$i]['mb_level_cond1']='';
		$cond[$i]['mb_level_cond2']='';
		$cond[$i]['mb_level_in1']='';
		$cond[$i]['mb_level_in2']='';
		$cond[$i]['partner_cnt']='';
		$cond[$i]['partner_cont']='';
		$cond[$i]['history_cnt']='';
		$cond[$i]['history_cond1']='';
		$cond[$i]['history_cond2']='';
		$cond[$i]['history_in1']='';
		$cond[$i]['history_in2']='';
		$cond[$i]['base_source']='';
		$cond[$i]['per']='';
		$cond[$i]['allowance_name']='';
		$cond[$i]['benefit_limit1']='';
		$cond[$i]['benefit']=0;
		$cond[$i]['source11']='';
		$cond[$i]['source_cond11']='';
		$cond[$i]['source_cond12']='';
		$cond[$i]['source_in11']='';
		$cond[$i]['source_in12']='';
		$cond[$i]['iwolyn']='';
		$cond[$i]['mb_level_in11']='';
		$cond[$i]['mb_level_cond11']='';
		$cond[$i]['mb_level_cond12']='';
		$cond[$i]['cycle']='';
		$cond[$i]['sales_reset']='';
		$cond[$i]['max_reset1']='';
		$cond[$i]['max_reset2']='';
		$cond[$i]['recom_kind']='';
		$cond[$i]['bigsmall1']='';
		$cond[$i]['bigsmall2']='';
		$cond[$i]['level1']='';
		$cond[$i]['level2']='';
		$cond[$i]['andor']='';
		$cond[$i]['mat']='';
		$cond[$i]['history']='';
		$cond[$i]['bf_limit1']='';
	}
}

function clear_benefit_mem(){
	for ($i=0; $row=sql_fetch_array($rrr); $i++) {   
		$cond[$i]['bigsmall1']='';
		$cond[$i]['bigsmall2']='';
		$cond[$i]['level1']='';
		$cond[$i]['level2']='';
		$cond[$i]['mat']='';
		$cond[$i]['history']='';
	}
}

/**** 수당이 있다면 함께 DB에 저장 한다.
function iwol_process($to_date,$mbid, $mb_name, $kind, $pv, $note){
	$temp_sql1 = " insert iwol set iwolday='".$to_date."'";
	$temp_sql1 .= " ,mb_id		= '".$mbid."'";
	$temp_sql1 .= " ,mb_name		= '".$mbname."'";
	$temp_sql1 .= " ,kind		= '".$kind."'";
	$temp_sql1 .= " ,pv		= '".$pv."'";
	$temp_sql1 .= " ,note		= '".$note."'";
	//sql_query($temp_sql1);
}
*/

$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));

$benefit = "SELECT * from soodang_set where immediate=1 order by partner_cnt desc, no ";
$rrr = sql_query($benefit);

for ($i=0; $row=sql_fetch_array($rrr); $i++) {   
	$cond[$i]['price_cond']=$row['price_cond'];
	$cond[$i]['source']=$row['source'];
	$cond[$i]['source_cond1']=$row['source_cond1'];
	$cond[$i]['source_cond2']=$row['source_cond2'];
	$cond[$i]['source_in1']=$row['source_in1'];
	$cond[$i]['source_in2']=$row['source_in2'];
	$cond[$i]['mb_level_cond1']=$row['mb_level_cond1'];
	$cond[$i]['mb_level_cond2']=$row['mb_level_cond2'];
	$cond[$i]['mb_level_in1']=$row['mb_level_in1'];
	$cond[$i]['mb_level_in2']=$row['mb_level_in2'];
	$cond[$i]['partner_cnt']=$row['partner_cnt'];
	$cond[$i]['partner_cont']=$row['partner_cont'];
	$cond[$i]['history_cnt']=$row['history_cnt'];
	$cond[$i]['history_cond1']=$row['history_cond1'];
	$cond[$i]['history_cond2']=$row['history_cond2'];
	$cond[$i]['history_in1']=$row['history_in1'];
	$cond[$i]['history_in2']=$row['history_in2'];
	$cond[$i]['base_source']=$row['base_source'];
	$cond[$i]['immediate']=$row['immediate'];
	$cond[$i]['per']=$row['per'];
	$cond[$i]['andor']=$row['andor'];
	$cond[$i]['allowance_name']=$row['allowance_name'];
	$cond[$i]['benefit_limit1']=$row['benefit_limit1'];
	$cond[$i]['source11']=$row['source11'];
	$cond[$i]['source_cond11']=$row['source_cond11'];
	$cond[$i]['source_cond12']=$row['source_cond12'];
	$cond[$i]['source_in11']=$row['source_in11'];
	$cond[$i]['source_in12']=$row['source_in12'];
	$cond[$i]['sales_reset']=$row['sales_reset'];
	$cond[$i]['iwolyn']=$row['iwolyn'];
	$cond[$i]['max_reset1']=$row['max_reset1'];
	$cond[$i]['max_reset2']=$row['max_reset2'];
	$cond[$i]['cycle']=$row['cycle'];
	$cond[$i]['mb_level_in11']=$row['mb_level_in11'];
	$cond[$i]['mb_level_cond11']=$row['mb_level_cond11'];
	$cond[$i]['mb_level_cond12']=$row['mb_level_cond12'];
	$cond[$i]['recom_kind']=$row['recom_kind'];

	if(($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){ $cond[$i]['level1']=1; } //본인직급 
	if(  ($row['mb_level_cond11']!=0) || ($row['mb_level_cond12']!=0) ){ $cond[$i]['level2']=1; } //하부직급
	if(  ($row['history_cond1']!='') || ($row['history_cond2']!='') ){ $cond[$i]['history']=1;}  //대수 level
	if( $row['benefit_limit1']>0  ){$cond[$i]['bf_limit1']=1;}  //매출자보다 수당받을자의 매출이 같거나 큰가?
}

/*$getstat = "select * from pinna_soodang_status";
$run_sql = sql_query($getstat);
$row = sql_fetch_array($run_sql);
$prev_day = $row['day'];

if($row['run_status']=='X' && $prev_day!='2018-10-12'){
*/
//	$target_day = strtotime("$prev_day + 1 days"); 

	$to_date =	'2018-10-15';//date("Y-m-d", $target_day);
	$fr_date = 	'2018-10-15';//date("Y-m-d", $target_day);

//	$update_end = "update pinna_soodang_status set run_status = 'Y', day = '".$to_date."'";
//	sql_query($update_end);


make_habu(''); // 누적,30일, 하루매출을 바이너리로 구함
//}
//$update_end = "update pinna_soodang_status set run_status = 'X'";
//sql_query($update_end);

//alert('수당계산이 완료되었습니다');

?>

