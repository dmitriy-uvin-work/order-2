@if(count($products) > 0)
    <div class="product-grid row mt-30">
        @foreach($products as $product)
            <div class="col-md-4 col-lg-3 col-6">
                @component('frontend.components.product-card', ['product'=>$product, 'classList' => 'product-card--small']) @endcomponent
            </div>
        @endforeach
    </div>
@else
    <p>По вашему запросу ничего не найдено, <strong>{{ $query }}</strong></p>
@endif
