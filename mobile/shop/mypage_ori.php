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
		<h3>파트너정보</h3>
		<ul>
			<li><span>추천파트너</span> <?=($member['mb_recommend'])?$member['mb_recommend']:"-"?></li>
			<li class="prc"><span>파트너 누적실적</span> -</li>
			<li class="prc"><span>파트너 금월실적</span> -</li>
		</ul>
	</div><!-- // infoBx -->

	<div class="infoBx">
		<h3>후원정보</h3>
		<ul>
			<li><span>산하조직</span> -</li>
			<li class="prc"><span>산하 누적실적</span> -</li>
			<li class="prc"><span>산하 금월실적</span> -</li>
		</ul>
	</div><!-- // infoBx -->

	<div class="infoBx">
		<h3>My team information</h3>
		<ul>
			<li><span>My team information</span> <a href="#" class="btn">보기</a></li>
			<li><span>Enrollment Tree</span> <a href="#" class="btn">보기</a></li>
		</ul>
	</div><!-- // infoBx -->





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