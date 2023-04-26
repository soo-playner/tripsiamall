<?php
include_once('./_common.php');

if (!$member['mb_id']) 
    alert('회원만 접근하실 수 있습니다.');


if ($is_admin == 'super')
    alert('최고 관리자는 탈퇴할 수 없습니다');

if (!($_POST['reg_mb_password'] && check_password($_POST['reg_mb_password'], $member['mb_password']))) {
    echo json_encode(array('code'=>'300', 'msg'=>"로그인 비밀번호가 틀립니다."));
    exit;
}
    

if (!($_POST['reg_tr_password'] && check_password($_POST['reg_tr_password'], $member['reg_tr_password']))) {
    echo json_encode(array('code'=>'300', 'msg'=>"출금비밀번호(핀코드)가 틀립니다."));
    exit;
}

// 회원자료 삭제
member_delete($member['mb_id']);

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

echo json_encode(array('code' => '200', 'url' => 'page.php?id=member_leave_result'));


?>
