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
		<col width="80" /><col width="80" /><col width="100" /><col width="500" /><col/>
	</colgroup>
    <thead>
    <tr>
        <th>수당지급일</th>
		<th>수당구분</th>
       	<th>지급액</th>
		<th>수당근거</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $sql = " select date_format(day, '%Y-%m-%d') as day, allowance_name, mb_recommend, benefit, rec from soodang_pay
             where mb_id = '{$member['mb_id']}'
              order by day desc
              $limit ";

    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
   
    ?>

    <tr>
		
        <td><?php echo ($row['day']); ?></td>
        <td><?php echo ($row['allowance_name']); ?></td>
        <td><?php echo display_price($row['benefit']); ?></td>
        <td><?php echo $row['rec']; ?></td>


    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="7" class="empty_table">수당 내역이 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>
<!-- } 주문 내역 목록 끝 -->