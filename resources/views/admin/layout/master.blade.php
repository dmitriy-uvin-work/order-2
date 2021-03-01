<!DOCTYPE html>
<html lang="en">

<head>
    <title>Панель управление | Beautyholic</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Favicon icon -->
    <link rel="icon" href="/admin-panel/favicon.png" type="image/png">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="/admin-panel/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="/admin-panel/plugins/animation/css/animate.min.css">
    <!-- select2 css -->
    <link rel="stylesheet" href="/admin-panel/plugins/select2/select2.min.css">
    <!-- summernote css -->
    <link rel="stylesheet" href="/admin-panel/plugins/summernote/summernote-lite.css">
    <!-- air-datepicker css -->
    <link rel="stylesheet" href="/admin-panel/plugins/air-datepicker/datepicker.min.css">
    <!-- nestable css -->
    <link rel="stylesheet" href="/admin-panel/plugins/nestable/nestable.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="/admin-panel/css/style.css">
    <!-- alert css -->
    <link rel="stylesheet" href="/admin-panel/css/alert.css">
    <!-- custom css -->
    <link rel="stylesheet" href="/admin-panel/css/custom.css">

    @yield('styles')

</head>

<body>
<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<!-- [ navigation menu ] start -->
@include('admin.layout.navigation')
<!-- [ navigation menu ] end -->

<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <div class="pcoded-content">
            @yield('section')
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->


<!-- Required Js -->
<script src="/admin-panel/js/vendor-all.min.js"></script>
<script src="/admin-panel/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/admin-panel/plugins/select2/select2.min.js"></script>
<script src="/admin-panel/plugins/summernote/summernote-lite.js"></script>
<script src="/admin-panel/plugins/summernote/summernote-cleaner.js"></script>
<script src="/admin-panel/plugins/vanilla-picker/vanilla-picker.min.js"></script>
<script src="/admin-panel/plugins/air-datepicker/datepicker.min.js"></script>
<script src="/admin-panel/plugins/slugify/slugify.js"></script>
<script src="/admin-panel/plugins/nestable/nestable.min.js"></script>
<script src="/admin-panel/js/pcoded.min.js"></script>
<script src="/admin-panel/js/custom.js"></script>

<script>
    function ajaxErrorMessage(error) {
        if (typeof error.responseJSON === 'object' && error.responseJSON !== null) {
            if (typeof error.responseJSON.message !== 'undefined') {
                alert(error.responseJSON.message);
            }
            if (typeof error.responseJSON.error !== 'undefined') {
                alert(error.responseJSON.error);
            }
        }
    }
</script>

@include('admin.parts.modal')
@include('admin.parts.alert')

@yield('scripts')
@stack('scripts')

<script>

    $(document).ready(function () {
        $('.img__downloaded__remove').on('click', function () {
            let parent = $(this).parent();
            $(this).remove();
            parent.hide();
            parent.find('img').remove();
            parent.find('input').val('');
        });

        $('.file-input__clear').on('click', function () {
            let inp = $(this).next().find('input');
            let parent = $(this).parent();

            destroyImage(inp.attr('data-path'), callback());

            function callback() {
                parent.empty();
                parent.removeAttr('style');
                parent.append('<label>\n' +
                    '<input\n' +
                    ' type="file"\n' +
                    ' name="gallery[]"\n' +
                    ' value="">\n' +
                    '</label>')
            }
        });
    });

    function destroyImage(path, callback) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.destroy-image') }}",
            method: 'POST',
            data: {path:path},
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    callback();
                }
            },
            error: function () {

            }
        })
    }
</script>

</body>
</html>
