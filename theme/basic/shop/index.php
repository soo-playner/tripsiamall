<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

define("_INDEX_", TRUE);

include_once(G5_THEME_SHOP_PATH.'/shop.head.php');
?>

	<!-- slide section -->
	<section>
		<div class="slider">

			<div class="slide_viewer">
				<div class="slide_group">

				<!-- first -->
				<div class="slide first">
					<div class="text-box">
						<h2>DONE FOR YOUR BITCOIN MINING</h2>
						<hr />
						<p>
							No need to waste your time or worry about expensive equipment, electricity management, or loud fans. <br>
							Our mining experts will take care of everything from start to finish.
						</p>
					</div>

				</div>

				<!-- second -->
				<div class="slide second">
					<div class="text-box">
						<h2>ELEVATED MEMBER REWARDS</h2>
						<hr />
						<p>
							Multiply your earnings with the most ambitious and lucrative referral system in the industry. <br>
							We believe in the principle of collective benefit and that by growing together, everyone earns more.
						</p>
					</div>

				</div>

				<!-- third -->
				<div class="slide third">
					<div class="text-box">
						<h2>EARN BITCOIN EVERY DAY</h2>
						<hr />
						<p>
							Pinnacle Mining empowers you with an opportunity to profit from an ever-changing financial landscape <br>
							with innovative blockchain technology and rewarded cryptocurrency mining.
						</p>
					</div>
				</div>

				<!-- 네비게이션 -->

				<div class="directional_nav">
					<div class="inner">
						<div class="previous_btn arrow_btn" title="Previous">
							<img src="<?php echo G5_THEME_URL; ?>/img/main/left-arrow.png">
						</div>
						<div class="next_btn arrow_btn" title="Next">
							<img src="<?php echo G5_THEME_URL; ?>/img/main/right-arrow.png">
						</div>
					</div>
				</div><!-- End // .directional_nav -->

				<div class="Tab_button slide_buttons"></div>

				</div><!-- view -->
			</div><!-- group -->
		</div>

	</section><!-- slide section -->

	<!--====================== content section ==================================-->

	<section class="csection cse02 SecPhra">
		<div class="inner">
			<video loop controls>
				<source src ="<?php echo G5_THEME_URL; ?>/img/main/pinaclemining.mp4" type = "video/mp4">
			</video>

			<div class="top-phra">
				<h2>All about Bitcoin</h2>

				<div class="phra-box after">
					<div class="kind">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s2_icon_coin.png" alt="" />
						<h4>What is Bitcoin?</h4>
						<hr />
						<p>
              Bitcoin was created in 2009 by Satoshi Nakamoto and is the world’s first decentralized
              digital cryptocurrency. It operates on a peer-to-peer payment system and allows users to
              send and receive money without the need of a centralized entity like a bank. Millions of
              people have already begun to use Bitcoin for everyday purchases such as coffee,
              electronics, and plane tickets.
						</p>
					</div><!-- kind -->
					<div class="kind second">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s2_icon_coin2.png" alt="" />
						<h4>Why Bitcoin?</h4>
						<hr />
						<p>
              There are myriad reasons for Bitcoin’s popularity. It has no borders, no qualifications,
              and essentially no fees. Anybody can use it. It’s also extremely fast, anonymous, and
              completely safe. In recent years, many people have also become interested for investment
              reasons due to the price skyrocketing to the thousands.
						</p>
					</div><!-- kind -->
					<div class="kind">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s2_icon_anonymous.png" alt="" />
						<h4>Anonymity</h4>
						<hr />
						<p>
              Although each Bitcoin transaction is recorded in a public ledger, the names of buyers and
              sellers are never revealed – only their wallet IDs. This allows users to keep their
              transactions private with little to no traceability.
						</p>
					</div><!-- kind -->
					<div class="kind last">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s2_icon_question.png" alt="" />
						<h4>Future in question</h4>
						<hr />
						<p>
              No one knows what the future of Bitcoin will look like. The currency is presently
              unregulated in most parts of the world, but countries such as Japan, China, and Australia
              have begun weighing regulations due to concerns regarding taxation and lack of control.
						</p>
					</div><!-- kind -->
				</div>
			</div><!-- top-phra -->

			<div class="bottom-phra">
				<h3><span>4 Reasons</span> Why You Should Own Bitcoins</h3>

				<div class="text-kind">
					<p class="title"><span>1. Get in on the potential for high returns</span></p>
					<p class="content">
						If you bought 100 bitcoin at the start of 2017 for around $780 each, your investment of $78,000
            would be worth around $1.64 million today, based on a bitcoin value of $16,411 on December 14.
            Some forecasts suggest bitcoin can still go up, maybe by a lot, and that is a good reason to buy.
            Simon Yu, CEO of StormX, a platform for users to earn money from micro-tasks, is bullish,
            saying that growth is coming as institutional investors get into cryptocurrencies, pushing up demand.
					</p>
				</div>

				<div class="text-kind">
					<p class="title"><span>2. Bitcoin can help diversify your portfolio</span></p>
					<p class="content">
						Some of the soundest advice for investing is to diversify your holdings, i.e. don’t put all of
            your eggs in the same basket. If you already own bonds and stocks, it could be good to put a
            little on some riskier assets that may fetch higher returns as long as you don’t mind losing the money.
					</p>
				</div>

				<div class="text-kind">
					<p class="title"><span>3. Bitcoin can verse you in how to use what could be a future form of currency</span></p>
					<p class="content">
						No matter what happens with bitcoin, it’s widely expected that cryptocurrencies will become more
            common in the future. In April, Japan legalized bitcoin as a method of payment, and Russia has said
            it is considering regulating its use. The Chicago Mercantile Exchange launched bitcoin derivatives on
            December 18, making it possible for hedge funds to get in the market to bet on the future value of the currency.
					</p>
				</div>

				<div class="text-kind last">
					<p class="title"><span>4. You can find out about new business models</span></p>
					<p class="content">
						Bitcoin and other such currencies run on blockchain technology, which is perhaps more exciting than the cryptocurrencies
            themselves. Blockchain is a technology for keeping records, a sort of public ledger. Every bitcoin trade
            is registered on this ledger as a line, such as new data on a spreadsheet. With blockchain, it would be
            cheaper and more efficient to process micro- or even nano-payments now unfeasible because of high processing
            fees, such as buying a single article on the internet for $0.10.
					</p>
				</div>
			</div>
		</div>

	</section>

	<section class="csection csec03">
		<div class="inner after">
			<div class="prediction flot-left">
				<h3>Bitcoins price predictions</h3>

				<div class="content">
					<div class="Opinion">
						<p class="name">John McAfee</p>
						<div class="after">
							<div class="img flot-left">
								<img src="<?php echo G5_THEME_URL; ?>/img/main/img_people01.png" alt="" />
							</div>
							<div class="op_text flot-right">
								“When I predicted Bitcoin at $500,000 by the end of 2020, it used a model that
								predicted $5,000 at the end of 2017. BTC has accelerated much faster than my
								model assumptions. I now predict Bitcoin at $1 million by the end of 2020.
								I will still eat my d*** if wrong.”
							</div>
						</div>

					</div><!-- Opinion -->

					<div class="Opinion">
						<p class="name">Venture Capitalist Tim Draper  </p>
						<div class="after">
							<div class="img flot-left">
								<img src="<?php echo G5_THEME_URL; ?>/img/main/img_people02.png" alt="" />
							</div>
							<div class="op_text flot-right">
								Last month, the famous venture capitalist and inspiring entrepreneur, Tim
								Draper, made a prediction that Bitcoin will hit $250,000 by the year 2022. In
								2014, Draper predicted that Bitcoin would be worth $10,000 in three years.
								Sure enough, Bitcoin hit the $10,000 mark, soaring above and beyond this
								amount to reach an all-time high of $19,783 last December.

							</div>
						</div>
					</div><!-- Opinion -->

					<div class="Opinion">
						<p class="name">Llew Claasen</p>
						<div class="after">
							<div class="img flot-left">
								<img src="<?php echo G5_THEME_URL; ?>/img/main/img_people03.png" alt="" />
							</div>
							<div class="op_text flot-right">
								Llew Claasen who is the executive director of Bitcoin foundation recently stated
								that he expected Bitcoin to hit $ 40,000 by the end of 2018. <br>
								He further added that 90% of the cryptocurrencies will actually fall. According
								to him, most of the cryptocurrency projects will actually turn out to be scams.
							</div>
						</div>
					</div><!-- Opinion -->

					<div class="Opinion">
						<p class="name">Jeet Singh</p>
						<div class="after">
							<div class="img flot-left">
								<img src="<?php echo G5_THEME_URL; ?>/img/main/img_people04.png" alt="" />
							</div>
							<div class="op_text flot-right">
								Speaking in January at the World Economic Forum in Davos, experienced
								cryptocurrency fund manager, Jeet Singh predicted that the price of Bitcoin will
								go as high as $50,000 in 2018. He warned of heavy price fluctuations which he
								believes is only normal for a Bitcoin market that is still maturing.
							</div>
						</div>
					</div><!-- Opinion -->



				</div>
			</div>
			<div class="prediction flot-right">
				<h3>Bitcoins predictions from Experts</h3>

				<div class="Opinion">
					<p class="name">Saxo Bank’s Bitcoin Price Prediction — The Future Of Bitcoin</p>
					<div class="op_text">
						Saxo Bank thinks Bitcoin will increase a lot this year. Not quite as much as John McAfee does,
						but still a lot. Saxo’s Bitcoin prediction 2018: they say the Bitcoin price will rise above $60,000
						in 2018!
					</div>
				</div><!-- Opinion -->
				<div class="Opinion">
					<p class="name">Blockchain Capital Partner, Spencer Bogart’s Bitcoin Price Prediction </p>
					<div class="op_text">
						Blockchain Capital partner, Spencer Bogart’s Bitcoin price prediction is exactly that. His Bitcoin
						projection says that it will increase to more than $50,000.  He said the increase would be due to
						big investments being made by large companies and investment banks. Mr. Bogart believes
						banks have seen how much money people are making with Bitcoin and do not want to miss out.
					</div>
				</div><!-- Opinion -->
				<div class="Opinion">
					<p class="name">Founder of Wawllet, Mihail Lala’s Bitcoin Price Prediction 2020</p>
					<div class="op_text">
						Mihail Lala (the founder and CEO of Wawllet) has a Bitcoin price prediction for 2020. His Bitcoin
						projection is that Bitcoin will get to over $100,000 by 2020.
					</div>
				</div><!-- Opinion -->
				<div class="Opinion">
					<p class="name">CEO of VA Research, David Garrity’s Bitcoin Price Prediction</p>
					<div class="op_text">
						David Garrity (the CEO at GVA Research) told Bloomberg that Bitcoin is going to crash as low as
						$5,000 this year. He believes that by the end of 2018, it will go back up. He says it’ll get to
						almost $20,000, just like at the end of 2017. As we have seen in Bitcoin’s history, this is
						completely normal for the cryptocurrency.
					</div>
				</div><!-- Opinion -->
			</div>

		</div>

	</section>

	<section class="csection csec04 SecPhra">
		<div class="inner">
			<div class="top-phra">
				<h2>How to acquire Bitcoins</h2>

				<div class="phra-box after">
					<div class="kind">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s4_img_money.png" alt="" />
						<h4>Buy on an Exchange</h4>
						<hr />
						<p>Many marketplaces called “bitcoin exchanges” allow people to buy or sell bitcoins using different currencies.
							Coinbase is a leading exchange, along with Bitstamp and Bitfinex.
						</p>
					</div><!-- kind -->

					<div class="kind second">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s4_img_phone.png" alt="" />
						<h4>Transfers</h4>
						<hr />
						<p>People can send bitcoins to each other using mobile apps or their computers. It’s similar to sending cash digitally.
						</p>
					</div><!-- kind -->

					<div class="kind last">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s4_img_system.png" alt="" />
						<h4>Mining</h4>
						<hr />
						<p>People compete to “mine” bitcoins using computers to solve complex math puzzles.
							This is how bitcoins are created. Bitcoin mining is so called because it resembles the mining of other commodities:
							it requires exertion, and it slowly makes new currency available at a rate that resembles
							the rate at which commodities like gold are mined from the ground.
						</p>
					</div><!-- kind -->
				</div>
			</div>

			<div class="bottom-phra">
				<h2>How to mine Bitcoins?</h2>

				<div class="after top-phra">
					<div class="mining phra-box after flot-left">
						<dl class="kind b-kind">
							<dt><img src="<?php echo G5_THEME_URL; ?>/img/main/s5_icon_cloud.png" alt="" /></dt>
							<dd class="text-box">
								<h4>Cloud Mining</h4>
								<hr />
								<p>
									Cloud mining or cloud hashing enables users to purchase mining capacity of hardware in data centers.
									Bitcoin cloud mining allows people to earn Bitcoins without managing hardware, software, electricity,
                  bandwidth or other offline issues. However, when you do the math, it seems that none of these cloud
                  mining sites are profitable in the long run. Those that do seems profitable are usually scams that don’t
                  even own any mining equipment, they are just elaborate Ponzi schemes.
								</p>

							</dd>
						</dl>

					</div>

					<div class="mining phra-box after flot-right">
						<dl class="kind b-kind">
							<dt><img src="<?php echo G5_THEME_URL; ?>/img/main/s5_icon_pool.png" alt="" /></dt>
							<dd class="text-box">
								<h4>Pool Mining</h4>
								<hr />
								<p>
									Mining pools are groups of cooperating miners who agree to share block rewards in proportion to their
                  contributed mining hash power. While mining pools are desirable to the average miner as they smooth
                  out rewards and make them more predictable, they, unfortunately, concentrate power to the mining pool’s owner.
									Miners can, however, choose to redirect their hashing power to a different mining pool at any time.
								</p>

							</dd>
						</dl>

					</div>
				</div><!-- top-phra -->

				<div class="vsMining after">
					<div class="img_box flot-left">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s5_img_bitcoin.png" alt="" />
					</div>
					<div class="text-box flot-right">
						<h4>Mining Pools vs Cloud Mining</h4>
						<p>
							Many people read about mining pools and think it is just a group that pays out free bitcoins.
							This is not true! Mining pools are for people who have mining hardware to split profits. Many people get mining pools confused with cloud mining.
							Cloud mining is where you pay a service provider to mine for you, and you get the rewards.
						</p>
					</div>
				</div>
			</div>

		</div>
	</section>

	<section class="csection csec05 csecPhra conPhra">
		<div class="inner">
			<div class="top_box">
				<h2>Pinnacle Mining Success Plan</h2>
				<hr />
				<p>
					World class mining facilities with cutting-edge mining hardware and industry-leading experts and professionals.
				</p>
				<div class="map_box">
					<h3>What is <span>Pinnacle</span> Mining?</h3>
					<img src="<?php echo G5_THEME_URL; ?>/img/main/s6_map.png" alt="" />
					<p>Pinnacle Mining is on a mission to make Bitcoin and other cryptocurrencies accessible to every people in the world. <br>
					Pinnacle Mining was formed by a team of forward-thinking cryptocurrency experts, investors, and engineers to
					revolutionize the cryptocurrency mining industry and is building one of the largest crowdfunded Bitcoin mining
					pools in Canada and Europe.
					</p>
				</div>
			</div><!-- top_box -->

			<div class="bottom_box">
				<span>THE PINNACLE MINING MISSION</span>
				<h2>WHY PINNACLE MINING?</h2>
				<hr />
				<p>
					Pinnacle Mining offers a simple platform for everyone to access the cryptocurrency market. <br>
					We are passionate about securing our members with digital assets and helping them profit from the <br>
					Bitcoin market by providing access to cryptocurrency mining without any of the logistical overhead.
				</p>
				<div class="content_box after">
					<div class="kind">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s6_icon01.png" alt="" />
						<h4>MINE WITH EASE</h4>
						<hr />
						<p>
							No need to mess with hot and noisy miners at home.
							Our experts take care of the entire mining process for you.
						</p>
					</div><!-- kind -->

					<div class="kind second">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s6_icon02.png" alt="" />
						<h4>SUSTAINABLE INCOME</h4>
						<hr />
						<p>
							Multiply your shares with our repurchase program and
							continue to earn as the mining pool expands.

						</p>
					</div><!-- kind -->

					<div class="kind last">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s6_icon03.png" alt="" />
						<h4>MEMBER BONUSES</h4>
						<hr />
						<p>
							Earn Bitcoin for each and every person that you refer.
							Plus, receive an additional bonus whenever your referral earns from their mining pool.

						</p>
					</div><!-- kind -->

				</div>
			</div>
		</div>

	</section>

	<section class="csection csec06 csecPhra">
		<div class="inner">
			<div class="top_box">
				<span>AN ENTIRELY BETTER WAY TO EARN BITCOIN</span>
				<h2>THE BENEFITS OF PINNACLE MINING</h2>
				<hr />
				<p>
					Pinnacle Mining was designed to revolutionize the cryptocurrency mining industry and has quickly become one of the
					largest crowdfunded Bitcoin mining pools in the world. By combining this expansion with our extremely profitable
					affiliate structure, we have an opportunity for members to leverage their earning potential unlike anything else in this
					industry. Here is what you can expect by becoming a member.
				</p>
				<div class="content_box">
					<img src="<?php echo G5_THEME_URL; ?>/img/main/s7_img_benefits.png" alt="" />
					<div class="bubble after w1045">
						<div class="flot-left kind text01">
							<p class="title"><ins>NO SETUP REQUIRED</ins></p>
							<p>
								As soon as you become a member, you can purchase
								a mining pool share and start mining right away.
								We'll take care of all the heavy lifting.
							</p>
						</div>
						<div class="flot-right kind text02">
							<p class="title"><ins>REFERRAL PROGRAM</ins></p>
							<p>
								Earn a commission for each person that you refer.
								You’ll also receive Bitcoin whenever your referral
								earns from their pool. This will be taken straight from
								our share so you won’t reduce their profit margin.
							</p>
						</div>
					</div><!-- bubble -->

					<div class="bubble after ">
						<div class="flot-left kind text03">
							<p class="title"><ins>SUSTAINABLE INCOME PLAN</ins></p>
							<p>
								Our re-purchase program will automatically
								reinvest in the latest equipment for you to ensure
								consistent earnings.
							</p>
						</div>
						<div class="flot-right kind text04">
							<p class="title"><ins>PERSONAL DASHBOARD</ins></p>
							<p>
								Keep track of your crypto earnings using our simple
								real-time dashboard. You can also view your affiliate
								bonuses and billing history.
							</p>

						</div>
					</div><!-- bubble -->

					<div class="bubble after w1045">
						<div class="flot-left kind text05">
							<p class="title"><ins>TRANSPARENT UPFRONT COST</ins></p>
							<p>
								There are no subscriptions or hidden fees. Your
								one-time $99 membership payment gives you access
								to our four exclusive mining pools and all other future
								crypto projects.
							</p>

						</div>
						<div class="flot-right kind text06">
							<p class="title"><ins>PARTNERSHIP</ins></p>
							<p>
								To remain competitive and minimize downtime,
								we have secured a partnership with a contracted
								manufacturer specializing in mining hardware.


							</p>

						</div>
					</div><!-- bubble -->
				</div>
			</div><!-- top_box -->

			<div class="bottom_box after">
				<div class="img_box flot-left">
					<img src="<?php echo G5_THEME_URL; ?>/img/main/s7_img_graph.png" alt="" />
				</div>
				<div class="text_box flot-right">
					<h2><span>Vision</span> of Pinnacle Mining</h2>
					<p>
						Building the world’s biggest crowdfunded cryptocurrency
						mining operation, reaching 10% of global hash power and
						one million members by 2019

					</p>
				</div>

			</div>
		</div>
	</section>

	<section class="csection csec07 csecPhra conPhra">
		<div class="inner">
			<div class="bottom_box">
				<span>JOIN NOW AND GET STARTED</span>
				<h2>HOW TO START MINING TODAY</h2>
				<hr />
				<p>
					Sign up and purchase shares in one of our mining pools. <br>
					Your share will then be allocated to purchase equipment, and you'll begin earning daily payments from whatever is mined. <br>
					To earn even more, share this opportunity with others and you'll receive a commission on everything they purchase. <br>
					<strong>The more you share, the more you earn!</strong>
				</p>
				<div class="content_box after">
					<div class="kind">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s7_icon01.png" alt="" />
						<h4>Sign up</h4>
						<hr />
						<p>
							Create your account with a one-time $99 membership fee.
							You'll have lifetime access to our platform, exclusive mining pools,
							and all other future cryptocurrency opportunities.
						</p>
					</div><!-- kind -->

					<div class="kind second">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s7_icon02.png" alt="" />
						<h4>Get a Bitcoin Wallet</h4>
						<hr />
						<p>
							You'll need a wallet to send and receive Bitcoin with us,
							and there are plenty to decide from.
							Choose one with security features that are right for you.
						</p>
					</div><!-- kind -->

					<div class="kind last">
						<img src="<?php echo G5_THEME_URL; ?>/img/main/s7_icon03.png" alt="" />
						<h4>Begin Mining Bitcoins</h4>
						<hr />
						<p>
							Start earning Bitcoins every day!
						</p>
					</div><!-- kind -->

				</div>
			</div>

		</div>

	</section>

	<section class="csection csec08">
		<div class="inner">
			<h2>What Makes Pinnacle Mining Successful?</h2>

			<div class="content">
				<dl>
					<dt><img src="<?php echo G5_THEME_URL; ?>/img/main/s9_img01.png" alt="" /></dt>
					<dd>
						<p class="title-phra">1. Cutting-Edge Mining Hardware</p>
						<p class="con-phra">
              Cryptocurrency mining is most profitable when done with top-of-the-line, task-specific hardware.
              Pinnacle Mining facilities are equipped with thousands of Bitmain S9s and are scheduled to upgrade
              with (seven times faster) 100 TH/s miners in the 4th quarter of 2018. During the 1st quarter of
              2019, we’ll prepare for another expansion with the world’s fastest ASIC miners with 225 TH/s. We
              will be unrelenting in our vision for the future and will set the industry standard as the market leader.
						</p>
					</dd>
				</dl>

				<dl class="padding-dl">
					<dt><img src="<?php echo G5_THEME_URL; ?>/img/main/s9_img02.png" alt="" /></dt>
					<dd>
						<p class="title-phra">2. Ultra-Low-Cost Electricity and Stable Energy Supply</p>
						<p class="con-phra">
							A key factor in mining profitability is the cost of power. Europe and Canada have a surplus of
              electricity due to their well-developed infrastructure, interconnectivity, and renewable energy programs.
              With this in mind, Pinnacle Mining has secured several reliable power sources for an extremely low price.
						</p>
					</dd>
				</dl>

				<dl class="padding-dl">
					<dt><img src="<?php echo G5_THEME_URL; ?>/img/main/s9_img03.png" alt="" /></dt>
					<dd>
						<p class="title-phra">3. Secure and Reliable Facilities</p>
						<p class="con-phra">
              Each Pinnacle Mining center will be surrounded by surveillance cameras and will also be protected by armed
              guards at all times. Our mining team will also be on site 24/7 to maintain, maximize, and ensure mining efficiency.
						</p>
					</dd>
				</dl>

				<dl>
					<dt><img src="<?php echo G5_THEME_URL; ?>/img/main/s9_img04.png" alt="" /></dt>
					<dd>
						<p class="title-phra">4. Top-Notch Experts and Professionals</p>
						<p class="con-phra">
							It takes a team of engineers, security and construction experts, and software developers to run even a modest
              mining center, not to mention centers on the scale Pinnacle Mining is planning. The Pinnacle Mining Team boasts
              all of the qualifications and competencies to build, install, and operate industrial-sized mining facilities.
						</p>
					</dd>
				</dl>

			</div>
		</div>
	</section>

	<section class="csection csec10">
		<div class="inner">
			<div class="content">
				<!--START COL -->
				<div class="pool-info-col" data-aos="fade-left">
					<div class="title-sec">
						<img class="title-img" src="<?php echo G5_THEME_URL; ?>/img/membership99.png">
					</div>
					<div class="pool-slide">
						<div class="pool-title">
						  Pinnacle Mining
						</div>
						<div class="pool-middle">
						 Lifetime
						</div>
						<div class="pool-bottom">
						 Membership
						</div>
						<div class="pool-price">
							$99
						</div>
						<div class="pool-button">
							<a class="btn pool-buy-btn skyblue-button" >BUY NOW</a>
						</div>
					</div>
				</div> <!--- END COL -->

				<!--START COL -->
				<div class="pool-info-col" data-aos="fade-left">
					<div class="title-sec">
						<img class="title-img" src="<?php echo G5_THEME_URL; ?>/img/pool_1.png">
					</div>
					<div class="pool-slide">
						<div class="pool-title">
							Bitcoin
						</div>
						<div class="pool-middle">
							Lifetime mining
						</div>
						<div class="pool-bottom">
							3,400 GH/S
						</div>
						<div class="pool-price">
							$1,000
						</div>
						<div class="pool-button">
							<a class="btn pool-buy-btn skyblue-button" >BUY NOW</a>
						</div>
					</div>
				</div> <!--- END COL -->

				<!--START COL -->
				<div class="pool-info-col" data-aos="fade-left">
					<div class="title-sec">
						<img class="title-img" src="<?php echo G5_THEME_URL; ?>/img/pool_2.png">
					</div>
					<div class="pool-slide">
						<div class="pool-title">
							Bitcoin
						</div>
						<div class="pool-middle">
							Lifetime mining
						</div>
						<div class="pool-bottom">
							10,200 GH/S
						</div>
						<div class="pool-price">
							$3,000
						</div>
						<div class="pool-button">
							<a class="btn pool-buy-btn skyblue-button" >BUY NOW</a>
						</div>
					</div>
				</div> <!--- END COL -->

				<!--START COL -->
				<div class="pool-info-col" data-aos="fade-left">
					<div class="title-sec">
						<img class="title-img" src="<?php echo G5_THEME_URL; ?>/img/pool_3.png">
					</div>
					<div class="pool-slide">
						<div class="pool-title">
							Bitcoin
						</div>
						<div class="pool-middle">
							Lifetime mining
						</div>
						<div class="pool-bottom">
							17,000 GH/S <span style="color:red;">+1%</span>
						</div>
						<div class="pool-price">
							$5,000
						</div>
						<div class="pool-button">
							<a class="btn pool-buy-btn skyblue-button" >BUY NOW</a>
						</div>
					</div>
				</div> <!--- END COL -->

				<!--START COL -->
				<div class="pool-info-col" data-aos="fade-left">
					<div class="title-sec">
						<img class="title-img" src="<?php echo G5_THEME_URL; ?>/img/pool_4.png">
					</div>
					<div class="pool-slide">
						<div class="pool-title">
							Bitcoin
						</div>
						<div class="pool-middle">
							Lifetime mining
						</div>
						<div class="pool-bottom">
							40,800 GH/S <span style="color:red;">+2%</span>
						</div>
						<div class="pool-price">
							$12,000
						</div>
						<div class="pool-button">
							<a class="btn pool-buy-btn skyblue-button" >BUY NOW</a>
						</div>
					</div>
				</div> <!--- END COL -->

				<!--START COL -->
				<div class="pool-info-col" data-aos="fade-left">
					<div class="title-sec">
						<img class="title-img" src="<?php echo G5_THEME_URL; ?>/img/pool_5.png">
					</div>
					<div class="pool-slide">
						<div class="pool-title">
							Ethereum
						</div>
						<div class="pool-middle">
							Lifetime mining
						</div>
						<div class="pool-bottom">
							80 MH/S
						</div>
						<div class="pool-price">
							$3,000
						</div>
						<div class="pool-button">
							<a class="btn pool-buy-btn skyblue-button" >BUY NOW</a>
						</div>
					</div>
				</div> <!--- END COL -->
			</div><!--- END ROW -->
		</div><!--- END CONTAINER -->
	</section>

<?php
include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
?>
