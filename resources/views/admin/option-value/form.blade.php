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
                            <li class="breadcrumb-item"><a href="{{ route('admin.option.index') }}">Опции товара</a></li>
                            @if(isset($option))
                                <li class="breadcrumb-item"><a href="{{ route('admin.option-value.index', ['option_id'=>$option->id]) }}">{{ $option->name }}</a></li>
                            @endif
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->name : 'Добавить опцию' }}</a></li>
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
                            <div class="form-group mb-0">
                                <label>Название</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ old('name') ?? $data->name }}"
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
