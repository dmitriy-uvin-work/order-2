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
                    <div class="main-title main-title--large-gap">
                        <h2>Заказ [2323c34]</h2>
                    </div>

                    <div class="order-info">
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-3">Добавлено</dt>
                            <dd class="col-sm-9">{{ $order->created_at->format('d.m.Y / H:i:s') }}</dd>
                        </dl>
                        <hr>
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-3">Заказчик</dt>
                            <dd class="col-sm-9">
                                {{ $order->user->name }} / {{ $order->user->email }} / {{ $order->user->phone }}
                            </dd>

                            <dt class="col-sm-3">Контактный номер</dt>
                            <dd class="col-sm-9">{{ $order->phone }}</dd>

                            <dt class="col-sm-3">Адрес доставки</dt>
                            <dd class="col-sm-9 mb-0">{{ $order->address }}</dd>
                        </dl>
                        <hr>
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-3">Способ оплаты</dt>
                            <dd class="col-sm-9">
                                <div class="d-flex align-items-center">
                                    <span class="mr-2 d-inline-block">{{ $order->payment_type_label }}</span>
                                    @if($order->payment_type != 0)
                                        <div class="ml-15">
                                            {!! $order->paymentStatusUI !!}
                                        </div>
                                        @if(!in_array($order->payment, [2,5]))
                                            <div class="ml-10">
                                                <button class="reload-ui"></button>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </dd>
                            <dt class="col-sm-3">Способ доставки</dt>
                            <dd class="col-sm-9 mb-0">{{ $order->delivery_type_label }}</dd>
                        </dl>
                        <hr>
                        <dl class="dl-horizontal row">
                            <dt class="col-sm-3">Начальная цена</dt>
                            <dd class="col-sm-9">@priceFormat($order->price) сум</dd>

                            <dt class="col-sm-3">Сумма доставки</dt>
                            <dd class="col-sm-9">0 сум</dd>

                            <dt class="col-sm-3">Сумма скидки (UDS)</dt>
                            <dd class="col-sm-9">0 сум</dd>

                            <dt class="col-sm-3">Итого</dt>
                            <dd class="col-sm-9 mb-0">@priceFormat($order->price) сум</dd>
                        </dl>
                        <hr>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Цена</th>
                            <th>Кол-во</th>
                            <th>Итого</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->products as $product)
                            <tr>
                                <td>{{ $product->name() }}</td>
                                <td>@priceFormat($product->pivot->price) сум</td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>@priceFormat($product->pivot->price * $product->pivot->quantity) сум</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('profile.history.list') }}" class="btn btn--black mt-30">Назад</a>
                </div>
            </div>
        </div>
    </div>

@endsection
