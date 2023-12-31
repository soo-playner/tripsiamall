<?php
$sub_menu = "300200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['board_table']} a ";
$sql_search = " where (1) ";

if ($is_admin != "super") {
    $sql_common .= " , {$g5['group_table']} b ";
    $sql_search .= " and (a.gr_id = b.gr_id and b.gr_admin = '{$member['mb_id']}') ";
}

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "bo_table" :
            $sql_search .= " ($sfl like '$stx%') ";
            break;
        case "a.gr_id" :
            $sql_search .= " ($sfl = '$stx') ";
            break;
        default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "a.gr_id, a.bo_table";
    $sod = "asc";
}

$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '게시판관리';
include_once('./admin.head.php');

$colspan = 15;
?>

<style>
	#container body{background:#fff !important}
	 body{background:#fff !important}
     iframe html body{background:#fff !important}
     .board_menu{color:blue;font-weight: 600}
</style>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    생성된 게시판수 <?php echo number_format($total_count) ?>개

    | <a href="/adm/board_notice.php" class="board_menu">notice</a>
    | <a href="/adm/board_news.php" class="board_menu">News</a>
    | <a href="/adm/board_support.php" class="board_menu">Support center</a>
    | <a href="/adm/board_kyc.php" class="board_menu">KYC 회원인증</a>
</div>

<?if($_GET['wr_id']){?>
    <iframe src="/adm/bbs/board.php?bo_table=kyc&wr_id=<?=$_GET['wr_id']?>" width="100%" height="700px;" scrolling="auto" style="border:0;background:white;"></iframe>
<?}else{?>
    <iframe src="/adm/bbs/board.php?bo_table=kyc" width="100%" height="700px;" scrolling="auto" style="border:0;background:white;"></iframe>
<?}?>



<?php
include_once('./admin.tail.php');
?>
