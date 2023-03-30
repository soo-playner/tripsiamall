<?php
$sub_menu = "700010";
include_once('./_common.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '코인 시세/환율 및 스왑';
include_once('../admin.head.php');

$coin_array = [];
$coin_list_query = sql_query("SELECT * from coins");
$coin_cnt = sql_num_rows($coin_list_query);

while($row=sql_fetch_array($coin_list_query)){
    array_push($coin_array,[$row['no'],$row['id'],$row['name'],$row['krname']]);
    // $coin_list[$i] = $row['id'];
}

if($coin_cnt > 9){
    echo "<style>.checkbox-group{max-width:800px !important;}</style>";
}
?>

<style>
    #wrapper{width:100%;min-width:100%;}
    .frm_input{width:100%;min-width:100px;}
    .frm_input.content{min-height:40px;}
    .local_ov a{color:green;font-weight:600;}
    .local_ov .small{font-size:11px;}
    .local_ov .strong{font-weight:600}
    .local_ov .blue{color:blue}

    .pollding_btn{padding:5px 10px;border-radius:0 !important;}
    

    .main{padding:15px;background: ghostwhite;}
    .empty{line-height:24px;width:600px; padding:30px;text-align: center;border:1px dashed #ccc;}
    .btn_layer{width:100%;margin:20px auto;text-align:center;}
    .btn_layer button{align:center;padding:15px 30px;}

    
    .checkbox{width:160px;}
    .checkbox-tile{min-height:140px;}
    .checkbox-price{margin-top:5px;}
    .checkbox .prices p{margin:0;padding:0;line-height:20px;text-align:center}
    .checkbox .prices .price{font-size:18px;line-height:26px;}
    .checkbox .change_price{font-size:11px;}
    .checkbox .change_price i {font-size:16px;margin-left:-10px;}
    .color-up{color:#c84a31}
    .color-down{color:#1261c4}
    .color-even{color:black}
    .checkbox img{width:45px;margin:5px 5px 10px ;}
    
   

    #coin_name{flex:auto;width:auto;}
    strong{color:red;}

    .modal-body .contents{text-align:center;}
    .modal-body .contents .coin_name{font-weight:bold;font-size:16px;line-height:30px;font-family: "Inter", sans-serif;}
    .modal-footer .btn{padding:10px 20px;}
    .btn-primary{color:white}
    .btn-primary:disabled{
        background-color: -internal-light-dark(rgba(239, 239, 239, 0.3), rgba(19, 1, 1, 0.3)) !important;
        color: -internal-light-dark(rgba(16, 16, 16, 0.3), rgba(255, 255, 255, 0.3)) !important;
        border-color: -internal-light-dark(rgba(118, 118, 118, 0.3), rgba(195, 195, 195, 0.3)) !important;
    }

    #coin_swap{color:black}
    #coin_swap:hover{color:white}

    #SwapCoinModal .line_card{display:flex;height:40px;line-height:40px;display: flex;
    flex-direction: row;
    align-items: left;
    justify-content: left;
    padding: 10px 16px 10px 16px;
    border-radius: 0.5rem;
    border: 2px solid #b5bfd9;
    background-color: #fff;
    box-shadow: 0 5px 10px rgb(0 0 0 / 10%);
    transition: 0.15s ease;
    cursor: pointer;
    position: relative;
    font-family: "Inter", sans-serif;
    color: black;
    font-weight: 600;}
    #SwapCoinModal .line_card .checkbox-icon img {height:100%;width:100%;}
    #SwapCoinModal .prices{text-align: right;flex:auto;font-size:16px;}
    #SwapCoinModal .checkbox-label,#SwapCoinModal .checkbox-labelkr{margin-left:5px;}
    #SwapCoinModal .checkbox-labelkr{font-weight:900;font-family:'Dotum';}

    .changer_ly{margin:10px auto;text-align: center;}
    .icon{font-size:32px;color:#1261c4;}

    #SwapCoinModal  #result_curency{margin-top:30px;border: 2px solid #1261c4;}
    #result_curency .prices{text-align:center !important}
    #result_currecy .prices.result-price{color:#12c438}
    .desc{margin:15px auto;padding:0;text-align: center;line-height:20px;}
    .desc .red{font-weight:600;color:red}
</style>





<script src="<?= G5_THEME_URL ?>/_common/js/common.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<link rel="stylesheet" href="<?= G5_ADMIN_URL ?>/css/modal.css">
<!-- <link rel="stylesheet" href="<?= G5_THEME_URL ?>/css/scss/custom.css"> -->
<link rel="stylesheet" href="../css/scss/admin_custom.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap">

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    <p class='guide'>
        <!-- -이모지 : 📊 📈 📉 🎁 <br> -->
        <span class='small strong'>현재기준통화 : <strong><?=strtoupper($minings[$now_mining_coin])?></strong> </span><br>
	</p>
    <!-- <button type='button' class='btn pollding_btn'>접어두기</button> -->
</div>

<div class='main'>
    <p>업비트 주요 코인 시세(실시간)</p>
    <fieldset class="checkbox-group" id='coin_dashboard'>
        <?if($coin_cnt < 1){
            echo "<p class='empty'>등록된 코인이 없습니다. <br>아래 코인추가를 눌러 시세를 확인할 코인을 추가해주세요.</p>";        }?>
    </fieldset>

    <div class="btn_layer">
        <button style="background:gray;" type="button" name='refresh' class="btn btn_submit" id="coin_refresh"><i class="ri-refresh-line" ></i></button>
        <button style="background:cornflowerblue;" type="button" name='add' class="btn btn_confirm btn_submit" id="coin_add">코인추가하기</button>
        <button style="" type="button" name='del' class="btn btn_confirm btn_submit" id="coin_del">삭제하기</button>
        <button style="background:#FECE00;" type="button" class="btn btn_confirm btn_submit" id="coin_swap">기준통화스왑</button>
    </div>
</div>

<section id='card_template' style='display: none'>
	<div class="checkbox">
		<label class="checkbox-wrapper" title="">
			<input type="checkbox" class="checkbox-input" data-id='' />
			<span class="checkbox-tile">
				<span class="checkbox-icon">
					<img src="https://static.upbit.com/logos/BTC.png"/>
				</span>
				<span class="checkbox-label">ETH</span>
                <span class="checkbox-labelkr">ETH</span>
                <span class="checkbox-price prices">
                    <p class='price'>0</p>
                    <p class="change_price">0</p>
                </span>
			</span>
		</label>
	</div>
</section>






<!-- <form name="msg_form" id="msg_form" action="./fcm_msg_proc.php" onsubmit="return fmemberlist_submit(this);" method="post">

<div class="tbl_head02 tbl_wrap">
    <table >
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
	<p> 시세 </p>
    <tr>
        <th scope="col" width="30px" id="mb_list_chk">
            <label for="chkall" class="sound_only">전체선택</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col" width="100px">메세지명</th>
        <th scope="col" width="200px">타이틀</th>	
        <th scope="col" width="250px">내용</th>
        <th scope="col" width="80px">이미지첨부</th>
        <th scope="col" width="30px">사용유무</th>
    </tr>
    </thead>

    <tbody>
    <?for($i=0; $row=sql_fetch_array($list); $i++){?>
    
    <tr class='<?if($i == 0){echo 'first';}?>'>
   
    <td style="text-align:center">
        <input type="hidden" name="no[]" value="<?=$row['no']?>">
        <input type="checkbox" name="chk[]" value="<?= $i ?>" id="chk_<?= $i ?>">
    </td>
    <td style="text-align:center"><input class='frm_input' name="name[]"  value="<?=$row['name']?>"></input></td>
    <td style="text-align:center;width:250px;"><input class='frm_input' name="title[]"  value="<?=$row['title']?>"></input></td>
    <td style="text-align:center"><textarea class='frm_input content' name="contents[]" ><?=$row['contents']?></textarea></td>
    <td style="text-align:center"><input class='frm_input' name="image[]"  value="<?=$row['images']?>"></input></td>
    <td style="text-align:center"><input type='checkbox' class='checkbox' name='used[]' <?php echo $row['used'] > 0 ?'checked':''; ?>></td>
    </tr>
    <?}?>
    </tbody>
    
</table>
</form> -->
</div>


<!-- 코인추가모달 -->
<div class="modal fade" id="addCoinModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">코인 추가</h3>
                    <button type="button" class="btn-close close modal-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-dismiss="modal">취소</button>
                    <button type="button" class="btn " id="saved">저장</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 스왑모달 -->
<div class="modal fade" id="SwapCoinModal" tabindex="-1" role="dialog" aria-hidden="true" style='_display:inherit;'>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">기준 통화 스왑</h3>
                    <button type="button" class="btn-close close modal-close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <div class='line_card item' id='origin_curency'>
                        <span class="checkbox-icon"><img src="https://static.upbit.com/logos/<?=strtoupper($minings[$now_mining_coin])?>.png"></span>
                        <span class="checkbox-label"></span>
                        <span class="checkbox-labelkr"></span>
                        <span class="checkbox-label2 prices color-down" data-val=""></span>
                    </div>

                    <div class='changer_ly'><i class="ri-swap-fill icon"></i></div>

                    <div class='line_card item' id='select_curency'>
                        <span class="checkbox-icon"><img src=""/></span>
                        <span class="checkbox-label"></span>
                        <span class="checkbox-labelkr"></span>
                        <span class="checkbox-label2 prices color-down" data-val=""></span>
                    </div>

                    <div class='line_card' id='result_curency'>
                        <span class="checkbox-label2 prices shift_price" data-val=""></span>
                        <span class="changer_ly2"><i class="ri-arrow-right-circle-line icon"></i></span>
                        <span class="checkbox-label2 prices result-price" data-val=""></span>
                    </div>

                    <form name="swap_form" id="swap_form" action="./coins_proc.php"  method="post">
                        <input type='hidden' name='func' value='swap'/>
                        <input type='hidden' name='origin_coin_id' value=''/>
                        <input type='hidden' name='origin_coin_price' value=''/>
                        <input type='hidden' name='change_coin_id' value=''/>
                        <input type='hidden' name='change_coin_price' value=''/>
                        <input type='hidden' name='change_val' value=''/>
                    </form>
                    
                    <div class='desc'></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close" data-dismiss="modal">취소</button>
                    <button type="button" class="btn " id="swap_exc" disabled>스왑(변경) 실행</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.pollding_btn').on('click',function(){
        $(this).parent().find('.guide').toggle();    
    });


    $(document).ready(function(){   
        var coin_list = <?=json_encode($coin_array)?>;
        var coin_name = '';
        var coinkr_name = '';
        
        if(coin_list.length > 0){
            $.each (coin_list, function (index, el) {

                var upbit_api_url = 'https://api.upbit.com/v1/ticker?markets=KRW-' + el[1];
                var upbit_img_src = "https://static.upbit.com/logos/" + el[1] + '.png';

                $.ajax({
                    type: "GET",
                    url: upbit_api_url,
                    data: {
                    },
                    cache: false,
                    dataType: 'json',
                    
                    success: function(res) {
                        if(res){
                            // console.log(`${res[0].market} : ${res[0].trade_price}`);

                            var temp_clone = $('#card_template').clone();
                            var temp_html = $(temp_clone.html());

                            // 템플릿 수정
                            temp_html.find('.checkbox-wrapper').attr('title',el[2]);
                            temp_html.find('.checkbox-wrapper').attr('id',el[1]);
                            temp_html.find('.checkbox-input').attr('data-id',el[1]);
                            temp_html.find('.checkbox-input').val(el[0]);
                            // temp_html.find('.prices').attr('data-no',index);
                            temp_html.find('img').attr('src',upbit_img_src);
                            temp_html.find('.checkbox-label').html(res[0].market);
                            temp_html.find('.checkbox-labelkr').html(el[3]);
                            // console.log(Number(res[0].signed_change_price));

                            if(res[0].change.toLowerCase()  =='rise'){
                                var colorstring = "color-up";
                                var express = "<i class='ri-arrow-up-s-fill'></i>";
                            }else if (res[0].change.toLowerCase() == 'even'){
                                var colorstring = "color-even";
                                var express = "<i class='ri-subtract-line'></i>";
                            }else{
                                var colorstring = "color-down";
                                var express = "<i class='ri-arrow-down-s-fill'></i>";
                            }
                            
                            temp_html.find('.prices').addClass(colorstring);
                            temp_html.find('.prices > .price').attr('data-val',res[0].trade_price).html('￦' +Price(res[0].trade_price));
                            temp_html.find('.prices > .change_price').html(express + Price(res[0].change_price));

                            temp_clone.html(temp_html.prop('outerHTML'));
                            $('#coin_dashboard').append(temp_clone.html());
                        }
                    }
                });
            });
        }

        // 버튼1 : 새로고침
        $('#coin_refresh').on('click', function(){
            location.reload();
        });

        //버튼2 : 코인추가
        $('#coin_add').on('click', function(){
            
            $('#addCoinModal').modal("show");
            
            
            var add_body_html = "<fieldset class='checkbox-group'>";
            add_body_html += "<input type='text' name='coin_name' id='coin_name' class='frm_input' placeholder='추가할 코인 심볼 예)ETH,etc'></input>";
            add_body_html += "<button type='button' name='coin_search' id ='coin_search' class='btn' >확인</button>";
            add_body_html += "</fieldset><div class='contents'></div>";
            
            $('#addCoinModal .modal-header .modal-title').html('코인 추가');
            $('#addCoinModal .modal-body').html(add_body_html);
            $('#addCoinModal .modal-footer #saved').attr("disabled", true);

            $('#addCoinModal #coin_search').on('click', function(){
                var id_val = $('#addCoinModal #coin_name').val();
                var coin_val = "KRW-" + id_val.toUpperCase();
                var coins_data = [];

                $.ajax({
                    type: "GET",
                    url: "https://api.upbit.com/v1/market/all",
                    cache: false,
                    dataType: 'json',
                    success: function(res) {
                        $.each( res, function( key, value ){ 
                           if(res[key].market == coin_val){
                                coins_data = res[key];
                           }
                        });
                        if(Object.keys(coins_data).length > 0){
                            coin_name = coins_data.english_name;
                            coinkr_name = coins_data.korean_name;
                            var html = "<span class='coin_name kor_name'>"+coins_data.korean_name+"</span> / <span class='coin_name eng_name'>"+coins_data.english_name+"</span><br>코인을 추가할까요?";
                            $('#addCoinModal .contents').html(html);
                            $('#addCoinModal #saved').attr('disabled', false);
                            $('#addCoinModal #saved').addClass('btn-primary');
                        }
                    } 
                });
            });

            $('#addCoinModal .close').on('click', function(){
                $('#addCoinModal #saved').prop('disabled', true);
                $('#addCoinModal #saved').removeClass('btn-primary');
                $('#addCoinModal .modal-body').empty();
            });

            $('#addCoinModal #saved').on('click', function(){
                var id_val = $('#addCoinModal #coin_name').val();
               
                /*   market_search().then(function() { }); */
 
                $.ajax({
                type: "POST",
                url: "./coins_proc.php",
                data: {
                    func : 'add',
                    id : id_val.toUpperCase(),
                    name : coin_name,
                    krname : coinkr_name
                },
                cache: false,
                dataType: 'json',
                
                success: function(res) {
                    if(res.result == 'success'){
                        alert('등록되었습니다.',location.reload());
                    }
                } 
                });
               
            });

            // confirmModal("코인 추가","","600px");
        });

        //버튼3 : 코인삭제
        $('#coin_del').on('click', function(){
            var check_item_cnt = $(".checkbox-input:checked").length;
            var select_item = [];

            if(check_item_cnt < 1){
                alert("삭제할 코인을 선택해주세요");
                return false;
            }
            
            if(!confirm("해당코인을 정말 삭제하시겠습니까?")){
                return false;
            }
            

            $(".checkbox-input:checked").each(function( index){ 
                select_item.push($(this).val());
            });

            $.ajax({
                type: "POST",
                url: "./coins_proc.php",
                data: {
                    func : 'del',
                    selected : select_item
                },
                cache: false,
                dataType: 'json',
                
                success: function(res) {
                    if(res.result == 'success'){
                        alert('변경되었습니다.',location.reload());
                    }else{
                        alert(res.sql);
                    }
                } 
            });
        });


        //버튼4 : 코인스왑
        $('#coin_swap').on('click', function(){
            var check_item_cnt = $(".checkbox-input:checked").length;
            var select_item = $(".checkbox-input:checked").data('id');

            if(check_item_cnt != 1){
                alert("스왑을 진행할 코인을 1개만 선택해주세요");
                return false;
            }

            var origin_curency = "<?=strtoupper($minings[$now_mining_coin])?>";
            var select_curency = select_item;
            
            $('#SwapCoinModal').modal("show");
            
            var exc_array = [['origin_curency',origin_curency],['select_curency',select_curency]];
            var exc_price_array = [$('#'+origin_curency+' .checkbox-price .price').data('val'), $('#'+select_curency+' .checkbox-price .price').data('val')];

            for(var i=0;i < exc_array.length; i++){ 
                var target_contents = $('#'+exc_array[i][0]);

                var upbit_img_src = "https://static.upbit.com/logos/" + exc_array[i][1] + '.png';
                target_contents.find('.checkbox-icon img').attr('src',upbit_img_src);
                target_contents.find('.checkbox-label').html(" "+exc_array[i][1]);
                target_contents.find('.checkbox-labelkr').html($('#'+ exc_array[i][1]+' .checkbox-labelkr').html());

                var coin_price_now = exc_price_array[i];
                target_contents.find('.checkbox-label2').attr('data-val',coin_price_now).html('￦' +Price(coin_price_now));
            }

            const result_val = calculate_math(Number(exc_price_array[0]/exc_price_array[1]),4); // 소수점 2자리

            $('#result_curency .shift_price').html("1 "+ exc_array[0][1]);
            $('#result_curency .result-price').attr('data-val',result_val).html(Price(result_val) +' '+ exc_array[1][1]);

            $('.desc').html("<span class='red'>경고! 슈퍼관리자가 아니면 실행 금지</span><br>현재 서비스내 기준 통화(<span class='red'>"+origin_curency+"</span>)를 해당 통화(<span class='red'>"+select_curency+"</span>) 으로 변환하시겠습니까?<br> 실행 이후에는 되돌릴수 없습니다.");

            if(origin_curency != select_curency && result_val != 1){

                $('#SwapCoinModal #swap_exc').addClass('btn-primary');
                $('#SwapCoinModal #swap_exc').attr('disabled', false);
            }

            $('#swap_form').find("input[name='origin_coin_id']").val(origin_curency);
            $('#swap_form').find("input[name='origin_coin_price']").val(exc_price_array[0]);
            $('#swap_form').find("input[name='change_coin_id']").val(select_curency);
            $('#swap_form').find("input[name='change_coin_price']").val(exc_price_array[1]);
            $('#swap_form').find("input[name='change_val']").val(result_val);
            
            $("#SwapCoinModal #swap_exc").on('click',function(){
                /* if(!confirm("기준통화를 변환하시겠습니까?")){
                    return false;
                }else{
                    $('#swap_form').submit();
                } */
                alert("기준통화스왑은 현재 사용할수없습니다.");
            });

        });
    });
    
</script>
<?php
include_once('../admin.tail.php');
?>
