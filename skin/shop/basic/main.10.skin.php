<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<style type="text/css">
.mian_wdBxType {}
.mian_wdBxType > ul > li {position:relative;padding:18px 0;height:80px;overflow:hidden;border-bottom:solid 1px #ddd;}
.mian_wdBxType > ul > li .thumb {float:right;width:80px;height:80px;background-color:#e5e5e5;}
.mian_wdBxType > ul > li .cont {float:left;width:60%;height:70px;overflow:hidden;}
.mian_wdBxType > ul > li .cont .sbj {line-height:20px;max-height:40px;overflow:hidden;margin-bottom:10px;}
.mian_wdBxType > ul > li .cont .sbj a {color:#000;font-size:16px;font-family:"nngdb";}
.mian_wdBxType > ul > li .cont .slogun {font-size:14px;color:#777;line-height:20px;max-height:40px;overflow:hidden;}
.mian_wdBxType > ul > li:last-child {border-bottom:0;}


</style>
<div class="mian_wdBxType">
	<ul>
	<?
		for ($i=1; $row=sql_fetch_array($result); $i++) {
	?>
		<li>

			<div class="cont">
				<div class="sbj">
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
				<div class="slogun">
				<?
					if ($this->view_it_basic && $row['it_basic']) {
						echo stripslashes($row['it_basic']);
					}
				?>				
				</div><!-- // slogun -->

			</div><!-- // cont -->
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
		</li>
	<?

		}
	?>
	</ul>
	<p class="clr"></p>
</div><!-- // mian_wdBxType -->