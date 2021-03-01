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
                            <li class="breadcrumb-item"><a href="{{ route('admin.menu.index') }}">Подменю</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->name : 'Создать подменю' }}</a></li>
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

                            <!------ title ------>
                            <div class="form-group">
                                <label>Заголовок</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ old('name') ?? $data->name }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ image ------>
                            <div class="form-group">
                                <label>Фото ( {{ $data->getRecommendedSize() }} )</label>
                                <div>
                                    <input type="file"
                                           data-buttontext="Выбирете изображение"
                                           data-placeholder="Файла нет"
                                           class="d-block"
                                           name="image" value="{{ $data->id ? $data->image : ''}}">
                                    @if(isset($data->image))
                                        <div class="img__downloaded">
                                            <div class="img__downloaded__remove"></div>
                                            <input type="hidden" name="image" value="{{$data->image}}">
                                            <img src="{{ $data->getImg('image', 'thumb') }}" alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!------ url ------>
                            <div class="form-group">
                                <label for="urlLabel">Url</label>
                                <input type="text"
                                       class="form-control"
                                       name="url"
                                       value="{{ old('url') ?? $data->url }}"
                                       autocomplete="off"
                                       id="urlLabel">
                            </div>

                            <!------ sort ------>
                            <div class="form-group">
                                <label for="sortLabel">Сортировка</label>
                                <input type="text"
                                       class="form-control"
                                       name="sort"
                                       value="{{ old('sort') ?? $data->sort }}"
                                       autocomplete="off"
                                       id="sortLabel">
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
