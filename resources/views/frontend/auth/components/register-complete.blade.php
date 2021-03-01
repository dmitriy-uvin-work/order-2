<form action="{{ route('profile.register.post') }}" method="post" id="postRegister">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <input type="text" name="name" class="form-control" placeholder="Имя" title=""
               autocomplete="off" value="{{ old('name') }}">
    </div>
    <div class="form-group">
        <input type="text" name="surname" class="form-control" placeholder="Фамилия" title=""
               autocomplete="off" value="{{ old('surname') }}">
    </div>

    <div class="form-group">
        <input type="text" name="value" class="form-control" title=""
               autocomplete="off" value="{{ $value }}">
    </div>

    <div class="form-group">
        <div class="type-password">
            <span class="type-password__eye"></span>
            <input type="password" name="password" class="form-control" placeholder="Пароль"
                   title="" autocomplete="off">
        </div>
    </div>
    <div class="form-group">
        <div class="type-password">
            <span class="type-password__eye"></span>
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Повторить пароль" title="" autocomplete="off">
        </div>
    </div>
    <div class="mt-45 mb-45">
        <a href="{{ $settings->policy_link }}" target="_blank" class="form-link">Нажимая на кнопку,
            вы принимаете <span class="c--main-black">условия Политики</span> и даете согласие на
            Обработку
            персональных данных</a>
    </div>
    <button type="submit" class="btn btn--black">Зарегистрироваться</button>
</form>
