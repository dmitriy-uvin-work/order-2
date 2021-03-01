@extends('frontend.layout.master')

@section('section')
    @if(count($banners) > 0)
    <div class="banner">
        <div class="swiper-container">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide" style="background-image: url({{ $banner->getImg('image', 'large') }})">
                        <div class="medium-container">
                            <div class="banner__note">{{ $banner->note }}</div>
                            <div class="banner__title mt-30">{{ $banner->title }}</div>
                            @if (!empty($banner->link))
                                <div class="mt-40"><a href="{{ $banner->link }}" class="btn btn--black">{{ $banner->btn_text }}</a></div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="banner-arrows">
                <div class="fluid-container">
                    <div class="d-flex justify-content-between">
                        <div class="banner-arrow banner-arrow--prev"></div>
                        <div class="banner-arrow banner-arrow--next"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(count($submenu))
        <div class="submenu">
            <div class="medium-container">
                <div class="submenu-grid">
                    @foreach($submenu as $menu)
                        <a href="#" class="submenu-grid__item">
                            <img src="{{ $menu->getImg('image', 'thumb') }}" alt="">
                            <span>{{ $menu->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if(count($newProducts) > 0)
        <div class="products section-gap">
            <div class="medium-container">
                <div class="main-title main-title--flex">
                    <h2>Новинки</h2>
                    <div class="arrows">
                        <button class="arrows__item arrows__item--prev"></button>
                        <button class="arrows__item arrows__item--next"></button>
                    </div>
                </div>
                <div class="product-slider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($newProducts as $product)
                                <div class="swiper-slide">
                                    @component('frontend.components.product-card', ['product'=>$product])@endcomponent
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="products section-gap">
        <div class="medium-container">
            <div class="main-title main-title--flex">
                <h2>Хиты</h2>
                <div class="arrows">
                    <button class="arrows__item arrows__item--prev"></button>
                    <button class="arrows__item arrows__item--next"></button>
                </div>
            </div>
            <div class="product-slider">
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($hitProducts as $product)
                            <div class="swiper-slide">
                                @component('frontend.components.product-card', ['product'=>$product])@endcomponent
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($stocks) > 0)
        <div class="section-gap">
            <div class="medium-container">
                <div class="main-title main-title--flex">
                    <h2>Акции</h2>
                    <div class="arrows">
                        <button class="arrows__item arrows__item--prev stock-arrow--prev"></button>
                        <button class="arrows__item arrows__item--next stock-arrow--next"></button>
                    </div>
                </div>
                <div class="stock-slider">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @foreach($stocks as $stock)
                            <div class="swiper-slide">
                                @component('frontend.components.stock-card', ['stock'=>$stock]) @endcomponent
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
