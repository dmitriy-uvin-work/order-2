<div class="stock-card">
    <div class="row">
        <div class="col-md-8">
            <a href="{{ route('stock.view', ['id'=>$stock->id]) }}" class="stock-card__item stock-card__item--{{ $stock->color }}" style="background-image: url({{ $stock->getImg('image', 'large') }})">
                <span class="s-brand"><img src="/frontend/images/pics/brand2.png" alt=""></span>
                <div class="s-title">{{ $stock->name }}</div>
                <span class="s-note">{{ $stock->note }}</span>
            </a>
        </div>
        @if ($stock->stockProduct)
            <div class="col-md-4">
                <div class="product-card">
                    <div class="product-card__top" style="background-image: url({{ $stock->stockProduct->getImg('image', 'medium') }})">
                        @if ($stock->stockProduct->discount())
                            <div class="badge badge--red">- {{ $stock->stockProduct->discount() }}%</div>
                        @endif
                        <div class="p-buttons">
                            <button><img src="/frontend/images/icons/cart.svg" alt=""></button>
                            <button><img src="/frontend/images/icons/wishlist.svg" alt=""></button>
                        </div>
                    </div>
                    <div class="product-card__caption">
                        <a href="{{ route('product.view', ['slug'=>$stock->stockProduct->slug]) }}" class="p-title">{{ $stock->stockProduct->name() }}</a>
                        @if(count($stock->stockProduct->categories))
                            <a href="{{ route('catalog', ['slug'=>$stock->stockProduct->categories[0]->slug]) }}" class="p-category">{{ $stock->stockProduct->categories[0]->name }}</a>
                        @endif
                        @if ($stock->stockProduct->discount())
                            <span class="p-price text-grey text-through font-size-14">{{ $stock->stockProduct->price }} UZS</span>
                            <span class="p-price">{{ $stock->stockProduct->getPrice() }} UZS</span>
                        @else
                            <span class="p-price">{{ $stock->stockProduct->getPrice() }} UZS</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
