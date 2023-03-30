<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
?>

<?php if($config['cf_kakao_js_apikey']) { ?>
<script src="https://developers.kakao.com/sdk/js/kakao.min.js"></script>
<script src="<?php echo G5_JS_URL; ?>/kakaolink.js"></script>
<script>
    // 사용할 앱의 Javascript 키를 설정해 주세요.
    Kakao.init("<?php echo $config['cf_kakao_js_apikey']; ?>");
</script>
<?php } ?>

<form name="fitem" action="<?php echo $action_url; ?>" method="post" onsubmit="return fitem_submit(this);">
<input type="hidden" name="it_id[]" value="<?php echo $it['it_id']; ?>">
<input type="hidden" name="sw_direct">
<input type="hidden" name="url">

<strong id="sit_title"><?php echo stripslashes($it['it_name']); ?></strong>
<style type="text/css">
#sit_pvi {border:solid 1px #ddd;}
</style>
<div id="sit_ov_wrap">
    <?php
    // 이미지(중) 썸네일
    $thumb_img = '';
    $thumb_img_w = 280; // 넓이
    $thumb_img_h = 280; // 높이
    for ($i=1; $i<=10; $i++)
    {
        if(!$it['it_img'.$i])
            continue;

        $thumb = get_it_thumbnail($it['it_img'.$i], $thumb_img_w, $thumb_img_h);

        if(!$thumb)
            continue;

        $thumb_img .= '<li>';
        $thumb_img .= '<a href="'.G5_SHOP_URL.'/largeimage.php?it_id='.$it['it_id'].'&amp;no='.$i.'" class="popup_item_image slide_img" target="_blank">'.$thumb.'</a>';
        $thumb_img .= '</li>'.PHP_EOL;
    }
    if ($thumb_img)
    {
        echo '<div id="sit_pvi">'.PHP_EOL;
        echo '<button type="button" id="sit_pvi_prev" class="sit_pvi_btn" >이전</button>'.PHP_EOL;
        echo '<button type="button" id="sit_pvi_next" class="sit_pvi_btn">다음</button>'.PHP_EOL;
        echo '<ul id="sit_pvi_slide" style="width:'.$thumb_img_w.'px;height:'.$thumb_img_h.'px">'.PHP_EOL;
        echo $thumb_img;
        echo '</ul>'.PHP_EOL;
        echo '</div>';
    }
    ?>

 	<div class="item_info">
	<!--## item_info ######-->
	<!--## item_info ######-->
	<!--## item_info ######-->

