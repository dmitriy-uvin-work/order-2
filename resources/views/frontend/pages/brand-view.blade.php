@php
    $query = request()->query();
    $show_reset_btn = false;

    $count_query = $query;
    if (isset($count_query['in_stock'])) unset($count_query['in_stock']);
    if (count($count_query) > 0) $show_reset_btn = true;

    $query['brand'] = isset($query['brand']) ? $query['brand'] : [];
    $query['option'] = isset($query['option']) ? $query['option'] : [];

    $sort_by = isset($query['sort_by']) ? $query['sort_by'] : '';
    $price_min = isset($query['price_min']) ? $query['price_min'] : '0';
    $price_max = isset($query['price_max']) ? $query['price_max'] : '600 000';
@endphp

@extends('frontend.layout.master')

@section('section')

    <div class="medium-container page-gap">
        <div class="main-title">
            <h2>{{ $brand->name }}</h2>
        </div>

        <div class="page-column page-column--border">
            <div class="page-column__left">
                <p class="c--main-grey mt-0 mb-0">{{ $brand->description }}</p>
            </div>
            <div class="page-column__right">
                <div class="brand-img">
                    <img src="{{ $brand->getImg('image','large') }}" alt="">
                </div>
            </div>
        </div>

        <form id="FilterForm">
            <div class="filter">
                <div class="d-flex flex-wrap">
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

                    @if ($show_reset_btn)
                        <button class="filter-reset">Сбросить фильтр</button>
                    @endif
                </div>
                <div>
                    <label class="checkbox-ui">
                        <input type="checkbox" name="in_stock" {{ isset($query['in_stock']) ? 'checked' : '' }}>
                        <span class="checkbox-ui__figure"></span><span>В наличии</span>
                    </label>
                </div>
            </div>
        </form>
        <div class="product-grid row">
            @component('frontend.render.catalog', ['products'=>$products])@endcomponent
        </div>
    </div>
@endsection

@section('js')
    @component('frontend.components.filter-js')@endcomponent
@endsection
