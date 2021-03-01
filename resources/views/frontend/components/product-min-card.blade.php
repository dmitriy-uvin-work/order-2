<div class="product-min-card product-cart-{{ $product->iid }}">
    <div class="product-min-card__left d-flex">
        <a href="{{ route('product.view', ['slug'=>$product->slug]) }}" class="p-image" style="background-image: url({{ $product->getImg('image', 'thumb') }})"></a>
        <div class="product-min-card__caption">
            <a href="{{ route('product.view', ['slug'=>$product->slug]) }}" class="mb-5 d-block">{{ $product->name() }}</a>
            <div class="font-12 c--main-grey">{{ $product->volume }} МЛ</div>
            @if ($product->discount())
                <div class="mt-15"><span class="p-price text-grey text-through font-size-12">{{ $product->price }} UZS</span> {{ $product->getPrice() }} UZS</div>
            @else
                <div class="mt-15">{{ $product->getPrice() }} UZS</div>
            @endif
        </div>
    </div>
    <div data-id="{{ $product->iid }}">
        <div class="destroyCart destroy-btn"></div>
        @if (isset($hideSwitch))
            <div class="quantity-switch quantity-switch--only" style="width: auto;">
                <div class="quantity-switch__value" style="border-right: none">{{ $product->cart_quantity }}</div>
            </div>
        @else
            <div class="quantity-switch" data-id="{{ $product->iid }}">
                <div class="quantity-switch__value" data-max-value="{{ $product->quantity }}">{{ $product->cart_quantity }}</div>
                <div class="quantity-switch__nav" data-type="plus"></div>
                <div class="quantity-switch__nav" data-type="minus"></div>
            </div>
        @endif
    </div>
</div>
