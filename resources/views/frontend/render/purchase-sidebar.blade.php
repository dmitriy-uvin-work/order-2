@if(count($products) > 0)
    @foreach($products as $product)
        @component('frontend.components.product-min-card', ['product'=>$product, 'hideSwitch'=>true])@endcomponent
    @endforeach
@else
    <p class="mt-40">К сожалению, в вашей корзине еще нет товаров. Подберите нужный вам товар в каталоге и добавьте их в <strong>корзину</strong>.</p>
@endif
