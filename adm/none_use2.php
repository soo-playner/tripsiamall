<?php

//$sub_menu = "600600";
include_once('/home/sdevftv/html/common.php'); //실서버용 경로


$sql_price = "select btc_cost from coin_cost";
$result = sql_query($sql_price);
$ret = sql_fetch_array($result);
echo $exchange_rate =  $ret['btc_cost'];


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

function save_benefit($day, $mbid, $mbname, $recom, $allowance_name, $sales_day, $habu_day_sales, $benefit, $rec_adm,$rec,$exchange_rate ){
	//수당을 비트로 환산 한다.
	$benefit_bit = round($benefit/$exchange_rate,8);
	//회원 잔고에 더해 준다.
	$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit_bit.",8)
	where mb_id = '".$mbid."';";
	sql_query($balance_up);
	$temp_sql1 = " insert soodang_pay set day='".$day."'";
	$temp_sql1 .= " ,mb_id		= '".$mbid."'";
	$temp_sql1 .= " ,mb_name		= '".$mbname."'";
	$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
	$temp_sql1 .= " ,allowance_name		= '".$allowance_name."'";
	//$temp_sql1 .= " ,day_sales		=  '".$sales_day."'";
	//$temp_sql1 .= " ,habu_day_sales 		=  '".$habu_day_sales."'";
	$temp_sql1 .= " ,benefit			=  ".$benefit_bit;
	$temp_sql1 .= " ,benefit_usd		=  '".($benefit)."'";
	$temp_sql1 .= " ,exchange_rate      =  ".$exchange_rate;
	$temp_sql1 .= " ,rec		= '".$rec."'";
	$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";
	sql_query($temp_sql1);
	//echo $temp_sql1.'<br>';
}

$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));

$benefitSql = "SELECT * from eos_soodang_set where immediate=2 order by partner_cnt desc, no";
$rrr = sql_query($benefitSql);

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

	if( ($row['sales_reset']>0) && ($row['cycle']>0)  && ($row['max_reset1']<>'')  && ($row['max_reset2']<>'') ){  $cond[$i]['limit_reset']=1;  }  //극점 사용여부
	if( ($row['partner_cnt']>0) && ($row['partner_cont']>0) ){$cond[$i]['mat']=1;}  // 메트릭스
	if(  ($row['source_in1']!=0) || ($row['source_in2']!=0) ){ $cond[$i]['bigsmall1']=1;  }// 대소실적조건1
	if(  ($row['source_in11']!=0) || ($row['source_in12']!=0) ){ $cond[$i]['bigsmall2']=1;  }// 대소실적조건12
	if(  ($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){ $cond[$i]['level1']=1; } //본인직급 
	if(  ($row['mb_level_cond11']!=0) || ($row['mb_level_cond12']!=0) ){ $cond[$i]['level2']=1; } //하부직급
	if(  ($row['history_cond1']!='') || ($row['history_cond2']!='') ){ $cond[$i]['history']=1;}  //대수 level
	if( $row['benefit_limit1']>0  ){$cond[$i]['bf_limit1']=1;}  //매출자보다 수당받을자의 매출이 같거나 큰가?
}


	$to_date =	date("Y-m-d",time() - 3600*24);
	$fr_date = 	date("Y-m-d",time() - 3600*24);
	$to_date =	'2018-04-19';
	$fr_date = 	'2018-04-19';
	

	$price=pv;
	$allowance_name='바이너리보너스';

// 직급이 최소 1스타(3) 이상이고 하부에 오늘 매출이 있는 사람만 
$sql = "SELECT m.mb_id, m.mb_name, m.mb_hp, m.mb_level, m.mb_recommend, m.mb_brecommend , s.allowance_name ,s.benefit_usd, s.benefit, s.benefit_level FROM g5_member as m, soodang_pay as s WHERE m.mb_id=s.mb_id and benefit_level>0 and s.day='$to_date'";
echo '<br>'.$sql;
$result = sql_query($sql);

//make_class();
echo $sql;
$rec='';

