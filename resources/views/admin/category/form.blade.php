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
                            <li class="breadcrumb-item"><a href="{{ route('admin.category.index') }}">Категории</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data ? ($data->name ?? 'Категория') : 'Добавить категорию' }}</a></li>
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
                                <label>Категория</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ $data->name }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ image ------>
                            <div class="form-group mb-0">
                                <label>Фото ( {{ $data->getRecommendedSize() }} )</label>
                                <div>
                                    <input type="file"
                                           data-buttontext="Выбирете изображение"
                                           data-placeholder="Файла нет"
                                           class="d-block"
                                           name="image" value="{{ $data->id ? $data->image : ''}}">
                                    @if(isset($data->image))
                                        <div class="img__downloaded max-width-200">
                                            <div class="img__downloaded__remove"></div>
                                            <input type="hidden" name="image" value="{{$data->image}}">
                                            <img src="{{ $data->getImg('image', 'thumb') }}" alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">

                            <!------ stock_id ------>
                            @if(count($stocks) > 0)
                                <div class="form-group">
                                    <label>Выберите акцию</label>
                                    <select class="select2_with_search admin-form-control" name="stock_id"
                                            title="">
                                        <option value="  ">Нет акции</option>
                                        @foreach($stocks as $stock)
                                            <option value="{{ $stock->iid }}" {{ $stock->iid == $data->stock_id ? 'selected' : '' }}>{{ $stock->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!------ categories ------>

                            <?php
                                if (count($data->options) > 0) {
                                    $optionIds = $data->options->pluck('id')->toArray();
                                }else {
                                    $optionIds = [];
                                }

                                if (count($data->singleOptions) > 0) {
                                    $singleOptionIds = $data->singleOptions->pluck('id')->toArray();
                                }else {
                                    $singleOptionIds = [];
                                }

                                if (count($data->brands) > 0) {
                                    $brandIds = $data->brands->pluck('iid')->toArray();
                                }else {
                                    $brandIds = [];
                                }
                            ?>

                            <div class="form-group">
                                <label>Опции</label>
                                <div class="nestable-checkbox">
                                    <button type="button" class="nestable-checkbox__collapse"></button>
                                    <ul class="ul">
                                        @if(count($options) > 0)
                                            @foreach($options as $option)
                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="options[]" {{ in_array($option->id, $optionIds) ? 'checked' : '' }} value="{{ $option->id }}">
                                                        {{ $option->name }}
                                                        <span style="opacity: 0.8; font-size: 12px;">многотипный</span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        @endif
                                        @if(count($singleOptions) > 0)
                                            @foreach($singleOptions as $singleOption)
                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="singleOptions[]" {{ in_array($singleOption->id, $singleOptionIds) ? 'checked' : '' }} value="{{ $singleOption->id }}">
                                                        {{ $singleOption->name }}
                                                        <span style="opacity: 0.8; font-size: 12px;">однотипный</span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label>Бренды ({{ count($brandIds) }})</label>
                                <div class="nestable-checkbox">
                                    <button type="button" class="nestable-checkbox__collapse"></button>
                                    <ul class="ul">
                                        @if(count($brands) > 0)
                                            @foreach($brands as $brand)
                                                <li>
                                                    <label>
                                                        <input type="checkbox" name="brands[]" {{ in_array($brand->iid, $brandIds) ? 'checked' : '' }} value="{{ $brand->iid }}">
                                                        {{ $brand->name }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
