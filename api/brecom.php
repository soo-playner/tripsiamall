<?php
include_once('./_common.php');
// include_once(G5_ADMIN_PATH.'/bonus_inc.php');
$title="zetabyte :: API - 후원산하검색";
include_once('./header.php');
include_once(G5_PATH.'/util/recommend.php');


$matching_lvl = $_REQUEST['mb_layer'];
$member = $_REQUEST['mb_id'];
if(!isset($_REQUEST['mb_where'])){
    echo '미지정'   ;
}else{
    $where = $_REQUEST['mb_where'];
}

?>
<link rel="stylesheet" href="./api.css">

<h2 class='title blue'>후원산하검색</h2>
<div class='inpuset'>
    <form action="./brecom.php" method="POST">
        <input type='text' name='mb_id' class='mb_input ' placeholder="검색할 회원아이디">
        <input type='text' name='mb_layer' class='mb_input small' placeholder="검색할 단계">
        <input type='text' name='mb_where' class='mb_input' placeholder="제타1 / 제타플러스2">
        <input type='submit' value='검색' class='mb_submit'>
    </form>
</div>

<?
if($member){ 
?>

<div id='container'>
<div class="mb_name">검색회원 : <?=$member?> | <?=$matching_lvl?> 단계 | 
<?if($where == 2){
    echo "제타플러스(조직도2)";
}else{
    echo "제타(조직도1)";
}?>
</div>

<?}?>

<?
// 추천트리 하부 
$mem_list =[];

$html = "
<li class='title'>
    <span class='header'>회원</span>
    <span class='layer'>대수</span>
    <span class='price'>매출금액</span>
    <span class='rate'>보유해시</span>
</li>
";

function  excute($mb,$category){
    global $matching_lvl,$html,$where;

    $sql = "SELECT * FROM g5_member WHERE mb_id ='{$mb}' ";
    $result = sql_fetch($sql);  

    if($result){
        if($category == 'all'){
            
            list($mem_result_l,$mem_result_r) = return_brecommend($mb,$matching_lvl,false,$where);

            $cnt_l =count($mem_result_l);
            $brecom_info_l_hash = array_int_sum($mem_result_l,'mb_rate','int');
            $brecom_info_l_sales = array_int_sum($mem_result_l,'mb_save_point','int');

            $cnt_r =count($mem_result_r);
            $brecom_info_r_hash = array_int_sum($mem_result_r,'mb_rate','int');
            $brecom_info_r_sales = array_int_sum($mem_result_r,'mb_save_point','int');

            $brecom_info_lr_hash = $brecom_info_l_hash + $brecom_info_r_hash;
            $brecom_info_lr_sales = $brecom_info_l_sales + $brecom_info_r_sales;
            
            echo "<div class='box'>";
            echo "총매출 : <span class='f_blue'>". Number_format($brecom_info_lr_sales);
            echo "</span>        |       <span class='f_red'>총해시 : ". Number_format($brecom_info_lr_hash);
            echo "</span></div>";

            echo "<div class='dual left'><h3>L : </h3>";
            echo $html;
            $i = 0;
            while($i < count($mem_result_l)){
                $left = $mem_result_l[$i];
                
                
                echo "<li>";
                echo "<span class='header'><a href='./sales.php?mb_id='".$left['mb_id']."'>".$left['mb_id']."</a></span>";
                echo "<span class='layer'>".$left['count']."</span>";
                echo "<span class='price'>".number_format($left['mb_save_point'])."</span>";
                echo "<span class='rate'>".number_format($left['mb_rate'])."</span>";
                echo "</li>";
                $i++;
            }
            echo "<li class='li_foot'>";
            echo "<span class='header'>".$cnt_l." 명</span>";
            echo "<span class='layer'></span>";
            echo "<span class='price'>".number_format($brecom_info_l_sales)."</span>";
            echo "<span class='rate'>".number_format($brecom_info_l_hash)."</span>";
            echo "</li>";
            echo "</div>";



            echo "<div class='dual right'><h3>R : </h3>";
            echo $html;

            $j = 0;
            while($j < count($mem_result_r)){
                $right = $mem_result_r[$j];
                
                echo "<li>";
                echo "<span class='header'><a href='./brecom.php?mb_id='".$right['mb_id']."'>".$right['mb_id']."</a></span>";
                echo "<span class='layer'>".$right['count']."</span>";
                echo "<span class='price'>".number_format($right['mb_save_point'])."</span>";
                echo "<span class='rate'>".number_format($right['mb_rate'])."</span>";
                echo "</li>";
                $j++;
            }
            echo "<li class='li_foot'>";
            echo "<span class='header'>".$cnt_r." 명</span>";
            echo "<span class='layer'></span>";
            echo "<span class='price'>".number_format($brecom_info_r_sales)."</span>";
            echo "<span class='rate'>".number_format($brecom_info_r_hash)."</span>";
            echo "</li>";
            echo "</div>";


        }
    }else{
        echo "해당회원이 없습니다";
    }
}


// array_column 5.4 대응
if( !function_exists( 'array_column' ) ):
    function array_column( array $input, $column_key, $index_key = null ) {
        $result = array();
        foreach( $input as $k => $v )
            $result[ $index_key ? $v[ $index_key ] : $k ] = $v[ $column_key ];
        
        return $result;
    }
    
endif;

if($member){
    excute($member,'all');
}
?>





