<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가



// 라이브러리
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
?>

<!DOCTYPE html>
<html>
<head>
<style>
* {
	box-sizing: border-box;
	font-family: 'Raleway', sans-serif;
}

html, body {
	height: 100%;
}

a, a:hover {
	text-decoration: none;
	color: inherit;
}

.underline {
	border-bottom: 3px solid rgb(0, 121, 211);
}

.section p {
	font-size: 18px;
}

.about-card {
	margin-top: 50px;
	margin-left: 30px;
	margin-right: 30px;
	display: inline-block;
	width: 365px;
}

.start-card {
	margin-top: 50px;
	margin-left: 15px;
	margin-right: 15px;
	display: inline-block;
	width: 365px;
}

.benefit-card {
	background-color: white;
	border-radius: 4px;
	display: inline-block;
	height: 290px;
	margin-top: 50px;
	margin-left: 35px;
	margin-right: 35px;
	margin-bottom: 35px;
	padding: 20px;
	width: 360px;
}

.benefit-card h5 {
	font-weight: bold;
}

.benefit-card p {
	font-size: 16px;
}

.benefits-section img {
	position: relative;
		bottom: 35px;
	width: 80px;
}

.right-arrow {
  position: relative;
  	bottom: 90px;
  width: 60px;
}

.about-card img, .start-card img {
	margin-bottom: 10px;
	width: 60px;
}

.benefits-section img {
	-webkit-filter: drop-shadow(5px 5px 5px #222 );
  filter: drop-shadow(5px 5px 5px #222);
}

#package-one {
	width: 25%;
}

#package-two {
	width: 31%;
}

#package-three {
	width: 32%;
}

#package-four {
	width: 40%;
}

#package-gpu {
	width: 30%;
}

.package-card img {
	margin-top: 5px;
	margin-bottom: 10px;
}

.package-section hr {
	margin-bottom: 50px;
}

.about-section iframe {
	margin-top: 20px;
	margin-bottom: 15px;
}

.section .about-card p, .start-card p {
	font-size: 16px;
}

.blue {
	color: rgb(0, 121, 211);
}

.gray {
	color: rgb(124, 124, 124);
}

.purple {
	color: #53407A;
}

.red {
	color: #ff6c6c;
}

.start-section, .benefits-section, footer {
	background-color: #F5F5F5;
	text-align: center;
}

footer {
	padding: 40px;
}

.about-section, .package-section, .success-section {
	text-align: center;
}

.carousel-caption {
	bottom: 33%;
}

.carousel-caption h2 {
	font-size: 45px;
	font-weight: bold;
	text-shadow: 3px 3px 6px black;
}

.carousel-caption p {
	font-size: 22px;
	text-shadow: 3px 3px 6px black;
}

.carousel-container {
	position: relative;
	z-index: 888;
}

.carousel-hr {
	border: 1px solid white;
	width: 175px;
}

.success-card h5 {
	font-weight: bold;
}

.success-card {
	margin: 0 auto;
	margin-top: 30px;
	margin-bottom: 30px;
}

.success-card img {
	margin-bottom: 10px;
}

.nav-handle {
	color: white;
	cursor: pointer;
	display: none;
	font-size: 20px;
	padding: 15px;
	position: relative;
	text-align: left;
	width: 100%;
}

.nav-handle i {
	position: absolute;
		top: 25px;
		right: 15px;
}

nav {
	width: 100%;
	position: fixed;
		top: 0;
		left: 0;
	transition: all 0.2s linear;
	z-index: 999;
}

nav ul {
	margin: 0;
	overflow: hidden;
	padding: 0;
	text-align: center;
	transition: max-height 0.4s;
		-webkit-transition: max-height: 0.4s;
		-ms-transition: max-height: 0.4s;
		-moz-transition: max-height: 0.4s;
		-o-transition: max-height: 0.4s;
}

nav ul li {
	color: white;
	display: inline-block;
	font-size: 20px;
	padding: 20px;
}

.nav-scroll {
	background-color: rgb(0, 121, 211);
}

