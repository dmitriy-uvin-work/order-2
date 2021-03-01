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
                            <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Товары</a></li>
                            <li class="breadcrumb-item"><a href="javascript:">{{ $data->id ? $data->name : 'Добавить товар' }}</a></li>
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
                                <label>Название</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="{{ $data->name }}"
                                       autocomplete="off"
                                       readonly
                                       title="">
                            </div>

                            <!------ alt name ------>
                            <div class="form-group">
                                <label>Альтернативное название</label>
                                <input type="text"
                                       class="form-control"
                                       name="alt_name"
                                       value="{{ old('alt_name') ?? $data->alt_name }}"
                                       autocomplete="off"
                                       title="">
                            </div>

                            <!------ description ------>
                            <div class="form-group">
                                <label>Описание</label>
                                <textarea class="form-control min-height-100"
                                          name="description"
                                          id="description"
                                          title="">{{ old('description') ?? $data->description }}</textarea>
                                <p class="inform-text text-right">Количество символов(макс 1400): <span id="countDescription"></span></p>
                            </div>

                            <!------ information ------>
                            <div class="form-group">
                                <label>Характеристики</label>
                                <textarea class="form-control min-height-100"
                                          name="information"
                                          id="information"
                                          title="">{{ old('information') ?? $data->information }}</textarea>
                                <p class="inform-text text-right">Количество символов(макс 1400): <span id="countInformation"></span></p>
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
                    <a href="{{ route('product.view', ['slug'=>$data->slug]) }}" target="_blank" class="btn btn-primary">На сайте</a>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <!------ iid ------>
                                    <div class="form-group">
                                        <label>Regos ID</label>
                                        <input type="text"
                                               class="form-control"
                                               name="iid"
                                               value="{{ old('iid') ?? $data->iid }}"
                                               autocomplete="off"
                                               readonly
                                               title="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!------ quantity ------>
                                    <div class="form-group">
                                        <label>Количество</label>
                                        <input type="text"
                                               class="form-control"
                                               name="quantity"
                                               value="{{ old('quantity') ?? $data->quantity }}"
                                               autocomplete="off"
                                               readonly
                                               title="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!------ price ------>
                                    <div class="form-group">
                                        <label>Цена</label>
                                        <input type="text"
                                               class="form-control"
                                               name="price"
                                               value="{{ old('price') ?? $data->price }}"
                                               autocomplete="off"
                                               readonly
                                               title="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!------ volume ------>
                                    <div class="form-group">
                                        <label>Объем (мл)</label>
                                        <input type="text"
                                               class="form-control"
                                               name="volume"
                                               value="{{ old('volume') ?? $data->volume }}"
                                               autocomplete="off"
                                               title="">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Цвет</label>
                                <input type="text"
                                       class="form-control"
                                       name="color"
                                       value="{{ $data->color->getName() }}"
                                       autocomplete="off"
                                       readonly
                                       title="">
                            </div>

                            <!------ brand_id ------>
                            @if(count($brands) > 0)
                                <div class="form-group">
                                    <label>Выберите бренд</label>
                                    <select class="select2_with_search admin-form-control" name="brand_id"
                                            title="">
                                        <option value="null">Нет бренда</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->iid }}" {{ $brand->iid == $data->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

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
                                if (count($data->categories) > 0) {
                                    $categoryIds = $data->categories->pluck('id')->toArray();
                                }else {
                                    $categoryIds = [];
                                }
                            ?>
                            <div class="form-group">
                                <label>Категории</label>
                                <div class="nestable-checkbox">
                                    <button type="button" class="nestable-checkbox__collapse"></button>
                                    <?php
                                    $categoryIds = $data->categories->pluck('id')->toArray();
                                    ?>
                                    @include('admin.product.sub-select', ['categories' => $categories, 'categoryIds' => $categoryIds, 'level'=>0])
                                </div>
                            </div>

                            <!------ options ------>
                            @if (count($options) > 0)
                                @php
                                    $optionIds = $data->options->pluck('id')->toArray();
                                @endphp

                                <div class="form-group">
                                    <label>Опции ({{ count($optionIds) }})</label>
                                    <div class="nestable-checkbox">
                                        <button type="button" class="nestable-checkbox__collapse"></button>
                                        <ul>
                                            @if(count($options) > 0)
                                            @foreach($options as $optGroup)
                                                <li class="optgroup">{{ $optGroup->name }}</li>
                                                <ul>
                                                    @foreach($optGroup->values as $value)
                                                        <li>
                                                            <label><input type="checkbox" name="options[]" value="{{ $value->id }}" {{ in_array($value->id, $optionIds) ? 'checked' : '' }}>{{ $value->name }}</label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endforeach
                                            @endif

                                            @if(count($singleOptions) > 0)
                                            @foreach($singleOptions as $value)
                                                <li>
                                                    <label><input type="checkbox" name="options[]" value="{{ $value->id }}" {{ in_array($value->id, $optionIds) ? 'checked' : '' }}>{{ $value->name }}</label>
                                                </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <!------ image ------>
                            <div class="form-group">
                                <label>Фото ( {{ $data->getRecommendedSize() }} )</label>
                                <div>
                                    <input type="file"
                                           data-buttontext="Выбирете изображение"
                                           data-placeholder="Файла нет"
                                           id="uploadPhoto"
                                           name="image" value="{{ $data->id ? $data->image : ''}}">
                                    <br>
                                    <small id="errorUpload" style="display: none" class="text-c-red">Загружаемый файл превышает 1мб</small>
                                    @if(isset($data->image))
                                        <div class="img__downloaded">
                                            <div class="img__downloaded__remove"></div>
                                            <input type="hidden" name="image" value="{{$data->image}}">
                                            <img src="{{ $data->getImg('image', 'medium') }}" alt="">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!------ gallery ------>
                            <?php
                            $gallery = json_decode($data->gallery);

                            if (!$gallery) {
                                $gallery = [];
                            }
                            $diff = 4 - count($gallery);
                            if ($diff > 0) {
                                for ($i = 0; $i < $diff; $i++) {
                                    $gallery[] = null;
                                }
                            }
                            ?>

                            <div class="form-group">
                                <div class="label">Галерея ( {{ $data->getRecommendedSize() }} )</div>
                                <div class="file-inputs">
                                    @foreach($gallery as $gallery_item)
                                        @if($gallery_item)
                                            <div class="file-input" style="background-image: url({{ $data->getImage('gallery', $gallery_item, 'medium') }})">
                                                <input type="hidden" name="gallery[]" value="{{ $gallery_item }}">
                                                <div class="file-input__clear">
                                                    <img src="/admin-panel/images/icons/icon-trash-white.svg" alt="">
                                                </div>
                                                <label>
                                                    <input type="file" name="gallery[]" data-path="{{ $data->getImage('gallery', $gallery_item) }}" value="{{ $data->id ? $gallery_item ?? '' : '' }}">
                                                </label>
                                            </div>
                                        @else
                                            <div class="file-input">
                                                <label>
                                                    <input type="file" name="gallery[]" value="{{ $data->id ? $gallery_item ?? '' : '' }}">
                                                </label>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!------ status ------>
                            <div class="form-group form-check mb-0">
                                @if(isset($data->status))
                                    <input type="checkbox" class="form-check-input" id="statusLabel"
                                           name="status" {{ $data->status == 1 ? 'checked' : '' }}>
                                @else
                                    <input type="checkbox" class="form-check-input" id="statusLabel" name="status"
                                           checked>
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

@push('scripts')
    <script>

        $('#uploadPhoto').bind('change', function() {
            if (this.files[0].size > 1000000) {
                $('#errorUpload').show();
                $("#uploadPhoto").val("")
            } else {
                $('#errorUpload').hide();
            }
        });

        let countSymbol = 1400;
        checkLength($("#description"), $('#countDescription'), countSymbol);
        stringLength($("#description"), $('#countDescription'), countSymbol);
        checkLength($("#information"), $('#countInformation'), countSymbol);
        stringLength($("#information"), $('#countInformation'), countSymbol);

        function checkLength(selector, counter, countSymbol) {
            selector.keyup(function(){
                var box = $(this).val();
                var count = box.length;
                if (count > countSymbol) {
                    $(this).css('border-color', 'red')
                } else {
                    $(this).removeAttr('style')
                }
                counter.html(count);
            });
        }

        function stringLength(selector, counter, countSymbol) {
            var box = selector.val();
            var count = box.length;
            if (count > countSymbol) {
                selector.css('border-color', 'red')
            } else {
                selector.removeAttr('style')
            }
            counter.html(count);
        }
    </script>
@endpush
