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
                            <li class="breadcrumb-item"><a href="{{ route('admin.region.index') }}">Регионы
                                    Узбекистана</a></li>
                            <li class="breadcrumb-item"><a
                                    href="javascript:">{{ $data->id ? $data->name : 'Добавить регион' }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <form action="" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <!------ name ------>
                            <div class="form-group">
                                <label>Название</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ old('name') ?? $data->name }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ sort ------>
                            <div class="form-group mb-0">
                                <label>Сортировка</label>
                                <input type="text"
                                       class="form-control"
                                       name="sort"
                                       value="{{ old('sort') ?? $data->sort }}"
                                       autocomplete="off"
                                       title="">
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">Цена за доставку</div>

                    <div class="card">
                        <div class="card-body">
                            @php
                                $delivery = json_decode($data->delivery);
                            @endphp
                            <div class="row">
                                <div class="col-md-4">
                                    <!------  ------>
                                    <div class="form-group">
                                        <label>От (кг)</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v1][from]"
                                               value="{{ $delivery->v1->{'from'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                    <!------  ------>
                                    <div class="form-group">
                                        <label>До (кг)</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v1][up-to]"
                                               value="{{ $delivery->v1->{'up-to'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                    <!------  ------>
                                    <div class="form-group mb-0">
                                        <label>Цена</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v1][price]"
                                               value="{{ $delivery->v1->{'price'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!------  ------>
                                    <div class="form-group">
                                        <label>От (кг)</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v2][from]"
                                               value="{{ $delivery->v2->{'from'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                    <!------  ------>
                                    <div class="form-group">
                                        <label>До (кг)</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v2][up-to]"
                                               value="{{ $delivery->v2->{'up-to'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                    <!------  ------>
                                    <div class="form-group mb-0">
                                        <label>Цена</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v2][price]"
                                               value="{{ $delivery->v2->{'price'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!------  ------>
                                    <div class="form-group">
                                        <label>От (кг)</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v3][from]"
                                               value="{{ $delivery->v3->{'from'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                    <!------  ------>
                                    <div class="form-group">
                                        <label>До (кг)</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v3][up-to]"
                                               value="{{ $delivery->v3->{'up-to'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                    <!------  ------>
                                    <div class="form-group mb-0">
                                        <label>Цена</label>
                                        <input type="text"
                                               class="form-control"
                                               name="delivery[v3][price]"
                                               value="{{ $delivery->v3->{'price'} ?? '' }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
@endsection
