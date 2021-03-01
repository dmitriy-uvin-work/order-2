@php
    $query = request()->query();
    $show_reset_btn = false;

    $count_query = $query;
    if (isset($count_query['in_stock'])) unset($count_query['in_stock']);
    if (isset($count_query['category'])) unset($count_query['category']);
    if (count($count_query) > 0) $show_reset_btn = true;

    $query['brand'] = isset($query['brand']) ? $query['brand'] : [];
    $query['option'] = isset($query['option']) ? $query['option'] : [];

    // max price from db products
    $maxPrice = isset($maxPrice) ? $maxPrice : 1400000;

    $sort_by = isset($query['sort_by']) ? $query['sort_by'] : '';
    $price_min = isset($query['price_min']) ? $query['price_min'] : 0;
    $price_max = isset($query['price_max']) ? $query['price_max'] : $maxPrice;
@endphp

@extends('frontend.layout.master')

@section('section')
    <div class="medium-container page-gap">
        <div class="main-title">
            <h2>{{ isset($currentCategory) ? $currentCategory->name : 'Каталог' }}</h2>
        </div>
        <form id="FilterForm">
            <div class="filter">
                <div class="d-flex flex-wrap">

                    <div class="filter-item filter-item--large">
                        <div class="filter-item__label">
                            <div>Стоимость</div>
                            <span></span>
                        </div>
                        <div class="filter-item__dropDown">
                            <div class="f-caption">
                                <div class="price-range-ui">
                                    <div class="price-range-ui__inputs">
                                        <input class="price-range-ui__input1"
                                               type="text"
                                               name="price_min"
                                               value="{{ $price_min }}"
                                               autocomplete="off"
                                               title="">
                                        <span class="ml-10 mr-10"></span>
                                        <input class="price-range-ui__input2"
                                               type="text"
                                               name="price_max"
                                               value="{{ $price_max }}"
                                               autocomplete="off"
                                               data-max="{{ $maxPrice }}"
                                               title="">
                                    </div>
                                    <div class="price-range-ui__line"></div>
                                </div>
                            </div>
                            <div class="f-footer">
                                <button class="filter-item__reset c--main-grey">
                                    Сбросить
                                </button>
                                <button class="btn btn--black btn--small">
                                    Применить
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="filter-item" data-filter="sort_by">
                        <div class="filter-item__label">
                            <div>Сортировать</div>
                            <span></span>
                        </div>
                        <div class="filter-item__dropDown">
                            <div class="f-caption">
                                <div class="f-value">
                                    <label class="checkbox-ui">
                                        <input type="radio" name="sort_by" value="created_at/desc" {{ $sort_by == 'created_at/desc' ? 'checked' : '' }}>
                                        <span class="checkbox-ui__figure"></span><span>По дате</span>
                                    </label>
                                </div>
                                <div class="f-value">
                                    <label class="checkbox-ui">
                                        <input type="radio" name="sort_by" value="price/asc" {{ $sort_by == 'price/asc' ? 'checked' : '' }}>
                                        <span class="checkbox-ui__figure"></span><span>Сначала дешевые</span>
                                    </label>
                                </div>
                                <div class="f-value">
                                    <label class="checkbox-ui">
                                        <input type="radio" name="sort_by" value="price/desc" {{ $sort_by == 'price/desc' ? 'checked' : '' }}>
                                        <span class="checkbox-ui__figure"></span><span>Сначала дорогие</span>
                                    </label>
                                </div>
                            </div>
                            <div class="f-footer">
                                <button class="filter-item__reset c--main-grey">
                                    Сбросить
                                </button>
                                <button class="btn btn--black btn--small">
                                    Применить
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(count($brands) > 0)
                    <div class="filter-item" data-filter="brand">
                        <div class="filter-item__label">
                            <div>Бренды</div>
                            <span></span>
                        </div>
                        <div class="filter-item__dropDown">
                            <div class="f-caption">
                                @if(count($brands) > 0)
                                    @foreach($brands as $brand)
                                        <div class="f-value">
                                            <label class="checkbox-ui">
                                                <input type="radio" name="brand[]" value="{{ $brand->id }}" {{ in_array($brand->id, $query['brand']) ? 'checked' : '' }}>
                                                <span class="checkbox-ui__figure"></span><span>{{ $brand->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="f-footer">
                                <button class="filter-item__reset c--main-grey">
                                    Сбросить
                                </button>
                                <button class="btn btn--black btn--small">
                                    Применить
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(count($options) > 0)
                        @foreach($options as $option)
                            <div class="filter-item" data-filter="option">
                                <div class="filter-item__label">
                                    <div>{{ $option->name }}</div>
                                    <span></span>
                                </div>
                                <div class="filter-item__dropDown">
                                    <div class="f-caption">
                                        @if(count($option->values) > 0)
                                            @foreach($option->values as $optionValue)
                                                <div class="f-value">
                                                    <label class="checkbox-ui">
                                                        <input type="radio" name="option[]" value="{{ $optionValue->id }}" {{ in_array($optionValue->id, $query['option']) ? 'checked' : '' }}>
                                                        <span class="checkbox-ui__figure"></span><span>{{ $optionValue->name }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="f-footer">
                                        <button class="filter-item__reset c--main-grey">
                                            Сбросить
                                        </button>
                                        <button class="btn btn--black btn--small">
                                            Применить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (count($singleOptions) > 0)
                        @foreach($singleOptions as $singleOption)
                            <div class="filter-item">
                                <label class="checkbox-ui">
                                    <input type="checkbox" name="option[]" {{ in_array($singleOption->id, $query['option']) ? 'checked' : '' }}>
                                    <span class="checkbox-ui__figure"></span><span>{{ $singleOption->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    @endif

                    @if ($show_reset_btn)
                        <button class="filter-reset">Сбросить фильтр</button>
                    @endif
                </div>
                <div class="in-stock">
                    <label class="checkbox-ui">
                        <input type="checkbox" name="in_stock" {{ isset($query['in_stock']) ? 'checked' : '' }}>
                        <span class="checkbox-ui__figure"></span><span>В наличии</span>
                    </label>
                </div>
            </div>
        </form>
        <div class="product-grid row">
            @if(count($products) > 0)
                @component('frontend.render.catalog', ['products'=>$products])@endcomponent
            @else
                @component('frontend.components.empty-data', ['text'=>'В этом разделе скоро появятся продукции']) @endcomponent
            @endif
        </div>
    </div>
@endsection

@section('js')
    @component('frontend.components.filter-js')@endcomponent
@endsection
