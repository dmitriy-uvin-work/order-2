<form action="{{ route('profile.send-activate-code') }}" method="post">
    <div class="form-group">
        <input type="email"
               name="email"
               class="form-control"
               placeholder="Email"
               value="{{ old('email') }}"
               title=""
               autocomplete="off"
               required>
    </div>
    <div class="font-style--min c--main-grey mt--20 mb-30">Введите email чтобы получить код
        активации
    </div>
    <button type="submit" class="btn btn--black" id="sendCodeBtn">Получить код активации</button>
    <div class="register-if-not flex-wrap">
        <div class="d-flex mb-15 font-12">
            <span>Нет номер телефона</span>
            <a href="{{ route('profile.register') }}" class=""
               style="text-decoration: none">Регистрация через номер телефона</a>
        </div>
        <div class="d-flex">
            <span>Уже регистрировались?</span>
            <a href="{{ route('profile.login') }}">Войти</a>
        </div>
    </div>
</form>
