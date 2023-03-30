<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!defined("_ORDERINQUIRY_")) exit; // 개별 페이지 접근 불가

// 테마에 orderinquiry.sub.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_inquiry_file = G5_THEME_SHOP_PATH.'/orderinquiry.sub.php';
    if(is_file($theme_inquiry_file)) {
        include_once($theme_inquiry_file);
        return;
        unset($theme_inquiry_file);
    }
}
?>

<!-- 주문 내역 목록 시작 { -->
<?php if (!$limit) { ?>총 <?php echo $cnt; ?> 건<?php } ?>
<style type="text/css">
table.lst {width:100%;table-layout:fixed;border-collapse:collapse;}
table.lst {border-top:solid 1px #ddd;}
table.lst th,
table.lst td {padding:12px 0;border-bottom:solid 1px #ddd;line-height:28px;}
table.lst th {font-weight:normal;color:#222;}
table.lst td {text-align:center;color:#777;}
table.lst input[type="text"],
table.lst input[type="password"] {padding:0;padding-left:8px;height:23px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
table.lst textarea {padding:0;padding-left:8px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
table.lst label {cursor:pointer;}
table.lst input[type="radio"] {}
table.lst input[type="radio"] + label{color:#999;}
table.lst input[type="radio"]:checked + label {color:#e50000;font-weight:bold;}
span.help {font-size:11px;font-weight:normal;color:rgba(38,103,184,1);}
</style>
<div>
	<table cellspacing="0" cellpadding="0" border="0" class="lst">
	<colgroup>
		<col/><col/><col/><col width="120" /><col width="120" /><col width="120" /><col width="80" /><col width="80" /><col width="160" />
	</colgroup>
    <thead>
    <tr>
        <th>주문서번호</th>
        <th>주문일시</th>
        <th>상품</th>
        <th>주문금액</th>
        <th>입금액</th>
        <th>미입금액</th>
		<th>PV</th>
		<th>BV</th>
        <th>상태</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = " select *
               from {$g5['g5_shop_order_table']}
              where mb_id = '{$member['mb_id']}'
              order by od_id desc
              $limit ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);

        switch($row['od_status']) {
            case '주문':
                $od_status = '입금확인중';
                break;
            case '입금':
                $od_status = '입금완료';
                break;
            case '준비':
                $od_status = '상품준비중';
                break;
            case '배송':
                $od_status = '상품배송';
                break;
            case '완료':
                $od_status = '배송완료';
                break;
            default:
                $od_status = '주문취소';
                break;
        }
    ?>

    <tr>
        <td>
            <input type="hidden" name="ct_id[<?php echo $i; ?>]" value="<?php echo $row['ct_id']; ?>">
            <a href="<?php echo G5_SHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $row['od_id']; ?>&amp;uid=<?php echo $uid; ?>"><?php echo $row['od_id']; ?></a>
        </td>
        <td><?php echo substr($row['od_time'],2,14); ?> (<?php echo get_yoil($row['od_time']); ?>)</td>
        <td class="td_num">
		<?
			$comma = "";
			$qry = sql_query(" select * from g5_shop_cart where od_id = '{$row[od_id]}' ");
			while ($res = sql_fetch_array($qry)) {
		?>
			<?=$comma?> <?=$res['it_name']?>(<?=$res['ct_qty']?>)
		<?
			$comma = ",";
			}
		?>
		<?//php echo $row['od_cart_count']; ?>
		</td>
        <td class="td_numbig"><?php echo display_price($row['od_cart_price'] + $row['od_send_cost'] + $row['od_send_cost2']); ?></td>
        <td class="td_numbig"><?php echo display_price($row['od_receipt_price']); ?></td>
        <td class="td_numbig"><?php echo display_price($row['od_misu']); ?></td>
        <td class="td_numbig"><?php echo display_price($row['pv']); ?></td>
        <td class="td_numbig"><?php echo display_price($row['bv']); ?></td>
        <td><?php echo $od_status; ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="9" class="empty_table">주문 내역이 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>
<!-- } 주문 내역 목록 끝 -->