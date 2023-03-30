<?php
include_once('./_common.php');

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_mypage_file = G5_THEME_MSHOP_PATH.'/mypage.php';
    if(is_file($theme_mypage_file)) {
        include_once($theme_mypage_file);
        return;
        unset($theme_mypage_file);
    }
}

$g5['title'] = '마이페이지';
include_once(G5_MSHOP_PATH.'/_head.php');

// 쿠폰
$cp_count = 0;
$sql = " select cp_id
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_start <= '".G5_TIME_YMD."'
              and cp_end >= '".G5_TIME_YMD."' ";
$res = sql_query($sql);

for($k=0; $cp=sql_fetch_array($res); $k++) {
    if(!is_used_coupon($member['mb_id'], $cp['cp_id']))
        $cp_count++;
}
?>

<p class="blk" style="height:10px;"></p>
<div class="pk_page">
<style type="text/css">
.pk_page {font-size:14px;}

span.btn,
a.btn {display:inline-block;*display:inline;*zoom:1;height:33px;line-height:33px;padding:0 15px;border-radius:3px;background-color:#1DC2BB;color:#fff;}


.infoBx {border:solid 2px rgba(39,48,62,0.4);border-radius:8px;margin-bottom:30px;}
.infoBx h3 {line-height:40px;font-size:14px;padding-left:20px;border-bottom:solid 1px rgba(0,0,0,0.1);background-color:rgba(39,48,62,0.05);}
.infoBx ul {margin:15px;}
.infoBx ul li {display:block;line-height:30px;font-size:12px;color:#777;border-bottom:solid 1px #fff;}
.infoBx ul li.prc {color:rgba(59,105,178,1);}

.infoBx ul li span {display:inline-block;*display:inline;*zoom:1;color:#000;padding-left:20px;width:100px;background-color:rgba(39,48,62,0.05);margin-right:20px;}






</style>
	<div class="infoBx">
		<h3>내정보</h3>
		<ul>
			<li><span>Personal Information</span> <a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=register_form.php" class="btn">회원정보수정</a></li>
			<li><span>성명</span> <?=$member['mb_name']?></li>
			<li><span>전화번호</span> <?=($member['mb_hp'])?$member['mb_hp']:$member['mb_tel']?></li>
			<li><span>Rank</span> <?=($member['mb_1'])?$member['mb_1']:"-"?></li>
			<li><span>소속</span> <?=($member['mb_2'])?$member['mb_2']:"-"?></li>
			<li><span>추천파트너</span> <? 
				$sql="select mb_name from {$g5['member_table']} where mb_id = TRIM('".$member['mb_recommend']."')"; 
				$row3 = sql_fetch($sql);
				echo $row3['mb_name'].'( '.$member['mb_recommend'].' )' ?>
			</li>

		</ul>
	</div><!-- // infoBx -->

	<div class="infoBx">
	<?
		$to_moth = date("Y-m");
		$ac_buy = sql_fetch(" select sum(od_cart_price) as total from g5_shop_order where mb_id = '{$member['mb_id']}' and (od_receipt_time != '' or od_receipt_time != '0000-00-00 00:00:00') ");
		$month_buy = sql_fetch(" select sum(od_cart_price) as total from g5_shop_order where mb_id = '{$member['mb_id']}' and od_receipt_time like = '{$to_moth}%'  ");
		$pv = sql_fetch(" select sum(pv) as total from g5_shop_order where mb_id = '{$member['mb_id']}' and (od_receipt_time != '' or od_receipt_time != '0000-00-00 00:00:00') ");
	?>
		<ul>
			<li class="prc"><span>누적구매</span> <?=number_format($ac_buy['total']);?> 원</li>
			<li class="prc"><span>금월구매</span> <?=number_format($month_buy['total']);?> 원</li>
			<li class="prc"><span>PV</span> <?=number_format($pv['total']);?> 원</li>
		</ul>
	</div><!-- // infoBx -->

	

	<div class="infoBx">
		<h3>My team information</h3>
		<ul>
			<li><span>My team information</span> <a href="#" onclick="window.open('myhabu.php','tree')" class="btn">보기</a></li>
  <area shape="rect" coords="448,26,828,524" href="#" />
			
			


			<li><span>Enrollment Tree</span> <a href="javascript:;" onclick="window.open('mypage_tree.php','tree')" class="btn">보기(트리)</a> <a href="javascript:;"  onclick="window.open('mypage_org.php','tree')"  class="btn">보기(박스)</a></li>
		</ul>
	</div><!-- // infoBx -->


<div class="infoBx">
<h3>Bonus Calendar</h3>
<?
/*## calendar ################################################*/
/*## calendar ################################################*/
// 1. 총일수 구하기
$year = ($_GET['year'])? $_GET['year'] : date( "Y" ); 
$month = ($_GET['month'])? $_GET['month'] : date( "m" ); 
$mktime = mktime( 0, 0, 0, $month, 1, $year ); 
$last_day = date("t", $mktime);
// 2. 시작요일 구하기
$start_week = date("w", strtotime($year."-".$month."-01"));
// 3. 총 몇 주인지 구하기
$total_week = ceil(($last_day + $start_week) / 7);
// 4. 마지막 요일 구하기
$last_week = date('w', strtotime($year."-".$month."-".$last_day));
?>
<style type="text/css">
#pkCalendar {position:relative;}
.pk_years {text-align:center;}
.calendar_year {position:relative;color:#333;font-size:16px;text-align:center;line-height:30px;}
#peak_caution {left:50%;margin-top:-70px;margin-left:130px;font-size:14px;line-height:30px;}
#peak_caution span {color:#000;}
#prev_month {position:absolute;padding:0;width:80px;line-height:30px;text-align:left;z-index:100;}
#next_month {position:absolute;right:0;padding:0;width:80px;line-height:30px;text-align:right;z-index:100;}
table.calendar {width:100%;table-layout:fixed;border-collapse:separate;border-spacing:3px;}
table.calendar thead th,
table.calendar thead td {line-height:30px;text-align:center;background-color:#969696;color:#fff !important;font-weight:normal;}
table.calendar tbody th,
table.calendar tbody td {padding-top:10px;vertical-align:top;background-color:#F5F5F5;text-align:right;font-size:13px;height:30px;}
table.calendar tbody th {}
table.calendar tbody td.day {}
table.calendar tbody td.today {background-color:#2CC1B9;color:#fff !important;}
table.calendar tbody td.on {background-color:#2CC1B9;color:#fff !important;}
table.calendar tbody td.not {background-color:#7A7A7A;color:#fff !important;}
table.calendar tbody td .tag {text-align:center;line-height:40px;font-size:20px;}
table.calendar input[type="text"],
table.calendar input[type="password"] {padding:0;padding-left:8px;height:23px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}

table.calendar tr th:first-child,
table.calendar tr td:first-child {color:#CF0020;}
/*
table.calendar tr th:nth-child(7),
table.calendar tr td:nth-child(7) {color:#487dc6 !Important;}
*/
span.rooms {display:inline-block;*display:inline;*zoom:1;width:40px;height:18px;line-height:19px;background-color:#1583af;cursor:pointer;overflow:hidden;}
span.rooms {font-family:"돋움","dotum";color:#fff;height:18px;text-align:center;}
span.roomout {display:inline-block;*display:inline;*zoom:1;width:40px;height:18px;line-height:19px;background-color:#ed6712;cursor:pointer;overflow:hidden;}
span.roomout {font-family:"돋움","dotum";color:#fff;height:18px;text-align:center;}
td.stamp {background:url(/img/stamp.png) no-repeat center;}
#stamps {position:absolute;}
#stamps img {width:400%;height:400%;}
.items {line-height:14px;}
.items p {margin-bottom:2px;text-align:left;background-color:rgba(0,0,0,0.05);}
.items p:hover {background:rgba(0,0,0,0.6);}
.items p:hover a {color:#fff;}


</style>
<script>
$(function(){
	
});
$(window).load(function(){
	
});
</script>
<?
if ($month == 1) {
	$prv_year = $year - 1;
	$prv_month = 12;
	$nxt_year = $year;
	$nxt_month = $month + 1;
} else if ($month == 12) {
	$prv_year = $year;
	$prv_month = $month - 1;
	$nxt_year = $year + 1;
	$nxt_month = 1;
} else {
	$prv_year = $year;
	$prv_month = $month - 1;
	$nxt_year = $year;
	$nxt_month = $month + 1;
}
?>
<div id="pkCalendar">
<!-- ##start## pkCalendar ##### -->
	<a name="pk"></a>
	<div class="pk_years">
		<div id="prev_month"><a href="<?=$_SERVER['SCRIPT_NAME']?>?year=<?=$prv_year?>&month=<?=$prv_month?>#pk" class="prv_month"><i class="fa fa-caret-left" aria-hidden="true"></i> 전달</a></div>
		<div id="next_month"><a href="<?=$_SERVER['SCRIPT_NAME']?>?year=<?=$nxt_year?>&month=<?=$nxt_month?>#pk" class="nxt_month">다음달 <i class="fa fa-caret-right" aria-hidden="true"></i></a></div>
		<div class="calendar_year"><?=$year?>년 <?=$month?>월</div>
	</div><!-- // pk_years // -->

	<table id="caledar" class="calendar">
	<thead>
	<tr>
		<th>일</th>
		<th>월</th>
		<th>화</th>
		<th>수</th>
		<th>목</th>
		<th>금</th>
		<th>토</th>
	</tr>
	</thead>
	<tbody>
	<?
	// 칸 width 137
	// 5. 화면에 표시할 화면의 초기값을 1로 설정
	$day=1;

	// 6. 총 주 수에 맞춰서 세로줄 만들기
	for($i=1; $i <= $total_week; $i++){?>
	<tr>
	<?
		// 7. 총 가로칸 만들기
		for ($j=0; $j<7; $j++){
			/*$days = sprintf("%02d",$day);
			if ($days == $at_day[$days]) {
				$bgs[$i][$j] = "stamp";
			}*/

	?>
		<td id="<?=($day == date("j"))?"today":""?>" class="<?=($day == date("j"))?"today":""?> <?=$on[$i][$j]?>">
		<?
		// 8. 첫번째 주이고 시작요일보다 $j가 작거나 마지막주이고 $j가 마지막 요일보다 크면 표시하지 않아야하므로
		//    그 반대의 경우 -  ! 으로 표현 - 에만 날자를 표시한다.
			if (!(($i == 1 && $j < $start_week) || ($i == $total_week && $j > $last_week))){
		?>
		<div class="day">
			<?=$day?>
			<div class="items">
			<?
			/*##  ################################################*/
			// 주문을 하고 싶으면 ->  $qry = sql_query(" select * from g5_shop_cart where mb_id = '{$member['mb_id']}' and ct_time like '".$year."-".sprintf("%02d",$month)."-".sprintf("%02d",$day)."%' and ct_status != '쇼핑' and ct_status != '주문'   "); 
			$qry = sql_query(" select * from dividend where mb_id = '{$member['mb_id']}' and dv_datetime like '".$year."-".sprintf("%02d",$month)."-".sprintf("%02d",$day)."%'");
			
			while ($res = sql_fetch_array($qry)) {
			?>
			<p><a href="/shop/mybenefit.php?mb_id=<?=$member['mb_id']?>"><?=conv_subject($res['dv_gubun'],2)?>(<?=round(($res['dv_money']/10000),1)?>)</a></p>
			<?
			}
			/*@@End.  #####*/
			?>
			</div><!-- // items -->
		</div>
		<?
			
				// 14. 날자 증가
				$day++;
			}
		?>
		</td>
	<?}?>
	</tr>
	<?}?>
	</tbody>
	</table>
<!-- ##end## pkCalendar ## -->
</div><!-- // pkCalendar -->
</div>
<?=$uid?>
<?
/*@@End. calendar #####*/
/*@@End. calendar #####*/
?>


		<h3>최근주문내역</h3>

        <?php
        // 최근 주문내역
        define("_ORDERINQUIRY_", true);

        $limit = " limit 0, 5 ";
        include G5_MSHOP_PATH.'/orderinquiry.sub.php';
        ?>



</div><!-- // pk_page -->

<script>
$(function() {
    $(".win_coupon").click(function() {
        var new_win = window.open($(this).attr("href"), "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
        new_win.focus();
        return false;
    });
});

function member_leave()
{
    return confirm('정말 회원에서 탈퇴 하시겠습니까?')
}
</script>

<?php
include_once(G5_MSHOP_PATH.'/_tail.php');
?>