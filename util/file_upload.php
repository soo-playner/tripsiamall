<?php
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/Encrypt/rule.php');

$g5['title'] = '파일 저장';

// print_R($_REQUEST);
// print_r($_FILES['bf_file']);



$msg = array();

$ca_name = '';
$write_table = 'g5_write_kyc';
$bo_table = 'kyc';
$w = $_POST['w'];
$wallet_type =$_POST['wr_wallet_type'];

if(!$board['bo_upload_count']){
    $board['bo_upload_count'] = 2;
    $board['bo_upload_size'] = '100048576';
}

$wr_subject = '';
    if (isset($_POST['wr_subject'])) {
        $wr_subject = $_POST['wr_subject'];
    }
    if ($wr_subject == '') {
        $msg[] = '<strong>제목</strong>을 입력하세요.';
        ob_clean();
        echo (json_encode(array("result" => "error",  "code" => "0001", "sql" => 'Please check input username and retry.')));
        return false;
    }

$wr_content = Encrypt($_POST['wr_content'],$secret_key,$secret_iv);
$wr_link1 = '';
$wr_link2 = '';


$msg = implode('<br>', $msg);
if ($msg) {
	alert($msg);
}

$upload_max_filesize = ini_get('upload_max_filesize');

if (empty($_POST)) {
	alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=".$upload_max_filesize."\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");
}

$notice_array = explode(",", $board['bo_notice']);

$secret = '';
if (isset($_POST['secret']) && $_POST['secret']) {
	if(preg_match('#secret#', strtolower($_POST['secret']), $matches))
		$secret = $matches[0];
}

// 외부에서 글을 등록할 수 있는 버그가 존재하므로 비밀글 무조건 사용일때는 관리자를 제외(공지)하고 무조건 비밀글로 등록
if (!$is_admin && $board['bo_use_secret'] == 2) {
	$secret = 'secret';
}

$html = '';
if (isset($_POST['html']) && $_POST['html']) {
	if(preg_match('#html(1|2)#', strtolower($_POST['html']), $matches))
		$html = $matches[0];
}

$mail = '';
if (isset($_POST['mail']) && $_POST['mail']) {
	if(preg_match('#mail#', strtolower($_POST['mail']), $matches))
		$mail = $matches[0];
}

$notice = '';
if (isset($_POST['notice']) && $_POST['notice']) {
	$notice = $_POST['notice'];
}

for ($i=1; $i<=10; $i++) {
	$var = "wr_$i";
	$$var = "";
	if (isset($_POST['wr_'.$i]) && settype($_POST['wr_'.$i], 'string')) {
		$$var = trim($_POST['wr_'.$i]);
	}
}


if ($member['mb_id']) {
    $mb_id = $member['mb_id'];
    $wr_name = addslashes($member['mb_name']);
    $wr_password = $member['mb_password'];
    $wr_email = addslashes($member['mb_email']);
    $wr_homepage = addslashes(clean_xss_tags($member['mb_homepage']));

		// 가입 시 휴대폰번호 입력 안했을 시 휴대폰번호 update 
		
		$mb_hp = trim(str_replace('-', '', $_POST['mb_hp']));
		if($mb_hp != '') {
			$hp_update_sql = "UPDATE {$g5['member_table']} SET mb_hp = '{$mb_hp}' WHERE mb_id = '{$mb_id}'";
			sql_query($hp_update_sql);
		}
		
} else {
    $mb_id = '';
    // 비회원의 경우 이름이 누락되는 경우가 있음
    $wr_name = clean_xss_tags(trim($_POST['wr_subject']));
    if (!$wr_name)
        alert('이름은 필히 입력하셔야 합니다.');
    $wr_password = '';
    $wr_email = '';
    $wr_homepage = '';
}



$sql = " insert into $write_table
set wr_num = '$wr_num',
     wr_reply = '$wr_reply',
     wr_comment = 0,
     ca_name = '$ca_name',
     wr_option = '$html,$secret,$mail',
     wr_subject = '$wr_subject',
     wr_content = '$wr_content',
     wr_link1 = '$wr_link1',
     wr_link2 = '$wr_link2',
     wr_link1_hit = 0,
     wr_link2_hit = 0,
     wr_hit = 0,
     wr_good = 0,
     wr_nogood = 0,
     mb_id = '{$member['mb_id']}',
     wr_password = '$wr_password',
     wr_name = '$wr_name',
     wr_email = '$wr_email',
     wr_homepage = '$wr_homepage',
     wr_datetime = '".G5_TIME_YMDHIS."',
     wr_last = '".G5_TIME_YMDHIS."',
     wr_ip = '{$_SERVER['REMOTE_ADDR']}',
     wr_1 = '$notice',
     wr_2 = '$wr_2',
     wr_3 = '{$_POST['wr_subject']}',
     wr_4 = '$wr_4',
     wr_5 = '{$wallet_type}',
     wr_6 = '$wr_6',
     wr_7 = '$wr_7',
     wr_8 = '$wr_8',
     wr_9 = '$wr_9',
     wr_10 = '$wr_10' ";
sql_query($sql);

//print_R($sql);

$wr_id = sql_insert_id();

// 부모 아이디에 UPDATE
sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

// 새글 INSERT
sql_query(" insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '".G5_TIME_YMDHIS."', '{$member['mb_id']}' ) ");

