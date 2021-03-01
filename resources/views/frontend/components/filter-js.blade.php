<script>
    (function () {
        $('body').on('click', '.product-more', function () {
            let btn = $(this);
            let page = btn.attr('data-page');
            let wrap = btn.parent();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "?page=" + page,
                method: "GET",
                data: $('#FilterForm').serialize(),
                success: function (data) {
                    if (data.view) {
                        btn.remove();
                        wrap.append(data.view);
                    }
                },
            });
        });

        $('input[name="in_stock"]').on('click', function () {
            $('#FilterForm').submit();
        });

        $('.filter-reset').on('click', function (e) {
            e.preventDefault();
            let url = window.location.href.split('?')[0];
            location.replace(url);
        });

        let filter_item = document.querySelectorAll('.filter-item');
        let i;
        if (filter_item.length > 0) {
            for(i=0; i < filter_item.length; i++) {
                let item = filter_item[i];

                let item_label = item.querySelector('.filter-item__label');
                let item_reset = item.querySelector('.filter-item__reset');

                $(item.querySelector('.f-caption')).overlayScrollbars({});

                if (item_label) {
                    item_label.addEventListener('click', function () {

                        [...filter_item].map(item => item.classList.remove('show'));

                        if (item.classList.contains('show')) {
                            item.classList.remove('show');
                        } else {
                            item.classList.add('show');
                        }
                    });
                }

                if (item_reset) {
                    item_reset.addEventListener('click', function () {
                        item.classList.remove('show');
                        clearFilters(item);
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

        function clearFilters(parentNode = null)
        {
            if (parentNode) {
                clearFilterItem(parentNode);
            } else {
                if (filter_item.length > 0) {
                    for(i=0; i < filter_item.length; i++) {
                        let parentNode = filter_item[i];
                        clearFilterItem(parentNode);
                    }
                }
            }
        }

        function clearFilterItem(parentNode)
        {
            let j;
            let inputs = parentNode.querySelectorAll('input');
            let rangeSlider = parentNode.querySelectorAll('.price-range-ui__line')[0];

            for (j=0; j < inputs.length; j++) {
                inputs[j].checked = false
            }

            if (rangeSlider) {
                rangeSlider.noUiSlider.set([0,600000]);
            }
        }
    })()
</script>
