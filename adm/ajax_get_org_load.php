<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('./inc.member.class.php');

if ($member['mb_org_num']){
	$max_org_num = $member['mb_org_num'];
}else{
	$max_org_num = 50;
}
$org_num     = 8;


if ($gubun=="B"){
	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";
}else{
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";
}

$sql = "SELECT c.c_id,c.c_class,(
	SELECT mb_level
	FROM g5_member
	WHERE mb_id=c.c_id) AS mb_level,(
	SELECT mb_name
	FROM g5_member
	WHERE mb_id=c.c_id) AS c_name,(
	SELECT COUNT(*)
	FROM g5_member
	WHERE mb_recommend=c.c_id) AS c_child,(
	SELECT mb_b_child
	FROM g5_member
	WHERE mb_id=c.c_id) AS b_child,(
	SELECT mb_id
	FROM g5_member
	WHERE mb_brecommend=c.c_id AND mb_brecommend_type='L') AS b_recomm,(
	SELECT mb_id
	FROM g5_member
	WHERE mb_brecommend=c.c_id AND mb_brecommend_type='R') AS b_recomm2,(
	SELECT COUNT(mb_no)
	FROM g5_member
	WHERE mb_brecommend=c.c_id AND mb_leave_date = '') AS m_child,  (
	SELECT mb_no
	FROM g5_member
	WHERE mb_id=c.c_id) AS m_no
	,(select mb_rate FROM g5_member WHERE mb_id=c.c_id) AS mb_rate
	, ( select recom_sales FROM g5_member WHERE mb_id=c.c_id) AS recom_sales
	,(select mb_save_point FROM g5_member WHERE mb_id=c.c_id) AS mb_save_point
	,(select grade FROM g5_member WHERE mb_id=c.c_id) AS grade
	,(SELECT mb_child FROM g5_member WHERE mb_id=c.c_id) AS mb_children
	FROM g5_member m
	JOIN ".$class_name." c ON m.mb_id=c.mb_id
	WHERE c.mb_id='{$member['mb_id']}' AND c.c_id='$go_id'
";

$srow = sql_fetch($sql);
$my_depth = strlen($srow['c_class']);


if ($order_proc==1){
	$sql  = "select today as tpv from brecom_bonus_today where mb_id='".$srow['c_id']."'";
	$row2 = sql_fetch($sql);

	$sql  = "select noo as tpv from ".$ngubun."recom_bonus_noo where mb_id='".$srow['c_id']."'";
	$row3 = sql_fetch($sql);


	$sql  = "select thirty as tpv from ".$ngubun."thirty where mb_id='".$srow['c_id']."'";
	$row5 = sql_fetch($sql);
}else{


	$sql  = "select no,today as tpv from ".$ngubun."today where mb_id='".$srow['c_id']."'";
	$row2 = sql_fetch($sql);

	if ($row2['no']){

	}else{

		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id='".$srow['c_id']."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
		$row2 = sql_fetch($sql);
		if (!$row2['tpv']) $row2['tpv'] = 0;
		sql_query("insert ".$ngubun."today SET today=".$row2['tpv']." ,mb_id='".$srow['c_id']."'");	
	}

	$sql  = "select no,noo as tpv from ".$ngubun."noo where mb_id='".$srow['c_id']."'";
	$row3 = sql_fetch($sql);
	if ($row3['no']){

	}else{
		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member['mb_id']."'  and c_class like '".$srow['c_class']."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
		$row3 = sql_fetch($sql);

		$row3 = sql_fetch($sql);
		if (!$row3['tpv']) $row3['tpv'] = 0;
		$sql  = "insert ".$ngubun."noo SET noo=".$row3['tpv']." ,mb_id='".$srow['c_id']."'";
		sql_query($sql);	
	}

	//���� 30��
	$sql  = "select no,thirty as tpv from ".$ngubun."thirty where mb_id='".$srow['c_id']."'";
	$row5 = sql_fetch($sql);
	if ($row5['no']){

	}else{
		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member['mb_id']."' and c_class like '".$srow['c_class']."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
		$row5 = sql_fetch($sql);
		if (!$row5['tpv']) $row5['tpv'] = 0;
		sql_query("insert ".$ngubun."thirty SET thirty=".$row5['tpv']." ,mb_id='".$srow['c_id']."'");	
	}

}


