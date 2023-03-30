<?php
include_once('./_common.php');

//include_once(G5_LIB_PATH.'/mailer.lib.php');
include_once(G5_LIB_PATH.'/Telegram/telegram_api.php');

$method = $_SERVER['REQUEST_METHOD'];

/* if(!$_GET['is_closed']) $_GET['is_closed'] = '0'; */

if($_GET['is_closed']) {
	$closed = intval(sql_real_escape_string($_GET['is_closed']));
} else {
	$closed = 0;
}


header('Content-Type: application/json');

if($method == 'GET'){
	if($is_admin){
		if($_GET['is_answer']){
			$sql = "select idx, topic, subject, is_closed, if(DATEDIFF(now(),create_date)>0,
			DATE_FORMAT(create_date, '%b-%d %H:%i %p'), TIME_FORMAT(create_date, '%H:%i %p')) as create_date,
			mb_no, t2.answer_dt, is_closed
			from ticket t inner join (
				select pid, max(create_date) as answer_dt from ticket_child where mb_no = 1
					group by pid
			) t2 on t.idx = t2.pid order by idx desc";
		}else{
			$sql = "select idx, topic, subject, is_closed, mb_no,
				if(DATEDIFF(now(),create_date)>0, DATE_FORMAT(create_date, '%b-%d %H:%i %p'), TIME_FORMAT(create_date, '%H:%i %p')) as create_date,
				t2.answer_dt
				from ticket t left outer join (
					select pid, max(create_date) as answer_dt from ticket_child where mb_no = 1
						group by pid
				) t2 on t.idx = t2.pid
			where is_closed = {$closed} order by idx desc";
		}
	}else{
		$sql = "select idx, topic, subject, is_closed, mb_no,
			if(DATEDIFF(now(),create_date)>0, DATE_FORMAT(create_date, '%b-%d %H:%i %p'), TIME_FORMAT(create_date, '%H:%i\ %p')) as create_date,
			t2.answer_dt
			from ticket t left outer join (
				select pid, max(create_date) as answer_dt from ticket_child where mb_no = 1
					group by pid
			) t2 on t.idx = t2.pid
		where is_closed = {$closed} and mb_no = {$member['mb_no']} order by idx desc";
	}

    //print_r($sql);

	$sth = sql_query($sql);
	$rows = array();
	while($r = mysqli_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}else if($method == 'POST'){
	$write_result = sql_query("insert into ticket(topic, subject, mb_no, create_date) values ({$_POST[topic]},'{$_POST[subject]}', {$member[mb_no]}, now())");
	$idx = sql_insert_id();

	if($write_result){
		// 입금알림 텔레그램 API
		if(TELEGRAM_ALERT_USE){
			$sumary_subject = mb_strimwidth($_POST['subject'], 0, 20, "...","utf-8");
			print_R($sumary_subject);
			curl_tele_sent("[ZETABYTE][1:1문의] ".$member['mb_id']." 님이 (".$sumary_subject.")  문의 내용을 남겼습니다.",2);
		}
		
	}
	
	sql_query("insert into ticket_child(content, pid, mb_no, create_date) values ('{$_POST[content]}', ".$idx.", $member[mb_no], now())");

	// 메일 전송
	//shell_exec("php support.ticket.mail.php ".$idx." ".$member['mb_id']." ".$member['mb_email']." ".$_POST['lang']." > /dev/null &");

	///// 파일 업로드 시작 ////////
	$wr_id = $idx;
	$bo_table = 'supportCenter';
	// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
	@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
	@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
	// 가변 파일 업로드
	$file_upload_msg = '';
	$upload = array();
	for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
		if($_FILES['bf_file']['name'][$i] == '') continue;
		$upload[$i]['file']     = '';
		$upload[$i]['source']   = '';
		$upload[$i]['filesize'] = 0;
		$upload[$i]['image']    = array();
		$upload[$i]['image'][0] = '';
		$upload[$i]['image'][1] = '';
		$upload[$i]['image'][2] = '';
		$upload[$i]['del_check'] = false;

		$tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
		$filesize  = $_FILES['bf_file']['size'][$i];
		$filename  = $_FILES['bf_file']['name'][$i];
		$filename  = get_safe_filename($filename);

		// 서버에 설정된 값보다 큰파일을 업로드 한다면
		if ($filename) {
			if ($_FILES['bf_file']['error'][$i] == 1) {
				$file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
				continue;
			}
			else if ($_FILES['bf_file']['error'][$i] != 0) {
				$file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
				continue;
			}
		}

		if (is_uploaded_file($tmp_file)) {
			// 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
			if (!$is_admin && $filesize > (1048576 * 5) ) {
				$file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format(1048576 * 5).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
				continue;
			}

			//=================================================================\
			// 090714
			// 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
			// 에러메세지는 출력하지 않는다.
			//-----------------------------------------------------------------
			$timg = @getimagesize($tmp_file);
			// image type
			if ( preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
				preg_match("/\.({$config['cf_flash_extension']})$/i", $filename) ) {
				if ($timg['2'] < 1 || $timg['2'] > 16)
					continue;
			}
			//=================================================================

			$upload[$i]['image'] = $timg;

			// 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
			if ($w == 'u') {
				// 존재하는 파일이 있다면 삭제합니다.
				$row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
				@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
				// 이미지파일이면 썸네일삭제
				if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
					delete_board_thumbnail($bo_table, $row['bf_file']);
				}
			}

			// 프로그램 원래 파일명
			$upload[$i]['source'] = $filename;
			$upload[$i]['filesize'] = $filesize;

			// 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
			$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

			shuffle($chars_array);
			$shuffle = implode('', $chars_array);

			// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
			$upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

			$dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload[$i]['file'];

			// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
			$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);

			// 올라간 파일의 퍼미션을 변경합니다.
			chmod($dest_file, G5_FILE_PERMISSION);
		}
	}

	// 나중에 테이블에 저장하는 이유는 $wr_id 값을 저장해야 하기 때문입니다.
	for ($i=0; $i<count($upload); $i++)
	{
		if (!get_magic_quotes_gpc()) {
			$upload[$i]['source'] = addslashes($upload[$i]['source']);
		}

		$sql = " insert into {$g5['board_file_table']}
					set bo_table = '{$bo_table}',
						wr_id = '{$wr_id}',
						bf_no = '{$i}',
						bf_source = '{$upload[$i]['source']}',
						bf_file = '{$upload[$i]['file']}',
						bf_content = '{$bf_content[$i]}',
						bf_download = 0,
						bf_filesize = '{$upload[$i]['filesize']}',
						bf_width = '{$upload[$i]['image']['0']}',
						bf_height = '{$upload[$i]['image']['1']}',
						bf_type = '{$upload[$i]['image']['2']}',
						bf_datetime = '".G5_TIME_YMDHIS."' ";
		sql_query($sql);
	}

	///// 파일 업로드 종료 ////////

	if($file_upload_msg){
		header('Location: '.G5_URL.'/page.php?id=support_center&msg='.$file_upload_msg);
	}else{
		header('Location: '.G5_URL.'/page.php?id=support_center&idx='.$pid);
    }


}else if($method == 'PUT'){
	parse_str(file_get_contents("php://input"),$put_vars);

    $update_closed = sql_query("update ticket set is_closed = 1 where idx = {$put_vars['idx']}");

    if($update_closed){
        print json_encode(array('idx' => $put_vars['idx']));
    }
}

?>
