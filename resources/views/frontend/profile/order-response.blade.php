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
                        <h2>Ваш заказ оформлен</h2>
                    </div>
                    <div class="rich-box-style rich-box-style--grey mb-30">
                        <p>
                            Спасибо! Ваш заказ оформлен. В ближайшее время наши операторы с Вами свяжутся.
                        </p>
                        <p>
                            Вы можете <a href='{{ route('profile.history.view', ['id'=>$order->id]) }}'>отслеживать статус заказа</a>
                        </p>
                        <p>
                            Номер Вашего заказа : <span class="C-main-orange" style="font-size: 18px;">{{ $order->id }}</span>
                        </p>
                    </div>
                    <a href="{{ route('catalog') }}" class="btn btn--black">ПРОДОЛЖИТЬ ПОКУПКИ</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        if (window.localStorage && window.localStorage['beauty_cart']) {
            window.localStorage.removeItem('beauty_cart');
        }
    </script>
@endsection
