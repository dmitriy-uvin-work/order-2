const l_body = $('body');

/*------------------------------ Slides start ------------------------------*/

let bannerSwiperLoop = $('.banner .swiper-slide').length > 1;

if ($('.banner').length > 0) {
    new Swiper('.banner .swiper-container', {
        slidesPerView: 1,
        spaceBetween: 10,
        speed: 1500,
        loop: bannerSwiperLoop,
        autoHeight: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        navigation: {
            nextEl: '.banner-arrow--next',
            prevEl: '.banner-arrow--prev',
        },
    });
}

let stockSwiperLoop = $('.stock-slider .swiper-slide').length > 1;

if ($('.stock-slider').length > 0) {
    new Swiper('.stock-slider .swiper-container', {
        slidesPerView: 1,
        spaceBetween: 10,
        speed: 1000,
        loop: stockSwiperLoop,
        autoHeight: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false
        },
        navigation: {
            nextEl: '.stock-arrow--next',
            prevEl: '.stock-arrow--prev',
        },
    });
}

let productSlider = $('.product-slider');

if (productSlider.length > 0) {
    productSlider.each(function () {
        let _this = $(this),
            prev_arrow = _this.prev().find('.arrows__item--prev'),
            next_arrow = _this.prev().find('.arrows__item--next');

        new Swiper(_this.find('.swiper-container'), {
            slidesPerView: 4,
            spaceBetween: 24,
            navigation: {
                nextEl: next_arrow,
                prevEl: prev_arrow,
            },
            breakpoints: {
                992: {
                    slidesPerView: 3,
                    spaceBetween: 15,
                },
                700: {
                    slidesPerView: 2,
                    spaceBetween: 15,
                }
            }
        });

    });
}

if ($('.product-view').length > 0) {
    let productViewGalleryThumbs = new Swiper('.gallery__thumbs .swiper-container', {
        slidesPerView: 3,
        direction: 'vertical',
        allowTouchMove: false,
    });
    new Swiper('.gallery__main .swiper-container', {
        spaceBetween: 10,
        thumbs: {
            swiper: productViewGalleryThumbs
        }
    });
}

/*------------------------------ Slides end ------------------------------*/

$('.type-password__eye').on('click', function () {
    if ($(this).hasClass('active')) {
        $(this).next().prop('type', 'password')
    } else {
        $(this).next().prop('type', 'text')
    }
    $(this).toggleClass('active');
});

/*------------------------------ UI elements start ------------------------------*/

// accordion
$('.accordion-ui__item').on('click', function () {
    let _this = $(this);
    if (_this.hasClass('collapse')) {
        _this.removeClass('collapse');
        _this.find('.a-body').slideUp();
    } else {
        _this.addClass('collapse');
        _this.find('.a-body').slideDown();
    }
});

// custom scrollbar
$('.scrollY').overlayScrollbars({
    paddingAbsolute: true,
    overflowBehavior: {
        x: "hidden"
    }
});

// popup
l_body.on('click', '.popup-btn', function (e) {
    e.preventDefault();

    let popupClass = $(this).attr('data-class');
    $('.' + popupClass).fadeIn(200).addClass('show');

    switch (popupClass) {
        case 'cart-modal':
            updateCartModal();
            break;
        case 'wish-modal':
            updateWishModal();
    }
});

l_body.on('click', '.right-popup .overlay, .right-popup .right-popup__close', function () {
    let popup = $('.right-popup');
    popup.removeClass('show');
    setTimeout(function () {
        popup.fadeOut(200);
    }, 300);
});

// select2
(function initSelect2() {
    let select2 = $('.select2'),
        select2WithSearch = $('.select2_with_search');

    if (select2.length > 0) {
        select2.select2({
            minimumResultsForSearch: -1,
            placeholder: "Выбрать",
            width: '100%',
            language: {
                noResults: function () {
                    return "ничего не найдено";
                }
            }
        });
    }

    if (select2WithSearch.length > 0) {
        select2WithSearch.select2({
            placeholder: "Выбрать",
            width: '100%'
        });
    }
})();

// слайдер цен
let price_range = $('.price-range-ui');

if (price_range.length > 0) {
    let priceSlider = price_range.find('.price-range-ui__line').get(0),
        input0 = price_range.find('.price-range-ui__input1').get(0),
        input1 = price_range.find('.price-range-ui__input2').get(0),
        inputs = [input0, input1],
        maxPrice = $(input1).attr('data-max');

    noUiSlider.create(priceSlider, {
        start: [$(input0).val(), $(input1).val()], // выбранный диапазон цен
        connect: true,
        step: 50000,
        margin: 100000,
        range: {
            'min': [0],
            'max': [Number(maxPrice)]
        },
        format: wNumb({
            decimals: 0,
            thousand: ' ',
        }),
        tooltips: false
    });

    priceSlider.noUiSlider.on('update', function (values, handle) {
        inputs[handle].value = values[handle];
    });

    // Listen to keydown events on the input field.
    inputs.forEach(function (input, handle) {
        input.addEventListener('change', function () {
            priceSlider.noUiSlider.setHandle(handle, this.value);
        });

        input.addEventListener('keydown', function (e) {

            let values = priceSlider.noUiSlider.get();
            let value = Number(values[handle]);

            // [[handle0_down, handle0_up], [handle1_down, handle1_up]]
            let steps = priceSlider.noUiSlider.steps();

            // [down, up]
            let step = steps[handle];

            let position;

            // 13 is enter,
            // 38 is key up,
            // 40 is key down.
            switch (e.which) {

                case 13:
                    priceSlider.noUiSlider.setHandle(handle, this.value);
                    break;

                case 38:

                    // Get step to go increase slider value (up)
                    position = step[1];

                    // false = no step is set
                    if (position === false) {
                        position = 1;
                    }

                    // null = edge of slider
                    if (position !== null) {
                        priceSlider.noUiSlider.setHandle(handle, value + position);
                    }

                    break;

                case 40:

                    position = step[0];

                    if (position === false) {
                        position = 1;
                    }

                    if (position !== null) {
                        priceSlider.noUiSlider.setHandle(handle, value - position);
                    }

                    break;
            }
        });
    });
}

