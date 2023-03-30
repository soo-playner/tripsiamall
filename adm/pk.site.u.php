<?php
$_VAR = $_GET + $_POST;
$sub_menu = '100100';
include_once('./_common.php');


/*
if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");
*/
if($_VAR['mode']=="site"):
/*## 설정 등록 ################################################*/

	$s_comma = $c_comma = $cfg = $site = "";
	$i = 0;
	foreach ($_POST as $key => $value) {
		if ($key != "mode" && $key != "token" && $key != "rtn" && $key != "submit" && $key != "cf_intercept_ip") {
			if(strpos($key, "cf_") !== false) { // g5_config
				$cfg .= $c_comma.$key." = '".$value."'";
				$c_comma = ", ";
			} else { // g5_site
				$site .= $s_comma.$key." = '".$value."'";
				$s_comma = ", ";
			}
		}
	}

	$pk = sql_fetch(" select * from g5_site ");
	if ($pk) {
		$site_sql = " update g5_site set ".$site;
	} else {
		$site_sql = " insert into g5_site set ".$site;
	}
	/*
	echo "<p>$site_sql</p>";
	exit;
	*/
	$cfg_sql = " update g5_config set ".$cfg;
	sql_query($site_sql);
	sql_query($cfg_sql);

	/*## 관리자 정보등 변경  ################################################*/
	sql_query(" update g5_config set cf_title = '{$_POST[title]}', cf_admin_email_name = '{$_POST[title]}', cf_admin_email = '{$_POST[email]}', cf_intercept_ip = '{$_POST[cf_intercept_ip]}'  ");
	//sql_query(" update g5_member set mb_name = '{$_POST[title]}', mb_nick = '{$_POST[title]}', mb_email = '{$_POST[email]}', mb_tel = '{$_POST[tel]}'  ");

	/*@@End.  #####*/
	goto_url($_VAR['rtn']);
	exit;
/*@@End.  #####*/

elseif($_VAR['mode']=="camp_reg"):
/*## 등록 ################################################*/
	goto_url($_VAR['rtn']);
	exit;


/*@@End.  #####*/
elseif($_VAR['mode']=="camp_dlt"):
/*## 삭제 ################################################*/

	if (!$_VAR['rm_id']) {
		alert("잘못된 경로로 접속하셨거나, 제대로 값이 넘어오지 않았습니다.\n다시 한번 시도해주세요!",$_VAR[rtn]);
	} else {
		sql_query(" delete from g5_room_info where rm_id = '{$_VAR['rm_id']}' ");
		alert("삭제 되었습니다.",$_VAR['rtn']);
	}

	exit;

/*@@End.  #####*/
elseif($_VAR['mode']=="out_delete"):
/*## 품목삭제 ################################################*/

	sql_query(" delete from g5_shop_acount where out_id = '{$_VAR['out_id']}' and it_id = '{$_VAR['it_id']}' ");
	alert("해당 품목이 제거 되었습니다.",$_VAR['rtn']);

/*@@End.  #####*/

endif;



?>