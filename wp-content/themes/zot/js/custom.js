$(document).ready(function() {
    $("li:first-child").addClass("first");
    $("li:last-child").addClass("last");
    $('[href="#"]').attr("href", "javascript:;");
    $('.menu-Bar').click(function() {
        $(this).toggleClass('open');
        $('.menuWrap').toggleClass('open');
        $('body').toggleClass('ovr-hiddn');
    });
});

$(window).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll >= 700) {
        $(".menu-sec").addClass("fixed");
    } else {
        $(".menu-sec").removeClass("fixed");
    }
});

$(window).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll >= 200) {
        $(".subHeader").addClass("fixed");
    } else {
        $(".subHeader").removeClass("fixed");
    }
});

$('.index-slider').slick({
    autoplay: true,
    autoplaySpeed: 3900,
    pauseOnHover: true,
    fade: true,
    dots: true,
    arrows: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 825,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            dots: false
        }
    }, ]
});

$('.image-slider').slick({
    dots: false,
    arrows: true,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [{
        breakpoint: 825,
        settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
        }
    }, ]
});

$('[data-targetit]').on('click', function(e) {
    $(this).addClass('current');
    $(this).siblings().removeClass('current');
    var target = $(this).data('targetit');
    $('.' + target).siblings('[class^="box-"]').hide();
    $('.' + target).fadeIn();
});

$('#closeMessage').on('click', function(e) {
    $('.header-top').hide('slow');
});

/* RESPONSIVE JS */
if ($(window).width() < 767) {
    $('.menuSlider').slick({
        dots: false,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 1
    });

    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= 200) {
            $(".menu-sec").addClass("fixed");
        } else {
            $(".menu-sec").removeClass("fixed");
        }
    });
}

$("document").ready(function() {

    $(".test").mouseenter(function() {
        $(this).attr('src', $(this).attr('data-altimg'));
    });
    $(".test").mouseleave(function() {
        $(this).attr('src', $(this).attr('data-main'));
    });
});


$(document).ready(function() {
    $(".pw-eye #eye-pw").on('click', function(event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass("fa-eye-slash");
            $('#show_hide_password i').removeClass("fa-eye");
        } else if ($('#show_hide_password input').attr("type") == "password") {
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass("fa-eye-slash");
            $('#show_hide_password i').addClass("fa-eye");
        }
    });
});


$(document).ready(function() {
    var modal = $(this)
    var titleLogin = "Please sign in to ZOT";
    var titleRegister = "Please sign up for ZOT";
    modal.find('.modal-title').text(titleLogin);
    $('#loginModal').on('show.bs.modal', function() {
        console.log('tetetetetet');
        $('#loginForm').show();
    });

    $('#createAccount').on('click', function() {

        modal.find('.modal-title').text(titleRegister);
        modal.find('.footer-title').text('Already a ZOT shopper?');
        $('#loginForm').hide();
        $('#signInBtn').show();
        $('#createAccountBtn').hide();
        $('#registerForm').fadeIn();
        $('#disclaimer-1').fadeIn();

    })

    $('#loginBtn').on('click', function() {

        modal.find('.modal-title').text(titleRegister);
        modal.find('.footer-title').text('New to ZOT?');
        $('#loginForm').fadeIn();
        $('#signInBtn').hide();
        $('#createAccountBtn').show();
        $('#registerForm').hide();
        $('#disclaimer-1').fadeOut();
    })

});




