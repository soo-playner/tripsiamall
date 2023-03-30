<?php
if($member['mb_id'] == 'admin'){
	$menu["menu800"] = array (
		array('800000', '앱/푸쉬관리', ''.G5_ADMIN_URL.'/app_push/fcm_memberlist.php', '0'),
		// array('800000', '앱/푸쉬관리', ''.G5_ADMIN_URL.'/app_push/fcm_manager.php', '0'),
		array('800100', '앱/푸쉬관리', G5_ADMIN_URL.'/app_push/fcm_memberlist.php', '0'),
		array('800200', '메세지관리', G5_ADMIN_URL.'/app_push/fcm_msg.php', '0'),
		array('800300', '전송내역', G5_ADMIN_URL.'/app_push/fcm_send_list.php', '0'),
	);
}else{
	$menu["menu800"] = array (
		array('800000', '앱/푸쉬관리', ''.G5_ADMIN_URL.'/app_push/fcm_memberlist.php', '0'),
		array('800100', '앱/푸쉬관리', G5_ADMIN_URL.'/app_push/fcm_memberlist.php', '0'),
		array('800200', '메세지관리', G5_ADMIN_URL.'/app_push/fcm_msg.php', '0'),
		array('800300', '전송내역', G5_ADMIN_URL.'/app_push/fcm_send_list.php', '0'),
	);
}

?>