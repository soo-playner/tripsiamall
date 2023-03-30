<?php
$sub_menu = "800100";
include_once('./_common.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');
include_once(G5_PATH . '/util/package.php');

auth_check($auth[$sub_menu], 'r');

$get_shop_item = get_shop_item();
/* 
$sub_sql = "";
if ($_GET['sst'] == "eth") {
	$sub_sql = " , (mb_eth_point+mb_eth_calc) as eth";
}

if ($_GET['sst'] == "total_fund") {
	$sub_sql = " , (mb_deposit_point + mb_deposit_calc + mb_balance) as total_fund";
}

if ($_GET['sst'] == "deposit_point") {
	$sub_sql = " , (mb_deposit_point) as deposit_point";
}

if ($_GET['sst'] == "mb_bonus_total") {
	$sub_sql = " , ((recom_mining + brecom_mining + brecom2_mining + super_mining)) as mb_bonus_total";
}

if ($_GET['sst'] == "mb_bonus_total_rate") {
	$sub_sql = " , ((recom_mining + brecom_mining + brecom2_mining + super_mining)/mb_rate) as mb_bonus_total_rate";
}

$sql_common = " {$sub_sql} from {$g5['member_table']} ";

if ($_GET['view'] == 'all') {
	$viewmode = 'all';
	$sql_search = " where (1) ";
} else {
	$viewmode = '';
	$sql_search = " where fcm_token != '' and mb_rate > 0 ";
}
if ($stx) {
	$sql_search .= " and ( ";
	switch ($sfl) {
		case 'mb_point':
			$sql_search .= " ({$sfl} >= '{$stx}') ";
			break;
		case 'mb_level':
			$sql_search .= " ({$sfl} = '{$stx}') ";
			break;
		case 'mb_tel':
		case 'mb_hp':
			$sql_search .= " ({$sfl} like '%{$stx}%') ";
			break;

		default:
			$sql_search .= " ({$sfl} like '%{$stx}%') ";
			break;
	}
	$sql_search .= " ) ";
}

if ($_GET['level']) {
	$sql_search .= " and mb_level = " . $_GET['level'];
}
if ($_GET['grade'] > -1) {
	$sql_search .= " and grade = " . $_GET['grade'];
}



if (!$sst) {
	$sst = "mb_datetime, mb_no";
	$sod = "desc";
}

$sql_order = " order by {$sst} {$sod}";
$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$rows = 600;

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
 */

$g5['title'] = '앱/푸쉬관리';
include_once('../admin.head.php');

if ($_GET['view'] == 'all') {
	$viewmode = 'all';
	$sql_search = " where fcm_token != '' ";
} else {
	$viewmode = '';
	$sql_search = " where fcm_token != '' and mb_rate > 0 ";
}

$sql_order = " ORDER BY A.mb_datetime, A.mb_no DESC ";


$sql_ex = "SELECT A.mb_no,a.mb_id,A.mb_name,A.mb_nick,A.grade,A.mb_level,A.mb_rate,A.mb_balance,A.mb_mining_2,A.fcm_token,A.mb_datetime,
B.hash_info,JSON_EXTRACT(B.hash_info, '$.all') AS now_all,
JSON_EXTRACT(C.hash_info, '$.all') AS prev_all
FROM g5_member A  

LEFT JOIN g5_member_info B
	ON A.mb_id = B.mb_id AND B.date = (SELECT MAX(DATE) FROM g5_member_info)
LEFT JOIN g5_member_info C
   ON A.mb_id = C.mb_id AND C.date = (SELECT MAX(date) FROM g5_member_info WHERE DATE NOT IN (SELECT MAX(date) FROM g5_member_info))
";



$sql = " {$sql_ex} {$sql_search} {$sql_order} ";

$result = sql_query($sql);
$total_count = sql_num_rows($result);

$colspan = 17;


/* 레벨 */
$grade = "SELECT grade, count( grade ) as cnt FROM g5_member GROUP BY grade order by grade";
$get_lc = sql_query($grade);

/* 국가 */
/* $nation_sql = "SELECT nation_number, count( nation_number ) as cnt FROM g5_member GROUP BY nation_number";
$nation_row = sql_query($nation_sql);

$blockRec = sql_fetch("select count(mb_block) as cnt from g5_member where mb_block = 1"); */



