<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<style type="text/css">
.main_BxType {min-height:335px;}
.main_BxType .hover {position:absolute;left:0;top:0;width:230px;height:335px;line-height:335px;background:rgba(188,133,84,0.9);text-align:center;display:none;}
.main_BxType > ul > li {position:relative;float:left;padding:20px;width:190px;height:295px;margin-right:1px;margin-bottom:1px;background-color:#fff;}
.main_BxType > ul > li:nth-child(4n+0) {margin-right:0px;}
.main_BxType > ul > li:hover .hover {display:block;}
.main_BxType > ul > li .tx {padding:10px 0;height:40px;line-height:20px;color:#000;font-size:13px;overflow:hidden;}
.main_BxType > ul > li .price {color:#bc8554;font-size:14px;font-family:"nngdb";}
.main_BxType > ul > li .price strike {font-size:12px;color:#888;}

</style>
<div class="main_BxType">
	<ul>
	<?
		for ($i=1; $row=sql_fetch_array($result); $i++) {
	?>
		<li>
			<div class="hover">
				<?
					if ($this->href) {
						echo "<div class=\"sct_txt\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
					}
				?>
				<img src="/img/view.png" alt="" />
				<?
					if ($this->href) {
						echo "</a></div>\n";
					}
				?>
			</div><!-- // hover -->
			<div class="thumb">
			<?
				if ($this->href) {
					echo "<div class=\"sct_img\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
				}

				if ($this->view_it_img) {
					echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
				}

				if ($this->href) {
					echo "</a></div>\n";
				}
			?>
			</div><!-- // thumb -->
			<div class="cont">
				<div class="tx">
				<?
					if ($this->href) {
						echo "<div class=\"sct_txt\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
					}

					if ($this->view_it_name) {
						echo stripslashes($row['it_name'])."\n";
					}

					if ($this->href) {
						echo "</a></div>\n";
					}
				?>
				</div><!-- // tx -->
				<div class="price">
				<?
					if ($this->view_it_cust_price || $this->view_it_price) {
						if ($this->view_it_cust_price && $row['it_cust_price']) {
							echo "<strike>".display_price($row['it_cust_price'])."</strike>\n";
						}
						if ($this->view_it_price) {
							echo display_price(get_price($row), $row['it_tel_inq'])."\n";
						}
					}
				?>
				</div><!-- // price -->
			</div><!-- // cont -->
		</li>
	<?

		}
	?>
	</ul>
	<p class="clr"></p>
</div><!-- // main_BxType -->