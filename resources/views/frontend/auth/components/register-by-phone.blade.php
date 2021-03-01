<form action="{{ route('profile.send-activate-code') }}" method="post">
    <div class="form-group form-group--phone">
        <div class="type-phone">
            <input type="text"
                   name="phone"
                   class="form-control"
                   placeholder="Телефон"
                   value="{{ old('phone') }}"
                   title=""
                   maxlength="9"
                   autocomplete="off"
                   required>
            <span>+998</span>
        </div>
    </div>
    <div class="font-style--min c--main-grey mt--20 mb-30">Введите номер телефона чтобы
        получить код активации
    </div>
    <button type="submit" class="btn btn--black" id="sendCodeBtn">Получить код активации</button>
    <div class="register-if-not flex-wrap">
        <div class="d-flex mb-15 font-12">
            <span>Нет номер телефона</span>
            <a href="{{ route('profile.register', ['by'=>'email']) }}" class=""
               style="text-decoration: none">Регистрация через email</a>
        </div>
        <div class="d-flex">
            <span>Уже регистрировались?</span>
            <a href="{{ route('profile.login') }}">Войти</a>
        </div>
    </div>
</form>
