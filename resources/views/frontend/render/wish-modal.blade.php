<div class="right-popup__head">
    <span>{{ count($products) }} товара в избранном</span>
    <button class="right-popup__close"></button>
</div>
<div class="right-popup__body">
    @if(count($products) > 0)
        <div class="product-grid row mt-30">
            @foreach($products as $product)
                <div class="col-md-4 col-6 product-wish-{{$product->iid}}">
                    @component('frontend.components.product-card', ['product'=>$product, 'isFavorite'=>true])@endcomponent
                </div>
            @endforeach
        </div>
    @else
        <p>В избранном пока пусто. Воспользуйтесь поиском или каталогом, выберите нужные товары и добавьте их в <strong>избранное</strong>.</p>
    @endif
</div>