.footer-left, .footer-center, .footer-right {
	display: inline-block;
	margin-left: 80px;
	margin-right: 80px;
	text-align: left;
}

.footer-left h5, .footer-center h5, .footer-right h5 {
	font-weight: bolder;
}

.footer-center p:hover, .footer-right p:hover {
	color: rgb(0, 121, 211);
}

.success-section button {
	border: 1px solid rgb(0, 121, 211);
	background-color: white;
	color: rgb(0, 121, 211);
	cursor: pointer;
	font-size: 22px;
	padding: 15px;
	transition: all 0.2s linear;
}

.success-section button:hover {
	background-color: rgb(0, 121, 211);
	color: white;
}

.package-card {
	display: inline-block;
	margin-left: 25px;
	margin-right: 25px;
	width: 246px;
}

.package-card h5, p {
	margin: 0;
}

.package-card button, .start-card p button {
	background-color: white;
	border: 1px solid rgb(0, 121, 211);
	color: rgb(0, 121, 211);
	cursor: pointer;
	margin-top: 15px;
	padding: 10px;
	transition: all 0.2s linear;
	width: 160px;
}

.start-card p button {
	background-color: transparent;
}

.begin-mining {
	position: relative;
		top: 13px;
}

.package-card button:hover, .start-card p button:hover {
	background-color: rgb(0, 121, 211); 
	color: white;
}

.register-link, .myoffice-link, .login-link, .pinnacle-brand {
	position: absolute;
}

.register-link {
	right: 200px;
}
.myoffice-link {
	right: 250px;
}
.login-link {
	right: 100px;
}

.pinnacle-brand {
	left: 100px;
}

.section {
	padding: 50px 70px;
}

.section hr {
	border: 1px solid rgb(0, 121, 211);
	width: 125px;
}

@media only screen and (max-width: 1455px) {
	nav ul {
		background-color: rgb(0, 121, 211);
		border-bottom: 1px solid white;
		max-height: 0;
	}

	.showing {
		max-height: 35em;
	}

	nav ul li {
		color: white;
		width: 100%;
	}

	nav ul li:hover, .nav-handle:hover {
		background-color: rgb(0, 146, 255);
	}

	.nav-handle {
		background-color: rgb(0, 121, 211);
		color: white;
		cursor: pointer;
		display: block;
		height: 70px;
	}

	.nav-handle span {
		position: relative;
			top: 5px;
	}

	.register-link, .myoffice-link, .login-link, .pinnacle-brand {
		position: static;
	}

	.carousel-container {
		margin-top: 70px;
	}
}

@media only screen and (max-width: 1461px) {
	.right-arrow {
		display: none;
	}
}

@media only screen and (max-width: 1350px) {
	.d-none {
		display: block !important;
	}
}

@media only screen and (max-width: 1336px) {
	.package-card {
		margin-bottom: 35px;
	}

	.success-card {
		width: 90%;
	}
}

@media only screen and (max-width: 1172px) {
	.footer-right {
		margin-top: 45px;
	}

	.footer-right br {
		display: none;
	}
}

@media only screen and (max-width: 1075px) {
	.carousel-caption hr, .carousel-caption p {
		display: none;
	}
}

@media only screen and (max-width: 796px) {
	.footer-left, .footer-center, .footer-right {
		display: block;
		margin: 0 auto;
	}

	.footer-center, .footer-right {
		margin-top: 45px;
	}

	.footer-center br {
		display: none;
	}
}

@media only screen and (max-width: 700px) {
	.carousel-caption h2 {
		font-size: 28px;
	}

	iframe {
		width: 90%;
	}
}

@media only screen and (max-width: 550px) {
	.section {
		padding: 25px 35px;
	}
}

@media only screen and (max-width: 500px) {
	.carousel-caption h2 {
		font-size: 22px;
	}

	.about-card {
		display: block;
		width: 90%;
	}

	.benefit-card {
		display: block;
		margin: 0 auto;
		margin-top: 45px;
		width: 90%;
	}
}

  /* 팝업레이어 */
