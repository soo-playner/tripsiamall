<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_CSS_URL.'/style.css">', 0);
echo $action_url;
?>

<form name="fitem" method="post" action="<?php echo $action_url; ?>" onsubmit="return fitem_submit(this);">
<input type="hidden" name="it_id[]" value="<?php echo $it_id; ?>">
<input type="hidden" name="sw_direct">
<input type="hidden" name="url">
<style type="text/css">
#itemform_Wrp {padding-top:38px;border-top:solid 2px #555;}
.item_form {float:left;width:562px;}
.item_form .bigThumb {width:560px;height:560px;line-height:560px;text-align:center;border:solid 1px #ddd;}
.item_form .item_thumbs {display:none;}

.item_info {float:right;width:600px;}



</style>
<div id="itemform_Wrp">

	<div class="item_form">

		<div class="bigThumb">
			<?php
			$big_img_count = 0;
			$thumbnails = array();
			for($i=1; $i<=10; $i++) {
				if(!$it['it_img'.$i])					continue;

				$img = get_it_thumbnail($it['it_img'.$i], $default['de_mimg_width'], $default['de_mimg_height']);

				if($img) {
					// 썸네일
					$thumb = get_it_thumbnail($it['it_img'.$i], 60, 60);
					$thumbnails[] = $thumb;
					$big_img_count++;

					echo '<a href="'.G5_SHOP_URL.'/largeimage.php?it_id='.$it['it_id'].'&amp;no='.$i.'" target="_blank" class="popup_item_image">'.$img.'</a>';
				}
			}

			if($big_img_count == 0) {
				echo '<img src="'.G5_SHOP_URL.'/img/no_image.gif" alt="">';
			}
			?>
		</div><!-- // bigThumb -->

		<div class="item_thumbs">
			<?php
			// 썸네일
			$thumb1 = true;
			$thumb_count = 0;
			$total_count = count($thumbnails);
			if($total_count > 0) {
				echo '<ul id="sit_pvi_thumb">';
				foreach($thumbnails as $val) {
					$thumb_count++;
					$sit_pvi_last ='';
					if ($thumb_count % 5 == 0) $sit_pvi_last = 'class="li_last"';
						echo '<li '.$sit_pvi_last.'>';
						echo '<a href="'.G5_SHOP_URL.'/largeimage.php?it_id='.$it['it_id'].'&amp;no='.$thumb_count.'" target="_blank" class="popup_item_image img_thumb">'.$val.'<span class="sound_only"> '.$thumb_count.'번째 이미지 새창</span></a>';
						echo '</li>';
				}
				echo '</ul>';
			}
			?>
		</div><!-- // item_thumbs -->

	</div><!-- // item_form -->

	<div class="item_info">
	<!--## item_info ######-->
	<!--## item_info ######-->
	<!--## item_info ######-->

<style type="text/css">
.item_info .item_subject {height:60px;line-height:50px;color:#000;font-size:22px;letter-spacing:-0.05em;font-family:"nngdb";text-shadow:1px 1px 0 rgba(0,0,0,0.2);border-bottom:dashed 1px #ddd;}
.item_info .tags {height:33px;line-height:33px;font-family:"nngdb";font-size:13px;}
.item_info .tags span.th {display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;padding-left:10px;width:100px;}

.item_info .prc_tags {height:55px;line-height:55px;border-bottom:dashed 1px #ddd;margin-bottom:10px;font-size:26px;color:#fe2828;}
.item_info .prc_tags span.th {font-size:16px;color:#000;}
.item_info .prc_tags span.won {font-size:14px;}
</style>

		<div class="item_subject">
			<?php echo stripslashes($it['it_name']); ?>
		</div><!-- // item_subject -->

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

		<p class="blk" style="height:10px;"></p>

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
#sit_opt_added li {padding:20px 30px;font-size:13px;color:#333;background-color:#f5f5f5;margin-bottom:10px;font-family:"nngdb";line-height:30px;}
#sit_opt_added li div {}
#sit_opt_added li div input[type="text"]{margin-left:2px;border:solid 1px rgba(0,0,0,0.1);height:22px;line-height:22px;text-align:center;background-color:#fff;}
#sit_opt_added button.btn_frmline {display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;width:22px;height:22px;line-height:22px;text-indent:-9999px;border-radius:3px;}
#sit_opt_added button.sit_qty_minus {background:url(/adm/img/w_minus.png) no-repeat center;background-color:#555;margin-right:-1px;}
#sit_opt_added button.sit_qty_plus {background:url(/adm/img/w_plus.png) no-repeat center;background-color:#555;}
#sit_tot_price {border-top:dashed 1px #ddd;padding:20px;text-align:right;font-family:"nngdb";color:#000;font-size:22px;}
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
</form>

<!-- ##start## 다음상품 이전상품 ##### -->
<!-- // <div id="sit_siblings">
	<?php
	if ($prev_href || $next_href) {
		echo $prev_href.$prev_title.$prev_href2;
		echo $next_href.$next_title.$next_href2;
	} else {
		echo '<span class="sound_only">이 분류에 등록된 다른 상품이 없습니다.</span>';
	}
	?>
</div> // -->
<!-- ##end## 다음상품 이전상품 ## -->

<script>
$(function(){
    // 상품이미지 첫번째 링크
    $("#sit_pvi_big a:first").addClass("visible");

    // 상품이미지 미리보기 (썸네일에 마우스 오버시)
    $("#sit_pvi .img_thumb").bind("mouseover focus", function(){
        var idx = $("#sit_pvi .img_thumb").index($(this));
        $("#sit_pvi_big a.visible").removeClass("visible");
        $("#sit_pvi_big a:eq("+idx+")").addClass("visible");
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


// 바로구매, 장바구니 폼 전송
function fitem_submit(f)
{
    if (document.pressed == "장바구니") {
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