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
                            <li class="breadcrumb-item"><a href="{{ route('admin.page.index') }}">Страницы</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->title : 'Создать страницу'}}</a></li>
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
                                       name="title"
                                       value="{{ old('title') ?? $data->title }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ body ------>
                            <div class="form-group">
                                <label>Контент</label>
                                <div class="sum_note__wrapper">
                                    <textarea class="form-control sum_note" title="" name="body">{!! old('body') ?? $data->body !!}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">

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
                                            <img src="{{ $data->getImg('image', 'medium') }}" alt="">
                                        </div>
                                    @endif
                                </div>
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

                            <!------ on_top ------>
                            <div class="form-group form-check">
                                @if(isset($data->on_top))
                                    <input type="checkbox" class="form-check-input" name="on_top" id="onTopLabel" {{ $data->on_top == 1 ? 'checked' : '' }}>
                                @else
                                    <input type="checkbox" class="form-check-input" name="on_top" id="onTopLabel" checked>
                                @endif
                                <label class="form-check-label" for="onTopLabel">Показать в странице помощи</label>
                            </div>

                            <!------ status ------>
                            <div class="form-group form-check mb-0">
                                @if(isset($data->status))
                                    <input type="checkbox" class="form-check-input" id="statusLabel" name="status" {{ $data->status == 1 ? 'checked' : '' }}>
                                @else
                                    <input type="checkbox" class="form-check-input" id="statusLabel" name="status" checked>
                                @endif
                                <label class="form-check-label" for="statusLabel">Статус</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
