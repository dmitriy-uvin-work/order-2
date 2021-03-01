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
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            <input type="hidden" name="_previous" value="{{ url()->previous() }}">

                            <!------ name ------>
                            <div class="form-group">
                                <label>Заголовок</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ $data->name }}"
                                       autocomplete="off"
                                       readonly
                                       title="">
                            </div>

                            <!------ description ------>
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea class="form-control min-height-100"
                                          name="description"
                                          title="">{{ old('description') ?? $data->description }}</textarea>
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

                            <!------ country_id ------>
                            <?php
                                $country_item = $data->country_id;
                            ?>
                            <div class="form-group mb-0">
                                <label for="countryLabel">Страна бренда</label>
                                <select class="form-control select2_with_search" name="country_id" id="countryLabel">
                                    <option value="null">Нет страна</option>
                                    @foreach($countries as $country)
                                        @if($country_item != null)
                                            <option value="{{ $country->iid }}" {{ $country->iid == $country_item ? 'selected' : '' }}>{{ $country->name }}</option>
                                        @else
                                            <option value="{{ $country->iid }}">{{ $country->name }}</option>
                                        @endif
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
                            <!------ stock_id ------>
                            @if(count($stocks) > 0)
                                <div class="form-group">
                                    <label>Выберите акцию</label>
                                    <select class="select2_with_search admin-form-control" name="stock_id"
                                            title="">
                                        <option value=" ">Нет акции</option>
                                        @foreach($stocks as $stock)
                                            <option value="{{ $stock->iid }}" {{ $stock->iid == $data->stock_id ? 'selected' : '' }}>{{ $stock->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
