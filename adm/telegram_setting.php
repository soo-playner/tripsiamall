<?php
$sub_menu = '100600';
include_once('./_common.php');
auth_check($auth[$sub_menu], 'r');



$create_table = sql_query("CREATE TABLE IF NOT EXISTS `telegram_setting` (
    `idx` int(255) NOT NULL,
    `bot_api_code` varchar(255) NOT NULL,
    `bot_chat_id` varchar(255) NOT NULL,
    KEY `bot_api_code` (`bot_api_code`),
    KEY `bot_chat_id` (`bot_chat_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 "
);
if($create_table){
    sql_query("INSERT INTO telegram_setting (`idx`, `bot_api_code`, `bot_chat_id`) VALUES (1, '', '')");
}


$g5['title'] = '텔레그램 플러그인 기본설정';
include_once('./admin.head.php');
$row = sql_fetch(" select bot_api_code, bot_chat_id from telegram_setting where idx = 1 ");
?>
    <?php
        if($_GET['mode'] == 'update'){
        
                $bot_api_code = trim($_POST['bot_api_code']);
                $bot_chat_id = trim($_POST['bot_chat_id']);
            
                if(!$bot_chat_id && !$bot_chat_id){
                    echo "<script>alert('누락된 정보가 있습니다.'); location.href = '$PHP_SELF?mode=select'; </script> ";
                    exit();
                }//
            
                sql_query(" update telegram_setting set bot_api_code = '$bot_api_code', bot_chat_id = '$bot_chat_id' ");
                echo "<script>alert('정상적으로 DB에 반영됐습니다.'); location.href = '$PHP_SELF?mode=select'; </script> ";
                exit();         
            
        }elseif($_GET['mode'] == 'reset'){
        
            sql_query(" truncate table telegram_setting ");
            sql_query(" insert into telegram_setting VALUES (1, '','') ");
            echo "<script>alert('정상적으로 초기화가 완료됐습니다.'); location.href = '$PHP_SELF?mode=select'; </script> ";            
            
        } // end
    ?>
<form action='<?=$PHP_SELF;?>?mode=update' method=post>
    <div class="tbl_frm01 tbl_wrap">
            <table>
            <caption>홈페이지 기본환경 설정</caption>
            <colgroup>
                <col class="grid_4">
                <col>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                
            <tr>
                <th scope="row"><label for="cf_title">수신 BOT API CODE<strong class="sound_only">필수</strong></label></th>
                <td colspan="3"><input type="text" name="bot_api_code" value="<?php if($row['bot_api_code']){ echo $row['bot_api_code']; } ?>" placeholder='435070531:AFFCwB4JfvU6hai1u_Osal3dYcERx4F67wQ' id="cf_title" required="" class="required frm_input" size="80"></td>
            </tr>
            <tr>
                <th scope="row"><label for="cf_admin">수신 BOT CHAT ID<strong class="sound_only">필수</strong></label></th>
                <td colspan="3"><input type="text" name="bot_chat_id" value="<?php if($row['bot_chat_id']){ echo $row['bot_chat_id']; } ?>" placeholder="354402611" id="cf_title" required="" class="required frm_input" size="80"></td>
            </tr>
            <tr>
                <th scope='row'>관리하기</th>
                <td colspan=3>
                    <input type="submit" value="DB에 반영 요청하기" class="btn_submit btn" accesskey="s">
                    <input type="button" onclick="location.href = '<?=$PHP_SELF;?>?mode=reset'; " value="DB 리셋" style="background:#3f51b5;" class="btn_submit btn" accesskey="s">
                
                </td>
            </tr>
            </tbody>
            </table>
        </div>
    <div class="local_desc02 local_desc" style="background:#f3f4f3;">
        <p><strong>◆ 텔레그램 봇 생성방법:</strong> <a href='https://hatpub.tistory.com/48' target=_blank>https://hatpub.tistory.com/48</a></p>
        <p><strong>◆ Bot 생성 후 Chat_id 구하기:</strong> <a href='https://blog.acidpop.kr/216' target=_blank>https://blog.acidpop.kr/216</a></p>
    </div>
</form>
<?php
include_once('./admin.tail.php');
?>