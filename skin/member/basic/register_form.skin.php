
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<title>Sign up</title>
	<link rel="shortcut icon" href="/favicon.png"> 

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" />
	<link rel="stylesheet" href="<?=$member_skin_url;?>/register.css" >
	
	<!-- <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script> -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<!--<script src='/js/jquery-captcha.min.js'></script>-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/1.9.0/i18next.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.js" type="text/javascript"></script>

	<script type="text/javascript">
		
		//var captcha;

		var key;
		var smsKey;
		var idchecked = false;

		$(function(){
			
			
			/*추천인 후원인 찾기*/
			function getUser(etarget,type){

			var target = etarget;
			if(type  == 1){
				var target_type = "#referral";
			}else{
				var target_type = "#sponsor";
			}

			$.ajax({
				type:'GET',
				url:'../new/purchase_hash_full.user.php',
				data: {
					mb_id : $(target).val()
				} ,
				success: function(data){
					var list = JSON.parse(data);
					if(list.length > 0){

						$(target_type).modal('show');

						var vHtml = $('<div>');
						$.each(list, function( index, obj ) {
							
							vHtml.append($('<div>').addClass('user').html(obj.mb_id));
						});

						$(target_type + ' .modal-body').html(vHtml.html());
					}else {
						//alert('MEMBER NOT FOUND');
						commonModal('Notice','MEMBER NOT FOUND',80);
					}
				}
			});

			$(document).on('click','.modal-body .user',function(e) {
				//console.log($( target + ' .modal-body .user'));
				$( target + ' .modal-body .user').removeClass('selected');
				$(this).addClass('selected');
			});

			$('.btnSave').on('click',function(e) {
				$(target).val( $( target_type + ' .modal-body .user.selected').html());
				$(target_type).modal('hide');
			});
		}

			
			/*추천인찾기버튼*/
			$('#btnSearch1').click(function () {
				getUser('#reg_mb_recommend',1);
			});

		
			$('#btnSearch2').click(function () {
				getUser('#reg_mb_brecommend',2);
			});

			
				/*찾기 모달 */
				
				/*
				$("input[id='reg_mb_recommend']").keydown(function (e) {
					
					if(e.keyCode == 13){//키가 13이면 실행 (엔터는 13)
						e.preventDefault();
						getUser('#reg_mb_recommend');
					}
				});

				$("input[id='reg_mb_brecommend']").keydown(function (e) {
					
					if(e.keyCode == 13){//키가 13이면 실행 (엔터는 13)
						e.preventDefault();
						getUser('#reg_mb_brecommend');
					}
				});
				*/
				
				$('#framewrp').click(function () {
					$(this).hide();
				});


			/*언어팩 */
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
			/*
			if(localStorage.getItem('myLang') || localStorage.getItem('myLang') != 'eng'){
				i18n.setLng(linkMap2[localStorage.getItem('myLang')], function(){ 
					$('body').i18n(); 
				});
			}
			*/


			/*메일 인증코드 발송*/
			$('#sendMail').on('click', function(e){
				if(!$('#reg_mb_email').val()){
					commonModal('Mail authentication','<p>Please enter your mail</p>',80);
					return;
				}

				$.ajax({
					url: '/bbs/register.mail.verify.php',
					type: 'GET',
					async: false,
					data: {
						"mb_email": $('#reg_mb_email').val()
					},
					dataType: 'json',
					success: function(result) {
						console.log(result);
						key = result.key;
						//console.log(key);
						commonModal('Mail authentication','<p>Send a authentication code to your mail.</p>',80);
					},
					error: function(e){
						//console.log(e);
					}
				});

				$('#vCode').addClass('active');
				//$('#verify').addClass('active');
			});


			$('#verify').on('click', function(e){
				console.log( $('#vCode').val().trim() );
				if(key == sha256( $('#vCode').val().trim()) ){
					// 메일 인증 성공
					$('.verifyContainer').hide();
					$('#reg_mb_email').css('background-color','#ccc').prop('readonly', true);;
					
				}else{
					commonModal('Do not match','<p>Email verification code is incorrect. Please enter the correct code</p>',80);
				}
			});

		});



		function commonModal(title, htmlBody, bodyHeight){
			$('#commonModal').modal('show');
			$('#commonModal .modal-header .modal-title').html(title);
			$('#commonModal .modal-body').html(htmlBody);
			if(bodyHeight) $('#commonModal .modal-body').css('height',bodyHeight+'px');
		}

		function confirmModal(title, htmlBody, yes){
			$('#confirmModal').modal('show');
			$('#confirmModal .modal-header .modal-title').html(title);
			$('#confirmModal .modal-body').html(htmlBody);
			$('#confirmModal .modal-body').css('height','100px');
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
		

		// submit 최종 폼체크

		function fregisterform_submit(){
			
			console.log(  idchecked );

			var f = $('#fregisterform')[0];

			if(!$('#fregisterform')[0].checkValidity()){
				commonModal('Please Check input Field.','Fill in all fields.',80);
				return false;
			}

			if(!idchecked){
				commonModal('Please check your ID.','Please Check your ID confirm button first.',80);
				$('#id_check').focus();
				//return false;
			}
			
			var Vcode = $('#vCode').val().replace(/ /gi, "");

			if(key != sha256( Vcode )){
				commonModal('Do not match','<p>Email verification code is incorrect. Please enter the correct code</p>',80);
				return false;
			}else{
				f.mb_password.value = f.mb_email.value; 
			}

			if (typeof(f.mb_recommend) != "undefined" && f.mb_recommend.value) {
				if (f.mb_id.value == f.mb_recommend.value) {
					commonModal('check recommend','<strong>Do not recommend self.</strong>',80);
					f.mb_recommend.focus();
					return false;
				}
			}

			return false;
		}
	</script>
</head>

<style>
	#vCode.active{border-bottom:2px solid #f9a62e;}
	.btn,button, .search-button{outline:0 !important;}

	#reg_mb_id{}
	#id_check{position:relative;float:right;top:-50px;z-index:100;
	background:cornflowerblue;color:white;font-size:0.7em;border:0;border-radius:15px;line-height:26px; padding:0 20px;}

	#id_check:focus{
		outline: 1px dotted !important;
		outline: 5px auto -webkit-focus-ring-color !important;
	}
