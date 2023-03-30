<?php
$sub_menu = "900200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from holiday ";

$sql_search = " where (1) ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'h_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "h_id";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);


$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '공휴일관리';
include_once ('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
$colspan = 3;


?>


<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    전체 <?php echo number_format($total_count) ?> 건

    <?php if ($is_admin == 'super') { ?><!-- <a href="javascript:chulja_clear();">포인트정리</a> --><?php } ?>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="h_day"<?php echo get_selected($_GET['sfl'], "h_day"); ?>>년도</option>
    <option value="h_name"<?php echo get_selected($_GET['sfl'], "h_name"); ?>>제목</option>
    <option value="h_day"<?php echo get_selected($_GET['sfl'], "h_day"); ?>>날짜</option
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class="required frm_input">
        
<input type="submit" class="btn_submit" value="검색">
</form>

<form name="fholidaylist" id="fholidaylist" method="post" action="./holiday_list_delete.php" onsubmit="return fholidaylist_submit(this);">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">배당금 내역 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">No</th>
        <th scope="col">공휴일</th>
        <th scope="col">제목</th>
        


    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $link1 = $link2 = '';


        $expr = '';


        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            
            <input type="hidden" name="h_id[<?php echo $i ?>]" value="<?php echo $row['h_id'] ?>" id="h_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['h_year'] ?> 공휴일적용년도</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_hid"><div><?php echo $row['h_id']; ?></div></td>
        <td class="td_hday"><a href="?sfl=<? if($sfl){echo $sfl;}else{ echo 'h_id'; }?>&amp;h_id=<?php echo $row['h_id'] ?>&amp;stx=<?php echo $stx ?>&amp;h_name=<?php echo $row['h_name']?>&amp;h_year=<?php echo $row['h_year']?>&amp;h_day=<?php echo $row['h_day'] ?>"><?php echo $row['h_day'] ?></a></td>
        <td class="td_hname"><?php echo $row['h_name']; ?></td>
        
      
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
    
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>

<section id="holiday_mng">
    <h2 class="h2_frm">공휴일 등록/수정</h2>

    <form name="fholidaylist2" method="post" id="fholidaylist2" action="./holiday_update.php" autocomplete="off">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="what" id="what" value="<?php echo $what ?>" >
    <input type="hidden" name="h_id" id="h_id" value="<?php echo $h_id ?>" >
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">
		
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>

        <tr>
            <th scope="row"><label for="h_day">공휴일날짜<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="h_day" value="<?php {echo $h_day; } ?>" id="h_day"  class="required frm_input" required> 
            	 </td>
        </tr>
        <tr>
            <th scope="row"><label for="h_name">제목<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="h_name"  value="<?php { echo $h_name; }?>"  id="h_name" required class="required frm_input"> 
        </tr>        
           
        </tbody>
        </table>
    </div>

    <div class="btn_confirm01 btn_confirm">
    	<input type="submit" name="act_button" value="수정" class="btn_submit">
    	<input type="submit" name="act_button" value="등록" class="btn_submit">
    	
    </div>
    

    </form>

</section>


<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/common.js"></script>


<script>
	
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yymmdd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

	
function fholiday_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }
    
    return true;
}


	
function clear()
{
		document.getElementById("h_day").value='';
		document.getElementById("h_name").value='';
		
}

		
</script>

<?php
include_once ('./admin.tail.php');
?>
