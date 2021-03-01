@extends('frontend.layout.master')

@section('section')
    <div class="purchasing-page page-gap">
        <div class="medium-container">
            <div class="d-flex flex-wrap">
                <div class="purchasing-page__content">
                    <div class="main-title">
                        <h2>Оформление заказа</h2>
                    </div>
                    <div class="content-ajax is-ajax-wrap">
                        <div class='loader-ui loader-ui--triangle'></div>
                    </div>
                </div>
                <div class="purchasing-page__sidebar">
                    <div class="sidebar-ajax is-ajax-wrap">
                        <div class='loader-ui loader-ui--triangle'></div>
                    </div>
                </div>
            </div>
        </div>

    <input type="hidden" value="{{ json_encode(request()->old()) }}" id="old">
@endsection
