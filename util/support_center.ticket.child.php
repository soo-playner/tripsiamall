<?php
include_once('./_common.php');
//include_once(G5_LIB_PATH.'/mailer.lib.php');

$method = $_SERVER['REQUEST_METHOD'];

$idx = intval($_GET['idx']);

header('Content-Type: application/json');

if($method == 'GET'){ // 상세 댓글 출력
	// $sql = "select a.idx, a.content, a.pid, if(DATEDIFF(now(),a.create_date)>0,DATE_FORMAT(a.create_date, '%b %d'), TIME_FORMAT(a.create_date, '%H:%i %p')) as create_date, a.mb_no, b.mb_name, b.mb_id from ticket_child a
	// 			inner join g5_member b on a.mb_no = b.mb_no
	// 		where a.pid = {$_GET[idx]} order by a.idx desc";
	$sql = "select a.idx, a.content, a.pid, a.mb_no, b.mb_name, b.mb_id,
			c.wr_id, c.bf_source, c.bf_no,
			if(DATEDIFF(now(),a.create_date)>0,DATE_FORMAT(a.create_date, '%b %d %H:%i %p' ), TIME_FORMAT(a.create_date, '%H:%i %p')) as create_date
		from ticket_child a
			inner join g5_member b on a.mb_no = b.mb_no
			left outer join g5_board_file c on c.wr_id = a.idx and c.bo_table = 'supportCenterChild'
		where a.pid = {$idx}
		order by a.idx desc";
	$sth = sql_query($sql);
	$rows = array();
	while($r = mysqli_fetch_assoc($sth)) {
		$r['is_admin'] = ($member['mb_no'] != $r['mb_no']);
		$rows[] = $r;
		if($r['wr_id']){
			$ss_name = 'ss_view_supportCenterChild_'.$r['wr_id'];
			set_session($ss_name, TRUE);
		}
	}

	// 파일
	$ss_name = 'ss_view_supportCenter_'.$idx;
	set_session($ss_name, TRUE);

	$sql = " select wr_id, bf_source, bf_no from {$g5['board_file_table']} where bo_table = 'supportCenter' and wr_id = {$idx} ";
	$file = sql_fetch($sql,true);

	print json_encode(array('list'=>$rows,'file'=>$file));
}else if($method == 'POST'){ // 상세 댓글 작성

	$pid = $_POST['idx'];

	$content = conv_content($_POST['content'],2);
	sql_query("insert into ticket_child(content, pid, mb_no, create_date) values ('{$content}', {$pid}, $member[mb_no], now())");
	$idx = sql_insert_id();

    /*
	if($member['mb_no'] = 1) {
		shell_exec("php support.mail.php ".$idx." > /dev/null &");
	}
    */

	///// 파일 업로드 시작 ////////
	$wr_id = $idx;
	$bo_table = 'supportCenterChild';
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
    //$path = 'Location: '.G5_URL.'/page.php?id=support_center.php?msg='.$file_upload_msg;
    //print_r($path);


	if($file_upload_msg){
		header('Location: '.G5_URL.'/page.php?id=support_center&msg='.$file_upload_msg);
	}else{
		header('Location: '.G5_URL.'/page.php?id=support_center&idx='.$pid);
    }


	//print json_encode(array('pid' => $_POST['idx']));
}

?>
