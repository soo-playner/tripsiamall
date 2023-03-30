<?include('../new/mypage_head.php')?>
<script src="js/moment.js"></script>
<script src="js/moment-timezone.js"></script>
<script src="js/moment-timezone-with-data-2012-2022.js"></script>
<script>
	var clocks = document.getElementsByClassName("clock");
		
	var dt= moment().tz("America/Los_Angeles").hours(24).minutes(0).second(0);

	var countDownDate = dt.valueOf();

	function updateClocks() {
		var now = moment().tz("America/Los_Angeles").valueOf();
		var distance = countDownDate - now;

		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		clocks[0].textContent = hours + " : "+ minutes + " : " + seconds;
		clocks[1].textContent = hours + " : "+ minutes + " : " + seconds;
		if (distance < 0) {
			clearInterval(x);
			clocks[0].textContent = "EXPIRED";
			clocks[1].textContent = "EXPIRED";
		}
	}

	// Update every minute:
	var x = setInterval(updateClocks, 1000);
	updateClocks();

	function recommendRegister(){
		var specs = "left=10,top=10,width=500,height=800";
		specs += ",toolbar=no,menubar=no,status=no,scrollbars=no,resizable=no";
		window.open("/shop/recommend_register.php?now_id=<?=$member['mb_id']?>", "recommend_register", specs);
	}

	var menu_dropdown = document.getElementsByClassName('menu-dropdown');

	for (var i = 0; i < menu_dropdown.length; i++) {
		menu_dropdown[i].onclick = function() {
			this.classList.toggle('is_open');

			var menu_item = this.nextElementSibling;

			if (menu_item.style.maxHeight) {
				menu_item.style.maxHeight = null;
			} else {
				menu_item.style.maxHeight = menu_item.scrollHeight + "px";
			}
		}
	}

jQuery(document).ready(function($) {
  var alterClass = function() {
    var ww = document.body.clientWidth;
    if (ww >= 1000) {
      $('#side-menu').addClass('side-menu-open');
      $('#body-wrapper').addClass('nav-body-shift');
    } else if (ww < 1000) {
    	$('#side-menu').removeClass('side-menu-open');
    	$('#body-wrapper').removeClass('nav-body-shift');
    }
  };
  alterClass();
});

function toggleSideMenu() {
	document.getElementById('side-menu').classList.toggle('side-menu-open');
	document.getElementById('side-menu').classList.toggle('shadow');

	if (document.body.clientWidth >= 1000) {
		document.getElementById('body-wrapper').classList.toggle('nav-body-shift');		
	}
}

</script>
	<script>
		var token = <? echo $result;?>;
		function copyURL(){
			//$('#url').val("http://www.pinnaclemining.net/bbs/register_form.php?mb_recommend=<? echo $member['mb_id'];?>").show();
			$('#url').val(token.result.url).show();
			document.getElementById("url").select();
			document.execCommand("copy");
		}
	</script>


<style type="text/css">
header{display:none;}
.sub_status_bar {display:none;height:30px;line-height:30px;background-color:#485461;color:#fff;font-size:14px;}
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
<div class="<?=($unq['pcode'] == "00")?"m":""?>Grp" id="body-wrapper">

