<?php
$sub_menu = "700600";
include_once('./_common.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = "구매취소내역";

include_once(G5_ADMIN_PATH . '/adm.header.php');


$status_string = array('요청확인중', '승인', '대기', '불가', '취소');

function status($val)
{
    global $status_string;
    return $status_string[$val];
}


/* 조건검색*/
if ($_GET['fr_id']) {
    $sql_condition .= " and A.mb_id = '{$_GET['fr_id']}' ";
    $qstr .= "&fr_id=" . $_GET['fr_id'];
}

if ($fr_date && $to_date) {
    $sql_condition .= " and DATE_FORMAT(A.de_datetime, '%Y-%m-%d') between '{$fr_date}' and '{$to_date}' ";
    $qstr = "fr_date=" . $fr_date . "&amp;to_date=" . $to_date . "&amp;to_id=" . $fr_id;
}

if ($_GET['ord'] != null && $_GET['ord_word'] != null) {
    $sql_ord = "order by " . $_GET['ord_word'] . " " . $_GET['ord'];
}


$colspan = 11;
// $to_date = date("Y-m-d", strtotime(date("Y-m-d")."+1 day"));

$sql_common = " from g5_shop_order_delete as A";
$sql_search = " WHERE 1=1 " . $sql_condition;

$sql = " select count(*) as cnt
{$sql_common}
{$sql_search}";


$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            order by de_datetime desc
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);


function  od_name_return_rank($val){
    if(strlen($val) < 5){
        return substr($val,1,1);
    }else{
        return 0;
    }
}

?>


<style>
    .red {
        color: red
    }

    .text-center {
        text-align: center
    }

    .hash {
        min-width: 120px;
        height: auto;
        display: block;
    }

    .reg_text {
        border: 1px solid #ccc;
        padding: 5px 10px;
        width: 80%;
    }

    select {
        padding: 5px;
        min-width: 80px;
        width: 80%;
    }

    table tr td {
        text-align: center
    }

    .row_dup td {
        background: rgba(253, 240, 220, 0.8)
    }

    .btn_submit.excel {
        background: green
    }


    .local_ov strong {
        color: red;
        font-weight: 600;
    }

    .local_ov .tit {
        color: black;
        font-weight: 600;
    }

    .local_ov a {
        margin-left: 20px;
    }
</style>
<link rel="stylesheet" href="/adm/css/scss/admin_custom.css">

<script>
    $(function() {


        $.datepicker.regional["ko"] = {
            closeText: "close",
            prevText: "이전달",
            nextText: "다음달",
            currentText: "오늘",
            monthNames: ["1월(JAN)", "2월(FEB)", "3월(MAR)", "4월(APR)", "5월(MAY)", "6월(JUN)", "7월(JUL)", "8월(AUG)", "9월(SEP)", "10월(OCT)", "11월(NOV)", "12월(DEC)"],
            monthNamesShort: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
            dayNames: ["일", "월", "화", "수", "목", "금", "토"],
            dayNamesShort: ["일", "월", "화", "수", "목", "금", "토"],
            dayNamesMin: ["일", "월", "화", "수", "목", "금", "토"],
            weekHeader: "Wk",
            dateFormat: "yymmdd",
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: true,
            yearSuffix: ""
        };
        $.datepicker.setDefaults($.datepicker.regional["ko"]);

        $("#create_dt_fr,#create_dt_to, #update_dt").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "c-99:c+99",
            maxDate: "+0d"
        });
    });
</script>




<div class="local_ov01 local_ov">
    <a href="./order_delete.php?<?= $qstr ?>" class="ov_listall"> 결과통계 <?= $total_count ?> 건<strong></a>
</div>

<div class="local_desc01 local_desc">
    <p>
        <strong>- 구매취소건 :</strong> 
    </p>
</div>

<div class="tbl_head01 tbl_wrap">
    <table class='regTb'>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
            <tr>
                <th scope="col" width='5%'>no</th>
                <th scope="col" width='10%'>아이디</th>
                <th scope="col" width='10%'>구매번호(코드)</th>
                <th scope="col" width='15%'>구매취소상품</th>
                <th scope="col" width='20%'>구매취소금액</th>
                <th scope="col" width='20%'>구매취소처리</th>
                <th scope="col" width='10%'>처리자 IP</th>
                <th scope="col" width='10%'>상태변경일</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $de_hap = 0;
            for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $bg = 'bg' . ($i % 2);
                $de_data = explode('|', $row['de_data']);
                $de_hap += $de_data[1];
            ?>

                <tr class="<?=$bg?>">
                    <td><?php echo $row['de_id'] ?></td>
                    <td style='color:#333;font-weight:600'><a href='/adm/member_form.php?sst=&sod=&sfl=&stx=&page=&w=u&mb_id=<?= $row['mb_id'] ?>' target='_blank'><?= $row['mb_id'] ?></a></td>
                    <td style='color:#666'><?= $row['de_key'] ?></td>
                    <td><span class='badge t_white color<?=od_name_return_rank($de_data[0])?>' ><?= $de_data[0] ?></span></td>
                    <td class='price'><?= Number_format($de_data[1]) ?></td>
                    <td><?= $de_data[2] ?></td>
                    <td><?= $row['de_ip'] ?></td>
                    <td><?= $row['de_datetime'] ?></td>
                </tr>

            <?php
            }
            if ($i == 0)
                echo '<tr><td colspan="' . $colspan . '" class="empty_table">자료가 없거나 관리자에 의해 삭제되었습니다.</td></tr>';
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td>합계:</td>
                <td colspan="3"></td>
                <td><?= Number_format($de_hap)?></td>
                <td colspan="3"></td>
            <tr>
        </tfoot>
    </table>
</div>

<?php
if (isset($domain))
    $qstr .= "&amp;domain=$domain";
$qstr .= "&amp;page=";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;


include_once('./admin.tail.php');
?>