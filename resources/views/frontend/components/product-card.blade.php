<div class="product-card {{ isset($isFavorite) ? 'product-card--small' : '' }} {{ isset($classList) ? $classList : '' }}">
    <div class="product-card__top" style="background-image: url({{ $product->getImg('image', 'medium') }})">
        @if($product->isNew)
            <div class="badge badge--green">New</div>
        @endif
        @if ($product->discount())
            <div class="badge badge--red">- {{ $product->discount() }}%</div>
        @endif
        <div class="p-buttons" data-id="{{ $product->iid }}">
            @if(isset($isFavorite))
                <button class="addToCart"><img src="/frontend/images/icons/cart.svg" alt=""></button>
                <div class="destroyWish destroy-btn"></div>
            @else
                <button class="addToCart"><img src="/frontend/images/icons/cart.svg" alt=""></button>
                <button class="addToWish"><img src="/frontend/images/icons/wishlist.svg" alt=""></button>
            @endif
        </div>
    </div>
    <div class="product-card__caption">
        <a href="{{ route('product.view', ['slug'=>$product->slug]) }}" class="p-title">{{ $product->name() }}</a>
        @if(count($product->categories))
            <a href="{{ route('catalog', ['slug'=>$product->categories[0]->slug]) }}" class="p-category">{{ $product->categories[0]->name }}</a>
        @endif
        @if ($product->discount())
            <span class="p-price text-grey text-through font-size-14">{{ $product->price }} UZS</span>
            <span class="p-price">{{ $product->getPrice() }} UZS</span>
        @else
            <span class="p-price">{{ $product->getPrice() }} UZS</span>
        @endif
    </div>
</div>
