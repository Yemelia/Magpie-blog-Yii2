/* ---------------------------------------------
 common scripts
 --------------------------------------------- */
;(function ($) {
    'use strict'; // use strict to start

    /* === Stickit === */

    (function () {
        $("[data-sticky_column]").stickit({
            scope: StickScope.Parent,
            top: 0
        });
    }());



    /*=== single blog carousel =====*/
    (function () {
        $('.items').owlCarousel({
            items: 3,
            autoPlay: true,
            pagination: false
        });
    }());

    /* === Instagram Widget === */

    (function () {
        $('#widget-feature').owlCarousel({
            singleItem: true,
            navigation: true,
            navigationText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            autoPlay: true,
            pagination: false
        });
    }());

    /* === Back To Top === */

    (function () {
        $(' a.back-to-top').click(function () {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
    }());


    /* === Footer Instagram === */

    (function () {
        $('#footer-instagram').owlCarousel({
            items: 8,
            navigation: false,
            autoPlay: false,
            pagination: false
        });
    }());
    /* === Search === */

    (function () {
        $('.top-search a').on('click', function (e) {
            e.preventDefault();
            $('.show-search').slideToggle('fast');
            $('.top-search a').toggleClass('sactive');
        });
    }());

    /* === Article Rate === */

    (function () {
        $('#article-rate').raty({
        });
    }());

    (function () {
        $('#article-rate img').on('click', function (e) {
            var rate = $(this).attr('alt');
            var id = getUrlVars()['id'];
            $.ajax({
                url: '/ajax/add-rate',
                type: 'post',
                dataType: 'json',
                data: {'rate' : rate, 'article_id' : id},
                success:function (data) {
                    currentRate(data['rate']);
                }
            });
        });
    }());

    function currentRate(rate) {
        $('#article-rate').raty({
            readOnly: true,
            score: rate
        });
    }

    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }

})(jQuery);


