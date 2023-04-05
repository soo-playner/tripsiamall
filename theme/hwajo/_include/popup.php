<script>

function open_pop_close(){
	console.log('closed');

	dimHide();
	$('.pop_wrap').css("display","none");
	// $('.dim').css("display","none");
	$('body').css({"overflow":"auto","height":"inherit"});
};

function close_pop_open(){
	// $('.dim').css("display","block");
	dimShow();
	$('body').css({"overflow":"hidden","height":"100%"});
}


function dimShow() {
	$('.dim').css("display", "block");
	$('body').css({"overflow": "hidden","height": "100%"});
	$('.dim').bind('click',open_pop_close);
};

function dimHide() {
	$('.dim').css("display", "none");
	$('body').css({"overflow": "auto","height": "inherit"});
};


// 비밀번호 조합
function CheckPass(str){
 var reg = /^(?=.*[a-zA-Z])(?=.*[!@#$%^&*=+])(?=.*[0-9]).{4,12}/;
 return reg.test(str);
}

function onlyNumber(id){
	document.getElementById(id).oninput = function(){
		// if empty
		if(!this.value) return;

		// if non numeric
		let isNum = this.value[this.value.length - 1].match(/[0-9]/g);
		if(!isNum) this.value = this.value.substring(0, this.value.length - 1);

	}
}



$(function() {

	$('.pop_open').bind('click',close_pop_open);
	$('.pop_close').bind('click',open_pop_close);



	/*회원가입완료*/
	function enroll_result(){
		console.log("enroll_ok");
		$('.enroll_ok_pop').css("display", "block");
	}

	$('.enroll_cancel_pop_open').click(function() {
		$('.enroll_cancel_pop').css("display", "block");
	});
	$('.search_result_btn').click(function() {
		$('.search_result').css("display", "none");
	});


	//send coin
	$('.send_tran_open').click(function(){
		$('.send_tran_pop').css("display","block");
	});
	$('.low_bal_pop_open').click(function(){
		$('.low_bal_pop').css("display","block");
	});
	$('.low_gas_pop_open').click(function(){
		$('.low_gas_pop').css("display","block");
	});


	//send_chk
	$('.send_coin_ok_open').click(function(){
		$('.send_coin_ok_pop').css("display","block");
	});


	//support
	$('.support_ok_pop_open').click(function() {
		$('.support_ok_pop').css("display", "block");
	});

});
</script>


<!-- 
<div class="loader">
	<p><img src="/img/loader.png"></p>
	<div class="comment"></div>
</div> -->



<div class="search_result pop_wrap">
	<strong>RESULT</strong>
	<ul>
		<li>rose</li>
		<li>rose777</li>
		<li>rose7</li>
		<li>rose8</li>
		<li>rose99</li>
		<li>roserose</li>
	</ul>
	<a class="search_result_close btn_basic pop_close" data-i18n='popup.창닫기'>Close</a>
</div>


<div class="pop_wrap email_pop_wrap notice_img_pop">
	<p class="pop_title"data-i18n='popup.인증번호 전송'>Email verification</p>
	<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="체크">
	<div data-i18n='popup.인증번호가 이메일로 전송되었습니다'>Security code sent to your email</div>
	<a href="javascript:void(0);" class="btn inline wd pop_close" data-i18n='popup.창닫기'>Close</a>
</div>



<div class="pop_wrap notice_img_pop enroll_ok_pop">
	<p class="pop_title" data-i18n='popup.신규 회원등록 완료'>new member signup</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span data-i18n='popup.신규회원등록이 완료되었습니다'>signup completed successfully.</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="btn inline wd pop_close"  data-i18n='popup.창닫기'>Close</a>
	</div>
</div>

<div class="pop_wrap notice_img_pop enroll_cancel_pop">
	<p class="pop_title">회원 등록 실패</p>
	<div>
		<img src="<?=G5_URL?>/img/notice.svg" alt="이미지">
		<span>회원 등록 실패</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:location.href='/';" class="btn inline wd pop_close" data-i18n='popup.창닫기'>창닫기</a>
	</div>
</div>



<!-- avatar -->
<!-- <div class="pop_wrap notice_img_pop ava_pop_wrap">
	<p class="pop_title">설정 저장</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span>적립 비율이 성공적으로 변경되었습니다</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div> -->


<!-- support -->
<div class="pop_wrap notice_img_pop support_ok_pop">
	<p class="pop_title">티켓 전송</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span>티켓이 성공적으로 전송되었습니다</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="btn inline wd pop_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>



<!-- send coin -->
<div class="pop_wrap send_tran_pop">
	<p class="pop_title"  data-i18n='popup.거래 승인'>Confirm the transaction</p>
	<div>
		<p data-i18n='popup.거래 비밀번호를 입력하여 송금을 확인합니다'>Please enter the transaction password to verify the transaction</p>
		<input type="text" placeholder="Transaction Password">
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="btn inline wd pop_close" data-i18n='popup.취소'>Cancle</a>
		<input type="button" value="Approve" class="not_btn_style">
	</div>
</div>

<div class="pop_wrap notice_img_pop low_bal_pop">
	<p class="pop_title" data-i18n='popup.지갑 잔고 부족'>Insufficient Funds</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span data-i18n='popup.지갑에 잔고가 부족합니다'>Not enough balance</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="btn inline wd pop_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>

<div class="pop_wrap notice_img_pop low_gas_pop">
	<p class="pop_title" data-i18n='popup.지갑 잔고 부족'>Insufficient Funds</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span data-i18n='popup.지갑에 개스비가 부족합니다.'>Insufficient funds to pay gas</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>




<!-- send chk -->
<div class="pop_wrap notice_img_pop send_coin_ok_pop">
	<p class="pop_title" data-i18n='popup.지갑잔고 부족'>Insufficient Funds</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span data-i18n='popup.지갑에 잔고가 부족합니다.'>Not enough balance</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>






<!-- 로그아웃 -->
<div class="pop_wrap notice_img_pop logout_pop" style="z-index:9999;">
	<p class="pop_title"  data-i18n="popup.로그 아웃">로그아웃</p>
	<div>
		<img src="<?=G5_URL?>/img/notice.svg" alt="이미지">
		<span >로그아웃 하시겠습니까?</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="btn inline wd btn_default pop_close">취소</a>
		<a href="/bbs/logout.php" class="btn inline wd btn_default black" style='margin-top:10px;'>로그아웃</a>
	</div>
</div>


<script>
	$(function() {
		$('.logout_pop_open').click(function(){
			$('.logout_pop').css("display","block");
			$('.dim').show();
		});
	});
</script>












