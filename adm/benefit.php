<?php
$sub_menu = "600600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');




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
		$cond[$i]['bigsmall']='';
		$cond[$i]['level']='';
		$cond[$i]['andor']='';
		$cond[$i]['mat']='';
		$cond[$i]['history']='';
		$cond[$i]['benefit']=0;
	}
}


function clear_benefit_mem(){
	for ($i=0; $row=sql_fetch_array($rrr); $i++) {   

		$cond[$i]['bigsmall']='';
		$cond[$i]['level']='';
		$cond[$i]['mat']='';
		$cond[$i]['history']='';
	}
}



$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level'=>'','bigsmall'=>'','history'=>'','benefit'=>''));

$benefit = "SELECT * from soodang_set where immediate=1 order by partner_cnt desc, no";
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
	$cond[$i]['per']=$row['per'];
	$cond[$i]['andor']=$row['andor'];
	$cond[$i]['allowance_name']=$row['allowance_name'];

	if($row['partner_cnt']>0){$cond[$i]['mat']=1;} else {$cond[$i]['mat']=0;} // 메트릭스
	
	if(  ($row['source_in1']!=0) || ($row['source_in1']!=0) ){ $cond[$i]['bigsmall']=1;  }else {$cond[$i]['bigsmall']=0;} // 대소실적

	if(  ($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){ $cond[$i]['level']=1; }else {$cond[$i]['level']=0;} //직급수당

	if(  ($row['history_cond1']!='') || ($row['history_cond2']!='') ){ $cond[$i]['history']=1;} else{  $cond[$i]['history']=0;} //추천수당


}





/*
function loopdeepod($recom, $partner_cnt,$partner_cont){

	$mat_deep=0;

	while( $recom!='admin' ){   

			 $res= sql_query("select mb_id, mat_cnt, from g5_member where mb_recommend='".$recom."' "); 

			 for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
				  $recom=$rrr['mb_id'];  	
				  if(   ($recom=='admin') || ($recom=='')  ) break;

				  if($partner_cnt<=$rrr['mat_cnt']){
					$mat_leg++;
				  } 

				  if($partner_cnt<=$mat_leg){
					$mat_cnt[$mat_deep]['cnt']=$mat_leg;
				  }
			  }
	
			$mat_deep++;

	}
	    return array($odhap,$cnt);    
}  
*/


function loopdeep2($recom){
	$cnt=0;
	$sql="select count(*) as hap from g5_member where mb_recommend='".$recom."' ";
	$res= sql_query($sql);
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $cnt=$rrr['hap'];
	} // for
	return $cnt;
}   


function loopdeepod($recom, $deep ,$partner_cnt,$partner_cont){
	$deep++;
    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' "); 

    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  	
			  if($recom=='admin') break;
	} // for j	
	     
	return array($odhap,$evhap);    
}  
    
 
$leg_success=0;

//******** 한줄로 몇대까지 성공하는지 확인할때... 
function loopdeep_one($recom, $deep ,$partner_cnt,$partner_cont, &$leg_success){
	
	//if(  ($leg_success==1) || ($leg_success==2)){ return $leg_succes;}
	$deep++;

	$sql= " delete from temp_mat_cnt"; 
	sql_query($sql);

    $res= sql_query("select * from g5_member where mb_recommend='".$recom."'  ");  //and mb_leave_date='' 
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  
 			  	  if($recom=='admin') break;
 			  	   
						$rcnt=loopdeep2($rrr['mb_id']);	
						//echo $recom.'==='.$deep.'==='.$rcnt.'<br/>';
						if(  ($rcnt<$partner_cnt)  ){				
							if($partner_cont>$deep) // 현재 deep대수가 기준(partner_cont)대수를 초과하지 않은 상태에서 하위 레그가 기준레그(partner_cnt) 갯수에 못 미치면 레그를 못맞춘 것임
							{		
								/*echo $leg_success.'='.$recom.'==='.$deep.'==='.$rcnt.'실퍠<br/>';
								if($leg_success!=2){
									$leg_success=1;
								}
								return $leg_success;
								*/

								
							}else{

								$leg_success=2;

							}
						}else{

							if($partner_cont<=$deep) 
							{		
								//echo $leg_success.'='.$recom.'==='.$deep.'==='.$rcnt.'성공<br/>';
								$leg_success=2;
								return $leg_success;
							}	
						}

						if($partner_cont>=$deep){					
							$leg_success=loopdeep_one($recom, $deep ,$partner_cnt,$partner_cont,$leg_success);
						}else{
							
							return $leg_success;
						}	   		
	 } // for j	

	return $leg_success;
	   
}


