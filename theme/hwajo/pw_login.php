<? include_once(G5_THEME_PATH.'/_include/head.php'); ?>
<style>
#wrapper{
	background:#f8fbff;
	margin: 0 auto;
}
</style>
<section id="wrapper">
	<div class="v_center">
		<div class="login_wrap">
			<div class="logo_login_div">
				<img src="<?=G5_THEME_URL?>/img/logo.png" alt="LOGO">
				<?if(strpos($url,'adm')){echo "<br><span class='adm_title'>For Administrator</span>";}?>
			</div>


			<form name="flogin" action="<?php echo $login_action_url ?>" method="post">
					<input type="hidden" id="url" name="url" value="<?=$url?>">
				<div>
					<label for="u_name"><span>아이디</span></label>
					<input type="text" name="mb_id" id=" u_name" placeholder="아이디 입력" />
				</div>
				<div>
					<label for="u_pw"><span >비밀번호</span></label>
					<input type="password" name="mb_password" id="u_pw" style="line-height:22px;" placeholder="비밀번호 입력" onkeyup="press(this.form)"/>
				</div>
				<style>
					input:active {
						/* background: yellow; */
					}
				</style>

				
				<!-- <div style='text-align:left'>
					<input type="checkbox" name="auto_login"  style="width:auto" id="login_auto_login" checked >
					<label for="login_auto_login" class="auto_login" style="display:inline-block">자동로그인</label>
				</div> -->
				

				<div class="login_btn_bottom">
					<button type="button" class="btn btn_wd btn_primary" onclick="flogin_submit();" ><span>로그인</span></button>
					<a href="/bbs/register_form.php" class="btn btn_wd btn_secondary"><span>회원 가입</span></a>
					<!-- <a href="javascript:temp_block();" class="btn btn_wd btn_default"><span data-i18n="login.신규 회원 등록하기">Create new account</span></a> -->
					<a href="<?=G5_THEME_URL?>/find_pw.php" class='desc' style="font-size:11px;letter-spacing:0;">비밀번호가 기억나지 않나요?</a>
						
				</div>

			</form>

			
		</div>
		
	</div>

	<div class='footer'>
		<p class='copyright'>Copyright ⓒ 2021. WIZCLASS Co. ALL right reserved.</p>
	</div>
	
</section>


<script type="text/javascript">
	function flogin_submit(){
		$('form[name=flogin]').submit();
	}
	function showhelp(){
		$('.helpmail').toggle();
	}
	function press(f){
		console.log(event.keyCode);
		if(event.keyCode == 13){
			f.submit();
		}
	}
	function temp_block(){
	commonModal("Notice",'방문을 환영합니다.<br />사전 가입이 마감되었습니다.<br />가입하신 회원은 로그인 해주세요.<br /><br />Welcome to One-EtherNet.<br />Pre-subscription is closed.<br />If you are a registered member,<br />please log in.',220);
	}
</script>

