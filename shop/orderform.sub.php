<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

require_once(G5_SHOP_PATH.'/settle_'.$default['de_pg_service'].'.inc.php');
require_once(G5_SHOP_PATH.'/settle_kakaopay.inc.php');

// 결제대행사별 코드 include (스크립트 등)
require_once(G5_SHOP_PATH.'/'.$default['de_pg_service'].'/orderform.1.php');
if($is_kakaopay_use) {
	require_once(G5_SHOP_PATH.'/kakaopay/orderform.1.php');
}
/*## only ################################################*/
/*## only ################################################*/
if ($_SERVER['REMOTE_ADDR']=="119.203.32.49" || $_SERVER['REMOTE_ADDR']=="119.203.32.242") {
	echo "<p style='color:#ff6600;'>".$order_action_url."</p>";	
}
/*@@End. only #####*/
/*@@End. only #####*/
?>
<style>
	html, body {background-color: #fff !important;}
	.tbl_head01 thead th {background:#fff;border-top:none;border-bottom:1px solid #000;padding: 5px 0;}
	.td_numbig {color:#ef0000; font-weight:bold;}
	.tbl_head01 td {border:0px;}
	.tbl_head01 tbody {border-bottom:1px solid #000;}
	.btn_del {cursor:pointer;}
	.Grp {min-height: 600px;}
	#sod_frm {display:none;}
</style>
<h1>Payment in progress...</h1>
	
<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
	<input type="hidden" name="payMethod" value="<?php echo $_GET['payMethod']?>" >
<div id="sod_frm">
	<!-- 주문상품 확인 시작 { -->
	<!--p>주문하실 상품을 확인하세요...</p-->

	<div class="tbl_head01 tbl_wrap">
		<table id="sod_list">
			<colgroup>
				<col style="width:90px;">
				<col style="width:auto;">
				<col style="width:90px;">
				<col style="width:90px;">
			</colgroup>
			<thead>
				<tr>
					<th scope="col"> </th>
					<th scope="col" style="text-align:left;">Product Description</th>
					<th scope="col">Price</th>
					<th scope="col">Quantity</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$tot_point = 0;
		$tot_sell_price = 0;

		$goods = $goods_it_id = "";
		$goods_count = -1;

		// $s_cart_id 로 현재 장바구니 자료 쿼리
		$sql = " select a.ct_id,
						a.it_id,
						a.it_name,
						b.it_basic,
						a.ct_price,
						a.ct_point,
						a.ct_qty,
						a.ct_status,
						a.ct_send_cost,
						a.it_sc_type,
						b.ca_id,
						b.ca_id2,
						b.ca_id3,
						b.it_notax
				   from {$g5['g5_shop_cart_table']} a left join {$g5['g5_shop_item_table']} b on ( a.it_id = b.it_id )
				  where a.od_id = '$s_cart_id'
					and a.ct_select = '1' ";
		$sql .= " group by a.it_id ";
		$sql .= " order by a.it_id desc ";
		$result = sql_query($sql);

		$good_info = '';
		$it_send_cost = 0;
		$it_cp_count = 0;

		$comm_tax_mny = 0; // 과세금액
		$comm_vat_mny = 0; // 부가세
		$comm_free_mny = 0; // 면세금액
		$tot_tax_mny = 0;

		$sum_qty = 0;

		for ($i=0; $row=sql_fetch_array($result); $i++)
		{
			$sum_qty += $sum['qty'];
			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((ct_price + io_price) * ct_qty))) as price,
							SUM(ct_point * ct_qty) as point,
							SUM(ct_qty) as qty
						from {$g5['g5_shop_cart_table']}
						where it_id = '{$row['it_id']}'
						  and od_id = '$s_cart_id' ";
			$sum = sql_fetch($sql);

			if (!$goods)
			{
				//$goods = addslashes($row[it_name]);
				//$goods = get_text($row[it_name]);
				$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $row['it_name']);
				$goods_it_id = $row['it_id'];
			}
			$goods_count++;

			// 에스크로 상품정보
			if($default['de_escrow_use']) {
				if ($i>0)
					$good_info .= chr(30);
				$good_info .= "seq=".($i+1).chr(31);
				$good_info .= "ordr_numb={$od_id}_".sprintf("%04d", $i).chr(31);
				$good_info .= "good_name=".addslashes($row['it_name']).chr(31);
				$good_info .= "good_cntx=".$row['ct_qty'].chr(31);
				$good_info .= "good_amtx=".$row['ct_price'].chr(31);
			}

			$image = get_it_image($row['it_id'], 50, 50);

			$it_name = '<b>' . stripslashes($row['it_name']) . '</b>';
			$it_options = print_item_options($row['it_id'], $s_cart_id);
			// if($it_options) {
			//     $it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			// }

			// 복합과세금액
			if($default['de_tax_flag_use']) {
				if($row['it_notax']) {
					$comm_free_mny += $sum['price'];
				} else {
					$tot_tax_mny += $sum['price'];
				}
			}

			$point      = $sum['point'];
			$sell_price = $sum['price'];

			// 쿠폰
			if($is_member) {
				$cp_button = '';
				$cp_count = 0;

				$sql = " select cp_id
							from {$g5['g5_shop_coupon_table']}
							where mb_id IN ( '{$member['mb_id']}', '전체회원' )
							  and cp_start <= '".G5_TIME_YMD."'
							  and cp_end >= '".G5_TIME_YMD."'
							  and cp_minimum <= '$sell_price'
							  and (
									( cp_method = '0' and cp_target = '{$row['it_id']}' )
									OR
									( cp_method = '1' and ( cp_target IN ( '{$row['ca_id']}', '{$row['ca_id2']}', '{$row['ca_id3']}' ) ) )
								  ) ";
				$res = sql_query($sql);

				for($k=0; $cp=sql_fetch_array($res); $k++) {
					if(is_used_coupon($member['mb_id'], $cp['cp_id']))
						continue;

					$cp_count++;
				}

				if($cp_count) {
					$cp_button = '<button type="button" class="cp_btn btn_frmline">적용</button>';
					$it_cp_count++;
				}
			}

			// 배송비
			switch($row['ct_send_cost'])
			{
				case 1:
					$ct_send_cost = '착불';
					break;
				case 2:
					$ct_send_cost = '무료';
					break;
				default:
					$ct_send_cost = '선불';
					break;
			}

			// 조건부무료
			if($row['it_sc_type'] == 2) {
				$sendcost = get_item_sendcost($row['it_id'], $sum['price'], $sum['qty'], $s_cart_id);

				if($sendcost == 0)
					$ct_send_cost = '무료';
			}
			
		?>

		<tr>
			<td class="sod_img"><?php echo $image; ?></td>
			<td>
				<input type="hidden" name="it_id[<?php echo $i; ?>]"    value="<?php echo $row['it_id']; ?>">
				<input type="hidden" name="it_name[<?php echo $i; ?>]"  value="<?php echo get_text($row['it_name']); ?>">
				<input type="hidden" name="it_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
				<input type="hidden" name="cp_id[<?php echo $i; ?>]" value="">
				<input type="hidden" name="cp_price[<?php echo $i; ?>]" value="0">
				<?php if($default['de_tax_flag_use']) { ?>
				<input type="hidden" name="it_notax[<?php echo $i; ?>]" value="<?php echo $row['it_notax']; ?>">
				<?php } ?>
				<?php echo $it_name; ?><br>
				<?php echo $row['it_basic']; ?>
			</td>
			
			<!-- <td class="td_numbig"><?php echo number_format($row['ct_price']); ?></td> -->
			<!--td class="td_mngsmall"><?php echo $cp_button; ?></td-->
			<td class="td_numbig"><span class="total_price"><?php echo number_format($sell_price); ?></span></td>
			<td class="td_num"><?php echo number_format($sum['qty']); ?></td>
			<!--td class="td_numbig"><?php echo number_format($point); ?></td-->
			<!--td class="td_dvr"><?php echo $ct_send_cost; ?></td-->
		</tr>

		<?php
			$tot_point      += $point;
			$tot_sell_price += $sell_price;
		} // for 끝

		if ($i == 0) {
			//echo '<tr><td colspan="7" class="empty_table">장바구니에 담긴 상품이 없습니다.</td></tr>';
			alert('Cart is empty.', G5_URL.'/new/purchase_hash_full.php');
		} else {
			// 배송비 계산
			$send_cost = get_sendcost($s_cart_id);
		}

		// 복합과세처리
		if($default['de_tax_flag_use']) {
			$comm_tax_mny = round(($tot_tax_mny + $send_cost) / 1.1);
			$comm_vat_mny = ($tot_tax_mny + $send_cost) - $comm_tax_mny;
		}

		$sql_coin = "select * from coin_cost";
		$row_coin = sql_fetch($sql_coin);

		?>
		</tbody>
		</table>
	</div>

	<?php if ($goods_count) $goods .= ' 외 '.$goods_count.'건'; ?>
	<!-- } 주문상품 확인 끝 -->

	<!-- 주문상품 합계 시작 { -->

	<?php $tot_price = $tot_sell_price + $send_cost; // 총계 = 주문상품금액합계 + 배송비 ?>
	<div style="text-align:right;">
		<strong>
			Subtotal
			<span style="color:#ef0000;"> $<?php echo number_format($tot_price);   ?></span>
			<span>   &nbsp;&nbsp;&nbsp; &nbsp; <?php echo $sum_qty+1;?> items <span>
		</strong>
	</div>
	<!-- } 주문상품 합계 끝 -->

	<input type="hidden" name="od_price"    value="<?php echo $tot_sell_price; ?>">
	<input type="hidden" name="org_od_price"    value="<?php echo $tot_sell_price; ?>">
	<input type="hidden" name="od_send_cost" value="<?php echo $send_cost; ?>">
	<input type="hidden" name="od_send_cost2" value="0">
	<input type="hidden" name="item_coupon" value="0">
	<input type="hidden" name="od_coupon" value="0">
	<input type="hidden" name="od_send_coupon" value="0">
	<input type="hidden" name="good_mny" value="0">

	<?php
	// 결제대행사별 코드 include (결제대행사 정보 필드)
	require_once(G5_SHOP_PATH.'/'.$default['de_pg_service'].'/orderform.2.php');

	if($is_kakaopay_use) {
		require_once(G5_SHOP_PATH.'/kakaopay/orderform.2.php');
	}
	?>

<?
/*## skin ################################################*/
/*## skin ################################################*/
/*## skin ################################################*/
include_once(G5_SHOP_PATH."/orderform.sub.mbid.php");
## orderform.sub.bsc.php //기본
## orderform.sub.mbid.php // 9레벨 이상 다른회원아이디 주문 가능 /  현금, 매장카드 주문추가
/*@@End. skin #####*/
/*@@End. skin #####*/
/*@@End. skin #####*/
?>

