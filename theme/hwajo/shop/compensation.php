<?php
include_once('./_common.php');

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

define("_SUB_", TRUE);

include_once(G5_THEME_SHOP_PATH.'/shop.head.php');
?>

    <!-- img section -->
    <section class="csection sec01 SecPhra">
		<div class="inner">
			<div class="text_box">
				<h2>Bonus Plan</h2>
				<hr />
				<p>A simple but powerful reward plan for everybody</p>
			</div>
		</div>
	</section><!-- img section -->

	<section class="csection sec02 SecPhra">
		<div class="inner">
			<h2>PINNACLE MINING
        <br>
				ELEVATED MEMBER REWARDS PROGRAM
      </h2>
			<h6>YOUR OPPORTUNITY TO SECURE DIGITAL ASSETS AND CREATE WEALTH</h6>
			<hr />
			<p>
				<strong>Welcome to the Pinnacle Mining Rewards Program!</strong>
        <br>
				We are excited to have you mining with us, and we look forward to helping you secure your financial future.
        Our platform is designed to multiply your cryptocurrency earnings with ease and is powered by a transparent earning
				structure to drive success. Pinnacle Mining has quickly become one of the largest crowdfunded Bitcoin mining pools
				in the world, and our facilities boast only the latest in mining technology.
        <br>
				<br>
				By combining this expansion with our carefully engineered compensation model, you have an opportunity to leverage
				your earning potential like never before. Sharing our vision with others has compounding benefits
				and can be a powerful supplement to your earnings.
        <br>
        <br>
				You can participate at a level that’s comfortable for you, all while creating passive and exponential income.
			</p>
		</div>
	</section>

	<section class="csection sec03 SecPhra">
		<div class="inner">

			<!-- mining -->
			<div class="table-section">
				<div class="title">
					<h2>Mining Pool</h2>
				</div>
				<img src="<?php echo G5_THEME_URL; ?>/img/sub/table_title_bg_img.png" alt="" / class="title_img">

				<table class="miningPool">
					<tr>
						<th>&nbsp;</th>
						<th>Member<br class="min610">ship</th>
						<th>Price</th>
						<th>Bonus Hash</th>
						<th>Mining Days</th>
						<th>Daily Mining</th>
						<th>Re-purchase</th>
					</tr>
					<tr >
						<td>Pool 1</td>
						<td>$99</td>
						<td>$1,000</td>
						<td>-</td>
						<td>Lifetime</td>
						<td>After 10 days <br>
							from sign up</td>
						<td>50%</td>
					</tr>
					<tr class="tr2n">
						<td>Pool 2</td>
						<td>$99</td>
						<td>$3,000</td>
						<td>-</td>
						<td>Lifetime</td>
						<td>After 10 days <br>
							from sign up</td>
						<td>50%</td>
					</tr>
					<tr >
						<td>Pool 3</td>
						<td>$99</td>
						<td>$5,000</td>
						<td>1%</td>
						<td>Lifetime</td>
						<td>After 10 days <br>
							from sign up</td>
						<td>40%</td>
					</tr>
					<tr class="tr2n">
						<td>Pool 4</td>
						<td>$99</td>
						<td>$12,000</td>
						<td>2%</td>
						<td>Lifetime</td>
						<td>After 10 days <br>
							from sign up</td>
						<td>30%</td>
					</tr>
					<tr>
						<td>GPU Pool</td>
						<td>$99</td>
						<td>$3,000</td>
						<td>-</td>
						<td>Lifetime</td>
						<td>After 10 days <br>
							from sign up</td>
						<td>30%</td>
					</tr>

				</table>
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
	<section class="csection sec03 SecPhra">
		<div class="inner">
			<!-- ranking -->
			<div class="table-section">
				<div class="title">
					<h2>Ranking system</h2>
				</div>
				<img src="<?php echo G5_THEME_URL; ?>/img/sub/table_title_bg_img.png" alt="" / class="title_img">

				<table class="rankingSystem">
					<tr>
						<th class="rank">Rank</th>
						<th class="Sponser">Sponsor</th>
						<th class="Total">Total Volume</th>
						<th class="Duration">Duration</th>
						<th class="Qualification">Qualification</th>
					</tr>
					<tr >
						<td class="rank">No rank</td>
						<td class="Sponser">-</td>
						<td class="Total">-</td>
						<td class="Duration">-</td>
						<td class="Qualification">
							Active Member with $99 membership
						</td>
					</tr>
					<tr class="tr2n">
						<td class="rank">0 star</td>
						<td class="Sponser">0</td>
						<td class="Total">-</td>
						<td class="Duration">-</td>
						<td class="Qualification">
							Active Member with $99 membership AND purchase hash power in any mining pool
						</td>
					</tr>
					<tr>
						<td class="rank">1 star</td>
						<td class="Sponser">2</td>
						<td class="Total">$3,000</td>
						<td class="Duration">Lifetime</td>
						<td class="Qualification">
							Sponsor two <span>0 star</span> members AND have $3,000 in total enrollment tree volume.
						</td>
					</tr>
					<tr class="tr2n">
						<td class="rank">2 star</td>
						<td class="Sponser">3</td>
						<td class="Total">$15,000</td>
						<td class="Duration">Lifetime</td>
						<td class="Qualification">
							Three (separate leg) <span>1 Star</span> members in your enrollment tree
							AND $15,000 in total enrollment tree volume.
						</td>
					</tr>
					<tr>
						<td class="rank">3 star</td>
						<td class="Sponser">4</td>
						<td class="Total">$75,000</td>
						<td class="Duration">Monthly</td>
						<td class="Qualification">
							Three (separate leg) <span>2 Star</span> members in your enrollment tree
              AND $75,000 in total enrollment tree volume.
						</td>
					</tr>
					<tr class="tr2n">
						<td class="rank">4 star</td>
						<td class="Sponser">5</td>
						<td class="Total">$350,000</td>
						<td class="Duration">Monthly</td>
						<td class="Qualification">
							Three (separate leg) <span>3 Star</span> members in your enrollment tree
              AND $350,000 in total enrollment tree volume.
						</td>
					</tr>
					<tr>
						<td class="rank">5 star</td>
						<td class="Sponser">6</td>
						<td class="Total">$3,500,000</td>
						<td class="Duration">Monthly</td>
						<td class="Qualification">
							Four (separate leg) <span>4 Star</span> members in your enrollment tree
							AND $3,500,000 in total enrollment tree volume.
						</td>
					</tr>
					<tr class="tr2n">
						<td class="rank">6 star</td>
						<td class="Sponser">8</td>
						<td class="Total">$15,000,000</td>
						<td class="Duration">Monthly</td>
						<td class="Qualification">
							Four (separate leg) <span>5 Star</span> members in your enrollment tree
							AND $15,000,000 in total enrollment tree volume.
						</td>
					</tr>


				</table>
			</div>
		</div>
	</section>

	<section class="csection sec04 ">
		<div class="inner">
			<h3><span>Compensation Plan : </span> Mining Pool Payouts + Rewards Program</h3>
			<p class="phra">Combination of daily mining payouts and referral bonuses</p>

			<div class="img_box after">
				<div class="img_b flot-left">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/s4_img_dollar.png" alt="" />
					<div class="text_phra">
						<p class="title">Mining Pool Payouts</p>
						<p class="con">Get fresh coins from world class mining farms every day.</p>
					</div>
				</div>
				<div class="plus flot-left">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/img_plus.png" alt="" />
				</div>
				<div class="img_b flot-right">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/s4_img_rewards.png" alt="" />
					<div class="text_phra">
						<p class="title">Rewards Program</p>
						<p class="con">Bonus income through member referral</p>
					</div>
				</div>
			</div><!-- img_box -->

			<div class="table_box SecPhra">
				<h2>Mining Pools</h2>

				<div class="content_box">
					<table>
						<tr>
							<th class="first">Pool 1 - $1,000</th>
							<th class="second">Pool 2 - $3,000</th>
							<th class="third">Pool 3 - $5,000</th>
							<th class="forth">Pool 4 - $12,000</th>
							<th class="fifth">GPU - $3,000</th>
						</tr>
						<tr class="tdn">
							<td class="first">3,400 GH/S</td>
							<td class="second">10,200 GH/S</td>
							<td class="third">17,000 GH/S +1%</td>
							<td class="forth">40,800 GH/S +2%</td>
							<td class="fifth">80 MH/S</td>
						</tr>
						<tr>
							<td class="first">50% payout</td>
							<td class="second">50% payout</td>
							<td class="third">60% payout</td>
							<td class="forth">70% payout</td>
							<td class="fifth">70% payout</td>
						</tr>
						<tr class="tdn">
							<td class="first">50% re-purchase</td>
							<td class="second">50% re-purchase</td>
							<td class="third">40% re-purchase</td>
							<td class="forth">30% re-purchase</td>
							<td class="fifth">30% re-purchase</td>
						</tr>
					</table>
					<div class="phra_box after">
						<img src="<?php echo G5_THEME_URL; ?>/img/sub/s5_icon_mining.png" alt="" / class="flot-left">
						<div class="phra_text flot-left">
							Lifetime mining with daily payouts <br>
							Automatic re-purchases for more hash power <br>
							Re-purchase rate adjustable up to 100%
						</div>
						<img src="<?php echo G5_THEME_URL; ?>/img/sub/s5_icon_profits.png" alt="" / class="flot-right">
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="csection sec05 SecPhra">
		<div class="inner">
			<h2>Daily Pool Payouts</h2>

			<div class="top_box after">
				<div class="text_box">
					As a leader in the cryptocurrency mining industry, Pinnacle Mining provides convenient and affordable options for experienced miners <br>
					and excited newcomers alike. Our entire mining catalog supports lifetime contracts and is committed to consistent technology <br>
					upgrades to ensure maximum efficiency. <br>
					<br>
					When mining with us, there’s no need to deal with loud fans, maintain expensive equipment, or manage high electricity costs. <br>
					Our experts handle all of the logistical overhead for you and make sure you’re up and running on day one.  <br>
					<br>
					After you purchase hash power from any of our mining pools, you'll begin earning daily payouts <br class="min767">
					to your wallet (larger pools will receive an additional mining bonus). In order to keep <br class="min767">
					your earnings consistent with mining difficulty, our unique growth model automatically <br class="min767">
					uses a small percentage of your profits to pay for mining costs and reinvest in more <br class="min767">
					hardware equipment. The rate for these repurchases can be adjusted in your <br class="min767">
					back office (we recommend a 100% repurchase rate for beginners).
				</div>
				<div class="img_box">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/s5_img_payouts.png" alt="" />
				</div>
			</div>

			<div class="bottom_box">
				<h2><span>Rewards</span> Program</h2>

				<div class="RP_box after">
					<div class="text_box flot-left">
						<div class="kind">
							<p class="title">1. Sponsor Bonus</p>
							<p class="con">
								You'll earn 8% commission from all sales in every pool. There is no limit to how many pools you can sell and no
                cap on the amount of income you can earn.
							</p>
						</div>
						<div class="kind">
							<p class="title">2. Sponsor Matching Bonus</p>
							<p class="con">
								You can earn up to 10% of the total sales volume of your 10 level enrollment tree. The more you help them
                succeed, the more you earn.
							</p>
						</div>
					</div>
					<div class="img_box flot-right">
						<img src="<?php echo G5_THEME_URL; ?>/img/sub/s5_img01.png" alt="" />
					</div>
				</div><!-- RP_box -->

				<div class="RP_box after middle">

					<div class="img_box flot-left">
						<img src="<?php echo G5_THEME_URL; ?>/img/sub/s5_img02.png" alt="" />
					</div>
					<div class="text_box flot-right">
						<div class="kind">
							<p class="title">3. Binary Bonus</p>
							<p class="con">
								This binary pays up to 15 cycles each day depending on your current rank in the system. To earn a cycle,
                you'll need to have one directly sponsored person on your left and right, each with a sales volume of at least $3,000.
							</p>
						</div>
						<div class="kind">
							<p class="title">4. Binary Matching Bonus</p>
							<p class="con">
								We have a 10 level binary match that pays an extra $120 for each $240 binary cycle for a maximum of 10
                levels of your enrollment tree.
							</p>
						</div>
					</div>
				</div><!-- RP_box -->

				<div class="RP_box after last">
					<div class="text_box flot-left">
						<div class="kind">
							<p class="title">5. Infinity Bonus</p>
							<p class="con">
								You will earn residual commissions on all partials and re-purchases made from
                everyone in your enrollment tree.
							</p>
						</div>
					</div>
					<div class="img_box flot-right">
						<img src="<?php echo G5_THEME_URL; ?>/img/sub/s5_img03.png" alt="" />
					</div>
				</div><!-- RP_box -->
			</div>
		</div>
	</section>

	<section class="csection sec06 reward">
		<div class="inner">
			<div class="program pro01">
				<h2>Rewards Program 01 <span> : Sponsor Bonus</span></h2>
				<p class="phra_text">You can begin enrolling new members once you are an active member and purchase a pool.
					You'll earn an 8% sponsor bonus from every pool your directly referred members purchase. <br>
					<span>Sponsor bonuses from pools you have not purchased will be withheld until you purchase that pool.</span>
				</p>
				<img src="<?php echo G5_THEME_URL; ?>/img/sub/s6_img_bonus.png" alt="" />
			</div>

			<div class="program">
					<h2>Rewards Program 02 <span> : Sponsor Matching Bonus</span></h2>
					<p class="phra_text">Sponsor matching pays an extra 10% for a maximum of 10 levels (generations)
						of your enrollment tree depending on your rank.
					</p>

					<div class="star_box after">
						<div class="star">
							<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank01.png" alt="" />
							<p class="level">Level 1</p>
							<p class="bonus">Bonus 2%</p>
						</div>

						<div class="star second">
							<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank02.png" alt="" />
							<p class="level">Level 2-3</p>
							<p class="bonus">Bonus 1%</p>
						</div>

						<div class="star third">
							<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank03.png" alt="" />
							<p class="level">Level 4-5</p>
							<p class="bonus">Bonus 1%</p>
						</div>

						<div class="star">
							<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank04.png" alt="" />
							<p class="level">Level 6-7</p>
							<p class="bonus">Bonus 1%</p>
						</div>

						<div class="star ">
							<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank05.png" alt="" />
							<p class="level">Level 8</p>
							<p class="bonus">Bonus 1%</p>
						</div>

						<div class="star second">
							<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank05.png" alt="" />
							<p class="level">Level 9</p>
							<p class="bonus">Bonus 0.5%</p>
						</div>

						<div class="star third six">
							<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank06.png" alt="" />
							<p class="level">Level 10</p>
							<p class="bonus">Bonus 0.5%</p>
						</div>

						<div class="star total six">
							<p class="level">Total</p>
							<p class="bonus">10%</p>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="csection sec07 reward">
		<div class="inner">
				<h2>Rewards Program 03 <span> : Binary Bonus</span></h2>
				<p class="phra_text">To qualify, place one directly sponsored person on your left and one on your right,
          each with a sales volume of at least $3,000.</p>

        <div class="credits_box">
					<div class="cycle">
						<span>$3000 sales volume on your left + $3000 sales volume on your right</span>
            <span> | </span>
						<span>1 cycle = $240</span>
					</div>
				</div><!-- credits_box -->
			</div>
	</section>

	<section class="csection sec08 after">
		<div class="after">
			<div class="area flot-left"></div>
			<div class="area flot-right"></div>
		</div>
		<div class="img_box"><img src="<?php echo G5_THEME_URL; ?>/img/sub/s6_img_bonus2.png" alt="" /></div>
	</section>

	<section class="csection sec09 reward">
		<div class="inner">
			<h2>Rewards Program 04 <span> : Binary Matching Bonus</span></h2>
			<p class="phra_text">Another $120 is paid as a matching bonus to the upline through 10 levels of sponsorship.</p>

			<div class="star_box after">
				<div class="star">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank01.png" alt="" />
					<p class="level">Level 1</p>
					<p class="bonus">Bonus $12</p>
				</div>

				<div class="star second">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank02.png" alt="" />
					<p class="level">Level 2-3</p>
					<p class="bonus">Bonus $12</p>
				</div>

				<div class="star third">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank03.png" alt="" />
					<p class="level">Level 4-5</p>
					<p class="bonus">Bonus $12</p>
				</div>

				<div class="star">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank04.png" alt="" />
					<p class="level">Level 6-7</p>
					<p class="bonus">Bonus $12</p>
				</div>

				<div class="star first">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank05.png" alt="" />
					<p class="level">Level 8-9</p>
					<p class="bonus">Bonus $12</p>
				</div>

				<div class="star">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank06.png" alt="" />
					<p class="level">Level 10</p>
					<p class="bonus">Bonus $12</p>
				</div>

				<div class="star total last back">
					<p class="level">Total</p>
					<p class="bonus">$120</p>
				</div>

			</div>
		</div>
	</section>

	<section class="csection sec10 reward">
		<div class="inner">
			<h2>Rewards Program 05 <span> : Infinity Bonus for Re-purchases</span></h2>
			<p class="phra_text">You will earn commissions on all partials and re-purchases made from everyone in your enrollment tree.
			This includes all automatic re-purchases and all other partial re-purchases.
			</p>

			<div class="star_box after">
				<div class="star">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank01.png" alt="" />
					<p class="level">Level 1</p>
					<p class="bonus">Bonus 2%</p>
				</div>

				<div class="star second">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank02.png" alt="" />
					<p class="level">Level 2-3</p>
					<p class="bonus">Bonus 1%</p>
				</div>

				<div class="star third">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank03.png" alt="" />
					<p class="level">Level 4-5</p>
					<p class="bonus">Bonus 1%</p>
				</div>

				<div class="star">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank04.png" alt="" />
					<p class="level">Level 6-7</p>
					<p class="bonus">Bonus 1%</p>
				</div>

				<div class="star ">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank05.png" alt="" />
					<p class="level">Level 8</p>
					<p class="bonus">Bonus 1%</p>
				</div>

				<div class="star second">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank05.png" alt="" />
					<p class="level">Level 9</p>
					<p class="bonus">Bonus 0.5%</p>
				</div>

				<div class="star third six">
					<img src="<?php echo G5_THEME_URL; ?>/img/sub/icon_lank06.png" alt="" />
					<p class="level">Level 10</p>
					<p class="bonus">Bonus 0.5%</p>
				</div>

				<div class="star total back six">
					<p class="level">Total</p>
					<p class="bonus">18%</p>
				</div>

			</div>
		</div>

	</section>


<?php
include_once(G5_THEME_SHOP_PATH.'/shop.tail.php');
?>
