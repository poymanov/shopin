$(function() {

    $('a.picture').Chocolat();

    addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }

    jQuery('.starbox').each(function() {
        var starbox = jQuery(this);
        starbox.starbox({
            average: starbox.attr('data-start-value'),
            changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
            ghosting: starbox.hasClass('ghosting'),
            autoUpdateAverage: starbox.hasClass('autoupdate'),
            buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
            stars: starbox.attr('data-star-count') || 5
        }).bind('starbox-value-changed', function(event, value) {
            if(starbox.hasClass('random')) {
                var val = Math.random();
                starbox.next().text(' '+val);
                return val;
            }
        })
    });

    $('.popup-with-zoom-anim').magnificPopup({
        type: 'inline',
        fixedContentPos: false,
        fixedBgPos: true,
        overflowY: 'auto',
        closeBtnInside: true,
        preloader: false,
        midClick: true,
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in'
    });

    $('.value-plus').on('click', function(){
        var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)+1;
        divUpd.text(newVal);
    });

    $('.value-minus').on('click', function(){
        var divUpd = $(this).parent().find('.value'), newVal = parseInt(divUpd.text(), 10)-1;
        if(newVal>=1) divUpd.text(newVal);
    });

    $('.flexslider').flexslider({
        animation: "slide",
        controlNav: "thumbnails"
    });

});
