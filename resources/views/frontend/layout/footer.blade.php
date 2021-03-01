<footer>
    <div class="footer">
        <div class="medium-container">
            <div class="row footer__top">
                <div class="col-md-6 footer__first-column">
                    <div class="logo">
                        <img src="/frontend/images/logo-white.svg" alt="">
                    </div>
                    <div class="circle-socials">
                        @if(!empty($settings->instagram))
                            <a href="{{ $settings->instagram }}" target="_blank" class="circle-socials__item"><img src="/frontend/images/icons/instagram.svg" alt=""></a>
                        @endif
                        @if(!empty($settings->telegram))
                            <a href="{{ $settings->telegram }}" target="_blank" class="circle-socials__item"><img src="/frontend/images/icons/telegram.svg" alt=""></a>
                        @endif
                        @if(!empty($settings->vk))
                            <a href="{{ $settings->vk }}" target="_blank" class="circle-socials__item"><img src="/frontend/images/icons/vk.svg" alt=""></a>
                        @endif
                        @if(!empty($settings->facebook))
                            <a href="{{ $settings->facebook }}" target="_blank" class="circle-socials__item"><img src="/frontend/images/icons/facebook.svg" alt=""></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <nav>
                                <ul>
                                    <li><a href="{{ route('stock.list') }}">Акции</a></li>
                                    <li><a href="#">Подарочный сертификат</a></li>
                                    <li><a href="{{ route('help') }}">Помощь</a></li>
                                    <li><a href="{{ route('page.view', ['slug'=>'kontakty']) }}">Контакты</a></li>
                                    <li><a href="{{ route('blog.list') }}">Блог</a></li>
                                    <li><a href="{{ route('catalog') }}">Каталог</a></li>
                                </ul>
                            </nav>
                        </div>
                        <div class="col-md-6 contacts">
                            <div><a href="tel:998990000000" class="font-500">{{ $settings->phone }}</a></div>
                            <div><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></div>
                            <div>Адрес: {{ $settings->address }}</div>
                            <div>Режим работы: {{ $settings->working_hours }}</div>
                            <div class="font-12 font-300 mt-50"><a href="{{ $settings->policy_link }}" target="_blank">Политика обработки персональных данных</a></div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-45 mb-45">

            <div class="footer__bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-40">
                    <div class="font-300">© 2007-2020 Все права защищены.</div>
                    <div class="payments">
                        <a href="#"><img src="/frontend/images/icons/click-white.png" alt=""></a>
                        <a href="#"><img src="/frontend/images/icons/payme-white.png" alt=""></a>
                    </div>
                </div>
                <div class="font-05 font-300 font-style--min">
                    {{ $settings->about }}
                </div>
                <div class="developed-by">
                    <a href="https://goldenminds.uz/" target="_blank">Разработка сайта</a>&nbsp;
                    <a href="https://goldenminds.uz/" target="_blank"><img src="/frontend/images/logo/goldenminds.svg" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</footer>
