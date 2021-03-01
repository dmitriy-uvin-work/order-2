@extends('admin.layout.master')

@section('section')
    <div class="pcoded-inner-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="page-header-title">
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i
                                        class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.order.index') }}">Заказ {{ $order->id }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <form action="" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="card">
                <div class="card-body">

                    <dl class="dl-horizontal row">
                        <dt class="col-sm-3">Добавлено</dt>
                        <dd class="col-sm-9">{{ $order->created_at->format('d.m.Y / H:i:s') }}</dd>
                    </dl>
                    <hr>
                    <dl class="dl-horizontal row">
                        <dt class="col-sm-3">Заказчик</dt>
                        <dd class="col-sm-9">
                            <span>{{ $order->user->name ? $order->user->name . ' /' : '' }} {{ $order->user->email ? $order->user->email . ' /' : '' }} {{ $order->user->phone }}</span>
                            @if($order->isFirst())
                                <span class="badge badge-secondary">первый заказ у клиента</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Контактный номер</dt>
                        <dd class="col-sm-9">{{ $order->phone }}</dd>

                        <dt class="col-sm-3">Адрес доставки</dt>
                        <dd class="col-sm-9">{{ $order->delivery_address }}</dd>
                    </dl>
                    <hr>
                    <dl class="dl-horizontal row">
                        <dt class="col-sm-3">Способ оплаты</dt>
                        <dd class="col-sm-9">
                            <div class="d-flex align-items-center">
                                <span class="mr-2 d-inline-block">{{ $order->payment_type_label }}</span>
                                @if($order->payment_type != 0)
                                    <div>
                                        {!! $order->paymentStatusUI !!}
                                    </div>
                                    @if(!in_array($order->payment, [2,5]))
                                        <div class="ml-2">
                                            <button class="reload-ui"></button>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </dd>
                        <dt class="col-sm-3">Способ доставки</dt>
                        <dd class="col-sm-9">{{ $order->delivery_type_label }}</dd>
                    </dl>
                    <hr>
                    <dl class="dl-horizontal row">
                        <dt class="col-sm-3">Начальная цена</dt>
                        <dd class="col-sm-9">@priceFormat($order->price) сум</dd>

                        <dt class="col-sm-3">Сумма доставки</dt>
                        <dd class="col-sm-9">@priceFormat($order->delivery_price) сум</dd>

                        <dt class="col-sm-3">Сумма скидки (UDS)</dt>
                        <dd class="col-sm-9">0 сум</dd>

                        <dt class="col-sm-3">Итого</dt>
                        <dd class="col-sm-9">@priceFormat($order->price) сум</dd>
                    </dl>
                    <hr>
                    <!------ Статус ------>
                    <div class="row">
                        <div class="col-sm-8 col-md-4">
                            <div class="form-group">
                                <label for="statusLabel">Статус</label>
                                <select class="form-control select2" name="status" title="">
                                    @if (count($order::$STATUS_ARRAY) > 0)
                                        @foreach($order::$STATUS_ARRAY as $key => $status)
                                            <option
                                                value="{{ $key }}" {{ $key == (int)$order->status ? 'selected' : '' }}>{{ $status[0] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mb-0">Сохранить</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-header">
                <h5>Товары</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>название товара</th>
                        <th>цена</th>
                        <th>кол-во</th>
                        <th>всего</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>@priceFormat($product->pivot->price) сум</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>@priceFormat($product->pivot->price * $product->pivot->quantity) сум</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
