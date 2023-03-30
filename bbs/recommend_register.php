<?php
$sub_menu = '100100';
include_once('./_common.php');

$g5['title'] = "이미지";
include_once('head_popup.php');


?>
<script type="text/javascript">
<!--
function set_recommend(mb_id){
	document.sForm.recommend_id.value = mb_id;
	document.sForm.submit();
}
function del_recommend(mb_id){
	document.sForm.del_id.value = mb_id;
	document.sForm.submit();
}
//-->
</script>
<link rel="stylesheet" href="/mobile/skin/member/basic/style.css">
<style type="text/css">
	body {background:#fff}
	html {background:#fff}
	th {font-size:12px !important}
	td {font-size:12px}
</style>
<style type="text/css">
    .mbskin {}
    .mbskin input[type="text"],
    .mbskin input[type="password"] {width:60%;height:22px;line-height:22px;}

    .btn {display:inline-block;*display:inline;*zoom:1;padding:0;margin:0;color:#fff;padding:3px;background-color:rgba(59,105,178,1);vertical-align:middle;}

    p.explain {line-height: 30px;color: #95a5a6;margin-bottom:40px;}
    div.acc_here {font-size: 24px;color: #3498db;padding: 10px 20px 20px 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);}
    div.btn_confirm {padding: 10px 20px 10px 20px;background-color: white;margin-bottom: 0px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);overflow: hidden;}
    .tbl_frm01 {margin: 0px;}
    .tbl_frm01 td {padding: 0px;border: 0px;}
    div.agree_txt {padding: 10px 20px 10px 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);line-height: 30px;}
    div.regi_box {padding: 30px 20px 20px 20px;background-color: white;margin-bottom: 0px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);}
    div.regi_box input {margin-bottom:20px;padding:5px 0 6px 30px;background: none !important;}
    #ajax_rcm_search {float: right;border-radius: 7px;padding: 4px 10px 9px 10px;}
    .mbskin .btn_cancel{background-color: #2980b9;color:white;border-radius:5px;line-height: 30px;padding: 2px 15px;}
    .mbskin .btn_submit {background-color: #2ecc71;color:white;border-radius:5px;line-height: 30px;padding: 2px 15px;}
</style>

<div style="padding:20px 20px 20px 20px;">
   
<script src="/js/jquery.register_form.js"></script>
<script type="text/javascript">
<!--
    function fregisterform_submit(f)
    {
        // 회원아이디 검사
        if (f.w.value == "") {
            var msg = reg_mb_id_check();
            if (msg) {
                alert(msg);
                f.mb_id.select();
                return false;
            }
        }

        if (f.w.value == '') {
            if (f.mb_password.value.length < 3) {
                alert('비밀번호를 3글자 이상 입력하십시오.');
                f.mb_password.focus();
                return false;
            }
        }

        if (f.mb_password.value != f.mb_password_re.value) {
            alert('비밀번호가 같지 않습니다.');
            f.mb_password_re.focus();
            return false;
        }

        if (f.mb_password.value.length > 0) {
            if (f.mb_password_re.value.length < 3) {
                alert('비밀번호를 3글자 이상 입력하십시오.');
                f.mb_password_re.focus();
                return false;
            }
        }

        // 이름 검사
        if (f.w.value=='') {
            if (f.mb_name.value.length < 1) {
                alert('이름을 입력하십시오.');
                f.mb_name.focus();
                return false;
            }
        }

        
        // 닉네임 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_nick.defaultValue != f.mb_nick.value)) {
            var msg = reg_mb_nick_check();
            if (msg) {
                alert(msg);
                f.reg_mb_nick.select();
                return false;
            }
        }

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }



        if (typeof(f.mb_recommend) != 'undefined' && f.mb_recommend.value) {
			/*
            if (f.mb_id.value == f.mb_recommend.value) { // mb_id 사용안함
                alert('본인을 추천할 수 없습니다.');
                f.mb_recommend.focus();
                return false;
            }
			*/

            var msg = reg_mb_recommend_check();

            if (msg) {
                alert(msg);
                f.mb_recommend.select();
                return false;
            }
        }

        if (!chk_captcha()) return false;

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
//-->
</script>
    <div class="acc_here">
        Get your account here
    </div>
    <div class="regi_box">
<?
if (!$main_id){
	$main_id = $member['mb_id'];
}

$sql = "select count(*) as cnt from g5_member where mb_brecommend='{$main_id}'";
$row = sql_fetch($sql);

if ($row['cnt']==2){ //PASS

}else if ($row['cnt']==1){ //PASS
	$sql  = "select * from g5_member where mb_brecommend='{$main_id}'";
	$row2 = sql_fetch($sql);
	if ($row2['mb_brecommend_type']=="L"){
		$brecommend_type = "R";		
	}else{
		$brecommend_type = "L";		
	}
	$brecommend      = $main_id;

}else{ //없으면
	$brecommend = $main_id;
	$brecommend_type = "L";		
}


?>
<form name="fregisterform" id="fregisterform" action="/bbs/register_form_update.php" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
<input type="hidden" name="w" value="">
<input type="hidden" name="wx" value="Y">
<input type="hidden" name="url" value="">
<input type="hidden" name="agree" value="">
<input type="hidden" name="agree2" value="">
<input type="hidden" name="cert_type" value="">
<input type="hidden" name="cert_no" value="">
<input type="hidden" name="mb_sex" value="">   
<input type="hidden" name="mb_recommend" value="<?=$main_id?>">   
<input type="hidden" name="mb_brecommend" value="<?=$brecommend?>">   
<input type="hidden" name="mb_brecommend_type" value="<?=$brecommend_type?>">   

<div class="tbl_frm01 tbl_wrap">
        <table style="">
        <tr>
            <td colspan="2">
                <input type="text" id="reg_mb_name" name="mb_name" value="" required  placeholder="FullName" class="frm_input required ">
                                <script>
$(function(){
	$('#reg_mb_name').change(function () {
		var $val = $(this).val();
		$('#reg_mb_nick').val($val);
	});
});
</script>
				<input type="hidden" name="mb_nick" id="reg_mb_nick" value="" />
            </td>
        </tr>

        <tr>
            <td>
              <span class="frm_info">
                    Verify your email address to complete the sign-up                                    </span>
                                <input type="hidden" name="old_email" value="">
                <input type="email" name="mb_email" placeholder="Email" value="" id="reg_mb_email" required class="frm_input email required" size="50" maxlength="100" style="width:80%;">
            </td>
        </tr>

        
        
        
                </table>
    </div>
    <div class="tbl_frm01 tbl_wrap">
        <table>

        <tr>
            <td colspan="2"><input type="password" name="mb_password" placeholder="Password" id="reg_mb_password" class="frm_input required" minlength="3" maxlength="20" required></td>
        </tr>
        <tr>
            <td colspan="2"><input type="password" name="mb_password_re" placeholder="Confirm-Password" id="reg_mb_password_re" class="frm_input required" minlength="3" maxlength="20" required></td>
        </tr>
        </table>
    </div>


	</div>



		<div align=center style="padding:30px 0px 30px 0px">
	
		 <input type="submit" style="padding:4px 8px 4px 8px;border:0px;background:#364fa0;color:#ffffff;cursor:pointer" value="회원등록">

		 <input type="button" value="close" onclick="self.close();" style="display:inline-block;padding:3px 7px 3px 7px;border:1px solid #3b3c3f;background:#4b545e;color:#ffffff;text-decoration:none;vertical-align:middle;cursor:pointer">
	
		</div>

	</form>
</div>

