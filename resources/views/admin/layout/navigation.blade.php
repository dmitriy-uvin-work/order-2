<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="{{ route('admin.home') }}" class="b-brand">
                <div class="b-bg">
                    <i class="feather icon-trending-up"></i>
                </div>
            </a>
            <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li class="nav-item pcoded-menu-caption">
                    <label>Заказы</label>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.user.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-user"></i>
                        </span>
                        <span class="pcoded-mtext">Пользователи</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.order.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-monitor"></i>
                        </span>
                        <span class="pcoded-mtext">Заказы</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.region.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-life-buoy"></i>
                        </span>
                        <span class="pcoded-mtext">Доставка</span>
                    </a>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>Regos</label>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.product.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-zap"></i>
                        </span>
                        <span class="pcoded-mtext">Товары</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.stock.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-percent"></i>
                        </span>
                        <span class="pcoded-mtext">Акции и скидки</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.brand.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-feather"></i>
                        </span>
                        <span class="pcoded-mtext">Бренды</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.color.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-disc"></i>
                        </span>
                        <span class="pcoded-mtext">Цвета</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.country.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-flag"></i>
                        </span>
                        <span class="pcoded-mtext">Страны мира</span>
                    </a>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>Menu 1</label>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.category.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-list"></i>
                        </span>
                        <span class="pcoded-mtext">Категории</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.banner.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-layers"></i>
                        </span>
                        <span class="pcoded-mtext">Баннер</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.menu.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-menu"></i>
                        </span>
                        <span class="pcoded-mtext">Подменю</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.post.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-edit"></i>
                        </span>
                        <span class="pcoded-mtext">Блог</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.page.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-package"></i>
                        </span>
                        <span class="pcoded-mtext">Страницы</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.tag.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-tag"></i>
                        </span>
                        <span class="pcoded-mtext">Теги</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.option.index') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-octagon"></i>
                        </span>
                        <span class="pcoded-mtext">Опции</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.setting.form') }}" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-settings"></i>
                        </span>
                        <span class="pcoded-mtext">Настройки сайта</span>
                    </a>
                </li>

                <li class="nav-item pcoded-menu-caption">
                    <label>Действии</label>
                </li>
                <li class="nav-item">
                    <a href="javascript:" onclick="getElementById('logout-form').submit()" class="nav-link ">
                        <span class="pcoded-micon">
                            <i class="feather icon-power"></i>
                        </span>
                        <span class="pcoded-mtext">Выйти</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="post" hidden>{{ csrf_field() }}</form>
                </li>
            </ul>
        </div>
    </div>
</nav>
