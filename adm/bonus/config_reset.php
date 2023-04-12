<?php
$sub_menu = "900120";
include_once('./_common.php');

$g5['title'] = "데이터 초기화 설정";

include_once(G5_ADMIN_PATH . '/admin.head.php');
?>

<link rel="stylesheet" href="/adm/css/switch.css">

<style type="text/css">
	/* xmp {font-family: 'Noto Sans KR', sans-serif; font-size:12px;} */
	.adminWrp {
		padding: 30px;
		min-height: 50vh
	}

	input[type="radio"] {}

	input[type="radio"]+label {
		color: #999;
	}

	input[type="radio"]:checked+label {
		color: #e50000;
		font-weight: bold;
		font-size: 14px;
	}

	table.regTb {
		width: 100%;
		table-layout: fixed;
		bdata_test-collapse: collapse;
	}

	table.regTb th,
	table.regTb td {
		line-height: 28px;
	}

	table.regTb th {
		padding: 6px 0;
		bdata_test: 1px solid #d1dee2;
		background: #e5ecef;
		color: #383838;
		letter-spacing: -0.1em;
	}

	table.regTb td {
		padding: 8px 0;
		padding-left: 10px;
		bdata_test-bottom: solid 1px #ddd;
		bdata_test-right: solid 1px #ddd;
	}

	table.regTb input[type="text"],
	table.regTb input[type="password"] {
		padding: 0;
		padding-left: 8px;
		height: 23px;
		line-height: 23px;
		bdata_test: solid 1px #ccc;
		background-color: #f9f9f9;
	}

	table.regTb textarea {
		padding: 0;
		padding-left: 8px;
		line-height: 23px;
		bdata_test: solid 1px #ccc;
		background-color: #f9f9f9;
	}

	table.regTb label {
		cursor: pointer;
	}

	table.regTb input[type="radio"] {}

	table.regTb input[type="radio"]+label {
		color: #999;
	}

	table.regTb input[type="radio"]:checked+label {
		color: #e50000;
		font-weight: bold;
	}

	tfoot {
		clear: both;
		display: table-footer-group;
		vertical-align: middle;
		bdata_test-color: inherit;
	}

	span.help {
		font-size: 11px;
		font-weight: normal;
		color: rgba(38, 103, 184, 1);
	}

	.name {
		background: #222437;
		color: white;
		font-weight: 900
	}

	.text-center {
		text-align: center !important;
	}

	.currency {
		font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
		font-size: 16px;
		font-weight: 900;
		letter-spacing: 1px;
		text-indent: 20%;
	}

	.currency-right {
		position: relative;
		float: right;
		right: 25px;
	}

	.btn_ly {
		width: 50%;
		min-height: 80px;
		display: block;
		margin: 20px auto;
		text-align: right;
	}
	
</style>

<div class="local_desc01 local_desc">
	<p>
		- 수당 초기화는 설정을 제외한 수당지급 로그,기록 관련 데이터등을 초기화<br>
		- 회원및구매내역 초기화는 회원 보유 잔고,포인트,직급 등을 초기화하고 구매내역,상품등을 초기화합니다.<span class='red'> ※관리자제외</span><br>
		- 입출금 초기화는 설정을 제외한 입금내역,출금내역 등을 초기화합니다.<br>
		
		- 테스트환경생성은 전회원 <strong>1,000 usdt 지급 하고, 당일기준 test5~test30까지의 p3 상품을</strong> 구매처리합니다.<span class='red'> ※관리자제외</span>
	</p>
</div>

