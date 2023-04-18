<?
include_once('./_common.php');
include_once(G5_THEME_PATH . '/_include/wallet.php');
include_once(G5_THEME_PATH . '/_include/gnb.php');
include_once(G5_PLUGIN_PATH.'/Encrypt/rule.php');
$coin = get_coins_price();

if($nw['nw_with'] == 'N'){
  alert("현재 서비스를 이용할수없습니다.");
}

login_check($member['mb_id']);
$title = 'Mywallet';



// 입금설정
$deposit_setting = wallet_config('deposit');
$deposit_fee = $deposit_setting['fee'];
$deposit_min_limit = shift_auto($deposit_setting['amt_minimum']/$coin['usdt_eth'],$currencys[0]);
$deposit_max_limit = $deposit_setting['amt_maximum'];
$deposit_day_limit = $deposit_setting['day_limit'];

// 출금설정
$withdrwal_setting = wallet_config('withdrawal');
$withdrwal_fee = $withdrwal_setting['fee'];
$withdrwal_min_limit = $withdrwal_setting['amt_minimum'];
$withdrwal_max_limit = $withdrwal_setting['amt_maximum'];
$withdrwal_day_limit = $withdrwal_setting['day_limit'];
$withdrawal_price = $withdrwal_setting['withdraw_price'];

$company_wallet = wallet_config('wallet_addr')['wallet_addr'];

$wallet_addr1 = Decrypt($member['mb_wallet'],$member['mb_id'],'x'); // erc20
$wallet_addr2 = Decrypt($member['eth_my_wallet'],$member['mb_id'],'x'); // eth

  // 수수료제외 실제 출금가능금액
  $withdrwal_total = $total_withraw;

  if ($withdrwal_max_limit != 0 && ($total_withraw * $withdrwal_max_limit * 0.01) < $withdrwal_total) {
    $withdrwal_total = $total_withraw * ($withdrwal_max_limit * 0.01);
  }

//계좌정보
$bank_setting = wallet_config('bank_account');
$bank_name = $bank_setting['bank_name'];
$bank_account = $bank_setting['bank_account'];
$account_name = $bank_setting['account_name'];

//시세 업데이트 시간
// $next_rate_time = next_exchange_rate_time();

//보너스/예치금 퍼센트
// $bonus_per = bonus_state($member['mb_id']);


// 패키지 선택하고 들어왔으면 입금할 가격표시
if ($_GET['sel_price']) {
  $sel_price = $_GET['sel_price'];
}


// 입금 OR 출금
if ($_GET['view'] == 'withdraw') {

  $view = 'withdraw';
  $history_target = $g5['withdrawal'];
} else {
  $view = 'deposit';
  $history_target = $g5['deposit'];
}

//kyc인증
$kyc_cert = $member['kyc_cert'];


//지갑 생성
/* $callback = G5_URL . "/plugin/blocksdk/point-callback.php";
      $blocksdk_conf = Crypto::GetConfig();

      if(empty($member['mb_9'])==true && $blocksdk_conf['de_eth_use'] == 1){
        $address = Crypto::GetClient("eth")->createAddress([
          "name" => "member_no_".$member['mb_no']
        ]);
        
        Crypto::CreateWebHook($callback,"eth",$address['address']);
        
        // $update_sql .= empty($update_sql) ? "" : ","; 
        $update_sql = "mb_9='{$address['address']}'";
        $member['mb_9'] = $address['address'];
        
        $sql = "
        insert into 
        blocksdk_member_eth_addresses (id, address, private_key) 
        values ('{$address['id']}', '{$address['address']}','{$address['private_key']}')
        ";
        sql_fetch($sql);
      }

      if(empty($update_sql) == false){
        $sql = "UPDATE {$g5['member_table']} SET {$update_sql} WHERE mb_no={$member['mb_no']}";
        sql_query($sql);
      } */

// $wallet_sql = "SELECT private_key FROM blocksdk_member_eth_addresses WHERE address = '{$member['mb_9']}'";
// $wallet_row = sql_fetch($wallet_sql);
// $private_key = $wallet_row['private_key'];
// $mb_id = $member['mb_id'];


// if($member['eth_download'] == "0"){      
//     include_once(G5_LIB_PATH."/download_key/set_private_key.php"); 
// }

// if($member['eth_download'] == "1"){
//   include_once(G5_LIB_PATH."/download_key/get_private_key.php");

