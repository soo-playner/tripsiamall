<?php
$sub_menu = "600500";
include_once('./_common.php');

$g5['title'] = "승급 현황";

include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if (empty($fr_date)) $fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-90 day"));
if (empty($to_date)) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;to_id=".$fr_id;
$query_string = $qstr ? '?'.$qstr : '';
?>

<style>
    .red{color:red}
    .text-center{text-align:center}
    .sch_last{display:inline-block;}
    .rank_img{width:20px;height:20px;margin-right:10px;}
	.btn_submit{width:100px;margin-left:20px;}
	.black_btn{background:#333 !important; border:1px solid black !important; color:white;}
</style>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function fvisit_submit(act)
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>



<form name="fvisit" id="fvisit" class="local_sch02 local_sch" method="get">
    <div class="sch_last">
        <strong>승급일자</strong>
        <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input" size="15" style="width:120px" maxlength="10">
        <label for="fr_date" class="sound_only">시작일</label>
        ~
        <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input" size="15" style="width:120px" maxlength="10">
        <label for="to_date" class="sound_only">종료일</label>
        
    </div>

    <div class="sch_last" style="margin-left:20px">
        <strong>멤버아이디</strong>
        <input type="text" name="fr_id" value="<?php echo $fr_id ?>" id="fr_id" class="frm_input" size="15" style="width:120px" maxlength="10">
        <label for="fr_id" class="sound_only">회원아이디</label>
    </div>
    <input type="submit" value="검색" class="btn_submit">
</form>






<?
$colspan = 6;
$to_date = date("Y-m-d", strtotime(date("Y-m-d")."+1 day"));

$sql_common = " from rank ";
$sql_search = " where l_date between '{$fr_date}' and '{$to_date}' ";
if($fr_id){
    $sql_search .= " AND mb_id = '{$fr_id}'";
}
$sql = " select count(*) as cnt
{$sql_common}
{$sql_search}";

$rows = sql_fetch($sql);
$total_count = $rows['cnt'];

$rows = 50;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            order by l_date desc
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);
?>

<link href="https://cdn.jsdelivr.net/npm/remixicon@2.3.0/fonts/remixicon.css" rel="stylesheet">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th>no</th>
        <th>회원아이디</th>
        <th>해당상품코드</th>
        <th>상품레벨</th>
        <th>승급레벨</th>
        <th>승급일</th>
        <th>승급기록</th>
    </tr>
    </thead>
    <tbody>
    <div>업데이트 기록</div>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>
    
    <tr class="<?php echo $bg; ?>">
        <td class='no'><?=$row['no']?></td>
        <td class='text-center'><?=$row['mb_id']?></td>
        <td class='text-center'> <?=$row['it_name'];?></td>
        <td class='text-center'><?=$row['origin_rank'];?></td>	
        <td class='text-center'><?=$row['change_rank'];?> </td>
        <td class='text-center'> <?=$row['l_date'];?></td>
        
        <td><?=$row['rank_note'];?></td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없거나 관리자에 의해 삭제되었습니다.</td></tr>';
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
?>


<?
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>


