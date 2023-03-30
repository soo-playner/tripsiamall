<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('./inc.member.class.php');

//손정숙
if (!$m_id) $m_id      = "0010000288";


//class 생성 실행
make_class();

// set 체크
$my_depth = get_depth($m_id);
echo "my_depth : ".$my_depth."<br>\n";

?>