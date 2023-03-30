
<style type="text/css">

.sub_status_bar {height:30px;line-height:30px;background-color:#485461;color:#fff;font-size:14px;}
.mGrp {padding:5px;max-width:620px;    margin: 100px auto 10px;}
</style>
<? if ($unq['id'] == "login") { ?>
<style type="text/css">
.blank {height:10vh;}
</style>
<? } ?>
<div class="sub_status_bar">

	<!-- <div class="Grp">
	<?=($unq['name'])?$unq['name']:$config['cf_title']?>
	</div> -->
	<!-- // Grp -->

</div><!-- // sub_status_bar -->
<p class="blk blank"></p>
<? if ($unq['pcode'] == "00") { ?><p class="blk" style="height:5px;"></p><? } ?>
<div class="<?=($unq['pcode'] == "00")?"m":""?>Grp">

