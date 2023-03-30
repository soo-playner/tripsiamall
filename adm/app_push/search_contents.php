<?
include_once('./_common.php');

$sql = "SELECT * from app_msg WHERE used =1 ";
$result = sql_query($sql);

	for ($i=0; $row=sql_fetch_array($result); $i++) {
	?>
		<div class='rows' >
			<span class="result_contents cabinet" data-id='<?=$row['no']?>'><?=$row['name']?></span>
            <p class='cabinet_inner'>
                <span class='con_title'><?=$row['title']?></span>
                <span class='con_contents'><?=$row['contents']?></span>
                <?if($row['images'] != ''){
                    $img_src = G5_URL.$row['images'];
                    ?>
                    <span class='con_images' style='display:block;margin:10px;'>
                        <img src="<?=$img_src?>" style='max-width:100%;max-height:200px;'>
                    </span>
                <?}?>
                
            </p>
		</div>
	<?}?>
    <div class='rows'>
        <span class="result_contents" data-id='99'>직접입력</span>
        <p class='cabinet_inner'>
            <span class='con_title'><input type='text' class='frm_input' id='con_title' name='con_title' placeholder='타이틀'></span>
            <span class='con_contents'><textArea type='text' class='frm_input textarea' id='con_contents' name='con_contents' placeholder='내용'></textArea></span>
            <span class='guide'>
                {mb_id} <code>: 회원명</code>
            </span>
            
        </p>
    </div>