// }




/*날짜계산*/
$qstr = "stx=" . $stx . "&fr_date=" . $fr_date . "&amp;to_date=" . $to_date;
$query_string = $qstr ? '?' . $qstr : '';

$fr_date = date("Y-m-d", strtotime(date("Y-m-d") . "-1 day"));
$to_date = date("Y-m-d", strtotime(date("Y-m-d") . "+1 day"));

$sql_search_deposit = " WHERE mb_id = '{$member['mb_id']}' ";
$sql_search_deposit .= " AND create_dt between '{$fr_date}' and '{$to_date}' ";

$rows = 15; //한페이지 목록수


//입금내역
$sql_common_deposit = "FROM {$g5['deposit']}";

$sql_deposit = " select count(*) as cnt {$sql_common_deposit} {$sql_search_deposit} ";
$row_deposit = sql_fetch($sql_deposit);

$total_count_deposit = $row_deposit['cnt'];
$total_page_deposit  = ceil($total_count_deposit / $rows);

if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지
$from_record_deposit = ($page - 1) * $rows; // 시작 열

$sql_deposit = " select * {$sql_common_deposit} {$sql_search_deposit} order by create_dt desc limit {$from_record_deposit}, {$rows} ";
$result_deposit = sql_query($sql_deposit);

//출금내역
$sql_common = "FROM {$g5['withdrawal']}";
// $sql_common ="FROM wallet_withdrawal_request";

$sql_search = " WHERE mb_id = '{$member['mb_id']}' ";
// $sql_search .= " AND create_dt between '{$fr_date}' and '{$to_date}' ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
if ($debug) echo "<code>" . $sql . "</code>";

$row = sql_fetch($sql);
$total_count = $row['cnt'];
$withdrawal_count = $row['cnt'];

$total_page  = ceil($total_count / $rows);
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지
$from_record = ($page - 1) * $rows; // 시작 열

$sql = " select * {$sql_common} {$sql_search} order by create_dt desc limit {$from_record}, {$rows} ";
$result_withdraw = sql_query($sql);

//  출금 승인 내역 
$amt_auth_log = sql_query("SELECT * from {$g5['withdrawal']} WHERE mb_id = '{$member['mb_id']}'  AND status = 1 ");
$auth_cnt = sql_num_rows($amt_auth_log);

?>


<!-- <link rel="stylesheet" href="<?= G5_THEME_CSS_URL ?>/withdrawal.css"> -->
<script type="text/javascript" src="./js/qrcode.js"></script>

<? include_once(G5_THEME_PATH . '/_include/breadcrumb.php'); ?>

<style>
  input[type='text'].modal_input{background: #ededed;
    margin-top: 10px;
    box-shadow: inset 1px 1px 1px rgb(0 0 0 / 50%);
    border: 0;
    text-align: center;
    width: 50%;}
  .time_remained{display:block;text-align:center}
  .processcode{color:red;display:block;text-align:center;font-size:13px;}
</style>