#hd_pop {z-index:1000;position:relative;margin:0 auto;width:970px;height:0}
#hd_pop h2 {position:absolute;font-size:0;line-height:0;overflow:hidden}
.hd_pops {position:absolute;border:1px solid #e9e9e9;background:#fff}
.hd_pops_con {overflow-y: scroll;}
.hd_pops_footer {padding:10px 0;background:#000;color:#fff;text-align:right}
.hd_pops_footer button {margin-right:5px;padding:5px 10px;border:0;background:#393939;color:#fff}
@media only screen and (max-width: 460px) {
	.start-card {
		display: block;
		width: 90%;
	}
}

</style>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo G5_JS_URL ?>/jquery.carouFredSel-5.5.0-packed.js"></script>

<!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->



	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="./css/home.css">
	<link rel="stylesheet" type="text/css" href="./css/default.css">
	<title>Pinnacle Mining | Home</title>
</head>
<body>
    <?php // index에서만 실행
	   // include_once(G5_THEME_PATH.'/head.sub.php');
        include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어

      ?>

	<nav>
		<ul id="nav-list">
			<a href="#top" id="top-link" class="scroll"><li class="pinnacle-brand"><img src="./img/logo.png" width="25" alt="pinnacle logo"> PINNACLE MINING</li></a>
			<a href="#about" id="about-link" class="scroll"><li>ABOUT</li></a>
			<a href="#start" id="start-link" class="scroll"><li>HOW TO START</li></a>
			<a href="#packages" id="package-link" class="scroll"><li>PACKAGES</li></a>
			<a href="#benefits" id="benefit-link" class="scroll"><li>BENEFITS</li></a>
			<a href="#success" id="successLink" class="scroll"><li>SUCCESS PLAN</li></a>
				<? if ($is_admin) { ?>
					<a href="/adm" target="_blank"><li class="menu-kind">ADMIN</li></a>
				<? } ?>	
			<? if ($is_member) { ?>
			<a href="<?php  ?>/new/dashboard.php" ><li class="myoffice-link">MY OFFICE</li></a>
			<a href="<?php echo G5_BBS_URL; ?>/logout.php" ><li class="login-link">LOG OUT</li></a>
			<? } else { ?>
			<a href="<?php echo G5_BBS_URL; ?>/register_form.php" ><li class="register-link">REGISTER</li></a>		
			<a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" ><li class="login-link">LOG IN</li></a>
			<?}?>

		</ul>

		<div id="handle" class="nav-handle"><span>Menu</span> <i class="fas fa-bars"></i></div>
	</nav>

	<div class="carousel-container" id="top">
	  <div id="carouselTabs" class="carousel slide" data-ride="carousel">
	    <ol class="carousel-indicators">
	      <li data-target="#carouselTabs" data-slide-to="0" class="active"></li>
	      <li data-target="#carouselTabs" data-slide-to="1"></li>
	      <li data-target="#carouselTabs" data-slide-to="2"></li>
	    </ol>
	    <div class="carousel-inner" role="listbox">
	      <div class="carousel-item active">
	        <img class="d-block w-100"  src="<?php echo G5_URL; ?>/new/images/slide_img01.png" alt="First slide">
	        <div class="carousel-caption d-none d-md-block">
	          <h2>DONE FOR YOU BITCOIN MINING</h2>
	          <hr class="carousel-hr">
	          <p>No need to waste your time or worry about expensive equipment, electricity management, or loud fans. Our mining experts will take care of everything from start to finish.</p>
	        </div>
	      </div>
	      <div class="carousel-item">
	        <img class="d-block w-100"   src="<?php echo G5_URL; ?>/new/images/slide_img02.png" alt="Second slide">
	        <div class="carousel-caption d-none d-md-block">
	          <h2>ELEVATED MEMBER REWARDS</h2>
	          <hr class="carousel-hr">
	          <p>Multiply your earnings with the most ambitious and lucrative referral system in the industry. We believe in the principle of collective benefit and that by growing together, everyone earns more.</p>
	        </div>
	      </div>
	      <div class="carousel-item">
	        <img class="d-block w-100"  src="<?php echo G5_URL; ?>/new/images/slide_img03.png" alt="Third slide">
	        <div class="carousel-caption d-none d-md-block">
	          <h2>EARN BITCOIN EVERY DAY</h2>
	          <hr class="carousel-hr">
	          <p>Pinnacle empowers you with an opportunity to profit from an ever-changing financial landscape with innovative blockchain technology and rewarded cryptocurrency mining.</p>
	        </div>
	      </div>
	    </div>
	    <a class="carousel-control-prev" href="#carouselTabs" role="button" data-slide="prev">
	      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	      <span class="sr-only">Previous</span>
	    </a>
	    <a class="carousel-control-next" href="#carouselTabs" role="button" data-slide="next">
	      <span class="carousel-control-next-icon" aria-hidden="true"></span>
	      <span class="sr-only">Next</span>
	    </a>
	  </div>
	</div>

	<div id="about" class="about-section section">
		<span class="gray">OUR MISSION</span>
		<h2>ABOUT Pinnacle</h2>
		<hr>
		<p>
			Pinnacle is on a mission to make Bitcoin and other cryptocurrencies accessible to every people in the world. The company was formed by a team of forward-thinking cryptocurrency experts, investors, and engineers to revolutionize the cryptocurrency mining industry and is building one of the largest crowdfunded Bitcoin mining pools in North America. Our mining facilities run only the latest in mining hardware and maintain profitability through reliable and cost-efficient energy sources.		
		</p>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/hFnJmCt8Bq4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		<p>
			We are passionate about securing our members with digital assets and have built a simple and intuitive platform for everyone to access the cryptocurrency market. With mining centers located throughout the world including Canada, Ohio, New York, North Carolina, Kyrgyzstan, Republic of Georgia, and Kazakhstan, Pinnacle is well on its way to surpassing its goal of reaching <span class="underline">10% of global hash power</span> and <span class="underline">one million members</span> by the end of 2019.
		</p>
		<div class="about-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/thumbs_up.png" alt="mine with ease">
			<h4 class="blue">MINE WITH EASE</h4>
			<p>No need to mess with hot and noisy miners at home. Our experts take care of the entire mining process for you.</p>
		</div>
		<div class="about-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/sustainable.png" alt="sustainable income">
			<h4 class="blue">SUSTAINABLE INCOME</h4>
			<p>Multiply your shares with our repurchase program and continue to earn as the mining pool expands.</p>
		</div>
		<div class="about-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/member_bonus.png" alt="member bonuses">
			<h4 class="blue">MEMBER BONUSES</h4>
			<p>Earn Bitcoin for each and every person that you refer. Plus, receive an additional bonus whenever your referral earns from their mining pool.</p>
		</div>
	</div>

	<div id="start" class="start-section section">
		<span class="gray">JOIN NOW AND GET STARTED</span>
		<h2>HOW TO START MINING TODAY</h2>
		<hr>
		<p>
			Sign up and purchase shares in one of our mining pools. Your share will then be allocated to purchase equipment, and you'll begin earning daily payments from whatever is mined. To earn even more, share this opportunity with others and you'll receive a commission on everything they purchase. 
			<br>
			<strong>The more you share, the more you earn!</strong>
			<div class="start-card">
				<img src="<?php echo G5_THEME_URL; ?>/img/sign_up.png" alt="sign up">
				<h4 class="blue">SIGN UP</h4>
				<p>Create your account with a one-time $99 membership fee. You'll have lifetime access to our platform, exclusive mining pools, and all other future cryptocurrency opportunities.</p>
			</div>
			<img src="<?php echo G5_THEME_URL; ?>/img/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card">
				<img src="<?php echo G5_THEME_URL; ?>/img/bitcoin_wallet.png" alt="bitcoin wallet">
				<h4 class="blue">GET A BITCOIN WALLET</h4>
				<p>You'll need a wallet to send and receive Bitcoin with us, and there are plenty to decide from. Choose one with security features that are right for you.</p>
			</div>
			<img src="<?php echo G5_THEME_URL; ?>/img/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card begin-mining">
				<img src="<?php echo G5_THEME_URL; ?>/img/start_mining.png" alt="start mining">
				<h4 class="blue">BEGIN MINING</h4>
				<p>
					Start earning Bitcoins every day!
					<button>GET STARTED</button>
					<br>
					<br>
				</p>
			</div>
		</p>
	</div>

	<div id="packages" class="package-section section">
		<h2>MINING PACKAGES</h2>
		<hr>
		<!-- <div class="package-card">
			<h4>ENROLLMENT</h4>
			<img src="<?php echo G5_THEME_URL; ?>/img/enrollment.png" alt="enrollment">
			<h5>Pinnix Membership</h5>
			<p>Lifetime Access</p>
			<p>Exclusive Opportunities</p>
			<h5><span class="blue">$99</span> <span class="gray">(One-time)</span></h5>
			<button>SIGN UP NOW</button>
		</div> -->
		<div class="package-card">
			<h4>PACKAGE 1</h4>
			<img id="package-one" src="<?php echo G5_THEME_URL; ?>/img/package1.png" alt="package 1">
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>3,400 GH/s <span class="gray">Hash Power</span></p>
			<h5><span class="blue">$1,000</span></h5>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<h4>PACKAGE 2</h4>
			<img id="package-two" src="<?php echo G5_THEME_URL; ?>/img/package2.png" alt="package 2">
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>10,200 GH/s <span class="gray">Hash Power</span></p>
			<h5><span class="blue">$3,000</span></h5>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<h4>PACKAGE 3</h4>
			<img id="package-three" src="<?php echo G5_THEME_URL; ?>/img/package3.png" alt="package 3">
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>17,000 GH/s <span class="blue">+1%</span> <span class="gray">Hash Power</span></p>
			<h5><span class="blue">$5,000</span></h5>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<h4>PACKAGE 4</h4>
			<img id="package-four" src="<?php echo G5_THEME_URL; ?>/img/package4.png" alt="package 4">
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>40,800 GH/s <span class="blue">+2%</span> <span class="gray">Hash Power</span></p>
			<h5><span class="blue">$12,000</span></h5>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<h4>GPU</h4>
			<img id="package-gpu" src="<?php echo G5_THEME_URL; ?>/img/gpu_package.png" alt="gpu package">
			<h5>Ethereum Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>80 MH/s <span class="gray">Hash Power</span></p>
			<h5><span class="blue">$3,000</span></h5>
			<button>SELECT PACKAGE</button>
		</div>
	</div>

	<div id="benefits" class="benefits-section section">
		<span class="gray">AN ENTIRELY BETTER WAY TO EARN BITCOIN</span>
		<h2>THE BENEFITS OF Pinnacle</h2>
		<hr>
		<div class="benefit-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/no_setup.png" alt="no setup required">
			<h5 class="blue">NO SETUP REQUIRED</h5>
			<p>As soon as you become a member, you can purchase a mining pool share and start mining right away. We'll take care of all the heavy lifting.<br><br></p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/sustainable_income.png" alt="sustainable income">
			<h5 class="blue">SUSTAINABLE INCOME PLAN</h5>
			<p>Our re-purchase program will automatically reinvest in the latest equipment for you to ensure consistent earnings.<br><br></p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/transparent_cost.png" alt="transparent upfront cost">
			<h5 class="blue">TRANSPARENT UPFRONT COST</h5>
			<p>There are no subscriptions or hidden fees. Your one-time $99 membership payment gives you access to our four exclusive mining pools and all other future crypto projects.</p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/referral_program.png" alt="referral program">
			<h5 class="blue">REFERRAL PROGRAM</h5>
			<p>Earn a commission for each person that you refer. You’ll also receive Bitcoin whenever your referral earns from their pool. This will be taken straight from our share so you won’t reduce their profit margin.</p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/personal_dashboard.png" alt="personal dashboard">
			<h5 class="blue">PERSONAL DASHBOARD</h5>
			<p>Keep track of your crypto earnings using our simple real-time dashboard. You can also view your affiliate bonuses and billing history.<br><br><br></p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_THEME_URL; ?>/img/partnerships.png" alt="partnerships">
			<h5 class="blue">PARTNERSHIP</h5>
			<p>To remain competitive and minimize downtime, we have secured a partnership with a contracted manufacturer specializing in mining hardware.<br><br><br></p>
		</div>
	</div>

	<div id="success" class="success-section section">
		<h2>4-STEP SUCCESS PLAN</h2>
		<hr>
			<div class="success-card">
				<img src="<?php echo G5_THEME_URL; ?>/img/cutting-edge.png" width="85" alt="cutting edge hardware">
				<h5>1. Cutting-Edge Mining Hardware</h5>
				<p>
					Cryptocurrency mining is most profitable when done with top-of-the-line, task-specific hardware. Pinnacle facilities are equipped with thousands of Bitmain S9s and are scheduled to upgrade with (seven times faster) 100 TH/s miners in the 4th quarter of 2018. During the 1st quarter of 2019, we’ll prepare for another expansion with the world’s fastest ASIC miners with 225 TH/s. We will be unrelenting in our vision for the future and will set the industry standard as the market leader.
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_THEME_URL; ?>/img/low_electricity.png" width="85" alt="low electricity">
				<h5>2. Ultra-Low-Cost Electricity and Stable Energy Supply</h5>
				<p>
					A key factor in mining profitability is the cost of power. We only mine in locations that have a surplus of electricity due to their well-developed infrastructure, interconnectivity, and renewable energy programs. With this in mind, Pinnacle has secured several reliable power sources for an extremely low price.
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_THEME_URL; ?>/img/secure_facility.png" width="70" alt="secure facility">
				<h5>3. Secure and Reliable Facilities</h5>
				<p>
					Each Pinnacle center will be surrounded by surveillance cameras and will also be protected by armed guards at all times. Our mining team will also be on site 24/7 to maintain, maximize, and ensure mining efficiency.
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_THEME_URL; ?>/img/team.png" width="85" alt="team">
				<h5>4. Top-Notch Experts and Professionals</h5>
				<p>
					It takes a team of engineers, security and construction experts, and software developers to run even a modest mining center, not to mention centers on the scale Pinnacle is planning. The Pinnacle Team boasts all of the qualifications and competencies to build, install, and operate industrial-sized mining facilities.
				</p>
			</div>
			<button>START MINING NOW</button>
	</div>

	<footer>
		<div class="footer-left">
			<h5>Pinnacle</h5>
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
				<a href="#">Privacy Policy</a>
			</p>
			<p>
				<a href="#">Terms of Service</a>
			</p>
			<p>
				<a href="#">Pinnacle Policy</a>
			</p>
			<br>
			<br>
			<br>
		</div>
		<div class="footer-right">
			<h5><i class="far fa-envelope blue"></i> Contact Us</h5>
			<p><a href="mailto:contact@Pinnacle.com">contact@Pinnacle.com</a></p>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
	</footer>


	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script type="text/javascript" src="./script.js"></script>
	<script Language="javascript">
const links = document.querySelectorAll('#handle, #top-link, #about-link, #start-link, #package-link, #benefit-link, #successLink');

for (let i = 0; i < links.length; i++) {
  links[i].addEventListener('click', function(e) {
    const navUl = document.getElementById('nav-list')
    navUl.classList.toggle('showing')
  })
}

$(document).ready(function(){
  let scrollTop = 0;
  $(window).scroll(function(){
    scrollTop = $(window).scrollTop();
     $('.counter').html(scrollTop);    
    if (scrollTop >= 100) {
      $('nav').addClass('nav-scroll');
    } else if (scrollTop < 100) {
      $('nav').removeClass('nav-scroll');
    }     
  }); 
  
  let scrollLink = $('.scroll');
  scrollLink.click(function(e) {
  	e.preventDefault()
  	$('body, html').animate({
  		scrollTop: $(this.hash).offset().top - 70
  	}, 750)
  })
});
	</script>
	
</body>
</html>