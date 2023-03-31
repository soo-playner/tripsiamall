<?php
if($member['mb_id'] == 'admin'){
$menu['menu600'] = array (

    array('600000', '마케팅플랜', ''.G5_ADMIN_URL.'/bonus/bonus_list.php','bbs_board'),
    array('600100', '마케팅 수당 설정', G5_ADMIN_URL.'/bonus/bonus_config2.php', 'bbs_board'),
    array('600200', '수당지급 및 지급내역', ''.G5_ADMIN_URL.'/bonus/bonus_list.php','bbs_board'),
    array('600300', '마이닝지급 및 내역', ''.G5_ADMIN_URL.'/bonus/bonus_mining.php','bbs_board'),
    array('600150', '패키지 상품관리', G5_ADMIN_URL.'/shop_admin/itemlist.php', 'scf_item'),
    array('600400', '패키지 상품 현황', ''.G5_ADMIN_URL.'/bonus/rank_table.php','bbs_board'),
    // array('600800', '센터관리',''.G5_ADMIN_URL.'/bonus/bonus.center_member.php'),
    array('600500', '승급 현황', ''.G5_ADMIN_URL.'/bonus/member_upgrade.php','bbs_board'),
    // array('600300', '프로모션 제한리셋 기록', G5_ADMIN_URL.'/m3cron_log.php')
    
    /*
    array('600400', 'B팩 수당',''.G5_ADMIN_URL.'/binary.php'),
    array('600500', '아바타 적금',''.G5_ADMIN_URL.'/avatar.php'),
	*/
);
}else{
    $menu['menu600'] = array (
        array('600000', '마케팅플랜', ''.G5_ADMIN_URL.'/bonus/bonus_list.php','bbs_board'),
        array('600100', '마케팅 수당 설정', G5_ADMIN_URL.'/bonus/bonus_config2.php', 'bbs_board'),
        array('600200', '수당지급 및 지급내역', ''.G5_ADMIN_URL.'/bonus/bonus_list.php','bbs_board'),
        array('600300', '마이닝지급 및 내역', ''.G5_ADMIN_URL.'/bonus/bonus_mining.php','bbs_board'),
        array('600150', '패키지 상품관리', G5_ADMIN_URL.'/shop_admin/itemlist.php', 'scf_item'),
        array('600400', '패키지 상품 현황', ''.G5_ADMIN_URL.'/bonus/rank_table.php','bbs_board'),
        array('600800', '센터관리',''.G5_ADMIN_URL.'/bonus/bonus.center_member.php'),
        array('600500', '승급 현황', ''.G5_ADMIN_URL.'/bonus/member_upgrade.php','bbs_board'),
    );
}
?>