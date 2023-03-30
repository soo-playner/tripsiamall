<?php
include_once('./_common.php');


if($_POST['func'] == 'reset'){
    $reset_sql = "UPDATE g5_member set mb_index = 0";
    $result = sql_query($reset_sql);
}


// 파일 목록 가져오기
$query = sql_query("select * from m3cron_config limit 0,1", false);

while($prog = sql_fetch_array($query)) {
	
	// 활성화 된 경우만 실행
	if(!$prog['status']) continue;

    

	// 로봇으로 실행시키는 경우
	if($prog['robot'] && $is_robot) continue;

    
	// 일단은 마지막 실행 시각 기록
    sql_query("update `{$m3cron['config_table']}` set lastrun='".G5_TIME_YMDHIS."' where name='{$prog['name']}' limit 1");

	// 실행 시간 구함
	$runtime = '0.00222707';

	// 마지막 실행 시간 기록
    sql_query("update `{$m3cron['config_table']}` set lastruntime='{$runtime}' where name='{$prog['name']}' limit 1");

    $log_sql = "insert into `{$m3cron['log_table']}` set name='{$prog['name']}', datetime='".G5_TIME_YMDHIS."', runtime='{$runtime}', ip='".$_SERVER['REMOTE_ADDR']."', robot='{$is_robot}', mb_id='{$member['mb_id']}'";
    

    // 로그 남김
    $log_result = sql_query($log_sql);
    
}


if($result && $log_result){
    // ob_end_clean();
	echo (json_encode(array("result" => "sucess",  "code" => "0000", "sql" => "리셋완료")));
}
?>

