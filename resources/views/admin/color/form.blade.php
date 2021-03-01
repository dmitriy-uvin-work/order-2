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
                            <li class="breadcrumb-item"><a href="{{ route('admin.brand.index') }}">Бренды</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->name : 'Создать бренд' }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <form action="" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <input type="hidden" name="_previous" value="{{ url()->previous() }}">

                            <!------ name ------>
                            <div class="form-group">
                                <label>Название</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ $data->name }}"
                                       autocomplete="off"
                                       readonly
                                       title="">
                            </div>

                            <div class="form-group">
                                <label>Альтернативное название</label>
                                <input type="text"
                                       class="form-control"
                                       name="alt_name"
                                       value="{{ $data->alt_name }}"
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
