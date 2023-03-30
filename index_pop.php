
<style>
.pop-layer{
	position:absolute;
	z-index:10000;
}
.container{
	position:relative;
	z-index:1;
}
.pop-layer .pop-container {
  padding: 20px 25px;
  
  height:100%;
}
.pop-conts{
	width:100%;
	height:100%;
}
 
.pop-layer p.title {
  color: #fff;
  line-height: 40px;
  background-color:#3571B5;
  text-align:center;
  font-size:1.2em;
}
 
.pop-layer p.ctxt {
  color: #666;
  padding: 5px;
}
 
.pop-layer .btn-r {
  width: 100%;
  margin: 10px 0 20px;
  padding-top: 10px;
  border-top: 1px solid #DDD;
  text-align: right;
}
 
.pop-layer {
  display: none;
  position: absolute;
  top: 50%;
  left: 50%;
  width: 80%;
  height: auto;
  background-color: #fff;
  border: 5px solid #3571B5;
  z-index: 10;
}
 
.dim-layer {
  display: none;
  position: fixed;
  _position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 100;
}
 
.dim-layer .dimBg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #000;
  opacity: .5;
  filter: alpha(opacity=50);
}
 
.dim-layer .pop-layer {
  display: block;
}
 
a.btn-layerClose {
  display: inline-block;
  height: 25px;
  padding: 0 14px 0;
  border: 1px solid #304a8a;
  background-color: #3f5a9d;
  font-size: 13px;
  color: #fff;
  line-height: 25px;
}
 
a.btn-layerClose:hover {
  border: 1px solid #091940;
  background-color: #1f326a;
  color: #fff;
}
#pop-header img{width:auto;height:150px;}
hr{width:95%;border-top-style:dashed}
.title p span{}
</style>
  
<div class="dim-layer" style="display: block;">
<div class="dimBg">
</div>
<div class="pop-layer" id="notice_layer" style="margin-top: -247px; margin-left: -500px;">
<div class="pop-container">
<div class="pop-conts">
<!--content //-->
<br>
	<div id='pop-header'style="text-align:center;margin:-30px auto -10px;height:150px">
		<img src="<?=G5_THEME_URL?>/img/logo.png" >
	</div>
<hr>


<div class="title" style="display:table;width:100%;height:200px;">
	<div style="display:table-row">
		<div style="display:table-cell;vertical-align: middle;text-align:center;font-family:''">
			<h2><?=$nw['nw_subject']?></h2>
			<br>
			<?=$nw['nw_contents_html']?>
		</div>
	</div>
</div>

<!--
<div class="btn-r">
	<a class="btn-layerClose" href="https://www.blogger.com/blogger.g?blogID=7732836231814138589#">오늘 하루 이 창을 열지 않음</a>
</div>
-->
<!--// content-->
            </div>
</div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.1.1.min.js" type="text/javascript"></script>   
<script>
  
 function setCookie(name, value, expiredays){
  var todayDate = new Date();
   todayDate.setDate (todayDate.getDate() + expiredays);
   document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";";
 }
 
  
 $(function(){
   
  cookiedata = document.cookie;
   
  if(cookiedata.indexOf('notice_layer=done') < 0){  //오늘 하루 이 창을 열지 않음 클릭 체크
       layer_popup("#notice_layer");
  }
          
  }); 
     
    function layer_popup(el){
 
        var $el = $(el);        //레이어의 id를 $el 변수에 저장
        var isDim = $el.prev().hasClass('dimBg');   //dimmed 레이어를 감지하기 위한 boolean 변수
 
        isDim ? $('.dim-layer').fadeIn() : $el.fadeIn();
 
        var $elWidth = ~~($el.outerWidth()),
            $elHeight = ~~($el.outerHeight()),
            docWidth = $(document).width(),
            docHeight = $(document).height();
 
        // 화면의 중앙에 레이어를 띄운다.
        if ($elHeight < docHeight || $elWidth < docWidth) {
            $el.css({
                marginTop: -$elHeight /2,
                marginLeft: -$elWidth/2
            })
        } else {
            $el.css({top: 0, left: 0});
        }
 
        $el.find('a.btn-layerClose').click(function(){
          setCookie("notice_layer", "done", 1);
            isDim ? $('.dim-layer').fadeOut() : $el.fadeOut(); // 닫기 버튼을 클릭하면 레이어가 닫힌다.
            return false;
        });
 
        $('.layer .dimBg').click(function(){
            $('.dim-layer').fadeOut();
            return false;
        });
 
    }  
    
</script>  