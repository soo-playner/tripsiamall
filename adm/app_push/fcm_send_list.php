<?php
$sub_menu = "800200";
include_once('./_common.php');
// include_once(G5_THEME_PATH . '/_include/wallet.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '메세지관리';
include_once('../admin.head.php');

if (empty($fr_date)) $fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-1 day"));
if (empty($to_date)) $to_date = G5_TIME_YMD;

if($start_dt){$fr_date = $start_dt;}
if($end_dt){$to_date = $end_dt;}


$sql_search = " WHERE 1=1 ";

if($sfl && $stx){
    $sql_search .= "AND {$sfl} = '{$stx}' ";
}

// 검색기간검색
if($fr_date){
	$sql_search .= " and DATE_FORMAT(datetime,'%Y-%m-%d') >= '{$fr_date}' ";
	$qstr .= "&start_dt=".$fr_date;
}
if($to_date){
	$sql_search .= " and DATE_FORMAT(datetime,'%Y-%m-%d') <= '{$to_date}'";
	$qstr .= "&end_dt=".$to_date;
}

$list_sql = "SELECT * from msg_send_log {$sql_search} ORDER BY datetime desc";
$list = sql_query($list_sql);

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

?>


<style>
    select#sfl{padding:9px 10px;}
    .frm_input{height:30px;}
    .local_sch .btn_submit{width:100px;padding:9px 5px;height:inherit;}

    .td_id {
		color: black;
		font-size: 14px;
		padding-left: 6px !important;
		font-weight: 700;
		width: 100px;
		font-family: Montserrat, Arial, sans-serif
	}

	.td_name {
		color: gray;
	}
    .td_nick {
		color: blue;
	}
</style>
<script>
    $(function(){
        $("#start_dt, #end_dt").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
    });
</script>


<link rel="stylesheet" href="<?= G5_THEME_URL ?>/css/scss/custom.css">
<link rel="stylesheet" href="../css/scss/admin_custom.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <p>
      
	</p>
</div>


<form name="fsearch" id="fsearch" class="local_sch01 local_sch" style="clear:both;padding:10px 20px 20px;" method="get" >
	
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="mb_id" <?= get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
        <option value="mb_nick" <?= get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option>
        <option value="mb_name" <?= get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
        <option value="mb_hp" <?= get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
    </select>

    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
    | 검색 기간 : <input type="text" name="start_dt" id="start_dt" placeholder="From" class="frm_input" value="<?=$fr_date?>" /> 
    ~ <input type="text" name="end_dt" id="end_dt" placeholder="To" class="frm_input" value="<?=$to_date?>"/>
    
    <?=$html?>

    <input type="submit" class="btn_submit search" value="검색"/>
    <input type="button" class="btn_submit excel" id="btnExport"  data-name='zeta_bonus_list' value="엑셀 다운로드" />
		
	</div>
</form>


<form name="msg_form" id="msg_form" action="./.php" onsubmit="return fmemberlist_submit(this);" method="post">
<div class="tbl_head02 tbl_wrap">
    <table >
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
	<p>  </p>
    <tr>
        <th scope="col" width="30px">No</th>
        <th scope="col" width="100px">회원아이디</th>
        <th scope="col" width="100px">이름/닉네임</th>
        <th scope="col" width="100px">발송시간</th>
        <th scope="col" width="200px">타이틀</th>	
        <th scope="col" width="250px">내용</th>
        <th scope="col" width="30px">처리결과</th>
        <th scope="col" width="30px">토큰</th>
    </tr>
    </thead>

    <tbody>
    <?for($i=0; $row=sql_fetch_array($list); $i++){
        $mb = sql_fetch("SELECT * from g5_member WHERE mb_id ='{$row['mb_id']}' ");

        ?>
    
    <tr class='<?if($i == 0){echo 'first';}?>'>
   
    <td rowspan="2" style="text-align:center">
        <input type="hidden" name="no[]" value="<?=$row['no']?>">
        <input type='checkbox' class='checkbox' name='check' <?php echo $row['used'] > 0?'checked':''; ?>>
    </td>
    <td rowspan="2" class='td_id'><?=$row['mb_id']?></td>
    <td rowspan="1" class='td_name'><?=$mb['mb_name']?></td>
    <td rowspan="2" class='td_datetime'><?=$row['datetime']?></td>
    <td rowspan="2" class='td_title'><?=$row['title']?></td>
    <td rowspan="2" ><?=$row['contents']?></td>
    <td rowspan="2" ><?=$row['msg_id']?></td>
    <td rowspan="2" ></td>
    </tr>
    <tr>
     <td rowspan="1" class='td_nick'><?=$mb['mb_nick']?></td>
    </tr>
    <?}?>
    </tbody>
    
</table>

   
</form>
</div>

<?php
include_once('../admin.tail.php');
?>
