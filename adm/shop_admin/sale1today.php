<?php
$sub_menu = '500110';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $date);

$g5['title'] = "$date 일 매출현황";
include_once (G5_ADMIN_PATH.'/admin.head.php');
if($_GET['ord']!=null && $_GET['ord_word']!=null){
	$sql_ord = "order by ".$_GET['ord_word']." ".$_GET['ord'];
}

$sql = " select *,
                (od_cart_price + od_send_cost ) as orderprice,
                (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
				 from {$g5['g5_shop_order_table']}
				where SUBSTRING(od_time,1,10) = '$date'
            ";

if($sql_ord){

	$sql .=$sql_ord;
}
else{
	$sql .= " order by od_id desc";
}
$result = sql_query($sql);
?>
<?php
$ord_array = array('desc','asc'); // 정렬 방법 (내림차순, 오름차순)
$ord_arrow = array('▼','▲'); // 정렬 구분용
$ord = isset($_REQUEST['ord']) && in_array($_REQUEST['ord'],$ord_array) ? $_REQUEST['ord'] : $ord_array[0]; // 지정된 정렬이면 그 값, 아니면 기본 정렬(내림차순)
$ord_key = array_search($ord,$ord_array); // 해당 키 찾기 (0, 1)
$ord_rev = $ord_array[($ord_key+1)%2]; // 내림차순→오름차순, 오름차순→내림차순
?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <thead>
    <tr>
        <th scope="col"><a href="?ord=<?php echo $ord_rev; ?>&ord_word=od_id&date=<?echo $date;?>">주문번호No<?php echo $ord_arrow[$ord_key]; ?></a></th>
        <th scope="col">주문자</th>
        <th scope="col"><a href="?ord=<?php echo $ord_rev; ?>&ord_word=orderprice&date=<?echo $date;?>">주문합계<?php echo $ord_arrow[$ord_key]; ?></a></th>
        <th scope="col">쿠폰</th>
		<!-- ##start##  ##### -->
        <th scope="col">현금</th>
        <th scope="col">매장카드</th>
		<!-- ##end##  ## -->
        <th scope="col">무통장</th>
        <th scope="col">가상계좌</th>
        <th scope="col">계좌이체</th>
        <th scope="col">카드입금</th>
        <th scope="col">휴대폰</th>
        <!-- // <th scope="col">PV입금</th> // -->
		<!-- ##start##  ##### -->
		<th scope="col"><a href="?ord=<?php echo $ord_rev; ?>&ord_word=pv&date=<?echo $date;?>">PV<?php echo $ord_arrow[$ord_key]; ?></a></th>
		<th scope="col">BV</th>
		<!-- ##end##  ## -->
        <th scope="col">주문취소</th>
        <th scope="col">미수금</th>
    </tr>
    </thead>
    <tbody>
    <?php
    unset($tot);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        if ($row['mb_id'] == '') { // 비회원일 경우는 주문자로 링크
            $href = '<a href="./orderlist.php?sel_field=od_name&amp;search='.$row['od_name'].'">';
        } else { // 회원일 경우는 회원아이디로 링크
            $href = '<a href="./orderlist.php?sel_field=mb_id&amp;search='.$row['mb_id'].'">';
        }

        $receipt_mcard = $receipt_bank = $receipt_card = $receipt_vbank = $receipt_iche = $receipt_hp = 0;
		/*
        if($row['od_settle_case'] == '현금')
            $receipt_cash = $row['od_receipt_price'];
		*/
        if($row['od_settle_case'] == '매장카드')
            $receipt_mcard = $row['od_receipt_price'];
        if($row['od_settle_case'] == '무통장')
            $receipt_bank = $row['od_receipt_price'];
        if($row['od_settle_case'] == '가상계좌')
            $receipt_vbank = $row['od_receipt_price'];
        if($row['od_settle_case'] == '계좌이체')
            $receipt_iche = $row['od_receipt_price'];
        if($row['od_settle_case'] == '휴대폰')
            $receipt_hp = $row['od_receipt_price'];
        if($row['od_settle_case'] == '신용카드')
            $receipt_card = $row['od_receipt_price'];

    ?>
        <tr>
            <td class="td_alignc"><a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>"><?php echo $row['od_id']; ?></a></td>
            <td class="td_name"><?php echo $href; ?><?php echo $row['od_name']; ?></a></td>
            <td class="td_numsum"><?php echo number_format($row['orderprice']); ?></td>
            <td class="td_numcoupon"><?php echo number_format($row['couponprice']); ?></td>
			<!-- ##start##  ##### -->
            <td class="td_numincome"><?php echo number_format($row['od_receipt_cash']); ?></td>
            <td class="td_numincome"><?php echo number_format($receipt_mcard); ?></td>
			<!-- ##end##  ## -->
            <td class="td_numincome"><?php echo number_format($receipt_bank); ?></td>
            <td class="td_numincome"><?php echo number_format($receipt_vbank); ?></td>
            <td class="td_numincome"><?php echo number_format($receipt_iche); ?></td>
            <td class="td_numincome"><?php echo number_format($receipt_card); ?></td>
            <td class="td_numincome"><?php echo number_format($receipt_hp); ?></td>
            <!-- // <td class="td_numincome"><?php echo number_format($row['od_receipt_point']); ?></td> // -->
			<!-- ##start##  ##### -->
			<td class="td_numincome"><?php echo number_format($row['pv']); ?></td>
			<td class="td_numincome"><?php echo number_format($row['bv']); ?></td>
			<!-- ##end##  ## -->
            <td class="td_numcancel1"><?php echo number_format($row['od_cancel_price']); ?></td>
            <td class="td_numrdy"><?php echo number_format($row['od_misu']); ?></td>
        </tr>
    <?php
        $tot['orderprice']    += $row['orderprice'];
        $tot['ordercancel']   += $row['od_cancel_price'];
        $tot['coupon']        += $row['couponprice'] ;
		//$tot['receipt_cash']  += $receipt_cash;
		/*##  ################################################*/
        $tot['receipt_mcard']  += $receipt_mcard;
		/*@@End.  #####*/
        $tot['receipt_bank']  += $receipt_bank;
        $tot['receipt_vbank'] += $receipt_vbank;
        $tot['receipt_iche']  += $receipt_iche;
        $tot['receipt_card']  += $receipt_card;
        $tot['receipt_hp']    += $receipt_hp;
        $tot['receipt_point'] += $row['od_receipt_point'];
		/*##  ################################################*/
        $tot['od_receipt_cash'] += $row['od_receipt_cash'];
        $tot['pv'] += $row['pv'];
        $tot['bv'] += $row['bv'];
		/*@@End.  #####*/
        $tot['misu']          += $row['od_misu'];
    }

    if ($i == 0) {
        echo '<tr><td colspan="12" class="empty_table">자료가 없습니다</td></tr>';
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="2">합 계</td>
        <td><?php echo number_format($tot['orderprice']); ?></td>
        <td><?php echo number_format($tot['coupon']); ?></td>
		<!-- ##start##  ##### -->
        <td><?php echo number_format($tot['od_receipt_cash']); ?></td>
        <td><?php echo number_format($tot['receipt_mcard']); ?></td>
		<!-- ##end##  ## -->

        <td><?php echo number_format($tot['receipt_bank']); ?></td>
        <td><?php echo number_format($tot['receipt_vbank']); ?></td>
        <td><?php echo number_format($tot['receipt_iche']); ?></td>
        <td><?php echo number_format($tot['receipt_card']); ?></td>
        <td><?php echo number_format($tot['receipt_hp']); ?></td>
        <!-- // <td><?php echo number_format($tot['receipt_point']); ?></td> // -->
		<!-- ##start##  ##### -->
        <td><?php echo number_format($tot['pv']); ?></td>
        <td><?php echo number_format($tot['bv']); ?></td>
		<!-- ##end##  ## -->
        <td><?php echo number_format($tot['ordercancel']); ?></td>
        <td><?php echo number_format($tot['misu']); ?></td>
    </tr>
    </tfoot>
    </table>
</div>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
