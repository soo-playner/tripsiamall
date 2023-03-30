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
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/home.css">
	<title>Pinnacle Mining | Home</title>
	<script src="<?php echo G5_JS_URL ?>/common.js"></script>
    <style>
        .user-drop-down-section{display:inline-block;margin-top:16px; position:absolute;right:50px;}
        .lang-sel{display:inline-block;}
        .custom-select{display: inline-block;
    width: 100%;
    height: calc(2.25rem + 2px);
    padding: .375rem 1.75rem .375rem .75rem;
    line-height: 1.5;
    color: #495057;
    vertical-align: middle;
    /* background: #fff url(data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' v…0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E) no-repeat right .75rem center; */
    background-size: 8px 10px;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;}        
    
    </style>
</head>
<body>
	<nav>
		<ul id="nav-list">
			<a href="#top" id="top-link" class="scroll"><li class="pinnacle-brand"><img src="./img/logo.png" width="25" alt="pinnacle logo"> PINNACLE MINING</li></a>
           
			<a href="#about" id="about-link" class="scroll"><li>피너클이란</li></a>
			<a href="#start" id="start-link" class="scroll"><li>시작하는 법</li></a>
			<a href="#packages" id="package-link" class="scroll"><li>패키지</li></a>
			<a href="#benefits" id="benefit-link" class="scroll"><li>회원의 특전</li></a>
			<a href="#success" id="successLink" class="scroll"><li>성공의 비결</li></a>
				<? if ($is_admin) { ?>
					<a href="/adm" target="_blank"><li class="menu-kind">ADMIN</li></a>
				<? } ?>
			<? if ($is_member) { ?>
			<a href="<?php  ?>/new/dashboard.php"><li class="myoffice-link">마이 오피스</li></a>
			<a href="<?php echo G5_BBS_URL; ?>/logout.php" ><li class="login-link">로그아웃</li></a>
			<? } else { ?>
			<a href="<?php echo G5_BBS_URL; ?>/register_form.php" ><li class="register-link">회원 등록</li></a>
			<a href="<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" ><li class="login-link">로그인</li></a>
            <?}?>

			
            <div class="user-drop-down-section">

<div class="lang-sel">
    <select class="custom-select">
      <option selected="Eng">English</option>
  <option value="kor">한국어</option>
  <!--option value="jap">日本語</option>
  <option value="chin">中文</option>
  <option value="viet">Tiếng Việt</option-->
    </select>
</div>
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
	          <p>Pinnacle Mining empowers you with an opportunity to profit from an ever-changing financial landscape with innovative blockchain technology and rewarded cryptocurrency mining.</p>
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
		<span class="gray">우리의 임무</span>
		<h2>피너클 마이닝에 관하여</h2>
		<hr>
		<p>
			피너클 마이닝은 비트코인과 기타 암호화 화폐를 전 세계 모든 이들이 소유할 수 있도록 하는 것을 목표로 설립되었습니다. 피너클 마이닝은 앞선 생각을 하는 암호화 화폐 전문가들과 투자자들과 엔지니어들로 구성되어있으며 암호화 화폐 채굴 산업을 혁신 시키고 크라우드 펀딩으로 설립된 세계 최대 규모의 비트코인 채굴장을 북미지역에 건설하고 있습니다. 피너클 마이닝은 최첨단 채굴 장비를 이용하여 채굴을 하고 있으며 저렴하면서도 안정된 전력을 확보하여 채산성을 유지하고 있습니다.
		</p>
		<video loop="" controls="">
				<source src="http://211.238.13.142/theme/basic/img/main/pinaclemining.mp4" type="video/mp4">
		</video>
		<p>
        피너클 마이닝은 회원들이 디지털 자산을 하나라도 더 확보하도록 노력하고 있으며 누구나 쉽게 암호화 화폐 시장에 발을 들여 놓을 수 있도록 간단하고 직관적인 플랫폼을 구축하였습니다. 캐나다 몬트리얼, 미국 오하이오, 뉴욕, 노스 캐롤라이나, 기르기스탄, 카자흐스탄, 그루지아 공화국등 여러 지역에서 채굴장을 운영중인 피너클 마이닝은 2019년 전 세계 해시파워의 10% 도달, 백 만명 회원 모집을 목표로 하고 있습니다.
		</p>
		<div class="about-card">
			<img src="<?php echo G5_URL; ?>/new/images/thumbs_up.png" alt="mine with ease">
			<h4 class="blue">간편하게 누구나 채굴</h4>
			<p>집에서 열도 많이 나고 시끄러운 채굴기로 고생하지 않아도 됩니다. 채굴 전문팀이 여러분을 대신하여 채굴장비를 운영하고 유지보수합니다.</p>
		</div>
		<div class="about-card">
			<img src="<?php echo G5_URL; ?>/new/images/sustainable.png" alt="sustainable income">
			<h4 class="blue">지속적 수입</h4>
			<p>피너클 마이닝의 재구매 프로그램은 마이닝 해시를 꾸준히 증가시켜서 비트코인 채굴 용량을 꾸준히 늘려줍니다.
