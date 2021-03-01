@extends('frontend.layout.master')

@section('section')
<div class="b--main-light-grey">
    <div class="login-page page-gap">
        <div class="card">
            <div class="card-body card-body--large">
                <div class="card-nav">
                    <a href="javascript:" class="active">Вход</a>
                    <div class="divider"></div>
                    <a href="{{ route('profile.register') }}">Регистрация</a>
                </div>
                <form action="{{ route('profile.login.post') }}" method="post">
                    <div class="form-group">
                        <input type="text"
                               name="login"
                               class="form-control"
                               placeholder="Email или телефон"
                               title=""
                               autocomplete="off">
                    </div>
                    <div class="form-group">
                        <div class="type-password">
                            <span class="type-password__eye"></span>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Пароль"
                                   title="">
                        </div>
                    </div>
                    <div class="mt-45 mb-45">
                        <a href="{{ route('profile.restore') }}" class="form-link">Восстановить пароль</a>
                    </div>
                    <button type="submit" class="btn btn--black">Войти</button>

                    <div class="register-if-not">
                        <span>Не зарегистрированы?</span>
                        <a href="{{ route('profile.register') }}">Зарегистрироваться</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div style="overflow: hidden">
        <div class="section-gap"></div>
    </div>
</div>
@endsection
