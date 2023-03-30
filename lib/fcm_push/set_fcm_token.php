<?php
$userId=$member['mb_id'];
$userPwd=$member['mb_password'];
echo "<script>javascript:App.setFcmToken('{$userId}','{$userPwd}');</script>"; 
// fcm token 등록 @키값은 유저 아이디 (사용자가 핸드폰 변경시 토큰 재등록을 위해 dashboard.php에서 호출)

// registerFcmToken.php 에 설명 추가
?>


<!-- 회원가입 또는 대쉬보드에 추가--> 
<?php if(strpos($_SERVER['HTTP_USER_AGENT'],'webview//1.0') == true){ ?>
	<script>App.setFcmToken('<?=$member['mb_id']?>');</script> 
<?php } ?> 
