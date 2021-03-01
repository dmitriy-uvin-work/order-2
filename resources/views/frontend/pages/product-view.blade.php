@extends('frontend.layout.master')

@section('section')

    <div class="page-gap">
        <div class="medium-container">
            <div class="breadcrumb-ui">
                <a href="{{ route('home') }}">Главная</a>
                @if(count($product->categories) > 0)
                    @if($product->categories[0]->parent->id)
                        <a href="{{ route('catalog', ['slug'=>$product->categories[0]->parent->slug]) }}">{{ $product->categories[0]->parent->name }}</a>
                    @endif
                        <a href="{{ route('catalog', ['slug'=>$product->categories[0]->slug]) }}">{{ $product->categories[0]->name }}</a>
                @endif
                <span>{{ $product->name() }} {{ $product->iid }}</span>
            </div>
            <div class="product-view">
                @php $gallery = json_decode($product->gallery) ?? []; @endphp
                <div class="product-view__left">
                    <div class="gallery">
                        <div class="gallery__thumbs">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide"><img src="{{ $product->getImg('image', 'thumb') }}" alt=""></div>
                                    @if(count($gallery))
                                        @foreach($gallery as $img)
                                            <div class="swiper-slide"><img src="{{ $product->getImage('gallery', $img, 'thumb') }}" alt=""></div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="gallery__main">
                            @if($product->isNew)
                                <div class="badge badge--green">New</div>
                            @endif
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide"><img src="{{ $product->getImg('image', 'large') }}" alt=""></div>
                                    @if(count($gallery))
                                        @foreach($gallery as $img)
                                            <div class="swiper-slide"><img src="{{ $product->getImage('gallery', $img, 'large') }}" alt=""></div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-advantages row">
                        <div class="product-advantages__item col-md-6">
                            <img src="/frontend/images/icons/box.svg" alt="">
                            <span>Доставка по всей территории Узбекистана</span>
                        </div>
                        <div class="product-advantages__item col-md-6">
                            <img src="/frontend/images/icons/tick.svg" alt="">
                            <span>Гарантия качества продукции</span>
                        </div>
                    </div>
                </div>
                <div class="product-view__right">
                    <h2 class="h2 mb-20">{{ $product->name() }}</h2>
                    <div class="font-16 mb-20">{{ $product->volume }} Объём / МЛ</div>
                    @if ($product->discount())
                        <div class="h2 pv-price mb-20">
                            <span class="p-price text-grey text-through font-size-22">{{ $product->price }} UZS</span>
                            {{ $product->getPrice() }} UZS
                        </div>
                    @else
                        <div class="h2 pv-price mb-20">{{ $product->getPrice() }} UZS</div>
                    @endif

                    <div class="d-flex flex-wrap mb-50" data-id="{{ $product->id }}">
                        @if(!($product->quantity < 1))
                            <div class="quantity-switch mr-30">
                                <div class="quantity-switch__value" data-max-value="{{ $product->quantity }}">1</div>
                                <div class="quantity-switch__nav" data-type="plus"></div>
                                <div class="quantity-switch__nav" data-type="minus"></div>
                            </div>
                            <button class="btn btn--black btn--with-icon addToCart addToCartWithQuantity mr-15"><img src="/frontend/images/icons/cart-white.svg" alt="">Добавить в корзину</button>
                        @endif
                        <button class="btn btn--black btn--only-icon addToWish"><img src="/frontend/images/icons/wishlist-white.svg" alt=""></button>
                    </div>

                    @if(count($colorProducts) > 0)
                        <div class="form-group">
                            <select class="select2" name="color_product" title="" id="colorProduct">
                                @foreach($colorProducts as $colorProduct)
                                    <option data-image="{{ $colorProduct->getImg('image', 'thumb')}}" data-image-large="{{ $colorProduct->getImg('image', 'large') }}" value="{{ route('product.view', ['slug' => $colorProduct->slug]) }}">{{ $colorProduct->color->getName() }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="accordion-ui">
                        @if(!empty($product->description))
                        <div class="accordion-ui__item">
                            <div class="a-head">
                                <div>О товаре</div>
                                <span class="arrow"></span>
                            </div>
                            <div class="a-body">
                                <div class="rich-box-style rich-box-style--grey">
                                    <p>{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if (!empty($product->brand->id))
                        <div class="accordion-ui__item">
                            <div class="a-head">
                                <div>О бренде</div>
                                <span class="arrow"></span>
                            </div>
                            <div class="a-body">
                                <div class="rich-box-style rich-box-style--grey">
                                    <h6>
                                        <a href="{{ route('brand.view', ['id'=> $product->brand->id]) }}">
                                            {{ $product->brand->name }}
                                        </a>
                                    </h6>
                                    <p>{{ $product->brand->description }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty($product->information))
                            <div class="accordion-ui__item">
                                <div class="a-head">
                                    <div>Характеристики</div>
                                    <span class="arrow"></span>
                                </div>
                                <div class="a-body">
                                    <div class="rich-box-style rich-box-style--grey">
                                        <p>{{ $product->information }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    @if(count($product->tags) > 0)
                        <div class="tag-ui mt-50">
                            @foreach($product->tags as $tag)
                                <a href="{{ route('catalog', ['tag'=>$tag->id]) }}" class="mb-5">#{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if(count($similarProducts) > 0)
            <div class="products section-gap">
                <div class="medium-container">
                    <div class="main-title main-title--flex">
                        <h2>Возможно вас заинтересует</h2>
                        <div class="arrows">
                            <button class="arrows__item arrows__item--prev"></button>
                            <button class="arrows__item arrows__item--next"></button>
                        </div>
                    </div>
                    <div class="product-slider">
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                @foreach($similarProducts as $similarProduct)
                                    <div class="swiper-slide">
                                        @component('frontend.components.product-card', ['product' => $similarProduct])@endcomponent
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

@endsection

@push('scripts')
    <script>
        let select2 = $('#colorProduct');

        select2.select2({
            width: '100%',
            minimumResultsForSearch: -1,
            templateResult: formatState
        });

        function formatState (state) {
            if (!state.id) { return state.text; }
            var img = $(state.element).data('image');
            var imgLarge = $(state.element).data('image-large');
            var $state = $(
                '<span><img src="'+img+'" style="width: 20px;height: 20px" class="img-flag" /> ' +
                state.text +     '</span>'
            );
            return $state;
        };

        select2.on('select2:select', function (e) {
            var data = e.params.data;
            var img = $(e.params.data.element).data('image');
            var imgLarge = $(e.params.data.element).data('image-large');
            $('.swiper-slide-active').find('img').attr('src', imgLarge)
            $('.swiper-slide-thumb-active').find('img').attr('src', img)
            // document.location.href = data.id;
        });

    </script>
@endpush
