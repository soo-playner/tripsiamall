<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$str = '';
$exists = false;

$ca_id_len = strlen($ca_id);
$len2 = $ca_id_len + 2;
$len4 = $ca_id_len + 4;

$sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']} where ca_id like '$ca_id%' and length(ca_id) = $len2 and ca_use = '1' order by ca_order, ca_id ";
$result = sql_query($sql);
while ($row=sql_fetch_array($result)) {

    $row2 = sql_fetch(" select count(*) as cnt from {$g5['g5_shop_item_table']} where (ca_id like '{$row['ca_id']}%' or ca_id2 like '{$row['ca_id']}%' or ca_id3 like '{$row['ca_id']}%') and it_use = '1'  ");

    $str .= '<li><a href="./list.php?ca_id='.$row['ca_id'].'">'.$row['ca_name'].'['.$row2['cnt'].']</a></li>';
    $exists = true;
}

if (!$it_id) {
	if ($exists) {
    // add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
    add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>
<style type="text/css">
.page_categorys {padding:20px 30px;border-top:solid 2px #50575d;border-bottom:solid 1px #aeaeae;}
.page_categorys li {display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;background:url(/adm/img/gap.png) no-repeat right center;}
.page_categorys li a {padding:0 10px;display:block;line-height:30px;color:#505050;font-size:13px;font-family:"nngdb";word-break:keep-all;}
.page_categorys li:last-child {background:none;}
</style>
<div class="page_categorys">
    <ul>
        <?php echo $str; ?>
    </ul>
</div><!-- // page_categorys -->
<aside id="sct_ct_1" class="sct_ct">
    <h2>현재 상품 분류와 관련된 분류</h2>

</aside>
<?
	}
}
?>