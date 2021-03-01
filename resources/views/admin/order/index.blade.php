@extends('admin.layout.master')

@section('section')
    <div class="pcoded-inner-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="page-header-title">
                                </div>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="feather icon-home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="javascript:">Заказы</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="" method="get" id="tableForm"></form>

        <!-- [ breadcrumb ] end -->
        <div class="main-body">
            <div class="page-wrapper">
                <!-- [ Main Content ] start -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="w-150p">ID заказа</th>
                                    <th>пользователь</th>
                                    <th>цена</th>
                                    <th>количество</th>
                                    <th>тип оплаты</th>
                                    <th>дата создание</th>
                                    <th class="w-150p">статус</th>
                                    <th class="w-100p">действие</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input type="text"
                                                   class="form-control"
                                                   name="id"
                                                   value="{{ request()->input('id') }}"
                                                   autocomplete="off"
                                                   placeholder="ID Заказа"
                                                   form="tableForm"
                                                   title="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input type="text"
                                                   class="form-control"
                                                   name="name"
                                                   value="{{ request()->input('name') }}"
                                                   autocomplete="off"
                                                   placeholder="Поиск по имен, email, телефон"
                                                   form="tableForm"
                                                   title="">
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                    <td>
                                        <button type="submit" form="tableForm" class="btn btn-primary w-100">Поиск</button>
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($data) > 0)
                                    @foreach($data as $key => $item)
                                        <tr>
                                            <td>
                                                {{ $item->id }}
                                            </td>
                                            <td>
                                                {{ $item->user->name }} <span>{{ $item->user->phone ? '('. $item->user->phone .')' : '' }}</span><br>
                                                {{ $item->user->email }} <br>
                                            </td>
                                            <td>
                                                {{ $item->price }}
                                            </td>
                                            <td>
                                                {{ $item->products_count }}
                                            </td>
                                            <td>
                                                {{ $item->payment_type_label }}
                                            </td>
                                            <td>
                                                {{ $item->created_at->format('d.m.Y / H:i:s') }}
                                            </td>
                                            <td>
                                                {!! $item->statusUI !!}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.order.form', ['id' => $item->id]) }}"
                                                   class="label theme-bg text-white f-12 mb-0 mr-0 w-100">Посмотреть
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="100" class="text-center">
                                            Нет данные
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                                @if ($data->hasPages())
                                    <tfoot>
                                        <tr>
                                            <td colspan="100">{{ $data->appends(request()->query())->links() }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
    </div>
@endsection
