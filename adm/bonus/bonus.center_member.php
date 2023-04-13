<?php
$sub_menu = "600800";
include_once('./_common.php');


$g5['title'] = '센터수당(멤버)';
$code = 'center';

include_once(G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

auth_check($auth[$sub_menu], 'r');

if (empty($fr_date)) $fr_date = date("Y-m-d", strtotime(date("Y-m-d")."+0 day"));
if (empty($to_date)) $to_date = date("Y-m-d", strtotime(date("Y-m-d")."+1 day"));


if($center){
    $select_sql = "SELECT * from g5_member WHERE mb_id = '{$center}' ";
    $select_result = sql_fetch($select_sql);
    $select_id = $select_result['mb_id'];
}

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;center=".$center;
$query_string = $qstr ? '?'.$qstr : '';

if($_GET['member']){
    $member_search = $_GET['member'];
}

function active_check($val, $target){
    $bool_check = $_GET[$target];
    if($bool_check == $val){
        return " active ";
    }
}

$colspan = 8;

$sql_common = " FROM g5_shop_order A left JOIN g5_member B ON A.mb_id = B.mb_id ";
$sql_search = " where B.mb_center = '{$select_id}' and B.mb_center != '' ";

if($fr_date && $to_date){
    $sql_search .= " AND A.od_date >= '{$fr_date}' AND A.od_date <= '{$to_date}' ";
}

if($member_search == 'all'){
    $sql = " select * as cnt from
    g5_member WHERE mb_center = '{$select_id}' ";
}else{
    $sql = " select COUNT(DISTINCT B.mb_id) as cnt
    {$sql_common}
    {$sql_search}";
}

$rows = sql_fetch($sql);
$total_count = $rows['cnt'];


$rows = 50;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if($member_search == 'all'){
    $sql = " select * from
    g5_member WHERE mb_center = '{$select_id}' ";
}else{
    $sql = " SELECT B.mb_no,A.mb_id,SUM(A.upstair) AS hap,A.od_date,B.mb_center,B.mb_open_date,B.mb_recommend,B.mb_level
            {$sql_common}
            {$sql_search}
            GROUP BY A.mb_id
            order by B.mb_no desc
            limit {$from_record}, {$rows} ";
}    

if($debug){
    echo "<br>";
    echo "=====================";
    print_R($sql);
    echo "=====================";
    echo "<br>";
}

$excel_sql = urlencode($sql);
$result = sql_query($sql);

?>


<style>
.benefit{color:white;border:0;padding: 5px 15px;height:40px;}
.benefit.day{background:cornflowerblue}
.benefit.upstair{background:steelblue}
.benefit.recom{background:slateblue}
.benefit.qpack{background:dodgerblue}
.benefit.level{background:slategray}
.benefit.bpack{background:teal}
.benefit.black{background:black}
.benefit.red{background:red}
.benefit.hotpink{background:hotpink}
.benefit:hover{background:black;}
.red{color:red}
.text-center{text-align:center}
.sch_last{display:inline-block;}
.rank_img{width:20px;height:20px;margin-right:10px;}
.btn_submit{width:100px;margin-left:20px;}
.green{background-color:green}
.black_btn{background:#333 !important; border:1px solid black !important; color:white;}
.labelM{margin-left:10px;}
.title{font-size:30px;font-weight:600;padding-left:10px;}
.title .eng{font-size:15px;}
a:hover{text-decoration: none;}

.local_sch02 .category_btn{margin-right:10px;padding:5px 20px;border:1px solid #111; font-weight:600;}
.local_sch02 .category_btn.active{background:#333;color:white;}
.local_sch02 .con_register_form{position:absolute; background:ghostwhite; border:1px solid #a1a1a1; width:300px; height:250px; z-index:10; padding:15px;display:none}
.con_register_form .inner_box{border-top:1px solid #e1e1e1;border-bottom:1px solid #e1e1e1;padding:15px 0;margin-bottom:15px}
.con_register_form .close{float:right;font-size:30px;display:inline-block;cursor:pointer;padding-right:10px;vertical-align: top;}
.con_register_form.active{display:block}
.con_register_form .inner_box label{display:inline-block;width:30%;}
.con_register_form .inner_box .frm_input{line-height:40px;height:40px;width:60%;border:1px solid #a1a1a1;letter-spacing: -1px;padding-left:10px;font-size:16px;}
.con_register_form .title{width:100%;font-size:20px;}
.con_register_form .btn_submit{margin:0;width:100%;height:40px;}
.head_bar{margin-top:30px;}
.head_bar p {display:inline-block;}

.local_sch02 div{border-bottom:none;}

.local_sch02 div + div{border-top:1px solid #e9e9e9;padding-top:10px;}

.btn{padding:3px 10px;margin: 0;padding: 5px;border: 1px solid #ced9de;background: #f6f9fa;font-size: 12px;cursor: pointer;}
.sys_btn{margin-left:20px;}
i{vertical-align: top;}
</style>



<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function fvisit_submit(act)
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>


<form name="fvisit" id="fvisit" class="local_sch02 local_sch" method="get">
<div>
    <div class="sch_last">
        <strong>기간별검색</strong>
        <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input" size="15" style="width:120px" maxlength="10">
        <label for="fr_date" class="sound_only">시작일</label>
        ~
        <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input" size="15" style="width:120px" maxlength="10">
        <label for="to_date" class="sound_only">종료일</label>
        
    </div>

    <!-- <div class="sch_last" style="margin-left:20px">
        <strong>센터멤버검색</strong>
        <input type="text" name="fr_id" value="<?php echo $fr_id ?>" id="fr_id" class="frm_input" size="15" style="width:120px" maxlength="10">
        <label for="fr_id" class="sound_only">회원아이디</label>
    </div> -->

    <input type="submit" value="검색" class="btn_submit">
    <input type="button" value="엑셀다운로드" class="btn_submit green" id="excel_down"/>
</div>

<div class='bottom_line'>  
    <input type='hidden' name='center' value='<?=$center?>'/>
    <input type='hidden' name='member' value='all'/>
    <!-- <button type='button' class='btn' id='create_new' >+ 신규 등록</button> -->
    <div class='con_register_form'>
        <p class='title'> <i class="ri-add-circle-line" style='vertical-align:middle'></i> 신규 등록<span class='close'><i class="ri-close-line"></i></span></p>

        <div class="inner_box" >
            <div>
            <label for="fr_id" >영어명(ID)</label>
            <input type="text" value="" id="input_eng" class="frm_input" size="15"  maxlength="15">
            </div><div>
            <label for="fr_id" >한글명</label>
            <input type="text" value="" id="input_han" class="frm_input" size="15"  maxlength="15">
            </div>
        </div>
        <input type="button" value="등록" class="btn_submit" id='create_btn'>
    </div>

    | 등록된 센터멤버 :
    <?
        $center_sql = "SELECT * from g5_member WHERE center_use = '1' ";
        $center_result = sql_query($center_sql);
        while($row = sql_fetch_array($center_result)){
            echo "<input type='submit' name='center' class='btn category_btn ".active_check($row['mb_id'],'center')."' value='".$row['mb_id']."' ></input>";
        }
    ?>
</div> 

</form>




<link href="https://cdn.jsdelivr.net/npm/remixicon@2.3.0/fonts/remixicon.css" rel="stylesheet">

<div class="local_desc01 local_desc">
	<p>
		- 센터멤버 선택후 목록보기 클릭시 선택된 센터멤버의 하부 회원리스트 확인가능합니다.<br>
		- 센터멤버는 회원관리 > 회원정보수정에서 지정 가능<br>
	</p>
</div>

<div class='head_bar'>
    <input type='hidden' id='select_id' value='<?=$select_id?>' />
    <?if($select_id){?>
        <p class="labelM title"> <?=$center?>
        <!-- <span class='eng'>(<?=strtoupper($select_id)?>)</span> -->
        의 센터회원 </p>

        <span class='sys_btn'>
        <!-- <p id='content_member' class='btn' >센터회원 목록보기</p> -->
        <!-- <p id='content_modify' class='btn' >변경</p> -->

        <!--  <div class='con_form'>
                <div class="inner_box" >
                    <div>
                    <label for="fr_id" >영어명(ID)</label>
                    <input type="text" value="" id="input_eng" class="frm_input" size="15"  maxlength="15">
                    </div><div>
                    <label for="fr_id" >한글명</label>
                    <input type="text" value="" id="input_han" class="frm_input" size="15"  maxlength="15">
                    </div>
                </div>
                <input type="button" value="등록" class="btn_submit" id='create_btn'>
            </div> -->

            <!-- <p id='content_delete' class='btn' >삭제</p> -->
        </span>
    <?}?>
</div>

<form name="fmemberlist" id="fmemberlist" action="" onsubmit="return fmemberlist_submit(this);" method="post">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" rowspan="2" id="mb_list_chk">
			<label for="chkall" class="sound_only">회원 전체</label>
			<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
		</th>
        <th>회원아이디</th>
        <th>회원가입일</th>
        <th>회원의 추천인</th>
        <th>기간매출(PV)금액</th>
        <th>해쉬</th>
        <th>멤버쉽결제</th>
        <th>센터수당</th>
    </tr>
    </thead>
    <tbody>

    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $order_total_sql = "SELECT sum(upstair) as upstair_total, sum(pv) as pv_total from g5_shop_order WHERE mb_id = '{$row['mb_id']}' AND od_date >= '{$fr_date}' AND od_date <= '{$to_date}' ";
        $order_total = sql_fetch($order_total_sql);
        
        $membership_sql = " SELECT * from g5_shop_order WHERE mb_id = '{$row['mb_id']}' AND od_date >= '{$fr_date}' AND od_date <= '{$to_date}' AND od_cash = 300000 ";
        $membership_yn = sql_fetch($membership_sql)['od_cash'];

        $bg = 'bg'.($i%2);
        $total_hap += $order_total['upstair_total'];
        $total_pv +=  $order_total['pv_total'];
        $center_bonus = $order_total['upstair_total']*0.02;
        $total_center_bonus += $center_bonus ;
        $membership_total += $membership_yn; 
        
    ?>
   
    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_chk" class="td_chk" rowspan="1">
			<input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
		</td>
        <td class='text-center'><?echo "<img src='/img/".$row['mb_level'].".png' style='width:20px;height:20px;margin-right:5px;'>".$row['mb_id']?></td>
        <td class='text-center'><?=$row['mb_open_date']?></td>
        <td class='text-center'><?=$row['mb_recommend']?></td>
        <td class='text-center'><?=Number_format($order_total['upstair_total'])?></td>
        <td class='text-center'><?=Number_format($order_total['pv_total'])?></td>
        <td class='text-center'><?=Number_format($membership_yn)?></td>			
        <td class='text-center'><?=Number_format($center_bonus)?></td>			
    </tr>

    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없거나 센터를 선택해주세요.</td></tr>';
    ?>
    </tbody>

    <tfoot>
        <td></td>
        <td><?=$i?>명</td>
        <td colspan='2'></td>
        <td><?=number_format($total_hap)?><?=$curencys[1]?></td>
        <td><?=number_format($total_pv)?> PV (hash)</td>
        <td><?=number_format($membership_total)?></td>
        <td><?=number_format($total_center_bonus)?><?=$curencys[1]?></td>
    </tfoot>

    </table>
</div>
<div class="btn_list01 btn_list">
	
</div>
</form>

<?php
if (isset($domain))
    $qstr .= "&amp;domain=$domain";
    $qstr .= "&amp;page=";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;

?>



<script>
$(function(){
    $('#content_member').on('click', function(){
        location.href="./bonus.center_member.php?center=<?=$select_id?>&member=all";
    });

    $('#create_new').on('click', function(){
        open_pop();

        $('#create_btn').on('click', function(){

            var input_han = $('#input_han').val();
            var input_eng = $('#input_eng').val();

            if( input_han == '' || input_eng == '' ){
                alert('등록명을 확인해주세요');
            }
            
            $.ajax({
                url: '/adm/adm.contents_proc.php',
                type: 'POST',
                cache: false,
                async: false,
                data: {
                "create_han" : input_han,
                "create_eng" : input_eng,
                "category" : '<?=$code?>',
                "func": 'create'
                },
                dataType: 'json',
                success: function(result) {
                    if(result.code == "0000"){
                        alert('신규생성완료 : \n'+result.sql);
                        location.reload();
                    }else{
                        alert(result.sql);
                    }
                },
                error: function(e){
                    alert('정상처리되지 않았습니다. 문제가 지속되면 관리자에게 연락주세요');
                }
                
            });
        });
        
    });

    $('.con_register_form .close').on('click', open_pop);

    // 변경수정
    $('#content_modify').on('click', function(){
        open_pop();
    });

    // 삭제
    $('#content_delete').on('click', function(){

        var result = confirm('해당 항목을 삭제하시겠습니까?');
        var select_id = $('#select_id').val();

        if(result){
            $.ajax({
                url: '/adm/adm.contents_proc.php',
                type: 'POST',
                data: {
                "select_id" : select_id,
                "category" : '<?=$code?>',
                "func": 'delete'
                },
                dataType: 'json',
                success: function(result) {
                    if(result.code == "0000"){
                        alert('삭제완료 : \n'+result.sql);
                        location.reload();
                    }else{
                        alert(result.sql);
                    }
                },
                error: function(e){
                    alert('정상처리되지 않았습니다. 문제가 지속되면 관리자에게 연락주세요');
                }
                
            });
        }
    });

    function open_pop(){
        var target = $('.con_register_form');
        
        if( target.hasClass('active') ){
            target.removeClass('active');
        }else{
            target.addClass('active');
        }
    }
});
    
$('#excel_down').click(function(){
	window.location.href="/excel/center_excel_down.php?fr_date=<?=$_GET['fr_date']?>&to_date=<?=$_GET['to_date']?>&excel_sql=<?=$excel_sql?>&target=<?=$g5['title']?>";
})
</script>

<?
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>


