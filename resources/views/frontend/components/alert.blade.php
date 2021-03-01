<div class="alert">
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            <div class="alert-item alert--warning">
                <div class="alert-icon"></div>
                <div class="alert-close"></div>
                <div class="alert-caption">
                    <div class="alert-message">
                        {!! $error !!}
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if (session()->has('error'))
        <div class="alert-item alert--warning">
            <div class="alert-icon"></div>
            <div class="alert-close"></div>
            <div class="alert-caption">
                <div class="alert-message">
                    {!! session()->get('error') !!}
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert-item alert--success">
            <div class="alert-icon"></div>
            <div class="alert-close"></div>
            <div class="alert-caption">
                <div class="alert-message">
                    {!! session()->get('success') !!}
                </div>
            </div>
        </div>
    @endif
</div>

<script>

    let hideAlert;

    function callAlert() {
        clearTimeout(hideAlert);

        let alertItem = body.find($('.alert-item'));

        if (alertItem.length > 0) {

            let container = $('.alert');

            container.fadeIn(function () {
                alertItem.each(function (index) {
                    let $this = $(this);
                    if (!$this.hasClass('showing')) {
                        setTimeout(function () {
                            $this.addClass('showing');
                        }, index*100);
                    }
                });
            });

            hideAlert = setTimeout(function () {
                clearItems();
            }, 4000);

            function clearItems() {

                alertItem.each(function (index) {
                    let $this = $(this);
                    setTimeout(function () {
                        $this.removeClass('showing');
                        if (alertItem.length === (index + 1)) {
                            setTimeout(function () {
                                alertItem.remove();
                                container.fadeOut();
                            }, 500)
                        }

                    }, index*100);
                });
            }
            container.on('click', function () {
                clearTimeout(hideAlert);
                clearItems();
            });
        }
    }

    // call notification
    $(document).ready(function () {
        // remove if view from browser cache
        if(performance.navigation.type === 2){
            alertItem.remove();
        }
        callAlert();
    });

    function ajaxAlert(type, message) {
        let container = $('.alert');

        switch (type) {
            case 'warning':
                container.append('<div class="alert-item alert--warning">\n' +
                    '                <div class="alert-icon"></div>\n' +
                    '                <div class="alert-close"></div>\n' +
                    '                <div class="alert-caption">\n' +
                    '                    <div class="alert-message">\n' +
                    '                        '+message+'\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>');
                break;
            case 'error':
                container.append('<div class="alert-item alert--error">\n' +
                    '                <div class="alert-icon"></div>\n' +
                    '                <div class="alert-close"></div>\n' +
                    '                <div class="alert-caption">\n' +
                    '                    <div class="alert-message">\n' +
                    '                        '+message+'\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>');
                break;
            case 'success':
                container.append('<div class="alert-item alert--success">\n' +
                    '                <div class="alert-icon"></div>\n' +
                    '                <div class="alert-close"></div>\n' +
                    '                <div class="alert-caption">\n' +
                    '                    <div class="alert-message">\n' +
                    '                        '+message+'\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>');
                break;
        }

        callAlert();
    }
</script>
