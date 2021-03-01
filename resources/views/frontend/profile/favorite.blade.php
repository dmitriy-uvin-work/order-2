@extends('frontend.layout.master')

@section('section')

    <div class="profile-page">
        <div class="medium-container">
            <div class="profile-page__sidebar">
                <div class="page-gap">
                    @include('frontend.profile.menu')
                </div>
            </div>
            <div class="profile-page__content">
                <div class="page-gap">
                    <div class="main-title">
                        <h2>Избранное</h2>
                    </div>

                    @if(count($products))
                        <div class="product-grid row">
                            @foreach($products as $product)
                                <div class="col-md-3 product-wish-{{$product->iid}}">
                                    @component('frontend.components.product-card', ['product'=>$product, 'isFavorite'=>true])@endcomponent
                                </div>
                            @endforeach
                        </div>
                        {{ $products->links('frontend.components.pagination') }}
                    @else
                        @component('frontend.components.empty-data', ['text'=>'В избранном пока пусто. Воспользуйтесь поиском или каталогом, выберите нужные товары и добавьте их в избранное.']) @endcomponent
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