if ($srow['b_recomm']){
	$left_sql = " SELECT mb_rate,mb_save_point, (SELECT noo FROM brecom_bonus_noo WHERE mb_id ='{$srow['b_recomm']}' ) AS noo FROM g5_member WHERE mb_id = '{$srow['b_recomm']}' ";
	
	$mb_self_left_result = sql_fetch($left_sql);
	$mb_self_left_acc = $mb_self_left_result['mb_save_point'] + $mb_self_left_result['noo'];
	$row6['tpv'] = $mb_self_left_acc ;
	
}else{
	$row6['tpv'] = 0;
}

if ($srow['b_recomm2']){
	$right_sql = " SELECT mb_rate,mb_save_point, (SELECT noo FROM brecom_bonus_noo WHERE mb_id ='{$srow['b_recomm2']}' ) AS noo FROM g5_member WHERE mb_id = '{$srow['b_recomm2']}' ";
	$mb_self_right_result = sql_fetch($right_sql);
	$mb_self_right_acc = $mb_self_right_result['mb_save_point'] + $mb_self_right_result['noo'];
	$row7['tpv'] = $mb_self_right_acc ;
}else{
	$row7['tpv'] = 0;
}

$sql    = "select c_class from ".$class_name." where mb_id='".$member['mb_id']."' and c_id='".$go_id."'";
$row4   = sql_fetch($sql);
$mdepth = (strlen($row4['c_class'])/2);

			

			if (!$srow['b_child']) $srow['b_child']=1;
			//if (!$srow['c_child']) $srow['c_child']=1;

			$member_info_data = sql_fetch("SELECT * FROM g5_member_info WHERE mb_id ='{$srow['c_id']}' order by date desc limit 0,1 ");
			$recom_info = json_decode($member_info_data['recom_info'],true);
			$brecom_info = json_decode($member_info_data['brecom_info'],true);

if ($srow['c_class']){
?>
		<ul id="org" style="display:none" >
			<li>
			[<?=(strlen($srow['c_class'])/2)-1?>-<?=($srow['c_child'])?>-<?=($srow['b_child']-1)?>]
			|<?=get_member_label($srow['mb_level'])?>
			|<?=$srow['c_id']?>|<?=$srow['c_name']?>
			|<?=Number_format($brecom_info['LEFT']['hash'])?>
				|<?=Number_format($brecom_info['RIGHT']['hash'])?>
			|<?=$srow['mb_level']?>
			|<?=pv($brecom_info['LEFT']['sales'])?>
			|<?=pv($brecom_info['RIGHT']['sales'])?>
			|<?=pv($srow['recom_sales'])?>
			|<?=($srow['mb_children']-1)?>
			|<?=pv($srow['mb_save_point'])?>
			|<?=$srow['grade']?>
			|<?=Number_format($srow['mb_rate'])?>
			|<?=pv($recom_info['sales_10'])?>
			|<?=($srow['c_child'])?>
			|<?=($srow['b_child']-1)?>
			|<?=Number_format($recom_info['hash_10'])?>
			|<?=$gubun?>


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
		 'zoom': false
		});

		var $container = $('#chart-container');
		
		var $chart = $('.orgchart');
		$chart.css('transform', "scale(1,1)");
		var div = $chart.css('transform');
		var values = div.split('(')[1];
		values = values.split(')')[0];
		values = values.split(',');
		var a = values[0];
		var b = values[1];
		var currentZoom = Math.sqrt(a*a + b*b);
		var zoomval = .8;
		$container.scrollLeft(($container[0].scrollWidth - $container.width())/2);
		var my_num = 0;

		// zoom buttons	
		$('#zoomIn').on('click', function () {
			my_num++;
			zoomval = currentZoom += 0.1;
			$chart.css("transform",'matrix('+zoomval+', 0, 0, '+zoomval+', 0 ,'+((my_num)*85)+')');    
			$container.scrollLeft(($container[0].scrollWidth - $container.width())/2);
		});

		$('#zoomOut').on('click', function () {
			zoomval = currentZoom -= 0.1;
			my_num--;
			$chart.css("transform",'matrix('+zoomval+', 0, 0, '+zoomval+', 0 ,'+((my_num)*85)+')');    
			$container.scrollLeft(($container[0].scrollWidth - $container.width())/2);

		});

    });
    </script>
<?}?>
