$(function () {
    //아코디언
    $("#accordian h3").click(function () {
        $("#accordian ul ul").slideUp();
        $("#accordian h3").removeClass('on')
        if (!$(this).next().is(":visible")) {
            $(this).next().slideDown();
            $(this).addClass('on')
        }
    })
    //통합데이터
    $('.top_bar div').click(function(){
        $('.top_bar div').removeClass('on');
        $(this).addClass('on');
        $('.bottom_con').show();
        $('.top_bar .ud_arrow').addClass('on');
    })
    $('.ud_arrow').click(function(){
        $('.top_bar div').removeClass('on');
        $('.bottom_con .btn button').removeClass('on')
        $('.top_bar .ud_arrow').removeClass('on');
        $('.bottom_con').hide();
    })
    $('.bottom_con .btn button').click(function(){
        $('.bottom_con .btn button').removeClass('on')
        $(this).addClass('on')
    })
    //소재별비교
    $('.compare .left .select_box p').click(function(){
        $('.compare .left .select_box p').removeClass('on')
        $(this).addClass('on')
    })
    $('.compare .right .select_box p').click(function(){
        $('.compare .right .select_box p').removeClass('on')
        $(this).addClass('on')
    })
    //위젯
    $(".widget_set h4").click(function () {
        $('.widget_inner').slideUp(0.01);
        if (!$(this).siblings('.widget_inner').is(":visible")) {
            $(this).siblings('.widget_inner').slideDown(0);
        }
    })
})