<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>
    <ul id="ul_date">
    <?php for ($i=0; $i<count($list); $i++) {  ?>
        <li>
			<p><?=substr($list[$i]['wr_datetime'],0,11)?></p>
			<?php
			//echo $list[$i]['icon_reply']." ";
			echo "<a href=\"".$list[$i]['href']."\">";
			if ($list[$i]['is_notice'])
				echo "<strong>".$list[$i]['subject']."</strong>";
			else
				echo "ㆍ".$list[$i]['subject'];

			if ($list[$i]['comment_cnt'])
				echo $list[$i]['comment_cnt'];

			echo "</a>";
			 ?>
        </li>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    <li class="taC">게시물이 없습니다.</li>
    <?php }  ?>
    </ul>