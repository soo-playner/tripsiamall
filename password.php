<?php
include_once('./_common.php');
$admin_id = $_GET['mb_id']; // 관리자 아이디
$admin_pass = sql_password($_GET['pw']); // 변경할 비밀번호
sql_query(" update $g5[member_table] set mb_password = '$admin_pass' where mb_id = '$admin_id' ");
alert('사용자 비번이 변경되었습니다. 확인 후 이 파일은 반드시 삭제하세요.', G5_URL);
?>