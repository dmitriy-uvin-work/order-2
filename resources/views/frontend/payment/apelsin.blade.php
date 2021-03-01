@extends('frontend.layout.master')

@section('section')
    <div class="purchasing-page page-gap">
        <div class="medium-container">
            <div class="d-flex flex-wrap">
                <form method="GET" action="https://oplata.kapitalbank.uz">
                    <input type="hidden" name="cash" value="{{ env('APELSIN_HASH_ID') }}"/>
                    <input type="hidden" name="redirectUrl" value="{{ route('profile.payment.postback.apelsin') }}"/>
                    <input type="hidden" name="description" value="Оплата товара"/>
                    <input type="hidden" name="amount" value=" {{ $order->price }}"/>
                    <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                    <button type="submit" style="cursor: pointer; border: 1px solid #ebebeb; border-radius: 6px; background: linear-gradient(to top, #f1f2f2, white); width: 100px; height: 54px; display: flex; align-items: center; justify-content: center;">
                        <img style="width: 100px; height: 42px;" src="https://oplata.kapitalbank.uz/images/apelsin-v1.png">
                    </button> </form>
            </div>
        </div>

@endsection
