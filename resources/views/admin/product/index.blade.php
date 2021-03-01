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
                                    <li class="breadcrumb-item"><a href="javascript:">Товары</a></li>
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
                                    <th class="w-100p">R_ID</th>
                                    <th class="w-70p"></th>
                                    <th>название</th>
                                    <th>код товара</th>
                                    <th>цена</th>
                                    <th>количество</th>
                                    <th>категории</th>
                                    <th>статус</th>
                                    <th class="w-100p">действие</th>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input type="text"
                                                   class="form-control"
                                                   name="iid"
                                                   value="{{ request()->input('iid') }}"
                                                   autocomplete="off"
                                                   placeholder="R_ID"
                                                   form="tableForm"
                                                   title="">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input type="text"
                                                   class="form-control"
                                                   name="name"
                                                   value="{{ request()->input('name') }}"
                                                   autocomplete="off"
                                                   placeholder="Поиск по название"
                                                   form="tableForm"
                                                   title="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group mb-0">
                                            <input type="text"
                                                   class="form-control"
                                                   name="code"
                                                   value="{{ request()->input('code') }}"
                                                   autocomplete="off"
                                                   placeholder="Код товара"
                                                   form="tableForm"
                                                   title="">
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="form-group form-check mb-0">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="notAvailable"
                                                   name="not_available"
                                                   form="tableForm"
                                                   {{ request()->input('not_available') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="notAvailable" style="white-space: nowrap">нет в наличии</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-check mb-0">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="color_id"
                                                   name="color_id"
                                                   form="tableForm"
                                                {{ request()->input('color_id') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="color_id" style="white-space: nowrap">Цвет</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group form-check mb-0">
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   id="status"
                                                   name="status"
                                                   form="tableForm"
                                                {{ request()->input('status') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status" style="white-space: nowrap">Активный</label>
                                        </div>
                                    </td>
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
                                                {{ $item->iid }}
                                            </td>
                                            <td>
                                                <img src="{{ $item->getImg('image', 'thumb') }}" alt="">
                                            </td>
                                            <td>
                                                {{ $item->name }}
                                            </td>
                                            <td>
                                                {{ $item->code }}
                                            </td>
                                            <td>
                                                {{ $item->price }}
                                            </td>
                                            <td>
                                                {{ $item->quantity }}
                                            </td>
                                            <td>
                                                @if(count($item->categories) > 0)
                                                    @foreach($item->categories as $category)
                                                        {{ $category->name }} {!! !$loop->last ? '&mdash;' : '' !!}
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                {!! $item->statusUI !!}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.product.form', ['id' => $item->id]) }}" style="display: inline-block"
                                                   class="label theme-bg text-white f-12 mb-0">Посмотреть
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