<form name="frmnewwin" action="./config_reset.proc.php" onsubmit="return frmnewwin_check(this);" method="post">
	<input type="hidden" name="w" value="<?php echo $w; ?>">
	<div class="tbl_frm01 tbl_wrap">
		<table>
			<caption><?php echo $g5['title']; ?></caption>
			<colgroup>
				<col class="grid_3">
				<col>
			</colgroup>
			<tbody>


				<tr>
					<th scope="row"><label for="nw_soodang_reset"> 수당 초기화<strong class="sound_only"> 필수</strong></label></th>
					<td>
						<p style="padding:0;"><input type="checkbox" id="nw_soodang_reset" name="nw_soodang_reset" <?if($nw['nw_soodang_reset']=='Y' ) {echo "checked" ;}?>/><label for="nw_soodang_reset"><span class="ui"></span><span class="nw_soodang_reset_txt">사용 설정</span></label></p>
					</td>
					<td>
					수당 초기화는 설정을 제외한 수당지급 로그,기록 관련 데이터등을 초기화
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="nw_member_reset"> 회원포인트내역 초기화 <strong class="sound_only"> 필수</strong></label></th>
					<td>
						<p style="padding:0;"><input type="checkbox" id="nw_member_reset" name="nw_member_reset" <?if($nw['nw_member_reset']=='Y' ) {echo "checked" ;}?>/><label for="nw_member_reset"><span class="ui"></span><span class="nw_member_reset_txt">사용 설정</span></label></p>
					</td>
					<td>회원및구매내역 초기화는 회원 보유 잔고,포인트,직급 등을 초기화하고 구매내역,상품등을 초기화합니다.</td>
				</tr>
				<tr>
					<th scope="row"><label for="nw_order_reset"> 구매내역 초기화 <strong class="sound_only"> 필수</strong></label></th>
					<td>
						<p style="padding:0;"><input type="checkbox" id="nw_order_reset" name="nw_order_reset" <?if($nw['nw_order_reset']=='Y' ) {echo "checked" ;}?>/><label for="nw_order_reset"><span class="ui"></span><span class="nw_order_reset_txt">사용 설정</span></label></p>
					</td>
					<td>회원 구매/결제내역, 하부매출 정보등을 초기화합니다.</td>
				</tr>
				<tr>
					<th scope="row"><label for="nw_asset_reset"> 입출금 내역 초기화<strong class="sound_only"> 필수</strong></label></th>
					<td>
						<p style="padding:0;"><input type="checkbox" id="nw_asset_reset" name="nw_asset_reset" <?if($nw['nw_asset_reset']=='Y' ) {echo "checked" ;}?>/><label for="nw_asset_reset"><span class="ui"></span><span class="nw_asset_reset_txt">사용 설정</span></label></p>
					</td>
					<td>
					입출금 초기화는 설정을 제외한 입금내역,출금내역 등을 초기화합니다.
					</td>

				</tr>
				<!-- <tr>
					<th scope="row"><label for="nw_binary_reset"> 후원 조직도 초기화<strong class="sound_only"> 필수</strong></label></th>
					<td>
						<p style="padding:0;"><input type="checkbox" id="nw_binary_reset" name="nw_binary_reset" <?if($nw['nw_binary_reset']=='Y' ) {echo "checked" ;}?>/><label for="nw_binary_reset"><span class="ui"></span><span class="nw_binary_reset_txt">사용 설정</span></label></p>
					</td>

				</tr>
 				-->
				
				<tr>
					<th scope="row"><label for="nw_data_test"> 테스트환경 생성 <strong class="sound_only"> 필수</strong></label></th>
					<td>
						<p style="padding:0;"><input type="checkbox" id="nw_data_test" name="nw_data_test" <?if($nw['nw_data_test']=='Y' ) {echo "checked" ;}?>/><label for="nw_data_test"><span class="ui"></span><span class="nw_data_test_txt">사용 설정</span></label></p>
					</td>
					<td>전회원 <strong>3,000 usdt 지급 하고, 당일기준 test5~test30까지의 p3 상품을</strong> 구매처리합니다.</td>
				</tr>

			</tbody>
		</table>
	</div>

	<div class="btn_confirm01 btn_confirm" style="margin-top:30px;">
		<input type="submit" value="확인" class="btn_submit" accesskey="s">
	</div>
</form>

<script>
	$(document).ready(function() {

		$('#nw_soodang_reset').on('click', function() {
			if ($('#nw_soodang_reset').is(":checked")) {
				$('.nw_soodang_reset_txt').html('사용함');
			} else {
				$('.nw_soodang_reset_txt').html('사용안함');
			}
		});

		$('#nw_member_reset').on('click', function() {
			if ($('#nw_member_reset').is(":checked")) {
				$('.nw_member_reset_txt').html('사용함');
			} else {
				$('.nw_member_reset_txt').html('사용안함');
			}
		});

		$('#nw_order_reset').on('click', function() {
			if ($('#nw_order_reset').is(":checked")) {
				$('.nw_order_reset_txt').html('사용함');
			} else {
				$('.nw_order_reset_txt').html('사용안함');
			}
		});

		$('#nw_asset_reset').on('click', function() {
			if ($('#nw_asset_reset').is(":checked")) {
				$('.nw_asset_reset_txt').html('사용함');
			} else {
				$('.nw_asset_reset_txt').html('사용안함');
			}
		});

		$('#nw_data_test').on('click', function() {
			if ($('#nw_data_test').is(":checked")) {
				$('.nw_data_test_txt').html('사용함');
			} else {
				$('.nw_data_test_txt').html('사용안함');
			}
		});

		$('#nw_data_del').on('click', function() {
			if ($('#nw_data_del').is(":checked")) {
				$('.nw_data_del_txt').html('사용함');
			} else {
				$('.nw_data_del_txt').html('사용안함');
			}
		});

		$('#nw_mining_del').on('click', function() {
			if ($('#nw_mining_del').is(":checked")) {
				$('.nw_mining_del_txt').html('사용함');
			} else {
				$('.nw_mining_del_txt').html('사용안함');
			}
		});

		$('#nw_binary_del').on('click', function() {
			if ($('#nw_binary_del').is(":checked")) {
				$('.nw_binary_del_txt').html('사용함');
			} else {
				$('.nw_binary_del_txt').html('사용안함');
			}
		});
	});


	function frmnewwin_check(f) {
		// console.log($('#nw_data_reset').is(":checked") + ' / ' + $('#nw_data_test').is(":checked"));

		if ($('#nw_data_test').is(":checked")) {
			if (confirm('테스트 데이터를 생성 하시겠습니까?')) {} else {
				return false;
			}
		}

		if ($('#nw_data_del').is(":checked")) {
			if (confirm('테스트용 회원 데이터를 생성 하시겠습니까? 되돌릴수없습니다.')) {} else {
				return false;
			}
		}
	}
</script>

<?php
include_once(G5_ADMIN_PATH . '/admin.tail.php');
?>