<main>
  <div class='container mywallet'>
    <div class="my_btn_wrap">
      <div class="row mywallet_btn">
        <div class='col-lg-6 col-12'>
          <button type='button' class='btn wd main_btn b_darkblue round' onclick="switch_func('deposit')" > 입금</button>
        </div>
        <div class='col-lg-6 col-12'>
          <button type='button' class='btn wd main_btn b_skyblue round' onclick="switch_func('withdraw')" >출금</button>
        </div>
      </div>
    </div>
    <!-- 입금 -->
    <section id='deposit' class='loadable'>
      <div class="content-box round">
        <h3 class="wallet_title" >입금지갑주소</h3>
        <div class="row ">
          <!-- <div class='col-12 text-center bank_info'>
            <?= $bank_name ?> : <input type="text" id="bank_account" class="bank_account" value="<?= $bank_account ?>" title='bank_account' disabled />(<?= $account_name ?>)
            <?if ($sel_price) { ?>
              <div class='sel_price'>입금액 : <span class='price'><?= Number_format($sel_price) ?><?= ASSETS_CURENCY ?></span></div>
            <?}?>
          </div>
          <div class='col-12'>
            <button class="btn wd line_btn " style="background: #f5f5f5;" id="accountCopy" onclick="copyURL('#bank_account')">
              <span > 계좌복사 </span>
            </button>
          </div> -->
          <!-- 이더전용입금 -->          
          <div class="wallet qrBox col-12">
              <div class="eth_qr_img qr_img" id="my_eth_qr"></div>
          </div> 
          <div class='qrBox_right col-12'>
              <input type="text" id="my_eth_wallet" class="wallet_addr text-center" value="<?=$company_wallet ?>" title='my address' disabled/>
              <button class="btn wd line_btn" id="accountCopy" onclick="copyURL('#my_eth_wallet')">
                  <span >주소복사</span>
              </button>
          </div>   
        </div>      
      </div>
      <div class="col-sm-12 col-12 content-box round mt20" id="eth">
        <h3 class="wallet_title" >입금확인요청 </h3> <span class='desc'> - 입금후 1회만 요청해주세요</span>
        <div style="clear:both"></div>
        <div class="row">
          <div class="btn_ly qrBox_right "></div>
          <div class="col-sm-12 col-12 withdraw mt20">
            <input type="text" id="deposit_name" class='b_ghostwhite p15' placeholder="TXID를 입력해주세요">

            <input type="text" id="deposit_value" class='b_ghostwhite p15' placeholder="입금수량을 입력해주세요">
            <label class='currency-right'><?= $curencys[0] ?></label>
          </div>
        
          <div class='col-sm-12 col-12 '>
            <button class="btn btn_wd font_white deposit_request" data-currency="<?=$curencys[0]?>">
              <span >입금확인요청</span>
            </button>
          </div>
        </div>
      </div>
      <!-- 입금 요청 내역 -->
      <div class="history_box content-box">
        <h3 class="hist_tit wallet_title">입금 내역</h3>
        <div class="b_line2"></div>
        <? if (sql_num_rows($result_deposit) == 0) { ?>
          <div class="no_data"> 입금내역이 존재하지 않습니다.</div>
        <? } ?>

        <? while ($row = sql_fetch_array($result_deposit)) { ?>
        <div class='hist_con'>
          <div class="hist_con_row1">
            <div class="row">
              <span class="hist_date"><?= $row['create_dt'] ?></span>
              <span class="hist_value"><?= shift_auto($row['amt']) ?> <?= $row['coin'] ?></span>
            </div>

            <div class="row">
              <span class='hist_name'>TXID : <?= $row['txhash'] ?></span>
              <span class="hist_value status"><? string_shift_code($row['status']) ?></span>
            </div>
          </div>
        </div>
        <? } ?>
        <?php
        $pagelist = get_paging($config['cf_write_pages'], $page, $total_page_deposit, "{$_SERVER['SCRIPT_NAME']}?id=mywallet&$qstr&view=deposit");
        echo $pagelist;
        ?>
      </div>
    </section>

    <!-- 출금 -->
    <section id='withdraw' class='loadable'>
      <form name=''>
      </form>
      <div class="col-sm-12 col-12 content-box round mt20">
        <h3 class="wallet_title">출금</h3>
        <span class="desc"> 총 출금 가능액 : <?= shift_auto($withdrwal_total,$curencys[1]) ?> <?= $curencys[1] ?></span>
        <div class="row">
          <div class="col-12 coin_select_wrap mb20">
            <label class="sub_title">- 출금코인 선택</label>
            <select class="form-control" name="" id="select_coin">
              <option value="<?=$curencys[3]?>" selected><?=$curencys[3]?></option>  
              <option value="<?=$curencys[0]?>"><?=$curencys[0]?></option>
            </select>
          </div> 
          <div class='col-12'><label class="sub_title">- 출금정보 (최초 1회입력)</label></div>
          <!-- <div class='col-6'>
            <input type="text" id="withdrawal_bank_name" class="b_ghostwhite " placeholder="은행명" value="<?= $member['bank_name'] ?>">
          </div>
          <div class='col-6'>
            <input type="text" id="withdrawal_account_name" class="b_ghostwhite " placeholder="예금주" value="<?= $member['account_name'] ?>">
          </div> -->
          <div class='col-12'>
            <input type="text" id="withdrawal_bank_account" class="b_ghostwhite " placeholder="출금 지갑주소를 입력해주세요" value="<?= $wallet_addr1 ?>">
          </div>
        </div>
        <div class="input_shift_value mb10 pb5">
          <label class="sub_title">- 출금금액 (수수료:<?= $withdrwal_fee ?>%)</label>
          <span style='display:inline-block;float:right;'><button type='button' id='max_value' class='btn inline' value=''>max</button></span>
          <input type="text" id="sendValue" class="send_coin b_ghostwhite " placeholder="출금 수량을 입력해주세요">
          <label class='currency-right'><?= $curencys[1] ?></label>          
            <!-- <div class='fee' style='color:black;padding-right:3px;letter-spacing:-0.5px'>
              <span>실 출금 금액(수수료 제외) : </span><span id='fee_val' style='color:red;margin-right:10px;font-size:14px;font-weight:bold'></span>
            </div> -->
          <div class="row fee hidden mt10" style='width:initial'>
            <div class="col-12">
                <i class="ri-exchange-fill"></i>
                <span id="active_amt">0</span>
            </div>
            <div class="col-12">
                <label class="fees">- 수수료(<?= $withdrwal_fee ?>%) :</label>
                <i class="ri-coins-line"></i>
                <span id="fee_val">0</span>
            </div>
          </div>
        </div>
        <div class="b_line5"></div>
        <div class="otp-auth-code-container mt20 pt10">
          <div class="verifyContainerOTP">
            <label class="sub_title" >- 출금 비밀번호</label>
            <input type="password" id="pin_auth_with" class="b_ghostwhite" name="pin_auth_code"  maxlength="6" placeholder="6 자리 핀코드를 입력해주세요">
          </div>
        </div>
        <div class="send-button-container row">
          <div class="col-5">
            <button id="pin_open" class="btn wd yellow form-send-button" >인증</button>
          </div>
          <div class="col-7">
            <button type="button" class="btn wd btn_wd form-send-button" id="Withdrawal_btn" data-toggle="modal" data-target="" disabled>출금 신청</button>
          </div>
        </div>
      </div>

      <!-- 출금내역 -->
      <div class="history_box content-box">
        <h3 class="hist_tit wallet_title">출금 내역</h3>
        <div class="b_line2"></div>

        <? if (sql_num_rows($result_withdraw) == 0) { ?>
          <div class="no_data">출금내역이 존재하지 않습니다</div>
        <? } ?>

        <? while ($row = sql_fetch_array($result_withdraw)) { 
          $coin_curency = $row['coin'] == $curencys[1] ? BONUS_NUMBER_POINT : COIN_NUMBER_POINT; 
          
          ?>
          <div class='hist_con'>
          <div class="hist_con_row1">
            <div class="row">
              <span class="hist_date"><?= $row['create_dt'] ?></span>
              <span class="hist_value "> <?=shift_auto($row['amt_total']) ?> <?= $row['coin'] ?></span>
            </div>

            <div class="row">
              <span class="hist_withval"> <?= shift_auto($row['amt']) ?> <?= $row['coin'] ?> / <label>Fee : </label> <?= shift_auto($row['fee']) ?> <?= $row['coin'] ?></span>
              <span class="hist_value status"><?=shift_auto($row['out_amt'])?> <?= $curencys[1] ?></span>
            </div>

            <!-- <div class="row">
              <span class='hist_bank'><label>Address : </label><?=$row['addr']?></span>
            </div> -->
            
            <div class="row">
              <span class="hist_withval f_small"><label>Result :</label> </span>
              <span class="hist_value status"><? string_shift_code($row['status']) ?></span>
            </div>

            
          </div>
        </div>
        <? } ?>

        <?php
        $pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?id=mywallet&$qstr&view=withdraw");
        echo $pagelist;
        ?>
      </div>
    </section>
  </div>
