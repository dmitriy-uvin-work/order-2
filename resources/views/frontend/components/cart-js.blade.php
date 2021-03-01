<script>
    const body = $('body');

    const isUser = body.hasClass('isUser');

    const storage = {
        get: (key) => {
            return (window.localStorage && window.localStorage.getItem(key)) || null;
        },
        set: (key, value) => {
            if (!value || value.length <= 0) {
                return;
            }
            if (window.localStorage) {
                window.localStorage.setItem(key, value);
            }
        },
        remove: (key) => {
            if (window.localStorage && window.localStorage[key]) {
                window.localStorage.removeItem(key);
                return true;
            }
        },
    };

    /*-------------------------------------------------------------------------------------
        Purchasing JS
    -------------------------------------------------------------------------------------*/
    function initSelect2() {
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
            }).on('change', function () {
                if ($(this).attr('name') === 'delivery_region') {
                    getPurchasePage();
                }
            });
        }

        if (select2WithSearch.length > 0) {
            select2WithSearch.select2({
                placeholder: "Выбрать",
                width: '100%'
            });
        }
    }

    let purchasePage = $('.purchasing-page'),
        isPurchasePage = purchasePage.length > 0;

    if (isPurchasePage) {
        function getPurchasePage() {
            let localCart = JSON.parse(storage.get('beauty_cart')) ? JSON.parse(storage.get('beauty_cart')) : [];

            $('.is-ajax-wrap').append("<div class='loader-ui loader-ui--triangle'></div>");

            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('profile.purchasing') }}",
                method: 'get',
                data: {cart: localCart, old: $('#old').val(), form: JSON.stringify($('#PurchaseForm').serializeArray())},
                dataType: 'json',
                success: function (data) {
                    if (data.content) {
                        purchasePage.find('.content-ajax').empty().append(data.content);
                        initSelect2();
                    }
                    if (data.sidebar) {
                        purchasePage.find('.sidebar-ajax').empty().append(data.sidebar);
                    }
                    $('#old').val('');
                },
                error: function (error) {
                    ajaxErrorMessage(error);
                    $('#old').val('');
                }
            });
        }

        getPurchasePage();

        function getUDSDiscount()
        {
            let promoCode = $('[name="uds_code"]').val(),
                total = $('input[name="net_price"]').val();
            if (promoCode) {
                if (Number(total) !== 0 ) {
                    getPurchasePage();
                } else {
                    ajaxAlert('success', 'Бонусы не списываются и не начисляются при оплате акционного товара, товара со скидкой');
                }
            }
        }

        body.on('change', 'input[name="delivery_type"]', function () {
            let value = Number($(this).val());
            $('[data-status="delivery_type"]').show().filter(function () {
                return value === 2
            }).hide();
        });

        body.on('click', '#getUDSDiscount', function (e) {
            e.preventDefault();
            getUDSDiscount();
        });
    }

    /*-------------------------------------------------------------------------------------
        AddToCart JS
    -------------------------------------------------------------------------------------*/

    body.on('click', '.addToCart', function () {
        let _this = $(this);
        let product_id = _this.parent().attr('data-id');
        let quantity = 1;


        if (_this.hasClass('addToCartWithQuantity')) {
            quantity = Number(_this.parent().find('.quantity-switch__value').text());
        }

        addToCart(product_id, quantity);
    });

    body.on('click', '.destroyCart', function () {
        let _this = $(this);
        let product_id = _this.parent().attr('data-id');

        destroyCart(product_id);
    });

    function addToCart(product_id, quantity) {
        let localCart = JSON.parse(storage.get('beauty_cart')) ? JSON.parse(storage.get('beauty_cart')) : [];

        let hasCart = localCart.find(i => i.product_id === product_id);

        let total_q = hasCart ? hasCart.quantity + quantity : quantity;

        addToCartAjax(product_id, total_q).done(function () {
            let newLocalCart = [];

            if (localCart) {
                if (hasCart) {
                    let newArrayWithoutProduct = localCart.filter(i => i.product_id !== hasCart.product_id);
                    newLocalCart = [...newArrayWithoutProduct, {
                        product_id: product_id,
                        quantity: total_q
                    }]
                } else {
                    newLocalCart = [...localCart, {product_id: product_id, quantity: total_q}]
                }
            } else {
                newLocalCart = [{product_id: product_id, quantity: total_q}]
            }

            storage.set('beauty_cart', JSON.stringify(newLocalCart));

            ajaxAlert('success', 'Успешно добавлен в корзину!');

        }).fail(function (error) {
            ajaxErrorMessage(error);
        });
    }

    function addToCartAjax(product_id, quantity) {
        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('cart.attach') }}",
            method: 'POST',
            data: {product_id: product_id, quantity: quantity},
            dataType: 'json',
        });
    }

    function updateCartModal()
    {
        let localCart = JSON.parse(storage.get('beauty_cart')) ? JSON.parse(storage.get('beauty_cart')) : [];
        let cartModal = $('.cart-modal');

        cartModal.find('.right-popup__caption-wrap').append("<div class='loader-ui loader-ui--triangle'></div>");

        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('cart.list') }}",
            method: 'get',
            data: {cart: localCart},
            dataType: 'json',
            success: function (data) {
                if (data.view) {
                    setTimeout(function () {
                        cartModal.find('.right-popup__caption-wrap').empty().append(data.view);
                        cartModal.find('.scrollY').overlayScrollbars({
                            paddingAbsolute: true,
                            overflowBehavior: {
                                x: "hidden"
                            }
                        });
                    }, 500);
                }
            },
            error: function (error) {
                ajaxErrorMessage(error);
            }
        });
    }

    function destroyCart(product_id) {
        if (isUser) {
            destroyCartAjax(product_id).done(function () {

                destroyCartFromLocale(product_id);

            }).fail(function (error) {
                ajaxErrorMessage(error);
            });
        } else {
            destroyCartFromLocale(product_id);
        }
    }

    function destroyCartFromLocale(product_id)
    {
        let localWish = JSON.parse(storage.get('beauty_cart')) ? JSON.parse(storage.get('beauty_cart')) : [];

        let newLocalWish = localWish.filter(i => Number(i.product_id) !== Number(product_id));
        storage.set('beauty_cart', JSON.stringify(newLocalWish));

        $(`.cart-modal .product-cart-${product_id}`).remove();

        if (isPurchasePage) {
            getPurchasePage();
        }

        if (body.find('.cart-modal').hasClass('show')) {
            updateCartModal();
        }
    }

    function destroyCartAjax(product_id)
    {
        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('cart.detach') }}",
            method: 'POST',
            data: {product_id: product_id},
            dataType: 'json'
        });
    }

    /*-------------------------------------------------------------------------------------
        Quantity UI JS
    -------------------------------------------------------------------------------------*/
    body.on('click', '.quantity-switch__nav', function () {
        let nav = $(this),
            inp = nav.parent().find('.quantity-switch__value'),
            quantity = Number(inp.text()),
            maxV = Number(inp.attr('data-max-value')),
            type = nav.attr('data-type');

        let product_id = nav.parent().attr('data-id');

        if (product_id) {
            if (type === 'minus' && quantity > 1) {
                quantity -= 1;
            } else if (type === 'plus' && quantity < maxV) {
                quantity += 1;
            } else {
                return false;
            }
            updateCartAjax(product_id, quantity).done(function () {
                let localCart = JSON.parse(storage.get('beauty_cart')) ? JSON.parse(storage.get('beauty_cart')) : [];
                let newLocalCart = [];
                let hasCart = localCart.find(i => i.product_id === product_id);

                if (localCart) {
                    if (hasCart) {
                        let newArrayWithoutProduct = localCart.filter(i => i.product_id !== hasCart.product_id);
                        newLocalCart = [...newArrayWithoutProduct, {
                            product_id: product_id,
                            quantity: quantity
                        }]
                    } else {
                        newLocalCart = [...localCart, {product_id: product_id, quantity: quantity}]
                    }
                } else {
                    newLocalCart = [{product_id: product_id, quantity: quantity}]
                }

                storage.set('beauty_cart', JSON.stringify(newLocalCart));

                inp.text(quantity);

                if (isPurchasePage) {
                    getPurchasePage();
                }

                if ($('.cart-modal').hasClass('show')) {
                    updateCartModal();
                }

            }).fail(function (error) {
                ajaxErrorMessage(error);
            });
        } else {
            if (type === 'minus' && quantity > 1) {
                quantity -= 1;
                inp.text(quantity);
            } else if (type === 'plus' && quantity < maxV) {
                quantity += 1;
                inp.text(quantity);
            }
        }
    });

    function updateCartAjax(product_id, quantity)
    {
        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('cart.attach') }}",
            method: 'POST',
            data: {product_id: product_id, quantity: quantity},
            dataType: 'json'
        });
    }

    /*-------------------------------------------------------------------------------------
        AddToWish JS
    -------------------------------------------------------------------------------------*/

    body.on('click', '.addToWish', function () {
        let _this = $(this);
        let product_id = _this.parent().attr('data-id');

        addToWish(product_id);
    });

    body.on('click', '.destroyWish', function () {
        let _this = $(this);
        let product_id = _this.parent().attr('data-id');

        destroyWish(product_id);
    });

    function addToWish(product_id) {
        let localWish = JSON.parse(storage.get('beauty_wish')) ? JSON.parse(storage.get('beauty_wish')) : [];

        let hasWish = localWish.find(i => i.product_id === product_id);

        addToWishAjax(product_id).done(function () {

            let newLocalWish = [];

            if (localWish) {
                if (!hasWish) {
                    newLocalWish = [...localWish, {product_id: product_id}]
                } else {
                    newLocalWish = localWish;
                }
            } else {
                newLocalWish = [{product_id: product_id}]
            }

            storage.set('beauty_wish', JSON.stringify(newLocalWish));

            ajaxAlert('success', 'Успешно добавлен в избранное!')

        }).fail(function (error) {
            ajaxErrorMessage(error);
        });
    }

    function addToWishAjax(product_id) {
        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('favorite.attach') }}",
            method: 'POST',
            data: {product_id: product_id},
            dataType: 'json'
        });
    }

    function updateWishModal()
    {
        let localWish = JSON.parse(storage.get('beauty_wish')) ? JSON.parse(storage.get('beauty_wish')) : [];
        let wishModal = $('.wish-modal');

        wishModal.find('.right-popup__caption-wrap').append("<div class='loader-ui loader-ui--triangle'></div>");

        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('favorite.list') }}",
            method: 'get',
            data: {wishlist: localWish},
            dataType: 'json',
            success: function (data) {
                if (data.view) {
                    setTimeout(function () {
                        wishModal.find('.right-popup__caption-wrap').empty().append(data.view);
                        wishModal.find('.scrollY').overlayScrollbars({
                            paddingAbsolute: true,
                            overflowBehavior: {
                                x: "hidden"
                            }
                        });
                    }, 500);
                }
            },
            error: function (error) {
                ajaxErrorMessage(error);
            }
        });
    }

    function destroyWish(product_id) {
        if (isUser) {
            destroyWishAjax(product_id).done(function () {

                destroyWishFromLocale(product_id);

            }).fail(function (error) {
                ajaxErrorMessage(error);
            });
        } else {
            destroyWishFromLocale(product_id);
        }
    }

    function destroyWishFromLocale(product_id)
    {
        let localWish = JSON.parse(storage.get('beauty_wish')) ? JSON.parse(storage.get('beauty_wish')) : [];

        let newLocalWish = localWish.filter(i => Number(i.product_id) !== Number(product_id));
        storage.set('beauty_wish', JSON.stringify(newLocalWish));

        $(`.product-wish-${product_id}`).remove();
    }

    function destroyWishAjax(product_id)
    {
        return $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('favorite.detach') }}",
            method: 'POST',
            data: {product_id: product_id},
            dataType: 'json'
        });
    }

</script>
