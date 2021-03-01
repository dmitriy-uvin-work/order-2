@extends('frontend.layout.master')

@section('section')
    <div class="login-page page-gap">
        <div class="card">
            <div class="card-body card-body--large">
                <div class="card-nav">
                    <a href="javascript:" class="active">Сброс пароля</a>
                </div>
                <form action="{{ route('profile.restore.post') }}" method="post">
                    <div class="form-group">
                        <input type="text"
                               name="login"
                               class="form-control"
                               placeholder="Email или телефон"
                               title=""
                               autocomplete="off">
                    </div>
                    <div class="font-style--min c--main-grey mt--20 mb-30">
                        Введите email или номер телефона чтобы получить код проверки
                    </div>
                    <button type="submit" class="btn btn--black">Отправить</button>
                </form>
            </div>
        </div>
    </div>
@endsection

