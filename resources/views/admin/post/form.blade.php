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
                            <li class="breadcrumb-item"><a href="{{ route('admin.post.index') }}">Блог</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->title : 'Создать блог'}}</a></li>
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

                            <!------ short_description ------>
                            <div class="form-group">
                                <label>Краткое описание</label>
                                <input type="text"
                                       class="form-control"
                                       name="short_description"
                                       value="{{ old('short_description') ?? $data->short_description }}"
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

                            <!------ tags ------>
                            <?php
                            $tagsId = $data->tags->pluck('id')->toArray();
                            ?>
                            <div class="form-group mb-0">
                                <label for="tagLabel">Выберите теги</label>
                                <select class="select2_with_search admin-form-control" name="tags[]" title="" multiple>
                                    <option value=""></option>
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, $tagsId) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
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

                            <!------ published_at ------>
                            <div class="form-group ">
                                <label>Дата публикации</label>
                                <input type="text"
                                       value="{{ $data->id != '' ? $data->published_at->format('d.m.Y') : '' }}"
                                       name="published_at"
                                       class="form-control air-datepicker"
                                       title=""/>
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
