@if(isset($data->errorCode))
    <div class="uds-card uds-card--pink">
        <div class="uds-card__img"
             style="background-image: url(/frontend/images/pics/uds-logo.png)"></div>
        <div class="uds-card__content">
            <div class="text-left mr-0">
                <span class="title mb-10">Beauty Holic</span>
                <span class="note">
                    Вы ещё не присоединились к компанию. <br>
                    Скачайте приложение UDS.App и по ссылке
                    <a href="#">beautyholic.udsapp.uz/join?rel=22223</a> <br>
                    присодинитесь к компанию
                </span>
                <div class="uds-card__steps">
                    <a href="#"><img src="/frontend/images/pics/google-play.png" alt=""></a>
                    <a href="#"><img src="/frontend/images/pics/app-store.png" alt=""></a>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="uds-card uds-card--pink mb-20">
        <div class="uds-card__img"
             style="background-image: url(/frontend/images/pics/uds-logo.png)"></div>
        <div class="uds-card__content">
            <div class="text-left">
                <span class="title mb-5">Beauty Holic</span>
                <span class="note">{{ $data->user->displayName }}</span>
            </div>
            <div class="text-right">
                <span class="ball mb-3">{{ $data->user->participant->points }} б.</span>
                <span class="note">1балл = 1UZS</span>
            </div>
        </div>
    </div>
@endif


