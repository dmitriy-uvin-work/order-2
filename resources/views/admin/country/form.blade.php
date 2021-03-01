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
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.country.index') }}">Страны мира</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->name : 'Добавить страну' }}</a></li>
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
                                <label>Наименование</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ old('name') ?? $data->name }}"
                                       autocomplete="off"
                                       title="">
                            </div>
                            <!------ fullname ------>
                            <div class="form-group">
                                <label>Полное наименование</label>
                                <input type="text"
                                       class="form-control"
                                       name="fullname"
                                       value="{{ old('fullname') ?? $data->fullname }}"
                                       autocomplete="off"
                                       title="">
                            </div>
                            <!------ alfa2 ------>
                            <div class="form-group">
                                <label>Двузначный код страны</label>
                                <input type="text"
                                       class="form-control"
                                       name="alfa2"
                                       value="{{ old('alfa2') ?? $data->alfa2 }}"
                                       autocomplete="off"
                                       title="">
                            </div>
                            <!------ alfa3 ------>
                            <div class="form-group">
                                <label>Трехзначный код страны</label>
                                <input type="text"
                                       class="form-control"
                                       name="alfa3"
                                       value="{{ old('alfa3') ?? $data->alfa3 }}"
                                       autocomplete="off"
                                       title="">
                            </div>
                            <!------ country_code ------>
                            <div class="form-group">
                                <label>Код страны</label>
                                <input type="text"
                                       class="form-control"
                                       name="code"
                                       value="{{ old('code') ?? $data->code }}"
                                       autocomplete="off"
                                       title="">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
