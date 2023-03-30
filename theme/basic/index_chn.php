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

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>FIJI Mining | Home</title>

	<!-- css -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/home.css">

	<!-- javascript -->
	<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/i18next/1.9.0/i18next.min.js" type="text/javascript"></script>
	<!--[if lte IE 8]>
	<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
	<![endif]-->
	<script src="/js/common.js"></script>
	<script Language="javascript">

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
			<a href="#top" id="top-link" class="scroll"><li class="pinnacle-brand"><img src="./img/logo.png" width="25" alt="pinnacle logo"> FIJI MINING</li></a>
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
			<a href="<?php echo G5_BBS_URL; ?>/register_form.php" ><li class="register-link">ENROLLMENT</li></a>
			<a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" ><li class="login-link">LOG IN</li></a>
			<?}?>
			
			<select class="custom-select" id="lang-select">
				<option value="en" selected>English</option>
				<option value="kr">한국어</option>
				<!-- <option value="jp">日本語</option>
				<option value="ch">中文</option>
				<option value="vt">Tiếng Việt</option> -->
			</select>
			
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
			  <h2>为您挖掘比特币</h2>
			  <hr class="carousel-hr">
			  <p>无需耽误您的时间或者为昂贵的设备，电力成本和吵闹的电机担心。我们的挖掘专家会从头到尾关注整个流程。</p>
			</div>
		  </div>
		  <div class="carousel-item">
			<img class="d-block w-100"  src="<?php echo G5_URL; ?>/new/images/slide_img02.png" alt="First slide">
			<div class="carousel-caption d-none d-md-block">
			  <h2>ELEVATED MEMBER REWARDS</h2>
			  <hr class="carousel-hr">
			  <p>Multiply your earnings with the most ambitious and lucrative referral system in the industry. We believe in the principle of collective benefit and that by growing together, everyone earns more.</p>
			</div>
		  </div>
		  <div class="carousel-item">
			<img class="d-block w-100"  src="<?php echo G5_URL; ?>/new/images/slide_img03.png" alt="First slide">
			<div class="carousel-caption d-none d-md-block">
			  <h2>EARN BITCOIN EVERY DAY</h2>
			  <hr class="carousel-hr">
			  <p>FIJI Mining empowers you with an opportunity to profit from an ever-changing financial landscape with innovative blockchain technology and rewarded cryptocurrency mining.</p>
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
		<h2>ABOUT FIJI</h2>
		<hr>
		<p>
			FIJI Mining is on a mission to make Bitcoin and other cryptocurrencies accessible to every people in the world. The company was formed by a team of forward-thinking cryptocurrency experts, investors, and engineers to revolutionize the cryptocurrency mining industry and is building one of the largest crowdfunded Bitcoin mining pools in North America. Our mining facilities run only the latest in mining hardware and maintain profitability through reliable and cost-efficient energy sources.
		</p>
		<video loop="" controls="">
				<source src="http://211.238.13.142/theme/basic/img/main/pinaclemining.mp4" type="video/mp4">
		</video>
		<p>
			We are passionate about securing our members with digital assets and have built a simple and intuitive platform for everyone to access the cryptocurrency market. With mining centers located throughout the world including Canada, Ohio, New York, North Carolina, Kyrgyzstan, Republic of Georgia, and Kazakhstan, FIJI Mining is well on its way to surpassing its goal of reaching <span class="underline">10% of global hash power</span> and <span class="underline">one million members</span> by the end of 2019.
		</p>
		<div class="about-card">
			<img src="<?php echo G5_URL; ?>/new/images/thumbs_up.png" alt="mine with ease">
			<h4 class="blue">MINE WITH EASE</h4>
			<p>No need to mess with hot and noisy miners at home. Our experts take care of the entire mining process for you.</p>
		</div>
		<div class="about-card">
			<img src="<?php echo G5_URL; ?>/new/images/sustainable.png" alt="sustainable income">
			<h4 class="blue">SUSTAINABLE INCOME</h4>
			<p>Multiply your shares with our repurchase program and continue to earn as our facilities expand.</p>
		</div>
		<div class="about-card">
			<img src="<?php echo G5_URL; ?>/new/images/member_bonus.png" alt="member bonuses">
			<h4 class="blue">MEMBER BONUSES</h4>
			<p>Earn Bitcoin for each and every person that you refer. Plus, receive an additional bonus whenever your referral earns from their mining package.</p>
		</div>
	</div>

	<div id="start" class="start-section section">
		<span class="gray">JOIN NOW AND GET STARTED</span>
		<h2>HOW TO START MINING TODAY</h2>
		<hr>
		<p>
			Sign up and purchase hash power in one of our mining packages. Your share will then be allocated to purchase equipment, and you'll begin earning daily payments from whatever is mined. To earn even more, share this opportunity with others and you'll receive a commission on everything they purchase.
			<br>
			<strong>The more you share, the more you earn!</strong>
			<div class="start-card">
				<img src="<?php echo G5_URL; ?>/new/images/sign_up.png" alt="sign up">
				<h4 class="blue">SIGN UP</h4>
				<p>Create your account with a one-time $99 membership fee. You'll have lifetime access to our platform, exclusive mining packages, and all other future cryptocurrency opportunities.</p>
			</div>
			<img src="<?php echo G5_URL; ?>/new/images/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card">
				<img src="<?php echo G5_URL; ?>/new/images/bitcoin_wallet.png" alt="bitcoin wallet">
				<h4 class="blue">GET A BITCOIN WALLET</h4>
				<p>You'll need a wallet to send and receive Bitcoin with us, and there are plenty to decide from. Choose one with security features that are right for you.</p>
			</div>
			<img src="<?php echo G5_URL; ?>/new/images/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card begin-mining">
				<img src="<?php echo G5_URL; ?>/new/images/start_mining.png" alt="start mining">
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

	<h2 id="packages">MINING PACKAGES</h2>
	<hr class="package-hr">
	<div class="package-section section">
		<div class="package-card">
			<h5>PACKAGE 1</h5>
			<h3><span class="blue">$1,000</span></h3>
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>4,500 GH/s <span class="gray">Hash Power</span></p>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<h5>PACKAGE 2</h5>
			<h3><span class="blue">$3,000</span></h3>
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>13,500 GH/s <span class="gray">Hash Power</span></p>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<h5>PACKAGE 3</h5>
			<h3><span class="blue">$5,000</span></h3>
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>22,500 GH/s <span class="gray">Hash Power</span></p>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<h5>PACKAGE 4</h5>
			<h3><span class="blue">$12,000</span></h3>
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>54,000 GH/s</p>
			<button>SELECT PACKAGE</button>
		</div>
		<div class="package-card">
			<div class="new_icon"><img src="<?php echo G5_URL; ?>/new/images/new_icon.png" width="85" alt="new_icon"></div>
			<h5>PACKAGE 5</h5>
			<h3><span class="blue">$25,000</span></h3>
			<h5>Bitcoin Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>112,500 GH/s</p>
			<button>SELECT PACKAGE</button>

		</div>
		<div class="package-card">
			<h5>GPU</h5>
			<h3><span class="blue">$3,000</span></h3>
			<h5>Ethereum Mining</h5>
			<p>1000 Days <span class="gray">/ Contract Term</span></p>
			<p>80 MH/s <span class="gray">Hash Power</span></p>
			<button>SELECT PACKAGE</button>


		</div>
	</div>

	<div id="benefits" class="benefits-section section">
		<span class="gray">AN ENTIRELY BETTER WAY TO EARN BITCOIN</span>
		<h2>THE BENEFITS OF FIJI MINING</h2>
		<hr>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/no_setup.png" alt="no setup required">
			<h5 class="blue">NO SETUP REQUIRED</h5>
			<p>As soon as you become a member, you can purchase a mining package and start mining right away. We'll take care of all the heavy lifting.<br><br></p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/sustainable_income.png" alt="sustainable income">
			<h5 class="blue">SUSTAINABLE INCOME PLAN</h5>
			<p>Our re-purchase program will automatically reinvest in the latest equipment for you to ensure consistent earnings.<br><br></p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/transparent_cost.png" alt="transparent upfront cost">
			<h5 class="blue">TRANSPARENT UPFRONT COST</h5>
			<p>There are no subscriptions or hidden fees. Your one-time $99 membership payment gives you access to our four exclusive mining packages and all other future crypto projects.</p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/referral_program.png" alt="referral program">
			<h5 class="blue">REFERRAL PROGRAM</h5>
			<p>Earn a commission for each person that you refer. You’ll also receive Bitcoin whenever your referral earns from their package. This will be taken straight from our share so you won’t reduce their profit margin.</p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/personal_dashboard.png" alt="personal dashboard">
			<h5 class="blue">PERSONAL DASHBOARD</h5>
			<p>Keep track of your crypto earnings using our simple real-time dashboard. You can also view your affiliate bonuses and billing history.<br><br><br></p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/partnerships.png" alt="partnerships">
			<h5 class="blue">PARTNERSHIP</h5>
			<p>To remain competitive and minimize downtime, we have secured a partnership with a contracted manufacturer specializing in mining hardware.<br><br><br></p>
		</div>
	</div>

	<div id="success" class="success-section section">
		<h2>4-STEP SUCCESS PLAN</h2>
		<hr>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/cutting-edge.png" width="85" alt="cutting edge hardware">
				<h5>1. Cutting-Edge Mining Hardware</h5>
				<p>
					Cryptocurrency mining is most profitable when done with top-of-the-line, task-specific hardware. FIJI's mining facilities are equipped with thousands of Bitmain S9s and are scheduled to upgrade with (seven times faster) 100 TH/s miners in the 4th quarter of 2018. During the 1st quarter of 2019, we’ll prepare for another expansion with the world’s fastest ASIC miners with 225 TH/s. We will be unrelenting in our vision for the future and will set the industry standard as the market leader.
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/low_electricity.png" width="85" alt="low electricity">
				<h5>2. Ultra-Low-Cost Electricity and Stable Energy Supply</h5>
				<p>
					A key factor in mining profitability is the cost of power. We only mine in locations that have a surplus of electricity due to their well-developed infrastructure, interconnectivity, and renewable energy programs. With this in mind, FIJI Mining has secured several reliable power sources for an extremely low price.
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/secure_facility.png" width="70" alt="secure facility">
				<h5>3. Secure and Reliable Facilities</h5>
				<p>
					Each FIJI Mining facility is surrounded by surveillance cameras and is also protected by armed guards at all times. Our mining team is also on site 24/7 to maintain, maximize, and ensure mining efficiency.
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/team.png" width="85" alt="team">
				<h5>4. Top-Notch Experts and Professionals</h5>
				<p>
					It takes a team of engineers, security and construction experts, and software developers to run even a modest mining center, not to mention centers on the scale FIJI Mining is planning. The FIJI team boasts all of the qualifications and competencies to build, install, and operate industrial-sized mining facilities.
				</p>
			</div>
			<button>START MINING NOW</button>
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
				<a href="javascript:open_pinnacle()">Pinnacle Policy</a>
			</p>
			<br>
			<br>
			<br>
		</div>
		<div class="footer-right">
			<h5><i class="far fa-envelope blue"></i> Contact Us</h5>
			<p><a href="mailto:support@pinnaclemining.com">support@pinnaclemining.net</a></p>
			<br>
			<p><a href="new/images/Rockwood Coin Agent Form.pdf" download>Rockwood Coin Agent Form</a></p>
			<p><a href="new/images/Rockwood Coin Trustee Form.pdf" download>Rockwood Coin Trustee Form</a></p>
			<br>
			<br>
			
		</div>
	</footer>

</body>
</html>
