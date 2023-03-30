<?php
// if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once('./_common.php');
include_once(G5_LIB_PATH.'/icode.sms.lib.php');
include_once(G5_PLUGIN_PATH.'/sms5/sms5.lib.php');
include_once(G5_PLUGIN_PATH.'/Encrypt/rule.php');

login_check($member['mb_id']);
// $debug = 1;
$debug_mode = LIVE_MODE;

$otp_key = mt_rand(100000, 999999);
$mb_id = $member['mb_id'];
$mb_hp = $member['mb_hp'];

if($debug_mode){
    $opt_key_secure = Encrypt($otp_key);
}else{
    $opt_key_secure = $otp_key;
}

$update_auth_mobile = "UPDATE g5_member set otp_key = '{$opt_key_secure}' WHERE mb_id = '{$mb_id}' ";

if($debug){
    // print_R($update_auth_mobile);
}else{
    sql_query($update_auth_mobile);
}

//----------------------------------------------------------
// SMS 문자전송 시작
//----------------------------------------------------------

$sms_contents = "[".$config['cf_title']."] 출금 인증번호 (".$otp_key.") 를 입력해주세요.";

// 핸드폰번호에서 숫자만 취한다
$receive_number = preg_replace("/[^0-9]/", "", $mb_hp);  // 수신자번호 (회원님의 핸드폰번호)
$send_number = preg_replace("/[^0-9]/", "", $sms5['cf_phone']); // 발신자번호
$wr_message = stripslashes($sms_contents);



if($debug || !$debug_mode){
    echo "<br>";
    print_R($sms_contents);
    echo "<br>receive_number : ".$receive_number;
    echo "<br>send_number : ".$send_number;
    ob_clean();
    echo json_encode(array("result" => "success",  "time" => 500, "code" => $otp_key));
}else{
    $SMS = new SMS; // SMS 연결
    $SMS->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $config['cf_icode_server_port']);
    $SMS->Add($receive_number, $send_number, $config['cf_icode_id'], iconv_euckr($wr_message), "");

    $result = $SMS->Send();

    if ($result) //SMS 서버에 접속했습니다.
    {
        $row = sql_fetch("select max(wr_no) as wr_no from {$g5['sms5_write_table']}");
        if ($row)
            $wr_no = $row['wr_no'] + 1;
        else
            $wr_no = 1;

        sql_query("insert into {$g5['sms5_write_table']} set wr_no='$wr_no', wr_renum=0, wr_reply='{$send_number}', wr_message='{$wr_message}', wr_total=1, wr_datetime='".G5_TIME_YMDHIS."'");

        $wr_success = 0;
        $wr_failure = 0;
        $count      = 0;

        foreach ($SMS->Result as $result)
        {
            list($phone, $code) = explode(":", $result);

            if (substr($code,0,5) == "Error")
            {
                $hs_code = substr($code,6,2);

                switch ($hs_code) {
                    case '02':	 // "02:형식오류"
                        $hs_memo = "형식이 잘못되어 전송이 실패하였습니다.";
                        break;
                    case '23':	 // "23:인증실패,데이터오류,전송날짜오류"
                        $hs_memo = "데이터를 다시 확인해 주시기바랍니다.";
                        break;
                    case '97':	 // "97:잔여코인부족"
                        $hs_memo = "잔여코인이 부족합니다.";
                        break;
                    case '98':	 // "98:사용기간만료"
                        $hs_memo = "사용기간이 만료되었습니다.";
                        break;
                    case '99':	 // "99:인증실패"
                        $hs_memo = "인증 받지 못하였습니다. 계정을 다시 확인해 주세요.";
                        break;
                    default:	 // "미 확인 오류"
                        $hs_memo = "알 수 없는 오류로 전송이 실패하였습니다.";
                        break;
                }
                $wr_failure++;
                $hs_flag = 0;
            }
            else
            {
                $hs_code = $code;
                $hs_memo = get_hp($phone, 1)."로 전송했습니다.";
                $wr_success++;
                $hs_flag = 1;
            }
            

            $log = $SMS->Log;
            $log = @iconv('euc-kr', 'utf-8', $log);

            sql_query("insert into {$g5['sms5_history_table']} set wr_no='{$wr_no}', wr_renum=0, bg_no=0, mb_id='{$member['mb_id']}', bk_no='{$member['mb_no']}', hs_name='".addslashes($member['mb_name'])."', hs_hp='{$receive_number}', hs_datetime='".G5_TIME_YMDHIS."', hs_flag='$hs_flag', hs_code='$hs_code', hs_memo='".addslashes($hs_memo)."', hs_log='".addslashes($log)."'", false);
        }
        $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.

        sql_query("update {$g5['sms5_write_table']} set wr_success='$wr_success', wr_failure='$wr_failure', wr_memo='$str_serialize' where wr_no='$wr_no' and wr_renum=0");
    }else{
        echo json_encode(array("result" => "failed","error" => "에러: SMS 서버와 통신이 불안정합니다."),JSON_UNESCAPED_UNICODE);
    }
    
    
    
    if($result){
        echo json_encode(array("result" => "success",  "time" => 500));
    } else {
        echo json_encode(array("result" => "failed","error" => "현재 서비스를 이용할수 없습니다."),JSON_UNESCAPED_UNICODE);
    }

}

//----------------------------------------------------------
// SMS 문자전송 끝
//----------------------------------------------------------

?>