//******** 2레그 이상 몇대까지 성공하는지 확인할때... 
function loopdeep($recom, $deep ,$partner_cnt,$partner_cont, &$leg_success){
	
	//if(  ($leg_success==1) || ($leg_success==2)){ return $leg_succes;}
	$deep++;

	$sql= " delete from temp_mat_cnt"; 
	sql_query($sql);

    $res= sql_query("select * from g5_member where mb_recommend='".$recom."'  ");  //and mb_leave_date='' 
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  
 			  	  if($recom=='admin') break;
 			  	   
						$rcnt=loopdeep2($rrr['mb_id']);	
						
						if(  ($rcnt<$partner_cnt)  ){				
							
							if($partner_cont>=$deep)
							{		
								//echo $leg_success.'='.$recom.'==='.$deep.'==='.$rcnt.'실퍠<br/>';

								$leg_success=1;

								return $leg_success;
								
							}
						}

						if($partner_cont>=$deep){					
							$leg_success=loopdeep($recom, $deep ,$partner_cnt,$partner_cont,$leg_success);
						}else{
							//$leg_success=2;
							return $leg_success;
						}	   		
	 } // for j	

	return $leg_success;
	   
}





//$save_noo_mon 변수는 누적 및 월합계 구해서 soodang_pay 에 넣어줌
//$save_benefit 은 금월의 각 수당구해서 soodang_pay 넣어줌  


// 테이블에 매출이 있었던 회원 찾아서 누적및 월 합계 구해서 넣기 위해 기존 테이블을 비워줌
if($save_noo_mon){ 
	$sql= " delete from soodang_pay"; 
	sql_query($sql);
}














//$fr_date="2015-05-01";
//$to_date="2015-05-31";

if(!$fr_date){ 
	$fr_date=date('Y-m-01');
}
if(!$to_date){ 
	$to_date=date('Y-m-31');
}

	$to_date=date('Y-m-31');
	$day=$to_date;
	$ym=substr($fr_date,0,7);




	if(   $price=='pv') {
		// PV가로
		$price_cond=", SUM(pv) AS hap";

	} else if(   $price=='bv') {
		//BV가로 계산
		$price_cond=", SUM(bv) AS hap";

	}else{
		// 판매가로 
		$price_cond=",SUM(od_receipt_price +od_receipt_cash) AS hap";
	}




$sql_common = " FROM g5_shop_order AS o, g5_member AS m ";

$sql_search=" WHERE o.mb_id=m.mb_id AND o.mb_id=m.mb_id";
$sql_member=" AND m.mb_id='".$mb_id."'";

$searchdate=" AND DATE_FORMAT(o.od_receipt_time,'%Y-%m')='".$ym."' GROUP BY DATE_FORMAT(o.od_receipt_time,'%Y-%m') ";

$searchdate_noo=" AND DATE_FORMAT(o.od_receipt_time,'%Y-%m')>='".$noo_start_day."'";

$sql_mgroup='GROUP BY m.mb_id';
$sql_orderby=' order by od_receipt_time asc';

$sql = "SELECT SUBSTRING(o.od_receipt_time,1,10) AS od_receipt_time, m.mb_id, m.mb_name,m.mb_5, m.mb_recommend, m.mb_hp 
			$price_cond 
            {$sql_common}
            {$sql_search}
			{$searchdate_noo}
            {$sql_mgroup}
";
$result = sql_query($sql);



