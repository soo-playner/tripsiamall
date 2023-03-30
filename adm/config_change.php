<?php
$sub_menu = "700400";
include_once('./_common.php');

include_once(G5_THEME_PATH.'/_include/wallet.php'); 

$g5['title'] = "코인 전환 내역";

include_once(G5_ADMIN_PATH.'/admin.head.php');

if($_GET['status']){
	$sql_condition .= " and A.status = '".$_GET['status']."'";
	$qstr .= "&status=".$_GET['status'];
}
if($_GET['id']){
	$sql_condition .= " and A.mb_id like '%".$_GET['id']."%'";
	$qstr .= "&id=".$_GET['id'];
}
if($_GET['first_name']){
	$sql_condition .= " and M.first_name like '%".$_GET['first_name']."%'";
	$qstr .= "&first_name=".$_GET['first_name'];
}
if($_GET['last_name']){
	$sql_condition .= " and M.last_name like '%".$_GET['last_name']."%'";
	$qstr .= "&last_name=".$_GET['last_name'];
}
if($_GET['mb_hp']){
	$sql_condition .= " and M.mb_hp like '%".$_GET['mb_hp']."%'";
	$qstr .= "&mb_hp=".$_GET['mb_hp'];
}
if($_GET['create_dt']){
	$sql_condition .= " and DATE_FORMAT(A.create_dt, '%Y-%m-%d') = '".$_GET['create_dt']."'";
	$qstr .= "&create_dt=".$_GET['create_dt'];
}
if($_GET['update_dt']){
	$sql_condition .= " and DATE_FORMAT(A.update_dt, '%Y-%m-%d') = '".$_GET['update_dt']."'";
	$qstr .= "&update_dt=".$_GET['update_dt'];
}

if($_GET['ord']!=null && $_GET['ord_word']!=null){
	$sql_ord = "order by ".$_GET['ord_word']." ".$_GET['ord'];
}

$sql = " select count(*) as cnt from g5_shop_change A inner join g5_member M on A.mb_id = M.mb_id WHERE 1=1  	 ";
$sql .= $sql_condition;
$sql .= $sql_ord;
$row = sql_fetch($sql,true);
$total_count = $row['cnt'];
$total_hap = $row['hap'];
$total_usd = $row['usdhap'];
$total_fee = $row['feehap'];


$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * from g5_shop_change A inner join g5_member M on A.mb_id = M.mb_id WHERE 1=1   ";
$sql .= $sql_condition;
if($sql_ord){
	$sql .= $sql_ord;
}else{
$sql .= " order by od_time desc ";
}
$sql .= " limit {$from_record}, {$rows} ";

$list = sql_query($sql);
//print_r($list);
?>
<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script>
	$(function(){

		$('.regTb [name=status]').on('change',function(e){
			var refund = 'N';

			if($(this).val() == 'N'){
				if (confirm('출금요청금을 반환하시겠습니까?')) {
					refund = 'Y';	
				} else {
					refund = 'N';
				}
			}

			$.post( "/adm/config_withdrawal.u.php", {
				uid : $(this).attr('uid'),
				status : $(this).val(),
				refund : refund
			}, function(data) {
				//alert(data.result);
			},'json');
		});


		$.datepicker.regional["ko"] = {
			closeText: "close",
			prevText: "이전달",
			nextText: "다음달",
			currentText: "오늘",
			monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
			monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
			dayNames: ["일","월","화","수","목","금","토"],
			dayNamesShort: ["일","월","화","수","목","금","토"],
			dayNamesMin: ["일","월","화","수","목","금","토"],
			weekHeader: "Wk",
			dateFormat: "yymmdd",
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			yearSuffix: ""
		};
		$.datepicker.setDefaults($.datepicker.regional["ko"]);

		$("#create_dt").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	});

</script>
<script>
$(function(){
		$('#com_send').on('click',function(e){
			var send_array = Array();
			var send_cnt = 0;
			var chkbox = $(".pay_check");
			for(i=0;i<chkbox.length;i++) {
				if (chkbox[i].checked == true){
					send_array[send_cnt] = chkbox[i].value;
					send_cnt++;
				}
			}
			
			

			$.ajax({
				type: "POST",
				url:  "./withdrawal_batch_run.php",
				cache: false,
				async: false,
				dataType: "text",
				data:  {
					send_array : send_array,
					wallet_addr :  $('#wallet_addr').val(),
					wallet_id :  $('#wallet_id').val(),
					wallet_pw :  $('#wallet_pw').val()
				},
				success: function(data) {
					alert(data);
				}
			});
			
		});
	});

</script>

