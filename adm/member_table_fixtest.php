<?php
$sub_menu = "200600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '회원추천관계 검사';
$g5['income_table'] = "member_table_fixtest";

include_once('./admin.head.php');
?>

<style>
	.sch_last{display:inline-block;}
	.btn_submit{width:100px;margin-left:20px;}
	.black_btn{background:#333 !important; border:1px solid black !important; color:white;}
</style>


<?
$colspan = 6;


$sql = "SELECT * FROM g5_member WHERE mb_recommend = mb_id OR mb_brecommend = mb_id";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " SELECT * FROM g5_member WHERE mb_recommend = mb_id OR mb_brecommend = mb_id";
$result = sql_query($sql);
?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">회원번호</th>
        <th scope="col">회원아이디</th>
        <th scope="col">추천인</th>
        <th scope="col">후원인</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $rowid = $row['mb_id'];

		/*
        if(!$brow)
            $brow = get_brow($row['vi_agent']);
		*/

        $amt = $row['transfer_amount'];
		/*
        if(!$os)
            $os = get_os($row['vi_agent']);
		*/
        $device = $row['vi_device'];

        $link = '';
        $link2 = '';
        $referer = '';
        $title = '';

        if ($row['vi_referer']) {

            $referer = get_text(cut_str($row['vi_referer'], 255, ''));
            $referer = urldecode($referer);

            if (!is_utf8($referer)) {
                $referer = iconv_utf8($referer);
            }

            $title = str_replace(array('<', '>', '&'), array("&lt;", "&gt;", "&amp;"), $referer);
            $link = '<a href="'.$row['vi_referer'].'" target="_blank">';
            $link = str_replace('&', "&amp;", $link);
            $link2 = '</a>';
        }

        if ($is_admin == 'super')
            $ip = $row['vi_ip'];
        else
            $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", G5_IP_DISPLAY, $row['vi_ip']);

        if ($brow == '기타') { $brow = '<span title="'.get_text($row['vi_agent']).'">'.$brow.'</span>'; }
        if ($os == '기타') { $os = '<span title="'.get_text($row['vi_agent']).'">'.$os.'</span>'; }

        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_category"><?php echo $row['mb_no'] ?></td>
        <td><?php echo $rowid ?></td>
        <td class="td_category td_category1"><?php echo $row['mb_recommend'] ?></td>
        <td class="td_category td_category3"><?php echo $row['mb_brecommend'] ?></td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">회원 추천인/후원인 정보가 모두 정상입니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<?php
if (isset($domain))
    $qstr .= "&amp;domain=$domain";
$qstr .= "&amp;page=";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;

include_once('./admin.tail.php');

?>
