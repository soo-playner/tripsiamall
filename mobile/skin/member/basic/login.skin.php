
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LOG IN</title>
  <link rel="shortcut icon" href="/favicon.png">

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
  <link rel="stylesheet" type="text/css"
    href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css"
    integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
  <link rel="stylesheet" href="<?=$member_skin_url;?>/login.1.css">

  <!-- <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous">
  </script>
  <!--<script src='/js/jquery-captcha.min.js'></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/1.9.0/i18next.min.js" type="text/javascript"></script>

  <script type="text/javascript">

    function commonModal(title, htmlBody, bodyHeight) {
      $('#commonModal').modal('show');
      $('#commonModal .modal-header .modal-title').html(title);
      $('#commonModal .modal-body').html(htmlBody);
      if (bodyHeight) $('#commonModal .modal-body').css('height', bodyHeight + 'px');
    }

    function flogin_submit() {
      /*
      	$.ajax({
      		method: 'POST',
      		url: '/botDetect/simple-api-jquery-captcha-example/form/basic.php',
      		data: JSON.stringify({
      			captchaId: captcha.getCaptchaId(),
      			captchaCode: captcha.getCaptchaCode()
      		}),
      		async: false,
      		success: function(response) {
      			// console.log(response);
      			if (response.success) {
      				$('form[name=flogin]').submit();
      			} else {
      				commonModal('Wrong Code','<strong>Wrong Code</strong>',80);
      				captcha.reloadImage();
      			}
      		},
      		complete: function() {
      			// captcha.reloadImage();
      		},
      		error: function(error) {
      			throw new Error(error);
      		}
      	});
      	return false;
      	*/

	   if (!$('#login_id').val()) {
			alert('check your ID or Password',0);
        return false;
      }
		/*
	   if (!$('#login_password').val()) {
			alert('check your Password',0);
        return false;
      }
	  */

	  //commonModal('Wrong Code','<strong>Wrong Code</strong>',80);
	  
	  $('form[name=flogin]').submit();

    }


    function fmail_submit() {
		/*
      if (!$('#mb_email').val()) {
        commonModal('Enter the email address', '<strong>Enter the email address</strong>', 80);
        return false;
      }
	  */
		

    }
	/*
    function open_privacy() {
      window.open("/privacy_policy.html", "popup01", "width=1200, height=1080");
    }

    function open_pinnacle() {
      window.open("/pinnacle_policy.html", "popup02", "width=1200, height=600");
    }

    function open_terms_and_conditions() {
      window.open("/terms_and_conditions.html", "popup02", "width=1200, height=1080");
    }
	*/
  </script>

</head>

<body>

<? include_once('../ETBC/test_server.php');?>

<?

//echo get_cookie('ck_mb_id', $mb['mb_id'], 86400 * 31);



/*
echo "<br>ck_mb_id : ".get_cookie('ck_id');
echo "<br>ck_mb_key : ".get_cookie('ck_pass');
*/
?>
<style>
	.adm_title{padding:5px 20px; background:#f9a62e; border-radius:15px;font-size:18px;font-weight:400;letter-spacing:0;}
</style>
<div class="container">
  <h2 class="back"><a href="" onclick="history.back();return false;" > <i class="fas fa-arrow-left"></i></a></h2>
  <div class="sign-up-container">
	

    <form name="flogin" action="<?php echo $login_action_url ?>" method="post">
	  <input type="hidden" id="url" name="url" value="<?=$url?>">
      <h2 class="blue">   Log In   <?if(strpos($url,'adm')){echo "<br><span class='adm_title'>For Administrator</span>";}?></h2>
	 
      <div class="input_icon_box">
        <input type="text" name="mb_id" id="login_id" placeholder="ID" value="<?=get_cookie('ck_id')?>">
        <i class="fas fa-user"></i>
      </div>
      <div class="input_icon_box">
        <input type="password" name="mb_password" id="login_pw" placeholder="Password" value="<?=get_cookie('ck_pass')?>">
        <i class="fas fa-lock"></i>
      </div>

      <!--
			<div class="otp_container" style="display:none;text-align: left;">
				<label for="otp" class="sound_only">Auth Code</label>
				<input type="text" name="otp"  id="otp" placeholder="auth code"  class="frm_input " maxlength="6" style="width:100px;display: inline;" >
				<span>Please enter Google Authentication code</span>
			</div>
			

			<div style='width:300px;margin:0 auto;'>
			<div id='botdetect-captcha' data-stylename='jqueryBasicCaptcha' ></div><input type='text' name='captchaCode' id='captchaCode' data-correct-captcha style='width: 276px;'>
			</div>
		-->
<!--xx 패스워드 불일치 문구 display:none -->
    <p class="wrong_pw" id="wrongPw">Incorrect password</p>
<!--xx 패스워드 불일치 문구 -->

<!--xx 비밀번호찾기링크 -->
<!--
      <p class="forgot_link_wrap">
        <span class="forgot_link" id="resetPasswordBtn">Forgot password?</span>
      </p>
	  -->
<!--xx 비밀번호찾기링크 -->

<!--xx 로그인버튼 -->
      <input type="button" value="Log In" class="submit-button" onclick="flogin_submit();">
<!--xx 로그인버튼 -->
    </form>
  </div>

  <div class="bottom-links">
    <p class="signup_link">
      <span>First time here?</span> <a href="<?php echo G5_BBS_URL; ?>/register_form.php">Sign up</a>
      <!--xx<span data-i18n="login.enjoy" >to enjoy the benefits available only for members.</span>-->
    </p>
  </div>

  <!--xx 푸터
	<footer>
		<div class="footer-left">
			<h5>FIJI</h5>
			<p>
				<i class="fas fa-map-marker-alt blue"></i> Boisbriand, Québec, Canada
			</p>
			<p>
				<i class="fas fa-map-marker-alt blue"></i> Albany, New York, USA
			</p>
			<p>
				<i class="fas fa-map-marker-alt blue"></i> Coshocton, Ohio, USA
			</p>
			<p>
				<i class="fas fa-map-marker-alt blue"></i> Columbus, Ohio, USA
			</p>
			<p>
				<i class="fas fa-map-marker-alt blue"></i> Kazakhstan
			</p>
			<p>
				<i class="fas fa-map-marker-alt blue"></i> Kyrgyzstan
			</p>
		</div>
		<div class="footer-center">
			<h5>PRIVACY & TOS</h5>
			<p>
				<a href="javascript:open_privacy() ">Privacy Policy</a>
			</p>
			<p>
				<a href="javascript:open_terms_and_conditions()">Terms of Service</a>
			</p>
			<p>
				<a href="javascript:open_pinnacle()">FIJI Policy</a>
			</p>
			<br>
			<br>
			<br>
		</div>
		<div class="footer-right">
			<h5><i class="far fa-envelope blue"></i> Contact Us</h5>
			<p></p>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
	</footer>
-->


	<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="saveSettingsCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" ></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-dismiss="modal" id="closeModal" >YES</button>
					<button type="button" class="btn btn-secondary " data-dismiss="modal" id="closeModal" >NO</button>
				</div>
			</div>
		</div>
	</div>


  <script type="text/javascript" src="./js/script.js"></script>
</div>
</body>

</html>