</style>
<body>
	<? include_once('../ETBC/test_server.php');?>

	<? include_once('modal.html'); ?>

	<div id="framewrp">
		<iframe name='framer' id="framer" frameborder="0"></iframe> 
	</div><!-- // framewrp -->

	
	<div class="container">
	<h2 class="back"><a href="" onclick="history.back();return false;" > <i class="fas fa-arrow-left"></i></a></h2>
	<div class="sign-up-container">
		
		<form id="fregisterform" name="fregisterform" action="<?php echo $register_action_url ?>" method="post" enctype="multipart/form-data" autocomplete="off">
			<input type='hidden' name='mb_password' value= ''>
			<h2 class="blue">Sign up</h2>
			
			<input type="text" name="mb_id" value="<?php echo $member['mb_id'] ?>" id="reg_mb_id" oninvalid="this.setCustomValidity('Enter ID Here')" oninput="this.setCustomValidity('')" <?php echo $required ?> <?php echo $readonly ?> placeholder="ID">	

			<button type="button" id="id_check">ID Confirm</button>

			<input type="text" name="mb_email" placeholder="E-mail"  value="<?php echo isset($member['mb_email'])?$member['mb_email']:''; ?>" id="reg_mb_email" required class="input-search" size="70" maxlength="100" oninvalid="this.setCustomValidity('Enter E-mail Here')" oninput="this.setCustomValidity('')">
			<span><button class="search-button send_btn" type="button" id="sendMail" >Send confirm code for E-mail </button></span>


			<div class="verifyContainer">
				<input type="text" name="vCode" placeholder=" Input Password Confirm" id="vCode" required class="input-search" size="70" maxlength="10"  >
				<p class="confirm_txt">Check your e-mail confirm code</p>
			
			</div>
			
			
			
			<div class="roundbox">

			<div class="referrer_input">
			<input class="input-search" value="<?php echo $mb_recommend ?>" type="text" name="mb_recommend" id="reg_mb_recommend"  placeholder="Referrer's Username" <?php echo $required ?> data-i18n="[placeholder]register.ref" >
			<span ><button class="search-button" type="button" data-i18n="register.search" id="btnSearch1">Search</button></span>
			</div>

			<!--xx 후원인 검색
			<div class="sponsor_input">
			<input class="input-search" value="<?php echo $mb_brecommend ?>" type="text" name="mb_brecommend" id="reg_mb_brecommend"  placeholder="Sponsor's Username" <?php echo $required ?> data-i18n="[placeholder]register.Sponsor" >
			<span><button class="search-button" type="button" data-i18n="register.search" id="btnSearch2" >Search</button></span>
			</div>
			-->
			</div>

	
			<p class="terms" >	By creating an account, you accept <br>EOS TEAM BLOCK CHAIN Terms of Service. </p>


		<button class="submit-button" onclick="fregisterform_submit();"  >CREATE ACCOUNT</button>
		<div style="clear:both;"></div>
		</form>

		<p class="aleady">
			<span>Aleady sign up?</span> <a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="loginlink">Log in</a>
		</p>
	</div>
</div>

<script>

     $('#id_check').click(function(e) {
		 
         var reg_mb_id= $('#reg_mb_id').val();
		
		if(reg_mb_id.length == 0 || reg_mb_id == undefined){
			commonModal('Please Check input ID.','Fill in all fields.',80);
			return false;
		}else if(reg_mb_id.length < 6){
			commonModal('ID is too short.','Please use at least 6 digits combination',80);
			return false;
		}

         $.ajax({
             type: 'post',
             url: 'ajax.idcheck.php',
             data: { reg_mb_id: reg_mb_id},
             success: function(data) {
                   //alert(data);

				   if(Number(data) > 0){
						commonModal('This ID is already in use.','Please use a different ID..',80);
						return false;
				   }else{
						confirmModal('This ID is currently available.','Do you want to use this ID?',80);
						
						
				   }
             }
         });

		 $('#select_yes').click(function() {
			
			idchecked = true;
			console.log("Click : " +idchecked);
			//$('#reg_mb_id').attr('readonly',true);
	  });
     });
</script>



	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="saveSettingsCenterTitle" aria-hidden="true">
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
					<button type="button" class="btn btn-secondary " data-dismiss="modal" id="select_yes" >YES</button>
					<button type="button" class="btn btn-secondary " data-dismiss="modal" id="closeModal" >NO</button>
				</div>
			</div>
		</div>
	</div>


</body>
</html>