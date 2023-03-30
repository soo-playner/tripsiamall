<?
include_once('./_common.php');

include_once ('./admin.head.php');

$sql_return_cnt = " SELECT COUNT(distinct mb_id) from soodang_pay WHERE allowance_name = '{Binary}' ";
$sql_cnt_result = sql_fetch($sql_return_cnt);
$sql_cnt = $sql_cnt_result['cnt'];

$sql_return = " update g5_member AS B ,(SELECT mb_id, round(sum(benefit),2) AS benefit from soodang_pay WHERE allowance_name = '{Binary}' GROUP BY mb_id ) AS A
SET B.mb_balance = ROUND((B.mb_balance - A.benefit),2)
WHERE B.mb_id = A.mb_id; ";

$sql_result = sql_query($sql_return);

if($sql_result){
    if($sql_cnt > 0){
        alert($sql_cnt.' 의 B팩 수당을 되돌렸습니다.');
    }else{
        alert('되돌릴 수당 지급내역이 없습니다');
    }
    goto_url('./binary.php');
}
?>
