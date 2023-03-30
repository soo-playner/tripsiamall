<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('../adm/inc.member.class.php');

if ($gubun=="B"){
	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";
}else{
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";
}

//$mrow = sql_fetch("select * from g5_member where mb_id='{$go_id}'");
$crow = sql_fetch("select c_class from g5_member_class where mb_id='{$member['mb_id']}' and c_id='{$go_id}'");
$mdepth = (strlen($crow['c_class'])/2);
?>
		<div class="zTreeDemoBackground left" style="min-height:573px;margin:0px 10px 0px 10px;border:1px solid #d9d9d9;">
			<ul id="treeDemo" class="ztree"></ul>
		</div>
		<SCRIPT type="text/javascript">
			<!--
			var setting = {
				view: {
					nameIsHTML: true
				},
				data: {
					simpleData: {
						enable: true
					}
				}
			};
			var zNodes =[
		<?
		$sql = "select c.c_id,c.c_class,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select count(*) from g5_member where mb_recommend=c.c_id) as c_child,(select mb_b_child from g5_member where mb_id=c.c_id) as b_child,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='L') as b_recomm,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='R') as b_recomm2,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member['mb_id']}' and c.c_class like '{$crow['c_class']}%' order by c.c_class";

		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			if (strlen($row['c_class'])==2){
				$parent_id = 0;
			}else{
				$parent_id = substr($row['c_class'],0,strlen($row['c_class'])-2);
			}
			$sql  = "select sum(od_receipt_price) as tprice,sum(pv) as tpv from g5_shop_order where mb_id='".$row['c_id']."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
			$row2 = sql_fetch($sql);

			$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_id<>'".$row['c_id']."' and c_class like '".$row['c_class']."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
			$row3 = sql_fetch($sql);

			//이전 30일
			$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_id<>'".$row['c_id']."' and c_class like '".$row['c_class']."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
			$row5 = sql_fetch($sql);

			//바이너리 왼쪽 오늘 매출
			if ($row['b_recomm']){
				$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row['b_recomm']."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
				$row6 = sql_fetch($sql);

				$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$row['b_recomm']."'";
				$row8 = sql_fetch($sql);

				$row6['tpv'] += $row8['tpv'];
			}else{
				$row6['tpv'] = 0;
			}

			//바이너리 오른쪽 오늘 매출
			if ($row['b_recomm2']){
				$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row['b_recomm2']."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
				$row7 = sql_fetch($sql);

				$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$row['b_recomm2']."'";
				$row9 = sql_fetch($sql);
				$row7['tpv'] += $row9['tpv'];
			}else{
				$row7['tpv'] = 0;
			}

			if (!$row['b_child']) $row['b_child']=1;
			//if (!$row['c_child']) $row['c_child']=1;

		?>
				{ id:"<?=$row['c_class']?>", pId:"<?=$parent_id?>", name:"<img src='/img/<?=$row['mb_level']?>.gif' width=12 align=absmiddle> <img src='/img/pool/<?=$row[pool_level]?>.gif' width=12 align=absmiddle> [<?=(strlen($row['c_class'])/2)-1?>-<?=($row['c_child'])?>-<?=($row['b_child']-1)?>] <?=$row['c_name']?> (<?=$row['c_id']?>)  <img src='/adm/img/dot.gif'> 누적매출 <?=number_format($row3['tpv']/$order_split)?> <img src='/adm/img/dot.gif'> 30일매출 <?=number_format($row5['tpv']/$order_split)?>  <img src='/adm/img/dot.gif'> 바이너리레그매출 <?=number_format($row6['tpv']/$order_split)?> - <?=number_format($row7['tpv']/$order_split)?> ", open:true, click:false},
		<?
		}
		?>
			];

			$(document).ready(function(){
				<?if ($stx && $sfl){?>
					btn_search();
				<?}?>
				$.fn.zTree.init($("#treeDemo"), setting, zNodes);
			});

			//-->
		</SCRIPT>