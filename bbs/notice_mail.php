<?
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$noti = sql_fetch("select wr_id, wr_subject, wr_content from g5_write_notice order by wr_id desc limit 1");
$content = conv_content($noti['wr_content'],2);

// 내용에 이미지 추가
$files = sql_query("select wr_id, bf_source, bf_no, bf_file, bf_type from {$g5['board_file_table']} where bo_table = 'notice' and wr_id = {$noti[wr_id]} and bf_type <> 0 order by bf_no desc ");
if($files){
	while($record = mysqli_fetch_assoc($files)) {
		$content = "<img src='https://www.pinnaclemining.net/data/file/notice/{$record[bf_file]}' /><br>".$content;
	}
}

// 회원들에게 발송 - 잘못된 메일때문에 메일 발송 block 되서 보류
// $sth = sql_query("select * from g5_member order by mb_no desc ");
if($sth){
	while($record = mysqli_fetch_assoc($sth)) {
		mailer('pinnaclemining', 'noreply@pinnaclemining.net', $record[mb_email], $noti['wr_subject'], $content, 1);
	}
}
?>