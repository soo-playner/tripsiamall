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

include(G5_PATH."/geoip/geoip.inc");
$gi = geoip_open(G5_PATH."/geoip/GeoIP.dat", GEOIP_STANDARD);
$cn = geoip_country_code_by_addr($gi, $_SERVER['REMOTE_ADDR']);
geoip_close($gi);

?>

<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="google" content="notranslate">

	<title>FIJI Mining | Home</title>
	<link rel="shortcut icon" href="/favicon2.png">

	<!-- css -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/home.css?v=20190212">

	<!-- javascript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/1.9.0/i18next.min.js" type="text/javascript"></script>
	<!--[if lte IE 8]>
	<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
	<![endif]-->
	<script src="/js/common.js"></script>
	<script Language="javascript">

		$cn = '<?=$cn?>';
		$clientIp = '<?=$_SERVER['REMOTE_ADDR']?>';

		var linkMap = {
			"en" : "eng",
			"kr" : "kor",
			"jp" : "jpn",
			"ch" : "chn"
		};

		var linkMap2 = {
			"eng" : "en",
			"kor" : "kr",
			"jpn" : "jp",
			"chn" : "ch"
		};

		$(document).ready(function(){
			const links = document.querySelectorAll('#handle, #top-link, #about-link, #start-link, #package-link, #benefit-link, #successLink');
			for (let i = 0; i < links.length; i++) {
				links[i].addEventListener('click', function(e) {
					const navUl = document.getElementById('nav-list');
					navUl.classList.toggle('showing');
				});
			}
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
				e.preventDefault();
				$('body, html').animate({
					scrollTop: $(this.hash).offset().top - 70
				}, 750);
			});

			/* $.i18n.init({
				resGetPath: '/locales/__lng__.json',
				load: 'unspecific',
				fallbackLng: false,
				lng: 'en'
			}, function (t){
				$('body').i18n();
			}); */

			$("#lang-select").change(function(){
				i18n.setLng($(this).val(), function(){
					$('body').i18n();
				}); // data-i18n="nav.about" 11
				localStorage.setItem('myLang',linkMap[$(this).val()]);
			});

			// if(linkMap[$cn.toLowerCase()]){
			// 	$('#lang-select').val($cn.toLowerCase()).change();
			// }

			if(linkMap2[localStorage.getItem('myLang')]){
				$('#lang-select').val(linkMap2[localStorage.getItem('myLang')]).change();
			}

		});
	</script>

