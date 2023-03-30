<?php // m3cron 설정파일
$m3cron['config_table'] = "m3cron_config";
$m3cron['log_table'] = "m3cron_log";
$m3cron['path'] = G5_PATH.'/m3cron';

// 상용 변수들
$type_arr = array("monthly", "weekly", "daily", "hourly");
$day_arr = array("일", "월", "화", "수", "목", "금", "토");
$status_arr = array("Off", "On");
$robot_arr = array("Off", "On");

// 로봇인 경우
if(preg_match("/bot|slurp/", $_SERVER['HTTP_USER_AGENT'])) $is_robot = 1;
else $is_robot = 0;

?>