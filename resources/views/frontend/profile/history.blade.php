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
                        <h2>История заказов</h2>
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>№ заказа</th>
                            <th>Кол-во</th>
                            <th>Итого</th>
                            <th>Добавленно</th>
                            <th>Статус</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($orders) > 0)
                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="{{ route('profile.history.view', ['id'=>$order->id]) }}">{{ $order->id }}</a></td>
                                    <td>{{ $order->products_count }}</td>
                                    <td>{{ $order->price }} UZS</td>
                                    <td>{{ $order->created_at->format('d.m.Y / H:i:s') }}</td>
                                    <td>
                                        {!! $order->statusUI !!}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