function mining_bonus_rate($mb_id, $mb_rate)
{
	// $member['recom_mining'] + $member['brecom_mining'] + $member['brecom2_mining'] + $member['super_mining']

	$bonus_total_sql = "SELECT SUM(recom_mining + brecom_mining + brecom2_mining + super_mining) as total FROM g5_member WHERE mb_id = '{$mb_id}' ";
	$bonus_total = sql_fetch($bonus_total_sql)['total'];

	if ($mb_rate > 0) {
		$bonus_rate_per =  Number_format($bonus_total / $mb_rate);
	} else {
		$bonus_rate_per =  0;
	}
	return array($bonus_total, $bonus_rate_per);
}

function active_check($val, $target)
{
	$bool_check = $_GET[$target];
	if ($bool_check == $val) {
		return " active ";
	}
}

function out_check($val)
{
	$bonus_OUT_CALC = $val;

	if ($bonus_OUT_CALC > 100) {
		$class = 'over';
	} else {
		$class = '';
	}
	return "<span class=" . $class . ">" . number_format($bonus_OUT_CALC) . " % </span>";
}

function app_install($val)
{
	if (strlen($val) > 0) {
		$result = "<span class='f_green'><img class='store_icon' src='../img/pngwing.com.png' title='Android'></span>";
	} else {

		$result = "";
	}
	return $result;
}

function diffvalue($val1, $val2, $diffval)
{
	if ($val1 > 0 && $val2 > 0) {

		if ($diffval > 0) {
			$result = "<p class='yesterday'>(<span class='comp plus'><i class='ri-arrow-up-s-fill'></i></span>" . shift_auto($diffval,'eth') . ")</p>";
		} else if ($diffval == 0) {
			$result = "<p class='yesterday'>(<span class='comp'><i class='ri-subtract-line'></i></span>)</p>";
		} else {
			$result = "<p class='yesterday'>(<span class='comp minus'><i class='ri-arrow-down-s-fill'></i></span>" . shift_auto($diffval,'eth') . ")</p>";
		}
	} else {
		$result = '';
	}
	return $result;
}

// 통계수치
/* $stats_sql = "SELECT COUNT(*) as cnt, 
SUM(mb_deposit_point) AS deposit, 
SUM(mb_balance) AS balance,
SUM(mb_save_point) AS pv, 
SUM($mining_target) AS mining, 
SUM(mb_deposit_point + mb_deposit_calc + mb_balance ) AS able_with, 
SUM($mining_target - $mining_amt_target) AS able_mining   
{$sql_common} {$sql_search}";
$stats_result = sql_fetch($stats_sql); */
?>

