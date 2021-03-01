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
                            <li class="breadcrumb-item"><a href="{{ route('admin.stock.index') }}">Акции</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->name : 'Создать акцию'}}</a></li>
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
                                       value="{{ old('title') ?? $data->name }}"
                                       autocomplete="off"
                                       readonly
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

                            <!------ body ------>
                            <div class="form-group mb-0">
                                <label>Условия акции</label>
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

                            <!------ discount ------>
                            <div class="form-group">
                                <label>Скидка %</label>
                                <input type="text"
                                       class="form-control"
                                       name="discount"
                                       value="{{ old('discount') ?? $data->discount }}"
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

                            <!------ color ------>
                            <div class="form-group">
                                <label>Цвет текста под фото</label>
                                <select class="form-control select2_with_search" name="color" title="">
                                    <option value="black" {{ $data->color == 'black' ? 'selected' : '' }}>black</option>
                                    <option value="white" {{ $data->color == 'white' ? 'selected' : '' }}>white</option>
                                </select>
                            </div>

                            <!------ started_at ------>
                            <div class="form-group ">
                                <label>Дата начала акции</label>
                                <input type="text"
                                       value="{{ $data->id != '' ? $data->started_at->format('d.m.Y H:i') : '' }}"
                                       name="started_at"
                                       class="form-control datepicker-here"
                                       data-time-format="hh:ii" data-timepicker="true"
                                       disabled
                                       title=""/>
                            </div>

                            <!------ ended_at ------>
                            <div class="form-group ">
                                <label>Дата конца акции</label>
                                <input type="text"
                                       value="{{ $data->id != '' ? $data->ended_at->format('d.m.Y H:i') : '' }}"
                                       name="ended_at"
                                       class="form-control datepicker-here"
                                       data-time-format="hh:ii" data-timepicker="true"
                                       disabled
                                       title=""/>
                            </div>

                            <!------ site status ------>
                            <div class="form-group form-check mb-0 mr-3">
                                <input type="checkbox" class="form-check-input" id="statusActiveLabel" name="site_active" {{ $data->site_active == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusActiveLabel">Активный на сайте</label>
                            </div>

                            <!------ status ------>
                            <div class="form-group form-check mb-0 mr-3">
                                @if(isset($data->active))
                                    <input type="checkbox" class="form-check-input" id="statusLabel" name="active" disabled {{ $data->active == 1 ? 'checked' : '' }}>
                                @else
                                    <input type="checkbox" class="form-check-input" id="statusLabel" name="active" disabled checked>
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
