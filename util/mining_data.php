<?php
include_once('./_common.php');
ob_clean();

if($_POST['category'] == 'my'){
    $category = 'mining';
}else{
    $category = $_POST['category'].'_mining';
}
$start = $_POST['start'];
$limited = $_POST['limited'];

if($_POST['category'] == 'super'){
    $detail_sql = "SELECT *,Trim(SUBSTRING_INDEX(SUBSTRING_INDEX(rec, 'hash', 1),'By', -1)) AS from_id,IFNULL(LAG(mining, -1) OVER (ORDER BY day desc), 0) AS prev FROM soodang_mining WHERE mb_id = '{$member['mb_id']}' and allowance_name='{$category}' ORDER BY day desc Limit {$start},{$limited}";
}else{
    $detail_sql = "SELECT *,IFNULL(LAG(mining, -1) OVER (ORDER BY day desc), 0) AS prev FROM soodang_mining WHERE mb_id = '{$member['mb_id']}' and allowance_name='{$category}' ORDER BY day desc Limit {$start},{$limited}";
}

$detail_result = sql_query($detail_sql);
$list = [];

while ($rows = sql_fetch_array($detail_result)) {
    array_push($list,$rows);
}

if(count($list) > 0){
    echo (json_encode(array("result" => "sucess",  "code" => "0000", "data" => $list),JSON_UNESCAPED_UNICODE));
}else{
    echo (json_encode(array("result" => "failed",  "code" => "0001", "data" => "데이터가 없습니다."),JSON_UNESCAPED_UNICODE));
}

?>