// 게시글 1 증가
sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");



// 파일개수 체크
$file_count   = 0;
$upload_count = count($_FILES['bf_file']['name']);

echo "<br>step 0 : 파일갯수 <br>";

for ($i=0; $i<$upload_count; $i++) {
	if($_FILES['bf_file']['name'][$i] && is_uploaded_file($_FILES['bf_file']['tmp_name'][$i]))
		$file_count++;

	print_R($upload_count."/".$_FILES['bf_file']['name'][$i]);
}

$board['bo_upload_count'] = 2;

echo "<br>step 1 : filecnt / boardcount <br>";
print_R($file_count. " / " . $board['bo_upload_count']);

if($w == 'u') {
	$file = get_file($bo_table, $wr_id);

	if($file_count && (int)$file['count'] > $board['bo_upload_count'])
		alert('기존 파일을 삭제하신 후 첨부파일을 '.number_format($board['bo_upload_count']).'개 이하로 업로드 해주십시오.');
} else {
	if($file_count > $board['bo_upload_count'])
		alert('첨부파일을 '.number_format($board['bo_upload_count']).'개 이하로 업로드 해주십시오.');
}



// 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

// 가변 파일 업로드
$file_upload_msg = '';
$upload = array();


for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
	$upload[$i]['file']     = '';
	$upload[$i]['source']   = '';
	$upload[$i]['filesize'] = 0;
	$upload[$i]['image']    = array();
	$upload[$i]['image'][0] = '';
	$upload[$i]['image'][1] = '';
	$upload[$i]['image'][2] = '';

	// 삭제에 체크가 되어있다면 파일을 삭제합니다.
	if (isset($_POST['bf_file_del'][$i]) && $_POST['bf_file_del'][$i]) {
		$upload[$i]['del_check'] = true;

		$row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$i}' ");
		@unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
		// 썸네일삭제
		if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
			delete_board_thumbnail($bo_table, $row['bf_file']);
		}
	}
	else
		$upload[$i]['del_check'] = false;

	$tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
	$filesize  = $_FILES['bf_file']['size'][$i];
	$filename  = $_FILES['bf_file']['name'][$i];
    $filename  = get_safe_filename($filename);
    
    echo "<br><br>step 2 filename : <br>";
    print_R($_FILES['bf_file']['name'][$i] . " / ". $filename." / ".$filesize );

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

	echo $file_upload_msg;

	if (is_uploaded_file($tmp_file)) {
		// 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
		if (!$is_admin && $filesize > $board['bo_upload_size']) {
			$file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format($board['bo_upload_size']).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
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
        
        // echo "timg :: ".$timg;

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
        
        echo "<br>step 3 upload_complete : <br>";
        print_R($upload[$i]['source']."/".$upload[$i]['filesize'] );

		// 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
		$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

		shuffle($chars_array);
		$shuffle = implode('', $chars_array);

		// 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
		$upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

		$dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload[$i]['file'];

		// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
		$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);

        // echo "ERROR :: ".$error_code;

		// 올라간 파일의 퍼미션을 변경합니다.
        chmod($dest_file, G5_FILE_PERMISSION);
       
	}
}

// 나중에 테이블에 저장하는 이유는 $wr_id 값을 저장해야 하기 때문입니다.
echo count($upload);
echo "<br>/////////////////<br>";
print_R($upload);
echo "<br>";

for ($i=0; $i<count($upload); $i++)
{
	if (!get_magic_quotes_gpc()) {
		$upload[$i]['source'] = addslashes($upload[$i]['source']);
	}

    $row = sql_fetch(" select count(*) as cnt from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$i}' ");
    
    echo "<br>4 update : ".$row['cnt'];
	echo "<br>";
    
	$sql = " insert into {$g5['board_file_table']}
				set bo_table = '{$bo_table}',
						wr_id = '{$wr_id}',
						bf_no = '{($i+1)}',
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
	print_R($sql);
	
}



// 업로드된 파일 내용에서 가장 큰 번호를 얻어 거꾸로 확인해 가면서
// 파일 정보가 없다면 테이블의 내용을 삭제합니다.
$row = sql_fetch(" select max(bf_no) as max_bf_no from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' ");

for ($i=(int)$row['max_bf_no']; $i>=0; $i--)
{
	$row2 = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$i}' ");

	// 정보가 있다면 빠집니다.
	if ($row2['bf_file']) break;

	// 그렇지 않다면 정보를 삭제합니다.
	sql_query(" delete from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' and bf_no = '{$i}' ");
}

// 파일의 개수를 게시물에 업데이트 한다.
$row = sql_fetch(" select count(*) as cnt from {$g5['board_file_table']} where bo_table = '{$bo_table}' and wr_id = '{$wr_id}' ");
sql_query(" update {$write_table} set wr_file = '{$row['cnt']}' where wr_id = '{$wr_id}' ");

// 자동저장된 레코드를 삭제한다.
sql_query(" delete from {$g5['autosave_table']} where as_uid = '{$uid}' ");
//------------------------------------------------------------------------------


delete_cache_latest($bo_table);

if ($file_upload_msg)
    alert($file_upload_msg, G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table.'&amp;wr_id='.$wr_id.'&amp;page='.$page.$qstr);
    
else
    ob_clean();
    echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => 'kyc upload success')));
?>
