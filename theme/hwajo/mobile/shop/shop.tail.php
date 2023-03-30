<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$admin = get_admin("super");

// 사용자 화면 우측과 하단을 담당하는 페이지입니다.
// 우측, 하단 화면을 꾸미려면 이 파일을 수정합니다.
?>
<?
if (!defined('_INDEX_')) {
	@include_once(G5_THEME_MSHOP_PATH."/sub.tail.php");
}
?>

<div id="footer">
<style type="text/css">
#footer {background-color:#1C273A;color:rgba(255,255,255,0.7);}
#footer .Grp {position:relative;}
.bnb_Grp {height:30px;line-height:30px;border-bottom:solid 1px rgba(255,255,255,0.2);}
.copyright {padding:20px;line-height:20px;font-size:12px;}
#sitemap {width:100%;}
#sitemap > li {float:left;width:33.3%;}
#sitemap > li > a {display:block;padding-left:15px;color:#fff;border-top:solid 1px rgba(255,255,255,0.2);border-bottom:solid 1px rgba(255,255,255,0.2);}

#sitemaps {}
#sitemaps > li:first-child {margin-top:10px;}
#sitemaps > li a {display:block;padding-left:10px;color:rgba(255,255,255,0.8);}
</style>
	<div class="copyright Grp">
		<?=nl2br($pk['copyright'])?>
		<p class="blk" style="height:20px;"></p>
		<p><img src="/img/ft.png" alt="" /></p>
	</div><!-- // copyright Grp -->

	<p class="blk" style="height:30px;"></p>
<? if (!$ca_id && !$it_id) { ?>
	<div class="bnb_Grp">
	
		<div class="Grp">
			<ul id="sitemap">
			<?
				$qry = sql_query(" select * from g5_shop_category where length(ca_id) = '2' and ca_use = '1' order by ca_order asc  ");
				while ($res = sql_fetch_array($qry)) {
//	http://shingu.godohosting.com/shop/list.php?ca_id=10
			?>
				<li>
					<a href="/shop/list.php?ca_id=<?=$res['ca_id']?>"><?=$res['ca_name']?></a>
					<ul id="sitemaps">
					<?
					$qrys = sql_query(" select * from g5_shop_category where ca_id like '{$res['ca_id']}%' and length(ca_id) = '4' and ca_use = '1' order by ca_id, ca_order asc ");
					for ($i=0;$list[$i] = sql_fetch_array($qrys);$i++){
					?>
						<li><a href="/shop/list.php?ca_id=<?=$list[$i]['ca_id']?>"><?=$list[$i]['ca_name']?></a></li>
					<?
						}
					?>
					</ul>
				</li>
			<?
				}

				$qry = sql_query(" select * from g5_menu where length(me_code) = '2' and me_use = '1' order by me_order asc ");
				while ($res = sql_fetch_array($qry)) {
			?>
				<li>
					<a href="<?=$res['me_link']?>"><?=$res['me_name']?></a>
					<ul id="sitemaps">
					<?
					$qrys = sql_query(" select * from g5_menu where me_code like '{$res['me_code']}%' and length(me_code) = '4' and me_use = '1' order by me_id, me_order asc ");
					for ($i=0;$list[$i] = sql_fetch_array($qrys);$i++){
					?>
						<li><a href="<?=$list[$i]['me_link']?>"><?=$list[$i]['me_name']?></a></li>
					<?
						}
					?>
					</ul>
				</li>
			<?
				}
			?>
			</ul>

			<p class="clr"></p>
		</div><!-- // Grp -->
	</div><!-- // bnb_Grp -->

	<p class="blk" style="height:80px;"></p>
<? } ?>



</div><!-- // footer -->

<div class="m_btm_action">
	<a href="/?device=pc">PC버젼</a>	
	<a href="tel:<?=$default['de_admin_company_tel']?>">전화걸기</a>	
</div><!-- // m_btm_action -->


<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');
?>