for ($i=0; $row=sql_fetch_array($result); $i++) {   
		$comp=$row['mb_id'];
		$pay=$row['benefit_usd'];
		//$benefit=$row['benefit_usd']/$row['benefit_level'];//기존 보너스 룰 : 바이너리 수당에 cycle당 수량 - 1사이클 금액을 나눈것.
		$benefit=$row['benefit_usd'];//신규 보너스 룰 : 바이너리 소실적을 넣어준 것을 가져 온다.
		$binary_firstname=$row['mb_name'];
		$binary_firstid=$comp;
		$history_cnt=0;
		$binary_fristlv = $row['mb_level'];

		while(  ($comp!='Coolrunning')  ){ 
			$sql = " SELECT mb_id, mb_name, mb_hp, mb_level, mb_recommend, mb_brecommend FROM g5_member WHERE mb_id= '".$comp."'";
			$recommend = sql_fetch($sql);
			$leg_success=0; // 메트릭스 성공찾기 클리어
			$mbid=$recommend['mb_id'];
			$mbname=$recommend['mb_name'];
			$mblevel=$recommend['mb_level'];
			if(($mb_name=='본사')  || ($mbid=='')  ) break;
				for ($i=0; $i<count($cond); $i++) {
					//위로 연속 올라가는 부분 추천인으로 올라갈지 바이너리추천인으로 올라갈지 결정
					if($cond[$i]['recom_kind']=='mb_recommend'){
							$recom=$recommend['mb_recommend'];
					}else{
							$recom=$recommend['mb_brecommend'];
				}
				//위$cond에서 설정한 내용에 대해 다시 변수설정하여 트리거 걸릴지 말지 설정
				if($cond[$i]['level1']=='1'){ $temp_cond_level1=1; } else {$temp_cond_level1=0;} //본인직급					
				if($cond[$i]['history']=='1'){ $temp_cond_history=1; } else {$temp_cond_history=0;} //대수조건									
				$temp_sql1 = '';					
				if($cond[$i]['per']!=''){ // 수당 퍼센트가 비어있으면 패스~
								  //******   본인직급 조건이 있다면  성공여부 기록 
								if(   ($cond[$i]['mb_level_cond1']=='==')   ){

										if($mblevel==$cond[$i]['mb_level_in1']){
											$temp_cond_level1=0;
										}else{
											$temp_cond_level1=1;
										}

								}else if(  ($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='')  ){

										if($mblevel>=$cond[$i]['mb_level_in1']){
											
											$temp_cond_level1=0;
										}else{
											$temp_cond_level1=1;
										}

								}else if(  ($cond[$i]['mb_level_cond1']=='') && ($cond[$i]['mb_level_cond2']=='<=')  ){

										if($mblevel<=$cond[$i]['mb_level_in2']){
											
											$temp_cond_level1=0;
										}else{
											$temp_cond_level1=1;
										}

								}else if(  ($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='<=')  ){


										if(  ($mblevel>=$cond[$i]['mb_level_in1']) && ($mblevel<=$cond[$i]['mb_level_in2'])  ){
							
											$temp_cond_level1=0;
										}else{
											$temp_cond_level1=1;
										}
								}
								// 바이너리매칭 대수조건
								if(($cond[$i]['history_cond1']=='==')   ){

										if(($history_cnt)==$cond[$i]['history_in1']){
											$bm=0;
										}else{
											$bm=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond1']=='')  ){
										if(($history_cnt)>=$cond[$i]['history_in1']){
											$bm=0;
										}else{
											$bm=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='') && ($cond[$i]['history_cond1']=='<=')  ){

										if(($history_cnt)<=$cond[$i]['history_in1']){
											$bm=0;
										}else{
											$bm=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond2']=='<=')  ){
										if( (($history_cnt)>=$cond[$i]['history_in1']) && ($history_cnt<=$cond[$i]['history_in2']) ){
											$bm=0;
										}else{
											$bm=1;
										}
								
								}


								if( ($bm==0) && ($temp_cond_level1==0)  ){

									
								
									if( ($pay>0) && ($cond[$i]['base_source']=='Cycle') ) {
									
										echo $mbname.'('.$mbid.'): 대수'.$history_cnt.'=='.$cond[$i]['history_in1'].'   직급 : '.$cond[$i]['mb_level_in1'].'=='.$mblevel.'  '.$bm,'---'.$temp_cond_level1.' /  '.$binary_firstname.'('.$binary_firstid.') 로부터 '.$cond[$i]['allowance_name'].' 발생<br>';

										$rec_adm=$binary_firstname.'('.$binary_firstid.') 로부터 '.$cond[$i]['allowance_name'].' 발생, 내 직급: '.$mb_level.', 대수: '.($history_cnt).' Cycle 수: '.$benefit*$cond[$i]['per']/100;

										$rec=$cond[$i]['allowance_name'].' from '.$binary_firstid.'( level '.$history_cnt.')';
										//echo $mbname.'('.$mbid.'): '.$rec.'<br>';

										save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], ($benefit*$cond[$i]['per']/100) , $cond[$i]['allowance_name'].' '.$rec_adm, $rec,$exchange_rate);
									}
								}

						}// 수당per 가 있으면 
					} // for
					
					//echo $rec;
			$rec='';
			$comp=$recom;
			$history_cnt++;
		} // while
		$rec='';	
	} //for

//alert('수당계산이 완료되었습니다');

$update_end = "update pinna_soodang_status set run_status = 'X'";
sql_query($update_end);
?>

