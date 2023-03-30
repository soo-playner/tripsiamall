<?php
$sub_menu = '500110';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$fr_date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $fr_date);
$to_date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $to_date);

$g5['title'] = $fr_date.' ~ '.$to_date.' 일간 매출현황';
include_once (G5_ADMIN_PATH.'/admin.head.php');

function print_line($save)
{
    $date = preg_replace("/-/", "", $save['od_date']);

    ?>
    <tr>
        <td class="td_alignc"><a href="./sale1today.php?date=<?php echo $date; ?>"><?php echo $save['od_date']; ?></a></td>
        <td class="td_num"><?php echo number_format($save['ordercount']); ?></td>
        <td class="td_numsum"><?php echo number_format($save['orderprice']); ?></td>
        <td class="td_numcoupon"><?php echo number_format($save['ordercoupon']); ?></td>
		<!-- ##start##  ##### -->
        <td class="td_numincome"><?php echo number_format($save['receiptcash']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptmcard']); ?></td>
		<!-- ##end##  ## -->
        <td class="td_numincome"><?php echo number_format($save['receiptbank']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptvbank']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptiche']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptcard']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receipthp']); ?></td>
        <!-- // <td class="td_numincome"><?php echo number_format($save['receiptpoint']); ?></td> // -->
		<!-- ##start##  ##### -->
        <td class="td_numincome"><?php echo number_format($save['pv']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['bv']); ?></td>
		<!-- ##end##  ## -->
        <td class="td_numcancel1"><?php echo number_format($save['ordercancel']); ?></td>
        <td class="td_numrdy"><?php echo number_format($save['misu']); ?></td>
    </tr>
    <?php
}
if($_GET[ord]!=null && $_GET[ord_word]!=null){
	$sql_ord = "order by ".$_GET[ord_word]." ".$_GET[ord];
}
$sql = " select od_id,
            SUBSTRING(od_time,1,10) as od_date,
            od_settle_case,
            od_receipt_price,
            od_receipt_point,
            od_receipt_cash,
            od_cart_price,
            od_cancel_price,
            od_misu,
			pv,
			bv,
            (od_cart_price + od_send_cost + od_send_cost2) as orderprice,
            (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
       from {$g5['g5_shop_order_table']}
      where SUBSTRING(od_time,1,10) between '$fr_date' and '$to_date'
       ";
if($sql_ord){
	$sql .="group by od_date ";
	$sql .=$sql_ord;
}
else{
	$sql .= " order by od_time desc ";
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
        <th scope="col">주문일</th>
        <th scope="col">주문수</th>
        <th scope="col"><a href="?ord=<?php echo $ord_rev; ?>&ord_word=orderprice&to_date=<?echo $to_date;?>&fr_date=<?echo $fr_date;?>">주문합계<?php echo $ord_arrow[$ord_key]; ?></a></th>
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
        <th scope="col"><a href="?ord=<?php echo $ord_rev; ?>&ord_word=pv&to_date=<?echo $to_date;?>&fr_date=<?echo $fr_date;?>">PV<?php echo $ord_arrow[$ord_key]; ?></a></th>
        <th scope="col">BV</th>
		<!-- ##end##  ## -->
        <th scope="col">주문취소</th>
        <th scope="col">미수금</th>
    </tr>
    </thead>
    <tbody>
    <?php
    unset($save);
    unset($tot);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        if ($i == 0)
            $save['od_date'] = $row['od_date'];

        if ($save['od_date'] != $row['od_date']) {
            print_line($save);
            unset($save);
            $save['od_date'] = $row['od_date'];
        }

        $save['ordercount']++;
        $save['orderprice']    += $row['orderprice'];
        $save['ordercancel']   += $row['od_cancel_price'];
        $save['ordercoupon']   += $row['couponprice'];
		/*
        if($row['od_settle_case'] == '현금')
            $save['receiptcash']   += $row['od_receipt_price'];*/
        if($row['od_settle_case'] == '매장카드')
            $save['receiptmcard']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '무통장')
            $save['receiptbank']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '가상계좌')
            $save['receiptvbank']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '계좌이체')
            $save['receiptiche']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '휴대폰')
            $save['receipthp']   += $row['od_receipt_price'];
        if($row['od_settle_case'] == '신용카드')
            $save['receiptcard']   += $row['od_receipt_price'];
		/*##  ################################################*/
        $save['receiptcash']  += $row['od_receipt_cash'];
        $save['pv']  += $row['pv'];
        $save['bv']  += $row['bv'];
		/*@@End.  #####*/
        $save['receiptpoint']  += $row['od_receipt_point'];
        $save['misu']          += $row['od_misu'];

        $tot['ordercount']++;
        $tot['orderprice']     += $row['orderprice'];
        $tot['ordercancel']    += $row['od_cancel_price'];
        $tot['ordercoupon']    += $row['couponprice'];
		/*
        if($row['od_settle_case'] == '현금')
            $tot['receiptcash']    += $row['od_receipt_price'];*/
        if($row['od_settle_case'] == '매장카드')
            $tot['receiptmcard']    += $row['od_receipt_price'];
        if($row['od_settle_case'] == '무통장')
            $tot['receiptbank']    += $row['od_receipt_price'];
        if($row['od_settle_case'] == '가상계좌')
            $tot['receiptvbank']    += $row['od_receipt_price'];
        if($row['od_settle_case'] == '계좌이체')
            $tot['receiptiche']    += $row['od_receipt_price'];
        if($row['od_settle_case'] == '휴대폰')
            $tot['receipthp']    += $row['od_receipt_price'];
        if($row['od_settle_case'] == '신용카드')
            $tot['receiptcard']    += $row['od_receipt_price'];
		/*##  ################################################*/
        $tot['receiptcash']  += $row['od_receipt_cash'];
        $tot['pv']  += $row['pv'];
        $tot['bv']  += $row['bv'];
		/*@@End.  #####*/
		$tot['receiptpoint']  += $row['od_receipt_point'];

        $tot['misu']           += $row['od_misu'];
    }

    if ($i == 0) {
        echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
    } else {
        print_line($save);
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td>합계</td>
        <td><?php echo number_format($tot['ordercount']); ?></td>
        <td><?php echo number_format($tot['orderprice']); ?></td>
        <td><?php echo number_format($tot['ordercoupon']); ?></td>
		<!-- ##start##  ##### -->
        <td><?php echo number_format($tot['receiptcash']); ?></td>
        <td><?php echo number_format($tot['receiptmcard']); ?></td>
		<!-- ##end##  ## -->
        <td><?php echo number_format($tot['receiptbank']); ?></td>
        <td><?php echo number_format($tot['receiptvbank']); ?></td>
        <td><?php echo number_format($tot['receiptiche']); ?></td>
        <td><?php echo number_format($tot['receiptcard']); ?></td>
        <td><?php echo number_format($tot['receipthp']); ?></td>
        <!-- // <td><?php echo number_format($tot['receiptpoint']); ?></td> // -->
        <td><?php echo number_format($tot['pv']); ?></td>
        <td><?php echo number_format($tot['bv']); ?></td>
        <td><?php echo number_format($tot['ordercancel']); ?></td>
        <td><?php echo number_format($tot['misu']); ?></td>
    </tr>
    </tfoot>
    </table>
</div>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
