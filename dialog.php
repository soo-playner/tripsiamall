<?
include_once("./_common.php");
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

//include_once(G5_PATH.'/head.php');
$val = $_REQUEST;

if($_GET['id']){
	include_once(G5_THEME_PATH.'/'.$_GET['id'].'.php');
}
?>