</head>
<body>
	<?php // index에서만 실행
	   // include_once(G5_THEME_PATH.'/head.sub.php');
		include G5_BBS_PATH.'/newwin.inc.php'; // 팝업레이어
	?>
	<nav>
		<ul id="nav-list">
			<!--a href="#top" id="top-link" class="scroll"><li class="FIJI-brand"><img src="./img/logo.png" width="25" alt="FIJI logo"> FIJI MINING</li></a>
			<a href="#about" id="about-link" class="scroll"><li data-i18n="nav.about">ABOUT</li></a>
			<a href="#start" id="start-link" class="scroll"><li data-i18n="nav.how">HOW TO START</li></a>-->
			<!--<a href="#packages" id="package-link" class="scroll"><li data-i18n="nav.packages">PACKAGES</li></a>
			<a href="#benefits" id="benefit-link" class="scroll"><li data-i18n="nav.benefits">BENEFITS</li></a>
			<a href="#success" id="successLink" class="scroll"><li data-i18n="nav.plan">SUCCESS PLAN</li></a>-->
				<? if ($is_admin) { ?>
					<a href="/adm" target="_blank"><li class="menu-kind" data-i18n="nav.admin">ADMIN</li></a>
				<? } ?>
			<? if ($is_member) { ?>
			<a href="/new/dashboard.php" id="linkOffice"><li class="myoffice-link" data-i18n="nav.office">MY OFFICE</li></a>
			<a href="<?php echo G5_BBS_URL; ?>/logout.php" ><li class="login-link" data-i18n="nav.logout">LOG OUT</li></a>
			<? } else { ?>
			<a href="<?php echo G5_BBS_URL; ?>/register_form.php" ><li class="register-link" data-i18n="nav.enrollment">ENROLLMENT</li></a>
			<a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" ><li class="login-link" data-i18n="nav.login">LOG IN</li></a>
			<?}?>

			<select class="custom-select" id="lang-select">
				<option value="en" selected>English</option>
				<option value="kr">한국어</option>
				<option value="jp">日本語</option>
				<option value="ch">中文</option>
			</select>
		</ul>

		<div id="handle" class="nav-handle">
			<span data-i18n="nav.menu">Menu</span> <i class="fas fa-bars"></i>
		</div>
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
	        <img class="d-block w-100" src="new/images/slide_img01.png" alt="First slide">
	        <div class="carousel-caption d-none d-md-block">
	          <h2 data-i18n="carousel.item1.caption">DONE FOR YOU BITCOIN MINING</h2>
	          <hr class="carousel-hr">
	          <p data-i18n="carousel.item1.paragraph">No need to waste your time or worry about expensive equipment, electricity management, or loud fans. Our mining experts will take care of everything from start to finish.</p>
	        </div>
	      </div>
	      <div class="carousel-item">
	        <img class="d-block w-100" src="new/images/slide_img02.png" alt="Second slide">
	        <div class="carousel-caption d-none d-md-block">
	          <h2 data-i18n="carousel.item2.caption">ELEVATED MEMBER REWARDS</h2>
	          <hr class="carousel-hr">
	          <p data-i18n="carousel.item2.paragraph">Multiply your earnings with the most ambitious and lucrative referral system in the industry. We believe in the principle of collective benefit and that by growing together, everyone earns more.</p>
	        </div>
	      </div>
	      <div class="carousel-item">
	        <img class="d-block w-100" src="new/images/slide_img03.png" alt="Third slide">
	        <div class="carousel-caption d-none d-md-block">
	          <h2 data-i18n="carousel.item3.caption">EARN BITCOIN EVERY DAY</h2>
	          <hr class="carousel-hr">
	          <p data-i18n="carousel.item3.paragraph">FIJI Mining empowers you with an opportunity to profit from an ever-changing financial landscape with innovative blockchain technology and rewarded cryptocurrency mining.</p>
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
		<h2 data-i18n="about.about">ABOUT FIJI</h2>
		<hr>
		<p data-i18n="about.paragraph1">
			FIJI Mining is on a mission to make Bitcoin and other cryptocurrencies accessible to every people in the world. The company was formed by a team of forward-thinking cryptocurrency experts, investors, and engineers to revolutionize the cryptocurrency mining industry and is building one of the largest crowdfunded Bitcoin mining pools in North America. Our mining facilities run only the latest in mining hardware and maintain profitability through reliable and cost-efficient energy sources.
		</p>
		<iframe width="560" height="315" src="https://www.youtube.com/embed/hFnJmCt8Bq4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		<p data-i18n="[html]about.paragraph2">
			We are passionate about securing our members with digital assets and have built a simple and intuitive platform for everyone to access the cryptocurrency market. With mining centers located throughout the world including Canada, Ohio, New York, North Carolina, Kyrgyzstan, Republic of Georgia, and Kazakhstan, FIJI Mining is well on its way to surpassing its goal of reaching <span class="underline">10% of global hash power</span> and <span class="underline">one million members</span> by the end of 2019.
		</p>
		<div class="about-card">
			<img src="new/images/thumbs_up.png" alt="mine with ease">
			<h4 class="blue" data-i18n="about.card1.head">MINE WITH EASE</h4>
			<p data-i18n="about.card1.paragraph">No need to mess with hot and noisy miners at home. Our experts take care of the entire mining process for you.</p>
		</div>
		<div class="about-card">
			<img src="new/images/sustainable.png" alt="sustainable income">
			<h4 class="blue" data-i18n="about.card2.head">SUSTAINABLE INCOME</h4>
			<p data-i18n="about.card2.paragraph">Multiply your accounts with our avatar program and continue to earn as our facilities expand.</p>
		</div>
		<div class="about-card">
			<img src="new/images/member_bonus.png" alt="member bonuses">
			<h4 class="blue" data-i18n="about.card3.head">MEMBER BONUSES</h4>
			<p data-i18n="about.card3.paragraph">Earn Bitcoin for each and every person that you refer. Plus, receive an additional bonus whenever your referral earns from their mining package.</p>
		</div>
	</div>

	<div id="start" class="start-section section">
		<span class="gray" data-i18n="start.head1">JOIN NOW AND GET STARTED</span>
		<h2 data-i18n="start.head2">HOW TO START MINING TODAY</h2>
		<hr>
		<p>
			<span data-i18n="start.main">Sign up and purchase one of our mining packages. You'll begin earning daily payments next day of the package purchase. To earn even more, share this opportunity with others and you'll receive a commission on everything they purchase. </span>
			<br>
			<strong data-i18n="start.strong">The more you share, the more you earn!</strong>
			<div class="start-card">
				<img src="new/images/sign_up.png" alt="sign up">
				<h4 class="blue" data-i18n="start.signup.tit">SIGN UP</h4>
				<p data-i18n="start.signup.txt">Create your account with a one-time $99 membership fee. You'll have lifetime access to our platform, exclusive mining packages, and all other future cryptocurrency opportunities.</p>
			</div>
			<img src="new/images/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card">
				<img src="new/images/bitcoin_wallet.png" alt="bitcoin wallet">
				<h4 class="blue" data-i18n="start.wallet.tit">GET A BITCOIN WALLET</h4>
				<p data-i18n="start.wallet.txt">You'll need a wallet to send and receive Bitcoin with us, and there are plenty to decide from. Choose one with security features that are right for you.</p>
			</div>
			<img src="new/images/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card begin-mining">
				<img src="new/images/start_mining.png" alt="start mining">
				<h4 class="blue" data-i18n="start.mining.tit">BEGIN MINING</h4>
				<p data-i18n="[html]start.mining.txt">
					Start earning Bitcoins every day!
					<br>
					<br>
				</p>
			</div>
		</p>
	</div>
	<div id="benefits" class="benefits-section section">
		<span class="gray" data-i18n="benefits.subtit">AN ENTIRELY BETTER WAY TO EARN BITCOIN</span>
		<h2 data-i18n="benefits.tit">THE BENEFITS OF FIJI MINING</h2>
		<hr>
		<div class="benefit-card">
			<img src="new/images/no_setup.png" alt="no setup required">
			<h5 class="blue" data-i18n="benefits.s1.hd">NO SETUP REQUIRED</h5>
			<p data-i18n="benefits.s1.pg">As soon as you become a member, you can purchase a mining package and start mining right away. We'll take care of all the heavy lifting.<br><br></p>
		</div>
		<div class="benefit-card">
			<img src="new/images/sustainable_income.png" alt="sustainable income">
			<h5 class="blue" data-i18n="benefits.s2.hd">SUSTAINABLE INCOME PLAN</h5>
			<p data-i18n="benefits.s3.pg">Our lucrative will ensure consistent mining earnings.<br><br></p>
		</div>
		<div class="benefit-card">
			<img src="new/images/transparent_cost.png" alt="transparent upfront cost">
			<h5 class="blue" data-i18n="benefits.s3.hd">TRANSPARENT UPFRONT COST</h5>
			<p data-i18n="benefits.s3.pg">There are no subscriptions or hidden fees. Your one-time $99 membership payment gives you access to our four exclusive mining packages and all other future crypto projects.</p>
		</div>
		<div class="benefit-card">
			<img src="new/images/referral_program.png" alt="referral program">
			<h5 class="blue" data-i18n="benefits.s4.hd">REFERRAL PROGRAM</h5>
			<p data-i18n="benefits.s4.pg">Earn a commission for each person that you refer. You’ll also receive Bitcoin whenever your referral earns from their package. This will be taken straight from our share so you won’t reduce their profit margin.</p>
		</div>
		<div class="benefit-card">
			<img src="new/images/personal_dashboard.png" alt="personal dashboard">
			<h5 class="blue" data-i18n="benefits.s5.hd">PERSONAL DASHBOARD</h5>
			<p data-i18n="benefits.s5.pg">Keep track of your crypto earnings using our simple real-time dashboard. You can also view your affiliate bonuses and billing history.<br><br><br></p>
		</div>
		<div class="benefit-card">
			<img src="new/images/partnerships.png" alt="partnerships">
			<h5 class="blue" data-i18n="benefits.s6.hd">PARTNERSHIP</h5>
			<p data-i18n="benefits.s6.pg">To remain competitive and minimize downtime, we have secured a partnership with a contracted manufacturer specializing in mining hardware.<br><br><br></p>
		</div>
	</div>

	<div id="success" class="success-section section">
		<h2 data-i18n="success.hd">4-STEP SUCCESS PLAN</h2>
		<hr>
			<div class="success-card">
				<img src="new/images/cutting-edge.png" width="85" alt="cutting edge hardware">
				<h5 data-i18n="success.c1.hd">1. Cutting-Edge Mining Hardware</h5>
				<p data-i18n="success.c1.pg">
					Cryptocurrency mining is most profitable when done with top-of-the-line, task-specific hardware. FIJI's mining facilities are equipped with the newsest miners with the best price to value ratio. During the 1st quarter of 2019, we’ll prepare for another expansion with the world’s fastest ASIC miners with 225 TH/s. We will be unrelenting in our vision for the future and will set the industry standard as the market leader.
				</p>
			</div>
			<div class="success-card">
				<img src="new/images/low_electricity.png" width="85" alt="low electricity">
				<h5 data-i18n="success.c2.hd">2. Ultra-Low-Cost Electricity and Stable Energy Supply</h5>
				<p data-i18n="success.c2.pg">
					A key factor in mining profitability is the cost of power. We only mine in locations that have a surplus of electricity due to their well-developed infrastructure, interconnectivity, and renewable energy programs. With this in mind, FIJI Mining has secured several reliable power sources for an extremely low price.
				</p>
			</div>
			<div class="success-card">
				<img src="new/images/secure_facility.png" width="70" alt="secure facility">
				<h5 data-i18n="success.c3.hd">3. Secure and Reliable Facilities</h5>
				<p data-i18n="success.c3.pg">
					Each FIJI Mining facility is surrounded by surveillance cameras and is also protected by armed guards at all times. Our mining team is also on site 24/7 to maintain, maximize, and ensure mining efficiency.
				</p>
			</div>
			<div class="success-card">
				<img src="new/images/team.png" width="85" alt="team">
				<h5 data-i18n="success.c4.hd">4. Top-Notch Experts and Professionals</h5>
				<p data-i18n="success.c4.pg">
					It takes a team of engineers, security and construction experts, and software developers to run even a modest mining center, not to mention centers on the scale FIJI Mining is planning. The FIJI team boasts all of the qualifications and competencies to build, install, and operate industrial-sized mining facilities.
				</p>
			</div>
		<button><a href="https://pinnaclemining.net/bbs/register_form.php?mb_recommend" target="_blank" button data-i18n="success.btn">START MINING NOW</a></button>
	</div>

	<footer>
		<div class="footer-left">
			<h5>FIJI MINING</h5>
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
				<i class="fas fa-map-marker-alt blue"></i> City, Providence, Kazakhstan
			</p>
			<p>
				<i class="fas fa-map-marker-alt blue"></i> City, Providence, Kyrgyzstan
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
				<a href="#">FIJI Mining Policy</a>
			</p>
			<br>
			<br>
			<br>
		</div>
		<div class="footer-right">
			<h5><i class="far fa-envelope blue"></i> Contact Us</h5>
			<p><a href="mailto:ticket@fijimining.com">ticket@fijimining.net</a></p>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
	</footer>

</body>
</html>
