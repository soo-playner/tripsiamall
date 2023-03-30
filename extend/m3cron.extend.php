<?php
// m3cron ver 1.11 (2009-06-02)
// a plugin for GNU board 4.31.02
// 리눅스의 크론처럼 매월, 매주, 매일, 설정한 간격마다 원하는 파일을 실행시키고 기록을 남깁니다.
// 1) /extend 에 이 파일(m3cron.extend.php)를 복사합니다.
// 2) /lib 에 m3cron.lib.php 를 복사합니다.
// 3) /adm 에 m3cron_list.php, m3cron_edit.php, admin.menu400.php 를 복사합니다.
// 4) /adm/img 에 menu400.gif 를 복사합니다.
// 5) /m3cron 폴더를 생성시키고 실행시킬 파일을 복사합니다.
// please give feedbacks to http://bomool.net

include_once(G5_PATH.'/lib/m3cron.lib.php');


// 파일 목록 가져오기
$query = sql_query("select * from m3cron_config", false);

while($prog = sql_fetch_array($query)) {
	
	
	// 활성화 된 경우만 실행
	if(!$prog['status']) continue;

	// 로봇으로 실행시키는 경우
	if($prog['robot'] && $is_robot) continue;


	// 타입에 따라 조건
	if($prog['type']=="monthly") {
		if(G5_TIME_YMD <= $prog['lastrun']) continue;
		if(intval(date("d")) != intval($prog['d'])) continue;
		if(intval(date("H")) < intval($prog['h'])) continue;
	}
	else if($prog['type']=="weekly") {
		if(G5_TIME_YMD <= $prog['lastrun']) continue;
		if(date("w") != $prog['w']) continue;
		if(intval(date("H")) < intval($prog['h'])) continue;
	}
	else if($prog['type']=="daily") {
		if(G5_TIME_YMD <= $prog['lastrun']) continue;
		if(intval(date("H")) < intval($prog['h'])) continue;
	}
	else if($prog['type']=="hourly") {
		
		if(time() - strtotime($prog['lastrun']) < $prog['h'] * 60 * 60) continue;
	}

	// 일단은 마지막 실행 시각 기록
sql_query("update `{$m3cron['config_table']}` set lastrun='".G5_TIME_YMDHIS."' where name='{$prog['name']}' limit 1");

	// 실행 시작 시간 구함
	$starttime = get_microtime();
	

	// 실행!!!
	include_once($m3cron['path'].'/'.$prog['name']);

	// 실행 시간 구함
	$runtime = get_microtime() - $starttime;

	// 마지막 실행 시간 기록
sql_query("update `{$m3cron['config_table']}` set lastruntime='{$runtime}' where name='{$prog['name']}' limit 1");

	// 로그 남김
sql_query("insert into `{$m3cron['log_table']}` set name='{$prog['name']}', datetime='".G5_TIME_YMDHIS."', runtime='{$runtime}', ip='".$_SERVER['REMOTE_ADDR']."', robot='{$is_robot}', mb_id='{$member['mb_id']}'");
}
?>