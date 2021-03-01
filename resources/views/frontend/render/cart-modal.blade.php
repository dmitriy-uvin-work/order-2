<div class="right-popup__head">
    <span>{{ count($cart['products']) }} товара в корзине</span>
    <button class="right-popup__close"></button>
</div>
<div class="right-popup__body">
    @if(count($cart['products']) > 0)
        @foreach($cart['products'] as $product)
            @component('frontend.components.product-min-card', ['product'=>$product])@endcomponent
        @endforeach

    @else
        <p>К сожалению, в вашей корзине еще нет товаров. Подберите нужный вам товар в каталоге и добавьте их в <strong>корзину</strong>.</p>
    @endif
</div>
@if(count($cart['products']))
    <div class="right-popup__footer">
        <div class="d-flex justify-content-end mb-30">
            <span class="mr-10">Всего:</span>
            <span class="font-18 font-500">@priceFormat($cart['total_price']) UZS</span>
        </div>
        <a href="{{ route('profile.purchasing') }}" class="btn btn--black">оформить заказ</a>
    </div>
@endif