</main>

<?php include_once(G5_THEME_PATH . '/_include/tail.php'); ?>
<div class="gnb_dim"></div>
</section>


<!-- <script src="<?= G5_THEME_URL ?>/_common/js/timer.js"></script> -->

<script>
  window.onload = function() {
    switch_func("<?= $view ?>");
    // move(<?= $bonus_per ?>); 
    // getTime("<?= $next_rate_time ?>");
  }
  
  $(function() {
    $(".top_title h3").html("<span >입출금</span>");

    var debug = "<?= $is_debug ?>";

    /* if(debug){
      console.log('[ Mode : debug ]');
      $('#Withdrawal_btn').attr('disabled',false);
    } */

    // 회사 지갑사용
    var eth_wallet_addr = '<?=$company_wallet?>';
    if(eth_wallet_addr != ''){
        $('#eth_wallet_addr').val(eth_wallet_addr);
        generateQrCode("my_eth_qr",eth_wallet_addr, 80, 80);
    }

    // 입금 전용 지갑사용
    /* var my_eth_wallet = "<?= $member['mb_9'] ?>"
    if(my_eth_wallet != ''){
      $('#my_eth_wallet').val(my_eth_wallet);
        generateQrCode("my_eth_qr",my_eth_wallet, 80, 80);
    } */
  
    /* 출금*/
    var curency_tmp = '<?= $curencys[3] ?>';
    var usdt_curency = '<?=$curencys[1]?>';
    var eth_curency = '<?= $curencys[0] ?>';
    var erc20_curency = '<?=$curencys[3]?>';
    var mb_block = Number("<?= $member['mb_block'] ?>"); // 차단

    var mb_id = '<?= $member['mb_id'] ?>';
    var nw_with = '<?= $nw_with ?>'; // 출금서비스 가능여부
    var personal_with = '<?=$member['mb_leave_date']?>'; // 별도구분회원 여부

    // 출금설정
    var out_fee = (<?= $withdrwal_fee ?> * 0.01);
    var out_min_limit = '<?= $withdrwal_min_limit ?>';
    var out_max_limit = '<?= $withdrwal_max_limit ?>';
    var out_day_limit = '<?= $withdrwal_day_limit ?>';

    // 최대출금가능금액
    var out_mb_max_limit = Number('<?= $withdrwal_total ?>'.replace(/,/g,''));
    let fixed_amt = 0, fixed_fee = 0;

    
    onlyNumber('pin_auth_with');

    
    // 출금금액 변경 
    function input_change() {

        const input_value = Number(conv_number(document.querySelector('#sendValue').value));
        const real_fee_val = Number(input_value * out_fee);
        const real_withdraw_val = input_value - real_fee_val;
        let shift_coin_value = <?=BONUS_NUMBER_POINT?>;
        let swap_coin_price = (real_withdraw_val*<?=$coin['usdt_krw']?>)/<?=$withdrawal_price?>;
        let swap_fee_val = (real_fee_val*<?=$coin['usdt_krw']?>)/<?=$withdrawal_price?>;

        if(curency_tmp == eth_curency){
          shift_coin_value = <?=ASSETS_NUMBER_POINT?>;
          swap_coin_price = real_withdraw_val/<?=$coin['usdt_eth']?>;
          swap_fee_val = real_fee_val/<?=$coin['usdt_eth']?>;
        }

        fixed_amt = Number(swap_coin_price).toFixed(shift_coin_value);
        fixed_fee = Number(swap_fee_val).toFixed(shift_coin_value);

        if(input_value != ""){  
          $('.fee').css('display', 'flex');
          $('#fee_val').text(`${fixed_fee} ${curency_tmp}`);
          $('#active_amt').text(`실 출금 금액(수수료 제외) : ${fixed_amt} ${curency_tmp}`);
        }

    }

    $('#sendValue').change(input_change);

    // 출금가능 맥스
    $('#max_value').on('click', function() {
      $("#sendValue").val(out_mb_max_limit);
      input_change('sendValue');
    });
    
    //이더 입금
    document.querySelector('#deposit_value').addEventListener('keyup',(e)=>{
      input_change_eth(e.target);
    });

    function input_change_eth(obj) {
        let pattern = /^\d+(\.)?(\d{0,8})?$/;
        let korean_check_pattern = /[가-힣ㄱ-ㅎㅏ-ㅣ\x20]/g; 
        let zero_check_pattern = /^(0)\d+/g;
        
        obj.value = obj.value.replace(zero_check_pattern,"");
        obj.value = obj.value.replace(korean_check_pattern,"");
        if(!pattern.test(obj.value)){
          obj.value = obj.value.slice(0, -1);
          return false;
        }
    };

   document.querySelector('#select_coin').addEventListener('change',(e)=>{
    curency_tmp = e.target.value;
    let wallet_addr = curency_tmp == erc20_curency ? '<?=$wallet_addr1?>' : '<?=$wallet_addr2?>';
    $('#withdrawal_bank_account').val(wallet_addr);
    $('.fee').css('display', 'none');
    document.querySelector('#sendValue').value = "";
   })


    /*핀 입력*/
    $('#pin_open').on('click', function(e) {

      // 회원가입시 핀입력안한경우
      if ("<?= $member['reg_tr_password'] ?>" == "") {
        dialogModal('출금 비밀번호(핀코드) 인증', '<p>출금 비밀번호(핀코드) 등록해주세요.</p>', 'warning');

        $('#modal_return_url').click(function() {
          location.href = "./page.php?id=profile"
        })
        return;
      }

      if ($('#pin_auth_with').val() == "") {
        dialogModal('출금 비밀번호(핀코드) 인증', '<p>출금 비밀번호(핀코드) 입력해주세요.</p>', 'warning');
        return;
      }

      $.ajax({
        url: './util/pin_number_check_proc.php',
        type: 'POST',
        cache: false,
        async: false,
        data: {
          "mb_id": mb_id,
          "pin": $('#pin_auth_with').val()
        },
        dataType: 'json',
        success: function(result) {
          if (result.response == "OK") {
            dialogModal('출금 비밀번호(핀코드) 인증', '<p>출금 비밀번호가 인증되었습니다.</p>', 'success');

            $('#Withdrawal_btn').attr('disabled', false);
            $('#pin_open').attr('disabled', true);
            $("#pin_auth_with").attr("readonly", true);
          } else {
            dialogModal('출금 비밀번호(핀코드) 인증', '<p>출금 비밀번호가 일치 하지 않습니다.</p>', 'failed');
          }
        },
        error: function(e) {
          //console.log(e);
        }
      });
    });

    var time_reamin = false;
    var is_sms_submitted = false;
    var check_pin = false;
    var process_step = false;

    var mb_hp = '<?=$member['mb_hp']?>';

    function input_timer(time,where){
      var time = time;
      var min = '';
      var serc = '';

      var x = setInterval(function(){
        min = parseInt(time/50);
        sec = time%60;

        $(where).html(min + "분 " + sec + "초");
        time--;

        if(time < 0){
          clearInterval(x);
          $(where).html("시간초과");
          time_reamin = false;
        }
      },1000)
    }

    function check_auth_mobile(val){
      $.ajax({
          type: "POST",
          url: "./util/check_auth_sms.php",
          dataType: "json",
          cache: false,
          async: false,
          data: {
            pin: val,
          },
          success: function(res) {
            if (res.result == "success") {
              check_pin = true;
            } else {
              check_pin = false;
            }
          }
        });
    }
  
    
     
    // 출금요청
    $('#Withdrawal_btn').on('click', function() {

      var inputVal = $('#sendValue').val().replace(/,/g, '');
      // console.log(` out_min_limit : ${out_min_limit}\n out_max_limit:${out_max_limit}\n out_day_limit:${out_day_limit}\n out_fee: ${out_fee}`);
      

      // 출금계좌정보확인
      // var withdrawal_bank_name = $('#withdrawal_bank_name').val();
      // var withdrawal_account_name = $('#withdrawal_account_name').val();
      var withdrawal_bank_account = $('#withdrawal_bank_account').val();
      
      // 모바일 등록 여부 확인
      // if(mb_hp == '' || mb_hp.length < 10){
      //   dialogModal('정보수정', '<strong> 안전한 출금을 위해 인증가능한 모바일 번호를 등록해주세요.</strong>', 'warning');
        
      //   $('.closed').on('click',function(){
      //     location.href='/page.php?id=profile';
      //   })
      //   return false;
      // }

      //KYC 인증
      var out_count = Number("<?=$auth_cnt ?>");
      var kyc_cert = Number("<?=$kyc_cert?>");

      if(out_count < 1 && kyc_cert != 1){
        dialogModal('KYC 인증 미등록/미승인 ', "<strong> KYC인증이 미등록 또는 미승인 상태입니다.<br>안전한 출금을 위해 최초 1회 KYC 인증을 진행해주세요<br><a href='/page.php?id=profile' class='btn btn-primary'>KYC인증</a></strong>", 'warning');
        return false;
      }

      // 계좌정보 입력 확인
      if (withdrawal_bank_account == '') {
        dialogModal('출금지갑확인', '<strong> 출금 지갑정보를 입력해주세요.</strong>', 'warning');
        return false;
      }

      // 출금서비스 이용가능 여부 확인
      if (nw_with == 'N') {
        dialogModal('서비스이용제한', '<strong>현재 출금가능한 시간이 아닙니다.</strong>', 'warning');
        return false;
      }

      if(personal_with != ''){
        dialogModal('서비스이용제한', '<strong>관리자에게 연락주세요</strong>', 'warning');
        return false;
      }
      

      // 금액 입력 없거나 출금가능액 이상일때  
      if (inputVal == '' || inputVal > out_mb_max_limit) {
        console.log(`input : ${inputVal} \n max : ${out_mb_max_limit}`);
        dialogModal('금액 입력 확인', '<strong>출금 금액을 확인해주세요.</strong>', 'warning');
        return false;
      }

      // 최소 금액 확인
      if (out_min_limit != 0 && inputVal < Number(out_min_limit)) {
        dialogModal('금액 입력 확인', '<strong> 최소가능금액은 ' + Price(out_min_limit) + ' ' + usdt_curency + '입니다.</strong>', 'warning');
        return false;
      }

      //최대 금액 확인
      if (out_max_limit != 0 && inputVal > Number(out_max_limit)) {
        dialogModal('금액 입력 확인', '<strong> 1회 출금 가능금액은 ' + Price(out_max_limit) + ' ' + usdt_curency + '입니다.</strong>', 'warning');
        return false;
      }
      
      // process_pin_mobile().then(function (){

        $.ajax({
          type: "POST",
          url: "./util/withdrawal_proc.php",
          cache: false,
          async: false,
          dataType: "json",
          data: {
            mb_id: mb_id,
            func: 'withdraw',
            total_amt: inputVal,
            select_coin : curency_tmp,
            fixed_fee: fixed_fee,
            fixed_amt: fixed_amt,
            bank_account: withdrawal_bank_account,
            cost: Number(<?=$coin['usdt_krw']?> * inputVal)
          },
          success: function(res) {
            if (res.result == "success") {
              dialogModal('출금신청이 정상적으로 처리되었습니다.', '<p>실제 출금까지 24시간 이상 소요될수있습니다.</p>', 'success');

              $('.closed').click(function() {
                location.href = '/page.php?id=mywallet&view=withdraw';
              });
            } else {
              dialogModal('Withdraw Failed', "<p>" + res.sql + "</p>", 'warning');
            }
          }
        });

      // });

      /* if (!mb_block) {
      } else {
        dialogModal('Withdraw Failed', "<p>Not available right now</p>", 'failed');
      } */

    });


  // function process_pin_mobile(){

    //   return new Promise(
    //     function(resolve,reject){
    //     dialogModal('본인인증', "<p>"+maskingFunc.phone(mb_hp)+"<br>모바일로 전송된 인증코드 6자리를 입력해주세요<br><input type='text' class='modal_input' id='auth_mobile_pin' name='auth_mobile_pin'></input><span class='time_remained'></span><span class='processcode'></span></p>", 'confirm');

    //     if( is_sms_submitted == false ){
    //       is_sms_submitted = true;

    //       $.ajax({
    //         type: "POST",
    //         url: "./util/send_auth_sms.php",
    //         cache: false,
    //         async: false,
    //         dataType: "json",
    //         data: {
    //           mb_id: mb_id,
    //         },
    //         success: function(res) {
    //           if (res.result == "success") {
    //             time_reamin = true;
    //             input_timer(res.time,'.time_remained');

    //             $('#modal_confirm').on('click',function(){
                  
    //               if(!time_reamin){
    //                 is_sms_submitted = false;
    //                 alert("시간초과로 다시 시도해주세요");
    //               }else{
    //                 var input_pin_val = $("#auth_mobile_pin").val();
    //                 check_auth_mobile(input_pin_val);

    //                 if(!check_pin){
    //                   $(".processcode").html("인증코드가 일치하지 않습니다.");
    //                   return false;
    //                 }else{
    //                   is_sms_submitted = false;
    //                   process_step = true;
    //                   resolve();
    //                 }
                    
    //               }
    //             });

    //             $('#dialogModal .cancle').on('click',function(){
    //               is_sms_submitted = false;
    //               location.reload();
    //             });
                
    //           }
    //         }
    //       });

    //     }else{
    //       alert('잠시 후 다시 시도해주세요.');
    //     }
    //   });
  // }

    

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /* 입금 */




    /*입금 확인 요청 - coin */
    /* $('.deposit_request.eth').on('click', function (e) {
      var d_price = $('#deposit_value').val();

      if($('.d_price').text() != ""){
          d_price = $('.d_price').text();
      }
      
      var coin = $(this).data('currency');
      var hash_target = $(this).parent().parent().find('.confirm_hash');
      
      if(hash_target.val()==""){
          dialogModal('Deposit Confirmation Request','<p>Transaction Hash is empty!</p>','warning');
          return;
      }

      if(debug) console.log('입금 : '+ coin +' || tx :' + hash_target.val());

      $.ajax({
        url: '/util/request_deposit.php',
        type: 'POST',
        cache: false,
        async: false,
        data: {
          "mb_id" : mb_id,
          "coin" : coin,
          "hash": hash_target.val(),
          "d_price" : d_price
        },
        dataType: 'json',
        success: function(result) {
          if(result.response == "OK"){
            dialogModal('Deposit Request', 'Deposit Request success', 'success');
            $('.closed').click(function(){
              location.reload();
            });
          }else{
            if(debug) dialogModal('Deposit Request',result.data,'failed'); 
            else dialogModal('Deposit Request','<p>ERROR<br>Please try later</p>','failed');
          }
        },
        error: function(e){
          if(debug) dialogModal('ajax ERROR','IO ERROR','failed'); 
        }
        
      });
    });  */


    // 입금확인요청 
    $('.deposit_request').on('click', function(e) {
      var d_name = $('#deposit_name').val(); // 입금자
      var d_price = $('#deposit_value').val(); // 입금액
      var coin = $(this).data('currency');

      // 입금설정
      var in_fee = (<?= $deposit_fee ?> * 0.01);
      var in_min_limit = '<?= $deposit_min_limit ?>';
      var in_max_limit = '<?= $deposit_max_limit ?>';
      var in_day_limit = '<?= $deposit_day_limit ?>';

      console.log(` in_min_limit : ${in_min_limit}\n in_max_limit:${in_max_limit}\n in_day_limit:${in_day_limit}\n in_fee: ${in_fee}`);
      console.log(' 입금자 : ' + d_name + ' || 입금액 :' + d_price);

      if (d_name == '' || d_price == '') {
        dialogModal('<p>입금 요청값 확인</p>', '<p>항목을 입력해주시고 다시 시도해주세요.</p>', 'warning');
        return false;
      }
      
      if(in_min_limit > 0 &&  Number(d_price) < Number(in_min_limit) ){
        dialogModal('<p>최소 입금액 확인</p>', '<p>최소 입금 확인 금액은 '+ Price(in_min_limit)+ coin +' 입니다. </p>', 'warning');
        return false;
      }
      

      $.ajax({
        url: '/util/request_deposit.php',
        type: 'POST',
        cache: false,
        dataType: 'json',
        data: {
          "mb_id": mb_id,
          "coin": coin,
          "hash": d_name,
          "d_price": d_price
        },
        success: function(result) {
          if (result.response == "OK") {
            dialogModal('입금 요청', '입금요청이 정상처리되었습니다.', 'success');
            $('.closed').click(function() {
              location.reload();
            });
          } else {
            dialogModal('Deposit Request', result.data, 'failed');
          }
        },
        error: function(e) {
          if (debug) dialogModal('ajax ERROR', 'IO ERROR', 'failed');
        }

      });

    });

  });


  

  function switch_func(n) {
    $('.loadable').removeClass('active');
    $('#' + n).toggleClass('active');
  }

  function switch_func_paging(n) {
    $('.loadable').removeClass('active');
    $('#' + n).toggleClass('active');
    window.location.href = window.location.pathname + "?id=mywallet&'<?= $qstr ?>'&page=1&view=" + n;
  }

  function copyURL(addr) {
    dialogModal("","<p>지갑주소가 복사 되었습니다.</p>","success");

    var temp = $("<input>");
    $("body").append(temp);
    temp.val($(addr).val()).select();
    document.execCommand("copy");
    temp.remove();
  }

  //  QR코드
  function generateQrCode(qrImg, text, width, height){
      return new QRCode(document.getElementById(qrImg), {
          text: text,
          width: width,
          height: height,
          colorDark : "#000000",
          colorLight : "#ffffff",
          correctLevel : QRCode.CorrectLevel.H
      });
  } 

  collapseClosed()
</script>