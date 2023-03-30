<?php
$sub_menu = "600000";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if ($_POST['benefit_limit1']=='on'){ $bbbb=1; }else {$bbbb=0;}

if(   ($_POST['act_button']=='Save Plan') && ($_POST['edit_no']=='')   ){

		if(  ($_POST['partner_cont']<0) && ($_POST['partner_cont']>3)  ){ alert("메트릭스수당 연속성은 1-3까지만 사용가능합니다!! 다시 확인하세요.");}	
		//if($_POST['price_kind']==""){ alert("수당 적용기준이 없습니다!! 다시 확인하세요.[PV, BV,판매가]");}
		if($_POST['allowance_name']==""){ alert("수당이름이 없습니다!! 다시 확인하세요~");}
		if($_POST['base_source']==""){ alert("계산할 수당이 없습니다!! 다시 확인하세요~");}
		if($_POST['per']==""){ alert("수당 적용율이 없습니다!! 다시 확인하세요~");}

								$sql = " insert into pinna_soodang_set
											set
												allowance_name = '{$_POST['allowance_name']}',
												day = '".date('Y-m-d')."',
												immediate='{$_POST['immediate']}',
												price_cond = '{$_POST['price_kind']}',
												
												base_source = '{$_POST['base_source']}',

												source = '{$_POST['source']}',
												source_in1 = '{$_POST['source_in1']}',
												source_in2 = '{$_POST['source_in2']}',
												source_cond1 = '{$_POST['source_cond1']}',
												source_cond2 = '{$_POST['source_cond2']}',


												source11 = '{$_POST['source11']}',
												source_in11 = '{$_POST['source_in11']}',
												source_in12 = '{$_POST['source_in12']}',
												source_cond11 = '{$_POST['source_cond11']}',
												source_cond12 = '{$_POST['source_cond12']}',


												per = '{$_POST['per']}',
												andor = '{$_POST['andor']}',
												iwolyn = '{$_POST['iwolyn']}',

												benefit_limit1 = $bbbb,
												sales_reset = '{$_POST['sales_reset']}',
												max_reset1 = '{$_POST['max_reset1']}',
												max_reset2 = '{$_POST['max_reset2']}',

												cycle = '{$_POST['cycle']}',
												recom_kind = '{$_POST['recom_kind']}',

												mb_level_in1 = '{$_POST['mb_level_in1']}',
												mb_level_in2 = '{$_POST['mb_level_in2']}',
												
												mb_level_cond1 = '{$_POST['mb_level_cond1']}',
												mb_level_cond2 = '{$_POST['mb_level_cond2']}',

												mb_level_in11 = '{$_POST['mb_level_in11']}',
												mb_level_cond11 = '{$_POST['mb_level_cond11']}',
												mb_level_cond12 = '{$_POST['mb_level_cond12']}',

												partner_cnt = '{$_POST['partner_cnt']}',
												partner_cont = '{$_POST['partner_cont']}',
												
												history_in1 = '{$_POST['history_in1']}',
												history_in2 = '{$_POST['history_in2']}',
												
												history_cond1 = '{$_POST['history_cond1']}',
												history_cond2 = '{$_POST['history_cond2']}'
												
											";
								sql_query($sql);
echo $sql;

}else if( ($_POST['act_button']=='Save Plan') && ($_POST['edit_no']>0) ){

								$sql = " update pinna_soodang_set
											set
												allowance_name = '{$_POST['allowance_name']}',
												day = '".date('Y-m-d')."',
												immediate='{$_POST['immediate']}',
												price_cond = '{$_POST['price_kind']}',
												
												base_source = '{$_POST['base_source']}',

												source = '{$_POST['source']}',
												source_in1 = '{$_POST['source_in1']}',
												source_in2 = '{$_POST['source_in2']}',
												source_cond1 = '{$_POST['source_cond1']}',
												source_cond2 = '{$_POST['source_cond2']}',

												source11 = '{$_POST['source11']}',
												source_in11 = '{$_POST['source_in11']}',
												source_in12 = '{$_POST['source_in12']}',
												source_cond11 = '{$_POST['source_cond11']}',
												source_cond12 = '{$_POST['source_cond12']}',

												per = '{$_POST['per']}',
												andor = '{$_POST['andor']}',
												iwolyn = '{$_POST['iwolyn']}',

												benefit_limit1 = $bbbb,
												sales_reset = '{$_POST['sales_reset']}',
												max_reset1 = '{$_POST['max_reset1']}',
												max_reset2 = '{$_POST['max_reset2']}',
												cycle = '{$_POST['cycle']}',
												recom_kind = '{$_POST['recom_kind']}',

												mb_level_in1 = '{$_POST['mb_level_in1']}',
												mb_level_in2 = '{$_POST['mb_level_in2']}',
												
												mb_level_cond1 = '{$_POST['mb_level_cond1']}',
												mb_level_cond2 = '{$_POST['mb_level_cond2']}',

												mb_level_in11 = '{$_POST['mb_level_in11']}',
												mb_level_cond11 = '{$_POST['mb_level_cond11']}',
												mb_level_cond12 = '{$_POST['mb_level_cond12']}',


												partner_cnt = '{$_POST['partner_cnt']}',
												partner_cont = '{$_POST['partner_cont']}',


												
												history_in1 = '{$_POST['history_in1']}',
												history_in2 = '{$_POST['history_in2']}',
												history_cnt = '{$_POST['history_cnt']}',
												history_cond1 = '{$_POST['history_cond1']}',
												history_cond2 = '{$_POST['history_cond2']}'
												
											where idx='".$_POST['edit_no']."'";
								sql_query($sql);
								echo $sql;
							
}else if( $_POST['act_button']=='Delete Plan'){

								$sql = " delete from pinna_soodang_set where idx='".$_POST['no']."'";
								sql_query($sql);
								

}
				
							
goto_url('./allowance_sett.php?'.$qstr);
?>