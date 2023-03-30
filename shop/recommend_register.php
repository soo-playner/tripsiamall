<?php
$sub_menu = '100100';
include_once('./_common.php');

$g5['title'] = "신규회원";
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
	/* th {font-size:12px !important}
	td {font-size:12px} */

    .mbskin {}
    .mbskin input[type="text"],
    .mbskin input[type="password"] {width:100%;height:22px;line-height:22px;}
    .mbskin .tbl_frm01 td {position: relative;}
    .btn {display:inline-block;*display:inline;*zoom:1;padding:0;margin:0;color:#fff;padding:3px;background-color:rgba(59,105,178,1);vertical-align:middle;}

    p.explain {line-height: 30px;color: #696969;margin-bottom:40px;word-break:break-word;}
    div.acc_here {font-size: 24px;color: #3498db;padding: 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);}
    div.btn_confirm {padding: 10px 20px 10px 20px;background-color: white;margin-bottom: 0px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);overflow: hidden;}
    .tbl_frm01 {margin: 0;}
    .tbl_frm01 td {padding: 0px;border: 0px;}
    div.agree_txt {padding: 10px 20px 10px 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);line-height: 30px;}
    div.regi_box {padding: 40px 20px 40px 20px;background-color: white;margin-bottom: 10px;box-shadow: 0 0 3px rgba(0, 0, 0, 0.3);}
    div.regi_box input[type=text],div.regi_box input[type=password] {margin-bottom:30px;padding:0 0 0 10px;background: none !important;height: 35px !important;line-height: 35px !important;width: 100%; }
    #ajax_rcm_search {position:absolute; top:0; right:0; border-radius: 7px;padding: 4px 10px 9px 10px;}
    .mbskin .btn_cancel{background-color: #2980b9;color:white;border-radius:5px;line-height: 30px;padding: 2px 15px;letter-spacing: 0;}
    .mbskin .btn_submit {background-color: #2ecc71;color:white;border-radius:5px;line-height: 30px;padding: 2px 15px;letter-spacing: 0;}
    .terms_popup {position: fixed;width: 600px;height: 600px;top: 83px;background: #fff;overflow-y: scroll;z-index: 10000;border: 1px solid #ccc;word-break: break-word;padding: 22px;box-shadow: 5px 3px 32px 0px #000;}
    #nation_number{margin-bottom: 30px;    border: 1px solid #e4eaec; height: 35px !important;line-height: 35px !important;}
    #reg_mb_hp{width:60%;}
    #framer {margin-left: -250px;width: 500px;}
    #ajax_mp_search {position: absolute;top: 0;right: 0;border-radius: 7px;padding: 4px 10px 9px 10px;}
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

        // E-mail 검사
        if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
            var msg = reg_mb_email_check();
            if (msg) {
                alert(msg);
                f.reg_mb_email.select();
                return false;
            }
        }

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
if ($now_id){
	$main_id = $now_id;
}else{
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
if($member['is_marketer'] == 'Y'){
    $gp = 'mp';
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
    <input type="hidden" name="gp" value="<?=$gp?>">   
     
    <!-- <input type="hidden" name="mb_brecommend" value="<?=$brecommend?>">    -->
    <input type="hidden" name="mb_brecommend_type" value="<?=$brecommend_type?>">   

    <div class="tbl_frm01 tbl_wrap">
        <table style="">
            <?if ($main_id && $member['is_marketer'] == 'N'){?>
            <tr>
                <td colspan="2">
                    <strong>Referrer's username : <span style="color:orange;"><?=$main_id?></span></strong><br>
                    <input type="hidden" name="mb_recommend" value="<?=$main_id?>">  
                    <br>
                </td>
            </tr>
            <?} else {?>
                <td colspan="2">
                    <strong>MP's username : <span style="color:orange;"><?=$main_id?></span></strong><br>
                    <input type="hidden" name="mb_mprecommend" value="<?=$main_id?>">  
                    <br>
                </td>
            <?} ?>
            <tr>
                <td>
                    <input type="text" placeholder="Username" name="mb_id" id="reg_mb_id" oninvalid="this.setCustomValidity('Enter Username Here')" oninput="this.setCustomValidity('')" <?php echo $required ?> <?php echo $readonly ?> class="frm_input <?php echo $required ?> <?php echo $readonly ?>" minlength="2" maxlength="20">
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="password" name="mb_password" placeholder="Password" id="reg_mb_password" class="frm_input required" minlength="3" maxlength="20" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="password" name="mb_password_re" placeholder="Confirm-Password" id="reg_mb_password_re" class="frm_input required" minlength="3" maxlength="20" required></td>
            </tr>
            <tr>
                <td>
                    <input type="text" id="first_name" name="first_name" placeholder="First Name"  <?php echo $required ?>  class="frm_input <?php echo $required ?> " size="10" oninvalid="this.setCustomValidity('Enter First Name Here')" oninput="this.setCustomValidity('')" />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" id="last_name" name="last_name" placeholder="Last Name"  <?php echo $required ?>  class="frm_input <?php echo $required ?> " size="10" oninvalid="this.setCustomValidity('Enter Last Name Here')" oninput="this.setCustomValidity('')" />
                </td>
            </tr>
            <tr>
                <td>
                    <input type="text" name="mb_email" placeholder="Email" id="reg_mb_email" required class="frm_input email required" size="70" maxlength="100" oninvalid="this.setCustomValidity('Enter Email Here')" oninput="this.setCustomValidity('')" />
                </td>
            </tr>
            <tr>
                <td>
                    <select id="nation_number" name="nation_number" required >
                        <option value=""  >--</option>
                        <option value="082" >Korea - 082aa</option>
                        <option value="001" >U.S.A - 001</option>
                        <option value="086" >China - 086</option>
                        <option value="081" >Japan - 081</option>
                        <option value="061" >Australia - 061</option>
                    </select>
                    <input type="text" name="mb_hp" id="reg_mb_hp" <?php echo ($config['cf_req_hp'])?"required":""; ?> class="frm_input <?php echo ($config['cf_req_hp'])?"required":""; ?>" maxlength="20" placeholder="Mobile Number" >
                    <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                    <?php } ?>
                </td>
            </tr>
            <?if ($member['is_marketer'] == 'Y'){?>
            <tr>
                <td colspan="2" style="position:relative;">
                    <input type="text" name="mb_recommend" id="reg_mb_recommend" placeholder="Referrer’s Username" class="frm_input" oninvalid="this.setCustomValidity('Enter Referrer’s Username Here')" value="<?=$_GET['mb_recommend']?>" oninput="this.setCustomValidity('')" required />
                    <span id="ajax_rcm_search" class="btn">
                        <i class="fa fa-search"></i>
                    </span>
                </td>
            </tr>
            <?} else {?>
                <tr>
                <td colspan="2" style="position:relative;">
                    <input type="text" name="mb_mprecommend" id="reg_mb_mprecommend" placeholder="MP’s Username" class="frm_input" />
                    <span id="ajax_mp_search" class="btn">
                        <i class="fa fa-search"></i>
                    </span>
                </td>
            </tr>
            <?} ?>
        </table>
    </div>

    <div align=center style="padding:30px 0px 30px 0px">
	    <input type="submit" id="btn_submit" style="padding:4px 8px 4px 8px;border:0px;background:#364fa0 !important;color:#ffffff;cursor:pointer" value="register">
        <input type="button" value="close" onclick="self.close();" style="display:inline-block;padding:3px 7px 3px 7px;border:1px solid #3b3c3f;background:#4b545e !important;color:#ffffff;text-decoration:none;vertical-align:middle;cursor:pointer">
    </div>

	</form>
</div>