<style>
	.send_person{
		
	}
	.total {
		background: #555 !important;
		color: white !important;
	}

	.td_mbgrade select {
		min-width: 50px;
		padding: 5px 5px
	}

	.tbl_head02 tbody td {
		padding: 5px;
	}

	.tbl_wrap {
		min-width: 1000px
	}

	/* 관리버튼 */
	.td_mngsmall a {
		border: 1px solid #ccc;
		padding: 3px 10px;
		display: inline-block;
		text-decoration: none;
	}

	.td_mngsmall a:hover {
		background: black;
		border: 1px solid black;
		color: white;
	}

	.f_red {
		color: red;
		font-weight: 600;
	}

	.f_blue {
		color: blue;
		font-weight: 600
	}

	.center {
		text-align: center !important;
	}

	select#sfl {
		padding: 9px 10px;
	}

	#stx {
		padding: 5px;
	}

	.btn_add01 {
		text-align: left;
		margin-bottom: 10px;
	}

	.btn_add01 div {
		display: inline-block;
	}

	.btn_add01 .btn_left {
		margin-left: -20px;
	}

	.btn_add01 .btn_right {
		float: right;
		margin-right: 20px;
	}

	.badge.over {
		position: absolute;
		padding: 2px 5px;
		background: #eee;
		font-size: 12px;
		margin-left: -10px;
		margin-top: 12px;
		display: inline-block;
		font-weight: 600;
		color: black;
	}

	.badge {
		word-break: keep-all
	}

	/* 코멘트영역 */
	.local_ov strong {
		color: red;
		font-weight: 600;
	}

	.local_ov {
		color: #777;
		font-weight: 500;
		line-height: 20px;
	}

	.local_ov a {
		margin-left: 20px;
	}

	.local_ov span {
		margin-left: 10px;
		padding-right: 5px;
	}

	.local_ov .bonus {
		margin-top: 5px;
		border-left: 3px solid green;
		background: white;
		display: inline-block;
		padding: 3px 5px;
		border-radius: 5px;
		color: black;
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
	}

	.local_ov .bonus.mining {
		border-left: 3px solid purple;
		margin-left: 10px;
	}

	/*컬러*/
	.gold {
		background: gold !important
	}

	.green {
		background: green !important;
	}

	.green a {
		color: white !important
	}

	.orange {
		background: #ff4500 !important;
	}

	.orange a {
		color: white !important
	}

	.pink {
		background: #cd36d7 !important;
	}

	.pink a {
		color: white !important
	}

	.purple {
		background: #6f00ff !important;
	}

	.purple a {
		color: white !important
	}

	.blue {
		background: #0260b9 !important;
	}

	.blue a {
		color: white !important
	}

	.black {
		background: #555 !important;
	}

	.black a {
		color: white !important
	}

	/*테이블영역*/
	.grade_icon {
		width: 26px;
		height: 26px;
	}

	.icon {
		width: 26px;
		height: 26px;
		display: inline-block;
		font-size: 18px;
		line-height: 26px;
	}

	.icon i {
		vertical-align: baseline;
	}

	.td_id {
		color: black;
		font-size: 14px;
		padding-left: 6px !important;
		font-weight: 700;
		min-width: 80px;
		font-family: Montserrat, Arial, sans-serif
	}

	.td_name {
		color: gray;
		min-width: 80px;
	}

	.td_mbstat {
		text-align: right;
		padding-right: 6px !important;
		min-width: 70px;
		color: #333
	}

	.td_rate {
		min-width: 40px;
	}

	.td_date {
		min-width: 50px;
	}

	.td_mngsmall {
		min-width: 100px;
	}

	.td_app .store_icon {
		width: 30px;
		height: 30px;
	}

	.td_mining {
		text-align: right;
		padding-right: 5px;
		min-width: 60px;
	}

	.td_mbstat.all {
		color: black;
		font-size: 13px;
		font-weight: 500;
	}

	.td_mbstat.mining {
		color: black;
		font-size: 13px;
		font-weight: 500;
		min-width: 80px;
	}

	.yesterday {
		display: block;
		color: gray;
		margin: 0;
		padding: 0;
		font-size: 11px;
	}

	.comp {
		color: gray;
		font-size: 15px;
	}

	.comp.plus {
		color: red;
	}

	.comp.minus {
		color: blue;
	}
</style>

<link href="https://cdn.jsdelivr.net/npm/remixicon@2.3.0/fonts/remixicon.css" rel="stylesheet">

<div class="local_ov01 local_ov">
	<?= $listall ?>
	<? if ($viewmode == 'all') {
		echo '전체회원 : ';
	} else {
		echo '발송가능회원 : ';
	} ?> <strong><?= number_format($total_count)?></strong> 명 |
	마이닝해시 보유회원만 
	<br>
	- 보낼사용자 선택 => 선택대상회원발송 => 메세지선택 => 발송
	<?
	/* if($member['mb_id'] == 'admin'){
			echo "<div class='bonus'>보너스<span> 보유량 : <strong>".Number_format($stats_result['balance'])."</strong></span> | ";
			echo "<span>출금 가능 : <span class='f_blue'>".Number_format($stats_result['able_with'])." 원  </span></span></div>  ";

			echo "<div class='bonus mining'>마이닝<span>보유량 : <strong>".Number_format($stats_result['mining'],8)." ETH </strong></span> | ";
			echo "<span>출금 가능 : <span class='f_blue'>".Number_format($stats_result['able_mining'],8)." ETH </span></span></div> ";
		} */
	?>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">
	<label for="sfl" class="sound_only">검색대상</label>
	<select name="sfl" id="sfl">
		<option value="mb_id" <?= get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
		<option value="mb_nick" <?= get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option>
		<option value="mb_name" <?= get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
		<option value="mb_hp" <?= get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
		<option value="mb_datetime" <?= get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
		<option value="mb_ip" <?= get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
		<option value="mb_recommend" <?= get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option>
	</select>

	<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
	<input type="text" name="stx" value="<?= $stx ?>" id="stx" required class="required frm_input">
	<input type="submit" class="btn_submit" value="검색">
</form>


<div style="padding:8px 20px 10px;font-size:15px;margin-bottom:10px;float:left">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= G5_THEME_URL ?>/css/scss/custom.css">
	<link rel="stylesheet" href="../css/scss/admin_custom.css">

</div>


