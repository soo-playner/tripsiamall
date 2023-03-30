<?php
$sub_menu = '600300';
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'd');

check_token();

if($fr_date){
$sql_search = " and dv_datetime>='$fr_date' AND dv_datetime<='$to_date'";
}



if(($chkc) || ($chkm) || ($chkr) || ($chkd) || ($chkp) || ($chke) ){
	$sql_search .= " and (";
		if($chkc){
		$sql_search .= " dv_gubun='출자금'";
		}
		
		if(($chkc) &&($chkm)){
			$sql_search .= " or ";
		}
		
		if($chkm){
		$sql_search .= "  dv_gubun='M'";
		}
		
		if(( ($chkc) || ($chkm) ) &&($chkr)){
			$sql_search .= " or ";
		}
				
		if($chkr){
		$sql_search .= "  dv_gubun='R'";
		}

		if(( ($chkc) || ($chkm) || ($chkr) ) &&($chkd)){
			$sql_search .= " or ";
		}
				
		if($chkd){
		$sql_search .= "  dv_gubun='D'";
		}
		
		if(( ($chkc) || ($chkm) || ($chkr) || ($chkd) ) &&($chke)){
			$sql_search .= " or ";
		}
				
		if($chke){
		$sql_search .= " ev_yn=1";
		}
 $sql_search .= " )";
 
}


if ($stx) {
    $sql_search .= " and ( ";
	if(($sfl=='mb_id') || ($sfl=='dv_oneid')){
            $sql_search .= " ({$sfl} = '{$stx}') ";
          
	}else{
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
          
    }
    $sql_search .= " ) ";
}


$doit1='현재조건 전체 배당금지급 함' ;
$doit2='현재조건 전체 배당금지급 안함' ;


 if(   $_POST['act_button']==$doit1   ){
 	$sql_search .= " and dv_paid<>'S' and dv_paid<>'R' ";
 	$sql = " update dividend set dv_paid='Y' where (1) $sql_search ";
	sql_query($sql);
	
 	
}else if(  $_POST['act_button']==$doit2  ){
	$sql_search .= " and dv_paid<>'S' and dv_paid<>'R' ";
 	$sql = " update dividend set dv_paid='N' where (1) $sql_search";
	sql_query($sql);

}else{



				$count = count($_POST['chk']);
				if(!$count)
				    alert($_POST['act_button'].' 하실 항목을 하나 이상 체크하세요.');

					for ($i=0; $i<$count; $i++)
					{
					    // 실제 번호를 넘김
					    $k = $_POST['chk'][$i];
					    
										if($_POST['act_button']=='선택 배당정지'){
											    $sql = " update dividend set dv_paid='S' where dv_id = '{$_POST['dv_id'][$k]}' and dv_paid='N' ";
											    sql_query($sql);
										}
								    // 배당 내역삭제
								    if($_POST['act_button']=='선택삭제'){
											    $sql = " delete from dividend where dv_id = '{$_POST['dv_id'][$k]}' ";
											    sql_query($sql);
											   
										}
								    // 배당 함
								    if($_POST['act_button']=='선택 배당금지급 함'){
											    $sql = " update dividend set dv_paid='Y' where dv_id = '{$_POST['dv_id'][$k]}' ";
											    sql_query($sql);
										}		
										
								    // 배당안 함
								    if($_POST['act_button']=='선택 배당금지급 안함'){
											    $sql = " update dividend set dv_paid='N' where dv_id = '{$_POST['dv_id'][$k]}' ";
											    sql_query($sql);
										}		
										
								
							
								/*
								    // 출자 UPDATE
								    $row2 = sql_fetch(" select sum(ch_money) as sum_chulja_money from chulja where mb_id='{$_POST['mb_id'][$k]}'  ");
								 
								    $sql= " update {$g5['member_table']} set mb_chulja = '".$row2['sum_chulja_money']."' where mb_id = '{$_POST['mb_id'][$k]}' ";
								    sql_query($sql);   
								*/

					}
			  
} //else
$qstr.= "&amp;fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;sfl=".$sfl."&amp;stx=".$stx;
goto_url('dividend_list.php?'.$qstr);
?>