//login auth
jQuery(document).ready(function($) {

    // Perform AJAX login on form submit
    $('#fire').on('click', function(e) {
        $('form#loginForm p.status').show().text(ajax_login_object.loadingmessage);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_login_object.ajaxurl,
            data: {
                'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
                'username': $('form#loginForm #email').val(),
                'password': $('form#loginForm #password').val(),
                'security': $('form#loginForm #security').val()
            },
            success: function(data) {
                $('form#loginForm p.status').text(data.message);
                if (data.loggedin == true) {
                    document.location.href = ajax_login_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });

    function getData(e) {
        e.preventDefault();
    }



    //zot modal
    var modal = $('.zot-story-modal');

    function closeModal() {

        modal.addClass('hideModal');
    }

    function launchModal(id) {
        //var placeHolder = "https://i.pinimg.com/736x/f1/93/45/f1934510654b541cafd3922d068af974.jpg";

        getPostStories(id);
        $("#b3 span").eq(0).html("<div class=\"filler playAnim\"></div>");

        $('.filler').css('width', '0%');

        //setImage(placeHolder);

        modal.addClass('openModal');
        modal.removeClass('closeIt');
        disableScrolling();
        setTimeout(function() {
            playAnim();
        }, 500)


    }

    $('.closeIt').on('click', function() {
        closeDown();
        closeModal();
        modal.removeClass('openModal');
        enableScrolling();
        removeImage();
        resetAnim();
    })

    function closeDown() {
        hideAllBarType();
        modal.removeClass('openModal');
        enableScrolling();
        removeImage();
        resetAnim();
        $("#b3 span").eq(0).empty();
        $("#b3 span").eq(1).empty();
        $("#b3 span").eq(2).empty();
        $("#b1 span").eq(0).empty();
        window.clearInterval(window.zotInterval);
    }

    $('.play-story').on('click', function() {
        var theId = $(this).attr('id');
        console.log('id', theId);
        launchModal(theId);
    });

    function disableScrolling() {
        $("body, html").css({
            'overflow': 'hidden',
            'height': '100%'
        });
    }

    function enableScrolling() {
        $("body, html").css({
            'overflow': 'auto',
            'overflow-x': 'hidden',
            'height': '100%'
        });
    }

    function playAnim() {
        setTimeout(function() {
            $('.filler').addClass('playAnim');
        }, 500)


    }

    function resetAnim() {
        $('.filler').removeClass('playAnim');
        hideAllBarType();
    }

    function getPostStories(post_id) {
        var storyData = $('#product-' + post_id).attr("data-story");
        var storyArray = storyData.split('|');
        var cleanArray = storyArray.filter(function(v) { return v !== "" });

        var storyCount = cleanArray.length;
        if (storyCount == 1) {
            setProductLink(post_id);
            setImage(cleanArray[0]);
            $("#b1 span").eq(0).html("<div class=\"filler playAnim\"></div>");
            selectBarTypeToShow('b' + storyCount);
            setTimeout(function() {
                closeDown();
            }, 13000);
        } else if (storyCount == 3) {
            selectBarTypeToShow('b' + storyCount);
            setProductLink(post_id);

            setImage(cleanArray[0]);

            var counter = 2;
            window.zotInterval = setInterval(function() {
                counter--
                if (counter == 2) {

                } else if (counter == 1) {
                    setImage(cleanArray[1]);
                    $("#b3 span").eq(1).html("<div class=\"filler playAnim\"></div>");
                } else if (counter == 0) {
                    setImage(cleanArray[2]);
                    $("#b3 span").eq(2).html("<div class=\"filler playAnim\"></div>");
                    setTimeout(function() {
                        closeDown();
                    }, 10000)
                }
            }, 10000);
        }


    }

    function setProductLink(id) {
        var url = $('#url-' + id).attr('href');
        console.log('url', url);
        $('#a-link').attr("href", url);
    }

    function setImage(img) {
        removeImage();
        if (isImage(img)) {
            $('.imageHolder').prepend('<img id="theImg" src="' + img + '" />')
        } else if (!isVideo(img)) {
            $('.imageHolder').prepend('<video autoplay width=\"320\" height=\"240\" src="' + img + '" controls>Your browser does not support the video tag.</video>')
        }

    }

    function removeImage() {
        $('.imageHolder').empty()
    }

    function selectBarTypeToShow(id) {
        $('#' + id).css('display', "block");
        if (id == 'b1') {
            $('#b1 span').css('width', '98%');
        }
    }

    function hideAllBarType() {
        $('#b1, #b2, b3').hide();
    }






    //check file type


    function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
    }

    function isImage(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'jpg':
            case 'gif':
            case 'bmp':
            case 'png':
                //etc
                return true;
        }
        return false;
    }

    function isVideo(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
            case 'm4v':
            case 'avi':
            case 'mpg':
            case 'mp4':
                // etc
                return true;
        }
        return false;
    }



});


$('.loadMore').on('click', function(e) {
    $('.prod-list li').css("display", "inline-block");
});