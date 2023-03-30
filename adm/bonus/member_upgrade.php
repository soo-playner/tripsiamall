<?php
$sub_menu = "600300";
include_once('./_common.php');

$g5['title'] = "멤버 승급 기록";

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
        <strong>기간별검색</strong>
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
$sql_search = " where rank_day between '{$fr_date}' and '{$to_date}' ";
if($fr_id){
    $sql_search .= " AND mb_id = '{$fr_id}'";
}
$sql = " select count(*) as cnt
{$sql_common}
{$sql_search}";

$rows = sql_fetch($sql);
$total_count = $rows['cnt'];

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            order by rank_day desc
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
        <th>기존직급</th>
        <th>승급직급</th>
        <th>승급일</th>
        <th>승급기록</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>
   
    <tr class="<?php echo $bg; ?>">
        <td class='no'><?=$row['no']?></td>
        <td class='text-center'><?=$row['mb_id']?></td>
        <td class='text-center'><img src="<?=G5_URL?>/img/<?=$row['old_level']?>.png" class='rank_img'><?=$row['old_level'];?></td>	
        <td class='text-center'><img src="<?=G5_URL?>/img/<?=$row['rank']?>.png" class='rank_img'><?=$row['rank'];?> </td>
        <td class='text-center'> <?=$row['rank_day'];?>	</td>
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
if (isset($domain)){
    $qstr .= "&amp;domain=$domain";
    $qstr .= "&amp;page=";
}

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;

?>

<!-- 
<link rel="stylesheet" href="/adm/css/switch.css">

<style type="text/css">
	/* xmp {font-family: 'Noto Sans KR', sans-serif; font-size:12px;} */
	.adminWrp{padding:30px; min-height:50vh}
	input[type="radio"] {}
	input[type="radio"] + label{color:#999;}
	input[type="radio"]:checked + label {color:#e50000;font-weight:bold;font-size:14px;}

	table.regTb {width:100%;table-layout:fixed;border-collapse:collapse;}
	table.regTb th,
	table.regTb td {line-height:28px;}
	table.regTb th {padding: 6px 0;border: 1px solid #d1dee2;background: #e5ecef;color: #383838;letter-spacing: -0.1em;}
	table.regTb td {padding:8px 0;padding-left:10px;border-bottom:solid 1px #ddd;border-right:solid 1px #ddd;}
	table.regTb input[type="text"],
	table.regTb input[type="password"] {padding:0;padding-left:8px;height:23px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb textarea {padding:0;padding-left:8px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb label {cursor:pointer;}

	table.regTb input[type="radio"] {}
	table.regTb input[type="radio"] + label{color:#999;}
	table.regTb input[type="radio"]:checked + label {color:#e50000;font-weight:bold;}
	tfoot {
		clear:both;
		display: table-footer-group;
		vertical-align: middle;
		border-color: inherit;
	}
	span.help {font-size:11px;font-weight:normal;color:rgba(38,103,184,1);}

	.name{background:#222437;color:white;font-weight:900}
	.text-center{text-align: center !important;}
	.currency{font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif; font-size:16px; font-weight:900;letter-spacing:1px; text-indent: 20%;}
	.currency-right{position:relative;float:right;right:25px;}
	.btn_ly{width:50%; min-height:80px; display:block; margin:20px auto; text-align:right;}

</style>
 
<div class="adminWrp mb50">

<form name="site" method="post" action="./config_price.proc.php" onsubmit="return frmnewwin_check(this);" style="margin:0px;">

	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
		<thead>
        <colgroup>
			<th>no</th>
			<th>회원아이디</th>
			<th>기존직급</th>
			<th>승급직급</th>
			<th>승급일</th>
			<th>승급기록</th>
        </colgroup>
		</thead>

        <tbody>
			<? while($row = sql_fetch_array($res)){ ?>
				<tr>
					
					<td class='no'><?=$row['no']?></td>
					<td class='text-center'><?=$row['mb_id']?></td>
					<td><?=$row['old_level'];?></td>	
					<td><?=$row['rank'];?> </td>
					<td> <?=$row['rank_day'];?>	</td>
					<td><?=$row['rank_note'];?></td>
				</tr>
			<?}?>
		</tbody>
	</table>
	
	<div class='btn_ly '>
		<button type='button' class="btn btn_wd btn_double blue" onclick="go_to_URL('/coin_price_curl.php?url=/adm/bonus/config_price.php');"> 코인 시세 수동 갱신</button>
		<input type="submit" name="submit" class="btn btn_wd btn_double btn_submit" value="저장하기" />
	</div>
	
</form>
</div> -->







<?
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>


