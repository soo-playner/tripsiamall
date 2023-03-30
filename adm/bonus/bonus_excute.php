<?php

$sub_menu = "600200";
include_once('./_common.php');
include_once('./bonus_inc.php');


auth_check($auth[$sub_menu], 'r');

$idcode = $_GET['id']; 
$sql = "SELECT * from `soodang_pre_record` WHERE code = '{$idcode}' ";
$result = sql_query($sql);
$result_cnt = sql_num_rows($result);

// 달러 표시
function shift_doller($val){
	return Number_format($val, 2);
}


ob_start();

// 설정로그 
echo "<strong>".$idcode." 수당지급실행</strong>";

echo "<br><br>지급 기준대상자 : <span class='red'>".$result_cnt." 명</span>";
echo "</span><br><br>";
echo "<div class='btn' onclick='bonus_url();'>돌아가기</div>";

?>

<html><body>
<header>정산시작</header>    
<div>

<?
excute();

function  excute(){

    global $result;
    global $g5,$now_date;
    global $debug;

    while($row = sql_fetch_array($result)){

        $mb_id = $row['mb_id'];
        $code = $row['code'];
        $benefit_limit = $row['benefit_limit'];
        $rec = $row['rec'];
        $rec_adm = $row['rec_adm'];
        $bonus_day = $row['bonus_day'];

        echo "<br><br><span class='title block' style='font-size:30px;'>".$mb_id."</span><br>";

        echo " = 수당지급 : <span class='blue'>$ ".shift_doller($benefit_limit)."</span>";

        $record_result = soodang_record($mb_id, $code, $benefit_limit,$rec,$rec_adm,$bonus_day);

        if($record_result){
            $balance_up = "update g5_member set mb_balance = mb_balance + {$benefit_limit}  where mb_id = '".$mb_id."'";

            // 디버그 로그
            if($debug){
                echo "<code>";
                print_R($balance_up);
                echo "</code>";
            }else{
                sql_query($balance_up);
            }
        }
    }

    echo "<br><br><span class='blue'>수당지급완료</span>";

}
?>

<?include_once('./bonus_footer.php');?>