for ($i=0; $row=sql_fetch_array($result); $i++) {   
		




		$comp=$row['mb_id'];
		$noohap=$row['hap'];
		$history_cnt=0;
		
	

		$recommend=0;


		//break;


		//******* 금월매출 구함
		$sql_member=" AND m.mb_id='".$comp."'";
		$sql = "SELECT m.mb_id $price_cond
					{$sql_common}
					{$sql_search}
					{$sql_member}	
					{$searchdate}
					";
		$mysqles = sql_fetch($sql);

		$my_month_sales=$mysqles['hap'];

		$rec='';


		//****************

		while(  ($comp!='admin')  ){   

			$sql = " SELECT mb_id, mb_name, mb_level, mb_recommend FROM g5_member WHERE mb_id= '".$comp."'";
			$recommend = sql_fetch($sql);

				$leg_success=0; // 메트릭스 성공찾기 클리어
				$mbid=$recommend['mb_id'];
				$mbname=$recommend['mb_name'];
				$mblevel=$recommend['mb_level'];
				$recom=$recommend['mb_recommend'];

				if(   ($mb_name=='본사')  || ($mbid=='')  ) break;

				// 누적 및 금월 합을 구해준다. 대 소실적 비교 등에서 사용함
				$sql = " SELECT mb_id, sum(accu_my_sales)as accu_my_sales, sum(mon_my_sales) as mon_my_sales, sum(accu_habu_sum) as accu_habu_sum, sum(mon_habu_sum) as mon_habu_sum FROM soodang_pay WHERE mb_id= '".$comp."' group by mb_id";

				$yn = sql_fetch($sql);

				if($yn['mb_id']==''){  // soodang_pay 없는 회원이면 만들어서 넣어라
					$sql2 = " insert into soodang_pay set day='".$day."',mb_id= '".$mbid."',mb_name= '".$mbname."',mb_recommend='".$recom."'";
					sql_query($sql2);	
				}	

					

		
					if(  ($my_month_sales>0)  ) {  // 금월 매출이 있는 사람만 계산 나머지는 누적 매출만 
						

							for ($i=0; $i<=count($cond); $i++) {   

									if(  ($cond[$i]['base_source']=='누적 대실적') || ($cond[$i]['base_source']=='누적 소실적') || ($cond[$i]['base_source']=='금월 대실적') || ($cond[$i]['base_source']=='금월 소실적') || ($cond[$i]['source_in1']>0) || ($cond[$i]['source_in2']>0) ){

											 $sql = " SELECT mb_id, sum(accu_my_sales)as accu_my_sales, sum(mon_my_sales) as mon_my_sales, sum(accu_habu_sum) as accu_habu_sum, sum(mon_habu_sum) as mon_habu_sum FROM soodang_pay WHERE mb_recommend= '".$comp."' group by mb_id";

											 $tempnoobig=0;		//누적대실적
											 $tempnoosmall=0;		//누적소실적
											 $tempmonbig=0;		//금월대실적
											 $tempmonsmall=0;		//금월소실적

											 $sales = sql_query($sql);	
											 for ($u=0; $sss=sql_fetch_array($sales); $u++) {   
												
												if(  ($sss['accu_my_sales']+$sss['accu_habu_sum'])>=$tempnoobig ){
													$tempnoobig=($sss['accu_my_sales']+$sss['accu_habu_sum']);
												}
												if(  ($sss['accu_my_sales']+$sss['accu_habu_sum'])<=$tempnoobig ){
													$tempnoosmall=($sss['accu_my_sales']+$sss['accu_habu_sum']);
												}
												if(  ($sss['mon_my_sales']+$sss['mon_habu_sum'])>=$tempnoobig ){
													$tempmonbig=($sss['mon_my_sales']+$sss['mon_habu_sum']);
												}
												if(  ($sss['mon_my_sales']+$sss['mon_habu_sum'])<=$tempnoobig ){
													$tempmonsmall=($sss['mon_my_sales']+$sss['mon_habu_sum']);
												}

											 } // for



									}//if(  ($cond[$i]['base_source']==


								if($cond[$i]['mat']=='1'){ $temp_cond_mat=1; } else {$temp_cond_mat=0;} // 메트릭스
								if($cond[$i]['bigsmall']=='1'){ $temp_cond_bigsmall=1; } else {$temp_cond_bigsmall=0;} //대소실적
								if($cond[$i]['level']=='1'){ $temp_cond_level=1; } else {$temp_cond_level=0;} //직급수당
								if($cond[$i]['history']=='1'){ $temp_cond_history=1; } else {$temp_cond_history=0;} //추천수당

									
								$temp_sql1 = '';
								

								if($cond[$i]['per']!=''){ // 수당 퍼센트가 비어있으면 패스~


										//********   메티릭스 조건
										 if($cond[$i]['partner_cnt']>0){

												$deep_chk=array();

												

												$mycnt=loopdeep2($comp);
												
												
												if(($mycnt>=$cond[$i]['partner_cnt']) && ($mycnt!=0) ){  // 최초 내가 기준 >= $partner_cnt 한가?

														//$leg_success= loopdeep($comp, $deep ,1, 3,&$leg_success);
														$leg_success= loopdeep($comp, $deep ,$cond[$i]['partner_cnt'], $cond[$i]['partner_cont'],&$leg_success);    // partner_cont는 -1 해줘야 함. 즉, 4는 

														if($leg_success==1){
															$temp_cond_mat=1;
														}else{

															if($comp!=''){
																echo  $comp.': 성공<br/>';
																$temp_cond_mat=0;
															}


														}

												}//if(($mycnt>=$partner_cnt) && ($mycnt!=0) ){ 
										 } // if




										  //******   직급 조건이 있다면  성공여부 기록 
										if(   ($cond[$i]['mb_level_cond1']=='==')   ){

												if($mblevel==$cond[$i]['mb_level_in1']){
												
													$temp_cond_level=0;
												}else{
													$temp_cond_level=1;
												}

										}else if(  ($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='')  ){

												if($mblevel>=$cond[$i]['mb_level_in1']){
													
													$temp_cond_level=0;
												}else{
													$temp_cond_level=1;
												}

										}else if(  ($cond[$i]['mb_level_cond1']=='') && ($cond[$i]['mb_level_cond2']=='<=')  ){

												if($mblevel<=$cond[$i]['mb_level_in2']){
													
													$temp_cond_level=0;
												}else{
													$temp_cond_level=1;
												}

										}else if(  ($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='<=')  ){


												if(  ($mblevel>=$cond[$i]['mb_level_in1']) && ($mblevel<=$cond[$i]['mb_level_in2'])  ){
									
													$temp_cond_level=0;
												}else{
													$temp_cond_level=1;
												}
										}


										if(  ($cond[$i]['base_source']=='누적 대실적') || ($cond[$i]['base_source']=='누적 소실적') || ($cond[$i]['base_source']=='금월 대실적') || ($cond[$i]['base_source']=='금월 소실적') || ($cond[$i]['source_in1']>0) || ($cond[$i]['source_in2']>0) ){


											 switch(  $yn[$cond[$i]['source']]  )
											 {
												 case '누적 대실적':
													 $my_month_sales= $tempnoobig;

													 break;
												 case '누적 소실적':
													 $my_month_sales= $tempnoosmall;

													 break;
												 case '누적 하부실적':
													$my_month_sales= $yn['accu_habu_sum']; 	
													 break;
												 case '누적 자기실적':
													 $my_month_sales= $yn['accu_my_sales']; 	
													 break;
												 case '누적 하부실적+자기실적':
													 $my_month_sales= ($yn['accu_habu_sum']+$yn['accu_my_sales']); 	
													 break;
												 case '금월 대실적':
													 $my_month_sales= $tempmonbig;

													 break;
												 case '금월 소실적':
													 $my_month_sales= $tempmonsmall;

													 break;
												 case '금월 하부실적':
													 $my_month_sales= $yn['mon_habu_sum']; 	
													 break;
												 case '금월 자기실적':
													 $my_month_sales= $yn['mon_my_sales']; 	
													 break;
												 case '금월 하부실적+자기실적':
													 $my_month_sales= ($yn['mon_habu_sum']+$yn['mon_my_sales']); 	
													 break;

											 }
										}



										if(   ($cond[$i]['source_cond1']=='==')   ){

												if($my_month_sales==$cond[$i]['source_in1']){
													
													$temp_cond_bigsmall=0;
												}else{
													$temp_cond_bigsmall=1;
												}

										}else if(  ($cond[$i]['source_cond1']=='>=') && ($cond[$i]['source_cond2']=='')  ){
										
											
												if($my_month_sales>=$cond[$i]['source_in1']){
													
													$temp_cond_bigsmall=0;
												}else{
													$temp_cond_bigsmall=1;
												}

										}else if(  ($cond[$i]['source_cond1']=='') && ($cond[$i]['source_cond2']=='<=')  ){

												if($my_month_sales<=$cond[$i]['source_in2']){
													
													$temp_cond_bigsmall=0;
												}else{
													$temp_cond_bigsmall=1;
												}

										}else if(  ($cond[$i]['source_cond1']=='>=') && ($cond[$i]['source_cond2']=='<=')  ){
											

												if(  ($my_month_sales>=$cond[$i]['source_in1']) && ($my_month_sales<=$cond[$i]['source_in2'])  ){
													
													$temp_cond_bigsmall=0;
												}else{
													$temp_cond_bigsmall=1;
												}
										}



									  
									  //******   대수 조건이 있다면 계산하라 

										if(   ($cond[$i]['history_cond1']=='==')   ){

												if($history_cnt==$cond[$i]['history_in1']){
													
													$temp_cond_history=0;
												}else{
													$temp_cond_history=1;
												}

										}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond1']=='')  ){
												if($history_cnt>=$cond[$i]['history_in1']){

													$temp_cond_history=0;
												}else{
													$temp_cond_history=1;
												}

										}else if(  ($cond[$i]['history_cond1']=='') && ($cond[$i]['history_cond1']=='<=')  ){

												if($history_cnt<=$cond[$i]['history_in1']){

													$temp_cond_history=0;
												}else{
													$temp_cond_history=1;
												}

										}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond2']=='<=')  ){
												if( ($history_cnt>=$cond[$i]['history_in1']) && ($history_cnt<=$cond[$i]['history_in2']) ){

													$temp_cond_history=0;
												}else{
													$temp_cond_history=1;
												}
										}


										
										
										// ***** 걸린 조건을 모두 충족한다면 계산하라 
										if(   ($temp_cond_history==0)  &&  ($temp_cond_bigsmall==0)  &&  ($temp_cond_level==0)  &&  ($temp_cond_mat==0)   ){


												if($save_benefit==1){ // 저장하라면 


													 switch(  $yn[$cond[$i]['base_source']]  )
													 {
														 case '누적 대실적':
															 $my_month_sales= $tempnoobig;

															 break;
														 case '누적 소실적':
															 $my_month_sales= $tempnoosmall;

															 break;
														 case '누적 하부실적':
															$my_month_sales= $yn['accu_habu_sum']; 	
															 break;
														 case '누적 자기실적':
															 $my_month_sales= $yn['accu_my_sales']; 	
															 break;
														 case '누적 하부실적+자기실적':
															 $my_month_sales= ($yn['accu_habu_sum']+$yn['accu_my_sales']); 	
															 break;
														 case '금월 대실적':
															 $my_month_sales= $tempmonbig;

															 break;
														 case '금월 소실적':
															 $my_month_sales= $tempmonsmall;

															 break;
														 case '금월 하부실적':
															 $my_month_sales= $yn['mon_habu_sum']; 	
															 break;
														 case '금월 자기실적':
															 $my_month_sales= $yn['mon_my_sales']; 	
															 break;
														 case '금월 하부실적+자기실적':
															 $my_month_sales= ($yn['mon_habu_sum']+$yn['mon_my_sales']); 	
															 break;

													 }




													if($comp==$oldcomp){
														$benefit+=($my_month_sales)*($cond[$i]['per']/100);
														$benefit2=($my_month_sales)*($cond[$i]['per']/100);	// 설명 기록에는 누적없이 각각	기록하기 위해
													}else{
														$benefit=($my_month_sales)*($cond[$i]['per']/100);
														$benefit2=($my_month_sales)*($cond[$i]['per']/100);		
													}
							


														$rec.=$cond[$i]['allowance_name'].': '.$mbname.'('.$mbid.') - '.$history_cnt.'대 '.$my_month_sales.'*'.($cond[$i]['per']/100).'='.$benefit2.' / <br/>';


														   //**** 수당이 있다면 함께 DB에 저장 한다.

														$temp_sql1 = " update soodang_pay set day='".$day."'";
														$temp_sql1 .= " ,allowance_name		= '".$cond[$i]['allowance_name']."'";
														$temp_sql1 .= " ,andor		= '".$cond[$i]['andor']."'";
														$temp_sql1 .= " ,rec		= '".$rec."'";
														$temp_sql1 .= " ,benefit		=  '".($benefit)."'";
													    $temp_sql1 .= " where mb_id='".$comp."'";
														sql_query($temp_sql1);

														//echo '===='.$yn['benefit'].'---'.$temp_sql1.'<br/>';

														
																
														clear_benefit_mem(); // 계산 한 후 다음사람 조건을 위해 지워라~
						
												}//if($save_benefit==1){ /

												$oldcomp=$comp;

												

										}//if(   ($temp_cond_history==0)  &&  ($temp_cond_bigsmall==0) 


									

								}// 수당per 가 있으면 
							} // for
							
					} // if(  ($my_month_sales>0)  )
					
					echo $rec;
					$rec='';
			
					

					if($save_noo_mon){ // 저장하라면 
								//********** 누적필드는 최초부터 현재까지 금액을 넣고 금월필드는 금월매출만 올라가면서 넣는다 

									$sql3 = " update soodang_pay set day='".$day."'";

									if($history_cnt==0)
									{ 

										$sql3 .= " ,accu_my_sales		= '".($yn['accu_my_sales']+$noohap)."'";
										$sql3 .= " ,mon_my_sales		= '".($yn['mon_my_sales']+$my_month_sales)."'";
										//$sql3 .= $temp_sql1;

									}else{
										$sql3 .= ",accu_habu_sum		 ='".($yn['accu_habu_sum']+$noohap)."'";
										$sql3 .= ", mon_habu_sum		 ='".($yn['mon_habu_sum']+$my_month_sales)."'";
										//$sql3 .= $temp_sql1;
									}

									$sql3 .= " where mb_id='".$comp."'";
									sql_query($sql3);

								
					}


					
			$comp=$recom;

			$history_cnt++;


		} // while

		$rec='';
		$my_month_sales=0;
		$noohap=0;


}	

//alert('수당계산이 완료되었습니다');
    ?>

