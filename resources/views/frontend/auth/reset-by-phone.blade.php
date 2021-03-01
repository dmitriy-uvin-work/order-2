@extends('frontend.layout.master')

@section('section')
    <div class="login-page page-gap">
        <div class="card">
            <div class="card-body card-body--large">
                <div class="card-nav">
                    <a href="#" class="active">Изменить пароль</a>
                </div>
                <form action="" method="post">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="text"
                               name="phone"
                               class="form-control"
                               placeholder="Телефон"
                               title=""
                               autocomplete="off"
                               value="{{ $phone }}"
                               readonly>
                    </div>
                    <div class="form-group">
                        <div class="type-password">
                            <span class="type-password__eye"></span>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Придумайте пароль"
                                   title=""
                                   autocomplete="off"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="type-password">
                            <span class="type-password__eye"></span>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Повторить пароль"
                                   title=""
                                   autocomplete="off"
                                   required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text"
                               name="phone_confirm"
                               class="form-control"
                               placeholder="Проверочный SMS код"
                               title=""
                               maxlength="4"
                               autocomplete="off">
                    </div>
                    <button type="submit" class="btn btn--black">Изменить пароль</button>
                </form>
            </div>
        </div>
    </div>
@endsection
