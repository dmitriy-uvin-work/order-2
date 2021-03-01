@extends('frontend.layout.master')

@section('section')
    <div class="b--main-light-grey">
        <div class="login-page page-gap">
            <div class="card">
                <div class="card-body card-body--large">
                    <div class="card-nav">
                        <a href="{{ route('profile.login') }}">Вход</a>
                        <div class="divider"></div>
                        <a href="javascript:" class="active">Регистрация</a>
                    </div>
                    <div class="register-ajax-wrap">
                        @if (request()->has('by') && request()->get('by') == 'email')
                            @include('frontend.auth.components.register-by-email')
                        @else
                            @include('frontend.auth.components.register-by-phone')
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div style="overflow: hidden">
            <div class="section-gap"></div>
        </div>
    </div>
@endsection

@section('js')
    <script>

        let container = $('.register-ajax-wrap');
        let bb = $('body');

        let timeOut = true;

        bb.on('click', '#sendCodeBtn', function (e) {
            e.preventDefault();
            let form = $(this).parent();

            if (timeOut) {
                timeOut = false;

                form.append("<div class='loader-ui loader-ui--triangle'></div>");

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: form.attr('action'),
                    method: "POST",
                    data: form.serialize(),
                    success: function (data) {
                        if (data.success) {
                            form.remove();
                            container.append(data.view);
                        }
                        timeOut = true;
                    },
                    error: function (error) {
                        if (typeof error.responseJSON === 'object' && error.responseJSON !== null) {
                            if (typeof error.responseJSON.message !== 'undefined') {
                                alert(error.responseJSON.message);
                            }
                            if (typeof error.responseJSON.error !== 'undefined') {
                                alert(error.responseJSON.error);
                            }
                        }
                        form.find('.loader-ui').remove();
                        timeOut = true;
                    }
                });
            }

        });

        bb.on('click', '#confirmCodeBtn', function (e) {
            e.preventDefault();
            let form = $(this).parent();

            form.append("<div class='loader-ui loader-ui--triangle'></div>");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: form.attr('action'),
                method: "POST",
                data: form.serialize(),
                success: function (data) {
                    if (data.success) {
                        form.remove();
                        container.append(data.view);
                    }
                },
                error: function (error) {
                    if (typeof error.responseJSON === 'object' && error.responseJSON !== null) {
                        if (typeof error.responseJSON.message !== 'undefined') {
                            alert(error.responseJSON.message);
                        }
                        if (typeof error.responseJSON.error !== 'undefined') {
                            alert(error.responseJSON.error);
                        }
                    }
                    form.find('.loader-ui').remove();
                }
            });
        });

    </script>
@endsection


{{--SQLSTATE[22001]: String data, right truncated: 7 ERROR: value too long for type character varying(9) (SQL: insert into "users" ("name", "surname", "phone", "password", "updated_at", "created_at") values (Xusan, Mirsharipov, h.mirsharipov@yandex.ru, $2y$10$nPEM7GcaHweueCF3x4mKXeYHUnhqjUYI4M/wXTY4ga6/XJKqkW17G, 2020-10-23 17:30:54, 2020-10-23 17:30:54) returning "id")--}}
