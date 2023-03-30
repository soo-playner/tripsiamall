<?

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>LOG IN</title>
	<link rel="shortcut icon" href="/favicon.png"> 

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" href="<?=$member_skin_url;?>/login.1.css" >
	
	<!-- <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src='/js/jquery-captcha.min.js'></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/1.9.0/i18next.min.js" type="text/javascript"></script>

	<script type="text/javascript">

		function commonModal(title, htmlBody, bodyHeight){
			$('#commonModal').modal('show');
			$('#commonModal .modal-header .modal-title').html(title);
			$('#commonModal .modal-body').html(htmlBody);
			if(bodyHeight) $('#commonModal .modal-body').css('height',bodyHeight+'px');
		}
		function flogin_submit(){

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
		}
		function fmail_submit(){
			if(!$('#mb_email').val()){
				commonModal('Enter the email address','<strong>Enter the email address</strong>',80);
				return false;
			}
			$.ajax({
				method: 'POST',
				url: '/botDetect/simple-api-jquery-captcha-example/form/basic.php',
				data: JSON.stringify({
					captchaId: captcha2.getCaptchaId(),
					captchaCode: captcha2.getCaptchaCode()
				}),
				async: false,
				success: function(response) {
					// console.log(response);
					if (response.success) {
						$('form[name=fpasswordlost]').submit();
					} else {
						commonModal('Wrong Code','<strong>Wrong / Code</strong>',80);
					}
				},
				complete: function() {
					captcha2.reloadImage();
				},
				error: function(error) {
					throw new Error(error);
				}
			});
			// $('form[name=fpasswordlost]').submit();
			return false;
		}
		function open_privacy() {
			window.open("/privacy_policy.html", "popup01", "width=1200, height=1080");
		}
		function open_pinnacle() {
			window.open("/pinnacle_policy.html", "popup02", "width=1200, height=600");
		}
		function open_terms_and_conditions() {
			window.open("/terms_and_conditions.html", "popup02", "width=1200, height=1080");
		}
		var captcha;
		var captcha2;
		$(function() {
			captcha = $('#botdetect-captcha').captcha({
				captchaEndpoint: '/botDetect/lib/simple-botdetect.php'
			});
			
			$('#resetPasswordBtn').on('click', function(e){
				captcha2 = $('#botdetect-captcha2').captcha({
					captchaEndpoint: '/botDetect/lib/simple-botdetect.php'
				});
				$('#resetPassword').modal('show');
			});

			$('#captchaCode').on('keydown', function(event){
				if ( event.which == 13 ) {
					event.preventDefault();
					flogin_submit();
				}
			});

			/* $.i18n.init({ 
				resGetPath: '/locales/__lng__.json', 
				load: 'unspecific', 
				fallbackLng: false, 
				lng: 'en' 
			}, function (t){ 
				$('body').i18n(); 
			}); 

			var linkMap2 = {
				"eng" : "en",
				"kor" : "kr",
				"jpn" : "jp",
				"chn" : "ch"
			} */

			if(localStorage.getItem('myLang') || localStorage.getItem('myLang') != 'eng'){
				i18n.setLng(linkMap2[localStorage.getItem('myLang')], function(){ 
					$('body').i18n(); 
				});
			}
		});

	</script>
</head>
<body>
	<nav class="shadow">
		<ul id="nav-list">
			<a href="/" id="top-link" class="scroll"><li class="pinnacle-brand"><!-- <img src="./images/logo.png" height="19" alt="pinnacle logo"> --> FIJI MINING</li></a>
			<a href="<?php echo G5_BBS_URL; ?>/register_form.php" class="scroll"><li class="register-link" data-i18n="login.enroll" >ENROLLMENT</li></a>
		</ul>
		<div id="handle" class="nav-handle"><span>Menu</span> <i class="fas fa-bars"></i></div>
	</nav>

	<div class="sign-up-container">
		<form class="shadow" name="flogin" action="<?php echo $login_action_url ?>" method="post">
			<h2 class="blue" data-i18n="login.title">LOG IN</h2>
			<input type="text" name="mb_id" id="login_id" placeholder="Username" data-i18n="[placeholder]login.user" >
			<input type="password" name="mb_password" id="login_pw" placeholder="Password" data-i18n="[placeholder]login.pass" >
			<div class="otp_container" style="display:none;text-align: left;">
				<label for="otp" class="sound_only">Auth Code</label>
				<input type="text" name="otp"  id="otp" placeholder="auth code"  class="frm_input " maxlength="6" style="width:100px;display: inline;" >
				<span>Please enter Google Authentication code</span>
			</div>
			<div style='width:300px;margin:0 auto;'>
			<div id='botdetect-captcha' data-stylename='jqueryBasicCaptcha' ></div><input type='text' name='captchaCode' id='captchaCode' data-correct-captcha style='width: 276px;'>
			</div>
			<br>
			<input type="button" value="LOG IN" class="submit-button" onclick="flogin_submit();" data-i18n="[value]login.title" >
			
		</form>
	</div>

	<div class="bottom-links">
		<p class="gray">
			<span data-i18n="login.forgot">Forgot username or password?</span> <span class="blue" id="resetPasswordBtn" data-i18n="login.click" >Click here.</span>
		</p>
		<p class="gray">
			<span data-i18n="login.not" >Not a member yet?</span> <a href="<?php echo G5_BBS_URL; ?>/register_form.php" data-i18n="login.sign" >Sign up now</a> 
			<span data-i18n="login.enjoy" >to enjoy the benefits available only for members.</span>
		</p>
	</div>

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
			<p><a href="mailto:support@pinnaclemining.net">support@FIJImining.net</a></p>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
	</footer>

	<div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-labelledby="passwordResetModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle" data-i18n="login.reset" >Password Reset</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form name="fpasswordlost" action="<?php echo  G5_HTTPS_BBS_URL.'/password_lost2.php' ?>"  method="post" autocomplete="off">
						<p data-i18n="login.txt">
							Enter the email address on your account to receive instructions on how to reset your password.
						</p>
						<input type="text" name="mb_email" id="mb_email" placeholder="Email" required data-i18n="[placeholder]login.email" >
						<!-- <div align="center" class="g-recaptcha" data-sitekey="<?php print $site_key;?>"></div> -->
						<div style='width:300px;margin:0 auto;'>
							<div id='botdetect-captcha2' data-stylename='jqueryBasicCaptcha2' ></div><input type='text' name='captchaCode' id='captchaCode2' data-correct-captcha style='width: 276px;'>
						</div>
						<br>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="button" class="btn btn-primary" onclick="fmail_submit();">Send</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="saveSettingsCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" ></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="./js/script.js"></script>
				
</body>
</html>