<style type="text/css">
.item_info .tags {height:22px;line-height:22px;font-family:"nngdb";font-size:12px;}
.item_info .tags span.th {display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;padding-left:10px;width:80px;}
.item_info .prc_tags {height:33px;line-height:33px;border-bottom:dashed 1px #ddd;margin-bottom:5px;font-size:16px;color:#fe2828;}
.item_info .prc_tags span.th {font-size:13px;color:#000;}
.item_info .prc_tags span.won {font-size:11px;}
</style>

        <?php if (!$it['it_use']) { // 판매가능이 아닐 경우 ?>
		<div class="tags">
			<span class="th">판매가</span>
			판매중지
		</div><!-- // tags -->
        <?php } else if ($it['it_tel_inq']) { // 전화문의일 경우 ?>
		<div class="tags">
			<span class="th">판매가</span>
			전화문의
		</div><!-- // tags -->
        <?php } else { // 전화문의가 아닐 경우?>
        <?php if ($it['it_cust_price']) { ?>
		<!-- // <div class="tags">
			<span class="th">시중가격</span>
			<?php echo display_price($it['it_cust_price']); ?>
		</div> // --><!-- // tags -->
        <?php } // 시중가격 끝 ?>
		<div class="tags prc_tags">
			<span class="th">판매가</span>
                <?=str_replace("원","<span class='won'>원</span>",display_price(get_price($it)))?>
                <input type="hidden" id="it_price" value="<?php echo get_price($it); ?>">
		</div><!-- // tags -->
        <?php } ?>


        <?php if ($config['cf_use_point']=="pk") { // 포인트 사용한다면 ?>
		<div class="tags">
			<span class="th">포인트</span>
			<?php
			if($it['it_point_type'] == 2) {
				echo '구매금액(추가옵션 제외)의 '.$it['it_point'].'%';
			} else {
				$it_point = get_item_point($it);
				echo number_format($it_point).'점';
			}
			?>
		</div><!-- // tags -->
        <?php } ?>

        <?php if ($it['it_maker']=="pk") { ?>
        <tr>
            <th scope="row">제조사</th>
            <td><?php echo $it['it_maker']; ?></td>
        </tr>
        <?php } ?>

        <?php if ($it['it_origin']=="pk") { ?>
        <tr>
            <th scope="row">원산지</th>
            <td><?php echo $it['it_origin']; ?></td>
        </tr>
        <?php } ?>

        <?php if ($it['it_brand']) { ?>
		<div class="tags">
			<span class="th">브랜드</span>
			<?=$it['it_brand']?>
		</div><!-- // tags -->
        <?php } ?>

        <?php if ($it['it_model']) { ?>
		<div class="tags">
			<span class="th">단위</span>
			<?php echo $it['it_model']; ?>
		</div><!-- // tags -->
        <?php } ?>

        <?php
        $ct_send_cost_label = '배송비';

        if($it['it_sc_type'] == 1)
            $sc_method = '무료배송';
        else {
            if($it['it_sc_method'] == 1)
                $sc_method = '수령후 지불';
            else if($it['it_sc_method'] == 2) {
                $ct_send_cost_label = '<label for="ct_send_cost">배송비결제</label>';
                $sc_method = '<select name="ct_send_cost" id="ct_send_cost">
                                  <option value="0">주문시 결제</option>
                                  <option value="1">수령후 지불</option>
                              </select>';
            }
            else
                $sc_method = '주문시 결제';
        }
        ?>
		<div class="tags">
			<span class="th"><?php echo $ct_send_cost_label; ?></span>
			<?php echo $sc_method; ?>
		</div><!-- // tags -->

		<p class="blk" style="height:10px;"></p>

        <?php if($is_orderable) { ?>
        <p id="sit_opt_info">
            상품 선택옵션 <?php echo $option_count; ?> 개, 추가옵션 <?php echo $supply_count; ?> 개
        </p>
        <?php } ?>
        <div id="sit_star_sns">
            <?php if ($star_score) { ?>
            고객평점 <span>별<?php echo $star_score?>개</span>
            <img src="<?php echo G5_SHOP_URL; ?>/img/s_star<?php echo $star_score?>.png" alt="" class="sit_star">
            <?php } ?>
            <?php echo $sns_share_links; ?>
        </div>


        <?php
        /* 재고 표시하는 경우 주석 해제
        <tr>
            <th scope="row">재고수량</th>
            <td><?php echo number_format(get_it_stock_qty($it_id)); ?> 개</td>
        </tr>
        */
        ?>



        <?php if($it['it_buy_min_qty']) { ?>
        <tr>
            <th>최소구매수량</th>
            <td><?php echo number_format($it['it_buy_min_qty']); ?> 개</td>
        </tr>
        <?php } ?>
        <?php if($it['it_buy_max_qty']) { ?>
        <tr>
            <th>최대구매수량</th>
            <td><?php echo number_format($it['it_buy_max_qty']); ?> 개</td>
        </tr>
        <?php } ?>
        </tbody>
        </table>

        <?php
        if($option_item) {
        ?>
        <!-- 선택옵션 시작 { -->
        <section>
            <h3>선택옵션</h3>
            <table class="sit_ov_tbl">
            <colgroup>
                <col class="grid_3">
                <col>
            </colgroup>
            <tbody>
            <?php // 선택옵션
            echo $option_item;
            ?>
            </tbody>
            </table>
        </section>
        <!-- } 선택옵션 끝 -->
        <?php
        }
        ?>

        <?php
        if($supply_item) {
        ?>
        <!-- 추가옵션 시작 { -->
        <section>
            <h3>추가옵션</h3>
            <table class="sit_ov_tbl">
            <colgroup>
                <col class="grid_3">
                <col>
            </colgroup>
            <tbody>
            <?php // 추가옵션
            echo $supply_item;
            ?>
            </tbody>
            </table>
        </section>
        <!-- } 추가옵션 끝 -->
        <?php
        }
        ?>
<style type="text/css">
#sit_sel_option {}
#sit_opt_added {}
#sit_opt_added li {padding:10px;font-size:12px;color:#333;background-color:#f5f5f5;margin-bottom:10px;font-family:"nngdb";line-height:20px;}
#sit_opt_added li div {}
#sit_opt_added li div input[type="text"]{margin-left:2px;border:solid 1px rgba(0,0,0,0.1);height:22px;line-height:22px;text-align:center;background-color:#fff;vertical-align:10px;}
#sit_opt_added button.btn_frmline {display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;width:22px;height:22px;line-height:22px;text-indent:-9999px;border-radius:3px;}
#sit_opt_added button.sit_qty_minus {background:url(/adm/img/w_minus.png) no-repeat center;background-color:#555;margin-right:-1px;}
#sit_opt_added button.sit_qty_plus {background:url(/adm/img/w_plus.png) no-repeat center;background-color:#555;}
#sit_tot_price {border-top:dashed 1px #ddd;padding:10px;text-align:left;font-family:"nngdb";color:#000;font-size:16px;}
</style>
		<?php if ($is_orderable) { ?>
		<!-- 선택된 옵션 시작 { -->
		<div id="sit_sel_option">
			<h3>선택된 옵션</h3>
			<?php
			if(!$option_item) {
				if(!$it['it_buy_min_qty'])
					$it['it_buy_min_qty'] = 1;
			?>
			<ul id="sit_opt_added">
				<li class="sit_opt_list">
					<input type="hidden" name="io_type[<?php echo $it_id; ?>][]" value="0">
					<input type="hidden" name="io_id[<?php echo $it_id; ?>][]" value="">
					<input type="hidden" name="io_value[<?php echo $it_id; ?>][]" value="<?php echo $it['it_name']; ?>">
					<input type="hidden" class="io_price" value="0">
					<input type="hidden" class="io_stock" value="<?php echo $it['it_stock_qty']; ?>">
					<span class="sit_opt_subj"><?php echo $it['it_name']; ?></span>
					<span class="sit_opt_prc">(+0원)</span>

					<div>
						<button type="button" class="sit_qty_minus btn_frmline">감소</button>
						<input type="text" name="ct_qty[<?php echo $it_id; ?>][]" value="<?php echo $it['it_buy_min_qty']; ?>" id="ct_qty_<?php echo $i; ?>" size="5">
						<button type="button" class="sit_qty_plus btn_frmline">증가</button>
					</div>
				</li>
			</ul>
			<script>
			$(function() {
				price_calculate();
			});
			</script>
			<?php } ?>
		</div>
		<!-- } 선택된 옵션 끝 -->

		<!-- 총 구매액 -->
		<div id="sit_tot_price"></div>
		<?php } ?>

        <?php if($is_soldout) { ?>
        <p id="sit_ov_soldout">상품의 재고가 부족하여 구매할 수 없습니다.</p>
        <?php } ?>
<style type="text/css">
#pk_shop_button {}
#pk_shop_button > ul > li {float:left;width:33%;margin-right:0.5%;}
#pk_shop_button > ul > li:nth-child(3n+0) {margin-right:0;}
#pk_shop_button > ul > li input,
#pk_shop_button > ul > li a {display:block;width:100%;height:50px;line-height:50px;text-align:center;cursor:pointer;border:none;font-size:16px;font-family:"nngdb";}
/* new button design */
#sit_btn_buy {color:#fff;background-color:#da3030;}
#sit_btn_cart {color:#fe6c31;background-color:#fff;box-shadow:inset 0 0 0 2px #fe6c31;}
#sit_btn_wish {color:#484848;background-color:#fff;box-shadow:inset 0 0 0 2px #a4a4a4;}
/*
#sit_ov_btn {text-align:right;letter-spacing:-3px}
#sit_ov_btn a {display:inline-block;width:80px;height:30px;border:0;font-size:0.95em;vertical-align:middle;text-align:center;text-decoration:none;letter-spacing:-0.1em;line-height:2.8em;cursor:pointer}
#sit_ov_btn input {display:inline-block;width:80px;height:30px;border:0;font-size:0.95em;text-align:center;text-decoration:none;letter-spacing:-0.1em;cursor:pointer}
#sit_btn_buy {background:#ff5b89;color:#fff}
#sit_btn_cart, #sit_btn_wish {background:#555;color:#fff}
#sit_btn_rec {background:#888;color:#fff}
*/
</style>

        <div id="pk_shop_button">
			<ul>
		        <?php if ($is_orderable) { ?>
				<li><input type="submit" onclick="document.pressed=this.value;" value="바로구매" id="sit_btn_buy"></li>
				<li><input type="submit" onclick="document.pressed=this.value;" value="장바구니" id="sit_btn_cart"></li>
			    <?php } ?>
				<?php if(!$is_orderable && $it['it_soldout'] && $it['it_stock_sms']) { ?>
				<a href="javascript:popup_stocksms('<?php echo $it['it_id']; ?>');" id="sit_btn_buy">재입고알림</a>
				<?php } ?>
	            <li><a href="javascript:item_wish(document.fitem, '<?php echo $it['it_id']; ?>');" id="sit_btn_wish">위시리스트</a></li>
	            <!-- // <a href="javascript:popup_item_recommend('<?php echo $it['it_id']; ?>');" id="sit_btn_rec">추천하기</a> // -->
			</ul>
			<p class="clr"></p>
        </div><!-- // pk_shop_button // -->

        <script>
        // 상품보관
        function item_wish(f, it_id)
        {
            f.url.value = "<?php echo G5_SHOP_URL; ?>/wishupdate.php?it_id="+it_id;
            f.action = "<?php echo G5_SHOP_URL; ?>/wishupdate.php";
            f.submit();
        }

        // 추천메일
        function popup_item_recommend(it_id)
        {
            if (!g5_is_member)
            {
                if (confirm("회원만 추천하실 수 있습니다."))
                    document.location.href = "<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo urlencode(G5_SHOP_URL."/item.php?it_id=$it_id"); ?>";
            }
            else
            {
                url = "./itemrecommend.php?it_id=" + it_id;
                opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
                popup_window(url, "itemrecommend", opt);
            }
        }

        // 재입고SMS 알림
        function popup_stocksms(it_id)
        {
            url = "<?php echo G5_SHOP_URL; ?>/itemstocksms.php?it_id=" + it_id;
            opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
            popup_window(url, "itemstocksms", opt);
        }
        </script>
	<!--end.## item_info ##-->
	<!--end.## item_info ##-->
	<!--end.## item_info ##-->
	</div><!-- // item_info -->

	<p class="clr"></p>
</div><!-- // itemform_Wrp -->

<p class="blk" style="height:40px;"></p>

<!-- // <div id="sit_sns">
    <?php echo get_sns_share_link('facebook', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/sns_fb.png'); ?>
                <?php echo get_sns_share_link('twitter', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/sns_twt.png'); ?>
                <?php echo get_sns_share_link('googleplus', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/sns_goo.png'); ?>
                <?php echo get_sns_share_link('kakaotalk', $sns_url, $sns_title, G5_MSHOP_SKIN_URL.'/img/sns_kakao.png'); ?>
                <a href="javascript:popup_item_recommend('<?php echo $it['it_id']; ?>');" id="sit_btn_rec">추천하기</a>
    <?php
    $href = G5_SHOP_URL.'/iteminfo.php?it_id='.$it_id;
    ?>
</div> // -->

<!-- // <aside id="sit_siblings">
    <h2>다른 상품 보기</h2>
    <?php
    if ($prev_href || $next_href) {
        echo $prev_href.$prev_title.$prev_href2;
        echo $next_href.$next_title.$next_href2;
    } else {
        echo '<span class="sound_only">이 분류에 등록된 다른 상품이 없습니다.</span>';
    }
    ?>
</aside> // -->

    <ul id="sit_more">
        <li><a href="<?php echo $href; ?>" target="_blank">상세보기</a></li>
        <?php if ($default['de_baesong_content']) { ?><li><a href="<?php echo $href; ?>&amp;info=dvr" target="_blank">배송정보</a></li><?php } ?>

        <li><a href="<?php echo $href; ?>&amp;info=use" target="_blank">사용후기<span class="item_use_count"><?php echo $item_use_count; ?></span></a></li>
        <li><a href="<?php echo $href; ?>&amp;info=qa" target="_blank">상품문의<span class="item_qa_count"><?php echo $item_qa_count; ?></span></a></li>


    </ul>

</form>

<?php if($default['de_mobile_rel_list_use']) { ?>
<!-- 관련상품 시작 { -->
<section id="sit_rel">
    <h2>WITH ITEM</h2>
    <div class="sct_wrap">
        <?php
        $rel_skin_file = $skin_dir.'/'.$default['de_mobile_rel_list_skin'];
        if(!is_file($rel_skin_file))
            $rel_skin_file = G5_MSHOP_SKIN_PATH.'/'.$default['de_mobile_rel_list_skin'];

        $sql = " select b.* from {$g5['g5_shop_item_relation_table']} a left join {$g5['g5_shop_item_table']} b on (a.it_id2=b.it_id) where a.it_id = '{$it['it_id']}' and b.it_use='1' ";
        $list = new item_list($rel_skin_file, $default['de_mobile_rel_list_mod'], 0, $default['de_mobile_rel_img_width'], $default['de_mobile_rel_img_height']);
        $list->set_query($sql);
        echo $list->run();
        ?>
    </div>
</section>
<!-- } 관련상품 끝 -->
<?php } ?>


<script>
$(window).bind("pageshow", function(event) {
    if (event.originalEvent.persisted) {
        document.location.reload();
    }
});

$(function(){
    // 상품이미지 슬라이드
    var time = 500;
    var idx = idx2 = 0;
    var slide_width = $("#sit_pvi_slide").width();
    var slide_count = $("#sit_pvi_slide li").size();
    $("#sit_pvi_slide li:first").css("display", "block");
    if(slide_count > 1)
        $(".sit_pvi_btn").css("display", "inline");

    $("#sit_pvi_prev").click(function() {
        if(slide_count > 1) {
            idx2 = (idx - 1) % slide_count;
            if(idx2 < 0)
                idx2 = slide_count - 1;
            $("#sit_pvi_slide li:hidden").css("left", "-"+slide_width+"px");
            $("#sit_pvi_slide li:eq("+idx+")").filter(":not(:animated)").animate({ left: "+="+slide_width+"px" }, time, function() {
                $(this).css("display", "none").css("left", "-"+slide_width+"px");
            });
            $("#sit_pvi_slide li:eq("+idx2+")").css("display", "block").filter(":not(:animated)").animate({ left: "+="+slide_width+"px" }, time,
                function() {
                    idx = idx2;
                }
            );
        }
    });

    $("#sit_pvi_next").click(function() {
        if(slide_count > 1) {
            idx2 = (idx + 1) % slide_count;
            $("#sit_pvi_slide li:hidden").css("left", slide_width+"px");
            $("#sit_pvi_slide li:eq("+idx+")").filter(":not(:animated)").animate({ left: "-="+slide_width+"px" }, time, function() {
                $(this).css("display", "none").css("left", slide_width+"px");
            });
            $("#sit_pvi_slide li:eq("+idx2+")").css("display", "block").filter(":not(:animated)").animate({ left: "-="+slide_width+"px" }, time,
                function() {
                    idx = idx2;
                }
            );
        }
    });

    // 상품이미지 크게보기
    $(".popup_item_image").click(function() {
        var url = $(this).attr("href");
        var top = 10;
        var left = 10;
        var opt = 'scrollbars=yes,top='+top+',left='+left;
        popup_window(url, "largeimage", opt);

        return false;
    });
});

// 상품보관
function item_wish(f, it_id)
{
    f.url.value = "<?php echo G5_SHOP_URL; ?>/wishupdate.php?it_id="+it_id;
    f.action = "<?php echo G5_SHOP_URL; ?>/wishupdate.php";
    f.submit();
}

// 추천메일
function popup_item_recommend(it_id)
{
    if (!g5_is_member)
    {
        if (confirm("회원만 추천하실 수 있습니다."))
            document.location.href = "<?php echo G5_BBS_URL; ?>/login.php?url=<?php echo urlencode(G5_SHOP_URL."/item.php?it_id=$it_id"); ?>";
    }
    else
    {
        url = "<?php echo G5_SHOP_URL; ?>/itemrecommend.php?it_id=" + it_id;
        opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
        popup_window(url, "itemrecommend", opt);
    }
}

// 재입고SMS 알림
function popup_stocksms(it_id)
{
    url = "<?php echo G5_SHOP_URL; ?>/itemstocksms.php?it_id=" + it_id;
    opt = "scrollbars=yes,width=616,height=420,top=10,left=10";
    popup_window(url, "itemstocksms", opt);
}

// 바로구매, 장바구니 폼 전송
function fitem_submit(f)
{
    if (document.pressed == "CART") {
        f.sw_direct.value = 0;
    } else { // 바로구매
        f.sw_direct.value = 1;
    }

    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

    if($(".sit_opt_list").size() < 1) {
        alert("상품의 선택옵션을 선택해 주십시오.");
        return false;
    }

    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt(<?php echo $it['it_buy_min_qty']; ?>);
    var max_qty = parseInt(<?php echo $it['it_buy_max_qty']; ?>);
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
        alert("선택옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}
</script>