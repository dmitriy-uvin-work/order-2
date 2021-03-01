<form action="{{ route('profile.confirm-activate-code') }}" method="post">
    {{ csrf_field() }}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <input type="text"
               class="form-control"
               maxlength="9"
               title=""
               autocomplete="off"
               readonly
               value="{{ $value }}">
    </div>
    <div class="form-group">
        <input type="text"
               name="code"
               class="form-control"
               placeholder="Введите код активации"
               title=""
               maxlength="4"
               autocomplete="off">
    </div>
    <button type="submit" class="btn btn--black" id="confirmCodeBtn">Подтвердить код активации</button>
</form>
