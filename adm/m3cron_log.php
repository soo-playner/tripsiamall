<?php // m3cron ver 1.10
$sub_menu = "600300";
include_once("./_common.php");
include_once(G5_PATH.'/lib/m3cron.lib.php');
$g5['title'] = "자동 스케쥴러 실행 기록";
include_once(G5_ADMIN_PATH.'/admin.head.php');
$colspan = 6;
?>

<?php
// 파일명 지정된 경우 "전체 파일 로그 보기" 링크
if($stx) {?>
<div class="local_ov01 local_ov">
    <a href="<?php echo $_SERVER['PHP_SELF']?>" class="ov_listall">전체파일 로그 보기</a>
</div>
<?php }?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>자동 스케쥴러  기록 목록</caption>
    <thead>
    <tr>
        <th scope="col">파일</th>
        <th scope="col">실행</th>
        <th scope="col">실행시간</th>
        <th scope="col">ip</th>
        <th scope="col">로봇</th>
        <th scope="col">mb_id 실행</th>
	</tr>	
    </thead>
    <tbody>

<?php
// 파일명 지정시 조건문 만들기
	$sql_search = "";
if($stx) {
	str_replace(array("\"", "\'"), "", $stx); // 주사지랄방지
	$sql_search = "where name = '{$stx}'";
}
// 로그 가져오기

    $sql_common = " from `{$m3cron['log_table']}` ";
	//$sql_search = "";
    $sql = " select count(*) as cnt
                {$sql_common}
                {$sql_search} ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];

    $rows = $config['cf_page_rows'];
    $total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
    if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
    $from_record = ($page - 1) * $rows; // 시작 열을 구함



$query = sql_query("select * {$sql_common} {$sql_search} order by datetime DESC limit {$from_record}, {$rows} ");
while($row = sql_fetch_array($query)) {
?>
<tr>
	<td><a href="<?=$_SERVER['PHP_SELF']?>?stx=<?=$row['name']?>"><?=$row['name']?></a></td>
	<td><?=$row['datetime']?></td>
	<td class="align_r"><?=sprintf("%.3f", $row['runtime']*1000)?> msec</td>
	<td><?=$row['ip']?></td>
	<td><?=$row['robot']?></td>
	<td><?=$row['mb_id']?></td>
</tr>
<?php } ?>
<?php if ($i == 0) echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>'; ?>
</tbody>
</table>
</div>

<?php
$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page=');
if ($pagelist) {
    echo $pagelist;
}

include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>