<div class="btn_add01 ">
	<div class="btn_left">
		<? if ($viewmode == 'all') { ?>
			<a href="./fcm_memberlist.php">메세지 발송 가능 회원만 보기</a>
		<? } else { ?>
			<a href="./fcm_memberlist.php?view=all">전체 APP 설치 회원보기</a>
		<? } ?>
	</div>

	<div class="btn_right">
		<a class='' id='target_send'>선택 대상 회원 발송</a>
		<!-- <a href="./">전회원 발송</a> -->
	</div>
</div>

<div class="local_desc01 local_desc">
	<p>
		- 총보너스, 마이닝보너스 : 금일 (전일대비증감)
	</p>
</div>

<form name="fcm_msg" id="fcm_msg" action="./fcm_send_proc.php" onsubmit="return fmemberlist_submit(this);" method="post">
	<input type="hidden" name="sst" value="<?= $sst ?>">
	<input type="hidden" name="sod" value="<?= $sod ?>">
	<input type="hidden" name="sfl" value="<?= $sfl ?>">
	<input type="hidden" name="stx" value="<?= $stx ?>">
	<input type="hidden" name="page" value="<?= $page ?>">
	<input type="hidden" name="view" value="<?= $viewmode ?>">
	<input type="hidden" name="token" value="">
	<input type="hidden" name="contents_code" id='contents_code' value="">
	<input type="hidden" name="contents_title" id='contents_title' value="">
	<input type="hidden" name="contents_content" id='contents_content' value="">
	<input type="hidden" name="contents_images" id='contents_images' value="">

	<div class="tbl_head02 tbl_wrap" style="clear:both">
		<table>
			<caption><?= $g5['title']; ?> 목록</caption>

			<thead>
				<tr>
					<th scope="col" rowspan="2" id="mb_list_chk">
						<label for="chkall" class="sound_only">회원 전체</label>
						<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
					</th>
					<th scope="col" rowspan="2" id="" class="">등급</th>
					<th scope="col" rowspan="2" id="mb_list_authcheck"><?= subject_sort_link('mb_level', '', 'desc') ?>직급</a></th>
					<th scope="col" rowspan="2" id="" class=""><?= subject_sort_link('mb_id') ?>아이디</a></th>
					<th scope="col" rowspan="1" id="" class="center"><?= subject_sort_link('mb_name') ?>이름</a></th>


					<th scope="col" rowspan="2" id="" class="gold"><?= subject_sort_link('mb_balance') ?> 수당합계</th>
					<th scope="col" rowspan="2" id="" class="green">
						<?= subject_sort_link('mining') ?> 마이닝보유<br>(<?= $minings[$now_mining_coin] ?>)
					</th>
					<th scope="col" rowspan="2" id="" class=""><?= subject_sort_link('mb_rate') ?>마이닝해시</th>

					<th scope="col" rowspan="2" id="" class="orange"><?= subject_sort_link('mb_bonus_total') ?>메가해시</th>
					<th scope="col" rowspan="2" id="" class="pink"><?= subject_sort_link('mb_bonus_total') ?>제타해시</th>
					<th scope="col" rowspan="2" id="" class="purple"><?= subject_sort_link('mb_bonus_total') ?>제타+해시</th>
					<th scope="col" rowspan="2" id="" class="blue"><?= subject_sort_link('mb_bonus_total') ?>슈퍼해시</th>
					<th scope="col" rowspan="2" id="" class=""><?= subject_sort_link('mb_bonus_total') ?>총보너스해시</th>

					<th scope="col" rowspan="2" id="" class="black"><?= subject_sort_link('mb_bonus_total') ?>마이닝보너스</th>

					<!-- <th scope="col" rowspan="2" id="" class="" ><?= subject_sort_link('mb_bonus_total_rate') ?>보너스율</th> -->
					<!-- <th scope="col" rowspan="2" id="" class="item_title" >상위보유패키지</th> -->

					<th scope="col" rowspan="2" id="" class=''><?= subject_sort_link('fcm_token', '', 'desc') ?>앱설치/푸쉬</a></th>
					<th scope="col" rowspan="2" id="mb_list_mng">관리</th>
				</tr>

				<tr>
					<th scope="col" id="mb_list_join"><?= subject_sort_link('mb_datetime', '', 'desc') ?>닉네임</a></th>
				</tr>

			</thead>

			<tbody>
				<?php
				for ($i = 0; $row = sql_fetch_array($result); $i++) {

					/* $info_sql  = "SELECT hash_info,json_extract(hash_info, '$.all') AS now_all,
					LAG(json_extract(hash_info, '$.all'),-1) OVER (ORDER BY DATE DESC) AS prev_all,
					json_extract(hash_info, '$.mining_total') AS now_mining,
					LAG(json_extract(hash_info, '$.mining_total'),-1) OVER (ORDER BY DATE DESC) AS prev_mining
					from g5_member_info WHERE mb_id = '{$row['mb_id']}' ORDER BY DATE DESC LIMIT 0,1"; */
					
						/* $info_sql  = "SELECT hash_info,json_extract(hash_info, '$.all') AS now_all
						from g5_member_info WHERE mb_id = '{$row['mb_id']}' ORDER BY DATE DESC LIMIT 0,1"; */

						// $info_today = sql_fetch($info_sql);
						
						$info_hash = json_decode($row['hash_info'], true);
						$diff_all = (float)($row['now_all']) - (float)($row['prev_all']);
						// $diff_mining = (float)($info_hash['mining_total']) - (float)($info_today['prev_mining']);

						$bg = 'bg' . ($i % 2);

					// list($total_mining,$total_mining_rate) = mining_bonus_rate($row['mb_id'],$row['mb_rate'])
					
				?>


					<tr class="<?= $bg; ?>">
						<td headers="mb_list_chk" class="td_chk" rowspan="2">
							<input type="hidden" name="chk_id[<?= $i ?>]" value="<?= $row['mb_id'] ?>" id="chk_<?= $i ?>">
							<label for="chk_<?= $i; ?>" class="sound_only"><?= get_text($row['mb_name']); ?> <?= get_text($row['mb_nick']); ?>님</label>
							<input type="checkbox" name="chk[]" value="<?= $i ?>" id="chk_<?= $i ?>">
						</td>

						<td headers="mb_list_id" rowspan="2" class="">
							<? echo "<img src='/img/" . $row['grade'] . ".png' class='grade_icon'>"; ?>
							<div class='badge over'><?= $row['grade'] ?></div>
						</td>
						<td headers="mb_list_member" class="td_mbgrade" rowspan="2">
							<span class='icon'><?= user_icon($row['mb_id'], 'icon') ?></span>
							<!-- <?= get_member_level_select("mb_level[$i]", 0, $member['mb_level'], $row['mb_level']) ?> -->
						</td>

						<td rowspan="2" class="td_id <?if($row['mb_divide_date'] != ''){echo 'red';}?>"><?= $row['mb_id'] ?></td>
						<td rowspan="1" class="td_name"><?= get_text($row['mb_name']); ?></td>

						<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><?= Number_format($row['mb_balance']) ?></td>
						<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><?= shift_auto_zero($row[$mining_target], 'eth') ?> </td>
						<td headers="mb_list_auth" class="td_mining" rowspan="2">
							<input type='hidden' name='mb_rate[]' value='<?= $row['mb_rate'] ?>'>
							<?= Number_format($row['mb_rate']) ?>
						</td>

						<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><?= $info_hash['mega'] ?></td>
						<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><?= $info_hash['zeta'] ?></td>
						<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><?= $info_hash['zetaplus'] ?></td>
						<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><?= $info_hash['super'] ?></td>

						<td headers="mb_list_auth" class="td_mbstat all" rowspan="2">

							<input type='hidden' name='all_hash[]' value='<?= $info_hash['all'] ?>'>
							<input type='hidden' name='all_diff[]' value='<?= $diff_all ?>'>

							<?= $info_hash['all'] ?>
							<?= diffvalue($row['now_all'], $row['prev_all'], $diff_all) ?>
						</td>
						<td headers="mb_list_auth" class="td_mbstat mining" rowspan="2">
							<input type='hidden' name='mining_total[]' value='<?= $info_hash['mining_total'] ?>'>
							<!-- <input type='hidden' name='mining_total_diff[]' value='<?= $diff_mining ?>'> -->

							<?= $info_hash['mining_total'] ?>
						</td>

						<td headers="mb_list_lastcall" rowspan="2" class="td_app  center"><?= app_install($row['fcm_token']) ?></td>
						<td headers="mb_list_mng" rowspan="2" class="td_mngsmall" style="width:100px;">
							<a class='btn send_person' >푸쉬/문자 발송</a>
						</td>

					</tr>
					<tr class="<?= $bg; ?>">
						<td><?= $row['mb_nick'] ?></td>
					</tr>

				<?php
				}
				if ($i == 0)
					echo "<tr><td colspan=\"" . $colspan . "\" class=\"empty_table\">자료가 없습니다.</td></tr>";
				?>
			</tbody>
		</table>
	</div>

	<style>
		.search_contents {
			display:none;
			position: absolute;
			background-color: ghostwhite;
			z-index: 1000;
			box-shadow: 1px 1px 10px rgba(0,0,0,0.75);
			width:320px;
			height:405px;
			top:Calc(50vh - 300px);
			left:Calc(50vw - 100px);
		}
		.search_contents.active{display:block;}
		.search_contents .title{text-align: center;line-height: 40px;font-size: 13px;background:#666;color:white}

		.search_result {
			right: 0;
			top: 0;
			width: 300px;
			height: 300px;
			text-align: center;
			padding: 10px;
			transition: all 2s;
			overflow: auto;
		}
		.search_result .result_contents{background:white;display:block;margin:5px 0;cursor: pointer;padding:10px 0;border:1px solid white;box-shadow:1px 1px 2px rgba(0,0,0,0.5)}
		.search_result .result_contents:hover{border:1px solid royalblue}
		.search_result .result_contents.active{background:royalblue;border:1px solid royalblue;color:white}

		.cabinet_inner{display:none;text-align: left;padding:10px;background:#eee;font-size:11px;}
		.cabinet_inner .con_title{display: block;}
		.cabinet_inner .frm_input{width:98%;font-size:12px;padding:0 5px;}
		.cabinet_inner .textarea{height:50px;margin:5px 0;}
		.cabinet_inner .guide{padding:10px 5px;margin-top:5px;}
		.cabinet_inner code{color:#666}
		.cabinet_inner.active{display:block;}

		.search_contents .btn{display: inline-block;width:49%;text-align: center;padding:10px 0;background:white;margin-bottom:10px;}

		.search_contents .send_btn{}
		.search_contents .send_btn:hover{background:#FECE00;}
		.search_contents .cancle_btn{background:#eee;}
	</style>

	<div class="search_contents">
		<div class='title'>전송할 메세지 선택</div>
		<div class="search_result" id="search_result" style='overflow:scroll'></div>

		<div class="btn cancle_btn">취소</div>
		<div class="btn send_btn">보내기</div>
	</div>

	<div class="btn_list01 btn_list">
		<!-- <input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
	<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value"> -->
	</div>

</form>

<!-- <?= get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?' . $qstr . '&amp;page='); ?> -->

<script>
	function fmemberlist_submit(f) {
		if (!is_checked("chk[]")) {
			alert(" 보내실 회원을 한명 이상 선택하세요.");
			return false;
		}

		if (document.pressed == "선택삭제") {
			if (!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
				return false;
			}
		}
		return true;
	}

	$("#target_send").on('click', function() {
		$target = $("#fcm_msg");
		console.log($target);

		search_contents(this);

	});

	$('.send_person').on('click',function(){
		
	});

	function search_contents($val) {
		var id_num = 0;

		$('.search_contents').addClass('active');

		$.ajax({
			type: "POST",
			url: "./search_contents.php",
			cache: false,

			success: function(res) {
				$("#search_result").html(res);

				$('.result_contents').on('click', function() {
					$('.result_contents').removeClass('active');
					$(this).addClass('active');

					$('.cabinet_inner').removeClass('active');
					$(this).next().addClass('active');

					id_num = $(this).data('id');
					console.log(id_num);
					$('#contents_code').val(id_num);
				});

				$('.cancle_btn').on('click',function(){
					console.log('cancle');

					$("#search_result").empty();
					$(".search_contents").removeClass('active');
				});

				$('.send_btn').on('click',function(){
					if(id_num == 99){
						$("#contents_title").val($("#con_title").val());
						$("#contents_content").val($("#con_contents").val());
						$("#contents_images").val($("#con_images").val());
					}else{
						$("#contents_title").val('');
						$("#contents_content").val('');
						$("#contents_images").val('');
					}

					$("#fcm_msg").submit();
				});
			}
		});
	}

	function level_search(param) {
		$('#search_bar #level').val(param);
		//console.log($('#search_bar #level').val());
		$('#search_bar').submit();
	}

	function grade_search(param) {
		$('#search_bar #grade').val(param);
		//console.log($('#search_bar #level').val());
		$('#search_bar').submit();
	}

	function nation_search(param) {
		$('#search_bar #nation').val(param);
		//console.log($('#search_bar #nation').val());
		$('#search_bar').submit();
	}
</script>

<?php
include_once('../admin.tail.php');
?>