<style type="text/css">
	xmp {font-family: 'Noto Sans KR', sans-serif;font-size:12px;}
	input[type="radio"] {}
	input[type="radio"] + label{color:#999;}
	input[type="radio"]:checked + label {color:#e50000;font-weight:bold;font-size:14px;}
	table.regTb {width:100%;table-layout:fixed;border-collapse:collapse;}
	table.regTb {border-top:solid 1px #777;}
	table.regTb th,
	table.regTb td {padding:4px 0;border-bottom:solid 1px #ddd;line-height:28px;font-size:12px;}
	table.regTb th {font-weight:normal;font-family:"nngdb";font-size:12px;color:#444;background-color:#f5f5f5;}
	table.regTb td {padding-left:10px;}
	table.regTb input[type="text"],
	table.regTb input[type="password"] {padding:0;padding-left:8px;height:23px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb textarea {padding:0;padding-left:8px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb label {cursor:pointer;}
	table.regTb input[type="radio"] {}
	table.regTb input[type="radio"] + label{color:#999;}
	table.regTb input[type="radio"]:checked + label {color:#e50000;font-weight:bold;}
	span.help {font-size:11px;font-weight:normal;color:rgba(38,103,184,1);}

	.btn_confirm {position:fixed;width:80px;right:10px;top:50%;z-index:9999;}
	.btn_confirm input[type="submit"] {display:block;width:100%;height:45px;line-height:45px;background-color:rgba(230,0,68,0.6);cursor:pointer;border:none;border-radius:5px;}
	.btn_confirm input[type="submit"]:hover {background-color:rgba(230,0,68,1);}
	#status{height:24px;}


	.adminWrp{padding: 0 20px;}
	.adminWrp .total_right{float:right;}

	.adm_wallet{position:relative;margin-right:30px;}
	.adm_wallet span{position:relative;top:-5px;}
	.adm_wallet input{border-radius:10px;margin-bottom:10px;}
	.wd_btn{padding:10px;border:none;background-color:rgb(0,121,211);color:#fff;}

	.td_pbal,.td_amt{font-size:1.2em; font-weight:600;}
	table.regTb tr:hover td{background:papayawhip;}
	.font_red{color:red;font-weight:600};
	.btn2{padding:5px 15px; margin-left:20px; background:#ff3061;color:white;}
</style>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" action="./config_change.php" method="GET">
	상태 : 
	<select name="status" id="status">
		<option value="">전체</option>
		<option <?=$_GET['status'] == 'R' ? 'selected':'';?> value="R">요청</option>
		<option <?=$_GET['status'] == 'Y'? 'selected':'';?> value="Y">승인</option>
		<option <?=$_GET['status'] == 'S'? 'selected':'';?> value="S">대기</option>
		<option <?=$_GET['status'] == 'N'? 'selected':'';?> value="N">불가</option>
	</select>
	<input type="text" name="id" placeholder="id" class="frm_input" value="<?=$_GET['id']?>" />

	<input type="text" name="create_dt" id="create_dt" placeholder="요청일시" class="frm_input" value="<?=$_GET['create_dt']?>" />
	<input type="text" name="update_dt" id="update_dt" placeholder="승인일시" class="frm_input" value="<?=$_GET['update_dt']?>" />

	<input type="submit" class="btn_submit" value="검색" />
</form>
<br><br>

<?php
$ord_array = array('desc','asc'); // 정렬 방법 (내림차순, 오름차순)
$ord_arrow = array('▼','▲'); // 정렬 구분용
$ord = isset($_REQUEST['ord']) && in_array($_REQUEST['ord'],$ord_array) ? $_REQUEST['ord'] : $ord_array[0]; // 지정된 정렬이면 그 값, 아니면 기본 정렬(내림차순)
$ord_key = array_search($ord,$ord_array); // 해당 키 찾기 (0, 1)
$ord_rev = $ord_array[($ord_key+1)%2]; // 내림차순→오름차순, 오름차순→내림차순
?>


<form name="site" method="post" action="" enctype="multipart/form-data" style="margin:0px;">
<div class="adminWrp">
	
<div>
	<button type="button" class="total_right btn_submit btn2" style="padding:5px 15px; margin-left:20px; " onclick="location.href='./delete_db_sol.php?id=change'">초기화</button>
	<span class="total_left">Total : <?=$total_count?></span> 
	<span class="total_right">Total withdrawal BTC : <span class="font_red"><?echo round($total_hap,8)?></span>
	 BTC / <span class="font_red"><?=$total_usd?></span> USD <br> Total transfer Fee : <span class="font_red"><?= round($total_fee,8) ?></span> BTC</span>
	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
        
		<thead>
			<th style="width:3%;">선택</th>
			<th style="width:3%;"><a href="?ord=<?php echo $ord_rev; ?>&ord_word=uid">No <?php echo $ord_arrow[$ord_key]; ?></a></th>
			<th style="width:10%;">아이디 </th>
			<th  style="width:10%;">출금전잔고(V7)</th>
			<!--<th>현재 BTC 수당 잔고</th>-->
			<th style="width:5%;">요청코인</th>
			<th style="width:5%;">전환코인</th>
			<th style="width:10%;">전환요청금액</th>
			<th style="width:10%;">전환수수료</th>
			<th style="width:10%;">전환금액</th>
			<th style="width:10%;">적용 코인시세</th>
			<th style="width:10%;">요청일시</th>
		</thead>

        <tbody>
		<?for ($i=0; $row=sql_fetch_array($list); $i++) {
			?>
			<tr>
				<td ><input type="checkbox" name="paid_BTC[]" value="<?=$row['no']?>" class="pay_check">  </td>
				<td><?=$row['no']?></td>
				<td><?=$row['mb_id']?></td>
				<!--<td><a href="#" onclick="window.open('https://blockchain.info/address/<?=$row['addr']?>','width=800, height=500');"><?=$row['addr']?></a></td>-->
				
				<td><?=$row['account']?></td>
				<td class="td_amt"><?=$row['source']?></td>
				<td class="td_amt"><?=$row['coin']?></td>
				<td class="td_amt" style="color:red"><?=$row['amount']?></td>
				
				<td class="td_amt"><?=$row['fee']?></td>
				<td class="td_amt"><?=$row['exchange']?></td>
				<td class="td_amt"><?=$row['cost']?></td>
				<td style="font-size:11px;letter-spacing:-1px;"><?=$row['od_time']?></td>
			</tr>
		<?}?>
        </tbody>
    </table>
</div>
</form>

<!-- // adminWrp // -->
<?php
$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
if ($pagelist) {
	echo $pagelist;
}

?>

<?
include_once ('./admin.tail.php');
?>

