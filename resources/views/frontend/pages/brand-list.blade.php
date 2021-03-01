@extends('frontend.layout.master')

@section('section')
    <div class="medium-container page-gap">
        <div class="main-title">
            <h2>Бренды</h2>
        </div>

        <div class="page-column align-items-end">
            <div class="page-column__left mb-0">
                <div class="filter-item mb-30" data-filter="country">
                    <div class="filter-item__label">
                        <div>Все страны</div>
                        <span></span>
                    </div>
                    <div class="filter-item__dropDown">
                        <div class="f-caption">
                            @if(count($countries) > 0)
                                @foreach($countries as $country)
                                    <div class="f-value font-12">
                                        <label class="checkbox-ui">
                                            <input type="radio" name="country" value="{{ $country->id }}">
                                            <span class="checkbox-ui__figure"></span><span>{{ $country->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="f-footer">
                            <button class="filter-item__reset c--main-grey">
                                Сбросить
                            </button>
                            <button class="filter-item__apply btn btn--black btn--small">
                                Применить
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <input type="text" name="q" placeholder="Найти бренд" title="" class="search-input" autocomplete="off">
                </div>
            </div>
            <?php /*<div class="page-column__right mobile-hide">
                <div class="h3 mb-20">Вы можете добавлять бренды в избранное</div>
                <p class="c--main-grey mt-0 mb-0">Просто ставьте лайки напротив любимого бренда, и он появится в вашем избранном</p>
            </div>*/ ?>
        </div>

        @if(count($grouped)>0)
        <div class="brands-grid">
            <div class="row">
                @foreach($grouped as $key => $group)
                    <div class="col-lg-6 col-md-12 brands-grid__group" data-char="{{ $key }}">
                        <ul>
                            @foreach($group as $item)
                                <li data-country-id="{{ $item->country->id }}"><span></span><a href="{{ route('brand.view', ['id'=>$item->id]) }}">{{$item->name}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
        @else
            @component('frontend.components.empty-data', ['text'=>'В этом разделе скоро появятся бренды']) @endcomponent
        @endif
    </div>
@endsection


@section('js')
    <script>
        (function () {

            let input = $('.search-input');
            let query = "";
            let brands = $('.brands-grid__group li');
            let group = $('.brands-grid__group');
            let country_id = $('[data-filter="country"]').find('input:checked').val() ?? "";

            let filter_item = document.querySelectorAll('.filter-item');
            let i;
            let j;

            if (filter_item.length > 0) {
                for(i=0; i < filter_item.length; i++) {
                    let item = filter_item[i];

                    let parentNode = item.closest('.filter-item');

                    let item_label = item.querySelector('.filter-item__label');
                    let item_reset = item.querySelector('.filter-item__reset');
                    let item_apply = item.querySelector('.filter-item__apply');

                    $(parentNode.querySelector('.f-caption')).overlayScrollbars({});

                    if (item_label) {
                        item_label.addEventListener('click', function () {

                            [...filter_item].map(item => item.classList.remove('show'));

                            if (parentNode.classList.contains('show')) {
                                parentNode.classList.remove('show');
                            } else {
                                parentNode.classList.add('show');
                            }
                        });
                    }

                    if (item_reset) {
                        item_reset.addEventListener('click', function () {
                            parentNode.classList.remove('show');

                            let inputs = parentNode.querySelectorAll('input');

                            for (j=0; j < inputs.length; j++) {
                                inputs[j].checked = false
                            }

                            country_id = "";

                            applyFilter(query);
                        });
                    }

                    if (item_apply) {
                        item_apply.addEventListener('click', function () {
                            parentNode.classList.remove('show');

                            country_id = $('[data-filter="country"]').find('input:checked').val() ?? "";
                            applyFilter(query);
                        });
                    }

                }

                let filter_item_jquery = $(filter_item);

                window.addEventListener('mouseup', e => {
                    if (!filter_item_jquery.is(e.target) // если клик был не по нашему блоку
                        && filter_item_jquery.has(e.target).length === 0) { // и не по его дочерним элементам
                        filter_item_jquery.removeClass('show');
                    }
                });
            }


            input.on('keyup', function () {
                query = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
                applyFilter(query);
            });

            function applyFilter(query) {
                if (country_id !== "") {
                    brands.show().filter(function () {
                        let text = $(this).find('a').text().replace(/\s+/g, ' ').toLowerCase();
                        let id = $(this).attr('data-country-id');

                        return !~text.indexOf(query) || id !== country_id;
                    }).hide();
                } else {
                    brands.show().filter(function () {
                        let text = $(this).find('a').text().replace(/\s+/g, ' ').toLowerCase();
                        return !~text.indexOf(query);
                    }).hide();
                }

                group.show().filter(function () {
                    let el = $(this).find('a:visible');
                    return el.length === 0;
                }).hide();
            }

        })();
    </script>
@endsection
