

<style>
body{overflow-y: hidden;}
.container {
	margin:0 auto !important;
	padding:0;
	width:100%;
	display:block;
	height:100vh;
	background:#000 url('<?=G5_THEME_URL?>/img/launcher.png') no-repeat center;
	background-size:cover;
}

/* .adm_title{background:#f9a62e;color:white;padding:5px 30px;font-size:1.2em; border-radius:25px;margin-bottom:20px;display:inline-block} */

#btnDiv {
  display: none;
  text-align: center;
  position:absolute;
  bottom:15vh;
  width:100%;
  z-index:1000;
}
.btn_ly{width:85%;
  text-align:center;
  margin:0 auto;}

#myProgress {
  width: 100%;
}

#myBar {
  width: 1%;
  height: 2px;
}

.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

.intro_title {position:fixed;bottom:10px;text-align:center;width:100%;}
.intro_title .company {color: rgba(255, 255, 255, 0.75);}
.intro_title .copyright {line-height:26px;}

@-webkit-keyframes animatebottom {
  from { bottom:-10%; opacity:0 }
  to { bottom:15vh; opacity:1 }
}

@keyframes animatebottom {
  from{ bottom:-10%; opacity:0 }
  to{ bottom:15vh; opacity:1 }
}

@media screen and (max-width: 766px) {
	.container {max-width: 100%;}
}

@media (min-width: 767px) {
	body{background:#0b0c13}
	.container{width:550px;margin:0 auto;}
	#btnDiv{width:550px;}
	.intro_title{width:550px;}
}
</style>



<script >
	var myVar;
	var maintenance = "<?=$maintenance?>";

	$(document).ready(function(){
	  move();
	});
	

	function temp_block(){
		commonModal("Notice",' 방문을 환영합니다.<br />사전 가입이 마감되었습니다.<br />가입하신 회원은 로그인 해주세요.<br /><br />Welcome to One-EtherNet.<br />Pre-subscription is closed.<br />If you are a registered member,<br />please log in.',220);
	}

	function showPage() {
	  document.getElementById("myBar").style.display = "none";
	  document.getElementById("btnDiv").style.display = "block";
	}

	function move() {
	  var elem = document.getElementById("myBar");
	  var width = 50;
	  var id = setInterval(frame, 1);
	  function frame() {
		if (width >= 100) {
		  clearInterval(id);
		  //showPage();

		  if(maintenance == 'N'){
			showPage();
		  }
		} else {
		  width = width + 10;
		  elem.style.width = width + '%';
		}
	  }
	}

	function auto_login(){

		if(typeof(web3) == 'undefined'){
    	window.location.href = "/bbs/login_pw.php";
  	}

		window.ethereum.enable().then((err) => {

    web3.eth.getAccounts((err, accounts) => {
    	if(accounts){
				$.ajax({
					url: "/bbs/login_check.php",
					async: false,
					type: "POST",
					dataType: "json",
					data:{
						trust : "trust",
						ether : accounts
					},
					success: function(res){
						if(res.result == "OK"){
							window.location.href = "/page.php?id=structure";

						}

						if(res.result == "FAIL"){
							alert("EHTEREUM ADDRESS is not registered. Please Sign In or Sign Up.");
							window.location.href = "/bbs/login_pw.php";
						}

						if(res.result == "ERROR"){
							alert("ERROR");
						}


					}
				});
			}
    })

  });
	}


</script>

<html>

<script src="<?php echo G5_JS_URL ?>/common.js?ver=<?php echo G5_JS_VER; ?>"></script>
<body style="margin:0;">

<div class="container">
	<div id="myBar"></div>

	<div id="btnDiv" class="animate-bottom">
		<div class='btn_ly'>
			<?include_once(G5_THEME_PATH.'/_include/lang.php')?>
	  		<a href="/bbs/login_pw.php" class="btn btn_wd btn_secondary login_btn">LOG IN</a>
				<!-- <a href="javascript:auto_login()" class="btn btn_wd btn_primary login_btn">LOG IN</a> -->
	  			<a href="/bbs/register_form.php" class="btn btn_wd btn_primary signup_btn">SIGN UP</a>
				<!-- <a href="javascript:temp_block()" class="btn btn_wd btn_secondary signup_btn">SIGN UP</a> -->
		</div>
	</div>

	<div class='intro_title'>
		<p class='company'> HWAJO GLOBAL ASSET <br>이메일 : <?=$config['cf_admin_email']?></p>
		<p class='copyright'>Copyright ⓒ 2023. HWAJO Co. ALL right reserved.</p>
	</div>
</div>
</html>
