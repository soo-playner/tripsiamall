<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('../adm/inc.member.class.php');
?>
<?
if ($member['mb_org_num']){
	$max_org_num = $member['mb_org_num'];
}else{
	$max_org_num = 50;
}
$org_num     = 0;



if ($gubun=="B"){
	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";
}else{
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";
}

$sql = "select c.c_id,c.c_class,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select count(*) from g5_member where mb_recommend=c.c_id) as c_child,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='L') as b_recomm,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='R') as b_recomm2,(select mb_b_child from g5_member where mb_id=c.c_id) as b_child,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member['mb_id']}' and c.c_id='$go_id'";
$srow = sql_fetch($sql);

$sql  = "select sum(pv) as tpv from g5_shop_order where mb_id='".$srow['c_id']."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
$row2 = sql_fetch($sql);

//�Ⱓ
$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member['mb_id']."' and c_id<>'".$srow['c_id']."' and c_class like '".$srow['c_class']."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
$row3 = sql_fetch($sql);

//���� 30��
$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member['mb_id']."' and c_id<>'".$srow['c_id']."' and c_class like '".$srow['c_class']."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
$row5 = sql_fetch($sql);

//���̳ʸ� ���� ���� ����
if ($srow['b_recomm']){
	$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$srow['b_recomm']."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
	$row6 = sql_fetch($sql);

	$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$srow['b_recomm']."'";
	$row8 = sql_fetch($sql);
	$row6['tpv'] += $row8['tpv'];
}else{
	$row6['tpv'] = 0;
}

//���̳ʸ� ������ ���� ����
if ($srow['b_recomm2']){
	$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$srow['b_recomm2']."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
	$row7 = sql_fetch($sql);

	$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$srow['b_recomm2']."'";
	$row9 = sql_fetch($sql);
	$row7['tpv'] += $row9['tpv'];
}else{
	$row7['tpv'] = 0;
}

$sql    = "select c_class from ".$class_name." where mb_id='".$member['mb_id']."' and c_id='".$go_id."'";
$row4   = sql_fetch($sql);
$mdepth = (strlen($row4['c_class'])/2);

if (!$srow['b_child']) $srow['b_child']=1;
//if (!$srow['c_child']) $srow['c_child']=1;
?>
		<ul id="org" style="display:none" >
			<li>
				[<?=(strlen($srow['c_class'])/2)-1?>-<?=($srow['c_child'])?>-<?=($srow['b_child']-1)?>]|<?=get_member_label($srow['mb_level'])?>|<?=$srow['c_id']?>|<?=$srow['c_name']?>|<?=number_format($row3['tpv']/$order_split)?>|<?=number_format($row5['tpv']/$order_split)?>|<?=$srow['mb_level']?>|<?=number_format($row6['tpv']/$order_split)?> - <?=number_format($row7['tpv']/$order_split)?>|0
<?
			get_org_down($srow);
?>
			</li>
<?
?>
		</ul>

    <div id="chart-container" class="orgChart"></div>
    <script>
    $(function() {
      $('#chart-container').orgchart({
        'data' : $('#org'),
		 'zoom': true
		});

    });
    </script>