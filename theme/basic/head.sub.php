<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if (!isset($g5['title'])) {
    $g5['title'] = $config['cf_title'];
    $g5_head_title = $g5['title'];
}
else {
    $g5_head_title = $g5['title']; // 상태바에 표시될 제목
    $g5_head_title .= " | ".$config['cf_title'];
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$g5['lo_location'] = addslashes($g5['title']);
if (!$g5['lo_location'])
    $g5['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$g5['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($g5['lo_url'], '/'.G5_ADMIN_DIR.'/') || $is_admin == 'super') $g5['lo_url'] = '';

/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<?php
if (G5_IS_MOBILE) {
    echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10,user-scalable=yes">'.PHP_EOL;
    echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
    echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
} else {
    echo '<meta http-equiv="imagetoolbar" content="no">'.PHP_EOL;
    echo '<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">'.PHP_EOL;
}

if($config['cf_add_meta'])
    echo $config['cf_add_meta'].PHP_EOL;
?>
<meta name="naver-site-verification" content="ced03219f17ed6a1bc13e5ad823b23c0b922a47a"/>
<title><?php echo $g5_head_title; ?></title>

<link rel="stylesheet" href="<?php echo G5_THEME_CSS_URL; ?>/all.css?<?=time()?>">

<?php
$shop_css = '';
if (defined('_SHOP_')) $shop_css = '_shop';
if (!defined('_INDEX_') && !defined('_SUB_')) {
	echo '<link rel="stylesheet" href="'.G5_THEME_CSS_URL.'/'.(G5_IS_MOBILE?'mobile':'default').$shop_css.'.css">'.PHP_EOL;
}
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->

<!-- favicon -->
<link rel="shortcut icon" href="http://pinnacle_mining.qtorrent.co.kr/img/favicon.ico">
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var g5_url       = "<?php echo G5_URL ?>";
var g5_bbs_url   = "<?php echo G5_BBS_URL ?>";
var g5_is_member = "<?php echo isset($is_member)?$is_member:''; ?>";
var g5_is_admin  = "<?php echo isset($is_admin)?$is_admin:''; ?>";
var g5_is_mobile = "<?php echo G5_IS_MOBILE ?>";
var g5_bo_table  = "<?php echo isset($bo_table)?$bo_table:''; ?>";
var g5_sca       = "<?php echo isset($sca)?$sca:''; ?>";
var g5_editor    = "<?php echo ($config['cf_editor'] && $board['bo_use_dhtml_editor'])?$config['cf_editor']:''; ?>";
var g5_cookie_domain = "<?php echo G5_COOKIE_DOMAIN ?>";
</script>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo G5_JS_URL ?>/jquery.carouFredSel-5.5.0-packed.js"></script>

<?php 
	if(defined('_INDEX_')) { // index에서만 실행
?>
<script src="/js/slide.js?<?=time()?>"></script>
<script src="/js/all.js?<?=time()?>"></script>
<?php 
	} 
?>
<?php
if (defined('_SHOP_')) {
    if(!G5_IS_MOBILE) {
?>
<script src="<?php echo G5_JS_URL ?>/jquery.shop.menu.js"></script>
<?php
    }
} else {
?>
<script src="<?php echo G5_JS_URL ?>/jquery.menu.js"></script>
<?php } ?>
<script src="<?php echo G5_JS_URL ?>/common.js"></script>
<script src="<?php echo G5_JS_URL ?>/wrest.js"></script>
<?php
if(G5_IS_MOBILE) {
    echo '<script src="'.G5_JS_URL.'/modernizr.custom.70111.js"></script>'.PHP_EOL; // overflow scroll 감지
}
if(!defined('G5_IS_ADMIN'))
    echo $config['cf_add_script'];
?>
</head>
<body<?php echo isset($g5['body_script']) ? $g5['body_script'] : ''; ?>>
<?php
if ($is_member) { // 회원이라면 로그인 중이라는 메세지를 출력해준다.
    $sr_admin_msg = '';
    if ($is_admin == 'super') $sr_admin_msg = "최고관리자 ";
    else if ($is_admin == 'group') $sr_admin_msg = "그룹관리자 ";
    else if ($is_admin == 'board') $sr_admin_msg = "게시판관리자 ";

    //echo '<div id="hd_login_msg">'.$sr_admin_msg.get_text($member['mb_nick']).'님 로그인 중 ';
    //echo '<a href="'.G5_BBS_URL.'/logout.php">로그아웃</a></div>';
}
?>

<?
/*## layer id search ################################################*/
/*## layer id search ################################################*/
?>
<div id="framewrp">
<style type="text/css">
#framewrp {position:fixed;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;display:none;}
#framer {position:absolute;left:50%;margin-left:-300px;top:50%;margin-top:-250px;width:600px;height:500px;background-color:#fff;border:0;}
@media screen and (max-width:480px) {
#framer {position:absolute;left:0;margin-left:0;top:10%;margin-top:0;width:100%;height:80%;}
}
</style>

	<iframe name='framer' id="framer" frameborder="0"></iframe> 
</div><!-- // framewrp -->
<script>
$(function(){
	$('span[id^="ajax_"]').click(function () {
		var $type = $(this).attr("id").replace("ajax_","");
		if ($type == "id_search") {
			var $search = $('#set_id_sel').val();
			$('#framer').attr("src","/shop/ajax.id.php?mbid="+$search);
			$('#framewrp').fadeIn();
		} else if ($type == "rcm_search") {
			var $rcm = $('#mb_recommend').val();
			if (!$rcm) {
				var $rcm = $('#reg_mb_recommend').val();
			}		
			var $mb_id = $('#mb_id').val();
			$('#framer').attr("src","/shop/ajax.id.php?mb_id="+$mb_id+"&rcm="+$rcm);
			$('#framewrp').fadeIn();
		} else if ($type == "mp_search") {
			var $marketer = $('#reg_mb_mprecommend').val();
			var $mb_id = $('#mb_id').val();
			$('#framer').attr("src","/shop/ajax.mp_id.php?mb_id="+$mb_id+"&marketer="+$marketer);
			$('#framewrp').fadeIn();
		}
	});
	/* enter key #################### */
	$("input[id='reg_mb_recommend']").keydown(function (e) {
		
         if(e.keyCode == 13){//키가 13이면 실행 (엔터는 13)
			e.preventDefault();
				var $rcm = $('#reg_mb_recommend').val();

				var $mb_id = $('#mb_id').val();
				$('#framer').attr("src","/shop/ajax.id.php?mb_id="+$mb_id+"&rcm="+$rcm);
				$('#framewrp').fadeIn();
        }
	 });
	 /* 마케터 엔터키 */
	 $("input[id='reg_mb_mprecommend']").keydown(function (e) {
		
		if(e.keyCode == 13){//키가 13이면 실행 (엔터는 13)
		   e.preventDefault();
			   var $marketer = $('#reg_mb_mprecommend').val();

			   var $mb_id = $('#mb_id').val();
			   $('#framer').attr("src","/shop/ajax.mp_id.php?mb_id="+$mb_id+"&marketer="+$marketer);
			   $('#framewrp').fadeIn();
	   }
	});
	/* enter key #################### */
	$('span[id^="insert_id"]').click(function () {
		var $mb_id = $('#set_id_sel').val();
		$.ajax({
			type: "POST",
			url: "<?=G5_SHOP_URL?>/ajax.id.php",
			data: {
				"mb_id":$mb_id
			},
			cache: false,
			async: false,
				error : function (request, status, error) { // error
							alert("code : " + request.status + "\r\nmessage : " + request.responseText);
						},
				success: function(data) {
					var $datas = $.trim(data).split("^");
					$('#od_name, #od_b_name').val($datas[0]);
					$('#od_tel, #od_b_tel').val($datas[1]);
					$('#od_hp, #od_b_hp').val($datas[2]);
					$('#od_zip, #od_b_zip').val($datas[3]);
					$('#od_addr1, #od_b_addr1').val($datas[4]);
					$('#od_addr2, #od_b_addr2').val($datas[5]);
					$('#od_addr3, #od_b_addr3').val($datas[6]);
					$('#od_email').val($datas[7]);
			}
		});
	});
	$('#framewrp').click(function () {
		$(this).hide();
	});
});
</script>
<?
/*@@End. layer id search #####*/
/*@@End. layer id search #####*/
?>
