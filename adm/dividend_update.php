<?php
$sub_menu = "200600";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

check_token();

$mb_id = $_POST['mb_id'];
$mb_name = $_POST['mb_name'];
$dv_datetime = $_POST['dv_datetime'];
$dv_gubun = $_POST['dv_gubun'];
$dv_oneid = $_POST['dv_oneid'];
$dv_count = $_POST['dv_count'];
$dv_money = $_POST['dv_money'];
$dv_paid = $_POST['dv_paid'];
$dv_content = $_POST['dv_content'];
$dv_id = $_POST['dv_id'];



$nal=G5_TIME_YMDHIS;
$mb = get_member($mb_id);

if (!$mb['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.', './dividend_list.php?'.$v);
    $cf_count=$config['cf_cbcount'];
    
    $tax=round($dv_money*0.043 );
	
	
   if ($_POST['what'] == "u" ){
    $sql = " update dividend
                set mb_id = '$mb_id',
                	mb_name = '$mb_name',
                    dv_datetime = '$dv_datetime',
                    dv_gubun = '$dv_gubun',
                    dv_oneid = '$dv_oneid',
                    dv_count = '$dv_count',
                    dv_money = '$dv_money',
					dv_tax = '$tax',
                    dv_paid = '$dv_paid',
                    ev_yn = '$ev_yn',
                    dv_content = '$dv_content'
             where dv_id='$dv_id'";
                    sql_query($sql); 
       
                   
   }else if ($_POST['what'] == "w") {

    $sql = " insert into dividend
                set mb_id = '$mb_id',
                	mb_name = '$mb_name',
                    dv_datetime = '$dv_datetime',
                    dv_gubun = '$dv_gubun',
                    dv_oneid = '$dv_oneid',
                    dv_count = '$dv_count',
                    dv_money = '$dv_money',
					dv_tax = '$tax',
                    dv_paid = '$dv_paid',
                    ev_yn = '$ev_yn',
                    dv_content = '$dv_content'";

					 sql_query($sql);
                   
   }
   

   

goto_url('./dividend_list.php?chkc='.$chkc.'&chkm='.$chkm.'&chkr='.$chkr.'&chkd='.$chkd.'&chke='.$chke.'&chki='.$chki.'&diviradio='.$diviradio.'&r='.$r.'&fr_date='.$fr_date.'&to_date='.$to_date.'&qstr='.$qstr);
?>
