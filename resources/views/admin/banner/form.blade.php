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
                            <li class="breadcrumb-item"><a href="{{ route('admin.banner.index') }}">Баннер</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->title : 'Создать баннер' }}</a></li>
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

                            <!------ note ------>
                            <div class="form-group">
                                <label>Заметка</label>
                                <input type="text"
                                       class="form-control"
                                       name="note"
                                       value="{{ old('note') ?? $data->note }}"
                                       autocomplete="off"
                                       title="">
                            </div>

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
                                            <img src="{{ $data->getImg('image', 'large') }}" alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <?php
                                $btn_arr = [
                                    'перейти к покупкам',
                                    'перейти к бренду',
                                    'узнать подробнее',
                                    'перейти к акцию',
                                    'подробности'
                                ]
                            ?>

                            <!------ btn_text ------>
                            <div class="form-group">
                                <label>Текст кнопку</label>
                                <select class="form-control select2_with_search" name="btn_text" title="">
                                    @foreach($btn_arr as $arr)
                                        <option value="{{ $arr }}" {{ $data->btn_text == $arr ? 'selected' : '' }}>{{ $arr }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!------ url ------>
                            <div class="form-group">
                                <label>Url</label>
                                <input type="text"
                                       class="form-control"
                                       name="link"
                                       value="{{ old('link') ?? $data->link }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ sort ------>
                            <div class="form-group">
                                <label>Сортировка</label>
                                <input type="text"
                                       class="form-control"
                                       name="sort"
                                       value="{{ old('sort') ?? $data->sort }}"
                                       autocomplete="off"
                                       title="">
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
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
