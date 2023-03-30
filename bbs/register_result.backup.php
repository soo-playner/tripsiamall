<?php
include_once('./_common.php');

if (isset($_SESSION['ss_mb_reg']))
    $mb = get_member($_SESSION['ss_mb_reg']);

// 회원정보가 없다면 초기 페이지로 이동
if (!$mb['mb_id'])
    goto_url(G5_URL);

$g5['title'] = '회원가입이 완료되었습니다.';
include_once('./_head.php');

/*## mobile skin use ################################################*/
if (G5_IS_MOBILE) {
$member_skin_path = str_replace("/skin/","/mobile/skin/",$member_skin_path);
$member_skin_url = str_replace("/skin/","/mobile/skin/",$member_skin_url);
}
/*@@End.  #####*/




include_once($member_skin_path.'/register_result.skin.php');
include_once('./_tail.php');
?>