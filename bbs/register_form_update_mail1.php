<?php
// 회원가입축하 메일 (회원님께 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<div style="text-align:center;width:100%;margin:50px 0;><img src="http://etbc.willsoft.kr/ETBC/images/logo_164_34.png"></div>
Hi <?php echo $first_name ? $first_name." ".$last_name : $mb_id;  ?>,
<br>
<br>
Welcome to EOS TEAM BLOCK CHAIN!<br>

Created your Account Information : <br>

ID : <span style="text-decoration:underline; color:blue"> <?=$mb_id?></span> <br>
PASSWORD : <span style="text-decoration:underline; color:blue"> your Email Address</span><br>
<br>
<a href="http://etbc.willsoft.kr">TEAM BLOCK CHAIN HOME.</a> <br>
<br>
Thank you,<br>
EOS TEAM BLOCK CHAIN Support.