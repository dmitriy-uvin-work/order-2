@foreach($products as $product)
    <div class="col-md-4 col-lg-3 col-6">
        @component('frontend.components.product-card', ['product'=>$product]) @endcomponent
    </div>
@endforeach
@if($products->hasMorePages())
    <div class="col-md-4 col-lg-3 col-6 product-more" data-page="{{ $products->currentPage() + 1 }}">
        <button class="product-add">
            <span>Показать еще</span>
            <img src="/frontend/images/icons/plus.svg" alt="">
            <span class="count">{{ $products->perPage() * $products->currentPage() }} / {{ $products->total() }}</span>
        </button>
    </div>
@endif
