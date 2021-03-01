<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,user-scalable=no,initial-scale=1,maximum-scale=1,minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $settings->meta_title }}</title>
    <link rel="icon" type="image/ico" href="/frontend/images/favicon.png" sizes="32x32">
    <link rel="stylesheet" href="/frontend/styles/vendor.css">
    <link rel="stylesheet" href="/frontend/styles/fonts.css">
    <link rel="stylesheet" href="/frontend/styles/alert.css">
    <link rel="stylesheet" href="/frontend/styles/main.css">
    <link rel="stylesheet" href="/frontend/styles/new.css">
    @yield('styles')
    @yield('css')
</head>
<body class="{{ auth()->check() ? 'isUser' : '' }}">

<div class="right-popup cart-modal">
    <div class="overlay overlay--black"></div>
    <div class="right-popup__caption scrollY">
        <div class="right-popup__caption-wrap">

        </div>
    </div>
</div>

<div class="right-popup wish-modal">
    <div class="overlay overlay--black"></div>
    <div class="right-popup__caption scrollY">
        <div class="right-popup__caption-wrap">

        </div>
    </div>
</div>

<div class="right-popup search-modal">
    <div class="overlay overlay--black"></div>
    <div class="right-popup__caption scrollY">
        <div class="right-popup__caption-wrap">
            <button class="right-popup__close"></button>
            <div class="medium-container">
                <div class="search-modal">
                    <div class="main-search">
                        <form action="{{ route('search.result') }}" id="mainSearchForm">
                            <input type="text" name="query" placeholder="Поиск по товарам" title="" class="search-input" autocomplete="off">
                            <button></button>
                        </form>
                    </div>
                </div>
                <div class="right-popup__body">
                    <div class="search-modal-ajax">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="min-width-992"></div>

<div class="page-wrapper">
    @include('frontend.layout.header')
    <div class="page-content">
        @yield('section')
    </div>
    @include('frontend.layout.footer')
</div>

<script src="/frontend/scripts/jquery.js"></script>
<script src="/frontend/scripts/vendor.js"></script>
<script src="/frontend/scripts/main.js"></script>

@include('frontend.components.alert')

<script>
    function ajaxErrorMessage(error) {
        if (typeof error.responseJSON === 'object' && error.responseJSON !== null) {
            if (typeof error.responseJSON.message !== 'undefined') {
                ajaxAlert('warning', error.responseJSON.message)
            }
            if (typeof error.responseJSON.error !== 'undefined') {
                ajaxAlert('error', error.responseJSON.error)
            }
        }
    }
</script>

@include('frontend.components.cart-js')

@yield('scripts')
@yield('js')
@stack('scripts')

</body>
</html>