</p>
		</div>
		<div class="about-card">
			<img src="<?php echo G5_URL; ?>/new/images/member_bonus.png" alt="member bonuses">
			<h4 class="blue">멤버 보너스</h4>
			<p>본인이 추천하는 각 사람으로부터 비트코인을 지급받게 됩니다. 또한 이 사람들이 마이닝 풀에서 수익을 받을 때마다 본인에게도 수당이 지급됩니다.</p>
		</div>
	</div>

	<div id="start" class="start-section section">
		<span class="gray">지금 가입하시고 채굴을 시작하십시오.</span>
		<h2>오늘 채굴을 시작하는 방법</h2>
		<hr>
		<p>
        지금 등록하고 5개의 패키지 중 하나를 구입합니다. 지불한 금액은 채굴기 구매에 사용되며 채굴이 시작되면 채굴된 수량에 상관없이 매일코인을 받게 됩니다. 소득을 더욱 늘리려면 이 기회를 다른 사람들에게 전하고 그들이 구매하는 모든 것으로 부터 보너스를 받으면 됩니다. 
			<br>
			<strong>더 많이 나누면 소득도 더 늘어납니다.</strong>
			<div class="start-card">
				<img src="<?php echo G5_URL; ?>/new/images/sign_up.png" alt="sign up">
				<h4 class="blue">회원등록을 합니다.</h4>
				<p>$99 회원비를 내고 구좌를 개설합니다. 회원등록을 하면 피너클이 제공하는 다양한 채굴 상품을 구매할 수 있습니다.</p>
			</div>
			<img src="<?php echo G5_URL; ?>/new/images/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card">
				<img src="<?php echo G5_URL; ?>/new/images/bitcoin_wallet.png" alt="bitcoin wallet">
				<h4 class="blue">비트코인 지갑을 만듭니다.</h4>
				<p>비트코인을 주고 받으려면 전자지갑이 피룡합니다. 수 많은 지갑들이 존재하는데 본인의 입맛에 맞는 보안 기능을 가진 지갑을 선택하면 됩니다.</p>
			</div>
			<img src="<?php echo G5_URL; ?>/new/images/right_arrow.png" class="right-arrow" alt="right arrow">
			<div class="start-card begin-mining">
				<img src="<?php echo G5_URL; ?>/new/images/start_mining.png" alt="start mining">
				<h4 class="blue">채굴을 시작합니다.</h4>
				<p>
                오늘부터 비트코인을 받기 시작합니다.
					<button>가즈아~~</button>
					<br>
					<br>
				</p>
			</div>
		</p>
	</div>

	<h2 id="packages">마이닝 패키지</h2>
	<hr class="package-hr">
	<div class="package-section section">
		<div class="package-card">
        <h5>패키지 1</h5>
			<h5>비트코인 채굴 </h5>
			<p>1000 일 <span class="gray">계약기간 </span></p>
			<p>4,500 GH/s <span class="gray">해시파워 </span></p>
			<h5><span class="blue">$1,000</span></h5>
			<button>패키지 선택 </button>
		</div>
		<div class="package-card">
        <div class="package-card">
			<h5>패키지 2</h5>
			<h5>비트코인 채굴 </h5>
			<p>1000 일  <span class="gray">계약기간</span></p>
			<p>13,500 GH/s <span class="gray">해시파워</span></p>
			<h5><span class="blue">$3,000</span></h5>
			<button>패키지 선택</button>
		</div>
		<div class="package-card">
        <h5>패키지 3</h5>
			<h5>비트코인 채굴 </h5>
			<p>1000 일  <span class="gray">계약기간</span></p>
			<p>22,500 GH/s <span class="gray">해시파워</span></p>
			<h5><span class="blue">$5,000</span></h5>
			<button>패키지 선택</button>
		</div>
		<div class="package-card">
        <h5>패키지 4</h5>
			<h5>비트코인 채굴 </h5>
			<p>1000 일  <span class="gray">계약기간</span></p>
			<p>54,000 GH/s <span class="gray">해시파워</span></p>
			<h5><span class="blue">$12,000</span></h5>
			<button>패키지 선택</button>
		</div>
		<div class="package-card">
        <h5>패키지 5</h5>
			<h5>비트코인 채굴 </h5>
			<p>1000 일  <span class="gray">계약기간</span></p>
			<p>112,500 GH/s <span class="gray">해시파워</span></p>
			<h5><span class="blue">$25,000</span></h5>
			<button>패키지 선택</button>
			<div class="new_icon"><img src="<?php echo G5_URL; ?>/new/images/new_icon.png" width="85" alt="new_icon"></div>
		</div>
		<div class="package-card">
        <h5>GPU</h5>
			<h5>이더리움 채굴 </h5>
			<p>1000 일  <span class="gray">계약기간</span></p>
			<p>80 MH/s <span class="gray">해시파워</span></p>
			<h5><span class="blue">$3,000</span></h5>
			<button>패키지 선택</button>


		</div>
	</div>

	<div id="benefits" class="benefits-section section">
    <span class="gray">비트코인을 소유하기 위한 더 나은 방법</span>
		<h2>피너클이 드리는 혜택</h2>
		<hr>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/no_setup.png" alt="no setup required">
			<h5 class="blue">장비를 설치할 필요가 없다. </h5>
			<p>피너클의 회원이 되는 즉시 패키지 중 하나를 사서 채굴을 시작할 수 있습니다. 어렵고 힘든일은 우리이게 맡기시면 됩니다.</p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/sustainable_income.png" alt="sustainable income">
			<h5 class="blue">지속적인 수입구조 </h5>
			<p>피너클의 재구매 프로그램은 최신 장비를 구입하는데 사용되어 꾸준한 수익을 보장합니다.<br></p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/transparent_cost.png" alt="transparent upfront cost">
			<h5 class="blue">투명한 비용 </h5>
			<p>숨겨진 비용이 없습니다. $99을 내고 회원이 되면 4가지의 비트코인 채굴 상품들과 다른 채굴 상품을 구매할 수 있습니다. </p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/referral_program.png" alt="referral program">
			<h5 class="blue">소개 프로그램</h5>
			<p>소개하는 각 사람으로 부터 소개비기 지급됩니다. 그리고 소개한 사람들이 구매한 패키지에서 비트코인을 받을 때마다 본인에게도 수익이 올라옵니다. </p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/personal_dashboard.png" alt="personal dashboard">
			<h5 class="blue">개인 데시보드 </h5>
			<p>직관적이고 간편한 데시보드를 이용해서  조직의 상태를 한 눈에 파악할 수 있습니다. 또한 하부 조직의 성장 상태를 실시간으로 점검할 수 있습니다.</p>
		</div>
		<div class="benefit-card">
			<img src="<?php echo G5_URL; ?>/new/images/partnerships.png" alt="partnerships">
			<h5 class="blue">파트너쉽 </h5>
			<p>급변하는 시장상황에서 경쟁력을 유지하고 다운타임을 최소화 하기 위해 채굴장비 개발 업체들과 운영회사들과 파트너 계약을 맺었습니다.</p>
		</div>
	</div>

	<div id="success" class="success-section section">
    <h2>4 가지 성공의 비결 </h2>
		<hr>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/cutting-edge.png" width="85" alt="cutting edge hardware">
				<h5>1. 최첨단 채굴 장비</h5>
				<p>함호화 화폐 채굴은 최첨단 기술과 채굴하는 코인에 특화된 기계를 이용해야 채산성을 높일 수 있습니다. 피너클의 채굴장은 비트메인의 최신 장비 S9들과 GPU 채굴기들을 운영하고 있으며 2018년 4분기에는 이보다 7배 이상 빠른 장비로 업그레이드 할 계획입니다. 그리고 2019년에는 세계에서 가장 빠른 장비도 도입할 계획입니다. 우리는 목표를 향해 꾸준이 사업을 확장하고 있으며 시장을 새롭게 개척해 나갈겁니다. 
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/low_electricity.png" width="85" alt="low electricity">
				<h5>2. 저렴한 전기값과 안정정인 전력 공급 </h5>
				<p>채굴장비의 채산성은 전력 수급 및 가격과 직접적으로 연관되어있습니다. 피너클은 전력 인프라가 잘 구축되어 있는 지역, 발전소들이 집중된 지역, 재생 에너지 프로그램 지원으로 전력이 풍부한 지역을 선택해서 채굴장을 설립합니다. 이렇게 하여 피너클은 아주 싼 가격에 전력을 이용하고 있습니다. 
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/secure_facility.png" width="70" alt="secure facility">
				<h5>3. 철저한 보안 체계를 갖춘 채굴 시설 </h5>
				<p>모든 채굴장은 보안 카메라와 무장한 경비로 24시간 감시하고 있습니다. 피너클 운영팀 또한 24시간 상주하여 채굴장비의 효율을 극대화 하고 있습니다.
				</p>
			</div>
			<div class="success-card">
				<img src="<?php echo G5_URL; ?>/new/images/team.png" width="85" alt="team">
				<h5>4. 최고 수준의 전문가와 경험자들 </h5>
				<p>피너클이 보유하고 있는 규모에 한참 못 미치는 소규모 채굴 업체들도 엔지니어, 보안담당, 건설업체, 컴퓨터 전문가가 팀을 이루어서 운영을 합니다. 피너클 마이닝의 운영팀은 최고 수준의 전문가들로 이루어져 있어서 대규모 시설을 건설하고 장비를 설치하고 운영하는데 전혀 문제가 없습니다.
				</p>
			</div>
			<button>지금 채굴을 시작하십시오</button>
	</div>

	<footer>
		<div class="footer-left">
			<h5>PINNACLE MINING</h5>
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