/*------------------------------ UI elements end ------------------------------*/

(function () {
    $('#mainSearchForm').on('submit', function (e) {
        e.preventDefault();
    });

    let searchModalAjaxContainer = $('.search-modal-ajax');
    let timeout;

    $(document).on('keyup', '#mainSearchForm input', function () {
        let $this = $(this),
            query = $this.val(),
            action = $this.parent().attr('action');

        let loader = searchModalAjaxContainer.find('.loader-ui');

        if(timeout) {
            clearTimeout(timeout);
        }

        if (loader.length < 1) {
            searchModalAjaxContainer.append("<div class='loader-ui loader-ui--triangle'></div>");
        }

        timeout = setTimeout(function() {
            $.ajax({
                url: action,
                method: 'GET',
                data: {query: query},
                dataType: 'json',
                success: function (data) {
                    if (data.html) {
                        searchModalAjaxContainer.empty().append(data.html);
                    }
                },
                error(request, error) {
                    searchModalAjaxContainer.empty()
                }
            })
        }, 2000);
    });
})();

let min_992 = $('#min-width-992').is(":visible");

(function () {
    let categoryDropDownBtn = $('.catalog-drop-btn');
    let categoryDropDown = $('.category-dropDown');
    let categoryDropDownWrap = $('.category-dropDown__wrap');
    let categoryDropDownOverlay = $('.category-dropDown__overlay');
    let timeout_enter;
    let timeout;
    let hover = true;
    let imgBox = $('.img-box');

    if (min_992) {
        categoryDropDownBtn.hover(function () {
            if (timeout_enter) {
                clearTimeout(timeout_enter);
            }
            timeout_enter = setTimeout(function () {
                categoryDropDown.show();
            }, 200);
        }, function () {
            timeout = setTimeout(function () {
                categoryDropDown.hide();
                clearTimeout(timeout_enter);
            }, 100);
        });

        categoryDropDownWrap.hover(function () {
            clearTimeout(timeout);
        }, function () {
            categoryDropDown.hide();
        });

        categoryDropDownOverlay.hover(function () {
            clearTimeout(timeout);
        });

        categoryDropDown.find('ul li').hover(function (e) {

            if (hover) {
                hover = false;

                let nav = $(this);

                nav.parent().find('.secondary-ul').hide();
                nav.parent().find('.active-nav').removeClass('active-nav');

                nav.addClass('active-nav');

                let dropDown = $(this).find('.secondary-ul');

                let currImg = imgBox.find('[data-category-id="'+nav.attr('data-id')+'"]');

                imgBox.find('.active').removeClass('active');

                if (!(currImg.length > 0)) {
                    currImg = imgBox.find('[data-category-id="'+nav.parent().parent().attr('data-id')+'"]');
                }

                currImg.addClass('active');


                if (dropDown.length > 0) {
                    dropDown.first().show();

                    let maxHeight = Math.max.apply(null, categoryDropDownWrap.find('ul').map(function ()
                    {
                        if ($(this).is(":visible")) {
                            return $(this).height();
                        }
                    }).get());
                    categoryDropDownWrap.css('height', maxHeight+160+'px')
                }

                setTimeout(function () {
                    hover = true;
                }, 100);
            }
        }, function () {
            hover = true
        });
    }

    if (!min_992) {
        categoryDropDownBtn.on('click', function (e) {
            e.preventDefault();

            categoryDropDown.fadeIn(100, function () {
                categoryDropDown.addClass('show');
            })
        });

        categoryDropDown.find('li.has-child > a').on('click', function (e) {
            e.preventDefault();

            let dropDown = $(this).parent().find('.secondary-ul');

            dropDown.first().fadeIn(100, function () {
                dropDown.addClass('show');
            })
        });

        categoryDropDown.find('span').on('click', function (e) {
            e.preventDefault();

            let dropDown = $(this).parent().parent();

            if (dropDown.hasClass('main-ul')) {
                categoryDropDown.removeClass('show');
                setTimeout(function () {
                    categoryDropDown.hide();
                }, 300);
            } else {
                dropDown.removeClass('show');
                setTimeout(function () {
                    dropDown.hide();
                }, 300);
            }
        });
    }
})();


(function () {
    let mobileMenu = $('.mobile-menu');
    let categoryDropDown = $('.category-dropDown');

    $('.menu-btn').on('click', function (e) {
        e.preventDefault();

        if (mobileMenu.is(":hidden")) {
            mobileMenu.fadeIn(200, function () {
                mobileMenu.addClass('show');
            })
        } else {
            categoryDropDown.removeClass('show').hide();
            categoryDropDown.find('.show').removeClass('show').hide();

            mobileMenu.removeClass('show').hide();
        }
    });

    $('.mobile-menu__overlay').on('click', function (e) {
        e.preventDefault();

        categoryDropDown.removeClass('show').hide();
        categoryDropDown.find('.show').removeClass('show').hide();

        mobileMenu.removeClass('show').hide();
    });
})();
