<?php
$sub_menu = "600600";
include_once('./_common.php');
include_once('./inc.member.class.php');

$now_time = time();
//매출 생성
make_price();

echo "<br>\n";
echo cal_time(time()-$now_time)."<br>\n";

function cal_time($input_time){
	$cal_time = $input_time;
	$secs     = $cal_time % 60; 
	$cal_time = floor($cal_time / 60); 
	$minutes  = $cal_time % 60; 
	$cal_time = floor($cal_time / 60); 
	$hours    = $cal_time % 24; 
	$return_time = "";
	if ($hours < 10) $return_time = $return_time . '0' . $hours .':';
	else  $return_time = $return_time . $hours .':';

	if ($minutes < 10) $return_time = $return_time . '0' . $minutes .':';
	else  $return_time = $return_time . $minutes .':';

	if ($secs >= 10) $return_time = $return_time . $secs;
	else $return_time = $return_time . '0' . $secs;

	return $return_time;
}

?>